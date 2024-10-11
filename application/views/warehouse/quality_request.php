<!-- application/views/warehouse/quality_request.php -->
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
                    <table class="table datatable table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Material</th>
                                <th>Material Description</th>
                                <th>Material Need</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($quality_request) && !empty($quality_request)): ?>
                            <?php $number = 1; ?>
                            <?php foreach ($quality_request as $request): ?>
                            <tr>
                                <td><?= $number++; ?></td>
                                <td><?= $request['Id_material']; ?></td>
                                <td><?= $request['Material_desc']; ?></td>
                                <td><?= $request['Material_need']; ?></td>
                                <td>
                                    <?php if ($request['status'] == 0): ?>
                                    <button class="btn btn-success"
                                         class="choose-sloc-box" data-bs-toggle="modal" data-bs-target="#chooseSlocAndBox<?= $request['Id_request']; ?>">
                                        <i class="bx bxs-edit" style="color: white;"> </i>Approve
                                    </button>
                                    <button class="btn btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#rejectModal<?= $request['Id_request']; ?>">
                                        <i class="bi bi-dash-circle" style="color: white;"> </i> Reject
                                    </button>
                                    <?php elseif ($request['status'] == 1): ?>
                                    <button type="button" class="btn btn-secondary"  style="background-color:#3fb03f" disabled>Approved</button>
                                    <button class="btn btn-primary" class="choose-sloc-box" data-bs-toggle="modal" data-bs-target="#detailSlocAndBox<?= $request['Id_request']; ?>">
                                   <i class="bx bx-show"></i></span> Detail
                                    </button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="printReq('<?= $request['Id_request']; ?>')"><i
                                            class="bi bi-printer"></i> Print</button>
                                    <?php else: ?>
                                    <button type="button" class="btn btn-danger" disabled>Rejected</button>
                                    <button class="btn btn-primary" class="choose-sloc-box" data-bs-toggle="modal" data-bs-target="#detailRejectSlocAndBox<?= $request['Id_request']; ?>">
                                   <i class="bx bx-show"></i></span> Detail
                                    </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5">No data available</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DETAIL-->
<?php foreach ($quality_request as $pr): ?>
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
                       <input type="text" class="form-control id_material_detail" value="<?= $pr['Id_material']?>" readonly>
                    </div>
                    <div class="col-5">
                        <label for="sloc" class="form-label">Material Description</label>
                        <input type="text" class="form-control"  value="<?= $pr['Material_desc']?>" readonly>
                        <input type="hidden" class="form-control"  value="<?= $pr['Id_request']?>" readonly>
                    </div>
                    <div class="col-3">
                        <label for="sloc" class="form-label">Qty Need</label>
                       <input type="text" class="form-control"  value="<?= $pr['Material_need']?>" readonly>
                    </div>
                </div>
                <hr>
                <button type="button" class="btn btn-success view-details" onclick="getdetailpr('<?=$pr['Id_request']?>')" data-id-material="<?= $pr['Id_material']?>">
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

<!-- REJECT MODAL DETAIL-->
<?php foreach ($quality_request as $request): ?>
<div class="modal fade" id="detailRejectSlocAndBox<?= $request['Id_request']; ?>" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Reject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                    <label for="productId" class="col-sm-4 col-form-label"><b>Material ID</b></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= $request['Id_material']; ?>"
                            name="productId" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="productDescription" class="col-sm-4 col-form-label"><b>Material  Description</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="<?= $request['Material_desc']; ?>"
                           name="productDescription"
                            value="${productDescription}" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="qty" class="col-sm-4 col-form-label"><b>Material Need</b></label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" value="<?= $request['Material_need']; ?>" id="qty"
                            name="qty" min="1" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="reject" class="col-sm-4 col-form-label"><b>Reject Description</b></label>
                    <div class="col-lg-8 col-sm-8">
                        <textarea type="text" style ="border-radius:10px; border: 1px solid #dc3545"
                       min="1" readonly><?= $request['reject_description']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"> Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<!-- REJECT MODAL-->
