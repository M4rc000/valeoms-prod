<section>
    <div class="row">
        <div class="card">
            <div class="card-body" style="height: 900px;">
                <div class="row mt-3 mb-1">
                    <div id="notif"></div>
                </div>
                <div class="row justify-content-center mt-3 gap-1">
                    <label for="box_id" class="col-sm-3 col-form-label text-end"><b>BOX ID</b></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="box_id">
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
            url: '<?= base_url('production/getBox'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                boxID
            },
            success: function(res) {
                if (res.length > 0) {
                    var boxID = res[0].id_box;
                    var sloc = res[0].sloc;
                    var weight = res[0].weight;

                    // // Construct HTML content to append
                    var htmlContent = 
                    `
                        <span style="font-size: 15px">
                            <p class="ms-2 mt-5"><b>BOX ID :</b> ${boxID} </p>
                            <p class="ms-2"><b>Weight :</b> ${weight} </p>                    
                        </span>
                    `;
                    htmlContent += 
                    `
                    <table class="table datatable table-bordered mb-5">
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2" class="text-center">#</th>
                                <th scope="col" rowspan="2" class="text-center">Material ID</th>
                                <th scope="col" rowspan="2" class="text-center">Material Description</th>
                                <th scope="col" colspan="3" class="text-center">Location 1</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">SLoc</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-center">UOM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="text-center">1</th>
                                <td class="text-center">1001</td>
                                <td class="text-center">Product A</td>
                                <td class="text-center">Loc1</td>
                                <td class="text-center">20</td>
                                <td class="text-center">pcs</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-center">2</th>
                                <td class="text-center">1002</td>
                                <td class="text-center">Product B</td>
                                <td class="text-center">D-1</td>
                                <td class="text-center">25</td>
                                <td class="text-center">pcs</td>
                            </tr>
                        </tbody>
                    </table>
                    `;  

                    htmlContent += `
                    <div class="row justify-content-center mt-5 gap-1">
                        <label for="reqNo" class="col-sm-3 col-form-label text-end"><b>Request No</b></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" id="reqNo" name="reqNo">
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-success" onclick="getReqNo()">Search</button>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-5 gap-1">
                        <div id="data-request"></div>
                    </div>
                    `;

                    // Append the HTML content to the div with id "data"
                    $('#data-box').empty().append(htmlContent);
                } else {
                    // Handle case when BOX ID is not found
                    $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            // error: function(xhr, ajaxOptions, thrownError) {
            //     // Handle AJAX error
            //     console.error(xhr.statusText);
            // }
        });
    }

    function getReqNo() {
        var boxID = $('#box_id').val();
        var reqNo = $('#reqNo').val();
        $.ajax({
            url: '<?= base_url('production/getReqNo'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                reqNo
            },
            success: function(res) {
                if (res.length > 0) {
                    console.log(res);
                    var reqNo = res[0].production_request_no;

                    // Construct HTML content to append
                    var htmlContent = 
                    `
                        <span style="font-size: 15px">
                            <p class="ms-2 mt-5"><b>BOX ID :</b> ${boxID} </p>
                            <p class="ms-2"><b>Request No :</b> ${reqNo} </p>                    
                        </span>
                    `;
                    htmlContent += 
                    `
                    <table class="table datatable table-bordered mb-5">
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2" class="text-center">#</th>
                                <th scope="col" rowspan="2" class="text-center">No</th>
                                <th scope="col" rowspan="2" class="text-center">Material ID</th>
                                <th scope="col" rowspan="2" class="text-center">Material Description</th>
                                <th scope="col" colspan="3" class="text-center">Location 1</th>
                            </tr>
                            <tr>
                                <th scope="col" class="text-center">SLoc</th>
                                <th scope="col" class="text-center">Qty</th>
                                <th scope="col" class="text-center">UOM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="text-center"><input class="form-check-input" type="checkbox" id="gridCheck1"></th>
                                <th scope="row" class="text-center">1</th>
                                <td class="text-center">1001</td>
                                <td class="text-center">Product A</td>
                                <td class="text-center">Loc1</td>
                                <td class="text-center">20</td>
                                <td class="text-center">pcs</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-center"><input class="form-check-input" type="checkbox" id="gridCheck1"></th>
                                <th scope="row" class="text-center">2</th>
                                <td class="text-center">1002</td>
                                <td class="text-center">Product B</td>
                                <td class="text-center">D-1</td>
                                <td class="text-center">25</td>
                                <td class="text-center">pcs</td>
                            </tr>
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

                    // Append the HTML content to the div with id "data"
                    $('#data-request').empty().append(htmlContent);
                } else {
                    // Handle case when BOX ID is not found
                    $('#notif').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> BOX ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            // error: function(xhr, ajaxOptions, thrownError) {
            //     // Handle AJAX error
            //     console.error(xhr.statusText);
            // }
        });
    }
</script>