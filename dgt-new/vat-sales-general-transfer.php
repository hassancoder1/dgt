<?php
$page_title = 'VAT SALES GENERAL TRANSFER';
$pageURL = 'vat-sales-general-transfer';
include("header.php");
$remove = $start_print = $end_print = $type = $acc_no = $warehouse_search = $p_id = $sea_road = $blNoSearch = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading` WHERE type='s'";
$conditions = [];
$print_filters = [];
$user = $_SESSION['username'];
if ($_GET) {
    $remove = removeFilter($pageURL);
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_id;
        $conditions[] = "p_id = '$p_id'";
    }
    $date_type = isset($_GET['date_type']) ? $_GET['date_type'] : '';
    $print_filters[] = 'date_type=' . $date_type;
    if (!empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        if ($date_type == 'loading') {
            $conditions[] = "JSON_EXTRACT(loading_details, '$.loading_date') >= '$start_print'";
        } elseif ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(receiving_details, '$.receiving_date') >= '$start_print'";
        }
    }
    if (!empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        if ($date_type == 'loading') {
            $conditions[] = "JSON_EXTRACT(loading_details, '$.loading_date') <= '$end_print'";
        } elseif ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(receiving_details, '$.receiving_date') <= '$end_print'";
        }
    }
    if (!empty($_GET['p_type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['p_type']);
        $print_filters[] = 'p_type=' . $type;
        $conditions[] = "p_type = '$type'";
    }
    if (isset($_GET['blNoSearch']) && $_GET['blNoSearch'] !== '') {
        $blNoSearch = mysqli_real_escape_string($connect, $_GET['blNoSearch']);
        $print_filters[] = 'blNoSearch=' . $blNoSearch;
        $conditions[] = "bl_no='$blNoSearch'";
    }
    if (!empty($_GET['acc_no']) && $user === 'admin') {
        $acc_no = mysqli_real_escape_string($connect, $_GET['acc_no']);
        $print_filters[] = 'acc_no=' . $acc_no;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.ag_acc_no') = '$acc_no'";
    }
    if (!empty($_GET['warehouse_search']) && $user === 'admin') {
        $warehouse_search = mysqli_real_escape_string($connect, $_GET['warehouse_search']);
        $print_filters[] = 'warehouse_search=' . $warehouse_search;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.cargo_transfer_warehouse') = '$warehouse_search'";
    }
    if (!empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(shipping_details, '$.transfer_by') = '$sea_road'";
    }
}
if (count($conditions) > 0) {
    $sql .= " AND " . implode(' AND ', $conditions);
}
if ($user !== 'admin') {
    $sql .= " AND JSON_EXTRACT(agent_details, '$.ag_id') = '$user'";
}
$sql .= " ORDER BY id DESC LIMIT $start_from, $results_per_page";
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
            <?= $page_title; ?>
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

                    $count_sql = "SELECT COUNT(id) AS total FROM `general_loading`";
                    if (count($conditions) > 0) {
                        $count_sql .= ' WHERE ' . implode(' AND ', $conditions);
                    }
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
                <label for="p_id" class="form-label">S#</label>
                <input type="number" name="p_id" value="<?php echo $p_id; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
            </div>
            <div class="form-group">
                <label for="date_type" class="form-label">Date Type</label>
                <select class="form-select form-select-sm" name="date_type" style="max-width:130px;" id="date_type" onchange="toggleDates()">
                    <option value="" <?= !in_array($date_type, ['loading', 'receiving']) ? 'selected' : ''; ?>>All</option>
                    <option value="loading" <?= $date_type == 'loading' ? 'selected' : ''; ?>>Loading</option>
                    <option value="receiving" <?= $date_type == 'receiving' ? 'selected' : ''; ?>>Receiving</option>
                </select>
            </div>
            <div class="form-group <?= !in_array($date_type, ['loading', 'receiving']) ? 'd-none' : ''; ?>" id="startInput">
                <label for="start" class="form-label">Start Date</label>
                <input type="date" name="start" value="<?php echo $start_print; ?>" id="start" class="form-control form-control-sm mx-1" style="max-width:160px;">
            </div>
            <div class="form-group <?= !in_array($date_type, ['loading', 'receiving']) ? 'd-none' : ''; ?>" id="endInput">
                <label for="end" class="form-label">End Date</label>
                <input type="date" name="end" value="<?php echo $end_print; ?>" id="end" class="form-control form-control-sm mx-2" style="max-width:160px;">
            </div>
            <div class="form-group">
                <label for="p_type" class="form-label">Type</label>
                <select class="form-select form-select-sm" name="p_type" style="max-width:130px;" id="p_type">
                    <option value="" selected>All</option>
                    <?php
                    $textQ = mysqli_query($connect, "SELECT * FROM static_types WHERE type_for IN ('ps_types', 's_types')");
                    while ($test = mysqli_fetch_assoc($textQ)) {
                        $sel_tran = $type == $test['type_name'] ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $test['type_name'] . '">' . strtoupper(htmlspecialchars($test['details'])) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sea_road" class="form-label">SEA/ROAD</label>
                <select class="form-select form-select-sm" name="sea_road" style="max-width:130px;" id="sea_road">
                    <option value="" <?= !in_array($sea_road, ['sea', 'road']) ? 'selected' : ''; ?>>All</option>
                    <option value="sea" <?= $sea_road == 'sea' ? 'selected' : ''; ?>>by Sea</option>
                    <option value="road" <?= $sea_road == 'road' ? 'selected' : ''; ?>>by Road</option>
                </select>
            </div>
            <div class="form-group mx-1">
                <label for="blNoSearch" class="form-label">B/L No</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:90px;" name="blNoSearch" placeholder="B/L No" value="<?php echo $blNoSearch; ?>" id="blNoSearch">
            </div>
            <div class="form-group <?= $user !== 'admin' ? 'd-none' : ''; ?>">
                <label for="acc_no" class="form-label">Acc No.</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:90px;" name="acc_no" placeholder="Acc No." value="<?php echo $acc_no; ?>" id="acc_no">
            </div>
            <div class="form-group mx-1">
                <label for="warehouse_search" class="form-label">WareHouse</label>
                <select id="warehouse_search" name="warehouse_search" class="form-select form-select-sm" required>
                    <option disabled selected>Select One</option>
                    <option value="Free Zone" <?= $warehouse_search === 'Free Zone' ? 'selected' : ''; ?>>Freezone Warehouse</option>
                    <option value="OFF Site" <?= $warehouse_search === 'OFF Site' ? 'selected' : ''; ?>>Offsite Warehouse</option>
                    <option value="Transit" <?= $warehouse_search === 'Transit' ? 'selected' : ''; ?>>Transit Warehouse</option>
                </select>
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
                    <th><?= SuperAdmin() ? 'S#+Bill#' : '#'; ?></th>
                    <th>B/L No.</th>
                    <th>Containers</th>
                    <th>AG ID</th>
                    <th>AG NAME</th>
                    <th>WareHouse</th>
                    <th>L_DATE</th>
                    <th>L_PORT</th>
                    <th>R_DATE</th>
                    <th>R_PORT</th>
                    <th>Total QTY</th>
                    <th>T.G.KGS</th>
                    <th>T.N.KGS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $rowColor = '';
                $locked = 0;
                $containerCounts = $Loadings = $netWeights = $grossWeights = $quantityNos = [];
                while ($one = mysqli_fetch_assoc($result)) {
                    $gloadingInfo = json_decode($one['gloading_info'], true);
                    if (isset($gloadingInfo['child_ids']) && $gloadingInfo['child_ids'] !== null) {
                        $Loadings[] = $one;
                    }
                    $blNo = $one['bl_no'];
                    if (!isset($containerCounts[$blNo])) {
                        $containerCounts[$blNo] = 1;
                        $netWeights[$blNo] = 0;
                        $grossWeights[$blNo] = 0;
                        $quantityNos[$blNo] = 0;
                    } else {
                        $containerCounts[$blNo]++;
                    }
                    $goodsDetails = json_decode($one['goods_details'], true);
                    $netWeights[$blNo] += $goodsDetails['net_weight'];
                    $grossWeights[$blNo] += $goodsDetails['gross_weight'];
                    $quantityNos[$blNo] += $goodsDetails['quantity_no'];
                }
                foreach ($Loadings as $SingleLoading) {
                    $id = $SingleLoading['id'];
                    $billNumber = json_decode($SingleLoading['gloading_info'], true)['billNumber'] ?? '';
                    $agentDetails = json_decode($SingleLoading['agent_details'], true);
                    if (!empty($agentDetails) && isset($agentDetails['transferred']) && $agentDetails['transferred'] === true) {
                        if (isset($agentDetails['bill_of_entry_no'])) {
                            $rowColor = 'text-dark';
                            $locked = 1;
                        } else {
                            $rowColor = 'text-danger';
                        }
                ?>

                        <tr class="text-nowrap">
                            <?php if (SuperAdmin()) { ?>
                                <td class="pointer text-uppercase <?php echo $rowColor; ?>" onclick="window.location.href= '?lp_id=<?= $SingleLoading['id']; ?>&view=1';">
                                    <?= '<b>S#' . $SingleLoading['p_id'] . "($billNumber)"; ?>
                                    <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                                </td>
                            <?php } else { ?>
                                <td class="pointer <?php echo $rowColor; ?>" onclick="window.location.href= '?lp_id=<?= $SingleLoading['id']; ?>&view=1';">
                                    <b>#<?= $billNumber; ?></b>
                                    <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                                </td>
                            <?php } ?>
                            <td class="<?php echo $rowColor; ?>"><?= $SingleLoading['bl_no']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $containerCounts[$SingleLoading['bl_no']]; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $agentDetails['ag_id']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $agentDetails['ag_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $agentDetails['cargo_transfer_warehouse']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_date']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_port_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_date']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_port_name']; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $quantityNos[$SingleLoading['bl_no']]; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $grossWeights[$SingleLoading['bl_no']]; ?></td>
                            <td class="<?php echo $rowColor; ?>"><?= $netWeights[$SingleLoading['bl_no']]; ?></td>
                        </tr>
                <?php
                        $row_count++;
                    }
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
            let action = '<?= isset($_GET['action']) ? $_GET['action'] : '' ?>';
            let editId = '<?= isset($_GET['editId']) ? $_GET['editId'] : '' ?>';
            $.ajax({
                url: 'ajax/viewVATTransfer.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "agent-form",
                    action: action,
                    editId: editId
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
<script>
    function toggleDates() {
        const selectedValue = $('#date_type').val();
        if (selectedValue === "") {
            $('#startInput, #endInput').addClass('d-none');
        } else {
            $('#startInput, #endInput').removeClass('d-none');
        }
    };
</script>
<?php
if (isset($_GET['lp_id']) && is_numeric($_GET['lp_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $lp_id = mysqli_real_escape_string($connect, $_GET['lp_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($lp_id); });</script>";
}
?>