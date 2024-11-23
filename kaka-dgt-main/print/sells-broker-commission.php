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
        $backUrl = '../sells-broker-commission-add?buys_id=' . $buys_id . '&buys_sold_id=' . $buys_sold_id;
        if (!$buys_sold['bc_total']) {
            message('danger', $backUrl, 'بروکر کمیشن کا یہ بل ابھی روزنامچہ میں ٹرانسفر نہیں ہوا ہے۔ پرنٹ سے پہلے ٹرانسفر کرنا ضروری ہے۔');
        }

        $seller_khaata_q = fetch('khaata', array('id' => $buys_sold['seller_khaata_id']));
        $seller = mysqli_fetch_assoc($seller_khaata_q);

        $jmm = fetch('khaata', array('id' => $buys_sold['bc_jmaa_khaata_id']));
        $jmaa = mysqli_fetch_assoc($jmm); ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>بروکر کمیشن فارم_<?php echo $buys_sold_id . '_' . $buys_sold['bill_no']; ?>
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
                        <?php include("inc-print-top-sm.php"); ?>
                        <div class="card rounded-0 shadow-none border-0">
                            <div class="card-body pt-2 px-1 mb-0">
                                <h3 class="urdu-2 text-center">بروکر کمشن فارم</h3>
                                <div class="border container border-dark pb-4">
                                    <div class="row justify-content--center row-cols-4 gy-3 gx-0">
                                        <?php $buyArray = array(
                                            array('col_name' => 'بل نمبر', 'col_val' => $buys_sold['bill_no'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'تاریخ', 'col_val' => $buys_sold['s_date'], 'class' => 'col-4', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'لاٹ نام', 'col_val' => $buys_sold['allot_name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'جنس', 'col_val' => $buys_sold['jins'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'جمع اکاؤنٹ', 'col_val' => $buys_sold['bc_jmaa_khaata_no'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'کھاتہ', 'col_val' => $jmaa['khaata_name'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'کمپنی', 'col_val' => $jmaa['comp_name'], 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'پتہ', 'col_val' => $jmaa['address'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                            array('col_name' => 'موبائل', 'col_val' => $jmaa['mobile'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => 'dir="ltr"'),
                                            //array('col_name' => 'بروکر نام', 'col_val' => $buys_sold['broker_name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                                        );
                                        foreach ($buyArray as $arr) {
                                            echo '<div class="' . $arr['class'] . ' mb-0 mb-md-2"><p class="urdu small"><span class="bold">' .
                                                $arr['col_name'] . ' </span>';
                                            echo '<span style="" class="small ms-2 ' . $arr['span_class'] . '"  ' . $arr['span_attr'] . ' id="' . $arr['span_id'] . '">' .
                                                $arr['col_val'] . '</span></p></div>';
                                        } ?>
                                    </div>
                                    <table class="table table-bordered border-dark">
                                        <thead>
                                        <tr class="text-dark text-nowrap">
                                            <th>رپورٹ</th>
                                            <th>فیصد</th>
                                            <th>کمیشن</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr style="line-height: 3;">
                                            <td>
                                                <?php echo $seller['khaata_name']; ?>
                                                <?php echo 'باردانہ تعداد: ' . $buys_sold['bardana_qty']; ?>
                                                <?php echo 'ٹوٹل وزن: ' . $buys_sold['total_wt']; ?>
                                                <?php echo 'لوڈنگ گودام : ' . $buys_sold['loading_godam']; ?>

                                            </td>
                                            <td><?php echo $buys_sold['bc_percent']; ?></td>
                                            <td><?php echo $buys_sold['bc_total']; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="row gy-3">
                                        <div class="col-8 mb-0 mb-md-2">
                                            <p class="urdu small ">
                                                <span class="bold">ٹوٹل: </span>
                                                <span class="small ms-1"><?php echo AmountInWordsUrdu($buys_sold['bc_total']); ?></span>
                                            </p>
                                        </div>
                                        <div class="col-4 mb-0 mb-md-2">
                                            <p class="urdu float-end">
                                                <span class="bold">ٹوٹل: </span>
                                                <span class="small ms-1"><?php echo $buys_sold['bc_total']; ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row  mt-3 py-2 border-top border-bottom  border-dark border-2">
                                        <div class="col">
                                            <p class="urdu">
                                                <span class="bold">مزید تفصیل: </span>
                                                <span class="small ms-1"><?php echo $buys_sold['bc_report']; ?></span>
                                            </p>
                                        </div>
                                        <!--<div class="col-3 text-end">
                                            <p class="urdu text-nowrap">
                                                <span class="bold">تاریخ ادائیگی: </span>
                                                <span class="ms-1"><?php /*echo $buys_sold['bc_date']; */?></span>
                                            </p>
                                        </div>-->
                                    </div>
                                    <div class="row justify-content-center  gx-0">
                                        <div class="col-12 text-center">
                                            <p class="urdu mb-3">دستخط لازمی ہے۔ بغیر دستخط بل قبول نہیں ہے۔</p>
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">بروکر دستخط</label>
                                                <input class="form-control" value=""></div>
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <label class="input-group-text urdu">منشی دستخط</label>
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
                <li class="bg-dark" data-tooltip="بروکر کمیشن میں واپس" data-tooltip-position="right">
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

