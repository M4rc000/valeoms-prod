<?php 
    $grouped_storage = array();

    // Group data by material_desc
    foreach ($list_storage as $storage) {
        if (!isset($grouped_storage[$storage['material_desc']])) {
            $grouped_storage[$storage['material_desc']] = $storage;
        } else {
            $grouped_storage[$storage['material_desc']]['total_qty'] += $storage['total_qty'];
        }
    }
?>
<link rel="stylesheet" href="<?=base_url('assets');?>/vendor/datatables/datatables.css">
<link rel="stylesheet" href="<?=base_url('assets');?>/vendor/datatables/buttons.dataTables.css">
<section>
	<div class="card">
		<div class="card-body">
            <div class="row">
                <div class="col-md mt-4">
                    <table class="table table-bordered display" id="tbl-storage">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Material Part No</th>
                                <th class="text-center">Material Part Name</th>
                                <th class="text-center">Qty Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $number = 0;
                                foreach ($grouped_storage as $material => $storage):
                                    $number++;
                            ?>
                            <tr>
                                <td class="text-center"><?= $number; ?></td>
                                <td class="text-center"><?= $storage['product_id']; ?></td>
                                <td class="text-center"><?= $storage['material_desc']; ?></td>
                                <td class="text-center"><?= $storage['total_qty']; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</section>

<script src="<?=base_url('assets/');?>/vendor/datatables/dataTables.buttons.js"></script>
<script src="<?=base_url('assets/');?>/vendor/datatables/buttons.dataTables.js"></script>
<script src="<?=base_url('assets/');?>/vendor/datatables/jszip.min.js"></script>
<script src="<?=base_url('assets/');?>/vendor/datatables/pdfmake.min.js"></script>
<script src="<?=base_url('assets/');?>/vendor/datatables/vfs_fonts.js"></script>
<script src="<?=base_url('assets/');?>/vendor/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url('assets/');?>/vendor/datatables/buttons.print.min.js"></script>
<script>
    new DataTable('#tbl-storage', {
        "pageLength": -1,
        layout: {
            topStart: {
                buttons: [
                    {
                        text: '<i class="bx bx-table"></i> Excel',
                        extend: 'excel',
                        title: ''
                    },
                    {
                        text: '<i class="bx bxs-file-pdf"></i> Pdf',
                        title: '',
                        extend: 'pdf',
                        customize: function (doc) {
                            // Set the title at the top of the PDF
                            doc.content.splice(0, 0, {
                                text: 'List Storage',
                                style: 'header'
                            });

                            // Define styles for the header
                            doc.styles.header = {
                                fontSize: 16,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 20, 0, 10] // margin: [left, top, right, bottom]
                            };

                            // Ensure title is centered on the page
                            doc.pageMargins = [40, 60, 40, 40]; // [left, top, right, bottom]
                        }
                    },
                ]
            }
        }
    });
</script>