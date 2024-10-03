<?php if (isset($_GET['p_id']) && $_GET['p_id'] > 0 && isset($_GET['action'])) {
    require("../connection.php");
    $purchase_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    $action = mysqli_real_escape_string($connect, $_GET['action']);
    $backURL = '../purchases';
    $pur_q = fetch('transactions', array('id' => $purchase_id));
    $p_data = mysqli_fetch_assoc($pur_q);
    $pur_d_q = fetch('transaction_items', array('parent_id' => $purchase_id));
    $purchaser = khaataSingle($p_data['p_khaata_id']);
    $seller = khaataSingle($p_data['s_khaata_id']); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Purchase<?php echo $purchase_id . '__' . date('Y_m_d'); ?></title>
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
    <img src="bg-logo.png"
         style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="container-fluid" style="min-height: 794px;">
        <div class="row justify-content-center fixed-top bg-white">
            <div class="col-lg-8 col-12">
                <div class="d-flex align-items-start justify-content-between">
                    <div><img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                    </div>
                    <div class="text-end">
                        <h2 class="fw-bold mb-0">PURCHASE CONTRACT</h2>
                        <h6>
                            <b>Sr#</b> <?php echo $purchase_id; ?><br>
                            <b>Date</b> <?php echo date('d M Y', strtotime($p_data['p_date'])); ?><br>
                            <b>Country</b> <?php echo $p_data['country']; ?><br>
                            <b>Allot</b> <?php echo $p_data['allot']; ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" style="margin-top: 110px">
            <div class="col-lg-8 col-12">
                <div class="row gx-1 text-uppercase">
                    <div class="col">
                        <h6 class="mb-0 fw-bold">Purchaser</h6>
                        <h6 class="small">
                            <?php echo '<b>Company</b> ' . $purchaser['comp_name'] . '<br>';
                            echo '<b>Address</b> ' . $purchaser['address'] . '<br>';
                            echo '<b>City</b> ' . $purchaser['city']; ?>
                        </h6>
                    </div>
                    <div class="col">
                        <h6 class="mb-0 fw-bold">Seller</h6>
                        <h6 class="small">
                            <?php echo '<b>Company</b> ' . $seller['comp_name'] . '<br>';
                            echo '<b>Address</b> ' . $seller['address'] . '<br>';
                            echo '<b>City</b> ' . $seller['city']; ?>
                        </h6>
                    </div>
                </div>
                <?php $details2 = ['indexes' => $p_data['rep_indexes'], 'vals' => $p_data['rep_vals']];
                $reps = displayKhaataDetails($details2, true);
                //if (!empty($reps)) { ?>
                <?php //} ?>
                <div class="row">
                    <?php if ($p_data['is_loading'] == 1) {
                        $loading_json = json_decode($p_data['loading_json']); ?>
                        <div class="col-6 mt-3">
                            <table class="table">
                                <tbody>
                                <?php echo '<tr><td class="fw-bold text-uppercase">Loading Country</td><td>' . $loading_json->l_country . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Loading Port</td><td>' . $loading_json->l_port . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Loading Date</td><td>' . $loading_json->l_date . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Container Name</td><td>' . $loading_json->ctr_name . '</td></tr>'; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    <?php if ($p_data['is_receiving'] == 1) {
                        $receiving_json = json_decode($p_data['receiving_json']); ?>
                        <div class="col-6 mt-3">
                            <table class="table">
                                <tbody>
                                <?php echo '<tr><td class="fw-bold text-uppercase">Receiving Country</td><td>' . $receiving_json->r_country . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Receiving Port</td><td>' . $receiving_json->r_port . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Receiving Date</td><td>' . $receiving_json->r_date . '</td></tr>';
                                echo '<tr><td class="fw-bold text-uppercase">Arrival Data</td><td>' . $receiving_json->arrival_date . '</td></tr>'; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
                <?php if (isPurchaseBookingDetailsAdded($purchase_id)) { ?>
                    <h5 class="fw-bold text-center">Goods & Payment Details</h5>
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
                        $pur_d_q = fetch('purchase_details', array('parent_id' => $purchase_id));
                        while ($details = mysqli_fetch_assoc($pur_d_q)) {
                            $details_id = $details['id'];
                            echo '<tr>';
                            echo '<td>' . $details['d_sr'] . '</td>';
                            echo '<td class="font-size-11">' . goodsName($details['goods_id']) . $details['size'] . $details['brand'] . $details['origin'] . '</td>';
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
                <div class="row">
                    <div class="col-12">
                        <table class="table ">
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
                <?php if ($action == 'adv' || $action == 'rem') { ?>
                    <div>
                        <?php $adv_paid_final = purchaseSpecificData($purchase_id, 'adv_paid_total');
                        $bal = $p_data['pct_amt'] - $adv_paid_final;
                        echo '<p class="mt-2 fw-bold mb-0">This invoice reflects a ' . round($p_data['pct'],2) . '% advance payment made by the buyer towards the total cost of the order: ';
                        echo '<span class="text-success"> TOTAL PAID:' . round($adv_paid_final) . '</span>';
                        echo '<span class="text-danger mb-0"><b> BALANCE:</b>' . round($bal) . '</span>';
                        echo '</p>';
                        ?>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-nowrap">Dr. A/c</th>
                            <th>Date</th>
                            <th style="width: 60%">Report</th>
                            <th>Amount</th>
                            <th>Rate</th>
                            <th>Final</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $adv_paid = purchaseSpecificData($purchase_id, 'adv');
                        foreach ($adv_paid as $item) {
                            echo '<tr>';
                            echo '<td>' . $item['dr_khaata_no'] . '</td>';
                            echo '<td class="text-nowrap">' . date('y-m-d', strtotime($item['created_at'])) . '</td>';
                            echo '<td class="font-size-11">';
                            $dr_khaata = khaataSingle($item['dr_khaata_id']);
                            echo $dr_khaata['khaata_name'] . ' ' . $item['report'];
                            echo '</td>';
                            echo '<td>' . round($item['amount']) . '<sub>' . $item['currency1'] . '</sub>' . '</td>';
                            echo '<td>' . $item['rate'] . '[' . $item['opr'] . ']' . '</td>';
                            echo '<td>' . round($item['final_amount']) . '<sub>' . $item['currency2'] . '</sub>' . '</td>';
                            echo '</tr>';
                        } ?>
                        </tbody>
                    </table>
                <?php }
                if ($action == 'rem') { ?>
                    <div>
                        <?php $totalPurchaseAmount = totalPurchaseAmount($purchase_id);
                        $adv_paid_total = purchaseSpecificData($purchase_id, 'adv_paid_total');
                        $rem_paid_total = purchaseSpecificData($purchase_id, 'rem_paid_total');
                        $bal2 = $totalPurchaseAmount - $rem_paid_total - $adv_paid_total;
                        $rrr = 100 - $p_data['pct'];
                        echo '<p class="mt-2 fw-bold mb-0">This reflects an ' . round($rrr,2) . '% payment towards the total cost, in addition to the ' . $p_data['pct'] . '% advance payment:';
                        echo '<span class="text-success"> TOTAL PAID:' . round($rem_paid_total) . '</span>';
                        echo '<span class="text-danger mb-0"><b> BALANCE:</b>' . round($bal2) . '</span>';
                        echo '</p>'; ?>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-nowrap">Dr. A/c</th>
                            <th>Date</th>
                            <th>Report</th>
                            <th>Amount</th>
                            <th>Rate</th>
                            <th>Final</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rem_paid = purchaseSpecificData($purchase_id, 'rem');
                        foreach ($rem_paid as $item) {
                            echo '<tr>';
                            echo '<td>' . $item['dr_khaata_no'] . '</td>';
                            echo '<td class="text-nowrap">' . date('y-m-d', strtotime($item['created_at'])) . '</td>';
                            echo '<td class="small">';
                            if ($item['dr_khaata_id'] > 0) {
                                $dr_khaata = khaataSingle($item['dr_khaata_id']);
                                echo $dr_khaata['khaata_name'];
                            }
                            echo ' ' . $item['report'];
                            echo '</td>';
                            echo '<td>' . round($item['amount']) . '<sub>' . $item['currency1'] . '</sub></td>';
                            echo '<td>' . $item['rate'] . '[' . $item['opr'] . ']</td>';
                            echo '<td>' . round($item['final_amount']) . '<sub>' . $item['currency2'] . '</sub></td>';

                            echo '</tr>';
                        } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="fixed-bottom bg-white">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-12">
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
        <div class="ms-4">
            <?php $print_array = array(array('Purchase Contract', 'contract'), array('Transfer', 'transfer'), array('Proforma Invoice', 'proforma'), array('Packing List', 'packing'), array('Bill Print', 'bill')); ?>
            <select class="form-select" id="print_type" name="print_type">
                <option value="" hidden="">Print type? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                <?php foreach ($print_array as $item) {
                    echo '<option value="' . $item[1] . '">' . ucfirst($item[0]) . '</option>';
                } ?>
            </select>
            <script>
                document.querySelector('#print_type').addEventListener('change', function () {
                    window.location.href = 'purchase-booking?p_id=<?php echo $purchase_id; ?>&action=' + this.value;
                });
            </script>
        </div>
    </div>
    </body>
    </html>
    <?php echo isset($_GET['print']) ? '<script>window.print();</script>' : '';
} else {
    //echo '<script>window.location.href="../";</script>';
} ?>

