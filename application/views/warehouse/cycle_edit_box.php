<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<style>
.select2-container {
    z-index: 9999;
}

.select2-selection {
    padding-top: 4px !important;
    height: 38px !important;
}
</style>

<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card ml-5">
                <div class="card-body table-responsive mt-2">
                    <?php if ($this->session->flashdata('SUCCESS')) { ?>
                    <div class="alert alert-success"><?= $this->session->flashdata('SUCCESS'); ?></div>
                    <?php } ?>

                    <?php if ($this->session->flashdata('ERROR')) { ?>
                    <div class="alert alert-danger"><?= $this->session->flashdata('ERROR'); ?></div>
                    <?php } ?>

                    <h4 class="mt-2">Box Number: <b><?= $no_box ?></b></h4>

                    <button type="button" class="btn btn-primary mb-2 mt-4" data-bs-toggle="modal"
                        data-bs-target="#addModal1">
                        Add Material
                    </button>

                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Material Part Number</th>
                                <th>Material Part Name</th>
                                <th>QTY</th>
                                <th>UOM</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0;
							foreach ($detail_box as $material) {
								$number++; ?>
                            <tr>
                                <td><?= $number; ?></td>
                                <td><?= $material['id_material']; ?></td>
                                <td><?= $material['material_desc']; ?></td>
                                <td><?= $material['qty']; ?></td>
                                <td><?= $material['uom']; ?></td>
                                <td>
                                    <button class="btn btn-success" data-bs-toggle="modal"
                                        data-bs-target="#editModal<?= $material['id_box_detail']; ?>">
                                        <i class="bx bxs-edit" style="color: white;"></i>
                                    </button>
                                    <button class="btn btn-danger ms-1"
                                        onclick="deleteItem(<?= $material['id_box_detail']; ?>)">
                                        <i class="bx bxs-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <div class="row mb-5 mt-2">
                        <label class="col-sm-2 col-form-label"><b>Total weight (kg)</b></label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="total_weight" onblur="getSloc()"
                                value="<?= $box->weight ?>">
                            <input type="hidden" id="sloc_select_before" value="<?= $box->sloc ?>">
                        </div>
                        <label class="col-sm-2 col-form-label"><b>Select SLoc</b></label>
                        <div class="col-sm-4">
                            <select id="sloc_select" class="form-select">
                                <option value="<?= $box->sloc ?>" disabled selected>
                                    <?= $box->sloc_name ? $box->sloc_name : 'Please select total weight first' ?>
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-10"></div>
                        <div class="col-md">
                            <button class="btn btn-primary" onclick="saveChanges(<?= $id_box ?>)">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADD MODAL-->
<div class="modal fade" id="addModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?= form_open_multipart('Warehouse/addMaterialCc'); ?>
            <div class="modal-header">
                <h5 class="modal-title">Add New Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row ps-2">
                    <div class="col-6">
                        <label for="reference_number" class="form-label">Material Part Number</label>
                        <select class="form-select" id="reference_number" name="reference_number">
                            <option value="">Select Material Part No</option>
                            <?php foreach ($materials as $mtr): ?>
                            <option value="<?= $mtr['Id_material']; ?>"><?= $mtr['Id_material']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="material_desc" class="form-label">Material</label>
                        <input type="text" class="form-control" id="material_desc" name="material_desc" readonly
                            required>
                        <input type="hidden" class="form-control" id="id_box" name="id_box" value="<?= $id_box ?>">
                        <input type="hidden" class="form-control" id="id_sloc" name="id_sloc" value="<?= $id_sloc ?>">
                    </div>
                    <div class="col-6 mb-3">
                        <label for="uom" class="form-label">UOM</label>
                        <input type="text" class="form-control" id="uom" name="uom" readonly required>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="qty" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="qty" name="qty" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="closeModal()">Close</button>
                <button type="submit" class="btn btn-primary" onclick="setReceivingDate()">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#addModal1').on('shown.bs.modal', function() {
        $('#reference_number').select2({
            dropdownParent: $('#addModal1'),
            width: '100%'
        });
    });
    $('#sloc_select').select2();
})
</script>

<!-- Edit Material Modals -->
<?php foreach ($detail_box as $material) { ?>
<div class="modal fade" id="editModal<?= $material['id_box_detail']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Form for editing material quantity -->
            <form id="editForm<?= $material['id_box_detail']; ?>" onsubmit="return false;">
                <input type="hidden" id="id_box_detail_<?= $material['id_box_detail']; ?>" name="id_box_detail"
                    value="<?= $material['id_box_detail']; ?>">
                <input type="hidden" id="id_box_<?= $material['id_box_detail']; ?>" name="id_box"
                    value="<?= $id_box ?>">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <label for="id_material_<?= $material['id_box_detail']; ?>" class="form-label">Material Part
                                Number</label>
                            <input type="text" class="form-control" id="id_material_<?= $material['id_box_detail']; ?>"
                                name="id_material" value="<?= $material['id_material']; ?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="material_desc_<?= $material['id_box_detail']; ?>" class="form-label">Material
                                Description</label>
                            <input type="text" class="form-control"
                                id="material_desc_<?= $material['id_box_detail']; ?>" name="material_desc"
                                value="<?= $material['material_desc']; ?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="qty_<?= $material['id_box_detail']; ?>" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="qty_<?= $material['id_box_detail']; ?>"
                                name="qty" value="<?= $material['qty']; ?>" required>
                        </div>
                    </div>
                    <div class="row" hidden>
                        <div class="col-4">
                            <label for="sloc_<?= $material['id_box_detail']; ?>" class="form-label">SLoc</label>
                            <input type="text" class="form-control" id="sloc_<?= $material['id_box_detail']; ?>"
                                name="sloc" value="<?= $box->sloc ?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="total_weight_<?= $material['id_box_detail']; ?>" class="form-label">Total
                                Weight</label>
                            <input type="text" class="form-control" id="total_weight_<?= $material['id_box_detail']; ?>"
                                name="total_weight" value="<?= $box->weight ?>">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"
                        onclick="submitEdit(<?= $material['id_box_detail']; ?>)">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<script>
function submitEdit(id_box_detail) {
    var formData = {
        id_box_detail: $('#id_box_detail_' + id_box_detail).val(),
        qty: $('#qty_' + id_box_detail).val(),
        sloc: $('#sloc_' + id_box_detail).val(),
        total_weight: $('#total_weight_' + id_box_detail).val(),
    };

    $.ajax({
        url: '<?= base_url('warehouse/editqty_cc'); ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status) {
                Swal.fire({
                    title: "Success!",
                    text: "Changes saved successfully.",
                    icon: "success"
                }).then(function() {
                    $('#editModal' + id_box_detail).modal('hide');
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred while saving your changes.'
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving your changes.'
            });
        }
    });
}
</script>


