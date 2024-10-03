<?php $page_title = 'User Entry';
$back_page_url = 'users';
include("header.php"); ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <?php if (isset($_SESSION['response'])) {
                echo $_SESSION['response'];
                unset($_SESSION['response']);
            } ?>
            <div class="card-body">
                <div class="mt-4 mt-xl-0">
                    <?php if (isset($_GET['id'])) {
                        $id = mysqli_real_escape_string($connect, $_GET['id']);
                        $records = fetch('users', array('id' => $id));
                        $record = mysqli_fetch_assoc($records); ?>
                        <div class="row g-2">
                            <div class="col-md-10">
                                <form method="post" id="addUserForm" enctype="multipart/form-data"
                                      onsubmit="return confirm('Are you sure to save data?');">
                                    <div class="row gy-4">
                                        <div class="col-md-3 col-12">
                                            <div class="input-group">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" id="name" name="full_name"
                                                       class="form-control" required autofocus
                                                       value="<?php echo $record['full_name']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="father_name" class="form-label">Father</label>
                                                <input type="text" id="father_name" name="father_name"
                                                       value="<?php echo $record['father_name']; ?>"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="user_date" class="form-label">Date</label>
                                                <input name="user_date" id="user_date"
                                                       value="<?php echo $record['user_date']; ?>" type="date"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="branch_id" class="form-label">Branch</label>
                                                <select id="branch_id" name="branch_id" class="form-select">
                                                    <option hidden class="" selected disabled="">Select</option>
                                                    <?php $branches = fetch('branches');
                                                    while ($branch = mysqli_fetch_assoc($branches)) {
                                                        $branchSelected = $branch['id'] == $record['branch_id'] ? 'selected' : '';
                                                        echo '<option ' . $branchSelected . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="row gx-0">
                                                <div class="col-lg-1">
                                                    <label for="permission"
                                                           class="col-form-label">Permissions</label>
                                                </div>
                                                <div class="col-lg-11">
                                                    <select multiple name="permission[]" id="permission" class="v-select" data-silent-initial-value-set="true">
                                                        <?php $pData = fetch('user_permissions', array('user_id' => $id));
                                                        $pDatum = mysqli_fetch_assoc($pData);
                                                        $forms = fetch('navbar', array('is_view' => 1));
                                                        while ($form = mysqli_fetch_assoc($forms)) {
                                                            $selected = '';
                                                            if (!empty($pDatum['permission'])) {
                                                                $perms = json_decode($pDatum['permission']);
                                                                $selected = in_array($form['url'], $perms) ? 'selected' : '';
                                                            }
                                                            //echo '<option ' . $selected . ' value="' . $form['id'] . '">' . $form['label'] . '</option>';
                                                            echo '<option ' . $selected . ' value="' . $form['url'] . '">' . $form['label'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="role" class="form-label">Designation</label>
                                                <select class="form-select" name="role" id="role">
                                                    <option selected hidden disabled="">Select</option>
                                                    <?php $roles = fetch('roles');
                                                    while ($role = mysqli_fetch_assoc($roles)) {
                                                        $roleSelected = $role['role_name'] == $record['role'] ? 'selected' : '';
                                                        echo '<option ' . $roleSelected . ' value="' . $role['role_name'] . '">' . ucfirst($role['role_name']) . '</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input value="<?php echo $record['email']; ?>" id="email"
                                                       name="email" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="mobile" class="form-label">Mobile</label>
                                                <input type="text" id="mobile" name="mobile"
                                                       value="<?php echo $record['mobile']; ?>"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text" id="phone" name="phone" required
                                                       value="<?php echo $record['phone']; ?>" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="username" class="form-label">User ID</label>
                                                <input type="text" id="username" name="username"
                                                       value="<?php echo $record['username']; ?>"
                                                       class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <label for="password" class="form-label">Password</label>
                                                <input id="password" class="form-control" name="password"
                                                       value="<?php echo $record['pass']; ?>" type="text"
                                                       autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <label for="user_details"
                                                           class="form-label">Details</label>
                                                    <input type="text" id="user_details" name="user_details"
                                                           value="<?php echo $record['user_details']; ?>"
                                                           class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                                        <div class="mt-3">
                                            <button type="submit" name="userUpdate"
                                                    class="btn btn-danger">Update
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2 text-center">
                                <form action="ajax/uploadPicture.php" method="post" id="picUpload"
                                      enctype="multipart/form-data">
                                    <label for="dropify" class="">
                                        <?php if (!empty($record['image']) && file_exists($record['image'])) {
                                            $img_path = $record['image'];
                                        } else {
                                            $img_path = 'assets/images/logo.png';
                                            //echo '<img class="img-fluid rounded" src="assets/images/logo.png">';
                                        }
                                        echo '<img class="img-fluid rounded" width="200" src="' . $img_path . '" alt="">'; ?>
                                        <input type="file" id="dropify" name="fileUpload" class="sr-only" required>
                                    </label>
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="pk" value="id">
                                    <input type="hidden" name="tbl" value="users">
                                    <input type="hidden" name="url" value="user-add?id=<?php echo $id; ?>">
                                </form>
                                <script>
                                    document.getElementById("dropify").onchange = function () {
                                        document.getElementById("picUpload").submit();
                                    }
                                </script>
                            </div>
                        </div>
                    <?php } else { ?>
                        <form method="post" id="addUserForm" enctype="multipart/form-data"
                              onsubmit="return confirm('Are you sure to save data?');">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="row gy-4">
                                        <div class="col-md-3">
                                            <label for="name" class="form-label">NAME</label>
                                            <input type="text" id="name" name="full_name" class="form-control"
                                                   required autofocus>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="father_name" class="form-label">FATHER NAME</label>
                                            <input type="text" id="father_name" name="father_name"
                                                   class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="user_date" class="form-label">Date</label>
                                            <input name="user_date" id="user_date" value="<?php echo date('Y-m-d'); ?>"
                                                   type="date" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="branch_id">Branch</label>
                                            <select id="branch_id" name="branch_id" class="form-select branch_id"
                                                    required>
                                                <option hidden value="">Select</option>
                                                <?php $branches = fetch('branches');
                                                while ($branch = mysqli_fetch_assoc($branches)) {
                                                    echo '<option value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="cat_id">Category</label>
                                            <select class="form-select cat_id" name="cat_id" id="cat_id" required>
                                                <option hidden value="">Select</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="permission" class="form-label">Permissions</label>
                                            <select multiple name="permission[]" id="permission"
                                                    class="v-select " data-silent-initial-value-set="true">
                                                <?php $forms = fetch('navbar', array('is_view' => 1));
                                                while ($form = mysqli_fetch_assoc($forms)) {
                                                    echo '<option value="' . $form['url'] . '">' . $form['label'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="role" class="form-label">Designation</label>
                                            <select class="form-select" name="role" id="role">
                                                <option selected hidden disabled="">Select</option>
                                                <?php $roles = fetch('roles');
                                                while ($role = mysqli_fetch_assoc($roles)) {
                                                    echo '<option value="' . $role['role_name'] . '">' . ucfirst($role['role_name']) . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input id="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="mobile" class="form-label">Mobile</label>
                                            <input type="text" id="mobile" name="mobile"
                                                   class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" id="phone" name="phone" required
                                                   class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">User ID</label>
                                            <input type="text" id="username" name="username"
                                                   class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input id="pass" class="form-control" name="password" type="password"
                                                   autocomplete="off">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="password" class="form-label">Confirm Password</label>
                                            <input id="c_pass" class="form-control" name="password" type="password"
                                                   autocomplete="off">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="user_details" class="form-label">Details</label>
                                            <input type="text" id="user_details" name="user_details"
                                                   class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <input type="file" class="dropify" name="user_image" required/>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button id="recordSubmit" name="userSubmit" type="submit" class="btn btn-primary w-md">
                                    Submit
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
<script type="text/javascript">
    $(document).ready(function () {
        $('.branch_id').on('change', function () {
            var branch_id = $(this).val();
            if (branch_id) {
                $.ajax({
                    type: 'POST',
                    url: 'ajax/fetch_branch_cats.php',
                    data: 'branch_id=' + branch_id,
                    success: function (html) {
                        $('.cat_id').html(html);
                    }
                });
            } else {
                $('.cat_id').html('<option value="">Select branch first</option>');
            }
        });
    });
</script>
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
<?php if (isset($_POST['userSubmit'])) {
    $url = "user-add";
    $msg = "DB failed";
    $type = "danger";
    $image = $_FILES['user_image']['name'];
    $path = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $path);
    /*$ut_permission_string = implode(',', $_POST['ut_permissions']);
    $ut_permissions = explode(',', $ut_permission_string);
    $ut_roznamcha_string = implode(',', $_POST['ut_roznamcha']);
    $ut_roznamcha = explode(',', $ut_roznamcha_string);*/
    $data = array(
        'full_name' => mysqli_real_escape_string($connect, $_POST['full_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'user_date' => $_POST['user_date'],
        'branch_id' => $_POST['branch_id'],
        'cat_id' => $_POST['cat_id'],
        'role' => $_POST['role'],
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'username' => mysqli_real_escape_string($connect, $_POST['username']),
        'pass' => mysqli_real_escape_string($connect, $_POST['password']),
        'user_details' => mysqli_real_escape_string($connect, $_POST['user_details']),
        'image' => $path,
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    $done = insert('users', $data);
    if ($done) {
        $insertId = $connect->insert_id;
        $url .= "?id=" . $insertId;
        $msg = "New User Saved.";
        $type = "success";
        $permission_string = implode(',', $_POST['permission']);
        $permissions = explode(',', $permission_string);
        $dataPermission = array('user_id' => $insertId, 'permission' => json_encode($permissions));
        $doneP = insert('user_permissions', $dataPermission);
    }
    message($type, $url, $msg);
}
if (isset($_POST['userUpdate'])) {
    $hidden_id = $_POST['hidden_id'];
    $url = "user-add?id=" . $hidden_id;
    $data = array(
        'full_name' => mysqli_real_escape_string($connect, $_POST['full_name']),
        'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
        'user_date' => $_POST['user_date'],
        'branch_id' => $_POST['branch_id'],
        'cat_id' => $_POST['cat_id'],
        'email' => mysqli_real_escape_string($connect, $_POST['email']),
        'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
        'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
        'username' => mysqli_real_escape_string($connect, $_POST['username']),
        'pass' => mysqli_real_escape_string($connect, $_POST['password']),
        'user_details' => mysqli_real_escape_string($connect, $_POST['user_details']),
        /*'ut_permissions' => json_encode($ut_permissions),
        'ut_roznamcha' => json_encode($ut_roznamcha)*/
    );
    $done = update('users', $data, array('id' => $hidden_id));
    $permission_string = implode(',', $_POST['permission']);
    $permissions = explode(',', $permission_string);
    $str = '';
    if (getNumRows('user_permissions', 'user_id', $hidden_id)) {
        $dataPermission = array('permission' => json_encode($permissions));
        $doneP = update('user_permissions', $dataPermission, array('user_id' => $hidden_id));
        $str = ' Permissions updated.';
    } else {
        $dataPermission = array('user_id' => $hidden_id, 'permission' => json_encode($permissions));
        $doneP = insert('user_permissions', $dataPermission);
        $str = ' New Permissions added.';
    }

    if ($done && $doneP) {
        message('info', $url, 'User updated. '.$str);
    } else {
        message('danger', $url, 'DB Error. :(');
    }

} ?>

