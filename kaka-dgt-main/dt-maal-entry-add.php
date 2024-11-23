<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n3">
    <div>
        <h4 class="mb-3 mb-md-0">ڈاؤن ٹرانزٹ مال انٹری</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="dt-maal-entry"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0 pt-0 pb-1 mt-1">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('dt_truck_loadings', array('id' => $id));
            $record = mysqli_fetch_assoc($records); ?>
            <?php include('dt-loading-details.php'); ?>
            <div class="card mt-2 p-2">
                <?php if (isset($_SESSION['response'])) {
                    echo $_SESSION['response'];
                    unset($_SESSION['response']);
                }
                if (!empty($record['sender_receiver'])) {
                    $sender_receiver = json_decode($record['sender_receiver']);
                    $names = array(
                        'dt_sender_id' => $sender_receiver->dt_sender_id,
                        'dt_comp_name' => $sender_receiver->dt_comp_name,
                        'dt_sender_address' => $sender_receiver->dt_sender_address,
                        'dt_sender_mobile' => $sender_receiver->dt_sender_mobile,
                        'dt_sender_owner' => $sender_receiver->dt_sender_owner,
                        'dt_receiver_id' => $sender_receiver->dt_receiver_id,
                        'dt_comp_name_r' => $sender_receiver->dt_comp_name_r,
                        'dt_receiver_address' => $sender_receiver->dt_receiver_address,
                        'dt_receiver_mobile' => $sender_receiver->dt_receiver_mobile,
                        'dt_receiver_owner' => $sender_receiver->dt_receiver_owner
                    );
                } else {
                    $names = array(
                        'dt_sender_id' => 0,
                        'dt_comp_name' => '',
                        'dt_sender_address' => '',
                        'dt_sender_mobile' => '',
                        'dt_sender_owner' => '',
                        'dt_receiver_id' => 0,
                        'dt_comp_name_r' => '',
                        'dt_receiver_address' => '',
                        'dt_receiver_mobile' => '',
                        'dt_receiver_owner' => ''
                    );
                } ?>
                <form id="sender_receiver_form_" method="post">
                    <div class="row ">
                        <div class="col-11">
                            <div class="row gx-0 g-2">
                                <div class="col-md-4 position-relative">
                                    <div class="input-group">
                                        <label for="dt_sender_id" class="input-group-text urdu">مال بھیجنےوالا</label>
                                        <select id="dt_sender_id" name="dt_sender_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $senders = fetch('senders');
                                            while ($sender = mysqli_fetch_assoc($senders)) {
                                                $s_selected = $sender['id'] == $names['dt_sender_id'] ? 'selected' : '';
                                                echo '<option ' . $s_selected . ' value="' . $sender['id'] . '">' . $sender['comp_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseDTSender" class="text-danger urdu position-absolute"
                                           style="top: -10px; left: 20px;"></small>
                                    <input type="hidden" name="dt_comp_name" id="dt_comp_name"
                                           value="<?php echo $names['dt_comp_name'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="dt_sender_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="dt_sender_address" name="dt_sender_address"
                                               class="form-control urdu-2" readonly tabindex="-1"
                                               value="<?php echo $names['dt_sender_address']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="dt_sender_mobile" class="input-group-text urdu">موبائل نمبر</label>
                                        <input type="text" id="dt_sender_mobile" name="dt_sender_mobile"
                                               class="form-control ltr urdu-2 " readonly tabindex="-1"
                                               value="<?php echo $names['dt_sender_mobile']; ?>">
                                        <label for="dt_sender_owner" class="input-group-text urdu">مالک نام</label>
                                        <input type="text" id="dt_sender_owner" name="dt_sender_owner"
                                               class="form-control urdu-2" readonly tabindex="-1"
                                               value="<?php echo $names['dt_sender_owner']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 position-relative">
                                    <div class="input-group">
                                        <label for="dt_receiver_id" class="input-group-text urdu">مال وصول کرنے
                                            والا</label>
                                        <select id="dt_receiver_id" name="dt_receiver_id"
                                                class="form-control border-bottom-0 virtual-select">
                                            <option value="0" selected disabled>انتخاب کریں</option>
                                            <?php $receivers = fetch('receivers');
                                            while ($receiver = mysqli_fetch_assoc($receivers)) {
                                                $r_selected = $receiver['id'] == $names['dt_receiver_id'] ? 'selected' : '';
                                                echo '<option ' . $r_selected . ' value="' . $receiver['id'] . '">' . $receiver['comp_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <small id="responseDTReceiver" class="text-danger urdu position-absolute"
                                           style="top: -10px; left: 20px;"></small>
                                    <input type="hidden" name="dt_comp_name_r" id="dt_comp_name_r">
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="dt_receiver_address" class="input-group-text urdu">پتہ</label>
                                        <input type="text" id="dt_receiver_address" name="dt_receiver_address"
                                               class="form-control urdu-2" readonly tabindex="-1"
                                               value="<?php echo $names['dt_receiver_address']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <label for="dt_receiver_mobile" class="input-group-text urdu">موبائل
                                            نمبر</label>
                                        <input type="text" id="dt_receiver_mobile" name="dt_receiver_mobile"
                                               class="form-control ltr urdu-2" readonly tabindex="-1"
                                               value="<?php echo $names['dt_receiver_mobile']; ?>">
                                        <label for="dt_receiver_owner" class="input-group-text urdu">مالک نام</label>
                                        <input type="text" id="dt_receiver_owner" name="dt_receiver_owner"
                                               class="form-control urdu-2" readonly tabindex="-1"
                                               value="<?php echo $names['dt_receiver_owner']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-1 flex-column justify-content-center d-flex">
                            <input type="hidden" name="tl_id_hidden" value="<?php echo $id; ?>">
                            <button type="submit" name="senderReceiverSubmit" class=" btn btn-dark w-100">محفوظ
                            </button>
                        </div>
                    </div>
                </form>
                <?php if (isset($_POST['senderReceiverSubmit'])) {
                    $tl_id_hidden = $_POST['tl_id_hidden'];
                    $url = 'dt-maal-entry-add?id=' . $tl_id_hidden;
                    $data = array(
                        'sender_receiver' => json_encode($_POST, JSON_UNESCAPED_UNICODE)
                    );
                    $done = update('dt_truck_loadings', $data, array('id' => $_POST['tl_id_hidden']));
                    if ($done) {
                        message('success', $url, 'مال بھیجنے والا / وصول کرنے والا محفوظ ہو گیا ہے۔');
                    } else {
                        message('danger', $url, 'ڈیٹا بیس پرابلم');
                    }
                } ?>
            </div>
            <div class="card mt-2">
                <div class="table-responsive">
                    <form id="insert_form">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>سیریل نمبر</th>
                                <th>جنس نام</th>
                                <th>باردانہ نام</th>
                                <th>باردانہ تعداد</th>
                                <th>فی وزن</th>
                                <th>ٹوٹل وزن</th>
                                <th>خالی وزن</th>
                                <th>ٹوٹل خالی وزن</th>
                                <th>صاف وزن</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <input type="text" id="sr_no" name="sr_no" class="form-control" disabled
                                           value="<?php echo getAutoIncrement('dt_truck_maals'); ?>">
                                </td>
                                <td>
                                    <input type="text" id="jins_name" name="jins_name" placeholder="جنس نام"
                                           autofocus class="form-control input-urdu" required>
                                </td>
                                <td>
                                    <input type="text" id="bardana_name" name="bardana_name"
                                           placeholder="باردانہ نام" class="input-urdu form-control" required>
                                </td>
                                <td>
                                    <input type="text" id="bardana_qty" name="bardana_qty"
                                           placeholder="باردانہ تعداد" onkeyup="totalWt()"
                                           class="form-control currency" required>
                                </td>
                                <td>
                                    <input type="text" id="per_wt" name="per_wt" placeholder="فی وزن"
                                           class="form-control currency" required onkeyup="totalWt()">
                                </td>
                                <script>
                                    function totalWt(e) {
                                        var value = $(e).val();
                                        var id = $(e).attr('id');
                                        var bardana_qty = $("#bardana_qty").val();
                                        var per_wt = $("#per_wt").val();
                                        var total_wt = Number(bardana_qty) * Number(per_wt);
                                        $("#total_wt").val(total_wt);
                                    }
                                </script>
                                <td>
                                    <input type="text" id="total_wt" name="total_wt" placeholder="ٹوٹل وزن"
                                           class="form-control currency" readonly tabindex="-1">
                                </td>
                                <td>
                                    <input type="text" id="empty_wt" name="empty_wt" placeholder="خالی وزن"
                                           class="form-control currency" required onkeyup="totalKWt()">
                                </td>
                                <script>
                                    function totalKWt(e) {
                                        var empty_wt = $("#empty_wt").val();
                                        var bardana_qty = $("#bardana_qty").val();
                                        var total_empty_wt = Number(bardana_qty) * Number(empty_wt);
                                        $("#total_empty_wt").val(total_empty_wt);
                                        var ww = $("#total_wt").val();
                                        //alert("Total wt=" + ww + " Total empty wt=" + total_empty_wt);
                                        var saaf_wt = Number(ww) - Number(total_empty_wt);
                                        $("#saaf_wt").val(saaf_wt);
                                    }
                                </script>
                                <td>
                                    <input type="text" id="total_empty_wt" name="total_empty_wt" readonly
                                           tabindex="-1" placeholder="ٹوٹل خالی وزن" class="form-control currency">
                                </td>
                                <td>
                                    <input type="text" id="saaf_wt" name="saaf_wt" readonly tabindex="-1"
                                           placeholder="صاف وزن" class="form-control currency">
                                </td>
                                <td>
                                    <button type="submit" name="recordSubmit" id="recordSubmit"
                                            class="btn btn-outline-primary pt-0">محفوظ
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="dt_truck_maals_id" id="dt_truck_maals_id" value="0">
                        <input type="hidden" name="dt_truck_maals_action" id="dt_truck_maals_action" value="insert">
                        <input type="hidden" name="dt_tl_id" value="<?php echo $id; ?>">
                    </form>
                </div>
            </div>
            <div class="card mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th> سیریل نمبر</th>
                            <th>جنس نام</th>
                            <th>باردن نام</th>
                            <th>باردن تعداد</th>
                            <th>فی وزن</th>
                            <th>ٹوٹل وزن</th>
                            <th>خالی باردن وزن</th>
                            <th>ٹوٹل خالی وزن</th>
                            <th>صاف وزن</th>
                        </tr>
                        </thead>
                        <tbody id="records_table">
                        <?php $maals = fetch('dt_truck_maals', array('dt_tl_id' => $id));
                        $x = 0;
                        $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = 0;
                        while ($maal = mysqli_fetch_assoc($maals)) {
                            $json = json_decode($maal['json_data']); ?>
                            <tr class="row-py-0 cursor-pointer" id="<?php echo $maal['id']; ?>"
                                onclick="maalEntryRowEdit(this)">
                                <td><?php echo $maal['id']; ?></td>
                                <td><?php echo $json->jins_name; ?></td>
                                <td><?php echo $json->bardana_name; ?></td>
                                <td><?php echo $json->bardana_qty; ?></td>
                                <td><?php echo $json->per_wt; ?></td>
                                <td><?php echo $json->total_wt; ?></td>
                                <td><?php echo $json->empty_wt; ?></td>
                                <td><?php echo $json->total_empty_wt; ?></td>
                                <td><?php echo $json->saaf_wt; ?></td>
                                <!--<td><input type="button" id="update_row" name="<?php /*echo $maal['id'];*/ ?>" value="update" onclick="updateFunction(event);"></td>-->
                            </tr>
                            <?php $x++;
                            $bardana_qty += $json->bardana_qty;
                            $per_wt += $json->per_wt;
                            $total_wt += $json->total_wt;
                            $empty_wt += $json->empty_wt;
                            $total_empty_wt += $json->total_empty_wt;
                            $saaf_wt += $json->saaf_wt;
                        } ?>
                        <tr class="row-py-0 bg-info bg-opacity-25 bold">
                            <td><?php echo $x; ?></td>
                            <td colspan="2"></td>
                            <td><?php echo $bardana_qty; ?></td>
                            <td><?php echo $per_wt; ?></td>
                            <td><?php echo $total_wt; ?></td>
                            <td><?php echo $empty_wt; ?></td>
                            <td><?php echo $total_empty_wt; ?></td>
                            <td><?php echo $saaf_wt; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <?php if ($x > 0 && !empty($record['sender_receiver']) && $record['is_saved'] == 0) { ?>
                    <a class="btn btn-primary btn-icon-text m-4 w-50 text-center" onclick="saveDTMaalEntry(this)"
                       id="<?php echo $id; ?>" data-url="dt-maal-entry">
                        <i class="btn-icon-prepend" data-feather="check-square"></i>محفوظ کریں</a>
                <?php } ?>
            </div>
        <?php } else {
            echo '<script>window.location.href="dt-maal-entry";</script>';
        } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    /*dt sender*/
    $(function () {
        var dt_sender_id = $('#dt_sender_id').val();
        senderAjax(dt_sender_id);
    });
    $('#dt_sender_id').change(function () {
        var dt_sender_id = $(this).val();
        senderAjax(dt_sender_id);
    });
    function senderAjax(dt_sender_id=null) {
        $.ajax({
            url: 'ajax/fetchSingleDTSender.php',
            type: 'post',
            data: {dt_sender_id: dt_sender_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    console.log(response);
                    $("#dt_comp_name").val(response.messages['comp_name']);
                    $("#dt_sender_address").val(response.messages['address']);
                    $("#dt_sender_mobile").val(response.messages['mobile']);
                    $("#dt_sender_owner").val(response.messages['comp_owner_name']);
                    $("#responseDTSender").text('');
                }
                if (response.success === false) {
                    $("#responseDTSender").text('مال بھیجنےوالا انتخاب کریں');
                }
            }
        });
    }
    /*dtreceiver*/
    $(function () {
        var dt_receiver_id = $('#dt_receiver_id').val();
        receiverAjax(dt_receiver_id);
    });
    $('#dt_receiver_id').change(function () {
        var dt_receiver_id = $(this).val();
        receiverAjax(dt_receiver_id);
    });
    function receiverAjax(dt_receiver_id=null) {
        $.ajax({
            url: 'ajax/fetchSingleDTReceiver.php',
            type: 'post',
            data: {dt_receiver_id: dt_receiver_id},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    $("#dt_comp_name_r").val(response.messages['comp_name']);
                    $("#dt_receiver_address").val(response.messages['address']);
                    $("#dt_receiver_mobile").val(response.messages['mobile']);
                    $("#dt_receiver_owner").val(response.messages['comp_owner_name']);
                    $("#responseDTReceiver").text('');
                }
                if (response.success === false) {
                    $("#responseDTReceiver").text('مال وصول کرنے والا انتخاب کریں');
                }
            }
        });
    }
