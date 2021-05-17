<?php

// REST Web Service for messages table/resource
require "./db.php";
header("Content-Type: application/json"); // return data in json format

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $response = getMessages($_GET["lastId"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = addMessage($_POST["nick"], $_POST["message"]);
}

echo json_encode($response); // send messages in json format.

// Private Function for Web Service
function addMessage($nick, $msg)
{
    global $db;
    $stmt = $db->prepare("insert into messages (nick, content) values (?,?)");
    $stmt->execute([$nick, $msg]);
    return ["valid" => true];
}




function getMessages($lastId)
{
    global $db; // $db is a global variable.
    $stmt = $db->prepare("select * from messages where id > ? order by id desc");
    $stmt->execute([$lastId]);
    $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $out = [];
    foreach ($all as $msg) {
        $date = new DateTime($msg["created"]);
        $time = $date->format("H:i:s");
        $row = [
            "id" => $msg["id"],
            "time" => $time,
            "nick" => filter_var($msg["nick"], FILTER_SANITIZE_STRING),
            "content" => filter_var($msg["content"], FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        ];
        array_push($out, $row);
    }
    return $out;
}
