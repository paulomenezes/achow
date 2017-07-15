<nav class="nav3">
    <div class="nav-wrapper">
        <a href="#!" class="brand-logo"><img src="images/logo.png" /></a>
    </div>
</nav>

<?php 
    if ($_GET['st'] != "") {
        $sql = "SELECT * FROM store_sub_type WHERE id = " . $_GET['st'];
        $row = $conn->query($sql)->fetchAll();

        $sql2 = "SELECT * FROM store_type WHERE id = " . $row[0]['Store_Type_id'];
        $row2 = $conn->query($sql2)->fetchAll();
    } else {
        $sql2 = "SELECT * FROM store_type WHERE id = " . $_GET['t'];
        $row2 = $conn->query($sql2)->fetchAll();
    }

    $sqlSubType = "SELECT * FROM store_sub_type WHERE Store_Type_id = " . $row2[0]['id'];
    $rowSubType = $conn->query($sqlSubType)->fetchAll();    

    $citys = array();
?>

<div>
    <div id="gostei" class="col s12 content-in">
        <p class="stores-title" style="margin-bottom:0"><?php echo utf8_encode($row2[0]['name']); ?></p>
        <div class="row tab-area">
            <div class="card col l2 s12 collection" style="padding:0">
                <a href="index.php?pg=sub&t=<?php echo $rowSubType[0]['Store_Type_id']; ?>" class="<?php echo $_GET['t'] != '' ? 'active' : '' ?> collection-item">Todas</a>
                <?php for ($j=0; $j < sizeof($rowSubType); $j++) { array_push($citys, $rowSubType[$j]['id']); ?>
                <a href="index.php?pg=sub&st=<?php echo $rowSubType[$j]['id']; ?>" class="<?php echo $rowSubType[$j]['id'] == $_GET['st'] ? 'active' : '' ?> collection-item">
                    <?php echo $rowSubType[$j]['name']; ?>
                </a>
                <?php } ?>
            </div>
            <div class="col l5 s12">
                <?php
                    if($_GET['t'] != "") {
                        $sqlStore = "SELECT * FROM store WHERE city = '" . $_SESSION['city'] . "' and idStoreType = '" . $_GET['t'] . "'"; // IN (" . implode(',', $citys) . ")
                    } else {
                        $sqlStore = "SELECT * FROM store WHERE city = '" . $_SESSION['city'] . "' and subtype LIKE '%, " . $_GET['st'] . ", %'";
                    }
                    $rowGostei = $conn->query($sqlStore)->fetchAll();

                    for ($j = 0; $j < sizeof($rowGostei); $j+=2) {          
                ?>
                <div class="card">
                    <div class="card-image">
                        <a href="index.php?id=<?php echo $rowGostei[$j]['id']; ?>">
                            <?php if($rowGostei[$j]['icon'] != null) { ?>
                            <img src="images/store/<?php echo $rowGostei[$j]['icon']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="card-data">
                        <p><a href="index.php?id=<?php echo $rowGostei[$j]['id']; ?>"><?php echo $rowGostei[$j]['name']; ?></a></p>
                        <span><?php echo $rowGostei[$j]['address'] . ', ' . $rowGostei[$j]['bairro'] . ' ' . $rowGostei[$j]['cidade']; ?></span>
                        <span><br><b><?php echo $rowGostei[$j]['phone']; ?></b></span>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="col l5 s12">
                <?php
                    for ($j = 1; $j < sizeof($rowGostei); $j+=2) {          
                ?>
                <div class="card">
                    <div class="card-image">
                        <a href="index.php?id=<?php echo $rowGostei[$j]['id']; ?>">
                            <?php if($rowGostei[$j]['icon'] != null) { ?>
                            <img src="images/store/<?php echo $rowGostei[$j]['icon']; ?>" />
                            <?php } else { ?>
                            <img src="images/logo-sq.png" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="card-data">
                        <p><a href="index.php?id=<?php echo $rowGostei[$j]['id']; ?>"><?php echo $rowGostei[$j]['name']; ?></a></p>
                        <span><?php echo $rowGostei[$j]['address'] . ', ' . $rowGostei[$j]['bairro'] . ' ' . $rowGostei[$j]['cidade']; ?></span>
                        <span><br><b><?php echo $rowGostei[$j]['phone']; ?></b></span>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>