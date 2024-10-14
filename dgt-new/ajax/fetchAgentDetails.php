<?php include('../connection.php');
if (isset($_POST["agent_acc_no"])) {
    $ag_acc_no = $_POST['agent_acc_no'];
    $agentUserQ = fetch('users');
    while ($agentUser = mysqli_fetch_assoc($agentUserQ)) {
        $agentAttactedKhaata = json_decode($agentUser['khaata'], true);
        if (isset($agentAttactedKhaata['khaata_no'])) {
            if (strtoupper($agentAttactedKhaata['khaata_no']) === $ag_acc_no) {
                $data = ['ag_acc_no' => strtoupper($agentAttactedKhaata['khaata_no']), 'ag_name' => $agentUser['full_name'], 'ag_id' => $agentUser['username']];
            } else {
                $data = ['ag_acc_no' => '', 'ag_name' => '', 'ag_id' => ''];
            }
        }
    }
    echo json_encode(['data' => $data]);
}
