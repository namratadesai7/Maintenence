<?php 
include('../includes/dbcon.php');
/*----------------------- code for get autocomplete mcno-----------------------*/
if (isset($_POST['mcno'])) {
    $ii = $_POST['mcno'];
    $query = "SELECT DISTINCT machine FROM machine_master where machine LIKE '%$ii%'";
	$result = sqlsrv_query($conn,$query);
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ){
		$response[] = array("label"=>$row['machine']);
	}
	echo json_encode($response);
}
/*----------------------- code for get sept-----------------------*/
if (!empty($_POST['mc_no'])) {
    $ii = $_POST['mc_no'];
    $query = "SELECT dept FROM machine_master where machine='$ii' ";
	$run = sqlsrv_query($conn,$query);
	$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);


	echo($row['dept']);
}

if (isset($_POST['aname'])) {
    $ii = $_POST['aname'];
    $query = "SELECT sortname  FROM [Workbook].[dbo].[user] where sortname LIKE '$ii%' ";
	$run = sqlsrv_query($conn,$query);
	while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
		$response[] = array("label"=>$row['sortname']);
	}


	echo json_encode($response);
}


?>