<?php
include('../connection.php');

if (isset($_POST["allot"])) {
    $allot = mysqli_real_escape_string($connect, $_POST["allot"]);

    // Fetch purchased and sold quantities grouped by goods_id, size, brand, origin, and qty_name
    $query = "
        SELECT 
            goods_id, 
            size, 
            brand, 
            origin, 
            qty_name,
            SUM(CASE WHEN p_s = 'p' THEN qty_no ELSE 0 END) AS purchased_quantity,
            SUM(CASE WHEN p_s = 's' THEN qty_no ELSE 0 END) AS sold_quantity
        FROM 
            transaction_items
        WHERE 
            allotment_name = '$allot'
        GROUP BY 
            goods_id, size, brand, origin, qty_name
    ";

    $result = mysqli_query($connect, $query);

    $data = [];
    $options = '<option value="" selected>Select Goods</option>';
    $uniqueGoods = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $goods_id = $row['goods_id'];
        $size = $row['size'];
        $brand = $row['brand'];
        $origin = $row['origin'];
        $qty_name = strtolower($row['qty_name']);
        $purchased_quantity = $row['purchased_quantity'];
        $sold_quantity = $row['sold_quantity'];
        $remaining_quantity = $purchased_quantity - $sold_quantity;

        // Prepare unique goods options
        if (!in_array($goods_id, $uniqueGoods)) {
            $options .= '<option value="' . $goods_id . '">' . goodsName($goods_id) . '</option>';
            $uniqueGoods[] = $goods_id;
        }

        if (!isset($data[$goods_id])) {
            $data[$goods_id] = [
                'goods_id' => $goods_id,
                'goods_name' => goodsName($goods_id),
                'sizes' => []
            ];
        }

        // Find or add size
        $sizeIndex = array_search($size, array_column($data[$goods_id]['sizes'], 'size'));
        if ($sizeIndex === false) {
            $data[$goods_id]['sizes'][] = [
                'size' => $size,
                'brands' => []
            ];
            $sizeIndex = count($data[$goods_id]['sizes']) - 1;
        }

        // Find or add brand and origin
        $brandIndex = array_search($brand, array_column($data[$goods_id]['sizes'][$sizeIndex]['brands'], 'brand'));
        if ($brandIndex === false) {
            $data[$goods_id]['sizes'][$sizeIndex]['brands'][] = [
                'brand' => $brand,
                'origin' => $origin,
                'quantities' => []
            ];
            $brandIndex = count($data[$goods_id]['sizes'][$sizeIndex]['brands']) - 1;
        }

        // Add qty_name and quantities
        $data[$goods_id]['sizes'][$sizeIndex]['brands'][$brandIndex]['quantities'][] = [
            'qty_name' => $qty_name,
            'purchased_quantity' => $purchased_quantity,
            'sold_quantity' => $sold_quantity,
            'remaining_quantity' => $remaining_quantity
        ];
    }

    // Ensure arrays are indexed properly
    foreach ($data as &$goods) {
        foreach ($goods['sizes'] as &$size) {
            foreach ($size['brands'] as &$brand) {
                $brand['quantities'] = array_values($brand['quantities']);
            }
            $size['brands'] = array_values($size['brands']);
        }
        $goods['sizes'] = array_values($goods['sizes']);
    }

    // Return JSON response
    echo json_encode([
        'html' => $options,
        'quantities' => array_values($data)
    ]);
}
