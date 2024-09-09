<style>
    .select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>
<section>
    <!-- GET USER -->
    <input type="text" id="user" name="user" value="<?=$name['username'];?>" hidden>
	<div class="row">
		<div class="card info-card" style="height: 2500px;">
			<div class="card-body">
                <?php if ($this->session->flashdata('Error') != '') { ?>
                    <?= $this->session->flashdata('Error'); ?>
                <?php } ?>
                <div class="row mt-3">
                    <div class="col-md">
                        <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home-justified" type="button" role="tab" aria-controls="home"
                                    aria-selected="true"><i class="bi bi-file-earmark-ruled-fill me-3"
                                        style="color: #012970"></i>BOM</button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#profile-justified" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false"><i
                                        class="bi bi-file-earmark-plus-fill me-2" style="color: #012970"></i> Material</button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#request-history" type="button" role="tab"
                                    aria-controls="profile" aria-selected="false"><i
                                        class="bx bxs-book-content me-2" style="color: #012970"></i> Request History</button>
                            </li>
                        </ul>
                        <div class="tab-content pt-2" id="myTabjustifiedContent">
                            <div class="tab-pane fade show active" id="home-justified" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="container mt-5">
                                    <div class="row justify-content-center gap-1" id="base-search">
                                        <div class="col-12 col-md-3 mb-3 mb-md-0 text-md-end">
                                            <label for="product_id" class="col-form-label"><b>Product FG ID</b></label>
                                        </div>
                                        <div class="col-12 col-md-5 mb-3 mb-md-0">
                                            <select id="product_id" class="form-select">
                                                <option selected>Choose Product FG</option>
                                                <?php foreach($boms as $bm): ?>
                                                    <option value="<?=$bm['Id_fg'];?>"><?=$bm['Id_fg'];?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-3">
                                            <button type="submit" class="btn btn-success w-100" onclick="getProduct()">Search</button>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-5">
                                <div class="row mt-3 ms-4">
                                    <div class="col-md">
                                        <div id="data"></div>
                                    </div>
                                </div>
                                <div class="row mt-5 ms-4" id="billofmaterial">
                                    <div class="col-md">
                                        <div id="data-table"></div>
                                    </div>
                                </div>                            
                            </div>
                            <div class="tab-pane fade" id="profile-justified" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
                                <div class="container mt-5 mb-5">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-md-3 mb-3 mb-md-0">
                                            <label for="material_id" class="col-form-label text-md-center"><b>Material Part No</b></label>
                                        </div>
                                        <div class="col-12 col-md-5 mb-3 mb-md-0">
                                            <select id="material_id" class="form-select">
                                                <option value="">Choose Materials</option>
                                                <?php foreach($materials as $mt): ?>
                                                    <option value="<?=$mt['Id_material'];?>"><?=$mt['Id_material'];?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <button class="btn btn-success w-100" id="btn-search" onclick="getMaterials()">Search</button>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-2 mb-3">
                                <div class="row mt-2 mb-3 mx-3" id="data-desc"></div>
                            </div>
                            <div class="tab-pane fade" id="request-history" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <table class="table datatable" id="request-history">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Production Plan No</th>
                                            <th>Product FG ID</th>
                                            <th>Product FG Description</th>
                                            <th>Request Date</th>
                                            <th>Reject Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $number=0; foreach($request_history as $rh): $number++?>
                                        <tr>
                                            <td><?=$number;?></td>
                                            <td><?=$rh['Production_plan'];?></td>
                                            <td><?=$rh['Id_fg'];?></td>
                                            <td><?=$rh['Fg_desc'];?></td>
                                            <td><?=$rh['Crtdt'];?></td>
                                            <td><?=$rh['reject_description'];?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</section>

