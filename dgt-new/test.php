<?php
require_once 'connection.php';
require_once 'functions.php';

$mydata = '{"goods_id":"12","quantity_no":3434,"rate":"5","empty_kgs":"0.3","quantity_name":"bags","size":"0","brand":"DG","origin":"AFGHANI","net_weight":16139.8,"gross_weight":17170,"container_no":"ssd","container_name":"HASnk","goods_json":{"id":"123","parent_id":"1","p_s":"p","sr":"1","quality_report":"This is best quality","goods_id":"12","allotment_name":"5container ","size":"0","brand":"DG","origin":"AFGHANI","qty_name":"bags","qty_no":3434,"qty_kgs":"5","total_kgs":17170,"empty_kgs":"0.3","total_qty_kgs":1030.2,"net_kgs":16139.8,"divide":"TON","weight":"0.2","total":80699,"price":"TON","currency1":"USD","rate1":"2","amount":161398,"currency2":"AED","rate2":"10","opr":"*","tax_percent":"","tax_amount":0,"total_with_tax":0,"final_amount":1613980,"created_at":"2024-12-27 17: 23: 54"},"amount":161398,"tax_amount":0,"total_with_tax":0,"final_amount":1613980}';

// Decode JSON without escaping it
$myGood = json_decode($mydata, true);

if ($myGood === null) {
    echo "JSON decoding failed: " . json_last_error_msg();
} else {
    print_r($myGood);
}
