<?php
require_once '../connection.php';

if (isset($_POST['t_type']) && isset($_POST['t_sr'])) {
    $t_type = $_POST['t_type'];
    $t_sr = $_POST['t_sr'];

    $sql = "SELECT id FROM `transactions` WHERE `p_s` = ? AND `sr` = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("ss", $t_type, $t_sr);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(array('id' => 0));
    }
}
?>
