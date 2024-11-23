<?php require_once '../connection.php';
$valid['success'] = array('success' => false, 'json1' => array());
$id = $_POST['id'];
$maal2id = $_POST['maal2id'];
$form_name = $_POST['form_name'];
if ($maal2id > 0) {
    $sql = "SELECT dt_truck_maals.json_data as json1, dt_truck_maals2.json_data as json2
        FROM dt_truck_maals 
        LEFT JOIN dt_truck_maals2 ON dt_truck_maals.id = dt_truck_maals2.maal_id
        WHERE dt_truck_maals.id = '$id' AND dt_truck_maals2.form_name='$form_name'";
    $result = $connect->query($sql);
    if ($result->num_rows > 0) {
        $row1 = mysqli_fetch_assoc($result);
        $valid['json1'] = json_decode($row1['json1']);
        $valid['json2'] = json_decode($row1['json2']);
        $valid['success'] = true;
    } else {
        $valid['success'] = false;
        $valid['json1'] = "Error ..";
    }
} else {
    $sql = "SELECT json_data as json1 FROM dt_truck_maals WHERE id = '$id'";
    $result = $connect->query($sql);
    if ($result->num_rows > 0) {
        $row1 = mysqli_fetch_assoc($result);
        $valid['json1'] = json_decode($row1['json1']);
        $valid['success'] = true;
    } else {
        $valid['success'] = false;
        $valid['json1'] = "Error ..";
    }
}
$connect->close();

echo json_encode($valid, JSON_PRETTY_PRINT);