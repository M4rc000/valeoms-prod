<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-size: 14px;
            margin: 10px
        }
        .container {
            text-align: left;
        }
        .container img {
            width: 80px;
            height: 65px;
            display: inline-block;
            vertical-align: middle;
        }
        .container h1 {
            display: inline-block;
            vertical-align: middle;
            margin-left: 10px; /* Adjust margin as needed */
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="<?= base_url(). 'assets/img/valeo.png'; ?>" alt="Logo">
        <h1>Production Request</h1>
        <hr>
    </div>
    <div class="info">
        <table style="width: 100%;">
            <tr>
                <td style="width: 14%;">No Request</td>
                <td style="width: 2%;">:</td>
                <td style="width: 30%;"><?= $header->Id_request ?></td>
            </tr>
            <tr>
                <td>Material ID</td>
                <td>:</td>
                <td><?= $header->Id_material ?></td>
            </tr>
            <tr>
                <td>Material Description</td>
                <td>:</td>
                <td><?= $header->Material_desc ?></td>
            </tr>
            <tr>
                <td>Material Need</td>
                <td>:</td>
                <td><?= $header->Material_need ?></td>

            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><?= $header->status == 1 ? 'APPROVED' : 'REJECTED' ?></td>
            </tr>
        </table>
    </div>
    <br><br>
    <div>
    <table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="width: 5%; border: 1px solid black;">No</th>
            <th style="width: 15%; border: 1px solid black;">Sloc</th>
            <th style="width: 15%; border: 1px solid black;">No Box</th>
            <th style="width: 10%; border: 1px solid black;">Qty On Box</th>
            <th style="width: 10%; border: 1px solid black;">Qty Unpack</th>
           
        </tr>
    </thead>
    <tbody>
    <?php
        $no = 1;
        foreach ($detail as $v) { ?>
        <tr>
            <td style="border: 1px solid black; padding: 5px; text-align: center;"><?= $no; ?></td>
            <td style="border: 1px solid black; padding: 5px;"><?= $v['sloc_name']?></td>
            <td style="border: 1px solid black; padding: 5px;"><?= $v['box_name']?></td>
            <td style="border: 1px solid black; padding: 5px; text-align: center;"><?= $v['qty_on_box']?></td>
            <td style="border: 1px solid black; padding: 5px; text-align: center;"><?= $v['qty_unpack']?></td>
  
        </tr>
        <?php 
            $no++; 
            }?>
    </tbody>
</table>
    </div>
</body>
</html>
