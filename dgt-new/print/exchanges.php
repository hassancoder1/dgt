<?php $url_index = '../';
if (isset($_GET['secret']) && base64_decode($_GET['secret']) == "powered-by-upsol"
    && isset($_GET['start_date']) && isset($_GET['end_date'])
    && isset($_GET['curr_get'])
) {
    require("../connection.php");
    global $connect;
    $start_date = mysqli_real_escape_string($connect, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($connect, $_GET['end_date']);
    $curr_get = mysqli_real_escape_string($connect, $_GET['curr_get']);

    $sql = "SELECT * FROM exchanges WHERE id>0 ";
    if ($start_date != '' && $end_date != '') {
        $sql .= " AND created_at BETWEEN '$start_date' AND '$end_date' ";
    }
    $sql .= " AND curr1 = '$curr_get' OR curr2 = '$curr_get' ";
    $dates = $start_date != '' && $end_date != '' ? '<div><b>Date: </b>' . my_date($start_date) . ' - ' . my_date($end_date) . '</div>' : '';
    $records = mysqli_query($connect, $sql); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo 'Exchanges_' . my_date(date('Y-m-d')); ?> </title>
        <meta name="description" content="Owner of DGT.llc">
        <meta name="author" content="Asmatullah Abdullah">
        <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
        <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/custom.css" rel="stylesheet">
        <link href="../assets/css/virtual-select.min.css" rel="stylesheet">
        <link href="../assets/fa/css/fontawesome.css" rel="stylesheet"/>
        <link href="../assets/fa/css/brands.css" rel="stylesheet"/>
        <link href="../assets/fa/css/solid.css" rel="stylesheet"/>
        <link rel="shortcut icon" href="../assets/images/favicon.jpg"/>
    </head>
    <body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between  mb-1">
                    <img src="../assets/images/logo.png" alt="logo" class="img-fluid" width="100">
                    <div>
                        <?php echo $dates; ?>
                    </div>
                    <div><b>P. </b><span id="p_total_span"></span></div>
                    <div><b>S. </b><span id="s_total_span"></span></div>
                    <div><b>Bal. </b><span id="bal_span"></span></div>

                    <div class="text-end">
                        <h3 class="fw-bold mb-0">Exchanges Entry</h3>
                    </div>
                </div>
                <table class="table table-sm table-bordered table-hover">
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
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rows = $p_total = $s_total = $balance = 0;
                    $records = mysqli_query($connect, $sql);
                    while ($record = mysqli_fetch_assoc($records)) {
                        ++$rows;
                        $id = $record["id"];
                        $khaata_exchange = json_decode($record['khaata_exchange']); ?>
                        <tr>
                            <td><?php echo $rows; ?></td>
                            <td><?php echo my_date($record['created_at']); ?></td>
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
                        </tr>
                        <?php if ($record['p_s'] == 'p') {
                            $p_total += $record['qty'];
                        } else {
                            $s_total += $record['qty'];
                        }
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="rows" value="<?php echo $rows; ?>">
                <input type="hidden" id="p_total" value="<?php echo $p_total; ?>">
                <input type="hidden" id="s_total" value="<?php echo $s_total; ?>">
                <input type="hidden" id="curr_get_hidden" value="<?php echo $curr_get; ?>">
            </div>
        </div>
    </div>
    <div class="d-print-none shadow-lg position-fixed  start-0" style="top: 5%">
        <div class="list-group rounded-0">
            <a href="../exchanges" class="list-group-item list-group-item-secondary p-1"><i
                        class="fa fa-arrow-left"></i>Back</a>
            <a onclick="window.print();" href="#." class="list-group-item list-group-item-secondary p-1"><i
                        class="fa fa-print"></i> Print</a>
        </div>
    </div>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/bs/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/virtual-select.min.js"></script>
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
    </body>
    </html>
<?php } else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
} ?>