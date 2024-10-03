<?php $page_title = 'Branch Entry';
$back_page_url = 'branches';
include("header.php");
$newRecord = true;
$updateRecord = false;
$id = 0;
$record = array();
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $records = fetch('branches', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $branch_user_id = $record['user_id'];
    $users = fetch('users', array('id' => $branch_user_id));
    $record_user = mysqli_fetch_assoc($users);
    $newRecord = false;
    $updateRecord = true;
}
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card-body">
                <div class="mt-4 mt-xl-0">
                    <?php if ($updateRecord) { ?>
                        <form method="post" onsubmit="return confirm('Are you sure to update data?');">
                            <div class="row gy-4">
                                <div class="col-md-3">
                                    <label for="b_code" class="form-label">BRANCH CODE</label>
                                    <input type="text" id="b_code" name="b_code" class="form-control" required
                                           autofocus value="<?php echo $record['b_code']; ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="b_name" class="form-label">BRANCH NAME</label>
                                    <input value="<?php echo $record['b_name']; ?>" id="b_name" name="b_name"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="name" class="form-label">NAME</label>
                                    <input value="<?php echo $record['name']; ?>" id="name" name="name"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="father_name" class="form-label">FATHER NAME</label>
                                    <input value="<?php echo $record['father_name']; ?>" id="father_name"
                                           name="father_name" class="form-control"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label for="choices-single-default" class="form-label">COUNTRY</label>
                                    <select class="form-control" data-trigger name="country_id" id="choices-single-default" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php $countries = fetch('countries');
                                        while ($country = mysqli_fetch_assoc($countries)) {
                                            $c_sel = $record['country_id'] == $country['id'] ? 'selected' : '';
                                            echo '<option ' . $c_sel . ' value="' . $country['id'] . '">' . $country['name'] . '</option>';
                                        } ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="city" class="form-label">CITY</label>
                                    <input value="<?php echo $record['city']; ?>" id="city" name="city"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="address" class="form-label">ADDRESS</label>
                                    <input value="<?php echo $record['address']; ?>" id="address" name="address"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="zip_code" class="form-label">ZIP CODE</label>
                                    <input value="<?php echo $record['zip_code']; ?>" id="zip_code" name="zip_code"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="mobile" class="form-label">MOBILE</label>
                                    <input value="<?php echo $record['mobile']; ?>" id="mobile" name="mobile"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="phone" class="form-label">PHONE</label>
                                    <input value="<?php echo $record['phone']; ?>" id="phone" name="phone" required
                                           class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="whatsapp" class="form-label">WHATSAPP</label>
                                    <input value="<?php echo $record['whatsapp']; ?>" id="whatsapp" name="whatsapp"
                                           required class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="email" class="form-label">EMAIL</label>
                                    <input value="<?php echo $record['email']; ?>" type="email" id="email" name="email"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="username" class="form-label fw-bold">ADMIN USERNAME</label>
                                    <input value="<?php echo $record_user['username']; ?>" id="username" name="username"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="pass" class="form-label fw-bold">PASSWORD</label>
                                    <input value="<?php echo $record_user['pass']; ?>" id="pass" name="pass"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="c_pass" class="form-label fw-bold">CONFIRM PASSWORD</label>
                                    <input value="<?php echo $record_user['pass']; ?>" id="c_pass" name="c_pass"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button id="recordSubmit" name="recordSubmit" type="submit" class="btn btn-dark w-md">
                                    Update Branch
                                </button>
                            </div>
                            <input type="hidden" value="update" name="action">
                            <input type="hidden" value="<?php echo $id; ?>" name="branch_id_hidden">
                            <input type="hidden" value="<?php echo $record['user_id']; ?>" name="user_id_hidden">
                        </form>
                    <?php }
                    if ($newRecord) { ?>
                        <form method="post" onsubmit="return confirm('Are you sure to save data?');">
                            <div class="row gy-4">
                                <div class="col-md-3">
                                    <label for="b_code" class="form-label">BRANCH CODE</label>
                                    <input type="text" id="b_code" name="b_code" class="form-control" required
                                           autofocus>
                                </div>
                                <div class="col-md-3">
                                    <label for="b_name" class="form-label">BRANCH NAME</label>
                                    <input type="text" id="b_name" name="b_name" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="name" class="form-label">NAME</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="father_name" class="form-label">FATHER NAME</label>
                                    <input type="text" id="father_name" name="father_name" class="form-control"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label for="choices-single-default" class="form-label">COUNTRY</label>
                                    <select class="form-control" data-trigger name="country_id"
                                            id="choices-single-default" required>
                                        <option value="" disabled selected>Select</option>
                                        <?php $countries = fetch('countries');
                                        while ($country = mysqli_fetch_assoc($countries)) {
                                            echo '<option value="' . $country['id'] . '">' . $country['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="city" class="form-label">CITY</label>
                                    <input type="text" id="city" name="city"
                                           class="form-control" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="address" class="form-label">ADDRESS</label>
                                    <input type="text" id="address" name="address" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label for="zip_code" class="form-label">ZIP CODE</label>
                                    <input type="text" id="zip_code" name="zip_code" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="mobile" class="form-label">MOBILE</label>
                                    <input id="mobile" name="mobile" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="phone" class="form-label">PHONE</label>
                                    <input id="phone" name="phone" required class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="whatsapp" class="form-label">WHATSAPP</label>
                                    <input id="whatsapp" name="whatsapp" required class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="email" class="form-label">EMAIL</label>
                                    <input type="email" id="email" name="email" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="username" class="form-label fw-bold">ADMIN USERNAME</label>
                                    <input id="username" name="username" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="pass" class="form-label fw-bold">PASSWORD</label>
                                    <input id="pass" name="pass" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="c_pass" class="form-label fw-bold">CONFIRM PASSWORD</label>
                                    <input id="c_pass" name="c_pass" class="form-control" required>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button id="recordSubmit" name="recordSubmit" type="submit" class="btn btn-dark w-md">
                                    Add New Branch
                                </button>
                            </div>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
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
<?php $url = "branch-add";
if (isset($_POST['recordSubmit'])) {
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
        $url .= '?id=' . $branch_id;
    } else {
        $record_added = insert('branches', $data);
        $form_info['type'] = 'info';
        $form_info['msg'] = 'New Branch has been created. But Error in creating user login.';
        $branch_id = $connect->insert_id;
    }
    $data_user = array(
        'khaata_id' => 0,
        'role' => 'admin',
        'username' => mysqli_real_escape_string($connect, $_POST['username']),
        'pass' => mysqli_real_escape_string($connect, $_POST['pass']),
        'full_name' => mysqli_real_escape_string($connect, $_POST['name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'user_date' => date('Y-m-d'),
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'user_details' => 'branch admin',
        'branch_id' => $branch_id,
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

    message($form_info['type'], $url, $form_info['msg']);
} ?>


