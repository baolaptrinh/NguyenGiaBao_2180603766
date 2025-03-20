<?php
include '../config/config.php';
include '../includes/header.php';

if (isset($_GET['MaSV'])) {
    $MaSV = $_GET['MaSV'];
    $sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaSV);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        echo "<p style='color:red;'>Không tìm thấy sinh viên!</p>";
        exit();
    }
} else {
    echo "<p style='color:red;'>Mã sinh viên không hợp lệ!</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $NewMaSV = $_POST['MaSV'];
    $HoTen = $_POST['HoTen'];
    $GioiTinh = $_POST['GioiTinh'];
    $NgaySinh = $_POST['NgaySinh'];
    $MaNganh = $_POST['MaNganh'];
    $Hinh = $row['Hinh']; // Giữ ảnh cũ nếu không thay đổi

    // Xử lý upload ảnh mới nếu có
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
        $target_dir = "../uploads/";
        $Hinh = $target_dir . basename($_FILES["Hinh"]["name"]);
        move_uploaded_file($_FILES["Hinh"]["tmp_name"], $Hinh);
    }

    // Nếu Mã SV thay đổi, cần cập nhật lại khóa chính
    if ($NewMaSV !== $MaSV) {
        $check_sql = "SELECT MaSV FROM SinhVien WHERE MaSV = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $NewMaSV);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            echo "<p style='color:red;'>Mã sinh viên đã tồn tại!</p>";
        } else {
            // Cập nhật mã sinh viên (chú ý update các bảng liên quan nếu có)
            $sql = "UPDATE SinhVien SET MaSV=?, HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $NewMaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $MaSV);
        }
    } else {
        $sql = "UPDATE SinhVien SET HoTen=?, GioiTinh=?, NgaySinh=?, Hinh=?, MaNganh=? WHERE MaSV=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh, $MaSV);
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "<p style='color:red;'>Lỗi: " . $conn->error . "</p>";
    }
}
?>

<div class="container">
    <h2>Chỉnh sửa thông tin sinh viên</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Mã SV:</label> <input type="text" name="MaSV" value="<?= htmlspecialchars($row['MaSV']) ?>" required><br>
        <label>Họ Tên:</label> <input type="text" name="HoTen" value="<?= htmlspecialchars($row['HoTen']) ?>" required><br>
        <label>Giới Tính:</label> 
        <select name="GioiTinh">
            <option value="Nam" <?= $row['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
            <option value="Nữ" <?= $row['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
        </select><br>
        <label>Ngày Sinh:</label> <input type="date" name="NgaySinh" value="<?= htmlspecialchars($row['NgaySinh']) ?>" required><br>
        <label>Hình:</label> 
        <input type="file" name="Hinh"><br>
        <?php if (!empty($row['Hinh'])): ?>
            <img src="<?= htmlspecialchars($row['Hinh']) ?>" width="100">
        <?php endif; ?>
        <br>
        <label>Ngành Học:</label> <input type="text" name="MaNganh" value="<?= htmlspecialchars($row['MaNganh']) ?>" required><br>
        <button type="submit">Lưu thay đổi</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
