<?php
$message = '';
$upload_dir = "uploads/";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $message = '파일 업로드 실패';
    } else {
        $filename = basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $filename);
        $message = "$filename 업로드 완료";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>파일 업로드</title></head>
<body>
    <h2>파일 업로드</h2>
    <form method="post" enctype="multipart/form-data">
        파일 선택: <input type="file" name="file">
        <button type="submit">업로드</button>
    </form>
    <p><?= $message ?></p>
</body>
</html>
