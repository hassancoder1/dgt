<?php
$page_title = 'L. Loading UID Print';
$pageURL = 'uid-print';
require("../connection.php");
$remove = $UIDSearch = '';
$is_search = false;
global $connect;
$sql = "SELECT * FROM `local_loading`";
$conditions = [];
if ($_GET) {
    $remove = removeFilter('uid-print');
    $is_search = true;
    if (!empty($_GET['UIDSearch'])) {
        $UIDSearch = mysqli_real_escape_string($connect, $_GET['UIDSearch']);
        $print_filters[] = 'UIDSearch=' . $UIDSearch;
        $conditions[] = "uid='$UIDSearch'";
    }
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}
$sql .= " ORDER BY created_at ASC";
$result = mysqli_query($connect, $sql);
$containerCounts = $Loadings = $netWeights = $grossWeights = $quantityNos = [];
$firstUID = null;
while ($one = mysqli_fetch_assoc($result)) {
    if ($firstUID === null) {
        $firstUID = $one;
    }
    $Loadings[] = $one;
    $UID = $one['uid'];
    if (!isset($containerCounts[$UID])) {
        $containerCounts[$UID] = 1;
        $netWeights[$UID] = 0;
        $grossWeights[$UID] = 0;
        $quantityNos[$UID] = 0;
    } else {
        $containerCounts[$UID]++;
    }
    $goodsDetails = json_decode($one['goods_details'], true);
    $netWeights[$UID] += $goodsDetails['net_weight'];
    $grossWeights[$UID] += $goodsDetails['gross_weight'];
    $quantityNos[$UID] += $goodsDetails['quantity_no'];
}

$fTransfer = json_decode($firstUID['transfer_details'], true);
$firstUIDPID = $firstUID['p_id'];
$pIDQ = mysqli_query($connect, "SELECT  * FROM local_loading WHERE p_id='$firstUIDPID' ORDER BY created_at ASC");
$UIDOrders = [];
$currentOrder = 1;
while ($SPid = mysqli_fetch_assoc($pIDQ)) {
    $UID = $SPid['uid'];
    if (!isset($UIDOrders[$UID])) {
        $UIDOrders[$UID] = $currentOrder;
        $currentOrder++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UID Print</title>
    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
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
            /* text-align: center; */
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
            <!-- Right Column -->
            <div class="col-12">
                <div class="border p-2 pt-3 mb-2 d-flex justify-content-between align-items-center">
                    <div class="header-logo">
                        <img src="../assets/images/logo.png" alt="logo" class="img-fluid">
                    </div>
                    <div>
                        <h6 class="fw-bold mt-2">DAMAAN GENERAL TRADING LLC</h6>
                        <p class="text-muted">Booking Ref.: <?= ucfirst($firstUID['type']); ?>#<?= $firstUID['p_sr'] . " (" . $UIDOrders[$firstUID['uid']] . ")" ?> - UID: #<?= $firstUID['uid']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Left Column -->
            <div class="col-5" style="margin-left: 0.6rem;">
                <div class="section-header mb-2">
                    <h6 class="fw-bold">General Details</h6>
                </div>
                <ul class="list-unstyled fw-light-muted">
                    <?php if (isset($fTransfer['truck_no'])) { ?>
                        <li><b>Truck No:</b> <?= htmlspecialchars($fTransfer['truck_no']); ?></li>
                        <li><b>Truck Name:</b> <?= htmlspecialchars($fTransfer['truck_name']); ?></li>
                    <?php } else { ?>
                        <li><b>Warehouse:</b> <?= htmlspecialchars($fTransfer['transfer_warehouse']); ?></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-12 row mt-3" style="margin-left: 0.1rem;">
                <div class="col-5">
                    <div class="section-header mb-2">
                        <h6 class="fw-bold">Loading Details</h6>
                    </div>
                    <ul class="list-unstyled fw-light-muted">
                        <li><b>Company Name:</b> <?= htmlspecialchars($fTransfer['loading_company_name']); ?></li>
                        <li><b>Date:</b> <?= my_date(htmlspecialchars($fTransfer['loading_date'])); ?></li>
                        <?php if (isset($fTransfer['truck_no'])) { ?>
                            <li><b>Warehouse:</b> <?= htmlspecialchars($fTransfer['loading_warehouse']); ?></li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-2"></div>
                <div class="col-5">
                    <div class="section-header mb-2">
                        <h6 class="fw-bold">Receiving Details</h6>
                    </div>
                    <ul class="list-unstyled fw-light-muted">
                        <li><b>Company Name:</b> <?= htmlspecialchars($fTransfer['receiving_company_name']); ?></li>
                        <li><b>Date:</b> <?= my_date(htmlspecialchars($fTransfer['receiving_date'])); ?></li>
                        <?php if (isset($fTransfer['truck_no'])) { ?>
                            <li><b>Warehouse:</b> <?= htmlspecialchars($fTransfer['receiving_warehouse']); ?></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="custom-table">
            <table class="table table-bordered table-sm">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Goods(Name / Size / Brand / Origin)</th>
                        <th>Quantity</th>
                        <th>Gross Weight</th>
                        <th>Net Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($Loadings as $SingleLoading) {
                        $G = json_decode($SingleLoading['goods_details'], true);
                    ?>
                        <tr>
                            <td><?= htmlspecialchars(goodsName($G['goods_id'])) . ' / ' . htmlspecialchars($G['size']) . ' / ' . htmlspecialchars($G['brand']) . ' / ' . htmlspecialchars($G['origin']); ?></td>
                            <td><?= htmlspecialchars($G['quantity_no']) . ' / ' . htmlspecialchars($G['quantity_name']); ?></td>
                            <td><?= htmlspecialchars($G['gross_weight']); ?></td>
                            <td><?= htmlspecialchars($G['net_weight']); ?></td>
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
                <span><b>Containers:</b> <?= htmlspecialchars($containerCounts[$UIDSearch]); ?></span><br>
                <span><b>Quantity:</b> <?= htmlspecialchars($quantityNos[$UIDSearch]); ?></span><br>
                <span><b>Gross Weight:</b> <?= htmlspecialchars($grossWeights[$UIDSearch]); ?></span><br>
                <span><b>Net Weight:</b> <?= htmlspecialchars($netWeights[$UIDSearch]); ?></span>
            </div>
        </div>

    </div>
</body>

</html>