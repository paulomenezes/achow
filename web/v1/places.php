<nav class="nav2">
    <div class="nav-wrapper">
        <a href="#!" class="brand-logo"><img src="images/logo.png" /></a>
    </div>

    <ul class="tabs">
        <li class="tab"><a class="active" href="#gostei">GOSTEI</a></li>
    </ul>
</nav>

<div>
    <div id="gostei" class="col s12 content-in">
        <div class="row tab-area">
            <?php
                $sqlGostei = "SELECT count(sv.idStore) AS avaliarGostei, s.* FROM store AS s LEFT JOIN store_visited AS sv ON s.id = sv.idStore and sv.idVisitedType = 1 GROUP BY s.id";
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

    <!-- <div id="naogostei" class="col s12 content-in">
        <div class="row tab-area">
            <?php
                $sqlNaoGostei = "SELECT * FROM [axaqui].[Store] ORDER BY avaliarNaoGostei DESC";
                $rowNaoGostei = $conn->query($sqlNaoGostei)->fetchAll();

                for ($j = 0; $j < sizeof($rowNaoGostei); $j++) {          
            ?>
            <div class="col l5 s12">
                <div class="card">
                    <div class="card-image">
                        <a href="index.php?id=<?php echo $rowNaoGostei[$j]['id']; ?>">
                            <?php if($rowNaoGostei[$j]['icon'] != null && $rowNaoGostei[$j]['vip'] == "sim") { ?>
                            <img src="images/store/<?php echo $rowNaoGostei[$j]['icon']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="card-data">
                        <p><a href="index.php?id=<?php echo $rowVouVoltar[$j]['id']; ?>"><?php echo $rowNaoGostei[$j]['name'] . ' ' . $rowNaoGostei[$j]['lastname']; ?></a></p>
                        <span><?php echo $rowNaoGostei[$j]['address'] . ', ' . $rowNaoGostei[$j]['bairro'] . ' - ' . $rowNaoGostei[$j]['cidade']; ?></span>
                        <span><br><b><?php echo $rowNaoGostei[$j]['phone']; ?></b></span>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div> -->
</div>