<?php

$json = file_get_contents('style-planet.json');
$json = json_decode($json, true);

$idx = 1;

foreach ($json['layers'] as $layer) {
    $fileName = sprintf('layers/%05d-%s.json', $idx * 10, $layer['id']);
    file_put_contents($fileName, json_encode($layer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $idx++;
}
