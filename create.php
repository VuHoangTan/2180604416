<?php
include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];

    $Hinh = "";
    if (!empty($_FILES['Hinh']['name'])) {
        $Hinh = $_FILES['Hinh']['name'];
        $target_dir = "images/";
        $target_file = $target_dir . basename($Hinh);

        // Kiểm tra định dạng file ảnh hợp lệ
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ["jpg", "jpeg", "png"];

        if (!in_array($imageFileType, $allowed_types)) {
            die("Lỗi: Chỉ chấp nhận file ảnh JPG, JPEG, PNG.");
        }

        // Di chuyển file ảnh vào thư mục
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file);
    }

    // Chuẩn bị câu lệnh SQL để tránh SQL Injection
    $stmt = $conn->prepare("INSERT INTO sinhvien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm sinh viên thành công!'); window.location='index.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-uppercase">Thêm Sinh Viên</h2>
        <form action="create.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Mã Sinh Viên</label>
                <input type="text" name="MaSV" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" name="HoTen" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới Tính</label>
                <select name="GioiTinh" class="form-control" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày Sinh</label>
                <input type="date" name="NgaySinh" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình</label>
                <input type="file" name="Hinh" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mã Ngành</label>
                <input type="text" name="MaNganh" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Mới</button>
        </form>
        <a href="index.php" class="mt-3 d-block">Quay lại danh sách</a>
    </div>
</body>
</html>
