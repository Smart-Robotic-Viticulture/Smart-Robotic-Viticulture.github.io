<?php
header('Content-type: image/jpeg');
$url = $_GET['url'];
//print $url;
header("URL: {$url}");
$path = realpath($url);
//print $path;
$pi = pathinfo($path);
//print_r($pi);
$matches = [];
preg_match('/^\/home\/viti\/drive\/vws_Android_AppData\/(\d+)$/', $pi['dirname'], $matches);

$type = array_key_exists('type', $_GET) ? $_GET['type'] : 'jpg';

if(count($matches)) {
	header("Matched: true");
	switch($type) {
		case 'jpg': 
		case 'rgb': 
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60*60*24*45)) . ' GMT'); 
			break;
		case 'meta': 
		case 'thumb': 
			header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60*60*4)) . ' GMT'); break;
	}

	switch($type) {
		case 'jpg': $img = str_replace('.jpg.txt','.jpg',$path); break;
		case 'rgb': $img = str_replace('.jpg.txt','.jpg.rgb.jpg',$path); break;
		case 'meta': $img = $path; break;
		case 'thumb': $img = str_replace('.jpg.txt','.jpg.thumb.jpg',$path); break;
		default: die();
	}

	header('Content-disposition: attachment; filename="' . $matches[1] .'_' . basename($img) . '"');
	readfile($img);
}
?>
