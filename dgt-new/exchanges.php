<?php $page_title = 'Exchanges Entry';
include("header.php");
$pageURL = 'exchanges';
$removeFilter = $curr_get = '';
$start_date = $end_date = date('Y-m-d');
$is_search = false;
$sql = "SELECT * FROM exchanges WHERE id>0 ";
if ($_GET) {
    $is_search = true;
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['start']) && isset($_GET['end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['start']));
        $end_date = date('Y-m-d', strtotime($_GET['end']));
        $pageURL .= '?start=' . $start_date . '&end=' . $end_date;
        $sql .= " AND created_at BETWEEN '$start_date' AND '$end_date' ";
    }
    if (!empty($_GET['curr_get'])) {
        $curr_get = mysqli_real_escape_string($connect, $_GET['curr_get']);
        $pageURL .= '&curr_get=' . $curr_get;
        $sql .= " AND curr1 = '$curr_get' OR curr2 = '$curr_get' ";
    }
} ?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex gap-0 align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
        <div><b>P. </b><span id="p_total_span"></span></div>
        <div><b>S. </b><span id="s_total_span"></span></div>
        <div><b>Bal. </b><span id="bal_span"></span></div>
        <form name="datesSubmit" method="get">
            <div class="input-group input-group-sm">
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
                <?php echo $removeFilter; ?>
            </div>
        </form>
        <div class="d-flex gap-1">
            <?php echo searchInput('a', 'form-control-sm '); ?>
            <?php echo addNew('exchange-add', '', 'btn-sm'); ?>
            <?php if ($is_search) { ?>
                <form action="print/exchanges" method="get">
                    <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                    <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                    <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                    <input type="hidden" name="curr_get" value="<?php echo $curr_get; ?>">
                    <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-print"></i></button>
                </form>
            <?php } ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="co-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-hover">
                        <thead>
                        <tr class="text-uppercase">
                            <th>SR#</th>
                            <th>DATE</th>
                            <th>DETAILS</th>
                            <th>1st Currency</th>
                            <th>2nd Currency</th>
                            <th>Dr. A/c</th>
                            <th>Cr. A/c</th>
                            <th>Balance</th>
                            <th>Voucher</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $rows = $p_total = $s_total = $balance = 0;
                        if ($is_search) {
                            /*$sql = "SELECT * FROM exchanges  ";
                            $sql .= $is_search ? " WHERE curr1 = '$curr_get' OR curr2 = '$curr_get' " : "";*/

                            $records = mysqli_query($connect, $sql);
                            while ($record = mysqli_fetch_assoc($records)) {
                                ++$rows;
                                $id = $record["id"];
                                $khaata_exchange = json_decode($record['khaata_exchange']); ?>
                                <tr>
                                    <td><?php echo $rows; ?></td>
                                    <td>
                                        <a class="text-dark"
                                           href="<?php echo 'exchange-add?id=' . $id; ?>"><?php echo my_date($record['created_at']); ?></a>
                                    </td>
                                    <td><?php echo $record['details']; ?></td>
                                    <td><?php echo $record['p_s'] == 'p' ? '<span class="badge text-bg-success ">P</span> ' : '<span class="badge text-bg-danger">S</span> ';
                                        echo $record['curr1'] . ' ' . $record['qty'] . '<sub>/' . $record['per_price'] . '</sub>'; ?></td>
                                    <td>
                                        <?php echo $record['p_s'] == 's' ? '<span class="badge text-bg-success">P</span> ' : '<span class="badge text-bg-danger">S</span> ';
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
                                    <td><?php echo $khaata_exchange->dr_khaata_no; ?></td>
                                    <td><?php echo $khaata_exchange->cr_khaata_no; ?></td>
                                    <td><?php echo $khaata_exchange->final_amount; ?></td>
                                    <td><?php echo getTransferredToRoznamchaSerial('Business', $id, 'exchange') ?></td>
                                    <!--echo $balance;-->
                                    <?php echo '<td>';
                                    if (SuperAdmin()) {
                                        $delete_msg = 'Are you sure to delete?\n' . $record['curr1'] . ' ' . $record['qty'] . '/' . $record['per_price'] . '\nDate: ' . my_date($record['created_at']);
                                        echo '<form method="post" onsubmit="return confirm(\'' . $delete_msg . '\')"><input value="' . $id . '" name="id_delete" type="hidden">';
                                        echo '<button name="deleteSDSubmit" type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" data-bs-toggle="tooltip" data-bs-title="Delete"><i class="fa fa-trash-alt"></i></button>';
                                        echo '</form>';
                                    }
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
} ?>
