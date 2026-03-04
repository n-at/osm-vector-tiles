<?php

//Tiles file name without .pmtiles extension
$TILES_FILE_NAME = 'planet'; 

//Style display name
$STYLE_NAME = 'Bright';

//Initial center point
$CENTER = [0, 0];

//Initial zoom level
$ZOOM = 1;

//

$json = [
    'id' => 'bright',
    'name' => $STYLE_NAME,
    'version' => 8,
    'center' => $CENTER,
    'zoom' => $ZOOM,
    'bearing' => 0,
    'pitch' => 0,
    'sources' => [
        'openmaptiles' => [
            'type' => 'vector',
            'url' => "/tiles/{$TILES_FILE_NAME}",
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
