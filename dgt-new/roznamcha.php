<?php $page_title = 'Roznamcha';
include("header.php");
$pageURL = 'roznamcha';
$r_type = $searchUserName = $selectedBranch = $removeFilter = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_id>0 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $pageURL .= '?r_date_start=' . $start_date . '&r_date_end=' . $end_date;
    }
    if (!empty($_GET['username'])) {
        $searchUserName = mysqli_real_escape_string($connect, $_GET['username']);
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        $pageURL .= '&username=' . $searchUserName;
    }
    if (!empty($_GET['r_type'])) {
        $r_type = mysqli_real_escape_string($connect, $_GET['r_type']);
        $sql .= "AND r_type = " . "'$r_type'" . " ";
        $pageURL .= '&r_type=' . $r_type;
    }
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
            <div class="lh-1"><b>Dr. </b><span id="dr_total_span"></span><br><b>Cr. </b><span
                        id="cr_total_span"></span></div>
            <div class="lh-1"><b>Bal. </b><span id="bal_span"></span><br><b>Rows </b><span id="rows_span"></span>
            </div>
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
                <select id="r_type" name="r_type" class="form-select">
                    <option value="">All Types</option>
                    <?php $static_types = fetch('static_types', array('type_for' => 'r_type'));
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        $r_select = $static_type['type_name'] == $r_type ? 'selected' : '';
                        echo '<option ' . $r_select . ' value="' . $static_type['type_name'] . '">' . $static_type['type_name'] . '</option>';
                    } ?>
                </select>
                <input type="text" id="username" name="username" class="form-control "
                       placeholder="Search user ID" value="<?php echo $searchUserName; ?>">
                <?php echo $removeFilter; ?>
            </div>
        </form>
        <div class="d-flex gap-1">
            <?php echo searchInput('1', 'form-control form-control-sm '); ?>
            <?php echo addNew('roznamcha-add', '', 'btn-sm'); ?>
            <form action="print/roznamcha-full" method="get" target="_blank">
                <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
                <input name="r_date_start" value="<?php echo $start_date; ?>" type="hidden">
                <input name="r_date_end" value="<?php echo $end_date; ?>" type="hidden">
                <input name="branch_id" value="<?php echo $selectedBranchId; ?>" type="hidden">
                <input name="r_type" value="<?php echo $r_type ?>" type="hidden">
                <input name="username" value="<?php echo $searchUserName; ?>" type="hidden">
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
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>SR#</th>
                            <th>USER</th>
                            <?php if (SuperAdmin()) {
                                echo '<th>BR.</th>';
                            } ?>
                            <th>DATE</th>
                            <th>TYPE</th>
                            <th>A/C</th>
                            <th>RZ#</th>
                            <th>NAME</th>
                            <th>NO.</th>
                            <th>DETAILS</th>
                            <th>Dr.</th>
                            <th>Cr.</th>
                            <th></th>
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
                                $tool = SuperAdmin() ? 'G.Sr#' . $roz['r_id'] . '&nbsp;&nbsp;&nbsp;' . 'Branch Sr#' . $roz['branch_serial'] : 'Branch Sr#' . $roz['branch_serial']; ?>
                                <tr>
                                    <td class="text-nowrap">
                                        <a href="<?php echo 'roznamcha-add?id=' . $r_id; ?>"
                                           class="text-dark" data-bs-toggle="tooltip"
                                           title="<?php echo $tool; ?>">
                                            <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                        </a>
                                    </td>
                                    <td><?php echo userName($roz['user_id']); ?></td>
                                    <td class="text-nowrap"><?php if (SuperAdmin()) {
                                            echo branchName($roz['branch_id']);
                                            echo !empty($roz['transfered_from']) ? roznamchaName($roz['transfered_from']) : '';
                                        } ?>
                                    </td>
                                    <td class="text-nowrap"><?php echo my_date($roz['r_date']); ?></td>
                                    <td class="text-nowrap"><?php echo badge(shortName($roz['r_type']), 'secondary');
                                        echo $roz['img'] != '' ? '<img src="' . $roz['img'] . '" width="20" height="20" class="img-fluid m-0">' : ''; ?>
                                    </td>
                                    <td><?php echo $roz['khaata_no']; ?></td>
                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                    <td><?php echo $roz['r_name']; ?></td>
                                    <td><?php echo $roz['r_no']; ?></td>
                                    <td class="small">
                                        <?php echo $roz['details'];
                                        if ($roz['r_type'] == 'Bill') {
                                            echo $roz['currency'] . ' AMOUNT' . $roz['qty'] . ' Per Price ' . $roz['per_price'];
                                        }
                                        if ($roz['r_type'] == 'Bank') {
                                            echo ' Bank:' . bankName($roz['bank_id']) . ' Date:' . my_date($roz['r_date_payment']);
                                        } ?>
                                    </td>
                                    <?php if ($roz['dr_cr'] == "dr") {
                                        $dr = $roz['amount'];
                                        $dr_total += $dr;
                                    } else {
                                        $cr = $roz['amount'];
                                        $cr_total += $cr;
                                    } ?>
                                    <td class="text-success"><?php echo round($dr); ?></td>
                                    <td class="text-danger"><?php echo round($cr); ?></td>
                                    <td>
                                        <?php if (SuperAdmin()) { ?>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo 'print/roznamcha-single?r_id=' . base64_encode($r_id) . '&secret=' . base64_encode('powered-by-upsol'); ?>"
                                                   target="_blank" class="btn btn-sm btn-outline-dark py-0"
                                                   data-bs-toggle="tooltip" title="Print">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <!--<a onclick="deleteRoznamcha(this)" id="<?php /*echo $roz['r_id']; */ ?>" data-url="roznamcha" data-r-type="<?php /*echo $roz['r_type']; */ ?>" data-amount="<?php /*echo $roz['amount']; */ ?>" class="btn btn-danger bg-gradient btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i data-eva="trash-2" data-eva-height="14" data-eva-width="14" class="fill-white align-text-top"></i></a>-->
                                                <form method="post"
                                                      onsubmit="return confirm('Are you sure to delete?\nRoznamcha Type: <?php echo $roz['r_type']; ?>\nAmount: <?php echo $roz['amount']; ?>');">
                                                    <input type="hidden" name="r_id_hidden"
                                                           value="<?php echo $roz['r_id']; ?>">
                                                    <input type="hidden" name="r_type_hidden"
                                                           value="<?php echo $roz['r_type']; ?>">
                                                    <button type="submit" name="deleteRoznamchaSubmit"
                                                            class="btn btn-sm btn-outline-danger py-0"
                                                            data-bs-toggle="tooltip" title="Delete">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php } ?>
                                    </td>
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
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['deleteRoznamchaSubmit'])) {
    $msg = 'DB Error';
    $type = 'danger';
    $r_id_hidden = mysqli_real_escape_string($connect, $_POST['r_id_hidden']);
    $r_type_hidden = mysqli_real_escape_string($connect, $_POST['r_type_hidden']);
    $roz_types = array('Business', 'Bank', 'Bill', 'Cash', 'Agent Bill');
    if ($r_id_hidden > 0 && in_array($r_type_hidden, $roz_types)) {
        $r_moved = mysqli_query($connect, "INSERT INTO roznamchaas_deleted SELECT * FROM roznamchaas WHERE r_id = '$r_id_hidden'");
        if ($r_moved) {
            $deleted = mysqli_query($connect, "DELETE FROM `roznamchaas` WHERE r_id = '$r_id_hidden'");
            if ($deleted) {
                $msg = $r_type_hidden . ' Roznamcha deleted. ';
                $type = 'success';
            }
        }
    }
    messageNew($type, $pageURL, $msg);

} ?>
<script>
    $("#rows_span").text($("#rows").val());
    var dr_total = $("#dr_total").val();
    var cr_total = $("#cr_total").val();
    var bal = Number(dr_total) - Number(cr_total);
    bal = bal.toFixed(2);
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
        $('#r_date_start, #r_date_end, #branch_id,#r_type').change(function () {
            document.datesSubmit.submit();
        });
    });

    function deleteRoznamcha(e) {
        var id = $(e).attr('id');
        var url = $(e).attr('data-url');
        var r_type = $(e).attr('data-r-type');
        ///var r_type_u = roznamchaName(r_type);
        var amount = $(e).attr('data-amount');
        var msgg = 'Are you sure to delete? ';
        msgg += '\n';
        msgg += 'Type:  ' + r_type;
        msgg += '\n';
        msgg += 'Amount: ' + amount;
        if (id && url && r_type) {
            let link = 'ajax/deleteRoznamcha.php?id=' + id + '&url=' + url + '&r_type=' + r_type;
            if (confirm(msgg)) {
                window.location.href = 'ajax/deleteRoznamcha.php?id=' + id + '&url=' + url + '&r_type=' + r_type;
            }
        }
    }
</script>
