<?php
include('../includes/dbcon.php');
session_start();
if(isset($_POST['pending']) || isset($_POST['cfrom']) || isset($_POST['cto']) || isset($_POST['afrom']) || isset($_POST['ato']) || isset($_POST['clfrom']) || isset($_POST['clto'])){

$pending=$_POST['pending'];
$ticketno=$_POST['ticketno'];
$cfrom=$_POST['cfrom'];
$cto=$_POST['cto'];
$afrom=$_POST['afrom'];
$ato=$_POST['ato'];
$clfrom=$_POST['clfrom'];
$clto=$_POST['clto'];

$condition="";

if( $_SESSION['urights']!="admin"){

  $condition.=" and a.assign_to='".$_SESSION['sname']."' and (u.istransfer='0' or u.istransfer is null) ";
}
if(!empty($afrom) && !empty($ato)){
    $condition.=" and a.assign_date between '$afrom' and '$ato' ";
  }
if(!empty($clfrom) && !empty($clto)){
$condition.=" and u.c_date between '$clfrom' and '$clto' ";
}
if(!empty($assignto)){
    $condition.=" AND a.assign_to='$assignto'";
}
if(!empty($ticketno)){
    $condition.=" AND a.ticket_id='$ticketno'  and (CONCAT(t.srno,'/',u.createdAt) in 
    (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL) and a.istransfer=0";   
}
if(!empty($pending)){
    if($pending=="assigned"){
        // $condition.=" and assign_to is not null and (ticketid is null or ticketid in( select ticket_id from abc WHERE CNT=2) )and a.istransfer=0 and (u.Status<> 'closed' or u.status is Null)
        //  and ticketid not in (select ticketid from closed)";
    $condition.=" and ticket_id not in( select ticketid from pqr) and ticket_id not in(select ticketid from transfer)	and a.istransfer<>1 and a.istransfer is not null  and u.Status not in('closed','delay')
       or ticketid is null and assign_to is not null 
			     ";
    }else if($pending=="unassigned"){
        $condition.=" and assign_to is null";
    }
    else if($pending=="closed"){
        $condition.=" and resolved_time is not null and resolved_time <> '' and a.istransfer=0 ";
    }
    else if($pending=="delayed"){
        $condition.=" and approx_cdate <> '1900-01-01'";
    }
    else if($pending=="transferred"){
        // $condition.=" and u.istransfer=1 and a.istransfer=1 or ticketid in(select ticket_id from abc where cnt=1)";
        $condition.="AND Status='transfer'
        and (CONCAT(t.srno,'/',a.createdAt) in 
        (select CONCAT(ticket_id,'/',min(createdAt)) from assign group by ticket_id) )  ";
    }  
}

$sql="WITH pqr as(SELECT COUNT(*) AS cnt, ticketid
      FROM uwticket_head 
      GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
      AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
      ),
      transfer as(SELECT COUNT(*) AS cnt, ticketid
      FROM uwticket_head u full join assign a on u.ticketid=a.ticket_id
      GROUP BY ticketid 
      HAVING  COUNT(*)%2  = 1 
      AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 		
      )
      SELECT top 200  u.Status,a.istransfer as a,u.istransfer,a.srno,t.srno as tsr,a.priority,t.pstop,format(t.date,'dd-MM-yyyy') as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.image,t.audio,t.video,t.remark as remarkc,a.ticket_id,a.assign_to,
      a.assign_date,a.approx_time,a.unit,a.update_assign,
      a.subcat, a.role ,u.c_date,format(u.c_date,'dd-MM-yyyy') as abc,u.resolved_time,u.approx_cdate,u.no_of_parts,u.remark,u.ticketid
      FROM assign a full outer join ticket t on a.ticket_id=t.srno
      full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0".$condition;
$run=sqlsrv_query($conn,$sql);
?>
<style>
    input{
        outline:none;
        border:none;
        background:transparent;
        width:100%;
    }
    th{
    font-weight:400 !important;
    }
    /* Set the table container to have a fixed height and overflow-y: auto */
    .table-container {
        max-height: 700px; /* Set your desired height */
        overflow-y: auto;
        }

    /* Make the table header fixed */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        position: sticky;
        top: 0;
        background-color: #f2f2f2; /* Add a background color if needed */
    }

    #ctickettable th{
            white-space:nowrap;
            font-size: 15px;
            padding: 8px 15px 8px 8px;
        }
        #ctickettable td{
            white-space: nowrap;
            font-size: 14px;
        
            font-weight:500;
        }
        @media only screen and (max-width:2600px) {
        #ctickettable{
            display: block;
            overflow-x: auto;
            float: none !important;
        }
      }
        @media only screen and (max-width:768px) {
        div.dt-buttons {
            display: flex !important;
            justify-content: center !important;
        }

        .dataTables_wrapper .dataTables_filter {
            display: flex;
            justify-content: center;
            padding-top: 10px;
        }
      }
    
