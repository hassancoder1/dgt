<?php $page_title = 'Roznamcha';
include("header.php");
$pageURL = 'roznamcha';
$searchUserName = $selectedBranch = $date_msg = $branch_msg = $username_msg = $removeFilter = "";
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM roznamchaas WHERE r_id>0 ";
if ($_GET) {
    $removeFilter = removeFilter('roznamcha');
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary">' . $start_date . ' to ' . $end_date . '</span>';
        $pageURL .= '?r_date_start=' . $start_date . '&r_date_end=' . $end_date;
    }
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $searchUserName = $_GET['username'];
        $sql .= " AND username LIKE " . "'%$searchUserName%'" . " ";
        $username_msg = '<span class="badge bg-primary ms-1">' . $searchUserName . '</span>';
        $pageURL .= '&username=' . $searchUserName;
    }
    if (isset($_GET['branch_id']) && !empty($_GET['branch_id'])) {
        $postBranchId = $_GET['branch_id'];
        if ($postBranchId > 0) {
            $sql .= "AND branch_id = " . "'$postBranchId'" . " ";
            $selectedBranchId = $postBranchId;
            $branch_msg = '<span class="badge bg-dark ms-1">' . getTableDataByIdAndColName('branches', $selectedBranchId, 'b_name') . '</span>';
        }
        $pageURL .= '&branch_id=' . $postBranchId;
    }
} else {
    $sql .= " AND r_date = '$start_date'";
}
//echo $sql;?>
<div class="row">
    <div class="co-md-12">
        <div class="d-flex align-items-center justify-content-between gap-1">
            <div class="d-flex gap-md-2">
                <div>Rows<span id="rows_span" class="fw-bold"></span></div>
                <div>Dr.<span id="dr_total_span" class="fw-bold"></span></div>
                <div>Cr.<span id="cr_total_span" class="fw-bold"></span></div>
                <div>Bal.<span id="bal_span" class="fw-bold"></span></div>
            </div>
            <form name="datesSubmit" method="get">
                <div class="row g-0 justify-content-lg-between justify-content-center table-form">
                    <div class="col-sm">
                        <div class="input-group">
                            <input id="r_date_start" name="r_date_start" type="date"
                                   value="<?php echo $start_date; ?>" class="form-control form-control-sm">
                            <label for="r_date_end" class="input--group-text">To</label>
                            <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="date"
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="input-group">
                            <select id="branch_id" name="branch_id" class="form-select form--control">
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
                        </div>
                    </div>
                    <div class="col-sm d-none d-md-block">
                        <?php echo searchInput('a', 'form-control-sm'); ?>
                    </div>
                    <div class="col-sm-auto">
                        <?php //echo $date_msg . $branch_msg . $username_msg . $removeFilter;
                         echo  $removeFilter; ?>
                    </div>
                </div>
            </form>
            <div class="d-flex ac_row table-form">
                <?php echo addNew('roznamcha-add', '', 'btn-sm text-nowrap'); ?>
                <form action="print/roznamcha-full" method="post" target="_blank" class="d-none d-md-block">
                    <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
                    <input name="r_type" value="<?php echo KAROBAR; ?>" type="hidden">
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
        <?php echo $_SESSION['response'] ?? ''; ?>
        <?php unset($_SESSION['response']); ?>
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>SR#</th>
                            <th>USER</th>
                            <th>DATE</th>
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
                                $r_id = $roz["r_id"]; ?>
                                <tr>
                                    <td class="text-nowrap">
                                        <a href="<?php echo 'roznamcha-add?id=' . $r_id; ?>"
                                           class="btn btn-success bg-gradient btn-sm" data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="<?php echo SuperAdmin() ? 'G.Sr#' . $roz['r_id'] . '&nbsp;&nbsp;&nbsp;' . 'Branch Sr#' . $roz['branch_serial'] : 'Branch Sr#' . $roz['branch_serial']; ?>">
                                            <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                        </a>
                                    </td>
                                    <td class="small">
                                        <?php echo userName($roz['user_id']); ?>
                                        <?php if (SuperAdmin()) {
                                            echo branchName($roz['branch_id']);
                                            echo !empty($roz['transfered_from']) ? roznamchaName($roz['transfered_from']) : '';
                                        } ?>
                                    </td>
                                    <td class="text-nowrap"><?php echo $roz['r_date']; ?>
                                        <br><span
                                            class="badge badge-pill badge-soft-success font-size-11"><?php echo $roz['r_type']; ?></span>
                                    </td>
                                    <td><?php echo $roz['khaata_no']; ?></td>
                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                    <td class="small"><?php echo $roz['r_name']; ?></td>
                                    <td><?php echo $roz['r_no']; ?></td>
                                    <td class="small">
                                        <?php echo $roz['details'];
                                        if ($roz['r_type'] == 'Bill') {
                                            echo '<b>' . $roz['currency'];
                                            echo ' AMOUNT' . $roz['qty'];
                                            echo ' Per Price ' . $roz['per_price'] . '</b>';
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
                                        <div class="d-flex gap-2">
                                            <a href="<?php echo 'print/roznamcha-single?r_id=' . base64_encode($r_id) . '&secret=' . base64_encode('powered-by-upsol'); ?>"
                                               class="btn btn-primary bg-gradient btn-sm" data-bs-toggle="tooltip"
                                               data-bs-placement="top" title="Print">
                                                <i data-eva="eye" data-eva-height="14" data-eva-width="14"
                                                   class="fill-white align-text-top"></i>
                                            </a>
                                            <!--<a onclick="deleteRoznamcha(this)" id="<?php /*echo $roz['r_id']; */?>" data-url="roznamcha" data-r-type="<?php /*echo $roz['r_type']; */?>" data-amount="<?php /*echo $roz['amount']; */?>" class="btn btn-danger bg-gradient btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i data-eva="trash-2" data-eva-height="14" data-eva-width="14" class="fill-white align-text-top"></i></a>-->
                                            <form method="post"
                                                  onsubmit="return confirm('Are you sure to delete?\nRoznamcha Type: <?php echo $roz['r_type']; ?>\nAmount: <?php echo $roz['amount']; ?>');">
                                                <input type="hidden" name="r_id_hidden"
                                                       value="<?php echo $roz['r_id']; ?>">
                                                <input type="hidden" name="r_type_hidden"
                                                       value="<?php echo $roz['r_type']; ?>">
                                                <button type="submit" name="deleteRoznamchaSubmit"
                                                        class="btn btn-danger bg-gradient btn-sm"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                                    <i data-eva="trash-2" data-eva-height="14" data-eva-width="14"
                                                       class="fill-white align-text-top"></i>
                                                </button>
                                            </form>
                                        </div>
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
    $roz_types = array('Business', 'Bank', 'Bill', 'Cash');
    if ($r_id_hidden > 0 && in_array($r_type_hidden, $roz_types)) {
        $r_moved = mysqli_query($connect, "INSERT INTO roznamchaas_deleted SELECT * FROM roznamchaas WHERE r_id = '$r_id_hidden'");
        if ($r_moved) {
            $deleted = mysqli_query($connect, "DELETE FROM `roznamchaas` WHERE r_id = '$r_id_hidden'");
            if ($deleted) {
                $msg = $r_type_hidden . ' deleted. ';
                $type = 'success';
            }
        }
    }
    message($type, $pageURL, $msg);

} ?>

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
