<?php
require('defines.berrycount.php');
require('class.messageservice.php');

header('Content-Type: application/json');

$ms = new MessageService(STORAGE_ROOT . MESSAGES_DB);
// $ms->clear();
function isJson($string) {
  json_decode($string);
  return (json_last_error() == JSON_ERROR_NONE);
}
if(array_key_exists('verb', $_GET)) {

	switch($_GET['verb']) {
		case 'count':
			print json_encode(['count' => $ms->count()]);
			break;
	}
}
else if(array_key_exists('verb', $_POST)) {
	$topic = array_key_exists('topic', $_POST) ? $_POST['topic'] : '';
	switch($_POST['verb']) { 
		case 'push':
			$message = $_POST['message'];
			$ms->push($topic, $message);
			break;

		case 'pull':
			$msg = $ms->pull($topic);
			if(is_array($msg) && array_key_exists('message', $msg) && isJson($msg['message'])) {
				$msg['message'] = json_decode($msg['message'], true);
			}
			print json_encode($msg);
			break;

		case 'delete':
			$uuid = $_POST['uuid'];
			$ms->delete($uuid);
			print "{}";
			break;

		case 'clear':
			$ms->clear();
			print "{}";
	}
}

?>
