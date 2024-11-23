<?php include("../connection.php");
$userId = $_SESSION['userId'];
if ($_POST) {
    $output = $message = '';
    $done = false;
    $dt_tl_id = mysqli_real_escape_string($connect, $_POST['dt_tl_id']);
    $dt_truck_maals_id = mysqli_real_escape_string($connect, $_POST['dt_truck_maals_id']);
    $form_name = mysqli_real_escape_string($connect, $_POST['form_name']);

    $maal2 = isDTKirayaAdded($dt_truck_maals_id, $form_name);
    if ($maal2['success']) {//data already exists in maal2 table
        $maal2Id = $maal2['output']['id'];
        $data = array(
            'json_data' => json_encode($_POST, JSON_UNESCAPED_UNICODE),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $userId
        );
        $done = update('dt_truck_maals2', $data, array('id' => $maal2Id));
        //$json2 = json_decode($maal2['output']['json_data']);
    } else {
        $data = array(
            'dt_tl_id' => $dt_tl_id,
            'maal_id' => $dt_truck_maals_id,
            'form_name' => $form_name,
            'json_data' => json_encode($_POST, JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $userId
        );
        $done = insert('dt_truck_maals2', $data);
    }
    $message = messageAjax('info', ' تبدیلی ہو گئی ہے۔', 'mb-0');
    if ($done) {
        $maals = fetch('dt_truck_maals', array('dt_tl_id' => $_POST['dt_tl_id']));
        $x = 1;
        $remainingRows = $godam_receive_no = $receive_bardana_qty = $bardana_balance = 0;
        $godam_receive_date = null;
        $maal2Id = $taqseem_qty = $per_mazdoori = $bardana_qty = $per_wt = $total_wt = $empty_wt = $total_empty_wt = $saaf_wt = $total_exp = $total_expFinal = 0;
        while ($maal = mysqli_fetch_assoc($maals)) {
            $maal2 = isDTKirayaAdded($maal['id'], $form_name);
            if ($maal2['success']) {
                $maal2Id = $maal2['output']['id'];
                $json2 = json_decode($maal2['output']['json_data']);
                if ($form_name == 'godam_received') {
                    $godam_receive_date = $json2->godam_receive_date;
                    $godam_receive_no = $json2->godam_receive_no;
                    $receive_bardana_qty = $json2->receive_bardana_qty;
                    $total_expFinal += $json2->receive_bardana_qty;
                } else {
                    $taqseem_qty = $json2->taqseem_qty;
                    $per_mazdoori = $json2->per_mazdoori;
                    $total_exp = $json2->total_exp;
                    $total_expFinal += $json2->total_exp;
                }
            } else {
                $maal2Id = 0;
            }
            $json = json_decode($maal['json_data']);
            $output .= '<tr class="row-py-0 cursor-pointer" id="' . $maal['id'] . '" data-maal2-id="' . $maal2Id . '" data-form-name="' . $form_name . '" onclick="maalEntryRowEdit(this)">';
            $output .= '<td class="">' . $x . '</td>';
            $output .= '<td class="">' . $json->jins_name . '</td>';
            $output .= '<td>' . $json->bardana_name . '</td>';
            $output .= '<td>' . $json->bardana_qty . '</td>';
            $output .= '<td>' . $json->per_wt . '</td>';
            $output .= '<td>' . $json->total_wt . '</td>';
            $output .= '<td>' . $json->empty_wt . '</td>';
            $output .= '<td>' . $json->total_empty_wt . '</td>';
            $output .= '<td>' . $json->saaf_wt . '</td>';
            if ($maal2Id > 0) {
                if ($form_name == 'godam_received') {
                    $output .= '<td>' . $godam_receive_date . '</td>';
                    $output .= '<td>' . $godam_receive_no . '</td>';
                    $output .= '<td>' . $receive_bardana_qty . '</td>';
                    $barSubBal = $json->bardana_qty - $receive_bardana_qty;
                    $bardana_balance += $barSubBal;
                    $output .= '<td>' . $barSubBal . '</td>';
                } else {
                    $output .= '<td>' . $taqseem_qty . '</td>';
                    $output .= '<td>' . $per_mazdoori . '</td>';
                    $output .= '<td>' . $total_exp . '</td>';
                }
            } else {
                $output .= '<td colspan="3"></td>';
            }
            $output .= '</tr>';
            $x++;
            $remainingRows++;
            $bardana_qty += $json->bardana_qty;
            $per_wt += $json->per_wt;
            $total_wt += $json->total_wt;
            $empty_wt += $json->empty_wt;
            $total_empty_wt += $json->total_empty_wt;
            $saaf_wt += $json->saaf_wt;
            if ($maal2Id > 0) {
                $remainingRows--;
            }
        }
        $output .= '<tr class="row-py-0 bg-info bg-opacity-25 bold">';
        $output .= '<td>' . $x - 1 . '</td>';
        $output .= '<td colspan="2"></td>';
        $output .= '<td>' . $bardana_qty . '</td>';
        $output .= '<td>' . $per_wt . '</td>';
        $output .= '<td>' . $total_wt . '</td>';
        $output .= '<td>' . $empty_wt . '</td>';
        $output .= '<td>' . $total_empty_wt . '</td>';
        $output .= '<td>' . $saaf_wt . '</td>';
        $output .= '<td colspan="2"></td>';
        $output .= '<td><span id="total_exp_final">' . $total_expFinal . '</span><input type="hidden" value="' . $remainingRows . '" id="remainingRows"></td>';
        if ($form_name == 'godam_received') {
            $output .= '<td>' . $bardana_balance . '</td>';
        }
        $output .= '</tr>';
        $output .= '<tr class="row-py-0"><td class="p-0" colspan="13">' . $message . '</td></tr>';
    } else {
        $message = messageAjax('danger', 'ڈیٹا بیس پرابلم.');
    }
    echo $output;
    echo $message;
    //echo '<pre>';//var_dump($data['json_data']);
} ?>
