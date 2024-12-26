<?php
require_once 'functions.php';
$myGood = json_decode('{
    "goods_id": "9",
    "quantity_no": "200",
    "rate": "5",
    "empty_kgs": "0.1",
    "quantity_name": "cotton",
    "size": "30/32",
    "brand": "DGT",
    "origin": "CHILI",
    "net_weight": "980",
    "gross_weight": "1000",
    "container_no": "sdcxdc",
    "container_name": "345efsdf",
    "goods_json": {
        "id": "86",
        "parent_id": "64",
        "p_s": "p",
        "sr": "2",
        "goods_id": "9",
        "allotment_name": "jkhsdjahd",
        "size": "30/32",
        "brand": "DGT",
        "origin": "CHILI",
        "qty_name": "cotton",
        "qty_no": 54,
        "qty_kgs": "5",
        "empty_kgs": "0.1",
        "rate1": "5",
        "rate2": "6",
        "opr": "*",
        "tax_percent": "10",
        "total_kgs": "1000",
        "total_qty_kgs": "20",
        "net_kgs": "980",
        "amount": "4900",
        "total_with_tax": "5390",
        "created_at": "2024-10-10 16:15:31"
    }
}', true);

echo json_encode(calcNewValues(20, $myGood,'rems'));
