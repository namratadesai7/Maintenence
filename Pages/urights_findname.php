<?php
include('../includes/dbcon.php');
$empid=$_POST['id'];


$sql="SELECT user_name from [Workbook].[dbo].[user] where  employee_id='$empid'";
$run=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);
if($run){
  echo $row['user_name'] ?? '';
}else{
  print_r(sqlsrv_errors());
}


?>