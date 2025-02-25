<?php
require_once '../connection.php';
if (isset($_GET['print_enteries'])) {
    $itemIds = [];
    $itemSrs = [];
    foreach (explode('~', $_GET['print_enteries']) as $printItems) {
        $ItemInfo = explode('-', $printItems);
        $_GET['t_id'] = $ItemInfo[0];
        $itemIds[] = $ItemInfo[1];
        $itemSrs[] = $ItemInfo[2];
    }
    $Ttempdata = mysqli_fetch_assoc(fetch('transactions', ['id' => $_GET['t_id']]));
    $T = array_merge(
        transactionSingle($Ttempdata['id']),
        ['sea_road_array' => json_decode($Ttempdata['sea_road'], true)] ?? [],
        ['notify_party_details' => json_decode($Ttempdata['notify_party_details'], true)] ?? [],
        ['third_party_bank' => json_decode($Ttempdata['third_party_bank'], true)] ?? [],
        ['reports' => json_decode($Ttempdata['reports'], true)] ?? []
    );
    $itemIdsString = implode(",", $itemIds);
    $Items = mysqli_fetch_all($connect->query("SELECT * FROM commission_items WHERE id IN ($itemIdsString) ORDER BY created_at DESC"), MYSQLI_ASSOC);
    if (!empty($Items)) {
        $Item = end($Items);
    } else {
        $Item = null;
    }
} else {
    header('Location: ../');
    exit();
}
$parent_item = [];
foreach ($T['items'] as $pItem) {
    if ($pItem['id'] == $Item['item_id']) {
        $parent_item = $pItem;
        break;
    }
}
$pItemID = $parent_item['id'];
$Ldata = mysqli_fetch_assoc($connect->query("SELECT bl_no, goods_info FROM general_loading WHERE t_type='commission' AND JSON_EXTRACT(goods_info, '$.*.good.id') = '$pItemID'")) ?? [];
$goodsInfo = json_decode($Ldata['goods_info'] ?? '[]', true);
$firstItem = reset($goodsInfo) ?: [];
$ItemLdata = [
    'bl_no' => $Ldata['bl_no'] ?? '',
    'container_no' => $firstItem['container_no'] ?? '',
'container_name' => $firstItem['container_name'] ?? '',
    'warehouse' => ''
];

