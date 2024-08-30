<?php
    function formatQuantity($qty) {
        // Convert to float and format with up to 3 decimal places
        $formattedQty = number_format((float)$qty, 3, '.', '');
        // Remove trailing zeros
        $formattedQty = rtrim(rtrim($formattedQty, '0'), '.');
        return $formattedQty;
    }
?>

<style>
    .edit-material-request:hover{
        cursor: pointer;
    }

    .hover:hover{
        cursor: pointer;
    }
</style>

<section>
    <div class="card">
        <div class="card-body">
            <!-- GET USER -->
            <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
            <div class="row mt-5 px-2">
                <div class="col-md mb-2">
                    <span>
                        <b>BILL OF MATERIAL</b>
                    </span>
                </div>
                <hr style="border: 1.5px solid black">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tbl-bom">
                        <thead style="font-size: 14px">
                            <tr>
                                <th scope="col" rowspan="2" class="text-center">#</th>
                                <th scope="col" rowspan="2" class="text-center">Production Plan</th>
                                <th scope="col" rowspan="2" class="text-center">Material Part No</th>
                                <th scope="col" rowspan="2" class="text-center">Material Part Name</th>
                                <th scope="col" rowspan="2" class="text-center">Material Need</th>
                                <th scope="col" rowspan="2" class="text-center">Qty</th>
                                <th scope="col" rowspan="2" class="text-center">Uom</th>
                                <th scope="col" rowspan="2" class="text-center">
                                    Status 
                                    <input type="checkbox" class="form-check-input" id="check-all" style="width: 18px; height: 18px">
                                </th>
                                <th scope="col" rowspan="2" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13.5px" id="material-table-body">
                            <?php $number = 0; foreach($production_plans as $pp): $number++?>
                            <tr data-MaterialID="<?=$pp['Id_material'];?>">
                                <td scope="row"><?=$number;?></td>
                                <td class="text-center"><?=$pp['Production_plan'];?></td>
                                <td><?=$pp['Id_material'];?></td>
                                <td><?=$pp['Material_desc'];?></td>
                                <td class="text-center"><?=$pp['Material_need'];?></td>
                                <td class="text-center">
                                    <?= ($pp['Uom'] == 'PC' || $pp['Uom'] == 'pc') ? rtrim(rtrim(number_format($pp['Qty'], 3, '.', ''), '0'), '.') : rtrim(rtrim(number_format($pp['Qty'], 3, '.', ''), '0'), '.'); ?>
                                </td>
                                <td class="text-center"><?=$pp['Uom'];?></td>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input checkbox-material"
                                        data-id="<?=$pp['id'];?>" style="width: 18px; height: 18px"
                                        <?=$pp['status'] == 1 ? 'checked':''; ?>>
                                </td>
                                <td class="text-center">
                                    <span class="edit-material-request" data-bs-toggle="modal"
                                        data-bs-target="#editMaterialRequest<?=$pp['id'];?>">
                                        <span class="badge bg-warning"><i class="bx bx-pencil"></i></span>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row mt-3 px-5 mb-2">
            <div class="col-md-12 text-start">
                <span><strong>Status: </strong> <span id="status-count">0/0</span></span>
            </div>
            <div class="col-12 text-end">
                <span class="w-20 hover">
                    <button class="btn btn-success" id="button-save">Save</button>
                </span>
            </div>
        </div>
    </div>
</section>

<!-- MATERIAL REQUEST MODAL -->
<?php foreach($production_plans as $pp):?>
<div class="modal fade" id="editMaterialRequest<?=$pp['id'];?>" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
        <?= form_open_multipart('production/editProductionPlan'); ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Material Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- GET USER -->
                <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
                <!-- GET PRODUC PLAN ID -->
                <input type="text" class="form-control" id="prod_plan_id" name="prod_plan_id" value="<?=$pp['id'];?>" hidden>
                
                <div class="row mb-3">
                    <div class="col-12 col-md-4">
                        <label for="production_plan" class="form-label"><b>Production Plan</b></label>
                        <input type="text" class="form-control" id="production_plan" name="production_plan" value="<?=$pp['Production_plan'];?>" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="materials_id" class="form-label"><b>Material Part No</b></label>
                        <input type="text" class="form-control" id="materials_id" name="materials_id" value="<?=$pp['Id_material'];?>" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="material_desc" class="form-label"><b>Material Part Name</b></label>
                        <input type="text" class="form-control" id="material_desc" name="material_desc" value="<?=$pp['Material_desc'];?>" readonly>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <label for="material_need" class="form-label"><b>Material Need</b></label>
                        <input type="text" class="form-control" id="material_need" name="material_need" value="<?=$pp['Material_need'];?>" readonly>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="qty" class="form-label"><b>Qty</b></label>
                        <input type="text" class="form-control" id="qty" name="qty" value="<?= formatQuantity($pp['Qty']); ?>">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="stock_on_hand" class="form-label"><b class="px-2">Stock on hand</b> 
                            <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Accumulation Quantity on storage"></i>
                        </label>
                        <input type="text" class="form-control" id="stock_on_hand" name="stock_on_hand" readonly>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="uom" class="form-label"><b>Uom</b></label>
                        <input type="text" class="form-control" id="uom" name="uom" value="<?=$pp['Uom'];?>" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btn-close-modal" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="updateProductionPlan">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="<?=base_url('assets');?>/vendor/sweet-alert/sweet-alert.js"></script>
