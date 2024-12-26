<?php
$page_title = 'Carry Bill';
$pageURL = 'carry-bill';
include("header.php");
$remove = $start_print = $end_print = $type = $acc_no = $p_id = $truck_no = $sea_road = $billStatus = $blSearch = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading` WHERE JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL";
$conditions = [];
$print_filters = [];
$user = $_SESSION['username'];
if ($_GET) {
    $remove = removeFilter('carry-bill');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_id;
        $conditions[] = "p_id = '$p_id'";
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
    if (!empty($_GET['truck_no'])) {
        $truck_no = mysqli_real_escape_string($connect, $_GET['truck_no']);
        $print_filters[] = 'truck_no=' . $truck_no;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.loading_truck_number') = '$truck_no'";
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
    $sql .= " AND " . implode(' AND ', $conditions);
}
if ($user !== 'admin') {
    $sql .= " AND JSON_EXTRACT(agent_details, '$.ag_id') = '$user'";
}
$sql .= " AND JSON_EXTRACT(agent_details, '$.ag_id') IS NOT NULL ORDER BY id DESC LIMIT $start_from, $results_per_page";
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
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

                    $count_sql = "SELECT COUNT(id) AS total FROM `general_loading` WHERE JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL AND JSON_EXTRACT(agent_details, '$.ag_id') IS NOT NULL";
                    if (count($conditions) > 0) {
                        $count_sql .= ' AND ' . implode(' AND ', $conditions);
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
                <input type="number" name="p_id" value="<?php echo $p_id; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
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
                <label for="truck_no" class="form-label">Truck No.</label>
                <input type="text" name="truck_no" value="<?php echo $truck_no; ?>" id="truck_no" class="form-control form-control-sm mx-1" style="max-width:100px;" placeholder="e.g. TKU 2563 COMTY NMNVBM">
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
                <tr class="text-nowrap">
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
                $Loadings = mysqli_query($connect, $sql);
                $row_count = $p_kgs_total = $p_qty_total = 0;
                $grandTotal = $rowColor = '';
                $paymentTotals = [];
                $paymentsData = [];
                $payment_totalQ = mysqli_query($connect, "SELECT * FROM agent_payments");
                while ($payment_total = mysqli_fetch_assoc($payment_totalQ)) {
                    $transferDetails = json_decode($payment_total['transfer_details'], true);
                    if (empty($transferDetails['transferred_to_admin']) || !$transferDetails['transferred_to_admin']) {
                        continue;
                    }
                    $loadingId = $payment_total['loading_id'];
                    $paymentTotals[$loadingId] = $transferDetails['total_bill_amount'] ?? 0;
                    $paymentsData[$loadingId] = $payment_total['id'];
                }
                $roznamchaasData = [];
                $roznamchaasQ = mysqli_query($connect, "SELECT r_id, branch_serial, created_at, transfered_from_id FROM roznamchaas WHERE r_type = 'Agent Bill'");
                while ($roznamchaas = mysqli_fetch_assoc($roznamchaasQ)) {
                    $transferredFromId = $roznamchaas['transfered_from_id'];
                    $roznamchaasData[$transferredFromId][] = [
                        'r_id' => $roznamchaas['r_id'],
                        'branch_serial' => $roznamchaas['branch_serial'],
                        'created_at' => $roznamchaas['created_at']
                    ];
                }
                $row_count = 0;
                foreach ($Loadings as $SingleLoading) {
                    $agentDetails = json_decode($SingleLoading['agent_details'], true);
                    $loadingId = $SingleLoading['id'];
                    if (!isset($paymentTotals[$loadingId])) {
                        continue;
                    }
                    $currentBillNumber = json_decode($SingleLoading['gloading_info'], true)['billNumber'] ?? '';
                    $grandTotal = $paymentTotals[$loadingId];
                    $SuperCode = $rowColor . ' pointer" onclick="window.location.href = \'carry-bill?view=1&id=' . $loadingId . '\';"';
                    $primaryId = $paymentsData[$loadingId] ?? null;
                    $roznamchaasDisplay = $createdAt = '';
                    if ($primaryId && isset($roznamchaasData[$primaryId])) {
                        $roznamchaasEntries = $roznamchaasData[$primaryId];
                        $roznamchaasDisplay = implode('<br>', array_map(function ($entry) {
                            return "<small>{$entry['r_id']}-{$entry['branch_serial']}</small>";
                        }, $roznamchaasEntries));
                        $createdAt = substr(end($roznamchaasEntries)['created_at'], 0, 10);
                    }
                ?>

                    <tr class="text-nowrap">
                        <?php if (!SuperAdmin()): ?>
                            <td class="<?= $rowColor; ?>"><?= $row_count + 1; ?></td>
                        <?php endif; ?>
                        <td class="<?= $SuperCode; ?>"><b><?= SuperAdmin() ? ucfirst($SingleLoading['type']) . "#" . $SingleLoading['p_id'] . " ($currentBillNumber)" : $SingleLoading['bl_no']; ?></b></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_acc_no']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_id']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_name']; ?></td>
                        <?php if (SuperAdmin()): ?>
                            <td class="<?= $SuperCode; ?>"><b><?= $SingleLoading['bl_no']; ?></b></td>
                        <?php endif; ?>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['boe_date'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['pick_up_date'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['waiting_days'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['return_date'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['transporter_name'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['truck_number'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['driver_name'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $grandTotal; ?></td>
                        <td class="<?= $rowColor; ?> transfer_date"><?= !empty($createdAt) ? $createdAt : '<i class="fa fa-times text-danger"></i>'; ?></td>
                        <td class="<?= $rowColor; ?>"><?= !empty($roznamchaasDisplay) ? $roznamchaasDisplay : '<i class="fa fa-times text-danger"></i>'; ?></td>
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
        let editId = '<?= isset($_GET['editId']) ? $_GET['editId'] : ''; ?>';
        if (id) {
            $.ajax({
                url: 'ajax/viewDailyAgentPaymentsCarry.php',
                type: 'post',
                data: {
                    id: id,
                    editId: editId,
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
        // Function to get the query parameter value
        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Get the value of 's_khaata_id' parameter from the URL
        var s_khaata_id = getQueryParameter('s_khaata_id').toUpperCase();

        // Iterate over all the <td class="<?php echo $rowColor; ?>"> elements with class 's_khaata_id_row'
        $('td.s_khaata_id_row').each(function() {
            // Get the text content of the current <td class="<?php echo $rowColor; ?>">
            var cellText = $(this).text().trim();
            // If the text doesn't match the 's_khaata_id' parameter, hide the parent <tr>
            if (cellText !== s_khaata_id && s_khaata_id !== '') {
                $(this).closest('tr').hide();
            }
        });
    });
</script>

<?php
if (isset($_POST['agPaymentSubmit'])) {
    unset($_POST['agPaymentSubmit']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['dr_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['dr_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['cr_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['cr_khaata_id']);
    $transfer_date = mysqli_real_escape_string($connect, $_POST['transfer_date']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $final_amount = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $details = mysqli_real_escape_string($connect, $_POST['details']) . " | Amount: $amount " . $_POST['currency1'] . " " . $_POST['opr'] . " " . $_POST['rate'] . ' = ' . $final_amount . " " . $_POST['currency2'];
    $p_id = mysqli_real_escape_string($connect, $_POST['parent_payment_id']);
    $type_post = "Agent Bill";
    $url = $pageURL . '?view=1&id=' . $_POST['loading_id'];
    $type = ucfirst($_POST['type']) . '.A.Bill';
    $transfered_from = 'agent_payments';
    $r_type = 'Agent Bill';
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $pQuery = fetch('transactions', array('id' => $_POST['p_id']));
        $p_data = mysqli_fetch_assoc($pQuery);
        $branch_serial = getBranchSerial($p_data['branch_id'], $r_type);
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $p_id,
            'branch_id' => $p_data['branch_id'],
            'user_id' => $_SESSION['userId'],
            'username' => $user,
            'r_date' => $transfer_date,
            'roznamcha_no' => $p_id,
            'r_name' => $type,
            'r_no' => $p_id,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " Agent Bill # " . $_POST['type'] . $p_id;
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
            $existingData = json_decode($_POST['existing_data'], true);
            $existingData['transferred_to_accounts'] = true;
            $mybl = $existingData['bl_no'];
            unset($_POST['existing_data']);
            $post_json = [
                'transfer_info' => $_POST
            ];
            $final = json_encode(array_merge($existingData, $post_json));
            $preData = array('transfer_details' => $final);
            $tlUpdated = update('agent_payments', $preData, array('id' => $p_id));
            $parentGL = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM general_loading WHERE bl_no = '$mybl' AND JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL"));
            $parentGL['gloading_info'] = json_encode(array_merge(json_decode($parentGL['gloading_info'], true), ['transfer_info' => $_POST]));
            update('general_loading', $parentGL, ['id' => $parentGL['id']]);
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
    message($msgType, $url, $msg);
}
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $loadingID = mysqli_real_escape_string($connect, $_GET['id']);
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