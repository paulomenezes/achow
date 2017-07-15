<?php
    $sqlStore = "SELECT * FROM shows WHERE id = '" . $_GET['id'] . "'";
    $rowStore = $conn->query($sqlStore)->fetchAll();

    $store = $rowStore[0];

    if($_POST) {
        if (sizeof($_POST['comentario']) > 0) {
            $sql_insert = "INSERT INTO store_comment (idAccount, idShows, message) VALUES (?,?, ?)";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $user['id']);
            $stmt->bindValue(2, $store['id']);
            $stmt->bindValue(3, $_POST['comentario']);
            $stmt->execute();

            //header("Location: index.php?pg=shows&id=" . $store['id']);
        }

        if (sizeof($_POST['encontro']) > 0) {
            $users = "";
            for ($i=0; $i < sizeof($_POST['users']); $i++) { 
                $users .= $_POST['users'][$i] . ", ";

                $sql_insert = "INSERT INTO store_notification (idUserSend, idUserReceiver, idShows, message) VALUES (?,?,?,?)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $_POST['users'][$i]);
                $stmt->bindValue(3, $store['id']);
                $stmt->bindValue(4, $_POST['encontro']);
                $stmt->execute();
            }

            $sql_insert = "INSERT INTO store_checkin (idAccount, idShows, message, users) VALUES (?,?,?,?)";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $user['id']);
            $stmt->bindValue(2, $store['id']);
            $stmt->bindValue(3, $_POST['encontro']);
            $stmt->bindValue(4, $users);
            $stmt->execute();

            //header("Location: index.php?pg=shows&id=" . $store['id']);
        }

        if (sizeof($_POST['checkin']) > 0) {
            $sql_insert = "INSERT INTO store_checkin (idAccount, idShows, message) VALUES (?,?,?)";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $user['id']);
            $stmt->bindValue(2, $store['id']);
            $stmt->bindValue(3, $_POST['checkin']);
            $stmt->execute();

            //header("Location: index.php?pg=shows&id=" . $store['id']);
        }
    }
?>
<nav class="nav3">
    <div class="nav-wrapper">
        <a href="/" class="brand-logo"><img src="images/logo.png" /></a>
    </div>
</nav>

<script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
<script src="adm/plugins/locationpicker/dist/locationpicker.jquery.min.js"></script>
<script src="adm/plugins/select2/js/select2.min.js"></script> 
<link href="adm/plugins/select2/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
    .number {
        position: relative;
        bottom: 18px;
        text-align: center;
    }

    .number div {
        background-color: #2196F3;
        color: #FFF;
        width: 25px;
        margin: 0 auto;
        border-radius: 5px;
    }

    .slider ul.slides li img {
        background-size: contain !important;
        background-position: center !important;
        background-repeat: no-repeat !important;
        background-color: white !important;
    }
</style>

