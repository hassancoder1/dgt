<?php if (isset($_GET['type']) && isset($_GET['table']) && isset($_GET['url'])) {
    require("../connection.php");
    $allowed_types = array('local', 'booking', 'market');
    $type = mysqli_real_escape_string($connect, $_GET['type']);
    if (in_array($type, $allowed_types)) {
        $table = mysqli_real_escape_string($connect, $_GET['table']);
        $backURL = '../' . mysqli_real_escape_string($connect, $_GET['url']) . '?print=1';
        $remove = $goods_name = $start = $end = $is_transferred = $s_khaata_id = '';

        if (isset($_GET['goods_name'])) {
            $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
            $backURL .= '&goods_name=' . $goods_name;
        }
        if (isset($_GET['start'])) {
            $start = mysqli_real_escape_string($connect, $_GET['start']);
            $backURL .= '&start=' . $start;
        }
        if (isset($_GET['end'])) {
            $end = mysqli_real_escape_string($connect, $_GET['end']);
            $backURL .= '&end=' . $end;
        }
        if (isset($_GET['is_transferred'])) {
            $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
            $backURL .= '&is_transferred=' . $is_transferred;
        }
        if (isset($_GET['s_khaata_id'])) {
            $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
            $backURL .= '&s_khaata_id=' . $s_khaata_id;
        }
        $sql = "SELECT * FROM `sales` WHERE type = '$type'";

        $sales = mysqli_query($connect, $sql); ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title><?php echo strtoupper($type) . '_SALE_' . date('Y_m_d'); ?></title>
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
                <div class="col-md-10- col-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                            <?php echo '<br><b>DGT.L.L.C</b> '; ?>
                        </div>
                        <div class="d-flex gap-2">
                            <div><b>ROWS: </b><span id="rows_count_span"></span></div>
                            <div><b>QTY: </b><span id="p_qty_total_span"></span></div>
                            <div><b>KGs: </b><span id="p_kgs_total_span"></span></div>
                        </div>
                        <div class="text-end">
                            <h1 class="fw-bold mb-0 text-uppercase"><?php echo $table . ' ' . $type ?></h1>
                            <?php echo $start != '' ? 'From:'.$start : '';
                            echo $end != '' ? ' To:' . $end : ''; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10- col-12">
                    <table class="table table-bordered mb-0">
                        <thead>
                        <tr class="text-nowrap">
                            <td>TYPE</td>
                            <td>DETAILS</td>
                            <td>GOODS DETAILS</td>
                            <td>AMOUNT</td>
                            <td>REPORT</td>
                            <td>SOLD TO</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                        while ($sale = mysqli_fetch_assoc($sales)) {
                            $sale_id = $sale['id'];
                            $sale_type = $sale['type'];
                            $transfer = $sale['transfer'];
                            $s_khaata_no = $sale['s_khaata_no'];

                            $cntrs = saleSpecificData($sale_id, 'sale_rows');
                            $totals = saleSpecificData($sale_id, 'product_details');

                            $Goods = $cntrs > 0 ? '<b>GOODS. </b>' . $totals['Goods'][0] . '<br>' : '';
                            $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                            //$Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . '<br>' : '';
                            //$Origin = $cntrs > 0 ? '<b>ORIGIN. </b>' . $totals['Origin'][0] . '<br>' : '';
                            $ITEMS = $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . ' ' : '';
                            $Qty = $cntrs > 0 ? '<b>Qty. </b>' . $totals['Qty'] . '<br>' : '';
                            $KGs = $cntrs > 0 ? '<b>KGs. </b>' . $totals['KGs'] . '<br>' : '';

                            if ($start != '') {
                                if ($sale['s_date'] < $start) continue;
                            }
                            if ($end != '') {
                                if ($sale['s_date'] > $end) continue;
                            }
                            $GoodsKaNaam = $cntrs > 0 ? $totals['Goods'][0] : '';
                            if ($goods_name != '') {
                                if ($goods_name != $GoodsKaNaam) continue;
                            }
                            if ($is_transferred != '') {
                                if ($is_transferred == 0) {
                                    if ($transfer == 1 || $transfer == 2) continue;
                                }
                                if ($is_transferred == 1) {
                                    if ($transfer == 0 || $transfer == 2) continue;
                                }
                                if ($is_transferred == 2) {
                                    if ($transfer == 0 || $transfer == 1) continue;
                                }
                            }
                            if ($s_khaata_id != '') {
                                if ($s_khaata_id != $sale['s_khaata_id']) continue;
                            }

                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                            $rowColor = $transfer == 0 ? 'bg-danger bg-opacity-10' : ''; ?>
                            <tr class="<?php echo $rowColor; ?>">
                                <td class="pointer text-nowrap" onclick="viewSale(<?php echo $sale_id; ?>)"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <?php echo '<b>S#</b>' . $sale_id . saleSpecificData($sale_id, 'sale_type');
                                    echo '<br>' . seaRoadBadge($sale['sea_road']);
                                    echo saleSpecificData($sale_id, 'transfer_type');
                                    echo '<br><span class="font-size-11"><b>D.</b>' . date('y-m-d', strtotime($sale['s_date'])) . '</span>';
                                    ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo '<b>B.</b>' . branchName($sale['branch_id']);
                                    echo ' <b>CITY</b>' . $sale['city'] . '<br><b>S.NAME</b>' . $sale['s_name'] . '<br><b>RECEIEVER</b>' . $sale['receiver']; ?>
                                </td>
                                <td class="font-size-11 text-nowrap"><?php echo $Goods . $ITEMS . $Qty . $KGs; ?></td>
                                <td class="text-dark text-nowrap">
                                    <?php if ($cntrs > 0) {
                                        echo '<b>Amnt </b>' . $totals['Amount'] . '<sub>' . $totals['curr1'] . '</sub>';
                                        echo '<br><b>Final </b>' . $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                    } ?>
                                </td>
                                <td class="font-size-11">
                                    <?php echo $sale['report']; ?>
                                </td>
                                <td class="font-size-11">
                                    <?php if ($sale['s_khaata_no'] == '') {
                                        echo '<div class="bg-danger">&nbsp;</div>';
                                    } else {
                                        echo '<b>A/c#</b>' . $sale['s_khaata_no'];
                                        $sold_to = khaataSingle($sale['s_khaata_no'], true);
                                        echo '<br>' . $sold_to['comp_name'];
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
    }
} else {
    echo '<script>window.location.href="../";</script>';
} ?>
<script>
    document.getElementById("rows_count_span").textContent = document.getElementById("row_count").value;
    document.getElementById("p_qty_total_span").textContent = document.getElementById("p_qty_total").value;
    document.getElementById("p_kgs_total_span").textContent = document.getElementById("p_kgs_total").value;
</script>
