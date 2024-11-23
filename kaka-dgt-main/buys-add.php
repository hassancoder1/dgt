<?php include("header.php");
$pageURL = 'buys-add';
$id = $buys_details_id = $balance = $bnaam_khaata_id = $jmaa_khaata_id = 0;
$b_date = $jins = $allot_name = $loading_city = $bail_no = $bnaam_khaata_no = $jmaa_khaata_no = $more_details = $container_no = $empty_godam = $loading_godam = $owner_name = '';
/*bd*/
$payment_date = date('Y-m-d');
$is_extra_exp = $final_amount_for_transfer = $total_bill = 0;
$action = $sub_title = $is_qty = $allot_name_sm = $bardana_name = $marka = $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = $taqseem_name = $taqseem_no = $taqseem_qty = $qeemat_name = $qeemat_raqam =
$currency2 = $rate2 = $opr = $final_amount = $yes_extra_exp = $no_extra_exp = $more_details2 = '';
$buys_sr_no = getAutoIncrement('buys');
$buys_username = $userName;
$buys_branch_name = $branchName;
$is_action = false;
$dr_khaata_json = [];
if (isset($_GET['id'])) {
    $buys_sr_no = $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('buys', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $balance = buySellBalance($id);
    $buys_username = $record['username'];
    $buys_branch_name = getTableDataByIdAndColName('branches', $record['branch_id'], 'b_name');
    $b_date = $record['b_date'];
    $jins = $record['jins'];
    $allot_name = $record['allot_name'];
    $loading_city = $record['loading_city'];
    $bail_no = $record['bail_no'];

    $is_extra_exp = $record['is_extra_exp'];
    $yes_extra_exp = $is_extra_exp == 1 ? 'checked' : '';
    $no_extra_exp = $is_extra_exp == 0 ? 'checked' : '';

    if (empty($record['dr_khaata_json'])) {
        $dd = mysqli_query($connect, "SELECT SUM(qeemat_raqam) as fa FROM `buys_details` WHERE buys_id = '$id'");
        $ggg = mysqli_fetch_assoc($dd);
        $total_bill = $final_amount_for_transfer = $ggg['fa'];
    } else {
        $dr_khaata_json = json_decode($record['dr_khaata_json']);
        $total_bill = $dr_khaata_json->total_bill;
        $jmaa_khaata_no = $dr_khaata_json->jmaa_khaata_no;
        $jmaa_khaata_id = $dr_khaata_json->jmaa_khaata_id;
        if (isset($dr_khaata_json->is_qty)) {
            $is_qty = $dr_khaata_json->is_qty == 1 ? 'checked' : '';
        }
        if (isset($dr_khaata_json->currency2)) {
            $currency2 = $dr_khaata_json->currency2;
        }
        $rate2 = $dr_khaata_json->rate2;
        $opr = $dr_khaata_json->opr;
        $final_amount_for_transfer = $dr_khaata_json->final_amount;
    }
    $bnaam_khaata_no = $record['bnaam_khaata_no'];
    $bnaam_khaata_id = $record['bnaam_khaata_id'];
    $more_details = $record['more_details'];
    $container_no = $record['container_no'];
    $empty_godam = $record['empty_godam'];
    $loading_godam = $record['loading_godam'];
    $owner_name = $record['owner_name'];
    $action = 'add';
    $sub_title = 'خریداری نیا اندراج';
    if (isset($_GET['bd_id']) && is_numeric($_GET['bd_id'])) {
        $action = 'update';
        $sub_title = 'خریداری ریکارڈ کی درستگی';
        $buys_details_id = mysqli_real_escape_string($connect, $_GET['bd_id']);
        $buys_details_q = fetch('buys_details', array('id' => $buys_details_id));
        $buys_details = mysqli_fetch_assoc($buys_details_q);
        $allot_name_sm = $buys_details['allot_name'];

        $bardana_name = $buys_details['bardana_name'];
        $marka = $buys_details['marka'];
        $bardana_qty = $buys_details['bardana_qty'];
        $per_wt = $buys_details['per_wt'];
        $total_wt = $buys_details['total_wt'];
        $empty_wt = $buys_details['empty_wt'];
        $total_empty_wt = $buys_details['total_empty_wt'];
        $saaf_wt = $buys_details['saaf_wt'];
        $taqseem_name = $buys_details['taqseem_name'];
        $taqseem_no = $buys_details['taqseem_no'];
        $taqseem_qty = $buys_details['taqseem_qty'];
        $qeemat_name = $buys_details['qeemat_name'];
        $qeemat_raqam = $buys_details['qeemat_raqam'];
        $payment_date = $buys_details['payment_date'];

        $more_details2 = $buys_details['more_details'];
    }
    if (isset($_GET['action'])) {
        $action = mysqli_real_escape_string($connect, $_GET['action']);
        $is_action = true;
    }
}
?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n2 mb-1">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n3">خریداری فارم اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="buys"
           class="btn btn-dark btn-icon-text pt-0 pb-1">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>
    </div>
</div>
<div class="row gx-1">
    <div class="col-md-10">
        <div class="card mb-1">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card-body pb-2 pt-0 px-2">
                <div class="row gx-0 mt-2 justify-content-center">
                    <form method="post">
                        <div class="row gx-0 gy-3">
                            <div class="col-md-3 col-12">
                                <div class="input-group" id="flatpickr-date">
                                    <label for="b_date" class="input-group-text urdu">تاریخ خرید</label>
                                    <input type="text" name="b_date" class="form-control" id="b_date" required
                                           data-input value="<?php echo $b_date; ?>">
                                    <label for="jins" class="input-group-text urdu">جنس</label>
                                    <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                           required value="<?php echo $jins; ?>">
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="input-group">
                                    <label for="allot_name" class="input-group-text urdu">لاٹ نام</label>
                                    <input type="text" id="allot_name" name="allot_name" class="form-control"
                                           required value="<?php echo $allot_name; ?>">
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="input-group">
                                    <label for="loading_city" class="input-group-text urdu">خرید شہر</label>
                                    <input type="text" id="loading_city" name="loading_city"
                                           class="form-control input-urdu" required
                                           value="<?php echo $loading_city; ?>">
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="input-group">
                                    <label for="bail_no" class="input-group-text urdu">بل نمبر</label>
                                    <input type="text" id="bail_no" name="bail_no" class="form-control" required
                                           value="<?php echo $bail_no; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-6 position-relative">
                                <div class="input-group">
                                    <label for="bnaam_khaata_no" class="input-group-text urdu">بنام
                                        اکاؤنٹ</label>
                                    <input type="text" id="bnaam_khaata_no" name="bnaam_khaata_no"
                                           class="form-control bg-transparent" required
                                           onchange="transferToRoznamcha()"
                                           value="<?php echo $bnaam_khaata_no; ?>">
                                    <small id="response2"
                                           class="text-danger urdu position-absolute top-0 left-0"></small>
                                </div>
                                <input type="hidden" id="khaata_id2" name="bnaam_khaata_id"
                                       value="<?php echo $bnaam_khaata_id; ?>">
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="container_no" class="input-group-text urdu">کنٹینر نمبر</label>
                                    <input type="text" id="container_no" name="container_no" class="form-control"
                                           required value="<?php echo $container_no; ?>">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group">
                                    <label for="loading_godam" class="input-group-text urdu">لوڈنگ گودام</label>
                                    <input type="text" id="loading_godam" name="loading_godam"
                                           class="form-control input-urdu" required
                                           value="<?php echo $loading_godam; ?>">
                                    <label for="empty_godam" class="input-group-text urdu">خالی کرنے
                                        گودام</label>
                                    <input type="text" id="empty_godam" name="empty_godam"
                                           class="form-control input-urdu" required
                                           value="<?php echo $empty_godam; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label for="owner_name" class="input-group-text urdu">خریدار نام</label>
                                    <input type="text" id="owner_name" name="owner_name"
                                           class="form-control input-urdu" required
                                           value="<?php echo $owner_name; ?>">
                                </div>
                            </div>
                            <div class="col-md-9 col-12">
                                <div class="input-group">
                                    <label for="more_details" class="input-group-text urdu">مزید تفصیل</label>
                                    <input type="text" name="more_details" class="form-control input-urdu"
                                           id="more_details" required
                                           value="<?php echo $more_details; ?>">
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="input-group">
                                    <label for="buy_balance" class="input-group-text urdu">بیلنس</label>
                                    <input type="text" class="form-control" id="buy_balance" readonly
                                           value="<?php echo $balance; ?>">
                                </div>
                            </div>
                            <div class="col-md-1 col-6">
                                <input type="hidden" value="<?php echo $id; ?>" name="hidden_id">
                                <button type="submit" name="firstStepSubmit" id="firstStepSubmit"
                                        class="btn btn-dark btn-icon-text float-end py-1">
                                    <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                    محفوظ
                                </button>
                            </div>
                        </div>
                    </form>
                    <?php /*$buyArray = array(
                        array('col_name' => 'تاریخ خرید', 'col_val' => $record['b_date'], 'class' => 'col-6 col-md-2', 'span_id' => '', 'span_class' => ''),
                        array('col_name' => 'جنس', 'col_val' => $record['jins'], 'class' => 'col-6 col-md-2', 'span_id' => '', 'span_class' => ''),
                        array('col_name' => 'لاٹ نام', 'col_val' => $record['allot_name'], 'class' => 'col-6 col-md-2', 'span_id' => '', 'span_class' => ''),
                        array('col_name' => 'خرید شہر', 'col_val' => $record['loading_city'], 'class' => 'col-6 col-md-2', 'span_id' => '', 'span_class' => ''),
                        array('col_name' => 'بل نمبر', 'col_val' => $record['bail_no'], 'class' => 'col-6 col-md-2', 'span_id' => '', 'span_class' => ''),
                        array('col_name' => 'بنام اکاؤنٹ', 'col_val' => $record['bnaam_khaata_no'], 'class' => 'col-6 col-md-2', 'span_id' => '', 'span_class' => ''),
                        array('col_name' => '', 'col_val' => '', 'class' => 'col-12 text-center border-bottom border-2 mb-3 mt--n2', 'span_id' => '', 'span_class' => 'text-white'),
                        array('col_name' => 'مزید تفصیل', 'col_val' => $record['more_details'], 'class' => 'col-12 col-md-8 ', 'span_id' => '', 'span_class' => ''),
                        array('col_name' => 'بیلنس', 'col_val' => $balance, 'class' => 'col-6 col-md-2', 'span_id' => 'balance', 'span_class' => 'text-danger'),
                    );
                    foreach ($buyArray as $arr) {
                        echo '<div class="' . $arr['class'] . '"><p style="line-height: 0" class="urdu"><span class="bold"> ' . $arr['col_name'] . ' </span>';
                        echo '<span class="ms-1 h5 underline urdu-2 ' . $arr['span_class'] . '" id="' . $arr['span_id'] . '">&nbsp;' . $arr['col_val'] . '&nbsp;&nbsp;</span></p></div>';
                    }*/ ?>
                </div>
            </div>
        </div>
        <?php if ($id > 0) {
            if ($is_action && $action == "transfer") { ?>
                <div class="card">
                    <h3 class="urdu-2 text-center text-bg-success">خریداری کو ٹرانسفر کریں</h3>
                    <div class="card-body pt-2">
                        <?php $crm_msg = 'کیا آپ واقعی ٹرانسفر کرنا چاہتے ہیں؟';
                        $crm_msg .= '\n';
                        $crm_msg .= 'مین خریداری کا نمبر: ' . $id;
                        $crm_msg .= '\n';
                        $crm_msg .= ' خریداری کا بل نمبر: ' . $bail_no;
                        $crm_msg .= '\n';
                        $crm_msg .= ' ٹوٹل رقم: ' . $final_amount_for_transfer; ?>
                        <form method="post" onsubmit="return confirm('<?php echo $crm_msg; ?>');">
                            <div class="row gx-0">
                                <div class="col-md-9">
                                    <div class="row gx-0 gy-4 ">
                                        <div class="col-md-4 position-relative">
                                            <div class="input-group">
                                                <label for="jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                                    نمبر</label>
                                                <input type="text" id="jmaa_khaata_no" name="jmaa_khaata_no"
                                                       autofocus value="<?php echo $jmaa_khaata_no; ?>"
                                                       class="form-control bg-transparent" required>
                                            </div>
                                            <small id="response1"
                                                   class="text-danger urdu position-absolute top-0 left-0"></small>
                                            <input type="hidden" id="khaata_id1" name="jmaa_khaata_id"
                                                   value="<?php echo $jmaa_khaata_id; ?>">
                                        </div>
                                        <div class="col-md-4 position-relative">
                                            <div class="input-group">
                                                <label for="bnaam_khaata_no" class="input-group-text urdu">بنام
                                                    کھاتہ
                                                    نمبر</label>
                                                <input type="text" id="bnaam_khaata_no" name="bnaam_khaata_no"
                                                       class="form-control bg-transparent" required readonly
                                                       tabindex="-1" value="<?php echo $bnaam_khaata_no; ?>">
                                                <small id="response2"
                                                       class="text-danger urdu position-absolute top-0 left-0"></small>
                                            </div>
                                            <input type="hidden" id="khaata_id22" name="bnaam_khaata_id"
                                                   value="<?php echo $bnaam_khaata_id ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="total" class="input-group-text urdu">رقم</label>
                                                <input type="text" id="total" readonly name="total_bill"
                                                       class="form-control bold" required tabindex="-1"
                                                       value="<?php echo $total_bill; ?>">
                                            </div>
                                        </div>
                                        <!--<div class="col-md-1">
                                            <div class="form-check mt-md-1">
                                                <input type="checkbox" class="form-check-input" id="is_qty"
                                                       name="is_qty"
                                                       value="1" <?php /*echo $is_qty; */ ?>>
                                                <label class="form-check-label" for="is_qty">تعداد</label>
                                            </div>
                                        </div>-->
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
                                        <div class="col-lg-6">
                                            <div class="input-group">
                                                <label for="is_extra_exp" class="input-group-text urdu me-3 mt-n2">کیا
                                                    اس
                                                    خریداری میں اضافی خرچہ ہے؟</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="is_extra_exp"
                                                           id="yes" value="1" <?php echo $yes_extra_exp; ?>>
                                                    <label class="form-check-label mt-n4" for="yes">ہاں</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="is_extra_exp"
                                                           id="no"
                                                           value="0" <?php echo $no_extra_exp; ?>>
                                                    <label class="form-check-label mt-n4" for="no">نہیں</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-flex justify-content-between align-items-center">
                                            <button name="transferToRoznamchaSubmit" id="transferToRoznamchaSubmit"
                                                    type="submit" class="btn btn-primary btn-icon-text "><i
                                                        class="btn-icon-prepend" data-feather="check-square"></i>ٹرانسفر
                                                کریں
                                            </button>
                                            <?php echo backUrl2('buys-add?id=' . $id); ?>
                                        </div>
                                        <input type="hidden" name="buys_id_hidden" value="<?php echo $id; ?>">
                                        <input type="hidden" name="bill_no_hidden" value="<?php echo $bail_no; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="urdu-2 text-center">
                                            <h5 class="bg-success bg-opacity-25 p-2">جمع کھاتہ نام</h5>
                                            <p class="p-1 bold text-primary" id="jm_kh_tafseel"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($dr_khaata_json != '') {
                                $rozQ = fetch('roznamchaas', array('r_type' => 'karobar', 'transfered_from_id' => $id, 'transfered_from' => 'buys_details'));
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
                            <div class="row gx-0 gy-4">
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="allot_name" class="input-group-text urdu">لاٹ نام</label>
                                        <input type="text" id="allot_name" name="allot_name" class="form-control"
                                               required value="<?php echo $allot_name_sm; ?>">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="bardana_name" class="input-group-text urdu">باردانہ نام</label>
                                        <input type="text" id="bardana_name" name="bardana_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $bardana_name; ?>">
                                        <label for="marka" class="input-group-text urdu">مارکہ</label>
                                        <input type="text" id="marka" name="marka" class="form-control" required
                                               value="<?php echo $marka; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="bardana_qty" class="input-group-text urdu">باردانہ تعداد</label>
                                        <input type="text" id="bardana_qty" name="bardana_qty"
                                               class="form-control currency" required
                                               value="<?php echo $bardana_qty; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
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
                                <div class="col-lg-2">
                                    <div class="input-group" id="flatpickr-date">
                                        <label for="payment_date" class="input-group-text urdu">ادائیگی
                                            تاریخ</label>
                                        <input type="text" name="payment_date" class="form-control"
                                               id="payment_date" required data-input
                                               value="<?php echo $payment_date; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <label for="more_details2" class="input-group-text urdu">مزید تفصیل</label>
                                        <input type="text" name="more_details" class="form-control input-urdu"
                                               id="more_details2" required
                                               value="<?php echo $more_details2; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-auto">
                                    <input type="hidden" value="<?php echo $id; ?>" name="buys_hidden_id">
                                    <input type="hidden" value="<?php echo $buys_details_id; ?>"
                                           name="buys_details_hidden_id">
                                    <input type="hidden" value="<?php echo $action; ?>" name="action">
                                    <button type="submit" name="secondStepSubmit" id="secondStepSubmit"
                                            class="btn btn-inverse-primary btn-icon-text btn-sm-">
                                        <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                        خریداری کو محفوظ کریں
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card shadow mt-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-0 px-3 py-1">
                        <div>
                            <h6 class="urdu bold">تفصیل خریداری</h6>
                        </div>
                        <div class="d-flex align-items-center flex-wrap text-nowrap gap-3">
                            <a href="buys-add?id=<?php echo $id; ?>&action=transfer" class="btn btn-dark btn-icon-text pt-0 pb-1 px-2">
                                <i class="btn-icon-prepend" data-feather="share"></i>
                                <span class="ms-2">خریداری کو ٹرانسفرکریں</span>
                            </a>
                            <form action="print/buys-add" method="get" target="_blank">
                                <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>"
                                       type="hidden">
                                <input name="buys_id" value="<?php echo $id; ?>" type="hidden">
                                <button type="submit" class="btn btn-primary btn-icon-text pt-0 pb-1 px-2">
                                    پرنٹ <i class="btn-icon-prepend me-0" data-feather="printer"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="fix-head-table">
                            <thead>
                            <tr class="text-nowrap">
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
                            <?php $buys_details = fetch('buys_details', array('buys_id' => $id));
                            $bardana_qty_bottom = $total_wt_bottom = $saaf_wt_bottom = $qeemat_raqam_bottom = 0;
                            while ($details = mysqli_fetch_assoc($buys_details)) { ?>
                                <tr>
                                    <td class="text-nowrap">
                                        <div class="d-flex justify-content-between">
                                            <div><?php echo '<a href="buys-add?id=' . $id . '&bd_id=' . $details['id'] . '">' . $details['allot_name'] . '</a>'; ?></div>
                                            <div>
                                                <a target="_blank"
                                                   href="print/buys-details?buys_id=<?php echo $id; ?>&bd_id=<?php echo $details['id']; ?>&secret=<?php echo base64_encode('powered-by-upsol') ?>">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo $details['bardana_name']; ?></td>
                                    <td><?php echo $details['marka']; ?></td>
                                    <td><?php echo $details['bardana_qty']; ?></td>
                                    <td><?php echo round($details['per_wt']); ?></td>
                                    <td><?php echo round($details['total_wt']); ?></td>
                                    <td><?php echo round($details['saaf_wt']); ?></td>
                                    <td><?php echo round($details['qeemat_raqam']); ?></td>
                                    <td class="small-2">
                                        <span class="cursor-pointer" data-bs-container="body"
                                              data-bs-toggle="popover" data-bs-placement="top"
                                              data-bs-content="<?php echo $details['more_details']; ?>">
                                            <?php echo readMore($details['more_details'], '90'); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php $bardana_qty_bottom += $details['bardana_qty'];
                                $total_wt_bottom += $details['total_wt'];
                                $saaf_wt_bottom += $details['saaf_wt'];
                                $qeemat_raqam_bottom += $details['qeemat_raqam'];
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
                <div class="col-12">
                    <div class="input-group bg-info bg-opacity-10">
                        <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                        <input type="text" id="ser" class="form-control" disabled
                               value="<?php echo $buys_sr_no; ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-group bg-info bg-opacity-10">
                        <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                        <input type="text" id="userName" class="form-control bg-transparent"
                               required value="<?php echo $buys_username; ?>" readonly tabindex="-1">
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-group bg-info bg-opacity-10">
                        <label for="" class="input-group-text urdu">برانچ کانام</label>
                        <input type="text" name="" readonly tabindex="-1"
                               class="form-control urdu-2 bold bg-transparent"
                               required value="<?php echo $buys_branch_name; ?>">
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($_GET['id'])) {
            if (isset($_GET['action'])) {
                echo '<a href="buys-add?id=' . $id . '" class=" btn btn-dark d-print-none w-100 mt-1"><i class="fa fa-arrow-circle-left"></i>   واپس خریداری تفصیل</a>';
            } else {
                echo '<a href="buys-add?id=' . $id . '&action=add" class=" btn btn-primary d-print-none w-100 mt-1">اندراج خریداری</a>';
            }
        } ?>
        <div class="card mt-1">
            <div class="urdu-2 text-center">
                <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                <p class="p-1 bold text-primary" id="bm_kh_tafseel"><?php //echo $bm_kh_tafseel; ?></p>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['firstStepSubmit'])) {
    $url = "buys-add";
    $data = array(
        'b_date' => date('Y-m-d'),
        'jins' => mysqli_real_escape_string($connect, $_POST['jins']),
        'allot_name' => mysqli_real_escape_string($connect, $_POST['allot_name']),
        'loading_city' => mysqli_real_escape_string($connect, $_POST['loading_city']),
        'bail_no' => mysqli_real_escape_string($connect, $_POST['bail_no']),
        'bnaam_khaata_no' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']),
        'bnaam_khaata_id' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']),
        'container_no' => mysqli_real_escape_string($connect, $_POST['container_no']),
        'empty_godam' => mysqli_real_escape_string($connect, $_POST['empty_godam']),
        'loading_godam' => mysqli_real_escape_string($connect, $_POST['loading_godam']),
        'owner_name' => mysqli_real_escape_string($connect, $_POST['owner_name']),
        'more_details' => mysqli_real_escape_string($connect, $_POST['more_details'])
    );
    if (isset($_POST['hidden_id']) && $_POST['hidden_id'] > 0) {
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('buys', $data, array('id' => $hidden_id));
        $idd = $hidden_id;
        $msg = 'خریداری فارم تبدیل ہوگیا';
    } else {
        $data['user_id'] = $userId;
        $data['username'] = $userName;
        $data['branch_id'] = $branchId;
        $data['created_at'] = date('Y-m-d H:i:s');
        $done = insert('buys', $data);
        $insertId = $connect->insert_id;
        $idd = $insertId;
        $msg = 'بنام کھاتہ کے ساتھ خریداری فارم محفوظ ہوگیا';
    }
    if ($done) {
        $url .= '?id=' . $idd;
        message('success', $url, $msg);
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['secondStepSubmit'])) {
    $buys_hidden_id = mysqli_real_escape_string($connect, $_POST['buys_hidden_id']);
    $url = "buys-add?id=" . $buys_hidden_id;
    $data = array(
        'buys_id' => $buys_hidden_id,
        'bd_date' => date('Y-m-d'),
        'allot_name' => mysqli_real_escape_string($connect, $_POST['allot_name']),
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
        'more_details' => mysqli_real_escape_string($connect, $_POST['more_details'])
    );
    $action2 = mysqli_real_escape_string($connect, $_POST['action']);
    if ($action2 == "add") {
        $data['user_id'] = $userId;
        $data['username'] = $userName;
        $data['branch_id'] = $branchId;
        $data['created_at'] = date('Y-m-d H:i:s');
        $done = insert('buys_details', $data);
        if ($done) {
            $insId = $connect->insert_id;
            $url .= "&bd_id=" . $insId;
        }
        $msg = 'نئی خریداری محفوظ ہوگئی۔';
    } elseif ($action2 == "update") {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $buys_details_hidden_id = mysqli_real_escape_string($connect, $_POST['buys_details_hidden_id']);
        $done = update('buys_details', $data, array('id' => $buys_details_hidden_id));
        $msg = ' خریداری میں تبدیلی ہوگئی۔';
        $url .= "&bd_id=" . $buys_details_hidden_id;
    } else {
        message('danger', $url, 'پرابلم');
    }
    if ($done) {
        $url .= '&action=update';
        message('success', $url, $msg);
    } else {
        message('danger', $url, $msg);
    }
} ?>
<?php if (isset($_POST['transferToRoznamchaSubmit'])) {
    //$amount = mysqli_real_escape_string($connect, $_POST['total_bill']);
    $final_amount_r = mysqli_real_escape_string($connect, $_POST['final_amount']);
    $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id_hidden']);
    $url = 'buys-add?id=' . $buys_id . '&action=transfer';
    $type = ' خریداری بل ';
    $transfered_from = 'buys_details';
    $r_type = 'karobar';
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']);
    $bill_no = mysqli_real_escape_string($connect, $_POST['bill_no_hidden']);
    $details = $type . ' سے ٹرانسفر, ' . ' بل نمبر ' . $bill_no;

    if ($jmaa_khaata_id > 0 && $bnaam_khaata_id > 0) {
        /*details for roznamcha*/
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
            'roznamcha_no' => $bill_no,
            'r_name' => $type,
            'r_no' => $bill_no,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " بل نمبر " . $bill_no;

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
            $preData = array(
                'is_extra_exp' => mysqli_real_escape_string($connect, $_POST['is_extra_exp']),
                'dr_khaata_json' => json_encode($_POST)
            );
            $tlUpdated = update('buys', $preData, array('id' => $buys_id));
            message('success', $url, ' روزنامچہ میں ٹرانسفر ہوگیا ہے۔ ' . $str);
        } else {
            message('danger', $url, ' روزنامچہ ٹرانسفر نہیں ہو سکا۔');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
} ?>
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
        var msg = '';
        var khaata_id2 = $("#khaata_id2").val();
        var khaata_id1 = $("#khaata_id1").val();
        if (khaata_id2 <= 0 || khaata_id1 <= 0) {
            if (khaata_id2 <= 0) {
                $("#firstStepSubmit").prop('disabled', true);
                msg = 'بنام کھاتہ درست نہیں۔';
            }
            if (khaata_id1 <= 0) {
                $("#recordSubmit").prop('disabled', true);
                $("#transferToRoznamchaSubmit").prop('disabled', true);
                msg = 'جمع کھاتہ درست نہیں۔';
            }
        } else {
            msg = '';
            $("#firstStepSubmit").prop('disabled', false);
            $("#transferToRoznamchaSubmit").prop('disabled', false);
        }
        //$("#totalBillMsg").text(msg);
    }
</script>
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