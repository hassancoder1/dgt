<?php
function insert($table, $data)
{
    global $connect;
    $query = "";
    $keys = "(";
    $values = "(";
    $i = 0;
    $j = 0;
    $final_query = "";
    $count = count($data);


    $query .= "INSERT INTO " . $table . " ";

    foreach ($data as $key => $value) {
        $i++;
        $j++;


        $keys .= "`" . $key . "`";
        if ($i < $count) {

            $keys .= ",";
        }
        $values .= "'" . $value . "'";
        if ($i < $count) {

            $values .= ",";
        }
    }
    $values .= ")";
    $keys .= ")";

    $final_query .= $query . $keys . " VALUES " . $values;

    //var_dump($final_query);
    $result = mysqli_query($connect, $final_query);
    if ($result) {
        return true;
    } else {
        return false;
    }
}

function update($table, $data, $where = null)
{
    global $connect;
    $query = "";
    $values = "";
    $i = 0;
    $j = 0;
    $final_query = "";
    $count = count($data);

    $query .= "UPDATE `" . $table . "` SET ";
    foreach ($data as $key => $value) {
        $i++;
        $values .= " " . $key . "='" . $value . "' ";
        if ($i < $count) {
            $values .= ",";
        }
    }
    $count1 = count($where);
    $where_values = " WHERE";
    foreach ($where as $key_where => $value_where) {
        $j++;
        $where_values .= " " . $key_where . "='" . $value_where . "' ";
        if ($j < $count1) {
            $where_values .= "AND";
        }
    }
    $final_query .= $query . $values . $where_values;
    // var_dump( $final_query);
    $result = mysqli_query($connect, $final_query);
    if ($result) {
        return true;
    } else {
        return false;
    }
    /*if (!$result) {
        die("Query Failed") . mysqli_error($connect);
    }*/
}

function fetch($table, $where = null)
{
    global $connect;
    $query = "SELECT * FROM `" . $table . "`";
    if ($where) {
        $count = count($where);
        $i = 0;

        $where_values = " WHERE";
        foreach ($where as $key => $value) {
            $i++;
            $where_values .= " " . $key . "='" . $value . "' ";
            if ($i < $count) {
                $where_values .= "AND";
            }
        }
        $query .= $where_values;
    }
    //var_dump($query);
    $result = mysqli_query($connect, $query);
    if (!$result) {
        die("Query Failed") . mysqli_error($connect);
    } else {
        //return $this->result_array($result);
        return $result;
    }
}
function decode_unique_code($unique_code, $keys = 'all')
{
    $data = [];
    if (empty($unique_code)) {
    } else if (preg_match('/^([ps])([blc]{1})(se|rd|ld|wr)_(\d+)_(\d+)$/', $unique_code, $matches)) {
        // if (preg_match('/^([ps])([blc]{1})(se|rd|ld|wr)_(\d+)_([a-zA-Z0-9]+)$/', $unique_code, $matches)) {
        $data = [
            'Ttype' => $matches[1],
            'Tcat' => $matches[2],
            'Troute' => $matches[3],
            'TID' => $matches[4],
            // 'BLUID' => $matches[5]
            'LID' => $matches[5]
        ];
    } else {
        echo "Invalid Unique Code format.";
        exit;
    }
    if ($keys === 'all') {
        if (!empty($unique_code)):
            return array_values($data);
        endif;
    }
    if (is_string($keys)) {
        return $data[$keys] ?? null;
    }
    if (is_array($keys)) {
        $filteredData = [];
        foreach ($keys as $key) {
            $filteredData[] = $data[$key] ?? null;
        }
        return $filteredData;
    }
    echo "Invalid keys parameter.";
    exit;
}

function calculateValues($qtyNo, $qtyKgs, $emptyKgs, $weight, $rate1, $rate2, $operator, $taxPercent = null)
{
    $totalKgs = $qtyNo * $qtyKgs;
    $totalQtyKgs = $qtyNo * $emptyKgs;
    $netKgs = $totalKgs - $totalQtyKgs;
    $total = $weight != 0 ? round($netKgs / $weight, 3) : 0;
    $amount = round($total * $rate1, 3);
    $finalAmount = $amount;
    if ($rate2 > 0) {
        $finalAmount = ($operator === '/') ? ($rate2 != 0 ? round($amount / $rate2, 3) : 0) : round($rate2 * $amount, 3);
    } elseif (!is_null($taxPercent)) {
        $taxAmount = round(($amount * $taxPercent) / 100, 2);
        $finalAmount = $amount + $taxAmount;
        return [
            'qty_no' => $qtyNo,
            'total_kgs' => $totalKgs,
            'total_qty_kgs' => $totalQtyKgs,
            'net_kgs' => $netKgs,
            'total' => $total,
            'amount' => $amount,
            'tax_amount' => $taxAmount,
            'total_with_tax' => $finalAmount,
            'final_amount' => $finalAmount
        ];
    }
    return [
        'qty_no' => $qtyNo,
        'total_kgs' => $totalKgs,
        'total_qty_kgs' => $totalQtyKgs,
        'net_kgs' => $netKgs,
        'total' => $total,
        'amount' => $amount,
        'tax_amount' => 0,
        'total_with_tax' => 0,
        'final_amount' => $finalAmount
    ];
}
function getTransactionSr($t_id)
{
    global $connect;
    return mysqli_fetch_assoc(mysqli_query($connect, "SELECT sr FROM transactions WHERE id=$t_id"))['sr'] ?? '';
}
function calcNewValues($quantity, $goodsDetails, $type)
{
    $updatedGoodsDetails = $goodsDetails;
    $goodsJson = isset($updatedGoodsDetails['goods_json']) ? $updatedGoodsDetails['goods_json'] : [];
    if (empty($goodsJson)) {
        throw new Exception('Invalid goods_json structure.');
    }
    $qtyKgs = floatval($goodsJson['qty_kgs'] ?? 0);
    $emptyKgs = floatval($goodsJson['empty_kgs'] ?? 0);
    $weight = floatval($goodsJson['weight'] ?? 0);
    $rate1 = floatval($goodsJson['rate1'] ?? 0);
    $rate2 = floatval($goodsJson['rate2'] ?? 0);
    $taxPercent = isset($goodsJson['tax_percent']) ? floatval($goodsJson['tax_percent']) : null;
    $operator = $goodsJson['opr'] ?? '*';
    if ($type === 'totals') {
        $qtyNo = floatval($quantity);
        $results = calculateValues($qtyNo, $qtyKgs, $emptyKgs, $weight, $rate1, $rate2, $operator, $taxPercent);
        $updatedGoodsDetails['quantity_no'] = $qtyNo;
        $updatedGoodsDetails['net_weight'] = $results['net_kgs'];
        $updatedGoodsDetails['gross_weight'] = $results['total_kgs'];
        $updatedGoodsDetails['amount'] = $results['amount'];
        $updatedGoodsDetails['tax_amount'] = $results['tax_amount'];
        $updatedGoodsDetails['total_with_tax'] = $results['total_with_tax'];
        $updatedGoodsDetails['final_amount'] = $results['final_amount'];
        $updatedGoodsDetails['goods_json'] = array_merge($goodsJson, $results);
    } elseif ($type === 'rems') {
        $qtyNo = floatval($quantity);
        $results = calculateValues($qtyNo, $qtyKgs, $emptyKgs, $weight, $rate1, $rate2, $operator, $taxPercent);
        $updatedGoodsDetails['goods_json'] = array_merge($goodsJson, $results);
    } elseif ($type === 'both') {
        if (!is_array($quantity) || count($quantity) !== 2) {
            throw new Exception('Invalid quantity structure for type "both".');
        }
        [$totalQty, $remQty] = $quantity;
        $totalResults = calculateValues(floatval($totalQty), $qtyKgs, $emptyKgs, $weight, $rate1, $rate2, $operator, $taxPercent);
        $updatedGoodsDetails['quantity_no'] = floatval($totalQty);
        $updatedGoodsDetails['net_weight'] = $totalResults['net_kgs'];
        $updatedGoodsDetails['gross_weight'] = $totalResults['total_kgs'];
        $updatedGoodsDetails['amount'] = $totalResults['amount'];
        $updatedGoodsDetails['tax_amount'] = $totalResults['tax_amount'];
        $updatedGoodsDetails['total_with_tax'] = $totalResults['total_with_tax'];
        $updatedGoodsDetails['final_amount'] = $totalResults['final_amount'];
        $remResults = calculateValues(floatval($remQty), $qtyKgs, $emptyKgs, $weight, $rate1, $rate2, $operator, $taxPercent);
        $updatedGoodsDetails['goods_json'] = array_merge($goodsJson, $remResults);
    }

    return $updatedGoodsDetails;
}




function recordExists($table, $conditions)
{
    global $connect;

    $query = "SELECT COUNT(*) as count FROM `" . $table . "` WHERE ";
    $values = "";
    $i = 0;
    $count = count($conditions);

    foreach ($conditions as $key => $value) {
        $i++;
        $values .= " " . $key . "='" . mysqli_real_escape_string($connect, $value) . "' ";
        if ($i < $count) {
            $values .= "AND ";
        }
    }

    $query .= $values;

    $result = mysqli_query($connect, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['count'] > 0 ? true : false;
    } else {
        return false;  // or you could return an error message/log error if needed
    }
}

