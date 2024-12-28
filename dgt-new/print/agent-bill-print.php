<?php
$page_title = 'Agent Bill Print';
$pageURL = 'Agent Bill Print';
require("../connection.php");
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$P = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM general_loading WHERE id='$id'ORDER BY created_at ASC"));
$PA = json_decode($P['agent_details'], true);
$result = fetch('agent_payments', array('bl_no' => $P['bl_no']));
$rows = [];

while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
$rowCount = count($rows);
$FR = $rowCount > 0 ? $rows[0] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGENT BILL</title>
    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
    echo "</style>";
    ?>
    <style>
        .invoice-container {
            background-color: #fff;
            padding: 20px;
        }

        .header-logo {
            width: 120px;
        }

        .company-info {
            font-size: 14px;
            color: #6c757d;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 16px;
            border-bottom: 1px solid #6c757d;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }

        .details-list {
            font-size: 14px;
            margin-bottom: 20px;
        }

        .details-list span {
            font-weight: bold;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .custom-table th {
            background-color: #343a40;
            color: #fff;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #dee2e6;
        }

        .custom-table td {
            text-align: center;
            padding: 10px;
            font-size: 13px;
            border: 1px solid #dee2e6;
        }

        .footer-summary {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
            color: #495057;
        }

        .footer-summary div {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="row mb-5">
            <div class="col-2"></div>
            <div class="col-8 text-center row">
                <img src="../assets/images/logo.png" alt="Company Logo" class="d-block w-25 mx-auto mb-1">
                <div><strong style="font-size: 20px;">DAMAAN GENERAL TRADING LLC</strong><br>
                    <span style="font-size:12px; display:block;">
                        OFFICE NO 201 AL HABTOOR BUILDING AL RAS AREA, DEIRA, DUBAI, UAE </span>
                    <span style="font-size:12px; display:block;">MOBILE: +971544816664 | OFFICE: +97142278608 | EMAIL: dgtllc@dgt.llc</span>
                    <span style="font-size:12px; display:block;">License: 1099020 | VAT # 1099020234235323</span>
                </div>
            </div>
            <div class="col-2"></div>
        </div>

        <div class="row mt-5 mb-2">
            <div class="col-5">
                <div class="section-title">Agent Details</div>
                <small class="d-block"><b>Acc No: </b><?= $PA['ag_acc_no']; ?></small>
                <small class="d-block"><b>Name: </b><?= $PA['ag_name']; ?></small>
                <small class="d-block"><b>B/L No: </b><?= $P['bl_no']; ?></small>
            </div>
            <div class="col-2"></div>
            <div class="col-5">
                <div class="section-title">Bill Info</div>
                <small class="d-block"><b>Sr# </b><?= $FR['sr_no']; ?> / <b>Bill No# </b><?= $FR['bill_no']; ?></small>
                <small class="d-block"><b>Purchase Date: </b><?= $P['p_date']; ?></small>
                <small class="d-block"><b>Type: </b><?= ucfirst($P['p_type']); ?></small>
            </div>
        </div>

        <div class="section-title mt-4">Bill Details</div>
        <p><?= $FR['bill_details'] ?></p>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Sr #</th>
                    <th>Details</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Tax %</th>
                    <th>Tax Amount</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_amount = $total_tax_amount = $total_grand = 0;
                foreach ($rows as $one) {
                    $one['tax_percentage'] = str_replace('%', '', $one['tax_percentage']);
                ?>
                    <tr>
                        <td><?= $one['sr_no']; ?></td>
                        <td><?= $one['details']; ?></td>
                        <td><?= $one['quantity']; ?></td>
                        <td><?= $one['rate']; ?></td>
                        <td><?= $one['total']; ?></td>
                        <td><?= $one['tax_percentage']; ?>%</td>
                        <td><?= round($one['tax_amount'], 2); ?></td>
                        <td><?= round($one['grand_total'], 2); ?></td>
                    </tr>
                <?php
                    $total_amount += (float)$one['total'];
                    $total_tax_amount += (float)$one['tax_amount'];
                    $total_grand += (float)$one['grand_total'];
                }
                ?>
            </tbody>
        </table>
        <?php
        $TI = json_decode($FR['transfer_details'], true)['transfer_info'] ?? [];
        if (isset($_GET['carry-print']) && !empty($TI)) {
            $rozQ = fetch('roznamchaas', array('r_type' => 'Agent Bill', 'transfered_from_id' => $FR['id'], 'transfered_from' => 'agent_payments'));
            if (mysqli_num_rows($rozQ) > 0) { ?>
                <table class="table table-sm table-bordered mt-2">
                    <thead>
                        <tr>
                            <th>Sr#</th>
                            <th>Date</th>
                            <th>A/c#</th>
                            <th>Roz.#</th>
                            <th>Name</th>
                            <th>No</th>
                            <th>Details</th>
                            <th>Dr.</th>
                            <th>Cr.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                            $dr = $cr = 0; ?>
                            <tr>
                                <td>
                                    <?= $roz['r_id'] . '-' . $roz['branch_serial']; ?>
                                </td>
                                <td><?php echo $roz['r_date']; ?></td>
                                <td>
                                    <a href="../ledger?back-khaata-no=<?php echo $roz['khaata_no']; ?>"
                                        target="_blank"><?php echo $roz['khaata_no']; ?></a>
                                </td>
                                <td><?php echo $roz['roznamcha_no']; ?></td>
                                <td class="small"><?php echo $roz['r_name']; ?></td>
                                <td><?php echo $roz['r_no']; ?></td>
                                <td class="small"><?php echo $roz['details']; ?></td>
                                <?php if ($roz['dr_cr'] == "dr") {
                                    $dr = $roz['amount'];
                                } else {
                                    $cr = $roz['amount'];
                                } ?>
                                <td class="text-success"><?php echo $dr; ?></td>
                                <td class="text-danger"><?php echo $cr; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
        <?php }
        } ?>
        <div class="footer-summary">
            <div><strong>Total Amount:</strong> <?= round($total_amount, 2); ?></div>
            <div><strong>Total VAT Amt:</strong> <?= round($total_tax_amount, 2); ?></div>
            <div><strong>Grand Total:</strong> <span class="text-success fw-bold"><?= round($total_grand, 2); ?></span></div>
        </div>
    </div>
</body>

</html>