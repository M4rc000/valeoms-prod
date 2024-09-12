<section>
	<div class="row">
		<div class="col-lg-12">
			<div class="card ml-5">
				<div class="row mb-2 mt-5 mb-5" style="margin-left: 20px">
					<?php $list_box ?>
					<div class="col-sm-3">
						<select class="form-control" id="id_box" style="width: 100%; height: 50% !important;">
							<option value="">Pilih Box..</option>
							<?php foreach ($list_box as $box): ?>
								<option value="<?php echo $box['id_box']; ?>"><?php echo $box['no_box']; ?></option>
							<?php endforeach; ?>
						</select>
					</div>

					<div class="col-sm-3">
						<button type="button" class="btn btn-primary" id="search_button"
							onclick="getBox()">Search</button>
					</div>
				</div>
				<div class="card-body table-responsive mt-2" style="display: none;">
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
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Part Number</th>
								<th>Part Name</th>
								<th>Sloc</th>
								<th>QTY</th>
								<th>UOM</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="detailsBody">
							<tr>

							</tr>
						</tbody>
					</table>
					<div class="row mt-5">
						<div class="col-md-10"></div>
						<div class="col-md">
							<form action="<?= base_url('warehouse/clearData') ?>" method="post">

							</form>
						</div>
					</div>

					<h2>Total Weight: <span id="totalWeightDisplay">N/A</span></h2>
					<h2>Sloc: <span id="slocDisplay">N/A</span></h2>


				</div>
			</div>
		</div>
	</div>
	</div>
</section>
<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="height: 400px !important">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editModalLabel">Edit Quantity and Sloc</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="col-sm-12 mb-3">
					<label style="font-weight: bold; margin-bottom: 10px;">Quantity</label>
					<input type="text" class="form-control" id="qty" placeholder="">
				</div>
				<div class="col-sm-12 mb-3">
					<label style="font-weight: bold; margin-bottom: 10px;">Sloc</label>
					<select class="form-control" id="sloc">
						<option value="">Select Sloc</option>
						<!-- Options will be dynamically loaded -->
					</select>
				</div>
				<div class="col-sm-12 mb-3">
					<label style="font-weight: bold; margin-bottom: 10px;">Total Weight</label>
					<input type="text" class="form-control" id="total_weight" placeholder="">
				</div>
				<input type="hidden" class="form-control" id="id_box_detail" placeholder="" disabled>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
					onclick="closeModal()">Close</button>
				<button type="submit" onclick="saveEdit()" class="btn btn-primary">Save changes</button>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
	$(document).ready(function () {
		$('#id_box').select2();
		$('#id_box_modal').select2({
			dropdownParent: $('#unpackModal') // Ensure the dropdown is appended to the modal
		});
	});

	function fillEditModal(qty, id_box_detail, sloc, total_weight) {
		$('#qty').val(qty);
		$('#id_box_detail').val(id_box_detail);
		$('#total_weight').val(total_weight); // Set total weight

		// Load available SLOC options dynamically from the new endpoint
		$.ajax({
			url: '<?php echo base_url('warehouse/get_all_sloc_options'); ?>',
			type: 'POST',
			success: function (response) {
				console.log(response); // Debug: Check if the response is valid
				var slocOptions = JSON.parse(response);

				// Check if slocOptions is valid and not empty
				if (Array.isArray(slocOptions) && slocOptions.length) {
					// Empty and populate the SLOC dropdown
					$('#sloc').empty();
					slocOptions.forEach(function (option) {
						$('#sloc').append(
							`<option value="${option.sloc_id}">${option.sloc_name}</option>`
						);
					});

					// Select the sloc after populating the dropdown
					$('#sloc').val(sloc);
				} else {
					console.error('No SLOC options found'); // Log if no options were returned
					$('#sloc').append(`<option value="">No SLOC available</option>`);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.error('Error occurred while loading SLOC options.');
			}
		});
	}



	function getBox() {
		var id_box = $('#id_box').val();

		if (!id_box) {
			Swal.fire({
				icon: 'warning',
				title: 'Warning',
				text: 'Please select a box before searching!'
			});
			return;
		}

		$.ajax({
			url: '<?php echo base_url('warehouse/get_box_details'); ?>',
			type: 'POST',
			data: {
				id_box: id_box,
			},
			success: function (res) {
				var data = JSON.parse(res);

				if (data.status === 'success') {
					$('#detailsBody').empty();

					// Tampilkan total_weight, total_qty, dan sloc di elemen <h2>
					if (data.box) {
						$('#totalWeightDisplay').text(data.total_weight || 'N/A');
						$('#slocDisplay').text(data.sloc || 'N/A');
					}

					$.each(data.detail, function (index, detail) {
						var slocDisplay = detail.Sloc ? detail.Sloc : 'Belum di Set';

						$('#detailsBody').append(`
						<tr>
							<td>${detail.id_material}</td>
							<td>${detail.material_desc}</td>
							<td>${slocDisplay}</td>
							<td>${detail.qty}</td>
							<td>${detail.uom}</td>
							<td>
								<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal"
									onclick="fillEditModal(${detail.qty}, ${detail.id_box_detail}, '${slocDisplay}', ${data.total_weight})">
									Edit
								</button>
							</td>
						</tr>
					`);
					});

					$('.card-body').show();

				} else {
					console.error('Error:', data.message || 'No details available');
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

	function saveEdit() {
		var id_box_detail = $('#id_box_detail').val();
		var qty = $('#qty').val();
		var sloc = $('#sloc').val(); // Capture the sloc value
		var total_weight = $('#total_weight').val(); // Capture the total weight value

		// Validate that the fields are not empty before sending the request
		if (!id_box_detail || !qty || !sloc || !total_weight) {
			Swal.fire({
				icon: 'warning',
				title: 'Validation Error',
				text: 'Please fill in all fields before saving.'
			});
			return;
		}

		// Log the data being sent for debugging
		console.log('Sending data:', {
			id_box_detail,
			qty,
			sloc,
			total_weight
		});

		$.ajax({
			url: '<?php echo base_url('warehouse/save_cycle_count'); ?>',
			type: 'POST',
			data: {
				id_box_detail: id_box_detail,
				qty: qty,
				sloc: sloc, // Send sloc along with the other data
				total_weight: total_weight // Send total weight along with the other data
			},
			success: function (response) {
				// Check if the response is valid JSON
				try {
					var data = JSON.parse(response);
					if (data.status) {
						Swal.fire({
							title: "Success!",
							text: "Material has been edited successfully.",
							icon: "success"
						}).then(function () {
							$('#editModal').modal('hide'); // Close the modal
							$('#qty').val('');
							$('#id_box_detail').val('');
							$('#sloc').val(''); // Clear the sloc field
							$('#total_weight').val(''); // Clear the total weight field
							getBox(); // Reload the box details
						});
					} else {
						// If the status is not success, display an error message
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: data.message || 'An error occurred while saving your changes.'
						});
					}
				} catch (e) {
					// If the response is not valid JSON, log it and show an error
					console.error('Invalid JSON response:', response);
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'The server returned an invalid response.'
					});
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				// Log detailed error information and display an error message
				console.error('AJAX error:', thrownError, xhr.responseText);
				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: 'An error occurred while saving your changes.'
				});
			}
		});
	}
</script>