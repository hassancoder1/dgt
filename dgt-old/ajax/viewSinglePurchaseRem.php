<?php require_once '../connection.php';
$purchase_id = $_POST['id'];
$purchase_pays_id = $_POST['purchase_pays_id'];
if ($purchase_id > 0) {
    $p_data = fetch('purchases', array('id' => $purchase_id));
    $record = mysqli_fetch_assoc($p_data);
    $purchase_type = $record['type'];
    $p_khaata = khaataSingle($record['p_khaata_id']);
    $s_khaata = khaataSingle($record['s_khaata_id']);
    $topArray = array(
        array('heading' => 'Sr#', 'value' => $purchase_id, 'id' => ''),
        array('heading' => 'User', 'value' => getTableDataByIdAndColName('users', $record['created_by'], 'username'), 'id' => ''),
        array('heading' => 'DATE', 'value' => $record['p_date'], 'id' => ''),
        array('heading' => 'BRANCH', 'value' => branchName($record['branch_id']), 'id' => ''),
        array('heading' => 'COUNTRY', 'value' => $record['country'], 'id' => ''),
        array('heading' => 'TYPE', 'value' => $record['type'], 'id' => ''),
        array('heading' => 'ALLOT NAME', 'value' => $record['allot'], 'id' => ''),
    );
    $totalPurchaseAmount = totalPurchaseAmount($purchase_id);
    $adv_paid_total = purchaseSpecificData($purchase_id, 'adv_paid_total');
    $rem_paid_total = purchaseSpecificData($purchase_id, 'rem_paid_total');
    $rem_amount = $totalPurchaseAmount - $record['pct_amt'];
    $rem_pct = 100 - $record['pct'];
    $bal = $totalPurchaseAmount - $rem_paid_total - $adv_paid_total;
    //var_dump($record); ?>
    <div class="row">
        <div class="col-10 order-1 content-column">
            <div class="card">
                <div class="card-body p-2 text-uppercase small">
                    <?php echo $_SESSION['response'] ?? ''; ?>
                    <div class="row gx-2">
                        <div class="col-auto">
                            <?php foreach ($topArray as $item) {
                                echo '<b>' . $item['heading'] . '</b><span id="' . $item['id'] . '">' . $item['value'] . '</span><br>';
                            } ?>
                        </div>
                        <div class="col">
                            <div class="card- mb-1 position-relative">
                                <div class="info-div">Purchaser</div>
                                <div class="d-flex p-1">
                                    <div>
                                        <?php $array_acc1 = array(
                                            array('label' => 'A/C#', 'id' => 'p_khaata_no', 'val' => $record['p_khaata_no']),
                                            array('label' => 'A/C NAME', 'id' => 'p_khaata_name', 'val' => $p_khaata['khaata_name']),
                                            array('label' => 'BRANCH', 'id' => 'p_b_name', 'val' => branchName($p_khaata['branch_id'])),
                                            array('label' => 'CATEGORY', 'id' => 'p_c_name', 'val' => catName($p_khaata['cat_id'])),
                                            array('label' => 'BUSINESS', 'id' => 'p_business_name', 'val' => $p_khaata['business_name']),
                                            array('label' => 'COMPANY', 'id' => 'p_comp_name', 'val' => $p_khaata['comp_name'])
                                        ); ?>
                                        <?php foreach ($array_acc1 as $item) {
                                            echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                                        } ?>
                                    </div>
                                    <div>
                                        <?php //echo '<span class="text-muted" id="p_contacts">';
                                        $details1 = ['indexes' => $p_khaata['indexes'], 'vals' => $p_khaata['vals']];
                                        echo displayKhaataDetails($details1);
                                        //echo '</span>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-1 position-relative">
                                <div class="info-div">SELLER</div>
                                <div class="d-flex p-1">
                                    <div>
                                        <?php $array_acc2 = array(
                                            array('label' => 'A/C#', 'id' => 's_khaata_no', 'val' => $record['s_khaata_no']),
                                            array('label' => 'A/C NAME', 'id' => 's_khaata_name', 'val' => $s_khaata['khaata_name']),
                                            array('label' => 'BRANCH', 'id' => 's_b_name', 'val' => branchName($s_khaata['branch_id'])),
                                            array('label' => 'CATEGORY', 'id' => 's_c_name', 'val' => catName($s_khaata['cat_id'])),
                                            array('label' => 'BUSINESS', 'id' => 's_business_name', 'val' => $s_khaata['business_name']),
                                            array('label' => 'COMPANY', 'id' => 's_comp_name', 'val' => $s_khaata['comp_name'])
                                        ); ?>
                                        <?php foreach ($array_acc2 as $item) {
                                            echo '<b>' . $item['label'] . '</b><span class="text-muted">' . $item['val'] . '</span><br>';
                                        } ?>
                                    </div>
                                    <div>
                                        <?php //echo '<span class="text-muted" id="s_contacts">';
                                        $details2 = ['indexes' => $s_khaata['indexes'], 'vals' => $s_khaata['vals']];
                                        echo displayKhaataDetails($details2);
                                        ///echo '</span>'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <?php $records2q = fetch('purchase_details', array('parent_id' => $purchase_id));
                    $final_amounts = $amounts = $qtys = $totals = 0;
                    $country = $allot = $goods = $curr = $rate = $curr2 = $rate2 = '';
                    $rows = mysqli_num_rows($records2q);
                    while ($record2 = mysqli_fetch_assoc($records2q)) {
                        $arr1 = array(
                            array('GOODS', goodsName($record2['goods_id'])),
                            array('Size', $record2['size']),
                            array('Brand', $record2['brand']),
                            array('Origin', $record2['origin']),
                        );
                        $arr2 = array(
                            array('Qty Name', $record2['qty_name']),
                            array('Qty#', $record2['qty_no']),
                            array('Qty KGs', $record2['qty_kgs']),
                            array('Total KGs', $record2['total_kgs']),
                        );
                        $arr3 = array(
                            array('Empty KGs', $record2['empty_kgs']),
                            array('Total Qty KGs', round($record2['total_qty_kgs'], 2)),
                            array('Net KGs', round($record2['net_kgs'], 2)),
                            array('Divide', $record2['divide']),
                        );
                        $arr4 = array(
                            array('Weight', $record2['weight']),
                            array('Total', $record2['total']),
                            array('Price', $record2['price'] . ' PRICE'),
                            //array('Currency', $record2['currency1']),
                            array('Rate', $record2['rate1']),
                        );
                        $arr5 = array(
                            array('Amount ', round($record2['amount']) . '<sub>' . $record2['currency1'] . '</sub>'),
                            array('Qty?', $record2['is_qty'] == 1 ? 'YES' : 'NO'),
                            array('Rate', $record2['rate2'] . ' [' . $record2['opr'] . ']')
                        );
                        $arr6 = array(
                            array('Final Amount ', $record2['final_amount'] . '<sub>' . $record2['currency2'] . '</sub>'),
                        );
                        $amounts += $record2['amount'];
                        $final_amounts += $record2['final_amount'];
                        $curr = $record2['currency1'];
                        $rate = $record2['rate1'];
                        $curr2 = $record2['currency2'];
                        $rate2 = $record2['rate2'];
                        $goods = goodsName($record2['goods_id']);
                        $qtys += $record2['qty_no'];
                        $totals += $record2['total']; ?>
                        <div class="row mb-1">
                            <div class="col">
                                <?php foreach ($arr1 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr2 as $item) {
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
                                    if ($item[1] == 'NO') exit();
                                } ?>
                            </div>
                            <div class="col">
                                <?php foreach ($arr6 as $item) {
                                    echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                                    if ($item[1] == 'NO') exit();
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-12 mt-2">
                            <?php $details2 = ['indexes' => $record['rep_indexes'], 'vals' => $record['rep_vals']];
                            $reps = displayKhaataDetails($details2, true);
                            foreach ($reps as $key => $val) {
                                echo '<span class=""><b>' . $key . ' Report: </b>' . $val . '</span><br>';
                            } ?>
                        </div>
                    </div>
                    <?php if ($record['khaata_tr1'] != '') {
                        $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $purchase_id, 'transfered_from' => 'purchase_' . $record['type']));
                        $roz = mysqli_fetch_assoc($rozQ);
                        $roz_arr1 = array(
                            array('Sr#', SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']),
                            array('Date', $roz['r_date']),
                            array('ID', $roz['username']),
                            array('Branch', branchName($roz['branch_id'])),
                        );
                        $roz_arr2 = array(
                            array('Roz.#', $roz['roznamcha_no']),
                            array('Name', $roz['r_name']),
                            array('No', $roz['r_no']),
                        );
                        $roz_arr3 = array(
                            array('Dr.', $roz['amount']),
                            //array('Cr.', 0),
                        );
                        $roz_arr4 = array(
                            array('Total Amt', round($final_amounts)),
                            //array('Percent', $record['pct'] . '%'),
                            array('Advance', round($record['pct_amt']) . '<sub>' . $record['pct'] . '%</sub>'),
                        );
                        //echo '<b>Remaining </b>' . round($rem_amount) . '<sub>' . $rem_pct . '%</sub>';
                    } ?>
                    <hr class="my-0">
                    <div class="row">
                        <div class="col-2">
                            <?php foreach ($roz_arr1 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col-2">
                            <?php foreach ($roz_arr2 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col-2">
                            <?php foreach ($roz_arr3 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col-2">
                            <?php foreach ($roz_arr4 as $item) {
                                echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                            } ?>
                        </div>
                        <div class="col">
                            <?php echo '<b>Remaining </b>' . round($rem_amount) . '<sub>' . $rem_pct . '%</sub>'; ?>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="row align-content-center">
                        <div class="col-10">
                            <?php $rem_paid = purchaseSpecificData($purchase_id, 'rem');
                            foreach ($rem_paid as $item) {
                                echo '<div class="row mb-2">';
                                echo '<div class="col">';
                                echo '<a href="purchase-rem?view=1&p_id=' . $purchase_id . '&purchase_pays_id=' . $item['id'] . '">';
                                echo '<b>Dr. A/c.</b>' . $item['dr_khaata_no'];
                                echo '</a>';
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Cr. A/c.</b>' . $item['cr_khaata_no'];
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Amount.</b>' . round($item['amount']) . '<sub>' . $item['currency1'] . '</sub>';
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Rate</b>' . $item['rate'] . ' [' . $item['opr'] . ']';
                                echo '</div>';
                                echo '<div class="col">';
                                echo '<b>Final</b>' . round($item['final_amount']) . '<sub>' . $item['currency2'] . '</sub>';
                                echo '</div>';
                                echo '<div class="col-12">';
                                echo '<b>Report</b>' . $item['report'];
                                echo '</div>';
                                echo '</div>';
                            } ?>
                        </div>
                        <div class="col-2">
                            <?php echo '<h6 class="text-success mb-0"><b>TOTAL </b>' . round($rem_paid_total) . '</h6>';
                            echo '<h6 class="text-danger mb-0"><b> BALNC </b>' . round($bal) . '</h6>';
                            if ($bal <= 10) {
                                if ($record['transfer'] == 1) { ?>
                                    <form method="post"
                                          onsubmit="return confirm('Transfer to Full Payment Form?\n Press OK to transfer')">
                                        <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                                        <button name="transferRemToFull" type="submit" class="btn btn-dark btn-sm">
                                            TRANSFER
                                        </button>
                                    </form>
                                <?php } else {
                                    echo '<i class="fa fa-check-double text-success"></i> Transferred';
                                }
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collapse show" id="collapseExample">
                <input type="hidden" id="balance" value="<?php echo $bal; ?>">
                <?php $adv_arr = array(
                    'finish' => array('div_class' => '', 'btn_text' => 'Transfer', 'btn_class' => 'btn-primary', 'back' => '', 'purchase_pays_id' => $purchase_pays_id, 'action' => 'insert'),
                    'dr_khaata_no' => $record['p_khaata_no'],
                    'cr_khaata_no' => $record['s_khaata_no'],
                    'currency1' => '',
                    'amount' => '',
                    'currency2' => '',
                    'rate' => '',
                    'opr' => '*',
                    'final_amount' => '',
                    'transfer_date' => date('Y-m-d'),
                    'report' => ''
                    //'report' => 'ENTRY:' . $rows . ' GOODS:' . $goods . ' COUNTRY:' . $record['country'] . ' ALLOT:' . $record['allot'] . ' T.Qty:' . $qtys . ' T.KGs:' . $totals . ' RATE:' . $rate . ' T.AMNT:' . $amounts . $curr . ' EXCH.:' . $curr2
                );
                if ($purchase_pays_id > 0) {
                    $purchase_paysQ = fetch('purchase_pays', array('id' => $purchase_pays_id));
                    if (mysqli_num_rows($purchase_paysQ) > 0) {
                        $pps = mysqli_fetch_assoc($purchase_paysQ);
                        $adv_arr = array(
                            'finish' => array('div_class' => 'border border-danger', 'btn_text' => 'Update', 'btn_class' => 'btn-warning', 'back' => '<a href="purchase-rem?view=1&p_id=' . $purchase_id . '">Back</a>', 'purchase_pays_id' => $purchase_pays_id, 'action' => 'update'),
                            'dr_khaata_no' => $pps['dr_khaata_no'],
                            'cr_khaata_no' => $pps['cr_khaata_no'],
                            'currency1' => $pps['currency1'],
                            'amount' => $pps['amount'],
                            'currency2' => $pps['currency2'],
                            'rate' => $pps['rate'],
                            'opr' => $pps['opr'],
                            'final_amount' => $pps['final_amount'],
                            'transfer_date' => $pps['transfer_date'],
                            'report' => $pps['report']
                        );
                    }
                }
                $rid_delete_array = array(); ?>
                <form method="post" onsubmit="return confirm('Are you sure?');" class="table-form <?php echo $adv_arr['finish']['div_class'] ?>">
                    <?php echo $adv_arr['finish']['back']; ?>
                    <div class="row gx-0">
                        <div class="col-md-2">
                            <div class="input-group position-relative">
                                <label for="khaata_no1" class="text-success">Dr. A/c</label>
                                <input name="dr_khaata_no" id="khaata_no1" required class="form-control bg-transparent"
                                       value="<?php echo $adv_arr['dr_khaata_no']; ?>">
                                <small class="error-response top-0" id="p_response"></small>
                            </div>
                            <input type="hidden" name="dr_khaata_id" id="p_khaata_id">
                        </div>
                        <div class="col-md-2">
                            <div class="input-group position-relative">
                                <label for="khaata_no2" class="text-danger">Cr. A/c</label>
                                <input name="cr_khaata_no" id="khaata_no2" required class="form-control bg-transparent"
                                       value="<?php echo $adv_arr['cr_khaata_no']; ?>">
                                <small class="error-response top-0" id="s_response"></small>
                            </div>
                            <input type="hidden" name="cr_khaata_id" id="s_khaata_id">
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="currency1">Currency</label>
                                <select id="currency1" name="currency1" class="form-select bg-transparent" required>
                                    <option value="" hidden="">Select</option>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $sel_curr = $adv_arr['currency1'] == $crr['name'] ? 'selected' : '';
                                        echo '<option ' . $sel_curr . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="amount">Amount</label>
                                <input type="text" id="amount" name="amount" class="form-control currency"
                                       onkeyup="lastAmount()" required value="<?php echo $adv_arr['amount']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="currency2">Currency</label>
                                <select id="currency2" name="currency2" class="form-select bg-transparent" required>
                                    <option value="" hidden="">Select</option>
                                    <?php $currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $sel_curr2 = $adv_arr['currency2'] == $crr['name'] ? 'selected' : '';
                                        echo '<option ' . $sel_curr2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } ?>
                                </select>
                                <label for="rate">Rate</label>
                                <input type="text" name="rate" class="form-control currency" id="rate" required
                                       onkeyup="lastAmount()" value="<?php echo $adv_arr['rate']; ?>">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="input-group">
                                <label for="opr">Op.</label>
                                <select name="opr" class="form-select" id="opr" required onchange="lastAmount()">
                                    <?php $ops = array('[*]' => '*', '[/]' => '/');
                                    foreach ($ops as $opName => $op) {
                                        $sel_op = $adv_arr['opr'] == $op ? 'selected' : '';
                                        echo '<option ' . $sel_op . ' value="' . $op . '">' . $opName . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="final_amount">F.Amt.</label>
                                <input type="text" name="final_amount" class="form-control" id="final_amount" required
                                       readonly tabindex="-1" value="<?php echo $adv_arr['final_amount']; ?>">

                                <label for="transfer_date">Date</label>
                                <input type="date" class="form-control" id="transfer_date" name="transfer_date" required
                                       value="<?php echo $adv_arr['transfer_date']; ?>">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <label for="report">Report</label>
                                <input placeholder="Report" class="form-control" id="report" name="report" required
                                       value="<?php echo $adv_arr['report']; ?>">
                            </div>
                        </div>
                        <div class="col-md-1 text-end">
                            <button name="tRemSubmit" id="recordSubmit" type="submit"
                                    class="btn <?php echo $adv_arr['finish']['btn_class']; ?> btn-sm  rounded-0"><i
                                    class="fa fa-paper-plane"></i> <?php echo $adv_arr['finish']['btn_text']; ?>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                    <input type="hidden" name="p_type_hidden" value="<?php echo $purchase_type; ?>">
                    <input type="hidden" name="purchase_pays_id_hidden" value="<?php echo $adv_arr['finish']['purchase_pays_id']; ?>">
                    <input type="hidden" name="action" value="<?php echo $adv_arr['finish']['action']; ?>">
                    <?php if ($purchase_pays_id > 0) {
                        $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $purchase_pays_id, 'transfered_from' => 'purchase_rem'));
                        if (mysqli_num_rows($rozQ) > 0) { ?>
                            <table class="table table-sm table-bordered">
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
                                    $rid_delete_array[] = $roz['r_id'];
                                    $dr = $cr = 0; ?>
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
                <?php if ($purchase_pays_id > 0) { ?>
                    <form method="post"
                          onsubmit="return confirm('Are you sure to delete Payment?\nThe record will be delete from Roznamcha too.\nPress OK to Delete');">
                        <input type="hidden" name="r_id_hidden" value="<?php echo htmlspecialchars(json_encode($rid_delete_array)); ?>">
                        <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                        <input type="hidden" name="p_type_hidden" value="<?php echo $purchase_type; ?>">
                        <input type="hidden" name="purchase_pays_id_hidden" value="<?php echo $purchase_pays_id; ?>">
                        <button name="deleteRemPaymentAndRozSubmit" type="submit" class="btn btn-danger btn-sm">Delete This Payment</button>
                    </form>
                <?php } ?>
            </div>
        </div>
        <div class="col-2 order-0 fixed-sidebar table-form">
            <div>
                <div></div>
                <div class="bottom-buttons">
                    <?php if ($record['transfer2'] == 1) { ?>
                        <div class="small px-2 border mb-3">
                            <div class="mb-1 position-relative">
                                <div class="info-div">Dr. A/c</div>
                                <?php $array_acc1 = array(
                                    array('label' => 'A/C#', 'id' => 'p_khaata_no', 'val' => $record['p_khaata_no']),
                                    array('label' => 'NAME', 'id' => 'p_khaata_name', 'val' => $p_khaata['khaata_name']),
                                    array('label' => 'B.', 'id' => 'p_b_name', 'val' => branchName($p_khaata['branch_id'])),
                                    array('label' => 'CAT.', 'id' => 'p_c_name', 'val' => catName($p_khaata['cat_id'])),
                                    array('label' => 'BIZ.', 'id' => 'p_business_name', 'val' => $p_khaata['business_name']),
                                    array('label' => 'COMP.', 'id' => 'p_comp_name', 'val' => $p_khaata['comp_name'])
                                ); ?>
                                <?php foreach ($array_acc1 as $item) {
                                    echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '">' . $item['val'] . '</span><br>';
                                } ?>
                            </div>
                            <div class=" position-relative">
                                <div class="info-div">Cr. A/c</div>
                                <?php $array_acc2 = array(
                                    array('label' => 'A/C#', 'id' => 's_khaata_no', 'val' => $record['s_khaata_no']),
                                    array('label' => 'NAME', 'id' => 's_khaata_name', 'val' => $s_khaata['khaata_name']),
                                    array('label' => 'B.', 'id' => 's_b_name', 'val' => branchName($s_khaata['branch_id'])),
                                    array('label' => 'CAT.', 'id' => 's_c_name', 'val' => catName($s_khaata['cat_id'])),
                                    array('label' => 'BIZ.', 'id' => 's_business_name', 'val' => $s_khaata['business_name']),
                                    array('label' => 'COMP.', 'id' => 's_comp_name', 'val' => $s_khaata['comp_name'])
                                ); ?>
                                <?php foreach ($array_acc2 as $item) {
                                    echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '">' . $item['val'] . '</span><br>';
                                } ?>
                            </div>
                            <a class="btn btn-primary btn-sm w-100 my-1" data-bs-toggle="collapse"
                               href="#collapseExample" role="button" aria-expanded="false"
                               aria-controls="collapseExample">
                                Add Payment
                            </a>
                        </div>
                    <?php } ?>
                    <div class="px-2">
                        <a href="print/purchase-booking?p_id=<?php echo $purchase_id; ?>&action=rem" target="_blank"
                           class="btn btn-success btn-sm w-100">PRINT</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<script>
    function fetchKhaata(inputField, khaataId, responseId, prefix, khaataImageId, recordSubmitId) {
        let khaata_no = $(inputField).val();
        console.log(khaata_no);
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton(recordSubmitId);
                    $(khaataId).val(response.messages['khaata_id']);
                    $(prefix + '_khaata_no').text(khaata_no);
                    $(prefix + '_khaata_name').text(response.messages['khaata_name']);
                    $(prefix + '_b_name').text(response.messages['b_name']);
                    $(prefix + '_c_name').text(response.messages['name']);
                    $(prefix + '_business_name').text(response.messages['business_name']);
                    $(prefix + '_address').text(response.messages['address']);
                    $(prefix + '_comp_name').text(response.messages['comp_name']);
                    var details = {
                        indexes: response.messages['indexes'],
                        vals: response.messages['vals']
                    };
                    $(prefix + '_contacts').html(displayKhaataDetails(details));
                    $(khaataImageId).attr("src", response.messages['image']);
                    $(recordSubmitId).prop('disabled', false);
                    $(responseId).text('');
                }
                if (response.success === false) {
                    disableButton(recordSubmitId);
                    $(responseId).text('INVALID');
                    $(prefix + '_khaata_no').text('---');
                    $(prefix + '_khaata_name').text('---');
                    $(prefix + '_c_name').text('---');
                    $(prefix + '_b_name').text('---');
                    $(prefix + '_comp_name').text('---');
                    $(prefix + '_business_name').text('---');
                    $(prefix + '_address').text('---');
                    $(prefix + '_contacts').text('');
                    $(khaataImageId).attr("src", 'assets/images/logo-placeholder.png');
                    $(khaataId).val(0);
                }
            }
        });
    }

    function displayKhaataDetails(details) {
        var html = ''; // Initialize an empty string to store HTML

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

    disableButton('recordSubmit');
    $(document).on('keyup', "#khaata_no1", function (e) {
        fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");
    });
    fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");
    $(document).on('keyup', "#khaata_no2", function (e) {
        fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");
    });
    fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");

    function lastAmount() {
        let amount = $("#amount").val();
        let rate = $("#rate").val();


        let operator = $('#opr').find(":selected").val();

        let final_amount;
        if (operator === "/") {
            final_amount = Number(amount) / Number(rate);
        } else {
            final_amount = Number(amount) * Number(rate);
        }
        final_amount = final_amount.toFixed(3);
        $("#final_amount").val(final_amount);
        var balance = $("#balance").val();
        if (Number(balance) >= 1) {
            if (Number(balance) <= Number(final_amount)) {
                disableButton('recordSubmit');
            } else {
                enableButton('recordSubmit');
            }
        } else {
            disableButton('recordSubmit');
        }
    }
</script>

