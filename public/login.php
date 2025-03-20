<?php
include '../config/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $MaSV = $_POST['MaSV'];
    
    // Kiểm tra mã SV trong CSDL
    $sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $MaSV);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['MaSV'] = $MaSV;
        header("Location: index.php");
        exit();
    } else {
        $error = "Mã sinh viên không tồn tại!";
    }
}
?>

<div class="container">
    <h2>ĐĂNG NHẬP</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>MaSV:</label>
        <input type="text" name="MaSV" required>
        <button type="submit">Đăng Nhập</button>
    </form>
</div>
