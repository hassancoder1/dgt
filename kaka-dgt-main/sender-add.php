<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">مال بھیجنے والا اندراج</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="senders"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>مال بھیجنے والا
            تفصیل</a>
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
                    $records = fetch('senders', array('id' => $id));
                    $record = mysqli_fetch_assoc($records);
                    ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-4">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_owner_name" class="input-group-text urdu"> کمپنی مالک
                                            کا نام</label>
                                        <input type="text" id="comp_owner_name" name="comp_owner_name"
                                               class="form-control input-urdu" required
                                               autofocus value="<?php echo $record['comp_owner_name']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input id="mobile" name="mobile" class="form-control ltr"
                                               required value="<?php echo $record['mobile']; ?>"
                                               placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="whatsapp" class="input-group-text urdu">واٹس ایپ</label>
                                        <input id="whatsapp" name="whatsapp" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               value="<?php echo $record['whatsapp']; ?>"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="kansani_name" class="input-group-text urdu">کسائینی نام</label>
                                        <input type="text" id="kansani_name" name="kansani_name"
                                               class="form-control input-urdu"
                                               value="<?php echo $record['kansani_name']; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="city" class="input-group-text urdu">شہر کا نام</label>
                                        <input type="text" id="city"
                                               value="<?php echo $record['city']; ?>" name="city"
                                               class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_name" class="input-group-text urdu">کمپنی کا
                                            نام</label>
                                        <input type="text" id="comp_name" name="comp_name"
                                               class="form-control input-urdu"
                                               value="<?php echo $record['comp_name']; ?>"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="license_no" class="input-group-text urdu">لائسینس
                                            نمبر</label>
                                        <input type="text" id="license_no" name="license_no"
                                               class="form-control" required
                                               value="<?php echo $record['license_no']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="address" class="input-group-text urdu">کمپنی کا پتہ</label>
                                        <input type="text" id="address" name="address"
                                               class="form-control input-urdu"
                                               value="<?php echo $record['address']; ?>"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">مزید رپورٹ</label>
                                        <input type="text" id="details" name="details"
                                               class="form-control input-urdu"
                                               value="<?php echo $record['details']; ?>"
                                               required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                        <div class="d-flex mt-4 align-items-center justify-content-between">
                            <button type="submit" name="recordUpdate" class="btn btn-dark btn-sm btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                درستگی
                            </button>
                            <div><?php echo addNew('sender-add'); ?></div>
                        </div>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-4">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_owner_name" class="input-group-text urdu"> کمپنی مالک
                                            کا نام</label>
                                        <input type="text" id="comp_owner_name" name="comp_owner_name"
                                               class="form-control input-urdu" required
                                               autofocus>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input type="" id="mobile" name="mobile" class="form-control ltr"
                                               required
                                               placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="whatsapp" class="input-group-text urdu">واٹس ایپ</label>
                                        <input id="whatsapp" name="whatsapp" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="kansani_name" class="input-group-text urdu">کسائینی نام</label>
                                        <input type="text" id="kansani_name" name="kansani_name"
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="city" class="input-group-text urdu">شہر کا نام</label>
                                        <input type="text" id="city" name="city" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_name" class="input-group-text urdu">کمپنی کا
                                            نام</label>
                                        <input type="text" id="comp_name" name="comp_name"
                                               class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="license_no" class="input-group-text urdu">لائسینس
                                            نمبر</label>
                                        <input type="text" id="license_no" name="license_no"
                                               class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="address" class="input-group-text urdu">کمپنی کا پتہ</label>
                                        <input type="text" id="address" name="address"
                                               class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">مزید رپورٹ</label>
                                        <input type="text" id="details" name="details"
                                               class="form-control input-urdu"
                                               required>
                                    </div>
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
<?php
$today = date('Y-m-d H:i:s');
if (isset($_POST['recordSubmit'])) {
    $url = "sender-add";
    $data = array(
        'comp_owner_name' => mysqli_real_escape_string($connect, $_POST['comp_owner_name']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'whatsapp' => mysqli_real_escape_string($connect, $_POST['whatsapp']),
        'kansani_name' => mysqli_real_escape_string($connect, $_POST['kansani_name']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'license_no' => mysqli_real_escape_string($connect, $_POST['license_no']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'created_at' => $today
    );
    $done = insert('senders', $data);
    if ($done) {
        $url .= '?id=' . $connect->insert_id;
        message('success', $url, 'مال بھیجنے والا ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "sender-add?id=" . $hidden_id;
    $data = array(
        'comp_owner_name' => mysqli_real_escape_string($connect, $_POST['comp_owner_name']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'whatsapp' => mysqli_real_escape_string($connect, $_POST['whatsapp']),
        'kansani_name' => mysqli_real_escape_string($connect, $_POST['kansani_name']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'license_no' => mysqli_real_escape_string($connect, $_POST['license_no']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'created_at' => $today
    );
    $done = update('senders', $data, array('id' => $hidden_id));
    if ($done) {
        $insId = $connect->insert_id;
        $url .= '?id=' . $hidden_id;
        message('success', $url, 'مال بھیجنے والا تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

