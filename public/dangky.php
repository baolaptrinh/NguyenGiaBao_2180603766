<?php
session_start();

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

// Lấy MãHP từ URL
if (isset($_GET['MaHP'])) {
    $MaHP = $_GET['MaHP'];

    // Nếu session chưa có danh sách học phần, khởi tạo nó
    if (!isset($_SESSION['hocphan_dadangky'])) {
        $_SESSION['hocphan_dadangky'] = [];
    }

    // Kiểm tra nếu MãHP chưa có trong danh sách thì thêm vào
    if (!in_array($MaHP, $_SESSION['hocphan_dadangky'])) {
        $_SESSION['hocphan_dadangky'][] = $MaHP;
    }

    // Quay lại trang học phần đã đăng ký
    header("Location: hocphan_dadangky.php");
    exit();
}
?>
