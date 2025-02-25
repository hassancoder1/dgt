<?php
$page_title = 'Loading Transfer';
$pageURL = 'loading-transfer';
include("header.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $p_sr = $sea_road = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM general_loading";
$conditions = ["JSON_EXTRACT(loading_info, '$.transferred') = 'true'"];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('loading-transfer');
    $is_search = true;
    if (isset($_GET['t_id']) && !empty($_GET['t_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['t_id']);
        $print_filters[] = 't_id=' . $p_sr;
        $conditions[] = "t_sr = '$p_sr'";
    }
    if (isset($_GET['start']) && !empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        $conditions[] = "_date >= '$start_print'";
    }
    if (isset($_GET['end']) && !empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        $conditions[] = "_date <= '$end_print'";
    }
    if (isset($_GET['is_transferred']) && $_GET['is_transferred'] !== '') {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        $print_filters[] = "is_transferred=" . $is_transferred;
        if ($is_transferred === '1') {
            $conditions[] = "locked = '1'";
        } elseif ($is_transferred === '0') {
            $conditions[] = "locked = '0'";
        }
    }
    if (isset($_GET['acc_no']) && !empty($_GET['acc_no'])) {
        $acc_no = mysqli_real_escape_string($connect, $_GET['acc_no']);
        $print_filters[] = 'acc_no=' . $acc_no;
        $conditions[] = "acc_no = '$acc_no'";
    }
    if (isset($_GET['acc_name']) && !empty($_GET['acc_name'])) {
        $acc_name = mysqli_real_escape_string($connect, $_GET['acc_name']);
        $print_filters[] = 'acc_name=' . $acc_name;
        $conditions[] = "acc_name = '$acc_name'";
    }
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $page = mysqli_real_escape_string($connect, $_GET['page']);
        $print_filters[] = 'page=' . $page;
    }
    if (isset($_GET['sea_road']) && !empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(sea_road, '$.sea_road') = '$sea_road'";
    }
}
if (count($conditions) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= " ORDER BY created_at DESC LIMIT $start_from, $results_per_page";
$BlNumbers = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$sortedEntries = [];
while ($oneBl = mysqli_fetch_assoc($BlNumbers)) {
    $oneBl['goods_info'] = json_decode($oneBl['goods_info'] ?? '[]', true);
    if (empty($oneBl['goods_info'])) {
        continue;
    }
    $row_color = '';
    $oneBl['loading_info'] = json_decode($oneBl['loading_info'] ?? '[]', true);
    $oneBl['agent_info'] = json_decode($oneBl['agent_info'] ?? '[]', true);
    $oneBl['warehouse_info'] = json_decode($oneBl['warehouse_info'] ?? '[]', true);
    $oneBl['loading_info']['status'] = $oneBl['loading_info']['status'] ?? '';
    if (empty($oneBl['agent_info']) && empty($oneBl['warehouse_info'])) {
        $row_color = 'text-danger';
    } else {
        $row_color = 'text-warning';
    }
    if ($oneBl['loading_info']['status'] === 'transferred') {
        $row_color = 'text-dark';
    }
    $oneBl['row_color'] = $row_color;
    $sortedEntries[] = $oneBl;
}

// Sorting based on priority
usort($sortedEntries, function ($a, $b) {
    $colorPriority = ['text-danger' => 1, 'text-warning' => 2, 'text-dark' => 3];
    return $colorPriority[$a['row_color']] <=> $colorPriority[$b['row_color']];
});
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3" style="margin-top: -40px;height:90vh;">
    <div class="d-flex justify-content-between align-items-center w-100">
        <form name="datesSubmit" class="mt-2" method="get">
            <div class="input-group input-group-sm">
                <div class="form-group">
                    <label for="t_id" class="form-label">P/S#</label>
                    <input type="number" name="t_id" value="<?php echo $p_sr; ?>" id="t_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
                </div>
                <div class="form-group">
                    <label for="start" class="form-label">Start Date</label>
                    <input type="date" name="start" value="<?php echo $start_print; ?>" id="start" class="form-control form-control-sm mx-1" style="max-width:160px;">
                </div>
                <div class="form-group">
                    <label for="end" class="form-label">End Date</label>
                    <input type="date" name="end" value="<?php echo $end_print; ?>" id="end" class="form-control form-control-sm mx-2" style="max-width:160px;">
                </div>
                <div class="form-group">
                    <label for="sea_road" class="form-label">SEA/ROAD</label>
                    <select class="form-select form-select-sm" name="sea_road" style="max-width:130px;" id="sea_road">
                        <option value="" selected>All</option>
                        <option value="sea" <?= $sea_road === 'sea' ? 'selected' : ''; ?>>by Sea</option>
                        <option value="road" <?= $sea_road === 'road' ? 'selected' : ''; ?>>by Road</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="is_transferred" class="form-label">Transfer Status</label>
                    <select class="form-select form-select-sm" name="is_transferred" style="max-width:180px;" id="is_transferred">
                        <option value="" selected>All</option>
                        <?php $imp_exp_array = array(1 => 'transferred', 0 => 'not transferred');
                        foreach ($imp_exp_array as $item => $value) {
                            $sel_tran = $is_transferred == $item ? 'selected' : '';
                            echo '<option ' . $sel_tran . '  value="' . $item . '">' . strtoupper($value) . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="acc_no" class="form-label">Account No.</label>
                    <input type="text" class="form-control form-control-sm mx-1" style="max-width:90px;" name="acc_no" placeholder="Acc No." value="<?php echo $acc_no; ?>" id="acc_no">
                </div>
                <div class="form-group mt-4 pt-1">
                    <?= $remove ? '<a href="' . $pageURL . '" class="btn btn-sm btn-danger"><i class="fa fa-sync-alt"></i></a>' : ''; ?>
                    <button type="submit" class="btn btn-sm btn-success">Search</button>
                </div>
            </div>
        </form>
        <div>
            <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                Loading Transfer
            </h1>
            <div class="d-flex gap-2">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center pagination-sm">
                        <?php
                        $current_url = $_SERVER['REQUEST_URI'];
                        $url_parts = parse_url($current_url);
                        parse_str($url_parts['query'] ?? '', $query_params);
                        unset($query_params['page']);
                        $base_url = $url_parts['path'] . '?' . http_build_query($query_params);

                        $count_sql = "SELECT COUNT(*) as total FROM general_loading";
                        if (count($conditions) > 0) {
                            $count_sql .= ' WHERE ' . implode(' AND ', $conditions);
                        }
                        $count_sql .= " ORDER BY created_at DESC LIMIT $start_from, $results_per_page";
                        $count_result = mysqli_query($connect, $count_sql);
                        $row = mysqli_fetch_assoc($count_result);
                        $total_pages = ceil($row['total'] / $results_per_page);
                        if ($page > 1) {
                            echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page - 1) . "'>Prev</a></li>";
                        } else {
                            echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
                        }

                        // Pagination logic with ellipsis
                        $max_displayed_pages = 5; // Maximum number of pages to display
                        if ($total_pages <= $max_displayed_pages) {
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $active_class = ($i == $page) ? 'active' : '';
                                echo "<li class='page-item $active_class'><a class='page-link' href='" . $base_url . "&page=$i'>$i</a></li>";
                            }
                        } else {
                            // Always display the first page
                            echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=1'>1</a></li>";
                            if ($page > 3) {
                                echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                            }

                            // Display pages around the current page
                            $start = max(2, $page - 1);
                            $end = min($total_pages - 1, $page + 1);
                            for ($i = $start; $i <= $end; $i++) {
                                $active_class = ($i == $page) ? 'active' : '';
                                echo "<li class='page-item $active_class'><a class='page-link' href='" . $base_url . "&page=$i'>$i</a></li>";
                            }

                            // Always display the last page
                            if ($page < $total_pages - 2) {
                                echo "<li class='page-item disabled'><span class='page-link'>...</span></li>";
                            }
                            echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=$total_pages'>$total_pages</a></li>";
                        }

                        // Next button
                        if ($page < $total_pages) {
                            echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page + 1) . "'>Next</a></li>";
                        } else {
                            echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
                        }
                        ?>
                    </ul>
                </nav>
                <div class="dropdown">
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
            </div>
        </div>
    </div>

    <style>
        #RecordsTable {
            height: 85%;
        }

        .fixed thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #fff;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="table-responsive mt-4" id="RecordsTable">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>P/S#</th>
                    <th>WAREHOUSE</th>
                    <th>AGENT ACC</th>
                    <th>AGENT NAME</th>
                    <th>L DATE</th>
                    <th>L PORT/BORDER</th>
                    <th>R DATE</th>
                    <th>R PORT/BORDER</th>
                    <th>Allot</th>
                    <th>B/L No</th>
                    <th>CONTAINERS</th>
                    <th>Total QTY</th>
                    <th>T.G.KGS</th>
                    <th>T.N.KGS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sortedEntries as $entry) {
                    $agentInfo = $entry['agent_info'] ?? [];
                    $firstAgent = reset($agentInfo);
                    if (!($firstAgent['agent_exist'] ?? false)) {
                        $entry['agent_info'] = [];
                    }

                ?>
                    <tr class="text-nowrap <?= $entry['row_color']; ?>">
                        <td class="fw-bold pointer" onclick="window.location.href = '?view=1&bl_id=<?= $entry['id']; ?>';">
                            <?= ucfirst($entry['p_s']) . '# ' . htmlspecialchars($entry['t_sr']); ?>
                        </td>
                        <td class="<?= $entry['row_color']; ?>"><?= ucwords(str_replace('-', ' ', (!empty($entry['warehouse_info']) ? reset($entry['warehouse_info']) : [])['warehouse'] ?? '')); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= (!empty($entry['agent_info']) ? reset($entry['agent_info']) : [])['ag_acc_no'] ?? '~~NOT~EXIST~~'; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= (!empty($entry['agent_info']) ? reset($entry['agent_info']) : [])['ag_name'] ?? '~~NOT~EXIST~~'; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['loading']['loading_date']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['loading']['loading_port_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['receiving']['receiving_date']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['receiving']['receiving_port_name']; ?></td>
                        <td class="fw-bold <?= $entry['row_color']; ?>"><?= $entry['bl_no']; ?></td>
                        <td class="fw-bold <?= $entry['row_color']; ?>"><?= reset($entry['goods_info'])['good']['allotment_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= count($entry['goods_info']); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= array_sum(array_column($entry['goods_info'], 'quantity_no')); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= array_sum(array_column($entry['goods_info'], 'gross_weight')); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= array_sum(array_column($entry['goods_info'], 'net_weight')); ?></td>
                    </tr>
                <?php
                } ?>
            </tbody>
        </table>
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
    function viewPurchase(id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewLoadingTransfer.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "loading-transfer",
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while processing your request. Please try again.');
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>
<?php
if (isset($_POST['UpdatePermission'])) {
    $bl_id = mysqli_real_escape_string($connect, $_POST['bl_id']);
    $keys = explode('~', mysqli_real_escape_string($connect, $_POST['permissionkeys']));
    $permission =  $_POST['permission'] === 'yes' ? true : false;
    $existingData = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));
    $existingData['agent_info'] = json_decode($existingData['agent_info'] ?? '[]', true);
    foreach ($keys as $key) {
        if (isset($existingData['agent_info'][$key])) {
            $existingData['agent_info'][$key]['edit_permission'] =  $permission;
        }
    }
    $existingData['agent_info'] = json_encode($existingData['agent_info']);
    $done = update('general_loading', $existingData, ['id' => $bl_id]);
    if ($done) {
        message('danger', '', 'Perimission Updated!');
    }
}
if (isset($_POST['TransferToAgentAndWarehouse'])) {
    $bl_id = mysqli_real_escape_string($connect, $_POST['bl_id']);
    $keys = explode('~', mysqli_real_escape_string($connect, $_POST['keys']));
    $agent_row_id = $_POST['row_id'];
    $pairedKeys = [];
    for ($i = 0; $i < count($keys); $i += 2) {
        if (isset($keys[$i + 1])) {
            $pairedKeys[] = $keys[$i] . '~' . $keys[$i + 1];
        }
    }
    $agentBillNumberCount = mysqli_fetch_assoc($connect->query(
        "SELECT COUNT(*) AS total_count FROM general_loading WHERE JSON_SEARCH(agent_info, 'one', '$agent_row_id', NULL, '$.*.row_id') IS NOT NULL"
    ));

    $existingData = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));

    // Merge and clean loading_info
    $existingData['loading_info'] = json_encode(array_merge(
        json_decode($existingData['loading_info'] ?? '[]', true) ?? [],
        ['status' => 'filled', 'agent_exist' => $_POST['agent_exist'] === 'yes']
    ));
    $existingData['loading_info'] = json_encode(clean_json_array(json_decode($existingData['loading_info'], true)));

    $existingData['goods_info'] = json_decode($existingData['goods_info'] ?? '[]', true);
    $existingData['agent_info'] = json_decode($existingData['agent_info'] ?? '[]', true);
    $existingData['warehouse_info'] = json_decode($existingData['warehouse_info'] ?? '[]', true);

    // If this is a sale entry, process the purchase goods sent via POST.
    if ($existingData['p_s'] === 's') {
        // Expecting a string like "purchaseGood1@purchaseGood2@..." 
        $purchaseGoods = explode('@', $_POST['purchase_selected_ids']);
        // Escape each purchase good for safety.
        $escapedPurchaseGoods = array_map(function ($good) use ($connect) {
            return mysqli_real_escape_string($connect, $good);
        }, $purchaseGoods);
        $goodKeys = implode("','", $escapedPurchaseGoods);

        // Fetch existing purchase records from warehouses
        $result = $connect->query("SELECT good_code, ps_info FROM warehouses WHERE good_code IN ('$goodKeys')");
        $purchaseRecords = [];
        while ($row = $result->fetch_assoc()) {
            // Decode the ps_info; if empty, default to an empty array.
            $row['ps_info'] = json_decode($row['ps_info'], true) ?? [];
            // Index by good_code for later lookup.
            $purchaseRecords[$row['good_code']] = $row;
        }
    }
    // Process each sale entry (paired key)
    foreach ($pairedKeys as $index => $key) {
        // Initialize agent_info and warehouse_info if not already set.
        if (!isset($existingData['agent_info'][$key])) {
            $existingData['agent_info'][$key] = ['bill_number' => (int)$agentBillNumberCount['total_count'] + 1];
        }
        if (!isset($existingData['warehouse_info'][$key])) {
            $existingData['warehouse_info'][$key] = [];
        }

        // Update agent_info details.
        $existingData['agent_info'][$key] = array_merge($existingData['agent_info'][$key], [
            'ag_acc_no'       => $_POST['ag_acc_no'],
            'ag_name'         => $_POST['ag_name'],
            'ag_id'           => $_POST['ag_id'],
            'row_id'          => $_POST['row_id'],
            'edit_permission' => false,
            'agent_exist'     => $_POST['agent_exist'] === 'yes'
        ]);
        $existingData['warehouse_info'][$key] = array_merge($existingData['warehouse_info'][$key], [
            'warehouse' => $_POST['warehouse']
        ]);

        $good_data = $existingData['goods_info'][$key];
        $warehouseData = [
            'warehouse'    => $_POST['warehouse'],
            'loading_id'   => $bl_id,
            'type'         => $existingData['t_type'],
            'p_s'          => $existingData['p_s'],
            'good_code'    => $key,
            'loading_data' => $existingData['loading_info'],
            'good_data'    => json_encode($good_data),
            'agent_data'   => json_encode($existingData['agent_info'][$key])
        ];

        // For sale entries, update ps_info fields.
        if ($existingData['p_s'] === 's') {
            // Get the corresponding purchase good code from the POST data.
            $purchaseGoodCode = $purchaseGoods[$index] ?? null;
            if ($purchaseGoodCode) {
                // Update the purchase record's sale_goods array.
                if (isset($purchaseRecords[$purchaseGoodCode])) {
                    $existingSaleGoods = $purchaseRecords[$purchaseGoodCode]['ps_info']['sale_goods'] ?? [];
                    $purchaseRecords[$purchaseGoodCode]['ps_info']['sale_goods'] = array_merge($existingSaleGoods, [$key]);
                } else {
                    // If no record exists, create one with the sale_goods array.
                    $purchaseRecords[$purchaseGoodCode] = [
                        'good_code' => $purchaseGoodCode,
                        'ps_info'   => ['sale_goods' => [$key]]
                    ];
                }
                // For this sale record, store purchase_goods as an array.
                $warehouseData['ps_info'] = json_encode(['purchase_goods' => [$purchaseGoodCode]]);
            }
        }

        // Update or insert the warehouse record for this sale.
        if (recordExists('warehouses', ['good_code' => $key])) {
            update('warehouses', $warehouseData, ['good_code' => $key]);
            $warehouseEntry = mysqli_fetch_assoc(fetch('warehouses', ['good_code' => $key]));
            $warehouse_id = $warehouseEntry['id'] ?? null;
        } else {
            insert('warehouses', $warehouseData);
            $warehouse_id = mysqli_insert_id($connect);
        }
        if ($warehouse_id) {
            $existingData['warehouse_info'][$key]['row_id'] = $warehouse_id;
        }
    }

    // Now update the purchase records in warehouses with the new sale_goods arrays.
    if ($existingData['p_s'] === 's' && !empty($purchaseRecords)) {
        $cases = '';
        $goodCodes = [];
        foreach ($purchaseRecords as $purchaseRecord) {
            $updated_ps_info = mysqli_real_escape_string($connect, json_encode($purchaseRecord['ps_info']));
            $good_code = mysqli_real_escape_string($connect, $purchaseRecord['good_code']);
            $cases .= " WHEN '$good_code' THEN '$updated_ps_info' ";
            $goodCodes[] = "'$good_code'";
        }
        if (!empty($goodCodes)) {
            $goodCodesList = implode(',', $goodCodes);
            $sql = "UPDATE warehouses 
                    SET ps_info = CASE good_code 
                        $cases
                    END 
                    WHERE good_code IN ($goodCodesList)";
            $connect->query($sql);
        }
    }

    // Clean up JSON columns before updating general_loading.
    $existingData['goods_info'] = json_encode(clean_json_array($existingData['goods_info']));
    $existingData['agent_info'] = json_encode(clean_json_array($existingData['agent_info']));
    $existingData['warehouse_info'] = json_encode(clean_json_array($existingData['warehouse_info']));
    $done = update('general_loading', $existingData, ['id' => $bl_id]);
    if ($done) {
        message('danger', '', 'Updated');
    }
}

if (isset($_GET['bl_id']) && is_numeric($_GET['bl_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $bl_id = mysqli_real_escape_string($connect, $_GET['bl_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($bl_id); });</script>";
}
?>