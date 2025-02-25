<?php
$page_title = 'G. Loading';
$pageURL = 'general-loading';
include("header.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $p_sr = $sea_road = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM transactions WHERE type IN ('booking', 'commission') AND JSON_EXTRACT(show_in, '$.loading') = 'yes'";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('general-loading');
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
$sql .= " AND locked IN ('1', '2') AND (CASE WHEN type = 'commission' THEN transfer_level >= '1' ELSE transfer_level >= '2' END) ORDER BY id DESC LIMIT $start_from, $results_per_page";
$transactions = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$sortedEntries = [];
$Gloading = [];
$gloadingCheck = $connect->query("SELECT id, t_id, goods_info FROM general_loading");
while ($GLone = mysqli_fetch_assoc($gloadingCheck)) {
    $GLone['goods_info'] = json_decode($GLone['goods_info'], true);
    if (!empty($GLone['goods_info']) && is_array($GLone['goods_info'])) {
        if (!isset($Gloading[$GLone['t_id']])) {
            $Gloading[$GLone['t_id']] = ['total_quantity' => 0];
        }
        foreach ($GLone['goods_info'] as $key => $value) {
            $quantity = is_numeric($value['quantity_no']) ? floatval($value['quantity_no']) : 0; // Validate numeric value
            $Gloading[$GLone['t_id']]['total_quantity'] += $quantity;
        }
    }
}
while ($transaction = mysqli_fetch_assoc($transactions)) {
    $totalLoadedQuantity = 0;
    $t_id = $transaction['id'];
    $T = transactionSingle($t_id);
    if (isset($Gloading[$t_id])) {
        $totalLoadedQuantity = $Gloading[$t_id]['total_quantity'] ?? 0;
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
            'sea_road_array' => json_decode($transaction['sea_road'] ?? '[]', true),
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
                General Loading
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

                        $count_sql = "SELECT COUNT(id) AS total FROM `transactions` WHERE type IN ('booking', 'commission') AND JSON_EXTRACT(show_in, '$.loading') = 'yes'";
                        if (count($conditions) > 0) {
                            $count_sql .= ' AND ' . implode(' AND ', $conditions);
                        }
                        $count_sql .= " AND locked = '1' AND transfer_level >= '2' ORDER BY id DESC";
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
                    <th>SEA/ROAD</th>
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
                        <td class="<?= $entry['row_color']; ?>"><?= ucfirst($entry['sea_road']); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= my_date($entry['sea_road_array']['l_date'] ?? $entry['sea_road_array']['l_date_road']); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= my_date($entry['sea_road_array']['r_date'] ?? $entry['sea_road_array']['r_date_road']); ?></td>
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
            $.ajax({
                url: 'ajax/viewGeneralLoading.php',
                type: 'post',
                data: {
                    id: id,
                    page: "general-loading",
                    edit: edit,
                    delete: myDelete
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
        'loading' => [
            'loading_date'      => clean_input($_POST['loading_date']),
            'loading_country'   => clean_input($_POST['loading_country']),
            'loading_port_name' => clean_input($_POST['loading_port_name']),
        ],
        'receiving' => [
            'receiving_date'      => clean_input($_POST['receiving_date']),
            'receiving_country'   => clean_input($_POST['receiving_country']),
            'receiving_port_name' => clean_input($_POST['receiving_port_name']),
        ],
        'importer' => [
            'im_acc_id'      => clean_input($_POST['im_acc_id']),
            'im_acc_no'      => clean_input($_POST['im_acc_no']),
            'im_acc_name'    => clean_input($_POST['im_acc_name']),
            'im_acc_kd_id'   => clean_input($_POST['im_acc_kd_id']),
            'im_acc_details' => clean_input($_POST['im_acc_details']),
        ],
        'exporter' => [
            'xp_acc_id'      => clean_input($_POST['xp_acc_id']),
            'xp_acc_no'      => clean_input($_POST['xp_acc_no']),
            'xp_acc_name'    => clean_input($_POST['xp_acc_name']),
            'xp_acc_kd_id'   => clean_input($_POST['xp_acc_kd_id']),
            'xp_acc_details' => clean_input($_POST['xp_acc_details']),
        ],
        'notify' => [
            'np_acc_id'      => clean_input($_POST['np_acc_id']),
            'np_acc_no'      => clean_input($_POST['np_acc_no']),
            'np_acc_name'    => clean_input($_POST['np_acc_name']),
            'np_acc_kd_id'   => clean_input($_POST['np_acc_kd_id']),
            'np_acc_details' => clean_input($_POST['np_acc_details']),
        ],
        'shipping' => [
            'shipping_name'     => clean_input($_POST['shipping_name']),
            'shipping_phone'    => clean_input($_POST['shipping_phone']),
            'shipping_whatsapp' => clean_input($_POST['shipping_whatsapp']),
            'shipping_email'    => clean_input($_POST['shipping_email']),
            'shipping_address'  => clean_input($_POST['shipping_address']),
            'transfer_by'       => clean_input($_POST['transfer_by']),
        ],
        'report' => clean_input($_POST['report']),
        'transferred' => false,
    ];
    $attachments = !empty($_FILES['entry_file']['name'][0])
        ? upload_files($_FILES['entry_file'])
        : json_encode([]);
    $newBlNo = clean_input($_POST['bl_no']);
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
        'container_no'  => $_POST['container_no'],
        'container_name' => $_POST['container_name'],
    ];
    $data = [
        't_id'         => clean_input($_POST['t_id']),
        't_sr'         => clean_input($_POST['t_sr']),
        'p_s'          => clean_input($_POST['p_s']),
        't_type'       => clean_input($_POST['t_type']),
        'bl_no'        => $newBlNo,
        'loading_info' => json_encode($loadingInfo),
        'attachments'  => $attachments,
    ];
    $existingData  = fetch('general_loading', ['bl_no' => $_POST['activeBl'] ?? '']);
    $existingEntry = mysqli_fetch_assoc($existingData);
    if ($existingEntry) {
        $oldBlNo = $existingEntry['bl_no'];
        $existingGoodsInfo = json_decode($existingEntry['goods_info'] ?? '[]', true);
        $existingLoading = json_decode($existingEntry['loading_info'] ?? '{}', true);
        if ($newBlNo === $oldBlNo) {
            $existingGoodsInfo[$newBlNo . '~' . $sr] = $currentGood;
            $mergedLoading = array_merge($existingLoading, $loadingInfo);
            $data['loading_info'] = json_encode($mergedLoading);
            $data['goods_info'] = json_encode($existingGoodsInfo);
            $data['attachments'] = json_encode(array_merge(json_decode($data['attachments'], true), json_decode($existingEntry['attachments'], true)));
            $done = update('general_loading', $data, ['bl_no' => $oldBlNo]);
        } else {
            $oldGoodKey = $oldBlNo . '~' . $sr;
            if (isset($existingGoodsInfo[$oldGoodKey])) {
                unset($existingGoodsInfo[$oldGoodKey]);
            }
            update('general_loading', ['goods_info' => json_encode($existingGoodsInfo)], ['bl_no' => $oldBlNo]);
            $newBlCheck = fetch('general_loading', ['bl_no' => $newBlNo]);
            if ($newBlCheck->num_rows > 0) {
                $newRecord = mysqli_fetch_assoc($newBlCheck);
                $newGoodsInfo = json_decode($newRecord['goods_info'] ?? '[]', true);
                $newLoadingInfo = json_decode($newRecord['loading_info'] ?? '{}', true);
                $newGoodsInfo[$newBlNo . '~' . $sr] = $currentGood;
                $mergedLoading = array_merge($newLoadingInfo, $loadingInfo);
                $updateData = [
                    'loading_info' => json_encode($mergedLoading),
                    'goods_info'   => json_encode($newGoodsInfo),
                    'attachments' => json_encode(array_merge(json_decode($data['attachments'], true), json_decode($newRecord['attachments'], true)))
                ];
                $done = update('general_loading', $updateData, ['bl_no' => $newBlNo]);
            } else {
                $newGoods = [$newBlNo . '~' . $sr => $currentGood];
                $data['goods_info'] = json_encode($newGoods);
                $done = insert('general_loading', $data);
            }
        }
    } else {
        $newGoods = [$newBlNo . '~' . $sr => $currentGood];
        $data['goods_info'] = json_encode($newGoods);
        $done = insert('general_loading', $data);
    }
    if ($done) {
        message('success', '?view=1&t_id=' . $_POST['t_id'], 'Update Successful!');
    } else {
        message('error', '', 'An error occurred while processing the entry.');
    }
}
if (isset($_GET['transferBL'], $_GET['t_id'], $_GET['bl_no'])) {
    $t_id = clean_input($_GET['t_id']);
    $bl_no = clean_input($_GET['bl_no']);
    $stmt = $connect->prepare("SELECT loading_info FROM general_loading WHERE bl_no = ?");
    $stmt->bind_param("s", $bl_no);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($loading_info);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        $loadingInfo = json_decode(html_entity_decode($loading_info), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($loadingInfo)) {
            array_walk_recursive($loadingInfo, function (&$value, $key) {
                if (is_string($value)) {
                    $value = str_replace(["\r", "\n"], ' ', $value); // replace newlines and carriage returns with a space
                }
            });
            $loadingInfo['transferred'] = true;
            $updatedLoadingInfo = json_encode($loadingInfo, JSON_UNESCAPED_UNICODE);
            if ($updatedLoadingInfo !== false) {
                $updateStmt = $connect->prepare("UPDATE general_loading SET loading_info = ? WHERE bl_no = ?");
                $updateStmt->bind_param("ss", $updatedLoadingInfo, $bl_no);
                if ($updateStmt->execute()) {
                    message('success', "?view=1&t_id=$t_id", 'BL closed successfully!');
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
        message('error', "?view=1&t_id=$t_id", 'BL not found.');
    }
    $stmt->close();
}
if (isset($_GET['print'], $_GET['bl_no'])) {
    message('', "print/bl-no-print.php?loading=general&bl_no=" . $_GET['bl_no'], '');
}
if (isset($_GET['t_id'], $_GET['delete'])) {
    $existingD = mysqli_fetch_assoc(fetch('general_loading', ['t_id' => $_GET['t_id']]));
    if ($existingD) {
        $goods = json_decode($existingD['goods_info'], true);
        if (isset($goods[$_GET['delete']])) {
            unset($goods[$_GET['delete']]);
        }
        $goods = json_encode($goods);
        $done = update('general_loading', ['goods_info' => $goods], ['t_id' => $_GET['t_id']]);
        if ($done) {
            message('success', '?view=1&t_id=' . $_GET['t_id'], 'Delete Successful!');
        }
    }
}

?>