$print_url = 'print/commission-item-print.php?print_enteries=' . $_GET['print_enteries'] . '&print_type=' . ($_GET['print_type'] ?? 'full');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Commission Item Invoice Print</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/fonts/lexend.css">
    <style>
        * {
            font-family: 'Lexend', serif;
        }

        body {
            font-size: 12px;
            color: black;
            background-color: white;
            position: relative;
        }

        .page-break {
            margin-top: 10mm;
        }

        .mycontainer {
            width: 210mm;
            margin-top: 50px;
        }

        @media print {
            .mycontainer {
                max-width: 100vw !important;
                /* border: none !important; */
                padding: 0 !important;
                margin: 0;
            }

            .hide-on-print {
                display: none;
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
    <div class="position-absolute mt-2 me-3 d-flex align-items-center gap-2" style="top:-50px; right:0;">
        <a href="../sales-commission-form?view=1&&t_id=<?= $_GET['t_id']; ?>" class="btn btn-sm hide-one-print btn-outline-secondary text-nowrap"> <i class="fa fa-arrow-left"></i> Go Back</a>
        <select name="print_type" id="print_type" class="form-select form-select-sm hide-on-print">
            <option value="full" <?= $_GET['print_type'] === 'full' ? 'selected' : ''; ?>>Full Print</option>
            <option value="customer" <?= $_GET['print_type'] === 'customer' ? 'selected' : ''; ?>>Customer Print</option>
        </select>
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
    <div class="mycontainer mx-auto border border-dark">
        <div class="py-1 pb-0 text-center border-bottom border-dark">
            <h5 class="fw-bold">TAX INVOICE</h5>
        </div>
        <!-- Header Section -->
        <div class="row m-0">
            <div class="col-6 border-end border-dark">
                <div class="p-2 px-1">
                    <img src="logo.jpg" style="width:80px;height:80px;border-radius:100%;" alt="">
                    <h4 class="mb-1">Seller Details</h4>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $T['cr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T['cr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($T['cr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T['cr_acc_kd_id']), '', $T['cr_acc_details']); ?></span>
                </div>
                <hr class="border-top border-dark opacity-100 m-0 p-0">
                <div class="p-2 px-1">
                    <h4 class="mb-1">Buyer Details</h4>
                    <div class="hide-on-print">
                        <span><strong>Acc Name: </strong> <?= $T['dr_acc_name']; ?> | <strong>Acc No: </strong> <?= $T['dr_acc']; ?></span><br>
                    </div>
                    <span><strong>Company:</strong> <?= getCompanyName($T['dr_acc_kd_id']) ?? 'Not Found!'; ?></span><br>
                    <span><?= str_replace(getCompanyName($T['dr_acc_kd_id']), '', $T['dr_acc_details']); ?></span>
                </div>
            </div>
            <div class="col-6">
                <!-- Invoice Details Grid -->
                <div class="row border border-dark border-start-0 border-top-0 border-end-0">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">Sale #</div>
                        <div>S# <?= $T['sr']; ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">Sale Date</div>
                        <div><?= my_date($T['_date']); ?></div>
                    </div>
                </div>

                <div class="row border border-dark border-start-0 border-top-0 border-end-0">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">Invoice No.</div>
                        <div>#<?= implode('/', $itemSrs); ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">Invoice Date</div>
                        <div><?= my_date($Item['created_at']); ?></div>
                    </div>
                </div>

                <div class="row border border-dark border-start-0 border-top-0 border-end-0">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">Branch</div>
                        <div><?= branchName($T['branch_id']); ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">ID</div>
                        <div><?= $T['username']; ?></div>
                    </div>
                </div>

                <div class="row border border-dark border-start-0 border-top-0 border-end-0">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">Origin</div>
                        <div><?= ucfirst($parent_item['origin']); ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">Warehouse</div>
                        <div></div>
                    </div>
                </div>

                <div class="row border border-dark border-start-0 border-top-0 border-end-0">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">Container No</div>
                        <div><?= $ItemLdata['container_no']; ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">Container Name</div>
                        <div><?= $ItemLdata['container_name']; ?></div>
                    </div>
                </div>

                <div class="row border border-dark border-start-0 border-top-0 border-end-0">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">BL/No</div>
                        <div><?= $ItemLdata['bl_no']; ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">Allot Name</div>
                        <div><?= $parent_item['allotment_name']; ?></div>
                    </div>
                </div>

                <div class="row border border-dark border-start-0 border-top-0 border-end-0">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">Payment Method</div>
                        <div><?= ucfirst($T['payment_details']->full_advance ?? ''); ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">Route</div>
                        <div>By <?= ucfirst($T['sea_road']); ?></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 py-1 px-2">
                        <div class="text-secondary">Loading Country</div>
                        <div><?= ucfirst($T['sea_road_array']['l_country_road'] ?? ''); ?></div>
                    </div>
                    <div class="col-6 py-1 px-2 border-start border-dark">
                        <div class="text-secondary">Receiving Country</div>
                        <div><?= ucfirst($T['sea_road_array']['r_country_road'] ?? ''); ?></div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table border border-start-0 border-end-0 border-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="border-start border-dark">Goods Description</th>
                    <th class="border-start border-dark">QTY</th>
                    <th class="border-start border-dark">W KGS / N KGS</th>
                    <th class="border-start border-dark">DIVIDE</th>
                    <th class="border-start border-dark">PRICE <?= $Item['price']; ?></th>
                    <th class="border-start border-dark">AMOUNT</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $totalQty = $totalNetKGS = $totalAmount = 0;
                $lastIndex = count($Items) - 1; // Get the last index
                foreach ($Items as $index => $SItem) {
                    $paddingStyle = ($index === $lastIndex) ? 'padding-bottom: 100px;' : ''; // Apply padding only to last row
                    $borderbottom = ($index != $lastIndex) ? 'border-bottom-0' : ''; // Apply padding only to last row
                ?>
                    <tr cla>
                        <td style="<?= $paddingStyle; ?>" class="text-center <?= $borderbottom; ?>"><?= $itemSrs[$index]; ?></td>
                        <td style="<?= $paddingStyle; ?>" class="border-start <?= $borderbottom; ?> border-dark">
                            <?= goodsName($parent_item['goods_id']) . ' / <b>Size:</b> ' . $parent_item['size'] . ' / <b>Brand:</b> ' . $parent_item['brand'] . ' / <b>Origin:</b> ' . $parent_item['origin'] ?>
                        </td>
                        <td style="<?= $paddingStyle; ?>" class="border-start <?= $borderbottom; ?> border-dark"><?= $SItem['qty_no'] . ' ' . $parent_item['qty_name']; ?></td>
                        <td style="<?= $paddingStyle; ?>" class="border-start <?= $borderbottom; ?> border-dark"><?= $SItem['total_kgs']; ?> / <?= $SItem['net_kgs']; ?></td>
                        <td style="<?= $paddingStyle; ?>" class="border-start <?= $borderbottom; ?> border-dark"><?= $SItem['total'] . ' ' . $SItem['divide']; ?></td>
                        <td style="<?= $paddingStyle; ?>" class="border-start <?= $borderbottom; ?> border-dark text-nowrap">
                            <?= number_format($SItem['rate1'], 2) . ' ' . $SItem['currency1'] ?>
                        </td>
                        <td style="<?= $paddingStyle; ?>" class="border-start <?= $borderbottom; ?> border-dark">
                            <?= number_format($SItem['amount']) . ' ' . $SItem['currency1']; ?>
                        </td>
                    </tr>
                <?php
                    $totalQty += $SItem['qty_no'];
                    $totalNetKGS += $SItem['net_kgs'];
                    $totalAmount += $SItem['amount'];
                } ?>

                <tr>
                    <td class="fw-bold" colspan="2">TOTALS: </td>
                    <td class="border-start border-dark fw-bold"><?= $totalQty ?? ''; ?></td>
                    <td class="border-start border-dark fw-bold"><?= $totalNetKGS ?? ''; ?></td>
                    <td class="border-start border-dark fw-bold"></td>
                    <td class="border-start border-dark fw-bold text-nowrap"></td>
                    <td class="border-start border-dark fw-bold"><?= number_format($totalAmount ?? 0); ?></td>
                </tr>
            </tbody>
        </table>


        <table class="table border border-start-0 border-end-0 border-dark">
            <tr>
                <td class="border-end border-dark">
                    <div class="py-1 border-bottom border-dark">
                        <span class="fw-bold">Commission Details: </span>
                        <span><?= $Item['details1'] ?? 'Not Added!'; ?></span>
                    </div>
                    <div class="py-1 border-bottom border-dark">
                        <span class="fw-bold">Additional Expenses: </span>
                        <span><?= $Item['details2'] ?? 'Not Added!'; ?></span>
                    </div>
                    <div class="py-1">
                        <span class="fw-bold">In words: </span>
                        <span><?php
                                $currency = $Item['currency1'] . ' ';
                                $amount = (float)$Item['amount'] - (float)$Item['commission_amount'] - (float)$Item['additional_expense'];
                                echo $currency . convertNumberToWords($amount);
                                ?>
                        </span>
                    </div>
                </td>
                <?php
                $totalComAmt = $totalAdditionalAmt = $totalRemAmt = $totalRateAmt = $totalFinalAmt = 0;

                // Loop through all items to calculate sums
                foreach ($Items as $item) {
                    $totalComAmt += (float)$item['commission_amount'];
                    $totalAdditionalAmt += (float)$item['additional_expense'];
                    $totalRemAmt += ((float)$item['amount'] - (float)$item['commission_amount'] - (float)$item['additional_expense']);
                    $totalRateAmt += (float)$item['rate2'];
                    $totalFinalAmt += (float)$item['final_amount'];
                }
                ?>

                <td rowspan="3">
                    <div class="border-bottom border-dark">
                        <span class="fw-bold">Com.Amt: </span>
                        <span>-<?= number_format($totalComAmt, 2); ?></span> <!-- Sum of commission_amount -->
                    </div>
                    <div class="border-bottom border-dark">
                        <span class="fw-bold">Additional.Amt: </span>
                        <span>-<?= number_format($totalAdditionalAmt, 2); ?></span> <!-- Sum of additional_expense -->
                    </div>
                    <div class="border-bottom border-dark">
                        <span class="fw-bold">Rem.AMOUNT: </span>
                        <span><?= number_format($totalRemAmt, 2) . ' ' . $Items[0]['currency1']; ?></span> <!-- Calculated remaining amount -->
                    </div>
                    <?php if ($_GET['print_type'] === 'full') { ?>
                        <div class="border-bottom border-dark">
                            <span class="fw-bold">RATE: </span>
                            <span><?= number_format($totalRateAmt, 2) . ' ( ' . $Items[0]['opr'] . ' ) '; ?></span> <!-- Sum of rate2 -->
                        </div>
                        <div>
                            <span class="fw-bold">FINAL: </span>
                            <span><?= number_format($totalFinalAmt, 2) . ' ' . $Items[0]['currency2']; ?></span> <!-- Sum of final_amount -->
                        </div>
                    <?php } ?>
                </td>

            </tr>
        </table>

        <!-- Bank Details -->
        <div class="row my-3 mx-2">
            <div class="col-12 mb-3">
                <span class="mb-0 fw-bold">Description: </span>
                <span><?= $Item['description'] ?? 'Not Added!'; ?></span>
            </div>
            <!-- <div class="col-12 mb-3">
                <p class="mb-0 fw-bold">Roznamcha Report: </p>
                <p><?= json_decode($Item['transferred'] ?? '[]', true)['details'] ?? 'Not Added!'; ?></p>
            </div> -->
            <div class="col-12 mb-3">
                <p class="mb-0 fw-bold">Report: </p>
                <p style="max-width:70%;"> <code style="color:#000;text-decoration:underline;">Once the bill has been signed, the responsibility no longer lies with the seller; it shifts to the purchaser. Any mistakes or issues that arise will be the purchaser’s responsibility. The purchaser must check everything before proceeding. </code></p>
            </div>
            <!-- <div class="col-6">
                <h6 class="mb-2">Seller Bank Details</h6>
                <?php
                $third_party_bank = acc_bank_details($T['cr_acc']);
                if (!empty($third_party_bank)) {
                    echo '<div class="row">';
                    echo '<div class="col-12"><b>Account Name: </b>' . (isset($third_party_bank['acc_name']) ? $third_party_bank['acc_name'] : 'N/A') . '</div>';
                    echo '<div class="col-12"><b>Bank Name: </b>' . (isset($third_party_bank['bank_name']) ? $third_party_bank['bank_name'] : 'N/A') . '</div>';
                    echo '<div class="col-12"><b>IBAN: </b>' . (isset($third_party_bank['iban']) ? $third_party_bank['iban'] : 'N/A') . '</div>';
                    echo '<div class="col-12"><b>Branch Code: </b>' . (isset($third_party_bank['branch_code']) ? $third_party_bank['branch_code'] : 'N/A') . '</div>';
                    echo '<div class="col-12"><b>Location: </b>' . (isset($third_party_bank['city']) ? $third_party_bank['city'] : 'N/A') . ', ' . (isset($third_party_bank['state']) ? $third_party_bank['state'] : 'N/A') . ', ' . (isset($third_party_bank['country']) ? $third_party_bank['country'] : 'N/A') . '</div>';
                    echo '<div class="col-12"><b>Address: </b>' . (isset($third_party_bank['address']) ? $third_party_bank['address'] : 'N/A') . '</div>';
                    echo '</div>';
                } else {
                    echo 'Not Found!';
                }
                ?>
            </div> -->
        </div>
        <div class="d-flex align-items-center text-center justify-content-between mt-5 pt-5 px-3 pb-3">
            <div>
                <div class="signature-box">
                    <span>Seller Signature</span><br>
                    <b><?= getCompanyName($T['cr_acc_kd_id']) ?? 'Not Found!'; ?></b>
                </div>
            </div>
            <div>
                <p class="mb-0 fw-bold">Roznamcha Report: </p>
                <p><?= json_decode($Item['transferred'] ?? '[]', true)['details'] ?? 'Not Added!'; ?></p>
            </div>
            <div>
                <div class="signature-box">
                    <span>Buyer Signature</span><br>
                    <b><?= getCompanyName($T['dr_acc_kd_id']) ?? 'Not Found!'; ?></b>
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
        $('#print_type').change(function() {
            window.location.href = '?t_id=<?= $_GET['t_id']; ?>&item_id=<?= $_GET['item_id']; ?>&item_sr=<?= $_GET['item_sr']; ?>&print_type=' + $(this).val();
        });
        <?php include("../assets/bs/js/bootstrap.bundle.min.js"); ?>
    </script>
</body>

</html>