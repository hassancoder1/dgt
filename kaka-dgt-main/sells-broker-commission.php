<?php include("header.php"); ?>
<?php $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM `buys_sold` WHERE is_gp =1 AND qeemat_raqam IS NOT NULL ";
if ($_POST) {
    $removeFilter = removeFilter('sells-broker-commission');
    if (isset($_POST['date_start']) && isset($_POST['date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['date_end']));
        $sql .= " AND s_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
}
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 ">فروشی بروکر کمیشن </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-100 mb-2 mb-md-0" id="flatpickr-date">
                <input id="date_start" name="date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-80 mb-2 mb-md-0 me-3" id="flatpickr-date">
                <input id="date_end" name="date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
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
                <table class="table table-bordered " id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap">
                        <th>بل نمبر</th>
                        <th>تاریخ</th>
                        <th>باردانہ تعداد</th>
                        <th>جنس</th>
                        <th>لاٹ نام</th>
                        <th>لوڈنگ گودام</th>
                        <th>بیچنے والا اکاؤنٹ</th>
                        <th>بروکر</th>
                        <th>بادانہ تعداد</th>
                        <th>رقم</th>
                        <th>فیصد</th>
                        <th>بروکرکمیشن</th>
                        <th>کمیشن تاریخ</th>
                        <th>جمع کھاتہ</th>
                        <th>مزید تفصیل</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($sale = mysqli_fetch_assoc($records)) {
                        //$rowClass = $sale['qeemat_raqam'] ? '' : 'bg-danger bg-opacity-10';
                        $rowMsg = array();
                        $info = ' بل نمبر ' . $sale['bill_no'] . ' ';
                        if (empty($sale['bc_total'])) {
                            $rowMsg['class'] = 'bg-danger bg-opacity-10';
                            $rowMsg['msg'] = $info . 'کمیشن کااندراج ابھی تک نہیں ہوا۔';
                            $rowMsg['fa'] = 'fa-info-circle text-danger';
                        } else {
                            if (empty($sale['bc_jmaa_khaata_no'])) {
                                $rowMsg['class'] = 'bg-warning bg-opacity-10';
                                $rowMsg['msg'] = $info . 'روزنامچہ میں ٹرانسفر کرنا باقی ہے۔';
                                $rowMsg['fa'] = 'fa-warning text-warning';
                            } else {
                                $rowMsg['class'] = '';
                                $rowMsg['msg'] = $info . ' کی بروکر کمیشن کاروبار روزنامچہ میں ٹرانسفر ہو چکی ہے۔';
                                $rowMsg['fa'] = 'fa-check-square text-success';
                            }
                        } ?>
                        <tr class=" <?php echo $rowMsg['class']; ?>">
                            <td>
                                <a href="sells-broker-commission-add?buys_id=<?php echo $sale['buys_id']; ?>&buys_sold_id=<?php echo $sale['id']; ?>"><?php echo $sale['bill_no']; ?></a>
                                <?php echo '<i class="fa ' . $rowMsg['fa'] . ' float-end mt-2" data-tooltip="' . $rowMsg['msg'] . '" data-tooltip-position="left"></i>'; ?>
                            </td>
                            <td><?php echo $sale['s_date']; ?></td>
                            <td><?php echo $sale['bardana_qty']; ?></td>
                            <td class="small-2"><?php echo $sale['jins']; ?></td>
                            <td><?php echo $sale['allot_name']; ?></td>
                            <td class="small-2"><?php echo $sale['loading_godam']; ?></td>
                            <td><?php echo $sale['seller_khaata_no']; ?></td>
                            <td class="small"><?php echo $sale['broker_name']; ?></td>
                            <td><?php echo $sale['bardana_qty']; ?></td>
                            <td><?php echo $sale['qeemat_raqam']; ?></td>
                            <td><?php echo $sale['bc_percent']; ?></td>
                            <td><?php echo $sale['bc_total']; ?></td>
                            <td><?php echo $sale['bc_date']; ?></td>
                            <td><?php echo $sale['bc_jmaa_khaata_no']; ?></td>
                            <td class="small-2"><?php echo $sale['bc_report']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script type="text/javascript">
    $('#date_start, #date_end').change(function () {
        document.datesSubmit.submit();
    });
</script>