<?php $backUrl = base64_decode($_GET['url']);
if (isset($_GET['type'])) {
    require("../connection.php");
    include("../variables.php");
    $typesAllowed = array(KARACHI, CHAMAN, BORDER, QANDHAR,BORDER_BILL);
    $getType = mysqli_real_escape_string($connect, $_GET['type']);
    if (in_array($getType, $typesAllowed)) {
        if (isset($_GET['bail_id']) && ($_GET['bail_id'] > 0) && isset($_GET['secret'])
            && isset($_GET['start_date']) && isset($_GET['end_date'])
        ) {
            if (base64_decode($_GET['secret']) == "powered-by-upsol") {
                $bail_id = mysqli_real_escape_string($connect, $_GET['bail_id']);
                //$branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
                $sql = "SELECT * FROM `ut_bail_entries` WHERE id = '$bail_id' ";
                /*if ($branch_id > 0) {
                    $sql .= " AND branch_id = " . "'$branch_id'" . " ";
                }*/
                $start_date = mysqli_real_escape_string($connect, $_GET['start_date']);
                $end_date = mysqli_real_escape_string($connect, $_GET['end_date']);
                if (empty($start_date)) {
                    $s_date = '2023-01-01';
                    $e_date = date('Y-m-d');
                } else {
                    $s_date = $start_date;
                    $e_date = $end_date;
                    $sql .= " AND loading_date BETWEEN " . "'$start_date'" . " AND " . "'$end_date'" . " ";
                }
                $datu = mysqli_query($connect, $sql);
                $data = mysqli_fetch_assoc($datu);
                $surrender_json = json_decode($data['surrender_json']);
                $sender_json = json_decode($data['sender_receiver']);
                $khaata_ = json_decode($data['khaata_' . $getType]);
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
                        <?php echo $getType; ?>_expense_bill_of_bail_<?php echo $data['bill_no']; ?>
                        _<?php echo date('Y_m_d-H_i_s'); ?>
                    </title>
                    <link rel="preconnect" href="https://fonts.googleapis.com/">
                    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
                    <link rel="stylesheet" href="../assets/css/style-rtl.min.css">
                    <link rel="stylesheet" href="../assets/css/custom.css">
                    <link rel="shortcut icon" href="../assets/images/anitco.png"/>
                    <link rel="stylesheet" href="../assets/vendors/font-awesome/css/font-awesome.min.css">
                    <link rel="stylesheet" href="../assets/tooltip/tooltip.min.css">
                    <style>
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
                            background: black;
                            color: white;
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
                            <div class="col-6">
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
                                        <div class="row text-center">
                                            <div class="col">
                                                <p class="urdu small">
                                                    <span class="bold">بل نمبر:  </span>
                                                    <span class="small"><?php echo $data['bill_no']; ?></span>

                                                    <span class="bold ms-3">لوڈنگ تاریخ:  </span>
                                                    <span class="small"> <?php echo $data['loading_date']; ?></span>
                                                    <span class="small ms-1"><?php echo '(' . $data['loading_city'] . ')'; ?></span>

                                                    <span class="bold ms-3">جنس:  </span>
                                                    <span class="small"><?php echo $data['jins']; ?></span>

                                                    <span class="bold ms-3">کنٹینر نمبر:  </span>
                                                    <span class=""><?php echo $surrender_json->sr_container_no; ?></span>
                                                    <span class="ltr">-<?php echo $surrender_json->sr_container_name; ?></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="row mt-2 text-center">
                                            <div class="col">
                                                <p class="urdu small">
                                                    <span class="bold">سلنڈر تاریخ:  </span>
                                                    <span class="small"><?php echo $surrender_json->sr_date; ?></span>

                                                    <span class="bold ms-3">سلنڈر بل نمبر:  </span>
                                                    <span class="small"><?php echo $surrender_json->sr_bill_no; ?></span>

                                                    <span class="bold ms-3">ٹوٹل وزن:  </span>
                                                    <span class="small"><?php echo round($data['total_wt']); ?></span>

                                                    <span class="bold ms-3">صاف وزن:  </span>
                                                    <span class="small"><?php echo round($data['saaf_wt']); ?></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-2 pt-0 mt-n2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered mt-3">
                                                <thead>
                                                <tr class="">
                                                    <th>خرچہ نام</th>
                                                    <th>تفصیل</th>
                                                    <th>رقم</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $exps = fetch('ut_expenses', array('bail_id' => $bail_id, 'expense_name' => $getType));
                                                $numRows = mysqli_num_rows($exps);
                                                if ($numRows > 0) {
                                                    $datum = mysqli_fetch_assoc($exps);
                                                    $json_data = json_decode($datum['json_data']);
                                                    foreach ($json_data->exp_names as $index => $val) {
                                                        echo '<tr>';
                                                        echo '<td>' . $val . '</td>';
                                                        echo '<td>' . $json_data->exp_details[$index] . '</td>';
                                                        echo '<td>' . $json_data->exp_values[$index] . '</td>';
                                                        //echo '<td class="text-danger"> ' . $datum['bnaam_amount'] . ' </td > ';
                                                        echo '</tr> ';
                                                    }
                                                } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mb-2 justify-content-center" style="">
                                        <div class="col">
                                            <p class="urdu">
                                                <span class="bold">جمع کھاتہ نمبر:  </span>
                                                <span class="small"><?php echo $khaata_->jmaa_khaata_no; ?></span>
                                                <span class="bold ms-1">بنام کھاتہ نمبر:  </span>
                                                <span class="small"><?php echo $khaata_->bnaam_khaata_no; ?></span>
                                                <span class="bold ms-1">ٹوٹل بل:  </span>
                                                <span class="small"><?php echo $khaata_->total_bill; ?></span>
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
                        <li class="bg-dark" data-tooltip="کھاتہ فارم میں واپس" data-tooltip-position="right">
                            <a href="../<?php echo $backUrl; ?>"><i
                                        class="fa fa-long-arrow-left"></i></a>
                        </li>
                        <li class="facebook"
                            title="PDF پرنٹ کریں">
                            <a class="cursor-pointer" onclick="window.print();">
                                <i class="fa fa-print"></i>
                            </a>
                        </li>
                        <!--<li class="excel" data-tooltip="Excel فائل ڈاؤن لوڈ کریں" data-tooltip-position="right">
                            <form action="../expor-excel/ledger-form.php" method="post">
                                <input type="hidden" name="secret"
                                       value="<?php /*echo base64_encode('powered-by-upsol'); */ ?>">
                                <input type="hidden" name="bail_id" id="bail_id_print" value="<?php /*echo $bail_id; */ ?>">
                                <input type="hidden" name="branch_id" value="<?php /*echo $branch_id; */ ?>">
                                <input type="hidden" name="start_date" id="start_date_print"
                                       value="<?php /*echo $start_date; */ ?>">
                                <input type="hidden" name="end_date" id="end_date_print"
                                       value="<?php /*echo $end_date; */ ?>">
                                <button type="submit"><i class="fa fa-file-excel-o"></i></button>
                            </form>
                        </li>
                        <li class="word">
                            <a class="twitter"><i class="fa fa-file-word-o"></i></a>
                        </li>-->
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
    } else {
        echo '<script>window.location.href="../' . $backUrl . '";</script>';
    }
} else {
    echo '<script>window.location.href="../' . $backUrl . '";</script>';
} ?>

