<?php include("header.php"); ?>
<?php if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['type'])
    && $_GET['type'] == 'beopari_summary_ee'
) {
    $getType = mysqli_real_escape_string($connect, $_GET["type"]);
    $urlArray = array('beopari_summary_ee' => array('path' => 'imp-beopari-summary', 'title' => ' بیوپاری سمری اضافی خرچہ ',
        'type' => ' اضافی خرچہ ', 'transfered_from' => 'beopari_summary_ee', 'khaata_' => 'khaata_bs_ee'));
    $page = $urlArray[$_GET["type"]];
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('imp_truck_loadings', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    if (empty($record[$page['khaata_']])) {
        $jmaa_khaata_no = $bnaam_khaata_no = $jmaaName = $bnaamName = $jmaa_khaata_id = $bnaam_khaata_id = "";
    } else {
        $khaataJson = json_decode($record[$page['khaata_']]);
        $jmaa_khaata_no = $khaataJson->jmaa_khaata_no;
        $jmaa_khaata_id = $khaataJson->jmaa_khaata_id;
        $bnaam_khaata_no = $khaataJson->bnaam_khaata_no;
        $bnaam_khaata_id = $khaataJson->bnaam_khaata_id;
        $jmaaName = getTableDataByIdAndColName('khaata', $jmaa_khaata_id, 'khaata_name');
        $bnaamName = getTableDataByIdAndColName('khaata', $bnaam_khaata_id, 'khaata_name');
    }
    if (!empty($record['sender_receiver'])) {
        $sender_receiver = json_decode($record['sender_receiver']);
        $names = array(
            'sender_name' => $sender_receiver->sender_name,
            'sender_address' => $sender_receiver->sender_address,
            'sender_mobile' => $sender_receiver->sender_mobile,
            'sender_wa' => $sender_receiver->sender_wa,
            'receiver_name' => $sender_receiver->receiver_name,
            'receiver_address' => $sender_receiver->receiver_address,
            'receiver_mobile' => $sender_receiver->receiver_mobile,
            'receiver_wa' => $sender_receiver->receiver_wa
        );
    } else {
        $names = array(
            'sender_name' => '',
            'sender_address' => '',
            'sender_mobile' => '',
            'sender_wa' => '',
            'receiver_name' => '',
            'receiver_address' => '',
            'receiver_mobile' => '',
            'receiver_wa' => ''
        );
    } ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n1">
        <div>
            <h4 class="mb-3 mb-md-0 mt-n2"><?php echo $page['title']; ?></h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl($page['path']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="p-2">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="row gx-0 gy-2">
                        <div class="col-3">
                            <div class="input-group">
                                <label for="ser" class="input-group-text urdu">لوڈنگ سیریل</label>
                                <input type="text" id="ser" class="form-control" disabled
                                       value="<?php echo $id; ?>">
                                <label for="today"
                                       class="input-group-text input-group-addon bg-transparent urdu">لوڈنگ
                                    تاریخ</label>
                                <input type="text" id="today" class="form-control bg-transparent border-primary"
                                       placeholder="تاریخ" value="<?php echo $record['loading_date']; ?>"
                                       disabled>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="owner_name" class="input-group-text urdu">مالک نام</label>
                                <input type="text" id="owner_name" name="owner_name"
                                       class="form-control input-urdu" disabled
                                       value="<?php echo $record['owner_name']; ?>">
                                <label for="jins" class="input-group-text urdu">جنس</label>
                                <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                       disabled value="<?php echo $record['jins']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                <input type="text" id="truck_no" name="truck_no" class="form-control" required
                                       value="<?php echo $record['truck_no']; ?>" disabled>
                                <label for="truck_name" class="input-group-text urdu">ٹرک نام</label>
                                <input type="text" id="truck_name" name="truck_name" tabindex="-1"
                                       class="form-control urdu-2 bold" disabled
                                       value="<?php echo $record['truck_name']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                <input type="text" id="driver_name" name="" disabled
                                       class="form-control urdu-2 bold" required readonly
                                       value="<?php echo $record['driver_name']; ?>">
                                <label for="driver_mobile" class="input-group-text urdu">موبائل</label>
                                <input type="text" id="driver_mobile" name="driver_mobile" tabindex="-1"
                                       class="form-control ltr small-2" disabled
                                       value="<?php echo $record['driver_mobile']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label class="input-group-text urdu">لوڈنگ کرنے گودام</label>
                                <?php $loadings = fetch('godam_loading_forms', array('id' => $record['godam_loading_id']));
                                $loading = mysqli_fetch_assoc($loadings); ?>
                                <input type="text" value="<?php echo $loading['name'] ?>"
                                       class="form-control bold urdu-2" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" class="form-control ltr bold" disabled
                                       value="<?php echo $loading['mobile1'] ?>">
                                <label class="input-group-text urdu">منشی کانام</label>
                                <input type="text" class="form-control urdu-2 bold" disabled
                                       value="<?php echo $loading['munshi'] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label class="input-group-text urdu">پتہ</label>
                                <input type="text" disabled value="<?php echo $loading['address'] ?>"
                                       class="form-control urdu-2 bold">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label class="input-group-text urdu">خالی کرنے گودام</label>
                                <?php $empties = fetch('godam_empty_forms', array('id' => $record['godam_empty_id']));
                                $empty = mysqli_fetch_assoc($empties); ?>
                                <input type="text" value="<?php echo $empty['name'] ?>"
                                       class="form-control bold urdu-2" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" class="form-control ltr bold" disabled
                                       value="<?php echo $empty['mobile1'] ?>">
                                <label class="input-group-text urdu">منشی کانام</label>
                                <input type="text" class="form-control urdu-2 bold" disabled
                                       value="<?php echo $empty['munshi'] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label class="input-group-text urdu">پتہ</label>
                                <input type="text" disabled value="<?php echo $empty['address'] ?>"
                                       class="form-control urdu-2 bold">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">کنسائینی نام</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['consignee_name']; ?>">
                                <label for="sender_city" class="input-group-text urdu">بھیجنے والا شہر</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['sender_city']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <label class="input-group-text urdu">رپورٹ</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['report']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="card mt-2 pb-2 px-2">
                <div class="row gx-0 gy-2">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="sender_name" class="input-group-text urdu">مال بھیجنےوالا</label>
                            <input type="text" id="sender_name" name="sender_name"
                                   class="form-control urdu-2 bold" readonly tabindex="-1"
                                   value="<?php /*echo $names['sender_name']; */ ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="sender_address" class="input-group-text urdu">پتہ</label>
                            <input type="text" id="sender_address" name="sender_address"
                                   class="form-control urdu-2 bold" readonly tabindex="-1"
                                   value="<?php /*echo $names['sender_address']; */ ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="sender_mobile" class="input-group-text urdu">موبائل نمبر</label>
                            <input type="text" id="sender_mobile" name="sender_mobile"
                                   class="form-control ltr bold" readonly tabindex="-1"
                                   data-inputmask-alias="(+99) 999-9999999"
                                   value="<?php /*echo $names['sender_mobile']; */ ?>">
                            <label for="sender_wa" class="input-group-text urdu">واٹس ایپ نمبر</label>
                            <input type="text" id="sender_wa" name="sender_wa"
                                   class="form-control urdu-2 ltr bold" readonly tabindex="-1"
                                   data-inputmask-alias="(+99) 999-9999999"
                                   value="<?php /*echo $names['sender_wa']; */ ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="receiver_name" class="input-group-text urdu">مال وصول
                                کرنےوالا</label>
                            <input type="text" id="receiver_name" name="receiver_name"
                                   class="form-control urdu-2 bold" readonly tabindex="-1"
                                   value="<?php /*echo $names['receiver_name']; */ ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="receiver_address" class="input-group-text urdu">پتہ</label>
                            <input type="text" id="receiver_address" name="receiver_address"
                                   class="form-control urdu-2 bold" readonly tabindex="-1"
                                   value="<?php /*echo $names['receiver_address']; */ ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="receiver_mobile" class="input-group-text urdu">موبائل نمبر</label>
                            <input type="text" id="receiver_mobile" name="receiver_mobile"
                                   class="form-control urdu-2 bold ltr" readonly tabindex="-1"
                                   data-inputmask-alias="(+99) 999-9999999"
                                   value="<?php /*echo $names['receiver_mobile']; */ ?>">
                            <label for="receiver_wa" class="input-group-text urdu">واٹس ایپ نمبر</label>
                            <input type="text" id="receiver_wa" name="receiver_wa"
                                   class="form-control urdu-2 bold ltr" readonly tabindex="-1"
                                   data-inputmask-alias="(+99) 999-9999999"
                                   value="<?php /*echo $names['receiver_wa']; */ ?>">
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="row gx-2">
                <div class="col-md-10">
                    <!--<div class="card mt-2 border-top-0">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th> سیریل نمبر</th>
                                    <th>جنس نام</th>
                                    <th>باردن نام</th>
                                    <th>باردن تعداد</th>
                                    <th>فی وزن</th>
                                    <th>ٹوٹل وزن</th>
                                    <th>خالی باردن وزن</th>
                                    <th>ٹوٹل خالی وزن</th>
                                    <th>صاف وزن</th>
                                </tr>
                                </thead>
                                <tbody id="records_table">
                                <?php /*$maals = fetch('imp_truck_maals', array('imp_tl_id' => $id));
                                $x = 1;
                                $godam_receive_date = null;
                                $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = 0;
                                while ($maal = mysqli_fetch_assoc($maals)) {
                                    $json = json_decode($maal['json_data']); */ ?>
                                    <tr class="row-py-0">
                                        <td><?php /*echo $x; */ ?></td>
                                        <td><?php /*echo $json->jins_name; */ ?></td>
                                        <td><?php /*echo $json->bardana_name; */ ?></td>
                                        <td><?php /*echo $json->bardana_qty; */ ?></td>
                                        <td><?php /*echo $json->per_wt; */ ?></td>
                                        <td><?php /*echo $json->total_wt; */ ?></td>
                                        <td class="ltr"><?php /*echo $json->empty_wt; */ ?></td>
                                        <td><?php /*echo $json->total_empty_wt; */ ?></td>
                                        <td class="ltr"><?php /*echo $json->saaf_wt; */ ?></td>
                                    </tr>
                                    <?php /*$x++;
                                    $bardana_qty += $json->bardana_qty;
                                    $per_wt += $json->per_wt;
                                    $total_wt += $json->total_wt;
                                    $empty_wt += $json->empty_wt;
                                    $total_empty_wt += $json->total_empty_wt;
                                    $saaf_wt += $json->saaf_wt;
                                } */ ?>
                                <tr class="row-py-0 bg-info bg-opacity-25 bold">
                                    <td><?php /*echo $x - 1; */ ?></td>
                                    <td colspan="2"></td>
                                    <td><?php /*echo $bardana_qty; */ ?></td>
                                    <td><?php /*echo $per_wt; */ ?></td>
                                    <td><?php /*echo $total_wt; */ ?></td>
                                    <td><?php /*echo $empty_wt; */ ?></td>
                                    <td><?php /*echo $total_empty_wt; */ ?></td>
                                    <td class="ltr"><?php /*echo $saaf_wt; */ ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>-->
                    <div class="card mt-2 border-top-0">
                        <div class="card-body">
                            <form method="post">
                                <table class="table table-bordered" id="productTable">
                                    <thead>
                                    <tr>
                                        <th width="5%">
                                            <i class="fa fa-plus-square border ms-0 px-2 py-1 mb-0 bg-light cursor-pointer"
                                               data-bs-toggle="tooltip"
                                               data-bs-title="اضافہ لائن (/)" onclick="addRow()" id="addRowBtn"
                                               data-loading-text="Loading..."></i>
                                        </th>
                                        <th width="20%">خرچہ نام</th>
                                        <th width="55%">تفصیل</th>
                                        <th width="20%">رقم</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $arrayNumber = $total_bill = $exp_names = 0;
                                    $exps = isImpExtraExpenseAdded($id, $getType);
                                    if ($exps['success']) {
                                        $json2 = json_decode($exps['output']['json_data']);
                                        $total_bill = $json2->total_bill;
                                        $exp_names = $json2->exp_names;
                                        $exp_details = $json2->exp_details;
                                        $exp_values = $json2->exp_values;
                                        $imp_truck_maals2_id = $exps['output']['id'];
                                        $x = 1;
                                        foreach ($exp_names as $index => $value) { ?>
                                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                <td class="border-bottom border-success border-end-0 border-start-0">
                                                    <i id="removeProductRowBtn"
                                                       class="fa fa-remove border px-2 py-1 ms-2 mt-1 text-danger cursor-pointer"
                                                       data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)"
                                                       tabindex="-1"
                                                       onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           name="exp_names[]" <?php echo empty($exp_names[$index]) ? 'autofocus' : ''; ?>
                                                           placeholder="خرچہ <?php echo $x; ?>" required
                                                           value="<?php echo $exp_names[$index]; ?>"
                                                           class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_details[]"
                                                           placeholder="تفصیل <?php echo $x; ?>" required
                                                           value="<?php echo $exp_details[$index]; ?>"
                                                           class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_values[]" required
                                                           placeholder="رقم <?php echo $x; ?>"
                                                           onkeypress="transferToRoznamcha()"
                                                           value="<?php echo $exp_values[$index]; ?>"
                                                           onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off"
                                                           class="form-control currency form-control-sm bold"
                                                           id="exp_value<?php echo $x; ?>">
                                                </td>
                                            </tr>
                                            <?php $arrayNumber++;
                                            $x++;
                                        }
                                        echo '<input type="hidden" value="update" name="action">';
                                        echo '<input type="hidden" value="' . $imp_truck_maals2_id . '" name="imp_truck_maals2_id">';
                                    } else {
                                        for ($x = 1; $x < 3; $x++) { ?>
                                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                <td class="border-bottom border-success border-end-0 border-start-0">
                                                    <i id="removeProductRowBtn"
                                                       class="fa fa-remove border px-2 py-1 ms-2 mt-1 text-danger cursor-pointer"
                                                       data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)"
                                                       tabindex="-1"
                                                       onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_names[]" autofocus
                                                           placeholder="خرچہ <?php echo $x; ?>" required
                                                           class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_details[]"
                                                           placeholder="تفصیل <?php echo $x; ?>" required
                                                           class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_values[]" required
                                                           placeholder="رقم <?php echo $x; ?>"
                                                           onkeypress="transferToRoznamcha()"
                                                           onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off"
                                                           class="form-control currency form-control-sm bold"
                                                           id="exp_value<?php echo $x; ?>">
                                                </td>
                                            </tr>
                                            <?php $arrayNumber++;
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                                <input type="hidden" name="url" value="<?php echo $page['path']; ?>">
                                <input type="hidden" name="form_name" value="<?php echo $getType; ?>">
                                <input type="hidden" name="imp_tl_id_hidden" value="<?php echo $id; ?>">
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <?php if (empty($record[$page['khaata_']])) { //agr roznamcha m transfer na hoa ho. ?>
                                            <button name="saveExpense" id="saveExpense" type="submit"
                                                    class="btn btn-dark btn-icon-text">
                                                <i class="btn-icon-prepend" data-feather="check-square"></i>خرچہ محفوظ
                                            </button>
                                        <?php } ?>
                                        <div class="float-end">
                                            <div class="input-group">
                                                <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                                <input type="text" id="total" readonly name="total_bill"
                                                       value="<?php echo $total_bill; ?>"
                                                       class="form-control bold" required min="1" tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <?php $formStr = 'رقم: ' . $total_bill . '\n';
                            $formStr .= 'محفوظ کرنے سے پہلے خرچوں کو اچھی طرح چیک کے لیں' . '\n';
                            $formStr .= 'اضافی خرچہ کو کاروبار روزنامچہ میں ٹرانسفر کرنے کے لیے OK کا بٹن دبائیں۔';
                            if ($exps['success']) { ?>
                                <form method="post" onsubmit="return confirm('<?php echo $formStr; ?>');">
                                    <div class="row gx-1 mt-4 justify-content-center">
                                        <div class="col-lg-2">
                                            <div class="input-group">
                                                <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                                    نمبر</label>
                                                <input type="text" id="afg_jmaa_khaata_no" name="afg_jmaa_khaata_no"
                                                       class="form-control bg-transparent" required
                                                       onchange="transferToRoznamcha()" autofocus
                                                       value="<?php echo $jmaa_khaata_no; ?>">
                                                <small id="response1"
                                                       class="top-0 left-0 text-danger urdu position-absolute"></small>
                                            </div>
                                            <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id"
                                                   value="<?php echo $jmaa_khaata_id; ?>">
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="input-group">
                                                <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام
                                                    کھاتہ
                                                    نمبر</label>
                                                <input type="text" id="afg_bnaam_khaata_no" name="afg_bnaam_khaata_no"
                                                       class="form-control bg-transparent" required
                                                       onchange="transferToRoznamcha()"
                                                       value="<?php echo $bnaam_khaata_no; ?>">
                                                <small id="response2"
                                                       class="text-danger top-0 left-0 urdu position-absolute"></small>
                                            </div>
                                            <input type="hidden" id="khaata_id2" name="afg_bnaam_khaata_id"
                                                   value="<?php echo $bnaam_khaata_id; ?>">
                                        </div>
                                        <input type="hidden" value="<?php echo $total_bill; ?>" name="total_bill">
                                        <input type="hidden" value="<?php echo $imp_truck_maals2_id; ?>"
                                               name="imp_truck_maals2_id">
                                        <input type="hidden" name="transfered_from"
                                               value="<?php echo $page["transfered_from"]; ?>">
                                        <input type="hidden" name="type" value="<?php echo $page["type"]; ?>">
                                        <input type="hidden" name="url" value="<?php echo $page['path']; ?>">
                                        <input type="hidden" name="tl_id_hidden" value="<?php echo $id; ?>">
                                        <div class="col-lg-3">
                                            <?php if (empty($record[$page['khaata_']])) { ?>
                                                <button name="recordSubmitFinal" id="recordSubmitFinal" type="submit"
                                                        class="btn btn-primary btn-icon-text" data-bs-toggle="tooltip">
                                                    <i class="btn-icon-prepend" data-feather="check-square"></i>روزنامچہ
                                                    میں ٹرانسفر
                                                </button>
                                                <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                            <?php } else {
                                                echo '<span class="mt-3 urdu h4 text-danger">روزنامچہ میں ٹرانسفر ہو گیا۔</span>';
                                            } ?>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card mt-2">
                        <div class="urdu-2">
                            <h5 class="bg-success bg-opacity-25 p-2">جمع کھاتہ نام</h5>
                            <p class="p-1 bold text-primary" id="jm_kh_tafseel"><?php echo $jmaaName; ?></p>
                            <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                            <p class="p-1 bold text-primary" id="bm_kh_tafseel"><?php echo $bnaamName; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    echo '<script>window.location.href="imp-beopari-summary";</script>';
} ?>
<?php include("footer.php"); ?>
<?php if (isset($_POST['saveExpense'])) {
    $imp_tl_id = mysqli_real_escape_string($connect, $_POST['imp_tl_id_hidden']);
    $form_name = mysqli_real_escape_string($connect, $_POST['form_name']);
    $expUrl = 'imp-beopari-summary-expenses?id=' . $imp_tl_id . '&type=' . $form_name;
    $utExpensesArray = array(
        'imp_tl_id' => $imp_tl_id,
        'is_ttr' => 0,
        'maal_id' => 0,
        'form_name' => $form_name,
        'json_data' => json_encode($_POST, JSON_UNESCAPED_UNICODE)
    );
    $strr = '';
    if (isset($_POST['imp_truck_maals2_id']) && isset($_POST['action'])) {
        $imp_truck_maals2_id = $_POST['imp_truck_maals2_id'];
        $utExpensesArray['updated_at'] = date('Y-m-d H:i:s');
        $utExpensesArray['updated_by'] = $userId;
        $strr = 'خرچہ میں تبدیلی محفوظ ہو گئی ہے۔';
        $epxAdded = update('imp_truck_maals2', $utExpensesArray, array('id' => $imp_truck_maals2_id));

    } else {
        $utExpensesArray['created_at'] = date('Y-m-d H:i:s');
        $utExpensesArray['created_by'] = $userId;
        $strr = 'بیوپاری سمری کا اضافی خرچہ محفوظ ہوگیا ہے۔';
        $epxAdded = insert('imp_truck_maals2', $utExpensesArray);

    }
    if ($epxAdded) {
        message('success', $expUrl, $strr);
    } else {
        message('danger', $expUrl, ' خرچہ محفوظ نہیں ہو سکا۔ ');
    }
}
if (isset($_POST['recordSubmitFinal'])) {
    $imp_truck_maals2_id = mysqli_real_escape_string($connect, $_POST['imp_truck_maals2_id']);
    $tl_id_hidden = mysqli_real_escape_string($connect, $_POST['tl_id_hidden']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_id']);
    $amount = mysqli_real_escape_string($connect, $_POST['total_bill']);
    $url = mysqli_real_escape_string($connect, $_POST['url']);
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $transfered_from = mysqli_real_escape_string($connect, $_POST['transfered_from']);
    $r_type = "karobar";
    $details = $type . ' سے ٹرانسفر ';
    if ($tl_id_hidden && $imp_truck_maals2_id) {
        $r_home_exp = fetch('imp_truck_loadings', array('id' => $tl_id_hidden));
        $data = mysqli_fetch_assoc($r_home_exp);
        $serial = fetch('roznamchaas', array('branch_id' => $branchId, 'r_type' => 'karobar'));
        $branch_serial = mysqli_num_rows($serial);
        $branch_serial = $branch_serial + 1;
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $tl_id_hidden,
            'branch_id' => $data['branch_id'],
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'r_date' => date('Y-m-d'),
            'roznamcha_no' => $data['truck_no'],
            'r_name' => $type,
            'r_no' => $tl_id_hidden,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " سیریل نمبر " . $tl_id_hidden;
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
                'jmaa_khaata_no' => $jmaa_khaata_no,
                'jmaa_khaata_id' => $jmaa_khaata_id,
                'bnaam_khaata_no' => $bnaam_khaata_no,
                'bnaam_khaata_id' => $bnaam_khaata_id,
                'total_bill' => $amount,
                'imp_truck_maals2_id' => $imp_truck_maals2_id
            );
            $khaataData = array('khaata_bs_ee' => json_encode($preData));
            $tlUpdated = update('imp_truck_loadings', $khaataData, array('id' => $tl_id_hidden));
            $maal2Data = array('is_ttr' => 1);
            $tlUpdated = update('imp_truck_maals2', $maal2Data, array('id' => $imp_truck_maals2_id));
            message('success', $url, ' اضافی خرچہ روزنامچہ میں ٹرانسفر ہوگیا ہے۔ ' . $str);
        } else {
            message('danger', $url, 'ہم معذرت خواہ ہیں کہ روزنامچہ ٹرانسفر نہیں ہو سکا۔');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
} ?>
<script src="assets/js/input-repeator.js"></script>
<script>
    transferToRoznamcha();
    $(document).on('keyup', "#afg_jmaa_khaata_no", function (e) {
        transferToRoznamcha();
        var khaata_no = $(this).val();
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
                    var res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
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
    });
    $(document).on('keyup', "#afg_bnaam_khaata_no", function (e) {
        transferToRoznamcha();
        var khaata_no = $(this).val();
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
                    var res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
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
    });

    function transferToRoznamcha() {
        var msg = '';
        var totalBillMsg = $("#totalBillMsg");
        var khaata_id1 = $("#khaata_id1").val();
        var khaata_id2 = $("#khaata_id2").val();
        var total = $("#total").val();
        if (khaata_id1 <= 0 || khaata_id2 <= 0 || total <= 0) {
            totalBillMsg.show();
            if (khaata_id1 <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = 'جمع کھاتہ درست نہیں۔';
            }
            if (khaata_id2 <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = 'بنام کھاتہ درست نہیں۔';
            }
            if (total <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = ' ٹوٹل بل خالی نہیں ہو سکتا ';
            }
        } else {
            msg = '';
            $("#recordSubmitFinal").prop('disabled', false);
            totalBillMsg.hide();
        }
        totalBillMsg.text(msg);
    }
</script>
<!--<script>
    $("#recordSubmitFinal").prop('disabled', true);
    var khaata_id = $("#khaata_id1").val();
    var typingTimer;
    var doneTypingInterval = 1000;
    var $input = $('#afg_jmaa_khaata_no');
    var khaata_no = '';
    $input.on('keyup', function (e) {
        clearTimeout(typingTimer);
        khaata_no = $('#afg_jmaa_khaata_no').val();
        typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });
    function doneTyping() {
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    totalBill();
                    $("#khaata_id1").val(response.messages['khaata_id']);
                    $("#response1").text('');
                    var res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                    $("#jm_kh_tafseel").html(res);
                }
                if (response.success === false) {
                    $("#recordSubmitFinal").prop('disabled', true);
                    $("#recordUpdate").prop('disabled', true);
                    $("#response1").text('جمع کھاتہ نمبر درست نہیں ہے');
                    $("#jm_kh_tafseel").text('');
                    $("#khaata_id1").val(khaata_id);
                }
            }
        });
    }
