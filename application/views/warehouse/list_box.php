<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card ml-5">
                <div class="card-body table-responsive mt-2">
                    <?php if ($this->session->flashdata('SUCCESS') != '') { ?>
                    <?= $this->session->flashdata('SUCCESS'); ?>
                    <?php } ?>
                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No Box</th>
                                <th>Total Weight</th>
                                <th>SLoc</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0;
							foreach ($list_box as $box):
								$number++ ?>
                            <tr>
                                <td><?= $number; ?></td>
                                <td><?php echo $box['no_box']; ?></td>
                                <td><?php echo $box['weight']; ?> Kg</td>
                                <td><?php echo $box['sloc_name']; ?></td>
                                <td>
                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#detailModal1<?= $box['id_box']; ?>"
                                        onclick="getDetailBox(<?= $box['id_box']; ?>, '<?= $box['no_box']; ?>')">
                                        <i class="bx bx-show" style="color: white;"></i>
                                    </button>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal1"
                                        onclick="editBox(<?= $box['id_box']; ?>)">
                                        <i class="bx bxs-edit" style="color: white;"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- EDIT MODAL -->
<div class="modal fade" id="editModal1" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editForm" action="<?= base_url('warehouse/edit_box'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Box</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeModal()"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id_box" name="id_box">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <label class="col-form-label">
                                <b>Total weight (kg)</b>
                            </label>
                            <input type="text" class="form-control" id="weight" name="weight" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">
                                <b>SLoc</b>
                            </label>
                            <input type="text" class="form-control" id="sloc" name="sloc" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <h5>Details</h5>
                            <table class="table table-bordered" id="detailsTable">
                                <thead>
                                    <tr>
                                        <th>Part Number</th>
                                        <th>Part Name</th>
                                        <th>QTY</th>
                                        <th>UOM</th>
                                    </tr>
                                </thead>
                                <tbody id="detailsBody">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tbody>
                            </table>
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
    $('#detailsBody').empty();
}

function editBox(id_box) {
    $.ajax({
        url: '<?= base_url('warehouse/get_box_details'); ?>',
        type: 'POST',
        data: {
            id_box: id_box
        },
        success: function(res) {
            var data = JSON.parse(res);
            $('#id_box').val(id_box);
            $('#weight').val(data[0].weight);
            $('#sloc').val(data[0].sloc);

            $('#detailsBody').empty();
            $.each(data, function(index, detail) {
                $('#detailsBody').append('<tr>' +
                    '<td><input type="hidden" name="details[' + index +
                    '][id_detail]" value="' + detail.id_detail + '">' +
                    '<input type="text" class="form-control" name="details[' + index +
                    '][id_material]" value="' + detail.id_material + '" required></td>' +
                    '<td><input type="text" class="form-control" name="details[' + index +
                    '][material_desc]" value="' + detail.material_desc + '" required></td>' +
                    '<td><input type="number" class="form-control" name="details[' + index +
                    '][qty]" value="' + detail.qty + '" required></td>' +
                    '<td><input type="text" class="form-control" name="details[' + index +
                    '][uom]" value="' + detail.uom + '" required></td>' +
                    '</tr>');
         
   });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    });
}
</script>