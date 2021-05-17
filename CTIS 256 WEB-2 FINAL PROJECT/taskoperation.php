<?php

require "./db.php";

header("Content-Type: application/json"); // return data in json format

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (isset($_POST["id"])) {
        completed($_POST["id"]);
    }
    if (isset($_POST["idimportant"])) {
        changeImportance($_POST["idimportant"]);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["userid"])) {
        getImportance($_GET["userid"]);
    }
}

function completed($id)
{
    global $db;

    $stmt = $db->prepare("select completed from task where id =?");
    $stmt->execute([$id]);
    $result = $stmt->fetch();
    if ($result["completed"] == 0) {
        # dbde 1 update et

        $stmt = $db->prepare("update task set completed=1 where id=?");
        $result = $stmt->execute([$id]);

        $stmt2 = $db->prepare("update task set importent=0 where id=?");
        $result2 = $stmt2->execute([$id]);
        if ($result && $result2) {
            echo json_encode(["status" => "completed"]); // send messages in json format.
        }
    } else if ($result["completed"] == 1) {
        $stmt = $db->prepare("update task set completed=0 where id=?");
        $result = $stmt->execute([$id]);
        if ($result) {
            echo json_encode(["status" => "completed"]); // send messages in json format.
        }
    }
}


function changeImportance($id)
{
    global $db;
    $stmt = $db->prepare("select * from task where id=?");
    $stmt->execute([$id]);
    $showrecords = $stmt->fetch();

    //task shouldnt be completed
    if ($showrecords["completed"] == 0 && $showrecords["importent"] == 0) {
        $stmt = $db->prepare("update task set importent=1 where id=?");
        $result = $stmt->execute([$id]);
        if ($result) {
            echo json_encode(["status" => "completed"]); // send messages in json format.
        }
    } else if ($showrecords["completed"] == 0 && $showrecords["importent"] == 1) {
        $stmt = $db->prepare("update task set importent=0 where id=?");
        $result = $stmt->execute([$id]);
        if ($result) {
            echo json_encode(["status" => "completed"]); // send messages in json format.
        }
    }
}


function  getImportance($id)
{
    $userid = $id;
    global $db;
    $stmt = $db->prepare("select * from task where userid=?");
    $stmt->execute([$userid]);
    $showrecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // echo json_encode($showrecords);
    $out = [];
    foreach ($showrecords as $uniquser) {
        if ($uniquser["importent"] == 1) {
            $uniquser_importent_record = $uniquser["content"];

            $contentid = $uniquser["owner"];
            $stmt = $db->prepare("select header from taskheader where id=?");
            $stmt->execute([$contentid]);
            $uniquser_importent_record_content_type = $stmt->fetch();
            //$recordType = $uniquser_importent_record_content_type["header"];

            $row = [
                "record" =>  $uniquser_importent_record,
                "recordType" => $uniquser_importent_record_content_type["header"],
            ];

            array_push($out, $row);
        }
    }
    echo json_encode($out);
    return $out;
}
