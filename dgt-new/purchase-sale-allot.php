<?php
$page_title = 'Purchase Sale Allot Stock';
$pageURL = 'purchase-sale-allot';
include("header.php");

// Retrieve filter values from GET parameters
$allot = isset($_GET['allot']) ? trim($_GET['allot']) : '';
$size = isset($_GET['size']) ? trim($_GET['size']) : '';
$brand = isset($_GET['brand']) ? trim($_GET['brand']) : '';
$origin = isset($_GET['origin']) ? trim($_GET['origin']) : '';
$goods_name = isset($_GET['goods_name']) ? trim($_GET['goods_name']) : '';
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

// Base SQL query with JOIN to include `sr` from `transactions`
$sql = "SELECT ti.*, t.sr 
        FROM transaction_items ti
        INNER JOIN transactions t ON ti.parent_id = t.id
        WHERE t.transfer_level >= 2 AND ti.allotment_name != ''";

// Apply filters dynamically
if ($allot) {
    $sql .= " AND ti.allotment_name LIKE '%" . mysqli_real_escape_string($connect, $allot) . "%'";
    $is_search = true;
}
if ($size) {
    $sql .= " AND ti.size = '" . mysqli_real_escape_string($connect, $size) . "'";
    $is_search = true;
}
if ($brand) {
    $sql .= " AND ti.brand = '" . mysqli_real_escape_string($connect, $brand) . "'";
    $is_search = true;
}
if ($origin) {
    $sql .= " AND ti.origin = '" . mysqli_real_escape_string($connect, $origin) . "'";
    $is_search = true;
}
if ($goods_name) {
    $sql .= " AND ti.goods_id IN (SELECT id FROM goods WHERE name = '" . mysqli_real_escape_string($connect, $goods_name) . "')";
    $is_search = true;
}
if ($date_from && $date_to) {
    $sql .= " AND DATE(ti.created_at) BETWEEN '" . mysqli_real_escape_string($connect, $date_from) . "' AND '" . mysqli_real_escape_string($connect, $date_to) . "'";
    $is_search = true;
}

// Fetch data and count total rows
$result = mysqli_query($connect, $sql);

$total_rows = mysqli_num_rows($result);

// Apply pagination
$sql .= " LIMIT $offset, $rows_per_page";
$entries = mysqli_query($connect, $sql);

// Process data
$processed_entries = [];
while ($entry = mysqli_fetch_assoc($entries)) {
    $allotment_name = $entry['allotment_name'];
    if (!isset($processed_entries[$allotment_name])) {
        $processed_entries[$allotment_name] = [
            'goods_id' => $entry['goods_id'],
            'sr' => $entry['sr'],
            'size' => $entry['size'],
            'brand' => $entry['brand'],
            'origin' => $entry['origin'],
            'total_purchased_qty' => 0,
            'total_purchased_kgs' => 0,
            'total_purchased_net_kgs' => 0,
            'total_sold_qty' => 0,
            'total_sold_kgs' => 0,
            'total_sold_net_kgs' => 0,
        ];
    }

    // Process purchase and sale entries
    if ($entry['p_s'] == 'p') {
        $processed_entries[$allotment_name]['total_purchased_qty'] += $entry['qty_no'];
        $processed_entries[$allotment_name]['p_id'] = $entry['parent_id'];
        $processed_entries[$allotment_name]['total_purchased_kgs'] += $entry['total_kgs'];
        $processed_entries[$allotment_name]['total_purchased_net_kgs'] += $entry['net_kgs'];
    } elseif ($entry['p_s'] == 's') {
        $processed_entries[$allotment_name]['s_id'] = $entry['parent_id'];
        $processed_entries[$allotment_name]['total_sold_qty'] += $entry['qty_no'];
        $processed_entries[$allotment_name]['total_sold_kgs'] += $entry['total_kgs'];
        $processed_entries[$allotment_name]['total_sold_net_kgs'] += $entry['net_kgs'];
    }
}

// Pagination calculation
$total_pages = ceil($total_rows / $rows_per_page);
$paginated_entries = array_slice($processed_entries, 0, $rows_per_page);
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
                                <?php if ($total_pages > 1): ?>
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
                                <span class="text-muted small">
                                    Showing <?php echo min($offset + 1, $total_rows); ?> to <?php echo min($offset + $rows_per_page, $total_rows); ?> of <?php echo $total_rows; ?> entries
                                </span>
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
                    <table class="table table-bordered table-hover table-sm fix-head-table mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No.</th>
                                <th>P#. </th>
                                <th>Allot</th>
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
                            $i = $offset + 1;
                            foreach ($paginated_entries as $allotment_name => $entry):
                                $remaining_qty = $entry['total_purchased_qty'] - $entry['total_sold_qty'];
                                $remaining_gross_weight = $entry['total_purchased_kgs'] - $entry['total_sold_kgs'];
                                $remaining_net_weight = $entry['total_purchased_net_kgs'] - $entry['total_sold_net_kgs'];
                            ?>
                                <tr class="text-nowrap">
                                    <td><?= htmlspecialchars($i); ?></td>
                                    <td>P# <?= htmlspecialchars($entry['sr']); ?></td>
                                    <td class="pointer" onclick="window.location.href = '?view=1&allot=<?= $allotment_name; ?>'"><b><?= htmlspecialchars($allotment_name); ?></b></td>
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
                            <?php
                                $i++;
                            endforeach;
                            ?>
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

    function viewPurchase(allot) {
        if (allot) {
            $.ajax({
                url: 'ajax/viewAllotStock.php',
                type: 'post',
                data: {
                    allot: allot,
                    page: "purchase-sale-allot",
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
if (isset($_GET['allot']) && !empty($_GET['allot']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $allot = mysqli_real_escape_string($connect, $_GET['allot']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase('$allot'); });</script>";
}
?>