<script>
    $(document).ready(function() {
        function updateButtonState() {
            var totalCheckboxes = $('.checkbox-material').length;
            var checkedCheckboxes = $('.checkbox-material:checked').length;
            
            $('#status-count').text(checkedCheckboxes + '/' + totalCheckboxes);
            
            if (totalCheckboxes === checkedCheckboxes) {
                $('#button-save').prop('disabled', false);
            } else {
                $('#button-save').prop('disabled', true);
            }
        }

        $('#check-all').change(function() {
            var isChecked = $(this).is(':checked');

            $('.checkbox-material').prop('checked', isChecked).trigger('change');
        });

        $('.edit-material-request').on('click', function(event) {
            event.preventDefault();
            var row = $(this).closest('tr');
            var materialId = row.find('td:eq(2)').text(); 

            $.ajax({
                url: '<?=base_url('production/getSlocStorage');?>',
                method: 'POST',
                data: { materialId },
                success: function(res) {
                    var result = JSON.parse(res);
                    // console.log(res);
                    var stock_on_hand = 0;
                    
                    for (var i = 0; i < result.length; i++) {
                        stock_on_hand += parseInt(result[i].total_qty, 10);
                    }
                    
                    $('#stock_on_hand').val(stock_on_hand);
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch stock on hand:', error);
                }
            });
        });

        $('.checkbox-material').change(function() {
            var isChecked = $(this).is(':checked');
            var productionPlanId = $(this).data('id');
            var user = $('#user').val();

            var data = {
                id: productionPlanId,
                status: isChecked ? 1 : 0
            };

            $.ajax({
                url: '<?=base_url('production/update_status_edit_production_plan');?>',
                type: 'POST',
                data: { data, user },
                dataType: 'json',
                success: function(res) {
                    if (res === 0) {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to save status",
                            icon: "error"
                        });
                    }
                    updateButtonState();
                },
                error: function(xhr, status, error) {
                    console.error('Error updating status:', error);
                }
            });
        });

        updateButtonState();

        function showConfirmation(event, action) {
            event.preventDefault(); // Prevent the default action

            Swal.fire({
                title: 'Are you sure?',
                text: "You have unsaved changes, do you want to leave this page?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, leave',
                cancelButtonText: 'No, stay',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the action
                    if (typeof action === 'function') {
                        action();
                    } else if (typeof action === 'string') {
                        window.location.href = action; // Redirect to the specified URL
                    } else {
                        event.target.submit(); // For form submission
                    }
                }
            });
        }

        // Handle link clicks
        $('a').on('click', function(event) {
            var url = $(this).attr('href');
            showConfirmation(event, url);
        });

        // Handle specific button clicks for redirection
        $('#button-save').on('click', function() {
            // Remove the 'beforeunload' event listener
            window.removeEventListener('beforeunload', handleBeforeUnload);

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save this record?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?=base_url('production/')?>'; 
                } else {
                    // Re-add the 'beforeunload' event listener if the user cancels
                    window.addEventListener('beforeunload', handleBeforeUnload);
                }
            });
        });


        // HANDLE BUTTON PREVIOUS AND FORWARD PRESS
        function handleBeforeUnload(event) {
            event.preventDefault();
            event.returnValue = ''; // For modern browsers
            return ''; // For older browsers
        }

        window.addEventListener('beforeunload', handleBeforeUnload);
    });
</script>


<!-- SWEET ALERT -->
<?php if ($this->session->flashdata('SUCCESS_editProductionPlan')): ?>
    <script>
        Swal.fire({
            title: "Success",
            text: "<?= $this->session->flashdata('SUCCESS_editProductionPlan'); ?>",
            icon: "success"
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('FAILED_editProductionPlan')): ?>
    <script>
        Swal.fire({
            title: "Error",
            text: "<?= $this->session->flashdata('FAILED_editProductionPlan'); ?>",
            icon: "error"
        });
    </script>
<?php endif; ?>