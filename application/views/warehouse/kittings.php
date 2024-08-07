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
                <div class="row mt-3">
                    <!-- Default Tabs -->
                    <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home-justified" type="button" role="tab" aria-controls="home"
                                aria-selected="true">
                                Production</button>
                        </li>
                        <li class="nav-item flex-fill" role="presentation">
                            <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-justified" type="button" role="tab"
                                aria-controls="profile" aria-selected="false">Quality</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2" id="myTabjustifiedContent">
                        <div class="tab-pane fade show active" id="home-justified" role="tabpanel"
                            aria-labelledby="home-tab">
                        </div>
                        <div class="tab-pane fade" id="profile-justified" role="tabpanel"
                            aria-labelledby="profile-tab">
                            <div class="row mt-3 mb-1">
                                <div id="notif"></div>
                            </div>
                            <div class="row justify-content-center mt-3 gap-1">
                                <label for="box_id" class="col-sm-3 col-form-label text-end"><b>Box ID</b></label>
                                <div class="col-sm-5">
                                    <select id="box_id" class="form-select">
                                        <option selected>Choose Box</option>
                                        <?php foreach($boxs as $box): ?>
                                            <option value="<?=$box['no_box'];?>"><?=$box['no_box'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success" onclick="getBoxQ()">Search</button>
                                </div>
                            </div>
                            <div class="row mt-3 mb-1">
                                <div id="data-box"></div>
                            </div>
                            <div class="row mt-1 mb-1">
                                <div id="data-request"></div>
                            </div>              
                        </div>
                    </div><!-- End Default Tabs -->
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // MENDAPATKAN BOX BERDASARKAN ID
    function getBoxQ() {
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
                    var sloc = res[0].Sloc;
                    var weight = res[0].weight;

                    var row = '';
                    for (let number = 0; number < res.length; number++) {
                        row+=
                        `
                        <tr>
                            <th scope="row" class="text-center">${number+1}</th>
                            <td class="text-center">${res[number].product_id}</td>
                            <td class="text-center">${res[number].material_desc}</td>
                            <td class="text-center">${res[number].SLoc}</td>
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
                                    <?php foreach($requestnoqual as $qr): ?>
                                        <option value="<?=$qr['Id_request'];?>"><?=$qr['Id_request'];?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-success" onclick="getReqQNo()">Search</button>
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
    function getReqQNo() {
        var boxID = $('#box_id').val();
        var reqNo = $('#reqNo').val();
        $.ajax({
            url: '<?= base_url('warehouse/getReqNoQR'); ?>',
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
                    var Id_material = res.reqNo_result[0].Id_material;
                    var Material_desc = res.reqNo_result[0].Material_desc;
                    var Material_need = res.reqNo_result[0].Material_need;
                    var Uom = res.reqNo_result[0].Uom;
                    
                    
                    // NO BOX
                    var boxID = res.boxID_result[0].no_box;

                    // FG PRODUCT ID
                    var production_id = res.reqNo_result[0]


                    // MENCOCOKAN DENGAN YANG ADA DI BOX
                    var productMaterials = res.Quality_result.map(item => item.Id_material);

                    var row = '';
                    for (let numbers = 0; numbers < res.boxID_result.length; numbers++) {
                        // Check if the current Material ID exists in the productMaterials array
                        var isChecked = productMaterials.includes(res.boxID_result[numbers].product_id) ? 'checked' : '';

                        // Fetch material_need from Quality_result if isChecked is true
                        var materialNeed = isChecked ? res.Quality_result.find(item => item.Id_material === res.boxID_result[numbers].product_id)?.Material_need : '';

                        row += `
                            <tr>
                                <th scope="row" class="text-center"><input class="form-check-input" type="checkbox" id="gridCheck1" ${isChecked}></th>
                                <th scope="row" class="text-center">${numbers+1}</th>
                                <td class="text-center">${res.boxID_result[numbers].product_id}</td>
                                <td class="text-center">${res.boxID_result[numbers].material_desc}</td>
                                <td class="text-center">${materialNeed}</td> 
                                <td class="text-center">${res.boxID_result[numbers].SLoc}</td>
                                <td class="text-center">${res.boxID_result[numbers].total_qty}</td>
                                <td class="text-center">${res.boxID_result[numbers].uom}</td>
                            </tr>
                        `;
                    }

                    var htmlContent = `
                        <div class="row mt-5">
                            <label for="box_iD" class="col-sm-3 col-form-label">Box ID</label>
                            <div class="col-sm-3">
                                <input type="text" id="box_iD" class="form-control text-center" value="${boxID}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="req_no" class="col-sm-3 col-form-label">Request No</label>
                            <div class="col-sm-3">
                                <input type="text" id="req_no" class="form-control text-center" value="${reqNo}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="Id_material" class="col-sm-3 col-form-label">Material Part No</label>
                            <div class="col-sm-3">
                                <input type="text" id="Id_material" class="form-control text-center" value="${Id_material}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="Material_desc" class="col-sm-3 col-form-label">Material Part Name</label>
                            <div class="col-sm-3">
                                <input type="text" id="Material_desc" class="form-control" value="${Material_desc}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2 mb-5">
                            <label for="Material_need" class="col-sm-3 col-form-label">Material Need</label>
                            <div class="col-sm-2">
                                <input type="text" id="Material_need" class="form-control text-center" value="${Material_need}" readonly>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="Uom" class="form-control text-center" value="${Uom}" readonly>
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