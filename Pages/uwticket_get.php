<?php
if(isset($_POST['sta'])){

    if($_POST['sta']=='closed'){
        ?>
        <label style="width: 25%;" class="form-label m-1" for="cdate">Close Date
            <input class="form-control" type="date" name="cdate" id="cdate" >
            <input type="text" name="closed" value="closed">
        </label>

        <label style="width: 25%;"  for="resolved_time">Resolved Time
            <div class="input-group">
                <input type="number" name="resolved_time" id="resolved_time" class="form-control ms-1 mt-1 " required>
                <select name="unit" id="unit" class="form-control me-1 mt-1">
                    <option value="hours">Hours</option>
                    <option value="days">Days</option>
                    <option value="months">Months</option>
                </select>
             </div>
        </label>
        
        <label style="width: 25%;" for="partschange">Parts Change
            <div class="input-group">
                <select name="partschange" id="partschange" class="form-control mt-1 custom-width">
                    <option value=""></option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <input class="form-control mt-1 custom-width hidden" type="number" id="numberOfParts" name="numberOfParts" placeholder="no. of parts">
            </div>
        </label>

        <label style="width: 25%;" class="form-label m-1" for="rem">Remark
            <input type="text" class="form-control" name="rem" id="rem">
        </label> 

        <script>
            // Get the select element and input element
            var partsChangeSelect = document.getElementById('partschange');
            var numberOfPartsInput = document.getElementById('numberOfParts');

            partsChangeSelect.addEventListener('change', function () {
                // Check if the selected value is 'yes'
                if (partsChangeSelect.value === 'yes') {
                
                    numberOfPartsInput.classList.remove('hidden');
                } else {
                
                    numberOfPartsInput.classList.add('hidden');
                }
            });  
        </script>
        <?php

            }
  
    if($_POST['sta']=='delay'){
        ?>
          <label style="width: 25%;" class="form-label m-1" for="approxdate">Approx close Date
          <input class="form-control" type="date" name="approxdate" id="approxdate" >
        </label>
                       
        <label style="width: 25%;" class="form-label m-1" for="rem">Remark
            <input type="text" class="form-control" name="rem" id="rem">
        </label> 
        
        <?php
        }
        if($_POST['sta']=='transfer'){
        
        ?>
            <label style="width: 25%;" class="form-label m-1" for="rem">Remark
                <input type="text" class="form-control" name="rem" id="rem">
            </label> 
        
        <?php
    }
}

if(isset($_POST['no'])){
    $no=$_POST['no'];

    ?>
 <table class="table table-bordered table-striped text-center table-hover" id="patchange">
    <thead class="bg-secondary text-light">
        <tr>
            <th>Sr</th>
            <th>Item Name</th>
            <th>Qty</th>            
            <th>Unit</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sr=1;
        for($i=0;$i<$no;$i++){
            ?>  
            <tr>
                <td><?php echo $sr ?></td>
                <?php
                $sql="select item FROM [RM_software].[dbo].[rm_item] ";
                ?>
                <td><input class="form-control" type="text" name="name[]" class="name" onFocus="Searchname(this)"></td>
                <td><input type="text" name="qty[]" id="qty"></td>
                <td><input type="text" class="punit" name="punit[]"  onFocus="Searchunit(this)"></td>
                <td>
                    <select class="form-select" name="status[]" >
                   <!-- <input type="text" id="status" name="status[]" value="replace">  -->  
                        <option selected default  value=""></option>                
                        <option  value="replace">Replace</option>
                        <option  value="new">New</option>
                        <option  value="repair">Repair</option>
                   </select>
                </td>
            </tr>
            <?php
            $sr++;
            }
    ?>
    </tbody>
</table>

    <?php
}
?>
<script>
     function Searchunit(txtBoxRef) {
      
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
                  data: {unit: request.term },
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
</script>