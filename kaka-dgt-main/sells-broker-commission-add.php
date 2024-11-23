<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mb-1 mt-n2">
    <div>
        <h4 class="mb-3 mb-md-0">بروکر کمیشن اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <?php echo backUrl('sells-broker-commission'); ?>
    </div>
</div>
<div class="row gx-1 mt-1">
    <?php if (isset($_GET['buys_id']) && is_numeric($_GET['buys_id']) && isset($_GET['buys_sold_id']) && is_numeric($_GET['buys_sold_id'])) {
        $buys_id = mysqli_real_escape_string($connect, $_GET['buys_id']);
        $records = fetch('buys', array('id' => $buys_id));
        $record = mysqli_fetch_assoc($records);
        $buys_sold_id = mysqli_real_escape_string($connect, $_GET['buys_sold_id']);
        $bsq = fetch('buys_sold', array('id' => $buys_sold_id));
        $buys_sold = mysqli_fetch_assoc($bsq);
        //var_dump($detailSums);?>
        <div class="col-md-10">
            <div class="card">
                <div class="card-body p-2">
                    <div class="row gx-0 gy-2">
                        <div class="col-md-1 col-2">
                            <div class="input-group">
                                <label for="bill_no" class="input-group-text urdu">بل نمبر</label>
                                <input type="text" id="bill_no" name="bill_no" class="form-control px-0"
                                       required value="<?php echo $buys_sold['bill_no']; ?>" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="input-group" id="flatpickr-date">
                                <label for="s_date" class="input-group-text urdu">تاریخ</label>
                                <input type="text" name="s_date" class="form-control" id="s_date" required
                                       value="<?php echo $buys_sold['s_date']; ?>" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-2 col-2">
                            <div class="input-group">
                                <label for="jins" class="input-group-text urdu">جنس</label>
                                <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                       required value="<?php echo $buys_sold['jins']; ?>" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-md-2 col-2">
                            <div class="input-group">
                                <label for="allot_name" class="input-group-text urdu">لاٹ نام</label>
                                <input type="text" id="allot_name" name="allot_name"
                                       class="form-control input-urdu" required readonly tabindex="-1"
                                       value="<?php echo $buys_sold['allot_name']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-5 col-3">
                            <div class="input-group">
                                <label for="loading_godam" class="input-group-text urdu">لوڈنگ گودام
                                    نام</label>
                                <input type="text" id="loading_godam" name="loading_godam"
                                       class="form-control input-urdu" required readonly tabindex="-1"
                                       value="<?php echo $buys_sold['loading_godam']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2 position-relative">
                            <div class="input-group">
                                <label for="seller_khaata_no" class="input-group-text urdu">بیچنے والا
                                    اکاؤنٹ</label>
                                <input type="text" id="seller_khaata_no" name="seller_khaata_no"
                                       class="form-control bg-transparent" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['seller_khaata_no']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2 col-3">
                            <div class="input-group">
                                <label for="seller_name" class="input-group-text urdu">نام</label>
                                <input type="text" id="seller_name" name="seller_name"
                                       class="form-control input-urdu" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 col-3">
                            <div class="input-group">
                                <label for="seller_mobile" class="input-group-text urdu">موبائل</label>
                                <input type="text" id="seller_mobile" name="seller_mobile"
                                       class="form-control ltr" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 col-3">
                            <div class="input-group">
                                <label for="seller_comp" class="input-group-text urdu">کمپنی</label>
                                <input type="text" id="seller_comp" name="seller_comp"
                                       class="form-control input-urdu" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-4 col-3">
                            <div class="input-group">
                                <label for="seller_address" class="input-group-text urdu">پتہ</label>
                                <input type="text" id="seller_address" name="seller_address"
                                       class="form-control input-urdu" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 position-relative">
                            <div class="input-group">
                                <label for="broker_name" class="input-group-text urdu">بروکرنام</label>
                                <select id="broker_id" name="broker_id" class="d-none">
                                    <option value="0" selected disabled>انتخاب کریں</option>
                                    <?php $brokers = fetch('brokers');
                                    $broker_id = $buys_sold['broker_id'];
                                    while ($broker = mysqli_fetch_assoc($brokers)) {
                                        $r_selected = $broker['id'] == $broker_id ? 'selected' : '';
                                        echo '<option ' . $r_selected . ' value="' . $broker['id'] . '">' . $broker['name'] . '</option>';
                                    } ?>
                                </select>
                                <input class="form-control urdu-2" type="text" id="broker_name" name="broker_name"
                                       value="<?php echo $buys_sold['broker_id']; ?>" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 col-3">
                            <div class="input-group">
                                <label for="broker_mobile" class="input-group-text urdu">موبائل</label>
                                <input type="text" id="broker_mobile" name="broker_mobile"
                                       class="form-control ltr" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-3 col-3">
                            <div class="input-group">
                                <label for="broker_email" class="input-group-text urdu">ای میل</label>
                                <input type="text" id="broker_email" name="broker_email"
                                       class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-1 col-3">
                            <div class="input-group">
                                <label for="broker_city" class="input-group-text urdu">شہر</label>
                                <input type="text" id="broker_city" name="broker_city"
                                       class="form-control input-urdu" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-4 col-3">
                            <div class="input-group">
                                <label for="broker_address" class="input-group-text urdu">پتہ</label>
                                <input type="text" id="broker_address" name="broker_address"
                                       class="form-control input-urdu" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2">
                            <div class="input-group">
                                <label for="bardana_name" class="input-group-text urdu">باردانہ
                                    نام</label>
                                <input type="text" id="bardana_name" name="bardana_name"
                                       class="form-control urdu-2" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['bardana_name']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2">
                            <div class="input-group">
                                <label for="marka" class="input-group-text urdu">مارکہ</label>
                                <input type="text" id="marka" name="marka" class="form-control"
                                       value="<?php echo $buys_sold['marka']; ?>" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2 position-relative">
                            <div class="input-group">
                                <label for="bardana_qty" class="input-group-text urdu">باردانہ
                                    تعداد</label>
                                <input type="text" id="bardana_qty" name="bardana_qty"
                                       value="<?php echo $buys_sold['bardana_qty']; ?>" readonly tabindex="-1"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2">
                            <div class="input-group">
                                <label for="per_wt" class="input-group-text urdu">فی وزن</label>
                                <input type="text" id="per_wt" name="per_wt"
                                       class="form-control" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['per_wt']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2  col-2">
                            <div class="input-group">
                                <label for="total_wt" class="input-group-text urdu">ٹوٹل وزن</label>
                                <input type="text" id="total_wt" name="total_wt"
                                       value="<?php echo $buys_sold['total_wt']; ?>"
                                       class="form-control currency" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2">
                            <div class="input-group">
                                <label for="empty_wt" class="input-group-text urdu">خالی وزن</label>
                                <input type="text" id="empty_wt" name="empty_wt"
                                       value="<?php echo $buys_sold['empty_wt']; ?>"
                                       class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2">
                            <div class="input-group">
                                <label for="total_empty_wt" class="input-group-text urdu">ٹوٹل خالی
                                    وزن</label>
                                <input type="text" id="total_empty_wt" name="total_empty_wt" readonly
                                       tabindex="-1" class="form-control"
                                       value="<?php echo $buys_sold['total_empty_wt']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2">
                            <div class="input-group">
                                <label for="saaf_wt" class="input-group-text urdu">صاف وزن</label>
                                <input type="text" id="saaf_wt" name="saaf_wt" readonly tabindex="-1"
                                       class="form-control" value="<?php echo $buys_sold['saaf_wt']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-4">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="taqseem_name">تقسیم
                                    نام</label>
                                <input type="text" id="taqseem_name" name="taqseem_name"
                                       class="form-control urdu-2" required
                                       value="<?php echo $buys_sold['taqseem_name']; ?>">
                                <label class="input-group-text urdu ps-0" for="taqseem_no"> نمبر</label>
                                <input type="text" id="taqseem_no" name="taqseem_no"
                                       class="form-control" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['taqseem_no']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-2">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="taqseem_qty">ٹوٹل تقسیم
                                    تعداد</label>
                                <input type="text" id="taqseem_qty" name="taqseem_qty" required
                                       class="form-control" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['taqseem_qty']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-2">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="qeemat_name">قیمت
                                    کانام</label>
                                <input type="text" id="qeemat_name" name="qeemat_name"
                                       class="form-control" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['qeemat_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-2 col-2">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="qeemat_raqam">رقم</label>
                                <input type="text" id="qeemat_raqam" name="qeemat_raqam" required
                                       class="form-control" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['qeemat_raqam']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-2 col-2">
                            <div class="input-group">
                                <label for="payment_date" class="input-group-text urdu">ادائیگی
                                    تاریخ</label>
                                <input type="text" name="payment_date" class="form-control"
                                       id="payment_date" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['payment_date']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-10 col-12">
                            <div class="input-group" id="flatpickr-date">
                                <label for="more_details" class="input-group-text urdu">مزید
                                    تفصیل</label>
                                <input type="text" name="more_details" class="form-control urdu-2"
                                       id="more_details" readonly tabindex="-1"
                                       value="<?php echo $buys_sold['more_details']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card mt-1">
                <div class="card-body p-2">
                    <?php if (isset($_GET['action']) && $_GET['action'] == "transfer") {
                        $buysQQ = fetch('buys', array('id' => $buys_id));
                        $buysData = mysqli_fetch_assoc($buysQQ);
                        $seller_khaataQ = fetch('khaata', array('id' => $buys_sold['seller_khaata_id']));
                        $seller_khaata = mysqli_fetch_assoc($seller_khaataQ); ?>
                        <h3 class="urdu-2 text-center text-bg-success">بروکر کمیشن کو ٹرانسفر کریں</h3>
                        <div class="row gx-0">
                            <div class="col-md-10">
                                <div class="row gx-0 mt-3 justify-content-center">
                                    <?php $buyArray = array(
                                        array('col_name' => 'فیصد', 'col_val' => $buys_sold['bc_percent'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
                                        array('col_name' => 'ٹوٹل کمیشن', 'col_val' => $buys_sold['bc_total'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
                                        array('col_name' => 'کمیشن تاریخ', 'col_val' => $buys_sold['bc_date'], 'class' => 'col', 'span_id' => '', 'span_class' => ''),
                                        array('col_name' => 'کمیشن رپورٹ', 'col_val' => $buys_sold['bc_report'], 'class' => 'col-6', 'span_id' => '', 'span_class' => '')
                                    );
                                    foreach ($buyArray as $arr) {
                                        echo '<div class="' . $arr['class'] . ' "><p class="urdu text-nowrap"><span class="bold">' . $arr['col_name'] . ' : </span>';
                                        echo '<span class="ms-2 ' . $arr['span_class'] . '" id="' . $arr['span_id'] . '">' . $arr['col_val'] . '</span></p></div>';
                                    } ?>
                                </div>
                                <?php $crm_msg = 'کیا آپ واقعی ٹرانسفر کرنا چاہتے ہیں؟';
                                $crm_msg .= '\n';
                                $crm_msg .= 'مین خریداری کا نمبر: ' . $buys_id;
                                $crm_msg .= '\n';
                                $crm_msg .= ' فروشی کا بل نمبر: ' . $buys_sold['bill_no'];
                                $crm_msg .= '\n';
                                $crm_msg .= ' ٹوٹل رقم: ' . round($buys_sold['bc_total']); ?>
                                <form method="post" onsubmit="return confirm('<?php echo $crm_msg; ?>');">
                                    <div class="row gx-0 mt-4">
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">جمع&nbsp;کھاتہ&nbsp;نمبر</label>
                                                <input type="text" required name="jmaa_khaata_no" id="jmaa_khaata_no"
                                                       autofocus
                                                       value="<?php echo $buys_sold['bc_jmaa_khaata_no']; ?>"
                                                       class="form-control bg-transparent"
                                                       onkeyup="fetchKhaataJmaa()">
                                                <small class="text-danger urdu position-absolute top-0 left-0"
                                                       id="response1"></small>
                                            </div>
                                            <input type="hidden" id="khaata_id1" name="jmaa_khaata_id"
                                                   value="<?php echo $buys_sold['bc_jmaa_khaata_id']; ?>">
                                        </div>
                                        <div class="col-md-3 position-relative">
                                            <div class="input-group">
                                                <label for="bnaam_khaata_no" class="input-group-text urdu">بنام&nbsp;کھاتہ&nbsp;نمبر</label>
                                                <input type="text" id="bnaam_khaata_no" name="bnaam_khaata_no"
                                                       class="form-control bg-transparent" readonly tabindex="-1"
                                                       value="<?php echo $buysData['bnaam_khaata_no']; ?>">

                                            </div>
                                            <input type="hidden" id="khaata_id2" name="bnaam_khaata_id"
                                                   value="<?php echo $buysData['bnaam_khaata_id']; ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="total" class="input-group-text urdu">رقم</label>
                                                <input type="text" id="total" readonly name="total_bill"
                                                       class="form-control bold" required tabindex="-1"
                                                       value="<?php echo $buys_sold['bc_total']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3 text-center">
                                            <?php if (empty($buys_sold['bc_jmaa_khaata_no'])) {
                                                echo '<button name="transferToRoznamchaSubmit" id="transferToRoznamchaSubmit" type="submit" class="w--100 btn btn-primary btn-icon-text "><i class="btn-icon-prepend" data-feather="check-square"></i>ٹرانسفر کریں</button>';
                                            } else {
                                                echo '<span class="text-danger urdu pt-3 bold">بروکر کمیشن، کاروبار روزنامچہ میں ٹرانسفر ہوچکا ہے</span>';
                                            } ?>
                                            <span class="text-danger bold urdu d-block" id="totalBillMsg"></span>
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <a href="sells-broker-commission-add?buys_id=<?php echo $buys_id; ?>&buys_sold_id=<?php echo $buys_sold_id; ?>"
                                               class="btn btn-inverse-dark btn-icon-text float--end"><i
                                                        class="btn-icon-prepend" data-feather="arrow-left"></i>واپس
                                            </a>
                                        </div>
                                        <input type="hidden" name="buys_id_hidden" value="<?php echo $buys_id; ?>">
                                        <input type="hidden" name="buys_sold_id_hidden"
                                               value="<?php echo $buys_sold_id; ?>">
                                        <input type="hidden" name="bill_no_hidden"
                                               value="<?php echo $buys_sold['bill_no']; ?>">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <div class="card">
                                    <div class="urdu-2 text-center">
                                        <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                                        <p class="p-1 bold text-primary" id="jm_kh_tafseel"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <form method="post">
                            <div class="row gy-3 gx-0">
                                <div class="col-md-1 col-2">
                                    <div class="input-group">
                                        <label for="bc_percent" class="input-group-text urdu">فیصد</label>
                                        <input type="text" autofocus id="bc_percent" name="bc_percent"
                                               value="<?php echo $buys_sold['bc_percent']; ?>"
                                               class="form-control currency" required onkeyup="commission()">
                                    </div>
                                </div>
                                <input type="hidden" id="hidden_raqam"
                                       value="<?php echo $buys_sold['qeemat_raqam']; ?>">
                                <script>
                                    function commission() {
                                        var bc_percent = Number($("#bc_percent").val());
                                        var hidden_raqam = Number($("#hidden_raqam").val());
                                        var ans = (bc_percent / 100) * hidden_raqam;
                                        ans = ans.toFixed(2);
                                        $("#bc_total").val(ans);
                                    }
                                </script>
                                <div class="col-md-2 col-2">
                                    <div class="input-group">
                                        <label for="bc_total" class="input-group-text urdu">ٹوٹل</label>
                                        <input type="text" id="bc_total" name="bc_total"
                                               class="form-control currency"
                                               value="<?php echo $buys_sold['bc_total']; ?>"
                                               required readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-md-2 col-3">
                                    <div class="input-group" id="flatpickr-date">
                                        <label for="bc_date" class="input-group-text urdu">کمیشن تاریخ</label>
                                        <input type="text" name="bc_date" class="form-control" id="bc_date" required
                                               data-input
                                               value="<?php echo empty($buys_sold['bc_date']) ? date('Y-m-d') : $buys_sold['bc_date']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="input-group">
                                        <label for="bc_report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" name="bc_report" class="form-control input-urdu"
                                               id="bc_report" required
                                               value="<?php echo $buys_sold['bc_report']; ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $buys_sold_id; ?>" name="buys_sold_id_hidden">
                            <input type="hidden" value="<?php echo $buys_id; ?>" name="buys_id_hidden">
                            <div class="mt-4">
                                <button type="submit" name="brokerCommissionSubmit_" id="brokerCommissionSubmit"
                                        class="btn btn-success btn-icon-text d-print-none">
                                    <i class="btn-icon-prepend" data-feather="check-square"></i>کمیشن محفوظ کریں
                                </button>
                                <div class="float-end">
                                    <?php if ($buys_sold['bc_total']) { ?>
                                        <div class="float-end">
                                            <a href="print/sells-broker-commission?buys_id=<?php echo $buys_id; ?>&buys_sold_id=<?php echo $buys_sold_id; ?>&secret=<?php echo base64_encode('powered-by-upsol') ?>"
                                               class="btn btn-primary btn-icon-text">
                                                <i class="btn-icon-prepend" data-feather="printer"></i> پرنٹ
                                            </a>
                                            <a href="sells-broker-commission-add?buys_id=<?php echo $buys_id; ?>&buys_sold_id=<?php echo $buys_sold_id; ?>&action=transfer"
                                               class="btn btn-dark btn-icon-text"><i class="btn-icon-prepend "
                                                                                     data-feather="share"></i>
                                                ٹرانسفر
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
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
                                   required
                                   value="<?php echo $record['username']; ?>" readonly tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-12 col-4">
                        <div class="input-group bg-info bg-opacity-10">
                            <label for="" class="input-group-text urdu">برانچ کانام</label>
                            <input type="text" name="" readonly tabindex="-1"
                                   class="form-control urdu-2 bold bg-transparent"
                                   required
                                   value="<?php echo getTableDataByIdAndColName('branches', $record['branch_id'], 'b_name'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else {
        echo '<script>window.location.href="sells-broker-commission";</script>';
    } ?>
</div>
<?php include("footer.php"); ?>
<script>
    $("html, body").animate({scrollTop: $(document).height()}, 1000);
</script>
<script>
    $(document).on('keyup', "#seller_khaata_no", function (e) {
        transferToRoznamcha();
        fetchKhaata();
    });
    function fetchKhaata() {
        var khaata_no = $("#seller_khaata_no").val();
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
                    $("#seller_name").val(response.messages['khaata_name']);
                    $("#seller_mobile").val(response.messages['mobile']);
                    $("#seller_address").val(response.messages['address']);
                    $("#seller_comp").val(response.messages['comp_name']);
                    transferToRoznamcha();
                }
                if (response.success === false) {
                    $("#response2").text('کھاتہ نمبر');
                    $("#bm_kh_tafseel").text('');
                    khaata_id2.val(0);
                    transferToRoznamcha();
                }
            }
        });
    }
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
<script>
    transferToRoznamcha();
    fetchKhaata();
    fetchKhaataJmaa();
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
        var khaata_id2 = $("#khaata_id1").val();
        if (khaata_id2 <= 0 || khaata_id2 == '') {
            $("#transferToRoznamchaSubmit").prop('disabled', true);
            //msg = 'بنام کھاتہ درست نہیں۔';
        } else {
            msg = '';
            $("#transferToRoznamchaSubmit").prop('disabled', false);
        }
        //totalBillMsg.text(msg);
    }
</script>
<?php if (isset($_POST['brokerCommissionSubmit_'])) {
    $buys_sold_id_hidden = mysqli_real_escape_string($connect, $_POST['buys_sold_id_hidden']);
    $buys_id_hidden = mysqli_real_escape_string($connect, $_POST['buys_id_hidden']);
    $bc_json = array('user_id' => $userId, 'username' => $userName, 'branch_id' => $branchId, 'created_at' => date('Y-m-d H:i:s'));
    $bc_data = array(
        'bc_date' => mysqli_real_escape_string($connect, $_POST['bc_date']),
        'bc_percent' => mysqli_real_escape_string($connect, $_POST['bc_percent']),
        'bc_total' => mysqli_real_escape_string($connect, $_POST['bc_total']),
        'bc_report' => mysqli_real_escape_string($connect, $_POST['bc_report']),
        'bc_json' => json_encode($bc_json)
    );
    $bc_updated = update('buys_sold', $bc_data, array('id' => $buys_sold_id_hidden, 'buys_id' => $buys_id_hidden));
    $bc_url = 'sells-broker-commission-add?buys_id=' . $buys_id_hidden . '&buys_sold_id=' . $buys_sold_id_hidden;
    if ($bc_updated) {
        message('success', $bc_url, 'بروکر کمیشن محفوظ ہو گئی۔');
    } else {
        message('danger', $bc_url, 'ڈیٹا بیس پرابلم۔');
    }
} ?>
<?php if (isset($_POST['transferToRoznamchaSubmit'])) {
    $amount = mysqli_real_escape_string($connect, $_POST['total_bill']);
    $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id_hidden']);
    $buys_sold_id_hidden = mysqli_real_escape_string($connect, $_POST['buys_sold_id_hidden']);
    $bill_no = mysqli_real_escape_string($connect, $_POST['bill_no_hidden']);
    $url = 'sells-broker-commission-add?buys_id=' . $buys_id . '&buys_sold_id=' . $buys_sold_id_hidden . '&action=transfer';
    $type = ' بروکر کمیشن ';
    $transfered_from = 'broker_commission';
    $r_type = 'karobar';
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']);
    $details = $type . ' سے ٹرانسفر, ' . ' بل نمبر ' . $bill_no;

    if ($bill_no && $amount && $jmaa_khaata_id && $bnaam_khaata_id) {
        $buys_sold_q = fetch('buys_sold', array('id' => $buys_sold_id_hidden));
        $data = mysqli_fetch_assoc($buys_sold_q);
        //$json_sold = json_decode($data['json_data']);
        $serial = fetch('roznamchaas', array('branch_id' => $data['branch_id'], 'r_type' => $r_type));
        $branch_serial = mysqli_num_rows($serial);
        $branch_serial = $branch_serial + 1;
        /*details for roznamcha*/
        $buys = fetch('buys', array('id' => $buys_id));
        //$data1 = mysqli_fetch_assoc($buys);
        $details .= ' جنس ' . $data['jins'] . ' لاٹ نام ' . $data['allot_name'] . ' بروکرنام ' . $data['broker_name'];
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $buys_sold_id_hidden,
            'branch_id' => $data['branch_id'],
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'r_date' => date('Y-m-d'),
            'roznamcha_no' => $bill_no,
            'r_name' => $type,
            'r_no' => $bill_no,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " بل نمبر " . $bill_no;
        $done = false;
        for ($i = 1; $i <= 2; $i++) {
            if ($i == 1) {
                $k_data = fetch('khaata', array('id' => $jmaa_khaata_id));
                $k_datum = mysqli_fetch_assoc($k_data);
                $dataArray['branch_serial'] = $branch_serial;
                $dataArray['cat_id'] = $k_datum['cat_id'];
                $dataArray['khaata_branch_id'] = $k_datum['branch_id'];
                $dataArray['khaata_id'] = $jmaa_khaata_id;
                $dataArray['khaata_no'] = $jmaa_khaata_no;
                $dataArray['jmaa_amount'] = $amount;
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
                $dataArray['bnaam_amount'] = $amount;
                $dataArray['jmaa_amount'] = 0;
                $str .= "<span class='badge bg-dark mx-2'> بنام:" . $bnaam_khaata_no . "</span>";
            }
            $done = insert('roznamchaas', $dataArray);
        }
        if ($done) {
            $preData = array(
                'bc_jmaa_khaata_no' => $jmaa_khaata_no,
                'bc_jmaa_khaata_id' => $jmaa_khaata_id,
                'bc_bnaam_khaata_no' => $bnaam_khaata_no,
                'bc_bnaam_khaata_id' => $bnaam_khaata_id
            );
            $tlUpdated = update('buys_sold', $preData, array('id' => $buys_sold_id_hidden));
            message('success', $url, ' بروکر کمیشن کاروبار روزنامچہ میں ٹرانسفر ہوگیا ہے۔ ' . $str);
        } else {
            message('danger', $url, ' روزنامچہ ٹرانسفر نہیں ہو سکا۔');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
} ?>
