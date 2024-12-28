<?php
require_once '../connection.php';
$print_url = 'container-stock-print';
if (!empty($_GET['container'])) {
    $container = mysqli_real_escape_string($connect, $_GET['container']);
    $query = "SELECT * FROM data_copies WHERE unique_code LIKE 'p%' AND unique_code NOT LIKE 'pl%' AND JSON_EXTRACT(ldata, '$.good.container_no') = '$container'";
    $result = mysqli_query($connect, $query);
    $print_url .= '?container=' . $container;
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Allot ( <?= $container; ?> ) Print</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="../assets/fonts/lexend.css" rel="stylesheet">
        <style>
            * {
                font-family: 'Lexend', serif;
            }

            body {
                font-size: 12px;
            }

            @media print {
                .hide-on-print {
                    display: none;
                }
            }
        </style>
    </head>

    <body>
        <div class="p-2 mx-auto">
            <div class="modal-header d-flex justify-content-between bg-white align-items-center p-3">
                <h5 class="modal-title" id="staticBackdropLabel">Container No: ( <?= htmlspecialchars($container); ?> )</h5>
                <div class="d-flex align-items-center justify-content-end gap-2 hide-on-print">
                    <div class="dropdown hide-on-print">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-print"></i>
                        </button>
                        <ul class="dropdown-menu mt-2" aria-labelledby="dropdownMenuButton">
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
            <table class="table table-bordered table-hover table-sm">
                <thead>
                    <tr class="text-nowrap">
                        <th>No.#</th>
                        <th>P | S</th>
                        <th>Goods (Name / Size / Brand / Origin)</th>
                        <th colspan="3">Total Qty</th>
                        <th colspan="3">Gross Weight</th>
                        <th colspan="3">Net Weight</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    while ($entry = mysqli_fetch_assoc($result)) {
                        $ldata = json_decode($entry['ldata'], true);
                        $G = $ldata['good'];

                        $total_purchased_qty = $ldata['good']['quantity_no'] ?? 0;
                        $total_purchased_kgs = $ldata['good']['gross_weight'] ?? 0;
                        $total_purchased_net_kgs = $ldata['good']['net_weight'] ?? 0;
                        $total_sold_qty = 0;
                        $total_sold_kgs = 0;
                        $total_sold_net_kgs = 0;
                    ?>
                        <tr class="table-primary">
                            <td><?= htmlspecialchars($i); ?></td>
                            <td>P# <?= $ldata['p_sr']; ?></td>
                            <td>
                                <?= goodsName(htmlspecialchars($G['goods_id'])) . ' / ' .
                                    htmlspecialchars($G['size']) . ' / ' .
                                    htmlspecialchars($G['brand']) . ' / ' .
                                    htmlspecialchars($G['origin']); ?>
                            </td>
                            <td colspan="3" class="fw-bold text-success"><?= round($total_purchased_qty); ?></td>
                            <td colspan="3" class="fw-bold text-success"><?= round($total_purchased_kgs); ?></td>
                            <td colspan="3" class="fw-bold text-success"><?= round($total_purchased_net_kgs); ?></td>
                        </tr>
                        <?php

                        // Display Sub-Entries
                        if (isset($ldata['transfer']['sold_to'])) {
                            foreach ($ldata['transfer']['sold_to'] as $sold) {
                                $soldData = explode('~', $sold);

                                // Accumulate Totals
                                $total_sold_qty += $soldData[3] ?? 0;
                                $total_sold_kgs += $soldData[5] ?? 0;
                                $total_sold_net_kgs += $soldData[6] ?? 0;

                        ?>
                                <tr>
                                    <td></td>
                                    <td>S# <?= htmlspecialchars(getTransactionSr(decode_unique_code($soldData[0], 'TID'))); ?></td>
                                    <td>
                                        <?= goodsName(htmlspecialchars($G['goods_id'])) . ' / ' .
                                            htmlspecialchars($G['size']) . ' / ' .
                                            htmlspecialchars($G['brand']) . ' / ' .
                                            htmlspecialchars($G['origin']); ?>
                                    </td>
                                    <td colspan="3" class="fw-bold text-danger"><?= round($soldData[3] ?? 0); ?></td>
                                    <td colspan="3" class="fw-bold text-danger"><?= round($soldData[5] ?? 0); ?></td>
                                    <td colspan="3" class="fw-bold text-danger"><?= round($soldData[6] ?? 0); ?></td>
                                </tr>
                        <?php
                            }
                        }

                        // Calculate Remaining
                        $total_remaining_qty = $total_purchased_qty - $total_sold_qty;
                        $total_remaining_gross_weight = $total_purchased_kgs - $total_sold_kgs;
                        $total_remaining_net_weight = $total_purchased_net_kgs - $total_sold_net_kgs;

                        // Display Remaining Totals
                        ?>
                        <tr class="table-warning">
                            <td></td>
                            <td colspan="2" class="fw-bold">Remaining Totals:</td>
                            <td colspan="3" class="fw-bold text-primary"><?= round($total_remaining_qty); ?></td>
                            <td colspan="3" class="fw-bold text-primary"><?= round($total_remaining_gross_weight); ?></td>
                            <td colspan="3" class="fw-bold text-primary"><?= round($total_remaining_net_weight); ?></td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
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
            <?php include("../assets/bs/js/bootstrap.bundle.min.js"); ?>
        </script>
    </body>

    </html>
<?php }
?>