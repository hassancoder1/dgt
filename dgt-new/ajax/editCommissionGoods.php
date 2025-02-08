<?php require_once '../connection.php';
$id = $_POST['id'];
$level = $_POST['level'];
$print_url = 'print/transaction-single';
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
    $_POST['print_type'] = $_POST['print_type'] ?? '';
    $print_url .= '?t_id=' . $id . '&print_type=' . $_POST['print_type'] . "&timestamp=" . ($_POST['timestamp'] ?? '');
    $payments = json_decode($record['payments'], true);
    if (!empty($_fields)) { ?>
        <div class="modal-header d-flex justify-content-between bg-white align-items-center">
            <h5 class="modal-title" id="staticBackdropLabel">TRANSACTION DETAILS</h5>
            <div class="d-flex align-items-center gap-2">
                <?php
                if (isset($_POST['type'])) { ?>
                    <a href="sales-commission-form" class="btn-close ms-3" aria-label="Close"></a>
                <?php }
                ?>
            </div>
        </div>
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
                                    <div><b>Date </b><?php echo my_date($_fields['_date']); ?></div>
                                    <div><b>Type </b><?php echo badge(strtoupper($_fields['type']), 'dark'); ?></div>
                                    <div><b>Country </b><?php echo $_fields['country']; ?></div>
                                    <div><b>Branch </b><?php echo branchName($_fields['branch_id']); ?></div>
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
                                <div><b>Cr. A/c # </b><?php echo $_fields['dr_acc']; ?> <sup class="fw-bold text-danger"> (Purchase)</sup></div>
                                <div><b>Cr. A/c Name </b><?php echo $_fields['dr_acc_name']; ?></div>
                                <?php if (!empty($_fields['dr_acc_details'])) {
                                    echo '<div><b>Company Details </b>' . nl2br($_fields['dr_acc_details']) . '</div>';
                                } ?>
                            </div>
                            <div class="col-md-4 border-end border-start">
                                <div><b>Dr. A/c # </b><?php echo $_fields['cr_acc']; ?> <sup class="fw-bold text-success"> (Sale)</sup></div>
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
                        <!-- <div class="row g-3 mt-2">
                            <?php if (!empty($_fields['sea_road_array'])) {
                                if ($_fields['type'] !== 'local'): ?>
                                    <div class="col-md-3 col-sm-12 mb-3">
                                        <h5 class="fw-bold text-primary">By <?= $_fields['sea_road']; ?></h5>
                                        <h6 class="fw-bold">Loading Details</h6>
                                        <ul class="list-unstyled">
                                            <?php foreach ($_fields['sea_road_array'] as $key => $value):
                                                if (!empty($value)) {
                                                    if (strpos($key, 'l_') === 0):
                                                        $key = str_replace('_', ' ', $key); ?>
                                                        <li>
                                                            <strong><?= is_array($value) ? $value[0] : strtoupper($key); ?>:</strong>
                                                            <?= is_array($value) ? $value[1] : $value; ?>
                                                        </li>
                                                <?php endif;
                                                } ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-3 col-sm-12 mb-3">
                                        <h6 class="fw-bold">Receiving Details</h6>
                                        <ul class="list-unstyled">
                                            <?php foreach ($_fields['sea_road_array'] as $key => $value):
                                                if (!empty($value)) {
                                                    if (strpos($key, 'r_') === 0 || strpos($key, 'd_') === 0):
                                                        $key = str_replace('_', ' ', $key); ?>
                                                        <li>
                                                            <strong><?= $key === 'd_date_road' ? 'Arrival Date' : (is_array($value) ? $value[0] : strtoupper($key)); ?>:</strong>
                                                            <?= is_array($value) ? $value[1] : $value; ?>
                                                        </li>
                                                <?php endif;
                                                } ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                <?php else: ?>
                                    <div class="col-md-4 col-sm-12 mb-3">
                                        <h6 class="fw-bold"><?= $sea_road['sea_road'] == 'sea' ? 'Local' : 'Warehouse'; ?> Details</h6>
                                        <ul class="list-unstyled">
                                            <?php if ($sea_road['sea_road'] == 'sea'): ?>
                                                <?php
                                                $fields = [
                                                    'Truck No' => !empty($sea_road['truck_no']) ? $sea_road['truck_no'] : '',
                                                    'Truck Name' => !empty($sea_road['truck_name']) ? $sea_road['truck_name'] : '',
                                                    'Loading Company Name' => !empty($sea_road['loading_company_name']) ? $sea_road['loading_company_name'] : '',
                                                    'Date' => !empty($sea_road['loading_date']) ? $sea_road['loading_date'] : '',
                                                    'Transfer Name' => !empty($sea_road['transfer_name']) ? $sea_road['transfer_name'] : ''
                                                ];
                                                ?>
                                            <?php else: ?>
                                                <?php
                                                $fields = [
                                                    'Old Company Name' => !empty($sea_road['old_company_name']) ? $sea_road['old_company_name'] : '',
                                                    'Transfer Company Name' => !empty($sea_road['transfer_company_name']) ? $sea_road['transfer_company_name'] : '',
                                                    'Date' => !empty($sea_road['warehouse_date']) ? $sea_road['warehouse_date'] : ''
                                                ];
                                                ?>
                                            <?php endif; ?>

                                            <?php foreach ($fields as $label => $value): ?>
                                                <li><strong><?= $label; ?>:</strong> <?= $value; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                            <?php endif;
                            } ?>
                            <div class="col-md-6">
                                <div class="fs-6 fw-bold">Payment Details</div>
                                <div>
                                    <?php
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
                                            'Date 1' => $payments->partial_date1,
                                            'Report 1' => ucfirst($payments->partial_report1),
                                            'Partial Amount 2' => number_format($partial_amount2, 2),
                                        ];
                                    } elseif (isset($payments->full_advance) && $payments->full_advance === 'full') {
                                        echo '<b>Type:</b> Full Payment<br>';
                                        echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';
                                        $paymentDetails = [
                                            'Type' => 'Full Payment',
                                            'Total Amount' => number_format($total_amount, 2),
                                            'Date' => $payments->full_date,
                                            'Report' => ucfirst($payments->full_report)
                                        ];
                                    } elseif (isset($payments->full_advance) && $payments->full_advance === 'credit') {
                                        echo '<b>Type:</b> Credit Payment<br>';
                                        echo '<b>Total Amount:</b> ' . number_format($total_amount, 2) . '<br>';
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
                            </div> -->




                        <!-- Report Section
                            <div class="mt-3"></div>
                            <!-- <?php if (isset($_fields['sea_road_report'])): ?>
                                    <div class="col-md-12">
                                        <div class="fs-6 fw-bold">Report</div>
                                        <?php echo $_fields['sea_road_report']; ?>
                                    </div>
                                <?php endif; ?> -->
                        <!--  </div> -->
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
                                            <th>Final Amt</th>
                                        <?php } ?>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="goods_table">
                                    <?php $sr_details = 1;
                                    $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = $total_tax_amount = $total_total_with_tax = 0;
                                    $pur_d_q = fetch('transaction_items', array('parent_id' => $id));
                                    while ($details = mysqli_fetch_assoc($pur_d_q)) {
                                        $details_id = $details['id'];
                                        echo '<tr id="toHideRow_' . $details_id . '">';
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
                                    if ($qty_no > 0) {
                                        $accepable_qty = $qty_no;
                                        $added_qty = 0;
                                        echo '<tr>';
                                        echo '<th colspan="2"></th>';
                                        echo '<th class="fw-bold" id="qty_acceptable">' . $qty_no . '</th>';
                                        echo '<th class="fw-bold">' . round($total_kgs, 2) . '</th>';
                                        // echo '<th class="fw-bold">' . round($total_qty_kgs, 2) . '</th>';
                                        echo '<th class="fw-bold">' . round($net_kgs, 2) . '</th>';
                                        // echo '<th colspan="1"></th>';
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
                                    $prepareGoodsReport = '';
                                    $TotalFinalAmount = $final_amount;
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        } ?>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $child_qty = [];
                            $structuredData = [];
                            $commission_items = [];
                            foreach ($_fields['items'] as $item) {
                                $myid = $item['id'];
                                $structuredData[$myid] = $item;
                            }
                            $commission = fetch('commission_items', ['sale_id' => $record['id']]);
                            while ($thisItem = mysqli_fetch_assoc($commission)) {
                                $commission_items[] = $thisItem;
                            }
                            if (count($commission_items) > 0) { ?>
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="text-nowrap">
                                            <th><i class="fa fa-square-o"></i></th>
                                            <th>#</th>
                                            <th>Date</th>
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
                                                <th>Final Amt</th>
                                            <?php } ?>
                                            <th>T</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sr_details = $commission_item = 1;
                                        $qty_no = $qty_kgs = $total_kgs = $total_qty_kgs = $net_kgs = $total = $amount = $final_amount = $total_tax_amount = $total_total_with_tax = 0;
                                        $cEntryIds = [];
                                        foreach ($_fields['items'] as $myitem) {
                                            $hasChildItems = false;
                                            foreach ($commission_items as $oneCitem) {
                                                if ($oneCitem['item_id'] === $myitem['id']) {
                                                    $hasChildItems = true;
                                                    $child_qty[$myitem['id']] = 0;
                                                    break;
                                                }
                                            }

                                            // Only display the parent row if there are child items
                                            if ($hasChildItems) {
                                                $Parent_item =  goodsName($myitem['goods_id']) . '</a> / ' . $myitem['size'] . ' / ' . $myitem['brand'] . ' / ' . $myitem['origin'];
                                                foreach ($commission_items as $oneCitem) {
                                                    if ($oneCitem['item_id'] !== $myitem['id']) {
                                                        continue;
                                                    }
                                                    $cEntryIds[] = $oneCitem['id'];
                                                    $transferred_ = !empty($oneCitem['transferred']) ? json_decode($oneCitem['transferred'], true) : null;
                                                    $child_qty[$myitem['id']] += $oneCitem['qty_no'];
                                                    echo '<tr>';
                                                    echo '<td><input type="checkbox" class="row-checkbox" value="' . $record['id'] . '-' . $oneCitem['id'] . '-' . $commission_item . '"></td>';
                                                    echo '<td>' . $commission_item . '</td>';
                                                    echo '<td class="text-nowrap">' . my_date($oneCitem['created_at']) . '</td>';
                                                    echo '<td>' . ($Parent_item ?? '// // // //') . '</td>';
                                                    $Parent_item = null;
                                                    echo '<td>' . $oneCitem['qty_no'] . '<sub>' . $myitem['qty_name'] . '</sub></td>';
                                                    echo '<td>' . round($oneCitem['total_kgs'], 2) . '</td>';
                                                    echo '<td>' . round($oneCitem['net_kgs'], 2);
                                                    echo '<sub>' . $oneCitem['divide'] . '</sub>';
                                                    echo '</td>';
                                                    echo '<td>' . $oneCitem['total'] . '</td>';
                                                    echo '<td>' . $oneCitem['price'] . '</td>';
                                                    echo '<td>' . round($oneCitem['amount'], 2);
                                                    echo '<sub>' . $oneCitem['currency1'] . '</sub>';
                                                    echo '</td>';
                                                    if ($record['type'] !== 'local') {
                                                        echo '<td class="text-end">' . round($oneCitem['final_amount'], 2);
                                                        echo '<sub>' . $oneCitem['currency2'] . '</sub>';
                                                    } else {
                                                        echo '<td>' . $oneCitem['tax_percent'] . "%";
                                                        echo '<td>' . $oneCitem['tax_amount'];
                                                        echo '<td>' . $oneCitem['total_with_tax'];
                                                    };
                                                    echo '</td>';
                                                    echo '<td>' . ($transferred_ ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-times text-danger"></i>') . '</td>';
                                                    echo '<td>
        <a href="?view=1&t_id=' . $record['id'] . '&viewId=' . $oneCitem['id'] . '"><i class="fas me-1 text-primary fa-eye"></i></a> | 
        <a href="?view=1&t_id=' . $record['id'] . '&editId=' . $oneCitem['id'] . '"><i class="fas me-1 text-warning fa-edit"></i></a> | 
        <a href="?view=1&t_id=' . $record['id'] . '&deleteId=' . $oneCitem['id'] . '"><i class="fas me-1 text-danger fa-trash-alt"></i></a>
      </td>';
                                                    // <a href="print/commission-item-print?t_id=' . $record['id'] . '&item_id=' . $oneCitem['id'] . '&item_sr=' . $commission_item . '&print_type=full">Print</a>
                                                    echo '</tr>';
                                                    $commission_item++;
                                                    $qty_no += $oneCitem['qty_no'];
                                                    $qty_kgs += $oneCitem['qty_kgs'];
                                                    $total_kgs += $oneCitem['total_kgs'];
                                                    $total_qty_kgs += $oneCitem['total_qty_kgs'];
                                                    $net_kgs += $oneCitem['net_kgs'];
                                                    $total += $oneCitem['total'];
                                                    $amount += $oneCitem['amount'];
                                                    $final_amount += $oneCitem['final_amount'];
                                                    $total_tax_amount += (float)$oneCitem['tax_amount'];
                                                    $total_total_with_tax += (float)$oneCitem['total_with_tax'];
                                                }
                                            }
                                            // echo json_encode($structuredData);
                                            if (isset($child_qty[$myitem['id']])) {
                                                $structuredData[$myitem['id']]['qty_no'] -= $child_qty[$myitem['id']];
                                            }
                                            // echo $myitem['id'];
                                            // echo json_encode($child_qty);
                                            // $structuredData[$myitem['id']]['qty_no'] -=  $child_qty[$myitem['id']];
                                        }
                                        if ($qty_no > 0) {
                                            $added_qty = $qty_no;
                                            echo '<tr>';
                                            echo '<th colspan="3"></th>';
                                            echo '<th class="fw-bold" id="qty_added">' . $qty_no . '</th>';
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
                            <?php
                            }
                            ?>
                        </div>
                    </div>

                    <?php

                    if (count($_fields['items']) > 0) {
                        if ($_POST['editId'] != 0) {
                            $item_fields = mysqli_fetch_assoc(fetch('commission_items', ['id' => $_POST['editId']]));
                            // $child_qty[$item_fields['item_id']] -= $item_fields['qty_no'];
                            if (isset($child_qty[$item_fields['item_id']])) {
                                $structuredData[$item_fields['item_id']]['qty_no'] += $item_fields['qty_no'];
                            }
                        } else {
                            $item_fields = ['p_s' => 'p', 'sr' => '', 'quality_report' => '', 'goods_id' => 0, 'size' => '', 'brand' => '', 'origin' => '', 'qty_name' => '', 'qty_no' => 0, 'qty_kgs' => 0, 'total_kgs' => 0, 'empty_kgs' => 0, 'total_qty_kgs' => 0, 'net_kgs' => 0, 'divide' => '', 'weight' => 0, 'total' => 0, 'price' => '', 'currency1' => '', 'rate1' => 0, 'amount' => 0, 'currency2' => 'AED', 'rate2' => '', 'opr' => '*', 'final_amount' => 0, 'tax_percent' => '', 'tax_amount' => '', 'total_with_tax' => '', 'name' => '', 'details1' => '', 'commission_percent' => '', 'commission_amount' => 0, 'additional_expense' => 0, 'details2' => ''];
                        }
                    }
                    if ($_POST['viewId'] == 0) {
                    ?>
                        <div class="card mb-2 transfer-form d-none">
                            <div class="card-body">
                                <form method="post" class="table-form">
                                    <?php
                                    if ($_POST['editId'] != 0) {
                                        echo '<input type="hidden" name="updateId" value="' . $_POST['editId'] . '"/>';
                                    } ?>
                                    <input type="hidden" name="goods_json" id="goods_json" value='<?= json_encode($structuredData); ?>'>
                                    <input type="hidden" name="child_qty" id="child_qty" value='<?= json_encode($child_qty); ?>'>
                                    <input type="hidden" name="sale_id" id="sale_id" value='<?= $id; ?>'>
                                    <div class="row gy-3">
                                        <div class="col-md-9 border-end">
                                            <div class="row gx-1 gy-3">
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        <label for="item_id">Select Good</label>
                                                        <select id="item_id" name="item_id" class="form-select" required>
                                                            <option value="">Select</option>
                                                            <?php
                                                            $allEnteriesDone = false;
                                                            $hideGTableRowById = [];
                                                            foreach ($_fields['items'] as $myitem) {
                                                                if (isset($child_qty[$myitem['id']]) && $child_qty[$myitem['id']] == $myitem['qty_no'] && $_POST['editId'] == 0) {
                                                                    $allEnteriesDone = true;
                                                                    $hideGTableRowById[] = $myitem['id'];
                                                                    continue;
                                                                }
                                                                $allEnteriesDone = false;
                                                                echo '<option value="' . htmlspecialchars($myitem['id']) . '"'
                                                                    . (isset($item_fields['item_id']) && $myitem['id'] === $item_fields['item_id'] ? ' selected' : '') . '>'
                                                                    . htmlspecialchars($myitem['sr']) . '. ' . htmlspecialchars(goodsName($myitem['goods_id']))
                                                                    . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="qty_no">Qty#</label>
                                                        <div class="col-sm">
                                                            <input value="<?php echo $item_fields['qty_no']; ?>" id="qty_no"
                                                                name="qty_no"
                                                                class="form-control currency" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap" for="qty_kgs">Qty
                                                            KGs</label>
                                                        <div class="col-sm">
                                                            <input value="<?php echo $item_fields['qty_kgs']; ?>" id="qty_kgs"
                                                                name="qty_kgs"
                                                                class="form-control currency" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap" for="empty_kgs">Empty
                                                            KGs</label>
                                                        <div class="col-sm">
                                                            <input value="<?php echo $item_fields['empty_kgs']; ?>"
                                                                id="empty_kgs"
                                                                name="empty_kgs" class="form-control currency" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="divide">DIVIDE</label>
                                                        <div class="col-sm">
                                                            <select id="divide" name="divide" class="form-select">
                                                                <?php $divides = array('D/TON' => 'D/TON', 'D/KGs' => 'D/KG', 'D/CARTON' => 'D/CARTON', 'D/PP BAGS' => 'D/PP BAGS');
                                                                foreach ($divides as $item => $val) {
                                                                    $d_sel = $item_fields['divide'] == $val ? 'selected' : '';
                                                                    echo '<option ' . $d_sel . ' value="' . $val . '">' . $item . '</option>';
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="weight">WEIGHT</label>
                                                        <div class="col-sm">
                                                            <input value="<?php echo $item_fields['weight']; ?>" id="weight"
                                                                name="weight"
                                                                class="form-control currency" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="price">PRICE</label>
                                                        <div class="col-sm">
                                                            <select id="price" name="price" class="form-select">
                                                                <?php $prices = array('P/TON' => 'P/TON', 'P/KGs' => 'P/KG', 'P/CARTON' => 'P/CARTON', 'P/PP BAGS' => 'P/PP BAGS');
                                                                foreach ($prices as $item => $val) {
                                                                    $pr_sel = $item_fields['price'] == $val ? 'selected' : '';
                                                                    echo '<option ' . $pr_sel . ' value="' . $val . '">' . $item . '</option>';
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="currency1">Currency</label>
                                                        <div class="col-sm">
                                                            <select id="currency1" name="currency1" class="form-select"
                                                                required>
                                                                <option selected hidden disabled value="">Select</option>
                                                                <?php $currencies = fetch('currencies');
                                                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                    $crr_sel = $crr['name'] == $item_fields['currency1'] ? 'selected' : '';
                                                                    echo '<option ' . $crr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="rate1">RATE</label>
                                                        <div class="col-sm">
                                                            <input value="<?php echo $item_fields['rate1']; ?>" id="rate1"
                                                                name="rate1"
                                                                class="form-control currency" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="name">Name</label>
                                                        <div class="col-sm">
                                                            <input value="<?php echo $item_fields['name']; ?>" id="name"
                                                                name="name"
                                                                class="form-control currency">
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="col-md-8 d-flex gap-1" style="align-items:center;">
                                                    <label class="form-label text-nowrap"
                                                        for="details1">Details1</label>
                                                    <input value="<?php echo $item_fields['details1']; ?>" id="details1"
                                                        name="details1"
                                                        class="form-control currency">
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="commission_percent">Com%</label>
                                                        <div class="col-sm">
                                                            <input value="<?= !empty($item_fields['commission_percent']) ? $item_fields['commission_percent'] : 0; ?>" id="commission_percent"
                                                                name="commission_percent"
                                                                class="form-control currency">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 d-flex gap-1" style="align-items:center;">
                                                    <label class="form-label text-nowrap"
                                                        for="details2">Details2</label>
                                                    <input value="<?php echo $item_fields['details2']; ?>" id="details2"
                                                        name="details2"
                                                        class="form-control currency">
                                                </div>
                                                <?php if ($_POST['editId'] > 0) { ?> <div class="col-md-1">
                                                        <span class="btn btn-sm btn-outline-secondary" id="openOtherPaymentPopup">
                                                            <i class="fa fa-plus"></i>
                                                        </span>
                                                    </div>
                                                <?php } ?>

                                                <!-- JavaScript to Manually Handle Modal -->
                                                <script>
                                                    document.getElementById("openOtherPaymentPopup").addEventListener("click", function() {
                                                        var secondModal = new bootstrap.Modal(document.getElementById("otherPaymentPopup"));
                                                        secondModal.show();
                                                    });

                                                    // Close second modal properly
                                                    document.querySelectorAll(".close-second-modal").forEach(btn => {
                                                        btn.addEventListener("click", function() {
                                                            var secondModalEl = document.getElementById("otherPaymentPopup");
                                                            var secondModal = bootstrap.Modal.getInstance(secondModalEl);
                                                            if (secondModal) {
                                                                secondModal.hide();
                                                            }
                                                        });
                                                    });
                                                </script>

                                                <div class="col-md-4">
                                                    <div class="row g-0">
                                                        <label class="col-sm-4 col-form-label text-nowrap"
                                                            for="additional_expense">Additional Expense</label>
                                                        <div class="col-sm">
                                                            <input value="<?= $item_fields['additional_expense']; ?>" id="additional_expense"
                                                                name="additional_expense"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ($record['type'] !== 'local'): ?>
                                                    <div class="col-md-4">
                                                        <div class="row g-0">
                                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                                for="currency2">Currency</label>
                                                            <div class="col-sm">
                                                                <select id="currency2" name="currency2" class="form-select"
                                                                    required>
                                                                    <option selected hidden disabled value="">Select</option>
                                                                    <?php $currencies = fetch('currencies');
                                                                    while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                        $crr_sel2 = $crr['name'] == $item_fields['currency2'] ? 'selected' : '';
                                                                        echo '<option ' . $crr_sel2 . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="row g-0">
                                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                                for="rate2">Rate</label>
                                                            <div class="col-sm">
                                                                <input value="<?php echo $item_fields['rate2']; ?>" id="rate2"
                                                                    name="rate2"
                                                                    class="form-control currency" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="row g-0">
                                                            <label class="col-sm-4 col-form-label text-nowrap" for="opr">Opr</label>
                                                            <div class="col-sm">
                                                                <select id="opr" name="opr" class="form-select" required>
                                                                    <?php $ops = array('[*]' => '*', '[/]' => '/');
                                                                    foreach ($ops as $opName => $op) {
                                                                        $op_sel = $item_fields['opr'] == $op ? 'selected' : '';
                                                                        echo '<option ' . $op_sel . ' value="' . $op . '">' . $opName . '</option>';
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="col-md-4">
                                                        <div class="row g-0">
                                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                                for="tax_percent">Tax %</label>
                                                            <div class="col-sm">
                                                                <input type="text" value="<?php echo $item_fields['tax_percent']; ?>" id="tax_percent"
                                                                    name="tax_percent"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="row g-0">
                                                            <label class="col-sm-4 col-form-label text-nowrap"
                                                                for="tax_amount">Tax.Amt</label>
                                                            <div class="col-sm">
                                                                <input type="text" value="<?php echo $item_fields['tax_amount']; ?>" id="tax_amount"
                                                                    name="tax_amount"
                                                                    class="form-control" readonly>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="row g-0">
                                                            <!-- <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="total_with_tax">Amt+Tax</label> -->
                                                            <div class="col-sm">
                                                                <input type="hidden" value="<?php echo $item_fields['total_with_tax']; ?>" id="total_with_tax"
                                                                    name="total_with_tax">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-12">
                                                <label class="col-sm-4 col-form-label text-nowrap"
                                                    for="description">Description</label>
                                                <textarea name="description" id="description" rows="4" class="form-control"><?php echo $item_fields['description'] ?? ''; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <table class="table table-sm">
                                                <tbody class="text-nowrap">
                                                    <?php
                                                    echo '<tr><th class="fw-normal">TOTAL KGs </th><th><span id="total_kgs_span"></span></th></tr>';
                                                    echo '<tr><th class="fw-normal">TOTAL QTY KGs </th><th><span id="total_qty_kgs_span"></span></th></tr>';
                                                    echo '<tr><th class="fw-normal">NET KGs </th><th><span id="net_kgs_span"></span></th></tr>';
                                                    echo '<tr><th class="fw-normal">TOTAL </th><th><span id="total_span"></span></th></tr>';
                                                    echo '<tr><th class="fw-normal">AMOUNT  </th><th><span id="amount_span"></span></th></tr>';
                                                    echo '<tr><th class="fw-normal">Com.Amt  </th><th><span id="commission_span">0</span></th></tr>';
                                                    echo '<tr><th class="fw-normal">Other.Amt  </th><th><span id="other_span">0</span></th></tr>';
                                                    echo '<tr><th class="fw-normal">Rem.Amt  </th><th><span id="rem_span">0</span></th></tr>';
                                                    if ($record['type'] !== 'local') {
                                                        echo '<tr><th class="fw-normal text-danger">FINAL  </th><th><span id="final_amount_span"></span></th></tr>';
                                                    } else {
                                                        echo '<tr><th class="fw-normal text-danger">Amt+Tax  </th><th><span id="total_with_tax_span">0</span></th></tr>';
                                                    };
                                                    ?>
                                                </tbody>
                                            </table>
                                            <input value="<?php echo $item_fields['total_kgs']; ?>" id="total_kgs"
                                                name="total_kgs" type="hidden">
                                            <input value="<?php echo $item_fields['total_qty_kgs']; ?>" id="total_qty_kgs"
                                                name="total_qty_kgs"
                                                type="hidden">
                                            <input value="<?php echo $item_fields['net_kgs']; ?>" id="net_kgs" name="net_kgs"
                                                type="hidden">
                                            <input value="<?php echo $item_fields['total']; ?>" id="total" name="total"
                                                type="hidden">
                                            <input value="<?php echo $item_fields['amount']; ?>" id="amount" name="amount" type="hidden">
                                            <input value="<?php echo $item_fields['commission_amount']; ?>" id="commission_amount" name="commission_amount" type="hidden">
                                            <input value="<?php echo $item_fields['final_amount']; ?>" id="final_amount"
                                                name="final_amount" type="hidden">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <button name="recordSubmit" id="recordSubmit" type="submit"
                                                    class="btn btn-dark" <?= $allEnteriesDone ? 'disabled' : ''; ?>>Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="hidden_item_id" value="<?php echo $item_id ?? 0; ?>">
                                </form>
                            </div>
                        </div>



                    <?php } else { ?>
                        <?php
                        $sCom = mysqli_fetch_assoc(fetch('commission_items', ['id' => $_POST['viewId']]));
                        $ddd = ucfirst($_fields['p_s_name']) . ' ' . ' #' . $_fields['sr'] . ', Type: ' . ucfirst($_fields['type']) . ' Bill Transfer, Quantity: ' . $sCom['qty_no'] . ' ';

                        ?>
                        <div class="card">
                            <div class="card-body p-2">
                                <form method="post">
                                    <input type="hidden" name="com_item_id" value="<?= $sCom['id']; ?>">
                                    <div class="row gx-1 gy-3 table-form mb-3">
                                        <div class="col-md-3">
                                            <small class="fw-bold text-danger" id="p_response"></small>
                                            <div class="input-group position-relative">
                                                <label for="khaata_no1" class="text-success">Dr. A/c</label>
                                                <input name="dr_khaata_no" id="khaata_no1" required class="form-control bg-transparent"
                                                    value="<?php echo $_fields['cr_acc']; ?>">
                                            </div>
                                            <input type="hidden" name="dr_khaata_id" id="p_khaata_id" value="<?= $_fields['cr_acc_id'] ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <small class="fw-bold text-danger" id="p_response"></small>
                                            <div class="input-group position-relative">
                                                <label for="khaata_no2" class="text-danger">Cr. A/c</label>
                                                <input name="cr_khaata_no" id="khaata_no2" required class="form-control bg-transparent"
                                                    value="<?php echo $_fields['dr_acc']; ?>">
                                            </div>
                                            <input type="hidden" name="cr_khaata_id" id="s_khaata_id" value="<?= $_fields['dr_acc_id'] ?>">
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
                                                <label for="transfer_amount" class="mb-0">Amount</label>
                                                <input value="<?= round($sCom['final_amount'], 2); ?>" id="transfer_amount"
                                                    readonly
                                                    name="transfer_amount" class="form-control" required tabindex="-1">
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
                                            <input type="hidden" name="check_full_payment" value="<?= $payments['full_advance'] === 'full' ? 'true' : 'false'; ?>">
                                            <button name="ttrFirstSubmit" type="submit"
                                                class="btn btn-primary w-100 btn-sm"><i class="fa fa-upload"></i>Transfer
                                            </button>
                                        </div>
                                        <input type="hidden" name="p_id_hidden" value="<?php echo $record['id']; ?>">
                                        <input type="hidden" name="p_sr" value="<?php echo $record['sr']; ?>">
                                        <input type="hidden" name="type" value="<?php echo $record['type']; ?>">
                                    </div>
                                    <?php if ($record['khaata_tr1'] != '') {
                                        $rozQ = fetch('roznamchaas', array('r_type' => 'Business', 'transfered_from_id' => $record['id'], 'transfered_from' => $_POST['type'] . '_' . $record['type'] . '_' . $sCom['id']));
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
                    <?php }
                    if ($_POST['page'] !== "bill-transfer") { ?>
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
                                                <!-- <th colspan="1">Actions</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchase_reports as $key => $value): ?>
                                                <tr>
                                                    <td class="text-nowrap fw-bold" colspan="4"><?php echo ucwords(str_replace('_', ' ', $key)); ?></td>
                                                    <td class="" colspan="7"><?php echo nl2br(htmlspecialchars($value)); ?></td>
                                                    <!-- <th colspan="1" class="text-nowrap">
                                                        <a href="<?= $_POST['type']; ?>-add?deletePurchaseReport=<?= urlencode($key); ?>&p_hidden_id=<?= $record['id']; ?>&t_id=<?= $record['id']; ?>&type=<?= $record['type']; ?>" class="btn btn-sm btn-outline-danger py-0 px-1 mx-2">
                                                            <i class="fa fa-trash-alt"></i>
                                                        </a>
                                                        <input type="hidden" id="<?= htmlspecialchars($key . $record['id']); ?>" value="<?= htmlspecialchars($key); ?>">
                                                        <textarea class="d-none" id="<?= htmlspecialchars($key . $record['id'] . '_report'); ?>"><?= htmlspecialchars($value); ?></textarea>
                                                        <a href="<?= $_POST['type']; ?>-add?id=<?= $record['id']; ?>&type=<?= $record['type']; ?>" class="btn btn-sm btn-outline-primary py-0 px-1 mx-2">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>

                                                    </th> -->
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
                <?php
                $com_items = fetch('commission_items', ['sale_id' => $record['id']]);
                $all_transferred = false;
                while ($item = mysqli_fetch_assoc($com_items)) {
                    if (!empty($item['transferred'])) {
                        $all_transferred = true;
                    } else {
                        $all_transferred = false;
                    }
                }
                if ($all_transferred) { ?>
                    <form method="post">
                        <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                        <input type="hidden" name="TotalFinalAmount" value="<?php echo $TotalFinalAmount; ?>">
                        <button name="transferToFinal" type="submit" class="btn btn-dark btn-sm w-100 mt-3">
                            TRANSFER
                        </button>
                    </form>
                <?php } ?>

                <div class="bottom-buttons">
                    <div class="px-2">
                        <button id="printEnteriesBtn" class="btn btn-sm btn-success">PRINT</button>
                        <?php if ($_POST['viewId'] == 0) { ?>
                            <button class="btn btn-warning btn-sm" onclick="document.querySelector('.transfer-form').classList.toggle('d-none');">Toggle Form</button>
                        <?php } ?>
                        <?php $update_url = $_fields['type'] == 'booking' ? $_POST['page'] . '-add' : ($_fields['type'] == 'market' ? $_POST['page'] . '-market-add' : $_POST['page'] . '-local-add');
                        if ($_POST['page'] !== "bill-transfer" && $_POST['page'] !== 'general-stock-form' && isset($_POST['type'])) {
                        ?>
                            <a href="<?php echo $_POST['type'] . '-add?id=' . $id . '&type=' . $_fields['type']; ?>" class="btn btn-dark btn-sm w-100 mt-2">UPDATE</a>
                        <?php } ?>
                        <!-- <a href="print/<?= $_POST['page']; ?>-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
                            target="_blank" class="btn btn-dark btn-sm w-100 mt-2">PRINT</a> -->
                        <?php if ($_fields['locked'] == 0 && $_POST['page'] !== 'general-stock-form') { ?>
                            <form method="post" onsubmit="return confirm('Are you sure to delete?');">
                                <input type="hidden" name="p_id_hidden" value="<?php echo $id; ?>">
                                <input type="hidden" name="p_sr" value="<?= $record['sr']; ?>">
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
        <!-- Modal Structure -->
        <div class="modal fade" id="otherPaymentPopup" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered"> <!-- Extra-large & centered modal -->
                <div class="modal-content border border-2">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Additional Amount Transfer</h5>
                        <button type="button" class="btn-close close-second-modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="m-4">
                            <div class="row gx-3 gy-4 align-items-center">
                                <!-- Purchaser Account -->
                                <div class="col-md-2">
                                    <small class="fw-bold text-dark d-none my-1" id="p_acc_name"></small>
                                    <label for="p_acc" class="form-label fw-bold text-danger">Cr. Account</label>
                                    <input type="text" value="" id="p_acc" name="p_acc_no"
                                        onkeyup="searchAcc('#p_acc')" tabindex="-1" class="form-control form-control-sm" required
                                        placeholder="Enter Cr. Account">

                                    <input type="hidden" name="p_acc_id" id="p_acc_id" value="">
                                </div>

                                <!-- Seller Account -->
                                <div class="col-md-2">
                                    <small class="fw-bold text-dark d-none my-1" id="s_acc_name"></small>
                                    <label for="s_acc" class="form-label fw-bold text-success">Dr. Account</label>
                                    <input type="text" value="" id="s_acc" name="s_acc_no"
                                        onkeyup="searchAcc('#s_acc')" tabindex="-1" class="form-control form-control-sm" required
                                        placeholder="Enter Dr. Account">
                                    <input type="hidden" name="s_acc_id" id="s_acc_id" value="">
                                </div>

                                <!-- Currency -->
                                <div class="col-md-2">
                                    <label for="currency1" class="form-label fw-bold">Primary Currency</label>
                                    <select id="currency1" name="currency1" class="form-select form-select-sm" required>
                                        <option value="" hidden>Select</option>
                                        <?php $currencies = fetch('currencies');
                                        while ($crr = mysqli_fetch_assoc($currencies)) {
                                            // $sel_curr = $roznamcha['currency1'] == $crr['name'] ? 'selected' : '';
                                            echo '<option value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                        } ?>
                                    </select>
                                </div>

                                <!-- Amount -->
                                <div class="col-md-2">
                                    <label for="otheramount" class="form-label fw-bold">Amount</label>
                                    <input type="text" id="otheramount" name="amount" class="form-control form-control-sm" onkeyup="mylastAmount()"
                                        readonly required placeholder="0.00">
                                </div>

                                <!-- Secondary Currency and Rate -->
                                <div class="col-md-4">
                                    <div class="row gx-2">
                                        <div class="col-7">
                                            <label for="currency2" class="form-label fw-bold">Secondary Currency</label>
                                            <select id="currency2" name="currency2" class="form-select form-select-sm" required>
                                                <option value="" hidden>Select</option>
                                                <?php $currencies = fetch('currencies');
                                                while ($crr = mysqli_fetch_assoc($currencies)) {
                                                    // $sel_curr2 = $roznamcha['currency2'] == $crr['name'] ? 'selected' : '';
                                                    echo '<option value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-5">
                                            <label for="rate" class="form-label fw-bold">Rate</label>
                                            <input type="text" name="rate" id="otherrate" class="form-control form-control-sm" required
                                                onkeyup="mylastAmount()" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>

                                <!-- Operation -->
                                <div class="col-md-2">
                                    <label for="opr" class="form-label fw-bold">Operation</label>
                                    <select name="opr" class="form-select form-select-sm" id="otheropr" required onchange="mylastAmount()">
                                        <?php $ops = array('[*]' => '*', '[/]' => '/');
                                        foreach ($ops as $opName => $op) {
                                            // $sel_op = $roznamcha['opr'] == $op ? 'selected' : '';
                                            echo '<option value="' . $op . '">' . $opName . '</option>';
                                        } ?>
                                    </select>
                                </div>

                                <!-- Final Amount and Date -->
                                <div class="col-md-4">
                                    <div class="row gx-2">
                                        <div class="col-6">
                                            <label for="final_amount" class="form-label fw-bold">Final Amount</label>
                                            <input type="text" name="final_amount" id="otherfinal_amount" class="form-control form-control-sm" required
                                                readonly tabindex="-1" placeholder="0.00">
                                        </div>
                                        <div class="col-6">
                                            <label for="transfer_date" class="form-label fw-bold">Transfer Date</label>
                                            <input type="date" class="form-control form-control-sm" id="transfer_date" name="transfer_date"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="col-md-6">
                                    <label for="details" class="form-label fw-bold">Details</label>
                                    <input type="text" name="details" id="details" class="form-control form-control-sm" placeholder="Enter transaction details" value="">
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-2">
                                    <button name="PaymentSubmit" type="submit" id="SubmitForm"
                                        class="btn btn-primary btn-sm "><i class="fa-solid fa-money-bill-transfer"></i> Transfer</button>
                                </div>

                                <!-- Hidden Inputs -->
                                <input type="hidden" name="id" value="<?= $record['id']; ?>">
                                <!-- <input type="hidden" name="com_id" value="<?= $_POST['editId']; ?>"> -->
                            </div>
                            <?php
                            $rozQ = fetch('roznamchaas', array('r_type' => 'Other Amt', 'transfered_from_id' => $record['id'], 'transfered_from' => 'sales-commission-form'));
                            if (mysqli_num_rows($rozQ) > 0) { ?>
                                <table class="table table-sm table-bordered my-3">
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

                                        <?php
                                        $rid_delete_array = [];
                                        while ($roz = mysqli_fetch_assoc($rozQ)) {
                                            $rid_delete_array[] = $roz['r_id'];
                                            $dr = $cr = 0; ?>
                                            <input type="hidden" value="<?php echo $roz['r_date']; ?>"
                                                id="temp_transfer_date">
                                            <input type="hidden" value="<?php echo $roz['r_id']; ?>"
                                                name="r_id[]">
                                            <input type="hidden" value="<?php echo $roz['branch_serial']; ?>"
                                                name="b_serial[]">
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
                                <a href="?DeleteOtherPaymentEntry=true&rids=<?= implode('~', $rid_delete_array); ?>&t_id=<?= $record['id']; ?>" class="btn btn-sm btn-danger text-end">Delete Entry</a>
                            <?php
                            } ?>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary close-second-modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            let remaining;
            $(document).ready(function() {
                let hideRows = JSON.parse('<?= json_encode($hideGTableRowById); ?>');
                $.each(hideRows, function(index, rowId) {
                    let someId = "#toHideRow_" + rowId;
                    $(someId).hide();
                });
                if (<?= $_POST['editId'] !== 0; ?>) {
                    finalAmount();
                }
                $('#qty_no,#qty_kgs,#empty_kgs,#weight,#rate1,#rate2,#opr,#commission_percent,#additional_expense').on('change', function() {
                    finalAmount();
                    // if (remaining === undefined) {
                    //     remaining = JSON.parse($('#child_qty').val())[$('#item_id').val()];
                    //     if ($('#qty_no').val() > remaining) {
                    //         disableButton('recordSubmit');
                    //     } else {
                    //         enableButton('recordSubmit');
                    //     }
                    // }
                });
                $('#item_id').change(function() {
                    let item_id = $(this).val();
                    fillInputs(item_id);
                    remaining = $('#qty_no').val();
                    finalAmount();

                });
            });
            let printRows = [];
            $('.row-checkbox').change(function() {
                printRows = []; // Reset array to store only checked values
                $('.row-checkbox:checked').each(function() {
                    printRows.push($(this).val()); // Add only checked checkboxes
                });
            });
            $('#printEnteriesBtn').click(function() {
                let enteriesString = printRows.join('~');
                window.location.href = 'print/commission-item-print?print_enteries=' + enteriesString + '&print_type=full';
            });


            function fillInputs(item_id) {
                let goods_json = JSON.parse($('#goods_json').val());
                let child_qty = JSON.parse($('#child_qty').val());
                let item = goods_json[item_id];
                $('#qty_name').val(item.qty_name);
                $('#qty_no').val(item.qty_no);
                $('#qty_kgs').val(item.qty_kgs);
                $('#empty_kgs').val(item.empty_kgs);
                $('#divide').val(item.divide);
                $('#weight').val(item.weight);
                $('#price').val(item.price);
                $('#currency1').val(item.currency1);
                $('#rate1').val(item.rate1);
                $('#currency2').val(item.currency2);
                $('#rate2').val(item.rate2);
                $('#opr').val(item.opr);
                $('#tax_percent').val(item.tax_percent);
                $('#tax_amount').val(item.tax_amount);
                $('#total_with_tax').val(item.total_with_tax);
                $('#total_kgs').val(item.total_kgs);
                $('#total_qty_kgs').val(item.total_qty_kgs);
                $('#net_kgs').val(item.net_kgs);
                $('#total').val(item.total);
                $('#amount').val(item.amount);
                $('#final_amount').val(item.final_amount);
                $('#total_kgs_span').text(item.total_kgs);
                $('#total_qty_kgs_span').text(item.total_qty_kgs);
                $('#net_kgs_span').text(item.net_kgs);
                $('#total_span').text(item.total);
                $('#amount_span').text(item.amount);
                $('#final_amount_span').text(item.final_amount);
                $('#total_with_tax_span').text(item.total_with_tax);
            }

            function finalAmount() {
                var qty_no = parseFloat($("#qty_no").val()) || 0;
                var qty_kgs = parseFloat($("#qty_kgs").val()) || 0;

                var total_kgs = qty_no * qty_kgs;
                $("#total_kgs").val(total_kgs);
                $("#total_kgs_span").text(total_kgs);

                var empty_kgs = parseFloat($("#empty_kgs").val()) || 0;
                var total_qty_kgs = qty_no * empty_kgs;
                $("#total_qty_kgs").val(total_qty_kgs);
                $("#total_qty_kgs_span").text(total_qty_kgs);

                var net_kgs = total_kgs - total_qty_kgs;
                $("#net_kgs").val(net_kgs);
                $("#net_kgs_span").text(net_kgs);

                var weight = parseFloat($("#weight").val()) || 0;
                var total = 0;

                if (!isNaN(weight) && weight !== 0 && !isNaN(net_kgs)) {
                    total = net_kgs / weight;
                    total = total.toFixed(3);
                }

                $("#total").val(isNaN(total) ? '' : total);
                $("#total_span").text(isNaN(total) ? '' : total);

                var rate1 = parseFloat($("#rate1").val()) || 0;
                var final_amount = 0;
                var amount = 0;

                if (!isNaN(rate1) && rate1 !== 0 && !isNaN(total)) {
                    amount = total * rate1;
                    amount = amount.toFixed(3);
                    final_amount = amount;
                }
                $("#amount").val(isNaN(amount) ? '' : amount);
                $("#amount_span").text(isNaN(amount) ? '' : amount);
                var commission_amt = amount * (parseFloat($('#commission_percent').val()) / 100);
                amount -= commission_amt;
                $("#commission_amount").val(isNaN(commission_amt) ? '' : commission_amt.toFixed(2));
                $("#commission_span").text(isNaN(commission_amt) ? '' : commission_amt.toFixed(2));
                $('#other_span').text($('#additional_expense').val());
                amount -= $('#additional_expense').val();
                $('#otheramount').val($('#additional_expense').val());
                $('#rem_span').text(amount.toFixed(2));
                updateTaxAndTotal(amount);
                //if ($("#is_qty").prop('checked') == true) {
                var rate2 = parseFloat($("#rate2").val()) || 0;
                let operator = $('#opr').find(":selected").val();

                if (!isNaN(rate2) && rate2 !== 0 && !isNaN(amount)) {
                    final_amount = (operator === '/') ? amount / rate2 : rate2 * amount;
                    final_amount = final_amount.toFixed(3);
                }
                //}

                $("#final_amount").val(isFinite(final_amount) ? final_amount : '');
                $("#final_amount_span").text(isFinite(final_amount) ? final_amount : '');

                if (final_amount <= 0 || isNaN(final_amount) || !isFinite(final_amount)) {
                    disableButton('recordSubmit');
                } else {
                    enableButton('recordSubmit');
                }
                // console.log(qty_no, pareremaining);
                if (qty_no > parseInt(remaining)) {
                    disableButton('recordSubmit');
                } else {
                    enableButton('recordSubmit');
                }
            }

            function updateTaxAndTotal(amount) {
                let taxPercent = parseFloat($('#tax_percent').val()) || 0;
                let taxAmount = (amount * (taxPercent / 100)).toFixed(2);
                let totalWithTax = (amount + parseFloat(taxAmount)).toFixed(2);
                $('#tax_amount').val(taxAmount != 0 ? taxAmount : '');
                $('#total_with_tax').val(totalWithTax);
                $('#total_with_tax_span').text(totalWithTax);
            }
        </script>
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
                    if (balance >= 1 || <?= $_POST['viewId'] > 0 ? 1 : 0; ?>) {
                        // if (final_amount <= balance + 0.5) {
                        //     enableButton('recordSubmit');
                        // } else {
                        //     disableButton('recordSubmit');
                        // }
                        enableButton('recordSubmit');
                    } else {
                        disableButton('recordSubmit');
                    }
                }
            }

            function mylastAmount() {
                let amount = $("#otheramount").val();
                let rate = $("#otherrate").val();
                let opr = $('#otheropr').find(":selected").val();
                let final_amount;

                if (amount && rate) { // Ensure both amount and rate have values
                    if (opr === "/") {
                        final_amount = Number(amount) / Number(rate);
                    } else {
                        final_amount = Number(amount) * Number(rate);
                    }
                    final_amount = final_amount.toFixed(2);
                    $("#otherfinal_amount").val(final_amount);
                }
            }

            function fetchKhaata(inputField, khaataId, responseId, prefix, khaataImageId, recordSubmitId) {
                let khaata_no = $(inputField).val();
                $.ajax({
                    url: 'ajax/fetchSingleKhaata.php',
                    type: 'post',
                    data: {
                        khaata_no: khaata_no
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success === true) {
                            enableButton(recordSubmitId);
                            $(khaataId).val(response.messages['khaata_id']);
                            $(prefix + '_khaata_no').text(khaata_no);
                            $(prefix + '_khaata_name').text(response.messages['khaata_name']);
                            $(prefix + '_b_name').text(response.messages['b_name']);
                            $(prefix + '_c_name').text(response.messages['name']);
                            $(prefix + '_business_name').text(response.messages['business_name']);
                            $(prefix + '_address').text(response.messages['address']);
                            $(prefix + '_comp_name').text(response.messages['comp_name']);
                            var details = {
                                indexes: response.messages['indexes'],
                                vals: response.messages['vals']
                            };
                            $(prefix + '_contacts').html(displayKhaataDetails(details));
                            $(khaataImageId).attr("src", response.messages['image']);
                            $(recordSubmitId).prop('disabled', false);
                            lastAmount();
                            $(responseId).text('');
                        }
                        if (response.success === false) {
                            disableButton(recordSubmitId);
                            $(responseId).text('INVALID');
                            $(prefix + '_khaata_no').text('---');
                            $(prefix + '_khaata_name').text('---');
                            $(prefix + '_c_name').text('---');
                            $(prefix + '_b_name').text('---');
                            $(prefix + '_comp_name').text('---');
                            $(prefix + '_business_name').text('---');
                            $(prefix + '_address').text('---');
                            $(prefix + '_contacts').text('');
                            $(khaataImageId).attr("src", 'assets/images/logo-placeholder.png');
                            $(khaataId).val(0);
                        }
                    }
                });
            }

            function displayKhaataDetails(details) {
                var html = ''; // Initialize an empty string to store HTML

                if (details.indexes && details.vals) {
                    var indexes = JSON.parse(details.indexes);
                    var vals = JSON.parse(details.vals);

                    if (Array.isArray(indexes) && Array.isArray(vals)) {
                        var count = Math.min(indexes.length, vals.length);

                        for (var i = 0; i < count; i++) {
                            var key = indexes[i];
                            var value = vals[i];
                            // Construct the HTML string
                            html += '<b class="text-dark">' + (key) + '</b>' + value + '<br>';
                        }
                    }
                }

                return html; // Return the constructed HTML string
            }

            $(document).on('keyup', "#khaata_no1", function(e) {
                fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");
            });
            fetchKhaata("#khaata_no1", "#p_khaata_id", "#p_response", "#p", "#p_khaata_image", "recordSubmit");
            $(document).on('keyup', "#khaata_no2", function(e) {
                fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");
            });
            fetchKhaata("#khaata_no2", "#s_khaata_id", "#s_response", "#s", "#s_khaata_image", "recordSubmit");

            function searchAcc(Acc) {
                var AccNo = $(Acc).val().toUpperCase();
                $.ajax({
                    type: 'POST',
                    url: 'ajax/fetchAgentDetails.php',
                    data: 'acc_no=' + AccNo,
                    success: function(html) {
                        let data = JSON.parse(html).data;
                        if (data.acc_no !== '') {
                            $(Acc).addClass('is-valid');
                            $(Acc).removeClass('is-invalid');
                            $(Acc + '_name').html('( ' + data.acc_name + ' )').removeClass('d-none');
                            $(Acc + '_id').val(data.row_id);
                        } else {
                            $(Acc).removeClass('is-valid');
                            $(Acc).addClass('is-invalid');
                        }
                    },
                    error: function(err) {

                    }
                });
            }
        </script>
        </div>