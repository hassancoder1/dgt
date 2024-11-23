<?php include("header.php");
$searchBnaamKhaataNo = $username_msg = $date_msg = $removeFilter = "";
$isKhaataPosted = false;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=1 AND is_saved=1 ";
if ($_POST) {
    $removeFilter = removeFilter('imp-kiraya-summary');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date' ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['bnaam_khaata_no']) && !empty($_POST['bnaam_khaata_no'])) {
        $isKhaataPosted = true;
        $searchBnaamKhaataNo = $_POST['bnaam_khaata_no'];
        //$sql .= " AND truck_no = " . "'$searchBnaamKhaataNo'" . " ";
        $username_msg = '<span class="badge bg-primary ms-1"><span class="urdu me-2">اکاؤنٹ</span> ' . $searchBnaamKhaataNo . '</span>';
    }
    echo '<div class="filter-div">' . $date_msg . $username_msg . $removeFilter . '</div>';
}
$sql .= " ORDER BY id DESC";
$records = mysqli_query($connect, $sql); ?>

<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n2">امپورٹ کرایہ سمری مکمل</h4>
    </div>
    <form name="datesSubmit" method="POST" class="d-flex">
        <div class="urdu d-flex align-items-center wd-md-80">
            <label class="input-group-text">تعداد</label>
            <input class="form-control" id="count_rows_span" readonly>
        </div>
        <div class="input-group flatpickr wd-130 mb-2 mb-md-0" id="flatpickr-date">
            <label for="r_date_start" class="input-group-text urdu">تاریخ</label>
            <input id="r_date_start" name="r_date_start" value="<?php echo $start_date; ?>" type="text"
                   class="form-control" data-input>
            <label for="r_date_end" class="input-group-text urdu">سے</label>
        </div>
        <div class="flatpickr wd-80 mb-2 mb-md-0" id="flatpickr-date">
            <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="text" class="form-control"
                   data-input>
        </div>
        <div class="input-group wd-200 mb-2 mb-md-0 me-1">
            <label for="bnaam_khaata_no" class="input-group-text input-group-addon bg-transparent urdu">بنام اکاؤنٹ
                نمبر</label>
            <input type="text" id="bnaam_khaata_no" name="bnaam_khaata_no"
                   class="form-control bg-transparent border-primary"
                   placeholder="بنام اکاؤنٹ نمبر" autofocus value="<?php echo $searchBnaamKhaataNo; ?>" required>
        </div>
    </form>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="gt_bardana_input" class="input-group-text input-group-addon bg-transparent urdu">ٹوٹل
                باردانہ تعداد</label>
            <input type="text" id="gt_bardana_input" class="form-control bg-transparent border-primary" readonly>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="gt_wt_input" class="input-group-text input-group-addon bg-transparent urdu">ٹوٹل
                وزن</label>
            <input type="text" id="gt_wt_input" class="form-control bg-transparent border-primary" readonly>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="gt_exp_input" class="input-group-text input-group-addon bg-transparent urdu">ٹوٹل
                خرچہ</label>
            <input type="text" id="gt_exp_input" class="form-control bg-transparent border-primary" readonly>
        </div>
    </div>
    <!--<div>
        <a href="imp-kiraya-summary" class="btn btn-primary btn-icon-text pt-0 me-1"> تمام ریکارڈ</a>
    </div>-->
