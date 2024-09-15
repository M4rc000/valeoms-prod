<style>
.select2-container {
    z-index: 999;
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
                <div class="row mb-2 mt-5 mb-5" style="margin-left: 20px">
                    <?php $list_box ?>
                    <div class="col-sm-3">
                        <select class="form-select" id="id_box">
                            <option value="">Select Box</option>
                            <?php foreach ($list_box as $box): ?>
                            <option value="<?php echo $box['id_box']; ?>"><?php echo $box['no_box']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Tombol Add Material (default hidden) -->
                    <button type="button" class="btn btn-primary mb-2 mt-4" data-bs-toggle="modal"
                        data-bs-target="#addModal1" style="font-weight: bold; display: none;" id="addBtn">
                        Add Material
                    </button>


                    <div class="col-sm-3">
                        <button class="btn btn-success" id="search-box">
                            search
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive mt-2" style="display: none;">
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Part Number</th>
                                <th>Part Name</th>
                                <th>Sloc</th>
                                <th>QTY</th>
                                <th>UOM</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="detailsBody">
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                    <div class="row mt-5">
                        <div class="col-md-10"></div>
                        <div class="col-md">
                            <form action="<?= base_url('warehouse/clearData') ?>" method="post">

                            </form>
                        </div>
                    </div>

                    <h2>Total Weight: <span id="totalWeightDisplay">N/A</span></h2>
                    <h2>Sloc: <span id="slocDisplay">N/A</span></h2>


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
            <?= form_open_multipart('Warehouse/AddItemBoxCycle'); ?>
            <div class="modal-header">
                <h5 class="modal-title">Add New Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row ps-2">
                    <!-- Material Part Number Dropdown -->
                    <div class="col-6">
                        <label for="reference_number" class="form-label">Material Part Number</label>
                        <select class="form-select" id="reference_number" name="reference_number">
                            <option value="">Select Material Part No</option>
                            <?php foreach ($materials as $mtr): ?>
                            <option value="<?= $mtr['Id_material']; ?>"><?= $mtr['Id_material']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Material Description (Auto-filled after selecting material id) -->
                    <div class="col-6 mb-3">
                        <label for="material_desc" class="form-label">Material Description</label>
                        <input type="text" class="form-control" id="material_desc" name="material_desc" readonly>
                        <input type="hidden" class="form-control" id="id_boxs" name="id_box" value="" hidden>
                        <input type="hidden" class="form-control" id="id_sloc" name="id_sloc" value="" hidden>
                    </div>

                    <!-- UOM (Auto-filled after selecting material id) -->
                    <div class="col-6 mb-3">
                        <label for="uom" class="form-label">UOM</label>
                        <input type="text" class="form-control" id="uom" name="uom" readonly>
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



<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="height: 400px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Quantity and Sloc</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-sm-12 mb-3">
                    <label style="font-weight: bold; margin-bottom: 10px;">Quantity</label>
                    <input type="text" class="form-control" id="qty" placeholder="">
                </div>
                <div class="col-sm-12 mb-3">
                    <label style="font-weight: bold; margin-bottom: 10px;">Sloc</label>
                    <select class="form-control" id="sloc">
                        <option value="">Select Sloc</option>
                        <!-- Options will be dynamically loaded -->
                    </select>
                </div>
                <div class="col-sm-12 mb-3">
                    <label style="font-weight: bold; margin-bottom: 10px;">Total Weight</label>
                    <input type="text" class="form-control" id="total_weight" placeholder="">
                </div>
                <input type="hidden" class="form-control" id="id_box_detail" placeholder="" disabled>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="closeModal()">Close</button>
                <button type="submit" onclick="saveEdit()" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<!-- <script src="../assets/js/select2.min.js"></script> -->
<script>
$(document).ready(function() {
    
    // When Material Part Number changes, trigger the AJAX call
    $('#reference_number').change(function() {
        var refnumber = $(this).val(); // Get selected material part number

        if (refnumber !== "") {
            $.ajax({
                url: '<?= base_url('warehouse/get_material_data'); ?>', // URL to the backend method
                type: 'POST',
                data: {
                    refnumber: refnumber
                }, // Send the selected reference number
                success: function(response) {
                    var data = JSON.parse(response); // Parse the JSON response

                    if (data.status === true) {
                        // Fill in the Material Description and UOM fields
                        $('#material_desc').val(data.material);
                        $('#uom').val(data.uom);
                    } else {
                        // If no material is found, show an error and clear the fields
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.msg
                        });
                        $('#material_desc').val('');
                        $('#uom').val('');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // Handle any errors during the AJAX request
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while fetching material data.'
                    });
                }
            });
        } else {
            // Clear the fields if no material is selected
            $('#material_desc').val('');
            $('#uom').val('');
        }
    });
});


