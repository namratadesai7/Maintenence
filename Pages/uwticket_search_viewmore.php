<?php  
include('../includes/dbcon.php');
session_start();


if(isset($_POST['cto']) || isset($_POST['afrom']) || isset($_POST['ato']) || isset($_POST['clfrom']) || isset($_POST['clto'])){

$pending=$_POST['pending'];
$ticketno=$_POST['ticketno'];
$cfrom=$_POST['cfrom'];
$cto=$_POST['cto'];
$afrom=$_POST['afrom'];
$ato=$_POST['ato'];
$clfrom=$_POST['clfrom'];
$clto=$_POST['clto'];

$page = $_POST['start'] / $_POST['length'] + 1; // Adjust as needed
$length = $_POST['length'];

// Calculate OFFSET based on the current page and length
$offset = ($page - 1) * $length;
$condition="";

if( $_SESSION['urights']!="admin"){

  $condition.=" and a.assign_to='".$_SESSION['sname']."' and (u.istransfer='0' or u.istransfer is null) ";
}
if(!empty($afrom) && !empty($ato)){
    $condition.=" and a.assign_date between '$afrom' and '$ato' ";
  }
if(!empty($clfrom) && !empty($clto)){
$condition.=" and u.c_date between '$clfrom' and '$clto' ";
}
if(!empty($assignto)){
    $condition.=" AND a.assign_to='$assignto'";
}
if(!empty($ticketno)){
    $condition.=" AND a.ticket_id='$ticketno'  and (CONCAT(t.srno,'/',u.createdAt) in 
    (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL) and a.istransfer=0";   
}
if(!empty($pending)){
    if($pending=="assigned"){
        // $condition.=" and assign_to is not null and (ticketid is null or ticketid in( select ticket_id from abc WHERE CNT=2) )and a.istransfer=0 and (u.Status<> 'closed' or u.status is Null)
        //  and ticketid not in (select ticketid from closed)";
    $condition.=" and ticket_id not in( select ticketid from pqr) and ticket_id not in(select ticketid from transfer)	and a.istransfer<>1 and a.istransfer is not null  and u.Status not in('closed','delay')
       or ticketid is null and assign_to is not null 
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
        $condition.="AND Status='transfer'
        and (CONCAT(t.srno,'/',a.createdAt) in 
        (select CONCAT(ticket_id,'/',min(createdAt)) from assign group by ticket_id) )  ";
    }  
}


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
      SELECT  u.Status,a.istransfer as a,u.istransfer,a.istransfer,a.srno,t.srno as tsr,a.priority,t.pstop,format(t.date,'dd-MM-yyyy') as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.image,t.audio,t.video,t.remark as remarkc,a.ticket_id,a.assign_to,
      a.assign_date,a.approx_time,a.unit,a.update_assign,
      a.subcat, a.role ,u.c_date,format(u.c_date,'dd-MM-yyyy') as abc,u.resolved_time,u.approx_cdate,u.no_of_parts,u.remark,u.ticketid
      FROM assign a full outer join ticket t on a.ticket_id=t.srno
      full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0".$condition.
             "ORDER BY 
             t.srno
             OFFSET ".$offset." ROWS
             FETCH NEXT  ".$length."  ROWS ONLY";;

$sqlCount="WITH pqr as(SELECT COUNT(*) AS cnt, ticketid
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
      SELECT COUNT(*) AS total 
      FROM assign a full outer join ticket t on a.ticket_id=t.srno
      full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0".$condition;

$stmtCount = sqlsrv_query($conn, $sqlCount);
$totalRecords = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];
$stmt = sqlsrv_query($conn,$sql);
$records = array();
$sr=1;
while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
  $row['abc']=$row['abc']?? '' ;
  $userDate = new DateTime($row['cdate']);
  $endDate = new DateTime($row['abc']);

  if($row['abc'] == '' || $row['cdate']=='' || $row['c_date']->format('Y')=='1900') {
      $difference = ''; // Set delay days to blank
   } else {
      $difference = $endDate->diff($userDate)->format("%a") ;
   }
  $row['c_date']=$row['c_date']?? '' ;

  $sql1=" SELECT COUNT(*) AS cnt, ticketid
          FROM uwticket_head 
          GROUP BY ticketid HAVING  COUNT(*) > 1 
          AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0 and ticketid='".$row['tsr']."'";
          $run1=sqlsrv_query($conn,$sql1);
          $row1=sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
          $ticket=$row1['ticketid'] ?? '';
          $rt=$row['resolved_time'] ?? '' ;
          $ct=$row['approx_cdate'] ?? '';

  $sql2="SELECT COUNT(*) AS cnt, ticket_id
  FROM assign where ticket_id='".$row['tsr']."'
  GROUP BY ticket_id ";        
  $run2=sqlsrv_query($conn,$sql2);
  $row2=sqlsrv_fetch_array($run2,SQLSRV_FETCH_ASSOC);

   $records[]=array(
      $sr,       
      (($row['Status'] == 'transfer' && $row['a']==0 && $row2['cnt']==1) || ($row['Status'] == 'transfer' && $row['a']==1 && $row['istransfer']==1)|| $row['Status'] == 'closed' || $row['tsr']== $ticket  ) ?'<button type="button" class="btn btn-sm rounded-pill btn-primary recordexist" >Action</button>' :'<button type="button" class="btn btn-sm rounded-pill btn-primary close" 
          id="'. $row['ticket_id'].'" data-name="'.$row['srno'].'">Action</button>',
      $row['tsr'],
      $row['priority'],
      $row['pstop'],
      ($ct=='')? 'Assigned'   : ( ($rt!='')? 'Closed' : (($ct->format('Y')=='1900' && $row['a']==1)?'Transferred' : (($ct->format('Y')=='1900' && $row['a']==0)?(($row2['cnt']%2==0)?'Assigned' :'Transfer') : 'Delayed'))  ),
      $row['cdate'],
      $row['username'],
      $row['mcno'],
      $row['department'],
      $row['plant'],
      $row['issue'],
      ($row['image']!='')? ($row['image'] != '') ? '<img src="../file/image-upload/' . $row['image'] . '" width="80" height="60">' : ''
      :(($row['audio']!='') ?  '<audio id="aud"   controls><source src="../file/audio-upload/'. $row['audio'].'" type="audio/mp3" > Your browser does not support the audio element.
      </audio>' : ( ($row['video']!='')?   '<video id="vid" width="80" height="60" controls>
      <source src="../file/video-upload/'. $row['video'].'" type="video/mp4">
          Your browser does not support the video tag.
      </video>': '' )),
      $row['remarkc'],
      $row['assign_to'],         
      $row['assign_date']->format('d-m-Y'),
      $row['approx_time'],       
      $row['unit'],              
      $row['update_assign'],     
      $row['subcat'],                      
      $row['role'],                
      $row['resolved_time'] ?? '',
      $row['no_of_parts'] ?? '',          
      $row['remark']?? '',
      ($row['c_date']!='')?( ($row['c_date']->format('Y')=='1900') ? '' : $row['c_date']->format('d-m-Y') ): '',
      $difference,
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
?>




