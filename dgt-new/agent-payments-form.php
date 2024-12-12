<?php
$page_title = 'Agent Payments Form';
$pageURL = 'agent-payments-form';
include("header.php");
$remove = $start_print = $end_print = $type = $acc_no = $p_id = $truck_no = $sea_road = $billStatus = $billEntryNoSearch = $date_type = '';
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
    $remove = removeFilter('agent-payments-form');
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
    if (!empty($_GET['billEntryNoSearch'])) {
        $billEntryNoSearch = mysqli_real_escape_string($connect, $_GET['billEntryNoSearch']);
        $print_filters[] = 'billEntryNoSearch=' . $billEntryNoSearch;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.bill_of_entry_no')='$billEntryNoSearch'";
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
            Agent Payments
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
                    <option value="" <?= !in_array($date_type, ['receiving', 'clearing', 'returning']) ? 'selected' : ''; ?>>All</option>
                    <option value="receiving" <?= $date_type == 'receiving' ? 'selected' : ''; ?>>Receiving</option>
                    <option value="clearing" <?= $date_type == 'clearing' ? 'selected' : ''; ?>>Clearing</option>
                    <option value="returning" <?= $date_type == 'returning' ? 'selected' : ''; ?>>Returning</option>
                </select>
            </div>
            <div class="form-group <?= !in_array($date_type, ['receiving', 'clearing', 'returning']) ? 'd-none' : ''; ?>" id="startInput">
                <label for="start" class="form-label">Start Date</label>
                <input type="date" name="start" value="<?php echo $start_print; ?>" id="start" class="form-control form-control-sm mx-1" style="max-width:160px;">
            </div>
            <div class="form-group <?= !in_array($date_type, ['receiving', 'clearing', 'returning']) ? 'd-none' : ''; ?>" id="endInput">
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
                <label for="billEntryNoSearch" class="form-label">Bill Entry No Search</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:130px;" name="billEntryNoSearch" placeholder="Bill Entry No Search" value="<?php echo $billEntryNoSearch; ?>" id="billEntryNoSearch">
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
    <div class="table-responsive mt-4">
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
                    <th>Bill Of Entry No</th>
                    <?php if (!SuperAdmin()): ?>
                        <th>AGENT ACC</th>
                        <th>AGENT ID</th>
                        <th>AGENT NAME</th>
                    <?php endif; ?>
                    <th>BOE.Date</th>
                    <th>PickUp.Date</th>
                    <th>Waiting(YES/NO)</th>
                    <th>No.of.Days.W</th>
                    <th>Return.Date</th>
                    <th>Truck No.</th>
                    <th>Driver</th>
                    <th>Transporter</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Loadings = mysqli_query($connect, $sql);
                $row_count = $p_kgs_total = $p_qty_total = 0;
                $grandTotal = $rowColor = '';
                $paymentTotals = [];
                $payment_totalQ = mysqli_query($connect, "SELECT * FROM agent_payments WHERE JSON_EXTRACT(transfer_details, '$.transferred_to_admin') IS NOT NULL");
                while ($payment_total = mysqli_fetch_assoc($payment_totalQ)) {
                    $transferDetails = json_decode($payment_total['transfer_details'], true);
                    $loadingId = $payment_total['loading_id'];
                    $paymentTotals[$loadingId] = isset($transferDetails['total_bill_amount']) ? $transferDetails['total_bill_amount'] : 0;
                }
                foreach ($Loadings as $SingleLoading) {
                    $agentDetails = json_decode($SingleLoading['agent_details'], true);
                    if (!isset($agentDetails['transporter_name'])) {
                        continue;
                    }
                    $loadingId = $SingleLoading['id'];
                    $currentBillNumber = json_decode($SingleLoading['gloading_info'], true)['billNumber'] ?? '';
                    $grandTotal = isset($paymentTotals[$loadingId]) ? $paymentTotals[$loadingId] : 0;
                    $rowColor = $grandTotal === 0 ? 'text-danger' : '';
                    $grandTotal =  $grandTotal === 0 ? 'In Complete' : $grandTotal;
                    $SuperCode = $rowColor . ' pointer" onclick="window.location.href = \'agent-payments-form?view=1&id=' . $loadingId . '\';"';
                ?>

                    <tr class="text-nowrap">
                        <?php if (!SuperAdmin()): ?>
                            <td class="<?= $rowColor; ?>"><?= $row_count + 1; ?></td>
                        <?php endif; ?>
                        <td class="text-uppercase <?= $SuperCode; ?>"><b><?= SuperAdmin() ? $SingleLoading['type'] . "#" . $SingleLoading['p_id'] . " ($currentBillNumber)" : $SingleLoading['bl_no']; ?></b></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_acc_no']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_id']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_name']; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['boe_date'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['pick_up_date'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['waiting_if_any'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['days_waiting'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['return_date'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['truck_number'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['driver_details'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?>"><?= $agentDetails['transporter_name'] ?? ''; ?></td>
                        <td class="<?= $rowColor; ?> bill-status fw-bold"><?= $grandTotal; ?></td>
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
                url: 'ajax/viewAgentPaymentsForm.php',
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
<?php
if (isset($_POST['AgentPaymentsFormSubmit']) || isset($_POST['UpdateAgPaymentEntry'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'agent-payments-form';
    $loading_id = mysqli_real_escape_string($connect, $_POST['loading_id']);
    $bl_no = mysqli_real_escape_string($connect, $_POST['bl_no']);
    $bill_no = mysqli_real_escape_string($connect, $_POST['bill_no']);
    $date = mysqli_real_escape_string($connect, $_POST['date']);
    $bill_details = mysqli_real_escape_string($connect, $_POST['bill_details']);
    $sr_no = mysqli_real_escape_string($connect, $_POST['sr_no']);
    $details = mysqli_real_escape_string($connect, $_POST['details']);
    $quantity = mysqli_real_escape_string($connect, $_POST['quantity']);
    $rate = mysqli_real_escape_string($connect, $_POST['rate']);
    $total = mysqli_real_escape_string($connect, $_POST['total']);
    $tax_percentage = mysqli_real_escape_string($connect, $_POST['tax-percentage']);
    $tax_amount = mysqli_real_escape_string($connect, $_POST['tax']);
    $grand_total = mysqli_real_escape_string($connect, $_POST['grand_total']);
    $editId = mysqli_real_escape_string($connect, isset($_POST['editId']) ? $_POST['editId'] : '');
    $uploadDir = 'attachments/';
    $uploadedFiles = [];
    if (!empty($_FILES['agent_file']['name'][0])) {
        foreach ($_FILES['agent_file']['name'] as $key => $filename) {
            $tmpName = $_FILES['agent_file']['tmp_name'][$key];
            $newFilename = time() . '_' . basename($filename);

            if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                $uploadedFiles[$key] = $newFilename;
            }
        }
    }
    if (isset($_POST['firstRowID'])) {
        $fetchTransfer = json_decode(mysqli_fetch_assoc(fetch('agent_payments', ['id' => $_POST['firstRowID']]))['transfer_details'], true);
    } else {
        $fetchTransfer = [];
    }
    $combineData = [
        'bill_no' => $bill_no,
        'date' => $date,
        'bill_details' => $bill_details,
        'transfer_details' => json_encode(array_merge($fetchTransfer, [
            'bl_no' => $bl_no,
            'transferred_to_admin' => false,
            'transferred_to_accounts' => false,
        ])),
        'agent_file' => json_encode($uploadedFiles),
    ];
    $data = [
        'bill_no' => $bill_no,
        'loading_id' => $loading_id,
        'bl_no' => $bl_no,
        'sr_no' => $sr_no,
        'details' => $details,
        'quantity' => $quantity,
        'rate' => $rate,
        'total' => $total,
        'tax_percentage' => $tax_percentage,
        'tax_amount' => $tax_amount,
        'grand_total' => $grand_total,
    ];
    if ($combineData['agent_file'] === '[]') {
        unset($combineData['agent_file']);
    }
    if (isset($_POST['firstRowID']) && $editId !== $_POST['firstRowID']) {
        $data['transfer_details'] = json_encode(['parent_id' => $_POST['firstRowID']]);
        $done = update('agent_payments', $combineData, ['id' => $_POST['firstRowID']]);
    } else {
        $data = array_merge($data, $combineData);
    }
    $done = isset($_POST['UpdateAgPaymentEntry']) ? update('agent_payments', $data, array('id' => $editId)) : insert('agent_payments', $data);
    if ($done) {
        $type = 'success';
        $url .= "?view=1&id=$loading_id";
        $msg = 'Agent Payment Added!';
    }
    message($type, $url, $msg);
}
if (isset($_GET['deleteAgPaymentEntry'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'agent-payments-form?view=1&id=' . mysqli_real_escape_string($connect, $_GET['loading_id']);
    $id = mysqli_real_escape_string($connect, $_GET['billEntryId']);
    $done = mysqli_query($connect, "DELETE FROM agent_payments WHERE id='$id'");
    if ($done) {
        $type = 'success';
        $msg = 'Agent Payment Added!';
    }
    message($type, $url, $msg);
}
if (isset($_POST['TransferBillToAdmin'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $url = 'agent-payments-form?view=1&id=' . mysqli_real_escape_string($connect, $_POST['loading_id']);
    $parent_id = mysqli_real_escape_string($connect, $_POST['parent_payment_id']);
    $existingData = json_decode($_POST['existing_data'], true); // Convert to associative array
    $child_ids = mysqli_real_escape_string($connect, $_POST['child_ids']);
    $child_ids_array = explode(',', $child_ids);

    $combineData = [
        'transferred_to_admin' => true,
        'child_ids' => $child_ids,
        'total_amount' => mysqli_real_escape_string($connect, $_POST['total_amount']),
        'total_bill_amount' => mysqli_real_escape_string($connect, $_POST['total_bill_amount']),
        'total_tax_amount' => mysqli_real_escape_string($connect, $_POST['total_tax_amount']),
    ];
    $existingData = array_merge($existingData, $combineData);
    $data = [
        'transfer_details' => json_encode($existingData)
    ];
    $done = update('agent_payments', $data, ['id' => $parent_id]);
    if ($done && !empty($child_ids_array)) {
        foreach ($child_ids_array as $child_id) {
            $child_id = mysqli_real_escape_string($connect, $child_id);
            $updateData = [
                'transfer_details' => json_encode([
                    'parent_id' => $parent_id
                ])
            ];
            $done = update('agent_payments', $updateData, ['id' => $child_id]);
        }
    }
    if ($done) {
        $type = 'success';
        $msg = 'Transferred to Admin';
    }
    message($type, $url, $msg);
}
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $loadingID = mysqli_real_escape_string($connect, $_GET['id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($loadingID); });</script>";
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
        $('tbody tr').each(function() {
            var rowbillStatus = $(this).find('td.bill-status').text().trim();
            if (billStatus && billStatus === 'Complete' && rowbillStatus === 'In Complete') {
                $(this).hide();
            } else if (billStatus && billStatus === 'In Complete' && rowbillStatus !== 'In Complete') {
                $(this).hide();
            }
        });
    });
</script>