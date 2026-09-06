<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'functions.php';

/*
|--------------------------------------------------------------------------
| JSON RESPONSE
|--------------------------------------------------------------------------
*/

header(
    'Content-Type: application/json'
);

/*
|--------------------------------------------------------------------------
| RESPONSE FUNCTION
|--------------------------------------------------------------------------
*/

function response($status, $message, $data = [])
{
    echo json_encode([

        'status' => $status,

        'message' => $message,

        'data' => $data

    ]);

    exit;
}

/*
|--------------------------------------------------------------------------
| VALIDASI FILE
|--------------------------------------------------------------------------
*/

if(!isset($_FILES['file'])){

    response(
        false,
        'No file uploaded'
    );

}

if(!isset($_POST['folder'])){

    response(
        false,
        'No folder selected'
    );

}

/*
|--------------------------------------------------------------------------
| FORCE
|--------------------------------------------------------------------------
*/

$force =
$_POST['force'] ?? '0';

/*
|--------------------------------------------------------------------------
| GET DATA
|--------------------------------------------------------------------------
*/

$file =
$_FILES['file'];

$folder =
trim(
    $_POST['folder']
);

/*
|--------------------------------------------------------------------------
| NORMALIZE FOLDER
|--------------------------------------------------------------------------
*/

$folder =
str_replace(
    '\\',
    '/',
    $folder
);

$folder =
trim(
    $folder,
    '/'
);

$folder =
cleanPath(
    $folder
);

/*
|--------------------------------------------------------------------------
| SECURITY
|--------------------------------------------------------------------------
*/

if(

    preg_match(
        '/(\.\.)/',
        $folder
    )

){

    response(
        false,
        'Invalid folder path'
    );

}

/*
|--------------------------------------------------------------------------
| FILE DATA
|--------------------------------------------------------------------------
*/

$originalName =
basename(
    $file['name']
);

$tmp =
$file['tmp_name'];

$size =
$file['size'];

$error =
$file['error'];

/*
|--------------------------------------------------------------------------
| ERROR CHECK
|--------------------------------------------------------------------------
*/

if($error !== 0){

    response(
        false,
        'Upload failed'
    );

}

/*
|--------------------------------------------------------------------------
| SIZE LIMIT
|--------------------------------------------------------------------------
*/

$maxSize =
50 * 1024 * 1024;

if($size > $maxSize){

    response(
        false,
        'File too large'
    );

}

/*
|--------------------------------------------------------------------------
| TARGET FOLDER
|--------------------------------------------------------------------------
*/

$targetFolder =
$ROOT_PATH
. '/'
. $folder;

/*
|--------------------------------------------------------------------------
| FOLDER EXISTS
|--------------------------------------------------------------------------
*/

if(!file_exists($targetFolder)){

    response(
        false,
        'Folder not found'
    );

}

/*
|--------------------------------------------------------------------------
| EXTENSION
|--------------------------------------------------------------------------
*/

$ext =
strtolower(

    pathinfo(
        $originalName,
        PATHINFO_EXTENSION
    )

);

/*
|--------------------------------------------------------------------------
| ALLOWED EXTENSION
|--------------------------------------------------------------------------
*/

$allowed = [

    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp',

    'pdf',

    'doc',
    'docx',

    'xls',
    'xlsx',

    'ppt',
    'pptx',

    'txt'

];

if(!in_array($ext, $allowed)){

    response(
        false,
        'File type not allowed'
    );

}

/*
|--------------------------------------------------------------------------
| MIME VALIDATION
|--------------------------------------------------------------------------
*/

$finfo =
finfo_open(
    FILEINFO_MIME_TYPE
);

$mime =
finfo_file(
    $finfo,
    $tmp
);

finfo_close($finfo);

$allowedMime = [

    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',

    'application/pdf',

    'application/msword',

    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

    'application/vnd.ms-excel',

    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

    'application/vnd.ms-powerpoint',

    'application/vnd.openxmlformats-officedocument.presentationml.presentation',

    'text/plain'

];

if(!in_array($mime, $allowedMime)){

    response(
        false,
        'Invalid file mime type'
    );

}

/*
|--------------------------------------------------------------------------
| FILE HASH
|--------------------------------------------------------------------------
*/

$hash =
hash_file(
    'sha256',
    $tmp
);

/*
|--------------------------------------------------------------------------
| DUPLICATE CHECK
|--------------------------------------------------------------------------
*/

$check =
$pdo->prepare(

    "SELECT *
    FROM files

    WHERE

    filehash=?

    AND

    filepath LIKE ?"

);

$check->execute([

    $hash,

    str_replace(
        '\\',
        '/',
        $targetFolder
    ) . '%'

]);

/*
|--------------------------------------------------------------------------
| DUPLICATE DETECTED
|--------------------------------------------------------------------------
*/

if(

    $check->rowCount() > 0

    &&

    $force != '1'

){

    response(
        'duplicate',
        'Duplicate detected'
    );

}

/*
|--------------------------------------------------------------------------
| CLEAN FILENAME
|--------------------------------------------------------------------------
*/

$cleanName =
preg_replace(

    '/[^a-zA-Z0-9_\-.]/',

    '_',

    pathinfo(
        $originalName,
        PATHINFO_FILENAME
    )

);

/*
|--------------------------------------------------------------------------
| EMPTY NAME
|--------------------------------------------------------------------------
*/

if($cleanName == ''){

    $cleanName =
    'file';

}

/*
|--------------------------------------------------------------------------
| VERSIONING
|--------------------------------------------------------------------------
*/

$filename =
$cleanName;

$counter = 1;

while(

    file_exists(

        $targetFolder
        . '/'
        . $filename
        . '.'
        . $ext

    )

){

    $counter++;

    $filename =

    $cleanName

    . '_v'

    . $counter;

}

/*
|--------------------------------------------------------------------------
| FINAL FILE
|--------------------------------------------------------------------------
*/

$finalName =

$filename
. '.'
. $ext;

/*
|--------------------------------------------------------------------------
| TARGET FILE
|--------------------------------------------------------------------------
*/

$target =

$targetFolder
. '/'
. $finalName;

/*
|--------------------------------------------------------------------------
| MOVE FILE
|--------------------------------------------------------------------------
*/

if(

    !move_uploaded_file(
        $tmp,
        $target
    )

){

    response(
        false,
        'Failed move uploaded file'
    );

}

/*
|--------------------------------------------------------------------------
| FILESIZE
|--------------------------------------------------------------------------
*/

$fileSize =
filesize(
    $target
);

/*
|--------------------------------------------------------------------------
| NORMALIZE PATH
|--------------------------------------------------------------------------
*/

$dbPath =
str_replace(
    '\\',
    '/',
    $target
);

/*
|--------------------------------------------------------------------------
| INSERT DATABASE
|--------------------------------------------------------------------------
*/

$stmt =
$pdo->prepare(

    "INSERT INTO files
    (
        filename,
        filepath,
        filesize,
        extension,
        filehash
    )

    VALUES (?, ?, ?, ?, ?)"

);

$stmt->execute([

    $finalName,

    $dbPath,

    $fileSize,

    $ext,

    $hash

]);

/*
|--------------------------------------------------------------------------
| AUDIT LOG
|--------------------------------------------------------------------------
*/

audit(
    'UPLOAD_FILE',
    $dbPath
);

/*
|--------------------------------------------------------------------------
| SUCCESS
|--------------------------------------------------------------------------
*/

response(

    true,

    'Upload success',

    [

        'filename' =>
        $finalName,

        'path' =>
        $dbPath,

        'size' =>
        $fileSize,

        'extension' =>
        $ext

    ]

);