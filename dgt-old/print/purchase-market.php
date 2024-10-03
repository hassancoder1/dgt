<?php if (isset($_GET['start']) && isset($_GET['end']) && isset($_GET['goods_name']) && isset($_GET['s_khaata_id'])
) {
    $backURL = '../vat-sales';
    require("../connection.php");
    global $connect;
    $sql = "SELECT * FROM `purchases` WHERE type = 'market' ORDER BY khaata_tr1 asc ";
    $purchases = mysqli_query($connect, $sql);


    $start = mysqli_real_escape_string($connect, $_GET['start']);
    $end = mysqli_real_escape_string($connect, $_GET['end']);
    $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
    $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PURCHASE_MARKET_<?php echo date('Y_m_d'); ?></title>
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
                        <div><b>QTY: </b><span id="p_qty_total_span"></span></div>
                        <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>
                    </div>
                    <div class="text-end">
                        <h2 class="fw-bold mb-0 text-uppercase">PURCHASE MARKET</h2>
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
                        <td>TYPE</td>
                        <td>DETAILS</td>
                        <td>GOODS DETAILS</td>
                        <td>AMOUNT</td>
                        <td>VAT</td>
                        <td>REPORT</td>
                        <td>PURCHASER</td>
                        <td>TRANSFER</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                    while ($sale = mysqli_fetch_assoc($purchases)) {
                        $sale_id = $sale['id'];
                        $sale_type = $sale['type'];
                        $is_acc = $sale['is_acc'];
                        $rowColor = '';
                        $seller_json = json_decode($sale['seller_json']);
                        $khaata_tr1 = json_decode($sale['khaata_tr1']);
                        if (empty($khaata_tr1)) {
                            $rowColor = 'bg-danger bg-opacity-25';
                            //continue;
                        }
                        $cntrs = purchaseSpecificData($sale_id, 'purchase_rows');
                        $totals = purchaseSpecificData($sale_id, 'product_details');
                        $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                        $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                        $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . '<br>' : '';
                        $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';

                        if ($start != '') {
                            if ($sale['p_date'] < $start) continue;
                        }
                        if ($end != '') {
                            if ($sale['p_date'] > $end) continue;
                        }
                        if ($goods_name != '') {
                            if ($goods_name != $totals['Goods'][0]) continue;
                        }
                        if ($s_khaata_id != '') {
                            if ($s_khaata_id != $seller_json->khaata_no) continue;
                        }
                        $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                        $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;

                        $sd_data = fetch('purchase_details', array('parent_id' => $sale_id));
                        $vat_items = $vat_items_amount = 0;
                        while ($sd_datum = mysqli_fetch_assoc($sd_data)) {
                            if ($sd_datum['rate2'] > 0) {
                                $vat_items_amount += $sd_datum['rate2'];
                                $vat_items++;
                            }
                        }
                        //if ($vat_items_amount > 0) continue; ?>
                        <tr class="pointer clickable-row <?php echo $rowColor; ?>"
                            data-href="<?php echo $pageURL . $view_url . '&id=' . $sale_id; ?>">
                            <td class="text-nowrap font-size-11">
                                <?php echo '<b>P#</b>' . $sale_id . purchaseSpecificData($sale_id, 'purchase_type');
                                echo '<br><span class="font-size-11">';
                                echo '<b>D.</b>' . date('y-m-d', strtotime($sale['p_date']));
                                echo $sale['city'] != '' ? '<br><b>B.NAME</b>' . $sale['city'] : '';
                                echo '</span>'; ?>
                            </td>
                            <td class="font-size-11 text-nowrap">
                                <?php echo '<b>B.</b> ' . branchName($sale['branch_id']) . '<br>'; ?>
                                <?php echo '<b>OWNER</b> ' . $sale['p_khaata_no']; ?>
                            </td>
                            <td class="font-size-11 text-nowrap"><?php echo $Goods . $ITEMS . $Qty . $KGs; ?></td>
                            <td class="text-dark text-nowrap">
                                <?php if ($cntrs > 0) {
                                    echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                    echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr1'] . '</sub>';
                                    //echo '<br><b>Transfer </b>' . $sale['t_date'];
                                } ?>
                            </td>
                            <td><?php echo $vat_items_amount; ?></td>
                            <td class="font-size-10">
                                <div style="width: 130px"><?php echo readMoreTooltip($sale['report'], 80) ?></div>
                            </td>
                            <td class="font-size-10 text--nowrap">
                                <?php if (!empty($seller_json)) {
                                    echo '<b>A/C.</b>' . $seller_json->khaata_no . '<br>';
                                    if ($is_acc == 1) {
                                        $seller_khaata = khaataSingle($seller_json->khaata_id);
                                        echo '<b>COMP.</b>' . $seller_khaata['comp_name'];
                                    } else {
                                        echo '<b>PURCAHSE NAME</b>' . $seller_json->s_name . '<br>';
                                        echo '<b>COMP.</b>' . $seller_json->s_company . '<br>';
                                        echo '<b>VAT#</b>' . $seller_json->s_weight_no . '<br>';
                                    }
                                } ?>
                            </td>
                            <td class="font-size-11 text-nowrap">
                                <?php if (!empty($khaata_tr1)) {
                                    echo '<b>Dr.A/C</b>' . $khaata_tr1->dr_khaata_no;
                                    echo isset($khaata_tr1->vat_khaata_no) ? '<b>&</b>' . $khaata_tr1->vat_khaata_no : '';
                                    echo ' <b>Cr.A/C</b>' . $khaata_tr1->cr_khaata_no;
                                    echo '<br><b>Transfer</b>' . date('y-m-d', strtotime($khaata_tr1->transfer_date));
                                    echo '<br><b>R. SR#</b> ';
                                    //echo getTransferredToRoznamchaSerial('Business', $sale_id, 'purchase_market');
                                } ?>
                            </td>
                        </tr>
                        <?php $row_count++;
                    } ?>
                    </tbody>
                    <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                    <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                    <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                </table>
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
    document.getElementById("p_qty_total_span").textContent = document.getElementById("p_qty_total").value;
    document.getElementById("p_kgs_total_span").textContent = document.getElementById("p_kgs_total").value;
</script>
