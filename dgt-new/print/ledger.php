<?php $url_index = '../';
if (isset($_GET['secret']) && base64_decode($_GET['secret']) == "powered-by-upsol"
    && isset($_GET['start_date']) && isset($_GET['end_date'])
    && isset($_GET['branch_id']) && isset($_GET['khaata_id']) && $_GET['khaata_id'] > 0
) {
    require("../connection.php");
    $print_type = isset($_GET['print_type']) ? mysqli_real_escape_string($connect, $_GET['print_type']) : 'sm';
    $khaata_id = mysqli_real_escape_string($connect, $_GET['khaata_id']);
    $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
    $start_date = mysqli_real_escape_string($connect, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($connect, $_GET['end_date']);

    $this_url = 'ledger?secret=' . $_GET['secret'] . '&khaata_id=' . $khaata_id . '&branch_id=' . $branch_id . '&start_date=' . $start_date . '&end_date=' . $end_date;

    $kh = fetch('khaata', array('id' => $khaata_id));
    $khaata = mysqli_fetch_assoc($kh);
    $sql = "SELECT * FROM `roznamchaas` WHERE khaata_id = '$khaata_id' ";
    $sql .= $branch_id > 0 ? " AND branch_id = " . "'$branch_id'" . " " : " ";
    if ($start_date != '' && $end_date != '') {
        $sql .= " AND r_date BETWEEN " . "'$start_date'" . " AND " . "'$end_date'" . " ";
    }
    $dates = $start_date != '' && $end_date != '' ? '<div><b>Date: </b>' . my_date($start_date) . ' - ' . my_date($end_date) . '</div>' : '';
    $branchName = $branch_id > 0 ? branchName($branch_id) : " All ";
    $records = mysqli_query($connect, $sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo 'Ledger_' . my_date(date('Y-m-d')); ?> </title>
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
                    <div>
                        <div><b>Br. </b><?php echo $branchName; ?>
                            <b>Cat. </b><?php echo catName($khaata['cat_id']); ?></div>
                        <div><b>Entries: </b><span id="rows_span"></span></div>
                        <?php echo $dates; ?>
                    </div>
                    <div>
                        <div><b>Dr. </b><span id="dr_total_span"></span></div>
                        <div><b>Cr. </b><span id="cr_total_span"></span></div>
                        <div><b>Balance </b><span class="fw-bold" id="bal_span"></span></div>
                    </div>
                    <div>
                        <div><b>A/C: </b><?php echo $khaata['khaata_no']; ?></div>
                        <div><b>A/C Name: </b><?php echo $khaata['khaata_name']; ?></div>
                        <div><b>PHONE: </b><?php echo $khaata['phone']; ?></div>
                    </div>

                    <div class="text-end">
                        <h3 class="fw-bold mb-0">Ledger</h3>
                    </div>
                </div>
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                    <tr>
                        <?php echo $print_type == 'lg' ? '<th>B.</th>' : ''; ?>
                        <th>Date</th>
                        <th>Serial</th>
                        <?php echo $print_type == 'lg' ? '<th>User</th>' : ''; ?>
                        <?php echo $print_type == 'lg' ? '<th>Roz#</th>' : ''; ?>
                        <th>Name</th>
                        <th>No</th>
                        <th>Details</th>
                        <th>Dr.</th>
                        <th>Cr.</th>
                        <th>/</th>
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
                            echo '<tr >';
                            echo $print_type == 'lg' ? '<td>' . branchName($datum['branch_id']) . '</td>' : '';
                            echo '<td class="text-nowrap">' . my_date($datum["r_date"]) . '</td>';
                            echo '<td>' . $datum['r_id'] . '-' . $datum['branch_serial'] . '</td>';
                            echo $print_type == 'lg' ? '<td>' . $datum['username'] . '</td>' : '';
                            echo $print_type == 'lg' ? '<td>' . $datum['roznamcha_no'] . '</td>' : '';
                            echo '<td>' . $datum["r_name"] . '</td>';
                            echo '<td>' . $datum['r_no'] . '</td>';
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
                            echo '<td> ' . ucfirst($datum['dr_cr']) . '.</td > ';
                            echo '<td class="bold"> ' . $balance . '</td > ';
                            echo '</tr> ';
                            $numRows++;
                        }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" id="rows" value="<?php echo $numRows; ?>">
        <input type="hidden" id="dr_total" value="<?php echo $dr_total; ?>">
        <input type="hidden" id="cr_total" value="<?php echo $cr_total; ?>">
    </div>
    <div class="d-print-none shadow-lg position-fixed  start-0" style="top: 5%">
        <div class="list-group rounded-0">
            <a href="../ledger?back-khaata-no=<?php echo $khaata['khaata_no']; ?>"
               class="list-group-item list-group-item-secondary p-1"><i
                        class="fa fa-arrow-left"></i>
                Back</a>
            <a onclick="window.print();" href="#." class="list-group-item list-group-item-secondary p-1"><i
                        class="fa fa-print"></i> Print</a>
        </div>
        <div class="my-2">
            <?php $print_array = array(array('Full Print', 'lg'), array('Small Print', 'sm')); ?>
            <select class="form-select form-select-sm" id="print_type" name="print_type">
                <?php foreach ($print_array as $item) {
                    $sel = $item[1] == $print_type ? 'selected' : '';
                    echo '<option ' . $sel . ' value="' . $item[1] . '">' . ucfirst($item[0]) . '</option>';
                } ?>
            </select>
            <script>
                document.querySelector('#print_type').addEventListener('change', function () {
                    window.location.href = '<?php echo $this_url; ?>&print_type=' + this.value;
                });
            </script>
        </div>
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