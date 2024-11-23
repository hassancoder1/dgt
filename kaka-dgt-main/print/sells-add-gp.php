<?php $backUrl = '../';
if (isset($_GET['buys_id']) && ($_GET['buys_id'] > 0) && isset($_GET['buys_sold_id']) && ($_GET['buys_sold_id'] > 0) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        include("../variables.php");
        $buys_id = mysqli_real_escape_string($connect, $_GET['buys_id']);
        $records = fetch('buys', array('id' => $buys_id));
        $record = mysqli_fetch_assoc($records);
        $buys_sold_id = mysqli_real_escape_string($connect, $_GET['buys_sold_id']);
        $buys_sold_q = fetch('buys_sold', array('id' => $buys_sold_id));
        $buys_sold = mysqli_fetch_assoc($buys_sold_q);
        $backUrl = '../sells-add?id=' . $buys_id . '&buys_sold_id=' . $buys_sold_id . '&action=gp-update';
        //$json_data = json_decode($buys_sold['json_data']);
        $bnn = fetch('khaata', array('id' => $buys_sold['seller_khaata_id']));
        $bnaam = mysqli_fetch_assoc($bnn);
        $jmm = fetch('khaata', array('id' => $record['bnaam_khaata_id']));
        $jmaa = mysqli_fetch_assoc($jmm);
        $seller_khaataQ= fetch('khaata', array('id' => $buys_sold['seller_khaata_id']));
        $seller_khaata = mysqli_fetch_assoc($seller_khaataQ);
        $brokersQ= fetch('brokers', array('id' => $buys_sold['broker_id']));
        $broker= mysqli_fetch_assoc($brokersQ); ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>خریداری فارم_<?php echo $record['id'] . '_' . $buys_sold['bill_no']; ?>
                __<?php echo date('Y_m_d-H_i_s'); ?>
            </title>
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
            <div class="row gx-0">
                <?php for ($i = 1; $i <= 2; $i++) { ?>
                    <div class="col-6">
                        <?php include ("inc-print-top-sm.php");?>
                        <div class="card rounded-0 shadow-none border-0">
                            <div class="card-body pt-2 px-1 mb-0">
                                <h3 class="urdu-2 text-center">گیٹ پاس</h3>
                                <div class="border container border-dark pb-4">
                                    <div class="row justify-content--center row-cols-4 gy-3 gx-0">
                                        <?php $buyArray = array(
                                            array('col_name' => 'تاریخ', 'col_val' => $record['b_date'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'شہر', 'col_val' => $record['loading_city'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'بل سیریل', 'col_val' => $record['id'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'بل نمبر', 'col_val' => $record['bail_no'], 'class' => 'col', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            //array('col_name' => 'بنام', 'col_val' => $record['bnaam_khaata_no'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            //array('col_name' => 'کھاتہ', 'col_val' => $bnaam['khaata_name'], 'class' => 'col-5', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            //array('col_name' => 'فون', 'col_val' => $bnaam['mobile'] . ' | ' . $bnaam['whatsapp'], 'class' => 'col-5', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),
                                            //array('col_name' => '', 'col_val' => $bnaam['whatsapp'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),
                                            array('col_name' => '', 'col_val' => '', 'class' => 'col-12 h-0 mb-0 border border-dark', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'بل نمبر', 'col_val' => $buys_sold['bill_no'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'تاریخ', 'col_val' => $buys_sold['s_date'], 'class' => 'col-4', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'لاٹ نام', 'col_val' => $buys_sold['allot_name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'جنس', 'col_val' => $buys_sold['jins'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'بروکر نام', 'col_val' => $buys_sold['broker_name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            //array('col_name' => 'موبائل', 'col_val' => $broker['mobile'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            //array('col_name' => 'پتہ', 'col_val' => $broker['address'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'لوڈنگ گودام', 'col_val' => $buys_sold['loading_godam'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),

                                            array('col_name' => 'بیچنے والا اکاؤنٹ', 'col_val' => $buys_sold['seller_khaata_no'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'بیچنے والا نام', 'col_val' => $seller_khaata['khaata_name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            //array('col_name' => 'کمپنی', 'col_val' => $seller_khaata['comp_name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'پتہ', 'col_val' => $seller_khaata['address'], 'class' => 'col-4', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'موبائل', 'col_val' => $seller_khaata['mobile'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),
                                            //array('col_name' => '', 'col_val' => '', 'class' => 'col-12 h-0 border border-dark', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                        );
                                        foreach ($buyArray as $arr) {
                                            echo '<div class="' . $arr['class'] . ' mb-0 mb-md-2"><p class="urdu small"><span class="bold">' .
                                                $arr['col_name'] . ' </span>';
                                            echo '<span style="" class="small ms-2 ' . $arr['span_class'] . '"  ' . $arr['span_attr'] . ' id="' . $arr['span_id'] . '">' .
                                                $arr['col_val'] . '</span></p></div>';
                                        } ?>
                                    </div>
                                    <table class="table table-bordered border-dark my-2">
                                        <thead>
                                        <tr class="text-dark text-nowrap">
                                            <th>جنس</th>
                                            <th>مارکہ</th>
                                            <th>باردانہ</th>
                                            <th>تعداد</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr style="">
                                            <td><?php echo $buys_sold['jins']; ?></td>
                                            <td><?php echo $buys_sold['marka']; ?></td>
                                            <td><?php echo $buys_sold['bardana_name']; ?></td>
                                            <td><?php echo $buys_sold['bardana_qty']; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="row justify-content-center  gx-0">
                                        <div class="col-12 text-center">
                                            <p class="urdu mb-3">دستخط لازمی ہے۔ بغیر دستخط بل قبول نہیں ہے۔</p>
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">مال بیچنے والا دستخط</label>
                                                <input class="form-control " value=""></div>
                                        </div>

                                        <div class="col">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">بروکر دستخط</label>
                                                <input class="form-control" value=""></div>
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">کمپنی مینیجر دستخط</label>
                                                <input class="form-control" value=""></div>
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
                <li class="bg-dark" data-tooltip="فروشی فارم میں واپس" data-tooltip-position="right">
                    <a href="<?php echo $backUrl; ?>"><i
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

