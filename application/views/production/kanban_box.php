<style>
	.kanban-card {
		border: 1px solid #B4B4B8;
		padding: 20px;
		width: 700px;
		margin: 20px auto;
		position: relative;
	}

	.kanban-card h3 {
		text-align: center;
		margin-bottom: 20px;
		text-decoration: underline;
	}

	.kanban-card .logo {
		position: absolute;
		top: 20px;
		left: 20px;
		width: 50px;
		height: 30px;
	}

	.kanban-card ul {
		list-style: none;
		padding: 0;
	}

	.kanban-card ul li {
		display: flex;
		align-items: center;
		padding: 5px 0;
	}

	.kanban-card ul li p {
		margin: 0;
	}

	.select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>
<section style="font-family: Nunito;">
	<div class="show" style="position: absolute; top: 12%; left: 70%; width: 70%; z-index: 999;">
		<?php if ($this->session->flashdata('ADD') != '') { ?>
			<?= $this->session->flashdata('ADD'); ?>
		<?php } ?>
		<?php if ($this->session->flashdata('ERROR') != '') { ?>
			<?= $this->session->flashdata('ERROR'); ?>
		<?php } ?>
		<?php if ($this->session->flashdata('EDIT') != '') { ?>
			<?= $this->session->flashdata('EDIT'); ?>
		<?php } ?>
		<?php if ($this->session->flashdata('DELETED') != '') { ?>
			<?= $this->session->flashdata('DELETED'); ?>
		<?php } ?>
	</div>
	<div class="row">
		<div class="card">
			<div class="card-body">
				<div class="row mt-3">
					<div class="col-md">
						<ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
							<li class="nav-item flex-fill" role="presentation">
								<button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
									data-bs-target="#home-justified" type="button" role="tab" aria-controls="home"
									aria-selected="true"><i class="bi bi-inbox-fill me-2"
										style="color: #012970"></i> New Kanban BOX</button>
							</li>
							<li class="nav-item flex-fill" role="presentation">
								<button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
									data-bs-target="#profile-justified" type="button" role="tab" aria-controls="profile"
									aria-selected="false"><i class="bi bi-inbox-fill me-2"
										style="color: #012970"></i> List Kanban BOX</button>
							</li>
						</ul>
						<div class="tab-content pt-2" id="myTabjustifiedContent">
							<div class="tab-pane fade show active" id="home-justified" role="tabpanel"
								aria-labelledby="home-tab">
								<?= form_open_multipart('production/AddKanbanBox'); ?>
								<div class="row mt-5 mx-3">
									<div class="col-3">
										<!-- GET USER -->
										<input type="text" class="form-control" id="user" name="user"
											value="<?=$name['username'];?>" hidden>
										<input type="text" class="form-control" id="kanbanBox_id" name="kanbanBox_id"
											value="<?= $kanban; ?>" readonly hidden>
										<label for="material_id" class="form-label"><b>Material Part No</b></label>
										<select class="form-select" id="material_id" name="material_id" required>
											<option value="" selected>Select Material Part No</option>
											<?php foreach($material_list as $ml): ?>
												<option value="<?=$ml['Id_material']?>"><?=$ml['Id_material'];?></option>
											<?php endforeach; ?>
										</select>
									</div>
									<div class="col-4">
										<label for="material_desc" class="form-label"><b>Material Part Name</b></label>
										<input type="text" class="form-control" id="material_desc" name="material_desc" required readonly>
									</div>
									<div class="col-2">
										<label for="qty" class="form-label"><b>Qty</b></label>
										<input type="text" class="form-control" id="qty" name="qty" required>
									</div>
									<div class="col-3">
										<label for="production_planning" class="form-label"><b>Production
												Planning</b></label>
										<input type="text" class="form-control" id="production_planning"
											name="production_planning" required>
									</div>
								</div>
								<div class="row mt-5 ms-5 justify-content-end mx-3" style="gap: 0;">
									<div class="col-md-6"></div>
									<div class="col-md-3 text-end">
										<button type="submit" class="btn btn-success"
											style="width: 150px">Submit</button>
									</div>
								</div>
								</form>
								<div class="row justify-content-center">
									<div class="preview mt-3 text-center"></div>
								</div>
							</div>
							<div class="tab-pane fade" id="profile-justified" role="tabpanel" aria-labelledby="profile-tab">
								<div class="table-responsive">
									<table class="table datatable mt-3">
										<thead>
										<tr>
											<th>#</th>
											<th>Production ID</th>
											<th>Material Part No</th>
											<th>Material Part Name</th>
											<th>Qty</th>
											<th>Production Plan No</th>
											<th>Action</th>
										</tr>
										</thead>
										<tbody>
											<?php $number = 0; foreach($kanbanlist as $kl): $number++ ?>
											<tr>
												<td><?=$number;?></td>
												<td><?=$kl['product_id'];?></td>
												<td><?=$kl['Id_material'];?></td>
												<td><?=$kl['Material_desc'];?></td>
												<td><?=$kl['Material_qty'];?></td>
												<td><?=$kl['Product_plan'];?></td>
												<td>
													<a href="#" data-bs-toggle="modal" data-bs-target="#barcode-modal<?=$kl['id_kanban_box'];?>">
														<span class="badge bg-success"><i class="bx bx-qr-scan" style="font-size: 12px"></i></span>
													</a>
													<a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?=$kl['id_kanban_box'];?>">
														<span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
													</a>
													<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$kl['id_kanban_box'];?>">
														<span class="badge bg-danger"><i class="bi bi-trash"></i></span>
													</a>
													<button class="btn btn-primary print-btn" data-id="<?=$kl['Id_material'];?>" data-description="<?=$kl['Material_desc'];?>" data-qty="<?=$kl['Material_qty'];?>" data-plan="<?=$kl['Product_plan'];?>" data-kanban="<?=$kl['id_kanban_box'];?>" style="width: 27px; height: 20px; align-items: center; justify-content: center; padding: 0;">
														<i class="bi bi-printer" style="line-height: 1;"></i>
													</button>
												</td>
											</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div><!-- End Default Tabs -->
				</div>
			</div>
		</div>
	</div>
	</div>
