<?php require_once '../connection.php';
$id = $_POST['id'];
$level = $_POST['level'];
$purchase_pays_id = $_POST['purchase_pays_id'];
if ($id > 0) {
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $_fields = transactionSingle($id);
    $notify_party = isset($record['notify_party_details']) ? json_decode($record['notify_party_details'], true) : false;
    if (!empty($_fields)) { ?>
        <div class="row">
            <div class="col-10 order-0 content-column">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="row border-bottom pb-2">
                            <div class="col-md-12 border-bottom px-2 pb-2 mb-2">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <b><?php echo strtoupper($_fields['p_s_name']) . ' #'; ?> </b><?php echo $_fields['sr_no']; ?>
                                    </div>
                                    <div><b>User </b><?php echo $_fields['username']; ?></div>
                                    <!-- </div>
                                <div class="d-flex align-items-center justify-content-between"> -->
                                    <div><b>Date </b><?php echo my_date($_fields['_date']); ?></div>
                                    <div><b>Type </b><?php echo badge(strtoupper($_fields['type']), 'dark'); ?></div>
                                    <!-- </div>
                                <div class="d-flex align-items-center justify-content-between"> -->
                                    <div><b>Country </b><?php echo $_fields['country']; ?></div>
                                    <div><b>Branch </b><?php echo branchName($_fields['branch_id']); ?></div>
                                    <!-- </div>
                                <div class="d-flex align-items-center justify-content-between"> -->
                                    <div><b>Status </b>
                                        <?php if ($_fields['locked'] == 0) {
                                            echo $_fields['is_doc'] == 0 ? '<span class="text-danger">Contract Pending</span>' : '<i class="fa fa-check-double text-success"></i> Attachment';
                                        } else {
                                            echo '<i class="fa fa-lock text-success"></i> Transferred.';
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Because Client said that Dr. Account should be Cr. and Cr. should be Dr.
                                    So it is much difficult to edit it from backend so what i'm doing i'm just labelling Dr. Acc from frontend as Cr. Acc. -->
                                <div><b>Cr. A/c # </b><?php echo $_fields['dr_acc']; ?></div>
                                <div><b>Cr. A/c Name </b><?php echo $_fields['dr_acc_name']; ?></div>
                                <?php if (!empty($_fields['dr_acc_details'])) {
                                    echo '<div><b>Company Details </b>' . nl2br($_fields['dr_acc_details']) . '</div>';
                                } ?>
                            </div>
                            <div class="col-md-4 border-end border-start">
                                <div><b>Dr. A/c # </b><?php echo $_fields['cr_acc']; ?></div>
                                <div><b>Dr. A/c Name </b><?php echo $_fields['cr_acc_name']; ?></div>
                                <?php if (!empty($_fields['cr_acc_details'])) {
                                    echo '<div><b>Company Details </b>' . nl2br($_fields['cr_acc_details']) . '</div>';
                                } ?>
                            </div>
                            <div class="col-md-4">
                                <strong>Notify Party Details</strong>
                                <?php if ($notify_party) { ?>
                                    <div><b>Acc No. </b><?= $notify_party['np_acc']; ?></div>
                                    <div><b>Acc Name </b><?php echo $notify_party['np_acc_name']; ?></div>
                                <?php
                                    if (!empty($notify_party['np_acc_details'])) {
                                        $details = $notify_party['np_acc_details'];
                                        $countryPos = strpos($details, 'Country');
                                        if ($countryPos !== false) {
                                            $companyName = substr($details, 0, $countryPos);
                                            $remainingDetails = substr($details, $countryPos);
                                            echo '<div><b>Company Name: </b>' . trim($companyName) . '</div>';
                                            echo '<div><b>Details: </b>' . nl2br($remainingDetails) . '</div>';
                                        } else {
                                            echo '<div><b>Company Details: </b>' . nl2br($details) . '</div>';
                                        }
                                    }
                                } else {
                                    echo "Notify Party Details Not Added!";
                                }
                                ?>

                            </div>
                        </div>
                        <?php if (!empty($_fields['sea_road_array'])): ?>
                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-12">
                                    <span class="fs-6 fw-bold">By </span>
                                    <?php echo $_fields['sea_road']; ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="fs-6 fw-bold">Loading Details</div>
                                    <div>
                                        <?php
                                        $loadingDetails = [];
                                        foreach ($_fields['sea_road_array'] as $key => $value) {
                                            if (strpos($key, 'l_') === 0) {
                                                if (is_array($value)) {
                                                    echo '<b>' . $value[0] . ':</b> ' . $value[1] . '<br>';
                                                    $loadingDetails[$value[0]] = $value[1];
                                                } else {
                                                    echo '<b>' . strtoupper($key) . ':</b> ' . $value . '<br>';
                                                    $loadingDetails[strtoupper($key)] = $value;
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <input type="hidden" id="loadingDetailsJSON" value='<?= json_encode($loadingDetails); ?>'>
                                </div>


                                <!-- Receiving Country Column -->
                                <div class="col-md-3">
                                    <div class="fs-6 fw-bold">Receiving Details</div>
                                    <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                        <?php
                                        // Check if the key starts with 'r_' (receiving-related) or 'd_' (delivery-related)
                                        if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0):
                                            if (is_array($value)) {
                                                // For delivery dates, show the label as "Arrival Date" instead of just the key
                                                $label =  $value[0];
                                                echo '<b>' . $label . ':</b> ' . $value[1] . '<br>';
                                            } else {
                                                echo '<b>' . strtoupper($key) . ':</b> ' . $value . '<br>';
                                            }
                                        endif;
                                        ?>
                                    <?php endforeach; ?>
                                </div>

                                <div class="col-md-6">
                                    <div class="fs-6 fw-bold">Payment Details</div>
                                    <div>
                                        <?php
                                        $payments = $_fields['payment_details'];
                                        $total_amount = isset($_fields['items_sum']['sum_final_amount']) ? (float)$_fields['items_sum']['sum_final_amount'] : 0;
                                        $percentage = isset($payments->pct_value) ? (int)$payments->pct_value : 0;
                                        $remaining_percentage = 100 - $percentage;
                                        $partial_amount1 = ($percentage / 100) * $total_amount;
                                        $partial_amount2 = ($remaining_percentage / 100) * $total_amount;
                                        $paymentDetails = [];

                                        if (isset($payments->full_advance) && $payments->full_advance === 'advance') {
                                            echo '<b>Type:</b> ' . ucfirst($payments->full_advance) . ' - ' . $percentage . '% (Remaining: ' . $remaining_percentage . '%)<br>';
                                            echo '<b>Partial Amount 1 (' . $percentage . '%):</b> ' . number_format($partial_amount1, 2) . '<br>';

                                            echo '<b>Partial Amount 2 (' . $remaining_percentage . '%):</b> ' . number_format($partial_amount2, 2) . '<br>';


                                            $paymentDetails = [
                                                'Type' => ucfirst($payments->full_advance) . ' - ' . $percentage . '% (Remaining: ' . $remaining_percentage . '%)',
                                                'Partial Amount 1' => number_format($partial_amount1, 2),

                                                'Partial Amount 2' => number_format($partial_amount2, 2),

                                            ];
                                        } elseif (isset($payments->full_advance) && $payments->full_advance === 'full') {
                                            echo '<b>Type:</b> Full Payment<br>';
                                            echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';


                                            $paymentDetails = [
                                                'Type' => 'Full Payment',
                                                'Total Amount' => number_format($total_amount, 2),

                                            ];
                                        } elseif (isset($payments->full_advance) && $payments->full_advance === 'credit') {
                                            echo '<b>Type:</b> Credit Payment<br>';
                                            echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';

                                            $paymentDetails = [
                                                'Type' => 'Credit Payment',
                                                'Total Amount' => number_format($total_amount, 2),

                                            ];
                                        } else {
                                            echo "<b>No payment details available.</b>";
                                        }
                                        ?>
                                    </div>
                                    <input type="hidden" id="paymentDetailsJSON" value='<?= json_encode($paymentDetails); ?>'>
                                </div>




                                <!-- Report Section -->
                                <?php if (isset($_fields['sea_road_report'])): ?>
                                    <div class="col-md-12">
                                        <div class="fs-6 fw-bold">Report</div>
                                        <?php echo $_fields['sea_road_report']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($_fields['items'])) { ?>
                            <table class="table mb-0 table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Goods</th>
                                        <th>SIZE</th>
                                        <th>BRAND</th>
                                        <th>ORIGIN</th>
                                        <th>Qty</th>
                                        <th>Total KGs</th>
                                        <th>Total Qty KGs</th>
                                        <th>Net KGs</th>
                                        <th>Wt.</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $items = $_fields['items'];
                                    $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = 0;
                                    $i = 0;
                                    $rate = 0;
                                    foreach ($items as $details) {
                                        $goods = goodsName($details['goods_id']);
                                        $t_country = $details['origin'];
                                        $allot = $details['allotment_name'];
                                        $curr1 = $details['currency1'];
                                        $curr2 = $details['currency2'];
                                        echo '<tr>';
                                        echo '<td>' . $details['sr'] . '</td>';
                                        echo '<td>' . goodsName($details['goods_id']) . '</td>';
                                        echo '<td>' . $details['size'] . '</td>';
                                        echo '<td>' . $details['brand'] . '</td>';
                                        echo '<td>' . $details['origin'] . '</td>';
                                        echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                        echo '<td>' . round($details['total_qty_kgs'], 2) . '</td>';
                                        echo '<td>' . round($details['net_kgs'], 2);
                                        echo '<sub>' . $details['divide'] . '</sub>';
                                        echo '</td>';
                                        echo '<td>' . $details['weight'] . '</td>';
                                        echo '<td>' . $details['total'] . '</td>';
                                        echo '</tr>';
                                        $rate += $details['rate1'];
                                        $qty_no += $details['qty_no'];
                                        $qty_kgs += $details['qty_kgs'];
                                        $total_kgs += $details['total_kgs'];
                                        $total_qty_kgs += $details['total_qty_kgs'];
                                        $net_kgs += $details['net_kgs'];
                                        $total += $details['total'];
                                        $amount += $details['amount'];
                                        $final_amount += $details['final_amount'];
                                        $i++;
                                    }
                                    if ($qty_no > 0) {
                                        $goodsTotals = [
                                            'Quantity' => $qty_no,
                                            'Total KGs' => round($total_kgs, 2),
                                            'Total Quantity KGs' => round($total_qty_kgs, 2),
                                            'Net KGs' => round($net_kgs, 2),
                                            'Total' => round($total, 2),
                                            'Final' => round($final_amount, 2)
                                        ];
                                        echo '<input type="hidden" id="goodsTotalsJSON" value=\'' . json_encode($goodsTotals) . '\'>';
                                        echo '<tr>
                                                <th colspan="5"></th>
                                                <th class="fw-bold">' . $qty_no . '</th>
                                                <th class="fw-bold">' . round($total_kgs, 2) . '</th>
                                                <th class="fw-bold">' . round($total_qty_kgs, 2) . '</th>
                                                <th class="fw-bold">' . round($net_kgs, 2) . '</th>
                                                <th colspan="1"></th>
                                                <th class="fw-bold">' . round($total, 2) . '</th>
                                                <th></th>
                                              </tr>';
                                    }

                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-responsive">
                            <thead>
                                <tr class="text-center">
                                    <th class="border border-dark bg-warning">Sr#</th>
                                    <th class="border border-dark bg-warning">L_DATE</th>
                                    <th class="border border-dark bg-warning">R_DATE</th>
                                    <th class="border border-dark bg-warning">Importer ACC No</th>
                                    <th class="border border-dark bg-warning">Exporter ACC No</th>
                                    <th class="border border-dark bg-warning">Notify Party ACC No</th>
                                    <th class="border border-dark bg-warning">Goods Name</th>
                                    <th class="border border-dark bg-warning">Quantity No</th>
                                    <th class="border border-dark bg-warning">Quantity Name</th>
                                    <th class="border border-dark bg-warning">Net Weight</th>
                                    <th class="border border-dark bg-warning">Container No</th>
                                    <th class="border border-dark bg-warning">Bail No</th>
                                    <th class="border border-dark bg-warning">Shipping Name</th>
                                    <th class="border border-dark bg-warning">Shipping Phone</th>
                                    <th class="border border-dark bg-warning">Shipping WhatsApp</th>
                                    <th class="border border-dark bg-warning">Shipping Email</th>
                                    <th class="border border-dark bg-warning">Shipping Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Example Row -->
                                <tr class="text-center">
                                    <td class="border border-dark">445</td>
                                    <td class="border border-dark">2023-10-01</td>
                                    <td class="border border-dark">2023-10-05</td>
                                    <td class="border border-dark">IMP123456</td>
                                    <td class="border border-dark">EXP654321</td>
                                    <td class="border border-dark">NOT789012</td>
                                    <td class="border border-dark">Electronics</td>
                                    <td class="border border-dark">100.000</td>
                                    <td class="border border-dark">Boxes</td>
                                    <td class="border border-dark">2000 KG</td>
                                    <td class="border border-dark">CNT123</td>
                                    <td class="border border-dark">BL123456</td>
                                    <td class="border border-dark">XYZ Shipping</td>
                                    <td class="border border-dark">+123456789</td>
                                    <td class="border border-dark">+987654321</td>
                                    <td class="border border-dark">shipping@example.com</td>
                                    <td class="border border-dark">123 Shipping Lane, City</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="card mt-3">
                        <div class="card-body p-3">
                            <form method="post" class="table-form">
                                <!-- General Information -->
                                <h5 class="text-primary">General Information</h5>
                                <hr>
                                <div class="row g-3">
                                    <!-- Sr# (small field) -->
                                    <div class="col-md-2">
                                        <label for="sr_no" class="form-label">Sr#</label>
                                        <input name="sr_no" id="sr_no" required readonly class="form-control form-control-sm" value="445">
                                    </div>
                                    <!-- Loading Date (small field) -->
                                    <div class="col-md-2">
                                        <label for="loading_date" class="form-label">Loading Date</label>
                                        <input type="date" name="loading_date" id="loading_date" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Receiving Date (small field) -->
                                    <div class="col-md-2">
                                        <label for="receiving_date" class="form-label">Receiving Date</label>
                                        <input type="date" name="receiving_date" id="receiving_date" required class="form-control form-control-sm">
                                    </div>
                                </div>

                                <!-- Importer, Notify Party, Exporter in One Row -->
                                <h5 class="text-primary mt-4">Importer, Notify Party, and Exporter Details</h5>
                                <hr>
                                <div class="row g-3">
                                    <!-- Importer Details -->
                                    <div class="col-md-4">
                                        <label for="np_acc_importer" class="form-label fw-bold">Importer Details</label>
                                        <div class="input-group">
                                            <input type="text" id="np_acc_importer" name="np_acc_importer" class="form-control form-control-sm w-25"
                                                placeholder="ACC No" value="<?= isset($NP_details['np_acc_importer']) ? $NP_details['np_acc_importer'] : ''; ?>">
                                            <input type="text" id="np_acc_name_importer" name="np_acc_name_importer" class="form-control form-control-sm w-75"
                                                placeholder="Importer Name" value="<?= isset($NP_details['np_acc_name_importer']) ? $NP_details['np_acc_name_importer'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm mt-2" name="np_acc_kd_id_importer" id="np_acc_kd_id_importer">
                                            <option hidden value="">Select Company</option>
                                            <?php // PHP code to fetch companies 
                                            ?>
                                        </select>
                                        <textarea class="form-control form-control-sm mt-2" name="np_acc_details_importer" id="np_acc_details_importer" rows="2"
                                            placeholder="Company Details"><?= isset($NP_details['np_acc_details_importer']) ? $NP_details['np_acc_details_importer'] : ''; ?></textarea>
                                    </div>

                                    <!-- Notify Party Details -->
                                    <div class="col-md-4">
                                        <label for="np_acc_notify" class="form-label fw-bold">Notify Party Details</label>
                                        <div class="input-group">
                                            <input type="text" id="np_acc_notify" name="np_acc_notify" class="form-control form-control-sm w-25"
                                                placeholder="ACC No" value="<?= isset($NP_details['np_acc_notify']) ? $NP_details['np_acc_notify'] : ''; ?>">
                                            <input type="text" id="np_acc_name_notify" name="np_acc_name_notify" class="form-control form-control-sm w-75"
                                                placeholder="Notify Party Name" value="<?= isset($NP_details['np_acc_name_notify']) ? $NP_details['np_acc_name_notify'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm mt-2" name="np_acc_kd_id_notify" id="np_acc_kd_id_notify">
                                            <option hidden value="">Select Company</option>
                                            <?php // PHP code to fetch companies 
                                            ?>
                                        </select>
                                        <textarea class="form-control form-control-sm mt-2" name="np_acc_details_notify" id="np_acc_details_notify" rows="2"
                                            placeholder="Company Details"><?= isset($NP_details['np_acc_details_notify']) ? $NP_details['np_acc_details_notify'] : ''; ?></textarea>
                                    </div>

                                    <!-- Exporter Details -->
                                    <div class="col-md-4">
                                        <label for="np_acc_exporter" class="form-label fw-bold">Exporter Details</label>
                                        <div class="input-group">
                                            <input type="text" id="np_acc_exporter" name="np_acc_exporter" class="form-control form-control-sm w-25"
                                                placeholder="ACC No" value="<?= isset($NP_details['np_acc_exporter']) ? $NP_details['np_acc_exporter'] : ''; ?>">
                                            <input type="text" id="np_acc_name_exporter" name="np_acc_name_exporter" class="form-control form-control-sm w-75"
                                                placeholder="Exporter Name" value="<?= isset($NP_details['np_acc_name_exporter']) ? $NP_details['np_acc_name_exporter'] : ''; ?>">
                                        </div>
                                        <select class="form-select form-select-sm mt-2" name="np_acc_kd_id_exporter" id="np_acc_kd_id_exporter">
                                            <option hidden value="">Select Company</option>
                                            <?php // PHP code to fetch companies 
                                            ?>
                                        </select>
                                        <textarea class="form-control form-control-sm mt-2" name="np_acc_details_exporter" id="np_acc_details_exporter" rows="2"
                                            placeholder="Company Details"><?= isset($NP_details['np_acc_details_exporter']) ? $NP_details['np_acc_details_exporter'] : ''; ?></textarea>
                                    </div>
                                </div>

                                <!-- Goods Details -->
                                <h5 class="text-primary mt-4">Goods Details</h5>
                                <hr>
                                <div class="row g-3">
                                    <!-- Goods Name -->
                                    <div class="col-md-2">
                                        <label for="goods_name" class="form-label">Goods Name</label>
                                        <input name="goods_name" id="goods_name" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Quantity No (small field, max 5 decimals) -->
                                    <div class="col-md-2">
                                        <label for="quantity_no" class="form-label">Quantity No</label>
                                        <input name="quantity_no" id="quantity_no" required class="form-control form-control-sm" step="0.00001">
                                    </div>
                                    <!-- Quantity Name -->
                                    <div class="col-md-2">
                                        <label for="quantity_name" class="form-label">Quantity Name</label>
                                        <input name="quantity_name" id="quantity_name" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Net Weight (small field) -->
                                    <div class="col-md-2">
                                        <label for="net_weight" class="form-label">Net Weight</label>
                                        <input name="net_weight" id="net_weight" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Container No -->
                                    <div class="col-md-2">
                                        <label for="container_no" class="form-label">Container No</label>
                                        <input name="container_no" id="container_no" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Bail No (small field) -->
                                    <div class="col-md-2">
                                        <label for="Bail" class="form-label">Bail No</label>
                                        <input name="Bail" id="Bail" required class="form-control form-control-sm">
                                    </div>
                                </div>

                                <!-- Shipping Details -->
                                <h5 class="text-primary mt-4">Shipping Details</h5>
                                <hr>
                                <div class="row g-3">
                                    <!-- Shipping Name -->
                                    <div class="col-md-2">
                                        <label for="shipping_name" class="form-label">Shipping Name</label>
                                        <input name="shipping_name" id="shipping_name" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Phone -->
                                    <div class="col-md-2">
                                        <label for="shipping_phone" class="form-label">Shipping Phone</label>
                                        <input name="shipping_phone" id="shipping_phone" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping WhatsApp -->
                                    <div class="col-md-2">
                                        <label for="shipping_whatsapp" class="form-label">Shipping WhatsApp</label>
                                        <input name="shipping_whatsapp" id="shipping_whatsapp" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Email -->
                                    <div class="col-md-2">
                                        <label for="shipping_email" class="form-label">Shipping Email</label>
                                        <input name="shipping_email" id="shipping_email" required class="form-control form-control-sm">
                                    </div>
                                    <!-- Shipping Address -->
                                    <div class="col-md-4">
                                        <label for="shipping_address" class="form-label">Shipping Address</label>
                                        <input name="shipping_address" id="shipping_address" required class="form-control form-control-sm">
                                    </div>
                                </div>

                                <!-- Hidden Fields -->
                                <input type="hidden" name="hidden_id" value="<?= isset($id) ? $id : ''; ?>">

                                <!-- Submit Button -->
                                <div class="row mt-4">
                                    <div class="col-md-12 text-end">
                                        <button name="GoodsSubmit" id="recordSubmit" type="submit"
                                            class="btn btn-primary btn-sm rounded-0">
                                            <i class="fa fa-paper-plane"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-2 order-1 fixed-sidebar table-form">
                        <div class="mt-3">
                            <form id="attachmentSubmit" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="t_id_hidden_attach" value="<?php echo $id; ?>">
                                <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                                <input type="button" class="form-control rounded-1 bg-dark text-white"
                                    value="+ Contract File"
                                    onclick="document.getElementById('attachments').click();" />
                            </form>
                            <script>
                                document.getElementById("attachments").onchange = function() {
                                    document.getElementById("attachmentSubmit").submit();
                                }
                            </script>
                            <?php $atts = getAttachments($id, 'purchase_contract');
                            $no = 0;
                            foreach ($atts as $att) {
                                echo ++$no . '.<a class="text-decoration-underline" href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank">' . readMore($att['attachment'], 20) . '</a><br>';
                            } ?>
                        <?php } ?>

                        <?php if ($_fields['locked'] == 0 && $_fields['is_doc'] > 0) { ?>
                            <form method="post" onsubmit="return confirm('Lock this purchase.\nPress OK to transfer')" action>
                                <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                                <button name="transferPurchase" type="submit" class="btn btn-dark btn-sm w-100 mt-3">
                                    TRANSFER
                                </button>
                            </form>
                        <?php } ?>
                        <div class="bottom-buttons">
                            <div class="px-2">
                                <?php $update_url = $_fields['type'] == 'booking' ? 'purchase-add' : ($_fields['type'] == 'market' ? 'purchase-market-add' : 'purchase-local-add');
                                if ($_POST['page'] !== "purchase-advance") {
                                ?>
                                    <a href="<?php echo $update_url . '?id=' . $id; ?>" class="btn btn-dark btn-sm w-100 mt-2">UPDATE</a>
                                <?php } ?>
                                <a href="print/purchase-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
                                    target="_blank" class="btn btn-dark btn-sm w-100 mt-2">PRINT</a>
                                <?php if ($_fields['locked'] == 0) { ?>
                                    <form method="post" onsubmit="return confirm('Are you sure to delete?');">
                                        <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                                        <button name="deleteTransaction" type="submit" class="btn btn-danger btn-sm w-100 mt-2">
                                            DELETE
                                        </button>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>

                    <?php }
                    ?>
                        </div>
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script>
                $(document).ready(function() {
                    function hidePctInputs() {
                        $("#pct, #pct_amt").hide();
                    }

                    function showPctInputs() {
                        $("#pct, #pct_amt").show();
                    }

                    function updatePctAmt() {
                        var pctValue = parseFloat($("#pct").val()) || 0;
                        var finalAmt = parseFloat($("#final_amt_hidden").val()) || 0;
                        var pctAmt = (pctValue / 100) * finalAmt;

                        $("#pct_amt").val(pctAmt.toFixed(2));
                    }

                    // Initialize: Hide pct inputs
                    hidePctInputs();

                    // Show/hide pct inputs based on the selected option
                    $("#transfer").change(function() {
                        showHidePctInputs($(this).val());
                    });

                    function showHidePctInputs(transfer = null) {
                        if (transfer == "1") {
                            showPctInputs();
                        } else {
                            hidePctInputs();
                        }
                    }

                    let transfer = $('#transfer').find(":selected").val();
                    showHidePctInputs(transfer);

                    // Update pct_amt when user inputs a number in pct
                    $("#pct").on("input", updatePctAmt);
                });

                function lastAmount() {
                    let amount = $("#amount").val();
                    let rate = $("#rate").val();


                    let operator = $('#opr').find(":selected").val();

                    let final_amount;
                    if (operator === "/") {
                        final_amount = Number(amount) / Number(rate);
                    } else {
                        final_amount = Number(amount) * Number(rate);
                    }
                    final_amount = final_amount.toFixed(3);
                    $("#final_amount").val(final_amount);
                    var balance = $("#balance").val();
                    /*if (Number(balance) >= 1) {
                        if (Number(balance) <= Number(final_amount)) {
                            disableButton('recordSubmit');
                        } else {
                            enableButton('recordSubmit');
                        }
                    } else {
                        disableButton('recordSubmit');
                    }*/
                }
            </script>