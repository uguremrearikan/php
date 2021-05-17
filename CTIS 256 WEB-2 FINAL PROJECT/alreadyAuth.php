<?php

//if the user has already authenticated dont show index.php, go to main.php
if (isset($_SESSION["user"])) {

    header("Location: main.php");
    exit;
}
