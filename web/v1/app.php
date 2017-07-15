<?php
    $sqlStore = "SELECT * FROM store WHERE id = '" . $_GET['id'] . "'";
    $rowStore = $conn->query($sqlStore)->fetchAll();

    $store = $rowStore[0];

    $opcoes = array("reservas" => "Reservas", "cartoesCredito" => "Cartões de Crédito", "mesaLivre" => "Mesa ao ar livre", 
                    "areaPrivada" => "Área privada", "musica" => "Música", "wifi" => "WiFi", 
                    "estacionamento" => "Estacionamento", "chapelaria" => "Chapelaria", "banheiro" => "Banheiro", 
                    "acessoCadeirantes" => "Acessível para Cadeirantes", "tvs" => "TVs", "caixaEletronico" => "Caixa eletrônico",
                    "permitidoFumar" => "Permitido fumar", "servicoMesa" => "Serviço de mesa", "servicoBar" => "Serviço de bar",
                    "paraViagem" => "Para viagem", "entrega" => "Entrega", "driveThru" => "Drive-thru",
                    "rodizio" => "Rodízio");

    if($_POST) {
        if (sizeof($_POST['comentario']) > 0) {
            $sql_insert = "INSERT INTO store_comment (idAccount, idStore, message) VALUES (?,?, ?)";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $user['id']);
            $stmt->bindValue(2, $store['id']);
            $stmt->bindValue(3, $_POST['comentario']);
            $stmt->execute();

            redirect("index.php?id=" . $store['id']);
        }

        if (sizeof($_POST['encontro']) > 0) {
            $users = "";
            for ($i=0; $i < sizeof($_POST['users']); $i++) { 
                $users .= $_POST['users'][$i] . ", ";

                $sql_insert = "INSERT INTO store_notification (idUserSend, idUserReceiver, idStore, message) VALUES (?,?,?,?)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $_POST['users'][$i]);
                $stmt->bindValue(3, $store['id']);
                $stmt->bindValue(4, $_POST['encontro']);
                $stmt->execute();

                $sql = "SELECT * FROM account WHERE id = '". $user['id'] ."'";
                $row2 = $conn->query($sql)->fetchAll();

                $sql = "SELECT * FROM account WHERE id = '". $_POST['users'][$i] ."'";
                $row1 = $conn->query($sql)->fetchAll();

                if ($row1[0]['gcm_regid']) {
                    $result = send_notification(array($row1[0]['gcm_regid']), array(
                                                "msg" => "Marcou um encontro com você.",
                                                "title" => $row2[0]['name'] . " " . $row2[0]["lastname"],
                                                "action" => "Meeting",
                                                "image" => $row2[0]['image'],
                                                "name" => "fragment",
                                                "value" => "1"
                                            ));
                }
            }

            $sql_insert = "INSERT INTO store_checkin (idAccount, idStore, message, users) VALUES (?,?,?,?)";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $user['id']);
            $stmt->bindValue(2, $store['id']);
            $stmt->bindValue(3, $_POST['encontro']);
            $stmt->bindValue(4, $users);
            $stmt->execute();

            redirect("index.php?id=" . $store['id']);
        }

        if (sizeof($_POST['checkin']) > 0) {
            $sql_insert = "INSERT INTO store_checkin (idAccount, idStore, message) VALUES (?,?,?)";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $user['id']);
            $stmt->bindValue(2, $store['id']);
            $stmt->bindValue(3, $_POST['checkin']);
            $stmt->execute();

            redirect("index.php?id=" . $store['id']);
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

