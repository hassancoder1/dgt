<?php require_once '../connection.php';
$T = mysqli_fetch_assoc(fetch('transactions', ['id' => $_GET['t_id']]));
$T_routes = !empty($T['sea_road']) ? json_decode($T['sea_road'], true) : [];
$T_details = transactionSingle($T['id']);
$T_details['sea_road_array'] = array_merge($T_details['sea_road_array'], $T_routes);
$T_notify = ($T['type'] === 'local') ? '' : (isset($T['notify_party_details']) ? json_decode($T['notify_party_details'], true) : false);
$T_ps = ucfirst($T['p_s'] === 'p' ? 'purhcase' : 'sale');
$T['p_s'] = ucfirst($T['p_s']);
$T['transferred'] = $T['locked'] == 1 ? '<span style="padding:1px 3px;" class="border mt-2 text-success rounded fw-bold border-success">Transferred</span>' : '<span style="padding:1px 3px;" class="border mt-2 text-danger rounded fw-bold border-danger">Not Transferred</span>';
$notify_Exists = isset($T_notify) && !empty($T_notify);

if (!empty($T_details['items'])) {
    $sr_details = 1;
    $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = $total_tax_amount = $total_total_with_tax = 0;
    $pur_d_q = fetch('transaction_items', array('parent_id' => $T['id']));
    $items = [];
    $firstItem = null;
    while ($item = mysqli_fetch_assoc($pur_d_q)) {
        $items[] = $item;
        if ($firstItem === null) {
            $firstItem = $item;
        }
        $qty_no += $item['qty_no'];
        $qty_kgs += $item['qty_kgs'];
        $total_kgs += $item['total_kgs'];
        $total_qty_kgs += $item['total_qty_kgs'];
        $net_kgs += $item['net_kgs'];
        $total += $item['total'];
        $amount += $item['amount'];
        $final_amount += $item['final_amount'];
        $total_tax_amount += (float)$item['tax_amount'];
        $total_total_with_tax += (float)$item['total_with_tax'];
        $curr = $item['currency1'];
    }
}

$print_type = isset($_GET['print_type']) ? '&print_type=' . $_GET['print_type'] : '';
$print_url = 'print/transaction-single?t_id=' . $T['id'] . $print_type . "&timestamp=" . $_GET['timestamp'];
$isInvoice = $_GET['print_type'] === 'invoice' ? ' Invoice' : '';

