<?php

require 'functions.php';

$name =
cleanPath(
    $_POST['name']
);

$path =
$ROOT_PATH . '/' . $name;

if(file_exists($path)){

    unlink($path);

    $stmt =
    $pdo->prepare(

        "DELETE FROM files
        WHERE filepath=?"

    );

    $stmt->execute([
        $path
    ]);

    audit(
        'DELETE_FILE',
        $path
    );

    echo 'File deleted';

}else{

    echo 'File not found';

}