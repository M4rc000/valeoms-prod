<style>
    .select2-container {
      z-index: 99;
    }

    .select2-selection {
      padding-top: 4px !important;
      height: 38px !important;
    }
    .col-md-21 {
      flex: 0 0 auto;
      width: 100%;
    }
</style>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card" style="height: 100%">
        <div class="card-body">
          <div class="row">
            <div class="col-md-2 mt-3 mb-4">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                New Material
              </button>
            </div>
          </div>
          <div class="table-responsive">
            <div class="row mt-4 mb-4 justify-content-center">
              <div class="col-12 col-md-4 mb-3 mb-md-0 text-center">
                  <b>Material Part No</b>
              </div>
              <div class="col-12 col-md-5 mb-3 mb-md-0">
                  <select class="form-select" id="material_id" name="material_id" required>
                      <option value="">Select Material Part No</option>
                      <?php foreach($material_list as $mtr): ?>
                      <option value="<?=$mtr['Id_material']?>"><?=$mtr['Id_material']?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="col-12 col-md-3">
                  <button class="btn btn-success w-100" type="submit" onclick="getMaterialList()">
                      Search
                  </button>
              </div>
            </div>
            <div class="row mt-5 justify-content-center" id="row-pagination"></div>
            <div class="row mt-2 mb-1 px-2">
                <div class="col">
                    <span style="font-size: 16px;"><b>Total Material : </b> <?=$total_rows;?></span>
                </div>
            </div>
            <div class="row mt-1 px-3" id="data-table">
                <div class="col-sm-12">
                  <table class="table table-bordered mt-3" id="table-content">
                    <thead>
                      <tr>
                        <th class="text-center">Material Part No</th>
                        <th class="text-center">Material Part Name</th>
                        <th class="text-center">Material Type</th>
                        <th class="text-center">Uom</th>
                        <th class="text-center">Family</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($materials as $mtr): ?>
                        <tr>
                          <td class="text-center"><?= $mtr['Id_material']; ?></td>
                          <td class="text-start"><?= $mtr['Material_desc']; ?></td>
                          <td class="text-center"><?= $mtr['Material_type']; ?></td>
                          <td class="text-center"><?= $mtr['Uom']; ?></td>
                          <td class="text-center"><?= $mtr['Family']; ?></td>
                          <td class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?= $mtr['Id']; ?>">
                              <span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
                            </a>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $mtr['Id']; ?>">
                              <span class="badge bg-danger"><i class="bi bi-trash"></i></span>
                            </a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ADD MODAL -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <?= form_open_multipart('master/AddMaterialList'); ?>
        <div class="modal-header">
          <h5 class="modal-title">Add Material List</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- GET USER -->
          <input type="text" class="form-control" id="user" name="user" value="<?= $name['username']; ?>" hidden> 
          <div class="row g-3">
            <div class="col-12 col-md-4">
              <label for="material_id" class="form-label">Material ID</label>
              <input type="text" class="form-control" id="material_id" name="material_id" required>
            </div>
            <div class="col-12 col-md-4">
              <label for="material_desc" class="form-label">Material Description</label>
              <input type="text" class="form-control" id="material_desc" name="material_desc" required>
            </div>
            <div class="col-12 col-md-4">
              <label for="material_type" class="form-label">Material Type</label>
              <input type="text" class="form-control" id="material_type" name="material_type">
            </div>
          </div>
          <div class="row g-3 mt-3">
            <div class="col-12 col-md-4">
              <label for="uom" class="form-label">Uom</label>
              <input type="text" class="form-control" id="uom" name="uom" required>
            </div>
            <div class="col-12 col-md-4">
              <label for="family" class="form-label">Family</label>
              <input type="text" class="form-control" id="family" name="family">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- EDIT MODAL -->
<?php foreach($materials as $mtr): ?>
<div class="modal fade" id="editModal<?=$mtr['Id'];?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <?= form_open_multipart('master/EditMaterialList'); ?>
            <div class="modal-header">
                <h5 class="modal-title">Edit Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden> 
                <input type="text" class="form-control" id="id" name="id" value="<?=$mtr['Id'];?>" hidden> 
                <div class="row ps-2">
                    <div class="col-4">
                        <label for="material_id" class="form-label">Material ID</label>
                        <input type="text" class="form-control" id="material_id" name="material_id" value="<?=$mtr['Id_material'];?>">
                    </div>
                    <div class="col-4">
                        <label for="material_desc" class="form-label">Material Description</label>
                        <input type="text" class="form-control" id="material_desc" name="material_desc" value="<?=$mtr['Material_desc'];?>">
                    </div>
                    <div class="col-4">
                        <label for="material_type" class="form-label">Material Type</label>
                        <input type="text" class="form-control" id="material_type" name="material_type" value="<?=$mtr['Material_type'];?>">
                    </div>
                </div>
                <div class="row mt-4 ps-2">
                    <div class="col-4">
                        <label for="uom" class="form-label">Uom</label>
                        <input type="text" class="form-control" id="uom" name="uom" value="<?=$mtr['Uom'];?>">
                    </div>
                    <div class="col-4">
                        <label for="family" class="form-label">Family</label>
                        <input type="text" class="form-control" id="family" name="family" value="<?=$mtr['Family'];?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<!-- DELETE MODAL -->
