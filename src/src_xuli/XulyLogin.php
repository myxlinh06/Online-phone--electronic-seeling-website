<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "webapp") or die("Không thể kết nối");
mysqli_query($conn, "SET NAMES 'utf8'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM taikhoan WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['username'];
        // Redirect to the manage page after successful login
        header("Location: Manage.php");
        exit();
    } else {
        echo "Đăng nhập thất bại!";
    }

    $stmt->close();
}
$conn->close();
?>
