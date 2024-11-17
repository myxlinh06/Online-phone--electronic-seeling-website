<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/changePassword.css">
    <title>Change Password</title>
</head>

<body>
    <?php
    $serverName = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webapp";
    $conn = mysqli_connect($serverName, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['saveChanges'])) {
        $Email = $_SESSION['user_id']; // Assuming the user_id is the email
        $currentPassword = $_POST['Password'];
        $newPassword = $_POST['newPassword'];
        $confirmNewPassword = $_POST['confirmNewPassword'];

        // Validate current password and update to new password
        $sqlcheckacc = "SELECT * FROM taikhoan WHERE username = ?";
        $stmt = $conn->prepare($sqlcheckacc);
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if ($currentPassword === $row['password']) {  
                if ($newPassword === $confirmNewPassword) {
                    $sqlupdate = "UPDATE taikhoan SET password = ? WHERE username = ?";
                    $stmt = $conn->prepare($sqlupdate);
                    $stmt->bind_param("ss", $newPassword, $Email);
                    if ($stmt->execute()) {
                        echo "<script> 
                            alert('Mật khẩu đã được thay đổi');
                            window.history.back();
                        </script>";
                    } else {
                        echo "<script> 
                            alert('Đã xảy ra lỗi khi thay đổi mật khẩu');
                            window.history.back();
                        </script>";
                    }
                } else {
                    echo "<script> 
                        alert('Mật khẩu mới không khớp');
                        window.history.back();
                    </script>";
                }
            } else {
                echo "<script> 
                    alert('Mật khẩu cũ không đúng');
                    window.history.back();
                </script>";
            }
        } else {
            echo "<script> 
                alert('Không có người dùng này');
                window.history.back();
            </script>";
        }
        $stmt->close();
    }
    ?>

    <h1>Change Password</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="form-field">
            <label for="Email">Username:</label>
            <input type="text" id="Email" name="Email" value="<?php echo $_SESSION['user_id']; ?>" disabled>
        </div>

        <div class="form-field">
            <label for="Password">Current Password:</label>
            <input type="password" id="Password" name="Password" required>
        </div>

        <div class="form-field">
            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>
        </div>

        <div class="form-field">
            <label for="confirmNewPassword">Confirm New Password:</label>
            <input type="password" id="confirmNewPassword" name="confirmNewPassword" required>
        </div>

        <div class="form-field">
            <button type="submit" name="saveChanges">Save Changes</button>
        </div>
    </form>
</body>

</html>