</style>

<div class="table-container"> 
<table class="table  table-bordered text-center table-striped table-hover mb-0" id="uwtickettable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Action</th>
                    <th>Ticket<br>ID</th>
                    <th>Priority</th>
                    <th>Prod Stop</th>
                    <th>Status Team </th>
                    <th>Create Date </th>
                    <th>User</th>
                    <th>M/C No.</th>
                    <th>Department </th>
                    <th>Plant</th>
                    <th>Issue</th>
                    <th>Remark C</th> 
                    <!-- From assign -->
                    <th>Assign to </th>
                    <th>Assign Date </th>
                    <th>Approx time</th>
                    <th>Unit</th>
                    <th>Update </th>
                    <th>Sub Cat</th>
                    <th>Role</th>
                    <!-- <th>Close Status</th> -->
                    <th>Resolved Time</th>
                    <th>Parts Change</th>
                    <th>Remarks Team</th>
                    <th>CloseDate</th>
                    <th>Days</th>                                  
                </tr>
            </thead>
            <tbody> 
                <?php
                    $sr=1;
                  
                    while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                        // $sql2="SELECT c_date,format(c_date,'dd-MM-yyyy') as abc,resolved_time,approx_cdate,no_of_parts,remark  FROM uwticket_head where ticketid='".$row['ticket_id']."' and istransfer=0";
                        // $run2=sqlsrv_query($conn,$sql2);
                        // $row2=sqlsrv_fetch_array($run2,SQLSRV_FETCH_ASSOC);
                        $row['abc']=$row['abc']?? '' ;
                        $userDate = new DateTime($row['cdate']);
                        $endDate = new DateTime($row['abc']);

                        if($row['abc'] == '' || $row['cdate']=='' || $row['c_date']->format('Y')=='1900') {
                            $difference = ''; // Set delay days to blank
                         } else {
                        $difference = $endDate->diff($userDate)->format("%a") ;
                         }
                         $row['c_date']=$row['c_date']?? '' ;
                ?>
                <tr>
                    <td><?php echo $sr;   ?></td>
                    
                    <td style="padding: 3px 6px !important;">
                    <?php
                        $sql1=" SELECT COUNT(*) AS cnt, ticketid
                        FROM uwticket_head 
                        GROUP BY ticketid HAVING  COUNT(*) > 1 
                        AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0 and ticketid='".$row['tsr']."'";
                        $run1=sqlsrv_query($conn,$sql1);
                        $row1=sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
                        $ticket=$row1['ticketid'] ?? '';
                        $rt=$row['resolved_time'] ?? '' ;
                        $ct=$row['approx_cdate'] ?? '';

                        $sql2="SELECT COUNT(*) AS cnt, ticket_id
                        FROM assign where ticket_id='".$row['tsr']."'
                        GROUP BY ticket_id ";        
                        $run2=sqlsrv_query($conn,$sql2);
                        $row2=sqlsrv_fetch_array($run2,SQLSRV_FETCH_ASSOC);
                        ?>
                    <?php
                   
                        if(($row['Status'] == 'transfer' && $row['a']==0 && $row2['cnt']==1) || ($row['Status'] == 'transfer' && $row['a']==1 && $row['istransfer']==1)|| $row['Status'] == 'closed' || $row['tsr']== $ticket ){ ?>
                            <button type="button" class="btn btn-sm rounded-pill btn-primary recordexist" 
                               >Action</button>                      
                            <?php } else{
                                ?>
                             <button type="button" class="btn btn-sm rounded-pill btn-primary close" 
                            id="<?php echo $row['ticket_id'] ?>" 
                            data-name="<?php echo $row['srno'] ?>">Action</button>

<?php
                    } 
                
                    ?>
                    </td>
                    <td><?php echo $row['tsr'] ?></td>
                    <td><?php echo $row['priority'] ?></td>
                    <td><?php echo $row['pstop'] ?></td>
                    <!-- from team -->
                    <?php
                    $rt=$row['resolved_time'] ?? '' ;
                    $ct=$row['approx_cdate'] ?? '';
                    if($ct==''){
                        ?>
                        <td>Assigned</td>
                        <?php
                    }else{

                        if($rt!=''){
                            ?>
                            <td>Closed</td>
                            <?php
                        }else{
                            if($ct->format('Y')=='1900'  && $row['a']==1){
                            ?>
                            <td>Transferred</td>
                            <?php
                            }elseif($ct->format('Y')=='1900' && $row['a']==0){
                                if($row2['cnt']%2==0){
                                            ?> 
                                            <td class="st">Assigned</td>
                                            <?php

                                            } else{
                                            ?>
                                            <td class="st">Transfer</td>
                                            <?php
                                            }

                            } 
                            else{ ?>
                                <td>Delayed</td>
                                <?php
                            } 
                        }
                    }              
                    ?>   
                    <!-- from create ticket -->
                    <td><?php echo $row['cdate'] ?></td>
                    <td><?php echo $row['username'] ?></td>
                    <td><?php echo $row['mcno'] ?></td>
                    <td><?php echo $row['department'] ?></td>
                    <td><?php echo $row['plant'] ?></td>
                    <td><?php echo $row['issue'] ?></td>
                    <td><?php echo $row['remarkc'] ?></td>
                    <!-- from assign -->
                    <td><?php echo $row['assign_to'] ?></td>
                    <td><?php echo $row['assign_date']->format('d-m-Y') ?></td>
                    <td><?php echo $row['approx_time'] ?></td>
                    <td><?php echo $row['unit'] ?></td>
                    <td><?php echo $row['update_assign'] ?></td>
                    <td><?php echo $row['subcat'] ?></td>
                    <td><?php echo $row['role'] ?></td>                      
                    <td><?php echo $row['resolved_time'] ?? '' ?></td>
                    <td><?php echo $row['no_of_parts'] ?? '' ?></td>
                    <td><?php echo $row['remark']?? '' ?></td>
                   <?php
                   if($row['c_date']!=''){
                    if($row['c_date']->format('Y')=='1900'){
                        ?>
                        <td></td>
                        <?php
                        }else{
                        ?>
                        <td> <?php echo $row['c_date']->format('d-m-Y') ?></td>
                        <?php
                        }
                   }            
               else{
                ?>
                <td></td>
            <?php
               }
            ?>             
                <td><?php echo $difference ?></td>         
                <?php
                $sr++;
                    }
                    ?>            
                </tr>
            </tbody>
           </table>

