<?php include("header.php");
$pageURL = 'imp-gate-pass-entry';
$truck_no = $truck_no_msg = $godam_msg = $date_msg = $removeFilter = "";
$godam_empty_id = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM imp_truck_loadings WHERE is_transfered=1 AND is_saved=1 ";
if ($_POST) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date' ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['godam_empty_id']) && $_POST['godam_empty_id'] > 0) {
        $godam_empty_id = $_POST['godam_empty_id'];
        $sql .= " AND godam_empty_id = " . "'$godam_empty_id'" . " ";
        $godam_msg = '<span class="badge bg-warning ms-1 urdu">' . getTableDataByIdAndColName('godam_empty_forms', $godam_empty_id, 'name') . '</span>';
    }
    if (isset($_POST['truck_no']) && !empty($_POST['truck_no'])) {
        $truck_no = $_POST['truck_no'];
        $sql .= " AND truck_no LIKE " . "'%$truck_no%'" . " ";
        $truck_no_msg = '<span class="badge bg-primary ms-1 urdu">' . $truck_no . '</span>';
    }
    echo '<div class="filter-div">' . $date_msg . $godam_msg . $truck_no_msg . $removeFilter . '</div>';
}
$sql .= " ORDER BY id DESC";
$records = mysqli_query($connect, $sql); ?>
<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap gap-0 grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0 mt-n2"> امپورٹ گیٹ پاس انٹری</h4>
    </div>
    <form name="filterForm" method="POST" class="d-flex">
        <div class="input-group flatpickr wd-100 mb-2 mb-md-0" id="flatpickr-date">
            <!--<label for="r_date_start" class="input-group-text urdu">تاریخ</label>-->
            <input id="r_date_start" name="r_date_start" value="<?php echo $start_date; ?>" type="text"
                   class="form-control" placeholder="تاریخ ابتداء" data-input>
            <label for="r_date_end" class="input-group-text urdu">سے</label>
        </div>
        <div class="flatpickr wd-80 mb-2 mb-md-0" id="flatpickr-date">
            <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                   type="text" class="form-control"
                   placeholder="تاریخ انتہاء" data-input>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="godam_empty_id" class="input-group-text  urdu">خالی
                گودام</label>
            <select id="godam_empty_id" name="godam_empty_id" class="form-select">
                <option value="0">تمام گودام</option>
                <?php $empties = fetch('godam_empty_forms');
                while ($empty = mysqli_fetch_assoc($empties)) {
                    $e_selected = $empty['id'] == $godam_empty_id ? 'selected' : '';
                    echo '<option ' . $e_selected . ' value="' . $empty['id'] . '">' . $empty['name'] . '</option>';
                } ?>
            </select>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="truck_no" class="input-group-text urdu">ٹرک نمبر</label>
            <input type="text" id="truck_no" name="truck_no" class="form-control" value="<?php echo $truck_no; ?>">
        </div>
    </form>
    <div class="d-flex align-items-center text-nowrap">
        <div class="input-group wd-80 mb-2 mb-md-0">
            <label for="rows_count_span" class="input-group-text input-group-addon bg-transparent urdu">تعداد</label>
            <input id="rows_count_span" readonly="" class="form-control">
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="gt_bardana_input" class="input-group-text  urdu">ٹوٹل
                باردانہ</label>
            <input id="gt_bardana_input" class="form-control" disabled>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="gt_bardana_qty_gp_input" class="input-group-text  urdu">گیٹ پاس
                انٹری</label>
            <input id="gt_bardana_qty_gp_input" class="form-control" disabled>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-1">
            <label for="gt_bardana_bal_input" class="input-group-text  urdu">بیلنس
                باردانہ</label>
            <input id="gt_bardana_bal_input" class="form-control" disabled>
        </div>
    </div>

    <div>
        <a href="<?php echo $pageURL; ?>" class="btn btn-primary btn-icon-text pt-0 me-1"> تمام</a>
    </div>
