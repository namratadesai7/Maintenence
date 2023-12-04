<?php
include('../includes/dbcon.php');
$sr=$_POST['sr'];
$date=date('Y-m-d');

 $sql="SELECT format(completed_date,'yyyy-MM-dd') as completed_date,status_final FROM workdetail where sr='$sr' ";
//$sql="SELECT completed_date,status_final FROM workdetail where sr='$sr' ";
$run=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);

?>
<!-- <style>
    label{
        width:100%;
    }
</style> -->

<div >
        <label style="width:100%;" for="date" id="abc">Completed Date
            <input class="form-control" name="date" type="date" id="abc" value="<?php echo ($row['completed_date']== null) ? $date :  $row['completed_date']   ?>" ><br>
            <input type="hidden" id="sr_no" name="sr_no" value="<?php echo $sr ?>">
        </label>
        
       <label  style="width:100%;" for="addsta" id="xyz">Final Status
            <select class="form-select" name="addsta" id="xyz">
                <option selected disabled value=""></option>
                <option <?php if($row['status_final']=="Pending") {?> selected <?php } ?> value="Pending">Pending</option>
                <option <?php if($row['status_final']=="Completed"){ ?> selected <?php } ?>  value="Completed">Completed</option>
                <option <?php if($row['status_final']=="on hold") {?> selected <?php }?>  value="on hold">On hold</option>
            </select>
            <!-- <input class="form-control " type="text" id="xyz" name="addsta" placeholder="Enter status" value="<?php echo $row['status_final'] ?>">      -->
       </label>
</div>