<?php

class JFiles {

	function listFiles($folder) {
		$src_files = array_diff(scandir($folder), array("..", "."));
		if (empty($src_files)) {
			Logger("ERR", "No journals found in $folder");
		}
		return $src_files;
	}

	// List filnames in folder to array
	function getFiles($folder) {
		if (!file_exists($folder)) {
			Logger("ERR", "Folder not found");
		}
		$src_files = $this->listFiles($folder);
		return $src_files;
	}

	// Create destination directory for zip-file
	function destDir($src_file) {
		$dir = basename($src_file, ".zip");
		return $dir;
	}

	function delFile($src_path) {
		unlink($src_path);
	}

	// Unzip file
	function unzip($source, $destination) {
		$zip = new ZipArchive;

		$res = $zip->open($source);
	        if ($res === TRUE) {
        	        $zip->extractTo($destination);
                	$zip->close();
                	$this->delFile($source);
	                Logger("INFO", "File: $source extracted to $destination");
			return true;
	        } else {
        	        Logger("ERR", "$source not extracted: $res");
			return false;
        	}
	}

	/* Extract files to destination */
	function import($files) {
		foreach ($files as $file) {
			$dest_dir = $this->destDir($file);
			$src_path = JOURNALS_FILES . $file;
			$dest_path = DOWNLOAD_PATH . $this->destDir($file);
			$this->unzip($src_path, $dest_path);
			$res[] = $dest_path . "/journals.xml";
		}
		return $res;
	}
}
