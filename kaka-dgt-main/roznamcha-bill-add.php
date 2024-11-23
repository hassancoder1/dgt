<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نیا بل روزنامچہ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="roznamcha-bill.php"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>بل روزنامچہ
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-10">
        <?php if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('roznamchaas', array('r_id' => $id));
            $record = mysqli_fetch_assoc($records);
            $khaata_id = $record['khaata_id'];
            $khaata = getTableDataById('khaata', $khaata_id); ?>
            <div class="card">
                <div class="card-body py-2">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="row">
                        <div class="col-md-10 ">
                            <div class="row gx-0 mb-2 gy-1">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="khaata_no1" class="input-group-text">اکاونٹ نمبر</label>
                                            <input type="text" id="khaata_no1" class="form-control" readonly
                                                   value="<?php echo $khaata['khaata_no']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="c_name" class="input-group-text">کیٹیگری</label>
                                        <input type="text" id="c_name" class="form-control" readonly
                                               value="<?php echo getTableDataByIdAndColName('cats', $khaata['cat_id'], 'c_name'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="b_name" class="input-group-text">برانچ نام</label>
                                        <input type="text" id="b_name" class="form-control input-urdu" readonly
                                               value="<?php echo getTableDataByIdAndColName('branches', $khaata['branch_id'], 'b_name'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="khaata_name" class="input-group-text">کھاتہ نام</label>
                                        <input type="text" id="khaata_name" class="form-control" readonly
                                               value="<?php echo $khaata['khaata_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="comp_name" class="input-group-text">کمپنی نام</label>
                                        <input type="text" id="comp_name" class="form-control" readonly
                                               value="<?php echo $khaata['comp_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="business_name" class="input-group-text">کاروبار نام</label>
                                        <input type="text" id="business_name" class="form-control" readonly
                                               value="<?php echo $khaata['business_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <label for="address" class="input-group-text">کاروبار پتہ</label>
                                        <input type="text" id="address" class="form-control" readonly
                                               value="<?php echo $khaata['address']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="mobile" class="input-group-text">موبائل نمبر</label>
                                        <input type="text" id="mobile" class="form-control ltr" readonly
                                               value="<?php echo $khaata['mobile']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="whatsapp" class="input-group-text">واٹس ایپ</label>
                                        <input type="text" id="whatsapp" class="form-control ltr" readonly
                                               value="<?php echo $khaata['whatsapp']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="phone" class="input-group-text">فون نمبر</label>
                                        <input type="text" id="phone" class="form-control ltr" readonly
                                               value="<?php echo $khaata['phone']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="email" class="input-group-text">ای میل</label>
                                        <input type="email" id="email" class="form-control ltr" readonly
                                               value="<?php echo $khaata['email']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-0">
                                <div class="col-md-4 bg-info bg-opacity-10">
                                    <?php $roznamcha_no = getAutoIncrement('roznamchaas'); ?>
                                    <div class="input-group">
                                        <label class="input-group-text urdu">روزنامچہ سیریل نمبر</label>
                                        <input type="text" class="form-control bg-transparent" required
                                               value="<?php echo $roznamcha_no; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-md-4 bg-info bg-opacity-10">
                                    <div class="input-group">
                                        <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                        <input type="text" id="userName" class="form-control bg-transparent"
                                               required
                                               value="<?php echo $userName; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-md-4 bg-info bg-opacity-10">
                                    <div class="input-group">
                                        <label for="" class="input-group-text urdu">برانچ کانام</label>
                                        <input type="text" id="" name=""
                                               class="form-control input-urdu bg-transparent"
                                               required
                                               value="<?php echo $branchName; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <img id="khaata_image" src="<?php echo $khaata['image']; ?>" alt="image"
                                 class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row gx-0 gy-4">
                            <div class="col-lg-2">
                                <div class="input-group position-relative">
                                    <label for="khaata_no" class="input-group-text urdu">کھاتہ نمبر</label>
                                    <input type="text" id="khaata_no" name="khaata_no"
                                           class="form-control bg-transparent" required
                                           autofocus value="<?php echo $record['khaata_no']; ?>">
                                    <span id="response" class="text-danger urdu khaata-invalid"></span>
                                </div>
                                <input type="hidden" name="khaata_id" id="khaata_id"
                                       value="<?php echo $khaata_id; ?>">
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <label for="r_date" class="input-group-text urdu">تاریخ</label>
                                    <input id="r_date" name="r_date" readonly tabindex="-1"
                                           value="<?php echo $record['r_date']; ?>"
                                           type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="roznamcha_no" class="input-group-text urdu">روزنامچہ
                                            نمبر</label>
                                        <input type="text" id="roznamcha_no" name="roznamcha_no"
                                               class="form-control"
                                               required value="<?php echo $record['roznamcha_no']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="r_jins" class="input-group-text urdu">جنس</label>
                                        <input type="text" id="r_jins" name="r_jins" class="form-control input-urdu"
                                               required value="<?php echo $record['r_jins']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="r_name" class="input-group-text urdu">نام</label>
                                        <input type="text" id="r_name" name="r_name" class="form-control input-urdu"
                                               required value="<?php echo $record['r_name']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="r_no" class="input-group-text urdu">نمبر</label>
                                        <input type="text" id="r_no" name="r_no" class="form-control" required
                                               value="<?php echo $record['r_no']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <label for="bnaam_qty" class="input-group-text urdu">بنام تعداد</label>
                                    <input type="text" id="bnaam_qty" name="bnaam_qty"
                                           class="form-control currency" value="<?php echo $record['bnaam_qty']; ?>"
                                           required onkeyup="bnaamAndJmaa(this)">
                                    <label for="jmaa_qty" class="input-group-text urdu"> جمع تعداد</label>
                                    <input type="text" id="jmaa_qty" name="jmaa_qty"
                                           value="<?php echo $record['jmaa_qty']; ?>"
                                           class="form-control currency" required onkeyup="bnaamAndJmaa(this)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="per_price" class="input-group-text urdu">فی قیمت</label>
                                    <input type="text" id="per_price" name="per_price"
                                           value="<?php echo $record['per_price']; ?>"
                                           class="form-control currency" required onkeyup="bnaamAndJmaa(this)">
                                </div>
                            </div>
                            <script>
                                function bnaamAndJmaa(e) {
                                    var value = $(e).val();
                                    var bnaam_qty_input = $("#bnaam_qty");
                                    var jmaa_qty_input = $("#jmaa_qty");
                                    var per_price_input = $("#per_price");
                                    var jmaa_amount = 0;
                                    var bnaam_amount = 0;

                                    var bnaam_qty = bnaam_qty_input.val();
                                    var jmaa_qty = jmaa_qty_input.val();
                                    var per_price = per_price_input.val();

                                    jmaa_amount = Number(per_price) * Number(jmaa_qty);
                                    $("#jmaa_amount").val(jmaa_amount);
                                    bnaam_amount = Number(per_price) * Number(bnaam_qty);
                                    $("#bnaam_amount").val(bnaam_amount);

                                }
                            </script>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <label for="bnaam_amount" class="input-group-text urdu">بنام رقم</label>
                                    <input type="text" id="bnaam_amount" name="bnaam_amount"
                                           class="form-control currency"
                                           value="<?php echo $record['bnaam_amount']; ?>"
                                           required onkeyup="bnaamAndJmaa(this)">
                                    <label for="jmaa_amount" class="input-group-text urdu">جمع رقم</label>
                                    <input type="text" id="jmaa_amount" name="jmaa_amount"
                                           class="form-control currency"
                                           required onkeyup="bnaamAndJmaa(this)"
                                           value="<?php echo $record['jmaa_amount']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">تفصیل</label>
                                        <input type="text" id="details" name="details"
                                               class="form-control input-urdu"
                                               required value="<?php echo $record['details']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $record["r_id"]; ?>" name="hidden_id">
                        <?php if (Administrator()) { ?>
                            <button type="submit" name="recordUpdate" id="recordUpdate"
                                    class="btn btn-dark mt-4 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="edit-3"></i>درستگی
                            </button>
                        <?php } ?>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="card">
                <div class="card-body py-2">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="row">
                        <div class="col-md-10 ">
                            <div class="row gx-0 mb-2 gy-1">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="khaata_no1" class="input-group-text">اکاونٹ نمبر</label>
                                        <input type="text" id="khaata_no1" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="c_name" class="input-group-text">کیٹیگری</label>
                                        <input type="text" id="c_name" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="b_name" class="input-group-text">برانچ نام</label>
                                        <input type="text" id="b_name" class="form-control input-urdu" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="khaata_name" class="input-group-text">کھاتہ نام</label>
                                        <input type="text" id="khaata_name" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="comp_name" class="input-group-text">کمپنی نام</label>
                                        <input type="text" id="comp_name" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="business_name" class="input-group-text">کاروبار نام</label>
                                        <input type="text" id="business_name" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <label for="address" class="input-group-text">کاروبار پتہ</label>
                                        <input type="text" id="address" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="mobile" class="input-group-text">موبائل نمبر</label>
                                        <input type="text" id="mobile" class="form-control ltr" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="whatsapp" class="input-group-text">واٹس ایپ</label>
                                        <input type="text" id="whatsapp" class="form-control ltr" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="phone" class="input-group-text">فون نمبر</label>
                                        <input type="text" id="phone" class="form-control ltr" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="email" class="input-group-text">ای میل</label>
                                        <input type="email" id="email" class="form-control ltr" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-0">
                                <div class="col-md-4 bg-info bg-opacity-10">
                                    <?php $roznamcha_no = getAutoIncrement('roznamchaas'); ?>
                                    <div class="input-group">
                                        <label class="input-group-text urdu">روزنامچہ سیریل نمبر</label>
                                        <input type="text" class="form-control bg-transparent" required value="<?php echo $roznamcha_no; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-md-4 bg-info bg-opacity-10">
                                    <div class="input-group">
                                        <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                        <input type="text" id="userName" class="form-control bg-transparent" required
                                               value="<?php echo $userName; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-md-4 bg-info bg-opacity-10">
                                    <div class="input-group">
                                        <label for="" class="input-group-text urdu">برانچ کانام</label>
                                        <input type="text" id="" name="" class="form-control input-urdu bg-transparent"
                                               required value="<?php echo $branchName; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <img id="khaata_image" src="assets/images/others/logo-placeholder.png" alt="image"
                                 class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row gx-0 gy-4">
                            <div class="col-lg-2">
                                <div class="input-group position-relative">
                                    <label for="khaata_no" class="input-group-text urdu">کھاتہ نمبر</label>
                                    <input type="text" id="khaata_no" name="khaata_no"
                                           class="form-control bg-transparent" required
                                           autofocus>
                                    <small id="response" class="text-danger urdu khaata-invalid"></small>
                                </div>
                                <input type="hidden" name="khaata_id" id="khaata_id">
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <label for="r_date" class="input-group-text urdu">تاریخ</label>
                                    <input id="r_date" name="r_date" readonly tabindex="-1"
                                           value="<?php echo date('Y-m-d'); ?>"
                                           type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="roznamcha_no" class="input-group-text urdu">روزنامچہ نمبر</label>
                                        <input type="text" id="roznamcha_no" name="roznamcha_no" class="form-control"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="r_jins" class="input-group-text urdu">جنس</label>
                                        <input type="text" id="r_jins" name="r_jins" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="r_name" class="input-group-text urdu">نام</label>
                                        <input type="text" id="r_name" name="r_name" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="r_no" class="input-group-text urdu">نمبر</label>
                                        <input type="text" id="r_no" name="r_no" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="input-group">
                                    <label for="bnaam_qty" class="input-group-text urdu">بنام تعداد</label>
                                    <input type="text" id="bnaam_qty" name="bnaam_qty"
                                           class="form-control currency"
                                           required onkeyup="bnaamAndJmaa(this)">
                                    <label for="jmaa_qty" class="input-group-text urdu"> جمع تعداد</label>
                                    <input type="text" id="jmaa_qty" name="jmaa_qty"
                                           class="form-control currency" required onkeyup="bnaamAndJmaa(this)">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="per_price" class="input-group-text urdu">فی قیمت</label>
                                    <input type="text" id="per_price" name="per_price"
                                           class="form-control currency" required onkeyup="bnaamAndJmaa(this)">
                                </div>
                            </div>
                            <script>
                                function bnaamAndJmaa(e) {
                                    var value = $(e).val();
                                    var bnaam_qty_input = $("#bnaam_qty");
                                    var jmaa_qty_input = $("#jmaa_qty");
                                    var per_price_input = $("#per_price");
                                    var jmaa_amount = 0;
                                    var bnaam_amount = 0;

                                    var bnaam_qty = bnaam_qty_input.val();
                                    var jmaa_qty = jmaa_qty_input.val();
                                    var per_price = per_price_input.val();

                                    jmaa_amount = Number(per_price) * Number(jmaa_qty);
                                    $("#jmaa_amount").val(jmaa_amount);
                                    bnaam_amount = Number(per_price) * Number(bnaam_qty);
                                    $("#bnaam_amount").val(bnaam_amount);

                                }
                            </script>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <label for="bnaam_amount" class="input-group-text urdu">بنام رقم</label>
                                    <input type="text" id="bnaam_amount" name="bnaam_amount"
                                           class="form-control" required readonly>
                                    <label for="jmaa_amount" class="input-group-text urdu">جمع رقم</label>
                                    <input type="text" id="jmaa_amount" name="jmaa_amount" class="form-control"
                                           required readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">تفصیل</label>
                                        <input type="text" id="details" name="details" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button name="recordSubmit" id="recordSubmit" type="submit"
                                class="btn btn-primary btn-icon-text mt-4">
                            <i class="btn-icon-prepend" data-feather="check-square"></i>
                            محفوظ کریں
                        </button>

                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordSubmit'])) {
    $url = "roznamcha-bill-add";
    $serial = fetch('roznamchaas', array('branch_id' => $branchId, 'r_type' => 'bill'));
    $branch_serial = mysqli_num_rows($serial);
    $branch_serial = $branch_serial + 1;
    $bnaam_amount = mysqli_real_escape_string($connect, $_POST['bnaam_amount']);
    $jmaa_amount = mysqli_real_escape_string($connect, $_POST['jmaa_amount']);
    $r_type = "bill";
    //$cat_id = getTableDataByIdAndColName('khaata', $_POST['khaata_id'], 'cat_id');
    $k_data = fetch('khaata', array('id' => $_POST['khaata_id']));
    $k_datum = mysqli_fetch_assoc($k_data);
    $data = array(
        'cat_id' => $k_datum['cat_id'],
        'khaata_branch_id' => $k_datum['branch_id'],
        'r_type' => $r_type,
        'khaata_id' => mysqli_real_escape_string($connect, $_POST['khaata_id']),
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'branch_id' => $branchId,
        'branch_serial' => $branch_serial,
        'user_id' => $userId,
        'username' => $userName,
        'r_date' => date('Y-m-d'),
        'roznamcha_no' => mysqli_real_escape_string($connect, $_POST['roznamcha_no']),
        'r_jins' => mysqli_real_escape_string($connect, $_POST['r_jins']),
        'r_name' => mysqli_real_escape_string($connect, $_POST['r_name']),
        'r_no' => mysqli_real_escape_string($connect, $_POST['r_no']),
        'bnaam_qty' => mysqli_real_escape_string($connect, $_POST['bnaam_qty']),
        'jmaa_qty' => mysqli_real_escape_string($connect, $_POST['jmaa_qty']),
        'per_price' => mysqli_real_escape_string($connect, $_POST['per_price']),
        'bnaam_amount' => $bnaam_amount,
        'jmaa_amount' => $jmaa_amount,
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = insert('roznamchaas', $data);
    if ($done) {
        $url .= '?id=' . $connect->insert_id;
        message('success', $url, 'بل روزنامچہ محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "roznamcha-bill-add.php?id=" . $hidden_id;
    $bnaam_amount = mysqli_real_escape_string($connect, $_POST['bnaam_amount']);
    $jmaa_amount = mysqli_real_escape_string($connect, $_POST['jmaa_amount']);
    //$cat_id = getTableDataByIdAndColName('khaata', $_POST['khaata_id'], 'cat_id');
    $k_data = fetch('khaata', array('id' => $_POST['khaata_id']));
    $k_datum = mysqli_fetch_assoc($k_data);
    $data = array(
        'cat_id' => $k_datum['cat_id'],
        'khaata_branch_id' => $k_datum['branch_id'],
        'khaata_id' => mysqli_real_escape_string($connect, $_POST['khaata_id']),
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        //'r_date' => date('Y-m-d'),
        'roznamcha_no' => mysqli_real_escape_string($connect, $_POST['roznamcha_no']),
        'r_jins' => mysqli_real_escape_string($connect, $_POST['r_jins']),
        'r_name' => mysqli_real_escape_string($connect, $_POST['r_name']),
        'r_no' => mysqli_real_escape_string($connect, $_POST['r_no']),
        'bnaam_qty' => mysqli_real_escape_string($connect, $_POST['bnaam_qty']),
        'jmaa_qty' => mysqli_real_escape_string($connect, $_POST['jmaa_qty']),
        'per_price' => mysqli_real_escape_string($connect, $_POST['per_price']),
        'bnaam_amount' => $bnaam_amount,
        'jmaa_amount' => $jmaa_amount,
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    $done = update('roznamchaas', $data, array('r_id' => $hidden_id));
    if ($done) {
        message('info', $url, 'بل روزنامچہ تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>
<script>
    $(document).on('keyup', "#khaata_no", function (e) {
        transferToRoznamcha();
        fetchKhaata();
    });

    function fetchKhaata() {
        var khaata_no = $("#khaata_no").val();
        var khaata_id = $("#khaata_id");
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    khaata_id.val(response.messages['khaata_id']);
                    $("#khaata_no1").val(khaata_no);
                    $("#c_name").val(response.messages['c_name']);
                    $("#b_name").val(response.messages['b_name']);
                    $("#khaata_name").val(response.messages['khaata_name']);
                    $("#comp_name").val(response.messages['comp_name']);
                    $("#business_name").val(response.messages['business_name']);
                    $("#address").val(response.messages['address']);
                    $("#mobile").val(response.messages['mobile']);
                    $("#whatsapp").val(response.messages['whatsapp']);
                    $("#phone").val(response.messages['phone']);
                    $("#email").val(response.messages['email']);
                    $("#khaata_image").attr("src", response.messages['image']);
                    $("#recordSubmit").prop('disabled', false);
                    $("#recordUpdate").prop('disabled', false);
                    $("#response").text('');
                    transferToRoznamcha();
                }
                if (response.success === false) {
                    $("#response").text('کھاتہ');
                    $("#khaata_no1").val('');
                    $("#c_name").val('');
                    $("#b_name").val('');
                    $("#khaata_name").val('');
                    $("#comp_name").val('');
                    $("#business_name").val('');
                    $("#address").val('');
                    $("#mobile").val('');
                    $("#whatsapp").val('');
                    $("#phone").val('');
                    $("#email").val('');
                    $("#khaata_image").attr("src", 'assets/images/others/logo-placeholder.png');
                    khaata_id.val(0);
                    transferToRoznamcha();
                }
            }
        });
    }

    function transferToRoznamcha() {
        var msg = '';
        var khaata_id = $("#khaata_id").val();
        if (khaata_id <= 0) {
            $("#recordSubmit").prop('disabled', true);
            $("#recordUpdate").prop('disabled', true);
        } else {
            msg = '';
            $("#recordSubmit").prop('disabled', false);
            $("#recordUpdate").prop('disabled', false);
        }
    }
</script>

<!--<script>
    $("#recordSubmit").prop('disabled', true);
    /*get khaata values at first time*/
    var khaata_id = $("#khaata_id").val();
    var khaata_no1 = $("#khaata_no1").val();
    var c_name = $("#c_name").val();
    var b_name = $("#b_name").val();
    var khaata_name = $("#khaata_name").val();
    var comp_name = $("#comp_name").val();
    var business_name = $("#business_name").val();
    var address = $("#address").val();
    var mobile = $("#mobile").val();
    var whatsapp = $("#whatsapp").val();
    var phone = $("#phone").val();
    var email = $("#email").val();
    var khaata_image = $("#khaata_image").attr('src');
    /*get khaata values at first time*/

    var typingTimer;
    var doneTypingInterval = 1000;  //time in ms, 5 seconds for example
    var $input = $('#khaata_no');
    var khaata_no = '';
    $input.on('keyup', function (e) {
        clearTimeout(typingTimer);
        khaata_no = $('#khaata_no').val();
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
                    $("#khaata_id").val(response.messages['khaata_id']);
                    $("#khaata_no1").val(khaata_no);
                    $("#c_name").val(response.messages['c_name']);
                    $("#b_name").val(response.messages['b_name']);
                    $("#khaata_name").val(response.messages['khaata_name']);
                    $("#comp_name").val(response.messages['comp_name']);
                    $("#business_name").val(response.messages['business_name']);
                    $("#address").val(response.messages['address']);
                    $("#mobile").val(response.messages['mobile']);
                    $("#whatsapp").val(response.messages['whatsapp']);
                    $("#phone").val(response.messages['phone']);
                    $("#email").val(response.messages['email']);
                    $("#khaata_image").attr("src", response.messages['image']);
                    $("#recordSubmit").prop('disabled', false);
                    $("#recordUpdate").prop('disabled', false);
                    //$(':input[type="submit"]').prop('disabled', false);
                    $("#response").text('');
                }
                if (response.success === false) {
                    $("#recordSubmit").prop('disabled', true);
                    $("#recordUpdate").prop('disabled', true);
                    $("#response").text('کھاتہ نمبر درست نہیں ہے');
                    $("#khaata_id").val(khaata_id);
                    $("#khaata_no1").val(khaata_no1);
                    $("#c_name").val(c_name);
                    $("#b_name").val(b_name);
                    $("#khaata_name").val(khaata_name);
                    $("#comp_name").val(comp_name);
                    $("#business_name").val(business_name);
                    $("#address").val(address);
                    $("#mobile").val(mobile);
                    $("#whatsapp").val(whatsapp);
                    $("#phone").val(phone);
                    $("#email").val(email);
                    $("#khaata_image").attr("src", khaata_image);

                }
            }
        });
    }
</script>-->