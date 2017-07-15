<?php
	try {
		include('connect.php');

		$sql_insert = "INSERT INTO chat_messenger (idroom, iduser, text) VALUES (?, ?, ?)";

		date_default_timezone_set('America/Sao_Paulo');

	    $stmt = $conn->prepare($sql_insert);
	    $stmt->bindValue(1, $_POST['idroom']);
	    $stmt->bindValue(2, $_POST['iduser']);
	    $stmt->bindValue(3, $_POST['text']);
	    $stmt->execute();

	    echo 0;
	} catch (Exception $e) {
		echo 1;
	}
?>