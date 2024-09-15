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
    <div class="card" style="height: auto">
        <div class="card-body">
            <!-- GET USER -->
            <input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
            <div class="row mt-5 px-2">
                <label for="no_box" class="col-sm-2 col-form-label">
                    <b>Box No</b>
                </label>
                <div class="col-sm-10 col-md-2">
                    <input type="text" name="no_box" id="no_box" class="form-control text-center" value="<?=$box['no_box'];?>" readonly>
                </div>
            </div>
            <div class="row mt-1 mb-5 px-2">
                <label for="box_type" class="col-sm-2 col-form-label">
                    <b>Box Type</b>
                </label>
                <div class="col-sm-10 col-md-2">
                    <input type="text" name="box_type" id="box_type" class="form-control text-center" value="<?=$box['box_type'];?>" readonly>
                </div>
            </div>
            <div class="table-responsive">
                <div class="row px-2">
                    <div class="col-12">
                        <table class="table" id="tbl-data-return">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Material Part No</th>
                                    <th class="text-center">Material Part Name</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center">Uom</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $number = 0; foreach($return_data_warehouse as $rdw): $number++?>
                                <tr>
                                    <td class="text-center"><?=$number;?></td>
                                    <td class="text-center"><?=$rdw['Id_material'];?></td>
                                    <td class="text-center"><?=$rdw['Material_desc'];?></td>
                                    <td class="text-center"><?=$rdw['Material_qty'];?></td>
                                    <td class="text-center"><?=$rdw['Material_uom'];?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-5 px-2">
                <div class="col-12 col-md-5">
                    <div class="row">
                        <label for="inputText" class="col-4 col-form-label text-end">
                            <b>Total Weight</b>
                        </label>
                        <div class="col-8">
                            <input type="number" class="form-control" id="total-weight" name="total-weight" value="<?=$box['box_weight'];?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5 mt-3 mt-md-0">
                    <div class="row">
                        <label class="col-4 col-form-label text-end">
                            <b>SLoc</b>
                        </label>
                        <div class="col-8">
                            <select class="form-select" aria-label="Default select example" id="sloc" required>
                                <option value="">Select SLoc</option>
                                <?php foreach($sloc as $sl): ?>
                                    <option value="<?=$sl['Id_storage'];?>"><?=$sl['SLoc'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary" id="submit-box">
                        Save
                    </button>
                </div>
            </div>
            <div class="row mt-4 mb-2 px-3">
                <hr>
                <div id="preview-barcode"></div>
            </div>
            <div class="row mt-5 px-3">
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-warning" id="btn-print-hg" style="color: white; display: none" onclick="printBarcodeHigh()">
                        <i class="bx bx-printer"> Print</i>
                    </button>
                    <button type="button" class="btn btn-warning" id="btn-print-md" style="color: white; display: none">
                        <i class="bx bx-printer"> Print</i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>


<script src="<?=base_url('assets');?>/vendor/qr-code/qr-code.min.js"></script>
<script>
    $(document).ready(function (){
        $('#tbl-data-return').DataTable();
        $('#sloc').select2({
            'z-index': '999',
            'width': '100%'
        });
    });

    $('#submit-box').on('click', function() {
        var no_box = $('#no_box').val();
        var box_type = $('#box_type').val();
        var id_return = '<?=$id_return;?>';
        var weight = $('#total-weight').val();
        var sloc = $('#sloc').val();
        var user = $('#user').val();
        var tableData = [];

        if(sloc == ''){
            Swal.fire({
                title: "Error",
                html: `Total <b>Weight</b> or <b>SLoc</b> is empty`,
                icon: "error"
            });
            return false;
        }

        $('#tbl-data-return tbody tr').each(function(index) {
            var rowData = {};

            $(this).find('td').each(function(colIndex) {
                switch (colIndex) {
                    case 0:
                    rowData['number'] = $(this).text().trim();
                    break;
                case 1:
                    rowData['Id_material'] = $(this).text().trim();
                    break;
                case 2:
                    rowData['Material_desc'] = $(this).text().trim();
                    break;
                case 3:
                    rowData['Material_qty'] = $(this).text().trim();
                    break;
                case 4:
                    rowData['Material_uom'] = $(this).text().trim();
                    break;
                default:
                    break;
                }
            });

            tableData.push(rowData);
        });

        $.ajax({
            url: '<?= base_url('warehouse/addBox');?>',
            type: 'post',
            dataType: 'json',
            data: {
                tableData, weight, sloc, user, box_type, id_return, no_box
            },
            beforeSend: function(){
                var spinner =
                `
                <div class="spinner-container">
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
                `;
                $('#preview-barcode').append(spinner);
            },
            success: function(res) {
                if(res.result == 3){
                    Swal.fire({
                        title: "Success",
                        html: `Data Box has been approved, please put Box <b>${res.no_box}</b> in SLoc <b>${res.sloc}</b>`,
                        icon: "success"
                    }).then((result) => {
                    if (result.isConfirmed) {
                            window.location.href = '<?=base_url('warehouse/return_request');?>'
                        }
                    });
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
            }
        });

    });
</script>