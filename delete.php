<?php
include 'config.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM sinhvien WHERE MaSV='$id'");
$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "DELETE FROM sinhvien WHERE MaSV='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Xóa thành công!'); window.location='index.php';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Thông Tin Sinh Viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-uppercase">XÓA THÔNG TIN</h2>
        <p class="text-danger">Are you sure you want to delete this?</p>
        <div>
            <strong>HoTen:</strong> <?= $row['HoTen'] ?><br>
            <strong>GioiTinh:</strong> <?= $row['GioiTinh'] ?><br>
            <strong>NgaySinh:</strong> <?= $row['NgaySinh'] ?><br>
            <strong>Hình:</strong><br>
            <img src="images/<?= $row['Hinh'] ?>" alt="Hình sinh viên" width="120"><br>
            <strong>MaNganh:</strong> <?= $row['MaNganh'] ?><br>
        </div>
        <br>
        <form method="POST">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="index.php" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</body>
</html>