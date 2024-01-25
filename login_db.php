<?php
session_start();
include('includes/dbcon.php');

if(isset($_POST['login'])){
    $empid=$_POST['empid'];
    $pwd=$_POST['pwd'];

    $sql="SELECT * FROM [Workbook].[dbo].[user] where employee_id= '$empid' and sortname1 is not null";
    $run= sqlsrv_query($conn,$sql);
    $row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);
    if(($row['password']?? '') ==''){
        ?>
    <script>
        alert('Sortname not updated, Please reach to the Admin ');
        window.open('index.php','_self');
    </script>
    <?php
    }
    else{
        if($pwd==$row['password']){
            $_SESSION['Sr']=$row['user_id'];
            $_SESSION['empid']=$row['employee_id'];
            $_SESSION['uname']=$row['user_name'];
            $_SESSION['sname']=$row['sortname1'];
            $_SESSION['password']=$row['password'];
            $_SESSION['urights']=$row['User_Rights'];
            $_SESSION['isteamlead']=$row['is_teamlead'] ?? '';
            ?>
        <script>
            //alert('User logged in');
            window.open('Pages/dashboard.php','_self');
        </script>
    <?php
        }
        else{
    ?>
        <script>
            alert('Password not match ');
            window.open('index.php','_self');
        </script>
    <?php
        }

    } 
}

?>