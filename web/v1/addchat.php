<?php
	try {
		include('connect.php');

		$sql_insert = "INSERT INTO chat_messenger (idchat, iduser, text) VALUES (?, ?, ?)";

		date_default_timezone_set('America/Sao_Paulo');

	    $stmt = $conn->prepare($sql_insert);
	    $stmt->bindValue(1, $_POST['idchat']);
	    $stmt->bindValue(2, $_POST['iduser']);
	    $stmt->bindValue(3, $_POST['text']);
	    $stmt->execute();

	    $sql = "SELECT * FROM friend WHERE id = '". $_POST['idchat'] ."'";
        $row = $conn->query($sql)->fetchAll();

        if ($row[0]['idAccount1'] == $_POST['iduser']) {
            $sql = "SELECT * FROM account WHERE id = '". $row[0]['idAccount2'] ."'";
        	$row2 = $conn->query($sql)->fetchAll();

        	$sql = "SELECT * FROM account WHERE id = '". $row[0]['idAccount1'] ."'";
        	$row1 = $conn->query($sql)->fetchAll();
        } else {
            $sql = "SELECT * FROM account WHERE id = '". $row[0]['idAccount1'] ."'";
        	$row2 = $conn->query($sql)->fetchAll();

        	$sql = "SELECT * FROM account WHERE id = '". $row[0]['idAccount2'] ."'";
        	$row1 = $conn->query($sql)->fetchAll();
        }

	    if ($row2[0]['gcm_regid']) {
            $result = send_notification(array($row2[0]['gcm_regid']), array(
                                        "msg" => "Você tem uma nova mensagem.",
                                        "title" => $row1[0]['name'] . " " . $row1[0]["lastname"],
                                        "action" => "Chat",
                                        "image" => $row1[0]['image'],
                                        "name" => "accountGet",
                                        "value" => $row2[0]['id'],
                                        "user" => $row1[0]
                                    ));
        }

        $sql_insert = "INSERT INTO store_notification (idUserSend, idUserReceiver, message) VALUES (?,?,?)";

        $stmt = $conn->prepare($sql_insert);
        $stmt->bindValue(1, $_POST['iduser']);
        $stmt->bindValue(2, $row2[0]['id']);
        $stmt->bindValue(3, 'Você tem uma nova mensagem');
        $stmt->execute();

	    echo 0;
	} catch (Exception $e) {
		echo 1;
	}
?>