<script>
function submitEdit(id_box_detail) {
    var formData = {
        id_box_detail: $('#id_box_detail_' + id_box_detail).val(),
        qty: $('#qty_' + id_box_detail).val(),
        sloc: $('#sloc_' + id_box_detail).val(),
        total_weight: $('#total_weight_' + id_box_detail).val(),
    };

    $.ajax({
        url: '<?= base_url('warehouse/editqty_cc'); ?>',
        type: 'POST',
        data: formData,
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status) {
                Swal.fire({
                    title: "Success!",
                    text: "Changes saved successfully.",
                    icon: "success"
                }).then(function() {
                    $('#editModal' + id_box_detail).modal('hide');
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'An error occurred while saving your changes.'
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving your changes.'
            });
        }
    });
}
</script>

<!-- jQuery & AJAX Functions -->
<script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/sweetalert/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    $('#reference_number').select2({
        width: '100%'
    });
    $('#sloc_select').select2();
});

$(document).on('change', '#sloc_select', function() {
    var selectedSlocText = $('#sloc_select option:selected').text();
    var selectedSloc = $('#sloc_select').val();

    // Jika teks opsi mengandung 'Full', beri peringatan
    if (selectedSlocText.includes('(Full)')) {
        Swal.fire({
            icon: 'warning',
            title: 'SLoc Full',
            text: 'The selected SLoc is full. Please choose another location.'
        });

        // Reset pilihan SLoc
        $('#sloc_select').val('');
    }
});

$(document).on('change', '#reference_number', function() {
    $('#material_desc').val("");
    $('#uom').val("");
    $('#material_edit').val('');
    $('#uom_edit').val('');
    var refnumber = $('#reference_number').val();
    var refnumber2 = $('#reference_number_edit').val();

    $.ajax({
        url: '<?php echo base_url('warehouse/get_material_data'); ?> ',
        type: 'POST',
        data: {
            refnumber: refnumber,
            refnumber2: refnumber2
        },
        success: function(res) {
            var data = JSON.parse(res);
            if (data.status) {
                $('#material_desc').val(data.material);
                $('#uom').val(data.uom);
                $('#material_edit').val(data.material);
                $('#uom_edit').val(data.uom);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.msg
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
});

function getSloc() {
    var total_weight = $('#total_weight').val();
    var slocSelect = $('#sloc_select');

    $.ajax({
        url: '<?php echo base_url('warehouse/get_sloc'); ?>',
        type: 'POST',
        data: {
            total_weight: total_weight
        },
        success: function(res) {
            var data = JSON.parse(res);

            if (data.status === 'success') {
                slocSelect.empty(); // Mengosongkan select sebelum menambahkan opsi baru

                $.each(data.sloc, function(index, sloc) {
                    var optionText = sloc.SLoc;
                    var optionDisabled = '';

                    // Cek apakah SLoc penuh
                    if (sloc.is_full) {
                        optionText += " (Full)";
                        optionDisabled = 'disabled'; // Disable opsi yang penuh
                    }

                    // Tambahkan opsi ke dropdown
                    slocSelect.append(
                        `<option value="${sloc.Id_storage}" ${optionDisabled}>${optionText}</option>`
                    );
                });
            } else if (data.status === 'empty') {
                slocSelect.empty();
                slocSelect.append('<option value="">No available Slocs</option>');
            } else {
                slocSelect.empty();
                slocSelect.append(
                    '<option value="">No available Slocs for the specified total weight</option>');
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



function deleteItem(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/delete_material_box'); ?>',
                type: 'POST',
                data: {
                    id: id,
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Material has been deleted.",
                            icon: "success"
                        });
                        window.location.reload();
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

function deleteItem(id) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/delete_material_box'); ?>',
                type: 'POST',
                data: {
                    id: id,
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Material has been deleted.",
                            icon: "success"
                        });
                        window.location.reload();
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

// Save Changes
function saveChanges(id_box) {
    var total_weight = $('#total_weight').val();
    var sloc = $('#sloc_select').val() || $('#sloc_select_before').val();

    $.ajax({
        url: '<?= base_url('warehouse/updateTotalWeightAndSloc'); ?>',
        type: 'POST',
        data: {
            total_weight: total_weight,
            sloc: sloc,
            id_box: id_box
        },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status == 'success') {
                Swal.fire({
                    title: "Success!",
                    text: "Changes saved successfully.",
                    icon: "success"
                }).then(() => {
                    window.location.href = "<?= base_url('warehouse/cycle_box_view/'); ?>" + id_box;
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Something went wrong!",
                    icon: "error"
                });
            }
        }
    });
}
</script>