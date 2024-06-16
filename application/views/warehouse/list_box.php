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
                                <th><input type="checkbox" id="selectAll" onclick="toggleSelectAll()" /></th>
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
                                <td><input type="checkbox" class="selectBox" value="<?= $box['no_box']; ?>" /></td>
                                <td><?php echo $box['no_box']; ?></td>
                                <td><?php echo $box['weight']; ?> Kg</td>
                                <td><?php echo $box['sloc_name']; ?></td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#detailModal1<?= $box['no_box']; ?>"
                                        onclick="getDetailBox(<?= $box['id_box']; ?>, '<?= $box['no_box']; ?>')">
                                        <i class="bx bx-show" style="color: white;"></i>
                                    </button>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal1"
                                        onclick="editBox('<?= $box['id_box']; ?>')">
                                        <i class="bx bxs-edit" style="color: white;"></i>
                                    </button>
                                    <button class="btn btn-info" onclick="getBarcode('<?= $box['no_box']; ?>')">
                                        <i class="bx bxs-printer" style="color: white;"></i>
                                    </button>
                                    <button class="btn btn-danger" onclick="deleteBox('<?= $box['id_box']; ?>')">
                                        <i class="bx bxs-trash" style="color: white;"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Button to Print Selected Barcodes with Options for High Rak and Medium Rak -->
                    <button class="btn btn-info" onclick="printSelectedBarcodes('high')">Print Selected Barcodes (High
                        Rak)</button>
                    <button class="btn btn-info" onclick="printSelectedBarcodes('medium')">Print Selected Barcodes
                        (Medium Rak)</button>

                    <!-- JavaScript Function to Toggle Select All -->
                    <script>
                    function toggleSelectAll() {
                        var selectAllCheckbox = document.getElementById('selectAll');
                        var checkboxes = document.querySelectorAll('.selectBox');
                        checkboxes.forEach(function(checkbox) {
                            checkbox.checked = selectAllCheckbox.checked;
                        });
                    }
                    </script>
                    <script>
                
                    </script>
                    <div class="col-md ms-5 mt-5" style="display: none;">
                        <div id="qrcode"></div>
                        <div id="barcode-info" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="addForm" method="post" onsubmit="event.preventDefault(); saveAndPrint();">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Boxes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="col-form-label"><b>Total weight (kg)</b></label>
                            <input type="text" class="form-control" id="weight-add" name="weight-add"
                                onblur="getSloc()">
                        </div>
                        <div class="col-sm-6 mt-3">
                            <b>Sloc</b>
                            <select id="sloc_select" class="form-select" aria-label="Default select example"
                                name="sloc_select">
                                <option value="" disabled selected style="color: GREY;">Please select total weight first
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="col-form-label"><b>Number of Boxes</b></label>
                            <input type="number" class="form-control" id="number-of-boxes" name="number-of-boxes"
                                min="1" value="1">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="col-form-label"><b>Print Barcode Size</b></label>
                            <div>
                                <label><input type="radio" name="print-size" value="high" checked> High</label>
                                <label><input type="radio" name="print-size" value="medium"> Medium</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>Details</h5>
                            <table class="table table-bordered" id="detailsTable">
                                <thead>
                                    <tr>
                                        <th>Material Part Number</th>
                                        <th>Material Part Name</th>
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

<script>
function closeModal() {
    // Close the modal and reset the form
    document.getElementById("addForm").reset();
}

function saveAndPrint() {
    const numberOfBoxes = document.getElementById('number-of-boxes').value;
    const printSize = document.querySelector('input[name="print-size"]:checked').value;

    for (let i = 0; i < numberOfBoxes; i++) {
        // Generate barcode data (replace with your actual barcode generation logic)
        const barcodeData = generateBarcodeData(i + 1, printSize);

        // Create a hidden iframe
        const iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';

        // Add the iframe to the document body
        document.body.appendChild(iframe);

        // Write the barcode data to the iframe's document
        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(barcodeData);
        doc.close();

        // Print the content of the iframe
        iframe.contentWindow.focus();
        iframe.contentWindow.print();

        // Remove the iframe after printing
        document.body.removeChild(iframe);
    }

    // Close the modal after printing
    closeModal();
}

