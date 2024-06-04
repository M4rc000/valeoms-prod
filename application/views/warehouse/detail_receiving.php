<section>
	<div class="row">
		<div class="col-lg-12">
			<div class="card ml-5">

				<div class="card-body table-responsive mt-2">
					<?php if ($this->session->flashdata('SUCCESS') != '') { ?>
						<?= $this->session->flashdata('SUCCESS'); ?>

					<?php } ?>
					<?php if ($this->session->flashdata('DUPLICATES') != '') { ?>
						<?= $this->session->flashdata('DUPLICATES'); ?>

					<?php } ?>
					<?php if ($this->session->flashdata('DELETED') != '') { ?>
						<?= $this->session->flashdata('DELETED'); ?>

					<?php } ?>
					<?php if ($this->session->flashdata('EDIT') != '') { ?>
						<?= $this->session->flashdata('EDIT'); ?>

					<?php } ?>
					<?php if ($this->session->flashdata('ERROR') != '') { ?>
						<?= $this->session->flashdata('ERROR'); ?>

					<?php } ?>
					<button type="button" class="btn btn-primary mb-2 mt-5" data-bs-toggle="modal"
						data-bs-target="#addModal1" style="font-weight: bold;" id="addBtn">
						+
					</button>
					<button class="btn btn-success mb-2 mt-5" onclick="refreshAll()">
						<i class="bx bx-revision"></i>
					</button>
					<table class="table datatable table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Material Part Number</th>
								<th>Material Part Name</th>
								<th>QTY</th>
								<th>UOM</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $number = 0;
							foreach ($receiving_material as $material):
								$number++ ?>
								<tr>

									<td>
										<?= $number; ?>
									</td>
									<td><?php echo $material['reference_number']; ?></td>
									<td><?php echo $material['material_desc']; ?></td>
									<td><?php echo $material['qty']; ?></td>
									<td><?php echo $material['uom']; ?></td>
									<td>
										<button class="btn btn-success" data-bs-toggle="modal"
											data-bs-target="#editModal<?= $material['id']; ?>">
											<i class="bx bxs-edit" style="color: white;"></i>
										</button>
										<button class="btn btn-danger ms-1" data-bs-toggle="modal"
											onclick="deleteItem(<?= $material['id']; ?>)">
											<i class="bx bxs-trash"></i>
										</button>
									</td>
								</tr>

							<?php endforeach; ?>
						</tbody>
					</table>
					<div class="row mb-5 mt-2">
						<label class="col-sm-2 col-form-label">
							<b>Total weight (kg)</b>
						</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" id="total_weight" onblur="getSloc()">
						</div>

						<label class="col-sm-2 col-form-label">
							<b>Select SLoc</b>
						</label>
						<div class="col-sm-4">
							<select id="sloc_select" class="form-select" aria-label="Default select example">
								<option value="" disabled selected style="color: GREY;">Please select total weight first
								</option>
							</select>
						</div>
					</div>
					<div class="row mt-2" style="text-align: right; margin-right: 5px;">
						<div class="col-md-10"></div>
						<div class="col-md">
							<button class="btn btn-primary" onclick="getBarcode()" id="approveBtn">
								Approve
							</button>
						</div>
					</div>
					<div class="row mt-2 mb-3">
						<div class="col-md" style="margin-left: 12px;">
							<b>Barcode</b>
						</div>
					</div>
					<div class="col-md ms-5 mt-5">
						<div id="qrcode"></div>
						<div id="barcode-info" class="mt-3"></div>
					</div>
					<div class="row mt-5">
						<div class="col-md-10"></div>
						<div class="col-md">
							<form action="<?= base_url('warehouse/clearData') ?>" method="post">
								<!-- Kosong -->
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
	function closeModal() {
		$('#reference_number').val("");
		$('#material').val("");
		$('#qty').val("");
		$('#material_edit').val('');
		$('#uom_edit').val('');
		$('#uom').val("");
	}

	function refreshAll() {
		$.ajax({
			url: '<?php echo base_url('warehouse/delete_receiving_temp'); ?>',
			type: 'POST',
			data: {
				id: 'delete',
			},
			success: function (res) {
				var data = JSON.parse(res);
				if (data.status) {
					window.location.reload();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
					});
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'An error occurred while processing your request.'
				});
			}
		});
	}

	function getMaterial() {
		$('#material').val("");
		$('#uom').val("");
		$('#material_edit').val('');
		$('#uom_edit').val('');
		var refnumber = $('#reference_number').val();
		var refnumber2 = $('#reference_number_edit').val();

		$.ajax({
			url: '<?php echo base_url('warehouse/get_material_data'); ?>',
			type: 'POST',
			data: {
				refnumber: refnumber,
				refnumber2: refnumber2
			},
			success: function (res) {
				var data = JSON.parse(res);
				if (data.status) {
					$('#material').val(data.material);
					$('#uom').val(data.uom);
					$('#material_edit').val(data.material);
					$('#uom_edit').val(data.uom);
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.msg
					});
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'An error occurred while processing your request.'
				});
			}
		});
	}

	function getSloc() {
		var total_weight = $('#total_weight').val();
		var slocSelect = $('#sloc_select');

		$.ajax({
			url: '<?php echo base_url('warehouse/get_sloc'); ?>',
			type: 'POST',
			data: {
				total_weight: total_weight
			},
			success: function (res) {
				var data = JSON.parse(res);
				if (data.sloc.length === 0 && data.status === 'success') {
					slocSelect.find('option:not(:selected)').remove();
					if (slocSelect.find('option:selected').length === 0) {
						slocSelect.append(
							'<option value="">No available Slocs for the specified total weight</option>');
					}
				} else if (data.status == 'success') {
					var selectedValues = [];
					slocSelect.find('option:selected').each(function () {
						selectedValues.push($(this).val());
					});

					slocSelect.empty();

					$.each(data.sloc, function (index, sloc) {
						slocSelect.append('<option value="' + sloc.Id_storage + '">' + sloc.SLoc +
							'</option>');
					});

					$.each(selectedValues, function (index, value) {
						slocSelect.find('option[value="' + value + '"]').prop('selected', true);
					});
				} else if (data.status == 'empty') {
					slocSelect.find('option:not(:selected)').remove();
					if (slocSelect.find('option:selected').length === 0) {
						slocSelect.append('<option value="">Please select total weight first</option>');
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'An error occurred while processing your request.'
				});
			}
		});
	}

	function getBarcode() {
		var slocSelect = $('#sloc_select').val();
		var total_weight = $('#total_weight').val();

		if (slocSelect && total_weight) {
			Swal.fire({
				title: "Are you sure?",
				text: "You want to approve this box?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Approve"
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: '<?php echo base_url('warehouse/save_new_box'); ?>',
						type: 'POST',
						data: {
							id_sloc: slocSelect,
							total_weight: total_weight,
						},
						success: function (res) {
							var data = JSON.parse(res);
							if (data.status) {
								var qrcode = new QRCode(document.getElementById("qrcode"), {
									text: data.no_box,
									width: 150,
									height: 150,
									correctLevel: QRCode.CorrectLevel.H
								});

								$('#barcode-info').html('<b>ID Box:</b> ' + data.no_box);

								setTimeout(function () {
									printBarcode(data.no_box);
								}, 500);

								document.getElementById("approveBtn").disabled = true;
								document.getElementById("addBtn").disabled = true;
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Please add material!',
									text: data.msg
								});
							}
						},
						error: function (xhr, ajaxOptions, thrownError) {
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: 'An error occurred while processing your request.'
							});
						}
					});
				}
			});
		} else {
			Swal.fire({
				icon: 'error',
				title: 'Incomplete Data',
				text: 'Please complete the Sloc field.'
			});
		}
	}

	function printBarcode(idBox) {
		var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?>';
		var printWindow = window.open('', '', 'height=400,width=600');
		printWindow.document.write('<html><head><title>Print Barcode</title>');
		printWindow.document.write('<style>');
		printWindow.document.write('@page { size: 15cm 10cm; margin: 0; }');
		printWindow.document.write(
			'.print-section { display: flex; flex-direction: column; width: 15cm; height: 9cm; border: 1px solid black; box-sizing: border-box; }'
		);
		printWindow.document.write(
			'.row { display: flex; flex: 1; align-items: center; justify-content: space-between; border-bottom: 1px solid black; }'
		);
		printWindow.document.write('.row:first-child { height: 4cm; padding: 0 5px; }');
		printWindow.document.write(
			'.row:last-child { height: 5cm; align-items: center; justify-content: center; text-align: center; }');
		printWindow.document.write('.barcode, .valeo-logo { display: inline-block; text-align: center;}');
		printWindow.document.write('.barcode { width: 2cm; height: 2cm; margin-left:2cm;}');
		printWindow.document.write(
			`.valeo-logo { width: 3cm; height: 2cm;margin-right:1cm; background-position: center; }`
		);
		printWindow.document.write(
			'.barcode-info { font-size: 2em; margin-top: 10px; text-align: center; width: 100%; margin-left:15px; }');
		printWindow.document.write('#qrcode img { width: 100%; height: 100%; }');
		printWindow.document.write('</style>');
		printWindow.document.write('</head><body>');
		printWindow.document.write('<div class="print-section">');
		printWindow.document.write('<div class="row">');
		printWindow.document.write('<div class="barcode" id="qrcode">' + document.getElementById('qrcode').innerHTML +
			'</div>');
		printWindow.document.write('<div class="valeo-logo"><img src="' + logoUrl +
			'" alt="Valeo Logo" style="width: 100%; height: 100%;"></div>');
		printWindow.document.write('</div>');
		printWindow.document.write('<div class="row">');
		printWindow.document.write(
			'<div class="barcode-info" style="margin-top:85px;"><span>ID Box:</span><h1 style="font-size:3em; margin-top:0;">' +
			idBox + '</h1></div>');
		printWindow.document.write('</div>');
		printWindow.document.write('</div>');
		printWindow.document.write('</body></html>');
		printWindow.document.close();
		printWindow.print();
	}



	function deleteItem(id) {
		Swal.fire({
			title: "Are you sure?",
			text: "You won't be able to revert this!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, delete it!"
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: '<?php echo base_url('warehouse/delete_material_temp'); ?>',
					type: 'POST',
					data: {
						id: id,
					},
					success: function (res) {
						var data = JSON.parse(res);
						if (data.status) {
							Swal.fire({
								title: "Deleted!",
								text: "Material has been deleted.",
								icon: "success"
							});
							window.location.reload();
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'An error occurred while processing your request.'
						});
					}
				});
			}
		});
	}
