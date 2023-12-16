<?php
include('../includes/dbcon.php');
include('../includes/header.php');

// $sql = "SELECT t.srno,a.ticket_id,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,a.assign_to,t.priority,t.pstop,a.unit
//  FROM assign a full outer join ticket t on a.ticket_id =t.srno
// where t.isdelete=0 and assign_to is null";
// $sql="SELECT t.srno,a.ticket_id,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,a.srno as asr,a.assign_to,format(assign_date,'yyyy-MM-dd') as adate,a.approx_time,t.priority,t.pstop,a.unit,a.update_assign,a.cat,a.subcat,
// a.role,u.resolved_time,u.approx_cdate
// FROM assign a full outer join ticket t on a.ticket_id =t.srno
// full outer join uwticket_head u on u.ticketid=a.ticket_id 
// where t.isdelete=0  and (approx_cdate='1900-01-01' and  resolved_time='') or assign_to is null and istransfer=0";
$sql="	WITH abc AS (

SELECT COUNT(*) AS cnt, ticket_id
FROM assign
GROUP BY ticket_id
HAVING COUNT(*) % 2 = 0
)

SELECT
   a.istransfer, t.srno, a.ticket_id, t.date, t.username, t.mcno, t.department, t.plant,
   t.issue, t.remark, t.pstop, a.srno AS asr, a.assign_to, FORMAT(assign_date, 'yyyy-MM-dd') AS adate,
   a.approx_time, t.priority, t.pstop, a.unit, a.update_assign, a.cat, a.subcat, a.role,
   u.resolved_time, u.approx_cdate
FROM
   assign a
FULL OUTER JOIN
   ticket t ON a.ticket_id = t.srno
FULL OUTER JOIN
   uwticket_head u ON u.ticketid = a.ticket_id

WHERE
 t.isdelete=0 and (approx_cdate='1900-01-01' and  resolved_time='')
 and ticket_id not in(select ticket_id from abc
) or assign_to is null

";
$run = sqlsrv_query($conn,$sql);
$at=$row1['assign_to'] ?? '' ;
?>
<title>
    Assign Ticket
</title>
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
    table.dataTable {
    border-collapse: collapse;
    }

    th {
        white-space: nowrap;
        font-size: 15px;
        padding: 8px 15px 8px 8px;
    }

    td {
        white-space: nowrap;
        font-size: 14px;
        padding-left: 6px;
        font-weight:500;
    }

    .pending {
        background: #FFC04C !important;
    }

    .viewall {
        background: #7DE5F6 !important;
    }
    @media only screen and (max-width:2600px) {
        #assignTable {
            padding: 0 !important;
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
<div class="container-fluid fl">
    <div class="row mb-3">
        <div class="col">
            <h4 class="pt-2 mb-0 ">Assign Tickets</h4>
        </div>

    </div>
    <div id="putTable" class="divCss">
        <table class="table table-bordered text-center table-striped table-hover mb-0 " id="assignTable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Action</th>
                    <th>Priority</th>
                    <th>Prod<br>Stop</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Created By</th>
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
                  
                </tr>
            </thead>
            <tbody>
                <?php
                     $sr=1;
                     while($row= sqlsrv_fetch_array($run, SQLSRV_FETCH_ASSOC)) {                     
                    ?>
                        <tr>
                            <td><?php echo $sr ?></td>
                            <td style="padding: 3px 6px !important;"> 
                            <?php
                                if($row['assign_to']== ''){ ?>
                                  <a type="button" class="btn btn-success btn-sm assign rounded-pill assign-button" id="<?php echo $row['srno'] ?>"
                                    data-name="<?php echo $row['asr'] ?>" >Assign</a> 
                                                             
                                <?php }else{
                                    ?>
                                    <a type="button" class="btn btn-primary rounded-pill btn-sm edit"
                                
                                id="<?php echo $row['srno']  ?>">Edit</a> 
                                    <?php
                                } ?>
                                                                                                 
                                <a type="button" class="btn btn-danger btn-sm rounded-pill" 
                                href="aticket_db.php?deleteid=<?php echo $row['srno']?>&asr=<?php echo $row['asr'] ?>" 
                                onclick="return confirm('Are you sure you want to delete the ticket? Once you click ok it will be removed from the below table?')" name="delete">Cancel</a>
                            </td>
                            <td> <?php echo $row['priority'] ?></td>
                            <td> <?php echo $row['pstop'] ?></td>
                            <?php
                            if($row['ticket_id']==null){

                                ?>
                                <td class="st">Unassigned</td>
                                <?php
                            }else{
                                ?>
                                  <td class="st">Transfer</td>   
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
                            <td><?php echo $row['assign_to'] ?? '' ?></td>
                            <td> <?php echo $row['adate'] ?? '' ?></td>
                            <td><?php echo $row['approx_time'] ?? '' ?> </td>
                            <td> <?php echo $row['unit'] ?? '' ?></td>
                            <td> <?php echo $row['update_assign'] ?? '' ?></td>
                            <td><?php echo $row['cat'] ?? '' ?> </td>
                            <td> <?php echo $row['subcat'] ?? '' ?></td>
                            <td><?php echo $row['role'] ?? '' ?> </td>                           
                        </tr>                    
                    <?php
                    $sr++; }
                ?>
            </tbody>
        </table>
    </div>
    <div id="spinLoader"></div>
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
<?php
include('../includes/footer.php');
?>
<script>
    $('#aticket').addClass('active');
        
    $(document).on('click', '.assign', function() {
        
        var sr = $(this).attr('id');
        var st= $(this).closest('tr').find('.st').text();
        var asr = $(this).data('name');
        console.log(asr)
        console.log(st)
        
        $.ajax({
            url: 'aticket_modal.php',
            type: 'post',
            data: {
                sr:sr,st:st,asr:asr
            },
            // dataType: 'json',
            success:function(data) {
                $('#assignform').html(data);
                $('#assignmodal').modal('show');
            }
        });

    });

    $(document).on('click','.edit',function(){

        var sr = $(this).attr('id');
        var st= $(this).closest('tr').find('.st').text();
       
        $.ajax({
            url:'aticketedit_modal.php',
            type: 'post',
            data: {sr:sr,st:st},  
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
    // $(document).ready(function(){
	// 	var table = $('#scrapTable').DataTable({   // initializes a DataTable using the DataTables library 
	// 	    "processing": true,                  //This option enables the processing indicator to be shown while the table is being processed
	// 		 dom: 'Bfrtip',                      // This option specifies the layout of the table's user interface B-buttons,f-flitering input control,T-table,I-informationsummary,P-pagination
	// 		 ordering: false,                   //sort the columns by clicking on the header cells if true
	// 		 destroy: true,                     //This option indicates that if this DataTable instance is re-initialized, 
    //                                             //the previous instance should be destroyed. This is useful when you need to re-create the table dynamically.
            
	// 	 	lengthMenu: [
    //         	[ 15, 50, -1 ],
    //         	[ '15 rows','50 rows','Show all' ]
    //     	],
	// 		 buttons: [
	// 	 		'pageLength','copy', 'excel'
    //     	]
    // 	});
 	// });
</script>