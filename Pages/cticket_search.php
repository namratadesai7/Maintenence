<?php
include('../includes/dbcon.php');
session_start();
if(isset($_POST['pending']) || isset($_POST['cfrom']) || isset($_POST['cto']) || isset($_POST['ticketno']) ){


$pending=$_POST['pending'];
$ticketno=$_POST['ticketno'];
$cfrom=$_POST['cfrom'];
$cto=$_POST['cto'];


$condition="";


if( $_SESSION['urights']!="admin"){

  $condition.=" and (a.istransfer=0 or a.istransfer is null) and t.username='".$_SESSION['sname']."'";
}

if(!empty($cfrom) && !empty($cto)){
  $condition.=" and t.date between '$cfrom' and '$cto' ";
}
if(!empty($ticketno)){
  $condition.=" AND t.srno='$ticketno'  and (CONCAT(t.srno,'/',u.createdAt) in 
    (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL) and  (a.istransfer=0 or a.istransfer is null)";   
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
                <th>Action</th>
                <th>Ticket<br>ID</th>
                <th>Prod<br>Stop</th>
                <th>Status </th>
                <th>Date </th>
                <th>Created By</th>
                <th>M/C No.</th>
                <th>Department </th>
                <th>Plant</th>
                <th >Issue</th>
                <th>Img/Aud/Vid</th>
                <th>Type</th>
                <th>Remark</th>
                <th>Assign<br>to </th>
                <th>Approx<br>time</th>
                <th>Unit</th>
                <th>Update</th>               
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
        SELECT top 200  t.srno,format(t.date,'dd-MM-yyyy') as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,
          t.image,t.audio,t.video,t.room,u.ticketid,a.assign_to,a.approx_time,a.unit,a.istransfer,a.update_assign,u.istransfer as utrans
          ,u.resolved_time,u.approx_cdate
          FROM assign a full outer join ticket t on a.ticket_id =t.srno
          full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0".$condition;

    $run=sqlsrv_query($conn,$sql);
    $sr=1;

    while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
      $sql1="SELECT COUNT(*) AS cnt, ticket_id
      FROM assign where ticket_id='".$row['srno']."'
      GROUP BY ticket_id";                 
      $run1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
      ?>
      <tr>
        <td><?php echo $sr ?></td>
        <td style="padding: 3px 6px !important;"><button type="button" class="btn btn-sm rounded-pill btn-primary edit" id="<?php echo $row['srno']  ?>" >Edit</button>
                                <button type="button" class="btn btn-sm rounded-pill btn-danger delete" id="<?php echo $row['srno']  ?>"><i class="fa-solid fa-trash"></i></button>
                            </td>
        <td><?php echo $row['srno'] ?></td>
        <td><?php echo $row['pstop'] ?></td>    
        <?php
                
                if($row['ticketid']==NULL){
                    if($row['assign_to']==NULL){
                        ?>
                        <td>Unassigned</td>
                        <?php
                    }else{
                        ?>
                        <td>Assigned</td>
                        <?php
                    }
                }else{  
                    if($row['resolved_time']!=''){
                        ?>
                        <td>Closed</td>
                        <?php
                    }else{
                        if($row['approx_cdate']->format('Y')=='1900' && $row['istransfer']==1){
                            ?> 
                            <td class="st">Transfer</td>
                            <?php
                        }else if($row['approx_cdate']->format('Y')=='1900' && $row['istransfer']==0){
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
                <td><?php echo $row['cdate'] ?></td>
                <td><?php echo $row['username'] ?></td>
                <td><?php echo $row['mcno'] ?></td>
                <td><?php echo $row['department'] ?></td>
                <td><?php echo $row['plant'] ?></td>
            
                <td class="tooltip-cell" title="<?php echo $row['issue'] ?>" style="max-width: 70px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?php echo $row['issue'] ?>
                    <span class="tooltiptext"><?php echo $row['issue'] ?></span>
                </td>
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
                <td class="tooltip-cell" title="<?php echo $row['remark'] ?>" style="max-width: 70px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?php echo $row['remark'] ?>
                    <span class="tooltiptext"><?php echo $row['remark'] ?></span>
                </td>    
                
        
                <!-- <td><?php echo $row['issue'] ?></td>
                <td><?php echo $row['remark'] ?></td> -->
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

        <?php
}
?>
    <script>
    $(document).ready(function() {
    var initialLength = 100; // Set the initial number of rows to load
    var isServerSide = false; // Flag to track server-side processing

    var dataTable = $('#ctickettable').DataTable({
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
            dataTable = $('#ctickettable').DataTable({
                "processing": true,
                "serverSide": isServerSide,
                "ajax": {
                    "url": "cticket_search_viewmore.php",
                    "type": "POST",
                    "data": function(d) {
                        d.start = d.start || 0;
                        d.length = d.length || 10;
                       
                        d.pending = pending;
                  d.ticketno = ticketno;
                  d.cfrom = cfrom;
                  d.cto = cto;
                
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