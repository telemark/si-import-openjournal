<?php
function Logger($e, $message) {
	if ($e == "ERR") {
		echo "\033[31m *** ERROR *** \e[0m" . $message . "\n";
		die();
	} else {
		echo "\033[32m *** INFO *** \e[0m" . $message . "\n";
	}
}
?>
