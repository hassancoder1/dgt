<?php if (isset($_GET['exp_id']) && ($_GET['exp_id'] > 0)) {
    require("../connection.php");
    $exp_id = mysqli_real_escape_string($connect, $_GET['exp_id']);
    $ddd = fetch('expenses', array('id' => $exp_id));
    $expense = mysqli_fetch_assoc($ddd);
    $backURL = '../daily-expense-add?exp_id=' . $exp_id;
    $json = json_decode($expense['khaata_exp']);
    if (!empty($json)) {
        $jmaa_khaata = khaataSingle($json->jmaa_khaata_id);
        $bnaam_khaata = khaataSingle($json->bnaam_khaata_id);
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="Damaan Impex">
        <meta name="author" content="Asmatullah">
        <meta name="keywords" content="Asmatullah Trading, dubai dry fruit">
        <title>Daily Expense-Bill#<?php echo $exp_id . '_' . date('Y_m_d-H_i_s'); ?></title>
        <link rel="preconnect" href="https://fonts.googleapis.com/">
        <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/custom.css">
        <link rel="shortcut icon" href="../assets/images/logo.png"/>
        <link rel="stylesheet" href="../assets/css/icons.min.css">
        <link href="../assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>
        <style>
            body {
                background: transparent;
            }

            #main {
                background-image: url("bg.png");
                background-position: center;
                background-size: contain;
                background-repeat: no-repeat;
                height: 110%;

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
                font-size: 14px;
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
    <img src="bg-logo.png"
         style="opacity:1; position: absolute; width:50%; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <section class="">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-12">
                    <div class="px-0 position-relative" style="min-height:1000px; position:absolute; z-index:444;">
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
                                <h2 class="mt-4 fw-bold text-nowrap">Expense</h2>
                                <div class="mt-5">
                                    <h5 class="fw-bold">
                                        Bill No: <?php echo $exp_id; ?><br>
                                        Expense Date: <?php echo date('d M Y', strtotime($expense['exp_date'])); ?>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between my-3">
                            <div>
                                <h5 class="mb-2 fw-bold">Dr. A/c.</h5>
                                <h6 class="small">
                                    <span class="fw-bold text-uppercase">Account#: </span><?php echo $json->jmaa_khaata_no;?><br>
                                    <span class="fw-bold text-uppercase">Account Name: </span> <?php echo $jmaa_khaata['khaata_name'];?><br>
                                    <span class="fw-bold text-uppercase">Company: </span> <?php echo $jmaa_khaata['comp_name'];?><br>
                                    <span class="fw-bold text-uppercase">City: </span> <?php echo $jmaa_khaata['city'];?>
                                </h6>
                            </div>
                            <div class="text-end-">
                                <h5 class="mb-2 fw-bold">Cr. A/c.</h5>
                                <h6 class="small">
                                    <span class="fw-bold text-uppercase">Account#: </span><?php echo $json->bnaam_khaata_no;?><br>
                                    <span class="fw-bold text-uppercase">Account Name: </span> <?php echo $bnaam_khaata['khaata_name'];?><br>
                                    <span class="fw-bold text-uppercase">Company: </span> <?php echo $bnaam_khaata['comp_name'];?><br>
                                    <span class="fw-bold text-uppercase">City: </span> <?php echo $bnaam_khaata['city'];?>
                                </h6>
                            </div>

                        </div>
                        <hr>
                        <div class="d-flex align-items-center justify-content-between mt-4 ">
                            <h5 class="">ROZNAMCHA#:
                                <span class="text-decoration-underline"><?php echo $expense['roznamcha_no']; ?></span>
                            </h5>
                            <h5>ENTRY NAME:
                                <span class="text-decoration-underline"><?php echo strtoupper($expense['entry_name']); ?></span>
                            </h5>
                        </div>
                        <table class="table table-bordered">
                            <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th style="width: 70%">GOODS DETAILS</th>
                                <th>AED</th>
                                <th>ATTACHMENT</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $total_expenses = 0;
                            $expense_details = fetch('expense_details', array('expense_id' => $exp_id));
                            if (mysqli_num_rows($expense_details) > 0) {
                                $no = 1;
                                while ($expD = mysqli_fetch_assoc($expense_details)) {
                                    $exp_details_id = $expD['id'];
                                    $total_expenses += $expD['amount']; ?>
                                    <tr>
                                        <td><?php echo $no; ?></td>
                                        <td><?php echo strtoupper($expD['goods']); ?></td>
                                        <td><?php echo $expD['amount']; ?></td>
                                        <td>
                                            <?php $attachments = fetch('attachments', array('source_id' => $exp_details_id, 'source_name' => 'expense_details'));
                                            if (mysqli_num_rows($attachments) > 0) {
                                                echo '<i class="fa fa-check-double text-success"></i> YES';
                                            }else{
                                                echo '<i class="fa fa-window-close text-danger"></i> NO';
                                            }?>
                                        </td>
                                    </tr>
                                    <?php $no++;
                                }
                                echo '<tr class="bg-soft-warning"><th></th><th class="text-end">Total</th><th class="text-danger">' . $total_expenses . '</th><th></th></tr>';
                            } else {
                                echo '<tr class="text-center"><th colspan="3">No records found under Bill#' . $exp_id . '</th></tr>';
                            } ?>
                            </tbody>
                        </table>
                        <div class="" style="/*width:100%;*/  position:absolute; bottom:0; ">
                            <div class="row mb-4">
                                <div class="col-7">
                                    <p><b>Signature</b></p>
                                </div>
                                <div class="col-7">
                                    <div class="border-top border-2 border-dark"></div>
                                </div>
                            </div>
                            <img src="pdf-footer.png" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="../assets/tooltip/tooltip.min.js"></script>
    <div class="sticky-social d-print-none">
        <ul class="social">
            <li class="bg-dark" data-tooltip="Go Back" data-tooltip-position="right">
                <a href="<?php echo $backURL; ?>"><i class="fa fa-long-arrow-alt-left"></i></a>
            </li>
            <li class="facebook" title="PDF Print">
                <a class="cursor-pointer" onclick="window.print();"><i class="fa fa-print"></i></a>
            </li>
        </ul>
    </div>
    </body>
    </html>
<?php } else {
    echo '<script>window.location.href="../";</script>';
} ?>

