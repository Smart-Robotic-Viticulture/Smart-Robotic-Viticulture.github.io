<?
require('defines.vws.php');

$info = json_decode($_POST['info'], true);
//print_r($data);

$path = STORAGE_ROOT . STORAGE_DIR . '/deviceinfo/';
if(!file_exists($path)) {
	mkdir($path, 0664);
}

// add extra info
$info['SyncIP'] = "{$_SERVER['REMOTE_ADDR']} (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")";


$userid = preg_replace("/[^\da-z]/i", '_', $info['Author']);
$filename = $path . $userid . ".json";
file_put_contents($filename, json_encode($info, JSON_PRETTY_PRINT));
?>

