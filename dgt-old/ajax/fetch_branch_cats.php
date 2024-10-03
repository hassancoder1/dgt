<?php include('../connection.php');

if (isset($_POST["branch_id"])) {
    $branch_id = $_POST['branch_id'];
    $run_query = mysqli_query($connect, "SELECT * FROM cats WHERE branch_id = '$branch_id' ORDER BY name ASC");
    $count = mysqli_num_rows($run_query);
    if ($count > 0) {
        echo '<option selected disabled hidden value="">Choose</option>';
        while ($row = mysqli_fetch_array($run_query)) {
            echo '<option value=' . $row['id'] . '>' . $row['name'] . '</option>';
        }
    } else {
        echo '<option value="">No category</option>';
    }
} ?>