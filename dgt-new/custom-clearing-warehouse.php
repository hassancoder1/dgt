<?php
$goods_id = $size = $brand = $origin = '';
$page_title = 'CUSTOM CLEARING => WAREHOUSE';
$CCWPageMapping = [
    'transit' => 'Transit',
    'free-zone-import' => 'Free Zone Import',
    'local-import' => 'Local Import',
    'import-re-export' => 'Import Re-Export',
    'local-export' => 'Local Export',
    'local-market' => 'Local Market',
    'all' => 'All WareHouses'
];
$CCWPage = $CCWPageMapping[$_GET['CCWpage'] ?? ''] ?? '';
$page_title .= " ($CCWPage)";
$pageURL = "custom-clearing-warehouse?CCWpage=" . ($_GET['CCWpage'] ?? '');
include("header.php");
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
$print_url = '';
if ($_GET['CCWpage'] !== 'all') {
    // $NotAll = "data_for='$CCWPage' AND";
} else {
    // $NotAll = '';
    $print_url = 'print/print-custom-warehouse-general?CCWpage=all';
}
$where_clause = !empty($conditions) ? ' AND ' . implode(' AND ', $conditions) : '';
$sql = "SELECT * FROM warehouses WHERE p_s='p'";
$count_sql = "SELECT COUNT(*) AS total FROM ({$sql}) AS subquery";
$total_rows_result = mysqli_query($connect, $count_sql);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];
$sql .= " ORDER BY created_at DESC LIMIT $rows_per_page OFFSET $offset";
$data = mysqli_fetch_all(mysqli_query($connect, $sql), MYSQLI_ASSOC);
$total_pages = ceil($total_rows / $rows_per_page);
$Gdata = mysqli_fetch_all(mysqli_query($connect, "SELECT * FROM general_loading"), MYSQLI_ASSOC);
$Ldata = mysqli_fetch_all(mysqli_query($connect, "SELECT * FROM local_loading"), MYSQLI_ASSOC);

// Fetch sale entries from warehouses where p_s = 's'
$myD = mysqli_fetch_all(fetch('warehouses', ['p_s' => 's']), MYSQLI_ASSOC);

// Index general and local loading by their id
$G = $L = [];
foreach ($Gdata as $g) {
    $G[$g['id']] = $g;
}
foreach ($Ldata as $l) {
    $L[$l['id']] = $l;
}

// Build an array of sale entries (for each good_code, keep the first sale record)
$sold = [];
foreach ($myD as $my) {
    // Only store the first sale entry for a given good_code
    if (!isset($sold[$my['good_code']])) {
        // Decode good_data so that we can later get the quantity_no, etc.
        $my['good_data'] = json_decode($my['good_data'], true);
        $sold[$my['good_code']] = $my;
    }
}

