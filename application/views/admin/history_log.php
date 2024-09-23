<style>
    .select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>

<section>
    <div class="row justify-content-center">
        <div class="card">
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-md-4 text-end">
                        <label for="type" class="form-label py-2"><b>Select Action Log</b></label>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="type" name="type" required>
                            <option value="kitting_log">Kitting</option>
                            <option value="material_return_log">Material Return</option>
                            <option value="production_material_request_log">Production Material Request</option>
                            <option value="quality_material_request_log">Quality Material Request</option>
                            <option value="kanban_box_log">Kanban Box</option>
                            <option value="material_list_log">Material List</option>
                            <option value="bom_log">BOM</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-start">
                        <button class="btn btn-primary" type="button" onclick="get_data_log()">Load Data</button>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="table-responsive">
                        <div class="" id="datas"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<center>
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
</center>

<script>
    $(document).ready(function() {
        $('#type').select2();
        $('#spinner-container').hide();
    });

    function get_data_log(){
        $('#spinner-container').show();
        var data = $('#type').val();

        $.ajax({
            url: "<?= base_url('admin/get_log_data'); ?>",
            method: "POST",
            dataType: "json",
            data:{
                data
            },
            success: function(data) {
                $('#spinner-container').hide();

                var datas = data;
                var row = ''; 

                for (var i = 0; i < data.length; i++) {
                    var bm = data[i];
                    row += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${bm['affected_table']}</td>
                            <td>${bm['queries']}</td>
                            <td>${bm['Crtdt']}</td>
                            <td>${bm['Crtby']}</td>
                        </tr>
                    `;
                }

                // Construct the table HTML
                var tableHTML = `
                    <table class="table-bordered" id="tbl-log">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Table</th>
                                <th>Query</th>
                                <th>Create date</th>
                                <th>Create by</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-table">
                            ${row}
                        </tbody>
                    </table>
                `;

                // Append the table HTML to the DOM
                $('#datas').html(tableHTML);

                // Initialize DataTables
                new DataTable('#tbl-log', {
                    "pageLength": 10,
                }); 
            }
        });
    }
</script>