<?php foreach ($quality_request as $request): ?>
<div class="modal fade" id="rejectModal<?= $request['Id_request']; ?>" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Quality Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                    <label for="productId" class="col-sm-4 col-form-label"><b>Material ID</b></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= $request['Id_material']; ?>"
                            name="productId" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="productDescription" class="col-sm-4 col-form-label"><b>Material  Description</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="<?= $request['Material_desc']; ?>"
                           name="productDescription"
                            value="${productDescription}" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="qty" class="col-sm-4 col-form-label"><b>Material Need</b></label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" value="<?= $request['Material_need']; ?>" id="qty"
                            name="qty" min="1" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="reject" class="col-sm-4 col-form-label"><b>Reject Description</b></label>
                    <div class="col-lg-6 col-sm-6">
                        <textarea type="text" class="form-control keterangan-reject_<?= $request['Id_request']; ?>" 
                            name="keterangan-reject_" min="1" required ></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Reject"
                onclick="rejectRequest('<?= $request['Id_request'] ?>')"> Reject</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>


<!-- ADD MODAL-->
<?php foreach ($quality_request as $pr): ?>
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
                       <input type="text" class="form-control id_material_<?= $pr['Id_request']?>" value="<?= $pr['Id_material']?>" readonly>
                    </div>
                    <div class="col-5">
                        <label for="sloc" class="form-label">Material Description</label>
                        <input type="text" class="form-control material_desc_<?= $pr['Id_request']?>"   value="<?= $pr['Material_desc']?>" readonly>
                        <input type="hidden" class="form-control id_request_<?= $pr['Id_request']?>"  value="<?= $pr['Id_request']?>" readonly>
                    </div>
                    <div class="col-3">
                        <label for="sloc" class="form-label">Qty Need</label>
                       <input type="text" class="form-control material_need_<?= $pr['Id_request']?>" value="<?= $pr['Material_need']?>" readonly>
                    </div>
                </div>
                <hr>
                <button type="button" class="btn btn-success plus-row" data-id-request="<?= $pr['Id_request']?>"  data-id-material="<?= $pr['Id_material']?>">
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
                <button type="submit" class="btn btn-primary" onclick="savePRDetail('<?= $pr['Id_material']?>', '<?= $pr['Id_request']?>')">Save</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>

$(document).ready(function() {
    $(document).ready(function() {
        $('.modal').modal({
            backdrop: 'static', // Prevent closing on click outside
            keyboard: false // Prevent closing on escape key
        });

        // If you need to add additional logic to ensure it stays open
        $('.modal').on('hide.bs.modal', function (e) {
            e.preventDefault();
            // Optionally, you can add logic to handle what happens when trying to close
        });
    });

    function closeModal() {
        // Custom logic for closing the modal if needed
        $('.modal').modal('hide');
    }
    var rowIndex = 0;
    // Event listener for plus-row button click
    $(document).on('click', '.plus-row', function() {
        var id_request = $(this).data('id-request'); 
        var Id_material = $(this).data('id-material'); 
        console.log(Id_material);
        rowIndex++;
        // Create a new setting block with a unique index
        var newSetting = `
            <div class="row ps-2 pt-3 setting" data-index="${rowIndex}_${id_request}">
                <div class="col-3" id="slocview_${rowIndex}_${id_request}">
                    <label for="sloc_${rowIndex}_${id_request}" class="form-label">Sloc</label>
                     <select class="form-control sloc-select sloc_${rowIndex}_${id_request}" name="sloc_name_${rowIndex}_${id_request}" id="sloc_${rowIndex}_${id_request}">
                    <option value="">Select Sloc</option>
                </select>
                </div>
                <div class="col-3" id="boxview_${rowIndex}_${id_request}">
                    <label for="no_box_${rowIndex}_${id_request}" class="form-label">No box</label>
                    <select class="form-control box-select box_${rowIndex}_${id_request}" name="no_box_${rowIndex}_${id_request}" id="box_${rowIndex}_${id_request}">
                        <option value="">Select Box</option>
                    </select>
                </div>
                <div class="col-2">
                    <label for="qty_on_sloc_${rowIndex}_${id_request}" class="form-label">Qty</label>
                    <input type="text" class="form-control qty_on_sloc_${rowIndex}_${id_request}" id="qty_on_sloc_${rowIndex}_${id_request}" name="qty_on_sloc_${rowIndex}_${id_request}[]" readonly>
                </div>
                <div class="col-2">
                    <label for="qty_need_${rowIndex}_${id_request}" class="form-label">Qty need</label>
                    <input type="number" class="form-control qty_need_${rowIndex}_${id_request}" id="qty_need_${rowIndex}_${id_request}" name="qty_need_${rowIndex}_${id_request}[]" min="0" onchange="cekQtyNeed(this, ${rowIndex}, '${id_request}')">
                </div>
                 <div class="col-2 p-4">
                    <button class="btn btn-success setPR_${rowIndex}_${id_request}" id="setPR_${rowIndex}_${id_request}" onclick="setPR(${rowIndex}, '${Id_material}', '${id_request}')">Set</button>
                    <button class="btn btn-success unsetPR_${rowIndex}_${id_request}" id="unsetPR_${rowIndex}_${id_request}" onclick="unsetPR(${rowIndex}, '${Id_material}', '${id_request}')" style="display:none">Unset</button>
                </div>
            </div>
        `;

        // Append the new setting block to the container
        $('.setting-container').append(newSetting);
        getSloc(Id_material, rowIndex, id_request);
        $('.plus-row').prop('disabled', true);
    });
});

