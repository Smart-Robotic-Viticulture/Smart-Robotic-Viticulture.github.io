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
if(count($matches)) {
	header("Matched: true");
	header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60*60*24*45)) . ' GMT');
	readfile(str_replace('.jpg.txt','.jpg.thumb.jpg',$path));
}
?>
