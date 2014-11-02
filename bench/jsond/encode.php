<?php

$string = @file_get_contents($argv[1]);
if ($string === false) {
    echo json_encode(array('error' => 'fread'));
    exit();
}
$json = json_decode($string);
if ($json === NULL) {
    echo json_encode(array('error' => 'json', 'json_error' => json_last_error()));
    exit();
}
$loops = intval($argv[2]);

$start = microtime(true);
for ($i = 0; $i < $loops; $i++) {
    jsond_decode($string);
}
$end = microtime(true);
echo json_encode(array('time' => $end - $start));
