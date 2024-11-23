<?php include("header.php"); ?>
<?php $searchUserName = $username_msg = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=1 AND is_saved=1 ";
if ($_POST) {
    $removeFilter = removeFilter('imp-beopari-summary');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date' ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $searchUserName = $_POST['username'];
        $sql .= " AND truck_no = " . "'$searchUserName'" . " ";
        $username_msg = '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>';
    }
}
$sql.= " ORDER BY id DESC";
//$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=1 AND is_saved=1 {$username_append} {$date_append}";
$records = mysqli_query($connect, $sql);
$noOfRows = mysqli_num_rows($records); ?>
<div class="filter-div">
    <?php echo $date_msg . $username_msg . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n2"> پیکنگ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-100 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-80 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
            <div class="input-group wd-200 mb-2 mb-md-0 me-1">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">ٹرک
                    نمبر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="ٹرک نمبر" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
    </div>
</div>
<div class="row mt-3 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="">
                    <div class="table-responsive scroll screen-ht" style="">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th>لوڈنگ #</th>
                            <th>لوڈنگ تاریخ</th>
                            <th>ٹرک نمبر</th>
                            <th>ڈرائیورنام</th>
                            <th class="small">سیریل تعداد</th>
                            <th>بھیجنےوالا</th>
                            <th>وصول کرنےوالا</th>
                            <th> شہر</th>
                            <th>لوڈکرانےگودام</th>
                            <th>خالی کرانےگودام</th>
                            <th>ٹوٹل باردن</th>
                            <th>ٹوٹل وزن</th>
                            <th>ٹوٹل خرچہ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = 1;
                        while ($loading = mysqli_fetch_assoc($records)) {
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
                            if (empty($loading['transfer_to_forms'])) {
                                continue;
                            } else {
                                $json = json_decode($loading['transfer_to_forms']);
                                $json = implode(',', $json);
                                $json = explode(',', $json);
                                if (!in_array("kilo", $json))
                                    continue;
                            } ?>
                            <tr>
                                <td><?php echo $loading["id"]; ?>
                                    <?php if (!empty($loading['khaata_bs'])) {
                                        echo '<a href="imp-summary-transfer?id=' . $loading['id'] . '&type=beopari-summary" class="btn btn-primary pt-0 float-end pb-1 px-1 btn-sm">اوپن</a>';
                                    } else { ?>
                                        <a href="imp-summary-transfer?id=<?php echo $loading['id']; ?>&type=beopari-summary"
                                           class="btn btn-dark pt-0 float-end pb-1 px-1 btn-sm">انٹری</a>
                                    <?php } ?>
                                </td>
                                <td><?php echo $loading['loading_date']; ?></td>
                                <td class="small"><?php echo strtoupper($loading['truck_no']); ?></td>
                                <td class="small-2">
                                    <?php echo $loading['driver_name']; ?>
                                    <br><span dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                                <td>
                                    <?php $tadadQuery = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                                    echo mysqli_num_rows($tadadQuery); ?>
                                </td>
                                <td class="small-2">
                                    <?php echo $names['sender_name']; ?>
                                    <br><span dir="ltr"><?php echo $names['sender_mobile']; ?></span></td>
                                <td class="small-2">
                                    <?php echo $names['receiver_name']; ?>
                                    <br><span dir="ltr"><?php echo $names['receiver_mobile']; ?></span></td>
                                <td class="small"><?php echo $loading['sender_city']; ?></td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                                </td>
                                <td class="small-2">
                                    <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                    <br><span dir="ltr">
                                        <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                                </td>
                                <?php $bardana_qty = $total_wt = $total_exp = 0;
                                $ddd = fetch('imp_truck_maals2', array('imp_tl_id' => $loading['id'], 'is_ttr' => 0, 'form_name' => 'beopari_summary'));
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
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            var username = $("#username").val();
            if (username == '' || username.length < 3) {
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
