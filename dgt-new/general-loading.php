<?php
$page_title = 'G. Loading';
$pageURL = 'general-loading';
include("header.php");
$remove = $goods_name = $start_print = $end_print = $type = $acc_no = $p_sr = $sea_road = $is_transferred = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `transactions` WHERE type IN ('booking','commission')";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('general-loading');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_sr;
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
    }
    if (isset($_GET['acc_name']) && !empty($_GET['acc_name'])) {
        $acc_name = mysqli_real_escape_string($connect, $_GET['acc_name']);
        $print_filters[] = 'acc_name=' . $acc_name;
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
$sql .= " AND locked IN ('1', '2') AND (CASE WHEN type='commission' THEN transfer_level >= '1' ELSE transfer_level >= '2' END) ORDER BY id DESC LIMIT $start_from, $results_per_page";
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

                    $count_sql = "SELECT COUNT(id) AS total FROM `transactions` WHERE p_s='p'";
                    if (count($conditions) > 0) {
                        $count_sql .= ' AND ' . implode(' AND ', $conditions);
                    }
                    $count_sql .= " AND locked = '1' AND transfer_level >= '2' ORDER BY id DESC";
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
                    <th>P/S#</th>
                    <th>Type</th>
                    <th>BR.</th>
                    <th>Date</th>
                    <th>A/c</th>
                    <th>A/c Name</th>
                    <th>Goods Name</th>
                    <th>Qty</th>
                    <th>KGs</th>
                    <th>SEA/ROAD</th>
                    <th>L. DATE</th>
                    <th>R. DATE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $generalLoadingQuery = "SELECT * FROM general_loading";
                $generalLoadingResult = mysqli_query($connect, $generalLoadingQuery);
                $generalLoadingData = [];
                while ($loading = mysqli_fetch_assoc($generalLoadingResult)) {
                    $p_id = $loading['p_id'];
                    $p_sr = $loading['p_sr'];
                    if (!isset($generalLoadingData[$p_id])) {
                        $generalLoadingData[$p_id] = [];
                    }
                    $generalLoadingData[$p_id][] = $loading;
                }

                // Process each purchase record
                $purchases = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $i = 1;

                while ($purchase = mysqli_fetch_assoc($purchases)) {
                    $id = $purchase['id'];
                    $p_sr = $purchase['sr'];
                    $_fields_single = transactionSingle($id);
                    $is_doc = $purchase['is_doc'];
                    $locked = $purchase['locked'];
                    $payments = json_decode($purchase['payments'], true);
                    $cntrs = purchaseSpecificData($id, 'purchase_rows');
                    $totals = purchaseSpecificData($id, 'product_details');
                    $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);
                    $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                    $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];

                    // Sea/Road information
                    $sea_road = '';
                    $sea_road_array = json_decode(getSeaRoadArray($id));
                    $_fields_sr = ['l_country' => '', 'l_date' => '', 'r_country' => '', 'r_date' => ''];
                    if (!empty($sea_road_array)) {
                        $sea_road = $sea_road_array->sea_road ?? '';
                        $_fields_sr = $sea_road == 'sea' ? [
                            'l_country' => $sea_road_array->l_country,
                            'l_date' => $sea_road_array->l_date,
                            'r_country' => $sea_road_array->r_country,
                            'r_date' => $sea_road_array->r_date
                        ] : [
                            'l_country' => $sea_road_array->l_country_road,
                            'l_date' => $sea_road_array->l_date_road,
                            'r_country' => $sea_road_array->r_country_road,
                            'r_date' => $sea_road_array->r_date_road
                        ];
                    }
                    $sr1_totals = $all_totals = ['quantity' => 0];
                    $matches_found = false;

                    if (isset($generalLoadingData[$id])) {
                        $matches_found = true;
                        foreach ($generalLoadingData[$id] as $loading) {
                            if ($loading['sr_no'] == 1) {
                                $sr1_info = json_decode($loading['gloading_info'], true);
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
                            <?php echo '<b>' . ucfirst($_fields_single['p_s']) . '#</b>' . $p_sr; ?>
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
                            <td class="<?php echo $rowColor; ?>"><?php echo $sea_road; ?></td>
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
                url: 'ajax/viewGeneralLoading.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "general-loading",
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
/* do seprate parent search for active bl_no */
if (isset($_POST['GLoadingSubmit'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    // General Details
    $sr_no = mysqli_real_escape_string($connect, $_POST['sr_no']);
    $p_id = mysqli_real_escape_string($connect, $_POST['p_id']);
    $p_sr = mysqli_real_escape_string($connect, $_POST['p_sr']);
    $p_type = mysqli_real_escape_string($connect, $_POST['p_type']);
    $p_branch = mysqli_real_escape_string($connect, $_POST['p_branch']);
    $p_date = mysqli_real_escape_string($connect, $_POST['p_date']);
    $p_cr_acc = mysqli_real_escape_string($connect, $_POST['p_cr_acc']);
    $p_cr_acc_name = mysqli_real_escape_string($connect, $_POST['p_cr_acc_name']);
    $bl_no = mysqli_real_escape_string($connect, $_POST['bl_no']);
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

    $ActiveblQ = fetch('general_loading', ['p_id' => $p_id, 'sr_no' => '1']);
    if (mysqli_num_rows($ActiveblQ) > 0) {
        $active_bl = mysqli_fetch_assoc($ActiveblQ);
        $active_gLoading = json_decode($active_bl['gloading_info'], true);
        $trans_bl = array_merge((isset($active_gLoading['transferred_bl']) ? $active_gLoading['transferred_bl'] : []), [$bl_no]);
        $active_gLoading = array_merge($active_gLoading, ['active_bl_no' => $bl_no, 'transferred_bl' => $trans_bl]);
        update('general_loading', ['gloading_info' => json_encode($active_gLoading)], ['id' => $active_bl['id']]);
    }

    $autoIncrementResult = mysqli_fetch_assoc(mysqli_query($connect, "SHOW TABLE STATUS LIKE 'general_loading'"));
    $nextId = $autoIncrementResult['Auto_increment'];
    $parent_bl_data = mysqli_fetch_assoc(mysqli_query($connect, "
    SELECT id, gloading_info, 
           LENGTH(JSON_EXTRACT(gloading_info, '$.child_ids')) 
           - LENGTH(REPLACE(JSON_EXTRACT(gloading_info, '$.child_ids'), ',', '')) + 1 AS child_ids_count
    FROM general_loading 
    WHERE bl_no = '$bl_no' 
    AND JSON_EXTRACT(gloading_info, '$.child_ids') IS NOT NULL
"));
    if ($parent_bl_data && (isset($_POST['action']) ? ($parent_bl_data['id'] !== $_POST['id']) : true)) {
        $data = json_decode($parent_bl_data['gloading_info'], true);
        $existing_child_ids = isset($data['child_ids']) ? $data['child_ids'] : '';
        $existing_child_ids_count = isset($data['child_ids_count']) ? $data['child_ids_count'] : 0;
        if ($_POST['action'] === 'update') {
            $child_ids = $existing_child_ids;
            $child_ids_count = $existing_child_ids_count;
        } else {
            $child_ids = $existing_child_ids . (!empty($existing_child_ids) ? ", " : "") . $nextId;
            $child_ids_count = $existing_child_ids === '' ? 1 : substr_count($child_ids, ',') + 1;
        }
        $my = [
            'parent_id' => $parent_bl_data['id'],
            'bl_entry_no' => $child_ids_count + 1
        ];
        $data = array_merge($data, [
            'child_ids' => $child_ids,
            'child_ids_count' => $child_ids_count
        ]);
        $updateData = ['gloading_info' => json_encode($data)];
        update('general_loading', $updateData, ['id' => $parent_bl_data['id']]);
    } else {
        $my = [
            'child_ids' => '',
            'child_ids_count' => 0,
            'bl_entry_no' => 1,
        ];
    }



    if ($sr_no == 1) {
        $my = array_merge($my, [
            'total_quanity_no' => $_POST['total_quantity_no'],
            'total_gross_weight' => $_POST['total_gross_weight'],
            'total_net_weight' => $_POST['total_net_weight'],
            'active_bl_no' => $bl_no
        ]);
    }
    $loading_details = [
        'loading_date' => mysqli_real_escape_string($connect, $_POST['loading_date']),
        'loading_country' => mysqli_real_escape_string($connect, $_POST['loading_country']),
        'loading_port_name' => mysqli_real_escape_string($connect, $_POST['loading_port_name']),
    ];
    $receiving_details = [
        'receiving_date' => mysqli_real_escape_string($connect, $_POST['receiving_date']),
        'receiving_country' => mysqli_real_escape_string($connect, $_POST['receiving_country']),
        'receiving_port_name' => mysqli_real_escape_string($connect, $_POST['receiving_port_name']),
    ];
    $importer_details = [
        'im_acc_id' => mysqli_real_escape_string($connect, $_POST['im_acc_id']),
        'im_acc_no' => mysqli_real_escape_string($connect, $_POST['im_acc_no']),
        'im_acc_name' => mysqli_real_escape_string($connect, $_POST['im_acc_name']),
        'im_acc_kd_id' => mysqli_real_escape_string($connect, $_POST['im_acc_kd_id']),
        'im_acc_details' => mysqli_real_escape_string($connect, $_POST['im_acc_details'])
    ];
    $notify_party_details = [
        'np_acc_id' => mysqli_real_escape_string($connect, $_POST['np_acc_id']),
        'np_acc_no' => mysqli_real_escape_string($connect, $_POST['np_acc_no']),
        'np_acc_name' => mysqli_real_escape_string($connect, $_POST['np_acc_name']),
        'np_acc_kd_id' => mysqli_real_escape_string($connect, $_POST['np_acc_kd_id']),
        'np_acc_details' => mysqli_real_escape_string($connect, $_POST['np_acc_details'])
    ];
    $exporter_details = [
        'xp_acc_id' => mysqli_real_escape_string($connect, $_POST['xp_acc_id']),
        'xp_acc_no' => mysqli_real_escape_string($connect, $_POST['xp_acc_no']),
        'xp_acc_name' => mysqli_real_escape_string($connect, $_POST['xp_acc_name']),
        'xp_acc_kd_id' => mysqli_real_escape_string($connect, $_POST['xp_acc_kd_id']),
        'xp_acc_details' => mysqli_real_escape_string($connect, $_POST['xp_acc_details'])
    ];
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
        'container_no' => mysqli_real_escape_string($connect, $_POST['container_no']),
        'container_name' => mysqli_real_escape_string($connect, $_POST['container_name']),
        'goods_json' => json_decode($_POST['goods_json'], true)
    ];
    $goods_details = calcNewValues([$_POST['quantity_no'], $_POST['quantity_no']], $goods_details, 'both');

    $shipping_details = [
        'shipping_name' => mysqli_real_escape_string($connect, $_POST['shipping_name']),
        'shipping_phone' => mysqli_real_escape_string($connect, $_POST['shipping_phone']),
        'shipping_whatsapp' => mysqli_real_escape_string($connect, $_POST['shipping_whatsapp']),
        'shipping_email' => mysqli_real_escape_string($connect, $_POST['shipping_email']),
        'shipping_address' => mysqli_real_escape_string($connect, $_POST['shipping_address']),
        'transfer_by' => mysqli_real_escape_string($connect, $_POST['transfer_by'])
    ];
    $data = [
        'sr_no' => $sr_no,
        'p_id' => $p_id,
        'p_sr' => $p_sr,
        'type' => $_POST['type'],
        'p_type' => $p_type,
        'p_branch' => $p_branch,
        'p_date' => $p_date,
        'p_cr_acc' => $p_cr_acc,
        'p_cr_acc_name' => $p_cr_acc_name,
        'loading_details' => json_encode($loading_details),
        'receiving_details' => json_encode($receiving_details),
        'bl_no' => $bl_no,
        'gloading_info' => json_encode($my),
        'report' => $report,
        'importer_details' => json_encode($importer_details),
        'notify_party_details' => json_encode($notify_party_details),
        'exporter_details' => json_encode($exporter_details),
        'goods_details' => mysqli_real_escape_string($connect, json_encode($goods_details)),
        'shipping_details' => json_encode($shipping_details),
        'attachments' => json_encode($uploadedFiles)
    ];
    if ($_POST['action'] === 'update' && isset($_POST['id'])) {
        $my_unique_code = $_POST['unique_code'];
        $myTable = 'data_copies';
        $Ttempdata = mysqli_fetch_assoc(fetch('transactions', ['id' => $p_id]));
        $tdata = array_merge(
            transactionSingle($p_id),
            ['sea_road_array' => json_decode($Ttempdata['sea_road'], true)] ?? [],
            ['notify_party_details' => json_decode($Ttempdata['notify_party_details'], true)] ?? [],
            ['third_party_bank' => json_decode($Ttempdata['third_party_bank'], true)] ?? [],
            ['reports' => json_decode($Ttempdata['reports'], true)] ?? []
        );
        $transferData = array_merge(
            json_decode($data['loading_details'], true),
            json_decode($data['receiving_details'], true),
            json_decode($data['shipping_details'], true)
        );
        $tempG = $goods_details;
        $TempData = $data;
        unset(
            $TempData['loading_details'],
            $TempData['receiving_details'],
            $TempData['shipping_details'],
            $TempData['agent_details'],
            $TempData['gloading_info'],
            $TempData['importer_details'],
            $TempData['exporter_details'],
            $TempData['notify_party_details'],
            $TempData['goods_details']
        );
        $ldata = array_merge(
            $TempData,
            [
                'transfer' => $transferData,
                'info' => json_decode($data['gloading_info'], true),
                'importer' => json_decode($data['importer_details'], true),
                'exporter' => json_decode($data['exporter_details'], true),
                'notify' => json_decode($data['notify_party_details'], true),
                'good' => array_merge(
                    $tempG,
                    [
                        'goods_json' => array_merge(
                            $tempG['goods_json'],
                            [
                                'qty_no' => $tempG['quantity_no'],
                                'qty_name' => $tempG['quantity_name'],
                                'total_kgs' => $tempG['gross_weight'],
                                'net_kgs' => $tempG['net_weight'],
                                'rate1' => $tempG['rate'],
                                'empty_kgs' => $tempG['empty_kgs'],
                                'size' => $tempG['size'],
                                'brand' => $tempG['brand'],
                                'origin' => $tempG['origin'],
                                'goods_id' => $tempG['goods_id'],
                            ]
                        )
                    ]
                ),
            ]
        );
        $CCWdata = [
            'unique_code' => $my_unique_code,
            'tdata' => mysqli_real_escape_string($connect, json_encode($tdata)),
            'ldata' => $ldata,
        ];
        if (recordExists($myTable, ['unique_code' => $CCWdata['unique_code']])) {
            $fetchedLdata = json_decode(mysqli_fetch_assoc(fetch($myTable, ['unique_code' => $CCWdata['unique_code']]))['ldata'], true);
            if (!isset($fetchedLdata['edited']) && $fetchedLdata['edited'] !== true) {
                $sold_to_from_key = isset($fetchedLdata['transfer']['sold_to']) ? 'sold_to' : (isset($fetchedLdata['transfer']['sold_from']) ? 'sold_from' : '');
                if ($fetchedLdata['ldata']['good']['quantity_no'] !== $fetchedLdata['ldata']['good']['goods_json']['qty_no']) {
                    $CCWdata['ldata']['good']['goods_json'] = $fetchedLdata['good']['goods_json'];
                }
                $CCWdata['ldata']['transfer']['warehouse_transfer'] = $fetchedLdata['transfer']['warehouse_transfer'];
                $CCWdata['ldata']['agent'] = $fetchedLdata['agent'];
                if (!empty($sold_to_from_key)) {
                    $CCWdata['ldata']['transfer'][$sold_to_from_key] = $fetchedLdata['transfer'][$sold_to_from_key];
                }
                $CCWdata['ldata'] = mysqli_real_escape_string($connect, json_encode($CCWdata['ldata']));
                update($myTable, $CCWdata, ['unique_code' => $CCWdata['unique_code']]);
            }
        }

        $url_ = "general-loading?p_id=" . $p_id . "&view=1";
        if ($data['attachments'] == '[]') {
            unset($data['attachments']);
        }
        if ($parent_bl_data['id'] == $_POST['id']) {
            unset($data['gloading_info']);
        }
        $done = update('general_loading', $data, array('id' => $_POST['id']));
        if ($done) {
            $type = 'success';
            $msg = 'Entry Updated!';
        }
    } else {
        $url_ = "general-loading?p_id=" . $p_id . "&view=1";
        $done = insert('general_loading', $data);
        if ($done) {
            $type = 'success';
            $msg = 'New Goods Loading Added!';
        }
    }
    messageNew($type, $url_, $msg);
}

if (isset($_GET['deleteLoadingEntry']) && isset($_GET['lp_id']) && !empty($_GET['lp_id'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $childEntryId = mysqli_real_escape_string($connect, $_GET['lp_id']);
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    $unique_code = mysqli_real_escape_string($connect, $_GET['unique_code']);
    $url_ = "general-loading?view=1&p_id=" . $p_id;

    // Step 1: Get the parent ID from the record being deleted
    $parentIdQuery = "SELECT JSON_EXTRACT(gloading_info, '$.parent_id') AS parent_id FROM general_loading WHERE id = '$childEntryId'";
    $parentIdResult = mysqli_fetch_assoc(mysqli_query($connect, $parentIdQuery));
    $parentId = trim($parentIdResult['parent_id'], '"'); // Remove extra quotes

    // Check if parent ID is valid
    if (empty($parentId)) {
        $msg = "No parent ID found for the entry.";
        message('danger', $url_, $msg);
        exit;
    }

    // Step 2: Fetch parent record's gloading_info
    $parentBlDataQuery = "SELECT id, gloading_info FROM general_loading WHERE id = '$parentId'";
    $parentBlData = mysqli_fetch_assoc(mysqli_query($connect, $parentBlDataQuery));

    // Check if the parent record exists
    if (!$parentBlData) {
        $msg = "Parent record not found.";
        message('danger', $url_, $msg);
        exit;
    }

    // Step 3: Update child_ids and child_ids_count
    $data = json_decode($parentBlData['gloading_info'], true);
    if (isset($data['child_ids']) && isset($data['child_ids_count'])) {
        // Remove the current ID from child_ids
        $childIdsArray = explode(', ', $data['child_ids']);
        $childIdsArray = array_filter($childIdsArray, function ($childId) use ($childEntryId) {
            return $childId != $childEntryId;
        });

        // Update child_ids and child_ids_count
        $data['child_ids'] = implode(', ', $childIdsArray);
        $data['child_ids_count'] = count($childIdsArray);

        // Step 4: Update the gloading_info in the database
        $updateData = ['gloading_info' => json_encode($data)];
        update('general_loading', $updateData, ['id' => $parentBlData['id']]);
    }

    // Step 5: Delete the current record
    $deleteQuery = "DELETE FROM `general_loading` WHERE id='$childEntryId'";
    $done = mysqli_query($connect, $deleteQuery);
    $done1 = "DELETE FROM `data_copies` WHERE unique_code='$unique_code'";
    $done2 = "DELETE FROM `vat_copies` WHERE unique_code='$unique_code'";
    $done1_result = mysqli_query($connect, $done1);
    $done2_result = mysqli_query($connect, $done2);
    if ($done1_result || $done2_result) {
        $Mydone = true;
    } else {
        $Mydone = false;
    }
    if ($done && $Mydone) {
        $msg = "Loading Entry Deleted!";
        $type = "success";
    } else {
        $msg = "Failed to delete loading entry.";
    }

    // Redirect or display message
    // message($type, $url_, $msg);
}


if (isset($_GET['updateActiveBl'])) {
    $parent_id = mysqli_real_escape_string($connect, $_GET['parent_id']);
    $gloading = json_decode(mysqli_fetch_assoc(fetch('general_loading', ['id' => $parent_id]))['gloading_info'], true);
    $gloading['active_bl_no'] = '';
    $done = update('general_loading', ['gloading_info' => json_encode($gloading)], ['id' => $parent_id]);
    $done ? messageNew('success', $pageURL . "?view=1&p_id=" . $_GET['p_id'], "BL Closed/Transferred") :  messageNew('danger', $pageURL . "?view=1&p_id=" . $p_id, "ERROR! While Closing BL Number");
}

if (isset($_GET['p_id']) && is_numeric($_GET['p_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($p_id); });</script>";
} ?>