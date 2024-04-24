<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

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

