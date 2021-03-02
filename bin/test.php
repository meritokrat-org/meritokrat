<?php

$tests = [
    'common:1',
    'common:1',
    'region:13',
    'status:5',
    'status:10',
    'political_views:10,45,3',
];

$predicate = static function ($string) {
    if (!preg_match('/(\w+):([\d,]+)/', $string, $matches)) {
        return null;
    }

    return $matches;
};

$output = array_map($predicate, $tests);

print_r($output);