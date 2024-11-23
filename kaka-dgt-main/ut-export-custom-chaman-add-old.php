<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h3 class="mb-3 mb-md-0 mt-n2 urdu-2">ایکسپورٹ کسٹم چمن انٹری</h3>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="ut-export-custom-chaman.php"
               class="btn btn-dark btn-icon-text mb-2 mb-md-0 pt-0">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>
        </div>
    </div>
    <div class="row">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <?php if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('ut_bail_entries', array('id' => $id));
            $record = mysqli_fetch_assoc($records);
            if ($record['is_surrender'] == 1 ) { ?>
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <form method="post">
                                <div class="row gx-0 gy-4">
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="bill_no" class="input-group-text urdu">بل نمبر</label>
                                            <input type="text" id="bill_no" name="bill_no"
                                                   class="form-control input-urdu"
                                                   required value="<?php echo $record['bill_no']; ?>">
                                            <label for="jins" class="input-group-text urdu">جنس</label>
                                            <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                                   required
                                                   value="<?php echo $record['jins']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <label for="consignee_name" class="input-group-text urdu">کنسائینی
                                                نام</label>
                                            <input type="text" id="consignee_name" name="consignee_name"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $record['consignee_name']; ?>">
                                            <label for="loading_city" class="input-group-text urdu">لوڈشہرکانام</label>
                                            <input type="text" id="loading_city" name="loading_city"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $record['loading_city']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <label for="bardana_qty" class="input-group-text urdu">باردن تعداد</label>
                                            <input type="text" id="bardana_qty" name="bardana_qty"
                                                   class="form-control currency" required
                                                   value="<?php echo $record['bardana_qty']; ?>">
                                            <label for="bardana_name" class="input-group-text urdu">باردن نام</label>
                                            <input type="text" id="bardana_name" name="bardana_name"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $record['bardana_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <label for="total_wt" class="input-group-text urdu">ٹوٹل وزن</label>
                                            <input type="text" id="total_wt" name="total_wt"
                                                   class="form-control currency"
                                                   required value="<?php echo $record['total_wt']; ?>">
                                            <label for="saaf_wt" class="input-group-text urdu">صاف وزن</label>
                                            <input type="text" id="saaf_wt" name="saaf_wt" class="form-control currency"
                                                   required
                                                   value="<?php echo $record['saaf_wt']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="input-group">
                                            <label for="report" class="input-group-text urdu">رپورٹ</label>
                                            <input type="text" id="report" name="report" class="form-control input-urdu"
                                                   required value="<?php echo $record['report']; ?>">
                                        </div>
                                    </div>
                                    <?php $sender_json = json_decode($record['sender_json']); ?>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <label for="sender_name" class="input-group-text urdu">مال بھیجنے
                                                والےکانام</label>
                                            <input type="text" id="sender_name" name="sender_name"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $sender_json->sender_name; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <label for="sender_mobile" class="input-group-text urdu">موبائل</label>
                                            <input type="text" id="sender_mobile" name="sender_mobile"
                                                   class="form-control ltr" required placeholder="(+92) 3xx-xxxxxxx"
                                                   data-inputmask-alias="(+99) 999-9999999"
                                                   value="<?php echo $sender_json->sender_mobile; ?>">
                                            <label for="sender_email" class="input-group-text">ای میل</label>
                                            <input type="text" id="sender_email" name="sender_email"
                                                   required data-inputmask="'alias': 'email'"
                                                   class="form-control ltr"
                                                   value="<?php echo $sender_json->sender_email; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="sender_address" class="input-group-text urdu">پتہ</label>
                                            <input type="text" id="sender_address" name="sender_address"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $sender_json->sender_address; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 position-relative">
                                        <div class="input-group">
                                            <label for="exporter_id"
                                                   class="input-group-text urdu">ایکسپورٹرکانام</label>
                                            <select id="exporter_id" name="exporter_id"
                                                    class="urdu-2 form-select js-example-basic-single"
                                                    style="width: 75%">
                                                <option value="0" selected disabled>انتخاب کریں</option>
                                                <?php $exporters = fetch('exporters');
                                                while ($exporter = mysqli_fetch_assoc($exporters)) {
                                                    if ($exporter['id'] == $record['exporter_id']) {
                                                        $exp_selected = 'selected';
                                                    } else {
                                                        $exp_selected = '';
                                                    }
                                                    echo '<option ' . $exp_selected . ' value="' . $exporter['id'] . '">' . $exporter['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <small id="responseExporter" class="text-danger urdu position-absolute"
                                               style="top: 0; left: 30px;"></small>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <label for="exp_mobile" class="input-group-text urdu">موبائل
                                                نمبر</label>
                                            <input type="text" id="exp_mobile" name="exp_mobile"
                                                   class="form-control ltr bold" required
                                                   placeholder="(+92) 3xx-xxxxxxx"
                                                   data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                            <label for="exp_email" class="input-group-text urdu">ای میل</label>
                                            <input type="text" id="exp_email" name="exp_email" readonly tabindex="-1"
                                                   class="form-control urdu-2 bold" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="exp_city" class="input-group-text urdu">شہر</label>
                                            <input type="text" id="exp_city" name="exp_city"
                                                   readonly tabindex="-1" class="form-control urdu-2 bold" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 position-relative">
                                        <div class="input-group">
                                            <label for="importer_id" class="input-group-text urdu">امپپورٹرکانام</label>
                                            <select id="importer_id" name="importer_id"
                                                    class="urdu-2 form-select js-example-basic-single"
                                                    style="width: 75%">
                                                <option value="0" selected disabled>انتخاب کریں</option>
                                                <?php $importers = fetch('importers');
                                                while ($importer = mysqli_fetch_assoc($importers)) {
                                                    if ($importer['id'] == $record['importer_id']) {
                                                        $imp_selected = 'selected';
                                                    } else {
                                                        $imp_selected = '';
                                                    }
                                                    echo '<option ' . $imp_selected . ' value="' . $importer['id'] . '">' . $importer['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <small id="responseImporter" class="text-danger urdu position-absolute"
                                               style="top: 0; left: 30px;"></small>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <label for="imp_mobile" class="input-group-text urdu">موبائل
                                                نمبر</label>
                                            <input type="text" id="imp_mobile" name="imp_mobile"
                                                   class="form-control ltr bold" required
                                                   placeholder="(+92) 3xx-xxxxxxx"
                                                   data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                            <label for="imp_email" class="input-group-text urdu">ای میل</label>
                                            <input type="text" id="imp_email" name="imp_email" readonly tabindex="-1"
                                                   class="form-control urdu-2 bold" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="imp_city" class="input-group-text urdu">شہر</label>
                                            <input type="text" id="imp_city" name="imp_city"
                                                   readonly tabindex="-1" class="form-control urdu-2 bold" required>
                                        </div>
                                    </div>
                                </div>
                                <?php /*سلنڈر / surrender info*/
                                if (empty($record['surrender_json'])) {
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
                                <div class="row gx-0 gy-4 mt-2">
                                    <div class="col-lg-2">
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                            <label for="sr_date" class="input-group-text urdu">سلنڈر تاریخ</label>
                                            <input value="<?php echo $srJson['sr_date']; ?>" type="text" name="sr_date"
                                                   class="form-control" id="sr_date" required data-input>
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
                                <div class="row gx-0 gy-4 mt-2">
                                    <div class="col-lg-2">
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                            <label for="imp_cus_loading_date" class="input-group-text urdu">لوڈنگ
                                                تاریخ</label>
                                            <input value="<?php echo $impCusJson['imp_cus_loading_date']; ?>"
                                                   type="text" name="imp_cus_loading_date" class="form-control"
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
                                            <label for="imp_cus_truck_name" class="input-group-text urdu">ٹرک
                                                نام</label>
                                            <input type="text" id="imp_cus_truck_name" name="imp_cus_truck_name"
                                                   class="form-control urdu-2 bold" required
                                                   value="<?php echo $impCusJson['imp_cus_truck_no']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="input-group">
                                            <label for="imp_cus_driver_name" class="input-group-text urdu">ڈرائیور
                                                نام</label>
                                            <input type="text" id="imp_cus_driver_name" name="imp_cus_driver_name"
                                                   class="form-control input-urdu" required
                                                   value="<?php echo $impCusJson['imp_cus_driver_name']; ?>">
                                            <label for="imp_cus_driver_mobile"
                                                   class="input-group-text urdu">موبائل</label>
                                            <input type="text" id="imp_cus_driver_mobile" name="imp_cus_driver_mobile"
                                                   class="form-control ltr bold" required
                                                   placeholder="(+92) 3xx-xxxxxxx"
                                                   data-inputmask-alias="(+99) 999-9999999"
                                                   value="<?php echo $impCusJson['imp_cus_driver_mobile']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                            <label for="imp_cus_clearing_date" class="input-group-text urdu">کلئیرنگ
                                                تاریخ</label>
                                            <input value="<?php echo $impCusJson['imp_cus_clearing_date']; ?>"
                                                   type="text"
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
                                            <label for="imp_cus_seal_no" class="input-group-text urdu">کسٹم سیل
                                                نمبر</label>
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
                                <hr>
                                <div class="row gx-0 gy-4">
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
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="exp_cus_gd_no" class="input-group-text urdu">جی ڈی نمبر</label>
                                            <input type="text" id="exp_cus_gd_no" name="exp_cus_gd_no"
                                                   class="form-control" required
                                                   value="<?php echo $expCusJson['exp_cus_gd_no']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="exp_cus_scart_no" class="input-group-text urdu">سکارٹ نمبر</label>
                                            <input type="text" id="exp_cus_scart_no" name="exp_cus_scart_no"
                                                   class="form-control" required
                                                   value="<?php echo $expCusJson['exp_cus_scart_no']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
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
                                        class="btn btn-dark mt-4 btn-icon-text">
                                    <i class="btn-icon-prepend" data-feather="edit-3"></i>محفوظ کریں
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
                message('danger', 'ut-export-custom-chaman.php', 'دوبارہ کوشش کریں');
            }
        } else {
            message('danger', 'ut-export-custom-chaman.php', 'دوبارہ کوشش کریں');
        } ?>
    </div>
<?php include("footer.php"); ?>
    <script>
        $(function () {
            var exporter_id = $('#exporter_id').val();
            expAjax(exporter_id);
        });
        $('#exporter_id').change(function () {
            var exporter_id = $(this).val();
            expAjax(exporter_id);
        });
        function expAjax(exporter_id=null) {
            $.ajax({
                url: 'ajax/fetchSingleExporter.php',
                type: 'post',
                data: {exporter_id: exporter_id},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        $("#exp_mobile").val(response.messages['mobile']);
                        $("#exp_email").val(response.messages['email']);
                        $("#exp_city").val(response.messages['city']);
                        $("#responseExporter").text('');
                    }
                    if (response.success === false) {
                        $("#responseExporter").text('ایکسپورٹر سیلیکٹ کریں');
                    }
                }
            });
        }

        $(function () {
            var importer_id = $('#importer_id').val();
            impAjax(importer_id);
        });
        $('#importer_id').change(function () {
            var importer_id = $(this).val();
            impAjax(importer_id);
        });
        function impAjax(importer_id=null) {
            $.ajax({
                url: 'ajax/fetchSingleImporter.php',
                type: 'post',
                data: {importer_id: importer_id},
                dataType: 'json',
                success: function (response) {
                    if (response.success === true) {
                        $("#imp_mobile").val(response.messages['mobile']);
                        $("#imp_email").val(response.messages['email']);
                        $("#imp_city").val(response.messages['city']);
                        $("#responseImporter").text('');
                    }
                    if (response.success === false) {
                        $("#responseImporter").text('امپورٹر سیلیکٹ کریں');
                    }
                }
            });
        }


    </script>
<?php if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "ut-export-custom-chaman-add.php?id=" . $hidden_id;
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
        message('success', $url, 'ایکسپورٹ کسٹم چمن انٹری محفوظ ہوگئی ہے۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>