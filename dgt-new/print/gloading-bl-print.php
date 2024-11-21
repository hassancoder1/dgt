
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BL Print</title>
    <?php
    echo "<script>";
    include '../assets/fa/fontawesome.js';
    include '../assets/js/jquery-3.7.1.min.js';
    echo "</script>";
    echo "<style>";
    include '../assets/bs/css/bootstrap.min.css';
    include '../assets/css/custom.css';
    echo "</style>";
    ?>
    <style>
        .bt2 {
            border-top: 1px solid #444;
        }

        .bb2 {
            border-bottom: 1px solid #444;
        }

        .br2 {
            border-right: 1px solid #444;
        }

        .bl2 {
            border-left: 1px solid #444;
        }

        @media print {
            body {
                background: #fff !important;
            }

            .without-print-classes {
                margin: 0.5rem !important;
                padding: 0 !important;
                width: auto !important;
                max-width: none !important;
            }

            .without-print-classes[class] {
                all: unset;
            }

            .without-print-classes {
                margin: 0.5rem !important;
            }
        }
    </style>
</head>

<body class="overflow-x-hidden bg-dark">
    <div class="fixed bg-secondary p-2 text-white d-flex justify-content-end">
        <div class="d-flex gap-2">
            <div>
                <button class="btn btn-sm btn-warning" onclick="window.location.href = '/general-loading'"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <div class="dropdown">
                <button class="btn btn-primary btn-sm" onclick="printFromUrl()">
                    <i class="fa fa-print"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
        </div>
        <div class="col-8 m-2 mt-2">
            <?php include 'real-print-bl.php'; ?>
        </div>
        <div class="col-1"></div>
    </div>
    <iframe id="printIframe" style="display: none;" src=""></iframe>

    <script>
        function printFromUrl() {
            const printIframe = document.getElementById('printIframe');
            printIframe.src = 'real-print-bl?secret=cG93ZXJlZC1ieS11cHNvbA==&blSearch=<?= $_GET['blSearch'] ?>';
            printIframe.onload = function() {
                printIframe.contentWindow.print();
            };
        }
    </script>
</body>

</html>