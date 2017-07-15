<?php
	session_start();
	if($_SESSION['store'] == '')
	{
		header("Location:index.php");
	}

	$store = $_SESSION['store'];

	require("../connect.php");

	$extra = array("Reservas", "Cartões de crédito", "Mesas ao ar livre", "Área privada",
				   "Música", "WiFi", "Estacionamento", "Chapelaria", "Banheiro", 
				   "Acessível para Cadeirantes", "TVs", "Caixa eletrônico", "Permitido fumar",
				   "Serviço de mesa", "Serviço de bar", "Para viagem", "Entrega", "Drive-thru",
				   "Rodízio");

	try 
	{   

		$sqlVisited = "SELECT * FROM store WHERE id = '".$store['id']."'";
		$stmt = $conn->query($sqlVisited);
		$estabelecimento = $stmt->fetchAll()[0]; 

		$sqlMenu = "SELECT * FROM store_type";
        $rowMenu = $conn->query($sqlMenu)->fetchAll();

		$sqlCity = "SELECT * FROM store_city";
        $rowCity = $conn->query($sqlCity)->fetchAll();

		if($_POST)
		{
			$sql_insert = "DELETE FROM store_extra WHERE idStore = ?";

		    $stmt = $conn->prepare($sql_insert);
		    $stmt->bindValue(1, $store['id']);
		    $stmt->execute();

			for ($i=0; $i < sizeof($_POST['extra']); $i++) { 
				$sql_insert = "INSERT INTO store_extra
								           (idStore, name) VALUES (?,?)";

			 
			    $stmt = $conn->prepare($sql_insert);
			    $stmt->bindValue(1, $store['id']);
			    $stmt->bindValue(2, $_POST['extra'][$i]);
			    $stmt->execute();
			}

			$sqlSub = "SELECT * FROM store_sub_type WHERE id = " . $_POST['estabelecimento'][0];
			$rowSub = $conn->query($sqlSub)->fetchAll();

			$subtypies = ", ";
			
			foreach ($_POST['estabelecimento'] as $selectedOption) {
				$subtypies .= $selectedOption . ", ";
			}

			$sql_insert = "UPDATE store SET
							           subtype = ?, idStoreType = ?, name = ?, responsible = ?,
							           phone1 = ?, phone2 = ?, phone3 = ?, address = ?, cnpj = ?, 
							           site = ?, twitter = ?, facebook = ?, instagram = ?, 
							           vip = ?, city = ?, description = ?, lat = ?, longitude = ?, email = ?, password = ? 
							           WHERE id = ?";

		    $stmt = $conn->prepare($sql_insert);
		    $stmt->bindValue(1, $subtypies);
		    $stmt->bindValue(2, $rowSub[0]['Store_Type_id']);
		    $stmt->bindValue(3, $_POST['nome']);
		    $stmt->bindValue(4, $_POST['responsavel']);
		    $stmt->bindValue(5, $_POST['telefone1']);
		    $stmt->bindValue(6, $_POST['telefone2']);
		    $stmt->bindValue(7, $_POST['telefone3']);
		    $stmt->bindValue(8, $_POST['endereco']);
		    $stmt->bindValue(9, $_POST['cnpj']);
		    $stmt->bindValue(10, $_POST['site']);
		    $stmt->bindValue(11, $_POST['twitter']);
		    $stmt->bindValue(12, $_POST['facebook']);
		    $stmt->bindValue(13, $_POST['instagram']);
		    $stmt->bindValue(14, $_POST['vip']);
		    $stmt->bindValue(15, $_POST['city']);
		    $stmt->bindValue(16, $_POST['descricao']);
		    $stmt->bindValue(17, $_POST['lat']);
		    $stmt->bindValue(18, $_POST['longitude']);
		    $stmt->bindValue(19, $_POST['email']);
		    $stmt->bindValue(20, md5($_POST['password']));
		    $stmt->bindValue(21, $store['id']);
		    $stmt->execute();

        	header("Location:info.php?msg=sucesso");
		}
	}
	catch ( PDOException $e ) 
	{ 
		print("Houve um error, tente novamente ou volte mais tarde.");
		die(print_r($e));
	}
