<?php if (isset($_SESSION['userId']) && isset($_SESSION['branch_id']) && isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $userId = $_SESSION['userId'];
    $branchId = $_SESSION['branch_id'];
    $branchName = getTableDataByIdAndColName('branches', $branchId, 'b_name');
    $userName = $_SESSION['username'];
    $userData = getUser($userId);
}else{
    die();
  //  echo '<script>window.location.href="../";</script>';
}
