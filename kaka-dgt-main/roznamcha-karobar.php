<?php include("header.php");
$pageURL = 'roznamcha-karobar';
global $branchId;
$date_msg = $username_msg = $searchUserName = $removeFilter = $branch_msg = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_type= 'karobar' ";
if ($branchId > 0) {
    $sql .= " AND branch_id = '$branchId' ";
    $branch_msg = branchName($branchId);
}
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (!empty($_GET['username'])) {
        $searchUserName = $_GET['username'];
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        $username_msg = '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>';
    }
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date' ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
} else {
    $sql .= " AND r_date = '$start_date' ";
}
$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$bnaamTotal = $jmaaTotal = $mezan = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal; ?>
<?php echo '<div class="filter-div">' . $username_msg . $date_msg . $removeFilter . '</div>'; ?>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mt-n2 mb-3 mb-md-0">کاروبار روزنامچہ
            <sub> <?php echo $branch_msg; ?> </sub>
        </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="get" class="d-flex">
            <div class="input-group flatpickr wd-110 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>" type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-80 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
            <div class="input-group wd-100 mb-2 mb-md-0">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">یوزر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="یوزر" value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <div class="input-group wd-100 mb-2 mb-md-0">
            <label for="rows_count_span" class="input-group-text input-group-addon bg-transparent urdu">تعداد</label>
            <input id="rows_count_span" readonly class="form-control bg-transparent border-primary">
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <label for="tableFilter" class="input-group-text input-group-addon bg-transparent urdu">تلاش</label>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش کریں (F2)">
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل جمع</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل جمع"
                   value="<?php echo $jmaaTotal; ?>" readonly>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-2">
            <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="میزان"
                   value="<?php echo $mezan; ?>" readonly>
        </div>
        <a href="roznamcha-karobar-add" class="btn btn-outline-primary pb-2 pt-1">اندراج</a>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>سیریل</th>
                            <th>تاریخ</th>
                            <th>یوزر</th>
                            <th>کھاتہ نمبر</th>
                            <th>روزنامچہ نمبر</th>
                            <th>نام</th>
                            <th>نمبر</th>
                            <th width="35%">تفصیل</th>
                            <th>جمع</th>
                            <th>بنام</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rows_count = 0;
                        if (mysqli_num_rows($records) > 0) {
                            while ($roz = mysqli_fetch_assoc($records)) { ?>
                                <tr>
                                    <td>
                                        <?php echo $roz['branch_serial']; ?>
                                        <!--<a href="roznamcha-karobar-add?id=<?php /*echo $roz["r_id"]; */ ?>"></a>-->
                                    </td>
                                    <td class="text-nowrap"><?php echo $roz['r_date']; ?></td>
                                    <td><?php echo getTableDataByIdAndColName('users', $roz['user_id'], 'username'); ?></td>
                                    <td><?php echo $roz['khaata_no']; ?></td>
                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                    <td class="urdu-td small"><?php echo $roz['r_name']; ?></td>
                                    <td><?php echo $roz['r_no']; ?></td>
                                    <?php $str = "";
                                    if ($roz['jmaa_amount'] == 0) {
                                        $str = "بنام:- ";
                                    }
                                    if ($roz['bnaam_amount'] == 0) {
                                        $str = "جمع:- ";
                                    } ?>
                                    <td class="urdu-td small"><?php echo $str;
                                        echo $roz['details']; ?></td>
                                    <td><?php echo $roz['jmaa_amount']; ?></td>
                                    <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                                </tr>
                                <?php $rows_count++;
                            }
                        } else {
                            echo '<tr class="text-center"><th colspan="10">کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="rows_count" value="<?php echo $rows_count; ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_count_span").val($("#rows_count").val());
</script>
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
<script>
    $('#r_date_start, #r_date_end,#username').change(function () {
        document.datesSubmit.submit();
    });
</script>