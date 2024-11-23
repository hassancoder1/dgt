<?php $backUrl = '../ledger-all-categories';
//$is_small = false;
if (isset($_POST['cat_ids']) && isset($_POST['jbval']) && isset($_POST['secret'])
    && isset($_POST['branch_id']) && isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['printSizeRadio'])
) {
    if (base64_decode($_POST['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id != 0 ";
        $printSizeRadio = mysqli_real_escape_string($connect, $_POST['printSizeRadio']);
        $is_large = $printSizeRadio == "lg" ? true : false;
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
        $jmaaBnaamArrayVals = array("jmaa" => "جمع", "bnaam" => "بنام");
        $isJB = false;
        $jbval = 0;
        if (!empty($_POST['jbval'])) {
            $isJB = true;
            $jbval = mysqli_real_escape_string($connect, $_POST['jbval']);
        }
        $jmaaTotalPrint = mysqli_real_escape_string($connect, $_POST['jmaaTotalPrint']);
        $bnaamTotalPrint = mysqli_real_escape_string($connect, $_POST['bnaamTotalPrint']);
        $mezanPrint = mysqli_real_escape_string($connect, $_POST['mezanPrint']);
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
            <title>آل کیٹیگری کھاتہ-<?php echo date('Y_m_d-H_i_s'); ?></title>
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
                                <?php if ($isJB) {
                                    if ($jbval == "jmaa") {
                                        echo '<div class="col-3"><p class="urdu small"><span class="bold">کل جمع:  </span><span class="small ms-2">' . $jmaaTotalPrint . '</span></p></div>';
                                    }
                                    if ($jbval == "bnaam") {
                                        echo '<div class="col-3"><p class="urdu small"><span class="bold">کل بنام:  </span><span class="small ms-2">' . $bnaamTotalPrint . '</span></p></div>';
                                    }
                                } else { ?>
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
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <tbody>
                                <tr class="bg-dark text-white">
                                    <td></td>
                                    <?php if ($is_large) {
                                        echo '<td class="">کیٹیگری</td>';
                                        //echo '<th>برانچ</th>';
                                    } ?>
                                    <td>کھاتہ نمبر</td>
                                    <td>کھاتہ نام</td>
                                    <td>کمپنی نام</td>
                                    <!--<th>کاروبار نام</th>-->
                                    <td>پتہ</td>
                                    <td>موبائل نمبر</td>
                                    <td>واٹس ایپ نمبر</td>
                                    <td>فون</td>
                                    <?php if (!$isJB) {
                                        if ($is_large) {
                                            echo '<td>ٹوٹل جمع</td>';
                                            echo '<td>ٹوٹل بنام</td>';
                                        }
                                    } ?>
                                    <td>بیلنس</td>
                                </tr>
                                <?php $khaataQ = mysqli_query($connect, $sql);
                                $jmaaTotalLast = $bnaamTotalLast = $balance = 0;
                                $number = 1;
                                $numRows = mysqli_num_rows($khaataQ);
                                if ($numRows > 0) {
                                    while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                                        $k_id = $khaata['khaata_id'];
                                        $khaataData = mysqli_query($connect, "SELECT * FROM khaata WHERE id = '$k_id'");
                                        $khaataDatum = mysqli_fetch_assoc($khaataData);
                                        $k_no = $khaataDatum['khaata_no'];
                                        $innerSql = "SELECT SUM(jmaa_amount) as jmaa_amount ,SUM(bnaam_amount) as bnaam_amount 
                        FROM `roznamchaas` WHERE `khaata_id` = '$k_id'";
                                        $records = mysqli_query($connect, $innerSql);
                                        $jb = mysqli_fetch_assoc($records);
                                        $balance = $jb['jmaa_amount'] - $jb['bnaam_amount'];
                                        if ($balance == 0) {
                                            continue;
                                        }
                                        if ($isJB) {
                                            if ($jbval == "jmaa") {
                                                if ($balance < 0) {
                                                    continue;
                                                }
                                            } else {
                                                if ($balance > 0) {
                                                    continue;
                                                }
                                            }
                                        } ?>
                                        <tr class="text-nowrap-">
                                            <td><?php echo $number; ?></td>
                                            <?php $cat_name = getTableDataByIdAndColName('cats', $khaataDatum['cat_id'], 'c_name');
                                            $branch_name = getTableDataByIdAndColName('branches', $khaataDatum['branch_id'], 'b_name');
                                            if ($is_large) {
                                                echo '<td>' . $cat_name . '</td>';
                                                //echo '<td>' . $branch_name . '</td>';
                                            } ?>
                                            <td><?php echo $k_no; ?></td>
                                            <td class="text-nowrap-"><?php echo $khaataDatum['khaata_name']; ?></td>
                                            <td><?php echo $khaataDatum['comp_name']; ?></td>
                                            <td><?php echo $khaataDatum['business_name']; ?></td>
                                            <!--<td><?php /*echo $khaataDatum['address']; */ ?></td>-->
                                            <td class="ltr"><?php echo $khaataDatum['mobile']; ?></td>
                                            <td class="ltr"><?php echo $khaataDatum['whatsapp']; ?></td>
                                            <?php echo '<td class="ltr">' . $khaataDatum['phone'] . '</td>'; ?>
                                            <?php if (!$isJB) {
                                                if ($is_large) {
                                                    echo '<td>' . $jb['jmaa_amount'] . '</td>';
                                                    echo '<td class="text-danger">' . $jb['bnaam_amount'] . '</td>';
                                                }
                                            } ?>
                                            <?php $redGreenText = $balance > 0 ? 'text-success' : 'text-danger';
                                            echo '<td class="ltr ' . $redGreenText . '">' . $balance . '</td>'; ?>
                                        </tr>
                                        <?php $jmaaTotalLast += $jb['jmaa_amount'];
                                        $bnaamTotalLast += $jb['bnaam_amount'];
                                        $number++;
                                    }
                                    echo '<tr>';
                                    if ($is_large) {
                                        echo '<td colspan="9"></td>';
                                    }else{
                                        echo '<td colspan="8"></td>';
                                    }
                                    if (!$isJB) {
                                        if ($is_large) {
                                            echo '<td class="bold">' . $jmaaTotalLast . '</td>';
                                            echo '<td class="bold">' . $bnaamTotalLast . '</td>';
                                        }
                                    }
                                    echo '<td class="ltr bold">' . $jmaaTotalLast - $bnaamTotalLast . '</td>';
                                    echo '</tr>';
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
                <li class="bg-dark" data-tooltip="آل کیٹیگری کھاتہ" data-tooltip-position="right">
                    <a href="<?php echo $backUrl; ?>"><i
                                class="fa fa-long-arrow-left"></i></a>
                </li>
                <li class="facebook"
                    title="PDF پرنٹ کریں">
                    <a class="cursor-pointer" onclick="window.print();">
                        <i class="fa fa-print"></i>
                    </a>
                </li>
                <li class="excel" data-tooltip="Excel فائل ڈاؤن لوڈ کریں" data-tooltip-position="right">
                    <form action="../expor-excel/ledger-form.php" method="post">
                        <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                        <input type="hidden" name="khaata_id" id="khaata_id_print" value="<?php echo $khaata_id; ?>">
                        <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
                        <input type="hidden" name="start_date" id="start_date_print"
                               value="<?php echo $start_date; ?>">
                        <input type="hidden" name="end_date" id="end_date_print" value="<?php echo $end_date; ?>">
                        <button type="submit" disabled=""><i class="fa fa-file-excel-o"></i></button>
                    </form>
                </li>
                <li class="word">
                    <a class="twitter"><i class="fa fa-file-word-o"></i></a>
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

