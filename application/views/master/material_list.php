<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table datatable table-bordered mt-2">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Reference No</th>
                    <th scope="col">Material Description</th>
                    <th scope="col">Material Type</th>
                    <th scope="col">UOM</th>
                    <th scope="col">Family</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    <?php $number = 0; foreach($material_list as $ml): $number++ ?>
                        <tr>
                            <td scope="row" class="text-center"><?=$number;?></td>
                            <td scope="row" class="text-center"><?= $ml['Id_material']; ?></td>
                            <td scope="row" class="text-center"><?= $ml['Material_desc']; ?></td>
                            <td scope="row" class="text-center"><?= $ml['Material_type']; ?></td>
                            <td scope="row" class="text-center"><?= $ml['Uom']; ?></td>
                            <td scope="row" class="text-center"><?= $ml['Family']; ?></td>
                            <td>
                                <div class="row" style="gap: 0">
                                    <div class="col-md text-center">
                                        <button class="btn btn-warning">
                                            <i class="bx bx-pen" style="color: white;"></i>
                                        </button>
                                    </div>
                                    <div class="col-md text-center">
                                        <button class="btn btn-danger">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </section>