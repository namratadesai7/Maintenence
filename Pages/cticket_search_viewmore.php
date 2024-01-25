<?php
include('../includes/dbcon.php');
if(isset($_POST['pending']) || isset($_POST['cfrom']) || isset($_POST['cto'])){
session_start();
$pending=$_POST['pending'];
$ticketno=$_POST['ticketno'];
$cfrom=$_POST['cfrom'];
$cto=$_POST['cto'];

$page = $_POST['start'] / $_POST['length'] + 1; // Adjust as needed
$length = $_POST['length'];

// Calculate OFFSET based on the current page and length
$offset = ($page - 1) * $length;

$condition="";

if( $_SESSION['urights']!="admin"){

  $condition.=" and (a.istransfer=0 or a.istransfer is null) and t.username='".$_SESSION['sname']."'";
}

if(!empty($cfrom) && !empty($cto)){
  $condition.=" and t.date between '$cfrom' and '$cto' ";
}


if(!empty($ticketno)){
  // $condition.=" AND a.ticket_id='$ticketno'  and (CONCAT(t.srno,'/',u.createdAt) in 
  //   (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL) and a.istransfer=0";   
  $condition.=" AND t.srno='$ticketno'  and (CONCAT(t.srno,'/',u.createdAt) in 
  (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL) and  (a.istransfer=0 or a.istransfer is null)";  
}
if(!empty($pending)){
    if($pending=="assigned"){
        // $condition.=" and assign_to is not null and (ticketid is null or ticketid in( select ticket_id from abc WHERE CNT=2) )and a.istransfer=0 and (u.Status<> 'closed' or u.status is Null)
        //  and ticketid not in (select ticketid from closed)";
    $condition.=" and ticket_id not in( select ticketid from pqr) and ticket_id  in(select ticketid from transfer)	and a.istransfer<>1 and a.istransfer is not null   or ticketid is null and assign_to is not null 
			     ";
    }else if($pending=="unassigned"){
        $condition.=" and assign_to is null";
    }
    else if($pending=="closed"){
        $condition.=" and resolved_time is not null and resolved_time <> '' and a.istransfer=0 ";
    }
    else if($pending=="delayed"){
        $condition.=" and approx_cdate <> '1900-01-01'";
    }
    else if($pending=="transferred"){
        // $condition.=" and u.istransfer=1 and a.istransfer=1 or ticketid in(select ticket_id from abc where cnt=1)";
        $condition.=" AND ticketid in(select ticket_id from abc ) and Status='transfer'
        and (CONCAT(t.srno,'/',a.createdAt) in 
        (select CONCAT(ticket_id,'/',min(createdAt)) from assign group by ticket_id) )  ";
    }  
}
$sql=" WITH abc as( 
  select  COUNT(*) AS cnt, ticketid from uwticket_head 
GROUP BY ticketid having
   SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0
),
XYZ as(SELECT COUNT(*) AS cnt, ticketid
  FROM uwticket_head 
  GROUP BY ticketid HAVING  COUNT(*) % 2 = 1 
  AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0
  ),
pqr as(SELECT COUNT(*) AS cnt, ticketid
FROM uwticket_head 
GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
),
transfer as(SELECT COUNT(*) AS cnt, ticketid
FROM uwticket_head 
GROUP BY ticketid HAVING  COUNT(*)  = 1 
AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 

) 
SELECT t.srno,format(t.date,'dd-MM-yyyy') as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,
t.image,t.audio,t.video,t.room,u.ticketid,a.assign_to,a.approx_time,a.unit,a.istransfer,a.update_assign,u.istransfer as utrans
,u.resolved_time,u.approx_cdate
FROM assign a full outer join ticket t on a.ticket_id =t.srno
full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0".$condition."
ORDER BY 
t.srno
    OFFSET ".$offset." ROWS
    FETCH NEXT  ".$length."  ROWS ONLY";

$stmt = sqlsrv_query($conn,$sql);
$sr=1;
$records = array();
while($row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){  $records[]=array(
  $sr,
  '<button type="button" class="btn btn-sm rounded-pill btn-primary edit" id="' . $row['srno'] . '">Edit</button>' .
  '<button type="button" class="btn btn-sm rounded-pill btn-danger delete" id="' . $row['srno'] . '"><i class="fa-solid fa-trash"></i></button>',
  $row['srno'],
  $row['pstop'],
  ($row['ticketid'] == NULL) ? (($row['assign_to'] == NULL) ? 'Unassigned' : 'Assigned') : (($row['resolved_time'] != '') ? 'Closed' : (($row['approx_cdate']->format('Y') == '1900' && $row['istransfer'] == 1) ? 'Transfer' : (($row['approx_cdate']->format('Y') == '1900' && $row['istransfer'] == 0 && $row1['cnt'] % 2 == 0) ? 'Assigned' : 'Transfer'))),
  $row['cdate'],
  $row['username'],
  $row['mcno'],
  $row['department'],
  $row['plant'],
  $row['issue'],
  ($row['image']!='')? ($row['image'] != '') ? '<img src="../file/image-upload/' . $row['image'] . '" width="80" height="60">' : ''
  :(($row['audio']!='') ?  '<audio id="aud"   controls><source src="../file/audio-upload/'. $row['audio'].'" type="audio/mp3"   > Your browser does not support the audio element.
  </audio>' : ( ($row['video']!='')?   '<video id="vid" width="80" height="60" controls>
  <source src="../file/video-upload/'. $row['video'].'" type="video/mp4">
      Your browser does not support the video tag.
</video>': '' )),
$row['room']=='maintenance' ? 'main' : $row['room'],
  $row['remark'],
  $row['assign_to'],
  $row['approx_time'],
  $row['unit'],
  $row['update_assign'],
);
$sr=$sr+1;
}
$sqlCount = " WITH abc as( 
  select  COUNT(*) AS cnt, ticketid from uwticket_head 
GROUP BY ticketid having
   SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0
),
pqr as(SELECT COUNT(*) AS cnt, ticketid
FROM uwticket_head 
GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
),
transfer as(SELECT COUNT(*) AS cnt, ticketid
FROM uwticket_head 
GROUP BY ticketid HAVING  COUNT(*)  = 1 
AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 

) 
SELECT count(*) as total
FROM assign a full outer join ticket t on a.ticket_id =t.srno
full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0".$condition;
$stmtCount = sqlsrv_query($conn, $sqlCount);
$totalRecords = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];

// Example response structure:
$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords, // For simplicity, assuming no filtering in this example
    "data" => $records
);

echo json_encode($response);

}
?>