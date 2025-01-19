<?php $url_index = '../';
if (
    isset($_GET['secret']) && base64_decode($_GET['secret']) == "powered-by-upsol"
    && isset($_GET['start_date']) && isset($_GET['end_date'])
    && isset($_GET['branch_id']) && isset($_GET['khaata_id']) && $_GET['khaata_id'] > 0
) {
    require("../connection.php");
    $print_type = isset($_GET['print_type']) ? mysqli_real_escape_string($connect, $_GET['print_type']) : 'sm';
    $khaata_id = mysqli_real_escape_string($connect, $_GET['khaata_id']);
    $branch_id = mysqli_real_escape_string($connect, $_GET['branch_id']);
    $start_date = mysqli_real_escape_string($connect, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($connect, $_GET['end_date']);

    $this_url = 'ledger?secret=' . $_GET['secret'] . '&khaata_id=' . $khaata_id . '&branch_id=' . $branch_id . '&start_date=' . $start_date . '&end_date=' . $end_date;

    $kh = fetch('khaata', array('id' => $khaata_id));
    $khaata = mysqli_fetch_assoc($kh);
    $sql = "SELECT * FROM `roznamchaas` WHERE khaata_id = '$khaata_id' ";
    $sql .= $branch_id > 0 ? " AND branch_id = " . "'$branch_id'" . " " : " ";
    if ($start_date != '' && $end_date != '') {
        $sql .= " AND r_date BETWEEN " . "'$start_date'" . " AND " . "'$end_date'" . " ";
    }
    $dates = $start_date != '' && $end_date != '' ? '<div class="text-muted"><i class="fas fa-calendar-alt me-2"></i>' . my_date($start_date) . ' - ' . my_date($end_date) . '</div>' : '';
    $branchName = $branch_id > 0 ? branchName($branch_id) : " All ";
    $records = mysqli_query($connect, $sql);

    $print_url = 'print/'.$this_url;
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo 'Ledger_' . my_date(date('Y-m-d')); ?></title>
        <link href="../assets/bs/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/virtual-select.min.css" rel="stylesheet">
        <!-- <link href="../assets/fa/css/all.min.css" rel="stylesheet" /> -->
        <link href="../assets/fonts/lexend.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="shortcut icon" href="../assets/images/favicon.jpg" />
        <style>
            * {
                font-family: 'Lexend', sans-serif;
            }

            body {
                background-color: #f8f9fa;
                font-size: 12px;
            }

            .ledger-header {
                background: white;
                border-radius: 10px;
                /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
                padding: 20px;
                margin-bottom: 20px;
            }

            .table {
                background: white;
                border-radius: 10px;
                /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
            }

            .table thead th {
                background-color: #f8f9fa;
                border-bottom: 2px solid #dee2e6;
            }

            .table-hover tbody tr:hover {
                background-color: #f8f9fa;
            }

            .stats-card {
                background: white;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
                /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
            }

            .print-controls {
                background: white;
                border-radius: 8px;
                padding: 15px;
                /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
            }

            @media print {
                .hide-on-print {
                    display: none !important;
                }

                body {
                    background-color: white;
                }

                .container {
                    width: 100vw !important;
                    max-width: 100vw !important;
                    padding: 0 5px !important;
                    margin: 0 auto;
                }
            }
        </style>
    </head>

    <body class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 bg-white border">
                    <div class="px-3 py-2 rounded">
                        <?php $_GET['print_type'] = isset($_GET['print_type']) ? $_GET['print_type'] : 'sm'; ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="../assets/images/logo.png" alt="logo" class="img-fluid my-3" width="120">
                            <div class="d-flex justify-content-center gap-2 align-items-center">
                                <select name="print_type" id="print_type" class="form-select form-select-sm hide-on-print">
                                    <option value="sm" <?= $_GET['print_type'] === 'sm' ? 'selected' : ''; ?>>Small</option>
                                    <option value="lg" <?= $_GET['print_type'] === 'lg' ? 'selected' : ''; ?>>Large</option>
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
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="px-3 py-2">
                                    <h6 class="mb-2">Branch Details</h6>
                                    <div><strong>Branch:</strong> <?php echo $branchName; ?></div>
                                    <div><strong>Category:</strong> <?php echo catName($khaata['cat_id']); ?></div>
                                    <div><strong>Entries:</strong> <span id="rows_span" class="badge bg-primary"></span></div>
                                    <?php echo $dates; ?>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="px-3 py-2">
                                    <h6 class="mb-2">Financial Summary</h6>
                                    <div><strong>Dr:</strong> <span id="dr_total_span" class="text-success"></span></div>
                                    <div><strong>Cr:</strong> <span id="cr_total_span" class="text-danger"></span></div>
                                    <div><strong>Balance:</strong> <span class="fw-bold" id="bal_span"></span></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="px-3 py-2">
                                    <h6 class="mb-2">Account Details</h6>
                                    <div><strong>A/C:</strong> <?php echo $khaata['khaata_no']; ?></div>
                                    <div><strong>Name:</strong> <?php echo $khaata['khaata_name']; ?></div>
                                    <div><strong>Phone:</strong> <?php echo $khaata['phone']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <table class="table my-3">
                        <thead>
                            <tr class="border text-nowrap">
                                <?php if ($print_type == 'lg'): ?>
                                    <th class="border text-nowrap"><i class="fas fa-building me-1"></i>Branch</th>
                                <?php endif; ?>
                                <th class="border text-nowrap"><i class="fas fa-calendar me-1"></i>Date</th>
                                <th class="border text-nowrap"><i class="fas fa-hashtag me-1"></i>Serial</th>
                                <?php if ($print_type == 'lg'): ?>
                                    <th class="border text-nowrap"><i class="fas fa-user me-1"></i>User</th>
                                    <th class="border text-nowrap"><i class="fas fa-file-alt me-1"></i>Roz#</th>
                                <?php endif; ?>
                                <th class="border text-nowrap"><i class="fas fa-exchange me-1"></i>Name</th>
                                <th class="border text-nowrap"><i class="fas fa-list-ol me-1"></i>No</th>
                                <th class="border text-nowrap"><i class="fas fa-info-circle me-1"></i>Details</th>
                                <th class="border text-nowrap text-end"><i class="fas fa-arrow-up me-1"></i>Dr.</th>
                                <th class="border text-nowrap text-end"><i class="fas fa-arrow-down me-1"></i>Cr.</th>
                                <th class="border text-nowrap text-center">/</th>
                                <th class="border text-nowrap text-end"><i class="fas fa-balance-scale me-1"></i>Balance</th>
                            </tr>
                        </thead>
                        <tbody class="border">
                            <?php
                            $data = mysqli_query($connect, $sql);
                            $numRows = $dr_total = $cr_total = 0;

                            if (mysqli_num_rows($data) > 0):
                                $jmaa = $bnaam = $balance = 0;
                                while ($datum = mysqli_fetch_assoc($data)):
                                    $dr = $cr = 0;
                            ?>
                                    <tr class="border">
                                        <?php if ($print_type == 'lg'): ?>
                                            <td class="border"><?php echo branchName($datum['branch_id']); ?></td>
                                        <?php endif; ?>
                                        <td class="border text-nowrap"><?php echo my_date($datum["r_date"]); ?></td>
                                        <td class="border"><?php echo $datum['r_id'] . '-' . $datum['branch_serial']; ?></td>
                                        <?php if ($print_type == 'lg'): ?>
                                            <td class="border"><?php echo $datum['username']; ?></td>
                                            <td class="border"><?php echo $datum['roznamcha_no']; ?></td>
                                        <?php endif; ?>
                                        <td class="border"><?php echo $datum["r_name"]; ?></td>
                                        <td class="border"><?php echo $datum['r_no']; ?></td>
                                        <td class="border" style="font-size: 10px; font-weight:600;"><?php echo $datum["details"]; ?></td>
                                        <?php
                                        if ($datum['dr_cr'] == "dr") {
                                            $dr = $datum['amount'];
                                            $dr_total += $dr;
                                            $jmaa += $datum['amount'];
                                        } else {
                                            $cr = $datum['amount'];
                                            $cr_total += $cr;
                                            $bnaam += $datum['amount'];
                                        }
                                        $balance = $jmaa - $bnaam;
                                        ?>
                                        <td class="border text-end fw-bold <?php echo $dr > 0 ? 'text-success' : ''; ?>"><?php echo $dr > 0 ? number_format($dr, 2) : ''; ?></td>
                                        <td class="border text-end fw-bold <?php echo $cr > 0 ? 'text-danger' : ''; ?>"><?php echo $cr > 0 ? number_format($cr, 2) : ''; ?></td>
                                        <td class="border text-center"><?php echo ucfirst($datum['dr_cr']) . '.'; ?></td>
                                        <td class="border text-end fw-bold <?php echo $balance >= 0 ? 'text-success' : 'text-danger'; ?>"><?php echo number_format($balance, 2); ?></td>
                                    </tr>
                            <?php
                                    $numRows++;
                                endwhile;
                            endif;
                            ?>
                            <!-- Summary Row -->
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="border fw-bold">
                                <?php
                                $colspan = $print_type == 'lg' ? 8 : 5;
                                ?>
                                <td colspan="<?php echo $colspan; ?>" class="border">Totals</td>
                                <td class="border text-end text-success"><?php echo number_format($dr_total, 2); ?></td>
                                <td class="border text-end text-danger"><?php echo number_format($cr_total, 2); ?></td>
                                <td class="border"></td>
                                <td class="border text-end <?php echo $balance ?? 0 >= 0 ? 'text-success' : 'text-danger'; ?>"><?php echo number_format($balance ?? 0, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <input type="hidden" id="rows" value="<?php echo $numRows; ?>">
            <input type="hidden" id="dr_total" value="<?php echo $dr_total; ?>">
            <input type="hidden" id="cr_total" value="<?php echo $cr_total; ?>">
        </div>
        <br><br>
        <div class="position-fixed top-0 start-0 w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(25, 26, 25, 0.4); z-index: 60;" id="processingScreen">
            <div class="spinner-border text-white" style="width: 5rem; height: 5rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <script src="../assets/js/jquery-3.7.1.min.js"></script>
        <script src="../assets/bs/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/virtual-select.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#rows_span").text($("#rows").val());

                const dr_total = parseFloat($("#dr_total").val());
                const cr_total = parseFloat($("#cr_total").val());
                const bal = dr_total - cr_total;

                $("#dr_total_span").text(dr_total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                $("#cr_total_span").text(cr_total.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                $("#bal_span").text(bal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));

                if (bal > 0) {
                    $("#bal_span").addClass('text-success');
                } else if (bal < 0) {
                    $("#bal_span").addClass('text-danger');
                }

                $('#print_type').on('change', function() {
                    window.location.href = '<?php echo $this_url; ?>&print_type=' + this.value;
                });
            });

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
        </script>
    </body>

    </html>
<?php } else {
    echo '<script>window.location.href="' . $url_index . '";</script>';
} ?>