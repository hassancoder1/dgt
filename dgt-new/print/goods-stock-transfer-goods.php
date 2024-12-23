<?php require_once '../connection.php';
$unique_code = $_GET['unique_code'];
[$Ttype, $Tcat, $Troute, $TID, $BLUID] = decode_unique_code($unique_code, 'all');
$data = mysqli_fetch_assoc(fetch('data_copies', ['unique_code' => $unique_code]));
$Tdata = json_decode($data['tdata'], true);
$Tdata['id'] = $TID;
$Ldata = json_decode($data['ldata'], true);
$T_ps = ucfirst($Ttype === 'p' ? 'purhcase' : 'sale');
$Ttype = ucfirst($Ttype);
$Tdata['transferred'] = $Tdata['locked'] == 1 ? '<span style="padding:1px 3px;" class="border mt-2 text-success rounded fw-bold border-success">Transferred</span>' : '<span style="padding:1px 3px;" class="border mt-2 text-danger rounded fw-bold border-danger">Not Transferred</span>';
$notify_Exists = isset($Tdata['np_acc']) && !empty($Tdata['np_acc']);

$sr_details = 1;
$qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = $total_tax_amount = $total_total_with_tax = 0;
$firstItem = null;
$items = [];
$transType = '';
if ($Ttype === 'P') {
    $transType = 'sold_to';
} else {
    $transType = 'purchased_in';
}
foreach ($Ldata['goods'] as $l_ID => $good) {
    $items[] = $good;
    if ($firstItem === null) {
        $firstItem = $good;
    }
    if (isset($good['agent'][$transType])) {
        foreach ($good['agent'][$transType] as $Transferred) {
            $TransData = explode('~', $Transferred);
            $qty_no += $TransData[4];
        }
    }
    $qty_no += $good['goods_json']['qty_no'];
    $qty_kgs += $good['goods_json']['qty_kgs'];
    $total_kgs += $good['goods_json']['total_kgs'];
    $total_qty_kgs += $good['goods_json']['total_qty_kgs'];
    $net_kgs += $good['goods_json']['net_kgs'];
    $total += $good['goods_json']['total'];
    $amount += $good['goods_json']['amount'];
    $final_amount += $good['goods_json']['final_amount'];
    $total_tax_amount += (float)$good['goods_json']['tax_amount'];
    $total_total_with_tax += (float)$good['goods_json']['total_with_tax'];
    $curr = $good['goods_json']['currency1'];
}
$print_type = isset($_GET['print_type']) ? '&print_type=' . $_GET['print_type'] : '';
$print_url = 'print/goods-stock-transfer-goods?unique_code=' . $unique_code . $print_type . "&timestamp=" . $_GET['timestamp'];
$isInvoice = $_GET['print_type'] === 'invoice' ? ' Invoice' : '';

