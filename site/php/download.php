<?php
$download_dir = __DIR__ . '/uploads/';  // 현재 폴더 기준 상대경로
$filename = 'test.txt';
$fullpath = $download_dir . $filename;

if (isset($_GET['download']) && $_GET['download'] === 'true') {
    if (file_exists($fullpath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullpath));
        readfile($fullpath);
        exit;
    } else {
        http_response_code(404);
        echo "파일이 존재하지 않습니다.";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>파일 다운로드</title>
</head>
<body>
    <h2>test.txt 다운로드 페이지</h2>
    <p>버튼을 클릭하면 파일이 다운로드됩니다.</p>
    <a href="download.php?download=true">
        <button>test.txt 다운로드</button>
    </a>
</body>
</html>
