<?php
    include('connect.php');

    if ($_SESSION['userID']) {
        header("Location:index.php");
    }

    if ($_POST) {
        $name = $_POST['name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $ocupation = $_POST['ocupation'];

        $sql_insert = "INSERT INTO account (name, lastname, email, password, ocupation) 
                            VALUES ('" . $name ."', '" . $last_name ."', '" . $email ."', '" . $password ."' ,'" . $ocupation ."')";

        $stmt = $conn->prepare($sql_insert);
        $stmt->execute();

        $user_id = $conn->lastInsertId();

        $_SESSION['userID'] = $user_id;

        header("Location:index.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <title>Achow - Criar conta</title>

        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="css/template.css"  media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    </head>

    <body class='grey lighten-3'>
        <div class="container login">
            <div class="row">
                <div class="col s6 offset-s3">
                    <nav style="height:155px;">
                        <div class="nav-wrapper">
                            <a href="#!" class="brand-logo"><img src="images/logo.png" /></a>
                        </div>
                    </nav>

                    <div class="card-panel login">
                        <form method="post">
                            <div class="input-field col s6">
                                <input name="name" type="text" class="validate" required>
                                <label for="name">Nome</label>
                            </div>
                            <div class="input-field col s6">
                                <input name="last_name" type="text" class="validate" required>
                                <label for="last_name">Sobrenome</label>
                            </div>
                            <div class="input-field col s12">
                                <input name="email" type="email" class="validate" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field col s12">
                                <input name="password" type="password" class="validate" required>
                                <label for="password">Senha</label>
                            </div>
                            <div class="input-field col s6">
                                <input name="phone" type="text" class="validate" required>
                                <label for="phone">Telefone</label>
                            </div>
                            <div class="input-field col s6">
                                <input name="ocupation" type="text" class="validate" required>
                                <label for="ocupation">Ocupação</label>
                            </div>
                            <a href="login.php" class="btn blue darken-4">Voltar</a>
                            <button type="submit" class="btn blue login-entrar-btn">Criar conta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </body>
</html>