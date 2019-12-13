


<!DOCTYPE html>



<?php
	define('PATH_REPORT_A', './report/tipoA/');
	define('PATH_REPORT_D', './report/tipoD/');
	define('PATH_ANALISI_SANGUE', './report/analisiSangue/');
	define('PATH_TBS', './report/tbs/');
	define('PATH_MORFO', './report/morfo/');
	define('PATH_POLSO', './report/polso/');
	define('PATH_GENERICO', './report/generico/');
?>

<html>
  	<head>

  		<title>Centro di Servizio e Ricerca per lo Studio della Menopausa e dell'Osteoporosi</title>
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<!--<link rel="stylesheet" type="text/css" href="stilelogin_antonio.css">-->
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    	<link src="lib/stilesito.css">
    	<!--<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		-->
    	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">-->
    	
    	    
    	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald">
    	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open Sans">
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    	<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>-->
    	<!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>--> 
    	<script src="lib/jquery-3.2.0.min.js"></script>
    	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>-->
    	<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    	<style>

    		i {
    			border: solid black;
				border-width: 0 3px 3px 0;
    			display: inline-block;
    			padding: 3px;
			}

			.right {
    			transform: rotate(-45deg);
    			-webkit-transform: rotate(-45deg);
			}

			.dimensione{
				height: 40px;
				width: 80px;
			}
	    
	    	.foo {
	        	
	        	outline: 1px solid black;
	        	margin: 0 auto;
				height: 271em;
	        	
	    	}

	    	.border {
  				border-right: 1px solid black;
			}

	    	.card {
	      		background-color: #F7F7F7;
	      		/* just in case there no content*/
	      		padding: 20px 25px 30px;
	      		margin: 0 auto 25px;
	  			margin-top: 50px;
		      	/* shadows and rounded borders */
		      	-moz-border-radius: 2px;
		      	-webkit-border-radius: 2px;
		      	border-radius: 2px;
		      	-moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
		      	-webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
		      	box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
	    	}

	    	.btn-circle {
	     	 	width: 40px;
		      	height: 40px;
		      	text-align: center;
		      	padding: 6px 0;
		      	font-size: 12px;
		      	line-height: 1.42;
		      	border-radius: 15px;

	    	}

	    	.btn-quad {
	     	 	height: 25px;
  				width: 25px;
  				/*background-color: #555;*/

	    	}

	    	

	    	footer{
	            clear:both;
	            text-align:center;
            
        	}

        	hr {
				border: 0;
				height: 1px;
				color: #8cacbb;
				background-color: #8cacbb;
				margin: 0.5em 0 1em 0;
			}

			/********************************************************************************** */

			/* Popup container */
			.popup {
			position: relative;
			display: inline-block;
			cursor: pointer;
			/*visibility: hidden;*/
			}

			/* The actual popup (appears on top) */
			.popup .popuptext {
			visibility: hidden;
			width: 450px;
			/*height: 100px;*/
			background-color: #555;
			color: #fff;
			text-align: center;
			border-radius: 6px;
			padding: 8px 0;
			position: absolute;
			z-index: 1000; /*prima 1 */
			bottom: 125%;
			left: 50%;
			margin-left: -80px;
			}

			/* Popup arrow */
			.popup .popuptext::after {
			
			}

			/* Toggle this class when clicking on the popup container (hide and show the popup) */
			.popup .show {
			visibility: visible;
			-webkit-animation: fadeIn 0.1s;
			animation: fadeIn 0.1s
			}

			/* Add animation (fade in the popup) */
			@-webkit-keyframes fadeIn {
			from {opacity: 0;}
			to {opacity: 1;}
			}

			@keyframes fadeIn {
			from {opacity: 0;}
			to {opacity:1 ;}
			} 

			
	  	</style>
  	</head>


	





  	<body>
    	<a href="index.php">
      		<img  src="nuovo_logo.jpg" class = "img-responsive" title="Centro di Servizio e Ricerca per lo Studio della Menopausa e dell'Osteoporosi"></img>
    	</a>

    	<?php include('lib/dbConfig.php'); ?>
    	<div class="container-fluid"> <!--container-fluid -->
    		<?php
    			$pk = $_GET[pk];
				$datascan_mysql = $_GET[datascan];


				$args = '"'.$pk.'","'.$datascan_mysql.'"';
				echo $args;
				


    			$queryPatient = "SELECT FIRST_NAME, LAST_NAME, BIRTHDATE, SEX FROM PATIENT WHERE PATIENT_KEY = '$pk'";
    			$res = mysqli_query($mysqli, $queryPatient);
    			if(!$res) exit("queryPatient: $mysqli->errno:\n$queryPatient\n\n");
    			$row = mysqli_fetch_assoc($res);

    			$datascan = date('d/m/Y', strtotime($datascan_mysql));    			
    			$nome = $row[FIRST_NAME];
    			$cognome = $row[LAST_NAME];

    			$data_nascita_mysql = $row[BIRTHDATE];
    			$sex = $row[SEX];

    			$data_nascita = date('d/m/Y', strtotime($data_nascita_mysql));
    		
    			//Query per la selezione dello stato attuale e degli eventuali path associati ai Report.
    			$queryStato = "SELECT STATO_A, STATO_B, STATO_C, STATO_D, NAME_REPORT_A, NAME_REPORT_D FROM Referti WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql'";
    			$res = mysqli_query($mysqli,$queryStato);

    			if(!$res) exit("queryStato: $mysqli->errno\n$queryStato\n\n");
    			$row = mysqli_fetch_assoc($res);
    		 	
    		 	$statoA = $row[STATO_A];
    		 	$nameReportA = $row[NAME_REPORT_A];
    		 	//non so se ci vuole
    		 	$nameReportA = str_replace('\\', '', $nameReportA);
    		 	
    		 	$pathReportA = PATH_REPORT_A.$nameReportA;
    		 	$statoB = $row[STATO_B];
    		 	$statoC = $row[STATO_C];
    		 	$statoD = $row[STATO_D];
    		 	$nameReportD = $row[NAME_REPORT_D];
    		 	$pathReportD = PATH_REPORT_D.$nameReportD;

    		 	// E qui si conclude il primo blocco di codice php, in cui abbiamo recuperato le informazioni principali sul paziente e sullo stato del suo referto.
		 	?>

		 	
		 	<!-- PRIMA PARTE IN CUI SI VEDONO I DATI ANAGRAFICI DEL PAZIENTE TUTTI QUESTI CAMPI NON SONO MODIFICABILI DALL'UTENTE. -->
    		<div class="row">
    			<div class="col-sm-12">
	            	<div class="row">
	            		<div class="col-sm-6">
	            	<div class="input-group">
	              		<span class="input-group-addon" id="sizing-addon2">Cognome e nome</span>
	              		<input type="text"  aria-describedby="sizing-addon1" id = "cognome" placeholder="Cognome" name="Cognome" value="<?php echo htmlspecialchars($cognome); ?>" readonly>
	              		<input type="text"  aria-describedby="sizing-addon1" id = "nome" placeholder="Nome" name="Nome" value="<?php echo($nome); ?>"readonly>
	            	</div>
	            	<br/>
	           
	            	<div class="input-group">
	              		<span class="input-group-addon" id="sizing-addon2">Data Nascita</span>
	              		
	              		<input type="text" name="datanascita1" id="datanascita1" placeholder="data-nascita" value="<?php echo $data_nascita; ?>" readonly>
	            	</div>
	       
	            	<br/>
	            	<div class="input-group">
	              		<span class="input-group-addon" id="sizing-addon2">Data Scan</span>
	                	
	                	
	                	<input type="text" name="datascan" id="datascan" value="<?php echo htmlspecialchars($datascan); ?>" readonly>
	            	</div>
	            </div>
	            
	            <?php
	                		$queryAnalisiSangue = "SELECT NAME_ANALISI_SANGUE, NAME_TBS, NAME_MORFO, NAME_POLSO, NAME_GENERICO, ANALISI_SANGUE_CHECKBOX, TBS_CHECKBOX, MORFO_CHECKBOX, POLSO_CHECKBOX, GENERICO_CHECKBOX FROM Referti WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql'";
							$res = mysqli_query($mysqli,$queryAnalisiSangue);
							if(!$res) echo "Errore queryAnalisiSangue:";
							$row = mysqli_fetch_assoc($res);
							$path_analisi_sangue = $row[NAME_ANALISI_SANGUE];
							$path_tbs = $row[NAME_TBS];
							$path_morfo = $row[NAME_MORFO];
							$path_polso = $row[NAME_POLSO];
							$path_generico = $row[NAME_GENERICO];
							
							$analisi_sangue_checkbox 	= $row['ANALISI_SANGUE_CHECKBOX'];
							$tbs_checkbox 				= $row['TBS_CHECKBOX'];
							$morfo_checkbox 			= $row['MORFO_CHECKBOX'];
							$polso_checkbox 			= $row['POLSO_CHECKBOX'];
							$generico_checkbox 			= $row['GENERICO_CHECKBOX'];
						?>

	            <div class="col-sm-6">
	            	<form action="./backend_frontend/upload.php" method="POST" enctype="multipart/form-data">
							    Seleziona l'allegato da caricare (sono accettati solo file .pdf):
	                  			<input type="hidden" name="pk" value="<?php echo $pk;?>">
							    <input type="hidden" name="datascan" value="<?php echo $datascan_mysql;?>">
							    <input type="file" name="fileToUpload" id="fileToUpload">
								<br/>
								
								<span style="display:block; float:left; margin:4px 4px 0 0;"><input type="checkbox" class="allegato_checkbox" data-name="analisi_sangue_checkbox" data-path="<?php echo $path_analisi_sangue ?>" <?php echo (!$path_analisi_sangue) ? 'disabled' : '' ?> <?php echo ($analisi_sangue_checkbox == true) ? 'checked' : '' ?> /></span> 
							    <a href="#" class="btn btn-default btn-quad" id="AnalisiSangue"><b></b></a>
							    <input type="submit" class="btn btn-default" id="UploadAnalisiSangue" value="UPLOAD ANALISI" name="uploadAnalisiSangue">
							    <a href="<?php echo PATH_ANALISI_SANGUE.$path_analisi_sangue ?>" class="btn btn-default" type="submit" id="Vedi_Analisi_Sangue" onclick="this.blur()" target="_blank" disabled>VEDI ANALISI</a>
							    <input type="submit" value="ELIMINA ALLEGATO" class="btn btn-default" name="EliminaAnalisiSangue" id="EliminaAnalisiSangue" onclick="return confirm('Sei sicuro di voler eliminare l\' allegato relativo all\' analisi del sangue?')"/>
								<br/><br/>
								
								<span style="display:block; float:left; margin:4px 4px 0 0;"><input type="checkbox" class="allegato_checkbox" data-name="tbs_checkbox" data-path="<?php echo $path_tbs ?>" <?php echo (!$path_tbs) ? 'disabled' : '' ?> <?php echo ($tbs_checkbox == true) ? 'checked' : '' ?> /></span> 
								<a href="#" class="btn btn-default btn-quad" id="TBS"><b></b></a>
							    <input type="submit" class="btn btn-default" id="UploadTBS" value="UPLOAD TBS" name="uploadTBS">
							    <a href="<?php echo PATH_TBS.$path_tbs ?>" class="btn btn-default" type="submit" id="Vedi_TBS" onclick="this.blur()" target="_blank">VEDI TBS</a>
							    <input type="submit" value="ELIMINA ALLEGATO" class="btn btn-default" name="EliminaTBS" id="EliminaTBS" onclick="return confirm('Sei sicuro di voler eliminare l\' allegato relativo al TBS?')"/>
							    <br/><br/>
								
								<span style="display:block; float:left; margin:4px 4px 0 0;"><input type="checkbox" class="allegato_checkbox" data-name="morfo_checkbox" data-path="<?php echo $path_morfo ?>" <?php echo (!$path_morfo) ? 'disabled' : '' ?> <?php echo ($morfo_checkbox == true) ? 'checked' : '' ?> /></span> 
								<a href="#" class="btn btn-default btn-quad" id="Morfo"><b></b></a>
							    <input type="submit" class="btn btn-default" id="UploadMorfo" value="UPLOAD MORFOMETRIA" name="uploadMorfo">
							    <a href="<?php echo PATH_MORFO.$path_morfo ?>" class="btn btn-default" target="_blank" id="Vedi_Morfo" onclick="this.blur()">VEDI MORFOMETRIA</a>
								<input type="submit" value="ELIMINA ALLEGATO" class="btn btn-default" name="EliminaMorfo" id="EliminaMorfo" onclick="return confirm('Sei sicuro di voler eliminare l\' allegato relativo alla Morfometria?')"/>
								<br/><br/>
								
								<span style="display:block; float:left; margin:4px 4px 0 0;"><input type="checkbox" class="allegato_checkbox" data-name="polso_checkbox" data-path="<?php echo $path_polso ?>" <?php echo (!$path_polso) ? 'disabled' : '' ?> <?php echo ($polso_checkbox == true) ? 'checked' : '' ?> /></span> 
								<a href="#" class="btn btn-default btn-quad" id="Polso"><b></b></a>
							    <input type="submit" class="btn btn-default" id="UploadPolso" value="UPLOAD SCAN POLSO" name="uploadPolso">
							    <a href="<?php echo PATH_POLSO.$path_polso ?>" class="btn btn-default" target="_blank" id="Vedi_Polso" onclick="this.blur()">VEDI SCAN POLSO</a>
							    <input type="submit" value="ELIMINA ALLEGATO" class="btn btn-default" name="EliminaPolso" id="EliminaPolso" onclick="return confirm('Sei sicuro di voler eliminare l\' allegato relativo alla scan del polso?')"/>
							    <br/><br/>
								
								<span style="display:block; float:left; margin:4px 4px 0 0;"><input type="checkbox" class="allegato_checkbox" data-name="generico_checkbox" data-path="<?php echo $path_generico ?>" <?php echo (!$path_generico) ? 'disabled' : '' ?> <?php echo ($generico_checkbox == true) ? 'checked' : '' ?> /></span> 
								<a href="#" class="btn btn-default btn-quad" id="Generico"><b></b></a>
							    <input type="submit" class="btn btn-default" id="UploadGenerico" value="UPLOAD DOC. GENERICO" name="uploadGenerico">
							    <a href="<?php echo PATH_GENERICO.$path_generico ?>" class="btn btn-default" target="_blank" id="Vedi_Generico" onclick="this.blur()">VEDI DOC. GENERICO</a>
							    <input type="submit" value="ELIMINA ALLEGATO" class="btn btn-default" name="EliminaGenerico" id="EliminaGenerico" onclick="return confirm('Sei sicuro di voler eliminare l\' allegato relativo al documento generico?')"/>
							</form>
							<br/>

							<?php if($path_polso == null){ 	?>
								<script>
										 	$(document).ready(function(){
												$('#Polso').css('background-color','red');
												$('#Vedi_Polso').attr('disabled', true);
												$('#Vedi_Polso').removeAttr('href');
												$('#EliminaPolso').prop('disabled', true);
													
											});
										</script>
							<?php   }
									else {
							?>  
										<script>
										 	$(document).ready(function(){
										 		var a = 1;
												$('#Polso').css('background-color','green');
												$('#Vedi_Polso').attr('disabled', false);
												$('#EliminaPolso').prop('disabled', false);
											});
										</script>
							<?php   } ?>
							<?php if($path_generico == null){ ?>
									<script>
										 	$(document).ready(function(){
												$('#Generico').css('background-color','red');
												$('#Vedi_Generico').attr('disabled', true);
												$('#Vedi_Generico').removeAttr('href');
												$('#EliminaGenerico').prop('disabled', true);
													
											});
										</script>
							<?php   }
									else {
							?>  
										<script>
										 	$(document).ready(function(){
										 		var a = 1;
												$('#Generico').css('background-color','green');
												$('#Vedi_Generico').attr('disabled', false);
												$('#EliminaGenerico').prop('disabled', false);
											});
										</script>
							<?php   } ?>
							<!--FIN QUA-->


							

							<?php 	if($path_analisi_sangue == null){    ?>

										<script>
										 	$(document).ready(function(){
												$('#AnalisiSangue').css('background-color','red');
												$('#Vedi_Analisi_Sangue').attr('disabled', true);
												$('#Vedi_Analisi_Sangue').removeAttr('href');
												$('#EliminaAnalisiSangue').attr('disabled', true);
													
											});
										</script>
							<?php   }
									else {
							?>
										<script>
										 	$(document).ready(function(){
										 		var a = 1;
												$('#AnalisiSangue').css('background-color','green');
												$('#Vedi_Analisi_Sangue').attr('disabled', false);
												$('#EliminaAnalisiSangue').attr('disabled', false);
											});
										</script>
							<?php   }

									if($path_tbs == null){
							?>
										<script>
											$(document).ready(function(){
												$('#TBS').css('background-color', 'red');
												$('#Vedi_TBS').attr('disabled', true);
												$('#Vedi_TBS').removeAttr('href');
												$('#EliminaTBS').attr('disabled', true);

											});

										</script>			
							<?php
									}
									else{
							?>		
										<script>
											$(document).ready(function(){
												var b = 1;
												
												$('#TBS').css('background-color', 'green');
												$('#Vedi_TBS').prop('disabled', false);
												$('#EliminaTBS').attr('disabled', false);
											});		
										</script>	

							<?php	

									}

									if($path_morfo == null){

							?>
										<script>
											$(document).ready(function(){
												$('#Morfo').css('background-color', 'red');
												$('#Vedi_Morfo').attr('disabled', true);
												$('#Vedi_Morfo').removeAttr('href');
												$('#EliminaMorfo').prop('disabled', true);
											});
										</script>
							<?php
									}

									else{

							?>
										<script>
											$(document).ready(function(){
												var c = 1;
												$('#Morfo').css('background-color', 'green');
												$('#Vedi_Morfo').attr('disabled', false);
												$('#EliminaMorfo').prop('disabled', false);
											});
										</script>							

							 <?php
							 		}


							 ?>

							 <?php 	if(strcmp($statoA, "VERDE")==0 && strcmp($statoB, "VERDE")==0 && strcmp($statoC, "VERDE")==0 && strcmp($statoD, "VERDE")==0){ ?>

										 <script>
										 	$(document).ready(function(){

										 			$('#UploadAnalisiSangue').prop('disabled', true);
										 			$('#UploadTBS').prop('disabled', true);
										 			$('#UploadMorfo').prop('disabled', true);
										 			$('#UploadPolso').prop('disabled', true);
										 			$('#UploadGenerico').prop('disabled', true);
										 		

										 	});
										 </script>
							<?php   }   ?> 

	            </div><!--/col-sm-6-->
	        </div><!--/row-->
	            	<br/>
	            	<?php
	            		
	            		//$uid = 'melchiore.giganti';
	            		$uid = $_SERVER[uid];
	            		$queryFirmante = "SELECT NOME, COGNOME, FIRMA FROM Firme WHERE UID = '$uid'";
	            		//echo $queryFirmante;
	            		$res = mysqli_query($mysqli,$queryFirmante);
	            		if(!$res) echo "$queryFirmante";
	            		$row = mysqli_fetch_assoc($res);
	            		$nome_firmante = $row[NOME];
	            		$cognome_firmante = $row[COGNOME];
	            		$firma_A = $row[FIRMA]; 
	            		$firmante = "$cognome_firmante $nome_firmante";

	            	?>
	            	
	            	<!--TEXT NASCOSTA PER SALVARMI IL NOME DELLA FOTO DEL FIRMANTE, SE NON ESISTE NON POSSO FIRMARE-->
	            	<input type="hidden" value="<?php echo $firma_A ?>" id="text_firma_A">

	            	<div class = "row">
	              		<!-- BOTTONE RELATIVO ALLO STATO A. -->
	              		<div class="col-sm-2">
	                		<a href="#"  class="btn btn-default btn-circle" id="A"><b>A</b></a>
	                		<i class="right"></i>
	                		<br/>
	                		Scan
	                		<br/>

	                		<a href="<?php echo $pathReportA; ?>" class="btn btn-default" onclick="this.blur();" target="_blank" type="submit" name="scan" id="scan">VEDI</a>
	              			<br/>
	              			<form action="./backend_frontend/firmaA.php" id="formA" method="GET" onsubmit="return check_firmaA()">
	              				<input type="hidden" name="pk" value="<?php echo $pk;?>">
	                  			<input type="hidden" name="datascan" value="<?php echo $datascan_mysql;?>">
	              				<input type="submit" name="FirmaA" class="btn btn-default" id="firma_scan" value="FIRMA SCAN" onclick="this.blur()">
	              			</form>

	              			<!--FORM PER IL BOTTONE ANNULLA FIRMA SCAN-->
	              			<form action="./backend_frontend/AnnullaFirmaScan.php" id="formAnnullaFirmaScan" method="GET" onsubmit="return check_annulla_firma_scan()">
	              				<input type="hidden" name="pk" value="<?php echo $pk;?>">
	                  			<input type="hidden" name="datascan" value="<?php echo $datascan_mysql;?>">
	              				<input type="submit" class="btn btn-default" id="AnnullaScan" value="ANNULLA SCAN" onclick="this.blur()">
	              			</form>


	              			<?php if (strcmp($statoA,"ROSSO")==0): ?>
	              					<script>
	              						$('#AnnullaScan').prop('disabled',true)
	              					</script>
	              			<?php endif; ?>

	              			

	              			<script type="text/javascript">
	              				function check_annulla_firma_scan(){
	              					return confirm("Sei sicuro di voler annullare la firma Scan?");

	              				}

	              			</script>

	              			<script type="text/javascript">
	              				function check_annulla_firma_referto(){
	              					return confirm("Sei sicuro di voler annullare la firma del Referto finale?");

	              				}

	              			</script>


	         				
	         				<script type="text/javascript">
	                	
			                	function check_firmaA() {
		     						var firma_A = $('#text_firma_A').val();
		     						
		     						if(firma_A == ""){
		     							alert("Non hai i diritti per firmare");
		      	 						return false;
		      	 					}
									else 
		       							return confirm('Stai firmando come <?php echo $firmante ?>. Sei sicuro di voler firmare la Scan?');
								}
							</script>     					              			
	              			<br/>
	              			
	              		</div>
	              		
	              		

	              		<!-- CASISTICA DELLE POSSIBILITÀ DELLO STATO VERDE. -->
	              		<!-- DA FARE !!!!!!!!!!!!!!!!!!! -->
	              		<!-- SE IL PDF RELATIVO ALLA SCAN NON ESISTE, ALLORA NASCONDO IL PULSANTE VEDI RELATIVO AL PDF, FEDERICO: NON SONO D'ACCORDO, SECONDO ME È IL PAZIENTE CHE NON DEVE ESSERE RICERCABILE ... -->
	              		<?php 	if($pathReportA == null){     ?>
	              					<script>
	              						$(document).ready(function(){
	              							$('#scan').hide();
	              							$('#A').css("background-color","red");
	              						});
	              					</script>
	              		<?php  	}  ?>

	              		




	              		<!-- SE IL PERCORSO AL PDF ESISTE ALLORA INIZIALIZZO GLI STATI -->
	              		<?php   if($pathReportA != null){      ?>
	              					<script>
	              						$(document).ready(function(){
	              							$('#A').css("background-color","yellow");
			                  				$('#B').css("background-color","yellow");
			                  				$('#C').css("background-color","red");
			              					$('#D').css("background-color","red");
			              				});
	              					</script>
	              		<?php   }   ?>

	              		
	              			
	              		<!-- SE LO STATO A È GIALLO VUOL DIRE CHE IL PDF ESISTE E POSSO VEDERLO -->
	              		<?php 	if(strcmp($statoA, "GIALLO")==0){ ?>
	              		
	              					<script>
	              						$(document).ready(function(){
	              							$('#scan').attr('disabled',false);
	              							$('#firma_scan').attr('disabled',false);
	              						});
	              					</script>
	              		<?php 	} 	?>

	              		<!-- SE LO STATO A È VERDE ALLORA COLORO DI VERDE IL RELATIVO BOTTONE E ABILITO VEDI FIRMATO, CHE DIVENTA DISPONNIBILE SOLO UNA VOLTA FIRMATO A-->
	              		<?php   if(strcmp($statoA, "VERDE")==0){ ?>
              						<script>
              							$(document).ready(function(){
              								//$('#scan').attr('disabled',true); //DISABILITO IL BOTTONE VEDI RELATIVO ALLA SCAN
 											//$("#scan").removeAttr('href'); //RIMUOVO L' ATTRIBUTO HREF DAL LINK, POICHÈ ALTRIMENTI ANCHE SE LO DISATTIVIAMO RESTEREBBE CLICCABILE-->
              								$('#firma_scan').attr('disabled',true);
		              						$('#A').css('background-color','green');
              							});
	              					</script>
	              		<?php	}  ?>

	              		<?php   if(strcmp($statoA, "ROSSO")==0){ ?>
              						<script>
              							$(document).ready(function(){
              								//$('#scan').attr('disabled',true); //DISABILITO IL BOTTONE VEDI RELATIVO ALLA SCAN
 											//$("#scan").removeAttr('href'); //RIMUOVO L' ATTRIBUTO HREF DAL LINK, POICHÈ ALTRIMENTI ANCHE SE LO DISATTIVIAMO RESTEREBBE CLICCABILE-->
              								$('#firma_scan').attr('disabled',true);
              								$('#scan').attr('disabled',true);
              								$("#scan").removeAttr('href');
		              						$('#A').css('background-color','red');
		              						$('#D').css('background-color','red');
		              						$('#firmaD').attr('disabled',true);

              							});
	              					</script>
	              		<?php	}  ?>



	              		

	              		
	              		<!-- PARTE RELATIVA AL BOTTONE B -->
	                	<div class="col-sm-2">
	                  		<a href="#" class="btn btn-default btn-circle" id="B"><b>B</b></a>
	                  		<i class="right"></i>
	                  		<br/>
	                  		Anamnesi
	                  		<br/>
	                	</div>

	                	<?php 	if(strcmp($statoB,"VERDE")==0){ ?>
	                		  		<script>
	                		  			$(document).ready(function(){
	                		  				$('#B').css('background-color','green');
	                		  				$('#C').css("background-color","yellow");
          									//$('#D').css("background-color","red");
	                		  			});
	                		  		</script>
	                	<?php 	}  ?>

	                	<!-- PARTE RELATIVA AL BOTTONE C -->
	                	<div class="col-sm-2">
	                  		<a href="#" class="btn btn-default btn-circle" id="C"><b>C</b></a>
	                  		<i class="right"></i>
	                  		<br/>
	                  		Diagnosi
	                  		<br/>
	                  		
	                  		<form action="./backend_frontend/genera_pdf.php" method="GET" target="_blank">
	                  			<input type="hidden" name="pk" value="<?php echo $pk;?>">
	                  			<input type="hidden" name="datascan" value="<?php echo $datascan_mysql;?>">
	                  			<input type="submit" class="btn btn-default" onclick="this.blur();" name="GeneraC" id="GeneraC" value="VEDI REFERTO" disabled>
	                  		</form>
	                	</div>

						<?php   if(strcmp($statoC, "ROSSO")==0){    ?>
	                				<script>
	                					$(document).ready(function(){
	                						$('#GeneraC').prop('disabled',true);
	                					});
	                					
	                				</script>
	                	<?php   }   ?>
	                	


	                	<?php   if(strcmp($statoC, "GIALLO")==0){    ?>
	                				<script>
	                					$(document).ready(function(){
	                						$('#GeneraC').prop('disabled',false);
	                					});
	                					
	                				</script>
	                	<?php   }   ?>

	                	<?php  	if(strcmp($statoC, "VERDE")==0){  ?>
	                				<script>
	                					$(document).ready(function(){
	                						$('#C').css("background-color","green");
          									//$('#D').css("background-color","yellow");
          									$('#GeneraC').prop('disabled',false);
          									//$('#FirmaD').prop('disabled',false);

	                					});

	                				</script>
	                	<?php 	}  ?>


	                	<?php
	              			//$uid = 'bng';
	              			$uid = $_SERVER[uid];
							$queryFirmante = "SELECT NOME, COGNOME, FIRMA FROM Firme WHERE UID = '$uid'";
	            			//echo $queryFirmante;
	            			$res = mysqli_query($mysqli,$queryFirmante);
	            			if(!$res) echo "$queryFirmante";
	            			$row = mysqli_fetch_assoc($res);
	            			$nome_firmante = $row[NOME];
	            			$cognome_firmante = $row[COGNOME];
	            			$firma_D = $row[FIRMA];
	            			$firmante = "$cognome_firmante $nome_firmante";


	              		?>
	              		<!--TEXT NASCOSTA PER SALVARMI IL NOME DELLA FOTO, SE ESISTE. SE NON ESISTE SIGNIFICA CHE NON POSSO FIRMARE -->
	              		<input type="hidden" value="<?php echo $firma_D ?>" id="text_firma_D">


	                	<!-- PARTE RELATIVA AL BOTTONE D -->
	                	<div class="col-sm-2">
	                  		<a href="#" class="btn btn-default btn-circle" id="D"><b>D</b></a>
	                  		
	                  		<br/>
	                  		Firma
	                  		<br/>

	                  		<form id="formD" onsubmit="return check_firmaD()" action="./backend_frontend/firmaD.php" method="GET">
	                  			<input type="hidden" name="pk" value="<?php echo $pk;?>">
								  <input type="hidden" name="datascan" value="<?php echo $datascan_mysql;?>">

								  <input type="hidden" name="analisi_sangue_checkbox" value="">
								  <input type="hidden" name="tbs_checkbox" value="">
								  <input type="hidden" name="morfo_checkbox" value="">
								  <input type="hidden" name="polso_checkbox" value="">
								  <input type="hidden" name="generico_checkbox" value="">

	                  			<input type="submit" class="btn btn-default" name="FirmaD" id="FirmaD" value="FIRMA REFERTO" onclick="this.blur()" disabled>
	                  			<a href="<?php echo $pathReportD  ?>" class="btn btn-default" type="submit" id="vedi_referto_firmato" onclick="this.blur();" target="_blank">VEDI REFERTO FIRMATO</a>
                  			</form>
	                	

		                	<!--FORM PER IL BOTTONE ANNULLA FIRMA REFERTO-->
	              			<form action="./backend_frontend/AnnullaFirmaReferto.php" id="formAnnullaFirmaReferto" method="GET" onsubmit="return check_annulla_firma_referto()">
	              				<input type="hidden" name="pk" value="<?php echo $pk;?>">
	                  			<input type="hidden" name="datascan" value="<?php echo $datascan_mysql;?>">
	              				<input type="submit" name="AnnullaFirmaReferto" class="btn btn-default" id="AnnullaFirmaReferto" value="ANNULLA FIRMA REFERTO" onclick="this.blur()" >
	              				
	              			</form>
              			</div>

	                	<script type="text/javascript">
	                		
							$('document.ready', function(){
								$('.allegato_checkbox').change(function(e){
									var name = $(this).attr('data-name');
									var path = $(this).attr('data-path');

									var checked = $(this).is(':checked');
									if (checked) 
										$("#formD input[name='"+ name +"']").val(path);
									else
										$("#formD input[name='"+ name +"']").val('');

								});
							});

		                	function check_firmaD() {
	     						var firma = $('#text_firma_D').val();
	     						
	     						if(firma == ""){
	     							alert("Non hai i diritti per firmare");
	      	 						return false;
	      	 					}
								else 
	       							return confirm('Stai firmando come <?php echo $firmante ?>. Sei sicuro di voler firmare il referto finale?');
							}
						</script>
	                	


	                	<div class="col-sm-4">
							
							          
						</div>
	            	</div> <!-- E QUI SI CONCLUDE TUTTA LA RIGA DEGLI STATI. -->

		            <?php   if(strcmp($statoD, "ROSSO")==0){  ?>		
		            			<script>
		            				$(document).ready(function(){
		            					$('#D').css('background-color','red');
		            					$('#FirmaD').prop('disabled',true);

		            				});

		            			</script>
		            <?php   }    ?>
		            <script>

                		$('#vedi_referto_firmato').css('visibility','hidden');
                		$('#AnnullaFirmaReferto').css('visibility','hidden');
	                	
		            </script>

		            <?php   if(strcmp($statoD, "GIALLO")==0){  ?>		
		            			<script>
		            				$(document).ready(function(){
		            					$('#D').css('background-color','yellow');
		            					$('#FirmaD').prop('disabled',false);

		            				});

		            			</script>
		            <?php   }    ?>
		            <script>

                		$('#vedi_referto_firmato').css('visibility','hidden');
	                	
		            </script>

		            <!--SE LO STATO D È VERDE ALLORA RENDO VERDE IL PULSANTE DEL TIPO D, MOSTRO IL PULSANTE PER VEDERE IL REFERTO FIRMATO, DISABILITO IL PULSANTE PER FIRMARE IL REFERTO FINALE
		                IN  QUANTO È STATO GIÀ FIRMATO, DISABILITO IL PULSANTE PER VEDERE IL PDF DELLA DIAGNOSI, IN QUANTO È GIA COMPRESO DENTRO IL PDF FINALE-->
		            <?php  	if(strcmp($statoD, "VERDE")==0){   ?>
		            			<script>
		            				$(document).ready(function(){

		            					$('#D').css('background-color','green');
	                					$('#vedi_referto_firmato').css('visibility','visible');
	                					$('#AnnullaFirmaReferto').css('visibility','visible');
	                					$('#FirmaD').prop('disabled',true);
	                					$('#GeneraC').prop('disabled',true);
	                					$('#scan').attr('disabled',true);
	                					$("#scan").removeAttr('href');
		            				});

		            			</script>
		            <?php 	}     ?> 

		            <script>
		            //FUNZIONE CHE AL CLICCARE DEL PULSANTE B ALL INIZIO DELLA PAGINA, INDIRIZZA DIRETTAMENTE ALL'INIZIO DEL REFERTO B
 						$('#B').click(function() {
    						$('html,body').animate({
        						scrollTop: $('#RefertoB').offset().top
        					},'slow');
						});
					</script>
 					



    			</div>
    			

