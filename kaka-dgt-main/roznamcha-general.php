<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $date_msg =$removeFilter = $username_append = $selectedBranch = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$branch_append = "AND branch_id = " . "'$branchId'" . " ";
if ($_POST) {
    $removeFilter = removeFilter('roznamcha-general');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $username_append = "";
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $date_append = " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $date_append = "";
        //$branch_append = "";
        $searchUserName = $_POST['username'];
        $username_append = " AND username = " . "'$searchUserName'" . " ";
    }

} else {
    $date_append = " AND r_date = '$start_date'";
    $username_append = "";
}
$sql = "SELECT * FROM roznamchaas WHERE r_id > 0 {$date_append} {$username_append} {$branch_append}";
//echo $sql;
$sqlStats = "SELECT * FROM roznamchaas WHERE r_id > 0 {$date_append} {$username_append} {$branch_append}";
$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sqlStats);
$bnaamTotal = $jmaaTotal = $mezan = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal; ?>
<div class="filter-div">
    <?php echo $date_msg; ?>
    <?php echo '<span class="badge bg-dark pt-2">' . $searchUserName . '</span>'; ?>
    <?php echo $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n3">جنرل روزنامچہ</h4>
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
                <label for="username"
                       class="input-group-text input-group-addon bg-transparent urdu">یوزر</label>
                <input type="text" id="username" name="username"
                       class="form-control bg-transparent border-primary inputFilter"
                       placeholder="یوزر" value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <div class="input-group wd-180 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل جمع</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل جمع"
                   value="<?php echo $jmaaTotal; ?>" readonly>
        </div>
        <div class="input-group wd-180 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <div class="input-group wd-180 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="میزان"
                   value="<?php echo $mezan; ?>" readonly>
        </div>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered table-sm" id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap">
                        <th width="">روزنامچہ نام</th>
                        <th width="">سیریل</th>
                        <th width="">یوزر</th>
                        <th width="">کھاتہ نمبر</th>
                        <th width="">روزنامچہ نمبر</th>
                        <th width="">نام</th>
                        <th >نمبر</th>
                        <th width="35%">تفصیل</th>
                        <th width="">جمع</th>
                        <th width="">بنام</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (mysqli_num_rows($records) > 0) {
                        while ($roz = mysqli_fetch_assoc($records)) { ?>
                            <tr>
                                <td class="text-nowrap">
                                    <?php echo roznamchaName($roz['r_type']); ?>
                                    <?php echo roznamchaName($roz['transfered_from']); ?>
                                </td>
                                <td>
                                    <?php $editUrl = "";
                                    if ($roz['r_type'] == "karobar") {
                                        $editUrl = "roznamcha-karobar-add?id=" . $roz["r_id"];
                                    }
                                    if ($roz['r_type'] == "bank") {
                                        $editUrl = "roznamcha-bank-add?id=" . $roz["r_id"];
                                    }
                                    if ($roz['r_type'] == "bill") {
                                        $editUrl = "roznamcha-bill-add?id=" . $roz["r_id"];
                                    } ?>
                                    <a href="<?php echo $editUrl; ?>"><?php echo $roz['branch_serial']; ?></a>
                                </td>
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
                                <td class="small"><?php echo $str;
                                    echo $roz['details']; ?></td>
                                <td><?php echo $roz['jmaa_amount']; ?></td>
                                <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr class="text-center"><th colspan="10"> کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                    } ?>
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
    $(function () {
        $('#r_date_start, #r_date_end').change(function () {
            document.datesSubmit.submit();
        });
        $('#branch_id').change(function () {
            document.userNameSubmit.submit();
        });
    });
</script>