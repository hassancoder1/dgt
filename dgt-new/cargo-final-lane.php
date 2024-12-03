<?php
$page_title = 'Cargo Final Lane';
$pageURL = 'cargo-final-lane';
include("header.php");
$remove = $start_print = $end_print = $type = $p_id = $l_port = $acc_no = $r_port = $blSearch = $sea_road = $date_type = '';
$is_search = false;
global $connect;
$results_per_page = 50;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;
$sql = "SELECT * FROM `general_loading`";
$conditions = [];
$print_filters = [];
if ($_GET) {
    $remove = removeFilter('cargo-final-lane');
    $is_search = true;
    if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
        $p_id = mysqli_real_escape_string($connect, $_GET['p_id']);
        $print_filters[] = 'p_id=' . $p_id;
        $conditions[] = "p_id = '$p_id'";
    }
    $date_type = isset($_GET['date_type']) ? $_GET['date_type'] : '';
    $print_filters[] = 'date_type=' . $date_type;
    if (!empty($_GET['start'])) {
        $start_print = mysqli_real_escape_string($connect, $_GET['start']);
        $print_filters[] = 'start=' . $start_print;
        if ($date_type == 'loading') {
            $conditions[] = "JSON_EXTRACT(loading_details, '$.loading_date') >= '$start_print'";
        } elseif ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(receiving_details, '$.receiving_date') >= '$start_print'";
        }
    }
    if (!empty($_GET['end'])) {
        $end_print = mysqli_real_escape_string($connect, $_GET['end']);
        $print_filters[] = 'end=' . $end_print;
        if ($date_type == 'loading') {
            $conditions[] = "JSON_EXTRACT(loading_details, '$.loading_date') <= '$end_print'";
        } elseif ($date_type == 'receiving') {
            $conditions[] = "JSON_EXTRACT(receiving_details, '$.receiving_date') <= '$end_print'";
        }
    }
    if (!empty($_GET['blSearch'])) {
        $blSearch = mysqli_real_escape_string($connect, $_GET['blSearch']);
        $print_filters[] = 'blSearch=' . $blSearch;
        $conditions[] = "bl_no='$blSearch'";
    }
    
    if (!empty($_GET['p_type'])) {
        $type = mysqli_real_escape_string($connect, $_GET['p_type']);
        $print_filters[] = 'p_type=' . $type;
        $conditions[] = "p_type = '$type'";
    }
    if (!empty($_GET['l_port'])) {
        $l_port = mysqli_real_escape_string($connect, $_GET['l_port']);
        $print_filters[] = 'l_port=' . $l_port;
        $conditions[] = "JSON_EXTRACT(loading_details, '$.loading_port_name') = '$l_port'";
    }
    if (!empty($_GET['r_port'])) {
        $r_port = mysqli_real_escape_string($connect, $_GET['r_port']);
        $print_filters[] = 'r_port=' . $r_port;
        $conditions[] = "JSON_EXTRACT(receiving_details, '$.receiving_port_name') = '$r_port'";
    }
    if (!empty($_GET['acc_no'])) {
        $acc_no = mysqli_real_escape_string($connect, $_GET['acc_no']);
        $print_filters[] = 'acc_no=' . $acc_no;
        $conditions[] = "JSON_EXTRACT(agent_details, '$.ag_acc_no') = '$acc_no'";
    }
    if (!empty($_GET['sea_road'])) {
        $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
        $print_filters[] = 'sea_road=' . $sea_road;
        $conditions[] = "JSON_EXTRACT(shipping_details, '$.transfer_by') = '$sea_road'";
    }

}
if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}
$sql .= " ORDER BY sr_no ASC LIMIT $start_from, $results_per_page";
$query_string = implode('&', $print_filters);
$print_url = "print/" . $pageURL . "-main" . '?' . $query_string;
$count_sql = "SELECT COUNT(id) AS total FROM `general_loading`" . (count($conditions) > 0 ? " WHERE " . implode(' AND ', $conditions) : "");
$count_result = mysqli_query($connect, $count_sql);
$total_pages = ceil(mysqli_fetch_assoc($count_result)['total'] / $results_per_page);
?>
<div class="fixed-top">
    <?php require_once('nav-links.php'); ?>