</div>
<div class="row mt-3 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="table-responsive scroll screen-ht" style="">
                <table class="table table-bordered table-sm table-hover" id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap ">
                        <th>لوڈنگ #</th>
                        <th class="small">لوڈنگ تاریخ</th>
                        <th>ٹرک نمبر</th>
                        <th>ڈرائیورنام</th>
                        <th class="small-3">سیریل <br> تعداد</th>
                        <th class="small">بھیجنےوالا</th>
                        <th class="small">وصول کرنےوالا</th>
                        <th class="small"> شہر</th>
                        <th class="small">لوڈکرانےگودام</th>
                        <th class="small">خالی کرانےگودام</th>
                        <th>ٹوٹل باردن</th>
                        <th>ٹوٹل وزن</th>

                        <!--<th>گیٹ پاس نمبر</th>
                        <th>گیٹ پاس دینے تاریخ</th>
                        <th>گیٹ پاس دینے والا نام</th>
                        <th>باردانہ </th>-->
                        <th class="small-3">گیٹ پاس <br>باردانہ تعداد</th>
                        <th class="small-2">باردانہ <br>بیلنس</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $no = 1;
                    $rows_count = $gt_bardana = $gt_wt = $gt_bardana_qty_gp = $gt_bardana_bal = 0;
                    while ($loading = mysqli_fetch_assoc($records)) {
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
                        if (empty($loading['transfer_to_forms'])) {
                            continue;
                        } else {
                            $json = json_decode($loading['transfer_to_forms']);
                            $json = implode(',', $json);
                            $json = explode(',', $json);
                            if (in_array("kilo", $json) || in_array("kiraya", $json)) {
                            } else {
                                continue;
                            }
                        }
                        $bardana_qty = $bardana_qtyGP = $total_wt = $total_exp = $bardana_bal = 0;
                        //$ddd = fetch('imp_truck_maals2', array('imp_tl_id' => $loading['id'], 'is_ttr' => 0, 'form_name' => 'beopari_summary'));
                        $data = fetch('imp_truck_maals', array('imp_tl_id' => $loading['id']));
                        while ($maal = mysqli_fetch_assoc($data)) {
                            $json = json_decode($maal['json_data']);
                            $bardana_qty += $json->bardana_qty;
                            $total_wt += $json->total_wt;

                            $gpData = fetch('imp_truck_gp', array('imp_tl_id' => $loading['id'], 'maal_id' => $maal['id']));
                            if (mysqli_num_rows($gpData) > 0) {
                                while ($gpDatum = mysqli_fetch_assoc($gpData)) {
                                    $json_gp = json_decode($gpDatum['json_gp']);
                                    if (!empty($json_gp)) {
                                        $bardana_qtyGP += $json_gp->bardana_qty_gp;
                                    }
                                }
                            }
                        }
                        $bardana_bal = $bardana_qty - $bardana_qtyGP;
                        if ($bardana_bal <= 0) continue;
                        if ($bardana_qtyGP == 0) {
                            $rowColor = 'bg-danger bg-opacity-10';
                        } else {
                            $rowColor = 'bg-warning bg-opacity-10';
                        } ?>
                        <tr class="<?php echo $rowColor; ?>">
                            <td><?php echo $loading["id"]; ?>
                                <a href="imp-gate-pass-entry-add?id=<?php echo $loading['id']; ?>&type=gatepass-entry"
                                   class="btn btn-dark pt-0 small-3 pb-1 px-1 btn-sm">انٹری</a>
                            </td>
                            <td class="small-2"><?php echo $loading['loading_date']; ?></td>
                            <td class="small"><?php echo strtoupper($loading['truck_no']); ?></td>
                            <td class="small-2">
                                <?php echo $loading['driver_name']; ?>
                                <br><span dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                            <td>
                                <?php $tadadQuery = fetch('imp_truck_maals', array('imp_tl_id' => $loading["id"]));
                                echo mysqli_num_rows($tadadQuery); ?>
                            </td>
                            <td class="small-2">
                                <?php echo $names['sender_name']; ?>
                                <br><span dir="ltr"><?php echo $names['sender_mobile']; ?></span></td>
                            <td class="small-2">
                                <?php echo $names['receiver_name']; ?>
                                <br><span dir="ltr"><?php echo $names['receiver_mobile']; ?></span></td>
                            <td class="small"><?php echo $loading['sender_city']; ?></td>
                            <td class="small-2">
                                <?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                <br><span
                                    dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-2">
                                <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                <br><span dir="ltr">
                                        <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                            </td>
                            <td><?php echo $bardana_qty; ?></td>
                            <td><?php echo $total_wt; ?></td>
                            <td class="text-danger"><?php echo $bardana_qtyGP; ?></td>
                            <td><?php echo $bardana_bal; ?></td>
                        </tr>
                        <?php $no++;
                        $gt_bardana += $bardana_qty;
                        $gt_wt += $total_wt;
                        $gt_bardana_qty_gp += $bardana_qtyGP;
                        $gt_bardana_bal += $bardana_bal;
                        $rows_count++;
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="gt_bardana" value="<?php echo $gt_bardana; ?>">
                <input type="hidden" id="gt_wt" value="<?php echo $gt_wt; ?>">
                <input type="hidden" id="gt_bardana_qty_gp" value="<?php echo $gt_bardana_qty_gp; ?>">
                <input type="hidden" id="gt_bardana_bal" value="<?php echo $gt_bardana_bal; ?>">
                <input type="hidden" id="rows_count" value="<?php echo $rows_count; ?>">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#gt_bardana_input").val($("#gt_bardana").val());
    $("#gt_bardana_qty_gp_input").val($("#gt_bardana_qty_gp").val());
    $("#gt_bardana_bal_input").val($("#gt_bardana_bal").val());
    $("#rows_count_span").val($("#rows_count").val());
</script>
<script>
    $(document).ready(function () {
        $('#godam_empty_id').on('change', function () {
            document.filterForm.submit();
        });
        $('#r_date_start, #r_date_end').change(function () {
            document.filterForm.submit();
        });
    });
    document.onkeydown = function (evt) {
        let keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            let truck_no = $("#truck_no").val();
            if (truck_no == '' || truck_no.length < 2) {
                evt.preventDefault();
                return false;
            }
            document.filterForm.submit();
        }
    }
</script>
