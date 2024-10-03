<?php if (isset($_GET['r_id']) && !(empty($_GET['r_id'])) && isset($_GET['secret'])) {
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
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>Invoice<?php echo $r_id . '_' . date('Y_m_d-H_i_s'); ?></title>
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
        <div class="container-fluid">
            <div class="p-1">
                <?php include ("inc-print-top-sm.php");?>
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
                                            <label for="no" class="input-group-text urdu">بینک نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo getTableDataByIdAndColName('banks', $roz['bank_id'], 'bank_name'); ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">تاریخ ادائیگی</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_date_payment']; ?>">
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
                                <div class="row gx-0 mt-4">
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
                        <h2>عصمت اللہ نجیب اللہ انیڈ کمپنی</h2>
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
                                            <label for="no" class="input-group-text urdu">بینک نام</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo getTableDataByIdAndColName('banks', $roz['bank_id'], 'bank_name'); ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group">
                                            <label for="no" class="input-group-text urdu">تاریخ ادائیگی</label>
                                            <input type="text" id="no" class="form-control"
                                                   value="<?php echo $roz['r_date_payment']; ?>">
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
                                <div class="row gx-0 mt-4">
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

