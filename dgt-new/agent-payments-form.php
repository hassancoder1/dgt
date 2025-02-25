<?php
$page_title = 'Agent Payments Form';
$pageURL = 'agent-payments-form';
include("header.php");

$remove = $start_print = $end_print = $type = $acc_no = $p_sr = $truck_number = $sea_road = $billStatus = $billEntryNoSearch = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading`";
$conditions = ["JSON_SEARCH(agent_info, 'one', 'true', NULL, '$.*.agent_exist') IS NOT NULL"];
$print_filters = [];
$user = $_SESSION['username'] ?? '';

if ($_GET) {
    $remove = removeFilter('agent-payments-form');
    $is_search = true;
    
    if (!empty($_GET['p_id'])) {
        $p_sr = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_sr;
        $conditions[] = "p_sr = '$p_sr'";
    }
    
    $date_type = $_GET['date_type'] ?? '';
    $print_filters[] = 'date_type=' . $date_type;
    
    if (!empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        $conditions[] = "JSON_EXTRACT(agent_details, '$." . $date_type . "_date') >= '$start_print'";
    }
    
    if (!empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        $conditions[] = "JSON_EXTRACT(agent_details, '$." . $date_type . "_date') <= '$end_print'";
    }
    
    if (!empty($_GET['p_type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['p_type']);
        $print_filters[] = 'p_type=' . $type;
        $conditions[] = "p_type = '$type'";
    }
    
    if (!empty($_GET['billEntryNoSearch'])) {
        $billEntryNoSearch = mysqli_real_escape_string($connect, $_GET['billEntryNoSearch']);
        $print_filters[] = 'billEntryNoSearch=' . $billEntryNoSearch;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.boe_no')='$billEntryNoSearch'";
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

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

if ($user !== 'admin') {
    $sql .= " AND JSON_SEARCH(agent_info, 'one', '$user', NULL, '$.*.ag_id') IS NOT NULL";
}

$sql .= " ORDER BY id DESC LIMIT $start_from, $results_per_page";
$query_string = implode('&', $print_filters);
$BlNumbers = mysqli_query($connect, $sql);
$print_url = "print/" . $pageURL . "-main?" . $query_string;
$sortedEntries = [];
$i = 1;

$Agpayments = mysqli_fetch_all(fetch('agent_payments'), MYSQLI_ASSOC) ?? [];
$payments = [];
foreach ($Agpayments as $p) {
    $payments[$p['bl_id']] = array_sum(array_column(json_decode($p['bill_entries'] ?? '[]', true), 'final_amount'));
}

while ($oneBl = mysqli_fetch_assoc($BlNumbers)) {
    $oneBl['loading_info'] = json_decode($oneBl['loading_info'] ?? '{}', true);
    $oneBl['goods_info'] = json_decode($oneBl['goods_info'] ?? '{}', true);
    $oneBl['agent_info'] = json_decode($oneBl['agent_info'] ?? '{}', true);
    $oneBl['warehouse_info'] = json_decode($oneBl['warehouse_info'] ?? '{}', true);
    
    $transferred_to_admin = $oneBl['loading_info']['transferred_to_admin'] ?? false;
    
    if ($transferred_to_admin) {
        $row_color = 'text-dark';
    } elseif (!isset($payments[$oneBl['id']])) {
        $row_color = 'text-danger';
    } else {
        $row_color = 'text-warning';
    }
    
    $oneBl['row_color'] = $row_color;
    $oneBl['i'] = $i;
    $oneBl['grandTotal'] = isset($payments[$oneBl['id']]) ? number_format($payments[$oneBl['id']], 2) : '&times;';
    $oneBl['firstAgent'] = !empty($oneBl['agent_info']) ? reset($oneBl['agent_info']) : null;
    
    $sortedEntries[] = $oneBl;
    $i++;
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

                    $count_sql = "SELECT COUNT(id) AS total FROM `general_loading` WHERE JSON_SEARCH(agent_info, 'one', NULL, NULL, '$.*.ag_id') IS NOT NULL";
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
                <input type="number" name="p_id" value="<?php echo $p_sr; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
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
    <div class="table-responsive mt-4">
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
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sortedEntries as $entry) {
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
                        <td class="<?= $entry['row_color']; ?> bill-status fw-bold"><?= $entry['grandTotal']; ?></td>
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
                url: 'ajax/viewAgentPaymentsForm.php',
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
<?php
if (isset($_POST['SubmitBill'])) {
    $bl_id = mysqli_real_escape_string($connect, $_POST['bl_id']);
    $edit = $_POST['edit'];
    $update = empty($edit) ? false : true;
    $Bill = mysqli_fetch_assoc(fetch('agent_payments', ['bl_id' => $bl_id]));
    $BL = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));
    $attachments = !empty($_FILES['attachments']['name'][0])
        ? upload_files($_FILES['attachments'])
        : json_encode([]);
    $data = [
        'bl_id' => $bl_id,
        'bl_no' => $BL['bl_no'],
        'bill_no' => $_POST['bill_no'],
        'bill_date' => $_POST['bill_date'],
        'bill_details' => $_POST['bill_details'],
        'attachments' => $attachments
    ];
    $key = $BL['bl_no'] . '~' . $_POST['sr'];
    $entry = [
        'sr' => $_POST['sr'],
        'details' => $_POST['details'],
        'quantity' => $_POST['quantity'],
        'rate' => $_POST['rate'],
        'amount' => $_POST['amount'],
        'tax_percent' => $_POST['tax_percent'],
        'tax_amount' => $_POST['tax_amount'],
        'final_amount' => $_POST['final_amount']
    ];
    if (!$Bill) {
        $data['bill_entries'] = json_encode([
            $key => $entry
        ]);
        $done = insert('agent_payments', $data);
    } else {
        $entries = json_decode($Bill['bill_entries'], true);
        if ($update) {
            $key = $edit;
        }
        if (!isset($entries[$key])) {
            $entries[$key] = [];
        }
        $entries[$key] = array_merge($entries[$key], $entry);
        $data['bill_entries'] = json_encode($entries);
        $done = update('agent_payments', $data, ['bl_id' => $bl_id]);
    }
    if ($done) {
        message('success', '', 'Success!');
    }
}
if (isset($_POST['TransferBillToAdmin'])) {
    $bl_id = $_POST['bl_id'];
    $BL = mysqli_fetch_assoc(fetch('general_loading', ['id' => $bl_id]));
    $Bill = mysqli_fetch_assoc(fetch('agent_payments', ['bl_id' => $bl_id]));
    $BL['loading_info'] = json_decode($BL['loading_info'], true);
    $Bill['transfer_info'] = json_decode($Bill['loading_info'] ?? '[]', true);
    $BL['loading_info']['transferred_to_admin'] = true;
    $Bill['transfer_info'] = array_merge($Bill['transfer_info'], [
        'transferred_to_admin' => true,
        'total_amount' => $_POST['total_amount'],
        'total_tax_amount' => $_POST['total_tax_amount'],
        'total_final_amount' => $_POST['total_final_amount']
    ]);
    $BL['loading_info'] = json_encode($BL['loading_info']);
    $Bill['transfer_info'] = json_encode($Bill['transfer_info']);
    $done = update('general_loading', $BL, ['id' => $bl_id]);
    $done = update('agent_payments', $Bill, ['bl_id' => $bl_id]);
    if ($done) {
        message('success', '', 'Success!');
    }
}
if (isset($_GET['bl_id']) && is_numeric($_GET['bl_id']) && isset($_GET['view']) && $_GET['view'] == 1) {
    $bl_id = mysqli_real_escape_string($connect, $_GET['bl_id']);
    echo "<script>jQuery(document).ready(function ($) {  $('#KhaataDetails').modal('show');});</script>";
    echo "<script>jQuery(document).ready(function ($) {  viewPurchase($bl_id); });</script>";
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