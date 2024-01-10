<?php
include('../includes/dbcon.php');
include('../includes/header.php');  
$date=date('Y-m-d');



$condition='';
if( $_SESSION['urights']!="admin"){

    $condition.=" and (a.istransfer=0 or a.istransfer is null) and username='".$_SESSION['sname']."'";
}

?>

<style>
     #aud{
        width:80px ;
        height: 40px;
    }
    #imgc{
        display:flex;
        flex-direction:row;
        justify-content:center !important;
       
    }
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
        text-align:center !important;
        /* padding: 30px 6px 10px 6px !important; */
        font-weight:500;
        text-align:center;
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
                        $sql="SELECT top 100 u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,t.image,t.audio,t.video,u.ticketid,a.assign_to,a.approx_time,a.unit,a.istransfer,u.istransfer as utrans
                        ,a.update_assign FROM assign a full outer join ticket t on a.ticket_id =t.srno
                        full outer join uwticket_head u on u.ticketid=a.ticket_id  where t.isdelete=0  ".$condition;
                        
                        $run=sqlsrv_query($conn,$sql);
                        while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                            $sql1="SELECT COUNT(*) AS cnt, ticket_id
                            FROM assign where ticket_id='".$row['srno']."'
                            GROUP BY ticket_id";                 
                            $run1 = sqlsrv_query($conn,$sql1);
                            $row1 = sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC);
                        ?>
                    <tr>
                        <td><?php echo $sr ?></td>
                        <!-- style="padding: 3px 6px !important;" -->
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
                        <td><?php echo $row['date']->format('Y-m-d') ?></td>
                        <td><?php echo $row['username'] ?></td>
                        <td><?php echo $row['mcno'] ?></td>
                        <td><?php echo $row['department'] ?></td>
                        <td><?php echo $row['plant'] ?></td>
                     
                        <td class="tooltip-cell" title="<?php echo $row['issue'] ?>" style="max-width: 70px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                <?php echo $row['issue'] ?>
                            <span class="tooltiptext"><?php echo $row['issue'] ?></span>
                        </td>
                        <td  style="padding: 3px 6px !important;" id="img"  data-name="<?php echo $row['srno'] ?>"
>
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
        <!-- modal for assign -->
    <div class="modal fade" id="imgvidaud" tabindex="-1" aria-labelledby="imgvidaud" aria-hidden="true">
        <div class="modal-dialog modal-xl ">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title">Image/Audio/Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="imgaud">
                    <?php 


                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn rounded-pill bg-secondary text-light"
                        data-bs-dismiss="modal">Close</button>       
                </div>
            </div>
        </div>
    </div>   
    <body>
   
    </body>

<script>
  $('#cticket').addClass('active');

    $(document).on('click', '#img', function() {
        var id=$(this).data('name');
        console.log(id);
            
            $.ajax({
                url: 'cticket_img.php',
                dataType:'json',
                type: 'post',
                data: {id:id       
                },
                // dataType:'json',
            
                success:function(data) {
                    console.log(data);
                       // Clear existing content before appending new content
                    $('#imgaud').html('');

                    // Iterate through each content entry in the array
                    data.forEach(function(entry) {
                        // Create a container for each content type
                        var container = $('<div id="imgc">');

                        // Append the content to the respective container
                        container.html(entry.content);

                        // Append the container to the main container (#imgaud)
                        $('#imgaud').append(container);
                    });
                        $('#imgvidaud').modal('show');
                },
                error:function(data){
                    console.log(data);
                }
            });

        });
   // datatable to table
//    $(document).ready(function() {
//         $('#ctickettable').DataTable({
//             "processing": true,
//             "lengthMenu": [10, 25, 50, 75, 100],
//             "responsive": {
//                 "details": true
//             },
//             "columnDefs": [{
//                 "className": "dt-center",
//                 "targets": "_all"
//             }],
//             dom: 'Bfrtip',
//             ordering: true,
//             destroy: true,
          
//             buttons: [
// 		 		'pageLength','copy', 'excel'
//         	],
//             language: {
//                 searchPlaceholder: "Search..."
//             }
//         });
//     });

// $(document).ready(function() {
//     var dataTable = $('#ctickettable').DataTable({
//         "processing": true,
//         "serverSide": true,
//         "ajax": {
//             "url": "server-side-script.php", // Replace with your server-side script
//             "type": "POST",
//              "data": function(d) {
//                 d.start = d.start || 0; // DataTables uses 'start' instead of 'offset'
//                 d.length = d.length || 10; // Default length if not provided
//                 d.draw=d.draw|| 1; 
//             }
//         },
//         "lengthMenu": [10, 25, 50, 75, 100],
//             "responsive": {
//                 "details": true
//             },
//             "columnDefs": [{
//                 "className": "dt-center",
//                 "targets": "_all"
//             }],
//             dom: 'Bfrtip',
//             ordering: true,
//             destroy: true,
          
//             buttons: [
// 		 		'pageLength','copy', 'excel'
//         	],
//             language: {
//                 searchPlaceholder: "Search..."
//             }
//         // Other DataTable options...
//     });

//     // Add a custom "View All" button to show the next chunk of records
//     console.log('Button created!');
//     $('<button class="btn btn-primary btn-sm" style="display: block;">View More</button>')
//         .appendTo('.dataTables_length')
//         .on('click', function() {
//             dataTable.page('next').draw('page');
//         });
// });

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
                    text:'ViewMore', className:'viewMore',
            }
        ],
        language: {
            searchPlaceholder: "Search..."
        }
    });

    // Add a custom "View More" button to switch to server-side processing
    console.log('Button created!');
    // $('<button class="btn btn-primary btn-sm" style="display: block;">View More</button>')
    //     .appendTo('.dataTables_length')
    //     .css('visibility', 'visible') 
        $('.viewMore').on('click', function() {
            isServerSide = true; // Switch to server-side processing
            dataTable.destroy(); // Destroy the current DataTable instance
            dataTable = $('#ctickettable').DataTable({
                "processing": true,
                "serverSide": isServerSide,
                "ajax": {
                    "url": "server-side-script.php",
                    "type": "POST",
                    "data": function(d) {
                        d.start = d.start || 0;
                        d.length = d.length || 10;
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