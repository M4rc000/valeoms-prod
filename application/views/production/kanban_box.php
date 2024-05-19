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
									<div class="col-4">
										<!-- GET USER -->
										<input type="text" class="form-control" id="user" name="user"
											value="<?=$name['username'];?>" hidden>
										<label for="product_id" class="form-label"><b>Product ID</b></label>
										<input type="text" class="form-control" id="product_id" name="product_id"
											required>
									</div>
									<div class="col-4">
										<label for="qty" class="form-label"><b>Qty</b></label>
										<input type="text" class="form-control" id="qty" name="qty" required>
									</div>
									<div class="col-4">
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
											<th>Production Description</th>
											<th>Qty</th>
											<th>Production Plan No</th>
											<th>Action</th>
										</tr>
										</thead>
										<tbody>
											<?php $number = 0; foreach($kanbanlist as $kl): $number++ ?>
											<tr>
												<td><?=$number;?></td>
												<td><?=$kl['Id_product'];?></td>
												<td><?=$kl['Product_desc'];?></td>
												<td><?=$kl['Product_qty'];?></td>
												<td><?=$kl['Product_plan'];?></td>
												<td>
													<a href="#" data-bs-toggle="modal" data-bs-target="#editModal`+ MaterialID +`">
														<span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
													</a>
													<a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal`+ MaterialID +`">
														<span class="badge bg-danger"><i class="bi bi-trash"></i></span>
													</a>
													<a href="#">
														<span class="badge bg-success"><i class="bi bi-printer"></i></span>
													</a>
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

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
	function generateBarcode() {
		var productId = "<?= isset($this->session->flashdata('kanban_data')['Id_product']) ? $this->session->flashdata('kanban_data')['Id_product'] : '' ?>";
        var qty = "<?= isset($this->session->flashdata('kanban_data')['Product_qty']) ? $this->session->flashdata('kanban_data')['Product_qty'] : '' ?>";
        var production_planning = "<?= isset($this->session->flashdata('kanban_data')['Product_plan']) ? $this->session->flashdata('kanban_data')['Product_plan'] : '' ?>";

		if (productId.length == 0 || qty.length == 0 || production_planning.length == 0) {
			return false;
		} else {
			var htmlContent = 
			`
			    <div class="kanban-card">
			        <img src="<?=base_url('assets');?>/img/valeo.png" alt="Logo" class="logo">
			        <h3>KANBAN CARD</h3>
			        <div class="row mt-5 me-0">
			            <div class="col-md-8" style="font-size: 14px">
			                <ul>
			                    <li>
			                        <p><b>Product ID :</b> ${productId}</p>
			                    </li>
			                    <li>
			                        <p><b>Product Qty :</b> ${qty}</p>
			                    </li>
			                    <li>
			                        <p><b>Product Plan :</b> ${production_planning}</p>
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
			    text: "<?=base_url('warehouse/')?>",
			    width: 150,
			    height: 150,
			    correctLevel: QRCode.CorrectLevel.H
			});
		}
	}

	// Check if the URL contains the generateBarcode flag
    window.onload = function() {
		var checkNewData = "<?= $this->session->flashdata('kanban_data') ? count($kanbanData = $this->session->flashdata('kanban_data')) : '' ?>";

		console.log(checkNewData.length);

		if(checkNewData.length > 0){
			generateBarcode();
			console.log('Iyaa');
		}
    }
</script>