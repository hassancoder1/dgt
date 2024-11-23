<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n3">سلنڈر بیل انٹری</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="ut-surrender-bails"
           class="btn btn-dark btn-icon-text p-1 pt-0 mt-n2">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>
    </div>
</div>
<?php if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('ut_bail_entries', array('id' => $id));
    $record = mysqli_fetch_assoc($records); ?>
    <form method="post">
        <div class="row gx-0">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body_ p-2">
                        <?php include("ut-bail-entry-inc.php"); ?>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($record['surrender_json'])) {
                            $srJson = array(
                                'sr_date' => date('Y-m-d'),
                                'sr_bill_no' => '',
                                'sr_shipping_lane' => '',
                                'sr_container_no' => '',
                                'sr_container_name' => '',
                                'sr_port_date' => date('Y-m-d'),
                                'sr_free_days' => '',
                                'sr_port_name' => ''
                            );
                        } else {
                            $surrender_json = json_decode($record['surrender_json']);
                            $srJson = array(
                                'sr_date' => $surrender_json->sr_date,
                                'sr_bill_no' => $surrender_json->sr_bill_no,
                                'sr_shipping_lane' => $surrender_json->sr_shipping_lane,
                                'sr_container_no' => $surrender_json->sr_container_no,
                                'sr_container_name' => $surrender_json->sr_container_name,
                                'sr_port_date' => $surrender_json->sr_port_date,
                                'sr_free_days' => $surrender_json->sr_free_days,
                                'sr_port_name' => $surrender_json->sr_port_name
                            );
                        } ?>
                        <div class="row gx-0 gy-4">
                            <div class="col-lg-2">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <label for="sr_date" class="input-group-text urdu">سلنڈر تاریخ</label>
                                    <input value="<?php echo $srJson['sr_date']; ?>" type="text" name="sr_date"
                                           autofocus class="form-control" id="sr_date" required data-input>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="sr_bill_no" class="input-group-text urdu">بل نمبر</label>
                                    <input type="text" id="sr_bill_no" name="sr_bill_no"
                                           class="form-control" required
                                           value="<?php echo $srJson['sr_bill_no']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="sr_shipping_lane" class="input-group-text urdu">شپنگ لین
                                        کانام</label>
                                    <input type="text" id="sr_shipping_lane" name="sr_shipping_lane"
                                           class="form-control" required
                                           value="<?php echo $srJson['sr_shipping_lane']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="sr_container_no" class="input-group-text urdu">کنٹینر
                                        نمبر</label>
                                    <input type="text" id="sr_container_no" name="sr_container_no"
                                           class="form-control" required
                                           value="<?php echo $srJson['sr_container_no']; ?>">
                                </div>

                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="sr_container_name" class="input-group-text urdu">کنٹینر
                                        نام</label>
                                    <input type="text" id="sr_container_name" name="sr_container_name"
                                           class="form-control" required
                                           value="<?php echo $srJson['sr_container_name']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <label for="sr_port_date" class="input-group-text urdu">پورٹ پہنچ
                                        تاریخ</label>
                                    <input value="<?php echo $srJson['sr_port_date']; ?>" type="text"
                                           name="sr_port_date"
                                           class="form-control" id="sr_port_date" required data-input>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="sr_free_days" class="input-group-text urdu">فری دن</label>
                                    <input type="text" id="sr_free_days" name="sr_free_days"
                                           class="form-control" required
                                           value="<?php echo $srJson['sr_free_days']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="sr_port_name" class="input-group-text urdu">پورٹ نام</label>
                                    <input type="text" id="sr_port_name" name="sr_port_name"
                                           class="form-control" required
                                           value="<?php echo $srJson['sr_port_name']; ?>">
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
                <div class="card mt-2">
                    <p class="bg-success bg-opacity-10 text-white- urdu py-2 text-center">ایجنٹس کو ٹرانسفر</p>
                    <div class="p-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group">
                                    <label class="input-group-text urdu-2" for="karachi_user_ids">کراچی</label>
                                    <select name="karachi_user_ids[]" id="karachi_user_ids" required class="agent-select w-70">
                                        <option value="" disabled selected>کراچی ایجنٹ</option>
                                        <?php $json = array();
                                        $form_selected = '';
                                        if (!empty($record['karachi_user_ids'])) {
                                            $json = json_decode($record['karachi_user_ids']);
                                        }
                                        $json = implode(',', $json);
                                        $json = explode(',', $json);
                                        $tt = fetch('users');
                                        while ($t = mysqli_fetch_assoc($tt)) {
                                            if (in_array($t['id'], $json)) {
                                                $form_selected = 'selected';
                                            } else {
                                                $form_selected = '';
                                            }
                                            echo '<option ' . $form_selected . ' value="' . $t['id'] . '">' . $t['full_name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="input-group mt-3">
                                    <label class="input-group-text urdu-2" for="chaman_user_ids">چمن</label>
                                    <select name="chaman_user_ids[]" id="chaman_user_ids" required
                                            class="agent-select w-75">
                                        <option value="" disabled selected>چمن ایجنٹ</option>
                                        <?php $json2 = array();
                                        if (!empty($record['chaman_user_ids'])) {
                                            $json2 = json_decode($record['chaman_user_ids']);
                                        }
                                        $json2 = implode(',', $json2);
                                        $json2 = explode(',', $json2);
                                        $tt = fetch('users');
                                        while ($t = mysqli_fetch_assoc($tt)) {
                                            $ccc = (in_array($t['id'], $json2)) ? 'selected' : '';
                                            echo '<option ' . $ccc . ' value="' . $t['id'] . '">' . $t['full_name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="input-group mt-3">
                                    <label class="input-group-text urdu-2" for="border_user_ids">بارڈر</label>
                                    <select name="border_user_ids[]" id="border_user_ids" required
                                            class="agent-select w-75">
                                        <option value="" disabled selected>بارڈر ایجنٹ</option>
                                        <?php $json3 = array();
                                        if (!empty($record['border_user_ids'])) {
                                            $json3 = json_decode($record['border_user_ids']);
                                        }
                                        $json3 = implode(',', $json3);
                                        $json3 = explode(',', $json3);
                                        $tt = fetch('users');
                                        while ($t = mysqli_fetch_assoc($tt)) {
                                            $bbb = (in_array($t['id'], $json3)) ? 'selected' : '';
                                            echo '<option ' . $bbb . ' value="' . $t['id'] . '">' . $t['full_name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="input-group mt-3">
                                    <label class="input-group-text urdu-2" for="qandhar_user_ids">قندھار</label>
                                    <select name="qandhar_user_ids[]" id="qandhar_user_ids" required
                                            class="agent-select w-70">
                                        <option hidden value="-1" disabled selected>قندھار ایجنٹ</option>
                                        <?php $json4 = array();
                                        if (!empty($record['qandhar_user_ids'])) {
                                            $json4 = json_decode($record['qandhar_user_ids']);
                                            $json4 = implode(',', $json4);
                                            $json4 = explode(',', $json4);
                                        }
                                        $tt = fetch('users');
                                        while ($t = mysqli_fetch_assoc($tt)) {
                                            $qqq = (in_array($t['id'], $json4)) ? 'selected' : '';
                                            echo '<option ' . $qqq . ' value="' . $t['id'] . '">' . $t['full_name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php } else {
    message('danger', 'ut-surrender-bails', 'دوبارہ کوشش کریں');
} ?>
<?php include("footer.php"); ?>
<script>
    $("html, body").animate({scrollTop: $(document).height()}, 1000);
</script>
<script src="assets/js/ut-bail-dropdowns.js"></script>
<?php if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "ut-surrender-bails-add?id=" . $hidden_id;
    $surrenderArray = array(
        'sr_date' => mysqli_real_escape_string($connect, $_POST['sr_date']),
        'sr_bill_no' => mysqli_real_escape_string($connect, $_POST['sr_bill_no']),
        'sr_shipping_lane' => mysqli_real_escape_string($connect, $_POST['sr_shipping_lane']),
        'sr_container_no' => mysqli_real_escape_string($connect, $_POST['sr_container_no']),
        'sr_container_name' => mysqli_real_escape_string($connect, $_POST['sr_container_name']),
        'sr_port_date' => mysqli_real_escape_string($connect, $_POST['sr_port_date']),
        'sr_free_days' => mysqli_real_escape_string($connect, $_POST['sr_free_days']),
        'sr_port_name' => mysqli_real_escape_string($connect, $_POST['sr_port_name']),
        'sr_added_at' => date('Y-m-d')
    );
    $surrender_json = json_encode($surrenderArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'surrender_json' => $surrender_json,
        'karachi_user_ids' => json_encode($_POST['karachi_user_ids']),
        'border_user_ids' => json_encode($_POST['border_user_ids']),
        'chaman_user_ids' => json_encode($_POST['chaman_user_ids']),
        'qandhar_user_ids' => json_encode($_POST['qandhar_user_ids'])
    );
    $done = update('ut_bail_entries', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'سلنڈر بیل انٹری محفوظ ہوگئی ہے۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>
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
