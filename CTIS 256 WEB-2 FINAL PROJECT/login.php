<?php
session_start();

//login.php is responsible for the authentication of the user
require_once "db.php";

extract($_POST); //email , password

$rs = $db->prepare("select * from user where email = :email");
$rs->execute(["email" => $email]);

if ($rs->rowCount() === 1) {
    // valid email address
    $user  = $rs->fetch(PDO::FETCH_ASSOC);
    //var_dump($user);
    if (password_verify($password, $user["password"])) {
        // echo "user authenticated";
        $_SESSION["user"] = $user; //login.php puts user data in global persisten data area.
        $_SESSION["message"] = "Succesful login!";
        header("Location:main.php");
    } else {
        $_SESSION["message"] = "Login Failed!";
        header("Location: index.php");
        exit;
    }
} else {

    $_SESSION["message"] = "Login Failed!";
    header("Location: index.php");
    exit;
}
