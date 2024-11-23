<?php include("header.php"); ?>
<?php
$c_selected = '';
$cat_ids = array();
$searchUserName = "";
$cat_append = "";
$selectedBranch = "";
$selectedBranchId = 0;
$branch_append = "";
$removeFilter = "";
$whereInnerSql = "";
if (Administrator()) {
    if (!empty($_POST['branch_id'])) {
        $postBranchId = $_POST['branch_id'];
        $branch_append = "AND branch_id = " . "'$postBranchId'" . " ";
        $selectedBranchId = $postBranchId;
    } else {
        $branch_append = "";
    }
} else {
    $branch_append = "AND branch_id = " . "'$branchId'" . " ";
}
if (isset($_POST['r_date_start']) && isset($_POST['r_date_end']) && Administrator()) {
    $removeFilter = '<a href="ledger-all-categories.php"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
    $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
    $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
    if (isset($_POST['cat_ids']) && !empty($_POST['cat_ids'][0])) {
        $cat_ids = $_POST['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $cat_append = "AND cat_id IN " . $in;
    }
    $sql = "SELECT * FROM roznamchaas WHERE r_date BETWEEN '$start_date' AND '$end_date' {$branch_append} {$cat_append} ";
    $whereInnerSql = "AND r_date BETWEEN '$start_date' AND '$end_date' {$branch_append} {$cat_append} ";
} else {
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d');
    $sql = "SELECT * FROM roznamchaas ";
    //$sql = "SELECT * FROM roznamchaas WHERE r_date BETWEEN '$start_date' AND '$end_date' {$branch_append}";
}
//echo $sql;
//$records = mysqli_query($connect, $sql);
$recordStats = mysqli_query($connect, $sql);
$bnaamTotal = 0;
$jmaaTotal = 0;
$mezan = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal;

?>
<div class="mt-0 filter-div">
    <?php if (isset($_POST['r_date_start'])) {
        echo '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    } ?>
    <?php
    $catMessage = '';
    if (!empty($_POST['cat_ids'][0])) {
        $catMessage = 'Categories: ';

        foreach ($cat_ids as $cc) {
            $catMessage .= getTableDataByIdAndColName('cats', $cc, 'c_name') . ' ';
        }
    }
    echo '<span class="badge bg-secondary pt-2">' . $catMessage . '</span>'; ?>
    <?php if ($selectedBranchId > 0) {
        echo '<span class="badge bg-secondary urdu">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
    }
    echo $removeFilter; ?>
</div>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">آل کیٹیگری کھاتہ</h4>
    </div>
    <form name="userNameSubmit" method="POST">
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <div class="input-group flatpickr wd-140 mb-2 mb-md-0" id="flatpickr-date">
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
            <div class="input-group wd-150 mb-2 mb-md-0 ms-2">
                <select multiple name="cat_ids[]" id="cat_ids" placeholder="کیٹیگری"
                        class="virtual-select bg-transparent">
                    <?php $cats = fetch('cats');
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        if (in_array($cat['id'], $cat_ids)) {
                            $c_selected = 'selected';
                        } else {
                            $c_selected = '';
                        }
                        echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['c_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <?php if (Administrator()) { ?>
                <div class="input-group wd-180 mb-2 mb-md-0">
                    <label for="branch_id" class="input-group-text input-group-addon bg-transparent urdu">برانچ</label>
                    <select id="branch_id" name="branch_id" class="form-select bg-transparent border-primary">
                        <option hidden value="">برانچ</option>
                        <?php $branches = fetch('branches');
                        while ($branch = mysqli_fetch_assoc($branches)) {
                            if ($branch['id'] == $selectedBranchId) {
                                $selectedBranch = "selected";
                            } else {
                                $selectedBranch = "";
                            }
                            echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php } ?>
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
                <input type="text" class="form-control bg-transparent border-primary ltr" placeholder="میزان"
                       value="<?php echo $mezan; ?>" readonly>
            </div>
            <button type="button" class="btn btn-outline-primary btn-icon-text me-1 mb-2 mb-md-0"
                    onclick="window.print();">
                <i class="btn-icon-prepend" data-feather="printer"></i>پرنٹ
            </button>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                } ?>
                <div class="table-responsive" id="">
                    <table class="table table-bordered overflow-hidden" id="table-1">
                        <thead>
                        <tr>
                            <th>کیٹیگری</th>
                            <th>برانچ</th>
                            <th>کھاتہ نمبر</th>
                            <th>کھاتہ نام</th>
                            <th>کمپنی نام</th>
                            <th>کاروبار نام</th>
                            <th>موبائل نمبر</th>
                            <th>واٹس ایپ نمبر</th>
                            <th>فون نمبر</th>
                            <th>ٹوٹل جمع</th>
                            <th>ٹوٹل بنام</th>
                            <th>بیلنس</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $khaataQ = fetch('khaata');
                        while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                            $k_id = $khaata['id'];
                            if (khaataExits($k_id)) {
                                $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount, r_date, updated_at FROM `roznamchaas` WHERE `khaata_id` = '$k_id' {$whereInnerSql}";
                                $records = mysqli_query($connect, $innerSql);
                                while ($roz = mysqli_fetch_assoc($records)) {
                                    if ($roz['bnaam_amount'] > 0 || $roz['jmaa_amount'] > 0) {
                                        ?>
                                        <tr class="text-nowrap">
                                            <td class="smal-2"><?php echo getTableDataByIdAndColName('cats', $khaata['cat_id'], 'c_name'); ?></td>
                                            <td class="small-2"><?php echo getTableDataByIdAndColName('branches', $khaata['branch_id'], 'b_name'); ?></td>
                                            <td><?php echo $khaata['khaata_no']; ?></td>
                                            <td class="small-2"><?php echo $khaata['khaata_name']; ?></td>
                                            <td class="small-2"><?php echo $khaata['comp_name']; ?></td>
                                            <td class="small-2"><?php echo $khaata['business_name']; ?></td>
                                            <td class="small-2 ltr"><?php echo $khaata['mobile']; ?></td>
                                            <td class="small-2 ltr"><?php echo $khaata['whatsapp']; ?></td>
                                            <td class="small-2 ltr"><?php echo $khaata['phone']; ?></td>
                                            <td><?php echo $roz['jmaa_amount']; ?></td>
                                            <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                                            <?php $balance = $roz['jmaa_amount'] - $roz['bnaam_amount']; ?>
                                            <td class="<?php echo ($balance>0)?'text-success':'text-danger';?> ltr">
                                                <?php echo $roz['jmaa_amount'] - $roz['bnaam_amount']; ?>
                                            </td>
                                        </tr>
                                    <?php }
                                }
                            }
                        } ?>
                        </tbody>
                    </table>
                    <table class="table table-bordered overflow-hidden" id="table-header-fixed"></table>
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
        $('#r_date_start, #r_date_end, #branch_id, #cat_ids').change(function () {
            document.userNameSubmit.submit();
        });
    });
</script>
