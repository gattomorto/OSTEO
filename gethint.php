<?php

// get the q parameter from URL
$q = $_POST["q"];

$hint = "";
//echo $q;
// lookup all hints from array if $q is different from ""

$command = escapeshellcmd('/home/kkk/PycharmProjects/OstPy/venv/bin/python /home/kkk/PycharmProjects/OstPy/main.py '."'".$q."'");
$output = shell_exec($command);
echo $output;
?>