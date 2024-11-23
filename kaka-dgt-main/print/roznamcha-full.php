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
            $branchName = getTableDataByIdAndColName('branches', $branch_id, 'b_name');
            $sql .= " AND branch_id = " . "'$branch_id'" . " ";
        } else {
            $branchId = $_SESSION['branch_id'];
            $branchName = Administrator() ? 'آل برانچ' : getTableDataByIdAndColName('branches', $branchId, 'b_name');
        }
        $records = mysqli_query($connect, $sql);
        $recordStats = mysqli_query($connect, $sql);
        $totalRows = mysqli_num_rows($recordStats);
        $bnaamTotal = $jmaaTotal = $mezan = $bnaam_qtyTotal = $jmaa_qtyTotal = $mezanQty = 0;
        while ($stat = mysqli_fetch_assoc($recordStats)) {
            $bnaamTotal += $stat['bnaam_amount'];
            $jmaaTotal += $stat['jmaa_amount'];
            $bnaam_qtyTotal += $stat['bnaam_qty'];
            $jmaa_qtyTotal += $stat['jmaa_qty'];
        }
        $mezan = $jmaaTotal - $bnaamTotal;
        $mezanQty = $jmaa_qtyTotal - $bnaam_qtyTotal;
        $infoArray = array(
            GENERAL => array('title' => 'جنرل روزنامچہ'),
            KAROBAR => array('title' => 'کاروبار روزنامچہ'),
            BANK => array('title' => 'بینک روزنامچہ'),
            BANK_CHEQUE => array('title' => 'چیک روزنامچہ'),
            BILL => array('title' => 'بل روزنامچہ'),
            BILL_CURRENCY => array('title' => 'بلہ چمک'),
        );
        switch ($r_sub_type) {
            case GENERAL:
                $grandArray = array(
                    'top' => array(
                        array('col_name' => 'تاریخ', 'col_val' => $start_date . ' to ' . $end_date, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $infoArray[$r_sub_type]['title'], 'class' => 'col-4 px-4', 'input_class' => 'text-center title-input '),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'برانچ', 'col_val' => $branchName, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل جمع', 'col_val' => $jmaaTotal, 'class' => 'col-3', 'input_class' => ''),
                        array('col_name' => 'کل بنام', 'col_val' => $bnaamTotal, 'class' => 'col-3', 'input_class' => ''),
                        array('col_name' => 'میزان', 'col_val' => $mezan, 'class' => 'col-3', 'input_class' => ''),
                    ),
                    'thead' => array('#' => '', 'سیریل' => '', 'برانچ' => '', 'یوزر' => '', 'کھاتہ نمبر' => '', 'روزنامچہ نمبر' => '', 'نام' => '15', 'نمبر' => '', 'تفصیل' => '30', 'جمع' => '', 'بنام' => ''),
                );
                break;
            case KAROBAR:
            case BANK:
                $grandArray = array(
                    'top' => array(
                        array('col_name' => 'تاریخ', 'col_val' => $start_date . ' to ' . $end_date, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $infoArray[$r_sub_type]['title'], 'class' => 'col-4 px-4', 'input_class' => 'text-center title-input '),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'برانچ', 'col_val' => $branchName, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل جمع', 'col_val' => $jmaaTotal, 'class' => 'col-3', 'input_class' => ''),
                        array('col_name' => 'کل بنام', 'col_val' => $bnaamTotal, 'class' => 'col-3', 'input_class' => ''),
                        array('col_name' => 'میزان', 'col_val' => $mezan, 'class' => 'col-3', 'input_class' => ''),
                    ),
                    'thead' => array('#' => '', 'سیریل' => '', 'برانچ' => '', 'تاریخ' => '', 'یوزر' => '', 'کھاتہ نمبر' => '', 'روزنامچہ نمبر' => '', 'نام' => '15', 'نمبر' => '', 'تفصیل' => '30', 'جمع' => '', 'بنام' => ''),
                );
                break;
            case BANK_CHEQUE:
                $grandArray = array(
                    'top' => array(
                        array('col_name' => 'تاریخ', 'col_val' => $start_date . ' to ' . $end_date, 'class' => 'col', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $infoArray[$r_sub_type]['title'], 'class' => 'col px-3', 'input_class' => 'text-center title-input '),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'برانچ', 'col_val' => $branchName, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل بنام', 'col_val' => $bnaamTotal, 'class' => 'col', 'input_class' => ''),
                    ),
                    'thead' => array('#' => '', 'تاریخ اندراج' => '', 'سیریل' => '', 'برانچ' => '', 'یوزر' => '', 'کھاتہ نمبر' => '',
                        'روزنامچہ نمبر' => '', 'تاریخ ادائیگی' => '', 'بینک نام' => '7', 'نام' => '', 'نمبر' => '', 'تفصیل' => '30', 'بنام' => ''),
                );
                break;
            case BILL:
                $grandArray = array(
                    'top' => array(
                        array('col_name' => 'تاریخ', 'col_val' => $start_date . ' to ' . $end_date, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $infoArray[$r_sub_type]['title'], 'class' => 'col-4 px-4', 'input_class' => 'text-center title-input '),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'برانچ', 'col_val' => $branchName, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل جمع', 'col_val' => $jmaaTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل بنام', 'col_val' => $bnaamTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'میزان', 'col_val' => $mezan, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل جمع تعداد', 'col_val' => $jmaa_qtyTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل بنام تعداد', 'col_val' => $bnaam_qtyTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'میزان', 'col_val' => $mezanQty, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('#' => '', 'سیریل' => '', 'برانچ' => '', 'تاریخ' => '', 'یوزر' => '', 'کھاتہ نمبر' => '', 'روزنامچہ نمبر' => '', 'نام' => '', 'نمبر' => '', 'جنس' => '', 'تفصیل' => '20', 'فی قیمت' => '', 'جمع تعداد' => '', 'بنام تعداد' => '', 'جمع' => '', 'بنام' => ''),
                );
                break;
            case BILL_CURRENCY:
                $grandArray = array(
                    'top' => array(
                        array('col_name' => 'تاریخ', 'col_val' => $start_date . ' to ' . $end_date, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $infoArray[$r_sub_type]['title'], 'class' => 'col-4 px-4', 'input_class' => 'text-center title-input '),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'برانچ', 'col_val' => $branchName, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل جمع', 'col_val' => $jmaaTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل بنام', 'col_val' => $bnaamTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'میزان', 'col_val' => $mezan, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل جمع تعداد', 'col_val' => $jmaa_qtyTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'کل بنام تعداد', 'col_val' => $bnaam_qtyTotal, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => 'میزان', 'col_val' => $mezanQty, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('#' => '', 'سیریل' => '', 'برانچ' => '', 'یوزر' => '', 'کھاتہ نمبر' => '', 'روزنامچہ نمبر' => '', 'نام' => '', 'نمبر' => '', 'جنس' => '', 'تفصیل' => '20', 'جمع' => '', 'بنام' => ''),
                );
                break;
            default:
                $grandArray = array('' => '', 'top' => array(), 'thead' => array('' => ''));
                break;
        }
        $title = $infoArray[$r_sub_type]['title'];
        $jmaaBnaamString = ""; ?>
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
                .font9 {
                    font-size: 9px !important;
                }

                .font8 {
                    font-size: 8px !important;
                }

                .font7 {
                    font-size: 7px !important;
                }

                input {
                    pointer-events: none;
                    font-weight: bold !important;
                    font-family: 'Noto Naskh Arabic', serif;
                }

                .table tbody tr td {
                    font-size: 10px;
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
                    <div class="card rounded-0 shadow-none border-0">
                        <div class="card-body pt-2 mb-0 px-0">
                            <div class="row justify-content-center row-cols--3 gy-3 gx-0">
                                <?php foreach ($grandArray['top'] as $arr) {
                                    echo '<div class="' . $arr['class'] . '"><div class="input-group">';
                                    echo '<label class="input-group-text ps-0 urdu">' . $arr['col_name'] . '</label>';
                                    echo '<input class="form-control ' . $arr['input_class'] . '" value="' . $arr['col_val'] . '">';
                                    echo '</div></div>';
                                } ?>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered mt-3">
                                    <thead>
                                    <tr>
                                        <?php foreach ($grandArray['thead'] as $item => $value) {
                                            echo '<th width="' . $value . '%">' . $item . '</th>';
                                        } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $no = 0;
                                    while ($roz = mysqli_fetch_assoc($records)) {
                                        $no++;
                                        $branchSerial = Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial'];
                                        if ($roz['jmaa_amount'] == 0) {
                                            $jmaaBnaamString = "بنام";
                                        }
                                        if ($roz['bnaam_amount'] == 0) {
                                            $jmaaBnaamString = "جمع";
                                        }
                                        echo '<tr>';
                                        echo '<td>' . $no . '</td>';
                                        switch ($r_sub_type) {
                                            case GENERAL:
                                                echo '<td>' . $branchSerial . '</td>';
                                                echo '<td class="font8">' . branchName($roz['branch_id']) . '</td>';
                                                echo '<td>' . userName($roz['user_id']) . '</td>';
                                                echo '<td>' . $roz['khaata_no'] . '</td>';
                                                echo '<td>' . $roz['roznamcha_no'] . '</td>';
                                                echo '<td class="font9">' . $roz['r_name'] . '</td>';
                                                echo '<td>' . $roz['r_no'] . '</td>';
                                                echo '<td class="font9">' . $jmaaBnaamString . ':- ' . $roz["details"] . ' </td> ';
                                                echo '<td class="bold">' . $roz['jmaa_amount'] . ' </td> ';
                                                echo '<td class="bold">' . $roz['bnaam_amount'] . ' </td> ';
                                                break;
                                            case KAROBAR:
                                            case BANK:
                                                echo '<td>' . $branchSerial . '</td>';
                                                echo '<td class="font8">' . branchName($roz['branch_id']) . '</td>';
                                                echo '<td>' . $roz["r_date"] . '</td>';
                                                echo '<td>' . userName($roz['user_id']) . '</td>';
                                                echo '<td>' . $roz['khaata_no'] . '</td>';
                                                echo '<td>' . $roz['roznamcha_no'] . '</td>';
                                                echo '<td>' . $roz['r_name'] . '</td>';
                                                echo '<td>' . $roz['r_no'] . '</td>';
                                                echo '<td>' . $jmaaBnaamString . ':- ' . $roz["details"] . ' </td> ';
                                                echo '<td class="bold">' . $roz['jmaa_amount'] . ' </td> ';
                                                echo '<td class="bold">' . $roz['bnaam_amount'] . ' </td> ';
                                                break;

                                            case BANK_CHEQUE:
                                                echo '<td>' . $branchSerial . '</td>';
                                                echo '<td>' . $roz["r_date"] . '</td>';
                                                echo '<td class="font8">' . branchName($roz['branch_id']) . '</td>';
                                                echo '<td>' . userName($roz['user_id']) . '</td>';
                                                echo '<td>' . $roz['khaata_no'] . '</td>';
                                                echo '<td>' . $roz['roznamcha_no'] . '</td>';
                                                echo '<td>' . $roz['r_date_payment'] . '</td>';
                                                echo '<td class="font8">' . bankName($roz['bank_id']) . '</td>';
                                                echo '<td>' . $roz['r_name'] . '</td>';
                                                echo '<td>' . $roz['r_no'] . '</td>';
                                                echo '<td class="font9">' . '<span class="border-end pe-1">' . $roz['r_date_payment'] . '</span><span class="border-end px-1 me-1">' . bankName($roz['bank_id']) . '</span>' . $roz["details"] . ' </td> ';
                                                echo '<td class="bold">' . $roz['bnaam_amount'] . ' </td> ';
                                                break;
                                            case BILL:
                                                echo '<td>' . $branchSerial . '</td>';
                                                echo '<td class="">' . branchName($roz['branch_id']) . '</td>';
                                                echo '<td>' . $roz["r_date"] . '</td>';
                                                echo '<td>' . userName($roz['user_id']) . '</td>';
                                                echo '<td>' . $roz['khaata_no'] . '</td>';
                                                echo '<td>' . $roz['roznamcha_no'] . '</td>';
                                                echo '<td>' . $roz['r_name'] . '</td>';
                                                echo '<td>' . $roz['r_no'] . '</td>';
                                                echo '<td>' . $roz['r_jins'] . '</td>';
                                                echo '<td>' . $jmaaBnaamString . ':- ' . $roz["details"] . ' </td> ';
                                                echo '<td>' . $roz['per_price'] . '</td>';
                                                echo '<td>' . $roz['jmaa_qty'] . '</td>';
                                                echo '<td>' . $roz['bnaam_qty'] . '</td>';
                                                echo '<td class="bold">' . $roz['jmaa_amount'] . ' </td> ';
                                                echo '<td class="bold">' . $roz['bnaam_amount'] . ' </td> ';
                                                break;
                                            case BILL_CURRENCY:
                                                echo '<td>' . $branchSerial . '</td>';
                                                echo '<td class="">' . branchName($roz['branch_id']) . '</td>';
                                                //echo '<td>' . $roz["r_date"] . '</td>';
                                                echo '<td>' . userName($roz['user_id']) . '</td>';
                                                echo '<td>' . $roz['khaata_no'] . '</td>';
                                                echo '<td>' . $roz['roznamcha_no'] . '</td>';
                                                echo '<td>' . $roz['r_name'] . '</td>';
                                                echo '<td>' . $roz['r_no'] . '</td>';
                                                echo '<td>' . $roz['r_jins'] . '</td>';
                                                echo '<td>' . $jmaaBnaamString . ':- ' .
                                                    '<span>فی قیمت:' . $roz['per_price'] . '</span> ' .
                                                    '<span>جمع تعداد:' . $roz['jmaa_qty'] . '</span> ' .
                                                    '<span>بنام تعداد:' . $roz['bnaam_qty'] . '</span> ' .
                                                    $roz["details"] . ' </td> ';
                                                echo '<td class="bold">' . $roz['jmaa_amount'] . ' </td> ';
                                                echo '<td class="bold">' . $roz['bnaam_amount'] . ' </td> ';
                                                break;
                                            default:
                                                break;
                                        }
                                        echo '</tr>';
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
} ?>