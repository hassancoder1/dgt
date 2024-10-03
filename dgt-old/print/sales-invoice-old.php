<?php if (isset($_GET['s_id']) && $_GET['s_id'] > 0 && isset($_GET['action'])) {
    require("../connection.php");
    $sale_id = mysqli_real_escape_string($connect, $_GET['s_id']);
    $action = mysqli_real_escape_string($connect, $_GET['action']);
    $backURL = '../sales';
    $pur_q = fetch('sales', array('id' => $sale_id));
    $s_data = mysqli_fetch_assoc($pur_q);

    $seller_khaata_no = $s_data['s_khaata_no'];
    $sea_road = $s_data['sea_road'];
    $is_loading = $s_data['is_loading'];
    $is_receiving = $s_data['is_receiving']; ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Sale<?php echo $sale_id . '__' . date('Y_m_d'); ?></title>
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

            input {
                pointer-events: none;
                font-weight: bold !important;
                font-family: 'Noto Naskh Arabic', serif;
            }

            .table tbody tr td {
                font-size: 12px;
                color: inherit;
            }

            .table thead tr th {
                /*font-size: 8px;*/
                background: black;
                color: white;
            }

            .under {
                text-decoration: underline;
                text-underline-offset: 10%;
            }

            .table-bordered {
                border: 1px solid #000000;
            }
        </style>
    </head>
    <body>
    <img src="bg-logo.png" style="opacity:1; position: absolute; width:100%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="container-fluid">
        <div class="row justify-content-center fixed-top bg-white">
            <div class="col-lg-7 col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                    </div>
                    <div class="text-end">
                        <h1 class="fw-bold mb-0">ORDER</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" style="margin-top: 80px">
            <div class="col-lg-7 col-12">
                <div class="row gx-1 text-uppercase">
                    <div class="col">
                        <h6 class="mb-0">
                            <?php echo '<b>PURCHASE A/C.</b> ' . $s_data['p_khaata_no'] . '<br>';
                            //echo '<b>Type </b>' . $s_data['type'] . '<br>';?>
                        </h6>
                    </div>
                    <div class="col-auto">
                        <h6>
                            <b>Date</b> <?php echo date('F d,Y', strtotime($s_data['s_date'])); ?><br>
                            <?php echo '<b>Type </b> ' . $s_data['type'] . '<br>';
                            echo '<b>ORDER #</b> ' . $sale_id; ?>
                        </h6>
                    </div>
                    <div class="col-12">
                        <h5 class="fw-bold mt-0">BILL TO:</h5>
                        <h6>
                            <?php echo '<b>A/C. </b> ' . $seller_khaata_no . '<br>';
                            echo '<b>SALE NAME </b> ' . $s_data['s_name'] . '<br>';
                            echo '<b>City </b>' . $s_data['city'] . '<br>';
                            echo '<b>Receiver </b> ' . $s_data['receiver'] . '<br>'; ?>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <?php if ($sea_road == 'sea') {
                        echo '<div class="col-12"><h5 class="fw-bold text-center border mb-0">BY SEA</h5></div>';
                        if ($is_loading == 1) {
                            $loading_json = json_decode($s_data['loading_json']);
                            if (!empty($loading_json)) {
                                echo '<div class="col-6"><table class="table mb-0"><tbody>';
                                echo '<tr><td class="fw-bold text-uppercase">Loading Country</td><td>' . $loading_json->l_country . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Loading Port</td><td>' . $loading_json->l_port . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Loading Date</td><td>' . $loading_json->l_date . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Container Name</td><td>' . $loading_json->ctr_name . '</td></tr>';
                                echo '</tbody></table></div>';
                            }
                        }
                        if ($is_receiving == 1) {
                            $receiving_json = json_decode($s_data['receiving_json']);
                            if (!empty($receiving_json)) {
                                echo '<div class="col-6"><table class="table mb-0"><tbody>';
                                echo '<tr><td class="fw-bold text-uppercase">Receiving Country</td><td>' . $receiving_json->r_country . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Receiving Port</td><td>' . $receiving_json->r_port . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Receiving Date</td><td>' . $receiving_json->r_date . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Arrival Date</td><td>' . $receiving_json->arrival_date . '</td></tr>';
                                echo '</tbody></table></div>';
                            }
                        }
                    } else { //by road
                        echo '<div class="col-12"><h5 class="fw-bold text-center border mb-0">BY ROAD</h5></div>';
                        $road_json = json_decode($s_data['road_json']);
                        if (!empty($road_json)) {
                            echo '<div class="col-6"><table class="table mb-0"><tbody>';
                            echo '<tr><td class="fw-bold text-uppercase">Loading Country</td><td>' . $road_json->l_country_road . '</td></tr>';
                            echo '<tr><td class="fw-bold text-uppercase">Loading Border</td><td>' . $road_json->l_border_road . '</td></tr>';
                            echo '<tr><td class="fw-bold text-uppercase">Loading Date</td><td>' . $road_json->l_date_road . '</td></tr>';
                            echo '<tr><td class="fw-bold text-uppercase">Status</td><td>' . $road_json->truck_container . '</td></tr>';
                            echo '</tbody></table></div>';

                            echo '<div class="col-6"><table class="table mb-0"><tbody>';
                            echo '<tr><td class="fw-bold text-uppercase">Receiving Country</td><td>' . $road_json->r_country_road . '</td></tr>';
                            echo '<tr><td class="fw-bold text-uppercase">Receiving Border</td><td>' . $road_json->r_border_road . '</td></tr>';
                            echo '<tr><td class="fw-bold text-uppercase">Receiving Date</td><td>' . $road_json->r_date_road . '</td></tr>';
                            echo '<tr><td class="fw-bold text-uppercase">Delivery Date</td><td>' . $road_json->d_date_road . '</td></tr>';
                            echo '</tbody></table></div>';

                        }
                    } ?>
                </div>
                <?php if (isSaleDetailsAdded($sale_id)) { ?>
                    <h5 class="fw-bold text-center mt-3">Goods & Payment Details</h5>
                    <table class="table table-sm table-bordered">
                        <thead class="table-dark">
                        <tr class="text-nowrap">
                            <th class="fw-bold">#</th>
                            <th style="width: 50%" class="fw-bold">Description</th>
                            <th class="fw-bold">Quantity</th>
                            <th class="fw-bold">KG(s)</th>
                            <th class="fw-bold">Price</th>
                            <th class="fw-bold">Amount</th>
                            <th class="fw-bold">Exch.Rate</th>
                            <th class="fw-bold text-end">Final Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sr_details = 1;
                        $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                        $pur_d_q = fetch('sale_details', array('parent_id' => $sale_id));
                        while ($details = mysqli_fetch_assoc($pur_d_q)) {
                            $details_id = $details['id'];
                            echo '<tr>';
                            echo '<td>' . $details['d_sr'] . '</td>';
                            echo '<td class="font-size-11">' . goodsName($details['goods_id']) . $details['size'] . $details['brand'] . '</td>';
                            echo '<td>' . $details['qty_no'] . $details['qty_name'] . '</td>';
                            echo '<td>' . $details['net_kgs'] . '<sub>' . $details['divide'] . '</sub>' . '</td>';
                            echo '<td>' . $details['rate1'] . '/<sub>' . $details['currency1'] . '</sub>' . '</td>';
                            echo '<td>' . round($details['amount']) . '</td>';
                            echo '<td>' . $details['rate2'] . '/<sub>' . $details['currency2'] . '</sub>' . '</td>';
                            echo '<td class="text-end">' . round($details['final_amount']) . '</td>';
                            echo '</tr>';
                            $sr_details++;
                            $qty_no += $details['qty_no'];
                            $qty_kgs += $details['qty_kgs'];
                            $total_kgs += $details['total_kgs'];
                            $total_qty_kgs += $details['total_qty_kgs'];
                            $net_kgs += $details['net_kgs'];
                            $total += $details['total'];
                            $amount += $details['amount'];
                            $final_amount += $details['final_amount'];
                        }
                        echo '<tr>';
                        echo '<th colspan="2"></th>';
                        echo '<th class="fw-bold">' . $qty_no . '</th>';
                        //echo '<th class="fw-bold">' . $qty_kgs . '</th>';
                        //echo '<th class="fw-bold">' . $total_kgs . '</th>';
                        //echo '<th class="fw-bold">' . $total_qty_kgs . '</th>';
                        //echo '<th class="fw-bold">' . $net_kgs . '</th>';
                        echo '<th class="fw-bold">' . $total . '</th>';
                        echo '<th colspan="1"></th>';
                        echo '<th class="fw-bold ">' . $amount . '</th>';
                        echo '<th colspan="1"></th>';
                        echo '<th class="fw-bold text-end">' . $final_amount . '</th>';
                        echo '</tr>'; ?>
                        </tbody>
                    </table>
                <?php } ?>
                <?php if (!empty($s_data['rep_indexes'])) {
                    $details_k = ['indexes' => $s_data['rep_indexes'], 'vals' => $s_data['rep_vals']];
                    $reps = displayKhaataDetails($details_k, true); ?>
                    <div class="row">
                        <div class="col-12">
                            <table class="table mb-0">
                                <tbody>
                                <?php if (array_key_exists('Condition', $reps)) {
                                    echo '<tr>
                                <td class="fw-bold text-uppercase">Goods Condition Report <span class="fw-normal text-decoration-underline">' . $reps['Condition'] . '</span>';
                                    echo '</td></tr>';
                                }
                                if (array_key_exists('Loading', $reps)) {
                                    echo '<tr>
                                <td class="fw-bold text-uppercase">Loading Report <span class="fw-normal text-decoration-underline">' . $reps['Loading'] . '</span>';
                                    echo '</td></tr>';
                                }
                                if (array_key_exists('Booking', $reps)) {
                                    echo '<tr>
                                <td class="fw-bold text-uppercase">Booking Report <span class="fw-normal text-decoration-underline">' . $reps['Booking'] . '</span>';
                                    echo '</td></tr>';
                                }
                                if (array_key_exists('Final', $reps)) {
                                    echo '<tr>
                                <td class="fw-bold text-uppercase">Final Report <span class="fw-normal text-decoration-underline">' . $reps['Final'] . '</span>';
                                    echo '</td></tr>';
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="fixed-bottom">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-12">
                    <?php echo $s_data['report'] != '' ? '<b>REPORT: </b>' . $s_data['report'] : ''; ?>
                </div>
                <div class="col-lg-7 col-12">
                    <div class="row">
                        <div class="col"><p><b>Buyer Signature</b></p></div>
                        <div class="col"><p><b>Seller Signature</b></p></div>
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
    <script src="../assets/tooltip/tooltip.min.js"></script>
    <div class="sticky-social d-print-none" style="z-index: 999999">
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
    <?php echo isset($_GET['print']) ? '<script>window.print();</script>' : '';
} else {
    echo '<script>window.location.href="../";</script>';
} ?>

