<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO comment (content) VALUES (?)");
    $stmt->bind_param("s", $content);
    $stmt->execute();
}
$comments = $conn->query("SELECT * FROM comment");
?>
<!DOCTYPE html>
<html>
<head><title>Stored XSS</title></head>
<body>
    <h2>댓글 작성</h2>
    <form method="post">
        <textarea name="content" rows="4" cols="40"></textarea><br>
        <button type="submit">등록</button>
    </form>
    <hr>
    <h3>댓글 목록:</h3>
    <?php while($row = $comments->fetch_assoc()): ?>
        <p><?= $row['content'] ?></p>
    <?php endwhile; ?>
</body>
</html>
