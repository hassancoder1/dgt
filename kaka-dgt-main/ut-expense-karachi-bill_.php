<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="ut-expense-karachi-bill"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
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
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 {$username_append} {$date_append}";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg;
    echo '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>';
    echo $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div><h4 class="mb-3 mb-md-0"> کراچی خرچہ بل</h4></div>
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
                            <th class="small-2">بیل #</th>
                            <th class="small-2">آئی ڈی</th>
                            <th>لوڈنگ تاریخ</th>
                            <th>جنس</th>
                            <th>لوڈشہر</th>
                            <th>ٹوٹل وزن</th>
                            <th>صاف وزن</th>
                            <th>بھیجنے والا</th>
                            <th>ایکسپورٹر</th>
                            <th>امپورٹر</th>
                            <th>رپورٹ</th>
                            <th>ٹوٹل بل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($loading = mysqli_fetch_assoc($records)) { ?>
                            <tr>
                                <td><?php echo $loading["id"]; ?>
                                    <a href="ut-expense-transfer.php?id=<?php echo $loading["id"]; ?>&type=karachi" class="btn btn-primary py-0 px-1 small"
                                       data-tooltip="کراچی خرچہ بل میں خرچہ انٹری کریں"
                                       data-tooltip-position="left">انٹری </a>
                                </td>
                                <td><?php echo $loading['username']; ?></td>
                                <td><?php echo $loading['loading_date']; ?></td>
                                <td><?php echo $loading['jins']; ?></td>
                                <td><?php echo $loading['loading_city']; ?></td>
                                <td><?php echo $loading['total_wt']; ?></td>
                                <td><?php echo $loading['saaf_wt']; ?></td>
                                <td class="small-2"><?php $sender_json = json_decode($loading['sender_json']);
                                    echo $sender_json->sender_name . '<br>';
                                    echo $sender_json->sender_address; ?>
                                </td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'mobile'); ?></span>
                                </td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'mobile'); ?></span>
                                </td>
                                <td class="small-2"><?php echo $loading['report']; ?></td>
                                <td>0</td>
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
<?php if (isset($_POST['transferToFormsSubmit'])) {
    $url = 'ut-expense-karachi-to-border.php';
    $bail_id_hidden = $_POST['bail_id_hidden'];
    $ut_expense_names = json_encode($_POST['ut_expense_names']);
    $dataTransfer = array(
        'ut_expense_names' => $ut_expense_names
    );
    $upp = update('ut_bail_entries', $dataTransfer, array('id' => $bail_id_hidden));
    if ($upp) {
        message('success', $url, 'ٹرانسفر ہو گیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>
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
    function transferUTBailSurrenderEntry_(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ امپورٹ کسٹم کراچی میں ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n بیل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferUTBailSurrenderEntry.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>