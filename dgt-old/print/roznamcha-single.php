<?php $backURL = '../roznamcha';
if (!empty($_GET['r_id']) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $r_id = mysqli_real_escape_string($connect, base64_decode($_GET['r_id']));
        $rQuery = mysqli_query($connect, "SELECT * FROM roznamchaas WHERE r_id = '$r_id'");
        $roz = mysqli_fetch_assoc($rQuery);

        $kh = fetch('khaata', array('id' => $roz['khaata_id']));
        $khaata = mysqli_fetch_assoc($kh); ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Roznamcha<?php echo $r_id . '_' . date('Y_m_d-H_i_s'); ?></title>
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
        <img src="bg-logo.png"
             style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <section>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <?php for ($i = 1; $i <= 2; $i++) { ?>
                        <div class="col-lg-6 col-12">
                            <div class="px-0 position-relative" id="main-"
                                 style="min-height:430px; position:absolute; z-index:444;">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div>
                                        <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid"
                                             style="width: 80px;">
                                        <!--<h6 class="mt-2 small">DAMAAN GENERAL TRADING L.L.C</h6>-->
                                    </div>
                                    <div class="text-end">
                                        <h2 class="fw-bold text-nowrap">Roznamcha</h2>
                                        <div class="mt-2">
                                            <h6 class="fw-bold">
                                                Sr#: <?php echo $r_id . '-' . $roz['branch_serial']; ?><br>
                                                Date: <?php echo date('M-d-Y', strtotime($roz['r_date'])); ?>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-1 mt-1 mb-2 border border-dark px-1">
                                    <div class="small text-nowrap text-uppercase">
                                        <?php echo '<b>' . ucfirst($roz['dr_cr']) . ' A/C</b> ' . $roz['khaata_no'] . '<br>';
                                        echo '<b>A/C NAME</b> ' . $khaata['khaata_name'] . '<br>';
                                        echo '<b>COMPANY</b> ' . $khaata['comp_name']; ?>
                                    </div>
                                    <div class="small text-uppercase">
                                        <?php echo '<b>Address</b> ' . $khaata['address'] . '<br>';
                                        echo '<b>City</b> ' . $khaata['city']; ?>
                                    </div>
                                    <div class="small text-nowrap">
                                        <?php $details1 = ['indexes' => $khaata['indexes'], 'vals' => $khaata['vals']];
                                        $contacts = displayKhaataDetails($details1, true);
                                        echo array_key_exists('Phone', $contacts) ? '<b>P.</b> ' . $contacts['Phone'] . '<br>' : '';
                                        echo array_key_exists('WhatsApp', $contacts) ? '<b>WA.</b> ' . $contacts['WhatsApp'] . '<br>' : '';
                                        echo array_key_exists('Email', $contacts) ? '<b>E.</b> ' . $contacts['Email'] : ''; ?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>ROZNAMCHA #</b> <?php echo $roz['roznamcha_no']; ?></div>
                                    <div><b>NAME</b> <?php echo $roz['r_name']; ?></div>
                                    <div><b>NO.</b> <?php echo $roz['r_no']; ?></div>
                                    <?php echo !empty($roz['mobile']) ? '<div><b>MOB.</b> ' . $roz['mobile'] . '</div>' : ''; ?>
                                </div>
                                <div class="mt-2 d-flex justify-content-between">
                                    <div>
                                        <b>DETAILS</b>
                                        <u class="small">
                                            <?php echo $roz['details']; ?>
                                            <?php
                                            echo $roz['qty'] . ' ' . $roz['currency'] . ' <b>Price</b>' . $roz['per_price'];
                                            ?>
                                        </u>
                                    </div>
                                    <div class="text-nowrap">
                                        <b><?php echo ucfirst($roz['dr_cr']) . '. AMOUNT'; ?></b> <?php echo $roz['amount']; ?>
                                    </div>
                                </div>
                                <div class="row" style="/*width:100%;*/  position:absolute; bottom:0; ">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p><b>Account Signature</b></p>
                                                </div>
                                                <div class="col">
                                                    <div class="border-top border-2 border-dark"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p><b>Company Signature</b></p>
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
                        <?php echo $i == 1 ? '<hr class="my-5">' : ''; ?>
                    <?php } ?>
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
        <?php
        if (isset($_GET['print'])) {
            echo '<script>window.print();</script>';
        }
    } else {
        echo '<script>window.location.href="' . $backURL . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backURL . '";</script>';
} ?>

