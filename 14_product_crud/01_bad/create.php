<?php 
$pdo= new PDO("mysql:host=localhost;port=3306;dbname=products_crud","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

// echo "<pre>";
// echo var_dump($_SERVER['REQUEST_METHOD']);
// echo "</pre>";
$errors=[];
$title='';
$price='';
$description='';
if ($_SERVER['REQUEST_METHOD']=="POST") {
    $title=$_POST["title"];
    $description=$_POST["description"];
    $price=$_POST["price"];
    $date=date('Y-m-d H:i:s');
    $image=$_FILES["image"] ?? null;
    $imagePath='';
    if(!is_dir("images")){
        mkdir("images");
    }
    if ($image && $image['tmp_name']) {
        $imagePath = 'images/' . randomString(8) . '/' . $image['name'];
        mkdir(dirname($imagePath));
        move_uploaded_file($image['tmp_name'], $imagePath);
    }
    if (!$title) {
        $errors[]="title is required";
    }
    if(!$price){
        $errors[]="price is required";
    }
    if(empty($errors)){
    $statement=$pdo->prepare("INSERT INTO products (title, image, description, price, create_date)
                    VALUES (:title, :image, :description,:price, :create_date)");
    $statement->bindValue(':title',$title);
    $statement->bindValue(':image',$imagePath);
    $statement->bindValue(':description',$description);
    $statement->bindValue(':price',$price);
    $statement->bindValue(':create_date',$date);
    $statement->execute();
    $title="";
    $price='';
    $description='';
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
  <h1>Create Product</h1>
  <?php if(!empty($errors)): ?>
  <div class="alert alert-danger" role="alert">
    <?php foreach($errors as $error): ?>
        <div><?php echo $error ?></div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  <form action="" method="post" enctype="multipart/form-data">
  <div class="mb-3">
    <p>Image</p>
    <input type="file" name="image">
  </div>
  <div class="mb-3">
    <label>Title</label>
    <input type="text" class="form-control" name="title" value="<?php echo $title ?>">
  </div>
  <div class="mb-3">
    <label>Description</label>
    <textarea class="form-control" name="description"></textarea>
  </div>
  <div class="mb-3">
    <label>Price</label>
    <input type="number" class="form-control" name="price" value="<?php echo $price ?>">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
  </body>
</html>