<?php

include('../includes/dbcon.php');
session_start();
$sr=$_POST['sr'];
?>
<div class="container-fluid">
        
    <div class="row">
        <div class="col-md-3">
            <label for="">Assign By</label>
            <input type="text" name="assignby" id="" value="<?php echo   $_SESSION['uname'] ?>" readonly class="form-control mt-1">
                <!-- <input type="text" name="" id="" value="<?php echo $row['srno'] ?>"> -->
            <input type="text" name="sr" value="<?php echo $sr ?>">
        </div>
        <div class="col-md-3">
              <label for="">Assign To</label>
              <input type="text" name="assign_to" id="" value="" class="form-control mt-1">
        </div>
        <div class="col-md-3">
              <label for="">Approx. Time</label>
              <div class="input-group mt-1">
                  <input type="number" name="approx_time" id="approxTime" class="form-control" required>
                  <select name="unit" id="unit" class="form-control">
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                  </select>
              </div>
        </div>

              <div class="col-md-3">
                <label for="">Priority</label>
                <select name="priority" id="priority" class="form-control mt-1">
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-3">
                <label for="">Category</label>
                <select name="cat" id="cat" class="form-control mt-1">
                 
                  <option value="mechanical">Mechanical</option>
                  <option value="electrical">Electrical</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Sub Category</label>
                <select name="subcat" id="subcat" class="form-control mt-1">
                  <option value="Crane">Crane</option>
                  <option value="Penal">Penal</option>
                  <option value="Febrication">Febrication</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Role</label>
                <select name="role" id="role" class="form-control mt-1">
                  <option value="inhouse">In House</option>
                  <option value="thirdparty">Third Party</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Update</label>
                <input type="text" name="updatefrom" id="" class="form-control mt-1">
              </div>
            </div>
</div>