<?php
include('../connection.php');

$response = [
    'sizes' => [],
    'brands' => [],
    'origins' => []
];

if (isset($_POST["goods_id"])) {
    $goods_id = intval($_POST['goods_id']);

    // Fetch distinct sizes for the selected goods_id
    $sizeQuery = mysqli_query($connect, "SELECT DISTINCT size FROM good_details WHERE goods_id = '$goods_id'");
    if (mysqli_num_rows($sizeQuery) > 0) {
        $response['sizes'][] = 'ALL SIZE'; // Optional default value
        while ($row = mysqli_fetch_assoc($sizeQuery)) {
            $response['sizes'][] = $row['size'];
        }
    }

    // Fetch distinct brands for the selected goods_id
    $brandQuery = mysqli_query($connect, "SELECT DISTINCT brand FROM good_details WHERE goods_id = '$goods_id'");
    if (mysqli_num_rows($brandQuery) > 0) {
        while ($row = mysqli_fetch_assoc($brandQuery)) {
            $response['brands'][] = $row['brand'];
        }
    }

    // Fetch distinct origins for the selected goods_id
    $originQuery = mysqli_query($connect, "SELECT DISTINCT origin FROM good_details WHERE goods_id = '$goods_id'");
    if (mysqli_num_rows($originQuery) > 0) {
        while ($row = mysqli_fetch_assoc($originQuery)) {
            $response['origins'][] = $row['origin'];
        }
    }
}

// Return the response in JSON format
echo json_encode($response);
?>
