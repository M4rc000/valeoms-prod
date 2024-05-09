<section>
	<div class="card">
		<div class="card-body">
			<div class="row mt-5 mb-5">
				<label for="inputText" class="col-sm-2 col-form-label">
					<b style="font-size: 14px;">
						Total Weight (kg)
					</b>
				</label>
				<div class="col-sm-3">
					<input type="text" class="form-control">
				</div>
			</div>
			<div class="row mt-5">
				<div class="col-sm">
					<span>
						<b>
							Description
						</b>
					</span>
				</div>
				<div class="row mt-2">
					<div class="col-md-5">
						<button class="btn btn-primary rounded-pill" id="addColumn">
							<i class="bx bx-plus"></i>
						</button>
					</div>
				</div>
				<div class="row mt-4">
					<div class="col-md">
						<table class="table table-bordered" id="data-Table">
							<thead>
								<tr>
									<th scope="col" class="text-center">#</th>
									<th scope="col" class="text-center">Reference No</th>
									<th scope="col" class="text-center">Material</th>
									<th scope="col" class="text-center">Qty</th>
									<th scope="col" class="text-center">UOM</th>
									<th scope="col" class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">1</th>
									<td>Brandon Jacob</td>
									<td>Designer</td>
									<td>28</td>
									<td>2016-05-25</td>
									<td>
										<div class="row justify-content-center">
											<div class="col-md text-center">
												<button class="btn btn-danger deleteColumn">
													<i class="bx bx-trash"> </i>
												</button>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<script>
    $(document).on('click', '#addColumn', function () {
        var rowCount = $('#dataTable tbody tr').length;
        var newRow = '<tr>' +
            '<th scope="row">' + (rowCount + 1) + '</th>' +
            '<td>New Reference</td>' +
            '<td>New Material</td>' +
            '<td>New Qty</td>' +
            '<td>New UOM</td>' +
            '<td>' +
            '<div class="row justify-content-center">' +
            '<div class="col-md text-center">' +
            '<button class="btn btn-danger deleteColumn">' +
            '<i class="bx bx-trash"></i>' +
            '</button>' +
            '</div>' +
            '</div>' +
            '</td>' +
            '</tr>';
        $('#dataTable tbody').append(newRow);
    });

    $(document).on('click', '.deleteColumn', function () {
        $(this).closest('tr').remove();
        // Update row numbers
        $('#dataTable tbody tr').each(function (index) {
            $(this).find('th:first').text(index + 1);
        });
    });
</script>
