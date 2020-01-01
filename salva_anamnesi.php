<?php
	include_once './lib/inserimento_prelievo.php';
	$mysqli = new mysqli('localhost', 'utente_web', 'CMOREL96T45', 'CMO'); 
	
	// Recupero PATIENT_KET e SCAN_DATE
	//$pk = $_SESSION[PATIENT_KEY];
	//$datascan_mysql = $_SESSION[SCANDATE];
	$pk = $_POST[pk];
	$datascan_mysql = $_POST[datascan];
	//RESIDENZA
	$residenza = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[Residenza])));

	//STRADA
	$strada = mysqli_real_escape_string($mysqli, $_POST[Strada]);
	//echo $strada;
	//VIA
	$via = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[Via])));

	//Telefono

	$telefono = mysqli_real_escape_string($mysqli, setNULL($_POST[Tel]));
	

	//INVIATA DA
	$inviata_da = setEnum($_POST[InviataDa]);
	//può essere: se stessa,medico curante,ginecologo,altro specialista
	$ginecologo_del_centro = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[GinecologoDelCentro]))); //se scelto ginecologo, prelevo il nome del ginecologo
	$altro_specialista = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[GinecologoEsterno]))); //se scelto altro specialista, prelevo il nome dello specialista


	//STATO MENOPAUSALE
	$stato_menopausale = setEnum($_POST[Statomenopausale]);
	$um = mysqli_real_escape_string($mysqli, setNULL($_POST[ultima_mestruazione])); 
	$eta_menopausa = mysqli_real_escape_string($mysqli, setNULL($_POST[etamenopausa])); //Prelevo l'età della menopausa. Questa va prelevata in tutti i casi tranne se è in Premenopausa o Perimenopausa


	//TERAPIE OSTEOPROTETTIVE
	$stato_terapia = setEnum($_POST[stato_terapia]);
	$sospesa_da = mysqli_real_escape_string($mysqli, setNULL($_POST[da_quanto])); 

	$ormonale = setCheck($_POST[Ormonale]);
	// PER ANTONIO: DA GESTIRE!
	if($ormonale == 1){
		$valori_ormonale = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_ormonale])); //Se è stata selezionata come terapia Ormonale, prelevo i valori dalla textarea
		$valori_ormonale = rtrim($valori_ormonale,","); //rimuovo la ',' alla fine della stringa
		$valori_ormonale = trim($valori_ormonale,","); //rimuovo le ',' all inizio della stirnga

		$valori_ormonale = preg_replace('/,+/', ',', $valori_ormonale); //espressione regolare per eliminare più di una ','
	}
	else $valori_ormonale = '';
		//$ids = explode("\n", str_replace("\n", "", $valori_ormonale)); //in questo modo mette tutto il contenuto nell'array $ids in posizione 0


	$osteoprotettiva = setCheck($_POST[Osteoprotettiva]);
	//selezionata come terapia Osteoprotettiva
	if($osteoprotettiva == 1){
		$valori_osteoprotettiva = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_osteoprotettiva])); //Se è stata selezionata come terapia Osteoprotettiva, prelevo i valori dalla textarea //Se è stata selezionata come terapia Ormonale, prelevo i valori dalla textarea
		$valori_osteoprotettiva = rtrim($valori_osteoprotettiva,","); //rimuovo la ',' alla fine della stringa
		$valori_osteoprotettiva = trim($valori_osteoprotettiva,","); //rimuovo le ',' all inizio della stirnga

		$valori_osteoprotettiva = preg_replace('/,+/', ',', $valori_osteoprotettiva); //espressione regolare per eliminare più di una ','
	}
	else $valori_osteoprotettiva = '';
	
	$vitamina_d = setCheck($_POST[VitaminaD]);
	if($vitamina_d == 1){
		$valori_vitamina_d = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_vitamina_d]));
		$valori_vitamina_d = rtrim($valori_vitamina_d,",");
		$valori_vitamina_d = trim($valori_vitamina_d);

		$valori_vitamina_d = preg_replace('/,+/', ',', $valori_vitamina_d);	
	}
	else $valori_vitamina_d = '';

	$altro_terapie = mysqli_real_escape_string($mysqli, setNULL($_POST[TerapieProtettive])); //vedo se è stata selezionata come terapia Altro
	$value_altro_terapia = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[ValueAltroTerapie]))); //se è stato cliccato Altro, prendo il valore dalla text

	$compliance_alla_terapia = setCheck($_POST[ComplianceAllaTerapia]);

	//FATTORI DI RISCHIO PER FRATTURA

	//PESO E ALTEZZA LI PRELEVO DALLA TABELLA ScanAnalisys
	$peso = mysqli_real_escape_string($mysqli, setNULL($_POST[Peso]));
	$altezza = mysqli_real_escape_string($mysqli, setNULL($_POST[Altezza]));
	$bmi = mysqli_real_escape_string($mysqli, setNULL($_POST[BMI]));

	//frattura da fragilità vertebrosa
	$frattura_fragilita_vertebrosa = setCheck($_POST[FratturaFragilitaVertebrosa]);
	$vertebre = setEnum($_POST[FratturaVertebre]);
	$femore = setEnum($_POST[FratturaFemore]);

	//fratture da fragilità in siti diversi
	$fratture_siti_diversi = setCheck($_POST[PregresseFratture1]);
	$pregresse_fratture = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[PregresseFratture]))); //se è stata cliccata la check, vedo dove sono avvenute le pregresse fratture dalla text

	//familiarità per frattura
	$familiarita_per_frattura = setCheck($_POST[FamiliaritaperFrattura]);

	//abuso fumo
	$abuso_fumo = setCheck($_POST[Abusofumo1]);
	$quantita_sigarette = setEnum($_POST[Abusofumo]);
	
	//uso cortisone
	$cortisone = setCheck($_POST[Usocortisone1]);
	$uso_cortisone = setEnum($_POST[Usocortisone]);


	//MALATTIE ATTUALI
	$malattie_attuali = setCheck($_POST[Malattieattuali]);
	$artrite = setCheck($_POST[Artrite]);
	$psoriasi = setCheck($_POST[Psoriasi]);
	$lupus = setCheck($_POST[Lupus]);
	$sclerodermia = setCheck($_POST[Sclerodermia]);
	$altre_connettiviti = setCheck($_POST[AltreConnettiviti]);

	//CAUSE SECONDARIE 
	$cause_secondarie = setCheck($_POST[Osteoporosisecondaria]);
	
	if($cause_secondarie == 1){
		$valori_cause_secondarie = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_causesecondarie])); //prendo i valori selezionati dalla textarea
		//$valori_cause_secondarie = rtrim($valori_cause_secondarie,","); //rimuovo la ',' alla fine della stringa
		//$valori_cause_secondarie = trim($valori_cause_secondarie,"\r\n"); //rimuovo le ',' all inizio della stirnga

		//$lines = preg_split('/\r+/', trim($valori_cause_secondarie));
		$lines = preg_replace('/\r\n(\r\n)+/', "\r\n", $valori_cause_secondarie);
		//echo $lines."\n";
		$valori_cause_secondarie = $lines;
		//$valori_cause_secondarie = str_replace("\r", '', $valori_cause_secondarie); // remove carriage returns

		
		
	}
	else $valori_cause_secondarie = '';

	//ABUSO ALCOOL
	$abuso_alcool = setCheck($_POST[Abusoalcool1]);
	$quantita_alcool = setEnum($_POST[Abusoalcool]);
	
	//INFORMAZIONI CLINICHE UTILI PER LA PRESCRIZIONE TERAPEUTICA

	//PATOLOGIE UTERINE
	$patologie_uterine = setCheck($_POST[PatologieUterine]);
	$diagnosi_patologie_uterine = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[diagnosi1]))); //se è stata cliccata la check per patologie uterine, prendo dalla text la diagnosi

	//NEOPLASIA MALIGNA MAMMARIA
	$neoplasia = setCheck($_POST[Neoplasia]);
	$data_neoplasia = setNULL($_POST[data_neoplasia]); // se è stata cliccata prelevo dalla text la data
	
	/*
	if($data_neoplasia != NULL){
		//$data_neoplasia_mysql = date('Y-m-d', strtotime($data_neoplasia));
		//$data_neoplasia = preg_replace('#(\d{4})-(\d{2})-(\d{2}))', '$3-$2-$1', $data_neoplasia);
		$data_neoplasia = str_replace('/', '-', $data_neoplasia);
		$data_neoplasia = date('Y-m-d', strtotime($data_neoplasia));
		$data_neoplasia = "'$data_neoplasia'";
	}else{
		$data_neoplasia = 'NULL';
	}
	*/
	

	$terapia_neoplasia = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[terapia_neoplasia]))); //e la terapia

	//SINTOMI VASOMOTORI PRESENTI
	$sintomi_vasomotori = setCheck($_POST[SintomiVasomotori]);
	$sintomi_distrofici = setCheck($_POST[SintomiDistrofici]);

	//DISLIPIDEMIA 
	$dislipidemia = setCheck($_POST[Dislipidemia]);
	$dislipidemia_terapia = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[dislipidemia_terapia]))); //se è satata cliccata prelevo la terapia dalla text

	//IPERTENSIONE IN TERAPIA
	$ipertensione = setCheck($_POST[Ipertensione]);
	$rischio_tev = setCheck($_POST[RischioTev]);
	$patologia_cardiaca = setCheck($_POST[PatologiaCardiaca]);
	$patologia_vascolare = setCheck($_POST[PatologiaVascolare]);
	$insufficienza_renale = setCheck($_POST[InsufficienzaRenale]);
	$patologia_respiratoria  = setCheck($_POST[PatologiaRespiratoria]);

	//PATOLOGIA DEL CAVO ORALE
	$patologia_del_cavo_orale = setCheck($_POST[PatologiaDelCavoOrale]);
	$terapia_patologia_del_cavo_orale = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[terapia]))); //se è stata cliccata prelevo la terapia

	$patologia_epatica = setCheck($_POST[PatologiaEpatica]);
	$patologia_esofagea = setCheck($_POST[PatologiaEsofagea]);
	$gastro_duodenite = setCheck($_POST[Gastroduodenite]);
	$gastro_resezione = setCheck($_POST[Gastroresezione]);
	$resezione_intestinale = setCheck($_POST[Resezioneintestinale]);
	$mici = setCheck($_POST[mici]);
	$ipovitaminosi = setCheck($_POST[Ipovitaminosi]);
	$valore_ipovitaminosi = mysqli_real_escape_string($mysqli, setNULL($_POST[valore_ipovitaminosi])); //se è stata cliccata la check prelevo dalla text il valore
	$valore_ipovitaminosi = str_replace(',', '.', $valore_ipovitaminosi);
	$altre_patologie = setCheck($_POST[AltroPatologie]);
	$altre_patologie_testo =  mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[altro_patologie])));

	//ALLERGIE/NON ACCETTABILITÀ

	//ALLERGIE
	$allergie = setCheck($_POST[Allergie]);
	$valori_allergie = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[allergie]))); //se è stata cliccata la check, allora prelevo il contenuto della text le allergie inserite

	//INTOLLERANZA
	$intolleranza = setCheck($_POST[Intolleranza]);
	$valori_intolleranze = mysqli_real_escape_string($mysqli, setNULL(strtoupper($_POST[value_intolleranze]))); //se è stata cliccata la check prelevo quello inserito nella text 


	//DENSITOMETRIA PRECEDENTE

	

	$densitometria_precedente = setCheck($_POST[DensitometriaPrecedente]);
	$data_densitometria_precedente = $_POST[data_densitometria_precedente]; //se è stata cliccata prelevo la data della densitometria precedente
	
	if($data_densitometria_precedente != NULL){
		
		$data_densitometria_precedente = str_replace('/', '-', $data_densitometria_precedente);
		$data_densitometria_precedente = date('Y-m-d', strtotime($data_densitometria_precedente));
		$data_densitometria_precedente = "'$data_densitometria_precedente'";
	}
	else{
		$data_densitometria_precedente = 'NULL';
	}

	$densitometria_precedente_interna = setCheck($_POST[densitometria_precedente_interna]);
	//DENSITOMETRIA PRECEDENTE COLONNA LOMBARE
	//VERTEBRE VALUTATE
	$colonna_lombare_L1 = setCheck($_POST[L1]);
	$colonna_lombare_L2 = setCheck($_POST[L2]);
	$colonna_lombare_L3 = setCheck($_POST[L3]);
	$colonna_lombare_L4 = setCheck($_POST[L4]);

	
  	$colonna_lombare_appicabile = setCheck($_POST[colonna_lombare_appicabile]);
	$colonna_lombare_tscore_intero = $_POST[ColonnaLombareTscoreIntero];
	$colonna_lombare_tscore_intero = str_replace(',', '.', $colonna_lombare_tscore_intero);
	if($colonna_lombare_tscore_intero == ""){
		$colonna_lombare_tscore_intero = 'NULL';
	}
	$colonna_lombare_tscore_decimale = $_POST[ColonnaLombareTscoreDecimale];

	if($colonna_lombare_tscore_decimale == ""){
		$colonna_lombare_tscore_decimale = 'NULL';
	}
	$colonna_lombare_zscore_intero = $_POST[ColonnaLombareZscoreIntero];
	$colonna_lombare_zscore_intero = str_replace(',', '.', $colonna_lombare_zscore_intero);
	if($colonna_lombare_zscore_intero == ""){
		$colonna_lombare_zscore_intero = 'NULL';
	}
	$colonna_lombare_zscore_decimale = $_POST[ColonnaLombareZscoreDecimale];
	if($colonna_lombare_zscore_decimale == ""){
		$colonna_lombare_zscore_decimale = 'NULL';
	}

	//DENSITOMETRIA PRECEDENTE FEMORE COLLO
	$femore_collo_posizione = setEnum($_POST[Femore_collo]);
	
	$femore_collo_appicabile = setCheck($_POST[femore_collo_appicabile]);
	$femore_collo_tscore_intero = $_POST[ColloFemoreTscoreIntero];
	$femore_collo_tscore_intero = str_replace(',', '.', $femore_collo_tscore_intero);
	if($femore_collo_tscore_intero == ""){
		$femore_collo_tscore_intero = 'NULL';
	}
	$femore_collo_tscore_decimale = $_POST[ColloFemoreTscoreDecimale];
	if($femore_collo_tscore_decimale == ""){
		$femore_collo_tscore_decimale = 'NULL';
	}
	$femore_collo_zscore_intero = $_POST[ColloFemoreZscoreIntero];
	$femore_collo_zscore_intero = str_replace(',', '.', $femore_collo_zscore_intero);
	if($femore_collo_zscore_intero == ""){
		$femore_collo_zscore_intero = 'NULL';
	}
	$femore_collo_zscore_decimale = $_POST[ColloFemoreZscoreDecimale];
	if($femore_collo_zscore_decimale == ""){
		$femore_collo_zscore_decimale = 'NULL';
	}

	/////////////////////////////////////////////////////////////   FINE DATI REFERTO TIPO B ////////////////////////////////////////////////////////

	/* Ora da qua costruiamo la query per l'inserimento nella tabella. */
	
	//COLONNE TABELLA ANAMNESI
	//(PATIENT_KEY, SCAN_DATE, CITTA_RESIDENZA, VIA_RESIDENZA, TELEFONO, INVIATA_DA, INVIATA_DA_GINECOLOGO, INVIATA_DA_ALTRO_SPECIALISTA, STATO_MENOPAUSALE, ULTIMA_MESTRUAZIONE, ETA_MENOPAUSA, TERAPIA_STATO, TERAPIA_ANNI_SOSPENSIONE, TERAPIA_OSTEOPROTETTIVA_ORMONALE, TERAPIA_OSTEOPROTETTIVA_ORMONALE_LISTA, TERAPIA_OSTEOPROTETTIVA_SPECIFICA, TERAPIA_OSTEOPROTETTIVA_SPECIFICA_LISTA, TERAPIA_ALTRO, PESO, ALTEZZA, BMI, FRATTURA_VERTEBRE, FRATTURA_FEMORE, FRATTURA_SITI_DIVERSI, FRATTURA_SITI_DIVERSI_ALTRO, FRATTURA_FAMILIARITA, ABUSO_FUMO, USO_CORTISONE, MALATTIE_ATTUALI_ARTRITE_REUM, MALATTIE_ATTUALI_ARTRITE_PSOR, MALATTIE_ATTUALI_LUPUS, MALATTIE_ATTUALI_SCLERODERMIA, MALATTIE_ATTUALI_ALTRE_CONNETTIVITI, CAUSE_OSTEOPOROSI_SECONDARIA, ALCOL, PATOLOGIE_UTERINE_DIAGNOSI, NEOPLASIA_MAMMARIA_DATA, NEOPLASIA_MAMMARIA_TERAPIA, SINTOMI_VASOMOTORI, SINTOMI_DISTROFICI, DISLIPIDEMIA_TERAPIA, IPERTENSIONE, RISCHIO_TEV, PATOLOGIA_CARDIACA, PATOLOGIA_VASCOLARE, INSUFFICIENZA_RENALE, PATOLOGIA_RESPIRATORIA, PATOLOGIA_CAVO_ORALE, PATOLOGIA_EPATICA, PAROLOGIA_ESOFAGEA, GASTRO_DUODENITE, GASTRO_RESEZIONE, RESEZIONE_INTESTINALE, MICI, VITAMINA_D, ALLERGIE, INTOLLERANZE, DENSITOMETRIA_PRECEDENTE_DATA, DENSITOMETRIA_PRECEDENTE_INTERNA, VERTEBRE_VALUTATE_L1, VERTEBRE_VALUTATE_L2, VERTEBRE_VALUTATE_L3, VERTEBRE_VALUTATE_L4, COLONNA_T_SCORE, COLONNA_Z_SCORE, FEMORE_LATO, FEMORE_T_SCORE, FEMORE_Z_SCORE)
	$queryInsert = "REPLACE INTO Anamnesi values('$pk', '$datascan_mysql', '$residenza', '$strada', '$via', '$telefono', '$inviata_da', '$ginecologo_del_centro', '$altro_specialista', '$stato_menopausale', $um, $eta_menopausa, '$stato_terapia', $sospesa_da, $ormonale, '$valori_ormonale', $osteoprotettiva, '$valori_osteoprotettiva', $vitamina_d, '$valori_vitamina_d', $altro_terapie, '$value_altro_terapia', $compliance_alla_terapia, $peso, $altezza, $bmi, $frattura_fragilita_vertebrosa, '$vertebre', '$femore', $fratture_siti_diversi, '$pregresse_fratture', $familiarita_per_frattura, $abuso_fumo, '$quantita_sigarette', $cortisone, '$uso_cortisone', $malattie_attuali, $artrite, $psoriasi, $lupus, $sclerodermia, $altre_connettiviti, $cause_secondarie, '$valori_cause_secondarie', $abuso_alcool, '$quantita_alcool', $patologie_uterine, '$diagnosi_patologie_uterine', $neoplasia, $data_neoplasia, '$terapia_neoplasia', $sintomi_vasomotori, $sintomi_distrofici, $dislipidemia, '$dislipidemia_terapia', $ipertensione, $rischio_tev, $patologia_cardiaca, $patologia_vascolare, $insufficienza_renale, $patologia_respiratoria, $patologia_del_cavo_orale, '$terapia_patologia_del_cavo_orale', $patologia_epatica, $patologia_esofagea, $gastro_duodenite, $gastro_resezione, $resezione_intestinale, $mici, $ipovitaminosi, $valore_ipovitaminosi, $altre_patologie, '$altre_patologie_testo', $allergie, '$valori_allergie', $intolleranza, '$valori_intolleranze', $densitometria_precedente, $data_densitometria_precedente, $densitometria_precedente_interna, $colonna_lombare_L1, $colonna_lombare_L2, $colonna_lombare_L3, $colonna_lombare_L4, $colonna_lombare_appicabile, $colonna_lombare_tscore_intero, $colonna_lombare_zscore_intero, '$femore_collo_posizione', $femore_collo_appicabile, $femore_collo_tscore_intero, $femore_collo_zscore_intero)";

	echo $bmi;

	if(DEBUG) echo $queryInsert;
	else if(!mysqli_query($mysqli, $queryInsert)) printMysqlError($mysqli, "queryInsert:");

?>
