<?php $page_title = 'User Entry';
$pageURL = 'user-add';
$back_page_url = 'users';
include("header.php");
$id = $branch_id = 0;
$sr = getAutoIncrement('users');
$user_date = date("Y-m-d");
$type = $full_name = $username = $password = $role = '';
$img_path = 'assets/images/avatar.jpg';
$contact_details = array();
/*contact details vars*/
$person_details = array('full_name' => '', 'father_name' => '', 'gender' => '', 'identity' => '', 'idn_no' => '', 'idn_reg' => '', 'idn_expiry' => '', 'idn_country' => '', 'country' => '', 'state' => '', 'city' => '', 'address' => '', 'postcode' => '', 'mobile' => '', 'phone' => '', 'whatsapp' => '');
if (isset($_GET['id']) && $_GET['id'] > 0) {
    $sr = $id = mysqli_real_escape_string($connect, $_GET['id']);

    if (!recordExists('users', ['id' => $id])) {
        messageNew('warning', $back_page_url, 'Something went wrong!');
    }

    $records = fetch('users', array('id' => $id));
    $record = mysqli_fetch_assoc($records);
    $user_date = $record['created_at'];
    $type = $record['type'];
    $branch_id = $record['branch_id'];
    $full_name = $record['full_name'];
    $username = $record['username'];
    $password = $record['pass'];
    $role = $record['role'];
    if (!empty($record['image']) && file_exists($record['image'])) {
        $img_path = $record['image'];
    }
    $contact_details = json_decode($record['contact_details']);
    if (!empty($contact_details)) {
        $person_details = array(
            'full_name' => $contact_details->full_name,
            'father_name' => $contact_details->father_name,
            'gender' => $contact_details->gender,
            'identity' => $contact_details->identity,
            'idn_no' => $contact_details->idn_no,
            'idn_reg' => $contact_details->idn_reg,
            'idn_expiry' => $contact_details->idn_expiry,
            'idn_country' => $contact_details->idn_country,
            'country' => $contact_details->country,
            'state' => $contact_details->state,
            'city' => $contact_details->city,
            'address' => $contact_details->address,
            'postcode' => $contact_details->postcode,
            'mobile' => $contact_details->mobile,
            'phone' => $contact_details->phone,
            'whatsapp' => $contact_details->whatsapp
        );
    }
} ?>
<!-- Main Container -->
<div class="fixed-top bg-light shadow-sm border-bottom">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-white rounded-3 p-4 shadow-sm">
                <h1 class="h4 text-uppercase mb-0 text-primary fw-bold">
                    <?php echo $page_title; ?>
                </h1>
                <div class="d-flex gap-2">
                    <?php echo backUrl($back_page_url); ?>
                    <?php echo addNew('user-add', '', 'btn-sm'); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="post" onsubmit="return confirm('Are you sure to save data?');" class="needs-validation" novalidate>
                        <div class="row">
                            <!-- Left Form Section -->
                            <div class="col-md-8">
                                <div class="row g-4">
                                    <!-- ID Type -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" name="type" id="type" required autofocus>
                                                <option value="" selected disabled>Select Type</option>
                                                <?php
                                                $id_types = array('agent', 'office', 'customer');
                                                foreach ($id_types as $id_type) {
                                                    $typeSelected = $id_type == $type ? 'selected' : '';
                                                    echo '<option ' . $typeSelected . ' value="' . $id_type . '">' . ucfirst($id_type) . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <label for="type">ID Type</label>
                                        </div>
                                    </div>

                                    <!-- Branch -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <select class="form-select" name="branch_id" id="branch_id" required>
                                                <option value="" selected disabled>Select Branch</option>
                                                <?php
                                                $branches = fetch('branches');
                                                while ($branch = mysqli_fetch_assoc($branches)) {
                                                    $branchSelected = $branch['id'] == $branch_id ? 'selected' : '';
                                                    echo '<option ' . $branchSelected . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <label for="branch_id">Branch</label>
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="full_name" value="<?php echo $full_name; ?>" required>
                                            <label for="name">Full Name</label>
                                        </div>
                                    </div>

                                    <!-- User ID -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                                            <label for="username">User ID</label>
                                        </div>
                                    </div>

                                    <!-- Password -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" autocomplete="off">
                                            <label for="password">Password</label>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="c_password" name="c_password" value="<?php echo $password; ?>" autocomplete="off">
                                            <label for="c_password">Confirm Password</label>
                                        </div>
                                    </div>

                                    <?php if ($id == 0 || ($id > 0 && userRoleStr($id) != 'superadmin')) { ?>
                                        <!-- Role -->
                                        <div class="col-md-3">
                                            <div class="form-floating">
                                                <select class="form-select" name="role" id="role" required>
                                                    <option value="" selected disabled>Select Role</option>
                                                    <?php
                                                    $roles = fetch('roles');
                                                    while ($rol = mysqli_fetch_assoc($roles)) {
                                                        $roleSelected = $rol['role_name'] == $role ? 'selected' : '';
                                                        echo '<option ' . $roleSelected . ' value="' . $rol['role_name'] . '">' . ucfirst($rol['role_name']) . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <label for="role">Role</label>
                                            </div>
                                        </div>

                                        <!-- Permissions -->
                                        <div class="col-md-9">
                                            <div class="form-floating">
                                                <select class="form-select" style="height:150px;" name="permission[]" id="permission" multiple data-silent-initial-value-set="true">
                                                    <?php
                                                    $pData = fetch('user_permissions', array('user_id' => $id));
                                                    $pDatum = mysqli_fetch_assoc($pData);
                                                    $forms = fetch('navbar', array('is_view' => 1));
                                                    while ($form = mysqli_fetch_assoc($forms)) {
                                                        $selected = '';
                                                        if (!empty($pDatum['permission'])) {
                                                            $perms = json_decode($pDatum['permission']);
                                                            $selected = in_array($form['url'], $perms) ? 'selected' : '';
                                                        }
                                                        echo '<option ' . $selected . ' value="' . $form['url'] . '">' . $form['label'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                                <label for="permission">Permissions</label>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Right Section -->
                            <div class="col-md-4 border-start ps-4">
                                <!-- User Info -->
                                <div class="d-flex justify-content-between mb-4 text-muted">
                                    <div class="fw-bold">Sr#: <span class="fw-normal"><?php echo $sr; ?></span></div>
                                    <div class="fw-bold">Date: <span class="fw-normal"><?php echo $user_date; ?></span></div>
                                </div>

                                <!-- Profile Picture -->
                                <div class="text-center mb-4">
                                    <form action="ajax/uploadPicture.php" method="post" id="picUpload" enctype="multipart/form-data">
                                        <div class="position-relative d-inline-block">
                                            <img src="<?php echo $img_path; ?>" alt="Profile" class="rounded-circle object-fit-cover" style="width: 120px; height: 120px;">
                                            <label for="dropify" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer">
                                                <i class="bi bi-camera-fill"></i>
                                                <input type="file" id="dropify" name="fileUpload" class="d-none" required>
                                            </label>
                                        </div>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="pk" value="id">
                                        <input type="hidden" name="tbl" value="users">
                                        <input type="hidden" name="url" value="user-add?id=<?php echo $id; ?>">
                                    </form>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
                                    <button type="submit" name="recordSubmit" class="btn btn-primary">
                                        <i class="bi bi-check2-circle me-2"></i>Submit
                                    </button>
                                    <?php if ($id > 0) { ?>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contactDetails">
                                            <i class="bi bi-person-lines-fill me-2"></i>Details
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#attachAccount">
                                            <i class="bi bi-link-45deg me-2"></i>Account
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Personal Details -->
                        <div class="col-md-8 border-end">
                            <?php if (!empty($contact_details)) { ?>
                                <h5 class="mb-4 text-primary">Personal Information</h5>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <div class="fw-bold text-muted small">NAME</div>
                                        <div><?php echo $person_details['full_name']; ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fw-bold text-muted small">FATHER NAME</div>
                                        <div><?php echo $person_details['father_name']; ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fw-bold text-muted small">GENDER</div>
                                        <div><?php echo ucfirst($person_details['gender']); ?></div>
                                    </div>

                                    <!-- Identity Information -->
                                    <div class="col-12">
                                        <div class="bg-light p-3 rounded-3">
                                            <div class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-muted small">IDENTITY</div>
                                                    <div><?php echo $person_details['identity']; ?></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-muted small">ID NUMBER</div>
                                                    <div><?php echo $person_details['idn_no']; ?></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-muted small">REGISTRATION DATE</div>
                                                    <div><?php echo $person_details['idn_reg']; ?></div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="fw-bold text-muted small">EXPIRY DATE</div>
                                                    <div><?php echo $person_details['idn_expiry']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-12">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="fw-bold text-muted small">MOBILE</div>
                                                <div><?php echo $person_details['mobile']; ?></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fw-bold text-muted small">PHONE</div>
                                                <div><?php echo $person_details['phone']; ?></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fw-bold text-muted small">WHATSAPP</div>
                                                <div><?php echo $person_details['whatsapp']; ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Information -->
                                    <div class="col-12">
                                        <div class="fw-bold text-muted small">ADDRESS</div>
                                        <div><?php echo $person_details['address']; ?></div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <!-- Account Information -->
                        <div class="col-md-4 ps-4">
                            <h5 class="mb-4 text-primary">Account Information</h5>
                            <?php
                            $attached = userAttachedAccount($id);
                            if (!empty($attached)) {
                                echo '<div class="d-flex align-items-center gap-2">';
                                echo '<i class="bi bi-credit-card text-primary fs-4"></i>';
                                echo '<div>';
                                echo '<div class="text-muted small">Account Number</div>';
                                echo '<a href="khaata-add?id=' . $attached['khaata_id'] . '" class="text-decoration-none">' . $attached['khaata_no'] . '</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="contactDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="contactDetailsLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="contactDetailsLabel">Contact Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body table-form">
                    <div class="row gx-1 gy-4">
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="full_name" class="form-label">Name</label>
                                <input value="<?php echo $person_details['full_name']; ?>" id="full_name"
                                    name="full_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="father_name" class="form-label">Father Name</label>
                                <input value="<?php echo $person_details['father_name']; ?>" id="father_name"
                                    name="father_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" name="gender" id="gender">
                                    <option selected hidden disabled="">Select</option>
                                    <?php $genders = array('male', 'female', 'other');
                                    foreach ($genders as $gender) {
                                        $genderSelected = $gender == $person_details['gender'] ? 'selected' : '';
                                        echo '<option ' . $genderSelected . ' value="' . $gender . '">' . ucfirst($gender) . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="identity" class="form-label">Identity</label>
                                <select class="form-select" name="identity" id="identity">
                                    <option selected hidden disabled="">Select</option>
                                    <?php $identities = array('passport', 'cnic', 'uae');
                                    foreach ($identities as $identity) {
                                        $idSelected = $identity == $person_details['identity'] ? 'selected' : '';
                                        echo '<option ' . $idSelected . ' value="' . $identity . '">' . ucfirst($identity) . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row g-0">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_no" class="form-label">No</label>
                                        <input value="<?php echo $person_details['idn_no']; ?>" id="idn_no"
                                            name="idn_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_reg" class="form-label">Reg.</label>
                                        <input type="date" value="<?php echo $person_details['idn_reg']; ?>"
                                            id="idn_reg" name="idn_reg" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_expiry" class="form-label">Expiry</label>
                                        <input type="date" value="<?php echo $person_details['idn_expiry']; ?>"
                                            id="idn_expiry" name="idn_expiry" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label for="idn_country" class="form-label">Country</label>
                                        <input value="<?php echo $person_details['idn_country']; ?>"
                                            id="idn_country" name="idn_country" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="country" class="form-label">Country</label>
                                <input value="<?php echo $person_details['country']; ?>" id="country" name="country"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="state" class="form-label">State</label>
                                <input value="<?php echo $person_details['state']; ?>" id="state" name="state"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label for="city" class="form-label">City</label>
                                <input value="<?php echo $person_details['city']; ?>" id="city" name="city"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group">
                                <label for="address" class="form-label">Address</label>
                                <input value="<?php echo $person_details['address']; ?>" id="address" name="address"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="postcode" class="form-label">Postcode</label>
                                <input value="<?php echo $person_details['postcode']; ?>" id="postcode"
                                    name="postcode" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input value="<?php echo $person_details['mobile']; ?>" id="mobile" name="mobile"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input value="<?php echo $person_details['phone']; ?>" id="phone" name="phone"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input value="<?php echo $person_details['whatsapp']; ?>" id="whatsapp"
                                    name="whatsapp" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="contactDetailsSubmit" class="btn btn-dark">Save</button>
                </div>
            </div>
            <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
        </form>
    </div>
</div>
<div class="modal fade" id="attachAccount" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="attachAccountLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg-">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="attachAccountLabel">Attach Account</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" class="table-form">
                <div class="modal-body">
                    <div class="row gx-1 gy-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="khaata_id" class="form-label">A/c. Sr#</label>
                                <input type="number" step="1" min="1" id="khaata_id" name="khaata_id"
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <label for="khaata_no" class="form-label">A/c. No.</label>
                                <input type="text" id="khaata_no" name="khaata_no" class="form-control"
                                    value="<?php //echo $bkn; 
                                            ?>" required>
                            </div>
                        </div>
                        <div class="col" id="khaata_details"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="attachAccountSubmit" id="attachAccountSubmit" class="btn btn-dark">
                        Submit
                    </button>
                </div>
                <input type="hidden" name="hidden_id" value="<?php echo $id; ?>">
            </form>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
<script>
    $("#entries").addClass('active');
</script>
<script>
    $("#users").addClass('active');
</script>
<script>
    $(document).on('keyup', "#password,#c_password", function(e) {
        password();
    });
    password();

    function password() {
        var pass = $("#password").val();
        var c_pass = $("#c_password").val();
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
<script>
    $(document).on('keyup', "#khaata_no,#khaata_id", function(e) {
        fetchKhaata();
    });
    fetchKhaata();
    disableButton('attachAccountSubmit');

    function fetchKhaata() {
        let khaata_no = $("#khaata_no").val();
        let khaata_id = $("#khaata_id").val();
        $.ajax({
            url: 'ajax/attachAccountWithUser.php',
            type: 'post',
            data: {
                khaata_no: khaata_no,
                khaata_id: khaata_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success === true) {
                    $("#khaata_details").html('<b>A/C NAME	</b>' + response.messages['khaata_name']);
                    enableButton('attachAccountSubmit');
                    /*var details = {
                        indexes: response.messages['indexes'],
                        vals: response.messages['vals']
                    };
                    $("#contacts").html(displayKhaataDetails(details));*/
                    $("#khaata_no").addClass('is-valid');
                    $("#khaata_no").removeClass('is-invalid');
                    //$("#response").text('');
                }
                if (response.success === false) {
                    $("#khaata_no").addClass('is-invalid');
                    $("#khaata_no").removeClass('is-valid');
                    disableButton('attachAccountSubmit');
                    $("#khaata_details").html('');
                }
            }
        });
    }
</script>
<?php $msg_array = array('msg' => 'DB Error', 'type' => 'danger');
if (isset($_POST['recordSubmit'])) {
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    /*$image = $_FILES['user_image']['name'];
    $path = "uploads/" . $image;
    $moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $path);*/
    $data = array(
        'type' => mysqli_real_escape_string($connect, $_POST['type']),
        'branch_id' => $_POST['branch_id'],
        'full_name' => mysqli_real_escape_string($connect, $_POST['full_name']),
        'username' => mysqli_real_escape_string($connect, $_POST['username']),
        'pass' => mysqli_real_escape_string($connect, $_POST['password']),
    );
    if (isset($_POST['role'])) {
        $data['role'] = mysqli_real_escape_string($connect, $_POST['role']);
    }
    if (isset($_POST['permission'])) {
        $permission_string = implode(',', $_POST['permission']);
        $permissions = explode(',', $permission_string);
    }
    if ($hidden_id > 0) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $done = update('users', $data, array('id' => $hidden_id));
        $pageURL .= "?id=" . $hidden_id;
        if ($done) {
            $u_role = userRoleStr($hidden_id);
            if ($u_role != 'superadmin') {
                $dataPermission = array('permission' => json_encode($permissions));
                if (getNumRows('user_permissions', 'user_id', $hidden_id) > 0) {
                    $doneP = update('user_permissions', $dataPermission, array('user_id' => $hidden_id));
                } else {
                    $doneP = insert('user_permissions', $dataPermission);
                }
            }
            $msg_array['msg'] = 'User Successfully Updated.';
            $msg_array['type'] = 'success';
        }
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $done = insert('users', $data);
        if ($done) {
            $insert_id = $connect->insert_id;
            $pageURL .= "?id=" . $insert_id;
            $u_role = userRoleStr($insert_id);
            if ($u_role != 'superadmin') {
                $dataPermission = array('user_id' => $insert_id, 'permission' => json_encode($permissions));
                $doneP = insert('user_permissions', $dataPermission);
            }
            $msg_array['msg'] = 'New User Successfully added.';
            $msg_array['type'] = 'success';
        }
    }
    messageNew($msg_array['type'], $pageURL, $msg_array['msg']);
} ?>
<?php if (isset($_POST['contactDetailsSubmit'])) {
    $post = json_encode($_POST);
    $data = array('contact_details' => $post);
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $done = update('users', $data, array('id' => $hidden_id));
    if ($done) {
        $msg_array['msg'] = 'Contact details Successfully Updated.';
        $msg_array['type'] = 'success';
    }
    messageNew($msg_array['type'], $pageURL . '?id=' . $hidden_id, $msg_array['msg']);
} ?>
<?php if (isset($_POST['attachAccountSubmit'])) {
    unset($_POST['attachAccountSubmit']);
    $post = json_encode($_POST);
    $data = array('khaata' => $post);
    $hidden_id = mysqli_real_escape_string($connect, $_POST['hidden_id']);
    $k_id = mysqli_real_escape_string($connect, $_POST['khaata_id']);
    $k_no = mysqli_real_escape_string($connect, $_POST['khaata_no']);
    $dumm = fetch('khaata', array('id' => $k_id, 'khaata_no' => $k_no));
    if (mysqli_num_rows($dumm) > 0) {
        $done = update('users', $data, array('id' => $hidden_id));
        if ($done) {
            $msg_array['msg'] = 'Account Successfully Attached.';
            $msg_array['type'] = 'success';
        }
    }
    messageNew($msg_array['type'], $pageURL . '?id=' . $hidden_id, $msg_array['msg']);
} ?>