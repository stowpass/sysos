<?php
require_once __DIR__ . '/../includes/funcoes.php';
include_once __DIR__ . '/../variaveis.php';

session_start();

isSessaoValida('admin/');

date_default_timezone_set('America/Fortaleza');
$diaCorrente = date('d');
$mesCorrente = date('m');
$anoCorrente = date('Y');
?>

<!DOCTYPE html>
<html>
<head>
	<?php include 'head.php' ?>
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
</head>
<body style="padding-top: 0px;">

	<?php include "topo.php"; ?>

	<div class="col-sm-12 main">
		<div class="row">
			<ol class="breadcrumb alert-info">
				<li><span class="text-warning">ROCHA, MARINHO E SALES ADVOGADOS</span></li>
				<li class="active">Bem-vindo(a) <strong><?=$_SESSION['nome']?></strong></li>
			</ol>
		</div><!--/.row-->

		<hr/>

		<div class="row">
			<div class="col-lg-1">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12 no-padding">
                            <h1 class="page-header text-center">
                                <i class="fa fa-list-alt color-blue"></i></strong>
                            </h1>
                            <?php include 'menu_compacto.php' ?>
						</div>
					</div>
				</div><!-- /.panel-->
			</div>

			<div class="col-lg-11">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="col-md-12">
							<h1 class="page-header">
								<i class="fa fa-user color-blue"></i> Candidatos - Mapa</strong>
							</h1>
						</div>

						<!-- CONTENT -->
						<div class="col-md-12" id="map" style="height: 600px;">

						</div>
						<!-- FIM CONTENT -->

					</div>
				</div><!-- /.panel-->

			</div>
		</div><!--/.row-->


		<div class="row">

			<?php include "../rodape.php"; ?>

		</div><!--/.row-->
	</div>	<!--/.main-->

    <?php include 'scripts.php' ?>
    <script type="text/javascript" src="../js/custom.js"></script>
    <script type="text/javascript" src="../js/admin.js"></script>

	<!-- google maps -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?=$_ENV['GOOGLE_MAPS_API_KEY']?>&language=pt-BR"></script>
    <!-- Fim Google Maps -->

    <script src="https://googlemaps.github.io/js-marker-clusterer/src/markerclusterer.js"></script>

    <?php
    $bd = abreConn();
    $q=  mysqli_query($bd, "SELECT * FROM tb_candidato where status = 'A' and coord1 != '' and coord2 != '' "); ?>

    <script>

      var pos = {lat: -3.732457, lng: -38.497444};
	  var map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 12,
	    center: pos
	  });


	  // LOOP dos clientes começa aqui
	  <?php while ($rs = mysqli_fetch_array($q)){ ?>

	  // ====== MARKER ==== //
	  var image<?=$rs['id']?> = '../img/clienteM.png';
	  var pos<?=$rs['id']?> = {lat: <?=$rs['coord1']?>, lng: <?=$rs['coord2']?>};
	  var marker<?=$rs['id']?> = new google.maps.Marker({
	    position: pos<?=$rs['id']?>,
	    map: map,
	    icon: image<?=$rs['id']?>,
	    animation: google.maps.Animation.DROP,
	    title: '<?=$rs['nome']?>'
	  });
	  marker<?=$rs['id']?>.addListener('click', function() {
	    infowindow<?=$rs['id']?>.open(map, marker<?=$rs['id']?>);
	    if (marker<?=$rs['id']?>.getAnimation() !== null) {
	        marker<?=$rs['id']?>.setAnimation(null);
	    } else {
	        marker<?=$rs['id']?>.setAnimation(google.maps.Animation.DROP);
	    }
	  });
	  var contentString<?=$rs['id']?> = "<div style='width:100%'>"+
	      "<div class='card card-user'>"+
	      "<div class='content'>"+
	      "<div class='alert alert-info'>"+
	      "<h4 class='title'><img src='../img/clienteM.png' /> <?=$rs['nome']?><br />"+
	      "<small><strong>Telefone(s)</strong>: <?=$rs['celular']?></small><br/>"+
	      "<small><strong>E-mail</strong>: <?=$rs['email']?></small><br/>"+
	      "<small><strong>CPF</strong>: <?=$rs['cpf']?></small><br/>"+
	      "<small><strong>RG</strong>: <?=$rs['rg']?></small><br/>"+
	      "</h4>"+
	      "</div>"+
	      "<p class=''> <strong>Endere&ccedil;o</strong>:<br/> <i class='fa fa-map-marker'></i> <?=$rs['endereco']?> <br/>"+
	      "<?=$rs['complemento']?> <br>"+
	      "</p>"+
	      "</div>"+
	      "</div>"+
	      "</div>";

	  var infowindow<?=$rs['id']?> = new google.maps.InfoWindow({
	    content: contentString<?=$rs['id']?>
	  });
	  // ========= FIM MARKER ==== //

	  <?php } ?>
	  // FIM LOOP Clientes


    </script>

	<script type="text/javascript">
		$(document).ready(function() {

			$('#cargoSelMenu').change(function() {
			    window.location = 'processoCad.php?idCargo='+$("#cargoSelMenu").val();
			});

			$('#cargoSelMenuCan').change(function() {
			    window.location = 'candidatoCad.php?idCargo='+$("#cargoSelMenuCan").val();
			});

		});

	</script>

	<?php mysqli_close($bd); ?>

</body>
</html>
