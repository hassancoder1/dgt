<?php $backURL = '../ledger';
if (isset($_POST['khaata_id']) && ($_POST['khaata_id'] > 0) && isset($_POST['secret']) && isset($_POST['branch_id']) && isset($_POST['start_date']) && isset($_POST['end_date'])) {
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
            $s_date = $e_date = date('Y-m-d');
        } else {
            $s_date = $start_date;
            $e_date = $end_date;
            $sql .= " AND r_date BETWEEN " . "'$start_date'" . " AND " . "'$end_date'" . " ";
        }
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Ledger_<?php echo $khaata['khaata_no'].'_'. date('Y_m_d'); ?></title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta content="Al Ras Deira Dubai office UAE dubai" name="description"/>
            <meta content="DGT L.L.C" name="author"/>
            <link rel="shortcut icon" href="../assets/images/favicon.jpg">
            <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
            <link href="../assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
            <link href="../assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
            <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
            <style>
                body {
                    background-color: white !important;
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

                .table-bordered {
                    border: 1px solid #000000;
                }
            </style>
        </head>
        <body>
        <div class="container-fluid">
            <div class="row justify-content-center ">
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <div>
                            <img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;">
                        </div>
                        <div>
                            Rows: <span id="rows_span"></span>
                        </div>
                        <div class="text-end">
                            <h1 class="fw-bold mb-0 text-uppercase">Ledger</h1>
                            <?php echo '<b>A/c No:</b> ' . $khaata['khaata_no']; ?><br>
                            <?php echo $s_date != '' ? date('d-F-Y', strtotime($s_date)) : '';
                            echo $e_date != '' ? ' To: ' . date('d-F-Y', strtotime($e_date)) : ''; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center  ">
                <div class="col-12">
                    <div class="d-flex justify-content-between border border-dark small px-1">
                        <div class="text-nowrap">
                            <b>Branch</b>
                            <span class="-under"><?php echo branchName($khaata['branch_id']); ?></span>
                            <br>
                            <b>Category</b><?php echo catName($khaata['cat_id']); ?>
                        </div>
                        <div class="w-50">
                            <b>A/c Name:</b><?php echo $khaata['khaata_name']; ?><br>
                            <b>Company:</b><?php echo $khaata['comp_name']; ?><br>
                            <b>Address</b><?php echo $khaata['address']; ?>
                        </div>
                        <div>
                            <?php $details = ['indexes' => $khaata['indexes'], 'vals' => $khaata['vals']];
                            displayKhaataDetails($details); ?>
                        </div>
                        <div>
                            <b>Old Balance</b> <span class="under"><?php echo '0'; ?><br>
                            <b>Cr. Balance</b> <span class="under"><?php echo 'AAA'; ?>
                        </div>
                    </div>
                    <div class="p-0">
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr class="">
                                <!--<th>Branch</th>-->
                                <th>Date</th>
                                <th>Serial</th>
                                <th>Name-No</th>
                                <th>Details</th>
                                <th>Dr.</th>
                                <th>Cr.</th>
                                <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $data = mysqli_query($connect, $sql);
                            $numRows = $dr_total = $cr_total = 0;
                            if (mysqli_num_rows($data) > 0) {
                                $jmaa = $bnaam = $balance = 0;
                                while ($datum = mysqli_fetch_assoc($data)) {
                                    $dr = $cr = 0;
                                    echo '<tr class="text-nowrap-">';
                                    //echo '<td>' . branchName($datum['branch_id']) . '</td>';
                                    echo '<td class="text-nowrap">' . $datum["r_date"] . '</td>';
                                    echo '<td>' . $datum['r_id'] . '</td>';
                                    echo '<td>' . $datum['r_name'] . ' ';
                                    echo $datum['r_no'] . '</td>';
                                    if ($datum['dr_cr'] == "dr") {
                                        $dr = $datum['amount'];
                                        $dr_total += $dr;
                                        $jmaa += $datum['amount'];
                                    } else {
                                        $cr = $datum['amount'];
                                        $cr_total += $cr;
                                        $bnaam += $datum['amount'];
                                    }
                                    $balance = $jmaa - $bnaam;
                                    $bank_str = $date_str = "";
                                    /*if ($datum['r_type'] == "bank") {
                                        $bank_str = ' <span class="">Bank: ' . getTableDataByIdAndColName('banks', $datum['bank_id'], 'bank_name') . '</span> ';
                                        $date_str = ' <span class="">Payment Date: ' . $datum['r_date_payment'] . '</span> ';
                                    }*/
                                    echo '<td>' . $bank_str . $datum["details"] . ' </td > ';
                                    echo '<td> ' . $dr . ' </td > ';
                                    echo '<td class="text-danger"> ' . $cr . ' </td > ';
                                    echo '<td class="bold"> ' . $balance . '</td > ';
                                    echo '</tr> ';
                                    $numRows++;
                                }
                            } ?>
                            </tbody>
                        </table>
                        <input type="hidden" value="<?php echo $numRows; ?>" id="rows_input">
                    </div>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none">
            <ul class="social">
                <li class="bg-dark" data-tooltip="Back to ledger" data-tooltip-position="right">
                    <a href="../ledger?back-khaata-no=<?php echo $khaata['khaata_no']; ?>"><i
                            class="fa fa-long-arrow-alt-left"></i></a>
                </li>
                <li class="facebook" title="PDF Print">
                    <a class="cursor-pointer" onclick="window.print();"><i class="fa fa-print"></i></a>
                </li>
                <li class="excel d-none" data-tooltip="Excel Print" data-tooltip-position="right">
                    <form action="../expor-excel/ledger-form.php" method="post">
                        <input type="hidden" name="secret" value="<?php echo base64_encode('powered-by-upsol'); ?>">
                        <input type="hidden" name="khaata_id" id="khaata_id_print" value="<?php echo $khaata_id; ?>">
                        <input type="hidden" name="branch_id" value="<?php echo $branch_id; ?>">
                        <input type="hidden" name="start_date" id="start_date_print"
                               value="<?php echo $start_date; ?>">
                        <input type="hidden" name="end_date" id="end_date_print" value="<?php echo $end_date; ?>">
                        <button type="submit"><i class="fa fa-file-excel"></i></button>
                    </form>
                </li>
                <li class="word d-none">
                    <a class="twitter"><i class="fa fa-file-word"></i></a>
                </li>
            </ul>
        </div>
        </body>
        </html>
        <?php if (isset($_GET['print'])) {
            echo '<script>window.print();</script>';
        }
    } else {
        echo '<script>window.location.href="' . $backURL . '";</script>';
    }
} else {
    echo '<script>window.location.href="' . $backURL . '";</script>';
} ?>

<script>
    document.getElementById("rows_span").textContent = document.getElementById("rows_input").value;
</script>