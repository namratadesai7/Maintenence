<?php
include('../includes/dbcon.php');
include('../includes/header.php'); 

$date=date('Y-m-d');
$sname=$_SESSION['sname'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- included css drop down Serching -->
	<link href='../css/select2.min.css' rel='stylesheet' type='text/css'>
    <title>Create ticket</title>
    <style>
        
        .hidden {
            display: none;
        }
        /* .tog{
            display:flex;
        }
         */
        .divCss {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1rem 2rem rgba(132, 139, 200, 0.18);
        }
        .fl{
            margin-top:2rem;
        }
        /*ADD dropdown searching*/
		.select2-container {
			max-width: 100%;
		}
		.select2-container .select2-selection--single{
			height:39px !important;
			border: 1px solid #ccc !important;
		}

        /* .abc{
            margin:20px;
            padding-top:20px;
            /* padding-bottom:20px;
            padding-left:250px !important;     */
            text-align:center;
                } */
        /* .col{
            text-align:center;
        } */
        
        /* .btn1{
            /* margin-top:10px;
            padding-left:40%;
            padding-right:40%;
            width:100%; */
            /* align-items:center;
            padding-top:40px;
            padding-left:350px;
         */
         text-align:center;
         margin-top:40px;
        } */

 

    </style>
</head>
<body>
        <div class="container-fluid fl">
            <div class="row mb-3">
                <div class="col">
                    <h4 class="pt-2 mb-0">Create Ticket</h4>
                </div>
            </div>
            <div class="divCss">
                <form action="cticket_db.php" method="post" autocomplete="off">          
                    <div class="row px-2">
                   
                        <label class="form-label col-lg-3 col-md-6" for="date">Date  
                            <input class="form-control" id="date" type="date" name="date" value="<?php echo $date ?>">              
                        </label>
                
                        <label class="form-label col-lg-3 col-md-6" for="user">Created By                   
                            <!-- <input class="form-control" id="user" type="text" name="user" value="<?php echo $sname ?>" onFocus="Searchname(this)" > -->
                            <select name="user" id="user" class="form-control user">
                                <option></option>
                                <?php 
                                    $query = "SELECT sortname1  FROM [Workbook].[dbo].[user] where sortname1 is not NULL";
                                    $run = sqlsrv_query($conn,$query);
                                    while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                                ?>
                                <option <?php if($row['sortname1']==$sname){ ?> selected <?php  } ?>><?php echo $row['sortname1'] ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    
                        <label class="form-label col-lg-3 col-md-6" for="mcno">M/c No                     
                            <input class="form-control" id="mcno" type="text" name="mcno" onFocus="Searchmc(this)" >
                        </label>
    
                        <label class="form-label col-lg-3 col-md-6" for="dept">Department
                            <input class="form-control" id="dept" type="text" name="dept"  >
                        </label>    
                 
                        <label class="form-label col-lg-3 col-md-6" for="plant">Plant                    
                            <!-- <input class="form-control" id="plant" type="text" name="plant" value=""> -->
                            <select class="form-select" name="plant" id="plant" >
                                <option selected default value=""></option>
                                <option value="1701">1701</option>
                                <option value="696">696</option>
                                <option value="2205">2205</option>
                                <option value="Jarod">Jarod</option>
                            </select>
                        </label>
                
                        <label class="form-label col-lg-3 col-md-6" for="issue">Issue/Problem
                            <input class="form-control" id="issue" type="text" name="issue" value="">
          
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="sel">Camera/Audio/Video     
                            <div class="input-group">
                                <select  name="sel" id="sel" class="form-control " >
                                    <option value=""></option>
                                    <option value="cam">Camera</option>
                                    <option value="aud">Audio</option>
                                    <option value="vid">Video</option>
                                </select>
                                <!-- <input class="form-control  custom-width hidden" type="number" id="numberOfParts" name="numberOfParts" placeholder="no. of parts"> -->
                                <input  class="form-control  hidden " type="file" accept="image/*" name="img" id="img"  style="width:50%;">
                                <input class="form-control  hidden " type="file" accept="audio/*"  name="audio" id="audio" style="width:50%;" >
                                <input class="form-control  hidden " type="file" accept="video/*"  name="video" id="video" style="width:50%;"    >
                            </div>
                       
                        </label>
                        <script>
            // Get the select element and input element
            var partsChangeSelect = document.getElementById('sel');
            var img = document.getElementById('img');
            var audio = document.getElementById('audio');
            var video = document.getElementById('video');

            partsChangeSelect.addEventListener('change', function () {
                // Check if the selected value is 'yes'
                if (partsChangeSelect.value === 'cam') {                
                    img.classList.remove('hidden');
                    audio.classList.add('hidden');
                    video.classList.add('hidden');

                } else if(partsChangeSelect.value === 'Audio') {               
                    audio.classList.remove('hidden');
                    img.classList.add('hidden');
                    video.classList.add('hidden');
                }
                else{
                    video.classList.remove('hidden');
                    img.classList.add('hidden');
                    audio.classList.add('hidden');
                }
            });  
        </script>

                        <!-- <label class="form-label col-lg-3 col-md-6" for="img">Camera/Audio/Video                   
                            <div class="tog">
                            <input  class="form-control" type="file" accept="image/*" name="img" id="img" required>
                            <input class="form-control" type="file" accept="audio/*"  name="audio" id="audio" >
                            <input class="form-control" type="file" accept="video/*"  name="video" id="video"     >
                            </div>
                        </label> -->
                
                        <label class="form-label col-lg-3 col-md-6" for="remark">Remark                   
                            <input class="form-control" id="remark" type="text" name="remark" value="">
                        </label>
                        </div> 
                        <div class="row ps-2 mt-2">
                        <label class="form-label col-lg-3 col-md-6" for="pstop">Production Stopped?
                                <select class="form-select" name="pstop" id="pstop">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                        </label>   
                   
                        <label  class="form-label col-lg-3 col-md-6 mt-2" for="">Priority
                            <br>
                            <input class="form-check-input" type="radio" name="priority" value="low" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">
                                Low
                            </label>
                                            
                            <input class="form-check-input" type="radio" name="priority" value="medium" id="flexRadioDefault2" checked>
                            <label class="form-check-label" for="flexRadioDefault2">
                                Medium
                            </label>
                                            
                            <input class="form-check-input" type="radio" name="priority" value="high" id="flexRadioDefault3" >
                            <label class="form-check-label" for="flexRadioDefault3">
                                High
                            </label>                    
                        </label>
                        
                        <div class="col"></div>
                        <div class="col-auto mt-2">
                            <a href="cticket.php" type="button" class="btn rounded-pill btn-danger mt-3">Back</a>
                            <button type="submit" class="btn rounded-pill btn-success  mt-3" name="save" >Save</button>                             
                        </div>                      
                    </div>                             
                </form>
            </div>
        </div>
    </body>
</html>
<!-- dropdown serching selected2 -->
<script src='../js/select2.min.js' type='text/javascript'></script>
<script>
    $(document).ready(function() {
		$(".user").select2();
	});

  $('#cticket').addClass('active');
  
    function Searchname(txtBoxRef) {

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
                data: {aname: request.term },
                success: function( data ) {
                    response( data );
                },
                error:function(data){
                    console.log(data);
                }
            });
        },
        select: function (event, ui) {
            $('#user').val(ui.item.label);
            return false;
        },
        change: function (event, ui) {
            if(f){
                if (ui.item == null){
                    $(this).val('');
                    $(this).focus();
                }
            }
        },
        open: function () {
        // Set a higher z-index for the Autocomplete dropdown
        $('.ui-autocomplete').css('z-index',1500);
        }
        });
  } 

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