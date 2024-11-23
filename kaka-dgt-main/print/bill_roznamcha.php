<?php $backUrl = '../';
if (isset($_GET['type'])) {
    require("../connection.php");
    include("../variables.php");
    $typesAllowed = array(BR_AFG_TRUCK_KIRAYA, BR_IMPORT_CUSTOM_EXP, BR_DT_CUSTOM_EXP, BR_OFFICE_EXP, BR_HOME_EXP);
    $getType = mysqli_real_escape_string($connect, $_GET['type']);
    if (in_array($getType, $typesAllowed)) {
        if (isset($_GET['id']) && ($_GET['id'] > 0) && isset($_GET['secret'])) {
            if (base64_decode($_GET['secret']) == "powered-by-upsol") {
                $id = mysqli_real_escape_string($connect, $_GET['id']);
                $sql = "SELECT * FROM `$getType` WHERE id = '$id' ";
                $datu = mysqli_query($connect, $sql);
                $data = mysqli_fetch_assoc($datu);
                switch ($getType) {
                    case BR_AFG_TRUCK_KIRAYA:
                        $infoArray = array('title' => 'افغانی ٹرک کرایہ', 'row_cols' => 3, 'backUrl' => 'afghani-truck-kiraya',
                            'jmaa' => $data['afg_jmaa_khaata_no'], 'bnaam' => $data['afg_bnaam_khaata_no'],
                            'jmaa_id' => $data['afg_jmaa_khaata_id'], 'bnaam_id' => $data['afg_bnaam_khaata_id'],
                            'total_bill' => $data['total_bill']);
                        $l_godam = getTableDataByIdAndColName('godam_loading_forms', $data['godam_loading_id'], 'name');
                        $e_godam = getTableDataByIdAndColName('godam_empty_forms', $data['godam_empty_id'], 'name');
                        $topArray = array(
                            array('col_name' => 'تاریخ', 'col_val' => $data['afg_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'بھیجنے والا نام', 'col_val' => $data['sender_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'بھیجنے والا شہر', 'col_val' => $data['sender_city'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ٹرک نمبر', 'col_val' => $data['afg_truck_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ٹرک نام', 'col_val' => $data['afg_truck_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ڈرائیور نام', 'col_val' => $data['driver_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'موبائل ', 'col_val' => '<span dir="ltr">' . $data['driver_mobile'] . '</span>', 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'لوڈنگ کرنے گودام', 'col_val' => $l_godam, 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'خالی کرنے گودام', 'col_val' => $e_godam, 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),);
                        break;
                    case BR_IMPORT_CUSTOM_EXP:
                        $infoArray = array('title' => 'امپورٹ کسٹم خرچہ', 'row_cols' => 3, 'backUrl' => 'import-kharcha','jmaa' => $data['jmaa_khaata_no'], 'bnaam' => $data['bnaam_khaata_no'],'jmaa_id' => $data['jmaa_khaata_id'], 'bnaam_id' => $data['bnaam_khaata_id'],'total_bill' => $data['total_bill']);
                        $topArray = array(
                            array('col_name' => 'تاریخ', 'col_val' => $data['exp_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'کنسائینی نام', 'col_val' => $data['sender_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'کنسائینی شہر', 'col_val' => $data['sender_city'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ٹرک نمبر', 'col_val' => $data['truck_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ٹرک نام', 'col_val' => $data['truck_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ڈرائیور نام', 'col_val' => $data['driver_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'موبائل ', 'col_val' => '<span dir="ltr">' . $data['driver_mobile'] . '</span>', 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'کسٹم کلیئر تاریخ', 'col_val' => $data['custom_clear_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'جی ڈی نمبر', 'col_val' => $data['gd_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'جنس', 'col_val' => $data['jins'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                        );
                        break;
                    case BR_DT_CUSTOM_EXP:
                        $infoArray = array('title' => 'ڈاؤن ٹرانزٹ کسٹم خرچہ', 'row_cols' => 3, 'backUrl' => 'dt-custom-kharcha',
                            'jmaa' => $data['jmaa_khaata_no'], 'bnaam' => $data['bnaam_khaata_no'],
                            'jmaa_id' => $data['jmaa_khaata_id'], 'bnaam_id' => $data['bnaam_khaata_id'], 'total_bill' => $data['total_bill']);
                        $topArray = array(
                            array('col_name' => 'تاریخ', 'col_val' => $data['exp_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'کنسائینی نام', 'col_val' => $data['sender_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'کنسائینی شہر', 'col_val' => $data['sender_city'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ٹرک نمبر', 'col_val' => $data['truck_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ٹرک نام', 'col_val' => $data['truck_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'ڈرائیور نام', 'col_val' => $data['driver_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'موبائل ', 'col_val' => '<span dir="ltr">' . $data['driver_mobile'] . '</span>', 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'کسٹم کلیئر تاریخ', 'col_val' => $data['custom_clear_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'جی ڈی نمبر', 'col_val' => $data['gd_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'جنس', 'col_val' => $data['jins'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                        );
                        break;
                    case BR_OFFICE_EXP:
                        $infoArray = array('title' => 'آفس خرچہ', 'row_cols' => 3, 'backUrl' => 'office-exp',
                            'jmaa' => $data['jmaa_khaata_no'], 'bnaam' => $data['bnaam_khaata_no'],
                            'jmaa_id' => $data['jmaa_khaata_id'], 'bnaam_id' => $data['bnaam_khaata_id'], 'total_bill' => $data['total_bill']);
                        $topArray = array(
                            array('col_name' => 'بل نمبر', 'col_val' => $data['bill_no'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'تاریخ', 'col_val' => $data['exp_date'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'بل دینے والا نام', 'col_val' => $data['bill_giver'], 'class' => 'col-8', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                        );
                        break;
                    case BR_HOME_EXP:
                        $infoArray = array('title' => 'گھر کا خرچہ', 'row_cols' => 3, 'backUrl' => 'home-exp',
                            'jmaa' => $data['jmaa_khaata_no'], 'bnaam' => $data['bnaam_khaata_no'],
                            'jmaa_id' => $data['jmaa_khaata_id'], 'bnaam_id' => $data['bnaam_khaata_id'], 'total_bill' => $data['total_bill']);
                        $topArray = array(
                            array('col_name' => 'بل نمبر', 'col_val' => $data['bill_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'تاریخ', 'col_val' => $data['exp_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            array('col_name' => 'بل دینے والا نام', 'col_val' => $data['bill_giver'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                        );
                        break;
                    default:
                        $infoArray = array('row_cols' => 3, 'backUrl' => '../', 'jmaa' => 0, 'bnaam' => 0,
                            'jmaa_id' => 0, 'bnaam_id' => 0, 'total_bill' => 0);
                        $topArray = array();
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
                    <title><?php echo $infoArray['title'] . '_' . $id . '_' . date('Y_m_d-H_i_s'); ?></title>
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
                    </style>
                </head>
                <body>
                <div class="container-fluid px--0">
                    <div class="row gx-1">
                        <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <div class="col-6  ">
                                <div class="row align-items-center mx-2">
                                    <div class="col flex-column justify-content-center d-flex">
                                        <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid w---50"
                                             style="width: 80px;">
                                    </div>
                                    <div class="col-10 text-center  urdu-2 flex-column justify-content-center d-flex ">
                                        <h5>عصمت اللہ نجیب اللہ جنرل ٹریڈنگ کمپنی</h5>
                                        <!--<h6 class="mt-1">امپورٹ ایکسپورٹ کسٹم کلیئرنگ ایجنٹ</h6>-->
                                        <p style="font-size: 10px;" class="mb-0">ایڈریس: سناتن بازار ہدایت پلازہ فلور
                                            نمبر 1 چمن <span>(نوید پلازہ سیکنڈ فلور آفس نمبر 7 نصفی روڑ کوئٹہ )</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="card rounded-0 shadow-none border-0">
                                    <div class="card-body pt-2 p-0 mb-0">
                                        <div class="row justify-content-center gy-3 gx-0">
                                            <div class="col-12 text-center">
                                                <h3 class="urdu"><?php echo $infoArray['title']; ?></h3>
                                            </div>
                                            <?php $jmaa_data = khaataSingle($infoArray['jmaa_id']); ?>
                                            <?php $bnaam_data = khaataSingle($infoArray['bnaam_id']);
                                            $jmaaBnaamArray = array(
                                                array('col_name' => 'col-2', 'label' => ' جمع کھاتہ نمبر ', 'value' => $infoArray['jmaa']),
                                                array('col_name' => 'col-3', 'label' => '  کھاتہ نام ', 'value' => $jmaa_data['khaata_name']),
                                                array('col_name' => 'col-4', 'label' => ' کمپنی ', 'value' => $jmaa_data['comp_name']),
                                                array('col_name' => 'col-3', 'label' => ' موبائل ', 'value' => '<span dir="ltr">' . $jmaa_data['mobile'] . '</span>'),

                                                array('col_name' => 'col-2', 'label' => ' بنام کھاتہ نمبر ', 'value' => $infoArray['bnaam']),
                                                array('col_name' => 'col-3', 'label' => '  کھاتہ نام ', 'value' => $bnaam_data['khaata_name']),
                                                array('col_name' => 'col-4', 'label' => ' کمپنی ', 'value' => $bnaam_data['comp_name']),
                                                array('col_name' => 'col-3', 'label' => ' موبائل ', 'value' => '<span dir="ltr">' . $bnaam_data['mobile'] . '</span>'),
                                            );
                                            foreach ($jmaaBnaamArray as $item) {
                                                echo '<div class="' . $item['col_name'] . '"><p class="urdu m-0 p-0">';
                                                echo '<span class="bold">' . $item['label'] . '</span>';
                                                echo '<span class="ms-2">' . $item['value'] . '</span>';
                                                echo '</p></div>';
                                            } ?>
                                        </div>

                                        <div class="row justify-content-center row-cols-<?php echo $infoArray['row_cols']; ?> gx-0 gy-3 mt-3">
                                            <?php foreach ($topArray as $arr) {
                                                echo '<div class="' . $arr['class'] . '"><p class="urdu small"><span class="bold">' .
                                                    $arr['col_name'] . ' </span>';
                                                echo '<span style="" class="small ms-2 ' . $arr['span_class'] . '"  ' . $arr['span_attr'] . ' id="' . $arr['span_id'] . '">' .
                                                    $arr['col_val'] . '</span></p></div>';
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="card-body p-2 pt-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mt-3 border-dark">
                                                <thead>
                                                <tr>
                                                    <th width="20%">خرچہ نام</th>
                                                    <th width="65%">تفصیل</th>
                                                    <th>رقم</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $exp_names = json_decode($data["exp_names"]);
                                                $exp_details = json_decode($data["exp_details"]);
                                                $exp_values = json_decode($data["exp_values"]);
                                                foreach ($exp_names as $index => $value) {
                                                    echo '<tr>';
                                                    echo '<td>' . $exp_names[$index] . '</td>';
                                                    echo '<td>' . $exp_details[$index] . '</td>';
                                                    echo '<td>' . $exp_values[$index] . '</td>';
                                                    //echo '<td class="text-danger"> ' . $datum['bnaam_amount'] . ' </td > ';
                                                    echo '</tr> ';
                                                } ?>
                                                <tr>
                                                    <td></td>
                                                    <td class="bold" align="left">ٹوٹل بل</td>
                                                    <td class="bold"><?php echo $infoArray['total_bill']; ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row mb-2 justify-content-center" style="">
                                        <div class="col">
                                            <p class="urdu">
                                                <span class="bold ms-1">  </span>
                                                <span class="small"></span>
                                            </p>
                                        </div>
                                        <div class="col text-end me-2">
                                            <p>دستخط: ______________________</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <script src="../assets/tooltip/tooltip.min.js"></script>
                <div class="sticky-social d-print-none">
                    <ul class="social">
                        <li class="bg-dark" data-tooltip="<?php echo $infoArray['title']; ?>"
                            data-tooltip-position="right">
                            <a href="../<?php echo $infoArray['backUrl']; ?>"><i
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
                <?php if (isset($_GET['print'])) {
                    echo '<script>window.print();</script>';
                }
            } else {
                echo '<script>window.location.href="' . $backUrl . '";</script>';
            }
        } else {
            echo '<script>window.location.href="' . $backUrl . '";</script>';
        }
    } else {
        echo '<script>window.location.href="' . $backUrl . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backUrl . '";</script>';
} ?>

