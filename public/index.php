<?php
include '../config/config.php';
$sql = "SELECT SinhVien.*, NganhHoc.TenNganh FROM SinhVien 
        LEFT JOIN NganhHoc ON SinhVien.MaNganh = NganhHoc.MaNganh";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sinh Viên</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <h2>TRANG SINH VIÊN</h2>
    <a href="create.php">Thêm Sinh Viên</a>
    <table border="1">
        <tr>
            <th>Mã SV</th><th>Họ Tên</th><th>Giới Tính</th><th>Ngày Sinh</th><th>Hình</th><th>Ngành Học</th><th>Hành Động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $row['MaSV'] ?></td>
            <td><?= $row['HoTen'] ?></td>
            <td><?= $row['GioiTinh'] ?></td>
            <td><?= $row['NgaySinh'] ?></td>
            <td>
    <img src="../assets/images/<?= !empty($row['Hinh']) ? htmlspecialchars($row['Hinh']) : 'default.png' ?>" width="50">
</td>
            <td><?= $row['TenNganh'] ?></td>
            <td>
                <a href="edit.php?MaSV=<?= $row['MaSV'] ?>">Sửa</a> |
                <a href="detail.php?MaSV=<?= $row['MaSV'] ?>">Chi Tiết</a> |
                <a href="delete.php?MaSV=<?= $row['MaSV'] ?>">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
