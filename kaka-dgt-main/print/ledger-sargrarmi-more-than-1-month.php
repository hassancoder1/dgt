<?php $backUrl = '../ledger-sargrarmi-more-than-1-month';
if (isset($_POST['cat_ids']) && isset($_POST['secret']) && isset($_POST['branch_id']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
    if (base64_decode($_POST['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id != 0 ";
        $start_date = mysqli_real_escape_string($connect, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($connect, $_POST['end_date']);
        if (empty($start_date)) {
            $s_date = '2023-01-01';
            $e_date = date('Y-m-d');
        } else {
            $s_date = $start_date;
            $e_date = $end_date;
            $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        }
        $in = $_POST['cat_ids'];
        if (!empty($in)) {
            $sql .= " AND cat_id IN " . $in;
        }
        $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id']);
        if ($branch_id > 0) {
            $sql .= " AND khaata_branch_id = " . "'$branch_id'" . " ";
        }
        $jmaaTotalPrint = mysqli_real_escape_string($connect, $_POST['jmaaTotalPrint']);
        $bnaamTotalPrint = mysqli_real_escape_string($connect, $_POST['bnaamTotalPrint']);
        $mezanPrint = mysqli_real_escape_string($connect, $_POST['mezanPrint']); ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>سرگرمی 1 ماہ سے زیادہ-<?php echo date('Y_m_d-H_i_s'); ?></title>
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link rel="stylesheet" href="../assets/css/style-rtl.min.css">
            <link rel="stylesheet" href="../assets/css/custom.css">
            <link rel="shortcut icon" href="../assets/images/anitco.png"/>
            <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
            <link rel="stylesheet" href="../assets/tooltip/tooltip.min.css">
            <style>
                body {
                    background: white;
                }

                input {
                    pointer-events: none;
                    font-weight: bold !important;
                    font-family: 'Noto Naskh Arabic', serif;
                }

                .table tbody tr td {
                    font-size: 7.5px;
                    color: inherit;
                }

                .table thead tr th {
                    font-size: 8px;
                    background: black;
                    color: white;
                }
            </style>
        </head>
        <body>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8 col-12">
                    <div class="p-1">
                        <?php include("inc-print-top.php"); ?>
                    </div>
                    <div class="card rounded-0 shadow-none border-0">
                        <div class="card-body py-0">
                            <div class="row justify-content-center text-center my-2">
                                <div class="col-3">
                                    <p class="urdu small"><span class="bold">کل جمع:  </span><span
                                                class="small ms-2"><?php echo $jmaaTotalPrint; ?></span></p>
                                </div>
                                <div class="col-3">
                                    <p class="urdu small"><span class="bold">کل بنام:  </span><span
                                                class="small ms-2"><?php echo $bnaamTotalPrint; ?></span></p>
                                </div>
                                <div class="col-3">
                                    <p class="urdu small">
                                        <span class="bold">میزان:  </span>
                                        <span class="small ms-2" dir="ltr">
                                            <?php echo $mezanPrint; ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tbody>
                                <tr class="bg-dark text-white">
                                    <td>تاریخ</td>
                                    <?php echo '<td class="">کیٹیگری</td>';
                                    echo '<td>برانچ</td>'; ?>
                                    <td>کھاتہ نمبر</td>
                                    <td>کھاتہ نام</td>
                                    <td>کمپنی نام</td>
                                    <td>کاروبار نام</td>
                                    <!--<td>پتہ</td>-->
                                    <td>موبائل</td>
                                    <?php echo '<td>ٹوٹل جمع</td>';
                                    echo '<td>ٹوٹل بنام</td>'; ?>
                                    <td>دن</td>
                                    <td>بیلنس</td>
                                </tr>
                                <?php $khaataQ = mysqli_query($connect, $sql);
                                $jmaaTotalLast = $bnaamTotalLast = $balance = $noOfDays = 0;
                                if (mysqli_num_rows($khaataQ) > 0) {
                                    while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                                        //$k_no = $khaata['khaata_no'];
                                        $k_id = $khaata['khaata_id'];
                                        $khaataData = mysqli_query($connect, "SELECT * FROM khaata WHERE id = '$k_id'");
                                        $khaataDatum = mysqli_fetch_assoc($khaataData);
                                        $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                                        $records = mysqli_query($connect, $innerSql);
                                        $jb = mysqli_fetch_assoc($records);

                                        //$datesSql = "SELECT * FROM `roznamchaas` WHERE `khaata_id` = '$k_id' AND r_date > date_sub(date_sub(now(), INTERVAL 1 MONTH),INTERVAL 0 DAY)";
                                        $datesSql = "SELECT MAX(r_date) as last_old_date FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                                        $datesQ = mysqli_query($connect, $datesSql);
                                        $dates = mysqli_fetch_assoc($datesQ);
                                        $last_old_date = $dates['last_old_date'];
                                        //echo $last_old_date;
                                        $origin = date_create($last_old_date);
                                        $target = date_create(date('Y-m-d'));
                                        $interval = date_diff($origin, $target);
                                        $noOfDays = $interval->format('%a');
                                        $balance = $jb['jmaa_amount'] - $jb['bnaam_amount'];
                                        $jmaaTotalLast += $jb['jmaa_amount'];
                                        $bnaamTotalLast += $jb['bnaam_amount'];
                                        if ($noOfDays > 29) {
                                            ?>
                                            <tr>
                                                <td><?php echo $last_old_date; ?></td>
                                                <td><?php echo getTableDataByIdAndColName('cats', $khaataDatum['cat_id'], 'c_name'); ?></td>
                                                <td><?php echo branchName($khaataDatum['branch_id']); ?></td>
                                                <td><?php echo $khaataDatum['khaata_no']; ?></td>
                                                <td><?php echo $khaataDatum['khaata_name']; ?></td>
                                                <td><?php echo $khaataDatum['comp_name']; ?></td>
                                                <td><?php echo $khaataDatum['business_name']; ?></td>
                                                <td class="ltr"><?php echo $khaataDatum['mobile']; ?></td>
                                                <td><?php echo $jb['jmaa_amount']; ?></td>
                                                <td class="text-danger"><?php echo $jb['bnaam_amount']; ?></td>
                                                <td class=""><?php echo $noOfDays;
                                                    echo '<span class="ms-1"> دن سے بنام </span>'; ?></td>
                                                <td class="<?php echo ($balance > 0) ? 'text-success' : 'text-danger'; ?> ltr">
                                                    <?php echo $balance; ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                } else {
                                    echo '<tr class="text-center"><th colspan="12"> کوئی ریکارڈ موجود نہیں ہے۔</th></tr>';
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="سرگرمی 1 ماہ سے زیادہ" data-tooltip-position="right">
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
        echo '<script>window.location.href="' . $backUrl . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backUrl . '";</script>';
} ?>

