<?php
include 'config.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM sinhvien WHERE MaSV='$id'");
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Chi Tiết</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-uppercase">Thông tin chi tiết</h2>
        <div>
            <strong>HoTen:</strong> <?= $row['HoTen'] ?><br>
            <strong>GioiTinh:</strong> <?= $row['GioiTinh'] ?><br>
            <strong>NgaySinh:</strong> <?= $row['NgaySinh'] ?><br>
            <strong>Hình:</strong><br>
            <img src="images/<?= $row['Hinh'] ?>" alt="Hình sinh viên" width="120"><br>
            <strong>MaNganh:</strong> <?= $row['MaNganh'] ?><br>
        </div>
        <br>
        <a href="edit.php?id=<?= $row['MaSV'] ?>" class="btn btn-primary">Edit</a>
        <a href="index.php" class="btn btn-secondary">Back to List</a>
    </div>
</body>
</html>