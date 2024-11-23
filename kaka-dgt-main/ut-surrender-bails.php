<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
if ($_POST) {
    $removeFilter = '<a href="ut-surrender-bails"><span class="ms-1 badge bg-danger urdu"><i class="mdi mdi-close-circle"></i> فلٹر ختم کریں</span></a>';
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $username_append = "";
        //$branch_append = "";
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
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 {$username_append} {$date_append} ORDER BY id DESC ";
$records = mysqli_query($connect, $sql); ?>
    <div class="filter-div">
        <?php echo $date_msg . '<span class="badge bg-secondary pt-2">' . $searchUserName . '</span>' . $removeFilter; ?>
    </div>
    <div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
        <div><h4 class="mb-3 mb-md-0"> سلنڈر بیل </h4></div>
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
                    <label for="username" class="input-group-text input-group-addon bg-transparent urdu">آئی ڈی
                        نام</label>
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
                        <table class="table table-bordered table-striped_ table-hover" id="fix-head-table">
                            <thead>
                            <tr class="text-nowrap">
                                <th class="small">بیل #</th>
                                <th class="small">لوڈنگ تاریخ</th>
                                <th width="" class="small-2">جنس/شہر</th>
                                <th width="" class="small">وزن</th>
                                <th class="small">بھیجنے والا</th>
                                <th class="small" width="">ایکسپورٹر</th>
                                <th class="small" width="">ایکسپورٹ ایجنٹ</th>
                                <th class="small" width="">امپورٹر</th>
                                <th class="small" width="">امپورٹ ایجنٹ</th>
                                <th width="" class="small">کراچی ایجنٹ</th>
                                <th width="" class="small">چمن ایجنٹ</th>
                                <th width="" class="small">بارڈر ایجنٹ</th>
                                <th width="" class="small">قندھار ایجنٹ</th>
                                <th width="5%" class="small-2">رپورٹ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php while ($loading = mysqli_fetch_assoc($records)) {
                                if (empty($loading['karachi_user_ids'])) {
                                    $rowClass = 'bg-warning bg-opacity-10 border border-light';
                                } else {
                                    $rowClass = '';
                                } ?>
                                <tr class=" <?php echo $rowClass; ?>">
                                    <td class="small-2">
                                        <a class="h5" data-tooltip="سلنڈر بیل کی تفصیل" data-tooltip-position="left"
                                           href="ut-surrender-bails-add?id=<?php echo $loading["id"]; ?>"><?php echo $loading["id"]; ?></a>
                                        <!--<a href="#" class="btn btn-primary py-0 px-1 small" data-tooltip="امپورٹ کسٹم کراچی میں ٹرانسفر کریں" data-tooltip-position="left" onclick="transferUTBailSurrenderEntry(this)" id="<?php /*echo $loading['id']; */ ?>" data-url="ut-surrender-bails" data-jins="<?php /*echo $loading['jins']; */ ?>">ٹرانسفر </a>-->
                                        <hr class="mt-2 mb-0">
                                        <span data-tooltip="آئی ڈی نام"
                                              data-tooltip-position="left"><?php echo $loading['username']; ?></span>
                                    </td>
                                    <td class="small-2 text-nowrap"><?php echo $loading['loading_date']; ?></td>
                                    <td class="small-2 text-wrap">
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
                                    <td class="small-2">
                                        <?php $sender_json = json_decode($loading['sender_receiver']);
                                        echo getTableDataByIdAndColName('senders', $sender_json->sender_id, 'comp_owner_name') . '<br>';
                                        echo '<span class="small-2 text-nowrap" dir="ltr">' . $sender_json->sender_mobile . '</span>'; ?>
                                    </td>
                                    <td class="small-2">
                                        <?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'name'); ?>
                                        <br>
                                        <span dir="ltr" class="small-2 text-nowrap"><?php echo getTableDataByIdAndColName('exporters', $loading['exporter_id'], 'mobile'); ?></span>
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
                                    <td class="small-2 text-nowrap">
                                        <?php if (!empty($loading['karachi_user_ids'])) {
                                            $json = json_decode($loading['karachi_user_ids']);
                                            $jsonID = implode(',', $json);
                                            $agentData = getTableDataById('users', $jsonID);
                                            echo $agentData['full_name'];
                                            echo '<br><span class="small-2" dir="ltr">' . $agentData['phone'] . '</span>';
                                        } ?>
                                    </td>
                                    <td class="small-2 text-nowrap">
                                        <?php if (!empty($loading['chaman_user_ids'])) {
                                            $json = json_decode($loading['chaman_user_ids']);
                                            $jsonID = implode(',', $json);
                                            $agentData = getTableDataById('users', $jsonID);
                                            echo $agentData['full_name'];
                                            echo '<br><span class="small-2" dir="ltr">' . $agentData['phone'] . '</span>';
                                        } ?>
                                    </td>
                                    <td class="small-2 text-nowrap">
                                        <?php if (!empty($loading['border_user_ids'])) {
                                            $json = json_decode($loading['border_user_ids']);
                                            $jsonID = implode(',', $json);
                                            $agentData = getTableDataById('users', $jsonID);
                                            echo $agentData['full_name'];
                                            echo '<br><span class="small-2" dir="ltr">' . $agentData['phone'] . '</span>';
                                        } ?>
                                    </td>
                                    <td class="small-2 text-nowrap">
                                        <?php if (!empty($loading['qandhar_user_ids'])) {
                                            $json = json_decode($loading['qandhar_user_ids']);
                                            $jsonID = implode(',', $json);
                                            $agentData = getTableDataById('users', $jsonID);
                                            echo $agentData['full_name'];
                                            echo '<br><span class="small-2" dir="ltr">' . $agentData['phone'] . '</span>';
                                        } ?>
                                    </td>
                                    <td class="small-2 text-nowrap" style=""><?php echo $loading['report']; ?></td>
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
        VirtualSelect.init({
            ele: '.agent-select',
            placeholder: 'انتخاب',
            searchPlaceholderText: 'تلاش',
            search: true,
            //optionsCount: 2,
            required: true,
            noSearchResultsTex: 'کوئی رزلٹ نہیں'
        });
    </script>
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
        function transferUTBailSurrenderEntry(e) {
            var id = $(e).attr('id');
            var jins = $(e).attr('data-jins');
            var url = $(e).attr('data-url');
            var str = "کیا آپ امپورٹ کسٹم کراچی میں ٹرانسفر کرنا چاہتے ہیں؟";
            if (id) {
                if (confirm(str + '\n بیل نمبر:' + id + '\nجنس: ' + jins)) {
                    window.location.href = 'ajax/transferUTBailSurrenderEntry.php?id=' + id + '&url=' + url;
                } else {
                    //alert('Action aborted.\n');
                }
            }
        }
    </script>
<?php if (isset($_POST['transferToAgents'])) {
    $url = 'ut-surrender-bails';
    $bail_id_hidden = $_POST['bail_id_hidden'];
    $user_ids = json_encode($_POST['user_ids']);
    $type = $_POST['type'];
    $dataTransfer = array();
    switch ($type) {
        case 'karachi':
            $dataTransfer['karachi_user_ids'] = $user_ids;
            break;
        case 'chaman':
            $dataTransfer['chaman_user_ids'] = $user_ids;
            break;
        case 'border':
            $dataTransfer['border_user_ids'] = $user_ids;
            break;
        case 'qandhar':
            $dataTransfer['qandhar_user_ids'] = $user_ids;
            break;
        default:
            echo 'INVALID type';
            break;
    }
    //$dataTransfer = array('karachi_user_ids' => $karachi_user_ids);
    $upp = update('ut_bail_entries', $dataTransfer, array('id' => $bail_id_hidden));
    if ($upp) {
        $remarks = 'Bail# ' . $bail_id_hidden . ' transferred to user IDs ' . $user_ids . ' by user ID ' . $userId;
        saveUTLogs($bail_id_hidden, $remarks);
        message('success', $url, 'ٹرانسفر ہو گیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>