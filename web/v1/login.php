<?php
    include('connect.php');

    // function __autoload($class_name) {
    //     if ($class_name != "Zend\Stdlib\ArrayObject")
    //         require_once($class_name . '.php');
    // }

    if ($_SESSION['userID']) {
        header("Location:index.php");
    }

    require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
    require_once( 'Facebook/HttpClients/FacebookCurl.php' );
    require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );

    use Facebook\HttpClients\FacebookHttpable;
    use Facebook\HttpClients\FacebookCurl;
    use Facebook\HttpClients\FacebookCurlHttpClient;

    require_once( 'Facebook/FacebookSession.php' );
    require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
    require_once( 'Facebook/FacebookRequest.php' );
    require_once( 'Facebook/FacebookResponse.php' );
    require_once( 'Facebook/FacebookSDKException.php' );
    require_once( 'Facebook/FacebookRequestException.php' );
    require_once( 'Facebook/FacebookAuthorizationException.php' );
    require_once( 'Facebook/GraphObject.php' );
    require_once( 'Facebook/Entities/AccessToken.php' );
     
    use Facebook\FacebookSession;
    use Facebook\FacebookRedirectLoginHelper;
    use Facebook\FacebookRequest;
    use Facebook\FacebookResponse;
    use Facebook\FacebookSDKException;
    use Facebook\FacebookRequestException;
    use Facebook\FacebookAuthorizationException;
    use Facebook\GraphObject;

    FacebookSession::setDefaultApplication('755278891254890', '848c46a242092f2d1b40149cf982a7e0');

    // login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper( 'http://www.achow.com.br/login.php' );
     
    try {
        $session = $helper->getSessionFromRedirect();
    } catch( FacebookRequestException $ex ) {
        // When Facebook returns an error
    } catch( Exception $ex ) {
        // When validation fails or other local issues
    }

    if ($_POST) {
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $sql = "SELECT * FROM account WHERE email = '". $email ."' and password = '" . $password . "'";
        $row = $conn->query($sql)->fetchAll();

        if (sizeof($row) > 0) {
            $_SESSION['userID'] = $row[0]['id'];

            header("Location:index.php");
        }
    }
     
    // see if we have a session
    if ( isset( $session ) ) {
        // graph api request for user data
        $request = new FacebookRequest( $session, 'GET', '/me?fields=id,first_name,last_name,gender,cover' );
        $response = $request->execute();
        // get response
        $graphObject = $response->getGraphObject();

        $id = $graphObject->getProperty('id');

        $sql = "SELECT * FROM account WHERE facebookID = '". $id ."'";
        $row = $conn->query($sql)->fetchAll();

        if (sizeof($row) > 0) {
            $_SESSION['userID'] = $row[0]['id'];

            header("Location:index.php");
        } else {
            // Register

            $name = $graphObject->getProperty('first_name');
            $last_name = $graphObject->getProperty('last_name');
            if ($graphObject->getProperty('gender') == "male") {
                $gender = "Masculino";
            } else if ($graphObject->getProperty('gender') == "female") {
                $gender = "Feminino";
            } else {
                $gender = "";
            }

            $image = "https://graph.facebook.com/" . $id . "/picture?type=large";

            if ($graphObject->getProperty('cover') && 
                $graphObject->getProperty('cover')->getProperty('source')) {

                $cover = $graphObject->getProperty('cover')->getProperty('source');

                $sql_insert = "INSERT INTO account (name, lastname, gender, facebookID, image, cover) 
                                VALUES ('" . $name ."', '" . $last_name ."', '" . $gender ."', '" . $id ."' ,'" . $image ."', '" . $cover . "')";

                $stmt = $conn->prepare($sql_insert);
                $stmt->execute();

                $user_id = $conn->lastInsertId();

                $_SESSION['userID'] = $user_id;

            } else {
                $sql_insert = "INSERT INTO account (name, lastname, gender, facebookID, image) 
                                VALUES ('" . $name ."', '" . $last_name ."', '" . $gender ."', '" . $id ."' ,'" . $image ."')";

                $stmt = $conn->prepare($sql_insert);
                $stmt->execute();

                $user_id = $conn->lastInsertId();

                $_SESSION['userID'] = $user_id;
            }

            header("Location:index.php");
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <title>Achow - Login</title>

        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="css/template.css"  media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    </head>

    <body class='grey lighten-3'>
        <div class="container login">
            <div class="row">
                <div class="col s12 m8 offset-m2 l6 offset-l3">
                    <nav style="height:155px;">
                        <div class="nav-wrapper">
                            <a href="#!" class="brand-logo"><img src="images/logo.png" /></a>
                        </div>
                    </nav>

                    <div class="card-panel login">
                        <a href="<?php echo $helper->getLoginUrl(); ?>" class="btn facebook-color">Entrar com o Facebook</a>
                        <form method="post">
                            <div class="input-field col s12">
                                <input name="email" type="email" class="validate" required>
                                <label for="email">Email</label>
                            </div>
                            <div class="input-field col s12">
                                <input name="password" type="password" class="validate" required>
                                <label for="password">Password</label>
                            </div>
                            <a href="register.php" class="btn blue darken-4" disabled>Criar conta</a>
                            <button type="submit" class="btn blue login-entrar-btn">Login</button>
                        </form>
                        <div id="status"></div>
                    </div>
                </div>
            </div>
        </div>
        

        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('ul.tabs').tabs();
            });
        </script>
    </body>
</html>