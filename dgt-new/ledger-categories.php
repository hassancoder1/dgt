<?php $page_title = 'Ledger Cats.';
include("header.php");
$pageURL = 'ledger-categories';
$removeFilter = $start_date_print = $end_date_print = $in  = $dr_cr ="";
$cat_ids = array();
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$jmaaBnaamArrayVals = array('dr' => 'Dr.', 'cr' => 'Cr.');
$isJB = false;
$sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id > 0 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $start_date_print = $start_date;
        $end_date_print = $end_date;
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date' ";
    }
    if (isset($_GET['cat_ids']) && !empty($_GET['cat_ids'][0])) {
        $cat_ids = $_GET['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $sql .= " AND cat_id IN " . $in;
    }
    if (!empty($_GET['branch_id'])) {
        $postBranchId = $_GET['branch_id'];
        if ($postBranchId > 0) {
            $sql .= " AND khaata_branch_id = " . "'$postBranchId'" . " ";
            $selectedBranchId = $postBranchId;
        }
    }
    if (!empty($_GET['dr_cr'])) {
        $isJB = true;
        $dr_cr = $_GET['dr_cr'];
    }
} ?>
<style>
    .vscomp-toggle-button {
        border-radius: 0;
        padding: 4px 8px;
    }
</style>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex gap-md-2">
            <div class="lh-1"><b>Dr. </b><span id="dr_total_span"></span><br><b>Cr. </b><span
                        id="cr_total_span"></span></div>
            <div class="lh-1"><b>Bal. </b><span id="bal_span"></span><br><b>Rows </b><span
                        id="rows_span"></span>
            </div>
        </div>
        <form name="datesSubmit" method="get">
            <div class="input-group input-group-sm">
                <input id="r_date_start" name="r_date_start" type="date" value="<?php echo $start_date; ?>"
                       class="form-control">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="date"
                       class="form-control">
                <select id="branch_id" name="branch_id" class="form-select">
                    <option value="0" <?php echo ($selectedBranchId == 0) ? 'selected' : ''; ?>>All</option>
                    <?php $branches = fetch('branches');
                    while ($branch = mysqli_fetch_assoc($branches)) {
                        $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                        echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_code'] . '</option>';
                    } ?>
                </select>
                <select multiple name="cat_ids[]" id="cat_ids" class="v-select p-0 form-control form-control-sm">
                    <?php $cats = fetch('cats');
                    while ($cat = mysqli_fetch_assoc($cats)) {
                        $c_selected = in_array($cat['id'], $cat_ids) ? 'selected' : '';
                        echo '<option ' . $c_selected . ' value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                    } ?>
                </select>
                <select id="dr_cr" name="dr_cr" class="form-select">
                    <option hidden value="" selected>Dr./Cr.?</option>
                    <?php foreach ($jmaaBnaamArrayVals as $arrayVal => $val) {
                        $jb_selected = $arrayVal == $dr_cr ? 'selected' : '';
                        echo '<option ' . $jb_selected . ' value="' . $arrayVal . '">' . $val . '</option>';
                    } ?>
                </select>
                <?php echo $removeFilter; ?>
                <?php echo searchInput('1', 'form-control form-control-sm '); ?>
            </div>
        </form>
        <div class="d-flex gap-1">
            <form action="print/ledger-categories" method="get" id="printLedgerAllCategoriesForm" target="_blank">
                <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                <input type="hidden" name="start_date" value="<?php echo $start_date_print; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date_print; ?>">
                <input type="hidden" name="cat_ids" value="<?php echo $in; ?>">
                <input type="hidden" name="branch_id" value="<?php echo $selectedBranchId; ?>">
                <input type="hidden" name="dr_cr" value="<?php echo $dr_cr; ?>">
                <div class="input-group input-group-sm">
                    <select class="form-select" name="print_type">
                        <?php $arr = array('General' => 'sm', 'Full' => 'lg');
                        foreach ($arr as $index => $value) {
                            echo '<option  value="' . $value . '">' . $index . '</option>';
                        } ?>
                    </select>
                    <button type="submit" class="btn btn-sm btn-dark"><i class="fa fa-print"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 ">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr class="text-uppercase">
                            <th>#</th>
                            <th>Type</th>
                            <th>Branch</th>
                            <th>Account</th>
                            <th>Contacts</th>
                            <th>Date</th>
                            <?php if (!$isJB) {
                                echo '<th>Dr.</th><th>Cr.</th>';
                            } ?>
                            <th>Bal</th>
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
                            $dr = roznamchaAmount($k_id, 'dr');
                            $dr_total += $dr;
                            $cr = roznamchaAmount($k_id, 'cr');
                            $cr_total += $cr;
                            $balance = $dr - $cr;

                            if ($balance == 0) continue;

                            if ($isJB) {
                                if ($dr_cr == "dr") {
                                    if ($balance < 0) continue;
                                } else {
                                    if ($balance > 0) continue;
                                }
                            }
                            $balance_total = $dr_total - $cr_total;
                            $rows++;
                            $redGreenText = $balance > 0 ? 'text-success' : 'text-danger'; ?>
                            <tr class="text-nowrap <?php //echo $redGreenText; ?>">
                                <td><?php echo $number; ?></td>
                                <td>
                                    <?php //echo '<b>G A/c.#</b>' . $khaataDatum['id'].'<br>';
                                    echo badge(strtoupper($khaataDatum['acc_for']), 'dark'); ?>
                                </td>
                                <td><?php echo branchName($khaataDatum['branch_id']) . '<br>';
                                    echo '<b>CAT.</b>' . catName($khaataDatum['cat_id']); ?>
                                </td>
                                <td class="font-size-11 text-wrap">
                                    <a class="text-dark" href="<?php echo 'ledger?back-khaata-no=' . $k_no; ?>"
                                       target="_blank">
                                        <?php echo '<b>A/c# </b>' . $k_no . '<br>';
                                        echo '<b>A/c Name </b>' . $khaataDatum['khaata_name']; ?>
                                    </a>
                                </td>
                                <td><?php echo '<b>P.</b>' . $khaataDatum['phone'] . '<br>';
                                    echo '<b>E.</b>' . $khaataDatum['email']; ?>
                                </td>
                                <td><?php $dd = mysqli_query($connect, "select min(r_date) as min_date ,max(r_date) as max_date from `roznamchaas` WHERE khaata_no = '$k_no'");
                                    $dataa = mysqli_fetch_assoc($dd);
                                    echo '<b>Start </b>' . my_date($dataa['min_date']) . '<br><b>Last </b>' . my_date($dataa['max_date']); ?>
                                </td>
                                <?php if (!$isJB) {
                                    echo '<td>' . round($dr) . '</td>';
                                    echo '<td><span class="text-danger">' . round($cr) . '</span></td>';
                                } ?>
                                <td><?php echo '<span class="fw-bold ' . $redGreenText . '">' . round($balance) . '</span>'; ?></td>
                            </tr>
                            <?php $number++;
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="rows" value="<?php echo $rows; ?>">
                    <input type="hidden" id="dr_total" value="<?php echo round($dr_total, 2); ?>">
                    <input type="hidden" id="cr_total" value="<?php echo round($cr_total, 2); ?>">
                    <input type="hidden" id="cr_balance" value="<?php echo round($balance_total, 2); ?>">
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
    $("#bal_span").text($("#cr_balance").val());
    /*$("#jmaaTotalPrint").val($("#dr_total").val());
    $("#bnaamTotalPrint").val($("#cr_total").val());
    $("#mezanPrint").val($("#cr_balance").val());*/
</script>
<script type="text/javascript">
    $(function () {
        $('#r_date_start, #r_date_end,#branch_id, #cat_ids,#dr_cr').change(function () {
            document.datesSubmit.submit();
        });
        /*$('').change(function () {
            document.drCrSubmit.submit();
        });*/
    });
</script>