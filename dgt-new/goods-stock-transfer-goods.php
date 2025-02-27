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

// Main query with the UNION to combine the two sources
$sql = "SELECT id, MAX(sr_no) AS sr_no, p_id, type, p_type, goods_details, shipping_details AS transfer_details, agent_details, created_at, bl_no, loading_details, receiving_details, gloading_info as info, 'general' AS source 
        FROM general_loading 
        WHERE agent_details IS NOT NULL AND JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL {$where_clause}
        GROUP BY id, p_id, type, p_type, goods_details, shipping_details, agent_details, created_at, bl_no, loading_details, receiving_details 

        UNION 

        SELECT id, MAX(sr_no) AS sr_no, p_id, type, 'local' AS p_type, goods_details, transfer_details, 'dummy_agent' AS agent_details, created_at, uid AS bl_no, 'dummy1' AS dummy1, 'dummy2' AS dummy2, lloading_info as info, 'local' AS source 
        FROM local_loading 
        WHERE transfer_details IS NOT NULL AND JSON_EXTRACT(lloading_info, '$.parent_id') IS NULL {$where_clause} 
        GROUP BY id, p_id, type, goods_details, transfer_details, created_at, uid";

// Count the total number of rows matching the criteria
$is_search = !empty($conditions);
$total_rows_result = mysqli_query($connect, "SELECT COUNT(*) AS total FROM ({$sql}) AS subquery");
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

// Add pagination to the query
$sql .= " ORDER BY p_id, created_at LIMIT $rows_per_page OFFSET $offset";

// Execute the query
$entries = mysqli_query($connect, $sql);

