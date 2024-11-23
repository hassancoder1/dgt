<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نیا کلئیرنگ ایجنٹ کا اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="clearing-agents"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
            کلئیرنگ ایجنٹ تفصیل
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12 d-print-none">
        <div class="card">
            <div class="card-body">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <?php if (isset($_GET['id'])) {
                    $id = mysqli_real_escape_string($connect, $_GET['id']);
                    $records = fetch('clearing_agents', array('id' => $id));
                    $record = mysqli_fetch_assoc($records);
                    ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">ایجنٹ نام</span>
                                    <input type="text" name="ca_name" class="form-control input-urdu"
                                           required autofocus value="<?php echo $record["ca_name"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">موبائل نمبر</span>
                                    <input type="text" id="ca_mobile" name="ca_mobile"
                                           class="form-control" required
                                           value="<?php echo $record["ca_mobile"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">ای میل</span>
                                    <input type="text" id="ca_email" name="ca_email"
                                           class="form-control" required
                                           value="<?php echo $record["ca_email"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">شہر کا نام</span>
                                    <input type="text" id="ca_city" name="ca_city"
                                           class="form-control input-urdu"
                                           required value="<?php echo $record["ca_city"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text urdu">لائسینس کا نام</span>
                                    <input value="<?php echo $record["ca_license"]; ?>" name="ca_license" required
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text urdu">لائسینس پتہ</span>
                                    <input value="<?php echo $record["ca_license_address"]; ?>"
                                           name="ca_license_address" required class="form-control input-urdu">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text urdu">لائسینس نمبر</span>
                                    <input value="<?php echo $record["ca_license_no"]; ?>" name="ca_license_no"
                                           required class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-text urdu">مزید رپورٹ</span>
                                    <input value="<?php echo $record["ca_details"]; ?>" name="ca_details"
                                           required class="form-control input-urdu">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $record["id"]; ?>" name="ca_id">
                        <button type="submit" name="recordUpdate" class="btn btn-dark mt-4 btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="edit-3"></i>
                            درستگی
                        </button>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">ایجنٹ نام</span>
                                    <input type="text" name="ca_name" class="form-control input-urdu"
                                           required autofocus>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">موبائل نمبر</span>
                                    <input type="text" id="ca_mobile" name="ca_mobile"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">ای میل</span>
                                    <input type="text" id="ca_email" name="ca_email"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <span class="input-group-text urdu">شہر کا نام</span>
                                    <input type="text" id="ca_city" name="ca_city"
                                           class="form-control input-urdu"
                                           required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text urdu">لائسینس کا نام</span>
                                    <input name="ca_license" required class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text urdu">لائسینس پتہ</span>
                                    <input name="ca_license_address" required class="form-control input-urdu">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text urdu">لائسینس نمبر</span>
                                    <input name="ca_license_no" required class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <span class="input-group-text urdu">مزید رپورٹ</span>
                                    <input name="ca_details" required class="form-control input-urdu">
                                </div>
                            </div>
                        </div>
                        <button name="recordSubmit" type="submit"
                                class="btn btn-primary btn-icon-text mt-4">
                            <i class="btn-icon-prepend" data-feather="check-square"></i>
                            محفوظ کریں
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordSubmit'])) {
    $url = "clearing-agent-add";
    $data = array(
        'ca_name' => mysqli_real_escape_string($connect, $_POST['ca_name']),
        'ca_mobile' => mysqli_real_escape_string($connect, $_POST['ca_mobile']),
        'ca_email' => mysqli_real_escape_string($connect, $_POST['ca_email']),
        'ca_city' => mysqli_real_escape_string($connect, $_POST['ca_city']),
        'ca_license' => mysqli_real_escape_string($connect, $_POST['ca_license']),
        'ca_license_address' => mysqli_real_escape_string($connect, $_POST['ca_license_address']),
        'ca_license_no' => mysqli_real_escape_string($connect, $_POST['ca_license_no']),
        'ca_details' => mysqli_real_escape_string($connect, $_POST['ca_details']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = insert('clearing_agents', $data);
    if ($done) {
        message('success', $url, 'کلئیرنگ ایجنٹ محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['recordUpdate'])) {
    $ca_id = $_POST['ca_id'];
    $url = "clearing-agent-add";
    $data = array(
        'ca_name' => mysqli_real_escape_string($connect, $_POST['ca_name']),
        'ca_mobile' => mysqli_real_escape_string($connect, $_POST['ca_mobile']),
        'ca_email' => mysqli_real_escape_string($connect, $_POST['ca_email']),
        'ca_city' => mysqli_real_escape_string($connect, $_POST['ca_city']),
        'ca_license' => mysqli_real_escape_string($connect, $_POST['ca_license']),
        'ca_license_address' => mysqli_real_escape_string($connect, $_POST['ca_license_address']),
        'ca_license_no' => mysqli_real_escape_string($connect, $_POST['ca_license_no']),
        'ca_details' => mysqli_real_escape_string($connect, $_POST['ca_details']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = update('clearing_agents', $data, array('id' => $ca_id));
    if ($done) {
        message('info', $url, 'کلئیرنگ ایجنٹ تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

