<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نیا کھاتہ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <?php echo backUrl('khaata'); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12 d-print-none">
        <?php if (isset($_SESSION['response'])) {
            echo $_SESSION['response'];
            unset($_SESSION['response']);
        } ?>
        <?php if (isset($_GET['id'])) {
            $id = mysqli_real_escape_string($connect, $_GET['id']);
            $records = fetch('khaata', array('id' => $id));
            $record = mysqli_fetch_assoc($records); ?>
            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row gx-0 gy-5">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="khaata_no" class="input-group-text urdu">کھاتہ
                                                    نمبر</label>
                                                <input type="text" id="khaata_no" name="khaata_no"
                                                       class="form-control" required
                                                       autofocus value="<?php echo $record['khaata_no']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="cat_id" class="input-group-text urdu">کیٹیگری</label>
                                            <select class="form-select" name="cat_id" id="cat_id" required>
                                                <option selected hidden disabled="disabled" value="">انتخاب</option>
                                                <?php $cats = fetch('cats');
                                                $catSelected = '';
                                                while ($cat = mysqli_fetch_assoc($cats)) {
                                                    $catSelected = $cat['id'] == $record['cat_id'] ? 'selected' : '';
                                                    echo '<option ' . $catSelected . ' value="' . $cat['id'] . '">' . $cat['c_name'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="branch_id" class="input-group-text urdu">برانچ
                                                کانام</label>
                                            <select id="branch_id" name="branch_id" class="form-select">
                                                <option hidden disabled selected value="">برانچ</option>
                                                <?php $branches = fetch('branches');
                                                while ($branch = mysqli_fetch_assoc($branches)) {
                                                    $brancSelected = $branch['id'] == $record['branch_id'] ? 'selected' : '';
                                                    echo '<option ' . $brancSelected . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                            <label for="khaata_date" class="input-group-text urdu">تاریخ</label>
                                            <input id="khaata_date" name="khaata_date"
                                                   value="<?php echo $record['khaata_date']; ?>"
                                                   type="text" class="form-control"
                                                   placeholder="Select date" data-input>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="khaata_name" class="input-group-text urdu">کھاتہ
                                                    نام</label>
                                                <input type="text" id="khaata_name" name="khaata_name"
                                                       class="form-control" required
                                                       value="<?php echo $record['khaata_name']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="comp_name" class="input-group-text urdu">کمپنی
                                                    نام</label>
                                                <input type="text" id="comp_name" name="comp_name"
                                                       class="form-control" required
                                                       value="<?php echo $record['comp_name']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="business_name" class="input-group-text urdu">کاروبار
                                                    نام</label>
                                                <input type="text" id="business_name"
                                                       value="<?php echo $record['business_name']; ?>"
                                                       name="business_name"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="city" class="input-group-text urdu">شہر کا
                                                    نام</label>
                                                <input type="text" id="city" name="city"
                                                       value="<?php echo $record['city']; ?>"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="address" class="input-group-text urdu">کاروبار
                                                    پتہ</label>
                                                <input type="text" id="address" name="address"
                                                       class="form-control urdu-2" required
                                                       value="<?php echo $record['address']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="mobile" class="input-group-text urdu">موبائل
                                                نمبر</label>
                                            <input type="text" id="mobile" name="mobile"
                                                   class="form-control ltr" required
                                                   placeholder="(+92) 3xx-xxxxxxx"
                                                   value="<?php echo $record['mobile']; ?>"
                                                   data-inputmask-alias="(+99) 999-9999999">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="whatsapp" class="input-group-text urdu">واٹس ایپ
                                                    نمبر</label>
                                                <input type="text" id="whatsapp" name="whatsapp"
                                                       class="form-control ltr" required
                                                       placeholder="(+92) 3xx-xxxxxxx"
                                                       value="<?php echo $record['whatsapp']; ?>"
                                                       data-inputmask-alias="(+99) 999-9999999">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="phone" class="input-group-text urdu">فون
                                                    نمبر</label>
                                                <input type="text" id="phone" name="phone"
                                                       class="form-control ltr"
                                                       value="<?php echo $record['phone']; ?>"
                                                       required placeholder="(+92) 3xx-xxxxxxx"
                                                       data-inputmask-alias="(+99) 999-9999999">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="email" class="input-group-text urdu">ای میل</label>
                                            <input id="email" name="email" class="form-control ltr" required
                                                   data-inputmask="'alias': 'email'"
                                                   value="<?php echo $record['email']; ?>"
                                                   placeholder="abc@example.com">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="cnic" class="input-group-text urdu">شناختی کارڈ
                                                    نمبر</label>
                                                <input type="text" id="cnic" name="cnic"
                                                       class="form-control ltr" required
                                                       placeholder="xxxxx-xxxxxxx-x"
                                                       value="<?php echo $record['cnic']; ?>"
                                                       data-inputmask-alias="99999-9999999-9">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="cnic_name" class="input-group-text urdu">شناختی کارڈ
                                                    نام</label>
                                                <input type="text" id="cnic_name"
                                                       value="<?php echo $record['cnic_name']; ?>"
                                                       name="cnic_name"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="father_name" class="input-group-text urdu">والد کا
                                                    نام</label>
                                                <input type="text" id="father_name" name="father_name"
                                                       class="form-control" required
                                                       value="<?php echo $record['father_name']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="home_address" class="input-group-text urdu">گھر کا
                                                    پتہ</label>
                                                <input type="text" id="home_address"
                                                       value="<?php echo $record['home_address']; ?>"
                                                       name="home_address"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="details" class="input-group-text urdu">مزید
                                                    تفصیل</label>
                                                <input type="text" id="details" name="details"
                                                       value="<?php echo $record['details']; ?>"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (Administrator()) {
                                    if ($record['is_active'] == 1) {
                                        $blockStr = "کھاتہ بلاک کریں";
                                        $active = 0;
                                        $returnMessage = "بلاک";
                                    } else {
                                        $blockStr = "کھاتہ اًن بلاک کریں";
                                        $active = 1;
                                        $returnMessage = "اًن بلاک";
                                    } ?>
                                    <!--<a onclick="blockRecord(this)" data-url="khaata-add?id=<?php /*echo $record['id']; */ ?>"
                                       data-tbl="khaata" data-pk="id" data-active="<?php /*echo $active; */ ?>"
                                       data-message="<?php /*echo $returnMessage; */ ?>"
                                       id="<?php /*echo $record['id']; */ ?>"
                                       class="btn btn-danger mt-4 btn-icon-text float-end">
                                        <i class="btn-icon-prepend" data-feather="delete"></i>
                                        <?php /*echo $blockStr; */ ?>
                                    </a>-->
                                <?php } ?>
                                <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">

                                <div class="d-flex mt-4 align-items-center justify-content-between">
                                    <button type="submit" name="recordUpdate"
                                            class="btn btn-dark btn-icon-text">
                                        <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                        درستگی
                                    </button>
                                    <a target="_blank"
                                       href="print/khaata?k_id=<?php echo base64_encode($id); ?>&print=<?php echo random_str('10'); ?>&secret=<?php echo base64_encode('powered-by-upsol'); ?>"
                                       class="btn btn-primary"> پرنٹ <i class="btn-icon-prepend"
                                                                                       data-feather="printer"></i></a>
                                    <div><?php echo addNew('khaata-add'); ?></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <div class="card">
                        <div class="card-body p-3">
                            <form action="ajax/uploadPicture.php" method="post" id="picUpload"
                                  enctype="multipart/form-data">
                                <label for="myDropify" class="">
                                    <?php if (!empty($record['image'])) {
                                        echo '<img class="img-fluid  rounded" src="' . $record['image'] . '" alt="">';
                                    } else {
                                        echo '<img class="img-fluid rounded" src="assets/images/others/logo-placeholder.png">';
                                    } ?>
                                    <input type="file" id="myDropify" name="fileUpload" class="sr-only"
                                           required>
                                </label>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="pk" value="id">
                                <input type="hidden" name="tbl" value="khaata">
                                <input type="hidden" name="url"
                                       value="khaata-add.php?id=<?php echo $id; ?>">
                            </form>
                            <script>
                                document.getElementById("myDropify").onchange = function () {
                                    document.getElementById("picUpload").submit();
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="card">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row gx-0 gy-5">
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="khaata_no" class="input-group-text urdu">کھاتہ
                                                    نمبر</label>
                                                <input type="text" id="khaata_no" name="khaata_no"
                                                       class="form-control" required
                                                       autofocus>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="cat_id" class="input-group-text urdu">کیٹیگری</label>
                                            <select class="form-select form-select-lg" name="cat_id" id="cat_id"
                                                    required>
                                                <option selected hidden disabled="disabled" value="">انتخاب
                                                </option>
                                                <?php $cats = fetch('cats');
                                                while ($cat = mysqli_fetch_assoc($cats)) {
                                                    echo '<option value="' . $cat['id'] . '">' . $cat['c_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group">
                                            <label for="branch_id" class="input-group-text urdu">برانچ</label>
                                            <select id="branch_id" name="branch_id" class="form-select form-select-lg"
                                                    required>
                                                <option hidden disabled selected value="">انتخاب</option>
                                                <?php $branches = fetch('branches');
                                                while ($branch = mysqli_fetch_assoc($branches)) {
                                                    echo '<option value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="input-group flatpickr" id="flatpickr-date">
                                            <label for="khaata_date" class="input-group-text urdu">تاریخ</label>
                                            <input id="khaata_date" name="khaata_date"
                                                   value="<?php echo date('Y-m-d'); ?>"
                                                   type="text" class="form-control"
                                                   placeholder="Select date" data-input>
                                            <span class="input-group-text input-group-addon" data-toggle><i
                                                    data-feather="calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="khaata_name" class="input-group-text urdu">کھاتہ
                                                    نام</label>
                                                <input type="text" id="khaata_name" name="khaata_name"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="comp_name" class="input-group-text urdu">کمپنی
                                                    نام</label>
                                                <input type="text" id="comp_name" name="comp_name"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="business_name" class="input-group-text urdu">کاروبار
                                                    نام</label>
                                                <input type="text" id="business_name" name="business_name"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="city" class="input-group-text urdu">شہر کا
                                                    نام</label>
                                                <input type="text" id="city" name="city" class="form-control input-urdu"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="address" class="input-group-text urdu">کاروبار
                                                    پتہ</label>
                                                <input type="text" id="address" name="address"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="mobile" class="input-group-text urdu">موبائل
                                                نمبر</label>
                                            <input type="text" id="mobile" name="mobile"
                                                   class="form-control ltr" required
                                                   placeholder="(+92) 3xx-xxxxxxx"
                                                   data-inputmask-alias="(+99) 999-9999999">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="whatsapp" class="input-group-text urdu">واٹس ایپ
                                                    نمبر</label>
                                                <input type="text" id="whatsapp" name="whatsapp"
                                                       class="form-control ltr" required
                                                       placeholder="(+92) 3xx-xxxxxxx"
                                                       data-inputmask-alias="(+99) 999-9999999">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="phone" class="input-group-text urdu">فون
                                                    نمبر</label>
                                                <input type="text" id="phone" name="phone"
                                                       class="form-control ltr"
                                                       required placeholder="(+92) 3xx-xxxxxxx"
                                                       data-inputmask-alias="(+99) 999-9999999">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="email" class="input-group-text urdu">ای میل</label>
                                            <input id="email" name="email" class="form-control ltr" required
                                                   data-inputmask="'alias': 'email'"
                                                   placeholder="abc@example.com">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="cnic" class="input-group-text urdu">شناختی کارڈ
                                                    نمبر</label>
                                                <input type="text" id="cnic" name="cnic"
                                                       class="form-control ltr" required
                                                       placeholder="xxxxx-xxxxxxx-x"
                                                       data-inputmask-alias="99999-9999999-9">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="cnic_name" class="input-group-text urdu">شناختی کارڈ
                                                    نام</label>
                                                <input type="text" id="cnic_name" name="cnic_name"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="father_name" class="input-group-text urdu">والد کا
                                                    نام</label>
                                                <input type="text" id="father_name" name="father_name"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="home_address" class="input-group-text urdu">گھر کا
                                                    پتہ</label>
                                                <input type="text" id="home_address" name="home_address"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="details" class="input-group-text urdu">مزید
                                                    تفصیل</label>
                                                <input type="text" id="details" name="details"
                                                       class="form-control input-urdu" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 text-center">
                                <input type="file" id="myDropify" name="user_image" required/>
                            </div>
                        </div>
                        <button name="recordSubmit" type="submit"
                                class="btn btn-primary btn-icon-text mt-4">
                            <i class="btn-icon-prepend" data-feather="check-square"></i>
                            محفوظ کریں
                        </button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php include("footer.php"); ?>
<?php if (isset($_POST['recordSubmit'])) {
    $url = "khaata-add";
    $image = $_FILES['user_image']['name'];
    $path = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $path);
    $data = array(
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'cat_id' => $_POST['cat_id'],
        'branch_id' => $_POST['branch_id'],
        'khaata_date' => $_POST['khaata_date'],
        'khaata_name' => mysqli_real_escape_string($connect, $_POST['khaata_name']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'business_name' => mysqli_real_escape_string($connect, $_POST['business_name']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'whatsapp' => mysqli_real_escape_string($connect, $_POST['whatsapp']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'cnic' => mysqli_real_escape_string($connect, $_POST['cnic']),
        'cnic_name' => mysqli_real_escape_string($connect, $_POST['cnic_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'home_address' => mysqli_real_escape_string($connect, $_POST['home_address']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'image' => $path,
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = insert('khaata', $data);
    if ($done) {
        $url .= '?id=' . $connect->insert_id;
        message('success', $url, 'کھاتہ محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['recordUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "khaata-add?id=" . $hidden_id;
    $data = array(
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'cat_id' => $_POST['cat_id'],
        'branch_id' => $_POST['branch_id'],
        'khaata_date' => $_POST['khaata_date'],
        'khaata_name' => mysqli_real_escape_string($connect, $_POST['khaata_name']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'business_name' => mysqli_real_escape_string($connect, $_POST['business_name']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'whatsapp' => mysqli_real_escape_string($connect, $_POST['whatsapp']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'cnic' => mysqli_real_escape_string($connect, $_POST['cnic']),
        'cnic_name' => mysqli_real_escape_string($connect, $_POST['cnic_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'home_address' => mysqli_real_escape_string($connect, $_POST['home_address']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = update('khaata', $data, array('id' => $hidden_id));
    if ($done) {
        message('info', $url, 'کھاتہ تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

