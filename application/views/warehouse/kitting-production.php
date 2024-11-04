<style>
	.kanban-card {
		border: 1px solid #B4B4B8;
		padding: 20px;
		width: 700px;
		margin: 20px auto;
		position: relative;
	}

	.kanban-card h3 {
		text-align: center;
		margin-bottom: 20px;
		text-decoration: underline;
	}

	.kanban-card .logo {
		position: absolute;
		top: 20px;
		left: 20px;
		width: 50px;
		height: 30px;
	}

	.kanban-card ul {
		list-style: none;
		padding: 0;
	}

	.kanban-card ul li {
		display: flex;
		align-items: center;
		padding: 5px 0;
	}

	.kanban-card ul li p {
		margin: 0;
	}

	.select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}

	.barcode-container {
		display: flex;
		justify-content: center;
		align-items: center;
		width: 100%;
		height: 150px; /* or adjust height as needed */
	}

	.barcode-container img {
		display: block;
		margin: 0 auto;
	}
</style>

<section>
    <div class="card">
        <div class="card-body" style="height: 1500px">
            <div class="row mt-5 px-2">
                <label for="req_nop" class="col-sm-3 col-form-label"><b>Request No</b></label>
                <div class="col-sm-3">
                    <input type="text" id="req_nop" class="form-control text-center" value="<?=$reqNo;?>"
                        readonly>
                </div>
            </div>
            <div class="row mt-2 px-2">
                <label for="Id_fg" class="col-sm-3 col-form-label"><b>FG Part No</b></label>
                <div class="col-sm-3">
                    <input type="text" id="Id_fg" class="form-control text-center" value="<?=$FG_result[0]['Id_fg'];?>"
                        readonly>
                </div>
            </div>
            <div class="row mt-2 px-2">
                <label for="Fg_desc" class="col-sm-3 col-form-label"><b>FG Part Name</b></label>
                <div class="col-sm-6">
                    <input type="text" id="Fg_desc" class="form-control" value="<?=$FG_result[0]['Fg_desc'];?>" readonly>
                </div>
            </div>
            <div class="row mt-2 px-2">
                <label for="qty_prod_plan" class="col-sm-3 col-form-label"><b>Qty Production Planning</b></label>
                <div class="col-sm-2">
                    <input type="text" id="qty_prod_plan" class="form-control text-center"
                        value="<?=$FG_result[0]['Production_plan_qty'];?>" readonly>
                </div>
            </div>
            <div class="row mt-2 px-2">
                <label for="qty_prod_plan" class="col-sm-3 col-form-label"><b>Material Part No</b></label>
                <div class="col-sm-3">
                    <input type="text" id="qty_prod_plan" class="form-control text-center"
                        value="<?=$Request_result[0]['Id_material'];?>" readonly>
                </div>
            </div>
            <div class="row mt-2 px-2">
                <label for="qty_prod_plan" class="col-sm-3 col-form-label"><b>Material Description</b></label>
                <div class="col-sm-5">
                    <input type="text" id="qty_prod_plan" class="form-control text-center"
                        value="<?=$Request_result[0]['Material_desc'];?>" readonly>
                </div>
            </div>
            <div class="row mt-2 mb-4 px-2">
                <label for="qty_prod_plan" class="col-sm-12 col-md-3 col-form-label"><b>Material Need</b></label>
                <div class="col-sm-12 col-md-9">
                    <div class="row">
                        <div class="col-3 col-md-3 col-lg-3">
                            <input type="text" id="qty_prod_plan" class="form-control text-center" value="<?=$FG_result[0]['Material_need'];?>" readonly>
                        </div>
                        <div class="col-3 col-md-3 col-lg-3">
                            <input type="text" id="qty_prod_plan_uom" class="form-control text-center" value="<?=$FG_result[0]['Uom'];?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row mt-3">
                <div class="col-md">
                    <span class="mb-4">
                        <strong>
                            List Box
                        </strong>
                    </span>
                    <div class="row mt-2" id="rowBox"></div>
                </div>
            </div>
            <div class="row justify-content-center mt-4 gap-1">
                <label for="box_id" class="col-sm-3 col-form-label text-end"><b>Box ID</b></label>
                <div class="col-sm-5">
                    <select id="box_id" class="form-select">
                        <option selected>Choose Box</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <button type="submit" class="btn btn-success" id="btn-search-box" onclick="getBoxP()">Search</button>
                </div>
            </div>
            <div class="row mt-3 mb-1">
                <div id="data-box-prod"></div>
            </div>
            <div class="row mt-4" id="qty-remaining">
                <div class="col-12 col-md-3 mb-3 mb-md-0">
                    <label for="qty_remaining" class="form-label"><b>Qty Save to kanban</b></label>
                    <input type="number" class="text-center form-control" id="qty_remaining" name="qty_remaining" readonly>
                </div>
            </div>
            <div class="row mt-2" id="kanban-card">
                <div class="col-12 col-md-3 mb-3 mb-md-0">
                    <!-- GET USER -->
                    <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
                    <!-- <input type="text" class="form-control" id="kanbanBox_id" name="kanbanBox_id" value="<?= $kanban; ?>" readonly hidden> -->
                    <label for="material_id" class="form-label"><b>Material Part No</b></label>
                    <input type="text" class="form-control" id="material_id" name="material_id" readonly>
                </div>
                <div class="col-12 col-md-4 mb-3 mb-md-0">
                    <label for="material_desc" class="form-label"><b>Material Part Name</b></label>
                    <input type="text" class="form-control" id="material_desc" name="material_desc" required readonly>
                </div>
                <div class="col-12 col-md-2 mb-3 mb-md-0">
                    <label for="qty_kanban" class="form-label"><b>Qty</b></label>
                    <input type="text" class="form-control" id="qty_kanban" name="qty_kanban" required placeholder="0.5">
                </div>
                <div class="col-12 col-md-3">
                    <label for="production_planning" class="form-label"><b>Production Planning</b></label>
                    <input type="text" class="form-control" id="production_planning" name="production_planning" readonly>
                </div>
            </div>
            <div class="row justify-content-center mt-3 gap-1">
                <div class="col text-end">
                    <button type="button" class="btn btn-info" id="print-btn" style="display: none; color: white"><i class="bx bx-printer"></i></button>
                </div>
            </div>
            <div class="row justify-content-center mt-3 gap-1">
                <div class="col text-end">
                    <button type="button" class="btn btn-warning" id="refresh-btn-prod" style="display: none"><i class="bx bx-revision" style="color: white"></i></button>
                </div>
            </div>
            <div class="row justify-content-center mt-3 gap-1">
                <div class="col text-end">
                    <button type="button" class="btn btn-primary" id="check-btn-prod" style="display: none" data-bs-toggle="modal" data-bs-target="#modalConfirm"><i class="bi bi-check2-circle" style="color: white"></i></button>
                </div>
            </div>
            <div class="row justify-content-center mt-3 gap-1">
                <div class="col text-end">
                    <a href="<?=base_url('warehouse/kitting');?>"><button type="button" class="btn btn-success" id="finnish-btn-prod" style="display: none">Finnish</button></a>
                </div>
            </div>
        </div>
    </div>    
