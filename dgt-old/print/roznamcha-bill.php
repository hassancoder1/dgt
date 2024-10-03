<?php $backURL = '../';
if (isset($_GET['r_id']) && !(empty($_GET['r_id'])) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $r_id = mysqli_real_escape_string($connect, base64_decode($_GET['r_id']));
        $rQuery = mysqli_query($connect, "SELECT * FROM roznamchaas WHERE r_id = '$r_id'");
        $roz = mysqli_fetch_assoc($rQuery);
        $khaata_id = $roz['khaata_id'];
        $kh = fetch('khaata', array('id' => $khaata_id));
        $khaata = mysqli_fetch_assoc($kh);
        $dr_account = khaataSingle($khaata_id); ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="DAMAAN GENERAL TRADING, UAE">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>Roznamcha<?php echo $khaata_id . '_' . date('Y_m_d-H_i_s'); ?></title>
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
                    /*font-size: 12px;*/
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

                @media print {
                    .pagebreak {
                        page-break-before: always;
                    }

                    /* page-break-after works, as well */
                    #qtyDiv {
                        margin-top: 230px;
                    }
                }
            </style>
        </head>
        <body>
        <img src="bg-logo.png"
             style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <section>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-12">
                        <div class="d-flex align-items-start justify-content-between">
                            <div>
                                <img src="../assets/images/logo.png" alt="logo" class="img-fluid"
                                     style="width: 110px;">
                                <h6 class="mt-1">
                                    DAMAAN GENERAL TRADING L.L.C <br>
                                    Al Ras Deira Dubai office UAE <br>
                                    +971544186664 damaan.dubai@gmail.com
                                </h6>
                            </div>
                            <div class="text-end">
                                <h2 class="mt-4 fw-bold text-nowrap">ROZNAMCHA</h2>
                                <div class="mt-5">
                                    <h5 class="fw-bold text-uppercase">
                                        Sr No: <?php echo $r_id; ?><br>
                                        Date: <?php echo date('d M Y', strtotime($roz['r_date'])); ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1 gx-1">
                            <div class="col">
                                <div class="border p-1 border-dark">
                                    <h5 class="mb-0 fw-bold">Dr. ACCOUNT</h5>
                                    <h6 class="small">
                                        <b>A/C. #: </b><?php echo $dr_account['khaata_no'] ?><br>
                                        <b>A/C. NAME: </b><?php echo $dr_account['khaata_name'] ?><br>
                                        <b>COMPANY: </b><?php echo $dr_account['comp_name'] ?><br>
                                        <b>ADDRESS: </b><?php echo $dr_account['address'] ?><br>
                                        <b>CITY: </b><?php echo $dr_account['city'] ?>
                                    </h6>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border p-1 border-dark">
                                    <h5 class="mb-0 fw-bold">Cr. ACCOUNT</h5>
                                    <h6 class="small">
                                        <b>A/C. #: </b><?php echo $dr_account['khaata_no'] ?><br>
                                        <b>A/C. NAME: </b><?php echo $dr_account['khaata_name'] ?><br>
                                        <b>COMPANY: </b><?php echo $dr_account['comp_name'] ?><br>
                                        <b>ADDRESS: </b><?php echo $dr_account['address'] ?><br>
                                        <b>CITY: </b><?php echo $dr_account['city'] ?>
                                    </h6>
                                </div>
                            </div>
                            <div class="col d-none">
                                <h5 class="fw-bold">USER ID</h5>
                                <h6 class="small">
                                    <b>A/C. #: </b><?php echo $roz['username']; ?>
                                </h6>
                            </div>
                        </div>
                        <div class="row gx-1">
                            <div class="col-6">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="fw-bold text-uppercase">ROZNAMCHA NO</td>
                                        <td><?php echo $roz['roznamcha_no']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-uppercase">GOODS</td>
                                        <td><?php echo $roz['r_jins']; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="fw-bold text-uppercase">NAME</td>
                                        <td><?php echo $roz['r_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-uppercase">No.</td>
                                        <td><?php echo $roz['r_no']; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <td class="fw-bold text-uppercase">REPORT
                                            <span
                                                class="fw-normal text-decoration-underline"><?php echo $roz['details'] ?></span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col"><p><b>Buyer Signature</b></p></div>
                            <div class="col"><p><b>Seller Signature</b></p></div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <div class="border-top border-2 border-dark"></div>
                            </div>
                            <div class="col">
                                <div class="border-top border-2 border-dark"></div>
                            </div>
                            <div class="col-12">
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
        <?php
        if (isset($_GET['print'])) {
            echo '<script>window.print();</script>';
        }
    } else {
        echo '<script>window.location.href="../index.php";</script>';
    }
} else {
    echo '<script>window.location.href="../index.php";</script>';
} ?>
<div class="sticky-social">
    <ul class="social">
        <li class="facebook" data-tooltip="PDF پرنٹ کریں" data-tooltip-position="right">
            <a class="cursor-pointer" onclick="window.print();">
                <i class="fa fa-print"></i>
            </a>
        </li>
        <li class="twitter" data-tooltip="Excel فائل ڈاؤن لوڈ کریں" data-tooltip-position="right">
            <a class="cursor-pointer" onclick="window.print();">
                <i class="fa fa-file-excel-o"></i>
            </a>
        </li>
        <!--<li class="whatsapp">
            <a href="" class="whatsapp"><i class="fa fa-whatsapp"></i></a>
        </li>-->
    </ul>
</div>

