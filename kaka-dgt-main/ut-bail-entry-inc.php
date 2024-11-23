<div class="row gx-0 gy-4">
    <div class="col-lg-3">
        <div class="input-group">
            <label for="bill_no" class="input-group-text urdu">بل نمبر</label>
            <input type="text" id="bill_no" name="bill_no"
                   class="form-control input-urdu" required
                   value="<?php echo $record['bill_no']; ?>">
            <label for="jins" class="input-group-text urdu">جنس</label>
            <input type="text" id="jins" name="jins" class="form-control input-urdu"
                   required
                   value="<?php echo $record['jins']; ?>">
        </div>
    </div>
    <div class="col-lg-5">
        <div class="input-group">
            <label for="consignee_name" class="input-group-text urdu">کنسائینی نام</label>
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
            <input type="text" id="total_wt" name="total_wt" class="form-control currency"
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
    <?php if (!empty($record['sender_receiver'])) {
        $sender_receiver = json_decode($record['sender_receiver']);
        $names = array(
            'sender_id' => $sender_receiver->sender_id,
            'receiver_id' => $sender_receiver->receiver_id
        );
    } else {
        $names = array(
            'sender_id' => 0,
            'receiver_id' => 0
        );
    } ?>
    <!--sender /  receiver-->
    <?php if (!ClearingAgent()) { ?>
        <div class="col-lg-4 position-relative">
            <div class="input-group">
                <label for="sender_id" class="input-group-text urdu">مال بھیجنےوالا</label>
                <select id="sender_id" name="sender_id"
                        class="form-control border-bottom-0 virtual-select">
                    <option value="0" selected disabled>انتخاب کریں</option>
                    <?php $senders = fetch('senders');
                    while ($sender = mysqli_fetch_assoc($senders)) {
                        $s_selected = ($sender['id'] == $names['sender_id']) ? 'selected' : '';
                        echo '<option ' . $s_selected . ' value="' . $sender['id'] . '">' . $sender['comp_owner_name'] . '</option>';
                    } ?>
                </select>
            </div>
            <small id="responseDTSender" class="text-danger urdu position-absolute"
                   style="top: -10px; left: 20px;"></small>
        </div>
        <div class="col-lg-3">
            <div class="input-group">
                <label for="sender_address" class="input-group-text urdu">پتہ</label>
                <input type="text" id="sender_address" name="sender_address"
                       class="form-control urdu" readonly tabindex="-1">
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
                        $r_selected = ($receiver['id'] == $names['receiver_id']) ? 'selected' : '';
                        echo '<option ' . $r_selected . ' value="' . $receiver['id'] . '">' . $receiver['comp_owner_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <small id="responseDTReceiver" class="text-danger urdu position-absolute"
                   style="top: -10px; left: 20px;"></small>
        </div>
        <div class="col-lg-3">
            <div class="input-group">
                <label for="receiver_address" class="input-group-text urdu">پتہ</label>
                <input type="text" id="receiver_address" name="receiver_address"
                       class="form-control urdu" readonly tabindex="-1">
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
    <?php } ?>
    <!--exporter-->
    <div class="col-lg-2 position-relative">
        <div class="input-group">
            <label for="exporter_id" class="input-group-text urdu">ایکسپورٹرنام</label>
            <select id="exporter_id" name="exporter_id" required class="form-control border-bottom-0 virtual-select">
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
            <select id="exp_ca_id" name="exp_ca_id" required class="form-control border-bottom-0 virtual-select">
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
    <div class="col-lg-2 position-relative">
        <div class="input-group">
            <label for="importer_id" class="input-group-text urdu">امپورٹرنام</label>
            <select id="importer_id" name="importer_id" required
                    class="form-control border-bottom-0 virtual-select">
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
                <option value="0" selected disabled>انتخاب کریں</option>
                <?php $cas = fetch('clearing_agents');
                while ($ca = mysqli_fetch_assoc($cas)) {
                    $imp_ca_selected = ($ca['id'] == $record['imp_ca_id']) ? 'selected' : '';
                    echo '<option ' . $imp_ca_selected . ' value="' . $ca['id'] . '">' . $ca['ca_name'] . '</option>';
                }
                ?>
            </select>
        </div>
        <small id="responseImpCa" class="text-danger urdu position-absolute"
               style="top: -5px; left: 10px;"></small>
    </div>
    <div class="col-lg-5">
        <div class="input-group">
            <label for="imp_ca_license" class="input-group-text urdu">لائسینس نام</label>
            <input type="text" id="imp_ca_license" name="imp_ca_license"
                   class="form-control " required readonly tabindex="-1">
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
</div>