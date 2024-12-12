<?php
$page_title = 'Local Loading';
$pageURL = 'local-loading';
include("header.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $p_id = $route = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `transactions` WHERE type='local' AND sea_road IS NOT NULL";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('local-loading');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_id;
        $conditions[] = "id = '$p_id'";
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
    }
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $page = mysqli_real_escape_string($connect, $_GET['page']);
        $print_filters[] = 'page=' . $page;
    }
    if (isset($_GET['route']) && !empty($_GET['route'])) {
        $route = mysqli_real_escape_string($connect, $_GET['route']);
        $print_filters[] = 'route=' . $route;
        $conditions[] = "JSON_EXTRACT(sea_road, '$.route') = '$route'";
    }
}
if (count($conditions) > 0) {
    $sql .= ' AND ' . implode(' AND ', $conditions);
}
$sql .= " AND locked = '1' AND transfer_level >= '2' ORDER BY id DESC LIMIT $start_from, $results_per_page";
$purchases = mysqli_query($connect, $sql);
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
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

                    $count_sql = "SELECT COUNT(id) AS total FROM `transactions` WHERE ";
                    if (count($conditions) > 0) {
                        $count_sql .= '' . implode(' AND ', $conditions);
                        $count_sql .= ' AND ';
                    }
                    $count_sql .= "locked = '1' AND transfer_level >= '2' ORDER BY id DESC";
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
                <input type="number" name="p_id" value="<?php echo $p_id; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
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

    <!-- <style>
        #RecordsTable {
            height: 300px;
            overflow-y: scroll;
        }

        .fixed thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #fff;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
    </style> -->
    <div class="table-responsive mt-4" id="RecordsTable">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>Bill#</th>
                    <th>Type</th>
                    <th>BR.</th>
                    <th>Date</th>
                    <th>A/c</th>
                    <th>A/c Name</th>
                    <th>Goods Name</th>
                    <th>Qty</th>
                    <th>KGs</th>
                    <th>Route</th>
                    <th>L. DATE</th>
                    <th>R. DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $LocalLoadingQuery = "SELECT * FROM local_loading";
                $LocalLoadingResult = mysqli_query($connect, $LocalLoadingQuery);
                $LocalLoadingData = [];
                while ($loading = mysqli_fetch_assoc($LocalLoadingResult)) {
                    $p_id = $loading['p_id'];
                    if (!isset($LocalLoadingData[$p_id])) {
                        $LocalLoadingData[$p_id] = [];
                    }
                    $LocalLoadingData[$p_id][] = $loading;
                }

                // Process each purchase record
                $purchases = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $i = 1;

                while ($purchase = mysqli_fetch_assoc($purchases)) {
                    $id = $purchase['id'];
                    $_fields_single = transactionSingle($id);
                    $is_doc = $purchase['is_doc'];
                    $locked = $purchase['locked'];
                    $payments = json_decode($purchase['payments'], true);
                    $cntrs = purchaseSpecificData($id, 'purchase_rows');
                    $totals = purchaseSpecificData($id, 'product_details');
                    $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);
                    $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                    $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];

                    $sea_road = '';
                    $sea_road_array = json_decode(getSeaRoadArray($id));
                    $_fields_sr = ['l_country' => '', 'l_date' => '', 'r_country' => '', 'r_date' => ''];
                    if (!empty($sea_road_array)) {
                        $sea_road = $sea_road_array->route ?? '';
                        $_fields_sr = [];
                        if ($sea_road === 'local') {
                            $_fields_sr = [
                                'uid' => $sea_road_array->l_country ?? '',
                                'l_country' => $sea_road_array->l_country ?? '',
                                'l_date'    => $sea_road_array->l_date ?? '',
                                'r_country' => $sea_road_array->r_country ?? '',
                                'r_date'    => $sea_road_array->r_date ?? '',
                                'truck_no' => $sea_road_array->truck_no ?? '',
                                'truck_name' => $sea_road_array->truck_name ?? '',
                                'loading_company_name' => $sea_road_array->loading_company_name ?? '',
                                'receiving_company_name' => $sea_road_array->receiving_company_name ?? '',
                                'loading_date' => $sea_road_array->loading_date ?? '',
                                'receiving_date' => $sea_road_array->receiving_date ?? '',
                                'loading_warehouse' => $sea_road_array->loading_warehouse ?? '',
                                'receiving_warehouse' => $sea_road_array->receiving_warehouse ?? '',
                                'warehouse_transfer' => $sea_road_array->warehouse_transfer ?? ''
                            ];
                        } elseif ($sea_road === 'warehouse') {
                            $_fields_sr = [
                                'uid' => $sea_road_array->l_country ?? '',
                                'l_country' => $sea_road_array->l_country_road ?? '',
                                'l_date'    => $sea_road_array->l_date_road ?? '',
                                'r_country' => $sea_road_array->r_country_road ?? '',
                                'r_date'    => $sea_road_array->r_date_road ?? '',
                                'loading_company_name' => $sea_road_array->loading_company_name ?? '',
                                'receiving_company_name' => $sea_road_array->receiving_company_name ?? '',
                                'loading_date' => $sea_road_array->loading_date ?? '',
                                'receiving_date' => $sea_road_array->receiving_date ?? '',
                                'warehouse_transfer' => $sea_road_array->warehouse_transfer ?? ''
                            ];
                        }
                    }
                    $sr1_totals = $all_totals = ['quantity' => 0];
                    $matches_found = false;

                    if (isset($LocalLoadingData[$id])) {
                        $matches_found = true;
                        foreach ($LocalLoadingData[$id] as $loading) {
                            if ($loading['sr_no'] == 1) {
                                $sr1_info = json_decode($loading['lloading_info'], true);
                                $sr1_totals['quantity'] = $sr1_info['total_quanity_no'] ?? 0;
                            }
                            $goods_details = json_decode($loading['goods_details'], true);
                            $all_totals['quantity'] += $goods_details['quantity_no'] ?? 0;
                        }
                    }
                    if (!$matches_found) {
                        $rowColor = 'text-danger';
                    } elseif ($sr1_totals['quantity'] > $all_totals['quantity']) {
                        $rowColor = 'text-warning';
                    } elseif ($sr1_totals['quantity'] == $all_totals['quantity']) {
                        $rowColor = 'text-dark';
                    }

                ?>

                    <tr class="text-nowrap">
                        <td class="pointer <?php echo $rowColor; ?>" onclick="window.location.href = '?p_id=<?= $id; ?>&view=1';">
                            <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $id; ?>
                            <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                        </td>
                        <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo branchName($_fields_single['branch_id']); ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']); ?></td>
                        <td class="acc_no <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['cr_acc']); ?></td>
                        <td class="acc_name <?php echo $rowColor; ?>"><?php echo $_fields_single['cr_acc_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                        <?php if ($sea_road == '') { ?>
                            <td class="<?php echo $rowColor; ?>" colspan="3"></td>
                        <?php } else { ?>
                            <td class="<?php echo $rowColor; ?>"><?= $sea_road === 'local' ? 'Loading' : 'Warehouse';; ?></td>
                        <?php
                            echo '<td class="' . $rowColor . '">' .  my_date($_fields_sr['l_date']) . '</td>';
                            echo '<td class="' . $rowColor . '">' . my_date($_fields_sr['r_date']) . '</td>';
                        } ?>
                    </tr>
                <?php
                    $row_count++;
                }
                ?>
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
            let action = '<?= isset($_GET['action']) ? $_GET['action'] : '' ?>'; // Check if action exists
            let lp_id = '<?= isset($_GET['lp_id']) ? $_GET['lp_id'] : '' ?>'; // Check if lp_id exists
            let sr_no = '<?= isset($_GET['sr_no']) ? $_GET['sr_no'] : '' ?>'; // Check if lp_id exists
            $.ajax({
                url: 'ajax/viewLocalLoading.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "local-loading",
                    lp_id: lp_id,
                    action: action,
                    sr_no: sr_no
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
if (isset($_POST['LLoadingSubmit'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    // General Details
    $sr_no = mysqli_real_escape_string($connect, $_POST['sr_no']);
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id']);
    $p_branch = mysqli_real_escape_string($connect, $_POST['p_branch']);
    $p_date = mysqli_real_escape_string($connect, $_POST['p_date']);
    $p_cr_acc = mysqli_real_escape_string($connect, $_POST['p_cr_acc']);
    $p_cr_acc_name = mysqli_real_escape_string($connect, $_POST['p_cr_acc_name']);
    $uid = mysqli_real_escape_string($connect, $_POST['uid']);
    $route = mysqli_real_escape_string($connect, $_POST['route']);
    $report = mysqli_real_escape_string($connect, $_POST['report']);
    $uploadedFiles = [];
    $uploadDir = 'attachments/';
    if (!empty($_FILES['entry_file']['name'][0])) {
        foreach ($_FILES['entry_file']['name'] as $key => $filename) {
            $tmpName = $_FILES['entry_file']['tmp_name'][$key];
            $newFilename = time() . '_' . basename($filename);
            if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                $uploadedFiles[] = [$key, $newFilename];
            }
        }
    } else {
        $uploadedFiles = [];
    }

    $ActiveUIDQ = fetch('local_loading', ['p_id' => $p_id, 'sr_no' => '1']);
    if (mysqli_num_rows($ActiveUIDQ) > 0) {
        $active_UID = mysqli_fetch_assoc($ActiveUIDQ);
        $active_lLoading = json_decode($active_UID['lloading_info'], true);
        $trans_UID = array_merge((isset($active_lLoading['transferred_uid']) ? $active_lLoading['transferred_uid'] : []), [$uid]);
        $active_lLoading = array_merge($active_lLoading, ['active_uid' => $uid, 'transferred_uid' => $trans_UID]);
        update('local_loading', ['lloading_info' => json_encode($active_lLoading)], ['id' => $active_UID['id']]);
    }

    $autoIncrementResult = mysqli_fetch_assoc(mysqli_query($connect, "SHOW TABLE STATUS LIKE 'local_loading'"));
    $nextId = $autoIncrementResult['Auto_increment'];
    $parent_uid_data = mysqli_fetch_assoc(mysqli_query($connect, "
    SELECT id, lloading_info, 
           LENGTH(JSON_EXTRACT(lloading_info, '$.child_ids')) 
           - LENGTH(REPLACE(JSON_EXTRACT(lloading_info, '$.child_ids'), ',', '')) + 1 AS child_ids_count
    FROM local_loading 
    WHERE uid = '$uid' 
    AND JSON_EXTRACT(lloading_info, '$.child_ids') IS NOT NULL
"));
    if ($parent_uid_data && $parent_uid_data['id'] !== $_POST['id']) {
        $data = json_decode($parent_uid_data['lloading_info'], true);
        $existing_child_ids = isset($data['child_ids']) ? $data['child_ids'] : '';
        $existing_child_ids_count = isset($data['child_ids_count']) ? $data['child_ids_count'] : 0;
        if ($_GET['action'] === 'update') {
            $child_ids = $existing_child_ids;
            $child_ids_count = $existing_child_ids_count;
        } else {
            $child_ids = $existing_child_ids . (!empty($existing_child_ids) ? ", " : "") . $nextId;
            $child_ids_count = $existing_child_ids === '' ? 1 : substr_count($child_ids, ',') + 1;
        }
        $my = [
            'parent_id' => $parent_uid_data['id'],
            'uid_entry_no' => $child_ids_count + 1
        ];
        $data = array_merge($data, [
            'child_ids' => $child_ids,
            'child_ids_count' => $child_ids_count
        ]);
        $updateData = ['lloading_info' => json_encode($data)];
        update('local_loading', $updateData, ['id' => $parent_uid_data['id']]);
    } else {
        $my = [
            'child_ids' => '',
            'child_ids_count' => 0,
            'uid_entry_no' => 1,
        ];
    }



    if ($sr_no == 1) {
        $my = array_merge($my, [
            'total_quanity_no' => $_POST['total_quantity_no'],
            'total_gross_weight' => $_POST['total_gross_weight'],
            'total_net_weight' => $_POST['total_net_weight'],
            'active_uid' => $uid
        ]);
    }
    $mustUpdateRoute = !(mysqli_num_rows($ActiveUIDQ) > 0) || isset($_POST['updateRoutes']);

    if ($route === 'local') {
        $transfer_details = [
            'truck_no' => mysqli_real_escape_string($connect, $_POST['truck_no']) ?? '',
            'truck_name' => mysqli_real_escape_string($connect, $_POST['truck_name']) ?? '',
            'loading_warehouse' => mysqli_real_escape_string($connect, $_POST['loading_warehouse']) ?? '',
            'receiving_warehouse' => mysqli_real_escape_string($connect, $_POST['receiving_warehouse']) ?? '',
        ];
    }
    $transfer_details = array_merge(
        $transfer_details,
        [
            'route' => $route,
            'uid' => $uid,
            'loading_company_name' => mysqli_real_escape_string($connect, $_POST['loading_company_name']) ?? '',
            'receiving_company_name' => mysqli_real_escape_string($connect, $_POST['receiving_company_name']) ?? '',
            'loading_date' => mysqli_real_escape_string($connect, $_POST['loading_date']) ?? '',
            'receiving_date' => mysqli_real_escape_string($connect, $_POST['receiving_date']) ?? '',
            'warehouse_transfer' => mysqli_real_escape_string($connect, $_POST['warehouse_transfer']) ?? '',
        ]
    );
    if ($mustUpdateRoute) {
        $existingRouteData = json_decode($_POST['existingRouteData'], true);
        $routeDetails = array_merge($existingRouteData, $transfer_details);
        $data = ['sea_road' => json_encode($routeDetails)];
        update('transactions', $data, ['id' => $p_id]);
    }
    $_POST['goods_json'] = json_decode($_POST['goods_json'], true);
    if (isset($_POST['goods_json']['sr_no'])) {
        unset($_POST['goods_json']['sr_no']);
    }
    $_POST['goods_json'] = json_encode($_POST['goods_json']);
    $goods_details = [
        'goods_id' => mysqli_real_escape_string($connect, $_POST['goods_id']),
        'quantity_no' => mysqli_real_escape_string($connect, $_POST['quantity_no']),
        'rate' => mysqli_real_escape_string($connect, $_POST['rate']),
        'empty_kgs' => mysqli_real_escape_string($connect, $_POST['empty_kgs']),
        'quantity_name' => mysqli_real_escape_string($connect, $_POST['quantity_name']),
        'size' => mysqli_real_escape_string($connect, $_POST['size']),
        'brand' => mysqli_real_escape_string($connect, $_POST['brand']),
        'origin' => mysqli_real_escape_string($connect, $_POST['origin']),
        'net_weight' => mysqli_real_escape_string($connect, $_POST['net_weight']),
        'gross_weight' => mysqli_real_escape_string($connect, $_POST['gross_weight']),
        'goods_json' => mysqli_real_escape_string($connect, $_POST['goods_json'])
    ];

    $data = [
        'sr_no' => $sr_no,
        'p_id' => $p_id,
        'type' => $_POST['type'],
        'p_branch' => $p_branch,
        'p_date' => $p_date,
        'p_cr_acc' => $p_cr_acc,
        'p_cr_acc_name' => $p_cr_acc_name,
        'transfer_details' => json_encode($transfer_details),
        'uid' => $uid,
        'lloading_info' => json_encode($my),
        'report' => $report,
        'goods_details' => json_encode($goods_details),
        'attachments' => json_encode($uploadedFiles)
    ];

    if (isset($_POST['action']) && isset($_POST['id'])) {
        $url_ = "local-loading?p_id=" . $p_id . "&view=1";
        if ($data['attachments'] == '[]') {
            unset($data['attachments']);
        }
        if ($parent_uid_data['id'] == $_POST['id']) {
            unset($data['lloading_info']);
        }
        $done = update('local_loading', $data, array('id' => $_POST['id']));
        if ($done) {
            $type = 'success';
            $msg = 'Entry Updated!';
        }
    } else {
        $url_ = "local-loading?p_id=" . $p_id . "&view=1";
        $done = insert('local_loading', $data);
        if ($done) {
            $type = 'success';
            $msg = 'New Goods Loading Added!';
        }
    }
    // messageNew($type, $url_, $msg);
}

if (isset($_GET['deleteLoadingEntry']) && isset($_GET['lp_id']) && !empty($_GET['lp_id'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $childEntryId = mysqli_real_escape_string($connect, $_GET['lp_id']);
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    $url_ = "local-loading?view=1&p_id=" . $p_id;
    $parentIdQuery = "SELECT JSON_EXTRACT(lloading_info, '$.parent_id') AS parent_id FROM local_loading WHERE id = '$childEntryId'";
    $parentIdResult = mysqli_fetch_assoc(mysqli_query($connect, $parentIdQuery));
    $parentId = trim($parentIdResult['parent_id'], '"');
    if (empty($parentId)) {
        $msg = "No parent ID found for the entry.";
        message('danger', $url_, $msg);
        exit;
    }
    $parentUIDDataQuery = "SELECT id, lloading_info FROM local_loading WHERE id = '$parentId'";
    $parentUIDData = mysqli_fetch_assoc(mysqli_query($connect, $parentUIDDataQuery));
    if (!$parentUIDData) {
        $msg = "Parent record not found.";
        message('danger', $url_, $msg);
        exit;
    }
    $data = json_decode($parentUIDData['lloading_info'], true);
    if (isset($data['child_ids']) && isset($data['child_ids_count'])) {
        $childIdsArray = explode(', ', $data['child_ids']);
        $childIdsArray = array_filter($childIdsArray, function ($childId) use ($childEntryId) {
            return $childId != $childEntryId;
        });
        $data['child_ids'] = implode(', ', $childIdsArray);
        $data['child_ids_count'] = count($childIdsArray);
        $updateData = ['lloading_info' => json_encode($data)];
        update('local_loading', $updateData, ['id' => $parentUIDData['id']]);
        $deleteQuery = "DELETE FROM `local_loading` WHERE id='$childEntryId'";
        $done = mysqli_query($connect, $deleteQuery);
        if ($done) {
            $msg = "Loading Entry Deleted for Purchase #" . $p_id;
            $type = "success";
        } else {
            $msg = "Failed to delete loading entry.";
        }
        message($type, $url_, $msg);
    }
}
if (isset($_GET['updateActiveUIDNo'])) {
    $parent_id = mysqli_real_escape_string($connect, $_GET['parent_id']);
    $lloading = json_decode(mysqli_fetch_assoc(fetch('local_loading', ['id' => $parent_id]))['lloading_info'], true);
    $lloading['active_uid'] = '';
    $done = update('local_loading', ['lloading_info' => json_encode($lloading)], ['id' => $parent_id]);
    $done ? messageNew('success', $pageURL . "?view=1&p_id=" . $_GET['p_id'], "UID Entry Closed/Transferred") :  messageNew('danger', $pageURL . "?view=1&p_id=" . $p_id, "ERROR! While Closing UID Entries");
}

if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id); });</script>";
} ?>