<?php
require('class.messageservice.php');

$db = new MessageService();
// $db->clear();

print_r($db->count());
print "\n";
// test
$db->push('t', 'aaa');
$db->push('t', 'bbb');
$db->push('t', 'ccc');
print_r($db->count());
print "\n";

$a = $db->pull('t');
$b = $db->pull('t');
$c = $db->pull('t');

print_r($db->count());
print "\n";

print_r($a);
print_r($b);
print_r($c);

print "\n";
$db->delete($a['uuid']);
$db->delete($b['uuid']);
$db->delete($c['uuid']);
print_r($db->count());

print "\n";
?>
