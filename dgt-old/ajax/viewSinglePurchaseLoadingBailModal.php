<?php require_once '../connection.php';
$d_id = $_POST['d_id'];
$source = $_POST['source'];
if ($d_id > 0) {
    if ($source == 'purchase') {
        $records2q = fetch('purchase_details', array('id' => $d_id));
        $record2 = mysqli_fetch_assoc($records2q);
        $id = $record2['parent_id'];
        $recordq = fetch('purchases', array('id' => $id));
        $record = mysqli_fetch_assoc($recordq);
    } else {
        $records2q = fetch('sale_details', array('id' => $d_id));
        $record2 = mysqli_fetch_assoc($records2q);
        $id = $record2['parent_id'];
        $recordq = fetch('sales', array('id' => $id));
        $record = mysqli_fetch_assoc($recordq);
    }
    $sea_road = $record['sea_road']; ?>
    <form method="post" onsubmit="confirm('Are you sure to save loading details?');" class="table-form">
        <?php echo $sea_road;
        if ($record['type'] == 'booking') {
            $data = bailDetails($d_id, $source);
            if ($sea_road == 'sea') { ?>
                <div class="row gx-1 gy-2">
                    <div class="col-12">
                        <div class="input-group">
                            <label for="bail_report">Bail Report</label>
                            <input class="form-control" id="bail_report" name="bail_report"
                                   value="<?php echo $data['bail_report']; ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="loading_country">Loading Country</label>
                            <input value="<?php echo $data['loading_country']; ?>" class="form-control"
                                   id="loading_country"
                                   name="loading_country">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="loading_port">Loading Port</label>
                            <input value="<?php echo $data['loading_port']; ?>" class="form-control" id="loading_port"
                                   name="loading_port">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="receiving_country">Receiving Country</label>
                            <input value="<?php echo $data['receiving_country']; ?>" class="form-control"
                                   id="receiving_country" name="receiving_country">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="receiving_port">Receiving Port</label>
                            <input value="<?php echo $data['receiving_port']; ?>" class="form-control"
                                   id="receiving_port"
                                   name="receiving_port">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="loading_date">Loading Date</label>
                            <input value="<?php echo $data['loading_date']; ?>" type="date" class="form-control"
                                   id="loading_date" name="loading_date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="receiving_date">Receiving Date</label>
                            <input value="<?php echo $data['receiving_date']; ?>" type="date" class="form-control"
                                   id="receiving_date" name="receiving_date">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="freight">Freight Prepaid</label>
                            <input value="<?php echo $data['freight']; ?>" class="form-control" id="freight"
                                   name="freight"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="bail_no">Bail No.</label>
                            <input value="<?php echo $data['bail_no']; ?>" class="form-control" id="bail_no"
                                   name="bail_no"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="container_name">Container Name</label>
                            <input value="<?php echo $data['container_name']; ?>" class="form-control"
                                   id="container_name"
                                   name="container_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="container_no">Container No.</label>
                            <input value="<?php echo $data['container_no']; ?>" class="form-control" id="container_no"
                                   name="container_no">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="container_size">Container Size</label>
                            <input value="<?php echo $data['container_size']; ?>" class="form-control"
                                   id="container_size"
                                   name="container_size">
                        </div>
                    </div>
                    <!--<div class="col-md-3">
                        <div class="d-flex">
                            <div class="input-group">
                                <label for="qty_name">Qty Name</label>
                                <input class="form-control" id="qty_name" name="qty_name">
                                <label for="qty_no">Qty#</label>
                                <input class="form-control" id="qty_no" name="qty_no" required onkeyup="grossWt()">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <label for="kgs">KGs</label>
                            <input class="form-control" id="kgs" name="kgs" required onkeyup="grossWt()">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <label for="total_kgs">Gross Wt.</label>
                            <input class="form-control" id="total_kgs" name="total_kgs" required readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <label for="empty_wt">Empty Wt.</label>
                            <input class="form-control" id="empty_wt" name="empty_wt" required onkeyup="grossWt()">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <label for="total_empty_wt">Total Empty Wt.</label>
                            <input class="form-control" id="total_empty_wt" name="total_empty_wt" required readonly
                                   tabindex="-1">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <label for="saaf_wt">Net Wt.</label>
                            <input class="form-control" id="saaf_wt" name="saaf_wt" required readonly>
                        </div>
                    </div>-->
                </div>
                <div class="row gx-0 mb-2 gy-2">
                    <div class="col-12">
                        <p class="text-muted mb-0 mt-2">Shipping Lane Details</p>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="loading_shipper_address">Name</label>
                            <input value="<?php echo $data['loading_shipper_address']; ?>" class="form-control"
                                   id="loading_shipper_address" name="loading_shipper_address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="receiving_shipper_address" class="col-md-auto col-form-label">Address</label>
                            <input value="<?php echo $data['receiving_shipper_address']; ?>" class="form-control"
                                   id="receiving_shipper_address" name="receiving_shipper_address">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ship_comp">Company</label>
                            <input value="<?php echo $data['ship_comp']; ?>" class="form-control" id="ship_comp"
                                   name="ship_comp">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ship_phone">Phone</label>
                            <input value="<?php echo $data['ship_phone']; ?>" class="form-control" id="ship_phone"
                                   name="ship_phone"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ship_email">Email</label>
                            <input value="<?php echo $data['ship_email']; ?>" type="email" class="form-control"
                                   id="ship_email" name="ship_email">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ship_wa">WhatsApp</label>
                            <input value="<?php echo $data['ship_wa']; ?>" class="form-control" id="ship_wa"
                                   name="ship_wa">
                        </div>
                    </div>

                </div>
            <?php } else { ?>
                <div class="row gx-1 gy-2">
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="loading_date">Loading Date</label>
                            <input value="<?php echo $data['loading_date']; ?>" type="date" class="form-control"
                                   id="loading_date" name="loading_date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="receiving_date">Receiving Date</label>
                            <input value="<?php echo $data['receiving_date']; ?>" type="date" class="form-control"
                                   id="receiving_date" name="receiving_date">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="truck_container">Status</label>
                            <select id="truck_container" name="truck_container" class="form-select">
                                <?php $tc_array = array('Open Truck' => 'open_truck', 'Container' => 'container');
                                foreach ($tc_array as $str => $value) {
                                    //$tc_selected = $truck_container == $value ? 'selected' : '';
                                    echo '<option value="' . $value . '">' . $str . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="truck_no">Truck No.</label>
                            <input value="<?php //echo $data['truck_no']; ?>" class="form-control" id="truck_no"
                                   name="truck_no">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="truck_name">Truck Name</label>
                            <input value="<?php //echo $data['truck_no']; ?>" class="form-control" id="truck_name"
                                   name="truck_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="driver_name">Driver Name</label>
                            <input value="<?php //echo $data['driver_name']; ?>" class="form-control" id="driver_name"
                                   name="driver_name">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="driver_phone">Phone</label>
                            <input value="<?php //echo $data['driver_phone']; ?>" class="form-control" id="driver_phone"
                                   name="driver_phone">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="input-group">
                            <label for="bail_report">Bail Report</label>
                            <input class="form-control" id="bail_report" name="bail_report"
                                   value="<?php echo $data['bail_report']; ?>">
                        </div>
                    </div>
                </div>
                <div class="row gx-0 mb-2 gy-2">
                    <div class="col-12">
                        <p class="text-muted mb-0 mt-2">Transport Details</p>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="loading_shipper_address">Transport Name</label>
                            <input value="<?php echo $data['loading_shipper_address']; ?>" class="form-control"
                                   id="loading_shipper_address" name="loading_shipper_address">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group">
                            <label for="receiving_shipper_address">Address</label>
                            <input value="<?php echo $data['receiving_shipper_address']; ?>" class="form-control"
                                   id="receiving_shipper_address" name="receiving_shipper_address">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ship_phone">Phone</label>
                            <input value="<?php echo $data['ship_phone']; ?>" class="form-control" id="ship_phone"
                                   name="ship_phone"
                            >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ship_email">Email</label>
                            <input value="<?php echo $data['ship_email']; ?>" type="email" class="form-control"
                                   id="ship_email" name="ship_email">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <label for="ship_wa">WhatsApp</label>
                            <input value="<?php echo $data['ship_wa']; ?>" class="form-control" id="ship_wa" name="ship_wa">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <label for="transport_report">Transport Report</label>
                            <input class="form-control" id="transport_report" name="transport_report" value="<?php //echo $data['transport_report']; ?>">
                        </div>
                    </div>
                </div>
            <?php }
        } else {
            $data = bailDetails($d_id, $source, 'local'); ?>
            <div class="row gx-1 gy-2 mb-4">
                <div class="col-12">
                    <div class="input-group">
                        <label for="bail_report">Report</label>
                        <input class="form-control" id="bail_report" name="bail_report" required
                               value="<?php echo $data['bail_report']; ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label for="loading_date">Loading Date</label>
                        <input value="<?php echo $data['loading_date']; ?>" type="date" class="form-control"
                               id="loading_date" name="loading_date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label for="receiving_date">Receiving Date</label>
                        <input value="<?php echo $data['receiving_date']; ?>" type="date" class="form-control"
                               id="receiving_date" name="receiving_date">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label for="truck_no">Truck No.</label>
                        <input value="<?php echo $data['truck_no']; ?>" class="form-control" id="truck_no"
                               name="truck_no">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label for="driver_name">Driver Name</label>
                        <input value="<?php echo $data['driver_name']; ?>" class="form-control" id="driver_name"
                               name="driver_name">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <label for="driver_phone">Phone</label>
                        <input value="<?php echo $data['driver_phone']; ?>" class="form-control" id="driver_phone"
                               name="driver_phone">
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="">
            <button type="submit" name="bailDetailsSubmit" class="btn btn-sm btn-success">Submit Details</button>
        </div>
        <input type="hidden" name="id_hidden" value="<?php echo $id; ?>">
        <input type="hidden" name="d_id_hidden" value="<?php echo $d_id; ?>">
        <input type="hidden" name="source_hidden" value="<?php echo $source; ?>">
    </form>
<?php } ?>

<script>function grossWt() {
        let qty_no = document.getElementById("qty_no").value;
        let kgs = document.getElementById("kgs").value;
        let empty_wt = document.getElementById("empty_wt").value;
        let total_kgs = document.getElementById("total_kgs").value;
        document.getElementById("total_kgs").value = Number(qty_no) * Number(kgs);
        document.getElementById("total_empty_wt").value = Number(qty_no) * Number(empty_wt);
        let total_empty_wt = document.getElementById("total_empty_wt").value;
        document.getElementById("saaf_wt").value = Number(total_kgs) - Number(total_empty_wt);
    }</script>