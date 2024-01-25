<!-- <table class="table table-bordered table-striped pt-2" id="assignTable">
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
                            <a type="button" id="<?php echo $row['srno'] ?>"  data-name="<?php echo $row['asr'] ?>"
                            <?php if($row['Status']=='closed' || ($row['u']==1  && $row1['cnt']==2)){ ?> class="btn btn-success btn-sm rounded-pill assign assign-button disabled "  <?php } else{
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
                        <td> <?php echo $row['adate']?></td>
                        <td> <?php echo $row['username'] ?></td>
                        <td> <?php echo $row['mcno'] ?></td>
                        <td><?php echo $row['department'] ?></td>
                        <td><?php echo $row['plant'] ?></td>
                        <td><?php echo $row['issue'] ?></td>
                        <td><?php echo $row['remark'] ?></td>
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
</table> -->


<!-- <script>
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
                            url:'aticket_pview.php',
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
</script> -->

<!-- <table class="table  table-bordered text-center table-striped table-hover mb-0" id="uwtickettable">
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
                    <!-- <td style="padding: 3px 6px !important;">
                        <button type="button" class="btn btn-sm rounded-pill btn-primary close" 
                        <?php if($row['Status']=="closed") {?> disabled <?php
                        } ?>
                        id="<?php echo $row['ticket_id'] ?>" 
                        data-name="<?php echo $row['srno'] ?>">Action</button>
                    </td> -->
                    <td style="padding: 3px 6px !important;">
                    <?php
                        $sql1=" SELECT COUNT(*) AS cnt, ticketid
                        FROM uwticket_head 
                        GROUP BY ticketid HAVING  COUNT(*) > 1 
                        AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0 and ticketid='".$row['tsr']."'";
                        $run1=sqlsrv_query($conn,$sql1);
                        $row1=sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
                        $ticket=$row1['ticketid'] ?? '';
                        ?>
                    <?php
                   
                        if($row['Status'] == 'transfer' || $row['Status'] == 'closed' || $row['tsr']== $ticket){ ?>
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
                            if($ct->format('Y')=='1900'){
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
           </table> -->


           <script>

function Searchname(txtBoxRef) {

    var f = true; //check if enter is detected
    $(txtBoxRef).keypress(function (e) {
        if (e.keyCode == '13' || e.which == '13'){
            f = false;
        }
    });
    $(txtBoxRef).autocomplete({      
        source: function( request, response ){
            $.ajax({
                url: "cticketget_data.php",
                type: 'post',
                dataType: "json",
                data: {aname: request.term },
                success: function( data ) {
                    response( data );
                },
                error:function(data){
                    console.log(data);
                }
            });
        },
        select: function (event, ui) {
            $('#assign_to').val(ui.item.label);
            return false;
        },
        change: function (event, ui) {
            if(f){
                if (ui.item == null){
                    $(this).val('');
                    $(this).focus();
                }
            }
        },
        open: function () {
        // Set a higher z-index for the Autocomplete dropdown
        $('.ui-autocomplete').css('z-index',1500);
    }
});
} 


// datatable to table
$(document).ready(function() {
$('#uwtickettable').DataTable({
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
          
        },
    ],
    language: {
        searchPlaceholder: "Search..."
    }
});
});
   </script>