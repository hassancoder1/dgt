<?php require_once '../connection.php';
global $connect;
if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];

        //$data = fetch('invoice_temp_data', array('id' => $id));
        $data = mysqli_query($connect, "SELECT json_data FROM invoice_temp_data WHERE id = '$id'");
        $datum = mysqli_fetch_assoc($data);
        echo json_encode($datum);
        //echo json_encode($datum['json_data']);
        //echo json_encode($row);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
