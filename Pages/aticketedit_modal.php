<?php

include('../includes/dbcon.php');
session_start();
$sr=$_POST['sr'];
echo $sr;
$sql="SELECT * FROM assign where ticket_id=$sr ";
$run=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);
$unit=$row['unit'] ?? '';
$priority=$row['priority'] ?? '';
$cat=$row['cat'] ?? '';
$subcat=$row['subcat']?? '';
$role=$row['role']?? '';
?>
<div class="container-fluid">
        
    <div class="row">
              <div class="col-md-3">
                <label for="assignby">Assign By</label>
                    <input type="text" name="assignby" id="assignby" value="<?php echo $row['createdBy'] ?? $_SESSION['uname'] ?>" readonly
                    
                    class="form-control mt-1">
                    <!-- <input type="text" name="" id="" value="<?php echo $row['srno'] ?>"> -->
                    <input type="hidden" name="sr" value="<?php echo $sr ?>">
              </div>
              <div class="col-md-3">
                <label for="">Assign To</label>
                <input type="text" name="assign_to" id="" class="form-control mt-1" value="<?php echo $row['assign_to'] ?? '' ?>">
              </div>
              <div class="col-md-3">
                <label for="">Approx. Time</label>
                <div class="input-group mt-1">
                <input type="number" name="approx_time" id="approxTime" class="form-control" value="<?php echo $row['approx_time'] ?? '' ?>"  required>
                  <select name="unit" id="unit" class="form-control">
              
                    <option disabled value=""></option>
                    <option <?php if($unit =="hours"){ ?> selected <?php } ?> value="hours">Hours</option>
                    <option <?php if($unit=="days"){ ?> selected <?php } ?> value="days">Days</option>
                    <option <?php if($unit=="months"){ ?> selected <?php } ?> value="months">Months</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <label for="">Priority</label>
                <select name="priority" id="priority" class="form-control mt-1">
                  <option disabled  value=""></option>
                  <option <?php if($priority=="low"){ ?> selected <?php } ?> value="low">Low</option>
                  <option <?php if($priority=="medium"){ ?> selected <?php } ?> value="medium">Medium</option>
                  <option <?php if($priority=="high"){ ?> selected <?php } ?> value="high">High</option>
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-3">
                <label for="">Category</label>
                <select name="cat" id="cat" class="form-control mt-1">
                 <option disabled value=""></option>
                  <option <?php if($cat=="mechanical"){ ?> selected <?php } ?> value="mechanical">Mechanical</option>
                  <option <?php if($cat=="electrical"){ ?> selected <?php } ?> value="electrical">Electrical</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Sub Category</label>
                <select name="subcat" id="subcat" class="form-control mt-1">
                  <option disabled value=""></option>
                  <option <?php if($subcat=="Crane"){ ?> selected <?php } ?>  value="Crane">Crane</option>
                  <option <?php if($subcat=="Penal"){ ?> selected <?php } ?>  value="Penal">Penal</option>
                  <option  <?php if($subcat=="Febrication"){ ?> selected <?php } ?> value="Febrication">Febrication</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Role</label>
                <select name="role" id="role" class="form-control mt-1">
                  <option disabled value=""></option>
                  <option  <?php if($role=="inhouse"){ ?> selected <?php } ?>  value="inhouse">In House</option>
                  <option  <?php if($role=="thirdparty"){ ?> selected <?php } ?>  value="thirdparty">Third Party</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Update</label>
                <input type="text" name="updatefrom" id="" class="form-control mt-1" value="<?php echo $row['update_assign'] ?? ''   ?>">
              </div>
            </div>
</div>