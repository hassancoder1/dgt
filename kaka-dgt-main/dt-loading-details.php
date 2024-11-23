<a class="btn btn-success pt-0 position-absolute" style="top: 65px; right: 50%; z-index: 9"
   data-bs-toggle="collapse" href="#collapseExample" role="button"
   aria-expanded="false" aria-controls="collapseExample"
   data-tooltip="لوڈنگ تفصیل کو بند کریں یا اوپن کریں"
   data-tooltip-position="bottom">
    <i class="fa fa-plus-square"></i> &nbsp;
    <i class="fa fa-minus-circle"></i>
</a>
<div class="card">
    <div class="p-2">
        <div class="collapse" id="collapseExample">
            <div class="row gx-0 gy-2">
                <div class="col-3">
                    <div class="input-group">
                        <label for="ser" class="input-group-text urdu">لوڈنگ سیریل</label>
                        <input type="text" id="ser" class="form-control" disabled
                               value="<?php echo $id; ?>">
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
                        <input type="text" id="owner_name" name="owner_name"
                               class="form-control input-urdu" disabled
                               value="<?php echo $record['owner_name']; ?>">
                        <label for="jins" class="input-group-text urdu">جنس</label>
                        <input type="text" id="jins" name="jins" class="form-control input-urdu"
                               disabled value="<?php echo $record['jins']; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                        <input type="text" id="truck_no" name="truck_no" class="form-control" required
                               value="<?php echo $record['truck_no']; ?>" disabled>
                        <label for="truck_name" class="input-group-text urdu">ٹرک نام</label>
                        <input type="text" id="truck_name" name="truck_name" tabindex="-1"
                               class="form-control urdu-2 bold" disabled
                               value="<?php echo $record['truck_name']; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                        <input type="text" id="driver_name" name="" disabled
                               class="form-control urdu-2 bold" required readonly
                               value="<?php echo $record['driver_name']; ?>">
                        <label for="driver_mobile" class="input-group-text urdu">موبائل</label>
                        <input type="text" id="driver_mobile" name="driver_mobile" tabindex="-1"
                               class="form-control ltr small-2" disabled
                               value="<?php echo $record['driver_mobile']; ?>">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <label class="input-group-text urdu">لوڈنگ کرنے گودام</label>
                        <?php $loadings = fetch('godam_loading_forms', array('id' => $record['godam_loading_id']));
                        $loading = mysqli_fetch_assoc($loadings); ?>
                        <input type="text" value="<?php echo $loading['name'] ?>"
                               class="form-control bold urdu-2" disabled="">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="input-group">
                        <label class="input-group-text urdu">موبائل نمبر</label>
                        <input type="text" class="form-control ltr bold" disabled
                               value="<?php echo $loading['mobile1'] ?>">
                        <label class="input-group-text urdu">منشی کانام</label>
                        <input type="text" class="form-control urdu-2 bold" disabled
                               value="<?php echo $loading['munshi'] ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label class="input-group-text urdu">پتہ</label>
                        <input type="text" disabled value="<?php echo $loading['address'] ?>"
                               class="form-control urdu-2 bold">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <label class="input-group-text urdu">خالی کرنے گودام</label>
                        <?php $empties = fetch('godam_empty_forms', array('id' => $record['godam_empty_id']));
                        $empty = mysqli_fetch_assoc($empties); ?>
                        <input type="text" value="<?php echo $empty['name'] ?>"
                               class="form-control bold urdu-2" disabled="">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="input-group">
                        <label class="input-group-text urdu">موبائل نمبر</label>
                        <input type="text" class="form-control ltr bold" disabled
                               value="<?php echo $empty['mobile1'] ?>">
                        <label class="input-group-text urdu">منشی کانام</label>
                        <input type="text" class="form-control urdu-2 bold" disabled
                               value="<?php echo $empty['munshi'] ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label class="input-group-text urdu">پتہ</label>
                        <input type="text" disabled value="<?php echo $empty['address'] ?>"
                               class="form-control urdu-2 bold">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <label class="input-group-text urdu">کنسائینی نام</label>
                        <input type="text" class="form-control input-urdu" disabled
                               value="<?php echo $record['consignee_name']; ?>">
                        <label for="sender_city" class="input-group-text urdu">بھیجنے والا شہر</label>
                        <input type="text" class="form-control input-urdu" disabled
                               value="<?php echo $record['sender_city']; ?>">
                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="input-group">
                        <label for="invoice_no" class="input-group-text urdu">انوائس نمبر</label>
                        <input type="text" id="invoice_no" name="invoice_no"
                               class="bold form-control urdu-2" required autofocus
                               value="<?php echo $record['invoice_no']; ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="input-group flatpickr" id="flatpickr-date">
                        <label for="invoice_date" class="input-group-text urdu">انوائس تاریخ</label>
                        <input type="text" id="invoice_date" name="invoice_date"
                               class="bold form-control urdu-2" required data-input
                               value="<?php echo $record['invoice_date']; ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="input-group">
                        <label for="transit_no" class="input-group-text urdu">ٹرانزٹ نمبر</label>
                        <input type="text" id="transit_no" name="transit_no"
                               class="bold form-control urdu-2" required
                               value="<?php echo $record['transit_no']; ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="input-group flatpickr" id="flatpickr-date">
                        <label for="transit_date" class="input-group-text urdu">ٹرانزٹ تاریخ</label>
                        <input type="text" id="transit_date" name="transit_date"
                               value="<?php echo $record['transit_date']; ?>"
                               class="bold form-control urdu-2" required data-input>
                    </div>
                </div>
                <!--importer-->
                <?php if (empty($record['imp_json'])) {
                    $imp = array('', '', '', '');
                } else {
                    $impJson = json_decode($record['imp_json']);
                    $imp = array($impJson->imp_name, $impJson->imp_address, $impJson->imp_mobile, $impJson->imp_email);
                }
                if (empty($record['exp_json'])) {
                    $exp = array('', '', '', '');
                } else {
                    $expJson = json_decode($record['exp_json']);
                    $exp = array($expJson->exp_name, $expJson->exp_address, $expJson->exp_mobile, $expJson->exp_email);
                }
                if (empty($record['imp_ca_json'])) {
                    $impCA = array('', '', '', '');
                } else {
                    $impCaJson = json_decode($record['imp_ca_json']);
                    $impCA = array($impCaJson->imp_ca_name, $impCaJson->imp_ca_address, $impCaJson->imp_ca_mobile, $impCaJson->imp_ca_email);
                }
                if (empty($record['exp_ca_json'])) {
                    $expCA = array('', '', '', '');
                } else {
                    $expCaJson = json_decode($record['exp_ca_json']);
                    $expCA = array($expCaJson->exp_ca_name, $expCaJson->exp_ca_address, $expCaJson->exp_ca_mobile, $expCaJson->exp_ca_email);
                } ?>
                <?php $importersQ = fetch('importers', array('id' => $record['importer_id']));
                $importerData = mysqli_fetch_assoc($importersQ); ?>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="imp_name" class="input-group-text urdu">امپورٹرنام</label>
                        <input type="text" id="imp_name" name="imp_name"
                               class="form-control urdu-2" required
                               value="<?php echo $importerData['name']; ?>">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <label for="imp_address" class="input-group-text urdu">پتہ</label>
                        <input type="text" id="imp_address" name="imp_address"
                               class="form-control" required
                               value="<?php echo $importerData['comp_address']; ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="input-group">
                        <label for="imp_mobile" class="input-group-text urdu">موبائل</label>
                        <input type="text" id="imp_mobile" name="imp_mobile"
                               class="form-control ltr" required
                               value="<?php echo $importerData['mobile']; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="imp_email" class="input-group-text urdu">ای
                            میل</label>
                        <input id="imp_email" name="imp_email" class="form-control" required
                               value="<?php echo $importerData['email']; ?>">
                    </div>
                </div>
                <!--exporter-->
                <?php $exportersQ = fetch('exporters', array('id' => $record['exporter_id']));
                $exporterData = mysqli_fetch_assoc($exportersQ); ?>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="exp_name" class="input-group-text urdu">ایکسپورٹرنام</label>
                        <input type="text" id="exp_name" name="exp_name"
                               class="form-control urdu-2" required
                               value="<?php echo $exporterData['name']; ?>">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <label for="exp_address" class="input-group-text urdu">پتہ</label>
                        <input type="text" id="exp_address" name="exp_address"
                               class="form-control" required
                               value="<?php echo $exporterData['comp_address']; ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="input-group">
                        <label for="exp_mobile" class="input-group-text urdu">موبائل</label>
                        <input type="text" id="exp_mobile" name="exp_mobile"
                               class="form-control ltr" required
                               value="<?php echo $exporterData['mobile']; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="exp_email" class="input-group-text urdu">ای
                            میل</label>
                        <input id="exp_email" name="exp_email" class="form-control" required
                               value="<?php echo $exporterData['email']; ?>">
                    </div>
                </div>
                <!--importer clearing agent-->
                <?php $clearing_agentsQ= fetch('clearing_agents', array('id' => $record['imp_ca_id']));
                $clearing_agentData = mysqli_fetch_assoc($clearing_agentsQ); ?>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="imp_ca_name" class="input-group-text urdu">امپورٹ کلیئرنگ ایجنٹ
                            نام</label>
                        <input type="text" id="imp_ca_name" name="imp_ca_name"
                               class="form-control urdu-2" required
                               value="<?php echo $clearing_agentData['ca_name']; ?>">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <label for="imp_ca_address" class="input-group-text urdu">پتہ</label>
                        <input type="text" id="imp_ca_address" name="imp_ca_address"
                               class="form-control urdu-2" required
                               value="<?php echo $clearing_agentData['ca_city']; ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="input-group">
                        <label for="imp_ca_mobile" class="input-group-text urdu">موبائل</label>
                        <input type="text" id="imp_ca_mobile" name="imp_ca_mobile"
                               class="form-control ltr" value="<?php echo $clearing_agentData['ca_mobile']; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="imp_ca_email" class="input-group-text urdu">ای
                            میل</label>
                        <input id="imp_ca_email" name="imp_ca_email" class="form-control ltr"
                               value="<?php echo $clearing_agentData['ca_email']; ?>">
                    </div>
                </div>
                <!--exporter clearing agent-->
                <?php $ex_clearing_agentsQ= fetch('clearing_agents', array('id' => $record['exp_ca_id']));
                $ex_clearing_agentData = mysqli_fetch_assoc($ex_clearing_agentsQ); ?>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="exp_ca_name" class="input-group-text urdu">ایکسپورٹ کلیئرنگ ایجنٹ
                            نام</label>
                        <input type="text" id="exp_ca_name" name="exp_ca_name"
                               class="form-control urdu-2" required
                               value="<?php echo $ex_clearing_agentData['ca_name']; ?>">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <label for="exp_ca_address" class="input-group-text urdu">پتہ</label>
                        <input type="text" id="exp_ca_address" name="exp_ca_address"
                               class="form-control urdu-2" value="<?php echo $ex_clearing_agentData['ca_city']; ?>">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="input-group">
                        <label for="exp_ca_mobile" class="input-group-text urdu">موبائل</label>
                        <input type="text" id="exp_ca_mobile" name="exp_ca_mobile"
                               class="form-control ltr" value="<?php echo $ex_clearing_agentData['ca_mobile']; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="exp_ca_email" class="input-group-text urdu">ای
                            میل</label>
                        <input id="exp_ca_email" name="exp_ca_email" class="form-control ltr"
                               value="<?php echo $ex_clearing_agentData['ca_email']; ?>">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-group">
                        <label class="input-group-text urdu">رپورٹ</label>
                        <input type="text" class="form-control input-urdu" disabled
                               value="<?php echo $record['report']; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>