<?php
$output = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'];
    $output = shell_exec("ping -c 2 " . $host);
}
?>
<!DOCTYPE html>
<html>
<head><title>Command Injection</title></head>
<body>
    <h2>Ping 테스트</h2>
    <form method="post">
        Host/IP: <input type="text" name="host">
        <button type="submit">Ping</button>
    </form>
    <pre><?= htmlspecialchars($output) ?></pre>
</body>
</html>
