<?php $url_index = '../';
if (isset($_GET['secret']) && base64_decode($_GET['secret']) == "powered-by-upsol"
    && isset($_GET['r_date_start']) && isset($_GET['r_date_end'])
    && isset($_GET['branch_id']) && isset($_GET['r_type']) && isset($_GET['username'])
) {
    require("../connection.php");
    $start_date = mysqli_real_escape_string($connect, $_GET['r_date_start']);
    $end_date = mysqli_real_escape_string($connect, $_GET['r_date_end']);
    $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
    $r_type = mysqli_real_escape_string($connect, $_GET['r_type']);
    $username = mysqli_real_escape_string($connect, $_GET['username']);
    $sql = "SELECT * FROM roznamchaas WHERE r_date BETWEEN '$start_date' AND '$end_date' ";
    $sql .= $branch_id > 0 ? " AND branch_id = " . "'$branch_id'" . " " : " ";
    $sql .= $r_type != '' ? " AND r_type = " . "'$r_type'" . " " : " ";
    $sql .= $username != '' ? " AND username LIKE " . "'%$username%'" . " " : " ";
    $branchName = $branch_id > 0 ? branchName($branch_id) : " All ";
    $records = mysqli_query($connect, $sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo 'Roznamcha_' . my_date(date('Y-m-d')); ?> </title>
        <meta name="description" content="Owner of DGT.llc">
        <meta name="author" content="Asmatullah Abdullah">
        <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
        <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/custom.css" rel="stylesheet">
        <link href="../assets/css/virtual-select.min.css" rel="stylesheet">
        <link href="../assets/fa/css/fontawesome.css" rel="stylesheet"/>
        <link href="../assets/fa/css/brands.css" rel="stylesheet"/>
        <link href="../assets/fa/css/solid.css" rel="stylesheet"/>
        <link rel="shortcut icon" href="../assets/images/favicon.jpg"/>
    </head>
    <body>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <img src="../assets/images/logo.png" alt="logo" class="img-fluid" width="100">
                    <div><b>Branch: </b><?php echo $branchName; ?></div>
                    <div><b>Entries: </b><span id="rows_span"></span></div>
                    <div><b>Dr. </b><span id="dr_total_span"></span></div>
                    <div><b>Cr. </b><span id="cr_total_span"></span></div>
                    <div><b>Bal. </b><span id="bal_span"></span></div>
                    <div class="text-end">
                        <h3 class="fw-bold mb-0">Roznamcha</h3>
                        <div>
                            <b>Date: </b><?php echo my_date($start_date) . ' to ' . my_date($end_date); ?>
                        </div>
                    </div>
                </div>
                <table class="table table-sm table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>SR#</th>
                        <th>USR</th>
                        <?php if (SuperAdmin()) {
                            echo '<th>BR.</th>';
                        } ?>
                        <th>DATE</th>
                        <th>TYPE</th>
                        <th>A/C</th>
                        <th>RZ#</th>
                        <th>NAME</th>
                        <th>NO.</th>
                        <th>DETAILS</th>
                        <th>Dr.</th>
                        <th>Cr.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $records = mysqli_query($connect, $sql);
                    $cr_total = $dr_total = 0;
                    $rows = mysqli_num_rows($records);
                    if ($rows > 0) {
                        while ($roz = mysqli_fetch_assoc($records)) {
                            $dr = $cr = 0;
                            $r_id = $roz["r_id"];
                            $tool = SuperAdmin() ? 'G.Sr#' . $roz['r_id'] . '&nbsp;&nbsp;&nbsp;' . 'Branch Sr#' . $roz['branch_serial'] : 'Branch Sr#' . $roz['branch_serial']; ?>
                            <tr>
                                <td><?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?></td>
                                <td><?php echo $roz['user_id']; ?></td>
                                <td class="text-nowrap"><?php echo SuperAdmin() ? branchName($roz['branch_id']) : ''; ?></td>
                                <td class="text-nowrap"><?php echo my_date($roz['r_date']); ?></td>
                                <td><?php echo badge(shortName($roz['r_type']), 'secondary'); ?></td>
                                <td><?php echo $roz['khaata_no']; ?></td>
                                <td><?php echo $roz['roznamcha_no']; ?></td>
                                <td><?php echo $roz['r_name']; ?></td>
                                <td><?php echo $roz['r_no']; ?></td>
                                <td>
                                    <?php echo $roz['details'];
                                    if ($roz['r_type'] == 'Bill') {
                                        echo $roz['currency'] . ' AMOUNT' . $roz['qty'] . ' Per Price ' . $roz['per_price'];
                                    }
                                    if ($roz['r_type'] == 'Bank') {
                                        echo ' Bank:' . bankName($roz['bank_id']) . ' Date:' . my_date($roz['r_date_payment']);
                                    } ?>
                                </td>
                                <?php if ($roz['dr_cr'] == "dr") {
                                    $dr = $roz['amount'];
                                    $dr_total += $dr;
                                } else {
                                    $cr = $roz['amount'];
                                    $cr_total += $cr;
                                } ?>
                                <td class="text-success"><?php echo round($dr); ?></td>
                                <td class="text-danger"><?php echo round($cr); ?></td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr class="text-center"><th colspan="12">No record(s)</th></tr>';
                    } ?>
                    <input type="hidden" id="rows" value="<?php echo $rows; ?>">
                    <input type="hidden" id="dr_total" value="<?php echo $dr_total; ?>">
                    <input type="hidden" id="cr_total" value="<?php echo $cr_total; ?>">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="list-group d-print-none shadow-lg position-fixed rounded-0 start-0" style="top: 5%">
        <a href="../roznamcha" class="list-group-item list-group-item-secondary p-1"><i class="fa fa-arrow-left"></i>
            Back</a>
        <a onclick="window.print();" href="#." class="list-group-item list-group-item-secondary p-1"><i
                    class="fa fa-print"></i> Print</a>
    </div>

    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <script src="../assets/bs/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/virtual-select.min.js"></script>

    <script>
        $("#rows_span").text($("#rows").val());
        var dr_total = $("#dr_total").val();
        var cr_total = $("#cr_total").val();
        var bal = Number(dr_total) - Number(cr_total);
        $("#dr_total_span").text(dr_total);
        $("#cr_total_span").text(cr_total);
        $("#bal_span").text(bal);

        if (bal > 0) {
            $("#bal_span").addClass('text-success');
        } else if (bal < 0) {
            $("#bal_span").addClass('text-danger');
        }


    </script>
    </body>
    </html>
<?php } else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
} ?>