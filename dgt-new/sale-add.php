<?php $page_title = 'Sale Entry';
$back_page_url = 'sales';
$pageURL = "sale-add";
include("header.php");
$id = $item_id = 0;
if (!isset($_GET['type'])) {
    echo "<script>window.location.href='sales';</script>";
}
$prepareLoadingReport = '';
$prepareBankReport = '';
$prepareGoodsReport = '';
$preparePaymentReport = '';
global $userId, $userName, $branchId;
$query = "SELECT COALESCE(MAX(sr), 0) + 1 AS next_sr FROM transactions WHERE p_s = 's'";
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_assoc($result);
$next_sr = $row['next_sr'] ?? 1;
$_fields = [
    'sr' => $next_sr,
    'username' => $userName,
    'branch_id' => $branchId,
    'p_s' => '',
    'type' => '',
    'active' => 1,
    'locked' => 0,
    '_date' => date('Y-m-d'),
    'country' => '',
    'delivery_terms' => '',
    'action' => 'insert',
    'dr_acc' => '',
    'dr_acc_name' => '',
    'dr_acc_details' => '',
    'dr_acc_id' => 0,
    'dr_acc_kd_id' => 0,
    'cr_acc' => '',
    'cr_acc_name' => '',
    'cr_acc_details' => '',
    'cr_acc_id' => 0,
    'cr_acc_kd_id' => 0,
    'transaction_accounts_dr_id' => 0,
    'transaction_accounts_cr_id' => 0,
    'show_in' => ['vat' => $_GET['type'] === 'local' ? 'yes' : 'no', 'loading' => 'no', 'warehouse' => 'no', 'stock' => 'yes']
];

