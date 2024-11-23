<?php include("header.php");
$pageURL = 'dt-kiraya-godam-received';
$searchUserName = $username_msg = $date_msg = $removeFilter = "";
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM dt_truck_loadings WHERE is_transfered=1 AND is_saved=1 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date'";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $searchUserName = $_GET['username'];
        $username_msg = '<span class="badge bg-primary ms-1"><span class="urdu me-2">ٹرک </span>' . $searchUserName . '</span>';
        $sql .= " AND truck_no = " . "'$searchUserName'" . " ";
    }
    echo '<div class="filter-div">' . $date_msg . $username_msg . $removeFilter . '</div>';
}
$sql .= " ORDER BY id DESC ";
$records = mysqli_query($connect, $sql); ?>
<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">ڈاؤن ٹرانزٹ گودام پہنچ مکمل</h4>
    </div>
    <div class="urdu">تعداد انٹری <span id="count_rows_span" class="bold underline"></span></div>
    <form name="datesSubmit" method="get" class="d-flex">
        <div class="urdu d-flex align-items-center wd-md-120 me-2">
            <?php echo searchInput('a'); ?>
        </div>
        <div class="input-group flatpickr wd-130" id="flatpickr-date">
            <label for="r_date_start" class="input-group-text urdu">تاریخ</label>
            <input id="r_date_start" name="r_date_start" value="<?php echo $start_date; ?>" type="text"
                   class="form-control " placeholder="تاریخ ابتداء" data-input>
            <label for="r_date_end" class="input-group-text urdu">سے</label>
        </div>
        <div class="flatpickr wd-80" id="flatpickr-date">
            <input id="r_date_end" name="r_date_end" value="<?php echo $end_date; ?>" type="text" class="form-control"
                   placeholder="تاریخ انتہاء" data-input>
        </div>
        <div class="input-group wd-150 ms-3 mb-2 mb-md-0 me-2">
            <label for="username" class="input-group-text urdu">ٹرک
                نمبر</label>
            <input type="text" id="username" name="username" class="form-control urdu" placeholder="ٹرک نمبر"
                   value="<?php echo $searchUserName; ?>" required>
        </div>
    </form>
</div>
<div class="row mt-3 pt-3">
    <div class="col-md-12">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <div class="card">
            <div class="table-responsive scroll screen-ht">
                <table class="table table-bordered table-sm table-hover" id="fix-head-table">
                    <thead>
                    <tr class="text-nowrap">
                        <th class="small">لوڈنگ #</th>
                        <th class="small">لوڈنگ تاریخ</th>
                        <th class="small">ٹرک نمبر</th>
                        <th class="small">ڈرائیورنام</th>
                        <th class="small-3">سیریل <br> تعداد</th>
                        <th class="small">بھیجنےوالا</th>
                        <th class="small">وصول کرنےوالا</th>
                        <th> شہر</th>
                        <th class="small">لوڈکرانےگودام</th>
                        <th class="small">خالی کرانےگودام</th>
                        <th class="small-2">گودام پہنچ تاریخ</th>
                        <th class="small-2">پہنچ نمبر</th>
                        <th class="small-2">وصول <br>باردانہ تعداد</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count_rows = 0;
                    $no = 1;
                    while ($loading = mysqli_fetch_assoc($records)) {
                        if (!empty($loading['sender_receiver'])) {
                            $sender_receiver = json_decode($loading['sender_receiver']);
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
                        }
                        if (empty($loading['transfer_to_forms'])) {
                            continue;
                        } else {
                            $json = json_decode($loading['transfer_to_forms']);
                            $json = implode(',', $json);
                            $json = explode(',', $json);
                            if (!in_array("kiraya", $json)) {
                                continue;
                            }
                        }
                        $maals = fetch('dt_truck_maals', array('dt_tl_id' => $loading['id']));
                        $godam_receive_no = $receive_bardana_qty = $bardana_balance = $total_rec = $maal2Id = 0;
                        $godam_receive_date = null;
                        $rowColor = 'bg-warning bg-opacity-10';
                        $btn_Array = array('class' => 'btn-warning', 'text' => 'انٹری');
                        while ($maal = mysqli_fetch_assoc($maals)) {
                            $maal2 = isDTKirayaAdded($maal['id'], 'godam_received');
                            if ($maal2['success']) {
                                $maal2Id = $maal2['output']['id'];
                                $json2 = json_decode($maal2['output']['json_data']);
                                $godam_receive_date = $json2->godam_receive_date;
                                $godam_receive_no = $json2->godam_receive_no;
                                $receive_bardana_qty = $json2->receive_bardana_qty;
                                //$total_rec += $json2->receive_bardana_qty;
                                $total_rec = $json2->receive_bardana_qty;
                                $rowColor = '';
                                $btn_Array = array('class' => 'btn-dark', 'text' => 'اوپن');
                            }
                        }
                        if ($rowColor != '') continue; ?>
                        <tr class="text-nowrap <?php echo $rowColor; ?>">
                            <td>
                                <?php echo $loading["id"]; ?>
                                <a href="dt-kiraya-godam-received-transfer?id=<?php echo $loading['id']; ?>&type=godam-received"
                                   class="btn <?php echo $btn_Array['class']; ?> pt-0 pb-1 px-1 btn-sm small-3"><?php echo $btn_Array['text']; ?></a>
                            </td>
                            <td><?php echo $loading['loading_date']; ?></td>
                            <td class="small"><?php echo $loading['truck_no']; ?></td>
                            <td class="small-2"><?php echo $loading['driver_name']; ?><br><span
                                    dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                            <td><?php $tadadQuery = fetch('dt_truck_maals', array('dt_tl_id' => $loading["id"]));
                                echo mysqli_num_rows($tadadQuery); ?></td>
                            <td class="small-2"><?php echo $names['dt_comp_name']; ?>
                                <br><span dir="ltr"><?php echo $names['dt_sender_mobile']; ?></span></td>
                            <td class="small-2"><?php echo $names['dt_comp_name_r']; ?>
                                <br><span dir="ltr"><?php echo $names['dt_receiver_mobile']; ?></span></td>
                            <td class="small"><?php echo $loading['sender_city']; ?></td>
                            <td class="small-2"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                <br><span
                                    dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-2"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                <br><span
                                    dir="ltr"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                            </td>
                            <?php if ($maal2Id > 0) {
                                echo '<td>' . $godam_receive_date . '</td>';
                                echo '<td>' . $godam_receive_no . '</td>';
                                echo '<td>' . $total_rec . '</td>';
                            } else {
                                echo '<td></td><td></td><td></td>';
                            } ?>
                        </tr>
                        <?php $no++;
                        $count_rows++;
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="count_rows" value="<?php echo $count_rows; ?>">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    document.onkeydown = function (evt) {
        let keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if (keyCode == 13) {
            //your function call here
            var username = $("#username").val();
            if (username == '' || username.length < 3) {
                evt.preventDefault();
                return false;
            }
            document.datesSubmit.submit();
        }
    }
</script>
<script type="text/javascript">
    $('#r_date_start, #r_date_end').change(function () {
        document.datesSubmit.submit();
    });
    $("#count_rows_span").text($("#count_rows").val());
</script>
