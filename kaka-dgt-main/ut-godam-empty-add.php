<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h3 class="mb-3 mb-md-0 mt-n2 urdu-2">خالی کرنے گودام انٹری</h3>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="ut-godam-empty"
               class="btn btn-dark btn-icon-text mb-2 mb-md-0 pt-0">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>
        </div>
    </div>
    <div class="row">
        <?php if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('ut_bail_entries', array('id' => $id));
            $record = mysqli_fetch_assoc($records);
            if ($record['is_surrender'] == 1 && $record['qandhar_user_ids'] != '') { ?>
                <div class="col-md-10 position-relative">
                    <?php include("ut-bail-details.php"); ?>
                    <div class="card mt-2">
                        <h3 class="urdu-2 bg-primary text-white text-center">گودام خالی کرنے انٹری</h3>
                        <div class="card-body">
                            <form method="post">
                                <?php if (empty($record['godam_json'])) {
                                    $godamJson = array(
                                        'godam_receiving_date' => date('Y-m-d'),
                                        'godam_bardana_qty' => '',
                                        'godam_bardana_name' => '',
                                        'godam_balance' => '',
                                        'godam_total_wt' => '',
                                        'godam_saaf_wt' => '',
                                        'godam_loading_id' => '',
                                        'godam_report' => ''
                                    );
                                } else {
                                    $godam_json = json_decode($record['godam_json']);
                                    $godamJson = array(
                                        'godam_receiving_date' => $godam_json->godam_receiving_date,
                                        'godam_bardana_qty' => $godam_json->godam_bardana_qty,
                                        'godam_bardana_name' => $godam_json->godam_bardana_name,
                                        'godam_balance' => $godam_json->godam_balance,
                                        'godam_total_wt' => $godam_json->godam_total_wt,
                                        'godam_saaf_wt' => $godam_json->godam_saaf_wt,
                                        'godam_loading_id' => $godam_json->godam_loading_id,
                                        'godam_report' => $godam_json->godam_report
                                    );
                                } ?>
                                <div class="row gx-0 gy-4">
                                    <div class="col-lg-2">
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                            <label for="godam_receiving_date" class="input-group-text urdu">گودام پہنچ
                                                تاریخ</label>
                                            <input value="<?php echo $godamJson['godam_receiving_date']; ?>"
                                                   type="text" name="godam_receiving_date" autofocus
                                                   class="form-control" id="godam_receiving_date" required data-input>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="godam_bardana_qty" class="input-group-text urdu">باردانہ
                                                تعداد</label>
                                            <input value="<?php echo $godamJson['godam_bardana_qty']; ?>"
                                                   type="text" name="godam_bardana_qty" onkeyup="godamBalance()"
                                                   class="form-control currency" id="godam_bardana_qty" required>
                                        </div>
                                        <small class="ms-5 text-danger">
                                            <span class="urdu">بیل میں باردانہ تعداد:</span>
                                            <span class="bold"
                                                  id="old-bardana"><?php echo $record['bardana_qty']; ?></span>
                                        </small>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="godam_bardana_name" class="input-group-text urdu">باردانہ
                                                نام</label>
                                            <input value="<?php echo $godamJson['godam_bardana_name']; ?>"
                                                   type="text" name="godam_bardana_name"
                                                   class="form-control input-urdu" id="godam_bardana_name" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="godam_balance" class="input-group-text urdu">بیلنس</label>
                                            <input value="<?php echo $godamJson['godam_balance']; ?>"
                                                   type="text" name="godam_balance" readonly tabindex="-1"
                                                   class="form-control currency ltr" id="godam_balance" required>
                                        </div>
                                        <small class="text-danger urdu ms-4">بیلنس خود بخود کیلکولیٹ ہوگا۔</small>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="godam_total_wt" class="input-group-text urdu">ٹوٹل وزن</label>
                                            <input value="<?php echo $godamJson['godam_total_wt']; ?>"
                                                   type="text" name="godam_total_wt"
                                                   class="form-control currency" id="godam_total_wt" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="godam_saaf_wt" class="input-group-text urdu">صاف وزن</label>
                                            <input value="<?php echo $godamJson['godam_saaf_wt']; ?>"
                                                   type="text" name="godam_saaf_wt"
                                                   class="form-control currency" id="godam_saaf_wt" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 position-relative">
                                        <div class="input-group">
                                            <label for="godam_loading_id" class="input-group-text urdu">لوڈنگ
                                                گودام</label>
                                            <select id="godam_loading_id" name="godam_loading_id"
                                                    class="form-control border-bottom-0 agent-select">
                                                <option value="" hidden selected disabled>انتخاب</option>
                                                <?php $gl_id = $godam_json->godam_loading_id;
                                                $loadings = fetch('godam_loading_forms');
                                                while ($loading = mysqli_fetch_assoc($loadings)) {
                                                    $glSelected = $loading['id'] == $gl_id ? 'selected' : '';
                                                    echo '<option ' . $glSelected . ' value="' . $loading["id"] . '">' . $loading["name"] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <small id="responseGodamLoading" class="text-danger urdu position-absolute"
                                               style="top: 0px; left: 0;"></small>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <label for="gl_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                            <input id="gl_mobile" name="gl_mobile" type="text" class="form-control ltr"
                                                   disabled>
                                            <label for="gl_munshi" class="input-group-text urdu">منشی کانام</label>
                                            <input id="gl_munshi" name="gl_munshi" type="text" class="form-control"
                                                   disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="gl_address" class="input-group-text urdu">پتہ</label>
                                            <input id="gl_address" name="gl_address" type="text" disabled
                                                   class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <label for="godam_report" class="input-group-text urdu">رپورٹ</label>
                                            <input type="text" id="godam_report" name="godam_report"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $godamJson['godam_report']; ?>">
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                                    <div class="col-lg-2">
                                        <button type="submit" name="recordUpdate" id="recordUpdate"
                                                class="btn btn-primary btn-icon-text w-100">
                                            <i class="btn-icon-prepend" data-feather="edit-3"></i>محفوظ کریں
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <?php if (!empty($record['godam_json'])) {
                        $c_b_b = $record['transfer_from_godam'] == 1 ? 'checked' : '';
                        $w_l_g = $record['transfer_from_godam'] == 2 ? 'checked' : ''; ?>
                        <div class="card mt-2">
                            <!--<p class="bg-dark bg-opacity-25 urdu py-2 text-center">ٹرانسفر کریں</p>-->
                            <div class="card-body">
                                <form method="post" class="d-inline"
                                      onsubmit="return confirm('ٹرانسفر کرنے کے بعد آپ تبدیل نہیں کر سکتے۔ پہلے اچھی طرح تسلی کر لیں۔');">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <div class="form-check form-check-inline form-switch">
                                                <input type="radio" class="form-check-input" name="transfer_from_godam"
                                                       id="wb1" value="1" required <?php echo $c_b_b; ?>>
                                                <label class="form-check-label mt-n4" for="wb1">
                                                    <span>کمیشن بارڈر بل</span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline form-switch">
                                                <input type="radio" class="form-check-input" name="transfer_from_godam"
                                                       id="wb2" value="2" required <?php echo $w_l_g; ?>>
                                                <label class="form-check-label mt-n4" for="wb2">واپسی لوڈنگ
                                                    گودام</label>
                                            </div>
                                            <input type="hidden" name="ut_bail_id_t" value="<?php echo $id; ?>">
                                            <?php if ($record['transfer_from_godam'] == 0) { ?>
                                                <button type="submit" name="transferFromGodamSubmit"
                                                        id="transferFromGodamSubmit"
                                                        class="btn btn-dark btn-icon-text py-1">
                                                    <i class="btn-icon-prepend" data-feather="share-2"></i>ٹرانسفر کریں
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
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
                message('danger', 'ut-godam-empty', 'دوبارہ کوشش کریں');
            }
        } else {
            message('danger', 'ut-godam-empty', 'دوبارہ کوشش کریں');
        } ?>
    </div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "ut-godam-empty-add?id=" . $hidden_id;
    $godamArray = array(
        'godam_receiving_date' => mysqli_real_escape_string($connect, $_POST['godam_receiving_date']),
        'godam_bardana_qty' => mysqli_real_escape_string($connect, $_POST['godam_bardana_qty']),
        'godam_bardana_name' => mysqli_real_escape_string($connect, $_POST['godam_bardana_name']),
        'godam_balance' => mysqli_real_escape_string($connect, $_POST['godam_balance']),
        'godam_total_wt' => mysqli_real_escape_string($connect, $_POST['godam_total_wt']),
        'godam_saaf_wt' => mysqli_real_escape_string($connect, $_POST['godam_saaf_wt']),
        'godam_loading_id' => mysqli_real_escape_string($connect, $_POST['godam_loading_id']),
        'godam_report' => mysqli_real_escape_string($connect, $_POST['godam_report'])
    );
    $godamJsone = json_encode($godamArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'godam_json' => $godamJsone
    );
    $done = update('ut_bail_entries', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'خالی کرنے گودام انٹری محفوظ ہوگئی ہے۔ اب آپ آگے ٹرانسفر کر سکتے ہیں۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>
    <script>
        $("html, body").animate({scrollTop: $(document).height()}, 1000);
    </script>
    <script>
        VirtualSelect.init({
            ele: '.agent-select',
            placeholder: 'انتخاب',
            searchPlaceholderText: 'تلاش',
            search: true,
            //optionsCount: 2,
            required: true,
            noSearchResultsTex: 'کوئی رزلٹ نہیں'
        });
    </script>
    <script>
        $(function () {
            loadingAjax($('#godam_loading_id').val());
        });
        $('#godam_loading_id').change(function () {
            loadingAjax($(this).val());
        });
        function loadingAjax(godam_loading_id=null) {
            $.ajax({
                url: 'ajax/fetchSingleGodamLoading.php',
                type: 'post',
                data: {godam_loading_id: godam_loading_id},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        $("#gl_mobile").val(response.messages['mobile1']);
                        $("#gl_munshi").val(response.messages['munshi']);
                        $("#gl_address").val(response.messages['address']);
                        $("#responseGodamLoading").text('');
                    }
                    if (response.success === false) {
                        $("#responseGodamLoading").text('لوڈنگ کرنے گودام');
                    }
                }
            });
        }
    </script>
    <script>
        function godamBalance(e) {
            var bardana_qty = $("#bardana_qty").val();
            var godam_bardana_qty = $("#godam_bardana_qty").val();
            var bal = Number(bardana_qty) - Number(godam_bardana_qty);
            $("#godam_balance").val(bal);
        }
    </script>
    <!--<script type="text/javascript">
        $('#wb1').change(function () {
            if ($(this).is(":checked")) {
                $('#wb2').prop('checked', false);
            }
        });
        $('#wb2').change(function () {
            if ($(this).is(":checked")) {
                $('#wb1').prop('checked', false);
            }
        });
    </script>-->
    <script src="assets/js/ut-bail-dropdowns.js"></script>
<?php if (isset($_POST['transferFromGodamSubmit'])) {
    $ut_bail_id_t = mysqli_real_escape_string($connect, $_POST['ut_bail_id_t']);
    $t_f_g = mysqli_real_escape_string($connect, $_POST['transfer_from_godam']);
    $datata = array('transfer_from_godam' => $t_f_g);
    $tfg = update('ut_bail_entries', $datata, array('id' => $ut_bail_id_t));
    $urlT = 'ut-godam-empty-add?id=' . $ut_bail_id_t;
    if ($tfg) {
        switch ($t_f_g) {
            case 1:
                $msg = 'کمیشن بارڈر بل میں ٹرانسفر ہو گیا ہے۔ اب بل کا اندراج کر سکتے ہیں۔';
                $type = 'success';
                break;
            case 2:
                $bailDataa = fetch('ut_bail_entries', array('id' => $ut_bail_id_t));
                $bailData = mysqli_fetch_assoc($bailDataa);
                $godam_json = json_decode($bailData['godam_json']);
                $data = array(
                    'user_id' => $bailData['user_id'],
                    'username' => $bailData['username'],
                    'branch_id' => $bailData['branch_id'],
                    'loading_date' => $godam_json->godam_receiving_date,
                    'owner_name' => '',
                    'jins' => $bailData['jins'],
                    'tl_id' => 0,
                    'truck_no' => '',
                    'truck_name' => '',
                    'driver_name' => '',
                    'driver_mobile' => '',
                    'godam_loading_id' => $godam_json->godam_loading_id,
                    'godam_empty_id' => 0,
                    'consignee_name' => $bailData['consignee_name'],
                    'sender_city' => $bailData['loading_city'],
                    'report' => '',
                    'is_ut_transferred' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                );
                $done = insert('imp_truck_loadings', $data);
                if ($done) {
                    $msg = 'ٹرانسفر ہو گیا ہے۔ اب آپ امپورٹ بلہ میں ٹرانزٹ واپسی ٹرک لوڈنگ میں اندراج کر سکتے ہیں۔';
                    $type = 'success';
                } else {
                    $msg = 'امپورٹ بلہ میں ٹرانسفر نہیں ہوسکا۔ دوبارہ کوشش کریں۔';
                    $type = 'danger';
                }
                break;
            default:
                $msg = '';
                $type = 'warning';
                break;
        }
        message($type, $urlT, $msg);
    } else {
        message('warning', $urlT, 'ڈیٹا بیس پرابلم ہے۔');
    }
} ?>