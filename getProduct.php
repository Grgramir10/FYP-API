<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

global $CON;


if(    !isset($_POST['token'])){
    array(
        "success" => false,
        "message" => "token required"
    );

die();
}


$token= $_POST['token'];


$checkAdmin = isAdmin($token);

// if (!$checkAdmin) {
//     echo json_encode(
//         array(
//             "success" => false,
//             "message" => "You are not authorized!"
//         )
//     );
//     die();
// }



if($checkAdmin){
    $sql = "Select * from products join categories on categories.category_id=products.category_id join users on users.user_id = products.user_id";
}
else{
    $sql = "Select * from products join categories on categories.category_id=products.category_id join users on users.user_id = products.user_id where products.is_available = 1 and products.is_verified = 1";
}


// $sql = "Select * from products";

$result = mysqli_query($CON, $sql);

$categories = [];

while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

if ($result) {
    echo json_encode(
        array(
            "success" => true,
            "message" => "Products fetched successfully!",
            "data" => $categories
        )
    );
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Something went wrong!"
        )
    );
}
