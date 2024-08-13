<style>
	.select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>

<section style="font-family: Nunito;">
	<div class="row">
		<div class="col-lg-12">
			<div class="card" style="height: 2500px;">
				<div class="card-body">
					<div class="row mt-3">
						<div class="col-md">
							<!-- Default Tabs -->
							<ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
								<li class="nav-item flex-fill" role="presentation">
									<button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
										data-bs-target="#home-justified" type="button" role="tab" aria-controls="home"
										aria-selected="true"><i class="bi bi-file-earmark-ruled-fill me-3"
											style="color: #012970"></i>
										FG</button>
								</li>
								<li class="nav-item flex-fill" role="presentation">
									<button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
										data-bs-target="#profile-justified" type="button" role="tab"
										aria-controls="profile" aria-selected="false"><i
											class="bi bi-file-earmark-plus-fill me-2" style="color: #012970"></i> New
										Product FG</button>
								</li>
							</ul>
							<div class="tab-content pt-2" id="myTabjustifiedContent">
								<div class="tab-pane fade show active" id="home-justified" role="tabpanel"
									aria-labelledby="home-tab">
									<div class="row mt-4 mb-2 ">
										<div class="col-md">
											<div class="col-md-2">
												<button class="btn btn-primary" data-bs-toggle="modal"
													data-bs-target="#addMaterialBOM"><i
														class="bi bi-journal-plus mx-1 me-2"></i> BOM</button>
											</div>
										</div>
									</div>
									<div class="row justify-content-center mt-4 me-0 ms-0 mb-5">
										<div class="col-12 col-md-2 mb-3 mb-md-0">
											<label for="id_product" class="col-form-label">
												<b>Finnish Good ID</b>
											</label>
										</div>
										<div class="col-12 col-md-5 mb-3 mb-md-0">
											<select class="form-select" id="id_product" name="id_product" required>
												<option value="" selected>Select Product Fg ID</option>
												<?php foreach($bom_distint as $bd): ?>
												<option value="<?=$bd['Id_fg']?>"><?=$bd['Id_fg'];?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-12 col-md-3">
											<button class="btn btn-success w-100" onclick="getBomList()">Search</button>
										</div>
									</div>
									<hr>
									<div class="row">
										<div id="data"></div>
									</div>
								</div>
								<div class="tab-pane fade" id="profile-justified" role="tabpanel"
									aria-labelledby="profile-tab">
               	                 	<?= form_open_multipart('master/addNewBom'); ?>
										<!-- GET USER -->
										<input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
										<div class="row mt-4 mb-4 mx-2">
											<div class="col-12 col-md-6 mb-3 mb-md-0">
												<label for="products_id" class="form-label"><b>Product ID</b></label>
												<input type="text" class="form-control" id="products_id" name="products_id" required>
											</div>
											<div class="col-12 col-md-6">
												<label for="product_desc" class="form-label"><b>Product Description</b></label>
												<input type="text" class="form-control" id="product_desc" name="product_desc" required>
											</div>
										</div>
										<hr>
										<div class="row mb-3 mx-2">
											<div class="col-md-3">
												<button type="button" class="btn btn-primary" id="add-row-btn">
													<i class="bi bi-plus-circle"></i>
												</button>
											</div>
										</div>
										<div class="row mt-3 mx-2">
											<div class="col-md">
													<div class="table-responsive">
														<table id="bomTable" class="table table-bordered">
															<thead>
																<tr>
																	<th class="text-center">#</th>
																	<th class="text-center">Material Part No</th>
																	<th class="text-center">Material Part Description</th>
																	<th class="text-center">Material Type</th>
																	<th class="text-center">Qty</th>
																	<th class="text-center">Uom</th>
																	<th class="text-center">Action</th>
																</tr>
															</thead>
															<tbody id="table-body"></tbody>
														</table>
													</div>
											</div>
										</div>
										<div class="row mt-3 mx-2">
											<div class="col-md text-end">
												<button type="submit" class="btn btn-success">
													Submit
												</button>
											</div>
										</div>
                                	</form>                        
								</div>
							</div><!-- End Default Tabs -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>


