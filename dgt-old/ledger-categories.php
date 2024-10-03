<?php $page_title = 'Ledger All Categories';
include("header.php"); ?>
<?php $c_selected = $selectedBranch = $removeFilter = $date_msg = $cat_msg = $branch_msg = $jb_msg = $start_date_print = $end_date_print = $in = $jbval = "";
$selectedBranchId = 0;
$cat_ids = array();
$start_date = $end_date = date('Y-m-d');
$jmaaBnaamArrayVals = array('dr' => 'Dr.', 'cr' => 'Cr.');
$isJB = false;
$sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id > 0 ";
if ($_GET) {
    $removeFilter = removeFilter('ledger-categories');
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $start_date_print = $start_date;
        $end_date_print = $end_date;
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-dark">' . $start_date . ' to ' . $end_date . '</span>';
    }
    if (isset($_GET['cat_ids']) && !empty($_GET['cat_ids'][0])) {
        $cat_ids = $_GET['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $sql .= " AND cat_id IN " . $in;
        $cat_msg = 'Category ';
        foreach ($cat_ids as $cc) {
            $cat_msg .= ' | ' . catName($cc) . ' ';
        }
        $cat_msg = '<span class="badge bg-dark">' . $cat_msg . '</span>';
    }
    if (!empty($_GET['branch_id'])) {
        $postBranchId = $_GET['branch_id'];
        $selectedBranchId = $postBranchId;
        if ($postBranchId > 0) {
            $sql .= " AND khaata_branch_id = " . "'$postBranchId'" . " ";
            $branch_msg = '<span class="badge bg-dark">' . branchName($selectedBranchId) . '</span>';
        }
    }
    if (!empty($_GET['dr_cr'])) {
        $isJB = true;
        $jbval = $_GET['dr_cr'];
        if ($jbval == "dr") {
            $jb_msg = '<span class="badge bg-dark">Dr.</span>';
        }
        if ($jbval == "cr") {
            $jb_msg = '<span class="badge bg-dark">Cr.</span>';
        }
    }
} ?>
<style>
    .vscomp-toggle-button {
        border-radius: 0;
        padding: 3px 8px;
    }
