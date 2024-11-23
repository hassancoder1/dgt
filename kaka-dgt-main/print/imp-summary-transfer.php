<?php $backUrl = '../';
if (isset($_GET['type'])) {
    require("../connection.php");
    include("../variables.php");
    require("check-session.php");
    $typesAllowed = array('beopari_summary', 'kiraya_summary', 'godam_mazdoori');
    $getType = mysqli_real_escape_string($connect, $_GET['type']);
    if (in_array($getType, $typesAllowed)) {
        if (isset($_GET['id']) && ($_GET['id'] > 0) && isset($_GET['secret'])) {
            if (base64_decode($_GET['secret']) == "powered-by-upsol") {
                $id = mysqli_real_escape_string($connect, $_GET['id']);
                $records = fetch('imp_truck_loadings', array('id' => $id));
                $record = mysqli_fetch_assoc($records);
                $urlArray = array(
                    'kiraya_summary' => array('path' => 'imp-kiraya-summary', 'title' => ' کرایہ سمری', 'type' => 'کرایہ سمری',
                        'transfered_from' => 'kiraya_summary', 'khaata_' => 'khaata_ks', 'back_url_type' => 'kiraya-summary'),
                    'beopari_summary' => array('path' => 'imp-beopari-summary', 'title' => 'امپورٹ بیوپاری سمری / کمپنی بلہ', 'type' => 'امپورٹ بیوپاری سمری',
                        'transfered_from' => 'beopari_summary', 'khaata_' => 'khaata_bs', 'back_url_type' => 'beopari-summary'),
                    'godam-mazdoori' => array('path' => 'imp-godam-mazdoori-bill', 'title' => ' گودام مزدوری بل ابھی نامکمل ہے۔ ', 'type' => 'گودام مزدوری بل',
                        'transfered_from' => 'godam_mazdoori', 'khaata_' => 'khaata_gm', 'back_url_type' => 'godam-mazdoori')
                );
                $page = $urlArray[$getType];
                $title = $page['title'];

                $khaataJson = json_decode($record[$page['khaata_']]);
                //var_dump($khaataJson);
                $bnaamKhaataQ = fetch('khaata', array('id' => $khaataJson->bnaam_khaata_id));
                $bnaamKhaata = mysqli_fetch_assoc($bnaamKhaataQ);

                $infoArray = array('title' => $page['title'], 'row_cols' => 5,
                    'backUrl' => 'imp-summary-transfer?id=' . $id . '&type=' . $page['back_url_type'],
                    'jmaa' => $khaataJson->jmaa_khaata_no, 'bnaam' => $khaataJson->bnaam_khaata_no, 'total_bill' => $khaataJson->total_bill);
                $driver_mobile = '<span dir="ltr">' . $record['driver_mobile'] . '</span>';
                $loadings = fetch('godam_loading_forms', array('id' => $record['godam_loading_id']));
                $loading = mysqli_fetch_assoc($loadings);
                $loadin_mobile = '<span dir="ltr">' . $loading['mobile1'] . '</span>';
                $empties = fetch('godam_empty_forms', array('id' => $record['godam_empty_id']));
                $empty = mysqli_fetch_assoc($empties);
                $empty_mobile = '<span dir="ltr">' . $empty['mobile1'] . '</span>';
                $topArray = array(
                    array('col_name' => 'تاریخ', 'col_val' => $record['loading_date'], 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => '', 'col_val' => $title, 'class' => 'col-6 px-4', 'input_class' => 'text-center title-input '),
                    array('col_name' => 'سمری نمبر', 'col_val' => $id, 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => 'ٹرک نمبر', 'col_val' => $record['truck_no'], 'class' => 'col', 'input_class' => ''),
                    //array('col_name' => 'ٹرک نام', 'col_val' => $record['truck_name'], 'class' => 'col', 'input_class' => ''),
                    array('col_name' => 'ڈرائیور نام', 'col_val' => $record['driver_name'], 'class' => 'col', 'input_class' => ''),
                    array('col_name' => 'موبائل', 'col_val' => $empty['mobile1'], 'class' => 'col', 'input_class' => 'ltr'),
                    array('col_name' => 'بھیجنے والا شہر', 'col_val' => $record['sender_city'], 'class' => 'col', 'input_class' => ''),
                    array('col_name' => 'کنسائینی نام', 'col_val' => $record['consignee_name'], 'class' => 'col', 'input_class' => ''),

                    array('col_name' => 'ٹرانسفر کھاتہ نمبر', 'col_val' => $infoArray['bnaam'], 'class' => 'col-2', 'input_class' => ''),
                    array('col_name' => ' کھاتہ کانام', 'col_val' => $bnaamKhaata['khaata_name'], 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => ' کمپنی نام', 'col_val' => $bnaamKhaata['comp_name'], 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => ' پتہ', 'col_val' => $bnaamKhaata['address'], 'class' => 'col-2', 'input_class' => 'ltr'),
                    array('col_name' => ' موبائل', 'col_val' => $bnaamKhaata['mobile'], 'class' => 'col-2', 'input_class' => 'ltr'),


                    array('col_name' => 'مالک نام', 'col_val' => $record['owner_name'], 'class' => 'col-2', 'input_class' => ''),
                    array('col_name' => 'لوڈنگ کرنے گودام', 'col_val' => $loading['name'], 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => 'پتہ', 'col_val' => $loading['address'], 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => 'منشی', 'col_val' => $loading['munshi'], 'class' => 'col-2', 'input_class' => ''),
                    array('col_name' => 'موبائل', 'col_val' => $loading['mobile1'], 'class' => 'col-2', 'input_class' => 'ltr'),

                    array('col_name' => 'خالی کرنے گودام', 'col_val' => $empty['name'], 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => 'پتہ', 'col_val' => $empty['address'], 'class' => 'col-3', 'input_class' => ''),
                    array('col_name' => 'منشی', 'col_val' => $empty['munshi'], 'class' => 'col-2', 'input_class' => ''),
                    array('col_name' => 'موبائل', 'col_val' => $empty['mobile1'], 'class' => 'col-2', 'input_class' => 'ltr'),
                    array('col_name' => 'موبائل', 'col_val' => $empty['mobile2'], 'class' => 'col-2', 'input_class' => 'ltr'),
                    ///array('col_name' => 'رپورٹ', 'col_val' => $record['report'], 'class' => 'col', 'input_class' => ''),
                );
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
                    <title><?php echo $infoArray['title'] . '_' . $id . '_' . date('Y_m_d-H_i_s'); ?></title>
                    <link rel="preconnect" href="https://fonts.googleapis.com/">
                    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
                    <link rel="stylesheet" href="../assets/css/style-rtl.min.css">
                    <link rel="stylesheet" href="../assets/css/custom.css">
                    <link rel="shortcut icon" href="../assets/images/anitco.png"/>
                    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
                    <link rel="stylesheet" href="../assets/tooltip/tooltip.min.css">
                    <link rel="stylesheet" href="../assets/css/virtual-select.min.css">
                    <style>
                        body {
                            background-color: white;
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
                        .input-group-text{
                            padding-left: 1px;
                        }
                    </style>
                </head>
                <body>
                <div class="container-fluid">
                    <div class="row justify-content--center">
                        <div class="col-md-8 col-12">
                            <div class="row ">
                                <?php for ($i = 1; $i <= 1; $i++) { ?>
                                    <div class="col-12">
                                        <div class="row align-items-center mx-2">
                                            <div class="col flex-column justify-content-center d-flex">
                                                <img src="../assets/images/print-logo.png" alt="logo"
                                                     class="img-fluid w---50"
                                                     style="width: 80px;">
                                            </div>
                                            <div class="col-10 text-center  urdu-2 flex-column justify-content-center d-flex ">
                                                <h5>عصمت اللہ نجیب اللہ جنرل ٹریڈنگ کمپنی</h5>
                                                <p style="font-size: 10px;" class="mb-0">ایڈریس: سناتن بازار ہدایت پلازہ
                                                    فلور
                                                    نمبر 1 چمن
                                                    <span>(نوید پلازہ سیکنڈ فلور آفس نمبر 7 نصفی روڑ کوئٹہ )</span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="card rounded-0 shadow-none border-0">
                                            <div class="card-body pt-2 mb-0 px-0">
                                                <div class="row justify-content-center row-cols-<?php echo $infoArray['row_cols']; ?> gy-3 gx-0">
                                                    <?php foreach ($topArray as $arr) {
                                                        echo '<div class="' . $arr['class'] . '"><div class="input-group">';
                                                        echo '<label class="input-group-text ps-0 urdu">' . $arr['col_name'] . '</label>';
                                                        echo '<input class="form-control ' . $arr['input_class'] . '" value="' . $arr['col_val'] . '">';
                                                        echo '</div></div>';
                                                    } ?>
                                                </div>
                                            </div>
                                            <div class="card-body px-0 pt-0">
                                                <table class="table table-bordered ">
                                                    <thead>
                                                    <tr>
                                                        <th> سیریل</th>
                                                        <th>جنس</th>
                                                        <th>تفصیلات۔۔باردانہ تعداد۔ باردانہ نام</th>
                                                        <th>تقسیم , فی خرچہ، ٹوٹل تقسیم</th>
                                                        <th>ٹوٹل قیمت</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="records_table">
                                                    <?php $maals = fetch('imp_truck_maals', array('imp_tl_id' => $id));
                                                    $x = 1;
                                                    $remainingRows = $taqseem_name = $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = $total_expFinal = 0;
                                                    while ($maal = mysqli_fetch_assoc($maals)) {
                                                        $maal2 = isKirayaAdded($maal['id'], $page['transfered_from']);
                                                        if ($maal2['success']) {
                                                            $maal2Id = $maal2['output']['id'];
                                                            $json2 = json_decode($maal2['output']['json_data']);
                                                            $taqseem_qty = $json2->taqseem_qty;
                                                            $taqseem_name = $json2->taqseem_name;
                                                            $per_mazdoori = $json2->per_mazdoori;
                                                            $total_exp = $json2->total_exp;
                                                            $total_expFinal += $json2->total_exp;
                                                        } else {
                                                            $maal2Id = $taqseem_qty = $per_mazdoori = $total_exp = 0;
                                                        }
                                                        $json = json_decode($maal['json_data']); ?>
                                                        <tr class="row-py-0">
                                                            <td><?php echo $x; ?></td>
                                                            <td><?php echo $json->jins_name; ?></td>
                                                            <td>
                                                                <?php echo $json->bardana_qty; ?>
                                                                <?php echo $json->bardana_name; ?>
                                                                <?php echo ' فی وزن ' . round($json->per_wt); ?>
                                                                <?php echo ' ٹوٹل وزن ' . round($json->total_wt); ?>
                                                                <?php echo ' خالی وزن ' . $json->empty_wt; ?>
                                                                <?php //echo $json->total_empty_wt; ?>
                                                                <?php echo ' صافی وزن ' . round($json->saaf_wt); ?>
                                                            </td>
                                                            <td class="p-0 border-top-0">
                                                                <table class="table table--bordered p-0">
                                                                    <tr>
                                                                        <td class="border-end border-dark border-bottom-0"><?php echo $taqseem_name; ?></td>
                                                                        <td class="border-end border-dark border-bottom-0"><?php echo $per_mazdoori; ?></td>
                                                                        <td><?php echo $taqseem_qty; ?></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                            <td><?php echo round($total_exp); ?></td>
                                                        </tr>
                                                        <?php $x++;
                                                    } ?>
                                                    <tr class="row-py-0 bold">
                                                        <td colspan="3" align="left">ٹوٹل</td>
                                                        <td></td>
                                                        <td>
                                                            <span id="total_exp_final"><?php echo round($total_expFinal); ?></span>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <?php $total_bill = 0;
                                                if ($getType == "beopari-summary") {
                                                    $exps = isImpExtraExpenseAdded($id, 'beopari_summary_ee');
                                                    if ($exps['success']) {
                                                        $json2 = json_decode($exps['output']['json_data']);
                                                        $total_bill = $json2->total_bill;
                                                        $exp_names = $json2->exp_names;
                                                        $exp_details = $json2->exp_details;
                                                        $exp_values = $json2->exp_values; ?>
                                                        <table class="table table-bordered mt-2 border-dark">
                                                            <thead>
                                                            <tr class="">
                                                                <th>خرچہ نام</th>
                                                                <th>تفصیل</th>
                                                                <th>رقم</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($exp_names as $index => $value) {
                                                                echo '<tr>';
                                                                echo '<td>' . $exp_names[$index] . '</td>';
                                                                echo '<td>' . $exp_details[$index] . '</td>';
                                                                echo '<td>' . $exp_values[$index] . '</td>';
                                                                echo '</tr> ';
                                                            }
                                                            echo '<tr style="font-weight: bold"><td colspan="2" align="left">ٹوٹل</td><td>' . $total_bill . '</td></tr>'; ?>
                                                            </tbody>
                                                        </table>
                                                    <?php } else { ?>
                                                        <div class="row mt-3">
                                                            <div class="col-8 ">
                                                                <h5 class="urdu-2">
                                                                    <span class="ms-1">اضافی خرچہ اور تفصیل</span>
                                                                </h5>
                                                            </div>
                                                            <div class="col-4">
                                                                <h5 class="urdu float-end ">
                                                                    <span class="bold me-3">0</span>
                                                                    <!--<span class="me-3"><?php /*echo round($infoArray['total_bill']); */ ?></span>-->
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    <?php }
                                                } ?>

                                                <div class="row my-3 gx-0 justify-content-center">
                                                    <div class="col">
                                                        <h6 class="urdu text-nowrap">
                                                            <span>ٹوٹل سمری کی رقم اور تفصیل</span></h6>
                                                    </div>
                                                    <div class="col text-nowrap">
                                                        <h6 class=""><?php echo AmountInWords($infoArray['total_bill'] + $total_bill); ?></h6>
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="text-end">
                                                            <span class=""><?php echo round($infoArray['total_bill'] + $total_bill); ?></span>
                                                        </h6>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center row-cols-4 gx-0 border-top border-dark border-2">
                                                    <div class="col">
                                                        <div class="input-group">
                                                            <label class="input-group-text urdu">پہنچ رسید نمبر</label>
                                                            <input class="form-control " value=""></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="input-group">
                                                            <label class="input-group-text urdu">پہنچ تاریخ</label>
                                                            <input class="form-control" value=""></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="input-group">
                                                            <label class="input-group-text urdu">پہنچ تعداد</label>
                                                            <input class="form-control" value=""></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="input-group">
                                                            <label class="input-group-text urdu">باردانہ بیلنس</label>
                                                            <input class="form-control" value=""></div>
                                                    </div>
                                                </div>
                                                <div class="container">
                                                    <div class="row pb-2 mt-3 border-top border-bottom border-dark border-2">
                                                        <div class="col">
                                                            <p class="urdu">
                                                                <span class="">سمری تفصیل:</span>
                                                                <?php $repp = fetch('imp_truck_reports', array('imp_tl_id' => $id));
                                                                if (mysqli_num_rows($repp) > 0) {
                                                                    $summary_report = mysqli_fetch_assoc($repp);
                                                                    echo '<span class="ms-2">' . $summary_report['report_summary'] . '</span>';
                                                                } else {
                                                                    echo ' &nbsp;&nbsp;&nbsp;&nbsp;--------------------------------------';
                                                                } ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center  gx-0 border-dark border-2">
                                                    <div class="col-12 text-center">
                                                        <p class="urdu mb-3">دستخط لازمی ہے۔ ایک سمری پیوپاری کو دینا ہے
                                                            اور ایک کمپنی کے ریکارڈ میں رکھنا ہے۔ بغیر دستخط قبول نہیں
                                                            ہے۔</p>
                                                    </div>
                                                    <div class="col">
                                                        <div class="input-group">
                                                            <label class="input-group-text urdu">لوڈنگ منشی
                                                                دستخط</label>
                                                            <input class="form-control " value=""></div>
                                                    </div>

                                                    <div class="col">
                                                        <div class="input-group">
                                                            <label class="input-group-text urdu">مال مالک دستخط</label>
                                                            <input class="form-control" value=""></div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="input-group">
                                                            <label class="input-group-text urdu">کمپنی مینیجر
                                                                دستخط</label>
                                                            <input class="form-control" value=""></div>
                                                    </div>
                                                </div>
                                                <div class="container">
                                                    <div class="row justify-content-center row-cols-3 mt-3 pt-2 border-top border-dark border-2">
                                                        <div class="col">
                                                            <p class="urdu">مال میں رد وبدل کی کمپنی ذمہ دار نہ ہو
                                                                گی۔</p>
                                                        </div>
                                                        <div class="col">
                                                            <p class="urdu">بل میں ڈبلنگ کی بھی کمپنی ذمہ دار نہ ہو
                                                                گی۔</p>
                                                        </div>
                                                        <div class="col">
                                                            <h6 class="text-end">
                                                                <span class="urdu"> تاریخ: </span>
                                                                <?php echo date('d/m/Y') ?>
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="../assets/tooltip/tooltip.min.js"></script>
                <div class="sticky-social d-print-none">
                    <ul class="social">
                        <li class="bg-dark" data-tooltip="<?php echo $page['title']; ?>"
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
                    <select class="virtual-select border" style="z-index: 9" id="summary-select">
                        <?php $form_name = $getType;
                        //$sers = fetch('imp_truck_loadings', array('is_transfered' => 1, 'is_saved' => 1));
                        $sers = mysqli_query($connect, "SELECT DISTINCT i.*
                        FROM imp_truck_loadings i
                        JOIN imp_truck_maals2 m ON i.id = m.imp_tl_id
                        WHERE m.form_name = '$form_name'
                        ");
                        //$sql = "SELECT * FROM dt_truck_loadings WHERE is_transfered=1 AND is_saved=1";
                        while ($ser = mysqli_fetch_assoc($sers)) {
                            $selected_ser = $id == $ser['id'] ? 'selected' : '';
                            echo '<option ' . $selected_ser . ' value="' . $ser['id'] . '">سمری نمبر ' . $ser['id'] . '</option>';
                        } ?>
                    </select>
                </div>
                <div class="fixed-top d-print-none border-border-light " style="right:unset; left: 10px; width: 30%; top: 25%; z-index: -9">
                    <?php //roznamcha details
                    $transfered_from = $page['transfered_from'];
                    $rozSQL = "SELECT * FROM roznamchaas WHERE r_type = 'karobar' AND transfered_from = '$transfered_from' AND transfered_from_id = '$id'";
                    $rozQ1 = mysqli_query($connect, $rozSQL);
                    /*ORDER BY r_id DESC LIMIT 1*/
                    $roznamcha = mysqli_fetch_assoc($rozQ1);
                    //var_dump($roznamcha);
                    ?>
                    <h6 class="urdu mb-3 d-flex align-items-center justify-content-between mt-2">
                        <span><span>برانچ: </span><span><?php echo branchName($roznamcha['branch_id']); ?></span></span>
                        <span><span>یوزرآئی ڈی: </span><span><?php echo $roznamcha['username']; ?></span></span>
                    </h6>
                    <h6 class="urdu mb-3 d-flex align-items-center justify-content-between">
                        <span><span>لوڈ تاریخ: </span><span><?php echo $record['loading_date']; ?></span></span>
                        <span><span>ٹرانسفر تاریخ: </span><span><?php echo $roznamcha['r_date']; ?></span></span>
                    </h6>
                    <h5 class="mt-4 urdu text-muted">بنام اکاونٹ ٹوٹل تفصیل</h5>
                    <table class="table table-borderless">
                        <tr class="text-nowrap">
                            <td><span class="bold">کھاتہ نمبر: </span><?php echo $infoArray['bnaam']; ?></td>
                            <td><span class="bold">کھاتہ نام: </span><?php echo $bnaamKhaata['khaata_name']; ?></td>
                        </tr>
                        <tr class="text-nowrap">
                            <td><span class="bold">کمپنی نام: </span><?php echo $bnaamKhaata['comp_name']; ?></td>
                            <td><span class="bold">موبائل: </span><span
                                        dir="ltr"><?php echo $bnaamKhaata['mobile']; ?></span></td>
                        </tr>
                        <tr class="text-nowrap">
                            <td colspan="2"><span class="bold">پتہ: </span><?php echo $bnaamKhaata['address']; ?></td>
                        </tr>
                    </table>
                    <h5 class="mt-4 urdu text-muted">بنام بیلنس پرانا</h5>
                    <h5 class="mt-4 urdu text-muted">ٹرانسفر روزنامچہ تفصیل</h5>
                    <table class="table">
                        <tr>
                            <td>سیریل</td>
                            <td>کھاتہ نمبر</td>
                            <td>روزنامچہ نمبر</td>
                            <td>تفصیل</td>
                            <td>جمع</td>
                            <td>بنام</td>
                        </tr>
                        <?php $rozQ2 = mysqli_query($connect, $rozSQL);
                        while ($roz = mysqli_fetch_assoc($rozQ2)) { ?>
                            <tr>
                                <td><?php echo Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial']; ?></td>
                                <td><?php echo $roz['khaata_no']; ?></td>
                                <td><?php echo $roz['roznamcha_no']; ?></td>
                                <?php $str = "";
                                if ($roz['jmaa_amount'] == 0) {
                                    $str = "بنام:- ";
                                }
                                if ($roz['bnaam_amount'] == 0) {
                                    $str = "جمع:- ";
                                } ?>
                                <td><?php echo $str . $roz['details']; ?></td>
                                <td><?php echo $roz['jmaa_amount']; ?></td>
                                <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
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
<script src="../assets/js/virtual-select.min.js"></script>
<script type="text/javascript">
    VirtualSelect.init({
        ele: '.virtual-select',
        placeholder: '',
        searchPlaceholderText: 'تلاش کریں',
        search: true,
        autoSelectFirstOption: false,
        hideClearButton: true
    });
</script>
<script>
    document.querySelector('#summary-select').addEventListener('change', function () {
        window.$_GET = location.search.substr(1).split("&").reduce((o, i) => (u = decodeURIComponent, [k, v] = i.split("="), o[u(k)] = v && u(v), o), {});
        var urlString = "imp-summary-transfer?id=";
        //var id = obj.options[obj.selectedIndex];
        var id = this.value;
        console.log(id);
        urlString += id + '&secret=' + btoa('powered-by-upsol') + '&type=' + $_GET.type;

        window.location.href = urlString;
    });
</script>
