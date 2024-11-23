<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نئی کیٹیگری کا اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="categories"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
            کیٹیگری تفصیل
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-10 d-print-none">
        <div class="card">
            <div class="card-body">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <?php if (isset($_GET['id'])) {
                    $id = mysqli_real_escape_string($connect, $_GET['id']);
                    $catSingleQuery = fetch('cats', array('id' => $id));
                    $catSingle = mysqli_fetch_assoc($catSingleQuery);
                    ?>
                    <form action="" method="post">
                        <div class="row gx-0">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="c_name">کیٹیگری نام</label>
                                    <input type="text" id="c_name" name="c_name" class="form-control"
                                           required autofocus value="<?php echo $catSingle["c_name"]; ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="input-group">
                                    <label for="c_details" class="input-group-text urdu">کیٹیگری تفصیل</label>
                                    <input type="text" id="c_detail" name="c_details"
                                           placeholder="کیٹیگری تفصیل"
                                           class="form-control input-urdu" lang="ur"
                                           required value="<?php echo $catSingle["c_details"]; ?>">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $catSingle["id"]; ?>" name="cat_id">
                        <button type="submit" name="catUpdate" class="btn btn-dark mt-4 btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="edit-3"></i>
                            درستگی
                        </button>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="row gx-0">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text urdu">کیٹیگری نام</span>
                                    <input type="text" id="c_name" name="c_name" class="form-control"
                                           required
                                           autofocus>
                                </div>
                            </div>
                            <div class="col-12 col-md-8">
                                <div class="input-group">
                                    <label for="c_details" class="input-group-text urdu">کیٹیگری تفصیل</label>
                                    <input type="text" id="cu_detail" name="c_details"
                                           class="form-control input-urdu" placeholder="کیٹیگری تفصیل"
                                           required>
                                </div>
                            </div>
                        </div>
                        <button name="catSubmit" id="catSubmit" type="submit"
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
<?php if (isset($_POST['catSubmit'])) {
    $url = "category-add";
    $data = array(
        'c_name' => mysqli_real_escape_string($connect, $_POST['c_name']),
        'c_details' => mysqli_real_escape_string($connect, $_POST['c_details'])
    );
    $done = insert('cats', $data);
    if ($done) {
        message('success', $url, 'کیٹیگری محفوظ ہوگئی');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['catUpdate'])) {
    $url = "categories";
    $cat_id = $_POST['cat_id'];
    $data = array(
        'c_name' => mysqli_real_escape_string($connect, $_POST['c_name']),
        'c_details' => mysqli_real_escape_string($connect, $_POST['c_details'])
    );
    $done = update('cats', $data, array('id' => $cat_id));
    if ($done) {
        message('info', $url, 'کیٹیگری تبدیل ہوگئی');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

