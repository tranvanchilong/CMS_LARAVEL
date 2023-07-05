<?php

return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 30,
    ],
    'characters' => ['1' ,'2', '3', '4', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'x', 'y', 'z'],
    'default'   => [
        'length'    => 4,
        'width'     => 120,
        'height'    => 38,
        'quality'   => 90,
        'math'      => false,  //Enable Math Captcha
        'expire'    => 60,    //Captcha expiration
        'bgColor' => '#e1e1e1',
        'bgImage' => false,
        'fontColors'=> ['#000000'],
        'blur' => 1,
        'contrast' => -5,
        'lines' => 0,
    ],
    // ...
];
