<?php if (isset($_POST['khaata_id']) && ($_POST['khaata_id'] > 0) && isset($_POST['secret'])
    && isset($_POST['branch_id']) && isset($_POST['start_date']) && isset($_POST['end_date'])
) {
    if (base64_decode($_POST['secret']) == "powered-by-upsol") {
        require("../connection.php");
        $khaata_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
        $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id']);
        $kh = fetch('khaata', array('id' => $khaata_id));
        $khaata = mysqli_fetch_assoc($kh);
        $sql = "SELECT * FROM `roznamchaas` WHERE khaata_id = '$khaata_id' ";
        if ($branch_id > 0) {
            $sql .= " AND branch_id = " . "'$branch_id'" . " ";
        }
        $start_date = mysqli_real_escape_string($connect, $_POST['start_date']);
        $end_date = mysqli_real_escape_string($connect, $_POST['end_date']);
        if (empty($start_date)) {
            $s_date = '2023-01-01';
            $e_date = date('Y-m-d');
        } else {
            $s_date = $start_date;
            $e_date = $end_date;
            $sql .= " AND r_date BETWEEN " . "'$start_date'" . " AND " . "'$end_date'" . " ";
        }
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
            <title>
                <?php echo $khaata['khaata_name']; ?>_<?php echo $khaata['khaata_no']; ?>-
                <?php echo date('Y_m_d-H_i_s'); ?>
            </title>
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
                    font-size: 11px;
                }

                .table thead tr th {
                    font-size: 11px;
                    /*                    background: black;
                                        color: white;*/
                }
            </style>
        </head>
        <body>
        <div class="row gx-0 justify-content-center">
            <div class="col-md-8 col-12">
                <div class="p-1 border-bottom border-dark">
                    <?php include("inc-print-top.php"); ?>
                </div>
                <div class="card rounded-0 shadow-none border-0">
                    <div class="card-body pb-0">
                        <div class="row gx-0 justify--content-center">
                            <div class="col-3">
                                <p class="urdu small">
                                    <span class="bold">برانچ نام:  </span>
                                    <span class="small">
                                            <?php echo getTableDataByIdAndColName('branches', $khaata['branch_id'], 'b_name'); ?>
                                        </span>
                                </p>
                            </div>
                            <div class="col-2">
                                <p class="urdu small">
                                    <span class="bold">کیٹیگری:  </span>
                                    <span class="small">
                                            <?php echo getTableDataByIdAndColName('cats', $khaata['cat_id'], 'c_name'); ?>
                                        </span>
                                </p>
                            </div>
                            <div class="col-2">
                                <p class="urdu small">
                                    <span class="bold">کھاتہ نمبر:  </span>
                                    <span class="small">
                                            <?php echo $khaata['khaata_no']; ?>
                                        </span>
                                </p>
                            </div>
                            <div class="col-5">
                                <p class="urdu small">
                                    <span class="bold">کھاتہ نام:  </span>
                                    <span class="small">
                                            <?php echo $khaata['khaata_name']; ?>
                                        </span>
                                </p>
                            </div>
                        </div>
                        <div class="row gx-0 my-3  justify-content-center">
                            <div class="col">
                                <p class="urdu small">
                                    <span class="bold">کاروبار پتہ:  </span>
                                    <span class="small">
                                            <?php echo $khaata['address']; ?>
                                        </span>
                                </p>
                            </div>
                            <div class="col-3">
                                <p class="urdu small">
                                    <span class="bold">موبائل:  </span>
                                    <span class="small" dir="ltr">
                                            <?php echo $khaata['mobile']; ?>
                                        </span>
                                </p>
                            </div>
                            <div class="col-3">
                                <p class="urdu small">
                                    <span class="bold">فون:  </span>
                                    <span class="small" dir="ltr">
                                            <?php echo $khaata['phone']; ?>
                                        </span>
                                </p>
                            </div>
                        </div>
                        <div class="row gx-0">
                            <div class="col-4">
                                <p class="urdu small">
                                    <span class="bold">تاریخ:  </span>
                                    <span class="small" dir="ltr">
                                            <?php echo $s_date; ?>
                                        </span>
                                    <span class="mx-1">سے</span>
                                    <span class="small" dir="ltr">
                                            <?php echo $e_date; ?>
                                        </span>
                                </p>
                            </div>
                            <div class="col">
                                <p class="urdu small">
                                    <span class="bold">گزشتہ بیلنس:  </span>
                                    <span class="small" dir="ltr">****</span>
                                </p>
                            </div>
                            <div class="col">
                                <p class="urdu small">
                                    <span class="bold">موجودہ بنام بیلنس:  </span>
                                    <span class="small" id="totalBalanceBnaamSpan" dir="ltr">AAA</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0 pt-0">
                        <table class="table table-bordered table-sm mt-3">
                            <thead>
                            <tr class="">
                                <th>برانچ</th>
                                <th>تاریخ</th>
                                <th>سیریل</th>
                                <th>نام</th>
                                <th>نمبر</th>
                                <th>تفصیل</th>
                                <th>جمع</th>
                                <th>بنام</th>
                                <th>رقم</th>
                                <th>ٹوٹل</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $numRows = $totalJmaa = $totalBnaam = $totalBalanceBnaam = 0;
                            $data = mysqli_query($connect, $sql);
                            $numRows = mysqli_num_rows($data);
                            if ($numRows > 0) {
                                $jmaa = $bnaam = $balance = 0;
                                while ($datum = mysqli_fetch_assoc($data)) {
                                    echo '<tr class="text-nowrap_">';
                                    echo '<td class="text-nowrap small-4">' . getTableDataByIdAndColName('branches', $datum['branch_id'], 'b_name') . '</td>';
                                    echo '<td class="text-nowrap">' . $datum["r_date"] . '</td>';
                                    echo '<td>' . $datum['r_id'] . '</td>';
                                    echo '<td>' . $datum['r_name'] . '</td>';
                                    echo '<td>' . $datum['r_no'] . '</td>';
                                    $jmaaBnaamString = "";
                                    $jmaa += $datum['jmaa_amount'];
                                    $bnaam += $datum['bnaam_amount'];
                                    $balance = $jmaa - $bnaam;
                                    /*if ($datum['jmaa_amount'] == 0) {
                                        $jmaaBnaamString = "بنام";
                                    }
                                    if ($datum['bnaam_amount'] == 0) {
                                        $jmaaBnaamString = "جمع";
                                    }*/
                                    if ($balance > 0) {
                                        $jmaaBnaamString = "جمع";
                                    } else {
                                        $jmaaBnaamString = "بنام";
                                    }
                                    $bank_str = $date_str = "";
                                    if ($datum['r_type'] == "bank") {
                                        $bank_str = ' <span class="">بینک: ' . getTableDataByIdAndColName('banks', $datum['bank_id'], 'bank_name') . '</span> ';
                                        $date_str = ' <span class="">تاریخ ادائیگی: ' . $datum['r_date_payment'] . '</span> ';
                                    }
                                    echo '<td>' . $jmaaBnaamString . ':- ' . $datum["details"] . $bank_str . ' </td > ';
                                    echo '<td> ' . $datum['jmaa_amount'] . ' </td > ';
                                    echo '<td> ' . $datum['bnaam_amount'] . ' </td > ';
                                    echo '<td> ' . $jmaaBnaamString . '</td > ';
                                    $balanceClass = $balance >= 0 ? 'text-success' : 'text-danger';
                                    echo '<td style="font-size: 13px;" dir="ltr" class="ltr bold ' . $balanceClass . '" > ' . $balance . '</td > ';
                                    echo '</tr> ';
                                    $totalJmaa += $datum['jmaa_amount'];
                                    $totalBnaam += $datum['bnaam_amount'];
                                }
                                $totalBalanceBnaam = $totalJmaa - $totalBnaam;
                                echo '<tr>';
                                echo '<td colspan="6"></td>';
                                echo '<td class="bold">' . $totalJmaa . '</td>';
                                echo '<td class="bold">' . $totalBnaam . '</td>';
                                echo '<td></td>';
                                echo '<td class="bold" dir="ltr">' . $totalBalanceBnaam . '</td>';
                                echo '</tr>';
                                echo '<input type="hidden" value="' . $totalBalanceBnaam . '" id="totalBalanceBnaam">';
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            //var totalBalanceBnaam = $("#totalBalanceBnaam").val();
            let totalBalanceBnaam = document.getElementById("totalBalanceBnaam").value;
            //alert(totalBalanceBnaam);
            document.getElementById("totalBalanceBnaamSpan").innerText = totalBalanceBnaam;
        </script>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="کھاتہ فارم میں واپس" data-tooltip-position="right">
                    <a href="../ledger-form?back-khaata-no=<?php echo $khaata['khaata_no']; ?>"><i
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
                        <button type="submit"><i class="fa fa-file-excel-o"></i></button>
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
        echo '<script>window.location.href="../ledger-form";</script>';
    }
} else {
    echo '<script>window.location.href="../ledger-form";</script>';
} ?>

