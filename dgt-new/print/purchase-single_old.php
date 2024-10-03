<?php $backURL = '../purchases';
if (isset($_GET['t_id']) && $_GET['t_id'] > 0 && isset($_GET['action']) && base64_decode($_GET['secret']) == "powered-by-upsol") {
    require("../connection.php");
    global $connect;
    $id = mysqli_real_escape_string($connect, $_GET['t_id']);
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $_fields = transactionSingle($id);
    if (empty($_fields)) {
        messageNew('danger', $backURL, 'Something is wrong.');
    } ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo 'Purchase_' . my_date(date('Y-m-d')); ?> </title>
        <meta name="description" content="Owner of DGT.llc">
        <meta name="author" content="Asmatullah Abdullah">
        <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
        <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/custom.css" rel="stylesheet">
        <link href="../assets/css/virtual-select.min.css" rel="stylesheet">
        <script src="../assets/fa/fontawesome.js" crossorigin="anonymous"></script>
        <link rel="shortcut icon" href="../assets/images/favicon.jpg" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <!--<style>
            #main {
                background-image: url("bg.png");
                background-position: center;
                background-size: contain;
                background-repeat: no-repeat;
                height: 110%;

            }

            h6 {
                margin-bottom: 0;
            }

            .table-bordered {
                border: 1px solid #000000;
            }
        </style>-->
    </head>

    <body onbeforeprint="togglePrintControls()" onafterprint="togglePrintControls()">
        <div class="bg-light p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2" id="printControls">
            <div class="fs-5 text-uppercase">Print Purchases</div>
            <div class="d-flex gap-1">
                <?= addNew('../purchases', 'Back', 'btn-sm', 'fa-arrow-left'); ?>
                <button class="btn btn-success btn-sm" onclick="downloadAsExcel()">
                    <i class="fa fa-file-excel-o"></i> Excel
                </button>
                <button class="btn btn-primary btn-sm" onclick="downloadAsWord()">
                    <i class="fa fa-file-word-o"></i> Word
                </button>
                <button class="btn btn-warning btn-sm">
                    <i class="fa fa-envelope"></i> Mail
                </button>
                <a onclick="window.print();" href="#." class="btn btn-success btn-sm">
                    <i class="fa fa-print"></i> Print
                </a>
            </div>
        </div>
        <img src="bg-logo.png"
            style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: -99">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <img src="../assets/images/logo.png" alt="logo" class="img-fluid" width="100">
                        <div><b>Branch: </b><?php echo $_fields['branch_id']; ?></div>
                        <div>
                            <div><b>Sr#</b> <?php echo $id; ?><br></div>
                            <b>Country</b> <?php echo $_fields['country']; ?><br>
                        </div>
                        <div class="text-end">
                            <h3 class="fw-bold mb-0">PURCHASE</h3>
                            <div>
                                <b>Date: </b><?php echo my_date($_fields['_date']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-0 mb-2">
                        <div class="col-6">
                            <div><b>Seller A/c # </b><?php echo $_fields['dr_acc']; ?></div>
                            <?php if (!empty($_fields['dr_acc_details'])) {
                                echo '<div><b>Company Details </b>' . nl2br($_fields['dr_acc_details']) . '</div>';
                            } ?>
                        </div>
                        <div class="col-6">
                            <div><b>Purchaser A/c # </b><?php echo $_fields['cr_acc']; ?></div>
                            <?php if (!empty($_fields['cr_acc_details'])) {
                                echo '<div><b>Company Details </b>' . nl2br($_fields['cr_acc_details']) . '</div>';
                            } ?>
                        </div>
                    </div>
                    <?php if (!empty($_fields['sea_road'])) { ?>
                        <div class="border-bottom py-2 row row-cols-md-4 row-cols-3 gy-2">
                            <?php echo '<div class="col-md-12 text-uppercase fs-6"><b>By </b>' . $_fields['sea_road'] . '</div>'; ?>
                            <?php if (!empty($_fields['sea_road_array'])): ?>
                                <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                    <?php if ($key === 'is_loading' || $key === 'is_receiving') continue;
                                    if (($key === 'l_country' || $key === 'l_port' || $key === 'l_date' || $key === 'ctr_name') && $_fields['sea_road_array']['is_loading'][1] == 0) continue;
                                    if (($key === 'r_country' || $key === 'r_port' || $key === 'r_date' || $key === 'arrival_date') && $_fields['sea_road_array']['is_receiving'][1] == 0) continue; ?>
                                    <?php if (is_array($value)): ?>
                                        <div class="col"><b><?php echo $value[0]; ?> </b><?php echo $value[1]; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="col">
                                            <b><?php echo strtoupper($key); ?> </b><?php echo $value; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php /*if (!empty($_fields['sea_road_array'])) {
                                foreach ($_fields['sea_road_array'] as $key => $value) {
                                    if (is_array($value)) { */ ?><!--
                                        <div class="col"><b><?php /*echo $value[0]; */ ?> </b><?php /*echo $value[1]; */ ?></div>
                                    <?php /*} else { */ ?>
                                        <div class="col"><b><?php /*echo strtoupper($key); */ ?> </b><?php /*echo $value; */ ?>
                                        </div>
                                    --><?php /*}
                                }
                            } */ ?>
                            <?php echo isset($_fields['sea_road_report']) ? '<div class="col-md-12"><b>Report </b>' . $_fields['sea_road_report'] . '</div>' : ''; ?>
                        </div>
                    <?php } ?>
                    <?php if (!empty($_fields['items'])) { ?>
                        <table class="table mb-0 table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Goods</th>
                                    <th>SIZE</th>
                                    <th>BRAND</th>
                                    <th>ORIGIN</th>
                                    <th>Qty</th>
                                    <th>Total KGs</th>
                                    <th>Total Qty KGs</th>
                                    <th>Net KGs</th>
                                    <th>Wt.</th>
                                    <th>Total</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                    <th>Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $items = $_fields['items'];
                                $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                                foreach ($items as $details) {
                                    echo '<tr>';
                                    echo '<td>' . $details['sr'] . '</td>';
                                    echo '<td>' . goodsName($details['goods_id']) . '</td>';
                                    echo '<td>' . $details['size'] . '</td>';
                                    echo '<td>' . $details['brand'] . '</td>';
                                    echo '<td>' . $details['origin'] . '</td>';
                                    echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                                    echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                    echo '<td>' . round($details['total_qty_kgs'], 2) . '</td>';
                                    echo '<td>' . round($details['net_kgs'], 2);
                                    echo '<sub>' . $details['divide'] . '</sub>';
                                    echo '</td>';
                                    echo '<td>' . $details['weight'] . '</td>';
                                    echo '<td>' . $details['total'] . '</td>';
                                    echo '<td>' . $details['price'] . '</td>';
                                    echo '<td>' . round($details['amount'], 2);
                                    echo '<sub>' . $details['currency1'] . '</sub>';
                                    echo '</td>';
                                    echo '<td class="text-end">' . round($details['final_amount'], 2);
                                    echo '<sub>' . $details['currency2'] . '</sub>';
                                    echo '</td>';
                                    echo '</tr>';

                                    $qty_no += $details['qty_no'];
                                    $qty_kgs += $details['qty_kgs'];
                                    $total_kgs += $details['total_kgs'];
                                    $total_qty_kgs += $details['total_qty_kgs'];
                                    $net_kgs += $details['net_kgs'];
                                    $total += $details['total'];
                                    $amount += $details['amount'];
                                    $final_amount += $details['final_amount'];
                                } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    <div class="fixed-bottom bg-white">
                        <div class="container-fluid">
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <div class="row">
                                        <div class="col">
                                            <p><b>Buyer Signature</b></p>
                                        </div>
                                        <div class="col">
                                            <p><b>Seller Signature</b></p>
                                        </div>
                                    </div>
                                    <div class="row mb-1 mt-4">
                                        <div class="col">
                                            <div class="border-top border-2 border-dark"></div>
                                        </div>
                                        <div class="col">
                                            <div class="border-top border-2 border-dark"></div>
                                        </div>
                                    </div>
                                    <img src="pdf-footer.png" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/js/jquery-3.7.1.min.js"></script>
        <script src="../assets/bs/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/virtual-select.min.js"></script>

        <script>
            function downloadAsWord() {
                var content = document.documentElement.outerHTML; // Get the full HTML of the current page
                var blob = new Blob(['<html><body>' + content + '</body></html>'], {
                    type: 'application/msword'
                });
                saveAs(blob, 'document.doc'); // Save as document.doc using FileSaver.js
            }

            function downloadAsExcel() {
                // Select the table by its ID
                var table = document.getElementById("purchasesTable");

                // Use SheetJS to convert the table to a worksheet
                var wb = XLSX.utils.book_new(); // Create a new workbook
                var ws = XLSX.utils.table_to_sheet(table); // Convert HTML table to a worksheet

                // Append the worksheet to the workbook
                XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

                // Save the workbook as an Excel file
                XLSX.writeFile(wb, "document.xlsx");
            }

            function togglePrintControls() {
                let printControls = document.getElementById('printControls');

                if (printControls.classList.contains('d-flex')) {
                    printControls.classList.remove('d-flex');
                    printControls.classList.add('d-none');
                } else {
                    printControls.classList.remove('d-none');
                    printControls.classList.add('d-flex');
                }
            }
        </script>
    </body>

    </html>
<?php

} else {
    echo '<script>window.location.href="' . $backURL . '";</script>';
} ?>