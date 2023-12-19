<?php 
include('../includes/dbcon.php');
/*----------------------- code for get autocomplete mcno-----------------------*/

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
    $query = "SELECT sortname1  FROM [Workbook].[dbo].[user] where sortname1 LIKE '$ii%' ";
	$run = sqlsrv_query($conn,$query);
	while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
		$response[] = array("label"=>$row['sortname1']);
	}


	echo json_encode($response);
}

// code to get item name from RM Software
if (isset($_POST['iname'])) {
		$ii = $_POST['iname'];
		$query = "SELECT DISTINCT item FROM [RM_software].[dbo].[rm_item] where item LIKE '%$ii%'";
		$result = sqlsrv_query($conn,$query);
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ){
		$response[] = array("label"=>$row['item']);
		}
	echo json_encode($response);
}

// code to get unit from RM Software
if (isset($_POST['unit'])) {
	$ii = $_POST['unit'];
	$query = "SELECT DISTINCT unit_name FROM [RM_software].[dbo].[unit] where unit_name LIKE '%$ii%'";
	$result = sqlsrv_query($conn,$query);
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ){
	$response[] = array("label"=>$row['unit_name']);
	}
echo json_encode($response);
}
?>