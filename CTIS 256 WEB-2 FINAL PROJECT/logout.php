<?php
session_start();

setcookie("PHPSESSID", "", 1, "/"); //logically invalidate the session id.
session_destroy(); //deletes the session

header("Location: index.php");
