<?php include("../connection.php");
$userId = $_SESSION['userId'];
if ($_POST['dt_truck_maals_action']) {
    $output = '';
    $message = '';
    $done = false;
    $action = $_POST['dt_truck_maals_action'];
    if ($action == "insert") {
        $data = array(
            'dt_tl_id' => $_POST['dt_tl_id'],
            'json_data' => json_encode($_POST, JSON_UNESCAPED_UNICODE),
            'created_by' => $userId,
            'created_at' => date('Y-m-d H:i:s')
        );
        $done = insert('dt_truck_maals', $data);
        $message = messageAjax('success', 'مال کی انٹری ہو گئی ہے۔');
    }
    if ($action == "update") {
        $data = array(
            'json_data' => json_encode($_POST, JSON_UNESCAPED_UNICODE),
            'updated_by' => $userId,
            'updated_at' => date('Y-m-d H:i:s')
        );
        $done = update('dt_truck_maals', $data, array('id' => $_POST['dt_truck_maals_id']));
        $message = messageAjax('info', 'مال کی انٹری تبدیل ہو گئی ہے۔', 'mb-0');
    }
    if ($done) {
        $maals = fetch('dt_truck_maals', array('dt_tl_id' => $_POST['dt_tl_id']));
        $x = 0;
        $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = 0;
        $output .= '<tr class="row-py-0"><td class="p-0" colspan="9">' . $message . '</td></tr>';
        while ($maal = mysqli_fetch_assoc($maals)) {
            $json = json_decode($maal['json_data']);
            $output .= '<tr class="row-py-0 cursor-pointer" id="' . $maal['id'] . '" onclick="maalEntryRowEdit(this)">';
            $output .= '<td class="">' . $maal['id'] . '</td>';
            $output .= '<td class="">' . $json->jins_name . '</td>';
            $output .= '<td>' . $json->bardana_name . '</td>';
            $output .= '<td>' . $json->bardana_qty . '</td>';
            $output .= '<td>' . $json->per_wt . '</td>';
            $output .= '<td>' . $json->total_wt . '</td>';
            $output .= '<td>' . $json->empty_wt . '</td>';
            $output .= '<td>' . $json->total_empty_wt . '</td>';
            $output .= '<td>' . $json->saaf_wt . '</td>';
            $output .= '</tr>';
            $x++;
            $bardana_qty += $json->bardana_qty;
            $per_wt += $json->per_wt;
            $total_wt += $json->total_wt;
            $empty_wt += $json->empty_wt;
            $total_empty_wt += $json->total_empty_wt;
            $saaf_wt += $json->saaf_wt;
        }
        $output .= '<tr class="row-py-0 bg-info bg-opacity-25 bold">';
        $output .= '<td>' . $x . '</td>';
        $output .= '<td colspan="2"></td>';
        $output .= '<td>' . $bardana_qty . '</td>';
        $output .= '<td>' . $per_wt . '</td>';
        $output .= '<td>' . $total_wt . '</td>';
        $output .= '<td>' . $empty_wt . '</td>';
        $output .= '<td>' . $total_empty_wt . '</td>';
        $output .= '<td>' . $saaf_wt . '</td>';
        $output .= '</tr>';
    } else {
        $message = messageAjax('danger', 'ڈیٹا بیس پرابلم');
    }
    echo $output;
    echo $message;
    echo '<pre>';
    var_dump($data);
}
?>
