<?php
$page_title = 'Purchase Sale Container Stock';
$pageURL = 'purchase-sale-containers';
include("header.php");

// Retrieve filter values from GET parameters
$allot = isset($_GET['allot']) ? trim($_GET['allot']) : '';
$date_from = isset($_GET['date_from']) ? trim($_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? trim($_GET['date_to']) : '';
$resetFilters = '';
if ($_GET) {
    $resetFilters = removeFilter($pageURL);
}
$is_search = false;

// Pagination variables
$rows_per_page = 20;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Where clause for filters
$where_clause = '';
if ($allot) {
    $where_clause .= " AND JSON_EXTRACT(goods_details, '$.container_no') LIKE '%" . mysqli_real_escape_string($connect, $allot) . "%'";
    $is_search = true;
}
if ($date_from && $date_to) {
    $where_clause .= " AND DATE(created_at) BETWEEN '" . mysqli_real_escape_string($connect, $date_from) . "' AND '" . mysqli_real_escape_string($connect, $date_to) . "'";
    $is_search = true;
}

// Fetch data from both tables d means dummy just to fill the columns which are less in local loading
$sql = "SELECT * FROM data_copies WHERE unique_code LIKE 'p%' AND unique_code NOT LIKE 'pl%'";
$entries = mysqli_query($connect, $sql);
$processed_entries = [];
while ($entry = mysqli_fetch_assoc($entries)) {
    // Decode the goods_details JSON
    $ldata = json_decode($entry['ldata'], true);
    $good = $ldata['good'];
    $container_no = $ldata['good']['container_no'];
    $goods_json = $ldata['good']['goods_json'];
    if (!isset($processed_entries[$container_no])) {
        $processed_entries[$container_no] = array_merge([
            'total_purchased_qty' => 0,
            'p_id' => decode_unique_code($entry['unique_code'], 'TID'),
            'total_purchased_kgs' => 0,
            'total_purchased_net_kgs' => 0,
            'total_sold_qty' => 0,
            'total_sold_kgs' => 0,
            'total_sold_net_kgs' => 0,
        ], $good);
    }

    $processed_entries[$container_no]['total_purchased_qty'] += $good['quantity_no'] ?? 0;
    $processed_entries[$container_no]['total_purchased_kgs'] += $good['gross_weight'] ?? 0;
    $processed_entries[$container_no]['total_purchased_net_kgs'] += $good['net_weight'] ?? 0;
    if (isset($ldata['transfer']['sold_to'])) {
        foreach ($ldata['transfer']['sold_to'] as $sold) {
            $soldData = explode('~', $sold);
            $processed_entries[$container_no]['total_sold_qty'] += $soldData[3] ?? 0;
            $processed_entries[$container_no]['total_sold_kgs'] += $soldData[5] ?? 0;
            $processed_entries[$container_no]['total_sold_net_kgs'] += $soldData[6] ?? 0;
        }
    }
}


// Pagination calculation
// $result = mysqli_query($connect, "SELECT COUNT(*) AS total_rows FROM data_copies WHERE {$where_clause}");
// $row = mysqli_fetch_assoc($result);
// $total_rows = $row['total_rows']; // Extract the actual count
// $total_pages = ceil($total_rows / $rows_per_page); // Calculate total pages

?>



<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>

<div class="mx-2" style="margin-top:-40px;">
    <form method="GET" class="row">
        <div class="col-md-12">
            <!-- 60% Section -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title"><?php echo $page_title; ?></h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="allot" class="form-label">Allot Name</label>
                            <input type="text" name="allot" value="<?= $allot; ?>" id="allot" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label for="goods_name" class="form-label">Goods</label>
                            <select id="goods_name" name="goods_name" class="form-select form-select-sm">
                                <option value="">ALL GOODS</option>
                                <?php $goods = fetch('goods');
                                while ($good = mysqli_fetch_assoc($goods)) {
                                    $g_selected = $good['name'] == $goods_name ? 'selected' : '';
                                    echo '<option ' . $g_selected . ' value="' . $good['name'] . '">' . $good['name'] . '</option>';
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
                        <!-- <div class="col-md-3 mt-2">
                            <span><b>Total Quantity No: </b><span id="show_total_qty_no"></span></span><br>
                            <span><b>Total Gross Net Weight KGS: </b><span id="show_total_gross_weight_kgs"></span></span><br>
                            <span><b>Total Net Weight KGS: </b><span id="show_total_net_weight_kgs"></span></span>
                        </div> -->
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

                        <div class="col-md-3 mt-4 d-flex justify-content-space-between align-items-center">
                            <div>
                                <?php $total_pages = 1;
                                if ($total_pages > 1): ?>
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination pagination-sm mb-0">
                                            <?php if ($current_page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page - 1])); ?>">Previous</a>
                                                </li>
                                            <?php endif; ?>
                                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                                <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>
                                            <?php if ($current_page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $current_page + 1])); ?>">Next</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                                <!-- <span class="text-muted small">
                                    Showing <?php // echo min($offset + 1, $total_rows); 
                                            ?> to <?php // echo min($offset + $rows_per_page, $total_rows); 
                                                                                                ?> of <?php //  echo $total_rows; 
                                                                                                                                                                ?> entries
                                </span> -->
                            </div>
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
                    <!-- Display the paginated entries (processed results) -->
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No.</th>
                                <th>P#. </th>
                                <th>Container No.</th>
                                <th>Goods (Name / Brand)</th>
                                <th>Size</th>
                                <th>ORIGIN</th>
                                <th>Total Qty</th>
                                <th>T.G.Weight</th>
                                <th>T.N.Weight</th>
                                <th>Sold Qty</th>
                                <th>S.G.Weight</th>
                                <th>S.N.Weight</th>
                                <th>Rem Qty</th>
                                <th>Rem.G.Weight</th>
                                <th>Rem.N.Weight</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;

                            foreach ($processed_entries as $container_no => $entry):
                                $remaining_qty = $entry['total_purchased_qty'] - ($entry['total_sold_qty'] ?? 0);
                                $remaining_gross_weight = $entry['total_purchased_kgs'] - ($entry['total_sold_kgs'] ?? 0);
                                $remaining_net_weight = $entry['total_purchased_net_kgs'] - ($entry['total_sold_net_kgs'] ?? 0);
                            ?>
                                <tr class="text-nowrap">
                                    <td><?= htmlspecialchars($i); ?></td>
                                    <td>P# <?= htmlspecialchars($entry['p_id']); ?></td>
                                    <td class="pointer" onclick="window.location.href = '?view=1&container=<?= $container_no; ?>'"><b><?= htmlspecialchars($container_no); ?></b></td>
                                    <td>
                                        <?= goodsName(htmlspecialchars($entry['goods_id'])) . ' / ' .
                                            htmlspecialchars($entry['brand']); ?>
                                    </td>
                                    <td><?= htmlspecialchars($entry['size']); ?></td>
                                    <td><?= htmlspecialchars($entry['origin']); ?></td>
                                    <td class="fw-bold text-success"><?= round($entry['total_purchased_qty']); ?></td>
                                    <td class="fw-bold text-success"><?= round($entry['total_purchased_kgs'], 2); ?></td>
                                    <td class="fw-bold text-success"><?= round($entry['total_purchased_net_kgs'], 2); ?></td>
                                    <td class="fw-bold text-danger"><?= round($entry['total_sold_qty']); ?></td>
                                    <td class="fw-bold text-danger"><?= round($entry['total_sold_kgs'], 2); ?></td>
                                    <td class="fw-bold text-danger"><?= round($entry['total_sold_net_kgs'], 2); ?></td>
                                    <td class="fw-bold text-primary"><?= round($remaining_qty); ?></td>
                                    <td class="fw-bold text-primary"><?= round($remaining_gross_weight, 2); ?></td>
                                    <td class="fw-bold text-primary"><?= round($remaining_net_weight, 2); ?></td>
                                </tr>
                            <?php $i++;
                            endforeach; ?>
                        </tbody>
                    </table>

                </div>
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

    function viewPurchase(container) {
        if (allot) {
            $.ajax({
                url: 'ajax/viewContainerStock.php',
                type: 'post',
                data: {
                    container: container,
                    page: "purchase-sale-containers",
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
<?php
if (isset($_GET['container']) && !empty($_GET['container']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $container = mysqli_real_escape_string($connect, $_GET['container']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase('$container'); });</script>";
}
?>