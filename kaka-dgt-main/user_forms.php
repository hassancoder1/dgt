<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">یوزر فارم</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group wd-400 me-2 mb-2 mb-md-0">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle="">
                    <i class="btn-icon-prepend text-primary" data-feather="search"></i></span>
                <input id="tableFilter" type="text" class="form-control bg-transparent border-primary"
                       placeholder="ٹیبل میں تلاش کریں (F2)">
            </div>
            <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0"
                    onclick="window.print();">پرنٹ
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5 d-print-none">
            <div class="card">
                <div class="card-body">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <?php if (isset($_GET['id'])) {
                        $id = mysqli_real_escape_string($connect, $_GET['id']);
                        $records = fetch('forms', array('id' => $id));
                        $record = mysqli_fetch_assoc($records);
                        ?>
                        <form action="" method="post">
                            <div class="mb-4">
                                <div class="input-group">
                                    <label for="form_name" class="input-group-text urdu">فارم نام</label>
                                    <input type="text" id="form_name" name="form_name"
                                           class="form-control" required
                                           value="<?php echo $record['form_name']; ?>"
                                           autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <label for="form_details" class="input-group-text urdu">فارم تفصیل</label>
                                    <input type="text" id="form_details" name="form_details"
                                           class="form-control input-urdu" required
                                           value="<?php echo $record['form_details']; ?>">
                                </div>
                            </div>
                            <input type="hidden" name="form_id" value="<?php echo $record['id']; ?>">
                            <button name="formUpdate" type="submit" class="btn btn-dark  btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                درست کریں
                            </button>
                            <a href="user_forms" class="btn btn-inverse-dark btn-icon-text float-end">
                                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
                                واپسی
                            </a>
                        </form>
                    <?php } else { ?>
                        <form action="" method="post">
                            <div class="mb-4">
                                <div class="input-group">
                                    <label for="form_name" class="input-group-text urdu">فارم نام</label>
                                    <input type="text" id="form_name" name="form_name"
                                           class="form-control" required
                                           autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="input-group">
                                    <label for="form_details" class="input-group-text urdu">فارم تفصیل</label>
                                    <input type="text" id="form_details" name="form_details"
                                           class="form-control" required>
                                </div>
                            </div>
                            <!--<div class="mb-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu">فارم ویلیو</label>
                                    <div class="mt-3 ms-2 border ps-2 pt-2">
                                        <div class="form-check form-check-inline">
                                            <input checked name="form_value[]" class="form-check-input"
                                                   type="checkbox"
                                                   id="View" value="View">
                                            <label class="form-check-label" for="View">دیکھنا</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input checked name="form_value[]" class="form-check-input"
                                                   type="checkbox"
                                                   id="Create" value="Create">
                                            <label class="form-check-label" for="Create">اندراج</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input checked name="form_value[]" class="form-check-input"
                                                   type="checkbox"
                                                   id="Update" value="Update">
                                            <label class="form-check-label" for="Update">درستگی</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input checked name="form_value[]" class="form-check-input"
                                                   type="checkbox"
                                                   id="Delete" value="Delete">
                                            <label class="form-check-label" for="Delete">ختم</label>
                                        </div>
                                    </div>
                                </div>
                            </div>-->

                            <button name="formSubmit" type="submit"
                                    class="btn btn-success btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="check-square"></i>
                                محفوظ کریں
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" id="">
                        <table class="table table-bordered overflow-hidden">
                            <thead>
                            <tr>
                                <th>فارم نام</th>
                                <th>تفصیل</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $forms = fetch('forms');
                            while ($form = mysqli_fetch_assoc($forms)) { ?>
                                <tr>
                                    <td class="">
                                        <a href="user_forms?id=<?php echo $form['id']; ?>"
                                           class="btn-link pointer"> <?php echo $form['form_name']; ?></a>
                                    </td>
                                    <td class="small urdu-td"><?php echo $form['form_details']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php");
$url = "user_forms.php";
?>
<?php if (isset($_POST['formSubmit'])) {
    $data = array(
        'form_name' => mysqli_real_escape_string($connect, $_POST['form_name']),
        'form_details' => mysqli_real_escape_string($connect, $_POST['form_details'])
    );
    $done = insert('forms', $data);
    if ($done) {
        message('success', $url, 'فارم محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['formUpdate'])) {
    $form_id = $_POST['form_id'];
    $data = array(
        'form_name' => mysqli_real_escape_string($connect, $_POST['form_name']),
        'form_details' => mysqli_real_escape_string($connect, $_POST['form_details'])
    );
    $done = update('forms', $data, array('id' => $form_id));
    if ($done) {
        message('info', $url, 'فارم تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>