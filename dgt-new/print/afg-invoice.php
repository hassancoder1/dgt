<?php $backURL = '../afghan-invoices';

if (!empty($_GET['id']) && isset($_GET['secret']) && isset($_GET['type'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $id = mysqli_real_escape_string($connect, base64_decode($_GET['id']));
        $type = mysqli_real_escape_string($connect, base64_decode($_GET['type']));
        $allowed_types = array('afg', 'draft');
        $info_types = array(
            'afg' => array('afghan-invoices'),
            'draft' => array('draft-invoices')
        );
        $backURL .= '?view=1&id=' . $id;
        if (in_array($type, $allowed_types)) {
            $rQuery = mysqli_query($connect, "SELECT * FROM `afg_invs` WHERE id = '$id'");
            if (mysqli_num_rows($rQuery) > 0) {
                $record = mysqli_fetch_assoc($rQuery);
                $json_data = json_decode($record['json_data']); ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <title><?php echo 'Ledger_' . my_date(date('Y-m-d')); ?> </title>
                    <meta name="description" content="Owner of DGT.llc">
                    <meta name="author" content="Asmatullah Abdullah">
                    <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
                    <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
                    <link href="../assets/css/custom.css" rel="stylesheet">
                    <!--<link href="../assets/css/virtual-select.min.css" rel="stylesheet">-->
                    <link href="../assets/fa/css/fontawesome.css" rel="stylesheet"/>
                    <link href="../assets/fa/css/brands.css" rel="stylesheet"/>
                    <link href="../assets/fa/css/solid.css" rel="stylesheet"/>
                    <link rel="shortcut icon" href="../assets/images/favicon.jpg"/>
                    <style>
                        .table thead tr th {
                            border: 3px solid;
                        }

                        .table tbody tr td {
                            border-left: 3px solid;
                            border-right: 3px solid;
                        }

                        .table tbody tr:last-child td {
                            border-bottom: 3px solid;
                        }
                    </style>
                </head>
                <body>
                <!--<img src="bg-logo.png" style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">-->

                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-lg-9">
                            <div class="row align-items-center">
                                <div class="col-lg-12 text-center fw-bold">
                                    <h3 class="fw-bold mb-0">INVOICE</h3>
                                    <h4 class="fw-bold">******************</h4>
                                </div>
                                <div class="col-7 flex-column align-items-center">
                                    <div class="border border-dark border-2 p-2" style="min-height: 220px">
                                        <div><?php echo '<b>FROM:</b> ' . nl2br($record['_from']); ?></div>
                                        <div><?php echo '<b>THIRD PARTY:</b><br> ' . nl2br($record['third_party']); ?></div>
                                        <div style="border-top: 2px dashed"><?php echo '<b>TO:</b> ' . nl2br($record['_to']); ?></div>
                                    </div>
                                </div>
                                <div class="col-5 flex-column align-items-center">
                                    <?php echo '<b>No: ' . $json_data->no1 . '</b><br>';
                                    echo '<b>Date: ' . my_date($record['_date']) . '</b>'; ?>
                                    <div class="border border-dark border-2 mt-2">
                                        <div
                                                class="px-2 border-bottom border-dark border-2 bold"><?php echo $json_data->afg; ?></div>
                                        <div
                                                class="px-2 border-bottom border-dark border-2 bold d-flex justify-content-between">
                                            <div>No: <?php echo $json_data->no2; ?></div>
                                            <div>Date: <?php echo $json_data->_date2; ?></div>
                                        </div>
                                        <div class="px-2 border-bottom border-dark border-2 bold">
                                            <div class="text-center">Terms of Payment</div>
                                            <?php echo $record['terms']; ?>
                                        </div>
                                        <div
                                                class="px-2 border-bottom border-dark border-2 bold d-flex justify-content-between">
                                            <div>Letter Of Credit No: <?php echo $json_data->letter; ?></div>
                                            <div><?php echo $json_data->collection; ?></div>
                                        </div>
                                        <div class="px-2 bold">
                                            Through: <br>
                                            <?php echo $record['through']; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-borderless mt-3" style="min-height: 610px;">
                                <thead>
                                <tr>
                                    <th style="width: 3%">No</th>
                                    <th style="width: 17%">Quantity</th>
                                    <th>Description of Goods</th>
                                    <th style="width: 15%">Unit Price USD</th>
                                    <th style="width: 15%">Total Price USD</th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="py-2"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $x = $total = 0;
                                $ddd = fetch('afg_inv_details', array('parent_id' => $id));
                                while ($temp = mysqli_fetch_assoc($ddd)) {
                                    ++$x;
                                    $temp_json = json_decode($temp['json_data']);
                                    echo '<tr class="">';
                                    echo '<td>' . $x . '</td>';
                                    echo '<td>';
                                    echo $temp_json->qty1 . ' ' . $temp_json->qty2 . ' ' . $temp_json->qty3 . ' ' . $temp_json->kgs . '</td>';
                                    echo '<td>' . $temp_json->goods;
                                    echo ' (' . $temp_json->kgs . ' ' . $temp_json->qty3 . ')';
                                    echo '</td>';
                                    echo '<td class="text-center">' . $temp_json->unit_price . '</td>';
                                    echo '<td class="text-center">' . $temp_json->total_price . '</td>';
                                    echo '</tr>';
                                    $total += $temp_json->total_price;
                                } ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center bold">
                                        <hr class="text-white">
                                        Total Amount Say: USD: <br>
                                        <?php echo AmountInWords(round($total)); ?>
                                        <br>(Origin Afghanistan)
                                        <div class="border border-dark border-2 p-2 text-start mb-5">
                                            Reg No: ................................... <br>
                                            Fee No: ................................... <br>
                                            Received the sum of: ......................... <br>
                                            Date: ................................... <br><br><br>
                                            Signature: ................................... <br>
                                        </div>
                                        <div class="fw-normal text-start">
                                            <?php if (!empty($record['json_final'])) {
                                                $jjj = json_decode($record['json_final']);
                                                echo '<b>Date: </b>' . $jjj->t_date . '<br>';
                                                echo '<b>TT. Amount: </b>' . $jjj->tt_amount . '<br>';
                                                echo '<b>Final Amount: </b>' . $jjj->final_amount . ' ' . $jjj->curr . '<br>';
                                                echo '<b>Bank Details: </b>' . $jjj->bank_details;
                                            } else {
                                                //echo '<br><br><br><br><br><br>';
                                            } ?>
                                        </div>
                                        <!--<br><br><br><br><br>-->
                                    </td>
                                    <td class="text-center">
                                        <hr class="text-white">
                                        TOTAL
                                    </td>
                                    <td class="text-center">
                                        <hr style="color: black; opacity: 1">
                                        <?php echo '<b>' . round($total) . '</b><br><br><br>';
                                        $string = $record['_from'];
                                        $lines = explode("\n", $string);
                                        $firstLine = $lines[0];
                                        echo '<b>' . $firstLine . '</b>'; ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-print-none shadow-lg position-fixed  start-0" style="top: 5%">
                    <div class="list-group rounded-0">
                        <a href="<?php echo $backURL; ?>"
                           class="list-group-item list-group-item-secondary p-1"><i
                                    class="fa fa-arrow-left"></i>
                            Back</a>
                        <a onclick="window.print();" href="#." class="list-group-item list-group-item-secondary p-1"><i
                                    class="fa fa-print"></i> Print</a>
                    </div>

                </div>
                <!--<script src="../assets/js/jquery-3.7.1.min.js"></script>
                <script src="../assets/bs/js/bootstrap.bundle.min.js"></script>
                <script src="../assets/js/virtual-select.min.js"></script>-->
                </body>
                </html>
            <?php }
        }
    } else {
        echo '<script>window.location.href="' . $backURL . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backURL . '";</script>';
} ?>

