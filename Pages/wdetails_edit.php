<?php
include('../includes/dbcon.php');
include('../includes/header.php'); 
$sr=$_GET['edit'];
$sql="SELECT * FROM workdetail where sr='$sr' ";
$run=sqlsrv_query($conn,$sql);
$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);   

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
                            <input class="form-control" id="plant" type="text" name="plant" value="<?php echo $row['Plant'] ?>"  >
                            <input type="hidden" name="sr" id="sr" value="<?php echo $row['sr']  ?>">
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="description">Description                    
                            <input class="form-control" id="description" type="text" name="description" value="<?php echo $row['Description'] ?>" >
                        </label>
                    
                        <label class="form-label col-lg-3 col-md-6" for="wtype">Work type                   
                            <input class="form-control" id="wtype" type="text" name="wtype" value="<?php echo $row['Work_type'] ?>"  >
                        </label>
    
                        <label class="form-label col-lg-3 col-md-6" for="agency">Agency
                            <input class="form-control" id="agency" type="text" name="agency" value="<?php echo $row['Agency'] ?>"  >
                        </label>    
                 
                        <label class="form-label col-lg-3 col-md-6" for="remark">Remark                    
                            <input class="form-control" id="remark" type="text" name="remark" value="<?php echo $row['Remark'] ?>"  >
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="consdate">1st Consideration Date
                            <input class="form-control" id="consdate" type="date" name="consdate" value="<?php echo $row['fstcons_date']->format('Y-m-d') ?>"  >
                        </label>
                
                        <label class="form-label col-lg-3 col-md-6" for="fstthinker">1st Thinker                   
                            <input class="form-control" id="fstthinker" type="text" name="fstthinker" value="<?php echo $row['fst_thinker'] ?>" >
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="sdate">Start Date  
                            <input class="form-control" id="sdate" type="date" name="sdate"  value="<?php echo $row['startdate']->format('Y-m-d') ?>" >              
                        </label> 

                        <label class="form-label col-lg-3 col-md-6" for="edate">End Date  
                            <input class="form-control" id="edate" type="date" name="edate" value="<?php echo $row['enddate']->format('Y-m-d') ?>"  >              
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="resperson">Responsible person
                            <input class="form-control" id="resperson" type="text" name="resperson" value="<?php echo $row['responsible_person'] ?>" >
                        </label>

                      

                        <div class="row ps-2">
                         
                                <div class="col"></div>
                            <div class="col-auto">
                                 <button type="submit" class="btn rounded-pill btn-primary  mt-3" name="update" >Update</button>
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