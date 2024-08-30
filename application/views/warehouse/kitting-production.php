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
    <div class="card">
        <div class="card-body">
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
                    <input type="text" id="Id_fg" class="form-control text-center" value="<?=$Request_result[0]['Id_fg'];?>"
                        readonly>
                </div>
            </div>
            <div class="row mt-2 px-2">
                <label for="Fg_desc" class="col-sm-3 col-form-label"><b>FG Part Name</b></label>
                <div class="col-sm-6">
                    <input type="text" id="Fg_desc" class="form-control" value="<?=$Request_result[0]['Fg_desc'];?>" readonly>
                </div>
            </div>
            <div class="row mt-2 px-2">
                <label for="qty_prod_plan" class="col-sm-3 col-form-label"><b>Qty Production Planning</b></label>
                <div class="col-sm-2">
                    <input type="text" id="qty_prod_plan" class="form-control text-center"
                        value="<?=$Request_result[0]['Production_plan_qty'];?>" readonly>
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
                            <input type="text" id="qty_prod_plan" class="form-control text-center" value="<?=$Request_result[0]['Material_need'];?>" readonly>
                        </div>
                        <div class="col-3 col-md-3 col-lg-3">
                            <input type="text" id="qty_prod_plan_uom" class="form-control text-center" value="<?=$Request_result[0]['Uom'];?>" readonly>
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
                    <button type="submit" class="btn btn-success" onclick="getBoxP()">Search</button>
                </div>
            </div>
            <div class="row mt-3 mb-1">
                <div id="data-box-prod"></div>
            </div>
        </div>
    </div>    
</section>

<script>
    $(document).ready(function () {
        $('#box_id').select2();

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
                                materialNeed = qualityItem.Material_need;
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
                        <table class="table datatable table-bordered mb-5">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col" class="text-center">Material Part No</th>
                                    <th scope="col" class="text-center">Material Name</th>
                                    <th scope="col" class="text-center">Material need</th>
                                    <th scope="col" class="text-center">SLoc</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-center">UOM</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${rows}
                            </tbody>
                        </table>
                    `;

                    htmlContent += `
                        <div class="row justify-content-center mt-5 gap-1">
                            <div class="col text-end">
                                <button type="button" class="btn btn-success" id="unpackBtn-prod">Unpack</button>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3 gap-1">
                            <div class="col text-end">
                                <button type="button" class="btn btn-warning" id="refresh-btn-prod" data-bs-toggle="modal" data-bs-target="#refresh-page" disabled><i class="bx bx-revision"></i></button>
                            </div>
                        </div>
                    `;

                    $('#data-box-prod').empty().append(htmlContent);

                    // Bind click event to Unpack button
                    $('#unpackBtn-prod').on('click', function() {
                        var checkedItems = [];
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
                                url: '<?= base_url('warehouse/save_kitting'); ?>',
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    checkedItems, fill, user
                                },
                                success: function(res) {
                                    if(res){
                                        Swal.fire({
                                            title: "Success",
                                            html: `Box <b>${res}</b> has been unpacked`,
                                            icon: "success"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Select and remove the specific box element by its unique ID
                                                const element = $(`#box-${res}`);
                                                element.remove();
                                            }
                                        });

                                        $('#refresh-btn-prod').prop('disabled', false);
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