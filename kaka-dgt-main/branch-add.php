<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نئی برانچ کا اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="branches"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
            برانچ تفصیل
        </a>
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
                    $records = fetch('branches', array('id' => $id));
                    $record = mysqli_fetch_assoc($records);
                    ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label for="" class="input-group-text urdu">برانچ نام</label>
                                    <input type="text" id="b_name" name="b_name" class="form-control input-urdu"
                                           required value="<?php echo $record["b_name"]; ?>" autofocus>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label for="" class="input-group-text urdu">برانچ پتہ</label>
                                    <input type="text" id="b_address" name="b_address"
                                           class="form-control input-urdu" required
                                           value="<?php echo $record["b_address"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label for="" class="input-group-text urdu">برانچ انچارج نام</label>
                                    <input type="text" id="b_incharge" name="b_incharge"
                                           class="form-control input-urdu" required
                                           value="<?php echo $record["b_incharge"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                    <input type="text" id="b_mobile" name="b_mobile" class="form-control"
                                           required value="<?php echo $record["b_mobile"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_phone" class="input-group-text urdu">فون نمبر</label>
                                    <input id="b_phone" name="b_phone" required class="form-control"
                                           data-inputmask-alias="(+99) 9999-9999" inputmode="text"
                                           value="<?php echo $record["b_phone"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_email" class="input-group-text urdu">ای میل</label>
                                    <input type="email" id="b_email" name="b_email" class="form-control"
                                           required value="<?php echo $record["b_email"]; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_city" class="input-group-text urdu">شہر کا نام</label>
                                    <input type="text" id="b_city" name="b_city"
                                           class="form-control input-urdu" required
                                           value="<?php echo $record["b_city"]; ?>">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $record["id"]; ?>" name="branch_id">
                        <button type="submit" name="branchUpdate" class="btn btn-dark mt-4 btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="edit-3"></i>
                            درستگی
                        </button>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label for="b_name" class="input-group-text urdu">برانچ نام</label>
                                    <input type="text" id="b_name" name="b_name" class="form-control input-urdu"
                                           required autofocus>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label for="b_address" class="input-group-text urdu">برانچ پتہ</label>
                                    <input type="text" id="b_address" name="b_address"
                                           class="form-control input-urdu" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="input-group">
                                    <label for="b_incharge" class="input-group-text urdu">برانچ انچارج نام</label>
                                    <input type="text" id="b_incharge" name="b_incharge"
                                           class="form-control input-urdu" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                    <input id="b_mobile" name="b_mobile" class="form-control ltr"
                                           required data-inputmask-alias="(+99) 999-9999999">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_phone" class="input-group-text urdu">فون نمبر</label>
                                    <input id="b_phone" name="b_phone" required class="form-control ltr"
                                           data-inputmask-alias="(+99) 999-9999999">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_email" class="input-group-text urdu">ای میل</label>
                                    <input id="b_email" name="b_email" class="form-control ltr"
                                           required data-inputmask="'alias': 'email'">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="b_city" class="input-group-text urdu">شہر کا نام</label>
                                    <input type="text" id="b_city" name="b_city"
                                           class="form-control input-urdu" required>
                                </div>
                            </div>
                        </div>
                        <button name="branchSubmit" id="branchSubmit" type="submit"
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
<?php if (isset($_POST['branchSubmit'])) {
    $url = "branch-add";
    $data = array(
        'b_name' => mysqli_real_escape_string($connect, $_POST['b_name']),
        'b_address' => mysqli_real_escape_string($connect, $_POST['b_address']),
        'b_incharge' => mysqli_real_escape_string($connect, $_POST['b_incharge']),
        'b_mobile' => mysqli_real_escape_string($connect, $_POST['b_mobile']),
        'b_phone' => mysqli_real_escape_string($connect, $_POST['b_phone']),
        'b_email' => mysqli_real_escape_string($connect, $_POST['b_email']),
        'b_city' => mysqli_real_escape_string($connect, $_POST['b_city']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = insert('branches', $data);
    if ($done) {
        message('success', $url, 'برانچ محفوظ ہوگئی');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['branchUpdate'])) {
    $url = "branch-add";
    $branch_id = $_POST['branch_id'];
    $data = array(
        'b_name' => mysqli_real_escape_string($connect, $_POST['b_name']),
        'b_address' => mysqli_real_escape_string($connect, $_POST['b_address']),
        'b_incharge' => mysqli_real_escape_string($connect, $_POST['b_incharge']),
        'b_mobile' => mysqli_real_escape_string($connect, $_POST['b_mobile']),
        'b_phone' => mysqli_real_escape_string($connect, $_POST['b_phone']),
        'b_email' => mysqli_real_escape_string($connect, $_POST['b_email']),
        'b_city' => mysqli_real_escape_string($connect, $_POST['b_city']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = update('branches', $data, array('id' => $branch_id));
    if ($done) {
        message('info', $url, 'برانچ تبدیل ہوگئی');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

