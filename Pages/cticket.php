<?php
include('../includes/dbcon.php');
include('../includes/header.php');  
$date=date('Y-m-d');
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
        .form-control{
            width:40%;
        }
        .form-select{
            width:40%;
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
        #ctickettable{
            width:100% !important;
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
        @media only screen and (max-width:1700px) {
            #ctickettable{
                display: block;
                overflow-x: auto;
                float: none !important;
                width: 100%; 
            }}        
                
                /* Set width to 100%
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
        /* .tooltip {
            position: relative;
            display: inline-block;
            border-bottom: 1px dotted black;
        } */
        .tooltip-cell {
            position: relative;
            /* display: inline-block; */
            border-bottom: 1px dotted black;
        }

        .tooltip-cell .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black !important;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;

            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            top: 100%; /* Position the tooltip below the cell */
            left: 50%; /* Center the tooltip horizontally */
            transform: translateX(-50%); /* Center the tooltip horizontally */
        }

        .tooltip-cell:hover .tooltiptext {
            visibility: visible;
        }

    </style>
        <title>Create Ticket</title>
    <div class="container-fluid fl ">
        <div class="row mb-3">
            <div class="col">
                <h4 class="pt-2 mb-0">Create Ticket</h4>
            </div>
            <div class="col-auto">
                <a href="cticket_add.php"  class="btn rounded-pill common-btn mt-2 " name="add">Add</a>
            </div>
        </div>
        <div class="divCss">
           <table class="table table-bordered text-center table-striped table-hover mb-0" id="ctickettable">
                <thead>
                     <tr class="bg-secondary text-light">
                        <th>Sr</th>
                        <th>Action</th>
                        <th>Prod<br>Stop</th>
                        <th>Status </th>
                        <th>Date </th>
                        <th>Created By</th>
                        <th>M/C No.</th>
                        <th>Department </th>
                        <th>Plant</th>
                        <th >Issue</th>
                        <th>Remark</th>
                        <th>Assign<br>to </th>
                        <th>Approx<br>time</th>
                        <th>Unit</th>
                        <th>Update</th>                     
                    </tr>
                </thead>
                <tbody> 
                    <?php
                        $sr=1;
                        $sql="SELECT u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,u.ticketid,a.assign_to,a.approx_time,a.unit,a.istransfer,
                        a.update_assign FROM assign a full outer join ticket t on a.ticket_id =t.srno
                        full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0 and (a.istransfer=0 or a.istransfer is null) and username='".$_SESSION['sname']."'";
                        
                        $run=sqlsrv_query($conn,$sql);
                        while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                        ?>
                    <tr>
                        <td><?php echo $sr ?></td>
                        <td style="padding: 3px 6px !important;"><button type="button" class="btn btn-sm rounded-pill btn-primary edit" id="<?php echo $row['srno']  ?>" >Edit</button>
                            <button type="button" class="btn btn-sm rounded-pill btn-danger delete" id="<?php echo $row['srno']  ?>"><i class="fa-solid fa-trash"></i></button>
                        </td>
                        <td><?php echo $row['pstop'] ?></td>
                        <?php
                        //  $sql2="SELECT u.resolved_time,u.approx_cdate,t.srno,u.ticketid ,a.assign_to
                        //  FROM uwticket_head u full outer join ticket t on u.ticketid=t.srno
                        //  full outer join assign a on u.ticketid=a.ticket_id  where t.isdelete=0 and t.srno='".$row['srno']."'";
                        //  $run2=sqlsrv_query($conn,$sql2);
                        //  $row2=sqlsrv_fetch_array($run2,SQLSRV_FETCH_ASSOC);

                        
                        //  $rt=$row2['resolved_time'] ?? '' ;
                        //  $ct=$row2['approx_cdate'] ?? '';
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
                                    <td>Transferred</td>
                                    <?php
                                }
                                else if($row['approx_cdate']->format('Y')=='1900' && $row['istransfer']==0){
                                    ?>
                                    <td>Assigned</td>
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
                     
                        <td class="tooltip-cell" title="<?php echo $row['issue'] ?>" style="max-width: 70px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo $row['issue'] ?>
                            <span class="tooltiptext"><?php echo $row['issue'] ?></span>
                        </td>
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
        </div>
    </div>
    <body>
   
    </body>
<script>
  $('#cticket').addClass('active');

   // datatable to table
   $(document).ready(function() {
        $('#ctickettable').DataTable({
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
		 		'pageLength','copy', 'excel'
        	],
            language: {
                searchPlaceholder: "Search..."
            }
        });
    });

  $(document).on('click','.edit',function(){
    var edit=$(this).attr('id');
    window.open('cticket_edit.php?edit='+edit,'_self');

  })
  
  $(document).on('click','.delete',function(){
    var del=$(this).attr('id');
    if(confirm('Are you sure!')){
        window.open('cticket_db.php?del='+del,'_self');
    }else{
        return false;
    }
  
  })
</script>
<?php

include('../includes/footer.php');
?>