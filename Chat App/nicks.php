<?php


//REST web service for messages table/resource
require "./db.php";
header("Content-Type: application/json"); //return data in JSON format

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = addNewNick($_POST["nick"]);
}

if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $requestData = file_get_contents("php://input"); // data part of the http request packet.
    parse_str($requestData, $_DELETE);
    $response = delNick($_DELETE["nick"]);
}


echo json_encode($response); //send messages in json format.

function delNick($nick)
{
    global $db;
    $stmt = $db->prepare("delete from nickinuse where nick = ?");
    $stmt->execute([$nick]);
    return ["valid" => true];
}

function addNewNick($nick)
{
    global $db; //to use database obeject in function.

    try {
        $stmt = $db->prepare("insert into nickinuse (nick) values (?)");
        $stmt->execute([$nick]);
        return ["valid" => true];
    } catch (PDOException $ex) { //it prevents dublication 
        return ["valid" => false];
    }
}
