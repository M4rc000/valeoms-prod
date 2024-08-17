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

	.barcode-container {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		height: 150px; /* or adjust height as needed */
	}

	.barcode-container img {
		display: block;
		margin: 0 auto;
	}
</style>
<section style="font-family: Nunito;">
	<div class="show" style="position: absolute; top: 12%; left: 70%; width: 70%; z-index: 999;">
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
										<div class="col-12 col-md-3 mb-3 mb-md-0">
											<!-- GET USER -->
											<input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
											<input type="text" class="form-control" id="kanbanBox_id" name="kanbanBox_id" value="<?= $kanban; ?>" readonly hidden>
											<label for="material_id" class="form-label"><b>Material Part No</b></label>
											<select class="form-select" id="material_id" name="material_id" required>
												<option value="" selected>Select Material Part No</option>
												<?php foreach($material_list as $ml): ?>
													<option value="<?=$ml['Id_material']?>"><?=$ml['Id_material'];?></option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="col-12 col-md-4 mb-3 mb-md-0">
											<label for="material_desc" class="form-label"><b>Material Part Name</b></label>
											<input type="text" class="form-control" id="material_desc" name="material_desc" required readonly>
										</div>
										<div class="col-12 col-md-2 mb-3 mb-md-0">
											<label for="qty" class="form-label"><b>Qty</b></label>
											<input type="text" class="form-control" id="qty" name="qty" required placeholder="0.5">
										</div>
										<div class="col-12 col-md-3">
											<label for="production_planning" class="form-label"><b>Production Planning</b></label>
											<select class="form-select" id="production_planning" name="production_planning" required>
												<option value="">Select Production Plan</option>
												<?php foreach($production_plans as $pp): ?>
													<option value="<?=$pp['Production_plan'];?>"><?=$pp['Production_plan'];?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="row mt-5 justify-content-end mx-3">
										<div class="col-12 col-md-3 text-end">
											<button type="submit" class="btn btn-success w-100" id="btn-submit" style="width: 150px">Submit</button>
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
											<th>Kanban Box No</th>
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
												<td><?=$kl['id_kanban_box'];?></td>
												<td><?=$kl['product_id'];?></td>
												<td><?=$kl['Id_material'];?></td>
												<td><?=$kl['Material_desc'];?></td>
												<td><?=$kl['Material_qty'];?></td>
												<td><?=$kl['Product_plan'];?></td>
												<td>
													<a href="#" data-bs-toggle="modal" data-bs-target="#barcode-modal<?=$kl['id_kanban_box'];?>" onclick="showBarcode('<?=$kl['id_kanban_box'];?>')">
														<span class="badge bg-success"><i class="bx bx-qr-scan" style="font-size: 12px"></i></span>
													</a>
													<a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?=$kl['id_kanban_box'];?>">
														<span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
													</a>
													<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$kl['id_kanban_box'];?>">
														<span class="badge bg-danger"><i class="bi bi-trash"></i></span>
													</a>
													<button class="btn btn-primary print-btn" data-id="<?=$kl['Id_material'];?>" data-description="<?=$kl['Material_desc'];?>" data-qty="<?=$kl['Material_qty'];?>" data-plan="<?=$kl['Product_plan'];?>" data-kanban="<?=$kl['id_kanban_box'];?>" data-fg="<?=$kl['product_id'];?>" style="width: 27px; height: 20px; align-items: center; justify-content: center; padding: 0;">
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
<?php foreach($kanbanlist as $kl): ?>
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
                    <div class="col-md text-center d-flex justify-content-center">
                        <div id="barcode-modal-img-<?=$kl['id_kanban_box'];?>" class="barcode-container"></div>
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
<?php $number = 0; foreach($kanbanlist as $kl): $number++; ?>
<div class="modal fade" id="editModal<?= $kl['id_kanban_box']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?= form_open_multipart('production/EditKanbanBox'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kanban Box</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="user" name="user" value="<?= $name['username']; ?>" hidden> 
                    <input type="text" class="form-control" id="id" name="id" value="<?= $kl['id_kanban_box']; ?>" hidden> 
                    <div class="row ps-2">
                        <div class="col-4">
                            <label for="id_kanban_box" class="form-label">Kanban Box</label>
                            <input type="text" class="form-control" id="id_kanban_box" name="id_kanban_box" value="<?= $kl['id_kanban_box']; ?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="product_id" class="form-label">Product ID</label>
                            <input type="text" class="form-control product_id_edit" id="product_id" name="product_id" value="<?= $kl['product_id']; ?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="material_id" class="form-label">Material Part No</label>
							<select class="form-select material_id_edit" id="material_id" name="material_id" required>
								<option value="" selected>Select Material Part No</option>
								<?php foreach($material_list as $ml): ?>
									<option value="<?=$ml['Id_material']?>" <?=$ml['Id_material'] == $kl['Id_material'] ? 'selected' : '';?>><?=$ml['Id_material'];?></option>
								<?php endforeach; ?>
							</select>
                        </div>
                    </div>
                    <div class="row mt-4 ps-2">
                        <div class="col-4">
                            <label for="material_desc" class="form-label">Material Part Name</label>
                            <input type="text" class="form-control material_desc_edit" id="material_desc" name="material_desc" value="<?= $kl['Material_desc']; ?>" readonly>
                        </div>
                        <div class="col-4">
                            <label for="material_qty" class="form-label">Material Qty</label>
                            <input type="text" class="form-control" id="material_qty" name="material_qty" value="<?= $kl['Material_qty']; ?>">
                        </div>
                        <div class="col-4">
                            <label for="product_plan" class="form-label">Production Plan</label>
                            <select class="form-select product_plan_edit" id="product_plan" name="product_plan" required>
                                <option value="" disabled>Select Production Plan</option>
                                <?php foreach($production_plans as $pp): ?>
                                    <option value="<?= $pp['Production_plan']; ?>" <?= $pp['Production_plan'] == $kl['Product_plan'] ? 'selected' : ''; ?>><?= $pp['Production_plan']; ?></option>
                                <?php endforeach; ?>
                            </select>
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
			<?= form_open_multipart('production/DeleteKanbanBox'); ?>
				<div class="modal-header">
					<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete?</h4>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" value="<?=$kl['id_kanban_box'];?>" hidden>
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
			</form>
		</div>
	</div>
