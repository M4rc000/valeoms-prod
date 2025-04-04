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
                    <h4 class="mt-2">Box Number : <b>
                            <?= $no_box ?>
                        </b></h4>
                    <button type="button" class="btn btn-primary mb-2 mt-4" style data-bs-toggle="modal"
                        data-bs-target="#addModal1" style="font-weight: bold;" id="addBtn">
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
							foreach ($detail_box as $material):
								$number++ ?>
                            <tr>

                                <td>
                                    <?= $number; ?>
                                </td>
                                <td>
                                    <?php echo $material['id_material']; ?>
                                </td>
                                <td>
                                    <?php echo $material['material_desc']; ?>
                                </td>
                                <td>
                                    <?php echo $material['qty']; ?>
                                </td>
                                <td>
                                    <?php echo $material['uom']; ?>
                                </td>
                                <td>
                                    <!-- <button class="btn btn-success" data-bs-toggle="modal"
										data-bs-target="#editModal<?= $material['id_box_detail']; ?>"
										onclick="getDataForEdit(<?= $material['id_box_detail']; ?>)">
									<i class="bx bxs-edit" style="color: white;"></i>
								</button> -->
                                    <button class="btn btn-danger ms-1" data-bs-toggle="modal"
                                        onclick="deleteItem(<?= $material['id_box_detail']; ?>)">
                                        <i class="bx bxs-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="row mb-5 mt-2">
                        <label class="col-sm-2 col-form-label">
                            <b>Total weight (kg)</b>
                        </label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="total_weight" onblur="getSloc()"
                                value="<?= $box->weight ?>">
                            <input type="hidden" class="form-control" id="sloc_select_before" value="<?= $box->sloc ?>">
                        </div>
                        <label class="col-sm-2 col-form-label">
                            <b>Select SLoc</b>
                        </label>
                        <div class="col-sm-4">
                            <select id="sloc_select" class="form-select" aria-label="Default select example">
                                <option value="<?= $box->sloc ?>" disabled selected style="color: GREY;">
                                    <?= $box->sloc_name ? $box->sloc_name : 'Please select total weight first' ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-10"></div>
                        <div class="col-md">
                            <form action="<?= base_url('warehouse/clearData') ?>" method="post">
                                <!-- Kosong -->
                            </form>
                        </div>
                    </div>
                    <div class="row mt-2" style="text-align: right; margin-right: 5px;">
                        <div class="col-md-10"></div>
                        <div class="col-md">
                            <button class="btn btn-primary" id="saveEdit" onclick="saveChanges(<?= $id_box ?>)">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= base_url('assets'); ?>/vendor/qr-code/qr-code.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

function closeModal() {
    $('#reference_number').val("");
    $('#material').val("");
    $('#qty').val("");
    $('#material_edit').val('');
    $('#uom_edit').val('');
    $('#uom').val("");
}

