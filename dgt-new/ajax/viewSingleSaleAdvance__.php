<?php require_once '../connection.php';
$sale_id = $_POST['id'];
//$p_khaata_no = $_POST['p_khaata_no'];
if ($sale_id > 0) {
    $p_data = fetch('sales', array('id' => $sale_id));
    $record = mysqli_fetch_assoc($p_data);
    $is_transfer = $record['is_transfer'];
    $transfer = $record['transfer'];
    $sale_id = $record['id'];
    $sale_type = $record['type'];
    $topArray = array(
        array('heading' => 'SALE D.', 'value' => $record['s_date'], 'id' => ''),
        array('heading' => 'SALE BILL#', 'value' => $sale_id, 'id' => ''),
        array('heading' => 'B.', 'value' => branchName($record['branch_id']), 'id' => ''),
        array('heading' => 'CITY', 'value' => $record['city'], 'id' => ''),
        array('heading' => 'TYPE', 'value' => $sale_type, 'id' => ''),
        array('heading' => 'SALE NAME', 'value' => $record['s_name'], 'id' => ''),
        array('heading' => 'RECEIVER NAME', 'value' => $record['receiver'], 'id' => ''),
        //array('heading' => 'User', 'value' => getTableDataByIdAndColName('users', $record['created_by'], 'username'), 'id' => ''),
    );
    $details_k = array();
    $collaps_show = 'show';
    $is_loading = $is_receiving = $khaata_no = $khaata_id = $l_country = $l_port = $ctr_name = $r_country = $r_port = $ctr_name = '';
    $l_date = $r_date = $arrival_date = date('Y-m-d');
    if (!empty($record['seller_json'])) {
        $collaps_show = '';
        $seller_json = json_decode($record['seller_json']);
        //var_dump($seller_json);
        $khaata_no = $seller_json->khaata_no;
        $khaata_id = $seller_json->khaata_id;
        if (isset($seller_json->is_loading)) {
            if ($seller_json->is_loading == 1) {
                $is_loading = 'checked';
                $l_country = $seller_json->l_country;
                $l_port = $seller_json->l_port;
                $l_date = $seller_json->l_date;
                $ctr_name = $seller_json->ctr_name;
            }
        }
        if (isset($seller_json->is_receiving)) {
            if ($seller_json->is_receiving == 1) {
                $is_receiving = 'checked';
                $r_country = $seller_json->r_country;
                $r_port = $seller_json->r_port;
                $r_date = $seller_json->r_date;
                $arrival_date = $seller_json->arrival_date;
            }
        }
        $details_k = ['indexes' => json_encode($seller_json->rep_indexes), 'vals' => json_encode($seller_json->rep_vals)];
    }
    //var_dump($record); ?>
    <div class="row">
        <div class="col-10 order-0 content-column">
            <?php echo $_SESSION['response'] ?? ''; ?>
            <div class="row pt-3 gx-2 text-uppercase small">
                <div class="col-3">
                    <?php foreach ($topArray as $item) {
                        echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                    } ?>
                </div>
                <div class="col-6">
                    <div class="position-relative ">
                        <!--<div class="info-div">Seller</div>-->
                        <div class=" d-flex p-1">
                            <div>
                                <?php $array_acc1 = array(
                                    array('label' => 'Seller A/C#', 'id' => 's_khaata_no'),
                                    array('label' => 'A/C NAME', 'id' => 's_khaata_name'),
                                    array('label' => 'B.', 'id' => 's_b_name'),
                                    array('label' => 'CAT.', 'id' => 's_c_name'),
                                    array('label' => 'BUSINESS', 'id' => 's_business_name'),
                                    /*array('label' => 'ADDRESS', 'id' => 's_address'),*/
                                    array('label' => 'COMPANY', 'id' => 's_comp_name')); ?>
                                <?php foreach ($array_acc1 as $item) {
                                    echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '"></span><br>';
                                } ?>
                            </div>
                            <div>
                                <?php $array_acc3 = array(array('label' => '', 'id' => 's_contacts')); ?>
                                <?php foreach ($array_acc3 as $item) {
                                    echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '"></span><br>';
                                } ?>
                            </div>
                            <!--<div><img src="assets/images/logo-placeholder.png" id="s_khaata_image" class="avatar-lg rounded shadow" alt="Image"></div>-->
                        </div>
                    </div>
                </div>
                <div class="col">
                    <?php echo $record['report'] != '' ? '<b>REPORT: </b>' . $record['report'] : ''; ?>
                </div>
            </div>
            <?php $records2q = fetch('sale_details', array('parent_id' => $sale_id));
            $final_amounts = $amounts = $qtys = $totals = 0;
            $country = $allot = $goods = $curr = $rate = $curr2 = $rate2 = '';
            $rows = mysqli_num_rows($records2q);
            while ($record2 = mysqli_fetch_assoc($records2q)) {
                $arr1 = array(array('GOODS', goodsName($record2['goods_id'])), array('Size', $record2['size']), array('Brand', $record2['brand']));
                $arr2 = array(array('Qty Name', $record2['qty_name']), array('Qty#', $record2['qty_no']), array('Qty KGs', $record2['qty_kgs']), array('Total KGs', $record2['total_kgs']));
                $arr3 = array(array('Empty KGs', $record2['empty_kgs']), array('Total Qty KGs', $record2['total_qty_kgs']), array('Net KGs', $record2['net_kgs']), array('Divide', $record2['divide']));
                $arr4 = array(array('Weight', $record2['weight']), array('Total', $record2['total']), array('Price', $record2['price'] . ' PRICE'), array('Rate', $record2['rate1']));
                $arr5 = array(array('Amount ', $record2['amount'] . '<sub>' . $record2['currency1'] . '</sub>'), array('Qty?', $record2['is_qty'] == 1 ? 'YES' : 'NO'), array('Rate', $record2['rate2'] . ' [' . $record2['opr'] . ']'), array('Final Amount ', $record2['final_amount'] . '<sub>' . $record2['currency2'] . '</sub>'));
                $amounts += $record2['amount'];
                $final_amounts += $record2['final_amount'];
                $curr = $record2['currency1'];
                $rate = $record2['rate1'];
                $curr2 = $record2['currency2'];
                $rate2 = $record2['rate2'];
                $goods = goodsName($record2['goods_id']);
                $qtys += $record2['qty_no'];
                $totals += $record2['total']; ?>
                <div class="card text-uppercase small mb-0">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col">
                                <?php foreach ($arr1 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php
                                foreach ($arr2 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php
                                foreach ($arr3 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr4 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr5 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                    if ($item[1] == 'NO') continue;
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
            if (!empty($record['seller_json'])) { ?>
                <div class="card text-uppercase small mt-1">
                    <div class="card-body p-2">
                        <div class="row">
                            <div class="col-6">
                                <table class="table mb-2 table-sm">
                                    <tbody>
                                    <?php echo '<tr><td class="fw-bold text-uppercase">Loading Country</td><td>' . $seller_json->l_country . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Loading Port</td><td>' . $seller_json->l_port . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Loading Date</td><td>' . $seller_json->l_date . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Container Name</td><td>' . $seller_json->ctr_name . '</td></tr>'; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table mb-2 table-sm">
                                    <tbody>
                                    <?php echo '<tr><td class="fw-bold text-uppercase">Receiving Country</td><td>' . $seller_json->r_country . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Receiving Port</td><td>' . $seller_json->r_port . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Receiving Date</td><td>' . $seller_json->r_date . '</td></tr>';
                                    echo '<tr><td class="fw-bold text-uppercase">Arrival Data</td><td>' . $seller_json->arrival_date . '</td></tr>'; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <?php $details_k = ['indexes' => json_encode($seller_json->rep_indexes), 'vals' => json_encode($seller_json->rep_vals)];
                                $reps = displayKhaataDetails($details_k, true);
                                if (array_key_exists('Condition', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Goods Condition Report <span class="fw-normal text-decoration-underline">' . $reps['Condition'] . '</span></div>';
                                }
                                if (array_key_exists('Loading', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Loading Report <span class="fw-normal text-decoration-underline">' . $reps['Loading'] . '</span></div>';
                                }
                                if (array_key_exists('Booking', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Booking Report <span class="fw-normal text-decoration-underline">' . $reps['Booking'] . '</span></div>';
                                }
                                if (array_key_exists('Final', $reps)) {
                                    echo '<div class="fw-bold text-uppercase">Final Report <span class="fw-normal text-decoration-underline">' . $reps['Final'] . '</span></div>';
                                } ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3 d-none">
                                <?php echo '<div><b>Loading Country</b> ' . $l_country . '</div>'; ?>
                                <?php echo '<div><b>Loading Port</b> ' . $l_port . '</div>'; ?>
                                <?php echo '<div><b>Loading Date</b> ' . $l_date . '</div>'; ?>
                                <?php echo '<div><b>Container Name</b> ' . $ctr_name . '</div>'; ?>
                            </div>
                            <div class="col-3 d-none">
                                <?php echo '<div><b>Receiving Country</b> ' . $r_country . '</div>'; ?>
                                <?php echo '<div><b>Receiving Port</b> ' . $r_port . '</div>'; ?>
                                <?php echo '<div><b>Receiving Date</b> ' . $r_date . '</div>'; ?>
                                <?php echo '<div><b>Arrival Date</b> ' . $arrival_date . '</div>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ($is_transfer == 0) { ?>
                <div class="mt-3 table-form collapse <?php echo $collaps_show; ?>" id="collapseSellerForm">
                    <form method="post">
                        <div class="row gx-1 align-items-center">
                            <div class="col-md-3">
                                <div class="input-group position-relative">
                                    <label for="khaata_no">Seller A/c No.</label>
                                    <input value="<?php echo $khaata_no; ?>" type="text" id="khaata_no"
                                           name="khaata_no" class="form-control bg-transparent-" required autofocus>
                                    <small class="error-response top-0" id="response"></small>
                                </div>
                                <input type="hidden" name="khaata_id" id="khaata_id">
                            </div>
                        </div>
                        <div class="row mb-2 gx-1">
                            <div class="col-md-auto">
                                <div class="form-check mt-md-1">
                                    <input type="checkbox" class="form-check-input" id="is_loading" name="is_loading"
                                           value="1" <?php echo $is_loading; ?>>
                                    <label class="form-check-label" for="is_loading">Loading?</label>
                                </div>
                            </div>
                            <div class="col-md toggleLoading">
                                <div class="input-group">
                                    <label for="l_country">Country</label>
                                    <input value="<?php echo $l_country; ?>" type="text" class="form-control"
                                           id="l_country" name="l_country">
                                </div>
                            </div>
                            <div class="col-md toggleLoading">
                                <div class="input-group">
                                    <label for="l_port">Port</label>
                                    <input value="<?php echo $l_port; ?>" type="text" class="form-control" id="l_port"
                                           name="l_port">
                                </div>
                            </div>
                            <div class="col-md toggleLoading">
                                <div class="input-group">
                                    <label for="l_date" class="text-nowrap">Loading </label>
                                    <input type="date" class="form-control" id="l_date" name="l_date"
                                           value="<?php echo $l_date; ?>">
                                </div>
                            </div>
                            <div class="col-md toggleLoading">
                                <div class="input-group">
                                    <label for="ctr_name" class="text-nowrap">Container Name</label>
                                    <input value="<?php echo $ctr_name; ?>" type="text" class="form-control"
                                           id="ctr_name" name="ctr_name">
                                </div>
                            </div>
                        </div>
                        <div class="row gx-1 mb-2">
                            <div class="col-md-auto">
                                <div class="form-check mt-md-1">
                                    <input type="checkbox" class="form-check-input" id="is_receiving"
                                           name="is_receiving" value="1" <?php echo $is_receiving; ?>>
                                    <label class="form-check-label" for="is_receiving">Receiving?</label>
                                </div>
                            </div>
                            <div class="col-md toggleReceiving">
                                <div class="input-group">
                                    <label for="r_country">Country</label>
                                    <input value="<?php echo $r_country; ?>" type="text" class="form-control"
                                           id="r_country" name="r_country">
                                </div>
                            </div>
                            <div class="col-md toggleReceiving">
                                <div class="input-group">
                                    <label for="r_port">Port</label>
                                    <input value="<?php echo $r_port; ?>" type="text" class="form-control" id="r_port"
                                           name="r_port">
                                </div>
                            </div>
                            <div class="col-md toggleReceiving">
                                <div class="input-group">
                                    <label for="r_date" class="text-nowrap">Receiving</label>
                                    <input type="date" class="form-control" id="r_date" name="r_date"
                                           value="<?php echo $r_date; ?>">
                                </div>
                            </div>
                            <div class="col-md toggleReceiving">
                                <div class="input-group">
                                    <label for="arrival_date" class="text-nowrap">Arrival</label>
                                    <input type="date" class="form-control" id="arrival_date" name="arrival_date"
                                           value="<?php echo $arrival_date; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row gx-5">
                            <div class="col-9">
                                <table class="table table-borderless table-pb mb-0" id="reportsTable">
                                    <tbody>
                                    <?php $reps = displayKhaataDetails($details_k, true);
                                    //$reps = array();
                                    $x = 1;
                                    $arrayNumber = 0;
                                    foreach ($reps as $key => $val) { ?>
                                        <tr id="rep_row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                            <td style="width: 20%">
                                                <select id="rep_indexes<?php echo $x; ?>" name="rep_indexes[]"
                                                        class="form-select">
                                                    <?php $static_types = fetch('static_types', array('type_for' => 'purchase_add'));
                                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                        $ss = $static_type['type_name'] == $key ? 'selected' : '';
                                                        echo '<option ' . $ss . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="rep_vals[]" required class="form-control"
                                                       id="rep_vals<?php echo $x; ?>" value="<?php echo $val; ?>">
                                            </td>
                                            <td style="width: 5%">
                                            <span id="removeReportRowBtn<?php echo $x; ?>"
                                                  class="btn btn-link text-danger p-1"
                                                  onclick="removeReportRow(<?php echo $x; ?>)">DELETE</span>
                                            </td>
                                        </tr>
                                        <?php $arrayNumber++;
                                        $x++;
                                    } ?>
                                    </tbody>
                                </table>
                                <span class="btn btn-secondary mt-2 btn-sm" onclick="addReportRow()"
                                      id="addReportRowBtn"
                                      data-loading-text="Loading...">+ Add Report</span>
                            </div>
                            <div class="col-3">
                                <button name="sellerAccountSubmit" id="recordSubmitSeller" type="submit"
                                        class="w-100 btn btn-primary btn-sm">Submit
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                    </form>
                </div>
            <?php } ?>
            <?php if (isset($_POST['show_transfer'])) {
                if ($transfer > 0) {//save adv / full payment before send to roznamcha
                    $ddd = 'ENTRY:' . $rows . ' GOODS:' . $goods . ' CITY:' . $record['city'] . ' T.Qty:' . $qtys . ' T.KGs:' . $totals . ' RATE:' . $rate . ' T.AMNT:' . $amounts . $curr . ' EXCH.:' . $curr2; ?>
                    <div class="card">
                        <div class="card-body p-2">
                            <form method="post">
                                <div class="row gx-1 gy-3 table-form mb-3">
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="s_khaata_no" class="text-success">Dr. (Sale)</label>
                                            <input value="<?php echo $khaata_no; ?>" id="s_khaata_no"
                                                   name="dr_khaata_no" readonly tabindex="-1" class="form-control" required>
                                        </div>
                                        <input type="hidden" name="dr_khaata_id" value="<?php echo $khaata_id; ?>">
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="p_khaata_no" class="text-danger">Cr. (Purchaser)</label>
                                            <input type="text" id="p_khaata_no" name="cr_khaata_no" class="form-control"
                                                   readonly tabindex="-1" value="<?php echo $record['p_khaata_no']; ?>">
                                            <?php $p_acc_details = khaataSingle($record['p_khaata_no'], true); ?>
                                        </div>
                                        <input type="hidden" name="cr_khaata_id"
                                               value="<?php echo $p_acc_details['id']; ?>">
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="transfer_date">Date</label>
                                            <input type="date" class="form-control" id="transfer_date"
                                                   name="transfer_date" required value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="amount" class="mb-0">Amount</label>
                                            <?php $this_amount = $transfer == 1 ? $record['pct_amt'] : $final_amounts; ?>
                                            <input value="<?php echo round($this_amount, 2); ?>" id="amount" readonly
                                                   name="amount" class="form-control" required tabindex="-1">
                                        </div>
                                    </div>
                                    <div class="col-11">
                                        <div class="input-group">
                                            <label for="details">Details</label>
                                            <input type="text" name="details" id="details" class="form-control"
                                                   value="<?php echo $ddd; ?>">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button name="ttrFirstSubmit" type="submit"
                                                class="btn btn-primary w-100 btn-sm"><i class="fa fa-upload"></i>Transfer
                                        </button>
                                    </div>
                                    <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                                    <input type="hidden" name="type" value="<?php echo $sale_type; ?>">
                                </div>
                                <?php if ($record['khaata_tr1'] != '') {
                                    $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $sale_id, 'transfered_from' => 'sale_' . $record['type']));
                                    if (mysqli_num_rows($rozQ) > 0) { ?>
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead>
                                            <tr>
                                                <th>Sr#</th>
                                                <th>Date</th>
                                                <th>A/c#</th>
                                                <th>Roz.#</th>
                                                <th>Name</th>
                                                <th>No</th>
                                                <th>Details</th>
                                                <th>Dr.</th>
                                                <th>Cr.</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                                                $dr = $cr = 0; ?>
                                                <input type="hidden" value="<?php echo $roz['r_date']; ?>" id="temp_transfer_date">
                                                <input type="hidden" value="<?php echo $roz['r_id']; ?>" name="r_id[]">
                                                <tr>
                                                    <td>
                                                        <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                                    </td>
                                                    <td><?php echo $roz['r_date']; ?></td>
                                                    <td>
                                                        <a href="ledger?back-khaata-no=<?php echo $roz['khaata_no']; ?>"
                                                           target="_blank"><?php echo $roz['khaata_no']; ?></a></td>
                                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                                    <td class="small"><?php echo $roz['r_name']; ?></td>
                                                    <td><?php echo $roz['r_no']; ?></td>
                                                    <td class="small"><?php echo $roz['details']; ?></td>
                                                    <?php if ($roz['dr_cr'] == "dr") {
                                                        $dr = $roz['amount'];
                                                    } else {
                                                        $cr = $roz['amount'];
                                                    } ?>
                                                    <td class="text-success"><?php echo $dr; ?></td>
                                                    <td class="text-danger"><?php echo $cr; ?></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
        <div class="col-2 order-1 fixed-sidebar table-form">
            <div>
                <?php if (isset($_POST['show_transfer'])) {
                    if ($is_transfer == 1) { ?>
                        <p class="bold mb-0"><?php echo 'Amount: ' . round($final_amounts, 2); ?></p>
                        <form method="post">
                            <select class="form-select" id="transfer" name="transfer" required>
                                <option value="" hidden="">Select</option>
                                <?php $transfer_array = array('Advance' => '1', 'Full Payment' => '2');
                                foreach ($transfer_array as $item => $value) {
                                    $t_sel = $value == $record['transfer'] ? 'selected' : '';
                                    echo '<option ' . $t_sel . ' value="' . $value . '">' . $item . '</option>';
                                } ?>
                            </select>
                            <input id="final_amt_hidden" type="hidden" value="<?php echo $final_amounts; ?>">
                            <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                            <div class="row g-0">
                                <div class="col-3">
                                    <div class="input-group">
                                        <label for="pct" id="pct_label">%</label>
                                        <input value="<?php echo $record['pct'] ?>" id="pct" name="pct" type="number" min="1" max="100" step="any" class="form-control" placeholder="%">
                                    </div>
                                </div>
                                <div class="col">
                                    <input value="<?php echo $record['pct_amt'] ?>" readonly id="pct_amt" name="pct_amt"
                                           class="form-control"
                                           placeholder="Adv. Amount">
                                </div>
                            </div>
                            <button name="transferAdvFullSubmit" type="submit" class="btn btn-sm btn-secondary">Submit
                            </button>
                        </form>
                    <?php }
                } ?>
                <form id="purchaseAttachSubmit" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="s_id_hidden_attach" value="<?php echo $sale_id; ?>">
                    <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                    <input type="button" class="form-control rounded-1 bg-dark mt-3 text-white"
                           value="+ Documents" onclick="document.getElementById('attachments').click();"/>
                </form>
                <script>
                    document.getElementById("attachments").onchange = function () {
                        document.getElementById("purchaseAttachSubmit").submit();
                    }
                </script>
                <?php $attachments = fetch('attachments', array('source_id' => $sale_id, 'source_name' => 'sales'));
                if (mysqli_num_rows($attachments) > 0) {
                    //echo '<h6 class=" mb-0 d-inline fw-bold">Documents </h6>';
                    $no = 1;
                    while ($attachment = mysqli_fetch_assoc($attachments)) {
                        $link = 'attachments/' . $attachment['attachment'];
                        echo $no . '.<a class="text-decoration-underline" href="' . $link . '" target="_blank">' . readMore($attachment['attachment'], 27) . '</a><br>';
                        $no++;
                    }
                }
                if ($is_transfer == 0) {
                    if (!empty($record['seller_json'])) { ?>
                        <form method="post"
                              onsubmit="return confirm('Do you want to transfer this sale?.\n Press OK to transfer')">
                            <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                            <button name="transferSaleSubmit" type="submit" class="btn btn-dark btn-sm w-100 mt-3">
                                <?php echo 'TRANSFER TO ' . strtoupper($sale_type); ?>
                            </button>
                        </form>
                    <?php }
                } else {
                    echo '<p class="mt-3 bold text-uppercase text-center">TRANSFERRED in ' . $sale_type . ' at ' . $record['tr_date'] . '</p>';
                } ?>
            </div>
            <div class="bottom-buttons">
                <div class="px-2">
                    <?php if ($is_transfer == 0) { ?>
                        <a class="btn btn-dark btn-sm w-100" data-bs-toggle="collapse" href="#collapseSellerForm"
                           role="button" aria-expanded="false" aria-controls="collapseExample">EDIT SELLER DETAILS</a>
                    <?php } ?>
                    <a href="<?php echo 'sale-add?id=' . $sale_id; ?>" class="btn btn-dark btn-sm w-100 mt-3">UPDATE</a>
                    <?php if ($is_transfer == 0) { ?>
                        <form method="post" onsubmit="return confirm('Are you sure to delete?');">
                            <input type="hidden" name="s_id_hidden" value="<?php echo $sale_id; ?>">
                            <button name="deleteSaleSubmit" type="submit" class="btn btn-danger btn-sm w-100 mt-3">
                                DELETE
                            </button>
                        </form>
                    <?php } ?>
                    <a href="print/sales-invoice?s_id=<?php echo $sale_id; ?>&action=booking" target="_blank"
                       class="btn btn-success btn-sm w-100 mt-3">PRINT</a>
                </div>
            </div>

        </div>
    </div>
<?php } ?>
<script>
    disableButton('recordSubmitSeller');
    $(document).on('keyup', "#khaata_no", function (e) {
        fetchKhaata();
    });
    fetchKhaata();

    function fetchKhaata() {
        let khaata_no = $("#khaata_no").val();
        let khaata_id = $("#khaata_id");
        var prefix = '#s';
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton('recordSubmitSeller');
                    khaata_id.val(response.messages['khaata_id']);
                    $(prefix + '_khaata_no').text(khaata_no);
                    $(prefix + '_khaata_name').text(response.messages['khaata_name']);
                    $(prefix + '_b_name').text(response.messages['b_code']);
                    $(prefix + '_c_name').text(response.messages['name']);
                    $(prefix + '_business_name').text(response.messages['business_name']);
                    $(prefix + '_address').text(response.messages['address']);
                    $(prefix + '_comp_name').text(response.messages['comp_name']);
                    var details = {indexes: response.messages['indexes'], vals: response.messages['vals']};
                    $(prefix + '_contacts').html(displayKhaataDetails(details));
                    //$(khaataImageId).attr("src", response.messages['image']);
                    /*var seller_details = '<b>A/C Name</b>' + response.messages['khaata_name'] + response.messages['comp_name'];
                    $("#seller-details").html(seller_details);*/
                    $("#recordSubmitSeller").prop('disabled', false);
                    $("#response").text('');
                }
                if (response.success === false) {
                    $(prefix + '_khaata_no').text('');
                    $(prefix + '_khaata_name').text('');
                    $(prefix + '_b_name').text('');
                    $(prefix + '_c_name').text('');
                    $(prefix + '_business_name').text('');
                    $(prefix + '_address').text('');
                    $(prefix + '_comp_name').text('');
                    $(prefix + '_contacts').html('');
                    disableButton('recordSubmitSeller');
                    $("#response").text('INVALID');
                    khaata_id.val(0);
                }
            }
        });
    }

    function displayKhaataDetails(details) {
        var html = '';
        if (details.indexes && details.vals) {
            var indexes = JSON.parse(details.indexes);
            var vals = JSON.parse(details.vals);

            if (Array.isArray(indexes) && Array.isArray(vals)) {
                var count = Math.min(indexes.length, vals.length);

                for (var i = 0; i < count; i++) {
                    var key = indexes[i];
                    var value = vals[i];
                    // Construct the HTML string
                    html += '<b class="text-dark">' + (key) + '</b>' + value + '<br>';
                }
            }
        }

        return html; // Return the constructed HTML string
    }
</script>
<script>
    toggleLoadingAndRequired();
    $("#is_loading").change(toggleLoadingAndRequired);

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

    toggleReceivingAndRequired();
    $("#is_receiving").change(toggleReceivingAndRequired);

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
<script type="text/javascript">
    function addReportRow() {
        $("#addReportRowBtn").button("loading");
        var tableLength = $("#reportsTable tbody tr").length;
        var tableRow;
        var arrayNumber;
        var count;
        if (tableLength > 0) {
            tableRow = $("#reportsTable tbody tr:last").attr('id');
            arrayNumber = $("#reportsTable tbody tr:last").attr('class');
            count = parseInt(tableRow.match(/\d+/)[0], 10) + 1;
            arrayNumber = Number(arrayNumber) + 1;
        } else {
            count = 1;
            arrayNumber = 0;
        }
        console.log(count);
        $("#addReportRowBtn").button("reset");
        $.ajax({
            type: 'GET',
            url: 'ajax/fetchStaticTypesForPurchaseAddReports.php',
            success: function (html) {
                $('#rep_indexes' + count).html(html);
            }
        });
        var tr = '<tr id="rep_row' + count + '" class="' + arrayNumber + '">' +
            '<td style="width: 20%">' +
            '<select id="rep_indexes' + count + '" name="rep_indexes[]" class="form-select">' +
            '</select>' +
            '</td>' +
            '<td>' +
            '<input type="text" name="rep_vals[]" required class="form-control" id="rep_vals' + count + '">' +
            '</td>' +
            '<td style="width: 5%">' +
            '<span id="removeReportRowBtn' + count + '" class="btn btn-link text-danger p-1" onclick="removeReportRow(' + count + ')">DELETE</span>' +
            '</td>' +
            '</tr>';
        if (tableLength > 0) {
            $("#reportsTable tbody tr:last").after(tr);
        } else {
            $("#reportsTable tbody").append(tr);
        }
    }

    function removeReportRow(row = null) {
        if (row) {
            var tableLength = $("#reportsTable tbody tr").length;
            if (tableLength > 1) {
                $("#rep_row" + row).remove();
            }
            //subAmount();
        } else {
            alert('error! Refresh the page again');
        }
    }
</script>
<script>
    $(document).ready(function () {
        function hidePctInputs() {
            $("#pct,#pct_label, #pct_amt").hide();
        }

        function showPctInputs() {
            $("#pct,#pct_label, #pct_amt").show();
        }

        function updatePctAmt() {
            var pctValue = parseFloat($("#pct").val()) || 0;
            var finalAmt = parseFloat($("#final_amt_hidden").val()) || 0;
            var pctAmt = (pctValue / 100) * finalAmt;

            $("#pct_amt").val(pctAmt.toFixed(2));
        }

        // Initialize: Hide pct inputs
        hidePctInputs();

        // Show/hide pct inputs based on the selected option
        $("#transfer").change(function () {
            showHidePctInputs($(this).val());
        });

        function showHidePctInputs(transfer = null) {
            if (transfer == "1") {
                showPctInputs();
            } else {
                hidePctInputs();
            }
        }

        let transfer = $('#transfer').find(":selected").val();
        showHidePctInputs(transfer);

        // Update pct_amt when user inputs a number in pct
        $("#pct").on("input", updatePctAmt);

    });
</script>