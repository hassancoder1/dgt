<?php
$page_title = 'Confirm Stock';
$pageURL = 'confirm-stock';
include("header.php");

$resetFilters = $size = $brand = $origin = $goods_name = $date_from = $date_to = $net_kgs = $qty_no = '';
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
    SELECT id, sr_no, p_id, goods_details, shipping_details, created_at, bl_no, loading_details, receiving_details, 'general' AS source 
    FROM general_loading 
    WHERE agent_details IS NOT NULL {$where_clause}
    UNION
    SELECT id, sr_no, p_id, goods_details, transfer_details AS shipping_details, created_at, uid AS bl_no, p_date AS dummy1, p_branch AS dummy2, 'local' AS source 
    FROM local_loading 
    WHERE goods_details IS NOT NULL {$where_clause}
";


$is_search = !empty($conditions);

$total_rows_result = mysqli_query($connect, "SELECT COUNT(*) AS total FROM ({$sql}) AS subquery");
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];

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
                                <th>P# (SR#)</th>
                                <th>BL / UID</th>
                                <th>Goods Name / SIZE / BRAND</th>
                                <th>ORIGIN</th>
                                <th>Qty.Name</th>
                                <th>Qty No</th>
                                <th>G.W.KGS</th>
                                <th>N.W.KGS</th>
                                <th>Loading</th>
                                <th>Receiving</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $offset + 1;
                            if (mysqli_num_rows($entries) > 0):
                                $total_qty_no = $total_gross_weight_kgs = $total_net_weight_kgs = 0;
                                while ($entry = mysqli_fetch_assoc($entries)) {
                                    $goodsDetails = json_decode($entry['goods_details'], true);
                                    $shippingDetails = json_decode($entry['shipping_details'], true);
                            ?>
                                    <tr class="text-nowrap">
                                        <td><?= htmlspecialchars($i); ?></td>
                                        <td class="pointer" onclick="viewPurchase(<?php echo $entry['p_id']; ?>)" data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                            <b>P#</b> <?= htmlspecialchars($entry['p_id']); ?> (<?= $entry['sr_no']; ?>)
                                        </td>
                                        <td><?= htmlspecialchars($entry['source'] === 'general' ? 'B/L: ' . $entry['bl_no'] : 'UID: ' . $entry['bl_no']); ?></td>
                                        <td><?= goodsName(htmlspecialchars($goodsDetails['goods_id'])) . ' / ' . htmlspecialchars($goodsDetails['size']) . ' / ' . htmlspecialchars($goodsDetails['brand']); ?></td>
                                        <td><?= htmlspecialchars($goodsDetails['origin']); ?></td>
                                        <td><?= htmlspecialchars($goodsDetails['quantity_name']); ?></td>
                                        <td><?= htmlspecialchars($goodsDetails['quantity_no']); ?></td>
                                        <td><?= htmlspecialchars($goodsDetails['gross_weight']); ?></td>
                                        <td><?= htmlspecialchars($goodsDetails['net_weight']); ?></td>
                                        <td>
                                            <?php if ($entry['source'] === 'general'): ?>
                                                <b><?= htmlspecialchars($shippingDetails['transfer_by']) === 'sea' ? 'Port' : 'Border'; ?>: </b> <?= json_decode($entry['loading_details'], true)['loading_port_name']; ?>
                                            <?php else: ?>
                                                <b>Date:</b> <?= htmlspecialchars($shippingDetails['loading_date']); ?> |
                                                <b>Comp Nme:</b> <?= htmlspecialchars($shippingDetails['loading_company_name']); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($entry['source'] === 'general'): ?>
                                                <b><?= htmlspecialchars($shippingDetails['transfer_by']) === 'sea' ? 'Port' : 'Border'; ?>: </b> <?= json_decode($entry['receiving_details'], true)['receiving_port_name']; ?>
                                            <?php else: ?>
                                                <b>Date:</b> <?= htmlspecialchars($shippingDetails['receiving_date']); ?> |
                                                <b>Comp Nme:</b> <?= htmlspecialchars($shippingDetails['receiving_company_name']); ?>
                                            <?php endif; ?>
                                        </td>
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
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">PURCHASE DETAILS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
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
                url: 'ajax/viewSingleTransaction.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "confirm-stock"
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