var materialSlocArray = []; // Array to store material settings

function getSloc(idMaterial, rowIndex, id_request) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_sloc_options'); ?>',
        method: 'POST',
        data: { id_material: idMaterial },
        success: function(response) {
            var data = JSON.parse(response);
            var $select = $(`.sloc_${rowIndex}_${id_request}`);
            $select.empty();
            $select.append('<option value="">-- Select Sloc --</option>');

            $.each(data, function(index, sloc) {
                $select.append('<option value="' + sloc.sloc_id + '">' + sloc.sloc_name + '</option>');
            });

            $select.select2({
                placeholder: "-- Select Sloc --",
                width: "100%"
            });

            // Attach change event listener to sloc-select
            $select.on('change', function() {
                var selectedSlocId = $(this).val();
                $(`.sloc_${rowIndex}_${id_request}`).val(selectedSlocId);
                getIdBox(idMaterial, selectedSlocId, rowIndex, id_request);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function getIdBox(idMaterial, slocId, rowIndex, id_request) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_id_box_options'); ?>',
        method: 'POST',
        data: { id_material: idMaterial, sloc_id: slocId },
        success: function(response) {
            var data = JSON.parse(response);
            var $boxSelect = $(`.box_${rowIndex}_${id_request}`);
            $boxSelect.empty();
            $boxSelect.append('<option value="">-- Select Box --</option>');

            $.each(data, function(index, box) {
                $boxSelect.append('<option value="' + box.id_box + '" data-total-qty="' + box.total_qty_real + '">' + box.no_box + '</option>');
            });

            $boxSelect.select2({
                placeholder: "-- Select Box --",
                width: "100%"
            });

            $boxSelect.on('change', function() {
                 // Check for duplicate box selection
                var selectedOption = $(this).find('option:selected');
                 var selectedBox = selectedOption.val();
                $(`.box_${rowIndex}_${id_request}`).val(selectedBox);
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
                $(`.qty_on_sloc_${rowIndex}_${id_request}`).val(totalQty);
                $(`.qty_need_${rowIndex}_${id_request}`).attr('max', totalQty);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
function getDetailRequest(id, material_id) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_detail_quality_request'); ?>',
        type: 'POST',
        data: {
            Id_material: material_id,
        },
        success: function(res) {
            var data = JSON.parse(res);
            $('#detailModal' + id + ' tbody').empty();
            if (data.status && data.dt.length > 0) {
                $('#detailModal' + id).modal('show');
                var dt = data.dt;
                for (var i = 0; i < dt.length; i++) {
                    var row = '<tr>' +
                        '<td style="text-align: left;">' + (i + 1) + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Id_material + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Material_desc + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Material_need + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Qty + '</td>' +
                        '<td style="text-align: left;">' +
                        '<select class="form-control sloc-select" name="sloc_name[]">' +
                        '<option value="">-- Pilih Sloc --</option>' +
                        '</select>' +
                        '</td>' +
                        '<td style="text-align: left;">' +
                        '<select class="form-control id-box-select" name="id_box[]">' +
                        '<option value="">-- Pilih ID Box --</option>' +
                        '</select>' +
                        '</td>' +
                        '</tr>';
                    $('#detailModal' + id + ' tbody').append(row);

                    // Initialize Select2 after adding row
                    $('.sloc-select').eq(i).select2({
                        placeholder: "-- Pilih Sloc --",
                        width: "100%"
                    });

                    $('.id-box-select').eq(i).select2({
                        placeholder: "-- Pilih ID Box --",
                        width: "100%"
                    });

                    // Initialize dataItems for this row
                    dataItems[i] = {
                        Id_material: dt[i].Id_material,
                        sloc_id: '',
                        id_box: ''
                    };

                    // Handle sloc-select change
                    $('.sloc-select').eq(i).on('change', function() {
                        var selectedIndex = $(this).closest('tr').index();
                        var selectedSlocId = $(this).val();

                        // Reset ID Box select
                        $('.id-box-select').eq(selectedIndex).html(
                            '<option value="">-- Pilih ID Box --</option>');
                        $('.id-box-select').eq(selectedIndex).select2({
                            placeholder: "-- Pilih ID Box --",
                            width: "100%"
                        });

                        // Update dataItems
                        dataItems[selectedIndex].sloc_id = selectedSlocId;

                        fetchIdBoxOptions(dt[selectedIndex].Id_material, selectedSlocId,
                            selectedIndex);
                    });

                    $('.id-box-select').eq(i).on('change', function() {
                        var selectedIndex = $(this).closest('tr').index();
                        var selectedIdBox = $(this).val();

                        dataItems[selectedIndex].id_box = selectedIdBox;
                    });

                    fetchSlocOptions(dt[i].Id_material, id, i);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Incomplete!',
                    text: 'Please edit the request data to complete the information for this request.'
                }).then(function() {
                    window.location.reload();
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

function approveRequest(id_material) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to approve this quality request?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Approve"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/approveQualityRequest'); ?>',
                type: 'POST',
                data: {
                    Id_material: id_material,
                    data_items: dataItems
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Quality Request Has Been Approved.'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                location.reload();
                            }
                        });
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


function cekQtyNeed(that, rowIndex, id_request) {
    var qty = $(that).val();
    var qtyOnSloc = $(`.qty_on_sloc_${rowIndex}_${id_request}`).val();

    if (parseFloat(qty) > parseFloat(qtyOnSloc)) {
        $(`.qty_need_${rowIndex}_${id_request}`).val(qtyOnSloc);
        Swal.fire({
            icon: 'error',
            text: 'Quantity cannot exceed the available quantity on Box'
        });
    } else {
        $(`.qty_need_${rowIndex}_${id_request}`).val(qty);
    }

}



function setPR(rowIndex, id_material, id_request) {
    var slocValue = $(`.sloc_${rowIndex}_${id_request}`).val();
    var boxValue = $(`.box_${rowIndex}_${id_request}`).val();
    var qtyNeedValue = $(`.qty_need_${rowIndex}_${id_request}`).val();
    var id_material = id_material;
    var Production_plan_detail_id = $(`.Production_plan_detail_id_${id_request}`).val();
    var material_desc = $(`.material_desc_${id_request}`).val();
    var production_plan = $(`.production_plan_${id_request}`).val();
    var id_request = $(`.id_request_${id_request}`).val();
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
        id_request: id_request,
        material_desc: material_desc,
        id_request: id_request,
    });
    console.log(materialSlocArray);

    // Disable the specific setPR button for this row
    $(`.setPR_${rowIndex}_${id_request}`).css('display', 'none');
    $(`.unsetPR_${rowIndex}_${id_request}`).css('display', 'block');
    $(`.qty_need_${rowIndex}_${id_request}`).prop('readonly', true);
    $(`.sloc_${rowIndex}_${id_request}`).prop('disabled', true);
    $(`.box_${rowIndex}_${id_request}`).prop('disabled', true);

    // Calculate the total qty_need in the array
    var totalQtyNeed = materialSlocArray.reduce((sum, item) => sum + item.qty_need, 0);
    var materialNeed = parseFloat($(`.material_need_${id_request}`).val());

    // Check if materialNeed is less than totalQtyNeed
    if (materialNeed > totalQtyNeed) {
        $('.plus-row').prop('disabled', false);
    }
    console.log(materialSlocArray);
}


function unsetPR(rowIndex, id_material, id_request){
    var slocValue = $(`.sloc_${rowIndex}_${id_request}`).val();
    var boxValue = $(`.box_${rowIndex}_${id_request}`).val();
    var qtyNeedValue = parseFloat($(`.qty_need_${rowIndex}_${id_request}`).val());
      // Find and remove the specific item from the materialSlocArray
      materialSlocArray = materialSlocArray.filter(item => 
        item.sloc !== slocValue || item.box !== boxValue || item.qty_need !== qtyNeedValue
    );
    // Disable the specific setPR button for this row
    $(`.setPR_${rowIndex}_${id_request}`).css('display', 'block');
    $(`.unsetPR_${rowIndex}_${id_request}`).css('display', 'none');
    $(`.qty_need_${rowIndex}_${id_request}`).prop('readonly', false);
    $(`.sloc_${rowIndex}_${id_request}`).prop('disabled', false);
    $(`.box_${rowIndex}_${id_request}`).prop('disabled', false);

    console.log(materialSlocArray);
}

function savePRDetail(id_material, id_request){
    if(materialSlocArray.length == 0){
        Swal.fire({
            icon: 'error',
            text: 'Please Complete the proccess!'
        });
        return;
    }
     // Calculate the total qty_need in the array
    var totalQtyNeed = materialSlocArray.reduce((sum, item) => sum + item.qty_need, 0);
    var materialNeed = parseFloat($(`.material_need_${id_request}`).val());

    // Check if materialNeed is less than totalQtyNeed
    if (materialNeed != totalQtyNeed) {
        Swal.fire({
            icon: 'error',
            text: 'Production plan quantity need must be equal to the total request quantity on boxes.'
        });
    } else {
        $.ajax({
        url: '<?php echo base_url('warehouse/save_quality_request_detail'); ?>',
        method: 'POST',
        data: { materialSlocArray: materialSlocArray },
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status) {
                    Swal.fire({
                        icon: 'success',
                        text: 'Save quality request detail is successfully.'
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
function closeModal(){
    window.location.reload();
}



function getdetailpr(id_request){
    $('.view-details').prop('disabled', true);
    $.ajax({
        url: '<?php echo base_url('warehouse/get_detail_quality_req'); ?>',
        method: 'POST',
        data: { id_request: id_request},
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
                            <input type="number" class="form-control" id="qty_need_${rowIndex}" name="qty_need_${rowIndex}[]" min="0" value="${item.qty_unpack}" readonly>
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


function rejectRequest(id_request) {
    var rejectDescription = $(`.keterangan-reject_${id_request}`).val();
    console.log(rejectDescription);
    if(!rejectDescription){
        Swal.fire({
            icon: 'error',
            text: 'Please fill out reject description form!',
        });
        return;
    }
    Swal.fire({
        title: "Are you sure?",
        text: "You want to reject this production request?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "yes"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/rejectQualityRequest'); ?>',
                type: 'POST',
                data: {
                    id_request: id_request,
                    reject_description: rejectDescription,
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Production Request Has Been Rejected.'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                location.reload();
                            }
                        });
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
function printReq(Id_request) {
    window.open('<?php echo base_url() . $this->router->fetch_class(); ?>/print_quality_request/' + Id_request, '_blank');
}
</script>