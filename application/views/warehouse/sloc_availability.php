<section>
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">SLoc Availability</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead class="thead-light">
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
								<td><?= $sloc['Sloc']; ?></td>
								<td><?= $sloc['space_now']; ?></td>
								<td><?= $sloc['space_max']; ?></td>
								<td
									class="<?= ($sloc['space_max'] - $sloc['space_now']) > 0 ? 'text-success' : 'text-danger'; ?>">
									<?= ($sloc['space_max'] - $sloc['space_now']) > 0 ? 'Available' : 'Full'; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</section>