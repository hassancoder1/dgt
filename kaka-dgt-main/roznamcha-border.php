<?php include("header.php"); ?>
<?php if (is_clearance_roznamcha_allowed('border', $userId)) {

}else{
    message("danger", "./", "ایڈمن کی طرف سے آپ کو یہ فارم اوپن کرنے کی اجازت نہیں ہے۔");
}
$searchUserName = $removeFilter = "";
$searchDate = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_type= 'ut_border' AND r_date = '$searchDate'";
//echo $sql;
$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$bnaamTotal = $jmaaTotal = $mezan = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal; ?>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mt-n2 mb-3 mb-md-0">روزنامچہ بارڈر</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group wd-140 mb-2 mb-md-0" id="flatpickr-date">
                <label for="today" class="input-group-text input-group-addon bg-transparent urdu">تاریخ</label>
                <input type="text" id="today" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ" value="<?php echo date('Y-m-d'); ?>" data-input <?php $disabledAttr; ?>>
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
        <div class="input-group wd-180 mb-2 mb-md-0 me-2">
            <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="میزان"
                   value="<?php echo $mezan; ?>" readonly>
        </div>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-0"
                onclick="window.print();">
            <i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
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
                            <th width="10%">برانچ سیریل</th>
                            <th width="6%">یوزر</th>
                            <th width="7%">کھاتہ نمبر</th>
                            <th width="7%">روزنامچہ نمبر</th>
                            <th width="10%">نام</th>
                            <th width="7%">نمبر</th>
                            <th width="32%">تفصیل</th>
                            <th width="7%">جمع</th>
                            <th width="7%">بنام</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (mysqli_num_rows($records) > 0) {
                            while ($roz = mysqli_fetch_assoc($records)) { ?>
                                <tr>
                                    <td><?php echo $roz['branch_serial']; ?></td>
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
        $('#today').change(function () {
            document.datesSubmit.submit();
        });
    });
</script>