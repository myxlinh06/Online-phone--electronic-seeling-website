<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../Manage.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>QUẢN LÍ</title>
</head>

<body>
  <div class="sidebar">
    <div class="logo-details">
      <a href="#" class="logo_name" style="text-decoration: none;">WEBAPP</a>
      <i class='bx bx-menu' id="btn"></i>
    </div>
    <ul class="nav-list">
      <li>
        <nav>
          <a href="#customers">
            <i class='bx bx-book-open'></i>
            <span class="links_name">Customers</span>
          </a>
        </nav>
        <span class="tooltip">Customers</span>
      </li>
      <li>
        <nav>
          <a href="#products">
            <i class='bx bxl-product-hunt'></i>
            <span class="links_name">Products</span>
          </a>
        </nav>
        <span class="tooltip">Products</span>
      </li>
      <li class="profile">
        <div class="profile-details">
          <div class="name_job">
            <div class="name">
              <?php if (isset($_SESSION['user_id'])) echo $_SESSION['user_id']; ?>
            </div>
          </div>
          <i class="bx bx-log-out" id="log_out"></i>
        </div>
      </li>
    </ul>
  </div>

  <!-- HIỂN THỊ KHÁCH HÀNG -->
  <section class="section home-section" id="customers">
    <div class="text">
      <h2>Khách hàng</h2>
      <div class="search-bar" style="margin-top: 15px; margin-bottom: 15px;">
        <form action="" method="get">
          <input type="text" name="search" placeholder="Tìm kiếm theo tên, địa chỉ, số điện thoại hoặc email" style="padding: 10px; width: 20%; font-size: 16px; border: 1px solid #ccc; border-radius: 5px;">
          <button type="submit" style="background-color: #dfe9df; border: none; padding: 10px 20px; text-align: center; display: inline-block; font-size: 16px; cursor: pointer; border-radius: 5px;">Tìm kiếm</button>
          <?php
          // Kiểm tra nếu có từ khóa tìm kiếm để hiển thị nút "Xem tất cả"
          if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            echo "<button type='button' onclick='window.location.href=\"Manage.php#customers\"' style='background-color: #dfe9df; /* Màu xanh lá cây */
                    border: none;
                    padding: 10px 20px;
                    text-align: center;
                    display: inline-block;
                    font-size: 16px;
                    cursor: pointer;
                    border-radius: 5px; margin-left: 10px;'>Xem tất cả</button>";
          }
          ?>
        </form>
      </div>

      <div class="data-table">
        <table>
          <thead>
            <tr>
              <th>Mã khách hàng</th>
              <th>Họ và tên</th>
              <th>Địa chỉ</th>
              <th>Số điện thoại</th>
              <th>Email</th>
              <th>Phương thức thanh toán</th>
              <th>Tổng tiền</th>
              <th>Ngày tạo</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $conn = new mysqli("localhost", "root", "", "webapp");
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }
            $conn->set_charset("utf8");

            $perPage = 3; // Số dòng trên mỗi trang
            $currentPage = isset($_GET['page_customers']) ? (int)$_GET['page_customers'] : 1;
            $offset = ($currentPage - 1) * $perPage;

            // Xử lý tìm kiếm
            $searchTerm = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
            $searchQuery = $searchTerm ? " WHERE hoten LIKE '%$searchTerm%' OR diachi LIKE '%$searchTerm%' OR sdt LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'" : '';

            $countQuery = "SELECT COUNT(*) AS count FROM orders" . $searchQuery;
            $countResult = $conn->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $totalCustomers = $countRow['count'];

            $totalPages = ceil($totalCustomers / $perPage);

            $stmt = $conn->prepare("SELECT * FROM orders" . $searchQuery . " LIMIT ?, ?");
            $stmt->bind_param("ii", $offset, $perPage);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
              echo "<tr>
        <td style='text-align: center'>" . htmlspecialchars($row['id']) . "</td>
        <td>" . htmlspecialchars($row['hoten']) . "</td>
        <td>" . htmlspecialchars($row['diachi']) . "</td>
        <td>" . htmlspecialchars($row['sdt']) . "</td>
        <td>" . htmlspecialchars($row['total_amount']) . "</td>
        <td>" . htmlspecialchars($row['created_at']) . "</td>
    </tr>";
            }
            $stmt->close();
            $conn->close();
            ?>

          </tbody>
        </table>
      </div>
      <div style="text-align:center; font-size:15px;">
        <?php
        for ($i = 1; $i <= $totalPages; $i++) {
          echo "<a style='text-decoration: none;' href='?page_customers=$i#customers'>$i </a> ";
        }
        ?>
      </div>
    </div>
  </section>

  <!-- HIỂN THỊ SẢN PHẨM -->
  <section class="section home-section" id="products">
    <div class="text">
      <h2>Bảng Sản Phẩm</h2>
      <button style="background-color: #6bff70; /* Màu xanh lá cây */
                      border: none;
                      color: white;
                      padding: 10px 20px;
                      text-align: center;
                      display: inline-block;
                      font-size: 16px;
                      margin: 4px 2px;
                      cursor: pointer;
                      border-radius: 5px;">
        <a style="text-decoration: none; color : black;" href="../formadd_product.php">Thêm sản phẩm</a>
      </button>
      <div class="search-bar" style="margin-top: 15px; margin-bottom: 15px;">
        <form action="" method="get">
          <input type="text" name="search-products" placeholder="Nhập tên sản phẩm" style="padding: 10px; width: 20%; font-size: 16px; border: 1px solid #ccc; border-radius: 5px;">
          <button type="submit" style="background-color: #dfe9df; /* Màu xanh lá cây */
                                             border: none;
                                             padding: 10px 20px;
                                             text-align: center;
                                             display: inline-block;
                                             font-size: 16px;
                                             cursor: pointer;
                                             border-radius: 5px;">Tìm kiếm</button>

          <?php
          // Kiểm tra nếu có từ khóa tìm kiếm để hiển thị nút "Xem tất cả"
          if (isset($_GET['search-products']) && !empty(trim($_GET['search-products']))) {
            echo "<button type='button' onclick='window.location.href=\"Manage.php#products\"' style='background-color: #dfe9df; /* Màu xanh lá cây */
                    border: none;
                    padding: 10px 20px;
                    text-align: center;
                    display: inline-block;
                    font-size: 16px;
                    cursor: pointer;
                    border-radius: 5px; margin-left: 10px;'>Xem tất cả</button>";
          }
          ?>
        </form>
      </div>
      <div class="data-table">
        <table>
          <thead>
            <tr>
              <th>Mã sản phẩm</th>
              <th>Tên sản phẩm</th>
              <th>Hình ảnh</th>
              <th>Giá</th>
              <th>Mô tả</th>
              <th>Số lượng</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $conn = new mysqli("localhost", "root", "", "webapp");
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }
            $conn->set_charset("utf8");

            $perPage = 4; // thay đổi số ô sẽ hiển thị. Ví dụ: bảng dữ liệu có 3 ô
            $currentPage = isset($_GET['page_products']) ? (int)$_GET['page_products'] : 1;
            $offset = ($currentPage - 1) * $perPage;

            $searchTerm = isset($_GET['search-products']) ? $conn->real_escape_string($_GET['search-products']) : '';

            $countQuery = "SELECT COUNT(*) AS count FROM products";
            if ($searchTerm) {
              $countQuery .= " WHERE name LIKE '%$searchTerm%'";
            }
            $countResult = $conn->query($countQuery);
            $countRow = $countResult->fetch_assoc();
            $totalProducts = $countRow['count'];

            $totalPages = ceil($totalProducts / $perPage);

            $query = "SELECT * FROM products";
            if ($searchTerm) {
              $query .= " WHERE name LIKE '%$searchTerm%'";
            }
            $query .= " LIMIT ?, ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $offset, $perPage);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $description = htmlspecialchars($row['description']);
                //kiểm tra ô mô tả, nếu có hơn 100 ký tự thì thay bằng dấu ... để bảng sản phẩm gọn hơn
                if (strlen($description) > 100) {
                  $description = substr($description, 0, 100) . '...';
                }
                echo "<tr>
                                    <td style='text-align: center'>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td><img src='" . htmlspecialchars($row['image']) . "' alt='Hình ảnh' style='width: 50px; height: 50px;'></td>
                                    <td>" . htmlspecialchars($row['price']) . "</td>
                                    <td style='white-space: nowrap;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                                max-width: 200px; // thay đổi kích thước trong ô'
                                    >" . $description . "</td>
                                    <td>" . htmlspecialchars($row['number']) . "</td>
                                    <td>
                                        <button style='background-color: #a3a19d; 
                                            border: none;
                                            padding: 10px 20px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            margin: 4px 2px;
                                            cursor: pointer;
                                            border-radius: 5px;'>
                                            <a style='text-decoration: none; color : white;' href='../form_update.php?id=" . htmlspecialchars($row['id']) . "'>Sửa</a>
                                        </button>
                                        <button style='background-color: #ff6363; /* Màu đỏ */
                                            border: none;
                                            padding: 10px 20px;
                                            text-align: center;
                                            text-decoration: none;
                                            display: inline-block;
                                            font-size: 16px;
                                            margin: 4px 2px;
                                            cursor: pointer;
                                            border-radius: 5px;'>
                                            <a style='text-decoration: none; color : white;' href='../src_xuli/delete.php?id=" . htmlspecialchars($row['id']) . "'>Xóa</a>
                                            </button>
                                        </td>
                                    </tr>";
              }
            } else {
              echo "<tr><td colspan='7'>Không có sản phẩm</td></tr>";
            }

            $stmt->close();
            $conn->close();
            ?>
          </tbody>
        </table>
        <div style="text-align:center">
          <?php
          for ($i = 1; $i <= $totalPages; $i++) {
            $searchParam = $searchTerm ? "&search-products=$searchTerm" : '';
            echo "<a style='text-decoration: none; margin: 0 5px;' href='?page_products=$i$searchParam#products'>$i </a>";
          }
          ?>
        </div>
      </div>
    </div>
  </section>


  <script>
    let sidebar = document.querySelector(".sidebar");
    let closeBtn = document.querySelector("#btn");
    let searchBtn = document.querySelector(".bx-search");

    closeBtn.addEventListener("click", () => {
      sidebar.classList.toggle("open");
      menuBtnChange();
    });

    searchBtn.addEventListener("click", () => {
      sidebar.classList.toggle("open");
      menuBtnChange();
    });

    function menuBtnChange() {
      if (sidebar.classList.contains("open")) {
        closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
      } else {
        closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      }
    }
  </script>

  <script>
    document.getElementById("log_out").addEventListener("click", function() {
      window.location.href = "../src_xuli/logout.php";
    });
  </script>
</body>

</html>