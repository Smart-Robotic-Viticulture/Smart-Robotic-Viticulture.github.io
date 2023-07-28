<?php
require('class.vws-explore.php');
$id = $_GET['id'];

$vws = new VWSExplore();
$selected_data = $vws->getData($id);

$json = json_encode($selected_data);
//$json = json_encode($selected_data, JSON_PRETTY_PRINT);

header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60)) . ' GMT');
header("Content-type: application/json");
ob_start('ob_gzhandler');
print_r($json);
?>
