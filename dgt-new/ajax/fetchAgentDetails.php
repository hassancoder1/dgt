<?php
include('../connection.php');
if (isset($_POST["agent_acc_no"])) {
    $ag_acc_no = strtoupper($_POST['agent_acc_no']);
    $agentUserQ = fetch('users');
    $data = ['ag_acc_no' => '', 'ag_name' => '', 'ag_id' => '', 'row_id' => ''];
    while ($agentUser = mysqli_fetch_assoc($agentUserQ)) {
        $agentAttactedKhaata = json_decode($agentUser['khaata'], true);
        if (isset($agentAttactedKhaata['khaata_no'])) {
            if (strtoupper($agentAttactedKhaata['khaata_no']) === $ag_acc_no) {
                $data = [
                    'ag_acc_no' => strtoupper($agentAttactedKhaata['khaata_no']),
                    'ag_name' => $agentUser['full_name'],
                    'ag_id' => $agentUser['username'],
                    'row_id' => $agentUser['id']
                ];
                break;
            }
        }
    }
    echo json_encode(['data' => $data]);
}
if (isset($_POST["acc_no"])) {
    $acc_no = strtoupper(mysqli_real_escape_string($connect, $_POST['acc_no']));
    $khaataQ = mysqli_query($connect, "SELECT * FROM khaata WHERE UPPER(khaata_no) = '$acc_no'");
    $data = ['acc_no' => '', 'acc_name' => '', 'row_id' => ''];
    if ($khaata = mysqli_fetch_assoc($khaataQ)) {
        $data = ['acc_no' => strtoupper($khaata['khaata_no']), 'acc_name' => $khaata['khaata_name'], 'row_id' => $khaata['id']];
    }
    echo json_encode(['data' => $data]);
}
