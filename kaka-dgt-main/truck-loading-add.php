<?php include("header.php"); ?>
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">نیا ٹرک لوڈنگ</h4>
    </div>
    <div class="d-flex align-items-center flex-wrap text-nowrap">
        <a href="truck-loadings"
           class="btn btn-dark btn-icon-text mb-2 mb-md-0">
            <i class="btn-icon-prepend" data-feather="arrow-left-circle"></i>
            ٹرک لوڈنگز
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
            $records = fetch('truck_loadings', array('id' => $id));
            $record = mysqli_fetch_assoc($records); ?>
            <div class="row">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="row gx-0 gy-2">
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="truck_no" class="input-group-text urdu"> ٹرک
                                                    نمبر</label>
                                                <input type="text" id="truck_no" name="truck_no"
                                                       class="form-control"
                                                       required=""
                                                       autofocus value="<?php echo $record["truck_no"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">چیسز نمبر</label>
                                                <input type="text" name="chassis_no" class="form-control"
                                                       required=""
                                                       value="<?php echo $record["chassis_no"]; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">ٹرک نام</label>
                                                <input type="text" name="truck_name" class="form-control"
                                                       required=""
                                                       value="<?php echo $record["truck_name"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">ماڈل</label>
                                                <input type="text" name="truck_modal" class="form-control"
                                                       required=""
                                                       value="<?php echo $record["truck_modal"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">ڈرائیور نام</label>
                                                <input type="text" id="" name="driver_name"
                                                       class="form-control input-urdu"
                                                       required=""
                                                       value="<?php echo $record["driver_name"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">والد کا نام</label>
                                                <input type="text" id="" name="d_father"
                                                       class="form-control input-urdu"
                                                       required="" value="<?php echo $record["d_father"]; ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">شناختی کارڈ
                                                    نمبر</label>
                                                <input type="text" id="" name="d_cnic" class="form-control"
                                                       required=""
                                                       value="<?php echo $record["d_cnic"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">قوم کا نام</label>
                                                <input type="text" id="" name="d_caste"
                                                       class="form-control input-urdu"
                                                       required="" value="<?php echo $record["d_caste"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">موبائل نمبر
                                                    1</label>
                                                <input type="text" id="" name="d_mobile1" class="form-control"
                                                       required="" value="<?php echo $record["d_mobile1"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">موبائل نمبر
                                                    2</label>
                                                <input type="text" id="" name="d_mobile2" class="form-control"
                                                       required="" value="<?php echo $record["d_mobile2"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">ڈرائیور گھر کا
                                                    پتہ</label>
                                                <input type="text" id="" name="d_address"
                                                       class="form-control input-urdu"
                                                       required="" value="<?php echo $record["d_address"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">شہر کا نام</label>
                                                <input type="text" id="" name="d_city"
                                                       class="form-control input-urdu"
                                                       required=""
                                                       value="<?php echo $record["d_city"]; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="" class="input-group-text urdu">مزید رپورٹ</label>
                                                <textarea id="" name="d_details" class="form-control input-urdu"
                                                          required><?php echo $record["d_details"]; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" value="<?php echo $record["id"]; ?>" name="tl_id">

                                <div class="d-flex mt-4 align-items-center justify-content-between">
                                    <button type="submit" name="recordUpdate"
                                            class="btn btn-dark btn-icon-text">
                                        <i class="btn-icon-prepend" data-feather="edit-3"></i>
                                        درستگی
                                    </button>
                                    <div><?php echo addNew('truck-loading-add'); ?></div>
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
                                        echo '<img class="img-fluid w-50 rounded" src="' . $record['image'] . '" alt="">';
                                    } else {
                                        echo '<img class="img-fluid w-50 rounded" src="assets/images/others/logo-placeholder.png">';
                                    } ?>
                                    <input type="file" id="myDropify" name="fileUpload" class="sr-only"
                                           required>
                                </label>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="pk" value="id">
                                <input type="hidden" name="tbl" value="truck_loadings">
                                <input type="hidden" name="url"
                                       value="truck-loading-add?id=<?php echo $id; ?>">
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
                                        <div class="input-group">
                                            <label for="truck_no" class="input-group-text urdu"> ٹرک
                                                نمبر</label>
                                            <input type="text" id="truck_no" name="truck_no"
                                                   class="form-control"
                                                   required=""
                                                   autofocus>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="chassis_no" class="input-group-text urdu">چیسز
                                                نمبر</label>
                                            <input type="text" id="chassis_no" name="chassis_no"
                                                   class="form-control"
                                                   required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="truck_name" class="input-group-text urdu">ٹرک
                                                نام</label>
                                            <input type="text" name="truck_name" id="truck_name"
                                                   class="form-control"
                                                   required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <label for="truck_modal" class="input-group-text urdu">ماڈل</label>
                                            <input type="text" id="truck_modal" name="truck_modal"
                                                   class="form-control"
                                                   required="">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="driver_name" class="input-group-text urdu">ڈرائیور
                                                    نام</label>
                                                <input type="text" id="driver_name" name="driver_name"
                                                       class="form-control input-urdu"
                                                       required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_father" class="input-group-text urdu">والد کا
                                                    نام</label>
                                                <input type="text" id="d_father" name="d_father"
                                                       class="form-control input-urdu"
                                                       required="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_cnic" class="input-group-text urdu">شناختی کارڈ
                                                    نمبر</label>
                                                <input type="text" id="d_cnic" name="d_cnic"
                                                       class="form-control"
                                                       required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_caste" class="input-group-text urdu">قوم کا
                                                    نام</label>
                                                <input type="text" id="d_caste" name="d_caste"
                                                       class="form-control input-urdu"
                                                       required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_mobile1" class="input-group-text urdu">موبائل نمبر
                                                    1</label>
                                                <input type="text" id="d_mobile1" name="d_mobile1"
                                                       class="form-control"
                                                       required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_mobile2" class="input-group-text urdu">موبائل نمبر
                                                    2</label>
                                                <input type="text" id="d_mobile2" name="d_mobile2"
                                                       class="form-control"
                                                       required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_address" class="input-group-text urdu">ڈرائیور گھر
                                                    کا
                                                    پتہ</label>
                                                <input type="text" id="d_address" name="d_address"
                                                       class="form-control input-urdu"
                                                       required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_city" class="input-group-text urdu">شہر کا
                                                    نام</label>
                                                <input type="text" id="d_city" name="d_city"
                                                       class="form-control input-urdu" required="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <label for="d_details" class="input-group-text urdu">مزید
                                                    رپورٹ</label>
                                                <textarea id="d_details" name="d_details"
                                                          class="form-control input-urdu"
                                                          required></textarea>
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
    $url = "truck-loading-add";
    $image = $_FILES['user_image']['name'];
    $path = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $path);
    $data = array(
        'truck_no' => mysqli_real_escape_string($connect, $_POST['truck_no']),
        'chassis_no' => mysqli_real_escape_string($connect, $_POST['chassis_no']),
        'truck_name' => mysqli_real_escape_string($connect, $_POST['truck_name']),
        'truck_modal' => mysqli_real_escape_string($connect, $_POST['truck_modal']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'd_father' => mysqli_real_escape_string($connect, $_POST['d_father']),
        'd_cnic' => mysqli_real_escape_string($connect, $_POST['d_cnic']),
        'd_caste' => mysqli_real_escape_string($connect, $_POST['d_caste']),
        'd_mobile1' => mysqli_real_escape_string($connect, $_POST['d_mobile1']),
        'd_mobile2' => mysqli_real_escape_string($connect, $_POST['d_mobile2']),
        'd_address' => mysqli_real_escape_string($connect, $_POST['d_address']),
        'd_city' => mysqli_real_escape_string($connect, $_POST['d_city']),
        'd_details' => mysqli_real_escape_string($connect, $_POST['d_details']),
        'image' => $path,
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = insert('truck_loadings', $data);
    if ($done) {
        $url .= '?id=' . $connect->insert_id;
        message('success', $url, 'ٹرک لوڈنگ محفوظ ہوگئی');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

}
if (isset($_POST['recordUpdate'])) {
    $tl_id = $_POST['tl_id'];
    $url = "truck-loading-add?id=".$tl_id;
    $data = array(
        'truck_no' => mysqli_real_escape_string($connect, $_POST['truck_no']),
        'chassis_no' => mysqli_real_escape_string($connect, $_POST['chassis_no']),
        'truck_name' => mysqli_real_escape_string($connect, $_POST['truck_name']),
        'truck_modal' => mysqli_real_escape_string($connect, $_POST['truck_modal']),
        'driver_name' => mysqli_real_escape_string($connect, $_POST['driver_name']),
        'd_father' => mysqli_real_escape_string($connect, $_POST['d_father']),
        'd_cnic' => mysqli_real_escape_string($connect, $_POST['d_cnic']),
        'd_caste' => mysqli_real_escape_string($connect, $_POST['d_caste']),
        'd_mobile1' => mysqli_real_escape_string($connect, $_POST['d_mobile1']),
        'd_mobile2' => mysqli_real_escape_string($connect, $_POST['d_mobile2']),
        'd_address' => mysqli_real_escape_string($connect, $_POST['d_address']),
        'd_city' => mysqli_real_escape_string($connect, $_POST['d_city']),
        'd_details' => mysqli_real_escape_string($connect, $_POST['d_details']),
        'created_at' => date('Y-m-d H:i:s')
    );
    $done = update('truck_loadings', $data, array('id' => $tl_id));
    if ($done) {
        message('info', $url, 'ٹرک لوڈنگ  تبدیل ہوگئی');
    } else {
        message('danger', $url, 'ڈیٹابیس پرابلم');
    }

} ?>

