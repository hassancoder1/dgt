<?php include("header.php"); ?>
<?php $cat_ids = array();
$c_selected = $cat_append = $selectedBranch = $branch_append = $date_append = $removeFilter = $date_msg = $cat_msg = $branch_msg = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="ledger-ograhi-form"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
    if (isset($_POST['cat_ids']) && !empty($_POST['cat_ids'][0])) {
        $date_append = "";
        $cat_ids = $_POST['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $cat_append = " AND cat_id IN " . $in;
        if (!empty($_POST['branch_id'])) {
            $postBranchId = $_POST['branch_id'];
            $selectedBranchId = $postBranchId;
            if ($postBranchId > 0) {
                $branch_append = " AND khaata_branch_id = " . "'$postBranchId'" . " ";
            }
        }
        $cat_msg = 'Categories: ';
        foreach ($cat_ids as $cc) {
            $cat_msg .= getTableDataByIdAndColName('cats', $cc, 'c_name') . ' ';
        }
    } elseif (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
        $date_append = "";
        $postBranchId = $_POST['branch_id'];
        $selectedBranchId = $postBranchId;
        if ($postBranchId > 0) {
            $branch_append = " AND khaata_branch_id = " . "'$postBranchId'" . " ";
        }
        if (isset($_POST['cat_ids']) && !empty($_POST['cat_ids'][0])) {
            $cat_ids = $_POST['cat_ids'];
            $cat_ids = explode(',', $cat_ids[0]);
            $in = "(" . implode(',', $cat_ids) . ")";
            $cat_append = " AND cat_id IN " . $in;
            $cat_msg = 'Categories: ';
            foreach ($cat_ids as $cc) {
                $cat_msg .= getTableDataByIdAndColName('cats', $cc, 'c_name') . ' ';
            }
        }
    } elseif (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $cat_append = $branch_append = "";
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $date_append = " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
} else {
}
$sql = "SELECT DISTINCT khaata_no,khaata_id, cat_id, khaata_branch_id FROM `roznamchaas` WHERE r_id != 0 {$date_append} {$branch_append} {$cat_append}";
//echo $sql;
$sqlStats = "SELECT * FROM `roznamchaas` WHERE r_id != 0 {$date_append} {$branch_append} {$cat_append}";
$recordStats = mysqli_query($connect, $sqlStats);
$bnaamTotal = $jmaaTotal = $mezan = 0;
while ($stat = mysqli_fetch_assoc($recordStats)) {
    $bnaamTotal += $stat['bnaam_amount'];
    $jmaaTotal += $stat['jmaa_amount'];
}
$mezan = $jmaaTotal - $bnaamTotal; ?>
<div class="filter-div">
    <?php echo $date_msg;
    echo '<span class="badge bg-primary pt-2">' . $cat_msg . '</span>';
    if ($selectedBranchId > 0) {
        echo '<span class="badge bg-dark urdu">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
    } elseif ($selectedBranchId == 0) {
        echo '<span class="badge bg-dark urdu">آل برانچ</span>';
    } else {
    }
    echo $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-2 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">اوگران کھاتہ فارم</h4>
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
        </form>
        <form name="userNameSubmit" method="POST" class="d-flex">
            <div class="input-group wd-160 mb-2 mb-md-0 ms-2">
                <select multiple name="cat_ids[]" id="cat_ids" placeholder="کیٹیگری"
                        class="virtual-select bg-transparent">
                    <?php $cats = fetch('cats');
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        $c_selected = in_array($cat['id'], $cat_ids) ? 'selected' : '';
                        echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['c_name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <?php if (Administrator()) { ?>
                <div class="input-group wd-160 mb-2 mb-md-0">
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
        <div class="input-group wd-180 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" placeholder="کل بنام"
                   value="<?php echo $bnaamTotal; ?>" readonly>
        </div>
        <button type="button" class="btn btn-outline-primary btn-icon-text me-1 mb-2 mb-md-0"
                onclick="window.print();">
            <i class="btn-icon-prepend" data-feather="printer"></i>پرنٹ
        </button>
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
                    <table class="table table-bordered " id="fix-head-table">
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
                            <th>دن</th>
                            <th>ٹوٹل بنام</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $khaataQ = mysqli_query($connect, $sql);
                        $jmaaTotalLast = 0;
                        $bnaamTotalLast = 0;
                        while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                            $k_no = $khaata['khaata_no'];
                            $k_id = $khaata['khaata_id'];
                            $khaataData = mysqli_query($connect, "SELECT * FROM khaata WHERE id = '$k_id'");
                            $khaataDatum = mysqli_fetch_assoc($khaataData);
                            $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                            $records = mysqli_query($connect, $innerSql);
                            $jb = mysqli_fetch_assoc($records);
                            $lastDateQ = mysqli_query($connect, "SELECT MAX(r_date) as maxData FROM `roznamchaas` WHERE khaata_id = '$k_id' AND jmaa_amount= 0");
                            $lastDate = mysqli_fetch_assoc($lastDateQ);
                            if ($jb['bnaam_amount'] > 0) {
                                ?>
                                <tr class="text-nowrap">
                                    <td class="smal-2"><?php echo getTableDataByIdAndColName('cats', $khaataDatum['cat_id'], 'c_name'); ?></td>
                                    <td class="small-2"><?php echo getTableDataByIdAndColName('branches', $khaata['khaata_branch_id'], 'b_name'); ?></td>
                                    <td><?php echo $k_no; ?></td>
                                    <td class="small-2"><?php echo $khaataDatum['khaata_name']; ?></td>
                                    <td class="small-2"><?php echo $khaataDatum['comp_name']; ?></td>
                                    <td class="small-2"><?php echo $khaataDatum['business_name']; ?></td>
                                    <td class="small-2 ltr"><?php echo $khaataDatum['mobile']; ?></td>
                                    <td class="small-2 ltr"><?php echo $khaataDatum['whatsapp']; ?></td>
                                    <td class="small-2 ltr"><?php echo $khaataDatum['phone']; ?></td>
                                    <td>
                                        <?php
                                        $now = time();
                                        $your_date = strtotime($lastDate['maxData']);
                                        $datediff = $now - $your_date;
                                        echo round($datediff / (60 * 60 * 24));
                                        echo '<span class="ms-1 small"> دن سے بنام ہے</span>';
                                        ?>
                                    </td>
                                    <td class="text-danger"><?php echo $jb['bnaam_amount'];
                                        $bnaamTotalLast += $jb['bnaam_amount']; ?></td>
                                </tr>
                            <?php }
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
            document.userNameSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $(function () {
        $('#r_date_start, #r_date_end').change(function () {
            document.datesSubmit.submit();
        });
        $('#branch_id, #cat_ids').change(function () {
            document.userNameSubmit.submit();
        });
    });
</script>
