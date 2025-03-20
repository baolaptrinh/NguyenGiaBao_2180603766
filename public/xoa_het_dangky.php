<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];

// Lấy MaDK của sinh viên
$sql = "SELECT MaDK FROM DangKy WHERE MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $MaSV);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $MaDK = $row['MaDK'];

    // Xóa toàn bộ học phần đã đăng ký
    $sql = "DELETE FROM ChiTietDangKy WHERE MaDK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $MaDK);
    $stmt->execute();

    // Xóa luôn thông tin đăng ký
    $sql = "DELETE FROM DangKy WHERE MaDK = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $MaDK);
    $stmt->execute();
}

header("Location: hocphan_dadangky.php");
exit();
?>
