<?php
session_start();
require_once "./alreadyAuth.php";

//insert a new record to the data base
if (!empty($_POST)) {
    //var_dump($_POST);
    extract($_POST);

    //REGULAR EXPRESSION PART
    $RegEx = '/^(?=.*\S).+$/';
    if (preg_match($RegEx, $username) === 0) {
        $error[] = "username";
    }
    if (preg_match($RegEx, $email) === 0) {
        $error[] = "email";
    }
    if (preg_match($RegEx, $password) === 0) {
        $error[] = "password";
    }

    //XSS PREVENTION
    $username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email =  filter_var($email, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    require_once "./db.php";

    $upload  = new Upload("profile", "images");
    $rs = $db->prepare("select * from user where email = :email");
    $rs->execute(["email" => $email]);

    if ($upload->error() || empty($username) || empty($email) || empty($password) || !empty($error)) {
        $err = 1; //for toast message

        //formda sadece resim gönderilmesini engelliyoruz bu şekilde.
        $unwanted = $upload->file();
        if ($unwanted) {
            unlink("images/$unwanted");
        }
    } else if ($rs->rowCount() === 1) {
        $dublicate_mail_error = 1;
        $unwanted = $upload->file();
        if ($unwanted) {
            unlink("images/$unwanted");
        }
    } else {
        $uploadsuccess = 1; //for toast message
        $rs = $db->prepare("insert into user (name,email,password,profile) values (?,?,?,?)");
        $rs->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $upload->file()]);
        //$password now hash with password_hash() function 
        //dont record raw password into data base always encrypt.
        //redirect browser to index.php
        sleep(1);
        $_SESSION["message"] = "Registration Successful";
        header("Location: index.php");
        exit;
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
        .container {
            margin-top: 100px;
        }

        .input-field {
            width: 60%;
            margin: 30px auto;
        }
    </style>
</head>

<body>
    <nav>
        <div class="nav-wrapper center blue lighten-2">
            <a href="index.php" class="brand-logo">TaskMan</a>

        </div>
    </nav>

    <div class="container">
        <form action="Upload.php" method="post" enctype="multipart/form-data">
            <div class="input-field">
                <input name="username" id="username" type="text" class="validate" value="<?= $username ?? '' ?>">
                <label for="username">Name Lastname</label>
            </div>

            <div class="input-field">
                <input name="email" id="email" type="text" class="validate" value="<?= $email ?? '' ?>">
                <label for="email">Email</label>
            </div>

            <div class="input-field">
                <input name="password" id="password" type="text" class="validate">
                <label for="password">Password</label>
            </div>

            <div class="file-field input-field">
                <div class="btn">
                    <span>File</span>
                    <input type="file" name="profile">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">

                </div>
            </div>


            <div class="center">
                <button class="btn waves-effect waves-light" type="submit" name="action">Register
                    <i class="material-icons right">send</i>
                </button>
            </div>
        </form>
    </div>

    <?php if (isset($err)) : ?>
        <script>
            M.toast({
                html: 'Something is missing!!'
            })
        </script>
    <?php endif; ?>
    <?php if (isset($dublicate_mail_error)) : ?>
        <script>
            M.toast({
                html: 'Mail already in use!'
            })
        </script>
    <?php endif; ?>
    <?php if (isset($uploadsuccess)) : ?>
        <script>
            M.toast({
                html: 'Registration was completed successfully'
            })
        </script>
    <?php endif; ?>


</body>
<script>
    $(function() {

    })
</script>

</html>