<?php include("header.php"); ?>
<?php $searchUserName = $username_msg = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
//$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=0 AND is_ut_transferred=0  ";
if (Administrator()){
    $sql = "SELECT * FROM imp_truck_loadings WHERE id>1 ";
}else{
    $sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=0 AND is_ut_transferred=0 ";
}
if ($_POST) {
    $removeFilter = removeFilter('imp-truck-loading');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date' ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $searchUserName = $_POST['username'];
        $sql .= " AND truck_no = " . "'$searchUserName'" . " ";
        $username_msg = '<span class="badge bg-primary ms-1">' . $searchUserName . '</span>';
    }
}
$sql .= " ORDER BY id DESC";

//$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=0 AND is_ut_transferred=0 {$username_append} {$date_append}";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . $username_msg . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">امپورٹ بل ٹرک لوڈنگ</h4>
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
            <div class="input-group wd-150 ms-3 mb-2 mb-md-0 ">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">ٹرک
                    نمبر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="ٹرک نمبر" value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <div class="input-group wd-150 mb-2 mb-md-0 me-2">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش (F2)">
        </div>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <a href="imp-truck-loading-add"
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
                    <table class="table table-bordered table-sm" id="fix-head-table">
                        <thead>
                        <tr class="small">
                            <th>لوڈنگ #</th>
                            <th class="small">آئی ڈی نام</th>
                            <th>لوڈنگ تاریخ</th>
                            <th>جنس</th>
                            <th>مالک نام</th>
                            <th>ٹرک نمبر</th>
                            <th>ڈرائیورنام</th>
                            <th>بھیجنے والا شہر</th>
                            <th>لوڈکرانےگودام</th>
                            <th>خالی کرانےگودام</th>
                            <th>رپورٹ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($loading = mysqli_fetch_assoc($records)) {
                            $rowColor = '';
                            $is_transfered=$loading['is_transfered'];
                            if ($is_transfered == 0){
                                $rowColor = 'bg-danger bg-opacity-10';
                            } ?>
                            <tr class="<?php echo $rowColor;?>">
                                <td>
                                    <a href="imp-truck-loading-add?id=<?php echo $loading["id"]; ?>"><?php echo $loading["id"]; ?></a>
                                    <?php if ($loading['is_transfered'] == 0 && $loading['godam_empty_id'] > 0 && $loading['truck_no'] != "") { ?>
                                        <a href="#" class="btn btn-primary py-0 px-1 small float-end"
                                           onclick="transferImpTruckLoading(this)"
                                           id="<?php echo $loading['id']; ?>"
                                           data-url="imp-truck-loading"
                                           data-jins="<?php echo $loading['jins']; ?>">ٹرانسفر </a>
                                    <?php } ?>
                                </td>
                                <td><?php echo $loading['username']; ?></td>
                                <td><?php echo $loading['loading_date']; ?></td>
                                <td><?php echo $loading['jins']; ?></td>
                                <td><?php echo $loading['owner_name']; ?></td>
                                <td class="small"><?php echo $loading['truck_no']; ?></td>
                                <td class="small-2"><?php echo $loading['driver_name']; ?>
                                    <br><span dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                                <td class="small"><?php echo $loading['sender_city']; ?></td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                                </td>
                                <td class="small-2">
                                    <?php if ($loading['godam_empty_id'] > 0) {
                                        echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name');
                                    } ?>
                                    <br>
                                    <span dir="ltr">
                                        <?php if ($loading['godam_empty_id'] > 0) {
                                            echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1');
                                        } ?>
                                    </span>
                                </td>
                                <td class="small-2"><?php echo $loading['report']; ?></td>
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
    function transferImpTruckLoading(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n سیریل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferImpTruckLoading.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>