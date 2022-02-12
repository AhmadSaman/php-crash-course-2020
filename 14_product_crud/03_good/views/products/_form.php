<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger" role="alert">
        <?php foreach ($errors as $error) : ?>
            <div><?php echo $error ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <p>Image</p>
        <?php if ($product["image"]) : ?>
            <img src="/<?php echo $product["image"] ?>" width="50px">
        <?php endif ?>
        <input type="file" name="image">
    </div>
    <div class="mb-3">
        <label>Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo $product["title"] ?>">
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea class="form-control" name="description"><?php echo $product["description"] ?></textarea>
    </div>
    <div class="mb-3">
        <label>Price</label>
        <input type="number" class="form-control" name="price" value="<?php echo $product["price"] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>