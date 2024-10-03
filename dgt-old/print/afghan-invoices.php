<?php if (isset($_GET['start']) && isset($_GET['end']) && isset($_GET['get_khaata_id']) && isset($_GET['is_cleared'])) {
    $backURL = '../afghan-invoices';
    require("../connection.php");
    global $connect;
    $sql = "SELECT * FROM `afg_invs` WHERE is_active = 1 AND type = 'afg' ORDER BY json_final asc ";
    $start = mysqli_real_escape_string($connect, $_GET['start']);
    $end = mysqli_real_escape_string($connect, $_GET['end']);
    $get_khaata_id = mysqli_real_escape_string($connect, $_GET['get_khaata_id']);
    $is_cleared = mysqli_real_escape_string($connect, $_GET['is_cleared']);
    $invoices = mysqli_query($connect, $sql); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Afghan_Invoices_<?php echo date('Y_m_d'); ?></title>
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
        <div class="row justify-content-center ">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                    </div>
                    <div>
                        <div><b>ROWS: </b><span id="rows_count_span"></span></div>
                        <div><b>AMOUNT: </b><span id="amount_total_span"></span></div>
                        <div><b>TT: </b><span id="tt_amount_total_span"></span></div>
                        <div><b>Balance: </b><span id="bal_span"></span></div>
                    </div>
                    <div class="text-end">
                        <h2 class="fw-bold mb-0 text-uppercase">AFGHAN INVOICES</h2>
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
                        <td>#</td>
                        <td>Invoice</td>
                        <td>FROM</td>
                        <td>TO</td>
                        <td>Qty/KGs</td>
                        <td>Goods</td>
                        <td>AMOUNT</td>
                        <td>TT</td>
                        <td>Final</td>
                        <td>Bank Details</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row_count = $amount_total = $tt_amount_total = $inv_khaata_id = 0;
                    while ($inv = mysqli_fetch_assoc($invoices)) {
                        $inv_goods_name = '';
                        $qty1 = $kgs = $total_price = 0;
                        $inv_id = $inv['id'];
                        $inv_type = $inv['type'];
                        $inv_json = json_decode($inv['json_data']);
                        $inv_khaata_id = $inv_json->imp_khaata_id;
                        $inv_khaata_no = $inv_json->imp_khaata_no;

                        $details_query = fetch('afg_inv_details', array('parent_id' => $inv_id));
                        if (mysqli_num_rows($details_query) > 0) {
                            while ($details = mysqli_fetch_assoc($details_query)) {
                                $json_data2 = json_decode($details['json_data']);
                                $total_price += (int)$json_data2->total_price;
                                $qty1 += $json_data2->qty1;
                                $kgs += $json_data2->kgs;
                                $inv_goods_name = $json_data2->goods;
                            }
                            $amount_total += $total_price;
                        }
                        $inv_tt_amount = $inv_final_amount = $inv_curr = $inv_bank_details = '';
                        if (!empty($inv['json_final'])) {
                            $kkk = json_decode($inv['json_final']);
                            $inv_tt_amount = $kkk->tt_amount;
                            $inv_final_amount = $kkk->final_amount;
                            $inv_curr = $kkk->curr;
                            $inv_bank_details = $kkk->bank_details;
                            $tt_amount_total += $inv_tt_amount;
                        }
                        $rowColor = ($inv_tt_amount > 0) ? '' : 'bg-danger bg-opacity-10';

                        if ($start != '') {
                            if ($inv['_date'] < $start) continue;
                        }
                        if ($end != '') {
                            if ($inv['_date'] > $end) continue;
                        }
                        /*if ($goods_name != '') {
                            if ($goods_name != $inv_goods_name) continue;
                        }*/
                        if ($get_khaata_id > 0) {
                            if ($get_khaata_id != $inv_khaata_id) continue;
                        }
                        if ($is_cleared != '') {
                            if ($is_cleared == 1) {
                                if ($inv_tt_amount <= 0) continue;
                            }
                            if ($is_cleared == 0) {
                                if ($inv_tt_amount > 0) continue;
                            }
                        }

                        ++$row_count; ?>
                        <tr>
                            <td><?php echo $row_count . '<br>' . $inv_khaata_no; ?></td>
                            <td class="text-nowrap font-size-11">
                                <?php echo '<b>INV#</b>' . $inv_json->no1;
                                echo '<br><b>D.</b>' . date('y-m-d', strtotime($inv['_date'])); ?>
                            </td>
                            <td class="font-size-11">
                                <?php echo '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $inv['_from'] . '">' . firstLine($inv['_from']) . '</span>'; ?></td>
                            <td class="font-size-11">
                                <?php echo '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $inv['_to'] . '">' . firstLine($inv['_to']) . '</span>'; ?>
                            </td>
                            <td class="fw-normal"><?php echo '<b>Qty</b>:' . $qty1 . '<br><b>KGs</b>' . $kgs; ?></td>
                            <td class="fw-normal"><?php echo $inv_goods_name; ?></td>
                            <td class="bold"><?php echo $total_price > 0 ? $total_price . '<small>USD</small>' : ''; ?></td>
                            <td><?php echo $inv_tt_amount; ?></td>
                            <td><?php echo $inv_final_amount . $inv_curr; ?></td>
                            <td class="fw-normal"><?php echo $inv_bank_details; ?></td>
                        </tr>
                    <?php }
                    $bal = $amount_total - $tt_amount_total; ?>
                    </tbody>
                </table>
                <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                <input type="hidden" id="tt_total_price" value="<?php echo $amount_total; ?>">
                <input type="hidden" id="tt_amount_total" value="<?php echo $tt_amount_total; ?>">
                <input type="hidden" id="bal" value="<?php echo $bal; ?>">
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
    document.getElementById("rows_count_span").textContent = document.getElementById("row_count").value;
    document.getElementById("amount_total_span").textContent = document.getElementById("tt_total_price").value;
    document.getElementById("tt_amount_total_span").textContent = document.getElementById("tt_amount_total").value;
    document.getElementById("bal_span").textContent = document.getElementById("bal").value;
</script>
