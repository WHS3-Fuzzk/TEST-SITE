<?php
$filename = $_GET['file'] ?? '';
if (!$filename || strpos($filename, '..') !== false || str_starts_with($filename, '/')) {
    http_response_code(400);
    echo "잘못된 요청";
    exit;
}
$path = 'uploads/' . $filename;
if (file_exists($path)) {
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
    header('Content-Type: application/octet-stream');
    readfile($path);
    exit;
} else {
    echo "파일이 존재하지 않습니다.";
}
