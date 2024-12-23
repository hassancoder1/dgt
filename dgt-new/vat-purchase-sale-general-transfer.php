<?php
$page_title = 'P/S GENERAL TRANSFER';
include("header.php");
$pageURL = "vat-purchase-sale-general-transfer";
$_GET['CCWpage'] = 'all';
$CCWPageMapping = [
    'transit' => 'Transit',
    'freezone-import' => 'Free Zone Import',
    'local-import' => 'Local Import',
    'import-re-export' => 'Import Re-Export',
    'local-export' => 'Local Export',
    'local-market' => 'Local Market',
    'all' => 'All WareHouses'
];
$filters = [
    'size' => '',
    'brand' => '',
    'origin' => '',
    'goods_id' => '',
    'date_from' => '',
    'date_to' => '',
    'net_kgs' => '',
    'qty_no' => ''
];
$is_search = false;
$rows_per_page = 50;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;
global $connect;
$conditions = [];
if ($_GET) {
    $resetFilters = removeFilter($pageURL);
    foreach ($filters as $key => &$value) {
        if (!empty($_GET[$key])) {
            $value = mysqli_real_escape_string($connect, $_GET[$key]);
            switch ($key) {
                case 'size':
                case 'brand':
                case 'origin':
                case 'goods_id':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.$key') = '$value'";
                    break;
                case 'date_from':
                    $conditions[] = "created_at >= '$value'";
                    break;
                case 'date_to':
                    $conditions[] = "created_at <= '$value'";
                    break;
                case 'net_kgs':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.goods_json.$key') = '$value'";
                    break;
                case 'qty_no':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.goods_json.$key') = '$value'";
                    break;
            }
        }
    }
}
$where_clause = !empty($conditions) ? ' AND ' . implode(' AND ', $conditions) : '';
$sql = "SELECT * FROM data_copies WHERE unique_code LIKE '_l%'";
$count_sql = "SELECT COUNT(*) AS total FROM ({$sql}) AS subquery";
$total_rows_result = mysqli_query($connect, $count_sql);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];
$sql .= " ORDER BY created_at DESC LIMIT $rows_per_page OFFSET $offset";
$data = mysqli_query($connect, $sql);
$entries = [];
while ($one = mysqli_fetch_assoc($data)) {
    if ($one['id'] !== null) {
        $entries[] = $one;
    }
}
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
                    <h5 class="card-title fw-bold text-success">P/S GENERAL TRANSFER</h5>
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
                    <?php
                    $i = $offset + 1;

                    // Separate entries into categories
                    $redEntries = [];
                    $yellowEntries = [];
                    $darkEntries = [];

                    foreach ($entries as $entry) {
                        $ldata = json_decode($entry['ldata'], true);
                        $unique_code = $entry['unique_code'];
                        [$Ttype, $Tcat, $Troute, $TID, $LID] = decode_unique_code($unique_code, 'all');
                        $TotalQty = $ldata['good']['quantity_no'];
                        $RemQty = $ldata['good']['goods_json']['qty_no'];
                        $SoldQty = $TotalQty - $RemQty;

                        // Categorize entries
                        if ($SoldQty === 0) {
                            $redEntries[] = $entry;
                        } elseif ($RemQty > 0) {
                            $yellowEntries[] = $entry;
                        } else {
                            $darkEntries[] = $entry;
                        }
                    }

                    // Combine sorted entries
                    $sortedEntries = array_merge($redEntries, $yellowEntries, $darkEntries);
                    ?>

                    <table class="table table-bordered table-hover table-sm fix-head-table mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No.</th>
                                <th>P/S# (SR#)</th>
                                <?php if ($_GET['CCWpage'] === 'all') { ?>
                                    <th>Warehouse</th>
                                <?php } ?>
                                <th>BL / UID</th>
                                <th>Type</th>
                                <?php if ($_GET['CCWpage'] === 'all') { ?>
                                    <th>Transferred To P/S#</th>
                                <?php } ?>
                                <th>Allot</th>
                                <th>Goods Name / ORIGIN</th>
                                <th>Total Qty</th>
                                <th>Sold Qty</th>
                                <th>Rem Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($sortedEntries as $entry) {
                                $ldata = json_decode($entry['ldata'], true);
                                $unique_code = $entry['unique_code'];
                                [$Ttype, $Tcat, $Troute, $TID, $LID] = decode_unique_code($unique_code, 'all');
                                $TotalQty = $ldata['good']['quantity_no'];
                                $RemQty = $ldata['good']['goods_json']['qty_no'];
                                $SoldQty = $TotalQty - $RemQty;

                                // Determine row color
                                if ($SoldQty === 0) {
                                    $rowColor = 'fw-bold text-danger';
                                } elseif ($RemQty > 0) {
                                    $rowColor = 'fw-bold text-warning';
                                } else {
                                    $rowColor = 'fw-bold text-dark';
                                }
                                $trans = $ldata['transfer']['sold_to'] ?? $ldata['transfer']['sold_from'] ?? [];
                                $TIDS = [];
                                foreach ($trans as $one) {
                                    $TIDS[] = '#' . decode_unique_code(explode('~', $one)[0], 'TID');
                                }
                                $TIDS = array_unique($TIDS);
                                $trans = implode(', ', $TIDS);
                            ?>
                                <tr class="text-nowrap">
                                    <td class="<?= $rowColor; ?>"><?= htmlspecialchars($i); ?></td>
                                    <td class="<?= $rowColor; ?> pointer"
                                        onclick="window.location.href = '?view=1&unique_code=<?= $unique_code; ?>&print_type=contract&CCWpage=<?= $_GET['CCWpage']; ?>';">
                                        <b><?= ucfirst($ldata['type']); ?>#</b> <?= htmlspecialchars($ldata['p_id']); ?> (<?= htmlspecialchars($ldata['sr_no']); ?>)
                                    </td>
                                    <?php if ($_GET['CCWpage'] === 'all') { ?>
                                        <td class="<?= $rowColor; ?>"><?= htmlspecialchars($ldata['transfer']['warehouse_transfer']); ?></td>
                                    <?php } ?>
                                    <td class="<?= $rowColor; ?>"><?= htmlspecialchars($Tcat !== 'l' ? 'B/L: ' . $ldata['bl_no'] : 'UID: ' . $ldata['uid']); ?></td>
                                    <td class="<?= $rowColor; ?>"><?= ucfirst(htmlspecialchars($ldata['p_type'] ?? 'local')); ?></td>
                                    <?php if ($_GET['CCWpage'] === 'all') { ?>
                                        <td class="<?= $rowColor; ?>"><?= !empty($trans) ? $trans : 'Not Transferred!'; ?></td>
                                    <?php } ?>
                                    <td class="<?= $rowColor; ?>"><?= $ldata['good']['goods_json']['allotment_name']; ?></td>
                                    <td class="<?= $rowColor; ?>"><?= goodsName(htmlspecialchars($ldata['good']['goods_id'])) . ' / ' . htmlspecialchars($ldata['good']['origin']); ?></td>
                                    <td class="fw-bold text-success"><?= htmlspecialchars($TotalQty); ?> <sub><?= htmlspecialchars($ldata['good']['goods_json']['qty_name']); ?></sub></td>
                                    <td class="fw-bold text-danger"><?= htmlspecialchars($SoldQty); ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($RemQty); ?></td>
                                </tr>
                            <?php
                                $i++;
                            }
                            ?>
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
                url: 'ajax/editCustomClearing.php',
                type: 'post',
                data: {
                    unique_code: uniqueCode,
                    page: "<?= $pageURL; ?>",
                    print_type: printType,
                    CCWpage: "<?= $_GET['CCWpage']; ?>",
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
    $return = explode('~', $_POST['return']);
    unset($_POST['reSubmit'], $_POST['return']);
    // Decode tdata and ldata JSON strings into PHP arrays
    $tdata = json_decode($_POST['tdata'], true);
    $ldata = json_decode($_POST['ldata'], true);

    // Recursive function to update JSON structure
    function updateNestedJson(&$data, $key, $value)
    {
        foreach ($data as $k => &$v) {
            // If key matches, update the value
            if ($k === $key) {
                $v = $value;
            }
            // If value is an array, recurse into it
            elseif (is_array($v)) {
                updateNestedJson($v, $key, $value);
            }
        }
    }

    // Iterate through POST keys to update tdata and ldata
    foreach ($_POST as $key => $value) {
        if ($key !== 'tdata' && $key !== 'ldata') { // Exclude JSON strings
            // Update tdata
            if (array_key_exists($key, $tdata)) {
                $tdata[$key] = $value;
            } else {
                updateNestedJson($tdata, $key, $value);
            }

            // Update ldata
            if (array_key_exists($key, $ldata)) {
                $ldata[$key] = $value;
            } else {
                updateNestedJson($ldata, $key, $value);
            }
        }
    }
    $data = [
        'data_for' => mysqli_real_escape_string($connect, $_POST['warehouse_transfer']),
        'tdata' => mysqli_real_escape_string($connect, json_encode($tdata)),
        'ldata' => mysqli_real_escape_string($connect, json_encode($ldata))
    ];

    $done = update('data_copies', $data, ['unique_code' => $_POST['unique_code']]);
    if ($done) {
        $message = 'Record Updated!';
        $keyWarehouse = $return[0] === $_POST['unique_code'] ? $_POST['warehouse_transfer'] : $return[1];
        $keyWarehouse = array_search($keyWarehouse, $CCWPageMapping);
        messageNew('success', 'custom-clearing-warehouse?view=1&unique_code=' . $return[0] . '&print_type=contract&CCWpage=' . $keyWarehouse, $message);
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