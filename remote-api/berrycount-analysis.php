<?php
require('defines.berrycount.php');

$photo = $_POST['photo'];
$method = $_POST['method'];
$count = $_POST['count'];
$sf = $_POST['sf'];
$error = $_POST['err'];
//print $photo;

$path = realpath($photo);
$pi = pathinfo($path);
//print $path;
//print_r($pi);

header('Content-Type: application/json');

$ok = false;
$msg = 'Not in storage root.';
if(strpos($pi['dirname'], STORAGE_ROOT) === 0) {

//if(strpos(STORAGE_PATH
	$txt = $path . ".analysis.json";
	$results = ['photo' => $photo, 'method' => $method, 'count' => $count, 'sf' => $sf, 'error' => $error];
	file_put_contents($txt, json_encode($results, JSON_PRETTY_PRINT));
	$ok = true;
	$msg = 'OK';
}

print json_encode(['status' => !$ok, 'msg' => $msg]);

?>
