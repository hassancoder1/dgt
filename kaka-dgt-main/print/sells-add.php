<?php $backUrl = '../';
if (isset($_GET['buys_id']) && ($_GET['buys_id'] > 0) && isset($_GET['secret'])) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        include("../variables.php");
        $buys_id = mysqli_real_escape_string($connect, $_GET['buys_id']);
        $records = fetch('buys', array('id' => $buys_id));
        $record = mysqli_fetch_assoc($records);
        $backUrl = '../sells-add?id=' . $buys_id;
        $copies = array(
            array(
                'name' => 'خریدار کاپی',
                'bg_color' => 'bg-light',
            ),
            array(
                'name' => 'کمپنی کاپی',
                'bg_color' => 'bg-light',
            )
        ); ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>فروشی فارم_<?php echo $record['id'] . '_' . $record['bail_no'] . '_' . date('Y_m_d-H_i_s'); ?>
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
        <?php foreach ($copies as $copy) { ?>
            <div class="container-fluid" style="page-break-before: always;">
                <div class="row justify-content-center fixed-top-bg-white ">
                    <div class="col-lg-7 col-12">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="urdu">
                                <h1 class="fw-bold mb-0 ">فروشی رسید</h1>
                            </div>
                            <div class="urdu">
                                <?php echo '<span class="' . $copy['bg_color'] . ' px-4">' . $copy['name'] . '</span>'; ?>
                            </div>
                            <div class="text-end">
                                <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid"
                                     style="width: 80px;">
                                <?php echo '<h6 class="font-size-11">BISMILLAH & BROTHERS</h6>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center g-0">
                    <div class="col-lg-7 col-12 urdu">
                        <hr>
                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <div>
                                <?php echo ' <h4 class="mb-2">بنام اکاؤنٹ</h4>';
                                if (!empty($record['cr_khaata_json'])) {
                                    $cr_khaata_json = json_decode($record['cr_khaata_json']);
                                    $bnaam_query = fetch('khaata', array('id' => $cr_khaata_json->bnaam_khaata_id));
                                    $bnaam = mysqli_fetch_assoc($bnaam_query);
                                    echo '<div class="mb-2"><b>اکاؤنٹ: </b> ' . strtoupper($cr_khaata_json->bnaam_khaata_no) . '</div>';
                                    echo '<div class="mb-2"><b>ںام: </b> ' . $bnaam['khaata_name'] . '</div>';
                                    echo '<div><b>کمپنی: </b> ' . $bnaam['comp_name'] . '</div>';
                                } ?>
                            </div>
                            <div>
                                <?php echo '<div class="mb-2"><b>بل نمبر </b> ' . $record['bail_no'] . '</div>';
                                echo '<div class="mb-1"><b>جنس </b> ' . $record['jins'] . '</div>';
                                //echo '<div class="mb-1"><b>لاٹ نام </b> ' . $record['allot_name'] . '</div>';
                                ?>
                            </div>
                            <div>
                                <?php echo ' <h4 class="mb-2">جمع اکاؤنٹ</h4>';
                                if (!empty($record['cr_khaata_json'])) {
                                    $dr_khaata_json = json_decode($record['cr_khaata_json']);
                                    $jmaa_query = fetch('khaata', array('id' => $dr_khaata_json->jmaa_khaata_id));
                                    $jmaa = mysqli_fetch_assoc($jmaa_query);
                                    echo '<div class="mb-2"><b>اکاؤنٹ: </b> ' . strtoupper($dr_khaata_json->jmaa_khaata_no) . '</div>';
                                    echo '<div class="mb-2"><b>ںام: </b> ' . $jmaa['khaata_name'] . '</div>';
                                    echo '<div><b>کمپنی: </b> ' . $jmaa['comp_name'] . '</div>';
                                } ?>
                            </div>
                        </div>
                        <table class="table table-sm table-bordered">
                            <thead>
                            <tr>
                                <td>#</td>
                                <td>جنس</td>
                                <td>لاٹ نام</td>
                                <td>باردانہ نام</td>
                                <td>مارکہ</td>
                                <td>باردانہ تعداد</td>
                                <td>فی وزن</td>
                                <td>ٹوٹل وزن</td>
                                <td>صاف وزن</td>
                                <td>رقم</td>
                                <!--<td>تفصیل</td>-->
                            </tr>
                            </thead>
                            <tbody>
                            <?php $qeemat_raqam_total = $no = 0;
                            $sales = mysqli_query($connect, "SELECT * FROM `buys_sold` WHERE buys_id = $buys_id ORDER BY qeemat_raqam ASC ");
                            while ($sale = mysqli_fetch_assoc($sales)) {
                                ++$no; ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $record['jins']; ?></td>
                                    <td><?php echo $sale['allot_name']; ?></td>
                                    <td><?php echo $sale['bardana_name']; ?></td>
                                    <td><?php echo $sale['marka']; ?></td>
                                    <td><?php echo $sale['bardana_qty']; ?></td>
                                    <td><?php echo round($sale['per_wt']); ?></td>
                                    <td><?php echo round($sale['total_wt']); ?></td>
                                    <td><?php echo round($sale['saaf_wt']); ?></td>
                                    <td><?php echo round($sale['qeemat_raqam']); ?></td>
                                    <!--<td><?php /*echo $sale['more_details']; */ ?></td>-->
                                </tr>
                                <?php $qeemat_raqam_total += $sale['qeemat_raqam'];
                            } ?>
                            <tr class="bold">
                                <td colspan="8"></td>
                                <td>ٹوٹل</td>
                                <td><?php echo $qeemat_raqam_total; ?></td>
                            </tr>
                            <?php if (!empty($record['cr_khaata_json'])) {
                                $jj = json_decode($record['cr_khaata_json']);
                                //var_dump($jj);
                                $ccc = $jj->currency2 . $jj->rate2;
                                $tt_amount = $jj->final_amount;
                            }
                            $roz_info = [];
                            if ($cr_khaata_json != '') {
                                $rozQ = fetch('roznamchaas', array('r_type' => 'karobar', 'transfered_from_id' => $buys_id, 'transfered_from' => 'buys_sold'));
                                if (mysqli_num_rows($rozQ) > 0) {
                                    while ($roz = mysqli_fetch_assoc($rozQ)) {
                                        $roz_info['date'] = $roz['r_date'];
                                        if ($roz['jmaa_amount'] == 0) {
                                            $roz_info['cr'] = $roz['branch_serial'] . '-' . $roz['r_id'];
                                        }
                                        if ($roz['bnaam_amount'] == 0) {
                                            $roz_info['dr'] = $roz['branch_serial'] . '-' . $roz['r_id'];
                                        }
                                    }
                                }
                            } ?>
                            <tr class="bold">
                                <td colspan="8"></td>
                                <td>کرنسی ریٹ</td>
                                <td><?php echo $ccc; ?></td>
                            </tr>
                            <tr class="bold">
                                <td colspan="8">
                                    <?php if (!empty($roz_info)) {
                                        echo '<div class="d-flex justify-content-between">';
                                        echo '<div>' . 'جمع روزنامچہ نمبر' . $roz_info['dr'] . ' </div>';
                                        echo '<div>' . 'بنام روزنامچہ نمبر' . $roz_info['cr'] . ' </div>';
                                        echo '<div>' . 'روزنامچہ تاریخ' . $roz_info['date'] . ' </div>';
                                        echo '</div>';
                                    } ?>
                                </td>
                                <td>ٹرانسفر اماؤنٹ</td>
                                <td><?php echo round($tt_amount); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="fixed-bottom bg-white">
                <div class="container-fluid">
                    <div class="row justify-content-center g-0 urdu">
                        <div class="col-lg-7 col-12">
                            <?php //echo $buys_sold['more_details'] != '' ? '<b> تٖفصیل: </b>' . $buys_sold['more_details'] : ''; ?>
                            <div class="row mt-5 urdu">
                                <div class="col"><p><b>خریدار دستخط</b></p></div>
                                <div class="col"><p><b>کمپنی دستخط</b></p></div>
                            </div>
                            <div class="row mb-4 mt-4">
                                <div class="col">
                                    <div class="border-top border-2 border-dark"></div>
                                </div>
                                <div class="col">
                                    <div class="border-top border-2 border-dark"></div>
                                </div>
                            </div>
                            <img src="pdf-footer.jpg" class="img-fluid" alt="bottom pic">
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
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

