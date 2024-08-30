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
                <!-- GET USER -->
                 <input type="text" class="form-control" id="user" value="<?=$name['username'];?>" hidden>
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
                            <div class="row justify-content-center mt-3 gap-1">
                                <label for="reqPNo" class="col-sm-3 col-form-label text-end"><b>Request No</b></label>
                                <div class="col-sm-5">
                                    <select id="reqPNo" class="form-select">
                                        <option selected>Choose Request No</option>
                                        <?php foreach($requestnoprod as $pr): ?>
                                            <option value="<?=$pr['Id_request'];?>"><?=$pr['Id_request'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success" id="get-req-PNo" onclick="getReqPNo()">Search</button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile-justified" role="tabpanel"
                            aria-labelledby="profile-tab">
                            <div class="row justify-content-center mt-3 gap-1">
                                <label for="reqQNo" class="col-sm-3 col-form-label text-end"><b>Request No</b></label>
                                <div class="col-sm-5">
                                    <select id="reqQNo" class="form-select">
                                        <option selected>Choose Request No</option>
                                        <?php foreach($requestnoqual as $qr): ?>
                                            <option value="<?=$qr['Id_request'];?>"><?=$qr['Id_request'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit" class="btn btn-success" id="get-req-QNo" onclick="getReqQNo()">Search</button>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Default Tabs -->
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // WAREHOUSE
    function getReqPNo() {
        var request_no = $('#reqPNo').val().trim();

        // Check if request_no is empty or null
        if (!request_no) { 
            Swal.fire({
                title: "Error",
                html: "Request No is null or empty",
                icon: "error"
            });
            return;
        }

        window.location.href = '<?=base_url('warehouse/kitting_production/')?>' + encodeURIComponent(request_no);


        // var reqNo = $('#reqPNo').val();
        // $.ajax({
        //     url: '<?= base_url('warehouse/getReqNoPR'); ?>',
        //     type: 'post',
        //     dataType: 'json',
        //     data: {
        //         reqNo
        //     },
        //     success: function(res) {
        //         if (res) {
        //             $('#get-req-PNo').prop('disabled', true);

        //             // console.log(res);
        //             var reqNo = res.Request_result[0].Id_request;
        //             var Id_fg = res.Request_result[0].Id_fg;
        //             var Fg_desc = res.Request_result[0].Fg_desc;
        //             var Qty_prod_plan = res.Request_result[0].Production_plan_qty;
                    
        //             var rowBox = '';
        //             for(var i = 0; i < res.Box_result.length; i++){
        //                 rowBox+=
        //                 `
        //                     <div class="col-md-2 mt-2" id="box-${res.Box_result[i].no_box}">
        //                         <input class="form-control" value="${res.Box_result[i].no_box}" readonly></input>
        //                     </div>
        //                 `;
        //             }

        //             var boxOptions = res.Box_result.map(function(box) {
        //                 return `<option value="${box.no_box}">${box.no_box}</option>`;
        //             }).join('');

        //             var htmlContent = `
        //                 <div class="row mt-4">
        //                     <label for="req_nop" class="col-sm-3 col-form-label">Request No</label>
        //                     <div class="col-sm-3">
        //                         <input type="text" id="req_nop" class="form-control text-center" value="${reqNo}" readonly>
        //                     </div>
        //                 </div>
        //                 <div class="row mt-2">
        //                     <label for="Id_fg" class="col-sm-3 col-form-label">FG Part No</label>
        //                     <div class="col-sm-3">
        //                         <input type="text" id="Id_fg" class="form-control text-center" value="${Id_fg}" readonly>
        //                     </div>
        //                 </div>
        //                 <div class="row mt-2">
        //                     <label for="Fg_desc" class="col-sm-3 col-form-label">FG Part Name</label>
        //                     <div class="col-sm-5">
        //                         <input type="text" id="Fg_desc" class="form-control" value="${Fg_desc}" readonly>
        //                     </div>
        //                 </div>
        //                 <div class="row mt-2 mb-4">
        //                     <label for="qty_prod_plan" class="col-sm-3 col-form-label">Qty Production Planning</label>
        //                     <div class="col-sm-2">
        //                         <input type="text" id="qty_prod_plan" class="form-control text-center" value="${Qty_prod_plan}" readonly>
        //                     </div>
        //                 </div>
        //                 <hr>
        //                 <div class="row mt-3">
        //                     <div class="col-md">
        //                         <span class="mb-4">
        //                             <strong>
        //                                 List Box
        //                             </strong>
        //                         </span>
        //                         <div class="row mt-2">
        //                             ${rowBox}
        //                         </div>
        //                     </div>
        //                 </div>
        //                 <div class="row justify-content-center mt-4 gap-1">
        //                     <label for="box_id" class="col-sm-3 col-form-label text-end"><b>Box ID</b></label>
        //                     <div class="col-sm-5">
        //                         <select id="box_id" class="form-select">
        //                             <option selected>Choose Box</option>
        //                             ${boxOptions}
        //                         </select>
        //                     </div>
        //                     <div class="col-sm-3">
        //                         <button type="submit" class="btn btn-success" onclick="getBoxP()">Search</button>
        //                     </div>
        //                 </div>
        //             `;
                    
        //             $('#reqPNo').prop('disabled', true);
        //             $('#get-req-PNo').prop('disabled', true);
        //             $('#data-request-prod').empty().append(htmlContent);
        //             $('#box_id').select2();
        //         } else {
        //             // Handle case when BOX ID is not found
        //             $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
        //         }
        //     },
        //     error: function(xhr, ajaxOptions, thrownError) {
        //         // Handle AJAX error
        //         console.error(xhr.statusText);
        //     }
        // });
    }

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

    // QUALITY
    // MENDAPATKAN MATERIAL REQUEST NO
    function getReqQNo() {
        var request_no = $('#reqQNo').val().trim();

        // Check if request_no is empty or null
        if (!request_no) { 
            Swal.fire({
                title: "Error",
                html: "Request No is null or empty",
                icon: "error"
            });
            return;
        }

        window.location.href = '<?=base_url('warehouse/kitting_quality/')?>' + encodeURIComponent(request_no);
        
        // var reqNo = $('#reqNo').val();
        // $.ajax({
        //     url: '<?= base_url('warehouse/getReqNoQR'); ?>',
        //     type: 'post',
        //     dataType: 'json',
        //     data: {
        //         reqNo
        //     },
        //     success: function(res) {
        //         if (res) {
        //             console.log(res);
        //             var reqNo = res[0].Id_request;
        //             var Id_material = res[0].Id_material;
        //             var Material_desc = res[0].Material_desc;
        //             var Material_need = res[0].Material_need;
        //             var Uom = res[0].Uom;

        //             // <div class="col-md-2">
        //             //     <input class="form-control" value="${res[i].no_box}" readonly></input>
        //             // </div>
                    
        //             var rowBox = '';
        //             for(var i = 0; i < res.length; i++){
        //                 rowBox+=
        //                 `
        //                     <div class="col-md-2 mt-2" id="boxq-${res[i].no_box}">
        //                         <input class="form-control" value="${res[i].no_box}" readonly></input>
        //                     </div>
        //                 `;
        //             }

        //             var boxOptions = res.map(function(box) {
        //                 return `<option value="${box.no_box}">${box.no_box}</option>`;
        //             }).join('');

        //             var htmlContent = `
        //                 <div class="row mt-4">
        //                     <label for="req_no" class="col-sm-3 col-form-label">Request No</label>
        //                     <div class="col-sm-3">
        //                         <input type="text" id="req_no" class="form-control text-center" value="${reqNo}" readonly>
        //                     </div>
        //                 </div>
        //                 <div class="row mt-2">
        //                     <label for="Id_material" class="col-sm-3 col-form-label">Material Part No</label>
        //                     <div class="col-sm-3">
        //                         <input type="text" id="Id_material" class="form-control text-center" value="${Id_material}" readonly>
        //                     </div>
        //                 </div>
        //                 <div class="row mt-2">
        //                     <label for="Material_desc" class="col-sm-3 col-form-label">Material Part Name</label>
        //                     <div class="col-sm-3">
        //                         <input type="text" id="Material_desc" class="form-control" value="${Material_desc}" readonly>
        //                     </div>
        //                 </div>
        //                 <div class="row mt-2 mb-4">
        //                     <label for="Material_need" class="col-sm-3 col-form-label">Material Need</label>
        //                     <div class="col-sm-2">
        //                         <input type="text" id="Material_need" class="form-control text-center" value="${Material_need}" readonly>
        //                     </div>
        //                     <div class="col-sm-2">
        //                         <input type="text" id="Uom" class="form-control text-center" value="${Uom}" readonly>
        //                     </div>
        //                 </div>
        //                 <hr>
        //                 <div class="row mt-3">
        //                     <div class="col-md">
        //                         <span class="mb-4">
        //                             <strong>
        //                                 List Box
        //                             </strong>
        //                         </span>
        //                         <div class="row mt-2">
        //                             ${rowBox}
        //                         </div>
        //                     </div>
        //                 </div>
        //                 <div class="row justify-content-center mt-3 gap-1">
        //                     <label for="box_id-q" class="col-sm-3 col-form-label text-end"><b>Box ID</b></label>
        //                     <div class="col-sm-5">
        //                         <select id="box_id-q" class="form-select">
        //                             <option selected>Choose Box</option>
        //                             ${boxOptions}
        //                         </select>
        //                     </div>
        //                     <div class="col-sm-3">
        //                         <button type="button" class="btn btn-success" onclick="getBoxQ()">Search</button>
        //                     </div>
        //                 </div>
        //             `;
                    
        //             $('#reqNo').prop('disabled', true);
        //             $('#get-req-QNo').prop('disabled', true);
        //             $('#data-request').empty().append(htmlContent);
        //             $('#box_id-q').select2();
        //         } else {
        //             // Handle case when BOX ID is not found
        //             $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
        //         }
        //     },
        //     error: function(xhr, ajaxOptions, thrownError) {
        //         // Handle AJAX error
        //         console.error(xhr.statusText);
        //     }
        // });
    }

    // MENDAPATKAN BOX 
    function getBoxQ() {
        var boxID = $('#box_id-q').val();
        var req_no = $('#req_no').val();
        var user = $('#user').val();

        $.ajax({
            url: '<?= base_url('warehouse/getBox'); ?>',
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
                    var fill = ''; // Variable to capture id_box
                    for (let numbers = 0; numbers < res.Box_result.length; numbers++) {
                        var boxItem = res.Box_result[numbers];
                        var isChecked = false;
                        var materialNeed = '';

                        // Check if Box_result[numbers].product_id and Box_result[numbers].id_box match any Quality_result item
                        for (let i = 0; i < res.Quality_result.length; i++) {
                            var qualityItem = res.Quality_result[i];
                            if (boxItem.product_id === qualityItem.Id_material && boxItem.id_box == qualityItem.id_box) {
                                isChecked = true;
                                materialNeed = qualityItem.Material_need;
                                fill = boxItem.id_box; // Capture id_box when matched
                                break; // Exit loop once matched
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
                                <button type="button" class="btn btn-success" id="unpackBtn">Unpack</button>
                            </div>
                        </div>
                        <div class="row justify-content-center mt-3 gap-1">
                            <div class="col text-end">
                                <button type="button" class="btn btn-warning" id="refresh-btn" data-bs-toggle="modal" data-bs-target="#refresh-page" disabled><i class="bx bx-revision"></i></button>
                            </div>
                        </div>
                    `;

                    $('#data-box').empty().append(htmlContent);
                    // $('#reqNo').select2(); 

                    // Bind click event to Unpack button
                    $('#unpackBtn').on('click', function() {
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
                                        // Swal.fire({
                                        //     title: "Success",
                                        //     html: `Box <b>${res}</b> have been unpacked`,
                                        //     icon: "success"
                                        // });

                                        Swal.fire({
                                            title: "Success",
                                            html: `Box <b>${res}</b> has been unpacked`,
                                            icon: "success"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Select and remove the specific box element by its unique ID
                                                const element = $(`#boxq-${res}`);
                                                element.remove();
                                            }
                                        });

                                        $('#refresh-btn').prop('disabled', false);
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
                $('#data-box').empty(); // Clear previous content if any
                $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> Error fetching data<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            }
        });
    }
    
    $(document).ready(function (){
        $('#reqQNo').select2({
            'width': '100%'
        }); 
        
        $('#reqPNo').select2({
            'width': '100%'
        }); 
    });
</script>

<!-- REFRESH MODAL -->
<div class="modal fade" id="refresh-page" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to refresh?</h4>
				</div>
				<div class="modal-body">
					<span>Did you have already unpack all the boxes ?</span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
					<a href="<?=base_url('warehouse/kitting')?>"><button type="button" class="btn btn-primary" name="delete_user">Confirm</button></a>
				</div>
		</div>
	</div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "The record have been saved",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "Failed save the record",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>