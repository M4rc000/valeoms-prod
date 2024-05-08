<section>
	<div class="row">
		<div class="card info-card" style="height: 600px;">
			<div class="card-body">
                <div class="row justify-content-center mt-5 gap-1">
                    <label for="product_id" class="col-sm-3 col-form-label"><b>Product ID</b></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="product_id">
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-success" onclick="getData()">Search</button>
                    </div>
                </div>
                <div class="row mt-5 ms-4">
                    <div class="col-md">
                        <div id="data">
                        </div>
                    </div>
                </div>
                <div class="row mt-5 ms-4">
                    <div class="col-md">
                        <div id="data-table">
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</div>
</section>


<script>
    function getData() {
        var product_id = $('#product_id').val();

        $.ajax({
            url: '<?=base_url();?>production/',
            type: 'POST',
            data: {
                product_id 
            },
            beforeSend: function () {
                $('#data').html(
                    "<b>Product Description :  </b>"+ product_id +"<br><br><div class='row mb-3'><label for='inputText' class='col-sm-3 col-form-label'>Qty Production Planning</label><div class='col-sm-2'><input type='text' class='form-control' id='qty'></div><div class='col-sm-4'><button type='submit' class='btn btn-success' onclick='getCalculate()'>Calculate</button></div></div>"
                );
            },
            success: function (data) {
                console.log(product_id);
            }
        });
    }

    function getCalculate() {
        var qty = $('#qty').val();

        $.ajax({
            url: '<?=base_url();?>production/',
            type: 'POST',
            data: {
                qty 
            },
            beforeSend: function () {
                $('#data-table').html(
                    '<table class="table table-bordered"><thead><tr><th scope="col" rowspan="2" class="text-center">#</th><th scope="col" rowspan="2" class="text-center">Product ID</th><th scope="col" rowspan="2" class="text-center">Material Description</th><th scope="col" rowspan="2" class="text-center">Material Need</th><th scope="col" rowspan="2" class="text-center">Stock On Hand</th><th scope="col" colspan="3" class="text-center">Location 1</th><th scope="col" colspan="3" class="text-center">Location 2</th><th scope="col" colspan="3" class="text-center">Location 3</th></tr><tr><th scope="col">SLoc</th><th scope="col">Qty</th><th scope="col">UOM</th><th scope="col">SLoc</th> <th scope="col">Qty</th> <th scope="col">UOM</th> <th scope="col">SLoc</th><th scope="col">Qty</th> <th scope="col">UOM</th> </tr></thead><tbody><tr><th scope="row">1</th><td>1001</td><td>Product A</td><td>10</td><td>20</td><td>Loc1</td><td>5</td><td>pcs</td><td>Loc2</td><td>8</td><td>pcs</td><td>Loc3</td><td>15</td><td>pcs</td></tr><tr><th scope="row">2</th><td>1002</td><td>Product B</td><td>15</td><td>25</td><td>Loc1</td><td>10</td><td>pcs</td><td>Loc2</td><td>5</td><td>pcs</td><td>Loc3</td><td>20</td><td>pcs</td></tr></tbody></table>'
                );
            },
            success: function (data) {
                console.log(qty);
            }
        });
    }
</script>