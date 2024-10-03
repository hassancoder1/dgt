<?php $backURL = '../afg-invoice';
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
        $backURL = '../' . $info_types[$type][0] . '?view=1&id=' . $id;
        if (in_array($type, $allowed_types)) {
            $rQuery = mysqli_query($connect, "SELECT * FROM `afg_invs` WHERE id = '$id'");
            if (mysqli_num_rows($rQuery) > 0) {
                $record = mysqli_fetch_assoc($rQuery);
                $json_data = json_decode($record['json_data']); ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>Draft_Invoice_<?php echo $id . '_' . date('Y_m_d-H_i_s'); ?></title>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta content="Al Ras Deira Dubai office UAE dubai" name="description"/>
                    <meta content="DGT L.L.C" name="author"/>
                    <link rel="shortcut icon" href="../assets/images/favicon.jpg">
                    <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
                    <link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
                    <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
                    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
                    <style>
                        body {
                            background: transparent;
                        }

                        #main {
                            background-image: url("bg.png");
                            background-position: center;
                            background-size: contain;
                            background-repeat: no-repeat;
                            height: 110%;

                        }

                        * {
                            color: black;
                        }

                        h6 {
                            margin-bottom: 0;
                        }

                        .table > :not(caption) > * > * {
                            padding: 0.1rem .45rem;
                        }

                        .table tbody tr td {
                            /*font-size: 10px;*/
                            color: inherit;
                        }

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

                        .under {
                            text-decoration: underline;
                            text-underline-offset: 10%;
                        }

                        /*.table-bordered {
                            border: 3px solid #000000;
                        }*/
                    </style>
                </head>
                <body>
                <!--<img src="bg-logo.png" style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">-->
                <section>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-12 text-center fw-bold">
                                <h3 class="fw-bold mb-0">INVOICE</h3>
                                <h4 class="fw-bold">******************</h4>
                            </div>
                            <div class="col-7 flex-column align-items-center">
                                <div class="border border-dark border-2 p-2" style="min-height: 220px">
                                    <div><?php echo '<b>FROM:</b> ' . nl2br($record['_from']); ?></div>
                                    <div><?php echo '<b>THIRD PARTY:</b><br> ' . nl2br($record['third_party']); ?></div>
                                    <div style="border-top: 2px dashed"><?php echo '<b>TO:</b> ' . nl2br($record['_from']); ?></div>
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
                        <table class="table table-borderless mt-3" style="min-height: 650px;">
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
                            <tbody class="">
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

                        <!--<div class="row align-items-center">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p><b>Account Signature</b></p>
                                                </div>
                                                <div class="col">
                                                    <div class="border-top border-2 border-dark"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p><b>Company Signature</b></p>
                                                </div>
                                                <div class="col">
                                                    <div class="border-top border-2 border-dark"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <img src="pdf-footer.png" class="img-fluid">-->
                    </div>
                </section>
                <script src="../assets/tooltip/tooltip.min.js"></script>
                <div class="sticky-social d-print-none">
                    <ul class="social">
                        <li class="bg-dark" data-tooltip="Go Back" data-tooltip-position="right">
                            <a href="<?php echo $backURL; ?>"><i class="fa fa-long-arrow-alt-left"></i></a>
                        </li>
                        <li class="facebook" title="PDF Print">
                            <a class="cursor-pointer" onclick="window.print();"><i class="fa fa-print"></i></a>
                        </li>
                    </ul>
                </div>
                </body>
                </html>
                <?php if (isset($_GET['print'])) {
                    echo '<script>window.print();</script>';
                }
            }
        }
    } else {
        echo '<script>window.location.href="' . $backURL . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backURL . '";</script>';
} ?>

