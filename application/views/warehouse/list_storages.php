<?php
$grouped_storage = array();

// Group data by material_desc
foreach ($list_storage as $storage) {
	if (!isset($grouped_storage[$storage['material_desc']])) {
		$grouped_storage[$storage['material_desc']] = $storage;
	} else {
		$grouped_storage[$storage['material_desc']]['total_qty'] += $storage['total_qty'];
	}
}
?>

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
                    <table class="table table-bordered" id="tbl-storage">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ProductID</th>
                                <th>Material</th>
                                <th>Qty Total</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$number = 0;
							foreach ($grouped_storage as $material => $storage):
								$number++;
								?>
                            <tr>
                                <td><?= $number; ?></td>
                                <td><?php echo $storage['product_id']; ?></td>
                                <td><?php echo $storage['material_desc']; ?></td>
                                <td><?php echo $storage['total_qty']; ?></td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#detailModal1<?= $storage['product_id']; ?>"
                                        onclick="getDetailStorage('<?= $storage['product_id']; ?>')">
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
                            <form action="<?= base_url('warehouse/clearData') ?>" method="post">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- DETAIL MODAL-->
<?php foreach ($grouped_storage as $material => $storage): ?>
<div class="modal fade" id="detailModal1_<?= $storage['product_id']; ?>" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Storage</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal('<?= $storage['product_id']; ?>')"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <label class="col-sm-2 col-form-label">
                        <b>Product ID</b>
                    </label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="total_weight" value="<?= $storage['product_id'] ?>"
                            disabled>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-sm-2 col-form-label">
                        <b>Material</b>
                    </label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" id="total_weight"
                            value="<?= $storage['material_desc'] ?>" disabled>
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
                    <tbody id="detailTable<?= $storage['product_id']; ?>">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                    onclick="closeModal('<?= $storage['product_id']; ?>')">Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
$(document).ready(function() {
    $('#detailModal1<?= $storage['product_id']; ?> tbody').empty();
});

function getDetailStorage(id) {
    console.log(id);
    $.ajax({
        url: '<?php echo base_url('warehouse/get_detail_storage'); ?>',
        type: 'POST',
        data: {
            id: id,
        },
        success: function(res) {
            var data = JSON.parse(res);
            if (data.status) {
                $('#detailModal1_' + id).modal('show');
                $('#detailModal1_' + id + ' tbody').empty();

                var dt = data.dt;
                for (var i = 0; i < dt.length; i++) {
                    var row = '<tr>' +
                        '<td style="text-align: left;">' + (i + 1) + '</td>' +
                        '<td style="text-align: left;">' + dt[i].sloc_name + '</td>' +
                        '<td style="text-align: left;">' + dt[i].total_qty + '</td>' +
                        '<td style="text-align: left;">' + dt[i].uom + '</td>' +
                        '</tr>';
                    $('#detailModal1_' + id + ' tbody').append(row);
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

function closeModal(id) {
    $('#detailModal1_' + id + ' tbody').empty();
}
</script>