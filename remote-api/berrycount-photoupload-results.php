<?
require('defines.berrycount.php');
require('class.vws-explore.php');

$vws = new VWSExplore(STORAGE_ROOT . STORAGE_DIR);

$data = $vws->getData('all');
//print_r($_POST);

$clientfilelist = json_decode($_POST['clientfilelist'], true);
//print_r($clientfilelist);

$serverfilelist = [];
$fileinfo = [];
foreach($data as $author => $files) {
	foreach($files as $f) {
		$client_filename = $f['File Name (Original)'];
		$serverfilelist[] = $client_filename;
		$fileinfo[$client_filename] = $f;
	}
}

$mode = array_key_exists('mode', $_POST) ? $_POST['mode'] : "exists"; 

$output = [];
foreach($clientfilelist as $f) {
	if($mode == "exists") {
		$info = ['photo' => $f, 'count' => -1, 'sf' => -1, 'err' => '', 'method' => ''];

		if(in_array($f, $serverfilelist)) {
			$server_filename = "{$fileinfo[$f]['Directory']}/{$fileinfo[$f]['File Name']}";
			$txt = $server_filename . '.analysis.json';

			if(file_exists($txt)) {

				$analysis = json_decode(file_get_contents($txt), true);
				$info['count'] = $analysis['count'];
				$info['sf'] = $analysis['sf'];
				$info['err'] = $analysis['error'];
				$info['method'] = $analysis['method'];

				if($info['err'])
					$info['err'] = 'Analysis error.';
				
			} else {
				$info['err'] = 'Analysis results not available.';
			}
		} else {
			$info['err'] = 'Source image not available. ' . $server_filename;
		}
		$output[] = $info;
	}
}
print json_encode($output);
//print_r($data);

?>

