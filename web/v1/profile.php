<?php
    $sqlStore = "SELECT * FROM account WHERE id = '" . $_GET['id'] . "'";
    $rowStore = $conn->query($sqlStore)->fetchAll();

    $store = $rowStore[0];
?>
<nav class="nav3">
    <div class="nav-wrapper">
        <a href="/" class="brand-logo"><img src="images/logo.png" /></a>
    </div>
</nav>

<div>
    <div id="test1" class="col s12">
        <div class="row tab-area">
            <div class="col s12">
                <div class="card IMAGEM-MENOR">
                    <div class="card-image">
                        <?php if($store['image'] != null) { ?>
                        <img src="<?php echo $store['image']; ?>" />
                        <?php } else { ?>
                        <img src="images/logo-sq.png" />
                        <?php } ?>
                    </div>
                    <div class="card-data">
                        <p>
                            <?php echo $store['name'] . ' ' . $store['lastname'] ?>
                        </p>
                        <span><?php echo $store['ocupation'] ?></span>
                        <br /><br />
                        <span><?php echo $store['phone'] ?></span>
                    </div>
                </div>

                <div class="app-buttons">
                    <?php if($store['id'] != $user['id']) { 
                        if ($_GET['ac'] != "add") {
                            $s = "SELECT * FROM friend WHERE idAccount1 = '" . $_GET['id'] . "' and idAccount2 = '" . $user['id'] . "'";
                            $r = $conn->query($s)->fetchAll();
                            if (sizeof($r) == 0) {
                                $s = "SELECT * FROM friend WHERE idAccount2 = '" . $_GET['id'] . "' and idAccount1 = '" . $user['id'] . "'";
                                $r = $conn->query($s)->fetchAll();
                                if (sizeof($r) == 0) {
                                    $s = "SELECT * FROM friend_request WHERE idAccount1 = '" . $_GET['id'] . "' and idAccount2 = '" . $user['id'] . "'";
                                    $r = $conn->query($s)->fetchAll();
                                    if (sizeof($r) == 0) {
                                        $s = "SELECT * FROM friend_request WHERE idAccount2 = '" . $_GET['id'] . "' and idAccount1 = '" . $user['id'] . "'";
                                        $r = $conn->query($s)->fetchAll();
                                        if (sizeof($r) == 0) {
                    ?>
                    <a href="index.php?pg=perfil&id=<?php echo $_GET['id']; ?>&ac=add" class="col l6 waves-effect waves-light btn blue">
                        <i class="mdi-action-account-box left"></i>Adicionar como amigo
                    </a>
                    <?php } } } else { ?>
                    <a href="index.php?pg=mensagem&id=<?php echo $_GET['id']; ?>" class="col l6 waves-effect waves-light btn blue">
                        Enviar mensagem
                    </a>
                    <?php /**/ } /**/ } else { ?>
                    <a href="index.php?pg=mensagem&id=<?php echo $_GET['id']; ?>" class="col l6 waves-effect waves-light btn blue">
                        Enviar mensagem
                    </a>
                    <?php } } } ?>
                </div>
                
                <div class="row card">
                    <div class="content-in">
                        <p class="stores-title">Histórico - Locais visitados</p>
                        <?php
                            $sqlStore = "SELECT * FROM store AS s INNER JOIN store_checkin AS c ON c.idStore = s.id WHERE c.idAccount = '" . $store['id'] . "' and c.users is null";
                            $rowStore = $conn->query($sqlStore)->fetchAll();
        
                            if (sizeof($rowStore) > 0 ) {
                            for ($j = 0; $j < sizeof($rowStore); $j++) { 
                        ?>
                        <div class="store-item">
                            <div class="image">
                                <a href="index.php?id=<?php echo $rowStore[$j]['id']; ?>">
                                    <?php if($rowStore[$j]['icon'] != null && $rowStore[$j]['vip'] == "sim") { ?>
                                    <img src="images/store/<?php echo $rowStore[$j]['icon']; ?>" />
                                    <?php } else { ?>
                                    <img src="images/logo-sq.png" />
                                    <?php } ?>
                                </a>
                            </div>
                            <div class="data">
                                <p><a href="index.php?id=<?php echo $rowStore[$j]['id']; ?>"><?php echo $rowStore[$j]['name']; ?></a></p>
                                <span><?php echo $rowStore[$j]['address'] . ', ' . $rowStore[$j]['bairro'] . $rowStore[$j]['cidade']; ?></span>
                                <span><br><b><?php echo $rowStore[$j]['phone']; ?></b></span>
                                <span><br>Mensagem: <b><?php echo $rowStore[$j]['message']; ?></b></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <?php } } else { ?>
                            <div class="card-data" style="text-align:center;margin:0;"><p>Nenhum estabelecimento encontrado.</p></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
    if($_GET['ac']) {
        if ($_GET['ac'] == "add") {
            $s = "SELECT * FROM friend WHERE idAccount1 = '" . $_GET['id'] . "' and idAccount2 = '" . $user['id'] . "'";
            $r = $conn->query($s)->fetchAll();
            if (sizeof($r) == 0) {
                $s = "SELECT * FROM friend WHERE idAccount2 = '" . $_GET['id'] . "' and idAccount1 = '" . $user['id'] . "'";
                $r = $conn->query($s)->fetchAll();
                if (sizeof($r) == 0) {
                    $s = "SELECT * FROM friend_request WHERE idAccount1 = '" . $_GET['id'] . "' and idAccount2 = '" . $user['id'] . "'";
                    $r = $conn->query($s)->fetchAll();
                    if (sizeof($r) == 0) {
                        $s = "SELECT * FROM friend_request WHERE idAccount2 = '" . $_GET['id'] . "' and idAccount1 = '" . $user['id'] . "'";
                        $r = $conn->query($s)->fetchAll();
                        if (sizeof($r) == 0) {
                            $sql_insert = "INSERT INTO friend_request (idAccount1, idAccount2) VALUES (?,?)";

                            $stmt = $conn->prepare($sql_insert);
                            $stmt->bindValue(1, $user['id']);
                            $stmt->bindValue(2, $store['id']);
                            $stmt->execute();

                            $result = send_notification(array($store['gcm_regid']), array(
                                    "msg" => "Você tem uma solicitação de amizade.",
                                    "title" => $user['name'] . " " . $user["lastname"],
                                    "action" => "Solicitações",
                                    "image" => $user['image']
                                ));
                        }
                    }
                }
            }
        } 
    }
?>

<script type="text/javascript">
    $(document).ready(function(){

    });
</script>