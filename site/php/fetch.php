<?php
$response = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    // http:// 또는 https://가 없으면 자동으로 붙임
    if (!preg_match('#^https?://#i', $url)) {
        $url = 'http://' . $url;
    }
    $response = @file_get_contents($url);
}
?>
<!DOCTYPE html>
<html>
<head><title>SSRF</title></head>
<body>
    <h2>SSRF 테스트</h2>
    <form method="post">
        URL: <input type="text" name="url" placeholder="e.g. 127.0.0.1:80 or http://site.com">
        <button type="submit">요청</button>
    </form>
    <pre><?= htmlspecialchars($response) ?></pre>
</body>
</html>
