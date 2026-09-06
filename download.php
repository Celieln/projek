<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

if(!isset($_GET['id'])){

    exit('File not found');

}

$id =
(int) $_GET['id'];

$stmt =
$pdo->prepare(

    "SELECT *
    FROM files
    WHERE id=?"

);

$stmt->execute([
    $id
]);

$file =
$stmt->fetch(
    PDO::FETCH_ASSOC
);

if(!$file){

    exit('File not found');

}

$path =
$file['filepath'];

if(!file_exists($path)){

    exit('File not found');

}

header(
    'Content-Description: File Transfer'
);

header(
    'Content-Type: application/octet-stream'
);

header(
    'Content-Disposition: attachment; filename="' .
    basename($path) .
    '"'
);

header(
    'Content-Length: ' .
    filesize($path)
);

readfile($path);