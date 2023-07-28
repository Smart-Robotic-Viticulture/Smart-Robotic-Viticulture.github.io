<?php
require('class.vws-devices.php');
$id = $_GET['id'];

$vws = new VWSDevices();
$selected_data = $vws->getData($id);

$json = json_encode($selected_data, JSON_PRETTY_PRINT);

header('Expires: ' . gmdate('D, d M Y H:i:s', time() + (60)) . ' GMT');
header("Content-type: application/json");
ob_start('ob_gzhandler');
//print_r(
print $json;
?>
