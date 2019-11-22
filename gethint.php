<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// get the q parameter from URL
$pk = $_POST["pk"];
$datascan = $_POST["datascan"];


$command = escapeshellcmd('python3.7 /home/dadawg/PycharmProjects/untitled1/main.py '.$pk.' '.$datascan.' 2>&1');
$output = shell_exec($command);
echo $output;

/*//$command = 'python3.7 /home/dadawg/PycharmProjects/untitled1/main.py '.$pk.' '.$datascan;
$command = 'python3.7 /home/dadawg/PycharmProjects/untitled1/main.py 2>&1';
exec($command, $output, $status);
print_r($output);
echo 'return:';
print_r($status);*/


?>