<?php
include('../includes/dbcon.php');
$tid=$_POST['tid'];
$aid=$_POST['aid'];

?>
<style>
   .divCss {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1rem 2rem rgba(132, 139, 200, 0.18);
        }
        .custom-width {
            width: 25%;
        }

        .hidden {
            display: none;
        }
</style>
    <div class="d-flex">
          <label style="width: 25%;" class="form-label m-1" for="cstatus">Close status
            <select name="cstatus" id="cstatus" class="form-control">
                <option value=""></option>
                <option value="closed">Close</option>
                <option value="delay">Delay</option>
                <option value="transfer">Transfer</option>
            </select>
            <input type="hidden" id="cticet" name="cticket" value="<?php echo $tid ?>" >
            <input type="hidden" id="aticket" name="aticket" value="<?php echo $aid ?>" >
          </label>
         
      </div>
      <div class="d-flex" id="delaydata">
    
      <!-- <label style="width: 25%;" class="form-label m-1" for="cdate">Close Date
          <input class="form-control" type="date" name="cdate" id="cdate" >
      </label>

      <label style="width: 25%;"  for="resolved_time">Resolved Time
        <div class="input-group">
            <input type="number" name="resolved_time" id="resolved_time" class="form-control ms-1 mt-1 " required>
            <select name="unit" id="unit" class="form-control me-1 mt-1">
                <option value="hours">Hours</option>
                <option value="days">Days</option>
                <option value="months">Months</option>
            </select>
          </div>
        </label>

   

        <label style="width: 25%;" for="partschange">Parts Change
    <div class="input-group">
        <select name="partschange" id="partschange" class="form-control mt-1 custom-width">
            <option value=""></option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
        <input class="form-control mt-1 custom-width hidden" type="number" id="numberOfParts" name="numberOfParts" placeholder="no. of parts">
    </div>
</label>

        <label style="width: 25%;" class="form-label m-1" for="rem">Remark
          <input type="text" class="form-control" name="rem" id="rem">
        </label>   -->

      </div>
      <div id="partchange" class="mt-2">
       
      </div>
     