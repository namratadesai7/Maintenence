<?php
include('../includes/dbcon.php');
$sr=$_POST['sr'];

?>
<style>
    .status{
        display: flex;
        flex-direction:row;
        justify-content:space-between;
    }
    #date{
        width:20%;
    }
    #addsta{
        width:50%
    }
</style>
<form  id="wdetform">
    <div class="status">
        
        <input class="form-control" name="date" type="date" id="date" >
        <input type="hidden" id="srno" name="srno" value="<?php echo $sr ?>">
        <input class="form-control " type="text" id="addsta" name="addsta" placeholder="Enter status">
        
        <button type="button" class="btn btn-sm rounded-pill btn-primary add" id="<?php echo $sr ?>" name="addName">Add</button>
    </div>
</form>
   
<table class="table table-borderd text-center table-hover table-striped mt-4 " class="modtable">
    <thead>
        <tr class="bg-secondary text-light">
            <th>Sr</th>
            <th>Date</th>
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
            <td><?php echo $row['status'] ?></td>
        </tr>
<?php
          $i=$i+1;  }
    ?>
        
    </tbody>

</table>

<?php

?>