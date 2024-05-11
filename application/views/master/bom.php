<section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <table class="table datatable table-bordered mt-3">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">FG Part</th>
                    <th scope="col">Reference No</th>
                    <th scope="col">Material Description</th>
                    <th scope="col">Material Type</th>
                    <th scope="col">Qty</th>
                    <th scope="col">UOM</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                    <?php $number = 0; foreach($bom as $b): $number++ ?>
                        <tr>
                            <td scope="row" class="text-center"><?=$number;?></td>
                            <td scope="row" class="text-center"><?= $b['Id_fg']; ?></td>
                            <td scope="row" class="text-center"><?= $b['Id_material']; ?></td>
                            <td scope="row" class="text-center"><?= $b['Material_desc']; ?></td>
                            <td scope="row" class="text-center"><?= $b['Material_type']; ?></td>
                            <td scope="row" class="text-center"><?= $b['Qty']; ?></td>
                            <td scope="row" class="text-center"><?= $b['Uom']; ?></td>
                            <td>
                                <div class="col-md text-center">
                                    <button class="btn btn-warning">
                                        <i class="bx bx-pen" style="color: white;"></i>
                                    </button>
                                </div>
                                <div class="col-md mt-1 text-center">
                                    <button class="btn btn-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
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