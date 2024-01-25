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
/*----------------------- code for get dept-----------------------*/
if (!empty($_POST['mc_no'])) {
    $ii = $_POST['mc_no'];
    $query = "SELECT dept
		 FROM machine_master where machine='$ii' ";
	$run = sqlsrv_query($conn,$query);
	$row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC);


	echo($row['dept'] ?? '');
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

/*----------------------- code for get tid-----------------------*/
if (isset($_POST['tid'])) {
	$ii = $_POST['tid'];
	$query = "SELECT srno  FROM ticket where srno LIKE '$ii%'";
$result = sqlsrv_query($conn,$query);
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ){
	$response[] = array("label"=>$row['srno']);
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

// code to get rmta from RM Software
if (isset($_POST['rmta'])) {
	$ii = $_POST['rmta'];
	$query = "SELECT a.sr_no,a.receive_at FROM [RM_software].[dbo].[inward_com] a join  [RM_software].[dbo].[inward_ind] b
	on a.sr_no=b.sr_no and a.receive_at=b.receive_at  where a.sr_no LIKE '$ii%'";
	$result = sqlsrv_query($conn,$query);
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ){
	$response[] = array("label"=>$row['sr_no'].'-'.$row['receive_at']);
	}
	echo json_encode($response);
}

// code to get item name from RM Software
if (isset($_POST['rmtano'])) {
	// $itname = $_POST['itname'];
	$ii=$_POST['rmtano'];
	$string = "$ii";
	list($number, $word) = explode("-", $string, 2);

	$query = "SELECT c.item FROM [RM_software].[dbo].[inward_com] a join  [RM_software].[dbo].[inward_ind] b
	on a.sr_no=b.sr_no and a.receive_at=b.receive_at join [RM_software].[dbo].[rm_item] c on c.i_code=b.p_item
	where a.sr_no ='$number' and a.receive_at='$word'";
	$result = sqlsrv_query($conn,$query);
	while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ){
	$response[] = array("label"=>$row['item']);
	}
	echo json_encode($response);
}
?>