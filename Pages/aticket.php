<?php
include('../includes/dbcon.php');
include('../includes/header.php');

$sql = "SELECT * from ticket where isdelete=0";
$run = sqlsrv_query($conn, $sql);
?>
<title>
    Assign Ticket
</title>
<style>
    table.dataTable {
    border-collapse: collapse;
    }

    th {
        white-space: nowrap;
    }

    td {
        white-space: nowrap;
    }

    .pending {
        background: #FFC04C !important;
    }

    .viewall {
        background: #7DE5F6 !important;
    }
    @media only screen and (max-width:2600px) {
    #assignTable {
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
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col">
            <h2 class="title">Assign Tickets</h2>
        </div>

    </div>
    <div>
        <table class="table table-bordered table-striped pt-2" id="assignTable">
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Priority</th>
                    <th>Prod Stop</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>User</th>
                    <th>M/c No</th>
                    <th>Department</th>
                    <th>Plant</th>
                    <th>Issue</th>
                    <th>Remark</th>
                    <th>Assign To</th>
                    <th>Assign Date</th>
                    <th>Approx. Time</th>
                    <th>Unit</th>
                    <th>Update from Assign Person</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $sr=1;
                     while($row= sqlsrv_fetch_array($run, SQLSRV_FETCH_ASSOC)) {
                     
                        $sql1 = "SELECT assign_to,format(assign_date,'yyyy-MM-dd') as adate,approx_time,unit,update_assign,cat,subcat,role from assign where ticket_id=".$row['srno']." and isdelete=0 ";
                        $run1 = sqlsrv_query($conn, $sql1);
                        $row1 = sqlsrv_fetch_array($run1, SQLSRV_FETCH_ASSOC);
                        $at=$row1['assign_to'] ?? '' ;
                    ?>
                        <tr>
                            <td><?php echo $sr ?></td>
                            <td> <?php echo $row['priority'] ?></td>
                            <td> <?php echo $row['pstop'] ?></td>
                            <?php
                                  if($at== ''){ 
                            ?>
                                    <td>Unassigned</td>
                            <?php
                                  }else{
                                    ?>
                                    <td>Open</td>
                                    <?php
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
                            <td> 
                                <a type="button" class="btn btn-primary btn-sm me-1 edit"
                                id="<?php echo $row['srno']   ?>">Edit</a>
                               
                                <?php
                                 if($at== ''){ ?>
                                    <a type="button" class="btn btn-success btn-sm me-1 assign assign-button" id="<?php echo $row['srno'] ?>" >Assign</a> 
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
    </div>
    <!-- modal for assign -->
    <div class="modal fade" id="assignmodal" tabindex="-1" aria-labelledby="assignmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl ">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title">Assign Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="aticket_db.php" method="post" id="assignform">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn rounded-pill bg-secondary text-light"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn rounded-pill common-btn save" name="save"
                        form="assignform" >Save</button>
                </div>
            </div>
        </div>
    </div>                   
       <!-- modal for edit -->
    <div class="modal fade" id="editmodal" tabindex="-1" aria-labelledby="editmodal" aria-hidden="true">
        <div class="modal-dialog modal-xl ">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title">Assign Ticket</h5> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>
                <div class="modal-body">  
                    <form action="aticket_db.php" method="post" id="editform">

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn rounded-pill bg-secondary text-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn rounded-pill common-btn " name="edit"  form="editform">Save</button>
                </div>
            </div>
        </div>
    </div>                    
</div>
<script>
    $('#aticket').addClass('active');
        
    $(document).on('click', '.assign', function() {
        
        var sr = $(this).attr('id');
        
        $.ajax({
            url: 'aticket_modal.php',
            type: 'post',
            data: {
                sr: sr
            },
            // dataType: 'json',
            success: function(data) {
                $('#assignform').html(data);
                $('#assignmodal').modal('show');
            }
        });

    });

    $(document).on('click','.edit',function(){

        var sr = $(this).attr('id'); 
       
        $.ajax({
            url:'aticketedit_modal.php',
            type: 'post',
            data: {sr:sr},  
            // dataType: 'json',
            success:function(data)
            {
            $('#editform').html(data);  
            $('#editmodal').modal('show');
            }
        });
    });
    
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
            "order": [
                [1, 'desc']
            ],
            buttons: ['pageLength', {
                    text: 'Pending',
                    className: 'pending',
                },
                {
                    text: 'View All',
                    className: 'viewall',
                }
            ],
            language: {
                searchPlaceholder: "Search..."
            }
        });
    });
</script>