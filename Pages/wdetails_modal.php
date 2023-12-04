<?php
include('../includes/dbcon.php');
$sr=$_POST['sr'];
$date=date('Y-m-d');
?>
<form  id="wdetform">
    <div class="status d-flex">
        <label style="width: 15%;" class="form-label" for="date">Date
            <input class="form-control " name="date" type="date" id="date" value="<?php echo $date ?>">
            <input type="hidden" id="srno" name="srno" value="<?php echo $sr ?>">
        </label>
        <label style="width: 40%;" class="form-label mx-1" for="reason">Reason for being late 
            <input class="form-control" type="text" id="reason" name="reason" placeholder="Enter reason for being late" >
        </label>
        <label style="width: 40%;" class="form-label" for="addsta">Add Status
            <input class="form-control" type="text" id="addsta" name="addsta" placeholder="Enter status">
        </label>
        <label style="width: 5%;" class="form-label ms-1"><br>
            <button type="button" class="btn btn-sm rounded-pill btn-primary add" id="<?php echo $sr ?>" name="addName">Add</button>
        </label>
    </div>
</form>
   
<table class="table table-bordered text-center table-hover table-striped mt-4 " class="modtable">
    <thead>
        <tr class="bg-secondary text-light">
            <th>Sr</th>
            <th>Date</th>
            <th>Reasons</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        $sql="SELECT * FROM workdetailtail where head_id='$sr'";
        $run=sqlsrv_query($conn,$sql);
        while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
?>
        <tr>
            <td><?php echo $i  ?></td>
            <td><?php echo $row['Date']->format('Y-m-d') ?></td>
            <td><?php echo $row['Reason'] ?></td>
            <td><?php echo $row['status'] ?></td>
        </tr>
<?php
          $i=$i+1;  }
    ?>
        
    </tbody>

</table>

<?php

?>