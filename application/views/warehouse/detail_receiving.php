<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Document</title>
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
</head>

<body>
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
                        <button type="button" class="btn btn-primary mb-2 mt-5" data-bs-toggle="modal"
                            data-bs-target="#addModal1" style="font-weight: bold;" id="addBtn">
                            +
                        </button>
                        <button class="btn btn-success mb-2 mt-5" onclick="refreshAll()">
                            <i class="bx bx-revision"></i>
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
								foreach ($receiving_material as $material):
									$number++ ?>
                                <tr>
                                    <td>
                                        <?= $number; ?>
                                    </td>
                                    <td><?php echo $material['reference_number']; ?></td>
                                    <td><?php echo $material['material_desc']; ?></td>
                                    <td><?php echo $material['qty']; ?></td>
                                    <td><?php echo $material['uom']; ?></td>
                                    <td>
                                        <button class="btn btn-success btn-edit" data-id="<?= $material['id']; ?>">
                                            <i class="bx bxs-edit" style="color: white;"></i>
                                        </button>
                                        <button class="btn btn-danger ms-1"
                                            onclick="deleteItem(<?= $material['id']; ?>)">
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
                                <input type="text" class="form-control" id="total_weight" onblur="getSloc()">
                            </div>
                            <label class="col-sm-2 col-form-label">
                                <b>Select SLoc</b>
                            </label>
                            <div class="col-sm-4">
                                <select id="sloc_select" class="form-select" aria-label="Default select example">
                                    <option value="" disabled selected style="color: GREY;">Please select total weight
                                        first</option>
                                    <?php foreach ($list_storage as $storage): ?>
                                    <option value="<?= $storage['sloc']; ?>" data-max-box="4"><?= $storage['sloc']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2" style="text-align: right; margin-right: 5px;">
                            <div class="col-md-10"></div>
                            <div class="col-md">
                                <button class="btn btn-primary" onclick="getBarcode()" id="approveBtn">
                                    Approve
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2 mb-3">
                            <div class="col-md" style="margin-left: 12px;">
                                <b>Barcode</b>
                            </div>
                        </div>
                        <div class="col-md ms-5 mt-5">
                            <div id="qrcode"></div>
                            <div id="barcode-info" class="mt-3"></div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-10"></div>
                            <div class="col-md">
                                <form action="<?= base_url('warehouse/clearData') ?>" method="post">
                                    <!-- Kosong -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
    $(document).ready(function() {
        $('#addModal1').on('shown.bs.modal', function() {
            $('#reference_number').select2({
                dropdownParent: $('#addModal1'),
                width: '100%'
            });
        });

        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            $('#editModal' + id).modal('show');
        });
    })

    function closeModal() {
        $('#reference_number').val("");
        $('#material').val("");
        $('#qty').val("");
        $('#uom').val("");
    }

    function refreshAll() {
        $.ajax({
            url: '<?php echo base_url('warehouse/delete_receiving_temp'); ?> ',
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
            url: '<?php echo base_url('warehouse/get_material_data'); ?> ',
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
        var total_weight = $('#total_weight').val();
        var slocSelect = $('#sloc_select');
        var selectedSloc = slocSelect.val();
        var maxBox = parseInt(slocSelect.find('option:selected').attr('data-max-box'));
        var currentBoxCount = 0;

        // Menghitung jumlah box yang sudah disimpan pada sloc yang dipilih
        slocSelect.find('option').each(function() {
            if ($(this).val() === selectedSloc) {
                currentBoxCount++;
            }
        });

        // Validasi jika jumlah box sudah mencapai batas maksimal
        if (currentBoxCount >= maxBox) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'The selected SLoc already has the maximum number of boxes allowed.',
            });
            slocSelect.val(''); // Reset pilihan SLoc
            return;
        }

        $.ajax({
            url: '<?php echo base_url('warehouse/get_sloc'); ?> ',
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
                            '<option value="">No available Slocs for the specified total weight</option>'
                        );
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

    function getBarcode() {
        var slocSelect = $('#sloc_select').val();
        var total_weight = $('#total_weight').val();

        if (slocSelect && total_weight) {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to approve this box?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Approve"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?php echo base_url('warehouse/save_new_box'); ?>',
                        type: 'POST',
                        data: {
                            id_sloc: slocSelect,
                            total_weight: total_weight,
                        },
                        success: function(res) {
                            var data = JSON.parse(res);
                            if (data.status) {
                                var qrcode = new QRCode(document.getElementById("qrcode"), {
                                    text: data.no_box,
                                    width: 150,
                                    height: 150,
                                    correctLevel: QRCode.CorrectLevel.H
                                });

                                $('#barcode-info').html('<b>ID Box:</b> ' + data.no_box);

                                setTimeout(function() {
                                    printBarcode(data.no_box);
                                }, 500);

                                document.getElementById("approveBtn").disabled = true;
                                document.getElementById("addBtn").disabled = true;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Please add material!',
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
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Incomplete Data',
                text: 'Please complete the Sloc field.'
            });
        }
    }

    function printBarcode(idBox) {
        var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?> ';
        var printWindow = window.open('', '', 'height=450,width=500');
        printWindow.document.write('<html><head><title>Print Barcode</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { size: 15cm 10cm; margin: 5px; }');
        printWindow.document.write(
            '.print-section { display: flex; flex-direction: column; width: 15cm; height: 8cm; border: 1px solid black; box-sizing: border-box; }'
        );
        printWindow.document.write(
            '.row { display: flex; flex: 1; align-items: center; justify-content: space-between; border-bottom: 1px solid black; }'
        );
        printWindow.document.write('.row:first-child { height: 3cm; padding: 0 2px; }');
        printWindow.document.write(
            '.row:last-child { height:5cm; align-items: center; justify-content: center; text-align: center; }');
        printWindow.document.write('.barcode, .valeo-logo { display: inline-block; text-align: center;}');
        printWindow.document.write('.barcode { width: 2cm; height: 2cm; margin-left:2cm;}');
        printWindow.document.write(
            `.valeo-logo { width:5cm; height: 2cm;margin-right:1cm; background-position: center; }`
        );
        printWindow.document.write(
            '.barcode-info { font-size: 2em; margin-top: 8px; text-align: center; width: 100%; margin-left:15px; }');
        printWindow.document.write('#qrcode img { width: 90%; height: 90%; }');
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
        printWindow.document.write(
            '<div class="barcode-info" style="margin-top:40px;"><span>ID Box:</span><h1 style="font-size:3em; margin-top:-5;">' +
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
    </script>

    <!-- ADD MODAL-->
    <div class="modal fade" id="addModal1" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <?= form_open_multipart('Warehouse/AddReceivingMaterial'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add New Data Receiving</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="row ps-2">
                        <div class="col-6">
                            <label for="reference_number" class="form-label">Material Part Number</label>
                            <select class="form-select" id="reference_number" name="reference_number"
                                onchange="getMaterialDetails()" required>
                                <option value="" disabled selected>Please select</option>
                                <?php foreach ($material_list as $material): ?>
                                <option value="<?= $material['Id_material']; ?>"
                                    data-desc="<?= $material['Material_desc']; ?>" data-uom="<?= $material['Uom']; ?>">
                                    <?= $material['Id_material']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="material" class="form-label">Material</label>
                            <input type="text" class="form-control" id="material" name="material" readonly required>
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
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL-->
    <?php foreach ($receiving_material as $material): ?>
    <div class="modal fade" id="editModal<?= $material['id']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editForm<?= $material['id']; ?>" action="<?= base_url('warehouse/editItemMaterial'); ?>"
                    method="post">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_box_detail" name="id_box_detail"
                            value="<?= $material['id_box_detail']; ?>">
                        <div class="row ps-2">
                            <div class="col-6">
                                <label for="reference_number" class="form-label">Material Part Number</label>
                                <input type="text" class="form-control" id="reference_number<?= $material['id']; ?>"
                                    name="reference_number" value="<?= $material['id_material']; ?>" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="material" class="form-label">Material</label>
                                <input type="text" class="form-control" id="material<?= $material['id']; ?>"
                                    name="material" value="<?= $material['material_desc']; ?>" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="uom" class="form-label">UOM</label>
                                <input type="text" class="form-control" id="uom<?= $material['id']; ?>" name="uom"
                                    value="<?= $material['uom']; ?>" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="qty<?= $material['id']; ?>" name="qty"
                                    value="<?= $material['qty']; ?>" required>
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
        $('#reference_number').val("");
        $('#material').val("");
        $('#qty').val("");
        $('#uom').val("");
    }

    function getMaterialDetails() {
        var selectedOption = $('#reference_number').find('option:selected');
        var materialDesc = selectedOption.data('desc');
        var uom = selectedOption.data('uom');

        $('#material').val(materialDesc);
        $('#uom').val(uom);
    }
    </script>
</body>

</html>