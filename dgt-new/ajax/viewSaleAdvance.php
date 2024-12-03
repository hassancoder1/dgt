<?php require_once '../connection.php';
$id = $purchase_id = $_POST['id'];
$level = $_POST['level'];
$purchase_pays_id = $_POST['purchase_pays_id'];
if ($id > 0) {
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $sea_road = json_decode($record['sea_road'], true);
    $purchase_type = $record['type'];
    $_fields = transactionSingle($id);
    $_fields['sea_road_array'] = array_merge($_fields['sea_road_array'], !empty($sea_road) ? $sea_road : []);
    $notify_party = isset($record['notify_party_details']) ? json_decode($record['notify_party_details'], true) : false;
    if ($record['type'] === 'local') {
        $notify_party = '';
    }
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
                            <?php if ($notify_party !== ''): ?>
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
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($_fields['sea_road_array'])): ?>
                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-12">
                                    <span class="fs-6 fw-bold">
                                        <?= $_fields['type'] === 'booking' ? 'By ' . $_fields['sea_road'] : (isset($_fields['sea_road_array']['truck_no']) ? 'Loading Transfer' : 'WareHouse Transfer'); ?> </span>

                                </div>
                                <?php if ($_fields['type'] === 'booking'):
                                    $_fields['sea_road_array']['l_date_road'] = '';
                                    $_fields['sea_road_array']['r_date_road'] = ''; ?>
                                    <div class="col-md-3">
                                        <div class="fs-6 fw-bold">Loading Details</div>
                                        <div>
                                            <?php
                                            foreach ($_fields['sea_road_array'] as $key => $value) {
                                                if (strpos($key, 'l_') === 0 && !empty($value)) {
                                                    if (is_array($value)) {
                                                        echo '<b>' . $value[0] . ':</b> ' . $value[1] . '<br>';
                                                    } else {
                                                        echo '<b>' . ucwords(str_replace('_', ' ', str_replace('l_', '', $key))) . ':</b> ' . $value . '<br>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <!-- Receiving Country Column -->
                                    <div class="col-md-3">
                                        <div class="fs-6 fw-bold">Receiving Details</div>
                                        <?php foreach ($_fields['sea_road_array'] as $key => $value): ?>
                                            <?php
                                            if (!empty($value) && strpos($key, 'r_') === 0):
                                                if (is_array($value)) {
                                                    $label = ($key === 'd_date_road') ? 'Arrival Date' : $value[0];
                                                    echo '<b>' . $label . ':</b> ' . $value[1] . '<br>';
                                                } else {
                                                    echo '<b>' . ucwords(str_replace('_', ' ', str_replace('r_', '', $key))) . ':</b> ' . $value . '<br>';
                                                }
                                            endif;
                                            ?>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php else: ?>
                                    <div class="col-md-4">
                                        <?php
                                        if ($_fields['sea_road_array']['sea_road'] === 'sea'): ?>
                                            <b>Truck No:</b> <?= $_fields['sea_road_array']['truck_no'] ?? 'N/A'; ?><br>
                                            <b>Truck Name:</b> <?= $_fields['sea_road_array']['truck_name'] ?? 'N/A'; ?><br>
                                            <b>Loading Company:</b> <?= $_fields['sea_road_array']['loading_company_name'] ?? 'N/A'; ?><br>
                                            <b>Loading Date:</b> <?= $_fields['sea_road_array']['loading_date'] ?? 'N/A'; ?><br>
                                            <b>Transfer Name:</b> <?= $_fields['sea_road_array']['transfer_name'] ?? 'N/A'; ?><br>
                                        <?php endif; ?>
                                        <?php if ($_fields['sea_road_array']['sea_road'] === 'road'): ?>
                                            <b>Old Company Name:</b> <?= $_fields['sea_road_array']['old_company_name'] ?? 'N/A'; ?><br>
                                            <b>Transfer Company Name:</b> <?= $_fields['sea_road_array']['transfer_company_name'] ?? 'N/A'; ?><br>
                                            <b>Warehouse Date:</b> <?= $_fields['sea_road_array']['warehouse_date'] ?? 'N/A'; ?><br>
                                        <?php endif; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

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
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th>#</th>
                                        <th>GOODS / SIZE / BRAND / ORIGIN</th>
                                        <th>QTY</th>
                                        <th>KGs</th>
                                        <th>NET KGs</th>
                                        <th>TOTAL</th>
                                        <th>PRICE</th>
                                        <th>AMOUNT</th>
                                        <?php if ($record['type'] !== 'local') { ?>
                                            <th class="text-end">FINAL</th>
                                        <?php } else {; ?>
                                            <th>Tax%</th>
                                            <th>Tax.Amt</th>
                                            <th>Amt+Tax</th>
                                        <?php } ?>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr_details = 1;
                                    $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = $total_tax_amount = $total_total_with_tax = 0;
                                    $pur_d_q = fetch('transaction_items', array('parent_id' => $id));
                                    while ($details = mysqli_fetch_assoc($pur_d_q)) {
                                        $details_id = $details['id'];
                                        echo '<tr>';
                                        echo '<td>' . $details['sr'] . '</td>';
                                        echo '<td><a class="text-dark">' . goodsName($details['goods_id']) . '</a> / ' . $details['size'] . ' / ' . $details['brand'] . ' / ' . $details['origin'] . '</td>';
                                        echo '<td>' . $details['qty_no'] . '<sub>' . $details['qty_name'] . '</sub></td>';
                                        echo '<td>' . round($details['total_kgs'], 2) . '</td>';
                                        echo '<td>' . round($details['net_kgs'], 2);
                                        echo '<sub>' . $details['divide'] . '</sub>';
                                        echo '</td>';
                                        echo '<td>' . $details['total'] . '</td>';
                                        echo '<td>' . $details['price'] . '</td>';
                                        echo '<td>' . round($details['amount'], 2);
                                        echo '<sub>' . $details['currency1'] . '</sub>';
                                        echo '</td>';
                                        if ($record['type'] !== 'local') {
                                            echo '<td class="text-end">' . round($details['final_amount'], 2);
                                            echo '<sub>' . $details['currency2'] . '</sub>';
                                        } else {
                                            echo '<td>' . $details['tax_percent'] . "%";
                                            echo '<td>' . $details['tax_amount'];
                                            echo '<td>' . $details['total_with_tax'];
                                        };
                                        echo '</td>';
                                        echo '</tr>';
                                        $sr_details++;
                                        $qty_no += $details['qty_no'];
                                        $qty_kgs += $details['qty_kgs'];
                                        $total_kgs += $details['total_kgs'];
                                        $total_qty_kgs += $details['total_qty_kgs'];
                                        $net_kgs += $details['net_kgs'];
                                        $total += $details['total'];
                                        $amount += $details['amount'];
                                        $final_amount += $details['final_amount'];
                                        $total_tax_amount += (float)$details['tax_amount'];
                                        $total_total_with_tax += (float)$details['total_with_tax'];
                                    }
                                    $prepareGoodsReport = '';
                                    if ($qty_no > 0) {
                                        echo '<tr>';
                                        echo '<th colspan="2"></th>';
                                        echo '<th class="fw-bold">' . $qty_no . '</th>';
                                        echo '<th class="fw-bold">' . round($total_kgs, 2) . '</th>';
                                        echo '<th class="fw-bold">' . round($net_kgs, 2) . '</th>';
                                        echo '<th class="fw-bold">' . round($total, 2) . '</th>';
                                        echo '<th></th>';
                                        echo '<th class="fw-bold">' . round($amount, 2) . '</th>';
                                        if ($record['type'] === 'local') {
                                            echo '<th></th>';
                                            echo '<th class="fw-bold">' . round($total_tax_amount, 2) . '</th>';
                                            echo '<th class="fw-bold">' . round($total_total_with_tax, 2) . '</th>';
                                        }
                                        if ($record['type'] !== 'local') {
                                            echo '<th class="fw-bold text-end">' . round($final_amount, 2) . '</th>';
                                        }
                                        echo '<th></th>';
                                        echo '</tr>';   
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <?php if ($record['khaata_tr1'] != '') {
                        $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $record['id'], 'transfered_from' => 'sale_' . $record['type']));
                        $roz = mysqli_fetch_assoc($rozQ);
                        $roz_arr1 = array(
                            array('Sr#', SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']),
                            array('Date', $roz['r_date']),
                            array('ID', $roz['username']),
                            array('Branch', branchName($roz['branch_id'])),
                        );
                        $roz_arr2 = array(
                            array('Roz.#', $roz['roznamcha_no']),
                            array('Name', $roz['r_name']),
                            array('No', $roz['r_no']),
                        );
                        $roz_arr3 = array(
                            array('Dr.', $roz['amount']),
                            //array('Cr.', 0),
                        );
                        $roz_arr4 = array(
                            array('Total Amount', round($final_amount, 2)),
                            array('Percent', $percentage . '%'),
                            array('Advance', round($partial_amount1, 2)),
                        );
                    }
                    $adv_paid_final = purchaseSpecificData($record['id'], 'adv_paid_total', 'amount');
                    $bal = $partial_amount1 - $adv_paid_final;
                    ?>
                    <hr class="my-0">
                    <div class="m-3">
                        <b>Advance Details</b>
                        <table class="table mb-2 table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="border bg-warning border-dark">Sr#</th>
                                    <th class="border bg-warning border-dark">Date</th>
                                    <th class="border bg-warning border-dark">ID</th>
                                    <th class="border bg-warning border-dark">Branch</th>
                                    <th class="border bg-warning border-dark">Roz#</th>
                                    <th class="border bg-warning border-dark">No</th>
                                    <th class="border bg-warning border-dark">Total Amount</th>
                                    <th class="border bg-warning border-dark">Percent</th>
                                    <th class="border bg-warning border-dark">Advance</th>
                                    <th class="border bg-warning border-dark text-success">Total</th>
                                    <th class="border bg-warning border-dark text-danger">BALANCE</th>
                                    <th class="border bg-warning border-dark">Transferred</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-dark"><?php echo $roz_arr1[0][1]; ?></td> <!-- Sr# -->
                                    <td class="border border-dark"><?php echo $roz_arr1[1][1]; ?></td> <!-- Date -->
                                    <td class="border border-dark"><?php echo $roz_arr1[2][1]; ?></td> <!-- ID -->
                                    <td class="border border-dark"><?php echo $roz_arr1[3][1]; ?></td> <!-- Branch -->
                                    <td class="border border-dark"><?php echo $roz_arr2[0][1]; ?></td> <!-- Roz# -->
                                    <td class="border border-dark"><?php echo $roz_arr2[1][1]; ?></td> <!-- Name -->
                                    <td class="border border-dark"><?php echo $roz_arr3[0][1]; ?></td> <!-- Total Amount -->
                                    <td class="border border-dark"><?php echo $roz_arr4[1][1]; ?></td> <!-- Percent -->
                                    <td class="border border-dark"><?php echo $roz_arr4[2][1]; ?></td> <!-- Advance -->
                                    <td class="border border-dark text-success"><?php echo round($adv_paid_final); ?></td> <!-- Total -->
                                    <td class="border border-dark text-danger" id="#balance"><?php echo round($bal); ?></td> <!-- BALANCE -->
                                    <td class="border border-dark">
                                        <?php
                                        if ($bal <= 10 && $record['transfer_level'] < 3) {
                                            update('transactions', array('transfer_level' => 3), array('id' => $record['id']));
                                        ?><script>
                                                window.location.href = '<?= "purchase-advance?view=1&p_id=" . $id ?>';
                                            </script><?php
                                                    }
                                                    if ($bal <= 10) {
                                                        if ($record['transfer_level'] > 2 && $record['transfer_level'] < 4) { ?>
                                                <form method="post" onsubmit="return confirm('Transfer to Remaining 80% Form.\n Press OK to transfer')">
                                                    <input type="hidden" name="p_id_hidden" value="<?php echo $record['id']; ?>">
                                                    <button name="transferAdvanceToRem" type="submit" class="btn btn-dark btn-sm">
                                                        TRANSFER
                                                    </button>
                                                </form>
                                        <?php } else {
                                                            echo '<i class="fa fa-check-double text-success"></i> Yes';
                                                        }
                                                    } else {
                                                        echo '<i class="fa fa-times text-danger"></i> No';
                                                    } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mx-3">
                        <b class="mt-2">Transactions Details</b>
                        <table class="table mb-2 table-hover table-sm">
                            <thead>
                                <tr>
                                    <th class="border border-dark">#</th>
                                    <th class="border border-dark">Date</th>
                                    <th class="border border-dark">Dr. A/c</th>
                                    <th class="border border-dark">Cr. A/c</th>
                                    <th class="border border-dark">Report</th>
                                    <th class="border border-dark">Amount</th>
                                    <th class="border border-dark">Rate</th>
                                    <th class="border border-dark">Final</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $adv_paid = purchaseSpecificData($record['id'], 'adv');
                                $i = 1;
                                foreach ($adv_paid as $item) {
                                    // $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $purchase_id, 'transfered_from' => 'purchase_' . $record['type']));
                                    // $roz = mysqli_fetch_assoc($rozQ);
                                    echo '<tr>';
                                    echo '<td class="border border-dark">' . $i++ . '</td>';
                                    echo '<td class="border border-dark">' . my_date($item['created_at']) . '</td>';

                                    echo '<td class="border border-dark"><a href="sale-advance?view=1&p_id=' . $record['id'] . '&purchase_pays_id=' . $item['id'] . '">' . $item['dr_khaata_no'] . '</a></td>';
                                    echo '<td class="border border-dark">' . $item['cr_khaata_no'] . '</td>';
                                    echo '<td class="border border-dark">' . $item['report'] . '</td>';
                                    echo '<td class="border border-dark">' . round($item['amount']) . '<sub>' . $item['currency1'] . '</sub></td>';
                                    echo '<td class="border border-dark">' . $item['rate'] . ' [' . $item['opr'] . ']</td>';
                                    echo '<td class="border border-dark">' . round($item['final_amount']) . '<sub>' . $item['currency2'] . '</sub></td>';
                                    echo '</tr>';
                                } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="collapse show" id="collapseExample">
                        <input type="hidden" id="balance" value="<?php echo $bal; ?>">
                        <?php
                        $p_khaata_id = json_decode($record['khaata_tr1'], true)['dr_khaata_no'];
                        $s_khaata_id = json_decode($record['khaata_tr1'], true)['cr_khaata_no'];
                        $adv_arr = array(
                            'finish' => array('div_class' => '', 'btn_text' => 'Transfer', 'btn_class' => 'btn-primary', 'back' => '', 'purchase_pays_id' => $purchase_pays_id, 'action' => 'insert'),
                            'dr_khaata_no' => $p_khaata_id,
                            'cr_khaata_no' => $s_khaata_id,
                            'currency1' => '',
                            'amount' => '',
                            'currency2' => '',
                            'rate' => '',
                            'opr' => '*',
                            'final_amount' => '',
                            'transfer_date' => date('Y-m-d'),
                            'report' => ''
                            //'report' => 'ENTRY:' . $rows . ' GOODS:' . $goods . ' COUNTRY:' . $record['country'] . ' ALLOT:' . $record['allot'] . ' T.Qty:' . $qtys . ' T.KGs:' . $totals . ' RATE:' . $rate . ' T.AMNT:' . $amounts . $curr . ' EXCH.:' . $curr2
                        );
                        if ($purchase_pays_id > 0) {
                            $purchase_paysQ = fetch('purchase_pays', array('id' => $purchase_pays_id));
                            if (mysqli_num_rows($purchase_paysQ) > 0) {
                                $pps = mysqli_fetch_assoc($purchase_paysQ);
                                $adv_arr = array(
                                    'finish' => array('div_class' => 'border border-danger', 'btn_text' => 'Update', 'btn_class' => 'btn-warning', 'back' => '<a href="sale-advance?view=1&p_id=' . $id . '">Back</a>', 'purchase_pays_id' => $purchase_pays_id, 'action' => 'update'),
                                    'dr_khaata_no' => $pps['dr_khaata_no'],
                                    'cr_khaata_no' => $pps['cr_khaata_no'],
                                    'currency1' => $pps['currency1'],
                                    'amount' => $pps['amount'],
                                    'currency2' => $pps['currency2'],
                                    'rate' => $pps['rate'],
                                    'opr' => $pps['opr'],
                                    'final_amount' => $pps['final_amount'],
                                    'transfer_date' => $pps['transfer_date'],
                                    'report' => $pps['report']
                                );
                            }
                        }
                        $rid_delete_array = array(); ?>
                        <div class="card mt-3">
                            <div class="card-body p-2">
                                <form method="post" onsubmit="return confirm('Are you sure?');" class="table-form <?php echo $adv_arr['finish']['div_class'] ?>">
                                    <?php echo $adv_arr['finish']['back']; ?>
                                    <div class="row gx-0">
                                        <div class="col-md-2">
                                            <div class="input-group position-relative">
                                                <label for="khaata_no1" class="text-success">Dr. A/c</label>
                                                <input name="dr_khaata_no" id="khaata_no1" required class="form-control bg-transparent"
                                                    value="<?php echo $adv_arr['dr_khaata_no']; ?>">
                                                <small class="error-response top-0" id="p_response"></small>
                                            </div>
                                            <input type="hidden" name="dr_khaata_id" id="p_khaata_id">
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group position-relative">
                                                <label for="khaata_no2" class="text-danger">Cr. A/c</label>
                                                <input name="cr_khaata_no" id="khaata_no2" required class="form-control bg-transparent"
                                                    value="<?php echo $adv_arr['cr_khaata_no']; ?>">
                                                <small class="error-response top-0" id="s_response"></small>
                                            </div>
                                            <input type="hidden" name="cr_khaata_id" id="s_khaata_id">
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="currency1">Currency</label>
                                                <select id="currency1" name="currency1" class="form-select bg-transparent" required>
                                                    <option value="" hidden="">Select</option>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $sel_curr = $adv_arr['currency1'] == $crr['name'] ? 'selected' : '';
                                                        echo '<option ' . $sel_curr . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <label for="amount">Amount</label>
                                                <input type="text" id="amount" name="amount" class="form-control currency"
                                                    onkeyup="lastAmount()" required value="<?php echo $adv_arr['amount']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="currency2">Currency</label>
                                                <select id="currency2" name="currency2" class="form-select bg-transparent" required>
                                                    <option value="" hidden="">Select</option>
                                                    <?php $currencies = fetch('currencies');
                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                        $sel_curr2 = $adv_arr['currency2'] == $crr['name'] ? 'selected' : '';
                                                        echo '<option ' . $sel_curr2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                    } ?>
                                                </select>
                                                <label for="rate">Rate</label>
                                                <input type="text" name="rate" class="form-control currency" id="rate" required
                                                    onkeyup="lastAmount()" value="<?php echo $adv_arr['rate']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="input-group">
                                                <label for="opr">Op.</label>
                                                <select name="opr" class="form-select" id="opr" required onchange="lastAmount()">
                                                    <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                    foreach ($ops as $opName => $op) {
                                                        $sel_op = $adv_arr['opr'] == $op ? 'selected' : '';
                                                        echo '<option ' . $sel_op . ' value="' . $op . '">' . $opName . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-3">
                                            <div class="input-group">
                                                <label for="final_amount">F.Amt.</label>
                                                <input type="text" name="final_amount" class="form-control" id="final_amount" required
                                                    readonly tabindex="-1" value="<?php echo $adv_arr['final_amount']; ?>">

                                                <label for="transfer_date">Date</label>
                                                <input type="date" class="form-control" id="transfer_date" name="transfer_date" required
                                                    value="<?php echo $adv_arr['transfer_date']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-7 mt-3">
                                            <div class="input-group">
                                                <label for="report">Report</label>
                                                <input placeholder="Report" class="form-control" id="report" name="report" required
                                                    value="<?php echo $adv_arr['report']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2 mt-3 text-end">
                                            <button name="tAdvSubmit" id="recordSubmit" type="submit"
                                                class="btn <?php echo $adv_arr['finish']['btn_class']; ?> btn-sm  rounded-0"><i
                                                    class="fa fa-paper-plane"></i> <?php echo $adv_arr['finish']['btn_text']; ?>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="p_id_hidden" value="<?php echo $record['id']; ?>">
                                    <input type="hidden" name="p_type_hidden" value="<?php echo $purchase_type; ?>">
                                    <input type="hidden" name="purchase_pays_id_hidden" value="<?php echo $adv_arr['finish']['purchase_pays_id']; ?>">
                                    <input type="hidden" name="action" value="<?php echo $adv_arr['finish']['action']; ?>">
                                    <?php
                                    if ($purchase_pays_id > 0) {
                                        $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $purchase_pays_id, 'transfered_from' => 'sale_advance'));
                                        if (mysqli_num_rows($rozQ) > 0) { ?>
                                            <table class="table table-sm table-bordered">
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
                                                        $rid_delete_array[] = $roz['r_id'];
                                                        $dr = $cr = 0; ?>
                                                        <input type="hidden" value="<?php echo $roz['r_id']; ?>" name="r_id[]">
                                                        <tr>
                                                            <td>
                                                                <?php echo SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']; ?>
                                                            </td>
                                                            <td><?php echo $roz['r_date']; ?></td>
                                                            <td>
                                                                <a href="ledger?back-khaata-no=<?php echo $roz['khaata_no']; ?>"
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
                                </form>
                                <?php //$decodedArray = json_decode($_POST['p_type_hidden'], true);
                                if ($purchase_pays_id > 0) { ?>
                                    <form method="post"
                                        onsubmit="return confirm('Are you sure to delete Payment?\nThe record will be delete from Roznamcha too.\nPress OK to Delete');">
                                        <input type="hidden" name="r_id_hidden" value="<?php echo htmlspecialchars(json_encode($rid_delete_array)); ?>">
                                        <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
                                        <input type="hidden" name="p_type_hidden" value="<?php echo $purchase_type; ?>">
                                        <input type="hidden" name="purchase_pays_id_hidden" value="<?php echo $purchase_pays_id; ?>">
                                        <button name="deletePaymentAndRozSubmit" type="submit" class="btn btn-danger btn-sm">Delete This Payment</button>
                                    </form>
                                <?php } ?>
                            </div>
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
                            <form method="post" onsubmit="return confirm('Lock this sale.\nPress OK to transfer')" action>
                                <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                                <button name="transferPurchase" type="submit" class="btn btn-dark btn-sm w-100 mt-3">
                                    TRANSFER
                                </button>
                            </form>
                        <?php } ?>
                        <div class="bottom-buttons">
                            <div class="px-2">
                                <?php $update_url = $_fields['type'] == 'booking' ? 'sale-add' : ($_fields['type'] == 'market' ? 'sale-market-add' : 'sale-local-add');
                                if ($_POST['page'] !== "sale-advance") {
                                ?>
                                    <a href="<?php echo $update_url . '?id=' . $id; ?>" class="btn btn-dark btn-sm w-100 mt-2">UPDATE</a>
                                <?php } ?>
                                <a href="print/sale-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
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

                    if (amount && rate) { // Ensure both amount and rate have values
                        if (operator === "/") {
                            final_amount = Number(amount) / Number(rate);
                        } else {
                            final_amount = Number(amount) * Number(rate);
                        }
                        final_amount = final_amount.toFixed(2);
                        $("#final_amount").val(final_amount);

                        let balance = $("#balance").val();
                        balance = parseFloat(balance);
                        // if (balance !== 0) {
                        //     if (final_amount > balance) {
                        //         disableButton('recordSubmit');
                        //     } else {
                        //         enableButton('recordSubmit');
                        //     }
                        // } else {
                        //     disableButton('recordSubmit');
                        // }
                        if (balance >= 1) {
                            // if (final_amount <= balance + 0.5) {
                            //     enableButton('recordSubmit');
                            // } else {
                            //     disableButton('recordSubmit');
                            // }
                        } else {
                            disableButton('recordSubmit');
                        }
                    }
                }
            </script>