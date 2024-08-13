<style>
	.select2-container {
		z-index: 9999;
	}

	.select2-selection {
		padding-top: 4px !important;
		height: 38px !important;
	}
</style>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card" style="height: 400px">
        <div class="card-body">
          <div class="row">
            <div class="col-md-2 mt-3 mb-4">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                New Material
              </button>
            </div>
          </div>
          <?php if ($this->session->flashdata('Error') != '') { ?>
            <?= $this->session->flashdata('Error'); ?>
          <?php } ?>
          <?php if ($this->session->flashdata('EDIT') != '') { ?>
            <?= $this->session->flashdata('EDIT'); ?>
          <?php } ?>
          <?php if ($this->session->flashdata('DELETED') != '') { ?>
            <?= $this->session->flashdata('DELETED'); ?>
          <?php } ?>
          <div class="row mt-3 mb-3 justify-content-center">
            <div class="col-sm-4">
              <select class="form-select" id="material_id" name="material_id" required>
                <option value="">Select Material Part No</option>
                <?php foreach($materials as $mtr): ?>
                <option value="<?=$mtr['Id_material']?>"><?=$mtr['Id_material']?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-2">
              <button class="btn btn-success" type="submit" onclick="getMaterialList()">
                Search
              </button>
            </div>
          </div>
          <div class="row mt-3">
            <div id="data"></div>
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
			<input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden> 
			<div class="row ps-2">
				<div class="col-4">
					<label for="material_id" class="form-label">Material ID</label>
					<input type="text" class="form-control" id="material_id" name="material_id" required>
				</div>
				<div class="col-4">
					<label for="material_desc" class="form-label">Material Description</label>
					<input type="text" class="form-control" id="material_desc" name="material_desc" required>
				</div>
				<div class="col-4">
					<label for="material_type" class="form-label">Material Type</label>
					<input type="text" class="form-control" id="material_type" name="material_type" required>
				</div>
			</div>
			<div class="row ps-2 mt-3">
				<div class="col-4">
					<label for="uom" class="form-label">Uom</label>
					<input type="text" class="form-control" id="uom" name="uom">
				</div>
				<div class="col-4">
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

<script>
  $(document).ready(function (){
    $('#material_id').select2()
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
          console.log(res[0].Id_material);
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
                ${rows}
              </tbody>
            </table>
        `;

        // Append the fragment to the table body

        $('#data').empty().append(htmlContent);
        new DataTable('#table-content');

        let modalEdit = '';
          modalEdit += `
          <div class="modal fade" id="editModal${res[0].Id}" tabindex="-1">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <?= form_open_multipart('master/EditMaterialList'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Menu</h5>
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
          <?= form_open_multipart('master/deleteMaterialBom'); ?>
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