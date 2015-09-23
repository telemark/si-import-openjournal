<?php
/* Basic settings */

// Display all errors
ini_set("display_errors", 1);
error_reporting(E_ALL);

// Database
define('DB_NAME', 'tfk');

// URLS
define('DOWNLOAD_URL', 'https://files.t-fk.no/');

// Paths
define('DOWNLOAD_PATH', '/srv/ws/files/journals/');

/* Application spesific settings */

// 360 Journals
define('JOURNALS_COLLECTION', 'journals');
define('JOURNALS_FILES', '/mnt/journals/');
define('JOURNALS_DOWNLOAD_URL', DOWNLOAD_URL . 'journals/');

?>
