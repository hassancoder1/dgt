<?php include("header.php"); ?>
<?php
$searchUserName = "";
$date_append = "";
$username_append = "";
$branch_append = "";
$selectedBranch = "";
$selectedBranchId = 0;
$date_msg = "";
$removeFilter = "";
$start_date = date('Y-m-d');
$end_date = date('Y-m-d');
if (Administrator() || Manager() || Munshi()) {
    if (Administrator()) {
        $branch_append = " ";
    } else {
        $branch_append = "AND branch_id = " . "'$branchId'" . " ";
    }
} else {
    message('danger', 'index.php', 'صرف ایڈمن یہ پیج استعمال کر سکتے ہیں۔');
}
if ($_POST) {
    $removeFilter = '<a href="roznamcha-bank-cheque.php"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
    /*if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $username_append = "";
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $date_append = " AND r_date_payment BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }*/
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $date_append = "";
        $searchUserName = $_POST['username'];
        $username_append = " AND username = " . "'$searchUserName'" . " ";
    }
} else {
    $date_append = " AND r_date = '$start_date'";
    $username_append = "";
    $branch_append = "";
}
$sql = "SELECT * FROM roznamchaas WHERE r_type= 'bank' {$date_append} {$username_append} {$branch_append}";
//echo $sql;
$sqlStats = "SELECT * FROM roznamchaas WHERE r_type= 'bank' {$date_append} {$username_append} {$branch_append}";
$recordStats = mysqli_query($connect, $sqlStats);
$bnaamTotal = 0;
$jmaaTotal = 0;
$mezan = 0;
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
        <h4 class="mb-3 mb-md-0">چیک روزنامچہ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <!--<form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-110 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php /*echo $start_date; */?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-80 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php /*echo $end_date; */?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
        </form>-->
        <form name="userNameSubmit" method="POST" class="d-flex">
            <div class="input-group wd-120 mb-2 mb-md-0">
                <label for="today" class="input-group-text input-group-addon bg-transparent urdu">تاریخ</label>
                <input type="text" id="today" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ" value="<?php echo date('Y-m-d'); ?>" disabled>
            </div>
            <div class="input-group wd-150 ms-3 mb-2 mb-md-0">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">یوزر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="یوزر" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <div class="input-group wd-200 mb-2 mb-md-0 me-2">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();">
            <i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <a href="roznamcha-bank.php"
           class="btn btn-outline-dark btn-icon-text py-1"> واپس بینک روزنامچہ <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i></a>
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
                        <tr>
                            <th width="8%" class="">تاریخ اندراج</th>
                            <th width="7%" class="small-2"><span>برانچ #</span> <span>مین #</span></th>
                            <th width="5%">یوزر</th>
                            <th width="5%">کھاتہ نمبر</th>
                            <th width="7%">روزنامچہ نمبر</th>
                            <th width="7%" class="">تاریخ ادائیگی</th>
                            <th width="10%" class="">بینک نام</th>
                            <th width="5%">نام</th>
                            <th width="7%">نمبر</th>
                            <th width="26%">تفصیل</th>
                            <th width="7%">بنام</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $records = mysqli_query($connect, $sql);
                        if (mysqli_num_rows($records) > 0) {
                            while ($roz = mysqli_fetch_assoc($records)) { ?>
                                <tr>
                                    <td><?php echo $roz['r_date']; ?></td>
                                    <td><a href="roznamcha-karobar-add.php?id=<?php echo $roz["r_id"]; ?>">
                                            <?php echo $roz['branch_serial']; ?></a>
                                    </td>
                                    <td><?php echo getTableDataByIdAndColName('users', $roz['user_id'], 'username'); ?></td>
                                    <td><?php echo $roz['khaata_no']; ?></td>
                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                    <td><?php echo $roz['r_date_payment']; ?></td>
                                    <td class="urdu-td small"><?php echo getTableDataByIdAndColName('banks', $roz['bank_id'], 'bank_name'); ?></td>
                                    <td class="urdu-td small"><?php echo $roz['r_name']; ?></td>
                                    <td><?php echo $roz['r_no']; ?></td>
                                    <td class="urdu-td small">
                                    <span class=""><i
                                                class="bi bi-user"></i><?php echo $roz['r_date_payment']; ?></span>
                                        <?php echo getTableDataByIdAndColName('banks', $roz['bank_id'], 'bank_name'); ?>
                                        <?php echo $roz['details']; ?>
                                    </td>
                                    <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                                </tr>
                            <?php }
                        } else {
                            echo '<tr class="text-center"><th colspan="10">آج کی تاریخ میں کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
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
    $(function () {
        /*$('#r_date_start, #r_date_end').change(function () {
            document.datesSubmit.submit();
        });*/
        $('#branch_id').change(function () {
            document.userNameSubmit.submit();
        });
    });
</script>
