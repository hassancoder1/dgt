<?php
$page_title = 'G. Loading B/L Print';
$pageURL = 'bl-no-print';
require("../connection.php");
$remove = $bl_no = '';
$is_search = false;
global $connect;
$bl_no = $_GET['bl_no'];
$LoadingTable = $_GET['loading'] . '_loading';
$BL = mysqli_fetch_assoc(fetch($LoadingTable, ['bl_no' => $bl_no]));
$L = json_decode($BL['loading_info'] ?? '[]', true);
$G = json_decode($BL['goods_info'] ?? '[]', true);
$A = json_decode($BL['agent_info'] ?? '[]', true);
$W = json_decode($BL['warehouse_info'] ?? '[]', true);

$t_id = $BL['t_id'];
$orders = [];
$BlOrder = mysqli_fetch_all($connect->query("SELECT * FROM $LoadingTable WHERE t_id = '$t_id' ORDER BY created_at"), MYSQLI_ASSOC);
foreach ($BlOrder as $key => $order) {
    $orders[$order['bl_no']] = $key + 1;
}
$fAgent = !empty($A) ? reset($A) : [];
$Ag_acc_no = $fAgent['ag_acc_no'] ?? '';
$AGAcc = mysqli_fetch_assoc(mysqli_query($connect, "SELECT id, email, phone FROM khaata WHERE LOWER(khaata_no) = LOWER('$Ag_acc_no')"));
$AGAcc_id = $AGAcc['id'] ?? '';
$AGCompany = mysqli_fetch_assoc(mysqli_query($connect, "SELECT json_data FROM khaata_details WHERE khaata_id = '$AGAcc_id' ORDER BY created_at ASC LIMIT 1"));
$AGCompany = json_decode($AGCompany['json_data'] ?? '[]', true);
$AGCombine = array_combine(isset($AGCompany['indexes1']) ? $AGCompany['indexes1'] : [], isset($AGCompany['vals1']) ? $AGCompany['vals1'] : []);

$company_names = [];
$unique_khaata_ids = array_filter(array_unique([
    $L['importer']['im_acc_kd_id'] ?? null,
    $L['exporter']['xp_acc_kd_id'] ?? null,
    $L['notify']['np_acc_kd_id'] ?? null
]));
if (!empty($unique_khaata_ids)) {
    $khaata_ids_str = implode(",", $unique_khaata_ids);
    $CompanyQuery = mysqli_query($connect, "SELECT id, json_data FROM khaata_details WHERE id IN ($khaata_ids_str)");
    while ($myRow = mysqli_fetch_assoc($CompanyQuery)) {
        $json_data = json_decode($myRow['json_data'], true);
        $company_names[$myRow['id']] = $json_data['company_name'];
    }
}
$IMCompany = $company_names[$L['importer']['im_acc_kd_id']] ?? 'N/A';
$XPCompany = $company_names[$L['exporter']['xp_acc_kd_id']] ?? 'N/A';
$NPCompany = $company_names[$L['notify']['np_acc_kd_id']] ?? 'N/A';

