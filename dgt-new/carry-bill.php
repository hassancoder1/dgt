<?php
$page_title = 'Carry Bill';
$pageURL = 'carry-bill';
include("header.php");
$remove = $start_print = $end_print = $type = $acc_no = $p_sr = $truck_number = $sea_road = $billStatus = $blSearch = $date_type = '';
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
    $remove = removeFilter('carry-bill');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_sr;
        $conditions[] = "p_sr = '$p_sr'";
    }
    $date_type = isset($_GET['date_type']) ? $_GET['date_type'] : '';
    if (!empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        if ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(agent_details, '$.received_date') >= '$start_print'";
        } elseif ($date_type == 'clearing') {
            $conditions[] = "JSON_EXTRACT(agent_details, '$.clearing_date') >= '$start_print'";
        } elseif ($date_type == 'returning') {
            $conditions[] = "JSON_EXTRACT(agent_details, '$.truck_returning_date') >= '$start_print'";
        }
    }
    if (!empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;

        if ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(agent_details, '$.received_date') <= '$end_print'";
        } elseif ($date_type == 'clearing') {
            $conditions[] = "JSON_EXTRACT(agent_details, '$.clearing_date') <= '$end_print'";
        } elseif ($date_type == 'returning') {
            $conditions[] = "JSON_EXTRACT(agent_details, '$.truck_returning_date') <= '$end_print'";
        }
    }
    if (!empty($_GET['p_type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['p_type']);
        $print_filters[] = 'p_type=' . $type;
        $conditions[] = "p_type = '$type'";
    }
    if (!empty($_GET['blSearch'])) {
        $blSearch = mysqli_real_escape_string($connect, $_GET['blSearch']);
        $print_filters[] = 'blSearch=' . $blSearch;
        $conditions[] = "bl_no='$blSearch'";
    }
    if (!empty($_GET['acc_no']) && $user === 'admin') {
        $acc_no = mysqli_real_escape_string($connect, $_GET['acc_no']);
        $print_filters[] = 'acc_no=' . $acc_no;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.ag_acc_no') = '$acc_no'";
    }
    if (!empty($_GET['truck_number'])) {
        $truck_number = mysqli_real_escape_string($connect, $_GET['truck_number']);
        $print_filters[] = 'truck_number=' . $truck_number;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.truck_number') = '$truck_number'";
    }
    if (!empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(shipping_details, '$.transfer_by') = '$sea_road'";
    }
    if (!empty($_GET['billStatus'])) {
        $billStatus = mysqli_real_escape_string($connect, $_GET['billStatus']);
        $print_filters[] = 'billStatus=' . $billStatus;
    }
}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}
if ($user !== 'admin') {
    $sql .= " JSON_SEARCH(agent_info, 'one', '$user', NULL, '$.*.ag_id') IS NOT NUL";
}
$sql .= " ORDER BY id DESC LIMIT $start_from, $results_per_page";
$query_string = implode('&', $print_filters);
$BlNumbers = mysqli_query($connect, $sql);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$sortedEntries = [];
$i = 1;
$Agpayments = mysqli_fetch_all(fetch('agent_payments'), MYSQLI_ASSOC);
$rozQ = fetch('roznamchaas', array('r_type' => 'Agent Bill', 'transfered_from' => 'agent_payments'));
$payments = [];
$rozEnteries = [];
foreach ($rozQ as $r) {
    $rozEnteries[$r['transfered_from_id']][] = $r;
}
foreach ($Agpayments as $p) {
    if (isset($rozEnteries[$p['id']])) {
        $rozEnteries[$p['bl_id']] = $rozEnteries[$p['id']];
        unset($rozEnteries[$p['id']]);
    }
    $payments[$p['bl_id']] = json_decode($p['transfer_info'], true);
}
while ($oneBl = mysqli_fetch_assoc($BlNumbers)) {
    $row_color = '';
    $oneBl['loading_info'] = json_decode($oneBl['loading_info'] ?? '[]', true);
    if (isset($oneBl['loading_info']['transferred_to_admin']) && $oneBl['loading_info']['transferred_to_admin'] === true) {
        $oneBl['goods_info'] = json_decode($oneBl['goods_info'] ?? '[]', true);
        $oneBl['agent_info'] = json_decode($oneBl['agent_info'] ?? '[]', true);
        $oneBl['warehouse_info'] = json_decode($oneBl['warehouse_info'] ?? '[]', true);
        $oneBl['loading_info']['transferred_to_accounts'] = isset($oneBl['loading_info']['transferred_to_accounts']) ? $oneBl['loading_info']['transferred_to_accounts'] : false;
        $row_color = !isset($payments[$oneBl['id']]) ? 'text-danger' : 'text-warning';
        $row_color = $oneBl['loading_info']['transferred_to_accounts'] ? 'text-dark' : 'text-warning';
        $oneBl['row_color'] = $row_color;
        $oneBl['i'] = $i;
        $oneBl['payments'] = isset($payments[$oneBl['id']]) ? $payments[$oneBl['id']] : [];
        $oneBl['roz'] = isset($rozEnteries[$oneBl['id']]) ? $rozEnteries[$oneBl['id']] : [];
        $oneBl['firstAgent'] = reset($oneBl['agent_info']);
        $sortedEntries[] = $oneBl;
        $i++;
    }
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
            Carry Bill
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
                    <option value="" <?= !in_array($date_type, ['receiving', 'clearing', 'returning', 'transfer']) ? 'selected' : ''; ?>>All</option>
                    <option value="receiving" <?= $date_type == 'receiving' ? 'selected' : ''; ?>>Receiving</option>
                    <option value="clearing" <?= $date_type == 'clearing' ? 'selected' : ''; ?>>Clearing</option>
                    <option value="returning" <?= $date_type == 'returning' ? 'selected' : ''; ?>>Returning</option>
                    <option value="transfer" <?= $date_type == 'transfer' ? 'selected' : ''; ?>>Transfer</option>
                </select>
            </div>
            <div class="form-group <?= !in_array($date_type, ['receiving', 'clearing', 'returning', 'transfer']) ? 'd-none' : ''; ?>" id="startInput">
                <label for="start" class="form-label">Start Date</label>
                <input type="date" name="start" value="<?php echo $start_print; ?>" id="start" class="form-control form-control-sm mx-1" style="max-width:160px;">
            </div>
            <div class="form-group <?= !in_array($date_type, ['receiving', 'clearing', 'returning', 'transfer']) ? 'd-none' : ''; ?>" id="endInput">
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
                <label for="blSearch" class="form-label">B/L Search</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:130px;" name="blSearch" placeholder="B/L Search" value="<?php echo $blSearch; ?>" id="blSearch">
            </div>
            <div class="form-group">
                <label for="truck_number" class="form-label">Truck No.</label>
                <input type="text" name="truck_number" value="<?php echo $truck_number; ?>" id="truck_number" class="form-control form-control-sm mx-1" style="max-width:100px;" placeholder="e.g. TKU 2563 COMTY NMNVBM">
            </div>
            <div class="form-group">
                <label for="billStatus" class="form-label">Bill Status</label>
                <select class="form-select form-select-sm" name="billStatus" style="max-width:130px;" id="billStatus">
                    <option value="" <?= !in_array($billStatus, ['Complete', 'In Complete']) ? 'selected' : ''; ?>>All</option>
                    <option value="Complete" <?= $billStatus == 'Complete' ? 'selected' : ''; ?>>Complete</option>
                    <option value="In Complete" <?= $billStatus == 'In Complete' ? 'selected' : ''; ?>>In Complete</option>
                </select>
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
                <tr class="text-nowrap" style="font-size: 13px;">
                    <?php if (SuperAdmin()): ?>
                        <th>P/S#+Bill#</th>
                        <th>AGENT ACC</th>
                        <th>AGENT ID</th>
                        <th>AGENT NAME</th>
                    <?php else: ?>
                        <th>#</th>
                    <?php endif; ?>
                    <th>BOE No</th>
                    <?php if (!SuperAdmin()): ?>
                        <th>AGENT ACC</th>
                        <th>AGENT ID</th>
                        <th>AGENT NAME</th>
                    <?php endif; ?>
                    <th>BOE.Date</th>
                    <th>PickUp.Date</th>
                    <th>Wait Days</th>
                    <th>Return.Date</th>
                    <th>Transporter</th>
                    <th>Truck No.</th>
                    <th>Driver Name</th>
                    <th>T.Bill.Amt</th>
                    <th>Acc Trans Date</th>
                    <th>Roz#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sortedEntries as $entry) {
                    $transfer_date = '';
                    $Roz = [];
                    foreach ($entry['roz'] as $myP) {
                        $transfer_date = my_date($myP['r_date']);
                        $Roz[] = '<span>' . $myP['r_id'] . '-' . $myP['branch_serial'] . '</span>';
                    }
                    $Roz = implode('<br>', $Roz);
                ?>
                    <tr class="text-nowrap <?= $entry['row_color']; ?>">
                        <?php if (!SuperAdmin()): ?>
                            <td class="<?= $entry['row_color']; ?>"><?= $entry['i']; ?></td>
                        <?php endif; ?>
                        <td class="text-uppercase pointer <?= $entry['row_color']; ?>" onclick="window.location.href= '?view=1&bl_id=<?= $entry['id']; ?>';"><b><?= SuperAdmin() ? ucfirst($entry['p_s']) . "#" . $entry['t_sr'] . " (" . $entry['firstAgent']['bill_number'] . ")" : $entry['bl_no']; ?></b></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['ag_acc_no']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['ag_id']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['ag_name']; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['boe_no'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['boe_date'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['pick_up_date'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['waiting_days'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['return_date'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['transporter_name'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['truck_number'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $entry['firstAgent']['driver_name'] ?? ''; ?></td>
                        <td class="<?= $entry['row_color']; ?> bill-status fw-bold"><?= $entry['payments']['total_final_amount'] ?? '&times;'; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $transfer_date ?? '&times;'; ?></td>
                        <td class="<?= $entry['row_color']; ?>"><?= $Roz ?? '&times;'; ?></td>
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
        let edit = '<?= isset($_GET['edit']) ? $_GET['edit'] : ''; ?>';
        if (id) {
            $.ajax({
                url: 'ajax/viewDailyAgentPaymentsCarry.php',
                type: 'post',
                data: {
                    id: id,
                    edit: edit,
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
    $(document).ready(function() {
        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
        var s_khaata_id = getQueryParameter('s_khaata_id').toUpperCase();
        $('td.s_khaata_id_row').each(function() {
            var cellText = $(this).text().trim();
            if (cellText !== s_khaata_id && s_khaata_id !== '') {
                $(this).closest('tr').hide();
            }
        });
    });
</script>
<?php
if (isset($_POST['PaymentSubmit'])) {
    unset($_POST['agPaymentSubmit']);
    $bill_id = mysqli_real_escape_string($connect, $_POST['bill_id']);
    $bl_id = mysqli_real_escape_string($connect, $_POST['bl_id']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_acc_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_acc_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_acc_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_acc_id']);
    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $final_amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']) . " | Amount: $amount " . $_POST['currency1'] . " " . $_POST['opr'] . " " . $_POST['rate'] . ' = ' . $final_amount . " " . $_POST['currency2'];
    $type_post = "Agent Bill";
    $url = $pageURL . '?view=1&bl_id=' . $bl_id;
    $type = ucfirst($_POST['p_s']) . '.Agent.Bill';
    $transfered_from = 'agent_payments';
    $r_type = 'Agent Bill';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $branch_serial = getBranchSerial($_POST['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $bill_id,
            'branch_id' => $_POST['branch_id'],
            'user_id' => $_SESSION['userId'],
            'username' => $user,
            'r_date' => $transfer_date,
            'roznamcha_no' => $bill_id,
            'r_name' => $type,
            'r_no' => $bill_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Agent Bill # " . $type . $bill_id;
        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                $i++;
                if ($i == 1) {
                    /*$k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);*/
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Dr. " . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['amount'] = $final_amount;
                    $str .= "<span class='badge bg-dark mx-2'> Cr. " . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'dr';
                    $dataArray['details'] = 'Dr. A/c:' . $bnaam_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Dr." . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['amount'] = $final_amount;
                    $dataArray['dr_cr'] = 'cr';
                    $dataArray['details'] = 'Cr. A/c:' . $jmaa_khaata_no . " " . $details;
                    $str .= "<span class='badge bg-dark mx-2'>Cr." . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $Bill = mysqli_fetch_assoc(fetch('agent_payments', ['bl_id' => $bl_id]));
            $BL = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));
            $BL['loading_info'] = json_decode($BL['loading_info'], true);
            $BL['loading_info']['transferred_to_accounts'] = true;
            $BL['loading_info'] = json_encode($BL['loading_info']);
            $Bill['transfer_info'] = json_decode($Bill['transfer_info'], true);
            $Bill['transfer_info']['transferred_to_accounts'] = true;
            $Bill['transfer_info']['transferred_info'] = $_POST;
            $Bill['transfer_info'] = json_encode($Bill['transfer_info']);
            update('general_loading', $BL, ['id' => $bl_id]);
            update('agent_payments', $Bill, ['bl_id' => $bl_id]);
            $msg = 'Transferred to Business Roznamcha ' . $str;
            $msgType = 'success';
        } else {
            $msg = 'Transfer Error ';
            $msgType = 'danger';
        }
    } else {
        $msg = 'Technical Problem. Contact Admin';
        $msgType = 'warning';
    }
    // message($msgType, $url, $msg);
}
if (isset($_GET['bl_id']) && is_numeric($_GET['bl_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $loadingID = mysqli_real_escape_string($connect, $_GET['bl_id']);
    echo "<script>
    jQuery(document).ready(function($) {
        $('#KhaataDetails').modal('show');
    });
</script>";
    echo "<script>
    jQuery(document).ready(function($) {
        viewPurchase($loadingID);
    });
</script>";
}
?>
<script>
    function toggleDates() {
        const selectedValue = $('#date_type').val();
        if (selectedValue === "") {
            $('#startInput, #endInput').addClass('d-none');
        } else {
            $('#startInput, #endInput').removeClass('d-none');
        }
    };
    $(document).ready(function() {
        var billStatus = getQueryParameter('billStatus') ? getQueryParameter('billStatus') : '';
        var transferDate = getQueryParameter('date_type') ? getQueryParameter('date_type') : '';
        if (transferDate === 'transfer') {
            var startDate = getQueryParameter('start') ? getQueryParameter('start') : '';
            var endDate = getQueryParameter('end') ? getQueryParameter('end') : '';
            var start = new Date(startDate);
            var end = new Date(endDate);
            $('tbody tr').each(function() {
                var rowTransferDate = $(this).find('td.transfer_date').text().trim();
                if ($(this).find('td.transfer_date i.fa-times.text-danger').length > 0) {
                    $(this).hide();
                } else if (rowTransferDate) {
                    var rowDate = new Date(rowTransferDate);
                    console.log('Start:', start, 'End:', end, 'Row Date:', rowDate);
                    if (isNaN(rowDate) || rowDate < start || rowDate > end) {
                        $(this).hide();
                    }
                }
            });
        }


        $('tbody tr').each(function() {
            var rowBillStatus = $(this).find('td.bill-status').text().trim();
            if (billStatus && billStatus === 'Complete' && rowBillStatus === 'In Complete') {
                $(this).hide();
            } else if (billStatus && billStatus === 'In Complete' && rowBillStatus !== 'In Complete') {
                $(this).hide();
            }
        });
    });
</script>