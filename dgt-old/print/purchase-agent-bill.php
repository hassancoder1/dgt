<?php if (isset($_GET['p_id']) && $_GET['p_id'] > 0 && isset($_GET['pd_id']) && $_GET['pd_id'] > 0 && isset($_GET['purchase_agents_id']) && $_GET['purchase_agents_id'] > 0 && isset($_GET['action'])) {
    require("../connection.php");
    $purchase_id = mysqli_real_escape_string($connect, $_GET['p_id']);
    $purchase_agents_id = mysqli_real_escape_string($connect, $_GET['purchase_agents_id']);
    $pd_id = mysqli_real_escape_string($connect, $_GET['pd_id']);
    $action = mysqli_real_escape_string($connect, $_GET['action']);
    $backURL = '../admin-agent-bills';
    $record = fetch('purchases', array('id' => $purchase_id));
    $record = mysqli_fetch_assoc($record);
    $purchase_type = $record['type'];
    $p_khaata = khaataSingle($record['p_khaata_id']);
    $s_khaata = khaataSingle($record['s_khaata_id']);
    $records2q = fetch('purchase_details', array('id' => $pd_id));
    $rows = mysqli_num_rows($records2q);
    $record2 = mysqli_fetch_assoc($records2q);
    $imp_json = json_decode($record2['imp_json']);
    $exp_json = json_decode($record2['exp_json']);
    $notify_json = json_decode($record2['notify_json']);
    $ware_json = json_decode($record2['ware_json']);
    $bail_json = json_decode($record2['bail_json']);
    //var_dump($agentKhaata);
    $check_agent_query = fetch('purchase_agents', array('id' => $purchase_agents_id));
    $check_agent_data = mysqli_fetch_assoc($check_agent_query);
    $khaataId = $check_agent_data['khaata_id'];
    $agentKhaata = khaataSingle($khaataId);
    $pa_sr = $check_agent_data['a_sr'];
    $agent_contacts = ['indexes' => $agentKhaata['indexes'], 'vals' => $agentKhaata['vals']];
    $agent_contacts = displayKhaataDetails($agent_contacts, true);
    //var_dump($agent_contacts);
    /*echo array_key_exists('Phone', $agent_contacts) ? '<b>P.</b> ' . $agent_contacts['Phone'] . '<br>' : '';
    echo array_key_exists('WhatsApp', $agent_contacts) ? '<b>WA.</b> ' . $agent_contacts['WhatsApp'] . '<br>' : '';
    echo array_key_exists('Email', $agent_contacts) ? '<b>E.</b> ' . $agent_contacts['Email'] : '';*/
    $topArray = array(
        array('heading' => 'Agent A/c.', 'value' => $agentKhaata['khaata_no']),
        array('heading' => 'A/c. Name', 'value' => $agentKhaata['khaata_name']),
        array('heading' => 'COMPANY', 'value' => $agentKhaata['comp_name'])
    );
    if (array_key_exists('Phone', $agent_contacts)) {
        $topArray[] = array('heading' => 'P.', 'value' => $agent_contacts['Phone']);
    }
    if (array_key_exists('WhatsApp', $agent_contacts)) {
        $topArray[] = array('heading' => 'WA.', 'value' => $agent_contacts['WhatsApp']);
    }
    if (array_key_exists('Email', $agent_contacts)) {
        $topArray[] = array('heading' => 'E.', 'value' => $agent_contacts['Email']);
    }
    $topArray[] = array('heading' => 'BRANCH', 'value' => branchName($agentKhaata['branch_id']));
    $topArray[] = array('heading' => 'P.TYPE', 'value' => strtoupper($check_agent_data['type']));
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Agent-Bill-<?php echo $purchase_id . '-' . $pa_sr . '__' . date('YmdHis'); ?></title>
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
                background: transparent;
            }

            #main {
                background-image: url("bg.png");
                background-position: center;
                background-size: contain;
                background-repeat: no-repeat;
                height: 110%;

            }

            * {
                color: black;
            }

            h6 {
                margin-bottom: 0;
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
                font-size: 12px;
                color: inherit;
            }

            .table thead tr th {
                /*font-size: 8px;*/
                background: black;
                color: white;
            }

            .under {
                text-decoration: underline;
                text-underline-offset: 10%;
            }

            .table-bordered {
                border: 1px solid #000000;
            }
        </style>
    </head>
    <body>
    <img src="bg-logo.png"
         style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="container-fluid" style="min-height: 794px;">
        <div class="row justify-content-center fixed-top bg-white">
            <div class="col-lg-8 col-12">
                <div class="d-flex align-items-start justify-content-between">
                    <div><img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                    </div>
                    <div class="text-end">
                        <h2 class="fw-bold mb-0">AGENT BILL</h2>
                        <h6>
                            <b>BILL#</b> <?php echo $purchase_id . '-' . $pa_sr; ?><br>
                            <b>DATE</b> <?php echo date('d-M-Y'); ?><br>
                            <!--<b>Date</b> <?php /*echo date('d M Y', strtotime($record['p_date'])); */ ?><br>-->
                            <!--<b>Country</b> <?php /*echo $record['country']; */ ?><br>
                            <b>Allot</b> --><?php /*echo $record['allot'];*/ ?>
                        </h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" style="margin-top: 110px">
            <div class="col-lg-8 col-12">
                <div class="row gx-1 font-size-11 text-uppercase">
                    <div class="col">
                        <?php foreach ($topArray as $item) {
                            echo '<b>' . $item['heading'] . '</b><span>' . $item['value'] . '</span><br>';
                        } ?>
                    </div>
                    <?php $arr1 = array(
                        array('GOODS', goodsName($record2['goods_id'])),
                        array('Qty Name', $record2['qty_name']),
                        array('Qty#', $record2['qty_no']),
                        array('Total KGs', $record2['total_kgs']),
                        array('Net KGs', round($record2['net_kgs'], 2)),
                    ); ?>
                    <div class="col">
                        <?php foreach ($arr1 as $item) {
                            echo '<div><b>' . $item[0] . '</b> ' . $item[1] . '</div>';
                        } ?>
                    </div>
                    <?php if (!empty($bail_json)) {
                        $arr_bail1 = array(
                            'Container#' => $bail_json->container_no,
                            'Bail#' => $bail_json->bail_no,
                            'Container Name' => $bail_json->container_name,
                            'Container Size' => $bail_json->container_size,
                            'Bail Report' => $bail_json->bail_report
                        );
                        $arr_bail2 = array(
                            'Loading Country' => $bail_json->loading_country,
                            'Loading Port' => $bail_json->loading_port,
                            'Loading Date' => $bail_json->loading_date,
                            'Receiving Country' => $bail_json->receiving_country,
                            'Receiving Port' => $bail_json->receiving_port,
                            'Receiving Date' => $bail_json->receiving_date,
                            'Freight Period' => $bail_json->freight,
                        );
                        $arr_bail_shipp = array(
                            'Shipping Lane Name' => $bail_json->loading_shipper_address,
                            'Address' => $bail_json->receiving_shipper_address,
                            'Company' => $bail_json->ship_comp,
                            'Phone' => $bail_json->ship_phone,
                            'Email' => $bail_json->ship_email,
                            'WhatsApp' => $bail_json->ship_wa,
                        ); ?>
                        <div class="col">
                            <?php foreach ($arr_bail1 as $name => $value) {
                                echo '<b>' . $name . '</b> ' . $value . '<br>';
                            } ?>
                        </div>
                    <?php } ?>
                    <?php $details = json_decode($check_agent_data['details']);
                    if (!empty($details)) {
                        echo '<div class="col">';
                        echo '<b>Entry Bill# </b>' . $details->bill_no;
                        echo '<br><b>Entry Bill Date</b>' . $details->bill_date;
                        echo '<br><b>Loading Truck#</b>' . $details->truck_no;
                        echo '<br><b>Container Name</b>' . $details->ctr_name;
                        echo '<br><b>Container#</b>' . $details->ctr_no;
                        echo '</div>';
                    } ?>
                </div>
                <hr class="text-dark">
                <div class="mt-4 row gx-1 font-size-11 text-uppercase">
                    <?php $bill = json_decode($check_agent_data['bill']);
                    if (!empty($bill)) {
                        echo '<div class="col-12">';
                        echo '<h6 class="mb-3"><b>REPORT </b>' . $bill->report . '</h6>';
                        echo '</div>';
                        echo '<div class="col-12">';
                        echo '<table class="table text-center ">';
                        echo '<tr><th style="width: 4%;">Sr#</th><th style="width: 8%">QTY</th><th>DESCRIPTION</th><th colspan="2" style="width: 15%">AMOUNT</th></tr>';
                        $exp_qtys = $bill->exp_qtys;
                        $exp_details = $bill->exp_details;
                        $exp_values = $bill->exp_values;
                        $srr = 1;
                        foreach ($exp_qtys as $key => $val) {
                            echo '<tr>';
                            echo '<td>' . $srr . '</td>';
                            echo '<td>' . $exp_qtys[$key] . '</td>';
                            echo '<td>' . $exp_details[$key] . '</td>';
                            echo '<td></td>';
                            echo '<td>' . $exp_values[$key] . '</td>';
                            echo '</tr>';
                        }
                        echo '<tr class="bold">';
                        echo '<td>' . count($bill->exp_qtys) . '</td>';
                        echo '<td colspan="2">' . AmountInWords($bill->amount) . '</td>';
                        echo '<td class="text-end">TOTAL</td>';
                        echo '<td class="">' . $bill->amount . '</td>';
                        echo '</tr>';
                        if (SuperAdmin() && $action == 'admin') {
                            if (isset($bill->is_qty)) {
                                //echo '<b>RATE </b>' . $bill->rate . '<sub>' . $bill->currency . '</sub>' . ' [' . $bill->opr . ']';
                                echo '<tr class="bold">';
                                echo '<td></td>';
                                echo '<td></td>';
                                echo '<td colspan="2" class="text-end"><b>RATE </b>' . $bill->rate . '<sub>' . $bill->currency . '</sub>' . ' [' . $bill->opr . ']</td>';
                                echo '<td class="">' . $bill->first_amount . '</td>';
                                echo '</tr>';
                            }
                        }
                        echo '<tr class="bold">';
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td colspan="2" class="text-end">TAX RATE</td>';
                        echo '<td class="">' . $bill->tax_rate . '%' . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                        echo '<td colspan="3" class="text-start">';
                        echo '<b>TRANSFER DATE# </b>' . $bill->bill_date;
                        echo ' <b>ROZNAMCHA SR# </b>';
                        $rozQ = mysqli_query($connect, "SELECT * FROM `roznamchaas` WHERE r_type='Business' AND transfered_from_id = '$purchase_agents_id' AND (transfered_from = 'purchase_agentsimport' || transfered_from = 'purchase_agentsexport')");
                        if (mysqli_num_rows($rozQ) > 0) {
                            while ($roz = mysqli_fetch_assoc($rozQ)) {
                                echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial'];
                                echo ' ';
                            }
                        }
                        echo '</td>';
                        echo '<td class="text-nowrap bold">FINAL AMT.</td>';
                        echo '<td class="">' . $bill->final_amount . '</td>';
                        echo '</tr>';

                        echo '</table>';
                        echo '</div>';
                        echo '<div class="col-6">';
                        echo '</div>';
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed-bottom bg-white">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-12">
                    <div class="row">
                        <div class="col"><p><b>Buyer Signature</b></p></div>
                        <div class="col"><p><b>Seller Signature</b></p></div>
                    </div>
                    <div class="row mb-1 mt-4">
                        <div class="col">
                            <div class="border-top border-2 border-dark"></div>
                        </div>
                        <div class="col">
                            <div class="border-top border-2 border-dark"></div>
                        </div>
                    </div>
                    <img src="pdf-footer.png" class="img-fluid">
                </div>
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
        <div class="ms-4">
            <?php $print_array = array(array('Purchase Contract', 'contract'), array('Transfer', 'transfer'), array('Proforma Invoice', 'proforma'), array('Packing List', 'packing'), array('Bill Print', 'bill')); ?>
            <select class="form-select" id="print_type" name="print_type">
                <option value="" hidden="">Print type? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
                <?php foreach ($print_array as $item) {
                    echo '<option value="' . $item[1] . '">' . ucfirst($item[0]) . '</option>';
                } ?>
            </select>
            <script>
                document.querySelector('#print_type').addEventListener('change', function () {
                    window.location.href = 'purchase-booking?p_id=<?php echo $purchase_id; ?>&action=' + this.value;
                });
            </script>
        </div>
    </div>
    </body>
    </html>
    <?php echo isset($_GET['print']) ? '<script>window.print();</script>' : '';
} else {
    //echo '<script>window.location.href="../";</script>';
} ?>

