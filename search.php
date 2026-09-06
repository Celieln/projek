<?php

require 'db.php';

$keyword =
$_GET['keyword']
?? '';

$stmt =
$pdo->prepare(

    "SELECT *
    FROM files

    WHERE filename
    LIKE ?

    ORDER BY id DESC"

);

$stmt->execute([
    "%$keyword%"
]);

$data =
$stmt->fetchAll(
    PDO::FETCH_ASSOC
);

header(
    'Content-Type: application/json'
);

echo json_encode($data);