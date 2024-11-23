<?php include("header.php");
$pageURL = 'sells-add';
$buys_id = $buys_sold_id = 0;
$buyArray = $cr_khaata_json = [];
$is_action = false;
$action = $sub_title = $allot_name_sm = $seller_khaata_no = $bardana_name = $marka = $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = $taqseem_name = $taqseem_no = $taqseem_qty = $qeemat_name = $qeemat_raqam = $more_details = '';
$s_date = $payment_date = date('Y-m-d');
$buyer_name = $buyer_mobile = $loading_godam = $jmaa_khaata_no = $bnaam_khaata_no = '';
$currency2 = $rate2 = $opr = $final_amount = '';
$buys_username = $userName;
$buys_branch_name = $branchName;
$total_bill = $final_amount_for_transfer = $jmaa_khaata_id = $bnaam_khaata_id = $soldBardana = $soldtotal_wt = $soldsaaf_wt = $soldqeemat_raqam = 0;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $buys_id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('buys', array('id' => $buys_id));
    $record = mysqli_fetch_assoc($records);
    $balance = buySellBalance($buys_id);
    $buys_username = $record['username'];
    $jmaa_khaata_no = $record['bnaam_khaata_no'];
    $jmaa_khaata_id = $record['bnaam_khaata_id'];

    if (empty($record['cr_khaata_json'])) {
        $dd = mysqli_query($connect, "SELECT SUM(qeemat_raqam) as fa FROM `buys_sold` WHERE buys_id = '$buys_id'");
        $ggg = mysqli_fetch_assoc($dd);
        $total_bill = $final_amount_for_transfer = $ggg['fa'];
    } else {
        $cr_khaata_json = json_decode($record['cr_khaata_json']);
        $total_bill = $cr_khaata_json->total_bill;
        $bnaam_khaata_no = $cr_khaata_json->bnaam_khaata_no;
        $bnaam_khaata_id = $cr_khaata_json->bnaam_khaata_id;
        if (isset($cr_khaata_json->is_qty)) {
            $is_qty = $cr_khaata_json->is_qty == 1 ? 'checked' : '';
        }
        if (isset($cr_khaata_json->currency2)) {
            $currency2 = $cr_khaata_json->currency2;
        }
        $rate2 = $cr_khaata_json->rate2;
        $opr = $cr_khaata_json->opr;
        $final_amount_for_transfer = $cr_khaata_json->final_amount;
    }

    $buys_branch_name = branchName($record['branch_id']);
    $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum, SUM(total_wt) as total_wtSum, SUM(total_empty_wt) as total_empty_wtSum, SUM(saaf_wt) as saaf_wtSum, SUM(qeemat_raqam) as qeemat_raqamSum FROM buys_details WHERE buys_id = '$buys_id'");
    $detailSums = mysqli_fetch_assoc($detailsQ);
    $bardana_qtySum = $detailSums['bardana_qtySum'];
    $sss = fetch('buys_sold', array('buys_id' => $buys_id));
    while ($soldStat = mysqli_fetch_assoc($sss)) {
        $soldBardana += $soldStat['bardana_qty'];
        $soldtotal_wt += $soldStat['total_wt'];
        $soldsaaf_wt += $soldStat['saaf_wt'];
        $soldqeemat_raqam += $soldStat['qeemat_raqam'];
    }
    $buyArray = array(
        /*array('col_name' => 'لوڈنگ تاریخ', 'col_val' => $record['b_date'], 'class' => 'col-2', 'span_id' => '', 'span_class' => ''),*/
        array('col_name' => 'جنس', 'col_val' => $record['jins'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'لاٹ نام', 'col_val' => $record['allot_name'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'لوڈنگ گودام', 'col_val' => $record['loading_godam'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        /*array('col_name' => 'تفصیل', 'col_val' => $record['more_details'], 'class' => 'col-6', 'span_id' => '', 'span_class' => ''),*/
        //array('col_name' => 'باردانہ تعداد', 'col_val' => round($detailSums['bardana_qtySum']), 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'ٹوٹل وزن', 'col_val' => $detailSums['total_wtSum'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        /*array('col_name' => 'ٹوٹل خالی وزن', 'col_val' => round($detailSums['total_empty_wtSum']), 'class' => 'col-2', 'span_id' => '', 'span_class' => ''),*/
        array('col_name' => 'ٹوٹل صاف وزن', 'col_val' => $detailSums['saaf_wtSum'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'ٹوٹل بیلنس', 'col_val' => buyBalance($buys_id)/*$balance + $soldBardana*/, 'class' => 'col-2', 'span_id' => 'balance', 'span_class' => 'text-success'),
        array('col_name' => '', 'col_val' => '', 'class' => 'col-12 text-center border-bottom border-2 mb-3 mt-n2', 'span_id' => '', 'span_class' => 'text-white'),
        array('col_name' => 'فروشی تفصیل', 'col_val' => '', 'class' => 'col ', 'span_id' => '', 'span_class' => 'text-white'),
        array('col_name' => 'کل رقم ', 'col_val' => $soldqeemat_raqam, 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'باردانہ تعداد', 'col_val' => $soldBardana, 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'ٹوٹل وزن', 'col_val' => $soldtotal_wt, 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'ٹوٹل صاف وزن', 'col_val' => $soldsaaf_wt, 'class' => 'col', 'span_id' => '', 'span_class' => ''),
        array('col_name' => 'بقایا بیلنس', 'col_val' => $balance, 'class' => 'col', 'span_id' => 'balance', 'span_class' => 'text-danger'),
    );
    $action = 'add';
    $sub_title = 'فروشی نیا اندراج';
    if (isset($_GET['buys_sold_id']) && is_numeric($_GET['buys_sold_id'])) {
        $action = 'update';
        $sub_title = 'فروشی ریکارڈ کی درستگی';
        $buys_sold_id = mysqli_real_escape_string($connect, $_GET['buys_sold_id']);
        $bsq = fetch('buys_sold', array('id' => $buys_sold_id));
        $buys_sold = mysqli_fetch_assoc($bsq);
        $s_date = $buys_sold['s_date'];
        $allot_name_sm = $buys_sold['allot_name'];
        $buyer_name = $buys_sold['buyer_name'];
        $buyer_mobile = $buys_sold['buyer_mobile'];
        $loading_godam = $buys_sold['loading_godam'];
        $bardana_name = $buys_sold['bardana_name'];
        $marka = $buys_sold['marka'];
        $bardana_qty = $buys_sold['bardana_qty'];
        $per_wt = $buys_sold['per_wt'];
        $total_wt = $buys_sold['total_wt'];
        $empty_wt = $buys_sold['empty_wt'];
        $total_empty_wt = $buys_sold['total_empty_wt'];
        $saaf_wt = $buys_sold['saaf_wt'];
        $taqseem_name = $buys_sold['taqseem_name'];
        $taqseem_no = $buys_sold['taqseem_no'];
        $taqseem_qty = $buys_sold['taqseem_qty'];
        $qeemat_name = $buys_sold['qeemat_name'];
        $qeemat_raqam = $buys_sold['qeemat_raqam'];
        $payment_date = $buys_sold['payment_date'];
        $more_details = $buys_sold['more_details'];
    }
    if (isset($_GET['action'])) {
        $action = mysqli_real_escape_string($connect, $_GET['action']);
        $is_action = true;
    }
} ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-1 mt-n2">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n3">فروشی فارم اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <?php echo backUrl('sells'); ?>
    </div>
</div>
<div class="row gx-1">
    <div class="col-md-10">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <?php if ($buys_id > 0) {
            echo '<input type="hidden" id="buys_id_js" value="' . $buys_id . '">'; ?>
            <div class="card">
                <div class="card-body py-2">
                    <div class="row gx-0 mt-2 justify-content-center">
                        <?php foreach ($buyArray as $arr) {
                            echo '<div class="' . $arr['class'] . '"><p style="line-height: 0" class="urdu"><span class="bold"> ' . $arr['col_name'] . ' </span>';
                            echo '<span class="ms-1 h5 underline urdu-2 ' . $arr['span_class'] . '" id="' . $arr['span_id'] . '">&nbsp;' . $arr['col_val'] . '&nbsp;&nbsp;</span></p></div>';
                        } ?>
                        <?php if (!$is_action) { ?>
                            <div class="col">
                                <p style="line-height: 0" class="urdu">
                                    <span class="bold"> لاٹ باردانہ </span>
                                    <span class="ms-1 h5 underline urdu-2 text-primary" id="bardana_in_allot"></span>
                                </p>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($is_action && $action == "transfer") { ?>
                <div class="card">
                    <h3 class="urdu-2 text-center text-bg-success">فروشی کو ٹرانسفر کریں</h3>
                    <div class="card-body pt-2">
                        <?php $crm_msg = 'کیا آپ واقعی ٹرانسفر کرنا چاہتے ہیں؟';
                        $crm_msg .= '\n';
                        //$crm_msg .= ' ٹوٹل رقم: ' . round($qeemat_raqam); ?>
                        <form method="post" onsubmit="confirm('<?php echo $crm_msg; ?>');">
                            <div class="row gx-0">
                                <div class="col-md-9">
                                    <div class="row gx-0 gy-4 ">
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">جمع&nbsp;کھاتہ&nbsp;نمبر</label>
                                                <input type="text" readonly tabindex="-1" name="jmaa_khaata_no"
                                                       value="<?php echo $jmaa_khaata_no; ?>"
                                                       class="form-control bg-transparent">
                                            </div>
                                            <input type="hidden" id="khaata_id1" name="jmaa_khaata_id"
                                                   value="<?php echo $jmaa_khaata_id; ?>">
                                        </div>
                                        <div class="col-md-4 position-relative">
                                            <div class="input-group">
                                                <label for="bnaam_khaata_no" class="input-group-text urdu">بنام&nbsp;کھاتہ&nbsp;نمبر</label>
                                                <input type="text" id="bnaam_khaata_no" name="bnaam_khaata_no"
                                                       class="form-control bg-transparent" required autofocus
                                                       value="<?php echo $bnaam_khaata_no; ?>">
                                                <small id="response2"
                                                       class="text-danger urdu position-absolute top-0 left-0"></small>
                                            </div>
                                            <input type="hidden" id="khaata_id2" name="bnaam_khaata_id"
                                                   value="<?php echo $bnaam_khaata_id; ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="total" class="input-group-text urdu">رقم</label>
                                                <input type="text" id="total" readonly name="total_bill"
                                                       class="form-control bold" required tabindex="-1"
                                                       value="<?php echo $total_bill; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="input-group-text urdu" for="currency2">کرنسی</label>
                                                <select id="currency2" name="currency2" class="form-select" required>
                                                    <option selected hidden disabled value="">انتخاب</option>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $crr_sel2 = $crr['name'] == $currency2 ? 'selected' : '';
                                                        echo '<option ' . $crr_sel2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label class="input-group-text urdu" for="rate2">ریٹ</label>
                                                <input value="<?php echo $rate2; ?>" id="rate2" name="rate2"
                                                       class="form-control currency" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label class="input-group-text urdu" for="opr">آپریٹر</label>
                                                <select id="opr" name="opr" class="form-select" required>
                                                    <?php $ops = array('ضرب' => '*', 'تقسیم' => '/');
                                                    foreach ($ops as $opName => $op) {
                                                        $op_sel = $opr == $op ? 'selected' : '';
                                                        echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="input-group">
                                                <label class="input-group-text urdu text-danger" for="final_amount">فائنل
                                                    اماؤنٹ</label>
                                                <input value="<?php echo $final_amount; ?>" required
                                                       id="final_amount" name="final_amount" class="form-control"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                                            <button name="transferToRoznamchaSubmit" id="transferToRoznamchaSubmit"
                                                    type="submit" class="btn btn-primary btn-icon-text "><i
                                                        class="btn-icon-prepend" data-feather="check-square"></i>ٹرانسفر
                                                کریں
                                            </button>
                                            <?php echo backUrl2('sells-add?id=' . $buys_id); ?>
                                        </div>
                                        <input type="hidden" name="buys_id_hidden" value="<?php echo $buys_id; ?>">
                                        <input type="hidden" name="allot_hidden"
                                               value="<?php echo $record['allot_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="urdu-2 text-center">
                                            <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                                            <p class="p-1 bold text-primary" id="bm_kh_tafseel"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($cr_khaata_json != '') {
                                $rozQ = fetch('roznamchaas', array('r_type' => 'karobar', 'transfered_from_id' => $buys_id, 'transfered_from' => 'buys_sold'));
                                if (mysqli_num_rows($rozQ) > 0) { ?>
                                    <table class="table table-sm table-bordered mb-0 mt-3">
                                        <thead class="table-primary">
                                        <tr>
                                            <th>سیریل</th>
                                            <th>تاریخ</th>
                                            <th>کھاتہ نمبر</th>
                                            <th>روزنامچہ نمبر</th>
                                            <th>نام</th>
                                            <th>نمبر</th>
                                            <th width="40%">تفصیل</th>
                                            <th>جمع</th>
                                            <th>بنام</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                                            $jmaa_amount = $roz['jmaa_amount'];
                                            //echo $jmaa_amount; ?>
                                            <tr>
                                                <td><?php echo Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial']; ?></td>
                                                <td>
                                                    <?php echo $roz['r_date']; ?>
                                                    <input type="hidden" value="<?php echo $roz['r_id']; ?>"
                                                           name="r_id[]">
                                                </td>
                                                <td><?php echo $roz['khaata_no']; ?></td>
                                                <td><?php echo $roz['roznamcha_no']; ?></td>
                                                <td class="small"><?php echo $roz['r_name']; ?></td>
                                                <td><?php echo $roz['r_no']; ?></td>
                                                <?php $str = "";
                                                if ($roz['jmaa_amount'] == 0) {
                                                    $str = "بنام:- ";
                                                }
                                                if ($roz['bnaam_amount'] == 0) {
                                                    $str = "جمع:- ";
                                                } ?>
                                                <td class="small bold-"><?php echo $str . $roz['details']; ?></td>
                                                <td class="text-success"><?php echo $roz['jmaa_amount']; ?></td>
                                                <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                <?php }
                            } ?>
                        </form>
                    </div>
                </div>
            <?php } else { ?>
                <div class="card">
                    <h3 class="urdu-2 text-center text-bg-primary"><?php echo $sub_title; ?></h3>
                    <div class="card-body pt-2">
                        <form method="post">
                            <div class="row gx-0 gy-3">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="allot_name_sm" class="input-group-text urdu">لاٹ نام</label>
                                        <select id="allot_name_sm" name="allot_name" class="form-select">
                                            <?php $all = mysqli_query($connect, "SELECT DISTINCT allot_name FROM `buys_details` WHERE buys_id = '$buys_id'");
                                            while ($row = mysqli_fetch_assoc($all)) {
                                                $all_sel = $allot_name_sm == $row['allot_name'] ? 'selected' : '';
                                                echo '<option ' . $all_sel . ' value="' . $row['allot_name'] . '">' . $row['allot_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="buyer_name" class="input-group-text urdu">خریدارنام</label>
                                        <input id="buyer_name" name="buyer_name" type="text"
                                               value="<?php echo $buyer_name; ?>"
                                               class="form-control input-urdu">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="buyer_mobile" class="input-group-text urdu">موبائل</label>
                                        <input id="buyer_mobile" name="buyer_mobile" type="text"
                                               value="<?php echo $buyer_mobile; ?>"
                                               class="form-control ">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="loading_godam" class="input-group-text urdu">لوڈنگ گودام
                                            نام</label>
                                        <input type="text" id="loading_godam" name="loading_godam"
                                               class="form-control input-urdu" required
                                               value="<?php echo $loading_godam; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="bardana_name" class="input-group-text urdu">باردانہ نام</label>
                                        <input type="text" id="bardana_name" name="bardana_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $bardana_name; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="input-group">
                                        <label for="marka" class="input-group-text urdu">مارکہ</label>
                                        <input type="text" id="marka" name="marka" class="form-control" required
                                               value="<?php echo $marka; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="bardana_qty" class="input-group-text urdu">باردانہ تعداد</label>
                                        <input type="text" id="bardana_qty" name="bardana_qty"
                                               class="form-control currency" required
                                               value="<?php echo $bardana_qty; ?>">
                                        <label for="per_wt" class="input-group-text urdu">فی وزن</label>
                                        <input type="text" id="per_wt" name="per_wt" class="form-control currency"
                                               required value="<?php echo $per_wt; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="total_wt" class="input-group-text urdu">ٹوٹل وزن</label>
                                        <input type="text" id="total_wt" name="total_wt"
                                               value="<?php echo $total_wt; ?>" class="form-control currency"
                                               readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="empty_wt" class="input-group-text urdu">خالی وزن</label>
                                        <input type="text" id="empty_wt" name="empty_wt"
                                               value="<?php echo $empty_wt; ?>"
                                               class="form-control currency" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="total_empty_wt" class="input-group-text urdu">ٹوٹل خالی
                                            وزن</label>
                                        <input type="text" id="total_empty_wt" name="total_empty_wt" readonly
                                               tabindex="-1" class="form-control currency"
                                               value="<?php echo $total_empty_wt; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="saaf_wt" class="input-group-text urdu ps-0">صاف وزن</label>
                                        <input type="text" id="saaf_wt" name="saaf_wt" readonly tabindex="-1"
                                               class="form-control currency" value="<?php echo $saaf_wt; ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="input-group-text urdu ps-0" for="taqseem_name">تقسیم
                                            نام</label>
                                        <input type="text" id="taqseem_name" name="taqseem_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $taqseem_name; ?>">
                                        <label class="input-group-text urdu ps-0" for="taqseem_no"> نمبر</label>
                                        <input type="text" id="taqseem_no" name="taqseem_no"
                                               value="<?php echo $taqseem_no; ?>"
                                               class="form-control urdu-2 currency" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="input-group-text urdu  ps-0" for="taqseem_qty">ٹوٹل تقسیم
                                            تعداد</label>
                                        <input type="text" id="taqseem_qty" name="taqseem_qty" required
                                               class="form-control" readonly tabindex="-1"
                                               value="<?php echo $taqseem_qty; ?>">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="input-group-text urdu ps-0" for="qeemat_name">قیمت
                                            کانام</label>
                                        <input type="text" id="qeemat_name" name="qeemat_name"
                                               value="<?php echo $qeemat_name; ?>"
                                               class="form-control currency" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="input-group-text urdu" for="qeemat_raqam">رقم</label>
                                        <input type="text" id="qeemat_raqam" name="qeemat_raqam" required
                                               value="<?php echo $qeemat_raqam; ?>"
                                               class="form-control"
                                               readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-2">
                                    <div class="input-group" id="flatpickr-date">
                                        <label for="payment_date" class="input-group-text urdu">ادائیگی
                                            تاریخ</label>
                                        <input type="date" name="payment_date" class="form-control"
                                               id="payment_date" required value="<?php echo $payment_date; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group " id="flatpickr-date">
                                        <label for="more_details" class="input-group-text urdu">مزید
                                            تفصیل</label>
                                        <input type="text" name="more_details" class="form-control input-urdu"
                                               id="more_details" required value="<?php echo $more_details; ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $buys_id; ?>" name="hidden_id">
                            <input type="hidden" value="<?php echo $buys_sold_id; ?>" name="buys_sold_id">
                            <input type="hidden" value="<?php echo $action; ?>" name="action">
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <button type="submit" name="updateSaleSubmit" id="saleSubmit"
                                        class="btn btn-inverse-primary btn-icon-text">
                                    <i class="btn-icon-prepend" data-feather="check-square"></i>فروشی کو
                                    محفوظ کریں
                                </button>
                                <?php if ($buys_sold_id > 0) {
                                    echo backUrl2($pageURL . '?id=' . $buys_id);
                                } ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-0 px-3 py-1">
                        <div>
                            <h6 class="urdu text-primary bold">تفصیل فروشی</h6>
                        </div>
                        <div class="d-flex align-items-center flex-wrap text-nowrap gap-3">
                            <a href="sells-add?id=<?php echo $buys_id; ?>&action=transfer" class="btn btn-dark btn-icon-text pt-0 pb-1 px-2">
                                <i class="btn-icon-prepend" data-feather="share"></i>
                                <span class="ms-2">فروشی کو ٹرانسفرکریں</span>
                            </a>
                            <form action="print/sells-add" method="get" target="_blank">
                                <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>"
                                       type="hidden">
                                <input name="buys_id" value="<?php echo $buys_id; ?>" type="hidden">
                                <button type="submit" class="btn btn-primary btn-icon-text pt-0 pb-1  px-2">
                                    پرنٹ <i class="btn-icon-prepend me-0" data-feather="printer"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered " id="fix-head-table">
                            <thead>
                            <tr>
                                <th class="small">لاٹ نام</th>
                                <th class="small-2">باردانہ نام</th>
                                <th class="small">مارکہ</th>
                                <th class="small-2">باردانہ تعداد</th>
                                <th class="small-2">فی وزن</th>
                                <th class="small-2">ٹوٹل وزن</th>
                                <th class="small-2">صاف وزن</th>
                                <th class="small-2">رقم</th>
                                <th class="small-2">تفصیل</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sales = mysqli_query($connect, "SELECT * FROM `buys_sold` WHERE buys_id = $buys_id ORDER BY qeemat_raqam ASC ");
                            $bardana_qty_bottom = $total_wt_bottom = $saaf_wt_bottom = $qeemat_raqam_bottom = 0;
                            while ($sale = mysqli_fetch_assoc($sales)) { ?>
                                <tr>
                                    <td class="text-nowrap">
                                        <?php echo '<a href="sells-add?id=' . $buys_id . '&buys_sold_id=' . $sale['id'] . '">' . $sale['allot_name'] . '</a>'; ?>
                                    </td>
                                    <td><?php echo $sale['bardana_name']; ?></td>
                                    <td><?php echo $sale['marka']; ?></td>
                                    <td><?php echo $sale['bardana_qty']; ?></td>
                                    <td><?php echo round($sale['per_wt']); ?></td>
                                    <td><?php echo round($sale['total_wt']); ?></td>
                                    <td><?php echo round($sale['saaf_wt']); ?></td>
                                    <td><?php echo round($sale['qeemat_raqam']); ?></td>
                                    <td class="small-2">
                                        <span class="cursor-pointer" data-bs-container="body"
                                              data-bs-toggle="popover" data-bs-placement="top"
                                              data-bs-content="<?php echo $sale['more_details']; ?>">
                                            <?php echo readMore($sale['more_details'], '90'); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php $bardana_qty_bottom += $sale['bardana_qty'];
                                $total_wt_bottom += $sale['total_wt'];
                                $saaf_wt_bottom += $sale['saaf_wt'];
                                $qeemat_raqam_bottom += $sale['qeemat_raqam'];
                            } ?>
                            <tr>
                                <td colspan="3"></td>
                                <td><?php echo $bardana_qty_bottom; ?></td>
                                <td></td>
                                <td><?php echo $total_wt_bottom; ?></td>
                                <td><?php echo $saaf_wt_bottom; ?></td>
                                <td><?php echo $qeemat_raqam_bottom; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="col-md-2">
        <div class="card p-2">
            <div class="row">
                <div class="col-md-12 col-4">
                    <div class="input-group bg-info bg-opacity-10">
                        <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                        <input type="text" id="ser" class="form-control" disabled
                               value="<?php echo $buys_id; ?>">
                    </div>
                </div>
                <div class="col-md-12 col-4">
                    <div class="input-group bg-info bg-opacity-10">
                        <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                        <input type="text" id="userName" class="form-control bg-transparent"
                               required value="<?php echo $buys_username; ?>" readonly tabindex="-1">
                    </div>
                </div>
                <div class="col-md-12 col-4">
                    <div class="input-group bg-info bg-opacity-10">
                        <label for="" class="input-group-text urdu">برانچ کانام</label>
                        <input type="text" name="" readonly tabindex="-1"
                               class="form-control urdu-2 bold bg-transparent" required
                               value="<?php echo $buys_branch_name; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("html, body").animate({scrollTop: $(document).height()}, 1000);
</script>
<?php if (isset($_POST['updateSaleSubmit'])) {
    $post_actions = array('type' => 'danger', 'msg' => 'DB Error');
    $buys_sold_id = mysqli_real_escape_string($connect, $_POST['buys_sold_id']);
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $url = $pageURL . '?id=' . $hidden_id;
    $action2 = mysqli_real_escape_string($connect, $_POST['action']);
    $data = array(
        'allot_name' => mysqli_real_escape_string($connect, $_POST['allot_name']),
        'buyer_name' => mysqli_real_escape_string($connect, $_POST['buyer_name']),
        'buyer_mobile' => mysqli_real_escape_string($connect, $_POST['buyer_mobile']),
        'loading_godam' => mysqli_real_escape_string($connect, $_POST['loading_godam']),
        'bardana_name' => mysqli_real_escape_string($connect, $_POST['bardana_name']),
        'marka' => mysqli_real_escape_string($connect, $_POST['marka']),
        'bardana_qty' => mysqli_real_escape_string($connect, $_POST['bardana_qty']),
        'per_wt' => mysqli_real_escape_string($connect, $_POST['per_wt']),
        'total_wt' => mysqli_real_escape_string($connect, $_POST['total_wt']),
        'empty_wt' => mysqli_real_escape_string($connect, $_POST['empty_wt']),
        'total_empty_wt' => mysqli_real_escape_string($connect, $_POST['total_empty_wt']),
        'saaf_wt' => mysqli_real_escape_string($connect, $_POST['saaf_wt']),
        'taqseem_name' => mysqli_real_escape_string($connect, $_POST['taqseem_name']),
        'taqseem_no' => mysqli_real_escape_string($connect, $_POST['taqseem_no']),
        'taqseem_qty' => mysqli_real_escape_string($connect, $_POST['taqseem_qty']),
        'qeemat_name' => mysqli_real_escape_string($connect, $_POST['qeemat_name']),
        'qeemat_raqam' => mysqli_real_escape_string($connect, $_POST['qeemat_raqam']),
        'payment_date' => mysqli_real_escape_string($connect, $_POST['payment_date']),
        'more_details' => mysqli_real_escape_string($connect, $_POST['more_details']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    if ($action2 == "add") {
        $data['buys_id'] = $hidden_id;
        $data['user_id'] = $userId;
        $data['username'] = $userName;
        $data['branch_id'] = $branchId;
        $data['created_at'] = date('Y-m-d H:i:s');
        $done = insert('buys_sold', $data);
        if ($done) {
            $post_actions = array('type' => 'success', 'msg' => 'نئی فروشی محفوظ ہوگئی۔');
            $insId = $connect->insert_id;
            $url .= "&buys_sold_id=" . $insId;
        }
    } elseif ($action2 == "update") {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $buys_sold_hidden_id = mysqli_real_escape_string($connect, $_POST['buys_sold_id']);
        $done = update('buys_sold', $data, array('id' => $buys_sold_hidden_id));
        $url .= "&buys_sold_id=" . $buys_sold_hidden_id;
        if ($done) {
            $post_actions = array('type' => 'success', 'msg' => 'فروشی محفوظ ہوگئی۔');
        }
    }
    message($post_actions['type'], $url, $post_actions['msg']);
} ?>
<?php if (isset($_POST['transferToRoznamchaSubmit'])) {
    //$amount = mysqli_real_escape_string($connect, $_POST['total_bill']);
    $final_amount_r = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id_hidden']);
    $url = $pageURL . '?id=' . $buys_id . '&action=transfer';
    $type = ' فروشی بل ';
    $transfered_from = 'buys_sold';
    $r_type = 'karobar';
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']);
    $allot_hidden = mysqli_real_escape_string($connect, $_POST['allot_hidden']);
    $details = $type . ' سے ٹرانسفر, ' . ' الاٹ نمبر ' . $allot_hidden;
    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        $buys = fetch('buys', array('id' => $buys_id));
        $data1 = mysqli_fetch_assoc($buys);
        $branch_serial = getBranchSerial($data1['branch_id'], $r_type);
        $details .= ' جنس ' . $data1['jins'] . ' کنٹینر نمبر ' . $data1['container_no'];
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $buys_id,
            'branch_id' => $data1['branch_id'],
            'user_id' => $data1['user_id'],
            'username' => $data1['username'],
            'r_date' => date('Y-m-d'),
            'roznamcha_no' => $allot_hidden,
            'r_name' => $type,
            'r_no' => $allot_hidden,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " الاٹ نمبر " . $allot_hidden;

        $done = false;
        if (isset($_POST['r_id'])) {
            $r_ids = $_POST['r_id'];
            $i = 0;
            $dataArrayUpdate = array();
            foreach ($r_ids as $r_id) {
                ++$i;
                if ($i == 1) {
                    $k_datum = khaataSingle($jmaa_khaata_id);
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $jmaa_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $jmaa_khaata_no;
                    $dataArrayUpdate['jmaa_amount'] = $final_amount_r;
                    $dataArrayUpdate['bnaam_amount'] = 0;
                    $str .= "<span class='badge bg-dark mx-2'> جمع:" . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_datum = khaataSingle($bnaam_khaata_id);
                    $dataArrayUpdate['r_name'] = $type;
                    $dataArrayUpdate['cat_id'] = $k_datum['cat_id'];
                    $dataArrayUpdate['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArrayUpdate['khaata_id'] = $bnaam_khaata_id;
                    $dataArrayUpdate['khaata_no'] = $bnaam_khaata_no;
                    $dataArrayUpdate['jmaa_amount'] = 0;
                    $dataArrayUpdate['bnaam_amount'] = $final_amount_r;
                    $str .= "<span class='badge bg-dark mx-2'> بنام:" . $bnaam_khaata_no . "</span>";
                }
                $done = update('roznamchaas', $dataArrayUpdate, array('r_id' => $r_id));
            }
        } else {
            for ($i = 1; $i <= 2; $i++) {
                if ($i == 1) {
                    $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $jmaa_khaata_id;
                    $dataArray['khaata_no'] = $jmaa_khaata_no;
                    $dataArray['jmaa_amount'] = $final_amount_r;
                    $dataArray['bnaam_amount'] = 0;
                    $str .= "<span class='badge bg-dark mx-2'> جمع:" . $jmaa_khaata_no . "</span>";
                }
                if ($i == 2) {
                    $k_data = fetch('khaata', array('id' => $bnaam_khaata_id));
                    $k_datum = mysqli_fetch_assoc($k_data);
                    $dataArray['branch_serial'] = $branch_serial + 1;
                    $dataArray['cat_id'] = $k_datum['cat_id'];
                    $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                    $dataArray['khaata_id'] = $bnaam_khaata_id;
                    $dataArray['khaata_no'] = $bnaam_khaata_no;
                    $dataArray['bnaam_amount'] = $final_amount_r;
                    $dataArray['jmaa_amount'] = 0;
                    $str .= "<span class='badge bg-dark mx-2'> بنام:" . $bnaam_khaata_no . "</span>";
                }
                $done = insert('roznamchaas', $dataArray);
            }
        }
        if ($done) {
            $preData = array('cr_khaata_json' => json_encode($_POST));
            $tlUpdated = update('buys', $preData, array('id' => $buys_id));
            message('success', $url, ' روزنامچہ میں ٹرانسفر ہوگیا ہے۔ ' . $str);
        } else {
            message('danger', $url, ' روزنامچہ ٹرانسفر نہیں ہو سکا۔');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
} ?>
<script type="text/javascript">
    //$(document).ready(function () {
    //toggleQtyAndRequired();
    finalAmountRoznamcha();
    /*$("#is_qty").change(toggleQtyAndRequired);*/
    $('#opr').on('change', function () {
        finalAmountRoznamcha();
    });
    $('#rate2').on('keyup', function () {
        finalAmountRoznamcha();
    });

    finalAmount();
    $('#bardana_qty,#per_wt,#empty_wt,#taqseem_no,#qeemat_name').on('keyup', function () {
        finalAmount();
    });
    //});

    /*function toggleQtyAndRequired() {
        finalAmountRoznamcha();
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

    function finalAmountRoznamcha() {
        var first_amount = parseFloat($("#total").val()) || 0;
        var final_amount = 0;

        //if ($("#is_qty").prop('checked') == true) {
        var curr_rate = parseFloat($("#rate2").val()) || 0;
        let operator = $('#opr').find(":selected").val();

        if (!isNaN(curr_rate) && curr_rate !== 0 && !isNaN(curr_rate)) {
            final_amount = (operator === '/') ? first_amount / curr_rate : curr_rate * first_amount;
            final_amount = final_amount.toFixed(3);
        }
        //}

        $("#final_amount").val(isFinite(final_amount) ? final_amount : '');

        if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
            disableButton('transferToRoznamchaSubmit');
        } else {
            enableButton('transferToRoznamchaSubmit');
        }
    }

    function finalAmount() {
        var qty_no = parseFloat($("#bardana_qty").val()) || 0;
        var qty_kgs = parseFloat($("#per_wt").val()) || 0;
        var total_kgs = qty_no * qty_kgs;
        $("#total_wt").val(total_kgs);
        var empty_kgs = parseFloat($("#empty_wt").val()) || 0;
        var total_qty_kgs = qty_no * empty_kgs;
        $("#total_empty_wt").val(total_qty_kgs);

        var net_kgs = total_kgs - total_qty_kgs;
        $("#saaf_wt").val(net_kgs);
        //$("#net_kgs").val(net_kgs);

        var weight = parseFloat($("#taqseem_no").val()) || 0;
        //var weight = parseFloat($("#weight").val()) || 0;
        var total = 0;

        if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
            total = net_kgs / weight;
            total = total.toFixed(3);
        }

        $("#taqseem_qty").val(isNaN(total) ? '' : total);
        var rate1 = parseFloat($("#qeemat_name").val()) || 0;
        var amount = 0;

        if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
            amount = total * rate1;
            amount = amount.toFixed(3);
        }

        $("#qeemat_raqam").val(isNaN(amount) ? '' : amount);

        if (amount <= 0 || isNaN(amount) || !isFinite(amount)) {
            disableButton('secondStepSubmit');
        } else {
            enableButton('secondStepSubmit');
        }
    }
</script>
<script type="text/javascript">
    var buys_id_js = $("#buys_id_js").val();
    var allot_name_sm = $('#allot_name_sm').find(":selected").val();
    allotDetails(allot_name_sm, buys_id_js);
    $("#allot_name_sm").change(function () {
        allotDetails($(this).val(), buys_id_js);
    });

    function allotDetails(allot_name, buys_id) {
        if (allot_name != '') {
            $.ajax({
                type: 'POST',
                url: 'ajax/fetch_allot_details.php',
                data: 'allot_name=' + allot_name + '&buys_id=' + buys_id,
                success: function (response) {
                    console.log(response)
                    $('#bardana_in_allot').text(response);
                    /*if (response.trim() !== '' && response.trim() !== '[]') {
                        var responseData = JSON.parse(response);
                        $("#total_kgs_bal_span").text(total_kgs_bal);
                    } else {
                        $('#total_kgs_span').text('');
                    }*/
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    }
</script>
<script>
    fetchKhaataBnaam();
    fetchKhaataJmaa();
    transferToRoznamcha();

    function fetchKhaataBnaam() {
        var khaata_no = $("#bnaam_khaata_no").val();
        var khaata_id2 = $("#khaata_id2");
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    khaata_id2.val(response.messages['khaata_id']);
                    $("#response2").text('');
                    var res = '<span class="urdu mt-1">' + response.messages['khaata_name'] + '</span>'
                        + '<br /><span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>'
                        + '<img src="' + response.messages['image'] + '" class="img-fluid">';
                    $("#bm_kh_tafseel").html(res);
                    transferToRoznamcha();
                }
                if (response.success === false) {
                    $("#response2").text('بنام کھاتہ نمبر');
                    $("#bm_kh_tafseel").text('');
                    khaata_id2.val(0);
                    transferToRoznamcha();
                }
            }
        });
    }

    $(document).on('keyup', "#bnaam_khaata_no", function (e) {
        transferToRoznamcha();
        fetchKhaataBnaam();
    });

    function fetchKhaataJmaa() {
        var khaata_no = $("#jmaa_khaata_no").val();
        var khaata_id1 = $("#khaata_id1");
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    khaata_id1.val(response.messages['khaata_id']);
                    $("#response1").text('');
                    var res = '<span class="urdu mt-1">' + response.messages['khaata_name'] + '</span>'
                        + '<br /><span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>'
                        + '<img src="' + response.messages['image'] + '" class="img-fluid">';
                    $("#jm_kh_tafseel").html(res);
                    transferToRoznamcha();
                }
                if (response.success === false) {
                    $("#response1").text('جمع کھاتہ نمبر');
                    $("#jm_kh_tafseel").text('');
                    khaata_id1.val(0);
                    transferToRoznamcha();
                }
            }
        });
    }

    $(document).on('keyup', "#jmaa_khaata_no", function (e) {
        transferToRoznamcha();
        fetchKhaataJmaa();
    });

    function transferToRoznamcha() {
        var khaata_id2 = $("#khaata_id2").val();
        var khaata_id1 = $("#khaata_id1").val();
        if (khaata_id2 <= 0 || khaata_id1 <= 0) {
            $("#transferToRoznamchaSubmit").prop('disabled', true);
        } else {
            $("#transferToRoznamchaSubmit").prop('disabled', false);
        }
    }
</script>
<script>
    $(function () {
        senderAjax($('#broker_id').val());
        transferToRoznamcha();
    });
    $('#broker_id').change(function () {
        senderAjax($(this).val());
        transferToRoznamcha();
    });

    function senderAjax(broker_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleBroker.php',
            type: 'post',
            data: {
                broker_id: broker_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#broker_name").val(response.messages['name']);
                    $("#broker_email").val(response.messages['email']);
                    $("#broker_mobile").val(response.messages['mobile']);
                    $("#broker_city").val(response.messages['city']);
                    $("#broker_address").val(response.messages['address']);
                    $("#responseBroker").text('');
                }
                if (response.success === false) {
                    $("#responseBroker").text('بروکر');
                }
            }
        });
    }

</script>