</div>
<div class="mx-5 bg-white p-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <h1 class="mb-2" style="font-size: 2rem; font-weight: 700; color: #333; text-transform: uppercase; letter-spacing: 1.5px; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);">
            Cargo Final Lane
        </h1>
        <div class="d-flex gap-2">
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center pagination-sm">
                    <?php
                    $current_url = $_SERVER['REQUEST_URI'];
                    $url_parts = parse_url($current_url);
                    parse_str($url_parts['query'] ?? '', $query_params);
                    unset($query_params['page']);
                    $base_url = $url_parts['path'] . '?' . http_build_query($query_params);
                    $count_sql = "SELECT COUNT(id) AS total FROM `general_loading`";
                    if (count($conditions) > 0) {
                        $count_sql .= " WHERE " . implode(' AND ', $conditions);
                    } else {
                        $count_sql .= " WHERE 1";
                    }
                    $count_result = mysqli_query($connect, $count_sql);
                    if (!$count_result) {
                        echo "Error: " . mysqli_error($connect);
                        exit;
                    }
                    $row = mysqli_fetch_assoc($count_result);
                    $total_pages = ceil($row['total'] / $results_per_page);
                    echo '<ul class="pagination">';
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page - 1) . "'>Previous</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = ($i == $page) ? 'active' : '';
                        echo "<li class='page-item $active_class'><a class='page-link' href='" . $base_url . "&page=$i'>$i</a></li>";
                    }
                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='" . $base_url . "&page=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
                    }
                    echo '</ul>';
                    ?>
                </ul>
            </nav>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="<?= $print_url; ?>" target="_blank">
                            <i class="fas text-secondary fa-eye me-2"></i> Print Preview
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= $print_url; ?>')">
                            <i class="fas text-secondary fa-print me-2"></i> Print
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= $print_url; ?>')">
                            <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= $print_url; ?>')">
                            <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= $print_url; ?>')">
                            <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= $print_url; ?>')">
                            <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <form name="datesSubmit" class="mt-2" method="get">
        <div class="input-group input-group-sm">
            <div class="form-group">
                <label for="p_id" class="form-label">P/S#</label>
                <input type="number" name="p_id" value="<?php echo $p_id; ?>" id="p_id" class="form-control form-control-sm mx-1" style="max-width:80px;" placeholder="e.g. 33">
            </div>
            <div class="form-group">
                <label for="date_type" class="form-label">Date Type</label>
                <select class="form-select form-select-sm" name="date_type" style="max-width:130px;" id="date_type" onchange="toggleDates()">
                    <option value="" <?= !in_array($date_type, ['loading', 'receiving']) ? 'selected' : ''; ?>>All</option>
                    <option value="loading" <?= $date_type == 'loading' ? 'selected' : ''; ?>>Loading</option>
                    <option value="receiving" <?= $date_type == 'receiving' ? 'selected' : ''; ?>>Receiving</option>
                </select>
            </div>
            <div class="form-group <?= !in_array($date_type, ['loading', 'receiving']) ? 'd-none' : ''; ?>" id="startInput">
                <label for="start" class="form-label">Start Date</label>
                <input type="date" name="start" value="<?php echo $start_print; ?>" id="start" class="form-control form-control-sm mx-1" style="max-width:160px;">
            </div>
            <div class="form-group <?= !in_array($date_type, ['loading', 'receiving']) ? 'd-none' : ''; ?>" id="endInput">
                <label for="end" class="form-label">End Date</label>
                <input type="date" name="end" value="<?php echo $end_print; ?>" id="end" class="form-control form-control-sm mx-2" style="max-width:160px;">
            </div>
            <div class="form-group">
                <label for="p_type" class="form-label">Type</label>
                <select class="form-select form-select-sm" name="p_type" style="max-width:130px;" id="p_type">
                    <option value="" selected>All</option>
                    <?php
                    $static_types = fetch('static_types', ['type_for' => 'ps_types']);
                    while ($static_type = mysqli_fetch_assoc($static_types)) {
                        $sel_tran = $type == $static_type['type_name'] ? 'selected' : '';
                        echo '<option ' . $sel_tran . '  value="' . $static_type['type_name'] . '">' . strtoupper(htmlspecialchars($static_type['details'])) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sea_road" class="form-label">SEA/ROAD</label>
                <select class="form-select form-select-sm" name="sea_road" style="max-width:130px;" id="sea_road">
                    <option value="" <?= !in_array($sea_road, ['sea', 'road']) ? 'selected' : ''; ?>>All</option>
                    <option value="sea" <?= $sea_road == 'sea' ? 'selected' : ''; ?>>by Sea</option>
                    <option value="road" <?= $sea_road == 'road' ? 'selected' : ''; ?>>by Road</option>
                </select>
            </div>
            <div class="form-group mx-1">
                <label for="blSearch" class="form-label">B/L Search</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:130px;" name="blSearch" placeholder="B/L Search" value="<?php echo $blSearch; ?>" id="blSearch">
            </div>
            <div class="form-group mx-1">
                <label for="l_port" class="form-label">L Port/Border</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:130px;" name="l_port" placeholder="L Port/Border" value="<?php echo $l_port; ?>" id="l_port">
            </div>
            <div class="form-group mx-1">
                <label for="r_port" class="form-label">R Port/Border</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:130px;" name="r_port" placeholder="R Port/Border" value="<?php echo $r_port; ?>" id="r_port">
            </div>
            <div class="form-group">
                <label for="acc_no" class="form-label">Acc No.</label>
                <input type="text" class="form-control form-control-sm mx-1" style="max-width:90px;" name="acc_no" placeholder="Acc No." value="<?php echo $acc_no; ?>" id="acc_no">
            </div>
            <div class="form-group mt-4 pt-1">
                <?= $remove ? '<a href="' . $pageURL . '" class="btn btn-sm btn-danger"><i class="fa fa-sync-alt"></i></a>' : ''; ?>
                <button type="submit" class="btn btn-sm btn-success">Search</button>
            </div>
        </div>
    </form>
    <style>
        #RecordsTable {
            height: 300px;
            overflow-y: scroll;
        }

        .fixed thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #fff;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="table-responsive mt-4" id="RecordsTable">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>P/S#</th>
                    <th>Sr#</th>
                    <th>L_DATE</th>
                    <th>L_COUNTRY</th>
                    <th>L_PORT/BORDER</th>
                    <th>R_DATE</th>
                    <th>R_COUNTRY</th>
                    <th>R_PORT/BORDER</th>
                    <th>B/L No.</th>
                    <th>Container No</th>
                    <th>AG ID</th>
                    <th>AG NAME</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $Loadings = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $i = 1;
                $rowColor = '';
                $locked = 0;
                while ($SingleLoading = mysqli_fetch_assoc($Loadings)) {
                    $id = $SingleLoading['id'];
                    if (!($SingleLoading['agent_details'])) {
                        $rowColor = 'text-danger';
                    } elseif (isset(json_decode($SingleLoading['agent_details'], true)['transferred'])) {
                        if (json_decode($SingleLoading['agent_details'], true)['transferred'] === true) {
                            $rowColor = 'text-dark';
                        } else {
                            $rowColor = 'text-warning';
                        }
                    }
                ?>
                    <tr class="text-nowrap">
                        <td class="pointer <?php echo $rowColor; ?>" onclick="viewPurchase(<?php echo $SingleLoading['id']; ?>)"
                            data-bs-toggle="modal" data-bs-target="#KhaataDetails">
                            <?php echo '<b>'.ucfirst($SingleLoading['type']).'#', $SingleLoading['p_id']; ?>
                            <?php echo $locked == 1 ? '<i class="fa fa-lock text-success"></i>' : ''; ?>
                        </td>
                        <td class="<?php echo $rowColor; ?>"><?php echo $SingleLoading['sr_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_date']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_country']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['loading_details'], true)['loading_port_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_date']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_country']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['receiving_details'], true)['receiving_port_name']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= $SingleLoading['bl_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= json_decode($SingleLoading['goods_details'], true)['container_no']; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_id'] : ''; ?></td>
                        <td class="<?php echo $rowColor; ?>"><?= isset(json_decode($SingleLoading['agent_details'], true)['ag_id']) ? json_decode($SingleLoading['agent_details'], true)['ag_name'] : ''; ?></td>
                    </tr>
                <?php
                    $row_count++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php include("footer.php"); ?>
