<?php
$page_title = 'Local Loading';
$pageURL = 'local-loading';
include("header.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $p_sr = $sea_road = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM transactions WHERE type = 'local' AND JSON_EXTRACT(show_in, '$.loading') = 'yes'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('local-loading');
    $is_search = true;
    if (isset($_GET['t_id']) && !empty($_GET['t_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['t_id']);
        $print_filters[] = 't_id=' . $p_sr;
        $conditions[] = "sr = '$p_sr'";
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
    $sql .= ' AND ' . implode(' AND ', $conditions);
}
$sql .= " AND locked IN ('1', '2') AND transfer_level >= '2' ORDER BY id DESC LIMIT $start_from, $results_per_page";
$transactions = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$sortedEntries = [];
$Lloading = [];
$lloadingCheck = $connect->query("SELECT id, t_id, goods_info FROM local_loading");
while ($LLone = mysqli_fetch_assoc($lloadingCheck)) {
    $LLone['goods_info'] = json_decode($LLone['goods_info'], true);
    if (!empty($LLone['goods_info']) && is_array($LLone['goods_info'])) {
        if (!isset($Lloading[$LLone['t_id']])) {
            $Lloading[$LLone['t_id']] = ['total_quantity' => 0];
        }
        foreach ($LLone['goods_info'] as $key => $value) {
            $quantity = is_numeric($value['quantity_no']) ? floatval($value['quantity_no']) : 0; // Validate numeric value
            $Lloading[$LLone['t_id']]['total_quantity'] += $quantity;
        }
    }
}
while ($transaction = mysqli_fetch_assoc($transactions)) {
    $totalLoadedQuantity = 0;
    $t_id = $transaction['id'];
    $T = transactionSingle($t_id);
    if (isset($Lloading[$t_id])) {
        $totalLoadedQuantity = $Lloading[$t_id]['total_quantity'] ?? 0;
    }
    if ($T['items_sum']['sum_qty_no'] == $totalLoadedQuantity) {
        $rowColor = 'text-dark';
    } elseif ($totalLoadedQuantity > 0) {
        $rowColor = 'text-warning';
    } else {
        $rowColor = 'text-danger';
    }
    $sortedEntries[] = array_merge(
        $transaction,
        $T,
        [
            'route_info' => json_decode($transaction['sea_road'] ?? '[]', true),
            'row_color' => $rowColor,
            'loaded_qty' => $totalLoadedQuantity
        ]
    );
}
usort($sortedEntries, function ($a, $b) {
    $colorPriority = ['text-danger' => 1, 'text-warning' => 2, 'text-dark' => 3];
    return $colorPriority[$a['row_color']] <=> $colorPriority[$b['row_color']];
});
?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
            Local Loading
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

                    $count_sql = "SELECT COUNT(id) AS total FROM `transactions` WHERE type = 'local' AND JSON_EXTRACT(show_in, '$.loading') = 'yes'";
                    if (count($conditions) > 0) {
                        $count_sql .= ' AND ' . implode(' AND ', $conditions);
                    }
                    $count_sql .= " AND locked IN ('1', '2') AND transfer_level >= '2' ORDER BY id DESC";
                    $count_result = mysqli_query($connect, $count_sql);
                    $row = mysqli_fetch_assoc($count_result);
                    $total_pages = ceil($row['total'] / $results_per_page);

                    // Previous button
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
    <form name="datesSubmit" class="mt-2" method="get">
        <div class="input-group input-group-sm">
            <div class="form-group">
                <label for="p_id" class="form-label">P/S#</label>
                <input type="number" name="p_id" value="<?php echo $p_sr; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
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
                <label for="route" class="form-label">Route</label>
                <select class="form-select form-select-sm" name="route" style="max-width:130px;" id="route">
                    <option value="" selected>All</option>
                    <option value="local" <?= $route === 'local' ? 'selected' : ''; ?>>Local Loading</option>
                    <option value="warehouse" <?= $route === 'warehouse' ? 'selected' : ''; ?>>WareHouse Transfer</option>
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
    <div class="table-responsive mt-4" id="RecordsTable">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>P/S#</th>
                    <th>Type</th>
                    <th>BR.</th>
                    <th>Date</th>
                    <th>Seller Acc.</th>
                    <th>Purchaser Acc.</th>
                    <th>Allot</th>
                    <th>Goods Name</th>
                    <th>T.Qty</th>
                    <th>Loaded Qty</th>
                    <th>KGs</th>
                    <th>Route</th>
                    <th>L. DATE</th>
                    <th>R. DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sortedEntries as $entry) {
                ?>
                    <tr class="text-nowrap <?= $entry['row_color']; ?>">
                        <td class="fw-bold pointer" onclick="window.location.href = '?view=1&t_id=<?= $entry['id']; ?>';">
                            <?= ucfirst($entry['p_s']) . '# ' . htmlspecialchars($entry['sr']); ?>
                        </td>
                        <td class="<?= $entry['row_color']; ?>"><?= strtoupper($entry['type']); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= branchName(strtoupper($entry['branch_id'])); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= my_date($entry['_date']); ?></td>
                        <td class="<?= $entry['row_color']; ?>" style="font-size:12px !important;font-weight:700 !important;" class=" acc_no <?php echo $rowColor; ?>"><?= strtoupper($entry['cr_acc']) . ' ' . $entry['cr_acc_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>" style="font-size:12px !important;font-weight:700 !important;" class=" acc_no <?php echo $rowColor; ?>"><?= strtoupper($entry['dr_acc']) . ' ' . $entry['dr_acc_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['items'][0]['allotment_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= goodsName($entry['items'][0]['goods_id']); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['items_sum']['sum_qty_no']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loaded_qty']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['items_sum']['sum_total_kgs']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= ucfirst($entry['route_info']['lwl'] ?? ''); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= my_date($entry['route_info']['loading_date'] ?? $entry['route_info']['transfer_date'] ?? ''); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= my_date($entry['route_info']['receiving_date'] ?? ''); ?></td>
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
            let edit = '<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>'; // Check if action exists
            let myDelete = '<?= isset($_GET['delete']) ? $_GET['delete'] : '' ?>'; // Check if action exists
            let wTransId = '<?= isset($_GET['warehouse_trans_id']) ? $_GET['warehouse_trans_id'] : '' ?>'; // Check if action exists
            $.ajax({
                url: 'ajax/viewLocalLoading.php',
                type: 'post',
                data: {
                    id: id,
                    page: "local-loading",
                    edit: edit,
                    delete: myDelete,
                    wTransId: wTransId
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while processing your reques Please try again.');
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
    $(document).ready(function() {
        var acc_no = getQueryParameter('acc_no') ? getQueryParameter('acc_no').toUpperCase() : '';
        var acc_name = getQueryParameter('acc_name') ? getQueryParameter('acc_name').toUpperCase() : '';
        $('tbody tr').each(function() {
            var rowAccNo = $(this).find('td.acc_no').text().trim().toUpperCase();
            var rowAccName = $(this).find('td.acc_name').text().trim().toUpperCase();
            if ((acc_no && rowAccNo !== acc_no) || (acc_name && !rowAccName.includes(acc_name))) {
                $(this).hide();
            }
        });
    });
</script>
<?php
if (isset($_GET['t_id'], $_GET['view']) && is_numeric($_GET['t_id']) && $_GET['view'] == 1) {
    $t_id = (int) $_GET['t_id'];
    echo "<script>
        jQuery(document).ready(function ($) { 
            $('#KhaataDetails').modal('show');
            viewPurchase('$t_id'); 
        });
    </script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entrySubmit'])) {
    $loadingInfo = [
        'loading_date'      => clean_input($_POST['loading_date']),
        'loading_company_name'   => clean_input($_POST['loading_company_name']),
        'receiving_date'      => clean_input($_POST['receiving_date']),
        'receiving_company_name'   => clean_input($_POST['receiving_company_name']),
        'transfer_date'      => clean_input($_POST['transfer_date'] ?? ''),
        'port_name'      => clean_input($_POST['port_name'] ?? ''),
        'launch_number'      => clean_input($_POST['launch_number'] ?? ''),
        'truck_number'      => clean_input($_POST['truck_number'] ?? ''),
        'truck_name'      => clean_input($_POST['truck_name'] ?? ''),
        'loading_warehouse'      => clean_input($_POST['loading_warehouse'] ?? ''),
        'receiving_warehouse'      => clean_input($_POST['receiving_warehouse'] ?? ''),
        'report' => clean_input($_POST['report']),
        'transferred' => false,
    ];
    $attachments = !empty($_FILES['entry_file']['name'][0])
        ? upload_files($_FILES['entry_file'])
        : json_encode([]);
    $newUID = clean_input($_POST['uid']);
    $sr      = $_POST['sr'];
    $selectGood = json_decode($_POST['select_good'], true);
    $currentGood = [
        'sr'            => $sr,
        'good'          => array_merge($selectGood, [
            'show_in'       => json_decode($selectGood['show_in'], true),
            'tracking_info' => json_decode($selectGood['tracking_info'], true),
        ]),
        'quantity_name' => $_POST['quantity_name'],
        'quantity_no'   => $_POST['quantity_no'],
        'gross_weight'  => $_POST['gross_weight'],
        'net_weight'    => $_POST['net_weight'],
        'truck_number'     => clean_input($_POST['truck_number'] ?? ''),
        'driver_name'    => clean_input($_POST['driver_name'] ?? ''),
        'driver_id' => clean_input($_POST['driver_id'] ?? ''),
        'driver_cell'    => clean_input($_POST['driver_cell'] ?? ''),
        'transport_name'  => clean_input($_POST['transport_name'] ?? ''),
    ];
    $data = [
        't_id'         => clean_input($_POST['t_id']),
        't_sr'         => clean_input($_POST['t_sr']),
        'p_s'          => clean_input($_POST['p_s']),
        't_type'       => clean_input($_POST['t_type']),
        'uid'        => $newUID,
        'loading_info' => json_encode($loadingInfo),
        'attachments'  => $attachments,
    ];
    $existingData  = fetch('local_loading', ['uid' => $_POST['activeUID'] ?? '']);
    $existingEntry = mysqli_fetch_assoc($existingData);
    if ($existingEntry) {
        $oldUID = $existingEntry['uid'];
        $existingGoodsInfo = json_decode($existingEntry['goods_info'] ?? '[]', true);
        $existingLoading = json_decode($existingEntry['loading_info'] ?? '{}', true);
        if ($newUID === $oldUID) {
            $existingGoodsInfo[$newUID . '~' . $sr] = $currentGood;
            $mergedLoading = array_merge($existingLoading, $loadingInfo);
            $data['loading_info'] = json_encode($mergedLoading);
            $data['goods_info'] = json_encode($existingGoodsInfo);
            $data['attachments'] = json_encode(array_merge(json_decode($data['attachments'], true), json_decode($existingEntry['attachments'], true)));
            $done = update('local_loading', $data, ['uid' => $oldUID]);
        } else {
            $oldGoodKey = $oldUID . '~' . $sr;
            if (isset($existingGoodsInfo[$oldGoodKey])) {
                unset($existingGoodsInfo[$oldGoodKey]);
            }
            update('local_loading', ['goods_info' => json_encode($existingGoodsInfo)], ['uid' => $oldUID]);
            $newBlCheck = fetch('local_loading', ['uid' => $newUID]);
            if ($newBlCheck->num_rows > 0) {
                $newRecord = mysqli_fetch_assoc($newBlCheck);
                $newGoodsInfo = json_decode($newRecord['goods_info'] ?? '[]', true);
                $newLoadingInfo = json_decode($newRecord['loading_info'] ?? '{}', true);
                $newGoodsInfo[$newUID . '~' . $sr] = $currentGood;
                $mergedLoading = array_merge($newLoadingInfo, $loadingInfo);
                $updateData = [
                    'loading_info' => json_encode($mergedLoading),
                    'goods_info'   => json_encode($newGoodsInfo),
                    'attachments' => json_encode(array_merge(json_decode($data['attachments'], true), json_decode($newRecord['attachments'], true)))
                ];
                $done = update('local_loading', $updateData, ['uid' => $newUID]);
            } else {
                $newGoods = [$newUID . '~' . $sr => $currentGood];
                $data['goods_info'] = json_encode($newGoods);
                $done = insert('local_loading', $data);
            }
        }
    } else {
        $newGoods = [$newUID . '~' . $sr => $currentGood];
        $data['goods_info'] = json_encode($newGoods);
        $done = insert('local_loading', $data);
    }
    if ($done) {
        message('success', '?view=1&t_id=' . $_POST['t_id'], 'Update Successful!');
    } else {
        message('error', '', 'An error occurred while processing the entry.');
    }
}
if (isset($_GET['transferUID'], $_GET['t_id'], $_GET['uid'])) {
    $t_id = clean_input($_GET['t_id']);
    $uid = clean_input($_GET['uid']);
    $stmt = $connect->prepare("SELECT loading_info FROM local_loading WHERE uid = ?");
    $stmt->bind_param("s", $uid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($loading_info);
    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        $loadingInfo = json_decode(html_entity_decode($loading_info), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($loadingInfo)) {
            array_walk_recursive($loadingInfo, function (&$value, $key) {
                if (is_string($value)) {
                    $value = str_replace(["\r", "\n"], ' ', $value);
                }
            });
            $loadingInfo['transferred'] = true;
            $updatedLoadingInfo = json_encode($loadingInfo, JSON_UNESCAPED_UNICODE);
            if ($updatedLoadingInfo !== false) {
                $updateStmt = $connect->prepare("UPDATE local_loading SET loading_info = ? WHERE uid = ?");
                $updateStmt->bind_param("ss", $updatedLoadingInfo, $uid);
                if ($updateStmt->execute()) {
                    message('success', "?view=1&t_id=$t_id", 'UID closed successfully!');
                } else {
                    message('error', "?view=1&t_id=$t_id", 'Error updating the record.');
                }
            } else {
                message('error', "?view=1&t_id=$t_id", 'Failed to encode the updated loading info.');
            }
        } else {
            message('error', "?view=1&t_id=$t_id", 'Invalid JSON format in loading_info.');
        }
    } else {
        message('error', "?view=1&t_id=$t_id", 'UID not found.');
    }
    $stmt->close();
}
if (isset($_GET['print'], $_GET['uid'])) {
    message('', "print/uid-print.php?uid=" . $_GET['uid'], '');
}
if (isset($_GET['t_id'], $_GET['delete'])) {
    $existingD = mysqli_fetch_assoc(fetch('local_loading', ['t_id' => $_GET['t_id']]));
    if ($existingD) {
        $goods = json_decode($existingD['goods_info'], true);
        if (isset($goods[$_GET['delete']])) {
            unset($goods[$_GET['delete']]);
        }
        $goods = json_encode($goods);
        $done = update('local_loading', ['goods_info' => $goods], ['t_id' => $_GET['t_id']]);
        if ($done) {
            message('success', '?view=1&t_id=' . $_GET['t_id'], 'Delete Successful!');
        }
    }
}

if (isset($_POST['TransferWarehouse'])) {
    $uid_id = mysqli_real_escape_string($connect, $_POST['uid_id']);
    $keys = explode('~', mysqli_real_escape_string($connect, $_POST['keys']));
    $pairedKeys = [];
    for ($i = 0; $i < count($keys); $i += 2) {
        if (isset($keys[$i + 1])) {
            $pairedKeys[] = $keys[$i] . '~' . $keys[$i + 1];
        }
    }
    $existingData = mysqli_fetch_assoc(fetch('local_loading', ['id' => $uid_id]));
    $existingData['loading_info'] = json_encode(clean_json_array(json_decode($existingData['loading_info'], true)));
    $existingData['goods_info'] = json_decode($existingData['goods_info'] ?? '[]', true);
    $existingData['warehouse_info'] = json_decode($existingData['warehouse_info'] ?? '[]', true);
    if ($existingData['p_s'] === 's') {
        $purchaseGoods = explode('@', $_POST['purchase_selected_ids']);
        $escapedPurchaseGoods = array_map(function ($good) use ($connect) {
            return mysqli_real_escape_string($connect, $good);
        }, $purchaseGoods);
        $goodKeys = implode("','", $escapedPurchaseGoods);
        $result = $connect->query("SELECT good_code, ps_info FROM warehouses WHERE good_code IN ('$goodKeys')");
        $purchaseRecords = [];
        while ($row = $result->fetch_assoc()) {
            $row['ps_info'] = json_decode($row['ps_info'], true) ?? [];
            $purchaseRecords[$row['good_code']] = $row;
        }
    }
    foreach ($pairedKeys as $index => $key) {
        if (!isset($existingData['warehouse_info'][$key])) {
            $existingData['warehouse_info'][$key] = [];
        }
        $existingData['warehouse_info'][$key] = array_merge($existingData['warehouse_info'][$key], [
            'warehouse' => $_POST['warehouse']
        ]);
        $good_data = $existingData['goods_info'][$key];
        $warehouseData = [
            'warehouse'    => $_POST['warehouse'],
            'loading_id'   => $uid_id,
            'type'         => $existingData['t_type'],
            'p_s'          => $existingData['p_s'],
            'good_code'    => $key,
            'loading_data' => $existingData['loading_info'],
            'good_data'    => json_encode($good_data),
        ];
        if ($existingData['p_s'] === 's') {
            $purchaseGoodCode = $purchaseGoods[$index] ?? null;
            if ($purchaseGoodCode) {
                if (isset($purchaseRecords[$purchaseGoodCode])) {
                    $existingSaleGoods = $purchaseRecords[$purchaseGoodCode]['ps_info']['sale_goods'] ?? [];
                    $purchaseRecords[$purchaseGoodCode]['ps_info']['sale_goods'] = array_merge($existingSaleGoods, [$key]);
                } else {
                    $purchaseRecords[$purchaseGoodCode] = [
                        'good_code' => $purchaseGoodCode,
                        'ps_info'   => ['sale_goods' => [$key]]
                    ];
                }
                $warehouseData['ps_info'] = json_encode(['purchase_goods' => [$purchaseGoodCode]]);
            }
        }
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
    $existingData['goods_info'] = json_encode(clean_json_array($existingData['goods_info']));
    $existingData['warehouse_info'] = json_encode(clean_json_array($existingData['warehouse_info']));
    if (update('local_loading', $existingData, ['id' => $uid_id])) {
        message('danger', '', 'Updated');
    }
}
?>