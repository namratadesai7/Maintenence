<?php  
include('../includes/dbcon.php');


$sql = "SELECT * from ticket where isdelete=0";
$run = sqlsrv_query($conn, $sql);
?>



<table class="table table-bordered table-striped pt-2" id="assignTable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Priority</th>
                    <th>Prod<br>Stop</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>M/c No</th>
                    <th>Department</th>
                    <th>Plant</th>
                    <th>Issue</th>
                    <th>Remark</th>
                    <th>Assign<br>To</th>
                    <th>Assign<br>Date</th>
                    <th>Approx<br> Time</th>
                    <th>Unit</th>
                    <th>Update from<br>Assign Person</th>
                    <th>Category</th>
                    <th>Sub<br>Category</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $sr=1;
                     while($row= sqlsrv_fetch_array($run, SQLSRV_FETCH_ASSOC)) {
                     
                        $sql1 ="SELECT assign_to,format(assign_date,'yyyy-MM-dd') as adate,approx_time,unit,update_assign,cat,subcat,role from assign where ticket_id=".$row['srno']." and isdelete=0 ";
                        $run1 = sqlsrv_query($conn, $sql1);
                        $row1 = sqlsrv_fetch_array($run1, SQLSRV_FETCH_ASSOC);
                        $at=$row1['assign_to'] ?? '' ;
                    ?>
                        <tr>
                            <td><?php echo $sr ?></td>
                            <td> <?php echo $row['priority'] ?></td>
                            <td> <?php echo $row['pstop'] ?></td>
                            <?php
                                if($at==''){ 
                            ?>
                                    <td>Unassigned</td>
                            <?php
                                }else{
                                    $sql2="SELECT resolved_time,approx_cdate FROM uwticket_head where ticketid='".$row['srno']."'";
                                    $run2=sqlsrv_query($conn,$sql2);
                                    $row2=sqlsrv_fetch_array($run2,SQLSRV_FETCH_ASSOC);
                                    $rt=$row2['resolved_time'] ?? '' ;
                                    $ct=$row2['approx_cdate'] ?? '';

                                    if($ct==''){
                                        ?>
                                        <td>Open</td>
                                        <?php
                                    }else{
                                        if($rt==''){
                                            if($ct->format('Y')=='1900'){
                                                ?>
                                                <td>Transferred</td>
                                                <?php
                                            }else{
                                                ?>
                                                <td>Delayed</td>
                                                <?php
                                            }
                                        }else{
                                            ?>
                                            <td>Closed</td>
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
                            <td><?php echo $row['remark']?></td>
                            <td><?php echo $row1['assign_to'] ?? '' ?></td>
                            <td><?php echo $row1['adate'] ?? '' ?></td>
                            <td><?php echo $row1['approx_time'] ?? '' ?></td>
                            <td><?php echo $row1['unit'] ?? '' ?></td>
                            <td><?php echo $row1['update_assign'] ?? '' ?></td>
                            <td><?php echo $row1['cat'] ?? '' ?></td>
                            <td><?php echo $row1['subcat'] ?? '' ?></td>
                            <td><?php echo $row1['role'] ?? '' ?></td>
                            <td style="padding: 3px 6px !important;"> 
                                <a type="button" class="btn btn-primary btn-sm edit"
                                id="<?php echo $row['srno']   ?>">Edit</a>
                               
                                <?php
                                 if($at== ''){ ?>
                                    <a type="button" class="btn btn-success btn-sm assign assign-button" id="<?php echo $row['srno'] ?>" >Assign</a> 
                                    <?php } ?>
                                <a type="button" class="btn btn-danger btn-sm" 
                                href="aticket_db.php?deleteid=<?php echo $row['srno']?>" 
                                onclick="return confirm('Are you sure you want to delete the ticket? Once you click ok it will be removed from the below table?')" name="delete">Cancel</a>
                            </td>
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
        	],
            language: {
                searchPlaceholder: "Search..."
            }
        });
    });
        </script>