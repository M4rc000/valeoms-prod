<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcodes</title>
    <style>
    @page {
        size: A4;
        margin: 10mm;
    }

    body {
        font-family: Arial, sans-serif;
    }

    .print-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .barcode-item {
        width: 45%;
        border: 1px solid black;
        margin-bottom: 10mm;
        padding: 10mm;
        box-sizing: border-box;
        text-align: center;
    }

    .barcode-item .barcode {
        width: 100px;
        height: 100px;
        margin: 0 auto 10mm;
    }

    .valeo-logo {
        width: 50px;
        height: 50px;
        background-image: url('<?php echo base_url("assets/img/valeo_logo.jpg"); ?>');
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
        margin: 0 auto 10mm;
    }

    .barcode-info {
        font-size: 1em;
    }

    @media print {
        .pagebreak {
            page-break-before: always;
        }
    }
    </style>
</head>

<body>
    <div class="print-section">
        <?php foreach ($box_ids as $index => $idBox): ?>
        <div class="barcode-item">
            <div class="valeo-logo"></div>
            <div class="barcode" id="qrcode<?php echo $index; ?>"></div>
            <div class="barcode-info">ID Box:<br>
                <h1 style="font-size:2em;"><?php echo $idBox; ?></h1>
            </div>
        </div>
        <?php if (($index + 1) % 4 == 0): ?>
        <div class="pagebreak"></div>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
    window.onload = function() {
        <?php foreach ($box_ids as $index => $idBox): ?>
        new QRCode(document.getElementById("qrcode<?php echo $index; ?>"), {
            text: "<?php echo $idBox; ?>",
            width: 100,
            height: 100,
            correctLevel: QRCode.CorrectLevel.H
        });
        <?php endforeach; ?>
        window.print();
    }
    </script>
</body>

</html>