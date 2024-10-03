<?php require_once '../connection.php';
global $connect;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $temp_id_hidden = $_POST['temp_id_hidden'];
        $jsonData = json_encode($_POST);
        $data = array('json_data' => $jsonData);
        if ($temp_id_hidden > 0) {
            $query = update('invoice_temp_data', $data,array('id'=>$temp_id_hidden));
        } else {
            $query = insert('invoice_temp_data', $data);
        }

        echo json_encode(['id' => $connect->insert_id]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
