<?php
session_start();
require_once "./alreadyAuth.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Title of the document</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
        .input-field {
            width: 50%;
            margin: 30px auto;
        }

        .container {
            margin-top: 150px;
        }

        .modalbx {
            margin-left: 15px;
        }
    </style>
</head>

<body>
    <!-- Modal Structure -->
    <div id="modal1" class="modal">
        <div class="modal-content">
            <h4>CREATER OF THIS PROJECT</h4>
            <h5>Uğur Emre Arıkan - 21703456</h5>
            <h5>Mustafa Buğra Serçe - 21702983</h5>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">
                <h4>OK</h4>
            </a>
        </div>
    </div>
    <!-- Modal Structure -->

    <nav>
        <div class="nav-wrapper  blue lighten-2">
            <a href="#" class="brand-logo center">TaskMan</a>
            <ul id="nav-mobile" class="right">
                <li><i class="material-icons">person_add</i></li>
                <li><a href="register.php">Register</a></li>

            </ul>
        </div>
    </nav>

    <div class="container">
        <form action="login.php" method="post">
            <div class="input-field">
                <i class="material-icons prefix">account_circle</i>
                <input name="email" id="email" type="text" class="validate">
                <label for="email">Email</label>
            </div>

            <div class="input-field">
                <i class="material-icons prefix">lock</i>
                <input name="password" id="password" type="password" class="validate">
                <label for="password">Password</label>
            </div>

            <div class="center">
                <button class="btn waves-effect waves-light" type="submit" name="action">Login

                </button>

                <button data-target="modal1" class="btn modal-trigger modalbx">CREATORS</button>

            </div>
            <!-- Modal Trigger -->

        </form>


    </div>
    <?php
    if (isset($_SESSION["message"])) {
        $err = $_SESSION["message"];
        echo "<script> M.toast({html: '$err'}) ; </script>";
        unset($_SESSION["message"]); //unset: delete from assoc. array.
    }
    ?>



</body>
<script>
    $(function() {
        $('.modal').modal();
    })
</script>

</html>