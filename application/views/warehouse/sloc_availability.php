<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">SLoc Availability</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table datatable table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>SLoc</th>
                                    <th>Space Now</th>
                                    <th>Space Max</th>
                                    <th>Availability</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sloc_availability as $sloc): ?>
                                <tr>
                                    <td><?= $sloc['SLoc']; ?></td>
                                    <td><?= $sloc['space_now']; ?></td>
                                    <td><?= $sloc['space_max']; ?></td>
                                    <td><?= ($sloc['space_max'] - $sloc['space_now']) > 0 ? 'Available' : 'Full'; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#detailModal"
                                            onclick="viewDetails(<?= $sloc['Id_storage']; ?>)">
                                            View Details
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

<!-- Modal for showing box details -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Box Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Box Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="boxDetailsTable">
                        <!-- Box details will be injected here via JavaScript -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewDetails(slocId) {
    $.ajax({
        url: '<?= base_url('warehouse/getBoxDetails'); ?>', // URL to the controller that fetches box details
        type: 'POST',
        data: {
            sloc_id: slocId
        }, // Send the SLoc ID to the server
        success: function(response) {
            var data = JSON.parse(response);
            var tableContent = '';

            if (data.status === 'success') {
                $.each(data.boxes, function(index, box) {
                    tableContent += '<tr>';
                    tableContent += '<td>' + (index + 1) + '</td>';
                    tableContent += '<td>' + box.no_box + '</td>';
                    tableContent += '<td>';
                    // Redirect to the detailed view when the "View Details" button is clicked
                    tableContent +=
                        '<button class="btn btn-info" onclick="window.location.href=\'<?= base_url('warehouse/cycle_box_view/'); ?>' +
                        box.id_box + '\'">View Details</button> ';
                    tableContent += '<button class="btn btn-danger" onclick="deleteBox(' + box
                        .id_box + ')">Delete</button>';
                    tableContent += '</td>';
                    tableContent += '</tr>';
                });
            } else {
                tableContent = '<tr><td colspan="3">No boxes found for this SLoc.</td></tr>';
            }

            $('#boxDetailsTable').html(tableContent); // Update modal with the box details
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert('An error occurred while fetching box details.');
        }
    });
}


function deleteBox(id_box) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url('warehouse/deleteBox'); ?>', // URL to delete the box
                type: 'POST',
                data: {
                    id_box: id_box
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        Swal.fire(
                            'Deleted!',
                            'The box has been deleted.',
                            'success'
                        );
                        // Refresh data or reload SLoc details
                        viewDetails($('#sloc_select').val());
                    } else {
                        Swal.fire(
                            'Error!',
                            'There was an issue deleting the box.',
                            'error'
                        );
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire(
                        'Error!',
                        'An error occurred while deleting the box.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>