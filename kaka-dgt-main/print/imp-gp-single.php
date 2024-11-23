<?php $backUrl = '../';
if (
    isset($_GET['imp_tl_id']) && ($_GET['imp_tl_id'] > 0) &&
    isset($_GET['maal_id']) && ($_GET['maal_id'] > 0) &&
    isset($_GET['gp_id']) && ($_GET['gp_id'] > 0) &&
    isset($_GET['secret'])
) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        include("../variables.php");
        $imp_tl_id = mysqli_real_escape_string($connect, $_GET['imp_tl_id']);
        $maal_id = mysqli_real_escape_string($connect, $_GET['maal_id']);
        $gp_id = mysqli_real_escape_string($connect, $_GET['gp_id']);
        $records = fetch('imp_truck_loadings', array('id' => $imp_tl_id));
        $loadingData = mysqli_fetch_assoc($records);
        $sender_receiver = json_decode($loadingData['sender_receiver']);

        $godam_loadingD = fetch('godam_loading_forms', array('id' => $loadingData['godam_loading_id']));
        $godam_loading = mysqli_fetch_assoc($godam_loadingD);
        $godam_emptyD = fetch('godam_empty_forms', array('id' => $loadingData['godam_empty_id']));
        $godam_empty = mysqli_fetch_assoc($godam_emptyD);


        $maalQ = fetch('imp_truck_maals', array('id' => $maal_id));
        $maalData = mysqli_fetch_assoc($maalQ);
        $json_maal = json_decode($maalData['json_data']);

        $gpData = fetch('imp_truck_gp', array('imp_tl_id' => $imp_tl_id, 'maal_id' => $maal_id, 'id' => $gp_id));
        $gpDatum = mysqli_fetch_assoc($gpData);
        $json_gp = json_decode($gpDatum['json_gp']);

        $backUrl = '../imp-gate-pass-entry-add?id=25' . $imp_tl_id . '&type=gatepass-entry'; ?>
        <!DOCTYPE html>
        <html lang="ar" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title><?php echo 'گیٹ پاس' . '-' . date('Y_m_d-H_i_s'); ?>
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
                    background-color: white;
                }

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
        <div class="container-fluid">
            <div class="row">
                <?php for ($i = 1; $i <= 2; $i++) { ?>
                    <div class="col-6">
                        <div class="d-flex urdu justify-content-between">
                            <div>
                                <img src="../assets/images/print-logo.png" alt="logo" class="img-fluid"
                                     style="width: 80px;">
                                <h4 class="mb-3 mt-2">عصمت اللہ نجیب اللہ انٹرنیشنل ٹریڈنگ کمپنی</h4>
                                <p style="font-size: ;" class="bold urdu-2"> سناتن بازار ہدایت پلازہ
                                    فلور
                                    نمبر 1 چمن
                                    <span>(نوید پلازہ سیکنڈ فلور آفس نمبر 7 نصفی روڑ کوئٹہ )</span>
                                </p>
                            </div>
                            <div>
                                <h3 class="urdu text-center my-5">گیٹ پاس</h3>
                            </div>
                        </div>
                        <div class="border border-dark mt-2"></div>
                        <!--<div class="row justify-content--center row-cols-4 gy-3 gx-0">-->
                        <?php $buyArray = array(
                            //array('col_name' => 'گیٹ پاس سیریل', 'col_val' => $imp_tl_id, 'class' => 'col-2', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'تاریخ', 'col_val' => $json_gp->gp_date, 'class' => 'col-4', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'مال بھیجنے والا نام', 'col_val' => $sender_receiver->sender_name, 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'مال وصول والا نام', 'col_val' => $sender_receiver->receiver_name, 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'لوڈنگ گودام', 'col_val' => $godam_loading['name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'منشی نام', 'col_val' => $godam_loading['munshi'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'موبائل نمبر', 'col_val' => $godam_loading['mobile1'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'خالی کرنے گودام', 'col_val' => $godam_empty['name'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'منشی نام', 'col_val' => $godam_empty['munshi'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'موبائل نمبر', 'col_val' => $godam_empty['mobile1'], 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),

                            //array('col_name' => 'جنس', 'col_val' => $json_maal->jins_name, 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'گیٹ پاس لینے باردانہ نام', 'col_val' => $json_gp->bardana_name_gp, 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'گیٹ پاس لینے باردانہ تعداد', 'col_val' => $json_gp->bardana_qty_gp, 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //array('col_name' => 'گیٹ پاس لینے والا نام', 'col_val' => $json_gp->gp_giver, 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                            //  array('col_name' => 'گیٹ پاس لینے والا موبائل', 'col_val' => $json_gp->gp_giver_phone, 'class' => 'col-3', 'span_id' => '', 'span_class' => '', 'span_attr' => ''),
                        );
                        /*foreach ($buyArray as $arr) {
                            echo '<div class="' . $arr['class'] . ' mb-0 mb-md-2"><p class="urdu small"><span class="bold">' .
                                $arr['col_name'] . ' </span>';
                            echo '<span style="" class="small ms-2 ' . $arr['span_class'] . '"  ' . $arr['span_attr'] . ' id="' . $arr['span_id'] . '">' .
                                $arr['col_val'] . '</span></p></div>';
                        }*/ ?>
                        <!--</div>-->
                        <table class="table table-borderless table-bordered-border-dark">
                            <tbody>
                            <!--<tr>
                                    <td>
                                        <span class="me-2">مین سیریل</span>
                                        <span class="bold h5"><?php /*echo $imp_tl_id; */ ?></span>
                                    </td>
                                    <td>
                                        <span class="me-2">گیٹ پاس نمبر</span>
                                        <span class="bold h5"><?php /*echo $gpDatum['id']; */ ?></span>
                                    </td>
                                    <td>
                                        <span class="me-2">گیٹ پاس دینے تاریخ	</span>
                                        <span class="bold h5"><?php /*echo $json_gp->gp_date; */ ?></span>
                                    </td>
                                </tr>-->
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">سمری نمبر</label>
                                        <input value="<?php echo $imp_tl_id; ?>" class="form-control border-dark">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">گیٹ پاس نمبر</label>
                                        <input value="<?php echo $gpDatum['id']; ?>"
                                               class="form-control border-dark">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">گیٹ پاس دینے تاریخ</label>
                                        <input value="<?php echo $json_gp->gp_date; ?>"
                                               class="form-control border-dark">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <div class="d-flex">
                                        <div class="input-group">
                                            <label class="input-group-text ps-0">مال بھیجنے والا نام</label>
                                            <input value="<?php echo $sender_receiver->sender_name; ?>"
                                                   class="form-control border-dark">
                                        </div>
                                        <div class="input-group">
                                            <label class="input-group-text ps-0">مال وصول کرنے والا نام</label>
                                            <input value="<?php echo $sender_receiver->receiver_name; ?>"
                                                   class="form-control border-dark">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">لوڈنگ گودام</label>
                                        <!--<span class="bold "><?php /*echo $godam_loading['name']; */ ?></span>-->
                                        <input value="<?php echo $godam_loading['name']; ?>"
                                               class="form-control border-dark">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">منشی نام</label>
                                        <input value="<?php echo $godam_loading['munshi']; ?>"
                                               class="form-control border-dark">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">موبائل</label>
                                        <input dir="ltr" value="<?php echo $godam_loading['mobile1']; ?>"
                                               class="form-control border-dark text-start">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">خالی کرنے گودام</label>
                                        <input value="<?php echo $godam_empty['name']; ?>"
                                               class="form-control border-dark">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">منشی نام</label>
                                        <input value="<?php echo $godam_empty['munshi']; ?>"
                                               class="form-control border-dark">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <label class="input-group-text ps-0">موبائل</label>
                                        <input dir="ltr" value="<?php echo $godam_empty['mobile1']; ?>"
                                               class="form-control border-dark text-start">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="row  mt-3 pb-2 ">
                            <div class="col-12">
                                <div class="border border-dark border-2">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th>جنس</th>
                                            <th>باردانہ نام</th>
                                            <th>باردانہ تعداد</th>
                                            <th>گیٹ پاس دینے والا نام</th>
                                            <th>موبائل</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><?php echo $json_maal->jins_name; ?></td>
                                            <td><?php echo $json_gp->bardana_name_gp; ?></td>
                                            <td><?php echo $json_gp->bardana_qty_gp; ?></td>
                                            <td><?php echo $json_gp->gp_giver; ?></td>
                                            <td><?php echo $json_gp->gp_giver_phone; ?></td>
                                        </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-12 text-center">
                                <p class="urdu mb-3">دستخط لازمی ہے۔ بغیر دستخط بل قبول نہیں ہے۔</p>
                            </div>
                            <!--<div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu">بروکر دستخط</label>
                                    <input class="form-control" value=""></div>
                            </div>-->
                            <div class="col">
                                <div class="input-group">
                                    <label class="input-group-text urdu">منشی دستخط</label>
                                    <input class="form-control border-dark" value=""></div>
                            </div>
                            <div class="col-3 text-end">
                                <p class="urdu text-nowrap">
                                    <span class="bold">پرنٹ تاریخ: </span>
                                    <span class="ms-1"><?php echo date('Y-m-d'); ?></span>
                                </p>
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

