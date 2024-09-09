<style>
	.select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>
<section style="font-family: Nunito;">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="height: 3000px">
                <div class="card-body">
                    <!-- GET USER -->
                    <input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                  <button class="nav-link w-100 active" id="high-tab" data-bs-toggle="tab" data-bs-target="#high-rack" type="button" role="tab" aria-controls="home" aria-selected="true">High Rack</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                  <button class="nav-link w-100" id="medium-tab" data-bs-toggle="tab" data-bs-target="#medium-rack" type="button" role="tab" aria-controls="profile" aria-selected="false">Medium Rack</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2" id="myTabjustifiedContent">
                                <div class="mx-2 tab-pane fade show active" id="high-rack" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="row mt-4 mb-3 mx-2">
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary" id="add-row-btn-hg">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#refresh-page">
                                                <i class="bx bx-revision"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-3 mb-3 mx-2">
                                        <div class="col-md">
                                                <div class="table-responsive">
                                                    <table id="bomTable" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">Material Part No</th>
                                                                <th class="text-center">Material Part Description</th>
                                                                <th class="text-center">Material Type</th>
                                                                <th class="text-center">Qty</th>
                                                                <th class="text-center">Uom</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="table-body-hg"></tbody>
                                                    </table>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4 mx-2">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <label for="weight-hg" class="col-sm-4 col-form-label"><b>Total Weight</b></label>
                                                <div class="col-sm-5">
                                                    <input type="number" min="1" class="form-control" id="weight-hg" name="weight-hg" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 mx-2">
                                        <div class="col-md text-end">
                                            <button type="button" id="submit-hg" class="btn btn-success">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-3 mx-2">
                                        <div class="mt-3 mb-2" id="qrbarcode-hg"></div>
                                        <br>
                                        <div class="mt-1">
                                            <button class="btn btn-warning" id="print-button-hg" style="display: none">
                                                <i class="bi bi-printer" style="color: white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mx-2 tab-pane fade" id="medium-rack" role="tabpanel" aria-labelledby="profile-tab">
                                    <div class="row mt-4 mb-3 mx-2">
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-primary" id="add-row-btn-mg">
                                                <i class="bi bi-plus-circle"></i>
                                            </button>
                                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#refresh-page">
                                                <i class="bx bx-revision"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-3 mb-3 mx-2">
                                        <div class="col-md">
                                                <div class="table-responsive">
                                                    <table id="bomTable" class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">Material Part No</th>
                                                                <th class="text-center">Material Part Description</th>
                                                                <th class="text-center">Material Type</th>
                                                                <th class="text-center">Qty</th>
                                                                <th class="text-center">Uom</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="table-body-mg"></tbody>
                                                    </table>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 mx-2">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <label for="weight-mg" class="col-sm-4 col-form-label"><b>Total Weight</b></label>
                                                <div class="col-sm-5">
                                                    <input type="number" min="1" class="form-control" id="weight-mg" name="weight-mg" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 mx-2">
                                        <div class="col-md text-end">
                                            <button type="button" id="submit-mg" class="btn btn-success">
                                                Submit
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-3 mx-2">
                                        <div class="mt-3 mb-2" id="qrbarcode-mg"></div>
                                        <br>
                                        <div class="mt-1">
                                            <button class="btn btn-warning" id="print-button-mg" style="display: none">
                                                <i class="bi bi-printer" style="color: white"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODAL REFRESH -->
<div class="modal fade" id="refresh-page" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Confirm Refresh</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           Are you sure to reload the page ?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <a href="">
                <button type="button" class="btn btn-primary">Reload</button>
            </a>
        </div>
        </div>
    </div>
