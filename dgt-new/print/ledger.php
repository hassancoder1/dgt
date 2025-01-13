<?php $url_index = '../';
if (
    isset($_GET['secret']) && base64_decode($_GET['secret']) == "powered-by-upsol"
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
    $dates = $start_date != '' && $end_date != '' ? '<div class="text-muted"><i class="fas fa-calendar-alt me-2"></i>' . my_date($start_date) . ' - ' . my_date($end_date) . '</div>' : '';
    $branchName = $branch_id > 0 ? branchName($branch_id) : " All ";
    $records = mysqli_query($connect, $sql);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo 'Ledger_' . my_date(date('Y-m-d')); ?></title>
        <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/virtual-select.min.css" rel="stylesheet">
        <link href="../assets/fa/css/all.min.css" rel="stylesheet" />
        <link rel="shortcut icon" href="../assets/images/favicon.jpg" />
        <style>
            body {
                background-color: #f8f9fa;
                font-size: 12px;
            }

            .ledger-header {
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin-bottom: 20px;
            }

            .table {
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .table thead th {
                background-color: #f8f9fa;
                border-bottom: 2px solid #dee2e6;
            }

            .table-hover tbody tr:hover {
                background-color: #f8f9fa;
            }

            .stats-card {
                background: white;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
                /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
            }

            .print-controls {
                background: white;
                border-radius: 8px;
                padding: 15px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            @media print {
                .d-print-none {
                    display: none !important;
                }

                body {
                    background-color: white;
                }

                .container {
                    width: 100vw !important;
                    max-width: 100vw !important;
                    padding: 0 !important;
                    margin: 0 auto;
                }
            }
        </style>
    </head>

    <body class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 bg-white border">
                    <div class="px-3 py-2 rounded">
                        <img src="../assets/images/logo.png" alt="logo" class="img-fluid my-3 mx-auto" width="120">
                        <div class="row">
                            <div class="col-4">
                                <div class="border px-3 py-2 rounded">
                                    <h6 class="mb-2">Branch Details</h6>
                                    <div><strong>Branch:</strong> <?php echo $branchName; ?></div>
                                    <div><strong>Category:</strong> <?php echo catName($khaata['cat_id']); ?></div>
                                    <div><strong>Entries:</strong> <span id="rows_span" class="badge bg-primary"></span></div>
                                    <?php echo $dates; ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border px-3 py-2 rounded">
                                    <h6 class="mb-2">Financial Summary</h6>
                                    <div><strong>Dr:</strong> <span id="dr_total_span" class="text-success"></span></div>
                                    <div><strong>Cr:</strong> <span id="cr_total_span" class="text-danger"></span></div>
                                    <div><strong>Balance:</strong> <span class="fw-bold" id="bal_span"></span></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border px-3 py-2 rounded">
                                    <h6 class="mb-2">Account Details</h6>
                                    <div><strong>A/C:</strong> <?php echo $khaata['khaata_no']; ?></div>
                                    <div><strong>Name:</strong> <?php echo $khaata['khaata_name']; ?></div>
                                    <div><strong>Phone:</strong> <?php echo $khaata['phone']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <table class="table table-bordered my-3">
                        <thead>
                            <tr class="border">
                                <?php if ($print_type == 'lg'): ?>
                                    <th class="border"><i class="fas fa-building me-1"></i>Branch</th>
                                <?php endif; ?>
                                <th class="border"><i class="fas fa-calendar me-1"></i>Date</th>
                                <th class="border"><i class="fas fa-hashtag me-1"></i>Serial</th>
                                <?php if ($print_type == 'lg'): ?>
                                    <th class="border"><i class="fas fa-user me-1"></i>User</th>
                                    <th class="border"><i class="fas fa-file-alt me-1"></i>Roz#</th>
                                <?php endif; ?>
                                <th class="border"><i class="fas fa-user me-1"></i>Name</th>
                                <th class="border"><i class="fas fa-list-ol me-1"></i>No</th>
                                <th class="border"><i class="fas fa-info-circle me-1"></i>Details</th>
                                <th class="border text-end"><i class="fas fa-arrow-up me-1"></i>Dr.</th>
                                <th class="border text-end"><i class="fas fa-arrow-down me-1"></i>Cr.</th>
                                <th class="border text-center">/</th>
                                <th class="border text-end"><i class="fas fa-balance-scale me-1"></i>Balance</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php
                            $data = mysqli_query($connect, $sql);
                            $numRows = $dr_total = $cr_total = 0;

                            if (mysqli_num_rows($data) > 0):
                                $jmaa = $bnaam = $balance = 0;
                                while ($datum = mysqli_fetch_assoc($data)):
                                    $dr = $cr = 0;
                            ?>
                                    <tr class="border">
                                        <?php if ($print_type == 'lg'): ?>
                                            <td class="border"><?php echo branchName($datum['branch_id']); ?></td>
                                        <?php endif; ?>
                                        <td class="border text-nowrap"><?php echo my_date($datum["r_date"]); ?></td>
                                        <td class="border"><?php echo $datum['r_id'] . '-' . $datum['branch_serial']; ?></td>
                                        <?php if ($print_type == 'lg'): ?>
                                            <td class="border"><?php echo $datum['username']; ?></td>
                                            <td class="border"><?php echo $datum['roznamcha_no']; ?></td>
                                        <?php endif; ?>
                                        <td class="border"><?php echo $datum["r_name"]; ?></td>
                                        <td class="border"><?php echo $datum['r_no']; ?></td>
                                        <td class="border"><?php echo $datum["details"]; ?></td>
                                        <?php
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
                                        ?>
                                        <td class="border text-end fw-bold <?php echo $dr > 0 ? 'text-success' : ''; ?>"><?php echo $dr > 0 ? number_format($dr, 2) : ''; ?></td>
                                        <td class="border text-end fw-bold <?php echo $cr > 0 ? 'text-danger' : ''; ?>"><?php echo $cr > 0 ? number_format($cr, 2) : ''; ?></td>
                                        <td class="border text-center"><?php echo ucfirst($datum['dr_cr']) . '.'; ?></td>
                                        <td class="border text-end fw-bold <?php echo $balance >= 0 ? 'text-success' : 'text-danger'; ?>"><?php echo number_format($balance, 2); ?></td>
                                    </tr>
                            <?php
                                    $numRows++;
                                endwhile;
                            endif;
                            ?>
                            <!-- Summary Row -->
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="border fw-bold">
                                <?php
                                $colspan = $print_type == 'lg' ? 8 : 5;
                                ?>
                                <td colspan="<?php echo $colspan; ?>" class="border">Totals</td>
                                <td class="border text-end text-success"><?php echo number_format($dr_total, 2); ?></td>
                                <td class="border text-end text-danger"><?php echo number_format($cr_total, 2); ?></td>
                                <td class="border"></td>
                                <td class="border text-end <?php echo $balance ?? 0 >= 0 ? 'text-success' : 'text-danger'; ?>"><?php echo number_format($balance ?? 0, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <input type="hidden" id="rows" value="<?php echo $numRows; ?>">
            <input type="hidden" id="dr_total" value="<?php echo $dr_total; ?>">
            <input type="hidden" id="cr_total" value="<?php echo $cr_total; ?>">
        </div>

        <div class="d-print-none position-fixed start-0 top-50 translate-middle-y ms-3">
            <div class="print-controls">
                <div class="d-grid gap-2">
                    <a href="../ledger?back-khaata-no=<?php echo $khaata['khaata_no']; ?>"
                        class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    <button onclick="window.print();" class="btn btn-primary btn-sm">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                    <select class="form-select form-select-sm mt-2" id="print_type" name="print_type">
                        <?php
                        $print_array = array(array('Full Print', 'lg'), array('Small Print', 'sm'));
                        foreach ($print_array as $item) {
                            $sel = $item[1] == $print_type ? 'selected' : '';
                            echo '<option ' . $sel . ' value="' . $item[1] . '">' . ucfirst($item[0]) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery-3.7.1.min.js"></script>
        <script src="../assets/bs/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/virtual-select.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#rows_span").text($("#rows").val());

                const dr_total = parseFloat($("#dr_total").val());
                const cr_total = parseFloat($("#cr_total").val());
                const bal = dr_total - cr_total;

                $("#dr_total_span").text(dr_total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                $("#cr_total_span").text(cr_total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                $("#bal_span").text(bal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                if (bal > 0) {
                    $("#bal_span").addClass('text-success');
                } else if (bal < 0) {
                    $("#bal_span").addClass('text-danger');
                }

                $('#print_type').on('change', function() {
                    window.location.href = '<?php echo $this_url; ?>&print_type=' + this.value;
                });
            });
        </script>
    </body>

    </html>
<?php } else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
} ?>