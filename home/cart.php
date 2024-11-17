<?php
// cart.php
session_start();

$conn=mysqli_connect("localhost","root","","webapp") or die("không thể kết nối");
mysqli_query($conn,"SET NAMES 'utf8'");

// Kiểm tra và xử lý các hành động thêm/xóa sản phẩm trong giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];
    if (isset($_POST["id"])) {
        $product_id = $_POST["id"];

        if ($action == "add") {
            if (isset($_SESSION["cart"][$product_id])) {
                $_SESSION["cart"][$product_id]++;
            } else {
                $_SESSION["cart"][$product_id] = 1;
            }
        }
            if ($action == "decre") {
                if (isset($_SESSION["cart"][$product_id])) {
                    $_SESSION["cart"][$product_id]--;
                } else {
                    $_SESSION["cart"][$product_id] = 1;
                }
        } elseif ($action == "remove") {
            if (isset($_SESSION["cart"][$product_id])) {
                unset($_SESSION["cart"][$product_id]);
                echo "<script>
                document.location.href = 'cart.php';
                </script>";
            }
        }
    } 
}

// Lấy thông tin sản phẩm trong giỏ hàng
$cart_total = 0;
$cart_items = array();
if (isset($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $product_id => $quantity) {
        $sql = "SELECT * FROM products WHERE id = $product_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cart_items[] = array(
                "id" => $row["id"],
                "name" => $row["name"],
                "price" => $row["price"],
                "quantity" => $quantity
            );
            $cart_total += $row["price"] * $quantity;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_order'])) {
    // Lấy dữ liệu từ form
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);

    $order_detail = json_encode($cart_items);

    // Bắt đầu giao dịch
    $conn->begin_transaction();

    try {
        // Câu lệnh SQL để chèn dữ liệu vào bảng orders
        $sql = "INSERT INTO orders (customer_name, customer_phone, customer_address,  order_detail, total_amount) VALUES ('$name', '$phone', '$address', ' $order_detail', '$cart_total')";

        if ($conn->query($sql) === TRUE) {
            // Lấy ID của đơn đặt hàng vừa thêm
            $order_id = $conn->insert_id;

            // Cập nhật số lượng sản phẩm trong bảng products
            foreach ($cart_items as $item) {
                $product_id = $item['id'];
                $quantity = $item['quantity'];

                $update_sql = "UPDATE products SET number = number - $quantity WHERE id = $product_id";
                if (!$conn->query($update_sql)) {
                    throw new Exception("Lỗi khi cập nhật số lượng sản phẩm: " . $conn->error);
                }
            }
            // Commit transaction
            $conn->commit();

             // Xóa giỏ hàng sau khi đặt hàng thành công
             unset($_SESSION["cart"]);

            echo "<script>
            alert('Bạn đã đặt hàng thành công !');
            document.location.href = 'index.php';
            </script>";
        } else {
            throw new Exception("Lỗi khi chèn đơn đặt hàng: " . $conn->error);
        }
    } catch (Exception $e) {
        // Rollback transaction nếu có lỗi
        $conn->rollback();
        echo $e->getMessage();
    }
}
// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>L2N SHOP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
</head>

<body>
        <div class="hbot">
            <div class="container">
                <nav class="navbar navbar-full navbar-dark bg-inverse">
                    <button class="navbar-toggler light float-xs-right hidden-sm-up" type="button" data-toggle="collapse" data-target="#nvbar"></button>
                    <div id="nvbar" class="collapse navbar-toggleable-xs">
                        <ul class="nav navbar-nav float-sm-right">
                            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                            <li>
                                <form action="search.php" method="get">
                                    <input type="text" name="search" placeholder="Tìm kiếm...">
                                    <button type="submit">Tìm</button>
                                </form>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="phone.php">Điện thoại</a></li>
                            <li class="nav-item"><a class="nav-link" href="mac.php">Laptop</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="phukien.php">Phụ kiện</a></li>
                            <li>
                                <div class="col-md-6 text-xs-right">
                                    <a href="cart.php" class="btn btn-primary">Giỏ hàng</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>

<main>
        <h1>Giỏ Hàng</h1>
        <table>
            <thead>
                <tr>
                    <th>Sản Phẩm</th>
                    <th>Số Lượng</th>
                    <th>Đơn Giá</th>
                    <th>Thành Tiền</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($cart_items as $item) {
                    echo "<tr>";
                    echo "<td>" . $item["name"] . "</td>";

                    echo "<td>
                                <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                                    <input type='hidden' name='action' value='decre'>
                                    <input type='hidden' name='id' value='" . $item["id"] . "'>
                                    <button type='submit'>-</button>
                                </form>
                                " . $item["quantity"] . "
                                <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                                    <input type='hidden' name='action' value='add'>
                                    <input type='hidden' name='id' value='" . $item["id"] . "'>
                                    <button type='submit'>+</button>
                                </form>
                            </td>";
                    echo "<td>" . number_format($item["price"], 0, ",", ".") . " VND</td>";
                    echo "<td>" . number_format($item["price"] * $item["quantity"], 0, ",", ".") . " VND</td>";
                    echo "<td>
                            <form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post'>
                                <input type='hidden' name='action' value='remove'>
                                <input type='hidden' name='id' value='" . $item["id"] . "'>
                                <button type='submit'>Xóa</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <p>Tổng Tiền: <strong><?php echo number_format($cart_total, 0, ",", "."); ?> VND</strong></p>
        <a href="address.php?id=product_id" class="checkout-btn">Đặt hàng</a>
    </main>
    <footer class="footer">
        <div class="container">
            <p class="text-xs-center">©Dự án web K44E</p>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.7/js/tether.min.js" integrity="sha384-XTs3FgkjiBgo8qjEjBk0Tmf3wPrWtA6coPfQDfFEY8AnYJwjalXCiosYRBIBZX8" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47B7iAn2C0C2Bo5g5Y1Ge6fa" crossorigin="anonymous"></script>
</body>
</html>

