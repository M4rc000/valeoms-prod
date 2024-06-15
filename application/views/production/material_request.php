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
                <div class="row justify-content-center mt-5 gap-1">
                    <label for="product_id" class="col-sm-3 col-form-label"><b>Product ID</b></label>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


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
            success: function(res) {
                if (res.length > 0) {
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
                                <button type="button" class="btn btn-primary" onclick="calculateData()" style="background-color: #4154f1">Calculate</button>
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
                var row = '';
                for (let number = 0; number < res.length; number++) {
                    row +=
                    `
                        <tr data-id="${res[number].Id_material}" data-desc="${res[number].Material_desc}" data-qty="${res[number].Material_need}" data-uom="${res[number].Uom}">
                            <th scope="row">${number + 1}</th>
                            <td>${res[number].Id_material}</td>
                            <td>${res[number].Material_desc}</td>
                            <td class="text-center">${res[number].Material_need}</td>
                            <td class="text-center">${res[number].Uom}</td>
                            <td class="text-center">
                                <a href="#" class="edit-material-request" data-bs-toggle="modal" data-bs-target="#addMaterialRequest">
                                    <span class="badge bg-warning"><i class="bx bx-pencil"></i></span>
                                </a>
                            </td>
                            <td class="text-center">
                                <input type="checkbox" name="" id="" ${res[number].status == 1 ? 'checked' : ''}>
                            </td>
                        </tr>
                    `;
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
                `;
                
                var tableBom = '';
                tableBom += 
                `
                    <table class="table table-bordered" id="tbl-bom">
                        <thead style="font-size: 14px">
                            <tr>
                                <th scope="col" rowspan="2" class="text-center">#</th>
                                <th scope="col" rowspan="2" class="text-center">Material Part No</th>
                                <th scope="col" rowspan="2" class="text-center">Material Part Name</th>
                                <th scope="col" rowspan="2" class="text-center">Material Need</th>
                                <th scope="col" rowspan="2" class="text-center">Uom</th>
                                <th scope="col" rowspan="2" class="text-center">Action</th>
                                <th scope="col" rowspan="2" class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 13.5px" id="material-table-body">
                            ${row}
                        </tbody>
                    </table>
                `;  

                $('#billofmaterial').empty().append(title);
                $('#billofmaterial').append(tableBom);
                // new DataTable('#tbl-bom');

                // ADDING DATA PROD PLAN
                $('#prod_plan_id').val(res[0].Production_plan);

			},
			error: function(xhr, ajaxOptions, thrownError) {
				console.log(thrownError);
			}
		});
    }
    
    // LAUNCH MODAL
    // $(document).on('click', '.edit-material-request', function () {
    //     var $row = $(this).closest('tr');
    //     var materialId = $row.data('id');
    //     var materialDesc = $row.data('desc');
    //     var materialNeed = $row.data('qty');
    //     var uom = $row.data('uom');

    //     $('#material_id').val(materialId);
    //     $('#material_desc').val(materialDesc);
    //     $('#material_need').val(materialNeed);
    //     $('#uom').val(uom);

    //     $('#addMaterialRequest').on('shown.bs.modal', function () {
    //         $('#sloc').select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });
    //         $('#box_no').select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });
    //     });

    //     $.ajax({
    //         url: '<?= base_url('production/getSlocStorage'); ?>',
    //         type: 'post',
    //         dataType: 'json',
    //         data: {
    //             materialId: materialId
    //         },
    //         success: function(res) {
    //             if (res && res.length > 0) {

    //                 // ADD INPUT STOCK ON HAND
    //                 var stock_on_hand = 0;
    //                 for (var i = 0; i < res.length; i++) {
    //                     stock_on_hand += parseInt(res[i].total_qty);
    //                 }
    //                 $('#stock_on_hand').val(stock_on_hand);
                    
    //                 // ADD OPTION SLOC
    //                 $('#sloc').empty().append('<option selected>Choose SLoc</option>');
    //                 for (var i = 0; i < res.length; i++) {
    //                     var sloc = res[i].sloc;
    //                     $('#sloc').append('<option value="' + sloc + '">' + sloc + '</option>');
    //                 }

    //                 // ADD OPTION BOX NO
    //                 $('#box_no').empty().append('<option selected>Choose Box No</option>');
    //                 for (var i = 0; i < res.length; i++) {
    //                     var box = res[i].no_box;
    //                     $('#box_no').append('<option value="' + box + '" data-total_qty="' + res[i].total_qty + '">' + box + '</option>');
    //                 }

    //                 // Update total_qty based on selected SLoc
    //                 $('#sloc').on('change', function () {
    //                     var selectedOption = $(this).find('option:selected');
    //                     var selectedTotalQty = selectedOption.data('total_qty');
    //                     $('#total_qty').val(selectedTotalQty);
    //                 });

    //                 // Update total_qty based on selected Box No
    //                 $('#box_no').on('change', function () {
    //                     var selectedOption = $(this).find('option:selected');
    //                     var selectedTotalQty = selectedOption.data('total_qty');
    //                     $('#total_qty').val(selectedTotalQty);
    //                 });

    //                 // Set initial total_qty based on the first SLoc in the list if needed
    //                 var initialTotalQty = $('#sloc').find('option:selected').data('total_qty');
    //                 $('#total_qty').val(initialTotalQty);
    //             } else {
    //                 $('#stock_on_hand').val(0);
    //             }
    //         },
    //         error: function(xhr, ajaxOptions, thrownError) {
    //             console.error('AJAX Error:', thrownError); // Log any errors
    //         }
    //     });
    // });
    // $(document).on('click', '.edit-material-request', function () {
    //     var rowIndex = 0; // Declare and reset rowIndex inside the click event

    //     var $row = $(this).closest('tr');
    //     var materialId = $row.data('id');
    //     var materialDesc = $row.data('desc');
    //     var materialNeed = $row.data('qty');
    //     var uom = $row.data('uom');

    //     $('#material_id').val(materialId);
    //     $('#material_desc').val(materialDesc);
    //     $('#material_need').val(materialNeed);
    //     $('#uom').val(uom);

    //     $.ajax({
    //         url: '<?= base_url('production/getSlocStorage'); ?>',
    //         type: 'post',
    //         dataType: 'json',
    //         data: {
    //             materialId: materialId
    //         },
    //         success: function(res) {
    //             if (res) {
    //                 console.log(res);
    //                 // ADD INPUT STOCK ON HAND
    //                 var stock_on_hand = 0;
    //                 for (var i = 0; i < res.length; i++) {
    //                     stock_on_hand += parseInt(res[i].total_qty);
    //                 }
    //                 $('#stock_on_hand').val(stock_on_hand);

    //                 // Ensure event handler for adding new rows is only added once
    //                 $('#plus-row').off('click').on('click', function() {
    //                     rowIndex++;
    //                     const rowHtml = `
    //                         <div class="row" id="row-${rowIndex}">
    //                             <div class="col-md-3">
    //                                 <label for="sloc-${rowIndex}" class="form-label"><b>SLoc</b></label>
    //                                 <select id="sloc-${rowIndex}" name="sloc[${rowIndex}]" class="form-select">
    //                                     <option selected>Choose SLoc</option>
    //                                 </select>
    //                             </div>
    //                             <div class="col-md-3">
    //                                 <label for="box_no-${rowIndex}" class="form-label"><b>Box</b></label>
    //                                 <select id="box_no-${rowIndex}" name="box_no[${rowIndex}]" class="form-select">
    //                                     <option selected>Choose Box</option>
    //                                 </select>
    //                             </div>
    //                             <div class="col-md-2">
    //                                 <label for="total_qty-${rowIndex}" class="form-label"><b>Qty on hand</b></label>
    //                                 <input type="text" class="form-control" id="total_qty-${rowIndex}" name="total_qty[${rowIndex}]" readonly>
    //                             </div>
    //                             <div class="col-md-2">
    //                                 <label for="qty_unpack-${rowIndex}" class="form-label"><b>Unpack</b></label>
    //                                 <input type="number" min="1" class="form-control" id="qty_unpack-${rowIndex}" name="qty_unpack[${rowIndex}]">
    //                             </div>
    //                             <div class="col-md-2">
    //                                 <button class="btn btn-primary" type="submit" onclick="SubmitMaterialReq()" style="margin-top: 2rem;">
    //                                     <i class="bx bx-check-circle"></i>
    //                                 </button>
    //                             </div>
    //                         </div>
    //                     `;
                        
    //                     $('#dynamic-rows-container').append(rowHtml);

    //                     // Initialize select2 for the newly added select elements
    //                     $(`#sloc-${rowIndex}`).select2({
    //                         dropdownParent: $('#addMaterialRequest')
    //                     });
    //                     $(`#box_no-${rowIndex}`).select2({
    //                         dropdownParent: $('#addMaterialRequest')
    //                     });

    //                     // ADD OPTION SLOC
    //                     $(`#sloc-${rowIndex}`).empty().append('<option selected>Choose SLoc</option>');
    //                     for (var i = 0; i < res.length; i++) {
    //                         var sloc = res[i].sloc;
    //                         $(`#sloc-${rowIndex}`).append('<option value="' + sloc + '">' + sloc + '</option>');
    //                     }

    //                     // ADD OPTION BOX NO
    //                     $(`#box_no-${rowIndex}`).empty().append('<option selected>Choose Box No</option>');
    //                     for (var i = 0; i < res.length; i++) {
    //                         var box = res[i].no_box;
    //                         $(`#box_no-${rowIndex}`).append('<option value="' + box + '" data-total_qty="' + res[i].total_qty + '">' + box + '</option>');
    //                     }

    //                     // Update total_qty based on selected SLoc
    //                     $(`#sloc-${rowIndex}`).on('change', function () {
    //                         var selectedOption = $(this).find('option:selected');
    //                         var selectedTotalQty = selectedOption.data('total_qty');
    //                         $(`#total_qty-${rowIndex}`).val(selectedTotalQty);
    //                     });

    //                     // Update total_qty based on selected Box No
    //                     $(`#box_no-${rowIndex}`).on('change', function () {
    //                         var selectedOption = $(this).find('option:selected');
    //                         var selectedTotalQty = selectedOption.data('total_qty');
    //                         $(`#total_qty-${rowIndex}`).val(selectedTotalQty);
    //                     });

    //                     // Set initial total_qty based on the first SLoc in the list if needed
    //                     var initialTotalQty = $(`#sloc-${rowIndex}`).find('option:selected').data('total_qty');
    //                     $(`#total_qty-${rowIndex}`).val(initialTotalQty);
    //                 });

    //                 // Ensure modal hidden event is handled properly
    //                 $('#addMaterialRequest').off('hidden.bs.modal').on('hidden.bs.modal', function () {
    //                     $('#dynamic-rows-container').empty(); // Clear all dynamic rows when the modal is hidden
    //                 });
    //             } else {
    //                 $('#stock_on_hand').val(0);
    //             }
    //         },
    //         error: function(xhr, ajaxOptions, thrownError) {
    //             console.error('AJAX Error:', thrownError); // Log any errors
    //         }
    //     });
    // });
    
    // $(document).ready(function (){
    //     $('#product_id').select2();
    // });

    // SUBMIT DATA MATERIAL REQUEST
    // function SubmitMaterialReq() {
    //     $("form").on("submit", function (event) {
    //         event.preventDefault();
            
    //         // Initialize an empty array to store the data for each row
    //         let materialData = [];
            
    //         // Iterate over each row
    //         $(".row").each(function() {
    //             // Get the row index
    //             let rowIndex = $(this).attr('id').split('-')[1];
                
    //             // Collect the data from the current row
    //             let sloc = $(`#sloc-${rowIndex}`).val();
    //             let box_no = $(`#box_no-${rowIndex}`).val();
    //             let qty_unpack = $(`#qty_unpack-${rowIndex}`).val();
                
    //             // Push the collected data into the array
    //             materialData.push({
    //                 sloc: sloc,
    //                 box_no: box_no,
    //                 qty_unpack: qty_unpack
    //             });
    //         });

    //         // Send the collected data via AJAX
    //         $.ajax({
    //             url: '<?=base_url('production/AddMaterialRequest');?>',
    //             type: 'POST',
    //             data: {
    //                 materialData: materialData
    //             },
    //             success: function (result) {
    //                 console.log(result);
    //             },
    //             error: function(xhr, ajaxOptions, thrownError) {
    //                 console.log(thrownError);
    //             }
    //         });
    //     });
    // }

    
    // $(document).ready(function () {
    //     $('#product_id').select2();
    //     let rowIndex = 0;
    //     let quantitiesTracker = {};

    //     // Function to handle form submission
    //     function SubmitMaterialReq() {
    //         $("form").on("submit", function (event) {
    //             event.preventDefault();

    //             // Initialize an empty array to store the data for each row
    //             let materialData = [];
    //             let totalQty = 0; // Variable to accumulate qty_unpack values

    //             // Iterate over each row
    //             $(".row").each(function () {
    //                 // Get the row index
    //                 let id = $(this).attr('id');
    //                 if (id) {
    //                     let rowIndex = id.split('-')[1];

    //                     // Collect the data from the current row
    //                     let sloc = $(`#sloc-${rowIndex}`).val();
    //                     let box_no = $(`#box_no-${rowIndex}`).val();
    //                     let qty_unpack = parseFloat($(`#qty_unpack-${rowIndex}`).val());

    //                     // Push the collected data into the array
    //                     materialData.push({
    //                         sloc: sloc,
    //                         box_no: box_no,
    //                         // qty_unpack: qty_unpack
    //                     });

    //                     // Accumulate qty_unpack, ensuring it's a valid number
    //                     if (!isNaN(qty_unpack)) {
    //                         totalQty += qty_unpack;

    //                         // Update the quantities tracker
    //                         let key = `${sloc}-${box_no}`;
    //                         if (quantitiesTracker[key]) {
    //                             quantitiesTracker[key] -= qty_unpack;
    //                         } else {
    //                             quantitiesTracker[key] = parseFloat($(`#total_qty-${rowIndex}`).val()) - qty_unpack;
    //                         }
    //                     }
    //                 }
    //             });

    //             // Send the collected data via AJAX
    //             $.ajax({
    //                 url: '<?=base_url('production/AddMaterialRequest');?>',
    //                 type: 'POST',
    //                 data: {
    //                     materialData: materialData
    //                 },
    //                 success: function (result) {
    //                     // Update total_qty_get with accumulated qty_unpack
    //                     $('#total_qty_get').val(totalQty.toFixed(2));

    //                     // Make inputs and buttons readonly and disabled
    //                     $(".row").each(function () {
    //                         let id = $(this).attr('id');
    //                         if (id) {
    //                             let rowIndex = id.split('-')[1];
    //                             $(`#sloc-${rowIndex}`).prop('disabled', true);
    //                             $(`#box_no-${rowIndex}`).prop('disabled', true);
    //                             $(`#qty_unpack-${rowIndex}`).prop('readonly', true);
    //                             $(`#submit-btn-${rowIndex}`).prop('disabled', true);
    //                         }
    //                     });

    //                     // Decrease the stock_on_hand based on the total unpacked quantity
    //                     let currentStock = parseFloat($('#stock_on_hand').val());
    //                     if (!isNaN(currentStock)) {
    //                         let newStock = currentStock - totalQty;
    //                         $('#stock_on_hand').val(newStock.toFixed(2));
    //                     }

    //                     console.log(result);
    //                 },
    //                 error: function (xhr, ajaxOptions, thrownError) {
    //                     console.log(thrownError);
    //                 }
    //             });
    //         });
    //     }

    //     // Function to add a new row
    //     function addNewRow(res) {
    //         rowIndex++;
    //         const rowHtml = `
    //             <div class="row" id="row-${rowIndex}">
    //                 <div class="col-md-3">
    //                     <label for="sloc-${rowIndex}" class="form-label"><b>SLoc</b></label>
    //                     <select id="sloc-${rowIndex}" name="sloc[${rowIndex}]" class="form-select">
    //                         <option value="" selected>Choose SLoc</option>
    //                     </select>
    //                 </div>
    //                 <div class="col-md-3">
    //                     <label for="box_no-${rowIndex}" class="form-label"><b>Box</b></label>
    //                     <select id="box_no-${rowIndex}" name="box_no[${rowIndex}]" class="form-select">
    //                         <option value="" selected>Choose Box</option>
    //                     </select>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="total_qty-${rowIndex}" class="form-label"><b>Qty on hand</b></label>
    //                     <input type="text" class="form-control" id="total_qty-${rowIndex}" name="total_qty[${rowIndex}]" readonly>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="qty_unpack-${rowIndex}" class="form-label"><b>Unpack</b></label>
    //                     <input type="number" min="0.1" step="0.1" class="form-control" id="qty_unpack-${rowIndex}" name="qty_unpack[${rowIndex}]">
    //                 </div>
    //                 <div class="col-md-2">
    //                     <button class="btn btn-primary submit-row-btn" id="submit-btn-${rowIndex}" type="button" style="margin-top: 2rem;">
    //                         <i class="bx bx-check-circle"></i>
    //                     </button>
    //                 </div>
    //             </div>
    //         `;

    //         $('#dynamic-rows-container').append(rowHtml);

    //         // Initialize select2 for the newly added select elements
    //         $(`#sloc-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });
    //         $(`#box_no-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });

    //         // ADD OPTION SLOC
    //         $(`#sloc-${rowIndex}`).empty().append('<option value="" selected>Choose SLoc</option>');
    //         for (let i = 0; i < res.length; i++) {
    //             let sloc = res[i].sloc;
    //             $(`#sloc-${rowIndex}`).append('<option value="' + sloc + '">' + sloc + '</option>');
    //         }

    //         // ADD OPTION BOX NO
    //         $(`#box_no-${rowIndex}`).empty().append('<option value="" selected>Choose Box No</option>');
    //         for (let i = 0; i < res.length; i++) {
    //             let box = res[i].no_box;
    //             $(`#box_no-${rowIndex}`).append('<option value="' + box + '" data-total_qty="' + (res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty) + '">' + box + '</option>');
    //         }

    //         // Update total_qty based on selected SLoc
    //         $(`#sloc-${rowIndex}`).on('change', function () {
    //             let selectedOption = $(this).find('option:selected');
    //             let selectedTotalQty = selectedOption.data('total_qty');
    //             let key = `${$(this).val()}-${$(`#box_no-${rowIndex}`).val()}`;
    //             if (quantitiesTracker[key] !== undefined) {
    //                 selectedTotalQty = quantitiesTracker[key];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(selectedTotalQty.toFixed(2));
    //         });

    //         // Update total_qty based on selected Box No
    //         $(`#box_no-${rowIndex}`).on('change', function () {
    //             let selectedOption = $(this).find('option:selected');
    //             let selectedTotalQty = selectedOption.data('total_qty');
    //             let key = `${$(`#sloc-${rowIndex}`).val()}-${$(this).val()}`;
    //             if (quantitiesTracker[key] !== undefined) {
    //                 selectedTotalQty = quantitiesTracker[key];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(selectedTotalQty.toFixed(2));
    //         });

    //         // Set initial total_qty based on the first SLoc in the list if needed
    //         let initialTotalQty = $(`#sloc-${rowIndex}`).find('option:selected').data('total_qty');
    //         let initialKey = `${$(`#sloc-${rowIndex}`).val()}-${$(`#box_no-${rowIndex}`).val()}`;
    //         if (quantitiesTracker[initialKey] !== undefined) {
    //             initialTotalQty = quantitiesTracker[initialKey];
    //         }
    //         $(`#total_qty-${rowIndex}`).val(initialTotalQty.toFixed(2));
    //     }

    //     // Handle click event for adding new rows
    //     $(document).on('click', '.edit-material-request', function () {
    //         var $row = $(this).closest('tr');
    //         var materialId = $row.data('id');
    //         var materialDesc = $row.data('desc');
    //         var materialNeed = $row.data('qty');
    //         var uom = $row.data('uom');

    //         $('#material_id').val(materialId);
    //         $('#material_desc').val(materialDesc);
    //         $('#material_need').val(materialNeed);
    //         $('#uom').val(uom);

    //         $.ajax({
    //             url: '<?= base_url('production/getSlocStorage'); ?>',
    //             type: 'post',
    //             dataType: 'json',
    //             data: {
    //                 materialId: materialId
    //             },
    //             success: function (res) {
    //                 if (res) {
    //                     console.log(res);
    //                     // ADD INPUT STOCK ON HAND
    //                     var stock_on_hand = 0;
    //                     for (let i = 0; i < res.length; i++) {
    //                         stock_on_hand += parseFloat(res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty);
    //                     }
    //                     $('#stock_on_hand').val(stock_on_hand.toFixed(2));

    //                     // Ensure event handler for adding new rows is only added once
    //                     $('#plus-row').off('click').on('click', function () {
    //                         addNewRow(res);
    //                     });

    //                     // Ensure modal hidden event is handled properly
    //                     $('#addMaterialRequest').off('hidden.bs.modal').on('hidden.bs.modal', function () {
    //                         $('#dynamic-rows-container').empty(); // Clear all dynamic rows when the modal is hidden
    //                         rowIndex = 0; // Reset rowIndex when the modal is hidden
    //                     });
    //                 } else {
    //                     $('#stock_on_hand').val(0);
    //                 }
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //                 console.error('AJAX Error:', thrownError); // Log any errors
    //             }
    //         });
    //     });

    //     // Handle click event for row submit buttons
    //     $(document).on('click', '.submit-row-btn', function () {
    //         let id = $(this).closest('.row').attr('id');
    //         if (id) {
    //             let rowIndex = id.split('-')[1];
    //             let sloc = $(`#sloc-${rowIndex}`).val();
    //             let box_no = $(`#box_no-${rowIndex}`).val();
    //             let qty_unpack = parseFloat($(`#qty_unpack-${rowIndex}`).val());
    //             let total_qty = parseFloat($(`#total_qty-${rowIndex}`).val());

    //             // Validate that sloc and box_no are selected
    //             if (!sloc || !box_no) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Select <strong>SLoc</strong> and <strong>Box</strong> before submitting<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Validate that qty_unpack does not exceed total_qty
    //             if (qty_unpack > total_qty) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Unpacked quantity cannot exceed quantity on hand<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Trigger form submission
    //             $("form").trigger('submit');
    //         }
    //     });

    //     // Initialize form submission handler
    //     SubmitMaterialReq();
    // });

    // $(document).ready(function () {
    //     $('#product_id').select2();
    //     let rowIndex = 0;
    //     let quantitiesTracker = {};
    //     let isFloatUom = false;

    //     // Function to handle form submission
    //     function SubmitMaterialReq() {
    //         $("form").on("submit", function (event) {
    //             event.preventDefault();

    //             // Initialize an empty array to store the data for each row
    //             let materialData = [];
    //             let totalQty = 0; // Variable to accumulate qty_unpack values

    //             // Iterate over each row
    //             $(".row").each(function () {
    //                 // Get the row index
    //                 let id = $(this).attr('id');
    //                 if (id) {
    //                     let rowIndex = id.split('-')[1];

    //                     // Collect the data from the current row
    //                     let sloc = $(`#sloc-${rowIndex}`).val();
    //                     let box_no = $(`#box_no-${rowIndex}`).val();
    //                     let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);

    //                     // Push the collected data into the array
    //                     materialData.push({
    //                         sloc: sloc,
    //                         box_no: box_no,
    //                         // qty_unpack: qty_unpack
    //                     });

    //                     // Accumulate qty_unpack, ensuring it's a valid number
    //                     if (!isNaN(qty_unpack)) {
    //                         totalQty += qty_unpack;

    //                         // Update the quantities tracker
    //                         let key = `${sloc}-${box_no}`;
    //                         if (quantitiesTracker[key]) {
    //                             quantitiesTracker[key] -= qty_unpack;
    //                         } else {
    //                             quantitiesTracker[key] = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) - qty_unpack : parseInt($(`#total_qty-${rowIndex}`).val(), 10) - qty_unpack;
    //                         }
    //                     }
    //                 }
    //             });

    //             // Send the collected data via AJAX
    //             $.ajax({
    //                 url: '<?=base_url('production/AddMaterialRequest');?>',
    //                 type: 'POST',
    //                 data: {
    //                     materialData: materialData
    //                 },
    //                 success: function (result) {
    //                     // Update total_qty_get with accumulated qty_unpack
    //                     $('#total_qty_get').val(totalQty.toFixed(isFloatUom ? 2 : 0));

    //                     // Make inputs and buttons readonly and disabled
    //                     $(".row").each(function () {
    //                         let id = $(this).attr('id');
    //                         if (id) {
    //                             let rowIndex = id.split('-')[1];
    //                             $(`#sloc-${rowIndex}`).prop('disabled', true);
    //                             $(`#box_no-${rowIndex}`).prop('disabled', true);
    //                             $(`#qty_unpack-${rowIndex}`).prop('readonly', true);
    //                             $(`#submit-btn-${rowIndex}`).prop('disabled', true);
    //                         }
    //                     });

    //                     // Decrease the stock_on_hand based on the total unpacked quantity
    //                     let currentStock = parseFloat($('#stock_on_hand').val());
    //                     if (!isNaN(currentStock)) {
    //                         let newStock = currentStock - totalQty;
    //                         $('#stock_on_hand').val(newStock.toFixed(isFloatUom ? 2 : 0));
    //                     }

    //                     console.log(result);
    //                 },
    //                 error: function (xhr, ajaxOptions, thrownError) {
    //                     console.log(thrownError);
    //                 }
    //             });
    //         });
    //     }

    //     // Function to add a new row
    //     function addNewRow(res) {
    //         rowIndex++;
    //         const rowHtml = `
    //             <div class="row" id="row-${rowIndex}">
    //                 <div class="col-md-3">
    //                     <label for="sloc-${rowIndex}" class="form-label"><b>SLoc</b></label>
    //                     <select id="sloc-${rowIndex}" name="sloc[${rowIndex}]" class="form-select">
    //                         <option value="" selected>Choose SLoc</option>
    //                     </select>
    //                 </div>
    //                 <div class="col-md-3">
    //                     <label for="box_no-${rowIndex}" class="form-label"><b>Box</b></label>
    //                     <select id="box_no-${rowIndex}" name="box_no[${rowIndex}]" class="form-select">
    //                         <option value="" selected>Choose Box</option>
    //                     </select>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="total_qty-${rowIndex}" class="form-label"><b>Qty on hand</b></label>
    //                     <input type="text" class="form-control" id="total_qty-${rowIndex}" name="total_qty[${rowIndex}]" readonly>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="qty_unpack-${rowIndex}" class="form-label"><b>Unpack</b></label>
    //                     <input type="number" min="${isFloatUom ? '0.1' : '1'}" step="${isFloatUom ? '0.1' : '1'}" class="form-control" id="qty_unpack-${rowIndex}" name="qty_unpack[${rowIndex}]">
    //                 </div>
    //                 <div class="col-md-2">
    //                     <button class="btn btn-primary submit-row-btn" id="submit-btn-${rowIndex}" type="button" style="margin-top: 2rem;">
    //                         <i class="bx bx-check-circle"></i>
    //                     </button>
    //                 </div>
    //             </div>
    //         `;

    //         $('#dynamic-rows-container').append(rowHtml);

    //         // Initialize select2 for the newly added select elements
    //         $(`#sloc-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });
    //         $(`#box_no-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });

    //         // ADD OPTION SLOC
    //         $(`#sloc-${rowIndex}`).empty().append('<option value="" selected>Choose SLoc</option>');
    //         for (let i = 0; i < res.length; i++) {
    //             let sloc = res[i].sloc;
    //             $(`#sloc-${rowIndex}`).append('<option value="' + sloc + '">' + sloc + '</option>');
    //         }

    //         // ADD OPTION BOX NO
    //         $(`#box_no-${rowIndex}`).empty().append('<option value="" selected>Choose Box No</option>');
    //         for (let i = 0; i < res.length; i++) {
    //             let box = res[i].no_box;
    //             $(`#box_no-${rowIndex}`).append('<option value="' + box + '" data-total_qty="' + (res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty) + '">' + box + '</option>');
    //         }

    //         // Update total_qty based on selected SLoc
    //         $(`#sloc-${rowIndex}`).on('change', function () {
    //             let selectedOption = $(this).find('option:selected');
    //             let selectedTotalQty = selectedOption.data('total_qty');
    //             let key = `${$(this).val()}-${$(`#box_no-${rowIndex}`).val()}`;
    //             if (quantitiesTracker[key] !== undefined) {
    //                 selectedTotalQty = quantitiesTracker[key];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(selectedTotalQty.toFixed(isFloatUom ? 2 : 0));
    //         });

    //         // Update total_qty based on selected Box No
    //         $(`#box_no-${rowIndex}`).on('change', function () {
    //             let selectedOption = $(this).find('option:selected');
    //             let selectedTotalQty = selectedOption.data('total_qty');
    //             let key = `${$(`#sloc-${rowIndex}`).val()}-${$(this).val()}`;
    //             if (quantitiesTracker[key] !== undefined) {
    //                 selectedTotalQty = quantitiesTracker[key];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(selectedTotalQty.toFixed(isFloatUom ? 2 : 0));
    //         });

    //         // Set initial total_qty based on the first SLoc in the list if needed
    //         let initialTotalQty = $(`#sloc-${rowIndex}`).find('option:selected').data('total_qty');
    //         let initialKey = `${$(`#sloc-${rowIndex}`).val()}-${$(`#box_no-${rowIndex}`).val()}`;
    //         if (quantitiesTracker[initialKey] !== undefined) {
    //             initialTotalQty = quantitiesTracker[initialKey];
    //         }
    //         $(`#total_qty-${rowIndex}`).val(initialTotalQty.toFixed(isFloatUom ? 2 : 0));
    //     }

    //     // Handle click event for adding new rows
    //     $(document).on('click', '.edit-material-request', function () {
    //         var $row = $(this).closest('tr');
    //         var materialId = $row.data('id');
    //         var materialDesc = $row.data('desc');
    //         var materialNeed = $row.data('qty');
    //         var uom = $row.data('uom');

    //         $('#material_id').val(materialId);
    //         $('#material_desc').val(materialDesc);
    //         $('#material_need').val(materialNeed);
    //         $('#uom').val(uom);

    //         // Check if Uom is not "PC"
    //         isFloatUom = (uom !== "PC");

    //         $.ajax({
    //             url: '<?= base_url('production/getSlocStorage'); ?>',
    //             type: 'post',
    //             dataType: 'json',
    //             data: {
    //                 materialId: materialId
    //             },
    //             success: function (res) {
    //                 if (res) {
    //                     console.log(res);
    //                     // ADD INPUT STOCK ON HAND
    //                     var stock_on_hand = 0;
    //                     for (let i = 0; i < res.length; i++) {
    //                         stock_on_hand += parseFloat(res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty);
    //                     }
    //                     $('#stock_on_hand').val(stock_on_hand.toFixed(isFloatUom ? 2 : 0));

    //                     // Ensure event handler for adding new rows is only added once
    //                     $('#plus-row').off('click').on('click', function () {
    //                         addNewRow(res);
    //                     });

    //                     // Ensure modal hidden event is handled properly
    //                     $('#addMaterialRequest').off('hidden.bs.modal').on('hidden.bs.modal', function () {
    //                         $('#dynamic-rows-container').empty(); // Clear all dynamic rows when the modal is hidden
    //                         rowIndex = 0; // Reset rowIndex when the modal is hidden
    //                     });
    //                 } else {
    //                     $('#stock_on_hand').val(0);
    //                 }
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //                 console.error('AJAX Error:', thrownError); // Log any errors
    //             }
    //         });
    //     });

    //     // Handle click event for row submit buttons
    //     $(document).on('click', '.submit-row-btn', function () {
    //         let id = $(this).closest('.row').attr('id');
    //         if (id) {
    //             let rowIndex = id.split('-')[1];
    //             let sloc = $(`#sloc-${rowIndex}`).val();
    //             let box_no = $(`#box_no-${rowIndex}`).val();
    //             let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);
    //             let total_qty = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) : parseInt($(`#total_qty-${rowIndex}`).val(), 10);

    //             // Validate that sloc and box_no are selected
    //             if (!sloc || !box_no) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Select <strong>SLoc</strong> and <strong>Box</strong> before submitting<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Validate that qty_unpack does not exceed total_qty
    //             if (qty_unpack > total_qty) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Unpacked quantity cannot exceed quantity on hand<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Trigger form submission
    //             $("form").trigger('submit');
    //         }
    //     });

    //     // Initialize form submission handler
    //     SubmitMaterialReq();
    // });

    // $(document).ready(function () {
    //     $('#product_id').select2();
    //     let rowIndex = 0;
    //     let quantitiesTracker = {};
    //     let isFloatUom = false;

    //     // Helper function to format numbers without trailing zeros
    //     function formatNumber(num) {
    //         return num % 1 === 0 ? num.toString() : num.toFixed(2).replace(/\.?0+$/, '');
    //     }

    //     // Function to handle form submission
    //     function SubmitMaterialReq() {
    //         $("form").on("submit", function (event) {
    //             event.preventDefault();

    //             // Initialize an empty array to store the data for each row
    //             let materialData = [];
    //             let totalQty = 0; // Variable to accumulate qty_unpack values

    //             // Iterate over each row
    //             $(".row").each(function () {
    //                 // Get the row index
    //                 let id = $(this).attr('id');
    //                 if (id) {
    //                     let rowIndex = id.split('-')[1];

    //                     // Collect the data from the current row
    //                     let sloc = $(`#sloc-${rowIndex}`).val();
    //                     let box_no = $(`#box_no-${rowIndex}`).val();
    //                     let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);

    //                     // Push the collected data into the array
    //                     materialData.push({
    //                         sloc: sloc,
    //                         box_no: box_no,
    //                         // qty_unpack: qty_unpack
    //                     });

    //                     // Accumulate qty_unpack, ensuring it's a valid number
    //                     if (!isNaN(qty_unpack)) {
    //                         totalQty += qty_unpack;

    //                         // Update the quantities tracker
    //                         let key = `${sloc}-${box_no}`;
    //                         if (quantitiesTracker[key]) {
    //                             quantitiesTracker[key] -= qty_unpack;
    //                         } else {
    //                             quantitiesTracker[key] = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) - qty_unpack : parseInt($(`#total_qty-${rowIndex}`).val(), 10) - qty_unpack;
    //                         }
    //                     }
    //                 }
    //             });

    //             // Send the collected data via AJAX
    //             $.ajax({
    //                 url: '<?=base_url('production/AddMaterialRequest');?>',
    //                 type: 'POST',
    //                 data: {
    //                     materialData: materialData
    //                 },
    //                 success: function (result) {
    //                     // Update total_qty_get with accumulated qty_unpack
    //                     $('#total_qty_get').val(formatNumber(totalQty));

    //                     // Make inputs and buttons readonly and disabled
    //                     $(".row").each(function () {
    //                         let id = $(this).attr('id');
    //                         if (id) {
    //                             let rowIndex = id.split('-')[1];
    //                             $(`#sloc-${rowIndex}`).prop('disabled', true);
    //                             $(`#box_no-${rowIndex}`).prop('disabled', true);
    //                             $(`#qty_unpack-${rowIndex}`).prop('readonly', true);
    //                             $(`#submit-btn-${rowIndex}`).prop('disabled', true);
    //                         }
    //                     });

    //                     // Decrease the stock_on_hand based on the total unpacked quantity
    //                     let currentStock = parseFloat($('#stock_on_hand').val());
    //                     if (!isNaN(currentStock)) {
    //                         let newStock = currentStock - totalQty;
    //                         $('#stock_on_hand').val(formatNumber(newStock));
    //                     }

    //                     console.log(result);
    //                 },
    //                 error: function (xhr, ajaxOptions, thrownError) {
    //                     console.log(thrownError);
    //                 }
    //             });
    //         });
    //     }

    //     // Function to add a new row
    //     function addNewRow(res) {
    //         rowIndex++;
    //         const rowHtml = `
    //             <div class="row" id="row-${rowIndex}">
    //                 <div class="col-md-3">
    //                     <label for="sloc-${rowIndex}" class="form-label"><b>SLoc</b></label>
    //                     <select id="sloc-${rowIndex}" name="sloc[${rowIndex}]" class="form-select">
    //                         <option value="" selected>Choose SLoc</option>
    //                     </select>
    //                 </div>
    //                 <div class="col-md-3">
    //                     <label for="box_no-${rowIndex}" class="form-label"><b>Box</b></label>
    //                     <select id="box_no-${rowIndex}" name="box_no[${rowIndex}]" class="form-select">
    //                         <option value="" selected>Choose Box</option>
    //                     </select>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="total_qty-${rowIndex}" class="form-label"><b>Qty on hand</b></label>
    //                     <input type="text" class="form-control" id="total_qty-${rowIndex}" name="total_qty[${rowIndex}]" readonly>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="qty_unpack-${rowIndex}" class="form-label"><b>Unpack</b></label>
    //                     <input type="number" min="${isFloatUom ? '0.1' : '1'}" step="${isFloatUom ? '0.1' : '1'}" class="form-control" id="qty_unpack-${rowIndex}" name="qty_unpack[${rowIndex}]">
    //                 </div>
    //                 <div class="col-md-2">
    //                     <button class="btn btn-primary submit-row-btn" id="submit-btn-${rowIndex}" type="button" style="margin-top: 2rem;">
    //                         <i class="bx bx-check-circle"></i>
    //                     </button>
    //                 </div>
    //             </div>
    //         `;

    //         $('#dynamic-rows-container').append(rowHtml);

    //         // Initialize select2 for the newly added select elements
    //         $(`#sloc-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });
    //         $(`#box_no-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });

    //         // ADD OPTION SLOC
    //         $(`#sloc-${rowIndex}`).empty().append('<option value="" selected>Choose SLoc</option>');
    //         for (let i = 0; i < res.length; i++) {
    //             let sloc = res[i].sloc;
    //             $(`#sloc-${rowIndex}`).append('<option value="' + sloc + '">' + sloc + '</option>');
    //         }

    //         // ADD OPTION BOX NO
    //         $(`#box_no-${rowIndex}`).empty().append('<option value="" selected>Choose Box No</option>');
    //         for (let i = 0; i < res.length; i++) {
    //             let box = res[i].no_box;
    //             $(`#box_no-${rowIndex}`).append('<option value="' + box + '" data-total_qty="' + (res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty) + '">' + box + '</option>');
    //         }

    //         // Update total_qty based on selected SLoc
    //         $(`#sloc-${rowIndex}`).on('change', function () {
    //             let selectedOption = $(this).find('option:selected');
    //             let selectedTotalQty = selectedOption.data('total_qty');
    //             let key = `${$(this).val()}-${$(`#box_no-${rowIndex}`).val()}`;
    //             if (quantitiesTracker[key] !== undefined) {
    //                 selectedTotalQty = quantitiesTracker[key];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(formatNumber(selectedTotalQty));
    //         });

    //         // Update total_qty based on selected Box No
    //         $(`#box_no-${rowIndex}`).on('change', function () {
    //             let selectedOption = $(this).find('option:selected');
    //             let selectedTotalQty = selectedOption.data('total_qty');
    //             let key = `${$(`#sloc-${rowIndex}`).val()}-${$(this).val()}`;
    //             if (quantitiesTracker[key] !== undefined) {
    //                 selectedTotalQty = quantitiesTracker[key];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(formatNumber(selectedTotalQty));
    //         });

    //         // Set initial total_qty based on the first SLoc in the list if needed
    //         let initialTotalQty = $(`#sloc-${rowIndex}`).find('option:selected').data('total_qty');
    //         let initialKey = `${$(`#sloc-${rowIndex}`).val()}-${$(`#box_no-${rowIndex}`).val()}`;
    //         if (quantitiesTracker[initialKey] !== undefined) {
    //             initialTotalQty = quantitiesTracker[initialKey];
    //         }
    //         $(`#total_qty-${rowIndex}`).val(formatNumber(initialTotalQty));
    //     }

    //     // Handle click event for adding new rows
    //     $(document).on('click', '.edit-material-request', function () {
    //         var $row = $(this).closest('tr');
    //         var materialId = $row.data('id');
    //         var materialDesc = $row.data('desc');
    //         var materialNeed = $row.data('qty');
    //         var uom = $row.data('uom');

    //         $('#material_id').val(materialId);
    //         $('#material_desc').val(materialDesc);
    //         $('#material_need').val(materialNeed);
    //         $('#uom').val(uom);

    //         // Check if Uom is not "PC"
    //         isFloatUom = (uom !== "PC");

    //         $.ajax({
    //             url: '<?= base_url('production/getSlocStorage'); ?>',
    //             type: 'post',
    //             dataType: 'json',
    //             data: {
    //                 materialId: materialId
    //             },
    //             success: function (res) {
    //                 if (res) {
    //                     console.log(res);
    //                     // ADD INPUT STOCK ON HAND
    //                     var stock_on_hand = 0;
    //                     for (let i = 0; i < res.length; i++) {
    //                         stock_on_hand += parseFloat(res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty);
    //                     }
    //                     $('#stock_on_hand').val(formatNumber(stock_on_hand));

    //                     // Ensure event handler for adding new rows is only added once
    //                     $('#plus-row').off('click').on('click', function () {
    //                         addNewRow(res);
    //                     });

    //                     // Ensure modal hidden event is handled properly
    //                     $('#addMaterialRequest').off('hidden.bs.modal').on('hidden.bs.modal', function () {
    //                         $('#dynamic-rows-container').empty(); // Clear all dynamic rows when the modal is hidden
    //                         rowIndex = 0; // Reset rowIndex when the modal is hidden
    //                     });
    //                 } else {
    //                     $('#stock_on_hand').val(0);
    //                 }
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //                 console.error('AJAX Error:', thrownError); // Log any errors
    //             }
    //         });
    //     });

    //     // Handle click event for row submit buttons
    //     $(document).on('click', '.submit-row-btn', function () {
    //         let id = $(this).closest('.row').attr('id');
    //         if (id) {
    //             let rowIndex = id.split('-')[1];
    //             let sloc = $(`#sloc-${rowIndex}`).val();
    //             let box_no = $(`#box_no-${rowIndex}`).val();
    //             let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);
    //             let total_qty = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) : parseInt($(`#total_qty-${rowIndex}`).val(), 10);

    //             // Validate that sloc and box_no are selected
    //             if (!sloc || !box_no) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Select <strong>SLoc</strong> and <strong>Box</strong> before submitting<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Validate that qty_unpack does not exceed total_qty
    //             if (qty_unpack > total_qty) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Unpacked quantity cannot exceed quantity on hand<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Trigger form submission
    //             $("form").trigger('submit');
    //         }
    //     });

    //     // Initialize form submission handler
    //     SubmitMaterialReq();
    // });

    $(document).ready(function () {
        $('#product_id').select2();
        let rowIndex = 0;
        let quantitiesTracker = {};
        let isFloatUom = false;

        // Helper function to format numbers without trailing zeros
        function formatNumber(num) {
            if (num === undefined || num === null || isNaN(num)) {
                return 0;
            }
            return num % 1 === 0 ? num.toString() : num.toFixed(2).replace(/\.?0+$/, '');
        }

        // Function to handle form submission
        function SubmitMaterialReq() {
            $("form").on("submit", function (event) {
                event.preventDefault();

                // Initialize an empty array to store the data for each row
                let materialData = [];
                let totalQty = 0; // Variable to accumulate qty_unpack values

                // Iterate over each row
                $(".rows").each(function () {
                    // Get the row index
                    let id = $(this).attr('id');
                    if (id) {
                        let rowIndex = id.split('-')[1];
                        console.log("RowIndex " + rowIndex);
                        console.log("Id " + id);
                        
                        // Collect the data from the current row
                        let sloc = $(`#sloc-${rowIndex}`).val();
                        let box_no = $(`#box_no-${rowIndex}`).val();
                        let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);

                        // Push the collected data into the array
                        materialData.push({
                            sloc: sloc,
                            box_no: box_no,
                            // qty_unpack: qty_unpack
                        });

                        // Accumulate qty_unpack, ensuring it's a valid number
                        if (!isNaN(qty_unpack)) {
                            totalQty += qty_unpack;
                            
                            // Update the quantities tracker
                            let key = `${sloc}-${box_no}`;
                            if (quantitiesTracker[key]) {
                                quantitiesTracker[key] -= qty_unpack;
                            } else {
                                quantitiesTracker[key] = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) - qty_unpack : parseInt($(`#total_qty-${rowIndex}`).val(), 10) - qty_unpack;
                            }

                            console.log("TQ " + totalQty);
                            console.log("QU " + qty_unpack);
                            let currentStock = parseFloat($('#stock_on_hand').val());
                            if (!isNaN(currentStock)) {
                                let newStock = currentStock - parseFloat(qty_unpack);
                                console.log("parseFloat(qty_unpack)" + parseFloat(qty_unpack));
                                console.log("Current stock" + currentStock);
                                console.log("New stock" + newStock);
                                console.log("Current Stock: " + currentStock + "-" +" Qty unpack: " + qty_unpack);
                                $('#stock_on_hand').val(newStock);
                            }
                        }

                        $('#total_qty_get').val(formatNumber(totalQty));

                    }
                });

                // Send the collected data via AJAX
                $.ajax({
                    url: '<?=base_url('production/AddMaterialRequest');?>',
                    type: 'POST',
                    data: {
                        materialData: materialData
                    },
                    success: function (result) {
                        // Update total_qty_get with accumulated qty_unpack
                        // $('#total_qty_get').val(formatNumber(totalQty));

                        // Make inputs and buttons readonly and disabled
                        $(".rows").each(function () {
                            let id = $(this).attr('id');
                            if (id) {
                                let rowIndex = id.split('-')[1];
                                $(`#sloc-${rowIndex}`).prop('disabled', true);
                                $(`#box_no-${rowIndex}`).prop('disabled', true);
                                $(`#qty_unpack-${rowIndex}`).prop('readonly', true);
                                $(`#submit-btn-${rowIndex}`).prop('disabled', true);
                            }
                        });

                        // Decrease the stock_on_hand based on the total unpacked quantity
                        // let currentStock = parseFloat($('#stock_on_hand').val());
                        // if (!isNaN(currentStock)) {
                        //     let newStock = currentStock - totalQty;
                        //     $('#stock_on_hand').val(formatNumber(newStock));
                        // }

                        console.log(materialData);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            });
        }

        // Function to add a new row
        function addNewRow(res) {
            rowIndex++;
            const rowHtml = `
                <div class="row rows" id="rows-${rowIndex}">
                    <div class="col-md-3">
                        <label for="sloc-${rowIndex}" class="form-label"><b>SLoc</b></label>
                        <select id="sloc-${rowIndex}" name="sloc[${rowIndex}]" class="form-select">
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="box_no-${rowIndex}" class="form-label"><b>Box</b></label>
                        <select id="box_no-${rowIndex}" name="box_no[${rowIndex}]" class="form-select">
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="total_qty-${rowIndex}" class="form-label"><b>Qty on hand</b></label>
                        <input type="text" class="form-control" id="total_qty-${rowIndex}" name="total_qty[${rowIndex}]" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="qty_unpack-${rowIndex}" class="form-label"><b>Unpack</b></label>
                        <input type="number" min="${isFloatUom ? '0.1' : '1'}" step="${isFloatUom ? '0.1' : '1'}" class="form-control" id="qty_unpack-${rowIndex}" name="qty_unpack[${rowIndex}]">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary submit-row-btn" id="submit-btn-${rowIndex}" type="button" style="margin-top: 2rem;">
                            <i class="bx bx-check-circle"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#dynamic-rows-container').append(rowHtml);

            // Initialize select2 for the newly added select elements
            $(`#sloc-${rowIndex}`).select2({
                dropdownParent: $('#addMaterialRequest')
            });
            $(`#box_no-${rowIndex}`).select2({
                dropdownParent: $('#addMaterialRequest')
            });

            let slocBoxes = {};
            for (let i = 0; i < res.length; i++) {
                let sloc = res[i].sloc;
                let box = res[i].no_box;
                let totalQty = res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty;

                if (!slocBoxes[sloc]) {
                    slocBoxes[sloc] = [];
                }
                slocBoxes[sloc].push({ box: box, totalQty: totalQty });
            }

            // Populate SLoc dropdown
            $(`#sloc-${rowIndex}`).append('<option value="" selected>Choose SLoc</option>');
            for (let sloc in slocBoxes) {
                $(`#sloc-${rowIndex}`).append(`<option value="${sloc}">${sloc}</option>`);
            }

            // Update Box options based on selected SLoc
            $(`#sloc-${rowIndex}`).on('change', function () {
                let selectedSLoc = $(this).val();
                $(`#box_no-${rowIndex}`).empty().append('<option value="" selected>Choose Box</option>');

                if (slocBoxes[selectedSLoc]) {
                    slocBoxes[selectedSLoc].forEach(item => {
                        $(`#box_no-${rowIndex}`).append(`<option value="${item.box}" data-total_qty="${item.totalQty}">${item.box}</option>`);
                    });
                }

                $(`#total_qty-${rowIndex}`).val(''); // Clear total_qty on SLoc change
            });

            // Update total_qty based on selected Box
            $(`#box_no-${rowIndex}`).on('change', function () {
                let selectedOption = $(this).find('option:selected');
                let selectedTotalQty = parseFloat(selectedOption.data('total_qty'));

                if (isNaN(selectedTotalQty)) {
                    selectedTotalQty = 0;
                }

                let key = `${$(`#sloc-${rowIndex}`).val()}-${$(this).val()}`;
                if (quantitiesTracker[key] !== undefined) {
                    selectedTotalQty = quantitiesTracker[key];
                }
                $(`#total_qty-${rowIndex}`).val(formatNumber(selectedTotalQty));
            });

            // Initialize total_qty based on initial selection
            let initialSLoc = $(`#sloc-${rowIndex}`).val();
            if (initialSLoc && slocBoxes[initialSLoc]) {
                let initialBox = $(`#box_no-${rowIndex}`).val();
                let initialTotalQty = slocBoxes[initialSLoc].find(item => item.box === initialBox)?.totalQty || 0;
                let initialKey = `${initialSLoc}-${initialBox}`;
                if (quantitiesTracker[initialKey] !== undefined) {
                    initialTotalQty = quantitiesTracker[initialKey];
                }
                $(`#total_qty-${rowIndex}`).val(formatNumber(initialTotalQty));
            }
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

            $.ajax({
                url: '<?= base_url('production/getSlocStorage'); ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    materialId: materialId
                },
                success: function (res) {
                    if (res) {
                        console.log(res);
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
                    console.error('AJAX Error:', thrownError); // Log any errors
                }
            });
        });

        // Handle click event for row submit buttons
        $(document).on('click', '.submit-row-btn', function () {
            let id = $(this).closest('.rows').attr('id');
            if (id) {
                let rowIndex = id.split('-')[1];
                let sloc = $(`#sloc-${rowIndex}`).val();
                let box_no = $(`#box_no-${rowIndex}`).val();
                let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);
                let total_qty = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) : parseInt($(`#total_qty-${rowIndex}`).val(), 10);

                // Validate that sloc and box_no are selected
                if (!sloc || !box_no) {
                    $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Select <strong>SLoc</strong> and <strong>Box</strong> before submitting<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                    setTimeout(function() {
                        $('#datas-modals .alert').alert('close');
                    }, 3000);

                    return;
                }

                // Validate that qty_unpack does not exceed total_qty
                if (qty_unpack > total_qty) {
                    $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Unpacked quantity cannot exceed quantity on hand<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

                    setTimeout(function() {
                        $('#datas-modals .alert').alert('close');
                    }, 3000);

                    return;
                }

                // Trigger form submission
                $("form").trigger('submit');
                }
        });
                
                // Initialize form submission handler
        SubmitMaterialReq();
    });

    // $(document).ready(function () {
    //     $('#product_id').select2();
    //     let rowIndex = 0;
    //     let quantitiesTracker = {};
    //     let isFloatUom = false;

    //     // Helper function to format numbers without trailing zeros
    //     function formatNumber(num) {
    //         if (num === undefined || num === null || isNaN(num)) {
    //             return 0;
    //         }
    //         return num % 1 === 0 ? num.toString() : num.toFixed(2).replace(/\.?0+$/, '');
    //     }

    //     // Function to handle form submission
    //     function SubmitMaterialReq() {
    //         $("form").on("submit", function (event) {
    //             event.preventDefault();

    //             // Initialize an empty array to store the data for each row
    //             let materialData = [];
    //             let totalQty = 0; // Variable to accumulate qty_unpack values

    //             // Iterate over each row
    //             $(".rows").each(function () {
    //                 // Get the row index
    //                 let id = $(this).attr('id');
    //                 if (id) {
    //                     let rowIndex = id.split('-')[1];
    //                     console.log("RowIndex " + rowIndex);
    //                     console.log("Id " + id);

    //                     // Collect the data from the current row
    //                     let sloc = $(`#sloc-${rowIndex}`).val();
    //                     let box_no = $(`#box_no-${rowIndex}`).val();
    //                     let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);

    //                     // Push the collected data into the array
    //                     materialData.push({
    //                         sloc: sloc,
    //                         box_no: box_no,
    //                         // qty_unpack: qty_unpack
    //                     });

    //                     // Accumulate qty_unpack, ensuring it's a valid number
    //                     if (!isNaN(qty_unpack)) {
    //                         totalQty += qty_unpack;

    //                         // Update the quantities tracker
    //                         let key = `${sloc}-${box_no}`;
    //                         if (quantitiesTracker[key]) {
    //                             quantitiesTracker[key] -= qty_unpack;
    //                         } else {
    //                             quantitiesTracker[key] = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) - qty_unpack : parseInt($(`#total_qty-${rowIndex}`).val(), 10) - qty_unpack;
    //                         }
    //                     }
    //                 }
    //             });

    //             // Decrease the stock_on_hand based on the total unpacked quantity
    //             let currentStock = parseFloat($('#stock_on_hand').val());
    //             if (!isNaN(currentStock)) {
    //                 let newStock = currentStock - totalQty;
    //                 $('#stock_on_hand').val(formatNumber(newStock));
    //             }

    //             // Send the collected data via AJAX
    //             $.ajax({
    //                 url: '<?= base_url('production/AddMaterialRequest'); ?>',
    //                 type: 'POST',
    //                 data: {
    //                     materialData: materialData
    //                 },
    //                 success: function (result) {
    //                     // Update total_qty_get with accumulated qty_unpack
    //                     $('#total_qty_get').val(formatNumber(totalQty));

    //                     // Make inputs and buttons readonly and disabled
    //                     $(".rows").each(function () {
    //                         let id = $(this).attr('id');
    //                         if (id) {
    //                             let rowIndex = id.split('-')[1];
    //                             $(`#sloc-${rowIndex}`).prop('disabled', true);
    //                             $(`#box_no-${rowIndex}`).prop('disabled', true);
    //                             $(`#qty_unpack-${rowIndex}`).prop('readonly', true);
    //                             $(`#submit-btn-${rowIndex}`).prop('disabled', true);
    //                         }
    //                     });

    //                     console.log(materialData);
    //                 },
    //                 error: function (xhr, ajaxOptions, thrownError) {
    //                     console.log(thrownError);
    //                 }
    //             });
    //         });
    //     }

    //     // Function to add a new row
    //     function addNewRow(res) {
    //         rowIndex++;
    //         const rowHtml = `
    //             <div class="row rows" id="rows-${rowIndex}">
    //                 <div class="col-md-3">
    //                     <label for="sloc-${rowIndex}" class="form-label"><b>SLoc</b></label>
    //                     <select id="sloc-${rowIndex}" name="sloc[${rowIndex}]" class="form-select">
    //                     </select>
    //                 </div>
    //                 <div class="col-md-3">
    //                     <label for="box_no-${rowIndex}" class="form-label"><b>Box</b></label>
    //                     <select id="box_no-${rowIndex}" name="box_no[${rowIndex}]" class="form-select">
    //                     </select>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="total_qty-${rowIndex}" class="form-label"><b>Qty on hand</b></label>
    //                     <input type="text" class="form-control" id="total_qty-${rowIndex}" name="total_qty[${rowIndex}]" readonly>
    //                 </div>
    //                 <div class="col-md-2">
    //                     <label for="qty_unpack-${rowIndex}" class="form-label"><b>Unpack</b></label>
    //                     <input type="number" min="${isFloatUom ? '0.1' : '1'}" step="${isFloatUom ? '0.1' : '1'}" class="form-control" id="qty_unpack-${rowIndex}" name="qty_unpack[${rowIndex}]">
    //                 </div>
    //                 <div class="col-md-2">
    //                     <button class="btn btn-primary submit-row-btn" id="submit-btn-${rowIndex}" type="button" style="margin-top: 2rem;">
    //                         <i class="bx bx-check-circle"></i>
    //                     </button>
    //                 </div>
    //             </div>
    //         `;

    //         $('#dynamic-rows-container').append(rowHtml);

    //         // Initialize select2 for the newly added select elements
    //         $(`#sloc-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });
    //         $(`#box_no-${rowIndex}`).select2({
    //             dropdownParent: $('#addMaterialRequest')
    //         });

    //         let slocBoxes = {};
    //         for (let i = 0; i < res.length; i++) {
    //             let sloc = res[i].sloc;
    //             let box = res[i].no_box;
    //             let totalQty = res[i].total_qty_real !== "0" ? res[i].total_qty_real : res[i].total_qty;

    //             if (!slocBoxes[sloc]) {
    //                 slocBoxes[sloc] = [];
    //             }
    //             slocBoxes[sloc].push({ box: box, totalQty: totalQty });
    //         }

    //         // Populate SLoc dropdown
    //         $(`#sloc-${rowIndex}`).append('<option value="" selected>Choose SLoc</option>');
    //         for (let sloc in slocBoxes) {
    //             $(`#sloc-${rowIndex}`).append(`<option value="${sloc}">${sloc}</option>`);
    //         }

    //         // Update Box options based on selected SLoc
    //         $(`#sloc-${rowIndex}`).on('change', function () {
    //             let selectedSLoc = $(this).val();
    //             $(`#box_no-${rowIndex}`).empty().append('<option value="" selected>Choose Box</option>');

    //             if (slocBoxes[selectedSLoc]) {
    //                 slocBoxes[selectedSLoc].forEach(item => {
    //                     $(`#box_no-${rowIndex}`).append(`<option value="${item.box}" data-total_qty="${item.totalQty}">${item.box}</option>`);
    //                 });
    //             }

    //             $(`#total_qty-${rowIndex}`).val(''); // Clear total_qty on SLoc change
    //         });

    //         // Update total_qty based on selected Box
    //         $(`#box_no-${rowIndex}`).on('change', function () {
    //             let selectedOption = $(this).find('option:selected');
    //             let selectedTotalQty = parseFloat(selectedOption.data('total_qty'));

    //             if (isNaN(selectedTotalQty)) {
    //                 selectedTotalQty = 0;
    //             }

    //             let key = `${$(`#sloc-${rowIndex}`).val()}-${$(this).val()}`;
    //             if (quantitiesTracker[key] !== undefined) {
    //                 selectedTotalQty = quantitiesTracker[key];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(formatNumber(selectedTotalQty));
    //         });

    //         // Initialize total_qty based on initial selection
    //         let initialSLoc = $(`#sloc-${rowIndex}`).val();
    //         if (initialSLoc && slocBoxes[initialSLoc]) {
    //             let initialBox = $(`#box_no-${rowIndex}`).val();
    //             let initialTotalQty = slocBoxes[initialSLoc].find(item => item.box === initialBox)?.totalQty || 0;
    //             let initialKey = `${initialSLoc}-${initialBox}`;
    //             if (quantitiesTracker[initialKey] !== undefined) {
    //                 initialTotalQty = quantitiesTracker[initialKey];
    //             }
    //             $(`#total_qty-${rowIndex}`).val(formatNumber(initialTotalQty));
    //         }
    //     }

    //     // Handle click event for adding new rows
    //     $(document).on('click', '.edit-material-request', function () {
    //         var $row = $(this).closest('tr');
    //         var materialId = $row.data('id');
    //         var materialDesc = $row.data('desc');
    //         var materialNeed = $row.data('qty');
    //         var uom = $row.data('uom');

    //         $('#material_id').val(materialId);
    //         $('#material_desc').val(materialDesc);
    //         $('#material_need').val(materialNeed);
    //         $('#uom').val(uom);

    //         // Check if Uom is not "PC"
    //         isFloatUom = (uom !== "PC");

    //         $.ajax({
    //             url: '<?= base_url('production/getSlocStorage'); ?>',
    //             type: 'post',
    //             dataType: 'json',
    //             data: {
    //                 materialId: materialId
    //             },
    //             success: function (res) {
    //                 if (res) {
    //                     console.log(res);
    //                     // ADD INPUT STOCK ON HAND
    //                     var stock_on_hand = 0;
    //                     for (let i = 0; i < res.length; i++) {
    //                         stock_on_hand += parseFloat(res[i].total_qty_real != res[i].total_qty ? res[i].total_qty_real : res[i].total_qty);
    //                     }
    //                     $('#stock_on_hand').val(formatNumber(stock_on_hand));

    //                     // Ensure event handler for adding new rows is only added once
    //                     $('#plus-row').off('click').on('click', function () {
    //                         addNewRow(res);
    //                     });

    //                     // Ensure modal hidden event is handled properly
    //                     $('#addMaterialRequest').off('hidden.bs.modal').on('hidden.bs.modal', function () {
    //                         $('#dynamic-rows-container').empty(); // Clear all dynamic rows when the modal is hidden
    //                         rowIndex = 0; // Reset rowIndex when the modal is hidden
    //                     });
    //                 } else {
    //                     $('#stock_on_hand').val(0);
    //                 }
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //                 console.error('AJAX Error:', thrownError); // Log any errors
    //             }
    //         });
    //     });

    //     // Handle click event for row submit buttons
    //     $(document).on('click', '.submit-row-btn', function () {
    //         let id = $(this).closest('.rows').attr('id');
    //         if (id) {
    //             let rowIndex = id.split('-')[1];
    //             let sloc = $(`#sloc-${rowIndex}`).val();
    //             let box_no = $(`#box_no-${rowIndex}`).val();
    //             let qty_unpack = isFloatUom ? parseFloat($(`#qty_unpack-${rowIndex}`).val()) : parseInt($(`#qty_unpack-${rowIndex}`).val(), 10);
    //             let total_qty = isFloatUom ? parseFloat($(`#total_qty-${rowIndex}`).val()) : parseInt($(`#total_qty-${rowIndex}`).val(), 10);

    //             // Validate that sloc and box_no are selected
    //             if (!sloc || !box_no) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Select <strong>SLoc</strong> and <strong>Box</strong> before submitting<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Validate that qty_unpack does not exceed total_qty
    //             if (qty_unpack > total_qty) {
    //                 $('#datas-modals').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 100%"><i class="bi bi-x-circle me-1"></i>Unpacked quantity cannot exceed quantity on hand<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');

    //                 setTimeout(function() {
    //                     $('#datas-modals .alert').alert('close');
    //                 }, 3000);

    //                 return;
    //             }

    //             // Update the display for quantity requested
    //             $(`#qty_request-${rowIndex}`).text(qty_unpack);

    //             // Enable the submit button for the current row
    //             $(`#submit-btn-${rowIndex}`).prop('disabled', false);
    //         }
    //     });

    //     // Initialize form submission handler
    //     SubmitMaterialReq();
    // });


























</script>