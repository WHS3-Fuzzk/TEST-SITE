<?php
$message = '';
$download_dir = __DIR__ . '/../flask/uploads/';  // flask/uploads 기준

// 다운로드 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['file'])) {
    $filename = $_GET['file'];
    $fullpath = $download_dir . ltrim($filename, '/');

    if (!file_exists($fullpath)) {
        http_response_code(404);
        $message = "파일이 존재하지 않습니다.";
    } else {
        // 다운로드 헤더 전송
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fullpath));
        readfile($fullpath);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>파일 다운로드</title></head>
<body>
    <h2>파일 다운로드</h2>
    <form method="get">
        다운로드할 파일명: <input type="text" name="file" placeholder="예: test.txt">
        <button type="submit">다운로드</button>
    </form>
    <p><?= htmlspecialchars($message) ?></p>
</body>
</html>
