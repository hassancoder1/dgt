<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="ut-export-custom-chaman.php"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
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
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND chaman_user_ids != '' {$username_append} {$date_append}";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg;
    echo '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>';
    echo $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div><h3 class="mb-3 mb-md-0 urdu-2"> ایکسپورٹ کسٹم چمن </h3></div>
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
                <div class="table-responsive scroll screen-ht">
                    <table class="table table-bordered " id="fix-head-table">
                        <thead>
                        <tr>
                            <th>بیل #</th>
                            <th>آئی ڈی</th>
                            <th>لوڈنگ تاریخ</th>
                            <th>جنس</th>
                            <th>لوڈشہر</th>
                            <th>ٹوٹل وزن</th>
                            <th>صاف وزن</th>
                            <th>بھیجنے والا</th>
                            <th>ایکسپورٹر</th>
                            <th>امپورٹر</th>
                            <th>رپورٹ</th>
                            <th>تاریخ</th>
                            <th>نمبرز</th>
                            <th class="small-2">رپورٹ</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($loading = mysqli_fetch_assoc($records)) {
                            $json = json_decode($loading['chaman_user_ids']);
                            $json = implode(',', $json);
                            $json = explode(',', $json);

                            $jsonPerms = array();
                            $perms = UTPermissions($userId);
                            if (!empty($perms)) {
                                $perms = json_decode($perms);
                                $jsonPerms = implode(',', $perms);
                                $jsonPerms = explode(',', $jsonPerms);
                            }
                            if ((in_array($userId, $json) && in_array("chaman", $jsonPerms)) || Administrator()) {
                            } else {
                                continue;
                            } ?>
                            <tr class="text-nowrap">
                                <td>
                                    <a data-tooltip="بیل کی تفصیل"
                                       data-tooltip-position="bottom left"
                                       href="ut-export-custom-chaman-add.php?id=<?php echo $loading["id"]; ?>"><?php echo $loading["id"]; ?></a>
                                    <?php if (empty($loading['exp_json'])) {
                                        echo '<i class="fa fa-info-circle" data-tooltip="بیل نمبر ' . $loading["id"] . ' میں ابھی ایکسپورٹ کسٹم چمن کی انٹری نہیں ہوئی۔ " data-tooltip-position="right"></i>'; ?>
                                    <?php } else { ?>
                                        <a href="#" class="btn btn-primary py-0 px-1 small"
                                           data-tooltip="افغان بارڈر کلینینگ کسٹم میں ٹرانسفر کریں"
                                           data-tooltip-position="left"
                                           onclick="transferUTExpCustomChaman(this)"
                                           id="<?php echo $loading['id']; ?>"
                                           data-url="ut-export-custom-chaman"
                                           data-jins="<?php echo $loading['jins']; ?>">ٹرانسفر </a>
                                    <?php } ?>
                                </td>
                                <td><?php echo $loading['username']; ?></td>
                                <td><?php echo $loading['loading_date']; ?></td>
                                <td><?php echo $loading['jins']; ?></td>
                                <td><?php echo $loading['loading_city']; ?></td>
                                <td><?php echo $loading['total_wt']; ?></td>
                                <td><?php echo $loading['saaf_wt']; ?></td>
                                <td class="small-2"><?php $sender_json = json_decode($loading['sender_json']);
                                    echo '<span> نام : </span>' . $sender_json->sender_name . '<br>';
                                    echo '<span> پتہ : </span>' . $sender_json->sender_address; ?>
                                </td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'mobile'); ?></span>
                                </td>
                                <td class="small-2"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'name'); ?>
                                    <br><span
                                            dir="ltr"><?php echo getTableDataByIdAndColName('importers', $loading['importer_id'], 'mobile'); ?></span>
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
                                        'imp_cus_loading_date' => $imp_json->imp_cus_loading_date,
                                        'imp_cus_truck_no' => $imp_json->imp_cus_truck_no,
                                        'imp_cus_truck_name' => $imp_json->imp_cus_truck_name,
                                        'imp_cus_driver_name' => $imp_json->imp_cus_driver_name,
                                        'imp_cus_driver_mobile' => $imp_json->imp_cus_driver_mobile,
                                        'imp_cus_clearing_date' => $imp_json->imp_cus_clearing_date,
                                        'imp_cus_gd_no' => $imp_json->imp_cus_gd_no,
                                        'imp_cus_seal_no' => $imp_json->imp_cus_seal_no,
                                        'imp_cus_report' => $imp_json->imp_cus_report
                                    );
                                }
                                if (empty($loading['exp_json'])) {
                                    $expCusJson = array(
                                        'exp_cus_receiving_date' => '',
                                        'exp_cus_clearance_date' => '',
                                        'exp_cus_gd_no' => '',
                                        'exp_cus_scart_no' => '',
                                        'exp_cus_report' => ''
                                    );
                                } else {
                                    $exp_json = json_decode($loading['exp_json']);
                                    $expCusJson = array(
                                        'exp_cus_receiving_date' => '<span> پہنچ : </span>' . $exp_json->exp_cus_receiving_date,
                                        'exp_cus_clearance_date' => '<span> کلئیرنس : </span>' . $exp_json->exp_cus_clearance_date,
                                        'exp_cus_gd_no' => '<span> جی ڈی: </span>' . $exp_json->exp_cus_gd_no,
                                        'exp_cus_scart_no' => '<span> سکارٹ : </span>' . $exp_json->exp_cus_scart_no,
                                        'exp_cus_report' => $exp_json->exp_cus_report
                                    );
                                } ?>
                                <td>
                                    <?php echo $expCusJson['exp_cus_receiving_date']; ?>
                                    <br><?php echo $expCusJson['exp_cus_clearance_date']; ?>
                                </td>
                                <td>
                                    <?php echo $expCusJson['exp_cus_gd_no']; ?>
                                    <br><?php echo $expCusJson['exp_cus_scart_no']; ?>
                                </td>
                                <td><?php echo $expCusJson['exp_cus_report']; ?></td>
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
    function transferUTExpCustomChaman(e) {
        var id = $(e).attr('id');
        var jins = $(e).attr('data-jins');
        var url = $(e).attr('data-url');
        var str = "کیا آپ افغان بارڈرکلیننگ کسٹم چمن میں ٹرانسفر کرنا چاہتے ہیں؟";
        if (id) {
            if (confirm(str + '\n بیل نمبر:' + id + '\nجنس: ' + jins)) {
                window.location.href = 'ajax/transferUTExpCustomChaman.php?id=' + id + '&url=' + url;
            } else {
                //alert('Action aborted.\n');
            }
        }
    }
</script>