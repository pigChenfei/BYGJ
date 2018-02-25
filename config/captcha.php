<?php

return [

    'characters' => '012346789',//'02346789abcdefghjmnpqrtuxyzABCDEFGHJMNPQRTUXYZ'

    'default'   => [
        'length'    => 4,
        'width'     => 60,
        'height'    => 33,
        'quality'   => 90,
        'bgImage'   => false,
        'bgColor'   => '#000000',
        'fontColors'=> ['#fff'],
        'lines'=> -1,
    ],

    'flat'   => [
        'length'    => 6,
        'width'     => 160,
        'height'    => 46,
        'quality'   => 90,
        'lines'     => 6,
        'bgImage'   => false,
        'bgColor'   => '#000000',
        'fontColors'=> ['#2c3e50', '#c0392b', '#16a085', '#c0392b',     '#8e44ad', '#303f9f', '#f57c00', '#795548'],
        'contrast'  => -5,
    ],

    'mini'   => [
        'length'    => 3,
        'width'     => 60,
        'height'    => 32,
    ],

    'inverse'   => [
        'length'    => 5,
        'width'     => 110,
        'height'    => 30,
        'quality'   => 90,
        'sensitive' => true,
        'angle'     => 12,
        'sharpen'   => 10,
        'blur'      => 2,
        'invert'    => true,
        'contrast'  => -5,
    ]

];
