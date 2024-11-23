<?php include("header.php"); ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin_">
        <div>
            <h3 class="mb-3 mb-md-0 mt-n2 urdu-2">قندھار کسٹم کلئیرنس انٹری</h3>
        </div>
        <div class="d-flex align-items-center flex-wrap text-nowrap">
            <?php if (isset($_GET['source']) && $_GET['source'] == 'surrender') {
                $backUrl = 'ut-surrender-bails';
            } else {
                $backUrl = 'ut-qandhar-custom';
            }
            echo '<a href="' . $backUrl . '" class="btn btn-dark btn-icon-text p-1 pt-0 mt-n2">
                <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>واپس </a>';
            ?>
        </div>
    </div>
    <div class="row">
        <?php if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('ut_bail_entries', array('id' => $id));
            $record = mysqli_fetch_assoc($records); ?>
            <div class="col-md-10 position-relative">
                <?php include("ut-bail-details.php"); ?>
                <div class="card mt-2">
                    <h3 class="urdu-2 bg-primary text-white text-center">قندھار کسٹم کلئیرنس انٹری</h3>
                    <div class="card-body pt-1">
                        <?php if (empty($record['qandhar_json'])) {
                            $qandharJson = array(
                                'qandhar_receiving_date' => date('Y-m-d'),
                                'qandhar_clearance_date' => date('Y-m-d'),
                                'qandhar_report' => '',
                                'docs' => ''
                            );
                        } else {
                            $qandhar_json = json_decode($record['qandhar_json']);
                            $qandharJson = array(
                                'qandhar_receiving_date' => $qandhar_json->qandhar_receiving_date,
                                'qandhar_clearance_date' => $qandhar_json->qandhar_clearance_date,
                                'qandhar_report' => $qandhar_json->qandhar_report
                            );
                            if (!empty($qandhar_json->docs)) {
                                $qandharJson['docs'] = $qandhar_json->docs;
                            }
                        } ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="row gx-0 gy-5">
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="qandhar_receiving_date" class="input-group-text urdu">قندھار پہنچ
                                            تاریخ</label>
                                        <input value="<?php echo $qandharJson['qandhar_receiving_date']; ?>"
                                               type="text" name="qandhar_receiving_date" autofocus
                                               class="form-control" id="qandhar_receiving_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="input-group flatpickr" id="flatpickr-date">
                                        <label for="qandhar_clearance_date" class="input-group-text urdu">کلئیرنس
                                            تاریخ</label>
                                        <input value="<?php echo $qandharJson['qandhar_clearance_date']; ?>"
                                               type="text" name="qandhar_clearance_date"
                                               class="form-control" id="qandhar_clearance_date" required data-input>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <label for="qandhar_report" class="input-group-text urdu">رپورٹ</label>
                                        <input type="text" id="qandhar_report" name="qandhar_report"
                                               class="form-control input-urdu" required
                                               value="<?php echo $qandharJson['qandhar_report']; ?>">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <?php if (!empty($qandharJson['docs'])) {
                                        echo '<div class="btn-group">';
                                        $docs = $qandharJson['docs'];
                                        foreach ($docs as $doc) {
                                            echo '<a href="' . $doc . '" target="_blank" class="btn btn-success me-2">' . substr($doc, '28') . '</a>';
                                        }
                                        echo '</div>';
                                        echo '<input type="hidden" name="old_docs[]" value="' . implode(",", $docs) . '">';
                                    } ?>
                                    <div class="input-group">
                                        <label for="qandhar_docs" class="input-group-text urdu">PDF ڈاکومنٹس
                                            (آپشنل)</label>
                                        <input type="file" id="qandhar_docs" name="qandhar_docs[]"
                                               class="form-control" multiple accept="application/pdf">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                            <div class="row mt-4">
                                <div class="col-4">
                                    <button type="submit" name="recordUpdate" id="recordUpdate"
                                            class="btn btn-dark btn-icon-text w-100">
                                        <i class="btn-icon-prepend" data-feather="edit-3"></i>محفوظ کریں
                                    </button>
                                </div>
                                <div class="col-8">
                                    <?php if (isset($_SESSION['response'])) {
                                        echo $_SESSION['response'];
                                        unset($_SESSION['response']);
                                    } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card">
                    <div class="p-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="loading_date_" class="input-group-text urdu">لوڈنگ تاریخ</label>
                                    <input type="text" id="loading_date_" class="form-control" disabled
                                           value="<?php echo $record['loading_date']; ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="ser" class="input-group-text urdu">سیریل نمبر</label>
                                    <input type="text" id="ser" class="form-control" disabled
                                           value="<?php echo $id; ?>">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="userName" class="input-group-text urdu">آئی ڈی نام</label>
                                    <input type="text" id="userName" class="form-control bg-transparent"
                                           required
                                           value="<?php echo $record['username']; ?>" readonly tabindex="-1">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group bg-info bg-opacity-10">
                                    <label for="" class="input-group-text urdu">برانچ کانام</label>
                                    <input type="text" name="" readonly tabindex="-1"
                                           class="form-control urdu-2 bold bg-transparent"
                                           required
                                           value="<?php echo getTableDataByIdAndColName('branches', $record['branch_id'], 'b_name'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else {
            message('danger', 'ut-qandhar-custom', 'دوبارہ کوشش کریں');
        } ?>
    </div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "ut-qandhar-custom-add?id=" . $hidden_id;
    $qandharArray = array(
        'qandhar_receiving_date' => mysqli_real_escape_string($connect, $_POST['qandhar_receiving_date']),
        'qandhar_clearance_date' => mysqli_real_escape_string($connect, $_POST['qandhar_clearance_date']),
        'qandhar_report' => mysqli_real_escape_string($connect, $_POST['qandhar_report'])
    );
    $targetDir = "docs/";
    $allowTypes = array('pdf', 'PDF');
    $fileNames = array_filter($_FILES['qandhar_docs']['name']);
    if (!empty($fileNames)) {
        $docsArray = array();
        foreach ($_FILES['qandhar_docs']['name'] as $key => $val) {
            $fileName = basename($_FILES['qandhar_docs']['name'][$key]);
            $targetFilePath = $targetDir . QANDHAR . '_' . date("YmdHis") . '_' . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES["qandhar_docs"]["tmp_name"][$key], $targetFilePath)) {
                    $docsArray[$key] = $targetFilePath;
                    //$docsArray[$key] = $fileName;
                    //$msg .= ' ' . $_FILES['files']['name'][$key] . ' ';
                } else {
                    $errorUpload .= $_FILES['qandhar_docs']['name'][$key] . ' | ';
                }
            } else {
                $errorUploadType .= $_FILES['qandhar_docs']['name'][$key] . ' | ';
            }
        }
        if (isset($_POST['old_docs'])) {
            $old_docs = $_POST['old_docs'];
            $docsArrayUpdated = array_merge($old_docs, $docsArray);
            $qandharArray['docs'] = $docsArrayUpdated;
        } else {
            $qandharArray['docs'] = $docsArray;
        }

    }
    $qandharJsone = json_encode($qandharArray, JSON_UNESCAPED_UNICODE);
    $data = array(
        'qandhar_json' => $qandharJsone
    );
    $done = update('ut_bail_entries', $data, array('id' => $hidden_id));
    if ($done) {
        message('success', $url, 'قندھار کسٹم کلئیرنس انٹری محفوظ ہوگئی ہے۔');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }
} ?>