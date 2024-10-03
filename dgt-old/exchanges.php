<?php $page_title = 'Exchanges Entry';
include("header.php");
$pageURL = 'exchanges';
$removeFilter = $p_s = '';
$curr_get = '';
$selectedBranchId = 0;
$start_date = $end_date = date('Y-m-d');
$is_search = false;
if ($_GET) {
    $is_search = true;
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['start']) && isset($_GET['end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['start']));
        $end_date = date('Y-m-d', strtotime($_GET['end']));
        $pageURL .= '?start=' . $start_date . '&end=' . $end_date;
    }

    if (!empty($_GET['curr_get'])) {
        $curr_get = $_GET['curr_get'];
        $pageURL .= '&curr_get=' . $curr_get;
    }
    if (!empty($_GET['p_s'])) {
        $p_s = mysqli_real_escape_string($connect, $_GET['p_s']);
        $pageURL .= '&p_s=' . $p_s;
    }
} ?>
<div class="row">
    <div class="co-md-12">
        <div class="d-flex align-items-center justify-content-between gap-md-3">
            <div class="d-flex gap-md-2">
                <!--<div>Rows<span id="rows_span" class="fw-bold"></span></div>-->
                <div>P.<span id="p_total_span" class="fw-bold"></span></div>
                <div>S.<span id="s_total_span" class="fw-bold"></span></div>
                <div>Bal.<span id="bal_span" class="fw-bold"></span></div>
            </div>
            <form name="datesSubmit" method="get" class="table-form d-flex flex-fill">
                <select id="curr_get" name="curr_get" class="form-select">
                    <option value="" selected disabled>Select</option>
                    <?php $currencies = fetch('currencies');
                    while ($crr = mysqli_fetch_assoc($currencies)) {
                        $p_crr_sel = $crr['name'] == $curr_get ? 'selected' : '';
                        echo '<option ' . $p_crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                    } ?>
                </select>
                <input id="start" name="start" type="date" value="<?php echo $start_date; ?>" class="form-control">
                <input id="end" name="end" value="<?php echo $end_date; ?>" type="date" class="form-control">
                <select id="p_s" name="p_s" class="form-select d-none">
                    <option value="" selected disabled>Select P/S</option>
                    <?php $p_s_array = array('p' => 'Purchase', 's' => 'Sale',);
                    foreach ($p_s_array as $index => $val) {
                        $p_s_sel = $index == $p_s ? 'selected' : '';
                        echo '<option ' . $p_s_sel . ' value="' . $index . '">' . $val . '</option>';
                    } ?>
                </select>
                <?php echo searchInput('a', 'form-control-sm'); ?>
                <?php //echo $removeFilter; ?>
            </form>

            <div class="d-flex ac_row table-form">
                <?php echo addNew('exchange-add', '', 'btn-sm text-nowrap'); ?>
                <form action="print/<?php echo $pageURL; ?>" method="post" target="_blank">
                    <input name="secret" value="<?php echo base64_encode('powered-by-upsol') ?>" type="hidden">
                    <input name="start" value="<?php echo $start_date; ?>" type="hidden">
                    <input name="end" value="<?php echo $end_date; ?>" type="hidden">
                    <input name="curr_get" value="<?php echo $curr_get; ?>" type="hidden">
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
                    <table class="table mb-0 table-sm">
                        <thead>
                        <tr>
                            <th>SR#</th>
                            <th>DATE</th>
                            <th>DETAILS</th>
                            <th>1st Currency</th>
                            <th>2nd Currency</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rows = $p_total = $s_total = $balance = 0;
                        if ($is_search) {
                            /*$sql = "SELECT * FROM exchanges  ";
                            $sql .= $is_search ? " WHERE curr1 = '$curr_get' OR curr2 = '$curr_get' " : "";*/
                            $sql = "SELECT * FROM exchanges WHERE curr1 = '$curr_get' OR curr2 = '$curr_get' ";
                            $records = mysqli_query($connect, $sql);
                            while ($record = mysqli_fetch_assoc($records)) {
                                ++$rows;
                                $id = $record["id"]; ?>
                                <tr>
                                    <td><?php echo $rows; ?></td>
                                    <td>
                                        <a href="<?php echo 'exchange-add?id=' . $id; ?>"><?php echo my_date($record['created_at']); ?></a>
                                    </td>
                                    <td><?php echo $record['details']; ?></td>
                                    <td>
                                        <?php echo $record['p_s'] == 'p' ? '<span class="badge badge-pill badge-soft-success ">P</span>' : '<span class="badge badge-pill badge-soft-danger">S</span>';
                                        echo $record['curr1'] . ' ' . $record['qty'] . '<sub>/' . $record['per_price'] . '</sub>'; ?>
                                    </td>
                                    <td>
                                        <?php echo $record['p_s'] == 's' ? '<span class="badge badge-pill badge-soft-success ">P</span>' : '<span class="badge badge-pill badge-soft-danger">S</span>';
                                        echo $record['curr2'] . ' ' . $record['amount']; ?>
                                    </td>
                                    <?php if ($record['curr1'] == $curr_get) {
                                        if ($record['p_s'] == 'p') {
                                            $balance += $record['qty'];
                                        } else {
                                            $balance -= $record['qty'];
                                        }
                                    }
                                    if ($record['curr2'] == $curr_get) {
                                        if ($record['p_s'] == 'p') {
                                            $balance -= $record['amount'];
                                        } else {
                                            $balance += $record['amount'];
                                        }
                                    } ?>
                                    <td><?php echo $balance; ?></td>
                                    <?php echo '<td>';
                                    $delete_msg = 'Are you sure to delete?\n'.$record['curr1'] . ' ' . $record['qty'] . '/' . $record['per_price'] . '\nDate: '.my_date($record['created_at']);
                                    echo '<form method="post" onsubmit="return confirm(\'' . $delete_msg . '\')"><input value="' . $id . '" name="id_delete" type="hidden">';
                                    echo '<button name="deleteSDSubmit" type="submit" class="btn btn-sm p-0 ms-1 text-danger">Delete</button>';
                                    echo '</form>';
                                    echo '</td>'; ?>
                                </tr>
                                <?php if ($record['p_s'] == 'p') {
                                    $p_total += $record['qty'];
                                } else {
                                    $s_total += $record['qty'];
                                }
                            }
                        } ?>
                        <input type="hidden" id="rows" value="<?php echo $rows; ?>">
                        <input type="hidden" id="p_total" value="<?php echo $p_total; ?>">
                        <input type="hidden" id="s_total" value="<?php echo $s_total; ?>">
                        <input type="hidden" id="curr_get_hidden" value="<?php echo $curr_get; ?>">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    //$("#rows_span").text($("#rows").val());
    var curr_get_hidden = $("#curr_get_hidden").val();
    var p_total = $("#p_total").val();
    var s_total = $("#s_total").val();
    $("#p_total_span").text(p_total);
    $("#s_total_span").text(s_total);

    var bal = Number(p_total) - Number(s_total);
    $("#bal_span").text(bal + ' ' + curr_get_hidden);

    if (bal > 0) {
        $("#bal_span").addClass('text-success');
    } else if (bal < 0) {
        $("#bal_span").addClass('text-danger');
    }
</script>
<script type="text/javascript">
    $(function () {
        $('#start, #end, #curr_get,#p_s').change(function () {
            document.datesSubmit.submit();
        });
    });
</script>
<?php if (isset($_POST['deleteSDSubmit'])) {
    $type = 'danger';
    $msg = 'DB Failed';
    $id_delete = mysqli_real_escape_string($connect, $_POST['id_delete']);
    $done = mysqli_query($connect, "DELETE FROM `exchanges` WHERE id='$id_delete'");
    if ($done) {
        $msg = "Exchange entry Deleted.";
        $type = "success";
    }
    message($type, $pageURL, $msg);
}  ?>
