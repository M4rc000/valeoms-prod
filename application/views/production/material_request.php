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
    // GET USER
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
                            <label for="stock_on_hand" class="form-label"><b>Stock on hand</b></label>
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
                            <button class="btn btn-success">
                                <i class="bi bi-plus-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row mt-2 ps-2 px-2">
                        <div class="col-md-2">
                            <label for="sloc" class="form-label"><b>SLoc</b></label>
                            <select id="sloc" class="form-select">
                                <option selected>Choose SLoc</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="box_no" class="form-label"><b>Box</b></label>
                            <select id="box_no" class="form-select">
                                <option selected>Choose Box</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="total_qty" class="form-label"><b>Qty on hand</b></label>
                            <input type="text" class="form-control" id="total_qty" name="total_qty" readonly>
                        </div>
                        <div class="col-md-2">
                            <label for="qty_unpack" class="form-label"><b>Unpack</b></label>
                            <input type="number" min="1" class="form-control" id="qty_unpack" name="qty_unpack">
                        </div>
                        <div class="col-md-2">
                            <Button class="btn btn-primary" style="margin-top: 2rem;">
                                <i class="bx bx-check-circle"></i>
                            </Button>
                        </div>
                        <div class="col-md-2">
                            <label for="qty_request" class="form-label"><b>Qty request</b></label>
                            <input type="number" min="1" class="form-control" id="qty_request" name="qty_request">
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
        var productID = $('#product_id').val();
        var productDesc = $('#productDescription').val();
        var qty = $('#qty').val();
        var user = $('#user').val();

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
                            <td class="text-center">
                                <input type="checkbox" name="" id="">
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

                new DataTable('#tbl-bom');
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
    //                 console.log(res);

    //                 // ADD INPUT STOCK ON HAND
    //                 for (var i = 0; i < res.length; i++) {
    //                     var stock_on_hand = 0;
    //                     stock_on_hand+= parseInt(res[i].total_qty);
    //                 }
    //                 $('#stock_on_hand').val(stock_on_hand);
                    
    //                 // ADD OPTION SLOC
    //                 $('#sloc').empty().append('<option selected>Choose SLoc</option>');

    //                 // LOOPING SLOC
    //                 for (var i = 0; i < res.length; i++) {
    //                     var sloc = res[i].sloc;
    //                     $('#sloc').append('<option value="' + sloc + '" data-total_qty="' + res[i].total_qty + '">' + sloc + '</option>');
    //                 }

    //                 // LOOPING BOX NO
    //                 for (var i = 0; i < res.length; i++) {
    //                     var box = res[i].no_box;
    //                     $('#box_no').append('<option value="' + box + '" data-total_qty="' + res[i].total_qty + '">' + box + '</option>');
    //                 }


    //                 // Add change event listener to update total_qty based on selected SLoc
    //                 $('#sloc').on('change', function () {
    //                     var selectedOption = $(this).find('option:selected');
    //                     var selectedTotalQty = selectedOption.data('total_qty');
    //                     $('#total_qty').val(selectedTotalQty);
    //                 });

    //                 // Set initial total_qty based on the first SLoc in the list if needed
    //                 var initialTotalQty = $('#sloc').find('option:selected').data('total_qty');
    //                 $('#total_qty').val(initialTotalQty);
    //             }
    //             else{
    //                 var stock_on_hand = 0;
    //                 $('#stock_on_hand').val(stock_on_hand);
    //             }
    //         },
    //         error: function(xhr, ajaxOptions, thrownError) {
    //             console.error('AJAX Error:', thrownError); // Log any errors
    //         }
    //     });
    // });
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

        $('#addMaterialRequest').on('shown.bs.modal', function () {
            $('#sloc').select2({
                dropdownParent: $('#addMaterialRequest')
            });
            $('#box_no').select2({
                dropdownParent: $('#addMaterialRequest')
            });
        });

        $.ajax({
            url: '<?= base_url('production/getSlocStorage'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                materialId: materialId
            },
            success: function(res) {
                if (res && res.length > 0) {
                    console.log(res);

                    // ADD INPUT STOCK ON HAND
                    var stock_on_hand = 0;
                    for (var i = 0; i < res.length; i++) {
                        stock_on_hand += parseInt(res[i].total_qty);
                    }
                    $('#stock_on_hand').val(stock_on_hand);
                    
                    // ADD OPTION SLOC
                    $('#sloc').empty().append('<option selected>Choose SLoc</option>');
                    for (var i = 0; i < res.length; i++) {
                        var sloc = res[i].sloc;
                        $('#sloc').append('<option value="' + sloc + '">' + sloc + '</option>');
                    }

                    // ADD OPTION BOX NO
                    $('#box_no').empty().append('<option selected>Choose Box No</option>');
                    for (var i = 0; i < res.length; i++) {
                        var box = res[i].no_box;
                        $('#box_no').append('<option value="' + box + '" data-total_qty="' + res[i].total_qty + '">' + box + '</option>');
                    }

                    // Update total_qty based on selected SLoc
                    $('#sloc').on('change', function () {
                        var selectedOption = $(this).find('option:selected');
                        var selectedTotalQty = selectedOption.data('total_qty');
                        $('#total_qty').val(selectedTotalQty);
                    });

                    // Update total_qty based on selected Box No
                    $('#box_no').on('change', function () {
                        var selectedOption = $(this).find('option:selected');
                        var selectedTotalQty = selectedOption.data('total_qty');
                        $('#total_qty').val(selectedTotalQty);
                    });

                    // Set initial total_qty based on the first SLoc in the list if needed
                    var initialTotalQty = $('#sloc').find('option:selected').data('total_qty');
                    $('#total_qty').val(initialTotalQty);
                } else {
                    $('#stock_on_hand').val(0);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error('AJAX Error:', thrownError); // Log any errors
            }
        });
    });

    $(document).ready(function (){
        $('#product_id').select2();
    });
</script>