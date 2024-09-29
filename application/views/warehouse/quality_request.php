<!-- application/views/warehouse/quality_request.php -->
<section>
    <div class="row">
        <div class="col-lg-12">
            <div class="card ml-5">
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
                    <table class="table datatable table-bordered" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Material</th>
                                <th>Material Description</th>
                                <th>Material Need</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($quality_request) && !empty($quality_request)): ?>
                            <?php $number = 1; ?>
                            <?php foreach ($quality_request as $request): ?>
                            <tr>
                                <td><?= $number++; ?></td>
                                <td><?= $request['Id_material']; ?></td>
                                <td><?= $request['Material_desc']; ?></td>
                                <td><?= $request['Material_need']; ?></td>
                                <td>
                                    <?php if ($request['status'] == 0): ?>
                                    <button class="btn btn-success"
                                        onclick="approveRequest('<?= $request['Id_material']; ?>')">
                                        <i class="bx bxs-edit" style="color: white;"> Approve</i>
                                    </button>
                                    <?php elseif ($request['status'] == 1): ?>
                                    <button type="button" class="btn btn-secondary" disabled>Approved</button>
                                    <?php else: ?>
                                    <button type="button" class="btn btn-danger" disabled>Rejected</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5">No data available</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function getDetailRequest(id, material_id) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_detail_quality_request'); ?>',
        type: 'POST',
        data: {
            Id_material: material_id,
        },
        success: function(res) {
            var data = JSON.parse(res);
            $('#detailModal' + id + ' tbody').empty();
            if (data.status && data.dt.length > 0) {
                $('#detailModal' + id).modal('show');
                var dt = data.dt;
                for (var i = 0; i < dt.length; i++) {
                    var row = '<tr>' +
                        '<td style="text-align: left;">' + (i + 1) + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Id_material + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Material_desc + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Material_need + '</td>' +
                        '<td style="text-align: left;">' + dt[i].Qty + '</td>' +
                        '<td style="text-align: left;">' +
                        '<select class="form-control sloc-select" name="sloc_name[]">' +
                        '<option value="">-- Pilih Sloc --</option>' +
                        '</select>' +
                        '</td>' +
                        '<td style="text-align: left;">' +
                        '<select class="form-control id-box-select" name="id_box[]">' +
                        '<option value="">-- Pilih ID Box --</option>' +
                        '</select>' +
                        '</td>' +
                        '</tr>';
                    $('#detailModal' + id + ' tbody').append(row);

                    // Initialize Select2 after adding row
                    $('.sloc-select').eq(i).select2({
                        placeholder: "-- Pilih Sloc --",
                        width: "100%"
                    });

                    $('.id-box-select').eq(i).select2({
                        placeholder: "-- Pilih ID Box --",
                        width: "100%"
                    });

                    // Initialize dataItems for this row
                    dataItems[i] = {
                        Id_material: dt[i].Id_material,
                        sloc_id: '',
                        id_box: ''
                    };

                    // Handle sloc-select change
                    $('.sloc-select').eq(i).on('change', function() {
                        var selectedIndex = $(this).closest('tr').index();
                        var selectedSlocId = $(this).val();

                        // Reset ID Box select
                        $('.id-box-select').eq(selectedIndex).html(
                            '<option value="">-- Pilih ID Box --</option>');
                        $('.id-box-select').eq(selectedIndex).select2({
                            placeholder: "-- Pilih ID Box --",
                            width: "100%"
                        });

                        // Update dataItems
                        dataItems[selectedIndex].sloc_id = selectedSlocId;

                        fetchIdBoxOptions(dt[selectedIndex].Id_material, selectedSlocId,
                            selectedIndex);
                    });

                    $('.id-box-select').eq(i).on('change', function() {
                        var selectedIndex = $(this).closest('tr').index();
                        var selectedIdBox = $(this).val();

                        dataItems[selectedIndex].id_box = selectedIdBox;
                    });

                    fetchSlocOptions(dt[i].Id_material, id, i);
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Data Incomplete!',
                    text: 'Please edit the request data to complete the information for this request.'
                }).then(function() {
                    window.location.reload();
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your request.'
            });
        }
    });
}

function approveRequest(id_material) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to approve this quality request?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Approve"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/approveQualityRequest'); ?>',
                type: 'POST',
                data: {
                    Id_material: id_material,
                    data_items: dataItems
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Quality Request Has Been Approved.'
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your request.'
                    });
                }
            });
        }
    });
}
</script>