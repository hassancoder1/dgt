<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = removeFilter('ut-afghan-border');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $username_append = "";
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $date_append = " AND loading_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_POST['username']) && !empty($_POST['username'])) {
        $date_append = "";
        $searchUserName = $_POST['username'];
        $username_append = " AND username = " . "'$searchUserName'" . " ";
    }
} else {
    $date_append = $username_append = "";
}
//$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND is_imp=1 AND is_exp= 0  {$username_append} {$date_append}";
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND border_user_ids != '' {$username_append} {$date_append} ORDER BY border_json ASC ";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>' . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div><h3 class="mb-3 mb-md-0 urdu-2"> افغان بارڈر کلئیرنس</h3></div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-150 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-120 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
        </form>
        <form name="userNameSubmit" method="POST" class="d-flex">
            <div class="input-group wd-150 ms-3 mb-2 mb-md-0 me-2">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">آئی ڈی نام</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="آئی ڈی نام" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
        <!--<a href="" class="btn btn-outline-primary btn-icon-text py-1">
            <i class="btn-icon-prepend" data-feather="file-plus"></i>اندراج
        </a>-->
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="">
                <div class="table-responsive scroll screen-ht" style="overflow-y: inherit">
                    <table class="table table-bordered" id="fix-head-table">
                        <thead>
                        <tr>
                            <th>بیل #</th>
                            <th class="small">لوڈنگ تاریخ</th>
                            <th width="" class="small-2">جنس/شہر</th>
                            <th width="" class="small">وزن</th>
                            <th class="small">بھیجنے والا</th>
                            <th class="small" width="">ایکسپورٹر</th>
                            <th class="small" width="">ایکسپورٹ ایجنٹ</th>
                            <th class="small" width="">امپورٹر</th>
                            <th class="small" width="">امپورٹ ایجنٹ</th>
                            <th class="small-2">بیل رپورٹ</th>
                            <th class="small">تاریخ بارڈر</th>
                            <th class="small">جی ڈی نمبر</th>
                            <th class="small">ٹرک</th>
                            <th>ڈرائیور</th>
                            <th>باردانہ</th>
                            <th>وزن</th>
                            <th class="small">بارڈر رپورٹ</th>
                            <th class="">ٹوٹل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($loading = mysqli_fetch_assoc($records)) {
                            $json = json_decode($loading['border_user_ids']);
                            $json = implode(',', $json);
                            $json = explode(',', $json);
                            $jsonPerms = array();
                            $perms = UTPermissions($userId);
                            if (!empty($perms)) {
                                $perms = json_decode($perms);
                                $jsonPerms = implode(',', $perms);
                                $jsonPerms = explode(',', $jsonPerms);
                            }
                            if ((in_array($userId, $json) && in_array(BORDER, $jsonPerms)) || Administrator()) {
                            } else {
                                continue;
                            }
                            $rowClass = 'border ';
                            if (!empty($loading['khaata_border'])) {
                                $rowClass = '';
                            } else {
                                if (empty($loading['border_json'])) {
                                    $rowClass = 'bg-danger bg-opacity-25 border border-white';
                                } else {
                                    $rowClass = 'bg-warning bg-opacity-25 border border-white';
                                }
                            } ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td class="text-nowrap small-2">
                                    <a class="h5" data-tooltip="افغان بارڈر کلئیرنس کی تفصیل"
                                       data-tooltip-position="left"
                                       href="ut-afghan-border-add?id=<?php echo $loading["id"]; ?>"><?php echo $loading["id"]; ?></a>
                                    <?php
                                    if (!empty($loading['khaata_border']) && !empty($loading['khaata_border_afg_truck'])) {
                                        echo '<a href="ut-expense-transfer?id=' . $loading["id"] . '&type=' . BORDER . '" 
                                        class="btn btn-primary p-0 small urdu-2 me-1"
                                           data-tooltip="بیل نمبر ' . $loading["id"] . ' میں بارڈر خرچہ بل انٹری ہو چکی ہے۔ " 
                                           data-tooltip-position="top right">مکمل شد</a>';
                                        echo '<a href="ut-expense-transfer?id=' . $loading["id"] . '&type=' . BORDER_AFG_TRUCK . '" 
                                        class="btn btn-warning p-0 small urdu-2 "
                                           data-tooltip="بیل نمبر ' . $loading["id"] . ' میں افغانی ٹرک کرایہ انٹری ہو چکی ہے۔ " 
                                           data-tooltip-position="bottom right">مکمل شد</a>';

                                    } else {
                                        if (empty($loading['border_json'])) {
                                            echo '<i class="fa fa-warning text-danger float-end me-2 mt-1" 
                                        data-tooltip="خرچہ ڈالنے سے پہلے افغان بارڈر کلئیرنس کی انٹری کریں " data-tooltip-position="right"></i>';
                                        } else {
                                            echo '<a href="ut-expense-transfer?id=' . $loading["id"] . '&type=' . BORDER . '" 
                                        class="btn btn-primary p-0 small urdu-2 me-1"
                                           data-tooltip="بیل نمبر ' . $loading["id"] . ' میں بارڈر خرچہ انٹری کریں۔ " 
                                           data-tooltip-position="top right">بارڈر بل</a>';
                                            echo '<a href="ut-expense-transfer?id=' . $loading["id"] . '&type=' . BORDER_AFG_TRUCK . '" 
                                        class="btn btn-warning p-0 small urdu-2 "
                                           data-tooltip="بیل نمبر ' . $loading["id"] . ' میں افغانی ٹرک کرایہ انٹری کریں۔ " 
                                           data-tooltip-position="bottom right">افغانی ٹرک</a>';
                                        }
                                    } ?>
                                    <hr class="mt-2 mb-0">
                                    <span class="" data-tooltip="آئی ڈی نام"
                                          data-tooltip-position="left"><?php echo $loading['username']; ?></span>
                                </td>
                                <td class="small-2"><?php echo $loading['loading_date']; ?></td>
                                <td class="small-2 text-nowrap"><?php echo $loading['jins']; ?>
                                    <hr class="mt-2 mb-0"><span class=""><?php echo $loading['loading_city']; ?></span>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <span class="small-2">ٹوٹل</span><?php echo $loading['total_wt']; ?>
                                    <hr class="mt-2 mb-0">
                                    <span class="small-2">صاف</span><?php echo $loading['saaf_wt']; ?>
                                </td>
                                <td class="small-2">
                                    <?php $sender_json = json_decode($loading['sender_receiver']);
                                    echo getTableDataByIdAndColName('senders', $sender_json->sender_id, 'comp_owner_name') . '<br>';
                                    echo '<span class="small-2 text-nowrap" dir="ltr">' . $sender_json->sender_mobile . '</span>'; ?>
                                </td>
                                <td class="small-2">
                                    <?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'name'); ?><br>
                                    <span dir="ltr" class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'mobile'); ?></span>
                                </td>
                                <td class="small-2 text-wrap">
                                    <?php $expAgent = getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_name');
                                    echo $expAgent; ?><br>
                                    <span dir="ltr" class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_mobile'); ?></span>
                                </td>
                                <td class="small-2">
                                    <?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'name'); ?>
                                    <br><span class="small-2 text-nowrap"
                                              dir="ltr"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'mobile'); ?></span>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php $impAgent = getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_name');
                                    echo $impAgent; ?>
                                    <br><span class="small-2"
                                              dir="ltr"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_mobile'); ?></span>
                                </td>
                                <td class="small-2"><?php echo $loading['report']; ?></td>
                                <?php if (empty($loading['border_json'])) {
                                    $borderJson = array(
                                        'border_receiving_date' => '',
                                        'border_unloading_date' => '',
                                        'border_gd_no' => '',
                                        'border_truck_no' => '',
                                        'border_truck_name' => '',
                                        'border_drive_name' => '',
                                        'border_drive_mobile' => '',
                                        'border_bardana_qty' => '',
                                        'border_bardana_name' => '',
                                        'border_total_wt' => '',
                                        'border_saaf_wt' => '',
                                        'border_report' => ''
                                    );
                                } else {
                                    $border_json = json_decode($loading['border_json']);
                                    $borderJson = array(
                                        'border_receiving_date' => '<span> بارڈر پہنچ : </span>' . $border_json->border_receiving_date . '<hr class="mt-2 mb-1">',
                                        'border_unloading_date' => '<span> ان لوڈنگ : </span>' . $border_json->border_unloading_date,
                                        'border_gd_no' => '<span>جی ڈی نمبر: </span>' . $border_json->border_gd_no . '<hr class="mt-2 mb-1">',
                                        'border_truck_no' => '<span>ٹرک نمبر: </span>' . $border_json->border_truck_no . '<hr class="mt-2 mb-1">',
                                        'border_truck_name' => '<span>ٹرک نام: </span>' . $border_json->border_truck_name,
                                        'border_drive_name' => '<span></span>' . $border_json->border_drive_name . '<hr class="mt-2 mb-1">',
                                        'border_drive_mobile' => '<span dir="ltr">' . $border_json->border_drive_mobile . '</span>',
                                        'border_bardana_qty' => ' <span> تعداد : </span> ' . $border_json->border_bardana_qty . '<hr class="mt-2 mb-1">',
                                        'border_bardana_name' => ' <span>نام: </span> ' . $border_json->border_bardana_name,
                                        'border_total_wt' => '<span>ٹوٹل: </span>' . $border_json->border_total_wt . '<hr class="mt-2 mb-1">',
                                        'border_saaf_wt' => ' <span>صاف: </span>' . $border_json->border_saaf_wt,
                                        'border_report' => $border_json->border_report
                                    );
                                }
                                ?>
                                <td class="small-2 text-nowrap">
                                    <?php echo $borderJson['border_receiving_date']; ?>
                                    <?php echo $borderJson['border_unloading_date']; ?>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $borderJson['border_gd_no']; ?>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $borderJson['border_truck_no']; ?>
                                    <?php echo $borderJson['border_truck_name']; ?>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $borderJson['border_drive_name']; ?>
                                    <?php echo $borderJson['border_drive_mobile']; ?>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $borderJson['border_bardana_qty']; ?><?php echo $borderJson['border_bardana_name']; ?>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $borderJson['border_total_wt']; ?><?php echo $borderJson['border_saaf_wt']; ?>
                                </td>
                                <td class="small-2"><?php echo $borderJson['border_report']; ?></td>
                                <?php echo '<td class="border border-light text-danger text-nowrap bg-white">';
                                if (!empty($loading['khaata_border'])) {
                                    $khaata_ = json_decode($loading['khaata_border']);
                                    echo 'ٹوٹل بل: ';
                                    echo $khaata_->total_bill;
                                    echo '<hr class="mt-2 mb-0">';
                                    echo 'جمع کھاتہ: ';
                                    echo $khaata_->jmaa_khaata_no;
                                    echo '<hr class="mt-2 mb-0">';
                                    echo 'بنام کھاتہ: ';
                                    echo $khaata_->bnaam_khaata_no;
                                }
                                echo '</td>';
                                ?>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    document.onkeydown = function (evt) {
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            var username = $("#username").val();
            if (username == '' || username.length < 3) {
                evt.preventDefault();
                return false;
            }
            document.userNameSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $('#r_date_start, #r_date_end').change(function () {
        document.datesSubmit.submit();
    });
</script>
<script>
    function transferUTImpCustomKarachi(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ ایکسپورٹ کسٹم چمن میں ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n بیل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferUTImpCustomKarachi.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>