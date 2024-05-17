<style>
    .kanban-card {
        border: 1px solid #F0F3FF;
        padding: 20px;
        width: 700px;
        margin: 20px auto;
        position: relative;
    }
    .kanban-card h3 {
        text-align: center;
        margin-bottom: 20px;
        text-decoration: underline;
    }
    .kanban-card .logo {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 50px;
        height: 30px;
    }
    .kanban-card ul {
        list-style: none;
        padding: 0;
    }
    .kanban-card ul li {
        display: flex;
        align-items: center;
        padding: 5px 0;
    }
    .kanban-card ul li p {
        margin: 0;
    }
</style>

<section>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row mt-5">
                    <div class="col-4">
                      <label for="product_id" class="form-label"><b>Product ID</b></label>
                      <input type="text" class="form-control" id="product_id" name="product_id" required>
                    </div>
                    <div class="col-4">
                      <label for="qty" class="form-label"><b>Qty</b></label>
                      <input type="text" class="form-control" id="qty" name="qty" required>
                    </div>
                    <div class="col-4">
                      <label for="production_planning" class="form-label"><b>Production Planning</b></label>
                      <input type="text" class="form-control" id="production_planning" name="production_planning" required>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="offset-9 col-3">
                        <button class="btn btn-success" style="width: 150px;" onclick="generateBarcode()"><i class="bx bxs-printer me-2"></i>Print</button>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="preview mt-3 text-center"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script>
    function generateBarcode() {
        var productId = $('#product_id').val();
        var qty = $('#qty').val();
        var production_planning = $('#production_planning').val();

        var htmlContent = 
        `
            <div class="kanban-card">
                <img src="<?=base_url('assets');?>/img/valeo.png" alt="Logo" class="logo">
                <h3>KANBAN CARD</h3>
                <div class="row mt-5 me-0">
                    <div class="col-md-8" style="font-size: 14px">
                        <ul>
                            <li>
                                <p><b>Product ID :</b> ${productId}</p>
                            </li>
                            <li>
                                <p><b>Product Qty :</b> ${qty}</p>
                            </li>
                            <li>
                                <p><b>Product Plan :</b> ${production_planning}</p>
                            </li>
                        </ul>
                    </div>  
                    <div class="col-md-4 text-center">
                        <div class="ms-5" id="preview-barcode"></div>
                    </div>
                </div>
            </div>
        `;
        
        // Empty the preview element and append the new content
        $('.preview').empty().append(htmlContent);
        
        // Generate the QR code after the element is in the DOM
        var qrcode = new QRCode(document.getElementById("preview-barcode"), {
            text: "<?=base_url('warehouse/')?>",
            width: 150,
            height: 150,
            correctLevel: QRCode.CorrectLevel.H
        });
    }
    
    
</script>

