
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
					<button type="button" class="btn btn-primary" id="search_button" onclick="getBox()">Search</button>
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
					<table class="table table-bordered" >
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="height: 400px !important">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
			<div class="col-sm-12 mb-3">
				<label style="font-weight: bold; margin-bottom: 10px;"> Quantity</label>
				<input type="text" class="form-control" id="qty" placeholder="">
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
	 $(document).ready(function() {
        $('#id_box').select2();
        $('#id_box_modal').select2({
            dropdownParent: $('#unpackModal') // Ensure the dropdown is appended to the modal
        });
    });

	function getBox() {
    var id_box = $('#id_box').val();

    $.ajax({
        url: '<?php echo base_url('warehouse/get_box_details');?>',
        type: 'POST',
        data: {
            id_box: id_box,
        },
        success: function(res) {
            var data = JSON.parse(res);
            console.log(data);
			$('#detailsBody').empty();

            $.each(data, function(index, detail) {
                $('#detailsBody').append(`
                    <tr>
                        <td>${detail.id_material}</td>
                        <td>${detail.material_desc}</td>
                        <td>${detail.Sloc}</td>
                        <td>${detail.qty}</td>
                        <td>${detail.uom}</td>
						<td>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal"
                                onclick="fillEditModal(${detail.qty}, ${detail.id_box_detail})">
                                Edit
                            </button>
                        </td>
                    </tr>
                `);
            });
			$('.card-body').show();

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
function fillEditModal(qty, id_box_detail) {
    $('#qty').val(qty); 
    $('#id_box_detail').val(id_box_detail); 
}

function saveEdit() {
        var id_box_detail = $('#id_box_detail').val();
		var qty = $('#qty').val();
        $.ajax({
			url: '<?php echo base_url('warehouse/save_cycle_count');?>',
            type: 'POST',
            data: {
                id_box_detail: id_box_detail,
				qty : qty
            },
            success: function(response) {
				var data = JSON.parse(response);
                    if (data.status) {
					Swal.fire({
						title: "Success!",
						text: "Material has been edited.",
						icon: "success"
					}).then(function() {
                    $('#editModal').modal('hide'); // Close the modal
					$('#qty').val('');
					$('#id_box_detail').val('');
					// Reload the page
					 getBox();
                });

				} else {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'An error occurred while processing your request.'
						
					});
					window.location.reload();

				}
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error('Error occurred while saving changes.');
            }
        });
    }


</script>