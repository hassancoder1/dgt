<?php $surrender_json = json_decode($record['surrender_json']);
$srJson = array(
    'sr_date' => $surrender_json->sr_date,
    'sr_bill_no' => $surrender_json->sr_bill_no,
    'sr_shipping_lane' => $surrender_json->sr_shipping_lane,
    'sr_container_no' => $surrender_json->sr_container_no,
    'sr_container_name' => $surrender_json->sr_container_name,
    'sr_port_date' => $surrender_json->sr_port_date,
    'sr_free_days' => $surrender_json->sr_free_days,
    'sr_port_name' => $surrender_json->sr_port_name
); ?>
<div class="row gx-0 gy-2">
    <div class="col-lg-2">
        <div class="input-group">
            <label class="input-group-text urdu">سلنڈر تاریخ</label>
            <input value="<?php echo $srJson['sr_date']; ?>" class="form-control" disabled>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="input-group">
            <label class="input-group-text urdu">بل نمبر</label>
            <input class="form-control" disabled value="<?php echo $srJson['sr_bill_no']; ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="input-group">
            <label class="input-group-text urdu">شپنگ لین کانام</label>
            <input class="form-control" disabled
                   value="<?php echo $srJson['sr_shipping_lane']; ?>">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="input-group">
            <label class="input-group-text urdu">کنٹینر نمبر</label>
            <input class="form-control" disabled
                   value="<?php echo $srJson['sr_container_no']; ?>">
        </div>

    </div>
    <div class="col-lg-3">
        <div class="input-group">
            <label class="input-group-text urdu">کنٹینرنام</label>
            <input class="form-control" disabled
                   value="<?php echo $srJson['sr_container_name']; ?>">
        </div>
    </div>
    <div class="col-lg-2">
        <div class="input-group">
            <label class="input-group-text urdu">پورٹ پہنچ تاریخ</label>
            <input value="<?php echo $srJson['sr_port_date']; ?>" class="form-control" disabled>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="input-group">
            <label class="input-group-text urdu">فری دن</label>
            <input class="form-control" disabled value="<?php echo $srJson['sr_free_days']; ?>">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="input-group">
            <label class="input-group-text urdu">پورٹ نام</label>
            <input class="form-control" disabled value="<?php echo $srJson['sr_port_name']; ?>">
        </div>
    </div>
</div>