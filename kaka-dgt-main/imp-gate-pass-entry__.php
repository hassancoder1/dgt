<?php include("header.php"); ?>
<?php
$searchUserName = "";
$date_append = "";
$username_append = "";
$date_msg = "";
$removeFilter = "";
$start_date = date('Y-m-d');
$end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="imp-kiraya-godam-received.php"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $username_append = "";
        //$branch_append = "";
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $date_append = " AND loading_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $date_append = "";
        //$branch_append = "";
        $searchUserName = $_POST['username'];
        $username_append = " AND truck_no = " . "'$searchUserName'" . " ";
    }
} else {
    $date_append = "";
    $username_append = "";
}
$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=1 AND is_saved=1 {$username_append} {$date_append}";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg; ?>
    <?php echo '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>'; ?>
    <?php echo $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">گیٹ پاس انٹری</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-150 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-120 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
        </form>
        <form name="userNameSubmit" method="POST" class="d-flex">
            <div class="input-group wd-150 ms-3 mb-2 mb-md-0 me-2">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">ٹرک
                    نمبر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="ٹرک نمبر" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <!--<a href="imp-truck-loading-add.php"
           class="btn btn-outline-primary btn-icon-text py-1">
            <i class="btn-icon-prepend" data-feather="file-plus"></i>
            اندراج
        </a>-->
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="">
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th>کھاتہ نمبر</th>
                            <th>کھاتہ نام</th>
                            <th>ٹوٹل سمری تعداد</th>
                            <th>ٹوٹل باردانہ تعداد</th>
                            <th>باردانہ گیٹ پاس شدہ</th>
                            <th>بقایا باردانہ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $no = 1;
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
                                if (in_array("kiraya", $json)) {
                                } else {
                                    continue;
                                }
                            } ?>
                            <tr class="text-nowrap">
                                <td>
                                    <?php echo $loading["id"]; ?>
                                    <a href="imp-comp-beopari-comm-transfer.php?id=<?php echo $loading['id']; ?>&type=beopari-commission"
                                       class="btn btn-dark pt-0 pe-1 ps-2 btn-sm">انٹری</a>
                                </td>
                                <td><?php echo $loading['loading_date']; ?></td>
                                <td class="small"><?php echo $loading['truck_no']; ?></td>
                                <td class=""><?php echo $loading['driver_name']; ?><br><span
                                            dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                                <td><?php $tadadQuery = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                                    echo mysqli_num_rows($tadadQuery); ?></td>
                                <td><?php echo $names['sender_name']; ?><br><span
                                            dir="ltr"><?php echo $names['sender_mobile']; ?></span></td>
                                <td><?php echo $names['receiver_name']; ?><br><span
                                            dir="ltr"><?php echo $names['receiver_mobile']; ?></span></td>
                                <td class="small"><?php echo $loading['sender_city']; ?></td>
                                <td class="small"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                                </td>
                                <td class="small"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                                </td>
                                <?php if ($loading['bcb_transfered'] == 0) {
                                    $total_bill = "";
                                } else {
                                    $total_bill = 0;
                                    $maal2 = isKirayaAdded(0, 'beopari_commission', $loading['id']);
                                    if ($maal2['success']) {
                                        $maal2Id = $maal2['output']['id'];
                                        $json2 = json_decode($maal2['output']['json_data']);
                                        $total_bill = $json2->total_bill;
                                    }
                                }
                                echo '<td>' . $total_bill . '</td>'; ?>
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
            document.userNameSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $('#r_date_start, #r_date_end').change(function () {
        document.datesSubmit.submit();
    });
</script>