// Calculate total pages for pagination
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
                                <th>Allot</th>
                                <th>Goods Name / ORIGIN</th>
                                <th>Quantity</th>
                                <th>Gross.KGS</th>
                                <th>Net.KGS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $offset + 1;
                            $entriesPerBlUID = 0;
                            $BLUIDsPerTID = [];
                            if (mysqli_num_rows($entries) > 0):
                                while ($entry = mysqli_fetch_assoc($entries)) {
                                    $Transfer = json_decode($entry['transfer_details'], true);
                                    $Agent = json_decode($entry['agent_details'], true);
                                    $Good = json_decode($entry['goods_details'], true);
                                    $Info = json_decode($entry['info'], true);
                                    $entriesPerBlUID = count(explode(', ', $Info['child_ids']));
                                    $trackerKey = $entry['p_id'];
                                    $BLUIDsPerTID[$trackerKey] = isset($BLUIDsPerTID[$trackerKey]) ? $BLUIDsPerTID[$trackerKey] + 1 : 1;
                                    $route = $Transfer['transfer_by'] ?? $Transfer['route'];
                                    $unique_code = $entry['type'] . $entry['p_type'][0] . (isset($Transfer['route'])
                                        ? ($Transfer['route'] === 'local' ? 'ld' : 'wr')
                                        : ($Transfer['transfer_by'] === 'sea' ? 'se' : 'rd'))
                                        . '_' . $entry['p_id'] . '_' . $entry['bl_no'];
                            ?>
                                    <tr class="text-nowrap">
                                        <td><?= htmlspecialchars($i); ?></td>
                                        <td class="pointer"
                                            onclick="window.location.href = '?view=1&unique_code=<?= $unique_code; ?>&print_type=contract';">
                                            <b><?= ucfirst($entry['type']); ?>#</b> <?= htmlspecialchars($entry['p_id']); ?> (<?= $entriesPerBlUID; ?>)
                                        </td>
                                        <td><?= htmlspecialchars($entry['source'] === 'general' ? 'B/L: ' . $entry['bl_no'] : 'UID: ' . $entry['bl_no']); ?> (<?= $BLUIDsPerTID[$entry['p_id']]; ?>)</td>
                                        <td><?= ucfirst(htmlspecialchars($entry['p_type'])); ?></td>
                                        <td><?= htmlspecialchars($entry['source'] === 'general' ? ($Agent['cargo_transfer_warehouse'] ?? 'Not Selected!') : ($Transfer['warehouse_transfer']) ?? 'Not Selected!'); ?></td>
                                        <td><?= json_decode($Good['goods_json'] ?? '[]', true)['allotment_name'] ?? ''; ?></td>
                                        <td><?= goodsName(htmlspecialchars($Good['goods_id'])) . ' / ' . htmlspecialchars($Good['origin']); ?></td>
                                        <td><?= htmlspecialchars($Info['total_quanity_no']); ?> <sub><?= htmlspecialchars($Good['quantity_name']); ?></sub></td>
                                        <td><?= htmlspecialchars($Info['total_gross_weight']); ?></td>
                                        <td><?= htmlspecialchars($Info['total_net_weight']); ?></td>
                                    </tr>
                                <?php
                                    $i++;
                                }
                                ?>
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
        let printType = '<?= isset($_GET['print_type']) ? $_GET['print_type'] : 'contract'; ?>';
        if (uniqueCode) {
            $.ajax({
                url: 'ajax/editGoodsTransfer.php',
                type: 'post',
                data: {
                    unique_code: uniqueCode,
                    page: "<?= $pageURL; ?>",
                    print_type: printType,
                    timestamp: currentFormattedDateTime()
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
    // Extract and process form data
    $unique_code = $_POST['unique_code'];
    $data_for = $_POST['data_for'];
    $recordEdited = $_POST['recordEdited'];
    $tdata = json_decode($_POST['tdata'], true);
    $ldata = json_decode($_POST['ldata'], true);
    $update = isset($_POST['updateTrue']);
    $SaleEntry = decode_unique_code($unique_code, 'Ttype') === 's';
    $isLocal = decode_unique_code($unique_code, 'Tcat') === 'l';

    // Remove unnecessary keys from $_POST
    unset(
        $_POST['reSubmit'],
        $_POST['unique_code'],
        $_POST['data_for'],
        $_POST['tdata'],
        $_POST['ldata'],
        $_POST['updateTrue'],
        $_POST['recordEdited']
    );

    // Process Local or Importer/Exporter/Notify data
    if ($isLocal) {
        foreach ($_POST as $key => $value) {
            if (isset($tdata[$key])) {
                $tdata[$key] = $value;
            }
        }
    } else {
        foreach ($_POST as $key => $value) {
            $prefixMap = [
                'im_' => 'importer_details',
                'xp_' => 'exporter_details',
                'np_' => 'notify_party_details',
            ];
            foreach ($prefixMap as $prefix => $detailKey) {
                if (strpos($key, $prefix) === 0 && isset($ldata[$detailKey][$key])) {
                    $ldata[$detailKey][$key] = $value;
                }
            }
        }
    }

    // Update Transfer Details
    foreach ($_POST as $key => $value) {
        if (isset($ldata['transfer_details'][$key])) {
            $ldata['transfer_details'][$key] = $value;
        }
    }

    // Update Goods Details
    if (!empty($recordEdited) && isset($ldata['goods'][$recordEdited])) {
        foreach ($_POST as $key => $value) {
            if (preg_match('/^' . preg_quote($recordEdited, '/') . '_/', $key)) {
                $cleanKey = preg_replace('/^\d+_/', '', $key);

                if (isset($ldata['goods'][$recordEdited]['goods_json'][$cleanKey])) {
                    $ldata['goods'][$recordEdited]['goods_json'][$cleanKey] = $value;
                    $ldata['goods'][$recordEdited]['edited'] = true;
                }

                if (isset($ldata['goods'][$recordEdited]['agent'][$cleanKey])) {
                    $ldata['goods'][$recordEdited]['agent'][$cleanKey] = $value;
                }
            }
        }
    }
    if (!empty($_POST['transfer_to_warehouse_ids'])) {
        $selected_ids = explode(',', $_POST['transfer_to_warehouse_ids']);

        foreach ($selected_ids as $id) {
            $ldata['goods'][$id]['agent']['cargo_transfer_warehouse'] = $_POST['warehouse_transfer'];
            if ($SaleEntry && isset($_POST['warehouse_entry']) && !empty($_POST['warehouse_entry'])) {
                if (isset($ldata['goods'][$id]['edited'])) {
                    $currentQty = $ldata['goods'][$id]['goods_json']['qty_no'] ?? 0;
                    $currentTotalKgs = $ldata['goods'][$id]['goods_json']['total_kgs'] ?? 0;
                    $currentNetKgs = $ldata['goods'][$id]['goods_json']['net_kgs'] ?? 0;
                } else {
                    $currentQty = $ldata['goods'][$id]['quantity_no'] ?? 0;
                    $currentTotalKgs = $ldata['goods'][$id]['gross_weight'] ?? 0;
                    $currentNetKgs = $ldata['goods'][$id]['net_weight'] ?? 0;
                }
                // Fetch warehouse entry data
                $warehouseEntry = explode('~', $_POST['warehouse_entry']);
                $retrievedData = mysqli_fetch_assoc(mysqli_query(
                    $connect,
                    "SELECT ldata FROM data_copies WHERE unique_code='$warehouseEntry[0]'"
                ));

                $retrievedLdata = json_decode($retrievedData['ldata'], true);
                $retrievedGoods = &$retrievedLdata['goods'][$warehouseEntry[1]];

                // Ensure default keys
                $retrievedGoods['goods_json']['qty_no'] = $retrievedGoods['goods_json']['qty_no'] ?? 0;
                $retrievedGoods['goods_json']['total_kgs'] = $retrievedGoods['goods_json']['total_kgs'] ?? 0;
                $retrievedGoods['goods_json']['net_kgs'] = $retrievedGoods['goods_json']['net_kgs'] ?? 0;

                // Calculate updated totals
                $totalsData = [
                    'quantity_no' => max($retrievedGoods['goods_json']['qty_no'] - $currentQty, 0),
                    'gross_weight' => max($retrievedGoods['goods_json']['total_kgs'] - $currentTotalKgs, 0),
                    'net_weight' => max($retrievedGoods['goods_json']['net_kgs'] - $currentNetKgs, 0),
                ];

                // Create 'sold_to' entry for Purchase Entry
                $soldEntry = implode('~', [
                    $unique_code,
                    $id,
                    $ldata['goods'][$id]['goods_id'],
                    goodsName($ldata['goods'][$id]['goods_id']),
                    $currentQty,
                    $ldata['goods'][$id]['quantity_name'],
                    $currentTotalKgs,
                    $currentNetKgs
                ]);

                // Update 'sold_to' array in Purchase Entry
                $retrievedGoods['agent']['sold_to'] = isset($retrievedGoods['agent']['sold_to']) && is_array($retrievedGoods['agent']['sold_to'])
                    ? array_merge($retrievedGoods['agent']['sold_to'], [$soldEntry])
                    : [$soldEntry];

                // Create 'purchased_in' entry for Sale Entry
                $purchasedEntry = implode('~', [
                    $warehouseEntry[0], // This includes unique_code and entry ID
                    $warehouseEntry[1],
                    $retrievedGoods['goods_id'],
                    goodsName($retrievedGoods['goods_id']),
                    $currentQty,
                    $ldata['goods'][$id]['quantity_name'],
                    $currentTotalKgs,
                    $currentNetKgs
                ]);

                // Update 'purchased_in' array in Sale Entry
                $ldata['goods'][$id]['agent']['purchased_in'] = isset($ldata['goods'][$id]['agent']['purchased_in']) && is_array($ldata['goods'][$id]['agent']['purchased_in'])
                    ? array_merge($ldata['goods'][$id]['agent']['purchased_in'], [$purchasedEntry])
                    : [$purchasedEntry];

                $ldata['goods'][$id]['goods_json']['qty_no'] = 0;
                $ldata['goods'][$id]['goods_json']['total_kgs'] = 0;
                $ldata['goods'][$id]['goods_json']['net_kgs'] = 0;

                // Update remaining totals in Purchase goods_json
                $retrievedGoods['goods_json']['qty_no'] = $totalsData['quantity_no'];
                $retrievedGoods['goods_json']['total_kgs'] = $totalsData['gross_weight'];
                $retrievedGoods['goods_json']['net_kgs'] = $totalsData['net_weight'];

                // Save updated warehouse data back to database
                $NewwLdata = mysqli_real_escape_string($connect, json_encode($retrievedLdata));
                update('data_copies', ['ldata' => $NewwLdata], ['unique_code' => $warehouseEntry[0]]);
            }
        }
    }





    // Encode data for database storage
    $tdata = mysqli_real_escape_string($connect, json_encode($tdata));
    $ldata = mysqli_real_escape_string($connect, json_encode($ldata));

    // Perform database operation (insert or update)
    $operationData = [
        'data_for' => $data_for,
        'unique_code' => $unique_code,
        'tdata' => $tdata,
        'ldata' => $ldata,
    ];
    $done = $update
        ? update('data_copies', $operationData, ['data_for' => $data_for, 'unique_code' => $unique_code])
        : insert('data_copies', $operationData);

    // Provide feedback to the user
    if ($done) {
        $message = $update ? 'Record Updated!' : 'Record Added!';
        messageNew('success', $pageURL . '?view=1&unique_code=' . $unique_code, $message);
    }
}


if (isset($_GET['delete']) && $_GET['delete'] === base64_encode('DELETE ME!')) {
    $unique_code = $_GET['unique_code'];
    $done = mysqli_query($connect, "DELETE FROM data_copies WHERE unique_code='$unique_code'");
    if ($done) {
        messageNew('success', $pageURL, 'Record Deleted!');
    }
}
?>