</section>

<!-- MODAL CONFIRMATION -->
<div class="modal fade" id="modalConfirm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to next box ?</h4>
            </div>
            <form action="">
                <div class="modal-body">
                    <span>Have all unpacked quantities been stored in the Kanban box ?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
                </div>
            </form>
		</div>
	</div>
</div>

<script src="<?=base_url('assets');?>/vendor/qr-code/qr-code.min.js"></script>
<script>
    $(document).ready(function () {
        $('#box_id').select2();
        $('#qty-remaining').hide();
        $('#kanban-card').hide();
        $('#kanban-card').children().hide();
        $('#qty-remaining').children().hide();

        var rowBox = '';
        var Box_result = <?= json_encode($Box_result); ?>;

        for (var i = 0; i < Box_result.length; i++) {
            rowBox +=
            `
                <div class="col-md-2 mt-2" id="box-${Box_result[i].no_box}">
                    <input class="form-control" value="${Box_result[i].no_box}" readonly>
                </div>
            `;
        }

        // Append the rowBox content to the #rowBox div
        $('#rowBox').html(rowBox);

        var boxOptions = Box_result.map(function(box) {
            return `<option value="${box.no_box}">${box.no_box}</option>`;
        }).join('');

        $('#box_id').html(`<option selected>Choose Box</option>` + boxOptions);


        // AVOID GO OUT FROM PAGE
            // Handle link clicks
            $('a').on('click', function(event) {
                var url = $(this).attr('href');
                showConfirmation(event, url);
            });

            function handleBeforeUnload(event) {
                event.preventDefault();
                event.returnValue = ''; // For modern browsers
                return ''; // For older browsers
            }

            window.addEventListener('beforeunload', handleBeforeUnload);
    });

    function getBoxP() {
        var boxID = $('#box_id').val();
        var req_no = $('#req_nop').val();
        var user = $('#user').val();

        $.ajax({
            url: '<?= base_url('warehouse/getBoxP'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                boxID: boxID,
                req_no: req_no
            },
            success: function(res) {
                if (res && res.Box_result.length > 0) {
                    // console.log(res);

                    var boxID = res.Box_result[0].no_box;
                    var sloc = res.Box_result[0].Sloc;
                    var weight = res.Box_result[0].weight;

                    var htmlContent = `
                        <div class="row mt-5">
                            <label for="inputText" class="col-sm-2 col-form-label">Box ID</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" value="${boxID}" readonly disabled>
                            </div>
                        </div>
                        <div class="row mt-2 mb-5">
                            <label for="inputText" class="col-sm-2 col-form-label">Weight</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" value="${weight !== null ? parseInt(weight) : '0'}" readonly disabled>
                            </div>
                        </div>
                    `;

                    var rows = '';
                    var fill = '';

                    for (let numbers = 0; numbers < res.Box_result.length; numbers++) {
                        var boxItem = res.Box_result[numbers];
                        var isChecked = false;
                        var materialNeed = '';

                        // Check if Box_result[numbers].product_id and Box_result[numbers].id_box match any Quality_result item
                        for (let i = 0; i < res.Quality_result.length; i++) {
                            var qualityItem = res.Quality_result[i];
                            if (boxItem.product_id == qualityItem.Id_material && boxItem.id_box == qualityItem.id_box) {
                                isChecked = true;
                                materialNeed = qualityItem.Qty;
                                $('#qty_remaining').val(materialNeed);
                                fill = boxItem.id_box; 
                                break;
                            }
                        }

                        rows += `
                            <tr>
                                <td class="text-center"><input class="form-check-input" type="checkbox" id="gridCheck${numbers + 1}" ${isChecked ? 'checked' : ''}></td>
                                <td class="text-center">${boxItem.product_id}</td>
                                <td class="text-center">${boxItem.material_desc}</td>
                                <td class="text-center">${isChecked ? materialNeed : ''}</td> 
                                <td class="text-center">${boxItem.SLoc}</td>
                                <td class="text-center">${boxItem.total_qty}</td>
                                <td class="text-center">${boxItem.uom}</td>
                            </tr>
                        `;
                    }

                    htmlContent += `
                        <div class="table-responsive">
                            <table class="table datatable table-bordered mb-5" id="table-unpack">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col" class="text-center">Material Part No</th>
                                        <th scope="col" class="text-center">Material Name</th>
                                        <th scope="col" class="text-center">Qty request</th>
                                        <th scope="col" class="text-center">SLoc</th>
                                        <th scope="col" class="text-center">Qty</th>
                                        <th scope="col" class="text-center">UOM</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rows}
                                </tbody>
                            </table>
                        </div>
                    `;

                    htmlContent += `
                        <div class="row justify-content-center mt-5 gap-1">
                            <div class="col text-end">
                                <button type="button" class="btn btn-success" id="unpackBtn-prod">Unpack</button>
                            </div>
                        </div>
                    `;

                    $('#data-box-prod').empty().append(htmlContent);

                    // Bind click event to Unpack button
                    $('#unpackBtn-prod').on('click', function() {
                        var checkedItems = [];
                        var reqNo = '<?=$reqNo;?>';

                        $('input.form-check-input:checked').each(function() {
                            var row = $(this).closest('tr');
                            var item = {
                                product_id: row.find('.text-center:nth-child(2)').text(),
                                material_desc: row.find('.text-center:nth-child(3)').text(),
                                material_need: row.find('.text-center:nth-child(4)').text(),
                                total_qty: row.find('.text-center:nth-child(6)').text(),
                                SLoc: row.find('.text-center:nth-child(5)').text(),
                                uom: row.find('.text-center:nth-child(7)').text()
                            };
                            checkedItems.push(item);
                        });

                        
                        if(checkedItems){
                            $.ajax({
                                url: '<?= base_url('warehouse/save_kitting_production'); ?>',
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    checkedItems, fill, user, reqNo
                                },
                                success: function(res) {
                                    if(res){
                                        Swal.fire({
                                            title: "Success",
                                            html: `Box <b>${res['no_box']}</b> has been unpacked`,
                                            icon: "success"
                                        });

                                        
                                        $('#print-btn').css('display', 'block');
                                        $('#kanban-card').show();
                                        $('#kanban-card').children().show();
                                        $('#qty-remaining').show();
                                        $('#qty-remaining').children().show();
                                        $('#unpackBtn-prod').hide();
                                        $('#table-unpack input[type="checkbox"]').prop('disabled', true);
                                        $('#btn-search-box').prop('disabled', true);
                                        $('#box_id').prop('disabled', true);

                                        // COLUMN INPUT KANBAN CARD
                                        $('#material_id').val(res['Id_material']);
                                        $('#material_desc').val(res['Material_desc']);
                                        $('#production_planning').val(res['Production_plan']);


                                        $('#print-btn').on('click', function() {
                                            var materialID = res['Id_material'];
                                            var materialDesc = res['Material_desc'];
                                            var materialQty = $('#qty_kanban').val();
                                            var proPlan = res['Production_plan'];
                                            var id_fg = res['Id_fg'];
                                            var user = $('#user').val();

                                            if(!materialQty){
                                                Swal.fire({
                                                    title: "Warning",
                                                    html: `Qty is empty`,
                                                    icon: "warning"
                                                });
                                                return false;
                                            }

                                            // QTY SAVE TO KANBAN REMAINING
                                            var qty_remaining = parseFloat($('#qty_remaining').val());

                                            if (qty_remaining < parseFloat(materialQty)) {
                                                Swal.fire({
                                                    title: "Warning",
                                                    html: `<b>All Data qty</b> unpacked already saved to kanban or <b>Qty</b> is greater than <b>Qty remaining</b> to save`,
                                                    icon: "warning"
                                                });
                                                return false;
                                            } else {
                                                var new_qty_remaining = qty_remaining - materialQty;
                                                $('#qty_remaining').val(new_qty_remaining);
                                            }

                                            $.ajax({
                                                url: '<?= base_url('warehouse/AddKanbanBox'); ?>',
                                                type: 'post',
                                                dataType: 'json',
                                                data: {
                                                    materialID, materialDesc, materialQty, proPlan, id_fg, user
                                                },
                                                success: function(res) {
                                                    if(res.code == 'ADD'){
                                                        Swal.fire({
                                                            title: "Success",
                                                            html: `New Kanban Box successfully added`,
                                                            icon: "success"
                                                        });

                                                        var id_kanban = res.kanban_id;

                                                        var printWindow = window.open('', '', 'height=400,width=600');
                                                        printWindow.document.write(`
                                                            <link href="<?=base_url('assets');?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
                                                            <style>
                                                                @media print {
                                                                    @page { 
                                                                        size: 15cm 10cm; 
                                                                        margin: 0;
                                                                    }
                                                                    header, footer {
                                                                        display: none;
                                                                    }
                                                                    .kanban-card {
                                                                        border: 1px solid black;
                                                                        padding: 20px;
                                                                        width: 15cm;
                                                                        height: 8.6cm;
                                                                        margin: 0;
                                                                        box-sizing: border-box;
                                                                        page-break-inside: avoid;
                                                                    }
                                                                }
                                                            </style>
                                                            <div class="kanban-card" style="border: 1px solid black; padding: 20px; width: 15cm; height: 8.6cm; margin: 15px auto; position: relative; box-sizing: border-box;">
                                                                <img src="<?=base_url('assets');?>/img/valeo-kanban-logo.png" alt="Logo" class="logo" style="width: auto; height: auto; display: block; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                                                                <h3 style="text-align: center; margin-top: -30px; margin-bottom: 5px; text-decoration: underline;">KANBAN CARD</h3>
                                                                <div class="row mt-5 me-0">
                                                                    <div class="col-md-8" style="font-size: 16px; width: 55% !important;">
                                                                        <ul style="list-style: none; padding: 0;">
                                                                            <li style="display: flex; align-items: center; padding: 5px 0;">
                                                                                <p style="margin: 0"><b>Material Part No :</b> ${materialID}</p>
                                                                            </li>
                                                                            <li style="display: flex; align-items: center; padding: 5px 0;">
                                                                                <p style="margin: 0"><b>Material Part Name :</b> ${materialDesc}</p>
                                                                            </li>
                                                                            <li style="display: flex; align-items: center; padding: 5px 0;">
                                                                                <p style="margin: 0"><b>Material Qty :</b> ${materialQty}</p>
                                                                            </li>
                                                                            <li style="display: flex; align-items: center; padding: 5px 0;">
                                                                                <p style="margin: 0"><b>FG ID :</b> ${id_fg}</p>
                                                                            </li>
                                                                            <li style="display: flex; align-items: center; padding: 5px 0;">
                                                                                <p style="margin: 0"><b>Production Plan :</b> ${proPlan}</p>
                                                                            </li>
                                                                        </ul>
                                                                    </div>  
                                                                    <div class="col-md-3 text-center" style="font-size: 14px; margin-left: 5rem; width: 8% !important;">
                                                                        <div id="preview-barcode"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `);

                                                        printWindow.document.close();

                                                        printWindow.onload = function() {
                                                            generateQRCode(id_kanban, function(no_box, qrcodeImg) {
                                                                var imgElement = printWindow.document.createElement('img');
                                                                imgElement.src = qrcodeImg;
                                                                printWindow.document.getElementById('preview-barcode').appendChild(imgElement);
                                                                printWindow.print();
                                                            });
                                                        };

                                                        $('#refresh-btn-prod').css('display', 'block');
                                                        $('#check-btn-prod').css('display', 'block');
                                                        $('#finnish-btn-prod').css('display', 'block');
                                                        $('#-btn-prod').css('display', 'block');
                                                    }
                                                    else{
                                                        Swal.fire({
                                                            title: "Error",
                                                            html: `Failed to add New Kanban Box`,
                                                            icon: "error"
                                                        });
                                                    }
                                                },
                                                error: function(xhr, ajaxOptions, thrownError) {
                                                    console.error(xhr.statusText);
                                                }
                                            });                                            
                                        });

                                        function generateQRCode(no_box, callback) {
                                            var qrcodeContainer = document.createElement('div');
                                            var qrcode = new QRCode(qrcodeContainer, {
                                                text: no_box,
                                                width: 150,
                                                height: 150,
                                                correctLevel: QRCode.CorrectLevel.H
                                            });

                                            setTimeout(function() {
                                                var qrcodeImg = qrcodeContainer.querySelector('img').src;
                                                callback(no_box, qrcodeImg);
                                            }, 500);
                                        }

                                        $('#refresh-btn-prod').on('click', function (){
                                            $('#qty_kanban').val('');
                                            $('#material_id').val(res['Id_material']);
                                            $('#material_desc').val(res['Material_desc']);
                                            $('#production_planning').val(res['Production_plan']);
                                        });
                                    }
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    console.error(xhr.statusText);
                                }
                            })
                        }
                    });
                } else {
                    $('#data-box').empty(); // Clear previous content if any
                    $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
                $('#data-box').empty();
                $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> Error fetching data<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            }
        });
    }
</script>