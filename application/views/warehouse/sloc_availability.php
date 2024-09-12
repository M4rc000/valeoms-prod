<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">SLoc Availability</h6>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>ID</th>
									<th>SLoc</th>
									<th>Space Now</th>
									<th>Space Max</th>
									<th>Availability</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($sloc_availability as $sloc): ?>
									<tr>
										<td><?= $sloc['Id_storage']; ?></td>
										<td><?= $sloc['SLoc']; ?></td>
										<td><?= $sloc['space_now']; ?></td>
										<td><?= $sloc['space_max']; ?></td>
										<td><?= ($sloc['space_max'] - $sloc['space_now']) > 0 ? 'Available' : 'Full'; ?>
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