<?php
require_once '../connection.php';
$print_url = '';
if (!empty($_GET['allot'])) {
    $allot = mysqli_real_escape_string($connect, $_GET['allot']);
    $query = "SELECT * FROM `transaction_items` WHERE allotment_name = '$allot' AND parent_id IN (SELECT id FROM transactions WHERE transfer_level >= 2)";
    $result = mysqli_query($connect, $query);

    if ($result) {
        $print_url = 'allot-stock-print?allot=' . $allot;
        $allEntries = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $processedEntries = [];
        $purchase_ids = [];
        $sold_ids = [];

        foreach ($allEntries as $entry) {
            $key = $entry['goods_id'] . '|' . $entry['size'] . '|' . $entry['brand'] . '|' . $entry['origin'];

            // Collect unique parent_ids for purchases and sales
            if ($entry['p_s'] === 'p') {
                if (!in_array($entry['parent_id'], $purchase_ids)) {
                    $purchase_ids[] = $entry['parent_id'];
                }

                // Add purchase entry to the processed list
                if (!isset($processedEntries[$key])) {
                    $processedEntries[$key] = [
                        'goods_id' => $entry['goods_id'],
                        'size' => $entry['size'],
                        'brand' => $entry['brand'],
                        'origin' => $entry['origin'],
                        'total_purchased_qty' => 0,
                        'total_purchased_kgs' => 0,
                        'total_purchased_net_kgs' => 0,
                        'total_sold_qty' => 0,
                        'total_sold_kgs' => 0,
                        'total_sold_net_kgs' => 0,
                    ];
                }
                $processedEntries[$key]['total_purchased_qty'] += $entry['qty_no'];
                $processedEntries[$key]['total_purchased_kgs'] += $entry['total_kgs'];
                $processedEntries[$key]['total_purchased_net_kgs'] += $entry['net_kgs'];
            } elseif ($entry['p_s'] === 's') {
                if (!in_array($entry['parent_id'], $sold_ids)) {
                    $sold_ids[] = $entry['parent_id'];
                }

                // Add sales data to the corresponding purchase entry
                if (isset($processedEntries[$key])) {
                    $processedEntries[$key]['total_sold_qty'] += $entry['qty_no'];
                    $processedEntries[$key]['total_sold_kgs'] += $entry['total_kgs'];
                    $processedEntries[$key]['total_sold_net_kgs'] += $entry['net_kgs'];
                }
            }
        }
?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Allot ( <?= $allot; ?> ) Print</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body{
                    font-size:12px;
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
                    <h5 class="modal-title" id="staticBackdropLabel">Allotment Details: ( <?= htmlspecialchars($allot); ?> )</h5>
                    <div>
                        <span>Purchase IDs:
                            <?php
                            foreach ($purchase_ids as $purchased_single_id) {
                                echo '<a href="purchases?t_id=' . htmlspecialchars($purchased_single_id) . '&print_type=contract">#' . htmlspecialchars($purchased_single_id) . '</a>, ';
                            }
                            ?>
                        </span> |
                        <span>Sale IDs:
                            <?php
                            foreach ($sold_ids as $sold_single_id) {
                                echo '<a href="sales?t_id=' . htmlspecialchars($sold_single_id) . '&print_type=contract">#' . htmlspecialchars($sold_single_id) . '</a>, ';
                            }
                            ?>
                        </span>
                    </div>

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
                            <th>No.</th>
                            <th>Goods (Name / Size / Brand / Origin)</th>
                            <th>Total Qty</th>
                            <th>T.G.Weight</th>
                            <th>T.N.Weight</th>
                            <th>Sold Qty</th>
                            <th>S.G.Weight</th>
                            <th>S.N.Weight</th>
                            <th>Rem Qty</th>
                            <th>Rem.G.Weight</th>
                            <th>Rem.N.Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Initialize totals
                        $total_purchased_qty = $total_purchased_kgs = $total_purchased_net_kgs = $total_sold_qty = $total_sold_kgs = $total_sold_net_kgs = $total_remaining_qty = $total_remaining_gross_weight = $total_remaining_net_weight = 0;

                        $i = 1;
                        foreach ($processedEntries as $entry):
                            $remaining_qty = $entry['total_purchased_qty'] - $entry['total_sold_qty'];
                            $remaining_gross_weight = $entry['total_purchased_kgs'] - $entry['total_sold_kgs'];
                            $remaining_net_weight = $entry['total_purchased_net_kgs'] - $entry['total_sold_net_kgs'];

                            // Update totals
                            $total_purchased_qty += $entry['total_purchased_qty'];
                            $total_purchased_kgs += $entry['total_purchased_kgs'];
                            $total_purchased_net_kgs += $entry['total_purchased_net_kgs'];
                            $total_sold_qty += $entry['total_sold_qty'];
                            $total_sold_kgs += $entry['total_sold_kgs'];
                            $total_sold_net_kgs += $entry['total_sold_net_kgs'];
                            $total_remaining_qty += $remaining_qty;
                            $total_remaining_gross_weight += $remaining_gross_weight;
                            $total_remaining_net_weight += $remaining_net_weight;
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($i); ?></td>
                                <td>
                                    <?= goodsName(htmlspecialchars($entry['goods_id'])) . ' / ' .
                                        htmlspecialchars($entry['size']) . ' / ' .
                                        htmlspecialchars($entry['brand']) . ' / ' .
                                        htmlspecialchars($entry['origin']); ?>
                                </td>
                                <td class="fw-bold text-success"><?= round($entry['total_purchased_qty']); ?></td>
                                <td class="fw-bold text-success"><?= round($entry['total_purchased_kgs']); ?></td>
                                <td class="fw-bold text-success"><?= round($entry['total_purchased_net_kgs']); ?></td>
                                <td class="fw-bold text-danger"><?= round($entry['total_sold_qty']); ?></td>
                                <td class="fw-bold text-danger"><?= round($entry['total_sold_kgs']); ?></td>
                                <td class="fw-bold text-danger"><?= round($entry['total_sold_net_kgs']); ?></td>
                                <td class="fw-bold text-primary"><?= round($remaining_qty); ?></td>
                                <td class="fw-bold text-primary"><?= round($remaining_gross_weight); ?></td>
                                <td class="fw-bold text-primary"><?= round($remaining_net_weight); ?></td>
                            </tr>
                        <?php
                            $i++;
                        endforeach;
                        ?>
                        <!-- Totals Row -->
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><i class="far fa-circle"></i></td>
                            <td class="fw-bold">Allot Totals: </td>
                            <td class="fw-bold text-success"><?= number_format(round($total_purchased_qty)); ?></td>
                            <td class="fw-bold text-success"><?= number_format(round($total_purchased_kgs)); ?></td>
                            <td class="fw-bold text-success"><?= number_format(round($total_purchased_net_kgs)); ?></td>
                            <td class="fw-bold text-danger"><?= number_format(round($total_sold_qty)); ?></td>
                            <td class="fw-bold text-danger"><?= number_format(round($total_sold_kgs)); ?></td>
                            <td class="fw-bold text-danger"><?= number_format(round($total_sold_net_kgs)); ?></td>
                            <td class="fw-bold text-primary"><?= number_format(round($total_remaining_qty)); ?></td>
                            <td class="fw-bold text-primary"><?= number_format(round($total_remaining_gross_weight)); ?></td>
                            <td class="fw-bold text-primary"><?= number_format(round($total_remaining_net_weight)); ?></td>

                        </tr>
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
} ?>