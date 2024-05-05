<section class="section">
	<button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#addModal" style="color: white">
		New menu
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
                    <th>ID</th>
                    <th>Menu</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
					<?php $number = 0; foreach($menus as $menu) : $number++?>
						<tr>
							<td><?=$number;?></td>
							<td><?=$menu['id'];?></td>
							<td><?=$menu['menu'];?></td>
							<td>
								<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal<?=$menu['id'];?>">
									<i class="bx bxs-edit" style="color: white;"></i>
								</button>
								<button class="btn btn-danger ms-1" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$menu['id'];?>">
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
	<?= form_open_multipart('admin/AddMenu'); ?>
		<div class="modal-header">
			<h5 class="modal-title">Add Menu</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		</div>
		<div class="modal-body">
			<div class="row ps-2">
				<div class="col-4">
					<label for="id" class="form-label">ID</label>
					<input type="text" class="form-control" id="id" name="id" required>
				</div>
				<div class="col-4">
					<label for="menu" class="form-label">Menu</label>
					<input type="text" class="form-control" id="menu" name="menu" required>
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
<?php foreach($menus as $menu) : ?>
	<div class="modal fade" id="editModal<?=$menu['id'];?>" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
		<?= form_open_multipart('admin/EditMenu'); ?>
			<div class="modal-header">
				<h5 class="modal-title">Edit Menu</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row ps-2">
					<div class="col-4">
						<label for="menu" class="form-label">ID</label>
						<input type="text" class="form-control" id="id" name="id" value="<?=$menu['id'];?>">
					</div>
					<div class="col-4">
						<label for="menu" class="form-label">Menu</label>
						<input type="text" class="form-control" id="menu" name="menu" value="<?=$menu['menu'];?>">
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
<?php foreach($menus as $menu) : ?>
	<?= form_open_multipart('admin/DeleteMenu'); ?>
		<div class="modal fade" id="deleteModal<?= $menu['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
				</div>
				<div class="modal-body">
					<input type="text" name="id" id="id" value="<?= $menu['id']; ?>" style="display: none;">
					<p><b>ID</b> : <?=$menu['id'];?></p>
					<p><b>Menu</b> : <?=$menu['menu'];?></p>
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