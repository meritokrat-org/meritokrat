<?php

$pdo = new PDO('pgsql:host=meritokrat-db;dbname=meritokrat', 'meritokrat', 'RRcylF0M');

$statement = $pdo->query('SELECT * FROM user_data WHERE contacts != \'\';');
if (!$statement->execute()) {
    throw new Exception('Error');
}

while ($row = $statement->fetch()) {
    $contacts = unserialize($row['contacts']);
    $handledContacts = [];

    if (is_array($contacts)) {
        array_walk($contacts, function (&$value, $key) use (&$handledContacts) {
            if (!in_array($key, [3, 7, 4, 5, 6]) || $value === '') {
                return;
            }
            $handledContacts[$key] = $value;
        });
    } else {
        var_dump('Not array ', $row['user_id']);
        echo PHP_EOL;
    }

    $handledContacts = !empty($handledContacts) ? serialize($handledContacts) : '';
    $result = $pdo
        ->prepare('UPDATE user_data SET contacts = :contacts WHERE user_id = :user_id')
        ->execute([
            'user_id' => $row['user_id'],
            'contacts' => $handledContacts,
        ]);
    if (!$result) {
        var_dump($row['user_id'], $pdo->errorInfo());
        echo PHP_EOL;
    }
    print_r($handledContacts);
    echo PHP_EOL;
}