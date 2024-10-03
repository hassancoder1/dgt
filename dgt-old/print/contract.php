<?php if (isset($_GET['contract_id']) && ($_GET['contract_id'] > 0)) {
    require("../connection.php");
    $id = mysqli_real_escape_string($connect, $_GET['contract_id']);
    $ddd = fetch('contracts', array('id' => $id));
    $inv = mysqli_fetch_assoc($ddd);
    $json = json_decode($inv['json_data']);
    $backURL = '../contract-add?id=' . $id;
    $type_array = array('title' => 'Contract');
    if (isset($_GET['type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['type']);
        switch ($type) {
            case 'proforma':
                $type_array['title'] = 'Proforma Invoice';
                break;
            case 'packing':
                $type_array['title'] = 'Packing List';
                break;

        }
    }
    $q_imp = fetch('imps_exps', array('id' => $json->importer_id));
    $importer = mysqli_fetch_assoc($q_imp);
    $q_exp = fetch('imps_exps', array('id' => $json->exporter_id));
    $exporter = mysqli_fetch_assoc($q_exp);
    $q_party = fetch('parties', array('id' => $json->party_id));
    $party = mysqli_fetch_assoc($q_party); ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="Damaan Impex">
        <meta name="author" content="Asmatullah">
        <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
        <title>Contract<?php echo $id . '_' . date('Y_m_d-H_i_s'); ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com/">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/custom.css">
        <link rel="shortcut icon" href="../assets/images/logo.png"/>
        <link rel="stylesheet" href="../assets/css/icons.min.css">
        <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
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
    <img src="bg-logo.png" style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="container-fluid" style="min-height: 794px;">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-12">
                <div class="px-0 position-relative" id="main-"
                     style="min-height:1000px; position:absolute; z-index:444;">
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
                            <h2 class="mt-4 fw-bold text-nowrap text-uppercase"><?php echo $type_array['title']; ?></h2>
                            <div class="mt-5">
                                <h5 class="fw-bold">
                                    Contract No: <?php echo $inv['contract_no']; ?><br>
                                    Contract Date: <?php echo date('d M Y', strtotime($inv['contract_date'])); ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-4 gx-0">
                        <div class="col">
                            <h5 class="mb-2 fw-bold">Buyer</h5>
                            <h6 class="small">
                                <span class="fw-bold text-uppercase">Company: </span>
                                <?php echo $importer['comp_name']; ?><br>
                                <span
                                    class="fw-bold text-uppercase">Address: </span><?php echo $importer['comp_address']; ?>
                                <br>
                                <span class="fw-bold text-uppercase">City: </span><?php echo $importer['city']; ?>
                            </h6>
                        </div>
                        <div class="col">
                            <h5 class="mb-2 fw-bold">Seller</h5>
                            <h6 class="small">
                                    <span
                                        class="fw-bold text-uppercase">Company: </span><?php echo $exporter['comp_name']; ?>
                                <br>
                                <span
                                    class="fw-bold text-uppercase">Address: </span><?php echo $exporter['comp_address']; ?>
                                <br>
                                <span class="fw-bold text-uppercase">City: </span><?php echo $exporter['city']; ?>
                            </h6>
                        </div>
                        <div class="col">
                            <h5 class="mb-2 fw-bold">Notify Party</h5>
                            <h6 class="small">
                                <span class="fw-bold text-uppercase">Company: </span>
                                <?php echo $party['comp_name']; ?><br>
                                <span
                                    class="fw-bold text-uppercase">Address: </span><?php echo $party['comp_address']; ?>
                                <br>
                                <span class="fw-bold text-uppercase">city: </span><?php echo $party['city']; ?>
                            </h6>
                        </div>
                        <div class="col">
                            <?php $q_bank = fetch('khaata', array('id' => $json->bank_khaata_id));
                            $bank = mysqli_fetch_assoc($q_bank); ?>
                            <h5 class="mb-2 fw-bold">Bank Details</h5>
                            <h6 class="small">
                                <span class="fw-bold text-uppercase">ACCOUNT NAME: </span><?php echo $bank['comp_name']; ?><br>
                                <span class="fw-bold text-uppercase">IBAN NO: </span><?php echo $bank['khaata_name']; ?><br>
                                <span class="fw-bold text-uppercase">BANK NAME: </span><?php echo $bank['business_name']; ?><br>
                                <span class="fw-bold text-uppercase">BRANCH: </span><?php echo $bank['cnic_name']; ?><br>
                                <span class="fw-bold text-uppercase">SWIFT: </span><?php echo $bank['details']; ?><br>
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <table class="table">
                                <tr>
                                    <td class="fw-bold text-uppercase">Loading Date</td>
                                    <td><?php echo date('d M Y', strtotime($json->loading_date)); ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">Receiving Date</td>
                                    <td><?php echo date('d M Y', strtotime($json->receiving_date)); ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">Terms</td>
                                    <td><?php echo $json->terms; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">Origin</td>
                                    <td><?php echo $json->origin; ?></td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table">
                                <tr>
                                    <td class="fw-bold text-uppercase">Loading Country</td>
                                    <td><?php echo $json->loading_country; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">Receiving Country</td>
                                    <td><?php echo $json->receiving_country; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">Shipping Method</td>
                                    <td><?php echo $json->shipping_method; ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-uppercase">Payment Terms</td>
                                    <td><?php echo $json->payment_terms; ?></td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead class="table-dark">
                        <tr>
                            <th>Goods Name</th>
                            <th>Qty Name</th>
                            <th>Qty No</th>
                            <th>KGs</th>
                            <th>Total KGs</th>
                            <th>Unit Price/KG</th>
                            <th>Total Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php echo $json->goods_name; ?></td>
                            <td><?php echo $json->qty_name; ?></td>
                            <td><?php echo $json->qty_no; ?></td>
                            <td><?php echo $json->kgs; ?></td>
                            <td><?php echo $json->total_kgs; ?></td>
                            <td><?php echo $json->unit_price . ' ' . $json->currency; ?></td>
                            <td><?php echo $json->amount . ' ' . $json->currency; ?></td>
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <th class="text-end">Advance <?php echo $json->advance_per; ?>%</th>
                            <th class="fw-bold"><?php echo $json->advance; ?></th>
                            <!--<th><?php /*echo empty($json->advance) ? '-' : $json->advance_per . '% ' . $json->currency; */ ?></th>-->
                        </tr>
                        <tr>
                            <td colspan="5"></td>
                            <th class="text-end fw-bold">Net Total <?php echo $json->currency; ?></th>
                            <th class="fw-bold"><?php echo $json->total_amount; ?></th>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col">
                            <p><?php echo $json->contract_details; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="fixed-bottom bg-white">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-12">
                    <div class="row">
                        <div class="col"><p><b>Buyer Signature</b></p></div>
                        <div class="col"><p><b>Seller Signature</b></p></div>
                        <div class="col"><p><b>Notify Party Signature</b></p></div>
                    </div>
                    <div class="row mb-1 mt-4">
                        <div class="col">
                            <div class="border-top border-2 border-dark"></div>
                        </div>
                        <div class="col">
                            <div class="border-top border-2 border-dark"></div>
                        </div>
                        <div class="col">
                            <div class="border-top border-2 border-dark"></div>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 text-center small fw-bold">"Although the buyer's signature is optional, the
                                signatures of both the seller and the notify party are required for this document to be
                                valid."</p>
                        </div>
                    </div>
                    <img src="pdf-footer.png" class="img-fluid">
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
    </body>
    </html>
<?php } else {
    echo '<script>window.location.href="../";</script>';
} ?>

