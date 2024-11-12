<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

$fileType = $_POST['filetype'] ?? null;
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$rootURL = $protocol . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['PHP_SELF'])) . '/';
$pageURL = isset($_POST['pageURL']) ? $rootURL . ltrim($_POST['pageURL'], '/') : null;

$baseDir = str_replace('ajax', '', __DIR__);
$generatedDir = $baseDir . 'generated-prints/';
$fileURL = '';

if (!file_exists($generatedDir)) {
    mkdir($generatedDir, 0755, true);
}

$content = file_get_contents($pageURL);

if ($fileType === 'pdf' || $fileType === 'whatsapp' || $fileType === 'email') {
    // Generate PDF using Dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml($content);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    $fileName = 'print_' . time() . '.pdf';
    $filePath = $generatedDir . $fileName;
    file_put_contents($filePath, $dompdf->output());

    $fileURL = 'generated-prints/' . $fileName;

} elseif ($fileType === 'word') {
    $htmlToDoc = new HTML_TO_DOC();
    $fileName = 'print_' . time() . '.docx';
    $filePath = $generatedDir . $fileName;
    $htmlToDoc->createDoc($content, $filePath);
    $fileURL = 'generated-prints/' . $fileName;
} else {
    echo json_encode(['error' => 'Invalid file type']);
    exit;
}

echo json_encode(['fileURL' => $rootURL . $fileURL]);
exit;
