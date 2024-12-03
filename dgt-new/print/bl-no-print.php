<?php
$page_title = 'G. Loading B/L Print';
$pageURL = 'bl-no-print';
require("../connection.php");
$remove = $blSearch = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `general_loading`";
$conditions = [];
if ($_GET) {
    $remove = removeFilter('bl-no-print');
    $is_search = true;
    if (!empty($_GET['blSearch'])) {
        $blSearch = mysqli_real_escape_string($connect, $_GET['blSearch']);
        $print_filters[] = 'blSearch=' . $blSearch;
        $conditions[] = "bl_no='$blSearch'";
    }
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}
$sql .= " ORDER BY created_at ASC";
$result = mysqli_query($connect, $sql);
$containerCounts = $Loadings = $netWeights = $grossWeights = $quantityNos = [];
$firstBl = null;
while ($one = mysqli_fetch_assoc($result)) {
    if ($firstBl === null) {
        $firstBl = $one;
    }
    $Loadings[] = $one;
    $blNo = $one['bl_no'];
    if (!isset($containerCounts[$blNo])) {
        $containerCounts[$blNo] = 1;
        $netWeights[$blNo] = 0;
        $grossWeights[$blNo] = 0;
        $quantityNos[$blNo] = 0;
    } else {
        $containerCounts[$blNo]++;
    }
    $goodsDetails = json_decode($one['goods_details'], true);
    $netWeights[$blNo] += $goodsDetails['net_weight'];
    $grossWeights[$blNo] += $goodsDetails['gross_weight'];
    $quantityNos[$blNo] += $goodsDetails['quantity_no'];
}

$fLoading = json_decode($firstBl['loading_details'], true);
$fReceiving = json_decode($firstBl['receiving_details'], true);
$fShipping = json_decode($firstBl['shipping_details'], true);
$fImporter = json_decode($firstBl['importer_details'], true);
$fExporter = json_decode($firstBl['exporter_details'], true);
$fAgent = json_decode($firstBl['agent_details'], true);
$fNotify = json_decode($firstBl['notify_party_details'], true);

$company_names = [];
$unique_khaata_ids = array_filter(array_unique([
    $fImporter['im_acc_kd_id'] ?? null,
    $fExporter['xp_acc_kd_id'] ?? null,
    $fNotify['np_acc_kd_id'] ?? null
]));

if (!empty($unique_khaata_ids)) {
    $khaata_ids_str = implode(",", $unique_khaata_ids);
    $CompanyQuery = mysqli_query($connect, "SELECT id, json_data FROM khaata_details WHERE id IN ($khaata_ids_str)");
    while ($myRow = mysqli_fetch_assoc($CompanyQuery)) {
        $json_data = json_decode($myRow['json_data'], true);
        $company_names[$myRow['id']] = $json_data['company_name'];
    }
}
$IMCompany = $company_names[$fImporter['im_acc_kd_id']] ?? 'N/A';
$XPCompany = $company_names[$fExporter['xp_acc_kd_id']] ?? 'N/A';
$NPCompany = $company_names[$fNotify['np_acc_kd_id']] ?? 'N/A';
$firstBLPID = $firstBl['p_id'];
$pIDQ = mysqli_query($connect, "SELECT  * FROM general_loading WHERE p_id='$firstBLPID' ORDER BY created_at ASC");
$blOrders = [];
$currentOrder = 1;
while ($SPid = mysqli_fetch_assoc($pIDQ)) {
    $blNo = $SPid['bl_no'];
    if (!isset($blOrders[$blNo])) {
        $blOrders[$blNo] = $currentOrder;
        $currentOrder++;
    }
}
$Ag_acc_no = $fAgent['ag_acc_no'];
$AGAcc = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id, email, phone FROM khaata WHERE LOWER(khaata_no) = LOWER('$Ag_acc_no')"));
$AGAcc_id = $AGAcc['id'];
$AGCompany = mysqli_fetch_assoc(mysqli_query($connect, "SELECT json_data FROM khaata_details WHERE khaata_id = '$AGAcc_id' ORDER BY created_at ASC LIMIT 1"));
$AGCompany = json_decode($AGCompany['json_data'], true);
$AGCombine = array_combine(isset($AGCompany['indexes1']) ? $AGCompany['indexes1'] : [], isset($AGCompany['vals1']) ? $AGCompany['vals1'] : []);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BL Print</title>
    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
    echo "</style>";
    ?>
    <style>
        body {
            font-size: 13px;
            background-color: #f8f9fa;
        }

        .section-header {
            border-bottom: 1px solid #444;
            margin-bottom: 1rem;
        }

        .bordered {
            border: 1px solid #444;
        }

        .custom-table th,
        .custom-table td {
            font-size: 12px;
            text-align: center;
            vertical-align: middle;
        }

        .signature-box {
            border-top: 1px solid #444;
            max-width: 130px;
            margin-top: 1rem;
            padding: 10px;
        }

        .fw-light-muted {
            font-weight: 300;
            color: #6c757d;
        }

        .header-logo img {
            max-width: 150px;
        }

        .text-highlight {
            font-weight: bold;
            color: #0d6efd;
        }

        .row {
            position: relative;
        }
    </style>
