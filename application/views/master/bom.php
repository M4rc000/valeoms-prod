<style>
	.select2-container {
		z-index: 9999;
	}
  .select2-selection{
    padding-top: 4px !important;
    height: 38px !important;
  }
</style>
<section style="font-family: Nunito;">
	<div class="row">
    <div class="show" style="position: absolute; top: 12%; left: 70%; width: 75%; z-index: 999;">
      <?php if ($this->session->flashdata('SUCCESS') != '') { ?>
        <?= $this->session->flashdata('SUCCESS'); ?>
      <?php } ?>
      <?php if ($this->session->flashdata('ERROR') != '') { ?>
        <?= $this->session->flashdata('ERROR'); ?>
      <?php } ?>
      <?php if ($this->session->flashdata('EDIT') != '') { ?>
        <?= $this->session->flashdata('EDIT'); ?>
      <?php } ?>
      <?php if ($this->session->flashdata('DELETED') != '') { ?>
        <?= $this->session->flashdata('DELETED'); ?>
      <?php } ?>
    </div>
		<div class="col-lg-12">
			<div class="card" style="height: 2500px;">
				<div class="card-body">
					<div class="row mt-3">
						<div class="col-md">
							<!-- Default Tabs -->
							<ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
								<li class="nav-item flex-fill" role="presentation">
									<button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab"
										data-bs-target="#home-justified" type="button" role="tab" aria-controls="home"
										aria-selected="true"><i class="bi bi-file-earmark-ruled-fill me-3" style="color: #012970"></i>
										BOM</button>
								</li>
								<li class="nav-item flex-fill" role="presentation">
									<button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab"
										data-bs-target="#profile-justified" type="button" role="tab" aria-controls="profile"
										aria-selected="false"><i class="bi bi-file-earmark-plus-fill me-2" style="color: #012970"></i> New
										Product FG</button>
								</li>
							</ul>
							<div class="tab-content pt-2" id="myTabjustifiedContent">
								<div class="tab-pane fade show active" id="home-justified" role="tabpanel" aria-labelledby="home-tab">
									<div class="row mt-4 mb-2 ">
										<div class="col-md">
											<div class="col-md-2">
												<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterialBOM"><i
														class="bi bi-journal-plus mx-1 me-2"></i> BOM</button>
											</div>
										</div>
									</div>
									<div class="row justify-content-center mt-4 me-0 ml-0 mb-5">
										<label for="id_product" class="col-md-2 col-form-label">
											<b>Product ID</b>
										</label>
										<div class="col-md-5">
											<input type="text" class="form-control" id="id_product">
										</div>
										<div class="col-md-3">
											<button class="btn btn-success" onclick="getBomList()">Search</button>
										</div>
									</div>
									<hr>
									<div class="row">
										<div id="data"></div>
									</div>
								</div>
								<div class="tab-pane fade" id="profile-justified" role="tabpanel" aria-labelledby="profile-tab">
									Nesciunt totam et. Consequuntur magnam aliquid eos nulla dolor iure eos quia. Accusantium distinctio
									omnis et atque fugiat. Itaque doloremque aliquid sint quasi quia distinctio similique. Voluptate nihil
									recusandae mollitia dolores. Ut laboriosam voluptatum dicta.
								</div>
							</div><!-- End Default Tabs -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- ADD Material MODAL -->
