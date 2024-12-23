<?php
// Include the database connection
include('../connection.php');

// Check if the request has the "warehouse" parameter
if (isset($_POST["warehouse"])) {
    // Query to fetch relevant data
    $query = "
        SELECT unique_code, ldata 
        FROM data_copies 
        WHERE 
            unique_code LIKE 'p%' AND
            JSON_EXTRACT(ldata, '$.good.goods_json.qty_no') != 0;
    ";

    $fetch = mysqli_query($connect, $query);
    $responseData = [];

    if ($fetch && $fetch->num_rows > 0) {
        while ($row = $fetch->fetch_assoc()) {
            $ldata = json_decode($row['ldata'], true); // Decode JSON
            $unique_code = $row['unique_code']; // Unique code for grouping

            // Ensure the warehouse key exists and group by warehouse
            if (!empty($ldata['transfer']['warehouse_transfer'])) {
                $warehouse = $ldata['transfer']['warehouse_transfer'];

                if (!isset($responseData[$warehouse])) {
                    $responseData[$warehouse] = [];
                }

                if (!empty($ldata['good']['goods_json']['allotment_name'])) {
                    $responseData[$warehouse][] = [
                        "unique_code" => $row['unique_code'],
                        "p_id" => $ldata['p_id'],
                        "sr_no" => $ldata['sr_no'],
                        "allot" => $ldata['good']['goods_json']['allotment_name'],
                        'goods_id' => $ldata['good']['goods_id'],
                        'goods_name' => goodsName($ldata['good']['goods_id']),
                        'size' => $ldata['good']['size'],
                        'brand' => $ldata['good']['brand'],
                        'origin' => $ldata['good']['origin'],
                        'quantity_name' => $ldata['good']['goods_json']['qty_name'],
                        'quantity_no' => $ldata['good']['goods_json']['qty_no'],
                        'rate' => $ldata['good']['goods_json']['rate1'],
                        'empty_kgs' => $ldata['good']['goods_json']['empty_kgs'],
                        'net_weight' => $ldata['good']['goods_json']['net_kgs'],
                        'gross_weight' => $ldata['good']['goods_json']['total_kgs'],
                        'container_no' => $ldata['good']['container_no'] ?? '',
                        'container_name' => $ldata['good']['container_name'] ?? ''
                    ];
                }
            }
        }
    }

    // Output the grouped data as JSON
    echo json_encode($responseData, JSON_PRETTY_PRINT);
}
