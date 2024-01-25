
<?php
include('../includes/dbcon.php');
include('../includes/header.php');

$teamlead=$_SESSION['isteamlead'];

$condition='';
if($teamlead=='tool'){
    $condition.=" and t.room='tool' "  ;

}else if($teamlead=='maint'){
    $condition.=" and t.room='maintenance'";
}
    $sql="	WITH abc AS (

        SELECT COUNT(*) AS cnt, ticket_id
        FROM assign
        GROUP BY ticket_id
        HAVING COUNT(*) % 2 = 0 
        ),
        XYZ as(SELECT COUNT(*) AS cnt, ticketid
            FROM uwticket_head 
            GROUP BY ticketid HAVING  COUNT(*) % 2 = 1 
        AND SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) > 0
        )
        SELECT top 100
        a.istransfer, t.srno, a.ticket_id, t.date, t.username, t.mcno, t.department, t.plant,
        t.issue, t.remark, t.pstop,t.image,t.audio,t.video,t.room,a.srno AS asr, a.assign_to, FORMAT(assign_date, 'yyyy-MM-dd') AS adate,
        a.approx_time, t.priority, t.pstop, a.unit, a.update_assign, a.cat, a.subcat, a.role,
        u.resolved_time, u.approx_cdate
        FROM
        assign a
        right  join
        ticket t ON a.ticket_id = t.srno
        left join
        uwticket_head u ON u.ticketid = a.ticket_id
        
        WHERE
        t.isdelete=0 and (approx_cdate='1900-01-01' and  resolved_time='')
        and ticket_id not in(select ticket_id from abc
        ) and ticket_id not in(select ticketid from xyz)or assign_to is null".$condition;

$run =sqlsrv_query($conn,$sql);
$at=$row1['assign_to'] ?? '' ;
?>
<title>
    Assign Ticket
</title>

<style>
    #aud{
        width:80px ;
        height: 40px;
    }
    #imgc{
        display:flex;
        justify-content:center;
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

     .pendingg {
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
                        <option selected default value=""></option>
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

                 <label class="form-label col-lg-3 col-md-6" for="afrom">Assigned From
                    <input type="date" class="form-control searchInput afrom" name="afrom" id="afrom" ></input>
                </label>    

                <label class="form-label col-lg-3 col-md-6" for="ato">Assigned To
                    <input type="date" class="form-control searchInput ato" name="ato" id="ato" ></input>
                </label> 
<!-- 
                <label class="form-label col-lg-2 col-md-6" for="clfrom">Closed From
                    <input type="date" class="form-control searchInput clfrom" name="clfrom" id="clfrom" ></input>
                </label>    

                <label class="form-label col-lg-2 col-md-6" for="clto">Closed To
                    <input type="date" class="form-control searchInput clto" name="clto" id="clto" ></input>
                </label>                -->
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
            <h4 class="pt-2 mb-0 ">Assign Tickets</h4>
        </div>
    </div>
    <div id="putTable" class="divCss">
        <table class="table table-bordered text-center table-striped table-hover mb-0 " id="assignTable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Action</th>
                    <th>Ticket <br>ID</th>
                    <th>Priority</th>
                    <th>Prod<br>Stop</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Created By</th>
                    <th>M/c No</th>
                    <th>Department</th>
                    <th>Plant</th>
                    <th>Issue</th>
                    <th>Img/Aud/Vid</th>
                    <th>Type</th>
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
                                  
                                <a type="button" class="btn btn-success btn-sm assign rounded-pill assign-button" id="<?php echo $row['srno'] ?>"
                                    data-name="<?php echo $row['asr'] ?>" >Assign</a> 
                            
                                 <!-- <a type="button" class="btn btn-primary rounded-pill btn-sm edit"                                
                                id="<?php echo $row['srno']  ?>">Edit</a>  -->
                                                                                                                                
                                <a type="button" class="btn btn-danger btn-sm rounded-pill" 
                                href="aticket_db.php?deleteid=<?php echo $row['srno']?>&asr=<?php echo $row['asr'] ?>" 
                                onclick="return confirm('Are you sure you want to delete the ticket? Once you click ok it will be removed from the below table?')" name="delete">Cancel</a>
                            </td>
                            <td><?php echo $row['srno'] ?></td>
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
                            <td><?php echo $row['date']->format('d-m-Y') ?></td>
                            <td><?php echo $row['username'] ?></td>
                            <td><?php echo $row['mcno'] ?></td>
                            <td><?php echo $row['department']?></td>
                            <td><?php echo $row['plant']?></td>
                            <td><?php echo $row['issue']?></td>

                        

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
<!-- modal for image -->
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
<?php
include('../includes/footer.php');
?>
<script>
    $('#aticket').addClass('active');

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
        
    $(document).on('click', '.assign', function() {
        
        var sr = $(this).attr('id');
        var st= $(this).closest('tr').find('.st').text();
        var asr = $(this).data('name');
        
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
        var isServerSide = false;
       var dataTable= $('#assignTable').DataTable({
            "processing": true,
            "serverSide": isServerSide,
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
                    // action:function(){
                    //     $('#spinLoader').html('<span class="spinner-border spinner-border-lg mx-2"></span><p>Loading..</p>');
                    //     $('#putTable').css({"opacity":"0.5"});

                    //     $.ajax({
                    //         url:'aticket_view.php',
                    //         type:'post',
                    //         data:{ },
                    //         success:function(data){
                    //             $('#putTable').html(data);
                    //             $('#spinLoader').html('');
                    //             $('#putTable').css({"opacity":"1"});
                    //         }
                    //     });
                    // }
                },
                {
                    text:'Pending', className:'pendingg',
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
        $('.viewall').on('click', function() {
         
            isServerSide = true; // Switch to server-side processing
            dataTable.destroy(); // Destroy the current DataTable instance
            dataTable = $('#assignTable').DataTable({
                "processing": true,
                "serverSide": isServerSide,
                "ajax": {
                    "url": "aticket_view.php",
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
                    'pageLength', 'copy', 'excel',
                    {
                        text:'Back', className:'back',
                        action:function(){
                            window.location.reload();
                        }
                    }
                ],
                language: {
                    searchPlaceholder: "Search..."
                }
            });
        });
    });

    $(document).on('click','#search', function(){

        var pending=$('#pending').val();
        var ticketno=$('#ticketno').val();
        var cfrom=$('#cfrom').val();
        var cto=$('#cto').val();
        var afrom=$('#afrom').val();
        var ato=$('#ato').val();
   
        $.ajax({    
            url:'aticket_search.php',
            type:'post',
            data:{pending:pending,ticketno:ticketno,cfrom:cfrom,cto:cto,afrom:afrom,ato:ato},
            success:function(data){
            
                $('#putTable').html(data);
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