<?php foreach($materials as $mtr): ?>
<div class="modal fade" id="deleteModal<?=$mtr['Id'];?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <?= form_open_multipart('master/DeleteMaterialID'); ?> 
            <div class="modal-header">
                <h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete?</h4>
            </div>
            <div class="modal-body">
                <input type="text" name="id" id="id" value="<?=$mtr['Id'];?>" hidden>
                <input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
                <p><b>Material ID</b>: <?=$mtr['Id_material'];?></p>
                <p><b>Material Description</b>: <?=$mtr['Material_desc'];?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
            </div>
          </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
  $(document).ready(function (){
    $('#material_id').select2()
    let table = $('#table-content').DataTable({
     paging: false,
     info: false,
     searching: false   
    });
  });
  
  function getMaterialList(){
    var Id_material = $('#material_id').val();

    $.ajax({
      url: '<?= base_url('master/getMaterialList'); ?>',
      type: 'post',
      dataType: 'json',
      data: {
        Id_material
      },
      beforeSend: function(){
        var spinner =
        `
        <div class="spinner-container">
          <div class="spinner-grow text-success" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>
          <div class="spinner-grow text-success" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>
          <div class="spinner-grow text-success" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        `;
        $('#data').append(spinner);
      },
      complete: function(res){
        $('.spinner-container').remove();
      },
      success: function (res) {
        if (res.length > 0) {
          let rows = '';
            rows += `
              <tr>
                <td>${res[0].Id_material}</td>
                <td>${res[0].Material_desc}</td>
                <td class="text-center">${res[0].Material_type}</td>
                <td class="text-center">${res[0].Uom}</td>
                <td class="text-center">${res[0].Family}</td>
                <td class="text-center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#editModal${res[0].Id}">
                        <span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal${res[0].Id}">
                        <span class="badge bg-danger"><i class="bi bi-trash"></i></span>
                    </a>
                </td>
              </tr>
            `;

            var htmlContent = `
            <table class="table table-bordered mt-3 px-2" id="table-content">
              <thead>
                <tr>
                  <th class="text-center">Material Part No</th>
                  <th class="text-center">Material Part Name</th>
                  <th class="text-center">Material Type</th>
                  <th class="text-center">Uom</th>
                  <th class="text-center">Family</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                ${rows}
              </tbody>
            </table>
        `;

        // Append the fragment to the table body

        $('#data-table').empty().append(htmlContent);

        let modalEdit = '';
          modalEdit += `
          <div class="modal fade" id="editModal${res[0].Id}" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <?= form_open_multipart('master/EditMaterialList'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden> 
                        <input type="text" class="form-control" id="id" name="id" value="${res[0].Id}" hidden> 
                        <div class="row ps-2">
                            <div class="col-4">
                                <label for="material_id" class="form-label">Material ID</label>
                                <input type="text" class="form-control" id="material_id" name="material_id" value="${res[0].Id_material}">
                            </div>
                            <div class="col-4">
                                <label for="material_desc" class="form-label">Material Description</label>
                                <input type="text" class="form-control" id="material_desc" name="material_desc" value="${res[0].Material_desc}">
                            </div>
                            <div class="col-4">
                                <label for="material_type" class="form-label">Material Type</label>
                                <input type="text" class="form-control" id="material_type" name="material_type" value="${res[0].Material_type}">
                            </div>
                        </div>
                        <div class="row mt-4 ps-2">
                          <div class="col-4">
                              <label for="uom" class="form-label">Uom</label>
                              <input type="text" class="form-control" id="uom" name="uom" value="${res[0].Uom}">
                          </div>
                          <div class="col-4">
                              <label for="family" class="form-label">Family</label>
                              <input type="text" class="form-control" id="family" name="family" value="${res[0].Family}">
                          </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        `;
        $('body').append(modalEdit);

        let modalDelete = '';
          modalDelete += `
          <?= form_open_multipart('master/DeleteMaterialID'); ?>
              <div class="modal fade" id="deleteModal${res[0].Id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete?</h4>
                          </div>
                          <div class="modal-body">
                              <input type="text" name="id" id="id" value="${res[0].Id}" hidden>
                              <input type="text" name="user" id="user" value="<?=$name['username'];?>" hidden>
                              <p><b>Material ID</b>: ${res[0].Id_material}</p>
                              <p><b>Material Description</b>: ${res[0].Material_desc}</p>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                              <button type="submit" class="btn btn-primary" name="delete_user">Confirm</button>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
        `;

        $('body').append(modalDelete);
        } else {
          $('#data').html(`
            <div class="row mt-5">
              <div class="col-md">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%">
                      <i class="bi bi-x-circle me-1"></i>Material Part No not found
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
              </div>
            </div>
          `);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        console.error(xhr.statusText);
      }
    });
  }
</script>

<!-- SWEET ALERT  -->
<?php if ($this->session->flashdata('DUPLICATE_AddMaterialList')): ?>
    <script>
        Swal.fire({
            title: "Warning",
            html: `<?=$this->session->flashdata('DUPLICATE_AddMaterialList');?>`,
            icon: "warning"
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('SUCCESS_AddMaterialList')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: `<?=$this->session->flashdata('SUCCESS_AddMaterialList');?>`,
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('FAILED_AddMaterialList')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('FAILED_AddMaterialList'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('SUCCESS_EditMaterialList')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "<?= $this->session->flashdata('SUCCESS_EditMaterialList'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('FAILED_AddMaterialList')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('FAILED_AddMaterialList'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('SUCCESS_DeleteMaterialID')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Success",
                html: "<?= $this->session->flashdata('SUCCESS_DeleteMaterialID'); ?>",
                icon: "success"
            });
        });
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('FAILED_DeleteMaterialID')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Error",
                html: "<?= $this->session->flashdata('FAILED_DeleteMaterialID'); ?>",
                icon: "error"
            });
        });
    </script>
<?php endif; ?>