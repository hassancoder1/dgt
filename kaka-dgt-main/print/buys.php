<?php $backUrl = '../';
if (isset($_GET['date_start']) && isset($_GET['date_end']) && isset($_GET['secret']) && (base64_decode($_GET['secret']) == "powered-by-upsol")) {
    require("../connection.php");
    include("../variables.php");
    $start_date = mysqli_real_escape_string($connect, $_GET['date_start']);
    $end_date = mysqli_real_escape_string($connect, $_GET['date_end']);
    $sql = "SELECT * FROM `buys` WHERE id > 0 ";
    $sql .= !empty($start_date) && !empty($end_date) ? " AND b_date BETWEEN '$start_date' AND '$end_date'" : "";
    $records = mysqli_query($connect, $sql);
    $title = 'خریداری جنرل فارم';
    $dates = empty($start_date) && empty($end_date) ? 'سارے ریکارڈز' : $start_date . ' سے ' . $end_date;
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
                        <h3 class="fw-bold mb-0 ">خریداری کی تفصیل</h3>
                    </div>
                    <div>
                        <div class="urdu"><span class="bold me-2">تاریخ</span><?php echo $dates; ?></div>
                        <div class="urdu"><span class="bold me-2">اندراج</span><?php echo mysqli_num_rows($records); ?></div>
                    </div>
                    <div class="text-end">
                        <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid"
                             style="width: 80px;">
                        <h6 class="font-size-11">BISMILLAH &amp; BROTHERS</h6></div>
                </div>
                <?php //include("inc-print-top-sm.php"); ?>
                <div class="card rounded-0 shadow-none border-0">
                    <div class="card-body pt-2 px-1 mb-0">
                        <table class="table table-bordered mt-3">
                            <thead>
                            <tr>
                                <th>بل#</th>
                                <th>یوزر</th>
                                <th>تاریخ</th>
                                <th>جنس</th>
                                <th>لاٹ نام</th>
                                <th>خرید شہر</th>
                                <th>بل نمبر</th>
                                <th>بنام اکاؤنٹ</th>
                                <th>جمع اکاؤنٹ</th>
                                <th>خریداری</th>
                                <th>فروشی</th>
                                <th>بیلنس</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($loading = mysqli_fetch_assoc($records)) {
                                $dr_khaata_json = json_decode($loading['dr_khaata_json']);
                                $jmaa_khaata_noo = empty($dr_khaata_json) ? '' : $dr_khaata_json->jmaa_khaata_no;
                                $rowColor = empty($dr_khaata_json) ? 'bg-danger bg-opacity-10' : '';
                                ?>
                                <tr class="<?php echo $rowColor; ?>">
                                    <td><?php echo $loading["id"]; ?></td>
                                    <td><?php echo $loading['username']; ?></td>
                                    <td><?php echo $loading['b_date']; ?></td>
                                    <td><?php echo $loading['jins']; ?></td>
                                    <td><?php echo $loading['allot_name']; ?></td>
                                    <td><?php echo $loading['loading_city']; ?></td>
                                    <td><?php echo $loading['bail_no']; ?></td>
                                    <td><?php echo $loading['bnaam_khaata_no']; ?></td>
                                    <td><?php echo $jmaa_khaata_noo; ?></td>
                                    <td><?php echo buyBalance($loading['id']); ?></td>
                                    <td><?php echo sellBalance($loading['id']); ?></td>
                                    <td><?php echo buySellBalance($loading['id']); ?></td>
                                </tr>
                            <?php } ?>
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
    <?php
} else {
    echo '<script>window.location.href="' . $backUrl . '";</script>';
} ?>

