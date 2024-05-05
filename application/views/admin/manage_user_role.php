<section class="section">
	<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal" style="color: white">New Role</button>
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body table-responsive">
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
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>ID</th>
                    <th>Role</th>
                    <th>CrtDt</th>
                    <th>CrtBy</th>
                    <th>UpdDt</th>
                    <th>UpdBy</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
			<?php $number = 0; foreach($roles as $role) : $number++?>
                  <tr>
                    <td><?=$number;?></td>
                    <td><?=$role['id'];?></td>
                    <td><?=$role['role'];?></td>
                    <td><?=$role['crtdt'];?></td>
                    <td><?=$role['crtby'];?></td>
                    <td><?=$role['upddt'];?></td>
                    <td><?=$role['updby'];?></td>
					<td>
						<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?=$role['id'];?>">
							<i class="bx bxs-edit" style="color: white;"></i>
						</button>
						<button class="btn btn-success ms-1" data-bs-toggle="modal" data-bs-target="#editModal<?=$role['id'];?>">
							<i class="bx bxs-edit" style="color: white;"></i>
						</button>
						<button class="btn btn-danger ms-1">
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
	<?= form_open_multipart('admin/AddRole'); ?>
		<div class="modal-header">
			<h5 class="modal-title">Add User</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="row ps-2">
				<div class="col-4">
					<label for="id" class="form-label">ID</label>
					<input type="text" class="form-control" id="id" name="id" readonly>
				</div>
				<div class="col-4">
					<label for="role" class="form-label">Role</label>
					<input type="text" class="form-control" id="role" name="role" required>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Save changes</button>
		</div>
	</form>
		</div>
	</div>
</div>


<!-- EDIT MODAL-->
<?php foreach($roles as $role) : ?>
<div class="modal fade" id="editModal<?=$role['id'];?>" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
	<?= form_open_multipart('admin/editUser'); ?>
		<div class="modal-header">
			<h5 class="modal-title">Edit User</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="row ps-2">
				<div class="col-4">
					<label for="id" class="form-label">ID</label>
					<input type="text" class="form-control" id="id" name="id" value="<?=$role['id'];?>" readonly>
				</div>
				<div class="col-4">
					<label for="role" class="form-label">Role</label>
					<input type="text" class="form-control" id="role" name="role" value="<?=$role['role'];?>" required>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary">Save changes</button>
		</div>
	</form>
		</div>
	</div>
</div>
<?php endforeach; ?>

<!-- DELETE CONFIRM MODAL-->
<?php foreach($roles as $role) : ?>
<?= form_open_multipart('admin/deleteUser'); ?>
	<div class="modal fade" id="DeleteConfirmModal<?= $role['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="margin-top: -5rem">
	<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
		</div>
		<input type="text" name="id" id="id" value="<?= $role['id']; ?>" style="display: none;">
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
			<button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
		</div>
		</div>
	</div>
	</div>
</form>
<?php endforeach; ?>