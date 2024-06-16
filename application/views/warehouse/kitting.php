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
    <div class="row">
        <div class="card">
            <div class="card-body" style="height: 1500px;">
                <div class="row mt-3 mb-1">
                    <div id="notif"></div>
                </div>
                <div class="row justify-content-center mt-3 gap-1">
                    <label for="box_id" class="col-sm-3 col-form-label text-end"><b>Box ID</b></label>
                    <div class="col-sm-5">
                        <select id="box_id" class="form-select">
                            <option selected>Choose Product FG</option>
                            <?php foreach($boxs as $box): ?>
                                <option value="<?=$box['no_box'];?>"><?=$box['no_box'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-success" onclick="getBox()">Search</button>
                    </div>
                </div>
                <div class="row mt-3 mb-1">
                    <div id="data-box"></div>
                </div>
                <div class="row mt-1 mb-1">
                    <div id="data-request"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // MENDAPATKAN BOX BERDASARKAN ID
    function getBox() {
        var boxID = $('#box_id').val();
        $.ajax({
            url: '<?= base_url('warehouse/getBox'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                boxID
            },
            success: function(res) {
                if (res.length > 0) {
                    console.log(res);
                    var boxID = res[0].no_box;
                    var sloc = res[0].sloc;
                    var weight = res[0].weight;

                    var row = '';
                    for (let number = 0; number < res.length; number++) {
                        row+=
                        `
                        <tr>
                            <th scope="row" class="text-center">${number+1}</th>
                            <td class="text-center">${res[number].product_id}</td>
                            <td class="text-center">${res[number].material_desc}</td>
                            <td class="text-center">${res[number].sloc}</td>
                            <td class="text-center">${res[number].total_qty}</td>
                            <td class="text-center">${res[number].uom}</td>
                        </tr>
                        `;
                    }
                    var htmlContent = 
                    `
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
                    htmlContent += 
                    `
                        <table class="table datatable table-bordered mb-5">
                            <thead>
                                <tr>
                                    <th scope="col" rowspan="2" class="text-center">#</th>
                                    <th scope="col" rowspan="2" class="text-center">Material Part No</th>
                                    <th scope="col" rowspan="2" class="text-center">Material Part Name</th>
                                    <th scope="col" colspan="3" class="text-center">Location 1</th>
                                </tr>
                                <tr>
                                    <th scope="col" class="text-center">SLoc</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-center">UOM</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${row}
                            </tbody>
                        </table>    
                    `;  

                    htmlContent += 
                    `
                        <div class="row justify-content-center mt-5 gap-1">
                            <label for="reqNo" class="col-sm-3 col-form-label text-end"><b>Request No</b></label>
                            <div class="col-md-4">
                                <select id="reqNo" class="form-select">
                                    <option selected>Choose Product FG</option>
                                    <?php foreach($requestno as $rq): ?>
                                        <option value="<?=$rq['Id_request'];?>"><?=$rq['Id_request'];?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-success" onclick="getReqNo()">Search</button>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-5 gap-1">
                            <div id="data-request"></div>
                        </div>
                    `;

                    $('#data-box').empty().append(htmlContent);
                    $('#reqNo').select2();
                } else {
                    // Handle case when BOX ID is not found
                    $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                // Handle AJAX error
                console.error(xhr.statusText);
            }
        });
    }

    // MENDAPATKAN MATERIAL REQUEST NO
    function getReqNo() {
        var boxID = $('#box_id').val();
        var reqNo = $('#reqNo').val();
        $.ajax({
            url: '<?= base_url('warehouse/getReqNo'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                reqNo, boxID
            },
            success: function(res) {
                if (res.reqNo_result.length > 0) {
                    console.log(res);
                    // ID MATERIAL REQUEST
                    var reqNo = res.reqNo_result[0].Id_request;
                    
                    // NO BOX
                    var boxID = res.boxID_result[0].no_box;

                    // FG PRODUCT ID
                    var production_id = res.reqNo_result[0]


                    // MENCOCOKAN DENGAN YANG ADA DI BOX
                    var productMaterials = res.ProdPlan_result.map(item => item.Id_material);

                    var row = '';
                    for (let numbers = 0; numbers < res.boxID_result.length; numbers++) {
                        // Check if the current Material ID exists in the productMaterials array
                        var isChecked = productMaterials.includes(res.boxID_result[numbers].product_id) ? 'checked' : '';

                        // Fetch material_need from ProdPlan_result if isChecked is true
                        var materialNeed = isChecked ? res.ProdPlan_result.find(item => item.Id_material === res.boxID_result[numbers].product_id)?.Material_need : '';

                        row += `
                            <tr>
                                <th scope="row" class="text-center"><input class="form-check-input" type="checkbox" id="gridCheck1" ${isChecked}></th>
                                <th scope="row" class="text-center">${numbers+1}</th>
                                <td class="text-center">${res.boxID_result[numbers].product_id}</td>
                                <td class="text-center">${res.boxID_result[numbers].material_desc}</td>
                                <td class="text-center">${materialNeed}</td> 
                                <td class="text-center">${res.boxID_result[numbers].sloc}</td>
                                <td class="text-center">${res.boxID_result[numbers].total_qty}</td>
                                <td class="text-center">${res.boxID_result[numbers].uom}</td>
                            </tr>
                        `;
                    }

                    var htmlContent = `
                        <div class="row mt-5">
                            <label for="box_iD" class="col-sm-2 col-form-label">Box ID</label>
                            <div class="col-sm-3">
                                <input type="text" id="box_iD" class="form-control" value="${boxID}" readonly disabled>
                            </div>
                        </div>
                        <div class="row mt-2 mb-5">
                            <label for="req_no" class="col-sm-2 col-form-label">Request No</label>
                            <div class="col-sm-3">
                                <input type="text" id="req_no" class="form-control" value="${reqNo}" readonly disabled>
                            </div>
                        </div>
                    `;

                    htmlContent += `
                    <table class="table datatable table-bordered mb-5">
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2" class="text-center">#</th>
                                <th scope="col" rowspan="2" class="text-center">No</th>
                                <th scope="col" rowspan="2" class="text-center">Material Part No</th>
                                <th scope="col" rowspan="2" class="text-center">Material Name</th>
                                <th scope="col" rowspan="2" class="text-center">Material need</th>
                                <th scope="col" colspan="3" class="text-center">Location 1</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">SLoc</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-center">UOM</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${row}
                        </tbody>
                    </table>
                    `;

                    htmlContent += `
                    <div class="row justify-content-center mt-5 gap-1">
                        <div class="col text-end">
                            <button type="button" class="btn btn-success">Unpack</button>
                        </div>
                    </div>
                    `;

                    $('#data-request').empty().append(htmlContent);
                } else {
                    // Handle case when BOX ID is not found
                    $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                // Handle AJAX error
                console.error(xhr.statusText);
            }
        });
    }

    $(document).ready(function (){
        $('#box_id').select2();
    });
</script>