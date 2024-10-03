<?php
$page_title = 'Branch Entry';
$pageURL = 'branch-add';
$back_page_url = 'branches';
include("header.php");
$id = $country_id = $branch_user_id = 0;
$b_code = $b_name = $name = $father_name = $city = $address = $zip_code = $mobile = $phone = $whatsapp = $email = '';
$username = $pass = '';
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('branches', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $b_code = $record['b_code'];
    $b_name = $record['b_name'];
    $name = $record['name'];
    $father_name = $record['father_name'];
    $country_id = $record['country_id'];
    $city = $record['city'];
    $address = $record['address'];
    $zip_code = $record['zip_code'];
    $mobile = $record['mobile'];
    $phone = $record['phone'];
    $whatsapp = $record['whatsapp'];
    $email = $record['email'];

    $branch_user_id = $record['user_id'];
    $users = fetch('users', array('id' => $branch_user_id));
    $record_user = mysqli_fetch_assoc($users);
    $username = $record_user['username'];
    $pass = $record_user['pass'];
} ?>
<div class="row">
    <div class="col-xl-12">
        <div class="d-flex align-items-center justify-content-between mb-md-2">
            <div class="fs-5 text-uppercase"><?php echo $page_title; ?></div>
            <div><?php echo backUrl($back_page_url); ?></div>
        </div>
        <div class="card">
            <div class="card-body">
                <form method="post" onsubmit="return confirm('Are you sure to save data?');">
                    <div class="row gy-4 gx-1 table-form">
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="b_code" class="form-label">BRANCH CODE</label>
                                <input type="text" id="b_code" name="b_code" class="form-control" required
                                       autofocus value="<?php echo $b_code; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="b_name" class="form-label">BRANCH NAME</label>
                                <input value="<?php echo $b_name; ?>" id="b_name" name="b_name"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="name" class="form-label">NAME</label>
                                <input value="<?php echo $name; ?>" id="name" name="name"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="father_name" class="form-label">FATHER NAME</label>
                                <input value="<?php echo $father_name; ?>" id="father_name"
                                       name="father_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="choices-single-default" class="form-label">COUNTRY</label>
                                <select class="form-select" name="country_id"
                                        id="choices-single-default" required>
                                    <option value="" disabled selected>Select</option>
                                    <?php $countries = fetch('countries');
                                    while ($country = mysqli_fetch_assoc($countries)) {
                                        $c_sel = $country_id == $country['id'] ? 'selected' : '';
                                        echo '<option ' . $c_sel . ' value="' . $country['id'] . '">' . $country['name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="city" class="form-label">CITY</label>
                                <input value="<?php echo $city; ?>" id="city" name="city"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <label for="address" class="form-label">ADDRESS</label>
                                <input value="<?php echo $address; ?>" id="address" name="address"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <label for="zip_code" class="form-label">ZIP CODE</label>
                                <input value="<?php echo $zip_code; ?>" id="zip_code" name="zip_code"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="mobile" class="form-label">MOBILE</label>
                                <input value="<?php echo $mobile; ?>" id="mobile" name="mobile"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="phone" class="form-label">PHONE</label>
                                <input value="<?php echo $phone; ?>" id="phone" name="phone" required
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="whatsapp" class="form-label">WHATSAPP</label>
                                <input value="<?php echo $whatsapp; ?>" id="whatsapp" name="whatsapp"
                                       required class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="email" class="form-label">EMAIL</label>
                                <input value="<?php echo $email; ?>" type="email" id="email" name="email"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="username" class="form-label fw-bold">ADMIN USERNAME</label>
                                <input value="<?php echo $username; ?>" id="username" name="username"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="pass" class="form-label fw-bold">PASSWORD</label>
                                <input value="<?php echo $pass; ?>" id="pass" name="pass"
                                       class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="c_pass" class="form-label fw-bold">CONFIRM PASSWORD</label>
                                <input value="<?php echo $pass; ?>" id="c_pass" name="c_pass"
                                       class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button id="recordSubmit" name="recordSubmit" type="submit" class="btn btn-dark w-md">
                            Submit
                        </button>
                    </div>
                    <input type="hidden" value="update" name="action">
                    <input type="hidden" value="<?php echo $id; ?>" name="branch_id_hidden">
                    <input type="hidden" value="<?php echo $branch_user_id; ?>" name="user_id_hidden">
                </form>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>$("#entries").addClass('active');</script>
<script>$("#branches").addClass('active');</script>
<script>
    $(document).on('keyup', "#pass,#c_pass", function (e) {
        password();
    });
    password();

    function password() {
        var pass = $("#pass").val();
        var c_pass = $("#c_pass").val();
        if (pass === '' || c_pass === '') {
            disableButton('recordSubmit');
        } else {
            if (pass !== c_pass) {
                disableButton('recordSubmit');
            } else {
                enableButton('recordSubmit');
            }
        }
    }
</script>
<?php if (isset($_POST['recordSubmit'])) {
    $form_info = array('type' => 'danger', 'msg' => 'System Error!');
    $data = array(
        'b_code' => mysqli_real_escape_string($connect, $_POST['b_code']),
        'b_name' => mysqli_real_escape_string($connect, $_POST['b_name']),
        'name' => mysqli_real_escape_string($connect, $_POST['name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'country_id' => mysqli_real_escape_string($connect, $_POST['country_id']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'zip_code' => mysqli_real_escape_string($connect, $_POST['zip_code']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'whatsapp' => mysqli_real_escape_string($connect, $_POST['whatsapp']),
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $update_record = $record_added = false;
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $update_record = true;
    }
    if ($update_record) {
        $branch_id = mysqli_real_escape_string($connect, $_POST['branch_id_hidden']);
        $record_added = update('branches', $data, array('id' => $branch_id));
        $form_info['type'] = 'info';
        $form_info['msg'] = 'Branch has been updated.';
        $pageURL .= '?id=' . $branch_id;
    } else {
        $record_added = insert('branches', $data);
        $form_info['type'] = 'info';
        $form_info['msg'] = 'New Branch has been created. But Error in creating user login.';
        $branch_id = $connect->insert_id;
    }
    $data_user = array(
        'type' => 'office',
        'branch_id' => $branch_id,
        'role' => 'admin',
        'username' => mysqli_real_escape_string($connect, $_POST['username']),
        'pass' => mysqli_real_escape_string($connect, $_POST['pass']),
        'full_name' => mysqli_real_escape_string($connect, $_POST['name']),
        'image' => '',
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    if ($update_record) {
        $user_id = mysqli_real_escape_string($connect, $_POST['user_id_hidden']);
        $done2 = update('users', $data_user, array('id' => $user_id));
        $form_info['type'] = 'success';
    } else {
        $done2 = insert('users', $data_user);
        $user_id = $connect->insert_id;
        mysqli_query($connect, "UPDATE branches SET user_id = '$user_id' WHERE id='$branch_id'");
        $form_info['type'] = 'success';
        $form_info['msg'] = 'New Branch has been created.';
    }

    messageNew($form_info['type'], $pageURL, $form_info['msg']);
} ?>


