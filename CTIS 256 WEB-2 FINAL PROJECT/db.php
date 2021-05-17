<?php
// data source name
$dsn = "mysql:host=localhost;port=3306;dbname=projecttodo;charset=utf8mb4";
$dbuser = "root";
$dbpass = "";

try {
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
    echo "<p>Connection Error:</p>";
    echo "<p>", $ex->getMessage(), "</p>";
    exit;
}