$P = json_decode(json_encode($T_details['payment_details']), true);
$Ptype = $P['full_advance'] === 'advance' ? strtoupper($P['full_advance']) . " " . $P['pct_value'] . "%" : strtoupper($P['full_advance']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $T_ps . " #" . $T['sr'] . $isInvoice; ?> Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/fonts/lexend.css">
    <style>
        * {
            font-family: 'Lexend', serif;
        }

        body {
            font-size: 12px;
            color: black;
            background-color: white;
            position: relative;
        }

        .page-break {
            margin-top: 10mm;
        }

        .container {
            width: 210mm;
        }

        @media print {
            .container {
                max-width: 100vw !important;
            }

            .hide-on-print {
                display: none;
            }

            .page-break {
                page-break-before: always;
                /* Forces a page break before this section */
            }
        }

        .signature-box {
            border-top: 1px solid black;
            margin-top: 20px;
            text-align: center;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container border p-3 pb-0 rounded m-3 mx-auto">
        <div class="position-absolute top-0 end-0 mt-2 me-3 d-flex align-items-center gap-2">
            <select name="print_type" id="print_type" class="form-select form-select-sm hide-on-print">
                <option value="contract" <?= $_GET['print_type'] === 'contract' ? 'selected' : ''; ?>>Contract Print</option>
                <option value="invoice" <?= $_GET['print_type'] === 'invoice' ? 'selected' : ''; ?>>Invoice Print</option>
            </select>
            <div class="dropdown hide-on-print">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= str_replace('print/', '', $print_url); ?>')">
                            <i class="fas text-secondary fa-print me-2"></i> Print
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')">
                            <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')">
                            <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')">
                            <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')">
                            <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php if (empty($isInvoice)) { ?>


            <!-- <div class="text-center mt-4">
                <img src="logo.jpg" style="border-radius: 100%; width:120px;" alt="">
                <h1 class="text-center mb-4 mt-2"><?= $T_ps; ?> Contract</h1>
            </div> -->
            <div class="d-flex justify-content-between align-items-center mt-2 mb-5">
                <div class="col-md-4">
                    <div>
                        <img src="logo.jpg" style="border-radius: 100%; width:80px;" alt="">
                    </div>
                    <span class="fw-bold text-dark"><a href="https://www.dgt.llc" class="text-dark">www.dgt.llc</a> | <a href="mailto:dgtllc@dgt.llc" class="text-dark">Email: dgtllc@dgt.llc</a></span><br>
                    <span class="fw-bold"> <a href="tel:+971433333" class="text-dark">Office#+971433333</a> / <a href="tel:+971544816664" class="text-dark">M#+971544816664</a></span>
                </div>
                <div class="col-md-4">
                    <h4 class="text-center mb-4 mt-2"><?= $T_ps; ?> Contract</h4>
                </div>
                <div class="col-md-4 text-end">
                    <strong><?= $T_ps; ?> #:</strong> <?= $T['sr']; ?><br>
                    <strong>Date:</strong> <?= $T['_date']; ?><br>
                    <strong>Country:</strong> <?= ucfirst($T['country']); ?><br>
                    <strong>Branch:</strong> <?= branchName(ucwords($T['branch_id'])); ?><br>
                    <strong>Status:</strong> <?= $T['transferred']; ?><br>
                </div>
            </div>

            <div class="row mb-3 mt-5">
                <div class="col-6 ">
                    <h5>Sale</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $T_details['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_details['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($T_details['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T_details['cr_acc_kd_id']), '', $T_details['cr_acc_details']); ?></span>
                </div>
                <div class="col-6">
                    <h5>Purchase</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $T_details['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_details['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($T_details['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T_details['dr_acc_kd_id']), '', $T_details['dr_acc_details']); ?></span>
                </div>

            </div>

            <div class="row mb-3 align-items-center">
                <?php if ($notify_Exists) { ?>
                    <div class="col-6">
                        <h5>Notify Party</h5>
                        <div class="hide-on-print">
                            <span><strong>Acc Name: </strong> <?= $T_notify['np_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_notify['np_acc']; ?></span><br>
                        </div>
                        <span><strong>Company:</strong> <?= getCompanyName($T_notify['np_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                        <span><?= str_replace(getCompanyName($T_notify['np_acc_kd_id']), '', $T_notify['np_acc_details']); ?></span>
                    </div>
                <?php } ?>
                <div class="col-6 text-end">
                    <strong>Quantity:</strong> <?= $qty_no; ?><br>
                    <strong>Total Gross Weight:</strong> <?= round($total_kgs, 2); ?><br>
                    <strong>Total Net Weight:</strong> <?= round($net_kgs, 2); ?><br>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="bg-dark text-white">ORIGIN</th>
                            <?php
                            if ($T['type'] !== 'local') {
                                echo '<th class="bg-dark text-white">SHIP</th>';
                            } else {
                                echo '<th class="bg-dark text-white">LOCAL Transfer</th>';
                            }
                            ?>
                            <th class="bg-dark text-white">Loading</th>
                            <th class="bg-dark text-white">Receiving</th>
                            <th class="bg-dark text-white">Delivery Terms</th>
                            <th class="bg-dark text-white">Payment Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $firstItem['origin']; ?></td>
                            <?php
                            if ($T['type'] !== 'local') {
                                echo '<td>' . strtoupper($T_details['sea_road']) . '</td>';
                                echo '<td>' . strtoupper($T_details['sea_road_array']['l_port']) . '</td>';
                                echo '<td>' . strtoupper($T_details['sea_road_array']['r_port']) . '</td>';
                            } else {
                                echo '<td>' . strtoupper($T_details['sea_road_array']['route']) . ' Transfer</td>';
                                echo '<td>' . strtoupper(($T_details['sea_road_array']['loading_company_name'] ?? '')) . '</td>';
                                echo '<td>' . strtoupper(($T_details['sea_road_array']['receiving_company_name'] ?? '')) . '</td>';
                            }
                            echo '<td>' . strtoupper($T['delivery_terms']) . '</td>';
                            echo '<td>' . $Ptype . '</td>';
                            ?>
                </table>
            </div>

            <?php if (!empty($T_details['items'])) { ?>
                <table class="table table-hover mb-0 mt-3">
                    <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>GOODS / SIZE / BRAND / ORIGIN</th>
                            <th>QTY</th>
                            <th>KGs</th>
                            <th>NET KGs</th>
                            <th>TOTAL</th>
                            <th>PRICE</th>
                            <th>AMOUNT</th>
                            <?php if ($T['type'] !== 'local') { ?>
                                <!-- <th class="text-end">FINAL</th> -->
                            <?php } else {; ?>
                                <th>Tax%</th>
                                <th>Tax.Amt</th>
                                <th>Final Amt</th>
                            <?php } ?>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($items as $details) {
                            $details_id = $details['id'];
                            echo '<tr>';
                            echo '<td>' . $details['sr'] . '</td>';
                            echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                            echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                            echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                            echo '<td>' . round($details['net_kgs'], 2);
                            echo '<sub>' . $details['divide'] . '</sub>';
                            echo '</td>';
                            echo '<td>' . $details['total'] . '</td>';
                            echo '<td>' . $details['rate1'];
                            echo '<sub>' . $details['currency1'] . '</sub></td>';
                            echo '<td>' . round($details['amount'], 2);
                            echo '</td>';
                            if ($T['type'] !== 'local') {
                                // echo '<td class="text-end">' . round($details['final_amount'], 2);
                                // echo '<sub>' . $details['currency2'] . '</sub>';
                            } else {
                                echo '<td>' . $details['tax_percent'] . "%";
                                echo '<td>' . $details['tax_amount'];
                                echo '<td>' . $details['total_with_tax'];
                            };
                            echo '</td>';
                            echo '</tr>';
                            $sr_details++;
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row mt-4 align-items-center">
                    <!-- Left Column: Bank Details -->
                    <div class="col-8">
                        <div class="fs-6 fw-bold">Third Party BANK Details</div>
                        <?php
                        $third_party_bank = json_decode($T['third_party_bank'], true);
                        if (!empty($third_party_bank)) {
                            echo '<div class="row">';
                            echo '<div class="col-12"><b>Account Name: </b>' . (isset($third_party_bank['acc_name']) ? $third_party_bank['acc_name'] : 'N/A') . '</div>';
                            echo '<div class="col-12"><b>Bank Name: </b>' . (isset($third_party_bank['bank_name']) ? $third_party_bank['bank_name'] : 'N/A') . '</div>';
                            echo '<div class="col-12"><b>IBAN: </b>' . (isset($third_party_bank['iban']) ? $third_party_bank['iban'] : 'N/A') . '</div>';
                            echo '<div class="col-12"><b>Branch Code: </b>' . (isset($third_party_bank['branch_code']) ? $third_party_bank['branch_code'] : 'N/A') . '</div>';
                            echo '<div class="col-12"><b>Location: </b>' . (isset($third_party_bank['city']) ? $third_party_bank['city'] : 'N/A') . ', ' . (isset($third_party_bank['state']) ? $third_party_bank['state'] : 'N/A') . ', ' . (isset($third_party_bank['country']) ? $third_party_bank['country'] : 'N/A') . '</div>';
                            echo '<div class="col-12"><b>Address: </b>' . (isset($third_party_bank['address']) ? $third_party_bank['address'] : 'N/A') . '</div>';
                            echo '</div>';
                        } else {
                            echo 'Not Found!';
                        }
                        ?>
                    </div>
                    <?php if ($qty_no > 0) { ?>

                        <!-- Right Column: Totals -->
                        <div class="col-4 text-end">
                            <strong>Total Amount:</strong> <?= round($amount, 2); ?><br>
                            <!-- <strong>Sub-Total:</strong> <?= round($amount, 2); ?><br> -->
                            <?php if ($T['type'] === 'local') { ?>
                                <strong>Total Tax:</strong> <?= round($total_tax_amount, 2); ?><br>
                                <strong>Final Amount:</strong> <?= round($total_total_with_tax, 2); ?><br>
                            <?php } else { ?>
                                <strong>Final Amount:</strong> <?= round($final_amount, 2); ?><br>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="text-center my-5 py-2 rounded" style="background-color: #fff3cd;">
                <span class="fw-bold">Reports and Signatures are on the last page!</span>
            </div>

            <div class="page-break"></div>
            <?php if (!empty($T['reports']) && $T['reports'] !== '[]') { ?>
                <div class="border-box">
                    <h6 class="section-title">Reports Summary</h6>
                    <div style="margin-top: 1rem;">
                        <?php
                        $purchase_reports = json_decode($T['reports'], true);
                        $company_details = '';
                        if (!empty($purchase_reports)) {
                            foreach ($purchase_reports as $key => $value) {
                                $report_title = ucwords(str_replace('_', ' ', $key));
                                echo '<h6>' . htmlspecialchars($report_title) . ':</h6>';
                                echo '<div style="margin-left:20px; margin-top:-6px; margin-bottom:3px;">';
                                echo nl2br(htmlspecialchars($value));
                                if ($key == 'company_details') {
                                    $company_details = nl2br(htmlspecialchars($value));
                                }
                                echo '</div>';
                            }
                        } else {
                            echo '<p>No Reports Found!</p>';
                        }
                        ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="border-box">
                    <h6 class="section-title">Reports Summary</h6>
                    <p style="margin-top: 1rem;">No Reports Found!</p>
                </div>
            <?php } ?>
            <form action="?t_id=<?= $T['id']; ?>&timestamp=<?= $_GET['timestamp']; ?>&<?= $print_type; ?>" method="POST">
                <input type="hidden" name="p_id_hidden" value="<?= $T['id']; ?>">
                <input type="text" id="inputBox" name="inputBox" class="form-control form control-sm d-none" required>
                <button type="submit" name="companyReport" onclick="document.querySelector('#inputBox').classList.toggle('d-none');" class="btn btn-outline-dark mt-3 hide-on-print" style="padding:2px 4px; font-size:12px;">Company Report</button>
            </form>

            <br><br><br><br><br><br>
            <!-- Totals and Signature -->
            <div class="d-flex align-items-center text-center justify-content-between mt-4 px-3 pb-3">
                <div>
                    <div class="signature-box">
                        <span>Buyer Signature</span><br>
                        <b><?= getCompanyName($T_details['dr_acc_kd_id']) ?? 'Not Found!'; ?></b>
                    </div>
                </div>
                <?php if ($notify_Exists) { ?>
                    <div>
                        <div class="signature-box">
                            <span>Notify Party Signature</span><br>
                            <b><?= getCompanyName($T_notify['np_acc_kd_id']) ?? 'Not Found!'; ?></b>
                        </div>
                    </div>
                <?php } ?>
                <div>
                    <div class="signature-box">
                        <span>Seller Signature</span><br>
                        <b><?= getCompanyName($T_details['cr_acc_kd_id']) ?? 'Not Found!'; ?></b>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="row align-items-center">
                <div class="text-center col-12">
                    <h1 class="text-center mb-4 mt-2"><?= $T_ps; ?> Invoice</h1>
                </div>
                <div class="col-6 border-top py-2 pt-4 border-end">
                    <h5>Seller</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $T_details['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_details['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($T_details['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T_details['cr_acc_kd_id']), '', $T_details['cr_acc_details']); ?></span>
                </div>
                <div class="col-6 border-top py-2 pt-2">
                    <div class="d-flex justify-content-between align-items-end mb-1">
                        <div class="col-md-4">
                            <div>
                                <img src="logo.jpg" style="border-radius: 100%; width:80px;" alt="">
                            </div>
                            <span class="fw-bold text-dark"><a href="https://www.dgt.llc" class="text-dark">www.dgt.llc</a><br><a href="mailto:dgtllc@dgt.llc" class="text-dark">Email: dgtllc@dgt.llc</a></span><br>
                            <span class="fw-bold"> <a href="tel:+971433333" class="text-dark">Office#+971433333</a><br><a href="tel:+971544816664" class="text-dark">M#+971544816664</a></span>
                        </div>

                        <div class="col-md-4 text-end">
                            <strong class="fs-6">Invoice #:</strong class="fs-6"> <?= $T['id']; ?><br>
                            <strong class="fs-6">Date:</strong class="fs-6"> <?= $T['_date']; ?><br>
                            <strong class="fs-6">Type:</strong class="fs-6"> <?= ucwords($T['type']); ?><br>
                            <strong class="fs-6">Branch:</strong class="fs-6"> <?= branchName(ucwords($T['branch_id'])); ?><br>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyer Info -->
            <div class="row">
                <div class="col-6 border-top py-2 border-end">
                    <h5>Buyer</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $T_details['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_details['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($T_details['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T_details['dr_acc_kd_id']), '', $T_details['dr_acc_details']); ?></span>
                </div>
                <div class="col-6 border-top py-2">
                    <?php if ($notify_Exists) { ?>
                        <h5>Notify Party</h5>
                        <div class="hide-on-print">
                            <span><strong>Acc Name: </strong> <?= $T_notify['np_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_notify['np_acc']; ?></span><br>
                        </div>
                        <span><strong>Company:</strong> <?= getCompanyName($T_notify['np_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                        <span><?= str_replace(getCompanyName($T_notify['np_acc_kd_id']), '', $T_notify['np_acc_details']); ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-6 border-top border-bottom py-2 border-end">
                    <?php
                    // Initialize arrays for Loading, Receiving, and Delivery details
                    $L = $R = $D = [];
                    $T['sea_road'] = json_decode($T['sea_road'], true);
                    if (!empty($T['sea_road']['route'])) {
                        // When 'route' exists
                        $L[] = ["Loading Company Name", $T['sea_road']['loading_company_name'] ?? ''];
                        $R[] = ["Receiving Company Name", $T['sea_road']['receiving_company_name'] ?? ''];
                        $D[] = ["Delivery Date", $T['sea_road']['receiving_date'] ?? ''];
                    } else {
                        // For 'sea' or road transport
                        if ($T['sea_road']['sea_road'] === 'sea') {
                            $L[] = ["Port of Loading", $T['sea_road']['l_port'] ?? ''];
                            $R[] = ["Port of Discharge", $T['sea_road']['r_port'] ?? ''];
                        } else {
                            $L[] = ["Border of Loading", $T['sea_road']['l_border_road'] ?? ''];
                            $R[] = ["Border of Discharge", $T['sea_road']['r_border_road'] ?? ''];
                        }
                        $D[] = ["Delivery Date", $T['sea_road']['r_date'] ?? $T['sea_road']['r_date_road'] ?? ''];
                    }
                    ?>
                    <strong>Delivery Date:</strong> <?= !empty($D[0][1]) ? my_date($D[0][1]) : 'N/A'; ?><br>
                    <strong>Method of Dispatch:</strong> <?= $T['type'] !== 'local'
                                                                ? strtoupper($T_details['sea_road'] ?? 'N/A')
                                                                : strtoupper($T['sea_road']['route'] ?? 'N/A') . ' Transfer'; ?><br>
                    <strong>Terms / Method of Payment:</strong> <?= $Ptype ?? 'N/A'; ?> <br>
                    <strong>Country of Origin:</strong> <?= ucfirst($firstItem['origin'] ?? 'N/A'); ?><br>
                    <strong>Delivery Terms:</strong> <?= $T['delivery_terms'] ?? 'N/A'; ?><br>
                </div>

                <!-- Right Column -->
                <div class="col-6 border-top border-bottom py-2">
                    <strong><?= $L[0][0] ?? 'N/A'; ?></strong><br> <?= $L[0][1] ?? 'N/A'; ?><br>
                    <strong><?= $R[0][0] ?? 'N/A'; ?></strong><br> <?= $R[0][1] ?? 'N/A'; ?><br>
                </div>
            </div>


            <table class="table border border-start-0 border-end-0 mt-2">
                <thead class="table-light border">
                    <tr>
                        <th class="border-end">Description of Goods</th>
                        <th class="border-end">Unit Quantity</th>
                        <th class="border-end">Unit Type</th>
                        <th class="border-end">Price</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qty_no = $curr = $amount = 0;
                    foreach ($items as $details) {
                        $details_id = $details['id'];
                        echo '<tr>';
                        echo '<td class="border-end">' . goodsName($details['goods_id']) . ' / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                        echo '<td class="border-end">' . $details['qty_no'] . '</td>';
                        echo '<td class="border-end">' . $details['qty_name'] . '</td>';
                        echo '<td class="border-end">' . $details['rate1'] . '<sub>' . $details['currency1'] . '</sub></td>';
                        echo '<td>' . round($details['amount'], 2) . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="border-end"><strong>Total: </strong></td>
                        <td class="border-end"><strong><?= $qty_no; ?></strong></td>
                        <td class="border-end" colspan="2"></td>
                        <td><?= round($amount, 2) . " " . $curr; ?></td>
                    </tr>
                </tfoot>
            </table>


            <!-- Additional Information -->
            <div class="row">
                <!-- Bank Details Section -->
                <div class="col-md-6 p-3 border-top border-end">
                    <h6 class="fw-bold">BANK Details</h6>
                    <?php
                    $third_party_bank = json_decode($T['third_party_bank'], true);
                    if (!empty($third_party_bank)) {
                        echo '<div class="row">';
                        echo '<div class="col-12"><b>Account Name:</b> ' . ($third_party_bank['acc_name'] ?? 'N/A') . '</div>';
                        echo '<div class="col-12"><b>Bank Name:</b> ' . ($third_party_bank['bank_name'] ?? 'N/A') . '</div>';
                        echo '<div class="col-12"><b>IBAN:</b> ' . ($third_party_bank['iban'] ?? 'N/A') . '</div>';
                        echo '<div class="col-12"><b>Branch Code:</b> ' . ($third_party_bank['branch_code'] ?? 'N/A') . '</div>';
                        echo '<div class="col-12"><b>Location:</b> ' . ($third_party_bank['city'] ?? 'N/A') . ', ' . ($third_party_bank['state'] ?? 'N/A') . ', ' . ($third_party_bank['country'] ?? 'N/A') . '</div>';
                        echo '<div class="col-12"><b>Address:</b> ' . ($third_party_bank['address'] ?? 'N/A') . '</div>';
                        echo '</div>';
                    } else {
                        echo '<p>Bank details not found!</p>';
                    }
                    ?>
                </div>

                <!-- Signature Section -->
                <div class="col-md-6 p-3 border-top">
                    <br><br><br><br>
                    <div class="d-flex justify-content-between">
                        <!-- Buyer Signature -->
                        <div class="text-center signature-box">
                            <strong>Buyer Signature</strong><br>
                            <span><?= getCompanyName($T_details['dr_acc_kd_id']) ?? 'Not Found!'; ?></span>
                        </div>
                        <!-- Seller Signature -->
                        <div class="text-center signature-box">
                            <strong>Seller Signature</strong><br>
                            <span><?= getCompanyName($T_details['cr_acc_kd_id']) ?? 'Not Found!'; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Copyright Section -->
                <div class="col-12 p-3 border-top text-center">
                    <small class="text-muted">
                        &copy; <?= date('Y'); ?> DAMAAN GENERAL TRADING (DGT). <?= $T_ps . " #" . $T['id']; ?> Invoice generated on <?= $_GET['timestamp']; ?>.
                    </small>
                </div>
            </div>

        <?php } ?>
    </div>
    <br><br>
    <div class="position-fixed top-0 start-0 w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(25, 26, 25, 0.4); z-index: 60;" id="processingScreen">
        <div class="spinner-border text-white" style="width: 5rem; height: 5rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <script>
        <?php include("../assets/js/jquery-3.7.1.min.js"); ?>

        function openAndPrint(url) {
            const newWindow = window.open(
                url,
                '_blank',
                'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + screen.width + ',height=' + screen.height
            );
            newWindow.onload = () => {
                newWindow.print();
            };
        }

        function getFileThrough(fileType, url) {
            $('#processingScreen').toggleClass('d-none d-flex');
            let formattedFileName = url
                .split('?')[0] // Remove query parameters and their values
                .replace(/^print\//, '')
                .replace(/-main|-print$/, '')
                .trim();
            let formattedName = formattedFileName
                .replace(/-/g, ' ')
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(' ');

            $.ajax({
                url: `${window.location.protocol}//${window.location.host}/ajax/generateFile.php`,
                type: 'post',
                data: {
                    filetype: fileType,
                    pageURL: url
                },
                success: function(response) {
                    $('#processingScreen').toggleClass('d-none d-flex');
                    try {
                        const result = JSON.parse(response);
                        if (result.fileURL) {
                            const fileURL = result.fileURL;
                            if (fileType === 'pdf' || fileType === 'word') {
                                fetch(fileURL)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(`HTTP error! Status: ${response.status}`);
                                        }
                                        return response.blob();
                                    })
                                    .then(blob => {
                                        const currentTime = Date.now();
                                        const fileExtension = fileType === 'pdf' ? 'pdf' : 'docx';
                                        const fileName = `Print-${formattedFileName}${currentTime}.${fileExtension}`;
                                        const downloadLink = document.createElement('a');
                                        const objectURL = URL.createObjectURL(blob);
                                        downloadLink.href = objectURL;
                                        downloadLink.download = fileName;
                                        document.body.appendChild(downloadLink);
                                        downloadLink.click();
                                        URL.revokeObjectURL(objectURL);
                                        document.body.removeChild(downloadLink);
                                    })
                                    .catch(error => {
                                        console.error('Error downloading file:', error);
                                        alert('Failed to download the file.');
                                    });
                            } else if (fileType === 'whatsapp') {
                                const whatsappURL = `https://wa.me/?text=Your+file+${encodeURIComponent(formattedName)}+is+ready!+Download+it+here:+${encodeURIComponent(fileURL)}`;
                                window.open(whatsappURL, '_blank');
                            } else if (fileType === 'email') {
                                const emailURL = `mailto:?subject=Your+Requested+File+-+${encodeURIComponent(formattedName)}&body=Hello,%0A%0AYour+file+${encodeURIComponent(formattedName)}+is+ready+for+download!%0A%0AAccess+it+here:+${encodeURIComponent(fileURL)}`;
                                window.open(emailURL, '_blank');
                            }

                        } else {
                            alert('Failed to retrieve the file URL.');
                            console.log(result.error);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Invalid response format received from the server.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Hide the processing screen
                    $('#processingScreen').toggleClass('d-none d-flex');

                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while processing your request. Please refresh and try again.');
                }
            });
        }
        $('#print_type').change(function() {
            window.location.href = '?t_id=<?= $T['id']; ?>&print_type=' + $(this).val() + '&timestamp=<?= $_GET['timestamp']; ?>';
        });
        <?php include("../assets/bs/js/bootstrap.bundle.min.js"); ?>
    </script>
</body>

</html>
<?php
if (isset($_POST['companyReport'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $reportType = 'company_details';
    $report = htmlspecialchars($_POST['inputBox']);
    $report = str_replace(array("\n", "\r", "\r\n"), ' ', $report);
    $records = fetch('transactions', ['id' => $id]);
    $record = mysqli_fetch_assoc($records);
    $reports = isset($record['reports']) && !empty($record['reports']) ? json_decode($record['reports'], true) : [];
    $reports[$reportType] = $report;
    $data = ['reports' => json_encode($reports)];
    if (update('transactions', $data, ['id' => $id])) {
        $type = 'success';
        $msg = 'Report Successfully Updated.';
    } else {
        $type = 'danger';
        $msg = 'DB Update Failed';
    }
    messageNew($type, $pageURL . '?t_id=' . $id . '&timestamp=' . $_GET['timestamp'] . '&' . $print_type, $msg);
}
?>