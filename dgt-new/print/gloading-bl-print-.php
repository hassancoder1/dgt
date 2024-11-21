<?php
$page_title = 'G. Loading B/L Print';
$pageURL = 'gloading-bl-print';
require("../connection.php");
$remove = $blSearch = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `general_loading`";
$conditions = [];
if ($_GET) {
    $remove = removeFilter('gloading-bl-print');
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
$sql .= " ORDER BY sr_no ASC";
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
        td {
            text-wrap: nowrap;
        }

        @media print {
            .hide-on-print {
                display: none;

            }
        }
    </style>
</head>

<body class="mx-2">
    <div class="p-3">
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 section-header">
            <h1 class="display-6" style="font-weight: 700;">
                B/L Print ( #<?= htmlspecialchars($blSearch); ?> )
            </h1>
            <div class="d-flex gap-2">
                <button class="btn btn-dark btn-sm hide-on-print" onclick="window.location.href = '/general-loading'">
                    <i class="fa fa-arrow-left"></i> Back
                </button>
                <button class="btn btn-success btn-sm hide-on-print" onclick="window.print();">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div>
        <div class="d-flex justify-content-center align-center flex-wrap gap-4">
            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark">Loading Details</h6>
                <ul class="list-unstyled text-muted">
                    <li><b>Date:</b> <?= htmlspecialchars(my_date($fLoading['loading_date'])); ?></li>
                    <li><b>Country:</b> <?= htmlspecialchars($fLoading['loading_country']); ?></li>
                    <li><b><?= htmlspecialchars($fShipping['transfer_by'] === 'sea' ? 'PORT' : 'BORDER'); ?>:</b> <?= htmlspecialchars($fLoading['loading_port_name']); ?></li>
                </ul>
            </div>
            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark">Receiving Details</h6>
                <ul class="list-unstyled text-muted">
                    <li><b>Date:</b> <?= htmlspecialchars(my_date($fReceiving['receiving_date'])); ?></li>
                    <li><b>Country:</b> <?= htmlspecialchars($fReceiving['receiving_country']); ?></li>
                    <li><b><?= htmlspecialchars($fShipping['transfer_by'] === 'sea' ? 'PORT' : 'BORDER'); ?>:</b> <?= htmlspecialchars($fReceiving['receiving_port_name']); ?></li>
                </ul>
            </div>
            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark"><?= htmlspecialchars($fShipping['transfer_by'] === 'sea' ? 'Shipping' : 'Transporter'); ?> Details</h6>
                <ul class="list-unstyled text-muted">
                    <li><b>Name:</b> <?= htmlspecialchars($fShipping['shipping_name']); ?></li>
                    <li><b>Address:</b> <?= htmlspecialchars($fShipping['shipping_address']); ?></li>
                    <li><b>Phone:</b> <?= htmlspecialchars($fShipping['shipping_phone']); ?></li>
                    <li><b>WhatsApp:</b> <?= htmlspecialchars($fShipping['shipping_whatsapp']); ?></li>
                    <li><b>Email:</b> <?= htmlspecialchars($fShipping['shipping_email']); ?></li>
                </ul>
            </div>
            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark">Importer Details</h6>
                <ul class="list-unstyled text-muted">
                    <li><b>Acc No:</b> <?= htmlspecialchars($fImporter['im_acc_no']); ?></li>
                    <li><b>Acc Name:</b> <?= htmlspecialchars($fImporter['im_acc_name']); ?></li>
                    <li><b>Company Name:</b> <?= htmlspecialchars($IMCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($IMCompany, '', $fImporter['im_acc_details'])); ?></li>
                </ul>
            </div>
            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark">Exporter Details</h6>
                <ul class="list-unstyled text-muted">
                    <li><b>Acc No:</b> <?= htmlspecialchars($fExporter['xp_acc_no']); ?></li>
                    <li><b>Acc Name:</b> <?= htmlspecialchars($fExporter['xp_acc_name']); ?></li>
                    <li><b>Company Name:</b> <?= htmlspecialchars($XPCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($XPCompany, '', $fExporter['xp_acc_details'])); ?></li>
                </ul>
            </div>
            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark">Notify Party Details</h6>
                <ul class="list-unstyled text-muted">
                    <li><b>Acc No:</b> <?= htmlspecialchars($fNotify['np_acc_no']); ?></li>
                    <li><b>Acc Name:</b> <?= htmlspecialchars($fNotify['np_acc_name']); ?></li>
                    <li><b>Company Name:</b> <?= htmlspecialchars($NPCompany); ?></li>
                    <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($NPCompany, '', $fNotify['np_acc_details'])); ?></li>
                </ul>
            </div>

            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark">Report</h6>
                <p><?= $firstBl['report']; ?></p>
            </div>
            <div class="section-content" style="width:48%;">
                <h6 class="fw-bold text-dark">Totals</h6>
                <ul class="list-unstyled text-muted">
                    <li><b>Countainers Count:</b> <?= htmlspecialchars($containerCounts[$blSearch]); ?></li>
                    <li><b>Total Quantity:</b> <?= htmlspecialchars($quantityNos[$blSearch]); ?></li>
                    <li><b>Total Gross Weight:</b> <?= htmlspecialchars($grossWeights[$blSearch]); ?></li>
                    <li><b>Total Net Weight:</b> <?= htmlspecialchars($netWeights[$blSearch]); ?></li>
                </ul>
            </div>
        </div>
        <div class="mt-4">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>P#</th>
                        <th>G.Name</th>
                        <th>Size</th>
                        <th>Brand</th>
                        <th>Origin</th>
                        <th>Qty</th>
                        <th>G.Weight</th>
                        <th>N.Weight</th>
                        <th>C.No</th>
                        <th>C.Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($Loadings as $SingleLoading) {
                        $G = json_decode($SingleLoading['goods_details'], true);
                    ?>
                        <tr class="text-center">
                            <td>
                                <span>
                                    <b>P#<?= htmlspecialchars($SingleLoading['p_id']); ?></b> (<?= htmlspecialchars($SingleLoading['sr_no']); ?>)
                                </span>
                            </td>
                            <td><?= htmlspecialchars(goodsName($G['goods_id'])); ?></td>
                            <td><?= htmlspecialchars($G['size']); ?></td>
                            <td><?= htmlspecialchars($G['brand']); ?></td>
                            <td><?= htmlspecialchars($G['origin']); ?></td>
                            <td>
                                <?= htmlspecialchars($G['quantity_no']); ?>
                                <sub class="fw-bold"><?= htmlspecialchars($G['quantity_name']); ?></sub>
                            </td>
                            <td><?= htmlspecialchars($G['gross_weight']); ?></td>
                            <td><?= htmlspecialchars($G['net_weight']); ?></td>
                            <td><?= htmlspecialchars($G['container_no']); ?></td>
                            <td><?= htmlspecialchars($G['container_name']); ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function toggleDates() {
            const selectedValue = $('#date_type').val();
            if (selectedValue === "") {
                $('#startInput, #endInput').addClass('d-none');
            } else {
                $('#startInput, #endInput').removeClass('d-none');
            }
        };
    </script>
</body>

</html>