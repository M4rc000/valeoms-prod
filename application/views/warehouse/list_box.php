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
					<table class="table datatable table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>No Box</th>
								<th>Total Weight</th>
								<th>SLoc</th>
								<th>Detail</th>
							</tr>
						</thead>
						<tbody>
							<?php $number = 0;
							foreach ($list_box as $box) :
								$number++ ?>
								<tr>
									<td><?= $number; ?></td>
									<td><?php echo $box['no_box']; ?></td>
									<td><?php echo $box['weight']; ?> Kg</td>
									<td>
										<?php echo $box['sloc_name']; ?>
									</td>
									<td>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal1<?= $box['id_box']; ?>" onclick="getDetailBox(<?= $box['id_box']; ?>, '<?= $box['no_box']; ?>')">
                                        <i class="bx bx-show" style="color: white;"></i>
                                    </button>
                                    </td>
								</tr>
							<?php endforeach; ?>
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


<!-- DETAIL MODAL-->
<?php foreach ($list_box as $box) : ?>
<div class="modal fade" id="detailModal1<?= $box['id_box']; ?>" tabindex="-1"  style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Box</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal()"></button>
			</div>
			<div class="modal-body">
                <div class="row mb-2">
                    <b>No Box : <?= $box['no_box'] ?></b>
                </div>
                <div class="row mb-2">
				<label class="col-sm-3 col-form-label">
					<b>Total weight (kg)</b>
				</label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="total_weight" value="<?= $box['weight'] ?>" disabled>
				</div>
				
				<label class="col-sm-2 col-form-label">
					<b>SLoc</b>
				</label>
				<div class="col-sm-4 mb-4">
                    <input type="text" class="form-control" id="total_weight" value="<?= $box['sloc_name'] ?>" disabled>
				</div>
			</div>
            <table class="table datatable table-bordered">
                <thead>
                	<tr>
                		<th>#</th>
                		<th>Reference Number</th>
                		<th>Material</th>
                		<th>QTY</th>
                		<th>UOM</th>
                	</tr>
                </thead>
                <tbody id="detailTable<?= $box['id_box']; ?>">
                    </tbody>
			</table>
            <div class="row mt-2 mb-2">
					<div class="col-md" style="margin-left: 20px;">
						<b>Barcode</b>
					</div>
				</div>
				<div class="col-md ms-3 mb-4 mt-3">
					<div id="qrcode<?= $box['id_box']?>"></div>
				</div>
                </div>
               
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  onclick="closeModal(<?= $box['id_box']; ?>)">Close</button>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    $(document).ready(function() {
        $('#detailModal1<?= $box['id_box']; ?> tbody').empty();
    });
        
// Membuat objek untuk menyimpan status QR code yang sudah dibuat
var qrCodeCreated = {};

function getDetailBox(id, no_box) {
    console.log(no_box);
   
        $.ajax({
            url: '<?php echo base_url('warehouse/get_detail_box'); ?>',
            type: 'POST',
            data: {
                id: id,
            },
            success: function(res) {
                var data = JSON.parse(res);
                if (data.status) {
                    $('#detailModal1' + id).modal('show');
                    $('#detailModal1' + id + ' tbody').empty();

                    var dt = data.dt;
                    for (var i = 0; i < dt.length; i++) {
                        var row = '<tr>' +
                            '<td style="text-align: left;">' + (i + 1) + '</td>' +
                            '<td style="text-align: left;">' + dt[i].id_material + '</td>' +
                            '<td style="text-align: left;">' + dt[i].material_desc + '</td>' +
                            '<td style="text-align: left;">' + dt[i].qty + '</td>' +
                            '<td style="text-align: left;">' + dt[i].uom + '</td>' +
                            '</tr>';
                        $('#detailModal1' + id + ' tbody').append(row);
                    }
                    if (!qrCodeCreated[id]) { // Periksa apakah QR code sudah dibuat untuk ID kotak ini
                    var qrcode = new QRCode(document.getElementById("qrcode" + id), {
                        text: no_box,
                        width: 150,
                        height: 150,
                        correctLevel: QRCode.CorrectLevel.H
                    });


                    // Set status QR code sudah dibuat untuk ID kotak ini
                    qrCodeCreated[id] = true;
                            } else {
                    $('#detailModal1' + id).modal('show');
                }
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

function closeModal(id){
    $('#detailModal1' + id + ' tbody').empty();
}


</script>