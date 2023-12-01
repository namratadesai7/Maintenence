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
                <h4 class="pt-2 mb-0">Work Details</h4>
            </div>
        </div>
        <div class="divCss">
            <form action="wdetails_db.php" method="post">
           
            <div class="row px-2">

                        <label class="form-label col-lg-3 col-md-6" for="plant">Plant                    
                            <input class="form-control" id="plant" type="text" name="plant"  >
                        </label>
                
                        <label class="form-label col-lg-3 col-md-6" for="description">Description                    
                            <input class="form-control" id="description" type="text" name="description" >
                        </label>
                    
                        <label class="form-label col-lg-3 col-md-6" for="wtype">Work type                   
                            <input class="form-control" id="wtype" type="text" name="wtype" >
                        </label>
    
                        <label class="form-label col-lg-3 col-md-6" for="agency">Agency
                            <input class="form-control" id="agency" type="text" name="agency" >
                        </label>    
                 
                        <label class="form-label col-lg-3 col-md-6" for="remark">Remark                    
                            <input class="form-control" id="remark" type="text" name="remark" >
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="consdate">1st Consideration Date
                            <input class="form-control" id="consdate" type="date" name="consdate" >
                        </label>
                
                        <label class="form-label col-lg-3 col-md-6" for="fstthinker">1st Thinker                   
                            <input class="form-control" id="fstthinker" type="text" name="fstthinker" >
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="sdate">Start Date  
                            <input class="form-control" id="sdate" type="date" name="sdate" >              
                        </label> 

                        <label class="form-label col-lg-3 col-md-6" for="edate">End Date  
                            <input class="form-control" id="edate" type="date" name="edate" >              
                        </label>

                        <!-- <label class="form-label col-lg-3 col-md-6" for="cdate">Completed Date  
                            <input class="form-control" id="cdate" type="date" name="cdate" >              
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="delaydays">Delay days
                            <input class="form-control" id="delaydays" type="text" name="delaydays" value="">
                        </label> -->

                        <label class="form-label col-lg-3 col-md-6" for="resperson">Responsible person
                            <input class="form-control" id="resperson" type="text" name="resperson" value="">
                        </label>

                        <!-- <label class="form-label col-lg-3 col-md-6" for="responsibledates">Status
                            <input class="form-control" id="responsibledates" type="text" name="responsibledates" value="">
                        </label> -->

                        <div class="row ps-2">
                            <!-- <label class="form-label col-lg-3 col-md-6" for="latereason">Late reason
                                <input class="form-control" id="latereason" type="text" name="latereason" value="">
                            </label>   -->
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
  $('#wdetails').addClass('active');
</script>
<?php

include('../includes/footer.php');
?>