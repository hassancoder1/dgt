<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin_">
        <div>
            <h3 class="mb-3 mb-md-0 mt-n2 urdu-2">افغان بارڈر کلئیرنس انٹری</h3>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php if (isset($_GET['source']) && $_GET['source'] == 'surrender') {
                $backUrl = 'ut-surrender-bails';
            } else {
                $backUrl = 'ut-afghan-border';
            }
            echo '<a href="' . $backUrl . '" class="btn btn-dark btn-icon-text p-1 pt-0 mt-n2">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>';
            ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('ut_bail_entries', array('id' => $id));
            $record = mysqli_fetch_assoc($records); ?>
            <div class="col-md-10 position-relative">
                <?php include("ut-bail-details.php"); ?>
                <div class="card mt-2">
                    <h3 class="urdu-2 bg-primary text-white text-center">افغان بارڈر کلئیرنس انٹری</h3>
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="card-body pt-1">
                        <?php if (empty($record['border_json'])) {
                            $borderJson = array(
                                'border_receiving_date' => date('Y-m-d'),
                                'border_unloading_date' => date('Y-m-d'),
                                'border_gd_no' => '',
                                'border_truck_no' => '',
                                'border_truck_name' => '',
                                'border_drive_name' => '',
                                'border_drive_mobile' => '',
                                'border_bardana_qty' => '',
                                'border_bardana_name' => '',
                                'border_total_wt' => '',
                                'border_saaf_wt' => '',
                                'border_report' => ''
                            );
                        } else {
                            $border_json = json_decode($record['border_json']);
                            $borderJson = array(
                                'border_receiving_date' => $border_json->border_receiving_date,
                                'border_unloading_date' => $border_json->border_unloading_date,
                                'border_gd_no' => $border_json->border_gd_no,
                                'border_truck_no' => $border_json->border_truck_no,
                                'border_truck_name' => $border_json->border_truck_name,
                                'border_drive_name' => $border_json->border_drive_name,
                                'border_drive_mobile' => $border_json->border_drive_mobile,
                                'border_bardana_qty' => $border_json->border_bardana_qty,
                                'border_bardana_name' => $border_json->border_bardana_name,
                                'border_total_wt' => $border_json->border_total_wt,
                                'border_saaf_wt' => $border_json->border_saaf_wt,
                                'border_report' => $border_json->border_report
                            );
                        } ?>
                        <form method="post">
                            <div class="row gx-0 gy-3">
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="border_receiving_date" class="input-group-text urdu">بارڈر پہنچ
                                            تاریخ</label>
                                        <input value="<?php echo $borderJson['border_receiving_date']; ?>"
                                               type="text" name="border_receiving_date" autofocus
                                               class="form-control" id="border_receiving_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="border_unloading_date" class="input-group-text urdu">ان لوڈنگ
                                            تاریخ</label>
                                        <input value="<?php echo $borderJson['border_unloading_date']; ?>"
                                               type="text" name="border_unloading_date"
                                               class="form-control" id="border_unloading_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="border_gd_no" class="input-group-text urdu">جی ڈی نمبر</label>
                                        <input type="text" id="border_gd_no" name="border_gd_no"
                                               class="form-control" required
                                               value="<?php echo $borderJson['border_gd_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="border_truck_no" class="input-group-text urdu">ٹرک
                                            نمبر</label>
                                        <input type="text" id="border_truck_no" name="border_truck_no"
                                               class="form-control" required
                                               value="<?php echo $borderJson['border_truck_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="border_truck_name" class="input-group-text urdu">ٹرک نام</label>
                                        <input type="text" id="border_truck_name" name="border_truck_name"
                                               class="form-control" required
                                               value="<?php echo $borderJson['border_truck_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="border_drive_name" class="input-group-text urdu">ڈرائیور
                                            نام</label>
                                        <input type="text" id="border_drive_name" name="border_drive_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $borderJson['border_drive_name']; ?>">
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="border_drive_mobile" class="input-group-text urdu">ڈرائیور
                                            موبائل</label>
                                        <input type="text" id="border_drive_mobile" name="border_drive_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999"
                                               value="<?php echo $borderJson['border_drive_mobile']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="border_bardana_qty" class="input-group-text urdu">باردانہ
                                            تعداد</label>
                                        <input type="text" id="border_bardana_qty" name="border_bardana_qty"
                                               class="form-control numberOnly" required
                                               value="<?php echo $borderJson['border_bardana_qty']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="border_bardana_name" class="input-group-text urdu">باردانہ
                                            نام</label>
                                        <input type="text" id="border_bardana_name" name="border_bardana_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $borderJson['border_bardana_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="border_total_wt" class="input-group-text urdu">ٹوٹل وزن</label>
                                        <input type="text" id="border_total_wt" name="border_total_wt"
                                               class="form-control numberOnly" required
                                               value="<?php echo $borderJson['border_total_wt']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="border_saaf_wt" class="input-group-text urdu">صاف وزن</label>
                                        <input type="text" id="border_saaf_wt" name="border_saaf_wt"
                                               class="form-control numberOnly" required
                                               value="<?php echo $borderJson['border_saaf_wt']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <label for="border_report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" id="border_report" name="border_report"
                                               class="form-control input-urdu" required
                                               value="<?php echo $borderJson['border_report']; ?>">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                            <button type="submit" name="recordUpdate" id="recordUpdate"
                                    class="btn btn-success mt-4 w-100 btn-icon-text">
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
            message('danger', 'ut-afghan-border', 'دوبارہ کوشش کریں');
        } ?>
    </div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "ut-afghan-border-add?id=" . $hidden_id;
    $borderArray = array(
        'border_receiving_date' => mysqli_real_escape_string($connect, $_POST['border_receiving_date']),
        'border_unloading_date' => mysqli_real_escape_string($connect, $_POST['border_unloading_date']),
        'border_gd_no' => mysqli_real_escape_string($connect, $_POST['border_gd_no']),
        'border_truck_no' => mysqli_real_escape_string($connect, $_POST['border_truck_no']),
        'border_truck_name' => mysqli_real_escape_string($connect, $_POST['border_truck_name']),
        'border_drive_name' => mysqli_real_escape_string($connect, $_POST['border_drive_name']),
        'border_drive_mobile' => mysqli_real_escape_string($connect, $_POST['border_drive_mobile']),
        'border_bardana_qty' => mysqli_real_escape_string($connect, $_POST['border_bardana_qty']),
        'border_bardana_name' => mysqli_real_escape_string($connect, $_POST['border_bardana_name']),
        'border_total_wt' => mysqli_real_escape_string($connect, $_POST['border_total_wt']),
        'border_saaf_wt' => mysqli_real_escape_string($connect, $_POST['border_saaf_wt']),
        'border_report' => mysqli_real_escape_string($connect, $_POST['border_report'])
    );
    $borderJsone = json_encode($borderArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'border_json' => $borderJsone
    );
    $done = update('ut_bail_entries', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'افغان بارڈر کلئیرنس انٹری محفوظ ہوگئی ہے۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>