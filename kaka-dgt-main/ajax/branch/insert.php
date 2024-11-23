<?php
include("../../connection.php");
if (!empty($_POST)) {
    $output = '';
    $message = '';
    $data = array(
        'b_name' => mysqli_real_escape_string($connect, $_POST["b_name"]),
        'b_address' => mysqli_real_escape_string($connect, $_POST["b_address"]),
        'b_incharge' => mysqli_real_escape_string($connect, $_POST["b_incharge"]),
        'b_mobile' => mysqli_real_escape_string($connect, $_POST["b_mobile"]),
        'b_phone' => mysqli_real_escape_string($connect, $_POST["b_phone"]),
        'b_email' => mysqli_real_escape_string($connect, $_POST["b_email"]),
        'b_city' => mysqli_real_escape_string($connect, $_POST["b_city"]),
        'created_at' => date('Y-m-d H:i:s')
    );
    if ($_POST["branch_id"] != '') {
        $query = "  
           UPDATE tbl_employee   
           SET name='$name',   
           address='$address',   
           gender='$gender',   
           designation = '$designation',   
           age = '$age'   
           WHERE id='" . $_POST["employee_id"] . "'";
        //$message = 'Data Updated';
        $message = messageAjax('success', 'نئی برانچ بن گئی ہے۔');
        $done = false;
    } else {
        $done = insert('branches', $data);
        //$message = 'Data Inserted';
        $message = messageAjax('success', 'نئی برانچ بن گئی ہے۔');
    }
//    if (mysqli_query($connect, $query)) {
    if ($done) {
        $output .= '<div class="">' . $message . '</div>';
        $result = fetch('branches');
        $output .= '<table class="table table-bordered table-striped">  
                    <thead>
                        <tr>
                            <th>برانچ</th>
                            <th>پتہ</th>
                            <th>انچارج</th>
                            <th>موبائل</th>
                            <th>فون نمبر</th>
                            <th>ای میل</th>
                            <th>شہر</th>
                        </tr>
                    </thead>';
        $output .= '<tbody>';
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= '<tr>';
            $output .= '<td class="small urdu-td"><a id="' . $row["id"] . '" data-bs-target="#branchModal" type="button" data-bs-toggle="modal" class="btn-link edit_data">' . $row["b_name"] . '</a></td>';
            $output .= '<td class="small urdu-td">' . $row['b_address'] . '</td>';
            $output .= '<td class="small urdu-td">' . $row['b_incharge'] . '</td>';
            $output .= '<td class="small urdu-td">' . $row['b_mobile'] . '</td>';
            $output .= '<td class="small urdu-td">' . $row['b_phone'] . '</td>';
            $output .= '<td class="small urdu-td">' . $row['b_email'] . '</td>';
            $output .= '<td class="small urdu-td">' . $row['b_city'] . '</td>';
            $output .= '</tr>';

            /*$output .= '<tr>
                          <td>' . $row["name"] . '</td>  
                          <td><input type="button" name="edit" value="Edit" id="' . $row["id"] . '" class="btn btn-info btn-xs edit_data" /></td>  
                          <td><input type="button" name="view" value="view" id="' . $row["id"] . '" class="btn btn-info btn-xs view_data" /></td>  
                     </tr>';*/
        }
        $output .= '</tbody></table>';
    }
    echo $output;
}
?>
