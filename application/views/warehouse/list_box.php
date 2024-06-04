<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card ml-5">
                <div class="card-body table-responsive mt-2">
                    <?php if ($this->session->flashdata('SUCCESS') != '') { ?>
                    <?= $this->session->flashdata('SUCCESS'); ?>
                    <?php } ?>
                    <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal"
                        style="font-weight: bold;" id="addBtn">
                        Add New Box
                    </button>
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No Box</th>
                                <th>Total Weight</th>
                                <th>SLoc</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0;
							foreach ($list_box as $box):
								$number++ ?>
                            <tr>
                                <td><?= $number; ?></td>
                                <td><?php echo $box['no_box']; ?></td>
                                <td><?php echo $box['weight']; ?> Kg</td>
                                <td><?php echo $box['sloc']; ?></td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#detailModal1<?= $box['no_box']; ?>"
                                        onclick="getDetailBox(<?= $box['no_box']; ?>, '<?= $box['no_box']; ?>')">
                                        <i class="bx bx-show" style="color: white;"></i>
                                    </button>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal1"
                                        onclick="editBox('<?= $box['id_box']; ?>')">
                                        <i class="bx bxs-edit" style="color: white;"></i>
                                    </button>
                                    <button class="btn btn-info" onclick="getBarcode('<?= $box['no_box']; ?>')">
                                        <i class="bx bxs-printer" style="color: white;"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="col-md ms-5 mt-5" style="display: none;">
                        <div id="qrcode"></div>
                        <div id="barcode-info" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addForm" action="<?= base_url('warehouse/add_new_box'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Box</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="col-form-label">
                                <b>Total weight (kg)</b>
                            </label>
                            <input type="text" class="form-control" id="weight-add" name="weight-add"
                                onblur="getSloc()">
                        </div>
                        <div class="col-sm-6 mt-3">
                            <b>Sloc</b>
                            <select id="sloc_select" class="form-select" aria-label="Default select example">
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
                                    </tr>
                                </thead>
                                <tbody id="detailsBody">
                                    <!-- Details will be appended here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="closeModal()">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal1" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editForm" action="<?= base_url('warehouse/edit_box'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Box</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_box" name="id_box">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="col-form-label">
                                <b>Total weight (kg)</b>
                            </label>
                            <input type="text" class="form-control" id="weight-edit" name="weight-edit" required
                                onblur="getSlocEdit()">
                        </div>
                        <div class="col-sm-6 mt-3">
                            <b>Sloc</b>
                            <select id="sloc_select_edit" class="form-select" aria-label="Default select example">
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
                                    </tr>
                                </thead>
                                <tbody id="detailsBody">
                                    <!-- Details will be appended here by JavaScript -->
                                </tbody>
                            </table>
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


<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
function closeModal() {
    $('#id_box').val("");
    $('#weight').val("");
    $('#sloc').val("");
    $('#detailsBody').empty();
}

function editBox(id_box) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_box'); ?>',
        type: 'POST',
        data: {
            id_box: id_box
        },
        success: function(res) {
            var data = JSON.parse(res);
            if (data.status) {
                $('#id_box').val(id_box);
                $('#weight-edit').val(data.box.weight);
                $('#sloc_select_edit').empty().append($('<option>', {
                    value: data.box.id_sloc,
                    text: data.box.sloc
                }));
                $('#detailsBody').empty();
                $.each(data.details, function(index, detail) {
                    $('#detailsBody').append('<tr>' +
                        '<td><input type="text" class="form-control" name="details[' + index +
                        '][id_material]" value="' + detail.id_material + '" required></td>' +
                        '<td><input type="text" class="form-control" name="details[' + index +
                        '][material_desc]" value="' + detail.material_desc +
                        '" required></td>' +
                        '<td><input type="number" class="form-control" name="details[' + index +
                        '][qty]" value="' + detail.qty + '" required></td>' +
                        '<td><input type="text" class="form-control" name="details[' + index +
                        '][uom]" value="' + detail.uom + '" required></td>' +
                        '</tr>');
                });
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
}

function getDetailBox(id_box, no_box) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_box_details'); ?>',
        type: 'POST',
        data: {
            id_box: id_box
        },
        success: function(res) {
            var data = JSON.parse(res);
            if (data.status) {
                $('#detailModal1' + no_box).find('.modal-body').empty();
                $.each(data.details, function(index, detail) {
                    $('#detailModal1' + no_box).find('.modal-body').append('<p>' +
                        '<b>Part Number:</b> ' + detail.id_material + '<br>' +
                        '<b>Part Name:</b> ' + detail.material_desc + '<br>' +
                        '<b>QTY:</b> ' + detail.qty + '<br>' +
                        '<b>UOM:</b> ' + detail.uom + '<br>' +
                        '</p><hr>');
                });
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

function getMaterial() {
    $('#material').val("");
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
                $('#material').val(data.material);
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
}

function getSloc() {
    var total_weight = $('#weight-add').val();
    console.log('ok');
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
                    slocSelect.append('<option value="">Please select total weight first</option>');
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

function getBarcode(no_box) {

    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: no_box,
        width: 150,
        height: 150,
        correctLevel: QRCode.CorrectLevel.H
    });


    setTimeout(function() {
        printBarcode(no_box);
    }, 500);
}


function getSlocEdit() {
    var total_weight = $('#weight-edit').val();
    console.log('ok');
    var slocSelect = $('#sloc_select_edit');

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
                    slocSelect.append('<option value="">Please select total weight first</option>');
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

function getBarcode(no_box) {

    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: no_box,
        width: 150,
        height: 150,
        correctLevel: QRCode.CorrectLevel.H
    });


    setTimeout(function() {
        printBarcode(no_box);
    }, 500);
}

function printBarcode(idBox) {
    var logoUrl = '<?php echo base_url("assets/img/valeo_logo.jpg"); ?>';
    var printWindow = window.open('', '', 'height=400,width=600');
    printWindow.document.write('<html><head><title>Print Barcode</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: 15cm 10cm; margin: 0; }');
    printWindow.document.write(
        '.print-section { display: flex; flex-direction: column; width: 15cm; height: 7cm; border: 1px solid black; box-sizing: border-box; }'
    );
    printWindow.document.write(
        '.row { display: flex; flex: 1; align-items: center; justify-content: center; border-bottom: 1px solid black; }'
    );
    printWindow.document.write('.row:first-child { height: 5cm; justify-content: space-between; padding: 0 5px; }');
    printWindow.document.write(
        '.row:last-child { height: 5cm; border: none; text-align: center; font-size: 1em; align-items: flex-start; margin-top: 3px; }'
    );
    printWindow.document.write('.barcode, .valeo-logo { display: inline-block; text-align: center;}');
    printWindow.document.write('.barcode { width: 2cm; height: 2cm; margin-left:25px;}');
    printWindow.document.write(
        `.valeo-logo { width: 3cm; height: 3cm; background-image: url('${logoUrl}'); background-size: contain; background-repeat: no-repeat; background-position: center; margin-right: 10px; margin-top: 3px;}`
    );
    printWindow.document.write('#qrcode img { width: 100%; height: 100%; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<div class="print-section">');
    printWindow.document.write('<div class="row">');
    printWindow.document.write('<div class="barcode" id="qrcode">' + document.getElementById('qrcode').innerHTML +
        '</div>');
    printWindow.document.write('<div class="valeo-logo"><img src="' + logoUrl +
        '" alt="Valeo Logo" style="width: 100%; height: 100%;"></div>');
    printWindow.document.write('</div>');
    printWindow.document.write('<div class="row">');
    printWindow.document.write('<div class="barcode-info">ID Box:<br><h1 style="font-size:6em; margin-top:24px;">' +
        idBox + '</h1></div>');
    printWindow.document.write('</div>');
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
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
                url: '<?php echo base_url('warehouse/delete_material_temp'); ?>',
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

function editBox(id_box) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_box'); ?>',
        type: 'POST',
        data: {
            id_box: id_box,
        },
        success: function(res) {
            var data = JSON.parse(res);
            console.log(data.box.Sloc);
            if (data.status) {
                $('#weight-edit').val(data.box.weight);
                $('#sloc_select_edit').append($('<option>', {
                    value: data.box.id_sloc,
                    text: data.box.Sloc
                }));
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

}
</script>
