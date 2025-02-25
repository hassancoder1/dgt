<?php
$page_title = 'G. Loading B/L Print';
$pageURL = 'bl-no-print';
require("../connection.php");
$remove = $blSearch = '';
$is_search = false;
global $connect;
$blSearch = $_GET['blSearch'];
$BL = mysqli_fetch_assoc(fetch('general_loading', ['bl_no' => $blSearch]));
$L = json_decode($BL['loading_info'] ?? '[]', true);
$G = json_decode($BL['goods_info'] ?? '[]', true);
$A = json_decode($BL['agent_info'] ?? '[]', true);
$W = json_decode($BL['warehouse_info'] ?? '[]', true);

$t_id = $BL['t_id'];
$orders = [];
$BlOrder = mysqli_fetch_all($connect->query("SELECT * FROM general_loading WHERE t_id = '$t_id' ORDER BY created_at"), MYSQLI_ASSOC);
foreach ($BlOrder as $key => $order) {
    $orders[$order['bl_no']] = $key + 1;
}
$fAgent = !empty($A) ? reset($A) : [];
$Ag_acc_no = $fAgent['ag_acc_no'] ?? '';
$AGAcc = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id, email, phone FROM khaata WHERE LOWER(khaata_no) = LOWER('$Ag_acc_no')"));
$AGAcc_id = $AGAcc['id'] ?? '';
$AGCompany = mysqli_fetch_assoc(mysqli_query($connect, "SELECT json_data FROM khaata_details WHERE khaata_id = '$AGAcc_id' ORDER BY created_at ASC LIMIT 1"));
$AGCompany = json_decode($AGCompany['json_data'] ?? '[]', true);
$AGCombine = array_combine(isset($AGCompany['indexes1']) ? $AGCompany['indexes1'] : [], isset($AGCompany['vals1']) ? $AGCompany['vals1'] : []);

