<?php $page_title = 'Purchase Entry';
$back_page_url = 'purchases';
$pageURL = "purchase-add";
include("header.php");
$id = $item_id = 0;
global $userId, $userName, $branchId;
$_fields = ['sr_no' => getAutoIncrement('transactions'), 'username' => $userName, 'branch_id' => $branchId, 'p_s' => '', 'type' => '', 'active' => 1, 'locked' => 0, '_date' => date('Y-m-d'), 'country' => '', 'action' => 'insert',
    'dr_acc' => '', 'dr_acc_name' => '', 'dr_acc_details' => '', 'dr_acc_id' => 0, 'dr_acc_kd_id' => 0,
    'cr_acc' => '', 'cr_acc_name' => '', 'cr_acc_details' => '', 'cr_acc_id' => 0, 'cr_acc_kd_id' => 0,
    'transaction_accounts_dr_id' => 0, 'transaction_accounts_cr_id' => 0];
$item_fields = ['p_s' => 'p', 'sr' => transactionItemsSerial($id, 'p'), 'goods_id' => 0, 'size' => '', 'brand' => '', 'origin' => '', 'qty_name' => '', 'qty_no' => 0, 'qty_kgs' => 0, 'total_kgs' => 0, 'empty_kgs' => 0, 'total_qty_kgs' => 0, 'net_kgs' => 0, 'divide' => '', 'weight' => 0, 'total' => 0, 'price' => '', 'currency1' => '', 'rate1' => 0, 'amount' => 0, 'currency2' => 'AED', 'rate2' => '', 'opr' => '*', 'final_amount' => 0];
$sea_road = ['sea_road' => 'sea', 'l_country_road' => '', 'l_border_road' => '', 'l_date_road' => date('Y-m-d'), 'truck_container' => '', 'r_country_road' => '', 'r_border_road' => '', 'r_date_road' => date('Y-m-d'), 'd_date_road' => date('Y-m-d'), 'is_loading' => 0, 'l_country' => '', 'l_port' => '', 'l_date' => date('Y-m-d'), 'ctr_name' => '', 'is_receiving' => 0, 'r_country' => '', 'r_port' => '', 'r_date' => date('Y-m-d'), 'arrival_date' => date('Y-m-d'), 'report' => ''];
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
        $item_fields = ['p_s' => $record2['p_s'], 'sr' => $record2['sr'], 'goods_id' => $record2['goods_id'], 'size' => $record2['size'], 'brand' => $record2['brand'], 'origin' => $record2['origin'], 'qty_name' => $record2['qty_name'], 'qty_no' => $record2['qty_no'], 'qty_kgs' => $record2['qty_kgs'], 'total_kgs' => $record2['total_kgs'], 'empty_kgs' => $record2['empty_kgs'], 'total_qty_kgs' => $record2['total_qty_kgs'], 'net_kgs' => $record2['net_kgs'], 'divide' => $record2['divide'], 'weight' => $record2['weight'], 'total' => $record2['total'], 'price' => $record2['price'], 'currency1' => $record2['currency1'], 'rate1' => $record2['rate1'], 'amount' => $record2['amount'], 'currency2' => $record2['currency2'], 'rate2' => $record2['rate2'], 'opr' => $record2['opr'], 'final_amount' => $record2['final_amount']];
    }
} ?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex gap-1">
            <?php echo backUrl($back_page_url);
            echo addNew($pageURL, '', 'btn-sm'); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-2">
            <div class="position-absolute end-0 top-0">
                <a class="btn btn-link text-dark" data-bs-toggle="collapse" href="#collapseFirst" role="button"
                   aria-expanded="false" aria-controls="collapseFirst">
                    <i class="fa fa-angle-down"></i>
                </a>
            </div>
            <div class="card-body">
                <form method="post" class="table-form collapse show" id="collapseFirst">
                    <div class="row">
                        <div class="col-md-8 border-end">
                            <div class="row gx-3 gy-4">
                                <div class="col-md-6">
                                    <div class="input-group mb-2">
                                        <label for="dr_acc" class="text-success fw-bold">Dr. A/C.</label>
                                        <input type="text" id="dr_acc" name="dr_acc" class="form-control" required
                                               value="<?php echo $_fields['dr_acc']; ?>">
                                        <input value="<?php echo $_fields['dr_acc_name']; ?>" id="dr_acc_name"
                                               name="dr_acc_name" class="form-control w-50" readonly tabindex="-1">
                                    </div>
                                    <input value="<?php echo $_fields['dr_acc_id']; ?>" type="hidden"
                                           name="dr_acc_id" id="dr_acc_id">
                                    <div class="input-group mb-0">
                                        <label for="dr_acc_kd_id">COMPANY</label>
                                        <select class="form-select" name="dr_acc_kd_id" id="dr_acc_kd_id">
                                            <option hidden value="">Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => $_fields['dr_acc_id'], 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd1 = $row['id'] == $_fields['dr_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd1 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                            <textarea class="form-control form-control-sm" name="dr_acc_details"
                                                      id="dr_acc_details" rows="7"
                                                      placeholder="Company Details"><?php echo $_fields['dr_acc_details']; ?></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-2">
                                        <label for="cr_acc" class="text-danger fw-bold">Cr. A/C.</label>
                                        <input type="text" id="cr_acc" name="cr_acc" class="form-control"
                                               required value="<?php echo $_fields['cr_acc']; ?>">
                                        <input value="<?php echo $_fields['cr_acc_name']; ?>" id="cr_acc_name"
                                               name="cr_acc_name" class="form-control w-50" readonly
                                               tabindex="-1">
                                    </div>
                                    <input value="<?php echo $_fields['cr_acc_id']; ?>" type="hidden"
                                           name="cr_acc_id" id="cr_acc_id">
                                    <div class="input-group mb-0">
                                        <select class="form-select" name="cr_acc_kd_id" id="cr_acc_kd_id">
                                            <option hidden value="">Company</option>
                                            <?php $run_query = fetch('khaata_details', array('khaata_id' => $_fields['cr_acc_id'], 'type' => 'company'));
                                            while ($row = mysqli_fetch_array($run_query)) {
                                                $row_data = json_decode($row['json_data']);
                                                $sel_kd2 = $row['id'] == $_fields['cr_acc_kd_id'] ? 'selected' : '';
                                                echo '<option ' . $sel_kd2 . ' value=' . $row['id'] . '>' . $row_data->company_name . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                            <textarea class="form-control form-control-sm" name="cr_acc_details"
                                                      id="cr_acc_details" rows="7"
                                                      placeholder="Company Details"><?php echo $_fields['cr_acc_details']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div><b>Sr# </b> <?php echo $_fields['sr_no']; ?></div>
                                <div><b>User </b> <?php echo strtoupper($_fields['username']); ?></div>
                            </div>
                            <div class="input-group mt-2">
                                <label for="_date">Date</label>
                                <input type="date" value="<?php echo $_fields['_date']; ?>" id="_date" name="_date"
                                       class="form-control" required>
                                <label for="type">Type</label>
                                <select id="type" name="type" class="form-select">
                                    <?php $static_types = fetch('static_types', ['type_for' => 'ps_types']);
                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                        $sel_type = $static_type['type_name'] == $_fields['type'] ? 'selected' : '';
                                        echo '<option ' . $sel_type . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <div class="input-group my-2">
                                <label for="country">Country</label>
                                <input value="<?php echo $_fields['country']; ?>" id="country" name="country"
                                       class="form-control" required>
                                <label for="branch_id">Branch</label>
                                <select id="branch_id" name="branch_id" class="form-select">
                                    <?php //$branch_sql = "SELECT * FROM `branches` ";if (!SuperAdmin()) {$branch_sql .= " WHERE id= '$branchId' ";}
                                    $branches = SuperAdmin() ? fetch('branches') : fetch('branches', array('id' => $_fields['branch_id']));
                                    while ($b = mysqli_fetch_assoc($branches)) {
                                        $b_select = $b['id'] == $_fields['branch_id'] ? 'selected' : '';
                                        echo '<option ' . $b_select . ' value="' . $b['id'] . '">' . $b['b_code'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                            <input type="hidden" value="<?php echo $id; ?>" name="hidden_id">
                            <div class="d-flex align-items-center justify-content-between mt-md-4">
                                <button name="purchaseSubmit" id="purchaseSubmit" type="submit" class="btn btn-dark">
                                    Submit
                                </button>
                                <a class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#seaRoadDetails">+
                                    Sea / Road</a>
                                <a class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#paymentDetails">+
                                    Payments</a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo $id; ?>" name="hidden_id">
                    <input type="hidden" value="<?php echo $_fields['transaction_accounts_dr_id']; ?>"
                           name="transaction_accounts_dr_id">
                    <input type="hidden" value="<?php echo $_fields['transaction_accounts_cr_id']; ?>"
                           name="transaction_accounts_cr_id">
                </form>
            </div>
        </div>
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
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7 border-end">
                            <?php if (!empty($_fields['sea_road'])) { ?>
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
                            <?php } ?>
                        </div>
                        <div class="col-md-5">
                            <?php if (!empty($_fields['payment_details'])) { ?>
                                <div class="row">
                                    <div class="col-md-12 text-uppercase fs-6"><b>Payment Details</b></div>
                                    <div class="col">
                                        <?php if ($_fields['payment_details']->full_advance == 'full') {
                                            echo 'FULL PAYMENT';
                                            echo '<br><b>Payment Date</b> ' . $_fields['payment_details']->full_date;
                                            echo '<br><b>Report</b> ' . $_fields['payment_details']->full_report;
                                        } else {
                                            echo 'ADVANCED PAYMENT';
                                            echo '<br><b>ADVANCE AMOUNT</b> ' . $_fields['payment_details']->partial_amount1 . ' (' . $_fields['payment_details']->pct_value . '%)';
                                            echo '<br><b>REMAINING AMOUNT</b> ' . $_fields['payment_details']->partial_amount2 . ' (' . (100 - $_fields['payment_details']->pct_value) . '%)';
                                            echo '<br><br><br>';
                                        }
                                        var_dump($_fields['payment_details']);
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<script type="text/javascript">
    $(document).on('keyup', "#dr_acc", function (e) {
        fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "purchaseSubmit");
    });
    //fetchKhaata("#dr_acc", "#dr_acc_id", "#dr_acc_kd_id", "purchaseSubmit");

    $(document).on('keyup', "#cr_acc", function (e) {
        fetchKhaata("#cr_acc", "#cr_acc_id", "#cr_acc_kd_id", "purchaseSubmit");
    });

    //fetchKhaata("#cr_acc", "#cr_acc_id", "#cr_acc_kd_id", "purchaseSubmit");

    function fetchKhaata(inputField, khaataId, kd_dropdown, recordSubmitId) {

        let khaata_no = $(inputField).val();
        let khaata_id_this = 0;
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton(recordSubmitId);
                    khaata_id_this = response.messages['khaata_id'];
                    $(khaataId).val(khaata_id_this);
                    $(recordSubmitId).prop('disabled', false);
                    $(inputField + '_name').val(response.messages['khaata_name']);

                    $(inputField).addClass('is-valid');
                    $(inputField).removeClass('is-invalid');
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
                }
                if (response.success === false) {
                    disableButton(recordSubmitId);
                    $(inputField).addClass('is-invalid');
                    $(inputField).removeClass('is-valid');
                    $(khaataId).val(0);
                    $(kd_dropdown).html('<option value="0">Invalid A/c.</option>');
                }
            },
            error: function (e) {
                $(inputField).html('<option value="0">Invalid A/c.</option>');
            }
        });
    }

    function khaataCompanies(khaata_id, dropdown_id) {
        if (khaata_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/companies_dropdown_by_khaata_id.php',
                data: {khaata_id: khaata_id},
                success: function (html) {
                    $('#' + dropdown_id).html(html);
                },
                error: function (xhr, status, error) {
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
                data: {khaata_details_id: khaata_details_id},
                success: function (response) {
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
                error: function (xhr, status, error) {
                }
            });
        } else {
            $('#' + dropdown_id).val('');
        }
    }

    $(document).ready(function () {
        $('#dr_acc_kd_id').on('change', function () {
            khaataDetailsSingle($(this).val(), 'dr_acc_details');
            //var kd_id = $(this).val();
        });
        $('#cr_acc_kd_id').on('change', function () {
            khaataDetailsSingle($(this).val(), 'cr_acc_details');
            //var kd_id = $(this).val();
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        finalAmount();
        $('#qty_no,#qty_kgs,#empty_kgs,#weight,#rate1,#rate2,#opr').on('keyup', function () {
            finalAmount();
        });

        /*toggleQtyAndRequired();
        $("#is_qty").change(toggleQtyAndRequired);
        $('#opr').on('change', function () {
            finalAmount();
        });*/

        /*var goods_id = $('#goods_id').find(":selected").val();
        goodDetails(goods_id);*/
        $("#goods_id").change(function () {
            var goods_id = $(this).val();
            goodDetails(goods_id);
        });
    });

    function goodDetails(goods_id) {
        if (goods_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_sizes.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    $('#size').html(html);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_brands.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    $('#brand').html(html);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_good_origins.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
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
    $data = ['p_s' => 'p', 'type' => $type, 'active' => 1, '_date' => $_POST['_date'],
        'country' => mysqli_real_escape_string($connect, $_POST['country']),
        'branch_id' => mysqli_real_escape_string($connect, $_POST['branch_id'])];

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
    if ($hidden_id > 0) {
        $pageURL .= '?id=' . $hidden_id;
        $data = array(
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
} ?>

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
            $info['msg'] = ' Sea / Road details Successfully Updated.';
            $info['type'] = 'success';
        }
    }
    messageNew($info['type'], $pageURL, $info['msg']);
} ?>
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
                    <?php $t = $_fields['items_sum']['sum_final_amount'];
                    $bees = Percentage(20, $t);
                    $assi = Percentage(80, $t); ?>
                    <div class="row">
                        <div class="col-md-9 border-end">
                            <div class="row gx-1 mb-4 align-items-center">
                                <div class="col-md-auto">
                                    <div class="bg-light border pt-1 ps-2">
                                        <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                            <input class="form-check-input" type="radio" name="full_advance" id="cash"
                                                   value="full">
                                            <label class="form-check-label" for="cash">Full Payment</label>
                                        </div>
                                        <div class="form-check form-check-inline form-switch mb-0 h-auto">
                                            <input checked class="form-check-input" type="radio" name="full_advance"
                                                   id="credit" value="advance">
                                            <label class="form-check-label" for="credit">Advance</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label for="pct_value">% Value</label>
                                        <input type="number" min="1" step="any" id="pct_value" name="pct_value"
                                               class="form-control" value="20" max="90">
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
} ?>
<script>
    var staticOptionsCache = {};

    function loadStaticTypes(url, callback) {
        if (staticOptionsCache[url]) {
            callback(staticOptionsCache[url]);
        } else {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (response) {
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

        loadStaticTypes(url, function (staticOptions) {
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

    $(document).ready(function () {
        $('.addContactRow').on('click', function () {
            addContactRow(this);
        });
    });
</script>
<script>
    toggleSeaRoadDivs();
    toggleLoadingAndRequired();
    toggleReceivingAndRequired();
    $('input[name="sea_road"]').change(function () {
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
    $(document).ready(function () {
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
                    '<input type="date" class="form-control" id="full_date" name="full_date" required>' +
                    '</div></div>' +
                    '<div class="col-md"><div class="input-group"><label for="full_report">Report</label>' +
                    '<input type="text" class="form-control" id="full_report" name="full_report" required>' +
                    '</div></div></div>');


                /*$('#partial_date1').closest('.input-group').hide();
                $('#partial_date1').attr('required', false);
                $('#partial_report1').closest('.input-group').hide();
                $('#partial_report1').attr('required', false);
                $('#partial_amount2').closest('.input-group').hide();
                $('#partial_amount2').attr('required', false);*/

                //$('#pct_value').val(0);
                $('#pct_value').closest('.input-group').hide();
                $('#pct_value').attr('required', false);
                //$('#partial_amount1').val(0);
                //$('#partial_amount2').val(0);
            } else if (selectedValue === 'advance') {
                //$('#pct_value').val(20);
                partialValues();

                var pct_value = $('#pct_value').val();

                $("#date-report-inputs").html('<div class="row gx-1 mt-3">' +
                    '<div class="col-md-auto"><div class="input-group"><label for="partial_date1">' + pct_value + ' % Payment Date</label>' +
                    '<input type="date" class="form-control" id="partial_date1" name="partial_date1" required></div></div>' +
                    '<div class="col-md"><div class="input-group"><label for="partial_report1">Report</label>' +
                    '<input type="text" class="form-control" id="partial_report1" name="partial_report1" required>' +
                    '</div></div></div>' +
                    '<div class="row gx-1 mt-3">' +
                    '<div class="col-md-auto"><div class="input-group">' +
                    '<label for="partial_date2">' + (100 - pct_value) + '% Payment Date</label>' +
                    '<input type="date" class="form-control" id="partial_date2" name="partial_date2" required></div></div>' +
                    '<div class="col-md"><div class="input-group"><label for="partial_report2">Report</label>' +
                    '<input type="text" class="form-control" id="partial_report2" name="partial_report2" required>' +
                    '</div></div></div>');

                $('#pct_value').closest('.input-group').show();
                $('#pct_value').attr('required', true);
            }
        }

        toggleInputs();
        $('input[name="full_advance"]').on('change', function () {
            toggleInputs();
        });
        $('#pct_value').on('change, keyup', function () {
            toggleInputs();
        });
    });

</script>
