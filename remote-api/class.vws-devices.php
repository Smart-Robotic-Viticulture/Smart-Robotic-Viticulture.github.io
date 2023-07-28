<?php

class VWSDevices
{
	public $data_by_author;
	public $data_by_email;

	function __construct($data_dir = "/home/viti/drive/vws_Android_AppData/deviceinfo/") {
		$cachefile = '/dev/shm/vwsdevices-' . sha1($data_dir);

//		if ((file_exists($cachefile)  && time() - filemtime($cachefile) < 60))  {
//			$this->data_by_author = unserialize(file_get_contents($cachefile));
//		}

//		else {
			$this->readdir($data_dir);
//			file_put_contents($cachefile, serialize($this->data_by_author));
//		}
	}

	function readdir($data_dir) {

		$files = scandir($data_dir);

		$all_files = [];

		foreach($files as $file) {
			if($file == "." || $file == "..") continue;

			$all_files[] = "{$data_dir}{$file}";
		}
		//print_r($all_files);

		$all_txt_files = [];
		$all_hidden_files = [];
		foreach($all_files as $file) {
			$parts = pathinfo($file);
			if($parts['extension'] == "json")
				$all_txt_files[] = $file;
			if($parts['extension'] == "hidden")
				$all_hidden_files[] = str_replace(".hidden", "", $file);
		}

		rsort($all_txt_files);

		//print_r($all_txt_files);

		$data_files = [];
		$data_by_author = [];
		$data_by_email = [];
		$files_by_author = [];
		foreach($all_txt_files as $file) {

			if(in_array($file, $all_hidden_files))
				continue;

			$obj = json_decode(file_get_contents($file), true);

			$obj['LastUpdate'] = date('Y-m-d\TH:i:s', filemtime($file));

			//ksort($obj);
			$data_files[$file] = $obj;

			if(array_key_exists('Author', $obj)) {
				$author = $obj['Author'];

				// skip google test accounts
				if(substr($author, -strlen("cloudtestlabaccounts.com")) === "cloudtestlabaccounts.com")
					continue;

				$email = split('; ', $author);
				$email = trim($email[1]);

				$userNumber = 0;
				if(sscanf($email, "vwsbeta%d", $userNumber) == 1) {
					$email = "vwsbeta" . sprintf("%02d", $userNumber);
				}

				if(!array_key_exists($author, $files_by_author)) {
					$data_by_email[$email] = [];
					$data_by_author[$author] = [];
					$files_by_author[$author] = [];
				}

				$data_by_author[$author] = $obj;
				$data_by_email[$email] = $obj;
				$files_by_author[$author] = $file;
			}
			$data_by_author['all'][] = $obj;
			$data_by_email['all'][] = $obj;

			//	break;
		}

		//print_r($data_files);
		//print_r($files_by_author);
		//print_r($data_by_author);
		ksort($data_by_author);
		ksort($data_by_email);
		$this->data_by_author = $data_by_author;
		$this->data_by_email = $data_by_email;
	}

	function getData($id, $tag = '') {
		//$selected_data = $this->data_by_author;
		$selected_data = $this->data_by_email;
		if($id != "all") {
			$selected_data = [$id => $this->data_by_email[$id]];
		} else {
			unset($selected_data['all']);
		}

		if($tag == "id") {
			$selected_data = array_keys($selected_data);
			sort($selected_data);
		}
		return $selected_data;
	}
}

?>
