
<?php
include('../includes/dbcon.php');
include('../includes/header.php');  
$sname=$_SESSION['sname'];

$date=date('Y-m-d');
// $sql="SELECT * FROM assign where assign_to='". $_SESSION['sname']."' and isdelete=0";
// $sql="SELECT a.srno,a.priority,t.pstop,t.date as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.remark as remarkc,a.ticket_id,a.assign_to,a.assign_date,a.approx_time,a.unit,a.update_assign,
// a.subcat, a.role ,u.resolved_time,u.no_of_parts,u.remark as uremark
// from assign a inner join ticket t on t.srno=a.ticket_id 
//             inner join uwticket_head u on t.srno=u.ticketid
//             where a.assign_to='Sumit' and a.isdelete=0";
            
$sql="SELECT a.srno,a.priority,t.pstop,t.date as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.remark as remarkc,a.ticket_id,a.assign_to,a.assign_date,a.approx_time,a.unit,a.update_assign,
a.subcat, a.role    
from assign a inner join ticket t on t.srno=a.ticket_id 
            where a.assign_to='Sumit' and a.isdelete=0";

$run=sqlsrv_query($conn,$sql);

?>

    <style>
        .divCss {
        background-color: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1rem 2rem rgba(132, 139, 200, 0.18);
        }
        .fl{
            margin-top:2rem;
        }
        .abc{
            margin:20px;
            padding-top:20px;
            /* padding-bottom:20px;
            padding-left:250px !important;     */
            text-align:center;
                }
        /* .col{
            text-align:center;
        } */
        #uwtickettable .form-control{
            width:40%;
        }
        #uwtickettable .form-select{
            width:40%;
        }
        #patchange input{
            border:none;
            outline:none;
            background:transparent;
            text-align:center;
        }
        .btn1{
            /* margin-top:10px;
            padding-left:40%;
            padding-right:40%;
            width:100%; */
            /* align-items:center;
            padding-top:40px;
            padding-left:350px;
         */
         text-align:center;
         margin-top:40px;
        }

        #uwtickettable th{
            white-space:nowrap;
        }
        #uwtickettable td{
            white-space:nowrap;
        }
        @media only screen and (max-width:2600px) {
            #uwtickettable {
            display: block;
            overflow-x: auto;
            float: none !important;
            }
        }
    </style>
  <title>User Work Ticket</title>
    <div class="container-fluid fl ">
        <div class="row mb-3">
            <div class="col">
                <h4 class="pt-2 mb-0">User work Ticket</h4>
            </div>
                <!-- <div class="col-auto">
                    <a href="cticket_add.php"  class="btn rounded-pill common-btn mt-2 " name="add">Add</a>
                </div> -->
        </div>
        <div class="divCss">
           <table class="table  table-bordered text-center table-striped table-hover mb-0" id="uwtickettable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Priority</th>
                    <th>Prod Stop</th>
                    <th>Status </th>
                    <th>Date </th>
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
                    <th>Close Status</th>
                    <th>Resolved Time</th>
                    <th>Parts Change</th>
                    <th>Remarks U</th>
                    <th>Days</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody> 
                <?php
                    $sr=1;
                  
                    while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                        $sql2="SELECT * FROM uwticket_head where ticketid='".$row['ticket_id']."'";
                        $run2=sqlsrv_query($conn,$sql2);
                        $row2=sqlsrv_fetch_array($run2,SQLSRV_FETCH_ASSOC);
                ?>
                <tr>
                    <td><?php echo $sr;   ?></td>
                    <td><?php echo $row['priority'] ?></td>
                    <td><?php echo $row['pstop'] ?></td>
                    <td><?php echo "status" ?></td>
                    <td><?php echo $row['cdate']->format('d-m-Y') ?></td>
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
                    <?php
                    $rt=$row2['resolved_time'] ?? '' ;
                    $ct=$row2['approx_cdate'] ?? '';
                    if($rt!=''){
                        ?>
                        <td>Closed</td>

                        <?php
                    }elseif($ct!='' ){
                        if($ct->format('Y')!='1900'){
                        ?>
                        <td>Delayed</td>
                        <?php
                        }
                    }elseif($rt!='' && $ct==''){
                        ?>
                        <td>Transferd</td>
                        <?php
                    }else{
                        ?>
                        <td></td>

                        <?php
                    }
                    
                    ?>
                           
              
                    <td><?php echo $row2['resolved_time'] ?? '' ?></td>
                    <td> <?php echo $row2['no_of_parts'] ?? '' ?></td>
                    <td><?php echo $row2['remark']?? '' ?></td>
                    <td></td>
                   
                    <td>
                        <button type="button" class="btn btn-sm rounded-pill btn-primary close" id="<?php echo $row['ticket_id'] ?>" 
                        data-name="<?php echo $row['srno'] ?>">Action</button>
                    </td>
                <?php
                $sr++;
                    }
                    ?>            
                </tr>
            </tbody>
           </table>
        </div>
    </div>
     <!-- modal for action -->
     <div class="modal fade" id="uwticketmodal" tabindex="-1" aria-labelledby="uwticketmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl ">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title">Add User work detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="uwticket_db.php" method="post" id="uwticketform">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn rounded-pill bg-secondary text-light"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn rounded-pill common-btn save" name="save"
                        form="uwticketform" >Save</button>
                </div>
            </div>
        </div>
    </div>    
<script>
  $('#uwticket').addClass('active');

    $(document).on('click', '.close', function() {
        
        var tid = $(this).attr('id');
        var aid = $(this).data('name');
        
        $.ajax({
            url: 'uwticket_modal.php',
            type: 'post',
            data: {
                tid:tid,aid:aid
            },
            // dataType: 'json',
            success: function(data) {
                $('#uwticketform').html(data);
                $('#uwticketmodal').modal('show');


            }
        });

    });
    
    $(document).on('change', '#cstatus', function() {
            var sta= $(this).val();
           
            $.ajax({
                url: 'uwticket_get.php',
                type: 'post',
                data: {sta:sta
                    
                },
                // dataType: 'json',
                success: function(data) {
                    $('#delaydata').html(data);
                    // $('#uwticketmodal').modal('show');
                }
    });


    });
    $(document).on('input', '#numberOfParts', function() {
            var no= $(this).val();
            console.log(no);

            $.ajax({
                url: 'uwticket_get.php',
                type: 'post',
                data: {no:no
                    
                },
                // dataType: 'json',
                success: function(data) {
                    $('#partchange').html(data);
                    // $('#uwticketmodal').modal('show');
                }
    });

    });

 
  
    $(document).ready(function () {
        $('#uwtickettable').DataTable({
        "processing": true,
        "lengthMenu": [10, 25, 50, 75, 100],
        "responsive": {
            "details": true
        },
        "columnDefs": [
            { "className": "dt-center", "targets": "_all" }
        ],
        dom: 'Bfrtip',
        ordering: true,
        destroy: true,
        //   "order": [[1, 'desc']],
        buttons: ['pageLength', {
            text: 'Pending', className: 'pending',
        },
            {
            text: 'View All', className: 'viewall',
            }],
        language: {
            searchPlaceholder: "Search..."
        }
        });
    });



  
 
</script>
<?php

include('../includes/footer.php');
?>

