<?php
include('../includes/dbcon.php');
session_start();
if(isset($_POST['save'])){
  $cstatus=$_POST['cstatus'];
  //head
  $tid=$_POST['cticket'] ?? '';
  $aid=$_POST['aticket'] ?? '';
  $cdate=$_POST['cdate'] ?? '';
  $resolved_time=$_POST['resolved_time'] ?? '';
  $unit=$_POST['unit'] ?? '';
  $partschange=$_POST['partschange'] ?? '';
  $noparts=$_POST['numberOfParts'] ?? '';
 
  $rem=$_POST['rem'] ?? '';
  $approxdate=$_POST['approxdate'] ?? '';
  // $statuss=$_POST['closed'] ?? '';
  //tail
  $name=$_POST['name'] ?? '';
  $qty=$_POST['qty']?? '';
  $punit=$_POST['punit'] ?? '';
  $status=$_POST['status']?? '';

  $sql1="SELECT MAX(sr) as 'num' FROM uwticket_head";
  $run1 =sqlsrv_query($conn,$sql1);
  $row1=sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
  $count=$row1['num']+1;


  if($cstatus=='transfer'){
    $istransfer=1;
    
  }
  else{
    $istransfer=0;

  }
    $sql="INSERT INTO uwticket_head (ticketid,assignid,c_date,Status,resolved_time,unit,no_of_parts,remark,approx_cdate,createdBy,istransfer)
    VALUES('$tid','$aid','$cdate','$cstatus','$resolved_time','$unit','$noparts','$rem','$approxdate','".$_SESSION['uname']."','$istransfer') ";
   $run=sqlsrv_query($conn,$sql);
  //  echo $sql;
  //  echo $status;
  
  
  if($partschange=='yes'){

    foreach($name as $key => $value){
      $sql2="INSERT INTO uwticket_tail(itemname,qty,unit,status,head_id)
      VALUES('$value','".$qty[$key]."','".$punit[$key]."','".$status[$key]."','$count')";
      $run2=sqlsrv_query($conn,$sql2);
    }
    }else{
      $run2=true;

    }
    if($run && $run2 && $run1){
      ?>
      <script>
          window.open('uwticket.php','_self');
      </script>
      <?php
  }else{
      print_r(sqlsrv_errors());
  }
}

?>