<?php
include('../includes/dbcon.php');


if(isset($_POST['submit'])){
    $wdetail=$_POST['wdetail'];

    $sql="INSERT INTO workdetail_master (work_type) values('$wdetail')";
    $run=sqlsrv_query($conn,$sql);


    if($run){
        header('Location:wdetailsmas.php');
    }else{
        print_r(sqlsrv_errors());
    }

}

if(isset($_POST['update'])){
    $sr=$_POST['editId'];
    $wtype=$_POST['editName'];

    $sql="UPDATE workdetail_master set work_type='$wtype' where sr='$sr' ";
    $run=sqlsrv_query($conn,$sql);

    if($run){
         header('Location:wdetailsmas.php');
    }else{
        print_r(sqlsrv_errors());
    }
}

if(isset($_GET['deleteid'])){
    $sr=$_GET['deleteid'];

    $sql="UPDATE workdetail_master set isdelete='1' ";
    $run=sqlsrv_query($conn,$sql);

    if($run){
        header('Location:wdetailsmas.php');
    }else{
        print_r(sqlsrv_errors());
    }
}

?>