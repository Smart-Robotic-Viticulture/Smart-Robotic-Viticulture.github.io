<?
require('defines.vws.php');

$filename = $_POST['filename'];
//print_r($data);

$path = STORAGE_ROOT . STORAGE_DIR;

// add extra info
$info = [
	'ActionByIP' => "{$_SERVER['REMOTE_ADDR']} (" . gethostbyaddr($_SERVER['REMOTE_ADDR']) . ")",
	'ActionTime' => date('l jS \of F Y h:i:s A')
];


if(file_exists($filename) && substr($filename, 0, strlen($path)) == $path) {
	$hiddenfilename = $filename . ".hidden";
	file_put_contents($hiddenfilename, json_encode($info, JSON_PRETTY_PRINT));

	// raise dirty flag
	touch('/dev/shm/vwsphotoupload-' . sha1('/home/viti/drive/vws_Android_AppData/'));
	print "ok";
}
?>
