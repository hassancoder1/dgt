<?php
$page_title = 'Agent Form';
$pageURL = 'agent-form';
include("header.php");
$remove = $start_print = $end_print = $type = $acc_no = $p_sr = $sea_road = $blNoSearch = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading`";
$conditions = ["JSON_EXTRACT(loading_info, '$.agent_exist') = 'true'"];
$print_filters = [];
$user = $_SESSION['username'];
if ($_GET) {
    $remove = removeFilter('agent-form');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_sr;
        $conditions[] = "p_sr = '$p_sr'";
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
    if (!empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(shipping_details, '$.transfer_by') = '$sea_road'";
    }
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}
if ($user !== 'admin') {
    $sql .= " AND JSON_SEARCH(agent_info, 'one', '$user', NULL, '$.*.ag_id') IS NOT NULL";
}
$sql .= " ORDER BY id DESC LIMIT $start_from, $results_per_page";
$query_string = implode('&', $print_filters);
$BlNumbers = mysqli_query($connect, $sql);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$sortedEntries = [];
while ($oneBl = mysqli_fetch_assoc($BlNumbers)) {
    $row_color = '';
    $oneBl['loading_info'] = json_decode($oneBl['loading_info'] ?? '[]', true);
    $oneBl['goods_info'] = json_decode($oneBl['goods_info'] ?? '[]', true);
    $oneBl['agent_info'] = json_decode($oneBl['agent_info'] ?? '[]', true);
    $oneBl['warehouse_info'] = json_decode($oneBl['warehouse_info'] ?? '[]', true);
    $oneBl['loading_info']['status'] = $oneBl['loading_info']['status'] ?? '';
    $row_color = empty($oneBl['agent_info']) ? 'text-danger' : 'text-warning';
    $row_color = $oneBl['loading_info']['status'] === 'transferred' ? 'text-dark' : 'text-warning';
    $oneBl['row_color'] = $row_color;
    $oneBl['firstAgent'] = !empty($oneBl['agent_info']) ? reset($oneBl['agent_info']) : [];
    $sortedEntries[] = $oneBl;
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
            Custom Clearing Agent Form
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
                <label for="p_id" class="form-label">P/S#</label>
                <input type="number" name="p_id" value="<?php echo $p_sr; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
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
                    <th><?= SuperAdmin() ? 'P/S#+Bill#' : '#'; ?></th>
                    <th>B/L No.</th>
                    <th>Containers</th>
                    <th>AG ID</th>
                    <th>AG NAME</th>
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
                foreach ($sortedEntries as $entry) {
                ?>
                    <tr class="text-nowrap <?= $entry['row_color']; ?>">
                        <?php if (SuperAdmin()) { ?>
                            <td class="pointer text-uppercase <?= $entry['row_color']; ?>" onclick="window.location.href= '?view=1&bl_id=<?= $entry['id']; ?>';">
                                <?= '<b>' . ucfirst($entry['p_s']) . '#' . $entry['t_sr'] . " (" . $entry['firstAgent']['bill_number'] . ")"; ?>
                            </td>
                        <?php } else { ?>
                            <td class="pointer <?= $entry['row_color']; ?>" onclick="window.location.href= '?view=1&bl_id=<?= $entry['id']; ?>';">
                                <b>#<?= $entry['firstAgent']['bill_number']; ?></b>
                                <?= $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                            </td>
                        <?php } ?>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['bl_no']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= count($entry['goods_info']); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['ag_id'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['ag_name'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['loading']['loading_date']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['loading']['loading_port_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['receiving']['receiving_date']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['loading_info']['receiving']['receiving_port_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= array_sum(array_column($entry['goods_info'], 'quantity_no')); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= array_sum(array_column($entry['goods_info'], 'gross_weight')); ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= array_sum(array_column($entry['goods_info'], 'net_weight')); ?></td>
                    </tr>
                <?php
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
            let edit = '<?= isset($_GET['edit']) ? $_GET['edit'] : '' ?>'; // Check if action exists
            $.ajax({
                url: 'ajax/viewAgentForm.php',
                type: 'post',
                data: {
                    id: id,
                    page: "agent-form",
                    edit: edit
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
if (isset($_POST['TransferToPayments'])) {
    $bl_id = mysqli_real_escape_string($connect, $_POST['bl_id']);
    $Transfer = explode('~', mysqli_real_escape_string($connect, $_POST['payment_transfer_ids']));
    $toTransfer = [];
    for ($i = 0; $i < count($Transfer); $i += 2) {
        if (isset($Transfer[$i + 1])) {
            $toTransfer[] = $Transfer[$i] . '~' . $Transfer[$i + 1];
        }
    }
    $existingData = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));
    $existingData['loading_info'] = json_decode($existingData['loading_info'], true);
    $existingData['agent_info'] = json_decode($existingData['agent_info'], true);
    if (count($existingData['agent_info']) === count($toTransfer)) {
        $existingData['loading_info']['status'] = 'transferred';
        $existingData['loading_info']['transferred_to_payments'] = $toTransfer;
    }
    $existingData['loading_info'] = json_encode($existingData['loading_info']);
    $existingData['agent_info'] = json_encode($existingData['agent_info']);
    $done = update('general_loading', $existingData, ['id' => $bl_id]);
    if ($done) {
        message('danger', '', 'Updated!');
    }
}
if (isset($_POST['AgentFormSubmit'])) {
    $bl_id = mysqli_real_escape_string($connect, $_POST['bl_id']);
    $item = mysqli_real_escape_string($connect, $_POST['item']);
    $existingData = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));
    $existingData['agent_info'] = json_decode($existingData['agent_info'], true);
    $attachments = !empty($_FILES['agent_file']['name'][0])
        ? upload_files($_FILES['agent_file'])
        : json_encode([]);

    $agentFormData = [
        'edit_permission' => false,
        'boe_no' => mysqli_real_escape_string($connect, $_POST['boe_no']),
        'boe_date' => mysqli_real_escape_string($connect, $_POST['boe_date']),
        'pick_up_date' => mysqli_real_escape_string($connect, $_POST['pick_up_date']),
        'waiting_days' => mysqli_real_escape_string($connect, $_POST['waiting_days']),
        'return_date' => mysqli_real_escape_string($connect, $_POST['return_date']),
        'transporter_name' => mysqli_real_escape_string($connect, $_POST['transporter_name']),
        'truck_number' => mysqli_real_escape_string($connect, $_POST['truck_number']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'driver_number' => mysqli_real_escape_string($connect, $_POST['driver_number']),
        'attachments' => json_decode($attachments, true)
    ];

    $existingData['agent_info'][$item] = array_merge($existingData['agent_info'][$item], $agentFormData);
    $row_id = $existingData['agent_info'][$item]['row_id'];
    $existingData['agent_info'] = json_encode($existingData['agent_info']);
    $done = update('general_loading', $existingData, ['id' => $bl_id]);
    $done = update('user_permissions', array('permission' => json_encode(['agent-form', 'agent-payments-form'])), array('id' => $row_id));
    if ($done) {
        message('danger', '', 'Updated!');
    }
}
if (isset($_GET['bl_id']) && is_numeric($_GET['bl_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $bl_id = mysqli_real_escape_string($connect, $_GET['bl_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($bl_id); });</script>";
}
?>