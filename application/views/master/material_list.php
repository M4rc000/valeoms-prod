  <section class="section">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md mt-3">
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
            <div class="row justify-content-center mt-4 me-0 ml-0">
              <label for="inputText" class="col-md-2 col-form-label">
                <b>Material ID</b>
              </label>
              <div class="col-md-5">
                <input type="text" class="form-control" id="id_material">
              </div>
              <div class="col-md-3">
                <button class="btn btn-success" onclick="getMaterialList()">Search</button>
              </div>
              
            </div> 
            <div class="row">
              <div id="data"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

<script>
  function getMaterialList(){
    var Id_material = $('#id_material').val();

    $.ajax({
      url: '<?= base_url('master/getMaterialList'); ?>',
      type: 'post',
      dataType: 'json',
      data: {
        Id_material 
      },
      success: function(res) {
          if (res.length > 0) {
            var id = res[0].Id;
            var MaterialID = res[0].Id_material;
            var Material_desc = res[0].Material_desc;
            var Material_type = res[0].Material_type;
            var Uom = res[0].Uom;
            var Family = res[0].Family;

            // Construct HTML content to append
            var htmlContent = 
            `<table class="table datatable mt-5">
              <thead>
                <tr>
                  <th>Reference No</th>
                  <th>Material Description</th>
                  <th>Material Type</th>
                  <th>Uom</th>
                  <th>Family</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>` + MaterialID + `</td>
                  <td>` + Material_desc + `</td>
                  <td>` + Material_type + `</td>
                  <td>` + Uom + `</td>
                  <td>` + Family + `</td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#editModal`+ MaterialID +`">
                      <span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal`+ MaterialID +`">
                      <span class="badge bg-danger"><i class="bi bi-trash"></i></span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>`;

            // Append the HTML content to the div with id "data"
            $('#data').empty().append(htmlContent);

            var modalEdit = 
            `<div class="modal fade" id="editModal` + MaterialID + `" tabindex="-1">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <?= form_open_multipart('master/EditMaterialList'); ?>
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Menu</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <!-- GET USER -->
                      <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden> 
                      <input type="text" class="form-control" id="id" name="id" value="`+ id +`" hidden> 
                      <div class="row ps-2">
                        <div class="col-4">
                          <label for="material_id" class="form-label">Material ID</label>
                          <input type="text" class="form-control" id="material_id" name="material_id" value="`+ MaterialID +`">
                        </div>
                        <div class="col-4">
                          <label for="material_desc" class="form-label">Material Description</label>
                          <input type="text" class="form-control" id="material_desc" name="material_desc" value="`+ Material_desc +`">
                        </div>
                        <div class="col-4">
                          <label for="material_type" class="form-label">Material Type</label>
                          <input type="text" class="form-control" id="material_type" name="material_type" value="`+ Material_type +`">
                        </div>
                      </div>
                      <div class="row mt-4 ps-2">
                      <div class="col-4">
                        <label for="uom" class="form-label">Uom</label>
                        <input type="text" class="form-control" id="uom" name="uom" value="`+ Uom +`">
                      </div>
                        <div class="col-4">
                          <label for="family" class="form-label">Family</label>
                          <input type="text" class="form-control" id="family" name="family" value="`+ Family +`">
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
            </div>`;

            // Append the modal markup to the body
            $('body').append(modalEdit);


            var modalDelete = 
            `
            <?= form_open_multipart('master/DeleteMaterialID'); ?>
              <div class="modal fade" id="deleteModal`+ MaterialID +`" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
                  </div>
                  <div class="modal-body">
                    <input type="text" name="id" id="id" value="`+ id +`" style="display: none;">
                    <p><b>Material ID</b> : `+ MaterialID +`</p>
                    <p><b>Material Description</b> : `+ Material_desc +`</p>
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
              // Handle case when product is not found
              $('#data').html(`
              <div class="row mt-5">
                <div class="col-md">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%">
                    <i class="bi bi-x-circle me-1"></i> Material ID not found
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>
              </div>
            `);
          }
      },
      error: function(xhr, ajaxOptions, thrownError) {
          // Handle AJAX error
          console.error(xhr.statusText);
      }
    });
  }

  function populateModal(MaterialID, Material_desc, Material_type, Uom, Family) {
    $('#id').val(MaterialID);
    $('#menu').val(Material_desc);
  }
</script>


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