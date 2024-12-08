<?php
$page_title = 'Local P/S WareHouse Transfer';
$pageURL = 'local-wareHouse-transfer-goods';
include("header.php");

$resetFilters = $size = $brand = $origin = $goods_name = $date_from = $date_to = $net_kgs = $qty_no = '';
$is_search = false;
global $connect;
$rows_per_page = 50;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;
$sql = "SELECT * FROM local_loading WHERE JSON_EXTRACT(transfer_details, '$.warehouse_transfer') IS NOT NULL";
$conditions = [];

// Handle filters
if ($_GET) {
    $resetFilters = removeFilter($pageURL);
    if (!empty($_GET['size'])) {
        $size = mysqli_real_escape_string($connect, $_GET['size']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.size')='$size'";
    }
    if (!empty($_GET['brand'])) {
        $brand = mysqli_real_escape_string($connect, $_GET['brand']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.brand')='$brand'";
    }
    if (!empty($_GET['origin'])) {
        $origin = mysqli_real_escape_string($connect, $_GET['origin']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.origin')='$origin'";
    }
    if (!empty($_GET['goods_id'])) {
        $goods_id = mysqli_real_escape_string($connect, $_GET['goods_id']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.goods_id')='$goods_id'";
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
        $conditions[] = "JSON_EXTRACT(goods_details, '$.net_weight')='$net_kgs'";
    }
    if (!empty($_GET['qty_no'])) {
        $qty_no = mysqli_real_escape_string($connect, $_GET['qty_no']);
        $conditions[] = "JSON_EXTRACT(goods_details, '$.quantity_no')='$qty_no'";
    }
}

if (!empty($conditions)) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
    $is_search = true;
}

$total_rows_result = mysqli_query($connect, $sql);
$total_rows = mysqli_num_rows($total_rows_result);

$sql .= " ORDER BY p_id, created_at, sr_no LIMIT $rows_per_page OFFSET $offset";

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
                                <th>UID</th>
                                <th>Goods Name / SIZE / BRAND</th>
                                <th>ORIGIN</th>
                                <th>Qty.Name</th>
                                <th>Qty No</th>
                                <th>WareHouse</th>
                                <th>G.W.KGS</th>
                                <th>N.W.KGS</th>
                                <th>L Comp Name</th>
                                <th>R Comp Name</th>
                                <th>L Date</th>
                                <th>R Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $offset + 1;
                            if (mysqli_num_rows($entries) > 0):
                                $total_qty_no = 0;
                                $total_gross_weight_kgs = 0;
                                $total_net_weight_kgs = 0;
                                while ($entry = mysqli_fetch_assoc($entries)) {
                            ?>
                                    <tr class="text-nowrap">
                                        <td><?= htmlspecialchars($i); ?></td>
                                        <td class="pointer" onclick="window.location.href = '?view=1&id=<?= $entry['p_id']; ?>';">
                                            <b><?= ucfirst($entry['type']); ?>#</b> <?= htmlspecialchars($entry['p_id']); ?> (<?= $entry['sr_no']; ?>)
                                        </td>
                                        <td><?= htmlspecialchars($entry['uid']); ?></td>
                                        <td><?= goodsName(htmlspecialchars(json_decode($entry['goods_details'], true)['goods_id'])) . ' / ' . htmlspecialchars(json_decode($entry['goods_details'], true)['size']) . ' / ' . htmlspecialchars(json_decode($entry['goods_details'], true)['brand']); ?> </td>
                                        <td><?= htmlspecialchars(json_decode($entry['goods_details'], true)['origin']); ?></td>
                                        <td><?= htmlspecialchars(json_decode($entry['goods_details'], true)['quantity_name']); ?></td>
                                        <td><?= htmlspecialchars(json_decode($entry['goods_details'], true)['quantity_no']); ?></td>
                                        <td><?= htmlspecialchars(json_decode($entry['transfer_details'], true)['warehouse_transfer']); ?></td>
                                        <td><?= htmlspecialchars(json_decode($entry['goods_details'], true)['gross_weight']); ?></td>
                                        <td><?= htmlspecialchars(json_decode($entry['goods_details'], true)['net_weight']); ?></td>
                                        <td><?= htmlspecialchars(json_decode($entry['transfer_details'], true)['loading_company_name']); ?></td>
                                        <td><?= htmlspecialchars(json_decode($entry['transfer_details'], true)['receiving_company_name']); ?></td>
                                        <td><?= my_date(htmlspecialchars(json_decode($entry['transfer_details'], true)['loading_date'])); ?></td>
                                        <td><?= my_date(htmlspecialchars(json_decode($entry['transfer_details'], true)['receiving_date'])); ?></td>
                                    </tr>
                                <?php $i++;
                                    $total_qty_no += round(htmlspecialchars(json_decode($entry['goods_details'], true)['quantity_no']));
                                    $total_gross_weight_kgs += round(htmlspecialchars(json_decode($entry['goods_details'], true)['gross_weight']));
                                    $total_net_weight_kgs += round(htmlspecialchars(json_decode($entry['goods_details'], true)['net_weight']));
                                }; ?>
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

    function viewPurchase(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewStockTransferGoods.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "stock-transfer-goods"
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
<?php if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($id); });</script>";
}