<?php
require_once "db.php";
session_start();

//PROTECTION @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
require_once "./protect.php"; //it doesnt allow user if it not authenticated!!!.

//var_dump($_SESSION);
$user = $_SESSION["user"];
//var_dump($_SESSION);
$owner = $user["id"];
//var_dump($owner);

//CREATE TASK CONTENT
if (isset($_POST["taskheader"])) {
    //var_dump($_POST);
    extract($_POST);

    //REGULAR EXPRESSION PART
    $RegEx = '/^(?=.*\S).+$/';
    if (preg_match($RegEx, $taskheader) === 0) {
        $error[] = "err";
    }
    //XSS PREVENTION FOR $taskheader
    $taskheader = filter_var($taskheader, FILTER_SANITIZE_FULL_SPECIAL_CHARS);



    if (!empty($taskheader) && empty($error)) {
        try {
            $sql = "insert into taskheader (header,owner) values(?,?)";
            $rs  = $db->prepare($sql);
            $rs->execute([$taskheader, $owner]);
            header("Location: main.php?id=$owner2");
            exit;
        } catch (PDOException $ex) {
            $errMsg = "Insert Fail";
        }
    } else {
        $fail = "Empty list!";
    }
}

//GET ID TO INSERT
if (isset($_GET["id"])) {
    $owner2 = $_GET["id"];

    $rs = $db->prepare("select * from task where owner = ?");
    $rs->execute([$owner2]);
    $showrecords = $rs;
}

//INSERT TASK RECORD
if (isset($_POST["record"]) && isset($owner2)) {
    $record = $_POST["record"];
    //var_dump($record);
    //REGULAR EXPRESSION PART
    $RegEx = '/^(?=.*\S).+$/';
    if (preg_match($RegEx, $record) === 0) {
        $error[] = "err";
    }
    //XSS PREVENTION FOR $taskheader
    $record = filter_var($record, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!empty($record) && empty($error)) {
        try {
            $sql = "insert into task (content,owner,userid) values(?,?,?)";
            $rs  = $db->prepare($sql);
            $rs->execute([$record, $owner2, $owner]);
            header("Location: main.php?id=$owner2");
            exit;
        } catch (PDOException $ex) {
            $errMsg = "Insert Fail";
        }
    } else {
        $fail = "Empty list!";
    }

    //UPDATE records list after post req
    $rs = $db->prepare("select * from task where owner = ?");
    $rs->execute([$owner2]);
    $showrecords = $rs;
}

