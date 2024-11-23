<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0 mt-n3">اپ ٹرانزٹ بیل انٹری</h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="ut-bail-entries"
               class="btn btn-dark btn-icon-text p-1 pt-0 mt-n2">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>
        </div>
    </div>
    <div class="row gx-0">
        <div class="col-12">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
        </div>
        <?php if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('ut_bail_entries', array('id' => $id));
            $record = mysqli_fetch_assoc($records); ?>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body_ p-2">
                        <form method="post">
                            <?php include("ut-bail-entry-inc.php"); ?>
                            <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                            <button type="submit" name="recordUpdate" id="recordUpdate"
                                    class="btn btn-dark mt-4 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="edit-3"></i>درستگی
                            </button>
                            <?php if (Administrator()) {
                                if ($record['is_surrender'] == 0) { ?>
                                    <a onclick="deleteRecord(this)" data-url="ut-bail-entry-add"
                                       data-tbl="ut_bail_entries" id="<?php echo $record['id']; ?>"
                                       class="btn btn-danger mt-4 btn-icon-text float-end">
                                        <i class="btn-icon-prepend" data-feather="delete"></i>ختم کریں
                                    </a>
                                <?php }
                            } ?>
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
        <?php } else { ?>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body_ p-2">
                        <form method="post">
                            <div class="row gx-0 gy-4">
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="bill_no" class="input-group-text urdu">بل نمبر</label>
                                        <input type="text" id="bill_no" name="bill_no" autofocus
                                               class="form-control input-urdu" required>
                                        <label for="jins" class="input-group-text urdu">جنس</label>
                                        <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="consignee_name" class="input-group-text urdu">کنسائینی نام</label>
                                        <input type="text" id="consignee_name" name="consignee_name"
                                               class="form-control input-urdu" required>
                                        <label for="loading_city" class="input-group-text urdu">لوڈشہرکانام</label>
                                        <input type="text" id="loading_city" name="loading_city"
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="bardana_qty" class="input-group-text urdu">باردن تعداد</label>
                                        <input type="text" id="bardana_qty" name="bardana_qty"
                                               class="form-control currency" required>
                                        <label for="bardana_name" class="input-group-text urdu">باردن نام</label>
                                        <input type="text" id="bardana_name" name="bardana_name"
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="total_wt" class="input-group-text urdu">ٹوٹل وزن</label>
                                        <input type="text" id="total_wt" name="total_wt" class="form-control currency"
                                               required>
                                        <label for="saaf_wt" class="input-group-text urdu">صاف وزن</label>
                                        <input type="text" id="saaf_wt" name="saaf_wt" class="form-control currency"
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <label for="report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" id="report" name="report" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                                <!--sender /  receiver-->
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="sender_id" class="input-group-text urdu">مال بھیجنےوالا</label>
                                        <select id="sender_id" name="sender_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $senders = fetch('senders');
                                            while ($sender = mysqli_fetch_assoc($senders)) {
                                                echo '<option value="' . $sender['id'] . '">' . $sender['comp_owner_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseDTSender" class="text-danger urdu position-absolute top-0 left-0"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="sender_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="sender_address" name="sender_address"
                                               class="form-control input-urdu" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="sender_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input type="text" id="sender_mobile" name="sender_mobile"
                                               class="form-control ltr" readonly tabindex="-1">
                                        <label for="sender_whatsapp" class="input-group-text urdu">واٹس ایپ</label>
                                        <input type="text" id="sender_whatsapp" name="sender_whatsapp"
                                               class="form-control ltr" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="receiver_id" class="input-group-text urdu">مال وصول کرنے
                                            والا</label>
                                        <select id="receiver_id" name="receiver_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $receivers = fetch('receivers');
                                            while ($receiver = mysqli_fetch_assoc($receivers)) {
                                                echo '<option value="' . $receiver['id'] . '">' . $receiver['comp_owner_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseDTReceiver" class="text-danger urdu position-absolute"
                                           style="top: -10px; left: 20px;"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="receiver_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="receiver_address" name="receiver_address"
                                               class="form-control input-urdu" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="receiver_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="receiver_mobile" name="receiver_mobile"
                                               class="form-control ltr" readonly tabindex="-1">
                                        <label for="receiver_whatsapp" class="input-group-text urdu">واٹس ایپ</label>
                                        <input type="text" id="receiver_whatsapp" name="receiver_whatsapp"
                                               class="form-control ltr" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-2 position-relative">
                                    <div class="input-group">
                                        <label for="exporter_id" class="input-group-text urdu">ایکسپورٹر</label>
                                        <select id="exporter_id" name="exporter_id" required
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $exporters = fetch('exporters');
                                            while ($exporter = mysqli_fetch_assoc($exporters)) {
                                                echo '<option value="' . $exporter['id'] . '">' . $exporter['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseExporter" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 30px;"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="exp_comp_name" class="input-group-text urdu">کمپنی</label>
                                        <input type="text" id="exp_comp_name" name="exp_comp_name"
                                               readonly tabindex="-1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="exp_comp_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="exp_comp_address" name="exp_comp_address"
                                               readonly tabindex="-1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="exp_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="exp_mobile" name="exp_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                        <label for="exp_email" class="input-group-text urdu">ای میل</label>
                                        <input type="text" id="exp_email" name="exp_email" readonly tabindex="-1"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="input-group">
                                        <label for="exp_city" class="input-group-text urdu">شہر</label>
                                        <input type="text" id="exp_city" name="exp_city"
                                               readonly tabindex="-1" class="form-control" required>
                                    </div>
                                </div>
                                <!--export clearing agent-->
                                <div class="col-lg-3 position-relative">
                                    <div class="input-group">
                                        <label for="exp_ca_id" class="input-group-text urdu">ایکسپورٹ کلئیرنگ
                                            ایجنٹ</label>
                                        <select id="exp_ca_id" name="exp_ca_id" required
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $cas = fetch('clearing_agents');
                                            while ($ca = mysqli_fetch_assoc($cas)) {
                                                echo '<option value="' . $ca['id'] . '">' . $ca['ca_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseExpCa" class="text-danger urdu position-absolute"
                                           style="top: -5px; left: 10px;"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="exp_ca_license" class="input-group-text urdu">لائسینس نام</label>
                                        <input type="text" id="exp_ca_license" name="exp_ca_license"
                                               class="form-control " required readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="exp_ca_license_no" class="input-group-text urdu">لائسینس
                                            نمبر</label>
                                        <input type="text" id="exp_ca_license_no" name="exp_ca_license_no" readonly
                                               tabindex="-1"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="exp_ca_mobile" class="input-group-text urdu">موبائل</label>
                                        <input type="text" id="exp_ca_mobile" name="exp_ca_mobile"
                                               class="form-control ltr " required readonly tabindex="-1">
                                        <label for="exp_ca_email" class="input-group-text urdu">ای میل</label>
                                        <input type="text" id="exp_ca_email" name="exp_ca_email" readonly tabindex="-1"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-2 position-relative">
                                    <div class="input-group">
                                        <label for="importer_id" class="input-group-text urdu">امپورٹر</label>
                                        <select id="importer_id" name="importer_id" required
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $importers = fetch('importers');
                                            while ($importer = mysqli_fetch_assoc($importers)) {
                                                echo '<option value="' . $importer['id'] . '">' . $importer['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseImporter" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 30px;"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="imp_comp_name" class="input-group-text urdu">کمپنی</label>
                                        <input type="text" id="imp_comp_name" name="imp_comp_name"
                                               readonly tabindex="-1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="imp_comp_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="imp_comp_address" name="imp_comp_address"
                                               readonly tabindex="-1" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="imp_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="imp_mobile" name="imp_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                        <label for="imp_email" class="input-group-text urdu">ای میل</label>
                                        <input type="text" id="imp_email" name="imp_email" readonly tabindex="-1"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="input-group">
                                        <label for="imp_city" class="input-group-text urdu">شہر</label>
                                        <input type="text" id="imp_city" name="imp_city"
                                               readonly tabindex="-1" class="form-control" required>
                                    </div>
                                </div>
                                <!--import clearing agent-->
                                <div class="col-lg-3 position-relative">
                                    <div class="input-group">
                                        <label for="imp_ca_id" class="input-group-text urdu">امپورٹ کلئیرنگ
                                            ایجنٹ</label>
                                        <select id="imp_ca_id" name="imp_ca_id" required
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled hidden>انتخاب کریں</option>
                                            <?php $cas = fetch('clearing_agents');
                                            while ($ca = mysqli_fetch_assoc($cas)) {
                                                echo '<option value="' . $ca['id'] . '">' . $ca['ca_name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <small id="responseImpCa" class="text-danger urdu position-absolute"
                                           style="top: -5px; left: 10px;"></small>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="imp_ca_license" class="input-group-text urdu">لائسینس نام</label>
                                        <input type="text" id="imp_ca_license" name="imp_ca_license"
                                               class="form-control " required readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="imp_ca_license_no" class="input-group-text urdu">لائسینس
                                            نمبر</label>
                                        <input type="text" id="imp_ca_license_no" name="imp_ca_license_no" readonly
                                               tabindex="-1"
                                               class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="imp_ca_mobile" class="input-group-text urdu">موبائل</label>
                                        <input type="text" id="imp_ca_mobile" name="imp_ca_mobile"
                                               class="form-control ltr" required readonly tabindex="-1">
                                        <label for="imp_ca_email" class="input-group-text urdu">ای میل</label>
                                        <input type="text" id="imp_ca_email" name="imp_ca_email" readonly tabindex="-1"
                                               class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <button name="recordSubmit" id="recordSubmit" type="submit"
                                    class="btn btn-primary btn-icon-text mt-4">
                                <i class="btn-icon-prepend" data-feather="check-square"></i>
                                محفوظ کریں
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
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                    <input type="text" id="ser" class="form-control" disabled
                                           value="<?php echo getAutoIncrement('ut_bail_entries'); ?>">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                    <input type="text" id="userName" class="form-control bg-transparent"
                                           required
                                           value="<?php echo $userName; ?>" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="" class="input-group-text urdu">برانچ کانام</label>
                                    <input type="text" id="" name=""
                                           class="form-control urdu-2 bold bg-transparent"
                                           required
                                           value="<?php echo $branchName; ?>" readonly tabindex="-1">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php include("footer.php"); ?>
    <script src="assets/js/ut-bail-dropdowns.js"></script>
    <script>
        $('#recordSubmit').prop('disabled', true);
        function enableDisableBtn() {
            var receiver_mobile = $('#receiver_mobile').val();
            var sender_mobile = $('#sender_mobile').val();
            var exp_mobile = $('#exp_mobile').val();
            var exp_ca_mobile = $('#exp_ca_mobile').val();
            var imp_mobile = $('#imp_mobile').val();
            var imp_ca_mobile = $('#imp_ca_mobile').val();
            if (exp_mobile != '' && imp_mobile != '' && exp_ca_mobile != '' && imp_ca_mobile != '' && sender_mobile != '' && receiver_mobile != '') {
                $('#recordSubmit').prop('disabled', false);
            }
        }
    </script>
<?php if (isset($_POST['recordSubmit'])) {
    /*$serial = fetch('ut_bail_entries', array('branch_id' => $branchId));
    $branch_serial = mysqli_num_rows($serial);
    $branch_serial = $branch_serial + 1;*/
    $url = "ut-bail-entry-add";
    $srArray = array(
        'sender_id' => mysqli_real_escape_string($connect, $_POST['sender_id']),
        'sender_address' => mysqli_real_escape_string($connect, $_POST['sender_address']),
        'sender_mobile' => mysqli_real_escape_string($connect, $_POST['sender_mobile']),
        'sender_whatsapp' => mysqli_real_escape_string($connect, $_POST['sender_whatsapp']),
        'receiver_id' => mysqli_real_escape_string($connect, $_POST['receiver_id']),
        'receiver_address' => mysqli_real_escape_string($connect, $_POST['receiver_address']),
        'receiver_mobile' => mysqli_real_escape_string($connect, $_POST['receiver_mobile']),
        'receiver_whatsapp' => mysqli_real_escape_string($connect, $_POST['receiver_whatsapp'])
    );
    $sender_receiver = json_encode($srArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'user_id' => $userId,
        'username' => $userName,
        'branch_id' => $branchId,
        'loading_date' => date('Y-m-d'),
        'bill_no' => mysqli_real_escape_string($connect, $_POST['bill_no']),
        'consignee_name' => mysqli_real_escape_string($connect, $_POST['consignee_name']),
        'loading_city' => mysqli_real_escape_string($connect, $_POST['loading_city']),
        'jins' => mysqli_real_escape_string($connect, $_POST['jins']),
        'bardana_qty' => mysqli_real_escape_string($connect, $_POST['bardana_qty']),
        'bardana_name' => mysqli_real_escape_string($connect, $_POST['bardana_name']),
        'total_wt' => mysqli_real_escape_string($connect, $_POST['total_wt']),
        'saaf_wt' => mysqli_real_escape_string($connect, $_POST['saaf_wt']),
        'sender_receiver' => $sender_receiver,
        'importer_id' => mysqli_real_escape_string($connect, $_POST['importer_id']),
        'imp_ca_id' => mysqli_real_escape_string($connect, $_POST['imp_ca_id']),
        'exporter_id' => mysqli_real_escape_string($connect, $_POST['exporter_id']),
        'exp_ca_id' => mysqli_real_escape_string($connect, $_POST['exp_ca_id']),
        'report' => mysqli_real_escape_string($connect, $_POST['report']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = insert('ut_bail_entries', $data);
    if ($done) {
        $insertId = $connect->insert_id;
        $url .= '?id=' . $insertId;
        message('success', $url, 'اپ ٹرانزٹ بیل انٹری محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "ut-bail-entry-add?id=" . $hidden_id;
    $srArray = array(
        'sender_id' => mysqli_real_escape_string($connect, $_POST['sender_id']),
        'sender_address' => mysqli_real_escape_string($connect, $_POST['sender_address']),
        'sender_mobile' => mysqli_real_escape_string($connect, $_POST['sender_mobile']),
        'sender_whatsapp' => mysqli_real_escape_string($connect, $_POST['sender_whatsapp']),
        'receiver_id' => mysqli_real_escape_string($connect, $_POST['receiver_id']),
        'receiver_address' => mysqli_real_escape_string($connect, $_POST['receiver_address']),
        'receiver_mobile' => mysqli_real_escape_string($connect, $_POST['receiver_mobile']),
        'receiver_whatsapp' => mysqli_real_escape_string($connect, $_POST['receiver_whatsapp'])
    );
    $sender_receiver = json_encode($srArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'loading_date' => date('Y-m-d'),
        'bill_no' => mysqli_real_escape_string($connect, $_POST['bill_no']),
        'consignee_name' => mysqli_real_escape_string($connect, $_POST['consignee_name']),
        'loading_city' => mysqli_real_escape_string($connect, $_POST['loading_city']),
        'jins' => mysqli_real_escape_string($connect, $_POST['jins']),
        'bardana_qty' => mysqli_real_escape_string($connect, $_POST['bardana_qty']),
        'bardana_name' => mysqli_real_escape_string($connect, $_POST['bardana_name']),
        'total_wt' => mysqli_real_escape_string($connect, $_POST['total_wt']),
        'saaf_wt' => mysqli_real_escape_string($connect, $_POST['saaf_wt']),
        'sender_receiver' => $sender_receiver,
        'importer_id' => mysqli_real_escape_string($connect, $_POST['importer_id']),
        'imp_ca_id' => mysqli_real_escape_string($connect, $_POST['imp_ca_id']),
        'exporter_id' => mysqli_real_escape_string($connect, $_POST['exporter_id']),
        'exp_ca_id' => mysqli_real_escape_string($connect, $_POST['exp_ca_id']),
        'report' => mysqli_real_escape_string($connect, $_POST['report']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    $done = update('ut_bail_entries', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'اپ ٹرانزٹ بیل انٹری تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>