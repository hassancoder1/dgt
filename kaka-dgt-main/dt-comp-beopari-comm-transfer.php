<?php include("header.php"); ?>
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['type'])
    && $_GET['type'] == 'beopari-commission'
) {
    $urlArray = array('beopari-commission' => array('path' => 'dt-comp-beopari-comm', 'title' => ' ڈاؤن ٹرانزٹ بیوپاری کمیشن بل ',
        'type' => ' ڈاؤن ٹرانزٹ بیوپاری کمیشن ', 'transfered_from' => 'beopari_commission_dt', 'khaata_' => 'khaata_bc'));
    $page = $urlArray[$_GET["type"]];
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('dt_truck_loadings', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    if (empty($record[$page['khaata_']])) {
        $jmaa_khaata_no = $bnaam_khaata_no = 0;
        $jmaaName = $bnaamName = "";
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
            'dt_comp_name' => $sender_receiver->dt_comp_name,
            'dt_sender_address' => $sender_receiver->dt_sender_address,
            'dt_sender_mobile' => $sender_receiver->dt_sender_mobile,
            'dt_sender_owner' => $sender_receiver->dt_sender_owner,
            'dt_comp_name_r' => $sender_receiver->dt_comp_name_r,
            'dt_receiver_address' => $sender_receiver->dt_receiver_address,
            'dt_receiver_mobile' => $sender_receiver->dt_receiver_mobile,
            'dt_receiver_owner' => $sender_receiver->dt_receiver_owner
        );
    } else {
        $names = array(
            'dt_comp_name' => '',
            'dt_sender_address' => '',
            'dt_sender_mobile' => '',
            'dt_sender_owner' => '',
            'dt_comp_name_r' => '',
            'dt_receiver_address' => '',
            'dt_receiver_mobile' => '',
            'dt_receiver_owner' => ''
        );
    } ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n1">
        <div>
            <h4 class="mb-3 mb-md-0"><?php echo $page['title']; ?></h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="<?php echo $page['path']; ?>"
               class="btn btn-dark btn-icon-text mb-2 mb-md-0 pt-0 pb-1 mt-1">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php include('dt-loading-details.php'); ?>
            <div class="card mt-2 pb-2 px-2">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <div class="row gx-0 gy-2">
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="dt_comp_name" class="input-group-text urdu">مال بھیجنےوالا</label>
                            <input type="text" id="dt_comp_name" disabled class="form-control urdu-2 bold"
                                   value="<?php echo $names['dt_comp_name']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="dt_sender_address" class="input-group-text urdu">پتہ</label>
                            <input type="text" id="dt_sender_address" disabled class="form-control urdu-2 bold"
                                   value="<?php echo $names['dt_sender_address']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="dt_sender_mobile" class="input-group-text urdu">موبائل نمبر</label>
                            <input type="text" id="dt_sender_mobile" disabled class="form-control ltr bold"
                                   value="<?php echo $names['dt_sender_mobile']; ?>">
                            <label for="dt_sender_owner" class="input-group-text urdu">مالک نام</label>
                            <input type="text" id="dt_sender_owner" class="form-control urdu-2 ltr bold" disabled
                                   value="<?php echo $names['dt_sender_owner']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="dt_comp_name_r" class="input-group-text urdu">مال وصول کرنےوالا</label>
                            <input type="text" id="dt_comp_name_r" disabled class="form-control urdu-2 bold"
                                   value="<?php echo $names['dt_comp_name_r']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="dt_receiver_address" class="input-group-text urdu">پتہ</label>
                            <input type="text" id="dt_receiver_address" disabled class="form-control urdu-2 bold"
                                   value="<?php echo $names['dt_receiver_address']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group">
                            <label for="dt_receiver_mobile" class="input-group-text urdu">موبائل نمبر</label>
                            <input type="text" id="dt_receiver_mobile" disabled class="form-control ltr bold"
                                   value="<?php echo $names['dt_receiver_mobile']; ?>">
                            <label for="dt_receiver_owner" class="input-group-text urdu">مالک نام</label>
                            <input type="text" id="dt_receiver_owner" class="form-control urdu-2 ltr bold" disabled
                                   value="<?php echo $names['dt_receiver_owner']; ?>">
                        </div>
                    </div>

                </div>
            </div>
            <div class="row gx-2">
                <div class="col-md-10">
                    <div class="card mt-2 border-top-0">
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
                                <?php $maals = fetch('dt_truck_maals', array('dt_tl_id' => $id));
                                $x = 1;
                                $godam_receive_date = null;
                                $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = 0;
                                while ($maal = mysqli_fetch_assoc($maals)) {
                                    $json = json_decode($maal['json_data']); ?>
                                    <tr class="row-py-0">
                                        <td><?php echo $x; ?></td>
                                        <td><?php echo $json->jins_name; ?></td>
                                        <td><?php echo $json->bardana_name; ?></td>
                                        <td><?php echo $json->bardana_qty; ?></td>
                                        <td><?php echo $json->per_wt; ?></td>
                                        <td><?php echo $json->total_wt; ?></td>
                                        <td class="ltr"><?php echo $json->empty_wt; ?></td>
                                        <td><?php echo $json->total_empty_wt; ?></td>
                                        <td class="ltr"><?php echo $json->saaf_wt; ?></td>
                                    </tr>
                                    <?php $x++;
                                    $bardana_qty += $json->bardana_qty;
                                    $per_wt += $json->per_wt;
                                    $total_wt += $json->total_wt;
                                    $empty_wt += $json->empty_wt;
                                    $total_empty_wt += $json->total_empty_wt;
                                    $saaf_wt += $json->saaf_wt;
                                } ?>
                                <tr class="row-py-0 bg-info bg-opacity-25 bold">
                                    <td><?php echo $x - 1; ?></td>
                                    <td colspan="2"></td>
                                    <td><?php echo $bardana_qty; ?></td>
                                    <td><?php echo $per_wt; ?></td>
                                    <td><?php echo $total_wt; ?></td>
                                    <td><?php echo $empty_wt; ?></td>
                                    <td><?php echo $total_empty_wt; ?></td>
                                    <td class="ltr"><?php echo $saaf_wt; ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (empty($record[$page['khaata_']])) { ?>
                        <div class="card mt-2 border-top-0">
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
                                    <?php
                                    $arrayNumber = 0;
                                    for ($x = 1; $x < 3; $x++) { ?>
                                        <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                            <td class="border-bottom border-success border-end-0 border-start-0">
                                                <i id="removeProductRowBtn"
                                                   class="fa fa-remove border px-2 py-1 ms-2 mt-1 text-danger cursor-pointer"
                                                   data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)" tabindex="-1"
                                                   onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                            </td>
                                            <td>
                                                <input type="text" name="exp_names[]" autofocus
                                                       placeholder="خرچہ <?php echo $x; ?>"
                                                       required class="form-control form-control-sm input-urdu"
                                                       id="exp_name<?php echo $x; ?>">
                                            </td>
                                            <td>
                                                <input type="text" name="exp_details[]"
                                                       placeholder="تفصیل <?php echo $x; ?>"
                                                       required class="form-control form-control-sm input-urdu"
                                                       id="exp_name<?php echo $x; ?>">
                                            </td>
                                            <td>
                                                <input type="text" name="exp_values[]" required
                                                       placeholder="رقم <?php echo $x; ?>"
                                                       onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off"
                                                       class="form-control currency form-control-sm bold"
                                                       id="exp_value<?php echo $x; ?>">
                                            </td>
                                        </tr>
                                        <?php
                                        $arrayNumber++;
                                    } ?>
                                    </tbody>
                                </table>
                                <div class="row gx-0 my-2">
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                                نمبر</label>
                                            <input type="text" id="afg_jmaa_khaata_no" name="afg_jmaa_khaata_no"
                                                   class="form-control" required
                                                   value="<?php echo $jmaa_khaata_no; ?>">
                                            <small id="response1" class="text-danger urdu position-absolute"
                                                   style="top: -20px;"></small>
                                        </div>
                                        <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id">
                                    </div>
                                    <div class="col-lg-3 position-relative">
                                        <div class="input-group">
                                            <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                                نمبر</label>
                                            <input type="text" id="afg_bnaam_khaata_no" name="afg_bnaam_khaata_no"
                                                   class="form-control" required
                                                   value="<?php echo $bnaam_khaata_no; ?>">
                                            <small id="response2" class="text-danger urdu position-absolute"
                                                   style="top: -20px;right:20px;"></small>
                                        </div>
                                        <input type="hidden" id="khaata_id2" name="afg_bnaam_khaata_id">
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                            <input type="text" id="total" readonly name="total_bill"
                                                   class="form-control bold" required>
                                        </div>
                                    </div>
                                    <input type="hidden" name="transfered_from"
                                           value="<?php echo $page["transfered_from"]; ?>">
                                    <input type="hidden" name="type" value="<?php echo $page["type"]; ?>">
                                    <input type="hidden" name="url" value="<?php echo $page['path']; ?>">
                                    <input type="hidden" name="tl_id_hidden" value="<?php echo $id; ?>">
                                    <div class="col-lg-2">
                                        <button name="recordSubmitFinal" id="recordSubmitFinal" type="submit"
                                                class="btn btn-primary btn-icon-text" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="ٹرانسفر کرنے سے پہلے ہر انٹری کی تسلی کر لیں۔ ٹرانسفر کے بعد تبدیل نہیں ہوگی">
                                            <i class="btn-icon-prepend" data-feather="check-square"></i>روزنامچہ میں
                                            ٹرانسفر
                                        </button>
                                        <span id="totalBillMsg" class="text-danger bold h4"></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php } else {
                        $total_bill = $exp_names = 0;
                        $maal2 = isDTKirayaAdded(0, 'beopari_commission_dt', $id);
                        if ($maal2['success']) {
                            $maal2Id = $maal2['output']['id'];
                            $json2 = json_decode($maal2['output']['json_data']);
                            $total_bill = $json2->total_bill;
                            $exp_names = $json2->exp_names;
                            $exp_details = $json2->exp_details;
                            $exp_values = $json2->exp_values;
                        } ?>
                        <div class="card mt-2 border-top-0">
                            <table class="table table-bordered" id="productTable">
                                <thead>
                                <tr>
                                    <th width="20%">خرچہ نام</th>
                                    <th width="60%">تفصیل</th>
                                    <th width="20%">رقم</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (count($exp_names) > 0) {
                                    foreach ($exp_names as $index => $value) { ?>
                                        <tr id="row<?php echo $x; ?>">
                                            <td><input value="<?php echo $exp_names[$index]; ?>" type="text" disabled
                                                       class="form-control form-control-sm input-urdu"></td>
                                            <td><input value="<?php echo $exp_details[$index]; ?>" type="text" disabled
                                                       class="form-control form-control-sm input-urdu"></td>
                                            <td><input value="<?php echo $exp_values[$index]; ?>" type="text" disabled
                                                       class="form-control form-control-sm input-urdu"></td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                            <div class="row gx-0 my-2">
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_jmaa_khaata_no" class="form-control" readonly
                                               value="<?php echo $jmaa_khaata_no; ?>">
                                    </div>
                                    <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id">
                                </div>
                                <div class="col-lg-3 ">
                                    <div class="input-group">
                                        <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_bnaam_khaata_no"
                                               class="form-control" readonly
                                               value="<?php echo $bnaam_khaata_no; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly value="<?php echo $total_bill; ?>"
                                               class="form-control bold" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <span class="text-danger urdu-2 bold">یہ روزنامچہ میں ٹرانسفر ہوچکا ہے۔</span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
    echo '<script>window.location.href="dt-comp-beopari-comm";</script>';
} ?>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordSubmitFinal'])) {
    //var_dump($_POST);
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
    //var_dump($_POST);
    //die();
    if ($tl_id_hidden) {
        $maal2Data = array(
            'dt_tl_id' => $tl_id_hidden,
            'maal_id' => 0,
            'form_name' => $transfered_from,
            'json_data' => json_encode($_POST, JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $userId
        );
        $maal2Added = insert('dt_truck_maals2', $maal2Data);
        if ($maal2Added) {
            $r_home_exp = fetch('dt_truck_loadings', array('id' => $tl_id_hidden));
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
                    'total_bill' => $amount
                );
                $khaataData = array(
                    'khaata_bc' => json_encode($preData)
                );
                $tlUpdated = update('dt_truck_loadings', $khaataData, array('id' => $tl_id_hidden));
                message('success', $url, ' روزنامچہ میں ٹرانسفر ہوگیا ہے۔ ' . $str);
            } else {
                message('danger', $url, 'ہم معذرت خواہ ہیں کہ روزنامچہ ٹرانسفر نہیں ہو سکا۔');
            }
        } else {
            message('danger', $url, 'اخراجات محفوظ نہیں ہو سکے۔');
        }
    } else {
        message('info', $url, 'کوئی ٹیکنیکل پرابلم ہے۔ ایڈمن سے رابطہ کریں');
    }
} ?>

<script src="assets/js/input-repeator.js"></script>
<script>
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
                    /*$("#recordSubmit").prop('disabled', false);
                     $("#recordUpdate").prop('disabled', false);*/
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
                    //$("#bm_kh_tafseel").text(response.messages['khaata_name']);
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
            //alert('لائینوں میں اندراج ابھی باقی ہے۔\n' + 'باقی لائینیں: ' + remainingRows);
            $("#recordSubmitFinal").hide();
        } else {
            //alert('سارے ریکارڈ اندراج ہو چکے ہیں۔ اب آپ ٹرانسفر کر سکتے ہیں۔ ');
            $("#recordSubmitFinal").show();
            $("#totalBillMsg").hide();
        }
    }
</script>