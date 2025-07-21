<?php
include 'db.php';

mysqli_report(MYSQLI_REPORT_OFF);
ini_set('display_errors', 0);
error_reporting(0);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500);
    }

    if ($result && $result->num_rows > 0) {
        sleep(5);
        $message = "로그인 성공!";
    } else {
        $message = "아이디 또는 비밀번호가 틀렸습니다.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>SQL Injection</title></head>
<body>
    <h2>로그인</h2>
    <form method="post">
        ID: <input type="text" name="username"><br>
        PW: <input type="password" name="password"><br>
        <button type="submit">로그인</button>
    </form>
    <p><?= $message ?></p>
</body>
</html>
