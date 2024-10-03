<?php $page_title = 'LOCAL SALE Entry';
$back_page_url = 'sales-local';
include("header.php");
$url = "sale-local-add";
global $branchId;
$sr_no = getAutoIncrement('sales');
$action_hidden = 'insert';
$currency1 = 'AED';
$s_date = date('Y-m-d');
$p_acc = $s_name = $receiver = $size = $brand = $is_qty = $type = $city = $qty_name = $divide = $price = $currency1 = $currency2 = $report = '';
$branch__id = $sd_id = $sale_id = $goods_id = $wh_k_id = $wh_kd_id = $qty_no = $qty_kgs = $total_kgs = $empty_kgs = $total_qty_kgs = $net_kgs = $weight = $total = $rate1 = $amount = $rate2 = $opr = $final_amount = 0;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $action_hidden = 'update';
    $sale_id = $sr_no = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('sales', array('id' => $sale_id));
    $record = mysqli_fetch_assoc($records);
    $branch__id = $record['branch_id'];
    $type = $record['type'];
    $s_date = $record['s_date'];
    $city = $record['city'];
    $s_name = $record['s_name'];
    $p_acc = $record['p_khaata_no'];
    $receiver = $record['receiver'];
    $report = $record['report'];
    if (isset($_GET['action'])) {
        $action_hidden = $action = mysqli_real_escape_string($connect, $_GET['action']);
        $add_details = $action == 'add_details';
        if (isset($_GET['sd_id']) && $_GET['sd_id'] > 0) {
            $update_details = $action == 'update_details';
            $sd_id = mysqli_real_escape_string($connect, $_GET['sd_id']);
            $records2 = fetch('sale_details', array('id' => $sd_id));
            $record2 = mysqli_fetch_assoc($records2);
            $goods_id = $record2['goods_id'];
            $size = $record2['size'];
            $brand = $record2['brand'];
            $wh_k_id = $record2['wh_k_id'];
            $wh_kd_id = $record2['wh_kd_id'];
            $qty_name = $record2['qty_name'];
            $divide = $record2['divide'];
            $price = $record2['price'];
            $currency1 = $record2['currency1'];
            $currency2 = $record2['currency2'];
            $qty_no = $record2['qty_no'];
            $qty_kgs = $record2['qty_kgs'];
            $total_kgs = $record2['total_kgs'];
            $empty_kgs = $record2['empty_kgs'];
            $total_qty_kgs = $record2['total_qty_kgs'];
            $net_kgs = $record2['net_kgs'];
            $weight = $record2['weight'];
            $total = $record2['total'];
            $rate1 = $record2['rate1'];
            $amount = $record2['amount'];
            $is_qty = $record2['is_qty'];
            $rate2 = $record2['rate2'];
            $opr = $record2['opr'];
            $final_amount = $record2['final_amount'];
            $is_qty = $record2['is_qty'] == 1 ? 'checked' : '';
        }
    }
}
$topArray = array(array('heading' => 'SALE DATE ', 'value' => '<input name="s_date" type="date" value="'.$s_date.'">', 'id' => ''),
    array('heading' => 'SALE BILL# ', 'value' => $sr_no, 'id' => '')); ?>