$print_url = 'print/bl-no-print.php?bl_no=' . $_GET['bl_no'] . '&loading=' . $_GET['loading'];
if (isset($_GET['agent-print'])) {
    $print_url .= '&agent-print=' . $_GET['agent-print'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BL Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/fonts/lexend.css">
    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    echo "</script>";
    echo "<style>";
    include '../assets/css/custom.css';
    echo "</style>";
    ?>
    <style>
        * {
            font-family: 'Lexend', serif;
        }

        body {
            font-size: 13px;
            background-color: #f8f9fa;
        }

        .section-header {
            border-bottom: 1px solid #444;
            margin-bottom: 1rem;
        }

        .bordered {
            border: 1px solid #444;
        }

        .custom-table th,
        .custom-table td {
            font-size: 12px;
            text-align: center;
            vertical-align: middle;
        }

        .signature-box {
            border-top: 1px solid #444;
            max-width: 130px;
            margin-top: 1rem;
            padding: 10px;
        }

        .fw-light-muted {
            font-weight: 300;
            color: #6c757d;
        }

        .header-logo img {
            max-width: 150px;
        }

        .text-highlight {
            font-weight: bold;
            color: #0d6efd;
        }

        .row {
            position: relative;
        }

        .container {
            width: 210mm;
        }

        @media print {
            body {
                background-color: white !important;
            }

            .container {
                max-width: 100vw !important;
                border: none !important;
            }

            .hide-on-print {
                display: none;
            }

        }
    </style>
</head>

<body>
    <div class="container border bg-white p-3 pb-0 rounded m-3 mx-auto">
        <div class="position-absolute top-0 end-0 mt-2 me-3 d-flex align-items-center gap-2 bg-white">
            <div class="dropdown hide-on-print">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="openAndPrint('<?= str_replace('print/', '', $print_url); ?>')">
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
        <div>
            <div class="row mb-4">
                <!-- Left Column -->
                <div class="col-6">
                    <div class="section-header mb-2">
                        <h6 class="fw-bold">Importer</h6>
                    </div>
                    <ul class="list-unstyled fw-light-muted">
                        <li><b>Company Name:</b> <?= htmlspecialchars($IMCompany); ?></li>
                        <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($IMCompany, '', $L['importer']['im_acc_details'])); ?></li>
                    </ul>

                    <div class="section-header mb-2">
                        <h6 class="fw-bold">Exporter</h6>
                    </div>
                    <ul class="list-unstyled fw-light-muted">
                        <li><b>Company Name:</b> <?= htmlspecialchars($XPCompany); ?></li>
                        <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($XPCompany, '', $L['exporter']['xp_acc_details'])); ?></li>
                    </ul>

                    <div class="section-header mb-2">
                        <h6 class="fw-bold">Notify</h6>
                    </div>
                    <ul class="list-unstyled fw-light-muted">
                        <li><b>Company Name:</b> <?= htmlspecialchars($NPCompany); ?></li>
                        <li><b>Company Details:</b> <?= htmlspecialchars(str_replace($NPCompany, '', $L['notify']['np_acc_details'])); ?></li>
                    </ul>

                    <div>
                        <h6 class="fw-bold mt-3">Transfer Warehouse:</h6>
                        <p class="fw-light-muted"><?= !empty($W) ? reset($W)['warehouse'] : 'N/A'; ?></p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-6">
                    <div class="border p-2 pt-3 mb-2 text-center">
                        <div class="header-logo">
                            <img src="../assets/images/logo.png" alt="logo" class="img-fluid">
                        </div>
                        <h6 class="fw-bold mt-2">DAMAAN GENERAL TRADING LLC</h6>
                        <p class="text-muted">Booking Ref.: <?= ucfirst($BL['p_s']); ?>#<?= $BL['t_sr'] . " (" . $orders[$BL['bl_no']] . ")" ?> - B/L Number: #<?= $BL['bl_no']; ?></p>
                    </div>

                    <div class="border p-2 mb-2">
                        <h6 class="fw-bold">Shipper</h6>
                        <ul class="list-unstyled">
                            <li><b>Name:</b> <?= htmlspecialchars($L['shipping']['shipping_name']); ?></li>
                            <li><b>Address:</b> <?= htmlspecialchars($L['shipping']['shipping_address']); ?></li>
                            <li><b>Phone:</b> <?= htmlspecialchars($L['shipping']['shipping_phone']); ?></li>
                            <li><b>WhatsApp:</b> <?= htmlspecialchars($L['shipping']['shipping_whatsapp']); ?></li>
                            <li><b>Email:</b> <?= htmlspecialchars($L['shipping']['shipping_email']); ?></li>
                        </ul>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="border p-2">
                                <h6 class="fw-bold">Loading</h6>
                                <p class="mb-0"><b>Date:</b> <?= htmlspecialchars($L['loading']['loading_date']); ?></p>
                                <p class="mb-0"><b>Country:</b> <?= htmlspecialchars($L['loading']['loading_country']); ?></p>
                                <p><b><?= $L['shipping']['transfer_by'] === 'sea' ? 'Port' : 'Border'; ?>:</b> <?= htmlspecialchars($L['loading']['loading_port_name']); ?></p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border p-2">
                                <h6 class="fw-bold">Receiving</h6>
                                <p class="mb-0"><b>Date:</b> <?= htmlspecialchars($L['receiving']['receiving_date']); ?></p>
                                <p class="mb-0"><b>Country:</b> <?= htmlspecialchars($L['receiving']['receiving_country']); ?></p>
                                <p><b><?= $L['shipping']['transfer_by'] === 'sea' ? 'Port' : 'Border'; ?>:</b> <?= htmlspecialchars($L['receiving']['receiving_port_name']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border p-2 pb-0 mb-2 d-flex justify-content-between">
                <div>
                    <h6 class="fw-bold">Clearing Agent:</h6>
                    <p><b>Acc No:</b> <?= htmlspecialchars(isset($fAgent['ag_acc_no']) ? $fAgent['ag_acc_no'] : 'N/A'); ?> <b>Name:</b> <?= htmlspecialchars(isset($fAgent['ag_name']) ? $fAgent['ag_name'] : 'N/A'); ?></p>
                </div>
                <ul class="list-unstyled">
                    <li><b>Company:</b> <?= htmlspecialchars(isset($AGCompany['company_name']) ? $AGCompany['company_name'] : 'N/A'); ?></li>
                    <li><b>Weight No:</b> <?= htmlspecialchars(isset($AGCombine['WEIGHT']) ? $AGCombine['WEIGHT'] : 'N/A'); ?>
                        <b> License No:</b> <?= htmlspecialchars(isset($AGCombine['License']) ? $AGCombine['License'] : 'N/A'); ?>
                    </li>
                    <li><b>Email:</b> <?= htmlspecialchars(isset($AGAcc['email']) ? $AGAcc['email'] : 'N/A'); ?> <b>Phone:</b> <?= htmlspecialchars(isset($AGAcc['phone']) ? $AGAcc['phone'] : 'N/A'); ?></li>
                </ul>
            </div>

            <div class="custom-table">
                <table class="table table-bordered table-sm">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th>Container</th>
                            <th>Quantity</th>
                            <th>Goods Description</th>
                            <th>Gross Weight</th>
                            <th>Net Weight</th>
                            <?php if (isset($_GET['agent-print'])) { ?>
                                <th>BOE No</th>
                                <th>PickUp.D</th>
                                <th>Waiting Days</th>
                                <th>Return.D</th>
                                <th>Transporter</th>
                                <th>Truck No.</th>
                                <th>Details</th>
                                <th>Driver Name</th>
                                <th>Driver No</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($G as $key => $g) {
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($g['container_no']) . ' / ' . htmlspecialchars($g['container_name']); ?></td>
                                <td><?= htmlspecialchars($g['quantity_no']) . ' / ' . htmlspecialchars($g['quantity_name']); ?></td>
                                <td><?= htmlspecialchars(goodsName($g['good']['goods_id'])) . ' / ' . htmlspecialchars($g['good']['size']) . ' / ' . htmlspecialchars($g['good']['brand']) . ' / ' . htmlspecialchars($g['good']['origin']); ?></td>
                                <td><?= htmlspecialchars($g['gross_weight']); ?></td>
                                <td><?= htmlspecialchars($g['net_weight']); ?></td>
                                <?php if (isset($_GET['agent-print'])) { ?>
                                    <td><?= $A[$key]['boe_no'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['pick_up_date'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['waiting_days'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['return_date'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['transporter_name'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['truck_number'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['details'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['driver_name'] ?? 'Not Given!'; ?></td>
                                    <td><?= $A[$key]['driver_number'] ?? 'Not Given!'; ?></td>
                                <?php } ?>
                            </tr>
                        <?php }; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-3 position-relative">
                <div class="col-8">
                    <div class="signature-box mx-auto mb-auto position-absolute bottom-0 start-0">
                        <h6 class="fw-bold">Signature</h6>
                    </div>
                </div>
                <div class="col-4 text-end pb-2">
                    <h6 class="fw-bold">Totals:</h6>
                    <span><b>Containers:</b> <?= count($G); ?></span><br>
                    <span><b>Quantity:</b> <?= array_sum(array_column($G, 'quantity_no')); ?></span><br>
                    <span><b>Gross Weight:</b> <?= array_sum(array_column($G, 'gross_weight')); ?></span><br>
                    <span><b>Net Weight:</b> <?= array_sum(array_column($G, 'net_weight')); ?></span>
                </div>
            </div>
            <div class="col-12 p-2 text-center border-top text-muted">This is a Computer Generated Print - Errors are expected!</div>
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
                                const emailURL = `/emails?page=compose&file-url=${fileURL}&file-name=${formattedFileName}&page-name=${formattedName}`;
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
        <?php include("../assets/bs/js/bootstrap.bundle.min.js"); ?>
    </script>
</body>

</html>