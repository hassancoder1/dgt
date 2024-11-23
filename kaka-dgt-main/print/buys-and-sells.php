<?php $backUrl = '../';
if (isset($_POST['date_start']) && isset($_POST['date_end']) && isset($_POST['secret']) && (base64_decode($_POST['secret']) == "powered-by-upsol")) {
    require("../connection.php");
    include("../variables.php");
    $start_date = mysqli_real_escape_string($connect, $_POST['date_start']);
    $end_date = mysqli_real_escape_string($connect, $_POST['date_end']);
    $typesAllowed = array(BUYS, BUYS_ADD, SELLS, GP);
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    if (in_array($type, $typesAllowed)) {
        switch ($type) {
            case BUYS:
                $sql = "SELECT * FROM `buys` WHERE id > 0 ";
                $sql .= !empty($start_date) && !empty($end_date) ? " AND b_date BETWEEN '$start_date' AND '$end_date'" : "";
                $records = mysqli_query($connect, $sql);
                $totalRows = mysqli_num_rows($records);
                $title = 'خریداری جنرل فارم';
                $dates = empty($start_date) && empty($end_date) ? 'سارے ریکارڈز' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'buys',
                    'top' => array(
                        array('col_name' => 'تاریخ', 'col_val' => $dates, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $title, 'class' => 'col-4 px-4', 'input_class' => 'text-center line-height-0 urdu border-0'),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('سیریل نمبر' => '', 'یوزر' => '', 'تاریخ' => '', 'جنس' => '', 'لاٹ نام' => '', 'خرید شہر' => '', 'بل نمبر' => '', 'بنام اکاؤنٹ' => '',
                        'خریداری' => '',
                        'فروشی' => '',
                        'بیلنس' => '',
                    ),
                );
                break;
            case BUYS_ADD:
                $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id']);
                $records = fetch('buys_details', array('buys_id' => $buys_id));
                $totalRows = mysqli_num_rows($records);
                $title = 'خریداری کی تفصیل';
                $dates = empty($start_date) && empty($end_date) ? 'سارے ریکارڈز' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'buys-add?id=' . $buys_id,
                    'top' => array(
                        array('col_name' => 'خریداری سیریل', 'col_val' => $buys_id, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $title, 'class' => 'col-4 px-4', 'input_class' => 'text-center line-height-0 urdu border-0'),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('لاٹ نام' => '',
                        /*'بل نمبر' => '', 'کنٹینر نمبر' => '',*/
                        /*'لوڈنگ گودام' => '', 'خریدار نام' => '',*/
                        'باردانہ نام' => '', 'مارکہ' => '', 'باردانہ تعداد' => '', 'فی وزن' => '', 'ٹوٹل وزن' => '', 'صاف وزن' => '', 'رقم' => '', 'جمع کھاتہ' => ''),
                );
                break;
            case SELLS:
                $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id']);
                $records = fetch('buys_sold', array('buys_id' => $buys_id));
                $totalRows = mysqli_num_rows($records);
                $buysQ = fetch('buys', array('id' => $buys_id));
                $buys = mysqli_fetch_assoc($buysQ);
                $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum, SUM(total_wt) as total_wtSum, SUM(total_empty_wt) as total_empty_wtSum, SUM(saaf_wt) as saaf_wtSum, SUM(qeemat_raqam) as qeemat_raqamSum FROM buys_sold WHERE buys_id = '$buys_id'");
                $detailSums = mysqli_fetch_assoc($detailsQ);
                $title = ' فروشی کی تفصیل';
                $dates = empty($start_date) && empty($end_date) ? 'سارے ریکارڈز' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'sells-add?id=' . $buys_id . '&view=sale',
                    'top' => array(
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-1', 'input_class' => ''),
                        array('col_name' => 'جنس', 'col_val' => $buys['jins'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'لاٹ نام', 'col_val' => $buys['allot_name'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'ٹوٹل وزن', 'col_val' => $detailSums['total_wtSum'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'ٹوٹل صاف وزن', 'col_val' => $detailSums['saaf_wtSum'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'کل رقم', 'col_val' => $detailSums['qeemat_raqamSum'], 'class' => 'col', 'input_class' => ''),
                        //array('col_name' => 'ٹوٹل بیلنس', 'col_val' => sellBalance($buys_id), 'class' => 'col', 'input_class' => ''),
                    ),
                    'thead' => array('لاٹ نام	' => '', 'باردانہ نام	' => '', 'مارکہ' => '', 'باردانہ تعداد	' => '', 'فی وزن	' => '', 'ٹوٹل وزن	' => '', 'صاف وزن' => '', 'رقم' => ''),
                );
                break;
            case GP:
                $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id']);
                $records = fetch('buys_sold', array('buys_id' => $buys_id));/*, 'is_gp' => 1*/
                $totalRows = mysqli_num_rows($records);
                $title = ' گیٹ پاس کی تفصیل';
                $dates = empty($start_date) && empty($end_date) ? 'سارے ریکارڈز' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'sells-add?id=' . $buys_id . '&view=gp',
                    'top' => array(
                        array('col_name' => 'خریداری سیریل', 'col_val' => $buys_id, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $title, 'class' => 'col-4 px-4', 'input_class' => 'text-center line-height-0 urdu border-0'),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('بل نمبر' => '', 'تاریخ' => '', 'باردانہ نام' => '', 'جنس' => '', 'لاٹ نام' => '', 'لوڈنگ گودام' => '', 'بیچنے والا اکاؤنٹ' => '', 'بیچنے والا نام' => '', 'بروکر' => '', 'باردانہ تعداد' => ''),
                );
                break;
            default:
                $title = '';
                $infoArray = array();
                break;
        } ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title><?php echo $title . '___' . date('Y_m_d-H_i_s'); ?></title>
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link rel="stylesheet" href="../assets/css/style-rtl.min.css">
            <link rel="stylesheet" href="../assets/css/custom.css">
            <link rel="shortcut icon" href="../assets/images/anitco.png"/>
            <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="../assets/tooltip/tooltip.min.css">
            <style>
                .table-bordered {
                    border: 1px solid #000 !important;
                }

                .line-height-0 {
                    line-height: 0 !important;
                }

                .rtl {
                    direction: rtl !important;
                }

                .ltr {
                    direction: ltr !important;
                }

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
                    font-size: 9px !important;
                }

                .h-0 {
                    padding: 0;
                    height: 0 !important;
                }
            </style>
        </head>
        <body>
        <div class="container-fluid px--0">
            <div class="row gx-0 justify-content-center">
                <div class="col-lg-12">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="urdu">
                            <h3 class="fw-bold mb-0 ">فروشی کی تفصیل</h3>
                            <br><b>خریداری سیریل نمبر </b>
                            <?php echo $buys['bail_no']; ?>
                        </div>
                        <div class="text-end">
                            <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid"
                                 style="width: 80px;">
                            <h6 class="font-size-11">BISMILLAH &amp; BROTHERS</h6></div>
                    </div>
                    <?php //include("inc-print-top-sm.php"); ?>
                    <div class="card rounded-0 shadow-none border-0">
                        <div class="card-body pt-2 px-1 mb-0">
                            <div class="row justify-content-center gy-0 gx-0">
                                <?php foreach ($infoArray['top'] as $arr) {
                                    echo '<div class="' . $arr['class'] . '"><div class="input-group">';
                                    echo '<label class="input-group-text ps-0 urdu">' . $arr['col_name'] . '</label>';
                                    echo '<input class="form-control ' . $arr['input_class'] . '" value="' . $arr['col_val'] . '">';
                                    echo '</div></div>';
                                } ?>
                            </div>
                            <table class="table table-bordered mt-3">
                                <thead>
                                <tr>
                                    <?php foreach ($infoArray['thead'] as $item => $value) {
                                        echo '<th width="' . $value . '%">' . $item . '</th>';
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                switch ($type) {
                                    case BUYS:
                                        while ($loading = mysqli_fetch_assoc($records)) {
                                            echo '<tr>';
                                            echo '<td>' . $loading["id"] . '</td>';
                                            echo '<td>' . $loading["username"] . '</td>';
                                            echo '<td>' . $loading["b_date"] . '</td>';
                                            echo '<td>' . $loading["jins"] . '</td>';
                                            echo '<td>' . $loading["allot_name"] . '</td>';
                                            echo '<td>' . $loading["loading_city"] . '</td>';
                                            echo '<td>' . $loading["bail_no"] . '</td>';
                                            echo '<td>' . $loading["bnaam_khaata_no"] . '</td>';
                                            echo '<td>' . buyBalance($loading['id']) . '</td>';
                                            echo '<td>' . sellBalance($loading['id']) . '</td>';
                                            echo '<td>' . buySellBalance($loading['id']) . '</td>';
                                            echo '</tr>';
                                        }
                                        break;
                                    case BUYS_ADD:
                                        while ($loading = mysqli_fetch_assoc($records)) {
                                            $parentq = fetch('buys', array('id' => $buys_id));
                                            $parent = mysqli_fetch_assoc($parentq);
                                            $dr_khaata_json = json_decode($parent['dr_khaata_json']);
                                            $jmaa_khaata_noo = empty($dr_khaata_json) ? '' : $dr_khaata_json->jmaa_khaata_no;
                                            echo '<tr>';
                                            //echo '<td>' . $loading["bill_no"] . '</td>';
                                            //echo '<td>' . $loading["container_no"] . '</td>';
                                            echo '<td>' . $loading["allot_name"] . '</td>';
                                            //echo '<td>' . $loading["loading_godam"] . '</td>';
                                            //echo '<td>' . $loading["owner_name"] . '</td>';
                                            echo '<td>' . $loading["bardana_name"] . '</td>';
                                            echo '<td>' . $loading["marka"] . '</td>';
                                            echo '<td>' . $loading["bardana_qty"] . '</td>';
                                            echo '<td>' . $loading["per_wt"] . '</td>';
                                            echo '<td>' . $loading["total_wt"] . '</td>';
                                            echo '<td>' . $loading["saaf_wt"] . '</td>';
                                            echo '<td>' . $loading["qeemat_raqam"] . '</td>';
                                            echo '<td>' . $jmaa_khaata_noo . '</td>';
                                            echo '</tr>';
                                            echo '<tr>';
                                            echo '<td colspan="13">تفصیل: ' . $loading["more_details"] . '</td>';
                                            echo '</tr>';
                                        }
                                        break;
                                    case SELLS:
                                        $sales = mysqli_query($connect, "SELECT * FROM `buys_sold` WHERE buys_id = $buys_id ORDER BY qeemat_raqam ASC ");
                                        while ($sale = mysqli_fetch_assoc($sales)) {
                                            echo '<tr>';
                                            echo '<td>' . $sale["allot_name"] . '</td>';
                                            echo '<td>' . $sale['bardana_name'] . '</td>';
                                            echo '<td>' . $sale['marka'] . '</td>';
                                            echo '<td>' . $sale['bardana_qty'] . '</td>';
                                            echo '<td>' . $sale['per_wt'] . '</td>';
                                            echo '<td>' . $sale['total_wt'] . '</td>';
                                            echo '<td>' . $sale['saaf_wt'] . '</td>';
                                            echo '<td>' . $sale['qeemat_raqam'] . '</td>';
                                            echo '</tr>';
                                        }
                                        break;
                                    case GP:
                                        while ($loading = mysqli_fetch_assoc($records)) {
                                            $seller_khaataQ = fetch('khaata', array('id' => $loading['seller_khaata_id']));
                                            $seller_khaata = mysqli_fetch_assoc($seller_khaataQ);
                                            echo '<tr>';
                                            echo '<td>' . $loading["bill_no"] . '</td>';
                                            echo '<td>' . $loading["s_date"] . '</td>';
                                            echo '<td>' . $loading["bardana_name"] . '</td>';
                                            echo '<td>' . $loading["jins"] . '</td>';
                                            echo '<td>' . $loading["allot_name"] . '</td>';
                                            echo '<td>' . $loading["loading_godam"] . '</td>';
                                            echo '<td>' . $loading["seller_khaata_no"] . '</td>';
                                            echo '<td>' . $seller_khaata['khaata_name'] . '</td>';
                                            echo '<td>' . $loading["broker_name"] . '</td>';
                                            echo '<td>' . $loading["bardana_qty"] . '</td>';
                                            echo '</tr>';
                                        }
                                        break;
                                    default:
                                        break;
                                } ?>
                                </tbody>
                            </table>
                            <div class="row mt-2">
                                <div class="col-4">
                                    <div class="input-group">
                                        <label class="input-group-text urdu">تاریخ / ٹائم</label>
                                        <input class="form-control ltr form-control-sm"
                                               value="<?php echo date('Y-m-d H:i:s'); ?>">
                                    </div>
                                </div>
                                <div class="col"></div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <label class="input-group-text urdu">منشی دستخط</label>
                                        <input class="form-control" value=""></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="خریداری فارم میں واپس" data-tooltip-position="right">
                    <a href="../<?php echo $infoArray['url']; ?>"><i
                                class="fa fa-long-arrow-left"></i></a>
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
    <?php } else {
        echo '<script>window.location.href="' . $backUrl . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backUrl . '";</script>';
} ?>

