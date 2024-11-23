<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0 mt-n2">افغانی ٹرک کرایہ اندراج </h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl('afghani-truck-kiraya'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <?php if (isset($_GET['id'])) {
                        $id = mysqli_real_escape_string($connect, $_GET['id']);
                        $records = fetch('afghani_truck', array('id' => $id));
                        $record = mysqli_fetch_assoc($records);
                        ?>
                        <form action="" method="post">
                            <div class="row gx-0 gy-3">
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="afg_date" class="input-group-text urdu">تاریخ</label>
                                        <input id="afg_date" value="<?php echo $record['afg_date']; ?>"
                                               type="date" class="form-control ltr" disabled>
                                        <label for="sender_name" class="input-group-text urdu">بھیجنے والا نام</label>
                                        <input type="text" id="sender_name" name="sender_name" autofocus
                                               class="form-control input-urdu" required
                                               value="<?php echo $record['sender_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="sender_city" class="input-group-text urdu">بھیجنے والا
                                                شہر</label>
                                            <input type="text" id="sender_city" name="sender_city" autofocus
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $record['sender_city']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                        <input type="text" id="afg_truck_no" name="afg_truck_no" class="form-control"
                                               required value="<?php echo $record['afg_truck_no']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_truck_name" class="input-group-text urdu">ٹرک نام</label>
                                        <input type="text" id="afg_truck_name" name="afg_truck_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $record['afg_truck_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                        <input type="text" id="driver_name" name="driver_name"
                                               class="form-control input-urdu" required
                                               value="<?php echo $record['driver_name']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="driver_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input type="text" id="driver_mobile" name="driver_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999"
                                               value="<?php echo $record['driver_mobile']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="godam_loading_id" class="input-group-text urdu">لوڈنگ کرنے
                                            گودام</label>
                                        <select id="godam_loading_id" name="godam_loading_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <?php $loadings = fetch('godam_loading_forms');
                                            while ($loading = mysqli_fetch_assoc($loadings)) {
                                                $l_selected = $loading['id'] == $record['godam_loading_id'] ? 'selected' : '';
                                                echo '<option ' . $l_selected . ' value="' . $loading['id'] . '">' . $loading['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="godam_empty_id" class="input-group-text urdu">خالی کرنے
                                            گودام</label>
                                        <select id="godam_empty_id" name="godam_empty_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <?php $empties = fetch('godam_empty_forms');
                                            while ($empty = mysqli_fetch_assoc($empties)) {
                                                $e_selected = $empty['id'] == $record['godam_empty_id'] ? 'selected' : '';
                                                echo '<option ' . $e_selected . ' value="' . $empty['id'] . '">' . $empty['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 mb-2">
                                <div class="col-md-9">
                                    <table class="table table-bordered" id="productTable">
                                        <thead>
                                        <tr>
                                            <th width="5%">
                                                <i class="fa fa-plus-square border ms-0 px-2 py-1 mb-0 bg-light cursor-pointer"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-title="اضافہ لائن (/)" onclick="addRow()" id="addRowBtn"
                                                   data-loading-text="Loading..."></i>
                                            </th>
                                            <th width="20%">خرچہ نام</th>
                                            <th width="55%">تفصیل</th>
                                            <th width="20%">رقم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $x = 1;
                                        $arrayNumber = 0;
                                        $exp_names = json_decode($record["exp_names"]);
                                        $exp_details = json_decode($record["exp_details"]);
                                        $exp_values = json_decode($record["exp_values"]);
                                        foreach ($exp_names as $index => $value) {
                                            //for ($x = 1; $x < 4; $x++) {
                                            ?>
                                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                <td class="border-bottom border-success border-end-0 border-start-0">
                                                    <i id="removeProductRowBtn"
                                                       class="fa fa-remove border px-2 py-1 ms-2 mt-1 text-danger cursor-pointer"
                                                       data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)"
                                                       tabindex="-1"
                                                       onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_names[]"
                                                           value="<?php echo $exp_names[$index]; ?>"
                                                           placeholder="خرچہ <?php echo $x; ?>"
                                                           required class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_details[]"
                                                           placeholder="تفصیل <?php echo $x; ?>"
                                                           value="<?php echo $exp_details[$index]; ?>"
                                                           required class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_values[]" required
                                                           placeholder="رقم <?php echo $x; ?>"
                                                           value="<?php echo $exp_values[$index]; ?>"
                                                           onkeyup="getTotal(<?php echo $x ?>)"
                                                           autocomplete="off"
                                                           class="form-control currency form-control-sm bold"
                                                           id="exp_value<?php echo $x; ?>">
                                                </td>
                                            </tr>
                                            <?php
                                            $arrayNumber++;
                                            $x++;
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <div class="row gy-0">
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                                <input type="text" id="ser" class="form-control" disabled
                                                       value="<?php echo getAutoIncrement('afghani_truck'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                                <input type="text" id="userName" class="form-control bg-transparent"
                                                       required
                                                       value="<?php echo $userName; ?>" readonly tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="" class="input-group-text urdu">برانچ کانام</label>
                                                <input type="text" id="" name=""
                                                       class="form-control input-urdu bg-transparent"
                                                       required
                                                       value="<?php echo $branchName; ?>" readonly tabindex="-1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-0 justify-content-center mt-5    ">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_jmaa_khaata_no" name="afg_jmaa_khaata_no"
                                               class="form-control" required
                                               value="<?php echo $record['afg_jmaa_khaata_no']; ?>">
                                        <small id="response1" class="text-danger urdu position-absolute"
                                               style="top: -20px;"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id"
                                           value="<?php echo $record['afg_jmaa_khaata_id']; ?>">
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_bnaam_khaata_no" name="afg_bnaam_khaata_no"
                                               class="form-control" required
                                               value="<?php echo $record['afg_bnaam_khaata_no']; ?>">
                                        <small id="response2" class="text-danger urdu position-absolute"
                                               style="top: -20px; right: 60px;"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id2" name="afg_bnaam_khaata_id"
                                           value="<?php echo $record['afg_bnaam_khaata_id'] ?>">
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly name="total_bill"
                                               value="<?php echo $record['total_bill'] ?>"
                                               class="form-control bold" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                                    <button type="submit" name="recordUpdate" id="recordSubmitFinal"
                                            class="btn btn-dark w-100    btn-icon-text">
                                        <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                        درستگی
                                    </button>
                                    <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                </div>
                            </div>
                            <?php if ($record['is_transfered'] == 0) { ?>
                                <!--<a onclick="deleteRecord(this)" data-url="afghani-truck-kiraya-add"
                                   data-tbl="afghani_truck" id="<?php /*echo $record['id']; */?>"
                                   class="btn btn-danger mt-4 btn-icon-text float-end">
                                    <i class="btn-icon-prepend" data-feather="delete"></i>ختم کریں
                                </a>-->
                            <?php } ?>
                        </form>
                    <?php } else { ?>
                        <form action="" method="post">
                            <div class="row gx-0 gy-3">
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="afg_date" class="input-group-text urdu">تاریخ</label>
                                        <input id="afg_date" value="<?php echo date('Y-m-d'); ?>"
                                               type="date" class="form-control ltr" disabled>
                                        <label for="s_name" class="input-group-text urdu">بھیجنے والا نام</label>
                                        <input type="text" id="s_name" name="sender_name" autofocus
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <label for="sender_city" class="input-group-text urdu">بھیجنے والا
                                                شہر</label>
                                            <input type="text" id="sender_city" name="sender_city" autofocus
                                                   class="form-control input-urdu" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                        <input type="text" id="afg_truck_no" name="afg_truck_no" class="form-control"
                                               required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_truck_name" class="input-group-text urdu">ٹرک نام</label>
                                        <input type="text" id="afg_truck_name" name="afg_truck_name"
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                        <input type="text" id="driver_name" name="driver_name"
                                               class="form-control input-urdu" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <label for="driver_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input type="text" id="driver_mobile" name="driver_mobile"
                                               class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                               data-inputmask-alias="(+99) 999-9999999">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="input-group">
                                        <label for="godam_loading_id" class="input-group-text urdu">لوڈنگ کرنے
                                            گودام</label>
                                        <select id="godam_loading_id" name="godam_loading_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <?php $loadings = fetch('godam_loading_forms');
                                            while ($loading = mysqli_fetch_assoc($loadings)) {
                                                echo '<option value="' . $loading['id'] . '">' . $loading['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <label for="godam_empty_id" class="input-group-text urdu">خالی کرنے
                                            گودام</label>
                                        <select id="godam_empty_id" name="godam_empty_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <?php $empties = fetch('godam_empty_forms');
                                            while ($empty = mysqli_fetch_assoc($empties)) {
                                                echo '<option value="' . $empty['id'] . '">' . $empty['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4 mb-2">
                                <div class="col-md-9">
                                    <table class="table table-bordered" id="productTable">
                                        <thead>
                                        <tr>
                                            <th width="5%">
                                                <i class="fa fa-plus-square border ms-0 px-2 py-1 mb-0 bg-light cursor-pointer"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-title="اضافہ لائن (/)" onclick="addRow()" id="addRowBtn"
                                                   data-loading-text="Loading..."></i>
                                            </th>
                                            <th width="20%">خرچہ نام</th>
                                            <th width="55%">تفصیل</th>
                                            <th width="20%">رقم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $arrayNumber = 0;
                                        for ($x = 1; $x < 3; $x++) { ?>
                                            <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                <td class="border-bottom border-success border-end-0 border-start-0">
                                                    <i id="removeProductRowBtn"
                                                       class="fa fa-remove border px-2 py-1 ms-2 mt-1 text-danger cursor-pointer"
                                                       data-bs-toggle="tooltip" data-bs-title="ختم لائن (,)"
                                                       tabindex="-1" onclick="removeProductRow(<?php echo $x; ?>)"></i>
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_names[]"
                                                           placeholder="خرچہ <?php echo $x; ?>"
                                                           required class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_details[]"
                                                           placeholder="تفصیل <?php echo $x; ?>"
                                                           required class="form-control form-control-sm input-urdu"
                                                           id="exp_name<?php echo $x; ?>">
                                                </td>
                                                <td>
                                                    <input type="text" name="exp_values[]" required
                                                           placeholder="رقم <?php echo $x; ?>"
                                                           onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off"
                                                           class="form-control currency form-control-sm bold"
                                                           id="exp_value<?php echo $x; ?>">
                                                </td>
                                            </tr>
                                            <?php
                                            $arrayNumber++;
                                        } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <div class="row gy-0">
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                                <input type="text" id="ser" class="form-control" disabled
                                                       value="<?php echo getAutoIncrement('afghani_truck'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                                <input type="text" id="userName" class="form-control bg-transparent"
                                                       required
                                                       value="<?php echo $userName; ?>" readonly tabindex="-1">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="input-group bg-info bg-opacity-10">
                                                <label for="" class="input-group-text urdu">برانچ کانام</label>
                                                <input type="text" class="form-control input-urdu bg-transparent"
                                                       required readonly tabindex="-1"
                                                       value="<?php echo $branchName; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-0 mt-5 justify-content-center">
                                <div class="col-md-2 position-relative">
                                    <div class="input-group">
                                        <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_jmaa_khaata_no" name="afg_jmaa_khaata_no"
                                               class="form-control bg-transparent" required>
                                    </div>
                                    <small id="response1" class="text-danger urdu position-absolute "
                                           style="top: 0; left: 0;"></small>
                                    <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id">
                                </div>
                                <div class="col-md-2 position-relative">
                                    <div class="input-group">
                                        <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_bnaam_khaata_no" name="afg_bnaam_khaata_no"
                                               class="form-control bg-transparent" required>
                                        <small id="response2" class="text-danger urdu position-absolute"
                                               style="top: 0; left: 0;"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id2" name="afg_bnaam_khaata_id">
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly name="total_bill"
                                               class="form-control bold" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button name="recordSubmit" id="recordSubmitFinal" type="submit"
                                            class="btn btn-primary btn-icon-text w-100">
                                        <i class="btn-icon-prepend" data-feather="check-square"></i>
                                        محفوظ کریں
                                    </button>
                                    <span id="totalBillMsg" class="text-danger bold urdu d-block"></span>
                                </div>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="urdu-2">
                    <?php $jmaaName = $bnaamName = "";
                    if (isset($_GET['id'])) {
                        $afg_jmaa_khaata_id = $record['afg_jmaa_khaata_id'];
                        $afg_bnaam_khaata_id = $record['afg_bnaam_khaata_id'];
                        $jmaaName = getTableDataByIdAndColName('khaata', $record['afg_jmaa_khaata_id'], 'khaata_name');
                        $bnaamName = getTableDataByIdAndColName('khaata', $record['afg_bnaam_khaata_id'], 'khaata_name');
                    } ?>
                    <h5 class="bg-success bg-opacity-25 p-2">جمع کھاتہ نام</h5>
                    <p class="p-1 bold text-primary" id="jm_kh_tafseel"><?php echo $jmaaName; ?></p>
                    <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                    <p class="p-1 bold text-primary" id="bm_kh_tafseel"><?php echo $bnaamName; ?></p>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>
    <script src="assets/js/input-repeator.js"></script>
    <script>
        transferToRoznamcha();
        $(document).on('keyup', "#afg_jmaa_khaata_no", function (e) {
            transferToRoznamcha();
            var khaata_no = $(this).val();
            var khaata_id1 = $("#khaata_id1");
            $.ajax({
                url: 'ajax/fetchSingleKhaata.php',
                type: 'post',
                data: {khaata_no: khaata_no},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        khaata_id1.val(response.messages['khaata_id']);
                        $("#response1").text('');
                        var res = response.messages['khaata_name']
                            + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                            + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                        $("#jm_kh_tafseel").html(res);
                        transferToRoznamcha();
                    }
                    if (response.success === false) {
                        $("#response1").text('جمع کھاتہ نمبر');
                        $("#jm_kh_tafseel").text('');
                        khaata_id1.val(0);
                        transferToRoznamcha();
                    }
                }
            });
        });
        $(document).on('keyup', "#afg_bnaam_khaata_no", function (e) {
            transferToRoznamcha();
            var khaata_no = $(this).val();
            var khaata_id2 = $("#khaata_id2");
            $.ajax({
                url: 'ajax/fetchSingleKhaata.php',
                type: 'post',
                data: {khaata_no: khaata_no},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        khaata_id2.val(response.messages['khaata_id']);
                        $("#response2").text('');
                        var res = response.messages['khaata_name']
                            + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                            + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                        $("#bm_kh_tafseel").html(res);
                        transferToRoznamcha();
                    }
                    if (response.success === false) {
                        $("#response2").text('بنام کھاتہ نمبر');
                        $("#bm_kh_tafseel").text('');
                        khaata_id2.val(0);
                        transferToRoznamcha();
                    }
                }
            });
        });

        function transferToRoznamcha() {
            var msg = '';
            var totalBillMsg = $("#totalBillMsg");
            var khaata_id1 = $("#khaata_id1").val();
            var khaata_id2 = $("#khaata_id2").val();
            var total = $("#total").val();
            if (khaata_id1 <= 0 || khaata_id2 <= 0 || total <= 0) {
                totalBillMsg.show();
                if (khaata_id1 <= 0) {
                    $("#recordSubmitFinal").prop('disabled', true);
                    msg = 'جمع کھاتہ درست نہیں۔';
                }
                if (khaata_id2 <= 0) {
                    $("#recordSubmitFinal").prop('disabled', true);
                    msg = 'بنام کھاتہ درست نہیں۔';
                }
                if (total <= 0) {
                    $("#recordSubmitFinal").prop('disabled', true);
                    msg = ' ٹوٹل بل خالی نہیں ہو سکتا ';
                }
            } else {
                msg = '';
                $("#recordSubmitFinal").prop('disabled', false);
                totalBillMsg.hide();
            }
            totalBillMsg.text(msg);
        }
    </script>
    <!--<script>
        $("#recordSubmit").prop('disabled', true);
        var khaata_id = $("#khaata_id1").val();
        var typingTimer;
        var doneTypingInterval = 1000;
        var $input = $('#afg_jmaa_khaata_no');
        var khaata_no = '';
        $input.on('keyup', function (e) {
            clearTimeout(typingTimer);
            khaata_no = $('#afg_jmaa_khaata_no').val();
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        function doneTyping() {
            $.ajax({
                url: 'ajax/fetchSingleKhaata.php',
                type: 'post',
                data: {khaata_no: khaata_no},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        /*$("#recordSubmit").prop('disabled', false);
                        $("#recordUpdate").prop('disabled', false);*/
                        $("#khaata_id1").val(response.messages['khaata_id']);
                        $("#response1").text('');
                        $("#jm_kh_tafseel").text(response.messages['khaata_name']);
                    }
                    if (response.success === false) {
                        $("#recordSubmit").prop('disabled', true);
                        $("#recordUpdate").prop('disabled', true);
                        $("#response1").text('جمع کھاتہ نمبر درست نہیں ہے');
                        $("#jm_kh_tafseel").text('');
                        $("#khaata_id1").val(khaata_id);
                    }
                }
            });
        }
    </script>
    <script>
        var khaata_id2 = $("#khaata_id2").val();
        var typingTimer2;
        var doneTypingInterval2 = 1000;
        var $input2 = $('#afg_bnaam_khaata_no');
        var khaata_no2 = '';
        $input2.on('keyup', function (e) {
            clearTimeout(typingTimer2);
            khaata_no2 = $('#afg_bnaam_khaata_no').val();
            typingTimer2 = setTimeout(doneTyping2, doneTypingInterval2);
        });

        function doneTyping2() {
            $.ajax({
                url: 'ajax/fetchSingleKhaata.php',
                type: 'post',
                data: {khaata_no: khaata_no2},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        $("#recordSubmit").prop('disabled', false);
                        $("#recordUpdate").prop('disabled', false);
                        $("#khaata_id2").val(response.messages['khaata_id']);
                        $("#response2").text('');
                        $("#bm_kh_tafseel").text(response.messages['khaata_name']);
                    }
                    if (response.success === false) {
                        $("#recordSubmit").prop('disabled', true);
                        $("#recordUpdate").prop('disabled', true);
                        $("#response2").text('بنام کھاتہ نمبر درست نہیں ہے');
                        $("#bm_kh_tafseel").text('');
                        $("#khaata_id2").val(khaata_id2);
                    }
                }
            });
        }
    </script>-->

<?php if (isset($_POST['recordSubmit'])) {
    $serial = fetch('afghani_truck', array('branch_id' => $branchId));
    $branch_serial = mysqli_num_rows($serial);
    $branch_serial = $branch_serial + 1;
    $url = "afghani-truck-kiraya-add";
    $exp_names = json_encode($_POST['exp_names'], JSON_UNESCAPED_UNICODE);
    $exp_details = json_encode($_POST['exp_details'], JSON_UNESCAPED_UNICODE);
    $exp_values = json_encode($_POST['exp_values']);
    $data = array(
        'user_id' => $userId,
        'username' => $userName,
        'branch_id' => $branchId,
        'branch_serial' => $branch_serial,
        'afg_date' => date('Y-m-d'),
        'sender_name' => mysqli_real_escape_string($connect, $_POST['sender_name']),
        'sender_city' => mysqli_real_escape_string($connect, $_POST['sender_city']),
        'afg_truck_no' => mysqli_real_escape_string($connect, $_POST['afg_truck_no']),
        'afg_truck_name' => mysqli_real_escape_string($connect, $_POST['afg_truck_name']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'driver_mobile' => mysqli_real_escape_string($connect, $_POST['driver_mobile']),
        'godam_loading_id' => mysqli_real_escape_string($connect, $_POST['godam_loading_id']),
        'godam_empty_id' => mysqli_real_escape_string($connect, $_POST['godam_empty_id']),
        'exp_names' => $exp_names,
        'exp_details' => $exp_details,
        'exp_values' => $exp_values,
        'afg_jmaa_khaata_no' => mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_no']),
        'afg_jmaa_khaata_id' => mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_id']),
        'afg_bnaam_khaata_no' => mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_no']),
        'afg_bnaam_khaata_id' => mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_id']),
        'total_bill' => mysqli_real_escape_string($connect, $_POST['total_bill']),
        'created_at' => date('Y-m-d H:i:s')
    );

    $done = insert('afghani_truck', $data);
    if ($done) {
        $inserId = $connect->insert_id;
        $url .= '?id=' . $inserId;
        message('success', $url, 'افغانی ٹرک کرایہ محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "afghani-truck-kiraya-add?id=" . $hidden_id;
    $exp_names = json_encode($_POST['exp_names'], JSON_UNESCAPED_UNICODE);
    $exp_details = json_encode($_POST['exp_details'], JSON_UNESCAPED_UNICODE);
    $exp_values = json_encode($_POST['exp_values']);
    $data = array(
        'sender_name' => mysqli_real_escape_string($connect, $_POST['sender_name']),
        'sender_city' => mysqli_real_escape_string($connect, $_POST['sender_city']),
        'afg_truck_no' => mysqli_real_escape_string($connect, $_POST['afg_truck_no']),
        'afg_truck_name' => mysqli_real_escape_string($connect, $_POST['afg_truck_name']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'driver_mobile' => mysqli_real_escape_string($connect, $_POST['driver_mobile']),
        'godam_loading_id' => mysqli_real_escape_string($connect, $_POST['godam_loading_id']),
        'godam_empty_id' => mysqli_real_escape_string($connect, $_POST['godam_empty_id']),
        'exp_names' => $exp_names,
        'exp_details' => $exp_details,
        'exp_values' => $exp_values,
        'afg_jmaa_khaata_no' => mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_no']),
        'afg_jmaa_khaata_id' => mysqli_real_escape_string($connect, $_POST['afg_jmaa_khaata_id']),
        'afg_bnaam_khaata_no' => mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_no']),
        'afg_bnaam_khaata_id' => mysqli_real_escape_string($connect, $_POST['afg_bnaam_khaata_id']),
        'total_bill' => mysqli_real_escape_string($connect, $_POST['total_bill']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    $done = update('afghani_truck', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'افغانی ٹرک کرایہ تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>