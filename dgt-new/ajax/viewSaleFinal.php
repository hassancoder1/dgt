<?php require_once '../connection.php';
$id = $purchase_id = $_POST['id'];
$level = $_POST['level'];
$purchase_pays_id = 0;
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
                                        <b><?php echo strtoupper($_fields['p_s_name']) . ' #'; ?> </b><?php echo $_fields['sr']; ?>
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

                                        if (isset($payments->full_advance) && $payments->full_advance === 'advance') {
                                            echo '<b>Type:</b> ' . ucfirst($payments->full_advance) . ' - ' . $percentage . '% (Remaining: ' . $remaining_percentage . '%)<br>';
                                            echo '<b>Partial Amount 1 (' . $percentage . '%):</b> ' . number_format($partial_amount1, 2) . '<br>';
                                            echo '<b>Partial Amount 2 (' . $remaining_percentage . '%):</b> ' . number_format($partial_amount2, 2) . '<br>';
                                        } elseif (isset($payments->full_advance) && $payments->full_advance === 'full') {
                                            echo '<b>Type:</b> Full Payment<br>';
                                            echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';
                                        } elseif (isset($payments->full_advance) && $payments->full_advance === 'credit') {
                                            echo '<b>Type:</b> Credit Payment<br>';
                                            echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';
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
                                        <th>Price</th>
                                        <th>Amount</th>
                                        <th>Final</th>
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
                                        echo '<td>' . $details['price'] . '</td>';
                                        echo '<td>' . round($details['amount'], 2);
                                        echo '<sub>' . $details['currency1'] . '</sub>';
                                        echo '</td>';
                                        echo '<td class="text-end">' . round($details['final_amount'], 2);
                                        echo '<sub>' . $details['currency2'] . '</sub>';
                                        echo '</td>';
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
                                                <th class="fw-bold">' . round($amount, 2) . '</th>
                                                <th class="fw-bold text-end">' . round($final_amount, 2) . '</th>
                                                <th></th>
                                              </tr>';
                                    }

                                    ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                    <?php
                    $payments = json_decode($record['payments'], true);
                    $total_amount = isset($_fields['items_sum']['sum_final_amount']) ? (float)$_fields['items_sum']['sum_final_amount'] : 0;
                    $percentage = isset($payments['pct_value']) ? (int)$payments['pct_value'] : 0;
                    $remaining_percentage = 100 - $percentage;
                    $partial_amount1 = ($percentage / 100) * $total_amount;
                    $partial_amount2 = ($remaining_percentage / 100) * $total_amount;
                    if ($record['from'] == 'sale-credit') {
                        if ($record['khaata_tr1'] != '') {
                            $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $record['sr'], 'transfered_from' => 'sale_' . $record['type']));
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
                            );
                        }
                        $crdt_paid_final = purchaseSpecificData($record['id'], 'crdt_paid_total', 'amount');
                        $bal = $total_amount - $crdt_paid_final;
                    } else {
                        if ($record['khaata_tr1'] != '') {
                            $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $record['sr'], 'transfered_from' => 'sale_' . $record['type']));
                            $roz = mysqli_fetch_assoc($rozQ);
                            $roz_arr1 = array(
                                array('Sr#', SuperAdmin() ? $roz['r_id'] . '-' . $roz['branch_serial'] : $roz['branch_serial']),
                                array('Date', $roz['r_date']),
                                // array('ID', $roz['username']),
                                // array('Branch', branchName($roz['branch_id'])),
                            );
                            $roz_arr2 = array(
                                // array('Roz.#', $roz['roznamcha_no']),
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
                            $roz_arr5 = array(
                                array('Total Amount', round($final_amount, 2)),
                                array('Percent', $percentage . '%'),
                                array('Remaining', round($partial_amount2, 2)),
                            );
                        }
                        $adv_paid_final = purchaseSpecificData($record['id'], 'adv_paid_total', 'amount');
                        $balADV = $partial_amount1 - $adv_paid_final;
                        $rem_paid_final = purchaseSpecificData($record['id'], 'rem_paid_total', 'amount');
                        $balREM = $partial_amount2 - $rem_paid_final;
                    }
                    ?>
                    <hr class="my-0">
                    <div class="m-3">
                        <b>Details</b>
                        <table class="table mb-2 table-hover table-sm">
                            <thead>
                                <?php if ($record['from'] !== 'sale-credit') { ?>
                                    <tr>
                                        <th class="border bg-warning border-dark">Sr#</th>
                                        <th class="border bg-warning border-dark">Date</th>
                                        <!-- <th class="border bg-warning border-dark">ID</th> -->
                                        <!-- <th class="border bg-warning border-dark">Branch</th> -->
                                        <!-- <th class="border bg-warning border-dark">Roz#</th> -->
                                        <th class="border bg-warning border-dark">No</th>
                                        <th class="border bg-warning border-dark">Total Amount</th>
                                        <?php if ($record['from'] !== 'bill-transfer') { ?>
                                            <th class="border bg-warning border-dark">Adv Percent</th>
                                            <th class="border bg-warning border-dark">Advance</th>
                                            <th class="border bg-warning border-dark text-success">Total</th>
                                            <th class="border bg-warning border-dark text-danger">BALANCE</th>
                                            <th class="border bg-warning border-dark">Transferred</th>
                                            <th class="border bg-warning border-dark">Remaining</th>
                                            <th class="border bg-warning border-dark text-success">Total</th>
                                            <th class="border bg-warning border-dark text-danger">BALANCE</th>
                                            <th class="border bg-warning border-dark">Transferred</th>
                                        <?php
                                        } else {
                                        ?><th class="border bg-warning border-dark text-success">Grand Total</th><?php
                                                                                                                } ?>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <th class="border bg-warning border-dark">Sr#</th>
                                        <th class="border bg-warning border-dark">Date</th>
                                        <th class="border bg-warning border-dark">ID</th>
                                        <th class="border bg-warning border-dark">Branch</th>
                                        <th class="border bg-warning border-dark">Roz#</th>
                                        <th class="border bg-warning border-dark">Name</th>
                                        <th class="border bg-warning border-dark">Total Amount</th>
                                        <th class="border bg-warning border-dark text-success">Total</th>
                                        <th class="border bg-warning border-dark text-danger">BALANCE</th>
                                        <th class="border bg-warning border-dark">Transferred</th>
                                    </tr>
                                <?php } ?>
                            </thead>
                            <tbody>
                                <?php if ($record['from'] !== 'sale-credit') { ?>
                                    <tr>
                                        <td class="border border-dark"><?php echo $roz_arr1[0][1]; ?></td> <!-- Sr# -->
                                        <td class="border border-dark"><?php echo $roz_arr1[1][1]; ?></td> <!-- Date -->
                                        <!-- <td class="border border-dark"><?php echo $roz_arr1[2][1]; ?></td> ID -->
                                        <!-- <td class="border border-dark"><?php echo $roz_arr1[3][1]; ?></td> Branch -->
                                        <!-- <td class="border border-dark"><?php echo $roz_arr2[0][1]; ?></td> Roz# -->
                                        <td class="border border-dark"><?php echo $roz_arr2[1][1]; ?></td> <!-- Name -->
                                        <td class="border border-dark"><?php echo round($roz_arr4[0][1]); ?></td> <!-- Total Amount -->
                                        <?php if ($record['from'] !== 'bill-transfer') { ?>
                                            <td class="border border-dark"><?php echo $roz_arr4[1][1]; ?></td> <!-- Percent -->
                                            <td class="border border-dark"><?php echo $roz_arr4[2][1]; ?></td> <!-- Advance -->
                                            <td class="border border-dark text-success"><?php echo round($adv_paid_final); ?></td> <!-- Total -->
                                            <td class="border border-dark text-danger"><?php echo round($balADV); ?></td> <!-- BALANCE -->
                                            <td class="border border-dark">
                                                <?php
                                                if ($balADV <= 10 && $record['transfer_level'] < 3) {
                                                    update('transactions', array('transfer_level' => 3), array('id' => $record['id']));

                                                ?><script>
                                                        window.location.href = '<?= "sale-remaining?view=1&p_id=" . $id ?>';
                                                    </script><?php
                                                            }

                                                            if ($balADV <= 10) {
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
                                                            }
                                                ?>
                                            </td>
                                            <td class="border border-dark"><?php echo $partial_amount2; ?></td> <!-- Remaining -->
                                            <td class="border border-dark text-success"><?php echo round($rem_paid_final); ?></td> <!-- RemTotal -->
                                            <td class="border border-dark text-danger"><?php echo round($balREM); ?></td> <!-- RemBALANCE -->

                                            <td class="border border-dark">
                                                <?php
                                                if ($balREM <= 10 && $record['transfer_level'] < 5) {
                                                    update('transactions', array('transfer_level' => 5), array('id' => $record['id']));
                                                ?><script>
                                                        window.location.href = '<?= "sale-remaining?view=1&p_id=" . $id ?>';
                                                    </script><?php
                                                            }

                                                            if ($balREM <= 10) {
                                                                if ($record['transfer_level'] > 4 && $record['transfer_level'] < 6) { ?>
                                                        <form method="post" onsubmit="return confirm('Transfer to Remaining.\n Press OK to transfer')">
                                                            <input type="hidden" name="p_id_hidden" value="<?php echo $record['id']; ?>">
                                                            <button name="transferRemToFull" type="submit" class="btn btn-dark btn-sm">
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
                                        <?php } else {
                                        ?><td class="border border-dark text-success"><?= round($total_amount); ?></td><?php
                                                                                                                    } ?>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <td class="border border-dark"><?php echo $roz_arr1[0][1]; ?></td> <!-- Sr# -->
                                        <td class="border border-dark"><?php echo $roz_arr1[1][1]; ?></td> <!-- Date -->
                                        <td class="border border-dark"><?php echo $roz_arr1[2][1]; ?></td> <!-- ID -->
                                        <td class="border border-dark"><?php echo $roz_arr1[3][1]; ?></td> <!-- Branch -->
                                        <td class="border border-dark"><?php echo $roz_arr2[0][1]; ?></td> <!-- Roz# -->
                                        <td class="border border-dark"><?php echo $roz_arr2[1][1]; ?></td> <!-- Name -->
                                        <td class="border border-dark"><?php echo $roz_arr4[0][1]; ?></td> <!-- Total Amount -->
                                        <td class="border border-dark text-success"><?php echo round($crdt_paid_final); ?></td> <!-- Total -->
                                        <td class="border border-dark text-danger"><?php echo round($bal); ?></td> <!-- BALANCE -->
                                        <td class="border border-dark">
                                            <?php
                                            if ($bal <= 10 && $record['transfer_level'] < 3) {
                                                update('transactions', array('transfer_level' => 3), array('id' => $record['id']));
                                            ?><script>
                                                    window.location.href = '<?= "sale-credit?view=1&p_id=" . $id ?>';
                                                </script><?php
                                                        }
                                                        if ($bal <= 10) {
                                                            if ($record['transfer_level'] > 2 && $record['transfer_level'] < 4) { ?>
                                                    <form method="post" onsubmit="return confirm('Transfer to Final.\n Press OK to transfer')">
                                                        <input type="hidden" name="p_id_hidden" value="<?php echo $record['id']; ?>">
                                                        <button name="transferCreditToFinal" type="submit" class="btn btn-dark btn-sm">
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
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($record['from'] === 'sale-remaining') { ?>
                        <div class="mx-3">
                            <b class="mt-2">Advance Transactions Details</b>
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
                                        // $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $record['sr'], 'transfered_from' => 'purchase_' . $record['type']));
                                        // $roz = mysqli_fetch_assoc($rozQ);
                                        echo '<tr>';
                                        echo '<td class="border border-dark">' . $i++ . '</td>';
                                        echo '<td class="border border-dark">' . my_date($item['created_at']) . '</td>';

                                        echo '<td class="border border-dark"><a href="sale-remaining?view=1&p_id=' . $record['id'] . '&purchase_pays_id=' . $item['id'] . '">' . $item['dr_khaata_no'] . '</a></td>';
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
                        <!-- Remaining Entries -->
                        <?php if ($record['khaata_tr1'] != '') {
                            $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $record['sr'], 'transfered_from' => 'sale_' . $record['type']));
                            $roz = mysqli_fetch_assoc($rozQ);

                            // Arrays to hold row data
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
                            );
                            $roz_arr4 = array(
                                array('Total Amt', round($final_amount)),
                                array('Advance', round($partial_amount1) . '<sub>' . $percentage . '%</sub>'),
                            );
                        }
                        ?>

                        <!-- <hr class="my-0 mb-2"> -->

                        <div class="m-3">
                            <b>Remaining Transactions Details</b>
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
                                    <?php
                                    $rem_paid = purchaseSpecificData($purchase_id, 'rem');
                                    $i = 1; // Initialize the counter
                                    foreach ($rem_paid as $item) {
                                        echo '<tr>';
                                        echo '<td class="border border-dark">' . $i++ . '</td>'; // Row number
                                        echo '<td class="border border-dark">' . my_date($item['created_at']) . '</td>'; // Date

                                        // Dr. A/c with link
                                        echo '<td class="border border-dark"><a href="sale-remaining?view=1&p_id=' . $purchase_id . '&purchase_pays_id=' . $item['id'] . '">' . $item['dr_khaata_no'] . '</a></td>';
                                        echo '<td class="border border-dark">' . $item['cr_khaata_no'] . '</td>'; // Cr. A/c
                                        echo '<td class="border border-dark">' . $item['report'] . '</td>'; // Report
                                        echo '<td class="border border-dark">' . round($item['amount']) . '<sub>' . $item['currency1'] . '</sub></td>'; // Amount

                                        echo '<td class="border border-dark">' . $item['rate'] . ' [' . $item['opr'] . ']</td>'; // Rate
                                        echo '<td class="border border-dark">' . round($item['final_amount']) . '<sub>' . $item['currency2'] . '</sub></td>'; // Final Amount
                                        echo '</tr>';
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else if ($record['from'] === 'sale-credit') { ?>
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
                                    <?php $adv_paid = purchaseSpecificData($record['id'], 'crdt');
                                    $i = 1;
                                    foreach ($adv_paid as $item) {
                                        // $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'dr_cr' => 'dr', 'transfered_from_id' => $record['sr'], 'transfered_from' => 'purchase_' . $record['type']));
                                        // $roz = mysqli_fetch_assoc($rozQ);
                                        echo '<tr>';
                                        echo '<td class="border border-dark">' . $i++ . '</td>';
                                        echo '<td class="border border-dark">' . my_date($item['created_at']) . '</td>';

                                        echo '<td class="border border-dark"><a href="sale-credit?view=1&p_id=' . $record['id'] . '&purchase_pays_id=' . $item['id'] . '">' . $item['dr_khaata_no'] . '</a></td>';
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
                        <?php
                    } else {
                        if ($record['khaata_tr1'] != '') {
                            $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $record['sr'], 'transfered_from' => 'sale_' . $record['type']));
                            if (mysqli_num_rows($rozQ) > 0) { ?>
                                <div class="p-2">
                                    <table class="table table-sm table-bordered mb-0">
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
                                                <input type="hidden" value="<?php echo $roz['r_date']; ?>"
                                                    id="temp_transfer_date">
                                                <input type="hidden" value="<?php echo $roz['r_id']; ?>"
                                                    name="r_id[]">
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
                                </div>
                    <?php }
                        }
                    } ?>
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
                                <?php $update_url = $_fields['type'] == 'booking' ? 'sale-add' : ($_fields['type'] == 'market' ? 'sale-market-add' : 'sale-local-add');
                                if ($_POST['page'] !== "sale-remaining") {
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
                        </div>
                    </div>
                <?php }
                ?>
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