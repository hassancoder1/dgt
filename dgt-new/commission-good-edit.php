<?php $page_title = 'COMMISSION GOOD EDIT';
$back_page_url = 'sales-commission-form';
$pageURL = "sales-commission-form";
include("header.php");
$id = $item_id = 0;
if (!isset($_GET['type'])) {
    echo "<script>window.location.href='sales-commission-form';</script>";
}
$prepareLoadingReport = '';
$prepareBankReport = '';
$prepareGoodsReport = '';
$preparePaymentReport = '';
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
    'transaction_accounts_cr_id' => 0
];
$item_fields = ['p_s' => 'p', 'sr' => transactionItemsSerial($id, 'p'), 'goods_id' => 0, 'size' => '', 'brand' => '', 'origin' => '', 'qty_name' => '', 'qty_no' => 0, 'qty_kgs' => 0, 'total_kgs' => 0, 'empty_kgs' => 0, 'total_qty_kgs' => 0, 'net_kgs' => 0, 'divide' => '', 'weight' => 0, 'total' => 0, 'price' => '', 'currency1' => '', 'rate1' => 0, 'amount' => 0, 'currency2' => 'AED', 'rate2' => '', 'opr' => '*', 'final_amount' => 0, 'tax_percent' => '', 'tax_amount' => '', 'total_with_tax' => ''];
$sea_road = ['sea_road' => 'sea', 'l_country_road' => '', 'l_border_road' => '', 'l_date_road' => date('Y-m-d'), 'truck_container' => '', 'r_country_road' => '', 'r_border_road' => '', 'r_date_road' => date('Y-m-d'), 'd_date_road' => date('Y-m-d'), 'is_loading' => 0, 'l_country' => '', 'l_port' => '', 'l_date' => date('Y-m-d'), 'ctr_name' => '', 'is_receiving' => 0, 'r_country' => '', 'r_port' => '', 'r_date' => date('Y-m-d'), 'arrival_date' => date('Y-m-d'), 'report' => '', 'old_company_name' => '', 'transfer_company_name' => '', 'warehouse_date' => date('Y-m-d'), 'truck_no' => '', 'truck_name' => '', 'loading_company_name' => '', 'loading_date' => date('Y-m-d'), 'transfer_name' => ''];
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
    $_fields = [
        'sr_no' => $id,
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
        'transaction_accounts_cr_id' => $cr_record['id']
    ];
    $_fields = transactionSingle($id);
    $_fields['delivery_terms'] = $record['delivery_terms'];
    $sea_road = [];
    if (!empty($record['sea_road'])) {
        $json_sea_road = json_decode($record['sea_road'], true);
        $keys = [
            'sea_road',
            'l_country_road',
            'l_border_road',
            'l_date_road',
            'truck_container',
            'r_country_road',
            'r_border_road',
            'r_date_road',
            'd_date_road',
            'is_loading',
            'l_country',
            'l_port',
            'l_date',
            'ctr_name',
            'is_receiving',
            'r_country',
            'r_port',
            'r_date',
            'arrival_date',
            'report',
            'old_company_name',
            'transfer_company_name',
            'truck_no',
            'truck_name',
            'loading_company_name',
            'transfer_name'
        ];
        foreach ($keys as $key) {
            $sea_road[$key] = $json_sea_road[$key] ?? '';
        }
        $sea_road['warehouse_date'] = $json_sea_road['warehouse_date'] ?? '';
        $sea_road['loading_date'] = $json_sea_road['loading_date'] ?? '';
    } else {
        $sea_road = ['sea_road' => 'sea', 'l_country_road' => '', 'l_border_road' => '', 'l_date_road' => date('Y-m-d'), 'truck_container' => '', 'r_country_road' => '', 'r_border_road' => '', 'r_date_road' => date('Y-m-d'), 'd_date_road' => date('Y-m-d'), 'is_loading' => 0, 'l_country' => '', 'l_port' => '', 'l_date' => date('Y-m-d'), 'ctr_name' => '', 'is_receiving' => 0, 'r_country' => '', 'r_port' => '', 'r_date' => date('Y-m-d'), 'arrival_date' => date('Y-m-d'), 'report' => '', 'old_company_name' => '', 'transfer_company_name' => '', 'warehouse_date' => date('Y-m-d'), 'truck_no' => '', 'truck_name' => '', 'loading_company_name' => '', 'loading_date' => date('Y-m-d'), 'transfer_name' => ''];
    }


    if (isset($_GET['item_id']) && $_GET['item_id'] > 0) {
        $item_id = mysqli_real_escape_string($connect, $_GET['item_id']);
        $records2 = fetch('transaction_items', array('id' => $item_id));
        $record2 = mysqli_fetch_assoc($records2);
        $item_fields = ['p_s' => $record2['p_s'], 'sr' => $record2['sr'], 'allotment_name' => $record2['allotment_name'], 'goods_id' => $record2['goods_id'], 'size' => $record2['size'], 'brand' => $record2['brand'], 'origin' => $record2['origin'], 'qty_name' => $record2['qty_name'], 'qty_no' => $record2['qty_no'], 'qty_kgs' => $record2['qty_kgs'], 'total_kgs' => $record2['total_kgs'], 'empty_kgs' => $record2['empty_kgs'], 'total_qty_kgs' => $record2['total_qty_kgs'], 'net_kgs' => $record2['net_kgs'], 'divide' => $record2['divide'], 'weight' => $record2['weight'], 'total' => $record2['total'], 'price' => $record2['price'], 'currency1' => $record2['currency1'], 'rate1' => $record2['rate1'], 'amount' => $record2['amount'], 'currency2' => $record2['currency2'], 'rate2' => $record2['rate2'], 'opr' => $record2['opr'], 'final_amount' => $record2['final_amount'], 'tax_percent' => $record2['tax_percent'], 'tax_amount' => $record2['tax_amount'], 'total_with_tax' => $record2['total_with_tax']];
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
        <div class="fs-5 text-uppercase">COMMISSION GOOD EDIT</div>
        <div class="d-flex gap-1">
            <?php echo backUrl('/sale-commission-form');
            // echo addNew($pageURL, '', 'btn-sm'); 
            ?>
            <!-- <div class="dropdown me-2">
                <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                    New
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <?php
                    $query = "SELECT * FROM static_types WHERE type_for IN ('ps_types', 's_types')";
                    $result = mysqli_query($connect, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<li><a class="dropdown-item" href="sale-add?type=' . urlencode($row['type_name']) . '">' . htmlspecialchars($row['details']) . '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
    <div class="card">
            <div id="availableQuantitiesTable" class="mt-4"></div>
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
                                        <div><b>Sr# </b> <?php echo $_fields['sr_no']; ?></div>
                                        <div class="row g-0">
                                            <label for="allotment_name" class="col-md-2 col-form-label text-nowrap">Allot</label>
                                            <style>
                                                .rotate {
                                                    animation: rotate 1s linear infinite;
                                                }

                                                @keyframes rotate {
                                                    from {
                                                        transform: rotate(0deg);
                                                    }

                                                    to {
                                                        transform: rotate(360deg);
                                                    }
                                                }

                                                .table-hover tbody tr:hover {
                                                    background-color: #f1f1f1;
                                                }

                                                .table-striped tbody tr:nth-of-type(odd) {
                                                    background-color: rgba(0, 0, 0, 0.05);
                                                }
                                            </style>
                                            <div class="col-md-10 d-flex gap-1 align-items-center">
                                                <input value="<?= isset($item_fields['allotment_name']) ? $item_fields['allotment_name'] : ''; ?>" id="allotment_name"
                                                    name="allotment_name" id="allotment_name" class="form-control" required>
                                                <button type="button" id="allotSearch" onclick="fetchGoods()" class="btn ml-1 btn-sm btn-success"><i class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="input-group">
                                            <label for="goods_id">GOODS</label>
                                            <select id="goods_id" name="goods_id" class="form-select" onchange="finalAmount()" required>
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
                                            <select class="form-select" name="size" id="size" onchange="finalAmount()" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details");
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
                                            <select class="form-select" name="origin" id="origin" onchange="finalAmount()" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details");
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
                                            <!-- <input type="text" name="brand" id="brand" value="<?= $item_fields['brand']; ?>" class="form-control" required> -->
                                            <select class="form-select" name="brand" id="brand" onchange="finalAmount()" required>
                                                <option hidden value="">Select</option>
                                                <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details");
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
                                                <input value="<?php echo $item_fields['qty_name']; ?>" onchange="finalAmount()" id="qty_name"
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
                                                    class="form-control currency" onchange="finalAmount()" required>
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
                                    <?php if ($_GET['type'] !== 'local'): ?>
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
                                    <?php else: ?>
                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="tax_percent">Tax %</label>
                                                <div class="col-sm">
                                                    <input type="text" value="<?php echo $item_fields['tax_percent']; ?>" id="tax_percent"
                                                        name="tax_percent"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="tax_amount">Tax.Amt</label>
                                                <div class="col-sm">
                                                    <input type="text" value="<?php echo $item_fields['tax_amount']; ?>" id="tax_amount"
                                                        name="tax_amount"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="row g-0">
                                                <!-- <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="total_with_tax">Amt+Tax</label> -->
                                                <div class="col-sm">
                                                    <input type="hidden" value="<?php echo $item_fields['total_with_tax']; ?>" id="total_with_tax"
                                                        name="total_with_tax">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
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
                                        if ($_GET['type'] !== 'local') {
                                            echo '<tr><th class="fw-normal text-danger">FINAL  </th><th><span id="final_amount_span"></span></th></tr>';
                                        } else {
                                            echo '<tr><th class="fw-normal text-danger">Amt+Tax  </th><th><span id="total_with_tax_span">0</span></th></tr>';
                                        };
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
                                    echo $item_id > 0 ? backUrl('sale-add?id=' . $id . '&type=' . $_GET['type']) : '';
                                    ?>
                                </div>
                                <span class="fw-bold text-danger d-none" id="qtyAlert">Enough Stock Not Found!</span>
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
    let availableQuantities;
    $(document).ready(function() {
        finalAmount();

        $('#qty_no, #qty_kgs, #empty_kgs, #weight, #rate1, #rate2, #opr').on('keyup', function() {
            finalAmount();
        });

        $('#allotSearch').on('click', function() {
            fetchGoods();
        });

        $('#goods_id').on('change', function() {
            updateSizeOptions($(this).val());
        });

        $('#size').on('change', function() {
            updateBrandOriginTable($(this).val());
        });
    });

    function fetchGoods() {
        let allot = $('#allotment_name').val().trim();
        if (!allot) {
            alert('Please enter an allotment name.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'ajax/fetch_goods_by_allot.php',
            data: {
                allot
            },
            success: function(res) {
                res = JSON.parse(res);
                $('#goods_id').html(res.html);
                availableQuantities = res.quantities;
                $('#size, #brand, #origin').html('<option value="">Select</option>');
                $('#availableQuantitiesTable').empty();
            },
            error: function(err) {
                console.error('AJAX error:', err);
                alert('Failed to fetch allotment details. Please try again later.');
            }
        });
    }

    function updateSizeOptions(goodsId) {
        if (!goodsId) {
            $('#size').html('<option value="">Select</option>');
            return;
        }

        let selectedGood = availableQuantities.find(item => item.goods_id == goodsId);
        if (selectedGood) {
            let sizeOptions = '<option value="" selected>Select Size</option>';
            selectedGood.sizes.forEach(size => {
                sizeOptions += `<option value="${size.size}">${size.size}</option>`;
            });
            $('#size').html(sizeOptions);
        } else {
            $('#size').html('<option value="">Select</option>');
        }
    }

    function updateBrandOriginTable(size) {
        let selectedGood = $('#goods_id').val();
        if (!selectedGood || !size) {
            $('#availableQuantitiesTable').html('<p class="text-warning">Please select both a good and size to view available quantities.</p>');
            return;
        }

        let selectedGoodData = availableQuantities.find(item => item.goods_id == selectedGood);
        if (!selectedGoodData) return;

        let sizeData = selectedGoodData.sizes.find(item => item.size == size);
        if (!sizeData) return;

        let originOptions = '<option value="" selected>Select Origin</option>';
        let brandOptions = '<option value="" selected>Select Brand</option>';

        let uniqueOrigins = new Set();
        let uniqueBrands = new Set();

        sizeData.brands.forEach(item => {
            uniqueOrigins.add(item.origin);
            uniqueBrands.add(item.brand);
        });

        uniqueOrigins.forEach(origin => {
            originOptions += `<option value="${origin}">${origin}</option>`;
        });

        uniqueBrands.forEach(brand => {
            brandOptions += `<option value="${brand}">${brand}</option>`;
        });

        $('#origin').html(originOptions);
        $('#brand').html(brandOptions);

        let tableHTML = `
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
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

        sizeData.brands.forEach(item => {
            item.quantities.forEach(qty => {
                tableHTML += `
                <tr>
                    <td class="tbrand">${item.brand}</td>
                    <td class="origin">${item.origin}</td>
                    <td class="text-info tqty_name">${qty.qty_name}</td>
                    <td class="fw-bold text-success">${qty.purchased_quantity}</td>
                    <td class="fw-bold text-danger">${qty.sold_quantity}</td>
                    <td class="fw-bold text-dark tqty_remaining">${qty.remaining_quantity}</td>
                </tr>
            `;
            });
        });

        tableHTML += `</tbody></table>`;
        $('#availableQuantitiesTable').html(tableHTML);
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

        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }

        checkRemainingQuantity(qty_no);
    }

    function checkRemainingQuantity(qty_no) {
        if (qty_no < 1) {
            disableButton('recordSubmit');
        }
        var qty_name = $("#qty_name").val().toLowerCase();
        var origin = $("#origin").val().toLowerCase();
        var brand = $("#brand").val().toLowerCase();
        var exceedLimit = false;

        $("#availableQuantitiesTable tr").each(function() {
            var Tbrand = $(this).find(".tbrand").text().toLowerCase();
            var Torigin = $(this).find(".origin").text().toLowerCase();
            var Tqty_name = $(this).find(".tqty_name").text().toLowerCase();
            var Tqty_remaining = parseFloat($(this).find(".tqty_remaining").text()) || 0;

            if (Tbrand === brand && Torigin === origin && Tqty_name === qty_name) {
                if (qty_no > Tqty_remaining) {
                    exceedLimit = true;
                    return false; // Exit loop
                }
            }
        });

        if (exceedLimit) {
            disableButton('recordSubmit');
            $('#qtyAlert').removeClass('d-none');
            $('#qty_no').addClass('is-invalid');

        } else {
            enableButton('recordSubmit');
            $('#qtyAlert').addClass('d-none');
            $('#qty_no').removeClass('is-invalid');
        }
    }
</script>
<?php $info = ['type' => 'danger', 'msg' => 'System Error :('];
if (isset($_POST['recordSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $hidden_item_id = mysqli_real_escape_string($connect, $_POST['hidden_item_id']);
    if ($hidden_id > 0) {
        $pageURL .= '?id=' . $hidden_id . '&type=' . $record['type'];
        $data = array(
            'allotment_name' => mysqli_real_escape_string($connect, $_POST['allotment_name']),
            'p_s' => mysqli_real_escape_string($connect, 's'),
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
            'tax_percent' => mysqli_real_escape_string($connect, $_POST['tax_percent'] ?? ''),
            'tax_amount' => mysqli_real_escape_string($connect, $_POST['tax_amount'] ?? 0),
            'total_with_tax' => mysqli_real_escape_string($connect, $_POST['total_with_tax'] ?? 0),
            'final_amount' => mysqli_real_escape_string($connect, (isset($_POST['total_with_tax']) && !empty($_POST['total_with_tax']) ? $_POST['total_with_tax'] : $_POST['final_amount'])),
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
    toggleSeaRoadDivs();
    toggleLoadingWareHouseDivs();
    toggleLoadingAndRequired();
    toggleReceivingAndRequired();
    $('input[name="sea_road"]').change(function() {
        toggleSeaRoadDivs();
    });
    $('input[name="local_warehouse"]').change(function() {
        toggleLoadingWareHouseDivs();
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

    function toggleLoadingWareHouseDivs() {
        var isLoadingWareHouseSelected = $('input[name="local_warehouse"]:checked').val().trim();
        if (isLoadingWareHouseSelected === "sea") {
            $('.toggleBySea').show();
            $('.toggleByRoad').hide();
        } else if (isLoadingWareHouseSelected === "road") {
            $('.toggleBySea').hide();
            $('.toggleByRoad').show();
        } else {
            console.log("Unexpected value: ", isLoadingWareHouseSelected);
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