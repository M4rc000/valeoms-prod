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
                        </ul>
                        <div class="tab-content pt-2" id="myTabjustifiedContent">
                            <div class="tab-pane fade show active" id="home-justified" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="row justify-content-center mt-5 gap-1" id="base-search">
                                    <label for="product_id" class="col-sm-3 col-form-label text-end"><b>Product ID</b></label>
                                    <div class="col-sm-5">
                                        <select id="product_id" class="form-select">
                                            <option selected>Choose Product FG</option>
                                            <?php foreach($boms as $bm): ?>
                                                <option value="<?=$bm['Id_fg'];?>"><?=$bm['Id_fg'];?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-success" onclick="getProduct()">Search</button>
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
                                <div class="row mt-5 mb-5 justify-content-center">
                                    <label for="material_id" class="col-sm-3 col-form-label text-end"><b>Material Part No</b></label>
                                    <div class="col-5">
                                        <select id="material_id" class="form-select">
                                            <option selected>Choose Materials</option>
                                            <?php foreach($materials as $mt): ?>
                                                <option value="<?=$mt['Id_material'];?>"><?=$mt['Id_material'];?></option>
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
                    </div>
                </div>
			</div>
		</div>
	</div>
</section>

<!-- MATERIAL REQUEST MODAL -->
<div class="modal fade" id="addMaterialRequest" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="row justify-content-center" style="z-index: 999">
                <div class="col-md-12 text-center" id="datas-modals">
                </div>
            </div>
            <form>
                <div class="modal-header">
                    <h5 class="modal-title">Add Material Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- GET USER -->
                    <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
                    <!-- GET PRODUC PLAN ID -->
                    <input type="text" class="form-control" id="prod_plan_id" name="prod_plan_id" value="" hidden>
                    <div class="row ps-2 mb-3 px-2">
                        <div class="col-md-2">
                            <label for="material_id" class="form-label"><b>Material Part No</b></label>
                            <input type="text" class="form-control" id="material_id" name="material_id" readonly>
                        </div>
                        <div class="col-4">
                            <label for="material_desc" class="form-label"><b>Material Part Name</b></label>
                            <input type="text" class="form-control" id="material_desc" name="material_desc" readonly required>
                        </div>
                        <div class="col-2">
                            <label for="material_need" class="form-label"><b>Material Need</b></label>
                            <input type="text" class="form-control" id="material_need" name="material_need" readonly>
                        </div>
                        <div class="col-2">
                            <label for="stock_on_hand" class="form-label"><b class="px-2">Stock on hand</b> <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Accumulation Quantity on storage"></i></label>
                            <input type="text" class="form-control" id="stock_on_hand" name="stock_on_hand" readonly>
                        </div>
                        <div class="col-2">
                            <label for="uom" class="form-label"><b>Uom</b></label>
                            <input type="text" class="form-control" id="uom" name="uom" readonly>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row mt-2 ps-2 px-2">
                        <div class="col-md">
                            <button type="button" class="btn btn-success" id="plus-row">
                                <i class="bi bi-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-10" id="dynamic-rows-container">
                             <!-- Dynamic rows will be appended here -->
                         </div>
                         <div class="col-md-2">
                            <label for="total_qty_get" class="form-label"><b class="px-2">Qty Request</b> <i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Accumulation Quantity unpack"></i></label>
                            <input type="text" class="form-control" id="total_qty_get" name="total_qty_get" readonly>
                         </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="btn-close-modal" data-bs-dismiss="modal" disabled>Close</button>
                    <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                </div>
            </form>
        </div>
    </div>