$company_names = [];
$unique_khaata_ids = array_filter(array_unique([
    $L['importer']['im_acc_kd_id'] ?? null,
    $L['exporter']['xp_acc_kd_id'] ?? null,
    $L['notify']['np_acc_kd_id'] ?? null
]));
if (!empty($unique_khaata_ids)) {
    $khaata_ids_str = implode(",", $unique_khaata_ids);
    $CompanyQuery = mysqli_query($connect, "SELECT id, json_data FROM khaata_details WHERE id IN ($khaata_ids_str)");
    while ($myRow = mysqli_fetch_assoc($CompanyQuery)) {
        $json_data = json_decode($myRow['json_data'], true);
        $company_names[$myRow['id']] = $json_data['company_name'];
    }
}
$IMCompany = $company_names[$L['importer']['im_acc_kd_id']] ?? 'N/A';
$XPCompany = $company_names[$L['exporter']['xp_acc_kd_id']] ?? 'N/A';
$NPCompany = $company_names[$L['notify']['np_acc_kd_id']] ?? 'N/A';
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
    include '../assets/fonts/lexend.css';
    echo "</style>";
    ?>
    <style>
        * {
            font-family: 'Lexend', serif;
        }

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
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($IMCompany, '', $L['importer']['im_acc_details'])); ?></li>
                </ul>

                <div class="section-header mb-2">
                    <h6 class="fw-bold">Exporter</h6>
                </div>
                <ul class="list-unstyled fw-light-muted">
                    <li><b>Company Name:</b> <?= htmlspecialchars($XPCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($XPCompany, '', $L['exporter']['xp_acc_details'])); ?></li>
                </ul>

                <div class="section-header mb-2">
                    <h6 class="fw-bold">Notify</h6>
                </div>
                <ul class="list-unstyled fw-light-muted">
                    <li><b>Company Name:</b> <?= htmlspecialchars($NPCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($NPCompany, '', $L['notify']['np_acc_details'])); ?></li>
                </ul>

                <div>
                    <h6 class="fw-bold mt-3">Transfer Warehouse:</h6>
                    <p class="fw-light-muted"><?= !empty($W) ? reset($W)['warehouse'] : 'N/A'; ?></p>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-6">
                <div class="border p-2 pt-3 mb-2 text-center">
                    <div class="header-logo">
                        <img src="../assets/images/logo.png" alt="logo" class="img-fluid">
                    </div>
                    <h6 class="fw-bold mt-2">DAMAAN GENERAL TRADING LLC</h6>
                    <p class="text-muted">Booking Ref.: <?= ucfirst($BL['p_s']); ?>#<?= $BL['t_sr'] . " (" . $orders[$BL['bl_no']] . ")" ?> - B/L Number: #<?= $BL['bl_no']; ?></p>
                </div>

                <div class="border p-2 mb-2">
                    <h6 class="fw-bold">Shipper</h6>
                    <ul class="list-unstyled">
                        <li><b>Name:</b> <?= htmlspecialchars($L['shipping']['shipping_name']); ?></li>
                        <li><b>Address:</b> <?= htmlspecialchars($L['shipping']['shipping_address']); ?></li>
                        <li><b>Phone:</b> <?= htmlspecialchars($L['shipping']['shipping_phone']); ?></li>
                        <li><b>WhatsApp:</b> <?= htmlspecialchars($L['shipping']['shipping_whatsapp']); ?></li>
                        <li><b>Email:</b> <?= htmlspecialchars($L['shipping']['shipping_email']); ?></li>
                    </ul>
                </div>

                <div class="row g-2">
                    <div class="col-6">
                        <div class="border p-2">
                            <h6 class="fw-bold">Loading</h6>
                            <p class="mb-0"><b>Date:</b> <?= htmlspecialchars($L['loading']['loading_date']); ?></p>
                            <p class="mb-0"><b>Country:</b> <?= htmlspecialchars($L['loading']['loading_country']); ?></p>
                            <p><b><?= $L['shipping']['transfer_by'] === 'sea' ? 'Port' : 'Border'; ?>:</b> <?= htmlspecialchars($L['loading']['loading_port_name']); ?></p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border p-2">
                            <h6 class="fw-bold">Receiving</h6>
                            <p class="mb-0"><b>Date:</b> <?= htmlspecialchars($L['receiving']['receiving_date']); ?></p>
                            <p class="mb-0"><b>Country:</b> <?= htmlspecialchars($L['receiving']['receiving_country']); ?></p>
                            <p><b><?= $L['shipping']['transfer_by'] === 'sea' ? 'Port' : 'Border'; ?>:</b> <?= htmlspecialchars($L['receiving']['receiving_port_name']); ?></p>
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
                            <th>BOE No</th>
                            <th>PickUp.D</th>
                            <th>Waiting Days</th>
                            <th>Return.D</th>
                            <th>Transporter</th>
                            <th>Truck No.</th>
                            <th>Details</th>
                            <th>Driver Name</th>
                            <th>Driver No</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($G as $key => $g) {
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($g['container_no']) . ' / ' . htmlspecialchars($g['container_name']); ?></td>
                            <td><?= htmlspecialchars($g['quantity_no']) . ' / ' . htmlspecialchars($g['quantity_name']); ?></td>
                            <td><?= htmlspecialchars(goodsName($g['good']['goods_id'])) . ' / ' . htmlspecialchars($g['good']['size']) . ' / ' . htmlspecialchars($g['good']['brand']) . ' / ' . htmlspecialchars($g['good']['origin']); ?></td>
                            <td><?= htmlspecialchars($g['gross_weight']); ?></td>
                            <td><?= htmlspecialchars($g['net_weight']); ?></td>
                            <?php if (isset($_GET['agent-print'])) { ?>
                                <td><?= $A[$key]['boe_no'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['pick_up_date'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['waiting_days'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['return_date'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['transporter_name'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['truck_number'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['details'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['driver_name'] ?? 'Not Given!'; ?></td>
                                <td><?= $A[$key]['driver_number'] ?? 'Not Given!'; ?></td>
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
                <span><b>Containers:</b> <?= count($G); ?></span><br>
                <span><b>Quantity:</b> <?= array_sum(array_column($G, 'quantity_no')); ?></span><br>
                <span><b>Gross Weight:</b> <?= array_sum(array_column($G, 'gross_weight')); ?></span><br>
                <span><b>Net Weight:</b> <?= array_sum(array_column($G, 'net_weight')); ?></span>
            </div>
        </div>

    </div>
</body>

</html>