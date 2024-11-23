<?php include("header.php"); ?>
<?php $truck_no = $date_msg = $truck_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered = 1  ";
if ($_POST) {
    $removeFilter = removeFilter('import-maal-entry');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['truck_no']) && !empty($_POST['truck_no'])) {
        $truck_no = $_POST['truck_no'];
        $sql .= " AND truck_no LIKE " . "'%$truck_no%'" . " ";
        $truck_msg = '<span class="badge bg-primary">' . $truck_no . '</span>';
    }
}
$sql .= " ORDER BY id DESC ";
//$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered = 1  {$truck_no_append} {$date_append}";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . $truck_msg . $removeFilter; ?>
</div>
<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n2">امپورٹ مال انٹری فارم</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap gap-2">
        <div class="urdu d-flex align-items-center wd-md-100">
            <label class="input-group-text">تعداد</label>
            <input class="form-control" id="count_rows_span" readonly>
        </div>
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-100 mb-2 mb-md-0" id="flatpickr-date">
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
            <div class="input-group wd-150 ms-3 mb-2 mb-md-0 ">
                <label for="truck_no" class="input-group-text input-group-addon bg-transparent urdu">ٹرک
                    نمبر</label>
                <input type="text" id="truck_no" name="truck_no" class="form-control bg-transparent border-primary"
                       placeholder="ٹرک نمبر" value="<?php echo $truck_no; ?>" required>
            </div>
        </form>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش (F2)">
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
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered table-sm" id="fix-head-table">
                        <thead>
                        <tr class="text-nowrap">
                            <th>لوڈنگ #</th>
                            <th>ٹرک نمبر</th>
                            <th>ڈرائیورنام</th>
                            <th>سیریل تعداد</th>
                            <th>بھیجنےوالا</th>
                            <th>وصول کرنےوالا</th>
                            <th> شہر</th>
                            <th>لوڈکرانےگودام</th>
                            <th>خالی کرانےگودام</th>
                            <th>ٹوٹل باردن</th>
                            <th>ٹوٹل وزن</th>
                            <!--<th>ٹرانسفرتاریخ</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?php $count_rows = 0;
                        while ($loading = mysqli_fetch_assoc($records)) {
                            $rowColor = "";
                            $btn_Array = array('class' => 'btn-dark', 'text' => 'اوپن');
                            $tadadQuery = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                            $is_saved = $loading['is_saved'];
                            if ($is_saved == 0) {
                                if (mysqli_num_rows($tadadQuery) > 0) {
                                    $rowColor = 'bg-warning bg-opacity-10';
                                    $btn_Array = array('class' => 'btn-warning', 'text' => 'محفوظ');
                                } else {
                                    $rowColor = 'bg-danger bg-opacity-10';
                                    $btn_Array = array('class' => 'btn-danger', 'text' => 'انٹری');
                                }
                            } else {
                                continue;
                            }
                            if (!empty($loading['sender_receiver'])) {
                                $sender_receiver = json_decode($loading['sender_receiver']);
                                $names = array(
                                    'sender_name' => $sender_receiver->sender_name,
                                    'sender_address' => $sender_receiver->sender_address,
                                    'sender_mobile' => $sender_receiver->sender_mobile,
                                    'sender_wa' => $sender_receiver->sender_wa,
                                    'receiver_name' => $sender_receiver->receiver_name,
                                    'receiver_address' => $sender_receiver->receiver_address,
                                    'receiver_mobile' => $sender_receiver->receiver_mobile,
                                    'receiver_wa' => $sender_receiver->receiver_wa
                                );
                            } else {
                                $names = array(
                                    'sender_name' => '',
                                    'sender_address' => '',
                                    'sender_mobile' => '',
                                    'sender_wa' => '',
                                    'receiver_name' => '',
                                    'receiver_address' => '',
                                    'receiver_mobile' => '',
                                    'receiver_wa' => ''
                                );
                            } ?>
                            <tr class="<?php echo $rowColor; ?>">
                                <td>
                                    <?php echo $loading["id"]; ?>
                                    <a href="import-maal-entry-add?id=<?php echo $loading['id']; ?>&return_url=import-maal-entry"
                                       class="btn <?php echo $btn_Array['class']; ?> pt-0 pb-1 px-1 btn-sm small-3"><?php echo $btn_Array['text']; ?></a>
                                </td>
                                <td class=""><?php echo strtoupper($loading['truck_no']); ?></td>
                                <td class="small-2"><?php echo $loading['driver_name']; ?>
                                    <br><span dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                                <td><?php echo mysqli_num_rows($tadadQuery); ?></td>
                                <td class="small-2"><?php echo $names['sender_name']; ?>
                                    <br><span dir="ltr"><?php echo $names['sender_mobile']; ?></span></td>
                                <td class="small-2"><?php echo $names['receiver_name']; ?>
                                    <br><span dir="ltr"><?php echo $names['receiver_mobile']; ?></span></td>
                                <td class="small"><?php echo $loading['sender_city']; ?></td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                    <br><span
                                        dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                                </td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                    <br><span dir="ltr">
                                        <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                                </td>
                                <?php $maals = fetch('imp_truck_maals', array('imp_tl_id' => $loading['id']));
                                $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = 0;
                                while ($maal = mysqli_fetch_assoc($maals)) {
                                    $json = json_decode($maal['json_data']);
                                    $bardana_qty += $json->bardana_qty;
                                    $per_wt += $json->per_wt;
                                    $total_wt += $json->total_wt;
                                    $empty_wt += $json->empty_wt;
                                    $total_empty_wt += $json->total_empty_wt;
                                    $saaf_wt += $json->saaf_wt;
                                } ?>
                                <td><?php echo $bardana_qty; ?></td>
                                <td><?php echo $total_wt; ?></td>
                                <?php $maals = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                                if (mysqli_num_rows($maals) > 0) {
                                    while ($maal = mysqli_fetch_assoc($maals)) {
                                        $json = json_decode($maal['json_data']);
                                        $bardana_qty += $json->bardana_qty;
                                        $per_wt += $json->per_wt;
                                        $total_wt += $json->total_wt;
                                        $empty_wt += $json->empty_wt;
                                        $total_empty_wt += $json->total_empty_wt;
                                        $saaf_wt += $json->saaf_wt;
                                    }
                                } ?>
                                <!--<td class="small"><?php /*echo $loading['transferred_at']; */ ?></td>-->
                            </tr>
                            <?php $count_rows++;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="count_rows" value="<?php echo $count_rows; ?>">
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
            var truck_no = $("#truck_no").val();
            if (truck_no == '' || truck_no.length < 3) {
                evt.preventDefault();
                return false;
            }
            document.datesSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $('#r_date_start, #r_date_end').change(function () {
        document.datesSubmit.submit();
    });
    $("#count_rows_span").val($("#count_rows").val());
</script>
