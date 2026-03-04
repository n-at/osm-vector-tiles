<?php

$json = [
    'id' => 'bright',
    'name' => 'Bright',
    'version' => 10,
    'center' => [0, 0],
    'zoom' => 1,
    'bearing' => 0,
    'pitch' => 0,
    'sources' => [
        'openmaptiles' => [
            'type' => 'vector',
            'url' => '/tiles/planet',
        ],
    ],
    'sprite' => '/tiles/sprite/glyphs',
    'glyphs' => '/tiles/font/{fontstack}/{range}',
    'metadata' => [],
    'layers' => [],
];

//

$metadata = file_get_contents('metadata.json');
$metadata = json_decode($metadata, true);
$json['metadata'] = $metadata;

//

$layers = scandir('layers');
foreach ($layers as $layerFileName) {
    if (is_dir($layerFileName) || substr($layerFileName, 0, 1) == '_') {
        continue;
    }
    $layer = file_get_contents("layers/{$layerFileName}");
    $layer = json_decode($layer, true);
    $json['layers'][] = $layer;
}

//

file_put_contents('../web/style.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
