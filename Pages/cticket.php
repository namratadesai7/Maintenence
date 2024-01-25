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
    /* .form-control{
        width:40%;
    }
    .form-select{
        width:40%;
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
           
            <form id="report">
                <div class="divCss ">           
                    <div class="row px-2">
                        
                        <!-- <label class="form-label col-lg-3 col-md-6" for="user">Created By     
                            <input type="text" class="form-control searchInput user" name="user" id="user" onFocus="Searchname(this)" placeholder="User Name"></input>
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="assignto">Assign to     
                            <input type="text" class="form-control searchInput assignto" name="assignto" id="assignto" onFocus="Searchassignname(this)" placeholder="Assign to"></input>
                        </label> -->

                        <label class="form-label col-lg-3 col-md-6" for="pending">Pending     
                            <!-- <input type="text" class="form-control searchInput pending" name="pending" id="pending"  placeholder="User Name"></input> -->
                            <select class="form-select searchInput pending" name="pending" id="pending">
                                <option  selected default value=""></option>
                                <option value="assigned">Assigned</option>
                                <option value="unassigned">Unassigned</option>
                                <option value="closed">Closed</option>
                                <option value="delayed">Delayed</option>
                                <option value="transferred">Transferred</option>
                            </select>
                        </label>

                        <label class="form-label col-lg-3 col-md-6" for="ticketno">Ticket No.     
                            <input type="text" class="form-control searchInput ticketno" name="ticketno" id="ticketno"  onFocus="Searchtid(this)"  placeholder="Ticket No."></input> 
                        </label>
                       
                        <label class="form-label col-lg-3 col-md-6" for="cfrom">Created From
                            <input type="date" class="form-control searchInput cfrom" name="cfrom" id="cfrom" ></input>
                        </label>    

                        <label class="form-label col-lg-3 col-md-6" for="cto">Created To
                            <input type="date" class="form-control searchInput cto" name="cto" id="cto" ></input>
                        </label>
                    </div>
                    <div class="row px-2">
                        <!-- <label class="form-label col-lg-2 col-md-6" for="cfrom">Created From
                            <input type="date" class="form-control searchInput cfrom" name="cfrom" id="cfrom" ></input>
                        </label>    

                        <label class="form-label col-lg-2 col-md-6" for="cto">Created To
                            <input type="date" class="form-control searchInput cto" name="cto" id="cto" ></input>
                        </label>  -->

                        <!-- <label class="form-label col-lg-2 col-md-6" for="afrom">Assigned From
                            <input type="date" class="form-control searchInput afrom" name="afrom" id="afrom" ></input>
                        </label>    

                        <label class="form-label col-lg-2 col-md-6" for="ato">Assigned To
                            <input type="date" class="form-control searchInput ato" name="ato" id="ato" ></input>
                        </label> 

                        <label class="form-label col-lg-2 col-md-6" for="clfrom">Closed From
                            <input type="date" class="form-control searchInput clfrom" name="clfrom" id="clfrom" ></input>
                        </label>    

                        <label class="form-label col-lg-2 col-md-6" for="clto">Closed To
                            <input type="date" class="form-control searchInput clto" name="clto" id="clto" ></input>
                        </label>                 -->
                    </div>
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-rounded rounded-pill btn-danger search" id="search">Search</button>                         
                        </div>
                    </div>
                </div><br>
            </form>

            <div class="row mb-3">
                <div class="col">
                    <h4 class="pt-2 mb-0">Create Ticket</h4>
                </div>
                <div class="col-auto">
                    <a href="cticket_add.php"  class="btn rounded-pill common-btn mt-2 " name="add">Add</a>
                </div>
            </div>
            <div class="divCss" id="showdata">
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
                            <th>Type</th>
                            <th>Remark</th>
                            <th>Assign<br>to </th>
                            <th>Approx<br>time</th>
                            <th>Update</th>                     
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                            $sr=1;
                            $sql="SELECT top 100 u.resolved_time,u.approx_cdate,t.srno,t.date,t.username,t.mcno,t.department,t.plant,t.issue,t.remark,t.pstop,t.image,t.audio,t.video,u.ticketid,t.room,a.assign_to,a.approx_time,a.unit,a.istransfer,u.istransfer as utrans
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
                            <td  style="padding: 3px 6px !important;" id="img"  data-name="<?php echo $row['srno'] ?>">
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
                            <td><?php echo $row['room']=='maintenance' ? 'main' : $row['room'] ?></td>
                            <td class="tooltip-cell" title="<?php echo $row['remark'] ?>" style="max-width: 70px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <?php echo $row['remark'] ?>
                                <span class="tooltiptext"><?php echo $row['remark'] ?></span>
                            </td>    
                            
                    
                            <!-- <td><?php echo $row['issue'] ?></td>
                            <td><?php echo $row['remark'] ?></td> -->
                            <td><?php echo $row['assign_to']  ?></td>
                            <td><?php echo $row['approx_time'].' '.$row['unit'] ?></td>
                            <!-- <td><?php echo $row['unit'] ?></td> -->
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
                "scrollX": true,
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

    $(document).on('click','#search', function(){

        var user=$('#user').val();
        var assignto=$('#assignto').val();
        var pending=$('#pending').val();
        var ticketno=$('#ticketno').val();
        var cfrom=$('#cfrom').val();
        var cto=$('#cto').val();
        var afrom=$('#afrom').val();
        var ato=$('#ato').val();
        var clfrom=$('#clfrom').val();
        var clto=$('#clto').val();

        $.ajax({    
            url:'cticket_search.php',
            type:'post',
            data:{user:user,assignto:assignto,pending:pending,ticketno:ticketno,cfrom:cfrom,cto:cto,afrom:afrom,ato:ato,clfrom:clfrom,clto:clto},
            success:function(data){
            
                $('#showdata').html(data);
            },
            error:function(res){
                console.log(res);
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
                        console.log(data)
                        response( data );
                    },
                    error:function(data){
                        console.log(data);
                    }
                });
            },
            select: function (event, ui) {
                $('#user').val(ui.item.label);
            
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
        
    function Searchassignname(txtBoxRef) {
      
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
                        console.log(data)
                        response( data );
                    },
                    error:function(data){
                        console.log(data);
                    }
                });
            },
            select: function (event, ui) {
                $('#assignto').val(ui.item.label);
            
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
function Searchtid(txtBoxRef) {
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
                    data: {tid: request.term },
                    success: function( data ) {
                  
                        response( data );
                    },
                    error:function(data){
                        console.log(data);
                    }
                });
            },
            select: function (event, ui) {
                $('#ticketno').val(ui.item.label);
            
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
             // Add a scroll bar to the autocomplete dropdown
             $('.ui-autocomplete').css({
                'max-height': '200px', // Set the maximum height for the dropdown
                'overflow-y': 'auto' // Add vertical scrollbar if needed
            });
           }
          });
        }
</script>


<?php

include('../includes/footer.php');
?>