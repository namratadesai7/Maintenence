<?php

include('../includes/dbcon.php');
session_start();


if(isset($_POST['save'])){
    $date=date_create();
    $tstamp=date_timestamp_get($date);
    $date=$_POST['date'];
    $user=$_POST['user'];
    $mcno=$_POST['mcno'];
    $dept=$_POST['dept'];
    $plant=$_POST['plant'];
    $issue=$_POST['issue'];
    $remark=$_POST['remark'];
    $priority=$_POST['priority'];
    $pstop=$_POST['pstop'];
    $img=$_FILES['img']['name']  ?? '';
    $type=$_POST['type'];
    $room=$_POST['room'];
 
    if($img!=''){
        $imgExt = substr($img, strripos($img, '.')); // get file extention
        $imgname = $user.$tstamp.$imgExt;
    
        move_uploaded_file($_FILES["img"]["tmp_name"] ?? '', "../file/image-upload/" .$imgname);
    }else{
        $imgname='';
    }
 
    $audio = $_FILES['audio']['name'] ??'';
       
    if($audio!=''){
        $audioExt = substr($audio, strripos($audio, '.')); // get file extention
        $audioname =  $user.$tstamp.$audioExt;  
        move_uploaded_file($_FILES["audio"]["tmp_name"] ?? '', "../file/audio-upload/" .$audioname);
    }else{
        $audioname='';
    }
   
    $video = $_FILES['video']['name'] ??'';
    // $videoExt = substr($video, strripos($video, '.')); // get file extention
    if($video!=''){
        $videoExt ='.mp4';
        $videoname =  $user.$tstamp.$videoExt;
        move_uploaded_file($_FILES["video"]["tmp_name"] ?? '', "../file/video-upload/" .$videoname);
    }else{
        $videoname='';
    }
    
    $sql="INSERT INTO ticket (date,username,mcno,department,plant,issue,remark,priority,pstop,createdBy,image,audio,video,type,room)
        VALUES('$date','$user','$mcno','$dept','$plant','$issue','$remark','$priority','$pstop','".$_SESSION['empid']."','$imgname','$audioname','$videoname','$type','$room')";

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
    $type=$_POST['type'];
    $room=$_POST['room'];
    
    $sql="UPDATE ticket SET date='$date',username='$user',mcno='$mcno',department='$dept',plant='$plant',issue='$issue',remark='$remark',priority='$priority',pstop='$pstop',
    type='$type',room='$room', updatedBy='".$_SESSION['empid']."',updatedAt='".date('Y-m-d')."' where srno='$srno' ";
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