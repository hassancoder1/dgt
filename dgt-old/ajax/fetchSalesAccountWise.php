<?php require_once '../connection.php';
$valid['success'] = array('tableData' => array(), 'bottomData' => array());
$output = '';
$row_count = $total_qty = $total_kgs = $net_kgs = $total_amount = $total_final_amount = 0;
if (isset($_POST['khaata_id'])) {
    $khaata_id = $_POST['khaata_id'];
    $sale_details_query = fetch('sale_details');
    while ($details = mysqli_fetch_assoc($sale_details_query)) {
        $parent_id = $details['parent_id'];
        $goods_id = $details['goods_id'];
        if (isset($_POST['action'])) {
            if ($_POST['goods_id'] > 0) {
                if ($goods_id != $_POST['goods_id']) continue;
            }
            if ($_POST['size'] != '') {
                if ($details['size'] != $_POST['size']) continue;
            }
            if ($_POST['allot'] != '') {
                if ($details['allot_name'] != $_POST['allot']) continue;
            }
        }
        $imp_json = json_decode($details['imp_json']);
        $imp_name = $party_khaata_id = '';
        if (!empty($imp_json)) {
            $imp_name = $imp_json->comp_name ?? '';
            $party_khaata_id = $imp_json->party_khaata_id ?? '';
        }
        $bail_json = json_decode($details['bail_json']);
        $bail_no = $container_no = '';
        if (!empty($bail_json)) {
            $bail_no = $bail_json->bail_no ?? '';
            $container_no = $bail_json->container_no ?? '';
        }

        $sales = fetch('sales', array('id' => $parent_id));
        $sale = mysqli_fetch_assoc($sales);
        $s_khaata_id = $sale['s_khaata_id'];
        if ($s_khaata_id == $khaata_id) {
            if (isset($_POST['action'])) {
                $start_date = $_POST['start_date'];
                $end_date = $_POST['end_date'];
                if ($start_date != '') {
                    if ($sale['s_date'] < $start_date) continue;
                }
                if ($end_date != '') {
                    if ($sale['s_date'] > $end_date) continue;
                }
            }
            $output .= '<tr class="font-size-12 text-nowrap">';
            $output .= '<td>' . $parent_id . '-' . $details['d_sr'] . saleSpecificData($parent_id, 'sale_type') . '</td>';
            $output .= '<td>' . $details['allot_name'] . '</td>';
            $output .= '<td>NILL</td>';
            $output .= '<td>' . my_date($sale['s_date']) . '</td>';
            $output .= '<td>' . $imp_name . '</td>';
            $output .= '<td class="text-uppercase">' . seaRoadBadge($sale['sea_road']) . '</td>';
            $output .= '<td>' . $bail_no . '</td>';
            $output .= '<td>' . $container_no . '</td>';
            $output .= '<td>' . goodsName($goods_id) . '</td>';
            $output .= '<td>' . $details['size'] . '</td>';
            $output .= '<td>' . $details['qty_name'] . '</td>';
            $output .= '<td>' . $details['qty_no'] . '</td>';
            $output .= '<td>' . $details['total_qty_kgs'] . '</td>';
            $output .= '<td>' . round($details['net_kgs']) . '</td>';
            $output .= '<td>' . $details['price'] . '</td>';
            $output .= '<td>' . round($details['amount'], 2) . '</td>';
            $output .= '<td>' . $details['currency2'] . '</td>';
            $output .= '<td>' . $details['rate2'] . '</td>';
            $output .= '<td>' . round($details['final_amount'], 2) . '</td>';
            $output .= '</tr> ';
            $row_count++;

            $total_qty += $details['qty_no'];
            $total_kgs += $details['total_qty_kgs'];
            $net_kgs += $details['net_kgs'];
            $total_amount += $details['amount'];
            $total_final_amount += $details['final_amount'];
        }
    }
} else {
    $output .= '<tr><td colspan="19" class="text-center text-danger" >Invalid A/c. #</td></tr>';
}
$totalBalanceBnaam = 0;
$valid['tableData'] = $output;
$valid['bottomData'][0] = $row_count;
$valid['bottomData'][1] = $total_qty;
$valid['bottomData'][2] = $total_kgs;
$valid['bottomData'][3] = $net_kgs;
$valid['bottomData'][4] = round($total_amount);
$valid['bottomData'][5] = round($total_final_amount);
echo json_encode($valid);