</div>
<script src="<?=base_url('assets');?>/vendor/qr-code/qr-code.min.js"></script>
<script>
    $(document).ready(function (){
        // HIGH RACK
        $(document).on('change', 'input[name^="materials-hg"][name$="[material_id]"]', function () {
            var materialID = $(this).val();
            var $row = $(this).closest('tr');
            var rowIndexHG = $row.index();

            $.ajax({
                url: '<?= base_url('production/getMaterialDesc'); ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    materialID
                },
                success: function (res) {
                    console.log(res);
                    var materialDesc = res[0].Material_desc;
                    var materialType = res[0].Material_type;
                    var uom = res[0].Uom;

                    $row.find('input[name^="materials-hg"][name$="[material_desc]"]').val(materialDesc);
                    $row.find('input[name^="materials-hg"][name$="[material_type]"]').val(materialType);
                    $row.find('input[name^="materials-hg"][name$="[uom]"]').val(uom);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error(xhr.statusText);
                }
            });
        });

        let rowIndexHG = 1;

        $('#add-row-btn-hg').click(function() {
            addRowHG();
        });
        
        $(document).on('click', '.btn-remove-row-hg', function() {
            $(this).closest('tr').remove();
            updateRowIndicesHG();
        });
        
        function addRowHG() {
            var material_list = <?= json_encode($material_list);?>;
            let materialOptions = '<option value="" selected>Select Material Part No</option>';
            material_list.forEach(ml => {
                materialOptions += `<option value="${ml.Id_material}">${ml.Id_material}</option>`;
            });

            const newRow = `
                <tr>
                    <td class="py-3"><b>${rowIndexHG}</b></td>
                    <td>
                        <select class="form-select material-select" name="materials-hg[${(rowIndexHG-1)}][material_id]" required>
                            ${materialOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control material-desc" name="materials-hg[${rowIndexHG}][material_desc]" required aria-label="Material Description" style="width: 300px;" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control text-center material-type" name="materials-hg[${rowIndexHG}][material_type]" aria-label="Material Type" style="width: 120px;" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control material-qty" name="materials-hg[${rowIndexHG}][qty]" required aria-label="Quantity" style="width: 100px;">
                    </td>
                    <td>
                        <input type="text" class="form-control text-center material-uom" name="materials-hg[${rowIndexHG}][uom]" required aria-label="Unit of Measure" style="width: 100px;" readonly>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-remove-row-hg" type="button" aria-label="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#table-body-hg').append(newRow);
            updateRowIndicesHG();
            
            $('.material-select').select2({
                width: '100%'
            });
            
            
            $('.material-select').last().change(function() {
                const selectedMaterialId = $(this).val();
                const selectedMaterial = material_list.find(ml => ml.Id_material == selectedMaterialId);

                if (selectedMaterial) {
                    $(this).closest('tr').find('.material-desc').val(selectedMaterial.Material_desc);
                    $(this).closest('tr').find('.material-type').val(selectedMaterial.Material_type); 
                    $(this).closest('tr').find('.material-uom').val(selectedMaterial.Uom);
                }
            });

            rowIndexHG += 1;
        }

        function updateRowIndicesHG() {
            $('table #table-body-hg tr').each(function(index) {
                $(this).find('td:first-child b').text(index + 1);
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    $(this).attr('name', newName);
                });
            });
            rowIndexHG = $('table #table-body-hg tr').length;
        }

        $('#submit-hg').on('click', function() {
            var materialData = [];
            var weight = $('#weight-hg').val();
            var user = $('#user').val();

            // Iterate over each table row to gather material data
            $('table #table-body-hg tr').each(function(index, row) {
                var rowIndexHG = index;
                var material_id = $(row).find('select[name="materials-hg[' + rowIndexHG + '][material_id]"]').val();
                var material_desc = $(row).find('input[name="materials-hg[' + rowIndexHG + '][material_desc]"]').val();
                var material_type = $(row).find('input[name="materials-hg[' + rowIndexHG + '][material_type]"]').val();
                var qty = $(row).find('input[name="materials-hg[' + rowIndexHG + '][qty]"]').val();
                var uom = $(row).find('input[name="materials-hg[' + rowIndexHG + '][uom]"]').val();

                // Check if qty is empty or null
                if (!qty || qty.trim() === '' || qty == null) {
                    return Swal.fire({
                        title: 'Error!',
                        html: `<b>Quantity</b> cannot be empty for material <b>${material_desc}</b>`,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }

                materialData.push({
                    material_id: material_id,
                    material_desc: material_desc,
                    material_type: material_type,
                    material_qty: qty,
                    material_uom: uom
                });
                rowIndexHG++;
            });

            if(materialData.length < 1 || weight.length < 1){
                return Swal.fire({
                    title: 'Error!',
                    html: `<b>Weight</b> or <b>Material</b> or <b>Qty</b> is empty`,
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
            }
            $.ajax({
                url: '<?= base_url('production/AddHighRack'); ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    weight,
                    materialData,
                    user
                },
                success: function(res) {
                    if(res.status == '2'){
                        Swal.fire({
                            title: "Success",
                            html: `Box ${res.no_box} has been requested for return`,
                            icon: "success",
                        });

                        
                        $('#weight-hg').prop('disabled', 'true');
                        $('#submit-hg').prop('disabled', 'true');
                        $('#add-row-btn-hg').prop('disabled', 'true');
                        $('#print-button-hg').css('display', 'block');
                       
                        var qrcode = new QRCode(document.getElementById("qrbarcode-hg"), {
                            text: `${res.no_box}`,
                            width: 150,
                            height: 150,
                            correctLevel: QRCode.CorrectLevel.H
                        });

                        function printBarcodeHigh() {
                            var idBox = res.no_box;

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
                            printWindow.document.write('<div class="barcode" id="qrcode">' + document.getElementById('qrbarcode-hg').innerHTML + '</div>');
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

                        $('#print-button-hg').on('click', function() {
                            printBarcodeHigh();
                        });
                    }
                    else{
                        Swal.fire({
                            title: "Error",
                            text: "Failed requested return for Materials",
                            icon: "error",
                            // showDenyButton: true,
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '<?=base_url('production/material_return');?>';
                            }
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.statusText);
                }
            });
        });



        // MEDIUM RACK
        $(document).on('change', 'input[name^="materials-mg"][name$="[material_id]"]', function () {
            var materialID = $(this).val();
            var $row = $(this).closest('tr');
            var rowIndexMG = $row.index();

            $.ajax({
                url: '<?= base_url('production/getMaterialDesc'); ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    materialID
                },
                success: function (res) {
                    // console.log(res);
                    var materialDesc = res[0].Material_desc;
                    var materialType = res[0].Material_type;
                    var uom = res[0].Uom;

                    $row.find('input[name^="materials-mg"][name$="[material_desc]"]').val(materialDesc);
                    $row.find('input[name^="materials-mg"][name$="[material_type]"]').val(materialType);
                    $row.find('input[name^="materials-mg"][name$="[uom]"]').val(uom);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error(xhr.statusText);
                }
            });
        });

        let rowIndexMG = 1;

        $('#add-row-btn-mg').click(function() {
            addRowMG();
        });
        
        $(document).on('click', '.btn-remove-row-mg', function() {
            $(this).closest('tr').remove();
            updateRowIndicesMG();
        });
        
        function addRowMG() {
            var material_list = <?= json_encode($material_list);?>;
            let materialOptions = '<option value="" selected>Select Material Part No</option>';
            material_list.forEach(ml => {
                materialOptions += `<option value="${ml.Id_material}">${ml.Id_material}</option>`;
            });

            const newRow = `
                <tr>
                    <td class="py-3"><b>${rowIndexMG}</b></td>
                    <td>
                        <select class="form-select material-select-mg" name="materials-mg[${(rowIndexMG-1)}][material_id]" required>
                            ${materialOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control material-desc-mg" name="materials-mg[${rowIndexMG}][material_desc]" required aria-label="Material Description" style="width: 300px;" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control text-center material-type-mg" name="materials-mg[${rowIndexMG}][material_type]" aria-label="Material Type" style="width: 120px;" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control material-qty-mg" name="materials-mg[${rowIndexMG}][qty]" required aria-label="Quantity" style="width: 100px;">
                    </td>
                    <td>
                        <input type="text" class="form-control text-center material-uom-mg" name="materials-mg[${rowIndexMG}][uom]" required aria-label="Unit of Measure" style="width: 100px;" readonly>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-remove-row-mg" type="button" aria-label="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#table-body-mg').append(newRow);
            updateRowIndicesMG();            
            
            $('.material-select-mg').select2({
                width: '100%'
            });
            
            
            $('.material-select-mg').last().change(function() {
                const selectedMaterialId = $(this).val();
                const selectedMaterial = material_list.find(ml => ml.Id_material == selectedMaterialId);

                if (selectedMaterial) {
                    $(this).closest('tr').find('.material-desc-mg').val(selectedMaterial.Material_desc);
                    $(this).closest('tr').find('.material-type-mg').val(selectedMaterial.Material_type); 
                    $(this).closest('tr').find('.material-uom-mg').val(selectedMaterial.Uom);
                }
            });
            
            rowIndexMG+=1;
        }

        function updateRowIndicesMG() {
            $('table #table-body-mg tr').each(function(index) {
                $(this).find('td:first-child b').text(index + 1);
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    $(this).attr('name', newName);
                });
            });
            rowIndexMG = $('table #table-body-mg tr').length;
        }

        $('#submit-mg').on('click', function() {
            var materialData = [];
            var weight = $('#weight-mg').val();
            var user = $('#user').val();

            // Iterate over each table row to gather material data
            $('table #table-body-mg tr').each(function(index, row) {
                var rowIndexMG = index;
                var material_id = $(row).find('select[name="materials-mg[' + rowIndexMG + '][material_id]"]').val();
                var material_desc = $(row).find('input[name="materials-mg[' + rowIndexMG + '][material_desc]"]').val();
                var material_type = $(row).find('input[name="materials-mg[' + rowIndexMG + '][material_type]"]').val();
                var qty = $(row).find('input[name="materials-mg[' + rowIndexMG + '][qty]"]').val();
                var uom = $(row).find('input[name="materials-mg[' + rowIndexMG + '][uom]"]').val();

                // Check if qty is empty or null
                if (!qty || qty.trim() === '' || qty == null) {
                    return Swal.fire({
                        title: 'Error!',
                        html: `<b>Quantity</b> cannot be empty for material <b>${material_desc}</b>`,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }

                materialData.push({
                    material_id: material_id,
                    material_desc: material_desc,
                    material_type: material_type,
                    material_qty: qty,
                    material_uom: uom
                });
                rowIndexMG++;
            });

            if(materialData.length < 1 || weight.length < 1){
                return Swal.fire({
                    title: 'Error!',
                    html: `<b>Weight</b> or <b>Material</b> or <b>Qty</b> is empty`,
                    icon: 'error',
                    confirmButtonText: 'Close'
                });
            }

            $.ajax({
                url: '<?= base_url('production/AddMediumRack'); ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    weight,
                    materialData,
                    user
                },
                success: function(res) {
                    if(res.status == '2'){
                        Swal.fire({
                            title: "Success",
                            html: `Box ${res.no_box} has been requested for return`,
                            icon: "success",
                        });

                        $('#weight-mg').prop('disabled', 'true');
                        $('#submit-mg').prop('disabled', 'true');
                        $('#add-row-btn-mg').prop('disabled', 'true');
                        $('#print-button-mg').css('display', 'block');
                       
                        var qrcode = new QRCode(document.getElementById("qrbarcode-mg"), {
                            text: `${res.no_box}`,
                            width: 150,
                            height: 150,
                            correctLevel: QRCode.CorrectLevel.H
                        });

                        function printBarcodeMedium() {
                            var idBox = res.no_box;

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
                            printWindow.document.write('<div class="barcode" id="qrcode">' + document.getElementById('qrbarcode-hg').innerHTML + '</div>');
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

                        $('#print-button-mg').on('click', function() {
                            printBarcodeMedium();
                        });
                    }
                    else{
                        Swal.fire({
                            title: "Error",
                            text: "Failed requested return for Materials",
                            icon: "error",
                            // showDenyButton: true,
                            confirmButtonText: "OK",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '<?=base_url('production/material_return');?>';
                            }
                        });
                    }

                    $('#high-tab').removeClass('active');
                    $('#high-rack').removeClass('show');
                    $('#high-rack').removeClass('active');
                    $('#medium-tab').addClass('active');
                    $('#medium-rack').addClass('show');
                    $('#medium-rack').addClass('active');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.error(xhr.statusText);
                }
            });
        });
    });
</script>