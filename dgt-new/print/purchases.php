<?php $url_index = '../';
if (isset($_GET['secret']) && base64_decode($_GET['secret']) == "powered-by-upsol") {
    require("../connection.php");
    global $connect;
$remove = $size = $brand = $goods_name = $start_print = $end_print = $is_transferred = $s_khaata_id = '';
$sql = "SELECT * FROM `transactions` WHERE p_s='p'";

$conditions = []; // Store all conditions here
// Handle other conditions if filters are applied
$is_search = true;
if ($_GET) {
    $is_search = true;

    // Filter by goods_name
    if (isset($_GET['goods_name']) && !empty($_GET['goods_name'])) {
        $goods_name = mysqli_real_escape_string($connect, $_GET['goods_name']);
    }

    // Filter by start date
    if (isset($_GET['start']) && !empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $conditions[] = "_date >= '$start_print'";
    }

    // Filter by end date
    if (isset($_GET['end']) && !empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $conditions[] = "_date <= '$end_print'";
    }

    if (isset($_GET['is_transferred']) && $_GET['is_transferred'] !== '') {
        $is_transferred = mysqli_real_escape_string($connect, $_GET['is_transferred']);
        if ($is_transferred === '1') {
            $conditions[] = "locked = '1'"; // Only inactive records
        } elseif ($is_transferred === '0') {
            $conditions[] = "locked = '0'"; // Only active records
        }
    }

    // Filter by s_khaata_id
    if (isset($_GET['s_khaata_id']) && !empty($_GET['s_khaata_id'])) {
        $s_khaata_id = mysqli_real_escape_string($connect, $_GET['s_khaata_id']);
    }

    
}

$sql = "SELECT * FROM `transactions` WHERE p_s='p'";


$sql .= " ORDER BY id DESC"; // Add order clause at the end
    $records = mysqli_query($connect, $sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo 'Purchase_' . my_date(date('Y-m-d')); ?> </title>
        <meta name="description" content="Owner of DGT.llc">
        <meta name="author" content="Asmatullah Abdullah">
        <meta name="keywords" content="dgt, uae, damaan general trading, damaan">
        <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/custom.css" rel="stylesheet">
        <!-- <link href="../assets/css/virtual-select.min.css" rel="stylesheet"> -->
        <!-- <script src="../assets/fa/fontawesome.js" crossorigin="anonymous"></script> -->
        <link rel="shortcut icon" href="../assets/images/favicon.jpg" />
        <script src="https://cdn.jsdelivr.net/npm/html-to-docx@1.8.0/dist/html-to-docx.umd.min.js"></script>
        <style>
            .sidebar {
                height: 100vh;
                width: 180px;
                position: fixed;
                top: 0;
                left: 0;
                background-color: transparent;
                padding: 20px;
                z-index: 1000;
            }

            .sidebar .nav-item {
                margin-bottom: 15px;
            }

            .main-content {
                margin-left: 270px;
                padding: 20px;
            }
        </style>
    </head>

    <body onbeforeprint="togglePrintControls()" onafterprint="togglePrintControls()">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column" id="printControls">
            <div class="fs-5 fw-bold text-uppercase text-dark mb-3">Print Purchases</div>
            <div class="d-flex flex-column gap-3">
                <!-- Add the 'Back' button and other controls -->
                <div class="nav-item">
                    <?= addNew('../purchases', 'Back', 'btn-sm btn-warning', 'fa-arrow-left'); ?>
                </div>
                <div class="nav-item">
                    <button class="btn btn-primary btn-sm" onclick="downloadAsWord()">
                        <i class="fa fa-file-word-o"></i> Word
                    </button>
                </div>
                <div class="nav-item">
                    <a href="/compose" class="btn btn-warning btn-sm">
                        <i class="fa fa-print"></i> Mail
                    </a>
                </div>
                <div class="nav-item">
                    <a onclick="window.print();" href="#." class="btn btn-success btn-sm">
                        <i class="fa fa-print"></i> Print
                    </a>
                </div>
            </div>
        </div>
    <div class="container-fluid my-3" style="margin-left:200px;">
        <div class="">
            <div class="col-lg-9">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <img src="../assets/images/logo.png" alt="logo" class="img-fluid" width="100">
                    <!-- <div><b>Branch: </b><?php echo $branchName; ?></div>
                    <div><b>Entries: </b><span id="rows_span"></span></div>
                    <div><b>Dr. </b><span id="dr_total_span"></span></div>
                    <div><b>Cr. </b><span id="cr_total_span"></span></div>
                    <div><b>Bal. </b><span id="bal_span"></span></div> -->
                    <div class="text-end">
                        <h3 class="fw-bold mb-0">Purchases</h3>
                        <!-- <div>
                            <b>Date: </b><?php echo my_date($start_date) . ' to ' . my_date($end_date); ?>
                        </div> -->
                    </div>
                </div>
                <table class="table table-bordered table-hover table-sm fix-head-table mb-0" id="purchasesTable">
                        <thead>
                        <tr class="text-nowrap">
                            <th>Bill#</th>
                            <th>Type</th>
                            <th>BR.</th>
                            <th>Date</th>
                            <th>A/c</th>
                            <th>A/c Name</th>
                            <th>Goods Name</th>
                            <th>Qty</th>
                            <th>KGs</th>
                            <th>AMOUNT</th>
                            <th>PAYMENT DETAILS</th>
                            <th>COUNTRY</th>
                            <th>ROAD</th>
                            <th>LOADING COUNTRY | DATE</th>
                            <th>RECEIVING COUNTRY | DATE</th>
                            <th>DOCS</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $purchases = mysqli_query($connect, $sql);
                        $row_count = $p_qty_total = $p_kgs_total = 0;
                        while ($purchase = mysqli_fetch_assoc($purchases)) {
                            $id = $purchase['id'];
                            $_fields_single = transactionSingle($id);

                            $is_doc = $purchase['is_doc'];
                            $locked = $purchase['locked'];

                            $cntrs = purchaseSpecificData($id, 'purchase_rows');
                            $totals = purchaseSpecificData($id, 'product_details');
                            $Goods = empty($_fields_single['items'][0]) ? '' : goodsName($_fields_single['items'][0]['goods_id']);

                            $Size = $cntrs > 0 ? '<b>SIZE. </b>' . $totals['Size'][0] . '<br>' : '';
                            $Brand = $cntrs > 0 ? '<b>BRNAD. </b>' . $totals['Brand'][0] . ' ' : '';

                            $Qty = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_qty_no'];
                            $KGs = empty($_fields_single['items_sum']) ? '' : $_fields_single['items_sum']['sum_total_kgs'];

                            $sea_road = '';
                            $sea_road_array = json_decode(getSeaRoadArray($id));
                            $_fields_sr = ['l_country' => '', 'l_date' => '', 'r_country' => '', 'r_date' => ''];
                            if (!empty($sea_road_array)) {
                                $sea_road = $sea_road_array->sea_road ?? '';
                                if ($sea_road == 'sea') {
                                    $_fields_sr = ['l_country' => $sea_road_array->l_country, 'l_date' => $sea_road_array->l_date, 'r_country' => $sea_road_array->r_country, 'r_date' => $sea_road_array->r_date];
                                }
                                if ($sea_road == 'road') {
                                    $_fields_sr = ['l_country' => $sea_road_array->l_country_road, 'l_date' => $sea_road_array->l_date_road, 'r_country' => $sea_road_array->r_country_road, 'r_date' => $sea_road_array->r_date_road];
                                }
                            }

                            if ($is_search) {
                                // if ($start != '') {
                                //     if ($purchase['p_date'] < $start) continue;
                                // }
                                // if ($end != '') {
                                //     if ($purchase['p_date'] > $end) continue;
                                // }
                                $GoodsKaNaam = $cntrs > 0 ? $totals['Goods'][0] : '';
                                if ($goods_name != '') {
                                    if ($goods_name != $GoodsKaNaam) continue;
                                }
                                // if ($size != '') {
                                //     if ($size != $totals['Size'][0]) continue;
                                // }
                                // if ($brand != '') {
                                //     if ($brand != $totals['Brand'][0]) continue;
                                // }

                                if ($is_transferred != '') {
                                    if ($is_transferred == '1') {
                                        if ($locked == 0) continue;
                                    }
                                    if ($is_transferred == '0') {
                                        if ($locked == 1) continue;
                                    }
                                }
                                // if ($s_khaata_id != '') {
                                //     if ($s_khaata_id != $purchase['s_khaata_id']) continue;
                                // }
                            }
                            $p_qty_total += !empty($totals['Qty']) ? $totals['Qty'] : 0;
                            $p_kgs_total += !empty($totals['KGs']) ? $totals['KGs'] : 0;
                            $rowColor = '';
                            if ($locked == 0) {
                                $rowColor = $is_doc == 0 ? ' text-danger ' : ' text-warning ';
                            } ?>
                            <tr class="text-nowrap">
                                <td class="pointer <?php echo $rowColor; ?>" onclick="viewPurchase(<?php echo $id; ?>)"
                                    data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                                    <?php echo '<b>P#</b>' . $id; echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['type']); //badge(strtoupper($_fields_single['type']), $purchase['type'] == 'booking' ? 'dark' : 'danger'); ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo branchName($_fields_single['branch_id']); ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo my_date($_fields_single['_date']);; ?></td>
                                <td class="s_khaata_id_row <?php echo $rowColor; ?>"><?php echo strtoupper($_fields_single['cr_acc']); ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo $_fields_single['cr_acc_name']; ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo $Goods; ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo $Qty; ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo $KGs; ?></td>
                                <td class="<?php echo $rowColor; ?>">
                                    <?php if ($cntrs > 0) {
                                        echo $totals['Final'] . '<sub>' . $totals['curr2'] . '</sub>';
                                    } ?>
                                </td>
                                <td class="<?php echo $rowColor; ?> px-2 payment_type"><?= isset($_fields_single['payment_details']->full_advance) ? ucwords($_fields_single['payment_details']->full_advance) : "No Payment Details Available"; ?></td>
                                <td class="<?php echo $rowColor; ?>"><?php echo $purchase['country']; ?></td>
                                <?php
                                if ($sea_road == '') {
                                    echo '<td class="<?php echo $rowColor; ?>" colspan="3"></td>';
                                } else {
                                    echo '<td class="' . $rowColor . '">' . $sea_road . '</td>';
                                    echo '<td class="' . $rowColor . '">' . $_fields_sr['l_country'] . ' ' . my_date($_fields_sr['l_date']) . '</td>';
                                    echo '<td class="' . $rowColor . '">' . $_fields_sr['r_country'] . ' ' . my_date($_fields_sr['r_date']) . '</td>';
                                }
                                ?>
                                <td class="<?php echo $rowColor; ?>">
                                    <?php if ($is_doc == 1) {
                                        $atts = getAttachments($id, 'purchase_contract');
                                        foreach ($atts as $att) {
                                            echo '<a href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank"><i class="fa fa-download text-success"></i></a>';
                                        }
                                    } ?>
                                </td>
                            </tr>
                            <?php $row_count++;
                        } ?>
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
    
    <!-- <div class="list-group d-print-none shadow-lg position-fixed rounded-0 start-0" style="top: 5%">
        <a href="../roznamcha" class="list-group-item list-group-item-secondary p-1"><i class="fa fa-arrow-left"></i>
            Back</a>
        <a onclick="window.print();" href="#." class="list-group-item list-group-item-secondary p-1"><i
                    class="fa fa-print"></i> Print</a>
    </div> -->

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
    <script>
    $(document).ready(function() {
    // Function to get the query parameter value
    function getQueryParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    if(getQueryParameter('s_khaata_id')){
        var s_khaata_id = getQueryParameter('s_khaata_id').toUpperCase();
    $('td.s_khaata_id_row').each(function() {
        var cellText = $(this).text().trim();
        if (cellText !== s_khaata_id && s_khaata_id !== '') {
            $(this).closest('tr').hide();
        }
    });
}
var paymentType = getQueryParameter('payment_type').toLowerCase();
    let paymentTypeCell;
    $('td.payment_type').each(function() {
        paymentTypeCell = $(this).text().trim().toLowerCase();
        if (paymentTypeCell !== paymentType) {
            $(this).closest('tr').hide();
        }
    });
});
async function downloadAsWord() {
                // Select the element you want to convert to DOCX (use the whole document or a specific part)
                var element = document.documentElement; // This will take the whole HTML document

                // Define options (you can customize these for headers, footers, margins, etc.)
                var options = {
                    orientation: 'portrait', // Can also be 'landscape'
                    margins: {
                        top: 720, // 1 inch = 720 twips (1/20th of a point)
                        right: 720,
                        bottom: 720,
                        left: 720,
                    },
                    title: 'Document Title',
                };

                try {
                    var docxBlob = await htmlToDocx(element, options);

                    var blobUrl = URL.createObjectURL(docxBlob);
                    var a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = 'document.docx'; // Specify the name of the downloaded file
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } catch (error) {
                    console.error('Error generating DOCX:', error);
                }
            }

function togglePrintControls() {
    let printControls = document.getElementById('printControls');
    
    if (printControls.classList.contains('d-flex')) {
        printControls.classList.remove('d-flex');
        printControls.classList.add('d-none');
    } else {
        printControls.classList.remove('d-none');
        printControls.classList.add('d-flex');
    }
}

</script>
    </body>
    </html>
<?php } else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
} ?>