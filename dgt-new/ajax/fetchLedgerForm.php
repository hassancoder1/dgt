<?php require_once '../connection.php';
$valid['success'] = array('tableData' => array(), 'bottomData' => array());
$output = '';
$numRows = $dr_total = $cr_total = $totalBalanceBnaam = 0;
if (isset($_POST['khaata_id'])) {
    $khaata_id = $_POST['khaata_id'];
    $sql = "SELECT * FROM `roznamchaas` WHERE khaata_id = '$khaata_id' ";
    //var_dump($_POST);
    if (isset($_POST['action'])) {
        $branch_id = $_POST['branch_id'];
        if ($branch_id > 0) {
            $sql .= " AND branch_id = '$branch_id' ";
        }
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $sql .= " AND r_date BETWEEN '$start_date' AND '$end_date'";
    }
    $data = mysqli_query($connect, $sql);
    $numRows = mysqli_num_rows($data);
    if ($numRows > 0) {
        $jmaa = $bnaam = $balance = 0;
        while ($datum = mysqli_fetch_assoc($data)) {
            $dr = $cr = 0;
            $output .= '<tr>';
            $output .= '<td class="font-size-11 text-nowrap">' . branchName($datum['branch_id']) . '</td>';
            $output .= '<td class="text-nowrap font-size-12">' . $datum["r_date"] . '</td>';
            $output .= '<td>' . $datum['r_id'] . '-' . $datum['branch_serial'] . '</td>';
            $output .= '<td>' . $datum['username'] . '</td>';
            $output .= '<td>' . (count(explode('.', $datum['r_name'])) == 2 ? (!empty(getTransactionSr($datum['roznamcha_no'])) ? getTransactionSr($datum['roznamcha_no']) : $datum['roznamcha_no']) : $datum['roznamcha_no']) . '</td>';
            $output .= '<td class="font-size-11">' . $datum['r_name'] . '</td>';
            $output .= '<td class="text-nowrap">' . (count(explode('.', $datum['r_name'])) == 2 ? (!empty(getTransactionSr($datum['roznamcha_no'])) ? getTransactionSr($datum['roznamcha_no']) : $datum['roznamcha_no']) : $datum['r_no']) . '</td>';
            if ($datum['dr_cr'] == "dr") {
                $dr = $datum['amount'];
                $dr_total += $dr;
                $jmaa += $datum['amount'];
            } else {
                $cr = $datum['amount'];
                $cr_total += $cr;
                $bnaam += $datum['amount'];
            }
            $balance = $jmaa - $bnaam;
            $bank_str = $date_str = "";
            /*if ($datum['r_type'] == "bank") {
                $bank_str = ' <span class="">Bank: ' . getTableDataByIdAndColName('banks', $datum['bank_id'], 'bank_name') . '</span> ';
                $date_str = ' <span class="">Payment Date: ' . $datum['r_date_payment'] . '</span> ';
            }*/
            $output .= '<td class="font-size-11">' . $datum['details'] . ' </td>';
            $output .= '<td class="text-success"> ' . round($dr) . ' </td>';
            $output .= '<td class="text-danger"> ' . round($cr) . ' </td>';
            //$output .= '<td class="small">' . $datum['dr_cr'] . '</td>';
            $redGreenText = $balance > 0 ? 'text-success' : 'text-danger';
            $output .= '<td class="' . $redGreenText . '"> ' . round($balance) . '</td>';
            $output .= '</tr> ';
        }
    } else {
        $output .= ' <tr><td colspan = "12" class="text-center text-danger" >No record</td></tr>';
    }
} else {
    $output .= '<tr><td colspan = "12" class="text-center text-danger" >Invalid A/c. #</td></tr>';
}
$totalBalanceBnaam = $dr_total - $cr_total;
$valid['tableData'] = $output;
$valid['bottomData'][0] = $numRows;
$valid['bottomData'][1] = $dr_total;
$valid['bottomData'][2] = $cr_total;
$valid['bottomData'][3] = $totalBalanceBnaam;
echo json_encode($valid);
