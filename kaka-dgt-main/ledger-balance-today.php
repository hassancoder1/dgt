<?php include("header.php"); ?>
<?php $cat_ids = array();
$c_selected = $selectedBranch = $removeFilter = $date_msg = $cat_msg = $branch_msg = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id != 0 ";
if (isset($_POST['cat_ids']) && !empty($_POST['cat_ids'][0])) {
    $cat_ids = $_POST['cat_ids'];
    $cat_ids = explode(',', $cat_ids[0]);
    $in = "(" . implode(',', $cat_ids) . ")";
    $sql .= " AND cat_id IN " . $in;
    $cat_msg = 'Categories: ';
    foreach ($cat_ids as $cc) {
        $cat_msg .= getTableDataByIdAndColName('cats', $cc, 'c_name') . ' ';
    }
    $cat_msg = '<span class="badge bg-primary pt-2">' . $cat_msg . '</span>';
}
if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
    $postBranchId = $_POST['branch_id'];
    $selectedBranchId = $postBranchId;
    if ($postBranchId > 0) {
        $sql .= " AND khaata_branch_id = " . "'$postBranchId'" . " ";
        if ($selectedBranchId > 0) {
            $branch_msg = '<span class="badge bg-dark urdu">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
        } else {
            $branch_msg = '<span class="badge bg-dark urdu">آل برانچ</span>';
        }
    }
}
if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
    $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
    $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
    $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
    $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
}
if ($_POST) {
    $removeFilter = removeFilter('ledger-balance-today');
}
//$sql = "SELECT DISTINCT khaata_no,khaata_id FROM `roznamchaas` WHERE r_id != 0 {$date_append} {$branch_append} {$cat_append}";
//echo $sql;
$sqlStats = "SELECT * FROM `roznamchaas` WHERE r_id != 0";
$recordStats = mysqli_query($connect, $sqlStats);
$bnaamTotal = $jmaaTotal = $mezan = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal;
?>
<div class="filter-div">
    <?php echo $date_msg . $branch_msg . $cat_msg;
    echo $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-2 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">آج کا بیلنس</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-120 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-90 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
            <div class="input-group wd-140 mb-2 mb-md-0 ms-2">
                <select multiple name="cat_ids[]" id="cat_ids" placeholder="کیٹیگری"
                        class="virtual-select bg-transparent">
                    <?php $cats = fetch('cats');
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        $c_selected = in_array($cat['id'], $cat_ids) ? 'selected' : '';
                        echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['c_name'] . '</option>';
                    } ?>
                </select>
            </div>
            <?php if (Administrator()) { ?>
                <div class="input-group wd-150 mb-2 mb-md-0">
                    <label for="branch_id" class="input-group-text input-group-addon bg-transparent urdu">برانچ</label>
                    <select id="branch_id" name="branch_id" class="form-select bg-transparent border-primary">
                        <option hidden value="">برانچ ؟</option>
                        <option value="0" <?php echo ($selectedBranchId == 0) ? 'selected' : ''; ?>>آل برانچ</option>
                        <?php $branches = fetch('branches');
                        while ($branch = mysqli_fetch_assoc($branches)) {
                            $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                            echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                        } ?>
                    </select>
                </div>
            <?php } ?>
        </form>
        <div class="input-group wd-120 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu ps-0">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش (F2)">
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل جمع</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل جمع"
                   value="<?php echo $jmaaTotal; ?>" readonly>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <div class="input-group wd-160 mb-2 mb-md-0 me-2">
            <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="میزان"
                   value="<?php echo $mezan; ?>" readonly>
        </div>
        <button type="button" class="btn btn-primary btn-icon-text pt-0"
                onclick="window.print();">
            <i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="table-responsive scroll screen-ht_" style="height: 79vh;">
                <table class="table table-bordered " id="fix-head-table">
                    <thead>
                    <tr>
                        <th class="small" width="4%">کیٹیگری</th>
                        <th>برانچ</th>
                        <th>کھاتہ نمبر</th>
                        <th>کھاتہ نام</th>
                        <th>کمپنی نام</th>
                        <th>کاروبار نام</th>
                        <th>واٹس ایپ</th>
                        <!--<th>پرانا بیلنس</th>-->
                        <th>انٹری تاریخ</th>
                        <th>جمع</th>
                        <th>بنام</th>
                        <th>بیلنس</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $khaataQ = mysqli_query($connect, $sql);
                    $balance = $balanceToday = $todayTotalJmaa = $todayTotalBnaam = $number = 0;
                    while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                        $k_id = $khaata['khaata_id'];
                        $khaataData = mysqli_query($connect, "SELECT * FROM khaata WHERE id = '$k_id'");
                        $khaataDatum = mysqli_fetch_assoc($khaataData);
                        $k_no = $khaataDatum['khaata_no'];
                        $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                        $records = mysqli_query($connect, $innerSql);
                        $jb = mysqli_fetch_assoc($records);
                        /*Today's / selected dates jmaa, bnaam, balance*/
                        $todaySql = "SELECT r_type,MAX(r_date) as todays_large_date, SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id' AND r_date BETWEEN '$start_date' AND '$end_date'";
                        $todays = mysqli_query($connect, $todaySql);
                        $todayData = mysqli_fetch_assoc($todays);
                        if ($todayData['jmaa_amount'] > 0 || $todayData['bnaam_amount'] > 0) {
                            $number++; ?>
                            <tr class="text-nowrap">
                                <td class="d-block small">
                                    <?php echo getTableDataByIdAndColName('cats', $khaataDatum['cat_id'], 'c_name'); ?>
                                    <span class="float-end small-2 pt-0 mt-1 px-1 badge bg-light border-secondary border text-dark rounded-0"><?php echo $number; ?></span>
                                </td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('branches', $khaataDatum['branch_id'], 'b_name'); ?></td>
                                <td><a href="ledger-form?back-khaata-no=<?php echo $k_no; ?>"
                                       target="_blank"><?php echo $k_no; ?></a></td>
                                <td class="small-2"><?php echo $khaataDatum['khaata_name']; ?></td>
                                <td class="small-2"><?php echo $khaataDatum['comp_name']; ?></td>
                                <td class="small-2"><?php echo $khaataDatum['business_name']; ?></td>
                                <td class="ltr small-2"><?php echo $khaataDatum['whatsapp']; ?></td>
                                <?php $balance = $jb['jmaa_amount'] - $jb['bnaam_amount']; ?>

                                <!--<td class="<?php /*echo $balance > 0 ? 'text-success' : 'text-danger'; */ ?> ltr">
                                    <?php /*echo $balance;
                                    echo $_POST ? 'posted' : 'noo post'; */ ?>
                                </td>-->

                                <td><?php echo $todayData['todays_large_date']; ?></td>
                                <td><?php echo $todayData['jmaa_amount']; ?></td>
                                <td class="text-danger"><?php echo $todayData['bnaam_amount']; ?></td>
                                <?php $todayTotalJmaa += $todayData['jmaa_amount'];
                                $todayTotalBnaam += $todayData['bnaam_amount'];
                                $balanceToday = $todayData['jmaa_amount'] - $todayData['bnaam_amount']; ?>
                                <td class="ltr <?php echo $balance + $balanceToday >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $balance + $balanceToday; ?></td>
                            </tr>
                            <?php
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
            <span class="d-none" id="todayTotalJmaaSpan"><?php echo $todayTotalJmaa; ?></span>
            <span class="d-none" id="todayTotalBnaamSpan"><?php echo $todayTotalBnaam; ?></span>
        </div>
    </div>
</div>
<div class="card shadow-lg" style="position: fixed; bottom: 0; width: 100%; left: 0; right: 0;">
    <div class="card-body pt-0 pb-1">
        <div class="d-flex float-end">
            <div class="w-200">
                <p class="d-flex">
                    <span class="urdu me-2">آج جمع</span>
                    <span class="mt-1 text-success border-bottom border-secondary px-3"
                          id="todayTotalJmaaBottom"></span>
                </p>
            </div>
            <div class="w-200 ms-4">
                <p class="d-flex">
                    <span class="urdu me-2">آج بنام</span>
                    <span class="mt-1 text-danger border-bottom border-secondary px-3"
                          id="todayTotalBnaamBottom"></span>
                </p>
            </div>
            <!--<div class="input-group wd-200 mb-2 mb-md-0">
                <span class="input-group-text input-group-addon bg-transparent urdu">آج جمع</span>
                <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="آج ٹوٹل جمع"
                       readonly id="todayTotalJmaa">
            </div>-->
            <!--<div class="input-group wd-130 mb-2 mb-md-0">
                <span class="input-group-text input-group-addon bg-transparent urdu">آج بنام</span>
                <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="آج ٹوٹل بنام"
                       readonly id="todayTotalBnaam">
            </div>-->
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    var todayTotalJmaa = $("#todayTotalJmaaSpan").text();
    var todayTotalBnaam = $("#todayTotalBnaamSpan").text();
    $("#todayTotalJmaa").val(todayTotalJmaa);
    $("#todayTotalJmaaBottom").text(todayTotalJmaa);
    $("#todayTotalBnaam").val(todayTotalBnaam);
    $("#todayTotalBnaamBottom").text(todayTotalBnaam);
</script>
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
        $('#r_date_start, #r_date_end,#branch_id, #cat_ids').change(function () {
            document.datesSubmit.submit();
        });
        /*$('#branch_id, #cat_ids').change(function () {
         document.userNameSubmit.submit();
         });*/
    });
</script>
