<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نئی بینک کا اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="banks.php"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
            بینک تفصیل
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
                    $records = fetch('banks', array('id' => $id));
                    $record = mysqli_fetch_assoc($records);
                    ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">بینک نام</label>
                                    <input type="text" name="bank_name" class="form-control" required autofocus
                                           value="<?php echo $record["bank_name"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu"> برانچ نام</label>
                                    <input type="text" name="branch_name" class="form-control" required
                                           value="<?php echo $record["branch_name"]; ?>">
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">اکاونٹ نام</label>
                                    <input type="text" name="acc_name" class="form-control" required
                                           value="<?php echo $record["acc_name"]; ?>">
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">اکاونٹ نمبر</label>
                                    <input type="text" name="acc_no" class="form-control" required
                                           value="<?php echo $record["acc_no"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label class="input-group-text urdu">برانچ کوڈ</label>
                                    <input type="text" name="branch_code" class="form-control" required
                                           value="<?php echo $record["branch_code"]; ?>">
                                </div>

                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <label class="input-group-text urdu">پتہ</label>
                                    <input type="text" name="bank_address" class="form-control input-urdu"
                                           required value="<?php echo $record["bank_address"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">موبائل نمبر</label>
                                    <input type="text" name="bank_mobile" class="form-control" required
                                           value="<?php echo $record["bank_mobile"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">فون نمبر</label>
                                    <input type="text" name="bank_phone" class="form-control" required
                                           value="<?php echo $record["bank_phone"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">ای میل</label>
                                    <input type="email" name="bank_email" class="form-control" required
                                           value="<?php echo $record["bank_email"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <label class="input-group-text urdu">مزید رپورٹ</label>
                                    <textarea name="bank_details" class="form-control input-urdu"
                                              required><?php echo $record["bank_details"]; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $record["id"]; ?>" name="bank_id">
                        <button type="submit" name="recordUpdate" class="btn btn-dark mt-4 btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="edit-3"></i>
                            درستگی
                        </button>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">بینک نام</label>
                                    <input type="text" name="bank_name" class="form-control" required autofocus>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu"> برانچ نام</label>
                                    <input type="text" name="branch_name" class="form-control" required>
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">اکاونٹ نام</label>
                                    <input type="text" name="acc_name" class="form-control" required="">
                                </div>

                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">اکاونٹ نمبر</label>
                                    <input type="text" name="acc_no" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label class="input-group-text urdu">برانچ کوڈ</label>
                                    <input type="text" name="branch_code" class="form-control" required>
                                </div>

                            </div>
                            <div class="col-lg-5">
                                <div class="input-group">
                                    <label class="input-group-text urdu">پتہ</label>
                                    <input type="text" name="bank_address" class="form-control input-urdu"
                                           required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">موبائل نمبر</label>
                                    <input type="text" name="bank_mobile" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">فون نمبر</label>
                                    <input type="text" name="bank_phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">ای میل</label>
                                    <input type="email" name="bank_email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-group">
                                    <label class="input-group-text urdu">مزید رپورٹ</label>
                                    <textarea name="bank_details" class="form-control input-urdu"
                                              required></textarea>
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
    $url = "bank-add";
    $data = array(
        'bank_name' => mysqli_real_escape_string($connect, $_POST['bank_name']),
        'branch_name' => mysqli_real_escape_string($connect, $_POST['branch_name']),
        'acc_name' => mysqli_real_escape_string($connect, $_POST['acc_name']),
        'acc_no' => mysqli_real_escape_string($connect, $_POST['acc_no']),
        'branch_code' => mysqli_real_escape_string($connect, $_POST['branch_code']),
        'bank_address' => mysqli_real_escape_string($connect, $_POST['bank_address']),
        'bank_mobile' => mysqli_real_escape_string($connect, $_POST['bank_mobile']),
        'bank_phone' => mysqli_real_escape_string($connect, $_POST['bank_phone']),
        'bank_email' => mysqli_real_escape_string($connect, $_POST['bank_email']),
        'bank_details' => mysqli_real_escape_string($connect, $_POST['bank_details']),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = insert('banks', $data);
    if ($done) {
        message('success', $url, 'بینک محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['recordUpdate'])) {
    $url = "banks";
    $bank_id = $_POST['bank_id'];
    $data = array(
        'bank_name' => mysqli_real_escape_string($connect, $_POST['bank_name']),
        'branch_name' => mysqli_real_escape_string($connect, $_POST['branch_name']),
        'acc_name' => mysqli_real_escape_string($connect, $_POST['acc_name']),
        'acc_no' => mysqli_real_escape_string($connect, $_POST['acc_no']),
        'branch_code' => mysqli_real_escape_string($connect, $_POST['branch_code']),
        'bank_address' => mysqli_real_escape_string($connect, $_POST['bank_address']),
        'bank_mobile' => mysqli_real_escape_string($connect, $_POST['bank_mobile']),
        'bank_phone' => mysqli_real_escape_string($connect, $_POST['bank_phone']),
        'bank_email' => mysqli_real_escape_string($connect, $_POST['bank_email']),
        'bank_details' => mysqli_real_escape_string($connect, $_POST['bank_details']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = update('banks', $data, array('id' => $bank_id));
    if ($done) {
        message('info', $url, 'بینک تبدیل ہوگئی');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

