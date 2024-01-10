<?php
include('../includes/dbcon.php');

// Example: Fetch data based on requested page and length
$page = $_POST['start'] / $_POST['length'] + 1; // Adjust as needed
$length = $_POST['length'];



// Calculate OFFSET based on the current page and length
$offset = ($page - 1) * $length;

// Increase the offset by 10 on every call
// $offset += 10 * ($_POST['draw'] - 1);

// Your database query to fetch data using OFFSET and FETCH
$sql = "SELECT u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,t.image,t.audio,t.video,u.ticketid,a.assign_to,a.approx_time,a.unit,a.istransfer,u.istransfer as utrans
,a.update_assign FROM assign a full outer join ticket t on a.ticket_id =t.srno
full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0  
ORDER BY 
    t.srno
        OFFSET ".$offset." ROWS
        FETCH NEXT  ".$length."  ROWS ONLY";
// $params = array(
//     ':offset' => $offset,
//     ':length' => $length
// );


$stmt = sqlsrv_query($conn,$sql);

// Fetch records
$records = array();
$sr=1;
while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    // $records[] = $row;
    $records[]=array(
      $sr,
      '<button type="button" class="btn btn-sm rounded-pill btn-primary edit" id="' . $row['srno'] . '">Edit</button>' .
      '<button type="button" class="btn btn-sm rounded-pill btn-danger delete" id="' . $row['srno'] . '"><i class="fa-solid fa-trash"></i></button>',
      $row['srno'],
      $row['pstop'],
      ($row['ticketid'] == NULL) ? (($row['assign_to'] == NULL) ? 'Unassigned' : 'Assigned') : (($row['resolved_time'] != '') ? 'Closed' : (($row['approx_cdate']->format('Y') == '1900' && $row['istransfer'] == 1) ? 'Transfer' : (($row['approx_cdate']->format('Y') == '1900' && $row['istransfer'] == 0 && $row1['cnt'] % 2 == 0) ? 'Assigned' : 'Transfer'))),
      $row['date']->format('Y-m-d'),
      $row['username'],
      $row['mcno'],
      $row['department'],
      $row['plant'],
      $row['issue'],
      ($row['image']!='')? ($row['image'] != '') ? '<img src="../file/image-upload/' . $row['image'] . '" width="80" height="60">' : ''
      :(($row['audio']!='') ?  '<audio id="aud"   controls><source src="../file/audio-upload/'. $row['audio'].'" type="audio/mp3"   > Your browser does not support the audio element.
      </audio>' : ( ($row['video']!='')?   '<video id="vid" width="80" height="60" controls>
      <source src="../file/video-upload/'. $row['video'].'" type="video/mp4">
          Your browser does not support the video tag.
  </video>': '' )),
      $row['remark'],
      $row['assign_to'],
      $row['approx_time'],
      $row['unit'],
      $row['update_assign'],
    

    );
    $sr=$sr+1;
}

// Example: Get the total number of records (for recordsTotal and recordsFiltered)
$sqlCount = "SELECT COUNT(*) AS total FROM assign a full outer join ticket t on a.ticket_id =t.srno
full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 ";
$stmtCount = sqlsrv_query($conn, $sqlCount);
$totalRecords = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];

// Example response structure:
$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords, // For simplicity, assuming no filtering in this example
    "data" => $records
);

echo json_encode($response);

?>