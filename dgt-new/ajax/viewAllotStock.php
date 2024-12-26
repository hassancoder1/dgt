<?php
require_once '../connection.php';
$print_url = '';
if (!empty($_POST['allot'])) {
    $allot = mysqli_real_escape_string($connect, $_POST['allot']);
    $query = "SELECT * FROM `transaction_items` WHERE allotment_name = '$allot' AND parent_id IN (SELECT id FROM transactions WHERE transfer_level >= 2)";
    $result = mysqli_query($connect, $query);

    if ($result) {
        $print_url = 'print/allot-stock-print?allot=' . $allot;
        $allEntries = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $processedEntries = [];
        $purchase_ids = [];
        $sold_ids = [];

        foreach ($allEntries as $entry) {
            $key = $entry['goods_id'] . '|' . $entry['size'] . '|' . $entry['brand'] . '|' . $entry['origin'];

            // Collect unique parent_ids for purchases and sales
            if ($entry['p_s'] === 'p') {
                if (!isset($purchase_ids[$key])) {
                    $purchase_ids[$key] = [];
                }
                if (!in_array($entry['parent_id'], $purchase_ids[$key])) {
                    $purchase_ids[$key][] = $entry['parent_id'];
                }

                // Add purchase entry to the processed list
                if (!isset($processedEntries[$key])) {
                    $processedEntries[$key] = [
                        'goods_id' => $entry['goods_id'],
                        'size' => $entry['size'],
                        'brand' => $entry['brand'],
                        'origin' => $entry['origin'],
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
                $processedEntries[$key]['total_purchased_qty'] += $entry['qty_no'];
                $processedEntries[$key]['total_purchased_kgs'] += $entry['total_kgs'];
                $processedEntries[$key]['total_purchased_net_kgs'] += $entry['net_kgs'];
                $processedEntries[$key]['purchase_ids'][] = $entry['parent_id'];
            } elseif ($entry['p_s'] === 's') {
                if (!isset($sold_ids[$key])) {
                    $sold_ids[$key] = [];
                }
                if (!in_array($entry['parent_id'], $sold_ids[$key])) {
                    $sold_ids[$key][] = $entry['parent_id'];
                }

                // Add sales data to the corresponding purchase entry
                if (isset($processedEntries[$key])) {
                    $processedEntries[$key]['total_sold_qty'] += $entry['qty_no'];
                    $processedEntries[$key]['total_sold_kgs'] += $entry['total_kgs'];
                    $processedEntries[$key]['total_sold_net_kgs'] += $entry['net_kgs'];
                    $processedEntries[$key]['sale_ids'][] = $entry['parent_id'];
                }
            }
        }
?>
        <div class="modal-header d-flex justify-content-between bg-white align-items-center">
            <h5 class="modal-title" id="staticBackdropLabel">Allotment Details: ( <?= htmlspecialchars($allot); ?> )</h5>
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
                            <th>Total Qty</th>
                            <th>T.G.Weight</th>
                            <th>T.N.Weight</th>
                            <th>Sold Qty</th>
                            <th>S.G.Weight</th>
                            <th>S.N.Weight</th>
                            <th>Rem Qty</th>
                            <th>Rem.G.Weight</th>
                            <th>Rem.N.Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Initialize totals
                        $total_purchased_qty = $total_purchased_kgs = $total_purchased_net_kgs = $total_sold_qty = $total_sold_kgs = $total_sold_net_kgs = $total_remaining_qty = $total_remaining_gross_weight = $total_remaining_net_weight = 0;
                        $i = 1;
                        foreach ($processedEntries as $entry) {
                            $remaining_qty = $entry['total_purchased_qty'] - $entry['total_sold_qty'];
                            $remaining_gross_weight = $entry['total_purchased_kgs'] - $entry['total_sold_kgs'];
                            $remaining_net_weight = $entry['total_purchased_net_kgs'] - $entry['total_sold_net_kgs'];

                            // Concatenate unique IDs
                            $purchase_ids = !empty($entry['purchase_ids']) ? implode(',', $entry['purchase_ids']) : '-';
                            $sale_ids = !empty($entry['sale_ids']) ? implode(',', $entry['sale_ids']) : '-';
                            $ps_display = $purchase_ids . ' | ' . $sale_ids;

                            // Update totals
                            $total_purchased_qty += $entry['total_purchased_qty'];
                            $total_purchased_kgs += $entry['total_purchased_kgs'];
                            $total_purchased_net_kgs += $entry['total_purchased_net_kgs'];
                            $total_sold_qty += $entry['total_sold_qty'];
                            $total_sold_kgs += $entry['total_sold_kgs'];
                            $total_sold_net_kgs += $entry['total_sold_net_kgs'];
                            $total_remaining_qty += $remaining_qty;
                            $total_remaining_gross_weight += $remaining_gross_weight;
                            $total_remaining_net_weight += $remaining_net_weight;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($i); ?></td>
                                <td><?= htmlspecialchars($ps_display); ?></td>
                                <td>
                                    <?= goodsName(htmlspecialchars($entry['goods_id'])) . ' / ' .
                                        htmlspecialchars($entry['size']) . ' / ' .
                                        htmlspecialchars($entry['brand']) . ' / ' .
                                        htmlspecialchars($entry['origin']); ?>
                                </td>
                                <td class="fw-bold text-success"><?= round($entry['total_purchased_qty']); ?></td>
                                <td class="fw-bold text-success"><?= round($entry['total_purchased_kgs']); ?></td>
                                <td class="fw-bold text-success"><?= round($entry['total_purchased_net_kgs']); ?></td>
                                <td class="fw-bold text-danger"><?= round($entry['total_sold_qty']); ?></td>
                                <td class="fw-bold text-danger"><?= round($entry['total_sold_kgs']); ?></td>
                                <td class="fw-bold text-danger"><?= round($entry['total_sold_net_kgs']); ?></td>
                                <td class="fw-bold text-primary"><?= round($remaining_qty); ?></td>
                                <td class="fw-bold text-primary"><?= round($remaining_gross_weight); ?></td>
                                <td class="fw-bold text-primary"><?= round($remaining_net_weight); ?></td>
                            </tr>
                        <?php $i++;
                        } ?>
                        <!-- Totals Row -->
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><i class="far fa-circle"></i></td>
                            <td></td>
                            <td class="fw-bold">Allot Totals: </td>
                            <td class="fw-bold text-success"><?= number_format(round($total_purchased_qty)); ?></td>
                            <td class="fw-bold text-success"><?= number_format(round($total_purchased_kgs)); ?></td>
                            <td class="fw-bold text-success"><?= number_format(round($total_purchased_net_kgs)); ?></td>
                            <td class="fw-bold text-danger"><?= number_format(round($total_sold_qty)); ?></td>
                            <td class="fw-bold text-danger"><?= number_format(round($total_sold_kgs)); ?></td>
                            <td class="fw-bold text-danger"><?= number_format(round($total_sold_net_kgs)); ?></td>
                            <td class="fw-bold text-primary"><?= number_format(round($total_remaining_qty)); ?></td>
                            <td class="fw-bold text-primary"><?= number_format(round($total_remaining_gross_weight)); ?></td>
                            <td class="fw-bold text-primary"><?= number_format(round($total_remaining_net_weight)); ?></td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
<?php }
} ?>