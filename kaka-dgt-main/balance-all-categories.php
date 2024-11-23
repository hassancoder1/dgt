<?php include("header.php"); ?>
<?php
$date_append = "";
$removeFilter = "";
$date_msg = "";
$start_date = date('Y-m-d');
$end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="balance-all-categories.php"><span class="mt-2 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $date_append = " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
} else {
}
$sql = "SELECT DISTINCT cat_id FROM `roznamchaas` WHERE r_id != 0 {$date_append}";
//echo $sql;
$sqlStats = "SELECT * FROM `roznamchaas` WHERE r_id != 0 {$date_append}";
$recordStats = mysqli_query($connect, $sqlStats);
$bnaamTotal = 0;
$jmaaTotal = 0;
$mezan = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal; ?>
<div class="heading-div px-4 py-1 border-bottom">
    <div class="row  ">
        <div class="col-2">
            <h4 class="mt-2">ٹوٹل کیٹیگری بیلنس</h4>
        </div>
        <div class="col-8">
            <form name="userNameSubmit" method="POST">
                <div class="row justify-content-center">
                    <div class="col-2">
                        <div class="input-group flatpickr mb-2 mb-md-0" id="flatpickr-date">
                            <input id="r_date_start" name="r_date_start"
                                   value="<?php echo $start_date; ?>"
                                   type="text" class="form-control bg-transparent border-primary"
                                   placeholder="تاریخ ابتداء" data-input>
                            <label for="r_date_start" class="input-group-text urdu">سے</label>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="flatpickr  mb-2 mb-md-0" id="flatpickr-date">
                            <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                                   type="text" class="form-control bg-transparent border-primary"
                                   placeholder="تاریخ انتہاء" data-input>
                        </div>
                    </div>
                    <div class="col-4">
                        <?php echo $date_msg; ?>
                        <?php echo $removeFilter; ?>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0 float-end"
                    onclick="window.print();">
                <i class="btn-icon-prepend" data-feather="printer"></i>پرنٹ
            </button>
        </div>
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
                <div class="table-responsive "><!--scroll screen-ht-->
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>کیٹیگری</th>
                            <th>کیٹیگری نام</th>
                            <th>بنام بیلنس</th>
                            <th>جمع بیلنس</th>
                            <th>جمع / بنام</th>
                            <th>ٹوٹل بیلنس</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $catQ = mysqli_query($connect, $sql);
                        $jmaaTotalLast = 0;
                        $bnaamTotalLast = 0;
                        while ($cat = mysqli_fetch_assoc($catQ)) {
                            $cat_id = $cat['cat_id'];
                            $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount FROM `roznamchaas` WHERE `cat_id` = '$cat_id'";
                            $records = mysqli_query($connect, $innerSql);
                            $jb = mysqli_fetch_assoc($records);
                            ?>
                            <tr>
                                <td>
                                    <?php echo $start_date; ?>
                                    <span class="mx-2">سے</span>
                                    <?php echo $end_date; ?>
                                </td>
                                <td><?php echo getTableDataByIdAndColName('cats', $cat_id, 'c_name'); ?></td>
                                <td><?php echo getTableDataByIdAndColName('cats', $cat_id, 'c_details'); ?></td>
                                <td class="text-danger"><?php echo $jb['bnaam_amount'];
                                    $bnaamTotalLast += $jb['bnaam_amount']; ?></td>
                                <td><?php echo $jb['jmaa_amount'];
                                    $jmaaTotalLast += $jb['jmaa_amount']; ?></td>
                                <?php $balance = $jb['jmaa_amount'] - $jb['bnaam_amount']; ?>
                                <th></th>
                                <td class="<?php echo ($balance > 0) ? 'text-success' : 'text-danger'; ?> ltr">
                                    <?php echo $jb['jmaa_amount'] - $jb['bnaam_amount']; ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row gx-0 my-4 justify-content-center">
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="tbb" class="input-group-text urdu">ٹوٹل بنام بیلنس</label>
                        <input type="text" id="tbb" name="tbb" class="form-control" readonly
                               value="<?php echo $bnaamTotalLast; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="tjb" class="input-group-text urdu">ٹوٹل جمع بیلنس</label>
                        <input type="text" id="tjb" name="tjb" class="form-control" readonly
                               value="<?php echo $jmaaTotalLast; ?>">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <label for="fraq" class="input-group-text urdu">فرق بیلنس یا جمع/بنام</label>
                        <input type="text" id="fraq" readonly="" name="fraq" class="form-control bold ltr"
                               value="<?php echo $jmaaTotalLast - $bnaamTotalLast; ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script type="text/javascript">
    $(function () {
        $('#r_date_start, #r_date_end').change(function () {
            document.userNameSubmit.submit();
        });
    });
</script>
