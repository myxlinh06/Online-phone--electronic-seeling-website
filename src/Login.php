<!DOCTYPE html>
<html>
<head>
  <title>Đăng nhập</title>
  <link rel="stylesheet" type="text/css" href="../style/style.css">
</head>
<body>
  <div class="login-container">
    <h2>Đăng nhập</h2>
    <form method="post" action="../src/src_xuli/XulyLogin.php">
      <input type="text" name="username" placeholder="Tên đăng nhập" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <input type="submit" value="Đăng nhập">
    </form>
    <!-- <div class="message">
      Chưa có tài khoản? <a href="Register.php">Đăng ký</a>
    </div> -->
  </div>
</body>
</html>