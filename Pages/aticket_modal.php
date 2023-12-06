<?php

include('../includes/dbcon.php');
session_start();
$sr=$_POST['sr'];

$date=date('Y-m-d');
$sql="SELECT * FROM ticket where srno='$sr' ";
$run=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);  
?>
<div class="container-fluid">
    <div style="background-color:#e6e8e6;" class="row p-4 ">

        <label class="form-label col-lg-3 col-md-6" for="date">Date
            <input class="form-control" id="date" type="date" name="date"
                value="<?php echo $row['date']->format('Y-m-d') ?>" readonly>
            <!-- <input type="hidden" id="srno" name="srno" value="<?php echo $row['srno']   ?>" > -->
        </label>

        <label class="form-label col-lg-3 col-md-6" for="user">User
            <input class="form-control" id="user" type="text" name="user" value="<?php echo $row['username'] ?>" readonly>
        </label>

        <label class="form-label col-lg-3 col-md-6" for="mcno">M/c No
            <input class="form-control" id="mcno" type="text" name="mcno" value="<?php echo $row['mcno'] ?>">
        </label>

        <label class="form-label col-lg-3 col-md-6" for="dept">Department
            <input class="form-control" id="dept" type="text" name="dept" value="<?php echo $row['department'] ?>">
        </label>


        <label class="form-label col-lg-3 col-md-6" for="plant">Plant
            <input class="form-control" id="plant" type="text" name="plant" value="<?php echo $row['plant'] ?>">
        </label>


        <label class="form-label col-lg-3 col-md-6" for="issue">Issue
            <input class="form-control" id="issue" type="text" name="issue" value="<?php echo $row['issue'] ?>">
            <!-- <Audio Controls>
                    <Source Src="abc.mp3" type="Audio/mpeg" >
                    <input type="text"> 
                /Audio>   -->
        </label>


        <label class="form-label col-lg-3 col-md-6" for="remark">Remark
            <input class="form-control" id="remark" type="text" name="remark" value="<?php echo $row['remark'] ?>">
        </label>

        <!-- <label class="form-label col-lg-3 col-md-6" for="priority">Priority -->
        <!-- <input class="form-control" id="priority" type="text" name="priority" value="<?php echo $row['priority'] ?>"> -->
        <!-- <select name="priority" id="priority" class="form-control mt-1">
                    <option  value=""></option>
                    <option <?php if($row['priority']=="low"){ ?> selected <?php } ?> value="low">Low</option>
                    <option <?php if($row['priority']=="medium"){ ?> selected <?php } ?> value="medium">Medium</option>
                    <option <?php if($row['priority']=="high"){ ?> selected <?php } ?> value="high">High</option>
                </select>
            </label> -->
        <label class="form-label col-lg-3 col-md-6" for="pstop">Production Stopped
            <select class="form-select" name="pstop" id="pstop">
                <option value=""></option>
                <option <?php if($row['pstop']=="yes"){ ?> selected <?php } ?> value="yes">Yes</option>
                <option <?php if($row['pstop']=="no"){ ?> selected <?php } ?> value="no">No</option>
            </select>
        </label>

        <!-- </div>  
            <div class="row ps-2"> -->
        <label class="form-label col-lg-3 col-md-6 mt-2" for="">Priority
            <br>

            <input class="form-check-input " type="radio" name="priority" value="low" id="flexRadioDefault1"
                <?php if($row['priority']=="low"){ ?> checked <?php } ?>>
            <label class="form-check-label me-2" for="flexRadioDefault1">
                Low
            </label>


            <input class="form-check-input" type="radio" name="priority" value="medium" id="flexRadioDefault2"
                <?php if($row['priority']=="medium"){ ?> checked <?php } ?>>
            <label class="form-check-label me-2" for="flexRadioDefault2">
                Medium
            </label>


            <input class="form-check-input" type="radio" name="priority" value="high" id="flexRadioDefault3"
                <?php if($row['priority']=="high"){ ?> checked <?php } ?>>
            <label class="form-check-label me-2" for="flexRadioDefault3">
                High
            </label>

        </label>


    </div>
    <div class="row mt-4">
        <div class="col-md-3">
            <label for="">Assigned By</label>
            <input type="text" name="assignby" id="" value="<?php echo   $_SESSION['uname'] ?>" readonly
                class="form-control mt-1">
            <!-- <input type="text" name="" id="" value="<?php echo $row['srno'] ?>"> -->
            <input type="hidden" name="sr" value="<?php echo $sr ?>">
        </div>
        <div class="col-md-3">
            <label for="">Assigned To</label>
            <input type="text" name="assign_to" id="assign_to" class="form-control mt-1" onFocus="Searchname(this)">
        </div>
        <div class="col-md-3">
            <label for="">Approx. Time</label>
            <div class="input-group mt-1">
                <input type="number" name="approx_time" id="" class="form-control" required>
                <select name="unit" id="unit" class="form-control mt-1" >
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                </select>
            </div>

        </div>
        <div class="col-md-3">
            <label for="">Category</label>
            <select name="cat" id="cat" class="form-control mt-1">

                <option value="mechanical">Mechanical</option>
                <option value="electrical">Electrical</option>
            </select>
        </div>


    </div>
    <div class="row mt-3">

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

        <!-- <div class="col-md-3">
            <label for="">Priority</label>
            <select name="priority" id="priority" class="form-control mt-1">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </div> -->
        <div class="col-md-3">
            <label for="">Update</label>
            <input type="text" name="updatefrom" id="" class="form-control mt-1">
        </div>

        <label class="form-label col-lg-3 col-md-6 mt-2" for="">Priority
            <br>

            <input class="form-check-input " type="radio" name="prioritya" value="low" id="flexRadioDefault4">
                
            <label class="form-check-label me-2" for="flexRadioDefault4">
                Low
            </label>


            <input class="form-check-input" type="radio" name="prioritya" value="medium" id="flexRadioDefault5" >
            <label class="form-check-label me-2" for="flexRadioDefault5">
                Medium
            </label>


            <input class="form-check-input" type="radio" name="prioritya" value="high" id="flexRadioDefault6">
            <label class="form-check-label me-2" for="flexRadioDefault6">
                High
            </label>

        </label>

      
    </div>
</div>
<script>

</script>