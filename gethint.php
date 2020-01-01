<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$pk = $_POST["pk"];
$datascan = $_POST["datascan"];

$command = escapeshellcmd("python3.7 /home/dadawg/PycharmProjects/untitled1/main.py '".$pk."' '".$datascan."'");

$output = shell_exec($command);
echo $output;

?>