?>

<!DOCTYPE html>
<html class="html">
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
		<title>Achow - Administração</title>

		<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
		<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css"/>
		<link rel="stylesheet" type="text/css" href="css/template.css" />

		<link rel="stylesheet" type="text/css" href="plugins/timepicker/bootstrap-timepicker.min.css">
		<link rel="stylesheet" type="text/css" href="plugins/datepicker/datepicker.css">
		<link rel="stylesheet" type="text/css" href="plugins/daterange/daterangepicker.css">
		<link rel="stylesheet" type="text/css" href="plugins/formswitch/css/bootstrap-switch.css">
		<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script> 
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/highcharts.js"></script>
		<script type="text/javascript" src="js/modules/exporting.js"></script>
		<script type="text/javascript" src="plugins/timepicker/bootstrap-timepicker.min.js"></script> 
		<script type="text/javascript" src="plugins/jquerymask/jquery.maskedinput.min.js"></script> 
		<script type="text/javascript" src="plugins/formswitch/js/bootstrap-switch.js"></script> 
		<script type="text/javascript" src="plugins/select2/js/select2.min.js"></script> 

		<script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
		<script src="plugins/locationpicker/dist/locationpicker.jquery.min.js"></script>

		<link href="plugins/select2/css/select2.min.css" rel="stylesheet" />

		<script type="text/javascript">
			jQuery(document).ready(function() {
				$('.select2').select2();

				$('.timepicker').timepicker();

				$('.phone').mask('(99) 9999-9999?9');
				$('.cnpj').mask('99.999.999/9999-99');
				$('.cep').mask('99999-999');

				$("select[name=estabelecimento]").change(function() {
					$("select[name=estabelecimento] option:selected").each(function () {
						if($(this).attr('value') == 4) {
							$("select[name=plano] option:eq(0)").html('45 Dias Grátis');
							/*$("select[name=plano] option:eq(1)").html('60 Dias - R$ 0,00');
							$("select[name=plano] option:eq(2)").html('90 Dias - R$ 0,00');
							$("select[name=plano] option:eq(3)").html('180 Dias - R$ 0,00');

							if($("select[name=plano] option:eq(4)").text() == "")
								$("select[name=plano]").append('<option>360 Dias - R$ 0,00</option>');
							else
								$("select[name=plano] option:eq(4)").html('360 Dias - R$ 0,00');*/

							$("#lanchonete").hide();
							$("input[name=responsavel]").attr('placeholder', 'Nome Responsável');
						} else if($(this).attr('value') == 8) {
							$("select[name=plano] option:eq(0)").html('15 Dias Antes - R$ 0,00');
							/*$("select[name=plano] option:eq(1)").html('30 Dias Antes - R$ 0,00');
							$("select[name=plano] option:eq(2)").html('60 Dias Antes - R$ 0,00');
							$("select[name=plano] option:eq(3)").html('90 Dias Antes - R$ 0,00');
							$("select[name=plano] option:eq(4)").remove();*/

							$("input[name=responsavel]").attr('placeholder', 'Nome do Evento/Show')
						} else {
							$("select[name=plano] option:eq(0)").html('45 Dias Grátis');
							/*$("select[name=plano] option:eq(1)").html('60 Dias - R$ 0,00');
							$("select[name=plano] option:eq(2)").html('90 Dias - R$ 0,00');
							$("select[name=plano] option:eq(3)").html('180 Dias - R$ 0,00');

							if($("select[name=plano] option:eq(4)").text() == "")
								$("select[name=plano]").append('<option>360 Dias - R$ 0,00</option>');
							else
								$("select[name=plano] option:eq(4)").html('360 Dias - R$ 0,00');*/

							$("#lanchonete").show();
							$("input[name=responsavel]").attr('placeholder', 'Nome Responsável');
						}
					});
				});
			});
		</script>
	</head>

	<body>

		<div class="banner">
			<div class="logo"></div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<?php include('menu.php'); ?>

				<div class="col-sm-12 main">
					<h1 class="page-header">Editar informações</h1>
					<div class="row">
						<form role="form" method="post" enctype="multipart/form-data">
						<?php 
							if($_GET) { 
								if($_GET['msg'] == 'sucesso') { ?>
							<div style="clear:both" class="alert alert-success">Estabelecimento atualizado com suscesso</div>
						<?php } else if($_GET['msg'] == 'senha') { ?>
							<div style="clear:both" class="alert alert-danger">Senhas incorretas</div>
						<?php } } ?>
						<div class="col-sm-6">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>ESTABELECIMENTO VIP</label>
				                        <div class="make-switch" data-on="sim" data-off="nao">
				                        	<input type="checkbox" value="sim" name="vip" <?php echo $estabelecimento['vip'] == 'sim' ? 'checked' : ''; ?>>
				                        </div>
									</div>
									<div class="col-sm-6">
										<label>Tipo do Estabelecimento</label>
										<select class="form-control select2" name="estabelecimento[]" multiple="multiple">
											<?php for($i = 0; $i < sizeof($rowMenu); $i++) { ?>
											<optgroup label="<?php echo utf8_encode($rowMenu[$i]['name']); ?>">
												<?php 
													$sqlSub = "SELECT * FROM store_sub_type WHERE Store_Type_id = " . $rowMenu[$i]['id'];
													$rowSub = $conn->query($sqlSub)->fetchAll();
													for ($j=0; $j < sizeof($rowSub); $j++) { ?>
														<option value="<?php echo $rowSub[$j]['id']; ?>" <?php echo strpos($estabelecimento['subtype'], ", " . $rowSub[$j]['id'] . ", ") !== false ? 'selected' : ''; ?>> 
															<?php echo $rowSub[$j]['name']; ?>
														</option>
													<?php }
												?>
											</optgroup>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>Cidade</label>
										<select class="form-control" name="city">
											<?php for($i = 0; $i < sizeof($rowCity); $i++) { ?>
											<option value="<?php echo $rowCity[$i]['name'] ?>"  <?php echo $estabelecimento['city'] == $rowCity[$i]['id'] ? 'selected' : ''; ?>><?php echo $rowCity[$i]['name']; ?></option>
											<?php } ?>
										</select>
									</div>
									<div class="col-sm-6">
										<label>Nome da empresa</label>
										<input type="text" class="form-control" name="nome" required value="<?php echo $estabelecimento['name']; ?>" >
									</div>
								</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>E-mail</label>
										<input type="email" class="form-control" name="email" value="<?php echo $estabelecimento['email']; ?>" >
									</div>
									<div class="col-sm-6">
										<label>Senha</label>
										<input type="password" class="form-control" name="password" >
									</div>
								</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>Nome do responsável</label>
										<input type="text" class="form-control" name="responsavel" required value="<?php echo $estabelecimento['responsible']; ?>" >
									</div>
									<div class="col-sm-6">
										<label>Telefone 1</label>
										<input type="text" class="form-control phone" name="telefone1" value="<?php echo $estabelecimento['phone1']; ?>" >
									</div>
								</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="row">
									<div class="col-sm-6">
										<label>Telefone 2</label>
										<input type="text" class="form-control phone" name="telefone2" value="<?php echo $estabelecimento['phone2']; ?>" >
									</div>
									<div class="col-sm-6">
										<label>Celular</label>
										<input type="text" class="form-control phone" name="telefone3" value="<?php echo $estabelecimento['phone3']; ?>" >
									</div>
								</div>
							</div>
							<hr />
							<div id="us5" style="width: 485x; height: 400px;"></div>
							<hr />
							<div class="form-group row">
								<div class="col-sm-12">
									<label>Endereço</label>
									<input type="text" class="form-control" id="endereco" name="endereco" required value="<?php echo $estabelecimento['address']; ?>" >
								</div>
							</div>
							<!--<div class="form-group row">
								<div class="col-sm-6">
									<label>Bairro</label>
									<input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo $estabelecimento['bairro']; ?>" >
								</div>
								<div class="col-sm-6">
									<label>Cidade</label>
									<input type="text" class="form-control" id="cidade" name="cidade" required value="<?php echo $estabelecimento['cidade']; ?>" >
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-6">
									<label>Estado</label>
									<select name="estado" class="form-control" id="estado" required>
										<option value="">Selecione</option>
										<option value="AC" <?php echo $estabelecimento['estado'] == 'AC' ? 'selected' : ''; ?>>AC</option>
										<option value="AL" <?php echo $estabelecimento['estado'] == 'AL' ? 'selected' : ''; ?>>AL</option>
										<option value="AP" <?php echo $estabelecimento['estado'] == 'AP' ? 'selected' : ''; ?>>AP</option>
										<option value="AM" <?php echo $estabelecimento['estado'] == 'AM' ? 'selected' : ''; ?>>AM</option>
										<option value="BA" <?php echo $estabelecimento['estado'] == 'BA' ? 'selected' : ''; ?>>BA</option>
										<option value="CE" <?php echo $estabelecimento['estado'] == 'CE' ? 'selected' : ''; ?>>CE</option>
										<option value="DF" <?php echo $estabelecimento['estado'] == 'DF' ? 'selected' : ''; ?>>DF</option>
										<option value="ES" <?php echo $estabelecimento['estado'] == 'ES' ? 'selected' : ''; ?>>ES</option>
										<option value="GO" <?php echo $estabelecimento['estado'] == 'GO' ? 'selected' : ''; ?>>GO</option>
										<option value="MA" <?php echo $estabelecimento['estado'] == 'MA' ? 'selected' : ''; ?>>MA</option>
										<option value="MT" <?php echo $estabelecimento['estado'] == 'MT' ? 'selected' : ''; ?>>MT</option>
										<option value="MS" <?php echo $estabelecimento['estado'] == 'MS' ? 'selected' : ''; ?>>MS</option>
										<option value="MG" <?php echo $estabelecimento['estado'] == 'MG' ? 'selected' : ''; ?>>MG</option>
										<option value="PA" <?php echo $estabelecimento['estado'] == 'PA' ? 'selected' : ''; ?>>PA</option>
										<option value="PB" <?php echo $estabelecimento['estado'] == 'PB' ? 'selected' : ''; ?>>PB</option>
										<option value="PR" <?php echo $estabelecimento['estado'] == 'PR' ? 'selected' : ''; ?>>PR</option>
										<option value="PE" <?php echo $estabelecimento['estado'] == 'PE' ? 'selected' : ''; ?>>PE</option>
										<option value="PI" <?php echo $estabelecimento['estado'] == 'PI' ? 'selected' : ''; ?>>PI</option>
										<option value="RJ" <?php echo $estabelecimento['estado'] == 'RJ' ? 'selected' : ''; ?>>RJ</option>
										<option value="RN" <?php echo $estabelecimento['estado'] == 'RN' ? 'selected' : ''; ?>>RN</option>
										<option value="RS" <?php echo $estabelecimento['estado'] == 'RS' ? 'selected' : ''; ?>>RS</option>
										<option value="RO" <?php echo $estabelecimento['estado'] == 'RO' ? 'selected' : ''; ?>>RO</option>
										<option value="RR" <?php echo $estabelecimento['estado'] == 'RR' ? 'selected' : ''; ?>>RR</option>
										<option value="SC" <?php echo $estabelecimento['estado'] == 'SC' ? 'selected' : ''; ?>>SC</option>
										<option value="SP" <?php echo $estabelecimento['estado'] == 'SP' ? 'selected' : ''; ?>>SP</option>
										<option value="SE" <?php echo $estabelecimento['estado'] == 'SE' ? 'selected' : ''; ?>>SE</option>
										<option value="TO" <?php echo $estabelecimento['estado'] == 'TO' ? 'selected' : ''; ?>>TO</option>
									</select>
								</div>
								<div class="col-sm-6">
									<label>CEP</label>
									<input type="text" class="form-control" id="cep" name="cep" required value="<?php echo $estabelecimento['cep']; ?>" >
								</div>
							</div>-->
							<div class="form-group row">
								<div class="col-sm-6">
									<label>Latitude</label>
									<input type="text" class="form-control" id="latView" value="<?php echo $estabelecimento['lat']; ?>" disabled>
								</div>
								<div class="col-sm-6">
									<label>Longitude</label>
									<input type="text" class="form-control" id="longitudeView" value="<?php echo $estabelecimento['longitude']; ?>" disabled>
								</div>
							</div>

							<input type="hidden" id="lat" name="lat">
							<input type="hidden" id="longitude" name="longitude">

							<div id="lanchonete">
								<hr />
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<label>CNPJ</label>
											<input type="text" class="form-control cnpj" name="cnpj" value="<?php echo $estabelecimento['cnpj']; ?>" >
										</div>
										<div class="col-sm-6">
											<label>Site</label>
											<input type="text" class="form-control" name="site" value="<?php echo $estabelecimento['site']; ?>" >
										</div>
									</div>
								</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<label>Twitter</label>
										<input type="text" class="form-control" name="twitter" value="<?php echo $estabelecimento['twitter']; ?>" >
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<label>Instagram</label>
										<input type="text" class="form-control" name="instagram" value="<?php echo $estabelecimento['instagram']; ?>" >
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<label>Facebook</label>
										<input type="text" class="form-control" name="facebook" value="<?php echo $estabelecimento['facebook']; ?>" >
									</div>
								</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<label>Descrição</label>
										<textarea class="form-control" name="descricao"><?php echo $estabelecimento['description']; ?></textarea>
									</div>
								</div>
							</div>

						</div>

						<div class="col-sm-6">
							<?php 
								for ($i=0; $i < sizeof($extra); $i++) { 
									$s = "SELECT * FROM store_extra WHERE idStore = '".$store['id']."' and name = '" . $extra[$i] . "'";
									$q = $conn->query($s)->fetchAll();
							?>
							<div class="form-group">
								<div class="row">
									<label class="col-md-4 control-label"><?php echo $extra[$i]; ?></label>
			                    	<div class="col-md-8">
				                        <div class="make-switch" data-on="sim" data-off="nao">
				                        	<input type="checkbox" value="<?php echo $extra[$i]; ?>" name="extra[]" <?php echo (sizeof($q) > 0 ? 'checked' : ''); ?>>
				                        </div>
				                    </div>
								</div>
							</div>
							<hr />
							<?php } ?>
						</div>

						<div class="clear"></div>

						<hr />

						<button type="submit" class="btn btn-default pull-right">Atualizar</button>
					</form>

					</div>
				</div>
			</div>
		</div>

		<script>
            function updateControls(addressComponents, lat, longitude) {
                // $('#endereco').val(addressComponents.addressLine1);
                // $('#cidade').val(addressComponents.city);
                // $('#estado').val(addressComponents.stateOrProvince);
                // $('#cep').val(addressComponents.postalCode);
                // $('#bairro').val("");
                $('#lat').val(lat);
                $('#longitude').val(longitude);

                $('#latView').val(lat);
                $('#longitudeView').val(longitude);
            }
            $('#us5').locationpicker({
                location: {
                	latitude: <?php echo $estabelecimento['lat'] == "" ? "-23.2101375" : $estabelecimento['lat']; ?>, 
                	longitude: <?php echo $estabelecimento['longitude'] == "" ? "-47.524628" : $estabelecimento['longitude']; ?>
                },
                radius: 10,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    var addressComponents = $(this).locationpicker('map').location.addressComponents;
                    updateControls(addressComponents, currentLocation.latitude, currentLocation.longitude);
                },
                oninitialized: function(component) {
                    var addressComponents = $(component).locationpicker('map').location.addressComponents;
                    updateControls(addressComponents, <?php echo $estabelecimento['lat'] == "" ? "-23.2101375" : $estabelecimento['lat']; ?>, <?php echo $estabelecimento['longitude'] == "" ? "-47.524628" : $estabelecimento['longitude']; ?>);
                }
            });
        </script>

	</body>
</html>