<script src="<?=base_url('assets');?>/vendor/sweet-alert/sweet-alert.js"></script>
<script src="<?=base_url('assets');?>/vendor/jquery/jquery.min.js"></script>
<script src="<?=base_url('assets/');?>vendor/datatables/datatables.js"></script>
<script>
    // MENDAPATKAN PRODUCT BERDASARKAN ID
    function getProduct() {
        var productID = $('#product_id').val();
        $.ajax({
            url: '<?= base_url('production/getProduct'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                productID: productID
            },
            beforeSend: function(){
                var spinner =
                `
                <div class="spinner-container">
                    <div class="spinner-grow text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                `;
                $('#data').append(spinner);
            },
            success: function(res) {
                if (res.length > 0) {
                    $('#base-search').css('display', 'none');
                    var productId = res[0].Id_fg;
                    var productDescription = res[0].Fg_desc;

                    // Construct HTML content to append
                    var htmlContent = '';
                    htmlContent+=
                    `
                        <form method="POST" action="<?=base_url('production/getProductData');?>">
                            <input type="text" id="user" name="user" value="<?=$name['username'];?>" hidden>
                            <div class="container mt-5">
                                <div class="row mb-3">
                                    <label for="production_plan_date" class="col-12 col-md-4 col-form-label text-md-end"><b>Production Planning Date</b></label>
                                    <div class="col-12 col-md-3">
                                        <input type="date" class="form-control" id="production_plan_date" name="production_plan_date" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="productId" class="col-12 col-md-4 col-form-label text-md-end"><b>Product ID</b></label>
                                    <div class="col-12 col-md-3">
                                        <input type="text" class="form-control" value="${productId}" name="productId" id="productId" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="productDescription" class="col-12 col-md-4 col-form-label text-md-end"><b>Product Description</b></label>
                                    <div class="col-12 col-md-6">
                                        <input type="text" class="form-control" id="productDescription" name="productDescription" value="${productDescription}" readonly>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="qty" class="col-12 col-md-4 col-form-label text-md-end"><b>Qty Production Planning</b></label>
                                    <div class="col-12 col-md-2">
                                        <input type="number" class="form-control" id="qty" name ="qty" min="1" required>
                                    </div>
                                    <div class="col-12 col-md-3 mt-3 mt-md-0">
                                        <button type="submit" class="btn btn-primary w-100" style="background-color: #4154f1">Calculate</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    `;

                    // Append the HTML content to the div with id "data"
                    $('#data').empty().append(htmlContent);
                } else {
                    // Handle case when product is not found
                    $('#data-modal').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> Product ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
            }
        });
    }
    
    $(document).ready(function () {
        $('#material_id').select2({
            width: '100%'
        });
        $('#product_id').select2({
            dropdownParent: $('#base-search') 
        });
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
            url: '<?= base_url('production/getMaterial'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                material_id
            },
            success: function(res) {
                if (res.result_material.length > 0) {
                    $('#btn-search').prop('disabled', true);
                    // console.log(res);
                    
                    var loop_production_plan = '';
                    for(var a = 0; a < res.result_production_plan.length; a++){
                        loop_production_plan +=
                        `
                            <option value="${res.result_production_plan[a].Production_plan}">${res.result_production_plan[a].Production_plan} | ${res.result_production_plan[a].Fg_desc}</option>
                        `;
                    }

                    var htmlDesc = 
                    `
                        <div class="container mt-5">
                            <div class="row mb-3">
                                <label for="production_plan" class="col-12 col-md-4 col-form-label text-md-end"><b>Material Need</b></label>
                                <div class="col-12 col-md-8">
                                    <select id="production_plan" class="form-select">
                                        <option value="">Choose Production Plan</option>
                                        ${loop_production_plan}
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="Id_material" class="col-12 col-md-4 col-form-label text-md-end"><b>Material ID</b></label>
                                <div class="col-12 col-md-3">
                                    <input type="text" class="form-control" value="${res.result_material[0].Id_material}" name="Id_material" id="Id_material" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="Material_desc" class="col-12 col-md-4 col-form-label text-md-end"><b>Material Description</b></label>
                                <div class="col-12 col-md-8">
                                    <input type="text" class="form-control" id="Material_desc" name="Material_desc" value="${res.result_material[0].Material_desc}" readonly>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="material_need" class="col-12 col-md-4 col-form-label text-md-end"><b>Material Need</b></label>
                                <div class="col-12 col-md-2">
                                    <input type="text" class="form-control" id="material_need" name="material_need" min="1" required placeholder="0.5">
                                </div>
                                <div class="col-12 col-md-2 mt-3 mt-md-0">
                                    <input type="text" class="form-control" id="uom" name="uom" value="${res.result_material[0].Uom}" readonly>
                                </div>
                                <div class="col-12 col-md-3 mt-3 mt-md-0">
                                    <button type="button" class="btn btn-primary w-100" id="calculate-material" onclick="getCalculateMaterial()" style="background-color: #4154f1">Submit</button>
                                </div>
                            </div>
                        </div>

                    `;
                    
                    $('#data-desc').empty().append(htmlDesc);
                    $('#production_plan').select2();
                    $('#material_id').prop('disabled', true);
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
        var production_plan = $('#production_plan').val();
        var user = $('#user').val();

        if(material_need.length < 1 || production_plan.length < 1){
            return Swal.fire({
                title: 'Error!',
                html: `<b>Material need</b> or <b>Production Plan</b> is empty`,
                icon: 'error',
                confirmButtonText: 'Close'
            });
        }

        $.ajax({
            url: '<?= base_url('production/getCalculateMaterial'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                material_id, material_desc, material_need, user, production_plan
            },
            success: function(res) {
                Swal.fire({
                    title: "Success",
                    text: "Material Qty have been requested",
                    icon: "success"
                }).then(() => {
                    window.location.href = '<?=base_url('production/');?>';
                });

            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    title: "Error",
                    text: "Material Qty haven't been failed requested",
                    icon: "error"
                }).then(() => {
                    window.location.href = '<?=base_url('production/');?>';
                });
            }
        });
    }
</script>