function generateBarcodeData(boxNumber, printSize) {
    // Replace this with your actual barcode HTML generation logic
    // Example HTML content for barcode
    return `
			<html>
				<head>
					<style>
						/* Add your barcode styling here */
					</style>
				</head>
				<body>
					<div style="text-align: center; font-size: ${printSize === 'high' ? '24px' : '18px'};">
						<p>Box Number: ${boxNumber}</p>
						<img src="path/to/barcode/image.png" alt="Barcode">
					</div>
				</body>
			</html>
		`;
}
</script>


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
                            <label class="col-form-label"><b>Total weight (kg)</b></label>
                            <input type="text" class="form-control" id="weight-edit" name="weight-edit" required
                                onblur="getSlocEdit()">
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

<!-- DETAIL MODAL -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Box Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Details will be appended here by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
function closeModal() {
    $('#id_box').val("");
    $('#weight-edit').val("");
    $('#sloc_select_edit').val("");
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
                $('#sloc_select_edit').empty();

                $.each(data.all_slocs, function(index, sloc) {
                    $('#sloc_select_edit').append($('<option>', {
                        value: sloc.Id_storage,
                        text: sloc.SLoc,
                        selected: sloc.Id_storage == data.box.sloc
                    }));
                });

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
                        '<td><button type="button" class="btn btn-danger" onclick="removeItemRow(this)">Remove</button></td>' +
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

function addNewItemRow() {
    var index = $('#detailsBody tr').length;
    $('#detailsBody').append('<tr>' +
        '<td><input type="text" class="form-control" name="details[' + index + '][id_material]" required></td>' +
        '<td><input type="text" class="form-control" name="details[' + index + '][material_desc]" required></td>' +
        '<td><input type="number" class="form-control" name="details[' + index + '][qty]" required></td>' +
        '<td><input type="text" class="form-control" name="details[' + index + '][uom]" required></td>' +
        '<td><button type="button" class="btn btn-danger" onclick="removeItemRow(this)">Remove</button></td>' +
        '</tr>');
}

function removeItemRow(button) {
    $(button).closest('tr').remove();
}

function getSlocEdit() {
    var total_weight = $('#weight-edit').val();
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
                $('#detailModal').find('.modal-body').empty();
                $.each(data.details, function(index, detail) {
                    $('#detailModal').find('.modal-body').append('<p>' +
                        '<b>Part Number:</b> ' + detail.id_material + '<br>' +
                        '<b>Part Name:</b> ' + detail.material_desc + '<br>' +
                        '<b>QTY:</b> ' + detail.qty + '<br>' +
                        '<b>UOM:</b> ' + detail.uom + '<br>' +
                        '</p><hr>');
                });
                $('#detailModal').modal('show');
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

function printSelectedBarcodes() {
    var selectedBoxes = [];
    $('.selectBox:checked').each(function() {
        selectedBoxes.push($(this).val());
    });
    console.log(selectedBoxes);
    if (selectedBoxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'No Box Selected',
            text: 'Please select at least one box to print barcodes.'
        });
        return;
    }

    var logoUrl = '<?php echo base_url("assets/img/valeo_logo.jpg"); ?>';
    var qrcodeContainer = document.createElement('div');

    function generateQRCode(no_box, callback) {
    var qrcodeContainer = document.createElement('div');
        var qrcode = new QRCode(qrcodeContainer, {
            text: no_box,
            width: 70,
            height: 70,
            correctLevel: QRCode.CorrectLevel.H
        });

        setTimeout(function() {
            var qrcodeImg = qrcodeContainer.querySelector('img').src;
            callback(no_box, qrcodeImg); 
        }, 500); 
    }

    $('.selectBox:checked').each(function() {
        var no_box = $(this).val();
        generateQRCode(no_box, addBarcodeToPrintWindow); // Memanggil fungsi generateQRCode untuk setiap kotak yang dipilih
    });


    var printWindow = window.open('', '', 'height=800,width=1200');
    printWindow.document.write('<html><head><title>Print Barcodes</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: 15cm 10cm; margin: 0; }');
    printWindow.document.write(
        '.print-section { display: flex; flex-direction: column; width: 15cm; height: 7cm; border: 1px solid black; box-sizing: border-box; margin-bottom: 10px; }'
    );
    printWindow.document.write(
        '.row { display: flex; flex: 1; align-items: center; justify-content: center; border-bottom: 1px solid black; }'
    );
    printWindow.document.write('.row:first-child { height: 5cm; justify-content: space-between; padding: 0 5px; }');
    printWindow.document.write(
        '.row:last-child { height: 5cm; border: none; text-align: center; font-size: 1em; align-items: flex-start; margin-top: 3px; }'
    );
    printWindow.document.write('.barcode, .valeo-logo { display: inline-block; text-align: center;}');
    printWindow.document.write('.barcode { width: 2cm; height: 2cm; margin:5px;}');
    printWindow.document.write(
        `.valeo-logo { width: 2cm; height: 2cm; margin-right: 10px; margin-top: 1px;margin-buttom: 1px; }`
    );
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');

    function addBarcodeToPrintWindow(no_box, qrcodeImg) {
        printWindow.document.write('<div class="print-section">');
        printWindow.document.write('<div class="row">');
        printWindow.document.write('<div class="barcode"><img src="' + qrcodeImg + '" alt="QR Code"></div>');
        printWindow.document.write('<div class="valeo-logo"><img src="' + logoUrl +
            '" alt="Valeo Logo" style="width: 100%; height: 100%;"></div>');
        printWindow.document.write('</div>');
        printWindow.document.write('<div class="row">');
        printWindow.document.write(
            '<div class="barcode-info">ID Box:<br><h1 style="font-size:6em; margin-top:24px;">' +
            no_box + '</h1></div>');
        printWindow.document.write('</div>');
        printWindow.document.write('</div>');
    }

    function processNextBox(index) {
        if (index < selectedBoxes.length) {
            generateQRCode(selectedBoxes[index], function(qrcodeImg) {
                addBarcodeToPrintWindow(selectedBoxes[index], qrcodeImg);
                processNextBox(index + 1);
            });
        } else {
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    }

    // processNextBox(0);
}


function deleteBox(id_box) {
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
                url: '<?php echo base_url('warehouse/delete_box'); ?>',
                type: 'POST',
                data: {
                    id_box: id_box
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Box has been deleted.",
                            icon: "success"
                        });
                        window.location.reload();
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
    });
}