</script>

<!-- ADD MODAL-->
<div class="modal fade" id="addModal1" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?= form_open_multipart('Warehouse/AddReceivingMaterial'); ?>
			<div class="modal-header">
				<h5 class="modal-title">Add New Data Receiving</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
					onclick="closeModal()"></button>
			</div>
			<div class="modal-body">
				<div class="row ps-2">
					<div class="col-6">
						<label for="reference_number" class="form-label">Material Part Number</label>
						<input type="text" class="form-control" id="reference_number" name="reference_number"
							onblur="getMaterial()" required>
					</div>
					<div class="col-6 mb-3">
						<label for="material" class="form-label">Material</label>
						<input type="text" class="form-control" id="material" name="material" required>
					</div>
					<div class="col-6 mb-3">
						<label for="uom" class="form-label">UOM</label>
						<input type="text" class="form-control" id="uom" name="uom" required>
					</div>
					<div class="col-6 mb-3">
						<label for="qty" class="form-label">Quantity</label>
						<input type="number" class="form-control" id="qty" name="qty" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
					onclick="closeModal()">Close</button>
				<button type="submit" class="btn btn-primary" onclick="setReceivingDate()">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- EDIT MODAL-->
<div class="modal fade" id="editModal1" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<form id="editForm" action="<?= base_url('warehouse/editReceivingMaterial'); ?>" method="post">
				<div class="modal-header">
					<h5 class="modal-title">Edit Data Receiving</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
						onclick="closeModal()"></button>
				</div>
				<div class="modal-body">
					<input type="hidden" id="id_box" name="id_box">
					<div class="row ps-2">
						<div class="col-6">
							<label for="weight" class="form-label">Total Weight (kg)</label>
							<input type="text" class="form-control" id="weight" name="weight" required>
						</div>
						<div class="col-6">
							<label for="sloc" class="form-label">SLOC</label>
							<input type="text" class="form-control" id="sloc" name="sloc" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
						onclick="closeModal()">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	function closeModal() {
		$('#id_box').val("");
		$('#weight').val("");
		$('#sloc').val("");
	}

	function editBox(id_box, weight, sloc) {
		$('#id_box').val(id_box);
		$('#weight').val(weight);
		$('#sloc').val(sloc);
	}
</script>

<!-- DELETE CONFIRM MODAL-->
<?php foreach ($users as $usr): ?>
	<?= form_open_multipart('admin/deleteDataReceiving'); ?>
	<div class="modal fade" id="deleteModal<?= $usr['id']; ?>" tabindex=" -1" aria-labelledby="exampleModalLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" value="<?= $usr['id']; ?>" style="display: none;">
					<p><b>Username</b> : <?= $usr['username']; ?></p>
					<p><b>Name</b> : <?= $usr['name']; ?></p>
					<p><b>Role</b> :
						<?php
						if ($usr['role_id'] == 1) {
							echo 'Administrator';
						} elseif ($usr['role_id'] == 2) {
							echo 'Warehouse';
						} else {
							echo 'Production';
						}
						?>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
				</div>
			</div>
		</div>


	</div>

	</form>

<?php endforeach; ?>
</section>