<div class="modal fade" id="KhaataDetails" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen -modal-xl -modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="staticBackdropLabel">GENERAL LOADING</h5>
                <div class="d-flex align-items-center">
                    <!-- Print Button -->
                    <!--  <a href="print/purchase-single?t_id=<?php echo $id; ?>&action=order&secret=<?php echo base64_encode('powered-by-upsol') . "&print_type=full"; ?>" -->
                        <!-- target="_blank" class="btn btn-dark btn-sm me-2">PRINT</a> -->

                    <!-- Contract File Upload -->
                    <!-- <form id="attachmentSubmit" method="post" enctype="multipart/form-data" class="d-flex align-items-center me-2">
                        <input type="hidden" name="t_id_hidden_attach" value="<?php echo $id; ?>">
                        <input type="file" id="attachments" name="attachments[]" class="d-none" multiple>
                        <input type="button" class="form-control rounded-1 bg-dark text-white" value="+ Contract File"
                            onclick="document.getElementById('attachments').click();" />
                    </form>

                    <script>
                        document.getElementById("attachments").onchange = function() {
                            document.getElementById("attachmentSubmit").submit();
                        }
                    </script> -->

                    <!-- Attachments List -->
                    <div class="">
                        <?php
                        // $atts = getAttachments($id, 'purchase_contract');
                        // $no = 0;
                        // foreach ($atts as $att) {
                        //     echo ++$no . '.<a class="text-decoration-underline me-2" href="attachments/' . $att['attachment'] . '" title="' . $att['created_at'] . '" target="_blank">' . readMore($att['attachment'], 20) . '</a><br>';
                        // } 
                        ?>
                    </div>

                    <!-- Close Button -->
                    <a href="<?php echo $mypageURL; ?>" class="btn-close ms-3" aria-label="Close"></a>
                </div>
            </div>


            <div class="modal-body bg-light pt-0" id="viewDetails"></div>
        </div>
    </div>
</div>
<script>
    function viewPurchase(id = null, loading_id = null) {
        if (id) {
            $.ajax({
                url: 'ajax/viewSingleCargoFinalLane.php',
                type: 'post',
                data: {
                    id: id,
                    level: 1,
                    page: "loading-transfer",
                },
                success: function(response) {
                    $('#viewDetails').html(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while processing your request. Please try again.');
                }
            });
        } else {
            alert('error!! Refresh the page again');
        }
    }
</script>