<div>
    <div id="test1" class="col s12">
        <div class="row tab-area">
            <div class="col l6 s12">
                <div class="card">
                    <div class="card-image">
                        <div class="slider">
                            <ul class="slides" style="height:217px;">
                                <?php if($store['image1'] != null) { ?>
                                <li>
                                    <img src="images/store/<?php echo $store['image1'] ?>">
                                </li>
                                <?php } ?>
                                <?php if($store['image2'] != null) { ?>
                                <li>
                                    <img src="images/store/<?php echo $store['image2'] ?>">
                                </li>
                                <?php } ?>
                                <?php if($store['image3'] != null) { ?>
                                <li>
                                    <img src="images/store/<?php echo $store['image3'] ?>">
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col l6 s12">
                <div class="card IMAGEM-MENOR">
                    <div class="card-image">
                        <?php if($store['icon']) { ?>
                        <img src="images/store/<?php echo $store['icon']; ?>" />
                        <?php } else { ?>
                        <img src="images/logo-sq.png" />
                        <?php } ?>
                    </div>
                    <div class="card-data">
                        <p>
                            <?php echo $store['name'] ?>
                        </p>
                        <?php if($store['vip'] == "sim") { ?>
                        <br /><br />
                        <span><?php echo $store['phone1']; echo $store['phone2'] != "" ? " / " . $store['phone1'] : ""; echo $store['phone3'] != "" ? " / " . $store['phone3'] : ""; ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="card">
                    <?php if($store['vip'] == "sim") { ?>
                    <p style="padding: 0 15px; word-break: break-all;">
                        <?php if ($store['site'] != "") { ?>
                            / <span><b>Site:</b> <?php echo $store['site']; ?></span>  <br>
                        <?php } ?>
                        <?php if ($store['twitter'] != "") { ?>
                            / <span><b>Twitter:</b>: <?php echo $store['twitter']; ?></span>  <br>
                        <?php } ?>
                        <?php if ($store['facebook'] != "") { ?>
                            / <span><b>Facebook:</b> <?php echo $store['facebook']; ?></span>  <br>
                        <?php } ?>
                        <?php if ($store['instagram'] != "") { ?>
                            / <span><b>Instagram:</b>: <?php echo $store['instagram']; ?></span> <br>
                        <?php } ?>
                    </p>
                    <?php } ?>
                    <p style="padding: 0 15px; word-break: break-all;">
                        / <span><b>Endereço:</b>: <?php echo $store['address']; ?></span> <br>
                    </p>
                    <?php if($store['vip'] == "sim") { ?>
                    <?php if ($store['description'] != "") { ?>
                        <hr>
                        <p style="padding: 0 15px; word-break: break-all;">
                            <?php echo $store['description']; ?>
                        </p>
                    <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <div class="col l12">
                <div class="app-buttons">
                    <a style="width: calc(33% - 15px)!important" href="index.php?pg=shows&id=<?php echo $_GET['id']; ?>&ac=gostei" class="col l4 waves-effect waves-light btn blue"><i class="mdi-action-thumb-up left"></i>Curtir</a>
                    <a style="width: calc(33% - 15px)!important" href="#encontro" class="col l4 waves-effect waves-light btn blue modal-trigger"><i class="mdi-social-people left"></i>Marcar encontro</a>
                    <a style="width: 33%!important" href="index.php?pg=shows&id=<?php echo $_GET['id']; ?>&ac=confirmar" class="col l4 waves-effect waves-light btn blue modal-trigger"><i class="mdi-maps-place left"></i>Confirmar Participação</a>
                </div>

                <div>
                    <?php 
                        $s = "SELECT sv.*, a.image FROM store_visited AS sv INNER JOIN account AS a ON sv.idAccount = a.id WHERE idShows = '" . $store['id'] . "' and idVisitedType = 2";
                        $r = $conn->query($s)->fetchAll();
                        if (sizeof($r) > 0) {
                    ?>
                        CONFIRMARAM PARTICIPAÇÃO
                        <hr/>
                        <?php for ($i=0; $i < sizeof($r); $i++) { ?>
                            <a href="index.php?pg=perfil&id=<?php echo $r[$i]['idAccount'] ?>" style="float:left;margin-right:10px">
                                <?php if ($r[$i]['image']) { ?>
                                <img style="width:50px;height:50px;border-radius:50%;" class="photo" src="<?php echo $r[$i]['image']; ?>" />
                                <?php } else { ?>
                                <img style="width:50px;height:50px;border-radius:50%;" class="photo" src="/images/logo-sq.png" />
                                <?php } ?>
                                <div class="number"><div><?php echo ($i + 1); ?></div></div>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="clear"></div>

                <div>
                    <?php 
                        $s = "SELECT sv.*, a.image FROM store_visited AS sv INNER JOIN account AS a ON sv.idAccount = a.id WHERE idShows = '" . $store['id'] . "' and idVisitedType = 1";
                        $r = $conn->query($s)->fetchAll();
                        if (sizeof($r) > 0) {
                    ?>
                        CURTIRAM
                        <hr/>
                        <?php for ($i=0; $i < sizeof($r); $i++) { ?>
                            <a href="index.php?pg=perfil&id=<?php echo $r[$i]['idAccount'] ?>" style="float:left;margin-right:10px">
                            <?php if ($r[$i]['image']) { ?>
                                <img style="width:50px;height:50px;border-radius:50%;" class="photo" src="<?php echo $r[$i]['image']; ?>" />
                                <?php } else { ?>
                                <img style="width:50px;height:50px;border-radius:50%;" class="photo" src="/images/logo-sq.png" />
                                <?php } ?>
                            </a>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="clear"></div>

                <div class="card">
                    <?php if($store['vip'] == "sim") { ?>
                    <?php if($store['lat']) { ?>
                    <div class="card-image">
                        <div id="us5" style="height:400px"></div>
                    </div>
                    <?php } ?>
                    <div class="info">
                        <ul>
                            <?php 
                            for ($i=1; $i < sizeof($store); $i++) { 
                                if($store[$i] == "sim" && array_keys($store)[$i * 2] != "vip") {
                                    echo "<li>" . $opcoes[array_keys($store)[$i * 2]] . "</li>";
                                }
                            } ?>
                        </ul>
                        <div style="clear:both"></div>
                    </div>
                    <?php } ?>
                </div>

                <div class="card">
                    <div class="card-content">
                        <form method="post">
                            <div class="input-field">
                                <textarea id="comentario" name="comentario" class="materialize-textarea" required></textarea>
                                <label for="comentario">Digite seu comentario</label>
                            </div>
                            <button type="submit" class="btn blue">Postar</button>
                        </form>
                    </div>
                    <ul class="list">
                        <?php 
                            $sqlComment = "SELECT a.id as userID, a.name as userName, a.lastname as userLast, a.image as userImage, c.* FROM store_comment AS c 
                                           INNER JOIN account AS a ON c.idAccount = a.id WHERE c.idShows = '" . $store['id'] . "' 
                                           ORDER BY c.id DESC";
                            $rowComment = $conn->query($sqlComment)->fetchAll();

                            for ($i=0; $i < sizeof($rowComment); $i++) { 
                        ?>
                        <li class="row">
                            <div class="col l1">
                                <div>
                                    <?php if($rowComment[$i]['userImage'] != null) { ?>
                                    <a href="index.php?pg=perfil&id=<?php echo $rowComment[$i]['userID'] ?>">
                                        <img class="photo" src="<?php echo $rowComment[$i]['userImage']; ?>" />
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col l11" style="word-wrap:break-word;">
                                <span>
                                    <a href="index.php?pg=perfil&id=<?php echo $rowComment[$i]['userID'] ?>">
                                        <?php echo $rowComment[$i]['userName'] . " " . $rowComment[$i]['userLast'] ?>
                                    </a>
                                </span>
                                <br>
                                <?php echo $rowComment[$i]['message'] ?>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Structure -->
<div id="encontro" class="modal">
    <form method="post">
        <div class="modal-content">
            <h4>Marcar encontro</h4>
            <div class="row">
                <div class="row">
                    <div class="input-field col s12">
                        <select name="users[]" class="select2" multiple="multiple" required style="display:block;width:100%;">
                            <?php
                                $sqlUsuarios = "SELECT * FROM account WHERE id <> '" . $user['id'] . "' ORDER BY name ASC";
                                $rowUsuario = $conn->query($sqlUsuarios)->fetchAll();
                                for ($i=0; $i < sizeof($rowUsuario); $i++) { 
                            ?>
                            <option value="<?php echo $rowUsuario[$i]['id']; ?>"><?php echo $rowUsuario[$i]['name'] . ' ' . $rowUsuario[$i]['lastname']; ?></option>
                            <?php } ?>
                        </select>
                        <label style="margin-top:-35px;">Selecione seus amigos</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <textarea required id="encontro" name="encontro" class="materialize-textarea"></textarea>
                        <label for="encontro">E mande uma mensagem para eles</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type='submit' class="modal-action modal-close waves-effect waves-blue btn-flat">Compartilhe com seus amigos</button>
        </div>
    </form>
</div>

<!-- Modal Structure -->
<div id="checkin" class="modal">
    <form method="post">
        <div class="modal-content">
            <h4>Checkin</h4>
            <div class="row">
                <div class="row">
                    <div class="input-field col s12">
                        <textarea required id="checkin" name="checkin" class="materialize-textarea"></textarea>
                        <label for="checkin">O que você achou?</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type='submit' class="modal-action modal-close waves-effect waves-blue btn-flat">Compartilhar</button>
        </div>
    </form>
</div>

<?php 
    if($_GET['ac']) {
        if ($_GET['ac'] == "gostei") {
            $s = "SELECT * FROM store_visited WHERE idAccount = '" . $user['id'] . "' and idShows = '" . $store['id'] . "' and idVisitedType = 1";
            $r = $conn->query($s)->fetchAll();    
            if (sizeof($r) == 0) {
                $sql_insert = "INSERT INTO store_visited (idAccount, idShows, idVisitedType) VALUES (?,?, 1)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $store['id']);
                $stmt->execute();

                redirect("index.php?pg=shows&id=" . $_GET['id']);
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("<?php echo $store['name']; ?> marcado como gostei.", 1500 );
                });
            </script>
            <?php
            } else {
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("Você já marcou como gostou de <?php echo $store['name']; ?>.", 1500 );
                });
            </script>
            <?php
            }
        } else if ($_GET['ac'] == "confirmar") {
            $s = "SELECT * FROM store_visited WHERE idAccount = '" . $user['id'] . "' and idShows = '" . $store['id'] . "' and idVisitedType = 2";
            $r = $conn->query($s)->fetchAll();    
            if (sizeof($r) == 0) {
                $sql_insert = "INSERT INTO store_visited (idAccount, idShows, idVisitedType) VALUES (?,?, 2)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $store['id']);
                $stmt->execute();

                redirect("index.php?pg=shows&id=" . $_GET['id']);
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("Presença confirmada!.", 1500 );
                });
            </script>
            <?php
            } else {
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("Você já confirmou presença.", 1500 );
                });
            </script>
            <?php
            }
        } else if ($_GET['ac'] == "favoritos") {
            $s = "SELECT * FROM store_visited WHERE idAccount = '" . $user['id'] . "' and idShows = '" . $store['id'] . "' and idVisitedType = 5";
            $r = $conn->query($s)->fetchAll();    
            if (sizeof($r) == 0) {
                $sql_insert = "INSERT INTO store_visited (idAccount, dStore, idVisitedType) VALUES (?,?, 5)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $store['id']);
                $stmt->execute();

                redirect("index.php?pg=shows&id=" . $_GET['id']);
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("<?php echo $store['name']; ?> adicionado aos favoritos.", 1500 );
                });
            </script>
            <?php
            } else {
                $sql_insert = "DELETE FROM store_visited WHERE id = ?";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $r[0]['id']);
                $stmt->execute();

                redirect("index.php?pg=shows&id=" . $_GET['id']);
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("<?php echo $store['name']; ?> removido dos seus favoritos.", 1500 );
                });
            </script>
            <?php
            }
        }
    }
?>

<script type="text/javascript">
    $(document).ready(function(){
        $('.modal-trigger').leanModal();
        $('.slider').slider({full_width: true});
        $('.collapsible').collapsible({
            accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
        });
    });

    <?php if($store['lat']) { ?>
    $('#us5').locationpicker({
        location: {
            latitude: <?php echo $store['lat']; ?>, 
            longitude: <?php echo $store['longitude']; ?>
        },
        radius: 10,
    });
    <?php } ?>

    $('.select2').select2();
</script>
