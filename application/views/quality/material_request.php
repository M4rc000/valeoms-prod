<style>
    .select2-container {
		z-index: 99;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
    .table-container {
        width: 100%;
    }

    .table-container table {
        width: 100%;
        table-layout: fixed;
    }

    .table-container td {
        vertical-align: middle;
        text-align: center;
    }

    .table-container select,
    .table-container input {
        width: 100%;
        box-sizing: border-box;
    }

    .fixed-width {
        width: 150px;
    }
</style>
<section>
    <div class="card">
        <div class="card-body" style="height: 900px">
            <!-- GET USER -->
            <input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
            <div class="row mt-5 mb-5 justify-content-center">
                <div class="col-12 col-sm-3 mb-3 mb-sm-0 text-center">
                    <label for="material_id" class="col-form-label"><b>Material Part No</b></label>
                </div>
                <div class="col-12 col-sm-5 mb-3 mb-sm-0">
                    <select id="material_id" class="form-select">
                        <option selected>Choose Materials</option>
                        <?php foreach($materials as $mt): ?>
                            <option value="<?=$mt['Id_material'];?>"><?=$mt['Id_material'];?> | <?=$mt['Material_desc'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12 col-sm-2">
                    <button class="btn btn-success w-100" id="btn-search" onclick="getMaterials()">Search</button>
                </div>
            </div>
            <hr class="mt-2 mb-3">
            <div class="row mt-2 mb-3 mx-3" id="data-desc"></div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#material_id').select2();
    });
    
    function getMaterials() {
        var material_id = $('#material_id').val();
        $.ajax({
            url: '<?= base_url('quality/getMaterial'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                material_id
            },
            success: function(res) {
                if (res.length > 0) {
                    $('#btn-search').prop('disabled', true);    
                    $('#material_id').prop('disabled', true);
                    var htmlDesc = 
                    `
                        <div class="row mt-5">
                            <label for="Id_material" class="col-12 col-sm-4 col-form-label text-sm-center"><b>Material ID</b></label>
                            <div class="col-12 col-sm-3">
                                <input type="text" class="form-control" value="${res[0].Id_material}" name="Id_material" id="Id_material" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="Material_desc" class="col-12 col-sm-4 col-form-label text-sm-center"><b>Material Description</b></label>
                            <div class="col-12 col-sm-6">
                                <input type="text" class="form-control" id="Material_desc" name="Material_desc" value="${res[0].Material_desc}" readonly>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <label for="material_need" class="col-12 col-sm-4 col-form-label text-sm-center"><b>Material Need</b></label>
                            <div class="col-12 col-sm-2 mb-3 mb-sm-0">
                                <input type="text" class="form-control" id="material_need" name="material_need" min="1" required placeholder="0.5">
                            </div>
                            <div class="col-12 col-sm-2 mb-3 mb-sm-0">
                                <input type="text" class="form-control" id="uom" name="uom" value="${res[0].Uom}" readonly>
                            </div>
                            <div class="col-12 col-sm-3 d-flex align-items-center">
                                <button type="button" class="btn btn-primary w-100" id="calculate-material" onclick="getSaveMaterialRequest()" style="background-color: #4154f1">Submit</button>
                            </div>
                        </div>
                    `;
                    
                    $('#data-desc').empty().append(htmlDesc);
                } 
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.error(xhr.statusText);
            }
        });
    }

    function getSaveMaterialRequest() {
        var material_id = $('#Id_material').val();
        var material_desc = $('#Material_desc').val();
        var material_need = $('#material_need').val();
        var material_uom = $('#uom').val();
        var user = $('#user').val();

        if(material_need.length < 1){
            return Swal.fire({
                title: 'Error!',
                html: `<b>Material need</b> is empty`,
                icon: 'error',
                confirmButtonText: 'Close'
            });
        }

        $.ajax({
            url: '<?= base_url('quality/getCalculateMaterial'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                material_id, material_desc, material_need, material_uom, user
            },
            success: function(res) {
                if(res == 'success'){
                    Swal.fire({
                        title: "Success",
                        text: "Material Qty have been requested",
                        icon: "success"
                    }).then(() => {
                        window.location.href = '<?=base_url('quality/');?>';
                    });
                }
                else{
                    Swal.fire({
                        title: "Error",
                        text: "Material Qty haven't been failed requested",
                        icon: "error"
                    }).then(() => {
                        window.location.href = '<?=base_url('quality/');?>';
                    });
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    title: "Error",
                    text: "Material Qty haven't been failed requested",
                    icon: "error"
                }).then(() => {
                    window.location.href = '<?=base_url('quality/');?>';
                });
            }
        });
    }
</script>