<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = $p_start_date = $p_end_date = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM buys WHERE id > 0 ";
if ($_POST) {
    $removeFilter = removeFilter('buys');
    if (isset($_POST['date_start']) && isset($_POST['date_end'])) {
        $p_start_date = $start_date = date('Y-m-d', strtotime($_POST['date_start']));
        $p_end_date = $end_date = date('Y-m-d', strtotime($_POST['date_end']));
        $sql .= " AND b_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
}
$sql .= " ORDER BY dr_khaata_json  ";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>' . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-lg-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 d-none d-md-block">خریداری فارم تفصیل</h4>
        <h4 class="mb-3 mb-md-0 d-block d-md-none">خریداری</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-md-120  wd-100 mb-2 mb-md-0" id="flatpickr-date">
                <input id="date_start" name="date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="date_start" class="input-group-text urdu d-none d-md-block">سے</label>
            </div>
            <div class="flatpickr wd-md-100 wd-80 mb-2 mb-md-0 me-lg-5" id="flatpickr-date">
                <input id="date_end" name="date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
        </form>
        <form action="print/buys" method="get" target="_blank">
            <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
            <input name="date_start" value="<?php echo $p_start_date; ?>" type="hidden">
            <input name="date_end" value="<?php echo $p_end_date; ?>" type="hidden">
            <button type="submit" class="btn btn-primary btn-icon-text pt-0 me-1 px-md-2 px-0">
                <i class="btn-icon-prepend me-0" data-feather="printer"></i>
            </button>
        </form>
        <a href="buys-add" class="btn btn-outline-primary pb-2 pt-1 px-md-2 px-0">اندراج</a>
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
                <table class="table table-bordered table-sm" id="fix-head-table">
                    <thead>
                    <tr>
                        <th>بل#</th>
                        <th>یوزر</th>
                        <th>تاریخ</th>
                        <th>جنس</th>
                        <th>لاٹ نام</th>
                        <th>خرید شہر</th>
                        <th>بل نمبر</th>
                        <th>بنام اکاؤنٹ</th>
                        <th>جمع اکاؤنٹ</th>
                        <th>خریداری</th>
                        <th>فروشی</th>
                        <th>بیلنس</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while ($loading = mysqli_fetch_assoc($records)) {
                        $dr_khaata_json = json_decode($loading['dr_khaata_json']);
                        $jmaa_khaata_noo = empty($dr_khaata_json) ? '' : $dr_khaata_json->jmaa_khaata_no;
                        $rowColor = empty($dr_khaata_json) ? 'bg-danger bg-opacity-10' : '';
                        ?>
                        <tr class="<?php echo $rowColor; ?>">
                            <td>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="buys-add?id=<?php echo $loading["id"]; ?>"><?php echo $loading["id"]; ?></a>
                                    <form action="print/buys-add" method="get" target="_blank">
                                        <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
                                        <input name="buys_id" value="<?php echo $loading["id"]; ?>" type="hidden">
                                        <button type="submit" class="btn p-0"><i class="fa fa-print"></i></button>
                                    </form>
                                </div>
                            </td>
                            <td><?php echo $loading['username']; ?></td>
                            <td><?php echo $loading['b_date']; ?></td>
                            <td class="small"><?php echo $loading['jins']; ?></td>
                            <td class="small"><?php echo $loading['allot_name']; ?></td>
                            <td class="small"><?php echo $loading['loading_city']; ?></td>
                            <td><?php echo $loading['bail_no']; ?></td>
                            <td><?php echo $loading['bnaam_khaata_no']; ?></td>
                            <td><?php echo $jmaa_khaata_noo; ?></td>
                            <td><?php echo buyBalance($loading['id']); ?></td>
                            <td><?php echo sellBalance($loading['id']); ?></td>
                            <td><?php echo buySellBalance($loading['id']); ?></td>
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