<?php
include('../includes/dbcon.php');
$pageTitle = "Assign Ticket";
include('../includes/header.php');

$sql = "SELECT * from [maintenance].[dbo].[ticket]";
$query = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

$sql1 = "SELECT * from [maintenance].[dbo].[assign]";
$query1 = sqlsrv_query($conn, $sql1);

?>

<title>
  <?php echo $pageTitle; ?>
</title>
<style>
  table.dataTable {
    border-collapse: collapse;
  }

  th {
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
</head>


<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-6">
      <h2 class="title">Assign Ticket</h2>
    </div>
    <div class="col-6">
      <button type="button" class="btn common-btn btn-info text-white fw-bold float-end" data-bs-toggle="modal"
        data-bs-target="#assignTicket">Assign Ticket</button>
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
          <th>Problem</th>
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
        <?php while($row1 = sqlsrv_fetch_array($query1, SQLSRV_FETCH_ASSOC)) {?>
        <tr>
          <td><?php echo $row1['srno']?></td>
          <td><?php echo $row['priority']?></td>
          <td><?php echo $row['proddtop']?></td>
          <td><?php echo $row['status']?></td>
          <td><?php echo $row['date']?></td>
          <td><?php echo $row['user']?></td>
          <td><?php echo $row['mcno']?></td>
          <td><?php echo $row['department']?></td>
          <td><?php echo $row['plant']?></td>
          <td><?php echo $row['problem']?></td>
          <td><?php echo $row['remark']?></td>
          <td><?php echo $row1['ticket_id']?></td>
          <td><?php echo $row1['prev_ticket_id']?></td>
          <td><?php echo $row1['assignby']?></td>
          <td><?php echo $row1['assignto']?></td>
          <td><?php echo $row1['assign_date']?></td>
          <td><?php echo $row1['approx_time']?></td>
          <td><?php echo $row1['unit']?></td>
          <td><?php echo $row1['updatefrom']?></td>
          <td><?php echo $row1['cat']?></td>
          <td><?php echo $row1['subcat']?></td>
          <td><?php echo $row1['role']?></td>
          <td>
            <div class="d-flex">
              <a type="button" class="btn btn-primary btn-sm me-1">Edit</a>
              <a type="button" class="btn btn-success btn-sm me-1">Save</a>
              <a type="button" class="btn btn-danger btn-sm">Cancel</a>
            </div>
          </td>
          <?php } ?>
        </tr>

      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="assignTicket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Assign Ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="background: #b9bbbe2b;">
          <form id="assignForm" method="post" action="../main_db.php">
            <div class="row">
              <div class="col-md-3">
                <label for="">Assign By</label>
                <input type="text" name="assignby" id="" value="<?php echo $_SESSION['uname'] ?>" readonly
                  class="form-control mt-1">
                <!-- <input type="text" name="" id="" value="<?php echo $row['srno'] ?>"> -->
              </div>
              <div class="col-md-3">
                <label for="">Assign To</label>
                <input type="text" name="assign_to" id="" value="" class="form-control mt-1">
              </div>
              <div class="col-md-3">
                <label for="">Approx. Time</label>
                <div class="input-group mt-1">
                  <input type="number" name="approx_time" id="approxTime" class="form-control" required>
                  <select name="unit" id="unit" class="form-control">
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <label for="">Priority</label>
                <select name="priority" id="priority" class="form-control mt-1">
                  <option value="low">Low</option>
                  <option value="medium">Medium</option>
                  <option value="high">High</option>
                </select>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-3">
                <label for="">Category</label>
                <select name="cat" id="cat" class="form-control mt-1">
                  <option value="mechanical">Mechanical</option>
                  <option value="electrical">Electrical</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Sub Category</label>
                <select name="subcat" id="subcat" class="form-control mt-1">
                  <option value="Crane">Crane</option>
                  <option value="Penal">Penal</option>
                  <option value="Febrication">Febrication</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Role</label>
                <select name="role" id="role" class="form-control mt-1">
                  <option value="inhouse">In House</option>
                  <option value="thirdparty">Third Party</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="">Update</label>
                <input type="text" name="updatefrom" id="" class="form-control mt-1">
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" name="assignsave" id="assignSave"
            form="assignForm">Submit</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // active link in sidebar
  $('#aticket').addClass('active');

  // datatable to table
  $(document).ready(function () {
    $('#assignTable').DataTable({
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
    // Assign Ticket - Save assigned ticket in database-table: assign
    $('#assignSave').click(function () {
      // Serialize the form data
      var formData = $('#assignForm').serialize();

      // Send the data using AJAX
      $.ajax({
        type: 'POST',
        url: $('#assignForm').attr('action'),
        data: formData,
        success: function (data) {
          alert(data); // Display the response from the server
          $('#assignTicket').modal('hide');
        },
        error: function () {
          alert('Error submitting form');
        }
      });
    });
    // $(document).on('click', '#assignSave', function () {
    //   console.log('hi click');
    //   $.ajax({
    //     url: 'main_db.php',
    //     type: 'POST',
    //     data: $('#assignForm').serialize(),
    //     success: function (data) {
    //       alert(data);
    //       $('#assignTicket').modal('hide');
    //     }
    //   });
    // });
  });


</script>
<?php

include('../includes/footer.php');
?>