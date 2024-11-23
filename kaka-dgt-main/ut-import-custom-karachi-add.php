<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin_">
        <div>
            <h3 class="mb-3 mb-md-0 mt-n2 urdu-2">امپورٹ کسٹم کراچی انٹری</h3>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php if (isset($_GET['source']) && $_GET['source'] == 'surrender') {
                $backUrl = 'ut-surrender-bails';
            } else {
                $backUrl = 'ut-import-custom-karachi';
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
                    <h3 class="urdu-2 bg-primary text-white text-center">امپورٹ کسٹم کلئیرنس کراچی انٹری</h3>
                    <div class="card-body">
                        <?php if (empty($record['imp_json'])) {
                            $impCusJson = array(
                                'imp_cus_loading_date' => date('Y-m-d'),
                                'imp_cus_truck_no' => '',
                                'imp_cus_truck_name' => '',
                                'imp_cus_driver_name' => '',
                                'imp_cus_driver_mobile' => '',
                                'imp_cus_clearing_date' => date('Y-m-d'),
                                'imp_cus_gd_no' => '',
                                'imp_cus_seal_no' => '',
                                'imp_cus_report' => ''
                            );
                        } else {
                            $imp_json = json_decode($record['imp_json']);
                            $impCusJson = array(
                                'imp_cus_loading_date' => $imp_json->imp_cus_loading_date,
                                'imp_cus_truck_no' => $imp_json->imp_cus_truck_no,
                                'imp_cus_truck_name' => $imp_json->imp_cus_truck_name,
                                'imp_cus_driver_name' => $imp_json->imp_cus_driver_name,
                                'imp_cus_driver_mobile' => $imp_json->imp_cus_driver_mobile,
                                'imp_cus_clearing_date' => $imp_json->imp_cus_clearing_date,
                                'imp_cus_gd_no' => $imp_json->imp_cus_gd_no,
                                'imp_cus_seal_no' => $imp_json->imp_cus_seal_no,
                                'imp_cus_report' => $imp_json->imp_cus_report
                            );
                        } ?>
                        <form method="post">
                            <div class="row gx-0 gy-3">
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="imp_cus_loading_date" class="input-group-text urdu">لوڈنگ
                                            تاریخ</label>
                                        <input value="<?php echo $impCusJson['imp_cus_loading_date']; ?>" type="text"
                                               name="imp_cus_loading_date" autofocus class="form-control"
                                               id="imp_cus_loading_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-2 position-relative">
                                    <div class="input-group">
                                        <label for="imp_cus_truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                        <input type="text" id="imp_cus_truck_no" name="imp_cus_truck_no"
                                               class="form-control" required
                                               value="<?php echo $impCusJson['imp_cus_truck_no']; ?>">
                                    </div>
                                    <small id="responseTruck" class="text-danger urdu position-absolute"
                                           style="top: -20px; right: 20px;"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="imp_cus_truck_name" class="input-group-text urdu">ٹرک نام</label>
                                        <input type="text" id="imp_cus_truck_name" name="imp_cus_truck_name"
                                               class="form-control urdu-2 bold" required
                                               value="<?php echo $impCusJson['imp_cus_truck_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="imp_cus_driver_name" class="input-group-text urdu">ڈرائیور
                                            نام</label>
                                        <input type="text" id="imp_cus_driver_name" name="imp_cus_driver_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $impCusJson['imp_cus_driver_name']; ?>">
                                        <label for="imp_cus_driver_mobile" class="input-group-text urdu">موبائل</label>
                                        <input type="text" id="imp_cus_driver_mobile" name="imp_cus_driver_mobile"
                                               class="form-control ltr bold" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999"
                                               value="<?php echo $impCusJson['imp_cus_driver_mobile']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="imp_cus_clearing_date" class="input-group-text urdu">کلئیرنگ
                                            تاریخ</label>
                                        <input value="<?php echo $impCusJson['imp_cus_clearing_date']; ?>" type="text"
                                               name="imp_cus_clearing_date" class="form-control"
                                               id="imp_cus_clearing_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="imp_cus_gd_no" class="input-group-text urdu">جی ڈی نمبر</label>
                                        <input type="text" id="imp_cus_gd_no" name="imp_cus_gd_no"
                                               class="form-control" required
                                               value="<?php echo $impCusJson['imp_cus_gd_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="imp_cus_seal_no" class="input-group-text urdu">کسٹم سیل نمبر</label>
                                        <input type="text" id="imp_cus_seal_no" name="imp_cus_seal_no"
                                               class="form-control" required
                                               value="<?php echo $impCusJson['imp_cus_seal_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <label for="imp_cus_report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" id="imp_cus_report" name="imp_cus_report"
                                               class="form-control input-urdu" required
                                               value="<?php echo $impCusJson['imp_cus_report']; ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                            <div class="row mt-4">
                                <div class="col-4">
                                    <button type="submit" name="recordUpdate" id="recordUpdate"
                                            class="btn btn-dark btn-icon-text w-100">
                                        <i class="btn-icon-prepend" data-feather="edit-3"></i>محفوظ کریں
                                    </button>
                                </div>
                                <div class="col-8">
                                    <?php if (isset($_SESSION['response'])) {
                                        echo $_SESSION['response'];
                                        unset($_SESSION['response']);
                                    } ?>
                                </div>
                            </div>
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
    $url = "ut-import-custom-karachi-add?id=" . $hidden_id;
    $impCusArray = array(
        'imp_cus_loading_date' => mysqli_real_escape_string($connect, $_POST['imp_cus_loading_date']),
        'imp_cus_truck_no' => mysqli_real_escape_string($connect, $_POST['imp_cus_truck_no']),
        'imp_cus_truck_name' => mysqli_real_escape_string($connect, $_POST['imp_cus_truck_name']),
        'imp_cus_driver_name' => mysqli_real_escape_string($connect, $_POST['imp_cus_driver_name']),
        'imp_cus_driver_mobile' => mysqli_real_escape_string($connect, $_POST['imp_cus_driver_mobile']),
        'imp_cus_clearing_date' => mysqli_real_escape_string($connect, $_POST['imp_cus_clearing_date']),
        'imp_cus_gd_no' => mysqli_real_escape_string($connect, $_POST['imp_cus_gd_no']),
        'imp_cus_seal_no' => mysqli_real_escape_string($connect, $_POST['imp_cus_seal_no']),
        'imp_cus_report' => mysqli_real_escape_string($connect, $_POST['imp_cus_report'])
    );
    $impCusJsone = json_encode($impCusArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'imp_json' => $impCusJsone
    );
    $done = update('ut_bail_entries', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'امپورٹ کسٹم کراچی انٹری محفوظ ہوگئی ہے۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>