<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap mb-1">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n3">ڈاؤن ٹرانزٹ ڈاکومنٹس اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <?php echo backUrl('dt-docs-entry'); ?>
    </div>
</div>
<div class="row g-0">
    <?php if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = mysqli_real_escape_string($connect, $_GET['id']);
        $records = fetch('dt_truck_loadings', array('id' => $id));
        $record = mysqli_fetch_assoc($records); ?>
        <div class="col-md-10">
            <div class="card p-2">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="row gx-0 gy-4">
                        <div class="col-lg-2">
                            <div class="input-group">
                                <label for="today"
                                       class="input-group-text input-group-addon bg-transparent urdu">لوڈنگ
                                    تاریخ</label>
                                <input type="text" id="today" class="form-control bg-transparent border-primary"
                                       value="<?php echo $record['loading_date']; ?>" disabled>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="owner_name" class="input-group-text urdu">مالک نام</label>
                                <input type="text" id="owner_name" name="owner_name"
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
                                       class="form-control urdu-2 bold" required readonly
                                       value="<?php echo $record['truck_name']; ?>">
                            </div>
                            <small id="responseTruck" class="text-danger urdu position-absolute"
                                   style="top: -20px; right: 20px;"></small>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                <input type="text" id="driver_name" name="driver_name" tabindex="-1"
                                       class="form-control urdu-2 bold" required readonly
                                       value="<?php echo $record['driver_name']; ?>">
                                <label for="driver_mobile" class="input-group-text urdu">موبائل</label>
                                <input type="text" id="driver_mobile" name="driver_mobile" tabindex="-1"
                                       class="form-control ltr bold" required placeholder="(+92) 3xx-xxxxxxx"
                                       data-inputmask-alias="(+99) 999-9999999" readonly
                                       value="<?php echo $record['driver_mobile']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4 position-relative">
                            <div class="input-group">
                                <label for="godam_loading_id" class="input-group-text urdu">لوڈنگ کرنے
                                    گودام</label>
                                <select id="godam_loading_id" name="godam_loading_id"
                                        class="urdu-2 form-select js-example-basic-single" style="width: 69%">
                                    <option value="0" selected disabled>انتخاب کریں</option>
                                    <?php $loadings = fetch('godam_loading_forms');
                                    while ($loading = mysqli_fetch_assoc($loadings)) {
                                        if ($loading['id'] == $record['godam_loading_id']) {
                                            $l_selected = 'selected';
                                        } else {
                                            $l_selected = '';
                                        }
                                        echo '<option ' . $l_selected . ' value="' . $loading['id'] . '">' . $loading['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <small id="responseGodamLoading" class="text-danger urdu position-absolute"
                                   style="top: -20px; right: 20px;"></small>
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
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label for="godam_empty_id" class="input-group-text urdu">خالی کرنے
                                    گودام</label>
                                <select id="godam_empty_id" name="godam_empty_id"
                                        class="form-select js-example-basic-single" style="width: 72%">
                                    <?php $empties = fetch('godam_empty_forms');
                                    while ($empty = mysqli_fetch_assoc($empties)) {
                                        if ($empty['id'] == $record['godam_empty_id']) {
                                            $e_selected = 'selected';
                                        } else {
                                            $e_selected = '';
                                        }
                                        echo '<option ' . $e_selected . ' value="' . $empty['id'] . '">' . $empty['name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
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
                        <div class="col-lg-4">
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
                    </div>
                    <?php if (!empty($record['sender_receiver'])) {
                        $sender_receiver = json_decode($record['sender_receiver']);
                        $names = array(
                            'dt_sender_id' => $sender_receiver->dt_sender_id,
                            'dt_comp_name' => $sender_receiver->dt_comp_name,
                            'dt_sender_address' => $sender_receiver->dt_sender_address,
                            'dt_sender_mobile' => $sender_receiver->dt_sender_mobile,
                            'dt_sender_owner' => $sender_receiver->dt_sender_owner,
                            'dt_receiver_id' => $sender_receiver->dt_receiver_id,
                            'dt_comp_name_r' => $sender_receiver->dt_comp_name_r,
                            'dt_receiver_address' => $sender_receiver->dt_receiver_address,
                            'dt_receiver_mobile' => $sender_receiver->dt_receiver_mobile,
                            'dt_receiver_owner' => $sender_receiver->dt_receiver_owner
                        );
                    } else {
                        $names = array(
                            'dt_sender_id' => 0,
                            'dt_comp_name' => '',
                            'dt_sender_address' => '',
                            'dt_sender_mobile' => '',
                            'dt_sender_owner' => '',
                            'dt_receiver_id' => 0,
                            'dt_comp_name_r' => '',
                            'dt_receiver_address' => '',
                            'dt_receiver_mobile' => '',
                            'dt_receiver_owner' => ''
                        );
                    } ?>
                    <!--sender--><!--receiver-->
                    <div class="row gx-0 mb-4 mt-1 gy-4">
                        <div class="col-lg-4 position-relative">
                            <div class="input-group">
                                <label for="dt_sender_id" class="input-group-text urdu">مال بھیجنےوالا</label>
                                <select id="dt_sender_id" name="dt_sender_id"
                                        class="urdu-2 form-select js-example-basic-single" style="width: 77%">
                                    <option value="0" selected disabled>انتخاب کریں</option>
                                    <?php $senders = fetch('senders');
                                    while ($sender = mysqli_fetch_assoc($senders)) {
                                        if ($sender['id'] == $names['dt_sender_id']) {
                                            $s_selected = 'selected';
                                        } else {
                                            $s_selected = '';
                                        }
                                        echo '<option ' . $s_selected . ' value="' . $sender['id'] . '">' . $sender['comp_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <small id="responseDTSender" class="text-danger urdu position-absolute"
                                   style="top: -10px; left: 20px;"></small>
                            <input type="hidden" name="dt_comp_name" id="dt_comp_name"
                                   value="<?php echo $names['dt_comp_name'] ?>">
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="dt_sender_address" class="input-group-text urdu">پتہ</label>
                                <input type="text" id="dt_sender_address" name="dt_sender_address"
                                       class="form-control input-urdu" readonly tabindex="-1"
                                       value="<?php echo $names['dt_sender_address']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label for="dt_sender_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" id="dt_sender_mobile" name="dt_sender_mobile"
                                       class="form-control ltr urdu-2 bold" placeholder="(+92) 3xx-xxxxxxx"
                                       data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1"
                                       value="<?php echo $names['dt_sender_mobile']; ?>">
                                <label for="dt_sender_owner" class="input-group-text urdu">مالک نام</label>
                                <input type="text" id="dt_sender_owner" name="dt_sender_owner"
                                       class="form-control urdu-2 bold" readonly tabindex="-1"
                                       value="<?php echo $names['dt_sender_owner']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4 position-relative">
                            <div class="input-group">
                                <label for="dt_receiver_id" class="input-group-text urdu">مال وصول کرنے
                                    والا</label>
                                <select id="dt_receiver_id" name="dt_receiver_id"
                                        class="urdu-2 form-select js-example-basic-single" style="width: 66%">
                                    <option value="0" selected disabled>انتخاب کریں</option>
                                    <?php $receivers = fetch('receivers');
                                    while ($receiver = mysqli_fetch_assoc($receivers)) {
                                        if ($receiver['id'] == $names['dt_receiver_id']) {
                                            $r_selected = 'selected';
                                        } else {
                                            $r_selected = '';
                                        }
                                        echo '<option ' . $r_selected . ' value="' . $receiver['id'] . '">' . $receiver['comp_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <small id="responseDTReceiver" class="text-danger urdu position-absolute"
                                   style="top: -10px; left: 20px;"></small>
                            <input type="hidden" name="dt_comp_name_r" id="dt_comp_name_r">
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="dt_receiver_address" class="input-group-text urdu">پتہ</label>
                                <input type="text" id="dt_receiver_address" name="dt_receiver_address"
                                       class="form-control input-urdu" readonly tabindex="-1"
                                       value="<?php echo $names['dt_receiver_address']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label for="dt_receiver_mobile" class="input-group-text urdu">موبائل
                                    نمبر</label>
                                <input type="text" id="dt_receiver_mobile" name="dt_receiver_mobile"
                                       class="form-control ltr urdu-2 bold" placeholder="(+92) 3xx-xxxxxxx"
                                       data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1"
                                       value="<?php echo $names['dt_receiver_mobile']; ?>">
                                <label for="dt_receiver_owner" class="input-group-text urdu">مالک نام</label>
                                <input type="text" id="dt_receiver_owner" name="dt_receiver_owner"
                                       class="form-control urdu-2 bold" readonly tabindex="-1"
                                       value="<?php echo $names['dt_receiver_owner']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row gx-0 gy-4">
                        <!--exporter-->
                        <div class="col-lg-3 position-relative">
                            <div class="input-group">
                                <label for="exporter_id" class="input-group-text urdu">ایکسپورٹر</label>
                                <select id="exporter_id" name="exporter_id" required
                                        class="urdu-2 form-select js-example-basic-single" style="width: 78%">
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
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="exp_comp_name" class="input-group-text urdu">کمپنی نام</label>
                                <input type="text" id="exp_comp_name" name="exp_comp_name"
                                       class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="exp_comp_address" class="input-group-text urdu">ایڈریس</label>
                                <input type="text" id="exp_comp_address" name="exp_comp_address" readonly
                                       tabindex="-1"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="exp_comp_ntn" class="input-group-text urdu">این ٹی این</label>
                                <input type="text" id="exp_comp_ntn" name="exp_comp_ntn"
                                       readonly tabindex="-1" class="form-control" required>
                            </div>
                        </div>
                        <!--export clearing agent-->
                        <div class="col-lg-3 position-relative">
                            <div class="input-group">
                                <label for="exp_ca_id" class="input-group-text urdu">ایکسپورٹ کلئیرنگ
                                    ایجنٹ</label>
                                <select id="exp_ca_id" name="exp_ca_id" required
                                        class="urdu-2 form-select js-example-basic-single" style="width: 46%">
                                    <option value="0" selected disabled>انتخاب کریں</option>
                                    <?php $cas = fetch('clearing_agents');
                                    while ($ca = mysqli_fetch_assoc($cas)) {
                                        if ($ca['id'] == $record['exp_ca_id']) {
                                            $exp_ca_selected = 'selected';
                                        } else {
                                            $exp_ca_selected = '';
                                        }
                                        echo '<option ' . $exp_ca_selected . ' value="' . $ca['id'] . '">' . $ca['ca_name'] . '</option>';
                                    }
                                    ?>
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
                                       class="form-control ltr small" required placeholder="(+92) 3xx-xxxxxxx"
                                       data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                <label for="exp_ca_email" class="input-group-text urdu">ای میل</label>
                                <input type="text" id="exp_ca_email" name="exp_ca_email" readonly tabindex="-1"
                                       class="form-control urdu-2 small" required>
                            </div>
                        </div>
                        <!--importer-->
                        <div class="col-lg-3 position-relative">
                            <div class="input-group">
                                <label for="importer_id" class="input-group-text urdu">امپپورٹر</label>
                                <select id="importer_id" name="importer_id" required
                                        class="urdu-2 form-select js-example-basic-single" style="width: 81%">
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
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="imp_comp_name" class="input-group-text urdu">کمپنی نام</label>
                                <input type="text" id="imp_comp_name" name="imp_comp_name"
                                       class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="imp_comp_address" class="input-group-text urdu">کمپنی ایڈریس</label>
                                <input type="text" id="imp_comp_address" name="imp_comp_address" readonly
                                       tabindex="-1"
                                       class="form-control " required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="imp_comp_ntn" class="input-group-text urdu">این ٹی این</label>
                                <input type="text" id="imp_comp_ntn" name="imp_comp_ntn"
                                       readonly tabindex="-1" class="form-control" required>
                            </div>
                        </div>
                        <!--import clearing agent-->
                        <div class="col-lg-3 position-relative">
                            <div class="input-group">
                                <label for="imp_ca_id" class="input-group-text urdu">امپورٹ کلئیرنگ
                                    ایجنٹ</label>
                                <select id="imp_ca_id" name="imp_ca_id" required
                                        class="urdu-2 form-select js-example-basic-single" style="width: 49%">
                                    <option value="0" selected disabled>انتخاب کریں</option>
                                    <?php $cas = fetch('clearing_agents');
                                    while ($ca = mysqli_fetch_assoc($cas)) {
                                        if ($ca['id'] == $record['imp_ca_id']) {
                                            $imp_ca_selected = 'selected';
                                        } else {
                                            $imp_ca_selected = '';
                                        }
                                        echo '<option ' . $imp_ca_selected . ' value="' . $ca['id'] . '">' . $ca['ca_name'] . '</option>';
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
                                       class="form-control ltr small" required placeholder="(+92) 3xx-xxxxxxx"
                                       data-inputmask-alias="(+99) 999-9999999" readonly tabindex="-1">
                                <label for="imp_ca_email" class="input-group-text urdu">ای میل</label>
                                <input type="text" id="imp_ca_email" name="imp_ca_email" readonly tabindex="-1"
                                       class="form-control urdu-2 small" required>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <label for="report" class="input-group-text urdu">رپورٹ</label>
                                <input type="text" id="report" name="report" class="form-control input-urdu"
                                       required value="<?php echo $record['report']; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="file" id="attachments" placeholder="attachments1"
                                       name="attachments[]" class="d-none" multiple>
                                <label for="attachments" class="mb-0">ڈاکومنٹس </label>
                                <input type="button" class="form-control bg-danger  text-white" value="+ Add files"
                                       onclick="document.getElementById('attachments').click();"/>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                    <button type="submit" name="recordUpdate" id="recordUpdate"
                            class="btn btn-dark mt-4 btn-icon-text">
                        <i class="btn-icon-prepend" data-feather="edit-3"></i>
                        درستگی
                    </button>
                </form>
            </div>
        </div>
        <div class="col-md-2 ">
            <div class="card p-2">
                <div class="row">
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
            <div class="card mt-2 p-2">
                <?php $attachments = fetch('attachments', array('source_id' => $id, 'source_name' => 'dt_docs_entry'));
                if (mysqli_num_rows($attachments) > 0) {
                    echo '<h5 class="urdu mb-3 bg-light py-2">سیریل نمبر ' . $id . ' ڈاکومنٹس </h5>';
                    $no = 1;
                    while ($attachment = mysqli_fetch_assoc($attachments)) {
                        $link = 'attachments/' . $attachment['attachment'];
                        echo '<p class="mb-2">' . $no . '. <a class="text-decoration-underline" -download href="' . $link . '" target="_blank">' . $attachment['attachment'] . '</a></p>';
                        $no++;
                    }
                } else {
                    echo '<p class="urdu text-center">کوئی ڈاکومنٹ موجود نہیں</p>';
                } ?>
            </div>
        </div>
    <?php } else { ?>
    <?php } ?>
</div>
<?php include("footer.php"); ?>
<script>$("html, body").animate({scrollTop: $(document).height()}, 1000);</script>
<?php if (isset($_POST['recordUpdate'])) {
    $msg = 'DB Error!!!';
    $msgType = 'danger';
    $hidden_id = $_POST['hidden_id'];
    $url = "dt-docs-entry-add?id=" . $hidden_id;
    /*$imp_json = array('imp_name' => $_POST['imp_name'], 'imp_address' => $_POST['imp_address'], 'imp_mobile' => $_POST['imp_mobile'], 'imp_email' => $_POST['imp_email']);
    $imp_json = json_encode($imp_json, JSON_UNESCAPED_UNICODE);
    $exp_json = array('exp_name' => $_POST['exp_name'], 'exp_address' => $_POST['exp_address'], 'exp_mobile' => $_POST['exp_mobile'], 'exp_email' => $_POST['exp_email']);
    $exp_json = json_encode($exp_json, JSON_UNESCAPED_UNICODE);
    $imp_ca_json = array('imp_ca_name' => $_POST['imp_ca_name'], 'imp_ca_address' => $_POST['imp_ca_address'], 'imp_ca_mobile' => $_POST['imp_ca_mobile'], 'imp_ca_email' => $_POST['imp_ca_email']);
    $imp_ca_json = json_encode($imp_ca_json, JSON_UNESCAPED_UNICODE);
    $exp_ca_json = array('exp_ca_name' => $_POST['exp_ca_name'], 'exp_ca_address' => $_POST['exp_ca_address'], 'exp_ca_mobile' => $_POST['exp_ca_mobile'], 'exp_ca_email' => $_POST['exp_ca_email']);
    $exp_ca_json = json_encode($exp_ca_json, JSON_UNESCAPED_UNICODE);*/
    $sender_receiver = array(
        'dt_sender_id' => $_POST['dt_sender_id'],
        'dt_comp_name' => $_POST['dt_comp_name'],
        'dt_sender_address' => $_POST['dt_sender_address'],
        'dt_sender_mobile' => $_POST['dt_sender_mobile'],
        'dt_sender_owner' => $_POST['dt_sender_owner'],
        'dt_receiver_id' => $_POST['dt_receiver_id'],
        'dt_comp_name_r' => $_POST['dt_comp_name_r'],
        'dt_receiver_address' => $_POST['dt_receiver_address'],
        'dt_receiver_mobile' => $_POST['dt_receiver_mobile'],
        'dt_receiver_owner' => $_POST['dt_receiver_owner']
    );
    $data = array(
        'owner_name' => mysqli_real_escape_string($connect, $_POST['owner_name']),
        'jins' => mysqli_real_escape_string($connect, $_POST['jins']),
        'tl_id' => mysqli_real_escape_string($connect, $_POST['tl_id']),//truck ID
        'truck_no' => mysqli_real_escape_string($connect, $_POST['truck_no']),
        'truck_name' => mysqli_real_escape_string($connect, $_POST['truck_name']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'driver_mobile' => mysqli_real_escape_string($connect, $_POST['driver_mobile']),
        'godam_loading_id' => mysqli_real_escape_string($connect, $_POST['godam_loading_id']),
        'godam_empty_id' => mysqli_real_escape_string($connect, $_POST['godam_empty_id']),
        'invoice_no' => mysqli_real_escape_string($connect, $_POST['invoice_no']),
        'invoice_date' => mysqli_real_escape_string($connect, $_POST['invoice_date']),
        'transit_no' => mysqli_real_escape_string($connect, $_POST['transit_no']),
        'transit_date' => mysqli_real_escape_string($connect, $_POST['transit_date']),
        'consignee_name' => mysqli_real_escape_string($connect, $_POST['consignee_name']),
        'sender_city' => mysqli_real_escape_string($connect, $_POST['sender_city']),
        'sender_receiver' => json_encode($sender_receiver),
        'importer_id' => mysqli_real_escape_string($connect, $_POST['importer_id']),
        'imp_ca_id' => mysqli_real_escape_string($connect, $_POST['imp_ca_id']),
        'exporter_id' => mysqli_real_escape_string($connect, $_POST['exporter_id']),
        'exp_ca_id' => mysqli_real_escape_string($connect, $_POST['exp_ca_id']),
        'report' => mysqli_real_escape_string($connect, $_POST['report']),
        'updated_at' => date('Y - m - d H:i:s'),
        'updated_by' => $userId
    );
    $done = update('dt_truck_loadings', $data, array('id' => $hidden_id));
    if ($done) {
        $msg = 'ڈاؤن ٹرانزٹ ٹرک لوڈنگ میں ڈاکومنٹس انٹری ہوگئی۔';
        $msgType = 'success';
    }
    foreach ($_FILES["attachments"]["tmp_name"] as $key => $tmp_name) {
        if ($_FILES['attachments']['error'][$key] == 4 || ($_FILES['attachments']['size'][$key] == 0 && $_FILES['attachments']['error'][$key] == 0)) {
        } else {
            $att = saveAttachment($hidden_id, 'dt_docs_entry', basename($_FILES["attachments"]["name"][$key]));
            $msg .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
            $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
            $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
        }
    }
    message($msgType, $url, $msg);
} ?>
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
            data: {
                truck_no: truck_no
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#recordUpdate").prop('disabled', false);
                    $("#tl_id").val(response.messages['tl_id']);
                    $("#truck_name").val(response.messages['truck_name']);
                    $("#driver_name").val(response.messages['driver_name']);
                    $("#driver_mobile").val(response.messages['d_mobile1']);
                    $("#responseTruck").text('');
                    //$("#jm_kh_tafseel").text(response.messages['khaata_name']);
                }
                if (response.success === false) {
                    $("#recordUpdate").prop('disabled', true);
                    $("#truck_name").val('');
                    $("#driver_name").val('');
                    $("#driver_mobile").val('');
                    $("#responseTruck").text('ٹرک نمبر درست نہیں ہے');
                    //$("#khaata_id1").val(khaata_id);
                }
            }
        });
    }
</script>
<script>
    /*dt sender*/
    $(function () {
        var dt_sender_id = $('#dt_sender_id').val();
        senderAjax(dt_sender_id);
    });
    $('#dt_sender_id').change(function () {
        var
            dt_sender_id = $(this).val();
        senderAjax(dt_sender_id);
    });

    function senderAjax(dt_sender_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleDTSender.php',
            type: 'post',
            data: {
                dt_sender_id: dt_sender_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    console.log(response);
                    $("#dt_comp_name").val(response.messages['comp_name']);
                    $("#dt_sender_address").val(response.messages['address']);
                    $("#dt_sender_mobile").val(response.messages['mobile']);
                    $("#dt_sender_owner").val(response.messages['comp_owner_name']);
                    $("#responseDTSender").text('');
                }
                if (response.success === false) {
                    $("#responseDTSender").text('مال بھیجنےوالا');
                }
            }
        });
    }

    /*dtreceiver*/
    $(function () {
        var
            dt_receiver_id = $('#dt_receiver_id').val();
        receiverAjax(dt_receiver_id);
    });
    $('#dt_receiver_id').change(function () {
        var
            dt_receiver_id = $(this).val();
        receiverAjax(dt_receiver_id);
    });

    function receiverAjax(dt_receiver_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleDTReceiver.php',
            type: 'post',
            data: {
                dt_receiver_id: dt_receiver_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#dt_comp_name_r").val(response.messages['comp_name']);
                    $("#dt_receiver_address").val(response.messages['address']);
                    $("#dt_receiver_mobile").val(response.messages['mobile']);
                    $("#receiver_whatsapp").val(response.messages['whatsapp']);
                    $("#responseDTReceiver").text('');
                }
                if (response.success === false) {
                    $("#responseDTReceiver").text('مال وصول کرنے والا');
                }
            }
        });
    }

    $('#recordUpdate').prop('disabled', true);

    function enableDisableBtn() {
        var
            exp_mobile = $('#exp_mobile').val();
        var
            exp_ca_mobile = $('#exp_ca_mobile').val();
        var
            imp_mobile = $('#imp_mobile').val();
        var
            imp_ca_mobile = $('#imp_ca_mobile').val();

        if (exp_mobile != '' && imp_mobile != '' && exp_ca_mobile != '' && imp_ca_mobile != '') {
            $('#recordUpdate').prop('disabled', false);
        }
    }

    /*godam_loading_id*/
    $(function () {
        var
            sel_godam_loading_id = $('#godam_loading_id').val();
        loadingAjax(sel_godam_loading_id);
    });
    $('#godam_loading_id').change(function () {
        var
            godam_loading_id = $(this).val();
        loadingAjax(godam_loading_id);
    });

    function loadingAjax(godam_loading_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleGodamLoading.php',
            type: 'post',
            data: {
                godam_loading_id: godam_loading_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#godam_loading_mobile").val(response.messages['mobile1']);
                    $("#godam_loading_munshi").val(response.messages['munshi']);
                    $("#godam_loading_address").val(response.messages['address']);
                    $("#responseGodamLoading").text('');
                }
                if (response.success === false) {
                    $("#responseGodamLoading").text('لوڈنگ کرنے گودام درست نہیں ہے');
                }
            }
        });
    }

    /*godam_empty_id*/
    $(function () {
        var
            sel_godam_empty_id = $('#godam_empty_id').val();
        emptyAjax(sel_godam_empty_id);
    });
    $('#godam_empty_id').change(function () {
        var
            godam_empty_id = $(this).val();
        emptyAjax(godam_empty_id);
    });

    function emptyAjax(godam_empty_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleGodamEmpty.php',
            type: 'post',
            data: {
                godam_empty_id: godam_empty_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#godam_empty_mobile").val(response.messages['mobile1']);
                    $("#godam_empty_munshi").val(response.messages['munshi']);
                    $("#godam_empty_address").val(response.messages['address']);
                    $("#responseGodamEmpty").text('');
                }
                if (response.success === false) {
                    $("#responseGodamEmpty").text('خالی کرنے گودام درست نہیں ہے');
                }
            }
        });
    }

    /*exporter*/
    $(function () {
        expAjax($('#exporter_id').val());
    });
    $('#exporter_id').change(function () {
        expAjax($(this).val());
    });

    function expAjax(exporter_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleExporter.php',
            type: 'post',
            data: {
                exporter_id: exporter_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#exp_comp_name").val(response.messages['comp_name']);
                    $("#exp_comp_address").val(response.messages['comp_address']);
                    $("#exp_comp_ntn").val(response.messages['comp_ntn']);
                    $("#responseExporter").text('');
                    enableDisableBtn();
                }
                if (response.success === false) {
                    $("#responseExporter").text('ایکسپورٹر');
                }
            }
        });
    }

    /*importer*/
    $(function () {
        var
            importer_id = $('#importer_id').val();
        impAjax(importer_id);
    });
    $('#importer_id').change(function () {
        var
            importer_id = $(this).val();
        impAjax(importer_id);
    });

    function impAjax(importer_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleImporter.php',
            type: 'post',
            data: {
                importer_id: importer_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#imp_comp_name").val(response.messages['comp_name']);
                    $("#imp_comp_address").val(response.messages['comp_address']);
                    $("#imp_comp_ntn").val(response.messages['comp_ntn']);
                    $("#responseImporter").text('');
                    enableDisableBtn();
                }
                if (response.success === false) {
                    $("#responseImporter").text('امپورٹر');
                }
            }
        });
    }

    /*import agent*/
    $(function () {
        var
            imp_ca_id = $('#imp_ca_id').val();
        impCaAjax(imp_ca_id);
    });
    $('#imp_ca_id').change(function () {
        var
            imp_ca_id = $(this).val();
        impCaAjax(imp_ca_id);
    });

    function impCaAjax(imp_ca_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleClearingAgent.php',
            type: 'post',
            data: {
                ca_id: imp_ca_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#imp_ca_mobile").val(response.messages['ca_mobile']);
                    $("#imp_ca_email").val(response.messages['ca_email']);
                    $("#imp_ca_license").val(response.messages['ca_license']);
                    $("#imp_ca_license_no").val(response.messages['ca_license_no']);
                    $("#responseImpCa").text('');
                    enableDisableBtn();
                }
                if (response.success === false) {
                    $("#responseImpCa").text('امپورٹ ایجنٹ');
                }
            }
        });
    }

    /*export agent*/
    $(function () {
        var
            exp_ca_id = $('#exp_ca_id').val();
        expCaAjax(exp_ca_id);
    });
    $('#exp_ca_id').change(function () {
        var
            exp_ca_id = $(this).val();
        expCaAjax(exp_ca_id);
    });

    function expCaAjax(exp_ca_id = null) {
        $.ajax({
            url: 'ajax/fetchSingleClearingAgent.php',
            type: 'post',
            data: {
                ca_id: exp_ca_id
            },
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#exp_ca_mobile").val(response.messages['ca_mobile']);
                    $("#exp_ca_email").val(response.messages['ca_email']);
                    $("#exp_ca_license").val(response.messages['ca_license']);
                    $("#exp_ca_license_no").val(response.messages['ca_license_no']);
                    $("#responseExpCa").text('');
                    enableDisableBtn();
                }
                if (response.success === false) {
                    $("#responseExpCa").text('ایکسپورٹ ایجنٹ');
                }
            }
        });
    }
</script>
