<?php

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_crud", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];
$title = $product["title"];
$price = $product["price"];
$description = $product["description"];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $date = date('Y-m-d H:i:s');
    $image = $_FILES["image"] ?? null;
    $imagePath = $product["image"];
    if (!is_dir("images")) {
        mkdir("images");
    }

    if ($image && $image["tmp_name"]) {
        if ($product['image']) {
            unlink($product['image']);
        }
        $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
        mkdir(dirname($imagePath));
        move_uploaded_file($image['tmp_name'], $imagePath);
    }
    if (!$title) {
        $errors[] = "title is required";
    }
    if (!$price) {
        $errors[] = "price is required";
    }
    if (empty($errors)) {
        $statement = $pdo->prepare("UPDATE products SET title = :title,
      image = :image,
      description = :description,
      price = :price WHERE id = :id");
        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':id', $id);
        $statement->execute();
        header('Location: index.php');
    }
}

function randomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }

    return $str;
}

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="app.css">
  <title>Product CRUD</title>
</head>

<body>
  <a href="index.php" class="btn btn-secondary">Go Back</a>
  <h1>Edit Product</h1>
  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
      <?php foreach ($errors as $error): ?>
        <div><?php echo $error ?></div>
      <?php endforeach;?>
    </div>
  <?php endif;?>
  <form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <p>Image</p>
      <?php if ($product["image"]): ?>
        <img src="<?php echo $product["image"] ?>" width="50px">
      <?php endif?>
      <input type="file" name="image">
    </div>
    <div class="mb-3">
      <label>Title</label>
      <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
    </div>
    <div class="mb-3">
      <label>Description</label>
      <textarea class="form-control" name="description"><?php echo $product["description"] ?></textarea>
    </div>
    <div class="mb-3">
      <label>Price</label>
      <input type="number" class="form-control" name="price" value="<?php echo $price ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</body>

</html>