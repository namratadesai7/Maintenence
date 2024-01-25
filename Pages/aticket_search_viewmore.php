<?php
include('../includes/dbcon.php');
if(isset($_POST['pending']) || isset($_POST['cfrom']) || isset($_POST['cto']) || isset($_POST['afrom']) || isset($_POST['ato']) || $_POST['ticketno'] ){
session_start();
$pending=$_POST['pending'];
$ticketno=$_POST['ticketno'];
$cfrom=$_POST['cfrom'];
$cto=$_POST['cto'];
$afrom=$_POST['afrom'];
$ato=$_POST['ato'];


$page = $_POST['start'] / $_POST['length'] + 1; // Adjust as needed
$length = $_POST['length'];

// Calculate OFFSET based on the current page and length
$offset = ($page - 1) * $length;


$condition="";

if(!empty($cfrom) && !empty($cto)){
  $condition.=" and t.date between '$cfrom' and '$cto' ";
}
if(!empty($afrom) && !empty($ato)){
    $condition.=" and a.assign_date between '$afrom' and '$ato' ";
  }
if(!empty($ticketno)){
  $condition.=" AND t.srno='$ticketno'  and (CONCAT(t.srno,'/',u.createdAt) in 
  (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL) and  (a.istransfer=0 or a.istransfer is null) ";   
}
if(!empty($pending)){
    if($pending=="assigned"){
      $condition.=" and ticket_id not in( select ticketid from pqr) and ticket_id not in(select ticketid from transfer)	and a.istransfer<>1 and a.istransfer is not null
      and u.Status not in('closed','delay') or ticketid is null and assign_to is not null 
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
        $condition.=" AND Status='transfer'
        and (CONCAT(t.srno,'/',a.createdAt) in 
        (select CONCAT(ticket_id,'/',min(createdAt)) from assign group by ticket_id) )  ";
    }  
}  
if( $_SESSION['urights']!="admin"){

    // $condition.=" AND(a.istransfer=0 OR a.istransfer is  null)  AND(u.istransfer=0 OR u.istransfer is  null) ";
    $sql="WITH pqr as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head 
        GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
        AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
        ),
        transfer as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head u full join assign a on u.ticketid=a.ticket_id
        GROUP BY ticketid 
        HAVING  COUNT(*)%2  = 1 
        AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 
				
				) 
      SELECT  u.istransfer as u,a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,t.room,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,
         a.approx_time,a.unit,a.istransfer,t.priority,u.Status,
         a.update_assign,a.srno as asr FROM assign a full outer join ticket t on a.ticket_id =t.srno
         full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0
        ".$condition."
                 ORDER BY 
                 t.srno
                 OFFSET ".$offset." ROWS
                 FETCH NEXT  ".$length."  ROWS ONLY";
      $sqlCount="SELECT count(*)as total FROM assign a full outer join ticket t on a.ticket_id =t.srno
          full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0";           

 }else{
     $sql="WITH pqr as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head 
        GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
        AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
        ),
        transfer as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head u full join assign a on u.ticketid=a.ticket_id
        GROUP BY ticketid 
        HAVING  COUNT(*)%2  = 1 
        AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 ) 
   
     SELECT u.istransfer as u,a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.image,t.room,t.audio,t.video,t.remark,t.pstop,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,
     a.approx_time,a.unit,a.istransfer,t.priority,u.Status,
     a.update_assign,a.srno as asr FROM assign a  full outer join  ticket t on a.ticket_id =t.srno
     full outer join  uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 
     ".$condition."
              ORDER BY 
              t.srno
              OFFSET ".$offset." ROWS
              FETCH NEXT  ".$length."  ROWS ONLY";
    $sqlCount="WITH abc as(SELECT COUNT(*) AS cnt, ticketid
    FROM uwticket_head 
    GROUP BY ticketid HAVING COUNT(*) % 2 = 0
    AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0)
    SELECT count(*) as total FROM assign a  full outer join  ticket t on a.ticket_id =t.srno
    full outer join  uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0";          
    }

    $stmtCount = sqlsrv_query($conn, $sqlCount);
    $totalRecords = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];
    $stmt = sqlsrv_query($conn,$sql);
    $sr=1;
    $records = array();

    while($row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
      $at=$row['assign_to'];
      $rt=$row['resolved_time'];
      $ct=$row['approx_cdate'];
      $sql1="SELECT COUNT(*) AS cnt, ticket_id
      FROM assign where ticket_id='".$row['srno']."'
      GROUP BY ticket_id";
      
      // $sql1 ="SELECT assign_to,format(assign_date,'yyyy-MM-dd') as adate,approx_time,unit,update_assign,cat,subcat,role from assign where ticket_id=".$row['srno']." and isdelete=0 ";
      $run1 = sqlsrv_query($conn, $sql1);
      $row1 = sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);

      $records[]=array(
        $sr,
        '<a type="button" id="'.$row['srno'].'" data-name="'.$row['asr'].'" ' . 
        ($row['Status'] == 'closed' || ($row['u'] == 1 && $row1['cnt'] == 2) ? 'class="btn btn-success btn-sm rounded-pill assign assign-button disabled"' : 
        'class="btn btn-success btn-sm rounded-pill assign assign-button"').'>Assign</a>

        <a type="button" class="btn btn-primary rounded-pill btn-sm edit"
        id="'.$row['srno'].'">Edit</a>
        <a type="button" class="btn btn-danger rounded-pill btn-sm" ' .
        'href="aticket_db.php?deleteid=' . $row['srno'] . '&asr=' . $row['asr'] . '" ' .
        'onclick="return confirm(\'Are you sure you want to delete the ticket? Once you click OK, it will be removed from the below table?\')" ' .
        'name="delete">Cancel</a>',
        $row['srno'],
        $row['priority'],
        $row['pstop'],
        ($at == NULL ? 'Unassigned' : ($ct == NULL ? 'Assigned' : ($rt == '' ? ($ct->format('Y') == '1900' && $row['istransfer'] == 1 ? 'Transfer' : ($ct->format('Y') == '1900' && $row['istransfer'] == 0 ? ($row1['cnt'] % 2 == 0 ? 'Assigned' : 'Transfer') : 'Delayed')) : 'Closed'))),
        $row['adate'],
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
        $row['assign_to'] ?? '',
        $row['adate'] ?? '',
        $row['approx_time'] ?? '',
        $row['unit'] ?? '',
        $row['update_assign'] ?? '',
        $row['cat'] ?? '',
        $row['subcat'] ?? '' ,
        $row['role'] ?? '',

    );
    $sr=$sr+1;
    }
    // Example response structure:
    $response = array(
      "draw" => intval($_POST['draw']),
      "recordsTotal" => $totalRecords,
      "recordsFiltered" => $totalRecords,
      "data" => $records
    );

    echo json_encode($response);






  }














