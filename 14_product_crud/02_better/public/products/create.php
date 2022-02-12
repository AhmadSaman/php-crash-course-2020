<?php

require_once "../../database.php";
require_once "../../functions.php";


$errors = [];
$title = '';
$price = '';
$description = '';
$product = [
  "image" => "",
  "description" => ""
];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  require_once "./../../validate_product.php";
  if (empty($errors)) {
    $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date)
                    VALUES (:title, :image, :description,:price, :create_date)");
    $statement->bindValue(':title', $title);
    $statement->bindValue(':image', $imagePath);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':create_date', date('Y-m-d H:i:s'));
    $statement->execute();
    $title = "";
    $price = '';
    $description = '';
    header('Location: index.php');
  }
}



?>
<?php include_once "./../../views/partials/header.php" ?>
<a href="index.php" class="btn btn-secondary">Go Back</a>
<h1>Create Product</h1>
<?php include "./../../views/products/form.php" ?>
</body>

</html>