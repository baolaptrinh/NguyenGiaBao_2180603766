<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Hệ thống Quản lý Sinh viên</h1>
        <nav>
            <ul style="list-style-type: none; margin: 0; padding: 0; display: flex;">
                <li style="margin-right: 20px;"><a href="index.php" style="color: white; text-decoration: none;">Test1</a></li>
                <li style="margin-right: 20px;"><a href="sinhvien.php" style="color: white; text-decoration: none;">Sinh Viên</a></li>
                <li style="margin-right: 20px;"><a href="hocphan.php" style="color: white; text-decoration: none;">Học Phần</a></li>
                <li style="margin-right: 20px;"><a href="hocphan_dadangky.php" style="color: white; text-decoration: none;">Đăng Kí Học Phần</a></li>

                <?php if (!isset($_SESSION['MaSV'])) { ?>
                    <li><a href="login.php" style="color: white; text-decoration: none;">Đăng Nhập</a></li>
                <?php } else { ?>
                    <li><a href="logout.php" style="color: white; text-decoration: none;">Đăng Xuất</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
