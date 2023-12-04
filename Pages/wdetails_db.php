<?php

include('../includes/dbcon.php');
session_start();

if(isset($_POST['save'])){
    $plant=$_POST['plant'];
    $description=$_POST['description'];
    $wtype=$_POST['wtype'];
    $agency=$_POST['agency'];
    $remark=$_POST['remark'];
    $consdate=$_POST['consdate'];
    $fstthinker=$_POST['fstthinker'];
    $sdate=$_POST['sdate'];
    $edate=$_POST['edate'];
    $resperson=$_POST['resperson'];
    

    $sql="INSERT INTO workdetail (Plant,Description,Work_type,Agency,Remark,fstcons_date,fst_thinker,startdate,enddate,responsible_person,createdBy)
     VALUES('$plant','$description','$wtype','$agency','$remark','$consdate','$fstthinker','$sdate','$edate','$resperson','".$_SESSION['empid']."')";
    $run=sqlsrv_query($conn,$sql);

    if($run){
        ?>
    <script>
        window.open('wdetails.php','_self');
    </script>
    <?php
    }else{
        print_r(sqlsrv_errors());
    }

}

if(isset($_POST['update'])){
    $sr=$_POST['sr'];
    $plant=$_POST['plant'];
    $description=$_POST['description'];
    $wtype=$_POST['wtype'];
    $agency=$_POST['agency'];
    $remark=$_POST['remark'];
    $consdate=$_POST['consdate'];
    $fstthinker=$_POST['fstthinker'];
    $sdate=$_POST['sdate'];
    $edate=$_POST['edate'];
    $resperson=$_POST['resperson'];
    

    $sql="UPDATE workdetail SET Plant='$plant',Description='$description',Work_type='$wtype',Agency='$agency',
    Remark='$remark',fstcons_date='$consdate',fst_thinker='$fstthinker',startdate='$sdate',enddate='$edate',responsible_person='$resperson',updatedAt='".date('Y-m-d')."', updatedBy='".$_SESSION['empid']."' 
    WHERE sr='$sr'";
    $run=sqlsrv_query($conn,$sql);

    if($run){
        ?>
    <script>
        window.open('wdetails.php','_self');
    </script>
    <?php
    }else{
        print_r(sqlsrv_errors());
    }

}

//update status
if(isset($_POST['srno'])){
    $sr=$_POST['srno'];
    $date=$_POST['date'];
    $sta=$_POST['addsta'];
    $reason=$_POST['reason'];

    $sql="INSERT INTO workdetailtail (Date,status,reason,head_id,createdBy) VALUES('$date','$sta','$reason','$sr','".$_SESSION['empid']."')";
   // echo $sql;
    $run=sqlsrv_query($conn,$sql);

    if($run){
        echo("saved successfully");
    
    }else{
        print_r(sqlsrv_errors());
    }
}

//update complete status
if(isset($_POST['savecomp'])){
    $sr=$_POST['sr_no'];
    $date=$_POST['date'];
    $sta=$_POST['addsta'];

    $sql="UPDATE workdetail SET completed_date='$date',status_final='$sta' WHERE  sr='$sr' ";

    $run=sqlsrv_query($conn,$sql);

    if($run){
       
        ?>
        <script>
            //  alert("saved successfully");
            window.open('wdetails.php','_self');
        </script>
        <?php
    
    }else{
        print_r(sqlsrv_errors());
    }
}
?>