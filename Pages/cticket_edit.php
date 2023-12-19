<?php
include('../includes/dbcon.php');
include('../includes/header.php'); 
$srno=$_GET['edit'];


$date=date('Y-m-d');
$sql="SELECT * FROM ticket where srno='$srno' ";
$run=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);   
?>
<title>Edit ticket</title>
<style>
.divCss {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 1rem 2rem rgba(132, 139, 200, 0.18);
}

.fl {
    margin-top: 2rem;
}
</style>

<div class="container-fluid fl">
    <div class="row mb-3">
        <div class="col">
            <h4 class="pt-2 mb-0">Edit Ticket</h4>
        </div>
    </div>
    <div class="divCss">
        <form action="cticket_db.php" method="post" autocomplete="off">
            <div class="row px-2">

                <label class="form-label col-lg-3 col-md-6" for="date">Date
                    <input class="form-control" id="date" type="date" name="date"
                        value="<?php echo $row['date']->format('Y-m-d') ?>">
                    <input type="hidden" id="srno" name="srno" value="<?php echo $row['srno']   ?>">
                </label>

                <label class="form-label col-lg-3 col-md-6" for="user">User
                    <input class="form-control" id="user" type="text" name="user"
                        value="<?php echo $row['username'] ?>" readonly > 
                </label>

                <label class="form-label col-lg-3 col-md-6" for="mcno">M/c No
                    <input class="form-control" id="mcno" type="text" name="mcno" value="<?php echo $row['mcno'] ?>" onFocus="Searchmc(this)">
                </label>

                <label class="form-label col-lg-3 col-md-6" for="dept">Department
                    <input class="form-control" id="dept" type="text" name="dept"
                        value="<?php echo $row['department'] ?>">
                </label>


                <label class="form-label col-lg-3 col-md-6" for="plant">Plant
                    <!-- <input class="form-control" id="plant" type="text" name="plant" value="<?php echo $row['plant'] ?>"> -->
                    <select class="form-select" name="plant" id="plant" >
                                <option  value=""></option>
                                <option <?php if($row['plant']=="107"){ ?> selected <?php } ?>value="107">107</option>
                                <option  <?php if($row['plant']=="696"){ ?> selected <?php } ?> value="696">696</option>
                                <option  <?php if($row['plant']=="2205"){ ?> selected <?php } ?> value="2205">2205</option>
                                <option  <?php if($row['plant']=="Jarod"){ ?> selected <?php } ?> value="Jarod">Jarod</option>

                            </select>
                </label>


                <label class="form-label col-lg-3 col-md-6" for="issue">Issue
                    <input class="form-control" id="issue" type="text" name="issue" value="<?php echo $row['issue'] ?>">
                    <!-- <Audio Controls>
                            <Source Src="abc.mp3" type="Audio/mpeg" >
                        <input type="text"> 
                    </Audio>   -->
                </label>


                <label class="form-label col-lg-3 col-md-6" for="remark">Remark
                    <input class="form-control" id="remark" type="text" name="remark"
                        value="<?php echo $row['remark'] ?>">
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

            </div>
            <div class="row ps-2">
                <label class="form-label col-lg-3 col-md-6 mt-2" for="">Priority
                    <br>
                    <input class="form-check-input" type="radio" name="priority" value="low" id="flexRadioDefault1"
                        <?php if($row['priority']=="low"){ ?> checked <?php } ?>>
                    <label class="form-check-label" for="flexRadioDefault1">
                        Low
                    </label>


                    <input class="form-check-input" type="radio" name="priority" value="medium" id="flexRadioDefault2"
                        <?php if($row['priority']=="medium"){ ?> checked <?php } ?>>
                    <label class="form-check-label" for="flexRadioDefault2">
                        Medium
                    </label>


                    <input class="form-check-input" type="radio" name="priority" value="high" id="flexRadioDefault3"
                        <?php if($row['priority']=="high"){ ?> checked <?php } ?>>
                    <label class="form-check-label" for="flexRadioDefault3">
                        High
                    </label>

                </label>


                <div class="col"></div>
                <div class="col-auto mt-2">
                    <a href="cticket.php" type="button" class="btn rounded-pill btn-danger mt-3">Back</a>
                    <button type="submit" class="btn rounded-pill btn-success  mt-3" name="update">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
$('#cticket').addClass('active');

function Searchmc(txtBoxRef) {
      
    var f = true; //check if enter is detected
    $(txtBoxRef).keypress(function (e) {
        if (e.keyCode == '13' || e.which == '13'){
            f = false;
        }
    });
       $(txtBoxRef).autocomplete({      
        source: function( request, response ){
               $.ajax({
                 url: "cticketget_data.php",
                  type: 'post',
                  dataType: "json",
                  data: {mcno: request.term },
                  success: function( data ) {
                    response( data );
                },
                error:function(data){
                    console.log(data);
                }
              });
        },
        select: function (event, ui) {
               $('#mcno').val(ui.item.label);
               return false;
          },
        change: function (event, ui) {
              if (f){
                  if (ui.item == null){
                    $(this).val('');
                    $(this).focus();
                  }
            }
        }
      });
}
$(document).on('change','#mcno',function(){
   
   var mc_no=$(this).val();
   console.log(mc_no)
   if(mc_no === null || mc_no === ''){
       //document.getElementById('dept').setAttribute('readonly', true);
       $.ajax({
       url:'cticketget_data.php',
       type:'post',
       data:{mc_no,mc_no},
       success:function(data){
        
           $('#dept').val(data);
           $('#dept').prop("readonly", false);
       }
   })
   
   }else{
       $.ajax({
       url:'cticketget_data.php',
       type:'post',
       data:{mc_no,mc_no},
       success:function(data){
        
           $('#dept').val(data);
           $('#dept').prop("readonly", true);
       }
        })
    }
  
})

   
</script>
<?php

include('../includes/footer.php');
?>