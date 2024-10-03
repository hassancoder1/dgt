<?php $backUrl = './';
if (isset($_GET['buys_id']) && ($_GET['buys_id'] > 0) && isset($_GET['bd_id']) && ($_GET['bd_id'] > 0) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        include("../variables.php");
        $buys_id = mysqli_real_escape_string($connect, $_GET['buys_id']);
        $records = fetch('buys', array('id' => $buys_id));
        $record = mysqli_fetch_assoc($records);
        $buys_details_id = mysqli_real_escape_string($connect, $_GET['bd_id']);
        $buys_details_q = fetch('buys_details', array('id' => $buys_details_id));
        $buys_details = mysqli_fetch_assoc($buys_details_q);
        $backUrl = 'buys-add?id=' . $buys_id . '&bd_id=' . $buys_details_id . '&action=update';

        $bnn = fetch('khaata', array('id' => $record['bnaam_khaata_id']));
        $bnaam = mysqli_fetch_assoc($bnn);
        $jmm = fetch('khaata', array('id' => $buys_details['jmaa_khaata_id']));
        $jmaa = mysqli_fetch_assoc($jmm);

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
            <title>خریداری فارم_<?php echo $record['id'] . '_' . $buys_details['bill_no']; ?>
                ____<?php echo date('Y_m_d-H_i_s'); ?>
            </title>
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link rel="stylesheet" href="../assets/css/style-rtl.min.css">
            <link rel="stylesheet" href="../assets/css/custom.css">
            <link rel="shortcut icon" href="../assets/images/anitco.png"/>
            <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="../assets/tooltip/tooltip.min.css">
            <style>
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

                /*.table thead tr th {
                    font-size: 11px;
                    background: black;
                    color: white;
                }*/

                p .small, p span {
                    font-size: 9px !important;
                }
                .h-0{
                    padding: 0;
                    height: 0 !important;
                }
            </style>
        </head>
        <body>
        <div class="container-fluid px--0">
            <div class="row gx-4">
                <?php for ($i = 1; $i <= 2; $i++) { ?>
                    <div class="col-6">
                        <div class="row align-items-center mx-2">
                            <div class="col flex-column justify-content-center d-flex">
                                <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid w---50"
                                     style="width: 80px;">
                            </div>
                            <div class="col-10 text-center  urdu-2 flex-column justify-content-center d-flex ">
                                <h5>عصمت اللہ نجیب اللہ جنرل ٹریڈنگ کمپنی</h5>
                                <p style="font-size: 10px;" class="mb-0">ایڈریس: سناتن بازار ہدایت پلازہ فلور
                                    نمبر 1 چمن <span>(نوید پلازہ سیکنڈ فلور آفس نمبر 7 نصفی روڑ کوئٹہ )</span>
                                </p>
                            </div>
                        </div>
                        <div class="card rounded-0 shadow-none border-0">
                            <div class="card-body pt-2  mb-0">
                                <div class="border container border-dark pb-4">
                                    <div class="row justify-content--center row-cols-5 gy-3 ">
                                        <?php $buyArray = array(
                                            array('col_name' => 'تاریخ', 'col_val' => $record['b_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'شہر', 'col_val' => $record['loading_city'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'ٹرک نمبر', 'col_val' => 'AAAAA', 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'بل سیریل', 'col_val' => $record['id'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'بل نمبر', 'col_val' => $buys_details['bill_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),

                                            array('col_name' => 'بنام', 'col_val' => $record['bnaam_khaata_no'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'کھاتہ', 'col_val' => $bnaam['khaata_name'], 'class' => 'col-4', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'فون', 'col_val' => $bnaam['mobile'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),
                                            array('col_name' => 'واٹس ایپ', 'col_val' => $bnaam['whatsapp'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),

                                            array('col_name' => '', 'col_val' => '', 'class' => 'col-12 h-0 border border-dark', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),

                                            array('col_name' => 'لوڈنگ گودام', 'col_val' => $buys_details['loading_godam'], 'class' => 'col-12', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),

                                            array('col_name' => 'جمع', 'col_val' => $buys_details['jmaa_khaata_no'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'کھاتہ', 'col_val' => $jmaa['khaata_name'], 'class' => 'col-4', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'فون', 'col_val' => $jmaa['mobile'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),
                                            array('col_name' => 'واٹس ایپ', 'col_val' => $jmaa['whatsapp'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),

                                            array('col_name' => '', 'col_val' => '', 'class' => 'col-12 h-0 border border-dark', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),

                                            /*array('col_name' => 'کنٹینر نمبر', 'col_val' => $buys_details['container_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'لاٹ نام', 'col_val' => $buys_details['allot_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'خالی کرنے گودام', 'col_val' => $buys_details['empty_godam'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'خریدار نام', 'col_val' => $buys_details['owner_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'باردانہ نام', 'col_val' => $buys_details['bardana_name'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'مارکہ', 'col_val' => $buys_details['marka'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'ادائیگی تاریخ', 'col_val' => $buys_details['payment_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'مزید تفصیل', 'col_val' => $buys_details['more_details'], 'class' => 'col-12', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),*/
                                        );
                                        foreach ($buyArray as $arr) {
                                            echo '<div class="' . $arr['class'] . ' mb-0 mb-md-2"><p class="urdu small"><span class="bold">' .
                                                $arr['col_name'] . ' </span>';
                                            echo '<span style="text-decoration:underline" class="small ms-2 ' . $arr['span_class'] . '"  ' . $arr['span_attr'] . ' id="' . $arr['span_id'] . '">' .
                                                $arr['col_val'] . '</span></p></div>';
                                        } ?>
                                    </div>
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="text-dark">
                                            <th>جنس</th>
                                            <th>باردانہ</th>
                                            <th>تعداد</th>
                                            <th>تفصیل</th>
                                            <th>فی قیمت</th>
                                            <th>ٹوٹل قیمت</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><?php echo $record['jins']; ?></td>
                                            <td><?php echo $buys_details['bardana_name']; ?></td>
                                            <td><?php echo $buys_details['bardana_qty']; ?></td>
                                            <td>
                                                <?php echo 'فی وزن  ' . round($buys_details['per_wt']); ?>
                                                <?php echo 'فی وزن  ' . round($buys_details['per_wt']); ?>
                                                <?php echo ' خالی وزن ' . round($buys_details['empty_wt']); ?>
                                                <?php echo ' صافی وزن ' . round($buys_details['saaf_wt']); ?>
                                                <?php echo ' فی تقسیم ' . round($buys_details['taqseem_no']); ?>
                                                <?php echo ' ٹوٹل تقسیم ' . round($buys_details['taqseem_qty']); ?>
                                            </td>
                                            <td><?php echo round($buys_details['qeemat_name']); ?></td>
                                            <td><?php echo round($buys_details['qeemat_raqam']); ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="row mt-2 gy-3">
                                        <div class="col-10">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">بروکر خرچہ و تفصیل</label>
                                                <input type="text" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="0">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <label class="input-group-text urdu float-end">ٹوٹل</label>
                                        </div>
                                        <div class="col-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-3">
                                        <div class="col-10">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">اضافی خرچہ و تفصیل</label>
                                                <input type="text" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="0">
                                            </div>
                                        </div>
                                        <div class="col-10">
                                            <label class="input-group-text urdu float-end">صافی ٹوٹل</label>
                                        </div>
                                        <div class="col-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 gy-3">
                                        <div class="col-4">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">تاریخ ادائیگی</label>
                                                <input type="text" class="form-control" value="ِ<?php echo $buys_details['payment_date']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-2"></div>
                                        <div class="col-6">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">دستخط</label>
                                                <input type="text" class="form-control" value="">
                                            </div>
                                        </div>
                                    </div>
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
                <li class="bg-dark" data-tooltip="خریداری فارم میں واپس" data-tooltip-position="right">
                    <a href="../<?php echo $backUrl; ?>"><i
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
        echo '<script>window.location.href="../' . $backUrl . '";</script>';
    }
} else {
    echo '<script>window.location.href="../' . $backUrl . '";</script>';
}
?>

