<?php
include('../includes/dbcon.php');
include('../includes/header.php'); 

$date=date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create ticket</title>
    <style>
        .divCss {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1rem 2rem rgba(132, 139, 200, 0.18);
        }
        .fl{
            margin-top:2rem;
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
            <form action="cticket_db.php" method="post">
           
            <div class="row px-2">
                   
                        <label class="form-label col-lg-3 col-md-6" for="date">Date  
                            <input class="form-control" id="date" type="date" name="date" value="<?php echo $date ?>">              
                        </label>
                
                        <label class="form-label col-lg-3 col-md-6" for="user">User                    
                            <input class="form-control" id="user" type="text" name="user"  value="<?php echo $_SESSION['uname']    ?>">
                        </label>
                    
                        <label class="form-label col-lg-3 col-md-6" for="mcno">M/c No                     
                            <input class="form-control" id="mcno" type="text" name="mcno" onFocus="Searchmc(this)" >
                        </label>
    
                        <label class="form-label col-lg-3 col-md-6" for="dept">Department
                            <input class="form-control" id="dept" type="text" name="dept"  >
                        </label>    
                 
                        <label class="form-label col-lg-3 col-md-6" for="plant">Plant                    
                            <input class="form-control" id="plant" type="text" name="plant" value="">
                        </label>

                
                        <label class="form-label col-lg-3 col-md-6" for="issue">Issue
                            <input class="form-control" id="issue" type="text" name="issue" value="">
                         <!-- <Audio Controls>
                             <Source Src="abc.mp3" type="Audio/mpeg" >
                            <input type="text"> 
                        </Audio>   -->
                        </label>

                
                        <label class="form-label col-lg-3 col-md-6" for="remark">Remark                   
                            <input class="form-control" id="remark" type="text" name="remark" value="">
                            </label>
                      
                        <label class="form-label col-lg-3 col-md-6" for="priority">Priority
                            <!-- <input class="form-control" id="priority" type="text" name="priority" value=""> -->
                            <select name="priority" id="priority" class="form-control mt-1">
                                <option value=""></option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </label>

                        <div class="row ps-2">
                            <label class="form-label col-lg-3 col-md-6" for="pstop">Production Stopped?
                                <select class="form-select" name="pstop" id="pstop">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </label>   
                            <div class="col"></div>
                            <div class="col-auto">
                                 <button type="submit" class="btn rounded-pill btn-success  mt-3" name="save" >Save</button>
                            </div>                      
                        </div> 
                    </form>
                </div>
            </div>
        </body>
</html>

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
    $.ajax({
        url:'cticketget_data.php',
        type:'post',
        data:{mc_no,mc_no},
        success:function(data){
         
            $('#dept').val(data);
        }
    })
})
</script>
<?php

include('../includes/footer.php');
?>