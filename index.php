<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Trang Chủ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="sinhvien.php">Sinh Viên</a></li>
                <li class="nav-item"><a class="nav-link" href="hocphan.php">Học Phần</a></li>
                <li class="nav-item"><a class="nav-link" href="dangky.php">Đăng Ký Học Phần</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Đăng Nhập</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center">TRANG SINH VIÊN</h2>
    <a href="create.php" class="btn btn-primary mb-3">Add Student</a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>MaSV</th>
                <th>Họ Tên</th>
                <th>Giới Tính</th>
                <th>Ngày Sinh</th>
                <th>Hình</th>
                <th>Ngành Học</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM sinhvien");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['MaSV'] . "</td>";
                echo "<td>" . $row['HoTen'] . "</td>";
                echo "<td>" . $row['GioiTinh'] . "</td>";
                echo "<td>" . date("d/m/Y", strtotime($row['NgaySinh'])) . "</td>";
                echo "<td><img src='images/" . $row['Hinh'] . "' width='100' height='100'></td>";
                echo "<td>" . $row['MaNganh'] . "</td>";
                echo "<td>
                    <a href='edit.php?id=" . $row['MaSV'] . "' class='btn btn-warning btn-sm'>Edit</a>
                    <a href='detail.php?id=" . $row['MaSV'] . "' class='btn btn-info btn-sm'>Details</a>
                    <a href='delete.php?id=" . $row['MaSV'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'>Delete</a>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>