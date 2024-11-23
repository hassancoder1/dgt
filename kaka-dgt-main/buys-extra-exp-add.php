<?php include("header.php"); ?>
<?php if (isset($_GET['bd_id']) && !empty($_GET['bd_id']) && is_numeric($_GET['bd_id']) &&
    isset($_GET['buys_id']) && !empty($_GET['buys_id']) && is_numeric($_GET['buys_id'])
) {
    $buys_details_id = mysqli_real_escape_string($connect, $_GET['bd_id']);
    $buys_id = mysqli_real_escape_string($connect, $_GET['buys_id']);
    $records = fetch('buys_details_expenses', array('bd_id' => $buys_details_id));
    $record = mysqli_fetch_assoc($records);
    if (mysqli_num_rows($records) > 0 && !empty($record['khaata_exp'])) {
        $khaataJson = json_decode($record['khaata_exp']);
        $jmaa_khaata_no = $khaataJson->jmaa_khaata_no;
        $jmaa_khaata_id = $khaataJson->jmaa_khaata_id;
        $bnaam_khaata_no = $khaataJson->bnaam_khaata_no;
        $bnaam_khaata_id = $khaataJson->bnaam_khaata_id;
        $jmaaName = getTableDataByIdAndColName('khaata', $jmaa_khaata_id, 'khaata_name');
        $bnaamName = getTableDataByIdAndColName('khaata', $bnaam_khaata_id, 'khaata_name');
    } else {
        $jmaa_khaata_no = $bnaam_khaata_no = $jmaaName = $bnaamName = $jm_kh_tafseel = $bm_kh_tafseel = "";
    } ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n1">
        <div>
            <h4 class="mb-3 mb-md-0 ">خریداری اضافی خرچہ اندراج</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl('buys-extra-exp'); ?>
        </div>
    </div>
    <div class="row gx-2">
        <div class="col-md-10">
            <div class="card border-top-0">
                <form method="post">
                    <div class="card-body">
                        <?php if (isset($_SESSION['response'])) {
                            echo $_SESSION['response'];
                            unset($_SESSION['response']);
                        } ?>
                        <?php if (mysqli_num_rows($records) > 0 && !empty($record['khaata_exp'])) { // just view
                            /*data for col-2 */
                            $jm_kh_tafseel = $jmaaName;
                            $branch_id = getTableDataByIdAndColName('khaata', $jmaa_khaata_id, 'branch_id');
                            $b_name = getTableDataByIdAndColName('branches', $branch_id, 'b_name');
                            $mobile = getTableDataByIdAndColName('khaata', $jmaa_khaata_id, 'mobile');
                            $jm_kh_tafseel .= '<span class="badge bg-success ">' . $b_name . '</span>';
                            $jm_kh_tafseel .= '<span class="badge bg-success ltr ms-1">' . $mobile . '</span>';
                            $bm_kh_tafseel = $bnaamName;
                            $branch_id2 = getTableDataByIdAndColName('khaata', $bnaam_khaata_id, 'branch_id');
                            $b_name2 = getTableDataByIdAndColName('branches', $branch_id2, 'b_name');
                            $bm_kh_tafseel .= '<span class="badge bg-success ">' . $b_name2 . '</span>';
                            $mobile2 = getTableDataByIdAndColName('khaata', $bnaam_khaata_id, 'mobile');
                            $bm_kh_tafseel .= '<span class="badge bg-success ltr ms-1">' . $mobile2 . '</span>';
                            /*data for col-2 end*/
                            $total_bill = $exp_names = 0;
                            $json2 = json_decode($record['json_data']);
                            $total_bill = $json2->total_bill;
                            $exp_names = $json2->exp_names;
                            $exp_details = $json2->exp_details;
                            $exp_values = $json2->exp_values; ?>
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
                                            <td><input value="<?php echo $exp_names[$index]; ?>" type="text"
                                                       disabled
                                                       class="form-control form-control-sm input-urdu"></td>
                                            <td><input value="<?php echo $exp_details[$index]; ?>" type="text"
                                                       disabled
                                                       class="form-control form-control-sm input-urdu"></td>
                                            <td><input value="<?php echo $exp_values[$index]; ?>" type="text"
                                                       disabled
                                                       class="form-control form-control-sm input-urdu"></td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                            <div class="row gx-0 mt-2 justify-content-center">
                                <div class="col-2">
                                    <div class="input-group">
                                        <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_jmaa_khaata_no" class="form-control" readonly
                                               value="<?php echo $jmaa_khaata_no; ?>">
                                    </div>
                                    <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id">
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_bnaam_khaata_no" class="form-control" readonly
                                               value="<?php echo $bnaam_khaata_no; ?>">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly value="<?php echo $total_bill; ?>"
                                               class="form-control bold" required>
                                    </div>
                                </div>
                                <div class="col-3 text-center">
                                    <p class="text-danger urdu pt-2 bold">روزنامچہ میں ٹرانسفر ہوچکا ہے</p>
                                </div>
                            </div>
                        <?php } else { ?>
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
                                <?php $arrayNumber = $total_bill = 0;
                                if (mysqli_num_rows($records) <= 0) { // exp add krna h
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
                                } else { // jmaa, bnaam show krna h
                                    $json2 = json_decode($record['json_data']);
                                    $total_bill = $json2->total_bill;
                                    $exp_names = $json2->exp_names;
                                    $exp_details = $json2->exp_details;
                                    $exp_values = $json2->exp_values;
                                    $bd_exp_id = $record['id'];
                                    $x = 1;
                                    foreach ($exp_names as $index => $value) { ?>
                                        <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                            <td class="border-bottom border-success border-end-0 border-start-0">
                                                <i id="removeProductRowBtn" tabindex="-1"
                                                   class="fa fa-remove border px-2 py-1 ms-2 mt-1 text-danger cursor-pointer"
                                                   data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)"
                                                   onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                            </td>
                                            <td>
                                                <input type="text" placeholder="خرچہ <?php echo $x; ?>" required
                                                       name="exp_names[]" <?php echo empty($exp_names[$index]) ? 'autofocus' : ''; ?>
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
                                    echo '<input type="hidden" value="' . $bd_exp_id . '" name="bd_exp_id">'; ?>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                        <?php } ?>
                        <input type="hidden" name="buys_details_id_hidden" value="<?php echo $buys_details_id; ?>">
                        <input type="hidden" name="buys_id_hidden" value="<?php echo $buys_id; ?>">
                        <?php
                        if (mysqli_num_rows($records) <= 0) { ?>
                            <div class="mt-2">
                                <button name="saveExpense" id="saveExpense" type="submit"
                                        class="btn btn-dark btn-icon-text">
                                    <i class="btn-icon-prepend" data-feather="check-square"></i>خرچہ محفوظ
                                </button>
                                <div class="float-end">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly name="total_bill"
                                               value="<?php echo $total_bill; ?>"
                                               class="form-control bold" required min="1" tabindex="-1">
                                    </div>
                                </div>
                            </div>
                        <?php } else {
                            if ($record['is_ttr'] == 0) { ?>
                                <div class="mt-2">
                                    <button name="saveExpense" id="saveExpense" type="submit"
                                            class="btn btn-dark btn-icon-text">
                                        <i class="btn-icon-prepend" data-feather="check-square"></i>خرچہ محفوظ
                                    </button>
                                    <div class="float-end">
                                        <div class="input-group">
                                            <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                            <input type="text" id="total" readonly name="total_bill"
                                                   value="<?php echo $total_bill; ?>"
                                                   class="form-control bold" required min="1" tabindex="-1">
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </form>
            </div>
            <?php if (mysqli_num_rows($records) > 0 && empty($record['khaata_exp'])) { ?>
                <div class="card mt-2 border-top-0">
                    <div class="card-body">
                        <?php $formStr = 'رقم: ' . $total_bill . '\n';
                        $formStr .= 'محفوظ کرنے سے پہلے خرچوں کو اچھی طرح چیک کے لیں' . '\n';
                        $formStr .= 'روزنامچہ میں ٹرانسفر کرنے کے لیے OK کا بٹن دبائیں۔'; ?>
                        <?php if (empty($record['khaata_exp'])) { ?>
                            <form method="post" onsubmit="return confirm('<?php echo $formStr; ?>');">
                                <div class="row gx-0 mt-4 justify-content-center">
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع
                                                کھاتہ
                                                نمبر</label>
                                            <input type="text" id="afg_jmaa_khaata_no" name="jmaa_khaata_no"
                                                   class="form-control bg-transparent" required autofocus
                                                   onchange="transferToRoznamcha()">
                                            <small id="response1"
                                                   class="text-danger urdu position-absolute top-0 left-0"></small>
                                        </div>
                                        <input type="hidden" id="khaata_id1" name="jmaa_khaata_id">
                                    </div>
                                    <div class="col-lg-2 position-relative">
                                        <div class="input-group">
                                            <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام
                                                کھاتہ
                                                نمبر</label>
                                            <input type="text" id="afg_bnaam_khaata_no" name="bnaam_khaata_no"
                                                   class="form-control bg-transparent" required
                                                   onchange="transferToRoznamcha()">
                                            <small id="response2"
                                                   class="text-danger urdu position-absolute top-0 left-0"></small>
                                        </div>
                                        <input type="hidden" id="khaata_id2" name="bnaam_khaata_id">
                                    </div>
                                    <input type="hidden" value="<?php echo $total_bill; ?>" name="total_bill">
                                    <input type="hidden" name="buys_id_hidden" value="<?php echo $buys_id; ?>">
                                    <input type="hidden" name="buys_details_id_hidden"
                                           value="<?php echo $buys_details_id; ?>">
                                    <input type="hidden" name="buys_details_exp_id_hidden"
                                           value="<?php echo $record['id']; ?>">
                                    <div class="col-lg-2">
                                        <button name="transferToRoznamchaSubmit" id="recordSubmitFinal"
                                                type="submit" class="btn btn-primary btn-icon-text">
                                            <i class="btn-icon-prepend" data-feather="check-square"></i>روزنامچہ
                                            میں
                                            ٹرانسفر
                                        </button>
                                        <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                    </div>
                                    <div class="col-lg-6">
                                        <p class="text-danger urdu h4">ٹرانسفر کرنے سے پہلے خرچہ محفوظ کا بٹن دبا
                                            کر
                                            خرچے ضرور محفوظ کر لیں۔</p>
                                    </div>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-2">
            <div class="card p-2">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group bg-info bg-opacity-10">
                            <label class="input-group-text urdu">جنرل سیریل نمبر</label>
                            <input type="text" class="form-control" disabled value="<?php echo $buys_id; ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group bg-info bg-opacity-10">
                            <label for="" class="input-group-text urdu">بل نمبر</label>
                            <input type="text" id="" class="form-control" disabled="" value="<?php echo $buys_details_id; ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-2">
                <div class="urdu-2">
                    <h5 class="bg-success bg-opacity-25 p-2">جمع کھاتہ نام</h5>
                    <p class="p-1 bold text-primary" id="jm_kh_tafseel"><?php echo $jm_kh_tafseel; ?></p>
                    <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                    <p class="p-1 bold text-primary" id="bm_kh_tafseel"><?php echo $bm_kh_tafseel; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    echo '<script>window.location.href="./";</script>';
} ?>
<?php include("footer.php"); ?>
<script>
    $("html, body").animate({scrollTop: $(document).height()}, 1000);
</script>
<?php if (isset($_POST['saveExpense'])) {
    $buys_details_id_hidden = mysqli_real_escape_string($connect, $_POST['buys_details_id_hidden']);
    $buys_id_hidden = mysqli_real_escape_string($connect, $_POST['buys_id_hidden']);
    $url = 'buys-extra-exp-add?buys_id=' . $buys_id_hidden . '&bd_id=' . $buys_details_id_hidden;
    $bdExpensesArray = array(
        'is_ttr' => 0,
        'bd_id' => $buys_details_id_hidden,
        'json_data' => mysqli_real_escape_string($connect, json_encode($_POST, JSON_UNESCAPED_UNICODE))
    );
    $strr = '';
    if (isset($_POST['bd_exp_id']) && isset($_POST['action'])) {
        $bd_exp_id = $_POST['bd_exp_id'];
        $bdExpensesArray['updated_at'] = date('Y-m-d H:i:s');
        $bdExpensesArray['updated_by'] = $userId;
        $strr = 'خرچہ میں تبدیلی محفوظ ہو گئی ہے۔';
        $epxAdded = update('buys_details_expenses', $bdExpensesArray, array('id' => $bd_exp_id));

    } else {
        $bdExpensesArray['created_at'] = date('Y-m-d H:i:s');
        $bdExpensesArray['created_by'] = $userId;
        $strr = 'خرچہ محفوظ ہوگیا ہے۔';
        $epxAdded = insert('buys_details_expenses', $bdExpensesArray);

    }
    if ($epxAdded) {
        message('success', $url, $strr);
    } else {
        message('danger', $url, ' خرچہ محفوظ نہیں ہو سکا۔ ');
    }
} ?>
<?php if (isset($_POST['transferToRoznamchaSubmit'])) {
    $amount = mysqli_real_escape_string($connect, $_POST['total_bill']);
    $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id_hidden']);
    $buys_details_id = mysqli_real_escape_string($connect, $_POST['buys_details_id_hidden']);
    $buys_details_exp_id_hidden = mysqli_real_escape_string($connect, $_POST['buys_details_exp_id_hidden']);
    $url = 'buys-extra-exp-add?buys_id=' . $buys_id . '&bd_id=' . $buys_details_id;
    $type = ' خریداری اضافی خرچہ ';
    $transfered_from = 'buys_details';
    $r_type = 'karobar';

    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['jmaa_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']);
    $details = $type . ' سے ٹرانسفر, ' . ' جنرل سیریل نمبر ' . $buys_id . ' بل نمبر ' . $buys_details_id;

    if ($buys_id && $buys_details_id && $jmaa_khaata_id && $bnaam_khaata_id) {
        $buys_details = fetch('buys_details', array('id' => $buys_details_id));
        $data = mysqli_fetch_assoc($buys_details);
        $serial = fetch('roznamchaas', array('branch_id' => $data['branch_id'], 'r_type' => $r_type));
        $branch_serial = mysqli_num_rows($serial);
        $branch_serial = $branch_serial + 1;
        /*details for roznamcha*/
        $buys = fetch('buys', array('id' => $buys_id));
        $data1 = mysqli_fetch_assoc($buys);
        $details .= ' جنس ' . $data1['jins'] . ' کنٹینر نمبر ' . $data['container_no'] . ' مارکہ ' . $data['marka'] . ' لاٹ نام ' . $data['allot_name'];
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $buys_details_id,
            'branch_id' => $data['branch_id'],
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'r_date' => date('Y-m-d'),
            'roznamcha_no' => $buys_details_id,
            'r_name' => $type,
            'r_no' => $buys_id,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " بل نمبر " . $buys_details_id;
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
                'bnaam_khaata_no' => $jmaa_khaata_no,
                'bnaam_khaata_id' => $jmaa_khaata_id,
                'total_bill' => $amount,
            );
            $bd_exp_arr = array(
                'iss_ttr' => 1,
                'khaata_exp' => mysqli_real_escape_string($connect, json_encode($preData))
            );
            $tlUpdated = update('buys_details_expenses', $bd_exp_arr, array('id' => $buys_details_exp_id_hidden));

            message('success', $url, ' روزنامچہ میں ٹرانسفر ہوگیا ہے۔ ' . $str);
        } else {
            message('danger', $url, ' روزنامچہ ٹرانسفر نہیں ہو سکا۔');
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
