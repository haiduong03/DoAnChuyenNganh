<?php
function connectDatabase()
{
    $servername = "localhost";
    $database = "web";
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

function deleteProduct($id)
{
    $stmt = connectDatabase()->prepare("DELETE FROM PRODUCT WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Delete successfull !!!");
}

function changeName($product)
{
    $new = $product->get_name();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductName='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Change successfull !!!");
}

function changeDetail($product)
{
    $new = $product->get_detail();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductDetail='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../admin.php?Message=Change successfull !!!");
}

function changeType($product)
{
    $new = $product->get_type();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductType='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Change successfull !!!");
}

function changeBrand($product)
{
    $new = $product->get_brand();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductBrand='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../admin.php?Message=Change successfull !!!");
}

function changeSize($product)
{
    $new = $product->get_size();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductSize='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Change successfull !!!");
}

function changeColor($product)
{
    $new = $product->get_color();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductColor='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Change successfull !!!");
}

function changeImage($product)
{
    $new = $product->get_image();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductImg=$new WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Change successfull !!!");
}

function changePrice($product)
{
    $new = $product->get_price();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductPrice='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Change successfull !!!");
}

function changePromotion($product)
{
    $new = $product->get_promotion();
    $id = $product->get_id();

    $stmt = connectDatabase()->prepare("UPDATE PRODUCT SET ProductPromotion='$new' WHERE ProductID = '$id'");
    $stmt->execute();
    header("Location: ../product.php?Message=Change successfull !!!");
}

function printProduct()
{
    include_once './model/product.php';

    $stmt = connectDatabase()->prepare("SELECT * FROM PRODUCT ");
    $stmt->execute();
    $listProduct = array();
    while ($row = $stmt->fetch()) {
        array_push(
            $listProduct,
            new Product(
                $row['ProductID'],
                $row['ProductName'],
                $row['ProductDetail'],
                $row['ProductBrand'],
                $row['ProductType'],
                $row['ProductSize'],
                $row['ProductColor'],
                $row['ProductImg'],
                $row['ProductPrice'],
                $row['ProductPromotion']
            )
        );
    }

    return $listProduct;
}

function inforProduct($id)
{
    include_once './model/product.php';

    $stmt = connectDatabase()->prepare("SELECT * FROM PRODUCT WHERE ProductID='$id';");
    $stmt->execute();
    $product = $stmt->fetchAll();
    foreach ($product as $row) {
        $product = new Product(
            $row['ProductID'],
            $row['ProductName'],
            $row['ProductDetail'],
            $row['ProductBrand'],
            $row['ProductType'],
            $row['ProductSize'],
            $row['ProductColor'],
            $row['ProductImg'],
            $row['ProductPrice'],
            $row['ProductPromotion']
        );
    }
    return $product;
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
        case $_POST['product'] == 'delete':

            $id = $_GET['id'];

            deleteProduct($id);

            break;
        case $_POST['product'] == 'change':

            $id = $_GET['id'];
            $name = $_POST['name'];
            $detail = $_POST['detail'];
            $brand = $_POST['brand'];
            $type = $_POST['type'];
            $size = $_POST['size'];
            $color = $_POST['color'];
            if ($_FILES['image']['name'] > 0)
                $image = file_get_contents($_FILES['image']['tmp_name']);
            else
                $image = null;
            $price = $_POST['price'];
            $promotion = $_POST['promotion'];


            include "../model/product.php";
            $product = new Product($id, $name, $detail, $brand, $type, $size, $color, $image, $price, $promotion);

            if ($product->get_name() > 0) {
                changeName($product);
            }

            if ($product->get_detail() > 0) {
                changeDetail($product);
            }

            if ($product->get_brand() > 0) {
                changeBrand($product);
            }

            if ($product->get_type() > 0) {
                changeType($product);
            }

            if ($product->get_size() > 0) {
                changeSize($product);
            }

            if ($product->get_color() > 0) {
                changeColor($product);
            }

            if ($product->get_image() != 0) {
                changeImage($product);
            }

            if ($product->get_price() > 0) {
                changePrice($product);
            }

            if ($product->get_promotion() >= 0) {
                changePromotion($product);
            }

            break;
    }
}
