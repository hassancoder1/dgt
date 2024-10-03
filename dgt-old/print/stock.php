<?php if (isset($_GET['wh_khaata']) && isset($_GET['goods_id']) && isset($_GET['size']) && isset($_GET['pur_sale']) && isset($_GET['start']) && isset($_GET['end'])) {
    require("../connection.php");
    $wh_khaata = mysqli_real_escape_string($connect, $_GET['wh_khaata']);
    $goods_id = mysqli_real_escape_string($connect, $_GET['goods_id']);
    $size = mysqli_real_escape_string($connect, $_GET['size']);
    $pur_sale = mysqli_real_escape_string($connect, $_GET['pur_sale']);
    $start = mysqli_real_escape_string($connect, $_GET['start']);
    $end = mysqli_real_escape_string($connect, $_GET['end']);
    $backURL = '../stock/wh_khaata=' . $wh_khaata . '&goods_id=' . $goods_id . '&size=' . $size . '&pur_sale=' . $pur_sale . '&start=' . $start . '&end=' . $end; ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title><?php echo 'STOCK_' . date('Y_m_d_His'); ?></title>
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
                    <div><b>ROWS: </b><span id="rows_count_span"></span></div>
                    <div>
                        <div><b>PURCHASE QTY: </b><span id="p_qty_total_span"></span></div>
                        <div><b>PURCHASE KGs: </b><span id="p_kgs_total_span"></span></div>
                    </div>
                    <div>
                        <div><b>SALE QTY: </b><span id="s_qty_total_span"></span></div>
                        <div><b>SALE KGs: </b><span id="s_kgs_total_span"></span></div>
                    </div>
                    <div class="text-end">
                        <h1 class="fw-bold mb-0 text-uppercase">STOCK</h1>
                        <?php
                        echo $start != '' ? date('d-M-y', strtotime($start)) : '';
                        echo $end != '' ? ' - ' . date('d-M-y', strtotime($end)) : '';
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10- col-12">
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr class="text-nowrap">
                        <td>#</td>
                        <td>SR#</td>
                        <td>P.DATE</td>
                        <td>P.A/C</td>
                        <td>S.A/C</td>
                        <td>B.</td>
                        <td>CONTAINER#</td>
                        <td>BAIL#</td>
                        <td>CTR REC.D.</td>
                        <td>ALLOT</td>
                        <td>GOODS</td>
                        <td>SIZE</td>
                        <td>P.QTY</td>
                        <td>S.QTY</td>
                        <td>P.KGs</td>
                        <td>S.KGs</td>
                        <td>WAREHOUSE</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sql_union = "
                        SELECT 'purchase' AS source_table,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg,wh_k_id,wh_kd_id
                        FROM purchase_details
                        UNION 
                        SELECT 'sale' AS source_table    ,id,parent_id,d_sr,goods_id,size,brand,origin,wh_kd_id,qty_name,qty_no,qty_kgs,total_kgs,empty_kgs,total_qty_kgs,net_kgs,divide,weight,total,price,currency1,rate1,amount,is_qty,currency2,rate2,opr,final_amount,imp_json,exp_json,notify_json,ware_json,tware_json,aware_json,bail_json,is_transfer,t_date,transfer_as,as_date,is_imp_agent,no_imp_agent_msg,is_exp_agent,no_exp_agent_msg,wh_k_id,wh_kd_id
                        FROM sale_details 
                        ORDER BY transfer_as";
                    $query_union = mysqli_query($connect, $sql_union);
                    $no = $row_count = $p_qty_total = $s_qty_total = $p_kgs_total = $s_kgs_total = 0;
                    while ($details = mysqli_fetch_assoc($query_union)) {
                        $source_table = $details['source_table'];
                        $d_id = $details['id'];
                        $d_sr = $details['d_sr'];
                        $parent_id = $details['parent_id'];
                        $is_transfer = $details['is_transfer'];
                        $transfer_as = $details['transfer_as'];
                        if ($is_transfer == 0) continue;
                        if ($details['transfer_as'] == 0) {
                            continue;
                        } elseif ($transfer_as == 1) {
                            $condo = fetch('purchase_agents', array('d_id' => $d_id));
                            $count_purchase_agents_empty_details = 0;
                            while ($zoz = mysqli_fetch_assoc($condo)) {
                                if (empty($zoz['details'])) {
                                    ++$count_purchase_agents_empty_details;
                                }
                            }
                            if ($count_purchase_agents_empty_details > 0) continue;
                        }
                        $imp_json = json_decode($details['imp_json']);
                        $exp_json = json_decode($details['exp_json']);
                        $notify_json = json_decode($details['notify_json']);
                        $ware_json = json_decode($details['ware_json']);
                        $tware_json = json_decode($details['tware_json']);
                        $bail_json = json_decode($details['bail_json']);
                        $parent_query = fetch($source_table . 's', array('id' => $parent_id));
                        $parent_data = mysqli_fetch_assoc($parent_query);
                        if ($parent_data['transfer'] < 2) continue;
                        $purchase_sale_type = $parent_data['type'];
                        $container_no = $bail_no = $ctr_rec_date = $warehouse = '';
                        if ($source_table == 'purchase') {
                            if ($parent_data['is_locked'] != 1) continue;
                            $label = '<b>P#</b>';
                            $type_badge = purchaseSpecificData($parent_id, 'purchase_type');
                            $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                            $purchase_sale_date = date('y-m-d', strtotime($parent_data['p_date']));
                            $s_khaata_no = $parent_data['s_khaata_no'];
                            $s_khaata = khaataSingle($parent_data['s_khaata_id']);

                            $p_khaata_no = $parent_data['p_khaata_no'];
                            $p_khaata = khaataSingle($parent_data['p_khaata_id']);
                            $allot = $parent_data['allot'];
                            $totals = purchaseSpecificData($parent_id, 'product_details');
                            if (!empty($tware_json)) {
                                $transfer_wh_khaata = khaataSingle($tware_json->party_khaata_id);
                                $warehouse = $transfer_wh_khaata['khaata_no'] . ' ' . $transfer_wh_khaata['khaata_name'];
                                if ($wh_khaata != '') {
                                    if ($wh_khaata != $transfer_wh_khaata['khaata_no']) continue;
                                }
                            }
                        } else {
                            $khaata_tr1 = json_decode($parent_data['khaata_tr1']);
                            if (empty($khaata_tr1)) continue;
                            $label = '<b>S#</b>';
                            $type_badge = saleSpecificData($parent_id, 'sale_type');
                            $cntrs = saleSpecificData($parent_id, 'sale_rows');
                            $purchase_sale_date = date('y-m-d', strtotime($parent_data['s_date']));
                            $seller_json = json_decode($parent_data['seller_json']);
                            $p_khaata_no = !empty($seller_json) ? $seller_json->khaata_no : '';
                            $p_khaata = !empty($seller_json) ? khaataSingle($seller_json->khaata_id) : array();
                            $s_khaata_no = $parent_data['p_khaata_no'];
                            $s_khaata = khaataSingle($parent_data['p_khaata_no'], true);
                            $allot = '';
                            $totals = saleSpecificData($parent_id, 'product_details');
                            $wh_khaataa = khaataSingle($details['wh_k_id']);
                            $wh_khaata_details = khaataDetailsData($details['wh_kd_id']);
                            $warehouse = $wh_khaataa['khaata_no'] . ' ';
                            $warehouse .= isset($wh_khaata_details['comp_name']) ? $wh_khaata_details['comp_name'] : '';
                            if ($wh_khaata != '') {
                                if ($wh_khaata != $wh_khaataa['khaata_no']) continue;
                            }
                        }
                        if (!empty($bail_json)) {
                            if ($purchase_sale_type == 'booking') {
                                $container_no = isset($bail_json->container_no) ? $bail_json->container_no : '';
                            } else {
                                $container_no = isset($bail_json->truck_no) ? $bail_json->truck_no : '';
                            }
                        }
                        if ($purchase_sale_type == 'booking') {
                            if (!empty($bail_json)) {
                                $bail_no = isset($bail_json->bail_no) ? $bail_json->bail_no : '';
                            }
                        } else {
                            if (!empty($ware_json)) {
                                $loading_wh_khaata = khaataSingle($ware_json->party_khaata_id);
                                $bail_no = $loading_wh_khaata['khaata_no'] . '<sub>LDG WH.A/C.</sub>';
                            }
                        }
                        if ($purchase_sale_type == 'booking') {
                            //get ctr_rec_date from purchase_agents =>import
                            $purchase_agents_dataa = fetch('purchase_agents', array('d_id' => $d_id, 'type' => 'import'));
                            if (mysqli_num_rows($purchase_agents_dataa) > 0) {
                                $purchase_agents_datum = mysqli_fetch_assoc($purchase_agents_dataa);
                                if (!empty($purchase_agents_datum['details'])) {
                                    $purchase_agents_details = json_decode($purchase_agents_datum['details']);
                                    $ctr_rec_date = $purchase_agents_details->ctr_rec_date;
                                }
                            }
                        } else {
                            if (!empty($bail_json)) {
                                $ctr_rec_date = $bail_json->receiving_date;
                            }
                        }
                        if ($goods_id > 0) {
                            if ($goods_id != $details['goods_id']) continue;
                        }
                        if ($size != '') {
                            if ($size != $details['size']) continue;
                        }
                        if ($start != '') {
                            if ($source_table == 'purchase') {
                                if ($parent_data['p_date'] < $start) continue;
                            } else {
                                if ($parent_data['s_date'] < $start) continue;
                            }
                        }
                        if ($end != '') {
                            if ($source_table == 'purchase') {
                                if ($parent_data['p_date'] > $end) continue;
                            } else {
                                if ($parent_data['s_date'] > $end) continue;
                            }
                        }
                        if ($pur_sale != '') {
                            if ($pur_sale != $source_table) continue;
                        }
                        ++$no; ?>
                        <tr class="text-uppercase">
                            <td><?php echo $no; ?></td>
                            <td><?php echo $label . $parent_id . '-' . $d_sr; ?></td>
                            <td><?php echo $purchase_sale_date; ?></td>
                            <td><?php echo $p_khaata_no; ?></td>
                            <td><?php echo $s_khaata_no; ?></td>
                            <td><?php echo branchName($parent_data['branch_id']); ?></td>
                            <td><?php echo $container_no; ?></td>
                            <td><?php echo $bail_no; ?></td>
                            <td><?php echo $ctr_rec_date; ?></td>
                            <td><?php echo $allot; ?></td>
                            <td><?php echo goodsName($details['goods_id']); ?></td>
                            <td><?php echo $details['size']; ?></td>
                            <?php if ($source_table == 'purchase') {
                                echo '<td>' . $details['qty_no'] . '</td>';
                                echo '<td>0</td>';
                                $p_qty_total += $details['qty_no'];
                            } else {
                                echo '<td>0</td>';
                                echo '<td>' . $details['qty_no'] . '</td>';
                                $s_qty_total += $details['qty_no'];
                            }
                            if ($source_table == 'purchase') {
                                echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                echo '<td>0</td>';
                                $p_kgs_total += $details['total_kgs'];
                            } else {
                                echo '<td>0</td>';
                                echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                $s_kgs_total += $details['total_kgs'];
                            } ?>
                            <td><?php echo $warehouse; ?></td>
                        </tr>
                        <?php $row_count++;
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="row_count" value="<?php echo $row_count; ?>">
                <input type="hidden" id="p_qty_total" value="<?php echo $p_qty_total; ?>">
                <input type="hidden" id="s_qty_total" value="<?php echo $s_qty_total; ?>">
                <input type="hidden" id="p_kgs_total" value="<?php echo $p_kgs_total; ?>">
                <input type="hidden" id="s_kgs_total" value="<?php echo $s_kgs_total; ?>">
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
<?php } else {
    echo '<script>window.location.href="../";</script>';
} ?>
<script>
    document.getElementById("rows_count_span").textContent = document.getElementById("row_count").value;
    document.getElementById("p_qty_total_span").textContent = document.getElementById("p_qty_total").value;
    document.getElementById("s_qty_total_span").textContent = document.getElementById("s_qty_total").value;
    document.getElementById("p_kgs_total_span").textContent = document.getElementById("p_kgs_total").value;
    document.getElementById("s_kgs_total_span").textContent = document.getElementById("s_kgs_total").value;
    document.getElementById("").textContent = document.getElementById("").value;
</script>