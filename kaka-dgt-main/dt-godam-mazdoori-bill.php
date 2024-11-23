<?php include("header.php");
$pageURL = 'dt-godam-mazdoori-bill';
$searchBnaamKhaataNo = $username_msg = $godam_msg = $date_msg = $removeFilter = "";
$godam_loading_id = 0;
$isKhaataPosted = false;
$start_date = $end_date = date('Y-m-d');
$sql = "SELECT * FROM dt_truck_loadings WHERE is_transfered=1 AND is_saved=1 ";
if ($_GET) {
    $removeFilter = removeFilter($pageURL);
    if (isset($_GET['r_date_start']) && isset($_GET['r_date_end'])) {
        $start_date = date('Y-m-d', strtotime($_GET['r_date_start']));
        $end_date = date('Y-m-d', strtotime($_GET['r_date_end']));
        $sql .= " AND loading_date BETWEEN '$start_date' AND '$end_date' ";
        $date_msg = '<span class="badge bg-secondary"><span class="urdu me-2">تاریخ</span>' . $start_date . ' سے ' . $end_date . '</span>';
    }
    if (isset($_GET['godam_loading_id']) && $_GET['godam_loading_id'] > 0) {
        $godam_loading_id = $_GET['godam_loading_id'];
        $sql .= " AND godam_loading_id = " . "'$godam_loading_id'" . " ";
        $godam_msg = '<span class="badge bg-warning ms-1 urdu">' . getTableDataByIdAndColName('godam_loading_forms', $godam_loading_id, 'name') . '</span>';
    }
    if (isset($_GET['bnaam_khaata_no']) && !empty($_GET['bnaam_khaata_no'])) {
        $isKhaataPosted = true;
        $searchBnaamKhaataNo = $_GET['bnaam_khaata_no'];
        $username_msg = '<span class="badge bg-secondary pt-2">' . $searchBnaamKhaataNo . '</span>';
    }
    echo '<div class="filter-div">' . $date_msg . $username_msg . $godam_msg . $removeFilter . '</div>';
}
$sql .= " ORDER BY id DESC";
$records = mysqli_query($connect, $sql); ?>
<div
    class="heading-div d-flex justify-content-between align-items-center flex-wrap grid-margin px-4 py-1 border-bottom">
    <div>
        <h4 class="mb-3 mb-md-0">ڈاؤن ٹرانزٹ گودام مزدوری بل</h4>
    </div>
    <div class="d-flex gap-md-4 urdu">
        <div>تعداد انٹری <span id="count_rows_span" class="bold underline"></span></div>
        <div>ٹوٹل باردانہ تعداد <span id="gt_bardana_input" class="bold underline"></span></div>
        <div>ٹوٹل وزن <span id="gt_wt_input" class="bold underline"></span></div>
        <div>ٹوٹل خرچہ <span id="gt_exp_input" class="bold underline"></span></div>
    </div>
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
        <div class="input-group wd-200 me-1">
            <label for="godam_loading_id" class="input-group-text urdu">لوڈ گودام</label>
            <select id="godam_loading_id" name="godam_loading_id" class="form-select">
                <option value="0">تمام گودام</option>
                <?php $empties = fetch('godam_loading_forms');
                while ($empty = mysqli_fetch_assoc($empties)) {
                    $e_selected = $empty['id'] == $godam_loading_id ? 'selected' : '';
                    echo '<option ' . $e_selected . ' value="' . $empty['id'] . '">' . $empty['name'] . '</option>';
                } ?>
            </select>
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
                    <tr class="text-nowrap ">
                        <th>لوڈنگ #</th>
                        <th class="small">لوڈنگ تاریخ</th>
                        <th>ٹرک نمبر</th>
                        <th>ڈرائیورنام</th>
                        <th class="small-3">سیریل <br> تعداد</th>
                        <th class="small">بھیجنےوالا</th>
                        <th class="small">وصول کرنےوالا</th>
                        <th class="small"> شہر</th>
                        <th class="small">لوڈکرانےگودام</th>
                        <th class="small">خالی کرانےگودام</th>
                        <th class="small">جمع اکاؤنٹ</th>
                        <th class="small">ٹوٹل باردن</th>
                        <th class="small">ٹوٹل وزن</th>
                        <th class="small">ٹوٹل خرچہ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count_rows = $gt_bardana = $gt_wt = $gt_exp = 0;
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
                            if (!in_array("godam", $json)) {
                                continue;
                            }
                        }

                        $maal1 = fetch('dt_truck_maals', array('dt_tl_id' => $loading["id"]));
                        $count_maal1 = mysqli_num_rows($maal1);
                        $btn_Array = array('class' => 'btn-dark', 'text' => 'اوپن');
                        $rowColor = '';
                        if (empty($loading['khaata_gm'])) {
                            $isMaal2Added = fetch('dt_truck_maals2', array('dt_tl_id' => $loading["id"], 'form_name' => 'godam_mazdoori_dt'));
                            $count_maal2 = 0;
                            while ($dd = mysqli_fetch_assoc($isMaal2Added)) {
                                if ($dd['maal_id'] > 0) {
                                    $count_maal2++;
                                }
                            }
                            if ($count_maal2 >= $count_maal1) {
                                $rowColor = 'bg-warning bg-opacity-10';
                                $btn_Array = array('class' => 'btn-warning', 'text' => 'انٹری');
                            } else {
                                $rowColor = 'bg-danger bg-opacity-10';
                                $btn_Array = array('class' => 'btn-danger', 'text' => 'انٹری');
                            }
                        }else {
                            continue; // show in final, if khaata_gm is filled
                        }

                        if (empty($loading['khaata_gm'])) {
                            if ($isKhaataPosted) {
                                continue;
                            }
                            $details_jmaa = '';
                        } else {
                            $khaata_bs = json_decode($loading['khaata_gm']);
                            $jmaa_khaata = khaataSingle($khaata_bs->jmaa_khaata_id);
                            //var_dump($jmaa_khaata);
                            if ($isKhaataPosted) {
                                if ($searchBnaamKhaataNo != $jmaa_khaata['khaata_no']) {
                                    continue;
                                }
                            }
                            $details_jmaa = $jmaa_khaata['khaata_no'] . '<br>' . $jmaa_khaata['khaata_name'];
                        } ?>
                        <tr class="<?php echo $rowColor; ?> text-nowrap">
                            <td>
                                <?php echo $loading["id"]; ?>

                                <a href="dt-summary-transfer?id=<?php echo $loading['id']; ?>&type=godam-mazdoori"
                                   class="btn <?php echo $btn_Array['class']; ?> pt-0 pb-1 px-1 btn-sm small-3"><?php echo $btn_Array['text']; ?></a>
                            </td>
                            <td class="small-2"><?php echo $loading['loading_date']; ?></td>
                            <td class="small-2"><?php echo strtoupper($loading['truck_no']); ?></td>
                            <td class="small-2"><?php echo $loading['driver_name']; ?><br><span
                                    dir="ltr"><?php echo $loading['driver_mobile']; ?></span></td>
                            <td><?php $tadadQuery = fetch('dt_truck_maals', array('dt_tl_id' => $loading["id"]));
                                echo mysqli_num_rows($tadadQuery); ?>
                            </td>
                            <td class="small-3"><?php echo $names['dt_comp_name']; ?>
                                <br><span dir="ltr"><?php echo $names['dt_sender_mobile']; ?></span></td>
                            <td class="small-3"><?php echo $names['dt_comp_name_r']; ?>
                                <br><span dir="ltr"><?php echo $names['dt_receiver_mobile']; ?></span></td>
                            <td class="small"><?php echo $loading['sender_city']; ?></td>
                            <td class="small-3"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'name'); ?>
                                <br><span
                                    dir="ltr"><?php echo getTableDataByIdAndColName('godam_loading_forms', $loading['godam_loading_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-3"><?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'name'); ?>
                                <br><span dir="ltr">
                                        <?php echo getTableDataByIdAndColName('godam_empty_forms', $loading['godam_empty_id'], 'mobile1'); ?></span>
                            </td>
                            <td class="small-2"><?php echo $details_jmaa; ?></td>
                            <?php $maals = fetch('dt_truck_maals', array('dt_tl_id' => $loading['id']));
                            $bardana_qty = $total_wt = $total_exp = 0;
                            while ($maal = mysqli_fetch_assoc($maals)) {
                                $maal_id = $maal['id'];
                                $maals2 = fetch('dt_truck_maals2', array('maal_id' => $maal_id, 'form_name' => 'godam_mazdoori_dt'));
                                while ($maal2 = mysqli_fetch_assoc($maals2)) {
                                    $json = json_decode($maal2['json_data']);
                                    $bardana_qty += $json->bardana_qty;
                                    $total_wt += $json->total_wt;
                                    $total_exp += $json->total_exp;
                                }
                            } ?>
                            <td><?php echo $bardana_qty; ?></td>
                            <td><?php echo $total_wt; ?></td>
                            <td><?php echo round($total_exp); ?></td>
                        </tr>
                        <?php $no++;
                        $count_rows++;
                        $gt_bardana += $bardana_qty;
                        $gt_wt += $total_wt;
                        $gt_exp += $total_exp;
                    } ?>
                    </tbody>
                </table>
                <input type="hidden" id="count_rows" value="<?php echo $count_rows; ?>">
                <input type="hidden" id="gt_bardana" value="<?php echo $gt_bardana; ?>">
                <input type="hidden" id="gt_wt" value="<?php echo $gt_wt; ?>">
                <input type="hidden" id="gt_exp" value="<?php echo $gt_exp; ?>">
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#count_rows_span").text($("#count_rows").val());
    $("#gt_bardana_input").text($("#gt_bardana").val());
    $("#gt_wt_input").text($("#gt_wt").val());
    $("#gt_exp_input").text($("#gt_exp").val());
</script>
<script type="text/javascript">
    $('#r_date_start, #r_date_end').change(function () {
        document.datesSubmit.submit();
    });
    $('#godam_loading_id').on('change', function () {
        document.datesSubmit.submit();
    });
</script>
