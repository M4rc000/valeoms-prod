<div class="content-wrapper">
	<div class="row">
		<div class="col-sm">
			<div class="card shadow" style="border-bottom: 2px solid #4b49ac; height: 40px; border-radius: 5px">
				<div class="card-body">
					<h5 class="text-left mb-5" style="line-height: 0px; font-size: 14px; font-weight: 100;">
						<span style="font-weight: 700;"><?= ucfirst($menus); ?></span> / <?= $title; ?>
					</h5>
				</div>
			</div>
		</div>
	</div>
	<br>
	<div class="card shadow">
		<div class="card-body">
			<div class="container">
				<div class="row">
					<div class="col-12 grid-margin">
						<div class="container">
						<div class="card-body">
								<h4 class="card-title"><?= $title; ?></h4>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg grid-margin stretch-card">
						<div class="card shadow" style="border-left: 2px solid #ffc100;">
							<div class="card-body">
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
								<h4 class="pt-2 pb-3 text-center"><strong>TABLE USER</strong></h4>
								<button class="btn btn-primary ml-3 mb-3" data-bs-toggle="modal"
									data-bs-target="#AddModal"><i class="ti-plus pt-5"
										style="font-size: small;"></i><span class="pl-3">New User</span></button>
								<div class="table-responsive py-3">
									<table class="table" id="tbl-friend">
										<thead>
											<tr>
												<th class="text-center">#</th>
												<th class="text-center">Name</th>
												<th class="text-center">Username</th>
												<th class="text-center">Gender</th>
												<th class="text-center">Date Joined</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach($users as $u) : ?>
											<tr>
												<td class="text-center"><img src="<?= base_url('assets/images/profile/') . $u['image']; ?>" alt="" style="width: 40px; height: 40px"></td>
												<td class="text-center"><?= $u['name']; ?></td>
												<td class="text-center"><?= $u['username']; ?></td>
												<td class="text-center"><?= $u['gender'] == 'Male' ? '<i class="fas fa-xl fa-male"></i>' : '<i class="fas fa-xl fa-female"></i>'; ?></td>
												<td class="text-center"><?= date('d-m-Y H:i', strtotime($u['date_joined'])); ?></td>
												<td class="text-center">
													<button class="btn btn-success" style="padding: 10px">
														<i class="far fa-add" style="color: white; margin: auto"></i>
													</button>
													<button class="btn btn-danger" style="padding: 10px;">
														<i class="fas fa-trash" style="margin: auto"></i>
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
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	const TableFriend = new DataTable('#tbl-friend');
</script>