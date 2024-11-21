<?php
$page = isset($_GET['secret']) ? base64_decode($_GET['secret']) : '';
$title = $backURL = '';
if ($page === 'bl-no-print') {
    $title = "B/L";
    $backURL = "general-loading";
}
$queryStringParts = [];
foreach ($_GET as $key => $value) {
    $queryStringParts[] = $key . '=' . urlencode($value);
}
$queryString = implode('&', $queryStringParts);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> Print</title>
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
                <button class="btn btn-sm btn-warning" onclick="window.location.href = '/<?= $backURL; ?>'"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <div class="dropdown">
    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton">
        <i class="fa fa-print"></i>
    </button>
    <ul class="dropdown-menu mt-2" id="dropdownMenu" aria-labelledby="dropdownMenuButton">
        <li>
            <a class="dropdown-item pointer" onclick="printFromUrl()">
                <i class="fas text-secondary fa-print me-2"></i> Print
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" onclick="getFileThrough('pdf', '<?= 'print/'.$page . '?' . $queryString; ?>')">
                <i id="pdfIcon" class="fas text-secondary fa-file-pdf me-2"></i> Download PDF
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" onclick="getFileThrough('word', '<?= 'print/'.$page . '?' . $queryString; ?>')">
                <i id="wordIcon" class="fas text-secondary fa-file-word me-2"></i> Download Word File
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" onclick="getFileThrough('whatsapp', '<?= 'print/'.$page . '?' . $queryString; ?>')">
                <i id="whatsappIcon" class="fa text-secondary fa-whatsapp me-2"></i> Send in WhatsApp
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="#" onclick="getFileThrough('email', '<?= 'print/'.$page . '?' . $queryString; ?>')">
                <i id="emailIcon" class="fas text-secondary fa-envelope me-2"></i> Send In Email
            </a>
        </li>
    </ul>
</div>

<script>
    // Custom Dropdown Toggle
    document.getElementById('dropdownMenuButton').addEventListener('click', function () {
        const dropdownMenu = document.getElementById('dropdownMenu');
        const isOpen = dropdownMenu.classList.contains('show');
        
        // Close other open dropdowns (if necessary)
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.remove('show'));
        
        // Toggle this dropdown
        if (!isOpen) {
            dropdownMenu.classList.add('show');
        }
    });

    // Close the dropdown when clicking outside
    document.addEventListener('click', function (e) {
        const dropdownMenu = document.getElementById('dropdownMenu');
        const dropdownButton = document.getElementById('dropdownMenuButton');
        if (!dropdownMenu.contains(e.target) && !dropdownButton.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });
</script>

<style>
    /* Ensures custom dropdown aligns with Bootstrap styling */
    .dropdown-menu {
        display: none;
        position: absolute;
        left: -320%;
        z-index: 1000;
        background-color: white;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
    }
    .dropdown-menu.show {
        display: block;
    }
</style>


            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
        </div>
        <div class="col-8 m-2 mt-2">
            <?php include $page . '.php'; ?>
        </div>
        <div class="col-1"></div>
    </div>
    <iframe id="printIframe" style="display: none;" src=""></iframe>

    <script>
        function printFromUrl() {
            const printIframe = document.getElementById('printIframe');
            printIframe.src = '<?= $page . '?' . $queryString; ?>';
            printIframe.onload = function() {
                printIframe.contentWindow.print();
            };
        }
    </script>
<?php include("../footer.php"); ?>
</body>

</html>