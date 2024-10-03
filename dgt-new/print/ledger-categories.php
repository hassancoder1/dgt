<?php $url_index = '../';
if (isset($_GET['secret']) && base64_decode($_GET['secret']) == "powered-by-upsol"
    && isset($_GET['start_date']) && isset($_GET['end_date'])
    && isset($_GET['branch_id']) && isset($_GET['cat_ids'])
    && isset($_GET['dr_cr'])
) {
    require("../connection.php");
    $print_type = isset($_GET['print_type']) ? mysqli_real_escape_string($connect, $_GET['print_type']) : 'sm';
    $start_date = mysqli_real_escape_string($connect, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($connect, $_GET['end_date']);
    $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
    $dr_cr = mysqli_real_escape_string($connect, $_GET['dr_cr']);
    $this_url = 'ledger-categories?secret=' . $_GET['secret'] . '&start_date=' . $start_date . '&end_date=' . $end_date . '&cat_ids=' . $_GET['cat_ids'] . '&branch_id=' . $branch_id . '&dr_cr=' . $dr_cr . '&print_type=' . $print_type;

    $sql = "SELECT DISTINCT khaata_id FROM `roznamchaas` WHERE r_id > 0 ";
    if ($start_date != '' && $end_date != '') {
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date' ";
    }
    if (!empty($_GET['cat_ids'][0])) {
        $cat_ids = $_GET['cat_ids'];
        $cat_ids = explode(',', $cat_ids[0]);
        $in = "(" . implode(',', $cat_ids) . ")";
        $sql .= " AND cat_id IN " . $in;
    }
    $sql .= $branch_id > 0 ? " AND khaata_branch_id = " . "'$branch_id'" . " " : " ";

    $dr_cr == 'dr';
    $isJB = $dr_cr != '';


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
        <title><?php echo 'Ledger_Categories_' . my_date(date('Y-m-d')); ?> </title>
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
                <style>
                    .tr-danger td {
                        color: red;
                    }
                </style>
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <img src="../assets/images/logo.png" alt="logo" class="img-fluid" width="100">
                    <div>
                        <div><b>Entries: </b><span id="rows_span"></span></div>
                        <?php echo $dates; ?>
                    </div>
                    <div>
                        <div><b>Dr. </b><span id="dr_total_span"></span></div>
                        <div><b>Cr. </b><span id="cr_total_span"></span></div>
                    </div>
                    <div><b>Balance </b><span class="fw-bold" id="bal_span"></span></div>
                    <div class="text-end">
                        <h3 class="fw-bold mb-0">Ledger Categories</h3>
                    </div>
                </div>
                <table class="table table-sm table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Last Date</th>
                        <!--<th>Br.</th>
                        <th>Cat.</th>-->
                        <th>A/c #</th>
                        <th>A/c Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <?php if ($print_type == 'lg') {
                            //echo '<th>Contact Details</th>';
                        } ?>
                        <?php //if (!$isJB) {echo '<th>Dr.</th><th>Cr.</th>';} ?>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $khaataQ = mysqli_query($connect, $sql);
                    $rows = $dr = $cr = $balance = $dr_total = $cr_total = $balance_total = 0;
                    $number = 1;
                    while ($khaata = mysqli_fetch_assoc($khaataQ)) {
                        $k_id = $khaata['khaata_id'];
                        $khaataDatum = khaataSingle($k_id);
                        $k_no = $khaataDatum['khaata_no'];
                        $dr = roznamchaAmount($k_id, 'dr');
                        $dr_total += $dr;
                        $cr = roznamchaAmount($k_id, 'cr');
                        $cr_total += $cr;
                        $balance = $dr - $cr;
                        if ($balance == 0) continue;
                        if ($isJB) {
                            if ($dr_cr == "dr") {
                                if ($balance < 0) continue;
                            } else {
                                if ($balance > 0) continue;
                            }
                        }
                        $balance_total = $dr_total - $cr_total;
                        $rows++;
                        $redGreenText = $balance > 0 ? 'text-success-' : 'text-danger';
                        $trDanger = $balance > 0 ? '' : 'tr-danger';
                        ?>
                        <tr class="<?php echo $trDanger; ?>">
                            <td><?php echo $number; ?></td>
                            <td class="text-nowrap"><?php $dd = mysqli_query($connect, "select min(r_date) as min_date ,max(r_date) as max_date from `roznamchaas` WHERE khaata_no = '$k_no'");
                                $dataa = mysqli_fetch_assoc($dd);
                                //echo '<b>Start </b>' . my_date($dataa['min_date']) . '<br>';
                                echo my_date($dataa['max_date']); ?>
                            </td>
                            <td class="text-nowrap d-none"><?php echo branchName($khaataDatum['branch_id']); ?></td>
                            <td class="d-none"><?php echo catName($khaataDatum['cat_id']); ?></td>
                            <td><?php echo $k_no . '<br>'; ?></td>
                            <td><?php echo $khaataDatum['khaata_name']; ?></td>
                            <td><?php echo $khaataDatum['phone']; ?></td>
                            <td><?php echo $khaataDatum['email']; ?></td>
                            <?php if ($print_type == 'lg') {
                                /*echo '<td>';
                                $contact_details = json_decode($khaataDatum['contact_details']);
                                if (!empty($contact_details)) {
                                    echo '<b>NAME</b>' . $contact_details->full_name;
                                    echo '<b> FATHER</b>' . $contact_details->father_name;
                                    echo '<b> Country</b>' . $contact_details->country;
                                    echo '<b> State</b>' . $contact_details->state;
                                    echo '<b> City</b>' . $contact_details->city;
                                    echo '<b> Address</b>' . $contact_details->address;
                                    echo '<b> Mobile</b>' . $contact_details->mobile;
                                    echo '<b> Phone</b>' . $contact_details->phone;
                                    echo '<b> Whatsapp</b>' . $contact_details->whatsapp;
                                }
                                echo '</td>';*/
                            } ?>
                            <?php /*if (!$isJB) {
                                echo '<td>' . round($dr) . '</td>';
                                echo '<td><span class="text-danger">' . round($cr) . '</span></td>';
                            }*/ ?>
                            <td><?php echo '<span class="fw-bold ' . $redGreenText . '">' . round($balance) . '</span>'; ?></td>
                        </tr>
                        <?php $number++;
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <input type="hidden" id="rows" value="<?php echo $rows; ?>">
        <input type="hidden" id="dr_total" value="<?php echo round($dr_total, 2); ?>">
        <input type="hidden" id="cr_total" value="<?php echo round($cr_total, 2); ?>">
        <input type="hidden" id="cr_balance" value="<?php echo round($balance_total, 2); ?>">
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
        $("#dr_total_span").text(dr_total);
        $("#cr_total_span").text(cr_total);
        var bal = $("#cr_balance").val()
        var bal_span = $("#bal_span");
        bal_span.text(bal);
        if (bal > 0) {
            bal_span.addClass('text-success');
        } else if (bal < 0) {
            bal_span.addClass('text-danger');
        }
    </script>
    </body>
    </html>
<?php } else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
} ?>