<?php $page_title = 'Account Entry';
$back_page_url = 'khaata';
include("header.php"); ?>
<?php $infoArray = array('title' => 'New', 'acc_for' => 'client');
$newRecord = true;
$updateRecord = $add_update_details = false;
$client_checked = $bank_checked = $agent_checked = $also_user_checked = $action = $acc_for_get = $userID = $userPASS = $userROLE = '';
$pageURL = $pageURLBack = 'khaata-add';
$types_array = array('client', 'bank', 'agent');
if (isset($_GET['acc_for']) && in_array($_GET['acc_for'], $types_array)) {
//if (isset($_GET['acc_for']) && ($_GET['acc_for'] === "client" || $_GET['acc_for'] === "bank" || $_GET['acc_for'] === "agent")) {
    $infoArray['acc_for'] = $acc_for_get = mysqli_real_escape_string($connect, $_GET['acc_for']);
    if ($acc_for_get == "client") {
        $client_checked = 'checked';
    }
    if ($acc_for_get == "bank") {
        $bank_checked = 'checked';
    }
    if ($acc_for_get == "agent") {
        $agent_checked = 'checked';
    }
    $pageURL .= '?acc_for=' . $acc_for_get;
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = mysqli_real_escape_string($connect, $_GET['id']);
        $pageURLBack = $pageURL;
        $pageURL .= '&id=' . $id;
        $newRecord = false;
        $updateRecord = true;
        $infoArray['title'] = 'Update';
        $records = fetch('khaata', array('id' => $id));
        $record = mysqli_fetch_assoc($records);
        $qqqqq = fetch('users', array('khaata_id' => $id));
        if (mysqli_num_rows($qqqqq) > 0) {
            $record_user = mysqli_fetch_assoc($qqqqq);
            $userID = $record_user['username'];
            $userPASS = $record_user['pass'];
            $userROLE = $record_user['role'];
            $also_user_checked = 'checked';
        }
        if (isset($_GET['action'])) {
            $action = mysqli_real_escape_string($connect, $_GET['action']);
            $add_update_details = true;
        }
    }
}
$aaa = array('comp_name' => 'comp_name1', 'country_id' => 'country_id', 'city' => 'city1', 'address' => 'address1', 'report' => 'report1');
$bbb = array('comp_name' => 'comp_name2', 'ac_no' => 'ac_no', 'owner_name' => 'owner_name', 'bank_name' => 'bank_name', 'ifsc_code' => 'ifsc_code', 'country_id' => 'country_id', 'city' => 'city2', 'address' => 'address2', 'report' => 'report2');
$ccc = array('comp_name' => 'comp_name3', 'country_id' => 'country_id', 'city' => 'city3', 'address' => 'address3', 'report' => 'report3');
$labels = array(array('A/C NAME', 'BUSINESS<br>CAPITAL', 'OWNER NAME'), array('BANK NAME', 'BRANCH<br>NAME', 'BANK A/C No.')); ?>
<style>
    .table-form .table label {
        padding-right: 0 !important;
        margin-bottom: 0 !important;
        text-transform: uppercase;
    }

    .table-form .table-pb tbody tr:not(:last-child) td {
        padding-bottom: 1rem !important;
    }

    .simple-inputs .form-control {
        border: none;
        border-bottom: 1px solid #eff0f2;
        padding: 0 3px;
        line-height: 20px;
        color: black;
        font-weight: 600;
    }

    .simple-inputs label {
        white-space: nowrap;
    }

    .choices__inner {
        padding: 0;
        background-color: #fff;
        vertical-align: middle;
        border-radius: initial;
        border: 1px solid #e2e5e8;
        min-height: 20px;
    }

    .choices[data-type*=select-one] .choices__inner {
        padding-bottom: 0;
    }
