<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];

// Lấy thông tin sinh viên
$sql_sv = "SELECT sv.MaSV, sv.HoTen, sv.GioiTinh, sv.NgaySinh, nh.TenNganh 
           FROM SinhVien sv 
           JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh
           WHERE sv.MaSV = ?";
$stmt_sv = $conn->prepare($sql_sv);
$stmt_sv->bind_param("s", $MaSV);
$stmt_sv->execute();
$result_sv = $stmt_sv->get_result();
$sv_info = $result_sv->fetch_assoc();

// Lấy danh sách học phần đã chọn từ session
if (!isset($_SESSION['hocphan_dadangky'])) {
    $_SESSION['hocphan_dadangky'] = [];
}
$hocphan_dadangky = $_SESSION['hocphan_dadangky'];

// Nếu bấm "Xác nhận", lưu vào CSDL
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['xacnhan'])) {
    if (!empty($hocphan_dadangky)) {
        // Thêm vào bảng DangKy
        $sql_dk = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (CURDATE(), ?)";
        $stmt_dk = $conn->prepare($sql_dk);
        $stmt_dk->bind_param("s", $MaSV);
        $stmt_dk->execute();
        $MaDK = $stmt_dk->insert_id; // Lấy ID của bản ghi vừa chèn

        // Thêm vào bảng ChiTietDangKy
        $sql_ctdk = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
        $stmt_ctdk = $conn->prepare($sql_ctdk);
        
        foreach ($hocphan_dadangky as $MaHP) {
            $stmt_ctdk->bind_param("is", $MaDK, $MaHP);
            $stmt_ctdk->execute();
        }

        // Xóa dữ liệu session sau khi lưu vào CSDL
        unset($_SESSION['hocphan_dadangky']);

        // Chuyển hướng để tránh gửi lại form
        header("Location: hocphan_dadangky.php?success=1");
        exit();
    }
}

include '../includes/header.php';
?>

<div class="container">
    <h2>ĐĂNG KÝ HỌC PHẦN</h2>

    <!-- Hiển thị danh sách học phần -->
    <table border="1">
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Hành Động</th>
        </tr>
        <?php if (!empty($hocphan_dadangky)) { 
            foreach ($hocphan_dadangky as $MaHP) {
                $sql_hp = "SELECT * FROM HocPhan WHERE MaHP = ?";
                $stmt_hp = $conn->prepare($sql_hp);
                $stmt_hp->bind_param("s", $MaHP);
                $stmt_hp->execute();
                $result_hp = $stmt_hp->get_result();
                $row = $result_hp->fetch_assoc();
        ?>
            <tr>
                <td><?= htmlspecialchars($row['MaHP']) ?></td>
                <td><?= htmlspecialchars($row['TenHP']) ?></td>
                <td><?= htmlspecialchars($row['SoTinChi']) ?></td>
                <td>
                    <a href="xoa_dangky.php?MaHP=<?= $row['MaHP'] ?>" class="btn btn-danger">Xóa</a>
                </td>
            </tr>
        <?php } } else { ?>
            <tr><td colspan="4">Chưa có học phần nào được chọn</td></tr>
        <?php } ?>
    </table>

    <br>

    <!-- Hiển thị thông tin sinh viên -->
    <h3>Thông tin Đăng Ký</h3>
    <table border="1">
        <tr><th>Mã số sinh viên:</th><td><?= htmlspecialchars($sv_info['MaSV']) ?></td></tr>
        <tr><th>Họ Tên:</th><td><b><?= htmlspecialchars($sv_info['HoTen']) ?></b></td></tr>
        <tr><th>Giới Tính:</th><td><?= htmlspecialchars($sv_info['GioiTinh']) ?></td></tr>
        <tr><th>Ngày Sinh:</th><td><?= htmlspecialchars($sv_info['NgaySinh']) ?></td></tr>
        <tr><th>Ngành Học:</th><td><b><?= htmlspecialchars($sv_info['TenNganh']) ?></b></td></tr>
        <tr><th>Ngày Đăng Ký:</th><td><?= date("d/m/Y") ?></td></tr>
    </table>

    <br>

    <!-- Nút xác nhận -->
    <form method="POST">
        <button type="submit" name="xacnhan" class="btn btn-success">Xác Nhận</button>
    </form>

    <?php if (isset($_GET['success'])) { ?>
        <p style="color: green;">Đăng ký thành công!</p>
    <?php } ?>
</div>

<?php include '../includes/footer.php'; ?>
