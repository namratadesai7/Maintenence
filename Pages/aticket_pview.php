<?php  
include('../includes/dbcon.php');
session_start();

// $condition='';
// if( $_SESSION['urights']!="admin"){

//     $condition.="    ";
// }

// $sql="	WITH abc AS (

//     SELECT COUNT(*) AS cnt, ticket_id
//     FROM assign
//     GROUP BY ticket_id
//     HAVING COUNT(*) % 2 = 0
//     )
// SELECT a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,a.approx_time,a.unit,a.istransfer,t.priority,
// a.update_assign,a.srno as asr FROM assign a full outer join ticket t on a.ticket_id =t.srno
// full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 and (u.Status <>'closed' or u.Status is null) and assign_to is not null AND a.istransfer=0  
// and ticket_id  in(select ticket_id from abc) or ticketid is null and a.istransfer is not  null".$condition;


$sql="	WITH pqr as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head 
        GROUP BY ticketid HAVING  COUNT(*) % 2 = 0
        AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
        ),
        transfer as(SELECT COUNT(*) AS cnt, ticketid
        FROM uwticket_head 
        GROUP BY ticketid HAVING  COUNT(*)  = 1 
        AND SUM(CASE WHEN status = 'transfer' THEN 1 ELSE 0 END) > 0 

)
SELECT a.istransfer,u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,u.ticketid,a.assign_to,format(a.assign_date,'dd-MM-yyyy') as adate,a.approx_time,a.unit,a.istransfer,t.priority,
a.update_assign,a.srno as asr FROM assign a full outer join ticket t on a.ticket_id =t.srno
full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0  and	ticket_id not in( select ticketid from pqr) and ticket_id  in(select ticketid from transfer)
and a.istransfer<>1 and a.istransfer is not null   or ticketid is null and assign_to is not null";
$run = sqlsrv_query($conn, $sql);

?>
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
                                                                                     
                                <!-- <a type="button" class="btn btn-success btn-sm rounded-pill assign assign-button" id="<?php echo $row['srno'] ?>"  data-name="<?php echo $row['asr'] ?>" >Assign</a>  -->
                                  
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
                                        if($rt==NULL){


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
                            <td> <?php echo $row['adate']?></td>
                            <td> <?php echo $row['username'] ?></td>
                            <td> <?php echo $row['mcno'] ?></td>
                            <td><?php echo $row['department']?></td>
                            <td><?php echo $row['plant']?></td>
                            <td><?php echo $row['issue']?></td>
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
        <script>
           // datatable to table
    $(document).ready(function() {
        $('#assignTable').DataTable({
            "processing": true,
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
            destroy: true,
          
            buttons: [
		 		'pageLength','copy', 'excel',
                {
                    text:'ViewAll', className:'viewall',
                    action:function(){
                        $('#spinLoader').html('<span class="spinner-border spinner-border-lg mx-2"></span><p>Loading..</p>');
                        $('#putTable').css({"opacity":"0.5"});

                        $.ajax({
                            url:'aticket_view.php',
                            type:'post',
                            data:{ },
                            success:function(data){
                                $('#putTable').html(data);
                                $('#spinLoader').html('');
                                $('#putTable').css({"opacity":"1"});
                            }
                        });
                    }
                },
                {
                    text:'Pending', className:'pending',
                    action:function(){
                        $('#spinLoader').html('<span class="spinner-border spinner-border-lg mx-2"></span><p>Loading..</p>');
                        $('#putTable').css({"opacity":"0.5"});

                        $.ajax({
                            url:'aticket_view.php',
                            type:'post',
                            data:{ },
                            success:function(data){
                                $('#putTable').html(data);
                                $('#spinLoader').html('');
                                $('#putTable').css({"opacity":"1"});
                            }
                        });
                    }
                },
        	],
            language: {
                searchPlaceholder: "Search..."
            }
        });
    });
        </script>