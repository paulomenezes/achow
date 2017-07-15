<?php
    if ($_GET['ac'] == "acc") {

    } else if ($_GET['ac'] == "rec") {
        $s = "DELETE friend_request WHERE id = '" . $_GET['id'] . "'";
        $r = $conn->query($s)->fetchAll();
    }

    header("Location: index.php?pg=contatos")
?>