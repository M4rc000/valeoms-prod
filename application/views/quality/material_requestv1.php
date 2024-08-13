<style>
    .select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
    .table-container {
        width: 100%;
    }

    .table-container table {
        width: 100%;
        table-layout: fixed;
    }

    .table-container td {
        vertical-align: middle;
        text-align: center;
    }

    .table-container select,
    .table-container input {
        width: 100%;
        box-sizing: border-box;
    }

    .fixed-width {
        width: 150px;
    }
</style>
<section>
    <div class="card">
        <div class="card-body" style="height: 900px">
            <!-- GET USER -->
            <input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
            <div class="row mt-5 mb-5 justify-content-center">
                <label for="material_id" class="col-sm-3 col-form-label text-end"><b>Material Part No</b></label>
                <div class="col-sm-5">
                    <select id="material_id" class="form-select">
                        <option selected>Choose Materials</option>
                        <?php foreach($materials as $mt): ?>
                            <option value="<?=$mt['Id_material'];?>"><?=$mt['Id_material'];?> | <?=$mt['Material_desc'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" id="btn-search" onclick="getMaterials()">Search</button>
                </div>
            </div>
            <hr class="mt-2 mb-3">
            <div class="row mt-2 mb-3 mx-3" id="data-desc"></div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#material_id').select2();
    });
    
    let rowIndex = 1;
    let materialData = [];

    function formatNumber(num) {
        num = Number(num); 
        if (isNaN(num)) {
            return '0'; 
        }
        return num % 1 === 0 ? num.toString() : num.toFixed(2).replace(/\.?0+$/, '');
    }

    function AddNewTrRow(res) {
        const currentRowIndex = rowIndex;

        const rowHtml = `
            <tr class="justify-content-center" id="rows-${currentRowIndex}">
                <td class="py-3 text-center align-middle" style="width: 50px;"><b>${currentRowIndex}</b></td>
                <td class="text-center align-middle">
                    <select id="sloc-${currentRowIndex}" name="sloc[${currentRowIndex}]" class="form-select fixed-width">
                    </select>
                </td>
                <td class="text-center align-middle">
                    <select id="box_no-${currentRowIndex}" name="box_no[${currentRowIndex}]" class="form-select fixed-width">
                        <option value="">Choose Box</option>
                    </select>
                </td>
                <td class="text-center align-middle">
                    <input type="text" class="form-control fixed-width text-center" id="total_qty-${currentRowIndex}" name="total_qty[${currentRowIndex}]" readonly>
                </td>
                <td class="text-center align-middle">
                    <input type="text" class="form-control fixed-width" id="qty_unpack-${currentRowIndex}" name="qty_unpack[${currentRowIndex}]">
                </td>
                <td class="text-center align-middle" style="width: 100px;">
                    <button class="btn btn-primary submit-row-btn" id="submit-btn-${currentRowIndex}" type="button">
                        <i class="bx bx-check-circle"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#dynamic-rows-container').append(rowHtml);

        $(`#sloc-${currentRowIndex}`).select2();
        $(`#box_no-${currentRowIndex}`).select2();

        // Populate the SLoc dropdown
        let slocOptions = '<option value="">Choose SLoc</option>';
        res.Box_result.forEach((box) => {
            slocOptions += `<option value="${box.sloc}">${box.SLoc}</option>`;
        });

        $(`#sloc-${currentRowIndex}`).html(slocOptions);

        (function(rowIdx) {
            $(`#sloc-${rowIdx}`).on('change', function() {
                const selectedSloc = $(this).val();
                let boxOptions = '<option value="">Choose Box</option>';

                $(`#box_no-${rowIdx}`).empty();

                res.Box_result.forEach((box) => {
                    if (box.sloc == selectedSloc) {
                        boxOptions += `<option value="${box.id_box}">${box.no_box}</option>`;
                    }
                });

                $(`#box_no-${rowIdx}`).html(boxOptions).trigger('change');
            });

            // Event handler for when a box_no is selected
            $(`#box_no-${rowIdx}`).on('change', function() {
                const selectedBoxId = $(this).val();
                const selectedBox = res.Box_result.find(box => box.id_box == selectedBoxId);
                if (selectedBox) {
                    $(`#total_qty-${rowIdx}`).val(formatNumber(selectedBox.total_qty_real));
                }
            });

            // Event handler for when the submit button is clicked
            $(`#submit-btn-${rowIdx}`).on('click', function() {
                const sloc = $(`#sloc-${rowIdx}`).val();
                const box_no = $(`#box_no-${rowIdx}`).val();
                const qty_unpack = $(`#qty_unpack-${rowIdx}`).val();

                if (!qty_unpack) {
                    return Swal.fire({
                        title: 'Error!',
                        html: `<b>Qty unpack</b> is empty`,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }

                const selectedBox = res.Box_result.find(box => box.id_box == box_no);
                if (selectedBox) {
                    const newTotalQty = selectedBox.total_qty_real - parseFloat(qty_unpack);
                    var id_list_storage = selectedBox.id_list_storage;
                    
                    // Update materialData
                    materialData.push({
                        sloc: sloc,
                        box_no: box_no,
                        qty_unpack: qty_unpack,
                    });
                    
                    var user = $('#user').val();
                    
                    // Update total_qty_real in the database
                    $.ajax({
                        url: '<?= base_url('quality/updateBoxQuantity'); ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id_box: selectedBox.id_box,
                            total_qty_real: newTotalQty,
                            materialData: materialData,
                            Id_request: res.Request_result[0].Id_request,
                            user: user,
                            id_list_storage: id_list_storage
                        },
                        success: function(updateRes) {
                            if (updateRes.success) {
                                // Calculate new stock on hand
                                let stock_on_hand_new = 0;
                                res.Box_result.forEach((box) => {
                                    if (box.id_box == selectedBox.id_box) {
                                        box.total_qty_real = newTotalQty;
                                    }
                                    stock_on_hand_new += parseFloat(box.total_qty_real);
                                });

                                // Update stock on hand in UI
                                $('#stock_on_hand').val(formatNumber(stock_on_hand_new));

                                // Disable the fields after submission
                                $(`#sloc-${rowIdx}`).prop('disabled', true);
                                $(`#box_no-${rowIdx}`).prop('disabled', true);
                                $(`#qty_unpack-${rowIdx}`).prop('disabled', true);

                                let currentTotalQtyGet = parseFloat($('#total_qty_get').val()) || 0;
                                $('#total_qty_get').val(formatNumber(currentTotalQtyGet + parseFloat(qty_unpack)));
                                
                                if($('#material_need').val() == $('#total_qty_get').val()){
                                    $('#save-btn').prop('disabled', false);
                                }
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    html: `<b>Failed to update box quantity in the database.</b>`,
                                    icon: 'error',
                                    confirmButtonText: 'Close'
                                });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            console.error(xhr.statusText);
                        }
                    });
                }
            });
        })(currentRowIndex); // Pass the current row index to the IIFE

        rowIndex += 1;
    }

    function getMaterials() {
        var material_id = $('#material_id').val();
        $.ajax({
            url: '<?= base_url('quality/getMaterial'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                material_id
            },
            success: function(res) {
                if (res.length > 0) {
                    $('#btn-search').prop('disabled', true);    
                    var htmlDesc = 
                    `
                        <div class="row mt-5">
                            <label for="Id_material" class="col-sm-4 col-form-label"><b>Material ID</b></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" value="${res[0].Id_material}" name="Id_material" id="Id_material" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="Material_desc" class="col-sm-4 col-form-label"><b>Material Description</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="Material_desc" name="Material_desc" value="${res[0].Material_desc}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="material_need" class="col-sm-4 col-form-label"><b>Material Need</b></label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="material_need" name ="material_need" min="1" required placeholder="0.5">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="uom" name ="uom" value="${res[0].Uom}" readonly>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-primary" id="calculate-material" onclick="getCalculateMaterial()" style="background-color: #4154f1">Submit</button>
                            </div>
                        </div>
                    `;
                    
                    $('#data-desc').empty().append(htmlDesc);
                } 
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
            }
        });
    }

    function getCalculateMaterial() {
        var material_id = $('#Id_material').val();
        var material_desc = $('#Material_desc').val();
        var material_need = $('#material_need').val();
        var material_uom = $('#uom').val();
        var user = $('#user').val();

        if(material_need.length < 1){
            return Swal.fire({
                title: 'Error!',
                html: `<b>Material need</b> is empty`,
                icon: 'error',
                confirmButtonText: 'Close'
            });
        }

        $.ajax({
            url: '<?= base_url('quality/getCalculateMaterial'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                material_id, material_desc, material_need, material_uom, user
            },
            success: function(res) {
                if (res) {
                    var stock_on_hand = 0; 
                    for(var i = 0; i < res.Box_result.length; i++){
                        stock_on_hand += parseFloat(res.Box_result[i].total_qty);
                    }

                    var htmlContent = 
                    `
                        <div class="row mt-5">
                            <label for="Id_material" class="col-sm-4 col-form-label"><b>Material ID</b></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" value="${res.Request_result[0].Id_material}" name="Id_material" id="Id_material" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="Material_desc" class="col-sm-4 col-form-label"><b>Material Description</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="Material_desc" name="Material_desc" value="${res.Request_result[0].Material_desc}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="material_need" class="col-sm-4 col-form-label"><b>Material Need</b></label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control text-center" id="material_need" name ="material_need" value="${formatNumber(res.Request_result[0].Material_need)}" required readonly>
                            </div>
                            <div class="col-sm-1">
                                <input type="text" class="form-control text-center" id="uom" name ="uom" value="${res.Request_result[0].Uom}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="stock_on_hand" class="col-sm-4 col-form-label">
                                <b style="margin-right: 20px">Stock on hand</b>
                                <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Accumulation Quantity on storage"></i>
                            </label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control text-center" id="stock_on_hand" name ="stock_on_hand" required value="${formatNumber(stock_on_hand)}" readonly>
                            </div>
                            <div class="col-sm-1">
                                <input type="text" class="form-control text-center" id="uom" name ="uom" value="${res.Request_result[0].Uom}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2 mb-4">
                            <label for="total_qty_get" class="col-form-label col-md-4"><b style="margin-right: 20px">Qty Request</b> <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Accumulation Quantity unpack"></i></label>
                            <div class="col-md-2">
                                <input type="text" class="form-control text-center" id="total_qty_get" name="total_qty_get" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-5 ps-2 px-2">
                            <div class="col-md">
                                <button type="button" class="btn btn-success" id="plus-row">
                                    <i class="bi bi-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md">
                                <div class="table-responsive">
                                    <table id="bomTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">SLoc</th>
                                                <th class="text-center">Box No</th>
                                                <th class="text-center">Stock <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Quantity on Box"></i></th>
                                                <th class="text-center">Qty unpack</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="dynamic-rows-container">
                                            <!-- Dynamic rows will be appended here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12 text-end">
                                <a href="">
                                    <Button class="btn btn-success" id="save-btn" disabled>Save</Button>
                                </a>
                            </div>
                        </div>
                    `;
                    
                    $('#data-desc').empty().append(htmlContent);

                    $(document).on('click', '#plus-row', function() {
                        AddNewTrRow(res);
                    });
                } else {
                    // Handle case when BOX ID is not found
                    $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
            }
        });
    }
</script>