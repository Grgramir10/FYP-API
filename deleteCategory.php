<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';


if (

    isset($_POST['token']) &&
    isset($_POST['catID'])

) {
    $token = $_POST['token'];
    $catID = $_POST['catID'];

    $isAdmin = isAdmin($token);


    if (!$isAdmin) {
        echo json_encode(array(
            "success" => false,
            "message" => "You are not authorized!"

        ));
        die();
    }


    global $CON;


    $sql = "delete from categories where category_id = '$catID'";

    $result = mysqli_query($CON, $sql);


    if ($result) {

        echo json_encode(array(
            "success" => true,
            "message" => "Category deleted successfully!"    

        ));
    } else {

        echo json_encode(array(
            "success" => false,
            "message" => "deleting Category failed!"

        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"

    ));
}