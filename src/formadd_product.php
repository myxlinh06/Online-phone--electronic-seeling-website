<!DOCTYPE html>
<html>
<head>
  <title>Upload Product</title>
  <link rel="stylesheet" href="../style/formadd_product.css">
</head>
<body>
  <div class="form-container">
    <h2>Upload Product</h2>
    <form action="../src/src_xuli/add_product.php" method="post" enctype="multipart/form-data">
      <label for="name">Product Name:</label>
      <input type="text" id="name" name="name" placeholder="Enter product name" required>

      <label for="image">Product Image:</label>
      <input type="file" id="image" name="image" required>

      <label for="description">Description:</label>
      <textarea id="description" name="description" placeholder="Enter product description" required></textarea>

      <label for="price">Price:</label>
      <input type="number" id="price" name="price" placeholder="Enter product price" min="0" step="0.01" required>

      <label for="quantity">Quantity:</label>
      <input type="number" id="quantity" name="quantity" placeholder="Enter product quantity" min="1" required>

      <button type="submit">Upload Product</button>      
      <button><a href="../src/src_xuli/Manage.php#products">Back</a></button>

    </form>
  </div>
</body>
</html>
