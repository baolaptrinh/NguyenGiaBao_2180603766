<?php
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

// Lấy MãHP từ URL
if (isset($_GET['MaHP'])) {
    $MaHP = $_GET['MaHP'];

    // Kiểm tra nếu danh sách học phần có trong session
    if (isset($_SESSION['hocphan_dadangky'])) {
        // Loại bỏ học phần khỏi session
        $_SESSION['hocphan_dadangky'] = array_diff($_SESSION['hocphan_dadangky'], [$MaHP]);
    }
}

// Quay lại trang học phần đã đăng ký
header("Location: hocphan_dadangky.php");
exit();
?>
