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

        #ctickettable th{
            white-space:nowrap;
        }
        #ctickettable td{
            white-space:nowrap;
        }
        @media only screen and (max-width:2600px) {
            #ctickettable {
            display: block;
            overflow-x: auto;
            float: none !important;
            }
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
           <table class="table table-borderd text-center table-striped table-hover mb-0" id="ctickettable">
                <thead>
                     <tr class="bg-secondary text-light">
                        <th>Sr</th>
                        <th>Prod Stop</th>
                        <th>Status </th>
                        <th>Date </th>
                        <th>User</th>
                        <th>M/C No.</th>
                        <th>Department </th>
                        <th>Plant</th>
                        <th>Issue</th>
                        <th>Remark</th>
                        <th>Assign to </th>
                        <th>Approx time</th>
                        <th>Unit</th>
                        <th>Update </th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                    <?php
                        $sr=1;
                        $sql="SELECT * FROM ticket where isdelete=0";
                        $run=sqlsrv_query($conn,$sql);
                        while($row=sqlsrv_fetch_array($run,SQLSRV_FETCH_ASSOC)){
                        ?>
                    <tr>
                        <td><?php echo $sr;   ?></td>
                        <td><?php echo $row['pstop'] ?></td>
                        <td><?php echo "status" ?></td>
                        <td><?php echo $row['date']->format('Y-m-d') ?></td>
                        <td><?php echo $row['username'] ?></td>
                        <td><?php echo $row['mcno'] ?></td>
                        <td><?php echo $row['department'] ?></td>
                        <td><?php echo $row['plant'] ?></td>
                        <td><?php echo $row['issue'] ?></td>
                        <td><?php echo $row['remark'] ?></td>
                        <td><?php echo "assigned to" ?></td>
                        <td><?php echo "approx time" ?></td>
                        <td><?php echo "unit " ?></td>
                        <td><?php echo "update" ?></td>
                        <td><button type="button" class="btn btn-sm rounded-pill btn-primary edit" id="<?php echo $row['srno']  ?>" >Edit</button>
                            <button type="button" class="btn btn-sm rounded-pill btn-danger delete" id="<?php echo $row['srno']  ?>">Delete</button>
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
<script>
  $('#cticket').addClass('active');

   // datatable to table
   $(document).ready(function () {
    $('#ctickettable').DataTable({
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
      "order": [[1, 'desc']],
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

//   $(document).ready(function(){
// 		var table = $('#ctickettable').DataTable({   
// 		    "processing": true,       
// 			 dom: 'Bfrtip',                      
// 			 ordering: false,                  
// 			 destroy: true,                  
            
// 		 	lengthMenu: [
//             	[ 10, 50, -1 ],
//             	[ '10 rows','50 rows','Show all' ]
//         	],
// 			 buttons: [
// 		 		'pageLength','copy', 'excel'
//         	]
//     	});
//  	});


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