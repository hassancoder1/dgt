<?php
require_once '../connection.php';

$loading_id = isset($_POST['loading_id']) ? (int)$_POST['loading_id'] : 0;
$bill_of_entry_no = isset($_POST['bill_of_entry_no']) ? mysqli_real_escape_string($connect, $_POST['bill_of_entry_no']) : '';
$level = isset($_POST['level']) ? $_POST['level'] : '';
$billNumber = (int)$_POST['billNumber'];

if ($loading_id > 0 && !empty($bill_of_entry_no)) {
    $user = $_SESSION['username'];

    // Construct the SQL query
    $sql = "SELECT * 
            FROM general_loading 
            WHERE JSON_EXTRACT(agent_details, '$.bill_of_entry_no') = '$bill_of_entry_no' ";

    // Add a condition if the user is not admin
    if ($user !== 'admin') {
        $sql .= "AND JSON_EXTRACT(agent_details, '$.ag_id') = '$user'";
    }
    $sql .= " AND JSON_EXTRACT(agent_details, '$.ag_billNumber') = '$billNumber' ORDER BY created_at DESC LIMIT 1";
    // Execute the query
    $Loadings = mysqli_query($connect, $sql);
    echo $sql;
    // Fetch and process the result
    while ($SingleLoading = mysqli_fetch_assoc($Loadings)) {
        $id = $SingleLoading['id'];
        $agentDetails = json_decode($SingleLoading['agent_details'], true);
        if (!empty($agentDetails) && isset($agentDetails['bill_of_entry_no']) && $agentDetails['bill_of_entry_no'] === $bill_of_entry_no) {
            $SingleLoading = $SingleLoading;
            $Agent = $agentDetails;
?>
            <div class="row">
                <div class="col-md-10 order-0 content-column">
                    <div class="card my-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pb-2 mb-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div><b><?= "Sr#" . $SingleLoading['sr_no']; ?></b></div>
                                        <div><b>Purchase Date </b><?php echo my_date($SingleLoading['p_date']); ?></div>
                                        <div><b>Type </b><?php echo badge(strtoupper($SingleLoading['p_type']), 'dark'); ?></div>
                                        <div><b>Branch </b><?php echo $SingleLoading['p_branch']; ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row gy-1 border-bottom py-1">
                                <div class="col-md-12">
                                    <span class="fs-6 fw-bold">By <?= ucwords(json_decode($SingleLoading['shipping_details'], true)['transfer_by']); ?></span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fs-6 fw-bold">Loading Details</div>
                                    <div>
                                        <?php
                                        $l_date = json_decode($SingleLoading['loading_details'], true)['loading_date'];
                                        echo '<b>Loading Date: ' . $l_date . '</b><br>';
                                        $bl_no = $SingleLoading['bl_no']; // B/L No

                                        // Assuming you have an active MySQLi connection in $conn
                                        $query = "
    SELECT 
        bl_no, 
        COUNT(DISTINCT JSON_UNQUOTE(JSON_EXTRACT(goods_details, '$.container_no'))) AS unique_container_count 
    FROM 
        general_loading 
    WHERE 
        bl_no = '$bl_no' 
    GROUP BY 
        bl_no
";

                                        $result = $connect->query($query);

                                        if ($result) {
                                            $row = $result->fetch_assoc();
                                            echo '<b>B/L No: ' . ($row['bl_no'] ?? $bl_no) . '</b><br>' .
                                                '<b>Containers: </b>' . ($row['unique_container_count'] ?? 0);
                                        } else {
                                            echo '<b>B/L No: ' . $bl_no . '</b> - No containers found';
                                        }

                                        ?>
                                    </div>
                                </div>







                                <?php if (!empty($Agent)): ?>
                                    <div class="col-md-3">
                                        <div class="fs-6 fw-bold">Agent Details</div>
                                        <div>
                                            <?php
                                            foreach ($Agent as $key => $value) {
                                                if ($key === 'ag_acc_no' || $key === 'ag_name' || $key === 'ag_id') {
                                                    echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . $value . "<br>";
                                                };
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($Agent)): ?>
                                    <div class="col-md-3">
                                        <div>
                                            <?php
                                            foreach ($Agent as $key => $value) {
                                                if ($key === 'received_date' || $key === 'clearing_date' || $key === 'loading_truck_number' || $key === 'truck_returning_date') {
                                                    echo '<b>' . ucwords(str_replace('_', ' ', str_replace('ag_', 'Agent ', $key))) . ': </b>' . $value . "<br>";
                                                };
                                            }
                                            ?>
                                        </div>
                                    </div>
                                <?php
                                endif;
                                $result = fetch('agent_payments', array('loading_bl_no' => $SingleLoading['bl_no']));
                                if (!$result) {
                                    echo "Error fetching data: " . mysqli_error($connect);
                                    exit;
                                }
                                $editRow = null;
                                $rows = [];
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $rows[] = $row;
                                }
                                $rowCount = count($rows);
                                $firstRow = $rowCount > 0 ? $rows[0] : null;
                                if (isset($_POST['edit']) && $_POST['edit'] === 'true') {
                                    $id = $_POST['id'];
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                        $result = fetch('agent_payments', array('loading_bl_no' => $SingleLoading['bl_no']));
                        if (!$result) {
                            echo "Error fetching data: " . mysqli_error($connect);
                            exit;
                        }
                        ?>
                        <?php
                        if (!$editRow) {
                            $editRow = ['id' => '', 'loading_id' => $SingleLoading['id'], 'loading_bl_no' => $SingleLoading['bl_no'], 'bill_no' => $Agent['ag_billNumber'], 'date' => date('M-y-d'), 'bill_details' => '', 'sr_no' => $rowCount + 1, 'details' => '', 'quantity' => '', 'rate' => '', 'total' => '', 'tax_amount' => '', 'tax_percentage' => '', 'grand_total' => ''];
                        }
                        ?>
                        <form method="post" class="table-form px-2" enctype="multipart/form-data">
                            <input type="hidden" name="bill_of_entry_no" value="<?= $bill_of_entry_no; ?>">
                            <input type="hidden" name="loading_id" value="<?= $editRow['loading_id']; ?>">
                            <input type="hidden" name="loading_bl_no" value="<?= $editRow['loading_bl_no']; ?>">
                            <h5 class="text-primary">Agent Payments Form</h5>
                            <div class="row g-3">
                                <?php $combineColumns = !empty($firstRow) ? $firstRow : $editRow; ?>
                                <?php echo !empty($firstRow) ? '<input type="hidden" name="firstRowID" value="' . $firstRow['id'] . '">' : ''; ?>
                                <div class="col-md-2">
                                    <label for="bill_no" class="form-label">Bill No#</label>
                                    <input type="text" name="bill_no" id="bill_no" required readonly value="<?= $combineColumns['bill_no']; ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="text" name="date" readonly id="date" required value="<?= $combineColumns['date']; ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-7">
                                    <label for="bill_details" class="form-label">Bill Details</label>
                                    <input type="text" name="bill_details" id="bill_details" required value="<?= $combineColumns['bill_details']; ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-1 mt-4">
                                    <input type="file" id="agent_file" name="agent_file[]" class="d-none" multiple>
                                    <span class="btn cursor btn-sm btn-success mt-3" onclick="document.getElementById('agent_file').click();"><i class="fa fa-paperclip"></i> File</span>
                                </div>
                                <div class="col-md-1">
                                    <label for="sr_no" class="form-label">Sr#</label>
                                    <input type="text" name="sr_no" id="sr_no" required value="<?= $editRow['sr_no']; ?>" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-4">
                                    <!-- text Area  -->
                                    <label for="details" class="form-label">Details</label>
                                    <input type="text" name="details" id="details" required class="form-control form-control-sm" value="<?= $editRow['details']; ?>">
                                </div>

                                <div class="col-md-1">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="text" name="quantity" id="quantity" required class="form-control form-control-sm" value="<?= $editRow['quantity']; ?>" onkeyup="calcGrand('#quantity','#rate', 'multi', '#total')">
                                </div>

                                <div class="col-md-1">
                                    <label for="rate" class="form-label">Rate</label>
                                    <input type="text" name="rate" id="rate" required class="form-control form-control-sm" value="<?= $editRow['rate']; ?>" onkeyup="calcGrand('#quantity','#rate', 'multi', '#total')">
                                </div>

                                <div class="col-md-1">
                                    <label for="total" class="form-label">Total</label>
                                    <input type="text" name="total" id="total" required class="form-control form-control-sm" value="<?= $editRow['total']; ?>" onkeyup="calcGrand('#total', '#tax', 'plus', '#grand_total')">
                                </div>

                                <div class="col-md-1">
                                    <label for="tax-percentage" class="form-label">Tax %</label>
                                    <input type="text" name="tax-percentage" id="tax-percentage" required class="form-control form-control-sm" onkeyup="calcTax('#tax-percentage', '#total', '#tax')" value="<?= $editRow['tax_percentage']; ?>">
                                </div>

                                <div class="col-md-1">
                                    <label for="tax" class="form-label">Tax</label>
                                    <input type="text" name="tax" id="tax" required class="form-control form-control-sm" value="<?= $editRow['tax_amount']; ?>" onkeyup="calcGrand('#total', '#tax', 'plus', '#grand_total')">
                                </div>


                                <div class="col-md-1">
                                    <label for="grand_total" class="form-label">G.Total</label>
                                    <input type="text" name="grand_total" id="grand_total" required class="form-control form-control-sm" value="<?= $editRow['grand_total']; ?>">
                                </div>

                                <div class="col-md-1">
                                    <?php if ($editRow['id'] !== '') { ?>
                                        <input type="hidden" name="id" value="<?= $editRow['id']; ?>">
                                        <button name="UpdateAgPaymentEntry" id="UpdateAgPaymentEntry" type="submit"
                                            class="btn btn-primary btn-sm rounded-0">
                                            Update
                                        </button>
                                    <?php } else { ?>
                                        <button name="AgentPaymentsFormSubmit" id="AgentFormSubmit" type="submit"
                                            class="btn btn-primary btn-sm rounded-0 mt-4">
                                            Save
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive px-3">

                            <table class="table mt-2 px-2 table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th class="bg-dark text-white">Sr#</th>
                                        <th class="bg-dark text-white" colspan="3">Details</th>
                                        <th class="bg-dark text-white">Quantity</th>
                                        <th class="bg-dark text-white">Rate</th>
                                        <th class="bg-dark text-white">Total</th>
                                        <th class="bg-dark text-white">Tax %</th>
                                        <th class="bg-dark text-white">Tax Amount</th>
                                        <th class="bg-dark text-white">Grand Total</th>
                                        <th class="bg-dark text-white">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_amount = $total_bill_amount = $total_tax = 0;
                                    if ($rowCount > 0) {
                                        $firstRowId = $rows[0]['id'] ?? null;
                                        $child_ids = implode(',', array_filter(array_column($rows, 'id'), fn($id) => $id !== $firstRowId));

                                        foreach ($rows as $row) {
                                            if (isset($id) && $row['id'] === $id) $editRow = $row;
                                    ?>



                                            <td class="border border-dark"><?= htmlspecialchars($row['sr_no'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark" colspan="3"><?= htmlspecialchars($row['details'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark"><?= htmlspecialchars($row['quantity'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark"><?= htmlspecialchars($row['rate'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark"><?= htmlspecialchars($row['total'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark"><?= htmlspecialchars($row['tax_percentage'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark"><?= htmlspecialchars($row['tax_amount'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark"><?= htmlspecialchars($row['grand_total'], ENT_QUOTES); ?></td>
                                            <td class="border border-dark text-center">
                                                <a href="agent-payments-form?edit=true&id=<?= $row['id']; ?>&loadingID=<?= $loading_id; ?>&bill_of_entry_no=<?= $bill_of_entry_no; ?>" class="text-primary me-2">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <?php if ($row['id'] !== $firstRowId): ?>
                                                    <a href="agent-payments-form?deleteAgPaymentEntry=true&id=<?= $row['id']; ?>" class="text-danger" onclick="return confirm('Are you sure you want to delete this record?');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            </tr>
                                    <?php
                                            $total_amount += $row['total'];
                                            $total_tax += $row['tax_amount'];
                                            $total_bill_amount += $row['grand_total'];
                                        }
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-2 order-1 fixed-sidebar table-form" style="top: 60px !important;">
                    <div class="my-3">
                        <!-- Total Details  -->
                        <b>T. AMOUNT: </b><span id="show_total_amount"><?= isset($total_amount) ? $total_amount : ''; ?></span><br>
                        <b>T. TAX: </b><span id="show_total_amount"><?= isset($total_tax) ? $total_tax : ''; ?></span><br>
                        <b>T.B. AMOUNT: </b><span id="show_total_amount"><?= isset($total_bill_amount) ? $total_bill_amount : ''; ?></span>
                    </div>
                    <div class="d-flex" style="align-items: center;">
                        <a href="print/agent-payments-form?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>"
                            target="_blank" class="btn btn-dark btn-sm me-2">PRINT</a>
                        <form method="post">
                            <?php
                            if (!empty($firstRow)) {
                                if (json_decode($firstRow['transfer_details'], true)['transferred_to_admin'] === false):
                            ?>
                                    <input type="hidden" name="total_amount" value="<?= $total_amount; ?>">
                                    <input type="hidden" name="total_tax_amount" value="<?= $total_tax; ?>">
                                    <input type="hidden" name="total_bill_amount" value="<?= $total_bill_amount; ?>">
                                    <input type="hidden" name="parent_payment_id" value="<?= $firstRow['id']; ?>">
                                    <input type="hidden" name="child_ids" value="<?= $child_ids; ?>">
                                    <input type="hidden" name="existing_data" value='<?= $firstRow['transfer_details']; ?>'>
                                    <button type="submit" name="TransferBillToAdmin" class="btn btn-dark btn-sm">Transfer</button>
                                <?php else: ?>
                                    <b class="text-success"><i class="fa fa-check"></i> Transferred to Admin</b>
                            <?php endif;
                            } ?>
                        </form>
                    </div>

                </div>


    <?php
        } else {
            echo "<p>No matching record found for the given Bill of Entry No.</p>";
        }
    }
}
    ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        function agentDetails() {
            var agentAccNo = $('#ag_acc_no').val().toUpperCase();
            $.ajax({
                type: 'POST',
                url: 'ajax/fetchAgentDetails.php',
                data: 'agent_acc_no=' + agentAccNo,
                success: function(html) {
                    let data = JSON.parse(html).data;
                    if (data.ag_acc_no !== '') {
                        $('#ag_acc_no').addClass('is-valid');
                        $('#ag_acc_no').removeClass('is-invalid');
                        $('#ag_name').val(data.ag_name);
                        $('#ag_id').val(data.ag_id);
                    } else {
                        $('#ag_acc_no').removeClass('is-valid');
                        $('#ag_acc_no').addClass('is-invalid');
                        $('#ag_name').val('');
                        $('#ag_id').val('');
                    }
                },
                error: function(err) {

                }
            });
        }

        function toggleDownloadMenu(event, iconElement) {
            event.preventDefault();
            document.querySelectorAll('.attachment-menu').forEach(menu => {
                menu.style.display = 'none';
            });
            const currentMenu = iconElement.nextElementSibling;
            if (currentMenu.style.display === 'none' || currentMenu.style.display === '') {
                currentMenu.style.display = 'block';
            } else {
                currentMenu.style.display = 'none';
            }
        }

        function calcGrand(value1, value2, Operator, totalInput) {
            let grand_Total = 0;
            grand_Total = Operator === 'multi' ? parseFloat($(value1).val()) * parseFloat($(value2).val()) : parseFloat($(value1).val()) + parseFloat($(value2).val());
            $(totalInput).val(isNaN(grand_Total) ? '' : grand_Total);
        }

        function calcTax(taxPercentageSelector, totalSelector, taxSelector) {
            var taxPercentage = parseFloat($(taxPercentageSelector).val().replace('%', '')) || 0;
            $(taxSelector).val(((taxPercentage / 100) * (parseFloat($(totalSelector).val()) || 0)).toFixed(2));
            calcGrand('#total', '#tax', 'plus', '#grand_total');
        }
    </script>