<?php if (isset($_GET['start']) && isset($_GET['end']) && isset($_GET['goods_name'])
    && isset($_GET['is_transferred']) && isset($_GET['search_acc']) && isset($_GET['sale_pur'])
) {
    $backURL = '../loading';
    require("../connection.php");
    global $connect;
    $sql = "SELECT 'purchase' AS source_table,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg
    FROM purchase_details
    UNION 
    SELECT 'sale' AS source_table,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg
    FROM sale_details 
    ORDER BY is_transfer";
    $start = mysqli_real_escape_string($connect, $_GET['start']);
    $end = mysqli_real_escape_string($connect, $_GET['end']);
    $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
    $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
    $search_acc = mysqli_real_escape_string($connect, $_GET['search_acc']);
    $sale_pur = mysqli_real_escape_string($connect, $_GET['sale_pur']); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>General_Loading_Form_<?php echo date('Y_m_d'); ?></title>
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
                        <h2 class="fw-bold mb-0 text-uppercase">General Loading Form</h2>
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
                        <td>PURCHASER &amp; SELLER</td>
                        <td>GOODS</td>
                        <td>REPORT</td>
                        <td>BAIL</td>
                        <td>IMPORTER</td>
                        <td>EXPORTER</td>
                        <td>NOTIFY PARTY</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $row_count = $p_qty_total = $p_kgs_total = 0;
                    $query2 = mysqli_query($connect, $sql);
                    while ($details = mysqli_fetch_assoc($query2)) {
                        $source_table = $details['source_table'];
                        $d_id = $details['id'];
                        $parent_id = $details['parent_id'];
                        $imp_json = json_decode($details['imp_json']);
                        $exp_json = json_decode($details['exp_json']);
                        $notify_json = json_decode($details['notify_json']);
                        $ware_json = json_decode($details['ware_json']);
                        $tware_json = json_decode($details['tware_json']);
                        $bail_json = json_decode($details['bail_json']);
                        $d_sr = $details['d_sr'];
                        //purchases / sales parent tables data
                        $parent_query = fetch($source_table . 's', array('id' => $parent_id));
                        $parent_data = mysqli_fetch_assoc($parent_query);
                        $purchase_sale_type = $parent_data['type'];
                        if ($source_table == 'purchase') {
                            if ($parent_data['is_locked'] != 1) continue;
                            $type_badge = '<br>' . purchaseSpecificData($parent_id, 'purchase_type');
                            $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                            $purchase_sale_date = date('y-m-d', strtotime($parent_data['p_date']));
                            $s_khaata_no = $parent_data['s_khaata_no'];
                            $s_khaata = khaataSingle($parent_data['s_khaata_id']);
                            $totals = purchaseSpecificData($parent_id, 'product_details');
                        } else {
                            $type_badge = '<br>' . saleSpecificData($parent_id, 'sale_type');
                            $cntrs = saleSpecificData($parent_id, 'sale_rows');
                            $purchase_sale_date = date('y-m-d', strtotime($parent_data['s_date']));
                            $seller_json = json_decode($parent_data['seller_json']);
                            $s_khaata_no = !empty($seller_json) ? $seller_json->khaata_no : '';
                            $totals = saleSpecificData($parent_id, 'product_details');
                            $s_khaata = !empty($seller_json) ? khaataSingle($seller_json->khaata_id) : array();
                        }
                        if ($purchase_sale_type == 'market') continue;
                        if ($parent_data['transfer'] < 2) continue;

                        $is_transfer = $details['is_transfer'];
                        $rowColor = $is_transfer <= 0 ? 'bg-danger bg-opacity-10 border border-danger' : '';


                        if ($start != '') {
                            if ($purchase_sale_date < $start) continue;
                        }
                        if ($end != '') {
                            if ($purchase_sale_date > $end) continue;
                        }
                        if ($goods_name != '') {
                            if ($goods_name != $totals['Goods'][0]) continue;
                        }
                        if ($is_transferred != '') {
                            if ($is_transferred == 1) {
                                if ($is_transfer <= 0) continue;
                            }
                            if ($is_transferred == 0) {
                                if ($is_transfer > 0) continue;
                            }
                        }
                        if ($search_acc != '') {
                            if ($search_acc != $s_khaata_no && $search_acc != $parent_data['p_khaata_no']) continue;
                        }
                        if ($sale_pur != '') {
                            if ($sale_pur != $source_table) continue;
                        }
                        $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                        $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0; ?>
                        <tr class="text-uppercase <?php //echo $rowColor; ?>">
                            <td class="text-nowrap">
                                <?php echo $source_table == 'purchase' ? '<b>P#</b>' : '<b>S#</b>';
                                echo $parent_id . '-' . $d_sr;
                                echo $is_transfer > 0 ? '<span class="font-size-10"><i class="fa fa-check-double text-success"></i>TRANSF</span>' : '';
                                echo $type_badge . '<br><span class="font-size-11"><b>D.</b>' . $purchase_sale_date . '</span>'; ?>
                            </td>
                            <td class="font-size-10 text-nowrap">
                                <?php echo '<b>BRANCH</b>' . branchName($parent_data['branch_id']) . '<br>';
                                echo '<b>PURCHASE A/c#</b>' . $parent_data['p_khaata_no'] . '<br>';
                                echo '<b>SELLER A/c#</b>' . $s_khaata_no;
                                echo isset($s_khaata['khaata_name']) ? '<br><b>A/c&nbsp;Name</b>' . $s_khaata['khaata_name'] : ''; ?>
                            </td>
                            <td class="font-size-10 text-nowrap">
                                <?php echo $cntrs > 0 ? '<b>ITEMS. </b>' . $cntrs . '<br><b>Qty </b>' . $details['qty_no'] . '<br><b>KGs </b>' . $details['total_kgs'] . '<br><b>Goods </b>' . $totals['Goods'][0] : ''; ?>
                            </td>
                            <td class="font-size-10">
                                <?php if ($source_table == 'purchase') {
                                    $details_k = ['indexes' => $parent_data['rep_indexes'], 'vals' => $parent_data['rep_vals']];
                                    $reps = displayKhaataDetails($details_k, true);
                                    if (array_key_exists('Final', $reps)) {
                                        echo $reps['Final'];
                                    }
                                } else {
                                    echo $parent_data['report'];
                                } ?>
                            </td>
                            <td class="font-size-10 text-nowrap">
                                <?php if (!empty($bail_json)) {
                                    if ($purchase_sale_type == 'booking') {
                                        echo '<b>CONTAINER#</b>' . $bail_json->container_no . '<br><b>BAIL#</b>' . $bail_json->bail_no . '<br><b>LOADING D.</b>' . $bail_json->loading_date . '<br><b>RECEIVING D.</b>' . $bail_json->receiving_date;
                                    } else {
                                        echo isset($bail_json->driver_name) ? '<b>D.Name </b>' . $bail_json->driver_name : '';
                                        echo isset($bail_json->driver_phone) ? '<br><b>D.Phone </b>' . $bail_json->driver_phone : '';
                                        echo '<br><b>LOADING D.</b>' . $bail_json->loading_date . '<br><b>RECEIVING D.</b>' . $bail_json->receiving_date;
                                    }
                                } ?>
                            </td>
                            <?php if ($purchase_sale_type == 'booking') { ?>
                                <td class="font-size-10">
                                    <?php if (!empty($imp_json)) {
                                        echo '<b>COMPANY</b>' . $imp_json->comp_name . '<br><b>COUNTRY</b>' . $imp_json->country;
                                    } ?>
                                </td>
                                <td class="font-size-10">
                                    <?php if (!empty($exp_json)) {
                                        echo '<b>COMPANY</b>' . $exp_json->comp_name . '<br><b>COUNTRY</b>' . $exp_json->country;
                                    } ?>
                                </td>
                                <td class="font-size-10">
                                    <?php if (!empty($notify_json)) {
                                        echo '<b>COMPANY</b>' . $notify_json->comp_name . '<br><b>COUNTRY</b>' . $notify_json->country;
                                    } ?>
                                </td>
                            <?php } else { ?>
                                <td class="font-size-10">
                                    <?php if (!empty($ware_json)) {
                                        echo '<b>WH.NAME</b>' . $ware_json->comp_name . '<br><b>COUNTRY</b>' . $ware_json->country . '<br><b>CITY</b>' . $ware_json->city;
                                    } ?>
                                </td>
                                <td class="font-size-10">
                                    <?php if (!empty($tware_json)) {
                                        echo '<b>Transfer WH.NAME</b>' . $tware_json->comp_name . '<br><b>COUNTRY</b>' . $tware_json->country . '<br><b>CITY</b>' . $tware_json->city;
                                    } ?>
                                </td>
                                <td></td>
                            <?php } ?>
                        </tr>
                        <?php $row_count++;
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                <input type="hidden" id="p_kgs_total" value="<?php echo round($p_kgs_total); ?>">
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
