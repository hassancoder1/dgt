<?php
$page_title = 'Print Users List';
include("../connection.php");

// Check SuperAdmin privileges
if (!SuperAdmin()) {
    die('ACCESS DENIED.');
}

// Build the SQL query based on filters
$sql = "SELECT * FROM `users` WHERE id > 0";

if (isset($_GET['role']) && !empty($_GET['role']) && $_GET['role'] != '0') {
    $type = mysqli_real_escape_string($connect, $_GET['role']);
    $sql .= " AND role = '$type'";
}

if (isset($_GET['name']) && !empty($_GET['name'])) {
    $name = mysqli_real_escape_string($connect, $_GET['name']);
    $sql .= " AND full_name LIKE '%$name%'";
}

$users = mysqli_query($connect, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 0;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Users List</h1>
        <p>Generated on: <?php echo date('Y-m-d H:i:s'); ?></p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Avatar</th>
                <th>Date</th>
                <th>ID Type</th>
                <th>Name</th>
                <th>Role</th>
                <th>Branch</th>
                <th>Username</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 0;
            while ($user = mysqli_fetch_assoc($users)) {
                $img_path = (!empty($user['image']) && file_exists('../' . $user['image'])) ? '../' . $user['image'] : '../assets/images/avatar.jpg';
                $no++;
            ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><img src="<?php echo $img_path; ?>" alt="Avatar" width="40" style="border-radius:100%;"></td>
                    <td><?php echo my_date($user['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($user['type']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo branchName($user['branch_id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>