<div>
    <div id="test1" class="col s12">
        <div class="row tab-area">
            <div class="col l5 s12">
                <?php if($store['image'] != null) { ?>
                <div class="card">
                    <div class="card-image">
                        <img src="images/store/<?php echo $store['image'] ?>">
                    </div>
                </div>
                <?php } ?>

                <?php if($store['vip'] == "sim") { ?>
                <div class="card">
                    <div class="card-image">
                        <div class="slider">
                            <ul class="slides">
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

                <?php
                    $sqlSchedule = "SELECT * FROM store_schedule WHERE idStore = '" . $store['id'] . "' ORDER BY dayOfWeek ASC";
                    $rowSchedule = $conn->query($sqlSchedule)->fetchAll();

                    if (sizeof($rowSchedule) > 0 && $store['vip'] == "sim") {
                ?>

                <div class="card">
                    <div class="card-content">
                        <h6>Horários de Funcionamento</h6>

                        <div class="row">
                            <?php 
                                $dayOfWeek = array("Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado");

                                for ($i=0; $i < sizeof($rowSchedule); $i++) { ?>
                                <div class="col l6">
                                    <?php echo $dayOfWeek[$rowSchedule[$i]['dayOfWeek']];  ?>
                                </div>
                                <div class="col l6">
                                    <?php 
                                        if ($rowSchedule[$i]['closed'] == 0) {
                                            echo $rowSchedule[$i]['hourOpen'] . ' - ' . $rowSchedule[$i]['hourClose']; 
                                        } else {
                                            echo "Fechado";
                                        }
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

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
                                           INNER JOIN account AS a ON c.idAccount = a.id WHERE c.idStore = '" . $store['id'] . "' 
                                           ORDER BY c.id DESC";
                            $rowComment = $conn->query($sqlComment)->fetchAll();

                            for ($i=0; $i < sizeof($rowComment); $i++) { 
                        ?>
                        <li class="row">
                            <div class="col l2">
                                <div>
                                    <?php if($rowComment[$i]['userImage'] != null) { ?>
                                    <a href="index.php?pg=perfil&id=<?php echo $rowComment[$i]['userID'] ?>">
                                        <img class="photo" src="<?php echo $rowComment[$i]['userImage']; ?>" />
                                    </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col l10" style="word-wrap:break-word;">
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
                <?php } ?>
            </div>
            <div class="col l7 s12">
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
                        <span><?php echo $store['address'] ?>, <?php echo $store['bairro'] ?> - <?php echo $store['cidade'] ?> - <?php echo $store['estado'] ?></span>
                        <?php if($store['vip'] == "sim") { ?>
                        <br /><br />
                        <span>
                            <?php 
                                echo $store['phone1']; 
                                echo $store['phone2'] != "" ? " / " . $store['phone2'] : ""; 
                                echo $store['phone3'] != "" ? " / " . $store['phone3'] : ""; 
                            ?>
                        </span>
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

                <div class="app-buttons">
                    <a href="index.php?id=<?php echo $_GET['id']; ?>&ac=favoritos" class="col l3 waves-effect waves-light btn blue"><i class="md-star left"></i>Favorito</a>
                    <a href="index.php?id=<?php echo $_GET['id']; ?>&ac=gostei" class="col l3 waves-effect waves-light btn blue"><i class="mdi-action-thumb-up left"></i>Gostei</a>
                    <a href="#encontro" class="col l3 waves-effect waves-light btn blue modal-trigger"><i class="mdi-social-people left"></i>Marcar encontro</a>
                    <a href="#checkin" class="col l3 waves-effect waves-light btn blue modal-trigger"><i class="mdi-maps-place left"></i>Estou aqui</a>
                </div>

                <div class="card">
                    <?php if($store['vip'] == "sim") { ?>
                    <div class="card-image">
                        <div id="us5" style="height:400px"></div>
                    </div>
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

                <?php if($store['vip'] == "sim") { ?>
                <div>
                    <?php 
                        $sqlMenu = "SELECT * FROM store_menu WHERE idStore = '" . $store['id'] . "' ORDER BY type ASC";
                        $rowMenu = $conn->query($sqlMenu)->fetchAll();

                        $menu = "";
                        $types = array();

                        for ($i=0; $i < sizeof($rowMenu); $i++) { 
                            if ($menu != $rowMenu[$i]['type']) {
                                $menu = $rowMenu[$i]['type'];
                                array_push($types, $menu);
                            }
                        }
                    ?>
                    <ul class="collapsible" data-collapsible="accordion">
                        <?php for($j = 0; $j < sizeof($types); $j++) { ?>
                        <li>
                            <div class="collapsible-header <?php echo $j == 0 ? 'active' : ''; ?>"><?php echo $types[$j] ?></div>
                            <div class="collapsible-body">
                                <ul class="list">
                                    <?php 
                                        $sqlMenu = "SELECT * FROM store_menu WHERE idStore = '" . $store['id'] . "' ORDER BY type ASC";
                                        $rowMenu = $conn->query($sqlMenu)->fetchAll();

                                        for ($i=0; $i < sizeof($rowMenu); $i++) { 
                                            if ($rowMenu[$i]['type'] == $types[$j]) {
                                    ?>
                                    <li class="row">
                                        <div class="col l2">
                                            <div>
                                                <?php if($rowMenu[$i]['image'] != null) { ?>
                                                <img src="images/menu/<?php echo $rowMenu[$i]['image']; ?>" class="photo" />
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col l8">
                                            <span><?php echo $rowMenu[$i]['name'] ?></span><br>
                                            <?php echo $rowMenu[$i]['description'] ?>
                                        </div>
                                        <div class="col l2">R$ <?php echo $rowMenu[$i]['price'] ?></div>
                                    </li>
                                    <?php } } ?>
                                </ul>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
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
            $s = "SELECT * FROM store_visited WHERE idAccount = '" . $user['id'] . "' and idStore = '" . $store['id'] . "' and idVisitedType = 1";
            $r = $conn->query($s)->fetchAll();    
            if (sizeof($r) == 0) {
                $sql_insert = "INSERT INTO store_visited (idAccount, idStore, idVisitedType) VALUES (?,?, 1)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $store['id']);
                $stmt->execute();
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
        } else if ($_GET['ac'] == "naogostei") {
            $s = "SELECT * FROM store_visited WHERE idAccount = '" . $user['id'] . "' and idStore = '" . $store['id'] . "' and idVisitedType = 2";
            $r = $conn->query($s)->fetchAll();    
            if (sizeof($r) == 0) {
                $sql_insert = "INSERT INTO store_visited (idAccount, idStore, idVisitedType) VALUES (?,?, 2)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $store['id']);
                $stmt->execute();
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("<?php echo $store['name']; ?> marcado como não gostei.", 1500 );
                });
            </script>
            <?php
            } else {
            ?>
            <script type="text/javascript">
                $(document).ready(function(){
                    Materialize.toast("Você já marcou como não gostou de <?php echo $store['name']; ?>.", 1500 );
                });
            </script>
            <?php
            }
        } else if ($_GET['ac'] == "favoritos") {
            $s = "SELECT * FROM store_visited WHERE idAccount = " . $user['id'] . " and idStore = " . $store['id'] . " and idVisitedType = 5";
            $r = $conn->query($s)->fetchAll();    

            if (sizeof($r) == 0) {
                $sql_insert = "INSERT INTO store_visited (idAccount, idStore, idVisitedType) VALUES (?,?, 5)";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $user['id']);
                $stmt->bindValue(2, $store['id']);
                $stmt->execute();
                redirect('index.php?id=' . $_GET['id'].'&a=a');
            } else {
                $sql_insert = "DELETE FROM store_visited WHERE id = ?";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $r[0]['id']);
                $stmt->execute();

                die;
                redirect('index.php?id=' . $_GET['id'].'&d=d');
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

    <?php if($store['lat'] != null) { ?>
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
