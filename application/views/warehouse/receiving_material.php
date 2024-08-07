<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="row mb-2 mt-5">
                    <label for="inputText" class="col-sm-2 col-form-label ms-3">
                        <b>Total weight (kg)</b>
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control">
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
                    <button type="button" class="btn btn-primary mb-2 mt-2 ml-5" data-bs-toggle="modal"
                        data-bs-target="#addModal1" style="color: white">
                        + Add
                    </button>

                    <table class="table datatable table-bordered">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th>ID Box</th>
                                <th>Total Weight</th>
                                <th>SLOC</th>
                                <th>Detail isi Box</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0;
							foreach ($receiving_material as $material):
								$number++ ?>
                            <tr>
                                <td><?= $number; ?></td>
                                <td><?php echo $material['id_box']; ?></td>
                                <td><?php echo $material['weight']; ?></td>
                                <td>
                                    <?php echo $material['sloc']; ?>
                                </td>
                                <td>
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="">
                                        <i class="bx bxs-edit" style="color: white;">Detail</i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="row mt-3 mb-3">
                        <label class="col-sm-2 col-form-label">
                            <b>
                                Select SLoc
                            </b>
                        </label>
                        <div class="col-sm-3">
                            <select class="form-select" aria-label="Default select example">
                                <option value="1">A1.1</option>
                                <option value="2">A1.2</option>
                                <option value="3">A1.3</option>
                            </select>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-10"></div>
                            <div class="col-md">
                                <button class="btn btn-primary" onclick="getBarcode()">
                                    Approve
                                </button>
                            </div>
                        </div>
                        <div class="row mt-5 mb-3">
                            <div class="col-md">
                                <b>Barcode</b>
                            </div>
                        </div>
                        <div class="col-md ms-5 mt-5">
                            <div id="qrcode"></div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-10"></div>
                            <div class="col-md">
                                <form action="<?= base_url('warehouse/clearData') ?>" method="post">
                                    <button class="btn btn-success">
                                        <i class="bx bx-revision"></i>
                                    </button>
                                </form>
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
            <form id="material">
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
                        <div class="col-6">
                            <label for="material" class="form-label">Material Part Name</label>
                            <input type="text" class="form-control" id="material" name="material" required>
                        </div>
                        <div class="col-6">
                            <label for="qty" class="form-label">Quantity</label>
                            <input type="text" class="form-control" id="qty" name="qty" required>
                        </div>
                        <input type="hidden" id="receiving_date" name="receiving_date">
                    </div>
                    <div class="row ps-2 mt-3">
                        <div class="col-4">
                            <label for="uom" class="form-label">UOM</label>
                            <input type="text" class="form-control" id="uom" name="uom" required>
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
<?php foreach ($receiving_material as $material): ?>
<div class="modal fade" id="editModal<?= $material['id_box']; ?>" tabindex=" -1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?= form_open_multipart('admin/editReceivingMaterial'); ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Receiving</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row ps-2">
                    <div class="col-4">
                        <label for="name" class="form-label">Material Part Number</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $material['name']; ?>">
                        <input type="text" class="form-control" id="id" name="id" value="<?= $material['id_box']; ?>"
                            hidden>
                    </div>
                    <div class="col-4">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?= $usr['username']; ?>">
                    </div>
                    <div class="col-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                </div>
                <div class="row ps-2 mt-3">
                    <div class="col-4">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" class="form-select" name="gender">
                            <option value="male" <?= $material['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?= $material['gender'] == 'male' ? 'selected' : ''; ?>>Female
                            </option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" class="form-select" name="role">
                            <option value="1" <?= $material['role_id'] == '1' ? 'selected' : ''; ?>>Administrator
                            </option>
                            <option value="2" <?= $material['role_id'] == '2' ? 'selected' : ''; ?>>Warehouse</option>
                            <option value="3" <?= $material['role_id'] == '3' ? 'selected' : ''; ?>>Production</option>
                            <option value="4" <?= $material['role_id'] == '4' ? 'selected' : ''; ?>>Finnish Good
                            </option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="active" class="form-label">Active</label>
                        <select id="active" class="form-select" name="active">
                            <option value="1" <?= $material['is_active'] == '1' ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?= $material['is_active'] == '0' ? 'selected' : ''; ?>>Not active
                            </option>
                        </select>
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
                <p><b>Name</b> : <?= $usr['name']; ?>
                </p>
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
<script>
function closeModal() {
    $('#reference_number').val("");
    $('#material').val("");
    $('#qty').val("");
    $('#uom').val("");
    $('#size').val('0');
}

function getMaterial() {
    var refnumber = $('#reference_number').val();
    $('#material').val("");
    $('#uom').val("");
    $.ajax({
        url: '<?php echo base_url('warehouse/get_material_data'); ?>',
        type: 'POST',
        data: {
            refnumber: refnumber
        },
        success: function(res) {
            var data = JSON.parse(res);
            console.log(data);
            $('#material').val(data.material);
            $('#uom').val(data.uom);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError);
        }
    })
}

function getBarcode() {
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: "<?= base_url('warehouse/') ?>",
        width: 150,
        height: 150,
        correctLevel: QRCode.CorrectLevel.H
    });
}

function setReceivingDate() {
    var now = new Date();
    var formattedDate = now.getFullYear() + '-' + (now.getMonth() + 1).toString().padStart(2, '0') + '-' + now.getDate()
        .toString().padStart(2, '0') + ' ' +
        now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0') + ':' + now
        .getSeconds().toString().padStart(2, '0');
    $('#receiving_date ').val(formattedDate);
}
</script>