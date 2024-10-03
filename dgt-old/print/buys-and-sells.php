<?php $backUrl = '../';
if (isset($_POST['date_start']) && isset($_POST['date_end']) && isset($_POST['secret']) && (base64_decode($_POST['secret']) == "powered-by-upsol")) {
    require("../connection.php");
    include("../variables.php");
    $start_date = mysqli_real_escape_string($connect, $_POST['date_start']);
    $end_date = mysqli_real_escape_string($connect, $_POST['date_end']);
    $typesAllowed = array(BUYS, BUYS_ADD, SELLS, GP);
    $type = mysqli_real_escape_string($connect, $_POST['type']);
    if (in_array($type, $typesAllowed)) {
        switch ($type) {
            case BUYS:
                $sql = "SELECT * FROM `buys` WHERE id > 0 ";
                $sql .= !empty($start_date) && !empty($end_date) ? " AND b_date BETWEEN '$start_date' AND '$end_date'" : "";
                $records = mysqli_query($connect, $sql);
                $totalRows = mysqli_num_rows($records);
                $title = 'Purchase General Form';
                $dates = empty($start_date) && empty($end_date) ? 'All records' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'buys',
                    'top' => array(
                        array('col_name' => 'Date', 'col_val' => $dates, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $title, 'class' => 'col-4 px-4', 'input_class' => 'text-center line-height-0 urdu border-0'),
                        array('col_name' => 'Rows', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('Serial #' => '', 'UserId' => '', 'Date' => '', 'Goods Name' => '', 'Allot Name' => '', 'Purchase City' => '', 'Bill #' => '', 'Cr. A/c.' => '', 'Purchase' => '', 'Sale' => '', 'Balance' => '',
                    ),
                );
                break;
            case BUYS_ADD:
                $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id']);
                $records = fetch('buys_details', array('buys_id' => $buys_id));
                $totalRows = mysqli_num_rows($records);
                $title = 'Purchase details';
                $dates = empty($start_date) && empty($end_date) ? 'سارے ریکارڈز' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'buys-add?id=' . $buys_id,
                    'top' => array(
                        array('col_name' => 'Purchase serial', 'col_val' => $buys_id, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $title, 'class' => 'col-4 px-4', 'input_class' => 'text-center line-height-0 urdu border-0'),
                        array('col_name' => 'اندراج', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('Bill #' => '', 'Container #' => '', 'Allot Name' => '', 'Loading depot' => '', 'Purcahser name' => '', 'Bardana Name' => '', 'Marka' => '', 'Bardana qty' => '', 'Per wt.' => '', 'Total wt.' => '', 'Empty wt.' => '', 'Amount' => '', 'Dr. A/c.' => ''),
                );
                break;
            case SELLS:
                $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id']);
                $records = fetch('buys_sold', array('buys_id' => $buys_id, 'is_gp' => 1));
                $totalRows = mysqli_num_rows($records);
                $buysQ = fetch('buys', array('id' => $buys_id));
                $buys = mysqli_fetch_assoc($buysQ);
                $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum, SUM(total_wt) as total_wtSum, SUM(total_empty_wt) as total_empty_wtSum, SUM(saaf_wt) as saaf_wtSum, SUM(qeemat_raqam) as qeemat_raqamSum FROM buys_details WHERE buys_id = '$buys_id'");
                $detailSums = mysqli_fetch_assoc($detailsQ);
                $title = ' Sale details';
                $dates = empty($start_date) && empty($end_date) ? 'All records' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'sells-add?id=' . $buys_id . '&view=sale',
                    'top' => array(
                        array('col_name' => 'Purchase serial', 'col_val' => $buys_id, 'class' => 'col-3', 'input_class' => 'ltr'),
                        array('col_name' => '', 'col_val' => $title, 'class' => 'col-6 px-4', 'input_class' => 'text-center line-height-0 urdu border-0 '),
                        array('col_name' => 'Rows', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                        array('col_name' => '', 'col_val' => '', 'class' => 'col-12 m-0 p-0', 'input_class' => 'd-none'),
                        array('col_name' => 'Goods Name', 'col_val' => $buys['jins'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'Allot Name', 'col_val' => $buys['allot_name'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'Total wt.', 'col_val' => $detailSums['total_wtSum'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'Total Net. wt.', 'col_val' => $detailSums['saaf_wtSum'], 'class' => 'col', 'input_class' => ''),
                        array('col_name' => 'Total Balance', 'col_val' => buyBalance($buys_id), 'class' => 'col', 'input_class' => ''),
                        array('col_name' => '', 'col_val' => '', 'class' => 'col-12 m-0 p-0', 'input_class' => 'd-none'),

                    ),
                    'thead' => array('Bill#' => '', 'Date' => '', 'Bardana name' => '', 'Goods name' => '', 'Allot name' => '', 'Loading deopt' => '', 'Selelr A/c.' => '', 'Broker' => '', 'Bardana qty' => '', 'Amount' => '', 'Payemnt date' => ''),
                );
                break;
            case GP:
                $buys_id = mysqli_real_escape_string($connect, $_POST['buys_id']);
                $records = fetch('buys_sold', array('buys_id' => $buys_id));/*, 'is_gp' => 1*/
                $totalRows = mysqli_num_rows($records);
                $title = 'Gate pass details';
                $dates = empty($start_date) && empty($end_date) ? 'All records' : $start_date . ' to ' . $end_date;
                $infoArray = array(
                    'url' => 'sells-add?id=' . $buys_id . '&view=gp',
                    'top' => array(
                        array('col_name' => 'Purchase serial: ', 'col_val' => $buys_id, 'class' => 'col-3', 'input_class' => 'ltr'),
                        //array('col_name' => '', 'col_val' => $title, 'class' => 'col-4 px-4', 'input_class' => 'text-center line-height-0 urdu border-0'),
                        array('col_name' => 'Rows: ', 'col_val' => $totalRows, 'class' => 'col-2', 'input_class' => ''),
                    ),
                    'thead' => array('Bill #' => '', 'Date' => '', 'Bardana Name' => '', 'Goods name' => '', 'Allot name' => '', 'Loading deopot' => '', 'Seller A/c.' => '', 'Seller name' => '', 'Broker' => '', 'Bardana qty' => ''),
                );
                break;
            default:
                $title = '';
                $infoArray = array();
                break;
        } ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title>Booking Purchase-<?php echo date('Y_m_d-H_i_s'); ?></title>
            <link rel="preconnect" href="https://fonts.googleapis.com/">
            <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
            <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
            <link rel="stylesheet" href="../assets/css/custom.css">
            <link rel="shortcut icon" href="../assets/images/logo.png"/>
            <link rel="stylesheet" href="../assets/css/icons.min.css">
            <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
            <style>
                body {
                    background-color: white;
                }

                * {
                    color: black;
                }

                h6 {
                    margin-bottom: 0;
                }

                .table > :not(caption) > * > * {
                    padding: 0.1rem .45rem;
                }

                input {
                    pointer-events: none;
                    font-weight: bold !important;
                    font-family: 'Noto Naskh Arabic', serif;
                }

                .table tbody tr td {
                    font-size: 10px;
                    color: inherit;
                }

                .table thead tr th {
                    /*font-size: 8px;*/
                    background: black;
                    color: white;
                }

                .under {
                    text-decoration: underline;
                    text-underline-offset: 10%;
                }

                .table-bordered {
                    border: 1px solid #000000;
                }
            </style>
        </head>
        <body>
        <div class="container-fluid px-0 overflow-hidden">
            <div class="row justify-content-center mt-0 pt-0">
                <div class="col-lg-8 col-12">
                    <?php //include("inc-print-top-sm.php"); ?>
                    <div class="p-1">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="">
                                <img src="../assets/images/logo.png" alt="logo" class="img-fluid" style="width: 150px;">
                                <h6 class="mt-3">
                                    DAMAAN GENERAL TRADING L.L.C <br>
                                    Al Ras Deira Dubai office UAE <br>
                                    +971544186664 damaan.dubai@gmail.com
                                </h6>
                            </div>
                            <div class="text-end">
                                <h2 class="mt-4 fw-bold"><?php echo $title; ?></h2>
                                <div class="mt-5">
                                    <?php foreach ($infoArray['top'] as $arr) {
                                        echo '<h6 class="fw-bold mb-3">' . $arr['col_name'] . ' ' . $arr['col_val'] . '</h6>';
                                        //echo '<div class="' . $arr['class'] . '"><div class="input-group">';
                                        //echo '<label class="input-group-text ps-0 ">' . $arr['col_name'] . '</label>';
                                        //echo '<input class="form-control ' . $arr['input_class'] . '" value="' . $arr['col_val'] . '">';
                                        //echo '</div></div>';
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="border-top mt-4">
                            <table class="table align-middle table-centered">
                                <thead>
                                <tr class="text-nowrap">
                                    <?php foreach ($infoArray['thead'] as $item => $value) {
                                        echo '<th width="' . $value . '%">' . $item . '</th>';
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                switch ($type) {
                                    case BUYS:
                                        while ($loading = mysqli_fetch_assoc($records)) {
                                            echo '<tr>';
                                            echo '<td>' . $loading["id"] . '</td>';
                                            echo '<td>' . $loading["username"] . '</td>';
                                            echo '<td>' . $loading["b_date"] . '</td>';
                                            echo '<td>' . $loading["jins"] . '</td>';
                                            echo '<td>' . $loading["allot_name"] . '</td>';
                                            echo '<td>' . $loading["loading_city"] . '</td>';
                                            echo '<td>' . $loading["bail_no"] . '</td>';
                                            echo '<td>' . $loading["bnaam_khaata_no"] . '</td>';
                                            echo '<td>' . buyBalance($loading['id']) . '</td>';
                                            echo '<td>' . sellBalance($loading['id']) . '</td>';
                                            echo '<td>' . buySellBalance($loading['id']) . '</td>';
                                            echo '</tr>';
                                        }
                                        break;
                                    case BUYS_ADD:
                                        while ($loading = mysqli_fetch_assoc($records)) {
                                            echo '<tr>';
                                            echo '<td>' . $loading["bill_no"] . '</td>';
                                            echo '<td>' . $loading["container_no"] . '</td>';
                                            echo '<td>' . $loading["allot_name"] . '</td>';
                                            echo '<td>' . $loading["loading_godam"] . '</td>';
                                            echo '<td>' . $loading["owner_name"] . '</td>';
                                            echo '<td>' . $loading["bardana_name"] . '</td>';
                                            echo '<td>' . $loading["marka"] . '</td>';
                                            echo '<td>' . $loading["bardana_qty"] . '</td>';
                                            echo '<td>' . $loading["per_wt"] . '</td>';
                                            echo '<td>' . $loading["total_wt"] . '</td>';
                                            echo '<td>' . $loading["saaf_wt"] . '</td>';
                                            echo '<td>' . $loading["qeemat_raqam"] . '</td>';
                                            echo '<td>' . $loading["jmaa_khaata_no"] . '</td>';
                                            echo '</tr>';
                                            echo '<tr>';
                                            echo '<td colspan="13">تفصیل: ' . $loading["more_details"] . '</td>';
                                            echo '</tr>';
                                        }
                                        break;
                                    case SELLS:
                                        $bard_to = $qeemat_to = 0;
                                        while ($loading = mysqli_fetch_assoc($records)) {
                                            echo '<tr>';
                                            echo '<td>' . $loading["bill_no"] . '</td>';
                                            echo '<td>' . $loading["s_date"] . '</td>';
                                            echo '<td>' . $loading["bardana_name"] . '</td>';
                                            echo '<td>' . $loading["jins"] . '</td>';
                                            echo '<td>' . $loading["allot_name"] . '</td>';
                                            echo '<td>' . $loading["loading_godam"] . '</td>';
                                            echo '<td>' . $loading["seller_khaata_no"] . '</td>';
                                            echo '<td>' . $loading["broker_name"] . '</td>';
                                            echo '<td>' . $loading["bardana_qty"] . '</td>';
                                            echo '<td>' . $loading["qeemat_raqam"] . '</td>';
                                            echo '<td>' . $loading["payment_date"] . '</td>';
                                            echo '</tr>';
                                            $bard_to += $loading["bardana_qty"];
                                            $qeemat_to += $loading["qeemat_raqam"];
                                        }
                                        echo '<tr>';
                                        echo '<th class="text-end" colspan="8">ٹوٹل</th>';
                                        echo '<th>' . $bard_to . '</th>';
                                        echo '<th>' . $qeemat_to . '</th>';
                                        echo '</tr>';
                                        break;
                                    case GP:
                                        while ($loading = mysqli_fetch_assoc($records)) {
                                            $seller_khaataQ = fetch('khaata', array('id' => $loading['seller_khaata_id']));
                                            $seller_khaata = mysqli_fetch_assoc($seller_khaataQ);
                                            echo '<tr>';
                                            echo '<td>' . $loading["bill_no"] . '</td>';
                                            echo '<td>' . $loading["s_date"] . '</td>';
                                            echo '<td>' . $loading["bardana_name"] . '</td>';
                                            echo '<td>' . $loading["jins"] . '</td>';
                                            echo '<td>' . $loading["allot_name"] . '</td>';
                                            echo '<td>' . $loading["loading_godam"] . '</td>';
                                            echo '<td>' . $loading["seller_khaata_no"] . '</td>';
                                            echo '<td>' . $seller_khaata['khaata_name'] . '</td>';
                                            echo '<td>' . $loading["broker_name"] . '</td>';
                                            echo '<td>' . $loading["bardana_qty"] . '</td>';
                                            echo '</tr>';
                                        }
                                        break;
                                    default:
                                        break;
                                } ?>
                                </tbody>
                            </table>
                            <div class="row mt-4 justify-content-between">
                                <div class="col-4">
                                    <p><b>Munshi Signature</b><br><span class="small"><?php //echo $seller_khaata['khaata_name']; ?></span>
                                    </p>
                                </div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <p><b>Date/Time</b><br><span class="small"><?php //echo $seller_khaata['khaata_name']; ?></span>
                                    </p>
                                </div>
                                <div class="col-4"><p></p><div class="border-top border-2 border-dark"></div></div>
                                <div class="col-4"></div>
                                <div class="col-4">
                                    <?php echo date('Y-m-d H:i:s'); ?>
                                    <div class="border-top border-2 border-dark"></div>
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
    <?php } else {
        echo '<script>window.location.href="' . $backUrl . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backUrl . '";</script>';
} ?>