</section>

<!-- BARCODE MODAL -->
<?php $number = 0; foreach($kanbanlist as $kl): $number++ ?>
	<div class="modal fade" id="barcode-modal<?=$kl['id_kanban_box'];?>" tabindex="-1">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="bx bx-qr-scan me-2" style="font-size: 20px"></i>Kanban Box</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row justify-content-center mt-1 mb-2">
					<div class="col-md text-center">
						<strong><?=$kl['id_kanban_box'];?></strong>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="col-md text-center">
						<img src="<?=base_url('assets/img/kanban-barcode/').$kl['image']?>" alt="">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
			</div>
		</div>
	</div>
<?php endforeach; ?>

<!-- EDIT MODAL -->
<?php $number = 0; foreach($kanbanlist as $kl): $number++ ?>
<div class="modal fade" id="editModal<?=$kl['id_kanban_box'];?>" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?= form_open_multipart('master/EditBomMaterial'); ?>
				<div class="modal-header">
					<h5 class="modal-title">Edit Menu</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden> 
					<input type="text" class="form-control" id="id" name="id" value="${res[number].Id_bom}" hidden> 
					<input type="text" class="form-control" id="id_fg" name="id_fg" value="${res[number].Id_fg}" hidden> 
					<div class="row ps-2">
						<div class="col-4">
							<label for="id_kanban_box" class="form-label">Kanban Box</label>
							<input type="text" class="form-control" id="id_kanban_box" name="id_kanban_box" value="<?=$kl['id_kanban_box'];?>">
						</div>
						<div class="col-4">
							<label for="product_id" class="form-label">Product ID</label>
							<input type="text" class="form-control" id="product_id" name="product_id" value="<?=$kl['product_id'];?>">
						</div>
						<div class="col-4">
							<label for="material_id" class="form-label">Material Part No</label>
							<input type="text" class="form-control" id="material_id" name="material_id" value="<?=$kl['Id_material'];?>">
						</div>
					</div>
					<div class="row mt-4 ps-2">
						<div class="col-4">
							<label for="material_desc" class="form-label">Material Part Name</label>
							<input type="text" class="form-control" id="material_desc" name="material_desc" value="<?=$kl['Material_desc'];?>">
						</div>
						<div class="col-4">
							<label for="material_qty" class="form-label">Material Qty</label>
							<input type="text" class="form-control" id="material_qty" name="material_qty" value="<?=$kl['Material_qty'];?>">
						</div>
						<div class="col-4">
							<label for="product_plan" class="form-label">Product Plan</label>
							<input type="text" class="form-control" id="product_plan" name="product_plan" value="<?=$kl['Product_plan'];?>">
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
<?php endforeach; ?>

