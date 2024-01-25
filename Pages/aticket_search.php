<?php
include('../includes/dbcon.php');
if(isset($_POST['pending']) || isset($_POST['cfrom']) || isset($_POST['cto']) || isset($_POST['afrom']) || isset($_POST['ato']) ){
session_start();
$pending=$_POST['pending'];
$ticketno=$_POST['ticketno'];
$cfrom=$_POST['cfrom'];
$cto=$_POST['cto'];
$afrom=$_POST['afrom'];
$ato=$_POST['ato'];


$condition="";

if(!empty($cfrom) && !empty($cto)){
  $condition.=" and t.date between '$cfrom' and '$cto' ";
}
if(!empty($afrom) && !empty($ato)){
    $condition.=" and a.assign_date between '$afrom' and '$ato' ";
  }
if(!empty($ticketno)){
    $condition.=" AND t.srno='$ticketno'  and (CONCAT(t.srno,'/',u.createdAt) in 
    (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL) and  (a.istransfer=0 or a.istransfer is null) ";   
}
if(!empty($pending)){
    if($pending=="assigned"){

    $condition.=" and ticket_id not in( select ticketid from pqr) and ticket_id not in(select ticketid from transfer)	and a.istransfer<>1 and a.istransfer is not null
        and u.Status not in('closed','delay') or ticketid is null and assign_to is not null 
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
        $condition.=" AND  Status='transfer'
        and (CONCAT(t.srno,'/',a.createdAt) in 
        (select CONCAT(ticket_id,'/',min(createdAt)) from assign group by ticket_id) )  ";
    }  
}  
if( $_SESSION['urights']!="admin"){

    // $condition.=" AND(a.istransfer=0 OR a.istransfer is  null)  AND(u.istransfer=0 OR u.istransfer is  null) ";
    $sql="WITH pqr as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head 
        GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
        AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
        ),
    transfer as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head u full join assign a on u.ticketid=a.ticket_id
        GROUP BY ticketid 
        HAVING  COUNT(*)%2  = 1 
        AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 )  
    SELECT top 100 u.istransfer as u,a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,t.room,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,
         a.approx_time,a.unit,a.istransfer,t.priority,u.Status,
         a.update_assign,a.srno as asr FROM assign a full outer join ticket t on a.ticket_id =t.srno
         full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0
        ".$condition;
    
 }else{
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
				
				)  ,
     uwticketcnt as(SELECT COUNT(*) AS cnt, ticketid
     FROM uwticket_head 
     GROUP BY ticketid HAVING COUNT(*) % 2 = 0
     AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0)
     SELECT  top 100 u.istransfer as u,a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.image,t.audio,t.video,t.remark,t.pstop,t.room,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,
     a.approx_time,a.unit,a.istransfer,t.priority,u.Status,
     a.update_assign,a.srno as asr FROM assign a  full outer join  ticket t on a.ticket_id =t.srno
     full outer join  uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 
     ".$condition;


 }

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

    #assignTable th{
            white-space:nowrap;
            font-size: 15px;
            padding: 8px 15px 8px 8px;
        }
        #assignTable td{
            white-space: nowrap;
            font-size: 14px;
        
            font-weight:500;
        }
        @media only screen and (max-width:2600px) {
        #assignTable{
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
<table class="table table-bordered table-striped pt-2" id="assignTable">
        <thead>
            <tr class="bg-secondary text-light">
                <th>Sr</th>
                <th>Action</th>
                <th>Ticket<br>ID</th>
                <th>Priority</th>
                <th>Prod<br>Stop</th>
                <th>Status</th>
                <th>Date</th>
                <th>Assigned<br>By</th>
                <th>M/c No</th>
                <th>Department</th>
                <th>Plant</th>
                <th>Issue</th>
                <th>Img/Aud/Vid</th>
                <th>Type</th>
                <th>Remark</th>
                <th>Assign<br>To</th>
                <th>Assign<br>Date</th>
                <th>Approx<br>Time</th>
                <th>Unit</th>
                <th>Update from<br>Assign Person</th>
                <th>Category</th>
                <th>Sub<br>Category</th>
                <th>Role</th>                    
            </tr>
        </thead>
        <tbody>
            <?php
                    $sr=1;
                    while($row= sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)) {
                    $at=$row['assign_to'];
                    $rt=$row['resolved_time'];
                    $ct=$row['approx_cdate'];
                    $sql1="SELECT COUNT(*) AS cnt, ticket_id
                    FROM assign where ticket_id='".$row['srno']."'
                    GROUP BY ticket_id";
                    
                    // $sql1 ="SELECT assign_to,format(assign_date,'yyyy-MM-dd') as adate,approx_time,unit,update_assign,cat,subcat,role from assign where ticket_id=".$row['srno']." and isdelete=0 ";
                    $run1 = sqlsrv_query($conn, $sql1);
                    $row1 = sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
                
                ?>
                    <tr>
                        <td><?php echo $sr ?></td>
                        <td style="padding: 3px 6px !important;"> 
                                                                                    
                            <a type="button" id="<?php echo $row['srno'] ?>"  data-name="<?php echo $row['asr'] ?>"
                            <?php if($row['Status']=='closed' || ($row['u']==1  && $row1['cnt']==2) || ($row['assign_to'])!= null){ ?> class="btn btn-success btn-sm rounded-pill assign assign-button disabled "  <?php } else{
                               ?>  class="btn btn-success btn-sm rounded-pill assign assign-button" 
                               <?php }?> >Assign</a> 
                          
                            <a type="button" class="btn btn-primary rounded-pill btn-sm edit"
                            id="<?php echo $row['srno']  ?>">Edit</a>   
                                                            
                            <a type="button" class="btn btn-danger rounded-pill btn-sm" 
                            href="aticket_db.php?deleteid=<?php echo $row['srno']?>&asr=<?php echo $row['asr'] ?>" 
                            onclick="return confirm('Are you sure you want to delete the ticket? Once you click ok it will be removed from the below table?')" name="delete">Cancel</a>
                        </td>
                        <td><?php echo $row['srno'] ?></td>
                        <td> <?php echo $row['priority'] ?></td>
                        <td> <?php echo $row['pstop'] ?></td>
                        <?php
                            if($at==NULL){ 
                        ?>
                                <td class="st">Unassigned</td>
                        <?php
                            }else{
                                if($ct==NULL){
                                    ?>
                                    <td class="st">Assigned</td>
                                    <?php
                                }else{
                                    if($rt==''){
                                        if($ct->format('Y')=='1900' && $row['istransfer']==1){
                                            ?> 
                                            <td class="st">Transfer</td>
                                            <?php
                                        }else if($ct->format('Y')=='1900' && $row['istransfer']==0){
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
                                    }else{
                                        ?>
                                        <td class="st">Closed</td>
                                        <?php
                                    }
                                }                                 
                            }
                        ?>                           
                        <td> <?php echo $row['date']->format('d-m-Y') ?></td>
                        <td> <?php echo $row['username'] ?></td>
                        <td> <?php echo $row['mcno'] ?></td>
                        <td><?php echo $row['department']?></td>
                        <td><?php echo $row['plant']?></td>
                        <td><?php echo $row['issue']?></td>
                        <td  style="padding: 3px 6px !important;" id="img"  data-name="<?php echo $row['srno'] ?>">
                            <?php
                             if($row['image']!=''){
                                ?>
                                <img  src="../file/image-upload/<?php echo $row['image'] ?>" width="80" height="60">
                                <?php
                             }else if($row['audio']!=''){ ?>
                                <audio id="aud"   controls>
                                        <source src="../file/audio-upload/<?php echo $row['audio'] ?>" type="audio/mp3"   > 
                                        Your browser does not support the audio element.
                                </audio>   <?php
                             }else if($row['video']!=''){ ?>
                                <video id="vid" width="80" height="60" controls>
                                    <source src="../file/video-upload/<?php echo $row['video'] ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                             <?php
                             }else{

                             }
                            ?>
                        </td>
                        <td><?php echo $row['room']=='maintenance' ? 'main' : $row['room'] ?></td>
                        <td><?php echo $row['remark']?></td>
                        <td><?php echo $row['assign_to'] ?? '' ?></td>
                        <td><?php echo $row['adate'] ?? '' ?></td>
                        <td><?php echo $row['approx_time'] ?? '' ?></td>
                        <td><?php echo $row['unit'] ?? '' ?></td>
                        <td><?php echo $row['update_assign'] ?? '' ?></td>
                        <td><?php echo $row['cat'] ?? '' ?></td>
                        <td><?php echo $row['subcat'] ?? '' ?></td>
                        <td><?php echo $row['role'] ?? '' ?></td>                           
                    </tr>                    
                <?php
                $sr++; }
            ?>
        </tbody>
</table>


</div>

<?php
}   ?>
<script>
    $(document).ready(function() {
      var initialLength = 100; // Set the initial number of rows to load
      var isServerSide = false; // Flag to track server-side processing

      var dataTable = $('#assignTable').DataTable({
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
                      text:'ViewAll', className:'viewMore',
              }
          ],
          language: {
              searchPlaceholder: "Search..."
          }
      });

    $('.viewMore').on('click', function() {
            isServerSide = true; // Switch to server-side processing
            dataTable.destroy(); // Destroy the current DataTable instance
       
            var pending=$('#pending').val();
            var ticketno=$('#ticketno').val();
            var cfrom=$('#cfrom').val();
            var cto=$('#cto').val();
            var afrom=$('#afrom').val();
            var ato=$('#ato').val();
      
            dataTable = $('#assignTable').DataTable({
                "processing": true,
                "serverSide": isServerSide,
                "ajax": {
                    "url": "aticket_search_viewmore.php",
                    "type": "POST",
                    "data": function(d) {
                        d.start = d.start || 0;
                        d.length = d.length || 10;
                      
                        d.pending = pending;
                        d.ticketno = ticketno;
                        d.cfrom = cfrom;
                        d.cto = cto;
                        d.afrom = afrom;
                        d.ato = ato;
                    
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

