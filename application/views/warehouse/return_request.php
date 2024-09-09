<section>
	<div class="card">
		<div class="card-body">
            <div class="row mt-3">
                <?= form_open_multipart('warehouse/AddBoxMaterial'); ?>
                <div class="row mt-3 mx-2">
                    <div class="col-md">
                        <div class="table-responsive">
                            <!-- GET USER -->
                            <input type="text" id="user" name="user" value="<?=$name['username'];?>" hidden>
                            <table class="table" id="table-rwd">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Return ID</th>
                                        <th class="text-center">Box No</th>
                                        <th class="text-center">Box Type</th>
                                        <th class="text-center">Box Weight</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Created at</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $number = 0; foreach($rwd as $rw): $number++?>
                                    <tr>
                                        <td class="text-center"><?=$number;?></td>
                                        <td class="text-center"><?=$rw['id_return'];?></td>
                                        <td class="text-center"><?=$rw['no_box'];?></td>
                                        <td class="text-center"><?=$rw['box_type'];?></td>
                                        <td class="text-center"><?=$rw['box_weight'];?></td>
                                        <td class="text-center" class="text-center"><?=$rw['status'] == 1 ? '<i class="bi bi-hourglass-split text-center"></i>' : '<i class="bi bi-check-circle-fill text-center" style="color: green"></i>'; ?></td>
                                        <td class="text-center"><?= date('d-M-Y', strtotime($rw['Crtdt'])); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('warehouse/approveReturnRequest/' . $rw['id']); ?>">
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i></span>
                                            </a>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#rejectModal<?=$rw['id_return'];?>">
                                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i></span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                </form>
            </div>
		</div>
	</div>
</section>

<!-- REJECT MODAL -->
<?php foreach($rwd as $rw): ?>
    <div class="modal fade" id="rejectModal<?= $rw['id_return']; ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <?= form_open_multipart('warehouse/RejectReturnRequest'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Return Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" id="user" name="user" value="<?= $name['username']; ?>" hidden> 
                        <div class="row ps-2">
                            <div class="col-4">
                                <label for="id_return" class="form-label">Return ID</label>
                                <input type="text" class="form-control" id="id_return" name="id_return" value="<?= $rw['id_return']; ?>" readonly>
                            </div>
                            <div class="col-4">
                                <label for="no_box" class="form-label">Box No</label>
                                <input type="text" class="form-control" id="no_box" name="no_box" value="<?= $rw['no_box']; ?>" readonly>
                            </div>
                            <div class="col-4">
                                <label for="box_type" class="form-label">Box Type</label>
                                <input type="text" class="form-control" id="box_type" name="box_type" value="<?= $rw['box_type']; ?>" readonly>
                            </div>
                            <div class="row mt-4 mb-3">
                                <label for="reject_description" class="col-sm-2 col-form-label">Reject Description</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="reject_description" name="reject_description" style="height: 100px" required></textarea>
                                </div>
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

<script>
    $(document).ready(function (){
        $('#table-rwd').DataTable();
    })
</script>

<!-- SWEET ALERT -->
<?php if ($this->session->flashdata('SUCCESS_RejectReturnRequest')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                text: "<?php echo $this->session->flashdata('SUCCESS_RejectReturnRequest'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>

<?php if ($this->session->flashdata('FAILED_RejectReturnRequest')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Failed",
                text: "<?php echo $this->session->flashdata('FAILED_RejectReturnRequest'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>