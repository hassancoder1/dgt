<?php include("header.php"); ?>
<?php $c_selected = $selectedBranch = $removeFilter = $date_msg = $cat_msg = $branch_msg = $jb_msg = $start_date_print = $end_date_print = $in = "";
$selectedBranchId = $jbval = 0;
$cat_ids = array();
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id != 0 ";
if ($_POST) {
    $removeFilter = removeFilter('ledger-sargrarmi-more-than-1-month');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $start_date_print = $start_date;
        $end_date_print = $end_date;
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['cat_ids']) && !empty($_POST['cat_ids'][0])) {
        $cat_ids = $_POST['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $sql .= " AND cat_id IN " . $in;
        $cat_msg = 'کیٹگری: ';
        foreach ($cat_ids as $cc) {
            $cat_msg .= ' ' . getTableDataByIdAndColName('cats', $cc, 'c_name') . ' | ';
        }
        $cat_msg = '<span class="badge bg-primary pt-1 urdu ms-1">' . $cat_msg . '</span>';
    }
    if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
        $postBranchId = $_POST['branch_id'];
        $selectedBranchId = $postBranchId;
        if ($postBranchId > 0) {
            $sql .= " AND khaata_branch_id = " . "'$postBranchId'" . " ";
            $branch_msg = '<span class="badge bg-dark urdu ms-1">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
        }
    }
}
//$sql = "SELECT DISTINCT khaata_no,khaata_id, cat_id, khaata_branch_id FROM `roznamchaas` WHERE r_id != 0 {$date_append} {$branch_append} {$cat_append}";
//echo $sql;
?>
<div class="filter-div">
    <?php echo $date_msg . $cat_msg . $branch_msg . $jb_msg . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">سرگرمی 1 ماہ سے زیادہ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-110 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu ps-0">سے</label>
            </div>
            <div class="flatpickr wd-80 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
            <div class="input-group wd-110 mb-2 mb-md-0 ms-1">
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
                <div class="input-group wd-130 mb-2 mb-md-0">
                    <label for="branch_id"
                           class="input-group-text  ps-0 input-group-addon bg-transparent urdu ps-0">برانچ</label>
                    <select id="branch_id" name="branch_id" class="form-select bg-transparent border-primary">
                        <option hidden value="">انتخاب</option>
                        <option value="0">آل برانچ</option>
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
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل جمع</span>
            <input type="text" class="form-control bg-transparent border-primary"
                   id="kulJmaaInput" readonly>
        </div>
        <div class="input-group wd-150 mb-2 mb-md-0">
            <span class="input-group-text input-group-addon bg-transparent urdu">کل بنام</span>
            <input type="text" class="form-control bg-transparent border-primary" id="kulBnaamInput" readonly>
        </div>
        <div class="input-group wd-120 mb-2 mb-md-0 me-2">
            <span class="input-group-text input-group-addon bg-transparent urdu">میزان</span>
            <input type="text" class="form-control bg-transparent border-primary ltr" id="mezanInput" readonly>
        </div>
        <form action="print/ledger-sargrarmi-more-than-1-month.php" method="post" target="_blank">
            <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
            <input type="hidden" name="start_date" value="<?php echo $start_date_print; ?>">
            <input type="hidden" name="end_date" value="<?php echo $end_date_print; ?>">
            <input type="hidden" name="cat_ids" value="<?php echo $in; ?>">
            <input type="hidden" name="branch_id" value="<?php echo $selectedBranchId; ?>">
            <input type="hidden" id="jmaaTotalPrint" name="jmaaTotalPrint">
            <input type="hidden" id="bnaamTotalPrint" name="bnaamTotalPrint">
            <input type="hidden" id="mezanPrint" name="mezanPrint">
            <button id="khaata_print_btn" name="printLedgerSubmit" type="submit"
                    class="btn btn-primary btn-icon-text  pt-0  btn-sm">
                <i class="btn-icon-prepend mx-0" data-feather="printer"></i>
            </button>
        </form>
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
                <style>
                    #fix-head-table tr th{
                        font-size: 12px;
                    }
                </style>
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th width="7%">تاریخ</th>
                            <th width="4%" class="">کیٹیگری</th>
                            <th width="7%">برانچ</th>
                            <th width="8%">کھاتہ نمبر</th>
                            <th width="13%">کھاتہ نام</th>
                            <th width="14%">کمپنی نام</th>
                            <th width="9%">کاروبار نام</th>
                            <th width="9%">موبائل نمبر</th>
                            <th width="7%">ٹوٹل جمع</th>
                            <th width="7%">ٹوٹل بنام</th>
                            <th width="8%">دن</th>
                            <th width="7%">بیلنس</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $khaataQ = mysqli_query($connect, $sql);
                        $jmaaTotalLast = $bnaamTotalLast = $balance = $noOfDays = 0;
                        if (mysqli_num_rows($khaataQ) > 0) {
                            while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                                //$k_no = $khaata['khaata_no'];
                                $k_id = $khaata['khaata_id'];
                                $khaataData = mysqli_query($connect, "SELECT * FROM khaata WHERE id = '$k_id'");
                                $khaataDatum = mysqli_fetch_assoc($khaataData);
                                $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                                $records = mysqli_query($connect, $innerSql);
                                $jb = mysqli_fetch_assoc($records);

                                //$datesSql = "SELECT * FROM `roznamchaas` WHERE `khaata_id` = '$k_id' AND r_date > date_sub(date_sub(now(), INTERVAL 1 MONTH),INTERVAL 0 DAY)";
                                $datesSql = "SELECT MAX(r_date) as last_old_date FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                                $datesQ = mysqli_query($connect, $datesSql);
                                $dates = mysqli_fetch_assoc($datesQ);
                                $last_old_date = $dates['last_old_date'];
                                //echo $last_old_date;
                                $origin = date_create($last_old_date);
                                $target = date_create(date('Y-m-d'));
                                $interval = date_diff($origin, $target);
                                $noOfDays = $interval->format('%a');
                                $balance = $jb['jmaa_amount'] - $jb['bnaam_amount'];
                                $jmaaTotalLast += $jb['jmaa_amount'];
                                $bnaamTotalLast += $jb['bnaam_amount'];
                                if ($noOfDays > 29) {
                                    ?>
                                    <tr>
                                        <td class="small-2"><?php echo $last_old_date; ?></td>
                                        <td class="small-2"><?php echo getTableDataByIdAndColName('cats', $khaataDatum['cat_id'], 'c_name'); ?></td>
                                        <td class="small-3"><?php echo branchName($khaataDatum['branch_id']); ?></td>
                                        <td>
                                            <a href="ledger-form?back-khaata-no=<?php echo $khaataDatum['khaata_no']; ?>" target="_blank"><?php echo $khaataDatum['khaata_no']; ?></a>
                                        </td>
                                        <td class="small-3"><?php echo $khaataDatum['khaata_name']; ?></td>
                                        <td class="small-3"><?php echo $khaataDatum['comp_name']; ?></td>
                                        <td class="small-3"><?php echo $khaataDatum['business_name']; ?></td>
                                        <td class="small-2 ltr"><?php echo $khaataDatum['mobile']; ?></td>
                                        <td><?php echo $jb['jmaa_amount']; ?></td>
                                        <td class="text-danger"><?php echo $jb['bnaam_amount']; ?></td>
                                        <td class=""><?php echo $noOfDays;
                                            echo '<span class="ms-1 small-3"> دن سے بنام </span>'; ?></td>
                                        <td class="<?php echo ($balance > 0) ? 'text-success' : 'text-danger'; ?> ltr">
                                            <?php echo $balance; ?>
                                        </td>
                                    </tr>
                                <?php }
                            }
                        } else {
                            echo '<tr class="text-center"><th colspan="12"> کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                        } ?>
                        <tr class="d-none">
                            <td colspan="9"></td>
                            <td><input type="hidden" id="jmaaTotalLast" value="<?php echo $jmaaTotalLast; ?>"></td>
                            <td><input type="hidden" id="bnaamTotalLast" value="<?php echo $bnaamTotalLast; ?>"></td>
                            <td><input type="hidden" id="mezanLast"
                                       value="<?php echo $jmaaTotalLast - $bnaamTotalLast; ?>">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    var jmaaTotalLast = $("#jmaaTotalLast").val();
    var bnaamTotalLast = $("#bnaamTotalLast").val();
    var mezanLast = $("#mezanLast").val();
    $("#kulJmaaInput").val(jmaaTotalLast);
    $("#kulBnaamInput").val(bnaamTotalLast);
    $("#mezanInput").val(mezanLast);
    /*values for print. Top left corner form*/
    $("#jmaaTotalPrint").val(jmaaTotalLast);
    $("#bnaamTotalPrint").val(bnaamTotalLast);
    $("#mezanPrint").val(mezanLast);
</script>
<script type="text/javascript">
    $(function () {
        $('#r_date_start, #r_date_end,#branch_id, #cat_ids,#jmaa_bnaam').change(function () {
            document.datesSubmit.submit();
        });
    });
    $(function () {
        $('input[name=printSizeRadio]').change(function () {
            $('#printLedgerAllCategoriesForm').submit();
        });
    });
</script>
