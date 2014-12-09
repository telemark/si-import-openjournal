<?php

class JImport {

	var $journal_date;
	var $journal_dir;

	// Load xml-file
	function loadXml($file) {
		Logger("INFO", "Loading xml: $file");
		$sarray = simplexml_load_file($file) or Logger("ERR", "Cannot create object from XML");
		return $sarray;
	}

	// Insert to mongo
	function mInsert($array) {
		try{
		$m = new MongoClient();
		Logger("INFO", "Connecting to database");

		// Use database
		$db = $m->tfk;

		// Select collection
		$collection = $db->journals;

		// Removing all journals with given journaldate
		$qry = array('JOURNPOST_OJ.JP_JDATO' => $this->journal_date);
		$collection->remove($qry);
		Logger("INFO", "Removing journals with same journaldate");

		// Insert array to database
		$collection->batchInsert($array);
		Logger("INFO", "Inserting journals to database");

		}catch(MongoException $mongoException){
		        Logger("ERR", $mongoException);
		        exit;
		}
	}

	// Create case array
	function createCase($xml_case) {
		$case['sakId'] = (string) $xml_case->{'SA.SAAR'} . "_" . (string) $xml_case->{'SA.SEKNR'};
		Logger("INFO", "Found case $case[sakId]");
		$case['SA_SAAR'] = (int) $xml_case->{'SA.SAAR'};
		$case['SA_SEKNR'] = (int) $xml_case->{'SA.SEKNR'};
		$case['SA_SAKNR'] = (string) $xml_case->{'SA.SAKNR'};
		$case['SA_OFFTITTEL'] = (string) $xml_case->{'SA.OFFTITTEL'};
		$case['SA_ADMKORT'] = (string) $xml_case->{'SA.ADMKORT'};
		$case['SA_ANSVINIT'] = (string) $xml_case->{'SA.ANSVINIT'};
		return $case;
	}

	// Create Classification array
	function createClassifiction($xml_class) {

		$classification['KL_ORDNVERDI'] = (string) $xml_class->{'KL.ORDNVERDI'};
		$classification['KL_SORT'] = (int) $xml_class->{'KL.SORT'};
		$classification['KL_OPLTEKST'] = (string) $xml_class->{'KL.OPLTEKST'};
		return $classification;
	}

	// Create journalpost array
	function createJournalPost($xml_journalpost) {
		$xml_jpost = $xml_journalpost->{'JOURNPOST.OJ'};
		$jpos['JP_JAAR'] = (int) $xml_jpost->{'JP.JAAR'};
		$jpost['JP_SEKNR'] = (int) $xml_jpost->{'JP.SEKNR'};
                $jpost['JP_DOKNR'] = (string) $xml_jpost->{'JP.DOKNR'};
		$jpost['JP_POSTNR'] = (int) $xml_jpost->{'JP.POSTNR'};
		$jpost['JP_JDATO'] = (int) $xml_jpost->{'JP.JDATO'};
		$jpost['JP_NDOKTYPE'] = (string) $xml_jpost->{'JP.NDOKTYPE'};
		$jpost['JP_DOKDATO'] = (int) $xml_jpost->{'JP.DOKDATO'};
		$jpost['JP_OFFINNHOLD'] = (string) $xml_jpost->{'JP.OFFINNHOLD'};
		$jpost['JP_TGKODE'] = (string) $xml_jpost->{'JP.TGKODE'};
		$jpost['JP_TGVERDI'] = (int) $xml_jpost->{'JP.TGVERDI'};
		$jpost['JP_ANSVAVD'] = (string) $xml_jpost->{'JP.ANSVAVD'};
                $jpost['AVSMOT_OJ']['AM_NAVN'] = (string) $xml_jpost->{'AVSMOT.OJ'}->{'AM.NAVN'};
                $jpost['AVSMOT_OJ']['AM_IHTYPE'] = (int) $xml_jpost->{'AVSMOT.OJ'}->{'AM.IHTYPE'};
		return $jpost;
	}

