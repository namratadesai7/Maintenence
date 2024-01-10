<?php
include('../includes/dbcon.php');
if(isset($_POST['user']) || isset($_POST['assignto']) ||isset($_POST['pending']) || isset($_POST['cfrom']) || isset($_POST['cto']) || isset($_POST['afrom']) || isset($_POST['ato']) || isset($_POST['clfrom']) || isset($_POST['clto'])){
$user=$_POST['user'];
$assignto=$_POST['assignto'];
$pending=$_POST['pending'];
$ticketno=$_POST['ticketno'];
$cfrom=$_POST['cfrom'];
$cto=$_POST['cto'];
$afrom=$_POST['afrom'];
$ato=$_POST['ato'];
$clfrom=$_POST['clfrom'];
$clto=$_POST['clto'];

$condition="";

if(!empty($user)){
  $condition.=" AND t.username='$user'";
}
if(!empty($cfrom) && !empty($cto)){
  $condition.=" and t.date between '$cfrom' and '$cto' ";
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
    $condition.=" and ticket_id not in( select ticketid from pqr) and ticket_id  in(select ticketid from transfer)	and a.istransfer<>1 and a.istransfer is not null   or ticketid is null and assign_to is not null 
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
        $condition.=" AND ticketid in(select ticket_id from abc ) and Status='transfer'
        and (CONCAT(t.srno,'/',a.createdAt) in 
        (select CONCAT(ticket_id,'/',min(createdAt)) from assign group by ticket_id) )  ";
    }  
}
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
    <table class="table table-bordered text-center table-striped table-hover mb-0" id="ctickettable">
        <thead>
            <tr class="bg-secondary text-light">
                <th>Sr</th>
                <th>Ticket<br>ID</th>
                <th>Priority</th>
                <th>Prod Stop</th>
                <th>Status Team </th>
                <th>Create Date </th>
                <th>Created By</th>
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
                <th>Transferred</th>  
                <th> Delay</th>             
            </tr>
        </thead>
        <tbody>
        <?php
        $sql=" WITH abc as( 
                    select  COUNT(*) AS cnt, ticketid from uwticket_head 
				   GROUP BY ticketid having
                     SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0
                ),
                XYZ as(SELECT COUNT(*) AS cnt, ticketid
                    FROM uwticket_head 
                    GROUP BY ticketid HAVING  COUNT(*) % 2 = 1 
                    AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0
                    ),
                closed as(
                    SELECT COUNT(*) AS cnt, ticketid
                    FROM uwticket_head 
                    GROUP BY ticketid HAVING  COUNT(*)> 1 
                    AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
                ),
                pqr as(SELECT COUNT(*) AS cnt, ticketid
						FROM uwticket_head 
						GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
						AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
						),
				transfer as(SELECT COUNT(*) AS cnt, ticketid
						FROM uwticket_head 
						GROUP BY ticketid HAVING  COUNT(*)  = 1 
						AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 
				
				) 
                SELECT a.istransfer as a,u.istransfer,u.ticketid,a.srno,a.priority,t.srno as tsr,t.pstop,format(t.date,'dd-MM-yyyy') as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.remark as remarkc,a.ticket_id,a.assign_to,
                format(a.assign_date,'dd-MM-yyyy') as adate,a.approx_time,a.unit,a.update_assign,
                a.subcat, a.role ,u.c_date,format(u.c_date,'dd-MM-yyyy') as abc,u.resolved_time,u.approx_cdate,u.no_of_parts,u.remark        
                FROM assign a full outer join ticket t on a.ticket_id=t.srno
                full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 ".$condition;
        
        
        $run=sqlsrv_query($conn,$sql);
                    $sr=1;
                 
                    while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                        $sql1="SELECT COUNT(*) AS cnt, ticket_id
                      FROM assign where ticket_id='".$row['tsr']."'
                      GROUP BY ticket_id";
                     
                        // $sql1 ="SELECT assign_to,format(assign_date,'yyyy-MM-dd') as adate,approx_time,unit,update_assign,cat,subcat,role from assign where ticket_id=".$row['srno']." and isdelete=0 ";
                        $run1 = sqlsrv_query($conn, $sql1);
                        $row1 = sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
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
                    <td><?php echo $row['tsr'] ?></td>
                    <td><?php echo $row['priority'] ?></td>
                    <td><?php echo $row['pstop'] ?></td>
                    <!-- from team -->
                    <?php
                    $rt=$row['resolved_time'] ?? '' ;
                    $ct=$row['approx_cdate'] ?? '';
                    if($row['ticketid']==NULL){
                        if($row['assign_to']==NULL) { ?>
                            <td>Unassigned</td>
                            <?php
                        }else{
                            ?>
                            <td>Assigned</td>
                            <?php
                        }
                   
                    }else{

                        if($rt!=''){
                            ?>
                            <td>Closed</td>
                            <?php
                        }else{
                            if($ct->format('Y')=='1900' && $row['a']==1){
                                ?> 
                                <td class="st">Transfer</td>
                                <?php
                            }else if($ct->format('Y')=='1900' && $row['a']==0){
                                if($row1['cnt']%2==0){
                                    ?> 
                                    <td class="st">Assigned</td>
                                    <?php

                                   } else{
                                    ?>
                                   <td class="st">Transfer</td>
                                    <?php
                                   }
                            }
                            else{
                                ?>
                                <td class="st">Delayed</td>
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
                    <td><?php echo $row['adate'] ?></td>
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
                    $sql2="SELECT isdelay, istransfer,ticketid,
                    CASE
                        WHEN isdelay=1 THEN 'delayed'
                        WHEN isdelay=0 THEN ''
                        ELSE ''
                    END AS isdelay,
                    CASE
                        WHEN istransfer=1 THEN 'transferred'
                        WHEN istransfer=0 THEN ''
                        ELSE ''
                    END AS istrans
                    FROM uwticket_head where ticketid= '".$row['tsr']."' ";
                    $run2=sqlsrv_query($conn,$sql2);
                    $row2=sqlsrv_fetch_array($run2,SQLSRV_FETCH_ASSOC)

                ?>
                 <td><?php echo $row2['istrans'] ?? ''?></td>
                <td><?php echo $row2['isdelay'] ?? ''?></td>             
                <?php
                $sr++;
                    }
                    ?>         
                   
            </tr>                   
        </tbody>
    </table>
</div>  
<?php
}
          
            ?>