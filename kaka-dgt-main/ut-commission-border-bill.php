<?php include("header.php"); ?>
<?php $searchUserName = $date_msg = $username_msg = $removeFilter = $print_start_date = $print_end_date = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND transfer_from_godam > 0 AND qandhar_user_ids IS NOT NULL ";
if ($_POST) {
    $removeFilter = removeFilter('ut-godam-empty');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $print_start_date = $start_date;
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $print_end_date = $end_date;
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $searchUserName = $_POST['username'];
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        $username_msg = '<span class="badge bg-secondary pt-2 ms-1">' . $searchUserName . '</span>';
    }
}
//$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND qandhar_user_ids != '' {$username_append} {$date_append}";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . $username_msg . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div><h3 class="mb-3 mb-md-0 urdu"> کمیشن بارڈر بل </h3></div>
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
            <div class="input-group wd-150 ms-3 mb-2 mb-md-0 me-2">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">آئی ڈی نام</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="آئی ڈی نام" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <div class="input-group wd-200 me-3">
            <span class="input-group-text input-group-addon bg-transparent urdu">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش کریں (F2)">
        </div>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <!--<a href="" class="btn btn-outline-primary btn-icon-text py-1">
            <i class="btn-icon-prepend" data-feather="file-plus"></i>اندراج
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
                        <tr class="text-nowrap">
                            <th>بیل #</th>
                            <th class="small">لوڈنگ تاریخ</th>
                            <th class="small-2">جنس/شہر</th>
                            <th class="small">وزن</th>
                            <th class="small">بھیجنے والا</th>
                            <th class="small" width="">ایکسپورٹر</th>
                            <th class="small" width="">ایکسپورٹ ایجنٹ</th>
                            <th class="small" width="">امپورٹر</th>
                            <th class="small" width="">امپورٹ ایجنٹ</th>
                            <th class="small">بیل رپورٹ</th>
                            <th class="small-2">گودام پہنچ تاریخ</th>
                            <th>باردانہ</th>
                            <th>وزن</th>
                            <th class="small-2">گودام رپورٹ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($loading = mysqli_fetch_assoc($records)) {
                            if (!empty($loading['khaata_qandhar'])) { ?>
                                <tr class="">
                                    <td>
                                        <?php $t_f_g = $loading['transfer_from_godam'];
                                        switch ($t_f_g) {
                                            case 1:
                                                echo '<a href="ut-expense-transfer?id=' . $loading["id"] . '&type=' . BORDER_BILL . '" class="h5" data-tooltip="بارڈر بل اندراج" data-tooltip-position="bottom left">' . $loading["id"] . '</a>';
                                                break;
                                            case 2:
                                                echo '<span class="small-2">واپسی لوڈنگ گودام</span>';
                                                break;
                                            default:
                                                echo '';
                                                break;
                                        } ?>
                                        <a class="btn btn-primary pt-0 p-1 mt-n1 btn-sm float-end"
                                           data-tooltip="کمیشن بارڈ بل کا پرنٹ"
                                           data-tooltip-position="left"
                                           href="print/ut-expense-bill?bail_id=<?php echo $loading["id"]; ?>&secret=<?php echo base64_encode("powered-by-upsol") ?>&start_date=<?php echo $print_start_date; ?>&end_date=<?php echo $print_end_date; ?>&type=<?php echo BORDER_BILL; ?>&url=<?php echo base64_encode('ut-commission-border-bill') ?>"><i
                                                    class="fa fa-print"></i>
                                        </a>
                                    </td>
                                    <td class="small-2 text-nowrap"><?php echo $loading['loading_date']; ?></td>
                                    <td class="small-2 text-nowrap"><?php echo $loading['jins']; ?>
                                        <hr class="mt-2 mb-0">
                                        <span class=""><?php echo $loading['loading_city']; ?></span>
                                    </td>
                                    <td class="small-2 text-nowrap">
                                        <span class="small-2">ٹوٹل</span><?php echo $loading['total_wt']; ?>
                                        <hr class="mt-2 mb-0">
                                        <span class="small-2">صاف</span><?php echo $loading['saaf_wt']; ?>
                                    </td>
                                    <td class="small-2">
                                        <?php $sender_json = json_decode($loading['sender_receiver']);
                                        echo getTableDataByIdAndColName('senders', $sender_json->sender_id, 'comp_owner_name') . '<br>';
                                        echo '<span class="small-2 text-nowrap" dir="ltr">' . $sender_json->sender_mobile . '</span>'; ?>
                                    </td>
                                    <td class="small-2">
                                        <?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'name'); ?>
                                        <br>
                                        <span dir="ltr"
                                              class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'mobile'); ?></span>
                                    </td>
                                    <td class="small-2 text-wrap">
                                        <?php $expAgent = getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_name');
                                        echo $expAgent; ?><br>
                                        <span dir="ltr"
                                              class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_mobile'); ?></span>
                                    </td>
                                    <td class="small-2">
                                        <?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'name'); ?>
                                        <br><span class="small-2 text-nowrap"
                                                  dir="ltr"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'mobile'); ?></span>
                                    </td>
                                    <td class="small-2 text-nowrap">
                                        <?php $impAgent = getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_name');
                                        echo $impAgent; ?>
                                        <br><span class="small-2"
                                                  dir="ltr"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_mobile'); ?></span>
                                    </td>
                                    <td class="small-2"><?php echo $loading['report']; ?></td>
                                    <?php if (empty($loading['godam_json'])) {
                                        $godamJson = array(
                                            'godam_receiving_date' => '',
                                            'godam_bardana_qty' => '',
                                            'godam_bardana_name' => '',
                                            'godam_balance' => '',
                                            'godam_total_wt' => '',
                                            'godam_saaf_wt' => '',
                                            'godam_report' => ''
                                        );
                                    } else {
                                        $godam_json = json_decode($loading['godam_json']);
                                        $godamJson = array(
                                            'godam_receiving_date' => $godam_json->godam_receiving_date . '<hr class="mt-2 mb-0">',
                                            'godam_bardana_qty' => '<span> باردانہ تعداد : </span>' . $godam_json->godam_bardana_qty . '<hr class="mt-2 mb-0">',
                                            'godam_bardana_name' => '<span> باردانہ نام : </span>' . $godam_json->godam_bardana_name,
                                            'godam_balance' => '<span> بیلنس : </span>' . $godam_json->godam_balance,
                                            'godam_total_wt' => '<span> ٹوٹل : </span>' . $godam_json->godam_total_wt . '<hr class="mt-2 mb-0">',
                                            'godam_saaf_wt' => '<span> صاف : </span>' . $godam_json->godam_saaf_wt,
                                            'godam_report' => $godam_json->godam_report
                                        );
                                    } ?>
                                    <td class="small-2 text-nowrap">
                                        <?php echo $godamJson['godam_receiving_date']; ?>
                                        <?php echo $godamJson['godam_bardana_name']; ?>
                                    </td>
                                    <td class="small-2 text-nowrap">
                                        <?php echo $godamJson['godam_bardana_qty']; ?>
                                        <?php echo $godamJson['godam_balance']; ?>
                                    </td>
                                    <td class="small-2 text-nowrap">
                                        <?php echo $godamJson['godam_total_wt']; ?>
                                        <?php echo $godamJson['godam_saaf_wt']; ?>
                                    </td>

                                    <td class="small-2"><?php echo $godamJson['godam_report']; ?></td>
                                </tr>
                            <?php }
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
<script>
    function transferUTQandharCustom(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ خالی کرنے گودام میں ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n بیل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferUTQandharCustom.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>