<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['MaSV']) || empty($_SESSION['hocphan_dangky'])) {
    header("Location: hocphan.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];
$hocPhanList = $_SESSION['hocphan_dangky'];
$NgayDK = date("Y-m-d");

// Thêm vào bảng `DangKy`
$sql = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $NgayDK, $MaSV);
$stmt->execute();
$MaDK = $stmt->insert_id;

// Thêm vào bảng `ChiTietDangKy`
$sql = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
foreach ($hocPhanList as $hp) {
    $stmt->bind_param("is", $MaDK, $hp['MaHP']);
    $stmt->execute();
}

// Xóa session và chuyển hướng
unset($_SESSION['hocphan_dangky']);
header("Location: hocphan_dadangky.php");
exit();
