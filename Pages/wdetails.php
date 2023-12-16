
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

        #wdetailtable th{
            white-space:nowrap;
            padding : 8px 10px;
            font-size: 15px;
        }
        #wdetailtable td{
            white-space: nowrap;
            padding : 6px;
            font-size: 15px;
        }
        table.dataTable{
            border-collapse: collapse;
        }
        @media only screen and (max-width:2600px) {
            #wdetailtable {
            display: block;
            overflow-x: auto;
            float: none !important;
            }
        }
        #re{
            white-space: pre-line !important;
            width: 250px !important;
        }
        
    </style>
  <title>User Work Ticket</title>
    <div class="container-fluid fl ">
        <div class="row mb-3">
            <div class="col">
                <h4 class="pt-2 mb-0">Work Details</h4>
            </div>
            <div class="col-auto">
                <a href="wdetails_add.php"  class="btn rounded-pill common-btn mt-2 " name="add">Add</a>
            </div>
        </div>
        <div class="divCss">
           <table class="table table-bordered table-sm text-center table-striped table-hover mb-0" id="wdetailtable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Plant</th>
                    <th>Description</th>
                    <th>Work Type </th>
                    <th>Agency</th>
                    <th style="padding:8px 100px;"  >Remark</th>
                    <th>First consi. date</th>
                    <th>Name of first thinker</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Completed Date</th>
                    <th>Delay days </th> 
                    <th>Responsible person </th>
                    <!-- <th>Reason for being late</th> -->
                    <th>Status finally</th>
                    <th>Actions</th>
                   
                </tr>
            </thead>
            <tbody> 
                <?php
                    $sr=1;
                    $sql="SELECT sr,Plant,Description,Work_type,Agency,Remark,format(fstcons_date,'dd-MM-yyyy')as fdate,status_final,fst_thinker,format(startdate,'dd-MM-yyyy') as sdate,responsible_person,format(completed_date,'dd-MM-yyyy') as completed_date,format(enddate,'yyyy-MM-dd') as caldat ,
                    format(enddate,'dd-MM-yyyy') as caldate FROM workdetail  order by sr ASC";
                    $run=sqlsrv_query($conn,$sql);
                    while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                         if($row['completed_date'] !== null || $row['sdate']=='01-01-1900' ) {
                            $difference = ''; // Set delay days to blank
                         }else {
                             // Calculate the difference in days
                             $endDate = new DateTime($row['caldat']);
                             $currentDate = new DateTime();
                    
                             $difference = $currentDate >= $endDate ? $currentDate->diff($endDate)->format("%a") : -($endDate->diff($currentDate)->format("%a"));
                         }
                ?>
                <tr>
                    <td><?php echo $sr;   ?></td>
                    <td><?php echo $row['Plant'] ?></td>
                    <td><?php echo $row['Description'] ?></td>
                    <td><?php echo $row['Work_type'] ?></td>
                    <td><?php echo $row['Agency'] ?></td>
                    <td id="re"><?php echo $row['Remark'] ?></td>
                    <td><?php echo $row['fdate'] ?></td>
                    <td><?php echo $row['fst_thinker'] ?></td>
                    <?php
                        if( $row['sdate']=='01-01-1900')
                {
                    ?>
                        <td></td>
                        <td></td>

                    <?php
                }  else{
                ?>
                    <td><?php echo $row['sdate']?></td>
                    <td><?php echo $row['caldate']?></td>

                <?php
                }   ?>
                                
                    <td><?php echo $row['completed_date']  ?></td>
                    <td><?php  echo $difference  ?></td> 
                     <td><?php echo $row['responsible_person'] ?></td> 
                
                    <td><?php echo $row['status_final'] ?></td>
                    <td><button type="button" class="btn btn-sm rounded-pill btn-primary edit" id="<?php echo $row['sr']  ?>" >Edit</button>
                        <button type="button" class="btn btn-sm rounded-pill btn-success update" id="<?php echo $row['sr']   ?>" >Followup</button>
                        <button type="button" class="btn btn-sm rounded-pill btn-warning complete" id="<?php echo $row['sr']   ?>" >Completed</button>
                        <!-- <button type="button" class="btn btn-sm rounded-pill btn-danger delete" id="<?php echo $row['sr']  ?>">Delete</button> -->
                    </td>
                <?php
                $sr++;
                    }
                    ?>            
                </tr>
            </tbody>
           </table>
        <!-- update modal -->
            <div class="modal fade" id="upmodal" tabindex="-1" aria-labelledby="upmodal" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header modal-xl">
                            <h5 class="modal-title">Add status</h5> 
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">  
                        <div id="moddiv">

                        </div>

                                  
                            
                        </div>
                        <div class="modal-footer">
                            <button type="button" class=" btn rounded-pill bg-secondary text-light" data-bs-dismiss="modal">Close</button>
                            <!-- <button type="submit" class="btn rounded-pill common-btn save" name="purchaseform" form="purchaseform">Save</button> -->
                        </div>
                     </div>
                </div>
            </div>
            <!-- complete modal -->
            <div class="modal fade" id="compmodal" tabindex="-1" aria-labelledby="compmodal" aria-hidden="true">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h5 class="modal-title">Add completed date</h5> 
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">  
                                <form action="wdetails_db.php" method="post" id="compdiv">

                                </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn rounded-pill bg-secondary text-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn rounded-pill common-btn save" name="savecomp"  form="compdiv">Save</button>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
<script>
  $('#wdetails').addClass('active');

  $(document).ready(function () {
    $('#wdetailtable').DataTable({
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
		 		'pageLength','copy', 'excel'
        	],
      language: {
        searchPlaceholder: "Search..."
      }
    });
});

    $(document).on('click','.update',function(){
     
            var sr = $(this).attr('id'); 
            $.ajax({
                url:'wdetails_modal.php',
                type: 'post',
                data: {sr:sr},  
                // dataType: 'json',
                success:function(data)
                {
                $('#moddiv').html(data);  
                $('#upmodal').modal('show');
                }
            });

        });


    $(document).on('click','.complete',function(){
     
            var sr = $(this).attr('id'); 
            $.ajax({
                url:'wdetcomp_modal.php',
                type: 'post',
                data: {sr:sr},  
                // dataType: 'json',
                success:function(data)
                {
                $('#compdiv').html(data);  
                $('#compmodal').modal('show');
                }
            });

    });

    $(document).on('click','.add',function(){
          
            var sr = $(this).attr('id'); 
         
            $.ajax({
                url:'wdetails_db.php',
                type:'post',
                data:$('#wdetform').serialize(),

                success:function(response){
                   
                //   alert(response);
                  $.ajax({
                    url:'wdetails_modal.php',
                    type: 'post',
                    data: {sr:sr},  
                    // dataType: 'json',
                    success:function(data)
                    {
                    $('#moddiv').html(data);  
                    $('#upmodal').modal('show');
                    }
            });
                    
                }

            })
            
            
        });
  $(document).on('click','.edit',function(){
    var edit=$(this).attr('id');
    window.open('wdetails_edit.php?edit='+edit,'_self');

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

