<?php $url_index = '../';
if (isset($_POST['secret']) && base64_decode($_POST['secret']) == "powered-by-upsol"
    && isset($_POST['r_type']) && isset($_POST['r_date_start']) && isset($_POST['r_date_end'])
    && isset($_POST['url'])
) {
    require("../connection.php");
    include("../variables.php");
    $url = mysqli_real_escape_string($connect, $_POST['url']);
    $r_type = mysqli_real_escape_string($connect, $_POST['r_type']);
    $r_sub_type = isset($_POST['r_sub_type']) ? mysqli_real_escape_string($connect, $_POST['r_sub_type']) : $r_type;
    $start_date = mysqli_real_escape_string($connect, $_POST['r_date_start']);
    $end_date = mysqli_real_escape_string($connect, $_POST['r_date_end']);
    $typesAllowed = array(GENERAL, KAROBAR, BANK, BANK_CHEQUE, BILL, BILL_CURRENCY);
    $grandArray = array();
    if (in_array($r_sub_type, $typesAllowed)) {
        if ($r_type == 'general') {
            $sql = "SELECT * FROM roznamchaas WHERE r_id > 0 ";
        } else {
            $sql = "SELECT * FROM roznamchaas WHERE r_type= '$r_type' ";
        }
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        if (isset($_POST['username']) && !empty($_POST['username'])) {
            $username = mysqli_real_escape_string($connect, $_POST['username']);
            $sql .= " AND username LIKE " . "'%$username%'" . " ";
        }
        if (isset($_POST['branch_id']) && $_POST['branch_id'] > 0) {
            $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id']);
            $sql .= " AND branch_id = " . "'$branch_id'" . " ";
        }
        $recordStats = mysqli_query($connect, $sql);
        $totalRows = mysqli_num_rows($recordStats);
        $bnaamTotal = $jmaaTotal = $mezan = 0;
        while ($stat = mysqli_fetch_assoc($recordStats)) {
            $bnaamTotal += $stat['bnaam_amount'];
            $jmaaTotal += $stat['jmaa_amount'];
        }
        $mezan = $jmaaTotal - $bnaamTotal;

        switch ($r_sub_type) {
            case KAROBAR:
                $grandArray = array(
                    'title' => 'کاروبار روزنامچہ',
                    'thead' => array('سیریل', 'برانچ', 'تاریخ', 'یوزر', 'کھاتہ نمبر', 'روزنامچہ نمبر', 'نام', 'نمبر', 'تفصیل', 'جمع', 'بنام'),
                    'tbody' => array('',),
                );
        }
        $urlArray = array(
            'general' => array('r_type' => 'general', 'title' => 'جنرل روزنامچہ'),
            'karobar' => array('r_type' => 'karobar', 'title' => 'کاروبار روزنامچہ'),
            'bank' => array('r_type' => 'bank', 'title' => 'بینک روزنامچہ'),
            'bank_cheque' => array('r_type' => 'bank', 'title' => 'چیک روزنامچہ'),
            'bill' => array('r_type' => 'general', 'title' => 'بل روزنامچہ'),
        );

        $page = $urlArray[$r_type];
        $title = $page['title']; ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title><?php echo $title . '_' . date('Y_m_d-H_i_s'); ?></title>
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

                .table tbody tr td {
                    font-size: 11px;
                }

                .table thead tr th {
                    font-size: 11px;
                    /*background: black;
                    color: white;*/
                }

                p .small, p span {
                    font-size: 10px !important;
                }

                .title-input {
                    font-weight: bold !important;
                    font-size: 1rem !important;
                    line-height: 1px;
                    padding: 2px !important;
                }

                .form-control {
                    border-bottom: 1px solid #000 !important;
                    font-size: 11px;
                    padding: 0;
                }

                .table-bordered {
                    border: 1px solid #000 !important;
                }

                .table-borderless {
                    border: none !important;
                }

                label {
                    font-size: 11px !important;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-12">
                    <?php include("inc-print-top-sm.php"); ?>
                    <?php $topArray = array(
                        array('col_name' => 'تاریخ', 'col_val' => $start_date . ' to ' . $end_date, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $title, 'class' => 'col-5 px-4', 'input_class' => 'text-center title-input '),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل جمع', 'col_val' => $jmaaTotal, 'class' => 'col-3', 'input_class' => ''),
                        array('col_name' => 'کل بنام', 'col_val' => $bnaamTotal, 'class' => 'col-3', 'input_class' => ''),
                        array('col_name' => 'میزان', 'col_val' => $mezan, 'class' => 'col-3', 'input_class' => ''),
                    ); ?>
                    <div class="card rounded-0 shadow-none border-0">
                        <div class="card-body pt-2 mb-0 px-0">
                            <div class="row justify-content-center row-cols-3 gy-3 gx-0">
                                <?php foreach ($topArray as $arr) {
                                    echo '<div class="' . $arr['class'] . '"><div class="input-group">';
                                    echo '<label class="input-group-text ps-0 urdu">' . $arr['col_name'] . '</label>';
                                    echo '<input class="form-control ' . $arr['input_class'] . '" value="' . $arr['col_val'] . '">';
                                    echo '</div></div>';
                                } ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3">
                                    <thead>
                                    <tr class="">
                                        <th>#</th>
                                        <th>سیریل</th>
                                        <th>برانچ</th>
                                        <th>تاریخ</th>
                                        <th>کھاتہ نمبر</th>
                                        <th>نام</th>
                                        <th>نمبر</th>
                                        <th width="30%">تفصیل</th>
                                        <th>جمع</th>
                                        <th>بنام</th>
                                        <!--<th style="font-size: 12px">جمع بنام</th>
                                        <th>ٹوٹل</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $data = mysqli_query($connect, $sql);
                                    $jmaa = $bnaam = $balance = $no = 0;
                                    while ($roz = mysqli_fetch_assoc($data)) {
                                        $no++;
                                        echo '<tr>';
                                        echo '<td>' . $no . '</td>';
                                        //echo '<td>' . $roz['r_id'] . '</td>';
                                        echo '<td>';
                                        echo Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial'];
                                        echo '</td>';
                                        echo '<td>' . getTableDataByIdAndColName('branches', $roz['branch_id'], 'b_name') . '</td>';
                                        echo '<td>' . $roz["r_date"] . '</td>';
                                        //echo '<td>' . getTableDataByIdAndColName('users', $roz['user_id'], 'username') . '</td>';
                                        //echo '<td>' . $roz['roznamcha_no'] . '</td>';
                                        echo '<td>' . $roz['khaata_no'] . '</td>';
                                        echo '<td>' . $roz['r_name'] . '</td>';
                                        echo '<td>' . $roz['r_no'] . '</td>';
                                        $jmaaBnaamString = "";
                                        $jmaa += $roz['jmaa_amount'];
                                        $bnaam += $roz['bnaam_amount'];
                                        $balance = $jmaa - $bnaam;
                                        if ($roz['jmaa_amount'] == 0) {
                                            $jmaaBnaamString = "بنام";
                                        }
                                        if ($roz['bnaam_amount'] == 0) {
                                            $jmaaBnaamString = "جمع";
                                        }

                                        $bank_str = "";
                                        if ($roz['r_type'] == "bank") {
                                            $bank_str = ' <span class="small-2">تاریخ ادائیگی: ' . $roz['r_date_payment'] . '</span> ';
                                            $bank_str .= ' <span class="small-2">بینک: ' . getTableDataByIdAndColName('banks', $roz['bank_id'], 'bank_name') . '</span> ';
                                        }
                                        echo '<td>' . $jmaaBnaamString . ':- ' . $bank_str . $roz["details"] . ' </td > ';
                                        echo '<td> ' . $roz['jmaa_amount'] . ' </td > ';
                                        echo '<td class="text-danger"> ' . $roz['bnaam_amount'] . ' </td > ';
                                        //echo '<td> ' . $jmaaBnaamString . '</td > ';
                                        //echo '<td class="ltr"> ' . $balance . '</td > ';
                                        echo '</tr> ';
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="<?php echo $title; ?>"
                    data-tooltip-position="right">
                    <a href="../<?php echo $url; ?>"><i class="fa fa-long-arrow-left"></i></a>
                </li>
                <li class="facebook"
                    title="PDF پرنٹ کریں">
                    <a class="cursor-pointer" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </a>
                </li>
            </ul>
        </div>
        </body>
        </html>
    <?php }
} else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
}
die();

if (isset($_GET['r_id']) && !(empty($_GET['r_id'])) && isset($_GET['secret'])) {
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