//Delete data from db
if (isset($_POST["deleteId"])) {

    try {
        $id = $_POST["deleteId"];
        $stmt = $db->prepare("delete from task where id = ?");
        $stmt->execute([$id]);

        //UPDATE records list after post req
        $rs = $db->prepare("select * from task where owner = ?");
        $rs->execute([$owner2]);
        $showrecords = $rs;
    } catch (PDOException $ex) {
        $errMsg = "Delete Fail";
    }
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Title of the document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


</head>

<body>
    <!--Secreen one -->
    <div class="containerTodo">
        <div class="contentMenu">
            <table class="highlight">
                <tr>
                    <td><?php $profile = $user["profile"];
                        echo "<img src='images/$profile'  class='circle' >";

                        ?></td>
                    <td>
                        <p><?= $user["name"]; ?></p>
                        <p><?= $user["email"]; ?></p>
                    </td>
                    <td><a href=" logout.php"><i class="material-icons small icoExit">exit_to_app</i></a></td>
                </tr>


                <!-- important btn -->
                <tr>
                    <td class="txt" colspan="3">
                        <a href="" id="imp_btn">

                            <p class="menutext textcolorblue"><i class="material-icons icoStar small ">star_border</i> Important</p>


                        </a>
                    </td>
                </tr>
                <!-- important btn -->

                <?php
                require_once "db.php";
                $sth = $db->prepare("SELECT id FROM taskheader WHERE owner = ?");
                $sth->execute([$owner]);

                $result = $sth->fetchAll(PDO::FETCH_ASSOC);

                $remain_tasks = [];

                foreach ($result as $item) {
                    $sth2 = $db->prepare("SELECT COUNT(completed) FROM task where owner = ? AND completed = 0");
                    $sth2->execute([$item["id"]]);
                    foreach ($sth2 as $item2) {
                        array_push($remain_tasks, $item2["COUNT(completed)"]);
                    }
                }



                //var_dump($result);

                $rs = $db->prepare("select * from taskheader where owner = ?");
                $rs->execute([$owner]);

                $i = 0;
                foreach ($rs as $headers) {
                    $badge_count = $remain_tasks[$i];
                    if ($badge_count == '0')
                        $badge_count = '';

                    $i++;
                    echo "
                    
                    <tr>
                    <td class='txt' colspan='2'> 
                        <a href='?id={$headers["id"]}' class='tofocus' >
                                <p class='collection-item textcolorblue'><i class='material-icons small icoDehaze'>dehaze</i>{$headers["header"]}</p>
                        </a>
                     </td>
                    <td>
                        <span class='badge'>$badge_count</span>
                    </td>
                    </tr>
                 ";
                }

                ?>

            </table>
            <table class="striped">
                <tbody id=theader>
                    <tr>
                        <td colspan="3" class="txt" id="newlist_btn" style="cursor: pointer;">
                            <p class="textcolorblue"><i class="material-icons icoadd">add</i> <em>New List</em></p>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

        <!-- Modal Structure -->

        <div id="nickModal" class="modal">
            <form action="#" id="nickForm" method="post">
                <div class="input-field modalbox">
                    <input name="taskheader" id="nick" type="text" class="validate">
                    <label for="nick">List Name</label>
                </div>
            </form>

        </div>

        <!--End of screen one -->






        <!-- secren two-->

        <div class="screentwo blue lighten-2">
            <section>
                <nav>
                    <div class="nav-wrapper  blue lighten-2">
                        <div class=" topheader rspsvinfo" id="header">
                            <?php
                            require_once "db.php";
                            if (isset($_GET["id"])) {
                                $id = $_GET["id"];

                                $rs = $db->prepare("select * from taskheader where id = :id");
                                $rs->execute(["id" => $id]);

                                //eğer query de gelen id databasede yok ise exit
                                if ($rs->rowCount() == 0) {
                                    exit;
                                }
                                $headerInfo =  $rs->fetch(PDO::FETCH_ASSOC);
                                //var_dump($headerInfo["owner"]);
                                //var_dump($taskheader);
                                if ($headerInfo["owner"] == $owner) {
                                    echo "{$headerInfo["header"]}";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </nav>

                <article>
                    <table>
                        <tbody id="importantList">

                        </tbody>
                        <tbody id="taskList">
                            <?php
                            //UPDATE
                            if (isset($showrecords)) {
                                foreach ($showrecords as $task) {
                                    $completed = "";
                                    $overline = "";
                                    $textcolor = "";
                                    if ($task["completed"] == 1) {
                                        $completed = "checked=true";
                                        $overline = "text-decoration: line-through;";
                                        $textcolor = "color:grey;";
                                    }
                                    //if important == 0
                                    $star = "star_border";

                                    if ($task["importent"] == 1) {
                                        $star = "star";
                                    }

                                    //---------------------------------------------------
                                    //eğer gelen query id si kullancı id ile eşleşiyorsa taskları göster
                                    if ($task["userid"] === $owner) {
                                        echo "
                    
                                        <tr style='background:#e8eaf6;'>
                                            <td>
                                            <label>
                                               <input type='checkbox' $completed class='filled-in' onclick='changecompleted({$task['id']})'/>
                                                   <span></span>
                                            </label>
                                            <span style='$overline $textcolor'>{$task["content"]}</span>
                                           </td>
                                           <td>
                                               <a href='#'  onclick='changeImportance({$task['id']})'>
                                                        <i class='material-icons icoStar small '>$star</i>
                                               </a>
                                             
                                           </td>
                                           <td>   
                                           <form action='' method='post'>
                                               <button class='btn  blue lighten-2' type='submit' name='deleteId' value='{$task["id"]}' >
                                                   <i class='material-icons small'>delete_forever</i>
                                               </button>
                                           </form>
                                           </td>
                                       </tr>
                                   ";
                                    } else { //istenen get request ile kullanıcı idsi eşleşmiyor ise tasklar görüntülenmez !!! exit
                                        exit;
                                    }
                                }
                            }





                            ?>

                        </tbody>

                    </table>
                </article>

                <div class="messageBox" id="msgbxhid">
                    <form action="#" method="post">
                        <div class="input-field textfield deep-purple lighten-1">

                            <button class="btn-large  deep-purple lighten-1" type="submit" name="action">
                                <i class="material-icons right">add</i>
                            </button>

                            <input name="record" id="message" type="text" class="validate" placeholder="add a task"></>
                        </div>

                    </form>

                </div>
            </section>
        </div>

        <?php
        if (isset($fail)) {
            echo "<script> M.toast({html:'$fail',classes:'red white-text'});</script>"; //toast message
        }
        if (isset($errMsg)) {
            echo "<script> M.toast({html:'$errMsg',classes:'red white-text'});</script>"; //toast message
        }
        if (isset($_SESSION["message"])) {
            echo "<script> M.toast({html:'{$_SESSION['message']}',classes:'green white-text'});</script>"; //toast message
            unset($_SESSION["message"]);
        }
        ?>



</body>
<script>
    $(function() {


        $("#message").focus();
        var elems = document.querySelector('#nickModal');
        var nickModal = M.Modal.init(elems, {
            dismissible: true,
        });
        $("#newlist_btn").click(function(e) {
            nickModal.open();
            $("#nick").focus();
        })
    })

    function changecompleted(id) {
        $.post("taskoperation.php", {
            id: id
        }, function(result) {
            if (result.status == "completed") {
                location.reload()
            }
        })
    }

    function changeImportance(id) {
        //alert(id);
        $.post("taskoperation.php", {
            idimportant: id
        }, function(result) {
            if (result.status == "completed") {
                location.reload()
            }
        })
    }

    $("#imp_btn").click(function(e) {
        e.preventDefault();
        $("#header").text("Important");
        $("#msgbxhid").hide();
        $("#taskList").hide();
        $("#importantList").empty();
        getImportancetasks(<?= $owner ?>)
    })

    function getImportancetasks(id) {

        //AJAX get request
        $.get("taskoperation.php", {
            userid: id
        }, function(data) {

            //console.log(data);
            rows = "";
            for (let imp of data) {
                rows += `
                    <tr style='background:#e8eaf6;'>
                        <td style='font-size:20px;'>${imp.record}</td>
                        <td style='font-size:20px;'><i>(${imp.recordType})</i></td>
                       <td> <i class="material-icons icoStar small ">star_border</i></td>
                    </tr>

                
                `;
            }
            console.log(rows);
            $("#importantList").prepend(rows);

            //location.reload();
        })
    }
</script>

</html>