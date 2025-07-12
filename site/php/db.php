<?php
$host = 'mysql';         
$db = 'vuln_db';
$user = 'vuln';
$pass = 'vuln';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}
?>