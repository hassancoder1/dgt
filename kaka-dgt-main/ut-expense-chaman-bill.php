<?php include("header.php"); ?>
<?php $searchUserName = $date_append = $username_append = $date_msg = $removeFilter = $print_start_date = $print_end_date = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM ut_bail_entries WHERE is_surrender = 1 AND chaman_user_ids IS NOT NULL ";
if ($_POST) {
    $removeFilter = removeFilter('ut-expense-chaman-bill');
    if (isset($_POST['r_date_start']) && isset($_POST['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_POST['r_date_start']));
        $print_start_date = $start_date;
        $end_date = date('Y-m-d', strtotime($_POST['r_date_end']));
        $print_end_date = $end_date;
        //$date_append = " AND loading_date BETWEEN '$start_date' AND '$end_date'";
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date' " . " ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">لوڈنگ تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
}
$sql .= " ORDER BY exp_json DESC ";
$records = mysqli_query($connect, $sql); ?>
<div class="filter-div">
    <?php echo $date_msg . $removeFilter; ?>
</div>
<div class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div><h4 class="mb-3 mb-md-0"> چمن خرچہ بل </h4></div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <div class="input-group wd-200 me-3">
            <span class="input-group-text input-group-addon bg-transparent urdu">تلاش</span>
            <input id="tableFilter" type="text" autofocus class="form-control bg-transparent border-primary"
                   placeholder="تلاش کریں (F2)">
        </div>
        <form name="datesSubmit" method="POST" class="d-flex">
            <div class="input-group flatpickr wd-120 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_start" name="r_date_start"
                       value="<?php echo $start_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ ابتداء" data-input>
                <label for="r_date_start" class="input-group-text urdu">سے</label>
            </div>
            <div class="flatpickr wd-100 mb-2 mb-md-0" id="flatpickr-date">
                <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>"
                       type="text" class="form-control bg-transparent border-primary"
                       placeholder="تاریخ انتہاء" data-input>
            </div>
        </form>
    </div>
</div>
<div class="row mt-4 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered table-striped_ table-hover" id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap">
                        <th class="small">بیل #</th>
                        <th class="small">تاریخ</th>
                        <th class="small-2">جنس/شہر</th>
                        <th class="small">وزن</th>
                        <th class="small">بھیجنے والا</th>
                        <th class="small">وصول کرنے والا</th>
                        <th class="small">بل نمبر</th>
                        <th class="small">کنٹینر</th>
                        <th class="small">ٹرانسفر تاریخ</th>
                        <th class="small">ٹوٹل</th>
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
                        }
                        /*if (empty($loading['chaman_user_ids'])) {
                            $rowClass = 'bg-warning bg-opacity-10 border border-white';
                        } else {
                            $rowClass = '';
                        }*/
                        if (!empty($loading['khaata_chaman'])) { //condition: k kharcha daal dia h.
                            $surrender_json = json_decode($loading['surrender_json']); ?>
                            <tr class="text-nowrap <?php echo $rowClass; ?>">
                                <td class="small-2">
                                    <?php echo $loading["id"]; ?>
                                    <a class="btn btn-primary pt-0 p-1 mt-n1 btn-sm float-end" data-tooltip="بل کا پرنٹ"
                                       data-tooltip-position="left"
                                       href="print/ut-expense-bill?bail_id=<?php echo $loading["id"]; ?>&secret=<?php echo base64_encode("powered-by-upsol") ?>&start_date=<?php echo $print_start_date; ?>&end_date=<?php echo $print_end_date; ?>&type=<?php echo CHAMAN; ?>&url=<?php echo base64_encode('ut-expense-chaman-bill') ?>"><i
                                                class="fa fa-print"></i>
                                    </a>
                                    <!--<a href="#" class="btn btn-primary py-0 px-1 small" data-tooltip="امپورٹ کسٹم کراچی میں ٹرانسفر کریں" data-tooltip-position="left" onclick="transferUTBailSurrenderEntry(this)" id="<?php /*echo $loading['id']; */ ?>" data-url="ut-surrender-bails" data-jins="<?php /*echo $loading['jins']; */ ?>">ٹرانسفر </a>-->
                                    <hr class="mt-2 mb-0">
                                    <span><?php echo $loading['username']; ?></span>
                                </td>
                                <td class="small-2">
                                    <span class="small-2">لوڈنگ </span>
                                    <?php echo $loading['loading_date']; ?>
                                    <hr class="mt-2 mb-0">
                                    <span class="small-2">سلنڈر </span>
                                    <?php echo $surrender_json->sr_date; ?>
                                </td>
                                <td class="small-2 text-wrap">
                                    <?php echo $loading['jins']; ?>
                                    <hr class="mt-2 mb-0">
                                    <span class=""><?php echo $loading['loading_city']; ?></span>
                                </td>
                                <td class="small-2">
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
                                    <?php echo getTableDataByIdAndColName('receivers', $sender_json->receiver_id, 'comp_owner_name') . '<br>';
                                    echo '<span class="small-2 text-nowrap" dir="ltr">' . $sender_json->receiver_mobile . '</span>'; ?>
                                </td>
                                <td class="small-2">
                                    <span class="small-2">بل</span>
                                    <?php echo $loading['bill_no']; ?>
                                    <hr class="mt-2 mb-0">
                                    <span class="small-2">سلنڈر بل</span>
                                    <?php echo $surrender_json->sr_bill_no; ?>
                                </td>
                                <td class="small-2">
                                    <span class="small-2">نمبر </span>
                                    <?php echo $surrender_json->sr_container_no; ?>
                                    <hr class="mt-2 mb-0">
                                    <span class="small-2">نام </span>
                                    <?php echo $surrender_json->sr_container_name; ?>
                                </td>
                                <td class="small-2">
                                    <?php $khaata_ = json_decode($loading['khaata_' . CHAMAN]);
                                    //$t_at=$khaata_->transferred_to_roznamcha_at;
                                    if (!empty($khaata_->transferred_to_roznamcha_at)) {
                                        echo $khaata_->transferred_to_roznamcha_at;
                                    } ?>
                                </td>
                                <td class="border border-light text-danger text-nowrap">
                                    <?php //$khaata_ = json_decode($loading['khaata_chaman']);
                                    echo 'ٹوٹل بل: ';
                                    echo $khaata_->total_bill;
                                    echo '<hr class="mt-2 mb-0">';
                                    echo 'جمع: ';
                                    echo $khaata_->jmaa_khaata_no;
                                    echo '<span class="mx-1"></span>';
                                    //echo '<hr class="mt-2 mb-0">';
                                    echo 'بنام: ';
                                    echo $khaata_->bnaam_khaata_no; ?>
                                </td>
                            </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
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