<form id="editForm" action="<?= base_url('warehouse/editItemMaterial'); ?>" method="post">
    <input type="hidden" id="id_box_detail" name="id_box_detail" value="<?= $material['id']; ?>">
    <div class="row ps-2">
        <div class="col-6">
            <label for="reference_number" class="form-label">Material Part Number</label>
            <input type="text" class="form-control" id="reference_number<?= $material['id']; ?>" name="reference_number"
                value="<?= $material['id_material']; ?>" required>
        </div>
        <div class="col-6 mb-3">
            <label for="material" class="form-label">Material</label>
            <input type="text" class="form-control" id="material<?= $material['id']; ?>" name="material"
                value="<?= $material['material_desc']; ?>" required>
        </div>
        <div class="col-6 mb-3">
            <label for="uom" class="form-label">UOM</label>
            <input type="text" class="form-control" id="uom<?= $material['id']; ?>" name="uom"
                value="<?= $material['uom']; ?>" required>
        </div>
        <div class="col-6 mb-3">
            <label for="qty" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="qty<?= $material['id']; ?>" name="qty"
                value="<?= $material['qty']; ?>" required>
        </div>
    </div>
</form>