</div>
<div class="row mt-3 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered table-sm table-hover" id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap ">
                        <th>لوڈنگ #</th>
                        <th class="small">لوڈنگ تاریخ</th>
                        <th>ٹرک نمبر</th>
                        <th>ڈرائیورنام</th>
                        <th class="small-3">سیریل <br> تعداد</th>
                        <th class="small">بھیجنےوالا</th>
                        <th class="small">وصول کرنےوالا</th>
                        <th class="small"> شہر</th>
                        <th class="small">لوڈکرانےگودام</th>
                        <th class="small">خالی کرانےگودام</th>
                        <th class="small">بنام اکاؤنٹ</th>
                        <th class="small">ٹوٹل باردن</th>
                        <th class="small">ٹوٹل وزن</th>
                        <th class="small">ٹوٹل خرچہ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1;
                    $count_rows = $gt_bardana = $gt_wt = $gt_exp = 0;
                    while ($loading = mysqli_fetch_assoc($records)) {
                        if (!empty($loading['sender_receiver'])) {
                            $sender_receiver = json_decode($loading['sender_receiver']);
                            $names = array(
                                'sender_name' => $sender_receiver->sender_name,
                                'sender_address' => $sender_receiver->sender_address,
                                'sender_mobile' => $sender_receiver->sender_mobile,
                                'sender_wa' => $sender_receiver->sender_wa,
                                'receiver_name' => $sender_receiver->receiver_name,
                                'receiver_address' => $sender_receiver->receiver_address,
                                'receiver_mobile' => $sender_receiver->receiver_mobile,
                                'receiver_wa' => $sender_receiver->receiver_wa
                            );
                        } else {
                            $names = array(
                                'sender_name' => '',
                                'sender_address' => '',
                                'sender_mobile' => '',
                                'sender_wa' => '',
                                'receiver_name' => '',
                                'receiver_address' => '',
                                'receiver_mobile' => '',
                                'receiver_wa' => ''
                            );
                        }
                        if (empty($loading['transfer_to_forms'])) {
                            continue;
                        } else {
                            $json = json_decode($loading['transfer_to_forms']);
                            $json = implode(',', $json);
                            $json = explode(',', $json);
                            if (!in_array("kiraya", $json)) {
                                continue;
                            }
                        }
                        $maal1 = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                        $count_maal1 = mysqli_num_rows($maal1);
                        $btn_Array = array('class' => 'btn-dark', 'text' => 'اوپن');
                        $rowColor = '';
                        if (empty($loading['khaata_ks'])) {
                            continue;
                            /*$isMaal2Added = fetch('imp_truck_maals2', array('imp_tl_id' => $loading["id"], 'form_name' => 'kiraya_summary'));
                            $count_maal2 = 0;
                            while ($dd = mysqli_fetch_assoc($isMaal2Added)) {
                                if ($dd['maal_id'] > 0) {
                                    $count_maal2++;
                                }
                            }
                            if ($count_maal2 >= $count_maal1) {
                                $rowColor = 'bg-warning bg-opacity-10';
                                $btn_Array = array('class' => 'btn-warning', 'text' => 'انٹری');
                            } else {
                                $rowColor = 'bg-danger bg-opacity-10';
                                $btn_Array = array('class' => 'btn-danger', 'text' => 'انٹری');
                            }*/
                        }
                        if (empty($loading['khaata_ks'])) {
                            $details_bnaam = '';
                            if ($isKhaataPosted) continue;
                        } else {
                            $khaata_bs = json_decode($loading['khaata_ks']);
                            $bnaam_khaata = khaataSingle($khaata_bs->bnaam_khaata_id);
                            //var_dump($bnaam_khaata);
                            if ($isKhaataPosted) {
                                if ($searchBnaamKhaataNo != $bnaam_khaata['khaata_no']) {
                                    continue;
                                }
                            }
                            $details_bnaam = $bnaam_khaata['khaata_no'] . '<br>' . $bnaam_khaata['khaata_name'];
                        } ?>
                        <tr class="<?php echo $rowColor; ?> text-nowrap">
                            <td>
                                <?php echo $loading["id"]; ?>
                                <a href="imp-summary-transfer?id=<?php echo $loading['id']; ?>&type=kiraya-summary" target="_blank"
                                   class="btn <?php echo $btn_Array['class']; ?> pt-0 pb-1 px-1 btn-sm small-3"><?php echo $btn_Array['text']; ?></a>
                            </td>
                            <td class="small-2"><?php echo $loading['loading_date']; ?></td>
                            <td class="small-2"><?php echo $loading['truck_no']; ?></td>
                            <td class="small-2"><?php echo $loading['driver_name']; ?>
                                <br><span dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                            <td class="small-2">
                                <?php $tadadQuery = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                                echo mysqli_num_rows($tadadQuery); ?>
                            </td>
                            <td class="small-2"><?php echo $names['sender_name']; ?>
                                <br><span dir="ltr"><?php echo $names['sender_mobile']; ?></span></td>
                            <td class="small-2"><?php echo $names['receiver_name']; ?>
                                <br><span dir="ltr"><?php echo $names['receiver_mobile']; ?></span></td>
                            <td class="small"><?php echo $loading['sender_city']; ?></td>
                            <td class="small-3"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                <br><span
                                    dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-3"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                <br><span dir="ltr">
                                        <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-2"><?php echo $details_bnaam; ?></td>

                            <?php $bardana_qty = $total_wt = $total_exp = 0;
                            $ddd = fetch('imp_truck_maals2', array('imp_tl_id' => $loading['id'], 'is_ttr' => 0, 'form_name' => 'kiraya_summary'));
                            if (mysqli_num_rows($ddd) > 0) {
                                while ($jjj = mysqli_fetch_assoc($ddd)) {
                                    $json = json_decode($jjj['json_data']);
                                    $total_exp += $json->total_exp;
                                    $bardana_qty += $json->bardana_qty;
                                    $total_wt += $json->total_wt;
                                }
                            } ?>
                            <td><?php echo $bardana_qty; ?></td>
                            <td><?php echo $total_wt; ?></td>
                            <td><?php echo $total_exp; ?></td>
                        </tr>
                        <?php $no++;
                        $gt_bardana += $bardana_qty;
                        $gt_wt += $total_wt;
                        $gt_exp += $total_exp;
                        $count_rows++;
                    } ?>
                    <input type="hidden" id="gt_bardana" value="<?php echo $gt_bardana; ?>">
                    <input type="hidden" id="gt_wt" value="<?php echo $gt_wt; ?>">
                    <input type="hidden" id="gt_exp" value="<?php echo $gt_exp; ?>">
                    </tbody>
                </table>
                <input type="hidden" id="count_rows" value="<?php echo $count_rows; ?>">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#gt_bardana_input").val($("#gt_bardana").val());
    $("#gt_wt_input").val($("#gt_wt").val());
    $("#gt_exp_input").val($("#gt_exp").val());

    $("#count_rows_span").val($("#count_rows").val());
</script>
<script>
    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            let bnaam_khaata_no = $("#bnaam_khaata_no").val();
            if (bnaam_khaata_no == '' || bnaam_khaata_no.length < 1) {
                evt.preventDefault();
                return false;
            }
            document.datesSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $('#r_date_start, #r_date_end').change(function () {
        document.datesSubmit.submit();
    });
</script>
