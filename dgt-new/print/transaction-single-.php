<?php require_once '../connection.php';
$T = mysqli_fetch_assoc(fetch('transactions', ['id' => $_GET['t_id']]));
$T_routes = !empty($T['sea_road']) ? json_decode($T['sea_road'], true) : [];
$T_details = transactionSingle($T['id']);
$T_details['sea_road_array'] = array_merge($T_details['sea_road_array'], $T_routes);
$T_notify = ($T['type'] === 'local') ? '' : (isset($T['notify_party_details']) ? json_decode($T['notify_party_details'], true) : false);
$T_ps = ucfirst($T['p_s'] === 'p' ? 'purhcase' : 'sale');
$T['p_s'] = ucfirst($T['p_s']);
$T['transferred'] = $T['locked'] == 1 ? '<span style="padding:2px 5px;" class="border text-success rounded fw-bold border-success">Transferred</span>' : '<span style="padding:2px 5px;" class="border text-danger rounded fw-bold border-danger">Not Transferred</span>'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $T_ps . " #" . $T['id']; ?> Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 12px;
            color: black;
            background-color: white;
        }

        .container {
            /* width: 210mm; */
            padding: 10mm;
            margin: auto;
            background: white;
            border: 1px solid black;
        }

        .border-box {
            border: 1px solid black;
            border-radius: 5px;
            padding: 10px;
        }

        .section-title {
            font-weight: bold;
            font-size: 0.85rem;
            text-transform: uppercase;
            text-align: left;
            /* margin-bottom: 8px; */
            border-bottom: 1px solid black;
            padding-bottom: 5px;
        }

        .data-row {
            font-size: 0.77rem;
            margin-bottom: 5px;
        }

        .table th,
        .table td {
            border: 1px solid black !important;
        }

        .signature-box {
            border-top: 1px solid black;
            margin-top: 20px;
            text-align: center;
            padding-top: 10px;
        }

        .text-end {
            text-align: right;
        }

        .page-break {
            margin-top: 10mm;
        }

        @media print {
            .container {
                max-width: 100vw !important;
            }

            .page-break {
                page-break-before: always;
                /* Forces a page break before this section */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="border-box mb-4">
            <h4 class="text-center"><?= $T_ps; ?> Details</h4>
            <div class="text-center">
                <span class="me-2"><strong><?= $T_ps; ?> #:</strong> <?= $T['id']; ?></span>
                <span class="me-2"><strong>Date:</strong> <?= $T['_date']; ?></span>
                <span class="me-2"><strong>Type:</strong> <span style="padding:2px 5px;" class="bg-dark text-white rounded fw-bold"><?= ucwords($T['type']); ?></span></span>
                <span class="me-2"><strong>Country:</strong> <?= ucfirst($T['country']); ?></span>
                <span class="me-2"><strong>Country:</strong> <?= ucwords($T['delivery_terms']); ?></span>
                <span class="me-2"><strong>Branch:</strong> <?= branchName(ucwords($T['branch_id'])); ?></span>
                <span class="me-2"><strong>Status:</strong> <?= $T['transferred']; ?></span>
            </div>
        </div>

        <!-- Transaction Information -->


        <!-- DR/CR and Notify Party Accounts -->
        <div class="row g-3 mb-4">
            <!-- DR Account Details -->
            <div class="col-md-6">
                <div class="border-box p-3 mt-3">
                    <h6 class="section-title">DR Account
                        <sup style="padding:2px 5px; font-size:12px;" class="border text-success rounded fw-bold border-success">Sale</sup>
                    </h6>
                    <span><strong>Acc Name: </strong> <?= $T_details['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_details['cr_acc']; ?></span><br>
                    <span><strong>Company:</strong> <?= getCompanyName($T_details['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T_details['cr_acc_kd_id']), '', $T_details['cr_acc_details']); ?></span>
                </div>

                <!-- CR Account Details -->
                <div class="border-box p-3 mt-3">
                    <h6 class="section-title">CR Account
                        <sup style="padding:2px 5px; font-size:12px;" class="border text-danger rounded fw-bold border-danger">Purchase</sup>
                    </h6>
                    <span><strong>Acc Name: </strong> <?= $T_details['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_details['dr_acc']; ?></span><br>
                    <span><strong>Company:</strong> <?= getCompanyName($T_details['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T_details['dr_acc_kd_id']), '', $T_details['dr_acc_details']); ?></span>
                </div>

                <!-- Notify Party Details (if type is booking) -->
                <?php if ($T['type'] === 'booking') { ?>
                    <div class="border-box p-3 mt-3">
                        <h6 class="section-title">Notify Party</h6>
                        <span><strong>Acc Name: </strong> <?= $T_notify['np_acc_name']; ?> | <strong>Acc No: </strong> <?= $T_notify['np_acc']; ?></span><br>
                        <span><strong>Company:</strong> <?= getCompanyName($T_notify['np_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                        <span><?= str_replace(getCompanyName($T_notify['np_acc_kd_id']), '', $T_notify['np_acc_details']); ?></span>
                    </div>
                <?php } ?>
            </div>

            <!-- Seller Bank Details -->
            <div class="col-md-6">

                <!-- Third Party Bank Details -->
                <div class="border-box p-3 mt-3">
                    <h6 class="section-title">Third Party Bank Details</h6>
                    <?php
                    $TP_bank = json_decode($T['third_party_bank'], true);
                    if (!empty($TP_bank)) {
                        foreach (['acc_name', 'acc_no', 'iban', 'company', 'branch_code', 'currency', 'address'] as $field) {
                            echo '<span><strong>' . ucwords(str_replace('_', ' ', $field)) . ': </strong>' . ($TP_bank[$field] ?? 'N/A') . '</span><br>';
                        }
                        echo '<span><strong>Location: </strong>' . (($TP_bank['city'] ?? 'N/A') . ', ' . ($TP_bank['state'] ?? 'N/A') . ', ' . ($TP_bank['country'] ?? 'N/A')) . '</span><br>';
                    } else {
                        echo 'Third Party Bank Details Not Found!';
                    }
                    ?>
                </div>

                <!-- Routes Details -->
                <div class="border-box p-3 mt-3">
                    <h6 class="section-title">Routes Details</h6>
                    <?php
                    if (!empty($T['sea_road'])) {
                        echo '<b>Transfer By:</b> ' . ucfirst($T_details['sea_road_array']['sea_road']) . "<br>";
                        foreach (['l_date', 'l_port', 'r_date', 'r_port', 'l_country', 'r_country'] as $field) {
                            if (!empty($T_details['sea_road_array'][$field])) {
                                echo '<b>' . ucwords(str_replace('_', ' ', $field)) . ': </b>' . $T_details['sea_road_array'][$field] . "<br>";
                            }
                        }
                    } else {
                        echo 'Route Details Not Found!';
                    }
                    ?>
                </div>
            </div>
        </div>



        <!-- Goods Details Table -->
        <!-- Goods Details Table -->
        <?php if (!empty($T_details['items'])) { ?>
            <div class="border-box mb-4">
                <h6 class="section-title">Goods Details</h6>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Good Name</th>
                                <th>Size</th>
                                <th>Brand</th>
                                <th>Origin</th>
                                <th>Quantity</th>
                                <th>Gross Weight</th>
                                <th>Net Weight</th>
                                <th>Amount</th>
                                <?php if ($T['type'] !== 'local') { ?>
                                    <th>Final Amount</th>
                                <?php } else { ?>
                                    <th>Tax (%)</th>
                                    <th>Tax Amount</th>
                                    <th>Total with Tax</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Variables to calculate totals
                            $qty_no = $total_kgs = $net_kgs = $total = $amount = $final_amount = $total_tax_amount = $total_total_with_tax = 0;

                            $pur_d_q = fetch('transaction_items', array('parent_id' => $T['id']));
                            while ($details = mysqli_fetch_assoc($pur_d_q)) {
                                $qty_no += $details['qty_no'];
                                $total_kgs += $details['total_kgs'];
                                $net_kgs += $details['net_kgs'];
                                $total += $details['total'];
                                $amount += $details['amount'];
                                $final_amount += $details['final_amount'];
                                $total_tax_amount += (float)$details['tax_amount'];
                                $total_total_with_tax += (float)$details['total_with_tax'];
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($details['sr']); ?></td>
                                    <td><?= htmlspecialchars(goodsName($details['goods_id'])); ?></td>
                                    <td><?= $details['size']; ?></td>
                                    <td><?= $details['brand']; ?></td>
                                    <td><?= $details['origin']; ?></td>
                                    <td><?= htmlspecialchars($details['qty_no']); ?> <sub class="fw-bold"><?= htmlspecialchars($details['qty_name']); ?></sub></td>
                                    <td><?= $details['total_kgs']; ?></td>
                                    <td><?= $details['net_kgs']; ?></td>
                                    <td><?= round($details['amount'] ?? 0, 2) . '<sub>' . htmlspecialchars($details['currency1']) . '</sub>'; ?></td>
                                    <?php if ($T['type'] !== 'local') { ?>
                                        <td><?= round($details['final_amount'], 2) . '<sub>' . htmlspecialchars($details['currency2']) . '</sub>'; ?></td>
                                    <?php } else { ?>
                                        <td><?= htmlspecialchars($details['tax_percent']); ?>%</td>
                                        <td><?= round($details['tax_amount'], 2); ?></td>
                                        <td><?= round($details['total_with_tax'], 2); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>

                            <!-- Totals Row -->
                            <?php if ($qty_no > 0) { ?>
                                <tr>
                                    <th colspan="5">Totals</th>
                                    <th><?= $qty_no; ?></th>
                                    <th><?= round($total_kgs, 2); ?></th>
                                    <th><?= round($net_kgs, 2); ?></th>
                                    <th><?= round($amount, 2); ?></th>
                                    <?php if ($T['type'] !== 'local') { ?>
                                        <th><?= round($final_amount, 2); ?></th>
                                    <?php } else { ?>
                                        <th></th>
                                        <th><?= round($total_tax_amount, 2); ?></th>
                                        <th><?= round($total_total_with_tax, 2); ?></th>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>


        <div class="page-break"></div>
        <?php if (!empty($T['reports']) && $T['reports'] !== '[]') { ?>
            <div class="border-box">
                <h6 class="section-title">Reports Summary</h6>
                <div style="margin-top: 1rem;">
                    <?php
                    $purchase_reports = json_decode($T['reports'], true);
                    if (!empty($purchase_reports)) {
                        foreach ($purchase_reports as $key => $value) {
                            $report_title = ucwords(str_replace('_', ' ', $key));
                            echo '<h6>' . htmlspecialchars($report_title) . ':</h6>';
                            echo '<div style="margin-left:20px; margin-top:-6px; margin-bottom:3px;">';
                            echo nl2br(htmlspecialchars($value));
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


        <br><br><br><br><br><br>
        <!-- Totals and Signature -->
        <div class="row mt-4">
            <div class="col-6" style="position: relative;">
                <div class="signature-box" style="position:absolute; bottom:0; left:0;">
                    <p>Buyer Signature</p>
                </div>
            </div>
            <div class="col-6" style="position: relative;">
                <div class="signature-box" style="position:absolute; bottom:0;right:0;">
                    <p>Seller Signature</p>
                </div>
            </div>
        </div>

    </div>
</body>

</html>