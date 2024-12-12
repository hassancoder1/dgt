<?php
include('../connection.php');

if (isset($_POST["l_id"])) {
    // Sanitize incoming POST data
    $goods_id = mysqli_real_escape_string($connect, $_POST['goods_id']);
    $size = mysqli_real_escape_string($connect, $_POST['size']);
    $brand = mysqli_real_escape_string($connect, $_POST['brand']);
    $origin = mysqli_real_escape_string($connect, $_POST['origin']);
    $quantity_name = mysqli_real_escape_string($connect, $_POST['quantity_name']);
    $warehouse = mysqli_real_escape_string($connect, $_POST['warehouse']);

    // Construct the query with JSON_SEARCH
    $query = "
        SELECT unique_code, ldata 
        FROM data_copies 
        WHERE 
            JSON_SEARCH(ldata, 'one', 'p') IS NOT NULL AND
            JSON_SEARCH(ldata, 'one', '$goods_id') IS NOT NULL AND
            JSON_SEARCH(ldata, 'one', '$size') IS NOT NULL AND
            JSON_SEARCH(ldata, 'one', '$brand') IS NOT NULL AND
            JSON_SEARCH(ldata, 'one', '$origin') IS NOT NULL AND
            JSON_SEARCH(ldata, 'one', '$quantity_name') IS NOT NULL AND
            JSON_SEARCH(ldata, 'one', '$warehouse') IS NOT NULL
    ";

    $result = mysqli_query($connect, $query);

    // Initialize an array to store the results grouped by unique_code
    $responseData = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ldata = json_decode($row['ldata'], true);
            $unique_code = $row['unique_code']; // Store unique_code to group by

            // Initialize the array for the current unique_code if not set
            if (!isset($responseData[$unique_code])) {
                $responseData[$unique_code] = [];
            }

            // Loop through dynamic keys in ldata
            foreach ($ldata as $key => $value) {
                if (preg_match('/^l_(\d+)_goods_id$/', $key, $matches)) {
                    $dynamic_id = $matches[1];
                    $sold_to_key = "l_{$dynamic_id}_sold_to";
                    if (isset($ldata[$sold_to_key])) {
                        continue;
                    }
                    $goods_key = "l_{$dynamic_id}_goods_id";
                    $size_key = "l_{$dynamic_id}_size";
                    $brand_key = "l_{$dynamic_id}_brand";
                    $origin_key = "l_{$dynamic_id}_origin";
                    $quantity_name_key = "l_{$dynamic_id}_quantity_name";
                    $quantity_no_key = "l_{$dynamic_id}_quantity_no";
                    $gross_weight_key = "l_{$dynamic_id}_gross_weight";
                    $net_weight_key = "l_{$dynamic_id}_net_weight";
                    $container_no_key = "l_{$dynamic_id}_container_no";
                    $container_name_key = "l_{$dynamic_id}_container_name";
                    $goodsValue = goodsName($value);
                    $responseData[$unique_code][] = [
                        'loadingID' => $dynamic_id,
                        'goods_id' => $value,
                        'goods_name' => $goodsValue,
                        'size' => $ldata[$size_key],
                        'brand' => $ldata[$brand_key],
                        'origin' => $ldata[$origin_key],
                        'quantity_name' => $ldata[$quantity_name_key],
                        'quantity_no' => $ldata[$quantity_no_key],
                        'gross_weight' => $ldata[$gross_weight_key],
                        'net_weight' => $ldata[$net_weight_key],
                        'container_no' => $ldata[$container_no_key] ?? '',
                        'container_name' => $ldata[$container_name_key] ?? '',
                    ];
                }
            }
        }
    }
    echo json_encode($responseData);
}
