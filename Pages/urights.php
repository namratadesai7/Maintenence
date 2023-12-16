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
                    <h4 class="pt-2 mb-0">Add User Rights</h4>
                </div>
            </div>
            <div class="divCss">
                <form action="urights_db.php" method="post">          
                    <div class="row px-2">
                   
                     
                        <label class="form-label col-lg-3 col-md-6" for="user">User Name                  
                            <input class="form-control" id="user" type="text" name="user"  value="<?php echo $_SESSION['uname']  ?>">
                            <input type="hidden" id=empid name="empid" value="<?php echo $_SESSION['empid'] ?>"> 
                        </label>
                    
                        <label class="form-label col-lg-3 col-md-6" for="sname">Add Sort Name                  
                            <input class="form-control" id="sname" type="text" name="sname" value="<?php echo $_SESSION['sname'] ?>"  >
                        </label>
                     
                        <label class="form-label col-lg-3 col-md-6" for="urights">User Right
                                <select class="form-select" name="urights" id="urights" value="<?php echo $_SESSION['urights'] ?>">
                                    <option value=""></option>
                                    <option <?php if($_SESSION['urights']=='admin'){ ?> selected  <?php } ?> value="admin">Admin</option>
                                    <option <?php if($_SESSION['urights']=='user'){ ?> selected  <?php } ?> value="user">User</option>
                                    <option <?php if($_SESSION['urights']=='assign'){ ?> selected  <?php } ?>value="assign">Assign</option>
                                    <option <?php if($_SESSION['urights']=='team'){ ?> selected  <?php } ?> value="team">Team</option>
                                </select>
                            </label>   

                    </div> 

                    <div class="row ps-2 mt-2">
                       
                        
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

<script>
  $('#urights').addClass('active');

 

</script>
<?php

include('../includes/footer.php');
?>