<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">کیٹیگری</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group wd-400 me-2 mb-2 mb-md-0">
                    <span class="input-group-text input-group-addon bg-transparent border-primary" data-toggle="">
                    <i class="btn-icon-prepend text-primary" data-feather="search"></i></span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="ٹیبل میں تلاش کریں (F2)">
        </div>
        <button type="button" class="btn btn-outline-primary btn-icon-text me-2 mb-2 mb-md-0"
                onclick="window.print();">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-printer btn-icon-prepend">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            پرنٹ لیں
        </button>
        <a href="category-add"
           class="btn btn-primary btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="file-plus"></i>
            کیٹیگری اندراج
        </a>
    </div>
</div>
<div class="row">
    <div class="col-md-4 d-print-none">
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
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text urdu">کیٹیگری نام</span>
                                <input type="text" id="c_name" name="c_name" class="form-control input-urdu" required
                                       autofocus value="<?php echo $catSingle["c_name"]; ?>">
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text urdu">کیٹیگری تفصیل</span>
                                <input type="text" id="c_details" name="c_details" class="form-control input-urdu"
                                       required value="<?php echo $catSingle["c_details"]; ?>">
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $catSingle["id"]; ?>" name="cat_id">
                        <button type="submit" name="catUpdate" class="btn btn-dark w-100 btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="edit-3"></i>
                            درستگی
                        </button>
                    </form>
                <?php } else { ?>
                    <form action="" method="post">
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text urdu">کیٹیگری نام</span>
                                <input type="text" id="c_name" name="c_name" class="form-control input-urdu" required
                                       autofocus>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="input-group">
                                <span class="input-group-text urdu">کیٹیگری تفصیل</span>
                                <input type="text" id="c_details" name="c_details" class="form-control input-urdu"
                                       required>
                            </div>
                        </div>
                        <button name="catSubmit" id="catSubmit" type="submit"
                                class="btn btn-success w-100 btn-icon-text">
                            <i class="btn-icon-prepend" data-feather="check-square"></i>
                            محفوظ کریں
                        </button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" id="">
                    <table class="table table-bordered overflow-hidden">
                        <thead>
                        <tr>
                            <th>کیٹیگری</th>
                            <th>کیٹیگری تفصیل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $cats = fetch('cats');
                        while ($cat = mysqli_fetch_assoc($cats)) { ?>
                            <tr>
                                <td class="small urdu-td">
                                    <a href="categories?id=<?php echo $cat['id']; ?>"
                                       class="btn-link pointer edit_data"> <?php echo $cat['c_name']; ?></a>
                                </td>
                                <td class="small urdu-td"><?php echo $cat['c_details']; ?></td>
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
$url = "categories";
?>
<?php if (isset($_POST['catSubmit'])) {
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
<!--<div class="modal fade" id="recordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" style="">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">کیٹیگری اندراج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <form method="post" id="insert_form">
                <div class="modal-body">
                    <div class="row g-0">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">کیٹیگری نام</span>
                                    <input type="text" id="b_name" name="b_name" class="form-control" required
                                           autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-text urdu">کیٹیگری تفصیل</span>
                                    <input type="text" id="b_address" name="b_address" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="cat_id" id="cat_id">
                <div class="modal-footer d-block">
                    <div class="row">
                        <div class="col-lg-3">
                            <button name="insert" id="insert" type="submit" class="btn btn-success w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="check-square"></i>
                                محفوظ کریں
                            </button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-primary w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="printer"></i>
                                پرنٹ کریں
                            </button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-dark w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                درستگی
                            </button>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-danger w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="trash-2"></i>
                                ختم کریں
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>-->
