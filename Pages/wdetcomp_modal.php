<?php
include('../includes/dbcon.php');
$sr=$_POST['sr'];
 $sql="SELECT format(completed_date,'yyyy-MM-dd') as completed_date,status_final FROM workdetail where sr='$sr' ";
//$sql="SELECT completed_date,status_final FROM workdetail where sr='$sr' ";
$run=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);

?>
<style>
    .status{
        display: flex;
        flex-direction:row;
        /* justify-content:flex-start; */
    }
    #abc{
        width:40%;
     }
    #xyz{
        width:50%
    } 
</style>

<div class="status">
        <label for="date" id="abc">Completed Date
            <input class="form-control" name="date" type="date" id="abc" value="<?php echo $row['completed_date']?>" >
            <input type="hidden" id="sr_no" name="sr_no" value="<?php echo $sr ?>">
        </label>    
        
       <label for="addsta" id="xyz">Final Status
            <input class="form-control " type="text" id="xyz" name="addsta" placeholder="Enter status" value="<?php echo $row['status_final'] ?>">     
       </label>
</div>