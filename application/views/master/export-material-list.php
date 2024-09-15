<section>
    <div class="card">
        <div class="card-body">
            <div class="row mt-5 mx-2">
                    <div class="col-md">
                        <div class="table-responsive">
                            <table id="bomTable" class="table table-bordered display">
                                <thead>
                                    <tr>
                                        <th class="text-center">Material Part No</th>
                                        <th class="text-center">Material Part Description</th>
                                        <th class="text-center">Material Type</th>
                                        <th class="text-center">Uom</th>
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
            url: "<?= base_url('master/get_material_list_data'); ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                $('#spinner-container').hide();

                var datas = data

                // Append rows to the table body
                for (var i = 0; i < data.length; i++) {
                    var bm = data[i];
                    var row = `
                        <tr>
                            <td>${bm['Id_material']}</td>
                            <td>${bm['Material_desc']}</td>
                            <td>${bm['Material_type']}</td>
                            <td>${bm['Uom']}</td>
                        </tr>
                    `;
                    $('#table-body').append(row);
                }

                // Initialize DataTables
                new DataTable('#bomTable', {
                    "pageLength": 10,
                    layout: {
                        topStart: {
                            buttons: [
                                {
                                    extend: 'excel',
                                    text: '<i class="bx bx-table"></i> Excel',
                                    title: '',
                                    className: 'btn-custom-excel'
                                }
                            ]   
                        }
                    }
                }); 
            }
        });
    });
</script>