</div>

<?php

  }    ?>
<script>
  $(document).ready(function() {
    var initialLength = 100; // Set the initial number of rows to load
    var isServerSide = false; // Flag to track server-side processing

    var dataTable = $('#uwtickettable').DataTable({
        "processing": true,
        "serverSide": isServerSide, // Set initial processing mode
      
        "lengthMenu": [10, 25, 50, 75], // Include the initialLength in the lengthMenu
        "responsive": {
            "details": true
        },
        "columnDefs": [{
            "className": "dt-center",
            "targets": "_all"
        }],
        dom: 'Bfrtip',
        ordering: true,
        destroy: true,
        buttons: [
            'pageLength', 'copy', 'excel',
            {
                    text:'ViewMore', className:'viewMore',
            }
        ],
        language: {
            searchPlaceholder: "Search..."
        }
    });

    $('.viewMore').on('click', function() {
            isServerSide = true; // Switch to server-side processing
            dataTable.destroy(); // Destroy the current DataTable instance
            var user=$('#user').val();
            var assignto=$('#assignto').val();
            var pending=$('#pending').val();
            var ticketno=$('#ticketno').val();
            var cfrom=$('#cfrom').val();
            var cto=$('#cto').val();
            var afrom=$('#afrom').val();
            var ato=$('#ato').val();
            var clfrom=$('#clfrom').val();
            var clto=$('#clto').val();
            dataTable = $('#uwtickettable').DataTable({
                "processing": true,
                "serverSide": isServerSide,
                "ajax": {
                    "url": "uwticket_search_viewmore.php",
                    "type": "POST",
                    "data": function(d) {
                        d.start = d.start || 0;
                        d.length = d.length || 10;
                        d.user = user;
                        d.assignto = assignto;
                        d.pending = pending;
                        d.ticketno = ticketno;
                        d.cfrom = cfrom;
                        d.cto = cto;
                        d.afrom = afrom;
                        d.ato = ato;
                        d.clfrom = clfrom;
                        d.clto = clto;
                        // d.draw = d.draw || 1;
                    }
                },
                "lengthMenu": [10, 25, 50, 75, 100],
                "responsive": {
                    "details": true
                },
                "columnDefs": [{
                    "className": "dt-center",
                    "targets": "_all"
                }],
            
                dom: 'Bfrtip',
                ordering: true,
                buttons: [
                    'pageLength', 'copy', 'excel'
                ],
                language: {
                    searchPlaceholder: "Search..."
                }
            });
        });

});
</script>