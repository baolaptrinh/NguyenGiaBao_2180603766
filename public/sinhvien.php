<?php
session_start();
include '../config/config.php';

// Kiểm tra nếu sinh viên chưa đăng nhập
if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];

// Lấy thông tin sinh viên từ CSDL
$sql = "SELECT sv.MaSV, sv.HoTen, sv.GioiTinh, sv.NgaySinh, nh.TenNganh 
        FROM SinhVien sv 
        JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh
        WHERE sv.MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $MaSV);
$stmt->execute();
$result = $stmt->get_result();
$sv_info = $result->fetch_assoc();

include '../includes/header.php';
?>

<div class="container">
    <h2>Thông Tin Sinh Viên</h2>
    <table border="1">
        <tr><th>Mã số sinh viên:</th><td><?= htmlspecialchars($sv_info['MaSV']) ?></td></tr>
        <tr><th>Họ Tên:</th><td><b><?= htmlspecialchars($sv_info['HoTen']) ?></b></td></tr>
        <tr><th>Giới Tính:</th><td><?= htmlspecialchars($sv_info['GioiTinh']) ?></td></tr>
        <tr><th>Ngày Sinh:</th><td><?= htmlspecialchars($sv_info['NgaySinh']) ?></td></tr>
        <tr><th>Ngành Học:</th><td><b><?= htmlspecialchars($sv_info['TenNganh']) ?></b></td></tr>
    </table>
    
    <br>
    <form method="POST" action="logout.php">
        <button type="submit" class="btn btn-danger">Đăng Xuất</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
