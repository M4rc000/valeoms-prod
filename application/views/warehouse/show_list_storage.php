<section>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md mt-4">
                    <div class="table-responsive">
                        <table class="table table-bordered display" id="tbl-storage">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product ID</th>
                                    <th>Material Description</th>
                                    <th>Total Quantity</th>
                                    <th>Box Details</th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                            </tbody>
                        </table>
                    </div>   
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SPINNER LOADING -->
<div class="spinner-container" id="spinner-container">
    <div class="spinner-grow text-success" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="spinner-grow text-success" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="spinner-grow text-success" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="<?=base_url('assets');?>/vendor/datatables/exports/js/dataTables.js"></script>
<script src="<?=base_url('assets');?>/vendor/datatables/exports/js/dataTables.buttons.js"></script>
<script src="<?=base_url('assets');?>/vendor/datatables/exports/js/buttons.dataTables.js"></script>
<script src="<?=base_url('assets');?>/vendor/datatables/exports/js/jszip.min.js"></script>
<script src="<?=base_url('assets');?>/vendor/datatables/exports/js/buttons.html5.min.js"></script>
<script src="<?=base_url('assets');?>/vendor/datatables/exports/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#spinner-container').show();

        $.ajax({
            url: "<?= base_url('warehouse/get_data_show_list_storage'); ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                // Hide spinner when the data is successfully retrieved
                $('#spinner-container').hide();

                // Append rows to the table body
                let tableBody = $('#table-body');
                let number = 1;

                // console.log(data);

                for (let i = 0; i < data.length; i++) {
                    let boxQtyDetails = data[i].box_qty_details || ''; // Handle null or undefined
                    let boxDetailsHtml = '';

                    if (boxQtyDetails) {
                        boxDetailsHtml = boxQtyDetails.split(',').map(boxQty => {
                            let [id_box, qty] = boxQty.split(':');
                            return `<div>ID Box: ${id_box}, Qty: ${qty}</div>`;
                        }).join('');
                    }

                    let row = `
                        <tr>
                            <td>${number++}</td>
                            <td>${data[i].product_id}</td>
                            <td>${data[i].material_desc}</td>
                            <td>${data[i].total_qty_sum}</td>
                            <td>${boxDetailsHtml}</td>
                        </tr>
                    `;
                    tableBody.append(row);
                }

                // Initialize DataTables
                new DataTable('#tbl-storage', {
                    "pageLength": 10,
                    layout: {
                        topStart: {
                            buttons: [
                                {
                                    extend: 'excel',
                                    text: '<i class="bx bx-table"></i> Excel',
                                    title: 'List Storage WMS',
                                    className: 'btn-custom-excel'
                                }
                            ]
                        }
                    }
                });
            },
            error: function() {
                $('#spinner-container').hide();
                alert('Failed to retrieve data.');
            }
        });
    });
</script>