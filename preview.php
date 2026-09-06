<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

require 'config.php';

/*
|--------------------------------------------------------------------------
| VALIDASI
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id'])){

    exit('File not found');

}

$id =
(int) $_GET['id'];

/*
|--------------------------------------------------------------------------
| GET FILE
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| REAL PATH
|--------------------------------------------------------------------------
*/

$path =
$file['filepath'];

if(!file_exists($path)){

    exit('File not found');

}

/*
|--------------------------------------------------------------------------
| EXTENSION
|--------------------------------------------------------------------------
*/

$ext =
strtolower(
    $file['extension']
);

/*
|--------------------------------------------------------------------------
| IMAGE
|--------------------------------------------------------------------------
*/

$imageTypes = [

    'jpg',
    'jpeg',
    'png',
    'gif',
    'webp'

];

if(in_array($ext, $imageTypes)){

    header(
        'Content-Type: image/' . $ext
    );

    readfile($path);

    exit;

}

/*
|--------------------------------------------------------------------------
| PDF
|--------------------------------------------------------------------------
*/

if($ext == 'pdf'){

    header(
        'Content-Type: application/pdf'
    );

    readfile($path);

    exit;

}

/*
|--------------------------------------------------------------------------
| TEXT
|--------------------------------------------------------------------------
*/

if($ext == 'txt'){

    header(
        'Content-Type: text/plain'
    );

    readfile($path);

    exit;

}

/*
|--------------------------------------------------------------------------
| OFFICE FILES
|--------------------------------------------------------------------------
*/

$office = [

    'doc',
    'docx',

    'xls',
    'xlsx',

    'ppt',
    'pptx'

];

if(in_array($ext, $office)){

    echo '

    <div
    style="
    background:#111827;
    color:#fff;
    padding:40px;
    font-family:Arial;
    height:100vh;
    ">

        <h2>

            Preview not supported

        </h2>

        <p>

            Browser cannot preview Office files directly.

        </p>

        <a
        href="download.php?id='

        . $id .

        '"

        style="
        color:#60a5fa;
        font-size:18px;
        ">

            Download File

        </a>

    </div>

    ';

    exit;

}

/*
|--------------------------------------------------------------------------
| UNKNOWN
|--------------------------------------------------------------------------
*/

echo '

<div
style="
background:#111827;
color:#fff;
padding:40px;
font-family:Arial;
height:100vh;
">

    Preview not supported

</div>

';