</style>
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center gap-1 gap-md-3 mb-2">
            <div class="d-flex align-items-center gap-md-3">
                <!--<h2 class="card-title"><?php /*echo $infoArray['title'] . ' ' . ucfirst($infoArray['acc_for']) . ' Account'; */ ?></h2>-->
                <?php if ($updateRecord) { ?>
                    <div>
                        <form action="ajax/uploadPicture.php" method="post" id="picUpload"
                              enctype="multipart/form-data">
                            <label for="dropify" class="pointer mb-0">
                                <?php if (!empty($record['image'])) {
                                    $imgSrc = $record['image'];
                                } else {
                                    $imgSrc = 'assets/images/avatar.jpg';
                                }
                                echo '<img width="50" class="rounded shadow -header-profile-user" src="' . $imgSrc . '" alt="profile">'; ?>
                                <input type="file" id="dropify" name="fileUpload" class="sr-only" required>
                            </label>
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input type="hidden" name="pk" value="id">
                            <input type="hidden" name="tbl" value="khaata">
                            <input type="hidden" name="url"
                                   value="khaata-add?id=<?php echo $id; ?>&acc_for=<?php echo $infoArray['acc_for']; ?>">
                        </form>
                    </div>
                <?php } ?>
                <div class="table-form ">
                    <div class="input-group ">
                        <label for="client" class="mb-0">A/c for:</label>
                        <div class="form-control d-flex bg-white">
                            <div class="form-check form-check-inline">
                                <input type="radio" id="client" name="acc_for" class="form-check-input acc_for"
                                       value="client" <?php echo $client_checked; ?>>
                                <label class="form-check-label" for="client">Client</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="bank" name="acc_for" class="form-check-input acc_for"
                                       value="bank" <?php echo $bank_checked; ?>>
                                <label class="form-check-label" for="bank">Bank</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="radio" id="agent" name="acc_for" class="form-check-input acc_for"
                                       value="agent" <?php echo $agent_checked; ?>>
                                <label class="form-check-label" for="agent">Agent</label>
                            </div>
                        </div>
                        <?php if ($updateRecord) {
                            echo $record['acc_for'] == $acc_for_get ? '' : '<i class="bx bx-info-circle text-danger"></i>';
                        } ?>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION['response'])) {
                echo '<div class="flex-fill">' . $_SESSION['response'] . '</div>';
                unset($_SESSION['response']);
            } ?>
            <div class="d-flex gap-1">
                <?php if ($updateRecord) { ?>
                    <?php if (SuperAdmin()) { ?>
                        <form method="post"
                              onsubmit="return confirm('Are you sure to delete A/c. <?php echo $record['khaata_no']; ?>');">
                            <input type="hidden" name="khaata_id_hidden" value="<?php echo $id; ?>">
                            <input type="hidden" name="khaata_no_hidden"
                                   value="<?php echo $record['khaata_no']; ?>">
                            <button name="deleteKhaataSubmit" class="btn btn-outline-danger btn-sm"
                                    type="submit" data-bs-toggle="tooltip"
                                    data-bs-title="Delete A/c. <?php echo $record['khaata_no'] ?>">Delete
                            </button>
                        </form>
                    <?php } ?>
                    <form action="#" method="post" id="userPicUpload" enctype="multipart/form-data">
                        <input type="hidden" name="khaataIdDocs" value="<?php echo $id; ?>">
                        <input type="file" id="file" class="d-none" name="file[]" multiple>
                        <button class="btn btn-outline-dark btn-sm"
                                type="button" data-bs-toggle="tooltip"
                                data-bs-title="Upload CNIC, License etc.  with this account."
                                value="Browse..."
                                onclick="document.getElementById('file').click();"><i
                                class="fa fa-upload"></i> Documents
                        </button>
                        <script>
                            document.getElementById("file").onchange = function () {
                                document.getElementById("userPicUpload").submit();
                            }
                        </script>
                    </form>
                <?php } ?>
                <?php echo addNew('khaata-add?acc_for=client', '', 'btn-sm');
                //echo backUrl('khaata'); ?>
            </div>
        </div>
        <div class="card">
            <!--<div class="card-header"></div>-->
            <div class="card-body">
                <?php if ($updateRecord) { ?>
                    <div class="row table-form">
                        <div class="col-md-12">
                            <form method="post" onsubmit="return confirm('Are you sure to save data?');"
                                  enctype="multipart/form-data" class="table-form">
                                <input type="hidden" name="acc_for" value="<?php echo $acc_for_get; ?>">
                                <div class="d-flex gap-md-2 align-items-center justify-content-between mb-3">
                                    <div class="input-group">
                                        <label for="sr_no">G A/C.#</label>
                                        <input type="text" id="sr_no" class="form-control" disabled
                                               value="<?php echo $id; ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="branch_id">BRANCH</label>
                                        <select id="branch_id" name="branch_id" class="form-select branch_id" required>
                                            <option selected hidden disabled>Select</option>
                                            <?php $branches = fetch('branches');
                                            while ($branch = mysqli_fetch_assoc($branches)) {
                                                $brancSelected = $branch['id'] == $record['branch_id'] ? 'selected' : '';
                                                echo '<option ' . $brancSelected . ' value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="cat_id">CATEGORY</label>
                                        <select class="form-select cat_id" name="cat_id" id="cat_id" required>
                                            <option selected hidden disabled>Select</option>
                                            <?php $cats = fetch('cats');
                                            while ($cat = mysqli_fetch_assoc($cats)) {
                                                $catSelected = $cat['id'] == $record['cat_id'] ? 'selected' : '';
                                                echo '<option ' . $catSelected . ' value="' . $cat['id'] . '">' . $cat['name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="khaata_date">DATE</label>
                                        <input id="khaata_date" name="khaata_date"
                                               value="<?php echo $record['khaata_date']; ?>" type="date"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="row gx-5">
                                    <div class="col-md-5">
                                        <table class="table table-borderless table-pb">
                                            <tr>
                                                <td><label for="khaata_no">A/C No.</label></td>
                                                <td><input type="text" id="khaata_no" name="khaata_no"
                                                           class="form-control" required
                                                           autofocus value="<?php echo $record['khaata_no']; ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label
                                                        for="khaata_name"><?php echo $acc_for_get == 'bank' ? $labels[1][0] : $labels[0][0]; ?></label>
                                                </td>
                                                <td><input type="text" id="khaata_name" name="khaata_name"
                                                           class="form-control"
                                                           value="<?php echo $record['khaata_name']; ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="line-height-1"
                                                           for="comp_name">Company<br>Name</label></td>
                                                <td><input type="text" id="comp_name" name="comp_name"
                                                           class="form-control"
                                                           value="<?php echo $record['comp_name']; ?>"></td>
                                            </tr>
                                            <tr>
                                                <td><label class="line-height-1"
                                                           for="business_name"><?php echo $acc_for_get == 'bank' ? $labels[1][1] : $labels[0][1]; ?></label>
                                                </td>
                                                <td><input type="text" id="business_name"
                                                           name="business_name" class="form-control"
                                                           value="<?php echo $record['business_name']; ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="choices-single-default"
                                                           class="form-label">COUNTRY</label></td>
                                                <td>
                                                    <select class="form-select" data-trigger name="country_id"
                                                            id="choices-single-default" required>
                                                        <option value="" disabled selected>Select</option>
                                                        <?php $countries = fetch('countries');
                                                        while ($country = mysqli_fetch_assoc($countries)) {
                                                            $c_sel = $record['country_id'] == $country['id'] ? 'selected' : '';
                                                            echo '<option ' . $c_sel . ' value="' . $country['id'] . '">' . $country['name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="city">City</label></td>
                                                <td><input type="text" id="city" name="city"
                                                           class="form-control"
                                                           value="<?php echo $record['city']; ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="line-height-1" for="address">Address</label></td>
                                                <td><textarea id="address" name="address"
                                                              class="form-control"><?php echo htmlspecialchars($record['address']); ?></textarea>
                                                </td>
                                            </tr>
                                            <?php if ($acc_for_get == "bank") { ?>
                                                <tr>
                                                    <td><label for="currency_b">Currency</label></td>
                                                    <td>
                                                        <select id="currency_b" name="currency" class="form-select"
                                                                required>
                                                            <option hidden value="">Select</option>
                                                            <?php $currencies = fetch('currencies');
                                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                $curr_sel = $crr['name'] == $record['currency'] ? 'selected' : '';
                                                                echo '<option ' . $curr_sel . ' value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php } //else { ?>
                                            <tr>
                                                <td><label
                                                        for="owner_name"><?php echo $acc_for_get == 'bank' ? $labels[1][2] : $labels[0][2]; ?></label>
                                                </td>
                                                <td>
                                                    <input type="text" id="owner_name" name="owner_name"
                                                           class="form-control"
                                                           value="<?php echo $record['owner_name']; ?>"></td>
                                            </tr>
                                            <?php //} ?>
                                            <tr>
                                                <td><label for="details">Details</label>
                                                <td><textarea rows="1" id="details" name="details"
                                                              class="form-control"><?php echo $record['details']; ?></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-5">
                                        <table class="table table-borderless mb-0 table-pb" id="contactsTable">
                                            <tbody>
                                            <?php $arrayNumber = 0;
                                            $x = 1;
                                            $indexes = json_decode($record["indexes"]);
                                            $vals = json_decode($record["vals"]);
                                            if (!empty($indexes)) {
                                                foreach ($indexes as $index => $value) {
                                                    //for ($x = 1; $x < 2; $x++) { ?>
                                                    <tr id="contact_row<?php echo $x; ?>"
                                                        class="<?php echo $arrayNumber; ?>">
                                                        <td>
                                                            <select id="contact_indexes<?php echo $x; ?>"
                                                                    name="indexes[]" class="form-select">
                                                                <?php $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                                                while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                                    $index_select = $static_type['type_name'] == $indexes[$index] ? 'selected' : '';
                                                                    echo '<option ' . $index_select . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input value="<?php echo $vals[$index]; ?>" type="text"
                                                                   name="vals[]" required
                                                                   placeholder="Value <?php echo $x; ?>"
                                                                   class="form-control"
                                                                   id="contact_vals<?php echo $x; ?>">
                                                        </td>
                                                        <td>
                                                            <span id="removeContactRow"
                                                                  class="btn btn-link text-danger p-1"
                                                                  onclick="removeContactRow(<?php echo $x; ?>)">Remove</span>
                                                        </td>
                                                    </tr>
                                                    <?php $arrayNumber++;
                                                    $x++;
                                                }
                                            } ?>
                                            </tbody>
                                        </table>
                                        <br><span class="btn btn-light btn-sm" onclick="addContactRow()"
                                                  id="addContactRow" data-loading-text="Loading...">+ Add line</span>
                                    </div>
                                    <?php if ($acc_for_get == "agent") { ?>
                                        <div class="col-md-auto">
                                            <table class="table table-borderless table-pb">
                                                <tr>
                                                    <td><label class="line-height-1" for="is_user">Also
                                                            user?</label>
                                                    <td>
                                                        <div class="form-check ">
                                                            <input type="checkbox" class="form-check-input"
                                                                   id="is_user" name="is_user"
                                                                   value="1" <?php echo $also_user_checked; ?>>
                                                            <label class="form-check-label" for="is_user">
                                                                Make Account
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="toggleDiv" style="display: none;">
                                                    <td><label class="line-height-1" for="username">User ID</label>
                                                    <td><input type="text" id="username" name="username"
                                                               class="form-control"
                                                               value="<?php echo $userID; ?>">
                                                    </td>
                                                </tr>
                                                <tr class="toggleDiv" style="display: none;">
                                                    <td><label class="line-height-1" for="password">Password</label>
                                                    <td><input type="text" id="password" name="password"
                                                               class="form-control"
                                                               value="<?php echo $userPASS; ?>">
                                                    </td>
                                                </tr>
                                                <tr class="toggleDiv" style="display: none;">
                                                    <td><label class="line-height-1" for="role">Role</label>
                                                    <td>
                                                        <select class="form-select" name="role" id="role">
                                                            <!--<option hidden value="">Select</option>-->
                                                            <?php $roles = fetch('roles', array('role_name' => 'agent'));
                                                            while ($role = mysqli_fetch_assoc($roles)) {
                                                                $roleSelected = $role['role_name'] == $userROLE ? 'selected' : '';
                                                                echo '<option ' . $roleSelected . ' value="' . $role['role_name'] . '">' . ucfirst($role['role_name']) . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                                <input type="hidden" value="<?php echo $record["id"]; ?>" name="hidden_id">
                                <button name="recordUpdate" type="submit" class="btn btn-dark btn-sm">Update Account
                                </button>
                            </form>
                        </div>
                    </div>
                <?php }
                if ($newRecord) { ?>
                    <form method="post" onsubmit="return confirm('Are you sure to save data?');"
                          enctype="multipart/form-data" class="table-form">
                        <div class="row table-form">
                            <div class="col-md-10">
                                <div class="d-flex gap-md-2 align-items-center justify-content-between mb-3">
                                    <div class="input-group">
                                        <label for="sr_no">G A/C.#</label>
                                        <input type="text" id="sr_no" class="form-control" disabled
                                               value="<?php echo getAutoIncrement('khaata'); ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="branch_id">BRANCH</label>
                                        <select id="branch_id" name="branch_id" class="form-select branch_id" required>
                                            <option hidden value="">Select</option>
                                            <?php $branches = fetch('branches');
                                            while ($branch = mysqli_fetch_assoc($branches)) {
                                                echo '<option value="' . $branch['id'] . '">' . $branch['b_name'] . '</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="cat_id">CATEGORY</label>
                                        <select class="form-select cat_id" name="cat_id" id="cat_id" required>
                                            <option hidden value="">Select</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <?php if ($acc_for_get == "client" || $acc_for_get == "agent") { ?>
                                            <label for="khaata_date">DATE</label>
                                            <input id="khaata_date" name="khaata_date" class="form-control"
                                                   value="<?php echo date('Y-m-d'); ?>" type="date">
                                        <?php }
                                        if ($acc_for_get == "bank") { ?>
                                            <label for="khaata_date_b">Date</label>
                                            <input id="khaata_date_b" name="khaata_date"
                                                   value="<?php echo date('Y-m-d'); ?>" type="date"
                                                   class="form-control">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row gx-5">
                                    <?php
                                    //if ($acc_for_get == "client" || $acc_for_get == "agent") { ?>
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-pb">
                                            <tr>
                                                <td><label for="khaata_no">A/C No.</label></td>
                                                <td><input type="text" id="khaata_no" name="khaata_no"
                                                           class="form-control" required></td>
                                            </tr>
                                            <tr>
                                                <td><label
                                                        for="khaata_name"><?php echo $acc_for_get == 'bank' ? $labels[1][0] : $labels[0][0]; ?></label>
                                                </td>
                                                <td><input type="text" id="khaata_name" name="khaata_name"
                                                           class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td><label class="line-height-1" for="comp_name">Company<br>Name</label>
                                                </td>
                                                <td><input type="text" id="comp_name" name="comp_name"
                                                           class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td><label class="line-height-1"
                                                           for="business_name"><?php echo $acc_for_get == 'bank' ? $labels[1][1] : $labels[0][1]; ?></label>
                                                </td>
                                                <td><input type="text" id="business_name" name="business_name"
                                                           class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td><label for="choices-single-default"
                                                           class="form-label">COUNTRY</label></td>
                                                <td>
                                                    <select class="form-select" data-trigger name="country_id"
                                                            id="choices-single-default" required>
                                                        <option value="" disabled selected>Select</option>
                                                        <?php $countries = fetch('countries');
                                                        while ($country = mysqli_fetch_assoc($countries)) {
                                                            echo '<option value="' . $country['id'] . '">' . $country['name'] . '</option>';
                                                        } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label for="city">City</label></td>
                                                <td><input type="text" id="city" name="city" class="form-control">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label class="line-height-1" for="address">Address</label></td>
                                                <td><textarea id="address" name="address"
                                                              class="form-control"></textarea></td>
                                            </tr>
                                            <?php if ($acc_for_get == "bank") { ?>
                                                <tr>
                                                    <td><label for="currency_b">Currency</label></td>
                                                    <td>
                                                        <select id="currency_b" name="currency" class="form-select"
                                                                required>
                                                            <option hidden value="">Select</option>
                                                            <?php $currencies = fetch('currencies');
                                                            while ($crr = mysqli_fetch_assoc($currencies)) {
                                                                echo '<option value="' . $crr['name'] . '">' . $crr['name'] . ' - ' . $crr['symbol'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                            <?php } //else { ?>
                                            <tr>
                                                <td><label
                                                        for="owner_name"><?php echo $acc_for_get == 'bank' ? $labels[1][2] : $labels[0][2]; ?></label></label>
                                                </td>
                                                <td><input type="text" id="owner_name" name="owner_name"
                                                           class="form-control"></td>
                                            </tr>
                                            <?php //} ?>
                                            <tr>
                                                <td><label for="details">Details</label>
                                                <td><textarea id="details" name="details"
                                                              class="form-control"></textarea></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless mb-0 table-pb" id="contactsTable">
                                            <tbody>
                                            <?php $arrayNumber = 0;
                                            for ($x = 1; $x < 2; $x++) { ?>
                                                <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                    <td>
                                                        <select id="indexes<?php echo $x; ?>" name="indexes[]"
                                                                class="form-select">
                                                            <?php $static_types = fetch('static_types', array('type_for' => 'contacts'));
                                                            while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                                echo '<option value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="vals[]" required
                                                               placeholder="Value <?php echo $x; ?>"
                                                               class="form-control" id="vals<?php echo $x; ?>">
                                                    </td>
                                                    <td>
                                                        <span id="removeContactRow" class="btn btn-link text-danger p-1"
                                                              onclick="removeProductRow(<?php echo $x; ?>)">Remove</span>
                                                    </td>
                                                </tr>
                                                <?php $arrayNumber++;
                                            } ?>
                                            </tbody>
                                        </table>
                                        <br><span class="btn btn-light btn-sm" onclick="addContactRow()"
                                                  id="addContactRow"
                                                  data-loading-text="Loading...">+ Add line</span>
                                    </div>
                                    <?php //} ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" value="<?php echo $infoArray['acc_for']; ?>" name="acc_for">
                                <input type="file" class="dropify" name="user_image" required
                                       data-default-file="assets/images/avatar.jpg"/>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button name="recordSubmit" type="submit" class="btn btn-primary w-md">Submit
                            </button>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <?php if ($updateRecord) { ?>
            <div style="min-height: 300px;">
                <?php if ($add_update_details) { ?>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" class="table-form">
                                <?php //$kd_back = $action == 'add' ? $pageURLBack : $pageURL;
                                $kd_back = $pageURL;
                                $kd_static_type = '';
                                $action_is_add = true;
                                $action_is_update = false;
                                if ($action == 'update') {
                                    $action_is_add = false;
                                    $action_is_update = true;
                                    $kd_id = mysqli_real_escape_string($connect, $_GET['kd_id']);
                                    $khaata_details = fetch('khaata_details', array('id' => $kd_id));
                                    $kd_data = mysqli_fetch_assoc($khaata_details);
                                    $indexes = json_decode($kd_data["indexes"]);
                                    $vals = json_decode($kd_data["vals"]);
                                    $kd_static_type = $kd_data['static_type'];
                                    echo '<input name="action" type="hidden" value="update">';
                                    echo '<input name="kd_id_hidden" type="hidden" value="' . $kd_id . '">';
                                }
                                echo '<input name="khaata_id_hidden" type="hidden" value="' . $id . '">';
                                echo '<input name="acc_for_hidden" type="hidden" value="' . $acc_for_get . '">'; ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <label for="static_type" class="bold">Account Type</label>
                                            <select class="form-select bg-light bold" id="static_type"
                                                    name="static_type" required>
                                                <?php $static_types = fetch('static_types', array('type_for' => 'khaata'));
                                                while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                    $account_type_selected = $static_type['type_name'] == $kd_static_type ? 'selected' : '';
                                                    echo '<option ' . $account_type_selected . ' value="' . $static_type['type_name'] . '" 
                                                    data-kid="' . $id . '" data-kdid="' . $kd_id . '" data-action="' . $action . '" >' . $static_type['details'] . '</option>';
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-7"></div>
                                    <div class="col-md-2 text-end">
                                        <?php echo '<a href="' . $kd_back . '" class="btn btn-danger btn-sm "><i class="fa fa-window-close"></i> Cancel</a>'; ?>
                                    </div>
                                </div>
                                <div class="row gx-1 gy-2 mt-2" id="kd-inputs"></div>
                                <div class="row mt-2">
                                    <div class="col-auto">
                                        <label class="fw-bold">CONTACTS</label>
                                    </div>
                                    <div class="col-8">
                                        <table class="table table-borderless mb-0" id="khaataDetailsTable">
                                            <tbody>
                                            <?php if ($action_is_update) {
                                                $arrayNumber = 0;
                                                $x = 1;
                                                if (!empty($indexes)) {
                                                    foreach ($indexes as $index => $value) { ?>
                                                        <tr id="row<?php echo $x; ?>"
                                                            class="<?php echo $arrayNumber; ?>">
                                                            <td>
                                                                <select id="indexes<?php echo $x; ?>" name="indexes[]"
                                                                        class="form-select">
                                                                    <?php $static_types = mysqli_query($connect, "SELECT * FROM `static_types` WHERE type_for = 'khaata_details' OR type_for = 'contacts'");
                                                                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                                        $index_select = $static_type['type_name'] == $indexes[$index] ? 'selected' : '';
                                                                        echo '<option ' . $index_select . ' value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                                    } ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input value="<?php echo $vals[$index]; ?>" type="text"
                                                                       name="vals[]" required
                                                                       placeholder="Value <?php echo $x; ?>"
                                                                       class="form-control" id="vals<?php echo $x; ?>">
                                                            </td>
                                                            <td>
                                                            <span id="removeProductRowBtn"
                                                                  class="btn btn-link text-danger p-1"
                                                                  onclick="removeProductRow(<?php echo $x; ?>)">Remove</span>
                                                            </td>
                                                        </tr>
                                                        <?php $arrayNumber++;
                                                        $x++;
                                                    }
                                                }
                                            } else {
                                                for ($x = 1; $x < 2; $x++) { ?>
                                                    <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                                                        <td>
                                                            <select id="indexes<?php echo $x; ?>" name="indexes[]"
                                                                    class="form-select">
                                                                <?php //$static_types = fetch('static_types', array('type_for' => 'khaata_details','type_for' => 'contacts'));
                                                                $static_types = mysqli_query($connect, "SELECT * FROM `static_types` WHERE type_for = 'khaata_details' OR type_for = 'contacts'");
                                                                while ($static_type = mysqli_fetch_assoc($static_types)) {
                                                                    echo '<option value="' . $static_type['type_name'] . '">' . $static_type['details'] . '</option>';
                                                                } ?>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="vals[]" required
                                                                   placeholder="Value <?php echo $x; ?>"
                                                                   class="form-control" id="vals<?php echo $x; ?>">
                                                        </td>
                                                        <td>
                                                            <span id="removeProductRowBtn"
                                                                  class="btn btn-link text-danger p-1"
                                                                  onclick="removeProductRow(<?php echo $x; ?>)">DELETE</span>
                                                        </td>
                                                    </tr>
                                                    <?php $arrayNumber++;
                                                }
                                            } ?>
                                            </tbody>
                                        </table>
                                        <span class="btn btn-light btn-sm" onclick="addRow()" id="addRowBtn"
                                              data-loading-text="Loading...">+ Add line</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button name="accDetailsSubmit" id="accDetailsSubmit"
                                            class="btn btn-success btn-sm">Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } else {
                    if ($acc_for_get != 'bank') { // only Client & Agnet can have these detials
                        $head1 = array('Type', 'Company', 'Mobile', 'Email', 'City', 'Address', 'NTN', 'Date', 'Sale Tax#', 'Consignee', 'Reebok Id', 'Passport', 'Report'); ?>
                        <?php $noo = 1;
                        $hide_items_array = array('static_type', 'khaata_id_hidden', 'acc_for_hidden'); ?>
                        <div class="d-flex align-items-center justify-content-between">
                            <ul class="nav nav-tabs" role="tablist">
                                <?php $static_types1 = fetch('static_types', array('type_for' => 'khaata'));
                                while ($static_type = mysqli_fetch_assoc($static_types1)) {
                                    $active_link = $static_type['type_name'] == "Extra" ? 'active' : ''; ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo $active_link; ?>" data-bs-toggle="tab"
                                           href="#<?php echo $static_type['type_name']; ?>" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                            <span
                                                class="d-none d-sm-block"><?php echo $static_type['details']; ?></span>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                            <?php echo '<a href="' . $pageURL . '&action=add" class="btn btn-dark btn-sm">Add New Details</a>'; ?>
                        </div>
                        <div class="tab-content mb-5">
                            <?php $static_types2 = fetch('static_types', array('type_for' => 'khaata'));
                            while ($static_type = mysqli_fetch_assoc($static_types2)) {
                                $active_pane = $static_type['type_name'] == "Extra" ? 'active' : ''; ?>
                                <div class="tab-pane <?php echo $active_pane; ?>"
                                     id="<?php echo $static_type['type_name']; ?>" role="tabpanel">
                                    <?php $khaata_details = fetch('khaata_details', array('khaata_id' => $id, 'is_active' => 1, 'static_type' => $static_type['type_name']));
                                    $x = 1;
                                    while ($details = mysqli_fetch_assoc($khaata_details)) { ?>
                                        <div class="card mb-2">
                                            <div class="card-header">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 class="card-title text-primary"><?php echo $x . '.'; ?></h5>
                                                    <div class="d-flex">
                                                        <a href="?id=<?php echo $id; ?>&kd_id=<?php echo $details['id']; ?>&acc_for=<?php echo $acc_for_get; ?>&is_delete=1"
                                                           class="btn btn-sm btn-danger me-4"
                                                           onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                                        <a href="<?php echo $pageURL . '&kd_id=' . $details['id']; ?>&action=update"
                                                           class="btn btn-sm btn-primary">Update</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-1">
                                                <div class="row gx-0 text-uppercase fw-bold text-dark small">
                                                    <?php if ($static_type['type_name'] == "Bank") {
                                                        $keys = array_keys($bbb);
                                                        echo '<div class="col-md-3">';
                                                        echo 'BANK NAME<span class="fw-normal">' . $details[$keys[3]] . '</span><br>';
                                                        echo 'A/C TITLE<span class="fw-normal">' . $details[$keys[0]] . '</span><br>';
                                                        echo 'A/C NO<span class="fw-normal">' . $details[$keys[1]] . '</span>';
                                                        echo '</div>';
                                                        echo '<div class="col-md-3">';
                                                        echo 'ADDRESS<span class="fw-normal">' . $details[$keys[7]] . '</span><br>';
                                                        echo 'COUNTRY<span class="fw-normal">' . countryName($details[$keys[5]]) . '</span><br>';
                                                        echo 'CITY<span class="fw-normal">' . $details[$keys[6]] . '</span>';
                                                        echo '</div>';
                                                        echo '<div class="col-md-3">';
                                                        echo 'OWNER NAME<span class="fw-normal">' . $details[$keys[2]] . '</span><br>';
                                                        echo 'IFSC CODE<span class="fw-normal">' . $details[$keys[4]] . '</span><br>';
                                                        echo 'REPORT<span class="fw-normal">' . $details[$keys[8]] . '</span>';
                                                        echo '</div>';
                                                        $row_cols = 'row-cols-2';
                                                        $col_md = 'col-md-3';
                                                    } else {
                                                        $row_cols = 'row-cols-3';
                                                        $col_md = 'col-md-6';
                                                        $keys = array_keys($aaa);
                                                        echo '<div class="col-md-3">';
                                                        echo 'NAME<span class="fw-normal">' . $details[$keys[0]] . '</span><br>';
                                                        echo 'COUNTRY<span class="fw-normal">' . countryName($details[$keys[1]]) . '</span><br>';
                                                        echo 'CITY<span class="fw-normal">' . $details[$keys[2]] . '</span>';
                                                        echo '</div>';
                                                        echo '<div class="col-md-3">';
                                                        echo 'ADDRESS<span class="fw-normal">' . $details[$keys[3]] . '</span><br>';
                                                        echo 'REPORT<span class="fw-normal">' . $details[$keys[4]] . '</span>';
                                                        echo '</div>';
                                                    }
                                                    echo '<div class="' . $col_md . '"><div class="row gx-0 ' . $row_cols . '">';
                                                    $details = ['indexes' => $details['indexes'], 'vals' => $details['vals']];
                                                    displayKhaataDetails($details);
                                                    echo '</div></div>'; ?>
                                                </div>
                                            </div>

                                        </div>
                                        <?php $x++;
                                    } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php }
                } ?>
            </div>
        <?php } ?>
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
<script src="assets/js/input-repeator-khaata-details.js"></script>
<script src="assets/js/input-repeator-contacts.js"></script>
<script>
    $(function () {
        $('.acc_for:not(:checked)').on('change', function () {
            let acc_for = this.value;
            //$("#acc_for_hidden").val(acc_for);
            let url = '';
            window.$_GET = location.search.substr(1).split("&").reduce((o, i) => (u = decodeURIComponent, [k, v] = i.split("="), o[u(k)] = v && u(v), o), {});
            if (acc_for) {
                url = "khaata-add?acc_for=" + acc_for;
                if ($_GET.hasOwnProperty('id')) {
                    url += '&id=' + $_GET.id;
                }
                window.location = url;
            }
            return false;
        });
        //$('.acc_for:checked').trigger('change');
    });
</script>
<!--<script>$(document).ready(function () { smoothScrollToBottom(1000);});</script>-->
<script type="text/javascript">
    $(document).ready(function () {
        function toggleContainers() {
            var st = $("#static_type");
            var staticType = st.val();
            var kid = st.find(':selected').data('kid')
            var kdid = st.find(':selected').data('kdid')
            var action = st.find(':selected').data('action')
            if (staticType != "") {
                $.ajax({
                    url: 'ajax/fetchInputsOfKhaataDetails.php',
                    type: 'post',
                    data: {staticType: staticType, kid: kid, kdid: kdid, action: action},
                    //dataType: 'json',
                    success: function (response) {
                        //console.log(response);
                        $('#kd-inputs').html(response);
                    }
                });
            } else {
                alert('error!! Refresh the page again');
            }
            //$("#first-container, #second-container, #third-container, #accDetailsSubmit").hide();
            //$("#cloning_div").hide();
            /*if (staticType != '') {
                if (staticType === 'Bank') {
                    //$("#cloning_div").hide();
                    $("#first-container, #third-container").hide();
                    $("#second-container").show();
                } else if (staticType === 'Warehouse') {
                    //$("#cloning_div").hide();
                    $("#first-container, #second-container").hide();
                    $("#third-container").show();
                } else {
                    $("#second-container, #third-container").hide();
                    $("#first-container").show();
                    //$("#cloning_div").show();
                }
                $("#accDetailsSubmit").show();
            }*/
        }

        toggleContainers();
        $("#static_type").change(function () {
            toggleContainers();
        });
    });
</script>
<?php $inputsNams = array('name', 'mobile', 'email', 'city', 'comp_name', 'comp_mobile', 'comp_email', 'comp_city', 'comp_address', 'ntn', 'date', 'details');
if (isset($_POST['recordSubmit'])) {
    $url = "khaata-add";
    if ($_FILES['user_image']['error'] == 4 || ($_FILES['user_image']['size'] == 0 && $_FILES['user_image']['error'] == 0)) {
        $path = '';
    } else {
        $image = $_FILES['user_image']['name'];
        $path = "uploads/" . $image;
        $moved = move_uploaded_file($_FILES['user_image']['tmp_name'], $path);
    }
    $acc_for = mysqli_real_escape_string($connect, $_POST['acc_for']);
    $data = array(
        'acc_for' => $acc_for,
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'cat_id' => $_POST['cat_id'],
        'branch_id' => $_POST['branch_id'],
        'khaata_date' => $_POST['khaata_date'],
        'khaata_name' => mysqli_real_escape_string($connect, $_POST['khaata_name']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'business_name' => mysqli_real_escape_string($connect, $_POST['business_name']),
        'country_id' => mysqli_real_escape_string($connect, $_POST['country_id']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'owner_name' => mysqli_real_escape_string($connect, $_POST['owner_name']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'image' => $path,
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $userId
    );
    if ($acc_for == 'bank') {
        $data['currency'] = mysqli_real_escape_string($connect, $_POST['currency']);
    }
    if (isset($_POST['indexes'])) {
        $data['indexes'] = json_encode($_POST['indexes']);
        $data['vals'] = json_encode($_POST['vals']);
    }
    $str = '';
    $done = insert('khaata', $data);
    if ($done) {
        $url .= '?id=' . $connect->insert_id . '&acc_for=' . $acc_for;
        message('success', $url, $acc_for . ' Account Saved.', 'A/c', $_POST['khaata_no'], '', $str);
    } else {
        message('danger', $url, 'Database Problem');
    }
}
if (isset($_POST['recordUpdate'])) {
    $msg = 'DB Error';
    $msgType = 'danger';
    $hidden_id = $_POST['hidden_id'];
    $acc_for = mysqli_real_escape_string($connect, $_POST['acc_for']);
    $url = "khaata-add?id=" . $hidden_id . '&acc_for=' . $acc_for;
    $is_user = isset($_POST['is_user']) ? 1 : 0;
    $data = array(
        'acc_for' => $acc_for,
        'khaata_no' => mysqli_real_escape_string($connect, $_POST['khaata_no']),
        'is_user' => $is_user,
        'cat_id' => $_POST['cat_id'],
        'branch_id' => $_POST['branch_id'],
        'khaata_date' => $_POST['khaata_date'],
        'khaata_name' => mysqli_real_escape_string($connect, $_POST['khaata_name']),
        'comp_name' => mysqli_real_escape_string($connect, $_POST['comp_name']),
        'business_name' => mysqli_real_escape_string($connect, $_POST['business_name']),
        'country_id' => mysqli_real_escape_string($connect, $_POST['country_id']),
        'city' => mysqli_real_escape_string($connect, $_POST['city']),
        'address' => mysqli_real_escape_string($connect, $_POST['address']),
        'owner_name' => mysqli_real_escape_string($connect, $_POST['owner_name']),
        'details' => mysqli_real_escape_string($connect, $_POST['details']),
        'updated_at' => date('Y-m-d H:i:s'),
        'updated_by' => $userId
    );
    if ($acc_for == 'bank') {
        $data['currency'] = mysqli_real_escape_string($connect, $_POST['currency']);
    }
    if (isset($_POST['indexes'])) {
        $data['indexes'] = json_encode($_POST['indexes']);
        $data['vals'] = json_encode($_POST['vals']);
    }
    $str = '';
    $done = update('khaata', $data, array('id' => $hidden_id));
    if ($done) {
        if ($is_user == 1) {
            $data_user = array(
                'khaata_id' => $hidden_id,
                'username' => mysqli_real_escape_string($connect, $_POST['username']),
                'pass' => mysqli_real_escape_string($connect, $_POST['password']),
                'role' => mysqli_real_escape_string($connect, $_POST['role']),
                'full_name' => mysqli_real_escape_string($connect, $_POST['khaata_name']),
                'email' => mysqli_real_escape_string($connect, $_POST['email']),
                'father_name' => mysqli_real_escape_string($connect, $_POST['father_name']),
                'user_date' => date('Y-m-d'),
                'mobile' => mysqli_real_escape_string($connect, $_POST['mobile']),
                'phone' => mysqli_real_escape_string($connect, $_POST['phone']),
                'branch_id' => $_POST['branch_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $userId
            );
            $qqqqq = fetch('users', array('khaata_id' => $hidden_id));
            //$khaata_user_data = mysqli_fetch_assoc($qqqqq);
            if (mysqli_num_rows($qqqqq) > 0) {
                $done2 = update('users', $data_user, array('khaata_id' => $hidden_id));
                $str .= ' and Login info updated.';
            } else {
                $done2 = insert('users', $data_user);
                $str .= ' Also, login account has been created.';
            }
        }
        $msg = $acc_for . 'Account updated. ' . $str;
        $msgType = 'info';
    }
    message($msgType, $url, $msg);
}
if (isset($_POST['deleteKhaataSubmit'])) {
    $khaata_id_hidden = mysqli_real_escape_string($connect, $_POST['khaata_id_hidden']);
    $khaata_no_hidden = mysqli_real_escape_string($connect, $_POST['khaata_no_hidden']);
    $del = mysqli_query($connect, "DELETE FROM khaata WHERE id = '$khaata_id_hidden'");
    if ($del) {
        message('success', 'khaata', 'A/c. ' . $khaata_no_hidden . ' deleted successfully.');
    } else {
        message('danger', 'khaata', 'A/c. ' . $khaata_no_hidden . ' not deleted.');
    }
}
if (isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['kd_id']) && is_numeric($_GET['kd_id']) && isset($_GET['acc_for']) && isset($_GET['is_delete']) && $_GET['is_delete'] == 1) {
    $id = mysqli_real_escape_string($connect, $_GET['id']);
    $kd_id = mysqli_real_escape_string($connect, $_GET['kd_id']);
    $acc_for = mysqli_real_escape_string($connect, $_GET['acc_for']);
    $del = mysqli_query($connect, "UPDATE `khaata_details` SET is_active= 0 WHERE id = '$kd_id'");
    if ($del) {
        message('success', $pageURL, ' deleted .');
    } else {
        message('danger', $pageURL, 'DB Error..');
    }
}
if (isset($_POST['accDetailsSubmit'])) {
    $msg = "DB Error";
    $type = "danger";
    $khaata_id_hidden = mysqli_real_escape_string($connect, $_POST['khaata_id_hidden']);
    $static_type = mysqli_real_escape_string($connect, $_POST['static_type']);
    $acc_for_hidden = mysqli_real_escape_string($connect, $_POST['acc_for_hidden']);
    $url = "khaata-add?id=" . $khaata_id_hidden . "&acc_for=" . $acc_for_hidden;
    $data = array('khaata_id' => $khaata_id_hidden, 'static_type' => $static_type);
    $temp = $aaa;
    if (isset($_POST['indexes'])) {
        $data['indexes'] = json_encode($_POST['indexes']);
        $data['vals'] = json_encode($_POST['vals']);
    }
    if ($static_type == 'Bank') {
        $temp = $bbb;
    }
    if ($static_type == 'Warehouse') {
        $temp = $ccc;
    }
    foreach ($temp as $index => $value) {
        $data[$index] = mysqli_real_escape_string($connect, $_POST[$value]);
    }

    if (isset($_POST['action']) && $_POST['action'] == "update" && isset($_POST['kd_id_hidden'])) {
        $kd_id_hidden = mysqli_real_escape_string($connect, $_POST['kd_id_hidden']);
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userId;
        $done = update('khaata_details', $data, array('id' => $kd_id_hidden));
        $msg = "Acc. details Updated.";
        $type = "success";
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userId;
        $done = insert('khaata_details', $data);
        if ($done) {
            $msg = "Acc. details saved successfully.";
            $type = "success";
        }
    }
    message($type, $url, $msg);
} ?>
<script>
    document.getElementById("dropify").onchange = function () {
        document.getElementById("picUpload").submit();
    }
</script>
<script>
    $(document).ready(function () {
        var $toggleCheckbox = $("#is_user");
        var $toggleDiv = $(".toggleDiv");

        // Function to toggle the div and set 'required' attributes
        function toggleDivAndRequired() {
            if ($toggleCheckbox.is(":checked")) {
                $toggleDiv.show();
                $("#username, #password, #role").attr('required', true);
            } else {
                $toggleDiv.hide();
                $("#username, #password, #role").attr('required', false);
            }
        }

        // Trigger the change event if the checkbox is already checked
        toggleDivAndRequired();

        // Add a change event handler to the checkbox
        $toggleCheckbox.change(toggleDivAndRequired);
    });
</script>