$(document).ready(function() {

    $('#search-box').on('click', function(){
        var id_box = $('#id_box').val();
        window.location.href = '<?= base_url('warehouse/cycle_box_view/'); ?>' + id_box;
    })
    $('#addModal1').on('shown.bs.modal', function() {
        $('#reference_number').select2({
            dropdownParent: $('#addModal1'),
            width: '100%',
        }).on('select2:open', function() {
            $('.select2-container').css('z-index', 9999);
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


<script>
$(document).ready(function() {
    // Initialize Select2 when the modal is shown
    $('#addModal1').on('shown.bs.modal', function() {
        $('#reference_number').select2({
            width: '100%' // Make Select2 full-width
        });
    });

    $('#id_box').select2();
    $('#id_box_modal').select2({
        dropdownParent: $('#unpackModal') // Ensure the dropdown is appended to the modal
    });
});

function fillEditModal(qty, id_box_detail, sloc, total_weight) {
    $('#qty').val(qty);
    $('#id_box_detail').val(id_box_detail);
    $('#total_weight').val(total_weight); // Set total weight

    // Load available SLOC options dynamically from the new endpoint
    $.ajax({
        url: '<?php echo base_url('warehouse/get_all_sloc_options'); ?>',
        type: 'POST',
        success: function(response) {
            console.log(response); // Debug: Check if the response is valid
            var slocOptions = JSON.parse(response);

            // Check if slocOptions is valid and not empty
            if (Array.isArray(slocOptions) && slocOptions.length) {
                // Empty and populate the SLOC dropdown
                $('#sloc').empty();
                slocOptions.forEach(function(option) {
                    $('#sloc').append(
                        `<option value="${option.sloc_id}">${option.sloc_name}</option>`
                    );
                });

                // Select the sloc after populating the dropdown
                $('#sloc').val(sloc);
            } else {
                console.error('No SLOC options found'); // Log if no options were returned
                $('#sloc').append(`<option value="">No SLOC available</option>`);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.error('Error occurred while loading SLOC options.');
        }
    });
}


// function getBox() {
//     var id_box = $('#id_box').val();

//     if (!id_box) {
//         Swal.fire({
//             icon: 'warning',
//             title: 'Warning',
//             text: 'Please select a box before searching!'
//         });
//         return;
//     }

//     $.ajax({
//         url: '<?php echo base_url('warehouse/get_box_details'); ?>',
//         type: 'POST',
//         data: {
//             id_box: id_box
//         },
//         success: function(res) {
//             var data = JSON.parse(res);
//             console.log(data);

//             $('#addBtn').on('click', function() {
//                 $('#addModal1').modal('show');
//                 $('#id_boxs').val(data.box['id_box']);
//                 $('#id_sloc').val(data.box['sloc']);

//             });


//             if (data.status === 'success') {
//                 $('#detailsBody').empty();
//                 $('#totalWeightDisplay').text(data.total_weight || 'N/A');
//                 $('#slocDisplay').text(data.sloc || 'N/A');

//                 $.each(data.detail, function(index, detail) {
//                     var slocDisplay = detail.Sloc ? detail.Sloc : 'Belum di Set';

//                     $('#detailsBody').append(`
// 						<tr>
// 							<td>${detail.id_material}</td>
// 							<td>${detail.material_desc}</td>
// 							<td>${slocDisplay}</td>
// 							<td>${detail.qty}</td>
// 							<td>${detail.uom}</td>
// 							<td>
// 								<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal"
// 									onclick="fillEditModal(${detail.qty}, ${detail.id_box_detail}, '${slocDisplay}', ${data.total_weight})">
// 									Edit
// 								</button>
// 								<button class="btn btn-danger ms-1" onclick="deleteItem()">Delete</button>

// 							</td>
// 						</tr>
// 					`);
//                 });

//                 // Tampilkan tombol "Add Material" setelah data Box berhasil diambil
//                 $('#addBtn').show();
//                 $('.card-body').show(); // Menampilkan data box
//             } else {
//                 console.error('Error:', data.message || 'No details available');
//                 $('#addBtn').hide(); // Sembunyikan tombol jika tidak ada data
//             }
//         },
//         error: function(xhr, ajaxOptions, thrownError) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Error',
//                 text: 'An error occurred while processing your request.'
//             });
//             $('#addBtn').hide(); // Sembunyikan tombol jika terjadi kesalahan
//         }
//     });
// }

function deleteItem(id_box_detail) {
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
                url: '<?= base_url('warehouse/delete_material_box'); ?>', // URL to the backend method
                type: 'POST',
                data: {
                    id_box_detail: id_box_detail // Send the id_box_detail to be deleted
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Material has been deleted.",
                            icon: "success"
                        });
                        getBox(); // Reload the box details to reflect the deletion
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting the material.'
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

function saveEdit() {
    var id_box_detail = $('#id_box_detail').val();
    var qty = $('#qty').val();
    var sloc = $('#sloc').val(); // Capture the sloc value
    var total_weight = $('#total_weight').val(); // Capture the total weight value

    // Validate that the fields are not empty before sending the request
    if (!id_box_detail || !qty || !sloc || !total_weight) {
        Swal.fire({
            icon: 'warning',
            title: 'Validation Error',
            text: 'Please fill in all fields before saving.'
        });
        return;
    }

    // Log the data being sent for debugging
    console.log('Sending data:', {
        id_box_detail,
        qty,
        sloc,
        total_weight
    });

    $.ajax({
        url: '<?php echo base_url('warehouse/save_cycle_count'); ?>',
        type: 'POST',
        data: {
            id_box_detail: id_box_detail,
            qty: qty,
            sloc: sloc, // Send sloc along with the other data
            total_weight: total_weight // Send total weight along with the other data
        },
        success: function(response) {
            // Check if the response is valid JSON
            try {
                var data = JSON.parse(response);
                if (data.status) {
                    Swal.fire({
                        title: "Success!",
                        text: "Material has been edited successfully.",
                        icon: "success"
                    }).then(function() {
                        $('#editModal').modal('hide'); // Close the modal
                        $('#qty').val('');
                        $('#id_box_detail').val('');
                        $('#sloc').val(''); // Clear the sloc field
                        $('#total_weight').val(''); // Clear the total weight field
                        getBox(); // Reload the box details
                    });
                } else {
                    // If the status is not success, display an error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message ||
                            'An error occurred while saving your changes.'
                    });
                }
            } catch (e) {
                // If the response is not valid JSON, log it and show an error
                console.error('Invalid JSON response:', response);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'The server returned an invalid response.'
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            // Log detailed error information and display an error message
            console.error('AJAX error:', thrownError, xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while saving your changes.'
            });
        }
    });
}
</script>