<?php
function connectDatabase()
{
    $servername = "localhost";
    // $database = "web";
    $database = "product";
    $username = "root";
    $password = "";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
    $conn = null;
}

function create($product)
{
    $name = $product->get_name();
    $detail = $product->get_detail();
    $brand = $product->get_brand();
    $type = $product->get_type();
    $size = $product->get_size();
    $color = $product->get_color();
    $image = $product->get_image();
    $price = $product->get_price();
    $promotion = $product->get_promotion();

    $stmt = connectDatabase()->prepare("INSERT INTO PRODUCT (ProductName, ProductDetail, ProductType, ProductBrand, ProductSize, ProductColor, ProductImg, ProductPrice, ProductPromotion) VALUES ( :ProductName, :ProductDetail, :ProductType, :ProductBrand, :ProductSize, :ProductColor, :ProductImg, :ProductPrice, :ProductPromotion)");
    $stmt->bindParam(':ProductName', $name);
    $stmt->bindParam(':ProductDetail', $detail);
    $stmt->bindParam(':ProductBrand', $brand);
    $stmt->bindParam(':ProductType', $type);
    $stmt->bindParam(':ProductSize', $size);
    $stmt->bindParam(':ProductColor', $color);
    $stmt->bindParam(':ProductImg', $image);
    $stmt->bindParam(':ProductPrice', $price);
    $stmt->bindParam(':ProductPromotion', $promotion);
    $stmt->execute();
    header("Location: ../product.php?Message=Create successfull !!!");
}

if (isset($_POST['product'])) {
    switch ($_POST['product']) {
        case $_POST['product'] == 'create':

            $name = $_POST['name'];
            $detail = $_POST['detail'];
            $brand = $_POST['brand'];
            $type = $_POST['type'];
            $size = $_POST['size'];
            $color = $_POST['color'];
            $image = file_get_contents($_FILES['image']['tmp_name']);
            $price = $_POST['price'];
            $promotion = $_POST['promotion'];
            $id = null;

            include "../model/product.php";
            $product = new Product($id, $name, $detail, $brand, $type, $size, $color, $image, $price, $promotion);

            if (checkNameProduct($product) > 0)
                header("Location: ../product.php?Message=Trùng tên !!!");
            else
                create($product);

            break;
    }
}