</div>
<?php endforeach; ?>

<script src="<?=base_url('assets');?>/vendor/sweet-alert/sweet-alert.js"></script>
<script src="<?=base_url('assets');?>/vendor/qr-code/qr-code.min.js"></script>
<script>
	$(document).ready(function (){
		ready();

		$('#material_id').select2();
		$('.modal').on('shown.bs.modal', function () {
			$(this).find('#product_plan').select2({
				dropdownParent: $(this)
			});
			$(this).find('.material_id_edit').select2({
				dropdownParent: $(this)
			});
		});
		
		$('#production_planning').select2();

		function generateBarcode() {
			var id_kanban_box = "<?= isset($this->session->flashdata('kanban_data')['id_kanban_box']) ? $this->session->flashdata('kanban_data')['id_kanban_box'] : '' ?>";
			var materialId = "<?= isset($this->session->flashdata('kanban_data')['Id_material']) ? $this->session->flashdata('kanban_data')['Id_material'] : '' ?>";
			var materialDesc = "<?= isset($this->session->flashdata('kanban_data')['Material_desc']) ? $this->session->flashdata('kanban_data')['Material_desc'] : '' ?>";
			var qty = "<?= isset($this->session->flashdata('kanban_data')['Material_qty']) ? $this->session->flashdata('kanban_data')['Material_qty'] : '' ?>";
			var production_planning = "<?= isset($this->session->flashdata('kanban_data')['Product_plan']) ? $this->session->flashdata('kanban_data')['Product_plan'] : '' ?>";
			var product_id = "<?= isset($this->session->flashdata('kanban_data')['product_id']) ? $this->session->flashdata('kanban_data')['product_id'] : '' ?>";

			if (materialId.length == 0 || qty.length == 0 || production_planning.length == 0) {
				return false;
			} else {
				// Show the spinner before sending the request
				var spinner = 
				`
					<div class="d-flex justify-content-center align-items-center">
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
				$('.preview').append(spinner);

				var htmlContent = 
				`
					<div class="kanban-card p-3">
						<img src="<?=base_url('assets');?>/img/valeo-kanban-logo.png" alt="Logo" class="logo mb-3 mx-auto d-block">
						<h3 class="text-center">KANBAN CARD</h3>
						<div class="row mt-5 me-0">
							<div class="col-12 col-md-8" style="font-size: 14px">
								<ul class="list-unstyled">
									<li><p><b>Material Part No :</b> ${materialId}</p></li>
									<li><p><b>Material Part Name :</b> ${materialDesc}</p></li>
									<li><p><b>Material Qty :</b> ${qty}</p></li>
									<li><p><b>FG ID :</b> ${product_id}</p></li>
									<li><p><b>Production Plan :</b> ${production_planning}</p></li>
								</ul>
							</div>  
							<div class="col-12 col-md-4 text-center mt-3 mt-md-0">
								<div id="preview-barcode-print" class="ms-md-5"></div>
							</div>
						</div>
					</div>
				`;

				htmlContent += `
					<div class="row justify-content-end mt-4">
						<div class="col-12 col-md-3 text-end">
							<button class="btn btn-warning w-100" onclick="printKanbanCard(this)" data-id="${materialId}" data-description="${materialDesc}" data-qty="${qty}" data-plan="${production_planning}" data-fg="${product_id}" data-kanban="${id_kanban_box}" style="color: white">
								<i class="bx bxs-printer me-2" style="color: white"></i>Print
							</button>
						</div>
					</div>
					<div class="row justify-content-end mt-4">
						<div class="col-12 col-md-3 text-end">
							<a href="">
								<button class="btn btn-success w-100">
									<i class="bx bx-revision me-2" style="color: white"></i>
								</button>
							</a>
						</div>
					</div>
				`;

				// Empty the preview element and append the new content
				$('.preview').empty().append(htmlContent);

				// Generate the QR code after the element is in the DOM
				var qrcode = new QRCode(document.getElementById("preview-barcode-print"), {
					text: id_kanban_box,
					width: 150,
					height: 150,
					correctLevel: QRCode.CorrectLevel.H
				});
			}
		}

		function ready() {
			var checkNewData = "<?= $this->session->flashdata('kanban_data') ? count($kanbanData = $this->session->flashdata('kanban_data')) : '' ?>";
	
			if(checkNewData.length > 0){
				$('#material_id').prop('disabled', true);
				$('#material_desc').prop('disabled', true);
				$('#qty').prop('disabled', true);
				$('#production_planning').prop('disabled', true);
				$('#btn-submit').prop('disabled', true);
				generateBarcode();
			}
		}
	
		$('.print-btn').on('click', function() {
			var materialID = $(this).data('id');
			var materialDesc = $(this).data('description');
			var materialQty = $(this).data('qty');
			var proPlan = $(this).data('plan');
			var id_fg = $(this).data('fg');
			var id_kanban = $(this).data('kanban');

			var printWindow = window.open('', '', 'height=400,width=600');
			printWindow.document.write(`
				<link href="<?=base_url('assets');?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
				<style>
					@media print {
						@page { 
							size: 15cm 10cm; 
							margin: 0;
						}
						header, footer {
							display: none;
						}
						.kanban-card {
							border: 1px solid black;
							padding: 20px;
							width: 15cm;
							height: 8.6cm;
							margin: 0;
							box-sizing: border-box;
							page-break-inside: avoid;
						}
					}
				</style>
				<div class="kanban-card" style="border: 1px solid black; padding: 20px; width: 15cm; height: 8.6cm; margin: 15px auto; position: relative; box-sizing: border-box;">
					<img src="<?=base_url('assets');?>/img/valeo-kanban-logo.png" alt="Logo" class="logo">
					<h3 style="text-align: center; margin-top: -30px; margin-bottom: 5px; text-decoration: underline;">KANBAN CARD</h3>
					<div class="row mt-5 me-0">
						<div class="col-md-8" style="font-size: 16px; width: 55% !important;">
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
									<p style="margin: 0"><b>FG ID :</b> ${id_fg}</p>
								</li>
								<li style="display: flex; align-items: center; padding: 5px 0;">
									<p style="margin: 0"><b>Production Plan :</b> ${proPlan}</p>
								</li>
							</ul>
						</div>  
						<div class="col-md-3 text-center" style="font-size: 14px; margin-left: 5rem; width: 8% !important;">
							<div id="preview-barcode"></div>
						</div>
					</div>
				</div>
			`);

			printWindow.document.close();

			printWindow.onload = function() {
				generateQRCode(id_kanban, function(no_box, qrcodeImg) {
					var imgElement = printWindow.document.createElement('img');
					imgElement.src = qrcodeImg;
					printWindow.document.getElementById('preview-barcode').appendChild(imgElement);
					printWindow.print();
				});
			};
		});

		function generateQRCode(no_box, callback) {
			var qrcodeContainer = document.createElement('div');
			var qrcode = new QRCode(qrcodeContainer, {
				text: no_box,
				width: 150,
				height: 150,
				correctLevel: QRCode.CorrectLevel.H
			});

			setTimeout(function() {
				var qrcodeImg = qrcodeContainer.querySelector('img').src;
				callback(no_box, qrcodeImg);
			}, 500);
		}
	
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

		$('.product_plan_edit').on('change', function(){
			var product_plan = $(this).val();
			
			$.ajax({
				url: '<?= base_url('production/getProductIdByProductPlan'); ?>',
				type: 'post',
				dataType: 'json',
				data: {
					product_plan
				},
				success: function (res) {
					var product_id = res[0].Id_fg;
					console.log(product_id);
					$('.product_id_edit').val(product_id);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.error(xhr.statusText);
				}
			});
		});

		$('.material_id_edit').on('change', function(){
			var material_id = $(this).val();
			
			$.ajax({
				url: '<?= base_url('production/getMaterialById'); ?>',
				type: 'post',
				dataType: 'json',
				data: {
					material_id
				},
				success: function (res) {
					var material_desc = res[0].Material_desc;
					$('.material_desc_edit').val(material_desc);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.error(xhr.statusText);
				}
			});
		});
	})

	function showBarcode(id) {
		var elementId = "barcode-modal-img-" + id;
		document.getElementById(elementId).innerHTML = ""; 
		new QRCode(document.getElementById(elementId), {
			text: id,
			width: 150,
			height: 150,
			correctLevel: QRCode.CorrectLevel.H
		});
	}

	function showBarcodePrint(id) {
		var print = new QRCode(document.getElementById('preview-barcode-print'), {
			text: id,
			width: 150,
			height: 150,
			correctLevel: QRCode.CorrectLevel.H
		});
	}

	function printKanbanCard(button) {
		var materialID = $(button).data('id');
		var materialDesc = $(button).data('description');
		var materialQty = $(button).data('qty');
		var proPlan = $(button).data('plan');
		var id_fg = $(button).data('fg');
		var id_kanban = $(button).data('kanban');

		var printWindow = window.open('', '', 'height=400,width=600');
		printWindow.document.write(`
			<link href="<?=base_url('assets');?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
			<style>
				@media print {
					@page { 
						size: 15cm 10cm; 
						margin: 0;
					}
					header, footer {
						display: none;
					}
					.kanban-card {
						border: 1px solid black;
						padding: 20px;
						width: 15cm;
						height: 8.6cm;
						margin: 0;
						box-sizing: border-box;
						page-break-inside: avoid;
					}
				}
			</style>
			<div class="kanban-card" style="border: 1px solid black; padding: 20px; width: 15cm; height: 8.6cm; margin: 15px auto; position: relative; box-sizing: border-box;">
				<img src="<?=base_url('assets');?>/img/valeo-kanban-logo.png" alt="Logo" class="logo">
				<h3 style="text-align: center; margin-top: -30px; margin-bottom: 5px; text-decoration: underline;">KANBAN CARD</h3>
				<div class="row mt-5 me-0">
					<div class="col-md-8" style="font-size: 16px; width: 55% !important;">
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
								<p style="margin: 0"><b>FG ID :</b> ${id_fg}</p>
							</li>
							<li style="display: flex; align-items: center; padding: 5px 0;">
								<p style="margin: 0"><b>Production Plan :</b> ${proPlan}</p>
							</li>
						</ul>
					</div>  
					<div class="col-md-3 text-center" style="font-size: 14px; margin-left: 5rem; width: 8% !important;">
						<div id="preview-barcode"></div>
					</div>
				</div>
			</div>
		`);
		printWindow.document.close();

		printWindow.onload = function() {
			generateQRCodes(id_kanban, function(no_box, qrcodeImg) {
				var imgElement = printWindow.document.createElement('img');
				imgElement.src = qrcodeImg;
				printWindow.document.getElementById('preview-barcode').appendChild(imgElement);
				printWindow.print();
			});
		};
	}

	function generateQRCodes(text, callback) {
		var qrcode = new QRCode(document.createElement('preview-barcode'), {
			text: text,
			width: 150,
			height: 150,
			correctLevel: QRCode.CorrectLevel.H
		});

		// Allow some time for the QR code to render
		setTimeout(function() {
			var canvas = qrcode._el.querySelector('canvas');
			if (canvas) {
				var qrcodeImg = canvas.toDataURL('image/png');
				callback(null, qrcodeImg);
			} else {
				callback('Error generating QR code');
			}
		}, 100); // Adjust the timeout as needed
	}
</script>


 <!-- SWEET ALERT  -->
<?php if ($this->session->flashdata('kanban_data')): ?>
    <script>
        Swal.fire({
            title: "Success",
            text: "Data Kanban have been added",
            icon: "success"
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('success_edit_kanban_box')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "<?= $this->session->flashdata('success_edit_kanban_box'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('failed_edit_kanban_box')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('failed_edit_kanban_box'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('success_delete_kanban_box')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "<?= $this->session->flashdata('success_delete_kanban_box'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('failed_delete_kanban_box')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('failed_delete_kanban_box'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>