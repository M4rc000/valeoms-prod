
<section>
	<div class="row">
		<div class="col-lg-12">
			<div class="card ml-5">
			<div class="row mb-2 mt-5 mb-5" style="margin-left: 20px">
				<div class="col-sm-3">
					<input type="text" class="form-control" id="id_material" placeholder="Material ID">
				</div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-primary" id="search_button" onclick="getMaterialReport()">Search</button>
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
								<th>ProductID</th>
								<th>Material Desc</th>
								<th>Early Qty</th>
								<th>UOM</th>
								<th>Receiving Date</th>
								<th>Current Qty</th>
								<th>Last Update</th>
							</tr>
						</thead>
						<tbody id="material_table_body">
						<tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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

<!-- DETAIL MODAL-->
<?php foreach ($list_storage as $storage) :?>
<div class="modal fade" id="detailModal1_<?= $storage['product_id'];?>" tabindex="-1"  style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Storage</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeModal('<?= $storage['product_id'];?>')"></button>
			</div>
			<div class="modal-body">
                <div class="row mb-2">
                    <label class="col-sm-2 col-form-label">
                        <b>Product ID</b>
                    </label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="total_weight" value="<?= $storage['product_id']?>" disabled>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-2 col-form-label">
                        <b>Material</b>
                    </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="total_weight" value="<?= $storage['material_desc']?>" disabled>
                    </div>
			    </div>
                <table class="table datatable table-bordered">
                <thead>
                	<tr>
                		<th>#</th>
                		<th>Location</th>
                		<th>QTY</th>
                		<th>UOM</th>
                	</tr>
                </thead>
                <tbody id="detailTable<?= $storage['product_id'];?>">
                    </tbody>
			    </table>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"  onclick="closeModal('<?=$storage['product_id'];?>')">Close</button>
			</div>
		</div>
	</div>
</div>
<?php endforeach;?>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
function getMaterialReport() {
	var id_material = $('#id_material').val();

    $.ajax({
        url: '<?php echo base_url('warehouse/get_material_report');?>',
        type: 'POST',
        data: {
            id_material: id_material,
        },
        success: function(res) {
            var data = JSON.parse(res);
			console.log(data.dt.qty);
                if (data.status) {
					if(data.early_qty == 0){
						early_qty = data.dt.qty;
						qty = data.dt.qty;
					} else 
					{
						early_qty = data.early_qty;
						qty = parseInt(data.dt.qty) + parseInt(early_qty);
					}
					$('#material_table_body').html(`
                    <tr>
                        <td>${data.dt.reference_number}</td>
                        <td>${data.dt.material}</td>
                        <td>${early_qty}</td>
                        <td>${data.dt.uom}</td>
                        <td>${data.dt.receiving_date}</td>
                        <td>${qty}</td>
                        <td>${data.dt.last_update}</td>
                    </tr>
                `);
                $('.card-body').show();
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




</script>