	// Create document array
	function createDocument($xml_doc) {
		$doc['DL_RNR'] = (int) $xml_doc->{'DL.RNR'};
		$doc['DL_DOKID'] = (int) $xml_doc->{'DL.DOKID'};
		$doc['DL_TYPE'] = (string) $xml_doc->{'DL.TYPE'};
		$doc['DOKBESKRIV_OJ']['DB_TITTEL'] = (string) $xml_doc->{'DOKBESKRIV.OJ'}->{'DB.TITTEL'};
		$doc['DOKBESKRIV_OJ']['DB_TGKODE'] = (string) $xml_doc->{'DOKBESKRIV.OJ'}->{'DB.TGKODE'};
		$doc['DOKBESKRIV_OJ']['DB_TGVERDI'] = (int) $xml_doc->{'DOKBESKRIV.OJ'}->{'DB.TGVERDI'};
		$doc['DOKBESKRIV_OJ']['DOKVERSJON_OJ']['VE_VERSJON'] = (int) $xml_doc->{'DOKBESKRIV.OJ'}->{'DOKVERSJON.OJ'}->{'VE.VERSJON'};
		$doc['DOKBESKRIV_OJ']['DOKVERSJON_OJ']['VE_VARIANT'] = (string) $xml_doc->{'DOKBESKRIV.OJ'}->{'DOKVERSJON.OJ'}->{'VE.VARIANT'};
		$doc['DOKBESKRIV_OJ']['DOKVERSJON_OJ']['VE_FILREF'] = (string) $xml_doc->{'DOKBESKRIV.OJ'}->{'DOKVERSJON.OJ'}->{'VE.FILREF'};
		$doc['DOKBESKRIV_OJ']['DOKVERSJON_OJ']['VE_FILURL'] = (string) JOURNALS_DOWNLOAD_URL . $this->journal_dir . $xml_doc->{'DOKBESKRIV.OJ'}->{'DOKVERSJON.OJ'}->{'VE.FILREF'};
		return $doc;
	}

	// Create journals array
	function createJournals($xml) {
		if (!isset($xml->RAPPORT->{'NOARKSAK.OJ'}->{'JOURNPOST.OJ'}->{'JP.JDATO'})) {
			 Logger("ERR", "No journals found in file");
		}
		$this->journal_date = (int) $xml->RAPPORT->{'NOARKSAK.OJ'}->{'JOURNPOST.OJ'}->{'JP.JDATO'};
		$this->journal_dir = (string) basename($xml->PRODINFO->FIL->{'PI.FILNAVN'}, ".xml") . "/";
		Logger("INFO", "Journal date: $this->journal_date");

		// Loop through cases
		foreach ($xml->RAPPORT->{'NOARKSAK.OJ'} as $case) {
			$journal = $this->createCase($case);

			// Loop through classifications
			foreach($case->{'KLASSERING.OJ'} as $classification) {
				$journal['KLASSERING_OJ'][] = $this->createClassifiction($classification);;
			}

			// Sets journals info
			$journal['JOURNPOST_OJ'] = $this->createJournalPost($case);

			// Loop through documents
			foreach ($case->{'JOURNPOST.OJ'}->{'DOKLINK.OJ'} as $document) {
				$journal['JOURNPOST_OJ']['JP_DOKUMENTER'][] = $this->createDocument($document);
			}

			// Ugly hack creates JOURNPOST_OJ empty array when no documents are found
			if (empty($journal['JOURNPOST_OJ']['JP_DOKUMENTER'])) {
				$journal['JOURNPOST_OJ']['JP_DOKUMENTER'] = array();
			}
			// Feed result with cases
			$result[] = $journal;

			// Unset case
			unset($journal);
		}
		Logger("INFO", "End of file");

		return $result;
	}


	// Output to json for debugging
	function outputJson($array) {
		$json =json_encode($array, JSON_PRETTY_PRINT);
		echo $json;
		die();
	}

}
?>
