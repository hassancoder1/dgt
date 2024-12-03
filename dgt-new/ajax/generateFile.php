<?php

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$rootURL = $protocol . $_SERVER['HTTP_HOST'] . "/";
$pageURL = $_POST['pageURL'] ?? null;
$gendFileName = str_replace(['print/', '-main', '-print'], ['', '', ''], $pageURL);
$pageURL = $rootURL . $pageURL;

// Validate input
if (!$pageURL) {
    echo json_encode(['error' => 'Page URL not provided']);
    exit;
}

// PDFEndpoint API Key
$apiKey = 'pdfe_live_fa44598e8bf76f1fc1f49047ee02e4d94b62'; // Replace this with your actual API key

// Define the API payload
$postData = json_encode([
    "margin_top" => "0.019cm",
    "margin_bottom" => "0.019cm",
    "margin_right" => "0.015cm",
    "margin_left" => "0.015cm",
    "url" => $pageURL,
    "sandbox" => true,
    "page_size" => "A4"
]);

// Initialize cURL request
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.pdfendpoint.com/v1/convert",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

// Handle errors
if ($err) {
    echo json_encode(['error' => "cURL Error: $err"]);
    exit;
}

// Decode the API response
$responseData = json_decode($response, true);

if (isset($responseData['error'])) {
    echo json_encode(['error' => $responseData['error']['message'] ?? 'Unknown error']);
    exit;
}

// Save the file locally
$fileUrl = $responseData['data']['url'] ?? null;
if (!$fileUrl) {
    echo json_encode(['error' => 'File URL not found in the response']);
    exit;
}

// Return the direct file URL to the client
echo json_encode(['fileURL' => $fileUrl]);
exit;
