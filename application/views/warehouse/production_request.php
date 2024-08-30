
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
					<table class="table datatable table-bordered" width="100%">
						<thead>
							<tr>
								<th style="width: 5%;">#</th>
								<th>Request No</th>
								<th>Production Plan</th>
								<th>Production Description</th>
								<th>Production Plan Qty</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php $number = 0;
							foreach ($production_request as $request) :
								$number++ ?>
								<tr>
									<td><?= $number; ?></td>
									<td><?= $request['Id_request']; ?></td>
									<td><?= $request['Production_plan']; ?></td>
									<td>
										<?= $request['Fg_Desc']; ?>
									</td>
									<td><?= $request['Material_need']; ?></td>
									<td>
                                    <?php  if($request['status_request'] == 'NEW'){ ?>
                                    <button class="btn btn-success" data-bs-toggle="modal" onclick="getDetailRequest(<?= $request['id']; ?>, '<?= $request['Production_plan']; ?>')" data-bs-target="#detailModal1<?= $request['id']; ?>" >
                                    Approve
                                    </button>
                                    <?php } else if($request['status_request'] == 'APPROVED'){ ?>
                                    <button type="button" class="btn btn-secondary" disabled>Approved</button>
                                    <button type="button" class="btn btn-warning" onclick="printReq('<?= $request['Production_plan']; ?>')"><i class="bi bi-printer"></i> Print</button>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-danger" disabled>Rejected</button>
                                        <button type="button" class="btn btn-warning" onclick="printReq('<?= $request['Production_plan']; ?>')"><i class="bi bi-printer"></i> View</button>
                                    <?php } ?>

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
<?php foreach ($production_request as $request): ?>
<div class="modal fade" id="detailModal1<?= $request['id']; ?>" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 1000px!important;">
            <div class="modal-header">
                <h5 class="modal-title">Detail Production Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                    <label for="productId" class="col-sm-4 col-form-label"><b>Product ID</b></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= $request['Production_plan'];?>"  name="productId" id="productId<?= $request['id']; ?>" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="productDescription" class="col-sm-4 col-form-label"><b>Product Description</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="<?= $request['Fg_Desc'];?>" id="productDescription<?= $request['id']; ?>" name="productDescription" value="${productDescription}" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="qty" class="col-sm-4 col-form-label"><b>Qty Production Planning</b></label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" value="<?= $request['Material_need'];?>" id="qty" name ="qty" min="1" readonly>
                    </div>
                </div>
                <table class="table datatable table-bordered" >
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">ID Material</th>
                            <th style="width: 30%;">Material Desc</th>
                            <th style="width: 10%;">Material Need</th>
                            <th style="width: 10%;">Materail Request </th>
                            <th style="width: 15%;">Sloc</th>
                            <th style="width: 15%;">No Box</th>
                        </tr>
                    </thead>
                    <tbody id="detailTable<?= $request['id']; ?>" >
                      
                    </tbody>
                </table>
                <div class="modal-footer">
                <?php if($request['status_request'] == 'NEW') {?>
                    <button type="button" class="btn btn-danger" onclick="rejectRequest('<?= $request['Production_plan'] ?>')">Reject</button>
                    <button type="button" class="btn btn-success" onclick="approveRequest('<?= $request['Production_plan'] ?>')">Approve</button>
                <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php endforeach; ?>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
$(document).ready(function() {
});

    function closeModal(id) {
        $('#detailModal' + id).modal('hide');
        $('#detailModal' + id + ' tbody').empty();
    }

    function getDetailRequest(id, product_plan) {
        $.ajax({
            url: '<?php echo base_url('warehouse/get_detail_request'); ?>',
            type: 'POST',
            data: {
                Production_plan: product_plan,
            },
            success: function(res) {
                var data = JSON.parse(res);
                $('#detailModal1' + id + ' tbody').empty();
                if (data.status && data.dt.length > 0) {
                    $('#detailModal1' + id).modal('show');
                    var dt = data.dt;
                    for (var i = 0; i < dt.length; i++) {
                        var row = '<tr>' +
                            '<td style="text-align: left;">' + (i + 1) + '</td>' +
                            '<td style="text-align: left;">' + dt[i].Id_material + '</td>' +
                            '<td style="text-align: left;">' + dt[i].Material_desc + '</td>' +
                            '<td style="text-align: left;">' + dt[i].Material_need + '</td>' +
                            '<td style="text-align: left;">' + dt[i].Qty + '</td>' +
                            '<td style="text-align: left;">' + dt[i].sloc_name + '</td>' +
                            '<td style="text-align: left;">' + dt[i].no_box + '</td>' +
                            '</tr>';
                        $('#detailModal1' + id + ' tbody').append(row);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Incomplete!',
                        text: 'Please edit the request data to complete the information for this request.'
                    }).then(function() {
                        window.location.reload();
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

    function approveRequest(production_plan){
        Swal.fire({
            title: "Are you sure?",
            text: "You want to approve this production request?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Approve"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('warehouse/approveProductionRequest'); ?>',
                    type: 'POST',
                    data: {
                        production_plan: production_plan,
                    },
                    success: function(res) {
                        var data = JSON.parse(res);
                        if (data.status) {
                            window.open('<?php echo base_url().$this->router->fetch_class(); ?>/print_request/'+ production_plan, '_blank' );
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
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
    }
    function rejectRequest(production_plan){
        Swal.fire({
            title: "Are you sure?",
            text: "You want to reject this production request?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "yes"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?php echo base_url('warehouse/rejectProductionRequest'); ?>',
                    type: 'POST',
                    data: {
                        production_plan: production_plan,
                    },
                    success: function(res) {
                        var data = JSON.parse(res);
                        if (data.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Production Request Has Been Rejected.'
                            }).then((result) => {
                                if (result.isConfirmed || result.isDismissed) {
                                    location.reload(); 
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
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
    }

    function printReq(production_plan){
        window.open('<?php echo base_url().$this->router->fetch_class(); ?>/print_request/'+ production_plan, '_blank' );
    }

</script>