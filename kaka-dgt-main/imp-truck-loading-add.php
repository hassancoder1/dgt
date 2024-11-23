<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n2">
        <div>
            <h4 class="mb-3 mb-md-0 mt-n2">امپورٹ بل ٹرک لوڈنگ اندراج </h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl('imp-truck-loading'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-11">
            <div class="card">
                <div class="card-body pt-0">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <?php if (isset($_GET['id'])) {
                        $id = mysqli_real_escape_string($connect, $_GET['id']);
                        $records = fetch('imp_truck_loadings', array('id' => $id));
                        $record = mysqli_fetch_assoc($records); ?>
                        <form action="" method="post">
                            <div class="row gx-0 mb-3 justify-content-center">
                                <div class="col-3">
                                    <div class="input-group bg-info bg-opacity-10">
                                        <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                        <input type="text" id="ser" class="form-control" disabled
                                               value="<?php echo $id; ?>">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group bg-info bg-opacity-10">
                                        <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                        <input type="text" id="userName" class="form-control bg-transparent"
                                               required
                                               value="<?php echo $record['username']; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group bg-info bg-opacity-10">
                                        <label for="" class="input-group-text urdu">برانچ کانام</label>
                                        <input type="text" name="" readonly tabindex="-1"
                                               class="form-control urdu-2 bold bg-transparent"
                                               required
                                               value="<?php echo getTableDataByIdAndColName('branches', $record['branch_id'], 'b_name'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-0 gy-5">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="today"
                                               class="input-group-text input-group-addon bg-transparent urdu">لوڈنگ
                                            تاریخ</label>
                                        <input type="text" id="today" class="form-control bg-transparent border-primary"
                                               placeholder="تاریخ" value="<?php echo $record['loading_date']; ?>"
                                               disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="owner_name" class="input-group-text urdu">مالک نام</label>
                                        <input type="text" id="owner_name" name="owner_name" autofocus
                                               class="form-control input-urdu" required
                                               value="<?php echo $record['owner_name']; ?>">
                                        <label for="jins" class="input-group-text urdu">جنس</label>
                                        <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                               required value="<?php echo $record['jins']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3 position-relative">
                                    <input type="hidden" id="tl_id" name="tl_id" required value="<?php echo $id; ?>">
                                    <div class="input-group">
                                        <label for="truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                        <input type="text" id="truck_no" name="truck_no" class="form-control" required
                                               value="<?php echo $record['truck_no']; ?>">
                                        <label for="truck_name" class="input-group-text urdu">ٹرک نام</label>
                                        <input type="text" id="truck_name" name="truck_name" tabindex="-1"
                                               class="form-control" required readonly
                                               value="<?php echo $record['truck_name']; ?>">
                                    </div>
                                    <small id="responseTruck" class="text-danger urdu position-absolute"
                                           style="top: -20px; right: 20px;"></small>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                        <input type="text" id="driver_name" name="driver_name" tabindex="-1"
                                               class="form-control input-urdu" required readonly
                                               value="<?php echo $record['driver_name']; ?>">
                                        <label for="driver_mobile" class="input-group-text urdu">موبائل</label>
                                        <input type="text" id="driver_mobile" name="driver_mobile" tabindex="-1"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly
                                               value="<?php echo $record['driver_mobile']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="godam_loading_id" class="input-group-text urdu">لوڈنگ کرنے
                                            گودام</label>
                                        <select id="godam_loading_id" name="godam_loading_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $loadings = fetch('godam_loading_forms');
                                            while ($loading = mysqli_fetch_assoc($loadings)) {
                                                $l_selected = $loading['id'] == $record['godam_loading_id'] ? 'selected' : '';
                                                echo '<option ' . $l_selected . ' value="' . $loading['id'] . '">' . $loading['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseGodamLoading" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 0;"></small>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="godam_loading_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="godam_loading_mobile" name="godam_loading_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                        <label for="godam_loading_munshi" class="input-group-text urdu">منشی کا
                                            نام</label>
                                        <input type="text" id="godam_loading_munshi" name="godam_loading_munshi"
                                               readonly tabindex="-1"
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="godam_loading_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="godam_loading_address" name="godam_loading_address"
                                               readonly tabindex="-1"
                                               class="form-control " required>
                                    </div>
                                </div>
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="godam_empty_id" class="input-group-text urdu">خالی کرنے
                                            گودام</label>
                                        <select id="godam_empty_id" name="godam_empty_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $empties = fetch('godam_empty_forms');
                                            while ($empty = mysqli_fetch_assoc($empties)) {
                                                $e_selected = $empty['id'] == $record['godam_empty_id'] ? 'selected' : '';
                                                echo '<option ' . $e_selected . ' value="' . $empty['id'] . '">' . $empty['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <small id="responseGodamEmpty" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 0;"></small>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="godam_empty_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="godam_empty_mobile" name="godam_empty_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                        <label for="godam_empty_munshi" class="input-group-text urdu">منشی کا
                                            نام</label>
                                        <input type="text" id="godam_empty_munshi" name="godam_empty_munshi" readonly
                                               class="bold form-control urdu-2" required tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="godam_empty_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="godam_empty_address" name="godam_empty_address" readonly
                                               class="bold form-control input-urdu" required tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="consignee_name" class="input-group-text urdu">کنسائینی نام</label>
                                        <input type="text" id="consignee_name" name="consignee_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $record['consignee_name']; ?>">
                                        <label for="sender_city" class="input-group-text urdu">بھیجنے والا شہر</label>
                                        <input type="text" id="sender_city" name="sender_city"
                                               class="form-control input-urdu" required
                                               value="<?php echo $record['sender_city']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <label for="report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" id="report" name="report" class="form-control input-urdu"
                                               required value="<?php echo $record['report']; ?>">
                                    </div>
                                </div>

                            </div>
                            <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                            <button type="submit" name="recordUpdate" id="recordUpdate"
                                    class="btn btn-dark mt-4 btn-icon-text">
                                <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                درستگی
                            </button>
                            <?php if ($record['is_transfered'] == 0 && $record['is_ut_transferred'] == 0 ) { ?>
                                <!--<a onclick="deleteRecord(this)" data-url="imp-truck-loading-add"
                                   data-tbl="imp_truck_loadings" id="<?php /*echo $record['id']; */?>"
                                   class="btn btn-danger mt-4 btn-icon-text float-end">
                                    <i class="btn-icon-prepend" data-feather="delete"></i>ختم کریں
                                </a>-->
                            <?php } ?>
                        </form>
                    <?php } else { ?>
                        <form action="" method="post">
                            <div class="row gx-0 mb-3 justify-content-center">
                                <div class="col-3">
                                    <div class="input-group bg-info bg-opacity-10">
                                        <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                        <input type="text" id="ser" class="form-control" disabled
                                               value="<?php echo getAutoIncrement('imp_truck_loadings'); ?>">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group bg-info bg-opacity-10">
                                        <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                        <input type="text" id="userName" class="form-control bg-transparent"
                                               required
                                               value="<?php echo $userName; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="input-group bg-info bg-opacity-10">
                                        <label for="" class="input-group-text urdu">برانچ کانام</label>
                                        <input type="text" id="" name=""
                                               class="form-control urdu-2 bold bg-transparent"
                                               required
                                               value="<?php echo $branchName; ?>" readonly tabindex="-1">
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-0 gy-5">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="today"
                                               class="input-group-text input-group-addon bg-transparent urdu">لوڈنگ
                                            تاریخ</label>
                                        <input type="text" id="today" class="form-control bg-transparent border-primary"
                                               placeholder="تاریخ" value="<?php echo date('Y-m-d'); ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="owner_name" class="input-group-text urdu">مالک نام</label>
                                        <input type="text" id="owner_name" name="owner_name" autofocus
                                               class="form-control input-urdu" required>
                                        <label for="jins" class="input-group-text urdu">جنس</label>
                                        <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-3 position-relative">
                                    <input type="hidden" id="tl_id" name="tl_id" required>
                                    <div class="input-group">
                                        <label for="truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                        <input type="text" id="truck_no" name="truck_no" class="form-control" required>
                                        <label for="truck_name" class="input-group-text urdu">ٹرک نام</label>
                                        <input type="text" id="truck_name" name="truck_name" tabindex="-1"
                                               class="form-control urdu-2 bold" required readonly>
                                    </div>
                                    <small id="responseTruck" class="text-danger urdu position-absolute"
                                           style="top: -20px; right: 20px;"></small>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                        <input type="text" id="driver_name" name="driver_name" tabindex="-1"
                                               class="form-control urdu-2 bold" required readonly>
                                        <label for="driver_mobile" class="input-group-text urdu">موبائل</label>
                                        <input type="text" id="driver_mobile" name="driver_mobile" tabindex="-1"
                                               class="form-control ltr bold" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="godam_loading_id" class="input-group-text urdu">لوڈنگ کرنے
                                            گودام</label>
                                        <select id="godam_loading_id" name="godam_loading_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $loadings = fetch('godam_loading_forms');
                                            while ($loading = mysqli_fetch_assoc($loadings)) {
                                                echo '<option value="' . $loading['id'] . '">' . $loading['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <small id="responseGodamLoading" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 0;"></small>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="godam_loading_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="godam_loading_mobile" name="godam_loading_mobile"
                                               class="form-control ltr bold" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                        <label for="godam_loading_munshi" class="input-group-text urdu">منشی کا
                                            نام</label>
                                        <input type="text" id="godam_loading_munshi" name="godam_loading_munshi"
                                               readonly tabindex="-1"
                                               class="form-control urdu-2 bold" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="godam_loading_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="godam_loading_address" name="godam_loading_address"
                                               readonly tabindex="-1"
                                               class="form-control urdu-2 bold" required>
                                    </div>
                                </div>
                                <div class="col-lg-4 position-relative">
                                    <div class="input-group">
                                        <label for="godam_empty_id" class="input-group-text urdu">خالی کرنے
                                            گودام</label>
                                        <select id="godam_empty_id" name="godam_empty_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $empties = fetch('godam_empty_forms');
                                            while ($empty = mysqli_fetch_assoc($empties)) {
                                                echo '<option value="' . $empty['id'] . '">' . $empty['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseGodamEmpty" class="text-danger urdu position-absolute"
                                           style="top: 0; left: 0;"></small>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="godam_empty_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="godam_empty_mobile" name="godam_empty_mobile"
                                               class="form-control ltr bold" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                        <label for="godam_empty_munshi" class="input-group-text urdu">منشی کا
                                            نام</label>
                                        <input type="text" id="godam_empty_munshi" name="godam_empty_munshi" readonly
                                               class="bold form-control urdu-2" required tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="godam_empty_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="godam_empty_address" name="godam_empty_address" readonly
                                               class="bold form-control urdu-2" required tabindex="-1">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="consignee_name" class="input-group-text urdu">کنسائینی نام</label>
                                        <input type="text" id="consignee_name" name="consignee_name"
                                               class="form-control input-urdu" required>
                                        <label for="sender_city" class="input-group-text urdu">بھیجنے والا شہر</label>
                                        <input type="text" id="sender_city" name="sender_city"
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="input-group">
                                        <label for="report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" id="report" name="report" class="form-control input-urdu"
                                               required>
                                    </div>
                                </div>

                            </div>
                            <button name="recordSubmit" id="recordSubmit" type="submit"
                                    class="btn btn-primary btn-icon-text mt-4">
                                <i class="btn-icon-prepend" data-feather="check-square"></i>
                                محفوظ کریں
                            </button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
<?php include("footer.php"); ?>
    <script>
        var typingTimer;
        var doneTypingInterval = 1000;
        var $input = $('#truck_no');
        $input.on('keyup', function (e) {
            clearTimeout(typingTimer);
            truck_no = $('#truck_no').val();
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });
        function doneTyping() {
            $.ajax({
                url: 'ajax/fetchSingleTruckData.php',
                type: 'post',
                data: {truck_no: truck_no},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        $("#recordSubmit").prop('disabled', false);
                        $("#recordUpdate").prop('disabled', false);
                        $("#tl_id").val(response.messages['tl_id']);
                        $("#truck_name").val(response.messages['truck_name']);
                        $("#driver_name").val(response.messages['driver_name']);
                        $("#driver_mobile").val(response.messages['d_mobile1']);
                        $("#responseTruck").text('');
                        //$("#jm_kh_tafseel").text(response.messages['khaata_name']);
                    }
                    if (response.success === false) {
                        $("#recordSubmit").prop('disabled', true);
                        $("#recordUpdate").prop('disabled', true);
                        $("#responseTruck").text('ٹرک نمبر');
                        //$("#khaata_id1").val(khaata_id);
                    }
                }
            });
        }
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
                        $("#godam_loading_mobile").val(response.messages['mobile1']);
                        $("#godam_loading_munshi").val(response.messages['munshi']);
                        $("#godam_loading_address").val(response.messages['address']);
                        $("#responseGodamLoading").text('');
                    }
                    if (response.success === false) {
                        $("#responseGodamLoading").text('لوڈنگ کرنے گودام');
                    }
                }
            });
        }

        $(function () {
            var sel_godam_empty_id = $('#godam_empty_id').val();
            emptyAjax(sel_godam_empty_id);
        });
        $('#godam_empty_id').change(function () {
            var godam_empty_id = $(this).val();
            emptyAjax(godam_empty_id);
        });
        function emptyAjax(godam_empty_id=null) {
            $.ajax({
                url: 'ajax/fetchSingleGodamEmpty.php',
                type: 'post',
                data: {godam_empty_id: godam_empty_id},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        $("#godam_empty_mobile").val(response.messages['mobile1']);
                        $("#godam_empty_munshi").val(response.messages['munshi']);
                        $("#godam_empty_address").val(response.messages['address']);
                        $("#responseGodamEmpty").text('');
                    }
                    if (response.success === false) {
                        $("#responseGodamEmpty").text('خالی کرنے گودام');
                    }
                }
            });
        }

    </script>
    <!--<script src="assets/js/input-repeator.js"></script>-->
<?php if (isset($_POST['recordSubmit'])) {
    /*$serial = fetch('imp_truck_loadings', array('branch_id' => $branchId));
    $branch_serial = mysqli_num_rows($serial);
    $branch_serial = $branch_serial + 1;*/
    $url = "imp-truck-loading-add";
    $data = array(
        'user_id' => $userId,
        'username' => $userName,
        'branch_id' => $branchId,
        'loading_date' => date('Y-m-d'),
        'owner_name' => mysqli_real_escape_string($connect, $_POST['owner_name']),
        'jins' => mysqli_real_escape_string($connect, $_POST['jins']),
        'tl_id' => mysqli_real_escape_string($connect, $_POST['tl_id']),
        'truck_no' => mysqli_real_escape_string($connect, $_POST['truck_no']),
        'truck_name' => mysqli_real_escape_string($connect, $_POST['truck_name']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'driver_mobile' => mysqli_real_escape_string($connect, $_POST['driver_mobile']),
        'godam_loading_id' => mysqli_real_escape_string($connect, $_POST['godam_loading_id']),
        'godam_empty_id' => mysqli_real_escape_string($connect, $_POST['godam_empty_id']),
        'consignee_name' => mysqli_real_escape_string($connect, $_POST['consignee_name']),
        'sender_city' => mysqli_real_escape_string($connect, $_POST['sender_city']),
        'report' => mysqli_real_escape_string($connect, $_POST['report']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = insert('imp_truck_loadings', $data);
    if ($done) {
        $insertId = $connect->insert_id;
        $url .= '?id=' . $insertId;
        message('success', $url, 'مپورٹ بل ٹرک لوڈنگ محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "imp-truck-loading-add?id=" . $hidden_id;
    $data = array(
        'owner_name' => mysqli_real_escape_string($connect, $_POST['owner_name']),
        'jins' => mysqli_real_escape_string($connect, $_POST['jins']),
        'tl_id' => mysqli_real_escape_string($connect, $_POST['tl_id']),
        'truck_no' => mysqli_real_escape_string($connect, $_POST['truck_no']),
        'truck_name' => mysqli_real_escape_string($connect, $_POST['truck_name']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'driver_mobile' => mysqli_real_escape_string($connect, $_POST['driver_mobile']),
        'godam_loading_id' => mysqli_real_escape_string($connect, $_POST['godam_loading_id']),
        'godam_empty_id' => mysqli_real_escape_string($connect, $_POST['godam_empty_id']),
        'consignee_name' => mysqli_real_escape_string($connect, $_POST['consignee_name']),
        'sender_city' => mysqli_real_escape_string($connect, $_POST['sender_city']),
        'report' => mysqli_real_escape_string($connect, $_POST['report']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    $done = update('imp_truck_loadings', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'مپورٹ بل ٹرک لوڈنگ تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>