</style>
<div class="row">
    <div class="col-lg-12 ">
        <div class="d-flex justify-content-between align-items-center gap-1 text-uppercase small-">
            <div class="d-flex gap-3">
                <div><b>Rows: </b><span id="rows_span"></span></div>
                <div><b>Dr. </b><span id="dr_total_span"></span></div>
                <div><b>Cr. </b><span id="cr_total_span"></span></div>
                <div><b>Balance: </b><span id="cr_balance_span"></span></div>
            </div>
            <div class="">
                <?php echo $date_msg . $cat_msg . $branch_msg . $jb_msg . $removeFilter; ?>
            </div>
            <form action="print/ledger-all-categories" method="get" id="printLedgerAllCategoriesForm" target="_blank"
                  class="table-form d-flex">
                <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                <input type="hidden" name="start_date" value="<?php echo $start_date_print; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date_print; ?>">
                <input type="hidden" name="cat_ids" value="<?php echo $in; ?>">
                <input type="hidden" name="branch_id" value="<?php echo $selectedBranchId; ?>">
                <input type="hidden" name="jbval" value="<?php echo $jbval; ?>">
                <input type="hidden" id="jmaaTotalPrint" name="jmaaTotalPrint">
                <input type="hidden" id="bnaamTotalPrint" name="bnaamTotalPrint">
                <input type="hidden" id="mezanPrint" name="mezanPrint">
                <div class="input-group">
                    <select class="form-select" name="print_type">
                        <?php $arr = array(1 => 'General Print', 2 => 'Checking Print', 3 => 'Ogurai Print');
                        foreach ($arr as $index => $value) {
                            //$selected_ser = $id == $ser['id'] ? 'selected' : '';
                            echo '<option  value="' . $index . '">' . $value . ' &nbsp;&nbsp;&nbsp; </option>';
                        } ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-secondary"><i class="fa fa-print"></i></button>
                </div>
                <!--<div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="printSizeRadio" id="lg" value="lg">
                    <label data-bs-toggle="tooltip" data-bs-original-title="Landscape (Large)" data-bs-placement="left"
                           class="btn btn-success btn-sm mb-0" for="lg"><i class="fa fa-print"></i></label>
                    <input type="radio" class="btn-check" name="printSizeRadio" id="lg" value="lg">
                    <label data-bs-toggle="tooltip" data-bs-original-title="Portrait (Small)" data-bs-placement="left"
                           class="btn btn-primary btn-sm mb-0" for="sm"><i class="fa fa-print"></i></label>
                    <input type="radio" class="btn-check" name="printSizeRadio" id="sm" value="sm">
                </div>-->
            </form>
        </div>
        <div class="table-form d-flex justify-content-between gap-0 justify-content-center">
            <form name="datesSubmit" method="get" class="d-flex w-75">
                <input id="r_date_start" name="r_date_start" type="date" value="<?php echo $start_date; ?>"
                       class="form-control">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="date"
                       class="form-control">
                <select id="branch_id" name="branch_id" class="form-select">
                    <option value="0" <?php echo ($selectedBranchId == 0) ? 'selected' : ''; ?>>All
                        Branch
                    </option>
                    <?php $branches = fetch('branches');
                    while ($branch = mysqli_fetch_assoc($branches)) {
                        $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                        echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                    } ?>
                </select>
                <select multiple name="cat_ids[]" id="cat_ids" placeholder="Category" class="v-select">
                    <?php $cats = fetch('cats');
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        $c_selected = in_array($cat['id'], $cat_ids) ? 'selected' : '';
                        echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                    } ?>
                </select>
            </form>
            <form name="drCrSubmit" method="post" class="d-flex">
                <select id="dr_cr" name="dr_cr" class="form-select" style="width: 200px">
                    <option hidden value="" selected>Dr./Cr.?</option>
                    <?php foreach ($jmaaBnaamArrayVals as $arrayVal => $val) {
                        $jb_selected = $arrayVal == $jbval ? 'selected' : '';
                        echo '<option ' . $jb_selected . ' value="' . $arrayVal . '">' . $val . '</option>';
                    } ?>
                </select>
                <?php echo searchInput(1, 'form-control-sm'); ?>
            </form>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 table-bordered table-sm">
                        <thead>
                        <tr class="text-nowrap text-uppercase">
                            <th>#</th>
                            <th>Type|Branch</th>
                            <th>Account</th>
                            <th>Address</th>
                            <th>Contacts</th>
                            <?php /*if (!$isJB) {
                                echo '<th>Total Dr.</th>';
                                echo '<th>Total Cr.</th>';
                            } */ ?><!--
                            <th>Balance</th>-->
                            <th>Date</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $khaataQ = mysqli_query($connect, $sql);
                        $rows = $dr = $cr = $balance = $dr_total = $cr_total = $balance_total = 0;
                        $number = 1;
                        while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                            $k_id = $khaata['khaata_id'];
                            $khaataDatum = khaataSingle($k_id);
                            $k_no = $khaataDatum['khaata_no'];

                            /*$records_dr = mysqli_query($connect, "SELECT SUM(amount) as amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id' AND dr_cr= 'dr'");
                            $ddd_dr = mysqli_fetch_assoc($records_dr);*/
                            $dr = roznamchaAmount($k_id, 'dr');
                            $dr_total += $dr;
                            $cr = roznamchaAmount($k_id, 'cr');
                            $cr_total += $cr;
                            /*$records_cr = mysqli_query($connect, "SELECT SUM(amount) as amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id' AND dr_cr= 'cr'");
                            $ddd_cr = mysqli_fetch_assoc($records_cr);
                            $cr_total += $ddd_cr['amount'];*/
                            $balance = $dr - $cr;

                            if ($balance == 0) {
                                continue;
                            }
                            if ($isJB) {
                                if ($jbval == "dr") {
                                    if ($balance < 0) {
                                        continue;
                                    }
                                } else {
                                    if ($balance > 0) {
                                        continue;
                                    }
                                }
                            }
                            $balance_total = $dr_total - $cr_total;
                            $rows++;
                            $redGreenText = $balance > 0 ? 'text-success' : 'text-danger'; ?>
                            <tr class="text-nowrap <?php //echo $redGreenText; ?>">
                                <td><?php echo $number; ?></td>
                                <td class="font-size-11">
                                    <?php echo '<span class="badge badge-soft-danger">' . strtoupper($khaataDatum['acc_for']) . '</span><br>';
                                    echo '<b>G A/c.#</b>' . $khaataDatum['id'];
                                    echo '<br><b>B.</b>' . branchName($khaataDatum['branch_id']) . '<br>';
                                    echo '<b>CAT.</b>' . catName($khaataDatum['cat_id']); ?>
                                </td>
                                <td class="font-size-11 text-wrap">
                                    <a class="text-dark" href="<?php echo 'ledger?back-khaata-no=' . $k_no; ?>"
                                       target="_blank">
                                        <?php echo '<b>A/c#</b>' . $k_no . '<br>';
                                        echo '<b>A/c Name</b>' . $khaataDatum['khaata_name'] . '<br>';
                                        echo '<b>Company</b>' . $khaataDatum['comp_name'] . '<br>';
                                        echo '<b>Owner</b>' . $khaataDatum['owner_name']; ?>
                                    </a>
                                </td>
                                <td class="font-size-11 text-wrap">
                                    <?php
                                    echo '<b>Country</b>' . countryName($khaataDatum['country_id']);
                                    echo '<br><b>City</b>' . $khaataDatum['city'];
                                    echo '<br><b>Address</b>' . $khaataDatum['address'];
                                    ?>
                                </td>
                                <td class="font-size-11">
                                    <?php $details = ['indexes' => $khaataDatum['indexes'], 'vals' => $khaataDatum['vals']];
                                    echo displayKhaataDetails($details); ?>
                                </td>
                                <td>
                                    <?php $dd = mysqli_query($connect,
                                        "select min(r_date) as min_date ,max(r_date) as max_date from `roznamchaas` WHERE khaata_no = '$k_no'");
                                    $dataa = mysqli_fetch_assoc($dd);
                                    echo 'Start: ' . my_date($dataa['min_date']) . '<br>';
                                    echo 'Last: ' . my_date($dataa['max_date']);
                                    ?>
                                </td>
                                <td>
                                    <?php if (!$isJB) {
                                        echo 'Dr. ' . round($dr) . '<br>';
                                        echo '<span class="text-danger">Cr. ' . round($cr) . '</span><br>';
                                    }
                                    echo '<span class=" ' . $redGreenText . '">Bal. ' . round($balance) . '</span>'; ?>
                                </td>
                            </tr>
                            <?php $number++;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="rows" value="<?php echo $rows; ?>">
                    <input type="hidden" id="dr_total" value="<?php echo round($dr_total,2); ?>">
                    <input type="hidden" id="cr_total" value="<?php echo round($cr_total,2); ?>">
                    <input type="hidden" id="cr_balance" value="<?php echo round($balance_total,2); ?>">
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_span").text($("#rows").val());
    $("#dr_total_span").text($("#dr_total").val());
    $("#cr_total_span").text($("#cr_total").val());
    $("#cr_balance_span").text($("#cr_balance").val());
    $("#jmaaTotalPrint").val($("#dr_total").val());
    $("#bnaamTotalPrint").val($("#cr_total").val());
    $("#mezanPrint").val($("#cr_balance").val());
</script>
<script type="text/javascript">
    $(function () {
        $('#r_date_start, #r_date_end,#branch_id, #cat_ids').change(function () {
            document.datesSubmit.submit();
        });
        $('#dr_cr').change(function () {
            document.drCrSubmit.submit();
        });
    });
</script>
<script>
    $('input[name=printSizeRadio]').change(function () {
        //document.printLedgerAllCategoriesForm.submit();
        $('#printLedgerAllCategoriesForm').submit();
    });
</script>
