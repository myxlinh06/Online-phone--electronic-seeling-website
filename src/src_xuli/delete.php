<?php
$conn = mysqli_connect("localhost", "root", "", "webapp") or die("không thể kết nối");
mysqli_query($conn, "SET NAMES 'utf8'");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // Chuyển đổi id sang kiểu số nguyên để tránh SQL Injection

    $sql = "DELETE FROM products WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Xóa thành công!');
            document.location.href = '../src_xuli/Manage.php#products';
        </script>";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Không có ID sản phẩm nào được cung cấp";
}

$conn->close();
?>