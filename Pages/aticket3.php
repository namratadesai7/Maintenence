<?php
include('../includes/dbcon.php');
if(isset($_POST['user']) || isset($_POST['assignto']) ||isset($_POST['pending']) || isset($_POST['cfrom']) || isset($_POST['cto']) || isset($_POST['afrom']) || isset($_POST['ato']) || isset($_POST['clfrom']) || isset($_POST['clto'])){
$user=$_POST['user'];
$assignto=$_POST['assignto'];
$pending=$_POST['pending'];
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
    $condition.=" and a.assign_date '$afrom' and '$ato' ";
  }
if(!empty($clfrom) && !empty($clto)){
$condition.=" and u.c_date between '$clfrom' and '$clto' ";
}
if(!empty($assignto)){
    $condition.=" AND a.assign_to='$assignto'";
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
        /* @media only screen and (max-width:2600px) {
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
      } */
    
</style>
<div class="table-container">   
    <table class="table table-bordered text-center table-striped table-hover mb-0" id="ctickettable">
        <thead>
            <tr class="bg-secondary text-light" >
            <tr class="bg-secondary text-light">
                <th>Sr</th>
                <th>Action</th>
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
               $sql="SELECT u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,u.ticketid,a.assign_to,a.approx_time,a.unit,a.istransfer,
               a.update_assign FROM assign a full outer join ticket t on a.ticket_id =t.srno
               full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 and (a.istransfer=0 or a.istransfer is null)".$condition;
               $run=sqlsrv_query($conn,$sql);
               while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
               ?>
           <tr>
               <td><?php echo $sr ?></td>
               <td><?php echo $row['pstop'] ?></td>
               <?php
           
               if($row['ticketid']==NULL){
                   if($row['assign_to']==NULL){
                       ?>
                       <td>Unassigned</td>
                       <?php
                   }else{
                       ?>
                       <td>Open</td>
                       <?php
                   }
               }else{  
                   if($row['resolved_time']!=''){
                       ?>
                       <td>Closed</td>
                       <?php
                   }else{
                       if($row['approx_cdate']->format('Y')=='1900'){
                           ?>
                           <td>Transferred</td>
                           <?php
                       }else{
                           ?>
                           <td>Delayed</td>
                           <?php
                       }
                   }
               }                      
               ?>                      
               <td><?php echo $row['date']->format('Y-m-d') ?></td>
               <td><?php echo $row['username'] ?></td>
               <td><?php echo $row['mcno'] ?></td>
               <td><?php echo $row['department'] ?></td>
               <td><?php echo $row['plant'] ?></td>
               <td><?php echo $row['issue'] ?></td>
               <td><?php echo $row['remark'] ?></td>
               <td><?php echo $row['assign_to']  ?></td>
               <td><?php echo $row['approx_time'] ?></td>
               <td><?php echo $row['unit'] ?></td>
               <td><?php echo $row['update_assign'] ?></td>
             
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
