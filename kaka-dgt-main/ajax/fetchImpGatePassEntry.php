<?php
$rgbColor = array();
require_once '../connection.php';
$valid['success'] = array('tableData' => array(), 'bottomData' => array());
$output = '';
$totalSerial = $totalSummaryQty = $totalBardana = $totalBnaam = $totalBalanceBnaam = 0;
if (isset($_POST['khaata_id'])) {
    $khaata_id = $_POST['khaata_id'];
    //$khaata_no = $_POST['khaata_no'];
    if (isset($_POST['action'])) {
        $branch_id = $_POST['branch_id'];
        if ($branch_id == 0) {
            $branch_append = "";
        } else {
            $branch_append = " AND branch_id = " . "'$branch_id'" . " ";
        }
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $sql = "SELECT * FROM `imp_truck_loadings` WHERE khaata_id = '$khaata_id' {$branch_append} AND r_date  BETWEEN '$start_date' AND '$end_date'";
    } else {
        $sql = "SELECT * FROM `imp_truck_loadings`";/*WHERE khaata_id = '$khaata_id'*/
    }
    $data = mysqli_query($connect, $sql);
    $numRows = mysqli_num_rows($data);
    if ($numRows > 0) {
        $jmaa = $bnaam = $balance = $totalBill=0;
        while ($datum = mysqli_fetch_assoc($data)) {
            $imp_truck_maals = fetch('imp_truck_maals', array('imp_tl_id' => $datum['id']));
            $maalCount = mysqli_num_rows($imp_truck_maals);
            $maal1_x = 1;
            if ($maalCount > 0) {
                $totalSerial++;
                foreach (array('r', 'g', 'b') as $color) {
                    $rgbColor[$color] = mt_rand(0, 255);
                }
                while ($maal1 = mysqli_fetch_assoc($imp_truck_maals)) {
                    $cc = 'rgba(' . implode(',', $rgbColor) . ',.05)';
                    $json1 = json_decode($maal1['json_data']);
                    $output .= '<tr style="background-color: ' . $cc . '">';
                    $output .= '<td>' . $datum["loading_date"] . '</td>';
                    $output .= '<td>' . $datum['id'] . '</td>';
                    if (!empty($datum['khaata_bs'])) {
                        $jsonBS = json_decode($datum['khaata_bs']);
                        $totalBill=$jsonBS->total_bill;
                    }
                    $output .= '<td>' . $maal1_x . '</td>';
                    $output .= '<td>' . $datum['truck_no'] . '</td>';
                    $output .= '<td class="small">' . $datum['godam_loading_id'] . '</td>';
                    $output .= '<td class="small">' . $datum['godam_empty_id'] . '</td>';
                    $output .= '<td class="small">' . $datum['consignee_name'] . '</td>';
                    $output .= '<td class="small">' . $json1->jins_name . '</td>';
                    $output .= '<td class="small">' . $json1->bardana_name . '</td>';
                    $output .= '<td class="small">' . $json1->bardana_qty . '</td>';
                    $totalBardana += $json1->bardana_qty;
                    $output .= '<td class="small">' . $json1->total_wt . '</td>';
                    $output .= '<td></td>';
                    $output .= '<td>' . $totalBill . '</td>';
                    $output .= '</tr > ';
                    /*$totalJmaa += $datum['jmaa_amount'];
                    $totalBnaam += $datum['bnaam_amount'];*/
                    $maal1_x++;
                    $totalSummaryQty++;
                }
            }
        }
    } else {
        $output .= ' < tr><td colspan = "11" class="text-center text-danger" > کوئی ریکارڈ موجود نہیں ہے۔</td ></tr > ';
    }
} else {
    $output .= '<tr ><td colspan = "11" class="text-center text-danger" > کھاتہ نمبر درست نہیں ہے۔</td ></tr > ';
}
$valid['tableData'] = $output;
$valid['bottomData'][0] = $totalSerial;
$valid['bottomData'][1] = $totalSummaryQty;
$valid['bottomData'][2] = $totalBardana;
//$valid['bottomData'][3] = $totalBalanceBnaam;
echo json_encode($valid);