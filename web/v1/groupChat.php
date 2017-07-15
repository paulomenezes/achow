<?php
    $chat = $_GET['id'];

    $sqlMessages = "SELECT cm.*, a.name, a.lastname, a.image, a.facebookID FROM chat_messenger AS cm INNER JOIN account AS a ON a.id = cm.iduser WHERE cm.idroom = '" . $chat	 . "' ORDER BY cm.id ASC";
    $rowMessages  = $conn->query($sqlMessages)->fetchAll();

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
			<div class="col s10" style="width:100%;">
				<span class="black-text">
					Sala <?php echo $chat; ?>
				</span>
				<div style="float:right">
					<a class="waves-effect waves-light btn blue" href="index.php?pg=chat&id=<?php echo $_GET['id']; ?>">	Atualizar
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="chat" style="height:100%;overflow:auto">
		<ul>
			<?php for ($i=0; $i < sizeof($rowMessages); $i++) { 
				$time = date_parse_from_format('Y-m-d H:i:s', $rowMessages[$i]['date']);
				$time = $time["year"] . "-" . $time["month"] . "-" . $time["day"] . " " . $time["hour"] . ":" . $time["minute"] . ":" . $time['second'];
			?>
			<li>
				<?php if($rowMessages[$i]['iduser'] != $user['id']) { ?>
				<div style="float:left; margin-right: 10px; margin-top: 6px;">
					<a href="index.php?pg=perfil&id=<?php echo $rowMessages[$i]['iduser']; ?>">
						<?php if($rowMessages[$i]['image'] != null) { ?>
		                <img src="<?php echo $rowMessages[$i]['image']; ?>"  alt="" class="circle responsive-img" style="height:40px" />
		                <?php } else { ?>
		                <img src="images/logo-sq.png"  alt="" class="circle responsive-img" style="height:40px" />
		                <?php } ?>
	                </a>
				</div>
				<?php } ?>
				<div class="time" <?php echo $rowMessages[$i]['iduser'] == $user['id'] ? 'style="text-align: right"' : ''; ?>>Há <?php echo humanTiming(strtotime($time)); ?></div>
				<div class="message <?php echo $rowMessages[$i]['iduser'] == $user['id'] ? 'send' : 'receiver'; ?>" <?php echo $rowMessages[$i]['iduser'] == $user['id'] ? 'style="float:right"' : ''; ?>>
					<?php echo $rowMessages[$i]['text']; ?>
				</div>
				<div class="clear"></div>
			</li>
			<?php } ?>
		</ul>
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
	$(".chat")[0].scrollTop = $(".chat")[0].scrollHeight;

	$("#message").keypress(function (event) {
		if (event.which == 13) {
			var typed = $("#message").val();
			$("#message").val("");

			$.ajax({
				type: "POST",
				url: "addchatgroup.php",
				data: { text: typed, idroom: <?php echo $chat; ?>, iduser: <?php echo $user['id']; ?> }
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