$sortedEntries = [];
$i = 1;
foreach ($data as $entry) {
    if ($_GET['CCWpage'] === 'all' || $entry['warehouse'] === $_GET['CCWpage']) {
        $entry['good_data'] = json_decode($entry['good_data'], true);
        $ps_info_decoded = json_decode($entry['ps_info'] ?? '[]', true);
        $saleGoodsArray = $ps_info_decoded['sale_goods'] ?? [];
        $soldQty = 0;
        foreach ($saleGoodsArray as $goodCode) {
            if (isset($sold[$goodCode])) {
                $saleGoodData = $sold[$goodCode]['good_data'];
                if (is_string($saleGoodData)) {
                    $saleGoodData = json_decode($saleGoodData, true);
                }
                $soldQty += isset($saleGoodData['quantity_no']) ? $saleGoodData['quantity_no'] : 0;
            }
        }
        $remaining = $entry['good_data']['quantity_no'] - $soldQty;
        $sale_sr = $sale_warehouse = $sale_bluid = $sale_container_no = '';
        if (!empty($saleGoodsArray)) {
            $firstSaleGoodCode = $saleGoodsArray[0];
            if (isset($sold[$firstSaleGoodCode])) {
                $saleEntry = $sold[$firstSaleGoodCode];
                $loading_id = $saleEntry['loading_id'];
                $exploded = explode('~', $saleEntry['good_code']);
                $bl_no   = $exploded[0] ?? '';
                $s_loading_sr = $exploded[1] ?? '';
                $container_no = isset($saleEntry['good_data']['container_no']) ? $saleEntry['good_data']['container_no'] : '';
                $sale_loading = null;
                if (isset($saleEntry['type']) && strtolower($saleEntry['type']) === 'local') {
                    if (isset($L[$loading_id])) {
                        $sale_loading = $L[$loading_id];
                    }
                    $bl_uid = 'UID: ';
                } else {
                    if (isset($G[$loading_id])) {
                        $sale_loading = $G[$loading_id];
                    }
                    $bl_uid = 'BL: ';
                }
                if ($sale_loading) {
                    // $sale_info = ucfirst($p_s_val) . "# $t_sr => $bl_uid $bl_no ($s_loading_sr) => $container_no";
                    $sale_sr = ucfirst($sale_loading['p_s'] ?? '') . "# " . $sale_loading['t_sr'] ?? '';
                    $sale_warehouse = $CCWPageMapping[$saleEntry['warehouse']];
                    $sale_bluid = "$bl_uid $bl_no";
                    $sale_container_no = $container_no;
                }
            }
        }
        if ($entry['type'] === 'local') {
            $GL = $L[$entry['loading_id']] ?? [];
        } else {
            $GL = $G[$entry['loading_id']] ?? [];
        }
        $GL = is_array($GL) ? $GL : [$entry['loading_id'] => []];
        $sortedEntries[] = [
            'i'             => $i,
            'p_s'           => $GL['p_s'] ?? '',
            't_sr'          => $GL['t_sr'] ?? '',
            'warehouse'     => $entry['warehouse'],
            'sr'            => $entry['good_data']['sr'] ?? '',
            'bl_uid'        => explode('~', $entry['good_code'])[0],
            'type'          => $GL['t_type'] ?? '',
            'allot'         => $entry['good_data']['good']['allotment_name'] ?? '',
            'good_name'     => goodsName($entry['good_data']['good']['goods_id'] ?? ''),
            'origin'        => $entry['good_data']['good']['origin'] ?? '',
            'sale_sr' => $sale_sr,
            'sale_warehouse' => $sale_warehouse,
            'sale_bluid' => $sale_bluid,
            'sale_container_no' => $sale_container_no,
            'quantity_name' => $entry['good_data']['quantity_name'] ?? '',
            'total_qty'     => $entry['good_data']['quantity_no'] ?? 0,
            'sold_qty'      => $soldQty,
            'remaining_qty' => $remaining
        ];
        $i++;
    }
}
?>
<style>
    .mycontainer {
        max-height: 85vh !important;
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .fixed-top,
    .filter-sec {
        flex-shrink: 0;
    }

    .table-sec {
        flex-shrink: 1;
    }
</style>
<div class="mycontainer">
    <div class="fixed-top">
        <?php require_once('nav-links.php'); ?>
    </div>

    <div class="mx-2 filter-sec" style="margin-top:-40px;">
        <form method="get" class="row">
            <div class="col-md-12">
                <!-- 60% Section -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title fw-bold text-success"><?= $CCWPage; ?></h5>
                            <?php if ($CCWPage === 'All WareHouses') { ?>
                                <div class="dropdown hide-on-print">
                                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <a class="dropdown-item" href="<?= $print_url; ?>" target="_blank">
                                                <i class="fas text-secondary fa-eye me-2"></i> Print Preview
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')">
                                                <i class="fas text-secondary fa-print me-2"></i> Print
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')">
                                                <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')">
                                                <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')">
                                                <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')">
                                                <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
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
    <div class="row mx-1 table-sec">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body p-0">
                    <div class="table-responsive mytable">
                        <style>
                            .mytable {
                                max-height: 400px;
                                overflow: scroll;
                            }
                        </style>
                        <table class="table mytable table-bordered table-hover table-sm mb-0">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>No.</th>
                                    <th>P/S# (SR#)</th>
                                    <?php if ($_GET['CCWpage'] === 'all') { ?>
                                        <th>Warehouse</th>
                                    <?php } ?>
                                    <th>BL / UID</th>
                                    <th>Type</th>
                                    <th>Allot</th>
                                    <th>Goods Name / ORIGIN</th>
                                    <th>Sale#</th>
                                    <th>S.Warehouse</th>
                                    <th>S.BL/UID</th>
                                    <th>S.Container No</th>
                                    <th>Total Qty</th>
                                    <th>Sold Qty</th>
                                    <th>Rem Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($sortedEntries as $entry) {
                                    $rowColor = $trans = '';
                                ?>
                                    <tr class="text-nowrap">
                                        <td class="<?= $rowColor; ?>"><?= htmlspecialchars($entry['i']); ?></td>
                                        <td class="<?= $rowColor; ?> pointer"
                                            onclick="window.location.href = '?view=1&unique_code=&print_type=contract&CCWpage=<?= $_GET['CCWpage']; ?>';">
                                            <b><?= ucfirst($entry['p_s']); ?>#</b> <?= htmlspecialchars($entry['t_sr']); ?> (<?= htmlspecialchars($entry['sr']); ?>)
                                        </td>
                                        <?php if ($_GET['CCWpage'] === 'all') { ?>
                                            <td class="<?= $rowColor; ?>"><?= $CCWPageMapping[$entry['warehouse']]; ?></td>
                                        <?php } ?>
                                        <td class="<?= $rowColor; ?>"><?= htmlspecialchars($entry['bl_uid']); ?></td>
                                        <td class="<?= $rowColor; ?>"><?= ucfirst(htmlspecialchars($entry['type'])); ?></td>
                                        <td class="<?= $rowColor; ?>"><?= $entry['allot']; ?></td>
                                        <td class="<?= $rowColor; ?>"><?= htmlspecialchars($entry['good_name']) . ' / ' . htmlspecialchars($entry['origin']); ?></td>
                                        <td class="fw-bold text-dark"><?= $entry['sale_sr'] ?? '' ?></td>
                                        <td class="fw-bold text-dark"><?= $entry['sale_warehouse'] ?? '' ?></td>
                                        <td class="fw-bold text-dark"><?= $entry['sale_bluid'] ?? '' ?></td>
                                        <td class="fw-bold text-dark"><?= $entry['sale_container_no'] ?? '' ?></td>
                                        <td class="fw-bold text-success"><?= htmlspecialchars($entry['total_qty']); ?> <sub><?= htmlspecialchars($entry['quantity_name']); ?></sub></td>
                                        <td class="fw-bold text-danger"><?= htmlspecialchars($entry['sold_qty']); ?></td>
                                        <td class="fw-bold text-primary"><?= htmlspecialchars($entry['remaining_qty']); ?></td>
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
                    page: "custom-clearing-warehouse",
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
        if (in_array($key, ['boe_no', 'boe_date', 'pick_up_date', 'waiting_days', 'return_date', 'transporter_name', 'truck_number', 'details', 'driver_name', 'driver_number'])) {
            $ldata['agent'][$key] = $value;
        }
    }
    $ldata['good'] = calcNewValues([$_POST['quantity_no'], $_POST['qty_no']], $ldata['good'], 'both');
    $ldata['edited'] = true;
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