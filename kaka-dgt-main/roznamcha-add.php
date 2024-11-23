<?php $page_title = 'Roznamcha Entry';
$back_page_url = 'roznamcha';
include("header.php");
$pageURL = $url = "roznamcha-add";
$sr_no = getAutoIncrement('roznamchaas');
$user__name = $userName;
$branch__name = $branchName;
$action_hidden = 'insert';
$r_id_hidden = $bank_id = 0;
$dr_cr = 'dr';
$currency = 'AED';
$branch_serial = getBranchSerial($branchId, 'karobar');
$r_type = $khaata_no = $roznamcha_no = $r_no = $r_name = $amount = $details = $qty = $per_price = $operator = $mobile = '';
$r_date_payment = date('Y-m-d');
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $action_hidden = 'update';
    $r_id_hidden = $sr_no = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('roznamchaas', array('r_id' => $r_id_hidden));
    $record = mysqli_fetch_assoc($records);
    $user__name = $record['username'];
    $branch__name = branchName($record['branch_id']);
    $branch_serial = $record['branch_serial'];
    $r_type = $record['r_type'];
    $khaata_no = $record['khaata_no'];
    $roznamcha_no = $record['roznamcha_no'];
    $r_no = $record['r_no'];
    $r_name = $record['r_name'];
    //$dr_cr = $record['dr_cr'];
    if ($record['jmaa_amount'] > 0) {
        $amount = $record['jmaa_amount'];
        $dr_cr = 'dr';
        $qty = $record['jmaa_qty'];
    } else {
        $amount = $record['bnaam_amount'];
        $dr_cr = 'cr';
        $qty = $record['bnaam_qty'];
    }
    //$amount = $record['jmaa_amount'] > 0 ? $record['jmaa_amount'] : $record['bnaam_amount'];
    $details = $record['details'];
    $bank_id = $record['bank_id'];
    $r_date_payment = $record['r_date_payment'];

    $per_price = $record['per_price'];
    $operator = $record['operator'];
    $currency = $record['currency'];
    $mobile = $record['mobile'];
}
$topArray = array(
    array('heading' => 'جنرل سیریل', 'value' => $sr_no, 'id' => ''),
    array('heading' => 'برانچ سیریل', 'value' => $branch_serial, 'id' => 'branch_sr'),
    array('heading' => 'آئی ڈی نام', 'value' => strtoupper($user__name), 'id' => ''),
    //array('heading' => 'تاریخ', 'value' => date('d-M-Y'), 'id' => '')
); ?>
<div class="row">
    <div class="col-md-10">
        <form method="post" enctype="multipart/form-data" class=" table-form">
            <div class="d-flex justify-content-between flex-wrap gap-1 mb-5 urdu">
                <div>
                    <?php foreach ($topArray as $item) {
                        echo '<b>' . $item['heading'] . '</b> <span id="' . $item['id'] . '" class="text-muted">' . $item['value'] . '</span><br>';
                    } ?>
                    <div class="d-flex align-items-center">
                        <label for="branch_id" class="mb-0 bold">برانچ</label>
                        <select id="branch_id" name="branch_id" class="form-select bg-transparent -border-0"
                                style="min-width: 130px;">
                            <?php $branch_sql = "SELECT * FROM `branches` ";
                            if (!Administrator()) {
                                $branch_sql .= " WHERE id= '$branchId' ";
                            }
                            $branches = mysqli_query($connect, $branch_sql);
                            while ($b = mysqli_fetch_assoc($branches)) {
                                //$index_select = $static_type['type_name'] == $indexes[$index] ? 'selected' : '';
                                echo '<option value="' . $b['id'] . '">' . $b['b_name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>
                <div>
                    <?php $array_acc1 = array(array('label' => 'اکاؤنٹ نمبر', 'id' => 'khaata_no1'),
                        array('label' => 'کھاتہ نام', 'id' => 'khaata_name'),
                        array('label' => 'برانچ', 'id' => 'b_name'), array('label' => 'کیٹیگری', 'id' => 'c_name')); ?>
                    <?php foreach ($array_acc1 as $item) {
                        echo '<div class="small-3 mb-2"><b>' . $item['label'] . '</b> <span class="text-muted" id="' . $item['id'] . '"></span></div>';
                    } ?>
                </div>
                <div>
                    <?php $array_acc2 = array(
                        array('label' => 'کاروبار نام', 'id' => 'business_name'), array('label' => 'کاروبار پتہ', 'id' => 'address'), array('label' => 'کمپنی نام', 'id' => 'comp_name')
                    ); ?>
                    <?php foreach ($array_acc2 as $item) {
                        //echo '<b>' . $item['label'] . '</b><span class="text-muted" id="' . $item['id'] . '"></span><br>';
                        echo '<div class="small-3 mb-2"><b>' . $item['label'] . '</b> <span class="text-muted" id="' . $item['id'] . '"></span></div>';
                    } ?>
                </div>
                <div>
                    <?php $array_acc3 = array(
                        array('label' => 'موبائل نمبر', 'id' => 'mobile'),
                        array('label' => 'وٹس ایپ', 'id' => 'whatsapp'),
                        array('label' => 'فون نمبر', 'id' => 'phone'),
                        array('label' => 'ای میل', 'id' => 'email'),
                    ); ?>
                    <?php foreach ($array_acc3 as $item) {
                        //echo '<b>' . $item['label'] . '</b><span class="text-muted" dir="ltr" id="' . $item['id'] . '"></span><br>';
                        echo '<div class="small-3 mb-2"><b>' . $item['label'] . '</b> <span dir="ltr" class="text-muted" id="' . $item['id'] . '"></span></div>';
                    } ?>
                </div>
                <div>
                    <img id="khaata_image" src="assets/images/others/logo-placeholder.png"
                         class="img-fluid rounded shadow" alt="Image" width="100">
                </div>
            </div>
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card">
                <div class="card-body">
                    <div class="row gx-1 gy-5">
                        <div class="col-md-2">
                            <div class="input-group position-relative">
                                <label for="khaata_no" class="input-group-text">کھاتہ نمبر</label>
                                <input type="text" id="khaata_no" name="khaata_no" class="form-control bg-transparent"
                                       required autofocus value="<?php echo $khaata_no; ?>">
                                <small class="error-response top-0 urdu" id="response"></small>
                            </div>
                            <input type="hidden" name="khaata_id" id="khaata_id">
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="r_type" class="input-group-text">روزنامچہ</label>
                                <select id="r_type" name="r_type" class="form-select">
                                    <?php $static_types = fetch('static_types', array('type_for' => 'r_type'));
                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                        $r_select = $static_type['type_name'] == $r_type ? 'selected' : '';
                                        echo '<option ' . $r_select . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="r_date" class="input-group-text">تاریخ</label>
                                <input id="r_date" name="r_date"
                                    <?php echo Administrator() ? '' : ' readonly '; ?>
                                       value="<?php echo date('Y-m-d'); ?>"
                                       type="date" class="form-control">
                                <!--<input id="r_date" name="r_date" value="<?php /*echo date('Y-m-d'); */ ?>" type="date" class="form-control">-->
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="roznamcha_no" class="input-group-text">روزنامچہ نمبر</label>
                                <input value="<?php echo $roznamcha_no; ?>" type="text" id="roznamcha_no"
                                       name="roznamcha_no" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="r_name" class="input-group-text">نام</label>
                                <input value="<?php echo $r_name; ?>" type="text" id="r_name" name="r_name"
                                       class="form-control input-urdu" required>
                                <label for="r_no" class="input-group-text">نمبر</label>
                                <input value="<?php echo $r_no; ?>" type="text" id="r_no" name="r_no"
                                       class="form-control" required>
                            </div>
                        </div>
                        <!--<div class="col-md-2 bill-inputs cash-inputs bank-inputs">
                            <div class="input-group">
                                <label for="currency">Currency</label>
                                <select id="currency" name="currency" class="form-select" required>
                                    <option selected hidden disabled value="">Select</option>
                                    <?php /*$currencies = fetch('currencies');
                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                        $crr_sel = $crr['name'] == $currency ? 'selected' : '';
                                        echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                    } */ ?>
                                </select>
                            </div>
                        </div>-->
                        <div class="col-md-3 bank-inputs">
                            <div class="input-group">
                                <label for="bank_id" class="input-group-text">بینک نام</label>
                                <select id="bank_id" name="bank_id" class="form-select">
                                    <option value="" selected hidden>انتخاب</option>
                                    <?php $banks = fetch('banks');
                                    while ($bank = mysqli_fetch_assoc($banks)) {
                                        $bank_select = $bank['id'] == $bank_id ? 'selected' : '';
                                        echo '<option ' . $bank_select . ' value="' . $bank['id'] . '">' . $bank['bank_name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 bank-inputs">
                            <div class="input-group">
                                <label for=r_date_payment" class="input-group-text">ادائیگی تاریخ</label>
                                <input id="r_date_payment" name="r_date_payment"
                                       value="<?php echo $r_date_payment; ?>" type="date"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1 bill-inputs cash-inputs">
                            <div class="form-check">
                                <label class="form-check-label" for="is_qty">تعداد؟</label>
                                <input type="checkbox" class="form-check-input" id="is_qty" name="is_qty" value="1">
                            </div>
                        </div>
                        <div class="col-md-2 toggleQty bill-inputs-cash-inputs">
                            <div class="input-group">
                                <label for="qty" class="input-group-text">تعداد</label>
                                <input value="<?php echo $qty; ?>" type="text" id="qty" name="qty"
                                       class="form-control currency" autocomplete="off"
                                       onkeyup="calculateAmount(this)">
                            </div>
                        </div>
                        <div class="col-md-2 toggleQty bill-inputs-cash-inputs">
                            <div class="input-group">
                                <label for="per_price" class="input-group-text">فی قیمت</label>
                                <input value="<?php echo $per_price; ?>" type="text" id="per_price" name="per_price"
                                       class="form-control currency" autocomplete="off"
                                       onkeyup="calculateAmount(this)">
                            </div>
                        </div>
                        <div class="col-md-3 toggleQty bill-inputs-cash-inputs">
                            <div class="input-group">
                                <label for="operator" class="input-group-text">آپریٹر</label>
                                <select name="operator" class="form-select" id="operator" onchange="calculateAmount()">
                                    <?php $ops = array('ضرب (*)' => '*', 'تقسیم (/)' => '/');
                                    foreach ($ops as $opName => $op) {
                                        $op_sel = $operator == $op ? 'selected' : '';
                                        echo '<option value="' . $op . '">' . $opName . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 cash-inputs">
                            <div class="input-group">
                                <label for="mobile" class="input-group-text">موبائل</label>
                                <input value="<?php echo $mobile; ?>" type="text" id="mobile" name="mobile"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group ">
                                <label for="client" class="input-group-text">جمع/بنام</label>
                                <div class="form-control- d-flex border-bottom border-success">
                                    <?php $dr_cr_array = array(array('dr', 'جمع', 'text-success'), array('cr', 'بنام', 'text-danger'));
                                    foreach ($dr_cr_array as $item) {
                                        $checked_dr_cr = $item[0] == $dr_cr ? 'checked' : '';
                                        echo '<div class="form-check form-check-inline">';
                                        echo '<input class="form-check-input" type="radio" name="dr_cr" id="' . $item[0] . '" value="' . $item[0] . '" ' . $checked_dr_cr . '>';
                                        echo '<label class="form-check-label ' . $item[2] . '" for="' . $item[0] . '">' . $item[1] . '</label>';
                                        echo '</div>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="amount" class="input-group-text">رقم</label>
                                <input value="<?php echo $amount; ?>" type="text" id="amount" name="amount"
                                       class="form-control currency" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="details" class="input-group-text">تفصیل</label>
                                <input value="<?php echo $details; ?>" type="text" id="details" name="details"
                                       class="form-control input-urdu" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        <div>
                            <?php if ($action_hidden == "add" || Administrator()) {
                                echo '<button name="recordSubmit" id="recordSubmit" type="submit" class="btn btn-primary btn-icon-text"><i class="btn-icon-prepend" data-feather="check-square"></i>محفوظ کریں</button>';
                            } ?>
                        </div>
                        <div>
                            <?php echo $action_hidden == "update" ? addNew($pageURL) : '';
                            echo '<a class="btn btn-dark ms-2" href="print/roznamcha-single?r_id=' . base64_encode($r_id_hidden) . '&secret='.base64_encode('powered-by-upsol').'">پرنٹ</a>';
                            ?>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="<?php echo base64_encode($action_hidden); ?>">
                    <input type="hidden" name="r_id" value="<?php echo $r_id_hidden; ?>">
                </div>
            </div>
        </form>
    </div>
    <div class="col-md text-center">
        <?php echo backUrl('roznamcha'); ?>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordSubmit'])) {
    $type = 'danger';
    $msg = 'DB Error';
    $action = mysqli_real_escape_string($connect, $_POST['action']);
    $r_id = mysqli_real_escape_string($connect, $_POST['r_id']);

    $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id']);
    $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
    $r_type = mysqli_real_escape_string($connect, $_POST['r_type']);
    $branch_serial = getBranchSerial($branchId, $r_type);
    $k_data = fetch('khaata', array('id' => $_POST['khaata_id']));
    $k_datum = mysqli_fetch_assoc($k_data);

    $dr_cr = mysqli_real_escape_string($connect, $_POST['dr_cr']);
    $amount = mysqli_real_escape_string($connect, $_POST['amount']);
    $qty = mysqli_real_escape_string($connect, $_POST['qty']);
    if ($dr_cr == 'dr') {
        $bnaam_amount = $bnaam_qty = 0;
        $jmaa_amount = $amount;
        $jmaa_qty = $qty;
    } else {
        $jmaa_amount = $jmaa_qty = 0;
        $bnaam_amount = $amount;
        $bnaam_qty = $qty;
    }

    $data = array(
        'cat_id' => $k_datum['cat_id'],
        'r_type' => $r_type,
        'khaata_id' => $khaata_id,
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'branch_id' => $branch_id,
        'khaata_branch_id' => $k_datum['branch_id'],
        'branch_serial' => $branch_serial,
        'user_id' => $userId,
        'username' => $userName,
        'r_date' => $_POST['r_date'],
        'roznamcha_no' => mysqli_real_escape_string($connect, $_POST['roznamcha_no']),
        'r_name' => mysqli_real_escape_string($connect, $_POST['r_name']),
        'r_no' => mysqli_real_escape_string($connect, $_POST['r_no']),
        'bnaam_amount' => $bnaam_amount,
        'jmaa_amount' => $jmaa_amount,
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'jmaa_qty' => $jmaa_qty,
        'bnaam_qty' => $bnaam_qty,
        'per_price' => mysqli_real_escape_string($connect, $_POST['per_price']),
        'operator' => mysqli_real_escape_string($connect, $_POST['operator']),
        /*'currency' => mysqli_real_escape_string($connect, $_POST['currency']),*/
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),

        'bank_id' => mysqli_real_escape_string($connect, $_POST['bank_id']),
        'r_date_payment' => mysqli_real_escape_string($connect, $_POST['r_date_payment']),
    );
    if (base64_decode($action) == 'insert') {
        $data['user_id'] = $userId;
        $data['created_at'] = date('Y-m-d H:i:s');
        $done = insert('roznamchaas', $data);
        if ($done) {
            $url .= "?id=" . $connect->insert_id;
            $type = 'success';
            $msg = roznamchaName($r_type, true) . ' روزنامچہ محفوظ ہوگیا ہے۔ ';
        }
    } else {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('roznamchaas', $data, array('r_id' => $r_id));
        if ($done) {
            $url .= "?id=" . $r_id;
            $type = 'warning';
            $msg = roznamchaName($r_type, true) . ' روزنامچہ تبدیل ہوگیا ہے۔ ';
        }
    }
    message($type, $url, $msg);
} ?>
<script>
    disableButton('recordSubmit');
    $(document).on('keyup', "#khaata_no", function (e) {
        fetchKhaata();
    });
    fetchKhaata();

    function fetchKhaata() {
        let khaata_no = $("#khaata_no").val();
        let khaata_id = $("#khaata_id");
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    enableButton('recordSubmit');
                    khaata_id.val(response.messages['khaata_id']);
                    $("#khaata_no1").text(khaata_no);
                    $("#c_name").text(response.messages['name']);
                    $("#b_name").text(response.messages['b_name']);
                    $("#khaata_name").text(response.messages['khaata_name']);
                    $("#comp_name").text(response.messages['comp_name']);
                    $("#business_name").text(response.messages['business_name']);
                    $("#address").text(response.messages['address']);

                    $("#mobile").text(response.messages['mobile']);
                    $("#whatsapp").text(response.messages['whatsapp']);
                    $("#phone").text(response.messages['phone']);
                    $("#email").text(response.messages['email']);
                    $("#khaata_image").attr("src", response.messages['image']);
                    $("#recordSubmit").prop('disabled', false);
                    //$("#recordUpdate").prop('disabled', false);
                    $("#response").text('');
                }
                if (response.success === false) {
                    disableButton('recordSubmit');
                    $("#response").text('ٹھیک نہیں');
                    $("#khaata_no1").text('---');
                    $("#c_name").text('---');
                    $("#b_name").text('---');
                    $("#khaata_name").text('---');
                    $("#comp_name").text('---');
                    $("#business_name").text('---');
                    $("#address").text('---');
                    $("#mobile").text('---');
                    $("#whatsapp").text('---');
                    $("#phone").text('---');
                    $("#email").text('---');
                    $("#khaata_image").attr("src", 'assets/images/others/logo-placeholder.png');
                    khaata_id.val(0);
                }
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        toggleContainers();
        $("#r_type").change(function () {
            toggleContainers();
            getBranchSerial();
        });
        toggleQtyAndRequired();
        $("#is_qty").change(toggleQtyAndRequired);
    });
</script>
<script>
    $(document).ready(function () {
        //getBranchSerial();
        $('#branch_id').on('change', function () {
            getBranchSerial();
        });
    });

    function getBranchSerial() {
        let branch_id = $("#branch_id").val();
        let r_type = $("#r_type").val();
        $.ajax({
            url: 'ajax/getBranchSerial.php',
            type: 'post',
            data: {branch_id: branch_id, r_type: r_type},
            success: function (response) {
                $('#branch_sr').html(response);
            }
        });
    }
</script>
<script>
    function displayKhaataDetails(details) {
        var html = ''; // Initialize an empty string to store HTML

        if (details.indexes && details.vals) {
            var indexes = JSON.parse(details.indexes);
            var vals = JSON.parse(details.vals);

            if (Array.isArray(indexes) && Array.isArray(vals)) {
                var count = Math.min(indexes.length, vals.length);

                for (var i = 0; i < count; i++) {
                    var key = indexes[i];
                    var value = vals[i];
                    // Construct the HTML string
                    html += '<b class="text-dark">' + (key) + '</b>' + value + '<br>';
                }
            }
        }

        return html; // Return the constructed HTML string
    }

    function href_link2(key, value, text, condition, key2, param1, param2) {
        // Replace this with your implementation of href_link2
        return '<a href="' + key + '">' + text + '</a>';
    }

</script>
<script>
    function calculateAmount(e) {
        var qty = $("#qty").val();
        var per_price = $("#per_price").val();
        var amount = 0;
        let operator = $('#operator').find(":selected").val();
        if (operator === "/") {
            amount = Number(qty) / Number(per_price);
        } else {
            amount = Number(qty) * Number(per_price);
        }
        $("#amount").val(amount.toFixed(2));
    }
</script>
<script>
    function toggleQtyAndRequired() {
        var $toggleQty = $(".toggleQty");
        var $is_qty2 = $("#is_qty");
        if ($is_qty2.is(":checked")) {
            $toggleQty.show();
            $("#qty, #per_price, #operator").attr('required', true);
            /*if (getRoznamchaType() === 'Bill' || getRoznamchaType() === 'Cash') {

            }*/
            disableAmount(true);
            //$("#amount").prop('readonly', true);
        } else {
            $toggleQty.hide();
            $("#qty, #per_price, #operator").attr('required', false);
            disableAmount(false);
            //$("#amount").prop('readonly', false);
        }
    }

    function toggleContainers() {
        var r_type = $("#r_type").val();
        toggleQtyAndRequired();
        console.log(r_type)
        if (r_type !== '') {
            if (r_type === 'karobar') {
                $(".bank-inputs,.bill-inputs,.cash-inputs").hide();
            }
            if (r_type === 'bank') {
                $(".bill-inputs,.cash-inputs").hide();
                $(".bank-inputs").show();
            }
            if (r_type === 'bill') {
                $(".bank-inputs,.cash-inputs").hide();
                $(".bill-inputs").show();
            }
            /*if (r_type === 'Cash') {
                $(".bank-inputs,.bill-inputs").hide();
                $(".cash-inputs").show();
            }*/
        }
    }

    function disableAmount(yes = true) {
        if (yes) {
            $("#amount").prop('readonly', true);
        } else {
            $("#amount").prop('readonly', false);
        }
    }

    function getRoznamchaType() {
        return $('#r_type').find(":selected").val();
    }
</script>

