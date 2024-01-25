<?php
date_default_timezone_set('Asia/Kolkata');

include('session.php');
include('dbcon.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Document</title> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"  href="../includes/style.css"/>


    <!-- 
      show 5 row,copy,excel and search  -->

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!----------------------- jQuery UI --------------------------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <!---------------------- Data Table links ------------------>
    <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css" /> -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/autofill/2.4.0/css/autoFill.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/autofill/2.4.0/js/dataTables.autoFill.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <style>
            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {     /*   remove up down arrows for number type*/
                -webkit-appearance: none;
                margin: 0;
            }
        </style> 


</head>

<body>

    <input type="checkbox" id="nav-toggle">
    <div class="sidebar">
        <div class="sidebar-brand">
            <h3><i class="fa-solid fa-earth-americas pt-1"></i><span>SEPL</span></h3>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <input type="hidden" id="empid" value="<?php echo $_SESSION['empid']  ?>">
                    <input type="hidden" id="rights" value="<?php echo $_SESSION['urights']  ?>">
                    <a href="../Pages/dashboard.php" id="dashboard" class="menu-link"><i class="fa-solid fa-house"></i>
                        <span>Dashboard</span> </a>
                    <div class="sub-menu mt-3">
                        <a href="../Pages/dashboard.php" class="link_name"></i>Dashboard</a>
                    </div>
                </li>
                <li class="hide">
                    <a href="../Pages/cticket.php" id="cticket" class="menu-link"><i class="fa-solid fa-drum-steelpan "></i>
                        <span>Create Ticket</span>
                    </a>
                    <div class="sub-menu">
                        <a href="../Pages/cticket.php" class="link_name"></i>Create Ticket</a>
                    </div>
                </li>
                <li class="hide">
                    <a href="../Pages/aticket.php" id="aticket" class="menu-link"><i class="fa-regular fa-square-plus me-3"></i>
                        <span>Assign Ticket</span>
                    </a>
                    <div class="sub-menu">
                        <a href="../Pages/aticket.php" class="link_name"></i>Assign Ticket</a>
                    </div>
                </li>
                <li class="hide">
                    <a href="../Pages/uwticket.php" id="uwticket" class="menu-link"><i class="fa-regular fa-file me-3"></i>
                        <span>User Work tickets</span>
                    </a>
                    <div class="sub-menu">
                        <a href="../Pages/User Work tickets.php" class="link_name"></i>User Work tickets</a>
                    </div>
                </li>
               
                <li class="hide">
                    <a href="../Pages/report.php" id="report" class="menu-link"><i class="fa-regular fa-file me-3"></i>
                        <span>Report</span>
                    </a>
                    <div class="sub-menu">
                        <a href="../Pages/report.php" class="link_name"></i>Report</a>
                    </div>
                </li>
                <li class="hide" >
                    <a href="../Pages/urights.php" id="urights" class="menu-link"><i class="fa-regular fa-file me-3"></i>
                        <span>User Rights</span>
                    </a>
                    <div class="sub-menu">
                        <a href="../Pages/urights.php" class="link_name"></i>User Rights</a>
                    </div>
                </li>
                <li>
                    <a href="../Pages/wdetails.php" id="wdetails" class="menu-link"><i class="fa-regular fa-file me-3"></i>
                        <span>Work Details</span>
                    </a>
                    <div class="sub-menu">
                        <a href="../Pages/wdetails.php" class="link_name"></i>Work Details</a>
                    </div>
                </li>
                <li>
                    <a href="../Pages/wdetailsmas.php" id="wdetailsmas" class="menu-link"><i class="fa-regular fa-file me-3"></i>
                        <span>Work Details Master</span>
                    </a>
                    <div class="sub-menu">
                        <a href="../Pages/wdetailsmas.php" class="link_name"></i>Work Details Master</a>
                    </div>
                </li>
                <li>
                    <a class="sub-btn menu-link" href="../logout.php" onclick="return confirm('Are you sure you want to log out?');">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="title">Logout</span>
                    </a>
                    <div class="sub-menu">
                        <a href="#" class="link_name"></i>Logout</a>
                    </div>
                </li>
              
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header>
            <div class="header-title">
                <label for="nav-toggle">
                    <i class="fa-solid fa-bars"></i>
                </label>
                <span>Dashboard</sapn>
            </div>

            <div class="user-wrapper">
                <img src="../images/avtar.png" width="40px" height="30px" alt="">
                <div>
                    <h4>
                        <?php echo $_SESSION['uname'] ?>
                    </h4>
                    <!-- <small>
                        <?php echo $_SESSION['rights'] ?>
                    </small> -->
                </div>
            </div>
        </header>

<main>  