</head>

<body>
    <div class="mx-2 bg-white p-3">
        <div class="row mb-4">
            <!-- Left Column -->
            <div class="col-6">
                <div class="section-header mb-2">
                    <h6 class="fw-bold">Importer</h6>
                </div>
                <ul class="list-unstyled fw-light-muted">
                    <li><b>Company Name:</b> <?= htmlspecialchars($IMCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($IMCompany, '', $fImporter['im_acc_details'])); ?></li>
                </ul>

                <div class="section-header mb-2">
                    <h6 class="fw-bold">Exporter</h6>
                </div>
                <ul class="list-unstyled fw-light-muted">
                    <li><b>Company Name:</b> <?= htmlspecialchars($XPCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($XPCompany, '', $fExporter['xp_acc_details'])); ?></li>
                </ul>

                <div class="section-header mb-2">
                    <h6 class="fw-bold">Notify</h6>
                </div>
                <ul class="list-unstyled fw-light-muted">
                    <li><b>Company Name:</b> <?= htmlspecialchars($NPCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($NPCompany, '', $fNotify['np_acc_details'])); ?></li>
                </ul>

                <div>
                    <h6 class="fw-bold mt-3">Transfer Warehouse:</h6>
                    <p class="fw-light-muted"><?= htmlspecialchars(isset($fAgent['cargo_transfer_warehouse']) ? $fAgent['cargo_transfer_warehouse'] : 'N/A'); ?></p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-6">
                <div class="border p-2 pt-3 mb-2 text-center">
                    <div class="header-logo">
                        <img src="../assets/images/logo.png" alt="logo" class="img-fluid">
                    </div>
                    <h6 class="fw-bold mt-2">DAMAAN GENERAL TRADING LLC</h6>
                    <p class="text-muted">Booking Ref.: <?= ucfirst($firstBl['type']); ?>#<?= $firstBl['p_id'] . " (" . $blOrders[$firstBl['bl_no']] . ")" ?> - B/L Number: #<?= $firstBl['bl_no']; ?></p>
                </div>

                <div class="border p-2 mb-2">
                    <h6 class="fw-bold">Shipper</h6>
                    <ul class="list-unstyled">
                        <li><b>Name:</b> <?= htmlspecialchars($fShipping['shipping_name']); ?></li>
                        <li><b>Address:</b> <?= htmlspecialchars($fShipping['shipping_address']); ?></li>
                        <li><b>Phone:</b> <?= htmlspecialchars($fShipping['shipping_phone']); ?></li>
                        <li><b>WhatsApp:</b> <?= htmlspecialchars($fShipping['shipping_whatsapp']); ?></li>
                        <li><b>Email:</b> <?= htmlspecialchars($fShipping['shipping_email']); ?></li>
                    </ul>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="border p-2">
                            <h6 class="fw-bold">Loading</h6>
                            <p class="mb-0"><b>Date:</b> 07-Nov-24</p>
                            <p class="mb-0"><b>Country:</b> Pakistan</p>
                            <p><b>Border:</b> Kasim</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border p-2">
                            <h6 class="fw-bold">Receiving</h6>
                            <p class="mb-0"><b>Date:</b> 07-Nov-24</p>
                            <p class="mb-0"><b>Country:</b> Pakistan</p>
                            <p><b>Border:</b> Kasim</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border p-2 pb-0 mb-2 d-flex justify-content-between">
            <div>
                <h6 class="fw-bold">Clearing Agent:</h6>
                <p><b>Acc No:</b> <?= htmlspecialchars(isset($fAgent['ag_acc_no']) ? $fAgent['ag_acc_no'] : 'N/A'); ?> <b>Name:</b> <?= htmlspecialchars(isset($fAgent['ag_name']) ? $fAgent['ag_name'] : 'N/A'); ?></p>
            </div>
            <ul class="list-unstyled">
                <li><b>Company:</b> <?= htmlspecialchars(isset($AGCompany['company_name']) ? $AGCompany['company_name'] : 'N/A'); ?></li>
                <li><b>Weight No:</b> <?= htmlspecialchars(isset($AGCombine['WEIGHT']) ? $AGCombine['WEIGHT'] : 'N/A'); ?>
                    <b> License No:</b> <?= htmlspecialchars(isset($AGCombine['License']) ? $AGCombine['License'] : 'N/A'); ?>
                </li>
                <li><b>Email:</b> <?= htmlspecialchars(isset($AGAcc['email']) ? $AGAcc['email'] : 'N/A'); ?> <b>Phone:</b> <?= htmlspecialchars(isset($AGAcc['phone']) ? $AGAcc['phone'] : 'N/A'); ?></li>
            </ul>
        </div>

        <div class="custom-table">
            <table class="table table-bordered table-sm">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Container</th>
                        <th>Quantity</th>
                        <th>Goods Description</th>
                        <th>Gross Weight</th>
                        <th>Net Weight</th>
                        <?php if (isset($_GET['agent-print'])) { ?>
                            <th>R.Date</th>
                            <th>Clear.D</th>
                            <th>Bill Entry No</th>
                            <th>L.Truck.No</th>
                            <th>R.Truck.Date</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Loadings as $SingleLoading) {
                        $G = json_decode($SingleLoading['goods_details'], true);
                        $A = json_decode($SingleLoading['agent_details'], true);
                        // Check if any required field is empty and set it to "Not Given"
                        $A['received_date'] = !empty($A['received_date']) ? $A['received_date'] : "Not Given";
                        $A['clearing_date'] = !empty($A['clearing_date']) ? $A['clearing_date'] : "Not Given";
                        $A['bill_of_entry_no'] = !empty($A['bill_of_entry_no']) ? $A['bill_of_entry_no'] : "Not Given";
                        $A['loading_truck_number'] = !empty($A['loading_truck_number']) ? $A['loading_truck_number'] : "Not Given";
                        $A['truck_returning_date'] = !empty($A['truck_returning_date']) ? $A['truck_returning_date'] : "Not Given";
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($G['container_no']) . ' / ' . htmlspecialchars($G['container_name']); ?></td>
                            <td><?= htmlspecialchars($G['quantity_no']) . ' / ' . htmlspecialchars($G['quantity_name']); ?></td>
                            <td><?= htmlspecialchars(goodsName($G['goods_id'])) . ' / ' . htmlspecialchars($G['size']) . ' / ' . htmlspecialchars($G['brand']) . ' / ' . htmlspecialchars($G['origin']); ?></td>
                            <td><?= htmlspecialchars($G['gross_weight']); ?></td>
                            <td><?= htmlspecialchars($G['net_weight']); ?></td>
                            <?php if (isset($_GET['agent-print'])) { ?>
                                <td><?= htmlspecialchars($A['received_date']); ?></td>
                                <td><?= htmlspecialchars($A['clearing_date']); ?></td>
                                <td><?= htmlspecialchars($A['bill_of_entry_no']); ?></td>
                                <td><?= htmlspecialchars($A['loading_truck_number']); ?></td>
                                <td><?= htmlspecialchars($A['truck_returning_date']); ?></td>
                            <?php } ?>

                        </tr>
                    <?php }; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-3 position-relative">
            <div class="col-8">
                <div class="signature-box mx-auto mb-auto position-absolute bottom-0 start-0">
                    <h6 class="fw-bold">Signature</h6>
                </div>
            </div>
            <div class="col-4 text-end">
                <h6 class="fw-bold">Totals:</h6>
                <span><b>Containers:</b> <?= htmlspecialchars($containerCounts[$blSearch]); ?></span><br>
                <span><b>Quantity:</b> <?= htmlspecialchars($quantityNos[$blSearch]); ?></span><br>
                <span><b>Gross Weight:</b> <?= htmlspecialchars($grossWeights[$blSearch]); ?></span><br>
                <span><b>Net Weight:</b> <?= htmlspecialchars($netWeights[$blSearch]); ?></span>
            </div>
        </div>

    </div>
</body>

</html>