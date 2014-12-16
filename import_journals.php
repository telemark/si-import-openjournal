<?php
require("config.php");
require("logger.class.php");

require("jfiles.class.php");
require("jimport.class.php");

$jfiles = new JFiles;
$jimport = new JImport;

// Get files in journals folder
$files = $jfiles->getFiles(JOURNALS_FILES);

// Copy files to destination
$import_files = $jfiles->import($files);

foreach ($import_files as $import_file) {
	// Load XML-file as object
	$sxml = $jimport->loadXml($import_file);

	// Create journals array from object
	$result = $jimport->createJournals($sxml);
	if (empty($result)) {
		Logger("INFO", "No journals found in file");
		continue;
	}

	// Debug
	// $jimport->outputJson($result); die();

	// Insert array to mongo
	$db_result = $jimport->mInsert($result);
}
?>
