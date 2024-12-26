<?php
require_once '../connection.php';
$print_url = '';

if (!empty($_POST['container'])) {
    $container = mysqli_real_escape_string($connect, $_POST['container']);

    // Query data from general_loading and local_loading
    $query = "SELECT * FROM data_copies WHERE unique_code LIKE 'p%' AND unique_code NOT LIKE 'pl%' AND JSON_EXTRACT(ldata, '$.good.container_no') = '$container'";

    $result = mysqli_query($connect, $query);
?>

    <div class="modal-header d-flex justify-content-between bg-white align-items-center">
        <h5 class="modal-title" id="staticBackdropLabel">ContainerNo Details: ( <?= htmlspecialchars($container); ?> )</h5>
        <div class="d-flex align-items-center justify-content-end gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="<?= $print_url; ?>" target="_blank">
                            <i class="fas text-secondary fa-eye me-2"></i> Print Preview
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')">
                            <i class="fas text-secondary fa-print me-2"></i> Print
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')">
                            <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')">
                            <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')">
                            <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')">
                            <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                        </a>
                    </li>
                </ul>
            </div>
            <a href="<?= $_POST['page']; ?>" class="btn-close ms-3" aria-label="Close"></a>
        </div>
    </div>
    <div class="card mt-2">
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr class="text-nowrap">
                        <th>No.#</th>
                        <th>P | S</th>
                        <th>Goods (Name / Size / Brand / Origin)</th>
                        <th colspan="3">Total Qty</th>
                        <th colspan="3">Gross Weight</th>
                        <th colspan="3">Net Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($entry = mysqli_fetch_assoc($result)) {
                        $ldata = json_decode($entry['ldata'], true);
                        $G = $ldata['good'];

                        $total_purchased_qty = $ldata['good']['quantity_no'] ?? 0;
                        $total_purchased_kgs = $ldata['good']['gross_weight'] ?? 0;
                        $total_purchased_net_kgs = $ldata['good']['net_weight'] ?? 0;
                        $total_sold_qty = 0;
                        $total_sold_kgs = 0;
                        $total_sold_net_kgs = 0;
                    ?>
                        <tr class="table-primary">
                            <td><?= htmlspecialchars($i); ?></td>
                            <td>P# <?= decode_unique_code($entry['unique_code'], 'TID'); ?></td>
                            <td>
                                <?= goodsName(htmlspecialchars($G['goods_id'])) . ' / ' .
                                    htmlspecialchars($G['size']) . ' / ' .
                                    htmlspecialchars($G['brand']) . ' / ' .
                                    htmlspecialchars($G['origin']); ?>
                            </td>
                            <td colspan="3" class="fw-bold text-success"><?= round($total_purchased_qty); ?></td>
                            <td colspan="3" class="fw-bold text-success"><?= round($total_purchased_kgs); ?></td>
                            <td colspan="3" class="fw-bold text-success"><?= round($total_purchased_net_kgs); ?></td>
                        </tr>
                        <?php

                        // Display Sub-Entries
                        if (isset($ldata['transfer']['sold_to'])) {
                            foreach ($ldata['transfer']['sold_to'] as $sold) {
                                $soldData = explode('~', $sold);

                                // Accumulate Totals
                                $total_sold_qty += $soldData[3] ?? 0;
                                $total_sold_kgs += $soldData[5] ?? 0;
                                $total_sold_net_kgs += $soldData[6] ?? 0;

                        ?>
                                <tr>
                                    <td></td>
                                    <td>S# <?= htmlspecialchars(decode_unique_code($soldData[0], 'TID')); ?></td>
                                    <td>
                                        <?= goodsName(htmlspecialchars($G['goods_id'])) . ' / ' .
                                            htmlspecialchars($G['size']) . ' / ' .
                                            htmlspecialchars($G['brand']) . ' / ' .
                                            htmlspecialchars($G['origin']); ?>
                                    </td>
                                    <td colspan="3" class="fw-bold text-danger"><?= round($soldData[3] ?? 0); ?></td>
                                    <td colspan="3" class="fw-bold text-danger"><?= round($soldData[5] ?? 0); ?></td>
                                    <td colspan="3" class="fw-bold text-danger"><?= round($soldData[6] ?? 0); ?></td>
                                </tr>
                        <?php
                            }
                        }

                        // Calculate Remaining
                        $total_remaining_qty = $total_purchased_qty - $total_sold_qty;
                        $total_remaining_gross_weight = $total_purchased_kgs - $total_sold_kgs;
                        $total_remaining_net_weight = $total_purchased_net_kgs - $total_sold_net_kgs;

                        // Display Remaining Totals
                        ?>
                        <tr class="table-warning">
                            <td></td>
                            <td colspan="2" class="fw-bold">Remaining Totals:</td>
                            <td colspan="3" class="fw-bold text-primary"><?= round($total_remaining_qty); ?></td>
                            <td colspan="3" class="fw-bold text-primary"><?= round($total_remaining_gross_weight); ?></td>
                            <td colspan="3" class="fw-bold text-primary"><?= round($total_remaining_net_weight); ?></td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>


        </div>
    </div>
<?php } ?>