<!DOCTYPE html>
<html>
<head>
    <title>Bảng Sản phẩm</title>
    <link rel="stylesheet" href="../style/form_product.css">
</head>
<body>
    <h2>Bảng Sản phẩm</h2>
    <button ><a href="../src/formadd_product.php">Thêm sản phẩm</a></button>
    <table>
        <tr>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Hình ảnh</th>
            <th>Giá</th>
            <th>Mô tả</th>
            <th>Số lượng</th>
            <th>Thao tác</th>
        </tr>
        <?php include 'src_xuli/product.php'; ?>
    </table>
</body>
</html>