function refreshAll() {
    $.ajax({
        url: '<?php echo base_url('warehouse/delete_receiving_temp'); ?>',
        type: 'POST',
        data: {
            id: 'delete',
        },
        success: function(res) {
            var data = JSON.parse(res);
            if (data.status) {
                window.location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
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

$(document).on('change', '#reference_number', function() {
    $('#material_desc').val("");
    $('#uom').val("");
    $('#material_edit').val('');
    $('#uom_edit').val('');
    var refnumber = $('#reference_number').val();
    var refnumber2 = $('#reference_number_edit').val();

    $.ajax({
        url: '<?php echo base_url('warehouse/get_material_data'); ?>',
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
            if (data.sloc.length === 0 && data.status === 'success') {
                slocSelect.find('option:not(:selected)').remove();
                if (slocSelect.find('option:selected').length === 0) {
                    slocSelect.append(
                        '<option value="">No available Slocs for the specified total weight</option>');
                }
            } else if (data.status == 'success') {
                var selectedValues = [];
                slocSelect.find('option:selected').each(function() {
                    selectedValues.push($(this).val());
                });

                slocSelect.empty();

                $.each(data.sloc, function(index, sloc) {
                    slocSelect.append('<option value="' + sloc.Id_storage + '">' + sloc.SLoc +
                        '</option>');
                });

                $.each(selectedValues, function(index, value) {
                    slocSelect.find('option[value="' + value + '"]').prop('selected', true);
                });
            } else if (data.status == 'empty') {
                slocSelect.find('option:not(:selected)').remove();
                if (slocSelect.find('option:selected').length === 0) {
                    slocSelect.append('<option value="">Please select total weight first</option > ');
                }
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
</script>

<!-- ADD MODAL-->
<div class="modal fade" id="addModal1" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?= form_open_multipart('Warehouse/AddItemBox'); ?>
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
                            <option value="<?= $mtr['Id_material']; ?>">
                                <?= $mtr['Id_material']; ?>
                            </option>
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

<?php foreach ($detail_box as $material): ?>
<!-- EDIT MODAL -->
<div class="modal fade" id="editModal1" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editForm" action="<?= base_url('warehouse/edit_box'); ?>" method="post"
                onsubmit="submitEditForm();">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Box</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_box" name="id_box">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="col-form-label"><b>Total weight (kg)</b></label>
                            <input type="text" class="form-control" id="weight-edit" name="weight-edit"
                                onblur="getSlocEdit()" required>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <b>Sloc</b>
                            <select id="sloc_select_edit" class="form-select" name="sloc_edit"
                                aria-label="Default select example" required>
                                <option value="" disabled selected style="color: GREY;">Please select total weight first
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>Details</h5>
                            <table class="table table-bordered" id="detailsTable">
                                <thead>
                                    <tr>
                                        <th>Part Number</th>
                                        <th>Part Name</th>
                                        <th>QTY</th>
                                        <th>UOM</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="detailsBody">
                                    <!-- Details will be appended here by JavaScript -->
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success" onclick="addNewItemRow()">Add Item</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="closeModal()">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php endforeach; ?>


<script>
function closeModal() {
    $('#id_box').val("");
    $('#weight').val("");
    $('#sloc').val("");
}

function editBox(id_box, weight, sloc) {
    $('#id_box').val(id_box);
    $('#weight').val(weight);
    $('#sloc').val(sloc);
}
</script>

<!-- DELETE CONFIRM MODAL-->
<?php foreach ($users as $usr): ?>
<?= form_open_multipart('admin/deleteDataReceiving'); ?>
<div class="modal fade" id="deleteModal<?= $usr['id']; ?>" tabindex=" -1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
            </div>
            <div class="modal-body">
                <input type="text" name="id" id="id" value="<?= $usr['id']; ?>" style="display: none;">
                <p><b>Username</b> :
                    <?= $usr['username']; ?>
                </p>
                <p><b>Name</b> :
                    <?= $usr['name']; ?>
                </p>
                <p><b>Role</b> :
                    <?php
						if ($usr['role_id'] == 1) {
							echo 'Administrator';
						} elseif ($usr['role_id'] == 2) {
							echo 'Warehouse';
						} else {
							echo 'Production';
						}
						?>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
            </div>
        </div>
    </div>
</div>
</form>
<?php endforeach; ?>
</section>
<script>
function saveChanges(id_box) {
    var total_weight = $('#total_weight').val();
    var sloc = $('#sloc_select').val();
    sloc = sloc ? sloc : $('#sloc_select_before').val();

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
                console.log('Changes saved successfully');
                Swal.fire({
                    title: "Success!",
                    text: "Changes saved successfully.",
                    icon: "success"
                }).then(function() {
                    window.location.href = "<?= base_url('admin/manage_box'); ?>";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Something wrong!',
                });

            }
        },
        error: function(xhr, status, error) {
            console.error('Error saving changes:', error);
        }
    });
}
</script>