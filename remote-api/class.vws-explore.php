<?php

class VWSExplore
{
	public $data_by_author;

	function __construct($data_dir = "/home/viti/drive/vws_Android_AppData/") {
		$cachefile = '/dev/shm/vwsexplore-' . sha1($data_dir);
		$phototouchfile = '/dev/shm/vwsphotoupload-' . sha1($data_dir);

		if ((file_exists($cachefile)  && time() - filemtime($cachefile) < 1800) && (!file_exists($phototouchfile) || filemtime($cachefile) > filemtime($phototouchfile)) ) {
			$this->data_by_author = unserialize(file_get_contents($cachefile));
		}

		else {
			$this->readdir($data_dir);
			file_put_contents($cachefile, serialize($this->data_by_author));
		}
	}

	function readdir($data_dir) {

		$dirs = scandir($data_dir);

		$all_files = [];

		foreach($dirs as $dir) {
			if($dir == "." || $dir == "..") continue;

			$files = scandir($data_dir . $dir);	

			foreach($files as $file) {
				if($file == "." || $file == "..") continue;

				$all_files[] = "{$data_dir}{$dir}/{$file}";
			}
		}
		//print_r($all_files);

		$all_txt_files = [];
		$all_hidden_files = [];
		foreach($all_files as $file) {
			$parts = pathinfo($file);
			if($parts['extension'] == "txt")
				$all_txt_files[] = $file;
			if($parts['extension'] == "hidden")
				$all_hidden_files[] = str_replace(".hidden", "", $file);
		}

		rsort($all_txt_files);

		//print_r($all_txt_files);

		$data_files = [];
		$data_by_author = [];
		$files_by_author = [];
		foreach($all_txt_files as $file) {

			$obj = [];
			$data = file($file);
			//print_r($data);
			foreach($data as $line) {
				$parts = split(":", $line, 2);
				if(count($parts) == 2) {
					$obj[trim($parts[0])] = trim($parts[1]);
				}
			}

			if(in_array($file, $all_hidden_files)) {
				$obj = [
					'File Name (Original)' => $obj['File Name (Original)'],
					'Hidden' => true,
					'Author' => 'Hidden'
				];
			} 
			//	print_r($obj);
			$obj['File Name (Server)'] = $file;
			$obj['File Size (Exact)'] = filesize(substr($file, 0, -4));

			if(array_key_exists('GPS Position', $obj)) {
				$gpspos = $obj['GPS Position'];
				$matches = [];
				preg_match("/(\d+) deg (\d+)' ([\.\d]+)\\\" ([NS]), (\d+) deg (\d+)' ([\.\d]+)\\\" ([EW])/", $gpspos, $matches);

				$lat = ($matches[1] + ($matches[2]/60) + ($matches[3]/3600)) * ($matches[4] == 'N' ? 1 : -1);
				$lon = ($matches[5] + ($matches[6]/60) + ($matches[7]/3600)) * ($matches[8] == 'E' ? 1 : -1);

				//		preg_match("/(\d+) deg (\d+)' ([\.\d]+)/", $gpspos, $matches);
				//		print_r($matches);
				$obj['GPS Latitude Numeric'] = $lat;
				$obj['GPS Longitude Numeric'] = $lon;
			}


			ksort($obj);
			$data_files[$file] = $obj;

			if(array_key_exists('Author', $obj)) {
				$author = $obj['Author'];
				if(!array_key_exists($author, $files_by_author)) {
					$data_by_author[$author] = [];
					$files_by_author[$author] = [];
				}

				$data_by_author[$author][] = $obj;
				$files_by_author[$author][] = $file;
			}
			$data_by_author['all'][] = $obj;

			//	break;
		}

		//print_r($data_files);
		//print_r($files_by_author);
		//print_r($data_by_author);
		//ksort($data_by_author);
		uksort($data_by_author, array("VWSExplore", "authorCmp"));
		$this->data_by_author = $data_by_author;
	}

	static function authorCmp($a, $b)
	{
		$as = split("; ", $a);
		$bs = split("; ", $b);
		if(count($as) == 1 && count($bs) == 1)
			return $a < $b;
		else if(count($as) == 1)
			return -1;
		else if(count($bs) == 1)
			return 1;
		else {
			$emaila = trim($as[1]);
			$emailb = trim($bs[1]);
			$userNumber = 0;
			if(sscanf($emaila, "vwsbeta%d@gmail.com", $userNumber) == 1) {
				$emaila = "vwsbeta" . sprintf("%02d", $userNumber) . "@gmail.com";
			}
			if(sscanf($emailb, "vwsbeta%d@gmail.com", $userNumber) == 1) {
				$emailb = "vwsbeta" . sprintf("%02d", $userNumber) . "@gmail.com";
			}
			return $emaila > $emailb;
		}
	}

	function getData($id, $tag = '') {
		$selected_data = $this->data_by_author;
		if($id != "all") {
			$selected_data = [$id => $this->data_by_author[$id]];
		}

		if($tag == "id") {
			$selected_data = array_keys($selected_data);
			sort($selected_data);
		}
		return $selected_data;
	}
}

?>