$sea_road = [
    'sea_road' => 'sea',
    'l_country_road' => '',
    'l_border_road' => '',
    'l_date_road' => date('Y-m-d'),
    'truck_container' => '',
    'r_country_road' => '',
    'r_border_road' => '',
    'r_date_road' => date('Y-m-d'),
    'd_date_road' => date('Y-m-d'),
    'is_loading' => 0,
    'l_country' => '',
    'l_port' => '',
    'l_date' => date('Y-m-d'),
    'ctr_name' => '',
    'is_receiving' => 0,
    'r_country' => '',
    'r_port' => '',
    'r_date' => date('Y-m-d'),
    'arrival_date' => date('Y-m-d'),
    'report' => '',
    'route' => '',
    'truck_no' => '',
    'truck_name' => '',
    'loading_warehouse' => '',
    'receiving_warehouse' => '',
    'warehouse_tranfer' => '',
    'loading_company_name' => '',
    'receiving_company_name' => '',
    'loading_date' => '',
    'receiving_date' => ''
];
$bank_details = ['acc_no' => '', 'acc_name' => '', 'company' => '', 'iban' => '', 'branch_code' => '', 'currency' => '', 'country' => '', 'state' => '', 'city' => '', 'address' => '', 'indexes4' => [], 'vals4' => []];
$NP_details = ['np_acc' => '', 'np_acc_name' => '', 'np_acc_id' => '', 'np_acc_kd_id' => '', 'np_acc_details' => '', 'notifyPartyDetailsSubmit' => '', 'hidden_id' => ''];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    if (!recordExists('transactions', ['id' => $id])) {
        messageNew('warning', $pageURL, 'Something went wrong!');
    }
    $dr_record = getTransactionAccounts($id, 'sale', 'dr');
    $cr_record = getTransactionAccounts($id, 'sale', 'cr');
    $record['show_in'] = json_decode(!empty($record['show_in']) ? $record['show_in'] : '{"vat":"no","loading":"no","warehouse":"no","stock":"yes"}', true);
    $_fields = [
        'sr' => $record['sr'],
        'username' => userName($record['created_by']),
        'branch_id' => $record['branch_id'],
        'p_s' => $record['p_s'],
        'type' => $record['type'],
        'active' => $record['active'],
        'locked' => $record['locked'],
        '_date' => $record['_date'],
        'country' => $record['country'],
        'delivery_terms' => $record['delivery_terms'],
        'action' => 'update',
        'dr_acc' => $dr_record['acc'],
        'dr_acc_name' => $dr_record['acc_name'],
        'dr_acc_details' => $dr_record['details'],
        'dr_acc_id' => $dr_record['acc_id'],
        'dr_acc_kd_id' => $dr_record['acc_kd_id'],
        'cr_acc' => $cr_record['acc'],
        'cr_acc_name' => $cr_record['acc_name'],
        'cr_acc_details' => $cr_record['details'],
        'cr_acc_id' => $cr_record['acc_id'],
        'cr_acc_kd_id' => $cr_record['acc_kd_id'],
        'transaction_accounts_dr_id' => $dr_record['id'],
        'transaction_accounts_cr_id' => $cr_record['id'],
        'show_in' => $record['show_in'] ?? ['loading' => 'no', 'vat' => 'no']
    ];
    $_fields = transactionSingle($id);
    $_fields['show_in'] = $record['show_in'] ?? ['loading' => 'no', 'vat' => 'no'];
    $_fields['delivery_terms'] = $record['delivery_terms'];
    $sea_road = [];
    if (!empty($record['sea_road'])) {
        $sea_road = json_decode($record['sea_road'], true);
    } else {
        $sea_route_key = $_GET['type'] === 'local' ? 'route' : 'sea_road';
        $sea_route_value = $_GET['type'] === 'local' ? 'local' : 'sea';
        $sea_road = [
            $sea_route_key => $sea_route_value,
            'l_country_road' => '',
            'l_border_road' => '',
            'l_date_road' => date('Y-m-d'),
            'truck_container' => '',
            'r_country_road' => '',
            'r_border_road' => '',
            'r_date_road' => date('Y-m-d'),
            'd_date_road' => date('Y-m-d'),
            'is_loading' => 0,
            'l_country' => '',
            'l_port' => '',
            'l_date' => date('Y-m-d'),
            'ctr_name' => '',
            'is_receiving' => 0,
            'r_country' => '',
            'r_port' => '',
            'r_date' => date('Y-m-d'),
            'arrival_date' => date('Y-m-d'),
            'report' => '',
            // 'route' => '',
            'truck_no' => '',
            'truck_name' => '',
            'loading_warehouse' => '',
            'receiving_warehouse' => '',
            'warehouse_tranfer' => '',
            'loading_company_name' => '',
            'receiving_company_name' => '',
            'loading_date' => '',
            'receiving_date' => '',
            'transfer_date' => '',
            'port_name' => '',
            'launchnumber' => ''
        ];
    }

    $myid = $_GET['id'];

    $query = "SELECT 
            COALESCE(MAX(sr), 0) + 1 AS next_sr, 
            (SELECT AUTO_INCREMENT 
             FROM information_schema.tables 
             WHERE table_name = 'transaction_items' 
             AND table_schema = DATABASE()) AS next_id 
          FROM transaction_items 
          WHERE p_s = 's' AND parent_id='$myid'";
    $result = mysqli_query($connect, $query);
    $row = mysqli_fetch_assoc($result);
    $next_items_sr = $row['next_sr'] ?? 1;
    $next_items_id = $row['next_id'] ?? 1;
    $item_fields = ['p_s' => 's', 'sr' => $next_items_sr, 'quality_report' => '', 'goods_id' => 0, 'size' => '', 'brand' => '', 'origin' => '', 'qty_name' => '', 'qty_no' => 0, 'qty_kgs' => 0, 'total_kgs' => 0, 'empty_kgs' => 0, 'total_qty_kgs' => 0, 'net_kgs' => 0, 'divide' => '', 'weight' => 0, 'total' => 0, 'price' => '', 'currency1' => '', 'rate1' => 0, 'amount' => 0, 'currency2' => 'AED', 'rate2' => '', 'opr' => '*', 'final_amount' => 0, 'tax_percent' => '', 'tax_amount' => '', 'total_with_tax' => '', 'show_in_vat' => $record['type'] === 'local' ? 'yes' : 'no'];
    if (isset($_GET['item_id']) && $_GET['item_id'] > 0) {
        $item_id = mysqli_real_escape_string($connect, $_GET['item_id']);
        $records2 = fetch('transaction_items', array('id' => $item_id));
        $record2 = mysqli_fetch_assoc($records2);
        $item_fields = ['p_s' => $record2['p_s'], 'sr' => $record2['sr'], 'quality_report' => $record2['quality_report'], 'allotment_name' => $record2['allotment_name'], 'goods_id' => $record2['goods_id'], 'size' => $record2['size'], 'brand' => $record2['brand'], 'origin' => $record2['origin'], 'qty_name' => $record2['qty_name'], 'qty_no' => $record2['qty_no'], 'qty_kgs' => $record2['qty_kgs'], 'total_kgs' => $record2['total_kgs'], 'empty_kgs' => $record2['empty_kgs'], 'total_qty_kgs' => $record2['total_qty_kgs'], 'net_kgs' => $record2['net_kgs'], 'divide' => $record2['divide'], 'weight' => $record2['weight'], 'total' => $record2['total'], 'price' => $record2['price'], 'currency1' => $record2['currency1'], 'rate1' => $record2['rate1'], 'amount' => $record2['amount'], 'currency2' => $record2['currency2'], 'rate2' => $record2['rate2'], 'opr' => $record2['opr'], 'final_amount' => $record2['final_amount'], 'tax_percent' => $record2['tax_percent'], 'tax_amount' => $record2['tax_amount'], 'total_with_tax' => $record2['total_with_tax']];
    }

    $bank_details = json_decode(decodeSpecialCharacters($record['third_party_bank']), true);
    $NP_details = json_decode($record['notify_party_details']);
    if (!empty($NP_details)) {
        $keys = ['np_acc', 'np_acc_name', 'np_acc_id', 'np_acc_kd_id', 'np_acc_details', 'notifyPartyDetailsSubmit', 'hidden_id'];
        $NP_details = array_filter(get_object_vars($NP_details), fn($key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY);
    }
}
echo '<script>let saleReports = [];</script>';
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-white border-bottom border-warning shadow-sm py-2 px-3 d-flex align-items-center justify-content-between">
        <!-- Page Title -->
        <h5 class="fw-bold text-uppercase m-0"><?php echo $page_title; ?></h5>

        <!-- Actions -->
        <div class="d-flex align-items-center gap-2">
            <?php echo backUrl($back_page_url); ?>

            <!-- New Button with Dropdown -->
            <div class="dropdown">
                <button class="btn btn-primary btn-sm d-flex align-items-center gap-1" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    <i class="fas fa-plus"></i> New
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                    <?php
                    // Fetch types dynamically
                    $static_types = $connect->query("SELECT * FROM static_types WHERE type_for IN ('ps_types', 's_types')");
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        echo '<li><a class="dropdown-item" href="purchase-add?type=' . urlencode($static_type['type_name']) . '">
                                <i class="fas fa-file-alt me-2"></i> ' . htmlspecialchars($static_type['details']) . '
                              </a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card mb-2 border">
            <div class="card-body p-3">
                <form method="post" class="collapse show" id="collapseFirst">
                    <input type="hidden" name="sr" value="<?= $_fields['sr']; ?>">
                    <div class="d-flex gap-3 flex-wrap">
                        <!-- Purchase Section -->
                        <div class="flex-fill bg-light p-3 rounded" style="max-width: 24%;">
                            <h6 class="fw-bold text-danger mb-3">Purchase Details</h6>
                            <div class="mb-2">
                                <label for="dr_acc" class="form-label text-danger fw-semibold">Cr.A/C (PURCHASE)</label>
                                <input type="text" id="dr_acc" name="dr_acc" class="form-control form-control-sm" required value="<?= $_fields['dr_acc']; ?>">
                                <input type="text" id="dr_acc_name" name="dr_acc_name" class="form-control form-control-sm mt-1" value="<?= $_fields['dr_acc_name']; ?>" readonly tabindex="-1">
                            </div>
                            <input type="hidden" name="dr_acc_id" id="dr_acc_id" value="<?= $_fields['dr_acc_id']; ?>">
                            <input type="hidden" name="transaction_accounts_dr_id" id="transaction_accounts_dr_id" value="<?= $_fields['transaction_accounts_dr_id']; ?>">
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <label for="dr_acc_kd_id" class="form-label">Company</label>
                                    <select class="form-select form-select-sm" name="dr_acc_kd_id" id="dr_acc_kd_id">
                                        <option hidden value="">Select Company</option>
                                        <?php
                                        $run_query = fetch('khaata_details', array('khaata_id' => $_fields['dr_acc_id'], 'type' => 'company'));
                                        while ($row = mysqli_fetch_array($run_query)) {
                                            $row_data = json_decode($row['json_data']);
                                            $sel_kd1 = $row['id'] == $_fields['dr_acc_kd_id'] ? 'selected' : '';
                                            echo '<option ' . $sel_kd1 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="dr_acc_details" id="dr_acc_details" placeholder="Company Details" rows="<?= $_fields['dr_acc_details'] ? '6' : '3'; ?>"><?= $_fields['dr_acc_details']; ?></textarea>
                            </div>
                        </div>

                        <!-- Sale Section -->
                        <div class="flex-fill bg-light p-3 rounded" style="max-width: 24%;">
                            <h6 class="fw-bold text-success mb-3">Sale Details</h6>
                            <div class="mb-2">
                                <label for="cr_acc" class="form-label text-success fw-bold">Dr.A/C (SALE)</label>
                                <input type="text" id="cr_acc" name="cr_acc" class="form-control form-control-sm" required value="<?= $_fields['cr_acc']; ?>">
                                <input type="text" id="cr_acc_name" name="cr_acc_name" class="form-control form-control-sm mt-1" value="<?= $_fields['cr_acc_name']; ?>" readonly tabindex="-1">
                            </div>
                            <input type="hidden" name="cr_acc_id" id="cr_acc_id" value="<?= $_fields['cr_acc_id']; ?>">
                            <input type="hidden" name="transaction_accounts_cr_id" id="transaction_accounts_cr_id" value="<?= $_fields['transaction_accounts_cr_id']; ?>">
                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <label for="cr_acc_kd_id" class="form-label">Company</label>
                                    <select class="form-select form-select-sm" name="cr_acc_kd_id" id="cr_acc_kd_id">
                                        <option hidden value="">Select Company</option>
                                        <?php
                                        $run_query = fetch('khaata_details', array('khaata_id' => $_fields['cr_acc_id'], 'type' => 'company'));
                                        while ($row = mysqli_fetch_array($run_query)) {
                                            $row_data = json_decode($row['json_data']);
                                            $sel_kd2 = $row['id'] == $_fields['cr_acc_kd_id'] ? 'selected' : '';
                                            echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="cr_acc_details" id="cr_acc_details" placeholder="Company Details" rows="<?= $_fields['cr_acc_details'] ? '6' : '3'; ?>"><?= $_fields['cr_acc_details']; ?></textarea>
                            </div>
                        </div>
                        <div class="flex-fill bg-light p-3 rounded" style="max-width:24%;">
                            <h6 class="fw-bold text-secondary mb-2">Details Added:</h6>
                            <ul class="details-list list-unstyled mb-2">
                                <?php
                                if (!empty($sea_road['sea_road'])) {
                                    if (in_array($_GET['type'], ['booking', 'commission'])) {
                                        $routeText = ($sea_road['sea_road'] == 'sea') ? 'Sea' : 'Road';
                                    }
                                } elseif (!empty($sea_road['lwl'])) {
                                    if ($sea_road['lwl'] == 'local' && !empty($sea_road['loading_date'])) {
                                        $routeText = 'Loading';
                                    } elseif ($sea_road['lwl'] == 'warehouse') {
                                        $routeText = 'Warehouse';
                                    } elseif ($sea_road['lwl'] == 'launch') {
                                        $routeText = 'Launch';
                                    }
                                } else {
                                    $routeText = '';
                                }
                                // var_dump($sea_road);
                                ?>
                                <li class="<?= !empty($routeText) ? 'text-success' : 'text-danger'; ?> fw-bold d-flex align-items-center">
                                    <i class="fa <?= !empty($routeText) ? 'fa-check' : 'fa-times'; ?> me-2"></i>
                                    Routes <?= !empty($routeText) ? '(' . $routeText . ')' : ''; ?>
                                </li>

                                <?php
                                $payments = !empty($_fields['payment_details']) ? json_decode(json_encode($_fields['payment_details']), true) : null;
                                $paymentType = $payments ? ($payments['full_advance'] === 'advance' ? 'Advance' : ($payments['full_advance'] === 'full' ? 'Full' : 'Credit')) : '';
                                ?>
                                <li class="<?= !empty($payments) ? 'text-success' : 'text-danger'; ?> fw-bold d-flex align-items-center">
                                    <i class="fa <?= !empty($payments) ? 'fa-check' : 'fa-times'; ?> me-2"></i>
                                    Payments <?= !empty($payments) ? '(' . $paymentType . ')' : ''; ?>
                                </li>

                                <li class="<?= !empty($bank_details['bank_name']) ? 'text-success' : 'text-danger'; ?> fw-bold d-flex align-items-center">
                                    <i class="fa <?= !empty($bank_details['bank_name']) ? 'fa-check' : 'fa-times'; ?> me-2"></i>
                                    Third-Party Bank
                                </li>
                                <li class="<?= !empty($NP_details['np_acc']) ? 'text-success' : 'text-danger'; ?> fw-bold d-flex align-items-center">
                                    <i class="fa <?= !empty($NP_details['np_acc']) ? 'fa-check' : 'fa-times'; ?> me-2"></i>
                                    Notify Party
                                </li>
                            </ul>
                            <h6 class="fw-bold text-secondary mb-2">Reports</h6>
                            <ul class="details-list list-unstyled mb-2">
                                <?php
                                $purchase_reports = !empty($record['reports']) && $record['reports'] !== '[]' ? json_decode($record['reports'], true) : [];
                                $report_keys = ['payment_details', 'contract_details', 'loading_details', 'goods_details'];

                                foreach ($report_keys as $key) {
                                    $exists = !empty($purchase_reports[$key]);
                                ?>
                                    <li class="<?= $exists ? 'text-success' : 'text-danger'; ?> fw-bold d-flex align-items-center">
                                        <i class="fa <?= $exists ? 'fa-check' : 'fa-times'; ?> me-2"></i>
                                        <?= ucfirst(str_replace('_', ' ', $key)); ?>
                                    </li>
                                <?php } ?>
                            </ul>
                            <div>
                                <h6 class="fw-bold text-secondary mb-2">Transfer Options</h6>
                                <div class="d-flex justify-content-between gap-2">
                                    <div>
                                        <label class="form-label"><i class="fas fa-exchange-alt me-1"></i> VAT Transfer</label>
                                        <select class="form-select form-select-sm mb-2" name="show_in_vat">
                                            <option value="no" <?= $_fields['show_in']['vat'] === 'no' ? 'selected' : ''; ?>>No</option>
                                            <option value="yes" <?= $_fields['show_in']['vat'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label"><i class="fas fa-truck-loading me-1"></i> Loading Transfer</label>
                                        <select class="form-select form-select-sm mb-2" name="show_in_loading">
                                            <option value="no" <?= $_fields['show_in']['loading'] === 'no' ? 'selected' : ''; ?>>No</option>
                                            <option value="yes" <?= $_fields['show_in']['loading'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-fill" style="max-width:24%;">
                            <div class="bg-light p-3 rounded">
                                <h6 class="fw-bold text-secondary mb-3">Additional Details</h6>

                                <!-- Row 1: Sr# & User -->
                                <div class="row g-2 mb-2">
                                    <div class="col-md-6">
                                        <span><b>Sr#:</b> <?= $_fields['sr']; ?></span><br>
                                        <span><b>User:</b> <?= strtoupper($_fields['username']); ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="type" class="form-label"><b>Type</b></label>
                                        <select name="type" id="type" class="form-select form-select-sm">
                                            <option value="booking" <?= $_GET['type'] === 'booking' ? 'selected' : ''; ?>>Booking</option>
                                            <option value="local" <?= $_GET['type'] === 'local' ? 'selected' : ''; ?>>Local</option>
                                            <option value="commission" <?= $_GET['type'] === 'commission' ? 'selected' : ''; ?>>Commission</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Row 2: Type & Date -->
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label for="_date" class="form-label"><b>Date</b></label>
                                        <input type="date" value="<?= $_fields['_date']; ?>" id="_date" name="_date" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="branch_id" class="form-label"><b>Branch</b></label>
                                        <?php if (!SuperAdmin()) {
                                            $dsd = fetch('branches', ['id' => $_fields['branch_id']]);
                                            while ($b = mysqli_fetch_assoc($dsd)) { ?>
                                                <input type="hidden" name="branch_id" value="<?= $b['id']; ?>" />
                                                <p><b><?= strtoupper($b['b_code']); ?></b></p>
                                            <?php }
                                        } else { ?>
                                            <select id="branch_id" name="branch_id" class="form-select form-select-sm">
                                                <?php
                                                $branches = fetch('branches');
                                                while ($b = mysqli_fetch_assoc($branches)) {
                                                    $selected = ($b['id'] == $_fields['branch_id']) ? 'selected' : '';
                                                    echo "<option value='{$b['id']}' $selected>{$b['b_code']}</option>";
                                                }
                                                ?>
                                            </select>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- Row 4: Country & Delivery Terms -->
                                <div class="row g-2 mt-2">
                                    <div class="col-md-6">
                                        <label for="country" class="form-label"><b>Country</b></label>
                                        <input type="text" value="<?= $_fields['country']; ?>" id="country" name="country" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="delivery_terms" class="form-label"><b>Delivery Terms</b></label>
                                        <input type="text" value="<?= $_fields['delivery_terms'] ?? ''; ?>" id="delivery_terms" name="delivery_terms" class="form-control form-control-sm">
                                    </div>
                                </div>

                                <!-- Row 5: Other Details & Reports -->
                                <?php if (!empty($_fields['dr_acc_details']) || !empty($_fields['cr_acc_details']) || !empty($NP_details['np_acc_details'])): ?>
                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <label for="actionSelect" class="form-label"><b>Other Details</b></label>
                                            <select id="actionSelect" name="actionSelect" class="form-select form-select-sm">
                                                <option selected>Other Details</option>
                                                <option value="seaRoadBtn">Sea/Road</option>
                                                <option value="paymentsBtn">Payments</option>
                                                <option value="thirdPartyBankBtn">Third Party Bank</option>
                                                <option value="notifyParty">Notify Party</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="reportsSelect" class="form-label"><b>Reports</b></label>
                                            <select id="reportsSelect" name="reportsSelect" class="form-select form-select-sm">
                                                <option value="" selected disabled>Report Type</option>
                                                <option value="payment_details">Payment Details</option>
                                                <option value="goods_details">Goods Details</option>
                                                <option value="loading_details">Loading Details</option>
                                                <option value="contract_details">Contract Details</option>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Hidden ID -->
                                <input type="hidden" name="hidden_id" value="<?= $id ?>">

                                <!-- Submit Button -->
                                <button type="submit" name="saleSubmit" class="btn btn-dark btn-sm w-100 mt-3">Submit</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if ($item_id == 0) { ?>
    <div class="card mb-2">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>GOODS / SIZE / BRAND / ORIGIN</th>
                            <th>QTY</th>
                            <th>KGs</th>
                            <!-- <th>EMPTY</th> -->
                            <th>NET KGs</th>
                            <!-- <th>Wt.</th> -->
                            <th>TOTAL</th>
                            <th>PRICE</th>
                            <th>AMOUNT</th>
                            <?php if ($_GET['type'] !== 'local') { ?>
                                <th class="text-end">FINAL</th>
                            <?php } else {; ?>
                                <th>Tax%</th>
                                <th>Tax.Amt</th>
                                <th>Amt+Tax</th>
                            <?php } ?>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sr_details = 1;
                        $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = $total_tax_amount = $total_total_with_tax = 0;
                        $pur_d_q = fetch('transaction_items', array('parent_id' => $id));
                        while ($details = mysqli_fetch_assoc($pur_d_q)) {
                            $details_id = $details['id'];
                            echo '<tr>';
                            echo '<td>' . $details['sr'] . '</td>';
                            echo '<td><a href="' . $pageURL . '?id=' . $id . '&item_id=' . $details_id . '&type=' . $record['type'] . '" class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                            // echo '<td>' . $details['size'] . '</td>';
                            // echo '<td>' . $details['brand'] . '</td>';
                            // echo '<td>' . $details['origin'] . '</td>';
                            echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                            echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                            // echo '<td>' . round($details['total_qty_kgs'], 2) . '</td>';
                            echo '<td>' . round($details['net_kgs'], 2);
                            echo '<sub>' . $details['divide'] . '</sub>';
                            echo '</td>';
                            // echo '<td>' . $details['weight'] . '</td>';
                            echo '<td>' . $details['total'] . '</td>';
                            echo '<td>' . $details['price'] . '</td>';
                            echo '<td>' . round($details['amount'], 2);
                            echo '<sub>' . $details['currency1'] . '</sub>';
                            echo '</td>';
                            if ($_GET['type'] !== 'local') {
                                echo '<td class="text-end">' . round($details['final_amount'], 2);
                                echo '<sub>' . $details['currency2'] . '</sub>';
                            } else {
                                echo '<td>' . $details['tax_percent'] . "%";
                                echo '<td>' . $details['tax_amount'];
                                echo '<td>' . $details['total_with_tax'];
                            };
                            echo '</td>';
                            echo '<td>';
                            if (empty($p_data['khaata_tr1'])) {
                                $delete_msg = 'Are you sure to delete?';
                                echo '<form method="post" onsubmit="return confirm(\'' . $delete_msg . '\')"><input value="' . $id . '" name="p_id_delete" type="hidden"><input value="' . $details_id . '" name="pd_id_delete" type="hidden">';
                                echo '<button name="deletePDSubmit" type="submit" data-bs-toggle="tooltip" data-bs-title="Delete container" class="btn btn-sm btn-outline-danger py-0 px-1"><i class="fa fa-trash-alt"></i></button>';
                                echo '</form>';
                            }
                            echo '</td>';
                            echo '</tr>';
                            $sr_details++;
                            $qty_no += $details['qty_no'];
                            $qty_kgs += $details['qty_kgs'];
                            $total_kgs += $details['total_kgs'];
                            $total_qty_kgs += $details['total_qty_kgs'];
                            $net_kgs += $details['net_kgs'];
                            $total += $details['total'];
                            $amount += $details['amount'];
                            $final_amount += $details['final_amount'];
                            $total_tax_amount += (float)$details['tax_amount'];
                            $total_total_with_tax += (float)$details['total_with_tax'];
                        }
                        $prepareGoodsReport = '';
                        if ($qty_no > 0) {
                            echo '<tr>';
                            echo '<th colspan="2"></th>';
                            echo '<th class="fw-bold">' . $qty_no . '</th>';
                            echo '<th class="fw-bold">' . round($total_kgs, 2) . '</th>';
                            // echo '<th class="fw-bold">' . round($total_qty_kgs, 2) . '</th>';
                            echo '<th class="fw-bold">' . round($net_kgs, 2) . '</th>';
                            // echo '<th colspan="1"></th>';
                            echo '<th class="fw-bold">' . round($total, 2) . '</th>';
                            echo '<th></th>';
                            echo '<th class="fw-bold">' . round($amount, 2) . '</th>';
                            if ($_GET['type'] === 'local') {
                                echo '<th></th>';
                                echo '<th class="fw-bold">' . round($total_tax_amount, 2) . '</th>';
                                echo '<th class="fw-bold">' . round($total_total_with_tax, 2) . '</th>';
                            }
                            if ($_GET['type'] !== 'local') {
                                echo '<th class="fw-bold text-end">' . round($final_amount, 2) . '</th>';
                            }
                            echo '<th></th>';
                            echo '</tr>';
                            $prepareGoodsReport .= "Total Quantity: $qty_no, ";
                            $prepareGoodsReport .= "Total KGs: " . round($total_kgs, 2) . ", ";
                            $prepareGoodsReport .= "Total Quantity KGs: " . round($total_qty_kgs, 2) . ", ";
                            $prepareGoodsReport .= "Total Net KGs: " . round($net_kgs, 2) . ", ";
                            $prepareGoodsReport .= "Total: " . round($total, 2) . ", ";
                            $prepareGoodsReport .= "Total Amount: " . round($amount, 2) . ", ";
                            if ($_GET['type'] !== 'local') {
                                $prepareGoodsReport .= "Final Amount: " . round($final_amount, 2) . ", ";
                            }
                        }
                        echo '<script>saleReports[\'goods_details\'] = "' . $prepareGoodsReport . '";</script>';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>
<div class="card">
    <div id="availableQuantitiesTable" class="mt-4"></div>
</div>
<?php if ($id > 0) { ?>
    <div class="card mb-3">
        <div class="card-body">
            <form method="post" class="row mx-auto">
                <strong class="text-muted mb-2">
                    <i class="fas fa-hashtag me-1"></i>
                    SR#: <span class="text-dark"><?= $item_fields['sr'] ?></span>
                    <input type="hidden" name="sr" value="<?= $item_fields['sr']; ?>">
                </strong>
                <div class="px-1" style="width:15.5%;">
                    <div class="mt-1">
                        <label for="allotment_name" class="form-label">
                            <i class="fas fa-signature me-1"></i>
                            Allot Name
                        </label>
                        <!-- <input type="text" class="form-control form-control-sm" id="allotment_name" name="allotment_name" value="<?= $item_fields['allotment_name'] ?? ''; ?>" required> -->
                        <select name="allotment_name" id="allotment_name" class="form-select form-select-sm" required>
                            <option value="" disabled selected> Select </option>
                            <?php
                            if (isset($_GET['item_id'])) {
                                echo '<option selected value="' . $item_fields['allotment_name'] . '">' . $item_fields['allotment_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>


                    <div class="mt-1">
                        <label for="size" class="form-label">
                            <i class="fas fa-ruler me-1"></i>
                            Size
                        </label>
                        <select id="size" class="form-select form-select-sm" name="size" required>
                            <option hidden value="">Select</option>
                            <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                            while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                $size_selected = $size_s['size'] == $item_fields['size'] ? 'selected' : '';
                                echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                            } ?>
                        </select>
                    </div>

                    <div class="mt-1">
                        <label for="origin" class="form-label">
                            <i class="fas fa-globe me-1"></i>
                            Origin
                        </label>
                        <select id="origin" class="form-select form-select-sm" name="origin" required>
                            <option hidden value="">Select</option>
                            <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                            while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                $size_selected = $size_s['origin'] == $item_fields['origin'] ? 'selected' : '';
                                echo '<option ' . $size_selected . ' value="' . $size_s['origin'] . '">' . $size_s['origin'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="px-1" style="width:15.5%;">
                    <div class="mt-1">
                        <label for="goods_id" class="form-label">
                            <i class="fas fa-boxes me-1"></i>
                            Goods
                        </label>
                        <select class="form-select form-select-sm" name="goods_id" id="goods_id" required>
                            <option hidden value="">Select</option>
                            <?php $goods = fetch('goods');
                            while ($good = mysqli_fetch_assoc($goods)) {
                                $g_selected = $good['id'] == $item_fields['goods_id'] ? 'selected' : '';
                                echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                    <div class="mt-1">
                        <label for="brand" class="form-label">
                            <i class="fas fa-tag me-1"></i>
                            Brand
                        </label>
                        <!-- <input id="brand" type="text" class="form-control form-control-sm" name="brand" value="<?= $item_fields['brand'] ?? ''; ?>" required> -->
                        <select name="brand" id="brand" class="form-select form-select-sm" required>
                            <option hidden value="">Select</option>
                            <?php $goods_brands = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                            while ($size_s = mysqli_fetch_assoc($goods_brands)) {
                                $brand_selected = $size_s['brand'] == $item_fields['brand'] ? 'selected' : '';
                                echo '<option ' . $brand_selected . ' value="' . $size_s['brand'] . '">' . $size_s['brand'] . '</option>';
                            } ?>
                        </select>
                        </select>
                    </div>
                    <!-- Price Type -->
                    <div class="mt-1">
                        <label for="price" class="form-label">
                            <i class="fas fa-tag me-1"></i>
                            Price Type
                        </label>
                        <select id="price" class="form-select form-select-sm" name="price" required>
                            <?php $prices = ['P/TON' => 'P/TON', 'P/KGs' => 'P/KG', 'P/CARTON' => 'P/CARTON', 'P/PP BAGS' => 'P/PP BAGS'];
                            foreach ($prices as $item => $val) {
                                $pr_sel = $item_fields['price'] == $val ? 'selected' : '';
                                echo '<option ' . $pr_sel . ' value="' . $val . '">' . $item . '</option>';
                            } ?>
                        </select>
                    </div>

                </div>
                <div class="px-1" style="width:15.5%;">
                    <div class="mt-1">
                        <label for="qty_name" class="form-label">
                            <i class="fas fa-cube me-1"></i>
                            Qty Name
                        </label>
                        <input id="qty_name" type="text" class="form-control form-control-sm" name="qty_name" value="<?= $item_fields['qty_name'] ?? ''; ?>" required>
                    </div>


                    <div class="mt-1">
                        <label for="empty_kgs" class="form-label">
                            <i class="fas fa-weight me-1"></i>
                            Empty KGS
                        </label>
                        <input type="number" id="empty_kgs" class="form-control form-control-sm" name="empty_kgs" value="<?= $item_fields['empty_kgs'] ?? ''; ?>" required step="0.01">
                    </div>

                    <!-- Currency 1 -->
                    <div class="mt-1">
                        <label for="currency1" class="form-label">
                            <i class="fas fa-money-bill-wave me-1"></i>
                            Currency 1
                        </label>
                        <select id="currency1" class="form-select form-select-sm" name="currency1" required>
                            <option selected hidden disabled value="">Select</option>
                            <?php $currencies = fetch('currencies');
                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                $crr_sel2 = $crr['name'] == $item_fields['currency1'] ? 'selected' : '';
                                echo '<option ' . $crr_sel2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                            } ?>
                        </select>
                    </div>


                </div>
                <div class="px-1" style="width:15.5%;">
                    <div class="mt-1">
                        <label for="qty_no" class="form-label">
                            <i class="fas fa-hashtag me-1"></i>
                            Quantity No
                        </label>
                        <input id="qty_no" type="number" class="form-control form-control-sm" name="qty_no" value="<?= $item_fields['qty_no'] ?? ''; ?>" required step="0.01">
                    </div>
                    <!-- Divide Type -->
                    <div class="mt-1">
                        <label for="divide" class="form-label">
                            <i class="fas fa-divide me-1"></i>
                            Divide Type
                        </label>
                        <select id="divide" class="form-select form-select-sm" name="divide" required>
                            <?php $divides = ['D/TON' => 'D/TON', 'D/KGs' => 'D/KG', 'D/CARTON' => 'D/CARTON', 'D/PP BAGS' => 'D/PP BAGS'];
                            foreach ($divides as $item => $val) {
                                $d_sel = $item_fields['divide'] == $val ? 'selected' : '';
                                echo '<option ' . $d_sel . ' value="' . $val . '">' . $item . '</option>';
                            } ?>
                        </select>
                    </div>
                    <!-- Rate 1 -->
                    <div class="mt-1">
                        <label for="rate1" class="form-label">
                            <i class="fas fa-percentage me-1"></i>
                            Rate 1
                        </label>
                        <input id="rate1" type="number" class="form-control form-control-sm" name="rate1" value="<?= $item_fields['rate1'] ?? ''; ?>" required step="0.01">
                    </div>
                    <?php if ($_GET['type'] !== 'local') { ?>
                        <!-- Currency 2 -->
                        <div class="mt-1">
                            <label for="currency2" class="form-label">
                                <i class="fas fa-money-bill-wave me-1"></i>
                                Currency 2
                            </label>
                            <select id="currency2" class="form-select form-select-sm" name="currency2" required>
                                <option selected hidden disabled value="">Select</option>
                                <?php $currencies = fetch('currencies');
                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                    $crr_sel = $crr['name'] == $item_fields['currency2'] ? 'selected' : '';
                                    echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    <?php } else { ?>
                        <div class="mt-1">
                            <label for="tax_percent" class="form-label">
                                <i class="fas fa-percent me-1"></i>
                                Tax %
                            </label>
                            <input id="tax_percent" type="text" class="form-control form-control-sm" name="tax_percent" value="<?= $item_fields['tax_percent'] ?? ''; ?>" required>
                        </div>
                    <?php } ?>
                </div>
                <div class="px-1" style="width:15.5%;">
                    <div class="mt-1">
                        <label for="qty_kgs" class="form-label">
                            <i class="fas fa-balance-scale me-1"></i>
                            Quantity KGS
                        </label>
                        <input id="qty_kgs" type="number" class="form-control form-control-sm" name="qty_kgs" value="<?= $item_fields['qty_kgs'] ?? ''; ?>" required step="0.01">
                    </div>
                    <div class="mt-1">
                        <label for="weight" class="form-label">
                            <i class="fas fa-weight-hanging me-1"></i>
                            Weight
                        </label>
                        <input id="weight" type="number" class="form-control form-control-sm" name="weight" value="<?= $item_fields['weight'] ?? ''; ?>" required step="0.01">
                    </div>

                    <?php if ($_GET['type'] !== 'local') { ?>
                        <div class="mt-1">
                            <label for="opr" class="form-label">
                                <i class="fas fa-calculator me-1"></i>
                                Operator
                            </label>
                            <select id="opr" class="form-select form-select-sm" name="opr" required>
                                <?php $ops = ['[*]' => '*', '[/]' => '/'];
                                foreach ($ops as $opName => $op) {
                                    $op_sel = $item_fields['opr'] == $op ? 'selected' : '';
                                    echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                } ?>
                            </select>
                        </div>
                    <?php } else { ?>
                        <div class="mt-1">
                            <!-- <label for="total_with_tax" class="form-label">
                            <i class="fas fa-receipt me-1"></i>
                            Total With Tax
                        </label> -->
                            <input id="total_with_tax" type="hidden" class="form-control form-control-sm" name="total_with_tax" value="<?= $item_fields['total_with_tax'] ?? ''; ?>" required>
                        </div>
                    <?php } ?>
                    <?php if ($_GET['type'] !== 'local') { ?>
                        <!-- Rate 2 -->
                        <div class="mt-1">
                            <label for="rate2" class="form-label">
                                <i class="fas fa-percentage me-1"></i>
                                Rate 2
                            </label>
                            <input id="rate2" type="number" class="form-control form-control-sm" name="rate2" value="<?= $item_fields['rate2'] ?? ''; ?>" required step="0.01">
                        </div>
                    <?php } else { ?>
                        <div class="mt-1">
                            <label for="tax_amount" class="form-label">
                                <i class="fas fa-coins me-1"></i>
                                Tax Amount
                            </label>
                            <input id="tax_amount" type="text" class="form-control form-control-sm" name="tax_amount" value="<?= $item_fields['tax_amount'] ?? ''; ?>" required>
                        </div>
                    <?php } ?>
                </div>
                <div class="card border" style="width:22%;">
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-7">
                                <i class="fas fa-weight me-1"></i>
                                Total KGS:
                            </div>
                            <span class="col-5 text-end" id="total_kgs_span">0.00</span>

                            <div class="col-7">
                                <i class="fas fa-cube me-1"></i>
                                Total Qty KGS:
                            </div>
                            <span class="col-5 text-end" id="total_qty_kgs_span">0.00</span>
                            <div class="col-7">
                                <i class="fas fa-minus-circle me-1"></i>
                                NET KGS:
                            </div>
                            <span class="col-5 text-end" id="net_kgs_span">0.00</span>

                            <div class="col-7">
                                <i class="fas fa-calculator me-1"></i>
                                Total:
                            </div>
                            <span class="col-5 text-end" id="total_span">0.00</span>

                            <div class="col-7">
                                <i class="fas fa-coins me-1"></i>
                                Amount:
                            </div>
                            <span class="col-5 text-end" id="amount_span">0.00</span>
                            <?php if ($_GET['type'] !== 'local') { ?>
                                <div class="col-7 fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Final Amount:
                                </div>
                                <span class="col-5 text-end fw-bold" id="final_amount_span">0.00</span>
                            <?php } else { ?>
                                <div class="col-7 fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Amt+Tax:
                                </div>
                                <span class="col-5 text-end fw-bold" id="total_with_tax_span">0.00</span>
                            <?php } ?>
                        </div>
                        <div class="hidden-inputs">
                            <input value="<?= $item_fields['total_kgs'] ?? ''; ?>" id="total_kgs"
                                name="total_kgs" type="hidden">
                            <input value="<?= $item_fields['total_qty_kgs'] ?? ''; ?>" id="total_qty_kgs"
                                name="total_qty_kgs"
                                type="hidden">
                            <input value="<?= $item_fields['net_kgs'] ?? ''; ?>" id="net_kgs" name="net_kgs"
                                type="hidden">
                            <input value="<?= $item_fields['total'] ?? ''; ?>" id="total" name="total"
                                type="hidden">
                            <input value="<?= $item_fields['amount'] ?? ''; ?>" id="amount" name="amount"
                                type="hidden">
                            <input value="<?= $item_fields['final_amount'] ?? ''; ?>" id="final_amount"
                                name="final_amount" type="hidden">
                            <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="hidden_item_id" value="<?= $item_id; ?>">
                            <input type="hidden" name="purchased_item_id" id="purchased_item_id">
                            <input type="hidden" name="new_item_id" value="<?= $next_items_id; ?>">
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <?php echo $item_id > 0 ? backUrl('purchase-add?id=' . $id . '&type=' . $_GET['type']) : '';
                                ?>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-sm btn-primary w-100" id="recordSubmit" name="recordSubmit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 p-1" style="margin-top:-60px;">
                    <label for="quality_report" class="form-label">
                        <i class="fas fa-file-alt me-1"></i>
                        Quality Report
                    </label>
                    <textarea id="quality_report" class="form-control form-control-sm" rows="3" name="quality_report"><?= $item_fields['quality_report']; ?></textarea>
                </div>
            </form>
        </div>
    </div>
<?php }
if ($id > 0): ?>
    <div class="card mb-3" style="border: none;">
        <div class="card-body">
            <?php
            $prepareLoadingReport = '';
            $preparePaymentReport = '';
            $prepareBankReport = '';

            // Loading Report
            $sea_road['warehouse_transfer'] = $sea_road['warehouse_transfer'] ?? '';
            $sea_road['loading_company_name'] = $sea_road['loading_company_name'] ?? '';
            $sea_road['receiving_company_name'] = $sea_road['receiving_company_name'] ?? '';
            $sea_road['loading_date'] = $sea_road['loading_date'] ?? '';
            $sea_road['receiving_date'] = $sea_road['receiving_date'] ?? '';
            if (!empty($_fields['sea_road_array']) && $_GET['type'] !== 'local') {
                foreach ($_fields['sea_road_array'] as $key => $value) {
                    if (strpos($key, 'l_') === 0) {
                        $prepareLoadingReport .= sprintf(
                            "%s: %s, ",
                            is_array($value) ? $value[0] : strtoupper($key),
                            is_array($value) ? $value[1] : $value
                        );
                    }
                    if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0) {
                        $prepareLoadingReport .= sprintf(
                            "%s: %s, ",
                            $key === 'd_date_road' ? 'Arrival Date' : (is_array($value) ? $value[0] : strtoupper($key)),
                            is_array($value) ? $value[1] : $value
                        );
                    }
                }
            } elseif ($_GET['type'] === 'local' && $sea_road['loading_date'] !== '') {
                $prepareLoadingReport = "Warehouse Transfer: " . $sea_road['warehouse_transfer'] ?? '' . ", Loading Company: " . $sea_road['loading_company_name'] ?? '' . ", Receiving Company: " . $sea_road['receiving_company_name'] ?? '' . ", Loading Date: " . $sea_road['loading_date'] ?? '' . ", Receiving Date: " . $sea_road['receiving_date'] ?? '';
            }

            // Payment Report
            if (!empty(json_decode($record['payments'], true))) {
                $payments = $_fields['payment_details'];
                $total_amount = $_fields['items_sum']['sum_final_amount'] ?? 0;
                $percentage = $payments->pct_value ?? 0;
                $remaining_percentage = 100 - $percentage;
                $partial_amount1 = ($percentage / 100) * $total_amount;
                $partial_amount2 = ($remaining_percentage / 100) * $total_amount;

                $preparePaymentReport = "Type: " . ucfirst($payments->full_advance) . ", Total Amount: " . number_format($total_amount, 2) . ", ";

                if ($payments->full_advance === 'advance') {
                    $preparePaymentReport .= sprintf(
                        "Percent: %s, Advance Amount: %.2f, Remaining: %.2f",
                        $percentage . '%',
                        $partial_amount1,
                        $partial_amount2
                    );
                } elseif ($payments->full_advance === 'full') {
                    $preparePaymentReport .= "Full Payment on " . $payments->full_date . ", ";
                } elseif ($payments->full_advance === 'credit') {
                    $preparePaymentReport .= "Credit Payment on " . $payments->credit_date . ", ";
                }
            }

            // Bank Report
            if (!empty($bank_details['bank_name'])) {
                $prepareBankReport = sprintf(
                    "Bank Name: %s, Account Name: %s, IBAN: %s, Company: %s",
                    $bank_details['bank_name'] ?? 'N/A',
                    $bank_details['acc_name'] ?? 'N/A',
                    $bank_details['iban'] ?? 'N/A',
                    $bank_details['company'] ?? 'N/A'
                );
            }

            $purchase_reports = ["loading_details" => $prepareLoadingReport, "payment_details" => $preparePaymentReport];
            echo '<script>saleReports[\'loading_details\'] = "' . $prepareLoadingReport . '";';
            echo 'saleReports[\'payment_details\'] = "' . $preparePaymentReport . '";</script>';
            ?>
            <!-- <div class="row gx-3">
                        Sea/Road Details
                        <div class="col-md-3 col-sm-12 mb-3">
                            <h5>Sea/Road Details:</h5>
                            <p><?= $prepareLoadingReport; ?></p>
                        </div>

                        Payment Details
                        <div class="col-md-3 col-sm-12 mb-3">
                            <h5>Payment Details:</h5>
                            <p><?= $preparePaymentReport; ?></p>
                        </div>

                        Bank Details
                        <div class="col-md-3 col-sm-12 mb-3">
                            <h5>Bank Details:</h5>
                            <p><?= $prepareBankReport; ?></p>
                        </div>
                    </div> -->



            <!-- Purchase Reports Section -->
            <div class="col-md-12 mt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Purchase Reports</h4>
                </div>

                <?php
                $purchase_reports = [];
                if (!empty($record['reports']) && $record['reports'] !== '[]') {
                    $purchase_reports = json_decode($record['reports'], true);
                }

                if (!empty($purchase_reports)) {
                    foreach ($purchase_reports as $key => $value): ?>
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title fw-bold text-primary mb-2">
                                            <?= ucwords(str_replace('_', ' ', $key)); ?>
                                        </h5>
                                        <p class="card-text text-muted mb-0">
                                            <?= nl2br(htmlspecialchars($value)); ?>
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="?deletePurchaseReport=<?= urlencode($key); ?>&p_hidden_id=<?= $id; ?>&type=<?= $_GET['type']; ?>" class="btn btn-sm btn-outline-danger" title="Delete Report">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <button onclick="setupReports('<?= htmlspecialchars($key); ?>')" class="btn btn-sm btn-outline-primary" title="Edit Report">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            saleReports['<?= $key; ?>'] = "<?= $value; ?>";
                        </script>
                <?php endforeach;
                } else {
                    echo '<div class="text-center py-5 bg-light rounded">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">No Reports Found!</p>
              </div>';
                }
                ?>
            </div>
        <?php endif;
    include("footer.php"); ?>
        <script type="text/javascript">
            $(document).on('keyup', "#dr_acc", function(e) {
                fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "saleSubmit");
            });
            //fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "saleSubmit");

            $(document).on('keyup', "#cr_acc", function(e) {
                fetchKhaata("#cr_acc", "#cr_acc_id", "#cr_acc_kd_id", "saleSubmit");
            });

            $(document).on('keyup', "#np_acc", function(e) {
                fetchKhaata("#np_acc", "#np_acc_id", "#np_acc_kd_id", "notifyPartyDetailsSubmit");
            });

            $(document).on('keyup', "#search_acc_no", function(e) {
                fillTPBankDetails("#search_acc_no", "#search_acc_id", "thirdPartyBankSubmit");
            });

            function decodeSpecialCharacters(string) {
                const replacements = {
                    'u0027': "'",
                    'u0022': '"',
                    'u0026': '&',
                    'u003C': '<',
                    'u003E': '>',
                    'u0021': '!',
                    'u002C': ',',
                    'u002E': '.',
                    'u003B': ';',
                    'u003A': ':',
                    'u003F': '?',
                    'u0040': '@',
                    'u002B': '+',
                    'u002D': '-',
                    'u002F': '/',
                    'u005C': '\\',
                    'u0028': '(',
                    'u0029': ')',
                    'u007B': '{',
                    'u007D': '}',
                    'u005B': '[',
                    'u005D': ']',
                    'u00A0': ' '
                };

                // Loop through replacements and replace each key in the string
                for (const key in replacements) {
                    string = string.split(key).join(replacements[key]);
                }

                return string;
            }


            function fillTPBankDetails(inputFieldID, UniqueIDInput, SubmitButtonId) {
                let khaata_no = $(inputFieldID).val();
                disableButton(SubmitButtonId);
                $.ajax({
                    type: 'POST',
                    url: 'ajax/khaata_by_id.php',
                    data: {
                        khaata_no: khaata_no
                    },
                    success: function(response) {
                        enableButton(SubmitButtonId);
                        $(SubmitButtonId).prop('disabled', false);

                        let data = JSON.parse(response) ? JSON.parse(response) : '';
                        $(UniqueIDInput).val(data.id);

                        if (data.bank_details) {
                            $('#responseText').html("Data Retrieved From (A/C No. " + khaata_no + ")");
                            $('#responseText').addClass('text-success bg-success');
                            $('#responseText').removeClass('text-danger bg-danger');
                            $(inputFieldID).addClass('is-valid');
                            $(inputFieldID).removeClass('is-invalid');

                            // Decode special characters in bank_details
                            let bank_details = JSON.parse(decodeSpecialCharacters(data.bank_details));

                            // Clear the existing values
                            $('#acc_no').val(bank_details.acc_no);
                            $('#acc_name').val(bank_details.acc_name);
                            $('#b_company').val(bank_details.company);
                            $('#iban').val(bank_details.iban);
                            $('#branch_code').val(bank_details.branch_code);
                            $('#currency').val(bank_details.currency);
                            $('#bank_country').val(bank_details.country);
                            $('#bank_state').val(bank_details.state);
                            $('#bank_city').val(bank_details.city);
                            $('#bank_address').val(bank_details.address);

                            // Remove previous contact rows
                            $(".contact_row").remove();

                            // Loop through indexes4 and vals4 arrays
                            if (bank_details.indexes4 && bank_details.vals4) {
                                let arrayNumber = 0;

                                bank_details.indexes4.forEach(function(indexValue, index) {
                                    // Create dynamic row with dropdowns and inputs
                                    let rowHtml = `
                        <tr class="col-md-6 contact_row contact_row_${arrayNumber}">
                            <td onclick="removeContactRow(this)">
                                <i class="fa fa-close fa-2xl btn fs-5 text-danger ps-0 pe-1 pt-1"></i>
                            </td>
                            <td class="w-50">
                                <select name="indexes4[]" class="form-select contact_indexes">
                                    <?php
                                    $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                        echo '<option value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="w-50">
                                <input name="vals4[]" required placeholder="Value ${index + 1}" class="form-control contact_vals" value="${bank_details.vals4[index]}">
                            </td>
                        </tr>
                        `;

                                    // Append the new row to the table
                                    $("#contact_table_body").append(rowHtml);

                                    // Preselect the correct option for the index
                                    $(`.contact_row_${arrayNumber} .contact_indexes option[value='${indexValue}']`).prop('selected', true);

                                    arrayNumber++;
                                });
                            }
                        } else {
                            $('#responseText').html("Data Not Found For (A/C No. " + khaata_no + ")");
                            $('#responseText').removeClass('text-success bg-success');
                            $('#responseText').addClass('text-danger bg-danger');
                            $(inputFieldID).removeClass('is-valid');
                            $(inputFieldID).addClass('is-invalid');
                            $('#acc_no').val('');
                            $('#acc_name').val('');
                            $('#b_company').val('');
                            $('#iban').val('');
                            $('#branch_code').val('');
                            $('#currency').val('');
                            $('#bank_country').val('');
                            $('#bank_state').val('');
                            $('#bank_city').val('');
                            $('#bank_address').val('');
                            $(".contact_row").remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error if needed
                    }
                });
            }



            function fetchKhaata(inputField, khaataId, kd_dropdown, recordSubmitId) {

                let khaata_no = $(inputField).val();
                let khaata_id_this = 0;
                $.ajax({
                    url: 'ajax/fetchSingleKhaata.php',
                    type: 'post',
                    data: {
                        khaata_no: khaata_no
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success === true) {
                            enableButton(recordSubmitId);
                            khaata_id_this = response.messages['khaata_id'];
                            $(khaataId).val(khaata_id_this);
                            $(recordSubmitId).prop('disabled', false);
                            $(inputField).addClass('is-valid');
                            $(inputField).removeClass('is-invalid');
                            $(inputField + '_name').val(response.messages['khaata_name']);
                            if (inputField == '#dr_acc') {
                                khaataCompanies(khaata_id_this, 'dr_acc_kd_id');
                                var dr_acc_kd_id = $('#dr_acc_kd_id').find(":selected").val();
                                khaataDetailsSingle(dr_acc_kd_id, 'dr_acc_details');
                            }
                            if (inputField == '#cr_acc') {
                                khaataCompanies(khaata_id_this, 'cr_acc_kd_id');
                                var cr_acc_kd_id = $('#cr_acc_kd_id').find(":selected").val();
                                khaataDetailsSingle(cr_acc_kd_id, 'cr_acc_details');
                            }

                            if (inputField == '#np_acc') {
                                khaataCompanies(khaata_id_this, 'np_acc_kd_id');
                                var np_acc_kd_id = $('#np_acc_kd_id').find(":selected").val();
                                khaataDetailsSingle(np_acc_kd_id, 'np_acc_details');
                            }
                        }
                        if (response.success === false) {
                            disableButton(recordSubmitId);
                            $(inputField).addClass('is-invalid');
                            $(inputField).removeClass('is-valid');
                            $(khaataId).val(0);
                            $(kd_dropdown).html('<option value="0">Invalid A/c.</option>');
                        }
                    },
                    error: function(e) {
                        $(inputField).html('<option value="0">Invalid A/c.</option>');
                    }
                });
            }

            function khaataCompanies(khaata_id, dropdown_id) {
                if (khaata_id > 0) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/companies_dropdown_by_khaata_id.php',
                        data: {
                            khaata_id: khaata_id
                        },
                        success: function(html) {
                            $('#' + dropdown_id).html(html);
                        },
                        error: function(xhr, status, error) {
                            //console.error("AJAX call failed:", status, error); // Debugging line
                        }
                    });
                } else {
                    $('#' + dropdown_id).html('<option value="0">FAILED</option>');
                }
            }

            function khaataDetailsSingle(khaata_details_id, dropdown_id) {
                if (khaata_details_id > 0) {
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/khaata_details_by_id.php',
                        data: {
                            khaata_details_id: khaata_details_id
                        },
                        success: function(response) {
                            var data = JSON.parse(response)
                            var data_comp = JSON.parse(data[3])
                            console.log(data_comp);
                            var datu = data_comp['company_name'] + '\n' +
                                'Country: ' + data_comp['country'] + '\n' +
                                'City: ' + data_comp['city'] + '\n' +
                                'State: ' + data_comp['state'] + '\n' +
                                'Address: ' + data_comp['address'];
                            var indexVals = '';
                            if (data_comp['indexes1'] && data_comp['vals1']) {
                                for (var i = 0; i < data_comp['indexes1'].length; i++) {
                                    indexVals += '\n' + data_comp['indexes1'][i] + ': ' + data_comp['vals1'][i];
                                }
                            }
                            $('#' + dropdown_id).val(datu + indexVals);
                        },
                        error: function(xhr, status, error) {}
                    });
                } else {
                    $('#' + dropdown_id).val('');
                }
            }


            $(document).ready(function() {
                $('#dr_acc_kd_id').on('change', function() {
                    khaataDetailsSingle($(this).val(), 'dr_acc_details');
                    //var kd_id = $(this).val();
                });
                $('#cr_acc_kd_id').on('change', function() {
                    khaataDetailsSingle($(this).val(), 'cr_acc_details');
                    //var kd_id = $(this).val();
                });
                $('#np_acc_kd_id').on('change', function() {
                    khaataDetailsSingle($(this).val(), 'np_acc_details');
                    //var kd_id = $(this).val();
                });

            });
        </script>
        <script type="text/javascript">
            let availableAllotments;
            $(document).ready(function() {
                finalAmount();
                $('#qty_no,#qty_kgs,#empty_kgs,#weight,#rate1,#rate2,#opr').on('change', function() {
                    finalAmount();
                });

                $('#goods_id').on('change', function() {
                    updateAllotmentOptions($(this).val());
                    // finalAmount();
                });

                $('#allotment_name').on('change', function() {
                    updateSizeBrandOriginTable($(this).val());
                    // finalAmount();
                });

                // Initialize hidden div
                $('#availableOptionsDiv').hide();
            });

            function updateAllotmentOptions(goodsId) {
                if (!goodsId) {
                    resetAllInputs();
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'ajax/fetch_allot_by_goods.php', // Update to correct PHP file
                    data: {
                        goods_id: goodsId
                    },
                    success: function(res) {
                        res = JSON.parse(res);
                        $('#allotment_name').html(res.html);

                        if (res.allotments && res.allotments.length > 0) {
                            availableQuantities = res.allotments;
                            $('#size, #brand, #origin').html('<option value="">Select</option>');
                            $('#availableQuantitiesTable').empty();
                            $('#availableOptionsDiv').show(); // Show the div when data is available
                        } else {
                            resetAllInputs();
                            $('#availableOptionsDiv').hide(); // Hide the div if no data is available
                        }
                    },
                    error: function(err) {
                        console.error('AJAX error:', err);
                        alert('Failed to fetch allotment details. Please try again later.');
                    }
                });
            }

            function updateSizeBrandOriginTable(allotmentName) {
                let selectedGood = $('#goods_id').val();
                if (!selectedGood || !allotmentName) {
                    $('#availableQuantitiesTable').html('<p class="text-warning">Please select both a good and allotment to view available options.</p>');
                    return;
                }

                let selectedAllotment = availableQuantities.find(item => item.allotment_name === allotmentName);
                if (!selectedAllotment) return;

                let sizeOptions = '<option value="" selected>Select Size</option>';
                let brandOptions = '<option value="" selected>Select Brand</option>';
                let originOptions = '<option value="" selected>Select Origin</option>';

                let uniqueSizes = new Set();
                let uniqueBrands = new Set();
                let uniqueOrigins = new Set();

                selectedAllotment.sizes.forEach(sizeData => {
                    uniqueSizes.add(sizeData.size);
                    sizeData.brands.forEach(brandData => {
                        uniqueBrands.add(brandData.brand);
                        uniqueOrigins.add(brandData.origin);
                    });
                });

                uniqueSizes.forEach(size => {
                    sizeOptions += `<option value="${size}">${size}</option>`;
                });

                uniqueBrands.forEach(brand => {
                    brandOptions += `<option value="${brand}">${brand}</option>`;
                });

                uniqueOrigins.forEach(origin => {
                    originOptions += `<option value="${origin}">${origin}</option>`;
                });

                $('#size').html(sizeOptions);
                $('#brand').html(brandOptions);
                $('#origin').html(originOptions);

                // Populate the table
                let tableHTML = `
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Size</th>
                    <th>Brand</th>
                    <th>Origin</th>
                    <th>Qty Name</th>
                    <th>Purchased Quantity</th>
                    <th>Sold Quantity</th>
                    <th>Remaining Quantity</th>
                </tr>
            </thead>
            <tbody>
    `;

                selectedAllotment.sizes.forEach(sizeData => {
                    sizeData.brands.forEach(brandData => {
                        brandData.quantities.forEach(qty => {
                            tableHTML += `
                    <tr>
                        <td>${sizeData.size}</td>
                        <td class="tbrand">${brandData.brand}</td>
                        <td class="torigin">${brandData.origin}</td>
                        <td class="tqty_name">${qty.qty_name}</td>
                        <td class="text-success">${qty.purchased_quantity}</td>
                        <td class="text-danger">${qty.sold_quantity}</td>
                        <td class="text-dark tqty_remaining">${qty.remaining_quantity}</td>
                        <td class="d-none tqreport">${qty.quality_report}</td>
                        <td class="d-none titemid">${qty.item_id}</td>
                    </tr>
                `;
                        });
                    });
                });

                tableHTML += '</tbody></table>';
                $('#availableQuantitiesTable').html(tableHTML);
            }

            function resetAllInputs() {
                $('#allotment_name, #size, #brand, #origin').html('<option value="">Select</option>');
                $('#availableQuantitiesTable').empty();
                $('#availableOptionsDiv').hide(); // Hide the div when inputs are reset
            }

            function finalAmount() {
                var qty_no = parseFloat($("#qty_no").val()) || 0;
                var qty_kgs = parseFloat($("#qty_kgs").val()) || 0;

                var total_kgs = qty_no * qty_kgs;
                $("#total_kgs").val(total_kgs);
                $("#total_kgs_span").text(total_kgs);

                var empty_kgs = parseFloat($("#empty_kgs").val()) || 0;
                var total_qty_kgs = qty_no * empty_kgs;
                $("#total_qty_kgs").val(total_qty_kgs);
                $("#total_qty_kgs_span").text(total_qty_kgs);

                var net_kgs = total_kgs - total_qty_kgs;
                $("#net_kgs").val(net_kgs);
                $("#net_kgs_span").text(net_kgs);

                var weight = parseFloat($("#weight").val()) || 0;
                var total = 0;

                if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
                    total = net_kgs / weight;
                    total = total.toFixed(3);
                }

                $("#total").val(isNaN(total) ? '' : total);
                $("#total_span").text(isNaN(total) ? '' : total);

                var rate1 = parseFloat($("#rate1").val()) || 0;
                var final_amount = 0;
                var amount = 0;

                if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
                    amount = total * rate1;
                    amount = amount.toFixed(3);
                    final_amount = amount;
                }

                $("#amount").val(isNaN(amount) ? '' : amount);
                $("#amount_span").text(isNaN(amount) ? '' : amount);

                var rate2 = parseFloat($("#rate2").val()) || 0;
                let operator = $('#opr').find(":selected").val();

                if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
                    final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
                    final_amount = final_amount.toFixed(3);
                }

                $("#final_amount").val(isFinite(final_amount) ? final_amount : '');
                $("#final_amount_span").text(isFinite(final_amount) ? final_amount : '');
                if (qty_no <= 0) {
                    $('#recordSubmit').addClass('disabled');
                }
                if (qty_no > 0) {
                    $('#recordSubmit').removeClass('disabled');
                }
                if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
                    disableButton('recordSubmit');
                } else {
                    enableButton('recordSubmit');
                }

                checkRemainingQuantity(qty_no);
            }

            function checkRemainingQuantity(qty_no) {
                var qty_name = $("#qty_name").val().toLowerCase();
                var origin = $("#origin").val().toLowerCase();
                var brand = $("#brand").val().toLowerCase();
                var exceedLimit = false;
                $("#availableQuantitiesTable table tbody tr").each(function() {
                    var Tbrand = $(this).find(".tbrand").text().toLowerCase();
                    var Torigin = $(this).find(".torigin").text().toLowerCase();
                    var Tqty_name = $(this).find(".tqty_name").text().toLowerCase();
                    var Tqreport = $(this).find(".tqreport").text().toLowerCase();
                    var Tqty_remaining = parseFloat($(this).find(".tqty_remaining").text()) || 0;
                    if (Tbrand === brand && Torigin === origin && Tqty_name === qty_name) {
                        $('#quality_report').html(Tqreport);
                        $('#purchased_item_id').val($(this).find(".titemid").text().toLowerCase());
                        if (qty_no > Tqty_remaining) {
                            exceedLimit = true;
                            return false; // Exit loop
                        }
                    }
                });
                if (exceedLimit) {
                    disableButton('recordSubmit');
                    $('#qtyAlert').removeClass('hidden');
                    $('#qty_no').addClass('is-invalid');
                } else {
                    enableButton('recordSubmit');
                    $('#qtyAlert').addClass('hidden');
                    $('#qty_no').removeClass('is-invalid');
                }
            }
        </script>

        <?php $info = ['type' => 'danger', 'msg' => 'System Error :('];
        if (isset($_POST['notifyPartySubmit'])) {
            $type = mysqli_real_escape_string($connect, $_POST['type']);
            $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
            $pageURL = '?id=' . $hidden_id . '&type=' . $type;
            $notifyData = json_encode([
                "np_acc" => $_POST['np_acc'],
                "np_acc_name" => $_POST['np_acc_name'],
                "np_acc_id" => $_POST['np_acc_id'],
                "np_acc_kd_id" => $_POST['np_acc_kd_id'],
                "np_acc_details" => $_POST['np_acc_details'],
                "hidden_id" => $_POST['hidden_id'],
                "type" => $_POST['type']
            ]);
            $done = update('transactions', ['notify_party_details' => $notifyData], array('id' => $hidden_id));
            if ($done) {
                $info['type'] = 'success';
                $info['msg'] = strtoupper($type) . ' save successfully';
            }
            messageNew($info['type'], $pageURL, $info['msg']);
        }
        if (isset($_POST['saleSubmit'])) {
            $type = mysqli_real_escape_string($connect, $_POST['type']);
            $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
            $data = [
                'sr' =>  mysqli_real_escape_string($connect, $_POST['sr']),
                'p_s' => 's',
                'type' => $type,
                'active' => 1,
                '_date' => $_POST['_date'],
                'country' => mysqli_real_escape_string($connect, $_POST['country']),
                'delivery_terms' => mysqli_real_escape_string($connect, $_POST['delivery_terms']),
                'branch_id' => mysqli_real_escape_string($connect, $_POST['branch_id']),
                'show_in' => json_encode(['vat' => $_POST['show_in_vat'], 'loading' => $_POST['show_in_loading']])
            ];

            $dr_acc = mysqli_real_escape_string($connect, $_POST['dr_acc']);
            $dr_acc_name = mysqli_real_escape_string($connect, $_POST['dr_acc_name']);
            $dr_acc_id = mysqli_real_escape_string($connect, $_POST['dr_acc_id']);
            $dr_acc_kd_id = mysqli_real_escape_string($connect, $_POST['dr_acc_kd_id']);
            $dr_acc_details = mysqli_real_escape_string($connect, $_POST['dr_acc_details']);

            $cr_acc = mysqli_real_escape_string($connect, $_POST['cr_acc']);
            $cr_acc_name = mysqli_real_escape_string($connect, $_POST['cr_acc_name']);
            $cr_acc_id = mysqli_real_escape_string($connect, $_POST['cr_acc_id']);
            $cr_acc_kd_id = mysqli_real_escape_string($connect, $_POST['cr_acc_kd_id']);
            $cr_acc_details = mysqli_real_escape_string($connect, $_POST['cr_acc_details']);

            if ($hidden_id > 0) {
                $pageURL .= "?id=" . $hidden_id . '&type=' . $type;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $data['updated_by'] = $userId;
                $data['`from`'] = 'sale-add';
                $done = update('transactions', $data, array('id' => $hidden_id));
                $transaction_accounts_dr_id = mysqli_real_escape_string($connect, $_POST['transaction_accounts_dr_id']);
                $transaction_accounts_cr_id = mysqli_real_escape_string($connect, $_POST['transaction_accounts_cr_id']);
                if ($done && $transaction_accounts_dr_id > 0 && $transaction_accounts_cr_id > 0) {
                    $dr_done = updateTransactionAccount($transaction_accounts_dr_id, $dr_acc, $dr_acc_name, $dr_acc_id, $dr_acc_kd_id, $dr_acc_details);
                    $cr_done = updateTransactionAccount($transaction_accounts_cr_id, $cr_acc, $cr_acc_name, $cr_acc_id, $cr_acc_kd_id, $cr_acc_details);
                    if ($dr_done && $cr_done) {
                        $info['type'] = 'success';
                        $info['msg'] = strtoupper($type) . ' Sale updated successfully';
                    }
                }
            } else {
                $data['created_at'] = date('Y-m-d H:i:s');
                $data['created_by'] = $userId;
                $data['from'] = 'sale-add';
                $done = insert('transactions', $data);
                if ($done) {
                    $tr_id = $connect->insert_id;
                    $pageURL .= "?id=" . $tr_id . '&type=' . $type;
                    $dr_done = saveTransactionAccount($tr_id, 'sale', 'dr', $dr_acc, $dr_acc_name, $dr_acc_id, $dr_acc_kd_id, $dr_acc_details);
                    $cr_done = saveTransactionAccount($tr_id, 'sale', 'cr', $cr_acc, $cr_acc_name, $cr_acc_id, $cr_acc_kd_id, $cr_acc_details);
                    if ($dr_done && $cr_done) {
                        $info['type'] = 'success';
                        $info['msg'] = strtoupper($type) . ' save successfully';
                    }
                }
            }
            messageNew($info['type'], $pageURL, $info['msg']);
        }
        if (isset($_POST['recordSubmit'])) {
            $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
            $hidden_item_id = mysqli_real_escape_string($connect, $_POST['hidden_item_id']);
            if ($hidden_id > 0) {
                if (!recordExists('good_details', ['goods_id' => $_POST['goods_id'], 'brand' => strtoupper($_POST['brand'])])) {
                    insert('good_details', ['goods_id' => $_POST['goods_id'], 'size' => $_POST['size'], 'brand' => strtoupper($_POST['brand']), 'origin' => $_POST['origin']]);
                }
                $pageURL .= '?id=' . $hidden_id . '&type=' . $record['type'];
                $show_in = mysqli_real_escape_string($connect, json_encode([
                    'vat' => $_POST['show_in_vat'],
                    'loading' => $_POST['show_in_loading'],
                    'warehouse' => $_POST['show_in_warehouse'],
                    'stock' => 'yes'
                ]));
                $data = array(
                    'p_s' => 's',
                    'sr' =>  mysqli_real_escape_string($connect, $_POST['sr']),
                    'allotment_name' => mysqli_real_escape_string($connect, $_POST['allotment_name']),
                    'quality_report' => mysqli_real_escape_string($connect, preg_replace('/[\n\r\t]+/', ', ', $_POST['quality_report'])),
                    'goods_id' => mysqli_real_escape_string($connect, $_POST['goods_id']),
                    'size' => mysqli_real_escape_string($connect, $_POST['size']),
                    'brand' => mysqli_real_escape_string($connect, $_POST['brand']),
                    'origin' => mysqli_real_escape_string($connect, $_POST['origin']),
                    'qty_name' => mysqli_real_escape_string($connect, $_POST['qty_name']),
                    'qty_no' => mysqli_real_escape_string($connect, $_POST['qty_no']),
                    'qty_kgs' => mysqli_real_escape_string($connect, $_POST['qty_kgs']),
                    'total_kgs' => mysqli_real_escape_string($connect, $_POST['total_kgs']),
                    'empty_kgs' => mysqli_real_escape_string($connect, $_POST['empty_kgs']),
                    'total_qty_kgs' => mysqli_real_escape_string($connect, $_POST['total_qty_kgs']),
                    'net_kgs' => mysqli_real_escape_string($connect, $_POST['net_kgs']),
                    'divide' => mysqli_real_escape_string($connect, $_POST['divide']),
                    'weight' => mysqli_real_escape_string($connect, $_POST['weight']),
                    'total' => mysqli_real_escape_string($connect, $_POST['total']),
                    'price' => mysqli_real_escape_string($connect, $_POST['price']),
                    'currency1' => mysqli_real_escape_string($connect, $_POST['currency1']),
                    'rate1' => mysqli_real_escape_string($connect, $_POST['rate1']),
                    'amount' => mysqli_real_escape_string($connect, $_POST['amount']),
                    'currency2' => mysqli_real_escape_string($connect, $_POST['currency2']),
                    'rate2' => mysqli_real_escape_string($connect, $_POST['rate2']),
                    'opr' => mysqli_real_escape_string($connect, $_POST['opr']),
                    'tax_percent' => mysqli_real_escape_string($connect, $_POST['tax_percent']),
                    'tax_amount' => mysqli_real_escape_string($connect, $_POST['tax_amount']),
                    'total_with_tax' => mysqli_real_escape_string($connect, $_POST['total_with_tax']),
                    'show_in' => $show_in,
                    'final_amount' => mysqli_real_escape_string($connect, (isset($_POST['total_with_tax']) && !empty($_POST['total_with_tax']) ? $_POST['total_with_tax'] : $_POST['final_amount'])),
                );
                if (isset($_POST['purchased_item_id']) && $_POST['purchased_item_id'] > 0) {
                    $data['tracking_info'] = json_encode(['linked_purchase_item_id' => $_POST['purchased_item_id']]);
                }
                if ($hidden_item_id > 0) {
                    if (!empty($_POST['purchased_item_id'])) {
                        update('transaction_items', ['tracking_info' => json_encode(['linked_sale_item_id' => $hidden_item_id])], ['id' => $_POST['purchased_item_id']]);
                    }
                    $pageURL .= '&item_id=' . $hidden_item_id;
                    $done = update('transaction_items', $data, ['id' => $hidden_item_id]);
                    if ($done) {
                        $info['type'] = 'success';
                        $info['msg'] = ' Container successfully updated. ';
                    }
                } else {
                    update('transaction_items', ['tracking_info' => json_encode(['linked_sale_item_id' => $_POST['new_item_id']])], ['id' => $_POST['purchased_item_id']]);
                    $data['parent_id'] = $hidden_id;
                    $done = insert('transaction_items', $data);
                    if ($done) {
                        $item_id_ = $connect->insert_id;
                        // $pageURL .= '&item_id=' . $item_id_;
                        $info['type'] = 'success';
                        $info['msg'] = ' New container successfully added. ';
                    }
                }
            }
            messageNew($info['type'], $pageURL, $info['msg']);
            /*echo '<pre>';
var_dump($_POST);*/
        }
        if (isset($_POST['deletePDSubmit'])) {
            $p_id_delete = mysqli_real_escape_string($connect, $_POST['p_id_delete']);
            $pd_id_delete = mysqli_real_escape_string($connect, $_POST['pd_id_delete']);
            $done = mysqli_query($connect, "DELETE FROM `transaction_items` WHERE id='$pd_id_delete'");
            $pageURL .= "?id=" . $p_id_delete . '&type=' . $record['type'];
            if ($done) {
                $info['msg'] = 'Record deleted.';
                $info['type'] = 'success';
            }
            messageNew($info['type'], $pageURL, $info['msg']);
        }

        if (isset($_POST['saleReports'])) {
            $type = 'danger';
            $msg = 'DB Failed';
            $id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
            $reportType = mysqli_real_escape_string($connect, $_POST['reportType']);
            $report = htmlspecialchars($_POST['reportBox']);
            $report = str_replace(array("\n", "\r", "\r\n"), ' ', $report);
            $records = fetch('transactions', ['id' => $id]);
            $record = mysqli_fetch_assoc($records);
            $reports = isset($record['reports']) && !empty($record['reports']) ? json_decode($record['reports'], true) : [];
            // if (json_last_error() !== JSON_ERROR_NONE) {
            //     $msg = 'JSON Decode Error: ' . json_last_error_msg();
            //     messageNew('danger', $pageURL . '?id=' . $id . '&type=' . $record['type'], $msg);
            //     return;
            // }
            $reports[$reportType] = $report;
            $data = ['reports' => json_encode($reports)];
            if (update('transactions', $data, ['id' => $id])) {
                $type = 'success';
                $msg = 'Report Successfully Updated.';
            } else {
                $type = 'danger';
                $msg = 'DB Update Failed';
            }
            messageNew($type, $pageURL . '?id=' . $id . '&type=' . $record['type'], $msg);
        }

        if (isset($_GET['deleteSaleReport'])) {
            $id = isset($_GET['p_hidden_id']) ? $_GET['p_hidden_id'] : '';
            $type = $_GET['type'];
            $pageURL = $pageURL . '?id=' . $id . '&type=' . $type;
            $deleteReport = isset($_GET['deleteSaleReport']) ? $_GET['deleteSaleReport'] : '';
            $records = fetch('transactions', ['id' => $id]);
            $record = mysqli_fetch_assoc($records);
            $reports = isset($record['reports']) ? json_decode($record['reports'], true) : [];
            if (isset($reports[$deleteReport])) {
                unset($reports[$deleteReport]);
                $data = ['reports' => json_encode($reports)];
                if (update('transactions', $data, ['id' => $id])) {
                    messageNew('success', $pageURL, 'Report Deleted Successfully!');
                } else {
                    messageNew('failed', $pageURL, 'Error in Deleting Report!');
                }
            } else {
                messageNew('failed', $pageURL, 'Report Type Not Found!');
            }
        }
        ?>
        <div class="modal fade" id="addReportModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addReportLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 80vw;">
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="addReportLabel">Add Report</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <mark>Note: Please don't use these words or combinations of these words => (\n, \r, \r\n, \n\r). This will affect report functionality. Thank you!</mark>
                            <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                            <input type="hidden" name="reportType" id="reportType">
                            <textarea placeholder="Write Report..." rows="6" name="reportBox" id="reportBox" class="form-control mt-3"></textarea>
                        </div>
                        <div class="modal-footer d-flex justify-content-end">
                            <input id="saleReportsSubmitBtn" name="saleReports" type="submit" value="Add Report" class="btn btn-dark" />
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="allotSearchModel" tabindex="-1" aria-labelledby="allotSearchModelLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="allotSearchModelLabel">Search Allotment Name</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="height: 400px; overflow-y:scroll;"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="insertGoodsBtn" onclick="insertBtnClick()" disabled>Insert</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="seaRoadDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="seaRoadDetailsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="seaRoadDetailsLabel">By Sea / Road Details</h1>
                            <a href="<?php echo $pageURL . '?id=' . $id . '&type=' . $record['type']; ?>" class="btn-close" aria-label="Close"></a>
                        </div>
                        <div class="modal-body table-form">
                            <div class="row mt-1 mb-4 align-items-center">
                                <div class="col-md-auto">
                                    <div class="bg-light border pt-1 ps-2">
                                        <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                            <input class="form-check-input" type="radio" name="sea_road" id="sea"
                                                value="sea" <?php echo $sea_road['sea_road'] == 'sea' ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="sea">By Sea</label>
                                        </div>
                                        <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                            <input class="form-check-input" type="radio" name="sea_road" id="road" value="road"
                                                <?php echo $sea_road['sea_road'] == 'road' ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="road">By Road</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-1 gy-4 mb-4 toggleByRoad">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="l_country_road">Loading Country</label>
                                        <input id="l_country_road" name="l_country_road"
                                            value="<?php echo $sea_road['l_country_road']; ?>" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="l_border_road">Loading Border</label>
                                        <input id="l_border_road" name="l_border_road"
                                            value="<?php echo $sea_road['l_border_road']; ?>" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="l_date_road">Loading Date</label>
                                        <input value="<?php echo $sea_road['l_date_road']; ?>" type="date"
                                            class="form-control" id="l_date_road" name="l_date_road">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="truck_container">Status</label>
                                        <select id="truck_container" name="truck_container" class="form-select">
                                            <?php $tc_array = array('Open Truck' => 'open_truck', 'Container' => 'container');
                                            foreach ($tc_array as $str => $value) {
                                                $tc_selected = $sea_road['truck_container'] == $value ? 'selected' : '';
                                                echo '<option ' . $tc_selected . ' value="' . $value . '">' . $str . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="r_country_road">Receiving Country</label>
                                        <input id="r_country_road" name="r_country_road"
                                            value="<?php echo $sea_road['r_country_road']; ?>" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="r_border_road">Receiving Border</label>
                                        <input id="r_border_road" name="r_border_road"
                                            value="<?php echo $sea_road['r_border_road']; ?>" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="r_date_road">Receiving Date</label>
                                        <input id="r_date_road" name="r_date_road"
                                            value="<?php echo $sea_road['r_date_road']; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="d_date_road">Delivery Date</label>
                                        <input id="d_date_road" name="d_date_road"
                                            value="<?php echo $sea_road['d_date_road']; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-1 mb-4 toggleBySea">
                                <div class="col-md-auto">
                                    <div class="form-check mt-md-1">
                                        <input type="checkbox" class="form-check-input" id="is_loading"
                                            name="is_loading"
                                            value="1" <?php echo $sea_road['is_loading'] == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold" for="is_loading">Loading?</label>
                                    </div>
                                </div>
                                <div class="col-md toggleLoading">
                                    <div class="input-group">
                                        <label for="l_country">Country</label>
                                        <input value="<?php echo $sea_road['l_country']; ?>" type="text"
                                            class="form-control"
                                            id="l_country" name="l_country">
                                    </div>
                                </div>
                                <div class="col-md toggleLoading">
                                    <div class="input-group">
                                        <label for="l_port">Port</label>
                                        <input value="<?php echo $sea_road['l_port']; ?>" type="text"
                                            class="form-control"
                                            id="l_port" name="l_port">
                                    </div>
                                </div>
                                <div class="col-md toggleLoading">
                                    <div class="input-group">
                                        <label for="l_date" class="text-nowrap">Loading Date</label>
                                        <input type="date" class="form-control" id="l_date" name="l_date"
                                            value="<?php echo $sea_road['l_date']; ?>">
                                    </div>
                                </div>
                                <div class="col-md toggleLoading">
                                    <div class="input-group">
                                        <label for="ctr_name" class="text-nowrap">Container Name</label>
                                        <input value="<?php echo $sea_road['ctr_name']; ?>" type="text"
                                            class="form-control" id="ctr_name" name="ctr_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-1 mb-4 toggleBySea">
                                <div class="col-md-auto">
                                    <div class="form-check mt-md-1">
                                        <input type="checkbox" class="form-check-input" id="is_receiving"
                                            name="is_receiving"
                                            value="1" <?php echo $sea_road['is_receiving'] == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-bold" for="is_receiving">Receiving?</label>
                                    </div>
                                </div>
                                <div class="col-md toggleReceiving">
                                    <div class="input-group">
                                        <label for="r_country">Country</label>
                                        <input value="<?php echo $sea_road['r_country']; ?>" type="text"
                                            class="form-control" id="r_country" name="r_country">
                                    </div>
                                </div>
                                <div class="col-md toggleReceiving">
                                    <div class="input-group">
                                        <label for="r_port">Port</label>
                                        <input value="<?php echo $sea_road['r_port']; ?>" type="text"
                                            class="form-control" id="r_port" name="r_port">
                                    </div>
                                </div>
                                <div class="col-md toggleReceiving">
                                    <div class="input-group">
                                        <label for="r_date" class="text-nowrap">Receivning Date</label>
                                        <input type="date" class="form-control" id="r_date" name="r_date"
                                            value="<?php echo $sea_road['r_date']; ?>">
                                    </div>
                                </div>
                                <div class="col-md toggleReceiving">
                                    <div class="input-group">
                                        <label for="arrival_date" class="text-nowrap">Arrival Date</label>
                                        <input type="date" class="form-control" id="arrival_date" name="arrival_date"
                                            value="<?php echo $sea_road['arrival_date']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="input-group">
                                        <label for="report" class="text-nowrap">Report</label>
                                        <input class="form-control" id="report" name="report"
                                            value="<?php echo $sea_road['report']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="seaRoadDetailsSubmit" class="btn btn-dark">Submit</button>
                        </div>
                    </div>
                    <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                </form>
            </div>
        </div>
        <div class="modal fade" id="localSeaRoadDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="localSeaRoadDetailsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="localSeaRoadDetailsLabel">Local Purchase By Sea / Road Details</h1>
                            <a href="<?php echo $pageURL . '?id=' . $id . '&type=' . $record['type']; ?>" class="btn-close" aria-label="Close"></a>
                        </div>
                        <div class="modal-body table-form">
                            <div class="row mt-1 mb-4 align-items-center">
                                <div class="col-md-auto">
                                    <div class="bg-light border pt-1 ps-2">
                                        <!-- For Local Payments I'm not building a different functionality for it rather I'm assuming sea as type = Loading and road as type = warehouse. -->
                                        <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                            <input class="form-check-input" type="radio" name="lwl" id="sea"
                                                value="local" <?= $sea_road['route'] == 'local' ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="sea">Loading Transfer</label>
                                        </div>
                                        <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                            <input class="form-check-input" type="radio" name="lwl" id="road" value="warehouse"
                                                <?= $sea_road['route'] == 'warehouse' ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="road">WareHouse Transfer</label>
                                        </div>
                                        <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                            <input class="form-check-input" type="radio" name="lwl" id="road" value="launch"
                                                <?= $sea_road['route'] == 'launch' ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="road">Launch Transfer</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-1 gy-4 mb-4 toggleByLocal">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="local_loading_date">Loading Date</label>
                                        <input id="local_loading_date" name="local_loading_date"
                                            value="<?php echo $sea_road['loading_date']; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="local_receiving_date">Receiving Date</label>
                                        <input id="local_receiving_date" name="local_receiving_date"
                                            value="<?php echo $sea_road['receiving_date']; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-1 gy-4 mb-4 toggleByWarehouse">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="warehouse_transfer_date">Transfer Date</label>
                                        <input id="warehouse_transfer_date" name="warehouse_transfer_date"
                                            value="<?php echo $sea_road['transfer_date'] ?? ''; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="warehouse_receiving_date">Receiving Date</label>
                                        <input id="warehouse_receiving_date" name="warehouse_receiving_date"
                                            value="<?php echo $sea_road['receiving_date']; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-1 gy-4 mb-4 toggleByLaunch">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="launch_port_name">Port Name</label>
                                        <input id="launch_port_name" name="launch_port_name"
                                            value="<?php echo $sea_road['port_name'] ?? ''; ?>" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="launch_launchnumber">Launch Number</label>
                                        <input id="launch_launchnumber" name="launch_launchnumber"
                                            value="<?php echo $sea_road['launchnumber'] ?? ''; ?>" type="text"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="launch_loading_date">Loading Date</label>
                                        <input id="launch_loading_date" name="launch_loading_date"
                                            value="<?php echo $sea_road['loading_date']; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="launch_receiving_date">Receiving Date</label>
                                        <input id="launch_receiving_date" name="launch_receiving_date"
                                            value="<?php echo $sea_road['receiving_date']; ?>" type="date"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="seaRoadDetailsSubmit" class="btn btn-dark">Submit</button>
                        </div>
                    </div>
                    <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="saleLocal" value="<?= "true"; ?>">
                </form>
            </div>
        </div>
        <?php if (isset($_POST['seaRoadDetailsSubmit'])) {
            if (isset($_POST['seaRoadDetailsSubmit'])) {
                $_POST['is_loading'] = isset($_POST['is_loading']) ? 1 : 0;
                $_POST['is_receiving'] = isset($_POST['is_receiving']) ? 1 : 0;

                if ($_POST['saleLocal'] == 'true') {
                    // $_POST['sea_road'] = $_POST['local_warehouse'];
                    $_POST['route'] = $_POST['lwl'];
                    $lwl_prefix = $_POST['lwl'] . '_';
                    foreach ($_POST as $key => $value) {
                        $_POST[str_replace($lwl_prefix, '', $key)] = $value;
                        if (!in_array($key, ['hidden_id', 'sea_road', 'lwl', 'saleLocal', 'seaRoadDetailsSubmit', 'route'])) {
                            unset($_POST[$key]);
                        }
                    }
                } else {
                    $_POST['sea_road'] = $_POST['sea_road'];
                }
                $post = json_encode($_POST);
                $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
                unset($_POST['seaRoadDetailsSubmit']);
                unset($_POST['saleLocal']);
                unset($_POST['hidden_id']);
                $pageURL .= "?id=" . $hidden_id . '&type=' . $record['type'];
                $data = array('sea_road' => $post);
                if ($hidden_id > 0) {
                    $done = update('transactions', $data, array('id' => $hidden_id));
                    if ($done) {
                        $info['msg'] = 'Sea / Road details Successfully Updated.';
                        $info['type'] = 'success';
                    }
                }
                // echo json_encode($_POST);
                messageNew($info['type'], $pageURL, $info['msg']);
            }
        } ?>
        <!-- <div class="modal fade" id="notifyPartyDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="notifyPartyDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">

    </div>
</div> -->
        <div class="modal fade" id="notifyParty" tabindex="-1" aria-labelledby="notifyPartyLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-primary" id="notifyPartyLabel">Notify Party Details <sup>Np.</sup></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="mb-2 d-flex justify-content-center align-items-center">
                                <input type="text" id="np_acc" style="width:65px;" name="np_acc" class="form-control form-control-sm" required value="<?= isset($NP_details['np_acc']) ? $NP_details['np_acc'] : ''; ?>">
                                <input type="text" id="np_acc_name" name="np_acc_name" class="form-control form-control-sm" value="<?= isset($NP_details['np_acc_name']) ? $NP_details['np_acc_name'] : ''; ?>" readonly tabindex="-1">
                            </div>
                            <input type="hidden" name="np_acc_id" id="np_acc_id" value="<?= isset($NP_details['np_acc_id']) ? $NP_details['np_acc_id'] : ''; ?>">

                            <div class="row g-2 mb-2">
                                <div class="col">
                                    <select class="form-select form-select-sm" name="np_acc_kd_id" id="np_acc_kd_id">
                                        <option selected disabled value="">Select Company</option>
                                        <?php
                                        $run_query = fetch('khaata_details', array('khaata_id' => isset($NP_details['np_acc_id']) ? $NP_details['np_acc_id'] : '', 'type' => 'company'));
                                        while ($row = mysqli_fetch_array($run_query)) {
                                            $row_data = json_decode($row['json_data']);
                                            $sel_kd2 = $row['id'] == $NP_details['np_acc_kd_id'] ? 'selected' : '';
                                            echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="np_acc_details" id="np_acc_details" placeholder="Company Details" rows="<?= !empty($NP_details['np_acc_details']) ? '6' : '3'; ?>"><?= isset($NP_details['np_acc_details']) ? $NP_details['np_acc_details'] : ''; ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="hidden_id" value="<?= $id; ?>">
                            <input type="hidden" name="type" value="<?= $_GET['type']; ?>">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="notifyPartySubmit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="thirdPartyBank" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="thirdPartyBankLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="thirdPartyBankLabel">Third Party Bank Details</h1>
                            <a href="<?php echo $pageURL . '?id=' . $id . '&type=' . $record['type']; ?>" class="btn-close" aria-label="Close"></a>
                        </div>
                        <div class="modal-body table-form">
                            <div class="row gx-1 gy-4">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="acc_no" class="form-label">Search ACC No.</label>
                                        <input value="<?= isset($bank_details['search_acc_no']) ? $bank_details['search_acc_no'] : '' ?>" id="search_acc_no"
                                            name="search_acc_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <input value="<?= isset($NP_details['search_acc_id']) ? $NP_details['search_acc_id'] : ''; ?>" type="hidden"
                                        name="search_acc_id" id="search_acc_id">
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="updateBankInfoToAccount" name="updateBankInfoToAccount">
                                        <label class="form-check-label" for="updateBankInfoToAccount">Want to add/update this bank info to the searched account number as well?</label>
                                    </div>
                                </div>
                                <span id="responseText" class="p-1 bg-opacity-10"></span>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="bank_name" class="form-label">Bank Name.</label>
                                        <input value="<?= isset($bank_details['bank_name']) ? $bank_details['bank_name'] : '' ?>" id="bank_name"
                                            name="bank_name" class="form-control" required>
                                    </div>
                                    <!-- <div class="input-group">
                                <label for="acc_no" class="form-label">A/c No.</label>
                                <input value="<?= isset($bank_details['acc_no']) ? $bank_details['acc_no'] : '' ?>" id="acc_no"
                                    name="acc_no" class="form-control" required>
                            </div> -->
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="acc_name" class="form-label">A/c Name</label>
                                        <input value="<?= isset($bank_details['acc_name']) ? $bank_details['acc_name'] : '' ?>" id="acc_name"
                                            name="acc_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="b_company" class="form-label">Company</label>
                                        <input value="<?= isset($bank_details['company']) ? $bank_details['company'] : '' ?>" id="b_company"
                                            name="company" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="iban" class="form-label">IBAN#</label>
                                        <input value="<?= isset($bank_details['iban']) ? $bank_details['iban'] : '' ?>" id="iban" name="iban"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="branch_code" class="form-label">Branch Code</label>
                                        <input value="<?= isset($bank_details['branch_code']) ? $bank_details['branch_code'] : '' ?>" id="branch_code"
                                            name="branch_code" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="currency">Currency</label>
                                        <select id="currency" name="currency" class="form-select" required>
                                            <?php $currencies = fetch('currencies');
                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                $crr_sel3 = $crr['name'] == isset($bank_details['currency']) ? 'selected' : '';
                                                echo '<option ' . $crr_sel3 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="bank_country" class="form-label">Country</label>
                                        <input value="<?= isset($bank_details['country']) ? $bank_details['country'] : '' ?>" id="bank_country"
                                            name="country" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="bank_state" class="form-label">State</label>
                                        <input value="<?= isset($bank_details['state']) ? $bank_details['state'] : '' ?>" id="bank_state" name="state"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="bank_city" class="form-label">City</label>
                                        <input value="<?= isset($bank_details['city']) ? $bank_details['city'] : '' ?>" id="bank_city" name="city"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label for="bank_address" class="form-label">Address</label>
                                        <input value="<?= isset($bank_details['address']) ? $bank_details['address'] : '' ?>" id="bank_address"
                                            name="address" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-1 my-3 table-container" data-instance="4">
                                <div class="col-md">
                                    <table class="table table-borderless mb-0 contactsTable">
                                        <tbody class="row" id="contact_table_body">
                                            <?php
                                            $arrayNumber = 0;
                                            if (isset($bank_details['indexes4'])) {
                                                foreach ($bank_details['indexes4'] as $index => $value) { ?>
                                                    <tr class="col-md-6 contact_row contact_row_<?php echo $arrayNumber; ?>">
                                                        <td onclick="removeContactRow(this)">
                                                            <i class="fa fa-close fa-2xl- btn fs-5 text-danger ps-0 pe-1 pt-1"></i>
                                                        </td>
                                                        <td class="w-50">
                                                            <select name="indexes4[]" class="form-select contact_indexes">
                                                                <?php $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                                                while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                                    $st_sel3 = $static_type['type_name'] == $value ? 'selected' : '';
                                                                    echo '<option ' . $st_sel3 . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td class="w-50">
                                                            <input name="vals4[]" required placeholder="Value <?php echo $index + 1; ?>"
                                                                class="form-control contact_vals"
                                                                value="<?php echo $bank_details['vals4'][$index] ?>">
                                                        </td>
                                                    </tr>
                                            <?php $arrayNumber++;
                                                }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-auto">
                                    <div class="btn btn-outline-secondary py-0 mt-1 addContactRow"
                                        data-url="ajax/fetchStaticTypesForContacts.php" data-loading-text="Loading...">
                                        <i class="fa fa-plus-circle"></i> New
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="thirdPartyBankSubmit" class="btn btn-dark">Submit</button>
                        </div>
                    </div>
                    <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                </form>
            </div>
        </div>
        <div class="modal fade" id="paymentDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="paymentDetailsLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="paymentDetailsLabel">Payment Details</h1>
                            <a href="<?php echo $pageURL . '?id=' . $id . '&type=' . $record['type']; ?>" class="btn-close" aria-label="Close"></a>
                        </div>
                        <div class="modal-body table-form">
                            <?php
                            if (isset($_fields['items_sum'])) {
                                $t = $_fields['items_sum']['sum_final_amount'];
                                $bees = Percentage(20, $t);
                                $assi = Percentage(80, $t); ?>
                                <div class="row">
                                    <div class="col-md-9 border-end">
                                        <div class="row gx-1 mb-4 align-items-center">
                                            <div class="col-md-auto">
                                                <div class="bg-light border pt-1 ps-2">
                                                    <?php
                                                    $payment_type = isset($record['payments']) ?
                                                        (json_decode($record['payments'], true)['full_advance'] ?? '')
                                                        : '';
                                                    ?>

                                                    <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                                        <input <?= $payment_type === 'credit' ? 'checked' : 'checked'; ?> class="form-check-input" type="radio" name="full_advance" id="credit"
                                                            value="credit">
                                                        <label class="form-check-label" for="credit">Credit Payment</label>
                                                    </div>
                                                    <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                                        <input <?= $payment_type === 'full' ? 'checked' : ''; ?> class="form-check-input" type="radio" name="full_advance" id="cash"
                                                            value="full">
                                                        <label class="form-check-label" for="cash">Full Payment</label>
                                                    </div>
                                                    <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                                        <input <?= $payment_type === 'advance' ? 'checked' : ''; ?> class="form-check-input" type="radio" name="full_advance"
                                                            id="advance" value="advance">
                                                        <label class="form-check-label" for="advance">Advance</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <label for="pct_value">% Value</label>
                                                    <input type="number" min="1" step="any" id="pct_value" name="pct_value"
                                                        class="form-control" value="<?= isset($_fields['payment_details']->pct_value) ? $_fields['payment_details']->pct_value : '20'; ?>" max="90">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="date-report-inputs"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <table class="table table-sm">
                                            <tbody>
                                                <?php echo '<tr><td>TOTAL PAYMENT </td><th>' . $t . '</th></tr>'; ?>
                                                <tr id="adv_values1"></tr>
                                                <tr id="adv_values2"></tr>
                                            </tbody>
                                        </table>
                                        <input type="hidden" id="p_total_amount" name="p_total_amount" value="<?php echo $t; ?>">
                                        <input id="partial_amount1" name="partial_amount1" type="hidden">
                                        <input id="partial_amount2" name="partial_amount2" type="hidden">
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="paymentDetailsSubmit" class="btn btn-dark">Submit</button>
                        </div>
                    <?php } else {
                                echo "<mark>Please Add Goods Details First!</mark>";
                            } ?>
                    </div>
                    <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                </form>
            </div>
        </div>
        <?php if (isset($_POST['paymentDetailsSubmit'])) {
            $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
            unset($_POST['paymentDetailsSubmit']);
            unset($_POST['hidden_id']);
            $post = json_encode($_POST);
            $pageURL .= "?id=" . $hidden_id . '&type=' . $record['type'];
            $data = array('payments' => $post);

            if ($hidden_id > 0) {
                $done = update('transactions', $data, array('id' => $hidden_id));
                if ($done) {
                    $info['msg'] = ' Payment details Successfully Updated.';
                    $info['type'] = 'success';
                }
            }
            messageNew($info['type'], $pageURL, $info['msg']);
        }
        if (isset($_POST['thirdPartyBankSubmit'])) {
            $transaction_hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
            $transaction_json = $_POST;
            $transaction_json = json_encode($transaction_json, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG); // Properly escape the JSON string

            if (isset($_POST['updateBankInfoToAccount']) && $_POST['updateBankInfoToAccount'] === "on") {
                $account_hidden_id = mysqli_real_escape_string($connect, $_POST['search_acc_id']);

                // Remove unnecessary fields from $_POST
                unset($_POST['search_acc_no'], $_POST['search_acc_id'], $_POST['thirdPartyBankSubmit'], $_POST['updateBankInfoToAccount']);

                // Set additional data for account
                $_POST['bankDetailsSubmit'] = '';
                $_POST['hidden_id'] = $account_hidden_id;
                $_POST['hidden_id_details'] = '0';
                $_POST['hidden_type'] = 'warehouse';

                $khaata_json = json_encode($_POST, JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG); // Escape the JSON string

                // Prepare data for updating
                $transaction_data = array('third_party_bank' => $transaction_json);
                $khaata_data = array('bank_details' => $khaata_json); // Fix to properly save JSON

                // Execute the update queries
                if (update('transactions', $transaction_data, array('id' => $transaction_hidden_id)) && update('khaata', $khaata_data, array('id' => $account_hidden_id))) {
                    $done = true;
                    $msg_array = array('msg' => 'Account + Third Party Bank details Updated.', 'type' => 'success');
                } else {
                    $done = false;
                }
            } else {
                // If no update to account, just update the transaction
                $data = array('third_party_bank' => $transaction_json);
                $done = update('transactions', $data, array('id' => $transaction_hidden_id));
                $msg_array = array('msg' => 'Third Party Bank details Successfully Updated.', 'type' => 'success');
            }
            $pageURL = $pageURL . '?id=' . $transaction_hidden_id . "&type=" . $record['type'];
            // Show a message and redirect if update was successful
            if ($done) {
                messageNew($msg_array['type'], $pageURL, $msg_array['msg']);
            }
        }
        ?>
        <script>
            var staticOptionsCache = {};

            function loadStaticTypes(url, callback) {
                if (staticOptionsCache[url]) {
                    callback(staticOptionsCache[url]);
                } else {
                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: function(response) {
                            staticOptionsCache[url] = response;
                            callback(response);
                        }
                    });
                }
            }

            function addContactRow(button) {
                var table = $(button).closest('.table-container').find('.contactsTable');
                var addButton = $(button);
                var url = addButton.data('url');
                var instance = $(button).closest('.table-container').data('instance');

                addButton.button("loading");

                var tableLength = table.find("tbody tr").length;
                var count = tableLength + 1;
                var arrayNumber = tableLength;

                addButton.button("reset");

                loadStaticTypes(url, function(staticOptions) {
                    var tr = `
            <tr class="col-md-6 contact_row_${arrayNumber}">
                <td onclick="removeContactRow(this)"><i class="fa fa-close btn fs-5 text-danger ps-0 pe-1 pt-1"></i></td>
                <td class="w-50">
                    <select name="indexes${instance}[]" class="form-select contact_indexes">${staticOptions}</select>
                </td>
                <td class="w-50">
                    <input type="text" name="vals${instance}[]" required placeholder="Value ${count}" class="form-control contact_vals">
                </td>
            </tr>`;

                    if (tableLength > 0) {
                        table.find("tbody tr:last").after(tr);
                    } else {
                        table.find("tbody").append(tr);
                    }
                });
            }

            function removeContactRow(button) {
                var row = $(button).closest('tr');
                var table = row.closest('table');
                var tableLength = table.find("tbody tr").length;
                if (tableLength > 1) {
                    row.remove();
                } else {
                    alert('error! Refresh the page again');
                }
            }

            $(document).ready(function() {
                $('.addContactRow').on('click', function() {
                    addContactRow(this);
                });
                $('#actionSelect').change(function() {
                    var selectedAction = $(this).val();
                    if (selectedAction === 'seaRoadBtn') {
                        var seaRoadModal = new bootstrap.Modal(document.getElementById('<?= $_GET['type'] == 'local' ?  "localSeaRoadDetails" : "seaRoadDetails"; ?>'));
                        seaRoadModal.show();
                    } else if (selectedAction === 'paymentsBtn') {
                        var paymentsModal = new bootstrap.Modal(document.getElementById('paymentDetails'));
                        paymentsModal.show();
                    } else if (selectedAction === 'thirdPartyBankBtn') {
                        var thirdPartyModal = new bootstrap.Modal(document.getElementById('thirdPartyBank'));
                        thirdPartyModal.show();
                    } else if (selectedAction === 'notifyParty') {
                        var thirdPartyModal = new bootstrap.Modal(document.getElementById('notifyParty'));
                        thirdPartyModal.show();
                    } else if (selectedAction === 'addReportsBtn') {
                        openModal('', '');
                    }
                });
                $('#reportsSelect').change(function() {
                    setupReports($(this).val());
                });

                $('#tax_percent').on('input', updateTaxAndTotal);
            });

            function updateTaxAndTotal() {
                let amount = parseFloat($('#amount_span').text()) || 0;
                let taxPercent = parseFloat($('#tax_percent').val()) || 0;
                let taxAmount = (amount * (taxPercent / 100)).toFixed(2);
                let totalWithTax = (amount + parseFloat(taxAmount)).toFixed(2);
                $('#tax_amount').val(taxAmount != 0 ? taxAmount : '');
                $('#total_with_tax').val(totalWithTax);
                $('#total_with_tax_span').text(totalWithTax);
            }

            function setupReports(reportType) {
                let label = reportType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                $('#addReportLabel').html(label);
                $('#reportType').val(reportType);
                $('#reportBox').val(saleReports[reportType] || '');
                let BtnText = saleReports[reportType] ? 'Update Report' : 'Add Report';
                $('#saleReportsSubmitBtn').val(BtnText);
                new bootstrap.Modal(document.getElementById('addReportModal')).show();
            }
        </script>
        <script>
            <?= $_GET['type'] === 'local' ? 'toggleLWL' : 'toggleSeaRoadDivs'; ?>();
            toggleLoadingAndRequired();
            toggleReceivingAndRequired();
            $('input[name="sea_road"]').change(function() {
                toggleSeaRoadDivs();
            });
            $('input[name="lwl"]').change(function() {
                toggleLWL();
            });
            $("#is_loading").change(toggleLoadingAndRequired);
            $("#is_receiving").change(toggleReceivingAndRequired);

            function toggleSeaRoadDivs() {
                var isSeaRoadSelected = $('input[name="sea_road"]:checked').val().trim();
                if (isSeaRoadSelected === "sea") {
                    $('.toggleBySea').show();
                    $('.toggleByRoad').hide();
                } else if (isSeaRoadSelected === "road") {
                    $('.toggleBySea').hide();
                    $('.toggleByRoad').show();
                } else {
                    console.log("Unexpected value: ", isSeaRoadSelected);
                }
            }

            function toggleLWL() {
                var isLWLSelected = $('input[name="lwl"]:checked').val();
                if (isLWLSelected === "local") {
                    $('.toggleByLocal').show();
                    $('.toggleByWarehouse').hide();
                    $('.toggleByLaunch').hide();
                } else if (isLWLSelected === "warehouse") {
                    $('.toggleByLocal').hide();
                    $('.toggleByWarehouse').show();
                    $('.toggleByLaunch').hide();
                } else if (isLWLSelected === "launch") {
                    $('.toggleByLocal').hide();
                    $('.toggleByWarehouse').hide();
                    $('.toggleByLaunch').show();
                } else {
                    console.log("Unexpected value: ", isLWLSelected);
                }
            }

            function toggleLoadingAndRequired() {
                var $toggleLoading = $(".toggleLoading");
                var $is_qty2 = $("#is_loading");
                if ($is_qty2.is(":checked")) {
                    $toggleLoading.show();
                    $("#l_country, #l_port, #l_date, #ctr_name").attr('required', true);
                } else {
                    $toggleLoading.hide();
                    $("#l_country, #l_port, #l_date, #ctr_name").attr('required', false);
                }
            }

            function toggleReceivingAndRequired() {
                var $toggleReceiving = $(".toggleReceiving");
                var $is_receiving = $("#is_receiving");
                if ($is_receiving.is(":checked")) {
                    $toggleReceiving.show();
                    $("#r_country, #r_port, #r_date, #arrival_date").attr('required', true);
                } else {
                    $toggleReceiving.hide();
                    $("#r_country, #r_port, #r_date, #arrival_date").attr('required', false);
                }
            }
        </script>
        <script>
            $(document).ready(function() {
                function Percentage(percentage, total) {
                    // Check if either value is not a number or is less than 0
                    if (isNaN(percentage) || isNaN(total) || percentage < 0 || total < 0) {
                        console.error("Invalid input: both percentage and total should be valid positive numbers.");
                        return null;
                    }
                    return ((percentage / 100) * total).toFixed(2);
                }

                function partialValues() {
                    var p_total_amount = $('#p_total_amount').val();
                    var pct_value = $('#pct_value').val();
                    var pct = Percentage(pct_value, p_total_amount);
                    var baqi = Number(p_total_amount - pct).toFixed(2);
                    $('#partial_amount1').val(pct);
                    $('#partial_amount2').val(baqi);

                    $('#adv_values1').html('<td>' + pct_value + '% PAYMENT </td><th>' + pct + '</th>');
                    $('#adv_values2').html('<td>' + (100 - pct_value) + '% PAYMENT </td><th>' + baqi + '</th>');
                }


                function toggleInputs() {
                    var selectedValue = $('input[name="full_advance"]:checked').val();

                    if (selectedValue === 'full') {
                        $('#adv_values1').html('');
                        $('#adv_values2').html('');

                        $("#date-report-inputs").html('<div class="row gx-1 mt-3">' +
                            '<div class="col-md-auto"><div class="input-group"><label for="full_date">100% Payment Date</label>' +
                            '<input type="date" class="form-control" id="full_date" name="full_date" value="<?= isset($_fields['payment_details']->full_date) ? $_fields['payment_details']->full_date : ''; ?>" required>' +
                            '</div></div>' +
                            '<div class="col-md"><div class="input-group"><label for="full_report">Report</label>' +
                            '<input type="text" class="form-control" id="full_report" name="full_report" value="<?= isset($_fields['payment_details']->full_report) ? $_fields['payment_details']->full_report : ''; ?>" required>' +
                            '</div></div></div>');

                        $('#pct_value').closest('.input-group').hide();
                        $('#pct_value').attr('required', false);

                    } else if (selectedValue === 'advance') {
                        partialValues();

                        var pct_value = $('#pct_value').val();

                        $("#date-report-inputs").html('<div class="row gx-1 mt-3">' +
                            '<div class="col-md-auto"><div class="input-group"><label for="partial_date1">' + pct_value + ' % Payment Date</label>' +
                            '<input type="date" class="form-control" id="partial_date1" name="partial_date1" value="<?= isset($_fields['payment_details']->partial_date1) ? $_fields['payment_details']->partial_date1 : ''; ?>" required></div></div>' +
                            '<div class="col-md"><div class="input-group"><label for="partial_report1">Report</label>' +
                            '<input type="text" class="form-control" id="partial_report1" name="partial_report1" value="<?= isset($_fields['payment_details']->partial_report1) ? $_fields['payment_details']->partial_report1 : ''; ?>" required>' +
                            '</div></div></div>' +
                            '<div class="row gx-1 mt-3">' +
                            '<div class="col-md-auto"><div class="input-group"><label for="partial_date2">' + (100 - pct_value) + '% Payment Date</label>' +
                            '<input type="date" class="form-control" id="partial_date2" name="partial_date2" value="<?= isset($_fields['payment_details']->partial_date2) ? $_fields['payment_details']->partial_date2 : ''; ?>" required></div></div>' +
                            '<div class="col-md"><div class="input-group"><label for="partial_report2">Report</label>' +
                            '<input type="text" class="form-control" id="partial_report2" name="partial_report2" value="<?= isset($_fields['payment_details']->partial_report2) ? $_fields['payment_details']->partial_report2 : ''; ?>" required>' +
                            '</div></div></div>');

                        $('#pct_value').closest('.input-group').show();
                        $('#pct_value').attr('required', true);
                    } else if (selectedValue === 'credit') {
                        $('#adv_values1').html('');
                        $('#adv_values2').html('');

                        $("#date-report-inputs").html('<div class="row gx-1 mt-3">' +
                            '<div class="col-md-auto"><div class="input-group"><label for="credit_date">Credit Payment Date</label>' +
                            '<input type="date" class="form-control" id="credit_date" name="credit_date" value="<?= isset($_fields['payment_details']->credit_date) ? $_fields['payment_details']->credit_date : ''; ?>" required>' +
                            '</div></div>' +
                            '<div class="col-md"><div class="input-group"><label for="credit_report">Report</label>' +
                            '<input type="text" class="form-control" id="credit_report" name="credit_report" value="<?= isset($_fields['payment_details']->credit_report) ? $_fields['payment_details']->credit_report : ''; ?>" required>' +
                            '</div></div></div>');

                        $('#pct_value').closest('.input-group').hide();
                        $('#pct_value').attr('required', false);

                    }

                }

                toggleInputs();
                $('input[name="full_advance"]').on('change', function() {
                    toggleInputs();
                });
                $('#pct_value').on('change, keyup', function() {
                    toggleInputs();
                });
            });
        </script>
        <!-- <script>
    function presetValue(SelectValue = '') {
        let reportType = SelectValue !== '' ? SelectValue : document.getElementById('reportType').value;
        let reportTextArea = document.getElementById('reportBox');

        let content = {};
        if (reportType === 'goods_details') {
            content = JSON.parse(document.getElementById('goodsTotalsJSON').value);
        } else if (reportType === 'loading_details') {
            content = JSON.parse(document.getElementById('loadingDetailsJSON').value);
        } else if (reportType === 'payment_details') {
            content = JSON.parse(document.getElementById('paymentDetailsJSON').value);
        }

        if (Object.keys(content).length > 0) {
            let contentString = '';
            for (const [key, value] of Object.entries(content)) {
                contentString += `${key}: ${value} `;
            }
            contentString = contentString.trim();

            let currentText = reportTextArea.value.trim();
            // Check for duplication before prepending
            if (!currentText.startsWith(contentString)) {
                reportTextArea.value = contentString + ' ' + currentText;
            }
        } else {
            reportTextArea.value = ''; // Clear the textarea if no content is found
        }
    }


    // This listener will clear the textarea when the report type changes
    document.getElementById('reportType').addEventListener('change', function() {
        document.getElementById('reportBox').value = ''; // Clear the textarea
        presetValue(this.value); // Set the preset value based on the new selection
    });

    

    // let reportsData;
</script> -->

        <script>
            function openModal(reportType = '', reportText = '') {
                document.getElementById('customModal').style.display = 'block';
                document.getElementById('reportType').value = reportType !== '' ? reportType : '';
                presetValue(reportType);
                if (reportText !== '') {
                    let reportForEdit = document.getElementById(reportText).textContent.trim();
                    document.getElementById('reportBox').value = reportForEdit;
                    if (reportType === '' && reportText === '') {
                        document.getElementById('modalHeading').innerText = 'Add Report';
                        document.getElementById('modalButton').innerText = 'Add Report';
                        document.getElementById('reportBox').value = '';
                    } else {
                        document.getElementById('modalHeading').innerText = 'Update Report';
                        document.getElementById('modalButton').innerText = 'Update Report';
                    }
                }
            }

            function closeModal() {
                document.getElementById('customModal').style.display = 'none';
            }
            window.onclick = event => {
                if (event.target === document.getElementById('customModal')) closeModal();
            };
        </script>