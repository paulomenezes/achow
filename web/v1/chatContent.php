<?php
	include('connect.php');

	$sqlMessages = "SELECT * FROM chat_messenger WHERE idchat = '" . $_GET['id'] . "' ORDER BY id ASC";
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
<ul>
	<?php for ($i=0; $i < sizeof($rowMessages); $i++) { 
		$time = date_parse_from_format('Y-m-d H:i:s', $rowMessages[$i]['date']);
		$time = $time["year"] . "-" . $time["month"] . "-" . $time["day"] . " " . $time["hour"] . ":" . $time["minute"] . ":" . $time['second'];
	?>
	<li>
		<div class="time" <?php echo $rowMessages[$i]['iduser'] == $_GET['user'] ? 'style="text-align: right"' : ''; ?>>Há <?php echo humanTiming(strtotime($time)); ?></div>
		<div class="message <?php echo $rowMessages[$i]['iduser'] == $_GET['user'] ? 'send' : 'receiver'; ?>" <?php echo $rowMessages[$i]['iduser'] == $_GET['user'] ? 'style="float:right"' : ''; ?>>
			<?php echo $rowMessages[$i]['text']; ?>
		</div>
		<div class="clear"></div>
	</li>
	<?php } ?>
</ul>