<?php include("header.php"); ?>
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['type'])
    && ($_GET['type'] == KARACHI || $_GET['type'] == CHAMAN || $_GET['type'] == BORDER || $_GET['type'] == QANDHAR || $_GET['type'] == BORDER_BILL || $_GET['type'] == BORDER_AFG_TRUCK)
) {
    $getType = mysqli_real_escape_string($connect, $_GET["type"]);
    $urlArray = array(
        KARACHI => array(
            'path' => 'ut-import-custom-karachi', 'title' => ' کراچی خرچہ بل ', 'r_type' => 'ut_' . KARACHI,
            'type' => ' کراچی خرچہ بل ', 'transfered_from' => 'ut_expense_' . KARACHI, 'khaata_' => 'khaata_' . KARACHI
        ),
        CHAMAN => array(
            'path' => 'ut-export-custom-chaman', 'title' => ' چمن خرچہ بل ', 'r_type' => 'ut_' . CHAMAN,
            'type' => ' چمن خرچہ بل ', 'transfered_from' => 'ut_expense_' . CHAMAN, 'khaata_' => 'khaata_' . CHAMAN
        ),
        BORDER => array(
            'path' => 'ut-afghan-border', 'title' => ' بارڈر خرچہ بل ', 'r_type' => 'ut_' . BORDER,
            'type' => ' بارڈر خرچہ بل ', 'transfered_from' => 'ut_expense_' . BORDER, 'khaata_' => 'khaata_' . BORDER
        ),
        BORDER_AFG_TRUCK => array(
            'path' => 'ut-afghan-border', 'title' => ' بارڈر افغانی ٹرک کرایہ ', 'r_type' => 'ut_' . BORDER_AFG_TRUCK,
            'type' => ' بارڈر افغانی ٹرک کرایہ ', 'transfered_from' => 'ut_expense_' . BORDER_AFG_TRUCK, 'khaata_' => 'khaata_' . BORDER_AFG_TRUCK
        ),
        QANDHAR => array(
            'path' => 'ut-qandhar-custom', 'title' => ' قندھار خرچہ بل ', 'r_type' => 'ut_' . QANDHAR,
            'type' => ' قندھار خرچہ بل ', 'transfered_from' => 'ut_expense_' . QANDHAR, 'khaata_' => 'khaata_' . QANDHAR
        ),
        BORDER_BILL => array(
            'path' => 'ut-commission-border-bill', 'title' => ' کمیشن بارڈر بل ', 'r_type' => 'ut_' . BORDER_BILL,
            'type' => ' کمیشن بارڈر بل ', 'transfered_from' => 'ut_expense_' . BORDER_BILL, 'khaata_' => 'khaata_' . BORDER_BILL
        )
    );
    $page = $urlArray[$getType];
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('ut_bail_entries', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    if (empty($record[$page['khaata_']])) {
        $jmaa_khaata_no = $bnaam_khaata_no = $jmaaName = $bnaamName = $jm_kh_tafseel = $bm_kh_tafseel = "";
    } else {
        $khaataJson = json_decode($record[$page['khaata_']]);
        $jmaa_khaata_no = $khaataJson->jmaa_khaata_no;
        $jmaa_khaata_id = $khaataJson->jmaa_khaata_id;
        $bnaam_khaata_no = $khaataJson->bnaam_khaata_no;
        $bnaam_khaata_id = $khaataJson->bnaam_khaata_id;
        $jmaaName = getTableDataByIdAndColName('khaata', $jmaa_khaata_id, 'khaata_name');
        $bnaamName = getTableDataByIdAndColName('khaata', $bnaam_khaata_id, 'khaata_name');
    } ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n1">
        <div>
            <h4 class="mb-3 mb-md-0 "><?php echo $page['title']; ?></h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl($page['path']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 position-relative">
            <?php include("ut-bail-details.php"); ?>
            <div class="row gx-2">
                <div class="col-md-10">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <?php if (empty($record[$page['khaata_']])) { ?>
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
                                        $exps = isUTExpenseAdded($id, $getType);
                                        if ($exps['success']) {
                                            $json2 = json_decode($exps['output']['json_data']);
                                            $total_bill = $json2->total_bill;
                                            $exp_names = $json2->exp_names;
                                            $exp_details = $json2->exp_details;
                                            $exp_values = $json2->exp_values;
                                            $ut_exp_id = $exps['output']['id'];
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
                                            echo '<input type="hidden" value="' . $ut_exp_id . '" name="ut_exp_id">';
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
                                    <input type="hidden" name="expense_name" value="<?php echo $getType; ?>">
                                    <input type="hidden" name="ut_bail_id_hidden" value="<?php echo $id; ?>">
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
                                </form>
                                <?php $formStr = 'رقم: ' . $total_bill . '\n';
                                $formStr .= 'محفوظ کرنے سے پہلے خرچوں کو اچھی طرح چیک کے لیں' . '\n';
                                $formStr .= 'روزنامچہ میں ٹرانسفر کرنے کے لیے OK کا بٹن دبائیں۔'; ?>
                                <?php if ($exps['success']) { ?>
                                    <form method="post" onsubmit="return confirm('<?php echo $formStr; ?>');">
                                        <div class="row gx-0 mt-4 justify-content-center">
                                            <div class="col-lg-2">
                                                <div class="input-group">
                                                    <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع
                                                        کھاتہ
                                                        نمبر</label>
                                                    <input type="text" id="afg_jmaa_khaata_no" name="afg_jmaa_khaata_no"
                                                           class="form-control bg-transparent" required onchange="transferToRoznamcha()">
                                                    <small id="response1" class="text-danger urdu position-absolute top-0 left-0"></small>
                                                </div>
                                                <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id">
                                            </div>
                                            <div class="col-lg-2 position-relative">
                                                <div class="input-group">
                                                    <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام
                                                        کھاتہ
                                                        نمبر</label>
                                                    <input type="text" id="afg_bnaam_khaata_no"
                                                           name="afg_bnaam_khaata_no"
                                                           class="form-control bg-transparent" required
                                                           onchange="transferToRoznamcha()">
                                                    <small id="response2" class="text-danger urdu position-absolute top-0 left-0"></small>
                                                </div>
                                                <input type="hidden" id="khaata_id2" name="afg_bnaam_khaata_id">
                                            </div>
                                            <input type="hidden" value="<?php echo $total_bill; ?>" name="total_bill">
                                            <input type="hidden" name="transfered_from"
                                                   value="<?php echo $page["transfered_from"]; ?>">
                                            <input type="hidden" name="type" value="<?php echo $page["type"]; ?>">
                                            <input type="hidden" name="r_type" value="<?php echo $page["r_type"]; ?>">
                                            <input type="hidden" name="url" value="<?php echo $page['path']; ?>">
                                            <input type="hidden" name="expense_name" value="<?php echo $getType; ?>">
                                            <input type="hidden" name="ut_bail_id_hidden" value="<?php echo $id; ?>">
                                            <div class="col-lg-2">
                                                <button name="recordSubmitFinal" id="recordSubmitFinal" type="submit"
                                                        class="w-100 btn btn-primary btn-icon-text"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="ٹرانسفر کرنے سے پہلے ہر انٹری کی تسلی کر لیں۔ ٹرانسفر کے بعد تبدیل نہیں ہوگی">
                                                    <i class="btn-icon-prepend" data-feather="check-square"></i>روزنامچہ
                                                    میں
                                                    ٹرانسفر
                                                </button>
                                                <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                            </div>
                                            <div class="col-lg-12 text-center">
                                                <p class="text-danger urdu">ٹرانسفر کرنے سے پہلے خرچہ محفوظ کا بٹن دبا
                                                    کر
                                                    خرچے ضرور محفوظ کر لیں۔</p>
                                            </div>
                                        </div>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } else {
                        $total_bill = $exp_names = 0;
                        $exps = isUTExpenseAdded($id, $getType);
                        $json2 = json_decode($exps['output']['json_data']);
                        $total_bill = $json2->total_bill;
                        $exp_names = $json2->exp_names;
                        $exp_details = $json2->exp_details;
                        $exp_values = $json2->exp_values; ?>
                        <div class="card mt-2 border-top-0">
                            <div class="card-body">
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
                                            <input type="text" id="afg_bnaam_khaata_no"
                                                   class="form-control" readonly
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
                            </div>
                        </div>
                        <?php $jm_kh_tafseel = $jmaaName;
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
                    } ?>
                </div>
                <div class="col-md-2">
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
        </div>
    </div>
<?php } else {
    echo '<script>window.location.href="./";</script>';
} ?>
<?php include("footer.php"); ?>
<script>
    $("html, body").animate({scrollTop: $(document).height()}, 1000);
</script>
<script src="assets/js/ut-bail-dropdowns.js"></script>
<?php
if (isset($_POST['saveExpense'])) {
    $url = mysqli_real_escape_string($connect, $_POST['url']);
    $bail_id = mysqli_real_escape_string($connect, $_POST['ut_bail_id_hidden']);
    $expense_name = mysqli_real_escape_string($connect, $_POST['expense_name']);
    $expUrl = 'ut-expense-transfer?id=' . $bail_id . '&type=' . $expense_name;
    $utExpensesArray = array(
        'is_ttr' => 0,
        'bail_id' => $bail_id,
        'expense_name' => $expense_name,
        'json_data' => json_encode($_POST, JSON_UNESCAPED_UNICODE)
    );
    $strr = '';
    if (isset($_POST['ut_exp_id']) && isset($_POST['action'])) {
        $ut_exp_id = $_POST['ut_exp_id'];
        $utExpensesArray['updated_at'] = date('Y-m-d H:i:s');
        $utExpensesArray['updated_by'] = $userId;
        $strr = 'خرچہ میں تبدیلی محفوظ ہو گئی ہے۔';
        $epxAdded = update('ut_expenses', $utExpensesArray, array('id' => $ut_exp_id));

    } else {
        $utExpensesArray['created_at'] = date('Y-m-d H:i:s');
        $utExpensesArray['created_by'] = $userId;
        $strr = 'خرچہ محفوظ ہوگیا ہے۔';
        $epxAdded = insert('ut_expenses', $utExpensesArray);

    }
    if ($epxAdded) {
        message('success', $expUrl, $strr);
    } else {
        message('danger', $expUrl, ' خرچہ محفوظ نہیں ہو سکا۔ ');
    }
}
if (isset($_POST['recordSubmitFinal'])) {
    $amount = mysqli_real_escape_string($connect, $_POST['total_bill']);
    $url = mysqli_real_escape_string($connect, $_POST['url']);
    $bail_id = mysqli_real_escape_string($connect, $_POST['ut_bail_id_hidden']);
    $expense_name = mysqli_real_escape_string($connect, $_POST['expense_name']);
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    $transfered_from = mysqli_real_escape_string($connect, $_POST['transfered_from']);
    $r_type = mysqli_real_escape_string($connect, $_POST['r_type']);
    $jmaa_khaata_no = mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_no']);
    $jmaa_khaata_id = mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_id']);
    $bnaam_khaata_no = mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_no']);
    $bnaam_khaata_id = mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_id']);
    $details = $type . ' سے ٹرانسفر. بیل نمبر ' . $bail_id;
    if ($bail_id && $jmaa_khaata_id && $bnaam_khaata_id) {
        $bail_data = fetch('ut_bail_entries', array('id' => $bail_id));
        $data = mysqli_fetch_assoc($bail_data);
        $serial = fetch('roznamchaas', array('branch_id' => $data['branch_id'], 'r_type' => $r_type));
        $branch_serial = mysqli_num_rows($serial);
        $branch_serial = $branch_serial + 1;
        /*details for roznamcha*/
        $surrender_json = json_decode($data['surrender_json']);
        $details .= ' جنس ' . $data['jins'] . ' کنٹینر نمبر ' . $surrender_json->sr_container_no;
        switch ($expense_name) {
            case KARACHI:
                $imp_json = json_decode($data['imp_json']);
                $details .= ' ٹرک نمبر ' . $imp_json->imp_cus_truck_no;
                break;
            case CHAMAN:
                $exp_json = json_decode($data['exp_json']);
                $details .= ' کلئیرنس تاریخ ' . $exp_json->exp_cus_clearance_date;
                break;
            case BORDER:
                $border_json = json_decode($data['border_json']);
                $details .= ' ٹرک نمبر ' . $border_json->border_truck_no;
                break;
            case BORDER_AFG_TRUCK:
                $border_json = json_decode($data['border_json']);
                $details .= ' ٹرک نمبر ' . $border_json->border_truck_no;
                break;
            case QANDHAR:
                $qandhar_json = json_decode($data['qandhar_json']);
                $details .= ' کلئیرنس تاریخ ' . $qandhar_json->qandhar_clearance_date;
                break;
            default:
                $details .= '';
                break;
        }
        $dataArray = array(
            'r_type' => $r_type,
            'transfered_from' => $transfered_from,
            'transfered_from_id' => $bail_id,
            'branch_id' => $data['branch_id'],
            'user_id' => $data['user_id'],
            'username' => $data['username'],
            'r_date' => date('Y-m-d'),
            'roznamcha_no' => $data['bill_no'],
            'r_name' => $type,
            'r_no' => $bail_id,
            'details' => $details,
            'created_at' => date('Y-m-d H:i:s')
        );
        $str = " سیریل نمبر " . $bail_id;
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
            $preData = array('jmaa_khaata_no' => $jmaa_khaata_no, 'jmaa_khaata_id' => $jmaa_khaata_id, 'bnaam_khaata_no' => $bnaam_khaata_no, 'bnaam_khaata_id' => $bnaam_khaata_id, 'total_bill' => $amount, 'transferred_to_roznamcha_at' => date('Y-m-d'));
            $khaataData = array('khaata_' . $expense_name => json_encode($preData));

            $utExpensesUpdateArray = array('is_ttr' => 1);
            $epxAdded = update('ut_expenses', $utExpensesUpdateArray, array('bail_id' => $bail_id, 'expense_name' => $expense_name));

            $tlUpdated = update('ut_bail_entries', $khaataData, array('id' => $bail_id));
            message('success', $url, ' روزنامچہ میں ٹرانسفر ہوگیا ہے۔ ' . $str);
        } else {
            message('danger', $url, 'اخراجات محفوظ ہوئے ہیں لیکن روزنامچہ ٹرانسفر نہیں ہو سکا۔');
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
