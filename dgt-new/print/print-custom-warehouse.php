<?php
require_once '../connection.php';
$p1 = $_GET['p1'];
$print_type = ucwords(str_replace('-', ' ', $_GET['print_type']));
$warehouse_type = ucwords(str_replace('-', ' ', $_GET['warehouse_type']));
[$p1Ttype, $p1Tcat, $p1Troute, $p1TID, $p1LID] = decode_unique_code($p1, 'all');
$res = $connect->query("SELECT * FROM data_copies WHERE unique_code = '" . addslashes($p1) . "'");
$entry = $res->fetch_assoc();
$allotment = json_decode($entry['ldata'], true)['good']['goods_json']['allotment_name'];
$generalWarehouse = array_search(json_decode($entry['ldata'], true)['transfer']['warehouse_transfer'], [
    'transit' => 'Transit',
    'freezone-import' => 'Free Zone Import',
    'local-import' => 'Local Import',
    'import-re-export' => 'Import Re-Export',
    'local-export' => 'Local Export',
    'local-market' => 'Local Market',
    'all' => 'All WareHouses'
]);
$prefix = explode('_', $p1)[0];
$res = $connect->query("SELECT * FROM data_copies WHERE unique_code LIKE '" . addslashes($prefix) . "%' AND JSON_EXTRACT(ldata, '$.good.goods_json.allotment_name') = '" . addslashes($allotment) . "'");
$entry['tdata'] = json_decode($entry['tdata'], true);
$entry['ldata'] = json_decode($entry['ldata'], true);
$p1Data = [$entry];
while ($row = $res->fetch_assoc()) {
    if ($row['unique_code'] !== $p1) {
        $row['tdata'] = json_decode($row['tdata'], true);
        $row['ldata'] = json_decode($row['ldata'], true);
        $p1Data[] = $row;
    }
}
$soldEntries = [];
foreach ($p1Data as $d) {
    $key = isset($d['ldata']['transfer']['sold_to']) ? 'sold_to' : (isset($d['ldata']['transfer']['sold_from']) ? 'sold_from' : '');
    if ($key) $soldEntries = $d['ldata']['transfer'][$key];
}
$saleCodes = implode("','", array_map(fn($s) => explode('~', $s)[0], $soldEntries));
$res = $connect->query("SELECT * FROM data_copies WHERE unique_code IN ('$saleCodes')");
$p2Data = [];
while ($row = $res->fetch_assoc()) {
    $row['tdata'] = json_decode($row['tdata'], true);
    $row['ldata'] = json_decode($row['ldata'], true);
    $p2Data[] = $row;
}
$p2Data[0] = $p2Data[0] ?? [];
$p2Data[0]['tdata'] = $p2Data[0]['tdata'] ?? [];
$p2Data[0]['ldata'] = $p2Data[0]['ldata'] ?? [];
[$p2Ttype, $p2Tcat, $p2Troute, $p2TID, $p2LID] = decode_unique_code($p2Data[0]['unique_code'] ?? '', 'all');
[$PONE0T, $PONE0L, $PTWO0T, $PTWO0L]  = [$p1Data[0]['tdata'], $p1Data[0]['ldata'], $p2Data[0]['tdata'], $p2Data[0]['ldata']];
function printDesign($WarehouseType, $printType)
{
    global $PONE0T, $PONE0L, $PTWO0T, $PTWO0L, $p2Ttype, $p2Tcat, $p2Troute, $p2TID, $p2LID, $p1Ttype, $p1Tcat, $p1Troute, $p1TID, $p1LID, $print_type, $warehouse_type, $p1Data, $p2Data, $connect;
    if ($WarehouseType === 'General') {
?><div class="row">
            <div class="col-6">
                <img src="logo.jpg" style="border-radius: 100%; width: 110px; margin-bottom: 10px;" alt="">
                <h6 class="text-primary fw-bold text-nowrap"><?= $warehouse_type . ' ' . $print_type; ?></h6>
            </div>
            <div class="col-6">
                <span><b> <?= $PONE0T['p_s'] === 'p' ? 'Purchase' : 'Sale'; ?> #: </b><?= $p1TID; ?></span><br>
                <span><b>Date:</b> <?= $PONE0T['_date']; ?></span><br>
                <span><b>Country:</b> <?= $PONE0T['country']; ?></span><br>
                <span><b>Branch:</b> <?= branchName($PONE0T['branch_id']) ?? ''; ?></span><br>
            </div>
        </div>

        <div class="row my-2 py-2 border-top">
            <div class="col-6">
                <h5>Sale</h5>
                <div class="hide-on-print">
                    <span><strong>Acc Name: </strong> <?= $PONE0T['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0T['cr_acc']; ?></span><br>
                </div>
                <span><strong>Company:</strong> <?= getCompanyName($PONE0T['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                <span><?= str_replace(getCompanyName($PONE0T['cr_acc_kd_id']), '', $PONE0T['cr_acc_details']); ?></span>
            </div>
            <div class="col-6">
                <h5>Purchase</h5>
                <div class="hide-on-print">
                    <span><strong>Acc Name: </strong> <?= $PONE0T['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0T['dr_acc']; ?></span><br>
                </div>
                <span><strong>Company:</strong> <?= getCompanyName($PONE0T['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                <span><?= str_replace(getCompanyName($PONE0T['dr_acc_kd_id']), '', $PONE0T['dr_acc_details']); ?></span>
            </div>
        </div>
        <div class="row py-2 my-2 border-top">
            <div class="col-2 my-2">
                <span><b>Loading Date:</b> <?= $PONE0L['transfer']['loading_date'] ?? ''; ?></span>
            </div>
            <div class="col-2 my-2">
                <span><b>Receiving Date:</b> <?= $PONE0L['transfer']['receiving_date'] ?? ''; ?></span>
            </div>
            <div class="col-2 my-2">
                <span><b>UID: No:</b> <?= $PONE0L['uid'] ?? ''; ?></span>
            </div>
        </div>

        <div class="table-responsive my-2 py-2 border-top">
            <table class="table table-bordered border-dark">
                <thead>
                    <tr>
                        <th class="bg-dark text-white">ORIGIN</th>
                        <?php
                        if ($PONE0T['type'] !== 'local') {
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
                        <td><?= $PONE0L['good']['origin']; ?></td>
                        <?php
                        if ($PONE0T['type'] !== 'local') {
                            echo '<td>' . strtoupper($PONE0T['sea_road']) . '</td>';
                            echo '<td>' . strtoupper($PONE0T['sea_road_array']['l_port']) . '</td>';
                            echo '<td>' . strtoupper($PONE0T['sea_road_array']['r_port']) . '</td>';
                        } else {
                            echo '<td>' . strtoupper($PONE0T['sea_road_array']['route']) . ' Transfer</td>';
                            echo '<td>' . strtoupper(($PONE0T['sea_road_array']['loading_company_name'] ?? '')) . '</td>';
                            echo '<td>' . strtoupper(($PONE0T['sea_road_array']['receiving_company_name'] ?? '')) . '</td>';
                        }
                        echo '<td>' . strtoupper($PONE0T['delivery_terms'] ?? 'Not Set!') . '</td>';
                        echo '<td>' . strtoupper($PONE0T['payment_details']['full_advance']) . '</td>';
                        ?>
            </table>
        </div>

        <table class="table table-hover table-bordered border-dark my-2 py-2 mt-3">
            <thead>
                <tr class="text-nowrap">
                    <th class="bg-dark text-white">#</th>
                    <th class="bg-dark text-white">GOODS Details</th>
                    <th class="bg-dark text-white">QTY</th>
                    <th class="bg-dark text-white">KGs</th>
                    <th class="bg-dark text-white">NET KGs</th>
                    <th class="bg-dark text-white">TOTAL</th>
                    <th class="bg-dark text-white">DIVIDE</th>
                    <th class="bg-dark text-white">PRICE</th>
                    <th class="bg-dark text-white">AMT</th>
                    <?php if ($PONE0T['type'] !== 'local') { ?>
                        <!-- <th class="bg-dark text-white" class="text-end">FINAL</th> -->
                    <?php } else { ?>
                        <th class="bg-dark text-white">Tax%</th>
                        <th class="bg-dark text-white">Tax.Amt</th>
                        <th class="bg-dark text-white">Final Amt</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalQty = $totalKgs = $totalNetKgs = $totalAmount = $totalTaxAmt = $totalFinalAmt = 0;
                foreach ($p1Data as $loading) {
                    $details = $loading['ldata']['good']['goods_json'];
                    echo '<tr>';
                    echo '<td>' . $details['sr'] . '</td>';
                    echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                    echo '<td>' . $loading['ldata']['good']['quantity_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                    echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                    echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                    echo '<td>' . $details['total'] . '</td>';
                    echo '<td>' . $details['divide'] . '</td>';
                    echo '<td>' . $details['rate1'] . '<sub>' . $details['currency1'] . '</sub></td>';
                    echo '<td>' . round($details['amount'], 2) . '</td>';

                    // Accumulate totals
                    $totalQty += $loading['ldata']['good']['quantity_no'];
                    $totalKgs += $details['total_kgs'];
                    $totalNetKgs += $details['net_kgs'];
                    $totalAmount += $details['amount'];

                    if ($PONE0T['type'] === 'local') {
                        echo '<td>' . $details['tax_percent'] . "%</td>";
                        echo '<td>' . $details['tax_amount'] . '</td>';
                        echo '<td>' . $details['total_with_tax'] . '</td>';
                        $totalTaxAmt += $details['tax_amount'];
                        $totalFinalAmt += $details['total_with_tax'];
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="2">Totals</td>
                    <td><?= $totalQty; ?></td>
                    <td><?= round($totalKgs, 2); ?></td>
                    <td><?= round($totalNetKgs, 2); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td><?= round($totalAmount, 2); ?></td>
                    <?php if ($PONE0T['type'] === 'local') { ?>
                        <td>-</td>
                        <td><?= round($totalTaxAmt, 2); ?></td>
                        <td><?= round($totalFinalAmt, 2); ?></td>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
        <!-- <----------- SALE START  -->
        <div style="margin: 2px; color:transparent;user-select:none;border-top:2px dashed #222;">!</div>
        <div class="row border-top border-dashed my-2 mt-0 py-2">
            <?php $PB =  'BORDER';  ?>
            <div class="col-3">
                <span><b>Loading Date:</b> <?= $PTWO0L['transfer']['loading_date'] ?? ''; ?></span><br>
                <span><b>Recieving Date:</b> <?= $PTWO0L['transfer']['receiving_date'] ?? ''; ?></span><br>
                <span><b>L <?= $PB; ?> Name:</b> <?= $PTWO0L['transfer']['loading_port_name'] ?? ''; ?></span><br>
                <span><b>R <?= $PB; ?> Name:</b> <?= $PTWO0L['transfer']['receiving_port_name'] ?? ''; ?></span><br>
            </div>
            <div class="col-3">
                <span><b>BOE Date:</b> <?= $PTWO0L['agent']['boe_date'] ?? ''; ?></span><br>
                <span><b>Pick Up Date:</b> <?= $PTWO0L['agent']['pick_up_date'] ?? ''; ?></span><br>
                <span><b>Waiting (days):</b> <?= $PTWO0L['agent']['waiting_days'] ?? ''; ?></span><br>
                <span><b>Return Date:</b> <?= $PTWO0L['agent']['return_date'] ?? ''; ?></span><br>
                <span><b>Transporter Name:</b> <?= $PTWO0L['agent']['transporter_name'] ?? ''; ?></span><br>
            </div>
            <div class="col-4">
                <span><b>Truck Number:</b> <?= $PTWO0L['agent']['truck_number'] ?? ''; ?></span><br>
                <span><b>Details:</b> <?= $PTWO0L['agent']['details'] ?? ''; ?></span><br>
                <span><b>Driver Name:</b> <?= $PTWO0L['agent']['driver_name'] ?? ''; ?></span><br>
                <span><b>Driver Number:</b> <?= $PTWO0L['agent']['driver_number'] ?? ''; ?></span><br>
            </div>
            <div class="col-2 border-bottom-2 border-dark text-end">
                <span><b> <?= $PTWO0T['p_s'] === 'p' ? 'Purchase' : 'Sale'; ?> #: </b><?= $p2TID; ?></span><br>
                <span><b> Date: </b><?= $PTWO0T['_date']; ?></span><br>
                <span><b> Country: </b><?= $PTWO0T['country']; ?></span><br>
                <span><b> Branch: </b><?= branchName($PTWO0T['branch_id']) ?? ''; ?></span><br>
            </div>
        </div>

        <div class="table-responsive my-2 py-2 border-top">
            <table class="table table-bordered border-dark">
                <thead>
                    <tr>
                        <th class="bg-dark text-white">ORIGIN</th>
                        <?php
                        if ($PTWO0T['type'] !== 'local') {
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
                        <td><?= $PTWO0L['good']['origin']; ?></td>
                        <?php
                        if ($PTWO0T['type'] !== 'local') {
                            echo '<td>' . strtoupper($PTWO0T['sea_road']) . '</td>';
                            echo '<td>' . strtoupper($PTWO0T['sea_road_array']['l_port']) . '</td>';
                            echo '<td>' . strtoupper($PTWO0T['sea_road_array']['r_port']) . '</td>';
                        } else {
                            echo '<td>' . strtoupper($PTWO0T['sea_road_array']['route']) . ' Transfer</td>';
                            echo '<td>' . strtoupper(($PTWO0T['sea_road_array']['loading_company_name'] ?? '')) . '</td>';
                            echo '<td>' . strtoupper(($PTWO0T['sea_road_array']['receiving_company_name'] ?? '')) . '</td>';
                        }
                        echo '<td>' . strtoupper($PTWO0T['delivery_terms'] ?? 'Not Set!') . '</td>';
                        echo '<td>' . strtoupper($PTWO0T['payment_details']['full_advance']) . '</td>';
                        ?>
            </table>
        </div>

        <table class="table table-hover table-bordered border-dark my-2 py-2 mt-3">
            <thead>
                <tr class="text-nowrap">
                    <th class="bg-dark text-white">#</th>
                    <th class="bg-dark text-white">GOODS Details</th>
                    <th class="bg-dark text-white">QTY</th>
                    <th class="bg-dark text-white">KGs</th>
                    <th class="bg-dark text-white">NET KGs</th>
                    <th class="bg-dark text-white">TOTAL</th>
                    <th class="bg-dark text-white">DIVIDE</th>
                    <th class="bg-dark text-white">PRICE</th>
                    <th class="bg-dark text-white">AMT</th>
                    <?php if ($PTWO0T['type'] !== 'local') { ?>
                        <!-- <th class="bg-dark text-white" class="text-end">FINAL</th> -->
                    <?php } else { ?>
                        <th class="bg-dark text-white">Tax%</th>
                        <th class="bg-dark text-white">Tax.Amt</th>
                        <th class="bg-dark text-white">Final Amt</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $totalQty = $totalKgs = $totalNetKgs = $totalAmount = $totalTaxAmt = $totalFinalAmt = 0;
                foreach ($p2Data as $loading) {
                    $details = $loading['ldata']['good']['goods_json'];
                    echo '<tr>';
                    echo '<td>' . $details['sr'] . '</td>';
                    echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                    echo '<td>' . $loading['ldata']['good']['quantity_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                    echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                    echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                    echo '<td>' . $details['total'] . '</td>';
                    echo '<td>' . $details['divide'] . '</td>';
                    echo '<td>' . $details['rate1'] . '<sub>' . $details['currency1'] . '</sub></td>';
                    echo '<td>' . round($details['amount'], 2) . '</td>';

                    // Accumulate totals
                    $totalQty += $loading['ldata']['good']['quantity_no'];
                    $totalKgs += $details['total_kgs'];
                    $totalNetKgs += $details['net_kgs'];
                    $totalAmount += $details['amount'];

                    if ($PTWO0T['type'] === 'local') {
                        echo '<td>' . $details['tax_percent'] . "%</td>";
                        echo '<td>' . $details['tax_amount'] . '</td>';
                        echo '<td>' . $details['total_with_tax'] . '</td>';
                        $totalTaxAmt += $details['tax_amount'];
                        $totalFinalAmt += $details['total_with_tax'];
                    }
                    echo '</tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="fw-bold bg-light">
                    <td colspan="2">Totals</td>
                    <td><?= $totalQty; ?></td>
                    <td><?= round($totalKgs, 2); ?></td>
                    <td><?= round($totalNetKgs, 2); ?></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td><?= round($totalAmount, 2); ?></td>
                    <?php if ($PTWO0T['type'] === 'local') { ?>
                        <td>-</td>
                        <td><?= round($totalTaxAmt, 2); ?></td>
                        <td><?= round($totalFinalAmt, 2); ?></td>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
        <?php
    } elseif ($WarehouseType === 'transit') {
        if ($printType === 'invoice') {
        ?>
            <div class="row">
                <div class="col-7">
                    <?php if (isset($PONE0L['notify'])) { ?>
                        <div>
                            <h5>Notify Party</h5>
                            <div class="hide-on-print">
                                <span><strong>Acc Name: </strong> <?= $PONE0L['notify']['np_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0L['notify']['np_acc_no']; ?></span><br>
                            </div>
                            <span><strong>Company:</strong> <?= getCompanyName($PONE0L['notify']['np_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                            <span><?= str_replace(getCompanyName($PONE0L['notify']['np_acc_kd_id']), '', $PONE0L['notify']['np_acc_details']); ?></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-3">
                    <img src="logo.jpg" style="border-radius: 100%; width: 110px; margin-bottom: 10px;" alt="">
                    <h6 class="text-primary fw-bold text-nowrap"><?= $warehouse_type . ' ' . $print_type; ?></h6>
                </div>
                <div class="col-2">
                    <span><b> <?= $PONE0T['p_s'] === 'p' ? 'Purchase' : 'Sale'; ?> #: </b><?= $p1TID; ?></span><br>
                    <span><b>Date:</b> <?= $PONE0T['_date']; ?></span><br>
                    <span><b>Country:</b> <?= $PONE0T['country']; ?></span><br>
                    <span><b>Branch:</b> <?= branchName($PONE0T['branch_id']) ?? ''; ?></span><br>
                </div>
            </div>

            <div class="row my-2 py-2 border-top">
                <div class="col-6">
                    <h5>Sale</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $PONE0T['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0T['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($PONE0T['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($PONE0T['cr_acc_kd_id']), '', $PONE0T['cr_acc_details']); ?></span>
                </div>
                <div class="col-6">
                    <h5>Purchase</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $PONE0T['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0T['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($PONE0T['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($PONE0T['dr_acc_kd_id']), '', $PONE0T['dr_acc_details']); ?></span>
                </div>
            </div>
            <?php
            $Arowid = intval($PONE0L['agent']['row_id']);
            $khaata_id = mysqli_fetch_assoc(mysqli_query($connect, "SELECT JSON_EXTRACT(khaata, '$.khaata_id') AS khaata_id FROM users WHERE id=$Arowid"))['khaata_id'];
            $data = json_decode(mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM khaata_details WHERE khaata_id=$khaata_id"))['json_data'], true);
            $WEIGHT_License = array_combine($data['indexes1'], $data['vals1']);
            $company_Name = $data['company_name'];
            ?>
            <div class="row py-2 my-2 border-top">
                <div class="col-4 my-2">
                    <span><b>Agent Company Name:</b> <?= $company_Name ?? 'Not Set'; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>Weight No:</b> <?= $WEIGHT_License['WEIGHT'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>License No:</b> <?= $WEIGHT_License['License'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>Loading Date:</b> <?= $PONE0L['transfer']['loading_date'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>Receiving Date:</b> <?= $PONE0L['transfer']['receiving_date'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>B/L No:</b> <?= $PONE0L['bl_no'] ?? ''; ?></span>
                </div>
                <div class="col-4 my-2">
                    <span><b>BOE No:</b> <?= $PONE0L['agent']['boe_no'] ?? ''; ?></span>
                </div>
                <div class="col-4 my-2">
                    <span><b>BOE Date:</b> <?= $PONE0L['agent']['boe_date'] ?? ''; ?></span>
                </div>
            </div>

            <div class="table-responsive my-2 py-2 border-top">
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th class="bg-dark text-white">ORIGIN</th>
                            <?php
                            if ($PONE0T['type'] !== 'local') {
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
                            <td><?= $PONE0L['good']['origin']; ?></td>
                            <?php
                            if ($PONE0T['type'] !== 'local') {
                                echo '<td>' . strtoupper($PONE0T['sea_road']) . '</td>';
                                echo '<td>' . strtoupper($PONE0T['sea_road_array']['l_port']) . '</td>';
                                echo '<td>' . strtoupper($PONE0T['sea_road_array']['r_port']) . '</td>';
                            } else {
                                echo '<td>' . strtoupper($PONE0T['sea_road_array']['route']) . ' Transfer</td>';
                                echo '<td>' . strtoupper(($PONE0T['sea_road_array']['loading_company_name'] ?? '')) . '</td>';
                                echo '<td>' . strtoupper(($PONE0T['sea_road_array']['receiving_company_name'] ?? '')) . '</td>';
                            }
                            echo '<td>' . strtoupper($PONE0T['delivery_terms'] ?? 'Not Set!') . '</td>';
                            echo '<td>' . strtoupper($PONE0T['payment_details']['full_advance']) . '</td>';
                            ?>
                </table>
            </div>

            <table class="table table-hover table-bordered border-dark my-2 py-2 mt-3">
                <thead>
                    <tr class="text-nowrap">
                        <th class="bg-dark text-white">#</th>
                        <th class="bg-dark text-white">Container</th>
                        <th class="bg-dark text-white">GOODS Details</th>
                        <th class="bg-dark text-white">QTY</th>
                        <th class="bg-dark text-white">KGs</th>
                        <th class="bg-dark text-white">NET KGs</th>
                        <th class="bg-dark text-white">TOTAL</th>
                        <th class="bg-dark text-white">DIVIDE</th>
                        <th class="bg-dark text-white">PRICE</th>
                        <th class="bg-dark text-white">AMT</th>
                        <?php if ($PONE0T['type'] !== 'local') { ?>
                            <!-- <th class="bg-dark text-white" class="text-end">FINAL</th> -->
                        <?php } else { ?>
                            <th class="bg-dark text-white">Tax%</th>
                            <th class="bg-dark text-white">Tax.Amt</th>
                            <th class="bg-dark text-white">Final Amt</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalQty = $totalKgs = $totalNetKgs = $totalAmount = $totalTaxAmt = $totalFinalAmt = 0;
                    foreach ($p1Data as $loading) {
                        $details = $loading['ldata']['good']['goods_json'];
                        echo '<tr>';
                        echo '<td>' . $details['sr'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['container_no'] ?? '' . '</td>';
                        echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['quantity_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                        echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                        echo '<td>' . $details['total'] . '</td>';
                        echo '<td>' . $details['divide'] . '</td>';
                        echo '<td>' . $details['rate1'] . '<sub>' . $details['currency1'] . '</sub></td>';
                        echo '<td>' . round($details['amount'], 2) . '</td>';

                        // Accumulate totals
                        $totalQty += $loading['ldata']['good']['quantity_no'];
                        $totalKgs += $details['total_kgs'];
                        $totalNetKgs += $details['net_kgs'];
                        $totalAmount += $details['amount'];

                        if ($PONE0T['type'] === 'local') {
                            echo '<td>' . $details['tax_percent'] . "%</td>";
                            echo '<td>' . $details['tax_amount'] . '</td>';
                            echo '<td>' . $details['total_with_tax'] . '</td>';
                            $totalTaxAmt += $details['tax_amount'];
                            $totalFinalAmt += $details['total_with_tax'];
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="3">Totals</td>
                        <td><?= $totalQty; ?></td>
                        <td><?= round($totalKgs, 2); ?></td>
                        <td><?= round($totalNetKgs, 2); ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?= round($totalAmount, 2); ?></td>
                        <?php if ($PONE0T['type'] === 'local') { ?>
                            <td>-</td>
                            <td><?= round($totalTaxAmt, 2); ?></td>
                            <td><?= round($totalFinalAmt, 2); ?></td>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
            <!-- <----------- SALE START  -->
            <div style="margin: 2px; color:transparent;user-select:none;border-top:2px dashed #222;">!</div>
            <div class="row border-top border-dashed my-2 mt-0 py-2">
                <?php $PB = $PTWO0L['transfer']['transfer_by'] === 'sea' ? 'PORT' : 'BORDER';  ?>
                <div class="col-3">
                    <span><b>Loading Date:</b> <?= $PTWO0L['transfer']['loading_date'] ?? ''; ?></span><br>
                    <span><b>Recieving Date:</b> <?= $PTWO0L['transfer']['receiving_date'] ?? ''; ?></span><br>
                    <span><b>L <?= $PB; ?> Name:</b> <?= $PTWO0L['transfer']['loading_port_name'] ?? ''; ?></span><br>
                    <span><b>R <?= $PB; ?> Name:</b> <?= $PTWO0L['transfer']['receiving_port_name'] ?? ''; ?></span><br>
                </div>
                <div class="col-3">
                    <span><b>BOE Date:</b> <?= $PTWO0L['agent']['boe_date'] ?? ''; ?></span><br>
                    <span><b>Pick Up Date:</b> <?= $PTWO0L['agent']['pick_up_date'] ?? ''; ?></span><br>
                    <span><b>Waiting (days):</b> <?= $PTWO0L['agent']['waiting_days'] ?? ''; ?></span><br>
                    <span><b>Return Date:</b> <?= $PTWO0L['agent']['return_date'] ?? ''; ?></span><br>
                    <span><b>Transporter Name:</b> <?= $PTWO0L['agent']['transporter_name'] ?? ''; ?></span><br>
                </div>
                <div class="col-4">
                    <span><b>Truck Number:</b> <?= $PTWO0L['agent']['truck_number'] ?? ''; ?></span><br>
                    <span><b>Details:</b> <?= $PTWO0L['agent']['details'] ?? ''; ?></span><br>
                    <span><b>Driver Name:</b> <?= $PTWO0L['agent']['driver_name'] ?? ''; ?></span><br>
                    <span><b>Driver Number:</b> <?= $PTWO0L['agent']['driver_number'] ?? ''; ?></span><br>
                </div>
                <div class="col-2 border-bottom-2 border-dark text-end">
                    <span><b> <?= $PTWO0T['p_s'] === 'p' ? 'Purchase' : 'Sale'; ?> #: </b><?= $p2TID; ?></span><br>
                    <span><b> Date: </b><?= $PTWO0T['_date']; ?></span><br>
                    <span><b> Country: </b><?= $PTWO0T['country']; ?></span><br>
                    <span><b> Branch: </b><?= branchName($PTWO0T['branch_id']) ?? ''; ?></span><br>
                </div>
            </div>

            <div class="table-responsive my-2 py-2 border-top">
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th class="bg-dark text-white">ORIGIN</th>
                            <?php
                            if ($PTWO0T['type'] !== 'local') {
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
                            <td><?= $PTWO0L['good']['origin']; ?></td>
                            <?php
                            if ($PTWO0T['type'] !== 'local') {
                                echo '<td>' . strtoupper($PTWO0T['sea_road']) . '</td>';
                                echo '<td>' . strtoupper($PTWO0T['sea_road_array']['l_port']) . '</td>';
                                echo '<td>' . strtoupper($PTWO0T['sea_road_array']['r_port']) . '</td>';
                            } else {
                                echo '<td>' . strtoupper($PTWO0T['sea_road_array']['route']) . ' Transfer</td>';
                                echo '<td>' . strtoupper(($PTWO0T['sea_road_array']['loading_company_name'] ?? '')) . '</td>';
                                echo '<td>' . strtoupper(($PTWO0T['sea_road_array']['receiving_company_name'] ?? '')) . '</td>';
                            }
                            echo '<td>' . strtoupper($PTWO0T['delivery_terms'] ?? 'Not Set!') . '</td>';
                            echo '<td>' . strtoupper($PTWO0T['payment_details']['full_advance']) . '</td>';
                            ?>
                </table>
            </div>

            <table class="table table-hover table-bordered border-dark my-2 py-2 mt-3">
                <thead>
                    <tr class="text-nowrap">
                        <th class="bg-dark text-white">#</th>
                        <th class="bg-dark text-white">Container</th>
                        <th class="bg-dark text-white">GOODS Details</th>
                        <th class="bg-dark text-white">QTY</th>
                        <th class="bg-dark text-white">KGs</th>
                        <th class="bg-dark text-white">NET KGs</th>
                        <th class="bg-dark text-white">TOTAL</th>
                        <th class="bg-dark text-white">DIVIDE</th>
                        <th class="bg-dark text-white">PRICE</th>
                        <th class="bg-dark text-white">AMT</th>
                        <?php if ($PTWO0T['type'] !== 'local') { ?>
                            <!-- <th class="bg-dark text-white" class="text-end">FINAL</th> -->
                        <?php } else { ?>
                            <th class="bg-dark text-white">Tax%</th>
                            <th class="bg-dark text-white">Tax.Amt</th>
                            <th class="bg-dark text-white">Final Amt</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalQty = $totalKgs = $totalNetKgs = $totalAmount = $totalTaxAmt = $totalFinalAmt = 0;
                    foreach ($p2Data as $loading) {
                        $details = $loading['ldata']['good']['goods_json'];
                        echo '<tr>';
                        echo '<td>' . $details['sr'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['container_no'] ?? '' . '</td>';
                        echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['quantity_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                        echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                        echo '<td>' . $details['total'] . '</td>';
                        echo '<td>' . $details['divide'] . '</td>';
                        echo '<td>' . $details['rate1'] . '<sub>' . $details['currency1'] . '</sub></td>';
                        echo '<td>' . round($details['amount'], 2) . '</td>';

                        // Accumulate totals
                        $totalQty += $loading['ldata']['good']['quantity_no'];
                        $totalKgs += $details['total_kgs'];
                        $totalNetKgs += $details['net_kgs'];
                        $totalAmount += $details['amount'];

                        if ($PTWO0T['type'] === 'local') {
                            echo '<td>' . $details['tax_percent'] . "%</td>";
                            echo '<td>' . $details['tax_amount'] . '</td>';
                            echo '<td>' . $details['total_with_tax'] . '</td>';
                            $totalTaxAmt += $details['tax_amount'];
                            $totalFinalAmt += $details['total_with_tax'];
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="3">Totals</td>
                        <td><?= $totalQty; ?></td>
                        <td><?= round($totalKgs, 2); ?></td>
                        <td><?= round($totalNetKgs, 2); ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?= round($totalAmount, 2); ?></td>
                        <?php if ($PTWO0T['type'] === 'local') { ?>
                            <td>-</td>
                            <td><?= round($totalTaxAmt, 2); ?></td>
                            <td><?= round($totalFinalAmt, 2); ?></td>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>

        <?php
        } elseif ($printType === 'packing-list') {
        } else {
            echo '<h2 class="fw-bold text-center p-2 m-2 text-danger">IN-VALID PRINT TYPE SELECTION!</h2>';
        }
    } elseif ($WarehouseType === 'freezone-import') {
        if ($printType === 'invoice') {
        } elseif ($printType === 'packing-list') {
        } else {
            echo '<h2 class="fw-bold text-center p-2 m-2 text-danger">IN-VALID PRINT TYPE SELECTION!</h2>';
        }
    } elseif ($WarehouseType === 'local-import') {
        if ($printType === 'invoice') {
        } elseif ($printType === 'packing-list') {
        } else {
            echo '<h2 class="fw-bold text-center p-2 m-2 text-danger">IN-VALID PRINT TYPE SELECTION!</h2>';
        }
    } elseif ($WarehouseType === 'import-re-export') {
        if ($printType === 'invoice') {
        ?>
            <div class="row">
                <div class="col-7">
                    <?php if (isset($PONE0L['notify'])) { ?>
                        <div class="border-top">
                            <h5>Notify Party</h5>
                            <div class="hide-on-print">
                                <span><strong>Acc Name: </strong> <?= $PONE0L['notify']['np_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0L['notify']['np_acc_no']; ?></span><br>
                            </div>
                            <span><strong>Company:</strong> <?= getCompanyName($PONE0L['notify']['np_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                            <span><?= str_replace(getCompanyName($PONE0L['notify']['np_acc_kd_id']), '', $PONE0L['notify']['np_acc_details']); ?></span>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-3">
                    <img src="logo.jpg" style="border-radius: 100%; width: 110px; margin-bottom: 10px;" alt="">
                    <h6 class="text-primary fw-bold text-nowrap"><?= "IMPORTER Company Invoice"; ?></h6>
                </div>
                <div class="col-2">
                    <span><b> <?= $PONE0T['p_s'] === 'p' ? 'Purchase' : 'Sale'; ?> #: </b><?= $p1TID; ?></span><br>
                    <span><b>Date:</b> <?= $PONE0T['_date']; ?></span><br>
                    <span><b>Country:</b> <?= $PONE0T['country']; ?></span><br>
                    <span><b>Branch:</b> <?= branchName($PONE0T['branch_id']) ?? ''; ?></span><br>
                </div>
            </div>

            <div class="row my-2 py-2 border-top">
                <div class="col-6 text-center">
                    <h5>Sale</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $PONE0T['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0T['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($PONE0T['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($PONE0T['cr_acc_kd_id']), '', $PONE0T['cr_acc_details']); ?></span>
                </div>
                <div class="col-6 text-center">
                    <h5>Purchase</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $PONE0T['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PONE0T['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($PONE0T['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($PONE0T['dr_acc_kd_id']), '', $PONE0T['dr_acc_details']); ?></span>
                </div>
            </div>
            <?php
            if (isset($PONE0L['agent'])) {
                $Arowid = intval($PONE0L['agent']['row_id']);
                $khaata_id = mysqli_fetch_assoc(mysqli_query($connect, "SELECT JSON_EXTRACT(khaata, '$.khaata_id') AS khaata_id FROM users WHERE id=$Arowid"))['khaata_id'];
                $data = json_decode(mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM khaata_details WHERE khaata_id=$khaata_id"))['json_data'], true);
                $WEIGHT_License = array_combine($data['indexes1'], $data['vals1']);
                $company_Name = $data['company_name'];
            ?>

                <div class="row py-2 my-2 border-top">
                    <div class="col-4 my-2">
                        <span><b>Agent Company Name:</b> <?= $company_Name ?? 'Not Set'; ?></span>
                    </div>
                    <div class="col-2 my-2">
                        <span><b>Weight No:</b> <?= $WEIGHT_License['WEIGHT'] ?? ''; ?></span>
                    </div>
                    <div class="col-2 my-2">
                        <span><b>License No:</b> <?= $WEIGHT_License['License'] ?? ''; ?></span>
                    </div>
                    <div class="col-2 my-2">
                        <span><b>Loading Date:</b> <?= $PONE0L['transfer']['loading_date'] ?? ''; ?></span>
                    </div>
                    <div class="col-2 my-2">
                        <span><b>Receiving Date:</b> <?= $PONE0L['transfer']['receiving_date'] ?? ''; ?></span>
                    </div>
                    <div class="col-2 my-2">
                        <span><b>B/L No:</b> <?= $PONE0L['bl_no'] ?? ''; ?></span>
                    </div>
                    <div class="col-4 my-2">
                        <span><b>BOE No:</b> <?= $PONE0L['agent']['boe_no'] ?? ''; ?></span>
                    </div>
                    <div class="col-4 my-2">
                        <span><b>BOE Date:</b> <?= $PONE0L['agent']['boe_date'] ?? ''; ?></span>
                    </div>
                </div>
            <?php } ?>

            <div class="table-responsive my-2 py-2 border-top">
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th class="bg-warning text-dark">ORIGIN</th>
                            <?php
                            if ($PONE0T['type'] !== 'local') {
                                echo '<th class="bg-warning text-dark">SHIP</th>';
                            } else {
                                echo '<th class="bg-warning text-dark">LOCAL Transfer</th>';
                            }
                            ?>
                            <th class="bg-warning text-dark">Loading</th>
                            <th class="bg-warning text-dark">Receiving</th>
                            <th class="bg-warning text-dark">Delivery Terms</th>
                            <th class="bg-warning text-dark">Payment Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $PONE0L['good']['origin']; ?></td>
                            <?php
                            if ($PONE0T['type'] !== 'local') {
                                echo '<td>' . strtoupper($PONE0T['sea_road']) . '</td>';
                                echo '<td>' . strtoupper($PONE0T['sea_road_array']['l_port']) . '</td>';
                                echo '<td>' . strtoupper($PONE0T['sea_road_array']['r_port']) . '</td>';
                            } else {
                                echo '<td>' . strtoupper($PONE0T['sea_road_array']['route']) . ' Transfer</td>';
                                echo '<td>' . strtoupper(($PONE0T['sea_road_array']['loading_company_name'] ?? '')) . '</td>';
                                echo '<td>' . strtoupper(($PONE0T['sea_road_array']['receiving_company_name'] ?? '')) . '</td>';
                            }
                            echo '<td>' . strtoupper($PONE0T['delivery_terms'] ?? 'Not Set!') . '</td>';
                            echo '<td>' . strtoupper($PONE0T['payment_details']['full_advance']) . '</td>';
                            ?>
                </table>
            </div>
            <style>
                table>* {
                    font-size: 11px;
                }
            </style>
            <table class="table table-hover table-bordered border-dark my-2 py-2 mt-3">
                <thead>
                    <tr class="text-nowrap">
                        <th class="bg-dark text-white">#</th>
                        <th class="bg-dark text-white">Container</th>
                        <th class="bg-dark text-white">GOODS Details</th>
                        <th class="bg-dark text-white">QTY</th>
                        <th class="bg-dark text-white">KGs</th>
                        <th class="bg-dark text-white">NET KGs</th>
                        <th class="bg-dark text-white">TOTAL</th>
                        <th class="bg-dark text-white">DIVIDE</th>
                        <th class="bg-dark text-white">PRICE</th>
                        <th class="bg-dark text-white">AMT</th>
                        <?php if ($PONE0T['type'] !== 'local') { ?>
                            <!-- <th class="bg-dark text-white" class="text-end">FINAL</th> -->
                        <?php } else { ?>
                            <th class="bg-dark text-white">Tax%</th>
                            <th class="bg-dark text-white">Tax.Amt</th>
                            <th class="bg-dark text-white">Final Amt</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalQty = $totalKgs = $totalNetKgs = $totalAmount = $totalTaxAmt = $totalFinalAmt = 0;
                    foreach ($p1Data as $loading) {
                        $details = $loading['ldata']['good']['goods_json'];
                        echo '<tr>';
                        echo '<td>' . $details['sr'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['container_no'] ?? '' . '</td>';
                        echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['quantity_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                        echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                        echo '<td>' . $details['total'] . '</td>';
                        echo '<td>' . $details['divide'] . '</td>';
                        echo '<td>' . $details['rate1'] . '<sub>' . $details['currency1'] . '</sub></td>';
                        echo '<td>' . round($details['amount'], 2) . '</td>';

                        // Accumulate totals
                        $totalQty += $loading['ldata']['good']['quantity_no'];
                        $totalKgs += $details['total_kgs'];
                        $totalNetKgs += $details['net_kgs'];
                        $totalAmount += $details['amount'];

                        if ($PONE0T['type'] === 'local') {
                            echo '<td>' . $details['tax_percent'] . "%</td>";
                            echo '<td>' . $details['tax_amount'] . '</td>';
                            echo '<td>' . $details['total_with_tax'] . '</td>';
                            $totalTaxAmt += $details['tax_amount'];
                            $totalFinalAmt += $details['total_with_tax'];
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="3">Totals</td>
                        <td><?= $totalQty; ?></td>
                        <td><?= round($totalKgs, 2); ?></td>
                        <td><?= round($totalNetKgs, 2); ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?= round($totalAmount, 2); ?></td>
                        <?php if ($PONE0T['type'] === 'local') { ?>
                            <td>-</td>
                            <td><?= round($totalTaxAmt, 2); ?></td>
                            <td><?= round($totalFinalAmt, 2); ?></td>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
            <!-- SALE START-->
            <div style="margin: 15px 0 5px 0; color:transparent;user-select:none;border-top:2px dashed #222;">!</div>
            <div class="row mb-2 py-2">
                <div class="col-5">
                    <h5>Sale</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $PTWO0T['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PTWO0T['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($PTWO0T['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($PTWO0T['cr_acc_kd_id']), '', $PTWO0T['cr_acc_details']); ?></span>
                </div>
                <div class="col-5">
                    <h6 class="text-primary fw-bold text-nowrap"><?= "EXPORTER COMPANY INVOICE"; ?></h6>
                    <h5>Purchase</h5>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $PTWO0T['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $PTWO0T['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($PTWO0T['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($PTWO0T['dr_acc_kd_id']), '', $PONE0T['dr_acc_details']); ?></span>
                </div>
                <div class="col-2 text-end">
                    <span><b> <?= $PTWO0T['p_s'] === 'p' ? 'Purchase' : 'Sale'; ?> #: </b><?= $p2TID; ?></span><br>
                    <span><b> Date: </b><?= $PTWO0T['_date']; ?></span><br>
                    <span><b> Country: </b><?= $PTWO0T['country']; ?></span><br>
                    <span><b> Branch: </b><?= branchName($PTWO0T['branch_id']) ?? ''; ?></span><br>
                </div>
            </div>

            <?php
            $Arowid = intval($PTWO0L['agent']['row_id']);
            $khaata_id = mysqli_fetch_assoc(mysqli_query($connect, "SELECT JSON_EXTRACT(khaata, '$.khaata_id') AS khaata_id FROM users WHERE id=$Arowid"))['khaata_id'];
            $data = json_decode(mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM khaata_details WHERE khaata_id=$khaata_id"))['json_data'], true);
            $WEIGHT_License = array_combine($data['indexes1'], $data['vals1']);
            $company_Name = $data['company_name'];
            ?>

            <div class="row py-2 my-2 border-top">
                <div class="col-4 my-2">
                    <span><b>Agent Company Name:</b> <?= $company_Name ?? 'Not Set'; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>Weight No:</b> <?= $WEIGHT_License['WEIGHT'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>License No:</b> <?= $WEIGHT_License['License'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>Loading Date:</b> <?= $PONE0L['transfer']['loading_date'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>Receiving Date:</b> <?= $PONE0L['transfer']['receiving_date'] ?? ''; ?></span>
                </div>
                <div class="col-2 my-2">
                    <span><b>B/L No:</b> <?= $PONE0L['bl_no'] ?? ''; ?></span>
                </div>
                <div class="col-4 my-2">
                    <span><b>BOE No:</b> <?= $PONE0L['agent']['boe_no'] ?? ''; ?></span>
                </div>
                <div class="col-4 my-2">
                    <span><b>BOE Date:</b> <?= $PONE0L['agent']['boe_date'] ?? ''; ?></span>
                </div>
            </div>

            <div class="table-responsive my-2 py-2 border-top">
                <table class="table table-bordered border-dark">
                    <thead>
                        <tr>
                            <th class="bg-warning text-dark">ORIGIN</th>
                            <?php
                            if ($PTWO0T['type'] !== 'local') {
                                echo '<th class="bg-warning text-dark">SHIP</th>';
                            } else {
                                echo '<th class="bg-warning text-dark">LOCAL Transfer</th>';
                            }
                            ?>
                            <th class="bg-warning text-dark">Loading</th>
                            <th class="bg-warning text-dark">Receiving</th>
                            <th class="bg-warning text-dark">Delivery Terms</th>
                            <th class="bg-warning text-dark">Payment Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $PTWO0L['good']['origin']; ?></td>
                            <?php
                            if ($PTWO0T['type'] !== 'local') {
                                echo '<td>' . strtoupper($PTWO0T['sea_road']) . '</td>';
                                echo '<td>' . strtoupper($PTWO0T['sea_road_array']['l_port']) . '</td>';
                                echo '<td>' . strtoupper($PTWO0T['sea_road_array']['r_port']) . '</td>';
                            } else {
                                echo '<td>' . strtoupper($PTWO0T['sea_road_array']['route']) . ' Transfer</td>';
                                echo '<td>' . strtoupper(($PTWO0T['sea_road_array']['loading_company_name'] ?? '')) . '</td>';
                                echo '<td>' . strtoupper(($PTWO0T['sea_road_array']['receiving_company_name'] ?? '')) . '</td>';
                            }
                            echo '<td>' . strtoupper($PTWO0T['delivery_terms'] ?? 'Not Set!') . '</td>';
                            echo '<td>' . strtoupper($PTWO0T['payment_details']['full_advance']) . '</td>';
                            ?>
                </table>
            </div>

            <table class="table table-hover table-bordered border-dark my-2 py-2 mt-3">
                <thead>
                    <tr class="text-nowrap">
                        <th class="bg-dark text-white">#</th>
                        <th class="bg-dark text-white">Container</th>
                        <th class="bg-dark text-white">GOODS Details</th>
                        <th class="bg-dark text-white">QTY</th>
                        <th class="bg-dark text-white">KGs</th>
                        <th class="bg-dark text-white">NET KGs</th>
                        <th class="bg-dark text-white">TOTAL</th>
                        <th class="bg-dark text-white">DIVIDE</th>
                        <th class="bg-dark text-white">PRICE</th>
                        <th class="bg-dark text-white">AMT</th>
                        <?php if ($PTWO0T['type'] !== 'local') { ?>
                            <!-- <th class="bg-dark text-white" class="text-end">FINAL</th> -->
                        <?php } else { ?>
                            <th class="bg-dark text-white">Tax%</th>
                            <th class="bg-dark text-white">Tax.Amt</th>
                            <th class="bg-dark text-white">Final Amt</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalQty = $totalKgs = $totalNetKgs = $totalAmount = $totalTaxAmt = $totalFinalAmt = 0;
                    foreach ($p2Data as $loading) {
                        $details = $loading['ldata']['good']['goods_json'];
                        echo '<tr>';
                        echo '<td>' . $details['sr'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['container_no'] ?? '' . '</td>';
                        echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                        echo '<td>' . $loading['ldata']['good']['quantity_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                        echo '<td>' . round($details['net_kgs'], 2) . '<sub>' . $details['divide'] . '</sub></td>';
                        echo '<td>' . $details['total'] . '</td>';
                        echo '<td>' . $details['divide'] . '</td>';
                        echo '<td>' . $details['rate1'] . '<sub>' . $details['currency1'] . '</sub></td>';
                        echo '<td>' . round($details['amount'], 2) . '</td>';

                        // Accumulate totals
                        $totalQty += $loading['ldata']['good']['quantity_no'];
                        $totalKgs += $details['total_kgs'];
                        $totalNetKgs += $details['net_kgs'];
                        $totalAmount += $details['amount'];

                        if ($PTWO0T['type'] === 'local') {
                            echo '<td>' . $details['tax_percent'] . "%</td>";
                            echo '<td>' . $details['tax_amount'] . '</td>';
                            echo '<td>' . $details['total_with_tax'] . '</td>';
                            $totalTaxAmt += $details['tax_amount'];
                            $totalFinalAmt += $details['total_with_tax'];
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="3">Totals</td>
                        <td><?= $totalQty; ?></td>
                        <td><?= round($totalKgs, 2); ?></td>
                        <td><?= round($totalNetKgs, 2); ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td><?= round($totalAmount, 2); ?></td>
                        <?php if ($PTWO0T['type'] === 'local') { ?>
                            <td>-</td>
                            <td><?= round($totalTaxAmt, 2); ?></td>
                            <td><?= round($totalFinalAmt, 2); ?></td>
                        <?php } ?>
                    </tr>
                </tfoot>
            </table>
<?php
        } elseif ($printType === 'packing-list') {
        } else {
            echo '<h2 class="fw-bold text-center p-2 m-2 text-danger">IN-VALID PRINT TYPE SELECTION!</h2>';
        }
    } elseif ($WarehouseType === 'local-export') {
        if ($printType === 'invoice') {
        } elseif ($printType === 'packing-list') {
        } else {
            echo '<h2 class="fw-bold text-center p-2 m-2 text-danger">IN-VALID PRINT TYPE SELECTION!</h2>';
        }
    } elseif ($WarehouseType === 'local-market') {
        if ($printType === 'invoice') {
        } elseif ($printType === 'packing-list') {
        } else {
            echo '<h2 class="fw-bold text-center p-2 m-2 text-danger">IN-VALID PRINT TYPE SELECTION!</h2>';
        }
    } else {
        echo '<h2 class="fw-bold text-center p-2 m-2 text-danger">IN-VALID WAREHOUSE SELECTION!</h2>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?= ucfirst($p1Ttype) . '#' . $p1TID . ' Allot: ' . $PONE0L['good']['goods_json']['allotment_name'] . ' ' . $print_type; ?> Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        <?php
        printDesign($_GET['warehouse_type'] === 'all' ? $generalWarehouse : $_GET['warehouse_type'], $_GET['print_type']); ?>
    </div>
</body>

</html>