<!-- ADD Material MODAL -->
<div class="modal fade" id="addMaterialBOM" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?= form_open_multipart('master/AddMaterialBom'); ?>
            <div class="modal-header">
                <h5 class="modal-title">Add Material's BOM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- GET USER -->
                <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
                <div class="row ps-2 mb-3">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label for="product_id" class="form-label">Finnish Good ID</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="" selected>Select Product Fg ID</option>
                            <?php foreach($bom_distint as $bd): ?>
                            <option value="<?=$bd['Id_fg']?>"><?=$bd['Id_fg'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label for="fg_desc" class="form-label">Finnish Good Description</label>
                        <input type="text" class="form-control" id="fg_desc" name="fg_desc" readonly required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="material_id" class="form-label">Material Part No</label>
                        <select class="form-select" id="material_id" name="material_id" required>
                            <option value="" selected>Select Material ID</option>
                            <?php foreach($materials as $mtr): ?>
                            <option value="<?=$mtr['Id_material']?>"><?=$mtr['Id_material'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row ps-2 mt-3">
                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                        <label for="material_desc" class="form-label">Material Part Name</label>
                        <input type="text" class="form-control" id="material_desc" name="material_desc" readonly required>
                    </div>
                    <div class="col-12 col-md-3 mb-3 mb-md-0">
                        <label for="material_type" class="form-label">Material Type</label>
                        <input type="text" class="form-control" id="material_type" name="material_type" readonly>
                    </div>
                    <div class="col-12 col-md-3 mb-3 mb-md-0">
                        <label for="qty" class="form-label">Qty</label>
                        <input type="text" class="form-control" id="qty" name="qty" required>
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="uom" class="form-label">Uom</label>
                        <input type="text" class="form-control" id="uom" name="uom" readonly>
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


<script>
	$(document).ready(function () {
		$('#id_product').select2({
			width: '100%'
		}).on('select2:open', function() {
			$('.select2-container--open').css('z-index', '99');
		});

		$('#addMaterialBOM').on('shown.bs.modal', function () {
			// Clear all input fields inside the modal
			$('#material_id').val('');
			$('#product_id').val('');
			$('#fg_desc').val('');
			$('#material_desc').val('');
			$('#material_type').val('');
			$(this).find('#qty').val('');
			$('#uom').val('');

			$('#material_id').select2({
				dropdownParent: $('#addMaterialBOM')
			});

			$('#product_id').select2({
				dropdownParent: $('#addMaterialBOM')
			});

			$('#product_id').on('change', function () {
				var productId = $(this).val();

				$.ajax({
					url: '<?= base_url('master/getProductDesc'); ?>',
					type: 'post',
					dataType: 'json',
					data: {
						productId
					},
					success: function (res) {
						var productDesc = res[0].Fg_desc;
						$('#fg_desc').val(productDesc);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						console.error(xhr.statusText);
					}
				});
			});

			$('#material_id').on('change', function () {
				var materialID = $(this).val();

				$.ajax({
					url: '<?= base_url('master/getMaterialDesc'); ?>',
					type: 'post',
					dataType: 'json',
					data: {
						materialID
					},
					success: function (res) {
						var materialDesc = res[0].Material_desc;
						var materialType = res[0].Material_type;
						var uom = res[0].Uom;

						$('#material_desc').val(materialDesc);
						$('#material_type').val(materialType);
						$('#uom').val(uom);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						console.error(xhr.statusText);
					}
				});
			});
		});

		// Add new row on button click
		let rowIndex = 1;

        $('#add-row-btn').click(function() {
            addRow();
        });

        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('tr').remove();
            updateRowIndices();
        });

        function addRow() {
			var material_list = <?= json_encode($materials);?>;
            let materialOptions = '<option value="" selected>Select Material Part No</option>';
            material_list.forEach(ml => {
                materialOptions += `<option value="${ml.Id_material}">${ml.Id_material}</option>`;
            });

            const newRow = `
                <tr>
                    <td class="py-3"><b>${rowIndex}</b></td>
                    <td>
						<select class="form-select material-select" name="materials[${(rowIndex-1)}][material_id]" required>
                            ${materialOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control material-desc" name="materials[${rowIndex}][material_desc]" aria-label="Material Description" style="width: 300px;" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control material-type text-center" name="materials[${rowIndex}][material_type]" aria-label="Material Type" style="width: 120px;" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control material-qty" name="materials[${rowIndex}][qty]" aria-label="Quantity" style="width: 100px;" required>
                    </td>
                    <td>
                        <input type="text" class="form-control material-uom text-center" name="materials[${rowIndex}][uom]" aria-label="Unit of Measure" style="width: 100px;" readonly>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-remove-row" type="button" aria-label="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#table-body').append(newRow);
			updateRowIndices();
			$('.material-select').select2({
                width: '100%'
            });
            
			
			$('.material-select').last().change(function() {
				const selectedMaterialId = $(this).val();
				const selectedMaterial = material_list.find(ml => ml.Id_material == selectedMaterialId);
				console.log("Material Selected: " + selectedMaterial);
				
				if (selectedMaterial) {
					$(this).closest('tr').find('.material-desc').val(selectedMaterial.Material_desc);
					$(this).closest('tr').find('.material-type').val(selectedMaterial.Material_type); 
					$(this).closest('tr').find('.material-uom').val(selectedMaterial.Uom);
				}
			});
			rowIndex++;
        }

        function updateRowIndices() {
            $('#table-body tr').each(function(index) {
                $(this).find('td:first-child b').text(index + 1);
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    $(this).attr('name', newName);
                });
            });
            rowIndex = $('#table-body tr').length;
        }
	});
    
    function getBomList() {
        var Id_product = $('#id_product').val();

        $.ajax({
            url: '<?= base_url('master/getBomList'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                Id_product
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
            success: function (res) {
                if (res.length > 0) {
                    let rows = '';
                    for (let number = 0; number < res.length; number++) {
                        rows += `
                        <tr data-id="${res[number].Id_bom}" data-Idmaterial="${res[number].Id_material}" data-Materialdesc="${res[number].Material_desc}" data-Qty="${res[number].Qty}" data-Uom="${res[number].Uom}">
							<td><input type="checkbox" class="form-check-input"></td>
                            <td>${res[number].Id_material}</td>
                            <td>${res[number].Material_desc}</td>
                            <td class="text-center">${res[number].Material_type}</td>
                            <td class="text-center">${res[number].Qty}</td>
                            <td class="text-center">${res[number].Uom}</td>
                            <td class="text-center">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#editModal${res[number].Id_material}">
                                    <span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
                                </a>
                            </td>
                        </tr>
                    `;
                    }

                    var htmlContent = `
						<div class="table-responsive">
							<table class="table table-bordered mt-3" id="table-content" style="width: 100%">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="text-center">Material ID</th>
										<th class="text-center">Material Description</th>
										<th class="text-center">Material Type</th>
										<th class="text-center">Qty</th>
										<th class="text-center">Uom</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									${rows}
								</tbody>
							</table>
						</div>

						<div class="row mt-2">
							<div class="col-md text-start">
								<button type="button" class="btn btn-danger" id="select-delete"><i class="bi bi-trash2"></i></button>
							</div>
						</div>
					`;

                    $('#data').empty().append(htmlContent);
                    var table = $('#table-content').DataTable({
						"pageLength": 10
					});

					$('#select-delete').on('click', function() {
						var user = $('#user').val();
						// Collect all checked checkboxes
						let selectedItems = [];
						$('#table-content tbody input[type="checkbox"]:checked').each(function() {
							let id = $(this).closest('tr').data('id');
							let Id_material = $(this).closest('tr').data('idmaterial'); 
							let Material_desc = $(this).closest('tr').data('materialdesc');
							let Qty = $(this).closest('tr').data('qty');
							let Uom = $(this).closest('tr').data('uom');

							selectedItems.push({ id: id, Id_Material: Id_material, Material_desc: Material_desc, Qty: Qty, Uom: Uom });
						});

						if (selectedItems.length > 0) {

							// Generate table rows for each selected item
							var number = 0;
							let tableRows = selectedItems.map(item => 
								`<tr>
									<td class="text-center">${++number}</td>
									<td class="text-center">${item.Id_Material}</td>
									<td class="text-start">${item.Material_desc}</td>
									<td class="text-center">${item.Qty}</td>
									<td class="text-center">${item.Uom}</td>
								</tr>`
							).join('');

							let tableHTML = `
								<div class="table-responsive">
									<table class="table table-bordered" id="tbl-selectedItems">
										<thead>
											<tr>
												<th class="text-center">#</th>
												<th class="text-center">Material Part No</th>
												<th class="text-center">Material Part Name</th>
												<th class="text-center">Qty</th>
												<th class="text-center">Uom</th>
											</tr>
										</thead>
										<tbody>
											${tableRows}
										</tbody>
									</table>
								</div>`;



							Swal.fire({
								title: "Are you sure?",
								html: tableHTML,
								width: 900,
								icon: "question",
								showCancelButton: true,
								confirmButtonColor: "#3085d6",
								cancelButtonColor: "#d33",
								confirmButtonText: "Yes, delete it!"
								}).then((result) => {
								if (result.isConfirmed) {
									$.ajax({
										url: '<?=base_url('master/deleteMultipleMaterialBom');?>',
										type: 'POST',
										data: { selectedItems, user },
										success: function(res) {
											if(res == 1){
												$('#table-content').DataTable().destroy();

												selectedItems.forEach(function(item) {
													$('#table-content tbody tr[data-id="' + item.id + '"]').remove();
												});

												$('#table-content').DataTable();
												
												Swal.fire({
													title: "Success",
													html: `Data Material's Bom have been successfully deleted`,
													icon: "success"
												});		
											}
											else{
												Swal.fire({
													title: "Error",
													html: `Failed to delete material's bom`,
													icon: "error"
												});		
											}
										},
										error: function(xhr, status, error) {
											console.error('Error deleting records:', error);
										}
									});
								}
							});
						} else {
							Swal.fire({
								title: "Error",
								html: `Please select at least one data`,
								icon: "error"
							});
						}
					});

                    let modalEdit = '';
                    for (let number = 0; number < res.length; number++) {
                        modalEdit += `
							<div class="modal fade" id="editModal${res[number].Id_material}" tabindex="-1">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<?= form_open_multipart('master/EditBomMaterial'); ?>
											<div class="modal-header">
												<h5 class="modal-title">Edit Bom's Material</h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden> 
												<input type="text" class="form-control" id="id" name="id" value="${res[number].Id_bom}" hidden> 
												<input type="text" class="form-control" id="id_fg" name="id_fg" value="${res[number].Id_fg}" hidden> 
												<div class="row ps-2">
													<div class="col-4">
														<label for="material_id" class="form-label">Material ID</label>
														<input type="text" class="form-control" id="material_id" name="material_id" value="${res[number].Id_material}">
													</div>
													<div class="col-4">
														<label for="material_desc" class="form-label">Material Description</label>
														<input type="text" class="form-control" id="material_desc" name="material_desc" value="${res[number].Material_desc}">
													</div>
													<div class="col-4">
														<label for="material_type" class="form-label">Material Type</label>
														<input type="text" class="form-control" id="material_type" name="material_type" value="${res[number].Material_type}">
													</div>
												</div>
												<div class="row mt-4 ps-2">
													<div class="col-4">
														<label for="qty" class="form-label">Qty</label>
														<input type="text" class="form-control" id="qty" name="qty" value="${res[number].Qty}">
													</div>
													<div class="col-4">
														<label for="uom" class="form-label">Uom</label>
														<input type="text" class="form-control" id="uom" name="uom" value="${res[number].Uom}">
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
						`;
                    }
                    $('body').append(modalEdit);

                    let modalDelete = '';
                    for (let number = 0; number < res.length; number++) {
                        modalDelete += `
							<?= form_open_multipart('master/deleteMaterialBom'); ?>
								<div class="modal fade" id="deleteModal${res[number].Id_material}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete?</h4>
											</div>
											<div class="modal-body">
												<input type="text" name="id" id="id" value="${res[number].Id_bom}" hidden>
												<input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
												<p><b>Material ID</b>: ${res[number].Id_material}</p>
												<p><b>Material Description</b>: ${res[number].Material_desc}</p>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
												<button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
											</div>
										</div>
									</div>
								</div>
							</form>
						`;
                    }
                    $('body').append(modalDelete);
                } else {
                    $('#data').html(`
						<div class="row mt-5">
							<div class="col-md">
								<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%">
									<i class="bi bi-x-circle me-1"></i>Product ID not found
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							</div>
						</div>
					`);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
            }
        });
    }
</script>



<!-- SWEET ALERT -->
<?php if ($this->session->flashdata('duplicate_add_new_bom')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Warning",
                html: `Bom <b><?=$this->session->flashdata('duplicate_add_new_bom');?></b> already exist`,
                icon: "warning"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('success_add_new_bom')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: `New Bom <b><?=$this->session->flashdata('success_add_new_bom');?></b> has been successfully added`,
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('failed_add_new_bom')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: `Failed to add new BOM <b><?=$this->session->flashdata('failed_add_new_bom');?></b>`,
                icon: "error"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('success_AddMaterialBom')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: `<?=$this->session->flashdata('success_AddMaterialBom');?>`,
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('failed_AddMaterialBom')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: `<?=$this->session->flashdata('failed_AddMaterialBom');?>`,
                icon: "error"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('success_EditBomMaterial')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: `<?=$this->session->flashdata('success_EditBomMaterial');?>`,
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('failed_EditBomMaterial')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: `<?=$this->session->flashdata('failed_EditBomMaterial');?>`,
                icon: "error"
            });
        });
    </script>
<?php endif; ?>