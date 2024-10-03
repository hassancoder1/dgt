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
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title><?php echo 'Roznamcha_' . my_date(date('Y-m-d')); ?> </title>
            <meta name="description" content="Owner of DGT.llc">
            <meta name="author" content="Asmatullah Abdullah">
            <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
            <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
            <link href="../assets/css/custom.css" rel="stylesheet">
            <link href="../assets/css/virtual-select.min.css" rel="stylesheet">
            <link href="../assets/fa/css/fontawesome.css" rel="stylesheet"/>
            <link href="../assets/fa/css/brands.css" rel="stylesheet"/>
            <link href="../assets/fa/css/solid.css" rel="stylesheet"/>
            <link rel="shortcut icon" href="../assets/images/favicon.jpg"/>
        </head>
        <body>
        <img src="bg-logo.png"
             style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <section>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <?php for ($i = 1; $i <= 2; $i++) { ?>
                        <div class="col-lg-6 col-12">
                            <div class="px-0 position-relative"
                                 style="min-height:430px; position:absolute; z-index:444;">
                                <div class="d-flex align-items-center justify-content-between border-bottom- mb-1">
                                    <img src="../assets/images/logo.png" alt="logo" class="img-fluid" width="100">

                                    <div class="text-end">
                                        <h3 class="fw-bold mb-0">Roznamcha</h3>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between gap-1 mt-1 mb-2 border border-dark px-1 small text-uppercase">
                                    <div class="text-nowrap">
                                        <?php echo '<b>' . ucfirst($roz['dr_cr']) . ' A/C</b> ' . $roz['khaata_no'] . '<br>';
                                        echo '<b>NAME</b> ' . $khaata['khaata_name'] . '<br>';
                                        echo '<b>Branch</b> ' . branchName($khaata['branch_id']); ?>
                                    </div>
                                    <div>
                                        <?php echo '<b>Category</b> ' . catName($khaata['cat_id']) . '<br>';
                                        echo '<b>Email</b> ' . $khaata['email'] . '<br>';
                                        echo '<b>Phone</b> ' . $khaata['phone']; ?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <div><b>Date</b> <?php echo my_date($roz['r_date']); ?></div>
                                    <div><b>Sr#: </b><?php echo $r_id . '-' . $roz['branch_serial']; ?></div>
                                    <div><b>ROZNAMCHA #</b> <?php echo $roz['roznamcha_no']; ?></div>
                                    <div class="mb-2"><b>NAME</b> <?php echo $roz['r_name']; ?></div>
                                    <b>NO.</b> <?php echo $roz['r_no']; ?>
                                </div>
                                <div class="d-flex justify-content-between mb-2 gap-2">
                                    <div><?php echo '<b>AMOUNT IN WORDS</b> <u>' . AmountInWords($roz['amount']); ?></u></div>
                                    <div class="text-nowrap"><?php echo '<b>' . ucfirst($roz['dr_cr']) . '. AMOUNT</b> ' . $roz['amount']; ?></div>
                                </div>
                                <div class="mb-2">
                                    <b>DETAILS</b>
                                    <u>
                                        <?php echo $roz['details'];
                                        echo !empty($roz['qty']) ? ' (' . $roz['qty'] . ')' : '';
                                        echo !empty($roz['currency']) ? ' (' . $roz['currency'] . ')' : '';
                                        echo !empty($roz['per_price']) ? '<b>Price</b>' . $roz['per_price'] : ''; ?>
                                    </u>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><?php echo !empty($roz['c_name']) ? '<div><b>NAME</b> ' . $roz['c_name'] . '</div>' : ''; ?>
                                        <?php echo !empty($roz['mobile']) ? '<div><b>MOB.</b> ' . $roz['mobile'] . '</div>' : ''; ?></div>
                                    <div>
                                        <?php echo $roz['img'] != '' ? '<img src="../' . $roz['img'] . '" width="100" height="100" class="img-fluid m-0">' : ''; ?>
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
                        <?php echo $i == 1 ? '<hr class="my-5" style="border: 2px #000 dashed;">' : ''; ?>
                    <?php } ?>
                </div>
            </div>
        </section>
        <div class="list-group d-print-none shadow-lg position-fixed rounded-0 start-0" style="top: 40%">
            <a href="../roznamcha" class="list-group-item list-group-item-secondary p-1"><i
                        class="fa fa-arrow-left"></i>
                Back</a>
            <a onclick="window.print();" href="#." class="list-group-item list-group-item-secondary p-1"><i
                        class="fa fa-print"></i> Print</a>
        </div>
        <script src="../assets/js/jquery-3.7.1.min.js"></script>
        <script src="../assets/bs/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/virtual-select.min.js"></script>
        <script>
            $("#rows_span").text($("#rows").val());
            var dr_total = $("#dr_total").val();
            var cr_total = $("#cr_total").val();
            var bal = Number(dr_total) - Number(cr_total);
            $("#dr_total_span").text(dr_total);
            $("#cr_total_span").text(cr_total);
            $("#bal_span").text(bal);

            if (bal > 0) {
                $("#bal_span").addClass('text-success');
            } else if (bal < 0) {
                $("#bal_span").addClass('text-danger');
            }


        </script>
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

