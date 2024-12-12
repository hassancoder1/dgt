<?php
$page_title = 'GOODS STOCK => TRANSFER GOODS';
$pageURL = 'goods-stock-transfer-goods';
include("header.php");

$resetFilters = $size = $brand = $origin = $goods_id = $goods_name = $date_from = $date_to = $net_kgs = $qty_no = '';
$is_search = false;

global $connect;
$rows_per_page = 50;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

$conditions = [];

// Handle filters
if ($_GET) {
    $resetFilters = removeFilter($pageURL);

    if (!empty($_GET['size'])) {
        $size = mysqli_real_escape_string($connect, $_GET['size']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.size') = '$size'";
    }
    if (!empty($_GET['brand'])) {
        $brand = mysqli_real_escape_string($connect, $_GET['brand']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.brand') = '$brand'";
    }
    if (!empty($_GET['origin'])) {
        $origin = mysqli_real_escape_string($connect, $_GET['origin']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.origin') = '$origin'";
    }
    if (!empty($_GET['goods_id'])) {
        $goods_id = mysqli_real_escape_string($connect, $_GET['goods_id']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.goods_id') = '$goods_id'";
    }
    if (!empty($_GET['date_from'])) {
        $date_from = mysqli_real_escape_string($connect, $_GET['date_from']);
        $conditions[] = "created_at >= '$date_from'";
    }
    if (!empty($_GET['date_to'])) {
        $date_to = mysqli_real_escape_string($connect, $_GET['date_to']);
        $conditions[] = "created_at <= '$date_to'";
    }
    if (!empty($_GET['net_kgs'])) {
        $net_kgs = mysqli_real_escape_string($connect, $_GET['net_kgs']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.net_weight') = '$net_kgs'";
    }
    if (!empty($_GET['qty_no'])) {
        $qty_no = mysqli_real_escape_string($connect, $_GET['qty_no']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.quantity_no') = '$qty_no'";
    }
}

// Apply conditions separately to each part of the UNION
$where_clause = !empty($conditions) ? ' AND ' . implode(' AND ', $conditions) : '';

$sql = "
    SELECT id, MAX(sr_no) AS sr_no, p_id, type, p_type, goods_details, shipping_details, agent_details, created_at, bl_no, loading_details, receiving_details, 'general' AS source 
    FROM general_loading 
    WHERE agent_details IS NOT NULL {$where_clause} 
    GROUP BY bl_no
    UNION
    SELECT id, MAX(sr_no) AS sr_no, p_id, type, 'local' as p_type, goods_details, transfer_details AS shipping_details, 'dummy_agent' as agent_details, created_at, uid AS bl_no, 'dummy1' AS dummy1, 'dummy2' AS dummy2, 'local' AS source 
    FROM local_loading 
    WHERE goods_details IS NOT NULL {$where_clause} 
    GROUP BY uid";

$is_search = !empty($conditions);

$total_rows_result = mysqli_query($connect, "SELECT COUNT(*) AS total FROM ({$sql}) AS subquery");
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

$sql .= " ORDER BY p_id, created_at LIMIT $rows_per_page OFFSET $offset";

$entries = mysqli_query($connect, $sql);
$total_pages = ceil($total_rows / $rows_per_page);
?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>

<div class="mx-2" style="margin-top:-40px;">
    <form method="get" class="row">
        <div class="col-md-12">
            <!-- 60% Section -->
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $page_title; ?></h5>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="goods_id" class="form-label">Goods</label>
                            <select id="goods_id" name="goods_id" class="form-select form-select-sm">
                                <option value="">ALL GOODS</option>
                                <?php $goods = fetch('goods');
                                while ($good = mysqli_fetch_assoc($goods)) {
                                    $g_selected = $good['id'] == $goods_id ? 'selected' : '';
                                    echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="size" class="form-label">Size</label>
                            <select class="form-select form-select-sm" name="size" id="size">
                                <option value="">ALL SIZE</option>
                                <?php
                                $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM transaction_items");
                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                    $size_selected = $size_s['size'] == $size ? 'selected' : '';
                                    echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="brand" class="form-label">Brand</label>
                            <select class="form-select form-select-sm" name="brand" id="brand">
                                <option value="">ALL BRAND</option>
                                <?php
                                $goods_brands = mysqli_query($connect, "SELECT DISTINCT brand FROM transaction_items");
                                while ($g_brand = mysqli_fetch_assoc($goods_brands)) {
                                    $brand_selected = $g_brand['brand'] == $brand ? 'selected' : '';
                                    echo '<option ' . $brand_selected . ' value="' . $g_brand['brand'] . '">' . $g_brand['brand'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3 mt-2">
                            <span><b>Total Quantity No: </b><span id="show_total_qty_no"></span></span><br>
                            <span><b>Total Gross Net Weight KGS: </b><span id="show_total_gross_weight_kgs"></span></span><br>
                            <span><b>Total Net Weight KGS: </b><span id="show_total_net_weight_kgs"></span></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="origin" class="form-label">Origin</label>
                            <select class="form-select form-select-sm" name="origin" id="origin">
                                <option value="">ALL ORIGIN</option>
                                <?php
                                $origins = mysqli_query($connect, "SELECT DISTINCT origin FROM transaction_items");
                                while ($origin_s = mysqli_fetch_assoc($origins)) {
                                    $origin_selected = $origin_s['origin'] == $origin ? 'selected' : '';
                                    echo '<option ' . $origin_selected . ' value="' . $origin_s['origin'] . '">' . $origin_s['origin'] . '</option>';
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" name="date_from" id="date_from" class="form-control form-control-sm" value="<?php echo $date_from; ?>">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" name="date_to" id="date_to" class="form-control form-control-sm" value="<?php echo $date_to; ?>">
                        </div>

                        <div class="col-md-3 mt-4">
                            <div class="d-flex justify-content-end gap-1">
                                <?= $resetFilters; ?>
                                <button type="submit" class="btn btn-success btn-sm">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Table Section -->
<div class="row mx-1">
    <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm fix-head-table mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No.</th>
                                <th>P/S# (SR#)</th>
                                <th>BL / UID (Count)</th>
                                <th>Type</th>
                                <th>WareHouse</th>
                                <th>Goods Name / ORIGIN</th>
                                <th>Quantity</th>
                                <th>Gross.KGS</th>
                                <th>Net.KGS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $offset + 1;
                            $entryCount = 1;
                            $currentPID = null;
                            $entryCountPerBL = [];
                            $generalBlNos = mysqli_fetch_all(mysqli_query($connect, "SELECT bl_no FROM general_loading"));
                            $localBlNos = mysqli_fetch_all(mysqli_query($connect, "SELECT uid FROM local_loading"));
                            $flatBlNos = array_merge(
                                array_map(function ($item) {
                                    return $item[0];
                                }, $generalBlNos),
                                array_map(function ($item) {
                                    return $item[0];
                                }, $localBlNos)
                            );
                            $entryCountPerBL = array_count_values($flatBlNos);
                            if (mysqli_num_rows($entries) > 0):
                                $total_qty_no = $total_gross_weight_kgs = $total_net_weight_kgs = 0;

                                while ($entry = mysqli_fetch_assoc($entries)) {
                                    $goodsDetails = json_decode($entry['goods_details'], true);
                                    $shippingDetails = json_decode($entry['shipping_details'], true);
                                    $agent = json_decode($entry['agent_details'], true);
                                    if ($currentPID !== $entry['p_id']) {
                                        $currentPID = $entry['p_id'];
                                        $entryCount = 1;
                                    } else {
                                        $entryCount++;
                                    }
                                    $route = $shippingDetails['transfer_by'] ?? $shippingDetails['route'];
                                    $unique_code = $entry['type'] . $entry['p_type'][0] . (isset($shippingDetails['route'])
                                        ? ($shippingDetails['route'] === 'local' ? 'ld' : 'wr')
                                        : ($shippingDetails['transfer_by'] === 'sea' ? 'se' : 'rd'))
                                        . '_' . $entry['p_id'] . '_' . $entry['bl_no'];
                            ?>
                                    <tr class="text-nowrap">
                                        <td><?= htmlspecialchars($i); ?></td>
                                        <td class="pointer" onclick="window.location.href = '?view=1&unique_code=<?= $unique_code; ?>';">
                                            <b><?= ucfirst($entry['type']); ?>#</b> <?= htmlspecialchars($entry['p_id']); ?> (<?= $entryCount; ?>)
                                        </td>
                                        <td><?= htmlspecialchars($entry['source'] === 'general' ? 'B/L: ' . $entry['bl_no'] : 'UID: ' . $entry['bl_no']); ?> (<?= $entryCountPerBL[$entry['bl_no']]; ?>)</td>
                                        <td><?= ucfirst(htmlspecialchars($entry['p_type'])); ?></td>
                                        <td><?= htmlspecialchars($entry['source'] === 'general' ? ($agent['cargo_transfer_warehouse'] ?? 'Not Selected!') : ($shippingDetails['warehouse_transfer']) ?? 'Not Selected!'); ?></td>
                                        <td><?= goodsName(htmlspecialchars($goodsDetails['goods_id'])) . ' / ' . htmlspecialchars($goodsDetails['origin']); ?></td>
                                        <td><?= htmlspecialchars($goodsDetails['quantity_no']); ?> <sub><?= htmlspecialchars($goodsDetails['quantity_name']); ?></sub></td>
                                        <td><?= htmlspecialchars($goodsDetails['gross_weight']); ?></td>
                                        <td><?= htmlspecialchars($goodsDetails['net_weight']); ?></td>
                                    </tr>
                                <?php
                                    $i++;
                                    $total_qty_no += round($goodsDetails['quantity_no']);
                                    $total_gross_weight_kgs += round($goodsDetails['gross_weight']);
                                    $total_net_weight_kgs += round($goodsDetails['net_weight']);
                                }
                                ?>
                                <input type="hidden" id="total_qty_no" value="<?= $total_qty_no; ?>">
                                <input type="hidden" id="total_gross_weight_kgs" value="<?= $total_gross_weight_kgs; ?>">
                                <input type="hidden" id="total_net_weight_kgs" value="<?= $total_net_weight_kgs; ?>">
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">No records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <!-- Pagination -->
            <div class="card-footer d-flex justify-content-between align-items-center">

                <span class="text-muted small">
                    Showing <?php echo min($offset + 1, $total_rows); ?> to <?php echo min($offset + $rows_per_page, $total_rows); ?> of <?php echo $total_rows; ?> entries
                </span>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>

<script>
    $("#show_total_qty_no").text($("#total_qty_no").val());
    $("#show_total_gross_weight_kgs").text($("#total_gross_weight_kgs").val());
    $("#show_total_net_weight_kgs").text($("#total_net_weight_kgs").val());

    function viewPurchase(uniqueCode) {
        if (uniqueCode) {
            $.ajax({
                url: 'ajax/editGoodsTransfer.php',
                type: 'post',
                data: {
                    unique_code: uniqueCode,
                    page: "<?= $pageURL; ?>"
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<?php if (isset($_GET['unique_code']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $unique_code = mysqli_real_escape_string($connect, $_GET['unique_code']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase('$unique_code'); });</script>";
}
if (isset($_POST['reSubmit'])) {
    $unique_code = $_POST['unique_code'];
    $SaleEntry = decode_unique_code($unique_code, 'Ttype') === 's';
    $data_for = $_POST['data_for'];
    $tdata = json_decode($_POST['tdata'], true);
    $ldata = json_decode($_POST['ldata'], true);
    $update = isset($_POST['updateTrue']);
    $recordEdited = $_POST['recordEdited'];
    unset($_POST['reSubmit'], $_POST['unique_code'], $_POST['data_for'], $_POST['tdata'], $_POST['ldata'], $_POST['updateTrue']);
    $newldata = [];
    $newtdata = [];

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'l_' . $recordEdited . '_') === 0) {
            $newldata[$key] = $value;
        } elseif (strpos($key, 't_') === 0) {
            $newKey = substr($key, 2);
            $newtdata[$newKey] = $value;
        }
    }

    if ($SaleEntry) {
        $warehouseEntryValue = $newldata['l_' . $recordEdited . '_warehouse_entry'];
        list($decodedUniqueCode, $decodedLoadingID, $decodedGoodsID, $decodedGoodsName, $decodedSize, $decodedBrand, $decodedOrigin, $decodedQuantityNo, $decodedQuantityName, $decodedGrossWeight, $decodedNetWeight) = explode('~', $warehouseEntryValue);
        $retrievedData = mysqli_fetch_assoc(mysqli_query($connect, "SELECT ldata FROM data_copies WHERE unique_code='$decodedUniqueCode'"));
        $retrievedLdata = json_decode($retrievedData['ldata'], true);
        $retrievedLdata['l_' . $decodedLoadingID . '_sold_to'] = $unique_code . '~' . $recordEdited . '~' . goodsName($ldata['l_' . $recordEdited . '_goods_id']);
        $NewwLdata = mysqli_real_escape_string($connect, json_encode($retrievedLdata));
        update('data_copies', ['ldata' => $NewwLdata], ['unique_code' => $decodedUniqueCode]);
    }
    // echo "THIS IS LDATA: ".json_encode($ldata)."<br><br><br>";
    // echo "THIS IS NEW L DATA: ".json_encode($newldata)."<br><br><br>";
    // echo "THIS IS DB DATA: ".mysqli_real_escape_string($connect, json_encode(array_merge($ldata, $newldata)))."<br><br><br>";

    $tdata = json_encode(array_merge($tdata, $newtdata));
    $ldata = json_encode(array_merge($ldata, $newldata));
    $tdata = mysqli_real_escape_string($connect, $tdata);
    $ldata = mysqli_real_escape_string($connect, $ldata);

    $done = $update
        ? update('data_copies', ['tdata' => $tdata, 'ldata' => $ldata], ['data_for' => $data_for, 'unique_code' => $unique_code])
        : insert('data_copies', ['data_for' => $data_for, 'unique_code' => $unique_code, 'tdata' => $tdata, 'ldata' => $ldata]);

    if ($done) {
        messageNew('success', $pageURL . '?view=1&unique_code=' . $unique_code, ($update ? 'Record Updated!' : 'Record Added!'));
    }
}


?>