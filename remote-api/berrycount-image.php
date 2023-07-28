<?php
require('defines.berrycount.php');

header('Content-type: image/jpeg');

$url = $_GET['url'];
//print $url;
header("URL: {$url}");

$path = realpath($url);
//print $path;
$pi = pathinfo($path);
//print_r($pi);

//print STORAGE_ROOT;

if(strpos($pi['dirname'], STORAGE_ROOT) === 0) {

	header("Matched: true");
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60*60*24*45)) . ' GMT');
	readfile(str_replace('.jpg','.jpg',$path));
}
?>
