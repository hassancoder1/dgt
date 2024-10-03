<?php if (isset($_GET['inv_id']) && ($_GET['inv_id'] > 0)) {
    require("../connection.php");
    $id = mysqli_real_escape_string($connect, $_GET['inv_id']);
    $ddd = fetch('invoices', array('id' => $id));
    $inv = mysqli_fetch_assoc($ddd);
    $json = json_decode($inv['json_data']);
    $backURL = '../invoice-add?id=' . $id;
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
        <title>Invoice<?php echo $id . '_' . date('Y_m_d-H_i_s'); ?></title>
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
    <section>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-12">
                    <div class="px-0 position-relative" id="main-" style="min-height:1000px; position:absolute; z-index:444;">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <img src="../assets/images/logo.png" alt="logo" class="img-fluid" style="width: 150px;">
                                <h6 class="mt-3">
                                    DAMAAN GENERAL TRADING L.L.C <br>
                                    Al Ras Deira Dubai office UAE <br>
                                    +971544186664 damaan.dubai@gmail.com
                                </h6>
                            </div>
                            <div class="text-end">
                                <h2 class="mt-4 fw-bold text-nowrap">INVOICE</h2>
                                <div class="mt-5">
                                    <h5 class="fw-bold">
                                        Sales No: <?php echo $inv['inv_no']; ?><br>
                                        Sales Date: <?php echo date('d M Y', strtotime($inv['inv_date'])); ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 mb-4 gx-0">
                            <div class="col">
                                <h5 class="mb-0 fw-bold">Importer</h5>
                                <h6 class="small">
                                    <?php echo $importer['comp_name']; ?><br>
                                    <?php echo $importer['comp_address']; ?><br>
                                    <?php echo $importer['city']; ?><br>
                                </h6>
                            </div>
                            <div class="col">
                                <h5 class="mb-0 fw-bold">Exporter</h5>
                                <h6 class="small">
                                    <?php echo $exporter['comp_name']; ?><br>
                                    <?php echo $exporter['comp_address']; ?><br>
                                    <?php echo $exporter['city']; ?><br>
                                </h6>
                            </div>
                            <div class="col">
                                <h5 class="mb-0 fw-bold">Notify Party</h5>
                                <h6 class="small">
                                    <?php echo $party['comp_name']; ?><br>
                                    <?php echo $party['comp_address']; ?><br>
                                    <?php echo $party['city']; ?><br>
                                </h6>
                            </div>
                        </div>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark">
                            <tr>
                                <th>Goods Name</th>
                                <th>Origin</th>
                                <th>Terms</th>
                                <th>Shipping Method</th>
                                <th>Port of loading</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo $json->goods_name; ?></td>
                                <td><?php echo $json->origin; ?></td>
                                <td><?php echo $json->terms; ?></td>
                                <td><?php echo $json->shipping_method; ?></td>
                                <td><?php echo $json->loading_port; ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark">
                            <tr>
                                <th>Shipping Terms</th>
                                <th>Delivery Date</th>
                                <th>Payment Terms</th>
                                <th>Due Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo $json->shipping_terms; ?></td>
                                <td><?php echo date('d M Y', strtotime($json->delivery_date)); ?></td>
                                <td><?php echo $json->payment_terms; ?></td>
                                <td><?php echo date('d M Y', strtotime($json->due_date)); ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-sm table-bordered">
                            <thead class="table-dark">
                            <tr>
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
                                <td><?php echo $json->qty_name; ?></td>
                                <td><?php echo $json->qty_no; ?></td>
                                <td><?php echo $json->kgs; ?></td>
                                <td><?php echo $json->total_kgs; ?></td>
                                <td><?php echo $json->unit_price . ' ' . $json->currency; ?></td>
                                <td><?php echo $json->amount . ' ' . $json->currency; ?></td>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <th class="text-end">Freight</th>
                                <th><?php echo empty($json->freight) ? '-' : $json->freight . ' ' . $json->currency; ?></th>
                            </tr>
                            <tr>
                                <td colspan="4"></td>
                                <th class="text-end fw-bold">Net Total <?php echo $json->currency; ?></th>
                                <th class="fw-bold"><?php echo $json->total_amount; ?></th>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row" style="/*width:100%;*/  position:absolute; bottom:0; ">
                            <div class="row align-items-center">
                                <div class="col-7">
                                    <?php $q_bank = fetch('khaata', array('id' => $json->bank_khaata_id));
                                    $bank = mysqli_fetch_assoc($q_bank); ?>
                                    <p class="" style="font-size: 12px"><b>BANK DETAILS:-</b><br>
                                        ACCOUNT NAME: <?php echo $bank['comp_name']; ?><br>
                                        <!--ACCOUNT NO: <?php /*echo $bank['cnic_name']; */ ?> 19101139623 <br>-->
                                        IBAN NO: <?php echo $bank['khaata_name']; ?><br>
                                        BANK NAME: <?php echo $bank['business_name']; ?><br>
                                        BRANCH: <?php echo $bank['cnic_name']; ?><br>
                                        SWIFT: <?php echo $bank['details']; ?></p>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-12">
                                            <p><b>Signature</b></p>
                                        </div>
                                        <div class="col">
                                            <div class="border-top border-2 border-dark"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="pdf-footer.png" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

