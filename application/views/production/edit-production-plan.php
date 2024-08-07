<section>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <div class="row mt-5 px-2">
                    <div class="col-md mb-2">
                        <span>
                            <b>BILL OF MATERIAL</b>
                        </span>
                    </div>
                    <hr style="border: 1.5px solid black">
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
                                <td class="text-center"><?=(floor($pp['Qty']) == $pp['Qty']) ? $pp['Qty'] : number_format($pp['Qty'], 2, '.', '');?></td>
                                <td class="text-center"><?=$pp['Uom'];?></td>
                                <td class="text-center">
                                    <a href="#" class="edit-material-request" data-bs-toggle="modal" data-bs-target="#editMaterialRequest<?=$pp['id'];?>">
                                        <span class="badge bg-warning"><i class="bx bx-pencil"></i></span>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-end">
                    <a href="<?=base_url('production/');?>" class="btn btn-success w-20">
                        Save
                    </a>
                </div>
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
                        <input type="text" class="form-control" id="qty" name="qty" value="<?=$pp['Qty'];?>">
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
        $('.edit-material-request').on('click', function(event) {
            event.preventDefault();
            var row = $(this).closest('tr');
            var materialId = row.find('td:eq(2)').text(); 
            
            $.ajax({
                url: '<?=base_url('production/getSlocStorage');?>', 
                method: 'POST',
                data: {
                    materialId
                },
                success: function(res) {
                    var result = JSON.parse(res);

                    var stock_on_hand = 0;
                    for(var i = 0; i < result.length; i++){
                        stock_on_hand+= parseInt(result[i].total_qty);
                    }

                    $('#stock_on_hand').val(stock_on_hand);
                },
                error: function(xhr, status, error) {
                    // alert('Failed to update production plan');
                }
            });
        });
    });
</script>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            title: "Success",
            text: "<?= $this->session->flashdata('success'); ?>",
            icon: "success"
        });
    </script>
<?php endif; ?>