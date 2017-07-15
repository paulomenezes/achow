<?php
    if ($_GET['ac'] == "acc") {
        $sqlFriends = "SELECT * FROM friend_request WHERE id = '" . $_GET['id'] . "'";
        $rowFriends = $conn->query($sqlFriends)->fetchAll();
        $rf = $rowFriends[0];

        $sql_insert = "INSERT INTO friend (idAccount1, idAccount2) VALUES (?,?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bindValue(1, $rf['idAccount1']);
        $stmt->bindValue(2, $rf['idAccount2']);
        $stmt->execute();

        $s = "DELETE FROM friend_request WHERE id = ?";
        $stmt = $conn->prepare($s);
        $stmt->bindValue(1, $_GET['id']);
        $stmt->execute();

        redirect("index.php?pg=contatos");
    } else if ($_GET['ac'] == "rec") {
        $s = "DELETE FROM friend_request WHERE id = ?";
        $stmt = $conn->prepare($s);
        $stmt->bindValue(1, $_GET['id']);
        $stmt->execute();

        redirect("index.php?pg=contatos");
    }
?>
<nav class="nav2">
    <div class="nav-wrapper">
        <a href="#!" class="brand-logo"><img src="images/logo.png" /></a>
    </div>

    <ul class="tabs">
        <li class="tab"><a class="active" href="#amigos">AMIGOS</a></li>
        <li class="tab"><a class="active" href="#solicitacoes">SOLICITAÇÕES</a></li>
    </ul>
</nav>

<div>
    <div id="amigos" class="col s12 content-in">
        <div class="row tab-area">
            <?php
                $sqlFriends = "SELECT * FROM friend WHERE idAccount1 = '" . $user['id'] . "' OR idAccount2 = '" . $user['id'] . "'";
                $rowFriends = $conn->query($sqlFriends)->fetchAll();

                if (sizeof($rowFriends) > 0 ) {

                for ($j = 0; $j < sizeof($rowFriends); $j++) { 

                if ($user['id'] == $rowFriends[$j]['idAccount2']) {
                    $sqlUser = "SELECT * FROM account WHERE id = '" . $rowFriends[$j]['idAccount1'] . "'";
                    $rowUser = $conn->query($sqlUser)->fetchAll();        
                } else {
                    $sqlUser = "SELECT * FROM account WHERE id = '" . $rowFriends[$j]['idAccount2'] . "'";
                    $rowUser = $conn->query($sqlUser)->fetchAll();        
                }

                $friend = $rowUser[0];                
            ?>
            <div class="col l5 s12">
                <div class="card">
                    <div class="card-image">
                        <a href="index.php?pg=perfil&id=<?php echo $friend['id']; ?>">
                            <?php if($friend['image'] != null) { ?>
                            <img src="<?php echo $friend['image']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="card-data">
                        <p><a href="index.php?pg=perfil&id=<?php echo $friend['id']; ?>"><?php echo $friend['name'] . ' ' . $friend['lastname']; ?></a></p>
                        <span><?php echo $friend['ocupation']; ?></span>
                        <span><br><b><?php echo $friend['phone']; ?></b></span>
                    </div>
                </div>
            </div>
            <?php } } else { ?>
            <div class="col l12 s12">
                <div class="card">
                    <div class="card-data" style="text-align:center;margin:0;"><p>Você ainda não adicionou nenhum amigo.</p></div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <div id="solicitacoes" class="col s12 content-in">
        <div class="row tab-area">
            <?php
                $sqlFriends = "SELECT * FROM friend_request WHERE idAccount2 = '" . $user['id'] . "'";
                $rowFriends = $conn->query($sqlFriends)->fetchAll();

                if (sizeof($rowFriends) > 0 ) {

                for ($j = 0; $j < sizeof($rowFriends); $j++) { 

                if ($user['id'] == $rowFriends[$j]['idAccount2']) {
                    $sqlUser = "SELECT * FROM account WHERE id = '" . $rowFriends[$j]['idAccount1'] . "'";
                    $rowUser = $conn->query($sqlUser)->fetchAll();        
                } else {
                    $sqlUser = "SELECT * FROM account WHERE id = '" . $rowFriends[$j]['idAccount2'] . "'";
                    $rowUser = $conn->query($sqlUser)->fetchAll();        
                }

                $friend = $rowUser[0];                
            ?>
            <div class="col l5 s12">
                <div class="card">
                    <div class="card-image">
                        <a href="index.php?pg=perfil&id=<?php echo $friend['id']; ?>">
                            <?php if($friend['image'] != null) { ?>
                            <img src="<?php echo $friend['image']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="card-data">
                        <p><a href="app.html"><?php echo $friend['name'] . ' ' . $friend['lastname']; ?></a></p>
                        <span><?php echo $friend['ocupation']; ?></span>
                        <span><br><b><?php echo $friend['phone']; ?></b></span>
                    </div>
                    <div class="card-action clear">
                        <a href="index.php?pg=contatos&ac=acc&id=<?php echo $rowFriends[$j]['id']; ?>">
                            Aceitar
                        </a>
                        <a href="index.php?pg=contatos&ac=rec&id=<?php echo $rowFriends[$j]['id']; ?>">
                            Recusar
                        </a>
                    </div>
                </div>
            </div>
            <?php } } else { ?>
            <div class="col l12 s12">
                <div class="card">
                    <div class="card-data" style="text-align:center;margin:0;"><p>Você não tem nenhuma solicitação de amizade.</p></div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>