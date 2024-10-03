<?php if (isset($_GET['agent_khaata_id']) && isset($_GET['start']) && isset($_GET['end']) && isset($_GET['imp_exp'])
    && isset($_GET['is_complete'])) {
    $backURL = '../admin-agent-bills';
    require("../connection.php");
    $agent_khaata_id = mysqli_real_escape_string($connect, $_GET['agent_khaata_id']);
    $start = mysqli_real_escape_string($connect, $_GET['start']);
    $end = mysqli_real_escape_string($connect, $_GET['end']);
    $imp_exp = mysqli_real_escape_string($connect, $_GET['imp_exp']);
    $is_complete = mysqli_real_escape_string($connect, $_GET['is_complete']); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Agent_Forms_<?php echo date('Y_m_d'); ?></title>
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
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <div>
                        <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                        <?php //echo '<br><b>DGT.L.L.C</b> '; ?>
                    </div>
                    <div>
                        Rows: <span id="rows_span"></span>
                    </div>
                    <div class="text-end">
                        <h1 class="fw-bold mb-0 text-uppercase">Agent Forms</h1>
                        <?php echo $start != '' ? date('F d,Y', strtotime($start)) : '';
                        echo $end != '' ? ' To: ' . date('F d,Y', strtotime($end)) : ''; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10- col-12">
                <table class="table table-bordered mb-0">
                    <thead>
                    <tr class="text-nowrap">
                        <th>TYPE</th>
                        <th>AGENT DESC.</th>
                        <th>GOODS</th>
                        <th>PURCHASE / SALE</th>
                        <th>BAIL DESC.</th>
                        <th>CUSTOM ENTRY DESC.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $pas = mysqli_query($connect, "SELECT * FROM `purchase_agents` ORDER BY details");
                    $numRows = 0;
                    while ($pa = mysqli_fetch_assoc($pas)) {
                        //if (empty($pa['bill'])) continue;
                        $purchase_agents_type = $pa['type'];
                        $pur_sale = $pa['pur_sale'];
                        $purchase_agents_id = $pa['id'];
                        $pa_sr = $pa['a_sr'];
                        $khaata_id = $pa['khaata_id'];

                        $agent_khaata = khaataSingle($khaata_id);
                        $d_id = $pa['d_id'];
                        $bill = json_decode($pa['bill']);

                        $pd_query = fetch($pur_sale . '_details', array('id' => $d_id));
                        $details = mysqli_fetch_assoc($pd_query);
                        $d_sr = $details['d_sr'];
                        $parent_id = $details['parent_id'];
                        $bail_json = json_decode($details['bail_json']);
                        $parent_query = fetch($pur_sale . 's', array('id' => $parent_id));
                        $parent_data = mysqli_fetch_assoc($parent_query);
                        if ($pur_sale == 'purchase') {
                            if ($parent_data['is_locked'] != 1) continue;
                            $ps_date = $parent_data['p_date'];
                            $ps_type = purchaseSpecificData($parent_id, 'purchase_type');
                            $cntrs = purchaseSpecificData($parent_id, 'purchase_rows');
                            $p_khaata = khaataSingle($parent_data['p_khaata_id']);
                            $p_khaata_no = $parent_data['p_khaata_no'];
                        } else {
                            $ps_date = $parent_data['s_date'];
                            $ps_type = saleSpecificData($parent_id, 'sale_type');
                            $cntrs = saleSpecificData($parent_id, 'sale_rows');
                            $p_khaata = khaataSingle($parent_data['s_khaata_no'], true);
                            $p_khaata_no = $parent_data['s_khaata_no'];
                        }
                        if ($parent_data['type'] == 'booking' && $parent_data['transfer'] == 2) {
                        } else {
                            continue;
                        }
                        $pa_details = json_decode($pa['details']);

                        if ($agent_khaata_id != '') {
                            if ($agent_khaata_id != $khaata_id) continue;
                        }
                        if ($imp_exp != '') {
                            if ($imp_exp != $purchase_agents_type) continue;
                        }
                        if ($is_complete != '') {
                            if ($is_complete == 1) {
                                if (empty($pa_details)) continue;
                            }
                            if ($is_complete == 0) {
                                if (!empty($pa_details)) continue;
                            }
                        }
                        if ($start != '') {
                            if ($ps_date < $start) continue;
                        }
                        if ($end != '') {
                            if ($ps_date > $end) continue;
                        }

                        $rowColor = empty($pa_details) ? 'bg-danger bg-opacity-25' : ''; ?>
                        <tr class="text-uppercase <?php echo $rowColor; ?>">
                            <td class="pointer text-nowrap" data-bs-toggle="modal" data-bs-target="#KhaataDetails"
                                onclick="viewPurchase(<?php echo $parent_id; ?>,<?php echo $d_id; ?>,<?php echo $purchase_agents_id; ?>,<?php echo $khaata_id; ?>,'<?php echo $pur_sale; ?>')">
                                <?php echo $pur_sale == 'purchase' ? '<b>P#</b>' : '<b>S#</b> ';
                                echo '[' . $pa_sr . '] ' . $parent_id . '-' . $d_sr . '<br>';
                                echo '<span class="badge bg-danger">' . $purchase_agents_type . '</span>';
                                echo $ps_type;
                                echo '<br><span class="badge bg-secondary"> By ' . $parent_data['sea_road'] . '</span>';
                                echo '<br><span class="font-size-11"><b>D.</b>' . $ps_date . '</span>'; ?>
                            </td>
                            <td class="font-size-11 text-nowrap-">
                                <?php echo '<b>A/C#</b>' . $agent_khaata['khaata_no'] . '<br>' . '<b>NAME</b>' . $agent_khaata['khaata_name'];
                                //. '<br>' . '<b>COMP.</b>' . $agent_khaata['comp_name']?>
                            </td>
                            <td class="font-size-11 text-nowrap">
                                <?php echo '<b>ITEMS. </b>' . $cntrs;
                                echo '<br><b>Qty </b>' . $details['qty_no'];
                                echo '<br><b>KGs </b>' . $details['total_kgs'];
                                echo '<br><b>Goods </b>' . goodsName($details['goods_id']); ?>
                            </td>
                            <td class="font-size-11">
                                <?php echo $pur_sale == 'purchase' ? '<b>COUNTRY</b>' . $parent_data['country'] : '<b>CITY</b>' . $parent_data['city'];
                                echo $pur_sale == 'purchase' ? '<br><b>ALLOT</b>' . $parent_data['allot'] : '<br><b>S.NAME</b>' . $parent_data['s_name'];
                                echo $pur_sale == 'purchase' ? '<br><b>P.A/C#</b>' : '<br><b>S.A/C#</b>';
                                echo $p_khaata_no; ?>
                            </td>
                            <td class="font-size-11">
                                <?php if (!empty($bail_json)) {
                                    echo '<b>CONTAINER#</b>' . $bail_json->container_no;
                                    echo '<br><b>BAIL#</b>' . $bail_json->bail_no;
                                    echo '<br><b>LOADING D.</b>' . $bail_json->loading_date;
                                    echo '<br><b>RECEIVE D.</b>' . $bail_json->receiving_date;
                                } else {
                                    echo '<div class="bg-danger">&nbsp;</div>';
                                } ?>
                            </td>
                            <td class="font-size-11">
                                <?php if (!empty($pa_details)) {
                                    echo '<b>Entry Bill#</b>' . $pa_details->bill_no;
                                    echo '<br><b>Entry Bill Date</b>' . $pa_details->bill_date;
                                    echo '<br><b>Report</b>' . $pa_details->report;
                                } ?>
                            </td>

                        </tr>
                        <?php $numRows++;
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" value="<?php echo $numRows; ?>" id="rows_input">
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
    document.getElementById("rows_span").textContent = document.getElementById("rows_input").value;
</script>