<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_msg = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
if (Administrator()) {
    $sql = "SELECT * FROM dt_truck_loadings WHERE is_transfered =1 ";
} else {
    $sql = "SELECT * FROM dt_truck_loadings WHERE is_docs =0 AND is_transfered =1 ";
}

if ($_POST) {
    $removeFilter = removeFilter('dt-docs-entry');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $searchUserName = $_POST['username'];
        $username_msg = '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>';
        $sql .= " AND truck_no = " . "'$searchUserName'" . " ";
    }
    echo '<div class="filter-div">' . $date_msg . $username_msg . $removeFilter . '</div>';
}
$sql .= " ORDER BY id DESC";
$records = mysqli_query($connect, $sql); ?>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mt-n1 mb-3 mb-md-0">ڈاؤن ٹرانزٹ ڈاکومنٹس انٹری</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-110 mb-2 mb-md-0" id="flatpickr-date">
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
        </form>
        <form name="userNameSubmit" method="POST" class="d-flex">
            <div class="input-group wd-150 ms-3 mb-2 mb-md-0">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">ٹرک
                    نمبر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="ٹرک نمبر" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered table-sm table-hover" id="fix-head-table">
                    <thead>
                    <tr>
                        <th>لوڈنگ #</th>
                        <th>آئی ڈی</th>
                        <th>لوڈنگ تاریخ</th>
                        <th>جنس</th>
                        <th>مالک نام</th>
                        <th>ٹرک نمبر</th>
                        <th>ڈرائیور</th>
                        <th class="small-2">بھیجنے والا شہر</th>
                        <th class="small-2">لوڈکرانےگودام</th>
                        <th class="small-2">خالی کرانےگودام</th>
                        <th>رپورٹ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($loading = mysqli_fetch_assoc($records)) {
                        $rowColor = '';
                        $btn = '<a href="dt-docs-entry-add?id=' . $loading["id"] . '" class="btn btn-dark mt-1 pt-0 pb-1 px-1 btn-sm small-3">انٹری</a>';
                        if ($loading['is_docs'] == 0) {
                            $rowColor = 'bg-danger bg-opacity-10';

                            if ($loading['importer_id'] > 0) {
                                $rowColor = 'bg-warning bg-opacity-10';
                                //$btn_Array = array('class' => 'btn-warning', 'text' => 'ٹرانسفر');
                                $btn .= '<a onclick="transferImpTruckLoading(this)" id="' . $loading["id"] . '" data-jins="' . $loading['jins'] . '" data-url="dt-docs-entry" class="btn btn-primary ms-2 pt-0 pb-1 mt-1 px-1 btn-sm small-3">ٹرانسفر</a>';
                            }
                        }
                        ?>
                        <tr class="<?php echo $rowColor; ?> text-nowrap">
                            <td class="d-flex align-items-center justify-content-between">
                                <?php echo $loading["id"];
                                echo $btn; ?>
                            </td>
                            <td><?php echo $loading['username']; ?></td>
                            <td><?php echo $loading['loading_date']; ?></td>
                            <td><?php echo $loading['jins']; ?></td>
                            <td><?php echo $loading['owner_name']; ?></td>
                            <td class="small"><?php echo $loading['truck_no']; ?></td>
                            <td class="small-2"><?php echo $loading['driver_name']; ?>
                                <br><span dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                            <td class="small-2"><?php echo $loading['sender_city']; ?></td>
                            <td class="small-2"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                <br><span
                                        dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-2"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                <br><span dir="ltr">
                                        <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-3"><?php echo $loading['report']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
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
    function transferImpTruckLoading(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n سیریل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferDTDocsEntry.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>