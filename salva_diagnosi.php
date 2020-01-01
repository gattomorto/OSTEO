<?php
	include_once './lib/inserimento_prelievo.php';
	$mysqli = new mysqli('localhost', 'utente_web', 'CMOREL96T45', 'CMO'); 
	
	// Recupero PATIENT_KET e SCAN_DATE
	$pk = $_POST[pk];
	$datascan_mysql = $_POST[datascan];

	// Situazione colonna, collo femore.
	$colonna_vertebrale_check = setCheck($_POST[colonna_vertebrale]);
	$situazione_colonna = setEnum($_POST[situazione_colonna]);
	

	if(strcmp($situazione_colonna, "Situazione di normalità")==0)
		$situazione_colonna = setEnum("Situazione di normalita");

	
	$collo_femore_sn_check = setCheck($_POST[collo_femore_sn]);
	$situazione_femore_sn = setEnum($_POST[situazione_femore_sn]);
	
	
	if(strcmp($situazione_femore_sn, "Situazione di normalità")==0)
		$situazione_femore_sn = setEnum("Situazione di normalita");

	$collo_femore_dx_check = setCheck($_POST[collo_femore_dx]);
	
	$situazione_femore_dx = setEnum($_POST[situazione_femore_dx]);
	
	if(strcmp($situazione_femore_dx, "Situazione di normalità")==0)
		$situazione_femore_dx = "Situazione di normalita";

	$osteoporosi_grave = setCheck($_POST[OsteoporosiGrave]);

	// Dettaglio
	$vertebre_non_analizzate = setCheck($_POST[ColonnaVertebraleParzialmenteNonAnalizzabile]);
	$vertebre_non_analizzate_L1 = setCheck($_POST[Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L1]);
	$vertebre_non_analizzate_L2 = setCheck($_POST[Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L2]);
	$vertebre_non_analizzate_L3 = setCheck($_POST[Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L3]);
	$vertebre_non_analizzate_L4 = setCheck($_POST[Colonna_Vertebrale_Parzialmente_NonAnalizzabile_L4]);
	$colonna_non_analizzabile = setCheck($_POST[colonnavertebralenonanalizzabile]);
	$colonna_valori_superiori = setCheck($_POST[colonnavertebraleconvalori]);
	$femore_non_analizzabile = setCheck($_POST[femorenonanalizzabile]);

	// Stima del Rischio di Frattura a 10 anni.
	$frax_applicabile = setCheck($_POST[Frax_applicabile]);
	$frax_percentuale = setCheck($_POST[percentuale_frax]);
	$frax_fratture_maggiori_intero = mysqli_real_escape_string($mysqli, setNULL($_POST[frax_fratture_maggiori_intero]));
	$frax_fratture_maggiori_intero = str_replace(',', '.', $frax_fratture_maggiori_intero);

	//$frax_fratture_maggiori_decimale = mysqli_real_escape_string($mysqli, setNULL($_POST[frax_fratture_maggiori_decimale]));
	
	$frax_collo_femore_percentuale = setCheck($_POST[percentuale_collo_femore]);
	$frax_collo_femore_intero = mysqli_real_escape_string($mysqli, setNULL($_POST[collo_femore_intero]));
	$frax_collo_femore_intero = str_replace(',', '.', $frax_collo_femore_intero);
	//$frax_collo_femore_decimale = mysqli_real_escape_string($mysqli, setNULL($_POST[collo_femore_decimale]));
	$defra_applicabile = setCheck($_POST[defra_applicabile]);
	$defra_percentuale_01 = setCheck($_POST[defra_percentuale_01]);
	$defra_percentuale_50 = setCheck($_POST[defra_percentuale_50]);
	$defra_intero = mysqli_real_escape_string($mysqli, setNULL($_POST[defra_intero]));
	$defra_intero = str_replace(',', '.', $defra_intero);
	$defra_intero = number_format((float)$defra_intero, 2);
	//$defra_decimale = mysqli_real_escape_string($mysqli, setNULL($_POST[defra_decimale]));

	// Indicazioni di prevenzione e terapia.
	$terapie_ormonali_check = setCheck($_POST[TerapieOrmonali]);
	if($terapie_ormonali_check == 1){// PER ANTONIO: DA GESTIRE!
		$terapie_ormonali = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_terapie_ormonali])); //Se è stata selezionata come terapia Ormonale, prelevo i valori dalla textarea
		//echo $terapie_ormonali;
		$terapie_ormonali = rtrim($terapie_ormonali,","); //rimuovo la ',' alla fine della stringa
		$terapie_ormonali = trim($terapie_ormonali,","); //rimuovo le ',' all inizio della stirnga

		$terapie_ormonali = preg_replace('/,+/', ',', $terapie_ormonali); //espressione regolare per eliminare più di una ','

	}
	else $terapie_ormonali = '';


	$terapie_osteoprotettive_check = setCheck($_POST[TerapieOsteoprotettive]);

	if($terapie_osteoprotettive_check == 1){// PER ANTONIO: DA GESTIRE!
		$terapie_osteoprotettive = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_terapie_osteoprotettive])); //Se è stata selezionata come terapia Ormonale, prelevo i valori dalla textarea
		$terapie_osteoprotettive = rtrim($terapie_osteoprotettive,","); //rimuovo la ',' alla fine della stringa
		$terapie_osteoprotettive = trim($terapie_osteoprotettive,","); //rimuovo le ',' all inizio della stirnga

		$terapie_osteoprotettive = preg_replace('/,+/', ',', $terapie_osteoprotettive); //espressione regolare per eliminare più di una ','
	}
	else $terapie_osteoprotettive = 'NULL';

	$vitamina_d_terapia_check = setCheck($_POST[VitaminaDTerapia]);
	if($vitamina_d_terapia_check == 1){
		// ANTONIO.
		$vitamina_d_terapia = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_vitamina_d_terapia])); //Se è stata selezionata come terapia Ormonale, prelevo i valori dalla textarea
		//echo $terapie_ormonali;
		$vitamina_d_terapia = rtrim($vitamina_d_terapia,","); //rimuovo la ',' alla fine della stringa
		$vitamina_d_terapia = trim($vitamina_d_terapia,","); //rimuovo le ',' all inizio della stirnga

		$vitamina_d_terapia = preg_replace('/,+/', ',', $vitamina_d_terapia); //espressione regolare per eliminare più di una ','
	}
	else $vitamina_d_terapia = 'NULL';

	$vitamina_d_supplementazione_check = setCheck($_POST[VitaminaDSupplementazione]);
	if($vitamina_d_supplementazione_check == 1){
		//ANTONIO
		$vitamina_d_supplementazione = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_vitamina_d_supplementazione])); //Se è stata selezionata come terapia Ormonale, prelevo i valori dalla textarea
		//echo $terapie_ormonali;
		$vitamina_d_supplementazione = rtrim($vitamina_d_supplementazione,","); //rimuovo la ',' alla fine della stringa
		$vitamina_d_supplementazione = trim($vitamina_d_supplementazione,","); //rimuovo le ',' all inizio della stirnga

		$vitamina_d_supplementazione = preg_replace('/,+/', ',', $vitamina_d_supplementazione); //espressione regolare per eliminare più di una ','
	}
	else $vitamina_d_supplementazione = 'NULL';

	$calcio_supplementazione_check = setCheck($_POST[CalcioSupplementazione]);
	$calcio_supplementazione = setList($mysqli, $_POST[valori_calcio_supplementazione], $calcio_supplementazione_check);

	$nota_79_applicabile = setCheck($_POST[nota_79_applicabile]);
	$nota_79_prescrizione = setCheck($_POST[Prescrizione]);
	$norme_prevenzione = setCheck($_POST[norme_prevenzione]);
	$norme_comportamentali = setCheck($_POST[norme_comportamentali]);
	$attivita_fisica = setCheck($_POST[attivita_fisica]);
	$sospensione_terapia_check = setCheck($_POST[sospensione_terapia]);
	$sospensione_terapia_farmaco = mysqli_real_escape_string($mysqli, setNULL($_POST[Farmaco]));
	$sospensione_terapia_mesi = mysqli_real_escape_string($mysqli, setNULL($_POST[Mesi]));
	
	$altro_check = setCheck($_POST[Altro]);
	$altro = mysqli_real_escape_string($mysqli, setNULL($_POST[value_altro]));
	
	$indagini_approfondimento_check = setCheck($_POST[Indagini_di_approfondimento]);
	
	
	if($indagini_approfondimento_check == 1){
		//ANTONIO
		$indagini_approfondimento = mysqli_real_escape_string($mysqli, setNULL($_POST[valori_indagini_approfondimento]));	
		 //Se è stata selezionata come terapia Ormonale, prelevo i valori dalla textarea
		
		$indagini_approfondimento = rtrim($indagini_approfondimento,","); //rimuovo la ',' alla fine della stringa
		$indagini_approfondimento = trim($indagini_approfondimento,","); //rimuovo le ',' all inizio della stirnga
		//$indagini_approfondimento = trim($indagini_approfondimento,",");

		$indagini_approfondimento = preg_replace('/,+/', ',', $indagini_approfondimento); //espressione regolare per eliminare più di una ','
		
		//$indagini_approfondimento = preg_replace("/[\n\r]/", "", $indagini_approfondimento);
		//$indagini_approfondimento = preg_replace("/[\r\n]/", "", $indagini_approfondimento);
		//$indagini_approfondimento = preg_replace("/[\n]/", "", $indagini_approfondimento);
	}
	else $indagini_approfondimento = 'NULL';

	$sospensione_fumo = setCheck($_POST[sospensione_fumo]);

	// Controlli suggeriti
	$controllo_densitometrico_check = setCheck($_POST[colloquio_densitometrico]);
	$controllo_densitometrico_mesi = mysqli_real_escape_string($mysqli, setNULL($_POST[colloquio_densitometrico_mesi]));
	$controllo_interno = setCheck($_POST[colloquio_presso_il_centro]);
	$controllo_interno_valutazioni = setCheck($_POST[Valutazioni_esame]);
	$controllo_interno_attivazione_piano = setCheck($_POST[Attivazione_piano_terapeutico]);
	$controllo_interno_clinico = setCheck($_POST[Controllo_clinico]);
	
	
  	// FRAX aggiustato per TBS e TBS colonna, ultime modifiche del 31/10/2018
    $frax_aggiustato_applicabile = setCheck($_POST[Frax_aggiustato_applicabile]);
    $frax_aggiustato_percentuale = setCheck($_POST[percentuale_frax_aggiustato]);
    $frax_aggiustato_valore = mysqli_real_escape_string($mysqli, setNULL($_POST[frax_aggiustato_valore]));
    $frax_aggiustato_valore = str_replace(',', '.', $frax_aggiustato_valore);
    $frax_aggiustato_valore = number_format((float)$frax_aggiustato_valore, 2);

    $frax_collo_femore_aggiustato_percentuale = setCheck($_POST[percentuale_collo_femore_aggiustato]);
    $frax_collo_femore_aggiustato_valore = mysqli_real_escape_string($mysqli, setNULL($_POST[collo_femore_aggiustato_valore]));
    $frax_collo_femore_aggiustato_valore = str_replace(',', '.', $frax_collo_femore_aggiustato_valore);
    $frax_collo_femore_aggiustato_valore = number_format((float)$frax_collo_femore_aggiustato_valore, 2);

    $tbs_colonna_applicabile = setCheck($_POST[tbs_colonna_applicabile]);
    $percentuale_tbs_colonna = setCheck($_POST[percentuale_tbs_colonna]);
	$tbs_colonna_valore = mysqli_real_escape_string($mysqli, setNULL($_POST[tbs_colonna_valore]));
	$tbs_colonna_valore = str_replace(',', '.', $tbs_colonna_valore);
	$tbs_colonna_valore = number_format((float)$tbs_colonna_valore, 3);
	$VALUTAZIONE_INTEGRATA = setCheck($_POST[VALUTAZIONE_INTEGRATA]);	


	// Costruisco la query per l'inserimento.
	$queryInsert = "REPLACE INTO Diagnosi VALUES('$pk', '$datascan_mysql', $colonna_vertebrale_check, '$situazione_colonna', $collo_femore_sn_check, '$situazione_femore_sn', $collo_femore_dx_check, '$situazione_femore_dx', $osteoporosi_grave, $vertebre_non_analizzate, $vertebre_non_analizzate_L1, $vertebre_non_analizzate_L2, $vertebre_non_analizzate_L3, $vertebre_non_analizzate_L4, $colonna_non_analizzabile, $colonna_valori_superiori, $femore_non_analizzabile, $frax_applicabile, $frax_percentuale, $frax_fratture_maggiori_intero, $frax_collo_femore_percentuale, $frax_collo_femore_intero, $defra_applicabile, $defra_percentuale_01, $defra_percentuale_50, '$defra_intero',  $terapie_ormonali_check, '$terapie_ormonali', $terapie_osteoprotettive_check, '$terapie_osteoprotettive', $vitamina_d_terapia_check, '$vitamina_d_terapia', $vitamina_d_supplementazione_check, '$vitamina_d_supplementazione', $calcio_supplementazione_check, '$calcio_supplementazione', $nota_79_applicabile, $norme_prevenzione, $nota_79_prescrizione, $norme_comportamentali, $attivita_fisica, $sospensione_terapia_check, '$sospensione_terapia_farmaco', $sospensione_terapia_mesi, $altro_check, '$altro', $indagini_approfondimento_check, '$indagini_approfondimento', $sospensione_fumo, $controllo_densitometrico_check, $controllo_densitometrico_mesi, $controllo_interno, $controllo_interno_valutazioni, $controllo_interno_attivazione_piano, $controllo_interno_clinico, $frax_aggiustato_applicabile, $frax_aggiustato_percentuale, $frax_aggiustato_valore, $frax_collo_femore_aggiustato_percentuale, $frax_collo_femore_aggiustato_valore, $tbs_colonna_applicabile, $percentuale_tbs_colonna, $tbs_colonna_valore, $VALUTAZIONE_INTEGRATA)";

	if(DEBUG) echo "$queryInsert\n\n";
	else if(!mysqli_query($mysqli, $queryInsert)) printMysqlError($mysqli, "queryInsert:");
?>
