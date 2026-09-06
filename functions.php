<?php

require 'db.php';

function audit($action, $target)
{
    global $pdo;

    $stmt =
    $pdo->prepare(

        "INSERT INTO audit_logs
        (
            action,
            target
        )

        VALUES (?, ?)"

    );

    $stmt->execute([
        $action,
        $target
    ]);
}

function cleanPath($path)
{
    return str_replace(
        ['../', '..\\'],
        '',
        trim($path)
    );
}

function fileHash($file)
{
    return hash_file(
        'sha256',
        $file
    );
}