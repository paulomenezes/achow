<?php
    $sqlStore = "SELECT * FROM account WHERE id = '" . $_GET['id'] . "'";
    $rowStore = $conn->query($sqlStore)->fetchAll();

    $store = $rowStore[0];

    $sqlChat = "SELECT * FROM friend WHERE 
    			(idAccount1 = '" . $_GET['id'] . "' and idAccount2 = '" . $user['id'] . "') or 
    			(idAccount2 = '" . $_GET['id'] . "' and idAccount1 = '" . $user['id'] . "')";
    $rowChat = $conn->query($sqlChat)->fetchAll();

    $chat = $rowChat[0];

    $sqlMessages = "SELECT * FROM chat_messenger WHERE idchat = '" . $chat['id'] . "' ORDER BY id ASC";
    $rowMessages  = $conn->query($sqlMessages)->fetchAll();

    $sql_insert = "DELETE FROM store_notification WHERE iduserreceiver = ? and idStore is null and idShows is null";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bindValue(1, intval($user['id']));
    $stmt->execute();

    date_default_timezone_set('America/Sao_Paulo');
    function humanTiming ($time) {
	    $time = time() - $time; // to get the time since that moment

	    $tokens = array (
	        31536000 => 'ano',
	        2592000 => 'mes',
	        604800 => 'semana',
	        86400 => 'dia',
	        3600 => 'hora',
	        60 => 'minuto',
	        1 => 'segundo'
	    );

	    $dias = array(
	    			'ano' => array('ano', 'anos'),
	    			'mes' => array('mês', 'meses'),
	    			'semana' => array('semana', 'semanas'),
	    			'dia' => array('dia', 'dias'),
	    			'hora' => array('hora', 'horas'),
	    			'minuto' => array('minuto', 'minutos'),
	    			'segundo' => array('segundo', 'segundos'));

	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);

	        return $numberOfUnits . ' ' . (($numberOfUnits > 1) ? $dias[$text][1] : $dias[$text][0]);
	    }

	}
?>

<style type="text/css">
	.chat ul {
		margin: 10px;
	}

	.chat ul li .time {
		font-size: 11px;
	}

	.chat ul li .message {
		border-radius: 5px;
		padding: 5px 10px;

		color: white;

		margin-bottom: 10px;

		float: left;
	}

	.chat ul li .send {
		background-color: #03a9f4;
		color: white;
	}

	.chat ul li .receiver {
		background-color: #dfdfdf;
		color: black;
	}

	.clear { clear: both; }
</style>

<nav class="nav3">
    <div class="nav-wrapper">
        <a href="/" class="brand-logo"><img src="images/logo.png" /></a>
    </div>
</nav>

<div class="col s12" style="height: calc(100% - 395px)">
	<div class="card-panel grey lighten-5 z-depth-1">
		<div class="valign-wrapper">
			<div class="col s2" style="margin-right:20px">
				<a href="index.php?pg=perfil&id=<?php echo $_GET['id'] ?>">
					<?php if($store['image'] != null) { ?>
	                <img src="<?php echo $store['image']; ?>"  alt="" class="circle responsive-img" style="height:50px" />
	                <?php } else { ?>
	                <img src="images/logo-sq.png"  alt="" class="circle responsive-img" style="height:50px" />
	                <?php } ?>
                </a>
			</div>
			<div class="col s10" style="width:100%;">
				<span class="black-text">
					<a href="index.php?pg=perfil&id=<?php echo $_GET['id'] ?>">
						<?php echo $store['name'] . ' ' . $store['lastname'] ?>
					</a>
				</span>
				<div style="float:right">
					<a class="waves-effect waves-light btn blue" href="index.php?pg=mensagem&id=<?php echo $_GET['id']; ?>">Atualizar</a>
				</div>
			</div>
		</div>
	</div>

	<div class="chat" style="height:100%;overflow:auto">
		
	</div>

	<div class="typeArea" style="z-index:-1">
		<div class="card-panel grey lighten-5 z-depth-1">
			<div class="row">
				<div class="input-field col s12">
					<input id="message" type="text" placeholder="Digite sua mensagem e pressione enter">
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
	    setInterval("load()",1000);
	});

	function load () {
		$(".chat").load("chatContent.php?id=<?php echo $chat['id'] ?>&user=<?php echo $user['id'] ?>");
		$(".chat")[0].scrollTop = $(".chat")[0].scrollHeight;
	}

	$("#message").keypress(function (event) {
		if (event.which == 13) {
			var typed = $("#message").val();
			$("#message").val("");

			$.ajax({
				type: "POST",
				url: "addchat.php",
				data: { text: typed, idchat: <?php echo $chat['id']; ?>, iduser: <?php echo $user['id']; ?> }
			}).done(function (data) {
				$(".chat ul").append('<li>' + 
										'<div class="time" style="text-align:right">Agora</div>' +
										'<div class="message send" style="float:right">' + typed + '</div>' +
										'<div class="clear"></div>' +
									 '</li>');
				$(".chat")[0].scrollTop = $(".chat")[0].scrollHeight;
			});
		}
	});
</script>