<style>
    h1 {
        font-size: 28px;
        font-weight: bold;
        color: #333;
    }

    .form-inline select,
    .form-inline button {
        height: 40px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .table thead th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .table .status-estimated {
        color: #6c757d;
    }

    .table .status-actual {
        color: #28a745;
    }

    .table .status-skip {
        color: #dc3545;
    }

    .note {
        font-size: 13px;
        color: #dc3545;
        margin-top: 5px;
    }

    .close-page {
        text-align: right;
        font-size: 14px;
    }

    .close-page a {
        color: #007bff;
        text-decoration: none;
    }

    .close-page a:hover {
        text-decoration: underline;
    }
</style>
<?php
$page_title = 'Cargo Lane';
$mypageURL = $pageURL = 'cargo-lane';
include("header.php");
global $connect;
$sql = "SELECT * FROM `general_loading` WHERE 1=1";
?>
<div class="container bg-white p-3">
    <div class="close-page">
        <a href="/">[Close Page]</a>
    </div>
    <h1>Cargo Lane</h1>
    <form class="row mb-3">
        <div class="col-md-2">
            <label for="sea_road">Sea/Road: </label>
            <select name="sea_road" class="form-control" id="sea_road">
                <option selected value="">View All</option>
                <option value="sea" <?= isset($_GET['sea_road']) && $_GET['sea_road'] === 'sea' ? 'selected' : ''; ?>>by Sea</option>
                <option value="road" <?= isset($_GET['sea_road']) && $_GET['sea_road'] === 'road' ? 'selected' : ''; ?>>by Road</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="loading_country" class="form-label">Loading Country:</label>
            <input type="text" name="loading_country" id="loading_country" class="form-control" value="<?= isset($_GET['loading_country']) ? htmlspecialchars($_GET['loading_country']) : ''; ?>" placeholder="Enter Loading Country">
        </div>

        <div class="col-md-2">
            <label for="receiving_country" class="form-label">Receiving Country:</label>
            <input type="text" name="receiving_country" id="receiving_country" class="form-control" value="<?= isset($_GET['receiving_country']) ? htmlspecialchars($_GET['receiving_country']) : ''; ?>" placeholder="Enter Receiving Country">
        </div>

        <div class="col-md-2">
            <label for="loading_date" class="form-label">Loading Date:</label>
            <input type="date" name="loading_date" id="loading_date" class="form-control" value="<?= isset($_GET['loading_date']) ? htmlspecialchars($_GET['loading_date']) : ''; ?>">
        </div>

        <div class="col-md-2">
            <label for="receiving_date" class="form-label">Receiving Date:</label>
            <input type="date" name="receiving_date" id="receiving_date" class="form-control" value="<?= isset($_GET['receiving_date']) ? htmlspecialchars($_GET['receiving_date']) : ''; ?>">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary">Query</button>
        </div>
    </form>


    <div class="table-responsive mt-4">
        <table class="table table-bordered">
            <thead>
                <tr class="text-nowrap">
                    <th>P#</th>
                    <th>Sr#</th>
                    <th>L_DATE</th>
                    <th>L_COUNTRY</th>
                    <th>L_PORT/BORDER</th>
                    <th>R_DATE</th>
                    <th>R_COUNTRY</th>
                    <th>R_PORT/BORDER</th>
                    <th>B/L No.</th>
                    <th>Container No</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $whereClauses = [];
                if (isset($_GET['sea_road']) && !empty($_GET['sea_road'])) {
                    $sea_road = mysqli_real_escape_string($connect, $_GET['sea_road']);
                    $whereClauses[] = "transactionSingle(id)['sea_road'] = '$sea_road'";
                }
                if (!empty($_GET['loading_country'])) {
                    $loading_country = mysqli_real_escape_string($connect, $_GET['loading_country']);
                    $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(loading_details, '$.loading_country')) LIKE '%$loading_country%'";
                }
                if (!empty($_GET['receiving_country'])) {
                    $receiving_country = mysqli_real_escape_string($connect, $_GET['receiving_country']);
                    $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(receiving_details, '$.receiving_country')) LIKE '%$receiving_country%'";
                }
                if (!empty($_GET['loading_date'])) {
                    $loading_date = mysqli_real_escape_string($connect, $_GET['loading_date']);
                    $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(loading_details, '$.loading_date')) = '$loading_date'";
                }
                if (!empty($_GET['receiving_date'])) {
                    $receiving_date = mysqli_real_escape_string($connect, $_GET['receiving_date']);
                    $whereClauses[] = "JSON_UNQUOTE(JSON_EXTRACT(receiving_details, '$.receiving_date')) = '$receiving_date'";
                }
                if (!empty($whereClauses)) {
                    $sql .= ' AND ' . implode(' AND ', $whereClauses);
                }
                $Loadings = mysqli_query($connect, $sql);
                $row_count = $p_qty_total = $p_kgs_total = 0;
                $i = 1;
                $rowColor = '';
                $locked = 0;

                while ($SingleLoading = mysqli_fetch_assoc($Loadings)) {
                    $id = $SingleLoading['id'];
                    $sea_road = isset(transactionSingle($id)['sea_road']) ? transactionSingle($id)['sea_road'] : '';
                    if (isset($_GET['sea_road']) && !empty($_GET['sea_road'])) {
                        if ($_GET['sea_road'] !== $sea_road) {
                            continue;
                        }
                    }
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
                            <?php echo '<b>P#', $SingleLoading['p_id']; ?>
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
                url: 'ajax/viewSingleCargoLane.php',
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