<div class="modal fade" id="addMaterialBOM" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?= form_open_multipart('master/AddMaterialBom'); ?>
			<div class="modal-header">
				<h5 class="modal-title">Add Material's BOM</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- GET USER -->
				<input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden>
				<div class="row ps-2 mb-3">
					<div class="col-md-4">
						<label for="product_id" class="form-label">Product ID</label>
						<select class="form-select" id="product_id" name="product_id" required>
							<option value="" selected>Select Product ID</option>
							<?php foreach($bom_distint as $bd): ?>
							<option value="<?=$bd['Id_fg']?>"><?=$bd['Id_fg'];?></option>
							<?php endforeach; ?>
						</select>
					</div>
          <div class="col-4">
            <label for="fg_desc" class="form-label">Product Description</label>
            <input type="text" class="form-control" id="fg_desc" name="fg_desc" readonly required>
          </div>
					<div class="col-md-4">
						<label for="material_id" class="form-label">Material ID</label>
						<select class="form-select" id="material_id" name="material_id" required>
							<option value="" selected>Select Material ID</option>
							<?php foreach($materials as $mtr): ?>
							<option value="<?=$mtr['Id_material']?>"><?=$mtr['Id_material'];?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="row ps-2 mt-3">
          <div class="col-4">
            <label for="material_desc" class="form-label">Material Description</label>
            <input type="text" class="form-control" id="material_desc" name="material_desc" readonly required>
          </div>
          <div class="col-3">
            <label for="material_type" class="form-label">Material Type</label>
            <input type="text" class="form-control" id="material_type" name="material_type" readonly>
          </div>
          <div class="col-3">
            <label for="qty" class="form-label">Qty</label>
            <input type="text" class="form-control" id="qty" name="qty" required>
          </div>
					<div class="col-2">
						<label for="uom" class="form-label">Uom</label>
						<input type="text" class="form-control" id="uom" name="uom" readonly>
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
	$('#addMaterialBOM').on('shown.bs.modal', function () {
		$('#material_id').select2({
			dropdownParent: $('#addMaterialBOM')
		});

		$('#product_id').select2({
			dropdownParent: $('#addMaterialBOM')
		});

    $('#product_id').on('change', function() {
      var productId = $(this).val();
      
      $.ajax({
        url: '<?= base_url('master/getProductDesc'); ?>',
        type: 'post',
        dataType: 'json',
        data: {
          productId
        },
        success: function (res){
          var productDesc = res[0].Fg_desc;
          $('#fg_desc').val(productDesc);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // Handle AJAX error
          console.error(xhr.statusText);
        }
      });
    });

    $('#material_id').on('change', function() {
      var materialID = $(this).val();
      
      $.ajax({
        url: '<?= base_url('master/getMaterialDesc'); ?>',
        type: 'post',
        dataType: 'json',
        data: {
          materialID
        },
        success: function (res){
          var materialDesc = res[0].Material_desc;
          var materialType = res[0].Material_type;
          var uom = res[0].Uom;

          $('#material_desc').val(materialDesc);
          $('#material_type').val(materialType);
          $('#uom').val(uom);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // Handle AJAX error
          console.error(xhr.statusText);
        }
      });
    });
	});

	function getBomList() {
		var Id_product = $('#id_product').val();

		$.ajax({
			url: '<?= base_url('master/getBomList'); ?>',
			type: 'post',
			dataType: 'json',
			data: {
				Id_product
			},
			success: function (res) {
				if (res.length > 0) {
					let rows = '';
					for (let number = 0; number < res.length; number++) {
						rows += `
                <tr>
                  <td>${res[number].Id_material}</td>
                  <td>${res[number].Material_desc}</td>
                  <td class="text-center">${res[number].Material_type}</td>
                  <td class="text-center">${res[number].Qty}</td>
                  <td class="text-center">${res[number].Uom}</td>
                  <td class="text-center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#editModal${res[number].Id_material}">
                      <span class="badge bg-warning"><i class="bi bi-pencil-square"></i></span>
                    </a>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal${res[number].Id_material}">
                      <span class="badge bg-danger"><i class="bi bi-trash"></i></span>
                    </a>
                  </td>
                </tr>
              `;
					}

					// Construct HTML content to append
					var htmlContent =
						`<table class="table datatable table-bordered mt-3" id=table-content>
              <thead>
                <tr>
                  <th class="text-center">Material ID</th>
                  <th class="text-center">Material Description</th>
                  <th class="text-center">Material Type</th>
                  <th class="text-center">Qty</th>
                  <th class="text-center">Uom</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                ${rows}
              </tbody>
            </table>`;

					// Append the HTML content to the div with id "data"
					$('#data').empty().append(htmlContent);
					let table = new DataTable('#table-content');

          // EDIT MODAL
					let modalEdit = '';
					for (let number = 0; number < res.length; number++) {
						modalEdit += `
              <div class="modal fade" id="editModal${res[number].Id_material}" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <?= form_open_multipart('master/EditBomMaterial'); ?>
                              <div class="modal-header">
                                  <h5 class="modal-title">Edit Menu</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  <!-- GET USER -->
                                  <input type="text" class="form-control" id="user" name="user" value="<?=$name['username'];?>" hidden> 
                                  <input type="text" class="form-control" id="id" name="id" value="${res[number].Id_bom}" hidden> 
                                  <input type="text" class="form-control" id="id_fg" name="id_fg" value="${res[number].Id_fg}" hidden> 
                                  <div class="row ps-2">
                                      <div class="col-4">
                                          <label for="material_id" class="form-label">Material ID</label>
                                          <input type="text" class="form-control" id="material_id" name="material_id" value="${res[number].Id_material}">
                                      </div>
                                      <div class="col-4">
                                          <label for="material_desc" class="form-label">Material Description</label>
                                          <input type="text" class="form-control" id="material_desc" name="material_desc" value="${res[number].Material_desc}">
                                      </div>
                                      <div class="col-4">
                                          <label for="material_type" class="form-label">Material Type</label>
                                          <input type="text" class="form-control" id="material_type" name="material_type" value="${res[number].Material_type}">
                                      </div>
                                  </div>
                                  <div class="row mt-4 ps-2">
                                      <div class="col-4">
                                          <label for="qty" class="form-label">Qty</label>
                                          <input type="text" class="form-control" id="qty" name="qty" value="${res[number].Qty}">
                                      </div>
                                      <div class="col-4">
                                          <label for="uom" class="form-label">Uom</label>
                                          <input type="text" class="form-control" id="uom" name="uom" value="${res[number].Uom}">
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
					}

					// Append the modal markup to the body
					$('body').append(modalEdit);


          // DELETE MODAL
					for (let number = 0; number < res.length; number++) {
						var modalDelete =
							`
              <?= form_open_multipart('master/DeleteMaterialBom'); ?>
                <div class="modal fade" id="deleteModal${res[number].Id_material}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title pb-0 mb-0" id="exampleModalLabel">Confirm to delete ?</h4>
                    </div>
                    <div class="modal-body">
                      <input type="text" name="id" id="id" value="` + res[number].Id_bom + `" style="display: none;">
                      <p><b>Material ID</b> : ` + res[number].Id_material + `</p>
                      <p><b>Material Description</b> : ` + res[number].Material_desc + `</p>
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
					}

					$('body').append(modalDelete);
				} else {
					// Handle case when product is not found
					$('#data').html(`
              <div class="row mt-5">
                <div class="col-md">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%">
                    <i class="bi bi-x-circle me-1"></i>Product ID not found
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>
              </div>
            `);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				// Handle AJAX error
				console.error(xhr.statusText);
			}
		});
	}
</script>