<!-- *******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************7
 *******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 *******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 *******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************-->

    			<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
    			<!-- DA QUA PARTE LA PARTE RELATIVA ALL'ANAMNESI. -->
	      		<br/><br/>
		      	<h3><b><p style="text-align:center;" id="RefertoB">Consulenza Osteoporosi - DXA vertebre-femore. Scheda anamnestica di rilevazione dati clinici</p></b></h3>
		      	<br/>

		      	<?php require './backend_frontend/recupero_anamnesi.php'; ?>
				<form action="./backend_frontend/inserimento_anamnesi.php" method="POST" id="myForm", name="myForm">
					<input type="hidden" name="pk" value="<?php echo $pk; ?>">
					<input type="hidden" name="datascan" value="<?php echo $datascan_mysql; ?>">
			      	<div class="row">
			        	<div class="col-sm-12">
			          		<div class="row">
			          			<div class="col-sm-1 foo well">
			            			<h1><p><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>B</p></h1>
			          			</div>

			          			<div class="col-sm-11">
			            			<div class="row">
			              				<div class="col-sm-4">
			                				<div class="input-group">
			                  					<span class="input-group-addon" id="sizing-addon2"><b>Data dell'esame</b></span>
			       
			                  					<input type="text" class="form-control" placeholder="Data esame" name="DataEsame" value="<?php echo $datascan; ?>"/>
			                				</div>
			              				<!-- chiudo la colonna -->
			              				</div>
			        				<!-- chiudo la riga principale-->
			          				</div>

			      					<br/>
			      					<div class="row">
			        					<div class="col-sm-5">
				          					<div class="form-group input-group">
		      										<span style="width: 20px;" class="input-group-addon"><b>Cognome e Nome</b></span>
		 	 										<input type="text" class="form-control" name="CognomeNome" value="<?php echo "$cognome $nome" ?>">
		    								</div>
			        					</div>
			        
			        					<div class="col-sm-3">
			          						<div class="form-group input-group">
		      										<span style="width: 20px;" class="input-group-addon"><b>Nata il</b></span>
		 	 										<input type="text" class="form-control" placeholder="Nata il" id="DataNascita" name="DataNascita" value = "<?php echo $data_nascita; ?>"> 
		    								</div>
			        					</div>

			        					<?php 
			            					$eta = "SELECT AGE FROM ScanAnalysis WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql'";
			            					$res = mysqli_query($mysqli,$eta);
			            					if(!$res) exit("getEta: $mysqli->errno\n$eta\n\n");
			            					$row = mysqli_fetch_assoc($res);
			            					$age = $row[AGE];
			        					?>
			        					<div class="col-sm-2">
			          						<div class="form-group input-group">
		      										<span style="width: 20px;" class="input-group-addon"><b>Età</b></span>
		 	 										<input type="text" class="form-control" placeholder="Età" id="Eta" name="Eta" value="<?php echo $age; ?>">
		    								</div>
			        					</div>
			  						</div>
			      					<br/>
				  					<div class="row">
				        				<div class="col-sm-4">
				          					<div class="input-group">
				            					<span class="input-group-addon" id="sizing-addon2"> <b>Risiede a</b></span>
				            					<input type="text" class="form-control" id="Residenza" placeholder="Residenza" name="Residenza" autocomplete="off" value="<?php if($residenza != 'NULL') echo $residenza; ?>">
				          					</div>
				        				</div>
				        				<!--
				        				<div class="col-sm-4">
				        					<div class="input-group">
										      <div class="input-group-btn">
										        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>
										        <ul class="dropdown-menu">
										          <li><a href="#">Action</a></li>
										          <li><a href="#">Another action</a></li>
										          <li><a href="#">Something else here</a></li>
										          <li role="separator" class="divider"></li>
										          <li><a href="#">Separated link</a></li>
										        </ul>
										      </div> 
										      <input type="text" class="form-control" aria-label="...">
										    </div>
				        				</div>
										-->
				        				
				        				<div class="col-sm-4">
				          					<div class="input-group">
				          						<div class="input-group-btn">
				            					<!--<span class="input-group-addon" id="sizing-addon2"> <b>Via</b></span>-->
					            					<!--<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <span class="caret"></span></button>-->
				            						<select name="Strada" class="dropdown-menu form-control" style="background-color:#eee" id="Strada">
				            							<option>Via</option>
				            							<option>Piazza</option>
				            							<option>Vicolo</option>
				            							<option>Stradella</option>
				            							<option>Viale</option>
				            						</select>
			            						</div>
				            					<input type="text" class="form-control" id="Via" placeholder="Via" name="Via" autocomplete="off" value="<?php if($via != 'NULL') echo $via; ?>">
				          					</div>
				        				</div>

				        			
				        					
				        				<?php 	if(strcmp($strada, "Via")==0) { ?>
				        							<script>
				        								$('#Strada').prop('selectedIndex',0);
				        							</script>
			        					<?php 	} ?> 

			        					<?php 	if(strcmp($strada, "Piazza")==0) { ?>
				        							<script>
				        								$('#Strada').prop('selectedIndex',1);
				        							</script>
			        					<?php 	} ?>

			        					<?php 	if(strcmp($strada, "Vicolo")==0) { ?>
				        							<script>
				        								$('#Strada').prop('selectedIndex',2);
				        							</script>
			        					<?php 	} ?>

			        					<?php 	if(strcmp($strada, "Stradella")==0) { ?>
				        							<script>
				        								$('#Strada').prop('selectedIndex',3);
				        							</script>
			        					<?php 	} ?>

			        					<?php 	if(strcmp($strada, "Viale")==0) { ?>
				        							<script>
				        								$('#Strada').prop('selectedIndex',4);
				        							</script>
			        					<?php 	} ?> 
 

				        				<div class="col-sm-4">
				          					<div class="input-group">
				            					<span class="input-group-addon" id="sizing-addon2"> <b>Tel</b></span>
				            					<input type="text" class="form-control" id="Tel" placeholder="Tel" name="Tel" autocomplete="off" value="<?php if($telefono != 'NULL') echo $telefono; ?>">
				          					</div>
				        				</div>

				      				</div>
				      				<br/>

				      				<!-- Sesso -->
				      				<!-- Per la checkbox bisogna mettere lo stesso nome a entrambe le checkbox altrimenti si possono accendere entrambe -->
				      				<?php
				      					// TRADUCO IMMEDIATAMENTE IL VALORE DELL'ENUM IN MODO DA NON SDOPPIARE IL CODICE.
				      					if(strcmp($sex, 'M') == 0) $maschioChecked = 'checked';
				      					else if(strcmp($sex, 'F') == 0) $femminaChecked = 'checked';
				      				?>
				      				<div class="row">
				        				<div class="col-sm-2">
				          					<div class="input-group">
				            					<span class="input-group-addon" id="sizing-addon1" style="text-align:left;"><b>Sesso</b></span>
				          					</div>
				        				</div>
				            			
				        				<div class="col-sm-5">
				          					<div class="input-group">
			            						<span class="input-group-addon">
				              						<input type="radio" id="radio_maschio" onclick="stickyheaddsadaer()" name="Sesso" value="Maschio" <?php echo $maschioChecked; ?> >
				            					</span>
				            					<input type="text" class="form-control" value="Maschio" readonly>
				          					</div><!-- /input-group -->
				        				</div><!-- /.col-sm-5 -->
				      
				        				<div class="col-sm-5">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="radio" id="radio_femmina" onclick="stickyheaddsadaer()" name="Sesso" value="Femmina" <?php echo $femminaChecked; ?>>
				            					</span>
				            					<input type="text" class="form-control" value="Femmina" readonly>
				          					</div><!-- /input-group -->
				        				</div><!-- /.col-sm-5 -->
				      				</div><!-- /.row -->
				     	 			<br/>


				     	 			<!--HO USATO QUESTO MODELLO PER LO STATO MENOPAUSALE
	    							<div class="input-group">
							          <span class="input-group-addon">
							            <input type="radio" aria-label="..." id="input-2">
							          </span>
							          <input type="text" class="form-control" aria-label="...">
							        </div><!-- /input-group -->

							        <h4><b>INVIATA DA</b></h4>
							        <?php
							        	// ANCHE QUA POSSO IMMEDIATAMENTE TRADURRE IL VALORE DAL DATABASE:
							        	if(strcmp($inviata_da, 'Se stessa') == 0) $seStessaChecked = 'checked';
							        	else if(strcmp($inviata_da, 'Medico curante') == 0) $medicoCuranteChecked = 'checked';
							        	else if(strcmp($inviata_da, 'Ginecologo') == 0) $ginecologoChecked = 'checked';
							        	else if(strcmp($inviata_da, 'Altro specialista') == 0) $altroSpecialistaChecked = 'checked';
							        ?>
				        			<div class="row">
				          				<!--prova-->
				          				<div class="col-sm-3">
				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" name="InviataDa" value="Se stessa" <?php echo $seStessaChecked ?> />
			                					</span>
			              						<!--<span value="se stessa">Se stessa</span>-->
				              					<input type="text" class="form-control" value="Se stessa" readonly>
				            				</div><!-- /input-group -->

				          				</div><!-- /.col-sm-5 -->
				          			
				          			
				          				<div class="col-sm-3">
				            				

				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" name="InviataDa" value="Medico curante" <?php echo $medicoCuranteChecked; ?>/>
			                					</span>
			              						<!--<span value="medico curante">medico curante</span>-->
				              					<input type="text" class="form-control" value="Medico curante" readonly>
				            				</div><!-- /input-group -->
				          				</div><!-- /.col-sm-5 -->
				          			
				          			
				          				<div class="col-sm-3">
				          					
				            				
			              					<div class="input-group">
			              						<span class="input-group-addon">
		                							<input type="radio" name="InviataDa" value="Ginecologo" <?php echo $ginecologoChecked; ?>/>
		                						</span>
		              						
		              							<!--<input style="width:130px;" type="text" name="GinecologoDelCentro" id="GinecologoDelCentro" placeholder="Ginecologo" disabled>-->
		              							<input type="text" class="form-control" name="GinecologoDelCentro" id="GinecologoDelCentro" placeholder="Ginecologo" value="<?php if(strcmp($ginecoloco_del_centro, 'NULL')) echo $ginecoloco_del_centro;?>" disabled>
			              					</div>


				            				
				          				</div><!-- /.col-sm-5 -->
				          			
				        			
				        				<!-- inviata da -->
				        				<!--<br/><br/>-->
				          				<div class="col-sm-3">
				            				

				            				<div class="input-group">
				            					<span class="input-group-addon">
				            						<input type="radio" id="input-2" name="InviataDa" value="Altro specialista" <?php echo $altroSpecialistaChecked; ?>/>
				            					</span>
		              						
		              							<!--<input style="width:130px;" type="text" name="GinecologoEsterno" id="GinecologoEsterno" placeholder="Altro specialista" disabled>-->
			              						<input type="text" class="form-control" name="GinecologoEsterno" id="GinecologoEsterno" placeholder="Altro specialista" value="<?php if(strcmp($altro_specialista, 'NULL')) echo $altro_specialista;?>" disabled>
			              					</div>
				          				</div><!-- /.col-sm-5 -->
				          			</div>

				        			<br/>
				        			<!-- stato menopausale-->

				        			<!--
				        			<div class="input-group">
	      								<span class="input-group-addon" id="sizing-addon2">@</span>
	      								<input type="text" class="form-control" placeholder="Username" aria-describedby="sizing-addon2">
	    							</div>
	    							-->

	    							<!--HO USATO QUESTO MODELLO PER LO STATO MENOPAUSALE
	    							<div class="input-group">
							          <span class="input-group-addon">
							            <input type="radio" aria-label="..." id="input-2">
							          </span>
							          <input type="text" class="form-control" aria-label="...">
							        </div><!-- /input-group -->

							        <h4><b>STATO MENOPAUSALE</b></h4>
							        <?php
							        	// TRADUCO IMMEDIATAMENTE IL VALORE DEL DATABASE PER LO STATO MENOPAUSALE.
							        	if(strcmp($stato_menopausale, 'Premenopausa') == 0) $premenopausaChecked = 'checked';
							        	else if(strcmp($stato_menopausale, 'Perimenopausa') == 0) $perimenopausaChecked = 'checked';
							        	else if(strcmp($stato_menopausale, 'Postmenopausa spontanea') == 0) $postmenopausaSpontaneaChecked = 'checked';
							        	else if(strcmp($stato_menopausale, 'Indotta') == 0) $indottaChecked = 'checked';
							        	else if(strcmp($stato_menopausale, 'Ister-ann bil') == 0) $isterannBilChecked = 'checked';
							        	else if(strcmp($stato_menopausale, 'Ister-ann mono') == 0) $isterannMonoChecked = 'checked';
										else if(strcmp($stato_menopausale, 'Solo isterectomia') == 0) $soloIsterectomiaChecked = 'checked';
							        ?>
				        			<div class="row">
				          				<div class="col-sm-4">
				            				

				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" id="radio_premenopausa"  onclick="stickyheaddsadaer()" name="Statomenopausale" value="Premenopausa" <?php echo $premenopausaChecked; ?>/>
			              						</span>
			              						<!--<span value="Premenopausa" class="input-group-addon" id="sizing-addon2">Premenopausa</span>-->
			              						<input type="text" class="form-control" value="Premenopausa" class="input-group-addon" readonly>
				              					
				            				</div><!-- /input-group -->

				            				
				          				</div><!-- /col-sm-3-->

				          				<div class="col-sm-4">
				            				

				            			

				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" id="radio_perimenopausa"  onclick="stickyheaddsadaer()"  name="Statomenopausale" value="Perimenopausa" <?php echo $perimenopausaChecked; ?>/>
			                					</span>
			              						<!--<span value="Perimenopausa" style="height:53px;" class="form-control" readonly>Perimenopausa</span>-->
				              					<input type="text" class="form-control" value="Perimenopausa" readonly>
				            				</div><!-- /input-group -->
				          				</div><!-- /col-sm-3-->

				          				<div class="col-sm-4">
				          				

				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" id="radio_post_spontanea"  onclick="stickyheaddsadaer()" name="Statomenopausale" value="Postmenopausa spontanea" <?php echo $postmenopausaSpontaneaChecked; ?>/>
			                					</span>
			              						<!--<span value="Postmenopausa spontanea">Postmenopausa spontanea</span>-->
				              					<input type="text" class="form-control" value="Postmenopausa spontanea" readonly>
				            				</div><!-- /input-group -->
				          				</div><!-- /col-sm-3-->

				          				
				        			</div><!--/row-->
				    				<br/>
				        			<!-- continua stato menopausale -->
				        			
				        			<div class="row">
				          				<div class="col-sm-3">
				          					
				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input  type="radio" id ="radio_indotta"  onclick="stickyheaddsadaer()" name="Statomenopausale" value="Indotta" <?php echo $indottaChecked; ?>/>
			                					</span>
			              						<!--<span style="height:53px;" class="form-control" value="Indotta(farmaci-radioterapia)" readonly>Indotta(farmaci-radioterapia)</span>-->
				              					<input type="text" class="form-control" value="Indotta (farmaci-radioterapia)" readonly>
				            				</div><!-- /input-group -->
				            				
				            				
											
				          				</div><!-- /col-sm-3-->

				          				<div class="col-sm-3">
				          					
				            				
				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" id ="radio_ister_ann_bil"  onclick="stickyheaddsadaer()" name="Statomenopausale" value="Ister-ann bil" <?php echo $isterannBilChecked; ?>/>
			                					</span>
				              					<!--<span value="Ister-ann bill" style="height:53px;" class="form-control" readonly>Ister-ann bil</span>-->
				              					<input type="text" class="form-control"value="Ister-ann bil" readonly>
				            				</div><!-- /input-group -->

				            				
				          				</div><!--/col-sm-4-->

				          				<div class="col-sm-3">

				          					
				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" id = "radio_ister_ann_mono"  onclick="stickyheaddsadaer()" name="Statomenopausale" value="Ister-ann mono" <?php echo $isterannMonoChecked; ?>/>
			                					</span>
			              						<!--<span value="Ister-ann mono">Ister-ann mono</span>-->
				              					<input type="text" class="form-control" value="Ister-ann mono" readonly>
				            				</div><!-- /input-group -->
				          				</div><!--/col-sm-4-->

				          				<div class="col-sm-3">

				   
				            				<div class="input-group">
				              					<span class="input-group-addon">
			                						<input type="radio" id="radio_solo_isterectomia" onclick="stickyheaddsadaer()" name="Statomenopausale" value="Solo isterectomia" <?php echo $soloIsterectomiaChecked; ?>/>
			                					</span>
				              					<!--<span value="Solo isterectomia">Solo isterectomia</span>-->
				              					<input type="text" class="form-control" value="Solo isterectomia" readonly>
				            				</div><!-- /input-group -->
				          				</div><!--/col-sm-4-->

				          				<!-- mi serve per tenere le radio allineate -->
				          				
				        			</div>

				        			<br/><br/>
				        			<!--U.M.-->
				        			<h4><b>U.M.</b></h4>
				        			<!-- se è presente il campo data non posso cliccare sulle option -->
				        			
				      				<div class="row">
				        				
										
										<!-- non serve più-->
				        				<div class="col-sm-3">
				        					<div class="input-group">
				        						
				        						<span class="input-group-addon">
				        							Anno U.M.
				        						</span>
				        						
				        						<input type="text"  class="form-control" name="ultima_mestruazione" id="UltimaMestruazione"  onchange="stickyheaddsadaer()" value="<?php if($um != null) echo $um; ?>" placeholder="Anno">
				        						
				        					</div>
				        				</div>
				        				
				        				

				        				<div class="col-sm-4">
				          					<div class="input-group">

				          						<span class="input-group-addon" id="prova">
				        							Età menopausa
				        						</span>
				            					
				            					<input type="text" class="form-control" id="etamenopausa" name="etamenopausa" value="<?php if($eta_menopausa != null) echo $eta_menopausa;   ?>"placeholder="Età  Menopausa">
				            					<!--<input type="text" class="form-control" value="> 4 anni: Età menopausa" readonly>-->
				          						
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-3-->
				      				</div><!--/row-->

				      		
									<br/>
				      				<h4><b>TERAPIE OSTEOPROTETTIVE</b></h4>
				      				<?php 
				      					if(strcmp($stato_terapia, 'Mai') == 0) $statoMaiChecked = 'checked';
				      					else if(strcmp($stato_terapia, 'In atto') == 0) $statoInAttoChecked = 'checked';
				      					else if(strcmp($stato_terapia, 'Sospesa') == 0) $statoSospesaChecked = 'checked';
				      				?>
				      				<div class="row">
				        				<div class="col-sm-8">
				          					<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				              						<b>Stato</b>
				            					</span>
				            					
				            					<span class="input-group-addon">
					          						Mai
					          						<input type="radio" id="stato_mai" onclick="stickyheaddsadaer()" name="stato_terapia" value="Mai" <?php echo $statoMaiChecked; ?>/>

					          					</span>
				            					<span class="input-group-addon" style="text-align:left;">
				            						In atto
				            						<input type="radio" id="stato_inatto" onclick="stickyheaddsadaer()" name="stato_terapia" value="In atto" <?php echo $statoInAttoChecked; ?>/>
				      							</span>

				      							<span class="input-group-addon" style="text-align:left;">
				            						Sospesa
				            						<input type="radio" id="stato_sospesa" onclick="stickyheaddsadaer()" name="stato_terapia" value="Sospesa" <?php echo $statoSospesaChecked; ?>/>
				          						</span>

				          						<span class="input-group-addon">
					            					
					            					da quanto
					          					</span>
				          
					          					
					            					
					            				<input type="text"  class="form-control" id="da_quanto" placeholder="Anni" name="da_quanto" value="<?php if($sospesa_da != null) echo $sospesa_da; ?>"  disabled>
				          					</div><!--/input-group-->

				        				</div><!--/col-sm-4-->

				        				<!--JAVASCRIPT CHE MI SERVE PER SETTARE A FALSO IL DISABLED DELLA CHECK NEL CASO LO STATO È IN ATTO O SOSPESA-->
				        				<script type="text/javascript">
											$(document).ready(function(){
												if($('#stato_inatto').prop('checked')){
													$('#Ormonale').prop('disabled', false)
													/*
													if($('#Ormonale').prop('input[name=Ormonale]:checked')){
														$('#TipoOrmonale').prop('disabled',false)													
													}
													else{
														$('#TipoOrmonale').prop('disabled',true)
													}
													*/

													//$('#TipoOrmonale').prop('disabled',false)
													//$('#DurataOrmonale').prop('disabled',false)
													$('#Osteoprotettiva').prop('disabled',false)
													$('#VitaminaD').prop('disabled',false)
													$('#TipoOsteoprotettiva').prop('disabled',false)
													$('#TipoVitaminaD').prop('disabled',false)
													$('#AltroTerapie').prop('disabled',false)
													$('#ComplianceAllaTerapia').prop('disabled',false)
													//$('#ComplianceAllaTerapia').prop('checked',true)
													//$('#text_area_causesecondarie').prop('disabled', false)
													//$('#Aggiungi_Cause_Secondarie').prop('disabled', false)
												}

												if($('#stato_sospesa').prop('checked')){
													$('#da_quanto').prop('disabled',false)
													$('#Ormonale').prop('disabled', false)
													//$('#TipoOrmonale').prop('disabled',false)
													$('#Osteoprotettiva').prop('disabled',false)
													$('#VitaminaD').prop('disabled',false)
													//$('#TipoOsteoprotettiva').prop('disabled',false)
													$('#AltroTerapie').prop('disabled',false)
													$('#ComplianceAllaTerapia').prop('disabled',false)
													//$('#ComplianceAllaTerapia').prop('checked',true)
													//$('#text_area_causesecondarie').prop('disabled', false)
													//$('#Aggiungi_Cause_Secondarie').prop('disabled', false)
												}
											});
										</script>

										
										
											
										

										<script type="text/javascript">
										//JAVASCRIPT CHE RENDE DISPONILI LE SELECT PER TIPOORMONALE E TIPOOSTEOPROTETTIVA E LE RISPETTIVE
										//DURATE NEL CASO È STATA CLICCACA LA CORRISPETTIVA CHECK
											$(document).ready(function(){

												if($('#Ormonale').prop('checked')){
													$('#TipoOrmonale').prop('disabled',false)
													$('#DurataOrmonale').prop('disabled',false)
												}
												if($('#Osteoprotettiva').prop('checked')){
													$('#TipoOsteoprotettiva').prop('disabled',false)
													$('#DurataOsteoprotettiva').prop('disabled',false)
												}
												if($('#VitaminaD').prop('checked')){
													$('#TipoVitaminaD').prop('disabled',false)
													$('#DurataVitaminaD').prop('disabled',false)
												}
												if($('#AltroTerapie').prop('checked')){
													$('#ValueAltroTerapie').prop('disabled',false)
												}
											});

										</script>


				        				<script>

							  				$('#myForm input').on('change', function() {
													var val = $('input[name=stato_terapia]:checked','#myForm').val(); 
													//alert(val)
												if(val == "Mai"){
													$('#Ormonale').prop('disabled',true);
													$('#Osteoprotettiva').prop('disabled',true);

													$('#Ormonale').prop('checked',false);
													$('#Osteoprotettiva').prop('checked',false);
													
													//RESETTO TUTTI I CAMPI DI OSTEOPROTETTIVA SE CLICCO SU MAI
													$('#text_area_osteoprotettiva').empty();
													tipi = {};

													$('#TipoOsteoprotettiva').prop('selectedIndex',0);
													$('#DurataOsteoprotettiva').prop('value','')

													$('#inserisci2').prop('disabled',true);
													$('#CancellaOsteoprotettiva').prop('disabled',true);
													//FINE OSTEOPROTETTIVA

													//RESETTO TUTTI I CAMPI DI ORM.SOST. SE CLICCO SU MAI
													
													$('#text_area_orm_sost').empty();
													tipi2 = {};
													
													$('#TipoOrmonale').prop('selectedIndex',0)
													$('#DurataOrmonale').prop('value','')

													$('#inserisci').prop('disabled',true);
													$('#Cancella').prop('disabled',true);
													//$('#CancellaOrmonale').prop('disabled',true);
													//FINE ORM.SOST.

													//VITAMINA D
													$('#VitaminaD').prop('disabled',true);
													$('#VitaminaD').prop('checked',false);
													$('#text_area_vitamina_d').empty();
													vitamina_d = {};

													$('#TipoVitaminaD').prop('selectedIndex',0);
													$('#DurataVitaminaD').prop('value','');

													$('#InserisciVitaminaD').prop('disabled',true);
													$('#CancellaVitaminaD').prop('disabled',true);
													

													$('#AltroTerapie').prop('disabled',true);
													$('#AltroTerapie').prop('checked',false)
													$('#ValueAltroTerapie').prop('value','');

													$('#ComplianceAllaTerapia').prop('disabled',true);
													//$('#ComplianceAllaTerapia').prop('checked',false);
												}
												else if (val == "In atto"){
														
													$('#Ormonale').prop('disabled',false);
													$('#Osteoprotettiva').prop('disabled',false);
													$('#VitaminaD').prop('disabled',false);
													$('#TipoVitaminaD').prop('disabled',false);
													$('#TipoOrmonale').prop('disabled',false);
													$('#AltroTerapie').prop('disabled',false);
													$('#ComplianceAllaTerapia').prop('disabled',false)
													//$('#ComplianceAllaTerapia').prop('checked',true)
													//$('#inserisci2').prop('disabled',false);
												}
												else if(val == "Sospesa"){
													$('#Ormonale').prop('disabled',false);
													$('#Osteoprotettiva').prop('disabled',false);
													$('#VitaminaD').prop('disabled',false);
													
													$('#AltroTerapie').prop('disabled',false);
													$('#ComplianceAllaTerapia').prop('disabled',false)
													//$('#ComplianceAllaTerapia').prop('checked',true)
												}
												
												
											});

										</script>
				        				<br/><br/><br/>
				      				</div> <!--/row-->
				        			
				        				
					        		
									<!--ORMONALE SOSTITUTIVA/C.O.-->
									<?php
										if($ormonale == 1) $ormonaleChecked = 'checked';
			      						$pezzi_ormonale = explode("\n", $valori_ormonale);
			      						//$pezzi_ormonale1 = explode("\n", $valori_ormonale);
									?>
									
									<div class="row">
				        				<div class="col-sm-5">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="Ormonale" name="Ormonale" onchange="stickyheaddsadaer()" value="true" <?php echo $ormonaleChecked; ?> disabled> <!--deve partire da disabled-->
				            					</span>
				            					<!--<input type="text" class="form-control" value="Orm.sost. / C.O." readonly>-->
				            					<span class="form-control"  readonly>Orm.sost. / C.O.</span>
				          					</div><!--/input-group-->
				          					<br/>
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					<!--<select id="TipoOsteoprotettiva" name="TipoOsteoprotettiva" class="form-control" disabled>   -->
				            					<select class="form-control" name="TipoOrmonale" id="TipoOrmonale" disabled>
				            						<option value="">Tipo</option>
				            						<?php
                                                        //stiamo seleziondo da GESTIONE INTERNA!!!
				            							$ormonale = "SELECT NOME FROM GestioneInterna WHERE TIPO = 'Orm.Sost./C.O.'";
				            							$res = mysqli_query($mysqli,$ormonale);
				            							while($row = mysqli_fetch_assoc($res)):
				            						?>
				            							<option><?php echo $row[NOME]; ?></option>
				            						<?php endwhile; ?>
				            					</select>

				          					<!--</div>/input-group-->
				          					<!--<div class="input-group">-->
				            					<span class="input-group-addon" style="visibility:hidden"></span>
				            					<span class="input-group-addon">
				              						Durata in anni
				            					</span>
				            					<input type="text" autocomplete="off" style="width:115px;" class="form-control" placeholder="Durata in anni" id="DurataOrmonale" name="DurataOrmonale" disabled>
				          						
				          						
				          					</div>                                                                                        <!--rimosso onclick di questa e quella sotto perchè  onclick="app2(), onclick="remove2() non esistono-->
				          					<input type="button" value="Aggiungi" class="dimensione" name="inserisci" id="inserisci"  disabled>
			        						<input type="button"  value="Cancella" class="dimensione" name="CancellaOrmonale" id="Cancella"  disabled>

				          					<!--<input type="button" name="inserisci" id="inserisci" value="Aggiungi" disabled/>-->
				          					
				        				</div><!--/col-sm-4-->

				        				<div class="col-sm-7">
				        					<!--<div><textarea class="text_area_orm_sost" name="text_area_orm_sost" id="text_area_orm_sost"></textarea></div>-->
				        					<?php $i = 0; ?>
				        					<select style="height:100px;width:500px" size="5" class="text_area_orm_sost" name="text_area_orm_sost" id="text_area_orm_sost" onchange="stickyheaddsadaer()" >
				        						<?php  
				        						while ($pezzi_ormonale[$i] != null){ if($pezzi_ormonale[$i] != 'NULL')  echo "<option> $pezzi_ormonale[$i] </option>"; $i = $i + 1;} 
				        						?>
				        					</select>
				        					<?php $i = 0; ?>
				        					<textarea style="visibility:hidden;" id="valori_ormonale" class="valori_ormonale" name="valori_ormonale" >
				        						<?php 
				        							while ($pezzi_ormonale[$i] != null) {if($pezzi_ormonale[$i] != 'NULL') echo "$pezzi_ormonale[$i]"."\n"; $i = $i + 1; }
				        						?>
				        					</textarea>
				        				</div>
				        				
				        			</div>
								
			        				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI ORMONALE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI2{}, CONTENENTE TUTTI I TIPI INSERITI
			        					var tipi2 = {};
			        					$('#inserisci').bind('click',function(){
			        						var x = document.getElementById("text_area_orm_sost");
        									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#TipoOrmonale option:selected").text();
											
											var durata = $("#DurataOrmonale").val();
											var st = tipo + ","+ durata + " anni";

											if(tipi2[st]){
												alert("Tipo già inserito");
											}
											else
                                            {
												$("textarea#valori_ormonale").val($("textarea#valori_ormonale").val() + st+"\n");

												tipi2[st] = true;
												option.text = st;
												x.add(option);
											}
											//quando schiaccia aggiungi() su Orm.sost. / C.O.
											stickyheaddsadaer();
											
										});

										$('#Cancella').bind('click',function(){
											var x = document.getElementById("text_area_orm_sost");
											//var tipo = x.split()
											var i;
											var y = document.getElementById("valori_ormonale");
											y.value = y.value.replace(x.value, "");

  											for (i = x.length - 1; i>=0; i--) {
    											if (x.options[i].selected) {
    												
      												var sel = $("#text_area_orm_sost option:selected").text(); //prendo il valore del tipo che voglio cancellare
      												//alert(v);
      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
      												
      												tipi2[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
      												x.remove(i); //lo rimuovo come option
      												$('#Cancella').prop('disabled',true);
    											}
  											}
											stickyheaddsadaer();

										});

			        				</script>

			        				<script>
            							//SCRIPT PER RENDERE DISPONIBILE IL PULSANTE CANCELLA SOLO SE SI È SELEZIONATO QUALCOSA DALLA SELECT, DOPO AVERLO CANCELLATO, IL PULSANTE TORNERÀ DISABILITATO
                						$(document).ready(function(){
					                        //$('cancella_ormonale').prop('disabled',true);
					                        $("#text_area_orm_sost").bind('change',function(e) {
					                            //return false;
					                            //e.stopPropagation();
					                        
					                            //alert($('#Ormonale option:selected').val());
					                            if ($('#text_area_orm_sost option:selected') != "") {
					                                $('#Cancella').prop("disabled", false);       
					                            } 
					                            else {
					                                $('#Cancella').prop("disabled", true)
					                            }
					                            
					                        });
					                    });
					                
					                </script>

				        				
				      				<br/>
				      				<!-- continua terapie protettive -->
				      				
				      				<!--OSTEOPROTETTIVE-->
				      				<?php
			      						$pezzi_osteoprotettiva = explode("\n", $valori_osteoprotettiva);
									?>
								
									<div class="row">
				        				<div class="col-sm-5">
				          					<div class="input-group">
				            					<span class="input-group-addon">                                                           <!--solo query-->
				              						<input type="checkbox" id="Osteoprotettiva" name="Osteoprotettiva" onchange="stickyheaddsadaer()" value="true" <?php if( $osteoprotettiva == 1) echo "checked='checked'"; ?> disabled> <!--deve partire da disabled-->
				            					</span>
				            					<!--<input type="text" class="form-control" value="Orm.sost. / C.O." readonly>-->
				            					<span class="form-control"  readonly>Osteoprotettiva specifica</span>
				          					</div><!--/input-group-->
				          					<br/>
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					
				            					<select id="TipoOsteoprotettiva" name="TipoOsteoprotettiva" class="form-control" disabled>
				            						<option value="">Tipo</option>
				            						<?php
				        								$osteoprotettiva = "SELECT NOME FROM GestioneInterna WHERE TIPO='Osteoprotettiva specifica'";
				        								//echo $osteoprotettiva;
			        									$res = mysqli_query($mysqli,$osteoprotettiva);
				        								
		        										while($row = mysqli_fetch_assoc($res)):


				        							?>
				        								<option><?php echo $row[NOME]; ?></option>

				        							<?php endwhile; ?>
				            					</select>

				          					<!--</div>/input-group-->
				          					<!--<div class="input-group">-->
				            					<span class="input-group-addon" style="visibility:hidden"></span>
				            					<span class="input-group-addon">
				              						Durata in anni
				            					</span>
				            					<input type="text" class="form-control" autocomplete="off" style="width:115px;" placeholder="Durata in anni" id="DurataOsteoprotettiva" name="DurataOsteoprotettiva" disabled>
				          						
				          						
				          					</div><!--/input-group-->
				          					<input type="button" value="Aggiungi" class="dimensione" name="inserisci2" id="inserisci2" disabled>
				        					<input type="button" value="Cancella" class="dimensione" id="CancellaOsteoprotettiva" name="CancellaOsteoprotettiva" disabled>
				          					<!--<input type="button" name="inserisci" id="inserisci" value="Aggiungi" disabled/>-->
				          					
				        				</div><!--/col-sm-4-->

				        				<div class="col-sm-7">
				        					<!--<div><textarea class="text_area_orm_sost" name="text_area_orm_sost" id="text_area_orm_sost"></textarea></div>-->
				        					<?php $i = 0; ?>
				        				
				        					<!--<textarea class="text_area_osteoprotettiva" name="text_area_osteoprotettiva" id="text_area_osteoprotettiva" disabled></textarea>-->
				        					<select style="height:100px;width:500px;" size="5" class="text_area_osteoprotettiva" name="text_area_ostoprotettiva" id="text_area_osteoprotettiva">
				        						<?php  while($pezzi_osteoprotettiva[$i] != null){ if($pezzi_osteoprotettiva[$i] != 'NULL') echo "<option> $pezzi_osteoprotettiva[$i] </option>"; $i=$i+1;} ?>

				        					</select>
				        					<textarea style="visibility:hidden;" id="valori_osteoprotettiva" name="valori_osteoprotettiva"><?php $j = 0; while($pezzi_osteoprotettiva[$j] != null){ echo $pezzi_osteoprotettiva[$j]."\n"; $j=$j+1; } ?></textarea>
				        					<!-- TEXT AREA PER TENERMI SALVATI I TIPI CHE SELEZIONO, MI SERVIRÀ PER PASSARLI POI AL DATABASE -->
				        				
				        				</div>
				        				
				        			</div>
				  					
				        				
				        				
				        				<script>
				        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI OSTEOPROTETTIVE, 
				        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

				        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI{}
				        					var tipi = {};
				        					$('#inserisci2').bind('click',function(){
				        						var x = document.getElementById("text_area_osteoprotettiva");
	        									//alert($("#TipoOsteoprotettiva option:selected").text());
												var option = document.createElement("option");
												var tipo = $("#TipoOsteoprotettiva option:selected").text();
												
												var durata = $("#DurataOsteoprotettiva").val();
												var st = tipo + ","+ durata + " anni";
												//alert("aggiungi osteoprotettiva:"+st);
												if(tipi[st]){
													alert("Tipo già inserito");
												}
												else{
													//$('#osteoprotettiva').append(st+"\n");
													$("textarea#valori_osteoprotettiva").val($("textarea#valori_osteoprotettiva").val() + st+"\n");

													tipi[st] = true;
													option.text = st;
													x.add(option);
													
													
												}

												stickyheaddsadaer();
												
											});
				        					/*
											$.ajax({ type: "GET",   
	     										url: "referti2-md.php",

	     										success : function(text)
	     										{
	         										$('#osteoprotettiva').append(text+"\n");
	     										}
											});
											*/

											

				        				</script>


				        				<script>
				        					$('#CancellaOsteoprotettiva').bind('click',function(){

												var x = document.getElementById("text_area_osteoprotettiva");
												//var tipo = x.split()
												var i;

												var y = document.getElementById("valori_osteoprotettiva");
												y.value = y.value.replace(x.value, "");
												//y.value.remove(x.value);
	  											for (i = x.length - 1; i>=0; i--) {
	    											if (x.options[i].selected) {
	    												
	      												var sel = $("#text_area_osteoprotettiva option:selected").text(); //prendo il valore del tipo che voglio cancellare
	      												//alert("cancella osteo:"+sel);
	      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
	      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
	      												
	      												tipi[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
	      												//alert(tipi[sel]);
	      												x.remove(i); //lo rimuovo come option
	      												$('#CancellaOsteoprotettiva').prop('disabled',true);

	      												
	      												
	    											}
	  											}

												  stickyheaddsadaer();
											});


				        				</script>


				        				<script>
                							//SCRIPT PER RENDERE DISPONIBILE IL PULSANTE CANCELLA SOLO SE SI È SELEZIONATO QUALCOSA DALLA SELECT, DOPO AVERLO CANCELLATO, IL PULSANTE TORNERÀ DISABILITATO
                    						$(document).ready(function(){
						                        //$('cancella_ormonale').prop('disabled',true);
						                        $("#text_area_osteoprotettiva").bind('change',function(e) {
						                            //return false;
						                            //e.stopPropagation();
						                        
						                            //alert($('#Ormonale option:selected').val());
						                            if ($('#text_area_osteoprotettiva option:selected') != "") {
						                                $('#CancellaOsteoprotettiva').prop("disabled", false);       
						                            } 
						                            else {
						                                $('#CancellaOsteoprotettiva').prop("disabled", true)
						                            }
						                            
						                        });
						                    });
						                
						                </script>
				      				
				      				
				      				
				      				<br/>

				      				<!--VITAMINA D SUPPLEMENTAZIONE DI TERAPIE OSTEOPROTETTIVE-->
				      				
									<?php
										if($vitamina_d == 1) $vitamina_dChecked = 'checked';
			      						$pezzi_vitamina_d = explode("\n", $valori_vitamina_d);

			      						//$pezzi_ormonale1 = explode("\n", $valori_ormonale);
									?>
									
									<div class="row">
				        				<div class="col-sm-5">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="VitaminaD" name="VitaminaD" value="true" onchange="stickyheaddsadaer()" <?php echo $vitamina_dChecked; ?> disabled> <!--deve partire da disabled-->
				            					</span>
				            					<!--<input type="text" class="form-control" value="Orm.sost. / C.O." readonly>-->
				            					<span class="form-control"  readonly>Vitamina D supplementazione</span>
				          					</div><!--/input-group-->
				          					<br/>
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					<!--<select id="TipoOsteoprotettiva" name="TipoOsteoprotettiva" class="form-control" disabled>   -->
				            					<select class="form-control" name="TipoVitaminaD" id="TipoVitaminaD" disabled>
				            						<option value="">Tipo</option>
				            						<?php
				            							$vitamina_d = "SELECT NOME FROM GestioneInterna WHERE TIPO = 'Vitamina D Supplementazione'";
				            							$res = mysqli_query($mysqli,$vitamina_d);
				            							while($row = mysqli_fetch_assoc($res)):
				            						?>
				            							<option><?php echo $row[NOME]; ?></option>
				            						<?php endwhile; ?>
				            					</select>

				          					<!--</div>/input-group-->
				          					<!--<div class="input-group">-->
				            					<!-- DURATA VITAMINA D
				            					<span class="input-group-addon" style="visibility:hidden"></span>
				            					<span class="input-group-addon">
				              						Durata in anni
				            					</span>
				            					<input type="text" autocomplete="off" style="width:115px;" class="form-control" placeholder="Durata in anni" id="DurataVitaminaD" name="DurataVitaminaD" disabled>
				          						-->
				          						
				          					</div><!--/input-group-->
				          					<input type="button" value="Aggiungi" class="dimensione" name="InserisciVitaminaD" id="InserisciVitaminaD" onclick="app_vitamina_d();" disabled>
			        						<input type="button"  value="Cancella" class="dimensione" name="CancellaVitaminaD" id="CancellaVitaminaD" onclick="remove_vitamina_d();" disabled>

				          					<!--<input type="button" name="inserisci" id="inserisci" value="Aggiungi" disabled/>-->
				          					
				        				</div><!--/col-sm-4-->

				        				<div class="col-sm-7">
				        					<!--<div><textarea class="text_area_orm_sost" name="text_area_orm_sost" id="text_area_orm_sost"></textarea></div>-->
				        					<?php $i = 0; ?>
				        					<select style="height:100px;width:500px" size="5" class="text_area_vitamina_d" name="text_area_vitamina_d" id="text_area_vitamina_d">
				        						<?php  
				        						while ($pezzi_vitamina_d[$i] != null){ if($pezzi_vitamina_d[$i] != 'NULL')  echo "<option> $pezzi_vitamina_d[$i] </option>"; $i = $i + 1;} 
				        						?>
				        					</select>
				        					<?php $i = 0; ?>
				        					<textarea style="visibility:hidden;" id="valori_vitamina_d" class="valori_vitamina_d" name="valori_vitamina_d" >
				        						<?php 
				        							while ($pezzi_vitamina_d[$i] != null) {if($pezzi_vitamina_d[$i] != 'NULL') echo "$pezzi_vitamina_d[$i]"."\n"; $i = $i + 1; }
				        						?>
				        					</textarea>
				        				</div>
				        				
				        			</div>

				        			<script>
				        				$(document).ready(function(){
				        					if($('#VitaminaD').prop('checked')){
				        						$('#InserisciVitaminaD').prop('disabled',false);
				        						$('#CancellaVitaminaD').prop('disabled',false);
				        					}

				        				});
				        			</script>
								
			        				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI ORMONALE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI2{}, CONTENENTE TUTTI I TIPI INSERITI
			        					var vitamina_d = {};
			        					function app_vitamina_d(){
			        						var x = document.getElementById("text_area_vitamina_d");
        									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#TipoVitaminaD option:selected").text();
											//alert("tipo:"+tipo);
											var durata = $("#DurataVitaminaD").val();
											var st = tipo + ","+ durata + " anni";
											//alert("st:"+st);
											if(vitamina_d[tipo]){
												alert("Tipo già inserito");
											}
											else if(tipo == "Tipo"){
												alert("Selezionare un tipo");
											}
											else{
												$("textarea#valori_vitamina_d").val($("textarea#valori_vitamina_d").val() + tipo+"\n");

												vitamina_d[tipo] = true;
												option.text = tipo;
												x.add(option);
											}

											stickyheaddsadaer();
											
										}

										function remove_vitamina_d(){
											var x = document.getElementById("text_area_vitamina_d");
											//var tipo = x.split()
											var i;
											var y = document.getElementById("valori_vitamina_d");
											y.value = y.value.replace(x.value, "");

  											for (i = x.length - 1; i>=0; i--) {
    											if (x.options[i].selected) {
    												
      												var sel = $("#text_area_vitamina_d option:selected").text(); //prendo il valore del tipo che voglio cancellare
      												var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
      												var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
      												
      												vitamina_d[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
      												x.remove(i); //lo rimuovo come option
      												$('#CancellaVitaminaD').prop('disabled',true);
    											}
  											}

											  stickyheaddsadaer();
										}

			        				</script>

			        				<script>
            							//SCRIPT PER RENDERE DISPONIBILE IL PULSANTE CANCELLA SOLO SE SI È SELEZIONATO QUALCOSA DALLA SELECT, DOPO AVERLO CANCELLATO, IL PULSANTE TORNERÀ DISABILITATO
                						$(document).ready(function(){
					                        //$('cancella_ormonale').prop('disabled',true);
					                        $("#text_area_vitamina_d").bind('change',function(e) {
					                            //return false;
					                            //e.stopPropagation();
					                        
					                            //alert($('#Ormonale option:selected').val());
					                            if ($('#text_area_vitamina_d option:selected') != "") {
					                                $('#CancellaVitaminaD').prop("disabled", false);       
					                            } 
					                            else {
					                                $('#CancellaVitaminaD').prop("disabled", true)
					                            }
					                            
					                        });
					                    });
					                
					                </script>

				        				
				      				<br/>
				      				<!--FINE VITAMINA D SUPPLEMENTAZIONE DI TERAPIE OSTEOPROTETTIVE-->

				      				<div class="row">
				        				<div class="col-sm-5">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="AltroTerapie" onchange="stickyheaddsadaer()" name="TerapieProtettive" value="true" <?php if( $altro_terapie == 1) echo "checked='checked'"; ?> disabled>
				            					</span>
				            					<span class="input-group-addon" value="Altro">Altro</span>
				            					<!--<input type="text" class="form-control" value="Altro" readonly>-->
				            					<input class="form-control" type="text" placeholder="Altro" id="ValueAltroTerapie" onchange="stickyheaddsadaer()" name="ValueAltroTerapie" value="<?php if($value_altro_terapia != 'NULL') echo $value_altro_terapia;?>" disabled>
				          					</div><!--/input-group-->

				        				</div><!--/col-sm-4-->

				      				</div> <!--/row-->
				      				<br/>
				      				<div class="row">
				        				<div class="col-sm-5">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="ComplianceAllaTerapia" name="ComplianceAllaTerapia" onchange="stickyheaddsadaer()" <?php if($compliance_alla_terapia == 1) echo "checked='checked'"; ?> disabled>
				            					</span>
				            					<input type="text" class="form-control" value="Compliance alla terapia >= 75%" readonly>
				            					
				          					</div><!--/input-group-->

				        				</div><!--/col-sm-4-->

				      				</div> <!--/row-->

				  					<br/>
				      				<h4><b>FATTORI DI RISCHIO PER FRATTURA( FRAX E DeFRA)</b></h4>
				      				<?php         
				        				/*
				        				$peso1 = "SELECT WEIGHT FROM ScanAnalysis WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql'";
				        				//echo $peso;
				        				$res = mysqli_query($mysqli, $peso1);
				        				if(!$res) printf("Errorcode: %d\n", $mysqli->errno);
				        				$row = mysqli_fetch_assoc($res);
				        				$peso = $row[WEIGHT];
				        				//echo $peso1;
				      				?>

				      				<?php
				        				$altezza = "SELECT HEIGHT FROM ScanAnalysis WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql'";
				        				$res = mysqli_query($mysqli, $altezza);
				        				if(!$res) printf("Errorcode: %d\n", $mysqli->errno);
				        				$row = mysqli_fetch_assoc($res);
				        				$altezza = $row[HEIGHT];
				         
				      				?>

				      				<?php
			        				
			          					$bmi1 = "SELECT BMI FROM ScanAnalysis WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql'"; 
			          					//echo $bmi;
			          					$res = mysqli_query($mysqli,$bmi1);
			          					if(!$res) printf("Errorcode123: %d\n", $mysqli->errno);
			          					$row = mysqli_fetch_assoc($res);
			          					$bmi = $row[BMI];
			          					*/
			        				
			        				?>
			        				<?php //echo $peso; ?>
				      				<div class="row">
				        				<div class="col-sm-3">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<b>Peso(Kg)</b>
				            					</span>
				            					<input type="text" class="form-control" id="Peso" onchange = "stickyheaddsadaer()" name="Peso" value="<?php echo $peso;?>">
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-3-->

				        				<div class="col-sm-3">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<b>Altezza(cm)</b>
				            					</span>
				            					<input type="text" class="form-control" id="Altezza" onchange = "stickyheaddsadaer()" name="Altezza" value="<?php echo $altezza;?>">
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-3-->

				        				<div class="col-sm-3">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<b>BMI(rischio se BMI < 19)</b>
				            					</span>
				            					<input type="text" class="form-control" style="width:70px;" id="BMI" name="BMI" value="<?php echo $bmi; ?>" readonly>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-3-->
				      				</div><!--row-->
				      				<div id="displaybmi"></div>



				      				
				      				<!-- continua fattori di rischio -->
				      				<br/>
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="FratturaFragilitaVertebrosa" name="FratturaFragilitaVertebrosa" onchange="stickyheaddsadaer()" <?php if($frattura_fragilità_vertebrosa == 1) echo "checked='checked'"  ?>/>
				            					</span>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
				        				<div class="col-sm-5">
				          					<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				          							<b>Frattura da fragilità vertebrale/femorale (> 50 a)</b>
				        						</span>

				        						<span class="input-group-addon">
				        							<b>Vertebre</b>
				          							<span class="input-group-addon">
				            							1
				            							<input type="radio" id="FratturaVertebreUna" name="FratturaVertebre" value="1" onchange="stickyheaddsadaer()" <?php if(strcmp($vertebre, "1")==0) echo "checked='checked'";  ?> disabled>
				          							</span>
				          							<span class="input-group-addon">
				            							più di 1
				           	 							<input type="radio" id="FratturaVertebrePiuDiUna" name="FratturaVertebre" value="piu di 1" onchange="stickyheaddsadaer()" <?php if(strcmp($vertebre, "piu di 1")==0) echo "checked='checked'";  ?> disabled>
				          							</span>
				          						</span>

			          							<span class="input-group-addon">
			        								<b>Femore</b>
			          								<span class="input-group-addon">
			            								1
			            								<input type="radio" id="FratturaFemoreUna" name="FratturaFemore" onchange="stickyheaddsadaer()" value="1" disabled <?php if(strcmp($femore, "1")==0) echo "checked='checked'";  ?> disabled>
			          								</span>
			          								<span class="input-group-addon">
			            								più di 1
			           	 								<input type="radio" id="FratturaFemorePiuDiUna" name="FratturaFemore" value="piu di 1" onchange="stickyheaddsadaer()" <?php if(strcmp($femore, "piu di 1")==0) echo "checked='checked'";  ?> disabled>
	          										</span>
			          							</span>
				          						
				          						<script type="text/javascript">
				          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
				          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
							      					$(document).ready(function(){
							      					
							      						var val = $('input[name=FratturaFragilitaVertebrosa]:checked','#myForm').val();
							      						if(val != null){
							      							
							      							$('#FratturaVertebreUna').prop('disabled',false)
							      							$('#FratturaVertebrePiuDiUna').prop('disabled',false)

							      							$('#FratturaFemoreUna').prop('disabled',false)
							      							$('#FratturaFemorePiuDiUna').prop('disabled',false)
							      						}
							      					});

				      							</script>
				          						


				          					</div><!--/input-group-->
				        				</div><!--/col-sm-7-->

				        				
				      				</div><!--/row-->


				      				


				      				<br/>
				      				<!--continua fattori di rischio-->
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">

				            						<input type="checkbox" id="PregresseFratture1" name="PregresseFratture1" onchange="stickyheaddsadaer()" <?php if($fratture_siti_diversi == 1) echo "checked='checked'" ?>/>
				            					</span>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
				        				<div class="col-sm-11">
				          					<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				              						<b>Fratture da fragilità in siti diversi (> 50 a)</b>
				            					</span>																														
				            					<input type="text" id="PregFratt" name="PregresseFratture" placeholder="Pregresse fratture" class="form-control" value="<?php if($pregresse_fratture != 'NULL') echo $pregresse_fratture;  ?>" disabled>
				          					</div><!--/input-group-->
				        				</div><!--/col-sm-7-->
				        				
				        				<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=PregresseFratture1]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#PregFratt').prop('disabled',false)
					      							
					      						}
					      					});

		      							</script>

				        				
				      				</div><!--/row-->

				      				

				      				

				      				<br/>
			      					<!--continua fattori di rischio-->
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="FamiliaritaperFrattura" name="FamiliaritaperFrattura" onchange="stickyheaddsadaer()"  <?php if($familiarita_per_frattura == 1) echo "checked='checked'" ?>/>

				            					</span>
				         	 				</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
				        			
				        				<div class="col-sm-7">
				          					<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				              						<b>Familiarità per frattura al femore o vertebre (madre,padre)</b>
				            					</span>
				          					</div><!--/input-group-->
				        				</div><!--/col-sm-7-->

				        				

				        				
				      				</div><!--/row-->
				      				<br/>
				        			<!--continua fattori di rischio--> 
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="Abusofumo1" name="Abusofumo1" onchange="stickyheaddsadaer()" <?php if($abuso_fumo == 1) echo "checked='checked'" ?>/>
				            					</span>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
				        			
				        				<div class="col-sm-10">
				          					<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				              						<b>Abuso <u>attuale</u> di fumo</b>
				            					</span>

				            					<span class="input-group-addon" style="text-align:left;">
				            						<= 10 sigarette/dì
				            						<input type="radio" name="Abusofumo" id="menodi10" value="<= 10 sigarette/di" onchange="stickyheaddsadaer()" <?php if(strcmp($quantita_sigarette, "<= 10 sigarette/di") == 0) echo "checked='checked'" ?> disabled>
				          						</span>
				          						<span class="input-group-addon" style="text-align:left;">
				            						> 10 sigarette/dì
				            						<input type="radio" name="Abusofumo" id="piudi10" value="> 10 sigarette/di" onchange="stickyheaddsadaer()" <?php if(strcmp($quantita_sigarette, "> 10 sigarette/di")==0) echo "checked='checked'" ?> disabled>
				          						</span>  
				          					</div><!--/input-group-->
				        				</div><!--/col-sm-7-->
				        				<!--di default le due option sono disabilitiate, se viene cliccata la check di sinistra va scelto se < 10 o > 10-->
				        				
				        				<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=Abusofumo1]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#menodi10').prop('disabled',false)
					      							$('#piudi10').prop('disabled',false)
					      							
					      						}
					      					});

		      							</script>

				      				</div><!--/row-->
				              			
				      				<br/>
				      				<!--continua fattori di rischio-->
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="Usocortisone1" name="Usocortisone1"  onchange="stickyheaddsadaer()" <?php if($cortisone == 1) echo "checked='checked'" ?>/>
				            					</span>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
			        					<div class="col-sm-6">
				          					<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				              						<b>Uso <u>attuale</u> di cortisone-Prednisone e.q.</b>
				            					</span>

				            					<span class="input-group-addon" style="text-align:left;">
				            						> 2,5 mg e < 5 mg
				            						<input type="radio" id="tradueecinque" name="Usocortisone" value="> 2.5 mg e < 5 mg"  onchange="stickyheaddsadaer()" <?php if(strcmp($uso_cortisone, "> 2.5 mg e < 5 mg")==0) echo "checked='checked'"  ?> disabled/>
				          						</span>
				          
				          						<span class="input-group-addon" style="text-align:left;">
				            						>= 5 mg (Prednisone)
				            						<input type="radio" id="piudicinque" name="Usocortisone" value=">= 5 mg (Prednisone)"  onchange="stickyheaddsadaer()" <?php if(strcmp($uso_cortisone, ">= 5 mg (Prednisone)")==0) echo "checked='checked'"  ?> disabled/>
				          						</span>
				          					</div><!--/input-group-->
				        				</div><!--/col-sm-7-->

				        				
				      				</div><!--/row-->

				      				
				      				<script type="text/javascript">
	          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
	          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
				      					$(document).ready(function(){
				      					
				      						var val = $('input[name=Usocortisone1]:checked','#myForm').val();
				      						if(val != null){
				      							
				      							$('#tradueecinque').prop('disabled',false)
				      							$('#piudicinque').prop('disabled',false)
				      							
				      						}
				      					});

	      							</script>
				      				<br/>
				      				<!--continua fattori di rischio-->
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="MalattieAttuali" onchange="stickyheaddsadaer()"  name="Malattieattuali" <?php if($malattie_attuali == 1) echo "checked='checked'" ?> />
				            					</span>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
				        				<div class="col-sm-7">
				      						<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				              						<b>Malattie attuali</b>
				            					</span>

				            					<span class="input-group-addon" style="text-align:left;">
				            						artrite reumatoide
				            						<input type="checkbox" id="artritereumatoide" onchange="stickyheaddsadaer()" name="Artrite" <?php if($artrite == 1) echo "checked='checked'" ?> disabled>
				      							</span>

				      							<span class="input-group-addon" style="text-align:left;">
				            						artrite psoriasica
				            						<input type="checkbox" id="artritepsoriasica" onchange="stickyheaddsadaer()" name="Psoriasi"  <?php if($psoriasi == 1) echo "checked='checked'" ?>   disabled>
				          						</span>
				          
					          					<span class="input-group-addon" style="text-align:left;">
					            					lupus
					            					<input type="checkbox" id="lupus" name="Lupus" onchange="stickyheaddsadaer()"  <?php if($lupus == 1) echo "checked='checked'" ?> disabled>
					          					</span>
					          
					          					<span class="input-group-addon" style="text-align:left;">
					            					sclerodermia
					            					<input type="checkbox" id="sclerodermia" name="Sclerodermia" onchange="stickyheaddsadaer()"  <?php if($sclerodermia == 1) echo "checked='checked'" ?> disabled>
					          					</span>

					          					<span class="input-group-addon" style="text-align:left;">
					            					altre connettiviti
					            					<input type="checkbox" id="altreconnettiviti" name="AltreConnettiviti"  onchange="stickyheaddsadaer()" <?php if($altre_connettiviti == 1) echo "checked='checked'" ?> disabled>
					          					</span>

				          					</div><!--/input-group-->
				        				</div><!--/col-sm-3-->

				        				<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=Malattieattuali]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#artritereumatoide').prop('disabled',false)
					      							$('#artritepsoriasica').prop('disabled',false)
					      							$('#lupus').prop('disabled',false)
					      							$('#sclerodermia').prop('disabled',false)
					      							$('#altreconnettiviti').prop('disabled',false)
					      							
					      						}
					      					});

		      							</script>

				        				
				      				</div><!--/row-->

				      				

									<br/>

									
				      
				      				<!--continua fattori di rischio-->
				      				<?php
			      						$pezzi_osteoporosi = explode("\n", $valori_cause_secondarie);
			      						$pezzi_osteoporosi1 = explode("\n", $valori_cause_secondarie);
				      				?>
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="Osteoporosisecondaria" onchange="stickyheaddsadaer()" name="Osteoporosisecondaria" <?php if($cause_secondarie == 1) echo "checked='checked'" ?>/>
				            					</span>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
				        				
				        				 
				        				<div class="col-sm-3">
				          						<div class="input-group">
				        							<b><input type="text" class="form-control" value="Cause secondarie" readonly></b>
				        						</div>
				        				</div>

				        				<div class="col-sm-3">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					
				            					<select class="form-control" name="CauseSecondarie" id="CauseSecondarie">
				            						<option value="">Tipo</option>
				            						<option value="Diabete-insulino dipendente">Diabete-insulino dipendente</option>
				            						<option value="Osteogenesi imperfecta in età adulta">Osteogenesi imperfecta in età adulta</option>
				            						<option value="Ipertiroidismo non trattato per lungo tempo">Ipertiroidismo non trattato per lungo tempo</option>
				            						<option value="Ipogonadismo">Ipogonadismo</option>
				            						<option value="Menopausa prematura">Menopausa prematura</option>
				            						<option value="Malnutrizione cronica">Malnutrizione cronica</option>
				            						<option value="M.I.C.I.">M.I.C.I.</option>
				            						<option value="Malattia cronica epatica come cirrosi/epatite cronica">Malattia cronica epatica come cirrosi/epatite cronica</option>
				            					</select>
				            					

				          					</div><!--/input-group-->
				          					
				          					<textarea style="visibility:hidden;" id="valori_causesecondarie" name="valori_causesecondarie"><?php $j = 0; while($pezzi_osteoporosi1[$j] != null){ echo $pezzi_osteoporosi1[$j]."\n"; $j=$j+1; } ?></textarea>
				          					
				        				</div>
				        				<div class="col-sm-1">
				        					<input type="button" value="inserisci" class="dimensione" name="Aggiungi_Cause_Secondarie" id="Aggiungi_Cause_Secondarie" onclick="app3()" disabled>
				        					<input type="button" value="Cancella" class="dimensione" name="Cancella_causa_secondaria" id="Cancella_causa_secondaria" onclick="remove3()" disabled>
				        				</div>
				        				<?php $i = 0; ?>
				          				<div class="col-sm-4">		
			          						<!--<textarea class="text_area_causesecondarie" style="width:350px;" name="text_area_causesecondarie" id="text_area_causesecondarie"></textarea>-->
			          						<select style="height:100px;width:350px;" size="5" class="text_area_causesecondarie" style="width:350px;" name="text_area_causesecondarie" id="text_area_causesecondarie"><?php  while($pezzi_osteoporosi[$i] != NULL){ if($pezzi_osteoporosi[$i] != 'NULL') {echo "<option> $pezzi_osteoporosi[$i] </option>";} $i=$i+1;} ?></select>

				          				</div>
				      				</div><!--/row-->

				      				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI CAUSE SECONDARIE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI2{}, CONTENENTE TUTTI I TIPI INSERITI
			        					var cause_secondarie = {};
			        					function app3(){
			        						var x = document.getElementById("text_area_causesecondarie");
	    									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#CauseSecondarie option:selected").text();
											//alert(tipo);
											
											
											
											if(cause_secondarie[tipo]){
												alert("Tipo già inserito");
											}
											else if(tipo == "Tipo"){
												alert("Selezionare un Tipo");
											}
											else{
												$("textarea#valori_causesecondarie").val($("textarea#valori_causesecondarie").val() + tipo+"\n");

												cause_secondarie[tipo] = true;
												option.text = tipo;
												x.add(option);

												
											}

											stickyheaddsadaer();
											
										}

										function remove3(){
											var x = document.getElementById("text_area_causesecondarie");
											//var tipo = x.split()
											var i;

											var y = document.getElementById("valori_causesecondarie");
											y.value = y.value.replace(x.value, "");
											
												for (i = x.length - 1; i>=0; i--) {
												if (x.options[i].selected) {
													
	  												var sel = $("#text_area_causesecondarie option:selected").text(); //prendo il valore del tipo che voglio cancellare
	  												//alert(v);
	  												var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
	  												var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
	  												
	  												cause_secondarie[tipo] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
	  												x.remove(i); //lo rimuovo come option

												}
											}

											stickyheaddsadaer();
										}
			        				</script>
				      				
				      				<br/>
				      
				      				<!-- continua fattori di rischio -->
				      				<div class="row">
				        				<div class="col-sm-1">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="Abusoalcool1" name="Abusoalcool1" <?php if($abuso_alcool == 1) echo "checked='checked'" ?>  >
				            					</span>
				          					</div><!-- /input-group -->
				        				</div><!--/col-sm-2-->
				        				<div class="col-sm-6">
				          					<div class="input-group">
				            					<span class="input-group-addon" style="text-align:left">
				              						<b>Abuso <u>attuale</u> di alcool</b>
				            					</span>

				            					<span class="input-group-addon" style="text-align:left;">
				            						< 3 unità/dì
				            						<input type="radio" id="abusoalcoolno" name="Abusoalcool" value="< 3 unita/di" <?php if(strcmp($quantita_alcool,"< 3 unita/di")==0) echo "checked='checked'"   ?> disabled/>
					          					</span>
					          					<span class="input-group-addon" style="text-align:left;">
					           	 					>= 3 unità/dì
					            					<input type="radio" id="abusoalcoolsi" name="Abusoalcool" value=">= 3 unita/di" <?php if(strcmp($quantita_alcool,">= 3 unita/di")==0) echo "checked='checked'"   ?> disabled/>
					          					</span> 

				          					</div><!--/input-group-->
				        				</div><!--/col-sm-6-->
				        				
				        				<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=Abusoalcool1]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#abusoalcoolno').prop('disabled',false)
					      							$('#abusoalcoolsi').prop('disabled',false)
					      							
					      						}
					      					});

		      							</script>
				        				

				    				<!-- SONO ARRIVATO QUA-->
				      				</div><!--/row-->
				      				<br/>
				      				<h3><b>Informazioni cliniche utili per la prescrizione terapeutica</b></h3>
				      				<!-- INIZIO GRUPPO GINECOLOGIA-->
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<!--<h4>Ginecologia</h4>-->
				      						<br/>
				      						
				      						<div style="width:100%">
		    									<div class="input-group">		
					      							<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="PatologieUterine" onchange="stickyheaddsadaer()" name="PatologieUterine" <?php if($patologie_uterine == 1) echo "checked='checked'"  ?>/>
					            					</span>
					            					<span class="input-group-addon" style="text-align:left;background:#eee">
					            						<div>Patologie uterine</div>
					            					</span>
					            				
					            				
					            				
					            					<input type="text"  class="form-control" id="diagnosi1" onchange="stickyheaddsadaer()" name="diagnosi1" placeholder="Diagnosi" value="<?php if($diagnosi_patologie_uterine != 'NULL') echo $diagnosi_patologie_uterine; ?>" disabled>

					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=PatologieUterine]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#diagnosi1').prop('disabled',false)
					      							
					      							
					      						}
					      					});

		      							</script>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					
	    									<div style="width:100%">
		    									<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="Neoplasia" onchange="stickyheaddsadaer()" name="Neoplasia" value="Neoplasia Magligna mammaria" <?php if($neoplasia == 1) echo "checked='checked'" ?>/>
					            						
					            					</span>
					            					<span class="input-group-addon" style="width:100%;text-align:left">
					            						<div>Neoplasia maligna mammaria</div>
					            					</span>
					            					
					            					<span class="input-group-addon" >
					            				
					            					
					            						<input type="text"  id="data_neoplasia" name="data_neoplasia" placeholder="Anno" value="<?php if($data_neoplasia != 'NULL') echo $data_neoplasia ?>" disabled>
					            						
					            					</span>

					            					<span class="input-group-addon" style="text-align:left">	
					            						<input type="text" id="terapia_neoplasia" onchange="stickyheaddsadaer()"  name="terapia_neoplasia" placeholder="Terapia" value="<?php if($terapia_neoplasia != 'NULL') echo $terapia_neoplasia; ?>" disabled>
					            					</span>
					            				
					            					
					            					
					            					
					            				</div>
					            			</div>

					            			
				          					
				          				</div>
				          			</div>
				          			<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=Neoplasia]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#data_neoplasia').prop('disabled',false)
					      							$('#terapia_neoplasia').prop('disabled',false)
					      							
					      						}
					      					});

		      							</script>

				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
										        <div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="SintomiVasomotori"  onchange="stickyheaddsadaer()" name="SintomiVasomotori" <?php if($sintomi_vasomotori == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" class="form-control" value="Sintomi vasomotori presenti" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Sintomi vasomotori presenti</span>
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="SintomiDistrofici" onchange="stickyheaddsadaer()" name="SintomiDistrofici" <?php if($sintomi_distrofici == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" value="Sintomi distrofici" class="form-control" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Sintomi distrofici</span> 
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<!--fino qua-->
				          			<br/><br/>
				          			<!--SECONDO GRUPPO-->
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<!--<h4>Apparato cardio/circolatorio/respiratorio/urinario</h4>-->
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="Dislipidemia" onchange="stickyheaddsadaer()" name="Dislipidemia" <?php if($dislipidemia == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<span class="input-group-addon" style="text-align:left">Dislipidemia</span>
					            					<input type="text"  class="form-control" id="dislipidemia_terapia" onchange="stickyheaddsadaer()" name="dislipidemia_terapia" placeholder="Terapia" value="<?php if($dislipidemia_terapia != 'NULL') echo $dislipidemia_terapia ?>"> 
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<script>
				          				$('#dislipidemia_terapia').prop('disabled',true)

				          				$('#Dislipidemia').bind('click',function(){
											if($('#Dislipidemia').prop('checked'))
											{
												
												$('#dislipidemia_terapia').prop('disabled', false)
											
											}
											else{
												$('#dislipidemia_terapia').prop('disabled', true)
												$('#dislipidemia_terapia').prop('value', '')
												//$('#terapia').prop('checked', false)	
											}	
										});

				          			</script>

				          			<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=Dislipidemia]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#dislipidemia_terapia').prop('disabled',false)
					      							
					      							
					      						}
					      					});

		      							</script>

				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="Ipertensione" onchange="stickyheaddsadaer()" name="Ipertensione" <?php if($ipertensione == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" value="Ipertensione in terapia" class="form-control" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Ipertensione in terapia</span>
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="RischioTev" onchange="stickyheaddsadaer()" name="RischioTev" <?php if($rischio_tev == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" value="Rischio TEV" class="form-control" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Rischio TEV</span> 
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="PatologiaCardiaca" onchange="stickyheaddsadaer()" name="PatologiaCardiaca" <?php if($patologia_cardiaca == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" value="Patologia cardiaca (infarto,coronaropatia)" class="form-control" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Patologia cardiaca (infarto,coronaropatia)</span>
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<div class="row">
				          				<div class="col-sm-8">
				          					
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="PatologiaVascolare" onchange="stickyheaddsadaer()" name="PatologiaVascolare" <?php if($patologia_vascolare == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" value="Patologia vascolare cerebrale (ictus,TIA,altro)" class="form-control" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Patologia vascolare cerebrale (ictus,TIA,altro)</span>
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="InsufficienzaRenale" onchange="stickyheaddsadaer()" name="InsufficienzaRenale" <?php if($insufficienza_renale == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" value="Insufficienza renale" class="form-control" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Insufficienza renale</span>
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="PatologiaRespiratoria" onchange="stickyheaddsadaer()" name="PatologiaRespiratoria" <?php if($patologia_respiratoria == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" value="Patologia respiratoria (asma,B.P.C.O.)" class="form-control" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Patologia respiratoria (asma,B.P.C.O.)</span>
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<br/><br/>
				      				<!--TERZO GRUPPO-->
				      				<div class="row">
				      					<div class="col-sm-8">
				      						
				      						<!--<h4>Gruppo digerente</h4>-->
				      						<br/>
				      						<div style="width:100%">
					      						<div class="input-group">
					      							<span class="input-group-addon">
					            						
					            						<input type="checkbox" id="PatologiaDelCavoOrale" onchange="stickyheaddsadaer()" name="PatologiaDelCavoOrale" <?php if($patologia_del_cavo_orale == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<span class="input-group-addon" style="text-align:left">Patologia del cavo orale</span>
					            						<!--Patologia del cavo orale-->
					            					
					          						
				          							<input type="text" class="form-control" id="terapia" name="terapia" value="<?php if($terapia_patologia_del_cavo_orale != 'NULL') echo $terapia_patologia_del_cavo_orale ?>" disabled>
					          					
					      						</div>
					      					</div>
				      					</div>
				      				</div>

				      				<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
					      					$(document).ready(function(){
					      					
					      						var val = $('input[name=PatologiaDelCavoOrale]:checked','#myForm').val();
					      						if(val != null){
					      							
					      							$('#terapia').prop('disabled',false)
					      							
					      							
					      						}
					      					});

		      							</script>

				      				<div class="row">
				      					<div class="col-sm-8">
				      						<br/>
				      						
				      						<div style="width:100%">
		        								<div class="input-group">
					      							<span class="input-group-addon">
					            					
					            						<input type="checkbox" id="PatologiaEpatica" onchange="stickyheaddsadaer()" name="PatologiaEpatica" <?php if($patologia_epatica == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" class="form-control" style="width:29em;" value="Grave patologia epatica (epatite acuta/cronica,cirrosi)" readonly>-->
					            					<span class="form-control" style="width:100%" readonly>Grave patologia epatica (epatite acuta/cronica,cirrosi)</span>
					            					
					          						

					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					

				          					<br/>
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					            						<input type="checkbox" id="PatologiaEsofagea" onchange="stickyheaddsadaer()" name="PatologiaEsofagea" <?php if($patologia_esofagea == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" class="form-control" style="width:70%;" value="Patologia esofagea (esofagite,diverticoli,reflusso g.e. , ernia iatale)" readonly>-->
					            					<span class="form-control" style="width:100%" readonly>Patologia esofagea (esofagite,diverticoli,reflusso g.e., ernia iatale)</span>
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          	
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
										        <div class="input-group">
					          						<span class="input-group-addon">
					            						<input type="checkbox" id="Gastroduodenite" onchange="stickyheaddsadaer()" name="Gastroduodenite" <?php if($gastro_duodenite == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" class="form-control" value="Gastro-duodenite, ulcera g.d." readonly>-->
					          						<span class="form-control" style="width:100%" readonly>Gastro-duodenite, ulcera g.d.</span>
					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
										        <div class="input-group">
					          						<span class="input-group-addon">
					            						<input type="checkbox" id="Gastroresezione" onchange="stickyheaddsadaer()" name="Gastroresezione" <?php if($gastro_resezione == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" class="form-control" value="Gastro-resezione" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Gastro-resezione</span>
					            					
					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
										        <div class="input-group">
					          						<span class="input-group-addon">
					            						<input type="checkbox" id="Resezioneintestinale" onchange="stickyheaddsadaer()" name="Resezioneintestinale" <?php if($resezione_intestinale == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" class="form-control" value="Resezione intestinale" readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">Resezione intestinale</span>
					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<br/>
				          					<div style="width:100%">
										        <div class="input-group">
					          						<span class="input-group-addon">
					            						<input type="checkbox" id="mici" onchange="stickyheaddsadaer()" name="mici" <?php if($mici == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<!--<input type="text" style="width:29em;" class="form-control" value="M.I.C.I." readonly>-->
					            					<span class="form-control" style="text-align:left;background:#eee">M.I.C.I.</span>
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			
				          			<!-- terzo gruppo-->
				          			
				          			
				          			
				          			<br/>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span class="input-group-addon">
					          							<input type="checkbox" id="Ipovitaminosi" onchange="stickyheaddsadaer()" name="Ipovitaminosi" <?php if($ipovitaminosi == 1) echo "checked='checked'" ?>/>
					          						</span>
					          						<span class="input-group-addon">25 OH Vitamina D ng/ml</span>
					          						<input type="text" class="form-control"  name="valore_ipovitaminosi" onchange="stickyheaddsadaer()" id="valore_ipovitaminosi" class="form-control" placeholder="ng/ml" value="<?php if($valore_ipovitaminosi != 'NULL') echo $valore_ipovitaminosi  ?>" disabled>
					          					</div>
					          				</div>
				          				</div>
				          			</div>


				          			<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
				      					$(document).ready(function(){
				      					
				      						var val = $('input[name=Ipovitaminosi]:checked','#myForm').val();
				      						if(val != null){
				      							
				      							$('#valore_ipovitaminosi').prop('disabled',false)
				      							
				      							
				      						}
				      					});

	      							</script>

				          			<!-- CODICE JAVASCRIPT PER IL CAMBIO DINAMICO DI PATOLOGIA UTERINA-->
									<script type="text/javascript">
										//Se clicco sulla check patologia del cavo orale, allora la text per la terapia è visibile
										//altrimenti se non è cliccata la check, la text è disabilitata
										$('#Ipovitaminosi').bind('click',function(){
											if($('#Ipovitaminosi').prop('checked'))
											{
												
												$('#valore_ipovitaminosi').prop('disabled', false)
											
											}
											else{
												$('#valore_ipovitaminosi').prop('disabled', true)
												$('#valore_ipovitaminosi').prop('value', '')
												//$('#diagnosi1').prop('checked', false)	
											}	
										});
									</script>

				          			<br/>
				          			<div class="row">
				          				<div class="col-sm-8">
				            				<br/>
				            				<div style="width:100%">
				            					<div class="input-group">
				            						<span class="input-group-addon">
				            							<input type="checkbox" id="AltroPatologie" onchange="stickyheaddsadaer()" name="AltroPatologie" <?php if($altre_patologie == 1) echo "checked='checked'" ?>/>
					            					</span>
					            					<span class="input-group-addon" style="text-align:left;background:#eee">Altro</span>
					            					<input type="text" class="form-control" id="altro_patologie" onchange="stickyheaddsadaer()" name="altro_patologie" placeholder="Altro" value="<?php if($altre_patologie_testo != 'NULL') echo $altre_patologie_testo ?>" disabled>		
					          					</div>
					          				</div>
				          				</div>
				          			</div>

				          			<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
				      					$(document).ready(function(){
				      					
				      						var val = $('input[name=AltroPatologie]:checked','#myForm').val();
				      						if(val != null){
				      							
				      							$('#altro_patologie').prop('disabled',false)
				      							
				      							
				      						}
				      					});
	      							</script>

				          			<br/>
				          			<div class="row">
		
				          				<div class="col-sm-8">
				          					<h3><b>Allergie/non accettabilità</b></h3>
				          					</br>
				          					<div style="width:100%">
					          					<div class="input-group">
				            						<span class="input-group-addon">
				            							<input type="checkbox" id="Allergie" onchange="stickyheaddsadaer()" name="Allergie" <?php if($allergie == 1) echo "checked='checked'" ?>/>
				            						</span>
					            					<span class="input-group-addon" style="text-align:left;background:#eee">Allergie</span>
					            					<input type="text" class="form-control" id="allergie" onchange="stickyheaddsadaer()" name="allergie" placeholder="Allergie" value="<?php if($valori_allergie != 'NULL') echo $valori_allergie ?>" disabled>
					            				</div>
					            			</div>
				            			</div>
				            		</div>

				            		<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
				      					$(document).ready(function(){
				      					
				      						var val = $('input[name=Allergie]:checked','#myForm').val();
				      						if(val != null){
				      							
				      							$('#allergie').prop('disabled',false)
				      							
				      							
				      						}
				      					});

	      							</script>
				            		<div class="row">
				            			<div class="col-sm-8">
				            				<br/>
				            				<div style="width:100%">
					            				<div class="input-group">
					            					<span class="input-group-addon">
					            						<input type="checkbox" id="Intolleranza" onchange="stickyheaddsadaer()" name="Intolleranza" <?php if($intolleranza == 1) echo "checked='checked'" ?>/>
					          						</span>
					          						<span class="input-group-addon" style="text-align:left;background:#eee">	
					          							Intolleranza/non accettabilità terapie per osteoporosi
					          						</span>

					          						<input type="text" class="form-control" id="value_intolleranze" onchange="stickyheaddsadaer()" name="value_intolleranze" placeholder="Intolleranze" value="<?php if($valori_intolleranze != 'NULL') echo $valori_intolleranze ?>">

					            					<br/>
					            					
					            				</div>
					            			</div>
				            			</div>
				            		</div>

				            		<!-- JAVASCRIPT PER INTOLLERANZE-->
				            		<script>
				            			$('#value_intolleranze').prop('disabled',true)

				          				$('#Intolleranza').bind('click',function(){
											if($('#Intolleranza').prop('checked'))
											{
												
												$('#value_intolleranze').prop('disabled', false)
											
											}
											else{
												$('#value_intolleranze').prop('disabled', true)
												$('#value_intolleranze').prop('value', '')
												//$('#terapia').prop('checked', false)	
											}	
										});

				            		</script>

				            		<script type="text/javascript">
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
				      					$(document).ready(function(){
				      					
				      						var val = $('input[name=Intolleranza]:checked','#myForm').val();
				      						if(val != null){
				      							
				      							$('#value_intolleranze').prop('disabled',false)
				      							
				      							
				      						}
				      					});

	      							</script>
				            		<br/>
			            			<br/>
				          			<div class="row">
				          				<div class="col-sm-12">
				          				<h3><b>Densitometria Precedente</b>
			          							&nbsp;&nbsp;&nbsp;
			          							
			          					</h3>
			          					</div>

				          			</div>


				          			<div class="row">
				          				<div class="col-sm-8">
				          					<div style="width:100%">
				          						<div class="input-group">
				          							<span class="input-group-addon">
			            								<input type="checkbox" id="DensitometriaPrecedente" name="DensitometriaPrecedente" <?php if($densitometria_precedente == 1) echo "checked='checked'" ?>/>
				            						</span>
				            						<span class="input-group-addon" stlye="text-align:left;background:#eee">Densitometria precedente</span>
				            						<input type="text" class="form-control" id="data_densitometria_precedente" name="data_densitometria_precedente" placeholder="Data" value="<?php if($data_densitometria_precedente != 'NULL') echo $data_densitometria_precedente ?>" disabled>
				            						
				            					</div>
				            				</div>

			            					<div class="input-group">
			            						<span class="input-group-addon">
			            							<input type="checkbox" name="densitometria_precedente_interna" id="densitometria_precedente_interna"  <?php if($densitometria_precedente_interna == 1) echo "checked='checked'" ?>/ disabled>
			          							</span>
			          							<span class="form-control" readonly>Interna</span>
			            					</div>

			            					<br/>
			            					<div style="width:100%">
				            					<div class="input-group">
				            						<span class="input-group-addon" style="width:100%;text-align:left;background:#eee">
				            							Vertebre valutate
				            						</span>
				            						<span class="input-group-addon" style="text-align:left;">
			            								
				            							
				            							<input type="checkbox" id="L1" name="L1"  <?php if($colonna_lombare_L1 == 1) echo "checked='checked'" ?> disabled>
				            							L1
				            						</span>

				            						<span class="input-group-addon" style="text-align:left;">
			            							
				            					
				            							<input type="checkbox" id="L2" name="L2"  <?php if($colonna_lombare_L2 == 1) echo "checked='checked'" ?> disabled>
				            							L2
				            						</span>

				            						<span class="input-group-addon" style="text-align:left;">
			            							
				            					
				            							<input type="checkbox" id="L3" name="L3" <?php if($colonna_lombare_L3 == 1) echo "checked='checked'" ?> disabled>
				            							L3
				            						</span>

				            						<span class="input-group-addon" style="text-align:left;">
			            							
				            					
				            							<input type="checkbox" id="L4" name="L4" <?php if($colonna_lombare_L4 == 1) echo "checked='checked'" ?> disabled>
				            							L4
				            						</span>
				            					</div>
				            				</div>
				          				</div>

				          			</div>
				          			<br/>
				          			<h4>
			            			<b>Colonna lombare</b></h4>
			            			<br/>
				          			<div class="row">
				          				<div class="col-sm-4">
				          					<div style="width:100%">
												<div class="input-group">
													<span style="align:center" class="input-group-addon">
			            								<input type="checkbox" id="colonna_lombare_appicabile" name="colonna_lombare_appicabile" value="applicabile" <?php if($colonna_lombare_applicabile == 1) echo "checked='checked'"  ?> disabled/>
			            								Applicabile

			            							</span>
		                  						
					          						<span class="input-group-addon" style="text-align:left;background:#eee">T score</span>
					            					
					            					
					            					<input type="text" class="form-control" id="ColonnaLombareTscoreIntero" name="ColonnaLombareTscoreIntero" style="text-align:right;width:70px" value="<?php if($colonna_lombare_tscore_intero != 'NULL') echo $colonna_lombare_tscore_intero   ?>" disabled>
					            					<!--<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
	              									<input type="text" style="width:50px" class="form-control" id = "ColonnaLombareTscoreDecimale"  name="ColonnaLombareTscoreDecimale" value="<?php if($colonna_lombare_tscore_decimale != 'NULL') echo $colonna_lombare_tscore_decimale ?>" disabled>-->
		              								
					          					</div>
					          				</div>
				          				</div>

				          				<div class="col-sm-4">
				          					<div style="width:100%">
												<div class="input-group">
					          						<span class="input-group-addon" style="text-align:left;background:#eee">Z score</span>
					            					
					            					
					            					<input type="text" style="width:70px;text-align:right" class="form-control" id="ColonnaLombareZscoreIntero" name="ColonnaLombareZscoreIntero" value="<?php if($colonna_lombare_zscore_intero != 'NULL') echo $colonna_lombare_zscore_intero   ?>" disabled>
					            					<!--<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
	              									<input type="text" style="width:50px" class="form-control" id = "ColonnaLombareZscoreDecimale"  name="ColonnaLombareZscoreDecimale" value="<?php if($colonna_lombare_zscore_decimale != 'NULL') echo $colonna_lombare_zscore_decimale ?>" disabled>-->

					          					</div>
					          				</div>
				          				</div>
				          			</div>
				          			<br/>
				          			<div class="row">
				          				<div class="col-sm-8">
				          					<h4>

				          					<b>Femore collo</b>
				          					&nbsp;&nbsp;&nbsp;
				          					<input type="radio" value="Destro" id="Femore-collo-destro" name="Femore_collo" <?php if(strcmp($femore_collo_posizione, "Destro")==0) echo "checked='checked'"  ?> disabled>
				          					Destro
				          					<input type="radio" value="Sinistro" id="Femore-collo-sinistro" name="Femore_collo" <?php if(strcmp($femore_collo_posizione, "Sinistro")==0) echo "checked='checked'"  ?> disabled>
				          					Sinistro
				          				</h4>
				          				</div>
				          			</div>
				          			<div class="row">

				          				<div class="col-sm-4">
				          					<div style="width:100%">
					          					<div class="input-group">
					          						<span style="align:center" class="input-group-addon">
			            								<input type="checkbox" id="femore_collo_appicabile" name="femore_collo_appicabile" value="applicabile" <?php if($femore_collo_appicabile == 1) echo "checked='checked'"  ?> disabled/>
			            								Applicabile

			            							</span>
		                  						

					          						<span class="input-group-addon" style="background:#eee;text-align:left">T score</span>
					            					
					            					
					            					<input type="text" style="width:70px;text-align:right" class="form-control" id="ColloFemoreTscoreIntero" name="ColloFemoreTscoreIntero"  value="<?php if($femore_collo_tscore_intero != 'NULL') echo $femore_collo_tscore_intero  ?>" disabled>
					            					<!--<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
	              									<input type="text" style="width:50px" class="form-control" id = "ColloFemoreTscoreDecimale"  name="ColloFemoreTscoreDecimale" value="<?php if($femore_collo_tscore_decimale != 'NULL') echo $femore_collo_tscore_decimale ?>">-->

					          					</div>
					          				</div>
				          				</div>

				          				<div class="col-sm-4">
				          					<div class="input-group">
				          						<span class="input-group-addon" id="sizing-addon2">Z score</span>
				            					
				            					
				            					<input type="text" style="width:70px;text-align:right" class="form-control" id="ColloFemoreZscoreIntero" name="ColloFemoreZscoreIntero"  value="<?php if($femore_collo_zscore_intero != 'NULL') echo $femore_collo_zscore_intero  ?>" disabled>
				            					<!--<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
              									<input type="text" style="width:50px" class="form-control" id = "ColloFemoreZscoreDecimale"  name="ColloFemoreZscoreDecimale" value="<?php if($femore_collo_zscore_decimale != 'NULL') echo $femore_collo_zscore_decimale ?>">-->

				          					</div>
				          				</div>

				          			</div>
				          			<br/><br/>

				          			<script>
				          				$('#ColonnaLombareTscoreIntero').prop('disabled', true);
			          					$('#ColonnaLombareTscoreDecimale').prop('disabled', true);

			          					$('#ColonnaLombareZscoreIntero').prop('disabled', true);
			          					$('#ColonnaLombareZscoreDecimale').prop('disabled', true);
			          					
				          				$('#ColloFemoreZscoreIntero').prop('disabled', true);
			          					$('#ColloFemoreZscoreDecimale').prop('disabled', true);

			          					$('#ColloFemoreTscoreIntero').prop('disabled', true);
			          					$('#ColloFemoreTscoreDecimale').prop('disabled', true);
			          					

				          				$('#colonna_lombare_appicabile').bind('click', function(){

				          					if($('#colonna_lombare_appicabile').prop('checked')){
					          					$('#ColonnaLombareTscoreIntero').prop('disabled', false);
					          					$('#ColonnaLombareTscoreDecimale').prop('disabled', false);

					          					$('#ColonnaLombareZscoreIntero').prop('disabled', false);
					          					$('#ColonnaLombareZscoreDecimale').prop('disabled', false);
												/************************************************************/
												/*
												
					          					*/					          					
					          				}
					          				else{
					          					$('#ColonnaLombareTscoreIntero').prop('disabled', true);
					          					$('#ColonnaLombareTscoreDecimale').prop('disabled', true);

					          					$('#ColonnaLombareZscoreIntero').prop('disabled', true);
					          					$('#ColonnaLombareZscoreDecimale').prop('disabled', true);
					          					
					          					$('#ColonnaLombareTscoreIntero').prop('value', '');
					          					$('#ColonnaLombareTscoreDecimale').prop('value', '');

					          					$('#ColonnaLombareZscoreIntero').prop('value', '');
					          					$('#ColonnaLombareZscoreDecimale').prop('value', '');
					          					
					          					/********************************************************/
					          					/*
					          					*/
					          				}
				          				});

										$('#femore_collo_appicabile').bind('click',function(){
											if($('#femore_collo_appicabile').prop('checked')){
												$('#ColloFemoreZscoreIntero').prop('disabled', false);
					          					$('#ColloFemoreZscoreDecimale').prop('disabled', false);

					          					$('#ColloFemoreTscoreIntero').prop('disabled', false);
					          					$('#ColloFemoreTscoreDecimale').prop('disabled', false);

											}
											else{
												$('#ColloFemoreZscoreIntero').prop('disabled', true);
					          					$('#ColloFemoreZscoreDecimale').prop('disabled', true);

					          					$('#ColloFemoreTscoreIntero').prop('disabled', true);
					          					$('#ColloFemoreTscoreDecimale').prop('disabled', true);	
					          					
					          					$('#ColloFemoreZscoreIntero').prop('value', '');
					          					$('#ColloFemoreZscoreDecimale').prop('value', '');

					          					$('#ColloFemoreTscoreIntero').prop('value', '');
					          					$('#ColloFemoreTscoreDecimale').prop('value', '');	
					          					
											}


										});

				          			</script>

				          			<script>
				          				$(document).ready(function(){
				          					if($('#colonna_lombare_appicabile').prop('checked')){
					          					$('#ColonnaLombareTscoreIntero').prop('disabled', false);
					          					$('#ColonnaLombareTscoreDecimale').prop('disabled', false);

					          					$('#ColonnaLombareZscoreIntero').prop('disabled', false);	
					          					$('#ColonnaLombareZscoreDecimale').prop('disabled', false);
					          					/*********************************************************/
					          					/*
					          					*/
					          				}
					          				else{
					          					$('#ColonnaLombareTscoreIntero').prop('disabled', true);
					          					$('#ColonnaLombareTscoreDecimale').prop('disabled', true);

					          					$('#ColonnaLombareZscoreIntero').prop('disabled', true);	
					          					$('#ColonnaLombareZscoreDecimale').prop('disabled', true);

					          					$('#ColonnaLombareTscoreIntero').prop('value', '');
					          					$('#ColonnaLombareTscoreDecimale').prop('value', '');

					          					$('#ColonnaLombareZscoreIntero').prop('value', '');	
					          					$('#ColonnaLombareZscoreDecimale').prop('value', '');
					          					

					          				}

					          					
				          					
					          				


				          				});
				          			</script>

				          			<script>
				          				$(document).ready(function(){
				          					if($('#femore_collo_appicabile').prop('checked')){
				          						$('#ColloFemoreZscoreIntero').prop('disabled', false);
					          					$('#ColloFemoreZscoreDecimale').prop('disabled', false);

					          					$('#ColloFemoreTscoreIntero').prop('disabled', false);
					          					$('#ColloFemoreTscoreDecimale').prop('disabled', false);
					          					


				          					}
				          					else{
				          						$('#ColloFemoreZscoreIntero').prop('disabled', true);
					          					$('#ColloFemoreZscoreDecimale').prop('disabled', true);

					          					$('#ColloFemoreTscoreIntero').prop('disabled', true);
					          					$('#ColloFemoreTscoreDecimale').prop('disabled', true);

					          					$('#ColloFemoreZscoreIntero').prop('value', '');
					          					$('#ColloFemoreZscoreDecimale').prop('value', '');

					          					$('#ColloFemoreTscoreIntero').prop('value', '');
					          					$('#ColloFemoreTscoreDecimale').prop('value', '');
					          					
					          					
				          					}
				          					


				          				});

				          			</script>
				          			
				      				<script type="text/javascript">
		          						/*
		          						//AL CARICAMENTO DEL FILE CONTROLLO SE È CLICCATA LA CHECKBOX FRATTURA FRAGILITÒ VERTEBROSA/FEMORALE
		          						//SE È STATA CLICCATA RENDO DISPONIBILE LE DUE RADIO CON QUELLO CHE È STATO CLICCATO, ALTRIMENTI LE LASCIO INDISPONIBILI
				      					$(document).ready(function(){
				      					
				      						var val = $('input[name=DensitometriaPrecedente]:checked','#myForm').val();
				      						if(val != null){
				      							
				      							$('#data_densitometria_precedente').prop('disabled', false)
				      							$('#densitometria_precedente_interna').prop('disabled', false)
				      							$('#L1').prop('disabled', false)
												$('#L2').prop('disabled', false)
												$('#L3').prop('disabled', false)
												$('#L4').prop('disabled', false)
												//$('#ColonnaLombareTscore').prop('value', '')
												$('#colonna_lombare_appicabile').prop('disabled', false)
												//$('#ColonnaLombareTscoreIntero').prop('disabled',false)
												//$('#ColonnaLombareTscoreDecimale').prop('disabled',false)
												//$('#ColonnaLombareZscore').prop('value', '')
												//$('#ColonnaLombareZscoreIntero').prop('disabled',false)
												//$('#ColonnaLombareZscoreDecimale').prop('disabled',false)
												$('#Femore-collo-destro').prop('disabled',false)
												$('#Femore-collo-sinistro').prop('disabled',false)
												$('#femore_collo_appicabile').prop('disabled',false)
												//$('#ColloFemoreTscoreIntero').prop('disabled',false)
												//$('#ColloFemoreTscoreDecimale').prop('disabled',false)
												//$('#ColloFemoreZscoreIntero').prop('disabled',false)
												//$('#ColloFemoreZscoreDecimale').prop('disabled',false)
												

				      							
				      						}
				      					});
										*/
	      							</script>









				     	 			<!--SONO ARRIVATO QUA 13.5.18 -->
				     	 			<div class="row">
				     	 				<div class="col-sm-2">
				     	 				</div>

				     	 				<div class="col-sm-5">
			     	 						<input type="submit" class="btn btn-primary submitbutton" id="Inserisci_B_Bozza" name="Inserisci_B_Bozza" value="Salva Bozza" onclick="return confirm('Stai salvando una bozza, potrai modificarlo e non verrà cambiato lo stato')"/>
			      						</div>

			      						<div class="col-sm-5">
			     	 						<input type="submit" class="btn btn-primary submitbutton" id="Inserisci_B_Finale" name="Inserisci_B_Finale" value="Salva Finale" onclick="return confirm('Stai salvando definitivamente, salvando cambierà lo stato e non potrai modificarlo')"/>
			      						</div>
			      					</div>

			      				</div><!-- chiudo la colonna sm-11-->
	  						</div><!-- chiudo la riga grande(inizio referto B), mi dice che chiudo il container--> 
	  					</div> <!-- chiudo colonna grande refertoB-->
	  				</div><!-- chiudo riga principale refertoB-->
  				</form>
      			<br/><br/><br/>
      			<!-- QUA CI ANDREBBE LA GESTIONE DEI COLORI DEGLI STATI !!!!!!!-->
          		<br/><br/>

          		<!-- inizio form C -->
          		<!--  RECUPERO I DATI DEL REFERTO C SE PRESENTE. -->	

	    		<?php 	if(strcmp($statoB, 'VERDE') == 0){  ?>
	    					<script>
	    						$(document).ready(function(){
	    							$('#myForm2').show();
	    						})
	    					</script>
				<?php 	}   ?>

				<?php 	if(strcmp($statoB, 'VERDE')==0){   ?>
							<script>
								$(document).ready(function(){
									$('#Inserisci_B_Bozza').prop('disabled',true);
								});

							</script>
				<?php 	}   ?>

	    		<script>
	    		//AL CLICK DEL PULSANTE C IN CIMA ALLA PAGINA, VIENE DIRETTAMENTE REINDERIZZATO ALLA POSIZIONE CON DIV RefertoC NELLA PAGINA
	    			$('#C').click(function() {
    					$('html,body').animate({
        					scrollTop: $('#RefertoC').offset().top},
        				'slow');
					});
				</script>



<!-- CONSULENZA PER OSTEOPOROSI*******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************7
 *******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 *******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 *******************************************************************************************************************************************************************************
**********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************
 **********************************************************************************************************************************************************-->



          		<?php require './backend_frontend/recupero_diagnosi.php'; ?>
          		<form method="POST" action="./backend_frontend/inserimento_diagnosi.php" id="myForm2"  name="myForm2" visible="true" style="display:none;">
	          		<div class="row">
	          			<h3><b><p style="text-align:center;" id="RefertoC">CONSULENZA PER OSTEOPOROSI</p></b></h3>
	          			<input type="hidden" name="pk" value="<?php echo $pk ?>">
	    				<input type="hidden" name="datascan" value="<?php echo $datascan_mysql ?>">
		          	
	          			<div class="col-sm-12">
	          				<div class="row">
	          					<div class="col-sm-1" style="border:1px solid black;height:2200px;text-align:center;">
	            					<h1><p style="text-position:relative;"><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>C</p></h1>
	          					</div>
	              					<script>
	              						$("input#Tel").keyup(function(){
	              							var numero = $(this).val();
	              							numero = numero.replace(/[^\w]+/g, "");
	              							$("input#telefono").val(numero);

	              						});

	              						$("input#Via").keyup(function(){
	              							var via = $(this).val();
	              							//via = via.replace(/[^\w]+/g, "");
	              							$("input#via").val(via);

	              						});

	              						$("input#Residenza").keyup(function(){
	              							var residenza = $(this).val();
	              							//via = via.replace(/[^\w]+/g, "");
	              							$("input#residenza").val(residenza);

	              						});
	              					</script>

	              					<script>
	              					$(document).ready(function(){
	              						$('#PregresseFratture1').bind('click',function(){
	              							if($('#PregresseFratture1').prop('checked')){
	              								$('#PregresseFratture2').prop('checked',true)
	              								//$('#PregresseFratture2').prop('visible','hidden')
	              								$("#PregresseFratture2").css('visibility','hidden')
	              							}
	              							else{
	              								$('#PregresseFratture2').prop('checked',false)	
	              								//$('#PregresseFratture2').prop('visible','hidden')
	              								$("#PregresseFratture2").css('visibility','hidden')
	              							}


	              						});
	              					});
	              					</script>
	          						
	          					<?php
	          						$query_recupero = "SELECT TOT_Tscore, TOT_Zscore FROM Spine WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql'";
	          						//echo($query_recupero);
	          						$res = mysqli_query($mysqli, $query_recupero);
	          						$row = mysqli_fetch_assoc($res);
	          						//echo $row[TOT_Tscore];
	          						$Tscore_Spine = $row[TOT_Tscore];
	          						$Zscore_Spine = $row[TOT_Zscore];

	          						$query_recupero = "SELECT NECK_Tscore, NECK_Zscore FROM HipRLTZscore WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql' AND SCAN_RL = 'L'";
	          						//echo($query_recupero);
	          						$res = mysqli_query($mysqli, $query_recupero);
	          						$row = mysqli_fetch_assoc($res);
	          						//echo $row[TOT_Tscore];
	          						$Tscore_HipL = $row[NECK_Tscore];
	          						$Zscore_HipL = $row[NECK_Zscore];


									$query_recupero = "SELECT NECK_Tscore, NECK_Zscore FROM HipRLTZscore WHERE PATIENT_KEY = '$pk' AND SCAN_DATE = '$datascan_mysql' AND SCAN_RL = 'R'";
	          						//echo($query_recupero);
	          						$res = mysqli_query($mysqli, $query_recupero);
	          						$row = mysqli_fetch_assoc($res);
	          						//echo $row[TOT_Tscore];
	          						$Tscore_HipR = $row[NECK_Tscore];
	          						$Zscore_HipR = $row[NECK_Zscore];
	          					?>

	          					<div class="col-sm-11">
	          						<h3><b>Diagnosi Densitometrica</b></h3>

	          						
	          						<div class="row">
	          							
	          							<div class="col-sm-6">
	          								<div class="input-group">
	          									<span class="input-group-addon">
		          									<input type="checkbox"  name="colonna_vertebrale" id="colonna_vertebrale" onchange="stickyheaddsadaer()" <?php if($colonna_vertebrale_check == 1) echo "checked='checked'"; ?>/>
		          								</span>
	          									<span class="form-control" style="width:50%;background:#eee"><b>Colonna vertebrale&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></span>
		          								
		          								<select class="form-control" style="width:50%" id="situazione_colonna" onchange="stickyheaddsadaer()" name = "situazione_colonna" disabled>
		          									<option>Situazione di normalità</option>
		          									<option>Osteopenia</option>
		          									<option>Osteoporosi</option>
		          								</select>
		          							
	          								</div>
	          							</div>

	          							<div class="col-sm-6">
	          								
			            			
				          					<div class="row">
				          						<div class="col-sm-4">
				          							<div style="width:100%">
														<div class="input-group">
													
		                  						
							          						<span class="input-group-addon" style="text-align:left;background:#eee">Total T score</span>
							            					
							            					
							            					<input type="text" class="form-control" id="" name="" style="text-align:right;width:70px" value="<?php echo $Tscore_Spine ?>" disabled>
							            					
					          							</div>
					          						</div>
				          						</div>

				          						<div class="col-sm-4">
				          							<div style="width:100%">
														<div class="input-group">
					          								<span class="input-group-addon" style="text-align:left;background:#eee">Total Z score</span>
					            					
					            				
					            							<input type="text" style="width:70px;text-align:right" class="form-control" id="" name="" value="<?php echo $Zscore_Spine ?>" disabled>
					            					
					          							</div>
					          						</div>
				          						</div>
				          					</div>

	          							</div>

	          						</div>

	          						<script>
	          							$(document).ready(function(){
	          								if($('#colonna_vertebrale').prop('checked')){

	          									$('#situazione_colonna').prop('disabled',false);
	          								}



	          							});



	          						</script>

	          						
	          						<?php	if(strcmp($situazione_colonna, "Osteopenia")==0){  ?>
	          						
	          									<script>
	          										$('#situazione_colonna').prop('selectedIndex',1);
	          									</script>
	          						<?php  }  ?>
	          						
	          						<?php	if(strcmp($situazione_colonna, "Osteoporosi")==0){  ?>
	          						
	          									<script>
	          										$('#situazione_colonna').prop('selectedIndex',2);
	          									</script>
	          						<?php  }  ?>
	          						
	          						<?php	if(strcmp($situazione_colonna, "Situazione di normalità")==0){ ?>
	          						
	          									<script>
	          										$('#situazione_colonna').prop('selectedIndex',0);
	          									</script>
	          						<?php  }  ?>


	          						<script>
	          						//CODICE JAVASCRIPT PER RENDERE DISPONIBILE/INDISPONIBILE LA SELECT DI SITUAZIONE COLONNA VERTEBRALE IN BASE ALLA CHECK CORRISPONDENTE SE CLICCATA O NON CLICCATA
	          							$('#colonna_vertebrale').bind('click', function(){
	          								if($('#colonna_vertebrale').prop('checked') ){
	          									$('#situazione_colonna').prop('disabled',false);

	          								}
	          								else{
	          									$('#situazione_colonna').prop('selectedIndex',0);
	          									$('#situazione_colonna').prop('disabled',true);
	          								}
	          							});

	          						</script>


	          						<div class="row">
	          							
	          							<div class="col-sm-6">
	          								<div class="input-group">
	          									<span class="input-group-addon">
		          									<input type="checkbox"  name="collo_femore_sn" id="collo_femore_sn" onchange="stickyheaddsadaer()" <?php if($collo_femore_sn_check == 1) echo "checked='checked'"; ?>/>
		          								</span>
	          									<span class="form-control" style="width:50%;background:#eee"><b>Collo femore sinistro</b></span>
		          								<select class="form-control" style="width:50%" id="situazione_femore_sn" onchange="stickyheaddsadaer()"  name = "situazione_femore_sn" disabled>
		          									<option>Situazione di normalità</option>
		          									<option>Osteopenia</option>
		          									<option>Osteoporosi</option>
		          								</select>
		          							
	          								</div>
	          							</div>

	          							<div class="col-sm-6">
	          								
			            			
				          					<div class="row">
				          						<div class="col-sm-4">
				          							<div style="width:100%">
														<div class="input-group">
													
		                  						
							          						<span class="input-group-addon" style="text-align:left;background:#eee">Neck T score</span>
							            					
							            				
							            					<input type="text" class="form-control" id="" name="" style="text-align:right;width:70px" value="<?php echo $Tscore_HipL ?>" disabled>
							            					
					          							</div>
					          						</div>
				          						</div>

				          						<div class="col-sm-4">
				          							<div style="width:100%">
														<div class="input-group">
					          								<span class="input-group-addon" style="text-align:left;background:#eee">Neck Z score</span>
					            					
					            					
					            							<input type="text" style="width:70px;text-align:right" class="form-control" id="" name="" value="<?php echo $Zscore_HipL ?>" disabled>
					            					
					          							</div>
					          						</div>
				          						</div>
				          					</div>

	          							</div>



	          						</div>

	          						<script>
	          							$('#collo_femore_sn').bind('click',function(){
	          								if($('#collo_femore_sn').prop('checked')){
	          									$('#situazione_femore_sn').prop('disabled',false);
	          								}
	          								else{
	          									$('#situazione_femore_sn').prop('disabled',true);
	          									$('#situazione_femore_sn').prop('selectedIndex',0);	
	          								}

	          							});

	          						</script>

	          						
	          						
	          						

	          						<script>
	          							$(document).ready(function(){
	          								if($('#collo_femore_sn').prop('checked')){

	          									$('#situazione_femore_sn').prop('disabled',false);
	          								}


	          							});

	          						</script>

	          						<?php 	if(strcmp($situazione_femore_sn, "Situazione di normalità")==0){  ?>
	          									<script>
	          										$('#situazione_femore_sn').prop('selectedIndex',0);
	          									</script>
	          						<?php 	}   ?>

	          						<?php 	if(strcmp($situazione_femore_sn, "Osteopenia")==0){  ?>
	          									<script>
	          										$('#situazione_femore_sn').prop('selectedIndex',1);
	          									</script>
	          						<?php 	}  ?>

	          						<?php 	if(strcmp($situazione_femore_sn, "Osteoporosi")==0){  ?>
	          									<script>
	          										$('#situazione_femore_sn').prop('selectedIndex',2)
	          									</script>
	          						<?php 	}  ?>

	          						<div class="row">
	          							
	          							<div class="col-sm-6">
	          								<div style="width:100%">
		          								<div class="input-group">
		          									<span class="input-group-addon">
			          									<input type="checkbox"  name="collo_femore_dx" id="collo_femore_dx" onchange="stickyheaddsadaer()" <?php if($collo_femore_dx_check == 1) echo "checked='checked'"; ?>/>
			          								</span>
		          									<span class="form-control" style="width:50%;background:#eee"><b>Collo femore destro</b></span>
			          								<select class="form-control" style="width:50%" id="situazione_femore_dx" onchange="stickyheaddsadaer()" name = "situazione_femore_dx" disabled>
			          									<option>Situazione di normalità</option>
			          									<option>Osteopenia</option>
			          									<option>Osteoporosi</option>
			          								</select>
			          							
		          								</div>
		          							</div>
	          							</div>

	          							<div class="col-sm-6">
	          								
			            			
				          					<div class="row">
				          						<div class="col-sm-4">
				          							<div style="width:100%">
														<div class="input-group">
													
		                  						
							          						<span class="input-group-addon" style="text-align:left;background:#eee">Neck T score</span>
							            					
							            					
							            					<input type="text" class="form-control" id="" name="" style="text-align:right;width:70px" value="<?php echo $Tscore_HipR ?>" disabled>
							            					
					          							</div>
					          						</div>
				          						</div>

				          						<div class="col-sm-4">
				          							<div style="width:100%">
														<div class="input-group">
					          								<span class="input-group-addon" style="text-align:left;background:#eee">Neck Z score</span>
					            					
					            					
					            							<input type="text" style="width:70px;text-align:right" class="form-control" id="" name="" value="<?php echo $Zscore_HipR ?>" disabled>
					            					
					          							</div>
					          						</div>
				          						</div>
				          					</div>

	          							</div>

	          						</div>

	          						<script>
	          							$('#collo_femore_dx').bind('click',function(){
	          								if($('#collo_femore_dx').prop('checked')){
	          									$('#situazione_femore_dx').prop('disabled',false);
	          									
	          								}
	          								else{
	          									$('#situazione_femore_dx').prop('disabled',true);
	          									$('#situazione_femore_dx').prop('selectedIndex',0);	
	          								}
	          							});
	          						</script>

	          						<script>
	          							$(document).ready(function(){
	          								if($('#collo_femore_dx').prop('checked')){
	          									
	          									$('#situazione_femore_dx').prop('disabled',false);
	          									
	          								}
	          							});

	          						</script>
          							
          							<?php 	if(strcmp($situazione_femore_dx, "Situazione di normalità")==0){ ?>
												<script>
													$('#situazione_femore_dx').prop('selectedIndex',0);
												</script>
									<?php   }  ?>

									<?php 	if(strcmp($situazione_femore_dx, "Osteopenia")==0){  ?>
												<script>
													$('#situazione_femore_dx').prop('selectedIndex',1);
												</script>
									<?php 	}  ?>

									<?php 	if(strcmp($situazione_femore_dx, "Osteoporosi")==0){   ?>
												<script>
													$('#situazione_femore_dx').prop('selectedIndex',2);
												</script>
									<?php 	}  ?>        								  
		          					<br>
		          					<div class="row">
		          						<div class="col-sm-5">
		          							<div class="input-group">
		          								<span class="input-group-addon">
		          									<input type="checkbox" id="OsteoporosiGrave" onchange="stickyheaddsadaer()" name="OsteoporosiGrave" <?php if($osteoporosi_grave == 1) echo "checked='checked'" ?>/>
		          								</span>
		          								<span class="form-control" readonly><b>Osteoporosi grave per pregresse fratture</b></span>
		          							</div>
		          						</div>
		          					</div>

		          					
		            				
		            				<br/>
		            				<div class="row">
		              					<div class="col-sm-11">
		              						<h3><b>Dettaglio</b></h3>
		              						
		              						<div style="width:100%">
		            							<div class="input-group">
		              								<span class="input-group-addon">
		                								<input type="checkbox" name="ColonnaVertebraleParzialmenteNonAnalizzabile" id="ColonnaVertebraleParzialmenteNonAnalizzabile"  onchange="stickyheaddsadaer()" <?php if($vertebre_non_analizzate == 1) echo "checked='checked'"; ?>/>
		              								</span>
		              								
		              								<span class="input-group-addon" style="width:100%;text-align:left">
		              									<!--<input type="text" class="form-control" value="colonna vertebrale parzialmente non analizzabile per deformazioni morfologiche/disomogeneita' di densita" readonly>-->
		              									<div id="colonna_vertebrale_parzialmente">Colonna vertebrale parzialmente non analizzabile per deformazioni morfologiche di densità; vertebre valutate</div>
		              									
		            								</span>

		            								
		            								<span class="input-group-addon">
		            									<input type="checkbox" name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1" onchange="stickyheaddsadaer()" id="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1"  <?php if($vertebre_non_analizzate_L1 == 1) echo "checked='checked'"; ?> disabled>L1
		            									
		            								</span>

		            								<span class="input-group-addon">
		            									<input type="checkbox" name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2" onchange="stickyheaddsadaer()" id="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2"  <?php if($vertebre_non_analizzate_L2 == 1) echo "checked='checked'"; ?>disabled>L2
		            								</span>

		            								<span class="input-group-addon">
		            									<input type="checkbox" name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3" onchange="stickyheaddsadaer()" id="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3" <?php if($vertebre_non_analizzate_L3 == 1) echo "checked='checked'"; ?> disabled>L3
		            								</span>

		            								<span class="input-group-addon">
		            									<input type="checkbox" name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4" onchange="stickyheaddsadaer()" id="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4" <?php if($vertebre_non_analizzate_L4 == 1) echo "checked='checked'"; ?> disabled>L4
		            								</span>
		            							</div><!--/input-group-->
	            							</div>
	          							</div><!--/col-sm-4-->
		              						
		            				</div><!--/row-->


		            				<script type="text/javascript">
		            				//JAVASCRIPT PER CAMBIO DINAMICO COLONNA VERTEBRALE PARZIALMENTE NON ANALIZZABILE
		            					

		            					$('#ColonnaVertebraleParzialmenteNonAnalizzabile').bind('click',function(){
		            						if($('#ColonnaVertebraleParzialmenteNonAnalizzabile').prop('checked')){
		            							
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1').prop('disabled',false)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2').prop('disabled',false)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3').prop('disabled',false)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4').prop('disabled',false)

		            						}
		            						else{
												$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1').prop('disabled',true)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2').prop('disabled',true)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3').prop('disabled',true)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4').prop('disabled',true)

		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1').prop('checked',false)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2').prop('checked',false)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3').prop('checked',false)
		            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4').prop('checked',false)	            							
		            						}



		            					});


		            				</script>

		            				<script type="text/javascript">
							  			$(document).ready(function(){
											//$('#ColonnaVertebraleParzialmenteNonAnalizzabile').bind('click',function(){
												if($('#ColonnaVertebraleParzialmenteNonAnalizzabile').prop('checked'))
												{

													$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1').prop('disabled',false)
		            								$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2').prop('disabled',false)
		            								$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3').prop('disabled',false)
	            									$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4').prop('disabled',false)

												}
												else{
													$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1').prop('disabled',true)
			            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2').prop('disabled',true)
			            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3').prop('disabled',true)
			            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4').prop('disabled',true)

			            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1').prop('checked',false)
			            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2').prop('checked',false)
			            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3').prop('checked',false)
			            							$('#Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4').prop('checked',false)	

												}	
											//});
										});

									</script>

		            				<div class="row">
		              					<div class="col-sm-11">
		              						<div style="width:100%">
			                					<div class="input-group">
			                  						<span class="input-group-addon">
			                    						<input type="checkbox"  onchange="stickyheaddsadaer()" id="colonnavertebralenonanalizzabile" name="colonnavertebralenonanalizzabile" <?php if($colonna_non_analizzabile == 1) echo "checked='checked'";   ?>/>
			                  						</span>
			                  						<!--<input type="text" class="form-control" value="Colonna vertebrale interamente non analizzabile per deformazioni morfologiche e artefatti" readonly>-->
			                						<span class="form-control" style="width:100%;background:#eee">
			                							<div style="text-align:left">Colonna vertebrale interamente non analizzabile per deformazioni morfologiche e artefatti</div>
			                						</span>
			                						
			                					</div><!--/input-group-->
		                					</div>
		              					</div><!--/col-sm-4-->
		            

		            				</div><!--/row-->
		            				<div class="row">
		              					<div class="col-sm-11">
		            						<div style="width:100%">
		            							<div class="input-group">

			                  						<span class="input-group-addon">
			                    						<input type="checkbox" id="radio_colonna_valori_sup" onchange="stickyheaddsadaer()"  name="colonnavertebraleconvalori"  <?php if($colonna_valori_superiori == 1) echo "checked='checked'" ?>/>
			                  						</span>
			                  						
			                  						<span class="form-control" style="width:100%;text-align:left;background:#eee">
			                  							<div style="display: inline-block;" >Colonna vertebrale con valori di densità superiori alla norma per l'età per sospetta presenza di addensamenti osteosclerotici</div>
			                						</span>
			                						
			                					</div><!--/input-group-->
			                				</div>
		              					</div><!--/col-sm-11-->
		            				</div><!--/row-->


		            
		            				<div class="row">
		              					<div class="col-sm-11">
		              						<div style="width:100%">
			                					<div class="input-group">
			                  						<span class="input-group-addon">
			                    						<input type="checkbox" name="femorenonanalizzabile" id="femore_non_analizzabile" onchange="stickyheaddsadaer()" <?php if($femore_non_analizzabile == 1) echo "checked='checked'" ?>/>
			                  						</span>

			                  						<span class="form-control" style="width:100%;text-align:left;background:#eee">
			                  							Femore non analizzabile per presenza di protesi/alterazioni morfologiche
			                						</span>

			                						
			                					</div><!--/input-group-->
			                				</div>
		              					</div><!--/col-sm-4-->
		            				</div><!--/row-->

		            				<script>
		            					$(document).ready(function(){
		            						if($('#femore_non_analizzabile').prop('checked')){
		          								

		          								$('#collo_femore_sn').prop('disabled',true)
		          								$('#situazione_femore_sn').prop('disabled',true)
		          								$('#situazione_femore_sn').prop('selectedIndex',0)
		          								
		          								$('#collo_femore_dx').prop('disabled',true)
		          								$('#situazione_femore_dx').prop('disabled',true)
		          								$('#situazione_femore_dx').prop('selectedIndex',0)
		          								
		          								

		          								$('#collo_femore_sn').prop('checked', false)
		          								
		          								$('#collo_femore_dx').prop('checked', false)
		          								
		          							}


		            					});

		            				</script>

		            				<script>
		            				//SE È STATA CLICCATA LA CHECKBOX FEMORE NON ANALIZZABILE, DEVO RENDERE INDISPONIBILE TUTTE LE OPTION IN 
		            				//ANALISI DENSITOMETRICA

		          						$('#femore_non_analizzabile').bind('click',function(){
		          							if($('#femore_non_analizzabile').prop('checked')){
		          								

		          								$('#collo_femore_sn').prop('disabled',true)
		          								$('#situazione_femore_sn').prop('disabled',true)
		          								$('#situazione_femore_sn').prop('selectedIndex',0)
		          								
		          								$('#collo_femore_dx').prop('disabled',true)
		          								$('#situazione_femore_dx').prop('disabled',true)
		          								$('#situazione_femore_dx').prop('selectedIndex',0)
		          								
		          								

		          								$('#collo_femore_sn').prop('checked', false)
		          								
		          								$('#collo_femore_dx').prop('checked', false)
		          								
		          							}
		          							else{
		          								

		          								$('#collo_femore_sn').prop('disabled',false)
		          								$('#situazione_femore_sn').prop('disabled',false)
		          								
		          								$('#collo_femore_dx').prop('disabled',false)
		          								$('#situazione_femore_dx').prop('disabled',false)
		          								
		          								//SE ERANO STATE CLICCATE, LE DECLICCO
		          								

		          								$('#collo_femore_sn').prop('checked', false)
		          								
		          								$('#collo_femore_dx').prop('checked', false)
		          								
		          							}	
		          						});

										$('#colonnavertebralenonanalizzabile').bind('click',function(){
											if($('#colonnavertebralenonanalizzabile').prop('checked')){
												$('#colonna_vertebrale').prop('disabled',true)
		          								$('#situazione_colonna').prop('disabled',true)
		          								$('#situazione_colonna').prop('selectedIndex',0)
		          								
		          								$('#colonna_vertebrale').prop('checked', false)
		          								
											}
											else{
												$('#colonna_vertebrale').prop('disabled',false)
		          								$('#situazione_colonna').prop('disabled',false)
		          								
		          								//SE ERANO STATE CLICCATE, LE DECLICCO
		          								$('#colonna_vertebrale').prop('checked', false)
		          								
											}



										});
		          						



		          					</script>

		          					<script>

		          						$(document).ready(function(){
		          							if($('#colonnavertebralenonanalizzabile').prop('checked')){
		          								$('#colonna_vertebrale').prop('disabled',true);
		          								$('#situazione_colonna').prop('disabled',true);
		          							}


		          						});

		          					</script>
		            				
		            				<br/><br/>
		            				

		            				<h4><p style="text-align:center;"><b>STIMA DEL RISCHIO DI FRATTURA A 10 ANNI</b></p></h4>
		            				<br/>
		            				<div class="row">
		              					<div class="col-sm-4">
		              						<div style="width:100%">
		                					<div class="input-group">
		                						<span style="align:center" class="input-group-addon">
			            							<input type="checkbox" id="Frax_applicabile" onchange="stickyheaddsadaer()" name="Frax_applicabile" value="applicabile" <?php if($frax_applicabile == 1) echo "checked='checked'"  ?>/>
			            							Applicabile

			            						</span>
		                  						

		                  						<span class="form-control" style="align:center" readonly>FRAX&nbsp;&nbsp;&nbsp;</span>
		                  						<!--<span class="input-group-addon"><input type="checkbox" id="percentuale_frax" name="percentuale_frax" <?php //if($frax_percentuale == 1) echo "checked='checked'" ?>/>&nbsp;&nbsp;&nbsp;< 0.1%</span>-->
	                  							<!--<span style="visibility:hidden" class="input-group-addon"><input type="checkbox">&nbsp;&nbsp;&nbsp;> 50%</span><!--mi serve per tenere allineato-->
	                  						</div>
	                  					</div><!--width-->
	                  					</div>


	                  					<div class="col-sm-4">
	                  						<div style="width:100%">		
	                  							<div class="input-group">
	                  								
		              								<span class="input-group-addon" id="sizing-addon2">Fratture maggiori&nbsp;&nbsp;</span>
		              									<span class="input-group-addon"><input type="checkbox" id="percentuale_frax" name="percentuale_frax" <?php if($frax_percentuale == 1) echo "checked='checked'" ?>/>&nbsp;&nbsp;&nbsp;< 0.1%</span>
		              									<input type="text" style="text-align:right;width:70px"  class="frax form-control" id = "frax" onchange="stickyheaddsadaer()" name="frax_fratture_maggiori_intero" value="<?php if($frax_fratture_maggiori_intero != 'NULL') echo $frax_fratture_maggiori_intero ?>">
		              									<!--
		              									<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
		              									<input type="text" style="width:50px" class="frax form-control" id = "frax"  name="frax_fratture_maggiori_decimale" value="<?php if($frax_fratture_maggiori_decimale != 'NULL') echo $frax_fratture_maggiori_decimale ?>">
		              									-->

		              							</div>
		              						</div>
												

	                					</div>
		              					

		              					

	              						<div class="col-sm-4">
	              							<div class="input-group">
	              								
	              								<span class="input-group-addon" id="sizing-addon2">Collo femore&nbsp;&nbsp;</span>
	              								<span class="input-group-addon"><input type="checkbox" id="percentuale_collo_femore" name="percentuale_collo_femore" <?php if($frax_collo_femore_percentuale == 1) echo "checked='checked'" ?>/>&nbsp;&nbsp;&nbsp;< 0.1%</span>
	              								
	              									<input type="text" style="text-align:right;width:70px"  class="frax_collo_femore form-control" id = "frax_collo_femore" onchange="stickyheaddsadaer()" name="collo_femore_intero" value="<?php if($frax_collo_femore_intero != 'NULL') echo $frax_collo_femore_intero ?>">
	              									<!--
	              									<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
	              									<input type="text" style="width:50px" class="frax_collo_femore form-control" id = "frax_collo_femore"  name="collo_femore_decimale" value="<?php if($frax_collo_femore_decimale != 'NULL') echo $frax_collo_femore_decimale ?>">
	              									-->

	              							</div>
	              						</div>
		              							
		              					
		            				</div>

		            				<script>
		            					$('#Frax_applicabile').bind('click',function(){
		            						if($('#Frax_applicabile').prop('checked')){
		            							$('#percentuale_frax').prop('disabled',false);
		            							$('.frax').prop('disabled',false);
		            							$('#percentuale_collo_femore').prop('disabled',false);
		            							$('.frax_collo_femore').prop('disabled',false);

		            						}
		            						else{
		            							$('#percentuale_frax').prop('disabled',true);
		            							$('#percentuale_frax').prop('checked',false);
		            							$('.frax').prop('disabled',true);
		            							$('.frax').prop('value','');
		            							$('#percentuale_collo_femore').prop('disabled',true);
		            							$('#percentuale_collo_femore').prop('checked',false);
		            							$('.frax_collo_femore').prop('disabled',true);
		            							$('.frax_collo_femore').prop('value','');

		            						}



		            					});

		            				</script>

		            				<script>
		            					$(document).ready(function(){
		            						if($('#Frax_applicabile').prop('checked')){
		            							$('#percentuale_frax').prop('disabled',false);
		            							$('.frax').prop('disabled',false);
		            							$('#percentuale_collo_femore').prop('disabled',false);
		            							$('.frax_collo_femore').prop('disabled',false);
		            						}
		            						else{
		            							$('#percentuale_frax').prop('disabled',true);
		            							$('#percentuale_frax').prop('checked',false);
		            							$('.frax').prop('disabled',true);
		            							$('.frax').prop('value','');
		            							$('#percentuale_collo_femore').prop('disabled',true);
		            							$('#percentuale_collo_femore').prop('checked',false);
		            							$('.frax_collo_femore').prop('disabled',true);
		            							$('.frax_collo_femore').prop('value','');	
		            						}


		            					});

		            				</script>

		            				<script>
		            				
		            					$('#percentuale_frax').bind('click',function(){
		              						if($('#percentuale_frax').prop('checked')){
		              							
		              							$('.frax').prop('disabled',true);
		              							$('.frax').prop('value','');
		              							
		              						}
		              						else{
		              							$('.frax').prop('disabled',false);
		              							
		              							//$('.frax').prop('value','');
		              							
		              						}
		              					});

		              					$('#percentuale_collo_femore').bind('click',function(){

		              						if($('#percentuale_collo_femore').prop('checked')){

		              							$('.frax_collo_femore').prop('disabled',true);
		              							$('.frax_collo_femore').prop('value','');
		              						}
		              						else{
		              							$('.frax_collo_femore').prop('disabled',false);
		              							//$('.frax_collo_femore').prop('value','');	
		              						}
		              					})
									
	              					</script>

	              					<script>	
	              						$(document).ready(function(){
	              							if($('#percentuale_frax').prop('checked')){
		              							
		              							$('.frax').prop('disabled',true);
		              							$('.frax').prop('value','');
		              							
		              						}
		              						
		              						if($('#percentuale_collo_femore').prop('checked')){
		              							$('.frax_collo_femore').prop('disabled',true);
		              							$('.frax_collo_femore').prop('value','');
		              						}


	              						});

	              					</script>
	              					<!--PARTE NUOVA 31/10/2018-->
	              					<br/>
	              					<div class="row">
		              					<div class="col-sm-4">
		              						<div style="width:100%">
		                					<div class="input-group">
		                						<span style="align:center" class="input-group-addon">
			            							<input type="checkbox" id="Frax_aggiustato_applicabile" name="Frax_aggiustato_applicabile" value="applicabile" <?php if($frax_aggiustato_applicabile == 1) echo "checked='checked'"  ?>/>
			            							Applicabile

			            						</span>
		                  						

		                  						<span class="form-control" style="align:center" readonly>FRAX corretto per TBS&nbsp;&nbsp;&nbsp;</span>
		                  						
	                  						</div>
	                  					</div><!--width-->
	                  					</div>


	                  					<div class="col-sm-4">
	                  						<div style="width:100%">		
	                  							<div class="input-group">
	                  								
		              								<span class="input-group-addon" id="sizing-addon2">Fratture maggiori&nbsp;&nbsp;</span>
		              									<span class="input-group-addon"><input type="checkbox" id="percentuale_frax_aggiustato" name="percentuale_frax_aggiustato" <?php if($frax_aggiustato_percentuale == 1) echo "checked='checked'" ?>/>&nbsp;&nbsp;&nbsp;< 0.1%</span>
		              									<input type="text" style="text-align:right;width:70px"  class="frax_aggiustato_valore form-control" id = "frax_aggiustato_valore"  name="frax_aggiustato_valore" value="<?php if($frax_aggiustato_valore != 'NULL') echo $frax_aggiustato_valore ?>">
		              									<!--
		              									<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
		              									<input type="text" style="width:50px" class="frax form-control" id = "frax"  name="frax_fratture_maggiori_decimale" value="<?php if($frax_fratture_maggiori_decimale != 'NULL') echo $frax_fratture_maggiori_decimale ?>">
		              									-->

		              							</div>
		              						</div>
												

	                					</div>
		              					

		              					

	              						<div class="col-sm-4">
	              							<div class="input-group">
	              								
	              								<span class="input-group-addon" id="sizing-addon2">Collo femore&nbsp;&nbsp;</span>
	              								<span class="input-group-addon"><input type="checkbox" id="percentuale_collo_femore_aggiustato" name="percentuale_collo_femore_aggiustato" <?php if($frax_collo_femore_aggiustato_percentuale == 1) echo "checked='checked'" ?>/>&nbsp;&nbsp;&nbsp;< 0.1%</span>
	              								
	              									<input type="text" style="text-align:right;width:70px"  class="frax_collo_femore_aggiustato form-control" id = "frax_collo_femore_aggiustato" name="collo_femore_aggiustato_valore" value="<?php if($frax_collo_femore_aggiustato_valore != 'NULL') echo $frax_collo_femore_aggiustato_valore ?>">
	              									

	              							</div>
	              						</div>
		              							
		              					
		            				</div>

		            				<script>
		            					$('#Frax_aggiustato_applicabile').bind('click',function(){
		            						if($('#Frax_aggiustato_applicabile').prop('checked')){
		            							$('#percentuale_frax_aggiustato').prop('disabled',false);
		            							$('.frax_aggiustato_valore').prop('disabled',false);
		            							$('#percentuale_collo_femore_aggiustato').prop('disabled',false);
		            							$('.frax_collo_femore_aggiustato').prop('disabled',false);

		            						}
		            						else{
		            							$('#percentuale_frax_aggiustato').prop('disabled',true);
		            							$('#percentuale_frax_aggiustato').prop('checked',false);
		            							$('.frax_aggiustato_valore').prop('disabled',true);
		            							$('.frax_aggiustato_valore').prop('value','');
		            							$('#percentuale_collo_femore_aggiustato').prop('disabled',true);
		            							$('#percentuale_collo_femore_aggiustato').prop('checked',false);
		            							$('.frax_collo_femore_aggiustato').prop('disabled',true);
		            							$('.frax_collo_femore_aggiustato').prop('value','');

		            						}



		            					});

		            				</script>

		            				<script>
		            					$(document).ready(function(){
		            						if($('#Frax_aggiustato_applicabile').prop('checked')){
		            							$('#percentuale_frax_aggiustato').prop('disabled',false);
		            							$('.frax_aggiustato_valore').prop('disabled',false);
		            							$('#percentuale_collo_femore_aggiustato').prop('disabled',false);
		            							$('.frax_collo_femore_aggiustato').prop('disabled',false);
		            						}
		            						else{
		            							$('#percentuale_frax_aggiustato').prop('disabled',true);
		            							$('#percentuale_frax_aggiustato').prop('checked',false);
		            							$('.frax_aggiustato_valore').prop('disabled',true);
		            							$('.frax_aggiustato_valore').prop('value','');
		            							$('#percentuale_collo_femore_aggiustato').prop('disabled',true);
		            							$('#percentuale_collo_femore_aggiustato').prop('checked',false);
		            							$('.frax_collo_femore_aggiustato').prop('disabled',true);
		            							$('.frax_collo_femore_aggiustato').prop('value','');	
		            						}


		            					});

		            				</script>

		            				<script>
		            				
		            					$('#percentuale_frax_aggiustato').bind('click',function(){
		              						if($('#percentuale_frax_aggiustato').prop('checked')){
		              							
		              							$('.frax_aggiustato_valore').prop('disabled',true);
		              							$('.frax_aggiustato_valore').prop('value','');
		              							
		              						}
		              						else{
		              							$('.frax_aggiustato_valore').prop('disabled',false);
		              							
		              							//$('.frax').prop('value','');
		              							
		              						}
		              					});

		              					$('#percentuale_collo_femore_aggiustato').bind('click',function(){

		              						if($('#percentuale_collo_femore_aggiustato').prop('checked')){

		              							$('.frax_collo_femore_aggiustato').prop('disabled',true);
		              							$('.frax_collo_femore_aggiustato').prop('value','');
		              						}
		              						else{
		              							$('.frax_collo_femore_aggiustato').prop('disabled',false);
		              							//$('.frax_collo_femore').prop('value','');	
		              						}
		              					})
									
	              					</script>

	              					<script>	
	              						$(document).ready(function(){
	              							if($('#percentuale_frax_aggiustato').prop('checked')){
		              							
		              							$('.frax_aggiustato_valore').prop('disabled',true);
		              							$('.frax_aggiustato_valore').prop('value','');
		              							
		              						}
		              						
		              						if($('#percentuale_collo_femore_aggiustato').prop('checked')){
		              							$('.frax_collo_femore_aggiustato').prop('disabled',true);
		              							$('.frax_collo_femore_aggiustato').prop('value','');
		              						}


	              						});

	              					</script>
		              				
	              					<br/>
	              					<div class="row">
		              					<div class="col-sm-4">
		              						<div style="width:100%">
		                					<div class="input-group">
		                						<span style="align:center" class="input-group-addon">
			            							<input type="checkbox" id="tbs_colonna_applicabile" onchange="stickyheaddsadaer()" name="tbs_colonna_applicabile" value="applicabile" <?php if($tbs_colonna_applicabile == 1) echo "checked='checked'"  ?>/>
			            							Applicabile

			            						</span>
		                  						

		                  						<span class="form-control" style="align:center" readonly>TBS colonna&nbsp;&nbsp;&nbsp;</span>
		                  						
	                  						</div>
	                  					</div><!--width-->
	                  					</div>


	                  					<div class="col-sm-4">
	                  						<div style="width:100%">		
	                  							<div class="input-group">
	                  								<!--NON SO COSA SCRIVERCI QUINDI LO COMMENTO NEL CASO DOVRO' SCRIVERE QUALCOSA-->
		              								<!--<span class="input-group-addon" id="sizing-addon2">Fratture maggiori&nbsp;&nbsp;</span>-->
	              									<span class="input-group-addon"><input type="checkbox" id="percentuale_tbs_colonna" name="percentuale_tbs_colonna" <?php if($percentuale_tbs_colonna == 1) echo "checked='checked'" ?>/>&nbsp;&nbsp;&nbsp;< 0.1%</span>
	              									<input type="text" style="text-align:right;width:70px"  class="tbs_colonna_valore form-control" onchange="stickyheaddsadaer()" id = "tbs_colonna_valore"  name="tbs_colonna_valore" value="<?php if($tbs_colonna_valore != 'NULL') echo $tbs_colonna_valore ?>">
		              									

		              							</div>
		              						</div>
												

	                					</div>
	                				</div>

	                				<script>
		            					$('#tbs_colonna_applicabile').bind('click',function(){
		            						if($('#tbs_colonna_applicabile').prop('checked')){
		            							$('#percentuale_tbs_colonna').prop('disabled',false);
		            							$('.tbs_colonna_valore').prop('disabled',false);
		            							$('#percentuale_tbs_colonna').prop('disabled',false);
		            							$('.tbs_colonna_valore').prop('disabled',false);

		            						}
		            						else{
		            							$('#percentuale_tbs_colonna').prop('disabled',true);
		            							$('#percentuale_tbs_colonna').prop('checked',false);
		            							$('.tbs_colonna_valore').prop('disabled',true);
		            							$('.tbs_colonna_valore').prop('value','');
		            							$('#percentuale_tbs_colonna').prop('disabled',true);
		            							$('#percentuale_tbs_colonna').prop('checked',false);
		            							$('.tbs_colonna_valore').prop('disabled',true);
		            							$('.tbs_colonna_valore').prop('value','');

		            						}



		            					});

		            				</script>

		            				<script>
		            					$(document).ready(function(){
		            						if($('#tbs_colonna_applicabile').prop('checked')){
		            							$('#percentuale_tbs_colonna').prop('disabled',false);
		            							$('.tbs_colonna_valore').prop('disabled',false);
		            							$('#percentuale_tbs_colonna').prop('disabled',false);
		            							$('.tbs_colonna_valore').prop('disabled',false);
		            						}
		            						else{
		            							$('#percentuale_tbs_colonna').prop('disabled',true);
		            							$('#percentuale_tbs_colonna').prop('checked',false);
		            							$('.tbs_colonna_valore').prop('disabled',true);
		            							$('.tbs_colonna_valore').prop('value','');
		            							$('#percentuale_tbs_colonna').prop('disabled',true);
		            							$('#percentuale_tbs_colonna').prop('checked',false);
		            							$('.tbs_colonna_valore').prop('disabled',true);
		            							$('.tbs_colonna_valore').prop('value','');	
		            						}


		            					});

		            				</script>

		            				<script>
		            				
		            					$('#percentuale_tbs_colonna').bind('click',function(){
		              						if($('#percentuale_tbs_colonna').prop('checked')){
		              							
		              							$('.tbs_colonna_valore').prop('disabled',true);
		              							$('.tbs_colonna_valore').prop('value','');
		              							
		              						}
		              						else{
		              							$('.tbs_colonna_valore').prop('disabled',false);
		              							
		              							//$('.frax').prop('value','');
		              							
		              						}
		              					});

		              					$('#percentuale_tbs_colonna').bind('click',function(){

		              						if($('#percentuale_tbs_colonna').prop('checked')){

		              							$('.tbs_colonna_valore').prop('disabled',true);
		              							$('.tbs_colonna_valore').prop('value','');
		              						}
		              						else{
		              							$('.tbs_colonna_valore').prop('disabled',false);
		              							//$('.frax_collo_femore').prop('value','');	
		              						}
		              					})
									
	              					</script>

	              					<script>	
	              						$(document).ready(function(){
	              							if($('#percentuale_tbs_colonna').prop('checked')){
		              							
		              							$('.tbs_colonna_valore').prop('disabled',true);
		              							$('.tbs_colonna_valore').prop('value','');
		              							
		              						}
		              						
		              						if($('#percentuale_tbs_colonna').prop('checked')){
		              							$('.tbs_colonna_valore').prop('disabled',true);
		              							$('.tbs_colonna_valore').prop('value','');
		              						}


	              						});

	              					</script>

		            				
	              					<br/>
	              					<!--PARTE VECCHIA-->
		            				<div class="row">
		              					<div class="col-sm-4">
		              						<div style="width:100%">
		                					<div class="input-group">
		                						<span style="align:center" class="input-group-addon">
			            							<input type="checkbox" id="defra_applicabile" onchange="stickyheaddsadaer()" name="defra_applicabile" value="defra_applicabile" <?php if($defra_applicabile == 1) echo "checked='checked'" ?>/>
			            							Applicabile

			            						</span>
		                  						<span class="form-control" style="align:center" readonly>DeFRACalc</span>
	                  							<!--
	                  							<span class="input-group-addon"><input type="checkbox" id="percentuale_defra_01" class="percentuale_defra" name="defra_percentuale_01" <?php if($defra_percentuale_01 == 1) echo "checked='checked'" ?> disabled/>&nbsp;&nbsp;< 0.1%</span>
	                  							<span class="input-group-addon"><input type="checkbox" id="percentuale_defra_50" class="percentuale_defra" name="defra_percentuale_50" <?php if($defra_percentuale_50 == 1) echo "checked='checked'" ?> disabled/>&nbsp;&nbsp;> 50%</span>
												-->
	                  							
		                					</div>
		                				</div>
		              					</div>

		              					
		              					
		              					<div class="col-sm-4">
		              						<div style="width:100%">
		              						<div class="input-group">
		              							<span class="input-group-addon"><input type="checkbox" id="percentuale_defra_01" class="percentuale_defra" name="defra_percentuale_01" <?php if($defra_percentuale_01 == 1) echo "checked='checked'" ?> disabled/>&nbsp;&nbsp;< 0.1%</span>
	                  							<span class="input-group-addon"><input type="checkbox" id="percentuale_defra_50" class="percentuale_defra" name="defra_percentuale_50" <?php if($defra_percentuale_50 == 1) echo "checked='checked'" ?> disabled/>&nbsp;&nbsp;> 50%</span>
              									<input type="text" style="text-align:right;width:70px"  class="defra form-control" id = "defra_intero" onchange="stickyheaddsadaer()"  name="defra_intero" value="<?php if($defra_intero != 'NULL') echo $defra_intero ?>">
              									<!--
              									<input type="text" style="width:30px;font-size:20px" value="." class="form-control" readonly>
              									<input type="text" style="width:50px" class="defra form-control" id = "defra_decimale"  name="defra_decimale" value="<?php if($defra_decimale != 'NULL') echo $defra_decimale ?>">
              									-->
		              						</div>
		              					</div><!--width-->
		              					</div>

		              					<div class="col-sm-2">
		              					</div>
		            				</div>
		            				<br/><br/>

		            				
		            				<script>
		            					$('#defra_applicabile').bind('click',function(){
		            						if($('#defra_applicabile').prop('checked')){

		            							$('#percentuale_defra_01').prop('disabled',false);
		            							$('#percentuale_defra_50').prop('disabled',false);
		            							$('#defra_intero').prop('disabled',false);
		            							$('#defra_decimale').prop('disabled',false);

		            						}
		            						else{
												$('#percentuale_defra_01').prop('disabled',true);
		            							$('#percentuale_defra_50').prop('disabled',true);
		            							$('#defra_intero').prop('disabled',true);
		            							$('#defra_decimale').prop('disabled',true);

		            							$('#percentuale_defra_01').prop('checked',false);
		            							$('#percentuale_defra_50').prop('checked',false);
												$('#defra_intero').prop('value','');
		            							$('#defra_decimale').prop('value','');		            							

		            						}


		            					});

		            				</script>

		            				<script>
		            					$(document).ready(function(){
		            						if($('#defra_applicabile').prop('checked')){

		            							$('#percentuale_defra_01').prop('disabled',false);
		            							$('#percentuale_defra_50').prop('disabled',false);
		            							$('#defra_intero').prop('disabled',false);
		            							$('#defra_decimale').prop('disabled',false);

		            						}
		            						else{
												$('#percentuale_defra_01').prop('disabled',true);
		            							$('#percentuale_defra_50').prop('disabled',true);
		            							$('#defra_intero').prop('disabled',true);
		            							$('#defra_decimale').prop('disabled',true);

		            							$('#percentuale_defra_01').prop('checked',false);
		            							$('#percentuale_defra_50').prop('checked',false);
												$('#defra_intero').prop('value','');
		            							$('#defra_decimale').prop('value','');		            							

		            						}


		            					});

		            				</script>

		            				<script>
			            				$('#percentuale_defra_01').bind('click',function(){	
			            					if($('#percentuale_defra_01').prop('checked')){
			            						
			            						$('#defra_intero').prop('disabled',true);
			            						$('#defra_decimale').prop('disabled',true);
			            						$('#percentuale_defra_50').prop('checked',false);
			            						$('#defra_intero').prop('value','');
			            						$('#defra_decimale').prop('value','');
			            					}
			            					else{
			            						$('#defra_intero').prop('disabled',false);
			            						$('#defra_decimale').prop('disabled',false);
			            						
			            					}
											stickyheaddsadaer();
			            				});

			            				$('#percentuale_defra_50').bind('click',function(){	
			            					if($('#percentuale_defra_50').prop('checked')){
			            						$('#defra_intero').prop('disabled',true);
			            						$('#defra_decimale').prop('disabled',true);
			            						$('#percentuale_defra_01').prop('checked',false);
			            						$('#defra_intero').prop('value','');
			            						$('#defra_decimale').prop('value','');
			            					}
			            					else{
			            						$('#defra_intero').prop('disabled',false);
			            						$('#defra_decimale').prop('disabled',false);
			            						
			            					}
											stickyheaddsadaer();
			            				});

			            				



		            				</script>

	            					<script>
	            						$(document).ready(function(){
	            							if($('#percentuale_defra_01').prop('checked')){
			            						
			            						$('#defra_intero').prop('disabled',true);
			            						$('#defra_decimale').prop('disabled',true);
			            						$('#percentuale_defra_50').prop('checked',false);
			            						$('#defra_intero').prop('value','');
			            						$('#defra_decimale').prop('value','');
			            					}
			            					
			            					if($('#percentuale_defra_50').prop('checked')){
			            						$('#defra_intero').prop('disabled',true);
			            						$('#defra_decimale').prop('disabled',true);
			            						$('#percentuale_defra_01').prop('checked',false);
			            						$('#defra_intero').prop('value','');
			            						$('#defra_decimale').prop('value','');
			            					}
			            					



	            						});

		            				</script>



		            				<h4><p style="text-align:center;"><b>INDICAZIONI DI PREVENZIONE E TERAPIA</b></p></h4>
		            				<!--ORMONALI-->
		            				<?php
				        					
			      						$pezzi_terapie_ormonali = explode("\n", $terapie_ormonali);
			      						$pezzi_terapie_ormonali1 = explode("\n", $terapie_ormonali);
				      					//echo $pezzi_terapie_ormonali[1];
			      						//$pezzi_terapia_ormonale1 = explode("\n", $valori_ormonale);
			      						

									?>
									<br/><br/>
									<!--TERAPIE ORMONALI-->
                                    
									
									<br>


                                   

									<form>
									<!--
									<input class="btn btn-primary" type="button" value = "Suggerisci" name="SubmitButton" onclick = 'stickyheaddsadaer(<?php echo $args; ?>)'/>
									<br/>
									<br/>-->

									</form>
								
									
									<?php

										
										//$s = '"'.$pk.'"'.$datascan_mysql;
										/*$command = escapeshellcmd('java -jar /home/kkk/IdeaProjects/OsteoporosiJava/out/artifacts/OsteoporosiJava_jar/OsteoporosiJava.jar nnnn uiuiui');
										$output = shell_exec($command);
										echo ": ".$output;*/

										/*ob_start();
										passthru('.python2.7 main.py 2>&1');
										$output = ob_get_clean();
										echo ": ".$output;
										
										$command = 'python2.7 main.py 2>&1';

										echo exec($command, $out, $status);
										echo ": ".$out;

										
										$last_line = system('./main.py 2>&1', $retval);
										echo "<br>";
										echo $retval;
										echo $last_line;

										echo "<br>";
										$command = escapeshellcmd('pwd');
										$output = shell_exec($command);
										echo ": ".$output;*/
									?>



									<br/>
							
									<div class="row">
										<div class="col-sm-3">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="TerapieOrmonali" name="TerapieOrmonali" value="TerapieOrmonali" <?php if($terapie_ormonali_check == 1) echo "checked='checked'"   ?>/>
				            					</span>
				            					<input type="text" class="form-control" value="Terapie ormonali" readonly>

				          					</div><!--/input-group-->
											  <!--primo bottone-->
											  <input class="btn btn-primary" id = "ter_orm_sugg_button" type="button" value = "Suggerisci" name="SubmitButton" onclick = 'myFunction(this.id)'/>

				        				</div><!--/col-sm-3-->

				        				<div class="col-sm-3">
				        					<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					
				            					<select class="form-control" name="TipoTerapiaOrmonale" id="TipoTerapiaOrmonale">
				            						<option value="">Tipo</option>
				            						<?php
                                                    ///qui contiene solo la query
				            						$ormonale = "SELECT NOME FROM GestioneInterna WHERE TIPO='Orm.Sost./C.O.'";
				            						$res = mysqli_query($mysqli,$ormonale);
				            						while($row = mysqli_fetch_assoc($res)){
				            							echo "<option>$row[NOME]</option>";
				            						}



				            						?>
				            						
				            						
				            						
				            					</select>
				            					

				          					</div><!--/input-group-->
				          					<input type="button" value="aggiungi" id="aggiungi_terapia_ormonale" name="aggiungi_terapia_ormonale" onclick="app4();">
			          						<input type="button" value="cancella" id="cancella_tipo_terapia_ormonale" onclick="remove4();">
	
				        				</div>

				        				<div class="col-sm-6">
				        					<?php $i = 0; ?>
			        						<select style="height:100px;width:500px;" size="5" class="text_area_terapie_ormonali" name="text_area_terapie_ormonali" id="text_area_terapie_ormonali">
			        							<?php  while($pezzi_terapie_ormonali[$i] != null){ if($pezzi_terapie_ormonali[$i] != 'NULL') echo "<option> $pezzi_terapie_ormonali[$i] </option>"; $i=$i+1;} ?>


			        						</select>

			        						<textarea style="visibility:hidden;" name="valori_terapie_ormonali" id="valori_terapie_ormonali"><?php $j = 0; while($pezzi_terapie_ormonali1[$j] != null){ echo $pezzi_terapie_ormonali1[$j]."\n"; $j=$j+1; } ?></textarea>
				        					
				        				</div>


									</div><!--/row-->


		            				
			        				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI TERAPIE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI3{}, CONTENENTE TUTTI I TIPI INSERITI
			        					//tipi3 vettore per terapie ormonali
			        					var tipi3 = {};
			        					function app4(){
			        						var x = document.getElementById("text_area_terapie_ormonali");
        									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#TipoTerapiaOrmonale option:selected").text();
											
											
											if(tipi3[tipo]){
												alert("Tipo già inserito");
											}
											else if(tipo == "Tipo"){
												alert("Selezionare un tipo");
											}
											else{
												$("textarea#valori_terapie_ormonali").val($("textarea#valori_terapie_ormonali").val() + tipo+"\n");
												tipi3[tipo] = true;
												option.text = tipo;
												x.add(option);
											}
											
										}


										function remove4(){
											var x = document.getElementById("text_area_terapie_ormonali");
											//var tipo = x.split()
											var i;

											var y = document.getElementById("valori_terapie_ormonali");
											y.value = y.value.replace(x.value, "");
											
  											for (i = x.length - 1; i>=0; i--) {
    											if (x.options[i].selected) {
    												
      												var sel = $("#text_area_terapie_ormonali option:selected").text(); //prendo il valore del tipo che voglio cancellare
      												//alert(v);
      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
      												
      												tipi3[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
      												x.remove(i); //lo rimuovo come option

    											}
  											}
										}

			        				</script>

				        				

				        				

				        			<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					$('#TipoTerapiaOrmonale').prop('disabled',true)
	        							$('#aggiungi_terapia_ormonale').prop('disabled',true)
		        						$('#text_area_terapie_ormonali').prop('disabled',true)
		        						$('#cancella_tipo_terapia_ormonale').prop('disabled',true)


				        				$('#TerapieOrmonali').bind('click',function(){
				        					if($('#TerapieOrmonali').prop('checked')){
				        						
				        						$('#TipoTerapiaOrmonale').prop('disabled',false)
				        						$('#aggiungi_terapia_ormonale').prop('disabled',false)
				        						$('#text_area_terapie_ormonali').prop('disabled',false)
				        						$('#cancella_tipo_terapia_ormonale').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoTerapiaOrmonale').prop('disabled',true)
				        						$('#aggiungi_terapia_ormonale').prop('disabled',true)
				        						$('#text_area_terapie').prop('disabled',true)
				        						$('#cancella_tipo_terapia_ormonale').prop('disabled',true)
				        						$('#TipoTerapiaOrmonale').prop('selectedIndex',0)
				        						tipi3 = {};
				        						$('#text_area_terapie_ormonali').empty();

				        						$('#valori_terapie_ormomnali').val('');
				        					}


				        				});


			        				</script>

			        				<script>
			        					$(document).ready(function(){
			        						if($('#TerapieOrmonali').prop('checked')){
			        							$('#TipoTerapiaOrmonale').prop('disabled',false)
				        						$('#aggiungi_terapia_ormonale').prop('disabled',false)
				        						$('#text_area_terapie_ormonali').prop('disabled',false)
				        						$('#cancella_tipo_terapia_ormonale').prop('disabled',false)
			        						}
			        						else{
			        							$('#TipoTerapiaOrmonale').prop('disabled',true)
				        						$('#aggiungi_terapia_ormonale').prop('disabled',true)
				        						$('#text_area_terapie').prop('disabled',true)
				        						$('#cancella_tipo_terapia_ormonale').prop('disabled',true)
				        						$('#TipoTerapiaOrmonale').prop('selectedIndex',0)
				        						tipi3 = {};
				        						$('#text_area_terapie_ormonali').empty();

				        						$('#valori_terapie_ormomnali').val('');
			        						}


			        					})

			        				</script>
			        				<br/>
			        				<!-- FIN QUA-->
			        				
			        				<?php
				        					
			      						$pezzi_terapie_osteo = explode("\n", $terapie_osteoprotettive);
				      						
			      						

									?>

									<!--TERAPIE OSTEOPROTETTIVE-->
									

									<div class="row">
										<div class="col-sm-3">
											<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="TerapieOsteoprotettive" name="TerapieOsteoprotettive" value="TerapieOsteoprotettive" <?php if($terapie_osteoprotettive_check == 1) echo "checked='checked'"  ?>/>
				            					</span>
				            					<input type="text" class="form-control" value="Terapie osteoprotettive" readonly>

				          					</div><!--/input-group-->
											  <input class="btn btn-primary" id = "ter_osteo_sugg_button" type="button" value = "Suggerisci" name="SubmitButton" onclick = 'myFunction(this.id)'/>

				          				</div>
				          				<div class="col-sm-3">	
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					
				            					<select class="form-control" name="TipoTerapiaOsteoprotettiva" id="TipoTerapiaOsteoprotettiva">
				            						<option value="">Tipo</option>
				            						<?php
				            						$osteoprotettive = "SELECT NOME FROM GestioneInterna WHERE TIPO='Osteoprotettiva specifica'";
				            						$res = mysqli_query($mysqli,$osteoprotettive);
				            						while($row = mysqli_fetch_assoc($res)){
				            							echo "<option>$row[NOME]</option>";
				            						}



				            						?>
				            						
				            						
				            						
				            					</select>
				            					

				          					</div><!--/input-group-->
				          					<input type="button" value="aggiungi" id="aggiungi_terapia_osteoprotettiva" name="aggiungi_terapia_osteoprotettiva" onclick="app6();">
				          					<input type="button" value="cancella" id="cancella_tipo_terapia_osteoprotettiva" onclick="remove6();">

				            			</div>
				            					
										<div class="col-sm-6">
											<?php $i = 0; ?>
			        						<select style="height:100px;width:500px;" size="5" class="text_area_terapie_osteoprotettive" name="text_area_terapie_osteoprotettive" id="text_area_terapie_osteoprotettive"><?php  while($pezzi_terapie_osteo[$i] != null){ if($pezzi_terapie_osteo[$i] != 'NULL') echo "<option> $pezzi_terapie_osteo[$i] </option>"; $i=$i+1;} ?></select>
			        						<textarea style="visibility:hidden;" name="valori_terapie_osteoprotettive" id="valori_terapie_osteoprotettive"><?php $j = 0; while($pezzi_terapie_osteo[$j] != null){ echo $pezzi_terapie_osteo[$j]."\n"; $j=$j+1; } ?></textarea>
				        					
										</div>

									</div><!--/row-->
			        				
			        				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI TERAPIE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI5{}, CONTENENTE TUTTI I TIPI INSERITI
			        					//tipi5 vettore per terapie osteoprotettive
			        					var tipi4 = {};
			        					function app6(){
			        						var x = document.getElementById("text_area_terapie_osteoprotettive");
        									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#TipoTerapiaOsteoprotettiva option:selected").text();
											//alert(tipo);
											
											if(tipi4[tipo]){
												alert("Tipo già inserito");
											}
											else if(tipo == "Tipo"){
												alert("Selezionare un tipo");
											}
											else{
												$("textarea#valori_terapie_osteoprotettive").val($("textarea#valori_terapie_osteoprotettive").val() + tipo+"\n");
												tipi4[tipo] = true;
												option.text = tipo;
												x.add(option);
											}
											
										}


										function remove6(){
											var x = document.getElementById("text_area_terapie_osteoprotettive");
											//var tipo = x.split()
											var i;

											var y = document.getElementById("valori_terapie_osteoprotettive");
											y.value = y.value.replace(x.value, "");
											
  											for (i = x.length - 1; i>=0; i--) {
    											if (x.options[i].selected) {
    												
      												var sel = $("#text_area_terapie_osteoprotettive option:selected").text(); //prendo il valore del tipo che voglio cancellare
      												//alert(v);
      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
      												
      												tipi4[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
      												x.remove(i); //lo rimuovo come option

    											}
  											}
										}

			        				</script>

				        				

				        			<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					$('#TipoTerapiaOsteoprotettiva').prop('disabled',true)
	        							$('#aggiungi_terapia_osteoprotettiva').prop('disabled',true)
		        						$('#text_area_terapie_osteoprotettive').prop('disabled',true)
		        						$('#cancella_tipo_terapia_osteoprotettiva').prop('disabled',true)


				        				$('#TerapieOsteoprotettive').bind('click',function(){
				        					if($('#TerapieOsteoprotettive').prop('checked')){
				        						
				        						$('#TipoTerapiaOsteoprotettiva').prop('disabled',false)
				        						$('#aggiungi_terapia_osteoprotettiva').prop('disabled',false)
				        						$('#text_area_terapie_osteoprotettive').prop('disabled',false)
				        						$('#cancella_tipo_terapia_osteoprotettiva').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoTerapiaOsteoprotettiva').prop('disabled',true)
				        						$('#aggiungi_terapia_osteoprotettiva').prop('disabled',true)
				        						$('#text_area_terapie_osteoprotettive').prop('disabled',true)
				        						$('#cancella_tipo_terapia_osteoprotettiva').prop('disabled',true)
				        						$('#TipoTerapiaOsteoprotettiva').prop('selectedIndex',0)
				        						tipi4 = {};
				        						$('#text_area_terapie_osteoprotettive').empty();

				        						$('#valori_terapie_osteoprotettive').val('');
				        					}


				        				});


			        				</script>


			        				<script>
			        					$(document).ready(function(){
			        						if($('#TerapieOsteoprotettive').prop('checked')){
				        						
				        						$('#TipoTerapiaOsteoprotettiva').prop('disabled',false)
				        						$('#aggiungi_terapia_osteoprotettiva').prop('disabled',false)
				        						$('#text_area_terapie_osteoprotettive').prop('disabled',false)
				        						$('#cancella_tipo_terapia_osteoprotettiva').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoTerapiaOsteoprotettiva').prop('disabled',true)
				        						$('#aggiungi_terapia_osteoprotettiva').prop('disabled',true)
				        						$('#text_area_terapie_osteoprotettive').prop('disabled',true)
				        						$('#cancella_tipo_terapia_osteoprotettiva').prop('disabled',true)
				        						$('#TipoTerapiaOsteoprotettiva').prop('selectedIndex',0)
				        						tipi4 = {};
				        						$('#text_area_terapie_osteoprotettive').empty();

				        						$('#valori_terapie_osteoprotettive').val('');
				        					}


			        					});


			        				</script>
			        				<br/>
			        				<!-- FIN QUA-->
			        				

			        				<?php
				        					
			      						$pezzi_vitamina_d_terapia = explode("\n", $vitamina_d_terapia);
				      						
			      						//$pezzi_ormonale1 = explode("\n", $vitamina_d_terapia);
			      						

									?>
									<!--VITAMINA D TERAPIA-->
									<div class="row">
										<div class="col-sm-3">
				          					<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="VitaminaDTerapia" name="VitaminaDTerapia" value="VitaminaDTerapia" <?php if($vitamina_d_terapia_check == 1) echo "checked='checked'";  ?>/>
				            					</span>
				            					<input type="text" class="form-control" value="Vitamina D Terapia" readonly>
				          					</div><!--/input-group-->
										  <input class="btn btn-primary" id = "vit_d_ter_sugg_button" type="button" value = "Suggerisci" name="SubmitButton" onclick = 'myFunction(this.id)'/>
				        				</div>

				        				<div class="col-sm-3">
				        					<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					
				            					<select class="form-control" name="TipoVitaminaDTerapia" id="TipoVitaminaDTerapia">
				            						<option value="">Tipo</option>
				            						<?php
				            						$vitamina_d_terapia = "SELECT NOME FROM GestioneInterna WHERE TIPO='Vitamina D terapia'";
				            						$res = mysqli_query($mysqli,$vitamina_d_terapia);
				            						while($row = mysqli_fetch_assoc($res)):
				            							echo "<option>$row[NOME]</option>";
				            						endwhile;



				            						?>
				            						
				            						
				            						
				            					</select>
				            					

				          					</div><!--/input-group-->
				          					<input type="button" value="aggiungi" id="aggiungi_vitamina_d_terapia" name="aggiungi_vitamina_d_terapia" onclick="app7();">
				          					<input type="button" value="cancella" id="cancella_vitamina_d_terapia" onclick="remove7();">

				        				</div>
				        				<div class="col-sm-6">
				        					<?php $i = 0; ?>
			        						<select style="height:100px;width:500px;" size="5" class="text_area_vitamina_d_terapia" name="text_area_vitamina_d_terapia" id="text_area_vitamina_d_terapia"><?php  while($pezzi_vitamina_d_terapia[$i] != null){ if($pezzi_vitamina_d_terapia[$i] != 'NULL') echo "<option> $pezzi_vitamina_d_terapia[$i] </option>"; $i=$i+1;} ?></select>
			        						<textarea style="visibility:hidden;" name="valori_vitamina_d_terapia" id="valori_vitamina_d_terapia"><?php $j = 0; while($pezzi_vitamina_d_terapia[$j] != null){ echo $pezzi_vitamina_d_terapia[$j]."\n"; $j=$j+1; } ?></textarea>
				        					
				        				</div>
										

									</div><!--/row-->
									<br/>
															
			        				
			        				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI TERAPIE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI6{}, CONTENENTE TUTTI I TIPI INSERITI
			        					//tipi6 vettore per vitamina d terapia
			        					var tipi6 = {};
			        					function app7(){
			        						var x = document.getElementById("text_area_vitamina_d_terapia");
        									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#TipoVitaminaDTerapia option:selected").text();
											//alert(tipo);
											
											if(tipi6[tipo]){
												alert("Tipo già inserito");
											}
											else if(tipo == "Tipo"){
												alert("Selezionare un tipo");
											}
											else{
												$("textarea#valori_vitamina_d_terapia").val($("textarea#valori_vitamina_d_terapia").val() + tipo+"\n");
												tipi6[tipo] = true;
												option.text = tipo;
												x.add(option);
											}
											
										}


										function remove7(){
											var x = document.getElementById("text_area_vitamina_d_terapia");
											//var tipo = x.split()
											var i;

											var y = document.getElementById("valori_vitamina_d_terapia");
											y.value = y.value.replace(x.value, "");
											
  											for (i = x.length - 1; i>=0; i--) {
    											if (x.options[i].selected) {
    												
      												var sel = $("#text_area_vitamina_d_terapia option:selected").text(); //prendo il valore del tipo che voglio cancellare
      												//alert(v);
      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
      												
      												tipi6[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
      												x.remove(i); //lo rimuovo come option

    											}
  											}
										}

			        				</script>

				        			
				        			<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					$('#TipoVitaminaDTerapia').prop('disabled',true)
	        							$('#aggiungi_vitamina_d_terapia').prop('disabled',true)
		        						$('#text_area_vitamina_d_terapia').prop('disabled',true)
		        						$('#cancella_vitamina_d_terapia').prop('disabled',true)


				        				$('#VitaminaDTerapia').bind('click',function(){
				        					if($('#VitaminaDTerapia').prop('checked')){
				        						
				        						$('#TipoVitaminaDTerapia').prop('disabled',false)
				        						$('#aggiungi_vitamina_d_terapia').prop('disabled',false)
				        						$('#text_area_vitamina_d_terapia').prop('disabled',false)
				        						$('#cancella_vitamina_d_terapia').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoVitaminaDTerapia').prop('disabled',true)
				        						$('#aggiungi_vitamina_d_terapia').prop('disabled',true)
				        						$('#text_area_vitamina_d_terapia').prop('disabled',true)
				        						$('#cancella_vitamina_d_terapia').prop('disabled',true)
				        						$('#TipoVitaminaDTerapia').prop('selectedIndex',0)
				        						tipi6 = {};
				        						$('#text_area_vitamina_d_terapia').empty();

				        						$('#valori_vitamina_d_terapia').val('');
				        					}


				        				});


			        				</script>
			        				<br/>
			        				<!-- FIN QUA-->

			        				<script>
			        					$(document).ready(function(){
			        						if($('#VitaminaDTerapia').prop('checked')){
				        						
				        						$('#TipoVitaminaDTerapia').prop('disabled',false)
				        						$('#aggiungi_vitamina_d_terapia').prop('disabled',false)
				        						$('#text_area_vitamina_d_terapia').prop('disabled',false)
				        						$('#cancella_vitamina_d_terapia').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoVitaminaDTerapia').prop('disabled',true)
				        						$('#aggiungi_vitamina_d_terapia').prop('disabled',true)
				        						$('#text_area_vitamina_d_terapia').prop('disabled',true)
				        						$('#cancella_vitamina_d_terapia').prop('disabled',true)
				        						$('#TipoVitaminaDTerapia').prop('selectedIndex',0)
				        						tipi6 = {};
				        						$('#text_area_vitamina_d_terapia').empty();

				        						$('#valori_vitamina_d_terapia').val('');

				        						
				        					}


			        					});


			        				</script>

			        				<?php
				        					
			      						$pezzi_vitamina_d_supplementazione = explode("\n", $vitamina_d_supplementazione);
				      						
									?>
									<div class="row">
										<div class="col-sm-3">
											<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="VitaminaDSupplementazione" name="VitaminaDSupplementazione" value="VitaminaDSupplementazione" <?php if($vitamina_d_supplementazione_check == 1) echo "checked='checked'";  ?>/>
				            					</span>
				            					<input type="text" class="form-control" value="Vitamina D Supplementazione" readonly>
				          					</div><!--/input-group-->
											<input class="btn btn-primary" id = "vit_d_supp_sugg_button" type="button" value = "Suggerisci" name="SubmitButton" onclick = 'myFunction(this.id)'/>
										</div>

										<div class="col-sm-3">
											<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					
				            					<select class="form-control" name="TipoVitaminaDSupplementazione" id="TipoVitaminaDSupplementazione">
				            						<option value="">Tipo</option>
				            						<?php
				            						$aggiungi_vitamina_d_supplementazione = "SELECT NOME FROM GestioneInterna WHERE TIPO='Vitamina D supplementazione'";
				            						$res = mysqli_query($mysqli,$aggiungi_vitamina_d_supplementazione);
				            						while($row = mysqli_fetch_assoc($res)):
				            							echo "<option>$row[NOME]</option>";
				            						endwhile;



				            						?>
				            						
				            						
				            						
				            					</select>
				            					

				          					</div><!--/input-group-->
				          					<input type="button" value="aggiungi" id="aggiungi_vitamina_d_supplementazione" name="aggiungi_vitamina_d_supplementazione" onclick="app8();">
				          					<input type="button" value="cancella" id="cancella_vitamina_d_supplementazione" onclick="remove8();">

										</div>

										<div class="col-sm-6">
											<?php $i = 0; ?>
			        						<select style="height:100px;width:500px;" size="5" class="text_area_vitamina_d_supplementazione" name="text_area_vitamina_d_supplementazione" id="text_area_vitamina_d_supplementazione"><?php  while($pezzi_vitamina_d_supplementazione[$i] != null){ if($pezzi_vitamina_d_supplementazione[$i] != 'NULL') echo "<option> $pezzi_vitamina_d_supplementazione[$i] </option>"; $i=$i+1;} ?></select>
			        						<textarea style="visibility:hidden;" name="valori_vitamina_d_supplementazione" id="valori_vitamina_d_supplementazione"><?php $j = 0; while($pezzi_vitamina_d_supplementazione[$j] != null){ echo $pezzi_vitamina_d_supplementazione[$j]."\n"; $j=$j+1; } ?></textarea>
				        					
										</div>
									</div><!--/row-->
									<br/>
			        				
			        				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI TERAPIE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI7{}, CONTENENTE TUTTI I TIPI INSERITI
			        					//tipi7 vettore per vitamina d supplementazione
			        					var tipi7 = {};
			        					function app8(){
			        						var x = document.getElementById("text_area_vitamina_d_supplementazione");
        									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#TipoVitaminaDSupplementazione option:selected").text();
											//alert(tipo);
											
											if(tipi7[tipo]){
												alert("Tipo già inserito");
											}
											else if(tipo == "Tipo"){
												alert("Selezionare un tipo");
											}
											else{
												$("textarea#valori_vitamina_d_supplementazione").val($("textarea#valori_vitamina_d_supplementazione").val() + tipo+"\n");
												tipi7[tipo] = true;
												option.text = tipo;
												x.add(option);
											}
											
										}


										function remove8(){
											var x = document.getElementById("text_area_vitamina_d_supplementazione");
											//var tipo = x.split()
											var i;

											var y = document.getElementById("valori_vitamina_d_supplementazione");
											y.value = y.value.replace(x.value, "");
											
  											for (i = x.length - 1; i>=0; i--) {
    											if (x.options[i].selected) {
    												
      												var sel = $("#text_area_vitamina_d_supplementazione option:selected").text(); //prendo il valore del tipo che voglio cancellare
      												//alert(v);
      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
      												
      												tipi7[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
      												x.remove(i); //lo rimuovo come option

    											}
  											}
										}

			        				</script>

				        		
				        			<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					$('#TipoVitaminaDSupplementazione').prop('disabled',true)
	        							$('#aggiungi_vitamina_d_supplementazione').prop('disabled',true)
		        						$('#text_area_vitamina_d_supplementazione').prop('disabled',true)
		        						$('#cancella_vitamina_d_supplementazione').prop('disabled',true)


				        				$('#VitaminaDSupplementazione').bind('click',function(){
				        					if($('#VitaminaDSupplementazione').prop('checked')){
				        						
				        						$('#TipoVitaminaDSupplementazione').prop('disabled',false)
				        						$('#aggiungi_vitamina_d_supplementazione').prop('disabled',false)
				        						$('#text_area_vitamina_d_supplementazione').prop('disabled',false)
				        						$('#cancella_vitamina_d_supplementazione').prop('disabled',false)
				        					}
				        					else{

				        						$('#TipoVitaminaDSupplementazione').prop('disabled',true)
				        						$('#aggiungi_vitamina_d_supplementazione').prop('disabled',true)
				        						$('#text_area_vitamina_d_supplementazione').prop('disabled',true)
				        						$('#cancella_vitamina_d_supplementazione').prop('disabled',true)
				        						$('#TipoVitaminaDSupplementazione').prop('selectedIndex',0)
				        						tipi7 = {};
				        						$('#text_area_vitamina_d_supplementazione').empty();

				        						$('#valori_vitamina_d_supplementazione').val('');
				        					}


				        				});


			        				</script>

			        				<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					


				        				$(document).ready(function(){
				        					if($('#VitaminaDSupplementazione').prop('checked')){
				        						
				        						$('#TipoVitaminaDSupplementazione').prop('disabled',false)
				        						$('#aggiungi_vitamina_d_supplementazione').prop('disabled',false)
				        						$('#text_area_vitamina_d_supplementazione').prop('disabled',false)
				        						$('#cancella_vitamina_d_supplementazione').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoVitaminaDSupplementazione').prop('disabled',true)
				        						$('#aggiungi_vitamina_d_supplementazione').prop('disabled',true)
				        						$('#text_area_vitamina_d_supplementazione').prop('disabled',true)
				        						$('#cancella_vitamina_d_supplementazione').prop('disabled',true)
				        						$('#TipoVitaminaDSupplementazione').prop('selectedIndex',0)
				        						tipi7 = {};
				        						$('#text_area_vitamina_d_supplementazione').empty();

				        						$('#valori_vitamina_d_supplementazione').val('');
				        					}


				        				});


			        				</script>


			        				<br/>
			        				<!-- FIN QUA-->


			        				<?php
				        					
			      						$pezzi_calcio_supplementazione = explode("\n", $calcio_supplementazione);
				      						
			      						
			      						

									?>
									<!--CALCIO SUPPLEMENTAZIONE-->
									<div class="row">
										<div class="col-sm-3">
											<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="CalcioSupplementazione" name="CalcioSupplementazione" value="CalcioSupplementazione" <?php if($calcio_supplementazione_check == 1) echo "checked='checked'"  ?>/>
				            					</span>
				            					<input type="text" class="form-control" value="Calcio supplementazione" readonly>
				          					</div><!--/input-group-->
											 <input class="btn btn-primary" id = "calcio_supp_sugg_button" type="button" value = "Suggerisci" name="SubmitButton" onclick = 'myFunction(this.id)'/>
										</div>

										<div class="col-sm-3">
											<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					
				            					<select class="form-control" name="TipoCalcioSupplementazione" id="TipoCalcioSupplementazione">
				            						<option value="">Tipo</option>
				            						<?php
				            						$calcio_supplementazione = "SELECT NOME FROM GestioneInterna WHERE TIPO='Calcio supplementazione'";
				            						$res = mysqli_query($mysqli,$calcio_supplementazione);
				            						while($row = mysqli_fetch_assoc($res)):
				            							echo "<option>$row[NOME]</option>";
				            						endwhile;



				            						?>
				            						
				            						
				            						
				            					</select>
				            					

				          					</div><!--/input-group-->
				          					<input type="button" value="aggiungi" id="aggiungi_calcio_supplementazione" name="aggiungi_calcio_supplementazione" onclick="app9();">
				          					<input type="button" value="cancella" id="cancella_calcio_supplementazione" onclick="remove9();">

										</div>

										<div class="col-sm-6">
											<?php $i = 0; ?>
			        						<select style="height:100px;width:500px;" size="5" class="text_area_calcio_supplementazione" name="text_area_calcio_supplementazione" id="text_area_calcio_supplementazione"><?php  while($pezzi_calcio_supplementazione[$i] != null){ if($pezzi_calcio_supplementazione[$i] != 'NULL') echo "<option> $pezzi_calcio_supplementazione[$i] </option>"; $i=$i+1;} ?></select>
			        						<textarea style="visibility:hidden;" name="valori_calcio_supplementazione" id="valori_calcio_supplementazione"><?php $j = 0; while($pezzi_calcio_supplementazione[$j] != null){ echo $pezzi_calcio_supplementazione[$j]."\n"; $j=$j+1; } ?></textarea>
				        					
										</div>

									</div><!--/row-->
			        				
			        				<script>
			        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI TERAPIE, 
			        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

			        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI8{}, CONTENENTE TUTTI I TIPI INSERITI
			        					//tipi8 vettore per vitamina d supplementazione
			        					var tipi8 = {};
			        					function app9(){
			        						var x = document.getElementById("text_area_calcio_supplementazione");
        									//alert($("#TipoOrmonale option:selected").text());
											var option = document.createElement("option");
											var tipo = $("#TipoCalcioSupplementazione option:selected").text();
											//alert(tipo);
											
											if(tipi8[tipo]){
												alert("Tipo già inserito");
											}
											else if(tipo == "Tipo"){
												alert("Selezionare un tipo");
											}
											else{
												$("textarea#valori_calcio_supplementazione").val($("textarea#valori_calcio_supplementazione").val() + tipo+"\n");
												tipi8[tipo] = true;
												option.text = tipo;
												x.add(option);
											}
											
										}


										function remove9(){
											var x = document.getElementById("text_area_calcio_supplementazione");
											//var tipo = x.split()
											var i;

											var y = document.getElementById("valori_calcio_supplementazione");
											y.value = y.value.replace(x.value, "");
											
  											for (i = x.length - 1; i>=0; i--) {
    											if (x.options[i].selected) {
    												
      												var sel = $("#text_area_calcio_supplementazione option:selected").text(); //prendo il valore del tipo che voglio cancellare
      												//alert(v);
      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
      												
      												tipi8[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
      												x.remove(i); //lo rimuovo come option

    											}
  											}
										}

			        				</script>

				        				

				        				

				        			<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					$('#TipoCalcioSupplementazione').prop('disabled',true)
	        							$('#aggiungi_calcio_supplementazione').prop('disabled',true)
		        						$('#text_area_calcio_supplementazione').prop('disabled',true)
		        						$('#cancella_calcio_supplementazione').prop('disabled',true)


				        				$('#CalcioSupplementazione').bind('click',function(){
				        					if($('#CalcioSupplementazione').prop('checked')){
				        						
				        						$('#TipoCalcioSupplementazione').prop('disabled',false)
				        						$('#aggiungi_calcio_supplementazione').prop('disabled',false)
				        						$('#text_area_calcio_supplementazione').prop('disabled',false)
				        						$('#cancella_calcio_supplementazione').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoCalcioSupplementazione').prop('disabled',true)
				        						$('#aggiungi_calcio_supplementazione').prop('disabled',true)
				        						$('#text_area_calcio_supplementazione').prop('disabled',true)
				        						$('#cancella_calcio_supplementazione').prop('disabled',true)
				        						$('#TipoCalcioSupplementazione').prop('selectedIndex',0)
				        						tipi8 = {};
				        						$('#text_area_calcio_supplementazione').empty();

				        						$('#valori_calcio_supplementazione').val('');
				        					}


				        				});


			        				</script>

			        				<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					
				        				$(document).ready(function(){
				        					if($('#CalcioSupplementazione').prop('checked')){
				        						
				        						$('#TipoCalcioSupplementazione').prop('disabled',false)
				        						$('#aggiungi_calcio_supplementazione').prop('disabled',false)
				        						$('#text_area_calcio_supplementazione').prop('disabled',false)
				        						$('#cancella_calcio_supplementazione').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoCalcioSupplementazione').prop('disabled',true)
				        						$('#aggiungi_calcio_supplementazione').prop('disabled',true)
				        						$('#text_area_calcio_supplementazione').prop('disabled',true)
				        						$('#cancella_calcio_supplementazione').prop('disabled',true)
				        						$('#TipoCalcioSupplementazione').prop('selectedIndex',0)
				        						tipi8 = {};
				        						$('#text_area_calcio_supplementazione').empty();

				        						$('#valori_calcio_supplementazione').val('');
				        					}


				        				});


			        				</script>
			        				<br/>
			        				<!-- FIN QUA-->

			        				<div class="row">
			        					<div class="col-sm-5">
			        						<div style="width:95rem">
				        						<div class="input-group">
			                  						<span class="input-group-addon">
			                    						<input type="checkbox" id="nota_79_applicabile" name="nota_79_applicabile" value="Nota 79 applicabile" <?php if($nota_79_applicabile == 1) echo "checked='checked'";  ?>/>
			                  						</span>
			                  						<!--<input type="text" class="form-control" value="Norme comportamentali" readonly>-->
			                  						<span class="form-control" readonly>Nota 79 applicabile</span>
			                					</div><!--/input-group-->
			                				</div>
		                				</div>
		                			</div>

		                			<div class="row">
			        					<div class="col-sm-5">
			        						<div style="width:95rem">
				        						<div class="input-group">
			                  						<span class="input-group-addon">
			                    						<input type="checkbox" id="Prescrizione" name="Prescrizione" <?php if($nota_79_prescrizione == 1) echo "checked='checked'" ?>/>
			                  						</span>
			                  						<!--<input type="text" class="form-control" value="Norme comportamentali" readonly>-->
			                  						<input type="text" class="form-control" value="La prescrizione va fatta nel rispetto delle indicazioni e avvertenze della nota 79 e della scheda tecnica dei singoli farmaci"  readonly>
			                					</div><!--/input-group-->
			                				</div>
		                				</div>
		                			</div>

		                			<div class="row">

				          				<div class="col-sm-5">
			          						<div style="width:95rem">
				          						<div class="input-group">
				          							<span class="input-group-addon">
			            								<input type="checkbox" id="Altro" onchange="stickyheaddsadaer()" name="Altro" <?php if($altro_check == 1) echo "checked='checked'" ?>/>
				            						</span>
				            						<span class="input-group-addon">Altro</span>
				            						<input type="text" class="form-control" id="value_altro" onchange="stickyheaddsadaer()" name="value_altro" placeholder="Altro" value="<?php if($altro != 'NULL') echo $altro ?>" disabled>
				            						
				            					</div>
				            				</div>
			            				</div>
			            			</div>


		                			
		                			<div class="row">
			        					<div class="col-sm-5">
			        						<div style="width:95rem">
				        						<div class="input-group">
			                  						<span class="input-group-addon">
			                    						<input type="checkbox" id="norme_prevenzione" onchange="stickyheaddsadaer()" name="norme_prevenzione" <?php if($norme_prevenzione == 1) echo "checked='checked'" ?>/>
			                  						</span>
			                  						<!--<input type="text" class="form-control" value="Norme comportamentali" readonly>-->
			                  						<input type="text" class="form-control" value="Norme di prevenzione"  readonly>
			                					</div><!--/input-group-->
			                				</div>
		                				</div>
		                			</div>



			        				<div class="row">
			        					<div class="col-sm-5">
			        						<div style="width:95rem">
				        						<div class="input-group">
			                  						<span class="input-group-addon">
			                    						<input type="checkbox" id="norme_comportamentali" onchange="stickyheaddsadaer()" name="norme_comportamentali" <?php if($norme_comportamentali == 1) echo "checked='checked'"; ?>/>
			                  						</span>
			                  						<!--<input type="text" class="form-control" value="Norme comportamentali" readonly>-->
			                  						<span class="form-control" readonly>Norme comportamentali</span>
			                					</div><!--/input-group-->
			                				</div>
		                				</div>
		                			</div>
		                			
		                			<div class="row">
		                				<div class="col-sm-5">
		                					<div style="width:95rem">
			                					<div class="input-group">
			                  						<span class="input-group-addon">
			                    						<input type="checkbox" id="attivita_fisica" onchange="stickyheaddsadaer()" name="attivita_fisica" <?php if($attivita_fisica == 1) echo "checked='checked'" ?>/>
			                  						</span>
			                  						<!--<input type="text" class="form-control" value="Norme comportamentali" readonly>-->
			                  						<span class="form-control" readonly>Attività fisica</span>
			                					</div><!--/input-group-->
		                					</div>
		                				</div>
		                			</div>

		                			<div class="row">
		                				<div class="col-sm-10">
		                					<div style="width:95rem">
		                					<div class="input-group">
			            						<span class="input-group-addon">
			            							<input type="checkbox" id="sospensione_terapia" onchange="stickyheaddsadaer()" name="sospensione_terapia" <?php if($sospensione_terapia_check == 1) echo "checked='checked'"  ?>/>
			            						</span>
				            					<span class="input-group-addon">Sospensione temporanea della terapia in corso con</span>
				            					<input type="text" class="form-control" id="Farmaco" onchange="stickyheaddsadaer()" name="Farmaco" placeholder="Farmaco" value="<?php if($sospensione_terapia_farmaco != 'NULL') echo $sospensione_terapia_farmaco  ?>"  disabled>
				            					<span class="input-group-addon">per</span>
				            					<input type="text" class="form-control" id="Mesi" name="Mesi" placeholder="Mesi" value="<?php if($sospensione_terapia_mesi != 'NULL') echo $sospensione_terapia_mesi  ?>" disabled>
				            				</div>
									</div>
				            			</div>
				            		</div>

							<div class="row">
								<div class="col-sm-5">
									<div style="width:95rem">
										<div class="input-group">
											<span class="input-group-addon">
											<input type="checkbox" id="VALUTAZIONE_INTEGRATA" name="VALUTAZIONE_INTEGRATA" <?php if($VALUTAZIONE_INTEGRATA == 1) echo "checked='checked'"  ?>/>
											</span>
											<span class="form-control" readonly>Valutazione integrata di dati clinici e densitometrici</span>
										</div>
									</div>
								</div>
				            		</div>

				            		<script>
				            		//JAVASCRIPT PER CAMBIO DINAMICO SOSPENSIONE TEMPORANEA TERAPIA IN CORSO
				            			$('#sospensione_terapia').bind('click',function(){
				            				if($('#sospensione_terapia').prop('checked')){
				            					$('#Farmaco').prop('disabled',false)
				            					$('#Mesi').prop('disabled',false)
				            					//$('#Farmaco').attr('required','required');
				            					//$('#Mesi').attr('required','required');

				            				}
				            				else{
				            					$('#Farmaco').prop('disabled',true)
				            					$('#Mesi').prop('disabled',true)

				            					$('#Farmaco').prop('value','')
				            					$('#Mesi').prop('value','')

				            					//$('#Farmaco').removeAttr('required');
				            					//$('#Mesi').removeAttr('required');

				            				}

				            			});


				            		</script>

				            		<script>
				            		//JAVASCRIPT PER CAMBIO DINAMICO SOSPENSIONE TEMPORANEA TERAPIA IN CORSO
				            			$(document).ready(function(){	
				            			
				            				if($('#sospensione_terapia').prop('checked')){
				            					$('#Farmaco').prop('disabled',false)
				            					$('#Mesi').prop('disabled',false)
				            					//$('#Farmaco').attr('required','required');
				            					//$('#Mesi').attr('required','required');

				            				}
				            				else{
				            					$('#Farmaco').prop('disabled',true)
				            					$('#Mesi').prop('disabled',true)

				            					$('#Farmaco').prop('value','')
				            					$('#Mesi').prop('value','')

				            					//$('#Farmaco').removeAttr('required');
				            					//$('#Mesi').removeAttr('required');
				            				}			            			
				            			})


				            		</script>

				            		

				            		<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO DI TERAPIE(UNIONE DELLA TABELLA TIPI ORMONALE E OSTEOPROTETTIVA)
			        					$('#TipoTerapia').prop('disabled',true)
	        							$('#aggiungi_terapia').prop('disabled',true)
		        						$('#text_area_terapie').prop('disabled',true)
		        						$('#cancella_tipo_terapia').prop('disabled',true)


				        				$('#Terapie').bind('click',function(){
				        					if($('#Terapie').prop('checked')){
				        						
				        						$('#TipoTerapia').prop('disabled',false)
				        						$('#aggiungi_terapia').prop('disabled',false)
				        						$('#text_area_terapie').prop('disabled',false)
				        						$('#cancella_tipo_terapia').prop('disabled',false)
				        					}
				        					else{
				        						$('#TipoTerapia').prop('disabled',true)
				        						$('#aggiungi_terapia').prop('disabled',true)
				        						$('#text_area_terapie').prop('disabled',true)
				        						$('#cancella_tipo_terapia').prop('disabled',true)
				        						$('#TipoTerapia').prop('selectedIndex',0)
				        						tipi3 = {};
				        						$('#text_area_terapie').empty();

				        						$('#valori_terapie').val('');
				        					}
				        				});
			        				</script>

			        			
			        				

			            			<script>
			        					$('#Altro').bind('click',function(){
			        						if($('#Altro').prop('checked')){
			        							$('#value_altro').prop('disabled',false);
			        						}
			        						else{
			        							$('#value_altro').prop('disabled',true);
			        							$('#value_altro').prop('value','');	
			        						}


			        					});


			        				</script>

			        				<script>
			        					$(document).ready(function(){
			        						if($('#Altro').prop('checked')){
			        							$('#value_altro').prop('disabled',false);
			        						}
			        						else{
			        							$('#value_altro').prop('disabled',true);
			        							$('#value_altro').prop('value','');	
			        						}


			        					});


			        				</script>

			        				

			        				<?php
				        					
			      						$pezzi_indagini_approfondimento = explode("\n", $indagini_approfondimento);
				      					//echo strcmp($pezzi_indagini_approfondimento[0],'NULL') == 0;
									?>

				            		<br/>
				            		<!--INDAGINI DI APPROFONDIMENTO-->
				            		<div class="row">
				            			<div class="col-sm-3">
				            				<div class="input-group">
				            					<span class="input-group-addon">
				              						<input type="checkbox" id="Indagini_di_approfondimento" name="Indagini_di_approfondimento" value="Indagini_di_approfondimento" <?php if($indagini_approfondimento_check == 1) echo "checked='checked'"  ?>/>
				            					</span>
				            					<!--<input type="text" class="form-control" value="Indagini di approfondimento" readonly>-->
				            					<span class="form-control" style="height:48px;" readonly>Indagini di approfondimento</span>
				          					</div><!--/input-group-->
				            			</div>

				            			<div class="col-sm-3">
				            				<div class="input-group">
				            					<span class="input-group-addon">
				              						Tipo
				            					</span>
				            					<!--<input type="text" class="form-control" placeholder="Tipo" name="TipoOrmonale" id="TipoOrmonale" disabled>-->
				            					
				            					<select class="form-control" name="TipoIndagine" id="TipoIndagine" disabled>
				            						<option value="">Tipo</option>
				            						<!--
				            						<option>Si consiglia di eseguire RX colonna dorso-lombare a scopo morfometrico</option>
				            						<option>Si consiglia di eseguire indagine Morfometrica Vertebrale MXA per evidenziare eventuali deformità e per l'applicabilità della Nota 79</option>-->
				            						<?php
				            							$indagini = "SELECT NOME FROM GestioneInterna WHERE TIPO = 'Indagini approfondimento' ";
				            							$res = mysqli_query($mysqli,$indagini);
				            							while($row = mysqli_fetch_assoc($res)){
				            								echo "<option>$row[NOME]</option>"; 
				            								
				            							}

				            						?>
				            					            									            									            									            									            						
				            					</select>
				            					

				          					</div><!--/input-group-->
				          					<input type="button" value="aggiungi" id="aggiungi_indagine" name="aggiungi_indagine" disabled>
				          					<input type="button" value="cancella" id="cancella_indagine" disabled>

				            			</div>

				            			<div class="col-sm-6">
				            				<?php  $i = 0; ?>
			        						<select style="height:100px;width:500px;" size="5" class="text_area_indagini_approfondimento" name="text_area_indagini_approfondimento" id="text_area_indagini_approfondimento" disabled><?php  while($pezzi_indagini_approfondimento[$i] != null){ if($pezzi_indagini_approfondimento[$i] != 'NULL') echo "<option> $pezzi_indagini_approfondimento[$i] </option>"; $i=$i+1;} ?></select>
			        						<textarea style="visibility:hidden;" id="valori_indagini_approfondimento" name="valori_indagini_approfondimento">
			        							<?php 
			        								$j = 0; 
			        								//if(!empty($indagini_approfondimento)){
			        									while($pezzi_indagini_approfondimento[$j] != null){
			        									 	echo $pezzi_indagini_approfondimento[$j]."\n"; 
			        									 	$j=$j+1; 
			        									 }
			        								//} 
			        							?>
			        						</textarea>
				            			</div>
				            		</div><!--/row-->


				            		
				        			<script>
				        					//JAVASCRIPT PER INSERIRE DINAMICAMENTE LE OPTION NELLA SELECT DI TIPI TERAPIE, 
				        					//UNA VOLTA CHE INSERISCO TIPO E DURATA DIVENTA DISPONIBILE IL PULSANTE AGGIUNGI E ME LO INSERISCE NELLA SELECT

				        					//SE LO SELEZIONO E CLICCO RIMUOVI ME LO RIMUOVE DALLA SELECT E DALL ARRAY TIPI4{}, CONTENENTE TUTTI I TIPI INSERITI
				        					var tipi4 = {};
				        					$('#aggiungi_indagine').bind('click', function(){
				        						var x = document.getElementById("text_area_indagini_approfondimento");
	        									//alert($("#TipoIndagine option:selected").text());
												var option = document.createElement("option");
												var tipo = $("#TipoIndagine option:selected").text();
												
												
												if(tipi4[tipo]){
													alert("Tipo già inserito");
												}
												else if(tipo == "Tipo"){
													alert("Selezionare un tipo");
												}
												else{
													$("textarea#valori_indagini_approfondimento").val($("textarea#valori_indagini_approfondimento").val()+tipo+"\n");
													tipi4[tipo] = true;
													option.text = tipo;
													x.add(option);
												}
												
											});


											$('#cancella_indagine').bind('click',function(){
												var x = document.getElementById("text_area_indagini_approfondimento");
												//var tipo = x.split()
												var i;
												
												var y = document.getElementById("valori_indagini_approfondimento");
												y.value = y.value.replace(x.value, "");
												
	  											for (i = x.length - 1; i>=0; i--) {
	    											if (x.options[i].selected) {
	    												
	      												var sel = $("#text_area_indagini_approfondimento option:selected").text(); //prendo il valore del tipo che voglio cancellare
	      												
	      												//var new_v = sel.split(","); //faccio lo split della stringa fino alla ','
	      												//var tipo = new_v[0]; //prendo l'elemento 0 della stringa, che conterrà solo il tipo
	      												
	      												tipi4[sel] = false; //metto a false nella posizione 'tipo', così se lo voglio reinserire posso reinserirlo
	      												x.remove(i); //lo rimuovo come option

	    											}
	  											}
											});

											
				        				</script>

				        			<script>
				        			//JAVASCRIPT PER CAMBIO DINAMICO INDAGINI DI APPROFONDIMENTO
				        				$('#Indagini_di_approfondimento').bind('click',function(){
				        					if($('#Indagini_di_approfondimento').prop('checked')){
				        						$('#text_area_indagini_approfondimento').prop('disabled',false)
				        						$('#TipoIndagine').prop('disabled',false)
				        						$('#aggiungi_indagine').prop('disabled',false)
				        						$('#cancella_indagine').prop('disabled',false)

				        					}
				        					else{
				        						$('#text_area_indagini_approfondimento').prop('disabled',true)
				        						$('#aggiungi_indagine').prop('disabled',true)
				        						$('#cancella_indagine').prop('disabled',true)
				        						$('#TipoIndagine').prop('disabled',true)
				        						$('#TipoIndagine').prop('selectedIndex',0)

				        						tipi4 = {};
				        						$('#text_area_indagini_approfondimento').empty();

				        						$('#valori_indagini_approfondimento').val('');

				        						
				        					}


				        				});

				        			</script>

				        			<script>
				        				$(document).ready(function(){
				        					if($('#Indagini_di_approfondimento').prop('checked')){
				        						
				        						$('#text_area_indagini_approfondimento').prop('disabled',false)
				        						$('#TipoIndagine').prop('disabled',false)
				        						$('#aggiungi_indagine').prop('disabled',false)
				        						$('#cancella_indagine').prop('disabled',false)

				        					}
				        					else{
				        						$('#text_area_indagini_approfondimento').prop('disabled',true)
				        						$('#aggiungi_indagine').prop('disabled',true)
				        						$('#cancella_indagine').prop('disabled',true)
				        						$('#TipoIndagine').prop('disabled',true)
				        						$('#TipoIndagine').prop('selectedIndex',0)

				        						tipi4 = {};
				        						$('#text_area_indagini_approfondimento').empty();

				        						$('#valori_indagini_approfondimento').val('');

				        						
				        					}
				        				});


				        			</script>

				        			<br/>
				        			<div class="row">
				        				<div class="col-sm-5">
				        					<div class="input-group">
		                  						<span class="input-group-addon">
		                    						<input type="checkbox" id="sospensione_fumo" name="sospensione_fumo" <?php if($sospensione_fumo == 1) echo "checked='checked'" ?>/>
		                  						</span>
		                  						<!--<input type="text" class="form-control" value="Norme comportamentali" readonly>-->
		                  						<span class="form-control" readonly>Sospensione fumo</span>
		                					</div><!--/input-group-->
		                				</div>
		                			</div>
		                			<br/>
		                			<h4 style="text-align:center;"><b>CONTROLLI SUGGERITI</b></h4>
		                			
		                			<div class="row">
		                				
		                				<div class="col-sm-7">
		                					<div class="input-group">
			            						<span class="input-group-addon">
			            							<input type="checkbox" style="width:1em;" id="colloquio_densitometrico" name="colloquio_densitometrico" value="colloquio_densitometrico" <?php if($controllo_densitometrico_check == 1) echo "checked='checked'" ?>/>
			            						</span>
				            					<span class="input-group-addon" >Controllo densitometrico tra&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				            					<input  type="text" style="width: 386px;" class="form-control" id="colloquio_densitometrico_mesi" name="colloquio_densitometrico_mesi" placeholder="Mesi" value="<?php if($controllo_densitometrico_mesi != 'NULL') echo $controllo_densitometrico_mesi ?>" disabled>
				            					
				            				</div>
				            			</div>
				            		</div>

				            		<script>
				            		//JAVASCRIPT PER COLLOQUIO DENSITOMETRICO TRA MESI
				            			$('#colloquio_densitometrico').bind('click',function(){
				            				if($('#colloquio_densitometrico').prop('checked')){
				            					$('#colloquio_densitometrico_mesi').prop('disabled',false)
				            					//$('#colloquio_densitometrico_mesi').attr('required','required')

				            				}
				            				else{
				            					$('#colloquio_densitometrico_mesi').prop('disabled',true)
				            					$('#colloquio_densitometrico_mesi').prop('value','')
				            					//$('#colloquio_densitometrico_mesi').removeAttr('required')
				            				}



				            			});
				            		</script>
				            		
				            		<script>
				            		//JAVASCRIPT PER COLLOQUIO DENSITOMETRICO TRA MESI
				            			$(document).ready(function(){
				            				if($('#colloquio_densitometrico').prop('checked')){
				            					$('#colloquio_densitometrico_mesi').prop('disabled',false)
				            					//$('#colloquio_densitometrico_mesi').attr('required','required')

				            				}
				            				else{
				            					$('#colloquio_densitometrico_mesi').prop('disabled',true)
				            					$('#colloquio_densitometrico_mesi').prop('value','')
				            					//$('#colloquio_densitometrico_mesi').removeAttr('required')
				            				}



				            			});
				            		</script>

				            	
				      
				            		<div class="row">
				            			<div class="col-sm-10">
				            				<div style="width:100%">
		                					<div class="input-group">
			            						<span class="input-group-addon">
			            							<input type="checkbox" id="colloquio_presso_il_centro" name="colloquio_presso_il_centro" <?php if($controllo_interno == 1) echo "checked='checked'" ?>/>
			            						</span>
				            					<!--<span class="form-control" style="width:100%;background:#eee">Controllo presso il centro per</span>-->
				            					<div style="width:100%;background:#eee" class="form-control">
				            						<span>Controllo presso il centro per</span>
				            					</div>
				            					
				            					<span class="input-group-addon">
				            						<input type="checkbox" name="Valutazioni_esame" id="Valutazioni_esame" <?php if($controllo_interno_valutazioni == 1) echo "checked='checked'" ?> disabled>Valutazioni esame
				            					</span>

				            					<span class="input-group-addon">
				            						<input type="checkbox" name="Attivazione_piano_terapeutico" id="Attivazione_piano_terapeutico" disabled <?php if($controllo_interno_attivazione_piano == 1) echo "checked='checked'" ?>>Attivazione/rinnovo piano terapeutico
				            						
				            					</span>

				            					<span class="input-group-addon">
				            						<input type="checkbox" name="Controllo_clinico" id="Controllo_clinico" disabled <?php if($controllo_interno_clinico == 1) echo "checked='checked'" ?>>Controllo clinico
				            						
				            					</span>


				            					
				            				</div>
				            			</div>
				            			</div>
				            		</div>

				            		<script>
				            		//JAVASCRIPT PER COLLOQUIO DENSITOMETRICO TRA MESI
				            			$(document).ready(function(){
				            				if($('#colloquio_presso_il_centro').prop('checked')){
				            					$('#Valutazioni_esame').prop('disabled',false)
				            					$('#Attivazione_piano_terapeutico').prop('disabled',false)
				            					$('#Controllo_clinico').prop('disabled',false)

				            					//$('#Attivazione_piano_terapeutico').attr('required','required')
				            					//$('#Valutazioni_esame').attr('required','required')

				            				}
				            				else{
				            					$('#Valutazioni_esame').prop('disabled',true)
				            					$('#Attivazione_piano_terapeutico').prop('disabled',true)
												$('#Controllo_clinico').prop('disabled',true)				            					
				            					$('#Valutazioni_esame').prop('value','')
				            					$('#Attivazione_piano_terapeutico').prop('value','')

				            					//$('#Valutazioni_esame').removeAttr('required')
				            					//$('#Attivazione_piano_terapeutico').removeAttr('required')
				            				}



				            			});
				            		</script>
				            		<br/>
				            		<div class="row">
				            			<div class="col-sm-2">
				            			</div>

				            			<div class="col-sm-5">
				            				<input type="submit" class="btn btn-primary" id="Inserisci_C_Bozza" name="Inserisci_C_Bozza" value="Salva Bozza" onclick="return confirm('Stai salvando una bozza, potrai modificarlo e non verrà cambiato lo stato')"/>
				            			</div>

				            			<div class="col-sm-5">
				            				<input type="submit" class="btn btn-primary" id="Inserisci_C_Finale" name="Inserisci_C_Finale" value="Salva Finale" onclick="return confirm('Stai salvando definitivamente, salvando cambierà lo stato e non potrai modificarlo')"/>
				            			</div>
				            		</div>

				            		<?php 	if(strcmp($statoC, 'VERDE')==0){  ?>
				            					<script>
				            						$('#Inserisci_C_Bozza').prop('disabled',true);
				            					</script>
				            		<?php 	}  ?>

				            		<!--SE TUTTI GLI STATI SONO VERDI DISABILITO I PULSANTI PER SALVARE -->
				            		<?php 	if(strcmp($statoA, 'VERDE')==0 && strcmp($statoB, 'VERDE')==0 && strcmp($statoC, 'VERDE')==0 && strcmp($statoD, 'VERDE')==0){ ?>
				            					<script>
				            						$('#Inserisci_B_Finale').prop('disabled',true);
				            						$('#Inserisci_C_Finale').prop('disabled',true);
				            					</script>
				            		<?php 	}  ?>

				            		

				            		

				            		<br/><br/>
				            		
				            		<script type="text/javascript">
				            		//VALIDAZIONE CAMPI REFERTO B
				            			$(document).ready(function(){
				            				
				            				$("form#myForm").submit(function(e){
				            					
				            					var artrite = document.querySelectorAll('input[name="Artrite"]');
								            	var psoriasi = document.querySelectorAll('input[name="Psoriasi"]');
								            	var lupus = document.querySelectorAll('input[name="Lupus"]');
								            	var sclerodermia = document.querySelectorAll('input[name="Sclerodermia"]');
								            	var altreconnettiviti = document.querySelectorAll('input[name="AltreConnettiviti"]');

												var checkedOne = Array.prototype.slice.call(artrite).some(x => x.checked);
												var checkedTwo = Array.prototype.slice.call(psoriasi).some(x => x.checked);
												var checkedThree = Array.prototype.slice.call(lupus).some(x => x.checked);
												var checkedFour = Array.prototype.slice.call(sclerodermia).some(x => x.checked);
												var checkedFive = Array.prototype.slice.call(altreconnettiviti).some(x => x.checked);

												//alert(checkedOne+" "+checkedTwo+" "+checkedThree+" "+checkedFour+" "+checkedFive);

												var malattie_attuali = $('input[name=Malattieattuali]:checked','#myForm').val();
												

												//alert(colonna_vertebrale_parzialmente_non_analizzabile);
												if(malattie_attuali != null){
													if(!checkedOne && !checkedTwo && !checkedThree && !checkedFour && !checkedFive){
														
														$('html,body').animate({
        													scrollTop: $('input[name=Malattieattuali]').offset().top},
        													'slow');


														alert("Seleziona almeno una malattia attuale");
														return false;
												
													}
												}

												//INVIATA DA
												var espressione_regolare_ginecologo = /^([^0-9]*)$/;
												var inviata_da = $('input[name=InviataDa]:checked','#myForm').val();
												var ginecologo = $('input[name=GinecologoDelCentro]','#myForm').val();
												var altro_specialista = $('input[name=GinecologoEsterno]','#myForm').val();
												//alert(inviata_da);
												

												//validazione um
												var text = /^[0-9]+$/;
												var anno_m = $('#UltimaMestruazione').val();
												var u = $('#etamenopausa').val();
												
												if(anno_m != "" && !text.test(anno_m)){
													
														alert("Inserisci anno U.M.");
														return false;
													
												}
												if (anno_m != "" && anno_m.length != 4) {
            										alert("Inserisci anno U.M.");
            										return false;
        										}

        										if(u != "" && !text.test(u)){
        											alert("Inserisci età menopausa");
        											return false;
        										}
												

												
												

												//STATO TERAPIA
												var regExp = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												var stato_terapia = $('input[name=stato_terapia]:checked','#myForm').val();
												var tempo_sospensione = $('input[name=da_quanto]','#myForm').val();

												if(stato_terapia != null){
													if(stato_terapia == "Sospesa"){
														if(!regExp.test(tempo_sospensione)){
															$('html,body').animate({
	        													scrollTop: $('input[name=stato_terapia]').offset().top},
	        													'slow');

															alert("Inserisci anni sospensione terapia");
															return false;
														}
													}
												} 
												//alert(stato_terapia);
												
												if(stato_terapia != null){
													if(stato_terapia == "Sospesa"){
														if(tempo_sospensione == ""){
															$('html,body').animate({
	        													scrollTop: $('input[name=stato_terapia]').offset().top},
	        													'slow');

															alert("Inserisci anni sospensione terapia");
															return false;
														}
													}
												}
												
												//ORMONALE
												var terapia_ormonale = $('input[name=Ormonale]:checked','#myForm').val();
												var text_area_ormonale = $('textarea#valori_ormonale').val();
												//alert(text_area_osteoprotettiva);
												
												if(terapia_ormonale != null){
													
													if(text_area_ormonale.trim() == ""){
														
														$('html,body').animate({
        													scrollTop: $('input[name=Ormonale]').offset().top},
        													'slow');

														alert("Inserisci terapie ormonali");
														return false;
													}
												}




												
												//OSTEOPROTETTIVA
												var terapia_osteoprotettiva = $('input[name=Osteoprotettiva]:checked','#myForm').val();
												var text_area_osteoprotettiva = $('textarea#valori_osteoprotettiva').val();
												//alert(text_area_osteoprotettiva);
												if(terapia_osteoprotettiva != null){
													if(text_area_osteoprotettiva == ""){
														$('html,body').animate({
        													scrollTop: $('input[name=Osteoprotettiva]').offset().top},
        													'slow');

														alert("Inserisci terapie osteoprotettive");
														return false;
													}
												}

												var vitaminad = $('input[name=VitaminaD]:checked','#myForm').val();
												
												var text_area_vitamina_d = $('textarea#valori_vitamina_d').val();
												//var text_area_vitamina_d = document.getElementById('text_area_vitamina_d');
												//alert(text_area_vitamina_d);
												//return false;
												
												if(vitaminad != null){
													
														
													if(text_area_vitamina_d.trim() == ""){
														
														$('html,body').animate({
															scrollTop: $('input[name=VitaminaD]').offset().top},
															'slow');
														alert("Inserisci Vitamina D supplementazione");
														return false;
														
													}
													
												}
												
												
												//ALTRO TERAPIE

												var altro_terapie = $('input[name=TerapieProtettive]:checked','#myForm').val();
												var value_altro_terapie = $('input[name=ValueAltroTerapie]','#myForm').val();

												if(altro_terapie != null){
													if(value_altro_terapie == ""){
														$('html,body').animate({
        													scrollTop: $('input[name=TerapieProtettive]').offset().top},
        													'slow');

														alert("Inserisci altro terapie");
														return false;
													}
												}

												//PESO

												var peso = $('#Peso').val();
												if(peso == ""){
													$('html,body').animate({
        													scrollTop: $('#Peso').offset().top},
        													'slow');

													alert("Inserisci il peso");
													return false;
												}

												var altezza = $('#Altezza').val();
												if(altezza == ""){
													$('html,body').animate({
        													scrollTop: $('#Altezza').offset().top},
        													'slow');

													alert("Inserisci altezza");
													return false;
												}

												var frattura_fragilita_vertebrosa_femorale = $('input[name=FratturaFragilitaVertebrosa]:checked','#myForm').val();
												var frattura_vertebre = $('input[name=FratturaVertebre]:checked','#myForm').val();
												var frattura_femore = $('input[name=FratturaFemore]:checked','#myForm').val();
												if(frattura_fragilita_vertebrosa_femorale != null){
													if(frattura_vertebre == null && frattura_femore == null){
														$('html,body').animate({
        													scrollTop: $('#FratturaFragilitaVertebrosa').offset().top},
        													'slow');

														alert("Seleziona un opzione in frattura da fragilità vertebrale/femorale");
														return false;
													}
												}

												var fratture_siti_diversi = $('input[name=PregresseFratture1]:checked','#myForm').val();
												var sito_pregresse_fratture = $('input[name=PregresseFratture]','#myForm').val();
												if(fratture_siti_diversi != null){
													if(sito_pregresse_fratture == ""){
														$('html,body').animate({
        													scrollTop: $('#PregresseFratture1').offset().top},
        													'slow');

														alert("Inserire pregresse fratture");
														return false;
													}
												}
												
												
												//ABUSO FUMO
												var abuso_fumo = $('input[name=Abusofumo1]:checked','#myForm').val();
				            					var quantita_fumo = $('input[name=Abusofumo]:checked','#myForm').val();
				            					
				            					if(abuso_fumo != null){
				            						if(quantita_fumo == null){
				            							$('html,body').animate({
        													scrollTop: $('#Abusofumo1').offset().top},
        													'slow');

				            							alert("Inserisci quantità sigarette");
				            							return false;
				            						}
				            					}
				            					
				            					
				            					//USO CORTISONE
				            					var cortisone = $('input[name=Usocortisone1]:checked','#myForm').val();
				            					var option_cortisone = $('input[name=Usocortisone]:checked','#myForm').val();
				            					
				            					if(cortisone != null){
				            						if(option_cortisone == null){
				            							$('html,body').animate({
        													scrollTop: $('#Usocortisone1').offset().top},
        													'slow');

				            							alert("Seleziona un opzione in uso cortisone");
				            							return false;

				            						}
				            					}

				            					//OSTEOPOROSI SECONDARIA
				            					
				            					var osteoporosi_secondaria_check = $('input[name=Osteoporosisecondaria]:checked','#myForm').val();
				            					var val_osteoporosi_secondaria = $('textarea#valori_causesecondarie').val();
				            					if(osteoporosi_secondaria_check != null){
				            						if(val_osteoporosi_secondaria == ""){
				            							$('html,body').animate({
        													scrollTop: $('#Osteoporosisecondaria').offset().top},
        													'slow');

				            							alert("Inserisci cause secondarie");
				            							return false;
				            						}
				            					}
				            					
				            					
				            					
											
											
											//ABUSO ALCOOL
											var abuso_alcool_check = $('input[name=Abusoalcool1]:checked','#myForm').val();
											var alcool = $('input[name=Abusoalcool]:checked','#myForm').val(); 
											if(abuso_alcool_check != null){
												if(alcool == null){
													$('html,body').animate({
        													scrollTop: $('#Abusoalcool1').offset().top},
        													'slow');

													alert("Seleziona opzione in abuso alcool");
													return false;
												}
											}

											//PATOLOGIE UTERINW
											var patologie_uterine_check = $('input[name=PatologieUterine]:checked','#myForm').val();
											var diagnosi_patologie = $('input[name=diagnosi1]','#myForm').val();
											if(patologie_uterine_check != null){
												if(diagnosi_patologie == ""){
													$('html,body').animate({
        													scrollTop: $('#PatologieUterine').offset().top},
        													'slow');

													alert("Inserisci diagnosi patologie uterine");
													return false;
												}
											}

											//NEOPLASIA
											var neoplasia_check = $('input[name=Neoplasia]:checked','#myForm').val();
											var data_neoplasia = $('input[name=data_neoplasia]','#myForm').val();
											
											//espressione regolare per validare la data inserita
											//var expression_regular = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
											var expression_regular = /^\d+\.?\d*$/
											if(neoplasia_check){
												if(!expression_regular.test(data_neoplasia)){
													$('html,body').animate({
														scrollTop: $('#Neoplasia').offset().top},
														'slow');

													alert("Inserisci anno neoplasia");
													return false;
												}
											}
											
											var terapia_neoplasia = $('input[name=terapia_neoplasia]','#myForm').val();
											if(neoplasia_check != null){
												if(data_neoplasia == "" || terapia_neoplasia == ""){
													$('html,body').animate({
        													scrollTop: $('#Neoplasia').offset().top},
        													'slow');													

													alert("Inserisci data neoplasia e terapia neoplasia");
													return false;
												}
											}

											//DISLIPIDEMIA
											var dislipidemia_check = $('input[name=Dislipidemia]:checked','#myForm').val();
											var terapia_dislipidemia = $('input[name=dislipidemia_terapia]','#myForm').val();
											if(dislipidemia_check != null){
												if(terapia_dislipidemia == ""){
													$('html,body').animate({
        													scrollTop: $('#Dislipidemia').offset().top},
        													'slow');

													alert("Inserisci terapia dislipidemia");
													return false;
												}
											}

											//PATOLOGIA DEL CAVO ORALE
											var patologia_cavo_orale_check = $('input[name=PatologiaDelCavoOrale]:checked','#myForm').val();
				            				var terapia_patologia_cavo_orale = $('input[name=terapia]','#myForm').val();
				            				if(patologia_cavo_orale_check != null){
				            					if(terapia_patologia_cavo_orale == ""){
				            						$('html,body').animate({
        													scrollTop: $('#PatologiaDelCavoOrale').offset().top},
        													'slow');

				            						alert("Inserisci terapia patologia del cavo orale");
				            						return false;
				            					}
				            				}

				            				//IPOVITAMINOSI
				            				var ipovitaminosi_check = $('input[name=Ipovitaminosi]:checked','#myForm').val();
				            				var value_ipovitaminosi = $('input[name=valore_ipovitaminosi]','#myForm').val();
				            				//alert(value_ipovitaminosi);
				            				var espressione_regolare = /^-?[0-9]\d*([.,][0-9]+)?$/g;
				            				if(ipovitaminosi_check){
				            					if(!espressione_regolare.test(value_ipovitaminosi)){
				            						$('html,body').animate({
        													scrollTop: $('#Ipovitaminosi').offset().top},
        													'slow');

				            						alert("Inserisci valore corretto in 25 OH Vitamina D");
				            						return false;
				            					}
				            				}
				            				if(ipovitaminosi_check != null){
				            					if(value_ipovitaminosi == ""){
				            						$('html,body').animate({
        													scrollTop: $('#Ipovitaminosi').offset().top},
        													'slow');

				            						alert("Inserisci valore 25 OH Vitamina D");
				            						return false;

				            					}
				            				}

				            				//ALTRO
				            				var altro = $('input[name=AltroPatologie]:checked','#myForm').val();
				            				var valore_altro = $('input[name=altro_patologie]','#myForm').val();
				            				if(altro != null){
				            					if(valore_altro == ""){
				            						$('html,body').animate({
        													scrollTop: $('#AltroPatologie').offset().top},
        													'slow');

				            						alert("Inserisci altro");
				            						return false;

				            					}
				            				}

				            				//ALLERGIE
				            				var allergie_check = $('input[name=Allergie]:checked','#myForm').val();
				            				var allergie_testo = $('input[name=allergie]','#myForm').val();
				            				if(allergie_check != null){
				            					if(allergie_testo == ""){
				            						$('html,body').animate({
        													scrollTop: $('input[name=Allergie]').offset().top},
        													'slow');

				            						alert("Inserisci allergie");
				            						return false;
				            					}
				            				}
				            				
				            				
											

				            				//INTOLLERANZE
				            				var intolleranze_check = $('input[name=Intolleranza]:checked','#myForm').val();
				            				var intolleranze_testo = $('input[name=value_intolleranze]','#myForm').val();		
				            				if(intolleranze_check != null){
				            					if(intolleranze_testo == ""){
				            						$('html,body').animate({
        													scrollTop: $('#Intolleranza').offset().top},
        													'slow');

				            						alert("Inserisci intolleranze");
				            						return false;

				            					}
				            				}


				            				//DENSITOMETRIA PRECEDENTE
				            				expression_regular = /^(0?[1-9]|[12][0-9]|3[01])[\/\-](0?[1-9]|1[012])[\/\-]\d{4}$/;
				            				var densitometria_precedente_check = $('input[name=DensitometriaPrecedente]:checked','#myForm').val();
				            				var densitometria_precedente_data = $('input[name=data_densitometria_precedente]','#myForm').val();
				            				
				            				if(densitometria_precedente_check != null){
				            					if(!expression_regular.test(densitometria_precedente_data)){
				            						$('html,body').animate({
        													scrollTop: $('#DensitometriaPrecedente').offset().top},
        													'slow');

				            						alert("Inserisci data densitometria precedente nel formato gg/mm/aaaa o g/m/aaaa");
				            						return false;
				            					}
				            				}
				            				
				            				

				            				if(densitometria_precedente_check != null){
				            					if(densitometria_precedente_data == ""){
				            						$('html,body').animate({
        													scrollTop: $('#DensitometriaPrecedente').offset().top},
        													'slow');

				            						alert("Inserisci data densitometria precedente");
				            						return false;
				            					}
				            				}

				            				//COLONNA LOMBARE APPLICABILE
				            				/*
				            				var colonna_applicabile = $('input[name=colonna_lombare_appicabile]:checked','#myForm');
				            				var colonna_lombare_tscore_intero = $('#colonna_lombare_tscore_intero').val();
				            				var colonna_lombare_tscore_decimale = $('#colonna_lombare_tscore_decimale').val();
				            				var colonna_lombare_zscore_intero = $('#colonna_lombare_zscore_intero').val();
				            				var colonna_lombare_zscore_decimale = $('#colonna_lombare_zscore_decimale').val();
				            				if(colonna_applicabile){
				            					if(colonna_lombare_tscore_intero == "" || colonna_lombare_tscore_decimale == "" || colonna_lombare_zscore_intero == "" || colonna_lombare_zscore_decimale == ""){
				            						alert("Inserisci valori in t score e z score colonna lombare");
				            						return false;
				            					}
				            				}
				            				*/
				            				//QUA
				            				var colonna_tscore = $('#ColonnaLombareTscoreIntero').val();
				            				var colonna_zscore = $('#ColonnaLombareZscoreIntero').val();
				            				var femore_tscore = $('#ColloFemoreTscoreIntero').val();
				            				var femore_zscore = $('#ColloFemoreZscoreIntero').val();
				            				
				            				/*alert(colonna_tscore);
				            				alert(colonna_zscore);
				            				alert(femore_tscore);*/
				            				//alert(femore_zscore);
				            				var colonna_lombare_applicabile = $('input[name=colonna_lombare_appicabile]:checked','#myForm').val();
				            				
				            				if(colonna_lombare_applicabile != null){
				            					
				            					if(colonna_tscore == "" && colonna_zscore == ""){
				            						alert("Inserisci un valore in tscore o zscore");
				            						return false;
				            					}
				            				}
				            				//validazione tscore


				            				var espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;

				            				if(colonna_tscore != ""){
				            					
				            					if(!espressione_regolare_decimale.test(colonna_tscore)){
				            						alert("Inserisci valore corretto per colonna lombare tscore");
				            						return false;
				            					}
				            				}
				            				espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
				            				if(colonna_zscore != ""){

				            					if(!espressione_regolare_decimale.test(colonna_zscore)){
				            						alert("Inserisci valore corretto per colonna lombare zscore");
				            						return false;
				            					}
				            				}

				            				//validazione collo femorename
				            				var collo_femore_checkbox = $('input[name=femore_collo_appicabile]:checked','#myForm').val();
				            				
				            				if(collo_femore_checkbox != null){
				            					if(femore_tscore == "" && femore_zscore == ""){
				            						alert("Inserisci un valore in collo femore tscore o collo femore zscore");
				            						return false;
				            					}
				            				}

				            				espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
				            				if(femore_tscore != ""){

				            					if(!espressione_regolare_decimale.test(femore_tscore)){
				            						alert("Inserisci valore corretto per collo femore tscore");
				            						return false;
				            					}
				            				}
				            				espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
				            				if(femore_zscore != ""){
				            					
				            					if(!espressione_regolare_decimale.test(femore_zscore)){
				            						alert("Inserisci valore corretto per collo femore zscore");
				            						return false;
				            					}
				            				}


				            			});
									});



				            		</script>
									
									
				            		

				            		<script type='text/javascript'>
				            		//VALIDAZIONE CAMPI REFERTO C
								         $(document).ready(function() {
								            //option A
								            //if( $('#fruit_name').has('option').length > 0 ) {
								            $("form#myForm2").submit(function(e){
								            	//var val = $('input[name=Terapie]:checked','#myForm2').val(); 
								            	//CONTROLLO CHE SIA STATA INSERITA ALMENO UNA TERAPIA SE È STATA CLICCATA LA CHECK TERAPIE
	  											var val = $('input[name=Terapie]:checked','#myForm2').val();
	  											
								                
								                if(val != null){
								                	if( !$('#text_area_terapie').text() ) { 

								                		alert("Seleziona un opzione in Terapie");
								                		return false;
								                	}
								            	}
								            	
								            	//CONTROLLO CHE SIA STATA INSERITA ALMENO UN INDAGINE DI APPROFONDIMENTO SE È STATA CLICCATA LA CHECK INDAGINE DI APPROFONDIMENTO
								            	var indagini = $('input[name=Indagini_di_approfondimento]:checked','#myForm2').val();

								            	if(indagini != null){
								            		if( !$('#text_area_indagini_approfondimento').text()){

								            			alert("Inserisci almeno un opzione in indagini di approfondimento");
								            			return false;
								            		}
								            	}
								            	
								            	
								            	//CONTROLLO CHE SIA STATA SELEZIONATA ALMENO UN OPZIONE IN COLLOQUIO CENTRO SE È STATO SELEZIONATO COLLOQUIO PRESSO IL CENTRO
								            	var colloquio_centro = $('input[name=colloquio_presso_il_centro]:checked','#myForm2').val();
								            	var valutazioni_esame = $('input[name=Valutazioni_esame]:checked','#myForm2').val();
								            	var Attivazione_piano_terapeutico = $('input[name=Attivazione_piano_terapeutico]:checked','#myForm2').val();
								            	var Controllo_clinico = $('input[name=Controllo_clinico]:checked','#myForm2').val();

								            	if(colloquio_centro != null){

								            		if(valutazioni_esame == null && Attivazione_piano_terapeutico == null && Controllo_clinico == null ){
								            			
								            			$('html,body').animate({
        													scrollTop: $('input[name=colloquio_presso_il_centro]').offset().top},
        													'slow');

								            			alert("Seleziona un opzione in controllo presso il centro");
								            			return false;
								            		}
								            	}

								            	//CONTROLLO CHE SIA STATA SELEZIONATA ALMENO UNA VERTEBRA VALUTATA SE È STATO SELEZIONATO COLONNA VERTEBRALE PARZIALMENTE NON ANALIZZABILE
								            	var checkboxes1 = document.querySelectorAll('input[name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1"]');
								            	var checkboxes2 = document.querySelectorAll('input[name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2"]');
								            	var checkboxes3 = document.querySelectorAll('input[name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3"]');
								            	var checkboxes4 = document.querySelectorAll('input[name="Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4"]');

												var checkedOne = Array.prototype.slice.call(checkboxes1).some(x => x.checked);
												var checkedTwo = Array.prototype.slice.call(checkboxes2).some(x => x.checked);
												var checkedThree = Array.prototype.slice.call(checkboxes3).some(x => x.checked);
												var checkedFour = Array.prototype.slice.call(checkboxes4).some(x => x.checked);

												//alert(checkedOne+" "+checkedTwo+" "+checkedThree+" "+checkedFour);

												var colonna_vertebrale_parzialmente_non_analizzabile = $('input[name=ColonnaVertebraleParzialmenteNonAnalizzabile]:checked','#myForm2').val();
												

												//alert(colonna_vertebrale_parzialmente_non_analizzabile);
												if(colonna_vertebrale_parzialmente_non_analizzabile != null){
													if(!checkedOne && !checkedTwo && !checkedThree && !checkedFour){
														
														$('html,body').animate({
        													scrollTop: $('input[name=ColonnaVertebraleParzialmenteNonAnalizzabile]').offset().top},
        													'slow');

														
														
														alert("Seleziona almeno una vertebra valutata in colonna vertebrale parzialmente non analizzabile");
														return false;
												
													}
												}


												//CONTROLLO CHE NON SIANO STATE CLICCATE INSIEME COLONNA VERTEBRALE INTERAMENTE NON ANALIZZABILE E COLONNA VERTEBRALE PARZIALMENTE NON ANALIZZABILE
												var colonna_interamente_non_analizzabile = $('input[name=colonnavertebralenonanalizzabile]:checked','#myForm2').val();
												var colonna_parzialmente_non_analizzabile = $('input[name=ColonnaVertebraleParzialmenteNonAnalizzabile]:checked','#myForm2').val();

												//alert(colonna_interamente_non_analizzabile);
												//alert(colonna_interamente_non_analizzabile);
												if(colonna_interamente_non_analizzabile != null && colonna_parzialmente_non_analizzabile != null){
													$('html,body').animate({
        													scrollTop: $('input[name=ColonnaVertebraleParzialmenteNonAnalizzabile]').offset().top},
        													'slow');

													alert("Scegli solo una delle due opzioni tra colonna vertebrale interamente non analizzabile e colonna vertebrale parzialmente non analizzabile");
													return false;
												}

												//validazione frax e defra

												var frax_applicabile = document.querySelectorAll('input[name="Frax_applicabile"]');
								            	var frax_percentuale = document.querySelectorAll('input[name="percentuale_frax"]');

												var check_frax_applicabile = Array.prototype.slice.call(frax_applicabile).some(x => x.checked);
												var check_percentuale_frax = Array.prototype.slice.call(frax_percentuale).some(x => x.checked);
												
												var frax_fratture_maggiori_intero = document.querySelectorAll('input[name="frax_fratture_maggiori_intero"]');
												var valore_frax_intero = Array.prototype.slice.call(frax_fratture_maggiori_intero).some(x => x.value);
												
												var frax_fratture_maggiori_decimale = document.querySelectorAll('input[name="frax_fratture_maggiori_decimale"]');
												var valore_frax_decimale = Array.prototype.slice.call(frax_fratture_maggiori_decimale).some(x => x.value);
												
												var percentuale_collo_femore = document.querySelectorAll('input[name="percentuale_collo_femore"]');
												var check_percentuale_collo_femore = Array.prototype.slice.call(percentuale_collo_femore).some(x => x.checked);
												

												var frax_collo_femore_intero = document.querySelectorAll('input[name="collo_femore_intero"]');
												var valore_collo_femore_intero = Array.prototype.slice.call(frax_collo_femore_intero).some(x => x.value);

												var frax_collo_femore_decimale = document.querySelectorAll('input[name="collo_femore_decimale"]');
												var valore_collo_femore_decimale = Array.prototype.slice.call(frax_collo_femore_decimale).some(x => x.value);

												var espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												var numero_frax_intero = $('input[name="frax_fratture_maggiori_intero"]').val();
												var numero_frax_decimale = $('input[name="frax_fratture_maggiori_decimale"]').val();
												var numero_collo_femore_intero = $('input[name="collo_femore_intero"]').val();
												var numero_collo_femore_decimale = $('input[name="collo_femore_decimale"]').val();
												var frax_intero = numero_frax_intero+numero_frax_decimale;
												var femore_intero = numero_collo_femore_intero+numero_collo_femore_decimale;

												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												/*
												if(check_frax_applicabile && !check_percentuale_frax){
													
													if(parseInt(numero_frax_intero) >= 100 && parseInt(numero_frax_decimale) > 0){
														alert("Inserisci un numero inferiore o uguale a 100 in Frax");
														return false;
													}
												}
												*/
												if(check_frax_applicabile && !check_percentuale_frax){
													
													if(parseFloat(numero_frax_intero) >= 100){
														alert("Inserisci un numero inferiore o uguale a 100 in Frax");
														return false;
													}
												}

												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_frax_applicabile && !check_percentuale_frax){
													if(!espressione_regolare_decimale.test(numero_frax_intero)){
														alert("Inserisci un numero in fratture maggiori");
														return false;
													}
													
												}
												
												//espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_frax_applicabile && !check_percentuale_collo_femore){
													if(!espressione_regolare_decimale.test(numero_collo_femore_intero)){
														alert("Inserisci un numero in collo femore");
														return false;
													}
												}
												


												//var espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_frax_applicabile && !check_percentuale_collo_femore){
													
													if(numero_collo_femore_intero >= 100){
														alert("Inserisci un numero inferiore o uguale a 100 in collo femore");
														return false;
													}
												}

												if(check_frax_applicabile){
													if(!check_percentuale_frax){
														if(!valore_frax_intero){
															
															$('html,body').animate({
        													scrollTop: $('input[name="Frax_applicabile"]').offset().top},
        													'slow');

															alert("Seleziona un opzione per il frax");
															return false;
														}
														
													}
													if(!check_percentuale_collo_femore){
														if(!valore_collo_femore_intero){
															$('html,body').animate({
        													scrollTop: $('input[name="Frax_applicabile"]').offset().top},
        													'slow');

															alert("Seleziona un opzione per collo femore");
															return false;	
														}
													}
												}


												
												//DEFRA
												//espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												
												var defra_applicabile = document.querySelectorAll('input[name="defra_applicabile"]');
												var check_defra_applicabile = Array.prototype.slice.call(defra_applicabile).some(x => x.checked);

												var defra_percentuale_01 = document.querySelectorAll('input[name="defra_percentuale_01"]');
												var check_defra_percentuale_01 = Array.prototype.slice.call(defra_percentuale_01).some(x => x.checked);

												var defra_percentuale_50 = document.querySelectorAll('input[name="defra_percentuale_50"]');
												var check_defra_percentuale_50 = Array.prototype.slice.call(defra_percentuale_50).some(x => x.checked);

												var defra_intero = document.querySelectorAll('input[name="defra_intero"]');
												var valore_defra_intero = Array.prototype.slice.call(defra_intero).some(x => x.value); 

												var defra_decimale = document.querySelectorAll('input[name="defra_decimale"]');
												var valore_defra_decimale = Array.prototype.slice.call(defra_decimale).some(x => x.value);

												var numero_defra_intero = $('input[name="defra_intero').val();
												var numero_defra_decimale = $('input[name="defra_decimale"]').val();
												var defra = numero_defra_intero+numero_defra_decimale;
												//alert(defra);
												if(check_defra_applicabile && !check_defra_percentuale_01 && !check_defra_percentuale_50){
													if(numero_defra_intero >= 100){
														alert("Inserisci un numero inferiore o uguale a 100 in DeFRA");
														return false;
													}
												}

												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_defra_applicabile && !check_defra_percentuale_01 && !check_defra_percentuale_50){
													//alert(numero_defra_intero);
													if(!espressione_regolare_decimale.test(numero_defra_intero)){
														alert("Inserisci un numero in DeFRA");
														return false;
													}
												}

												if(check_defra_applicabile){
													if(!check_defra_percentuale_01 && !check_defra_percentuale_50){
														if(!valore_defra_intero){
															$('html,body').animate({
        													scrollTop: $('input[name="defra_applicabile"]').offset().top},
        													'slow');

															alert("Seleziona un opzione per defra");
															return false;
														}
													}
												}

												//FRAX AGGIUSTATO
												var frax_aggiustato_applicabile = document.querySelectorAll('input[name="Frax_aggiustato_applicabile"]');
								            	var frax_aggiustato_percentuale = document.querySelectorAll('input[name="percentuale_frax_aggiustato"]');

												var check_frax_aggiustato_applicabile = Array.prototype.slice.call(frax_aggiustato_applicabile).some(x => x.checked);
												var check_percentuale_frax_aggiustato = Array.prototype.slice.call(frax_aggiustato_percentuale).some(x => x.checked);
												
												var frax_aggiustato_valore = document.querySelectorAll('input[name="frax_aggiustato_valore"]');
												var valore_frax_aggiustato = Array.prototype.slice.call(frax_aggiustato_valore).some(x => x.value);
												
												
												var percentuale_collo_femore_aggiustato = document.querySelectorAll('input[name="percentuale_collo_femore_aggiustato"]');
												var check_percentuale_collo_femore_aggiustato = Array.prototype.slice.call(percentuale_collo_femore_aggiustato).some(x => x.checked);
												

												var collo_femore_aggiustato_valore = document.querySelectorAll('input[name="collo_femore_aggiustato_valore"]');
												var valore_collo_femore_aggiustato = Array.prototype.slice.call(collo_femore_aggiustato_valore).some(x => x.value);

												
												var espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												var numero_frax_aggiustato = $('input[name="frax_aggiustato_valore"]').val();
												var numero_collo_femore_aggiustato = $('input[name="collo_femore_aggiustato_valore"]').val();
												
												//var femore_intero = numero_collo_femore_intero+numero_collo_femore_decimale;

												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												
												//VALIDAZIONE FRAX AGGIUSTATO
												if(check_frax_aggiustato_applicabile && !check_percentuale_frax_aggiustato){
													
													if(parseFloat(numero_frax_aggiustato) >= 100){
														alert("Inserisci un numero inferiore o uguale a 100 in Frax");
														return false;
													}
												}

												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_frax_aggiustato_applicabile && !check_percentuale_frax_aggiustato){
													if(!espressione_regolare_decimale.test(numero_frax_aggiustato)){
														alert("Inserisci un numero in fratture maggiori");
														return false;
													}
													
												}
												
												//espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_frax_aggiustato_applicabile && !check_percentuale_collo_femore_aggiustato){
													if(!espressione_regolare_decimale.test(numero_collo_femore_aggiustato)){
														alert("Inserisci un numero in collo femore");
														return false;
													}
												}
												


												//var espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_frax_aggiustato_applicabile && !check_percentuale_collo_femore_aggiustato){
													
													if(numero_collo_femore_aggiustato >= 100){
														alert("Inserisci un numero inferiore o uguale a 100 in collo femore");
														return false;
													}
												}

												if(check_frax_aggiustato_applicabile){
													if(!check_percentuale_frax_aggiustato){
														if(!valore_frax_aggiustato){
															
															$('html,body').animate({
        													scrollTop: $('input[name="Frax_aggiustato_applicabile"]').offset().top},
        													'slow');

															alert("Seleziona un opzione per il frax");
															return false;
														}
														
													}
													if(!check_percentuale_collo_femore_aggiustato){
														if(!valore_collo_femore_aggiustato){
															$('html,body').animate({
        													scrollTop: $('input[name="Frax_aggiustato_applicabile"]').offset().top},
        													'slow');

															alert("Seleziona un opzione per collo femore");
															return false;	
														}
													}
												}
		
																					
											    //TBS COLONNA
												var tbs_colonna_applicabile = document.querySelectorAll('input[name="tbs_colonna_applicabile"]');
								            	var tbs_colonna_percentuale = document.querySelectorAll('input[name="percentuale_tbs_colonna"]');

												var check_tbs_colonna_applicabile = Array.prototype.slice.call(tbs_colonna_applicabile).some(x => x.checked);
												var check_percentuale_tbs_colonna = Array.prototype.slice.call(tbs_colonna_percentuale).some(x => x.checked);
												
												var tbs_colonna_valore = document.querySelectorAll('input[name="tbs_colonna_valore"]');
												var valore_tbs_colonna = Array.prototype.slice.call(tbs_colonna_valore).some(x => x.value);
												
												var numero_tbs_colonna = $('input[name="tbs_colonna_valore"]').val();
												

												
												if(check_tbs_colonna_applicabile && !check_percentuale_tbs_colonna){
													
													if(parseFloat(numero_tbs_colonna) >= 100){
														alert("Inserisci un numero inferiore o uguale a 100 in TBS colonna");
														return false;
													}
												}

												espressione_regolare_decimale = /^-?[0-9]\d*([.,][0-9]+)?$/g;
												if(check_tbs_colonna_applicabile && !check_percentuale_tbs_colonna){
													if(!espressione_regolare_decimale.test(numero_tbs_colonna)){
														alert("Inserisci un numero in TBS colonna");
														return false;
													}
													
												}

												//SOSPENSIONE TEMPORANEA DELLA TERAPIA IN CORSO
												espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												var check_sospensione_temporanea_terapia = $('input[name=sospensione_terapia]:checked','#myForm2').val();
												//alert(check_sospensione_temporanea_terapia == null);
												var farmaco = $('input[name=Farmaco]','#myForm2').val();
												//alert(farmaco == "");
												var mesi = $('input[name=Mesi]','#myForm2').val();
												if(check_sospensione_temporanea_terapia){
													if(!espressione_regolare_numero.test(mesi)){
														$('html,body').animate({
        													scrollTop: $('input[name=sospensione_terapia]').offset().top},
        													'slow');

														alert("Inserisci numero mesi in sospensione temporanea della terapia in corso");
														return false;
													}
												}
												if(check_sospensione_temporanea_terapia){
													if(farmaco == "" || mesi == ""){
														$('html,body').animate({
        													scrollTop: $('input[name=sospensione_terapia]').offset().top},
        													'slow');
														alert("Inserisci opzioni in sospensione temporanea della terapia in corso");
														return false;
													}
												}
												
												//ALTRO
												var altro = $('input[name=Altro]:checked','#myForm2').val();
												var value_altro = $('input[name=value_altro]','#myForm2').val();
												if(altro){
													if(value_altro == ""){
														$('html,body').animate({
        													scrollTop: $('input[name=Altro]').offset().top},
        													'slow');

														alert("Inserisci testo in altro");
														return false;
													}
												}

												//CONTROLLI SUGGERITI
												espressione_regolare_numero = /^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/g;
												var controllo_densitometrico = $('input[name=colloquio_densitometrico]:checked','#myForm2').val();
												var controllo_densitometrico_mesi = $('input[name=colloquio_densitometrico_mesi]','#myForm2').val();
												
												if(controllo_densitometrico){
													if(!espressione_regolare_numero.test(controllo_densitometrico_mesi)){
														alert("Inserisci numero mesi in controllo densitometrico");
														return false;
													}
												}

												if(controllo_densitometrico){
													if(!controllo_densitometrico_mesi){
														
														$('html,body').animate({
        													scrollTop: $('input[name=colloquio_densitometrico]').offset().top},
        													'slow');

														alert("Inserisci mesi in controllo densitometrico");
														return false;

													}
												}
												
												var check_terapie_ormonali = $('input[name=TerapieOrmonali]:checked','#myForm2').val();								            
								            	var select_terapie_ormonali = $('#text_area_terapie_ormonali').text();
								            	//alert(select_terapie_ormonali);
								            	if(check_terapie_ormonali){
								            		if(select_terapie_ormonali == ""){
								            			
								            			$('html,body').animate({
        													scrollTop: $('input[name=TerapieOrmonali]').offset().top},
        													'slow');

								            			alert("Inserisci almeno una terapia ormonale");
								            			//return false;
								            			return false;
								            		}
								            	}

								            	var check_terapie_osteoprotettive = $('input[name=TerapieOsteoprotettive]:checked','#myForm2').val();
								            	var select_terapie_osteoprotettive = $('#text_area_terapie_osteoprotettive').text();
								            	//alert(select_terapie_osteoprotettive);
								            	if(check_terapie_osteoprotettive){
								            		if(select_terapie_osteoprotettive == ""){
								            			
								            			$('html,body').animate({
        													scrollTop: $('input[name=TerapieOsteoprotettive]').offset().top},
        													'slow');

								            			alert("Inserisci almeno una terapia osteoprotettiva");
								            			//return false;
								            			return false;
								            		}
								            	}

								            	var check_vitamina_d_terapia = $('input[name=VitaminaDTerapia]:checked','#myForm2').val();
								            	var select_vitamina_d_terapia = $('#text_area_vitamina_d_terapia').text();
								            	if(check_vitamina_d_terapia){
								            		if(select_vitamina_d_terapia == ""){
								            			
								            			$('html,body').animate({
        													scrollTop: $('input[name=VitaminaDTerapia]').offset().top},
        													'slow');

								            			alert("Inserisci almeno un opzione in vitamina D terapia");
								            			return false;
								            		}
								            	}

								            	var check_vitamina_d_supplementazione = $('input[name=VitaminaDSupplementazione]:checked','#myForm2').val();
								            	var select_vitamina_d_supplementazione = $('#text_area_vitamina_d_supplementazione').text();
								            	if(check_vitamina_d_supplementazione){
								            		if(select_vitamina_d_supplementazione == ""){
								            			
								            			$('html,body').animate({
        													scrollTop: $('input[name=VitaminaDSupplementazione]').offset().top},
        													'slow');

								            			alert("Inserisci almeno un opzione in vitamina D supplementazione");
								            			return false;
								            		}
								            	}

								            	var check_calcio_supplementazione = $('input[name=CalcioSupplementazione]:checked','#myForm2').val();
								            	var select_calcio_supplementazione = $('#text_area_calcio_supplementazione').text();
								            	if(check_calcio_supplementazione){
								            		if(select_calcio_supplementazione == ""){
								            			
								            			$('html,body').animate({
        													scrollTop: $('input[name=CalcioSupplementazione]').offset().top},
        													'slow');

								            			alert("Inserisci almeno un opzione in calcio supplementazione");
								            			return false;
								            		}
								            	}
								            	
								            });




											

								        }); //end document.ready

										
							        </script>

				            		<script>
				            		//JAVASCRIPT PER CAMBIO DINAMICO COLLOQUIO PRESSO IL CENTRO

				            			$('#colloquio_presso_il_centro').bind('click',function(){
				            				if($('#colloquio_presso_il_centro').prop('checked')){
				            					$('#Valutazioni_esame').prop('disabled',false)
				            					//alert($('#Valutazioni_esame').val());
				            					$('#Attivazione_piano_terapeutico').prop('disabled',false)
				            					$('#Controllo_clinico').prop('disabled',false)
				            					


				            				}
				            				else{
				            					$('#Valutazioni_esame').prop('disabled',true)
				            					$('#Attivazione_piano_terapeutico').prop('disabled',true)
				            					$('#Controllo_clinico').prop('disabled',true)
				            					$('#Valutazioni_esame').prop('checked',false)
				            					$('#Attivazione_piano_terapeutico').prop('checked',false)
				            					$('#Controllo_clinico').prop('checked',false)
				            					
				            				}


				            			});

				            		</script>
	        					</div><!--/col-sm-11-->
        					</div>
        				</div>
        			</div>
        		</form>
    		</div>

        	<footer>
          		<hr>
          		<p>Centro di Ricerca per lo Studio della Menopausa e dell'Osteoporosi - Direttore: Prof. Gloria Bonaccorsi</p>
          		<p><b>Sede:</b> Via Boschetto, 29/31 - 44124 Ferrara - <b>Tel:</b> +39 0532 742253 - <b>Fax:</b> +39 0532 747038 - <b>Numero verde:</b> 800229394 - <b>Email:</b> cmo@unife.it</p>
          		<hr>
    		</footer>

      
    
		</div><!-- chiude il container-->

    






















	

    
    		<!-- CODICE JAVASCRIPT PER IL CAMBIO DINAMICO DI PATOLOGIA DEL CAVO ORALE-->
    		<script>
				//Se clicco sulla check patologia del cavo orale, allora la text per la terapia è visibile
				//altrimenti se non è cliccata la check, la text è disabilitata
				$('#PatologiaDelCavoOrale').bind('click',function(){
					if($('#PatologiaDelCavoOrale').prop('checked'))
					{
						
						$('#terapia').prop('disabled', false)
					
					}
					else{
						$('#terapia').prop('disabled', true)
						$('#terapia').prop('value', '')
						$('#terapia').prop('checked', false)	
					}	
				});
			</script>


			<!-- CODICE JAVASCRIPT PER IL CAMBIO DINAMICO DI PATOLOGIA UTERINA-->
			<script type="text/javascript">
				//Se clicco sulla check patologia del cavo orale, allora la text per la terapia è visibile
				//altrimenti se non è cliccata la check, la text è disabilitata
				$('#PatologieUterine').bind('click',function(){
					if($('#PatologieUterine').prop('checked'))
					{
						
						$('#diagnosi1').prop('disabled', false)
					
					}
					else{
						$('#diagnosi1').prop('disabled', true)
						$('#diagnosi1').prop('value', '')
						//$('#diagnosi1').prop('checked', false)	
					}	
				});
			</script>

			<script type="text/javascript">
				$('#Abusoalcool1').bind('click',function(){
					if($('#Abusoalcool1').prop('checked'))
					{
					
						$('#abusoalcoolsi').prop('disabled', false)
						$('#abusoalcoolno').prop('disabled', false)
					}
					else{
						$('#abusoalcoolsi').prop('disabled', true)
						$('#abusoalcoolno').prop('disabled', true)
						$('#abusoalcoolno').prop('checked', false)
						$('#abusoalcoolsi').prop('checked', false)
					}
					
				});


			</script>


			<!-- CODICE JAVASCRIPT PER IL CAMBIO DINAMICO DI NEOPLASIA-->
			<script type="text/javascript">
				//Se clicco sulla check patologia del cavo orale, allora la text per la terapia è visibile
				//altrimenti se non è cliccata la check, la text è disabilitata
				$('#Neoplasia').bind('click',function(){
					if($('#Neoplasia').prop('checked'))
					{
						
						$('#data_neoplasia').prop('disabled', false)
						$('#terapia_neoplasia').prop('disabled', false)
					
					}
					else{
						$('#data_neoplasia').prop('disabled', true)
						$('#terapia_neoplasia').prop('disabled', true)
						$('#data_neoplasia').prop('value', '')
						$('#terapia_neoplasia').prop('value', '')
						//$('#diagnosi1').prop('checked', false)	
					}	
				});
			</script>



			<!-- CODICE JAVASCRIPT PER ALTRE PATOLOGIE-->
			<script type="text/javascript">
  				$('#AltroPatologie').bind('click',function(){
					if($('#AltroPatologie').prop('checked'))
					{
	
						$('#altro_patologie').prop('disabled', false)

					}
					else{
						$('#altro_patologie').prop('disabled', true)
						$('#altro_patologie').prop('value', '')
							
					}	
				});

  			</script>

  			<!-- CODICE JAVASCRIPT PER ALLERGIE/INTOLLERANZE-->
		  	<script>
				$('#Allergie').bind('click',function(){
					if($('#Allergie').prop('checked'))
					{

						$('#allergie').prop('disabled', false)

					}
					else{
						$('#allergie').prop('disabled', true)
						$('#allergie').prop('value', '')
						
					}	
				});


			</script>

			<script>
				$('#Intolleranza').bind('click',function(){
					if($('#Intolleranza').prop('checked'))
					{

						$('#intolleranza').prop('disabled', false)

					}
					else{
						$('#intolleranza').prop('disabled', true)
						$('#intolleranza').prop('value', '')
						
					}	
				});


			</script>

			<script>
				$(document).ready(function(){
					if($('#DensitometriaPrecedente').prop('checked'))
					{
	
						$('#data_densitometria_precedente').prop('disabled', false)
						$('#densitometria_precedente_interna').prop('disabled', false)
						$('#Femore-collo-destro').prop('disabled',false)
						$('#Femore-collo-sinistro').prop('disabled',false)
						//$('#VertebreValutate').prop('disabled', false)
						$('#L1').prop('disabled', false)
						$('#L2').prop('disabled', false)
						$('#L3').prop('disabled', false)
						$('#L4').prop('disabled', false)

						$('#colonna_lombare_appicabile').prop('disabled',false)
						
						
						
						$('#femore_collo_appicabile').prop('disabled',false)
						

						

					}
				})

			</script>

			<!-- CODICE JAVASCRIPT PER DENSITOMETRIA PRECEDENTE E VERTEBRE VALUTATE-->

  			<script type="text/javascript">
  			
  				$('#DensitometriaPrecedente').bind('click',function(){
					if($('#DensitometriaPrecedente').prop('checked'))
					{
	
						$('#data_densitometria_precedente').prop('disabled', false)
						$('#densitometria_precedente_interna').prop('disabled', false)
						$('#Femore-collo-destro').prop('disabled',false)
						$('#Femore-collo-sinistro').prop('disabled',false)
						//$('#VertebreValutate').prop('disabled', false)
						$('#L1').prop('disabled', false)
						$('#L2').prop('disabled', false)
						$('#L3').prop('disabled', false)
						$('#L4').prop('disabled', false)

						$('#colonna_lombare_appicabile').prop('disabled',false)
						
						
						
						$('#femore_collo_appicabile').prop('disabled',false)
						

						

					}
					else{
						$('#data_densitometria_precedente').prop('disabled', true)
						$('#densitometria_precedente_interna').prop('disabled', true)
						$('#densitometria_precedente_interna').prop('checked', false)

						$('#Femore-collo-destro').prop('disabled',true)
						$('#Femore-collo-sinistro').prop('disabled',true)
						$('#Femore-collo-destro').prop('checked',false)
						$('#Femore-collo-sinistro').prop('checked',false)
						//$('#VertebreValutate').prop('disabled', true)
						//$('#VertebreValutate').prop('checked', false)
						
						$('#data_densitometria_precedente').prop('value', '')
											

						$('#L1').prop('disabled', true)
						$('#L2').prop('disabled', true)
						$('#L3').prop('disabled', true)
						$('#L4').prop('disabled', true)


						$('#L1').prop('checked', false)
						$('#L2').prop('checked', false)
						$('#L3').prop('checked', false)
						$('#L4').prop('checked', false)

						$('#colonna_lombare_appicabile').prop('disabled',true)
						$('#colonna_lombare_appicabile').prop('checked',false)

						
						$('#ColonnaLombareTscoreIntero').prop('disabled',true)
						$('#ColonnaLombareTscoreDecimale').prop('disabled',true)
						$('#ColonnaLombareTscoreIntero').prop('value','')
						$('#ColonnaLombareTscoreDecimale').prop('value','')
						
						$('#ColonnaLombareZscoreIntero').prop('disabled',true)
						$('#ColonnaLombareZscoreDecimale').prop('disabled',true)
						$('#ColonnaLombareZscoreIntero').prop('value','')
						$('#ColonnaLombareZscoreDecimale').prop('value','')
						
						
						$('#femore_collo_appicabile').prop('disabled',true)
						$('#femore_collo_appicabile').prop('checked',false)
						$('#ColloFemoreTscoreIntero').prop('disabled',true)
						$('#ColloFemoreTscoreDecimale').prop('disabled',true)
						$('#ColloFemoreTscoreIntero').prop('value','')
						$('#ColloFemoreTscoreDecimale').prop('value','')
						
						$('#ColloFemoreZscoreIntero').prop('disabled',true)
						$('#ColloFemoreZscoreDecimale').prop('disabled',true)
						
						$('#ColloFemoreZscoreIntero').prop('value','')
						$('#ColloFemoreZscoreDecimale').prop('value','')
							
					}	
				});
			
  			</script>

  			<!-- JAVASCRIPT PER IL CAMBIO DINAMICO TERAPIE OSTEOPROTETTIVE, DA COMPLETARE -->
  			<script>
  				
				//CODICE JAVASCRIPT PER TERAPIE OSTEOPROTETTIVE, CON CHECKBOX: POSSO SCEGLIERE DI AVERE PIÙ DI UNA TERAPIA
				//O NESSUNA TERAPIA
				$('#Osteoprotettiva').bind('click',function(){
					if($('#Osteoprotettiva').prop('checked'))
					{
						
						$('#TipoOsteoprotettiva').prop('disabled', false)
						$('#DurataOsteoprotettiva').prop('disabled', false)
						//$('#inserisci2').prop('disabled', false)
						
					}
					else{
						$('#TipoOsteoprotettiva').prop('disabled', true)
						$('#TipoOsteoprotettiva').prop('selectedIndex',0)
						$('#DurataOsteoprotettiva').prop('disabled', true)
						$('#DurataOsteoprotettiva').prop('value', '')
						$('#inserisci2').prop('disabled', true)
						$('#CancellaOsteoprotettiva').prop('disabled', true)

						$('#valori_osteoprotettiva').val(""); //resetto la text area con i valori

						$("#text_area_osteoprotettiva").empty(); //resetto la select
						tipi = {}; //resetto l'array dei tipi
					}
					
					
				});

				$('#Ormonale').bind('click',function(){
					if($('#Ormonale').prop('checked'))
					{
						
						$('#TipoOrmonale').prop('disabled', false)
						$('#DurataOrmonale').prop('disabled', false)
						
					}
					else{
						$('#TipoOrmonale').prop('disabled', true)
						$('#TipoOrmonale').prop('selectedIndex',0)
						$('#DurataOrmonale').prop('disabled', true)
						$('#DurataOrmonale').prop('value', '')
						$('#inserisci').prop('disabled', true)
						$('#Cancella').prop('disabled',true)



						$('#valori_ormonale').val(""); //resetto la text area con i valori

						$("#text_area_orm_sost").empty();
						tipi2 = {};
					}
					
					
				});

				$('#VitaminaD').bind('click',function(){
					if($('#VitaminaD').prop('checked')){

						$('#TipoVitaminaD').prop('disabled', false)
						$('#DurataVitaminaD').prop('disabled', false)
						$('#InserisciVitaminaD').prop('disabled', false)
						$('#CancellaVitaminaD').prop('disabled', false)
					}
					else{
						$('#TipoVitaminaD').prop('disabled', true)
						$('#TipoVitaminaD').prop('selectedIndex', 0)
						$('#DurataVitaminaD').prop('disabled', true)
						$('#DurataVitaminaD').prop('value', '')
						$('#InserisciVitaminaD').prop('disabled', true)
						$('#CancellaVitaminaD').prop('disabled', true)

						$('#valori_vitamina_d').val("")

						$('#text_area_vitamina_d').empty()
						vitamina_d = {}
					}
					


				});

				
				$('#AltroTerapie').bind('click',function(){
					if($('#AltroTerapie').prop('checked'))
					{
						
						$('#ValueAltroTerapie').prop('disabled', false)
						
						
					}
					else{
						$('#ValueAltroTerapie').prop('disabled', true)
						$('#ValueAltroTerapie').prop('value', '')
					}
					
					
				});

			</script>

  			<script type="text/javascript">
  				$('#VertebreValutate').bind('click',function(){
					if($('#VertebreValutate').prop('checked'))
					{
	
						$('#L1').prop('disabled', false)
						$('#L2').prop('disabled', false)
						$('#L3').prop('disabled', false)
						$('#L4').prop('disabled', false)
						$('#ColonnaLombareTscore').prop('disabled',false)
						$('#ColonnaLombareZscore').prop('disabled',false)
						$('#ColloFemoreTscore').prop('disabled',false)
						$('#ColloFemoreZscore').prop('disabled',false)
						



					}
					else{
						$('#L1').prop('disabled', true)
						$('#L2').prop('disabled', true)
						$('#L3').prop('disabled', true)
						$('#L4').prop('disabled', true)
						$('#ColonnaLombareTscore').prop('disabled',true)
						$('#ColonnaLombareZscore').prop('disabled',true)
						$('#ColloFemoreTscore').prop('disabled',true)
						$('#ColloFemoreZscore').prop('disabled',true)

						$('#L1').prop('checked', false)
						$('#L2').prop('checked', false)
						$('#L3').prop('checked', false)
						$('#L4').prop('checked', false)

						

						$('#ColonnaLombareTscore').prop('value', '')
						$('#ColonnaLombareZscore').prop('value', '')
						$('#ColloFemoreTscore').prop('value', '')
						$('#ColloFemoreZscore').prop('value', '')
													
					}	
				});

  			</script>

  			<!-- CIDUCE JAVASCRIPT PER CAMBIO DINAMICO FRATTURA DA FRAGILITA' VERTEBROSA/FEMORE -->
  			<script type="text/javascript">
  			$(document).ready(function(){
				$('#FratturaFragilitaVertebrosa').bind('click',function(){
					if($('#FratturaFragilitaVertebrosa').prop('checked'))
					{

						$('#FratturaVertebreUna').prop('disabled', false)
						$('#FratturaVertebrePiuDiUna').prop('disabled', false)

						$('#FratturaFemoreUna').prop('disabled', false)
						$('#FratturaFemorePiuDiUna').prop('disabled', false)

					}
					else{
						$('#FratturaVertebreUna').prop('disabled', true)
						$('#FratturaVertebrePiuDiUna').prop('disabled', true)

						$('#FratturaFemoreUna').prop('disabled', true)
						$('#FratturaFemorePiuDiUna').prop('disabled', true)

						$('#FratturaVertebreUna').prop('checked', false)
						$('#FratturaVertebrePiuDiUna').prop('checked', false)
						$('#FratturaFemoreUna').prop('checked', false)
						$('#FratturaFemorePiuDiUna').prop('checked', false)


					}	
				});
			});

			</script>

			<!--JAVASCRIPT PER CAMBIO DINAMICO INVIATA DA-->
			<script>
			
  				$('#myForm input').on('change', function() {
						var val = $('input[name=InviataDa]:checked','#myForm').val(); 
					//alert(val);
					if(val == "Ginecologo"){
						$('#GinecologoDelCentro').prop('disabled',false);
						$('#GinecologoEsterno').prop('disabled',true);
						
						$('#GinecologoEsterno').prop('value','');
					}
					else if(val == "Altro specialista"){
							
						$('#GinecologoDelCentro').prop('disabled',true);
						$('#GinecologoEsterno').prop('disabled', false);
						$('#GinecologoDelCentro').prop('value','');
					}
					else if(val == "medico curante"){
						$('#GinecologoDelCentro').prop('disabled',true);
						$('#GinecologoEsterno').prop('disabled', true);
						$('#GinecologoDelCentro').prop('value','');
						$('#GinecologoEsterno').prop('value', '');

					}
					else if(val == "se stessa"){
						$('#GinecologoDelCentro').prop('disabled',true);
						$('#GinecologoEsterno').prop('disabled', true);
						$('#GinecologoDelCentro').prop('value','');
						$('#GinecologoEsterno').prop('value', '');
					}
					
				});
			
			</script>

			<script>
				$(document).ready(function(){
					var val = $('input[name=InviataDa]:checked','#myForm').val(); 
					//alert(val);
					if(val == "Ginecologo"){
						$('#GinecologoDelCentro').prop('disabled',false);
						$('#GinecologoEsterno').prop('disabled',true);
						
						$('#GinecologoEsterno').prop('value','');
					}
					else if(val == "Altro specialista"){
							
						$('#GinecologoDelCentro').prop('disabled',true);
						$('#GinecologoEsterno').prop('disabled', false);
						$('#GinecologoDelCentro').prop('value','');
					}
					else if(val == "medico curante"){
						$('#GinecologoDelCentro').prop('disabled',true);
						$('#GinecologoEsterno').prop('disabled', true);
						$('#GinecologoDelCentro').prop('value','');
						$('#GinecologoEsterno').prop('value', '');

					}
					else if(val == "se stessa"){
						$('#GinecologoDelCentro').prop('disabled',true);
						$('#GinecologoEsterno').prop('disabled', true);
						$('#GinecologoDelCentro').prop('value','');
						$('#GinecologoEsterno').prop('value', '');
					}


				});

			</script>

			<!-- JAVASCRIPT PER IL CAMBIO DINAMICO BMI-->
			<script>
				$("#Altezza, #Peso").on( 'change', function () {

				    var altezza, peso;
				    altezza = parseFloat($('#Altezza').val());
				    //alert(altezza);
				    altezza = altezza / 100;
				    peso = parseFloat($('#Peso').val());
				    //alert(peso);
				    var bmi = calbmi(peso, altezza);

				    $('#BMI').val(bmi);

				});


				function calbmi(peso, altezza) {

				    var bmi = peso / (altezza * altezza);
				    return (bmi);

				}
			</script>

			<!--JAVASCRIPT PER CAMBIO DINAMICO MALATTIE ATTUALI -->
			<script type="text/javascript">
				$('#MalattieAttuali').bind('click',function(){
				if($('#MalattieAttuali').prop('checked'))
				{
					
					$('#artritereumatoide').prop('disabled', false)
					$('#artritepsoriasica').prop('disabled', false)
					$('#lupus').prop('disabled', false)
					$('#sclerodermia').prop('disabled', false)
					$('#altreconnettiviti').prop('disabled', false)
				}
				else{
					$('#artritereumatoide').prop('disabled', true)
					$('#artritepsoriasica').prop('disabled', true)
					$('#lupus').prop('disabled', true)
					$('#sclerodermia').prop('disabled', true)
					$('#altreconnettiviti').prop('disabled', true)

					$('#artritereumatoide').prop('checked', false)
					$('#artritepsoriasica').prop('checked', false)
					$('#lupus').prop('checked', false)
					$('#sclerodermia').prop('checked', false)
					$('#altreconnettiviti').prop('checked', false)
				}
				
			});
		</script>

		<!--JAVASCRIPT CAMBIO DINAMICO ETÀ MENOPAUSA-->
		<script type="text/javascript">
				      					
			//cambio dinamico età menopausa: se cliccati Perimenopausa e Premenopausa, allora
			//disbilito la text eta menopausa, perche non è in menopausa e non la devo calcolare
			//in tutti gli altri casi la text eta menopausa è disponibile
			//PROVA È LA SPAN DI ETAMENOPAUSA

			$('#myForm input').on('change', function() {
				var val = $('input[name=Statomenopausale]:checked','#myForm').val(); 
				//alert(val);
				if(val == "Premenopausa"){
					//$('#Perimenopausa').prop('disabled',false);
					
					$("#etamenopausa").css('visibility','hidden')
					$("#prova").css('visibility','hidden')
					
					
				}
				else if(val == "Perimenopausa"){
					$('#etamenopausa').css('visibility','hidden')
					$("#prova").css('visibility','hidden')
					
				}
				else{
					$('#etamenopausa').css('visibility','visible')
					$("#prova").css('visibility','visible') //È LA SPAN DI ETÀ MENOPAUSA, SE NON È PRE O PERI POSSO VISUALIZZARLA
					$('#etamenopausa').prop('disabled',false)
					
				}
			
			});


		</script>

		<script>
			$(document).ready(function(){
				var val = $('input[name=Statomenopausale]:checked','#myForm').val(); 
				//alert(val);
				if(val == "Premenopausa"){
					//$('#Perimenopausa').prop('disabled',false);
					
					$("#etamenopausa").css('visibility','hidden')
					$("#prova").css('visibility','hidden')
					
					
				}
				else if(val == "Perimenopausa"){
					$('#etamenopausa').css('visibility','hidden')
					$("#prova").css('visibility','hidden')
					
				}
				else{
					$('#etamenopausa').css('visibility','visible')
					$("#prova").css('visibility','visible') //È LA SPAN DI ETÀ MENOPAUSA, SE NON È PRE O PERI POSSO VISUALIZZARLA
					$('#etamenopausa').prop('disabled',false)
					
				}
			
			});


		

		</script>

		<script type="text/javascript">
			//JAVASCRIPT PER IL CAMBIO DINAMICO ETÀ MENOPAUSA IN BASE A ETÀ U.M.

			$("#UltimaMestruazione").on( 'change', function () {

			    //var eta = $("#Eta").val(); //prendo il valore dell'età
			    //alert(eta); lo prende giusto
			    var um;
			    um = $("#UltimaMestruazione").val(); //prendo l'anno dell'ultima mestruazione
			    // alert(um); //lo prende giusto
			    var birth = $("#DataNascita").val(); //prendo l'anno di nascita
			    var anno_n = birth.split("/"); //uso lo split su anno di nascita per spezzarlo
			    var anno = Number(anno_n[2]); //mi salvo l'anno che è l'elemento 2 della stringa spezzata
			    
			    var diff = Number(um - anno); //mi calcolo la differenza tra l anno di ultima mestruazione e l'anno di nascita
			    
			    
			    $("#etamenopausa").val(diff);

			});


		</script>

		<script type="text/javascript">
		//ultima mestruazione
			//var eta = $('#Eta').val();
			$('#etamenopausa').on('change', function(){	
				var eta_m = $('#etamenopausa').val();
				//alert(eta_m);
				var birth = $("#DataNascita").val(); //prendo l'anno di nascita
				//alert(birth);
				var anno_n = birth.split("/"); //uso lo split su anno di nascita per spezzarlo
				var anno = Number(anno_n[2]); //mi salvo l'anno che è l'elemento 2 della stringa spezzata
				//alert(anno);
				var um = parseInt(anno) + parseInt(eta_m);
				//alert(um);
				$('#UltimaMestruazione').val(um);
			});
		</script>


		<!-- JAVASCRIPT PER CAMBIO DINAMICO ABUSO FUMO-->
		<script>

		//eseguo uno script in cui se clicco la check abuso fumo, allora cambio lo stato della text in enabled, altrimenti disabled
		$(document).ready(function(){	
			$('#Abusofumo1').bind('click',function(){
				if($('#Abusofumo1').prop('checked'))
				{
					
					$('#menodi10').prop('disabled', false)
					$('#piudi10').prop('disabled', false)
				}
				else{
					$('#menodi10').prop('disabled', true)
					$('#piudi10').prop('disabled', true)
					$('#menodi10').prop('checked', false)
					$('#piudi10').prop('checked', false)
				}
				
			});
		});
					

		</script>

			

			<script>
				//JAVASCRIPT PER RENDERE DISPONIBILE IL PULSANTE INSERISCI (NELLA TEXTAREA) SOLO SE E' STATO SCELTO UN
				//TIPO ED E' STATA INSERITA LA DURATA
				$(document).ready(function() {
					$('input[name="inserisci2"]').attr('disabled', true);
					$('input[name="DurataOsteoprotettiva"],#TipoOsteoprotettiva option:selected').on('keyup',function() {
					    var textarea_value = $("#TipoOsteoprotettiva").val();
					    var text_value = $('input[name="DurataOsteoprotettiva"]').val();
					    
					    //alert("text-area: "+textarea_value+" text:"+text_value);
					    if(textarea_value != '' && text_value != '') {
					        $('input[name="inserisci2"]').attr('disabled' , false);
					        $('input[name="CancellaOsteoprotettiva"]').attr('disabled' , false);
					    }
					    else if(textarea_value == '' && text_value == '')
					   		$('input[name="inserisci2"]').attr('disabled' , true);
					   	else if(textarea_value != '' && text_value == '')
					   		$('input[name="inserisci2"]').attr('disabled' , true);
					    else{
					    	$('input[name="inserisci2"]').attr('disabled' , true);
					    }
					});

				});


				


				
			</script>

			<script>
			//vitamina D

				//JAVASCRIPT PER RENDERE DISPONIBILE IL PULSANTE INSERISCI (NELLA TEXTAREA) SOLO SE E' STATO SCELTO UN
				//TIPO ED E' STATA INSERITA LA DURATA
				/*
				$(document).ready(function() {
					$('input[name="InserisciVitaminaD"]').attr('disabled', true);
					$('input[name="DurataVitaminaD"],#TipoVitaminaD option:selected').on('keyup',function() {
					    var textarea_value = $("#TipoVitaminaD").val();
					    var text_value = $('input[name="DurataVitaminaD"]').val();
					    
					    //alert("text-area: "+textarea_value+" text:"+text_value);
					    if(textarea_value != '' && text_value != '') {
					        $('input[name="InserisciVitaminaD"]').attr('disabled' , false);
					        $('input[name="CancellaVitaminaD"]').attr('disabled' , false);
					    }
					    else if(textarea_value == '' && text_value == '')
					   		$('input[name="InserisciVitaminaD"]').attr('disabled' , true);
					   	else if(textarea_value != '' && text_value == '')
					   		$('input[name="InserisciVitaminaD"]').attr('disabled' , true);
					    else{
					    	$('input[name="InserisciVitaminaD"]').attr('disabled' , true);
					    }
					});

				});
				*/


			</script>


			<script type="text/javascript">
				$('#Intolleranza').bind('click',function(){
				if($('#Intolleranza').prop('checked'))
				{
					
					$('#Estrogeninonorali').prop('disabled', false)
					$('#Estrogeniorali').prop('disabled', false)
					$('#Bisfosfonatiorali').prop('disabled', false)
					$('#Ranelato').prop('disabled', false)
					$('#Raloxifene').prop('disabled', false)
					$('#AltroIntolleranza').prop('disabled', false)
				}
				else{
					$('#Estrogeninonorali').prop('disabled', true)
					$('#Estrogeniorali').prop('disabled', true)
					$('#Bisfosfonatiorali').prop('disabled', true)
					$('#Ranelato').prop('disabled', true)
					$('#Raloxifene').prop('disabled', true)
					$('#AltroIntolleranza').prop('disabled', true)

					$('#Estrogeninonorali').prop('checked', false)
					$('#Estrogeniorali').prop('checked', false)
					$('#Bisfosfonatiorali').prop('checked', false)
					$('#Ranelato').prop('checked', false)
					$('#Raloxifene').prop('checked', false)
					$('#AltroIntolleranza').prop('value', '')
				}
				
			});
		</script>

		<!--JAVASCRIPT PER CAMBIO DINAMICO PREGRESSE FRATTURE-->
		<script>

		//eseguo uno script in cui se clicco la check pregresse fratture, allora cambio lo stato della text in enabled, altrimenti disabled
			$('#PregresseFratture1').bind('click',function(){
				if($('#PregresseFratture1').prop('checked'))
				{
					
					$('#PregFratt').prop('disabled', false)
				}
				else{
					$('#PregFratt').prop('disabled', true)
					$('#PregFratt').prop('value','') //se non è attiva la check cancello quello che c'era scritto dentro
				}
			
			});
					

		</script>

			<script>
				//SE CAMBIAMO L'OPTION SELECT DEL TIPO DI OSTEOPROTETTIVA, ALLORA CANCELLA CIÒ CHE È STATO SCRITTO
				//IN DURATA, E RENDE INDISPONIBILE IL PULSANTE AGGIUNGI

				$(document).ready(function() {
					//$('#DurataOsteoprotettiva]').attr('disabled', true);
					$('#TipoOsteoprotettiva').bind('change',function(){

						$('#DurataOsteoprotettiva').prop('value','');
						$('#inserisci2').prop('disabled','true');

					});
				});
				

			</script>

			<script>
				//cambio dinamico stato terapia, se cliccato in atto, allora l'option in_atto passa da dibled
				//a enabled, la text da_quanto passa a disabled, e cancello il valore scritto precedentemente
				//se è stato cliccato sospesa, fa il contrario
  				$('#myForm input').on('change', function() {
					var val = $('input[name=stato_terapia]:checked','#myForm').val(); 
						
					if(val == "In atto"){
						$('#stato_inatto').prop('disabled',false);
						
						//$('#stato_sospesa').prop('disabled',true);
						$('#da_quanto').prop('disabled', true);
						$('#da_quanto').prop('value','');
						$('#text_area_terapie_ormonali').prop('disabled',false)
						//$('#TipoOrmonale').prop('disabled',false)
						
					}
					else if(val == "Sospesa"){
						//$('#stato_inatto').prop('disabled',true);
						$('#stato_sospesa').prop('disabled',false);
						$('#da_quanto').prop('disabled', false);
					}
					else if(val == "Mai"){
						$('#da_quanto').prop('disabled', true);
						$('#da_quanto').prop('value','');
					}
					
				});

			</script>

			

			<script>
				//JAVASCRIPT PER RENDERE DISPONIBILE IL PULSANTE INSERISCI (NELLA TEXTAREA) SOLO SE E' STATO SCELTO UN
				//TIPO ED E' STATA INSERITA LA DURATA
				$(document).ready(function() {
					$('input[name="inserisci"]').attr('disabled', true);
					$('input[name="DurataOrmonale"],#TipoOrmonale option selected').on('keyup',function() {
					    var tipoOrmonale = $("#TipoOrmonale").val();
					    var text_value = $('input[name="DurataOrmonale"]').val();
					    //alert(tipoOrmonale);
					    if(tipoOrmonale != '' && text_value != '') {
					        $('input[name="inserisci"]').attr('disabled' , false);
					        $('input[name="CancellaOrmonale"]').attr('disabled' , false);
					    }else{
					        $('input[name="inserisci"]').attr('disabled' , true);
					        $('input[name="CancellaOrmonale"]').attr('disabled' , true);
					    }
					});

				});
			</script>

			<script>
				//JAVASCRIPT SULLA SELECT DI ORMONALE SOSTITUTIVA. SE CAMBIO IL TIPO ALLORA VIENE RESETTATA LA DURATA
				//E VIENE DISABILITATO IL TASTO INSERISCI POICHÈ NON È PRESENTE LA DURATA
				$(document).ready(function() {
					//$('#DurataOsteoprotettiva]').attr('disabled', true);
					$('#TipoOrmonale').bind('change',function(){

						$('#DurataOrmonale').prop('value','');
						$('#inserisci').prop('disabled','true');

					});
				});
				

			</script>

			<!--  JAVASCRIPT PER CAMBIO DINAMICO USO CORTISONE -->
			<script type="text/javascript">
				$('#Usocortisone1').bind('click',function(){
					if($('#Usocortisone1').prop('checked'))
					{
						
						$('#tradueecinque').prop('disabled', false)
						$('#piudicinque').prop('disabled', false)
					}
					else{
						$('#tradueecinque').prop('disabled', true)
						$('#piudicinque').prop('disabled', true)
						$('#tradueecinque').prop('checked', false)
						$('#piudicinque').prop('checked', false)
					}
				
				});


			</script>

			<script type="text/javascript">
			$(document).ready(function(){
				if($('#Osteoporosisecondaria').prop('checked')){
					$('#CauseSecondarie').prop('disabled', false)

						//$('#text_area_causesecondarie').prop('disabled', false)
					$('#Aggiungi_Cause_Secondarie').prop('disabled', false)
					$('#Cancella_causa_secondaria').prop('disabled', false)
				}
			});


			</script>

			<script type="text/javascript">
				$('#CauseSecondarie').prop('disabled', true)
				//$('#text_area_causesecondarie').prop('disabled', true)
				$('#Aggiungi_Cause_Secondarie').prop('disabled', true)
				
				$('#Osteoporosisecondaria').bind('click',function(){
					if($('#Osteoporosisecondaria').prop('checked'))
					{
					
						$('#CauseSecondarie').prop('disabled', false)
						//$('#text_area_causesecondarie').prop('disabled', false)
						$('#Aggiungi_Cause_Secondarie').prop('disabled', false)
						$('#Cancella_causa_secondaria').prop('disabled', false)
						
					}
					else{
						$('#CauseSecondarie').prop('disabled', true)
						$('#CauseSecondarie').prop('selectedIndex',0)
						//$('#text_area_causesecondarie').prop('disabled', true)
						$('#Aggiungi_Cause_Secondarie').prop('disabled', true)
						$('#Cancella_causa_secondaria').prop('disabled', true)

						$('#valori_causesecondarie').val("");
						
						$("#text_area_causesecondarie").empty();
						cause_secondarie = {};

						
					}
				
				});
			</script>


			


			
  	</body>

  <!-- con questo script javascript ajax riempio dinamicamente la select della data di nascita in base al nome e cognome inserito-->
  	<script type="text/javascript">
      	$(document).ready(function(){
          	$('#datanascita1').on('change',function(){
              	var datanascita = $(this).val();
              	var nome = $('#nome').val();
              	var cognome = $('#cognome').val();
              	if(datanascita){
                  	$.ajax({
                    	type:'GET',
                    	url:'lib/ajaxData.php', 
                    	data:{birthdate:datanascita,nm:nome,cg:cognome},
                    	success:function(html){
                      		$('#datascan').html(html);    
                    	}
                  	}); 
              	}
              	else{
                  
                	$('#datascan').html('<option value="">Seleziona prima la data di nascita</option>'); 
              	}
          	});    
      	});
  	</script>

									<script type="text/javascript">

									var TERAPIE_ORMONALI_sugg;
									var TERAPIE_OSTEOPROTETTIVE_sugg;
									var VITAMINA_D_TERAPIA_sugg;
									var VITAMINA_D_SUPPLEMENTAZIONE_sugg;
									var CALCIO_SUPPLEMENTAZIONE_sugg;
									var suggestions = {};

                                    function stickyheaddsadaer(pk,data_scan)
                                    {   

										//qui salvo anamnesi
										var xmlhttp = new XMLHttpRequest();
										var datastring = $("#myForm").serialize();
										//console.log(datastring);
										xmlhttp.open("POST", "backend_frontend/filenon2.php", true);
										xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
										xmlhttp.send(datastring);

										//salvo diagnosi
										var xmlhttp = new XMLHttpRequest();
										var datastring = $("#myForm2").serialize();
										//console.log(datastring);
										xmlhttp.open("POST", "backend_frontend/filenon.php", true);
										xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
										xmlhttp.send(datastring);


										var xmlhttp = new XMLHttpRequest();
										xmlhttp.open("POST", "gethint.php", true);
										xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

										xmlhttp.onreadystatechange = function() {
											if (this.readyState == 4 && this.status == 200)
											{
												//TODO: sistemare Spiegazione non disponibile
												
												//console.log( this.responseText )

												var response = this.responseText;

												response = response.replace(/\n/g, '<br>');
												//console.log( response )
												
												var splitted = response.split("<br><br><br>");
												

												/////////////////////////////////////TERAPIE_ORMONALI//////////////////////////////////////////////////

												/*var TERAPIE_ORMONALI_CHECKBOX_prediction = splitted[0];
												var TERAPIE_ORMONALI_CHECKBOX_rule = splitted[1];
												var TERAPIE_ORMONALI_LISTA_prediction = splitted[2];
												var TERAPIE_ORMONALI_LISTA_rule = splitted[3];

												//console.log(TERAPIE_ORMONALI_CHECKBOX_prediction);

												if(TERAPIE_ORMONALI_CHECKBOX_prediction.localeCompare("idk")==0)
												{
													TERAPIE_ORMONALI_sugg = "Suggerimento non disponibile";
												}
												else if(TERAPIE_ORMONALI_CHECKBOX_prediction.localeCompare("0")==0)
												{	
													TERAPIE_ORMONALI_sugg = "Non si consiglia la terapia ormonale perchè:<br>"+TERAPIE_ORMONALI_CHECKBOX_rule;
												}
												else if(TERAPIE_ORMONALI_CHECKBOX_prediction.localeCompare("1") == 0)
												{
													if(TERAPIE_ORMONALI_LISTA_prediction.localeCompare("idk") == 0)
													{	
														TERAPIE_ORMONALI_sugg = "Si suggerisce la terapia ormonale perchè:<br>"+TERAPIE_ORMONALI_CHECKBOX_rule;
													}
													else
													{	
														TERAPIE_ORMONALI_sugg = "Si suggerisce la terapia ormonale perchè:<br>"+TERAPIE_ORMONALI_CHECKBOX_rule +"<br><br>di tipo: "
														+TERAPIE_ORMONALI_LISTA_prediction+" perchè:<br>"+TERAPIE_ORMONALI_LISTA_rule;
													}
												}*/
												

												for(var i=0; i<5; i++)
												{
													if(splitted[0+4*i].localeCompare("idk")==0)
													{
														suggestions[i] = "Suggerimento non disponibile";
													}
													else if(splitted[0+4*i].localeCompare("0")==0)
													{	
														suggestions[i] = "Non si consiglia la terapia perchè:<br>"+splitted[1+4*i];
													}
													else if(splitted[0+4*i].localeCompare("1") == 0)
													{
														if(splitted[2+4*i].localeCompare("idk") == 0)
														{	
															suggestions[i] = "Si suggerisce la terapia perchè:<br>"+splitted[1+4*i];
														}
														else
														{	
															suggestions[i] = "Si suggerisce la terapia perchè:<br>"+splitted[1+4*i] +"<br><br>di tipo: "
															+splitted[2+4*i]+" perchè:<br>"+splitted[3+4*i];
														}
													}
												}
												////////////////////////////////////////////TERAPIE_OSTEOPROTETTIVE//////////////////////////////////////////////////

												
												

												

											}
										};

										xmlhttp.send("pk="+pk+"&datascan="+data_scan);
                                    }

									function myFunction(sugg_button_id) 
									{
										console.log(sugg_button_id);
										var mapForm = document.createElement("form");
										mapForm.target = "Map";
										mapForm.method = "POST"; 
										mapForm.action = "show_suggestion.php";

										var mapInput = document.createElement("input");
										mapInput.type = "text";
										mapInput.name = "sugg";
										mapInput.hidden = "true";
//											  <input class="btn btn-primary" id = "ter_orm_sugg_button" type="button" value = "Suggerisci" name="SubmitButton" onclick = 'myFunction(this.id)'/>

										if(sugg_button_id.localeCompare("ter_orm_sugg_button")==0)
											mapInput.value = suggestions[0];
										else if(sugg_button_id.localeCompare("ter_osteo_sugg_button")==0)
											mapInput.value = suggestions[1];
										else if(sugg_button_id.localeCompare("vit_d_ter_sugg_button")==0)
											mapInput.value = suggestions[2];
										else if(sugg_button_id.localeCompare("vit_d_supp_sugg_button")==0)
											mapInput.value = suggestions[3];
										else if(sugg_button_id.localeCompare("calcio_supp_sugg_button")==0)
											mapInput.value = suggestions[4];
							



										mapForm.appendChild(mapInput);

										document.body.appendChild(mapForm);

										map = window.open("", "Map", "status=0,title=0,height=300,width=500,scrollbars=1");

										if (map) {
										mapForm.submit();
										} else {
										alert('You must allow popups for this map to work.');
										}



									}
									function myFunction2()
									{
									
									}

									window.onload = function() {
										stickyheaddsadaer(<?php echo $args; ?>);
										


									};



                                    </script>

									<!-- xxx -->

</html>
