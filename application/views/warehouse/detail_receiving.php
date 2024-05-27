<section>
	<div class="row">
		<div class="col-lg-12">
			<div class="card ml-5">
			<div class="row mb-2 mt-5" style="margin-left: 20px">
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
						<option value="" disabled selected style="color: GREY;">Please select total weight first</option>
					</select>
				</div>
			</div>

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
					<button type="button" class="btn btn-primary mb-2 mt-5" data-bs-toggle="modal" data-bs-target="#addModal1" style=" font-weight: bold;" id="addBtn">
						+
					</button>
					<button class="btn btn-success  mb-2 mt-5" onclick="refreshAll()">
						<i class="bx bx-revision"></i>
					</button>
					<table class="table datatable table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Reference Number</th>
								<th>Material</th>
								<th>QTY</th>
								<th>UOM</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $number = 0;
							foreach ($receiving_material as $material) :
								$number++ ?>
								<tr>
									<td><?= $number; ?></td>
									<td><?php echo $material['reference_number']; ?></td>
									<td><?php echo $material['material_desc']; ?></td>
									<td>
										<?php echo $material['qty']; ?>
									</td>
									<td>
										<?php echo $material['uom']; ?>
									</td>
									<td>
										<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal<?= $material['id']; ?>">
											<i class="bx bxs-edit" style="color: white;"></i>
										</button>
										<button class="btn btn-danger ms-1" data-bs-toggle="modal" onclick="deleteItem(<?= $material['id']; ?>)">
											<i class="bx bxs-trash"></i>
										</button>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					
					<div class="row mt-2" style="text-align: right; margin-right: 5px;">
						<div class="col-md-10" ></div>
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
					</div>
					<div class="row mt-5">
						<div class="col-md-10"></div>
						<div class="col-md">
							<form action="<?=base_url('warehouse/clearData')?>" method="post">
								
							</form>
						</div>
					</div>
                </div>
				</div>
			</div>
		</div>
	</div>
</section>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>


<!-- ADD MODAL-->
<div class="modal fade" id="addModal1" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?= form_open_multipart('Warehouse/AddReceivingMaterial'); ?>
			<div class="modal-header">
				<h5 class="modal-title">Add New Data Receiving</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
			</div>
			<div class="modal-body">
				<div class="row ps-2">
					<div class="col-6">
						<label for="reference_number" class="form-label">Reference Number</label>
						<input type="text" class="form-control" id="reference_number" name="reference_number" onblur="getMaterial()" required>
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
					<div class="col-6">
						<label for="receiving_date" class="form-label">Receiving Date</label>
						<input type="date" class="form-control" id="receiving_date" name="receiving_date" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
				<button type="submit" class="btn btn-primary">Save</button>
			</div>
			</form>
		</div>
	</div>
</div>

<!-- EDIT MODAL -->
<?php foreach ($receiving_material as $material) : ?>
	<div class="modal fade" id="editModal<?= $material['id']; ?>" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<?= form_open_multipart('warehouse/editReceivingMaterialTemp'); ?>
				<div class="modal-header">
					<h5 class="modal-title">Edit Data Receiving</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row ps-2 mb-5">
						<div class="col-6">
						<label for="reference_number_edit" class="form-label">Reference Number</label>
						<input type="text" class="form-control" id="reference_number_edit" name="reference_number" onblur="getMaterial()" required value="<?= $material['reference_number']; ?>">
						<input type="hidden" class="form-control" id="id" name="id" value="<?= $material['id']; ?>">
					</div>
					<div class="col-6 mb-3">
						<label for="material_edit" class="form-label">Material</label>
						<input type="text" class="form-control" id="material_edit" name="material" required value="<?= $material['material_desc']; ?>">
					</div>
					<div class="col-6 mb-3">
						<label for="uom" class="form-label">UOM</label>
						<input type="text" class="form-control" id="uom_edit" name="uom" required value="<?= $material['uom']; ?>">
					</div>
					<div class="col-6 mb-3">
						<label for="qty_edit" class="form-label">Quantity</label>
						<input type="number" class="form-control" id="qty_edit" name="qty" required value="<?= $material['qty']; ?>">
					</div>
					<div class="col-6">
						<label for="receiving_date_edit" class="form-label">Receiving Date</label>
						<input type="date" class="form-control" id="receiving_date_edit" name="receiving_date" required value="<?= date('Y-m-d', strtotime($material['receiving_date'])); ?>">
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

<!-- DELETE CONFIRM MODAL-->
<?php foreach ($users as $usr) : ?>
	<?= form_open_multipart('admin/deleteDataReceiving'); ?>
	<div class="modal fade" id="deleteModal<?= $usr['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Include SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
function closeModal() {
    $('#reference_number').val("");
    $('#material').val("");
    $('#qty').val("");
	$('#material_edit').val('');
    $('#uom_edit').val('');
    $('#uom').val("");
    $('#size').val('0');
}

function refreshAll(){
	$.ajax({
        url: '<?php echo base_url('warehouse/delete_receiving_temp'); ?>',
        type: 'POST',
        data: {
            id: 'delete',
        },
        success: function(res) {
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
        error: function(xhr, ajaxOptions, thrownError) {
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
        success: function(res) {
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
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.'
            });
        }
    });
}

function getSloc(){
	var total_weight = $('#total_weight').val();
	var slocSelect = $('#sloc_select'); 

	$.ajax({
        url: '<?php echo base_url('warehouse/get_sloc'); ?>',
        type: 'POST',
        data: {
            total_weight: total_weight
        },
        success: function(res) {
            var data = JSON.parse(res);
			if(data.sloc.length === 0 && data.status === 'success'){
				slocSelect.empty();
				slocSelect.append('<option value="">No available Slocs for the specified total weight</option>');
			}
            else if (data.status == 'success') {
				slocSelect.empty();

				$.each(data.sloc, function(index, sloc) {
					slocSelect.append('<option value="' + sloc.Id_storage + '">' + sloc.SLoc + '</option>');
				});
            } else if (data.status == 'empty'){
				slocSelect.empty();
				slocSelect.append('<option value="">Please select total weight first</option>');
            } 
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.'
            });
        }
    });
}


function getBarcode() {
//cek apakah sudah disi Sloc nya
var slocSelect = $('#sloc_select').val();
var total_weight = $('#total_weight').val();

if(slocSelect && total_weight) {
Swal.fire({
  title: "Are you sure?",
  text: "You want approve this box?",
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
        success: function(res) {
            var data = JSON.parse(res);
            if (data.status) {
				var qrcode = new QRCode(document.getElementById("qrcode"), {
				text: data.no_box,
				width: 150,
				height: 150,
				correctLevel: QRCode.CorrectLevel.H
			});
			
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
        error: function(xhr, ajaxOptions, thrownError) {
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
function deleteItem(id){
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
        success: function(res) {
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
        error: function(xhr, ajaxOptions, thrownError) {
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