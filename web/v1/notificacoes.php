<?php
    if($_GET) {
        if ($_GET['lida']) {
            $sql_insert = "DELETE FROM store_notification WHERE id = ?";

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, intval($_GET['lida']));
            $stmt->execute();

            if ($_GET['red']) {
                redirect("index.php?pg=mensagem&id=" . $_GET['red']);
            } else {
                redirect("index.php?pg=notificacoes");
            }
        }
    }
?>
<nav class="nav3">
    <div class="nav-wrapper">
        <a href="/" class="brand-logo"><img src="images/logo.png" /></a>
    </div>
</nav>

<div class="row">    
    <div class="lista-stores col s12 l12">
        <div class="content-in">
            <?php
                $sqlFriends = "SELECT * FROM friend_request WHERE idAccount2 = '" . $user['id'] . "'";
                $rowFriends = $conn->query($sqlFriends)->fetchAll();

                if (sizeof($rowFriends) > 0 ) {             
            ?>
            <a href="index.php?pg=contatos#solicitacoes" style="color:white" class='waves-effect waves-light btn blue modal-trigger'>Você tem <?php echo sizeof($rowFriends) ?> solicitações de amizade.</a>
            <?php } ?>
        </div>
        <div class="row card">
            <div class="content-in">
                <p class="stores-title">Notificações</p>
                <?php
                    $sqlNotification = "SELECT * FROM store_notification WHERE idUserReceiver = '" . $user['id'] . "' ORDER BY id DESC";
                    $rowNotification = $conn->query($sqlNotification)->fetchAll();

                    if (sizeof($rowNotification) > 0) {
                    for ($j = 0; $j < sizeof($rowNotification); $j++) { 
                        $sqlStore = "SELECT * FROM store WHERE id = '" . $rowNotification[$j]['idStore'] . "'";
                        $rowStores = $conn->query($sqlStore)->fetchAll();

                        $rowStore = $rowStores[0];

                        $sqlUser = "SELECT * FROM account WHERE id = '" . $rowNotification[$j]['idusersend'] . "'";
                        $rowUser = $conn->query($sqlUser)->fetchAll();

                        $rowUser = $rowUser[0];
                ?>
                <div class="store-item">
                    <div class="image">
                        <?php if ($rowNotification[$j]['idShow'] == null && $rowNotification[$j]['idStore'] == null) { ?>
                        <a href="index.php?pg=perfil&id=<?php echo $rowUser['id']; ?>">
                            <?php if($rowUser['image'] != null) { ?>
                            <img src="<?php echo $rowUser['image']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                        <?php } else { ?>
                        <a href="index.php?id=<?php echo $rowStore['id']; ?>">
                            <?php if($rowStore['icon'] != null && $rowStore['vip'] == "sim") { ?>
                            <img src="images/store/<?php echo $rowStore['icon']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                        <?php } ?>
                    </div>
                    <div class="data">
                        <?php if ($rowNotification[$j]['idShow'] == null && $rowNotification[$j]['idStore'] == null) { ?>
                            <p>
                                Você tem uma nova mensagem de <a href="/index.php?pg=perfil&id=<?php echo $rowUser['id']; ?>"><?php echo $rowUser['name'] . ' ' . $rowUser['lastname']; ?></a> 
                            </p>
                            <br><br>
                            <?php if($rowNotification[$j]['read'] == 0) { ?>
                            <span><a href="index.php?pg=notificacoes&red=<?php echo $rowUser['id']; ?>&lida=<?php echo $rowNotification[$j]['id']; ?>" style="color:white" class='waves-effect waves-light btn blue modal-trigger'>Ver mensagem</a></span>
                            <?php } ?>
                        <?php } else { ?>
                            <p>
                                O usuário <a href="/index.php?pg=perfil&id=<?php echo $rowUser['id']; ?>"><?php echo $rowUser['name'] . ' ' . $rowUser['lastname']; ?></a> convidou você para ir ao 
                                <a href="/index.php?id=<?php echo $rowStore['id']; ?>"><?php echo $rowStore['name']; ?></a>
                            </p>
                            <span><b><?php echo $rowNotification[$j]['message']; ?></b></span>
                            <br><br>
                            <?php if($rowNotification[$j]['read'] == 0) { ?>
                            <span><a href="index.php?pg=notificacoes&lida=<?php echo $rowNotification[$j]['id']; ?>" style="color:white" class='waves-effect waves-light btn blue modal-trigger'>Marcar como lida</a></span>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <?php } } else { ?>
                <div class="card-data" style="text-align:center;margin:0;"><p>Sem notificações.</p></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>