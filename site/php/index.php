<?php
if (!file_exists('db_init.lock')) {
    include 'init_db.php';
    file_put_contents('db_init.lock', 'ok');
}
?>

<!DOCTYPE html>
<html>
<head><title>취약점 테스트 대시보드</title></head>
<body>
<h1>🔥 PHP 취약점 테스트 페이지</h1>
<ul>
    <li><a href="search.php">1. Reflected XSS</a></li>
    <li><a href="comment.php">2. Stored XSS</a></li>
    <li><a href="dom_xss.php">3. DOM XSS</a></li>
    <li><a href="login.php">4. SQL Injection</a></li>
    <li><a href="upload.php">5. 파일 업로드</a></li>
    <li><a href="ping.php">6. Command Injection</a></li>
    <li><a href="fetch.php">7. SSRF</a></li>
    <li><a href="download.php?file=test.txt">8. 파일 다운로드</a></li>
</ul>
</body>
</html>
