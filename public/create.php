<?php
include '../config/config.php';
include '../includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = trim($_POST['MaSV']);
    $HoTen = trim($_POST['HoTen']);
    $GioiTinh = trim($_POST['GioiTinh']);
    $NgaySinh = trim($_POST['NgaySinh']);
    $MaNganh = trim($_POST['MaNganh']);

    // Xử lý file ảnh
    $Hinh = "";
    if (isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0) {
        $target_dir = "../assets/images/"; // Thư mục lưu ảnh
        $file_extension = strtolower(pathinfo($_FILES["Hinh"]["name"], PATHINFO_EXTENSION));

        // Định dạng cho phép
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_extension, $allowed_extensions)) {
            $file_name = time() . "_" . basename($_FILES["Hinh"]["name"]); // Đổi tên tránh trùng
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $target_file)) {
                $Hinh = $file_name; // Lưu tên file vào database
            } else {
                echo "<p style='color:red;'>Lỗi khi tải ảnh lên!</p>";
            }
        } else {
            echo "<p style='color:red;'>Chỉ chấp nhận file JPG, JPEG, PNG, GIF!</p>";
        }
    }

    // Kiểm tra dữ liệu đầu vào
    if (empty($MaSV) || empty($HoTen) || empty($NgaySinh) || empty($MaNganh)) {
        echo "<p style='color:red;'>Vui lòng nhập đầy đủ thông tin!</p>";
    } else {
        $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $MaSV, $HoTen, $GioiTinh, $NgaySinh, $Hinh, $MaNganh);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "<p style='color:red;'>Lỗi: " . $conn->error . "</p>";
        }
    }
}

?>

<div class="container">
    <h2>THÊM SINH VIÊN</h2>
    <form method="POST" enctype="multipart/form-data">
    <label>Mã SV:</label> <input type="text" name="MaSV" required><br>
    <label>Họ Tên:</label> <input type="text" name="HoTen" required><br>
    <label>Giới Tính:</label> 
    <select name="GioiTinh">
        <option value="Nam">Nam</option>
        <option value="Nữ">Nữ</option>
    </select><br>
    <label>Ngày Sinh:</label> <input type="date" name="NgaySinh" required><br>
    <label>Hình:</label> <input type="file" name="Hinh" accept="image/*"><br>
    <label>Ngành Học:</label> <input type="text" name="MaNganh" required><br>
    <button type="submit">Create</button>
</form>
</div>

<?php include '../includes/footer.php'; ?>