function generateSubMenu($parentId, $connect)
{
    $query = "SELECT * FROM navbar WHERE parent_id = $parentId ORDER BY position";
    $result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) > 0) {
        echo '<ul class="dropdown-menu">';
        while ($row = mysqli_fetch_assoc($result)) {
            $row_id = $row['id'];
            $dd = mysqli_query($connect, "SELECT * FROM `navbar` WHERE parent_id='$row_id'");
            $hasDropdown = mysqli_num_rows($dd) > 0;
            $dropdownSubmenuClass = $hasDropdown ? 'dropdown-submenu' : '';

            echo '<li class="dropdown-item ' . $dropdownSubmenuClass . '">';
            echo '<a class="dropdown-item ' . ($hasDropdown ? 'dropdown-toggle' : '') . '" href="' . $row['url'] . '" ' . ($hasDropdown ? 'role="button" data-bs-toggle="dropdown" aria-expanded="false"' : '') . '>';
            echo $row['label'];
            echo '</a>';
            if ($hasDropdown) {
                generateSubMenu($row['id'], $connect);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

function generateSubMenuAdmin($parentId, $connect)
{
    $query = "SELECT * FROM navbar WHERE parent_id = $parentId ORDER BY position";
    $result = mysqli_query($connect, $query);
    if (mysqli_num_rows($result) > 0) {
        echo '<ul class="list-group">';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li class="list-group-item pb-0 border-0">';
            echo '<a class="text-dark" href="navbar?id=' . $row['id'] . '">';
            echo $row['position'] . '. ' . $row['label'] . '</a>';
            echo '<small class="ms-2">/' . $row['url'] . '</small>';
            generateSubMenuAdmin($row['id'], $connect);
            echo '</li>';
        }
        echo '</ul>';
    }
}

function saveTransactionAccount($trans_id, $type, $dr_cr, $acc, $acc_name, $acc_id, $acc_kd_id, $details = null): bool
{
    $data = ['trans_id' => $trans_id, 'type' => $type, 'dr_cr' => $dr_cr, 'acc' => $acc, 'acc_name' => $acc_name, 'acc_id' => $acc_id, 'acc_kd_id' => $acc_kd_id, 'details' => $details];
    $d = insert('transaction_accounts', $data);
    return (bool)$d;
}

function updateTransactionAccount($transaction_accounts_id, $acc, $acc_name, $acc_id, $acc_kd_id, $details = null): bool
{
    $data = ['acc' => $acc, 'acc_name' => $acc_name, 'acc_id' => $acc_id, 'acc_kd_id' => $acc_kd_id, 'details' => $details];
    $d = update('transaction_accounts', $data, ['id' => $transaction_accounts_id]);
    return (bool)$d;
}

function getTransactionAccounts($trans_id, $type, $dr_cr)
{
    $d = fetch('transaction_accounts', ['trans_id' => $trans_id, 'type' => $type, 'dr_cr' => $dr_cr]);
    if (mysqli_num_rows($d) > 0) {
        $data = mysqli_fetch_assoc($d);
        return $data;
    }
}

function getSaleDetailsSerial($sale_id)
{
    $serial = fetch('sale_details', array('parent_id' => $sale_id));
    $pd_serial = mysqli_num_rows($serial);
    return $pd_serial + 1;
}

function firstLine($string)
{
    $lines = explode("\n", $string);
    $firstLine = $lines[0];
    return $firstLine;
}

function exchange_total($currency_name, $p_s = 'p')
{
    global $connect;
    $qty = 0;
    $arr = array('p', 's');
    if (in_array($p_s, $arr)) {
        $sql = $p_s == 'p' ?
            "SELECT SUM(qty) as qty FROM `exchanges` WHERE `curr1` = '$currency_name' " :
            "";

        $data = mysqli_query($connect, $sql);
        if (mysqli_num_rows($data) > 0) {
            $datum = mysqli_fetch_assoc($data);
            $qty = $datum['qty'];
        }
    }
    return $qty;
}

function decodeSpecialCharacters($string)
{
    $replacements = array('u0027' => "'", 'u0022' => '"', 'u0026' => '&', 'u003C' => '<', 'u003E' => '>', 'u0021' => '!', 'u002C' => ',', 'u002E' => '.', 'u003B' => ';', 'u003A' => ':', 'u003F' => '?', 'u0040' => '@', 'u002B' => '+', 'u002D' => '-', 'u002F' => '/', 'u005C' => '\\', 'u0028' => '(', 'u0029' => ')', 'u007B' => '{', 'u007D' => '}', 'u005B' => '[', 'u005D' => ']', 'u00A0' => ' ');
    return str_replace(array_keys($replacements), array_values($replacements), $string);
}
function transactionSingle($transaction_id)
{
    global $connect;
    $id = $transaction_id;
    if (is_numeric($transaction_id) && recordExists('transactions', ['id' => $id])) {
        $records = fetch('transactions', array('id' => $id));
        $record = mysqli_fetch_assoc($records);
        $Type = $record['p_s'] == 'p' ? 'purchase' : 'sale';
        $dr_record = getTransactionAccounts($id, $Type, 'dr');
        $cr_record = getTransactionAccounts($id, $Type, 'cr');
        $p_s_name = $Type;
        $_fields = [
            'sr' => $record['sr'],
            'username' => userName($record['created_by']),
            'branch_id' => $record['branch_id'],
            'p_s' => $record['p_s'],
            'p_s_name' => $p_s_name,
            'type' => $record['type'],
            'active' => $record['active'],
            'locked' => $record['locked'],
            '_date' => $record['_date'],
            'country' => $record['country'],
            'is_doc' => $record['is_doc'],
            'dr_acc' => $dr_record['acc'],
            'dr_acc_name' => $dr_record['acc_name'],
            'dr_acc_details' => $dr_record['details'],
            'dr_acc_id' => $dr_record['acc_id'],
            'dr_acc_kd_id' => $dr_record['acc_kd_id'],
            'cr_acc' => $cr_record['acc'],
            'cr_acc_name' => $cr_record['acc_name'],
            'cr_acc_details' => $cr_record['details'],
            'cr_acc_id' => $cr_record['acc_id'],
            'cr_acc_kd_id' => $cr_record['acc_kd_id'],
            'transaction_accounts_dr_id' => $dr_record['id'],
            'transaction_accounts_cr_id' => $cr_record['id']
        ];
        $_fields['sea_road_array'] = $_fields['payment_details'] = [];
        if (!empty($record['payments'])) {
            $json_payments = json_decode($record['payments']);
            $_fields['payment_details'] = $json_payments;
        }
        if (!empty($record['sea_road'])) {
            // Decode the JSON string into an array
            $json_sea_road = json_decode($record['sea_road'], true);

            // If decoding failed, use the raw string
            if (!$json_sea_road) {
                $json_sea_road = $record['sea_road'];
            }

            // Check if sea_road exists and handle accordingly
            $_fields['sea_road'] = $json_sea_road['sea_road'] ?? '';
            $_fields['sea_road_report'] = $json_sea_road['report'] ?? '';

            // Initialize sea_road_array if not already
            $_fields['sea_road_array'] = $_fields['sea_road_array'] ?? [];

            // Handling 'sea' transportation
            if ($_fields['sea_road'] == 'sea') {
                $_fields['sea_road_array'] = array_merge($_fields['sea_road_array'], [
                    'is_loading' => ['Loading', $json_sea_road['is_loading'] ?? ''],
                    'l_country' => ['Loading Country', $json_sea_road['l_country'] ?? ''],
                    'l_port' => ['Loading Port', $json_sea_road['l_port'] ?? ''],
                    'l_date' => ['Loading Date', $json_sea_road['l_date'] ?? ''],
                    'ctr_name' => ['Container Name', $json_sea_road['ctr_name'] ?? ''],
                    'is_receiving' => ['Receiving', $json_sea_road['is_receiving'] ?? ''],
                    'r_country' => ['Receiving Country', $json_sea_road['r_country'] ?? ''],
                    'r_port' => ['Receiving Port', $json_sea_road['r_port'] ?? ''],
                    'r_date' => ['Receiving Date', $json_sea_road['r_date'] ?? ''],
                    'arrival_date' => ['Arrival Date', $json_sea_road['arrival_date'] ?? ''],
                ]);
            }

            // Handling 'road' transportation
            elseif ($_fields['sea_road'] == 'road') {
                $_fields['sea_road_array'] = array_merge($_fields['sea_road_array'], [
                    'l_country_road' => ['Loading Country', $json_sea_road['l_country_road'] ?? ''],
                    'l_border_road' => ['Loading Border', $json_sea_road['l_border_road'] ?? ''],
                    'l_date_road' => ['Loading Date', $json_sea_road['l_date_road'] ?? ''],
                    'truck_container' => ['Status', $json_sea_road['truck_container'] ?? ''],
                    'r_country_road' => ['Receiving Country', $json_sea_road['r_country_road'] ?? ''],
                    'r_border_road' => ['Receiving Border', $json_sea_road['r_border_road'] ?? ''],
                    'r_date_road' => ['Receiving Date', $json_sea_road['r_date_road'] ?? ''],
                    'd_date_road' => ['Delivery Date', $json_sea_road['d_date_road'] ?? ''],
                ]);
            }
        }

        $items_query = fetch('transaction_items', array('parent_id' => $id));

        $_fields['items'] = [];
        if ($items_query instanceof mysqli_result) {
            while ($row = $items_query->fetch_assoc()) {
                $_fields['items'][] = $row;
            }
        } else {
            $_fields['items'] = null;
        }
        $sum_query = "SELECT 
            SUM(qty_no) AS sum_qty_no, 
            SUM(qty_kgs) AS sum_qty_kgs, 
            SUM(total_kgs) AS sum_total_kgs, 
            SUM(empty_kgs) AS sum_empty_kgs, 
            SUM(total_qty_kgs) AS sum_total_qty_kgs, 
            SUM(net_kgs) AS sum_net_kgs, 
            SUM(weight) AS sum_weight, 
            SUM(total) AS sum_total, 
            SUM(amount) AS sum_amount, 
            SUM(final_amount) AS sum_final_amount 
            FROM transaction_items 
            WHERE parent_id = $id";

        $sum_result = mysqli_query($connect, $sum_query);
        if ($sum_result) {
            $_fields['items_sum'] = mysqli_fetch_assoc($sum_result);
        } else {
            $_fields['items_sum'] = null;
        }

        return $_fields;
    }
}

function transactionItemsSerial($parent_id, $p_s)
{
    $serial = fetch('transaction_items', array('parent_id' => $parent_id, 'p_s' => $p_s));
    $pd_serial = mysqli_num_rows($serial);
    return $pd_serial + 1;
}

function getPurchaseAgentSerial($khaata_id)
{
    //$serial = fetch('purchase_agents', array('d_id' => $pd_id, 'khaata_id' => $khaata_id));
    $serial = fetch('purchase_agents', array('khaata_id' => $khaata_id));
    $pa_serial = mysqli_num_rows($serial);
    return $pa_serial + 1;
}

function getBranchSerial($branch_id, $r_type)
{
    $serial = fetch('roznamchaas', array('branch_id' => $branch_id, 'r_type' => $r_type));
    $branch_serial = mysqli_num_rows($serial);
    return $branch_serial + 1;
}

function removeFilter($returnUrl)
{
    return '<a href="' . $returnUrl . '" class="btn btn-danger  btn-sm"><i class="fa fa-sync-alt"></i></a>';
}

function removeFilter2($returnUrl)
{
    return '<a href="' . $returnUrl . '" class="btn btn-outline-danger py-0 px-1 btn-sm"><i class="fa fa-sync-alt"></i>&nbsp;Clear</a>';
}

function phoneLInk($phone, $class = null)
{
    $p = '<a href="tel://' . $phone . '" class="' . $class . '">' . $phone . '</a>';
    return $p;
}

function aTag($href, $a_type, $class = null)
{
    $p = '<a href="tel://' . $phone . '" class="' . $class . '">' . $phone . '</a>';
    return $p;
}


function countGoods($type)
{
    $d = fetch('good_names', array('type' => $type));
    return mysqli_num_rows($d);
}

function buysDetailsBillNo($buys_id)
{
    $qq = fetch('buys_details', array('buys_id' => $buys_id));
    $c = mysqli_num_rows($qq);
    return $c + 1;
}

function sellBillNo($buys_id)
{
    $qq = fetch('buys_sold', array('buys_id' => $buys_id));
    $c = mysqli_num_rows($qq);
    return $c + 1;
}

function backUrl($backUrl, $label = null, $btn_class = null, $i_class = null)
{
    $text = $label ? $label : ' Back ';
    $btn_class = $btn_class ? $btn_class : ' btn-sm btn-secondary ';
    $i_class = $i_class ? $i_class : ' fa fa-arrow-left ';
    $url = '<a href="' . $backUrl . '" class="btn d-print-none ' . $btn_class . ' "><i class="' . $i_class . '"></i> ' . $text . '</a>';
    return $url;
}

function searchInput($autofocus = null, $input_class = null)
{
    $aa = $autofocus ? 'autofocus' : '';
    //$input = '<div class="email-search"><div class="position-relative">';
    //$input .= '<input type="text" id="tableFilter" ' . $aa . ' class="form-control" placeholder="Search... (f2)"><span class="bx bx-search font-size-18"></span>';
    //$input .= '</div></div>';
    $input = '<input type="text" id="tableFilter" ' . $aa . ' class="form-control ' . $input_class . '" placeholder="Search... (f2)">';
    return $input;
}

function addNew($url, $label = null, $btn_class = null, $icon = null)
{
    $text = $label ? $label : ' New';
    $f_icon = $icon ? $icon : "fa-plus";
    $a = '<a href="' . $url . '" class="text-nowrap btn btn-dark ' . $btn_class . '"><i class="fa ' . $f_icon . ' me-1"></i>' . $text . '</a>';
    return $a;
}

function UTPermissions($user_id)
{
    $ddd = fetch('users', array('id' => $user_id));
    $data = mysqli_fetch_assoc($ddd);
    $ut_permissions = $data['ut_permissions'];
    return $ut_permissions;
}

function UTRoznamcha($user_id, $ut_clearance_entry = null)
{
    $ddd = fetch('users', array('id' => $user_id));
    $data = mysqli_fetch_assoc($ddd);
    if ($ut_clearance_entry) {
        return $data['ut_permissions'];
    } else {
        return $data['ut_roznamcha'];
    }
}

function is_clearance_roznamcha_allowed($clearanceRoznamchaName, $user_id, $clearanceEntryName = null)
{
    if (SuperAdmin()) {
        return true;
    } else {
        if ($clearanceEntryName) {
            $perms = UTRoznamcha($user_id, $clearanceEntryName);
        } else {
            $perms = UTRoznamcha($user_id);
        }

        $url = './';
        if (empty($perms)) {
            return false;
        } else {
            $perms = json_decode($perms);
            $jsonPerms = implode(',', $perms);
            $jsonPerms = explode(',', $jsonPerms);
            if (in_array($clearanceRoznamchaName, $jsonPerms)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

function buyBalance($buys_id)
{
    global $connect;
    $totalBardana = 0;;
    $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_details WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsQ) > 0) {
        $detailSums = mysqli_fetch_assoc($detailsQ);
        $totalBardana += $detailSums['bardana_qtySum'];
    }

    return $totalBardana;
}

function sellBalance($buys_id)
{
    global $connect;
    $totalBardana = 0;
    $detailsSold = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_sold WHERE buys_id = '$buys_id'");
    //$detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_details WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsSold) > 0) {
        $detailSums = mysqli_fetch_assoc($detailsSold);
        $totalBardana += $detailSums['bardana_qtySum'];
    }

    return $totalBardana;
}

function buySellBalance($buys_id)
{
    //global $connect;
    $totalBardana = buyBalance($buys_id);
    $soldBardana = sellBalance($buys_id);
    $balance = $totalBardana - $soldBardana;
    return $balance;
    /*$balance = $totalBardana = $soldBardana = 0;
    $detailsQ = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum FROM buys_details WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsQ) > 0) {
        $detailSums = mysqli_fetch_assoc($detailsQ);
        $totalBardana = $detailSums['bardana_qtySum'];
    }
    $detailsSold = mysqli_query($connect, "SELECT SUM(bardana_qty) as bardana_qtySum2 FROM buys_sold WHERE buys_id = '$buys_id'");
    if (mysqli_num_rows($detailsSold) > 0) {
        $detailSums2 = mysqli_fetch_assoc($detailsSold);
        $soldBardana = $detailSums2['bardana_qtySum2'];
    }
    $balance = $totalBardana - $soldBardana;
    return $balance;*/
}

function monthNameENByNumber($number, $format = null)
{
    $ff = $format ? $format : 'F';
    $dateObj = DateTime::createFromFormat('!m', $number);
    $monthName = $dateObj->format($ff);
    return $monthName;
}

function monthNameURByNumber($number)
{
    $dateObj = DateTime::createFromFormat('!m', $number);
    $monthName = $dateObj->format('F');
    $nmeng = array('january', 'february', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $nmtur = array('جنوری', 'فروری', 'مارچ', 'اپریل', 'مئی', 'جون', 'جولائی', 'اگست', 'ستمبر', 'اکتوبر', 'نومبر', 'دسمبر');
    $dt = str_ireplace($nmeng, $nmtur, $monthName);
    return $dt;
}

function isDTKirayaAdded($maalId, $formName, $tlId = null)
{
    $valid['success'] = array('success' => false, 'output' => array());
    if ($tlId) {
        $ddd = fetch('dt_truck_maals2', array('dt_tl_id' => $tlId, 'form_name' => $formName));
    } else {
        $ddd = fetch('dt_truck_maals2', array('maal_id' => $maalId, 'form_name' => $formName));
    }
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        //$valid['output'] = $data;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function isKirayaAdded($maalId, $formName, $tlId = null)
{
    $valid['success'] = array('success' => false, 'output' => array());
    if ($tlId) {
        $ddd = fetch('imp_truck_maals2', array('imp_tl_id' => $tlId, 'form_name' => $formName));
    } else {
        $ddd = fetch('imp_truck_maals2', array('maal_id' => $maalId, 'form_name' => $formName));
    }
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        //$valid['output'] = $data;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function isImpExtraExpenseAdded($imp_tl_id, $form_name)
{
    $valid['success'] = array('success' => false, 'output' => array());
    $ddd = fetch('imp_truck_maals2', array('imp_tl_id' => $imp_tl_id, 'maal_id' => 0, 'form_name' => $form_name));
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        //$valid['output'] = $data;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function isUTExpenseAdded($bail_id, $expense_name)
{
    $valid['success'] = array('success' => false, 'output' => array());
    $ddd = fetch('ut_expenses', array('bail_id' => $bail_id, 'expense_name' => $expense_name));
    $data = mysqli_fetch_assoc($ddd);
    $c = mysqli_num_rows($ddd);
    if ($c > 0) {
        $valid['success'] = true;
        //$valid['output'] = $data;
        $valid['output'] = $data;
    } else {
        $valid['success'] = false;
    }
    return $valid;
}

function roznamchaAmount($khaata_id, $dr_cr = null)
{
    global $connect;
    $amount = 0;
    if ($khaata_id > 0) {
        $sql = "SELECT SUM(amount) as amount FROM `roznamchaas` WHERE `khaata_id` = '$khaata_id' ";
        if ($dr_cr) {
            $sql .= " AND dr_cr= '$dr_cr' ";
        }
        $q = mysqli_query($connect, $sql);
        $data = mysqli_fetch_assoc($q);
        $amount = $data['amount'];
    }
    return $amount;
}

function khaataExits($khaataId)
{
    global $connect;
    $c = getNumRows('roznamchaas', 'khaata_id', $khaataId);
    if ($c > 0) {
        return true;
    } else {
        return false;
    }
}

function areTransactionItemsAdded($parent_id): bool
{
    $data = fetch('transaction_items', array('parent_id' => $parent_id));
    if (mysqli_num_rows($data) > 0) {
        return true;
    } else {
        return false;
    }
}
function convertNumberToWords($number)
{
    $ones = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen'
    );

    $tens = array(
        2 => 'Twenty',
        3 => 'Thirty',
        4 => 'Forty',
        5 => 'Fifty',
        6 => 'Sixty',
        7 => 'Seventy',
        8 => 'Eighty',
        9 => 'Ninety'
    );

    $words = array();
    $integerPart = (int)abs($number);

    if ($integerPart >= 10000000) {
        $crore = (int)($integerPart / 10000000);
        $integerPart %= 10000000;
        $words[] = convertLessThanOneThousand($crore, $ones, $tens) . ' Crore';
    }

    if ($integerPart >= 100000) {
        $lakh = (int)($integerPart / 100000);
        $integerPart %= 100000;
        $words[] = convertLessThanOneThousand($lakh, $ones, $tens) . ' Lakh';
    }

    if ($integerPart >= 1000) {
        $thousand = (int)($integerPart / 1000);
        $integerPart %= 1000;
        $words[] = convertLessThanOneThousand($thousand, $ones, $tens) . ' Thousand';
    }

    if ($integerPart > 0) {
        $words[] = convertLessThanOneThousand($integerPart, $ones, $tens);
    }

    $result = implode(' ', $words);

    if ($number < 0) {
        $result = 'Minus ' . $result;
    }

    return $result . ' Only';
}
function convertLessThanOneThousand($num, $ones, $tens)
{
    $word = '';
    $hundreds = (int)($num / 100);
    $remainder = $num % 100;

    if ($hundreds > 0) {
        $word .= $ones[$hundreds] . ' Hundred ';
    }

    if ($remainder > 0) {
        if ($remainder < 20) {
            $word .= $ones[$remainder];
        } else {
            $ten = (int)($remainder / 10);
            $unit = $remainder % 10;
            $word .= $tens[$ten];
            if ($unit > 0) {
                $word .= ' ' . $ones[$unit];
            }
        }
    }

    return trim($word);
}

function acc_bank_details($accNo)
{
    global $connect;
    $accNo = strtoupper($accNo);
    $data = mysqli_fetch_assoc($connect->query("SELECT bank_details FROM khaata WHERE UPPER(khaata_no) = UPPER('$accNo')")) ?? [];
    return json_decode($data['bank_details'] ?? '[]', true);
}

function isSaleDetailsAdded($saleId)
{
    global $connect;
    $data = fetch('sale_details', array('parent_id' => $saleId));
    if (mysqli_num_rows($data) > 0) {
        return true;
    } else {
        return false;
    }
}

function isPurchasePaysAdded($purchaseId, $payment_type)
{
    global $connect;
    $data = fetch('purchase_pays', array('purchase_id' => $purchaseId, 'type' => $payment_type));
    //$data = mysqli_query($connect, "SELECT * FROM `purchase_details` WHERE parent_id= '$purchaseId' AND kgs >0");
    if (mysqli_num_rows($data) > 0) {
        return true;
    } else {
        return false;
    }
}

function totalPurchasesDone($khaata_no)
{
    $sum = 0;
    $dd = fetch('purchases', array('purchaser_khaata_no' => $khaata_no));
    if (mysqli_num_rows($dd) > 0) {
        while ($pp = mysqli_fetch_assoc($dd)) {
            $purchase_id = $pp['id'];
            $data = fetch('purchase_details', array('parent_id' => $purchase_id));
            while ($datum = mysqli_fetch_assoc($data)) {
                $sum += $datum['total_kgs'];
            }
        }
    }
    return $sum;
}

function totalPurchaseAmount($purchaseId)
{
    $sum = 0;
    $data = fetch('transaction_items', array('parent_id' => $purchaseId));
    if (mysqli_num_rows($data) > 0) {
        while ($datum = mysqli_fetch_assoc($data)) {
            $sum += $datum['final_amount'];
        }
    }
    return $sum;
}

function totalSaleAmount($saleId)
{
    $sum = 0;
    $data = fetch('sale_details', array('parent_id' => $saleId));
    if (mysqli_num_rows($data) > 0) {
        while ($datum = mysqli_fetch_assoc($data)) {
            $sum += $datum['final_amount'];
        }
    }
    return $sum;
}

function totalSalesDone($khaata_no)
{
    $sum = 0;
    $ss = fetch('purchase_sales', array('seller_khaata_no' => $khaata_no));
    if (mysqli_num_rows($ss) > 0) {
        while ($pp = mysqli_fetch_assoc($ss)) {
            $sale_id = $pp['id'];
            $data = fetch('purchase_sale_details', array('parent_id' => $sale_id));
            while ($datum = mysqli_fetch_assoc($data)) {
                $sum += $datum['total_kgs'];
            }
        }
    }
    return $sum;
}


function purchasePrintBtn($purchaseId, $action)
{
    $btn = '<a href="print/purchase-booking?p_id=' . $purchaseId . '&action=' . $action . '" target="_blank" class="btn btn-outline-primary  btn-sm py-0 small w-100"> <i class="fa fa-print"></i> Print</a>';
    return $btn;
}

function purchaseKhaata($khaata_id, $khaata_no = null)
{
    $data = khaataSingle($khaata_id);
    $khaata_no = isset($khaata_no) ? $khaata_no : $data['khaata_no'];
    $array = array(array('t' => 'A/c.', 'v' => $khaata_no, 'wrap_class' => ''), array('t' => 'Name', 'v' => $data['khaata_name'], 'wrap_class' => ''), array('t' => 'Company', 'v' => $data['comp_name'], 'wrap_class' => ''));
    if (!SuperAdmin()) {
        $array = array(array('t' => 'Company', 'v' => $data['comp_name'], 'wrap_class' => ''));
    }
    $info = '';
    foreach ($array as $item) {
        $info .= '<span class="' . $item['wrap_class'] . '"><span class="text-muted">' . $item['t'] . '</span><span class="text-dark font-size-11 bold">' . $item['v'] . '</span></span> ';
    }
    return $info;
}

function href_link($link, $link_text, $target = null, $a_class = null, $icon_class = null, $label = null, $title = null): string
{
    $a = '';
    $a_class = !$a_class ? '' : $a_class;
    $title = !$title ? '' : $title;
    $target = !$target ? '_self' : $target;
    if ($link_text != "") {
        $a = '<a href="' . $link . '" class="' . $a_class . '" target="' . $target . '" title="' . $title . '"> ';
        if ($label) {
            $a = '<b>' . $label . '</b>' . $a;
        }
        if ($icon_class) {
            $a .= '<i class="' . $icon_class . '"></i> ';
        }
        $a .= $link_text;
        $a .= '</a>';
    }
    return $a;
}

function href_link2($link_type, $link, $link_text, $show_label = false, $title = null, $target = null, $a_class = null, $icon_class = null): string
{
    $protocol = $a = $label = $href = '';
    $a_class = !$a_class ? '' : $a_class;
    $title = !$title ? '' : $title;
    $target = !$target ? '_self' : $target;

    switch ($link_type) {
        case 'Email':
            $href = 'href="mailto://' . $link . '"';
            $label = 'E.';
            break;
        case 'WhatsApp':
            $href = 'href="https://wa.me/' . $link . '"';
            $label = 'WA.';
            break;
        case 'Phone':
            $href = 'href="tel://' . $link . '"';
            $label = 'P.';
            break;
        case 'Mobile':
            $href = 'href="tel://' . $link . '"';
            $label = 'M.';
            break;
        case 'Office':
            $href = 'href="tel://' . $link . '"';
            $label = 'Off.';
            break;
        case 'Other':
            $label = 'Other';
            break;
        case 'NTN':
            $label = 'NTN';
            break;
        case 'FSSAI':
            $label = 'FSSAI';
            break;
        case 'VAT':
            $label = 'VAT.';
            break;
        case 'License':
            $label = 'LIC.';
            break;
        case 'IEC':
            $label = 'IEC';
            break;
        case 'ST':
            $label = 'ST';
            break;
        case 'GST':
            $label = 'GST';
            break;
        default:
            break;
    }
    if ($link_text != "") {
        $a = '<a ' . $href . ' class="' . $a_class . '" target="' . $target . '" title="' . $title . '"> ';
        if ($show_label) {
            $a .= '<b>' . $label . '</b>';
        }
        if ($icon_class) {
            $a .= '<i class="' . $icon_class . '"></i> ';
        }
        $a .= $link_text;
        $a .= '</a>';
    }
    return $a;
}

function getAllotNames()
{
    global $connect;
    $ss = "SELECT allot_name FROM purchase_details WHERE allot_name IS NOT NULL
        UNION
        SELECT allot_name FROM sale_details WHERE allot_name IS NOT NULL";
    $result = mysqli_query($connect, $ss);
    $allotNames = array();
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['allot_name'] != '') {
            $allotNames[] = $row['allot_name'];
        }
    }
    $allotNames = array_unique($allotNames);
    return $allotNames;
}

function displayKhaataDetails($details, $get_array = false)
{
    $output = '';
    $arr = array();
    if (!empty($details['indexes'])) {
        $indexes = json_decode($details['indexes']);
        $vals = json_decode($details['vals']);
        if (is_array($indexes) && is_array($vals)) {
            $count = min(count($indexes), count($vals));
            for ($i = 0; $i < $count; $i++) {
                $key = $indexes[$i];
                $value = $vals[$i];
                if ($get_array) {
                    $arr[$key] = $value;
                } else {
                    //echo '<div class="col">' . staticTypeDetails($key) . '<span class="fw-normal">' . $value . '</span></div>';
                    $output .= '<div class="col">';
                    $output .= href_link2($key, $value, $value, true, $key, '', 'text-dark fw-normal');
                    $output .= '<br>';
                    $output .= '</div>';
                }
            }
        }
    }
    $return = $get_array ? $arr : $output;
    return $return;
}

function khaataDetails($id)
{
    $kdd = fetch('khaata_details', array('id' => $id));
    if (mysqli_num_rows($kdd) > 0) {
        return mysqli_fetch_assoc($kdd);
    }
}

function khaataDetailsData($khaata_details_id)
{
    $kd = fetch('khaata_details', array('id' => $khaata_details_id));

    if (mysqli_num_rows($kd) > 0) {
        return mysqli_fetch_assoc($kd);
    } else {
        return false;
    }
}

function shortName($str)
{
    switch ($str) {
        case 'Bank':
            return 'BK';
        case 'Business':
            return 'BS';
        case 'Cash':
            return 'CS';
        case 'Bill':
            return 'BL';
        default:
            return $str;
    }
}

function badge($str, $bg)
{
    return '<span class="badge bg-' . $bg . '">' . $str . '</span>';
}

function getSeaRoadArray($transaction_id)
{
    $query = fetch('transactions', array('id' => $transaction_id));
    $record = mysqli_fetch_assoc($query);
    if (!empty($record['sea_road'])) {
        return $record['sea_road'];
    }
}

function purchaseSpecificData($purchaseId, $type, $GetValue = 'final_amount', $isCommission = false)
{
    $query = fetch('transactions', array('id' => $purchaseId));
    $purchase = mysqli_fetch_assoc($query);
    if ($isCommission) {
        $pur_d_q = fetch('commission_items', array('sale_id' => $purchaseId));
    } else {
        $pur_d_q = fetch('transaction_items', array('parent_id' => $purchaseId));
    }
    $count_pd = mysqli_num_rows($pur_d_q);
    $data = '';
    switch ($type) {
        case 'purchase_type':
            //$bgg = $purchase['type'] == "local" ? 'badge-soft-danger' : 'badge-soft-success';
            if ($purchase['type'] == "local") {
                $bgg = 'bg-danger';
            } elseif ($purchase['type'] == "booking") {
                $bgg = 'bg-dark';
            } else {
                $bgg = 'bg-secondary';
            }
            $data .= '<span class="badge text-uppercase  ' . $bgg . ' ">' . $purchase['type'] . '</span>';
            break;
        case 'purchase_rows':
            $data = $count_pd;
            break;
        case 'product_details':
            if (areTransactionItemsAdded($purchaseId)) {
                $qty_no = $total_kgs = $amount = $total_amount = 0;
                $goods = $size = $brand = $origin = array();
                $curr1 = $curr2 = '';
                while ($pd_data = mysqli_fetch_assoc($pur_d_q)) {
                    $qty_no += $pd_data['qty_no'];
                    $total_kgs += $pd_data['total_kgs'];
                    $amount += $pd_data['amount'];
                    $total_amount += $pd_data['final_amount'];
                    $goods[] = goodsName($pd_data['goods_id']);
                    $size[] = $pd_data['size'];
                    $brand[] = $pd_data['brand'];
                    $origin[] = $pd_data['origin'];
                    $curr1 = $pd_data['currency1'];
                    $curr2 = $pd_data['currency2'];
                }
                $data = array('Qty' => $qty_no, 'KGs' => $total_kgs, 'Amount' => $amount, 'Final' => $total_amount, 'Goods' => $goods, 'Size' => $size, 'Brand' => $brand, 'Origin' => $origin, 'curr1' => $curr1, 'curr2' => $curr2);
            }
            break;
        case 'adv':
            $data = purchasePaysArray($purchaseId, 'p_adv');
            break;
        case 'crdt':
            $data = purchasePaysArray($purchaseId, 'p_crdt');
            break;
        case 'adv_paid_total':
            $data = purchasePaysArray($purchaseId, 'p_adv', true, $GetValue);
            break;
        case 'crdt_paid_total':
            $data = purchasePaysArray($purchaseId, 'p_crdt', true, $GetValue);
            break;
        case 'rem_paid_total':
            $data = purchasePaysArray($purchaseId, 'p_rem', true, $GetValue);
            break;
        case 'rem':
            $data = purchasePaysArray($purchaseId, 'p_rem');
            break;
        case 'full_payment':
            /*get data from json_loading col. to check if container etc. are saved in purchase-loading-add.php file*/
            $dataf = fetch('purchase_details', array('parent_id' => $purchaseId));
            $x = 0;
            if (mysqli_num_rows($dataf) > 0) {
                while ($d = mysqli_fetch_assoc($dataf)) {
                    $json_loading = json_decode($d['json_loading']);
                    if (!empty($json_loading)) {
                        $fp_array = array(
                            array('i' => '0', 't' => 'Loading Country', 'v' => $json_loading->loading_country, 'wrap_class' => 'text-nowrap'),
                            array('i' => '1', 't' => 'Port', 'v' => $json_loading->loading_port, 'wrap_class' => 'text-nowrap'),
                            array('i' => '2', 't' => 'Date', 'v' => $json_loading->loading_date, 'wrap_class' => 'text-nowrap'),
                            array('i' => '3', 't' => 'Receiving Country', 'v' => $json_loading->receiving_country, 'wrap_class' => 'text-nowrap'),
                            array('i' => '4', 't' => 'Receiving', 'v' => $json_loading->receiving_port, 'wrap_class' => 'text-nowrap'),
                            array('i' => '5', 't' => 'Date', 'v' => $json_loading->receiving_date, 'wrap_class' => 'text-nowrap'),
                            array('i' => '6', 't' => 'Shipping Lane Phone', 'v' => $json_loading->ship_phone, 'wrap_class' => 'text-nowrap'),
                            array('i' => '7', 't' => 'Email', 'v' => $json_loading->ship_email, 'wrap_class' => 'text-nowrap'),
                            array('i' => '8', 't' => 'WhatsApp', 'v' => $json_loading->ship_wa, 'wrap_class' => 'text-nowrap'),
                            array('i' => '9', 't' => 'Company', 'v' => $json_loading->ship_comp, 'wrap_class' => 'text-nowrap'),
                            array('i' => '10', 't' => 'Container Name', 'v' => $json_loading->container_name, 'wrap_class' => 'text-nowrap'),
                            array('i' => '11', 't' => 'No', 'v' => $json_loading->container_no, 'wrap_class' => 'text-nowrap'),
                            array('i' => '12', 't' => 'Size', 'v' => $json_loading->container_size, 'wrap_class' => 'text-nowrap'),
                        );
                    } else {
                        $fp_array = array();
                    }
                }
                foreach ($fp_array as $item) {
                    $data .= '<span class="' . $item['wrap_class'] . '"><span class="text-muted">' . $item['t'] . '</span><span class="text-dark font-size-12 bold">' . $item['v'] . '</span></span> ';
                }
            }
            break;
        default:
            break;
    }
    return $data;
}

function getCompanyName($kd_id)
{
    global $connect;
    $kd_id = mysqli_real_escape_string($connect, $kd_id);
    $query = "SELECT JSON_EXTRACT(json_data, '$.company_name') as c_name FROM khaata_details WHERE id='$kd_id'";
    $result = mysqli_query($connect, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $c_name = mysqli_fetch_assoc($result);
        return $c_name['c_name'] !== null ? str_replace('"', '', $c_name['c_name']) : null;
    }
    return null;
}
function purchasePaysArray($purchaseId, $type, $final_amount = false, $GetValue = 'final_amount')
{
    $purchase_pays = fetch('purchase_pays', array('purchase_id' => $purchaseId, 'type' => $type));
    $x = 0;
    $percent_array = array();
    if ($final_amount) {
        if (mysqli_num_rows($purchase_pays) > 0) {
            while ($d = mysqli_fetch_assoc($purchase_pays)) {
                $x += $d[$GetValue];
            }
        }
        return $x;
    } else {
        while ($d = mysqli_fetch_assoc($purchase_pays)) {
            $percent_array[] = array(
                'id' => $d['id'],
                'dr_khaata_no' => $d['dr_khaata_no'],
                'dr_khaata_id' => $d['dr_khaata_id'],
                'cr_khaata_no' => $d['cr_khaata_no'],
                'cr_khaata_id' => $d['cr_khaata_id'],
                'currency1' => $d['currency1'],
                'amount' => $d['amount'],
                'currency2' => $d['currency2'],
                'rate' => $d['rate'],
                'opr' => $d['opr'],
                'final_amount' => $d[$GetValue],
                'transfer_date' => $d['transfer_date'],
                'report' => $d['report'],
                'created_at' => $d['created_at'],
                'created_by' => $d['created_by'],
                'updated_at' => $d['updated_at'],
                'updated_by' => $d['updated_by']
            );
        }
        return $percent_array;
    }
}

function saleSpecificData($saleId, $type)
{
    $query = fetch('sales', array('id' => $saleId));
    $purchase = mysqli_fetch_assoc($query);
    $pur_d_q = fetch('sale_details', array('parent_id' => $saleId));
    $count_pd = mysqli_num_rows($pur_d_q);
    $data = '';
    switch ($type) {
        case 'sale_type':
            if ($purchase['type'] == "local") {
                $bgg = 'badge-soft-danger';
            } elseif ($purchase['type'] == "booking") {
                $bgg = 'badge-soft-success';
            } else {
                $bgg = 'badge-soft-dark';
            }
            //$bgg = $purchase['type'] == "local" ? 'badge-soft-danger' : $purchase['type'] == "booking" ? 'badge-soft-success' : 'badge-soft-info';
            $data .= '<span class="badge text-uppercase  ' . $bgg . ' ">' . $purchase['type'] . '</span>';
            break;
        case 'transfer_type':
            if ($purchase['transfer'] == 1) {
                $transfer_name = 'Advance';
                $bgg = 'badge-soft-dark';
            } elseif ($purchase['transfer'] == 2) {
                $transfer_name = 'Full Pay';
                $bgg = 'badge-soft-primary';
            } else {
                $bgg = $transfer_name = '';
            }
            $data .= '<span class="badge text-uppercase  ' . $bgg . ' ">' . $transfer_name . '</span>';
            break;
        case 'sale_rows':
            $data = $count_pd;
            break;
        case 'product_details':
            if (isSaleDetailsAdded($saleId)) {
                $qty_no = $total_kgs = $amount = $total_amount = 0;
                $goods = $size = $brand = $origin = $allot = array();
                $curr1 = $curr2 = '';
                while ($pd_data = mysqli_fetch_assoc($pur_d_q)) {
                    $qty_no += $pd_data['qty_no'];
                    $total_kgs += $pd_data['total_kgs'];
                    $amount += $pd_data['amount'];
                    $total_amount += $pd_data['final_amount'];
                    $goods[] = goodsName($pd_data['goods_id']);
                    $size[] = $pd_data['size'];
                    $brand[] = $pd_data['brand'];
                    $origin[] = $pd_data['origin'];
                    $allot[] = $pd_data['allot_name'];
                    $curr1 = $pd_data['currency1'];
                    $curr2 = $pd_data['currency2'];
                }
                $data = array(
                    'Qty' => $qty_no,
                    'KGs' => $total_kgs,
                    'Amount' => $amount,
                    'Final' => $total_amount,
                    'Goods' => $goods,
                    'Size' => $size,
                    'Brand' => $brand,
                    'Origin' => $origin,
                    'Allot' => $allot,
                    'curr1' => $curr1,
                    'curr2' => $curr2
                );
            }
            break;
        case 'adv':
            $data = salePaysArray($saleId, 's_adv');
            break;
        case 'adv_paid_total':
            $data = salePaysArray($saleId, 's_adv', true);
            break;
        case 'rem_paid_total':
            $data = salePaysArray($saleId, 's_rem', true);
            break;
        case 'rem':
            $data = salePaysArray($saleId, 's_rem');
            break;
        default:
            break;
    }
    return $data;
}

function salePaysArray($saleId, $type, $final_amount = false)
{
    $purchase_pays = fetch('sale_pays', array('sale_id' => $saleId, 'type' => $type));
    $x = 0;
    $percent_array = array();
    if ($final_amount) {
        if (mysqli_num_rows($purchase_pays) > 0) {
            while ($d = mysqli_fetch_assoc($purchase_pays)) {
                $x += $d['final_amount'];
            }
        }
        return $x;
    } else {
        while ($d = mysqli_fetch_assoc($purchase_pays)) {
            $percent_array[] = array(
                'id' => $d['id'],
                'dr_khaata_no' => $d['dr_khaata_no'],
                'dr_khaata_id' => $d['dr_khaata_id'],
                'cr_khaata_no' => $d['cr_khaata_no'],
                'cr_khaata_id' => $d['cr_khaata_id'],
                'currency1' => $d['currency1'],
                'amount' => $d['amount'],
                'currency2' => $d['currency2'],
                'rate' => $d['rate'],
                'opr' => $d['opr'],
                'final_amount' => $d['final_amount'],
                'transfer_date' => $d['transfer_date'],
                'report' => $d['report'],
                'created_at' => $d['created_at'],
                'created_by' => $d['created_by'],
                'updated_at' => $d['updated_at'],
                'updated_by' => $d['updated_by']
            );
        }
        return $percent_array;
    }
}

function isSaleBookingDetailsAdded($purchaseId)
{
    global $connect;
    //$data = fetch('purchase_details', array('parent_id' => $purchaseId));
    $data = mysqli_query($connect, "SELECT * FROM `sale_details` WHERE parent_id= '$purchaseId' AND kgs >0");
    if (mysqli_num_rows($data) > 0) {
        return true;
    } else {
        return false;
    }
}

function bailDetails($details_id, $purchaseSale, $ps_type = null): array
{
    $allowedValues = ['purchase', 'sale'];
    if (!in_array($purchaseSale, $allowedValues)) {
        throw new InvalidArgumentException('$purchaseSale must be one of the allowed values.');
    } else {
        if ($purchaseSale == 'purchase') {
            $query = fetch('purchase_details', array('id' => $details_id));
        } else {
            $query = fetch('sale_details', array('id' => $details_id));
        }
        $details = mysqli_fetch_assoc($query);
        $bail_json = json_decode($details['bail_json']);
        if ($ps_type && $ps_type == 'local') {
            $data = array(
                'bail_report' => '',
                'loading_date' => date('Y-m-d'),
                'receiving_date' => date('Y-m-d'),
                'driver_name' => '',
                'driver_phone' => '',
                'truck_no' => ''
            );
            if (!empty($bail_json)) {
                $data = array(
                    'bail_report' => $bail_json->bail_report,
                    'loading_date' => $bail_json->loading_date,
                    'receiving_date' => $bail_json->receiving_date,
                    'driver_name' => $bail_json->driver_name,
                    'driver_phone' => $bail_json->driver_phone,
                    'truck_no' => $bail_json->truck_no
                );
            }
        } else {
            $data = array(
                'bail_report' => '',
                'loading_country' => '',
                'loading_port' => '',
                'receiving_country' => '',
                'receiving_port' => '',
                'loading_date' => date('Y-m-d'),
                'receiving_date' => date('Y-m-d'),
                'freight' => '',
                'bail_no' => '',
                'container_name' => '',
                'container_no' => '',
                'container_size' => '',
                'ship_phone' => '',
                'ship_email' => '',
                'ship_wa' => '',
                'ship_comp' => '',
                'loading_shipper_address' => '',
                'receiving_shipper_address' => ''
            );
            if (!empty($bail_json)) {
                $data = array(
                    'bail_report' => $bail_json->bail_report,
                    'loading_country' => $bail_json->loading_country,
                    'loading_port' => $bail_json->loading_port,
                    'receiving_country' => $bail_json->receiving_country,
                    'receiving_port' => $bail_json->receiving_port,
                    'loading_date' => $bail_json->loading_date,
                    'receiving_date' => $bail_json->receiving_date,
                    'freight' => $bail_json->freight,
                    'bail_no' => $bail_json->bail_no,
                    'container_name' => $bail_json->container_name,
                    'container_no' => $bail_json->container_no,
                    'container_size' => $bail_json->container_size,
                    'ship_phone' => $bail_json->ship_phone,
                    'ship_email' => $bail_json->ship_email,
                    'ship_wa' => $bail_json->ship_wa,
                    'ship_comp' => $bail_json->ship_comp,
                    'loading_shipper_address' => $bail_json->loading_shipper_address,
                    'receiving_shipper_address' => $bail_json->receiving_shipper_address
                );
            }
        }
    }
    return $data;
}

function loadingData($purchase_details_id)
{
    $query = fetch('purchase_details', array('id' => $purchase_details_id));
    $details = mysqli_fetch_assoc($query);
    $p_id = $details['parent_id'];
    $pur_q = fetch('purchases', array('id' => $p_id));
    $p_data = mysqli_fetch_assoc($pur_q);
    $json_loading = json_decode($details['json_loading']);
    $data = array(
        'goods_name' => $details['goods_name'],
        'brand' => $details['brand'],
        'origin' => $details['origin'],
        'size' => $details['size'],
        'qty_name' => $details['qty_name'],
        'qty_no' => $details['qty_no'],
        'kgs' => $details['kgs'],
        'total_kgs' => $details['total_kgs'],
        'saaf_wt' => $details['saaf_wt'],
        'empty_wt' => $details['empty_wt'],
        'total_empty_wt' => $details['total_empty_wt'],
        'loading_report' => $p_data['loading_report'],
        'loading_country' => $p_data['loading_country'],
        'loading_port' => $p_data['loading_port'],
        'receiving_country' => $p_data['receiving_country'],
        'receiving_port' => $p_data['receiving_port'],
        'bail_no' => '',
        'bail_report' => '',
        'loading_shipper_address' => '',
        'receiving_shipper_address' => '',
        'ship_phone' => '',
        'ship_email' => '',
        'ship_wa' => '',
        'ship_comp' => '',
        'container_name' => '',
        'container_no' => '',
        'container_size' => '',
        'loading_date' => $p_data['loading_date'],
        'receiving_date' => '',
        'freight' => '',
        'rowColor' => 'bg-danger bg-opacity-25',
    );
    if (!empty($json_loading)) {
        $data['qty_no'] = $json_loading->qty_no;
        $data['qty_name'] = $json_loading->qty_name;
        $data['kgs'] = $json_loading->kgs;
        $data['total_kgs'] = $json_loading->total_kgs;
        $data['saaf_wt'] = $json_loading->saaf_wt;
        $data['empty_wt'] = $json_loading->empty_wt;
        $data['total_empty_wt'] = $json_loading->total_empty_wt;
        if (isset($json_loading->loading_report)) {
            $data['loading_report'] = $json_loading->loading_report;
        }
        $data['loading_country'] = $json_loading->loading_country;
        $data['loading_port'] = $json_loading->loading_port;
        $data['loading_date'] = $json_loading->loading_date;
        $data['receiving_country'] = $json_loading->receiving_country;
        $data['receiving_port'] = $json_loading->receiving_port;
        $data['receiving_date'] = $json_loading->receiving_date;
        $data['bail_no'] = $json_loading->bail_no;
        if (isset($json_loading->bail_report)) {
            $data['bail_report'] = $json_loading->bail_report;
        }
        $data['freight'] = $json_loading->freight;
        $data['ship_phone'] = $json_loading->ship_phone;
        $data['ship_email'] = $json_loading->ship_email;
        $data['ship_wa'] = $json_loading->ship_wa;
        $data['ship_comp'] = $json_loading->ship_comp;
        $data['container_name'] = $json_loading->container_name;
        $data['container_no'] = $json_loading->container_no;
        $data['container_size'] = $json_loading->container_size;
        $data['loading_shipper_address'] = $json_loading->loading_shipper_address;
        $data['receiving_shipper_address'] = $json_loading->receiving_shipper_address;
        $data['rowColor'] = '';
    }
    return $data;
}

function exportTransitData($purchase_details_id)
{
    $query = fetch('purchase_details', array('id' => $purchase_details_id));
    $details = mysqli_fetch_assoc($query);
    $json = json_decode($details['json_export_transit']);
    $data = array();
    if (!empty($json)) {
        $data['port_receiving_date'] = $json->port_receiving_date;
        $data['clearance_date'] = $json->clearance_date;
        $data['qty_name'] = $json->qty_name;
        $data['qty_no'] = $json->qty_no;
        $data['kgs'] = $json->kgs;
        $data['total_kgs'] = $json->total_kgs;
        $data['empty_wt'] = $json->empty_wt;
        $data['total_empty_wt'] = $json->total_empty_wt;
        $data['saaf_wt'] = $json->saaf_wt;
        $data['vehicle_no'] = $json->vehicle_no;
        $data['container_no'] = $json->container_no;
        $data['id_no'] = $json->id_no;
        $data['id_date'] = $json->id_date;
        $data['demerge_details'] = $json->demerge_details;
        $data['shipping_receiving_date'] = $json->shipping_receiving_date;
        $data['transfer_border_name'] = $json->transfer_border_name;
        $data['agent_name_address'] = $json->agent_name_address;
    }
    return $data;
}


function importTransitData($purchase_details_id)
{
    $query = fetch('purchase_details', array('id' => $purchase_details_id));
    $details = mysqli_fetch_assoc($query);
    $json = json_decode($details['json_import_transit']);
    $data = array();
    if (!empty($json)) {
        $data['port_receiving_date'] = $json->port_receiving_date;
        $data['clearance_date'] = $json->clearance_date;
        $data['qty_name'] = $json->qty_name;
        $data['qty_no'] = $json->qty_no;
        $data['kgs'] = $json->kgs;
        $data['total_kgs'] = $json->total_kgs;
        $data['empty_wt'] = $json->empty_wt;
        $data['total_empty_wt'] = $json->total_empty_wt;
        $data['saaf_wt'] = $json->saaf_wt;
        $data['vehicle_no'] = $json->vehicle_no;
        $data['container_no'] = $json->container_no;
        $data['id_no'] = $json->id_no;
        $data['id_date'] = $json->id_date;
        $data['demerge_details'] = $json->demerge_details;
        $data['shipping_receiving_date'] = $json->shipping_receiving_date;
        $data['transfer_border_name'] = $json->transfer_border_name;
        $data['agent_name_address'] = $json->agent_name_address;
    }
    return $data;
}

function importExportTransitDataView(array $array, $edit_href = null)
{
    $output = '';
    if (!empty($array)) {
        $output .= '<span class="text-white bg-secondary"> Filled form </span> ';
        if ($edit_href) {
            $output .= '<a href="' . $edit_href . '" class="btn-link text-nowrap"> Edit </a>';
        }
        $output .= 'ID#<span class="text-dark bold text-nowrap">' . $array['id_no'] . '</span>';
        $output .= '<br>Port&nbsp;Receiving<span class="text-dark bold text-nowrap">' . $array['port_receiving_date'] . '</span>';
        $output .= '<br>Clearance<span class="text-dark bold text-nowrap">' . $array['clearance_date'] . '</span>';
        $output .= '<br>Shipping&nbsp;Receiving<span class="text-dark bold text-nowrap">' . $array['shipping_receiving_date'] . '</span>';
        $output .= '<br>Border<span class="text-dark bold text-nowrap">' . $array['transfer_border_name'] . '</span>';
        $output .= '<br>Agent<span class="text-dark bold text-nowrap">' . $array['agent_name_address'] . '</span>';
    }
    return $output;
}

function importExportAccountView($khaata_id, $edit_href = null)
{
    $output = '';
    $acc = khaataSingle($khaata_id);
    $output .= '<i class="mdi mdi-check-all text-primary"></i>Agent A/c.<span class="text-dark bold">' . $acc['khaata_no'] . '</span>';
    $output .= '<br>Comapny<span class="text-dark bold">' . $acc['comp_name'] . '</span>';
    if ($edit_href) {
        $output .= '<br><a href="' . $edit_href . '" class="btn-link">Edit</a>';
    }
    return $output;
}

function showArrayData($form, $key, $default_value)
{
    //return isset($form[$key]) ? $form[$key] : $default_value;
    return $form[$key] ?? $default_value;
}

/*function showArrayData($value, $default_value, $form)
{
    if (isset($value)) {
        return $value;
    } else {
        return $default_value;
    }
}*/
function purchase_agent_data($pur_sale, $pd_id, $type = null)
{
    $aa = array();
    if ($type) {
        $nn = array('pur_sale' => $pur_sale, 'd_id' => $pd_id, 'type' => $type);
    } else {
        $nn = array('pur_sale' => $pur_sale, 'd_id' => $pd_id);
    }
    $data = fetch('purchase_agents', $nn);
    if (mysqli_num_rows($data) > 0) {
        $aa = mysqli_fetch_assoc($data);
    }
    return $aa;
}

function fetchTableAssoc($id, $table_name)
{
    $aa = array();
    $data = fetch($table_name, array('id' => $id));
    if (mysqli_num_rows($data) > 0) {
        $aa = mysqli_fetch_assoc($data);
    }
    return $aa;
}

function khaataSingle($khaataId, $getByKhaataNo = false)
{
    if ($getByKhaataNo) {
        $data = fetch('khaata', array('khaata_no' => $khaataId));
    } else {
        $data = fetch('khaata', array('id' => $khaataId));
    }
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function khaataShipper($khaataNo)
{
    $data = fetch('shippers', array('khaata_no' => $khaataNo));
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function staticTypeDetails($type_name)
{
    $s = fetch('static_types', array('type_name' => $type_name));
    if (mysqli_num_rows($s) > 0) {
        $data = mysqli_fetch_assoc($s);
        return $data['details'];
    } else {
        return $type_name;
    }
}

function getMaalSerial($imp_truck_loading_id)
{
    $d = fetch('imp_truck_maals', array('imp_tl_id' => $imp_truck_loading_id));
    return mysqli_num_rows($d) + 1;
}

function getAutoIncrement($tableName)
{
    global $dbname;
    global $connect;
    $data = mysqli_query($connect, "SELECT AUTO_INCREMENT FROM information_schema.TABLES
WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$tableName'");
    $datum = mysqli_fetch_assoc($data);
    return $datum['AUTO_INCREMENT'];
}

function VAT_Account($column = null)
{
    $dd = fetch('business_settings', array('id' => 1));
    $datum = mysqli_fetch_assoc($dd);
    $vat_acc = json_decode($datum['vat_acc'], true);
    if ($column) {
        return $vat_acc[$column];
    } else {
        return $vat_acc;
    }
}

function business_setting()
{
    global $connect;
    $bs = mysqli_query($connect, "SELECT * FROM `business_settings` WHERE id =1");
    $bSetting = '';
    if (mysqli_num_rows($bs) > 0) {
        $bSetting = mysqli_fetch_assoc($bs);
        $siteurl = $bSetting['siteurl'];
        $sitename = $bSetting['sitename'];
        $sitedescription = $bSetting['sitedescription'];
        $copyRight = $bSetting['copy'];
    }
    return $bSetting;
}

function userRole($role)
{
    $span = '';
    if ($role == "admin") {
        $span = '<span class="badge bg-dark">ایڈمن</span>';
    }
    if ($role == "manager") {
        $span = '<span class="badge bg-danger">مینیجر</span>';
    }
    if ($role == "munshi") {
        $span = '<span class="badge bg-success">منشی</span>';
    }
    if ($role == "staff") {
        $span = '<span class="badge bg-secondary">ملازم</span>';
    }
    if ($role == "agent") {
        $span = '<span class="badge bg-light border border-secondary text-dark">کلئیرنگ ایجنٹ</span>';
    }
    return $span;
}

function fetchAndDisplayOptionsByGoodsID($goods_id, $columnName)
{
    global $connect;
    $query = "SELECT DISTINCT $columnName FROM `purchase_details` WHERE goods_id = '$goods_id'";
    $result = mysqli_query($connect, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row[$columnName] . '">' . $row[$columnName] . '</option>';
        }
    } else {
        echo '<option value=""></option>';
    }
}

function fetchAndDisplayPurchaseOptionsByGoodsID($goods_id, $column_name, $get_array = false)
{
    global $connect;
    $output = '';
    $arrr = array();
    $purchase_details = mysqli_query($connect, "SELECT DISTINCT parent_id FROM `purchase_details` WHERE goods_id= '$goods_id'");
    if (mysqli_num_rows($purchase_details) > 0) {
        while ($pd = mysqli_fetch_assoc($purchase_details)) {
            $p_id = $pd['parent_id'];
            $purchase = fetchTableAssoc($p_id, 'purchases');
            if ($get_array) {
                $arrr[] = $purchase[$column_name];
            } else {
                if (!empty($purchase)) {
                    $output .= '<option value="' . $purchase[$column_name] . '">' . $purchase[$column_name] . '</option>';
                }
            }
        }
    }
    if ($get_array) {
        $arrr = array_map('strtolower', $arrr);
        $arrr = array_unique($arrr);
        return $arrr;
    } else {
        return $output;
    }
}

function seaRoadBadge($str)
{
    $bb = $str == 'sea' ? 'bg-info text-dark ' : 'bg-secondary';
    return '<span class="badge ' . $bb . '"> By ' . $str . '</span>';
}

function getTransferredToRoznamchaSerial($r_type, $transfered_from_id, $transfered_from)
{
    $rozQ = fetch('roznamchaas', array('r_type' => $r_type, 'transfered_from_id' => $transfered_from_id, 'transfered_from' => $transfered_from));
    $arrrr = array();
    while ($rr = mysqli_fetch_assoc($rozQ)) {
        $arrrr[]['r_id'] = $rr['r_id'];
        $arrrr[]['branch_serial'] = $rr['branch_serial'];
    }
    $msgg = '';
    foreach ($arrrr as $item) {
        if (isset($item['r_id'])) {
            $msgg .= $item['r_id'] . "-";
            //echo "r_id: " . $item['r_id'] . "<br>";
        }
        if (isset($item['branch_serial'])) {
            $msgg .= $item['branch_serial'] . " ";
            //echo "branch_serial: " . $item['branch_serial'] . "<br>";
        }
    }
    return $msgg;
}

function roznamchaName($type)
{
    $span = '';
    if ($type == "buys_details") {
        $span = '<span class="badge badge-pill badge-soft-success font-size-12">Purchase Bill</span>';
    }
    if ($type == "buys_details_exp") {
        $span = '<span class="badge badge-pill badge-soft-danger font-size-12">Purchase Expense</span>';
    }
    if ($type == "buys_sold_local") {
        $span = '<span class="badge badge-pill badge-soft-primary font-size-12">Local Sale</span>';
    }
    if ($type == "buys_sold_export") {
        $span = '<span class="badge badge-pill badge-soft-warning font-size-12">Export Sale</span>';
    }


    if ($type == "karobar") {
        $span = '<span class="badge bg-primary">Business</span>';
    }
    if ($type == "bank") {
        $span = '<span class="badge bg-success">Bank</span>';
    }
    if ($type == "bill") {
        $span = '<span class="badge bg-dark">Bill</span>';
    }
    if ($type == "AFG") {
        $span = '<span class="badge bg-light small-4 text-dark">Afghani Truck</span>';
    }
    if ($type == "import_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">Imp. Custom Exp</span>';
    }
    if ($type == "dt_custom_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">Dt. Custom Exp</span>';
    }
    if ($type == "r_office_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">Office Exp</span>';
    }
    if ($type == "r_home_exp") {
        $span = '<span class="badge bg-light small-4 text-dark">Home Exp</span>';
    }
    if ($type == "kiraya_summary") {
        $span = '<span class="badge bg-light small-4 text-dark">Imp. Fare Summary</span>';
    }
    if ($type == "ut_karachi") {
        $span = '<span class="badge bg-info  small-4 text-dark">Imp. Kirachi</span>';
    }
    if ($type == "ut_chaman") {
        $span = '<span class="badge bg-info  small-4 text-dark">Chaman Export</span>';
    }
    if ($type == "ut_border") {
        $span = '<span class="badge bg-info  small-4 text-dark">Ut. Afg. Border</span>';
    }
    if ($type == "ut_qandhar") {
        $span = '<span class="badge bg-info  small-4 text-dark">Qandhar Custom</span>';
    }
    if ($type == "ut_border_bill") {
        $span = '<span class="badge bg-info  small-4 text-dark">Border Bill</span>';
    }
    if ($type == "ut_border_afg_truck") {
        $span = '<span class="badge bg-info  small-4 text-dark">AFG. Truck Fare</span>';
    }
    return $span;
}

function SuperAdmin()
{
    if ($_SESSION['role'] == "superadmin") {
        return true;
    } else {
        return false;
    }
}

function Administrator()
{
    if ($_SESSION['role'] == "admin") {
        return true;
    } else {
        return false;
    }
}

function userAttachedAccount($UserId)
{
    $aa = [];
    $data = fetch('users', array('id' => $UserId));
    if (mysqli_num_rows($data) > 0) {
        $d = mysqli_fetch_assoc($data);
        if (!empty($d['khaata'])) {
            $json = json_decode($d['khaata']);
            $aa['khaata_no'] = $json->khaata_no;
            $aa['khaata_id'] = $json->khaata_id;
        }
    }
    return $aa;
}

function branchName($branch_id, $label = null)
{
    $data = fetch('branches', array('id' => $branch_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        //$name = $datum['b_name'];
        $name = $datum['b_code'];
        if ($label) {
            $name = '<b>' . $label . '</b>' . $name;
        }
    } else {
        $name = '';
    }
    return $name;
}

function countryName($country_id)
{
    $data = fetch('countries', array('id' => $country_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['name'];
    } else {
        $name = 'UNKNOWN';
    }
    return $name;
}

function shipperData($shipperId)
{
    $data = fetch('shippers', array('id' => $shipperId));
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function catName($cat_id, $label = null)
{
    $data = fetch('cats', array('id' => $cat_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['name'];
        if ($label) {
            $name = '<b>' . $label . '</b>' . $name;
        }
    } else {
        $name = '';
    }
    return $name;
}

function goodsName($goods_id, $label = null)
{
    $data = fetch('goods', array('id' => $goods_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['name'];
        if ($label) {
            $name = '<b>' . $label . '</b>' . $name;
        }
    } else {
        $name = '';
    }
    return $name;
}

function userName($user_id)
{
    $data = fetch('users', array('id' => $user_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['username'];
    } else {
        $name = 'نا معلوم';
    }
    return $name;
}

function userRoleStr($user_id)
{
    $role = '';
    $data = fetch('users', array('id' => $user_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $role = $datum['role'];
    }
    return $role;
}

function bankName($bank_id)
{
    //$data = fetch('banks', array('id' => $bank_id));
    $name = '';
    $data = fetch('khaata', array('id' => $bank_id));
    if (mysqli_num_rows($data) > 0) {
        $datum = mysqli_fetch_assoc($data);
        $name = $datum['khaata_name'];
    }
    return $name;
}

function Manager()
{
    if ($_SESSION['role'] == "manager") {
        return true;
    } else {
        return false;
    }
}

function Munshi()
{
    if ($_SESSION['role'] == "munshi") {
        return true;
    } else {
        return false;
    }
}

function ClearingAgent()
{
    if ($_SESSION['role'] == "agent") {
        return true;
    } else {
        return false;
    }
}

function Customer()
{
    if ($_SESSION['role'] == "customer") {
        return true;
    } else {
        return false;
    }
}

//get expenses by dates
function getExpenseTotal($expense_id)
{
    $totalExps = 0;
    $expenses = fetch('expense_details', array('expense_id' => $expense_id));
    while ($expense = mysqli_fetch_assoc($expenses)) {
        $totalExps += $expense['amount'];
    }
    return $totalExps;
}

function getNumRows($table, $where_key = null, $where_value = null)
{
    global $connect;
    if ($where_key && $where_value) {
        $data = mysqli_query($connect, "SELECT * FROM $table WHERE $where_key = '$where_value'");
    } else {
        $data = mysqli_query($connect, "SELECT * FROM $table");
    }
    return mysqli_num_rows($data);
}

function getTableDataById($table, $id)
{
    global $connect;
    $data = mysqli_query($connect, "SELECT * FROM $table WHERE id = $id");
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function userMainPic($userId, $width = null, $class = null, $height = null)
{
    global $connect;
    $data = mysqli_query($connect, "SELECT * FROM `client_pics` WHERE user_id = '$userId'");
    if (mysqli_num_rows($data) > 0) {
        $data1 = mysqli_query($connect, "SELECT * FROM `client_pics` WHERE user_id = '$userId' AND main_pic = 1");
        if (mysqli_num_rows($data1) > 0) {
            $datum1 = mysqli_fetch_assoc($data1);
            $pic = '<img src="' . $datum1['pic'] . '" alt="Image not found." style="width:' . $width . 'px; height:' . $height . 'px" class="img-fluid ' . $class . '">';
        } else {
            $data2 = mysqli_query($connect, "SELECT * FROM `client_pics` WHERE user_id = '$userId' LIMIT 1");
            $datum2 = mysqli_fetch_assoc($data2);
            $pic = '<img src="' . $datum2['pic'] . '" alt="Image not found." style="width:' . $width . 'px; height:' . $height . 'px" class="img-fluid ' . $class . '">';
        }
    } else {
        $pic = '<img src="../assets/img/avatar.jpg" alt="Image not found." class="img-fluid ' . $class . '" style="width:' . $width . 'px ; height:' . $height . 'px">';
    }
    return $pic;
}

function setMainPic($client_pics_id)
{
    global $connect;
    $userId = $_SESSION['userId'];
    mysqli_query($connect, "UPDATE `client_pics` SET `main_pic`= 0 WHERE user_id = '$userId'");
    mysqli_query($connect, "UPDATE `client_pics` SET `main_pic`= 1 WHERE user_id = '$userId' AND id = '$client_pics_id'");
}

function saveUTLogs($bail_id, $remarks)
{
    global $connect;
    $presentDateTime = date('Y-m-d H:i:s');
    $data = array(
        'bail_id' => $bail_id,
        'remarks' => $remarks,
        'created_at' => $presentDateTime
    );
    insert('ut_logs', $data);
    return true;
}


function saveTransaction($source_id, $source_name, $date_time, $amount, $type, $source_other = null, $note = null)
{
    global $connect;
    $presentDateTime = date('Y-m-d H:i:s');
    if ($type == 'credit') {
        $add_sql = "INSERT INTO transactions
                    (`source_id`, `source_name`, `date_time`, `credit`, `debit`, `source_other`, `note`)
                    VALUES
                    ('$source_id', '$source_name', '$date_time', '$amount', '', '$source_other', '$note')";
    } elseif ($type == 'debit') {
        $add_sql = "INSERT INTO transactions
                    (`source_id`, `source_name`, `date_time`, `credit`, `debit`, `source_other`, `note`)
                    VALUES
                    ('$source_id', '$source_name', '$date_time', '', '$amount', '$source_other', '$note')";
    }
    mysqli_query($connect, $add_sql);
    return true;
}

function getAttachments($source_id, $source_name)
{
    $attachments = []; // Initialize an empty array to store results
    $results = fetch('attachments', array('source_id' => $source_id, 'source_name' => $source_name));

    if ($results instanceof mysqli_result) {
        while ($row = $results->fetch_assoc()) {
            $attachments[] = $row; // Append each fetched row to the attachments array
        }
    }

    return $attachments;
}


function saveAttachment($source_id, $source_name, $attachment)
{
    global $connect;
    $presentDateTime = date('Y-m-d H:i:s');
    $add_sql = "INSERT INTO `attachments` (`source_id`, `source_name`, `attachment`, `created_at`) VALUES ('$source_id', '$source_name', '$attachment', '$presentDateTime')";
    mysqli_query($connect, $add_sql);
    return true;
}


function fetchUsers()
{
    global $connect;
    $sql = "SELECT users.*, user_permissions.permission as permission
    FROM users
    LEFT JOIN user_permissions ON users.id = user_permissions.user_id
    WHERE users.is_active=1";
    $data = mysqli_query($connect, $sql);
    return $data;
}

function getUser($userId)
{
    global $connect;
    $sql = "SELECT users.*, user_permissions.*
        FROM users
        LEFT JOIN user_permissions ON users.id = user_permissions.user_id
        WHERE users.id = '$userId'";
    $data = mysqli_query($connect, $sql);
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function getTableDataByIdAndColName($table, $id, $col)
{
    global $connect;
    $userData = mysqli_query($connect, "SELECT * FROM `$table` WHERE id = '$id'");
    if (mysqli_num_rows($userData) > 0) {
        $userDatum = mysqli_fetch_assoc($userData);
        return $userDatum[$col];
    } else {
        return 'UNKNOWN';
    }
}

function getStaticTypeName($id, $get_details = false)
{
    $data = fetch('static_types', array('id' => $id));
    $datum = mysqli_fetch_assoc($data);
    if ($get_details) {
        $d = $datum['details'];
    } else {
        $d = $datum['type_name'];
    }
    return $d;
}

function getTableDataByCol($table, $col, $key)
{
    global $connect;
    $data = mysqli_query($connect, "SELECT * FROM `$table` WHERE $col = '$key'");
    $datum = mysqli_fetch_assoc($data);
    return $datum;
}

function navbarCol($navbar_id, $col)
{
    $data = fetch('navbar', array('id' => $navbar_id));
    $datum = mysqli_fetch_assoc($data);
    return $datum[$col];
}

function message($type, $url, $msg, $spanLabel1 = null, $span1 = null, $spanLabel2 = null, $span2 = null, $spanLabel3 = null, $span3 = null, $spanLabel4 = null, $span4 = null, $spanLabel5 = null, $span5 = null)
{
    //session_start();
    $div = '<div class="alert alert-' . $type . ' alert-dismissible fade show mb-0 p-1 ps-2" role="alert">';
    $div .= '<strong>' . strtoupper($type) . '! </strong> ' . $msg . ' ';
    if ($span1) {
        $div .= '<span class="badge bg-dark">' . $spanLabel1 . ': ' . $span1 . '</span>';
    }
    if ($span2) {
        $div .= '<span class="badge bg-success">' . $spanLabel2 . ': ' . $span2 . '</span>';
    }
    if ($span3) {
        $div .= '<span class="badge bg-danger">' . $spanLabel3 . ': ' . $span3 . '</span>';
    }
    if ($span4) {
        $div .= '<span class="badge bg-info">' . $spanLabel4 . ': ' . $span4 . '</span>';
    }
    if ($span5) {
        $div .= '<span class="badge bg-warning">' . $spanLabel5 . ': ' . $span5 . '</span>';
    }
    $div .= '<button type="button" class="btn-close" style="padding: 0.6rem!important;" data-bs-dismiss="alert" aria-label="btn-close"></button>';
    $div .= '</div>';
    $_SESSION['response'] = $div;
    echo '<script>window.location.href="' . $url . '"</script>';
}

function messageNew($type, $url, $msg, $date = null)
{
    $toast = '<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <i class="fa fa-square text-' . $type . '"></i><strong class="ms-2 me-auto text-uppercase">' . $type . '</strong>
      <small>' . date('D, d M Y H:i:s') . '</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body bg-light">' . $msg . '</div>
  </div>
</div>';


    $_SESSION['response'] = $toast;
    echo '<script>window.location.href="' . $url . '"</script>';
}


function Percentage($percentage, $total)
{
    return ($percentage / 100) * $total;
}


function messageAjax($type, $msg, $divClass = null)
{
    //session_start();
    $div = '<div class="alert alert-' . $type . ' alert-dismissible alert-section ' . $divClass . '" role="alert">';
    $div .= '<strong>' . strtoupper($type) . '! </strong> ' . $msg . ' ';

    $div .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>';
    $div .= '</div>';
    return $div;
}

function getMsg_attach($att, $key, $msg_attach)
{
    $msg_attach .= $att ? basename($_FILES["attachments"]["name"][$key]) . ', ' : '';
    $location = 'attachments/' . basename($_FILES["attachments"]["name"][$key]);
    $moved = move_uploaded_file($_FILES["attachments"]["tmp_name"][$key], $location);
    return $msg_attach;
}

function getAge($day, $month, $year)
{
    $array = array();
    $bday = new DateTime($day . '.' . $month . '.' . $year);
    $today = new Datetime(date('m.d.y'));
    $diff = $today->diff($bday);
    $array['years'] = $diff->y;
    $array['months'] = $diff->m;
    $array['days'] = $diff->d;
    //$age = $diff->y . ' years, ' . $diff->m . ' month,' . $diff->d . ' days';
    $age = $diff->y . ' years, ' . $diff->m . ' months';
    //printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
    //printf("\n");
    return $array;
}

function random_str($length = 20)
{
    $characters = '_0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function AmountInWords($amount)
{
    $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
    // Check if there is any number after decimal
    $amt_hundred = null;
    $count_length = strlen($num);
    $x = 0;
    $string = array();
    $change_words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );
    $here_digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($x < $count_length) {
        $get_divider = ($x == 2) ? 10 : 100;
        $amount = floor($num % $get_divider);
        $num = floor($num / $get_divider);
        $x += $get_divider == 10 ? 1 : 2;
        if ($amount) {
            $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
            $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
            $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' 
       ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' 
       ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
        } else $string[] = null;
    }
    $implode_to_Rupees = implode('', array_reverse($string));
    $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' only' : '';
    return ($implode_to_Rupees ? $implode_to_Rupees . ' ' : '') . $get_paise;
    //return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}

function AmountInWordsUrdu($amount)
{
    $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
    // Check if there is any number after decimal
    $amt_hundred = null;
    $count_length = strlen($num);
    $x = 0;
    $string = array();
    $change_words = array(
        0 => '',
        1 => 'ایک',
        2 => 'دو',
        3 => 'تین',
        4 => 'چار',
        5 => 'پانچ',
        6 => 'چھ',
        7 => 'سات',
        8 => 'آٹھ',
        9 => 'نو',
        10 => 'دس',
        11 => 'گیارہ',
        12 => 'بارہ',
        13 => 'تیرہ',
        14 => 'چودہ',
        15 => 'پندرہ',
        16 => 'سولہ',
        17 => 'سترہ',
        18 => 'اٹھارہ',
        19 => 'انیس',
        20 => 'بیس',
        30 => 'تیس',
        40 => 'چالیس',
        50 => 'پچاس',
        60 => 'ساٹھ',
        70 => 'ستر',
        80 => 'اسی',
        90 => 'نوے'
    );
    $here_digits = array('', 'سو', 'ہزار', 'لاکھ', 'کروڑ');
    while ($x < $count_length) {
        $get_divider = ($x == 2) ? 10 : 100;
        $amount = floor($num % $get_divider);
        $num = floor($num / $get_divider);
        $x += $get_divider == 10 ? 1 : 2;
        if ($amount) {
            $add_plural = (($counter = count($string)) && $amount > 9) ? '' : null;
            $amt_hundred = ($counter == 1 && $string[0]) ? ' اور ' : null;
            $string[] = ($amount < 21) ? $change_words[$amount] . ' ' . $here_digits[$counter] . $add_plural . ' 
       ' . $amt_hundred : $change_words[floor($amount / 10) * 10] . ' ' . $change_words[$amount % 10] . ' 
       ' . $here_digits[$counter] . $add_plural . ' ' . $amt_hundred;
        } else $string[] = null;
    }
    $implode_to_Rupees = implode('', array_reverse($string));
    $get_paise = ($amount_after_decimal > 0) ? "اور " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' صرف' : '';
    return ($implode_to_Rupees ? $implode_to_Rupees . 'روپے ' : '') . $get_paise;
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function compressImage($source, $destination, $quality)
{
    // Get image info
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    // Save image
    imagejpeg($image, $destination, $quality);

    // Return compressed image
    return $destination;
}

function convert_filesize($bytes, $decimals = 2)
{
    $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function custom_number_format($n, $precision = 3)
{
    if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n);
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}

function numberToUrduWords($number)
{
    $words = array(
        0 => 'صفر',
        1 => 'ایک',
        2 => 'دو',
        3 => 'تین',
        4 => 'چار',
        5 => 'پانچ',
        6 => 'چھ',
        7 => 'سات',
        8 => 'آٹھ',
        9 => 'نو',
        10 => 'دس',
        11 => 'گیارہ',
        12 => 'بارہ',
        13 => 'تیرہ',
        14 => 'چودہ',
        15 => 'پندرہ',
        16 => 'سولہ',
        17 => 'سترہ',
        18 => 'اؠٹھارہ',
        19 => 'انیس',
        20 => 'بیس',
        30 => 'تیس',
        40 => 'چالیس',
        50 => 'پچاس',
        60 => 'ستان',
        70 => 'ستر',
        80 => 'اسی',
        90 => 'نوے'
    );

    $numDigits = strlen($number);
    $result = '';

    switch ($numDigits) {
        case 1:
            $result = $words[$number];
            break;
        case 2:
            if ($number < 21) {
                $result = $words[$number];
            } else {
                $result = $words[10 * floor($number / 10)];
                $remainder = $number % 10;
                if ($remainder != 0) {
                    $result .= ' ' . $words[$remainder];
                }
            }
            break;
        case 3:
            $result = $words[floor($number / 100)] . ' سو';
            $remainder = $number % 100;
            if ($remainder != 0) {
                $result .= ' ' . numberToUrduWords($remainder);
            }
            break;
        case 4:
        case 5:
        case 6:
            $result = numberToUrduWords(substr($number, 0, $numDigits - 3)) . ' ہزار';
            $remainder = substr($number, -3);
            if ($remainder != '000') {
                $result .= ' ' . numberToUrduWords($remainder);
            }
            break;
        case 7:
        case 8:
        case 9:
            $result = numberToUrduWords(substr($number, 0, $numDigits - 5)) . ' لاکھ';
            $remainder = substr($number, -5);
            if ($remainder != '00000') {
                $result .= ' ' . numberToUrduWords($remainder);
            }
            break;
        case 10:
        case 11:
        case 12:
            $result = numberToUrduWords(substr($number, 0, $numDigits - 7)) . ' کروڑ';
            $remainder = substr($number, -7);
            if ($remainder != '0000000') {
                $result .= ' ' . numberToUrduWords(substr($number, -7));
            }
            break;
        default:
            $result = 'بہت بڑا عدد';
            break;
    }

    return $result;
}

function readMoreTooltip($str, $length)
{
    return '<span data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="' . $str . '">' . readMore($str, $length) . '</span>';
}

function readMore($longStr, $lenght = null)
{
    $string = strip_tags($longStr);
    if (strlen($string) > $lenght) {
        $stringCut = substr($string, 0, $lenght);
        $endPoint = strrpos($stringCut, ' ');
        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }
    return ucfirst($string);
}

//used in khaata-add page
function mapInputIdToLabel($inputId)
{
    $idToLabelMap = array(
        'comp_name' => 'Company Name',
        'name' => 'Company Name',
        'bank_name' => 'Bank Name',
        'ac_no' => 'Bank A/c. No.',
        'ifsc_code' => 'BANK IFSC CODE',
        'mobile' => 'Mobile #',
        'office_no' => 'Office #',
        'whatsapp' => 'WhatsApp ',
        'email' => 'Email',
        'city' => 'City',
        'address' => 'Address',
        'ntn_name_text' => 'NTN Name',
        'report' => 'Report',
        'renewal_date' => 'Renewal Date ',
        'expiry_date' => 'Expiry Date',
        'consignee' => 'Consignee Name',
        'reebok_id' => 'Reebok ID',
        'warehouse_name' => 'Warehouse Name',
        'warehouse_city' => 'Warehouse City',
        'warehouse_address' => 'Warehouse Address',
        'warehouse_email' => 'Warehouse Email',
        'warehouse_mobile' => 'Warehouse Mobile',
        'date' => 'Date',
        'warehouse_report' => 'Warehouse Report',
    );
    return isset($idToLabelMap[$inputId]) ? $idToLabelMap[$inputId] : $inputId;
}

function my_date($date)
{
    $d = $date;
    if ($date != "") {
        $d = date('d-M-y', strtotime($date));
    }
    return $d;
}
