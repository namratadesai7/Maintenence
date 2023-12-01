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
        .abc{
            margin:20px;
            padding-top:20px;
            /* padding-bottom:20px;
            padding-left:250px !important;     */
            text-align:center;
                }
        /* .col{
            text-align:center;
        } */
        .form-control{
            width:40%;
        }
        .form-select{
            width:40%;
        }
        .btn1{
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
        }
    </style>
</head>
<body>
    <div class="container fl">
        <div class="row mb-3">
            <div class="col">
                <h4 class="pt-2 mb-0">Create Ticket</h4>
            </div>
        </div>
        <div class="divCss">
            <form action="cticket_db.php" method="post">
            <div class="abc">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="date">Date  </label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="date" type="date" name="date" value="<?php echo $date ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="user">User</label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="user" type="text" name="user"  value="<?php echo $_SESSION['uname']    ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="mcno">M/c No</label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="mcno" type="number" name="mcno" value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="dept">Department</label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="dept" type="text" name="dept" value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="plant">Plant</label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="plant" type="text" name="plant" value="">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="issue">Issue</label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="issue" type="text" name="issue" value="">
                         <!-- <Audio Controls>
                             <Source Src="abc.mp3" type="Audio/mpeg" >
                            <input type="text"> 
                        </Audio>   -->
                    </div>
                </div>
      
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="remark">Remark</label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="remark" type="text" name="remark" value="">
                    </div>
                </div>

                
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="priority">Priority</label>
                    </div>
                    <div class="col"> 
                        <input class="form-control" id="priority" type="text" name="priority" value="">
                    </div>
                   
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label" for="pstop">Production Stopped?</label>
                    </div>
                    <div class="col"> 
                        <select class="form-select" name="pstop" id="pstop">
                            <option value=""></option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                       
                    </div>
                </div>

                <div class="btn1">
                        <button type="submit" class="btn btn-sm btn-primary submit" name="save" > Save</button>
                </div>
        </div>
        </form>
        </div>
    </div>
</body>
</html>

<script>
  $('#cticket').addClass('active');
</script>
<?php

include('../includes/footer.php');
?>