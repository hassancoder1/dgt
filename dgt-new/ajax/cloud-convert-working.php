<?php 

require '../vendor/autoload.php';

use CloudConvert\CloudConvert;
use CloudConvert\Models\Job;
use CloudConvert\Models\Task;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$rootURL = $protocol . $_SERVER['HTTP_HOST']."/";
$fileType = $_POST['filetype'] ?? null;
$pageURL = $_POST['pageURL'] ?? null;
$gendFileName = str_replace(['print/', '-main', '-print'], ['', '', ''], $pageURL);
$pageURL = $rootURL . $pageURL;


if (!$fileType || !$pageURL) {
    echo json_encode(['error' => 'File type or page URL not provided']);
    exit;
}

$cloudconvert = new CloudConvert([
    'api_key' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNjMxMGM1ZmQ3ODllN2E4NWNlOGIzZmZmYzMzYmRhMWVhM2MwMjMyY2Q0YjJmYjc4YTMyOTY3ZTQ1ZmEwNThmOThjZGIxM2FjZDhhMzVjYjEiLCJpYXQiOjE3MzI0NDkxMzYuMTczNTg5LCJuYmYiOjE3MzI0NDkxMzYuMTczNTkxLCJleHAiOjQ4ODgxMjI3MzYuMTcwNTEyLCJzdWIiOiI3MDI5ODE5MSIsInNjb3BlcyI6WyJ1c2VyLnJlYWQiLCJ1c2VyLndyaXRlIiwidGFzay5yZWFkIiwidGFzay53cml0ZSIsIndlYmhvb2sucmVhZCIsIndlYmhvb2sud3JpdGUiLCJwcmVzZXQucmVhZCIsInByZXNldC53cml0ZSJdfQ.g7OvoYoGH5lyuLDaA-scOv0k9_QuVB4RTUqhQOpLL7EgY8IBHgxCJtvfBJGWtrdxJAtwzvAKOT4qd2noFeTKRbny8CTvP1zSmi_bh_VyJ-e7Dcw0vcnNORqk3UeGV-pWiVz-Jmek55gODd0gmT_aukv7Fk26eeAN-9HrlKonpgMP9ElMoU2SguYfgoD3fcRxRjew68NyZ4utr6QFksy0I9FbuFWttX2ml7a_g6OS_12VqsEb-4kVOObP2u0-QRcN2sydqhIhmnItHNMLWHtLqg8ABFr3YnTOYb5o5Zm6lp_5Rf0mEBShE8CKfMcUBPv0WEjDdc-c5qLRXAJajNZHk2r9mO32f5uCTlwJn-TStLTIc1-zHQBinwMTAJEgd7C_bzhkEoBkvcef-3NejR2-V0CuPPzqjk3fk8kcOgHhP9iEm8_YzcEjU1QVeOSc92NINCzKRTiu7ZmRN_tqlfcIh2upfR-e7RcjAcRs286WCqI71qgFjQZGEj8HjIffwABXKLp47ms7ij_653sEQevfYVKoW6lH8ysfx_qo60A3HMZwAEG-67Od0hCokROUPt9-7KTn7gn2vTqJrXPKzZJtiSj4pu2BOw_k5jacWVmIcJHkPT7D--_ojaCvVup7ud6VZXJ2oLK1hYeOxk8hzbRSGTCvNF7V5lm_EhTAnoMAYIM', // Replace with your CloudConvert API key
    'sandbox' => true
]);

try {
    // Define unique task names based on file type
    $importTaskName = 'importpage';
    $convertTaskName = ($fileType === 'pdf') ? 'converttopdf' : 'converttoword';
    $exportTaskName = 'exportfile';

    // Build the CloudConvert job
    $job = (new Job())
        ->setTag('jobbuilder')
        ->addTask(
            (new Task('import/url', $importTaskName))
                ->set('url', $pageURL)
        )
        ->addTask(
            (new Task('convert', $convertTaskName))
                ->set('input', $importTaskName)
                ->set('output_format', $fileType) 
                ->set('page_size', 'A4')
                ->set('margins', '0.19,0.19,0.15,0.15')
        )
        ->addTask(
            (new Task('export/url', $exportTaskName))
                ->set('input', $convertTaskName)
        );

    // Create the job in CloudConvert
    $cloudconvert->jobs()->create($job);
    // Wait for job completion
    $completedJob = $cloudconvert->jobs()->wait($job);
    if ($completedJob->getStatus() !== 'finished') {
        echo json_encode(['error' => 'Job did not finish successfully', 'status' => $completedJob->getStatus(), 'details' => $completedJob]);
        exit;
    }

    foreach ($job->getExportUrls() as $file) {
        $source = $cloudconvert->getHttpTransport()->download($file->url)->detach();
        $generatedDir = str_replace('\ajax','',__DIR__) . '/generated-prints/';
        if (!file_exists($generatedDir)) {
            mkdir($generatedDir, 0755, true);
        }

        $fileName = 'print_' . $gendFileName . time() . '.' . $fileType;
        $filePath = $generatedDir . $fileName;

        // Save the file locally
        $dest = fopen($filePath, 'w');
        stream_copy_to_stream($source, $dest);
        echo json_encode(['fileURL' => $rootURL . 'generated-prints/' . $fileName]);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}
