<?php
require '../connection.php';
$page_title = 'CUSTOM CLEARING => WAREHOUSE GENERAL';
$CCWPageMapping = [
    'transit' => 'Transit',
    'freezone-import' => 'Free Zone Import',
    'local-import' => 'Local Import',
    'import-re-export' => 'Import Re-Export',
    'local-export' => 'Local Export',
    'local-market' => 'Local Market',
    'all' => 'All WareHouses'
];
$CCWPage = $CCWPageMapping[$_GET['CCWpage'] ?? ''] ?? '';
$page_title .= " ($CCWPage)";
$pageURL = "custom-clearing-warehouse?CCWpage=" . ($_GET['CCWpage'] ?? '');
$filters = [
    'size' => '',
    'brand' => '',
    'origin' => '',
    'goods_id' => '',
    'date_from' => '',
    'date_to' => '',
    'net_kgs' => '',
    'qty_no' => ''
];
$is_search = false;
$rows_per_page = 50;
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $rows_per_page;
global $connect;
$conditions = [];
// Handle filters
if ($_GET) {
    $resetFilters = removeFilter($pageURL);
    foreach ($filters as $key => &$value) {
        if (!empty($_GET[$key])) {
            $value = mysqli_real_escape_string($connect, $_GET[$key]);
            switch ($key) {
                case 'size':
                case 'brand':
                case 'origin':
                case 'goods_id':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.$key') = '$value'";
                    break;
                case 'date_from':
                    $conditions[] = "created_at >= '$value'";
                    break;
                case 'date_to':
                    $conditions[] = "created_at <= '$value'";
                    break;
                case 'net_kgs':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.goods_json.$key') = '$value'";
                    break;
                case 'qty_no':
                    $conditions[] = "JSON_EXTRACT(ldata, '$.good.goods_json.$key') = '$value'";
                    break;
            }
        }
    }
}
if ($_GET['CCWpage'] !== 'all') {
    $NotAll = "data_for='$CCWPage' AND";
} else {
    $NotAll = '';
}
$where_clause = !empty($conditions) ? ' AND ' . implode(' AND ', $conditions) : '';
$sql = "SELECT * FROM data_copies WHERE $NotAll unique_code LIKE 'p%'";
$count_sql = "SELECT COUNT(*) AS total FROM ({$sql}) AS subquery";
$total_rows_result = mysqli_query($connect, $count_sql);
$total_rows = mysqli_fetch_assoc($total_rows_result)['total'];
$sql .= " ORDER BY created_at DESC LIMIT $rows_per_page OFFSET $offset";
$data = mysqli_query($connect, $sql);
$entries = [];
while ($one = mysqli_fetch_assoc($data)) {
    if ($one['id'] !== null) {
        $entries[] = $one;
    }
}
$total_pages = ceil($total_rows / $rows_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Warehouse Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-size: 12px;
            color: black;
            background-color: white;
            position: relative;
        }

        .page-break {
            margin-top: 10mm;
        }

        .container {
            width: 210mm;
        }

        @media print {
            .container {
                max-width: 100vw !important;
            }

            .hide-on-print {
                display: none;
            }

            .page-break {
                page-break-before: always;
                /* Forces a page break before this section */
            }
        }

        .signature-box {
            border-top: 1px solid black;
            margin-top: 20px;
            text-align: center;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div>
        <h2 class="text-dark fw-bold my-3 mx-2">WAREHOUSE GENERAL PRINT</h2>
        <div class="position-absolute top-0 end-0 mt-2 me-3 d-flex align-items-center gap-2">
            <button class="btn btn-warning fw-bold btn-sm hide-on-print" type="button" onclick="window.print();">
                PRINT
            </button>
        </div>
    </div>
    <div class="row mx-1">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-body p-0">
                    <?php
                    $i = $offset + 1;

                    // Separate entries into categories
                    $redEntries = [];
                    $yellowEntries = [];
                    $darkEntries = [];

                    foreach ($entries as $entry) {
                        $ldata = json_decode($entry['ldata'], true);
                        $unique_code = $entry['unique_code'];
                        [$Ttype, $Tcat, $Troute, $TID, $LID] = decode_unique_code($unique_code, 'all');
                        $TotalQty = $ldata['good']['quantity_no'];
                        $RemQty = $ldata['good']['goods_json']['qty_no'];
                        $SoldQty = $TotalQty - $RemQty;

                        // Categorize entries
                        if ($SoldQty === 0) {
                            $redEntries[] = $entry;
                        } elseif ($RemQty > 0) {
                            $yellowEntries[] = $entry;
                        } else {
                            $darkEntries[] = $entry;
                        }
                    }

                    // Combine sorted entries
                    $sortedEntries = array_merge($redEntries, $yellowEntries, $darkEntries);
                    ?>

                    <table class="table table-bordered table-hover table-sm fix-head-table mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th>No.</th>
                                <th>P/S# (SR#)</th>
                                <?php if ($_GET['CCWpage'] === 'all') { ?>
                                    <th>Warehouse</th>
                                <?php } ?>
                                <th>BL / UID</th>
                                <th>Type</th>
                                <?php if ($_GET['CCWpage'] === 'all') { ?>
                                    <th>Transferred To P/S#</th>
                                <?php } ?>
                                <th>Allot</th>
                                <th>Goods Name / ORIGIN</th>
                                <th>Total Qty</th>
                                <th>Sold Qty</th>
                                <th>Rem Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($sortedEntries as $entry) {
                                $ldata = json_decode($entry['ldata'], true);
                                $unique_code = $entry['unique_code'];
                                [$Ttype, $Tcat, $Troute, $TID, $LID] = decode_unique_code($unique_code, 'all');
                                $TotalQty = $ldata['good']['quantity_no'];
                                $RemQty = $ldata['good']['goods_json']['qty_no'];
                                $SoldQty = $TotalQty - $RemQty;

                                // Determine row color
                                if ($SoldQty === 0) {
                                    $rowColor = 'fw-bold text-danger';
                                } elseif ($RemQty > 0) {
                                    $rowColor = 'fw-bold text-warning';
                                } else {
                                    $rowColor = 'fw-bold text-dark';
                                }
                                $trans = $ldata['transfer']['sold_to'] ?? $ldata['transfer']['sold_from'] ?? [];
                                $TIDS = [];
                                foreach ($trans as $one) {
                                    $TIDS[] = '#' . decode_unique_code(explode('~', $one)[0], 'TID');
                                }
                                $TIDS = array_unique($TIDS);
                                $trans = implode(', ', $TIDS);
                            ?>
                                <tr class="text-nowrap">
                                    <td class="<?= $rowColor; ?>"><?= htmlspecialchars($i); ?></td>
                                    <td class="<?= $rowColor; ?> pointer"
                                        onclick="window.location.href = '?view=1&unique_code=<?= $unique_code; ?>&print_type=contract&CCWpage=<?= $_GET['CCWpage']; ?>';">
                                        <b><?= ucfirst($ldata['type']); ?>#</b> <?= htmlspecialchars($ldata['p_id']); ?> (<?= htmlspecialchars($ldata['sr_no']); ?>)
                                    </td>
                                    <?php if ($_GET['CCWpage'] === 'all') { ?>
                                        <td class="<?= $rowColor; ?>"><?= htmlspecialchars($ldata['transfer']['warehouse_transfer']); ?></td>
                                    <?php } ?>
                                    <td class="<?= $rowColor; ?>"><?= htmlspecialchars($Tcat !== 'l' ? 'B/L: ' . $ldata['bl_no'] : 'UID: ' . $ldata['uid']); ?></td>
                                    <td class="<?= $rowColor; ?>"><?= ucfirst(htmlspecialchars($ldata['p_type'] ?? 'local')); ?></td>
                                    <?php if ($_GET['CCWpage'] === 'all') { ?>
                                        <td class="<?= $rowColor; ?>"><?= !empty($trans) ? $trans : 'Not Transferred!'; ?></td>
                                    <?php } ?>
                                    <td class="<?= $rowColor; ?>"><?= $ldata['good']['goods_json']['allotment_name']; ?></td>
                                    <td class="<?= $rowColor; ?>"><?= goodsName(htmlspecialchars($ldata['good']['goods_id'])) . ' / ' . htmlspecialchars($ldata['good']['origin']); ?></td>
                                    <td class="fw-bold text-success"><?= htmlspecialchars($TotalQty); ?> <sub><?= htmlspecialchars($ldata['good']['goods_json']['qty_name']); ?></sub></td>
                                    <td class="fw-bold text-danger"><?= htmlspecialchars($SoldQty); ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($RemQty); ?></td>
                                </tr>
                            <?php
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
                <!-- Pagination -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <span class="text-muted small">
                        Showing <?php echo min($offset + 1, $total_rows); ?> to <?php echo min($offset + $rows_per_page, $total_rows); ?> of <?php echo $total_rows; ?> entries
                    </span>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <div class="position-fixed top-0 start-0 w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(25, 26, 25, 0.4); z-index: 60;" id="processingScreen">
        <div class="spinner-border text-white" style="width: 5rem; height: 5rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <script>
        <?php include("../assets/js/jquery-3.7.1.min.js"); ?>

        function openAndPrint(url) {
            const newWindow = window.open(
                url,
                '_blank',
                'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=' + screen.width + ',height=' + screen.height
            );
            newWindow.onload = () => {
                newWindow.print();
            };
        }

        function getFileThrough(fileType, url) {
            $('#processingScreen').toggleClass('d-none d-flex');
            let formattedFileName = url
                .split('?')[0] // Remove query parameters and their values
                .replace(/^print\//, '')
                .replace(/-main|-print$/, '')
                .trim();
            let formattedName = formattedFileName
                .replace(/-/g, ' ')
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                .join(' ');

            $.ajax({
                url: `${window.location.protocol}//${window.location.host}/ajax/generateFile.php`,
                type: 'post',
                data: {
                    filetype: fileType,
                    pageURL: url
                },
                success: function(response) {
                    $('#processingScreen').toggleClass('d-none d-flex');
                    try {
                        const result = JSON.parse(response);
                        if (result.fileURL) {
                            const fileURL = result.fileURL;
                            if (fileType === 'pdf' || fileType === 'word') {
                                fetch(fileURL)
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error(`HTTP error! Status: ${response.status}`);
                                        }
                                        return response.blob();
                                    })
                                    .then(blob => {
                                        const currentTime = Date.now();
                                        const fileExtension = fileType === 'pdf' ? 'pdf' : 'docx';
                                        const fileName = `Print-${formattedFileName}${currentTime}.${fileExtension}`;
                                        const downloadLink = document.createElement('a');
                                        const objectURL = URL.createObjectURL(blob);
                                        downloadLink.href = objectURL;
                                        downloadLink.download = fileName;
                                        document.body.appendChild(downloadLink);
                                        downloadLink.click();
                                        URL.revokeObjectURL(objectURL);
                                        document.body.removeChild(downloadLink);
                                    })
                                    .catch(error => {
                                        console.error('Error downloading file:', error);
                                        alert('Failed to download the file.');
                                    });
                            } else if (fileType === 'whatsapp') {
                                const whatsappURL = `https://wa.me/?text=Your+file+${encodeURIComponent(formattedName)}+is+ready!+Download+it+here:+${encodeURIComponent(fileURL)}`;
                                window.open(whatsappURL, '_blank');
                            } else if (fileType === 'email') {
                                const emailURL = `mailto:?subject=Your+Requested+File+-+${encodeURIComponent(formattedName)}&body=Hello,%0A%0AYour+file+${encodeURIComponent(formattedName)}+is+ready+for+download!%0A%0AAccess+it+here:+${encodeURIComponent(fileURL)}`;
                                window.open(emailURL, '_blank');
                            }

                        } else {
                            alert('Failed to retrieve the file URL.');
                            console.log(result.error);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                        alert('Invalid response format received from the server.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Hide the processing screen
                    $('#processingScreen').toggleClass('d-none d-flex');

                    console.error("AJAX Error: ", textStatus, errorThrown);
                    alert('An error occurred while processing your request. Please refresh and try again.');
                }
            });
        }
        $('#print_type').change(function() {
            window.location.href = '?t_id=<?= $T['id']; ?>&print_type=' + $(this).val() + '&timestamp=<?= $_GET['timestamp']; ?>';
        });
        <?php include("../assets/bs/js/bootstrap.bundle.min.js"); ?>
    </script>
</body>

</html>