<?php $page_title = 'Bank Payments';
include("header.php");
$pageURL = 'roznamcha-banks';
$r_type = $searchUserName = $selectedBranch = $removeFilter = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_type= 'Bank' ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND r_date_payment BETWEEN '$start_date' AND '$end_date'";
        $pageURL .= '?r_date_start=' . $start_date . '&r_date_end=' . $end_date;
    }
    if (!empty($_GET['username'])) {
        $searchUserName = mysqli_real_escape_string($connect, $_GET['username']);
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        $pageURL .= '&username=' . $searchUserName;
    }
    /*if (!empty($_GET['r_type'])) {
        $r_type = mysqli_real_escape_string($connect,$_GET['r_type']);
        $sql .= "AND r_type = " . "'$r_type'" . " ";
        $pageURL .= '&r_type=' . $r_type;
    }*/
    if (!empty($_GET['branch_id'])) {
        $postBranchId = $_GET['branch_id'];
        if ($postBranchId > 0) {
            $sql .= "AND branch_id = " . "'$postBranchId'" . " ";
            $selectedBranchId = $postBranchId;
        }
        $pageURL .= '&branch_id=' . $postBranchId;
    }
} else {
    $sql .= " AND r_date = '$start_date'";
}
//echo $sql;?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div class="d-flex gap-md-2">
            <div class="lh-1"><b>Dr. </b><span id="dr_total_span"></span><br>
                <b>Cr. </b><span id="cr_total_span"></span></div>
            <div class="lh-1"><b>Bal. </b><span id="bal_span"></span><br>
                <b>Rows </b><span id="rows_span"></span></div>
        </div>
        <form name="datesSubmit" method="get">
            <div class="input-group input-group-sm">
                <input id="r_date_start" name="r_date_start" type="date"
                       value="<?php echo $start_date; ?>" class="form-control">
                <label for="r_date_end" class="d-none">To</label>
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="date"
                       class="form-control">
                <select id="branch_id" name="branch_id" class="form-select">
                    <option value="0" <?php echo ($selectedBranchId == 0) ? 'selected' : ''; ?>>All
                        Branches
                    </option>
                    <?php $branches = fetch('branches');
                    while ($branch = mysqli_fetch_assoc($branches)) {
                        $selectedBranch = $branch['id'] == $selectedBranchId ? "selected" : "";
                        echo '<option ' . $selectedBranch . ' value="' . $branch['id'] . '">' . $branch['b_code'] . '</option>';
                    } ?>
                </select>
                <input type="text" id="username" name="username" class="form-control "
                       placeholder="Search user ID" value="<?php echo $searchUserName; ?>">
                <?php echo $removeFilter; ?>
            </div>
        </form>
        <div class="d-flex gap-1">
            <?php echo searchInput('1', 'form-control form-control-sm '); ?>
            <?php //echo addNew('roznamcha-add', '', 'btn-sm'); ?>
            <form action="print/roznamcha-full" method="post" target="_blank" class="d-none">
                <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
                <input name="r_type" value="Business" type="hidden">
                <input name="r_date_start" value="<?php echo $start_date; ?>" type="hidden">
                <input name="r_date_end" value="<?php echo $end_date; ?>" type="hidden">
                <input name="branch_id" value="<?php echo $selectedBranchId; ?>" type="hidden">
                <input name="username" value="<?php echo $searchUserName; ?>" type="hidden">
                <input name="url" value="roznamcha" type="hidden">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa fa-print"></i>
                </button>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="co-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm- table-hover">
                    <thead>
                    <tr>
                        <th>SR#</th>
                        <!--<th>USER</th>-->
                        <th>A/C</th>
                        <th>DATE</th>
                        <th>PAY DATE</th>
                        <th>BANK</th>
                        <th>RZ#</th>
                        <th>NAME</th>
                        <th>NO.</th>
                        <th>DETAILS</th>
                        <th>Dr.</th>
                        <!--<th>Cr.</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php $records = mysqli_query($connect, $sql);
                    $cr_total = $dr_total = 0;
                    $rows = mysqli_num_rows($records);
                    if ($rows > 0) {
                        while ($roz = mysqli_fetch_assoc($records)) {
                            $dr = $cr = 0;
                            $r_id = $roz["r_id"];
                            $tool = SuperAdmin() ? 'G.Sr#' . $roz['r_id'] . '&nbsp;&nbsp;&nbsp;' . 'Branch Sr#' . $roz['branch_serial'] : 'Branch Sr#' . $roz['branch_serial'];
                            if ($roz['r_name'] != 'cheque') continue;
                            if ($roz['dr_cr'] == "cr") continue;
                            ?>
                            <tr>
                                <td class="text-nowrap">
                                    <a href="<?php echo 'roznamcha-add?id=' . $r_id; ?>" target="_blank"
                                       class="text-dark" data-bs-toggle="tooltip"
                                       title="<?php echo $tool; ?>">
                                        <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                    </a>
                                </td>
                                <!--<td><?php /*echo userName($roz['user_id']); */?></td>-->
                                <!--<td class="text-nowrap"><?php /*if (SuperAdmin()) {
                                        echo branchName($roz['branch_id']);
                                        echo !empty($roz['transfered_from']) ? roznamchaName($roz['transfered_from']) : '';
                                    } */?>
                                </td>-->
                                <td><?php echo $roz['khaata_no']; ?></td>
                                <td class="text-nowrap"><?php echo my_date($roz['r_date']); ?></td>
                                <td class="text-nowrap fw-medium"><?php echo my_date($roz['r_date_payment']); ?></td>
                                <td class="text-nowrap"><?php echo bankName($roz['bank_id']); ?></td>
                                <!--<td><?php /*echo badge($roz['r_type'], 'secondary'); */ ?></td>-->
                                <td><?php echo $roz['roznamcha_no']; ?></td>
                                <td><?php echo $roz['r_name']; ?></td>
                                <td><?php echo $roz['r_no']; ?></td>
                                <td class="small"><?php echo $roz['details']; ?></td>
                                <?php if ($roz['dr_cr'] == "dr") {
                                    $dr = $roz['amount'];
                                    $dr_total += $dr;
                                } else {
                                    $cr = $roz['amount'];
                                    $cr_total += $cr;
                                } ?>
                                <td class="text-success"><?php echo round($dr); ?></td>
                                <td class="d-none text-danger"><?php echo round($cr); ?></td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr class="text-center"><th colspan="12">No record(s)</th></tr>';
                    } ?>
                    <input type="hidden" id="rows" value="<?php echo $rows; ?>">
                    <input type="hidden" id="dr_total" value="<?php echo $dr_total; ?>">
                    <input type="hidden" id="cr_total" value="<?php echo $cr_total; ?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#rows_span").text($("#rows").val());
    var dr_total = $("#dr_total").val();
    var cr_total = $("#cr_total").val();
    var bal = Number(dr_total) - Number(cr_total);
    $("#dr_total_span").text(dr_total);
    $("#cr_total_span").text(cr_total);
    $("#bal_span").text(bal);

    if (bal > 0) {
        $("#bal_span").addClass('text-success');
    } else if (bal < 0) {
        $("#bal_span").addClass('text-danger');
    }


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
