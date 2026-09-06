<?php

require 'functions.php';

$old =
cleanPath(
    $_POST['old']
);

$new =
cleanPath(
    $_POST['new']
);

$oldPath =
$ROOT_PATH . '/' . $old;

$newPath =
$ROOT_PATH . '/' . $new;

if(rename($oldPath, $newPath)){

    $stmt =
    $pdo->prepare(

        "UPDATE folders

        SET

        folder_name=?,
        folder_path=?

        WHERE folder_path=?"

    );

    $stmt->execute([
        $new,
        $newPath,
        $oldPath
    ]);

    audit(
        'RENAME_FOLDER',
        $oldPath
    );

    echo 'Folder renamed';

}else{

    echo 'Rename failed';

}