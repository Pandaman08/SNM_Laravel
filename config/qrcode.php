<?php

return [
    'renderer' => 'gd', // Cambiar de 'imagick' a 'gd'
    'merge' => 'gd',    // Cambiar de 'imagick' a 'gd'
    'format' => 'png',
    'size' => 300,
    'margin' => 1,
    'errorCorrection' => 'H',
    'encoding' => 'UTF-8',
    'colors' => [
        'background' => [255, 255, 255, 0],
        'foreground' => [0, 0, 0, 0],
    ],
    'style' => 'square',
    'eye' => 'square',
];