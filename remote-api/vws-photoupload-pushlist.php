<?
require('class.vws-explore.php');

$vws = new VWSExplore();

$data = $vws->getData('all');

//print_r($_POST);

$clientfilelist = json_decode($_POST['clientfilelist'], true);
//print_r($clientfilelist);

$serverfilelist = [];
foreach($data as $author => $files) {
	foreach($files as $f) {
		$serverfilelist[] = $f['File Name (Original)'];
	}
}

$mode = array_key_exists('mode', $_POST) ? $_POST['mode'] : "exists"; 

$output = [];
foreach($clientfilelist as $f) {
	if($mode == "exists") {
		if(in_array($f, $serverfilelist))
			$output[] = $f;
	}
}
print json_encode($output);
//print_r($data);

?>

