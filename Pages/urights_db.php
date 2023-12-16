<?php
include('../includes/dbcon.php');
session_start();

if(isset($_POST['user'])){

  $user=$_POST['user'];
  $sname=$_POST['sname'];
  $urights=$_POST['urights'];
  $empid=$_POST['empid'];

  $sql="UPDATE  [Workbook].[dbo].[user] SET User_Rights='$urights',sortname1='$sname' where employee_id='$empid' and user_name='$user' ";
  $run=sqlsrv_query($conn,$sql);

  if($run){
    $_SESSION['urights']= $urights;
    $_SESSION['sname']=$sname;
    ?>
<script>
  alert("updated Successfully");
  window.open('urights.php','_self');
</script>

<?php
  }else{
    print_r(sqlsrv_errors());
  }
}
?>