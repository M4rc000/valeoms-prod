<style>
.select2-container {
    z-index: 9999;
}

.select2-selection {
    padding-top: 4px !important;
    height: 38px !important;
    /* width: 367px !important; */
}
</style>
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
                                <th style="width: 5%;">#</th>
                                <!-- <th>Request No</th> -->
                                <th>Production Plan</th>
                                <th>Production Description</th>
                                <th>Production Plan Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 0;
							foreach ($production_request as $request):
								$number++ ?>
                            <tr>
                                <td><?= $number; ?></td>
                                <td><?= $request['Production_plan']; ?></td>
                                <td>
                                    <?= $request['Fg_desc']; ?>
                                </td>
                                <td><?= $request['Production_plan_qty']; ?></td>
                                <td>
                                    <?php if ($request['status'] == 'NEW') { ?>
                                    <button class="btn btn-success"
                                        onclick="redirectTo('<?= base_url('warehouse/approve_production_plan'); ?>', '<?= $request['Production_plan']; ?>')">
                                        <i class="bi bi-list-check" style="color: white;"> </i> Approve
                                    </button>
                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal<?= $request['Production_plan']; ?>">
                                        <i class="bi bi-dash-circle" style="color: white;"> </i> Reject
                                    </button>
                                    <?php } else if ($request['status'] == 'APPROVED') { ?>
                                    <button type="button" class="btn btn-secondary" style="background-color:#3fb03f"
                                        disabled>Approved</button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="redirectTo('<?= base_url('warehouse/approve_production_plan'); ?>', '<?= $request['Production_plan']; ?>')"><i
                                            class="bx bx-show"></i> Detail</button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="printReq('<?= $request['Production_plan']; ?>')"><i
                                            class="bi bi-printer"></i> Print</button>
                                    <?php } else { ?>
                                    <button type="button" class="btn btn-danger" disabled>Rejected</button>
                                    <button class="btn btn-primary" class="choose-sloc-box" data-bs-toggle="modal"
                                        data-bs-target="#detailRejectSlocAndBox<?= $request['Production_plan']; ?>">
                                        <i class="bx bx-show"></i></span> Detail
                                    </button>
                                    <button type="button" class="btn btn-warning"
                                        onclick="printReqReject('<?= $request['Production_plan']; ?>')"><i
                                            class="bi bi-printer"></i> View</button>
                                    <?php } ?>

                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="row mt-5">
                        <div class="col-md-10"></div>
                        <div class="col-md">
                            <form action="<?= base_url('warehouse/clearData') ?>" method="post">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- REJECT MODAL-->
<?php foreach ($production_request as $request): ?>
<div class="modal fade" id="rejectModal<?= $request['Production_plan']; ?>" tabindex="-1" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Production Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="productId" class="col-sm-4 col-form-label"><b>Product ID</b></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= $request['Production_plan']; ?>"
                            name="productId" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="productDescription" class="col-sm-4 col-form-label"><b>Product Description</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="<?= $request['Fg_desc']; ?>"
                            name="productDescription" value="${productDescription}" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="qty" class="col-sm-4 col-form-label"><b>Qty Production Planning</b></label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" value="<?= $request['Production_plan_qty']; ?>"
                            id="qty" name="qty" min="1" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="reject" class="col-sm-4 col-form-label"><b>Reject Description</b></label>
                    <div class="col-lg-6 col-sm-6">
                        <textarea type="text" class="form-control keterangan-reject_<?= $request['Production_plan']; ?>"
                            name="keterangan-reject_" min="1" required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Reject"
                    onclick="rejectRequest('<?= $request['Production_plan'] ?>')"> Reject</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- REJECT MODAL DETAIL-->
<?php foreach ($production_request as $request): ?>
<div class="modal fade" id="detailRejectSlocAndBox<?= $request['Production_plan']; ?>" tabindex="-1"
    style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Reject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="productId" class="col-sm-4 col-form-label"><b>Product ID</b></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= $request['Production_plan']; ?>"
                            name="productId" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="productDescription" class="col-sm-4 col-form-label"><b>Product Description</b></label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" value="<?= $request['Fg_desc']; ?>"
                            name="productDescription" value="${productDescription}" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="qty" class="col-sm-4 col-form-label"><b>Qty Production Planning</b></label>
                    <div class="col-sm-2">
                        <input type="number" class="form-control" value="<?= $request['Production_plan_qty']; ?>"
                            id="qty" name="qty" min="1" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <label for="reject" class="col-sm-4 col-form-label"><b>Reject Description</b></label>
                    <div class="col-lg-8 col-sm-8">
                        <textarea type="text" style="border-radius:10px; border: 1px solid #dc3545" min="1"
                            readonly><?= $request['reject_description']; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"
                    onclick="closeSpecificModal()"> Close</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
