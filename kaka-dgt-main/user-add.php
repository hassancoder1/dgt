<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نیا یوزر کا اندراج </h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="users"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
            یوزرز تفصیل
        </a>
    </div>
</div>
<?php if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('users', array('id' => $id));
    $record = mysqli_fetch_assoc($records); ?>
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form action="" method="post"
                          onsubmit="return confirm('محفوظ کرنے سے پہلے اچھی طرح چیک کے لیں کہ یوزر کو کون کونی سا فارم دینا ہے۔ \n محفوظ کرنے کے لیے OK کا بٹن دبائیں۔\n ');">
                        <div class="row gx-0 gy-4">
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="name" class="input-group-text urdu"> نام</label>
                                    <input type="text" id="name" name="full_name"
                                           class="form-control input-urdu"
                                           required autofocus
                                           value="<?php echo $record['full_name']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="father_name" class="input-group-text urdu">والد
                                        کا
                                        نام</label>
                                    <input type="text" id="father_name" name="father_name"
                                           class="form-control input-urdu"
                                           required
                                           value="<?php echo $record['father_name']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <label class="input-group-text urdu">تاریخ</label>
                                    <input name="user_date" type="text" class="form-control"
                                           value="<?php echo $record['user_date']; ?>"
                                           placeholder="Select date" data-input>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="row gx-0">
                                    <div class="col-lg-1">
                                        <label for="permission" class="col-form-label urdu">فارم</label>
                                    </div>
                                    <div class="col-lg-11">
                                        <select multiple name="permission[]" id="permission" class="virtual-select"
                                                data-silent-initial-value-set="true">
                                            <?php $selected = '';
                                            $pData = fetch('user_permissions', array('user_id' => $id));
                                            $pDatum = mysqli_fetch_assoc($pData);
                                            $forms = fetch('forms');
                                            while ($form = mysqli_fetch_assoc($forms)) {
                                                if (!empty($pDatum['permission'])) {
                                                    $perms = json_decode($pDatum['permission']);
                                                    $selected = in_array($form['form_name'], $perms) ? 'selected' : '';
                                                }
                                                echo '<option ' . $selected . ' value="' . $form['form_name'] . '">' . ucfirst(str_replace('-', ' ', $form['form_name'])) . ' - ' . $form['form_details'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="branch_id" class="input-group-text urdu">برانچ
                                        کانام</label>
                                    <select id="branch_id" name="branch_id" class="form-select">
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
                                    <label for="role" class="input-group-text urdu">پوسٹ کا
                                        نام</label>
                                    <select class="form-select" name="role" id="role">
                                        <option hidden disabled selected value="">پوسٹ کانام</option>
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
                                <div class="input-group">
                                    <label for="email" class="input-group-text urdu">ای
                                        میل</label>
                                    <input id="email" name="email" class="form-control ltr"
                                           required data-inputmask="'alias': 'email'"
                                           value="<?php echo $record['email']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="mobile" class="input-group-text urdu">موبائل
                                        نمبر</label>
                                    <input type="text" id="mobile" name="mobile" class="form-control ltr" required
                                           placeholder="(+92) 3xx-xxxxxxx" value="<?php echo $record['mobile']; ?>"
                                           data-inputmask-alias="(+99) 999-9999999">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="phone" class="input-group-text urdu">فون
                                        نمبر</label>
                                    <input type="text" id="phone" name="phone" required
                                           class="form-control ltr"
                                           placeholder="(+92) 3xx-xxxxxxx"
                                           data-inputmask-alias="(+99) 999-9999999"
                                           value="<?php echo $record['phone']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="username" class="input-group-text urdu">یوزر آئ
                                        ڈی</label>
                                    <input type="text" id="username" name="username"
                                           value="<?php echo $record['username']; ?>"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="password"
                                           class="input-group-text urdu">پاسورڈ</label>
                                    <input id="password" class="form-control" name="password"
                                           value="<?php echo $record['pass']; ?>"
                                           type="text" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="confirm_password" class="input-group-text urdu">کنفرم
                                        پاسورڈ</label>
                                    <input id="confirm_password" class="form-control"
                                           name="confirm_password" type="text"
                                           value="<?php echo $record['pass']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row gx-0">
                                    <div class="col-lg-3">
                                        <label for="ut_permissions" class="col-form-label urdu">اپ ٹرانزٹ فارم</label>
                                    </div>
                                    <div class="col-lg-9">
                                        <select multiple name="ut_permissions[]" id="ut_permissions"
                                                class="virtual-select" data-silent-initial-value-set="true">
                                            <?php $selected = '';
                                            $ut_permissions = $record['ut_permissions'];
                                            $forms = fetch('ut_expense_names');
                                            while ($form = mysqli_fetch_assoc($forms)) {
                                                if (!empty($ut_permissions)) {
                                                    $perms = json_decode($ut_permissions);
                                                    if (in_array($form['t_value'], $perms)) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                }
                                                echo '<option ' . $selected . ' value="' . $form['t_value'] . '">' . $form['t_value_urdu'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="text-primary urdu">یہ صارف اپ ٹرانزٹ کلئیرنس انٹری میں کون سے فارم دیکھ
                                    سکتا ہے۔
                                </small>
                            </div>
                            <div class="col-lg-4">
                                <div class="row gx-0">
                                    <div class="col-lg-4">
                                        <label for="ut_roznamcha" class="col-form-label urdu">اپ ٹرانزٹ روزنامچہ</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select multiple name="ut_roznamcha[]" id="ut_roznamcha"
                                                class="virtual-select" data-silent-initial-value-set="true">
                                            <?php $ut_roznamcha = $record['ut_roznamcha'];
                                            $forms = fetch('ut_expense_names');
                                            while ($form = mysqli_fetch_assoc($forms)) {
                                                if (!empty($ut_roznamcha)) {
                                                    $perms = json_decode($ut_roznamcha);
                                                    $rselected = in_array($form['t_value'], $perms) ? 'selected' : '';
                                                }
                                                echo '<option ' . $rselected . ' value="' . $form['t_value'] . '">' . $form['t_value_urdu'] . ' روزنامچہ </option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="text-danger urdu">یہ صارف اپ ٹرانزٹ کے کون سے روزنامچے دیکھ سکتا ہے؟
                                </small>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="user_details" class="input-group-text urdu">تفصیل</label>
                                        <input type="text" id="user_details" name="user_details"
                                               class="form-control input-urdu" required
                                               value="<?php echo $record['user_details']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="<?php echo $record["id"]; ?>"
                               name="hidden_id">
                        <div class="row mt-5">
                            <div class="col-5">
                                <button type="submit" name="userUpdate"
                                        class="btn btn-dark btn-icon-text">
                                    <i class="btn-icon-prepend" data-feather="edit-3"></i>درستگی
                                </button>
                                <?php if (Administrator()) {
                                    if ($id != $userId && $record['id'] != 1) { ?>
                                        <a class="btn btn-danger btn-icon-text float-end"
                                           onclick="deleteUser(this)" data-url="users" data-tbl="users"
                                           id="<?php echo $record['id']; ?>">
                                            <i class="btn-icon-prepend" data-feather="delete"></i>بلاک کریں </a>
                                    <?php }
                                } ?>
                            </div>
                            <div class="col-7">
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
        <div class="col-md-2 text-center">
            <div class="card">
                <div class="card-body p-3">
                    <form action="ajax/uploadUserPicture.php" method="post" id="userPicUpload"
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
                        <input type="hidden" name="user_id" value="<?php echo $id; ?>">
                        <input type="hidden" name="url" value="user-add?id=<?php echo $id; ?>">
                    </form>
                    <script>
                        document.getElementById("myDropify").onchange = function () {
                            document.getElementById("userPicUpload").submit();
                        }

                    </script>
                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="card">
        <div class="card-body">
            <form action="" method="post" id="addUserForm" enctype="multipart/form-data"
                  onsubmit="return confirm('محفوظ کرنے سے پہلے اچھی طرح چیک کے لیں کہ یوزر کو کون کونی سا فارم دینا ہے۔ \n محفوظ کرنے کے لیے OK کا بٹن دبائیں۔\n ');">
                <div class="row g-0">
                    <div class="col-md-10">
                        <div class="row gx-0 gy-4">
                            <div class="col-lg-2">
                                <div class="input-group">
                                    <label for="name" class="input-group-text urdu"> نام</label>
                                    <input type="text" id="name" name="full_name"
                                           class="form-control input-urdu"
                                           required autofocus>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="father_name" class="input-group-text urdu">والد کا
                                        نام</label>
                                    <input type="text" id="father_name" name="father_name"
                                           class="form-control input-urdu"
                                           required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <label class="input-group-text urdu">تاریخ</label>
                                    <input name="user_date" value="<?php echo date('Y-m-d'); ?>"
                                           type="text" class="form-control"
                                           placeholder="Select date" data-input>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="row gx-0">
                                    <div class="col-lg-1">
                                        <label for="permission" class="col-form-label urdu">فارم</label>
                                    </div>
                                    <div class="col-lg-11">
                                        <select multiple name="permission[]" id="permission"
                                                class="virtual-select" data-silent-initial-value-set="true">
                                            <?php $forms = fetch('forms');
                                            while ($form = mysqli_fetch_assoc($forms)) {
                                                echo '<option value="' . $form['form_name'] . '">' . $form['form_details'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="branch_id" class="input-group-text urdu">برانچ
                                        کانام</label>
                                    <select id="branch_id" name="branch_id" data-width="100%"
                                            class="form-select">
                                        <!--data-width="100%"-->
                                        <option hidden class="">برانچ</option>
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
                                    <select class="form-select" name="role" id="role">
                                        <option selected hidden>پوسٹ کانام</option>
                                        <?php $roles = fetch('roles');
                                        while ($role = mysqli_fetch_assoc($roles)) {
                                            echo '<option value="' . $role['role_name'] . '">' . $role['urdu_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
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
                                    <label for="phone" class="input-group-text urdu">فون نمبر</label>
                                    <input type="text" id="phone" name="phone" required
                                           class="form-control ltr" placeholder="(+92) 3xx-xxxxxxx"
                                           data-inputmask-alias="(+99) 999-9999999">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label class="input-group-text urdu">یوزر آئ ڈی</label>
                                    <input type="text" id="username" name="username"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="password" class="input-group-text urdu">پاسورڈ</label>
                                    <input id="password" class="form-control" name="password"
                                           type="password" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="input-group">
                                    <label for="confirm_password" class="input-group-text urdu">کنفرم
                                        پاسورڈ</label>
                                    <input id="confirm_password" class="form-control"
                                           name="confirm_password" type="password">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row gx-0">
                                    <div class="col-lg-3">
                                        <label for="ut_permissions" class="col-form-label urdu">اپ ٹرانزٹ فارم</label>
                                    </div>
                                    <div class="col-lg-9">
                                        <select multiple name="ut_permissions[]" id="ut_permissions"
                                                class="virtual-select"
                                                data-silent-initial-value-set="true">
                                            <?php $forms = fetch('ut_expense_names');
                                            while ($form = mysqli_fetch_assoc($forms)) {
                                                echo '<option value="' . $form['t_value'] . '">' . $form['t_value_urdu'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="text-primary urdu">یہ صارف اپ ٹرانزٹ کلئیرنس انٹری میں کون سے فارم دیکھ
                                    سکتا ہے۔
                                </small>
                            </div>
                            <div class="col-lg-4">
                                <div class="row gx-0">
                                    <div class="col-lg-4">
                                        <label for="ut_roznamcha" class="col-form-label urdu">اپ ٹرانزٹ روزنامچہ</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select multiple name="ut_roznamcha[]" id="ut_roznamcha"
                                                class="virtual-select" data-silent-initial-value-set="true">
                                            <?php $forms = fetch('ut_expense_names');
                                            while ($form = mysqli_fetch_assoc($forms)) {
                                                echo '<option value="' . $form['t_value'] . '">' . $form['t_value_urdu'] . ' روزنامچہ</option>';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="text-danger urdu">یہ صارف اپ ٹرانزٹ کے کون سے روزنامچے دیکھ سکتا ہے؟
                                </small>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <div class="input-group">
                                        <label for="user_details" class="input-group-text urdu">مزید
                                            تفصیل</label>
                                        <input type="text" id="user_details" name="user_details"
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
                <div class="row mt-3">
                    <div class="col-5">
                        <button name="userSubmit" type="submit"
                                class="btn btn-primary btn-primary btn-icon-text mt-4">
                            <i class="btn-icon-prepend" data-feather="check-square"></i>
                            محفوظ کریں
                        </button>
                    </div>
                    <div class="col-7">
                        <?php if (isset($_SESSION['response'])) {
                            echo $_SESSION['response'];
                            unset($_SESSION['response']);
                        } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php } ?>
<?php include("footer.php"); ?>
<script>
    $(function () {
        'use strict';
        /*$.validator.setDefaults({
         submitHandler: function() {
         alert("submitted!");
         }
         });*/
        $(function () {
            $("#addUserForm").validate({
                rules: {
                    password: {
                        required: true,
                        minlength: 4
                    },
                    confirm_password: {
                        required: true,
                        minlength: 4,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password: {
                        required: "پاسورڈ ضروری ہے",
                        minlength: "پاسورڈ کم از کم چار الفاظ"
                    },
                    confirm_password: {
                        required: "کنفرم پاسورڈ ضروری ہے",
                        minlength: "پاسورڈ کم از کم چار الفاظ",
                        equalTo: "کنفرم پاسورڈ ایک جیسا ہونا چاہیئے"
                    }
                },
                errorPlacement: function (error, element) {
                    error.addClass("invalid-feedback");

                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if (element.prop('type') === 'radio' && element.parent('.radio-inline').length) {
                        error.insertAfter(element.parent().parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass) {
                    if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
                        $(element).addClass("is-invalid").removeClass("is-valid");
                    }
                },
                unhighlight: function (element, errorClass) {
                    if ($(element).prop('type') != 'checkbox' && $(element).prop('type') != 'radio') {
                        $(element).addClass("is-valid").removeClass("is-invalid");
                    }
                }
            });
        });
    });
</script>
<?php if (isset($_POST['userSubmit'])) {
    $url = "user-add";
    $image = $_FILES['user_image']['name'];
    $path = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $path);
    $ut_permission_string = implode(',', $_POST['ut_permissions']);
    $ut_permissions = explode(',', $ut_permission_string);
    $ut_roznamcha_string = implode(',', $_POST['ut_roznamcha']);
    $ut_roznamcha = explode(',', $ut_roznamcha_string);
    $data = array(
        'full_name' => mysqli_real_escape_string($connect, $_POST['full_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'user_date' => $_POST['user_date'],
        'branch_id' => $_POST['branch_id'],
        'role' => $_POST['role'],
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'username' => mysqli_real_escape_string($connect, $_POST['username']),
        'pass' => mysqli_real_escape_string($connect, $_POST['password']),
        'user_details' => mysqli_real_escape_string($connect, $_POST['user_details']),
        'ut_permissions' => json_encode($ut_permissions),
        'ut_roznamcha' => json_encode($ut_roznamcha),
        'image' => $path,
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = insert('users', $data);
    $insertId = $connect->insert_id;
    $permission_string = implode(',', $_POST['permission']);
    $permissions = explode(',', $permission_string);
    $dataPermission = array(
        'user_id' => $insertId,
        'permission' => json_encode($permissions)
    );
    $doneP = insert('user_permissions', $dataPermission);
    if ($done && $doneP) {
        message('success', $url, 'یوزر محفوظ ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['userUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "user-add?id=" . $hidden_id;
    $ut_permission_string = implode(',', $_POST['ut_permissions']);
    $ut_permissions = explode(',', $ut_permission_string);
    $ut_roznamcha_string = implode(',', $_POST['ut_roznamcha']);
    $ut_roznamcha = explode(',', $ut_roznamcha_string);
    $data = array(
        'full_name' => mysqli_real_escape_string($connect, $_POST['full_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'user_date' => $_POST['user_date'],
        'branch_id' => $_POST['branch_id'],
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'username' => mysqli_real_escape_string($connect, $_POST['username']),
        'pass' => mysqli_real_escape_string($connect, $_POST['password']),
        'user_details' => mysqli_real_escape_string($connect, $_POST['user_details']),
        'ut_permissions' => json_encode($ut_permissions),
        'ut_roznamcha' => json_encode($ut_roznamcha)
    );

    if (isset($_POST['role'])) {
        $data['role'] = $_POST['role'];
        //echo 'POST SELECT KI H';
    } else {
        //echo 'post khaali h.';
    }
    $done = update('users', $data, array('id' => $hidden_id));

    $permission_string = implode(',', $_POST['permission']);
    $permissions = explode(',', $permission_string);
    $dataPermission = array(
        'permission' => json_encode($permissions)
    );
    $doneP = update('user_permissions', $dataPermission, array('user_id' => $hidden_id));
    if ($done && $doneP) {
        message('success', $url, 'یوزر تبدیل ہوگیا');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>
