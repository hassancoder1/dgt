<?php $page_title = 'Purchase Entry';
$back_page_url = 'purchases';
$pageURL = "purchase-add";
include("header.php");
$id = $item_id = 0;
if (!isset($_GET['type'])) {
    echo "<script>window.location.href='purchases';</script>";
}
global $userId, $userName, $branchId;
$_fields = [
    'sr_no' => getAutoIncrement('transactions'),
    'username' => $userName,
    'branch_id' => $branchId,
    'p_s' => '',
    'type' => '',
    'active' => 1,
    'locked' => 0,
    '_date' => date('Y-m-d'),
    'country' => '',
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
    'transaction_accounts_cr_id' => 0
];
$item_fields = ['p_s' => 'p', 'sr' => transactionItemsSerial($id, 'p'), 'goods_id' => 0, 'size' => '', 'brand' => '', 'origin' => '', 'qty_name' => '', 'qty_no' => 0, 'qty_kgs' => 0, 'total_kgs' => 0, 'empty_kgs' => 0, 'total_qty_kgs' => 0, 'net_kgs' => 0, 'divide' => '', 'weight' => 0, 'total' => 0, 'price' => '', 'currency1' => '', 'rate1' => 0, 'amount' => 0, 'currency2' => 'AED', 'rate2' => '', 'opr' => '*', 'final_amount' => 0];
$sea_road = ['sea_road' => 'sea', 'l_country_road' => '', 'l_border_road' => '', 'l_date_road' => date('Y-m-d'), 'truck_container' => '', 'r_country_road' => '', 'r_border_road' => '', 'r_date_road' => date('Y-m-d'), 'd_date_road' => date('Y-m-d'), 'is_loading' => 0, 'l_country' => '', 'l_port' => '', 'l_date' => date('Y-m-d'), 'ctr_name' => '', 'is_receiving' => 0, 'r_country' => '', 'r_port' => '', 'r_date' => date('Y-m-d'), 'arrival_date' => date('Y-m-d'), 'report' => ''];
$bank_details = ['acc_no' => '', 'acc_name' => '', 'company' => '', 'iban' => '', 'branch_code' => '', 'currency' => '', 'country' => '', 'state' => '', 'city' => '', 'address' => '', 'indexes4' => [], 'vals4' => []];
$NP_details = ['np_acc' => '', 'np_acc_name' => '', 'np_acc_id' => '', 'np_acc_kd_id' => '', 'np_acc_details' => '', 'notifyPartyDetailsSubmit' => '', 'hidden_id' => ''];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    if (!recordExists('transactions', ['id' => $id])) {
        messageNew('warning', $pageURL, 'Something went wrong!');
    }

    /*$dr_record = getTransactionAccounts($id, 'purchase', 'dr');
    $cr_record = getTransactionAccounts($id, 'purchase', 'cr');
    $_fields = ['sr_no' => $id, 'username' => userName($record['created_by']), 'branch_id' => $record['branch_id'], 'p_s' => $record['p_s'], 'type' => $record['type'], 'active' => $record['active'], 'locked' => $record['locked'], '_date' => $record['_date'], 'country' => $record['country'], 'action' => 'update',
        'dr_acc' => $dr_record['acc'], 'dr_acc_name' => $dr_record['acc_name'], 'dr_acc_details' => $dr_record['details'], 'dr_acc_id' => $dr_record['acc_id'], 'dr_acc_kd_id' => $dr_record['acc_kd_id'],
        'cr_acc' => $cr_record['acc'], 'cr_acc_name' => $cr_record['acc_name'], 'cr_acc_details' => $cr_record['details'], 'cr_acc_id' => $cr_record['acc_id'], 'cr_acc_kd_id' => $cr_record['acc_kd_id'],
        'transaction_accounts_dr_id' => $dr_record['id'], 'transaction_accounts_cr_id' => $cr_record['id']];*/
    $_fields = transactionSingle($id);
    if (!empty($record['sea_road'])) {
        $json_sea_road = json_decode($record['sea_road']);
        $sea_road = ['sea_road' => $json_sea_road->sea_road, 'l_country_road' => $json_sea_road->l_country_road, 'l_border_road' => $json_sea_road->l_border_road, 'l_date_road' => $json_sea_road->l_date_road, 'truck_container' => $json_sea_road->truck_container, 'r_country_road' => $json_sea_road->r_country_road, 'r_border_road' => $json_sea_road->r_border_road, 'r_date_road' => $json_sea_road->r_date_road, 'd_date_road' => $json_sea_road->d_date_road, 'is_loading' => $json_sea_road->is_loading, 'l_country' => $json_sea_road->l_country, 'l_port' => $json_sea_road->l_port, 'l_date' => $json_sea_road->l_date, 'ctr_name' => $json_sea_road->ctr_name, 'is_receiving' => $json_sea_road->is_receiving, 'r_country' => $json_sea_road->r_country, 'r_port' => $json_sea_road->r_port, 'r_date' => $json_sea_road->r_date, 'arrival_date' => $json_sea_road->arrival_date, 'report' => $json_sea_road->report];
    }
    if (isset($_GET['item_id']) && $_GET['item_id'] > 0) {
        $item_id = mysqli_real_escape_string($connect, $_GET['item_id']);
        $records2 = fetch('transaction_items', array('id' => $item_id));
        $record2 = mysqli_fetch_assoc($records2);
        $item_fields = ['p_s' => $record2['p_s'], 'sr' => $record2['sr'], 'allotment_name' => $record2['allotment_name'], 'goods_id' => $record2['goods_id'], 'size' => $record2['size'], 'brand' => $record2['brand'], 'origin' => $record2['origin'], 'qty_name' => $record2['qty_name'], 'qty_no' => $record2['qty_no'], 'qty_kgs' => $record2['qty_kgs'], 'total_kgs' => $record2['total_kgs'], 'empty_kgs' => $record2['empty_kgs'], 'total_qty_kgs' => $record2['total_qty_kgs'], 'net_kgs' => $record2['net_kgs'], 'divide' => $record2['divide'], 'weight' => $record2['weight'], 'total' => $record2['total'], 'price' => $record2['price'], 'currency1' => $record2['currency1'], 'rate1' => $record2['rate1'], 'amount' => $record2['amount'], 'currency2' => $record2['currency2'], 'rate2' => $record2['rate2'], 'opr' => $record2['opr'], 'final_amount' => $record2['final_amount']];
    }

    $bank_details = json_decode(decodeSpecialCharacters($record['third_party_bank']), true);

    $NP_details = json_decode($record['notify_party_details']);
    if (!empty($NP_details)) {
        $keys = ['np_acc', 'np_acc_name', 'np_acc_id', 'np_acc_kd_id', 'np_acc_details', 'notifyPartyDetailsSubmit', 'hidden_id'];
        $NP_details = array_filter(get_object_vars($NP_details), fn($key) => in_array($key, $keys), ARRAY_FILTER_USE_KEY);
    }
} ?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex gap-1">
            <?php echo backUrl($back_page_url);
            // echo addNew($pageURL, '', 'btn-sm'); 
            ?>
            <div class="dropdown me-2">
                <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    New
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                    // Fetch the types from the database
                    $static_types = fetch('static_types', ['type_for' => 'ps_types']);

                    // Loop through each type and render it as a dropdown item with links
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        // Generate a URL parameter for each link based on `type_name`
                        echo '<li><a class="dropdown-item" href="purchase-add?type=' . urlencode($static_type['type_name']) . '">' . htmlspecialchars($static_type['details']) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="position-absolute end-0 top-0 m-2">
                <a class="btn btn-link text-dark" data-bs-toggle="collapse" href="#collapseFirst" role="button" aria-expanded="false" aria-controls="collapseFirst">
                    <i class="fa fa-angle-down"></i>
                </a>
            </div>
            <div class="card-body">
                <form method="post" class="collapse show" id="collapseFirst">
                    <div class="row g-3">
                        <!-- Purchase Section -->
                        <div class="col-lg-3 col-md-6">
                            <!-- <h6 class="fw-bold text-danger"></h6> -->
                            <div class="mb-2">
                                <label for="dr_acc" class="form-label text-danger fw-semibold">Cr.A/C (PURCHASE)</label>
                                <input type="text" id="dr_acc" name="dr_acc" class="form-control" required value="<?php echo $_fields['dr_acc']; ?>">
                                <input type="text" id="dr_acc_name" name="dr_acc_name" class="form-control mt-1" value="<?php echo $_fields['dr_acc_name']; ?>" readonly tabindex="-1">
                            </div>
                            <input type="hidden" name="dr_acc_id" id="dr_acc_id" value="<?php echo $_fields['dr_acc_id']; ?>">
                            <div class="mb-2">
                                <!-- <label for="dr_acc_kd_id" class="form-label">COMPANY</label> -->
                                <select class="form-select" name="dr_acc_kd_id" id="dr_acc_kd_id">
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
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="dr_acc_details" id="dr_acc_details" rows="6" placeholder="Company Details"><?php echo $_fields['dr_acc_details']; ?></textarea>
                            </div>
                        </div>

                        <!-- Sale Section -->
                        <div class="col-lg-3 col-md-6">
                            <!-- <h6 class="fw-bold text-success"></h6> -->
                            <div class="mb-2">
                                <label for="cr_acc" class="form-label text-success fw-bold">Dr.A/C (SALE)</label>
                                <input type="text" id="cr_acc" name="cr_acc" class="form-control" required value="<?php echo $_fields['cr_acc']; ?>">
                                <input type="text" id="cr_acc_name" name="cr_acc_name" class="form-control mt-1" value="<?php echo $_fields['cr_acc_name']; ?>" readonly tabindex="-1">
                            </div>
                            <input type="hidden" name="cr_acc_id" id="cr_acc_id" value="<?php echo $_fields['cr_acc_id']; ?>">
                            <div class="mb-2">
                                <select class="form-select" name="cr_acc_kd_id" id="cr_acc_kd_id">
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
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="cr_acc_details" id="cr_acc_details" rows="6" placeholder="Company Details"><?php echo $_fields['cr_acc_details']; ?></textarea>
                            </div>
                        </div>

                        <!-- Notify Party Section -->
                        <div class="col-lg-3 col-md-6">
                            <!-- <h6 class="fw-bold text-primary"></h6> -->
                            <div class="mb-2">
                                <label for="np_acc" class="form-label text-primary fw-bold">ACC No. (NOTIFY PARTY)</label>
                                <input type="text" id="np_acc" name="np_acc" class="form-control" required value="<?= isset($NP_details['np_acc']) ? $NP_details['np_acc'] : ''; ?>">
                                <input type="text" id="np_acc_name" name="np_acc_name" class="form-control mt-1" value="<?= isset($NP_details['np_acc_name']) ? $NP_details['np_acc_name'] : ''; ?>" readonly tabindex="-1">
                            </div>
                            <input type="hidden" name="np_acc_id" id="np_acc_id" value="<?= isset($NP_details['np_acc_id']) ? $NP_details['np_acc_id'] : ''; ?>">
                            <div class="mb-2">
                                <select class="form-select" name="np_acc_kd_id" id="np_acc_kd_id">
                                    <option hidden value="">Select Company</option>
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
                            <div class="mb-2">
                                <textarea class="form-control form-control-sm" name="np_acc_details" id="np_acc_details" rows="6" placeholder="Company Details"><?= isset($NP_details['np_acc_details']) ? $NP_details['np_acc_details'] : ''; ?></textarea>
                            </div>
                        </div>

                        <!-- Additional Details Section -->
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <p><b>Sr#</b> <?php echo $_fields['sr_no']; ?></p>
                                    <p><b>User</b> <?php echo strtoupper($_fields['username']); ?></p>
                                    <p><b>Type:</b> <?php echo strtoupper($_GET['type']); ?></p>
                                </div>
                                <div>
                                    <label for="_date" class="form-label"><b>Date</b></label>
                                    <input type="date" value="<?php echo $_fields['_date']; ?>" id="_date" name="_date" class="form-control">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label for="branch_id" class="form-label"><b>Branch</b></label>
                                <select id="branch_id" name="branch_id" class="form-select">
                                    <?php
                                    $branches = SuperAdmin() ? fetch('branches') : fetch('branches', ['id' => $_fields['branch_id']]);
                                    while ($b = mysqli_fetch_assoc($branches)) {
                                        $b_select = $b['id'] == $_fields['branch_id'] ? 'selected' : '';
                                        echo '<option ' . $b_select . ' value="' . $b['id'] . '">' . $b['b_code'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-2">
                                <label for="country" class="form-label"><b>Delivery Terms</b></label>
                                <input type="text" value="<?php echo $_fields['country']; ?>" id="country" name="country" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-dark btn-sm w-100">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($id > 0) { ?>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if (!empty($_fields['sea_road_array'])): ?>
                                <div class="row gy-1 py-1">
                                    <div class="col-md-12">
                                        <span class="fs-6 fw-bold">By </span>
                                        <?php echo $_fields['sea_road']; ?>
                                    </div>
                                    <!-- Loading Country Column -->
                                    <div class="col-md-6">
                                        <div class="fs-6 fw-bold">Loading Details</div>
                                        <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                            <?php
                                            // Check if the key starts with 'l_' to identify loading-related fields
                                            if (strpos($key, 'l_') === 0):
                                                if (is_array($value)) {
                                                    echo '<b>' . $value[0] . ':</b> ' . $value[1] . '<br>';
                                                } else {
                                                    echo '<b>' . strtoupper($key) . ':</b> ' . $value . '<br>';
                                                }
                                            endif;
                                            ?>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Receiving Country Column -->
                                    <div class="col-md-6">
                                        <div class="fs-6 fw-bold">Receiving Details</div>
                                        <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                            <?php
                                            // Check if the key starts with 'r_' (receiving-related) or 'd_' (delivery-related)
                                            if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0):
                                                if (is_array($value)) {
                                                    // For delivery dates, show the label as "Arrival Date" instead of just the key
                                                    $label = ($key === 'd_date_road') ? 'Arrival Date' : $value[0];
                                                    echo '<b>' . $label . ':</b> ' . $value[1] . '<br>';
                                                } else {
                                                    echo '<b>' . strtoupper($key) . ':</b> ' . $value . '<br>';
                                                }
                                            endif;
                                            ?>
                                        <?php endforeach; ?>
                                    </div>


                                    <!-- Report Section -->
                                    <?php if (isset($_fields['sea_road_report'])): ?>
                                        <div class="col-md-12">
                                            <span class="fs-6 fw-bold">Report: </span>
                                            <?php echo $_fields['sea_road_report']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <!-- <?php if (!empty($_fields['sea_road'])) { ?>
                                <div class="row row-cols-md-4 row-cols-3 gy-2">
                                    <div class="col-md-12 text-uppercase fs-6"><?php echo '<b>By ' . $_fields['sea_road']; ?></b></div>
                                    <?php if (!empty($_fields['sea_road_array'])): ?>
                                        <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                            <?php if ($key === 'is_loading' || $key === 'is_receiving') continue;
                                                if (($key === 'l_country' || $key === 'l_port' || $key === 'l_date' || $key === 'ctr_name') && $_fields['sea_road_array']['is_loading'][1] == 0) continue;
                                                if (($key === 'r_country' || $key === 'r_port' || $key === 'r_date' || $key === 'arrival_date') && $_fields['sea_road_array']['is_receiving'][1] == 0) continue; ?>
                                            <?php if (is_array($value)): ?>
                                                <div class="col">
                                                    <b><?php echo $value[0]; ?> </b><br><?php echo $value[1]; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="col">
                                                    <b><?php echo strtoupper($key); ?> </b><?php echo $value; ?></div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    <div class="col-md-12"><?php echo '<b>Report </b>' . $_fields['sea_road_report']; ?></div>
                                </div>
                            <?php } ?> -->
                        </div>
                        <!-- Payment Details Column -->
                        <div class="col-md-6 mt-3">
                            <div class="fs-6 fw-bold">Payment Details</div>

                            <?php
                            // Ensure $payments is an object and access properties using -> syntax
                            $payments = $_fields['payment_details'];
                            $total_amount = isset($_fields['items_sum']['sum_final_amount']) ? (float)$_fields['items_sum']['sum_final_amount'] : 0;

                            // Calculate remaining percentage
                            $percentage = isset($payments->pct_value) ? (int)$payments->pct_value : 0;
                            $remaining_percentage = 100 - $percentage;

                            // Calculate the amounts for partial payments
                            $partial_amount1 = ($percentage / 100) * $total_amount;
                            $partial_amount2 = ($remaining_percentage / 100) * $total_amount;

                            // Check the type of payment (advance or full)
                            if (isset($payments->full_advance) && $payments->full_advance === 'advance') {
                            ?>
                                <!-- Show partial amounts, dates, and reports if type is 'advance' -->
                                <b>Type:</b> <?= ucfirst($payments->full_advance); ?> - <?= $percentage; ?>% (Remaining: <?= $remaining_percentage; ?>%)<br>

                                <b>Partial Amount 1 (<?= $percentage; ?>%):</b> <?= number_format($partial_amount1, 2); ?><br>
                                <b>Date:</b> <?= $payments->partial_date1; ?>
                                <b>Report:</b> <?= ucfirst($payments->partial_report1); ?><br>

                                <b>Partial Amount 2 (<?= $remaining_percentage; ?>%):</b> <?= number_format($partial_amount2, 2); ?><br>
                                <b>Date:</b> <?= $payments->partial_date2; ?>
                                <b>Report:</b> <?= ucfirst($payments->partial_report2); ?><br>
                            <?php
                            } elseif (isset($payments->full_advance) && $payments->full_advance === 'full') {
                            ?>
                                <!-- Show full payment details if type is 'full' -->
                                <b>Type:</b> Full Payment<br>
                                <b>Total Amount:</b> <?= number_format($total_amount, 2); ?><br>
                                <b>Date:</b> <?= $payments->full_date; ?><br>
                                <b>Report:</b> <?= ucfirst($payments->full_report); ?><br>
                            <?php
                            } elseif (isset($payments->full_advance) && $payments->full_advance === 'credit') {
                            ?>
                                <!-- Show full payment details if type is 'full' -->
                                <b>Type:</b> Credit Payment<br>
                                <b>Total Amount:</b> <?= number_format($total_amount, 2); ?><br>
                                <b>Date:</b> <?= $payments->credit_date; ?><br>
                                <b>Report:</b> <?= ucfirst($payments->credit_report); ?><br>
                            <?php
                            } else {
                                // Show default or empty values if no type is selected
                                echo "<b>No payment details available.</b>";
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        <?php }
        if ($item_id == 0) { ?>
            <div class="card mb-2">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr class="text-nowrap">
                                    <th>#</th>
                                    <th>GOODS</th>
                                    <th>SIZE</th>
                                    <th>BRAND</th>
                                    <th>ORIGIN</th>
                                    <th>QTY</th>
                                    <th>KGs</th>
                                    <th>EMPTY</th>
                                    <th>NET KGs</th>
                                    <th>Wt.</th>
                                    <th>TOTAL</th>
                                    <th>PRICE</th>
                                    <th>AMOUNT</th>
                                    <th class="text-end">FINAL</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sr_details = 1;
                                $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                                $pur_d_q = fetch('transaction_items', array('parent_id' => $id));
                                while ($details = mysqli_fetch_assoc($pur_d_q)) {
                                    $details_id = $details['id'];
                                    echo '<tr>';
                                    echo '<td>' . $details['sr'] . '</td>';
                                    echo '<td><a href="' . $pageURL . '?id=' . $id . '&item_id=' . $details_id . '" class="text-dark">' . goodsName($details['goods_id']) . '</a></td>';
                                    echo '<td>' . $details['size'] . '</td>';
                                    echo '<td>' . $details['brand'] . '</td>';
                                    echo '<td>' . $details['origin'] . '</td>';
                                    echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                                    echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                    echo '<td>' . round($details['total_qty_kgs'], 2) . '</td>';
                                    echo '<td>' . round($details['net_kgs'], 2);
                                    echo '<sub>' . $details['divide'] . '</sub>';
                                    echo '</td>';
                                    echo '<td>' . $details['weight'] . '</td>';
                                    echo '<td>' . $details['total'] . '</td>';
                                    echo '<td>' . $details['price'] . '</td>';
                                    echo '<td>' . round($details['amount'], 2);
                                    echo '<sub>' . $details['currency1'] . '</sub>';
                                    echo '</td>';
                                    echo '<td class="text-end">' . round($details['final_amount'], 2);
                                    echo '<sub>' . $details['currency2'] . '</sub>';
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
                                }
                                if ($qty_no > 0) {
                                    echo '<tr>';
                                    echo '<th colspan="5"></th>';
                                    echo '<th class="fw-bold">' . $qty_no . '</th>';
                                    echo '<th class="fw-bold">' . round($total_kgs, 2) . '</th>';
                                    echo '<th class="fw-bold">' . round($total_qty_kgs, 2) . '</th>';
                                    echo '<th class="fw-bold">' . round($net_kgs, 2) . '</th>';
                                    echo '<th colspan="1"></th>';
                                    echo '<th class="fw-bold">' . round($total, 2) . '</th>';
                                    echo '<th></th>';
                                    echo '<th class="fw-bold">' . round($amount, 2) . '</th>';
                                    echo '<th class="fw-bold text-end">' . round($final_amount, 2) . '</th>';
                                    echo '<th></th>';
                                    echo '</tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($id > 0) { ?>
            <div class="card mb-2">
                <div class="position-absolute end-0 top-0">
                    <a class="btn btn-link text-dark" data-bs-toggle="collapse" href="#collapseTow" role="button"
                        aria-expanded="false" aria-controls="collapseTow">
                        <i class="fa fa-angle-down"></i>
                    </a>
                </div>
                <div class="card-body">
                    <form method="post" class="table-form collapse show" id="collapseTow">
                        <div class="row gy-3">
                            <div class="col-md-4">
                                <div class="row gx-1 gy-3">
                                    <div class="col-md-7">
                                        <div><b>Sr# </b> <?php echo $_fields['sr_no']; ?></div>
                                        <div class="row g-0">
                                            <label for="allotment_name" class="col-md-6 col-form-label text-nowrap">Allotment Name</label>
                                            <div class="col-md-6">
                                                <input value="<?= isset($item_fields['allotment_name']) ? $item_fields['allotment_name'] : ''; ?>" id="allotment_name"
                                                    name="allotment_name" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <label for="goods_id">GOODS</label>
                                            <select id="goods_id" name="goods_id" class="form-select" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods = fetch('goods');
                                                while ($good = mysqli_fetch_assoc($goods)) {
                                                    $g_selected = $good['id'] == $item_fields['goods_id'] ? 'selected' : '';
                                                    echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="size">SIZE</label>
                                            <select class="form-select" name="size" id="size" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['size'] == $item_fields['size'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <label for="origin">ORIGIN</label>
                                            <select class="form-select" name="origin" id="origin" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['origin'] == $item_fields['origin'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['origin'] . '">' . $size_s['origin'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <label for="brand">BRAND</label>
                                            <select class="form-select" name="brand" id="brand" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = " . $item_fields['goods_id']);
                                                while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                                    $size_selected = $size_s['brand'] == $item_fields['brand'] ? 'selected' : '';
                                                    echo '<option ' . $size_selected . ' value="' . $size_s['brand'] . '">' . $size_s['brand'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 border-end">
                                <div class="row gx-1 gy-3">
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label for="qty_name" class="col-sm-4 col-form-label text-nowrap">Qty
                                                Name</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['qty_name']; ?>" id="qty_name"
                                                    name="qty_name" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="qty_no">Qty#</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['qty_no']; ?>" id="qty_no"
                                                    name="qty_no"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap" for="qty_kgs">Qty
                                                KGs</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['qty_kgs']; ?>" id="qty_kgs"
                                                    name="qty_kgs"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap" for="empty_kgs">Empty
                                                KGs</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['empty_kgs']; ?>"
                                                    id="empty_kgs"
                                                    name="empty_kgs" class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="divide">DIVIDE</label>
                                            <div class="col-sm">
                                                <select id="divide" name="divide" class="form-select">
                                                    <?php $divides = array('TON' => 'TON', 'KGs' => 'KG', 'CARTON' => 'CARTON');
                                                    foreach ($divides as $item => $val) {
                                                        $d_sel = $item_fields['divide'] == $val ? 'selected' : '';
                                                        echo '<option ' . $d_sel . ' value="' . $val . '">' . $item . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="weight">WEIGHT</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['weight']; ?>" id="weight"
                                                    name="weight"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="price">PRICE</label>
                                            <div class="col-sm">
                                                <select id="price" name="price" class="form-select">
                                                    <?php $prices = array('TON PRICE' => 'TON', 'KGs PRICE' => 'KG', 'CARTON PRICE' => 'CARTON');
                                                    foreach ($prices as $item => $val) {
                                                        $pr_sel = $item_fields['price'] == $val ? 'selected' : '';
                                                        echo '<option ' . $pr_sel . ' value="' . $val . '">' . $item . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="currency1">Currency</label>
                                            <div class="col-sm">
                                                <select id="currency1" name="currency1" class="form-select"
                                                    required>
                                                    <option selected hidden disabled value="">Select</option>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $crr_sel = $crr['name'] == $item_fields['currency1'] ? 'selected' : '';
                                                        echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="rate1">RATE</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['rate1']; ?>" id="rate1"
                                                    name="rate1"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="currency2">Currency</label>
                                            <div class="col-sm">
                                                <select id="currency2" name="currency2" class="form-select"
                                                    required>
                                                    <option selected hidden disabled value="">Select</option>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $crr_sel2 = $crr['name'] == $item_fields['currency2'] ? 'selected' : '';
                                                        echo '<option ' . $crr_sel2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                for="rate2">Rate</label>
                                            <div class="col-sm">
                                                <input value="<?php echo $item_fields['rate2']; ?>" id="rate2"
                                                    name="rate2"
                                                    class="form-control currency" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row g-0">
                                            <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                            <div class="col-sm">
                                                <select id="opr" name="opr" class="form-select" required>
                                                    <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                    foreach ($ops as $opName => $op) {
                                                        $op_sel = $item_fields['opr'] == $op ? 'selected' : '';
                                                        echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <table class="table table-sm">
                                    <tbody class="text-nowrap">
                                        <?php
                                        echo '<tr><th class="fw-normal">TOTAL KGs </th><th><span id="total_kgs_span"></span></th></tr>';
                                        echo '<tr><th class="fw-normal">TOTAL QTY KGs </th><th><span id="total_qty_kgs_span"></span></th></tr>';
                                        echo '<tr><th class="fw-normal">NET KGs </th><th><span id="net_kgs_span"></span></th></tr>';
                                        echo '<tr><th class="fw-normal">TOTAL </th><th><span id="total_span"></span></th></tr>';
                                        echo '<tr><th class="fw-normal">AMOUNT  </th><th><span id="amount_span"></span></th></tr>';
                                        echo '<tr><th class="fw-normal text-danger">FINAL  </th><th><span id="final_amount_span"></span></th></tr>';
                                        ?>
                                    </tbody>
                                </table>
                                <input value="<?php echo $item_fields['total_kgs']; ?>" id="total_kgs"
                                    name="total_kgs" type="hidden">
                                <input value="<?php echo $item_fields['total_qty_kgs']; ?>" id="total_qty_kgs"
                                    name="total_qty_kgs"
                                    type="hidden">
                                <input value="<?php echo $item_fields['net_kgs']; ?>" id="net_kgs" name="net_kgs"
                                    type="hidden">
                                <input value="<?php echo $item_fields['total']; ?>" id="total" name="total"
                                    type="hidden">
                                <input value="<?php echo $item_fields['amount']; ?>" id="amount" name="amount"
                                    type="hidden">
                                <input value="<?php echo $item_fields['final_amount']; ?>" id="final_amount"
                                    name="final_amount" type="hidden">
                                <div class="d-flex align-items-center justify-content-between">
                                    <button name="recordSubmit" id="recordSubmit" type="submit"
                                        class="btn btn-dark">Submit
                                    </button>
                                    <?php //echo $id > 0 ? addNew($pageURL . '?id=' . $id . '&action=add_details') : '';
                                    echo $item_id > 0 ? backUrl('purchase-add?id=' . $id) : '';
                                    ?>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                        <input type="hidden" name="hidden_item_id" value="<?php echo $item_id; ?>">
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<script type="text/javascript">
    $(document).on('keyup', "#dr_acc", function(e) {
        fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "purchaseSubmit");
    });
    //fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "purchaseSubmit");

    $(document).on('keyup', "#cr_acc", function(e) {
        fetchKhaata("#cr_acc", "#cr_acc_id", "#cr_acc_kd_id", "purchaseSubmit");
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
    $(document).ready(function() {
        finalAmount();
        $('#qty_no,#qty_kgs,#empty_kgs,#weight,#rate1,#rate2,#opr').on('keyup', function() {
            finalAmount();
        });

        /*toggleQtyAndRequired();
        $("#is_qty").change(toggleQtyAndRequired);
        $('#opr').on('change', function () {
            finalAmount();
        });*/

        /*var goods_id = $('#goods_id').find(":selected").val();
        goodDetails(goods_id);*/
        $("#goods_id").change(function() {
            var goods_id = $(this).val();
            goodDetails(goods_id);
        });
        $("#type").change(function() {
            $('#bookingForm').toggleClass('d-none row');
            $('#localForm').toggleClass('d-none row');
        });
    });

    function goodDetails(goods_id) {
        if (goods_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_sizes.php',
                data: 'goods_id=' + goods_id,
                success: function(html) {
                    $('#size').html(html);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_brands.php',
                data: 'goods_id=' + goods_id,
                success: function(html) {
                    $('#brand').html(html);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_origins.php',
                data: 'goods_id=' + goods_id,
                success: function(html) {
                    $('#origin').html(html);
                }
            });
        } else {
            $('#size').html('<option value="">Select</option>');
            $('#brand').html('<option value="">Select</option>');
            $('#origin').html('<option value="">Select</option>');
        }
    }

    /*function toggleQtyAndRequired() {
        finalAmount();
        var $toggleQty = $(".toggleQty");
        var $is_qty2 = $("#is_qty");
        if ($is_qty2.is(":checked")) {
            $toggleQty.show();
            $("#currency2, #rate2, #opr").attr('required', true);
        } else {
            $toggleQty.hide();
            $("#currency2, #rate2, #opr").attr('required', false);
        }
    }*/

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

        //if ($("#is_qty").prop('checked') == true) {
        var rate2 = parseFloat($("#rate2").val()) || 0;
        let operator = $('#opr').find(":selected").val();

        if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
            final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
            final_amount = final_amount.toFixed(3);
        }
        //}

        $("#final_amount").val(isFinite(final_amount) ? final_amount : '');
        $("#final_amount_span").text(isFinite(final_amount) ? final_amount : '');

        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }
    }
</script>
<?php $info = ['type' => 'danger', 'msg' => 'System Error :('];
if (isset($_POST['purchaseSubmit'])) {
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $_POST['np_acc_details'] = str_replace(array("\r\n", "\r", "\n"), ' ', $_POST['np_acc_details']);
    $data = [
        'p_s' => 'p',
        'type' => $type,
        'active' => 1,
        '_date' => $_POST['_date'],
        'country' => mysqli_real_escape_string($connect, $_POST['country']),
        'branch_id' => mysqli_real_escape_string($connect, $_POST['branch_id']),
        'notify_party_details' => json_encode([
            "np_acc" => $_POST['np_acc'],
            "np_acc_name" => $_POST['np_acc_name'],
            "np_acc_id" => $_POST['np_acc_id'],
            "np_acc_kd_id" => $_POST['np_acc_kd_id'],
            "np_acc_details" => $_POST['np_acc_details'],
            "hidden_id" => $_POST['hidden_id']
        ])
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
        $pageURL .= "?id=" . $hidden_id;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $data['`from`'] = 'purchase-add';
        $done = update('transactions', $data, array('id' => $hidden_id));
        $transaction_accounts_dr_id = mysqli_real_escape_string($connect, $_POST['transaction_accounts_dr_id']);
        $transaction_accounts_cr_id = mysqli_real_escape_string($connect, $_POST['transaction_accounts_cr_id']);
        if ($done && $transaction_accounts_dr_id > 0 && $transaction_accounts_cr_id > 0) {
            $dr_done = updateTransactionAccount($transaction_accounts_dr_id, $dr_acc, $dr_acc_name, $dr_acc_id, $dr_acc_kd_id, $dr_acc_details);
            $cr_done = updateTransactionAccount($transaction_accounts_cr_id, $cr_acc, $cr_acc_name, $cr_acc_id, $cr_acc_kd_id, $cr_acc_details);
            if ($dr_done && $cr_done) {
                $info['type'] = 'success';
                $info['msg'] = strtoupper($type) . ' Purchase updated successfully';
            }
            /*if ($action == 'add_details') {
                $data2['parent_id'] = $p_id_hidden;
                $pd_sr = getPurchaseDetailsSerial($p_id_hidden);
                $data2['d_sr'] = $pd_sr;
                $details_added = insert('transaction_items', $data2);
                if ($details_added) {
                    $ggd_id = $connect->insert_id;
                    $pageURL .= '&pd_id=' . $ggd_id . '&action=update_details';
                    $msg .= ' and New Container saved.';
                }
            }
            if ($action == 'update_details') {
                $pd_id_hidden = mysqli_real_escape_string($connect, $_POST['pd_id_hidden']);
                $pageURL .= '&pd_id=' . $pd_id_hidden . '&action=update_details';
                $details_added = update('transaction_items', $data2, array('id' => $pd_id_hidden));
                if ($details_added) {
                    $msg .= ' with Container details.';
                }
            }*/
        }
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $data['from'] = 'purchase-add';
        $done = insert('transactions', $data);
        if ($done) {
            $tr_id = $connect->insert_id;
            $pageURL .= "?id=" . $tr_id;
            $dr_done = saveTransactionAccount($tr_id, 'purchase', 'dr', $dr_acc, $dr_acc_name, $dr_acc_id, $dr_acc_kd_id, $dr_acc_details);
            $cr_done = saveTransactionAccount($tr_id, 'purchase', 'cr', $cr_acc, $cr_acc_name, $cr_acc_id, $cr_acc_kd_id, $cr_acc_details);
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
    echo json_encode($_POST);
    if ($hidden_id > 0) {
        $pageURL .= '?id=' . $hidden_id;
        $data = array(
            'allotment_name' => mysqli_real_escape_string($connect, $_POST['allotment_name']),
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
            'final_amount' => mysqli_real_escape_string($connect, $_POST['final_amount']),
        );
        if ($hidden_item_id > 0) {
            $pageURL .= '&item_id=' . $hidden_item_id;
            $done = update('transaction_items', $data, ['id' => $hidden_item_id]);
            if ($done) {
                $info['type'] = 'success';
                $info['msg'] = ' Container successfully updated. ';
            }
        } else {
            $data['parent_id'] = $hidden_id;
            $data['sr'] = transactionItemsSerial($hidden_id, 'p');
            $done = insert('transaction_items', $data);
            if ($done) {
                $item_id_ = $connect->insert_id;
                $pageURL .= '&item_id=' . $item_id_;
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
    $pageURL .= "?id=" . $p_id_delete;
    if ($done) {
        $info['msg'] = 'Record deleted.';
        $info['type'] = 'success';
    }
    messageNew($info['type'], $pageURL, $info['msg']);
}

if (isset($_POST['purchaseReports'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id = mysqli_real_escape_string($connect, $_POST['p_id_hidden']);
    $reportType = mysqli_real_escape_string($connect, $_POST['reportType']);
    $report = htmlspecialchars($_POST['reportBox']);
    $report = str_replace(array("\n", "\r", "\r\n"), ' ', $report);
    if (is_numeric($id) && recordExists('transactions', ['id' => $id])) {
        $records = fetch('transactions', ['id' => $id]);
        $record = mysqli_fetch_assoc($records);
        $reports = isset($record['reports']) && !empty($record['reports']) ? json_decode($record['reports'], true) : [];
        if (json_last_error() !== JSON_ERROR_NONE) {
            $msg = 'JSON Decode Error: ' . json_last_error_msg();
            messageNew('danger', $pageURL, $msg);
            return;
        }
        $reports[$reportType] = $report;
        $data = ['reports' => json_encode($reports)];
        error_log("Updating Reports Data: " . print_r($data, true));
        if (update('transactions', $data, ['id' => $id])) {
            $type = 'success';
            $msg = 'Report Successfully Updated.';
        } else {
            $msg = 'DB Update Failed';
        }
    }
    messageNew($type, $pageURL, $msg);
}

?>

<div class="modal fade" id="seaRoadDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="seaRoadDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="seaRoadDetailsLabel">By Sea / Road Details</h1>
                    <a href="<?php echo $pageURL . '?id=' . $id; ?>" class="btn-close" aria-label="Close"></a>
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
<?php if (isset($_POST['seaRoadDetailsSubmit'])) {
    if (isset($_POST['seaRoadDetailsSubmit'])) {
        $_POST['is_loading'] = isset($_POST['is_loading']) ? 1 : 0;
        $_POST['is_receiving'] = isset($_POST['is_receiving']) ? 1 : 0;
        $post = json_encode($_POST);
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        unset($_POST['seaRoadDetailsSubmit']);
        unset($_POST['hidden_id']);
        $pageURL .= "?id=" . $hidden_id;
        $data = array('sea_road' => $post);
        if ($hidden_id > 0) {
            $done = update('transactions', $data, array('id' => $hidden_id));
            if ($done) {
                $info['msg'] = 'Sea / Road details Successfully Updated.';
                $info['type'] = 'success';
            }
        }
        messageNew($info['type'], $pageURL, $info['msg']);
    }
} ?>
<div class="modal fade" id="notifyPartyDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="notifyPartyDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">

    </div>
</div>
<div class="modal fade" id="thirdPartyBank" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="thirdPartyBankLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="thirdPartyBankLabel">Third Party Bank Details</h1>
                    <a href="<?php echo $pageURL . '?id=' . $id; ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body table-form">
                    <div class="row gx-1 gy-4">
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="acc_no" class="form-label">Search ACC No.</label>
                                <input value="<?= isset($bank_details->search_acc_no) ? $bank_details->search_acc_no : '' ?>" id="search_acc_no"
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
                                <label for="acc_no" class="form-label">A/c No.</label>
                                <input value="<?= isset($bank_details->acc_no) ? $bank_details->acc_no : '' ?>" id="acc_no"
                                    name="acc_no" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="acc_name" class="form-label">A/c Name</label>
                                <input value="<?= isset($bank_details->acc_name) ? $bank_details->acc_name : '' ?>" id="acc_name"
                                    name="acc_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="b_company" class="form-label">Company</label>
                                <input value="<?= isset($bank_details->company) ? $bank_details->company : '' ?>" id="b_company"
                                    name="company" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="iban" class="form-label">IBAN#</label>
                                <input value="<?= isset($bank_details->iban) ? $bank_details->iban : '' ?>" id="iban" name="iban"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="branch_code" class="form-label">Branch Code</label>
                                <input value="<?= isset($bank_details->branch_code) ? $bank_details->branch_code : '' ?>" id="branch_code"
                                    name="branch_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="currency">Currency</label>
                                <select id="currency" name="currency" class="form-select" required>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $crr_sel3 = $crr['name'] == isset($bank_details->currency) ? 'selected' : '';
                                        echo '<option ' . $crr_sel3 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="bank_country" class="form-label">Country</label>
                                <input value="<?= isset($bank_details->country) ? $bank_details->country : '' ?>" id="bank_country"
                                    name="country" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="bank_state" class="form-label">State</label>
                                <input value="<?= isset($bank_details->state) ? $bank_details->state : '' ?>" id="bank_state" name="state"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="bank_city" class="form-label">City</label>
                                <input value="<?= isset($bank_details->city) ? $bank_details->acc_no : '' ?>" id="bank_city" name="city"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="bank_address" class="form-label">Address</label>
                                <input value="<?= isset($bank_details->address) ? $bank_details->address : '' ?>" id="bank_address"
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
                                    if (isset($bank_details->indexes4)) {
                                        foreach ($bank_details->indexes4 as $index => $value) { ?>
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
                                                        value="<?php echo $bank_details->vals4[$index] ?>">
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
                    <a href="<?php echo $pageURL . '?id=' . $id; ?>" class="btn-close" aria-label="Close"></a>
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
    $pageURL .= "?id=" . $hidden_id;
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

    // Show a message and redirect if update was successful
    if ($done) {
        messageNew($msg_array['type'], $pageURL . '?id=' . $transaction_hidden_id . "&type=" . $type, $msg_array['msg']);
    }
}



// if (isset($_POST['purchaseSubmit'])) {

//     $data = array('notify_party_details' => $post);
//     $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
//     $done = update('transactions', $data, array('id' => $hidden_id));
//     if ($done) {
//         $msg_array['msg'] = 'Notify Party details Successfully Updated.';
//         $msg_array['type'] = 'success';
//     }
//     messageNew($msg_array['type'], $pageURL . '?id=' . $hidden_id, $msg_array['msg']);
// }
// 
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
                var seaRoadModal = new bootstrap.Modal(document.getElementById('seaRoadDetails'));
                seaRoadModal.show();
            } else if (selectedAction === 'paymentsBtn') {
                var paymentsModal = new bootstrap.Modal(document.getElementById('paymentDetails'));
                paymentsModal.show();
            } else if (selectedAction === 'thirdPartyBankBtn') {
                var thirdPartyModal = new bootstrap.Modal(document.getElementById('thirdPartyBank'));
                thirdPartyModal.show();
            } else if (selectedAction === 'addReportsBtn') {
                openModal('', '');
            }
        });

    });
</script>
<script>
    toggleSeaRoadDivs();
    toggleLoadingAndRequired();
    toggleReceivingAndRequired();
    $('input[name="sea_road"]').change(function() {
        toggleSeaRoadDivs();
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
<script>
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

    function openModal(reportType = '', reportText = '') {
        document.getElementById('customModal').style.display = 'block';
        document.getElementById('reportType').value = reportType !== '' ? reportType : '';

        // Set the textarea with the preset value
        presetValue(reportType);

        // Additional handling to get report details for editing
        if (reportText !== '') {
            let reportForEdit = document.getElementById(reportText).textContent.trim();
            document.getElementById('reportBox').value = reportForEdit; // Set the textarea for editing
        }

        if (reportType === '' && reportText === '') {
            document.getElementById('modalHeading').innerText = 'Add Report';
            document.getElementById('modalButton').innerText = 'Add Report';
            document.getElementById('reportBox').value = ''; // Clear the textarea for new report
        } else {
            document.getElementById('modalHeading').innerText = 'Update Report';
            document.getElementById('modalButton').innerText = 'Update Report';
        }
    }

    function closeModal() {
        document.getElementById('customModal').style.display = 'none';
    }

    window.onclick = event => {
        if (event.target === document.getElementById('customModal')) closeModal();
    };

    // let reportsData;
</script>