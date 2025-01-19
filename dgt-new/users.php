<?php
$page_title = 'Users List';
$pageURL = 'users';
include("header.php");

// Check if the user has SuperAdmin privileges
if (!SuperAdmin()) {
    messageNew('danger', './', 'ACCESS DENIED.');
}

// Build the SQL query to fetch users
$sql = "SELECT * FROM `users` WHERE id > 0";
$queryParams = []; // To store query parameters for print_url

if (isset($_GET['role']) && (!empty($_GET['role']) && $_GET['role'] != '0')) {
    $type = mysqli_real_escape_string($connect, $_GET['role']);
    $sql .= " AND role = '$type'";
    $queryParams['role'] = $type; // Add to query parameters
}

if (isset($_GET['name']) && !empty($_GET['name'])) {
    $name = mysqli_real_escape_string($connect, $_GET['name']);
    $sql .= " AND full_name LIKE '%$name%'";
    $queryParams['name'] = $name; // Add to query parameters
}

// Generate the print_url based on the applied filters
$print_url = 'print/' . $pageURL;
if (!empty($queryParams)) {
    $print_url .= '?' . http_build_query($queryParams);
}
?>

<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
    <div class="bg-light shadow p-2 border-bottom border-warning d-flex align-items-center justify-content-between mb-md-2">
        <div class="fs-5 text-uppercase">Users List</div>
        <form method="get" class="d-flex gap-3">
            <select class="form-select form-select-sm" name="role" id="role" required>
                <option value="0">All Roles</option>
                <?php
                $roles = fetch('roles');
                while ($role = mysqli_fetch_assoc($roles)) {
                    $selected = (isset($type) && $type === $role['role_name']) ? 'selected' : '';
                    echo "<option value='{$role['role_name']}' $selected>{$role['role_name']}</option>";
                }
                ?>
            </select>
            <input type="text" class="form-control form-control-sm" name="name" placeholder="Filter by Name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>">
            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
        </form>
        <div class="d-flex gap-2">
            <?php // echo searchInput('a', 'form-control-sm'); 
            ?>
            <?php echo addNew('user-add', '', 'btn-sm btn-success'); ?>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="<?= $print_url; ?>" target="_blank">
                            <i class="fas text-secondary fa-eye me-2"></i> Print Preview
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')">
                            <i class="fas text-secondary fa-print me-2"></i> Print
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')">
                            <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')">
                            <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')">
                            <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')">
                            <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Date</th>
                            <th>ID Type</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Branch</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $users = mysqli_query($connect, $sql);
                        $no = 0;
                        while ($user = mysqli_fetch_assoc($users)) {
                            $is_active = $user['is_active'];
                            $status_icon = $is_active ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-ban text-danger" title="Blocked"></i>';
                            $img_path = (!empty($user['image']) && file_exists($user['image'])) ? $user['image'] : 'assets/images/avatar.jpg';
                            $no++;
                        ?>
                            <tr>
                                <td><?php echo $no; ?></td>
                                <td><img src="<?php echo $img_path; ?>" class="img-fluid rounded-circle" width="40" alt="Avatar"></td>
                                <td><?php echo my_date($user['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($user['type']); ?></td>
                                <td><a href="user-add?id=<?php echo $user['id']; ?>"> <?php echo htmlspecialchars($user['full_name']); ?> </a></td>
                                <td><?php echo badge(strtoupper($user['role']), 'dark'); ?></td>
                                <td><?php echo branchName($user['branch_id']); ?></td>
                                <td><?php echo $status_icon . ' ' . htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['pass']); ?></td>
                                <td class="d-flex gap-2">
                                    <?php if (SuperAdmin()) { ?>
                                        <form method="post" onsubmit="return confirm('Are you sure to delete?');">
                                            <input type="hidden" name="hidden_id" value="<?php echo $user['id']; ?>">
                                            <button name="deleteSubmit" type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash-alt"></i></button>
                                        </form>
                                        <form method="post" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="hidden_id" value="<?php echo $user['id']; ?>">
                                            <input type="hidden" name="hidden_status" value="<?php echo $is_active; ?>">
                                            <button name="blockSubmit" type="submit" class="btn btn-sm btn-secondary">
                                                <i class="fa fa-lock"></i>
                                            </button>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
    document.getElementById('role').addEventListener('change', function() {
        const selectedRole = this.value;
        const baseUrl = '<?php echo $pageURL; ?>';
        window.location.href = selectedRole !== '0' ? `${baseUrl}?role=${selectedRole}` : baseUrl;
    });
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['deleteSubmit'])) {
        handleDeleteUser($connect, $_POST['hidden_id']);
    } elseif (isset($_POST['blockSubmit'])) {
        handleBlockUnblockUser($connect, $_POST['hidden_id'], $_POST['hidden_status']);
    }
}

function handleDeleteUser($connect, $userId)
{
    global $pageURL;
    $userId = mysqli_real_escape_string($connect, $userId);
    mysqli_begin_transaction($connect);
    try {
        $dependentTables = ['khaata' => 'created_by', 'branches' => 'created_by'];
        foreach ($dependentTables as $table => $foreignKeyColumn) {
            $checkDependents = mysqli_query($connect, "SELECT COUNT(*) AS count FROM `$table` WHERE `$foreignKeyColumn` = '$userId'");
            $dependentCount = mysqli_fetch_assoc($checkDependents)['count'];
            if ($dependentCount > 0) {
                throw new Exception("Unable to delete the record due to dependencies in $table.");
            }
        }

        $deleteUser = mysqli_query($connect, "DELETE FROM `users` WHERE id = '$userId'");
        if (!$deleteUser) {
            throw new Exception("Failed to delete the user record.");
        }

        mysqli_commit($connect);
        messageNew('success', $pageURL, 'User successfully deleted.');
    } catch (Exception $e) {
        mysqli_rollback($connect);
        messageNew('danger', $pageURL, $e->getMessage());
    }
}

function handleBlockUnblockUser($connect, $userId, $currentStatus)
{
    global $pageURL;
    $userId = mysqli_real_escape_string($connect, $userId);
    $newStatus = $currentStatus == 1 ? 0 : 1;

    $updateStatus = mysqli_query($connect, "UPDATE `users` SET is_active = '$newStatus' WHERE id = '$userId'");
    if ($updateStatus) {
        messageNew('success', $pageURL, 'User status successfully updated.');
    } else {
        messageNew('danger', $pageURL, 'Failed to update user status.');
    }
}

?>