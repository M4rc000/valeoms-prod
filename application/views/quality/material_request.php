<section>
    <div class="card">
        <div class="card-body" style="height: 900px">
            <div class="row mt-5 justify-content-center">
                <label for="box_id" class="col-sm-2 col-form-label text-end"><b>Box No</b></label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="box_id">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-success" onclick="getBox()">Search</button>
                </div>
            </div>
            <div class="row mt-2">
                <div id="data-box"></div>
            </div>
        </div>
    </div>
</section>

<script>
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
                    // console.log(res);
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
                                <input type="text" class="form-control" value="${parseInt(weight)}" readonly disabled>
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
            error: function(xhr, ajaxOptions, thrownError) {
                // Handle AJAX error
                console.error(xhr.statusText);
            }
        });
    }
</script>