<?php include("header.php"); ?>
<?php $date_msg = $username_msg = $searchUserName = $removeFilter = $branch_msg = "";
$start_date = $end_date = date('Y-m-d');
$selectedBranchId = 0;
$sql = "SELECT * FROM afghani_truck WHERE id > 0 ";

if (!Administrator()) {
    $sql .= "AND branch_id = " . "'$branchId'" . " ";
}
if ($_POST) {
    $removeFilter = removeFilter('afghani-truck-kiraya');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $sql .= " AND afg_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $searchUserName = $_POST['username'];
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        $username_msg = '<span class="badge bg-primary pt-2 ms-1">' . $searchUserName . '</span>';
    }
    if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
        $postBranchId = $_POST['branch_id'];
        if ($postBranchId > 0) {
            $sql .= "AND branch_id = " . "'$postBranchId'" . " ";
            $selectedBranchId = $postBranchId;
            $branch_msg = '<span class="badge bg-dark urdu ms-1">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
        }
    }
} else {
    $sql .= " AND afg_date = '$start_date'";
}

$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$bnaamTotal = $jmaaTotal = $mezan = $numRows = 0;
$numRows = mysqli_num_rows($records);
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['total_bill'];
    $jmaaTotal += $stat['total_bill'];
}
$mezan = $jmaaTotal - $bnaamTotal; ?>
<div class="filter-div">
    <?php echo $date_msg . $username_msg . $branch_msg . $removeFilter; ?>
