<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM buys WHERE id > 0 ";
if ($_POST) {
    $removeFilter = removeFilter('buys');
    if (isset($_POST['date_start']) && isset($_POST['date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['date_end']));
        $sql .= " AND b_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
}
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>' . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">خریداری اضافی خرچہ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-150 mb-2 mb-md-0" id="flatpickr-date">
                <input id="date_start" name="date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-120 mb-2 mb-md-0 me-3" id="flatpickr-date">
                <input id="date_end" name="date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
        </form>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <a href="buys-add" class="btn btn-outline-primary pb-2 pt-1">اندراج</a>
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
                        <th class="small-2">بل نمبر</th>
                        <th class="small">کنٹینر نمبر</th>
                        <th>لاٹ نام</th>
                        <th>لوڈنگ گودام</th>
                        <th>خریدار نام</th>
                        <th>باردانہ نام</th>
                        <th>مارکہ</th>
                        <th>باردانہ تعداد</th>
                        <th>فی وزن</th>
                        <th>ٹوٹل وزن</th>
                        <th>صاف وزن</th>
                        <th>رقم</th>
                        <th>تفصیل</th>
                        <th>ٹوٹل</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $buys_details = fetch('buys_details', array('is_extra_exp' => 1));
                    while ($details = mysqli_fetch_assoc($buys_details)) {
                        $buys_details_expenses = fetch('buys_details_expenses', array('bd_id' => $details['id']));
                        $bd_exp = mysqli_fetch_assoc($buys_details_expenses);
                        $rowClass = 'border ';
                        if (mysqli_num_rows($buys_details_expenses) <= 0) {
                            $rowClass = 'bg-danger bg-opacity-25 border border-white';
                        } else {
                            if (empty($bd_exp['khaata_exp'])) {
                                $rowClass = 'bg-warning bg-opacity-25 border border-white';
                            }
                        } ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <td>
                                <a href="buys-extra-exp-add?buys_id=<?php echo $details['buys_id']; ?>&bd_id=<?php echo $details['id']; ?>"><?php echo $details['bill_no']; ?></a>
                            </td>
                            <td><?php echo $details['container_no']; ?></td>
                            <td class="text-nowrap"><?php echo $details['allot_name']; ?></td>
                            <td class="small-2"><?php echo $details['loading_godam']; ?></td>
                            <td class="small-2"><?php echo $details['owner_name']; ?></td>
                            <td><?php echo $details['bardana_name']; ?></td>
                            <td><?php echo $details['marka']; ?></td>
                            <td><?php echo $details['bardana_qty']; ?></td>
                            <td><?php echo round($details['per_wt']); ?></td>
                            <td><?php echo round($details['total_wt']); ?></td>
                            <td><?php echo round($details['saaf_wt']); ?></td>
                            <td><?php echo round($details['qeemat_raqam']); ?></td>
                            <td class="small-2"><?php echo $details['more_details']; ?></td>
                            <?php if (mysqli_num_rows($buys_details_expenses) > 0) {
                                if (!empty($bd_exp['khaata_exp'])) {
                                    echo '<td class="border border-light text-nowrap">';
                                    $khaata_ = json_decode($bd_exp['khaata_exp']);
                                    echo 'رقم: ';
                                    echo $khaata_->total_bill;
                                    echo '<hr class="mt-1 mb-0">';
                                    //echo '<span class="ms-2"></span>';
                                    echo '<span class="text-success">' . 'جمع: '.$khaata_->jmaa_khaata_no . '</span>';
                                    echo '<span class="ms-2"></span>';
                                    echo '<span class="text-danger">' . 'بنام: '.$khaata_->bnaam_khaata_no . '</span>';
                                    echo '</td>';
                                } else {
                                    echo '<td>ٹرانسفر کرنا ہے</td>';
                                }
                            } else {
                                echo '<td>خرچہ ڈالنا ہے</td>';
                            } ?>
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
<script>
    function transferImpTruckLoading(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n سیریل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferDTTruckLoading.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>