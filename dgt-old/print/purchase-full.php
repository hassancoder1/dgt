<?php if (isset($_GET['start']) && isset($_GET['end']) && isset($_GET['goods_name']) && isset($_GET['ps_type']) && isset($_GET['s_khaata_id'])
) {
    $backURL = '../purchases';
    require("../connection.php");
    global $connect;
    $sql = "SELECT * FROM `purchases` WHERE is_locked = 1 AND transfer = 2 ";
    $start = mysqli_real_escape_string($connect, $_GET['start']);
    $end = mysqli_real_escape_string($connect, $_GET['end']);
    $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
    $ps_type = mysqli_real_escape_string($connect, $_GET['ps_type']);
    $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Full_Payment_Form_<?php echo date('Y_m_d'); ?></title>
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
                        <h2 class="fw-bold mb-0 text-uppercase">Full Payment Form</h2>
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
                        <td>BRANCH</td>
                        <td>SELLER</td>
                        <td>GOODS DETAILS</td>
                        <td>AMOUNT</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $purchases = mysqli_query($connect, $sql);
                    $row_count = $p_qty_total = $p_kgs_total = 0;
                    while ($purchase = mysqli_fetch_assoc($purchases)) {
                        $purchase_id = $purchase['id'];
                        $purchase_type = $purchase['type'];
                        $p_khaata = khaataSingle($purchase['p_khaata_id']);
                        $s_khaata = khaataSingle($purchase['s_khaata_id']);
                        $cntrs = purchaseSpecificData($purchase_id, 'purchase_rows');
                        $totals = purchaseSpecificData($purchase_id, 'product_details');
                        $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                        $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                        $Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . '<br>' : '';
                        $Origin = $cntrs > 0 ? '<b>ORIGIN. </b>' . $totals['Origin'][0] . '<br>' : '';
                        $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                        $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . ' ' : '';
                        $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';

                        if ($start != '') {
                            if ($purchase['p_date'] < $start) continue;
                        }
                        if ($end != '') {
                            if ($purchase['p_date'] > $end) continue;
                        }
                        if ($goods_name != '') {
                            if ($goods_name != $totals['Goods'][0]) continue;
                        }

                        if ($ps_type != '') {
                            if ($purchase_type != $ps_type) continue;
                        }
                        if ($s_khaata_id != '') {
                            if ($s_khaata_id != $purchase['s_khaata_id']) continue;
                        }
                        $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                        $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                        //$rowColor = empty($khaata_tr1) ? 'bg-danger bg-opacity-10' : ''; ?>
                        <tr>
                            <td>
                                <?php echo '<b>P#</b>' . $purchase_id;
                                echo purchaseSpecificData($purchase_id, 'purchase_type');
                                echo '<br><span class="font-size-11"><b>P.D. </b>' . date('y-m-d', strtotime($purchase['p_date'])) . '</span>'; ?>
                            </td>
                            <td class="font-size-11 text-nowrap text-uppercase">
                                <?php echo '<b>P.A/c# </b>' . $purchase['p_khaata_no'] . '<br>'; ?>
                                <?php echo '<b>B. </b>' . branchName($purchase['branch_id']); ?>
                            </td>
                            <td class="font-size-11 text-nowrap-">
                                <?php echo '<b>A/c#</b>' . $purchase['s_khaata_no'] . '<br>';
                                echo $s_khaata['khaata_name'] ?? '' . '<br>';
                                echo $s_khaata['comp_name'] ?? ''; ?>
                            </td>
                            <td class="font-size-11 text-nowrap">
                                <?php echo $Goods . $ITEMS . $Qty . $KGs; ?>
                            </td>
                            <td class="font-size-11">
                                <?php if ($cntrs > 0) {
                                    echo '<b>Amnt </b>' . round($totals['Amount'], 2) . '<sub>' . $totals['curr1'] . '</sub>';
                                    echo '<br><b>Final </b>', round($totals['Final']) . '<sub>' . $totals['curr2'] . '</sub>';
                                    echo !empty($purchase['t2_date']) ? '<br><b>Transfer D.</b>' . date('y-m-d', strtotime($purchase['t2_date'])) : '';
                                } ?>
                            </td>
                        </tr>
                        <?php $row_count++;
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
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