<!-- DELETE MODAL -->
<?php $number = 0; foreach($kanbanlist as $kl): $number++ ?>
<div class="modal fade" id="deleteModal<?=$kl['id_kanban_box'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete?</h4>
			</div>
			<div class="modal-body">
				<input type="text" name="id" id="id" value="${res[number].Id_bom}" hidden>
				<input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
				<p><b>Kanban Box</b>: <?=$kl['id_kanban_box'];?></p>
				<p><b>Material ID</b>: <?=$kl['Id_material'];?></p>
				<p><b>Material Description</b>: <?=$kl['Material_desc'];?></p>
				<p><b>Product ID</b>: <?=$kl['product_id'];?></p>
				<p><b>Product Plan</b>: <?=$kl['Product_plan'];?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
	$(document).ready(function (){
		$('#material_id').select2();

		function generateBarcode() {
			var id_kanban_box = "<?= isset($this->session->flashdata('kanban_data')['Id_kanban_box']) ? $this->session->flashdata('kanban_data')['Id_kanban_box'] : '' ?>";
			var materialId = "<?= isset($this->session->flashdata('kanban_data')['Id_material']) ? $this->session->flashdata('kanban_data')['Id_material'] : '' ?>";
			var materialDesc = "<?= isset($this->session->flashdata('kanban_data')['Material_desc']) ? $this->session->flashdata('kanban_data')['Material_desc'] : '' ?>";
			var qty = "<?= isset($this->session->flashdata('kanban_data')['Material_qty']) ? $this->session->flashdata('kanban_data')['Material_qty'] : '' ?>";
			var production_planning = "<?= isset($this->session->flashdata('kanban_data')['Product_plan']) ? $this->session->flashdata('kanban_data')['Product_plan'] : '' ?>";

			if (materialId.length == 0 || qty.length == 0 || production_planning.length == 0) {
				return false;
			} else {
				var htmlContent = 
				`
					<div class="kanban-card">
						<img src="<?=base_url('assets');?>/img/valeo-kanban-logo.png" alt="Logo" class="logo">
						<h3>KANBAN CARD</h3>
						<div class="row mt-5 me-0">
							<div class="col-md-8" style="font-size: 14px">
								<ul>
									<li>
										<p><b>Material Part No :</b> ${materialId}</p>
									</li>
									<li>
										<p><b>Material Part Name :</b> ${materialDesc}</p>
									</li>
									<li>
										<p><b>Material Qty :</b> ${qty}</p>
									</li>
									<li>
										<p><b>FG ID :</b> ${production_planning}</p>
									</li>
									<li>
										<p><b>Production Plan :</b> ${production_planning}</p>
									</li>
								</ul>
							</div>  
							<div class="col-md-4 text-center">
								<div class="ms-5" id="preview-barcode"></div>
							</div>
						</div>
					</div>
				`;

				htmlContent+=
				`
				<div class="offset-md-7 col-md-3 text-end">
					<button class="btn btn-warning" style="width: 100px; color: white" onclick="printKanbanCard()"><i class="bx bxs-printer me-2"></i>Print</button>
				</div>
				`;

				// Empty the preview element and append the new content
				$('.preview').empty().append(htmlContent);

				// Generate the QR code after the element is in the DOM
				var qrcode = new QRCode(document.getElementById("preview-barcode"), {
					text: `${id_kanban_box}`,
					width: 150,
					height: 150,
					correctLevel: QRCode.CorrectLevel.H
				});

				// Wait for the QR code to be rendered
				setTimeout(function() {
					var canvas = document.querySelector('#preview-barcode canvas');
					var imageData = canvas.toDataURL('image/png');

					// Send the image data to the server
					$.ajax({
						url: '<?= base_url('production/SaveBarcode') ?>',
						type: 'POST',
						data: {
							imageData,
							id_kanban_box
						},
						success: function(response) {
							console.log('QR code saved successfully');
						},
						error: function(err) {
							console.error('Error saving QR code:', err);
						}
					});
				}, 10); // Allow some time for the QR code to render
			}
		}
	
		// Check if the URL contains the generateBarcode flag
		window.onload = function() {
			var checkNewData = "<?= $this->session->flashdata('kanban_data') ? count($kanbanData = $this->session->flashdata('kanban_data')) : '' ?>";
	
			if(checkNewData.length > 0){
				generateBarcode();
			}
		}
	
		$('.print-btn').on('click', function() {
			var materialID = $(this).data('id');
			var materialDesc = $(this).data('description');
			var materialQty = $(this).data('qty');
			var proPlan = $(this).data('plan');
			var id_kanban = $(this).data('kanban');
			console.log(materialID);

			$.ajax({
				url: '<?= base_url('production/getKanbanImage'); ?>',
				type: 'POST',
				dataType: 'json',
				data: { 
					id_kanban 
				},
				success: function(res) {
					console.log(res);
					console.log('QR code saved successfully');
					var image = res[0].image;

					// Construct the correct image URL
					var imageSrc = '<?= base_url('assets/img/kanban-barcode/'); ?>' + image;

					// Open a new window with the data to print
					var printWindow = window.open('', '', 'height=300,width=600');
					printWindow.document.write(`
						<link href="<?=base_url('assets');?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
						<style>
                            @media print {
                                @page {
                                    margin: 0;
                                }
                                body {
                                    margin: 1.6cm;
                                }
                                header, footer {
                                    display: none;
                                }
                            }
                        </style>
							<div class="kanban-card" style="border: 1px solid #B4B4B8; padding: 20px; width: 700px; margin: 20px auto; position: relative;">
								<img src="<?=base_url('assets');?>/img/valeo-kanban-logo.png" alt="Logo" class="logo">
								<h3 style="text-align: center; margin-bottom: 20px; text-decoration: underline;">KANBAN CARD</h3>
								<div class="row mt-5 me-0">
									<div class="col-md-8" style="font-size: 14px">
										<ul style="list-style: none; padding: 0;">
											<li style="display: flex; align-items: center; padding: 5px 0;">
												<p style="margin: 0"><b>Material Part No :</b> ${materialID}</p>
											</li>
											<li style="display: flex; align-items: center; padding: 5px 0;">
												<p style="margin: 0"><b>Material Part Name :</b> ${materialDesc}</p>
											</li>
											<li style="display: flex; align-items: center; padding: 5px 0;">
												<p style="margin: 0"><b>Material Qty :</b> ${materialQty}</p>
											</li>
											<li style="display: flex; align-items: center; padding: 5px 0;">
												<p style="margin: 0"><b>FG ID :</b> ${proPlan}</p>
											</li>
											<li style="display: flex; align-items: center; padding: 5px 0;">
												<p style="margin: 0"><b>Production Plan :</b> ${proPlan}</p>
											</li>
										</ul>
									</div>  
									<div class="col-md-3 text-center">
										<div id="preview-barcode">
											<img src="${imageSrc}" alt="QR Code">
										</div>
									</div>
								</div>
							</div>
					`);
					printWindow.document.close();
					printWindow.print();
				},
				error: function(err) {
					console.error('Error saving QR code:', err);
				}
			});
		});

		$('#material_id').on('change', function () {
			var materialID = $(this).val();

			$.ajax({
				url: '<?= base_url('production/getMaterialList'); ?>',
				type: 'post',
				dataType: 'json',
				data: {
					materialID
				},
				success: function (res) {
					var materialDesc = res[0].Material_desc;
					$('#material_desc').val(materialDesc);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.error(xhr.statusText);
				}
			});
		});
	})
</script>