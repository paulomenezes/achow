<nav class="nav3">
    <div class="nav-wrapper">
        <a href="#!" class="brand-logo"><img src="images/logo.png" /></a>
    </div>
</nav>

<div>
    <div id="gostei" class="col s12 content-in">
        <div class="row tab-area">
            <?php
                $sqlGostei = "SELECT s.* FROM store_visited AS v INNER JOIN store AS s ON v.idStore = s.id WHERE idAccount = '$id' and idVisitedType = 5 ORDER BY id DESC";
                $rowGostei = $conn->query($sqlGostei)->fetchAll();

                for ($j = 0; $j < sizeof($rowGostei); $j++) {          
            ?>
            <div class="col l5 s12">
                <div class="card">
                    <div class="card-image">
                        <a href="index.php?id=<?php echo $rowGostei[$j]['id']; ?>">
                            <?php if($rowGostei[$j]['icon'] != null && $rowGostei[$j]['vip'] == "sim") { ?>
                            <img src="images/store/<?php echo $rowGostei[$j]['icon']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="card-data">
                        <p><a href="index.php?id=<?php echo $rowGostei[$j]['id']; ?>"><?php echo $rowGostei[$j]['name']; ?></a></p>
                        <span><?php echo $rowGostei[$j]['address'] . ', ' . $rowGostei[$j]['bairro'] . ' - ' . $rowGostei[$j]['cidade']; ?></span>
                        <span><br><b><?php echo $rowGostei[$j]['phone']; ?></b></span>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>