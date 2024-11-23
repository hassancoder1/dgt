<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">گھر کا خرچہ اندراج </h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl('home-exp'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <?php if (isset($_GET['id'])) {
                        $id = mysqli_real_escape_string($connect, $_GET['id']);
                        $records = fetch('r_home_exp', array('id' => $id));
                        $record = mysqli_fetch_assoc($records);
                        ?>
                        <form action="" method="post">
                            <div class="row gx-0 gy-3 justify-content-center">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="bill_no" class="input-group-text urdu">بل نمبر</label>
                                        <input type="text" id="bill_no" name="bill_no" autofocus
                                               class="form-control" required value="<?php echo $record['bill_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label class="input-group-text urdu" for="exp_date">بل تاریخ</label>
                                        <input name="exp_date" type="text" id="exp_date" class="form-control"
                                               value="<?php echo $record['exp_date']; ?>"
                                               placeholder="بل تاریخ" data-input>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="bill_giver" class="input-group-text urdu">بل دینے والا
                                                نام</label>
                                            <input type="text" id="bill_giver" name="bill_giver"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $record['bill_giver']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 mt-4">
                                <div class="col-md-9">
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
                                        $x = 1;
                                        $arrayNumber = 0;
                                        $exp_names = json_decode($record["exp_names"]);
                                        $exp_details = json_decode($record["exp_details"]);
                                        $exp_values = json_decode($record["exp_values"]);
                                        foreach ($exp_names as $index => $value) {
                                            //for ($x = 1; $x < 4; $x++) {
                                            ?>
                                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                <td class="border-bottom border-success border-end-0 border-start-0">
                                                    <i id="removeProductRowBtn"
                                                       class="fa fa-remove border px-2 py-1 ms-2 mt-1 text-danger cursor-pointer"
                                                       data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)"
                                                       tabindex="-1"
                                                       onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_names[]"
                                                           value="<?php echo $exp_names[$index]; ?>"
                                                           placeholder="خرچہ <?php echo $x; ?>"
                                                           required class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_details[]"
                                                           placeholder="تفصیل <?php echo $x; ?>"
                                                           value="<?php echo $exp_details[$index]; ?>"
                                                           required class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_values[]" required
                                                           placeholder="رقم <?php echo $x; ?>"
                                                           value="<?php echo $exp_values[$index]; ?>"
                                                           onkeyup="getTotal(<?php echo $x ?>)"
                                                           autocomplete="off"
                                                           class="form-control currency form-control-sm bold"
                                                           id="exp_value<?php echo $x; ?>">
                                                </td>
                                            </tr>
                                            <?php
                                            $arrayNumber++;
                                            $x++;
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <div class="row gy-0">
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                                <input type="text" id="ser" class="form-control" disabled
                                                       value="<?php echo getAutoIncrement('r_home_exp'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                                <input type="text" id="userName" class="form-control bg-transparent"
                                                       required
                                                       value="<?php echo $userName; ?>" readonly tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="" class="input-group-text urdu">برانچ کانام</label>
                                                <input type="text" id="" name=""
                                                       class="form-control input-urdu bg-transparent"
                                                       required
                                                       value="<?php echo $branchName; ?>" readonly tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row gx-0 mt-4 justify-content-center">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label for="jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="jmaa_khaata_no" name="jmaa_khaata_no"
                                               class="form-control bg-transparent" required
                                               value="<?php echo $record['jmaa_khaata_no']; ?>">
                                        <small id="response1" class="text-danger urdu position-absolute top-0 left-0"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id1" name="jmaa_khaata_id"
                                           value="<?php echo $record['jmaa_khaata_id']; ?>">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label for="bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="bnaam_khaata_no" name="bnaam_khaata_no"
                                               class="form-control bg-transparent" required
                                               value="<?php echo $record['bnaam_khaata_no']; ?>">
                                        <small id="response2" class="text-danger urdu position-absolute top-0 left-0"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id2" name="bnaam_khaata_id"
                                           value="<?php echo $record['bnaam_khaata_id'] ?>">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly name="total_bill"
                                               value="<?php echo $record['total_bill']; ?>"
                                               class="form-control bold" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                                    <button type="submit" name="recordUpdate" id="recordSubmitFinal"
                                            class="btn btn-dark w-100 btn-icon-text">
                                        <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                        درستگی
                                    </button>
                                    <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                </div>
                            </div>
                            <?php if ($record['is_transfered'] == 0) { ?>
                                <!--<a onclick="deleteRecord(this)" data-url="home-exp-add"
                                   data-tbl="r_home_exp" id="<?php /*echo $record['id']; */?>"
                                   class="btn btn-danger mt-4 btn-icon-text float-end">
                                    <i class="btn-icon-prepend" data-feather="delete"></i>ختم کریں
                                </a>-->
                            <?php } ?>
                        </form>
                    <?php } else { ?>
                        <form action="" method="post">
                            <div class="row gx-0 gy-3 justify-content-center">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="bill_no" class="input-group-text urdu">بل نمبر</label>
                                        <input type="text" id="bill_no" name="bill_no" autofocus
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label class="input-group-text urdu" for="exp_date">بل تاریخ</label>
                                        <input name="exp_date" type="text" id="exp_date" class="form-control"
                                               value="<?php echo date('Y-m-d'); ?>"
                                               placeholder="بل تاریخ" data-input>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="bill_giver" class="input-group-text urdu">بل دینے والا
                                                نام</label>
                                            <input type="text" id="bill_giver" name="bill_giver"
                                                   class="form-control input-urdu" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2 mt-4">
                                <div class="col-md-9">
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
                                                       data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)"
                                                       tabindex="-1"
                                                       onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_names[]"
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
                                </div>
                                <div class="col-md-3">
                                    <div class="row gy-0">
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                                <input type="text" id="ser" class="form-control" disabled
                                                       value="<?php echo getAutoIncrement('r_home_exp'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                                <input type="text" id="userName" class="form-control bg-transparent"
                                                       required
                                                       value="<?php echo $userName; ?>" readonly tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="" class="input-group-text urdu">برانچ کانام</label>
                                                <input type="text" id="" name=""
                                                       class="form-control input-urdu bg-transparent"
                                                       required
                                                       value="<?php echo $branchName; ?>" readonly tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-0 mt-5 justify-content-center">
                                <div class="col-md-2 position-relative">
                                    <div class="input-group">
                                        <label for="jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="jmaa_khaata_no" name="jmaa_khaata_no"
                                               class="form-control bg-transparent" required>
                                    </div>
                                    <small id="response1"
                                           class="text-danger urdu position-absolute top-0 left-0"></small>
                                    <input type="hidden" id="khaata_id1" name="jmaa_khaata_id">
                                </div>
                                <div class="col-md-2 position-relative">
                                    <div class="input-group">
                                        <label for="bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="bnaam_khaata_no" name="bnaam_khaata_no"
                                               class="form-control bg-transparent" required>
                                        <small id="response2"
                                               class="text-danger urdu position-absolute top-0 left-0"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id2" name="bnaam_khaata_id">
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly name="total_bill"
                                               class="form-control bold" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button name="recordSubmit" id="recordSubmitFinal" type="submit"
                                            class="btn btn-primary btn-icon-text w-100">
                                        <i class="btn-icon-prepend" data-feather="check-square"></i>
                                        محفوظ کریں
                                    </button>
                                    <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="urdu">
                    <?php $jmaaName = $bnaamName = "";
                    if (isset($_GET['id'])) {
                        $jmaa_khaata_id = $record['jmaa_khaata_id'];
                        $bnaam_khaata_id = $record['bnaam_khaata_id'];
                        $jmaaName = getTableDataByIdAndColName('khaata', $record['jmaa_khaata_id'], 'khaata_name');
                        $bnaamName = getTableDataByIdAndColName('khaata', $record['bnaam_khaata_id'], 'khaata_name');
                    } ?>
                    <h5 class="bg-success bg-opacity-25 p-2">جمع کھاتہ نام</h5>
                    <p class="p-1 bold text-primary" id="jm_kh_tafseel"><?php echo $jmaaName; ?></p>
                    <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                    <p class="p-1 bold text-primary" id="bm_kh_tafseel"><?php echo $bnaamName; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>
    <script src="assets/js/input-repeator.js"></script>
    <script>
        transferToRoznamcha();
        $(document).on('keyup', "#jmaa_khaata_no", function (e) {
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
        $(document).on('keyup', "#bnaam_khaata_no", function (e) {
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
<?php if (isset($_POST['recordSubmit'])) {
    $serial = fetch('r_home_exp', array('branch_id' => $branchId));
    $branch_serial = mysqli_num_rows($serial);
    $branch_serial = $branch_serial + 1;
    $url = "home-exp-add";
    $exp_names = json_encode($_POST['exp_names'], JSON_UNESCAPED_UNICODE);
    $exp_details = json_encode($_POST['exp_details'], JSON_UNESCAPED_UNICODE);
    $exp_values = json_encode($_POST['exp_values']);
    $data = array(
        'user_id' => $userId,
        'username' => $userName,
        'branch_id' => $branchId,
        'branch_serial' => $branch_serial,
        'exp_date' => mysqli_real_escape_string($connect, $_POST['exp_date']),
        'bill_no' => mysqli_real_escape_string($connect, $_POST['bill_no']),
        'bill_giver' => mysqli_real_escape_string($connect, $_POST['bill_giver']),
        'exp_names' => $exp_names,
        'exp_details' => $exp_details,
        'exp_values' => $exp_values,
        'jmaa_khaata_no' => mysqli_real_escape_string($connect, $_POST['jmaa_khaata_no']),
        'jmaa_khaata_id' => mysqli_real_escape_string($connect, $_POST['jmaa_khaata_id']),
        'bnaam_khaata_no' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']),
        'bnaam_khaata_id' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']),
        'total_bill' => mysqli_real_escape_string($connect, $_POST['total_bill']),
        'created_at' => date('Y-m-d H:i:s')
    );

    $done = insert('r_home_exp', $data);
    if ($done) {
        message('success', $url, 'گھر کا خرچہ محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "home-exp-add?id=" . $hidden_id;
    $exp_names = json_encode($_POST['exp_names'], JSON_UNESCAPED_UNICODE);
    $exp_details = json_encode($_POST['exp_details'], JSON_UNESCAPED_UNICODE);
    $exp_values = json_encode($_POST['exp_values']);
    $data = array(
        //'branch_id' => $branchId,
        'exp_date' => mysqli_real_escape_string($connect, $_POST['exp_date']),
        'bill_no' => mysqli_real_escape_string($connect, $_POST['bill_no']),
        'bill_giver' => mysqli_real_escape_string($connect, $_POST['bill_giver']),
        'exp_names' => $exp_names,
        'exp_details' => $exp_details,
        'exp_values' => $exp_values,
        'jmaa_khaata_no' => mysqli_real_escape_string($connect, $_POST['jmaa_khaata_no']),
        'jmaa_khaata_id' => mysqli_real_escape_string($connect, $_POST['jmaa_khaata_id']),
        'bnaam_khaata_no' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']),
        'bnaam_khaata_id' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']),
        'total_bill' => mysqli_real_escape_string($connect, $_POST['total_bill']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    $done = update('r_home_exp', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'گھر کا خرچہ تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>