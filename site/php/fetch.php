<?php
$response = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];
    $response = @file_get_contents($url);
}
?>
<!DOCTYPE html>
<html>
<head><title>SSRF</title></head>
<body>
    <h2>SSRF 테스트</h2>
    <form method="post">
        URL: <input type="text" name="url">
        <button type="submit">요청</button>
    </form>
    <pre><?= htmlspecialchars($response) ?></pre>
</body>
</html>
