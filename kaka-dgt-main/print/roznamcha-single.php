<?php $backURL = '../roznamcha';
if (!empty($_GET['r_id']) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $r_id = mysqli_real_escape_string($connect, base64_decode($_GET['r_id']));
        $rQuery = mysqli_query($connect, "SELECT * FROM roznamchaas WHERE r_id = '$r_id'");
        $roz = mysqli_fetch_assoc($rQuery);

        $kh = fetch('khaata', array('id' => $roz['khaata_id']));
        $khaata = mysqli_fetch_assoc($kh);
        if ($roz['jmaa_amount'] > 0) {
            $amount = $roz['jmaa_amount'];
            $dr_cr = 'جمع';
            $qty = $roz['jmaa_qty'];
        } else {
            $amount = $roz['bnaam_amount'];
            $dr_cr = 'بنام';
            $qty = $roz['bnaam_qty'];
        }
        ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="BISMILLAH & BROTHERS Import Export Wholesaler">
            <meta name="author" content="BISMILLAH & BROTHERS">
            <meta name="keywords" content="Import Export Wholesaler">
            <title><?php echo ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME)); ?>
                _<?php echo $roz['r_date']; ?>_<?php echo $roz['khaata_no']; ?></title>
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link rel="stylesheet" href="../assets/css/style-rtl.min.css">
            <link rel="stylesheet" href="../assets/css/custom.css">
            <link rel="shortcut icon" href="../assets/images/anitco.png"/>
            <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="../assets/tooltip/tooltip.min.css">
            <style>
                input {
                    pointer-events: none;
                    font-weight: bold !important;
                    font-family: 'Noto Naskh Arabic', serif;
                }
            </style>
        </head>
        <body class="urdu">
        <!--<img src="bg-logo.png" style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">-->
        <section>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <?php for ($i = 1; $i <= 2; $i++) { ?>
                        <div class="col-lg-6 col-12">
                            <div class="px-0 position-relative"
                                 style="min-height:430px; position:absolute; z-index:444;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="text-start">
                                        <h2 class="fw-bold text-nowrap">روزنامچہ</h2>
                                        <div class="mt-3">
                                            <h6><span class="bold">سیریل نمبر</span><?php echo $roz['branch_serial']; ?>
                                            </h6>
                                            <h6 class="mt-2"><span
                                                    class="bold">تاریخ </span><?php echo date('M-d-Y', strtotime($roz['r_date'])); ?>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="">بسم اللہ اینڈ برادرز</h4>
                                        <h6 class="small-4 mt-3">امپورٹ ایکسپورٹ ہول سیلر</h6>
                                    </div>
                                    <div>
                                        <img src="../assets/images/anitco.webp" alt="logo" class="img-fluid"
                                             style="width: 120px;">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-1 mt-3 mb-2 border border-dark p-2">
                                    <div class="small text-nowrap text-uppercase">
                                        <?php echo '<b>آئی ڈی نام</b> ' . $roz['username'] . '<br>';
                                        echo '<b>کھاتہ برانچ نام</b> ' . branchName($roz['khaata_branch_id']) . '<br>'; ?>
                                    </div>
                                    <div class="small text-uppercase">
                                        <?php echo '<b>' . $dr_cr . ' </b>';
                                        echo '<span class="bold">کھاتہ نمبر </span>';
                                        echo $roz['khaata_no'] . '<br>';
                                        echo '<b>کھاتہ نام</b> ' . $khaata['khaata_name'] . '<br>';
                                        echo '<b>کمپنی نام</b> ' . $khaata['comp_name'] . '<br>';
                                        //echo '<b>Address</b> ' . $khaata['address'] . '<br>'; ?>
                                    </div>
                                    <div></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><b>روزنامچہ نمبر</b> <?php echo $roz['roznamcha_no']; ?></div>
                                    <div><b>نام</b> <?php echo $roz['r_name']; ?></div>
                                    <div><b>نمبر</b> <?php echo $roz['r_no']; ?></div>
                                    <?php echo !empty($roz['mobile']) ? '<div><b>موبائل</b> ' . $roz['mobile'] . '</div>' : ''; ?>
                                </div>
                                <div class="mt-2 d-flex justify-content-between">
                                    <div>
                                        <b>تفصیل</b>
                                        <u class="small">
                                            <?php echo $roz['details'];

                                            echo $qty . ' ' . $roz['currency'] . ' <b>Price</b>' . $roz['per_price']; ?>
                                        </u>
                                    </div>
                                    <div class="text-nowrap">
                                        <b><?php echo $dr_cr . ' رقم'; ?></b> <?php echo $amount; ?>
                                    </div>
                                </div>
                                <div class="row align-items-center mt-5">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="mb-5"><b>جمع کرنے والا دستخط</b></p>
                                            </div>
                                            <div class="col">
                                                <div class="border-top border-2 border-dark"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="mb-5"><b>منشی دستخط</b></p>
                                            </div>
                                            <div class="col">
                                                <div class="border-top border-2 border-dark"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php echo $i == 1 ? '<hr class="my-2 mb-5">' : ''; ?>
                    <?php } ?>
                </div>
            </div>
        </section>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="Go Back" data-tooltip-position="right">
                    <a href="<?php echo $backURL; ?>"><i class="fa fa-arrow-left"></i></a>
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

