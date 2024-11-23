<?php include("header.php"); ?>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div>
                <h4 class="mb-3 mb-md-0">نیا ملازم</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <a href="staffs"
                   class="btn btn-dark btn-icon-text mb-2 mb-md-0">
                    <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
                    ملازم تفصیل
                </a>
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
                    $records = fetch('staffs', array('id' => $id));
                    $record = mysqli_fetch_assoc($records); ?>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="card">
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="row gx-0 gy-2">
                                            <div class="col-lg-3">
                                                <div class="input-group flatpickr" id="flatpickr-date">
                                                    <label for="staff_date" class="input-group-text urdu">تاریخ</label>
                                                    <input id="staff_date" name="staff_date"
                                                           value="<?php echo $record['staff_date']; ?>"
                                                           type="text" class="form-control" autofocus
                                                           placeholder="Select date" data-input>
                                                    <span class="input-group-text input-group-addon" data-toggle><i
                                                                data-feather="calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <label for="branch_id" class="input-group-text urdu">برانچ
                                                        کانام</label>
                                                    <select id="branch_id" name="branch_id" class="form-select"
                                                            required>
                                                        <option hidden disabled selected value="">برانچ</option>
                                                        <?php $branches = fetch('branches');
                                                        $brancSelected = '';
                                                        while ($branch = mysqli_fetch_assoc($branches)) {
                                                            if ($branch['id'] == $record['branch_id']) {
                                                                $brancSelected = 'selected';
                                                            } else {
                                                                $brancSelected = '';
                                                            }
                                                            echo '<option ' . $brancSelected . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <label for="role" class="input-group-text urdu">پوسٹ کا نام</label>
                                                    <select class="form-select" name="role" id="role" required>
                                                        <option selected hidden disabled="disabled" value="">پوسٹ
                                                            کانام
                                                        </option>
                                                        <?php $roles = fetch('roles');
                                                        $roleSelected = '';
                                                        while ($role = mysqli_fetch_assoc($roles)) {
                                                            if ($role['role_name'] == $record['role']) {
                                                                $roleSelected = 'selected';
                                                            } else {
                                                                $roleSelected = '';
                                                            }
                                                            echo '<option ' . $roleSelected . ' value="' . $role['role_name'] . '">' . $role['urdu_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="city" class="input-group-text urdu">شہر کا
                                                            نام</label>
                                                        <input type="text" id="city" name="city"
                                                               value="<?php echo $record['city']; ?>"
                                                               class="form-control input-urdu" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="staff_name" class="input-group-text urdu">
                                                            نام</label>
                                                        <input type="text" id="staff_name" name="staff_name"
                                                               value="<?php echo $record['staff_name']; ?>"
                                                               class="form-control input-urdu" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="license_name" class="input-group-text urdu">لائسینس
                                                            کانام</label>
                                                        <input type="text" id="license_name" name="license_name"
                                                               value="<?php echo $record['license_name']; ?>"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="father_name" class="input-group-text urdu">والدکا
                                                            نام</label>
                                                        <input type="text" id="father_name" name="father_name"
                                                               value="<?php echo $record['father_name']; ?>"
                                                               class="form-control input-urdu" required>
                                                    </div>
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
                                                        <label for="caste" class="input-group-text urdu">قوم</label>
                                                        <input type="text" id="caste" name="caste"
                                                               value="<?php echo $record['caste']; ?>"
                                                               class="form-control input-urdu"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="address" class="input-group-text urdu">گھر کا
                                                            پتہ</label>
                                                        <input type="text" id="address" name="address"
                                                               value="<?php echo $record['address']; ?>"
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
                                                           value="<?php echo $record['mobile']; ?>"
                                                           data-inputmask-alias="(+99) 999-9999999">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <label for="email" class="input-group-text urdu">ای میل</label>
                                                    <input id="email" name="email" class="form-control ltr" required
                                                           data-inputmask="'alias': 'email'"
                                                           value="<?php echo $record['email']; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="salary" class="input-group-text urdu">تنخواہ کی
                                                            رقم</label>
                                                        <input type="number" id="salary" name="salary"
                                                               value="<?php echo $record['salary']; ?>"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="details" class="input-group-text urdu">مزید
                                                            رپورٹ</label>
                                                        <input type="text" id="details" name="details"
                                                               value="<?php echo $record['details']; ?>"
                                                               class="form-control input-urdu" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                                        <button type="submit" name="recordUpdate"
                                                class="btn btn-dark mt-4 btn-icon-text">
                                            <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                            درستگی
                                        </button>
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
                                                echo '<img class="img-fluid w-50 rounded" src="' . $record['image'] . '" alt="">';
                                            } else {
                                                echo '<img class="img-fluid w-50 rounded" src="assets/images/others/logo-placeholder.png">';
                                            } ?>
                                            <input type="file" id="myDropify" name="fileUpload" class="sr-only"
                                                   required>
                                        </label>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="pk" value="id">
                                        <input type="hidden" name="tbl" value="staffs">
                                        <input type="hidden" name="url"
                                               value="staff-add.php?id=<?php echo $id; ?>">
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
                                <div class="row g-0">
                                    <div class="col-md-10">
                                        <div class="row gx-0 gy-2">
                                            <div class="col-lg-3">
                                                <div class="input-group flatpickr" id="flatpickr-date">
                                                    <label for="staff_date" class="input-group-text urdu">تاریخ</label>
                                                    <input id="staff_date" name="staff_date"
                                                           value="<?php echo date('Y-m-d'); ?>"
                                                           type="text" class="form-control" autofocus
                                                           placeholder="Select date" data-input>
                                                    <span class="input-group-text input-group-addon" data-toggle><i
                                                                data-feather="calendar"></i></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <label for="branch_id" class="input-group-text urdu">برانچ
                                                        کانام</label>
                                                    <select id="branch_id" name="branch_id" class="form-select"
                                                            required>
                                                        <option hidden disabled selected value="">برانچ</option>
                                                        <?php $branches = fetch('branches');
                                                        while ($branch = mysqli_fetch_assoc($branches)) {
                                                            echo '<option value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="input-group">
                                                    <label for="role" class="input-group-text urdu">پوسٹ کا نام</label>
                                                    <select class="form-select" name="role" id="role" required>
                                                        <option selected hidden disabled="disabled" value="">پوسٹ
                                                            کانام
                                                        </option>
                                                        <?php $roles = fetch('roles');
                                                        while ($role = mysqli_fetch_assoc($roles)) {
                                                            echo '<option value="' . $role['role_name'] . '">' . $role['urdu_name'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="city" class="input-group-text urdu">شہر کا
                                                            نام</label>
                                                        <input type="text" id="city" name="city"
                                                               class="form-control input-urdu" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="staff_name" class="input-group-text urdu">
                                                            نام</label>
                                                        <input type="text" id="staff_name" name="staff_name"
                                                               class="form-control input-urdu" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="license_name" class="input-group-text urdu">لائسینس
                                                            کانام</label>
                                                        <input type="text" id="license_name" name="license_name"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="father_name" class="input-group-text urdu">والدکا
                                                            نام</label>
                                                        <input type="text" id="father_name" name="father_name"
                                                               class="form-control input-urdu" required>
                                                    </div>
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
                                                        <label for="caste" class="input-group-text urdu">قوم</label>
                                                        <input type="text" id="caste" name="caste"
                                                               class="form-control input-urdu"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="address" class="input-group-text urdu">گھر کا
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
                                                <div class="input-group">
                                                    <label for="email" class="input-group-text urdu">ای میل</label>
                                                    <input id="email" name="email" class="form-control ltr" required
                                                           data-inputmask="'alias': 'email'">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="salary" class="input-group-text urdu">تنخواہ کی
                                                            رقم</label>
                                                        <input type="number" id="salary" name="salary"
                                                               class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <label for="details" class="input-group-text urdu">مزید
                                                            رپورٹ</label>
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
    $url = "staff-add";
    $image = $_FILES['user_image']['name'];
    $path = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $path);
    $data = array(
        'branch_id' => $_POST['branch_id'],
        'role' => $_POST['role'],
        'staff_date' => $_POST['staff_date'],
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'staff_name' => mysqli_real_escape_string($connect, $_POST['staff_name']),
        'license_name' => mysqli_real_escape_string($connect, $_POST['license_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'cnic' => mysqli_real_escape_string($connect, $_POST['cnic']),
        'caste' => mysqli_real_escape_string($connect, $_POST['caste']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'salary' => mysqli_real_escape_string($connect, $_POST['salary']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'image' => $path,
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = insert('staffs', $data);
    if ($done) {
        message('success', $url, 'ملازم محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['recordUpdate'])) {
    $url = "staff-add";
    $hidden_id = $_POST['hidden_id'];
    $data = array(
        'branch_id' => $_POST['branch_id'],
        'role' => $_POST['role'],
        'staff_date' => $_POST['staff_date'],
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'staff_name' => mysqli_real_escape_string($connect, $_POST['staff_name']),
        'license_name' => mysqli_real_escape_string($connect, $_POST['license_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'cnic' => mysqli_real_escape_string($connect, $_POST['cnic']),
        'caste' => mysqli_real_escape_string($connect, $_POST['caste']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'salary' => mysqli_real_escape_string($connect, $_POST['salary']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = update('staffs', $data, array('id' => $hidden_id));
    if ($done) {
        message('info', $url, 'ملازم تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

