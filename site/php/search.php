<?php $query = $_GET['q'] ?? ''; ?>
<!DOCTYPE html>
<html>
<head><title>Reflected XSS</title></head>
<body>
    <h2>검색</h2>
    <form method="get">
        검색어: <input type="text" name="q">
        <button type="submit">검색</button>
    </form>
    <?php if ($query): ?>
        <p>검색 결과: <?= $query ?></p>
    <?php endif; ?>
</body>
</html>