function getSloc() {
    var total_weight = $('#weight-add').val();
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
        var printSize = $('input[name="print-size"]:checked').val();
        if (printSize === 'high') {
            printBarcode(no_box);
        }
    }, 500);
}

function generateBoxIds(lastBoxId, count) {
    const prefix = lastBoxId.slice(0, 2); // 'CK'
    let lastChar = lastBoxId.slice(2, 3); // 'A'
    let lastNumber = parseInt(lastBoxId.slice(3)); // '000001'

    const boxIds = [];
    for (let i = 0; i < count; i++) {
        lastNumber += 1;
        if (lastNumber > 99999) {
            lastNumber = 1;
            lastChar = String.fromCharCode(lastChar.charCodeAt(0) + 1);
        }
        const newBoxId = prefix + lastChar + lastNumber.toString().padStart(6, '0');
        boxIds.push(newBoxId);
    }

    return boxIds;
}


function printBarcodeHigh(idBox) {
    var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?>';
    var printWindow = window.open('', '', 'height=750,width=500');
    printWindow.document.write('<html><head><title>Print Barcode</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: 17cm 13cm; margin: 5px; }');
    printWindow.document.write(
        '.print-section { display: flex; flex-direction: column; width: 14cm; height: 8cm; border: 1px solid black; box-sizing: border-box; }'
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



function printBarcodeMedium(idBox) {
    var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?>';
    var printWindow = window.open('', '', 'height=750,width=500');
    printWindow.document.write('<html><head><title>Print Barcode</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: 17cm 13cm; margin: 5px; }');
    printWindow.document.write(
        '.print-section { display: flex; flex-direction: column; width: 14cm; height: 8cm; border: 1px solid black; box-sizing: border-box; }'
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


let printCount = 0;


function printBarcode(idBox) {
    var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?>';
    var printWindow = window.open('', '', 'height=750,width=500');
    printWindow.document.write('<html><head><title>Print Barcode</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: 17cm 13cm; margin: 5px; }');
    printWindow.document.write(
        '.print-section { display: flex; flex-direction: column; width: 14cm; height: 8cm; border: 1px solid black; box-sizing: border-box; }'
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
        '<div class="barcode-info" style="margin-top:40px;"><span>ID :</span><h1 style="font-size:3em; margin-top:-5;">' +
        idBox + '</h1></div>');
    printWindow.document.write('</div>');
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
    printCount++;
}

function getBarcode(no_box) {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: no_box,
        width: 150,
        height: 100,
        correctLevel: QRCode.CorrectLevel.H
    });

    setTimeout(function() {
        var printSize = $('input[name="print-size"]:checked').val();
        printBarcode(no_box, printSize);
    }, 500);
}

function printMultipleBarcodes(boxIds, printSize) {
    var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?>';
    var printWindow = window.open('', '', 'height=750,width=500');
    printWindow.document.write('<html><head><title>Print Barcode</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('@page { size: 17cm 13cm; margin: 5px; }');
    printWindow.document.write(
        '.print-section { display: flex; flex-direction: column; width: 14cm; height: 8cm; border: 1px solid black; box-sizing: border-box; }'
    );
    printWindow.document.write(
        '.row { display: flex; flex: 1; align-items: center; justify-content: space-between; border-bottom: 1px solid black; }'
    );
    printWindow.document.write('.row:first-child { height: 7cm; padding: 0 2px; }');
    printWindow.document.write(
        '.row:last-child { height:8cm; align-items: center; justify-content: center; text-align: center; }');
    printWindow.document.write('.barcode, .valeo-logo { display: inline-block; text-align: center;}');
    printWindow.document.write('.barcode { width: 2cm; height: 2cm; margin:5cm;}');
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
        '<div class="barcode-info" style="margin-top:40px;"><span> Box:</span><h1 style="font-size:3em; margin-top:-5;">' +
        idBox + '</h1></div>');
    printWindow.document.write('</div>');
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}


function generateBoxIds(lastBoxId, count) {
    const prefix = 'CKA'; // Prefix yang diinginkan
    let lastNumber = parseInt(lastBoxId.slice(3)); // Memotong bagian nomor dari ID terakhir

    const boxIds = [];
    for (let i = 0; i < count; i++) {
        lastNumber += 1;
        if (lastNumber > 999999) {
            lastNumber = 1; // Jika melebihi batas, reset kembali ke 1
        }
        const newBoxId = prefix + lastNumber.toString().padStart(5, '0');
        boxIds.push(newBoxId);
    }

    return boxIds;
}




// Function to handle form submission
$('#addForm').on('submit', function(event) {
    event.preventDefault();

    var formData = $(this).serialize();
    var numberOfBoxes = parseInt($('#number-of-boxes').val());
    var printSize = $('input[name="print-size"]:checked').val();

    $.ajax({
        url: '<?php echo base_url('warehouse/add_new_boxes'); ?>',
        type: 'POST',
        data: formData,
        success: function(res) {
            var data = JSON.parse(res);
            if (data.status) {
                var boxIds = generateBoxIds(data.lastBoxId, numberOfBoxes);
                if (printSize === 'high') {
                    printMultipleBarcodes(boxIds, 'high');
                } else {
                    printMultipleBarcodes(boxIds, 'medium');
                }
                window.location.reload();
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
                    id: id
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
