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
	<div class="row">
		<div class="card info-card" style="height: 2500px;">
			<div class="card-body">
                <?php if ($this->session->flashdata('Error') != '') { ?>
                    <?= $this->session->flashdata('Error'); ?>
                <?php } ?>
                <div class="row justify-content-center mt-5 gap-1">
                    <label for="product_id" class="col-sm-3 col-form-label"><b>Product ID</b></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="product_id">
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
<div class="modal fade" id="addMaterialRequest" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="production/AddMaterialRequest" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Add Material Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- GET USER -->
                    <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
                    <div class="row ps-2 mb-3 px-2">
                        <div class="col-md-3">
                            <label for="material_id" class="form-label"><b>Material Part No</b></label>
                            <input type="text" class="form-control" id="material_id" name="material_id" readonly>
                        </div>
                        <div class="col-5">
                            <label for="material_desc" class="form-label"><b>Material Part Name</b></label>
                            <input type="text" class="form-control" id="material_desc" name="material_desc" readonly required>
                        </div>
                        <div class="col-2">
                            <label for="material_need" class="form-label"><b>Material Need</b></label>
                            <input type="text" class="form-control" id="material_need" name="material_need" readonly>
                        </div>
                        <div class="col-2">
                            <label for="uom" class="form-label"><b>Uom</b></label>
                            <input type="text" class="form-control" id="uom" name="uom" readonly>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div class="row mt-2 ps-2 px-2">
                        <div class="col-2">
                            <label for="sloc" class="form-label"><b>SLoc</b></label>
                            <select id="sloc" class="form-select">
                                <option selected>Choose SLoc</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <label for="total_qty" class="form-label"><b>Qty</b></label>
                            <input type="text" class="form-control" id="total_qty" name="total_qty" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
                productID: productID // Corrected object key-value pair
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
                    $('#data').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> Product ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                // Handle AJAX error
                console.error(xhr.statusText);
            }
        });
    }

    // MENDAPATKAN DATA BOM
    function calculateData(){
        var qty = $('#qty').val();
        var productID = $('#product_id').val();

		$.ajax({
			url: '<?= base_url('production/getProductData'); ?>',
			type: 'post',
			dataType: 'json',
			data: {
				productID
			},
			success: function(res) {
                var row = '';
                for (let number = 0; number < res.length; number++) {
                    row +=
                    `
                        <tr data-id="${res[number].Id_material}" data-desc="${res[number].Material_desc}" data-qty="${res[number].Qty * qty}" data-uom="${res[number].Uom}">
                            <th scope="row">${number + 1}</th>
                            <td>${res[number].Id_material}</td>
                            <td>${res[number].Material_desc}</td>
                            <td class="text-center">${res[number].Qty * qty}</td>
                            <td class="text-center">${res[number].Uom}</td>
                            <td class="text-center">
                                <a href="#" class="edit-material-request" data-bs-toggle="modal" data-bs-target="#addMaterialRequest">
                                    <span class="badge bg-warning"><i class="bx bx-pencil"></i></span>
                                </a>
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
                        </tr>
                    </thead>
                    <tbody style="font-size: 13.5px" id="material-table-body">
                        ${row}
                    </tbody>
                </table>
                `;  

                $('#billofmaterial').empty().append(title);
                $('#billofmaterial').append(tableBom);
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

	// 	$.ajax({
	// 		url: '<?= base_url('production/getSlocStorage'); ?>',
	// 		type: 'post',
	// 		dataType: 'json',
	// 		data: {
	// 			materialId
	// 		},
	// 		success: function(res) {
    //             // MASUKAN VALUE KE MODAL
    //             if(res){
    //                 console.log(res);
    //                 console.log(sloc);
    //                 $('#material_id').val(materialId);
    //                 $('#material_desc').val(materialDesc);
    //                 $('#material_need').val(materialNeed);
    //                 $('#uom').val(uom);
    //                 $('#sloc').empty().append('<option selected>Choose SLoc</option>');
    //                 $('#sloc').append('<option value="'+ sloc +'">'+ sloc +'</option>');
                    
    //                 for (var i = 0; i < res.length; i++) {
    //                     var total_qty = res[i].total_qty;
    //                     var sloc = res[i].sloc;
    //                     $('#sloc').append('<option value="' + sloc + '">' + sloc + '</option>');
    //                     $('#total_qty').val(total_qty);
    //                 }

    //                 $('#addMaterialRequest').on('shown.bs.modal', function () {
    //                     $('#sloc').select2({
    //                         dropdownParent: $('#addMaterialRequest')
    //                     });
    //                 });
    //             }
    //         },
    //         error: function(xhr, ajaxOptions, thrownError) {
	// 			console.log(thrownError);
	// 		}
    //     });
    // });
    $(document).on('click', '.edit-material-request', function () {
        var $row = $(this).closest('tr');
        var materialId = $row.data('id');
        var materialDesc = $row.data('desc');
        var materialNeed = $row.data('qty');
        var uom = $row.data('uom');

        $.ajax({
            url: '<?= base_url('production/getSlocStorage'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                materialId: materialId // Explicitly specify key-value pair
            },
            success: function(res) {
                if (res && res.length > 0) {
                    console.log(res);
                    
                    $('#material_id').val(materialId);
                    $('#material_desc').val(materialDesc);
                    $('#material_need').val(materialNeed);
                    $('#uom').val(uom);

                    // Clear existing options
                    $('#sloc').empty().append('<option selected>Choose SLoc</option>');

                    // Append new options from res array
                    for (var i = 0; i < res.length; i++) {
                        var sloc = res[i].sloc;
                        $('#sloc').append('<option value="' + sloc + '" data-total_qty="' + res[i].total_qty + '">' + sloc + '</option>');
                    }

                    // Initialize Select2 on modal show
                    $('#addMaterialRequest').on('shown.bs.modal', function () {
                        $('#sloc').select2({
                            dropdownParent: $('#addMaterialRequest')
                        });
                    });

                    // Add change event listener to update total_qty based on selected SLoc
                    $('#sloc').on('change', function () {
                        var selectedOption = $(this).find('option:selected');
                        var selectedTotalQty = selectedOption.data('total_qty');
                        $('#total_qty').val(selectedTotalQty);
                    });

                    // Set initial total_qty based on the first SLoc in the list if needed
                    var initialTotalQty = $('#sloc').find('option:selected').data('total_qty');
                    $('#total_qty').val(initialTotalQty);
                } else {
                    console.error('Invalid response structure:', res);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error('AJAX Error:', thrownError); // Log any errors
            }
        });
    });
</script>