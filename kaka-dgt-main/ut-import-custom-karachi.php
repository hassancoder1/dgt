<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="ut-import-custom-karachi"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
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
}
//$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND is_imp=1 AND is_exp= 0  {$username_append} {$date_append}";
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND karachi_user_ids != '' {$username_append} {$date_append} ORDER BY imp_json ASC ";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>' . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div><h3 class="mb-3 mb-md-0 urdu-2"> امپورٹ کسٹم کراچی </h3></div>
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
            <div class="input-group wd-200 ms-3 mb-2 mb-md-0 me-2">
                <label for="username" class="input-group-text input-group-addon bg-transparent urdu">آئی ڈی نام</label>
                <input type="text" id="username" name="username" class="form-control bg-transparent border-primary"
                       placeholder="آئی ڈی نام" autofocus value="<?php echo $searchUserName; ?>" required>
            </div>
        </form>
        <button type="button" class="btn btn-primary btn-icon-text pt-0 me-1"
                onclick="window.print();"><i class="btn-icon-prepend me-0" data-feather="printer"></i>
        </button>
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
                            <th class="small-2">تاریخ</th>
                            <th class="small">ٹرک / ڈرائیور</th>
                            <th class="">نمبرز</th>
                            <th width="18%">کراچی رپورٹ</th>
                            <th class="">ٹوٹل</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($loading = mysqli_fetch_assoc($records)) {
                            $json = json_decode($loading['karachi_user_ids']);
                            $json = implode(',', $json);
                            $json = explode(',', $json);
                            $jsonPerms = array();
                            $perms = UTPermissions($userId);
                            if (!empty($perms)) {
                                $perms = json_decode($perms);
                                $jsonPerms = implode(',', $perms);
                                $jsonPerms = explode(',', $jsonPerms);
                            }
                            if ((in_array($userId, $json) && in_array("karachi", $jsonPerms)) || Administrator()) {
                            } else {
                                continue;
                            }
                            $rowClass = 'border ';
                            if (!empty($loading['khaata_karachi'])) {
                                $rowClass = '';
                            } else {
                                if (empty($loading['imp_json'])) {
                                    $rowClass = 'bg-danger bg-opacity-25 border border-white';
                                } else {
                                    $rowClass = 'bg-warning bg-opacity-25 border border-white';
                                }
                            } ?>
                            <tr class="<?php echo $rowClass; ?>">
                                <td class="text-nowrap small-2">
                                    <a class="h5" data-tooltip="کسٹم کلئیرنس کی تفصیل"
                                       data-tooltip-position="bottom left"
                                       href="ut-import-custom-karachi-add?id=<?php echo $loading["id"]; ?>"><?php echo $loading["id"]; ?></a>
                                    <?php
                                    if (!empty($loading['khaata_karachi'])) {
                                        echo '<a href="ut-expense-transfer?id=' . $loading["id"] . '&type=karachi" 
                                        class="btn btn-success py-0 px-1 small urdu-2 float-end"
                                           data-tooltip="بیل نمبر ' . $loading["id"] . ' میں خرچہ انٹری ہو چکی ہے۔ " 
                                           data-tooltip-position="left">مکمل شد</a>';
                                    } else {
                                        if (empty($loading['imp_json'])) {
                                            echo '<i class="fa fa-warning text-danger float-end me-2 mt-1" 
                                        data-tooltip="خرچہ ڈالنے سے پہلے کسٹم کلئیرنس کی انٹری کریں " data-tooltip-position="right"></i>';
                                        } else {
                                            echo '<a href="ut-expense-transfer?id=' . $loading["id"] . '&type=karachi" 
                                        class="btn btn-primary py-0 px-1 small urdu-2 float-end"
                                           data-tooltip="بیل نمبر ' . $loading["id"] . ' میں خرچہ انٹری کریں۔ " 
                                           data-tooltip-position="left">خرچہ</a>';
                                        }
                                    } ?>
                                    <hr class="mt-2 mb-0">
                                    <span class="" data-tooltip="آئی ڈی نام"
                                          data-tooltip-position="left"><?php echo $loading['username']; ?></span>
                                </td>
                                <td class="small-2"><?php echo $loading['loading_date']; ?></td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $loading['jins']; ?>
                                    <hr class="mt-2 mb-0">
                                    <span class=""><?php echo $loading['loading_city']; ?></span>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <span class="small-2">ٹوٹل</span>
                                    <?php echo $loading['total_wt']; ?>
                                    <hr class="mt-2 mb-0">
                                    <span class="small-2">صاف</span>
                                    <?php echo $loading['saaf_wt']; ?>
                                </td>
                                <td class="small-2 ">
                                    <?php $sender_json = json_decode($loading['sender_receiver']);
                                    echo getTableDataByIdAndColName('senders', $sender_json->sender_id, 'comp_owner_name') . '<br>';
                                    echo '<span class="small-2 text-nowrap" dir="ltr">' . $sender_json->sender_mobile . '</span>'; ?>
                                </td>
                                <td class="small-2">
                                    <?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"
                                            class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'mobile'); ?></span>
                                </td>
                                <td class="small-2 text-wrap">
                                    <?php $expAgent = getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_name');
                                    echo $expAgent; ?>
                                    <br><span dir="ltr"
                                              class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['exp_ca_id'], 'ca_mobile'); ?></span>
                                </td>
                                <td class="small-2">
                                    <?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'name'); ?>
                                    <br><span class="small-2 text-nowrap"
                                              dir="ltr"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'mobile'); ?></span>
                                </td>
                                <td class="small-2 text-wrap">
                                    <?php $impAgent = getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_name');
                                    echo $impAgent; ?>
                                    <br><span class="small-2"
                                              dir="ltr"><?php echo getTableDataByIdAndColName('clearing_agents', $loading['imp_ca_id'], 'ca_mobile'); ?></span>
                                </td>
                                <td class="small-2"><?php echo $loading['report']; ?></td>


                                <?php if (empty($loading['imp_json'])) {
                                    $impCusJson = array(
                                        'imp_cus_loading_date' => '',
                                        'imp_cus_truck_no' => '',
                                        'imp_cus_truck_name' => '',
                                        'imp_cus_driver_name' => '',
                                        'imp_cus_driver_mobile' => '',
                                        'imp_cus_clearing_date' => '',
                                        'imp_cus_gd_no' => '',
                                        'imp_cus_seal_no' => '',
                                        'imp_cus_report' => ''
                                    );
                                } else {
                                    $imp_json = json_decode($loading['imp_json']);
                                    $impCusJson = array(
                                        'imp_cus_loading_date' => '<span class="small-2">لوڈنگ تاریخ</span><br>' . $imp_json->imp_cus_loading_date . '<hr class="mt-2 mb-1">',
                                        'imp_cus_truck_no' => '<span class="small-2">نمبر: </span>' . $imp_json->imp_cus_truck_no . '<br>',
                                        'imp_cus_truck_name' => '<span class="small-2">نام: </span>' . $imp_json->imp_cus_truck_name . '<hr class="mt-2 mb-0">',
                                        'imp_cus_driver_name' => $imp_json->imp_cus_driver_name . '<br>',
                                        'imp_cus_driver_mobile' => '<span dir="ltr">' . $imp_json->imp_cus_driver_mobile . '</span>',
                                        'imp_cus_clearing_date' => '<span class="">کسٹم کلئیرنگ تاریخ </span><br>' . $imp_json->imp_cus_clearing_date,
                                        'imp_cus_gd_no' => '<span class="small-2">جی ڈی نمبر: </span><br>' . $imp_json->imp_cus_gd_no . '<hr class="mt-2 mb-1">',
                                        'imp_cus_seal_no' => '<span class="small-2">کسٹم سیل نمبر: </span><br>' . $imp_json->imp_cus_seal_no,
                                        'imp_cus_report' => $imp_json->imp_cus_report
                                    );
                                } ?>
                                <td class="small-2 text-nowrap">
                                    <?php echo $impCusJson['imp_cus_loading_date']; ?>
                                    <?php echo $impCusJson['imp_cus_clearing_date']; ?>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $impCusJson['imp_cus_truck_no']; ?>
                                    <?php echo $impCusJson['imp_cus_truck_name']; ?>
                                    <?php echo $impCusJson['imp_cus_driver_name']; ?>
                                    <?php echo $impCusJson['imp_cus_driver_mobile']; ?>
                                </td>
                                <td class="small-2 text-nowrap">
                                    <?php echo $impCusJson['imp_cus_gd_no']; ?>
                                    <?php echo $impCusJson['imp_cus_seal_no']; ?>
                                </td>
                                <td class="small-2"><?php echo $impCusJson['imp_cus_report']; ?></td>
                                <?php if (!empty($loading['khaata_karachi'])) {
                                    echo '<td class="border border-light text-danger text-nowrap">';
                                    $khaata_ = json_decode($loading['khaata_karachi']);
                                    echo 'ٹوٹل بل: ';
                                    echo $khaata_->total_bill;
                                    echo '<hr class="mt-2 mb-0">';
                                    echo 'جمع کھاتہ: ';
                                    echo $khaata_->jmaa_khaata_no;
                                    echo '<hr class="mt-2 mb-0">';
                                    echo 'بنام کھاتہ: ';
                                    echo $khaata_->bnaam_khaata_no;
                                    echo '</td>';
                                } ?>
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