$P = $Tdata['payment_details'];
$Ptype = $P['full_advance'] === 'advance' ? strtoupper($P['full_advance']) . " " . $P['pct_value'] . "%" : strtoupper($P['full_advance']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $T_ps . " #" . $TID . (($Tcat === 'l' ? ' UID ' : ' BL ') . $BLUID) . $isInvoice; ?> Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-size: 12px;
            color: black;
            background-color: white;
            position: relative;
        }

        /* .page-break {
            margin-top: 10mm;
        } */

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
                    <strong><?= $T_ps; ?> #:</strong> <?= $Tdata['id']; ?><br>
                    <strong>Date:</strong> <?= $Tdata['_date']; ?><br>
                    <strong>Country:</strong> <?= ucfirst($Tdata['country']); ?><br>
                    <strong>Branch:</strong> <?= branchName(ucwords($Tdata['branch_id'])); ?><br>
                    <strong>Status:</strong> <?= $Tdata['transferred']; ?><br>
                </div>
            </div>

            <div class="row mb-3 mt-5">
                <div class="col-6 ">
                    <h5>Sale</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $Tdata['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $Tdata['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($Tdata['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($Tdata['cr_acc_kd_id']), '', $Tdata['cr_acc_details']); ?></span>
                </div>
                <div class="col-6">
                    <h5>Purchase</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $Tdata['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $Tdata['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($Tdata['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($Tdata['dr_acc_kd_id']), '', $Tdata['dr_acc_details']); ?></span>
                </div>

            </div>

            <div class="row mb-3 align-items-center">
                <?php if ($notify_Exists) { ?>
                    <div class="col-6">
                        <h5>Notify Party</h5>
                        <div class="hide-on-print">
                            <span><strong>Acc Name: </strong> <?= $Tdata['np_acc_name']; ?> | <strong>Acc No: </strong> <?= $Tdata['np_acc']; ?></span><br>
                        </div>
                        <span><strong>Company:</strong> <?= getCompanyName($Tdata['np_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                        <span><?= str_replace(getCompanyName($Tdata['np_acc_kd_id']), '', $Tdata['np_acc_details']); ?></span>
                    </div>
                <?php } else {
                    echo '<div class="col-6"></div>';
                } ?>
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
                            if ($Tdata['type'] !== 'local') {
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
                            if ($Tdata['type'] !== 'local') {
                                echo '<td>' . strtoupper($Tdata['sea_road']) . '</td>';
                                echo '<td>' . strtoupper($Tdata['l_port']) . '</td>';
                                echo '<td>' . strtoupper($Tdata['r_port']) . '</td>';
                            } else {
                                echo '<td>' . strtoupper($Tdata['route']) . ' Transfer</td>';
                                echo '<td>' . strtoupper(($Tdata['loading_company_name'] ?? '')) . '</td>';
                                echo '<td>' . strtoupper(($Tdata['receiving_company_name'] ?? '')) . '</td>';
                            }
                            echo '<td>' . strtoupper($Tdata['delivery_terms'] ?? 'Not Added!') . '</td>';
                            echo '<td>' . $Ptype . '</td>';
                            ?>
                </table>
            </div>

            <?php if (!empty($items)) { ?>
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
                            <?php if ($Tdata['type'] !== 'local') { ?>
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
                        foreach ($Ldata['goods'] as $details) {
                            $details_id = $details['goods_json']['id'];
                            echo '<tr>';
                            echo '<td>' . $details['goods_json']['sr'] . '</td>';
                            echo '<td><a class="text-dark">' . goodsName($details['goods_json']['goods_id']) . '</a> / ' . $details['goods_json']['size'] . ' / ' . $details['goods_json']['brand'] . ' / ' . $details['goods_json']['origin'] . '</td>';
                            echo '<td>' . $details['goods_json']['qty_no'] . '<sub>' . $details['goods_json']['qty_name'] . '</sub></td>';
                            echo '<td>' . round($details['goods_json']['total_kgs'], 2) . '</td>';
                            echo '<td>' . round($details['goods_json']['net_kgs'], 2);
                            echo '<sub>' . $details['goods_json']['divide'] . '</sub>';
                            echo '</td>';
                            echo '<td>' . $details['goods_json']['total'] . '</td>';
                            echo '<td>' . $details['goods_json']['rate1'];
                            echo '<sub>' . $details['goods_json']['currency1'] . '</sub></td>';
                            echo '<td>' . round($details['goods_json']['amount'], 2);
                            echo '</td>';
                            if ($Tdata['type'] !== 'local') {
                                // echo '<td class="text-end">' . round($details['goods_json']['final_amount'], 2);
                                // echo '<sub>' . $details['goods_json']['currency2'] . '</sub>';
                            } else {
                                echo '<td>' . $details['goods_json']['tax_percent'] . "%";
                                echo '<td>' . $details['goods_json']['tax_amount'];
                                echo '<td>' . $details['goods_json']['total_with_tax'];
                            };
                            echo '</td>';
                            echo '</tr>';
                            $sr_details++;
                            if (isset($details['agent'][$transType])) {
                                foreach ($details['agent'][$transType] as $Transferred) {
                                    $TransData = explode('~', $Transferred);
                                    echo '<tr>';
                                    echo '<td> <i class="fas fa-arrow-right"></i> </td>';
                                    echo '<td>  ' . ($transType === 'sold_to' ? 'Sold To => S# ' : 'Purchased From => P# ') . decode_unique_code($TransData[0], 'TID') . '</td>';
                                    echo '<td>' . $TransData[4] . '</td>';
                                    echo '<td>' . $TransData[6] . '</td>';
                                    echo '<td>' . $TransData[7] . '</td>';
                                    echo '</tr>';
                                    $qty_no += $TransData[4];
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <div class="row mt-4 align-items-center">
                    <!-- Left Column: Bank Details -->
                    <div class="col-8">
                        <div class="fs-6 fw-bold">Third Party BANK Details</div>
                        <?php
                        $third_party_bank = $Tdata['third_party_bank'];
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
                        <div class="col-4 text-end">
                            <strong>Total Amount:</strong> <?= round($amount, 2); ?><br>
                            <!-- <strong>Sub-Total:</strong> <?= round($amount, 2); ?><br> -->
                            <?php if ($Tdata['type'] === 'local') { ?>
                                <strong>Total Tax:</strong> <?= round($total_tax_amount, 2); ?><br>
                                <strong>Final Amount:</strong> <?= round($total_total_with_tax, 2); ?><br>
                            <?php } else { ?>
                                <strong>Final Amount:</strong> <?= round($final_amount, 2); ?><br>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>

            <!-- <div class="text-center my-5 py-2 rounded" style="background-color: #fff3cd;">
                <span class="fw-bold">Reports and Signatures are on the last page!</span>
            </div>

            <div class="page-break"></div>
            <?php if (!empty($Tdata['reports']) && $Tdata['reports'] !== null) { ?>
                <div class="border-box">
                    <h6 class="section-title">Reports Summary</h6>
                    <div style="margin-top: 1rem;">
                        <?php
                        $purchase_reports = $Tdata['reports'];
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
            <form action="?unique_code=<?= $unique_code; ?>&timestamp=<?= $_GET['timestamp']; ?>&<?= $print_type; ?>" method="POST">
                <input type="hidden" name="unique_code_hidden" value="<?= $unique_code; ?>">
                <input type="text" id="inputBox" name="inputBox" class="form-control form control-sm d-none" required>
                <button type="submit" name="companyReport" onclick="document.querySelector('#inputBox').classList.toggle('d-none');" class="btn btn-outline-dark mt-3 hide-on-print" style="padding:2px 4px; font-size:12px;">Company Report</button>
            </form> -->

            <br><br><br><br><br><br>
            <!-- Totals and Signature -->
            <div class="d-flex align-items-center text-center justify-content-between mt-4 px-3 pb-3">
                <div>
                    <div class="signature-box">
                        <span>Buyer Signature</span><br>
                        <b><?= getCompanyName($Tdata['dr_acc_kd_id']) ?? 'Not Found!'; ?></b>
                    </div>
                </div>
                <?php if ($notify_Exists) { ?>
                    <div>
                        <div class="signature-box">
                            <span>Notify Party Signature</span><br>
                            <b><?= getCompanyName($Tdata['np_acc_kd_id']) ?? 'Not Found!'; ?></b>
                        </div>
                    </div>
                <?php } ?>
                <div>
                    <div class="signature-box">
                        <span>Seller Signature</span><br>
                        <b><?= getCompanyName($Tdata['cr_acc_kd_id']) ?? 'Not Found!'; ?></b>
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
                        <span><strong>Acc Name: </strong> <?= $Tdata['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $Tdata['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($Tdata['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($Tdata['cr_acc_kd_id']), '', $Tdata['cr_acc_details']); ?></span>
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
                            <strong class="fs-6">Invoice #:</strong class="fs-6"> <?= $Tdata['id']; ?><br>
                            <strong class="fs-6">Date:</strong class="fs-6"> <?= $Tdata['_date']; ?><br>
                            <strong class="fs-6">Type:</strong class="fs-6"> <?= ucwords($Tdata['type']); ?><br>
                            <strong class="fs-6">Branch:</strong class="fs-6"> <?= branchName(ucwords($Tdata['branch_id'])); ?><br>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buyer Info -->
            <div class="row">
                <div class="col-6 border-top py-2 border-end">
                    <h5>Buyer</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $Tdata['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $Tdata['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($Tdata['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($Tdata['dr_acc_kd_id']), '', $Tdata['dr_acc_details']); ?></span>
                </div>
                <div class="col-6 border-top py-2">
                    <?php if ($notify_Exists) { ?>
                        <h5>Notify Party</h5>
                        <div class="hide-on-print">
                            <span><strong>Acc Name: </strong> <?= $Tdata['np_acc_name']; ?> | <strong>Acc No: </strong> <?= $Tdata['np_acc']; ?></span><br>
                        </div>
                        <span><strong>Company:</strong> <?= getCompanyName($Tdata['np_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                        <span><?= str_replace(getCompanyName($Tdata['np_acc_kd_id']), '', $Tdata['np_acc_details']); ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-6 border-top border-bottom py-2 border-end">
                    <?php
                    // Initialize arrays for Loading, Receiving, and Delivery details
                    $L = $R = [];
                    $D = '';
                    if (in_array($Troute, ['ld', 'wr'])) {
                        $L[] = ["Loading Company Name", $Tdata['loading_company_name'] ?? ''];
                        $R[] = ["Receiving Company Name", $Tdata['receiving_company_name'] ?? ''];
                        $D = $Tdata['receiving_date'] ?? '';
                    } else {
                        if ($Tdata['sea_road'] === 'sea') {
                            $L[] = ["Port of Loading", $Tdata['l_port'] ?? ''];
                            $R[] = ["Port of Discharge", $Tdata['r_port'] ?? ''];
                        } else {
                            $L[] = ["Border of Loading", $Tdata['l_border_road'] ?? ''];
                            $R[] = ["Border of Discharge", $Tdata['r_border_road'] ?? ''];
                        }
                        $D = $Tdata['r_date'] ?? $Tdata['r_date_road'] ?? '';
                    }
                    ?>
                    <strong>Delivery Date:</strong> <?= !empty($D) ? my_date($D[1]) : 'N/A'; ?><br>
                    <strong>Method of Dispatch:</strong> <?= $Tdata['type'] !== 'local'
                                                                ? strtoupper($Tdata['sea_road'] ?? 'N/A')
                                                                : strtoupper($Tdata['route'] ?? 'N/A') . ' Transfer'; ?><br>
                    <strong>Terms / Method of Payment:</strong> <?= $Ptype ?? 'N/A'; ?> <br>
                    <strong>Country of Origin:</strong> <?= ucfirst($firstItem['origin'] ?? 'N/A'); ?><br>
                    <strong>Delivery Terms:</strong> <?= $Tdata['delivery_terms'] ?? 'N/A'; ?><br>
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
                    foreach ($Ldata['goods'] as $details) {
                        $details_id = $details['goods_json']['id'];
                        echo '<tr>';
                        echo '<td class="border-end">' . goodsName($details['goods_json']['goods_id']) . ' / ' . $details['goods_json']['size'] . ' / ' . $details['goods_json']['brand'] . ' / ' . $details['goods_json']['origin'] . '</td>';
                        echo '<td class="border-end">' . $details['goods_json']['qty_no'] . '</td>';
                        echo '<td class="border-end">' . $details['goods_json']['qty_name'] . '</td>';
                        echo '<td class="border-end">' . $details['goods_json']['rate1'] . '<sub>' . $details['goods_json']['currency1'] . '</sub></td>';
                        echo '<td>' . round($details['goods_json']['amount'], 2) . '</td>';
                        echo '</tr>';
                        if (isset($details['agent'][$transType])) {
                            foreach ($details['agent'][$transType] as $Transferred) {
                                $TransData = explode('~', $Transferred);
                                echo '<tr>';
                                echo '<td class="border-end">  <i class="fas fa-arrow-right"></i> ' . ($transType === 'sold_to' ? 'Sold To => S# ' : 'Purchased From => P# ') . decode_unique_code($TransData[0], 'TID') . '</td>';
                                echo '<td class="border-end">' . $TransData[4] . '</td>';
                                echo '<td class="border-end">' . $TransData[6] . '</td>';
                                echo '<td class="border-end">' . $TransData[7] . '</td>';
                                echo '</tr>';
                            }
                        }
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
                    $third_party_bank = $Tdata['third_party_bank'];
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
                            <span><?= getCompanyName($Tdata['dr_acc_kd_id']) ?? 'Not Found!'; ?></span>
                        </div>
                        <!-- Seller Signature -->
                        <div class="text-center signature-box">
                            <strong>Seller Signature</strong><br>
                            <span><?= getCompanyName($Tdata['cr_acc_kd_id']) ?? 'Not Found!'; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Copyright Section -->
                <div class="col-12 p-3 border-top text-center">
                    <small class="text-muted">
                        &copy; <?= date('Y'); ?> DAMAAN GENERAL TRADING (DGT). <?= $T_ps . " #" . $Tdata['id']; ?> {!cc} Invoice generated on <?= $_GET['timestamp']; ?>.
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
            window.location.href = '?unique_code=<?= $unique_code; ?>&print_type=' + $(this).val() + '&timestamp=<?= $_GET['timestamp']; ?>';
        });
        <?php include("../assets/bs/js/bootstrap.bundle.min.js"); ?>
    </script>
</body>

</html>