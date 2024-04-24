<?php
include '../Helpers/DatabaseConfig.php';

if (
    isset($_POST['fullname']) &&
    isset($_POST['email']) &&
    isset($_POST['phoneNumber']) &&
    isset($_POST['location']) &&
    isset($_POST['password'])

) {
    global $CON;
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $phoneNumber = $_POST['phoneNumber'];
    $location = $_POST['location'];
    $password = $_POST['password'];

    $sql = "Select * from users where email ='$email'";
    $result = mysqli_query($CON, $sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Email already exists!"
            )
        );
        return;
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // $sql = "INSERT INTO users (full_name, email, phoneNumber, Location, password,role) VALUES ('$fullname', '$email', '$phoneNumber', '$location','$hashed_password','user')";
        $sql = "INSERT INTO `users`(`full_name`, `password`, `email`, `role`, `phoneNumber`, `Location`) VALUES ('$fullname', '$hashed_password', '$email', 'user', '$phoneNumber','$location')";
        $result = mysqli_query($CON, $sql);

        if ($result) {
            echo json_encode(
                array(
                    "success" => true,
                    "message" => "User registered successfully!"
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
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Please fill all the fields!",
            "required fields" => "fullname, email, password"
        )
    );
}
