
<?php
include('../includes/dbcon.php');
include('../includes/header.php');  
$sname=$_SESSION['sname'];

$date=date('Y-m-d');

$condition='';
if( $_SESSION['urights']!="admin"){

   
    // $condition.=" and a.assign_to='".$_SESSION['sname']."' and (u.status='' or u.status is null) and (u.istransfer='' or u.istransfer is null) ";
      
    $condition.=" and a.assign_to='".$_SESSION['sname']."' and (u.istransfer='0' or u.istransfer is null) ";
}
// $sql="SELECT a.istransfer,a.srno,t.srno as tsr,a.priority,t.pstop,format(t.date,'dd-MM-yyyy') as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.remark as remarkc,a.ticket_id,a.assign_to,
// a.assign_date,a.approx_time,a.unit,a.update_assign,
// a.subcat, a.role ,u.c_date,format(u.c_date,'dd-MM-yyyy') as abc,u.resolved_time,u.approx_cdate,u.no_of_parts,u.remark,u.ticketid

// FROM assign a full outer join ticket t on a.ticket_id=t.srno
// full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0  and assign_to is not null ".$condition;
$sql="SELECT u.Status,a.istransfer,a.srno,t.srno as tsr,a.priority,t.pstop,format(t.date,'dd-MM-yyyy') as cdate,t.username,t.mcno,t.department,t.plant,t.issue,t.remark as remarkc,a.ticket_id,a.assign_to,
a.assign_date,a.approx_time,a.unit,a.update_assign,
a.subcat, a.role ,u.c_date,format(u.c_date,'dd-MM-yyyy') as abc,u.resolved_time,u.approx_cdate,u.no_of_parts,u.remark,u.ticketid

FROM assign a full outer join ticket t on a.ticket_id=t.srno
full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0  and assign_to is not null and (u.Status <> 'closed' or u.Status is null)   and (CONCAT(t.srno,'/',u.createdAt) in 
    (select CONCAT(ticketid,'/',max(createdAt)) from uwticket_head group by ticketid) OR u.createdAt is NULL)  ".$condition;
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
    .viewall {
    background: #7DE5F6 !important;
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
        font-size: 15px;
        padding: 8px 15px 8px 8px;
    }
    #uwtickettable td{
        white-space:nowrap;
        font-size: 14px;
        padding-left: 6px;
        font-weight:500;
    }
    @media only screen and (max-width:2600px) {
        #uwtickettable {
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
        <div id="putTable" class="divCss">
           <table class="table table-bordered text-center table-striped table-hover mb-0" id="uwtickettable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Action</th>
                    <th>Ticket <br>ID</th>
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
                    <td style="padding: 3px 6px !important;">
                        <button type="button" class="btn btn-sm rounded-pill btn-primary close" 
                             id="<?php echo $row['ticket_id'] ?>" 
                            data-name="<?php echo $row['srno'] ?>"   >Action</button>
                        <!-- <button type="button" class="btn btn-sm rounded-pill btn-primary close" <?php if($row['ticketid']==$row['ticket_id']){
                            ?> disabled <?php
                        } ?> id="<?php echo $row['ticket_id'] ?>" 
                        data-name="<?php echo $row['srno'] ?>">Action</button> -->
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
           </table>
        </div>
        <div id="spinLoader"></div>
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
                    data: {iname: request.term },
                    success: function( data ) {
                        response( data );
                    },
                    error:function(data){
                        console.log(data);
                    }
                });
            },
            select: function (event, ui) {
                $(this).val(ui.item.label);
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
            $('.ui-autocomplete').css('width', '300px'); 
           }
        });
    } 
   
  

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
        buttons: [
		 		'pageLength','copy', 'excel',
                 {
                    text:'ViewAll', className:'viewall',
                    action:function(){
                        $('#spinLoader').html('<span class="spinner-border spinner-border-lg mx-2"></span><p>Loading..</p>');
                        $('#putTable').css({"opacity":"0.5"});

                        $.ajax({
                            url:'uwticket_view.php',
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
<?php

include('../includes/footer.php');
?>

