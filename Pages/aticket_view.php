<?php  
include('../includes/dbcon.php');
session_start();
$page = $_POST['start'] / $_POST['length'] + 1; // Adjust as needed
$length = $_POST['length'];

// Calculate OFFSET based on the current page and length
$offset = ($page - 1) * $length;
$condition='';
if( $_SESSION['urights']!="admin"){

   // $condition.=" AND(a.istransfer=0 OR a.istransfer is  null)  AND(u.istransfer=0 OR u.istransfer is  null) ";
    $sql="WITH abc as(SELECT COUNT(*) AS cnt, ticketid
    FROM uwticket_head 
    GROUP BY ticketid HAVING  count(*)=1
AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0)
SELECT u.istransfer as u,a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,
        a.approx_time,a.unit,a.istransfer,t.priority,u.Status,
        a.update_assign,a.srno as asr FROM assign a full outer join ticket t on a.ticket_id =t.srno
        full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0
        AND(a.istransfer=0 OR a.istransfer is  null)  AND(u.istransfer=0 OR u.istransfer is  null) and( u.isdelay<>1 or u.isdelay is  null)  or u.ticketid  in(select ticketid from abc)
         OR assign_to is null
         ORDER BY 
    t.srno
        OFFSET ".$offset." ROWS
        FETCH NEXT  ".$length."  ROWS ONLY
         
         ";
         $sqlCount="WITH abc as(SELECT COUNT(*) AS cnt, ticketid
         FROM uwticket_head 
         GROUP BY ticketid HAVING  count(*)=1
     AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0)
     SELECT count(*)as total FROM assign a full outer join ticket t on a.ticket_id =t.srno
             full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0
             AND(a.istransfer=0 OR a.istransfer is  null)  AND(u.istransfer=0 OR u.istransfer is  null) and( u.isdelay<>1 or u.isdelay is  null)  or u.ticketid  in(select ticketid from abc)
              OR assign_to is null";
   
}else{
    $sql="WITH abc as(SELECT COUNT(*) AS cnt, ticketid
    FROM uwticket_head 
    GROUP BY ticketid HAVING COUNT(*) % 2 = 0
    AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0)
    SELECT u.istransfer as u,a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,
    a.approx_time,a.unit,a.istransfer,t.priority,u.Status,
    a.update_assign,a.srno as asr FROM assign a  full outer join  ticket t on a.ticket_id =t.srno
    full outer join  uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 
    AND a.istransfer=1 and u.istransfer=1 or ( u.Status='closed' and a.istransfer=0)or u.ticketid  not in(select ticketid from abc) or ( u.Status='delay' and a.istransfer=0)
    OR( a.istransfer IS NULL and u.istransfer IS NULL) 	OR( a.istransfer=0 and u.istransfer IS NULL)
    OR assign_to is null
    ORDER BY 
    t.srno
        OFFSET ".$offset." ROWS
        FETCH NEXT  ".$length."  ROWS ONLY
    ";
    $sqlCount="WITH abc as(SELECT COUNT(*) AS cnt, ticketid
    FROM uwticket_head 
    GROUP BY ticketid HAVING COUNT(*) % 2 = 0
    AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0)
    SELECT count(*) as total FROM assign a  full outer join  ticket t on a.ticket_id =t.srno
    full outer join  uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 
    AND a.istransfer=1 and u.istransfer=1 or ( u.Status='closed' and a.istransfer=0)or u.ticketid  not in(select ticketid from abc) or ( u.Status='delay' and a.istransfer=0)
    OR( a.istransfer IS NULL and u.istransfer IS NULL) 	OR( a.istransfer=0 and u.istransfer IS NULL)
    OR assign_to is null";
     
}
$stmtCount = sqlsrv_query($conn, $sqlCount);
$totalRecords = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)['total'];
$stmt = sqlsrv_query($conn,$sql);
$records = array();
$sr=1;
while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    $at=$row['assign_to'];
    $rt=$row['resolved_time'];
    $ct=$row['approx_cdate'];
    $sql1="SELECT COUNT(*) AS cnt, ticket_id
    FROM assign where ticket_id='".$row['srno']."'
    GROUP BY ticket_id";
    
    // $sql1 ="SELECT assign_to,format(assign_date,'yyyy-MM-dd') as adate,approx_time,unit,update_assign,cat,subcat,role from assign where ticket_id=".$row['srno']." and isdelete=0 ";
    $run1 = sqlsrv_query($conn, $sql1);
    $row1 = sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
    $records[]=array(
        $sr,
        '<a type="button" id="'.$row['srno'].'" data-name="'.$row['asr'].'" ' . 
        ($row['Status'] == 'closed' || ($row['u'] == 1 && $row1['cnt'] == 2) ? 'class="btn btn-success btn-sm rounded-pill assign assign-button disabled"' : 
        'class="btn btn-success btn-sm rounded-pill assign assign-button"').'>Assign</a>',

        '<a type="button" class="btn btn-primary rounded-pill btn-sm edit"
        id="'.$row['srno'].'">Edit</a>',   
        '<a type="button" class="btn btn-danger rounded-pill btn-sm" ' .
        'href="aticket_db.php?deleteid=' . $row['srno'] . '&asr=' . $row['asr'] . '" ' .
        'onclick="return confirm(\'Are you sure you want to delete the ticket? Once you click OK, it will be removed from the below table?\')" ' .
        'name="delete">Cancel</a>',
        $row['srno'],
        $row['priority'],
        $row['pstop'],
        ($at == NULL ? 'Unassigned' : ($ct == NULL ? 'Assigned' : ($rt == '' ? ($ct->format('Y') == '1900' && $row['istransfer'] == 1 ? 'Transfer' : ($ct->format('Y') == '1900' && $row['istransfer'] == 0 ? ($row1['cnt'] % 2 == 0 ? 'Assigned' : 'Transfer') : 'Delayed')) : 'Closed'))),
        $row['adate'],
        $row['username'],
        $row['mcno'],
        $row['department'],
        $row['plant'],
        $row['issue'],
        $row['remark'],
        $row['assign_to'] ?? '',
        $row['adate'] ?? '',
        $row['approx_time'] ?? '',
        $row['unit'] ?? '',
        $row['update_assign'] ?? '',
        $row['cat'] ?? '',
        $row['subcat'] ?? '' ,
        $row['role'] ?? '',

    );
    $sr=$sr+1;
}


// Example response structure:
$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords,
    "data" => $records
);

echo json_encode($response);
?>

