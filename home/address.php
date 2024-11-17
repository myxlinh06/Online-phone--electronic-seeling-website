<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Địa Chỉ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            color: #333;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <title>Thông Tin Địa Chỉ</title>
    <form action="cart.php" method="post">
        <label for="name">Họ và Tên:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="phone">Số Điện Thoại:</label>
        <input type="tel" id="phone" name="phone" required><br>

        <label for="address">Địa Chỉ:</label>
        <textarea id="address" name="address" required></textarea><br>

        <input type="hidden" name="confirm_order" value="1">
        <button type="submit">Xác Nhận Đặt Hàng</button>
    </form>
</body>
</html>
