<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">گودام لوڈنگ کرنے کا انٹری فارم</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="godam-loading-forms"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>گودام لوڈنگ کرنے کے فارم تفصیل</a>
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
                    $records = fetch('godam_loading_forms', array('id' => $id));
                    $record = mysqli_fetch_assoc($records);
                    ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="name" class="input-group-text urdu"> گودام کا نام</label>
                                        <input type="text" id="name" name="name" class="form-control input-urdu"
                                               required autofocus value="<?php echo $record['name']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="address" name="address"
                                               class="form-control input-urdu"
                                               required value="<?php echo $record['address']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="city" class="input-group-text urdu">شہر کا نام</label>
                                        <input type="text" id="city" name="city" class="form-control input-urdu"
                                               required value="<?php echo $record['city']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="munshi" class="input-group-text urdu">گودام کا منشی کا
                                            نام</label>
                                        <input type="text" id="munshi" name="munshi"
                                               class="form-control input-urdu"
                                               required value="<?php echo $record['munshi']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile1" class="input-group-text urdu">موبائل 1</label>
                                        <input type="text" id="mobile1" name="mobile1" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999"
                                               value="<?php echo $record['mobile1']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile2" class="input-group-text urdu">موبائل 2</label>
                                        <input type="text" id="mobile2" name="mobile2" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999"
                                               value="<?php echo $record['mobile2']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="phone" class="input-group-text urdu">فون نمبر</label>
                                        <input type="text" id="phone" name="phone" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999"
                                               value="<?php echo $record['phone']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">مزید رپورٹ</label>
                                        <input type="text" id="details" name="details"
                                               class="form-control input-urdu"
                                               required value="<?php echo $record['details']; ?>">
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
                            <div><?php echo addNew('godam-loading-form-add'); ?></div>
                        </div>
                        <!--<a onclick="deleteRecord(this)" data-url="godam-loading-form-add"
                           data-tbl="godam_loading_forms" id="<?php /*echo $record['id']; */ ?>"
                           class="btn btn-danger mt-4 btn-icon-text float-end">
                            <i class="btn-icon-prepend" data-feather="delete"></i>ختم
                        </a>-->
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="name" class="input-group-text urdu"> گودام کا نام</label>
                                        <input type="text" id="name" name="name" class="form-control input-urdu"
                                               required autofocus>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="address" name="address"
                                               class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
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
                                        <label for="munshi" class="input-group-text urdu">گودام کا منشی کا
                                            نام</label>
                                        <input type="text" id="munshi" name="munshi"
                                               class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile1" class="input-group-text urdu">موبائل 1</label>
                                        <input type="text" id="mobile1" name="mobile1" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile2" class="input-group-text urdu">موبائل 2</label>
                                        <input type="text" id="mobile2" name="mobile2" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="phone" class="input-group-text urdu">فون نمبر</label>
                                        <input type="text" id="phone" name="phone" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
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
<?php if (isset($_POST['recordSubmit'])) {
    $url = "godam-loading-form-add";
    $data = array(
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'munshi' => mysqli_real_escape_string($connect, $_POST['munshi']),
        'mobile1' => mysqli_real_escape_string($connect, $_POST['mobile1']),
        'mobile2' => mysqli_real_escape_string($connect, $_POST['mobile2']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'details' => mysqli_real_escape_string($connect, $_POST['details'])
    );
    $done = insert('godam_loading_forms', $data);
    if ($done) {
        message('success', $url, 'گودام لوڈنگ  کرنے کا انٹری فارم محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "godam-loading-form-add?id=".$hidden_id;
    $data = array(
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'munshi' => mysqli_real_escape_string($connect, $_POST['munshi']),
        'mobile1' => mysqli_real_escape_string($connect, $_POST['mobile1']),
        'mobile2' => mysqli_real_escape_string($connect, $_POST['mobile2']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'details' => mysqli_real_escape_string($connect, $_POST['details'])
    );
    $done = update('godam_loading_forms', $data, array('id' => $hidden_id));
    if ($done) {
        message('info', $url, 'گودام لوڈنگ  کرنے کا انٹری فارم تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

