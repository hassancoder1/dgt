<?php require_once '../connection.php';
$id = $_POST['id'];
$level = $_POST['level'];
if ($id > 0) {
    $records = fetch('transactions', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $sea_road = json_decode($record['sea_road'], true);
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
                                            // echo '<b>Date:</b> ' . $payments->partial_date1 . '<br>';
                                            // echo '<b>Report:</b> ' . ucfirst($payments->partial_report1) . '<br>';
                                            echo '<b>Partial Amount 2 (' . $remaining_percentage . '%):</b> ' . number_format($partial_amount2, 2) . '<br>';
                                            // echo '<b>Date:</b> ' . $payments->partial_date2 . '<br>';
                                            // echo '<b>Report:</b> ' . ucfirst($payments->partial_report2) . '<br>';

                                            $paymentDetails = [
                                                'Type' => ucfirst($payments->full_advance) . ' - ' . $percentage . '% (Remaining: ' . $remaining_percentage . '%)',
                                                'Partial Amount 1' => number_format($partial_amount1, 2),
                                                'Date 1' => $payments->partial_date1,
                                                'Report 1' => ucfirst($payments->partial_report1),
                                                'Partial Amount 2' => number_format($partial_amount2, 2),
                                                // 'Date 2' => $payments->partial_date2,
                                                // 'Report 2' => ucfirst($payments->partial_report2)
                                            ];
                                        } elseif (isset($payments->full_advance) && $payments->full_advance === 'full') {
                                            echo '<b>Type:</b> Full Payment<br>';
                                            echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';
                                            // echo '<b>Date:</b> ' . $payments->full_date . '<br>';
                                            // echo '<b>Report:</b> ' . ucfirst($payments->full_report) . '<br>';

                                            $paymentDetails = [
                                                'Type' => 'Full Payment',
                                                'Total Amount' => number_format($total_amount, 2),
                                                'Date' => $payments->full_date,
                                                'Report' => ucfirst($payments->full_report)
                                            ];
                                        } elseif (isset($payments->full_advance) && $payments->full_advance === 'credit') {
                                            echo '<b>Type:</b> Credit Payment<br>';
                                            echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';
                                            // echo '<b>Date:</b> ' . $payments->full_date . '<br>';
                                            // echo '<b>Report:</b> ' . ucfirst($payments->full_report) . '<br>';

                                            $paymentDetails = [
                                                'Type' => 'Credit Payment',
                                                'Total Amount' => number_format($total_amount, 2),
                                                'Date' => $payments->credit_date,
                                                'Report' => ucfirst($payments->credit_report)
                                            ];
                                        } else {
                                            echo "<b>No payment details available.</b>";
                                        }
                                        ?>
                                    </div>
                                    <input type="hidden" id="paymentDetailsJSON" value='<?= json_encode($paymentDetails); ?>'>
                                </div>




                                <!-- Report Section -->
                                <duv class="mt-3"></duv>
                                <!-- <?php if (isset($_fields['sea_road_report'])): ?>
                                    <div class="col-md-12">
                                        <div class="fs-6 fw-bold">Report</div>
                                        <?php echo $_fields['sea_road_report']; ?>
                                    </div>
                                <?php endif; ?> -->
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
                    <?php if ($_POST['page'] !== "bill-transfer") { ?>
                        <?php
                        if (isset($record['reports']) && !empty($record['reports']) && $record['reports'] !== '[]') {
                            $purchase_reports = json_decode($record['reports'], true);
                            if (json_last_error() !== JSON_ERROR_NONE) {
                                echo '<div class="alert alert-danger">JSON Decode Error: ' . htmlspecialchars(json_last_error_msg()) . '</div>';
                                $purchase_reports = [];
                            }

                            if (!empty($purchase_reports)) {
                        ?>
                                <div class="p-3 mt-4">
                                    <h4 class="fw-bold">Purchase Reports</h4>
                                    <table class="table mb-2 table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap" colspan="4">Report Type</th>
                                                <th class="" colspan="7">Report Details</th>
                                                <th colspan="1">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchase_reports as $key => $value): ?>
                                                <tr>
                                                    <td class="text-nowrap fw-bold" colspan="4"><?php echo ucwords(str_replace('_', ' ', $key)); ?></td>
                                                    <td class="" colspan="7"><?php echo nl2br(htmlspecialchars($value)); ?></td>
                                                    <th colspan="1" class="text-nowrap">
                                                        <a href="?deletePurchaseReport=<?= urlencode($key); ?>&p_hidden_id=<?= $record['id']; ?>&t_id=<?= $record['id']; ?>" class="btn btn-sm btn-outline-danger py-0 px-1 mx-2">
                                                            <i class="fa fa-trash-alt"></i>
                                                        </a>
                                                        <input type="hidden" id="<?= htmlspecialchars($key . $record['id']); ?>" value="<?= htmlspecialchars($key); ?>">
                                                        <textarea class="d-none" id="<?= htmlspecialchars($key . $record['id'] . '_report'); ?>"><?= htmlspecialchars($value); ?></textarea>
                                                        <a href="purchase-add?id=<?= $record['id']; ?>&type=<?= $record['type']; ?>" class="btn btn-sm btn-outline-primary py-0 px-1 mx-2">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>

                                                    </th>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                            <?php
                            } else {
                                echo '<strong class="p-3">No Reports Found!</strong>';
                            ?>

                            <?php
                            }
                        } else {
                            echo '<strong class="p-3">No Reports Found!</strong>';
                            ?>
                    <?php
                        }
                    }
                    ?>

                </div>
                <?php
                if ($record['locked'] == 1 && ($_POST['page'] === "bill-transfer" || $_POST['page'] === "purchase-advance" || $_POST['page'] === "purchase-remaining")) {
                    $ddd = '';
                    // $ddd = 'ENTRY:' . $i . ' GOODS:' . $goods . ' COUNTRY:' . $t_country . ' ALLOT:' . $allot . ' T.Qty:' . $qty_no . ' T.KGs:' . $total_kgs . ' RATE:' . $rate / $i . ' T.AMNT:' . $amount . $curr1 . ' EXCH.:' . $curr2;
                ?>
                    <div class="card">
                        <div class="card-body p-2">
                            <form method="post">
                                <div class="row gx-1 gy-3 table-form mb-3">
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="cr_acc" class="text-success">Dr. (Sale)</label>

                                            <input value="<?php echo $_fields['cr_acc']; ?>" id="cr_acc"
                                                name="dr_khaata_no" readonly tabindex="-1" class="form-control"
                                                required>
                                        </div>
                                        <input type="hidden" name="dr_khaata_id"
                                            value="<?php echo $_fields['cr_acc_id']; ?>">
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="p_khaata_no" class="text-danger">Cr. (Purchaser)</label>
                                            <input type="text" id="p_khaata_no" name="cr_khaata_no"
                                                class="form-control"
                                                readonly tabindex="-1"
                                                value="<?php echo $_fields['dr_acc']; ?>">
                                        </div>
                                        <input type="hidden" name="cr_khaata_id"
                                            value="<?php echo $_fields['dr_acc_id'] ?>">
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="transfer_date">Date</label>
                                            <input type="date" class="form-control" id="transfer_date"
                                                name="transfer_date" required
                                                value="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group">
                                            <label for="amount" class="mb-0">Amount</label>
                                            <input value="<?php echo round((!empty($total_total_with_tax) ? $total_total_with_tax : $final_amount), 2); ?>" id="amount"
                                                readonly
                                                name="amount" class="form-control" required tabindex="-1">
                                                <input type="hidden" name="total_with_tax" value="<?= !empty($total_total_with_tax) ? $total_total_with_tax : ''; ?>">
                                        </div>
                                    </div>

                                    <div class="col-10">
                                        <div class="input-group">
                                            <label for="details">Details</label>
                                            <input type="text" name="details" id="details" class="form-control"
                                                value="<?php echo $ddd; ?>">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <input type="hidden" name="check_full_payment" value="<?= $payments->full_advance === 'full' ? 'true' : 'false'; ?>">
                                        <button name="ttrFirstSubmit" type="submit"
                                            class="btn btn-primary w-100 btn-sm"><i class="fa fa-upload"></i>Transfer
                                        </button>
                                    </div>
                                    <input type="hidden" name="p_id_hidden" value="<?php echo $record['id']; ?>">
                                    <input type="hidden" name="type" value="<?php echo $record['type']; ?>">
                                </div>
                                <?php if ($record['khaata_tr1'] != '') {
                                    $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $record['id'], 'transfered_from' => 'purchase_' . $record['type']));
                                    if (mysqli_num_rows($rozQ) > 0) { ?>
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
                                <?php }
                                } ?>
                            </form>
                        </div>
                    </div>
                <?php
                } ?>
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
                    <form method="post" onsubmit="return validateForm()" action="">
                        <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                        <button name="transferPurchase" type="submit" class="btn btn-dark btn-sm w-100 mt-3">
                            TRANSFER
                        </button>
                    </form>
                    <script>
                        function validateForm() {
                            const seaRoad = <?= json_encode($record['sea_road']); ?>;
                            const thirdPartyBank = <?= json_encode($record['third_party_bank']); ?>;
                            const notifyParty = <?= json_encode($record['notify_party_details']); ?>;
                            const payments = <?= json_encode($record['payments']); ?>;
                            if (!seaRoad || !thirdPartyBank || !notifyParty || !payments) {
                                alert('Please Fill Routes, Third Party Bank, Notify Party Details & Payment Details To transfer');
                                return false;
                            }
                            return confirm('Lock this purchase.\nPress OK to transfer');
                        }
                    </script>
                <?php } ?>

                <?php /* if ($_POST['page'] !== "bill-transfer" && $_POST['page'] !== "general-stock-form") { ?>
                    <button class="btn btn-dark btn-sm w-100 mt-3" onclick="openModal('', '')">Add Reports</button>
                <?php } */ ?>

                <!-- <div id="customModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); z-index: 1000;">
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 75%; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                        <button style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 24px; cursor: pointer;" onclick="closeModal()">
                            <i class="fa fa-times"></i>
                        </button>
                        <h3 id="modalHeading">Add Report</h3>
                        <mark>Note: Please Don't use these words OR combination of these words => (\n, \r, \r\n, \n\r). This will effect reports functionality. Thank You!</mark>
                        <form method="post" class="mt-3">
                            <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                            <select name="reportType" class="form-control" id="reportType">
                                <option value="" selected disabled>Select Report Type</option>
                                <option value="payment_details">Payment Details</option>
                                <option value="goods_details">Goods Details</option>
                                <option value="loading_details">Loading Details</option>
                                <option value="contract_details">Contract Details</option>
                            </select>
                            <textarea placeholder="Write Report..." rows="6" name="reportBox" id="reportBox" class="form-control mt-1"></textarea>
                            <button id="modalButton" name="purchaseReports" type="submit" class="btn btn-dark btn-sm w-100 mt-3">Add Report</button>
                        </form>
                    </div>
                </div>
                </div> -->
                <div class="bottom-buttons">
                    <div class="px-2">
                        <?php $update_url = $_fields['type'] == 'booking' ? 'purchase-add' : ($_fields['type'] == 'market' ? 'purchase-market-add' : 'purchase-local-add');
                        if ($_POST['page'] !== "bill-transfer" && $_POST['page'] !== 'general-stock-form') {
                        ?>
                            <a href="<?php echo 'purchase-add?id=' . $id . '&type=' . $_fields['type']; ?>" class="btn btn-dark btn-sm w-100 mt-2">UPDATE</a>
                        <?php } ?>
                        <a href="print/purchase-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
                            target="_blank" class="btn btn-dark btn-sm w-100 mt-2">PRINT</a>
                        <?php if ($_fields['locked'] == 0 && $_POST['page'] !== 'general-stock-form') { ?>
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
        </script>