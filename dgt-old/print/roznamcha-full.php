<?php $url_index = '../';
if (isset($_POST['secret']) && base64_decode($_POST['secret']) == "powered-by-upsol"
    && isset($_POST['r_type']) && isset($_POST['r_date_start']) && isset($_POST['r_date_end'])
    && isset($_POST['url'])
) {
    require("../connection.php");
    include("../variables.php");
    $url = mysqli_real_escape_string($connect, $_POST['url']);
    $r_type = mysqli_real_escape_string($connect, $_POST['r_type']);
    $r_sub_type = isset($_POST['r_sub_type']) ? mysqli_real_escape_string($connect, $_POST['r_sub_type']) : $r_type;
    $start_date = mysqli_real_escape_string($connect, $_POST['r_date_start']);
    $end_date = mysqli_real_escape_string($connect, $_POST['r_date_end']);
    $typesAllowed = array(GENERAL, KAROBAR, BANK, BANK_CHEQUE, BILL, BILL_CURRENCY);
    if (in_array($r_sub_type, $typesAllowed)) {
        if ($r_type == 'general') {
            $sql = "SELECT * FROM roznamchaas WHERE r_id > 0 ";
        } else {
            $sql = "SELECT * FROM roznamchaas WHERE r_type= '$r_type' ";
        }
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        if (isset($_POST['username']) && !empty($_POST['username'])) {
            $username = mysqli_real_escape_string($connect, $_POST['username']);
            $sql .= " AND username LIKE " . "'%$username%'" . " ";
        }
        if (isset($_POST['branch_id']) && $_POST['branch_id'] > 0) {
            $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id']);
            $branchName = getTableDataByIdAndColName('branches', $branch_id, 'b_name');
            $sql .= " AND branch_id = " . "'$branch_id'" . " ";
        } else {
            $branchId = $_SESSION['branch_id'];
            $branchName = Administrator() ? ' All Branch ' : getTableDataByIdAndColName('branches', $branchId, 'b_name');
        }
        $records = mysqli_query($connect, $sql);
        $recordStats = mysqli_query($connect, $sql);
        $totalRows = mysqli_num_rows($recordStats);
        $bnaamTotal = $jmaaTotal = $mezan = $bnaam_qtyTotal = $jmaa_qtyTotal = $mezanQty = 0;
        while ($stat = mysqli_fetch_assoc($recordStats)) {
            if ($stat['dr_cr'] == 'dr') {
                $jmaaTotal += $stat['amount'];
                $jmaa_qtyTotal += $stat['qty'];
            } else {
                $bnaamTotal += $stat['amount'];
                $bnaam_qtyTotal += $stat['qty'];
            }
        }
        $mezan = $jmaaTotal - $bnaamTotal;
        $mezanQty = $jmaa_qtyTotal - $bnaam_qtyTotal;
        $infoArray = array(
            GENERAL => array('title' => 'General Roznamcha'),
            KAROBAR => array('title' => 'Business Roznamcha'),
            BANK => array('title' => 'Bank Roznamcha'),
            BANK_CHEQUE => array('title' => 'Cheque Roznamcha'),
            BILL => array('title' => 'Bill Roznamcha'),
            BILL_CURRENCY => array('title' => 'Currency Roznamcha'),
        );
        switch ($r_sub_type) {
            case KAROBAR:
            case BANK:
                $grandArray = array(
                    'top' => array(),
                    'thead' => array('#' => '', 'Serial' => '', 'Branch' => '', 'Date' => '', 'UserID' => '', 'A/cNo' => '', 'Roz.#' => '', 'Name' => '10%', 'No.' => '', 'Details' => '40%', 'Dr.' => '', 'Cr.' => ''),
                );
                break;
            default:
                $grandArray = array('top' => array(), 'thead' => array());
                break;
        }
        $title = $infoArray[$r_sub_type]['title']; ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <meta name="description" content="Damaan Impex">
            <meta name="author" content="Asmatullah">
            <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
            <title><?php echo $title . '_' . date('Y_m_d-H_i_s'); ?></title>
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
                    font-size: 8px;
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
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between">
                <div><img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 90px;"></div>
                <div>
                    <div><b>Total Dr. </b> <?php echo round($jmaaTotal); ?></div>
                    <div><b>Total Cr. </b> <?php echo round($bnaamTotal); ?></div>
                    <div><b>Balance: </b> <?php echo round($mezan); ?></div>
                </div>
                <div class="text-end">
                    <h2 class="fw-bold mb-0"><?php echo $infoArray[$r_sub_type]['title']; ?></h2>
                    <div><b>No. of entries: </b><?php echo $totalRows; ?></div>
                    <div><b>Branch: </b><?php echo $branchName; ?></div>
                    <div>
                        <b>Date: </b><?php echo date('d-F-Y', strtotime($start_date)) . ' to ' . date('d-F-Y', strtotime($end_date)); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid px-0 overflow-hidden">
            <div class="row justify-content-center">
                <div class=" col-12">
                    <table class="table table-bordered ">
                        <thead>
                        <tr>
                            <?php //foreach ($grandArray['thead'] as $item => $value) {echo '<td>' . $item . '</td>';} ?>
                            <td>Date</td>
                            <td>Serial</td>
                            <!--<td>Branch</td>-->
                            <!--<td>UserID</td>-->
                            <td>A/cNo</td>
                            <td>Roz.#</td>
                            <td>Name</td>
                            <td>No.</td>
                            <td>Details</td>
                            <td>Dr.</td>
                            <td>Cr.</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $no = $dr_total = $cr_total = 0;
                        while ($roz = mysqli_fetch_assoc($records)) {
                            $dr = $cr = 0;
                            $no++;
                            $branchSerial = Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial'];
                            if ($roz['dr_cr'] == "dr") {
                                $dr = $roz['amount'];
                                $dr_total += $dr;
                            } else {
                                $cr = $roz['amount'];
                                $cr_total += $cr;
                            }
                            echo '<tr>';
                            //echo '<td>' . $no . '</td>';
                            switch ($r_sub_type) {
                                case KAROBAR:
                                case BANK:
                                    echo '<td class="text-nowrap">' . $roz["r_date"] . '</td>';
                                    echo '<td>' . $branchSerial . '</td>';
                                    //echo '<td class="font8">' . branchName($roz['branch_id']) . '</td>';
                                    //echo '<td>' . userName($roz['user_id']) . '</td>';
                                    echo '<td>' . $roz['khaata_no'] . '</td>';
                                    echo '<td>' . $roz['roznamcha_no'] . '</td>';
                                    echo '<td>' . $roz['r_name'] . '</td>';
                                    echo '<td>' . $roz['r_no'] . '</td>';
                                    echo '<td>' . ucfirst($roz['dr_cr']) . ':- ' . $roz["details"] . ' </td> ';
                                    echo '<td class="bold">' . $dr . ' </td> ';
                                    echo '<td class="bold">' . $cr . ' </td> ';
                                    break;
                                default:
                                    break;
                            }
                            echo '</tr>';
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="<?php echo $title; ?>"
                    data-tooltip-position="right">
                    <a href="../<?php echo $url; ?>"><i class="fa fa-long-arrow-alt-left"></i></a>
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
    <?php }
} else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
} ?>