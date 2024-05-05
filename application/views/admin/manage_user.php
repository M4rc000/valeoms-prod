<section class="section">
	<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal" style="color: white">
		New user
	</button>
	<div class="row">
		<div class="col-lg-12">
			<div class="card">
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
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Role</th>
                    <th>Active</th>
                    <th>Date Joined</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
					<?php $number = 0; foreach($users as $usr) : $number++?>
						<tr>
							<td><?=$number;?></td>
							<td><?=$usr['username'];?></td>
							<td><?=$usr['name'];?></td>
							<td><?=$usr['gender'];?></td>
							<td>
							<?php
								if ($usr['role_id'] == 1) {
									echo 'Administrator';
								} elseif ($usr['role_id'] == 2) {
									echo 'Warehouse';
								} else {
									echo 'Production';
								}
							?>
							</td>
							<td><?=$usr['is_active'] == 1 ? '<i class="bx bxs-check-circle ps-4" style="color: #012970"></i>' : '<i class="bx bxs-x-circle" style="color: #012970"></i>'; ?></td>
							<td><?= date('d M Y H:i', strtotime($usr['date_joined'])); ?></td>
							<td>
								<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal<?=$usr['id'];?>">
									<i class="bx bxs-edit" style="color: white;"></i>
								</button>
								<button class="btn btn-danger ms-1" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$usr['id'];?>">
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
	<?= form_open_multipart('admin/AddUser'); ?>
		<div class="modal-header">
			<h5 class="modal-title">Add User</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="row ps-2">
				<div class="col-4">
					<label for="name" class="form-label">Name</label>
					<input type="text" class="form-control" id="name" name="name" required>
				</div>
				<div class="col-4">
					<label for="username" class="form-label">Username</label>
					<input type="text" class="form-control" id="username" name="username" required>
				</div>
				<div class="col-4">
					<label for="password" class="form-label">Password</label>
					<input type="password" class="form-control" id="password" name="password" required>
				</div>
			</div>
			<div class="row ps-2 mt-3">
				<div class="col-4">
					<label for="gender" class="form-label">Gender</label>
					<select id="gender" class="form-select" required name="gender">
						<option value="male">Male</option>
						<option value="female">Female</option>
				  </select>
				</div>
				<div class="col-4">
					<label for="role" class="form-label">Role</label>
					<select id="role" class="form-select" required name="role">
						<option value="1">Administrator</option>
						<option value="2">Warehouse</option>
						<option value="3">Production</option>
						<option value="4">Finnish Good</option>
				  </select>
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
<?php foreach($users as $usr) : ?>
	<div class="modal fade" id="editModal<?=$usr['id'];?>" tabindex="-1">
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
						<label for="name" class="form-label">Name</label>
						<input type="text" class="form-control" id="name" name="name" value="<?=$usr['name'];?>">
						<input type="text" class="form-control" id="id" name="id" value="<?=$usr['id'];?>" hidden>
					</div>
					<div class="col-4">
						<label for="username" class="form-label">Username</label>
						<input type="text" class="form-control" id="username" name="username" value="<?=$usr['username'];?>">
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
							<option value="male" <?=$usr['gender'] == 'male' ? 'selected' : '' ;?>>Male</option>
							<option value="female" <?=$usr['gender'] == 'male' ? 'selected' : '' ;?>>Female</option>
					</select>
					</div>
					<div class="col-4">
						<label for="role" class="form-label">Role</label>
						<select id="role" class="form-select" name="role">
							<option value="1" <?=$usr['role_id'] == '1' ? 'selected' : '' ;?>>Administrator</option>
							<option value="2" <?=$usr['role_id'] == '2' ? 'selected' : '' ;?>>Warehouse</option>
							<option value="3" <?=$usr['role_id'] == '3' ? 'selected' : '' ;?>>Production</option>
							<option value="4" <?=$usr['role_id'] == '4' ? 'selected' : '' ;?>>Finnish Good</option>
					</select>
					</div>
					<div class="col-4">
						<label for="active" class="form-label">Active</label>
						<select id="active" class="form-select" name="active">
							<option value="1" <?=$usr['is_active'] == '1' ? 'selected' : '' ;?>>Active</option>
							<option value="0" <?=$usr['is_active'] == '0' ? 'selected' : '' ;?>>Not active</option>
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
<?php foreach($users as $usr) : ?>
	<?= form_open_multipart('admin/deleteUser'); ?>
		<div class="modal fade" id="deleteModal<?= $usr['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" value="<?= $usr['id']; ?>" style="display: none;">
					<p><b>Username</b> : <?=$usr['username'];?></p>
					<p><b>Name</b> : <?=$usr['name'];?></p>
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