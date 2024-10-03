<?php if (isset($_GET['s_id']) && $_GET['s_id'] > 0 && isset($_GET['action'])) {
    require("../connection.php");
    $sale_id = mysqli_real_escape_string($connect, $_GET['s_id']);
    $action = mysqli_real_escape_string($connect, $_GET['action']);
    $backURL = '../purchase-market';
    $pur_q = fetch('purchases', array('id' => $sale_id));
    $s_data = mysqli_fetch_assoc($pur_q);
    $pur_d_q = fetch('purchase_details', array('parent_id' => $sale_id));
    $seller_json = json_decode($s_data['seller_json']);
    $seller_khaata_no = empty($seller_json) ? '' : $seller_json->khaata_no;
    $seller_khaata_id = empty($seller_json) ? '' : $seller_json->khaata_id;
    $copies = array(
        array(
            'name' => 'Customer Copy',
            'bg_color' => 'bg-light',
        ),
        array(
            'name' => 'Owner Copy',
            'bg_color' => 'bg-light',
        )
    ); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Market_Purchase_<?php echo $sale_id . '__' . date('Y_m_d'); ?></title>
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

            /*@media print {
                @page {
                    margin: 0;
                    background-image: url('bg-logo.png');
                    background-size: cover;
                }
            }*/
        </style>
    </head>
    <body>
    <?php foreach ($copies as $copy) { ?>
        <div class="container-fluid" style="page-break-before: always;">
            <div class="row justify-content-center fixed-top-bg-white">
                <div class="col-lg-7 col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                            <?php //echo '<br><b>DGT L.L.C</b> ';
                            echo '<h6 class="font-size-11">';
                            $pp = khaataSingle($s_data['p_khaata_no'], true);
                            echo empty($pp) ? '<b>DGT L.L.C</b> ' : '<b>' . $pp['comp_name'] . '</b>';
                            if (!empty($pp)) {
                                echo '<br><b>A/C. </b>' . '<span class="text-uppercase">'.$s_data['p_khaata_no'].'</span>';
                                $details_k = ['indexes' => $pp['indexes'], 'vals' => $pp['vals']];
                                $reps = displayKhaataDetails($details_k, true);
                                //var_dump($details_k);
                                if (array_key_exists('VAT', $reps)) {
                                    echo '<br><b>VAT# </b>' . $reps['VAT'];
                                }
                                if (array_key_exists('License', $reps)) {
                                    echo '<br><b>License# </b>' . $reps['License'];
                                }
                            }
                            echo '</h6>';
                            ?>
                        </div>
                        <div>
                            <!--<span class="bg-light p-2">CUSTOMER COPY</span>-->
                            <?php echo '<span class="' . $copy['bg_color'] . ' p-2">' . $copy['name'] . '</span>'; ?>
                        </div>
                        <div class="text-end">
                            <h1 class="fw-bold mb-0">MARKET PURCHASE</h1>
                            <b>Date</b> <?php echo date('F d,Y', strtotime($s_data['p_date']));
                            echo '<br><b>BILL #</b> ' . $sale_id; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center g-0">
                <div class="col-lg-7 col-12">
                    <hr>
                    <h5 class="fw-bold mt-3">BILL TO:</h5>
                    <h6 class="font-size-11">
                        <?php if ($s_data['is_acc'] == 1) {
                            echo '<b>A/C. </b> ' . $seller_khaata_no;
                            $seller_khaata = khaataSingle($seller_json->khaata_id);
                            echo '<br><b>A/C NAME </b>' . $seller_khaata['khaata_name'];
                            echo '<br><b>BRANCH </b>' . branchName($seller_khaata['branch_id']);
                            echo '<br><b>CATEGORY </b>' . catName($seller_khaata['cat_id']);
                            echo '<br><b>BUSINESS </b>' . $seller_khaata['business_name'];
                            echo '<br><b>COMPANY </b>' . $seller_khaata['comp_name'];
                            echo '<br><b>CITY </b>' . $seller_khaata['city'];
                            echo '<br><b>ADDRESS </b>' . $seller_khaata['address'];

                            $details_k = ['indexes' => $seller_khaata['indexes'], 'vals' => $seller_khaata['vals']];
                            $reps = displayKhaataDetails($details_k, true);
                            //var_dump($details_k);
                            if (array_key_exists('VAT', $reps)) {
                                echo '<br><b>VAT# </b>' . $reps['VAT'];
                            }


                        } else {
                            echo '<b>PURCHASE NAME </b>' . $seller_json->s_name . '<br>';
                            echo '<b>COMPANY </b>' . $seller_json->s_company . '<br>';
                            echo '<b>WEIGHT # </b>' . $seller_json->s_weight_no . '<br>';
                            echo '<b>PHONE </b>' . $seller_json->s_phone . '<br>';
                            echo '<b>EMAIL </b>' . $seller_json->s_email . '<br>';
                            echo '<b>ADDRESS </b>' . $seller_json->s_address . '<br>';
                        } ?>
                    </h6>
                    <?php if (isPurchaseBookingDetailsAdded($sale_id)) { ?>
                        <h5 class="fw-bold text-center mt-4">Goods & Payment Details</h5>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark">
                            <tr class="text-nowrap">
                                <th class="fw-bold">#</th>
                                <th style="width: 50%" class="fw-bold">Description</th>
                                <th class="fw-bold">Quantity</th>
                                <th class="fw-bold">KG(s)</th>
                                <th class="fw-bold">Price</th>
                                <th class="fw-bold">Amount</th>
                                <th class="fw-bold">VAT</th>
                                <th class="fw-bold text-end">Final Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sr_details = 1;
                            $wh_k_ids = $wh_kd_ids = array();
                            $currency1 = $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $vat = $final_amount = 0;
                            $pur_d_q = fetch('purchase_details', array('parent_id' => $sale_id));
                            while ($details = mysqli_fetch_assoc($pur_d_q)) {
                                $details_id = $details['id'];
                                echo '<tr>';
                                echo '<td>' . $details['d_sr'] . '</td>';
                                echo '<td class="font-size-11">' . goodsName($details['goods_id']) . $details['size'] . $details['brand'] . '</td>';
                                echo '<td>' . $details['qty_no'] . $details['qty_name'] . '</td>';
                                echo '<td>' . $details['net_kgs'] . '<sub>' . $details['divide'] . '</sub>' . '</td>';
                                echo '<td>' . $details['rate1'] . '/<sub>' . $details['currency1'] . '</sub>' . '</td>';
                                echo '<td>' . round($details['amount']) . '</td>';
                                echo '<td>' . round($details['rate2'], 2);
                                echo $details['rate2'] > 0 ? '<sub>' . $details['currency2'] . '%</sub>' : '';
                                echo '</td>';
                                echo '<td class="text-end-">' . round($details['final_amount']) . '</td>';
                                echo '</tr>';
                                $sr_details++;

                                $wh_k_ids[] = $details['wh_k_id'];
                                $wh_kd_ids[] = $details['wh_kd_id'];

                                $currency1 = $details['currency1'];
                                $qty_no += $details['qty_no'];
                                $qty_kgs += $details['qty_kgs'];
                                $total_kgs += $details['total_kgs'];
                                $total_qty_kgs += $details['total_qty_kgs'];
                                $net_kgs += $details['net_kgs'];
                                $total += $details['total'];
                                $amount += $details['amount'];
                                $vat += $details['rate2'];
                                $final_amount += $details['final_amount'];
                            }
                            echo '<tr class="text-nowrap">';
                            echo '<th colspan="2"></th>';
                            echo '<th class="fw-bold">' . $qty_no . '</th>';
                            echo '<th class="fw-bold">' . $total . '</th>';
                            echo '<th colspan="1"></th>';
                            echo '<th class="fw-bold" colspan="2">SUB TOTAL</th>';
                            echo '<th class="fw-bold">' . round($amount, 2) . '</th>';
                            echo '</tr>';
                            echo '<tr  class="text-nowrap">';
                            echo '<th colspan="5"></th>';
                            echo '<th class="fw-bold" colspan="2">VAT AMOUNT</th>';
                            echo '<th class="fw-bold">' . round($vat, 2) . '</th>';
                            echo '</tr>';
                            echo '<tr class="text-nowrap">';
                            echo '<th colspan="5"></th>';
                            echo '<th class="fw-bold" colspan="2">TOTAL</th>';
                            echo '<th class="fw-bold">' . round($final_amount, 2) . ' ' . $currency1 . '</th>';
                            echo '</tr>'; ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="fixed-bottom bg-white">
            <div class="container-fluid">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-7 col-12 mb-5">
                        <?php echo '<b>LOADING WAREHOUSE </b> ';

                        foreach (array_unique($wh_k_ids) as $wh_k_id) {
                            $wh_khaata = khaataSingle($wh_k_id);
                            echo '<b>A/C.</b> ' . $wh_khaata['khaata_no'] . '<br>';
                        }
                        foreach (array_unique($wh_kd_ids) as $wh_kd_id) {
                            $wh_khaata_details = khaataDetailsData($wh_kd_id);
                            echo '<b>NAME</b> ' . $wh_khaata_details['comp_name'] . '<br>';
                            echo '<b>CITY</b> ' . $wh_khaata_details['city'] . '<br>';
                            echo '<b>ADDRESS</b> ' . $wh_khaata_details['address'] . '<br>';
                            //var_dump($wh_khaata_details);
                        }
                        echo '<hr>';
                        echo $s_data['receiver'] != '' ? '<b>PAYMENT RECEIVED DATE: </b>' . $s_data['receiver'] : '';
                        echo '<br>';
                        echo $s_data['report'] != '' ? '<b>REPORT: </b>' . $s_data['report'] : ''; ?>
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
    <?php } ?>

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

