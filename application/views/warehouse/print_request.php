<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Request</title>
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
        margin-left: 10px;
        /* Adjust margin as needed */
    }
    </style>
</head>

<body>
    <div class="container">
        <img src="<?= base_url() . 'assets/img/valeo.png'; ?>" alt="Logo">
        <h1>Production Request</h1>
        <hr>
    </div>
    <div class="info">
        <table style="width: 100%;">
            <tr>
                <td>Production Plan</td>
                <td>:</td>
                <td><?= $header->Production_plan ?></td>
            </tr>
            <tr>
                <td>Product Description</td>
                <td>:</td>
                <td><?= $header->Fg_desc ?></td>
            </tr>
            <tr>
                <td>Product Quantity</td>
                <td>:</td>
                <td><?= $header->Production_plan_qty ?></td>

            </tr>
            <tr>
                <td>Production Planning Date</td>
                <td>:</td>
                <td>
                    <?php 
                        $date = new DateTime($header->Crtdt);
                        echo $date->format('l, d F Y'); 
                    ?>
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><?= $header->status ?></td>
            </tr>

        </table>
    </div>
    <br><br>
    <div>
        <table border="1" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 5%; border: 1px solid black;">No</th>
                    <th style="width: 15%; border: 1px solid black;">Request No</th>
                    <th style="width: 15%; border: 1px solid black;">ID Material</th>
                    <th style="width: 30%; border: 1px solid black;">Material Desc</th>
                    <th style="width: 10%; border: 1px solid black;">Material Need</th>
                    <th style="width: 10%; border: 1px solid black;">Material Request</th>
                    <th style="width: 15%; border: 1px solid black;">Sloc</th>
                    <th style="width: 15%; border: 1px solid black;">No Box</th>
                </tr>
            </thead>
            <tbody>
                <?php
				$no = 1;
                // var_dump($detail);
                // die;
				foreach ($detail as $v) { ?>
                <tr>
                    <td style="border: 1px solid black; padding: 5px; text-align: center;"><?= $no; ?></td>
                    <td style="border: 1px solid black; padding: 5px;"><?= $v['Id_request'] ?></td>
                    <td style="border: 1px solid black; padding: 5px;"><?= $v['Id_material'] ?></td>
                    <td style="border: 1px solid black; padding: 5px;"><?= $v['Material_desc'] ?></td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center;"><?= $v['Material_need'] ?>
                    </td>
                    <td style="border: 1px solid black; padding: 5px; text-align: center;"><?= $v['Qty'] ?></td>
                    <td style="border: 1px solid black; padding: 5px;"><?= $v['sloc_name'] ?></td>
                    <td style="border: 1px solid black; padding: 5px;"><?= $v['no_box'] ?></td>
                </tr>
                <?php
					$no++;
				} ?>
            </tbody>
        </table>
    </div>
</body>

</html>