</script>
<script>
    function maalEntryRowEdit(e) {
        var id = $(e).attr('id');
        $.ajax({
            url: "ajax/fetchSingleDTTruckMaalEntry.php",
            method: "POST",
            data: {id: id},
            success: function (data) {
                $(e).addClass('bg-light');
                $(e).siblings().removeClass("bg-light");
                var dd = $.parseJSON(data);
                console.log(dd.messages);
                $('#recordSubmit').text("تبدیل");
                $('#recordSubmit').addClass("btn-outline-danger");
                $('#recordSubmit').removeClass("btn-outline-primary");
                $('#sr_no').val(id);
                $('#dt_truck_maals_id').val(id);
                $('#dt_truck_maals_action').val("update");
                $('#jins_name').val(dd.messages.jins_name);
                $('#bardana_name').val(dd.messages.bardana_name);
                $('#bardana_qty').val(dd.messages.bardana_qty);
                $('#per_wt').val(dd.messages.per_wt);
                $('#total_wt').val(dd.messages.total_wt);
                $('#empty_wt').val(dd.messages.empty_wt);
                $('#total_empty_wt').val(dd.messages.total_empty_wt);
                $('#saaf_wt').val(dd.messages.saaf_wt);
            },
            error: function () {

            }
        });
    }
</script>
<script>
    $('#insert_form').on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "ajax/dtTruckMaalsEntry.php",
            method: "POST",
            data: $('#insert_form').serialize(),
            beforeSend: function () {
                $('#recordSubmit').val("ڈیٹ محفوظ ہورہاہے");
            },
            success: function (data) {
                $('#records_table').html(data);
                $('#insert_form')[0].reset();
                $('#recordSubmit').text("محفوظ");
                $('#recordSubmit').addClass("btn-outline-primary");
                $('#recordSubmit').removeClass("btn-outline-danger");
                var sr_no = $('#sr_no').val();
                sr_no = Number(sr_no) + 1;
                $('#sr_no').val(sr_no);
            }
        });
    });
</script>
<script>
    function saveDTMaalEntry(e) {
        var id = $(e).attr('id');
        var url = $(e).attr('data-url');
        var str = "کیا آپ محفوظ کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n سیریل نمبر:' + id)) {
                window.location.href = 'ajax/saveDTMaalEntry.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>