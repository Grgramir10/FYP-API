<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';


if (!isset($_POST['token'])) {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required"
    ));
    die();
}

$token = $_POST['token'];

$userId = getUserId($token);

if (!$userId) {
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid token"
    ));
    die();
}


if (isset(
    $_POST['product_id'],
    $_POST['rating']
)) {

    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];


    $sql = "select * from rating where product_id = $product_id AND user_id = $userId";

    $result = mysqli_query($CON, $sql);


    $rating_id = null;

    if (mysqli_num_rows($result) > 0) {
        $ratingData = mysqli_fetch_assoc($result);
        $rating_id = $ratingData['rating_id'];
    }

    $sql = '';

    if ($rating_id != null) {
        $sql = "UPDATE rating SET rating = $rating WHERE rating_id = $rating_id";
    } else {
        $sql = "INSERT INTO rating (user_id, product_id, rating) VALUES ('$userId', '$product_id', '$rating')";
    }
    $result = mysqli_query($CON, $sql);


    if ($result) {

        echo json_encode(array(
            "success" => true,
            "message" => "Rating added successfully"
        ));


        $sql = "UPDATE products SET rating = (SELECT AVG(rating) FROM rating WHERE product_id = $product_id) WHERE product_id = $product_id";
        $result = mysqli_query($CON, $sql);
        die();
    }

    echo json_encode(array(
        "success" => false,
        "message" => "Failed to add rating"
    ));
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "product_id and rating are required"
    ));
    die();
}