<div class="row">
    <div class="col-md-12">
        <form method="post" enctype="multipart/form-data" class=" table-form">
            <div class="d-flex justify-content-between flex-wrap gap-1 text-uppercase small">
                <div>
                    <?php foreach ($topArray as $item) {
                        echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '" class="text-muted">' . $item['value'] . '</span><br>';
                    } ?>
                    <div class="d-flex align-items-center">
                        <label for="branch_id" class="mb-0 bold">Branch</label>
                        <select id="branch_id" name="branch_id" class="form-select bg-transparent border-0"
                                style="min-width: 130px;">
                            <?php $array_branch_condition = SuperAdmin() ? array() : array('id' => $branchId);
                            $branches = fetch('branches', $array_branch_condition);
                            while ($b = mysqli_fetch_assoc($branches)) {
                                $b_select = $b['id'] == $branch__id ? 'selected' : '';
                                echo '<option ' . $b_select . ' value="' . $b['id'] . '">' . $b['b_code'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="h6">
                    <b>T. PURCHASE QTY </b><span class="text-muted" id="qty_no_span"></span><br>
                    <b>T. SALE QTY </b><span class="text-muted" id="qty_no_sale_span"></span><br>
                    <b>QTY BALANCE </b><span class="text-danger" id="qty_no_bal_span"></span>
                </div>
                <div class="h6">
                    <b>T. PURCHASE KGs </b><span class="text-muted" id="total_kgs_span"></span><br>
                    <b>T. SALE KGs </b><span class="text-muted" id="total_kgs_sale_span"></span><br>
                    <b>KGs BALANCE </b><span class="text-danger" id="total_kgs_bal_span"></span>
                    <?php echo '<input type="hidden" value="' . $qty_no . '" id="qty_no_sale">';
                    echo '<input type="hidden" value="' . $total_kgs . '" id="total_kgs_sale">'; ?>
                </div>
                <div class="h6">
                    <b>Warehouse </b> <span class="text-muted" id="warehouse_name"></span><br>
                    <b>Stock Qty </b> <span class="text-muted" id="wh_stock_qty_no"></span>
                    <b>Stock KGs </b> <span class="text-muted" id="wh_stock_total_kgs"></span><br>

                    <b>Sale Qty </b> <span class="text-muted" id="wh_sale_qty_no"></span>
                    <b>Sale KGs </b> <span class="text-muted" id="wh_sale_total_kgs"></span><br>

                    <b>QTY BALANCE </b> <span class="text-danger" id="wh_bal_qty_no"></span>
                    <b>KGS BALANCE </b> <span class="text-danger" id="wh_bal_total_kgs"></span><br>
                </div>
                <div class="btn-group- dropend">
                    <b>Purchase A/C </b><span class="text-muted" id="ppp"><?php echo $p_acc; ?></span><br>
                    <input type="hidden" id="purchase_account" name="p_acc">
                    <button type="button" class="btn w-100 btn-sm btn-dark py-0" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="mdi mdi-chevron-left"></i> New <i class="mdi mdi-plus"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo $url; ?>">New Sale</a>
                        <?php echo $sale_id > 0 ? '<a class="dropdown-item" href="sale-add?id=' . $sale_id . '&action=add_details">New Container</a>' : ''; ?>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card mb-1">
                <div class="card-body">
                    <div class="row gx-1 gy-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="s_name">SALE NAME</label>
                                <input value="<?php echo $s_name; ?>" id="s_name" name="s_name" type="text"
                                       class="form-control" autofocus required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="receiver">RECEIVER NAME</label>
                                <input value="<?php echo $receiver; ?>" id="receiver" name="receiver" type="text"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="type">TYPE</label>
                                <select id="type" name="type" class="form-select" required>
                                    <!--<option value="" hidden="">Select</option>-->
                                    <?php $aa = array( 'local');
                                    foreach ($aa as $item) {
                                        $type_sel = $item == $type ? 'selected' : '';
                                        echo '<option ' . $type_sel . ' value="' . $item . '">' . ucfirst($item) . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="city">CITY</label>
                                <input value="<?php echo $city; ?>" id="city" name="city" type="text"
                                       class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php if ($action_hidden != 'update') { ?>
                        <div class="row gx-1 gy-3">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="goods_id">GOODS</label>
                                    <select id="goods_id" name="goods_id" class="form-select" required>
                                        <option hidden value="">Select</option>
                                        <?php $goods = fetch('goods');
                                        while ($good = mysqli_fetch_assoc($goods)) {
                                            $g_selected = $good['id'] == $goods_id ? 'selected' : '';
                                            echo '<option ' . $g_selected . ' value="' . $good['id'] . '">' . $good['name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="size">SIZE</label>
                                    <select class="form-select" name="size" id="size" required>
                                        <option hidden value="">Select</option>
                                        <?php $goods_sizes = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = '$goods_id'");
                                        while ($size_s = mysqli_fetch_assoc($goods_sizes)) {
                                            //$size_selected = $size_s['size'] == $size ? 'selected' : '';
                                            echo '<option  value="' . $size_s['size'] . '">' . $size_s['size'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="brand">Brand</label>
                                    <select class="form-select" name="brand" id="brand" required>
                                        <option hidden value="">Select</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="party_khaata_id">WH.A/C</label>
                                    <select class="form-select" name="wh_k_id" id="party_khaata_id" required>
                                        <option hidden value="">Select</option>
                                        <?php $result = fetch('khaata_details', array('static_type' => 'Warehouse'));
                                        while ($kh = mysqli_fetch_assoc($result)) {
                                            if ($kh['comp_name'] != '') {
                                                $wh_khaata = khaataSingle($kh['khaata_id']);
                                                $wh_selected = $wh_khaata['id'] == $wh_k_id ? 'selected' : '';
                                                echo '<option ' . $wh_selected . ' value="' . $wh_khaata['id'] . '">' . $wh_khaata['khaata_no'] . '</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="wh_kd_id">WH.</label>
                                    <select id="wh_kd_id" name="wh_kd_id" class="form-select">
                                        <option hidden value="">Select</option>
                                        <?php $result2 = fetch('khaata_details', array('static_type' => 'Warehouse', 'khaata_id' => $wh_kd_id));
                                        while ($kd = mysqli_fetch_assoc($result2)) {
                                            if ($kd['comp_name'] != '') {
                                                $wh_selected2 = $kd['id'] == $wh_kd_id ? 'selected' : '';
                                                echo '<option ' . $wh_selected2 . ' value="' . $kd['id'] . '">' . $kd['khaata_no'] . '</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="qty_name">Qty Name</label>
                                    <input value="<?php echo $qty_name; ?>" id="qty_name" name="qty_name"
                                           class="form-control" required>
                                    <label for="qty_no">Qty#</label>
                                    <input value="<?php echo $qty_no; ?>" id="qty_no" name="qty_no"
                                           class="form-control currency" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="qty_kgs">Qty KGs</label>
                                    <input value="<?php echo $qty_kgs; ?>" id="qty_kgs" name="qty_kgs"
                                           class="form-control currency" required>
                                    <label for="total_kgs">Total KGs</label>
                                    <input value="<?php echo $total_kgs; ?>" id="total_kgs" name="total_kgs"
                                           class="form-control" required readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="empty_kgs">Empty KGs</label>
                                    <input value="<?php echo $empty_kgs; ?>" id="empty_kgs" name="empty_kgs"
                                           class="form-control currency" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="total_qty_kgs">Total Qty KGs</label>
                                    <input value="<?php echo $total_qty_kgs; ?>" id="total_qty_kgs" name="total_qty_kgs"
                                           class="form-control" required readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="net_kgs">NET KGs</label>
                                    <input value="<?php echo $net_kgs; ?>" id="net_kgs" name="net_kgs"
                                           class="form-control"
                                           required readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label for="divide">DIVIDE</label>
                                    <select id="divide" name="divide" class="form-select">
                                        <?php $divides = array('TON' => 'TON', 'KGs' => 'KG', 'CARTON' => 'CARTON');
                                        foreach ($divides as $item => $val) {
                                            $d_sel = $divide == $val ? 'selected' : '';
                                            echo '<option ' . $d_sel . ' value="' . $val . '">' . $item . '</option>';
                                        } ?>
                                    </select>
                                    <label for="weight">WEIGHT</label>
                                    <input value="<?php echo $weight; ?>" id="weight" name="weight"
                                           class="form-control currency" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="total">TOTAL</label>
                                    <input value="<?php echo $total; ?>" id="total" name="total" class="form-control"
                                           required readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="price">PRICE</label>
                                    <select id="price" name="price" class="form-select">
                                        <?php $prices = array('TON PRICE' => 'TON', 'KGs PRICE' => 'KG', 'CARTON PRICE' => 'CARTON');
                                        foreach ($prices as $item => $val) {
                                            $pr_sel = $price == $val ? 'selected' : '';
                                            echo '<option ' . $pr_sel . ' value="' . $val . '">' . $item . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="currency1">Currency</label>
                                    <select id="currency1" name="currency1" class="form-select" required>
                                        <option selected hidden disabled value="">Select</option>
                                        <?php $currencies = fetch('currencies');
                                        while ($crr = mysqli_fetch_assoc($currencies)) {
                                            $crr_sel = $crr['name'] == $currency1 ? 'selected' : '';
                                            echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group">
                                    <label for="rate1">RATE</label>
                                    <input value="<?php echo $rate1; ?>" id="rate1" name="rate1"
                                           class="form-control currency" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label for="amount" class="text-danger">AMOUNT</label>
                                    <input value="<?php echo $amount; ?>" id="amount" name="amount"
                                           class="form-control currency" required readonly>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-check mt-md-1">
                                    <input type="checkbox" class="form-check-input" id="is_qty" name="is_qty"
                                           value="1" <?php echo $is_qty; ?>>
                                    <label class="form-check-label" for="is_qty">Qty?</label>
                                </div>
                            </div>
                            <div class="col-md-2 toggleQty">
                                <div class="input-group">
                                    <label for="currency2">Currency</label>
                                    <select id="currency2" name="currency2" class="form-select" required>
                                        <option selected hidden disabled value="">Select</option>
                                        <?php $currencies = fetch('currencies');
                                        while ($crr = mysqli_fetch_assoc($currencies)) {
                                            $crr_sel2 = $crr['name'] == $currency2 ? 'selected' : '';
                                            echo '<option ' . $crr_sel2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1 toggleQty">
                                <div class="input-group">
                                    <label for="rate2">Rate</label>
                                    <input value="<?php echo $rate2; ?>" id="rate2" name="rate2"
                                           class="form-control currency">
                                </div>
                            </div>
                            <div class="col-md-1 toggleQty">
                                <div class="input-group">
                                    <label for="opr">Opr</label>
                                    <select id="opr" name="opr" class="form-select">
                                        <?php $ops = array('[*]' => '*', '[/]' => '/');
                                        foreach ($ops as $opName => $op) {
                                            $op_sel = $opr == $op ? 'selected' : '';
                                            echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 toggleQty">
                                <div class="input-group">
                                    <label for="final_amount" class="text-danger">FINAL</label>
                                    <input value="<?php echo $final_amount; ?>" id="final_amount" name="final_amount"
                                           class="form-control" required readonly>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="mt-3">
                        <div class="input-group">
                            <label for="s_name">REPORT</label>
                            <input value="<?php echo $report; ?>" id="report" name="report" type="text"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button name="recordSubmit" id="recordSubmit" type="submit" class="btn btn-primary btn-sm">
                            Submit
                        </button>
                        <?php if ($action_hidden == "update") {
                            echo '<a href="' . $url . '" class="btn btn-dark btn-sm">Add New</a>';
                        } ?>
                    </div>
                    <input type="hidden" name="action" value="<?php echo $action_hidden; ?>">
                    <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                    <input type="hidden" name="sd_id_hidden" value="<?php echo $sd_id; ?>">
                </div>
            </div>
        </form>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                        <tr class="text-nowrap">
                            <th>#</th>
                            <th>GOODS</th>
                            <th>SIZE</th>
                            <th>BRAND</th>
                            <th>QTY</th>
                            <th>KGs</th>
                            <th>EMPTY</th>
                            <th>NET KGs</th>
                            <th>Wt.</th>
                            <th>TOTAL</th>
                            <th>PRICE</th>
                            <th>AMOUNT</th>
                            <th class="text-end">FINAL</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sr_details = 1;
                        $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                        $pur_d_q = fetch('sale_details', array('parent_id' => $sale_id));
                        while ($details = mysqli_fetch_assoc($pur_d_q)) {
                            $details_id = $details['id'];
                            echo '<tr>';
                            echo '<td>' . $sr_details . '</td>';
                            echo '<td><a href="sale-add?id=' . $sale_id . '&action=update_details&sd_id=' . $details_id . '">' . goodsName($details['goods_id']) . '</a></td>';
                            echo '<td>' . $details['size'] . '</td>';
                            echo '<td>' . $details['brand'] . '</td>';
                            echo '<td>' . $details['qty_no'] . $details['qty_name'] . '</td>';
                            echo '<td>' . $details['total_kgs'] . '</td>';
                            echo '<td>' . round($details['total_qty_kgs'], 2) . '</td>';
                            echo '<td>' . $details['net_kgs'];
                            echo '<sub>' . $details['divide'] . '</sub>';
                            echo '</td>';
                            echo '<td>' . $details['weight'] . '</td>';
                            echo '<td>' . $details['total'] . '</td>';
                            echo '<td>' . $details['price'] . '</td>';
                            echo '<td>' . $details['amount'];
                            echo '<sub>' . $details['currency1'] . '</sub>';
                            echo '</td>';
                            echo '<td class="text-end">' . round($details['final_amount'], 2);
                            echo '<sub>' . $details['currency2'] . '</sub>';
                            echo '</td>';
                            echo '<td>';
                            //if (empty($p_data['khaata_tr1'])) {
                            $delete_msg = 'Are you sure to delete?';
                            echo '<form method="post" onsubmit="return confirm(\'' . $delete_msg . '\')"><input value="' . $sale_id . '" name="s_id_delete" type="hidden"><input value="' . $details_id . '" name="sd_id_delete" type="hidden">';
                            echo '<button name="deleteSDSubmit" type="submit" class="btn btn-sm p-0 ms-1 text-danger">Delete</button>';
                            echo '</form>';
                            //}
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
                            echo '<th colspan="4"></th>';
                            echo '<th class="fw-bold">' . $qty_no . '</th>';
                            echo '<th class="fw-bold">' . round($total_kgs, 2) . '</th>';
                            echo '<th class="fw-bold">' . round($total_qty_kgs, 2) . '</th>';
                            echo '<th class="fw-bold">' . round($net_kgs, 2) . '</th>';
                            echo '<th colspan="1"></th>';
                            echo '<th class="fw-bold">' . round($total, 2) . '</th>';
                            echo '<th colspan="1"></th>';
                            echo '<th class="fw-bold">' . round($amount, 2) . '</th>';
                            echo '<th class="fw-bold text-end">' . round($final_amount, 2) . '</th>';
                            echo '</tr>';
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordSubmit'])) {
    $type = 'danger';
    $msg = 'DB Error';
    $action = mysqli_real_escape_string($connect, $_POST['action']);
    $r_type = mysqli_real_escape_string($connect, $_POST['type']);
    $data = array(
        'type' => $r_type,
        's_date' => mysqli_real_escape_string($connect, $_POST['s_date']),
        's_name' => mysqli_real_escape_string($connect, $_POST['s_name']),
        'receiver' => mysqli_real_escape_string($connect, $_POST['receiver']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'p_khaata_no' => mysqli_real_escape_string($connect, $_POST['p_acc']),
        'report' => mysqli_real_escape_string($connect, $_POST['report']),
        'branch_id' => mysqli_real_escape_string($connect, $_POST['branch_id'])
    );

    $data2 = array(
        'goods_id' => mysqli_real_escape_string($connect, $_POST['goods_id']),
        'size' => mysqli_real_escape_string($connect, $_POST['size']),
        'brand' => mysqli_real_escape_string($connect, $_POST['brand']),
        'wh_k_id' => mysqli_real_escape_string($connect, $_POST['wh_k_id']),
        'wh_kd_id' => mysqli_real_escape_string($connect, $_POST['wh_kd_id']),
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
        'is_qty' => mysqli_real_escape_string($connect, $_POST['is_qty']),
        'currency2' => mysqli_real_escape_string($connect, $_POST['currency2']),
        'rate2' => mysqli_real_escape_string($connect, $_POST['rate2']),
        'opr' => mysqli_real_escape_string($connect, $_POST['opr']),
        'final_amount' => mysqli_real_escape_string($connect, $_POST['final_amount']),
    );
    if ($action == 'insert') {
        $data['created_by'] = $userId;
        $data['created_at'] = date('Y-m-d H:i:s');
        $done = insert('sales', $data);
        if ($done) {
            $pp_id = $connect->insert_id;
            $url .= "?id=" . $pp_id;
            $type = 'success';
            $msg = $r_type . ' Sale saved.';
            $data2['parent_id'] = $pp_id;
            $pd_sr = getSaleDetailsSerial($pp_id);
            $data2['d_sr'] = $pd_sr;
            $details_added = insert('sale_details', $data2);
            if ($details_added) {
                $ggd_id = $connect->insert_id;
                $url .= '&sd_id=' . $ggd_id . '&action=update_details';
            }
        }
    } else {
        $s_id_hidden = mysqli_real_escape_string($connect, $_POST['s_id_hidden']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('sales', $data, array('id' => $s_id_hidden));
        if ($done) {
            $url .= "?id=" . $s_id_hidden;
            $type = 'warning';
            $msg = $r_type . ' Sale updated ';
            if ($action == 'add_details') {
                $data2['parent_id'] = $s_id_hidden;
                $pd_sr = getSaleDetailsSerial($s_id_hidden);
                $data2['d_sr'] = $pd_sr;
                $details_added = insert('sale_details', $data2);
                if ($details_added) {
                    $ggd_id = $connect->insert_id;
                    $url .= '&sd_id=' . $ggd_id . '&action=update_details';
                    $msg .= ' and New Container saved.';
                }
            }
            if ($action == 'update_details') {
                $sd_id_hidden = mysqli_real_escape_string($connect, $_POST['sd_id_hidden']);
                $url .= '&sd_id=' . $sd_id_hidden . '&action=update_details';
                $details_added = update('sale_details', $data2, array('id' => $sd_id_hidden));
                if ($details_added) {
                    $msg .= ' with Container details.';
                }
            }
        }
    }
    message($type, $url, $msg);
}
if (isset($_POST['deleteSDSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $s_id_delete = mysqli_real_escape_string($connect, $_POST['s_id_delete']);
    $sd_id_delete = mysqli_real_escape_string($connect, $_POST['sd_id_delete']);
    $done = mysqli_query($connect, "DELETE FROM `sale_details` WHERE id='$sd_id_delete'");
    $url = "sale-add?id=" . $s_id_delete;
    if ($done) {
        $msg = "Purchase details Deleted.";
        $type = "success";
    }
    message($type, $url, $msg);
} ?>
<script type="text/javascript">
    /*var party_khaata_id = $('#party_khaata_id').find(":selected").val();
    warehouseKhaata(party_khaata_id);*/
    $("#party_khaata_id").change(function () {
        warehouseKhaata($(this).val());
    });

    $(document).ready(function () {
        var wh_kd_id = $('#wh_kd_id').find(":selected").val();
        warehouseDetails(wh_kd_id);

        $("#wh_kd_id").change(function () {
            var wh_kd_id = $(this).val();
            warehouseDetails(wh_kd_id);
        });


        var goods_id = $('#goods_id').find(":selected").val();
        goodDetails(goods_id);
        $("#goods_id").change(function () {
            var goods_id = $(this).val();
            goodDetails(goods_id);
        });

        $("#size").change(function () {
            var g = getSelectedGoodsID();
            var s = getSelectedSize();
            //var w = getSelectedWhKdID();
            topTotals(g, s);
        });
    });

    function warehouseKhaata(party_khaata_id) {
        $.ajax({
            type: 'POST',
            url: 'ajax/fetchKhaataDetailsDropdown.php',
            data: {khaata_id: party_khaata_id},
            success: function (html) {
                console.log(party_khaata_id);
                $('#wh_kd_id').html(html);
                var ddd = getSelectedWhKdID();
                warehouseDetails(ddd);
            }
        });
    }

    function warehouseDetails(wh_kd_id) {
        var gooods_id = getSelectedGoodsID();
        var siize = getSelectedSize();
        if (gooods_id) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_wh_by_goods_id.php',
                //dataType:JSON,
                data: 'goods_id=' + gooods_id + '&size=' + siize + '&wh_kd_id=' + wh_kd_id,
                success: function (response) {
                    console.log(response)
                    if (response.trim() !== '' && response.trim() !== '[]') {
                        var responseData = JSON.parse(response);
                        $('#warehouse_name').text(responseData.warehouse_name);
                        $('#wh_stock_qty_no').text(responseData.wh_stock_qty_no);
                        $('#wh_stock_total_kgs').text(responseData.wh_stock_total_kgs);
                        $('#wh_sale_qty_no').text(responseData.wh_sale_qty_no);
                        $('#wh_sale_total_kgs').text(responseData.wh_sale_total_kgs);
                        $('#wh_bal_qty_no').text(responseData.wh_bal_qty_no);
                        $('#wh_bal_total_kgs').text(responseData.wh_bal_total_kgs);
                    } else {
                        $('#wh_stock_qty_no').text();
                        $('#wh_stock_total_kgs').text();
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    }

    function goodDetails(goods_id) {
        if (goods_id > 0) {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_brands_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    $('#brand').html(html);
                }
            });
            /*$.ajax({
                type: 'POST',
                url: 'ajax/fetch_wh_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    //console.log(html);
                    $('#wh_kd_id').html(html);
                }
            });*/
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_purchase_account_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    var ppp = JSON.parse(html);
                    //console.log(html)
                    $('#ppp').html(ppp[0]);
                    $('#purchase_account').val(ppp[0]);
                }
            });
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_sizes_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    $('#size').html(html);
                    var size_value = getSelectedSize();
                    var wh_k_id = getSelectedWhKdID();
                    topTotals(goods_id, size_value, wh_k_id);
                }
            });
            /*$.ajax({
                type: 'POST',
                url: 'ajax/fetch_country_by_goods_id.php',
                data: 'goods_id=' + goods_id,
                success: function (html) {
                    $('#country').html(html);
                }
            });*/
            var whh = getSelectedWhKdID();
            warehouseDetails(whh);
        } else {
            $('#size').html('<option value="">Select</option>');
            $('#brand').html('<option value="">Select</option>');
        }
    }

    function topTotals(goods_id, size) {
        //console.log('Goods Id=' + goods_id +'Size=' + size + 'WH Khaata Id=' + warehouse_khaata_id)
        $.ajax({
            type: 'POST',
            url: 'ajax/fetch_totals_sale_entry.php',
            //dataType:JSON,
            data: 'goods_id=' + goods_id + '&size=' + size + '&type=purchase',
            success: function (response) {
                console.log(response)
                if (response.trim() !== '' && response.trim() !== '[]') {
                    var responseData = JSON.parse(response);
                    var TOTAL_PURCHASE_QTY = responseData.qty_no;
                    var TOTAL_PURCHASE_KGS = responseData.total_kgs;
                    $('#qty_no_span').text(TOTAL_PURCHASE_QTY);
                    $('#total_kgs_span').text(TOTAL_PURCHASE_KGS);
                    $.ajax({
                        type: 'POST',
                        url: 'ajax/fetch_totals_sale_entry.php',
                        data: 'goods_id=' + goods_id + '&size=' + size + '&type=sale',
                        //data: 'goods_id=' + goods_id + '&size=' + size + '&warehouse_khaata_id=' + warehouse_khaata_id + '&type=sale',
                        success: function (response) {
                            if (response.trim() !== '' && response.trim() !== '[]') {
                                var responseData = JSON.parse(response);
                                var TOTAL_SALE_QTY = responseData.qty_no;
                                var TOTAL_SALE_KGS = responseData.total_kgs;
                                $("#qty_no_sale_span").text(TOTAL_SALE_QTY);
                                $("#total_kgs_sale_span").text(TOTAL_SALE_KGS);

                                /*BALANCE*/
                                var qty_no_sale = $("#qty_no_sale").val();
                                var total_kgs_sale = $("#total_kgs_sale").val();
                                var qty_no_bal = TOTAL_PURCHASE_QTY - TOTAL_SALE_QTY;
                                $("#qty_no_bal_span").text(qty_no_bal);
                                var total_kgs_bal = TOTAL_PURCHASE_KGS - TOTAL_SALE_KGS;
                                $("#total_kgs_bal_span").text(total_kgs_bal);

                            } else {
                                $('#qty_no_span').text('');
                                $('#total_kgs_span').text('');
                            }
                        }
                    });
                    var qty_no_sale = $("#qty_no_sale").val();
                    var qty_no_bal = responseData.qty_no - qty_no_sale;
                    $("#qty_no_bal_span").text(qty_no_bal);
                    var total_kgs_sale = $("#total_kgs_sale").val();
                    var total_kgs_bal = responseData.total_kgs - total_kgs_sale;
                    $("#total_kgs_bal_span").text(total_kgs_bal);

                } else {
                    $('#qty_no_span').text('');
                    $('#total_kgs_span').text('');
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function getSelectedSize() {
        return $('#size :selected').val();
    }

    function getSelectedGoodsID() {
        return $('#goods_id :selected').val();
    }

    function getSelectedWhKdID() {
        return $('#wh_kd_id').find(":selected").val();
    }

    function getSelectedType() {
        return $('#type :selected').val();
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        toggleQtyAndRequired();
        $("#is_qty").change(toggleQtyAndRequired);
        $('#opr').on('change', function () {
            finalAmount();
        });
    });

    function toggleQtyAndRequired() {
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
    }

    function finalAmount() {
        var qty_no = parseFloat($("#qty_no").val()) || 0;
        var qty_kgs = parseFloat($("#qty_kgs").val()) || 0;

        var total_kgs = qty_no * qty_kgs;
        $("#total_kgs").val(total_kgs);

        var empty_kgs = parseFloat($("#empty_kgs").val()) || 0;
        var total_qty_kgs = qty_no * empty_kgs;
        $("#total_qty_kgs").val(total_qty_kgs);

        var net_kgs = total_kgs - total_qty_kgs;
        $("#net_kgs").val(net_kgs);

        var weight = parseFloat($("#weight").val()) || 0;
        var total = 0;

        if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
            total = net_kgs / weight;
            total = total.toFixed(3);
        }

        $("#total").val(isNaN(total) ? '' : total);

        var rate1 = parseFloat($("#rate1").val()) || 0;
        var final_amount = 0;
        var amount = 0;

        if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
            amount = total * rate1;
            final_amount = amount.toFixed(3);
        }

        $("#amount").val(isNaN(amount) ? '' : amount);

        if ($("#is_qty").prop('checked') == true) {
            var rate2 = parseFloat($("#rate2").val()) || 0;
            let operator = $('#opr').find(":selected").val();

            if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
                final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
                final_amount = final_amount.toFixed(3);
            }
        }

        $("#final_amount").val(isFinite(final_amount) ? final_amount : '');

        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('recordSubmit');
        } else {
            enableButton('recordSubmit');
        }
    }

    $(document).ready(function () {
        finalAmount();
        $('#qty_no,#qty_kgs,#empty_kgs,#weight,#rate1,#rate2').on('keyup', function () {
            finalAmount();
        });
    });
</script>