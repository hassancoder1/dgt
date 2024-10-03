<?php $backUrl = '../ledger-all-categories';
//$is_small = false;
if (isset($_GET['cat_ids']) && isset($_GET['jbval']) && isset($_GET['secret'])
    && isset($_GET['branch_id']) && isset($_GET['start_date']) && isset($_GET['end_date']) /*&& isset($_GET['printSizeRadio'])*/
) {
    if (base64_decode($_GET['secret']) == "powered-by-upsol") {
        require("../connection.php");
        global $connect;
        $sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id != 0 ";
        $arr = array(1 => 'General Print', 2 => 'Checking Print', 3 => 'Ogurai Print');
        $print_type = mysqli_real_escape_string($connect, $_GET['print_type']);
        if (!in_array($print_type, array(1, 2, 3))) {
            exit(0);
        }
        //$is_large = $printSizeRadio == "lg" ? true : false;
        $start_date = mysqli_real_escape_string($connect, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($connect, $_GET['end_date']);
        if (empty($start_date)) {
            $s_date = '2023-01-01';
            $e_date = date('Y-m-d');
        } else {
            $s_date = $start_date;
            $e_date = $end_date;
            $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
        }
        $in = $_GET['cat_ids'];
        if (!empty($in)) {
            $sql .= " AND cat_id IN " . $in;
        }
        $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
        if ($branch_id > 0) {
            $sql .= " AND khaata_branch_id = " . "'$branch_id'" . " ";
        }
        $jmaaBnaamArrayVals = array("jmaa" => "جمع", "bnaam" => "بنام");
        $isJB = false;
        $jbval = 0;
        if (!empty($_GET['jbval'])) {
            $isJB = true;
            $jbval = mysqli_real_escape_string($connect, $_GET['jbval']);
        }
        $jmaaTotalPrint = mysqli_real_escape_string($connect, $_GET['jmaaTotalPrint']);
        $bnaamTotalPrint = mysqli_real_escape_string($connect, $_GET['bnaamTotalPrint']);
        $mezanPrint = mysqli_real_escape_string($connect, $_GET['mezanPrint']); ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>LEDGER_ALL_CATEGORIES_<?php echo date('Y_m_d'); ?></title>
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
            <div class="d-flex align-items-center justify-content-between">
                <div><img src="../assets/images/logo_dgt.png" alt="logo" class="img-fluid" style="width: 80px;"></div>
                <div>
                    <?php /*if ($isJB && $is_large) {
                        if ($jbval == "jmaa") {echo '<div><b>Total Dr. </b>' . $jmaaTotalPrint . '</div>';}
                        if ($jbval == "bnaam") {echo '<div><b>Total Cr. </b>' . $bnaamTotalPrint . '</div>';} } else {}*/
                    if ($print_type == 1) {
                        echo '<div><b>Total Dr. </b>' . $jmaaTotalPrint . '</div>';
                        echo '<div><b>Total Cr. </b>' . $bnaamTotalPrint . '</div>';
                    }
                    echo '<div><b>Balance: </b>' . $mezanPrint . '</div>'; ?>
                </div>
                <div class="text-end">
                    <h2 class="fw-bold mb-0">Ledger All Categories</h2>
                    <?php echo $arr[$print_type]; ?>
                </div>
            </div>
        </div>
        <div class="container-fluid px-0 overflow-hidden">
            <div class="row">
                <div class="col-lg-4 d-print-none">
                    <div class="position-fixed mt-lg-5">
                        <div class="input-group mt-lg-5">
                            <label for="print_type" class="input-group-text">Print type</label>
                            <select class="form-select" name="print_type" id="print_type">
                                <?php $arr = array(1 => 'General Print', 2 => 'Checking Print', 3 => 'Ogurai Print');
                                foreach ($arr as $index => $value) {
                                    $selected_ser = $print_type == $index ? 'selected' : '';
                                    echo '<option ' . $selected_ser . ' value="' . $index . '">' . $value . ' &nbsp;&nbsp;&nbsp; </option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="text-nowrap">
                            <td>#</td>

                            <?php echo $print_type == 1 ? '<td>Type|Branch</td>' : '<td>Info</td>';?>
                            <td>Account</td>
                            <?php echo $print_type == 1 ? '<td>Address</td>' : '';
                            echo $print_type == 1 ? '<td>Contacts</td>' : '';
                            echo $print_type == 1 ? '<td>Date</td>' : ''; ?>
                            <td>Amount</td>
                        </tr>
                        <?php $khaataQ = mysqli_query($connect, $sql);
                        $rows = $dr = $cr = $balance = $dr_total = $cr_total = $balance_total = 0;
                        $number = 1;
                        while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                            $k_id = $khaata['khaata_id'];
                            $khaataDatum = khaataSingle($k_id);
                            $k_no = $khaataDatum['khaata_no'];
                            $dr = roznamchaAmount($k_id, 'dr');
                            $cr = roznamchaAmount($k_id, 'cr');
                            $balance = $dr - $cr;
                            if ($balance > -50 && $balance < 50) continue;
                            $dr_total += $dr;
                            $cr_total += $cr;

                            /*if ($isJB) {
                                if ($jbval == "dr") {
                                    if ($balance < 0) continue;
                                } else {
                                    if ($balance > 0) continue;
                                }
                            }*/
                            $balance_total = $dr_total - $cr_total;
                            $rows++;
                            $redGreenText = $balance > 0 ? 'text-success' : 'text-danger'; ?>
                            <tr>
                                <td><?php echo $number; ?></td>
                                <td>
                                    <?php echo '<span class="badge badge-soft-danger">' . strtoupper($khaataDatum['acc_for']) . '</span><br>';
                                    echo '<b>G A/c.#</b>' . $khaataDatum['id'];
                                    if ($print_type == 1) {
                                        echo '<br><b>B.</b>' . branchName($khaataDatum['branch_id']) . '<br>';
                                        echo '<b>CAT.</b>' . catName($khaataDatum['cat_id']);
                                    } ?>
                                </td>
                                <td>
                                    <?php echo '<b>A/c#</b>' . $k_no . '<br>';
                                    echo '<b>A/c Name</b>' . $khaataDatum['khaata_name'] . '<br>';
                                    if ($print_type == 1) {
                                        echo '<b>Company</b>' . $khaataDatum['comp_name'] . '<br>';
                                        echo '<b>Owner</b>' . $khaataDatum['owner_name'];
                                    } ?>
                                </td>
                                <?php if ($print_type == 1) { ?>
                                    <td>
                                        <?php echo '<b>Country</b>' . countryName($khaataDatum['country_id']);
                                        echo '<br><b>City</b>' . $khaataDatum['city'];
                                        echo '<br><b>Address</b>' . $khaataDatum['address']; ?>
                                    </td>

                                    <td><?php $details = ['indexes' => $khaataDatum['indexes'], 'vals' => $khaataDatum['vals']];
                                        echo displayKhaataDetails($details); ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <?php $dd = mysqli_query($connect,
                                            "select min(r_date) as min_date ,max(r_date) as max_date from `roznamchaas` WHERE khaata_no = '$k_no'");
                                        $dataa = mysqli_fetch_assoc($dd);
                                        echo 'S:' . my_date($dataa['min_date']) . '<br>';
                                        echo 'L:' . my_date($dataa['max_date']); ?>
                                    </td>
                                <?php } ?>
                                <td class="bold">
                                    <?php //if (!$isJB) {if ($is_large) {echo 'Dr. ' . round($dr) . '<br>';echo '<span class="text-danger">Cr. ' . round($cr) . '</span><br>';}}
                                    if ($print_type == 1) {
                                        echo 'Dr. ' . round($dr) . '<br>';
                                        echo '<span class="text-danger">Cr. ' . round($cr) . '</span><br>';
                                    }
                                    echo '<span class=" ' . $redGreenText . '">Bal:' . round($balance, 2) . '</span>'; ?>
                                </td>
                            </tr>
                            <?php $number++;
                        } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script src="../assets/tooltip/tooltip.min.js"></script>
        <div class="sticky-social d-print-none" style="z-index: 999999">
            <ul class="social">
                <li class="bg-dark" data-tooltip="Go Back" data-tooltip-position="right">
                    <a href="<?php echo $backUrl; ?>"><i class="fa fa-long-arrow-alt-left"></i></a>
                </li>
                <li class="facebook" title="PDF Print">
                    <a class="cursor-pointer" onclick="window.print();"><i class="fa fa-print"></i></a>
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

<script>
    document.querySelector('#print_type').addEventListener('change', function () {
        window.$_GET = location.search.substr(1).split("&").reduce((o, i) => (u = decodeURIComponent, [k, v] = i.split("="), o[u(k)] = v && u(v), o), {});
        var print_type = this.value;
        var urlString = "ledger-all-categories?secret=" + btoa('powered-by-upsol') + '&start_date=' + $_GET.start_date + '&end_date=' + $_GET.end_date + '&cat_ids=' + $_GET.cat_ids + '&branch_id=' + $_GET.branch_id + '&jbval=' + $_GET.jbval + '&jmaaTotalPrint=' + $_GET.jmaaTotalPrint + '&bnaamTotalPrint=' + $_GET.bnaamTotalPrint
            + '&mezanPrint=' + $_GET.mezanPrint
            + '&print_type=' + print_type
        ;
        //var id = obj.options[obj.selectedIndex];
        //console.log(id);
        //urlString += '&print_type' + print_type;

        window.location.href = urlString;
    });
</script>