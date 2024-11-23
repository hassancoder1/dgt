<?php include("header.php"); ?>
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['type']) && ($_GET['type'] == 'gatepass-entry')
) {
    $urlArray = array('gatepass-entry' => array('path' => 'imp-gate-pass-entry', 'title' => 'امپورٹ گیٹ پاس انٹری', 'type' => 'گیٹ پاس', 'transfered_from' => 'imp_gate_pass'),);
    $page = $urlArray[$_GET["type"]];
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('imp_truck_loadings', array('id' => $id));
    $record = mysqli_fetch_assoc($records); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n2">
        <div>
            <h4 class="mb-3 mb-md-0 mt-n2"><?php echo $page['title']; ?></h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl($page['path']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="p-2">
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
                        <div class="col-3">
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
                                       class="form-control" disabled value="<?php echo $record['truck_name']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                <input type="text" id="driver_name" name="" disabled
                                       class="form-control urdu-2 " required readonly
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
                                <input type="text" value="<?php echo $loading['name']; ?>"
                                       class="form-control bold urdu-2" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" class="form-control ltr bold" disabled
                                       value="<?php echo $loading['mobile1']; ?>">
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
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">کنسائینی نام</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['consignee_name']; ?>">
                                <label for="sender_city" class="input-group-text urdu">بھیجنے والا شہر</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['sender_city']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <label class="input-group-text urdu">لوڈنگ رپورٹ</label>
                                <input type="text" class="form-control" disabled
                                       value="<?php echo $record['report']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($_GET['maal_id']) && isset($_GET['serial'])) {
                $maal_id = mysqli_real_escape_string($connect, $_GET['maal_id']);
                $serial = mysqli_real_escape_string($connect, $_GET['serial']);
                $maalQ = fetch('imp_truck_maals', array('id' => $maal_id));
                $maalData = mysqli_fetch_assoc($maalQ);
                $json_maal = json_decode($maalData['json_data']);
                //var_dump($json_maal);
                ?>
                <div class="card mt-2 pb-3 d-print-none">
                    <form method="post">
                        <div class="row gx-0 row-cols me-2">
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="sr_no">سیریل</label>
                                    <input type="text" id="sr_no" class="form-control" disabled
                                           value="<?php echo $serial; ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="jins_name">جنس</label>
                                    <input type="text" id="jins_name" class="form-control input-urdu" required disabled
                                           value="<?php echo $json_maal->jins_name; ?>">
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="bardana_name">باردانہ</label>
                                    <input value="<?php echo $json_maal->bardana_name; ?>" type="text" id="bardana_name"
                                           class="input-urdu form-control" required readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="bardana_qty">تعداد</label>
                                    <input value="<?php echo $json_maal->bardana_qty; ?>" type="text" id="bardana_qty"
                                           class="form-control" required readonly>
                                </div>
                            </div>
                            <!--<div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="per_wt">فی وزن</label>
                                    <input value="<?php /*echo $json_maal->per_wt; */ ?>" type="text" id="per_wt" class="form-control" required readonly>
                                </div>
                            </div>-->
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="total_wt">ٹوٹل وزن</label>
                                    <input value="<?php echo $json_maal->total_wt; ?>" type="text" id="total_wt"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="empty_wt">خالی وزن</label>
                                    <input value="<?php echo $json_maal->empty_wt; ?>" type="text" id="empty_wt"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <!--<div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="total_empty_wt">ٹوٹل خالی وزن </label>
                                    <input value="<?php /*echo $json_maal->total_empty_wt; */ ?>" type="text"
                                           id="total_empty_wt" readonly class="form-control">
                                </div>
                            </div>-->
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="saaf_wt">صاف وزن</label>
                                    <input value="<?php echo $json_maal->saaf_wt; ?>" type="text" id="saaf_wt" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <?php if (isset($_GET['gp_id'])) {
                            $gpId = mysqli_real_escape_string($connect, $_GET['gp_id']);
                            $serial = mysqli_real_escape_string($connect, $_GET['serial']);
                            $gpQ = fetch('imp_truck_gp', array('id' => $gpId));
                            $gpData = mysqli_fetch_assoc($gpQ);
                            $jsonGP = json_decode($gpData['json_gp']);
                            $actionGP = 'update';
                            $bardana_name_gp = $jsonGP->bardana_name_gp;
                            $bardana_qty_gp = $jsonGP->bardana_qty_gp;
                            $gp_giver = $jsonGP->gp_giver;
                            $gp_giver_phone = $jsonGP->gp_giver_phone;
                            $gp_date = $jsonGP->gp_date;
                        } else {
                            $gpId = $bardana_name_gp = $bardana_qty_gp = $gp_giver = $gp_giver_phone = "";
                            $gp_date = date('Y-m-d');
                            $actionGP = 'insert';
                        } ?>
                        <div class="row gx-0 row-cols mt-2 ">
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="bardana_name_gp">باردانہ نام</label>
                                    <input type="text" id="bardana_name_gp" name="bardana_name_gp"
                                           class="form-control input-urdu" required autofocus
                                           value="<?php echo $bardana_name_gp; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="bardana_qty_gp">باردانہ تعداد</label>
                                    <input type="text" id="bardana_qty_gp" name="bardana_qty_gp"
                                           class="form-control currency" required
                                           value="<?php echo $bardana_qty_gp; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group" id="flatpickr-date">
                                    <label class="input-group-text urdu" for="gp_date">گیٹ پاس دینے تاریخ </label>
                                    <input type="text" id="gp_date" name="gp_date"
                                           class="form-control" required data-input value="<?php echo $gp_date; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="gp_giver">گیٹ پاس دینے والا نام</label>
                                    <input type="text" id="gp_giver" name="gp_giver" class="form-control input-urdu"
                                           required value="<?php echo $gp_giver; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <label class="input-group-text urdu" for="gp_giver_phone">فون</label>
                                    <input type="text" id="gp_giver_phone" name="gp_giver_phone" class="form-control"
                                           required value="<?php echo $gp_giver_phone; ?>">
                                </div>
                            </div>
                            <input type="hidden" name="action" value="<?php echo $actionGP; ?>">
                            <input type="hidden" name="hidden_id" value="<?php echo $gpId; ?>">
                            <input type="hidden" name="imp_tl_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="maal_id" value="<?php echo $maal_id; ?>">
                            <div class="col text-end">
                                <?php //if (empty($record[$page['khaata_']])) { ?>
                                <?php //} ?>
                                <button type="submit" name="gatePassSubmit" id="recordSubmit"
                                        class="btn btn-primary pt-0 mt-1 w-100">محفوظ
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php } ?>
            <div class="row gx-2">
                <div class="col-md-12">
                    <?php if (isset($_SESSION['response'])) {
                        echo $_SESSION['response'];
                        unset($_SESSION['response']);
                    } ?>
                    <div class="card mt-2 border-top-0">
                        <table class="table table-bordered table-sm-">
                            <thead>
                            <tr class="bg-info-">
                                <th> سیریل</th>
                                <th>جنس نام</th>
                                <th>باردن نام</th>
                                <th>باردن تعداد</th>
                                <th>فی وزن</th>
                                <th>ٹوٹل وزن</th>
                                <!--<th>خالی باردن وزن</th>-->
                                <th>ٹوٹل خالی وزن</th>
                                <th>صاف وزن</th>

                                <th> سیریل</th>
                                <td>باردانہ نام</td>
                                <td>تعداد</td>
                                <td>گیٹ پاس دینے تاریخ</td>
                                <td>گیٹ پاس دینے والا نام</td>
                                <td>فون</td>
                                <!--<th class="p-0 border-0">
                                    <table class="table table-bordered small mb-0">
                                        <tr>
                                            <td>باردانہ نام</td>
                                            <td>تعداد</td>
                                            <td> تاریخ</td>
                                            <td>گیٹ پاس دینے والا نام</td>
                                        </tr>
                                    </table>
                                </th>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sql = "SELECT imp_truck_maals.imp_tl_id, imp_truck_maals.json_data, imp_truck_gp.id as gp_id, imp_truck_maals.id as maal_id, imp_truck_gp.json_gp
                            FROM imp_truck_maals
                            LEFT JOIN  imp_truck_gp ON imp_truck_maals.id = imp_truck_gp.maal_id
                            WHERE imp_truck_maals.imp_tl_id = $id
                            ORDER BY imp_truck_maals.id";
                            $data = mysqli_query($connect, $sql);
                            //$data = fetch('imp_truck_maals', array('imp_tl_id' => $id));
                            $x = 1;
                            $temp_maal_id = 0;
                            while ($maal = mysqli_fetch_assoc($data)) {
                                $json = json_decode($maal['json_data']);
                                $maalId = $maal['maal_id'];
                                ?>
                                <tr class="row-py-0">
                                    <?php if ($temp_maal_id == $maalId) {
                                        echo '<td colspan="8"></td>';
                                    } else {
                                        echo '<td><a href="imp-gate-pass-entry-add?id=' . $id . '&type=gatepass-entry&maal_id=' . $maalId . '&serial=' . $x . '">' . $x . '</a></td>'; ?>
                                        <td><?php echo $json->jins_name; ?></td>
                                        <td><?php echo $json->bardana_name; ?></td>
                                        <td><?php echo $json->bardana_qty; ?></td>
                                        <td><?php echo $json->per_wt; ?></td>
                                        <td><?php echo round($json->total_wt,2); ?></td>
                                        <td><?php echo $json->total_empty_wt; ?></td>
                                        <td><?php echo round($json->saaf_wt,2); ?></td>
                                    <?php } ?>
                                    <?php $gpId = $maal['gp_id'];
                                    $json_gp = json_decode($maal['json_gp']);
                                    if (empty($json_gp)) {

                                    } else {
                                        echo '<td>' . $gpId . '</td>';
                                        echo '<td><a href="imp-gate-pass-entry-add?id=' . $id . '&type=gatepass-entry&maal_id=' . $maalId . '&serial=' . $maalId . '&gp_id=' . $gpId . '">' . $json_gp->bardana_name_gp . '</a>';
                                        echo '<a href="print/imp-gp-single?imp_tl_id=' . $id . '&maal_id=' . $maalId . '&gp_id=' . $gpId . '&secret=' . base64_encode('powered-by-upsol') . '" target="_blank" class="float-end btn btn-primary py-0"><i class="fa fa-print pt-0"></i></a>';
                                        echo '</td>';
                                        echo '<td>' . $json_gp->bardana_qty_gp . '</td>';
                                        echo '<td>' . $json_gp->gp_date . '</td>';
                                        echo '<td>' . $json_gp->gp_giver . '</td>';
                                        echo '<td>' . $json_gp->gp_giver_phone . '</td>';
                                    } ?>
                                </tr>
                                <?php
                                $temp_maal_id = $maalId;
                                $x++;
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    echo '<script>window.location.href="./";</script>';
} ?>
<?php include("footer.php"); ?>
<?php if (isset($_POST['gatePassSubmit'])) {
    unset($_POST['gatePassSubmit']);
    $msg = 'DB Error';
    $msgType = 'danger';
    $action = mysqli_real_escape_string($connect, $_POST['action']);
    $imp_tl_id = mysqli_real_escape_string($connect, $_POST['imp_tl_id']);
    $maal_id = mysqli_real_escape_string($connect, $_POST['maal_id']);
    $url = 'imp-gate-pass-entry-add?id=' . $imp_tl_id . '&type=gatepass-entry';
    $data = array(
        'imp_tl_id' => $imp_tl_id,
        'maal_id' => $maal_id,
        'json_gp' => json_encode($_POST, JSON_UNESCAPED_UNICODE),
    );
    if ($action == "insert") {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $done = insert('imp_truck_gp', $data);
        if ($done) {
            $msg = 'گیٹ پاس کی انٹری ہو گئی ہے۔';
            $msgType = 'success';
        }
    }
    if ($action == "update") {
        $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('imp_truck_gp', $data, array('id' => $hidden_id));
        if ($done) {
            $msg = 'گیٹ پاس کی انٹری تبدیل ہو گئی ہے۔';
            $msgType = 'success';
        }
    }
    message($msgType, $url, $msg);
} ?>