</div>

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
                        <div class="row mt-5">
                            <label for="productId" class="col-sm-4 col-form-label"><b>Product ID</b></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" value="${productId}" name="productId" id="productId" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="productDescription" class="col-sm-4 col-form-label"><b>Product Description</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="productDescription" name="productDescription" value="${productDescription}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="qty" class="col-sm-4 col-form-label"><b>Qty Production Planning</b></label>
                            <div class="col-sm-2">
                                <input type="number" class="form-control" id="qty" name ="qty" min="1" required>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-primary" id="btn-calculate" onclick="calculateData()" style="background-color: #4154f1">Calculate</button>
                            </div>
                        </div>
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

    // MENDAPATKAN DATA BOM
    function calculateData(){
        var productID = $('#product_id').val();
        var productDesc = $('#productDescription').val();
        var qty = $('#qty').val();
        var user = $('#user').val();

        if(qty == 0 || qty == ''){
            $('#data').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> Qty can\'t empty<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            return false;
        }

		$.ajax({
			url: '<?= base_url('production/getProductData'); ?>',
			type: 'post',
			dataType: 'json',
			data: {
				productID, productDesc, qty, user
			},
			success: function(res) {
                console.log(res);
                $('#btn-calculate').prop('disabled', true);

                var row = '';
                for (let number = 0; number < res.length; number++) {
                    row +=
                    `
                        <tr data-id="${res[number].Id_material}" data-desc="${res[number].Material_desc}" data-qty="${res[number].Material_need}" data-uom="${res[number].Uom}">
                            <th scope="row">${number + 1}</th>
                            <td class="text-center">${res[number].Production_plan}</td>
                            <td>${res[number].Id_material}</td>
                            <td>${res[number].Material_desc}</td>
                            <td class="text-center">${res[number].Material_need}</td>
                            <td class="text-center">${res[number].Material_need}</td>
                            <td class="text-center">${res[number].Uom}</td>
                            <td class="text-center">
                                <a href="#" class="edit-material-request" data-bs-toggle="modal" data-bs-target="#addMaterialRequest">
                                    <span class="badge bg-warning"><i class="bx bx-pencil"></i></span>
                                </a>
                            </td>
                        </tr>`;
                    }

                var title = '';
                title+=
                `
                    <div class="col-md mb-2">
                        <span>
                            <b>
                                BILL OF MATERIAL
                            </b>
                        </span>
                    </div>
                    <hr style="border: 1.5px solid black">
                    <input type="text" class="form-control" hidden id="production_plan_request" value="${res[0].Production_plan}">
                `;
                
                var tableBom = '';
                tableBom += 
                `
                    <table class="table table-bordered" id="tbl-bom">
                        <thead style="font-size: 14px">
                            <tr>
                                <th scope="col" rowspan="2" class="text-center">#</th>
                                <th scope="col" rowspan="2" class="text-center">Production Plan</th>
                                <th scope="col" rowspan="2" class="text-center">Material Part No</th>
                                <th scope="col" rowspan="2" class="text-center">Material Part Name</th>
                                <th scope="col" rowspan="2" class="text-center">Material Need</th>
                                <th scope="col" rowspan="2" class="text-center">Qty</th>
                                <th scope="col" rowspan="2" class="text-center">Uom</th>
                                <th scope="col" rowspan="2" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13.5px" id="material-table-body">
                            ${row}
                        </tbody>
                    </table>
                    <div class="row mt-2">
                        <div class="col-md-12 text-end">
                            <a href=""><Button type="submit" class="btn btn-success">Save</Button></a>
                        </div>
                    </div>
                `;  
                                
                $('#billofmaterial').empty().append(title);
                $('#billofmaterial').append(tableBom);

                // ADDING DATA PROD PLAN
                $('#prod_plan_id').val(res[0].Production_plan);

			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
    }
    
    $(document).ready(function () {
        $('#material_id').select2();
        $('#product_id').select2();
        let rowIndex = 1;
        let isFloatUom = false;
        var materialData = [];

        // Helper function to format numbers without trailing zeros
        function formatNumber(num) {
            if (num === undefined || num === null || isNaN(num)) {
                return 0;
            }
            return num % 1 === 0 ? num.toString() : num.toFixed(2).replace(/\.?0+$/, '');
        }

        // Function to handle form submission
        function SubmitMaterialReq(res) {
            $("form").on("submit", function (event) {
                event.preventDefault();
            });
        }

        // Function to add a new row
        function addNewRow(res) {
            const currentRowIndex = rowIndex;
            const rowHtml = `
                <div class="row rows" id="rows-${currentRowIndex}">
                    <div class="col-md-3">
                        <label for="sloc-${currentRowIndex}" class="form-label"><b>SLoc</b></label>
                        <select id="sloc-${currentRowIndex}" name="sloc[${currentRowIndex}]" class="form-select">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="box_no-${currentRowIndex}" class="form-label"><b>Box</b></label>
                        <select id="box_no-${currentRowIndex}" name="box_no[${currentRowIndex}]" class="form-select">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="total_qty-${currentRowIndex}" class="form-label"><b>Qty on hand</b></label>
                        <input type="text" class="form-control" id="total_qty-${currentRowIndex}" name="total_qty[${currentRowIndex}]" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="qty_unpack-${currentRowIndex}" class="form-label"><b>Unpack</b></label>
                        <input type="number" min="${isFloatUom ? '0.1' : '1'}" step="${isFloatUom ? '0.1' : '1'}" class="form-control" id="qty_unpack-${currentRowIndex}" name="qty_unpack[${currentRowIndex}]">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary submit-row-btn" id="submit-btn-${currentRowIndex}" type="button" style="margin-top: 2rem;">
                            <i class="bx bx-check-circle"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#dynamic-rows-container').append(rowHtml);

            $(`#sloc-${currentRowIndex}`).select2();
            $(`#box_no-${currentRowIndex}`).select2();

            // Populate the SLoc dropdown
            let slocOptions = '<option value="">Choose SLoc</option>';
            res.forEach((box) => {
                slocOptions += `<option value="${box.sloc}">${box.SLoc}</option>`;
            });

            $(`#sloc-${currentRowIndex}`).html(slocOptions);

            (function(rowIdx) {
                $(`#sloc-${rowIdx}`).on('change', function() {
                    const selectedSloc = $(this).val();
                    let boxOptions = '<option value="">Choose Box</option>';

                    $(`#box_no-${rowIdx}`).empty();

                    res.forEach((box) => {
                        if (box.sloc == selectedSloc) {
                            boxOptions += `<option value="${box.id_box}">${box.no_box}</option>`;
                        }
                    });

                    $(`#box_no-${rowIdx}`).html(boxOptions).trigger('change');
                });

                // Event handler for when a box_no is selected
                $(`#box_no-${rowIdx}`).on('change', function() {
                    const selectedBoxId = $(this).val();
                    const selectedBox = res.find(box => box.id_box == selectedBoxId);
                    if (selectedBox) {
                        $(`#total_qty-${rowIdx}`).val(formatNumber(selectedBox.total_qty_real));
                    }
                    // console.log("Select box: " + rowIdx);
                });

                // Event handler for when the submit button is clicked
                $(document).off('click', `#submit-btn-${rowIdx}`).on('click', `#submit-btn-${rowIdx}`, function() {
                    materialData = [];

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

                    const selectedBox = res.find(box => box.id_box == box_no);
                    if (selectedBox) {
                        const newTotalQty = selectedBox.total_qty_real - qty_unpack;
                        var id_list_storage = selectedBox.list_storage_id;

                        // Update materialData
                        materialData.push({
                            id_material: selectedBox.product_id,
                            material_desc: selectedBox.material_desc,
                            sloc: sloc,
                            box_no: box_no,
                            qty_unpack: qty_unpack,
                        });

                        var user = $('#user').val();
                        var Production_plan = $('#production_plan_request').val();

                        // Update total_qty_real in the database
                        $.ajax({
                            url: '<?= base_url('production/updateBoxQuantity'); ?>',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                id_box: selectedBox.id_box,
                                total_qty_real: newTotalQty,
                                materialData: materialData,
                                Production_plan: Production_plan,
                                user: user,
                                id_list_storage: id_list_storage
                            },
                            success: function(updateRes) {
                                if (updateRes.success) {
                                    // Calculate new stock on hand
                                    let stock_on_hand_new = 0;
                                    res.forEach((box) => {
                                        if (box.id_box == selectedBox.id_box) {
                                            box.total_qty_real = newTotalQty;
                                        }
                                        stock_on_hand_new += parseInt(box.total_qty_real);
                                    });

                                    // Update stock on hand in UI
                                    $('#stock_on_hand').val(formatNumber(stock_on_hand_new));

                                    // Disable the fields after submission
                                    $(`#sloc-${rowIdx}`).prop('disabled', true);
                                    $(`#box_no-${rowIdx}`).prop('disabled', true);
                                    $(`#qty_unpack-${rowIdx}`).prop('disabled', true);

                                    let currentTotalQtyGet = parseInt($('#total_qty_get').val()) || 0;
                                    $('#total_qty_get').val(formatNumber(currentTotalQtyGet + parseFloat(qty_unpack)));
                                    
                                    var material_need = $('#material_need').val();
                                    var total_qty_get = $('#total_qty_get').val();

                                    // CHECK IF MATERIAL NEED IS SAME WITH TOTAL REQUEST unpack
                                    if(material_need == total_qty_get){
                                        $('#btn-close-modal').prop('disabled', false);
                                    }

                                    // console.log(materialData);
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

        // Handle click event for adding new rows
        $(document).on('click', '.edit-material-request', function () {
            var $row = $(this).closest('tr');
            var materialId = $row.data('id');
            var materialDesc = $row.data('desc');
            var materialNeed = $row.data('qty');
            var uom = $row.data('uom');
            $('#material_id').val(materialId);
            $('#material_desc').val(materialDesc);
            $('#material_need').val(materialNeed);
            $('#uom').val(uom);

            // Check if Uom is not "PC"
            isFloatUom = (uom !== "PC");

            // Reset these fields when opening the modal
            $('#stock_on_hand').val('');
            $('#total_qty_get').val(''); // Reset total quantity get

            $.ajax({
                url: '<?= base_url('production/getSlocStorage'); ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    materialId: materialId
                },
                success: function (res) {
                    if (res) {
                        SubmitMaterialReq(res);
                        // console.log(res);
                        // ADD INPUT STOCK ON HAND
                        var stock_on_hand = 0;
                        for (let i = 0; i < res.length; i++) {
                            stock_on_hand += parseFloat(res[i].total_qty_real != res[i].total_qty ? res[i].total_qty_real : res[i].total_qty);
                        }
                        $('#stock_on_hand').val(formatNumber(stock_on_hand));

                        // Ensure event handler for adding new rows is only added once
                        $('#plus-row').off('click').on('click', function () {
                            addNewRow(res);
                        });

                        // Ensure modal hidden event is handled properly
                        $('#addMaterialRequest').off('hidden.bs.modal').on('hidden.bs.modal', function () {
                            $('#dynamic-rows-container').empty(); // Clear all dynamic rows when the modal is hidden
                            rowIndex = 0; // Reset rowIndex when the modal is hidden
                        });
                    } else {
                        $('#stock_on_hand').val(0);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error('AJAX Error:', thrownError);
                }
            });
        });
    
        SubmitMaterialReq();
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
                    console.log(res);
                    
                    var loop_production_plan = '';
                    for(var a = 0; a < res.result_production_plan.length; a++){
                        loop_production_plan +=
                        `
                            <option value="${res.result_production_plan[a].Production_plan}">${res.result_production_plan[a].Production_plan} | ${res.result_production_plan[a].Fg_desc}</option>
                        `;
                    }

                    var htmlDesc = 
                    `
                        <div class="row mt-5">
                            <label for="production_plan" class="col-sm-4 col-form-label"><b>Material Need</b></label>
                            <div class="col">
                                <select id="production_plan" class="form-select">
                                    <option selected>Choose Production Plan</option>
                                    ${loop_production_plan}
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="Id_material" class="col-sm-4 col-form-label"><b>Material ID</b></label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" value="${res.result_material[0].Id_material}" name="Id_material" id="Id_material" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="Material_desc" class="col-sm-4 col-form-label"><b>Material Description</b></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="Material_desc" name="Material_desc" value="${res.result_material[0].Material_desc}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="material_need" class="col-sm-4 col-form-label"><b>Material Need</b></label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="material_need" name ="material_need" min="1" required placeholder="0.5">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="uom" name ="uom" value="${res.result_material[0].Uom}" readonly>
                            </div>
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-primary" id="calculate-material" onclick="getCalculateMaterial()" style="background-color: #4154f1">Submit</button>
                            </div>
                        </div>
                    `;
                    
                    $('#data-desc').empty().append(htmlDesc);
                    $('#production_plan').select2();
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