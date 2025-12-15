<?php
// =======================================
// STREAM PROXY UNTUK SEGMENT .TS
// =======================================

// PARAMETER
$folder = isset($_GET['file']) ? $_GET['file'] : null;
$segment = isset($_GET['seg']) ? $_GET['seg'] : null;

if (!$folder || !$segment) {
    http_response_code(400);
    die("Error: folder atau seg tidak ada.");
}

// PATH FILE
$path = __DIR__ . '/' . $folder . '/' . $segment;

if (!file_exists($path)) {
    http_response_code(404);
    die("Error: segment tidak ditemukan.");
}

// HEADER CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, HEAD, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Range");
header("Access-Control-Expose-Headers: Content-Length, Content-Range, Accept-Ranges");

// HEADER VIDEO
header("Content-Type: video/mp2t");
header("Content-Length: " . filesize($path));

// KIRIM FILE
readfile($path);
exit;
?>
