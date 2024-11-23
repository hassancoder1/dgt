<?php include("header.php");
$pageURL = 'ledger-nill';
global $connect;
$cat_ids = array();
$selectedBranch = $removeFilter = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT DISTINCT khaata_no,khaata_id, cat_id, khaata_branch_id FROM `roznamchaas` WHERE r_id > 0 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['cat_ids']) && !empty($_GET['cat_ids'][0])) {
        $cat_ids = $_GET['cat_ids'];


        $cat_ids = explode(',', $cat_ids[0]);
        //$cat_ids = explode(',', $cat_ids);
        $in = "(" . implode(',', $cat_ids) . ")";
        $sql .= " AND cat_id IN " . $in . " ";
    }
    if (!empty($_GET['branch_id'])) {
        $selectedBranchId = mysqli_real_escape_string($connect, $_GET['branch_id']);
        if ($selectedBranchId > 0) {
            $sql .= " AND khaata_branch_id = '$selectedBranchId'";
        }

    }
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        //$date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
} else {
    $sql .= " AND r_date = '$start_date'";
} ?>
<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">نل کھاتہ</h4>
    </div>
    <div class="d-flex gap-md-4 urdu">
        <div>تعداد انٹری <span id="rows_span" class="bold underline"></span></div>
        <div class="mx-3">کل جمع <span id="dr_total_span" class="bold underline"></span></div>
        <div>کل بنام <span id="cr_total_span" class="bold underline"></span></div>
        <div>میزان <span id="mezan_span" class="bold underline"></span></div>
        <?php echo '<div>' .  $removeFilter . '</div>'; ?>
    </div>
    <form name="datesSubmit" method="get" class="d-flex">
        <div class="urdu d-flex align-items-center wd-md-120 me-2">
            <?php echo searchInput('a'); ?>
        </div>
        <div class="input-group flatpickr wd-110" id="flatpickr-date">
            <input id="r_date_start" name="r_date_start" value="<?php echo $start_date; ?>" type="text"
                   class="form-control" placeholder="تاریخ ابتداء" data-input>
            <label for="r_date_start" class="input-group-text urdu">سے</label>
        </div>
        <div class="flatpickr wd-80" id="flatpickr-date">
            <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="text"
                   class="form-control" placeholder="تاریخ انتہاء" data-input>
        </div>
        <div class="input-group wd-150 mx-2">
            <select multiple name="cat_ids[]" id="cat_ids" placeholder="کیٹیگری" class="virtual-select">
                <?php $cats = fetch('cats');
                while ($cat = mysqli_fetch_assoc($cats)) {
                    $c_selected = in_array($cat['id'], $cat_ids) ? 'selected' : '';
                    echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['c_name'] . '</option>';
                } ?>
            </select>
        </div>
        <?php if (Administrator()) { ?>
            <div class="input-group wd-180">
                <label for="branch_id" class="input-group-text urdu">برانچ</label>
                <select id="branch_id" name="branch_id" class="form-select">
                    <option value="">آل برانچ</option>
                    <?php $branches = fetch('branches');
                    while ($branch = mysqli_fetch_assoc($branches)) {
                        $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                        echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                    } ?>
                </select>
            </div>
        <?php } ?>
    </form>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered " id="fix-head-table">
                    <thead>
                    <tr>
                        <th>تاریخ ابتداء</th>
                        <th>تاریخ انتہاء</th>
                        <th>کیٹیگری</th>
                        <th>برانچ</th>
                        <th>کھاتہ نمبر</th>
                        <th>کھاتہ نام</th>
                        <th>کمپنی نام</th>
                        <th>کاروبار نام</th>
                        <th>موبائل نمبر</th>
                        <th>ٹوٹل جمع</th>
                        <th>ٹوٹل بنام</th>
                        <th>بیلنس</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $khaataQ = mysqli_query($connect, $sql);
                    $rows = $balanceNill = $jmaaTotal = $bnaamTotal = $mezan = 0;
                    while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                        $k_no = $khaata['khaata_no'];
                        $k_id = $khaata['khaata_id'];
                        $khaataData = mysqli_query($connect, "SELECT * FROM khaata WHERE id = '$k_id'");
                        $khaataDatum = mysqli_fetch_assoc($khaataData);
                        $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                        $records = mysqli_query($connect, $innerSql);
                        $jb = mysqli_fetch_assoc($records);
                        $datesSql = "SELECT MIN(r_date) AS first_date, MAX(r_date) AS last_date FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                        $datesQ = mysqli_query($connect, $datesSql);
                        $dates = mysqli_fetch_assoc($datesQ);

                        $balanceNill = $jb['jmaa_amount'] - $jb['bnaam_amount'];
                        if ($balanceNill == 0) { ?>
                            <tr>
                                <td><?php echo $dates['first_date']; ?></td>
                                <td><?php echo $dates['last_date']; ?></td>
                                <td class="smal-2"><?php echo getTableDataByIdAndColName('cats', $khaataDatum['cat_id'], 'c_name'); ?></td>
                                <td><?php echo getTableDataByIdAndColName('branches', $khaata['khaata_branch_id'], 'b_name'); ?></td>
                                <td><?php echo $k_no; ?></td>
                                <td><?php echo $khaataDatum['khaata_name']; ?></td>
                                <td><?php echo $khaataDatum['comp_name']; ?></td>
                                <td><?php echo $khaataDatum['business_name']; ?></td>
                                <td class="ltr"><?php echo $khaataDatum['mobile']; ?></td>
                                <td><?php echo $jb['jmaa_amount']; ?></td>
                                <td class="text-danger"><?php echo $jb['bnaam_amount']; ?></td>
                                <td class="text-success ltr"><?php echo $balanceNill; ?></td>
                            </tr>
                            <?php $rows++;
                            $jmaaTotal += $jb['jmaa_amount'];
                            $bnaamTotal += $jb['bnaam_amount'];
                        }
                    }
                    $mezan = $jmaaTotal - $bnaamTotal; ?>
                    </tbody>
                </table>
                <input type="hidden" id="rows" value="<?php echo $rows; ?>">
                <input type="hidden" id="dr_total" value="<?php echo $jmaaTotal; ?>">
                <input type="hidden" id="cr_total" value="<?php echo $bnaamTotal; ?>">
                <input type="hidden" id="mezan" value="<?php echo $mezan; ?>">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_span").text($("#rows").val());
    $("#dr_total_span").text($("#dr_total").val());
    $("#cr_total_span").text($("#cr_total").val());
    $("#mezan_span").text($("#mezan").val());
</script>
<script type="text/javascript">
    $(function () {
        $('#r_date_start, #r_date_end,#branch_id, #cat_ids').change(function () {
            document.datesSubmit.submit();
        });
    });
</script>
