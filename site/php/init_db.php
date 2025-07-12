<?php
$host = 'mysql';         
$user = 'vuln';
$pass = 'vuln';

// DB 연결 (초기에는 DB 이름 없이 접속)
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("DB 접속 실패: " . $conn->connect_error);
}

// DB 생성
$conn->query("CREATE DATABASE IF NOT EXISTS vuln_db");

// 생성된 DB로 전환
$conn->select_db("vuln_db");

// 테이블 생성
$conn->query("
    CREATE TABLE IF NOT EXISTS comment (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content TEXT NOT NULL
    )
");

$conn->query("
    CREATE TABLE IF NOT EXISTS user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(64) NOT NULL,
        password VARCHAR(64) NOT NULL
    )
");

?>
