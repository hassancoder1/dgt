<?php require_once '../connection.php';
//$purchase_id = $_POST['id'];
$pd_id = $_POST['pd_id'];
$title = $_POST['title'];
$type = $_POST['type'];
if ($pd_id > 0) {
    $records2q = fetch('purchase_details', array('id' => $pd_id));
    $rows = mysqli_num_rows($records2q);
    $record2 = mysqli_fetch_assoc($records2q);
    $purchase_id = $record2['parent_id'];
    $recordq = fetch('purchases', array('id' => $purchase_id));
    $record = mysqli_fetch_assoc($recordq);
    ?>
    <form method="post">
        <div class="row table-form gx-1 gy-3">
            <div class="col-md-6">
                <div class="input-group">
                    <label for="doc_rec_date">Documents Receiving</label>
                    <input type="date" id="doc_rec_date" name="doc_rec_date" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <label for="ctr_rec_date">Container Receiving</label>
                    <input type="date" id="ctr_rec_date" name="ctr_rec_date" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <label for="bill_no">Entry Bill#</label>
                    <input type="text" id="bill_no" name="bill_no" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <label for="truck_no">Loading Truck#</label>
                    <input type="text" id="truck_no" name="truck_no" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <label for="ctr_return_date">Container Return</label>
                    <input type="date" id="ctr_return_date" name="ctr_return_date" class="form-control" required>
                </div>
            </div>
            <div class="col-md-12">
                <div class="input-group">
                    <label for="report">Report</label>
                    <textarea id="report" name="report" class="form-control" rows="5" required></textarea>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <button type="submit" class="btn btn-sm btn-dark" id="saveDetailsSubmit" name="saveDetailsSubmit">Save Details
            </button>
        </div>
        <input type="hidden" id="agent-khaata-id" name="khaata_id">
        <input type="hidden" id="party-type" name="type">
        <input type="hidden" name="pd_id_hidden" value="<?php echo $pd_id; ?>">
        <input type="hidden" name="p_id_hidden" value="<?php echo $purchase_id; ?>">
        <input type="hidden" name="created_at" value="<?php echo date('Y-m-d'); ?>">
    </form>
<?php } ?>