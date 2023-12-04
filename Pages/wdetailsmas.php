<?php
include('../includes/dbcon.php');
include('../includes/header.php');  

?>
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h4 class="pt-2 mb-0">Name Of Work detail</h4>
        </div>
        <div class="col-auto">
            <button class="btn rounded-pill common-btn add-new"   data-bs-toggle="modal" data-bs-target="#addModal">+ Add New</button>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Contractor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" id="addForm" action="wdetailsmas_db.php" method="post">
                    <label for="name">Work Detail</label>
                    <input type="text" name="wdetail" id="wdetail" placeholder="Enter Work detail" class="form-control" required>
                </form>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn common-btn btn-sm" form="addForm">Submit</button>
                </div>
            </div>        
        </div>
    </div>
 
    <div class="divCss">
        <table class="table table-bordered text-center mb-0">
            <thead>
                <tr>
                    <th class="bg-light">Sr</th>
                    <th class="bg-light">Work Type</th>
                    <th class="bg-light">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sr=1;
                $sql="SELECT sr, work_type from workdetail_master where isdelete='0' ";
                $run=sqlsrv_query($conn,$sql);                                
                while($row = sqlsrv_fetch_array($run, SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                        <th scope="row">
                            <?php echo $sr ?>
                        </th>
                        <td>
                            <?php echo $row['work_type'] ?>
                        </td>
                        <td class="tdCss">
                            <button class="btn btn-primary btn-sm edit" id=<?php echo $row['sr'] ?>
                            data-name="<?php echo $row['work_type'] ?>" >Edit</button>
                            <a href="wdetailsmas_db.php?deleteid=<?php echo $row['sr'] ?>"
                                onclick="return confirm('Are you sure?')" name="delete"
                                class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php 
            $sr++;
            } ?>
            </tbody>
        </table>
    </div>
  
 
    <!------------------ edit modal ------------------->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Contractor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="modal-body" id="editForm" action="wdetailsmas_db.php" method="post">
                    <label for="name">Name</label>
                    <input type="text" id="editName" name="editName" class="form-control" required>
                    <input type="hidden" id="editId" name="editId">
                </form>
                <div class="modal-footer">
                    <button type="submit" name="update" class="btn common-btn btn-sm" form="editForm">Submit</button>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // $('#wdetailsmas').addClass('active');
    $('#wdetailsmas').addClass('active');

    //add modal
    $(document).on('click', '.edit', function () {
        var editid = $(this).attr('id');
        var wtype = $(this).data('name');
        $('#editId').val(editid);
        $('#editName').val(wtype);
        $('#editModal').modal('show');
    });
</script>
<?php

include('../includes/footer.php');
?>