function closeSpecificModal(modalId) {
    $('#' + modalId).modal('hide'); // Close the specific modal
}

$(document).ready(function() {
    // Prevent closing on click outside or ESC
    $('.modal').modal({
        backdrop: 'static',
        keyboard: false
    });

});


$(document).ready(function() {});

var dataItems = [];

function getDetailRequest(id, product_plan) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_detail_request'); ?>',
        type: 'POST',
        data: {
            Production_plan: product_plan,
        },
        success: function(res) {
            var data = JSON.parse(res);
            $('#detailModal1' + id + ' tbody').empty();
            if (data.status && data.dt.length > 0) {
                $('#detailModal1' + id).modal('show');
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
                    $('#detailModal1' + id + ' tbody').append(row);

                    // inisialisasi Select2 setelah menambahkan baris
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

                    // handle sloc-select change
                    $('.sloc-select').eq(i).on('change', function() {
                        var selectedIndex = $(this).closest('tr').index();
                        var selectedSlocId = $(this).val();

                        // reset ID Box select
                        $('.id-box-select').eq(selectedIndex).html(
                            '<option value="">-- Pilih ID Box --</option>');
                        $('.id-box-select').eq(selectedIndex).select2({
                            placeholder: "-- Pilih ID Box --",
                            width: "100%"
                        });

                        // update dataItems
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

// Fungsi untuk mengambil dan mengisi pilihan sloc
function fetchSlocOptions(idMaterial, modalId, index) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_sloc_options'); ?>',
        type: 'POST',
        data: {
            id_material: idMaterial
        },
        success: function(response) {
            var options = '<option value="">-- Pilih Sloc --</option>';
            var data = JSON.parse(response);
            if (data.length > 0) {
                for (var j = 0; j < data.length; j++) {
                    options += '<option value="' + data[j].sloc_id + '">' + data[j].sloc_name + '</option>';
                }
            }
            $('.sloc-select').eq(index).html(options);
            // Setelah mengisi opsi, update Select2
            $('.sloc-select').eq(index).select2({
                placeholder: "-- Pilih Sloc --",
                width: "100%",
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching sloc options.'
            });
        }
    });
}

// Fungsi untuk mengambil dan mengisi pilihan id_box

function fetchIdBoxOptions(idMaterial, slocId, index) {
    $.ajax({
        url: '<?php echo base_url('warehouse/get_id_box_options'); ?>',
        type: 'POST',
        data: {
            id_material: idMaterial,
            sloc_id: slocId
        },
        success: function(response) {
            var options = '<option value="">-- Pilih ID Box --</option>';
            var data = JSON.parse(response);
            if (data.length > 0) {
                for (var j = 0; j < data.length; j++) {
                    options += '<option value="' + data[j].id_box + '">' + data[j].no_box + '</option>';
                }
            }
            $('.id-box-select').eq(index).html(options);
            $('.id-box-select').eq(index).select2({
                placeholder: "-- Pilih ID Box --",
                width: "100%",
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while fetching id box options.'
            });
        }
    });
}

function approveRequest(production_plan) {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to approve this production request?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Approve"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/approveProductionRequest'); ?>',
                type: 'POST',
                data: {
                    production_plan: production_plan,
                    data_items: dataItems
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        window.open(
                            '<?php echo base_url().$this->router->fetch_class(); ?>/print_request/' +
                            production_plan, '_blank');
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

function rejectRequest(production_plan) {
    var rejectDescription = $(`.keterangan-reject_${production_plan}`).val();
    console.log(rejectDescription);
    if (!rejectDescription) {
        Swal.fire({
            icon: 'error',
            text: 'Please fill out reject description form!',
        });
        return;
    }
    Swal.fire({
        title: "Are you sure?",
        text: "You want to reject this production request?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "yes"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo base_url('warehouse/rejectProductionRequest'); ?>',
                type: 'POST',
                data: {
                    production_plan: production_plan,
                    reject_description: rejectDescription,
                },
                success: function(res) {
                    var data = JSON.parse(res);
                    if (data.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Production Request Has Been Rejected.'
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

function printReq(production_plan) {
    window.open('<?php echo base_url() . $this->router->fetch_class(); ?>/print_request/' + production_plan, '_blank');
}

function printReqReject(production_plan) {
    window.open('<?php echo base_url() . $this->router->fetch_class(); ?>/print_request_reject/' + production_plan,
        '_blank');
}

function redirectTo(baseUrl, idBox) {
    var url = baseUrl + '/' + idBox;
    window.open(url, '_blank');
}
</script>