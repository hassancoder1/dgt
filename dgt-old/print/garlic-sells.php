<?php if (isset($_GET['buys_id']) && ($_GET['buys_id'] > 0) && isset($_GET['buys_sold_id'])
    && ($_GET['buys_sold_id'] > 0) && isset($_GET['secret']) && isset($_GET['sale_for'])) {
    require("../connection.php");
    require("check-session.php");
    $sale_for = mysqli_real_escape_string($connect, $_GET['sale_for']);
    $arraySaleFor = array('export', 'local');
    if (in_array($sale_for, $arraySaleFor)) {
        $buys_id = mysqli_real_escape_string($connect, $_GET['buys_id']);
        $records = fetch('buys', array('id' => $buys_id));
        $record = mysqli_fetch_assoc($records);
        $buys_sold_id = mysqli_real_escape_string($connect, $_GET['buys_sold_id']);
        $backURL = '../garlic-sells-' . $sale_for . '?id=' . $buys_id . '&buys_sold_id=' . $buys_sold_id . '&action=sale-update';
        $buys_sold_id = mysqli_real_escape_string($connect, $buys_sold_id);
        $bsq = fetch('buys_sold', array('id' => $buys_sold_id));
        $buys_sold = mysqli_fetch_assoc($bsq);
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>Fresh-Sale-<?php echo $sale_for . '-' . date('Y_m_d-H_i_s'); ?></title>
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="../assets/css/custom.css">
            <link rel="shortcut icon" href="../assets/images/logo.png"/>
            <link rel="stylesheet" href="../assets/css/icons.min.css">
            <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
            <style>
                body {
                    background-color: white;
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
                    font-size: 10px;
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
        <div class="container-fluid px-0 overflow-hidden">
            <div class="row justify-content-center mt-0 pt-0">
                <div class="col-lg-7 col-12">
                    <div class="p-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="">
                                <img src="../assets/images/logo.png" alt="logo" class="img-fluid" style="width: 150px;">
                                <h6 class="mt-3">
                                    DAMAAN GENERAL TRADING L.L.C <br>
                                    Al Ras Deira Dubai office UAE <br>
                                    +971544186664 damaan.dubai@gmail.com
                                </h6>
                            </div>
                            <div class="text-end">
                                <h2 class="mt-4 fw-bold">Fresh <?php echo ucfirst($sale_for); ?> Sale</h2>
                                <div class="mt-5">
                                    <!--<h5 class="mb-0 fw-bold">SALE CONTRACT</h5>-->
                                    <h5 class="fw-bold">
                                        Bill No. <?php echo $buys_id . '/' . $buys_sold['bill_no']; ?><br>
                                        <?php echo '<span class="fw-normal">Sale Date: </span>' . date('d M Y', strtotime($buys_sold['s_date'])); ?>

                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="row gx-0 mt-4">
                            <?php if ($buys_sold['party_id'] > 0) {
                                echo '<div class="col">';
                                echo '<h5 class="mb-0 fw-bold">Notify Party</h5>';
                                $partQ = fetch('parties', array('id' => $buys_sold['party_id']));
                                $party = mysqli_fetch_assoc($partQ);
                                echo '<h6 class="small">';
                                echo '<span class="fw-bold">Company: </span><span>' . $party['comp_name'] . '<br></span>';
                                echo '<span class="fw-bold">City: </span><span>' . $party['city'] . '<br></span>';
                                echo '<span class="fw-bold">Company Address: </span><span>' . $party['comp_address'] . '<br></span>';
                                echo '<span class="fw-bold">Mobile: </span><span>' . $party['mobile'] . '<br></span>';
                                echo '<span class="fw-bold">Email: </span><span>' . $party['email'] . '<br></span>';
                                echo '</h6>';
                                echo '</div>';
                            } ?>
                            <?php if ($buys_sold['seller_khaata_id'] > 0) {
                                $khQ = fetch('khaata', array('id' => $buys_sold['seller_khaata_id']));
                                $seller = mysqli_fetch_assoc($khQ);
                                echo '<div class="col">';
                                echo '<h5 class="mb-0 fw-bold">Seller</h5>';
                                echo '<h6 class="small">';
                                echo '<span class="fw-bold">A/c. No: </span><span>' . $seller['khaata_no'] . '<br></span>';
                                echo '<span class="fw-bold">A/c. Name: </span><span>' . $seller['khaata_name'] . '<br></span>';
                                echo '<span class="fw-bold">Company: </span><span>' . $seller['comp_name'] . '<br></span>';
                                echo '<span class="fw-bold">Address: </span><span>' . $seller['address'] . '<br></span>';
                                echo '<span class="fw-bold">Mobile: </span><span>' . $seller['mobile'] . '<br></span>';
                                echo '<span class="fw-bold">Email: </span><span>' . $seller['email'] . '<br></span>';
                                echo '</h6>';
                                echo '</div>';
                            } ?>
                            <?php if ($buys_sold['importer_id'] > 0) {
                                $imps = fetch('imps_exps', array('id' => $buys_sold['importer_id']));
                                $imp = mysqli_fetch_assoc($imps);
                                echo '<div class="col">';
                                echo '<h5 class="mb-0 fw-bold">Importer</h5>';
                                echo '<h6 class="small">';
                                echo '<span class="fw-bold">Name: </span>' . $imp['name'] . '<br>';
                                echo '<span class="fw-bold">Email: </span>' . $imp['email'] . '<br>';
                                echo '<span class="fw-bold">City: </span>' . $imp['city'] . '<br>';
                                echo '<span class="fw-bold">Company: </span><span>' . $imp['comp_name'] . '<br></span>';
                                echo '<span class="fw-bold">Company Address: </span>' . $imp['comp_address'] . '<br>';
                                echo '<span class="fw-bold">Mobile: </span>' . $imp['mobile'];
                                echo '</h6>';
                                echo '</div>';
                            } ?>
                            <?php if ($buys_sold['exporter_id'] > 0) {
                                $imps = fetch('imps_exps', array('id' => $buys_sold['exporter_id']));
                                $imp = mysqli_fetch_assoc($imps);
                                echo '<div class="col">';
                                echo '<h5 class="mb-0 fw-bold">Exporter</h5>';
                                echo '<h6 class="small">';
                                echo '<span class="fw-bold">Name: </span>' . $imp['name'] . '<br>';
                                echo '<span class="fw-bold">Email: </span>' . $imp['email'] . '<br>';
                                echo '<span class="fw-bold">City: </span>' . $imp['city'] . '<br>';
                                echo '<span class="fw-bold">Company: </span><span>' . $imp['comp_name'] . '<br></span>';
                                echo '<span class="fw-bold">Company Address: </span>' . $imp['comp_address'] . '<br>';
                                echo '<span class="fw-bold">Mobile: </span>' . $imp['mobile'];
                                echo '</h6>';
                                echo '</div>';
                            } ?>
                        </div>
                        <hr>
                        <div class="row mb-2 gx-5">
                            <div class="col-6 ">
                                <h6 class="text-uppercase">
                                    <span class="fw-bold">Goods Name </span><?php echo $buys_sold['jins']; ?><br>
                                    <span class="fw-bold">Allot Name </span><?php echo $buys_sold['allot_name']; ?><br>
                                    <span class="fw-bold">Loading depot </span><?php echo $buys_sold['loading_godam']; ?>
                                    <br>
                                </h6>
                                <h6 class="mt-2 text-uppercase">
                                    <span class="fw-bold">Currency </span><?php echo $buys_sold['currency2']; ?><br>
                                    <span class="fw-bold">Rate </span><?php echo $buys_sold['exchange_rate']; ?><br>
                                    <span class="fw-bold">Final Amount </span><?php echo $buys_sold['final_amount']; ?>
                                    <br>
                                </h6>

                            </div>
                            <div class="col-6">
                                <h6 class="text-uppercase">
                                    <span class="fw-bold">Bail date	 </span><?php echo $buys_sold['bail_date']; ?>
                                    <br>
                                    <span class="fw-bold">Bail#	 </span><?php echo $buys_sold['bail_no']; ?><br>
                                    <span class="fw-bold">Container# </span><?php echo $buys_sold['container_no']; ?>
                                    <br>
                                    <span class="fw-bold">Loading port </span><?php echo $buys_sold['loading_port']; ?>
                                    <br>
                                    <span class="fw-bold">Receiving port </span><?php echo $buys_sold['receiving_port']; ?>
                                    <br>
                                    <span class="fw-bold">Shipping lane details </span><?php echo $buys_sold['shipping_lane_details']; ?>
                                    <br>
                                    <span class="fw-bold">Bill Details </span><?php echo $buys_sold['bill_details']; ?>
                                </h6>
                            </div>
                        </div>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark">
                            <tr>
                                <th>Qty Name</th>
                                <th>Qty No</th>
                                <th>KGs</th>
                                <th>Total KGs</th>
                                <th>Unit Price/KG</th>
                                <th>Total Amount</th>
                                <th>Total Expenses</th>
                                <th>Next amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo $buys_sold['bardana_name']; ?></td>
                                <td><?php echo $buys_sold['bardana_qty']; ?></td>
                                <td><?php echo $buys_sold['total_wt']; ?></td>
                                <td><?php echo $buys_sold['saaf_wt']; ?></td>
                                <td><?php echo $buys_sold['qeemat_name'] . '/' . $buys_sold['currency1']; ?></td>
                                <td><?php echo $buys_sold['qeemat_raqam']; ?></td>
                                <td><?php echo $buys_sold['total_exp']; ?></td>
                                <td class="fw-bold"><?php echo $buys_sold['amount_exc_exp']; ?></td>
                            </tr>
                            <?php if (!empty($buys_sold['json_exp'])) {
                                $json2 = json_decode($buys_sold['json_exp']);
                                $exp_names = $json2->exp_names;
                                $exp_details = $json2->exp_details;
                                $exp_values = $json2->exp_values;
                                echo '<tr><td colspan="8">';
                                echo '<h6 class="fw-bold">Expense Details:</h6>';
                                foreach ($exp_names as $index => $value) {
                                    echo ' Expense: ' . $exp_names[$index] . ' Details: ' . $exp_details[$index] . ' Amount: ' . $exp_values[$index] . '<br>';
                                }
                                echo '</td></tr>';
                                ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <p class=" mb-0">This contract is in copies, effective since being signed/sealed by all
                                parties:</p>
                        </div>
                        <?php if ($buys_sold['party_id'] > 0) {
                            $partQ = fetch('parties', array('id' => $buys_sold['party_id']));
                            $party = mysqli_fetch_assoc($partQ);
                            echo '<div class="col stretch-card">';
                            echo '<h5 class="mb-0 fw-bold">Notify Party Signature</h5>';
                            echo '<p class="small"><span>' . $party['comp_name'] . '</span></p>';
                            echo '<div class="border-top border-2 border-dark">&nbsp;</div>';
                            echo '</div>';
                        } ?>

                        <?php if ($buys_sold['seller_khaata_id'] > 0) {
                            $khQ = fetch('khaata', array('id' => $buys_sold['seller_khaata_id']));
                            $seller = mysqli_fetch_assoc($khQ);
                            echo '<div class="col stretch-card">';
                            echo '<h5 class="mb-0 fw-bold">Seller Signature</h5>';
                            echo '<p class="small"><span>' . $seller['khaata_name'] . '</span></p>';
                            echo '<div class="border-top border-2 border-dark">&nbsp;</div>';
                            echo '</div>';
                        } ?>
                        <?php if ($buys_sold['importer_id'] > 0) {
                            $imps = fetch('imps_exps', array('id' => $buys_sold['importer_id']));
                            $imp = mysqli_fetch_assoc($imps);
                            echo '<div class="col stretch-card">';
                            echo '<h5 class="mb-0 fw-bold">Importer Signature</h5>';
                            echo '<p class="small"><span>' . $imp['name'] . '</span></p>';
                            echo '<div class="border-top border-2 border-dark">&nbsp;</div>';
                            echo '</div>';
                        } ?>
                        <?php if ($buys_sold['exporter_id'] > 0) {
                            $imps = fetch('imps_exps', array('id' => $buys_sold['exporter_id']));
                            $imp = mysqli_fetch_assoc($imps);
                            echo '<div class="col stretch-card">';
                            echo '<h5 class="mb-0 fw-bold">Exporter Signature</h5>';
                            echo '<p class="small"><span>' . $imp['name'] . '</span></p>';
                            echo '<div class="border-top border-2 border-dark">&nbsp;</div>';
                            echo '</div>';
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="Go Back" data-tooltip-position="right">
                    <a href="<?php echo $backURL; ?>"><i class="fa fa-long-arrow-alt-left"></i></a>
                </li>
                <li class="facebook" title="PDF Print">
                    <a class="cursor-pointer" onclick="window.print();"><i class="fa fa-print"></i></a>
                </li>
            </ul>
        </div>
        <div class="fixed-top  d-print-none bg-light p-2" style="width: 270px; top:30%">
            <p>
                <span class="fw-bold">Branch:</span> <?php echo $branchName; ?><br>
                <span class="fw-bold">UserID:</span> <?php echo $userName; ?>
            </p>
            <select class="form-select form-select-sm ms-4-" id="bill-select">
                <?php $sales = fetch('buys_sold', array('buys_id' => $buys_id, 'sale_for' => $sale_for, 'type' => 'garlic', 'is_gp' => 1));
                while ($sale = mysqli_fetch_assoc($sales)) {
                    $selected_ser = $buys_sold_id == $sale['id'] ? 'selected' : '';
                    echo '<option ' . $selected_ser . ' value="' . $sale['id'] . '">' . $sale['bill_no'] . '</option>';
                } ?>
            </select>
            <?php if ($buys_sold['is_ttr'] == 1) {
                $rozQ = fetch('roznamchaas', array('r_type' => 'karobar', 'transfered_from_id' => $buys_sold['id'], 'transfered_from' => 'garlic_buys_sold_'.$sale_for));
                if (mysqli_num_rows($rozQ) > 0) {
                    //echo '<h5 class="text-center">Transferred to Roznmacha.</h5>'; ?>
                    <div class="table-responsive col-12">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-primary">
                            <tr>
                                <th>Main#,&nbsp;B#</th>
                                <th>Date</th>
                                <th>A/c&nbsp;No.</th>
                                <th>Roznamcha&nbsp;#</th>
                                <th>Name</th>
                                <th>No</th>
                                <th width="40%">Details</th>
                                <th>Dr.</th>
                                <th>Cr.</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                                $jmaa_amount = $roz['jmaa_amount'];
                                //echo $jmaa_amount; ?>
                                <tr>
                                    <td><?php echo Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial']; ?></td>
                                    <td>
                                        <?php echo $roz['r_date']; ?>
                                        <input type="hidden"
                                               value="<?php echo $roz['r_id']; ?>"
                                               name="r_id[]">
                                    </td>
                                    <td><?php echo $roz['khaata_no']; ?></td>
                                    <td><?php echo $roz['roznamcha_no']; ?></td>
                                    <td class="small"><?php echo $roz['r_name']; ?></td>
                                    <td><?php echo $roz['r_no']; ?></td>
                                    <?php $str = "";
                                    if ($roz['jmaa_amount'] == 0) {
                                        $str = "Cr. ";
                                    }
                                    if ($roz['bnaam_amount'] == 0) {
                                        $str = "Dr. ";
                                    } ?>
                                    <td class="small text-nowrap"><?php echo $str . $roz['details']; ?></td>
                                    <td class="text-success"><?php echo $roz['jmaa_amount']; ?></td>
                                    <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php }
            } ?>
        </div>
        </body>
        </html>
        <?php echo isset($_GET['print']) ? '<script>window.print();</script>' : '';
    } else {
        echo '<script>window.location.href="../";</script>';
    }
} else {
    echo '<script>window.location.href="../";</script>';
} ?>
<script>
    document.querySelector('#bill-select').addEventListener('change', function () {
        window.$_GET = location.search.substr(1).split("&").reduce((o, i) => (u = decodeURIComponent, [k, v] = i.split("="), o[u(k)] = v && u(v), o), {});
        let urlString = "sells-export?buys_id=" + $_GET.buys_id + "&buys_sold_id=";
        //var id = obj.options[obj.selectedIndex];
        let id = this.value;
        console.log(id);
        urlString += id + '&sale_for=' + $_GET.sale_for + '&secret=' + btoa('powered-by-upsol');

        window.location.href = urlString;
    });
</script>

