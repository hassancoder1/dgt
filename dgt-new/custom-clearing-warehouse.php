<?php
$page_title = 'CUSTOM CLEARING => WAREHOUSE';
$CCWPage = '';
switch ($_GET['CCWpage']) {
    case 'transit':
        $CCWPage = 'Transit';
        break;
    case 'freezone-import':
        $CCWPage = 'Free Zone Import';
        break;
    case 'local-import':
        $CCWPage = 'Local Import';
        break;
    case 'import-re-export':
        $CCWPage = 'Import Re-Export';
        break;
    case 'local-export':
        $CCWPage = 'Local Export';
        break;
    case 'local-market':
        $CCWPage = 'Local Market';
        break;
    default:
        $CCWPage = '';
}
$pageURL = 'custom-clearing-warehouse?CCWpage=' . $_GET['CCWpage'];
include("header.php");
$resetFilters = $size = $brand = $origin = $goods_id = $date_from = $date_to = $net_kgs = $qty_no = '';
$is_search = false;
global $connect;
$rows_per_page = 50;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;
$conditions = [];
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
$sql = "SELECT * FROM data_copies LIMIT $rows_per_page OFFSET $offset";
$result = mysqli_query($connect, $sql);
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $ldata = json_decode($row['ldata'], true);
    $normalized_data = [];
    foreach ($ldata as $key => $value) {
        $normalized_key = preg_replace('/^l_\w+?_/', '', $key);
        $normalized_data[$normalized_key] = $value;
    }
    if (isset($normalized_data['warehouse_transfer']) && $normalized_data['warehouse_transfer'] === $CCWPage) {
        $data[] = $row;
    }
}
$total_rows = count($data);
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
                            if (count($data) > 0):
                                $total_qty_no = $total_gross_weight_kgs = $total_net_weight_kgs = 0;
                                foreach ($data as $entry) {
                                    $Tdata = json_decode($entry['tdata'], true);
                                    $Ldata = json_decode($entry['ldata'], true);
                                    reset($Ldata);  // Move the internal pointer to the first element of the array
                                    $firstKey = key($Ldata);
                                    preg_match('/^l_(\d+)_/', $firstKey, $matches);
                                    if (!empty($matches[1])) {
                                        $firstID = $matches[1];
                                    }
                                    if ($currentPID !== $Ldata['l_' . $firstID . '_p_id']) {
                                        $currentPID = $Ldata['l_' . $firstID . '_p_id'];
                                        $entryCount = 1;
                                    } else {
                                        $entryCount++;
                                    }
                            ?>
                                    <tr class="text-nowrap">
                                        <td><?= htmlspecialchars($i); ?></td>
                                        <td class="pointer" onclick="window.location.href = '?CCWpage=<?= $_GET['CCWpage']; ?>&view=1&unique_code=<?= $entry['unique_code']; ?>';">
                                            <b><?= ucfirst($Ldata['l_' . $firstID . '_type']); ?>#</b> <?= htmlspecialchars($Ldata['l_' . $firstID . '_p_id']); ?> (<?= $entryCount; ?>)
                                        </td>
                                        <td><?= htmlspecialchars(isset($Ldata['l_' . $firstID . '_bl_no']) ? 'B/L: ' . $Ldata['l_' . $firstID . '_bl_no'] : 'UID: ' . $Ldata['l_' . $firstID . '_uid']); ?> (<?= $entryCountPerBL[$Ldata['l_' . $firstID . '_bl_no'] ?? $Ldata['l_' . $firstID . '_uid']]; ?>)</td>
                                        <td><?= ucfirst(htmlspecialchars($Tdata['type'])); ?></td>
                                        <td><?= ucfirst(htmlspecialchars($Ldata['l_' . $firstID . '_warehouse_transfer'])); ?></td>
                                        <td><?= goodsName(htmlspecialchars($Ldata['l_' . $firstID . '_goods_id'])) . ' / ' . htmlspecialchars($Ldata['l_' . $firstID . '_origin']); ?></td>
                                        <td><?= htmlspecialchars($Ldata['l_' . $firstID . '_quantity_no']); ?> <sub><?= htmlspecialchars($Ldata['l_' . $firstID . '_quantity_name']); ?></sub></td>
                                        <td><?= htmlspecialchars($Ldata['l_' . $firstID . '_gross_weight']); ?></td>
                                        <td><?= htmlspecialchars($Ldata['l_' . $firstID . '_net_weight']); ?></td>
                                    </tr>
                                <?php
                                    $i++;
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
                url: 'ajax/editCustomClearing.php',
                type: 'post',
                data: {
                    unique_code: uniqueCode,
                    page: "<?= $pageURL; ?>",
                    CCWpage: true
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