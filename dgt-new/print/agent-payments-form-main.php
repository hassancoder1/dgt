<?php
$page_title = 'Agent Payments Form';
$pageURL = 'agent-payments-form-main';
require("../connection.php");
$remove = $start_print = $end_print = $type = $acc_no = $p_sr = $truck_number = $sea_road = $billStatus = $billEntryNoSearch = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 25;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading` WHERE JSON_EXTRACT(gloading_info, '$.parent_id') IS NULL";
$conditions = ["JSON_EXTRACT(agent_details, '$.agent_exist') = 'yes'"];
$print_filters = [];
$user = $_SESSION['username'];
if ($_GET) {
    $remove = removeFilter('agent-payments-form-main');
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
        $conditions[] = "JSON_EXTRACT(agent_details, '$.boe_no')='$billEntryNoSearch'";
    }
    if (!empty($_GET['truck_number'])) {
        $truck_number = mysqli_real_escape_string($connect, $_GET['truck_number']);
        $print_filters[] = 'truck_number=' . $truck_number;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.truck_number') = '$truck_number'";
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
$query_string = str_replace('%20', '+', $query_string);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$count_sql = "SELECT COUNT(id) AS total FROM `general_loading`" . (count($conditions) > 0 ? " WHERE " . implode(' AND ', $conditions) : "");
$count_result = mysqli_query($connect, $count_sql);
$total_pages = ceil(mysqli_fetch_assoc($count_result)['total'] / $results_per_page);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Payments Form <?= $page >= 1 ? " - Page $page" : ''; ?></title>
    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
    include '../assets/fonts/lexend.css';
    echo "*{font-family:'Lexend',serif;}";
    echo "</style>";
    ?>
</head>

<body class="mx-2">
    <div class="bg-white mt-3">
        <div class="d-flex justify-content-between align-items-center w-100">
            <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
                Agent Payments Form
                <span class="text-muted" style="font-size: 12px; display: block;">
                    <?php
                    $applied_filters = [];
                    if ($p_sr) $applied_filters[] = "P# $p_sr";
                    if ($date_type) $applied_filters[] = "Date Type: " . ucfirst($date_type);
                    if ($start_print && $end_print) $applied_filters[] = "From $start_print to $end_print";
                    if ($type) $applied_filters[] = "Purchase Type: $type";
                    if ($sea_road) $applied_filters[] = "Sea/Road: $sea_road";
                    if ($billEntryNoSearch) $applied_filters[] = "Bill Entry Search: $billEntryNoSearch";
                    if ($truck_number) $applied_filters[] = "L.Truck No: $truck_number";
                    if ($billStatus) $applied_filters[] = "Bill Status: $billStatus";
                    if ($acc_no) $applied_filters[] = "Acc No: $acc_no";
                    if (count($applied_filters) > 0) {
                        echo " | Applied Filters: " . implode(", ", $applied_filters);
                    }
                    ?>
                    <?php if (isset($page)) { ?>
                        | Page: <?php echo $page; ?>
                    <?php } ?>
                </span>
            </h1>
            <div class="d-flex gap-2">
                <div>
                    <button class="btn btn-sm btn-dark" onclick="window.location.href = '/agent-payments-form'"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="dropdown">
                    <button class="btn btn-success btn-sm" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-nowrap">
                        <?php if (SuperAdmin()): ?>
                            <th>P#+Bill#</th>
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
                        $SuperCode = $rowColor . ' pointer"';
                    ?>

                        <tr class="text-nowrap">
                            <?php if (!SuperAdmin()): ?>
                                <td class="<?= $rowColor; ?>"><?= $row_count + 1; ?></td>
                            <?php endif; ?>
                            <td class="text-uppercase <?= $SuperCode; ?>"><b><?= SuperAdmin() ? $SingleLoading['type'] . "#" . $SingleLoading['p_sr'] . " ($currentBillNumber)" : $SingleLoading['bl_no']; ?></b></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_acc_no']; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_id']; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['ag_name']; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['boe_no'] ?? ''; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['boe_date'] ?? ''; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['pick_up_date'] ?? ''; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['waiting_days'] ?? ''; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['return_date'] ?? ''; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['transporter_name'] ?? ''; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['truck_number'] ?? ''; ?></td>
                            <td class="<?= $rowColor; ?>"><?= $agentDetails['driver_name'] ?? ''; ?></td>
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

        function getQueryParameter(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
    </script>
</body>

</html>