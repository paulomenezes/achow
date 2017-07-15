<?php
    if ($_POST) {
        if ($_POST['images'] == "images") {
            $uploaddir = 'images/user/';

            if ($_FILES['profile']['size'] > 0) {
                $uploadfile = $uploaddir . 'Axaqui_profile_' . date('m-d-Y'). '_' . basename($_FILES['profile']['name']);

                include('SimpleImage.php');
                $image = new SimpleImage();
                $image->load($_FILES['profile']['tmp_name']);
                $image->resizeToWidth(200);
                $image->save($uploadfile);

                $sql_insert = "UPDATE account SET image = ? WHERE id = ?";

                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $uploadfile);
                $stmt->bindValue(2, $user['id']);
                $stmt->execute();

                //header("location:index.php?pg=configuracoes");

                // if (move_uploaded_file($_FILES['profile']['tmp_name'], $uploadfile)) {
                //     echo "Arquivo válido e enviado com sucesso.\n";
                // } else {
                //     echo "Possível ataque de upload de arquivo!\n";
                // }
            }

            // if ($_FILES['cover']['size'] > 0) {
            //     $uploadfile = $uploaddir . 'Axaqui_cover_' . date('m-d-Y'). '_' . basename($_FILES['cover']['name']);

            //     $sql_insert = "UPDATE [axaqui].[Account] SET
            //                    [cover] = ? WHERE id = ?";

            //     $stmt = $conn->prepare($sql_insert);
            //     $stmt->bindValue(1, $uploadfile);
            //     $stmt->bindValue(2, $user['id']);
            //     $stmt->execute();

            //     if (move_uploaded_file($_FILES['cover']['tmp_name'], $uploadfile)) {
            //         echo "Arquivo válido e enviado com sucesso.\n";
            //     } else {
            //         echo "Possível ataque de upload de arquivo!\n";
            //     }
            // }
        } else {
            $name = $_POST['name'];
            $lastname = $_POST['last_name'];
            $birth = $_POST['birth'];
            $gender = $_POST['gender'];
            $password = $_POST['password'];
            $phone = $_POST['phone'];
            $ocupation = $_POST['ocupation'];

            if($user['facebookID'] == null) {
                $sql_insert = "UPDATE account SET
                           name = ?, lastname = ?, birth = ?, gender = ?,
                           password = ?, phone = ?, ocupation = ? WHERE id = ?";
            } else {
                $sql_insert = "UPDATE account SET
                           name = ?, lastname = ?, birth = ?, gender = ?,
                           phone = ?, ocupation = ? WHERE id = ?";
            }

            $stmt = $conn->prepare($sql_insert);
            $stmt->bindValue(1, $name);
            $stmt->bindValue(2, $lastname);
            $stmt->bindValue(3, $birth);
            $stmt->bindValue(4, $gender);
            if($user['facebookID'] == null) {
                $stmt->bindValue(5, $password);
                $stmt->bindValue(6, $phone);
                $stmt->bindValue(7, $ocupation);
                $stmt->bindValue(8, $user['id']);
            } else {
                $stmt->bindValue(5, $phone);
                $stmt->bindValue(6, $ocupation);
                $stmt->bindValue(7, $user['id']);
            }
            $stmt->execute();

            header("location:index.php?pg=configuracoes");
        }
    }
?>
<script type="text/javascript" src="adm/plugins/jquerymask/jquery.maskedinput.min.js"></script> 
<script type="text/javascript">
    jQuery(document).ready(function() {
        $('.birth').mask('99/99/9999');
        $('.phone').mask('(99) 9999-9999?9');
    });
</script>

<nav class="nav2">
    <div class="nav-wrapper">
        <a href="#!" class="brand-logo"><img src="images/logo.png" /></a>
    </div>

    <ul class="tabs">
        <li class="tab"><a class="active" href="#perfil">ALTERAR PERFIL</a></li>
        <li class="tab" style="line-height: 30px;"><a class="active" href="#fotos">ALTERAR FOTO</a></li>
    </ul>
</nav>

<div>
    <div id="perfil" class="col s12 content-in">
        <div class="row tab-area">
            <div class="col l12 s12">
                <div class="card">
                    <div class="card-data" style="margin:0">
                        <br>
                        <form method="post">
                            <div class="input-field col s6">
                                <input name="name" type="text" class="validate" required value="<?php echo $user['name']; ?>">
                                <label for="name">Nome</label>
                            </div>
                            <div class="input-field col s6">
                                <input name="last_name" type="text" class="validate" required value="<?php echo $user['lastname']; ?>">
                                <label for="last_name">Sobrenome</label>
                            </div>
                            <div class="input-field col s6">
                                <input name="birth" type="text" class="validate birth" required value="<?php echo $user['birth']; ?>">
                                <label for="birth">Nascimento</label>
                            </div>
                            <div class="input-field col s6">
                                <select name="gender">
                                    <option <?php echo $user['gender'] == "Masculino" ? "selected" : ""; ?> value="Masculino">Masculino</option>
                                    <option <?php echo $user['gender'] == "Feminino" ? "selected" : ""; ?>  value="Feminino">Feminino</option>
                                    <option <?php echo $user['gender'] == "" ? "selected" : ""; ?>  value="">Prefiro não dizer</option>
                                </select>
                            </div>
                            <?php if($user['facebookID'] == null) { ?>
                            <div class="input-field col s12">
                                <div class="col s6">
                                    <input name="password" type="password" class="validate">
                                    <label for="password">Senha</label>
                                </div>
                                <div class="col s6">
                                    <p>Deixe a senha em branco se não quiser altera-lá</p>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="input-field col s6">
                                <input name="phone" type="text" class="validate phone" required value="<?php echo $user['phone']; ?>">
                                <label for="phone">Telefone</label>
                            </div>
                            <div class="input-field col s6">
                                <input name="ocupation" type="text" class="validate" required value="<?php echo $user['ocupation']; ?>">
                                <label for="ocupation">Ocupação</label>
                            </div>
                            <div class="input-field col s12">
                                <button type="submit" class="btn blue login-entrar-btn">ATUALIZAR</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="fotos" class="col s12 content-in">
        <div class="row tab-area">
            <div class="col l12 s12">
                <div class="card">
                    <div class="card-data" style="margin:0">
                        <form method="post" enctype="multipart/form-data">
                            <div class="file-field input-field col s12">
                                <input class="file-path validate" type="text" style="margin-left:260px;width:calc(100%-260px)"/>
                                <div class="btn blue">
                                    <span>ALTERAR FOTO DE PERFIL</span>
                                    <input name="profile" type="file" />
                                </div>
                            </div>
                            <!-- <div class="file-field input-field col s6">
                                <input class="file-path validate" type="text" style="margin-left:200px;width:calc(100%-200px)"/>
                                <div class="btn blue">
                                    <span>ALTERAR COVER</span>
                                    <input name="cover" type="file" />
                                </div>
                            </div> -->
                            <input type="hidden" name="images" value="images" />
                            <div class="input-field col s12">
                                <button type="submit" class="btn blue login-entrar-btn">ENVIAR</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('select').material_select();
    });
</script>