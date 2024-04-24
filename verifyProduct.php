<?php
include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

if (isset($_POST['product_id'])){
    $product_id = $_POST['product_id'];

    $sql = "UPDATE products SET is_verified = 1 WHERE product_id = '$product_id'";
    $result = mysqli_query($CON,$sql);

    if ($result){
        echo json_encode(array("message" => "Product verified"));
    }else{
        echo json_encode(array("message" => "Error verifying product"));
    }
}else{
    echo json_encode(array("message" => "Product ID not provided"));
}