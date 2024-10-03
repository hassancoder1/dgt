<?php $backURL = '../purchase-account';
if (isset($_POST['khaata_id']) && ($_POST['khaata_id'] > 0) && isset($_POST['secret']) && isset($_POST['goods_id'])
    && isset($_POST['size']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['action'])) {
    if (base64_decode($_POST['secret']) == "powered-by-upsol") {
        require("../connection.php");
        global $connect;
        $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
        $khaata = khaataSingle($khaata_id);
        $goods_id = mysqli_real_escape_string($connect, $_POST['goods_id']);
        $size = mysqli_real_escape_string($connect, $_POST['size']);
        $start_date = mysqli_real_escape_string($connect, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($connect, $_POST['end_date']);
        $action = mysqli_real_escape_string($connect, $_POST['action']);
        $printSizeRadio = mysqli_real_escape_string($connect, $_POST['printSizeRadio']);
        $is_large = $printSizeRadio == 'lg' ? true : false;
        $row_count = $total_qty = $total_kgs = $net_kgs = $total_amount = $total_final_amount = 0;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Purchase_account_<?php echo date('Y_m_d'); ?></title>
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
                            <div>Rows <span id="rows_span" class="fw-bold"></span></div>
                            <div>Total Qty <span id="total_qty_span" class="fw-bold"></span></div>
                        </div>
                        <div>
                            <div>Total KGs <span id="total_kgs_span" class="fw-bold"></span></div>
                            <div>Total Net KGs <span id="net_kgs_span" class="fw-bold"></span></div>
                        </div>
                        <div>
                            <div>Total Amount <span id="total_amount_span" class="fw-bold"></span></div>
                            <?php if ($is_large) {
                                echo '<div>Total Final Amount <span id="total_final_amount_span" class="fw-bold"></span></div>';
                            } ?>
                        </div>
                        <div class="text-end">
                            <h1 class="fw-bold mb-0 text-uppercase">Purcahse Account</h1>
                            <?php echo '<b>A/c No:</b> ' . strtoupper($khaata['khaata_no']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center  ">
                <div class="col-12">
                    <div
                        class="d-flex justify-content-between border border-bottom-0 border-dark small px-1 text-uppercase">
                        <div class="text-nowrap">
                            <b>A/c Name: </b><?php echo $khaata['khaata_name']; ?><br>
                            <b>Branch </b><?php echo branchName($khaata['branch_id']); ?>
                            <b> Category </b><?php echo catName($khaata['cat_id']); ?>
                        </div>
                        <div class="w-50">
                            <b>Business Name: </b><?php echo $khaata['business_name']; ?><br>
                            <b>Company: </b><?php echo $khaata['comp_name']; ?>
                        </div>
                        <div><b>Address: </b><?php echo $khaata['address']; ?></div>
                        <div>
                            <img src="../<?php echo $khaata['image']; ?>" alt="img" class="img-fluid avatar-sm">
                        </div>
                    </div>
                    <table class="table mb-0 table-bordered table-sm ">
                        <thead>
                        <tr>
                            <td>S#</td>
                            <td>Allot</td>
                            <td>Bill#</td>
                            <td>Date</td>
                            <td>Importer Name</td>
                            <td>Type</td>
                            <td>Bail#</td>
                            <td>Container#</td>
                            <td>Goods Name</td>
                            <td>Size</td>
                            <td>Qty Name</td>
                            <td>Qty#</td>
                            <td>Gross KG</td>
                            <td>Net KG</td>
                            <td>Per Price</td>
                            <td>Amount</td>
                            <?php if ($is_large) {
                                echo '<td>Exch. Name</td><td>Exch. Rate</td><td>Final Amount</td>';
                            } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $sale_details_query = fetch('purchase_details');
                        while ($details = mysqli_fetch_assoc($sale_details_query)) {
                            $parent_id = $details['parent_id'];
                            $goods_id_this = $details['goods_id'];
                            if ($action == 'true') {
                                if ($goods_id > 0) {
                                    if ($goods_id_this != $goods_id) continue;
                                }
                                if ($size != '') {
                                    if ($details['size'] != $size) continue;
                                }
                            }
                            $imp_json = json_decode($details['imp_json']);
                            $imp_name = $party_khaata_id = '';
                            if (!empty($imp_json)) {
                                $imp_name = $imp_json->comp_name ?? '';
                                $party_khaata_id = $imp_json->party_khaata_id ?? '';
                            }
                            $bail_json = json_decode($details['bail_json']);
                            $bail_no = $container_no = '';
                            if (!empty($bail_json)) {
                                $bail_no = $bail_json->bail_no ?? '';
                                $container_no = $bail_json->container_no ?? '';
                            }
                            $sales = fetch('purchases', array('id' => $parent_id));
                            $sale = mysqli_fetch_assoc($sales);
                            $s_khaata_id = $sale['s_khaata_id'];
                            if ($s_khaata_id == $khaata_id) {
                                if ($action == 'true') {
                                    //echo $khaata_id.'<br>';
                                    if ($start_date != '') {
                                        if ($sale['p_date'] < $start_date) continue;
                                    }
                                    if ($end_date != '') {
                                        if ($sale['p_date'] > $end_date) continue;
                                    }
                                }
                                echo '<tr class="font-size-12 text-nowrap">';
                                echo '<td>' . $parent_id . '-' . $details['d_sr'] . purchaseSpecificData($parent_id, 'purchase_type') . '</td>';
                                echo '<td>' . $details['allot_name'] . '</td>';
                                echo '<td>NILL</td>';
                                echo '<td>' . my_date($sale['p_date']) . '</td>';
                                echo '<td>' . $imp_name . '</td>';
                                echo '<td class="text-uppercase">' . seaRoadBadge($sale['sea_road']) . '</td>';
                                echo '<td>' . $bail_no . '</td>';
                                echo '<td>' . $container_no . '</td>';
                                echo '<td>' . goodsName($goods_id_this) . '</td>';
                                echo '<td>' . $details['size'] . '</td>';
                                echo '<td>' . $details['qty_name'] . '</td>';
                                echo '<td>' . $details['qty_no'] . '</td>';
                                echo '<td>' . $details['total_qty_kgs'] . '</td>';
                                echo '<td>' . round($details['net_kgs']) . '</td>';
                                echo '<td>' . $details['price'] . '</td>';
                                echo '<td>' . round($details['amount'], 2) . '</td>';
                                if ($is_large) {
                                    echo '<td>' . $details['currency2'] . '</td>';
                                    echo '<td>' . $details['rate2'] . '</td>';
                                    echo '<td>' . round($details['final_amount'], 2) . '</td>';
                                }
                                echo '</tr> ';
                                $row_count++;

                                $total_qty += $details['qty_no'];
                                $total_kgs += $details['total_qty_kgs'];
                                $net_kgs += $details['net_kgs'];
                                $total_amount += $details['amount'];
                                $total_final_amount += $details['final_amount'];
                            }
                        } ?>
                        </tbody>
                    </table>
                    <input type="hidden" id="rows" value="<?php echo $row_count; ?>">
                    <input type="hidden" id="total_qty" value="<?php echo $total_qty; ?>">
                    <input type="hidden" id="total_kgs" value="<?php echo $total_kgs; ?>">
                    <input type="hidden" id="net_kgs" value="<?php echo $net_kgs; ?>">
                    <input type="hidden" id="total_amount" value="<?php echo $total_amount; ?>">
                    <input type="hidden" id="total_final_amount" value="<?php echo $total_final_amount; ?>">
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="Back to ledger" data-tooltip-position="right">
                    <a href="<?php echo $backURL; ?>"><i class="fa fa-long-arrow-alt-left"></i></a>
                </li>
                <li class="facebook" title="PDF Print">
                    <a class="cursor-pointer" onclick="window.print();"><i class="fa fa-print"></i></a>
                </li>
            </ul>
        </div>
        </body>
        </html>
        <?php if (isset($_GET['print'])) {
            echo '<script>window.print();</script>';
        }
    } else {
        echo '<script>window.location.href="' . $backURL . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backURL . '";</script>';
} ?>

<script>
    document.getElementById("rows_span").textContent = document.getElementById("rows").value;
    document.getElementById("total_qty_span").textContent = document.getElementById("total_qty").value;
    document.getElementById("total_kgs_span").textContent = document.getElementById("total_kgs").value;
    document.getElementById("net_kgs_span").textContent = document.getElementById("net_kgs").value;
    document.getElementById("total_amount_span").textContent = document.getElementById("total_amount").value;
    document.getElementById("total_final_amount_span").textContent = document.getElementById("total_final_amount").value;
</script>