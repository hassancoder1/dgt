<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">امپورٹر کا اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="importers"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
            امپورٹر تفصیل
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
                    $records = fetch('importers', array('id' => $id));
                    $record = mysqli_fetch_assoc($records);
                    ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="name" class="input-group-text urdu"> نام</label>
                                        <input type="text" id="name" name="name" class="form-control input-urdu"
                                               required autofocus value="<?php echo $record['name']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input id="mobile" name="mobile" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               value="<?php echo $record['mobile']; ?>"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="email" class="input-group-text urdu">ای میل پتہ</label>
                                        <input id="email" name="email" class="form-control ltr"
                                               required data-inputmask="'alias': 'email'"
                                               value="<?php echo $record['email']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="city" class="input-group-text urdu">شہر کا نام</label>
                                        <input type="text" id="city" name="city"
                                               value="<?php echo $record['city']; ?>"
                                               class="form-control"
                                               required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_name" class="input-group-text urdu">امپورٹر کمپنی کا
                                            نام</label>
                                        <input type="text" id="comp_name" value="<?php echo $record['comp_name']; ?>"
                                               name="comp_name" class="form-control"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_mobile" class="input-group-text urdu">امپورٹر کمپنی کا
                                            موبائل</label>
                                        <input id="comp_mobile" name="comp_mobile"
                                               class="form-control ltr" value="<?php echo $record['comp_mobile']; ?>"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_email" class="input-group-text urdu">امپورٹر کمپنی کا
                                            ای میل</label>
                                        <input id="comp_email" name="comp_email"
                                               value="<?php echo $record['comp_email']; ?>"
                                               class="form-control ltr" data-inputmask="'alias': 'email'"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_city" class="input-group-text urdu">امپورٹر کمپنی شہر
                                            کا نام</label>
                                        <input type="text" id="comp_city" name="comp_city"
                                               class="form-control"
                                               required value="<?php echo $record['comp_city']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_address" class="input-group-text urdu">امپورٹر کمپنی کا
                                            پتہ</label>
                                        <input type="text" id="comp_address" name="comp_address"
                                               class="form-control" required=""
                                               value="<?php echo $record['comp_address']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_ntn" class="input-group-text urdu">امپورٹر کمپنی کا این
                                            ٹی
                                            این</label>
                                        <input type="text" id="comp_ntn" name="comp_ntn"
                                               value="<?php echo $record['comp_ntn']; ?>" class="form-control"
                                               required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_tax_no" class="input-group-text urdu">امپورٹر کمپنی سیل
                                            ٹیکسٹ
                                            نمبر</label>
                                        <input type="text" id="comp_tax_no" name="comp_tax_no"
                                               class="form-control" required=""
                                               value="<?php echo $record['comp_tax_no']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="kansani_name" class="input-group-text urdu"> کانسانے
                                            نام</label>
                                        <input type="text" id="kansani_name" name="kansani_name"
                                               class="form-control" required=""
                                               value="<?php echo $record['kansani_name']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="rebock_id" class="input-group-text urdu">ریبوک آئی
                                            ڈی</label>
                                        <input type="text" id="rebock_id" name="rebock_id"
                                               value="<?php echo $record['rebock_id']; ?>" class="form-control"
                                               required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="passport" class="input-group-text urdu">پاسپورٹ</label>
                                        <input type="text" id="passport" name="passport" class="form-control"
                                               required="" value="<?php echo $record['passport']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="rec_date" class="input-group-text urdu">تاریخ</label>
                                        <input name="rec_date" id="rec_date"
                                               value="<?php echo date('Y-m-d', strtotime($record['rec_date'])); ?>"
                                               type="text" class="form-control"
                                               placeholder="Select date" data-input>
                                        <span class="input-group-text input-group-addon" data-toggle><i
                                                data-feather="calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">مزید رپورٹ</label>
                                        <input type="text" value="<?php echo $record['details']; ?>"
                                               id="details" name="details" class="form-control ">
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
                            <div><?php echo addNew('importer-add'); ?></div>
                        </div>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0 gy-2">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="name" class="input-group-text urdu"> نام</label>
                                        <input type="text" id="name" name="name" class="form-control input-urdu"
                                               required autofocus>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input id="mobile" name="mobile" class="form-control ltr"
                                               required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="email" class="input-group-text urdu">ای میل پتہ</label>
                                        <input id="email" name="email" class="form-control ltr"
                                               required data-inputmask="'alias': 'email'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="city" class="input-group-text urdu">شہر کا نام</label>
                                        <input type="text" id="city" name="city" class="form-control"
                                               required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_name" class="input-group-text urdu">امپورٹر کمپنی کا
                                            نام</label>
                                        <input type="text" id="comp_name" name="comp_name"
                                               class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_mobile" class="input-group-text urdu">امپورٹر کمپنی کا
                                            موبائل</label>
                                        <input id="comp_mobile" name="comp_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_email" class="input-group-text urdu">امپورٹر کمپنی کا
                                            ای میل</label>
                                        <input id="comp_email" name="comp_email"
                                               class="form-control ltr" data-inputmask="'alias': 'email'"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_city" class="input-group-text urdu">امپورٹر کمپنی شہر
                                            کا نام</label>
                                        <input type="text" id="comp_city" name="comp_city"
                                               class="form-control"
                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_address" class="input-group-text urdu">امپورٹر کمپنی کا
                                            پتہ</label>
                                        <input type="text" id="comp_address" name="comp_address"
                                               class="form-control" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_ntn" class="input-group-text urdu">امپورٹر کمپنی کا این
                                            ٹی
                                            این</label>
                                        <input type="text" id="comp_ntn" name="comp_ntn" class="form-control"
                                               required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="comp_tax_no" class="input-group-text urdu">امپورٹر کمپنی سیل
                                            ٹیکسٹ
                                            نمبر</label>
                                        <input type="text" id="comp_tax_no" name="comp_tax_no"
                                               class="form-control" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="kansani_name" class="input-group-text urdu"> کانسانے
                                            نام</label>
                                        <input type="text" id="kansani_name" name="kansani_name"
                                               class="form-control" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="rebock_id" class="input-group-text urdu">ریبوک آئی
                                            ڈی</label>
                                        <input type="text" id="rebock_id" name="rebock_id" class="form-control"
                                               required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="passport" class="input-group-text urdu">پاسپورٹ</label>
                                        <input type="text" id="passport" name="passport" class="form-control"
                                               required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="rec_date" class="input-group-text urdu">تاریخ</label>
                                        <input name="rec_date" id="rec_date"
                                               value="<?php echo date('Y-m-d'); ?>"
                                               type="text" class="form-control"
                                               placeholder="Select date" data-input>
                                        <span class="input-group-text input-group-addon" data-toggle><i
                                                data-feather="calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="details" class="input-group-text urdu">مزید رپورٹ</label>
                                        <input type="text" id="details" name="details"
                                               class="form-control">
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
    $url = "importer-add";
    $data = array(
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'comp_mobile' => mysqli_real_escape_string($connect, $_POST['comp_mobile']),
        'comp_email' => mysqli_real_escape_string($connect, $_POST['comp_email']),
        'comp_city' => mysqli_real_escape_string($connect, $_POST['comp_city']),
        'comp_address' => mysqli_real_escape_string($connect, $_POST['comp_address']),
        'comp_ntn' => mysqli_real_escape_string($connect, $_POST['comp_ntn']),
        'comp_tax_no' => mysqli_real_escape_string($connect, $_POST['comp_tax_no']),
        'kansani_name' => mysqli_real_escape_string($connect, $_POST['kansani_name']),
        'rebock_id' => mysqli_real_escape_string($connect, $_POST['rebock_id']),
        'passport' => mysqli_real_escape_string($connect, $_POST['passport']),
        'rec_date' => $_POST['rec_date'],
        'details' => mysqli_real_escape_string($connect, $_POST['details'])
    );
    $done = insert('importers', $data);
    if ($done) {
        $url .= '?id=' . $connect->insert_id;
        message('success', $url, 'امپورٹر محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "importer-add?id=" . $hidden_id;
    $data = array(
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'comp_mobile' => mysqli_real_escape_string($connect, $_POST['comp_mobile']),
        'comp_email' => mysqli_real_escape_string($connect, $_POST['comp_email']),
        'comp_city' => mysqli_real_escape_string($connect, $_POST['comp_city']),
        'comp_address' => mysqli_real_escape_string($connect, $_POST['comp_address']),
        'comp_ntn' => mysqli_real_escape_string($connect, $_POST['comp_ntn']),
        'comp_tax_no' => mysqli_real_escape_string($connect, $_POST['comp_tax_no']),
        'kansani_name' => mysqli_real_escape_string($connect, $_POST['kansani_name']),
        'rebock_id' => mysqli_real_escape_string($connect, $_POST['rebock_id']),
        'passport' => mysqli_real_escape_string($connect, $_POST['passport']),
        'rec_date' => $_POST['rec_date'],
        'details' => mysqli_real_escape_string($connect, $_POST['details'])
    );
    $done = update('importers', $data, array('id' => $hidden_id));
    if ($done) {
        message('info', $url, 'امپورٹر تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

