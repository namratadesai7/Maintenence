<?php
include('../includes/dbcon.php');
include('../includes/header.php');
?>
<title>
    Report
</title>

<!-- <link href='../css/select2.min.css' rel='stylesheet' type='text/css'> -->

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
        @media only screen and (max-width:2600px) {
        #distable{
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
      /*ADD dropdown searching*/
		/* .select2-container {
			max-width: 100%;
		}
		.select2-container .select2-selection--single{
			height:39px !important;
			border: 1px solid #ccc !important;
		} */
</style>
<div class="container-fluid fl" >
    <div class="row mb-3">
        <div class="col"><h4 class="pt-2 mb-0">Dispatch From Update</h4></div>
        <!-- <div class="col-auto"> <a class="btn btn-warning p-2" href="summary_add.php">+Add</a></div> -->
    </div>
    <form id="report">
        <div class="divCss ">           
            <div class="row px-2">
                
                <label class="form-label col-lg-3 col-md-6" for="user">Created By     
                    <input type="text" class="form-control searchInput user" name="user" id="user" onFocus="Searchname(this)" placeholder="User Name"></input>
                </label>

                <label class="form-label col-lg-3 col-md-6" for="assignto">Assign to     
                    <input type="text" class="form-control searchInput assignto" name="assignto" id="assignto" onFocus="Searchassignname(this)" placeholder="Assign to"></input>
                </label>

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
                
                
                <!-- <label class="form-label col-lg-3 col-md-6" for="ticketno">Ticket No.      -->
                    <!-- <input type="text" class="form-control searchInput ticketno" name="ticketno" id="ticketno"  placeholder="Ticket No."></input> -->
                    <!-- <select name="ticketno" id="ticketno" class="form-control ticketno">
                        <option></option>
                        <?php 
                            $query ="SELECT top 100 srno  FROM ticket ";
                            $run1 = sqlsrv_query($conn,$query);
                            while($row1=sqlsrv_fetch_array($run1,SQLSRV_FETCH_ASSOC)){
                        ?>
                        <option ><?php echo $row1['srno'] ?></option>
                        <?php } ?>
                    </select>
                </label> -->

                <label class="form-label col-lg-3 col-md-6" for="ticketno">Ticket No.     
                    <input type="text" class="form-control searchInput ticketno" name="ticketno" id="ticketno"  onFocus="Searchtid(this)"  placeholder="Ticket No."></input> 
                </label>
            </div>
            <div class="row px-2">
                <label class="form-label col-lg-2 col-md-6" for="cfrom">Created From
                    <input type="date" class="form-control searchInput cfrom" name="cfrom" id="cfrom" ></input>
                </label>    

                <label class="form-label col-lg-2 col-md-6" for="cto">Created To
                    <input type="date" class="form-control searchInput cto" name="cto" id="cto" ></input>
                </label> 

                <label class="form-label col-lg-2 col-md-6" for="afrom">Assigned From
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
                </label>                
            </div>
            <div class="row">
                <div class="col"></div>
                <div class="col-auto">
                    <button type="button" class="btn btn-rounded rounded-pill btn-danger search" id="search">Search</button>                         
                </div>
            </div>
        </div><br>
    </form>
    <div class="divCss" id="showdata" >
        <table class="table table-bordered text-center table-striped table-hover mb-0" id="distable">
            <thead>
                <tr class="bg-secondary text-light">
                    <th>Sr</th>
                    <th>Ticket<br>ID</th>
                    <th>Priority</th>
                    <th>Prod Stop</th>
                    <th>Status<br>Team </th>
                    <th>Create<br>Date </th>
                    <th>Created<br>By</th>
                    <th>M/C No.</th>
                    <th>Department </th>
                    <th>Plant</th>
                    <th>Issue</th>
                    <th>Remark C</th> 
                    <!-- From assign -->
                    <th>Assign<br>to </th>
                    <th>Assign<br>Date </th>
                    <th>Approx<br>time</th>
                    <th>Unit</th>
                    <th>Update </th>
                    <th>Sub Cat</th>
                    <th>Role</th>
                    <!-- <th>Close Status</th> -->
                    <th>Resolved<br>Time</th>
                    <th>Parts<br>Change</th>
                    <th>Remarks<br>Team</th>
                    <th>Close<br>Date</th>
                    <th>Days</th>      
                    <th>Transferred</th>  
                    <th>Delayed</th>               
                </tr>
            </thead>
            <tbody>
            
            </tbody>
        </table>
    </div>
</div>
<?php
include('../includes/footer.php');
?>
<!-- <script src='../js/select2.min.js' type='text/javascript'></script> -->

<script>

    // $(document).ready(function() {
	// 	$(".ticketno").select2();
	// });
$(document).ready(function() {
    var initialLength = 100; // Set the initial number of rows to load
    var isServerSide = false; // Flag to track server-side processing

    var dataTable = $('#distable').DataTable({
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
            'pageLength', 'copy', 'excel'
            
        ],
        language: {
            searchPlaceholder: "Search..."
        }
    });

  

});


     
  
$('#report').addClass('active');

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
        url:'report_data1.php',
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