<?php
include('../includes/dbcon.php');
session_start();

if(isset($_POST['save'])){
    $sr=$_POST['sr'];
    $assignby=$_POST['assignby'];
    $assign_to=$_POST['assign_to'];
    $approx_time=$_POST['approx_time'];
    $unit=$_POST['unit'];
    $priority=$_POST['priority'];
    $cat=$_POST['cat'];
    $subcat=$_POST['subcat'];
    $role=$_POST['role'];
    $updatefrom=$_POST['updatefrom'];
   
    $sql="INSERT INTO assign (ticket_id,prev_ticket_id,assign_to,assign_date,approx_time,unit,update_assign,cat,subcat,role,priority,createdBy)
    VALUES('$sr','0','$assign_to','".date('Y-m-d')."','$approx_time','$unit','$updatefrom','$cat','$subcat','$role','$priority','".$_SESSION['uname']."')";
    $run=sqlsrv_query($conn,$sql);
   
 
    if($run){
        ?>
        <script>
           window.open('aticket.php','_self');
        </script>

<?php
    }else{
        print_r(sqlsrv_errors());
    }

}
if(isset($_POST['edit'])){
    $sr=$_POST['sr'];
    $assignby=$_POST['assignby'];
    $assign_to=$_POST['assign_to'];
    $approx_time=$_POST['approx_time'];
    $unit=$_POST['unit'];
    $priority=$_POST['priority'];
    $cat=$_POST['cat'];
    $subcat=$_POST['subcat'];
    $role=$_POST['role'];
    $updatefrom=$_POST['updatefrom'];
   
    $sql="UPDATE assign SET assign_to='$assign_to',approx_time='$approx_time',unit='$unit',update_assign='$updatefrom',
    cat='$cat',subcat='$subcat',role='$role',priority='$priority',updatedBy='".$_SESSION['uname']."',updatedAt='".date('Y-m-d')."' WHERE ticket_id='$sr' ";
    $run=sqlsrv_query($conn,$sql);
   
 
    if($run){
        ?>
        <script>
           window.open('aticket.php','_self');
        </script>

<?php
    }else{
        print_r(sqlsrv_errors());
    }

}
if(isset($_GET['deleteid'])){
    $sr=$_GET['deleteid'];
   
   
    $sql="UPDATE ticket SET isdelete=1 where srno='$sr' ";
    $run=sqlsrv_query($conn,$sql);
    $sql1="UPDATE assign SET isdelete=1 where ticket_id='$sr'";
    $run1=sqlsrv_query($conn,$sql1);   
    echo $sql;
    echo $sql1;
 
    if($run){
        ?>
        <script>
         window.open('aticket.php','_self');
        </script>

<?php
    }else{
        print_r(sqlsrv_errors());
    }

}

?>