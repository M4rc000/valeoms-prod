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
                        Approve
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
                tableData, weight, sloc, user, box_type, id_return
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
                $('#preview-barcode').empty();

                // DISABLED SLOC AND APPROVE BUTTON
                $('#submit-box').prop('disabled', true);
                $('#sloc').prop('disabled', true);

                document.addEventListener('keydown', function(e) {
                    // F5 key
                    if (e.key === 'F5') {
                        e.preventDefault();
                        alert('Page refresh is disabled.');
                    }
                    // Ctrl + R key combination
                    if ((e.ctrlKey && e.key === 'r') || (e.metaKey && e.key === 'r')) {
                        e.preventDefault();
                        alert('Page refresh is disabled.');
                    }
                });


                if(res.result == 3){
                    Swal.fire({
                        title: "Success",
                        text: 'Data Box has been approved',
                        icon: "success"
                    });

                    if(box_type == 'HIGH'){
                        $('#btn-print-hg').css('display', 'block');
                    }
                    else{
                        $('#btn-print-md').css('display', 'block');
                    }

                    var qrcode = new QRCode(document.getElementById("preview-barcode"), {
                        text: `${res.box_id}`,
                        width: 150,
                        height: 150,
                        correctLevel: QRCode.CorrectLevel.H
                    });

                    $(document).on('click', '#btn-print-mg', function(){
                    
                    });
                }
                else{
                    Swal.fire({
                        title: "Error",
                        text: `${res.error}`,
                        icon: "error"
                    });

                    window.location.href = '<?=base_url('warehouse/return_request');?>';
                }

                function printBarcodeHigh() {
                    var idBox = res.box_id;

                    // Printing logic here...
                    var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?>';
                    var printWindow = window.open('', '', 'height=750,width=500');
                    printWindow.document.write('<html><head><title>Print Barcode</title>');
                    printWindow.document.write('<style>');
                    printWindow.document.write('@page { size: 17cm 13cm; margin: 5px; }');
                    printWindow.document.write('.print-section { display: flex; flex-direction: column; width: 14cm; height: 8cm; border: 1px solid black; box-sizing: border-box; }');
                    printWindow.document.write('.row { display: flex; flex: 1; align-items: center; justify-content: space-between; border-bottom: 1px solid black; }');
                    printWindow.document.write('.row:first-child { height: 3cm; padding: 0 2px; }');
                    printWindow.document.write('.row:last-child { height: 5cm; align-items: center; justify-content: center; text-align: center; }');
                    printWindow.document.write('.barcode, .valeo-logo { display: inline-block; text-align: center;}');
                    printWindow.document.write('.barcode { width: 2cm; height: 2cm; margin-left: 2cm;}');
                    printWindow.document.write('.valeo-logo { width: 5cm; height: 2cm; margin-right: 1cm; background-position: center; }');
                    printWindow.document.write('.barcode-info { font-size: 2em; margin-top: 8px; text-align: center; width: 100%; margin-left: 15px; }');
                    printWindow.document.write('#qrcode img { width: 90%; height: 90%; }');
                    printWindow.document.write('</style>');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write('<div class="print-section">');
                    printWindow.document.write('<div class="row">');
                    printWindow.document.write('<div class="barcode" id="qrcode">' + document.getElementById('preview-barcode').innerHTML + '</div>');
                    printWindow.document.write('<div class="valeo-logo"><img src="' + logoUrl + '" alt="Valeo Logo" style="width: 100%; height: 100%;"></div>');
                    printWindow.document.write('</div>');
                    printWindow.document.write('<div class="row">');
                    printWindow.document.write('<div class="barcode-info" style="margin-top:40px;"><span>ID Box:</span><h1 style="font-size:2.5em; margin-top:-5;">' + idBox + '</h1></div>');
                    printWindow.document.write('</div>');
                    printWindow.document.write('</div>');
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                }

                $('#btn-print-hg').on('click', function() {
                    printBarcodeHigh();
                });

                function printBarcodeMedium() {
                    var idBox = res.box_id;

                    // Printing logic here...
                    var logoUrl = '<?php echo base_url("assets/img/valeo.png"); ?>';
                    var printWindow = window.open('', '', 'height=750,width=500');
                    printWindow.document.write('<html><head><title>Print Barcode</title>');
                    printWindow.document.write('<style>');
                    printWindow.document.write('@page { size: 17cm 13cm; margin: 5px; }');
                    printWindow.document.write('.print-section { display: flex; flex-direction: column; width: 14cm; height: 8cm; border: 1px solid black; box-sizing: border-box; }');
                    printWindow.document.write('.row { display: flex; flex: 1; align-items: center; justify-content: space-between; border-bottom: 1px solid black; }');
                    printWindow.document.write('.row:first-child { height: 3cm; padding: 0 2px; }');
                    printWindow.document.write('.row:last-child { height: 5cm; align-items: center; justify-content: center; text-align: center; }');
                    printWindow.document.write('.barcode, .valeo-logo { display: inline-block; text-align: center;}');
                    printWindow.document.write('.barcode { width: 2cm; height: 2cm; margin-left: 2cm;}');
                    printWindow.document.write('.valeo-logo { width: 5cm; height: 2cm; margin-right: 1cm; background-position: center; }');
                    printWindow.document.write('.barcode-info { font-size: 2em; margin-top: 8px; text-align: center; width: 100%; margin-left: 15px; }');
                    printWindow.document.write('#qrcode img { width: 90%; height: 90%; }');
                    printWindow.document.write('</style>');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write('<div class="print-section">');
                    printWindow.document.write('<div class="row">');
                    printWindow.document.write('<div class="barcode" id="qrcode">' + document.getElementById('preview-barcode').innerHTML + '</div>');
                    printWindow.document.write('<div class="valeo-logo"><img src="' + logoUrl + '" alt="Valeo Logo" style="width: 100%; height: 100%;"></div>');
                    printWindow.document.write('</div>');
                    printWindow.document.write('<div class="row">');
                    printWindow.document.write('<div class="barcode-info" style="margin-top:40px;"><span>ID Box:</span><h1 style="font-size:2.5em; margin-top:-5;">' + idBox + '</h1></div>');
                    printWindow.document.write('</div>');
                    printWindow.document.write('</div>');
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                }

                $('#btn-print-md').on('click', function() {
                    printBarcodeMedium();
                });

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
            }
        });

    });
</script>