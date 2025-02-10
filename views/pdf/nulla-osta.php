<?php 

	$nombreCompleto = "Zambrano Curiel Ibelitze Carolina";
	$ciudad = "Trujillo";
	$pais = "Perú";
	$diamesanio = "06/03/2023";
	$domicilio = "Mi casa";
	$comuna = "La Libertad";
	$curso = "Test123";
	$nivel = "Quinto";
	$fechaHoy = "Santiago del Cile, luglio 2021"

?>

<!DOCTYPE html>
<html>
<head>
	<title>Nulla Osta - Scuola</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<style type="text/css">
		p{font-size: 18px;}
	</style>
</head>
<body>

	<div class="wti-retiro-alumnos">
	    <div class="container">
	        <!--  VISTA HTML  -->
	        <div class="row">
	        	<div class="col-md-4 col-xs-12">
	        		<?php get_company_logo(); ?>
	        	</div>
	        </div>
	        <br>

	        <div class="row justify-content-center mb-40">
	            <h3><strong>II Dirigente Scolastico</strong></h3>
	        </div>
	        <br>

	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p><strong>VISTA</strong> la domanda dell'interessato..(a)</p>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p><strong>VISTI</strong> gli atti di Ufficio:</p>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p>VISTO che, <strong><?php echo $nombreCompleto ?></strong>, nato a <strong><?php echo $ciudad; ?></strong>, <strong><?php echo $pais; ?></strong> il <strong><?php echo $diamesanio; ?></strong> e domiciliato in Via <strong><?php echo $domicilio; ?></strong>, <?php echo $comuna; ?></p>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p>Ha frequentato il <strong><?php echo $curso; ?></strong>, ottenendo l’ammissione al <strong><?php echo $curso; ?></strong>. Di Scuola <strong><?php echo $nivel; ?></strong>.</p>
	        	</div>
	        </div>
	        <br>
	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p>Ritenuti validi i motivi addotti, concede il</p>
	        	</div>
	        </div>
	        <div class="row justify-content-center">
	            <h3><strong>NULLA OSTA</strong></h3>
	        </div>
	        <br>

	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p>Al trasferimento dell’alunno..(a) presso un’altro Istituto.</p>
	        	</div>
	        </div>
	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p>Si rilascia a richiesta dell’interessato in carta semplice per uso consentito</p>
	        	</div>
	        </div>
	        <br>

	        <div class="row">
	        	<div class="col-md-12 col-xs-12">
	        		<p><?php echo $fechaHoy; ?></p>
	        		<p>Ref.: 165 – 2021 D</p>
	        	</div>
	        </div>
	        <div class="row flex-row-reverse">
	            <?php get_firma_presidente(); ?>
	        </div>


            <div style="width:100%; padding:0; Margin:0;">
                <div style="width: 49%; text-align:center; position: absolute; left: 0px;">
                    <br>
                    <br>
                    <p>LAS CONDES, [FECHA DE EMISIÓN]</p>
                </div>
                <div style="width: 49%; text-align:center; position: absolute; right: 0px;">
                    <p>_________________________________</p>
                    <p>Nombre, Apellidos, Firma y Timbre</p>
                    <p>Director(a) del Establecimiento</p>
                </div>
            </div>


	    </div>

	</div>

</body>
</html>

