<?php
    function redirect($url)
    {
        echo '<script type="text/javascript">
                   window.location = "' . $url . '"
              </script>';
    }

    include('connect.php');

    if ($_SESSION['userID']) {
        $id = $_SESSION['userID'];

        if (!$_SESSION['city']) {
            $_SESSION['city'] = "Porto Feliz";
        }

        if ($_GET['ct']) {
            $_SESSION['city'] = $_GET['ct'];

            header("Location:/");
        }

        $sql = "SELECT * FROM account WHERE id = '". $id ."'";
        $row = $conn->query($sql)->fetchAll();

        $sqlMenu = "SELECT * FROM store_type";
        $rowMenu = $conn->query($sqlMenu)->fetchAll();

        $sqlAdsSide = "SELECT * FROM ads WHERE position = 'side'";
        $rowAdsSide = $conn->query($sqlAdsSide)->fetchAll();

        $sqlFav = "SELECT v.*, s.name as Name, s.id AS idStore, s.icon FROM store_visited AS v INNER JOIN store AS s ON v.idStore = s.id WHERE idAccount = '$id' and idVisitedType = 5 ORDER BY id DESC LIMIT 5";
        $rowFav = $conn->query($sqlFav)->fetchAll();

        $user = $row[0];

        $sqlNotification = "SELECT * FROM store_notification     WHERE iduserreceiver = '" . $user['id'] . "'";
        $rowNotification = $conn->query($sqlNotification)->fetchAll();

        $sqlFriends = "SELECT * FROM friend_request WHERE idAccount2 = '" . $user['id'] . "'";
        $rowFriends = $conn->query($sqlFriends)->fetchAll();

        $notifications = sizeof($rowFriends);

        for ($i=0; $i < sizeof($rowNotification); $i++) { 
            if($rowNotification[$i]['read'] == 0) {
                $notifications++;
            }
        }
    } else {
        header("Location:login.php");
    }

    $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />

        <title>Achow</title>

        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
        <link type="text/css" rel="stylesheet" href="css/template.css"  media="screen,projection"/>

        <!-- Fav Icon -->
        <link rel="apple-touch-icon" sizes="57x57" href="/images/fav/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/images/fav/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/fav/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/fav/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/fav/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/fav/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/images/fav/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/fav/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/fav/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/images/fav/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/fav/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/images/fav/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/fav/favicon-16x16.png">
        <link rel="manifest" href="/images/fav/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/images/fav/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <!-- Fav Icon -->

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
    </head>

    <body class='grey lighten-3'>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=755278891254890";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

        <!-- Material sidebar -->
        <aside id="sidebar" class="sidebar sidebar-default open z-depth-1" role="navigation">
            <!-- Sidebar header -->
            <div class="sidebar-header header-cover" style="background-image: url(<?php echo $user['cover']; ?>);">
                <!-- Sidebar brand image -->
                <div class="sidebar-image">
                    <?php if($user['image']) { ?>
                    <img src="<?php echo $user['image']; ?>">
                    <?php } ?>
                </div>
                <!-- Sidebar brand name -->
                <a class="sidebar-brand" href="#">
                    <?php echo $user['name'] . ' ' . $user['lastname']; ?>
                </a>
            </div>

            <!-- Sidebar navigation -->
            <ul class="sidebar-nav">
                <li>
                    <a href="/index.php?pg=inicio">
                        <i class="sidebar-icon mdi-action-home"></i>
                        Inicio
                    </a>
                </li>
                <li>
                    <a href="/index.php?pg=notificacoes">
                        <i class="sidebar-icon mdi-notification-more"></i>
                        Notificações 
                        <?php if ($notifications > 0) { ?>
                        <span class="new badge"><?php echo $notifications; ?></span>
                        <?php } ?>
                    </a>
                </li>
                <li>
                    <a href="/index.php?pg=contatos">
                        <i class="sidebar-icon mdi-social-people"></i>
                        Contatos
                    </a>
                </li>
                <li>
                    <a href="/index.php?pg=locais">
                        <i class="sidebar-icon mdi-maps-map"></i>
                        Locais visitados
                    </a>
                </li>
                <li>
                    <a href="/index.php?pg=salas">
                        <i class="sidebar-icon mdi-communication-comment"></i>
                        Bate papo
                    </a>
                </li>
                <li>
                    <a href="/index.php?pg=configuracoes">
                        <i class="sidebar-icon mdi-action-settings"></i>
                        Configurações
                    </a>
                </li>
                <li>
                    <a href="#faleConosco" class="modal-trigger">
                        <i class="sidebar-icon mdi-communication-phone"></i>
                        Fale conosco
                    </a>
                </li>
                <?php if(sizeof($rowFav) > 0) { ?>
                <li class="divider"></li>
                <li>
                    <a href="/index.php?pg=favoritos">
                        <i class="sidebar-icon md-star"></i>
                        Favoritos
                    </a>
                </li>
                <li>
                    <?php for($i = 0; $i < sizeof($rowFav); $i++) { ?>
                    <a href="/index.php?id=<?php echo $rowFav[$i]['idStore']; ?>">
                        <img class="icon-fav-menu" src="images/store/<?php echo $rowFav[$i]['icon']; ?>" />
                        <?php echo $rowFav[$i]['Name']; ?>
                    </a>
                    <?php } ?>
                </li>
                <?php } ?>
                <li class="divider"></li>
                <li>
                    <a href="/index.php?pg=institucional">
                        <i class="sidebar-icon mdi-action-info"></i>
                        Institucional
                    </a>
                </li>
                <li>
                    <a href="logoff.php">
                        <i class="sidebar-icon mdi-action-exit-to-app"></i>
                        Sair
                    </a>
                </li>
            </ul>

            <ul class="side-ads">
                <?php for($i = 0; $i < sizeof($rowAdsSide); $i++) { if($rowAdsSide[$i]['idstore'] != null) { ?>
                <li><a href="index.php?id=<?php echo intval($rowAdsSide[$i]['idstore']); ?>"><img src="images/top/side/<?php echo $rowAdsSide[$i]['file']; ?>" /></a></li>
                <?php } else { ?>
                <li><a href="<?php echo $rowAdsSide[$i]['link']; ?>" target="_blank"><img src="images/top/side/<?php echo $rowAdsSide[$i]['file']; ?>" /></a></li>
                <?php } } ?>
            </ul>

            <!-- Sidebar divider -->
            <!-- <div class="sidebar-divider"></div> -->
            
            <!-- Sidebar text -->
            <!--  <div class="sidebar-text">Text</div> -->
        </aside>

        <?php if ($_GET['pg'] == 'mensagem' || $_GET['pg'] == 'chat') { ?>
        <main style="height: 97%;">
        <div class="site-content" style="height: 100%;">
        <?php } else { ?>
        <main>
        <div class="site-content">
        <?php } ?>


            <?php

                if ($_GET['pg'] == 'contatos') {
                    include('contacts.php'); 
                } else if ($_GET['pg'] == 'sub') {
                    include('subtypes.php'); 
                } else if ($_GET['pg'] == 'locais') {
                    include('places.php'); 
                } else if ($_GET['pg'] == 'configuracoes') {
                    include('settings.php'); 
                } else if ($_GET['pg'] == 'perfil') {
                    include('profile.php'); 
                } else if ($_GET['pg'] == 'favoritos') {
                    include('fav.php'); 
                } else if ($_GET['busca'] != '') {
                    include('search.php'); 
                } else if ($_GET['pg'] == 'institucional') {
                    include('institucional.php'); 
                } else if ($_GET['pg'] == 'termos') {
                    include('termos.php'); 
                } else if ($_GET['pg'] == 'politica') {
                    include('politica.php'); 
                } else if ($_GET['pg'] == 'notificacoes') {
                    include('notificacoes.php'); 
                } else if ($_GET['pg'] == 'mensagem') {
                    include('chat.php'); 
                } else if ($_GET['pg'] == 'salas') {
                    include('rooms.php'); 
                } else if ($_GET['pg'] == 'chat') {
                    include('groupChat.php'); 
                } else if ($_GET['pg'] == 'shows') {
                    include('appShows.php'); 
                } else {
                    if ($_GET['id']) {
                        include('app.php'); 
                    } else {
                        include('home.php'); 
                    }
                }

            ?>

            <div id="faleConosco" class="modal">
                <form method="post">
                    <div class="modal-content">
                        <h4>Fale Conosco</h4>
                        <p>
                            <div class="collection">
                                <a href="" class="collection-item">Telefone: 1532615686</a>
                                <a target="_blank" href="https://www.facebook.com/Achowpag/?fref=ts&__mref=message_bubble" class="collection-item">Pág do face</a>
                                <a target="_blank" href="https://www.facebook.com/groups/1655880931318136/?__mref=message_bubble" class="collection-item">Grupo do face</a>
                                <a target="_blank" href="https://instagram.com/achowapp/" class="collection-item">Instagram</a>
                                <a target="_blank" href="https://twitter.com/achowapp/" class="collection-item">Twitter</a>
                                <a href="" class="collection-item">E-mail: contato@achow.com.br</a>
                                <a href="" class="collection-item">Whatsapp: <b>15997636965</b></a>
                            </div>
                        </p>
                    </div>
                </form>
            </div>
        </div>

    </main>

        <footer class="page-footer" style="clear:both">
            <div class="container">
                <div class="row" style="padding-left:55px;">
                    <div class="col s6" style="padding:0">
                        <div class="fb-page" data-href="https://www.facebook.com/Achowpag?fref=ts&amp;ref=br_tf" data-width="500" data-hide-cover="false" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/Achowpag?fref=ts&amp;ref=br_tf"><a href="https://www.facebook.com/Achowpag?fref=ts&amp;ref=br_tf">Achow</a></blockquote></div></div>
                    </div>
                    <div class="col s3">
                        <ul>
                            <li>INFORMAÇÕES</li>
                            <li><a href="/index.php?pg=institucional">Sobre o Achow</a></li>
                            <li><a href="/index.php?pg=politica">Política de Privacidade</a></li>
                            <li><a href="/index.php?pg=termos">Termos e Condições</a></li>
                        </ul>
                    </div>
                    <div class="col s3">
                        <ul>
                            <li>ATENDIMENTO</li>
                            <li><a href="#faleConosco" class="modal-trigger">Contate-nos</a></li>
                            <!-- <li><a href="#">Mapa do Site</a></li> -->
                            <li><a href="/index.php?pg=perfil&id=<?php echo $user['id']; ?>">Minha Conta</a></li>
                            <!-- <li><a href="#">Histórico de Visitas</a></li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-copyright">
                <div class="container" style="padding-left:67px;">
                    © <?php echo date('Y'); ?> Desenvolvido por Paulo Menezes
                </div>
            </div>
        </footer>

        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-63784409-1', 'auto');
          ga('send', 'pageview');

        </script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('.modal-trigger').leanModal();
            });
        </script>
    </body>
</html>