</div>
<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">افغانی ٹرک کرایہ</h4>
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
            <div class="flatpickr wd-100 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
            <?php if (Administrator()) { ?>
                <div class="input-group wd-140 mb-2 mb-md-0">
                    <label for="branch_id"
                           class="input-group-text input-group-addon bg-transparent urdu ps-0">برانچ</label>
                    <select id="branch_id" name="branch_id" class="form-select bg-transparent border-primary">
                        <option hidden value="">برانچ انتخاب</option>
                        <option value="0" <?php echo ($selectedBranchId == 0) ? 'selected' : ''; ?>>آل برانچ</option>
                        <?php $branches = fetch('branches');
                        while ($branch = mysqli_fetch_assoc($branches)) {
                            $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                            echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                        } ?>
                    </select>
                </div>
            <?php } ?>
            <!--<div class="input-group wd-120 mb-2 mb-md-0">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu ps-0">یوزر</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="یوزر" value="<?php /*echo $searchUserName; */ ?>" required>
            </div>-->
        </form>
        <div class="input-group wd-80 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">اندراج</span>
            <input type="text" class="form-control bg-transparent border-primary"
                   value="<?php echo $numRows; ?>">
        </div>
        <div class="input-group wd-110 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش (F2)">
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل جمع</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل جمع"
                   value="<?php echo $jmaaTotal; ?>" readonly>
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0 me-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="میزان"
                   value="<?php echo $mezan; ?>" readonly>
        </div>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();">
            <i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <a href="afghani-truck-kiraya-add"
           class="btn btn-outline-primary pb-2 pt-1">اندراج</a>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-bod">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered table-hover table-sm" id="fix-head-table">
                        <thead>
                        <tr>
                            <th>سیریل #</th>
                            <?php if (Administrator()) { ?>
                                <th>برانچ</th>
                            <?php } ?>
                            <th>تاریخ</th>
                            <th>شہر</th>
                            <th>ٹرک نمبر</th>
                            <th>ٹرک نام</th>
                            <th>ڈرائیور</th>
                            <th>موبائل نمبر</th>
                            <th>لوڈنگ کرنے گودام</th>
                            <th>خالی کرنے گودام</th>
                            <th>جمع کھاتہ نمبر</th>
                            <th>بنام کھاتہ نمبر</th>
                            <th>ٹوٹل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (mysqli_num_rows($records) > 0) {
                            while ($roz = mysqli_fetch_assoc($records)) {
                                $is_transfered = $roz['is_transfered'];
                                $id = $roz['id'];
                                $submitMsg = 'کیا آپ افغانی ٹرک کرایہ کو ٹرانسفر کرنا چاہتے ہیں؟' . '\n';
                                $submitMsg .= 'سیریل: ' . $id . '\n';
                                $submitMsg .= 'رقم: ' . $roz['total_bill'] . '\n';
                                $rowColor = $is_transfered == 0 ? 'bg-danger bg-opacity-10' : ''; ?>
                                <tr class="<?php echo $rowColor; ?>">
                                    <td class="d-flex justify-content-between">
                                        <div>
                                            <a href="afghani-truck-kiraya-add?id=<?php echo $id; ?>"><?php echo $roz["id"]; ?></a>
                                        </div>
                                        <div class="d-flex justify-content-between gap-2 align-items-center">
                                            <form action="ajax/transferAFG.php" method="post"
                                                  onsubmit="return confirm('<?php echo $submitMsg; ?>')">
                                                <input type="hidden" name="id" value="<?php echo $roz['id']; ?>">
                                                <input type="hidden" name="amount"
                                                       value="<?php echo $roz['total_bill']; ?>">
                                                <input type="hidden" name="url" value="afghani-truck-kiraya">
                                                <?php if ($is_transfered == 0 || Administrator()) { ?>
                                                    <button type="submit" class="btn btn-primary btn-sm pt-0 pb-1 small">
                                                        ٹرانسفر
                                                    </button>
                                                <?php }
                                                if (Administrator() && $is_transfered == 1) {
                                                    $rozQ1 = fetch('roznamchaas', array('r_type' => 'karobar', 'transfered_from_id' => $id, 'transfered_from' => 'AFG'));
                                                    while ($roz1 = mysqli_fetch_assoc($rozQ1)) {
                                                        echo '<input type="hidden" value="' . $roz1['r_id'] . '" name="r_id[]">';
                                                    }
                                                } ?>
                                            </form>
                                            <?php if ($is_transfered == 1) {
                                                echo '<a target="_blank" class="btn btn-success py-0 small btn-sm " data-tooltip="افغانی ٹرک کا پرنٹ" data-tooltip-position="left" href="print/bill_roznamcha?id=' . $roz["id"] . '&secret=' . base64_encode("powered-by-upsol") . '&url=' . base64_encode("ut-commission-border-bill") . '&type=' . BR_AFG_TRUCK_KIRAYA . '"><i class="fa fa-print"></i></a>';
                                            } ?>
                                        </div>
                                    </td>
                                    <?php if (Administrator()) { ?>
                                        <td class="small">
                                            <?php echo getTableDataByIdAndColName('branches', $roz['branch_id'], 'b_name'); ?></td>
                                    <?php } ?>
                                    <td><?php echo $roz['afg_date']; ?></td>
                                    <td class="small"><?php echo $roz['sender_city']; ?></td>
                                    <td><?php echo $roz['afg_truck_no']; ?></td>
                                    <td class="small"><?php echo $roz['afg_truck_name']; ?></td>
                                    <td><?php echo $roz['driver_name']; ?></td>
                                    <td class="ltr small"><?php echo $roz['driver_mobile']; ?></td>
                                    <td class="small"><?php echo getTableDataByIdAndColName('godam_loading_forms', $roz['godam_loading_id'], 'name'); ?></td>
                                    <td class="small"><?php echo getTableDataByIdAndColName('godam_empty_forms', $roz['godam_empty_id'], 'name'); ?></td>
                                    <td class=""><?php echo $roz['afg_jmaa_khaata_no']; ?></td>
                                    <td class=""><?php echo $roz['afg_bnaam_khaata_no']; ?></td>
                                    <td class=""><?php echo $roz['total_bill']; ?></td>
                                </tr>
                            <?php }
                        } else {
                            echo '<tr class="text-center"><th colspan="13">آج کی تاریخ میں کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
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
    function transferAFG(e) {
        var id = $(e).attr('id');
        var amount = $(e).attr('data-amount');
        var url = $(e).attr('data-url');
        if (id) {
            if (confirm('کیا آپ افغانی ٹرک کرایہ کو ٹرانسفر کرنا چاہتے ہیں?\n سیریل#:' + id + '\nرقم: ' + amount)) {
                window.location.href = 'ajax/transferAFG.php?id=' + id + '&amount=' + amount + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>