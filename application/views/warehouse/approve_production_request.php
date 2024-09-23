<style>
.select2-container {
    z-index: 9999;
}

.select2-selection {
    padding-top: 4px !important;
    height: 38px !important;
    /* width: 367px !important; */
}
</style>
<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card ml-5">

                <div class="card-body table-responsive mt-2">
                    <?php if ($this->session->flashdata('SUCCESS') != '') { ?>
                    <?= $this->session->flashdata('SUCCESS'); ?>

                    <?php } ?>
                    <?php if ($this->session->flashdata('DUPLICATES') != '') { ?>
                    <?= $this->session->flashdata('DUPLICATES'); ?>

                    <?php } ?>
                    <?php if ($this->session->flashdata('DELETED') != '') { ?>
                    <?= $this->session->flashdata('DELETED'); ?>

                    <?php } ?>
                    <?php if ($this->session->flashdata('EDIT') != '') { ?>
                    <?= $this->session->flashdata('EDIT'); ?>

                    <?php } ?>
                    <?php if ($this->session->flashdata('ERROR') != '') { ?>
                    <?= $this->session->flashdata('ERROR'); ?>

                    <?php } ?>
                    <h5 class="mt-2">Production Plan: <b><?= $production_plan->Production_plan ?></b></h5>
                    <h5 class="mt-2">Production Description: <b><?= $production_plan->Fg_desc ?></b></h5>
                    <h5 class="mt-2">Production Plan Qty: <b><?= $production_plan->Production_plan_qty ?></b></h5>
                    <!-- <button type="button" class="btn btn-primary mb-2 mt-4" style data-bs-toggle="modal"
                        data-bs-target="#addModal1" style="font-weight: bold;" id="addBtn">
                        + Add Material
                    </button> -->
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No Request</th>
                                <th>Id Material</th>
                                <th>Material Part Name</th>
                                <th>QTY</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0;
							foreach ($production_request as $pr):
								$number++ ?>
                            <tr>
                                <td>
                                    <?= $number; ?>
                                </td>
                                <td><?php echo $pr['Id_request']; ?></td>
                                <td><?php echo $pr['Id_material']; ?></td>
                                <td><?php echo $pr['Material_desc']; ?></td>
                                <td><?php echo $pr['Qty']; ?></td>
                                <td style="text-align:center">
                                    <?php if($pr['status'] == 0) { ?>
                                    <button class="btn btn-warning" class="choose-sloc-box" data-bs-toggle="modal"
                                        data-bs-target="#chooseSlocAndBox<?= $pr['Id_request']; ?>">
                                        <i class="bx bx-pencil"></i></span>
                                    </button>
                                    <?php } else { ?>
                                    <button class="btn btn-primary" class="choose-sloc-box" data-bs-toggle="modal"
                                        data-bs-target="#detailSlocAndBox<?= $pr['Id_request']; ?>">
                                        <i class="bx bx-show"></i></span>
                                    </button>
                                    <?php }  ?>

                                </td>
                            </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="row mt-2" style="text-align: right; margin-right: 5px;">
                        <div class="col-md-10"></div>
                        <div class="col-md">
                            <?php if($production_plan->status == 'APPROVED') {?>
                            <button class="btn btn-primary" id="saveEdit"
                                onclick="saveApprove('<?= $production_plan->Production_plan ?>')" disabled>
                                Approve
                            </button>
                            <?php } else { ?>
                            <button class="btn btn-primary" id="saveEdit"
                                onclick="saveApprove('<?= $production_plan->Production_plan ?>')">
                                Approve
                            </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADD MODAL-->
<?php foreach ($production_request as $pr): ?>
<div class="modal fade" id="chooseSlocAndBox<?= $pr['Id_request']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose Sloc and Box</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row ps-2">
                    <div class="col-4">
                        <label for="sloc" class="form-label">Id Material</label>
                        <input type="text" class="form-control id_material" value="<?= $pr['Id_material']?>" readonly>
                    </div>
                    <div class="col-5">
                        <label for="sloc" class="form-label">Material Description</label>
                        <input type="text" class="form-control material_desc_<?= $pr['Id_material']?>"
                            value="<?= $pr['Material_desc']?>" readonly>
                        <input type="hidden" class="form-control Production_plan_detail_id_<?= $pr['Id_material']?>"
                            value="<?= $pr['Production_plan_detail_id']?>" readonly>
                        <input type="hidden" class="form-control id_request_<?= $pr['Id_material']?>"
                            value="<?= $pr['Id_request']?>" readonly>
                        <input type="hidden" class="form-control production_plan_<?= $pr['Id_material']?>"
                            value="<?= $pr['Production_plan']?>" readonly>
                    </div>
                    <div class="col-3">
                        <label for="sloc" class="form-label">Qty Need</label>
                        <input type="text" class="form-control material_need_<?= $pr['Id_material']?>"
                            value="<?= $pr['Qty']?>" readonly>
                    </div>
                </div>
                <hr>
                <button type="button" class="btn btn-success plus-row" data-id-material="<?= $pr['Id_material']?>">
                    <i class="bi bi-plus-circle"></i>
                </button>

                <!-- Container for dynamic setting rows -->
                <div class="setting-container">
                    <!-- Initial setting block can be empty or with default fields -->
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="closeModal()">Close</button>
                <button type="submit" class="btn btn-primary"
                    onclick="savePRDetail('<?= $pr['Id_material']?>')">Save</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


<!-- DETAIL-->
<?php foreach ($production_request as $pr): ?>
<div class="modal fade" id="detailSlocAndBox<?= $pr['Id_request']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Sloc and Box</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row ps-2">
                    <div class="col-4">
                        <label for="sloc" class="form-label">Id Material</label>
                        <input type="text" class="form-control id_material_detail" value="<?= $pr['Id_material']?>"
                            readonly>
                    </div>
                    <div class="col-5">
                        <label for="sloc" class="form-label">Material Description</label>
                        <input type="text" class="form-control" value="<?= $pr['Material_desc']?>" readonly>
                        <input type="hidden" class="form-control" value="<?= $pr['Production_plan_detail_id']?>"
                            readonly>
                        <input type="hidden" class="form-control" value="<?= $pr['Id_request']?>" readonly>
                    </div>
                    <div class="col-3">
                        <label for="sloc" class="form-label">Qty Need</label>
                        <input type="text" class="form-control" value="<?= $pr['Qty']?>" readonly>
                    </div>
                </div>
                <hr>
                <button type="button" class="btn btn-success view-details"
                    onclick="getdetailpr(<?=$pr['Production_plan_detail_id']?>)"
                    data-id-material="<?= $pr['Id_material']?>">
                    View Details
                </button>

                <!-- Container for dynamic setting rows -->
                <div class="detail-pr-container">
                    <!-- Initial setting block can be empty or with default fields -->
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<script src="<?= base_url('assets'); ?>/vendor/qr-code/qr-code.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
$(document).ready(function() {
    var rowIndex = 0;
    // Event listener for plus-row button click
    $(document).on('click', '.plus-row', function() {
        var id_material = $(this).data('id-material');
        rowIndex++;
        // Create a new setting block with a unique index
        var newSetting = `
            <div class="row ps-2 pt-3 setting" data-index="${rowIndex}_${id_material}">
                <div class="col-3" id="slocview_${rowIndex}_${id_material}">
                    <label for="sloc_${rowIndex}_${id_material}" class="form-label">Sloc</label>
                     <select class="form-control sloc-select sloc_${rowIndex}_${id_material}" name="sloc_name_${rowIndex}_${id_material}" id="sloc_${rowIndex}_${id_material}">
                    <option value="">Select Sloc</option>
                </select>
                </div>
                <div class="col-3" id="boxview_${rowIndex}_${id_material}">
                    <label for="no_box_${rowIndex}_${id_material}" class="form-label">No box</label>
                    <select class="form-control box-select box_${rowIndex}_${id_material}" name="no_box_${rowIndex}_${id_material}" id="box_${rowIndex}_${id_material}">
                        <option value="">Select Box</option>
                    </select>
                </div>
                <div class="col-2">
                    <label for="qty_on_sloc_${rowIndex}_${id_material}" class="form-label">Qty</label>
                    <input type="text" class="form-control qty_on_sloc_${rowIndex}_${id_material}" id="qty_on_sloc_${rowIndex}_${id_material}" name="qty_on_sloc_${rowIndex}_${id_material}[]" readonly>
                </div>
                <div class="col-2">
                    <label for="qty_need_${rowIndex}_${id_material}" class="form-label">Qty need</label>
                    <input type="number" class="form-control qty_need_${rowIndex}_${id_material}" id="qty_need_${rowIndex}_${id_material}" name="qty_need_${rowIndex}_${id_material}[]" min="0" onchange="cekQtyNeed(this, ${rowIndex}, '${id_material}')">
                </div>
                 <div class="col-2 p-4">
                    <button class="btn btn-success setPR_${rowIndex}_${id_material}" id="setPR_${rowIndex}_${id_material}" onclick="setPR(${rowIndex}, '${id_material}')">Set</button>
                    <button class="btn btn-success unsetPR_${rowIndex}_${id_material}" id="unsetPR_${rowIndex}_${id_material}" onclick="unsetPR(${rowIndex}, '${id_material}')" style="display:none">Unset</button>
                </div>
            </div>
        `;

        // Append the new setting block to the container
        $('.setting-container').append(newSetting);
        getSloc(id_material, rowIndex);
        $('.plus-row').prop('disabled', true);
    });
});

var materialSlocArray = []; // Array to store material settings

function getDetailRequest(id, production_plan) {
    $.ajax({
        url: '<?php echo base_url('warehouse/getApprovedDetail'); ?>',
        type: 'POST',
        data: {
            Production_plan: production_plan
        },
        success: function(res) {
            var data = JSON.parse(res);
            $('#detailModal' + id + ' .sloc-name').text(data.sloc_name ||
                'No Sloc Assigned'); // Tampilkan Sloc
            $('#detailModal' + id).modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load approval details'
            });
        }
    });
}

