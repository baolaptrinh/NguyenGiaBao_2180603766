<?php
include '../config/config.php';
include '../includes/header.php';

// Lấy danh sách học phần từ CSDL
$sql = "SELECT * FROM HocPhan";
$result = $conn->query($sql);
?>

<div class="container">
    <h2>DANH SÁCH HỌC PHẦN</h2>
    <table border="1">
        <tr>
            <th>Mã Học Phần</th>
            <th>Tên Học Phần</th>
            <th>Số Tín Chỉ</th>
            <th>Hành Động</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['MaHP']) ?></td>
                <td><?= htmlspecialchars($row['TenHP']) ?></td>
                <td><?= htmlspecialchars($row['SoTinChi']) ?></td>
                <td>
                    <a href="dangky.php?MaHP=<?= $row['MaHP'] ?>" class="btn btn-success">Đăng Ký</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
