<section>
	<div class="row">
		<div class="card info-card" style="height: 600px;">
			<div class="card-body">
                <?php if ($this->session->flashdata('Error') != '') { ?>
                    <?= $this->session->flashdata('Error'); ?>
                <?php } ?>
                <div class="row justify-content-center mt-5 gap-1">
                    <label for="product_id" class="col-sm-3 col-form-label"><b>Product ID</b></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="product_id">
                    </div>
                    <div class="col-sm-3">
                        <button type="submit" class="btn btn-success" onclick="getProduct()">Search</button>
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
    // MENDAPATKAN PRODUCT BERDASARKAN ID
    function getProduct() {
        var productID = $('#product_id').val();
        $.ajax({
            url: '<?= base_url('production/getProduct'); ?>',
            type: 'post',
            dataType: 'json',
            data: {
                productID: productID // Corrected object key-value pair
            },
            success: function(res) {
                if (res.length > 0) {
                    var productId = res[0].Id_fg;
                    var productDescription = res[0].Fg_desc;

                    // Construct HTML content to append
                    var htmlContent = '<p><b>Product ID:</b> ' + productId + '</p>';
                    htmlContent += '<p><b>Product Description:</b> ' + productDescription + '</p>';
                    htmlContent += '<div class="row mt-3 gap-1"><label for="product_id" class="col-sm-3 col-form-label"><b>Qty Production Planning : </b></label><div class="col-sm-2"><input type="text" class="form-control" id="qty"></div><div class="col-sm-2"><button type="button" class="btn btn-primary" onclick="calculateData()" style="background-color: #4154f1">Calculate</button></div></div>';  

                    // Append the HTML content to the div with id "data"
                    $('#data').empty().append(htmlContent);
                } else {
                    // Handle case when product is not found
                    $('#data').html('<div class="alert alert-danger alert-dismissible fade show" role="alert" style="width: 40%"><i class="bi bi-x-circle me-1"></i> Product ID not found<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                }
            },
            // error: function(xhr, ajaxOptions, thrownError) {
            //     // Handle AJAX error
            //     console.error(xhr.statusText);
            // }
        });
    }

    function calculateData(){
        var qty = $('#qty').val();
		$.ajax({
			url: '<?= base_url('production/getProduct'); ?>',
			type: 'post',
			dataType: 'json',
			data: {
				qty
			},
			success: function(res) {

                // Construct HTML content to append
                var htmlContent = '<p><b>Product ID:</b> ' + productId + '</p>';
                htmlContent += 
                `
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" rowspan="2" class="text-center">#</th>
                            <th scope="col" rowspan="2" class="text-center">Product ID</th>
                            <th scope="col" rowspan="2" class="text-center">Material Description</th>
                            <th scope="col" rowspan="2" class="text-center">Material Need</th>
                            <th scope="col" rowspan="2" class="text-center">Stock On Hand</th>
                            <th scope="col" colspan="3" class="text-center">Location 1</th>
                            <th scope="col" colspan="3" class="text-center">Location 2</th>
                            <th scope="col" colspan="3" class="text-center">Location 3</th>
                        </tr>
                        <tr>
                            <th scope="col">SLoc</th>
                            <th scope="col">Qty</th>
                            <th scope="col">UOM</th>
                            <th scope="col">SLoc</th>
                            <th scope="col">Qty</th>
                            <th scope="col">UOM</th>
                            <th scope="col">SLoc</th>
                            <th scope="col">Qty</th>
                            <th scope="col">UOM</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>1001</td>
                            <td>Product A</td>
                            <td>10</td>
                            <td>20</td>
                            <td>Loc1</td>
                            <td>5</td>
                            <td>pcs</td>
                            <td>Loc2</td>
                            <td>8</td>
                            <td>pcs</td>
                            <td>Loc3</td>
                            <td>15</td>
                            <td>pcs</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>1002</td>
                            <td>Product B</td>
                            <td>15</td>
                            <td>25</td>
                            <td>Loc1</td>
                            <td>10</td>
                            <td>pcs</td>
                            <td>Loc2</td>
                            <td>5</td>
                            <td>pcs</td>
                            <td>Loc3</td>
                            <td>20</td>
                            <td>pcs</td>
                        </tr>
                    </tbody>
                </table>
                `;  

                // Append the HTML content to the div with id "data"
                $('#data').empty().append(htmlContent);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError);
			}
		});
    }
</script>