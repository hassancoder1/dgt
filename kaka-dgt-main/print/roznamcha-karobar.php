<?php if (isset($_GET['r_id']) && !(empty($_GET['r_id'])) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $r_id = mysqli_real_escape_string($connect, base64_decode($_GET['r_id']));
        $rQuery = mysqli_query($connect, "SELECT * FROM roznamchaas WHERE r_id = '$r_id'");
        $roz = mysqli_fetch_assoc($rQuery);

        $kh = fetch('khaata', array('id' => $roz['khaata_id']));
        $khaata = mysqli_fetch_assoc($kh);
        ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
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
        <body>
        <div class="container-fluid">
            <div class="p-1">
                <div class="row">
                    <div class="col">
                        <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid w-50">
                    </div>
                    <div class="col-6 text-center urdu-2 flex-column justify-content-center d-flex">
                        <h2>عصمت اللہ نجیب اللہ اینڈ کمپنی</h2>
                        <h6 class="mt-1">امپورٹ ایکسپورٹ کسٹم کلیئرنگ ایجنٹ</h6>
                    </div>
                    <div class="col text-end">
                        <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid w-50">
                    </div>
                </div>
            </div>
            <div class="px-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row gx-0">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">سیریل نمبر</label>
                                            <input id="no" class="form-control" value="<?php echo $r_id; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">تاریخ</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_date']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">آئی ڈی نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['username']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">کھاتہ برانچ نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo getTableDataByIdAndColName('branches', $roz['khaata_branch_id'], 'b_name'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0 my-4">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع کھاتہ نمبر</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['khaata_no']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع کھاتہ نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $khaata['khaata_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">کمپنی نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $khaata['comp_name']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu"> نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">نمبر</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_no']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع رقم</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['jmaa_amount']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0 my-4">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">تفصیل</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['details']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0 mt-5">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">منشی دستخط</label>
                                            <input type="text" id="no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col">

                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع کرنے والا دستخط</label>
                                            <input type="text" id="no" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="container-fluid">
            <div class="p-1">
                <div class="row">
                    <div class="col">
                        <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid w-50">
                    </div>
                    <div class="col-6 text-center urdu-2 flex-column justify-content-center d-flex">
                        <h2>عصمت اللہ نجیب اللہ اینڈ کمپنی</h2>
                        <h6 class="mt-1">امپورٹ ایکسپورٹ کسٹم کلیئرنگ ایجنٹ</h6>
                    </div>
                    <div class="col text-end">
                        <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid w-50">
                    </div>
                </div>
            </div>
            <div class="px-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row gx-0">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">سیریل نمبر</label>
                                            <input id="no" class="form-control" value="<?php echo $r_id; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">تاریخ</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_date']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">آئی ڈی نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['username']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">کھاتہ برانچ نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo getTableDataByIdAndColName('branches', $roz['khaata_branch_id'], 'b_name'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0 my-4">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع کھاتہ نمبر</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['khaata_no']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع کھاتہ نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $khaata['khaata_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">کمپنی نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $khaata['comp_name']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu"> نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_name']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">نمبر</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_no']; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع رقم</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['jmaa_amount']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0 my-4">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">تفصیل</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['details']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-0 mt-5">
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">منشی دستخط</label>
                                            <input type="text" id="no" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col">

                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">جمع کرنے والا دستخط</label>
                                            <input type="text" id="no" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
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

<div class="sticky-social d-print-none">
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
