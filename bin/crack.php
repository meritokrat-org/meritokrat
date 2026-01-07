<?php

$cfg = [
    'driver' => 'pgsql',
    'host' => 'meritokrat-db',
    'user' => 'meritokrat',
    'password' => 'RRcylF0M',
    'dbname' => 'meritokrat',
];

$pdo = new Pdo(
    sprintf('%s:dbname=%s;host=%s', $cfg['driver'], $cfg['dbname'], $cfg['host']),
    $cfg['user'],
    $cfg['password']
);


$email = $argv[1];

$sql = "select * from user_auth where email like '%{$email}%'";
// echo $sql.PHP_EOL;die;
$stmt = $pdo->query($sql);
$stmt->execute();

$rows = $stmt->fetchAll();

print_r($rows);
