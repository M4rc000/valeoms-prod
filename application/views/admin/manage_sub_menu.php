<style>
	.select2-container {
		z-index: 9999;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>
<section class="section">
	<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal" style="color: white">
		New submenu
	</button>
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body table-responsive mt-2">
              <table class="table datatable">
                <thead>
                  <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Menu</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Url</th>
                    <th class="text-center">Icon</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
					<?php $number = 0; foreach($submenus as $sb) : $number++?>
						<tr class="text-center">
							<td><?=$number;?></td>
							<td>
								<?php 
									if($sb['menu_id'] == 1){
										echo 'Admin';
									}
									elseif($sb['menu_id'] == 2){
										echo 'Warehouse';
									}
									elseif($sb['menu_id'] == 3){
										echo 'Production';
									}
									elseif($sb['menu_id'] == 4){
										echo 'FG';
									}
									else{
										echo 'User';
									}
								?>
							</td>
							<td><?=$sb['title'];?></td>
							<td><?=$sb['url'];?></td>
							<td><i class="<?=$sb['icon'];?>"></i></td>
							<td><?=$sb['is_active'] == 1 ? '<i class="bx bxs-check-circle ps-4" style="color: #012970"></i>' : '<i class="bx bxs-x-circle ps-4" style="color: #012970"></i>'; ?></td>
							<td>
								<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal<?=$sb['id'];?>">
									<i class="bx bxs-edit" style="color: white;"></i>
								</button>
								<button class="btn btn-danger ms-1" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$sb['id'];?>">
									<i class="bx bxs-trash"></i>
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

<!-- ADD MODAL-->
<div class="modal fade" id="addModal" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
	<?= form_open_multipart('admin/AddSubMenu'); ?>
		<div class="modal-header">
			<h5 class="modal-title">Add SubMenu</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<!-- GET USER -->
			<input type="text" class="form-control" id="user" name="user" value="<?=$user['username'];?>" hidden> 
			<div class="row ps-2">
				<div class="col-4">
					<label for="menu_id" class="form-label">Menu ID</label>
					<select id="menu_id" class="form-select" required name="menu_id">
						<?php foreach($menus as $menu) : ?>
							<option value="<?=$menu['id'];?>"><?=$menu['id'];?> | <?=$menu['menu'];?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-4">
					<label for="title" class="form-label">Title</label>
					<input type="text" class="form-control" id="title" name="title" required>
				</div>
				<div class="col-4">
					<label for="url" class="form-label">Url</label>
					<input type="text" class="form-control" id="url" name="url" required>
				</div>
			</div>
			<div class="row ps-2 mt-4">
				<div class="col-4">
					<label for="icon" class="form-label">Icon</label>
					<div class="input-group mb-3">
						  <span class="input-group-text icon-show" id="basic-addon1"></span>
						  <input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="icon" name="icon">
					</div>
				</div>
				<div class="col-4">
					<label for="active" class="form-label">Active</label>
					<select id="active" class="form-select" required name="active">
						<option value="1">Active</option>
						<option value="0">Not active</option>
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

<!-- EDIT MODAL-->
<?php foreach($submenus as $sb) : ?>
	<div class="modal fade" id="editModal<?=$sb['id'];?>" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
		<?= form_open_multipart('admin/EditSubMenu'); ?>
			<div class="modal-header">
				<h5 class="modal-title">Edit SubMenu</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			<!-- GET USER -->
			<input type="text" class="form-control" id="user" name="user" value="<?=$user['username'];?>" hidden> 
				<div class="row ps-2">
					<div class="col-4">
						<label for="menu_id" class="form-label">Menu ID</label>
						<select id="menu_id" class="form-select" required name="active">
							<?php foreach($menus as $menu): ?>
								<option <?= $menu['id'] == $sb['menu_id'] ? 'selected' : '';?> value="<?=$menu['id'];?>"><?= $menu['menu']?></option>
							<?php endforeach; ?>
						</select>
						<input type="text" class="form-control" id="id" name="id" value="<?=$sb['id'];?>" hidden>
					</div>
					<div class="col-4">
						<label for="title" class="form-label">Title</label>
						<input type="text" class="form-control" id="title" name="title" value="<?=$sb['title'];?>">
					</div>
					<div class="col-4">
						<label for="url" class="form-label">Url</label>
						<input type="text" class="form-control" id="url" name="url" value="<?=$sb['url'];?>">
					</div>
				</div>
				<div class="row ps-2 mt-4">
					<div class="col-4">
						<label for="icon" class="form-label">Icon</label>
						<div class="input-group mb-3">
							<span class="input-group-text icon-show" id="basic-addon1"></span>
							<input type="text" class="form-control" aria-label="Username" aria-describedby="basic-addon1" id="icon" name="icon" value="<?=$sb['icon'];?>">
						</div>
					</div>
					<div class="col-4">
						<label for="active" class="form-label">Active</label>
						<select id="active" class="form-select" required name="active">
							<option <?= $sb['is_active'] == 1 ? 'selected' : '';?> value="1">Active</option>
							<option <?= $sb['is_active'] == 0 ? 'selected' : '';?> value="0">Not active</option>
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
<?php foreach($submenus as $sb) : ?>
	<?= form_open_multipart('admin/DeleteSubMenu'); ?>
		<div class="modal fade" id="deleteModal<?= $sb['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" value="<?= $sb['id']; ?>" style="display: none;">
					<p><b>Menu ID</b> : <?=$sb['menu_id'];?></p>
					<p><b>Title</b> : <?=$sb['title'];?></p>
					<p><b>Url</b> : <?=$sb['url'];?></p>
					<p><b>Icon</b> : <i class="<?=$sb['icon'];?>"></i></p>
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


<script>
  $(document).ready(function(){
    $('#icon').on('input', function(){
      var iconValue = $(this).val();
      $('.icon-show').html('<i class="' + iconValue + '"></i>');
    });

	$('.modal').on('shown.bs.modal', function () {
		$(this).find('#menu_id').select2({
			dropdownParent: $(this)
		});
		$(this).find('#active').select2({
			dropdownParent: $(this)
		});
	});
  });
</script>



<!-- SWEET ALERT -->
<?php if ($this->session->flashdata('SUCCESS_AddSubMenu')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "<?= $this->session->flashdata('SUCCESS_AddSubMenu'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('FAILED_AddSubMenu')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('FAILED_AddSubMenu'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('SUCCESS_editSubMenu')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "<?= $this->session->flashdata('SUCCESS_editSubMenu'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('FAILED_editSubMenu')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('FAILED_editSubMenu'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('SUCCESS_DeleteSubMenu')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "<?= $this->session->flashdata('SUCCESS_DeleteSubMenu'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('FAILED_DeleteSubMenu')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('FAILED_DeleteSubMenu'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>