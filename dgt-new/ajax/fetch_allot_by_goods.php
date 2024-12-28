<?php
include('../connection.php');

if (isset($_POST["goods_id"])) {
    $goods_id = mysqli_real_escape_string($connect, $_POST["goods_id"]);

    // Fetch purchased and sold quantities grouped by allotment_name, size, brand, origin, and qty_name
    $query = "
        SELECT 
            allotment_name,
            quality_report,
            size, 
            brand, 
            origin, 
            qty_name,
            SUM(CASE WHEN p_s = 'p' THEN qty_no ELSE 0 END) AS purchased_quantity,
            SUM(CASE WHEN p_s = 's' THEN qty_no ELSE 0 END) AS sold_quantity
        FROM 
            transaction_items
        WHERE 
            goods_id = '$goods_id'
        GROUP BY 
            allotment_name, size, brand, origin, qty_name
    ";

    $result = mysqli_query($connect, $query);

    $data = [];
    $options = '<option value="" selected>Select Allotment</option>';
    $uniqueAllotments = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $allotment_name = $row['allotment_name'];
        if (empty($allotment_name)) {
            continue;
        }
        $quality_report = $row['quality_report'];
        $size = $row['size'];
        $brand = $row['brand'];
        $origin = $row['origin'];
        $qty_name = strtolower($row['qty_name']);
        $purchased_quantity = $row['purchased_quantity'];
        $sold_quantity = $row['sold_quantity'];
        $remaining_quantity = $purchased_quantity - $sold_quantity;

        // Prepare unique allotment options
        if (!in_array($allotment_name, $uniqueAllotments)) {
            $options .= '<option value="' . $allotment_name . '">' . $allotment_name . '</option>';
            $uniqueAllotments[] = $allotment_name;
        }

        if (!isset($data[$allotment_name])) {
            $data[$allotment_name] = [
                'allotment_name' => $allotment_name,
                'sizes' => []
            ];
        }

        // Find or add size
        $sizeIndex = array_search($size, array_column($data[$allotment_name]['sizes'], 'size'));
        if ($sizeIndex === false) {
            $data[$allotment_name]['sizes'][] = [
                'size' => $size,
                'brands' => []
            ];
            $sizeIndex = count($data[$allotment_name]['sizes']) - 1;
        }

        // Find or add brand and origin
        $brandIndex = array_search($brand, array_column($data[$allotment_name]['sizes'][$sizeIndex]['brands'], 'brand'));
        if ($brandIndex === false) {
            $data[$allotment_name]['sizes'][$sizeIndex]['brands'][] = [
                'brand' => $brand,
                'origin' => $origin,
                'quantities' => []
            ];
            $brandIndex = count($data[$allotment_name]['sizes'][$sizeIndex]['brands']) - 1;
        }

        // Add qty_name and quantities
        $data[$allotment_name]['sizes'][$sizeIndex]['brands'][$brandIndex]['quantities'][] = [
            'qty_name' => $qty_name,
            'purchased_quantity' => $purchased_quantity,
            'sold_quantity' => $sold_quantity,
            'remaining_quantity' => $remaining_quantity,
            'quality_report' => $quality_report
        ];
    }

    // Ensure arrays are indexed properly
    foreach ($data as &$allotment) {
        foreach ($allotment['sizes'] as &$size) {
            foreach ($size['brands'] as &$brand) {
                $brand['quantities'] = array_values($brand['quantities']);
            }
            $size['brands'] = array_values($size['brands']);
        }
        $allotment['sizes'] = array_values($allotment['sizes']);
    }

    // Return JSON response
    echo json_encode([
        'html' => $options,
        'allotments' => array_values($data)
    ]);
}
