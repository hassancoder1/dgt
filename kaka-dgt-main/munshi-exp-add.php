<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin ">
    <div>
        <h4 class="mb-3 mb-md-0 ">منشی خرچہ اندراج</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <?php echo backUrl('munshi-exp'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 position-relative">
        <div class="row gx-2">
            <div class="col-md-6">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <div class="card">
                    <div class="card-body">
                        <form method="post">
                            <div class="row gy-4 gx-0">
                                <div class="col-md-9 position-relative">
                                    <div class="input-group">
                                        <label for="staff_id" class="input-group-text urdu">ملازم کا نام</label>
                                        <select id="staff_id" name="staff_id" required autofocus
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled hidden>انتخاب کریں</option>
                                            <?php $staffs = fetch('staffs');
                                            while ($staff = mysqli_fetch_assoc($staffs)) {
                                                echo '<option value="' . $staff['id'] . '">' . $staff['staff_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseStaff" class="text-danger urdu position-absolute"
                                           style="top: 5px; left: 10px;"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="salary_year" class="input-group-text urdu">سال</label>
                                        <input id="salary_year" name="salary_year" class="form-control"
                                               value="<?php echo date('Y'); ?>"
                                               data-inputmask="'alias': 'datetime'"
                                               data-inputmask-inputformat="yyyy" inputmode="numeric">
                                        <label for="salary_month" class="input-group-text urdu">مہینہ</label>
                                        <input id="salary_month" name="salary_month" class="form-control"
                                               value="<?php echo date('m'); ?>"
                                               data-inputmask="'alias': 'datetime'"
                                               data-inputmask-inputformat="mm" inputmode="numeric">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="exp_date" class="input-group-text urdu">تاریخ</label>
                                        <input id="exp_date" name="exp_date" class="form-control" data-input required
                                               value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3 position-relative">
                                    <div class="input-group">
                                        <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_jmaa_khaata_no" name="jmaa_khaata_no"
                                               class="form-control" required value="<?php //echo $jmaa_khaata_no; ?>"
                                               onchange="transferToRoznamcha()">
                                        <small id="response1" class="text-danger urdu position-absolute"
                                               style="top: -20px;"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id1" name="jmaa_khaata_id">
                                </div>
                                <div class="col-lg-3 position-relative">
                                    <div class="input-group">
                                        <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_bnaam_khaata_no" name="bnaam_khaata_no"
                                               class="form-control" required onchange="transferToRoznamcha()"
                                               value="<?php //echo $bnaam_khaata_no; ?>">
                                        <small id="response2" class="text-danger urdu position-absolute"
                                               style="top: -20px;right:20px;"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id2" name="bnaam_khaata_id">
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="salary_amount" class="input-group-text urdu">رقم</label>
                                        <input id="salary_amount" name="salary_amount" class="form-control" readonly
                                               tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">مزید تفصیل</label>
                                        <input id="details" name="details" class="form-control"
                                               placeholder="ضروری نہیں">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button name="recordSubmitFinal" id="recordSubmitFinal" type="submit"
                                            class="btn btn-primary btn-icon-text">
                                        <i class="btn-icon-prepend" data-feather="check-square"></i>محفوظ کریں
                                    </button>
                                    <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                </div>
                                <div class="col-md-8 text-center">
                                    <span class="text-danger h4 urdu " id="isSalaryExistsMessage"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body p-2 urdu">
                        <div class="text-center">
                            <img id="staff_image" src="assets/images/others/logo-placeholder.png" alt="avatart"
                                 class="w-25 mb-2 img-fluid rounded-3 mt-n5">
                        </div>
                        <table class="table table-bordered">
                            <tr>
                                <td width="30%">برانچ</td>
                                <td id="staff_branch"></td>
                            </tr>
                            <tr>
                                <td>شہر</td>
                                <td id="staff_city"></td>
                            </tr>
                            <tr>
                                <td>لائسینس</td>
                                <td id="staff_license"></td>
                            </tr>
                            <tr>
                                <td>رقم</td>
                                <td id="staff_salary" class="text-success"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card ">
                    <div class="p-2 urdu">
                        <h5 class="bg-success bg-opacity-25 p-2">جمع کھاتہ نام</h5>
                        <p class="p-2 bold text-primary" id="jm_kh_tafseel"><?php //echo $jm_kh_tafseel; ?></p>
                        <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                        <p class="p-2 bold text-primary" id="bm_kh_tafseel"><?php //echo $bm_kh_tafseel; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordSubmitFinal'])) {
    $url = 'munshi-exp-add';
    $data = array(
        'exp_date' => mysqli_real_escape_string($connect, $_POST['exp_date']),
        'staff_id' => mysqli_real_escape_string($connect, $_POST['staff_id']),
        'salary_amount' => mysqli_real_escape_string($connect, $_POST['salary_amount']),
        'salary_month' => mysqli_real_escape_string($connect, $_POST['salary_month']),
        'salary_year' => mysqli_real_escape_string($connect, $_POST['salary_year']),
        'jmaa_khaata_no' => mysqli_real_escape_string($connect, $_POST['jmaa_khaata_no']),
        'jmaa_khaata_id' => mysqli_real_escape_string($connect, $_POST['jmaa_khaata_id']),
        'bnaam_khaata_no' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_no']),
        'bnaam_khaata_id' => mysqli_real_escape_string($connect, $_POST['bnaam_khaata_id']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'created_at' => date('Y-m-d H:i:s'),
        'user_id' => $userId,
        'username' => $userName
    );
    $done = insert('r_munshi_exp', $data);
    if ($done) {
        message('success', $url, 'منشی خرچہ اندراج ہو گیا۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم۔');
    }

} ?>
<script type="text/javascript">
    $(function () {
        staffAjax($('#staff_id').val());
    });
    $('#staff_id').change(function () {
        staffAjax($(this).val());
    });
    function staffAjax(staff_id=null) {
        //transferToRoznamcha();
        isSalaryExists();
        $.ajax({
            url: 'ajax/fetchSingleStaff.php',
            type: 'post',
            data: {staff_id: staff_id},
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.success === true) {
                    $("#staff_image").attr('src', response.messages['image']);
                    $("#staff_branch").text(response.messages['b_name']);
                    $("#staff_city").text(response.messages['city']);
                    $("#staff_license").text(response.messages['license_name']);
                    $("#staff_salary").text(response.messages['salary']);
                    $("#salary_amount").val(response.messages['salary']);
                    $("#responseStaff").text('');
                }
                if (response.success === false) {
                    $("#responseStaff").text('ملازم');
                }
            }
        });
    }
    function isSalaryExists() {
        var staff_id = $('#staff_id').val() ? $('#staff_id').val() : 0;
        var salary_year = $("#salary_year").val();
        var salary_month = $("#salary_month").val();
        if (staff_id > 0) {
            $.ajax({
                url: 'ajax/isSalaryExists.php',
                type: 'post',
                data: {staff_id: staff_id, salary_year: salary_year, salary_month: salary_month},
                dataType: 'json',
                success: function (response) {
                    console.log(response);
                    if (response.success === true) {
                        $("#isSalaryExistsMessage").text(response.messages);
                        document.querySelector('#staff_id').reset();
                    }
                    if (response.success === false) {
                        $("#isSalaryExistsMessage").text(response.messages);
                    }
                }
            });
        }
    }
</script>
<script>
    $("#recordSubmitFinal").prop('disabled', true);
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
                    $("#response1").text('جمع کھاتہ نمبر درست نہیں ہے');
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
                    $("#response2").text('جمع کھاتہ نمبر درست نہیں ہے');
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
        var total = $("#salary_amount").val();
        var staff_id = $('#staff_id').val();
        //alert('func called');
        if (khaata_id1 <= 0 || khaata_id2 <= 0 || total <= 0 || staff_id <= 0) {
            totalBillMsg.show();
            if (khaata_id1 <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = 'جمع کھاتہ؟';
            }
            if (khaata_id2 <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = 'بنام کھاتہ؟';
            }
            if (total <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = ' رقم؟ ';
            }
            if (staff_id <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = ' ملازم؟ ';
            }
            totalBillMsg.text(msg);
        } else {
            msg = '';
            $("#recordSubmitFinal").prop('disabled', false);
            totalBillMsg.hide();
            totalBillMsg.text(msg);
        }
    }
    document.querySelector('#staff_id').addEventListener('afterClose', function () {
        transferToRoznamcha();
    });
    document.querySelector('#staff_id').addEventListener('reset', function () {
        transferToRoznamcha();
    });
</script>
