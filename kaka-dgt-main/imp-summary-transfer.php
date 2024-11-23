<?php include("header.php"); ?>
<?php
if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['type'])
    && ($_GET['type'] == 'kiraya-summary' || $_GET['type'] == 'beopari-summary' || $_GET['type'] == 'godam-mazdoori')
) {
    $urlArray = array(
        'kiraya-summary' => array('path' => 'imp-kiraya-summary', 'title' => ' کرایہ سمری', 'type' => 'کرایہ سمری', 'transfered_from' => 'kiraya_summary', 'khaata_' => 'khaata_ks'),
        'beopari-summary' => array('path' => 'imp-beopari-summary', 'title' => 'بیوپاری سمری کلو', 'type' => 'بیوپاری سمری', 'transfered_from' => 'beopari_summary', 'khaata_' => 'khaata_bs'),
        'godam-mazdoori' => array('path' => 'imp-godam-mazdoori-bill', 'title' => 'امپورٹ گودام مزدوری بل', 'type' => 'امپورٹ گودام مزدوری بل', 'transfered_from' => 'godam_mazdoori', 'khaata_' => 'khaata_gm')
    );
    $page = $urlArray[$_GET["type"]];
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('imp_truck_loadings', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $jmaa_khaata_no = $bnaam_khaata_no = $jmaaName = $bnaamName = "";
    if (!empty($record[$page['khaata_']])) {
        $khaataJson = json_decode($record[$page['khaata_']]);
        $jmaa_khaata_no = $khaataJson->jmaa_khaata_no;
        $jmaa_khaata_id = $khaataJson->jmaa_khaata_id;
        $bnaam_khaata_no = $khaataJson->bnaam_khaata_no;
        $bnaam_khaata_id = $khaataJson->bnaam_khaata_id;
        $jmaaName = getTableDataByIdAndColName('khaata', $jmaa_khaata_id, 'khaata_name');
        $bnaamName = getTableDataByIdAndColName('khaata', $bnaam_khaata_id, 'khaata_name');
    } ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin mt-n2">
        <div>
            <h4 class="mb-3 mb-md-0 mt-n2"><?php echo $page['title']; ?></h4>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php echo backUrl($page['path']); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="p-2">
                    <div class="row gx-0 gy-2">
                        <div class="col-3">
                            <div class="input-group">
                                <label for="ser" class="input-group-text urdu">لوڈنگ سیریل</label>
                                <input type="text" id="ser" class="form-control" disabled
                                       value="<?php echo $id; ?>">
                                <label for="today"
                                       class="input-group-text input-group-addon bg-transparent urdu">لوڈنگ
                                    تاریخ</label>
                                <input type="text" id="today" class="form-control bg-transparent border-primary"
                                       placeholder="تاریخ" value="<?php echo $record['loading_date']; ?>"
                                       disabled>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="input-group">
                                <label for="owner_name" class="input-group-text urdu">مالک نام</label>
                                <input type="text" id="owner_name" name="owner_name"
                                       class="form-control input-urdu" disabled
                                       value="<?php echo $record['owner_name']; ?>">
                                <label for="jins" class="input-group-text urdu">جنس</label>
                                <input type="text" id="jins" name="jins" class="form-control input-urdu"
                                       disabled value="<?php echo $record['jins']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="truck_no" class="input-group-text urdu">ٹرک نمبر</label>
                                <input type="text" id="truck_no" name="truck_no" class="form-control" required
                                       value="<?php echo $record['truck_no']; ?>" disabled>
                                <label for="truck_name" class="input-group-text urdu">ٹرک نام</label>
                                <input type="text" id="truck_name" name="truck_name" tabindex="-1"
                                       class="form-control" disabled value="<?php echo $record['truck_name']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label for="driver_name" class="input-group-text urdu">ڈرائیور نام</label>
                                <input type="text" id="driver_name" name="" disabled
                                       class="form-control urdu-2 " required readonly
                                       value="<?php echo $record['driver_name']; ?>">
                                <label for="driver_mobile" class="input-group-text urdu">موبائل</label>
                                <input type="text" id="driver_mobile" name="driver_mobile" tabindex="-1"
                                       class="form-control ltr small-2" disabled
                                       value="<?php echo $record['driver_mobile']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label class="input-group-text urdu">لوڈنگ کرنے گودام</label>
                                <?php $loadings = fetch('godam_loading_forms', array('id' => $record['godam_loading_id']));
                                $loading = mysqli_fetch_assoc($loadings); ?>
                                <input type="text" value="<?php echo $loading['name']; ?>"
                                       class="form-control bold urdu-2" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" class="form-control ltr bold" disabled
                                       value="<?php echo $loading['mobile1']; ?>">
                                <label class="input-group-text urdu">منشی کانام</label>
                                <input type="text" class="form-control urdu-2 bold" disabled
                                       value="<?php echo $loading['munshi'] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label class="input-group-text urdu">پتہ</label>
                                <input type="text" disabled value="<?php echo $loading['address'] ?>"
                                       class="form-control urdu-2 bold">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group">
                                <label class="input-group-text urdu">خالی کرنے گودام</label>
                                <?php $empties = fetch('godam_empty_forms', array('id' => $record['godam_empty_id']));
                                $empty = mysqli_fetch_assoc($empties); ?>
                                <input type="text" value="<?php echo $empty['name'] ?>"
                                       class="form-control bold urdu-2" disabled="">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">موبائل نمبر</label>
                                <input type="text" class="form-control ltr bold" disabled
                                       value="<?php echo $empty['mobile1'] ?>">
                                <label class="input-group-text urdu">منشی کانام</label>
                                <input type="text" class="form-control urdu-2 bold" disabled
                                       value="<?php echo $empty['munshi'] ?>">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group">
                                <label class="input-group-text urdu">پتہ</label>
                                <input type="text" disabled value="<?php echo $empty['address'] ?>"
                                       class="form-control urdu-2 bold">
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <label class="input-group-text urdu">کنسائینی نام</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['consignee_name']; ?>">
                                <label for="sender_city" class="input-group-text urdu">بھیجنے والا شہر</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['sender_city']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group">
                                <label class="input-group-text urdu">لوڈنگ رپورٹ</label>
                                <input type="text" class="form-control input-urdu" disabled
                                       value="<?php echo $record['report']; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-2 pb-3 d-print-none">
                <form id="insert_form">
                    <div class="row gx-0 row-cols me-2">
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="sr_no">سیریل نمبر</label>
                                <input type="text" id="sr_no" name="sr_no" class="form-control" disabled
                                       value="<?php echo getMaalSerial($id); ?>">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="jins_name">جنس نام</label>
                                <input type="text" id="jins_name" name="jins_name"
                                       class="form-control input-urdu" required readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="bardana_name">باردانہ نام</label>
                                <input type="text" id="bardana_name" name="bardana_name"
                                       class="input-urdu form-control" required readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="bardana_qty">باردانہ تعداد</label>
                                <input type="text" id="bardana_qty" name="bardana_qty" onkeyup="totalWt()"
                                       class="form-control currency" required readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="per_wt">فی وزن</label>
                                <input type="text" id="per_wt" name="per_wt" class="form-control currency" required
                                       onkeyup="totalWt()" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="total_wt">ٹوٹل وزن</label>
                                <input type="text" id="total_wt" name="total_wt" class="form-control currency"
                                       readonly tabindex="-1">
                            </div>
                        </div>
                        <script>
                            function totalWt(e) {
                                let value = $(e).val();
                                let id = $(e).attr('id');
                                let bardana_qty = $("#bardana_qty").val();
                                let per_wt = $("#per_wt").val();
                                let total_wt = Number(bardana_qty) * Number(per_wt);
                                $("#total_wt").val(total_wt);
                            }
                        </script>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="empty_wt">خالی وزن</label>
                                <input type="text" id="empty_wt" name="empty_wt" class="form-control currency"
                                       required onkeyup="totalKWt()" readonly>
                            </div>
                        </div>
                        <script>
                            function totalKWt(e) {
                                let empty_wt = $("#empty_wt").val();
                                let bardana_qty = $("#bardana_qty").val();
                                let total_empty_wt = Number(bardana_qty) * Number(empty_wt);
                                $("#total_empty_wt").val(total_empty_wt);
                                let ww = $("#total_wt").val();
                                //alert("Total wt=" + ww + " Total empty wt=" + total_empty_wt);
                                let saaf_wt = Number(ww) - Number(total_empty_wt);
                                $("#saaf_wt").val(saaf_wt);
                            }
                        </script>
                    </div>
                    <div class="row gx-0 row-cols mt-2 me-2">
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="total_empty_wt">ٹوٹل خالی وزن </label>
                                <input type="text" id="total_empty_wt" name="total_empty_wt" readonly tabindex="-1"
                                       class="form-control currency">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="saaf_wt">صاف وزن</label>
                                <input type="text" id="saaf_wt" name="saaf_wt" readonly tabindex="-1"
                                       class="form-control currency">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="taqseem_name">تقسیم نام</label>
                                <input placeholder="نام" type="text" id="taqseem_name" name="taqseem_name"
                                       class="form-control input-urdu" required>
                                <label class="input-group-text urdu" for="taqseem_no"> نمبر</label>
                                <input placeholder="نمبر" type="text" id="taqseem_no" name="taqseem_no"
                                       class="form-control urdu-2" onkeyup="totalTaqseemQty(this)" required>
                            </div>
                        </div>
                        <script>
                            function totalTaqseemQty(e) {
                                let saaf_wt_t = $("#saaf_wt").val();
                                let taqseem_no = $("#taqseem_no").val();
                                if (taqseem_no > 0) {
                                    let taqseem_qty = Number(saaf_wt_t) / Number(taqseem_no);
                                    taqseem_qty = taqseem_qty.toFixed(2);
                                    $("#taqseem_qty").val(taqseem_qty);
                                }
                            }
                        </script>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="taqseem_qty">ٹوٹل تقسیم تعداد</label>
                                <input type="text" id="taqseem_qty" name="taqseem_qty" required
                                       class="form-control" readonly tabindex="-1">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="per_mazdoori">فی مزدوری</label>
                                <input type="text" id="per_mazdoori" name="per_mazdoori" required
                                       class="form-control currency" onkeyup="totalExpLast()">
                            </div>
                        </div>
                        <script>
                            function totalExpLast(e) {
                                let per_mazdoori = $("#per_mazdoori").val();
                                let taqseem_qty_tt = $("#taqseem_qty").val();
                                let total_exp = Number(per_mazdoori) * Number(taqseem_qty_tt);
                                total_exp = total_exp.toFixed(3);
                                $("#total_exp").val(total_exp);
                            }
                        </script>
                        <div class="col">
                            <div class="input-group">
                                <label class="input-group-text urdu" for="total_exp">ٹوٹل خرچہ</label>
                                <input type="text" id="total_exp" name="total_exp" class="form-control" readonly value="0" tabindex="-1">
                                <?php //if (empty($record[$page['khaata_']])) { ?>
                                    <button type="submit" name="recordSubmit" id="recordSubmit"
                                            class="btn btn-outline-danger pt-0 mt-1 d-none">تبدیل
                                    </button>
                                <?php //} ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="imp_truck_maals_id" id="imp_truck_maals_id" value="0">
                    <input type="hidden" name="form_name" value="<?php echo $page['transfered_from']; ?>">
                    <input type="hidden" name="imp_tl_id" value="<?php echo $id; ?>">
                </form>
            </div>
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="row gx-2">
                <div class="col-md-10">
                    <div class="card mt-2 border-top-0">
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
                                    <th>تقسیم تعداد</th>
                                    <th>فی مزدوری</th>
                                    <th>ٹوٹل خرچہ</th>
                                </tr>
                                </thead>
                                <tbody id="records_table">
                                <?php $maals = fetch('imp_truck_maals', array('imp_tl_id' => $id));
                                $x = 1;
                                $remainingRows = $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = $total_expFinal = 0;
                                while ($maal = mysqli_fetch_assoc($maals)) {
                                    $maal2 = isKirayaAdded($maal['id'], $page['transfered_from']);
                                    if ($maal2['success']) {
                                        $maal2Id = $maal2['output']['id'];
                                        $json2 = json_decode($maal2['output']['json_data']);
                                        $taqseem_qty = $json2->taqseem_qty;
                                        $per_mazdoori = $json2->per_mazdoori;
                                        $total_exp = $json2->total_exp;
                                        $total_expFinal += $json2->total_exp;
                                    } else {
                                        $maal2Id = $taqseem_qty = $per_mazdoori = $total_exp = 0;
                                    }
                                    $json = json_decode($maal['json_data']); ?>
                                    <tr class="row-py-0 cursor-pointer" id="<?php echo $maal['id']; ?>"
                                        data-maal2-id="<?php echo $maal2Id; ?>"
                                        data-form-name="<?php echo $page['transfered_from']; ?>"
                                        onclick="maalEntryRowEdit(this)">
                                        <td><?php echo $x; ?></td>
                                        <td><?php echo $json->jins_name; ?></td>
                                        <td><?php echo $json->bardana_name; ?></td>
                                        <td><?php echo $json->bardana_qty; ?></td>
                                        <td><?php echo $json->per_wt; ?></td>
                                        <td><?php echo $json->total_wt; ?></td>
                                        <td class="ltr"><?php echo $json->empty_wt; ?></td>
                                        <td><?php echo $json->total_empty_wt; ?></td>
                                        <td class="ltr"><?php echo $json->saaf_wt; ?></td>
                                        <?php if ($maal2Id > 0) { ?>
                                            <td><?php echo $taqseem_qty; ?></td>
                                            <td><?php echo $per_mazdoori; ?></td>
                                            <td><?php echo $total_exp; ?></td>
                                        <?php } else {
                                            echo '<td colspan="3"></td>';
                                        } ?>
                                    </tr>
                                    <?php $x++;
                                    $remainingRows++;
                                    $bardana_qty += $json->bardana_qty;
                                    $per_wt += $json->per_wt;
                                    $total_wt += $json->total_wt;
                                    $empty_wt += $json->empty_wt;
                                    $total_empty_wt += $json->total_empty_wt;
                                    $saaf_wt += $json->saaf_wt;
                                    if ($maal2Id > 0) {
                                        $remainingRows--;
                                    }
                                } ?>
                                <tr class="row-py-0 bg-info bg-opacity-25 bold">
                                    <td><?php echo $x - 1; ?></td>
                                    <td colspan="2"></td>
                                    <td><?php echo $bardana_qty; ?></td>
                                    <td><?php echo $per_wt; ?></td>
                                    <td><?php echo $total_wt; ?></td>
                                    <td><?php echo $empty_wt; ?></td>
                                    <td><?php echo $total_empty_wt; ?></td>
                                    <td class="ltr"><?php echo $saaf_wt; ?></td>
                                    <td colspan="2"></td>
                                    <td><span id="total_exp_final"><?php echo $total_expFinal; ?></span>
                                        <input type="hidden" value="<?php echo $remainingRows; ?>" id="remainingRows">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <form method="post">
                                    <div class="input-group">
                                        <?php $reportsQ = fetch('imp_truck_reports', array('imp_tl_id' => $id, 'form_name' => $page['transfered_from']));
                                        if (mysqli_num_rows($reportsQ) > 0) {
                                            $dataR = mysqli_fetch_assoc($reportsQ);
                                            $report_action = "update";
                                            $report_summary_val = $dataR['report_summary'];
                                        } else {
                                            $report_action = "insert";
                                            $report_summary_val = "";
                                        } ?>
                                        <label for="report_summary" class="input-group-text urdu">سمری رپورٹ</label>
                                        <input type="text" id="report_summary" name="report_summary"
                                               class="form-control input-urdu" required
                                               value="<?php echo $report_summary_val; ?>">
                                        <input type="hidden" name="form_name"
                                               value="<?php echo $page['transfered_from']; ?>">
                                        <input type="hidden" name="imp_tl_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="report_action" value="<?php echo $report_action; ?>">
                                        <input type="hidden" name="page_type" value="<?php echo $_GET["type"]; ?>">
                                        <button name="report_summary_submit" id="report_summary_submit" type="submit"
                                                class="btn btn-secondary btn-icon-text btn-sm">
                                            <i class="btn-icon-prepend" data-feather="check-square"></i>رپورٹ محفوظ
                                        </button>
                                    </div>
                                </form>
                                <?php if (isset($_POST['report_summary_submit'])) {
                                    $report_action = mysqli_real_escape_string($connect, $_POST['report_action']);
                                    $form_name = mysqli_real_escape_string($connect, $_POST['form_name']);
                                    $imp_tl_id = mysqli_real_escape_string($connect, $_POST['imp_tl_id']);
                                    $page_type = mysqli_real_escape_string($connect, $_POST['page_type']);
                                    $dataR = array(
                                        'report_summary' => mysqli_real_escape_string($connect, $_POST['report_summary'])
                                    );
                                    switch ($report_action) {
                                        case 'insert':
                                            $dataR['form_name'] = $form_name;
                                            $dataR['imp_tl_id'] = $imp_tl_id;
                                            $dataR['created_at'] = date('Y-m-d H:i:s');
                                            $dataR['created_by'] = $userId;
                                            $s_report_added = insert('imp_truck_reports', $dataR);
                                            break;
                                        case 'update':
                                            $dataR['updated_at'] = date('Y-m-d H:i:s');
                                            $dataR['updated_by'] = $userId;
                                            $s_report_added = update('imp_truck_reports', $dataR, array('imp_tl_id' => $id, 'form_name' => $page['transfered_from']));
                                            break;
                                        default:
                                            exit(1);
                                    }
                                    $urlll = 'imp-summary-transfer?id=' . $imp_tl_id . '&type=' . $page_type;
                                    if ($s_report_added) {
                                        message('success', $urlll, 'سمری رپورٹ محفوظ ہو گئی ہے۔');
                                    } else {
                                        message('danger', $urlll, 'ڈیٹابیس پرابلم۔');
                                    }
                                } ?>
                            </div>
                        </div>
                        <form action="ajax/transferImpKirayaSummary.php" method="post">
                            <div class="row gx-0 mt-5">
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_jmaa_khaata_no" class="input-group-text urdu">جمع کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_jmaa_khaata_no" name="afg_jmaa_khaata_no"
                                               class="form-control bg-transparent" required autofocus
                                               value="<?php echo $jmaa_khaata_no; ?>" onchange="transferToRoznamcha()">
                                        <small id="response1"
                                               class="text-danger urdu position-absolute top-0 left-0"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id1" name="afg_jmaa_khaata_id">
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="afg_bnaam_khaata_no" class="input-group-text urdu">بنام کھاتہ
                                            نمبر</label>
                                        <input type="text" id="afg_bnaam_khaata_no" name="afg_bnaam_khaata_no"
                                               class="form-control bg-transparent" required
                                               value="<?php echo $bnaam_khaata_no; ?>" onchange="transferToRoznamcha()">
                                        <small id="response2"
                                               class="text-danger urdu position-absolute top-0 left-0"></small>
                                    </div>
                                    <input type="hidden" id="khaata_id2" name="afg_bnaam_khaata_id">
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group">
                                        <label for="total" class="input-group-text urdu">ٹوٹل بل</label>
                                        <input type="text" id="total" readonly name="total_bill"
                                               class="form-control bold" required>
                                    </div>
                                </div>
                                <input type="hidden" name="transfered_from" value="<?php echo $page["transfered_from"]; ?>">
                                <input type="hidden" name="type" value="<?php echo $page["type"]; ?>">
                                <input type="hidden" name="url" value="<?php echo $page['path']; ?>">
                                <input type="hidden" name="tl_id_hidden" value="<?php echo $id; ?>">
                                <input type="hidden" name="form_name" value="<?php echo $page['transfered_from']; ?>">
                                <div class="col-lg-3">
                                    <button name="recordSubmitFinal" id="recordSubmitFinal" type="submit"
                                            class="btn btn-primary"><i class="fa fa-check-square"></i> روزنامچہ میں ٹرانسفر
                                    </button>
                                    <?php if (empty($record[$page['khaata_']])) { ?>
                                    <?php } else {
                                        echo '<a class="btn btn-success ms-2" href="print/imp-summary-transfer?id=' . $record["id"] . '&secret=' . base64_encode("powered-by-upsol") . '&url=' . base64_encode("imp-summary-transfer") . '&type=' . $page['transfered_from'] . '"><i class="fa fa-print"></i> پرنٹ </a>';
                                        echo '<p class="text-danger urdu small bold">یہ سمری روزنامچہ میں ٹرانسفر ہے۔</p>';
                                    } ?>
                                </div>
                                <div class="col-md-3">
                                    <span id="remainingRowsMsg" class="text-warning bold urdu"></span>
                                    <span id="totalBillMsg" class="text-danger bold urdu ms-2"></span>
                                </div>
                                <?php if (!empty($record[$page['khaata_']])) {
                                    $rozQ = fetch('roznamchaas', array('r_type' => 'karobar', 'transfered_from_id' => $id, 'transfered_from' => $page['transfered_from']));
                                    if (mysqli_num_rows($rozQ) > 0) { ?>
                                        <div class="col-md-12">
                                            <table class="table table-sm table-bordered mb-0 mt-3">
                                                <thead class="table-primary">
                                                <tr>
                                                    <th>سیریل</th>
                                                    <th>تاریخ</th>
                                                    <th>کھاتہ نمبر</th>
                                                    <th>روزنامچہ نمبر</th>
                                                    <th>نام</th>
                                                    <th>نمبر</th>
                                                    <th width="40%">تفصیل</th>
                                                    <th>جمع</th>
                                                    <th>بنام</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php while ($roz = mysqli_fetch_assoc($rozQ)) {
                                                    $jmaa_amount = $roz['jmaa_amount'];
                                                    //echo $jmaa_amount; ?>
                                                    <tr>
                                                        <td><?php echo Administrator() ? $roz['branch_serial'] . ' - ' . $roz['r_id'] : $roz['branch_serial']; ?></td>
                                                        <td>
                                                            <?php echo $roz['r_date']; ?>
                                                            <input type="hidden" value="<?php echo $roz['r_id']; ?>" name="r_id[]">
                                                        </td>
                                                        <td><?php echo $roz['khaata_no']; ?></td>
                                                        <td><?php echo $roz['roznamcha_no']; ?></td>
                                                        <td class="small"><?php echo $roz['r_name']; ?></td>
                                                        <td><?php echo $roz['r_no']; ?></td>
                                                        <?php $str = "";
                                                        if ($roz['jmaa_amount'] == 0) {
                                                            $str = "بنام:- ";
                                                        }
                                                        if ($roz['bnaam_amount'] == 0) {
                                                            $str = "جمع:- ";
                                                        } ?>
                                                        <td class="small bold-"><?php echo $str . $roz['details']; ?></td>
                                                        <td class="text-success"><?php echo $roz['jmaa_amount']; ?></td>
                                                        <td class="text-danger"><?php echo $roz['bnaam_amount']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-2 d-print-none">
                    <div class="card mt-2">
                        <div class="urdu-2">
                            <h5 class="bg-success bg-opacity-25 p-2">جمع کھاتہ نام</h5>
                            <p class="p-1 bold text-primary" id="jm_kh_tafseel"><?php echo $jmaaName; ?></p>
                            <h5 class="bg-success bg-opacity-25 p-2">بنام کھاتہ نام</h5>
                            <p class="p-1 bold text-primary" id="bm_kh_tafseel"><?php echo $bnaamName; ?></p>
                        </div>
                    </div>
                    <a href="imp-beopari-summary-expenses?id=<?php echo $id; ?>&type=<?php echo $page['transfered_from'] . '_ee'; ?>"
                       class="btn btn-pinterest pt-0 w-100 mt-2">اضافی خرچہ</a>
                </div>
            </div>
        </div>
    </div>
<?php } else {
    echo '<script>window.location.href="./";</script>';
} ?>
<?php include("footer.php"); ?>
<script>
    function maalEntryRowEdit(e) {
        let id = $(e).attr('id');
        let maal2id = $(e).attr('data-maal2-id');
        let form_name = $(e).attr('data-form-name');

        $.ajax({
            url: "ajax/fetchSingleImpTruckMaalEntry.php",
            method: "POST",
            data: {id: id, maal2id: maal2id, form_name: form_name},
            success: function (data) {
                totalBill();
                $(e).addClass('bg-warning');
                $(e).siblings().removeClass("bg-warning");
                //console.log(data);
                ///let dd = $.parseJSON(data);
                let dd = JSON.parse(data);
                //console.log(dd.json2);
                //$('#recordSubmit').text("تبدیل");
                $('#recordSubmit').addClass("d-block");
                $('#recordSubmit').removeClass("d-none");
                $('#sr_no').val(id);
                $('#imp_truck_maals_id').val(id);
                $('#imp_truck_maals_action').val("update");
                $('#jins_name').val(dd.json1.jins_name);
                $('#bardana_name').val(dd.json1.bardana_name);
                $('#bardana_qty').val(dd.json1.bardana_qty);
                $('#per_wt').val(dd.json1.per_wt);
                $('#total_wt').val(dd.json1.total_wt);
                $('#empty_wt').val(dd.json1.empty_wt);
                $('#total_empty_wt').val(dd.json1.total_empty_wt);
                $('#saaf_wt').val(dd.json1.saaf_wt);
                if (dd.json2 === undefined) {
                    $('#taqseem_name').val("");
                    $('#taqseem_no').val("");
                    $('#taqseem_qty').val("");
                    $('#per_mazdoori').val("");
                    $('#total_exp').val("");
                } else {
                    $('#taqseem_name').val(dd.json2.taqseem_name);
                    $('#taqseem_no').val(dd.json2.taqseem_no);
                    $('#taqseem_qty').val(dd.json2.taqseem_qty);
                    $('#per_mazdoori').val(dd.json2.per_mazdoori);
                    $('#total_exp').val(dd.json2.total_exp);
                }
                $('#taqseem_name').focus();
            },
            error: function () {

            }
        });
    }
</script>
<script>
    totalBill();

    function totalBill() {
        let total_exp_final = $('#total_exp_final').text();
        //alert(total_exp_final);
        $('#total').val(total_exp_final);
    }

    $('#insert_form').on("submit", function (event) {
        event.preventDefault();
        $.ajax({
            url: "ajax/impTruckMaalsKirayaEntry.php",
            method: "POST",
            data: $('#insert_form').serialize(),
            beforeSend: function () {
                $('#recordSubmit').val("ڈیٹ محفوظ ہورہاہے");
            },
            success: function (data) {
                console.log(data);
                $('#records_table').html(data);
                $('#insert_form')[0].reset();
                $('#recordSubmit').addClass("d-none");
                $('#recordSubmit').removeClass("d-block");
                $(".alert-dismissible").fadeTo(5000, 1000).slideUp(1000, function () {
                    $(".alert-dismissible").slideUp(1000);
                    $(".alert-section").addClass('d-none');
                });
                let sr_no = $('#sr_no').val();
                sr_no = Number(sr_no) + 1;
                $('#sr_no').val(sr_no);
                totalBill();
                remainingRows();
                $('#afg_jmaa_khaata_no').focus();
            }
        });
    });
</script>
<script>
    remainingRows();
    function remainingRows() {
        let remainingRows = $("#remainingRows").val();
        if (remainingRows > 0) {
            let msg = remainingRows + ' لائن باقی';
            $("#remainingRowsMsg").text(msg);
            $("#recordSubmitFinal").hide();
        } else {
            $("#recordSubmitFinal").show();
            $("#remainingRowsMsg").hide();
        }
    }
</script>
<script type="text/javascript">
    transferToRoznamcha();
    fetchKhaataJmaa();
    fetchKhaataBnaam();
    $(document).on('keyup', "#afg_jmaa_khaata_no", function (e) {
        transferToRoznamcha();
        fetchKhaataJmaa();
    });
    $(document).on('keyup', "#afg_bnaam_khaata_no", function (e) {
        transferToRoznamcha();
        fetchKhaataBnaam();
    });

    function fetchKhaataJmaa() {
        var khaata_no = $("#afg_jmaa_khaata_no").val();
        var khaata_id1 = $("#khaata_id1");
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    khaata_id1.val(response.messages['khaata_id']);
                    $("#response1").text('');
                    let res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                    $("#jm_kh_tafseel").html(res);
                    transferToRoznamcha();
                }
                if (response.success === false) {
                    $("#response1").text('جمع کھاتہ نمبر');
                    $("#jm_kh_tafseel").text('');
                    khaata_id1.val(0);
                    transferToRoznamcha();
                }
            }
        });
    }
    function fetchKhaataBnaam() {
        var khaata_no = $("#afg_bnaam_khaata_no").val();
        var khaata_id2 = $("#khaata_id2");
        $.ajax({
            url: 'ajax/fetchSingleKhaata.php',
            type: 'post',
            data: {khaata_no: khaata_no},
            dataType: 'json',
            success: function (response) {
                if (response.success === true) {
                    khaata_id2.val(response.messages['khaata_id']);
                    $("#response2").text('');
                    let res = response.messages['khaata_name']
                        + '<span class="badge bg-success ">' + response.messages['b_name'] + '</span>'
                        + '<span class="badge bg-success ltr ms-1">' + response.messages['mobile'] + '</span>';
                    $("#bm_kh_tafseel").html(res);
                    transferToRoznamcha();
                }
                if (response.success === false) {
                    $("#response2").text('بنام کھاتہ نمبر');
                    $("#bm_kh_tafseel").text('');
                    khaata_id2.val(0);
                    transferToRoznamcha();
                }
            }
        });
    }

    function transferToRoznamcha() {
        let msg = '';
        let totalBillMsg = $("#totalBillMsg");
        let khaata_id1 = $("#khaata_id1").val();
        let khaata_id2 = $("#khaata_id2").val();
        let total = $("#total").val();
        if (khaata_id1 <= 0 || khaata_id2 <= 0) {/*|| total <= 0*/
            totalBillMsg.show();
            if (khaata_id1 <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = 'جمع کھاتہ';
            }
            if (khaata_id2 <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = 'بنام کھاتہ';
            }
            /*if (total <= 0) {
                $("#recordSubmitFinal").prop('disabled', true);
                msg = ' ٹوٹل بل خالی زیرو ہے۔';
            }*/
        } else {
            msg = '';
            $("#recordSubmitFinal").prop('disabled', false);
            totalBillMsg.hide();
        }
        totalBillMsg.text(msg);
    }
</script>