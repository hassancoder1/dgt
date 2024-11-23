<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="ut-bail-entries"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
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
        $searchUserName = $_POST['username'];
        $username_append = " AND username = " . "'$searchUserName'" . " ";
    }
} else {
    $date_append = $username_append = "";
}
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 0 {$username_append} {$date_append}";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg; ?>
    <?php echo '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>'; ?>
    <?php echo $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">بیل انٹری</h4>
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
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">آئی ڈی نام</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="آئی ڈی نام" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <a href="ut-bail-entry-add"
           class="btn btn-outline-primary btn-icon-text py-1">
            <i class="btn-icon-prepend" data-feather="file-plus"></i>
            اندراج
        </a>
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
                            <th>بیل #</th>
                            <th class="small">آئی ڈی</th>
                            <th class="small">لوڈنگ تاریخ</th>
                            <th>جنس</th>
                            <th>لوڈشہر</th>
                            <th>ٹوٹل وزن</th>
                            <th class="small"> صاف وزن</th>
                            <th>بھیجنے والا</th>
                            <th>ایکسپورٹر</th>
                            <th class="small">ایکسپورٹ ایجنٹ</th>
                            <th>امپورٹر</th>
                            <th class="small">امپورٹ ایجنٹ</th>
                            <th width="20%">رپورٹ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($loading = mysqli_fetch_assoc($records)) { ?>
                            <tr class="text-nowrap">
                                <td>
                                    <a data-tooltip="بیل انٹری کی تفصیل"
                                       data-tooltip-position="left"
                                       href="ut-bail-entry-add?id=<?php echo $loading["id"]; ?>"><?php echo $loading["id"]; ?></a>
                                    <a href="#" class="btn btn-primary py-0 px-1 small"
                                       data-tooltip="بیل انٹری کو سلنڈر بیل میں ٹرانسفر کریں"
                                       data-tooltip-position="left"
                                       onclick="transferUTBailEntry(this)"
                                       id="<?php echo $loading['id']; ?>"
                                       data-url="ut-bail-entries"
                                       data-jins="<?php echo $loading['jins']; ?>">ٹرانسفر </a>
                                </td>
                                <td><?php echo $loading['username']; ?></td>
                                <td><?php echo $loading['loading_date']; ?></td>
                                <td><?php echo $loading['jins']; ?></td>
                                <td><?php echo $loading['loading_city']; ?></td>
                                <td><?php echo $loading['total_wt']; ?></td>
                                <td><?php echo $loading['saaf_wt']; ?></td>
                                <td><?php $sender_json = json_decode($loading['sender_receiver']);
                                    echo getTableDataByIdAndColName('senders',$sender_json->sender_id,'comp_owner_name') . '<br>';
                                    echo '<span class="small-2 text-nowrap" dir="ltr">' . $sender_json->sender_mobile . '</span>'; ?>
                                </td>
                                <td>
                                    <?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"
                                            class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'mobile'); ?></span>
                                </td>
                                <td>
                                    <?php echo getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_name'); ?>
                                    <br><span dir="ltr"
                                              class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_mobile'); ?></span>
                                </td>
                                <td>
                                    <?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'name'); ?>
                                    <br><span class="small-2 text-nowrap"
                                              dir="ltr"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'mobile'); ?></span>
                                </td>
                                <td>
                                    <?php echo getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_name'); ?>
                                    <br><span class="small-2"
                                              dir="ltr"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_mobile'); ?></span>
                                </td>
                                <td><?php echo $loading['report']; ?></td>
                            </tr>
                        <?php } ?>
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
<script>
    function transferUTBailEntry(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ سلنڈر بیل میں ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n سیریل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferUTBailEntry.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>