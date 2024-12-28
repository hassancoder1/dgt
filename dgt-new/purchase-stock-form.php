<?php
$page_title = 'P.Stock Form';
$pageURL = 'purhcase-stock-form';
include("header.php");

$resetFilters = $size = $brand = $origin = $goods_name = $date_from = $date_to = $net_kgs = $qty_no = '';
$is_search = false;
global $connect;

// Pagination settings
$rows_per_page = 20;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;

// Build the SQL query
$sql = "
    SELECT ti.*, t.sr
    FROM transaction_items ti
    INNER JOIN transactions t ON ti.parent_id = t.id
    WHERE t.p_s = 'p' AND t.transfer_level >= 2
";

$conditions = [];

// Handle filters
if ($_GET) {
    $resetFilters = removeFilter($pageURL);
    if (!empty($_GET['size'])) {
        $size = mysqli_real_escape_string($connect, $_GET['size']);
        $conditions[] = "size='$size'";
    }
    if (!empty($_GET['brand'])) {
        $brand = mysqli_real_escape_string($connect, $_GET['brand']);
        $conditions[] = "brand='$brand'";
    }
    if (!empty($_GET['origin'])) {
        $origin = mysqli_real_escape_string($connect, $_GET['origin']);
        $conditions[] = "origin='$origin'";
    }
    if (!empty($_GET['goods_name'])) {
        $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
        $conditions[] = "goods_id IN (SELECT id FROM goods WHERE name='$goods_name')";
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
        $conditions[] = "net_kgs='$net_kgs'";
    }
    if (!empty($_GET['qty_no'])) {
        $qty_no = mysqli_real_escape_string($connect, $_GET['qty_no']);
        $conditions[] = "qty_no='$qty_no'";
    }
}

if (!empty($conditions)) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
    $is_search = true;
}

$total_rows_result = mysqli_query($connect, $sql);
$total_rows = mysqli_num_rows($total_rows_result);

$sql .= " ORDER BY parent_id, created_at LIMIT $rows_per_page OFFSET $offset";

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
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title"><?php echo $page_title; ?></h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
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
                    <table class="table table-bordered table-hover table-sm fix-head-table mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No.</th>
                                <th>P# (Count)</th>
                                <th>Date</th>
                                <th>Goods Name</th>
                                <th>SIZE</th>
                                <th>BRAND</th>
                                <th>ORIGIN</th>
                                <th>QTY.Ne</th>
                                <th>QTY.No</th>
                                <th>G.W.KGS</th>
                                <th>N.W.KGS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = $offset + 1;
                            $current_parent_id = null;
                            $entry_count = 0;
                            if (mysqli_num_rows($entries) > 0):
                                $total_qty_no = 0;
                                $total_gross_weight_kgs = 0;
                                $total_net_weight_kgs = 0;
                                while ($entry = mysqli_fetch_assoc($entries)):
                                    if ($entry['parent_id'] !== $current_parent_id) {
                                        $current_parent_id = $entry['parent_id'];
                                        $entry_count = 1;
                                    } else {
                                        $entry_count++;
                                    }
                            ?>
                                    <tr class="text-nowrap">
                                        <td><?= htmlspecialchars($i); ?></td>
                                        <td class="pointer" onclick="viewPurchase(<?php echo $entry['parent_id']; ?>)"
                                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                            <b>P#</b> <?= htmlspecialchars($entry['sr']); ?> (<?= $entry_count; ?>)
                                        </td>
                                        <td><?= my_date(htmlspecialchars($entry['created_at'])); ?></td>
                                        <td><?= goodsName(htmlspecialchars($entry['goods_id'])); ?></td>
                                        <td><?= htmlspecialchars($entry['size']); ?></td>
                                        <td><?= htmlspecialchars($entry['brand']); ?></td>
                                        <td><?= htmlspecialchars($entry['origin']); ?></td>
                                        <td><?= htmlspecialchars($entry['qty_name']); ?></td>
                                        <td><?= round(htmlspecialchars($entry['qty_no'])); ?></td>
                                        <td><?= round(htmlspecialchars($entry['total_kgs'])); ?></td>
                                        <td><?= round(htmlspecialchars($entry['net_kgs'])); ?></td>
                                    </tr>
                                <?php $i++;
                                    $total_qty_no += round(htmlspecialchars($entry['qty_no']));
                                    $total_gross_weight_kgs += round(htmlspecialchars($entry['total_kgs']));
                                    $total_net_weight_kgs += round(htmlspecialchars($entry['net_kgs']));
                                endwhile; ?>
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
                    page: "purhcase-stock-form",
                    type: 'purchase'
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