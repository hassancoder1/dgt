<?php include("header.php"); ?>
<?php $year_msg = $month_msg = $removeFilter = "";
$year = date('Y');
$month = date('m');
$sql = "SELECT * FROM r_munshi_exp WHERE id > 0 ";

if ($_POST) {
    $removeFilter = removeFilter('munshi-exp');
    if (isset($_POST['salary_year']) && is_numeric($_POST['salary_year'])) {
        $year = $_POST['salary_year'];
        //$year = date('Y', strtotime($_POST['salary_year']));
        $sql .= " AND salary_year = '$year'";
        $year_msg = '<span class="badge bg-secondary"><span class="urdu me-2">سال</span>' . $year . '</span>';
    }
    if (isset($_POST['salary_month']) && is_numeric($_POST['salary_month'])) {
        $month = $_POST['salary_month'];
        $sql .= " AND salary_month = '$month'";
        $month_msg = '<span class="badge bg-secondary"><span class="urdu me-2">مہینہ</span>' . $month . '</span>';
    }
} else {
    $sql .= " AND salary_year = '$year' AND salary_month = '$month'";
}
$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$totalSalary = $jmaaTotal = $mezan = $numRows = 0;
$numRows = mysqli_num_rows($records);
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $totalSalary += $stat['salary_amount'];
} ?>
<div class="filter-div">
    <?php echo $year_msg . $month_msg . $removeFilter; ?>
</div>
<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n2">منشی خرچہ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group wd-80 mb-2 mb-md-0">
                <label for="salary_year" class="input-group-text urdu">سال</label>
                <input type="text" id="salary_year" name="salary_year" autofocus
                       value="<?php echo $year; ?>" class="form-control bg-transparent border-primary"
                       data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="yyyy" inputmode="numeric">
            </div>
            <div class="input-group wd-80 mb-2 mb-md-0">
                <label for="salary_month" class="input-group-text urdu">مہینہ</label>
                <input id="salary_month" name="salary_month"
                       value="<?php echo $month; ?>" type="text" class="form-control bg-transparent border-primary"
                       data-inputmask="'alias': 'datetime'" data-inputmask-inputformat="mm" inputmode="numeric">
            </div>
        </form>
        <div class="input-group wd-80 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">اندراج</span>
            <input type="text" class="form-control bg-transparent border-primary"
                   value="<?php echo $numRows; ?>" disabled>
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">تلاش</span>
            <input id="tableFilter" type="text" class="form-control bg-transparent border-primary"
                   placeholder="تلاش (F2)">
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل رقم</span>
            <input type="text" class="form-control bg-transparent border-primary"
                   value="<?php echo $totalSalary; ?>" readonly>
        </div>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();">
            <i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <a href="munshi-exp-add"
           class="btn btn-outline-primary pb-2 pt-1">اندراج</a>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            }
            //echo $sql; ?>
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered " id="fix-head-table">
                    <thead>
                    <tr>
                        <th width="10%">سیریل #</th>
                        <th>نام</th>
                        <th>رقم</th>
                        <th>مہینہ - سال</th>
                        <th>جمع کھاتہ نمبر</th>
                        <th>بنام کھاتہ نمبر</th>
                        <th>تاریخ / تفصیل</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (mysqli_num_rows($records) > 0) {
                        while ($roz = mysqli_fetch_assoc($records)) { ?>
                            <tr>
                                <td class="d-flex align-items-center justify-content-between">
                                    <?php echo $roz["id"]; ?>
                                    <?php if ($roz['is_transferred'] == 0) { ?>
                                        <a class="btn btn-primary py-0 px-1 small"
                                           onclick="transferMunshiExp(this)"
                                           id="<?php echo $roz['id']; ?>" data-url="munshi-exp"
                                           data-amount="<?php echo $roz['salary_amount']; ?>"
                                           data-tooltip="منشی خرچہ کو کاروبار روزنامچہ میں ٹرانسفر کریں۔"
                                           data-tooltip-position="bottom">ٹرانسفر </a>
                                    <?php } else {
                                        echo '<span class="badge bg-light text-dark border float-end border-secondary">ٹرانسفر ہو گیا</span>';
                                    } ?>
                                </td>
                                <td><?php echo getTableDataByIdAndColName('staffs', $roz['staff_id'], 'staff_name'); ?></td>
                                <td class=""><?php echo $roz['salary_amount']; ?></td>
                                <td><?php //echo monthNameENByNumber($roz['salary_month'], 'F');
                                    echo monthNameURByNumber($roz['salary_month']); ?>
                                    <?php echo ' - ' . $roz['salary_year']; ?>
                                </td>
                                <td><?php echo $roz['jmaa_khaata_no']; ?></td>
                                <td class=""><?php echo $roz['bnaam_khaata_no']; ?></td>
                                <td><?php echo $roz['exp_date']; ?><?php echo $roz['details']; ?></td>
                                <td>
                                    <?php if (Administrator()) { ?>
                                        <form method="post"
                                              onsubmit="return confirm('کیا آپ  واقعی ڈیلیٹ کرنا چاہتے ہیں؟');">
                                            <input name="hidden_id" type="hidden" value="<?php echo $roz["id"]; ?>">
                                            <button class="btn btn-danger py-0 px-1 urdu-2 small-2" type="submit"
                                                    name="deleteSubmit">ختم کریں
                                            </button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr class="text-center"><th colspan="7">کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['deleteSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    if ($hidden_id > 0) {
        $done = mysqli_query($connect, "DELETE FROM `r_munshi_exp` WHERE id = '$hidden_id'");
        if ($done) {
            message('success', 'munshi-exp', 'ریکارڈ ڈیلیٹ ہو گیا ہے۔');
        } else {
            message('danger', 'munshi-exp', 'سسٹم پرابلم۔');
        }
    }
} ?>
<script>
    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            var salary_year = $("#salary_year").val();
            var salary_month = $("#salary_month").val();
            if (salary_year == '' || salary_year.length < 4) {
                evt.preventDefault();
                return false;
            }
            document.datesSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $(function () {
        $('#r_date_start, #r_date_end, #branch_id').change(function () {
            document.datesSubmit.submit();
        });
    });
</script>
<script>
    function transferMunshiExp(e) {
        var id = $(e).attr('id');
        var amount = $(e).attr('data-amount');
        var url = $(e).attr('data-url');
        if (id) {
            if (confirm('کیا آپ منشی خرچہ کو کاروبار روزنامچہ میں ٹرانسفرکرنا چاہتے ہیں؟\n سیریل #: ' + id + '\n رقم: ' + amount)) {
                window.location.href = 'ajax/transferMunshiExp.php?id=' + id + '&amount=' + amount + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>