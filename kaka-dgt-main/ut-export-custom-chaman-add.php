<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin_">
        <div>
            <h3 class="mb-3 mb-md-0 mt-n2 urdu-2">ایکسپورٹ کسٹم کلئیرنس چمن انٹری</h3>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php if (isset($_GET['source']) && $_GET['source'] == 'surrender') {
                $backUrl = 'ut-surrender-bails';
            } else {
                $backUrl = 'ut-export-custom-chaman';
            }
            echo '<a href="' . $backUrl . '" class="btn btn-dark btn-icon-text p-1 pt-0 mt-n2">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>';
            ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('ut_bail_entries', array('id' => $id));
            $record = mysqli_fetch_assoc($records); ?>
            <div class="col-md-10 position-relative">
                <?php include("ut-bail-details.php"); ?>
                <div class="card mt-2">
                    <h3 class="urdu-2 bg-primary text-white text-center">ایکسپورٹ کسٹم کلئیرنس چمن انٹری</h3>
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="card-body pt-1">
                        <?php if (empty($record['exp_json'])) {
                            $expCusJson = array(
                                'exp_cus_receiving_date' => date('Y-m-d'),
                                'exp_cus_clearance_date' => date('Y-m-d'),
                                'exp_cus_gd_no' => '',
                                'exp_cus_scart_no' => '',
                                'exp_cus_report' => ''
                            );
                        } else {
                            $exp_json = json_decode($record['exp_json']);
                            $expCusJson = array(
                                'exp_cus_receiving_date' => $exp_json->exp_cus_receiving_date,
                                'exp_cus_clearance_date' => $exp_json->exp_cus_clearance_date,
                                'exp_cus_gd_no' => $exp_json->exp_cus_gd_no,
                                'exp_cus_scart_no' => $exp_json->exp_cus_scart_no,
                                'exp_cus_report' => $exp_json->exp_cus_report
                            );
                        } ?>
                        <form method="post">
                            <div class="row gx-0 gy-3">
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="exp_cus_receiving_date" class="input-group-text urdu">پہنچ
                                            تاریخ</label>
                                        <input value="<?php echo $expCusJson['exp_cus_receiving_date']; ?>"
                                               type="text" name="exp_cus_receiving_date" autofocus
                                               class="form-control" id="exp_cus_receiving_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="exp_cus_clearance_date" class="input-group-text urdu">کلئیرنس
                                            تاریخ</label>
                                        <input value="<?php echo $expCusJson['exp_cus_clearance_date']; ?>"
                                               type="text" name="exp_cus_clearance_date"
                                               class="form-control" id="exp_cus_clearance_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="exp_cus_gd_no" class="input-group-text urdu">جی ڈی نمبر</label>
                                        <input type="text" id="exp_cus_gd_no" name="exp_cus_gd_no"
                                               class="form-control" required
                                               value="<?php echo $expCusJson['exp_cus_gd_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="exp_cus_scart_no" class="input-group-text urdu">سکارٹ نمبر</label>
                                        <input type="text" id="exp_cus_scart_no" name="exp_cus_scart_no"
                                               class="form-control" required
                                               value="<?php echo $expCusJson['exp_cus_scart_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <label for="exp_cus_report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" id="exp_cus_report" name="exp_cus_report"
                                               class="form-control input-urdu" required
                                               value="<?php echo $expCusJson['exp_cus_report']; ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                            <button type="submit" name="recordUpdate" id="recordUpdate"
                                    class="btn btn-success mt-3 w-100 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="save"></i>محفوظ کریں
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="loading_date_" class="input-group-text urdu">لوڈنگ تاریخ</label>
                                    <input type="text" id="loading_date_" class="form-control" disabled
                                           value="<?php echo $record['loading_date']; ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                    <input type="text" id="ser" class="form-control" disabled
                                           value="<?php echo $id; ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                    <input type="text" id="userName" class="form-control bg-transparent"
                                           required
                                           value="<?php echo $record['username']; ?>" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="" class="input-group-text urdu">برانچ کانام</label>
                                    <input type="text" name="" readonly tabindex="-1"
                                           class="form-control urdu-2 bold bg-transparent"
                                           required
                                           value="<?php echo getTableDataByIdAndColName('branches', $record['branch_id'], 'b_name'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else {
            message('danger', 'ut-import-custom-karachi', 'دوبارہ کوشش کریں');
        } ?>
    </div>
<?php include("footer.php"); ?>
    <script>
        $("html, body").animate({scrollTop: $(document).height()}, 1000);
    </script>
    <script src="assets/js/ut-bail-dropdowns.js"></script>
<?php if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "ut-export-custom-chaman-add?id=" . $hidden_id;
    $expCusArray= array(
        'exp_cus_receiving_date' => mysqli_real_escape_string($connect, $_POST['exp_cus_receiving_date']),
        'exp_cus_clearance_date' => mysqli_real_escape_string($connect, $_POST['exp_cus_clearance_date']),
        'exp_cus_gd_no' => mysqli_real_escape_string($connect, $_POST['exp_cus_gd_no']),
        'exp_cus_scart_no' => mysqli_real_escape_string($connect, $_POST['exp_cus_scart_no']),
        'exp_cus_report' => mysqli_real_escape_string($connect, $_POST['exp_cus_report'])
    );
    $expCusJsone = json_encode($expCusArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'exp_json' => $expCusJsone
    );
    $done = update('ut_bail_entries', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'ایکسپورٹ کسٹم کلئیرنس چمن انٹری محفوظ ہوگئی ہے۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>