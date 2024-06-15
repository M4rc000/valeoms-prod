<section style="font-family: Nunito;">
    <div class="row">
        <div class="col-md-12">
            <div class="card" style="height: 3000px">
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                                <li class="nav-item flex-fill" role="presentation">
                                  <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-justified" type="button" role="tab" aria-controls="home" aria-selected="true">High Rack</button>
                                </li>
                                <li class="nav-item flex-fill" role="presentation">
                                  <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-justified" type="button" role="tab" aria-controls="profile" aria-selected="false">Medium Rack</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2" id="myTabjustifiedContent">
                                <div class="mx-2 tab-pane fade show active" id="home-justified" role="tabpanel" aria-labelledby="home-tab">
                                </div>
                                <div class="mx-2 tab-pane fade" id="profile-justified" role="tabpanel" aria-labelledby="profile-tab">
                                    <?= form_open_multipart('production/AddMediumRack'); ?>
                                        <div class="row mb-3 mx-2">
                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-primary" id="add-row-btn">
                                                    <i class="bi bi-plus-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mt-3 mx-2">
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
                                                            <tbody id="table-body"></tbody>
                                                        </table>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3 mx-2">
                                            <div class="col-md text-end">
                                                <button type="submit" class="btn btn-success">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function (){
        $(document).on('change', 'input[name^="materials"][name$="[material_id]"]', function () {
            var materialID = $(this).val();

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
                    var qty = res[0].Uom;

                    $('input[name="materials[' + rowIndex + '][material_desc]"]').val(materialDesc);
                    $('input[name="materials[' + rowIndex + '][material_type]"]').val(materialDesc);
                    $('input[name="materials[' + rowIndex + '][uom]"]').val(materialDesc);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.error(xhr.statusText);
                }
            });
        });


        let rowIndex = 1;

        $('#add-row-btn').click(function() {
            addRow();
        });

        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('tr').remove();
            updateRowIndices();
        });

        function addRow() {
            const newRow = `
                <tr>
                    <td class="py-3"><b>${rowIndex}</b></td>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowIndex}][material_id]" required aria-label="Material ID" style="width: 160px;">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowIndex}][material_desc]" required aria-label="Material Description" style="width: 300px;">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowIndex}][material_type]" aria-label="Material Type" style="width: 120px;">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowIndex}][qty]" required aria-label="Quantity" style="width: 100px;">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="materials[${rowIndex}][uom]" required aria-label="Unit of Measure" style="width: 100px;">
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-remove-row" type="button" aria-label="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#table-body').append(newRow);
            rowIndex++;
            updateRowIndices();
        }

        function updateRowIndices() {
            $('#table-body tr').each(function(index) {
                $(this).find('td:first-child b').text(index + 1);
                $(this).find('input').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    $(this).attr('name', newName);
                });
            });
            rowIndex = $('#table-body tr').length;
        }
    })
</script>