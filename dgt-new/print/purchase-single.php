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
        <!-- <link href="../assets/css/virtual-select.min.css" rel="stylesheet"> -->
        <!-- <script src="../assets/fa/fontawesome.js" crossorigin="anonymous"></script> -->
        <link rel="shortcut icon" href="../assets/images/favicon.jpg" />
        <script src="https://cdn.jsdelivr.net/npm/html-to-docx@1.8.0/dist/html-to-docx.umd.min.js"></script>
        <style>
            .sidebar {
                height: 100vh;
                width: 180px;
                position: fixed;
                top: 0;
                left: 0;
                background-color: transparent;
                padding: 20px;
                z-index: 1000;
            }

            .sidebar .nav-item {
                margin-bottom: 15px;
            }

            .main-content {
                margin-left: 270px;
                padding: 20px;
            }
        </style>
    </head>

    <body onbeforeprint="togglePrintControls()" onafterprint="togglePrintControls()">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column" id="printControls">
            <div class="fs-5 fw-bold text-uppercase text-dark mb-3">Print Purchases</div>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex flex-column fw-bold">
                    <div class="text-dark mb-2">Print Type</div>
                    <select id="printTypeSelect" class="form-select">
                        <option value="full" selected>Full Print</option>
                        <option value="customer">Customer Print</option>
                    </select>
                </div>
                <!-- Add the 'Back' button and other controls -->
                <div class="nav-item">
                    <?= addNew('../purchases', 'Back', 'btn-sm btn-warning', 'fa-arrow-left'); ?>
                </div>
                <div class="nav-item">
                    <button class="btn btn-primary btn-sm" onclick="downloadAsWord()">
                        <i class="fa fa-file-word-o"></i> Word
                    </button>
                </div>
                <div class="nav-item">
                    <a href="/compose" class="btn btn-warning btn-sm">
                        <i class="fa fa-print"></i> Mail
                    </a>
                </div>
                <div class="nav-item">
                    <a onclick="window.print();" href="#." class="btn btn-success btn-sm">
                        <i class="fa fa-print"></i> Print
                    </a>
                </div>
            </div>
        </div>
        <img src="bg-logo.png"
            style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: -99">
        <div class="container-fluid mt-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <img src="../assets/images/logo.png" alt="logo" class="img-fluid" width="100">
                        <div>
                            <h3 class="fw-bold mb-0">PURCHASE</h3>
                            <div>
                                <b>Date: </b><?php echo my_date($_fields['_date']); ?><br>
                                <b>Branch: </b><?php echo $_fields['branch_id']; ?> <br>
                                <b>Sr#</b> <?php echo $id; ?><br>
                            </div>
                        </div>
                    </div>
                    <!-- Because Client said that Dr. Account should be Cr. and Cr. should be Dr.
                                    So it is much difficult to edit it from backend so what i'm doing i'm just labelling Dr. Acc from frontend as Cr. Acc. -->
                    <div class="row gx-0 mb-2">
                        <div class="col-6">
                            <div><b>Purchaser A/c # </b><?php echo $_fields['dr_acc']; ?></div>
                            <?php if (!empty($_fields['dr_acc_details'])): ?>
                                <div>
                                    <?php
                                    $seller_lines = explode("\n", $_fields['dr_acc_details']);
                                    $company_name = array_shift($seller_lines); // First line is company name
                                    echo "<b>Company Name: </b>" . trim($company_name) . "<br>"; // Display company name

                                    // Extract city, state, country in one line
                                    $location = '';
                                    foreach ($seller_lines as $key => $line) {
                                        if (strpos($line, 'City:') !== false || strpos($line, 'State:') !== false || strpos($line, 'Country:') !== false) {
                                            $location .= ' ' . trim($line);
                                            unset($seller_lines[$key]); // Remove from list
                                        }
                                    }
                                    echo "<b>Location: </b>" . trim($location) . "<br>";

                                    // Show address
                                    foreach ($seller_lines as $key => $line) {
                                        if (strpos($line, 'Address:') !== false) {
                                            echo "<b>Address: </b>" . trim($line) . "<br>";
                                            unset($seller_lines[$key]); // Remove address from list
                                        }
                                    }

                                    // Show the rest of the details two per line
                                    $count = 0;
                                    foreach ($seller_lines as $line) {
                                        if (strpos($line, ':') !== false):
                                            [$key, $value] = explode(':', $line, 2);
                                            echo "<span><b>" . trim($key) . ":</b> " . trim($value) . "</span>&nbsp;&nbsp;";
                                        else:
                                            echo "<span>" . trim($line) . "</span>";
                                        endif;

                                        $count++;
                                        if ($count % 2 == 0) {
                                            echo "<br>"; // Add line break after every two spans
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-6">
                            <div><b>Seller A/c # </b><?php echo $_fields['cr_acc']; ?></div>
                            <?php if (!empty($_fields['cr_acc_details'])): ?>
                                <div>
                                    <?php
                                    $purchaser_lines = explode("\n", $_fields['cr_acc_details']);
                                    $company_name = array_shift($purchaser_lines); // First line is company name
                                    echo "<b>Company Name: </b>" . trim($company_name) . "<br>"; // Display company name

                                    // Extract city, state, country in one line
                                    $location = '';
                                    foreach ($purchaser_lines as $key => $line) {
                                        if (strpos($line, 'City:') !== false || strpos($line, 'State:') !== false || strpos($line, 'Country:') !== false) {
                                            $location .= ' ' . trim($line);
                                            unset($purchaser_lines[$key]); // Remove from list
                                        }
                                    }
                                    echo "<b>Location: </b>" . trim($location) . "<br>";

                                    // Show address
                                    foreach ($purchaser_lines as $key => $line) {
                                        if (strpos($line, 'Address:') !== false) {
                                            echo "<b>Address: </b>" . trim($line) . "<br>";
                                            unset($purchaser_lines[$key]); // Remove address from list
                                        }
                                    }

                                    // Show the rest of the details two per line
                                    $count = 0;
                                    foreach ($purchaser_lines as $line) {
                                        if (strpos($line, ':') !== false):
                                            [$key, $value] = explode(':', $line, 2);
                                            echo "<span><b>" . trim($key) . ":</b> " . trim($value) . "</span>&nbsp;&nbsp;";
                                        else:
                                            echo "<span>" . trim($line) . "</span>";
                                        endif;

                                        $count++;
                                        if ($count % 2 == 0) {
                                            echo "<br>"; // Add line break after every two spans
                                        }
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="fullPrint">
                        <?php if (!empty($_fields['sea_road_array'])): ?>
                            <div class="row gy-1 border-bottom py-2">
                                <div class="col-12">
                                    <span class="fs-6 fw-bold">By </span>
                                    <?php echo $_fields['sea_road']; ?>
                                </div>
                                <div class="col-4">
                                    <div class="fs-6 fw-bold">Loading Details</div>
                                    <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                        <?php if (strpos($key, 'l_') === 0): ?>
                                            <b><?php echo is_array($value) ? $value[0] : strtoupper($key); ?>:</b> <?php echo is_array($value) ? $value[1] : $value; ?><br>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-4">
                                    <div class="fs-6 fw-bold">Receiving Details</div>
                                    <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                        <?php if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0): ?>
                                            <b><?php echo $key === 'd_date_road' ? 'Arrival Date' : (is_array($value) ? $value[0] : strtoupper($key)); ?>:</b> <?php echo is_array($value) ? $value[1] : $value; ?><br>

                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div class="col-4">
                                    <div class="fs-6 fw-bold">Payment Details</div>
                                    <?php
                                    $payments = $_fields['payment_details'];
                                    $total_amount = isset($_fields['items_sum']['sum_final_amount']) ? (float)$_fields['items_sum']['sum_final_amount'] : 0;
                                    $percentage = isset($payments->pct_value) ? (int)$payments->pct_value : 0;
                                    $remaining_percentage = 100 - $percentage;
                                    $partial_amount1 = ($percentage / 100) * $total_amount;
                                    $partial_amount2 = ($remaining_percentage / 100) * $total_amount;

                                    if (isset($payments->full_advance) && $payments->full_advance === 'advance'): ?>
                                        <b>Type:</b> <?= ucfirst($payments->full_advance); ?> - <?= $percentage; ?>% (Remaining: <?= $remaining_percentage; ?>%)<br>
                                        <b>Partial Amount 1 (<?= $percentage; ?>%):</b> <?= number_format($partial_amount1, 2); ?><br>
                                        <b>Date:</b> <?= $payments->partial_date1; ?>
                                        <b>Report:</b> <?= ucfirst($payments->partial_report1); ?><br>
                                        <b>Partial Amount 2 (<?= $remaining_percentage; ?>%):</b> <?= number_format($partial_amount2, 2); ?><br>
                                        <b>Date:</b> <?= $payments->partial_date2; ?>
                                        <b>Report:</b> <?= ucfirst($payments->partial_report2); ?><br>
                                    <?php elseif (isset($payments->full_advance) && $payments->full_advance === 'full'): ?>
                                        <b>Type:</b> Full Payment<br>
                                        <b>Total Amount:</b> <?= number_format($total_amount, 2); ?><br>
                                        <b>Date:</b> <?= $payments->full_date; ?>
                                        <b>Report:</b> <?= ucfirst($payments->full_report); ?><br>
                                    <?php else: ?>
                                        <b>No payment details available.</b>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_fields['sea_road_report'])): ?>
                            <div class="col-12 mt-2 mb-4">
                                <div class="fs-6 fw-bold">Report</div>
                                <?php echo $_fields['sea_road_report']; ?>
                            </div>
                        <?php endif; ?>
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
                                        echo '<td>' . $details['qty_no'] . ' <b> ' . $details['qty_name'] . '</b></td>';
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
                    </div>



                    <div id="customerPrint" class="d-none">
                        <table class="table mt-4 mb-2 table-hover table-sm">
                            <thead>
                                <tr>
                                    <?php
                                    // Table headers
                                    $headers = ['Loading Details', 'Receiving Details', 'SHIP VIA', 'Payment Details'];
                                    foreach ($headers as $header): ?>
                                        <th class="bg-dark text-white border border-white text-center"><?= $header; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Determine whether the shipment is by road or sea
                                $is_road = $_fields['sea_road'] === 'road';

                                // Set dynamic loading and receiving details based on transport mode
                                $loading_country = $_fields['sea_road_array'][$is_road ? 'l_country_road' : 'l_country'][1];
                                $loading_location = $_fields['sea_road_array'][$is_road ? 'l_border_road' : 'l_port'][1];
                                $receiving_country = $_fields['sea_road_array'][$is_road ? 'r_country_road' : 'r_country'][1];
                                $receiving_location = $_fields['sea_road_array'][$is_road ? 'r_border_road' : 'r_port'][1];
                                $ship_via = $is_road ? 'Road' : 'Sea';

                                // Payment details
                                $payment_details = $_fields['payment_details']->full_advance === 'full'
                                    ? 'Full Payment: ' . $_fields['payment_details']->p_total_amount : 'Advance Payment: ' . $_fields['payment_details']->pct_value . '% (Remaining: ' . (100 - $_fields['payment_details']->pct_value) . '%)';
                                ?>
                                <!-- Main row with dynamic data -->
                                <tr>
                                    <td class="border border-dark text-center"><?= $loading_country . ' - ' . ($is_road ? 'Border' : 'Port'); ?></td>
                                    <td class="border border-dark text-center"><?= $receiving_country . ' - ' . ($is_road ? 'Border' : 'Port'); ?></td>
                                    <td class="border border-dark text-center"><?= $ship_via; ?></td>
                                    <td class="border border-dark text-center"><?= $payment_details; ?></td>
                                </tr>

                                <!-- Second row with loading/receiving border or port and partial payment details -->
                                <tr>
                                    <!-- Show either "Port" or "Border" dynamically based on the transportation mode -->
                                    <td class="border border-dark text-center"><?= $is_road ? 'Border: ' : 'Port: '; ?><?= $loading_location; ?></td>
                                    <td class="border border-dark text-center"><?= $is_road ? 'Border: ' : 'Port: '; ?><?= $receiving_location; ?></td>
                                    <td class="border border-dark text-center">Delivery Terms</td>

                                    <!-- Handle partial payments for advance -->
                                    <td class="border border-dark text-center">
                                        <?php if ($_fields['payment_details']->full_advance === 'advance'): ?>
                                            Partial 1: <?= $_fields['payment_details']->partial_date1 . ' - ' . $_fields['payment_details']->partial_amount1; ?><br>
                                        <?php else: ?>
                                            Date: <?= $_fields['payment_details']->full_date; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                                <!-- Additional row for dates and other dynamic information -->
                                <tr>
                                    <td class="border border-dark text-center">Loading Date: <?= $_fields['sea_road_array'][$is_road ? 'l_date_road' : 'l_date'][1]; ?></td>
                                    <td class="border border-dark text-center">Receiving Date: <?= $_fields['sea_road_array'][$is_road ? 'r_date_road' : 'r_date'][1]; ?></td>
                                    <td class="border border-dark text-center"><?= $_fields['country']; ?></td>
                                    <td class="border border-dark text-center">
                                        <?php if ($_fields['payment_details']->full_advance === 'advance'): ?>
                                            Partial 2: <?= $_fields['payment_details']->partial_date2 . ' - ' . $_fields['payment_details']->partial_amount2; ?>
                                        <?php else: ?>
                                            Full Payment Report: <?= $_fields['payment_details']->full_report; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <?php if (isset($_fields['sea_road_report'])): ?>
                            <div class="col-12 mt-2 mb-4">
                                <div class="fs-6 fw-bold">Report</div>
                                <?php echo $_fields['sea_road_report']; ?>
                            </div>
                        <?php endif; ?>
                        <table class="table mb-0 table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="bg-dark text-white border border-white">#</th>
                                    <th class="bg-dark text-white border border-white">GOODS/SIZE/BRAND</th>
                                    <th class="bg-dark text-white border border-white">ORIGIN</th>
                                    <th class="bg-dark text-white border border-white">Qty Name/No.</th>
                                    <th class="bg-dark text-white border border-white">Qty KGs</th>
                                    <th class="bg-dark text-white border border-white">T Qty KGs</th>
                                    <th class="bg-dark text-white border border-white">T.W KGs</th>
                                    <th class="bg-dark text-white border border-white">T.N KGs</th>
                                    <th class="bg-dark text-white border border-white">Divide T No.</th>
                                    <th class="bg-dark text-white border border-white">Price</th>
                                    <th class="bg-dark text-white border border-white">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $items = $_fields['items'];
                                $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                                foreach ($items as $details) {
                                    echo '<tr>';
                                    echo '<td class="border border-dark">' . $details['sr'] . '</td>';
                                    echo '<td class="border border-dark">' . goodsName($details['goods_id']) . ' / ' . $details['size'] . ' / ' . $details['brand'] . '</td>';
                                    echo '<td class="border border-dark">' . $details['origin'] . '</td>';
                                    echo '<td class="border border-dark">' . $details['qty_no'] . ' <b> ' . $details['qty_name'] . '</b></td>';
                                    echo '<td class="border border-dark">' . $details['qty_kgs'] . '</td>';
                                    echo '<td class="border border-dark">' . round($details['total_qty_kgs'], 2) . '</td>';
                                    echo '<td class="border border-dark">' . $details['weight'] . "</td>";
                                    echo '<td class="border border-dark">' . $details['net_kgs'] . '</td>';
                                    echo '<td class="border border-dark">' . $details['total'] . ' ' . $details['divide'] . '</td>';
                                    echo '<td class="border border-dark">' . $details['rate1'] . ' ' . $details['price'] . '</td>';
                                    echo '<td class="border border-dark">' . round($details['amount'], 2);
                                    echo '<sub>' . $details['currency1'] . '</sub>';
                                    echo '</td>';
                                    // echo '<td class="text-end">' . round($details['final_amount'], 2);
                                    // echo '<sub>' . $details['currency2'] . '</sub>';
                                    // echo '</td>';
                                    echo '</tr>';

                                    $qty_no += $details['qty_no'];
                                    $qty_kgs += $details['qty_kgs'];
                                    $total_kgs += $details['total_kgs'];
                                    $total_qty_kgs += $details['total_qty_kgs'];
                                    $net_kgs += $details['net_kgs'];
                                    $total += $details['total'];
                                    $amount += $details['amount'];
                                    $final_amount += $details['final_amount'];
                                }
                                ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="border border-dark"><b>Total Amount: </b></td>
                                    <td class="border border-dark"><?= $amount; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php } ?>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="fs-6 fw-bold">BANK Details</div>
                        <?php
                        $seller_account_id = $_fields['cr_acc_id'];
                        if (isset(khaataSingle($seller_account_id)['bank_details'])) {
                            // Decode the JSON bank details
                            $bank_details = json_decode(khaataSingle($seller_account_id)['bank_details'], true);

                            echo '<div class="row">';

                            // Display the details in a 2-column format
                            echo '<div class="col-6"><b>Account Name: </b>' . (isset($bank_details['acc_name']) ? $bank_details['acc_name'] : 'N/A') . '</div>';
                            echo '<div class="col-6"><b>Account No: </b>' . (isset($bank_details['acc_no']) ? $bank_details['acc_no'] : 'N/A') . '</div>';

                            echo '<div class="col-6"><b>IBAN: </b>' . (isset($bank_details['iban']) ? $bank_details['iban'] : 'N/A') . '</div>';
                            echo '<div class="col-6"><b>Company: </b>' . (isset($bank_details['company']) ? $bank_details['company'] : 'N/A') . '</div>';

                            echo '<div class="col-6"><b>Branch Code: </b>' . (isset($bank_details['branch_code']) ? $bank_details['branch_code'] : 'N/A') . '</div>';
                            echo '<div class="col-6"><b>Currency: </b>' . (isset($bank_details['currency']) ? $bank_details['currency'] : 'N/A') . '</div>';

                            // Combine Country, State, and City in one line
                            $country = isset($bank_details['country']) ? $bank_details['country'] : 'N/A';
                            $state = isset($bank_details['state']) ? $bank_details['state'] : 'N/A';
                            $city = isset($bank_details['city']) ? $bank_details['city'] : 'N/A';
                            echo '<div class="col-12"><b>Location: </b>' . "$city, $state, $country" . '</div>';

                            // Display Address in a full-width column
                            echo '<div class="col-12"><b>Address: </b>' . (isset($bank_details['address']) ? $bank_details['address'] : 'N/A') . '</div>';

                            echo '</div>'; // Close row
                        } else {
                            echo 'Seller Bank Details Not Found!';
                        }
                        ?>
                    </div>

                    <style>
                        @media print {
                            #second-page-div {
                                page-break-before: always;
                            }
                        }
                    </style>

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
                    <div id="second-page-div"></div>
                    <script>
                        let reportData;
                    </script>
                    <?php
                    if (isset($record['reports']) && !empty($record['reports']) && $record['reports'] !== '[]') {
                        $purchase_reports = json_decode($record['reports'], true);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            echo '<div class="alert alert-danger">JSON Decode Error: ' . htmlspecialchars(json_last_error_msg()) . '</div>';
                            $purchase_reports = [];
                        }

                        if (!empty($purchase_reports)) {
                    ?>
                            <div class="p-3 mt-4">
                                <h4 class="fw-bold">Purchase Reports</h4>
                                <table class="table mb-2 table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap" colspan="4">Report Type</th>
                                            <th class="" colspan="7">Report Details</th>
                                            <th colspan="1">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($purchase_reports as $key => $value): ?>
                                            <tr>
                                                <td class="text-nowrap fw-bold" colspan="4"><?php echo ucwords(str_replace('_', ' ', $key)); ?></td>
                                                <td class="" colspan="7"><?php echo nl2br(htmlspecialchars($value)); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <script>
                                reportsData = <?php echo json_encode($purchase_reports, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                            </script>
                        <?php
                        } else {
                            echo '<strong class="p-3">No Reports Found!</strong>';
                        ?>
                            <script>
                                reportsData = '';
                            </script>
                        <?php
                        }
                    } else {
                        echo '<strong class="p-3">No Reports Found!</strong>';
                        ?>
                        <script>
                            reportsData = '';
                        </script>
                    <?php
                    }
                    ?>
                    <br><br><br><br><br><br>
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
            async function downloadAsWord() {
                // Select the element you want to convert to DOCX (use the whole document or a specific part)
                var element = document.documentElement; // This will take the whole HTML document

                // Define options (you can customize these for headers, footers, margins, etc.)
                var options = {
                    orientation: 'portrait', // Can also be 'landscape'
                    margins: {
                        top: 720, // 1 inch = 720 twips (1/20th of a point)
                        right: 720,
                        bottom: 720,
                        left: 720,
                    },
                    title: 'Document Title', // Title of the document
                };

                try {
                    // Convert the HTML to DOCX Blob
                    var docxBlob = await htmlToDocx(element, options);

                    // Trigger the download of the generated DOCX file
                    var blobUrl = URL.createObjectURL(docxBlob);
                    var a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = 'document.docx'; // Specify the name of the downloaded file
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } catch (error) {
                    console.error('Error generating DOCX:', error);
                }
            }
        </script>
    </body>

    </html>
<?php

} else {
    echo '<script>window.location.href="' . $backURL . '";</script>';
} ?>

<script>
    window.onload = () => {
        // Function to get query parameters from URL
        const getQueryParam = (param) => {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        };

        // Get the print_type from the URL
        const printType = getQueryParam('print_type');

        // Elements
        const printTypeSelect = document.getElementById('printTypeSelect');
        const customerPrintDiv = document.getElementById('customerPrint');
        const fullPrintDiv = document.getElementById('fullPrint');

        // Set the selected option based on the print_type parameter
        if (printType === 'customer') {
            printTypeSelect.value = 'customer';
            customerPrintDiv.classList.remove('d-none');
            fullPrintDiv.classList.add('d-none');
        } else {
            printTypeSelect.value = 'full';
            fullPrintDiv.classList.remove('d-none');
            customerPrintDiv.classList.add('d-none');
        }

        // Change event listener for select menu
        printTypeSelect.addEventListener('change', function() {
            const selectedType = this.value;

            // Reload the page with the new selected print type
            window.location.search = `?t_id=${getQueryParam('t_id')}&action=${getQueryParam('action')}&secret=${getQueryParam('secret')}&print_type=${selectedType}`;
        });
    };

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