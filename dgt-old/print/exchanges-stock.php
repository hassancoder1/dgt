<?php if (isset($_GET['start']) && isset($_GET['end']) && isset($_GET['curr_get'])) {
    $backURL = '../exchanges-stock';
    require("../connection.php");
    global $connect;
    $curr_get = mysqli_real_escape_string($connect, $_GET['curr_get']);
    $start = mysqli_real_escape_string($connect, $_GET['start']);
    $end = mysqli_real_escape_string($connect, $_GET['end']); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Exchanges_Stock_<?php echo date('Y_m_d'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Al Ras Deira Dubai office UAE dubai" name="description"/>
        <meta content="DGT L.L.C" name="author"/>
        <link rel="shortcut icon" href="../assets/images/favicon.jpg">
        <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
        <style>
            body {
                background-color: white !important;
            }

            * {
                color: black;
            }

            .table > :not(caption) > * > * {
                padding: 0.1rem .45rem;
            }

            input {
                pointer-events: none;
                font-weight: bold !important;
                font-family: 'Noto Naskh Arabic', serif;
            }

            .table tbody tr td {
                font-size: 10px;
                color: inherit;
            }

            .table thead tr th {
                font-size: 8px;
                background: black;
                color: white;
            }

            .table-bordered {
                border: 1px solid #000000;
            }
        </style>
    </head>
    <body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                    </div>
                    <div class="text-center">
                        <div>
                            <b>PURCHASES: </b><span id="p_total_span"></span>
                            <b>SALES: </b><span id="s_total_span"></span>
                        </div>
                        <div><b>BALANCE: </b><span id="bal_span"></span></div>
                    </div>
                    <div class="text-end">
                        <h2 class="fw-bold mb-0 text-uppercase">Exchanges Stock</h2>
                        <?php echo $start != '' ? 'From:' . date('d-F-Y', strtotime($start)) : '';
                        echo $end != '' ? ' To:' . date('d-F-Y', strtotime($end)) : ''; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12">
                <table class="table mb-0 table-bordered table-sm fix-head-table">
                    <thead>
                    <tr class="text-nowrap">
                        <td>SR#</td>
                        <td>DATE</td>
                        <td>DETAILS</td>
                        <td>1st Currency</td>
                        <td>2nd Currency</td>
                        <td>Balance</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $rows = $p_total = $s_total = $balance = 0;
                    $sql = "SELECT * FROM exchanges WHERE curr1 = '$curr_get' OR curr2 = '$curr_get' ";
                    $records = mysqli_query($connect, $sql);
                    while ($record = mysqli_fetch_assoc($records)) {
                        if ($start != '') {
                            if ($record['created_at'] < $start) continue;
                        }
                        if ($end != '') {
                            if ($record['created_at'] > $end) continue;
                        }
                        ++$rows;
                        $id = $record["id"]; ?>
                        <tr>
                            <td><?php echo $rows; ?></td>
                            <td><?php echo my_date($record['created_at']); ?></td>
                            <td><?php echo $record['details']; ?></td>
                            <td>
                                <?php echo $record['p_s'] == 'p' ? '<span class="badge badge-pill badge-soft-success ">P</span>' : '<span class="badge badge-pill badge-soft-danger">S</span>';
                                echo $record['curr1'] . ' ' . $record['qty']; ?>
                                <sub>/<?php echo $record['per_price']; ?></sub>
                            </td>
                            <td>
                                <?php echo $record['p_s'] == 's' ? '<span class="badge badge-pill badge-soft-success ">P</span>' : '<span class="badge badge-pill badge-soft-danger">S</span>';
                                echo $record['curr2'] . ' ' . $record['amount']; ?>
                            </td>
                            <?php
                            if ($record['curr1'] == $curr_get) {
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
    <script src="../assets/tooltip/tooltip.min.js"></script>
    <div class="sticky-social d-print-none" style="z-index: 999999">
        <ul class="social">
            <li class="bg-dark" data-tooltip="Go Back" data-tooltip-position="right">
                <a href="<?php echo $backURL; ?>"><i class="fa fa-long-arrow-alt-left"></i></a>
            </li>
            <li class="facebook" title="PDF Print">
                <a class="cursor-pointer" onclick="window.print();"><i class="fa fa-print"></i></a>
            </li>
        </ul>
    </div>
    </body>
    </html>
    <?php echo isset($_GET['print']) ? '<script>window.print();</script>' : '';
} else {
    echo '<script>window.location.href="../";</script>';
} ?>
<script>
    var curr_get_hidden  = document.getElementById("curr_get_hidden").value;
    var p_total = document.getElementById("p_total").value;
    var s_total = document.getElementById("s_total").value;
    document.getElementById("p_total_span").textContent = p_total;
    document.getElementById("s_total_span").textContent = s_total;
    var bal = Number(p_total) - Number(s_total);
    document.getElementById("bal_span").textContent = bal + ' ' + curr_get_hidden;
    if (bal > 0) {
        document.getElementById("bal_span").classList.add('text-success');
    } else if (bal < 0) {
        document.getElementById("bal_span").classList.add('text-danger');
    }

</script>
