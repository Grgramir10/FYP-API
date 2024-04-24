<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

if (

    isset($_POST['token'])

) {
    $token = $_POST['token'];

    $isAdmin = isAdmin($token);


    if (!$isAdmin) {
        echo json_encode(array(
            "success" => false,
            "message" => "You are not authorized!"

        ));
        die();
    }


    global $CON;


    $sql = 'select sum(total) as total_income from orders where status = "paid"';

    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_income = $row['total_income'];

    $sql = 'select count(*) as total_users from users';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_users = $row['total_users'];

    $sql = 'select count(*) as total_orders from orders';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_orders = $row['total_orders'];

    $sql = 'select count(*) as total_products from products';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_products = $row['total_products'];
    $sql = "
select categories.category_id,categories.category_title,sum(amount) as total_income from payments
join order_details on order_details.order_id = payments.order_id
join products on products.product_id = order_details.product_id
join categories on categories.category_id = products.category_id
group by categories.category_id
order by total_income desc
limit 3
";

$result = mysqli_query($CON, $sql);

if (!$result) {
    echo json_encode(array(
        "success" => false,
        "message" => "Error retrieving stats",
        "error" => mysqli_error($CON)
    ));
    die();
}

$top_categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$remainingAmount = $total_income;
$remainingPercentage = 100;


foreach ($top_categories as $key => $user) {
    $top_categories[$key]['percentage'] = round((($user['total_income'] / $total_income) * 100), 2);
    $remainingPercentage -= $top_categories[$key]['percentage'];
    $remainingAmount -= $top_categories[$key]['total_income'];
}

$top_categories[] = array(
    "category_id" => 0,
    "category_title" => "Others",
    "total_income" => $remainingAmount,
    "percentage" => abs(round($remainingPercentage))
);

    if ($result) {



        echo json_encode(array(
            "success" => true,
            "message" => "Stats fetched successfully!",
            "data" => array(
                "total_income" => $total_income,
                "total_users" => $total_users,
                "total_orders" => $total_orders,
                "total_products" => $total_products,
                "top_categories"=> $top_categories

            )

        ));
    } else {

        echo json_encode(array(
            "success" => false,
            "message" => "Fetching total income failed!"

        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"

    ));
}