function getSloc(idMaterial, rowIndex) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_sloc_options'); ?>',
        method: 'POST',
        data: {
            id_material: idMaterial
        },
        success: function(response) {
            var data = JSON.parse(response);
            var $select = $(`.sloc_${rowIndex}_${idMaterial}`);
            $select.empty();
            $select.append('<option value="">-- Select Sloc --</option>');

            $.each(data, function(index, sloc) {
                $select.append('<option value="' + sloc.sloc_id + '">' + sloc.sloc_name +
                    '</option>');
            });

            $select.select2({
                placeholder: "-- Select Sloc --",
                width: "100%"
            });

            // Attach change event listener to sloc-select
            $select.on('change', function() {
                var selectedSlocId = $(this).val();
                $(`.sloc_${rowIndex}_${idMaterial}`).val(selectedSlocId);
                getIdBox(idMaterial, selectedSlocId, rowIndex);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function getIdBox(idMaterial, slocId, rowIndex) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_id_box_options'); ?>',
        method: 'POST',
        data: {
            id_material: idMaterial,
            sloc_id: slocId
        },
        success: function(response) {
            var data = JSON.parse(response);
            var $boxSelect = $(`.box_${rowIndex}_${idMaterial}`);
            $boxSelect.empty();
            $boxSelect.append('<option value="">-- Select Box --</option>');

            $.each(data, function(index, box) {
                $boxSelect.append('<option value="' + box.id_box + '" data-total-qty="' + box
                    .total_qty_real + '">' + box.no_box + '</option>');
            });

            $boxSelect.select2({
                placeholder: "-- Select Box --",
                width: "100%"
            });

            $boxSelect.on('change', function() {
                // Check for duplicate box selection
                var selectedOption = $(this).find('option:selected');
                var selectedBox = selectedOption.val();
                $(`.box_${rowIndex}_${idMaterial}`).val(selectedBox);
                if (materialSlocArray.some(item => item.box === selectedBox)) {
                    Swal.fire({
                        icon: 'error',
                        text: 'This box has already been selected. Please choose a different box.'
                    });
                    $(this).val(''); // Reset the dropdown
                    $(this).trigger('change'); // Trigger change event to reset qty_on_sloc
                    return;
                }
                var totalQty = selectedOption.data('total-qty');
                $(`.qty_on_sloc_${rowIndex}_${idMaterial}`).val(totalQty);
                $(`.qty_need_${rowIndex}_${idMaterial}`).attr('max', totalQty);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function cekQtyNeed(that, rowIndex, idMaterial) {
    var qty = $(that).val();
    var qtyOnSloc = $(`.qty_on_sloc_${rowIndex}_${idMaterial}`).val();

    if (parseFloat(qty) > parseFloat(qtyOnSloc)) {
        $(`.qty_need_${rowIndex}_${idMaterial}`).val(qtyOnSloc);
        Swal.fire({
            icon: 'error',
            text: 'Quantity cannot exceed the available quantity on Box'
        });
    } else {
        $(`.qty_need_${rowIndex}_${idMaterial}`).val(qty);
    }

}



function setPR(rowIndex, id_material) {
    var slocValue = $(`.sloc_${rowIndex}_${id_material}`).val();
    var boxValue = $(`.box_${rowIndex}_${id_material}`).val();
    var qtyNeedValue = $(`.qty_need_${rowIndex}_${id_material}`).val();
    var id_material = id_material;
    var Production_plan_detail_id = $(`.Production_plan_detail_id_${id_material}`).val();
    var material_desc = $(`.material_desc_${id_material}`).val();
    var production_plan = $(`.production_plan_${id_material}`).val();
    var id_request = $(`.id_request_${id_material}`).val();
    // Check if sloc, box, or qty_need values are empty
    if (!slocValue) {
        Swal.fire({
            icon: 'error',
            text: 'Please select a Sloc.'
        });
        return;
    }
    if (!boxValue) {
        Swal.fire({
            icon: 'error',
            text: 'Please select a Box.'
        });
        return;
    }
    if (!qtyNeedValue || parseFloat(qtyNeedValue) <= 0) {
        Swal.fire({
            icon: 'error',
            text: 'Please enter a valid Quantity Need.'
        });
        return;
    }

    // Push data to the array
    materialSlocArray.push({
        sloc: slocValue,
        box: boxValue,
        qty_need: parseFloat(qtyNeedValue),
        id_material: id_material,
        production_plan: production_plan,
        Production_plan_detail_id: Production_plan_detail_id,
        material_desc: material_desc,
        id_request: id_request,
    });
    console.log(materialSlocArray);

    // Disable the specific setPR button for this row
    $(`.setPR_${rowIndex}_${id_material}`).css('display', 'none');
    $(`.unsetPR_${rowIndex}_${id_material}`).css('display', 'block');
    $(`.qty_need_${rowIndex}_${id_material}`).prop('readonly', true);
    $(`.sloc_${rowIndex}_${id_material}`).prop('disabled', true);
    $(`.box_${rowIndex}_${id_material}`).prop('disabled', true);

    // Calculate the total qty_need in the array
    var totalQtyNeed = materialSlocArray.reduce((sum, item) => sum + item.qty_need, 0);
    var materialNeed = parseFloat($(`.material_need_${id_material}`).val());

    // Check if materialNeed is less than totalQtyNeed
    if (materialNeed > totalQtyNeed) {
        $('.plus-row').prop('disabled', false);
    }
    console.log(materialSlocArray);
}


function unsetPR(rowIndex, id_material) {
    var slocValue = $(`.sloc_${rowIndex}_${id_material}`).val();
    var boxValue = $(`.box_${rowIndex}_${id_material}`).val();
    var qtyNeedValue = parseFloat($(`.qty_need_${rowIndex}_${id_material}`).val());
    // Find and remove the specific item from the materialSlocArray
    materialSlocArray = materialSlocArray.filter(item =>
        item.sloc !== slocValue || item.box !== boxValue || item.qty_need !== qtyNeedValue
    );
    // Disable the specific setPR button for this row
    $(`.setPR_${rowIndex}_${id_material}`).css('display', 'block');
    $(`.unsetPR_${rowIndex}_${id_material}`).css('display', 'none');
    $(`.qty_need_${rowIndex}_${id_material}`).prop('readonly', false);
    $(`.sloc_${rowIndex}_${id_material}`).prop('disabled', false);
    $(`.box_${rowIndex}_${id_material}`).prop('disabled', false);

    console.log(materialSlocArray);
}

function savePRDetail(id_material) {
    if (materialSlocArray.length == 0) {
        Swal.fire({
            icon: 'error',
            text: 'Please Complete the proccess!'
        });
        return;
    }
    // Calculate the total qty_need in the array
    var totalQtyNeed = materialSlocArray.reduce((sum, item) => sum + item.qty_need, 0);
    var materialNeed = parseFloat($(`.material_need_${id_material}`).val());

    // Check if materialNeed is less than totalQtyNeed
    if (materialNeed != totalQtyNeed) {
        Swal.fire({
            icon: 'error',
            text: 'Production plan quantity need must be equal to the total request quantity on boxes.'
        });
    } else {
        $.ajax({
            url: '<?php echo base_url('warehouse/save_production_request_detail'); ?>',
            method: 'POST',
            data: {
                materialSlocArray: materialSlocArray
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status) {
                    Swal.fire({
                        icon: 'success',
                        text: 'Save production request detail is successfully.'
                    }).then(() => {
                        // Reload the page after success message
                        window.location.reload();
                    });
                    materialSlocArray = [];
                }

            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
}

function closeModal() {
    window.location.reload();
}



function getdetailpr(Production_plan_detail_id) {
    $('.view-details').prop('disabled', true);
    $.ajax({
        url: '<?php echo base_url('warehouse/get_detail_approve_pr'); ?>',
        method: 'POST',
        data: {
            Production_plan_detail_id: Production_plan_detail_id
        },
        success: function(response) {
            var data = JSON.parse(response);
            var rowIndex = $('.detail-pr-container').length;

            $.each(data, function(index, item) {
                var rowIndex = index; // or use a unique identifier if available

                // Create a new setting block for each item
                var newDetail = `
                    <div class="row ps-2 pt-3 setting" data-index="${rowIndex}">
                        <div class="col-3">
                            <label for="sloc_${rowIndex}" class="form-label">Sloc</label>
                             <input type="text" class="form-control" value="${item.sloc_name}" readonly>
                        </div>
                        <div class="col-3">
                            <label for="no_box_${rowIndex}" class="form-label">No box</label>
                            <input type="text" class="form-control" value="${item.box_name}" readonly>
                        </div>
                        <div class="col-2">
                            <label for="qty_on_sloc_${rowIndex}" class="form-label">Qty</label>
                            <input type="text" class="form-control" id="qty_on_sloc_${rowIndex}" name="qty_on_sloc_${rowIndex}[]" value="${item.qty_on_box}" readonly>
                        </div>
                        <div class="col-2">
                            <label for="" class="form-label">Qty need</label>
                            <input type="number" class="form-control" id="qty_need_${rowIndex}" name="qty_need_${rowIndex}[]" min="0" value="${item.Qty}" readonly>
                        </div>
                    </div>
                `;

                // Append the new setting block to the container
                $('.detail-pr-container').append(newDetail);
            });

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function saveApprove(production_plan) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_count_status_pr'); ?>',
        method: 'POST',
        data: {
            production_plan: production_plan
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status) {
                approveRequest(production_plan);
            } else {
                Swal.fire({
                    icon: 'error',
                    text: 'Please Complete the sloc and box for All Materials!'
                });
            }

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function approveRequest(production_plan) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to approve this production request?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Approve"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/approveProductionRequest'); ?>',
                type: 'POST',
                data: {
                    production_plan: production_plan,
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        window.open(
                            '<?php echo base_url() . $this->router->fetch_class(); ?>/print_request/' +
                            production_plan, '_blank');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your request.'
                    });
                }
            });
        }
    });
}
</script>