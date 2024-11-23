<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n2">
    <div>
        <h4 class="mb-3 mb-md-0">بروکراندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <?php echo backUrl('brokers'); ?>
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
                    $records = fetch('brokers', array('id' => $id));
                    $record = mysqli_fetch_assoc($records); ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-4">
                            <div class="col-md-2 position-relative col-4">
                                <div class="input-group">
                                    <label for="khaata_no" class="input-group-text urdu">کھاتہ&nbsp;نمبر</label>
                                    <input type="text" id="khaata_no" name="khaata_no"
                                           class="form-control bg-transparent" required autofocus
                                           onkeyup="fetchKhaata()" value="<?php echo $record["khaata_no"]; ?>">
                                    <small id="response"
                                           class="text-danger urdu position-absolute top-0 left-0"></small>
                                </div>
                                <input type="hidden" id="khaata_id" name="khaata_id"
                                       value="<?php echo $record["khaata_id"]; ?>">
                            </div>
                            <div class="col-md-4 col-8">
                                <div class="input-group">
                                    <label for="name" class="input-group-text urdu">بروکر نام</label>
                                    <input type="text" id="name" name="name" class="form-control urdu-2"
                                           value="<?php echo $record["name"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="input-group">
                                    <label for="mobile" class="input-group-text urdu">موبائل نمبر</label>
                                    <input type="text" id="mobile" name="mobile"
                                           class="form-control ltr" value="<?php echo $record["mobile"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="input-group">
                                    <label for="email" class="input-group-text urdu">ای میل</label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           value="<?php echo $record["email"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="input-group">
                                    <label for="city" class="input-group-text urdu">شہر کا نام</label>
                                    <input type="text" id="city" name="city"
                                           class="form-control urdu-2"
                                           value="<?php echo $record["city"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-5 col-12">
                                <div class="input-group">
                                    <label for="address" class="input-group-text urdu">کاروبارپتہ</label>
                                    <input type="text" id="address" name="address" class="form-control urdu-2"
                                           value="<?php echo $record["address"]; ?>">
                                </div>
                            </div>
                            <div class="col-md-5 col-12">
                                <div class="input-group">
                                    <label for="more_details" class="input-group-text urdu">مزید رپورٹ</label>
                                    <input name="more_details" id="more_details" required
                                           class="form-control input-urdu"
                                           value="<?php echo $record["more_details"]; ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <input type="hidden" value="<?php echo $id; ?>" name="id">
                                <button type="submit" name="recordUpdate" id="recordSubmit"
                                        class="btn btn-dark btn-icon-text">
                                    <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                    درستگی
                                </button>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-4">
                            <div class="col-md-2 position-relative col-4">
                                <div class="input-group">
                                    <label for="khaata_no" class="input-group-text urdu">کھاتہ&nbsp;نمبر</label>
                                    <input type="text" id="khaata_no" name="khaata_no"
                                           class="form-control bg-transparent" required autofocus
                                           onkeyup="fetchKhaata()">
                                    <small id="response"
                                           class="text-danger urdu position-absolute top-0 left-0"></small>
                                </div>
                                <input type="hidden" id="khaata_id" name="khaata_id">
                            </div>
                            <div class="col-md-4 col-8">
                                <div class="input-group">
                                    <label for="name" class="input-group-text urdu">بروکر نام</label>
                                    <input type="text" id="name" name="name" class="form-control input-urdu">
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="input-group">
                                    <label for="mobile" class="input-group-text urdu">موبائل نمبر</label>
                                    <input type="text" id="mobile" name="mobile"
                                           class="form-control ltr">
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="input-group">
                                    <label for="email" class="input-group-text urdu">ای میل</label>
                                    <input type="email" id="email" name="email" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 col-6">
                                <div class="input-group">
                                    <label for="city" class="input-group-text urdu">شہر کا نام</label>
                                    <input type="text" id="city" name="city"
                                           class="form-control input-urdu">
                                </div>
                            </div>
                            <div class="col-md-5 col-12">
                                <div class="input-group">
                                    <label for="address" class="input-group-text urdu">کاروبارپتہ</label>
                                    <input type="text" id="address" name="address" class="form-control input-urdu">
                                </div>
                            </div>
                            <div class="col-md-5 col-12">
                                <div class="input-group">
                                    <label for="more_details" class="input-group-text urdu">مزید رپورٹ</label>
                                    <input name="more_details" id="more_details" required
                                           class="form-control input-urdu">
                                </div>
                            </div>
                            <div class="col-12">
                                <button name="recordSubmit" id="recordSubmit" type="submit"
                                        class="btn btn-primary btn-icon-text">
                                    <i class="btn-icon-prepend" data-feather="check-square"></i>
                                    محفوظ کریں
                                </button>
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="urdu-2 text-center">
                <h5 class="bg-success bg-opacity-25 p-2">کھاتہ نام</h5>
                <p class="p-1 bold text-primary" id="kh_tafseel"></p>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    fetchKhaata();
    $(document).on('keyup', "#khaata_no", function (e) {
        validateKhaata();
        fetchKhaata();
        isDuplicateBrokerKhaata();
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
                    $("#response").text('');
                    $("#name").val(response.messages['khaata_name']);
                    $("#mobile").val(response.messages['mobile']);
                    $("#address").val(response.messages['address']);
                    $("#city").val(response.messages['city']);
                    var res = '<span class="urdu mt-1">' + response.messages['khaata_name'] + '</span>'
                        + '<br /><span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>'
                        + '<img src="' + response.messages['image'] + '" class="img-fluid">';
                    $("#kh_tafseel").html(res);
                    validateKhaata();
                }
                if (response.success === false) {
                    $("#response").text('کھاتہ نمبر');
                    $("#kh_tafseel").text('');
                    khaata_id.val(0);
                    validateKhaata();
                }
            }
        });
    }
    function validateKhaata() {
        var khaata_id = $("#khaata_id").val();
        if (khaata_id <= 0) {
            $("#recordSubmit").prop('disabled', true);
        } else {
            $("#recordSubmit").prop('disabled', false);
        }
        //isDuplicateBrokerKhaata();
    }
    function isDuplicateBrokerKhaata() {
        var khaata_no = $("#khaata_no").val();
        var khaata_id = $("#khaata_id").val();
        $.ajax({
            url: 'ajax/isDuplicateBrokerKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no, khaata_id: khaata_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#response").text(' ڈپلیکیٹ ');
                    var strr = ' کھاتہ نمبر  پہلے ہی کسی اور بروکر میں استعمال ہو چکا ہے۔';
                    var res = '<span class="urdu mt-1">' + khaata_no + strr + '</span>';
                    $("#kh_tafseel").html(res);
                    $("#khaata_id").val(response.messages['khaata_id']);
                    validateKhaata();
                    //$("#recordSubmit").prop('disabled', true);
                }
                if (response.success === false) {
                    $("#khaata_id").val(khaata_id);
                    //$("#response").text('');
                    //$("#kh_tafseel").text('');
                    $("#recordSubmit").prop('disabled', false);
                }
            }
        });
    }
</script>
<?php if (isset($_POST['recordSubmit'])) {
    $url = "broker-add";
    $data = array(
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'khaata_id' => mysqli_real_escape_string($connect, $_POST['khaata_id']),
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'more_details' => mysqli_real_escape_string($connect, $_POST['more_details']),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = insert('brokers', $data);
    if ($done) {
        $insertId = $connect->insert_id;
        $url .= '?id=' . $insertId;
        message('success', $url, 'بروکر محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['recordUpdate'])) {
    $ca_id = $_POST['id'];
    $url = "broker-add?id=" . $ca_id;
    $data = array(
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'khaata_id' => mysqli_real_escape_string($connect, $_POST['khaata_id']),
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'more_details' => mysqli_real_escape_string($connect, $_POST['more_details']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    $done = update('brokers', $data, array('id' => $ca_id));
    if ($done) {
        message('info', $url, 'بروکر تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

