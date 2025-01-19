<?php
require_once '../connection.php';
$print_url = '';
if (!empty($_POST['allot'])) {
    $allot = mysqli_real_escape_string($connect, $_POST['allot']);

    // Fetch transaction_items and transactions
    $query = "
    SELECT ti.*, t.id AS transaction_id
    FROM transaction_items ti
    INNER JOIN transactions t ON ti.parent_id = t.id
    WHERE ti.allotment_name = '" . mysqli_real_escape_string($connect, $allot) . "' 
      AND t.transfer_level >= 2
";

    $result = mysqli_query($connect, $query);

    // Fetch transaction_accounts data
    $accounts_query = "SELECT * FROM transaction_accounts";
    $accounts_result = mysqli_query($connect, $accounts_query);
    $trans_Acc = mysqli_fetch_all($accounts_result, MYSQLI_ASSOC);

    if ($result) {
        $print_url = 'print/allot-stock-print?allot=' . $allot;
        $allEntries = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $processedEntries = [];

        foreach ($allEntries as $entry) {
            // Find corresponding cr_dr accounts for this transaction
            $cr_acc = null;
            $dr_acc = null;
            foreach ($trans_Acc as $acc) {
                if ($acc['trans_id'] == $entry['parent_id']) {
                    if ($acc['dr_cr'] === 'cr') {
                        $cr['acc'] = $acc['acc'];
                        $cr['name'] = $acc['acc_name'];
                    } elseif ($acc['dr_cr'] === 'dr') {
                        $dr['acc'] = $acc['acc'];
                        $dr['name'] = $acc['acc_name'];
                    }
                }
            }

            // Create a unique key for grouping entries
            $key = $entry['goods_id'] . '|' . $entry['size'] . '|' . $entry['brand'] . '|' . $entry['origin'];

            if (!isset($processedEntries[$key])) {
                $processedEntries[$key] = [
                    'goods_id' => $entry['goods_id'],
                    'p_sr' => getTransactionSr($entry['parent_id']),
                    'sr' => $entry['sr'],
                    'size' => $entry['size'],
                    'brand' => $entry['brand'],
                    'origin' => $entry['origin'],
                    'cr_acc' => $cr,
                    'dr_acc' => $dr,
                    'total_purchased_qty' => 0,
                    'total_purchased_kgs' => 0,
                    'total_purchased_net_kgs' => 0,
                    'total_sold_qty' => 0,
                    'total_sold_kgs' => 0,
                    'total_sold_net_kgs' => 0,
                    'purchase_ids' => [],
                    'sale_ids' => [],
                ];
            }

            // Accumulate purchase or sales details
            if ($entry['p_s'] === 'p') {
                $processedEntries[$key]['total_purchased_qty'] += $entry['qty_no'];
                $processedEntries[$key]['total_purchased_kgs'] += $entry['total_kgs'];
                $processedEntries[$key]['total_purchased_net_kgs'] += $entry['net_kgs'];
                $processedEntries[$key]['purchase_ids'][] = $entry['sr'];
            } elseif ($entry['p_s'] === 's') {
                $processedEntries[$key]['total_sold_qty'] += $entry['qty_no'];
                $processedEntries[$key]['total_sold_kgs'] += $entry['total_kgs'];
                $processedEntries[$key]['total_sold_net_kgs'] += $entry['net_kgs'];
                $processedEntries[$key]['sale_ids'][] = $entry['sr'];
            }
        }
?>

        <div class="modal-header d-flex justify-content-between bg-white align-items-center">
            <h5 class="modal-title" id="staticBackdropLabel">Allotment Name: ( <?= htmlspecialchars($allot); ?> )</h5>
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

        <!-- Purchase Section -->
        <div class="card mt-2">
            <div class="card-body">
                <h5>Purchase Entries</h5>
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.#</th>
                            <th>Goods (Name / Size / Brand / Origin)</th>
                            <th>Cr. Acc</th>
                            <th>Dr. Acc</th>
                            <th>Total Qty</th>
                            <th>T.G.Weight</th>
                            <th>T.N.Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($processedEntries as $entry) {
                            if ($entry['total_purchased_qty'] > 0) {
                        ?>
                                <tr>
                                    <td>P#<?= $entry['p_sr'] . ' (' . $entry['sr'] . ')'; ?></td>
                                    <td><?= goodsName(htmlspecialchars($entry['goods_id'])) . ' / ' . htmlspecialchars($entry['size']) . ' / ' . htmlspecialchars($entry['brand']) . ' / ' . htmlspecialchars($entry['origin']); ?></td>
                                    <td><?= $entry['cr_acc']['acc'] . ' (' . $entry['cr_acc']['name'] . ')'; ?></td>
                                    <td><?= $entry['dr_acc']['acc'] . ' (' . $entry['dr_acc']['name'] . ')'; ?></td>
                                    <td class="fw-bold text-success"><?= round($entry['total_purchased_qty']); ?></td>
                                    <td class="fw-bold text-success"><?= round($entry['total_purchased_kgs']); ?></td>
                                    <td class="fw-bold text-success"><?= round($entry['total_purchased_net_kgs']); ?></td>
                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Section -->
        <div class="card mt-2">
            <div class="card-body">
                <h5>Sale Entries</h5>
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.#</th>
                            <th>Goods (Name / Size / Brand / Origin)</th>
                            <th>Cr. Acc</th>
                            <th>Dr. Acc</th>
                            <th>Total Sold Qty</th>
                            <th>S.G.Weight</th>
                            <th>S.N.Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($processedEntries as $entry) {
                            if ($entry['total_sold_qty'] > 0) {
                        ?>
                                <tr>
                                    <td>S#<?= $entry['p_sr'] . ' (' . $entry['sr'] . ')'; ?></td>
                                    <td><?= goodsName(htmlspecialchars($entry['goods_id'])) . ' / ' . htmlspecialchars($entry['size']) . ' / ' . htmlspecialchars($entry['brand']) . ' / ' . htmlspecialchars($entry['origin']); ?></td>
                                    <td><?= $entry['cr_acc']['acc'] . ' (' . $entry['cr_acc']['name'] . ')'; ?></td>
                                    <td><?= $entry['dr_acc']['acc'] . ' (' . $entry['dr_acc']['name'] . ')'; ?></td>
                                    <td class="fw-bold text-danger"><?= round($entry['total_sold_qty']); ?></td>
                                    <td class="fw-bold text-danger"><?= round($entry['total_sold_kgs']); ?></td>
                                    <td class="fw-bold text-danger"><?= round($entry['total_sold_net_kgs']); ?></td>
                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Remaining Stock Section -->
        <div class="card mt-2">
            <div class="card-body">
                <h5>Remaining Stock</h5>
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>No.#</th>
                            <th>Goods (Name / Size / Brand / Origin)</th>
                            <th>Cr. Acc</th>
                            <th>Dr. Acc</th>
                            <th>Remaining Qty</th>
                            <th>Rem.G.Weight</th>
                            <th>Rem.N.Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($processedEntries as $entry) {
                            $remaining_qty = $entry['total_purchased_qty'] - $entry['total_sold_qty'];
                            $remaining_gross_weight = $entry['total_purchased_kgs'] - $entry['total_sold_kgs'];
                            $remaining_net_weight = $entry['total_purchased_net_kgs'] - $entry['total_sold_net_kgs'];

                            if ($remaining_qty > 0) {
                        ?>
                                <tr>
                                    <td>P#<?= $entry['p_sr'] . ' (' . $entry['sr'] . ')'; ?></td>
                                    <td><?= goodsName(htmlspecialchars($entry['goods_id'])) . ' / ' . htmlspecialchars($entry['size']) . ' / ' . htmlspecialchars($entry['brand']) . ' / ' . htmlspecialchars($entry['origin']); ?></td>
                                    <td><?= $entry['cr_acc']['acc'] . ' (' . $entry['cr_acc']['name'] . ')'; ?></td>
                                    <td><?= $entry['dr_acc']['acc'] . ' (' . $entry['dr_acc']['name'] . ')'; ?></td>
                                    <td class="fw-bold text-primary"><?= round($remaining_qty); ?></td>
                                    <td class="fw-bold text-primary"><?= round($remaining_gross_weight); ?></td>
                                    <td class="fw-bold text-primary"><?= round($remaining_net_weight); ?></td>
                                </tr>
                        <?php
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
<?php
    }
}
?>