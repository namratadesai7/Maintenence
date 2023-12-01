<?php

include('../includes/dbcon.php');
session_start();

if(isset($_POST['save'])){
    $date=$_POST['date'];
    $user=$_POST['user'];
    $mcno=$_POST['mcno'];
    $dept=$_POST['dept'];
    $plant=$_POST['plant'];
    $issue=$_POST['issue'];
    $remark=$_POST['remark'];
    $priority=$_POST['priority'];
    $pstop=$_POST['pstop'];
    

    $sql="INSERT INTO ticket (date,username,mcno,department,plant,issue,remark,priority,pstop,createdBy)
     VALUES('$date','$user','$mcno','$dept','$plant','$issue','$remark','$priority','$pstop','".$_SESSION['empid']."')";

    $run=sqlsrv_query($conn,$sql);

     if($run){
        ?>
    <script>
            window.open('cticket.php','_self');
    </script>
    <?php
     }else{
        print_r(sqlsrv_errors());
     }
}

//edit
if(isset($_POST['update'])){
    $srno=$_POST['srno'];
    $date=$_POST['date'];
    $user=$_POST['user'];
    $mcno=$_POST['mcno'];
    $dept=$_POST['dept'];
    $plant=$_POST['plant'];
    $issue=$_POST['issue'];
    $remark=$_POST['remark'];
    $priority=$_POST['priority'];
    $pstop=$_POST['pstop'];
    

    $sql="UPDATE ticket SET date='$date',username='$user',mcno='$mcno',department='$dept',plant='$plant',issue='$issue',remark='$remark',priority='$priority',pstop='$pstop',updatedBy='".$_SESSION['empid']."'
    ,updatedAt='".date('Y-m-d')."' where srno='$srno' ";
    $run=sqlsrv_query($conn,$sql);

     if($run){
        ?>
    <script>
            window.open('cticket.php','_self');
    </script>
    <?php
     }else{
        print_r(sqlsrv_errors());
     }
}

//delete
if(isset($_GET['del'])){
    $srno=$_GET['del'];

    $sql="UPDATE ticket SET isdelete=1 where srno='$srno' ";
    $run=sqlsrv_query($conn,$sql);
    
    if($run){
        ?>
    <script>
        window.open('cticket.php','_self');
    </script>

<?php
    }else{
        print_r(sqlsrv_errors());
    }

}
?>