</script>
<script>
    var khaata_id2 = $("#khaata_id2").val();
    var typingTimer2;
    var doneTypingInterval2 = 1000;
    var $input2 = $('#afg_bnaam_khaata_no');
    var khaata_no2 = '';
    $input2.on('keyup', function (e) {
        clearTimeout(typingTimer2);
        khaata_no2 = $('#afg_bnaam_khaata_no').val();
        typingTimer2 = setTimeout(doneTyping2, doneTypingInterval2);
    });
    function doneTyping2() {
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no2},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    totalBill();
                    $("#recordSubmitFinal").prop('disabled', false);
                    $("#recordUpdate").prop('disabled', false);
                    $("#khaata_id2").val(response.messages['khaata_id']);
                    $("#response2").text('');
                    var res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                    $("#bm_kh_tafseel").html(res);
                }
                if (response.success === false) {
                    $("#recordSubmitFinal").prop('disabled', true);
                    $("#recordUpdate").prop('disabled', true);
                    $("#response2").text('بنام کھاتہ نمبر درست نہیں ہے');
                    $("#bm_kh_tafseel").text('');
                    $("#khaata_id2").val(khaata_id2);
                }
            }
        });
    }
    function totalBill() {
        var total = $("#total").val();
        if (total <= 0) {
            var msg = ' ٹوٹل بل خالی نہیں ہو سکتا ';
            $("#totalBillMsg").text(msg);
            $("#recordSubmitFinal").hide();
        } else {
            $("#recordSubmitFinal").show();
            $("#totalBillMsg").hide();
        }
    }
</script>-->