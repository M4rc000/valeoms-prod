<section>
	<div class="card">
		<div class="card-body">
            <div class="row mt-3">
                <?= form_open_multipart('warehouse/AddBoxMaterial'); ?>
                <div class="row mt-5 mx-2">
                    <div class="col-md">
                        <div class="table-responsive">
                            <!-- GET USER -->
                            <input type="text" id="user" name="user" value="<?=$name['username'];?>" hidden>
                            <table class="table" id="table-rwd">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Return ID</th>
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
                                        <td class="text-center"><?=$rw['box_type'];?></td>
                                        <td class="text-center"><?=$rw['box_weight'];?></td>
                                        <td class="text-center" class="text-center"><?=$rw['status'] == 1 ? '<i class="bi bi-hourglass-split"></i>' : '<i class="bi bi-check-circle-fill" style="color: green"></i>'; ?></td>
                                        <td class="text-center"><?= date('Y-m-d', strtotime($rw['Crtdt'])); ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('warehouse/approveReturnRequest/' . $rw['id']); ?>">
                                                <span class="badge bg-success"><i class="bi bi-check-circle"></i></span>
                                            </a>
                                            <a href="#" id="reject-request" data-idreturn="<?=$rw['id_return'];?>">
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


<script>
    $(document).ready(function (){
        $('#table-rwd').DataTable();

        $('#reject-request').on('click', function(e) {
            e.preventDefault();

            var idReturn = $(this).data('idreturn');
            var user = $('#user').val();

            Swal.fire({
                title: "Are you sure to reject ?",
                html: `${idReturn}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Reject"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?=base_url('warehouse/RejectReturnRequest');?>',
                        type: 'POST',
                        data: { idReturn, user },
                        success: function(res) {
                            if (res == 1) {
                                $('#reject-request').closest('tr').remove();
                                
                                Swal.fire({
                                    title: "Success",
                                    html: `Return ID ${idReturn} has been rejected`,
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    html: `Failed to reject Return ID ${idReturn}`,
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + error);
                        }
                    });
                }
            });

        });
    })
</script>