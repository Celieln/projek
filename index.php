<!DOCTYPE html>
<html>

<head>

    <title>LPK File Manager</title>

    <meta charset="UTF-8">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{

            background:
            linear-gradient(
                135deg,
                #0f172a,
                #111827
            );

            color:#fff;

            font-family:
            Arial;

            min-height:100vh;

            padding:25px;

        }

        .navbar{

            background:
            rgba(255,255,255,0.05);

            border:
            1px solid rgba(255,255,255,0.08);

            backdrop-filter:
            blur(10px);

            padding:20px;

            border-radius:18px;

            margin-bottom:20px;

            display:flex;

            justify-content:space-between;

            align-items:center;

        }

        .logo{

            font-size:22px;

            font-weight:bold;

        }

        .grid{

            display:grid;

            grid-template-columns:
            340px 1fr;

            gap:20px;

        }

        .card{

            background:
            rgba(255,255,255,0.05);

            border:
            1px solid rgba(255,255,255,0.08);

            backdrop-filter:
            blur(10px);

            border-radius:18px;

            padding:20px;

            margin-bottom:20px;

        }

        h3{

            margin-bottom:15px;

            font-size:18px;

        }

        input,
        select{

            width:100%;

            background:
            rgba(255,255,255,0.04);

            border:
            1px solid rgba(255,255,255,0.08);

            color:#fff;

            padding:13px;

            border-radius:12px;

            margin-bottom:12px;

            outline:none;

        }

        input::placeholder{

            color:#aaa;

        }

        button{

            background:
            #2563eb;

            color:#fff;

            border:none;

            padding:12px 16px;

            border-radius:10px;

            cursor:pointer;

            transition:0.2s;

            font-weight:bold;

        }

        button:hover{

            opacity:0.8;

        }

        .danger{

            background:#dc2626;

        }

        .file,
        .folder{

            background:
            rgba(255,255,255,0.04);

            border:
            1px solid rgba(255,255,255,0.05);

            border-radius:14px;

            padding:15px;

            margin-bottom:12px;

            display:flex;

            justify-content:space-between;

            align-items:flex-start;

            gap:15px;

        }

        .file:hover,
        .folder:hover{

            background:
            rgba(255,255,255,0.08);

        }

        .actions{

            display:flex;

            gap:10px;

            flex-shrink:0;

        }

        .empty{

            opacity:0.5;

            text-align:center;

            padding:20px;

        }

        .top-grid{

            display:grid;

            grid-template-columns:
            repeat(2,1fr);

            gap:20px;

            margin-bottom:20px;

        }

        .stats{

            font-size:30px;

            font-weight:bold;

            margin-top:10px;

        }

        #dropZone{

            padding:30px;

            border:
            2px dashed rgba(255,255,255,0.2);

            border-radius:15px;

            text-align:center;

            transition:0.2s;

            margin-bottom:15px;

        }

        #dropZone.drag{

            background:
            rgba(37,99,235,0.2);

            border-color:
            #2563eb;

        }

        .file-info{

            width:100%;

            overflow:hidden;

        }

        .filename{

            font-weight:bold;

            white-space:nowrap;

            overflow:hidden;

            text-overflow:ellipsis;

            width:100%;

        }

        .meta{

            opacity:0.6;

            margin-top:5px;

            font-size:13px;

        }

        iframe{

            width:100%;
            height:100%;
            border:none;
            background:#fff;

        }

        #previewModal{

            display:none;

            position:fixed;

            top:0;
            left:0;

            width:100%;
            height:100%;

            background:
            rgba(0,0,0,0.8);

            z-index:999;

            padding:30px;

        }

        .preview-content{

            background:#111827;

            height:100%;

            border-radius:18px;

            overflow:hidden;

        }

        .preview-header{

            padding:15px;

            display:flex;

            justify-content:space-between;

            align-items:center;

            border-bottom:
            1px solid rgba(255,255,255,0.08);

        }

        .recent-item{

            padding:12px;

            border-radius:10px;

            background:
            rgba(255,255,255,0.03);

            margin-bottom:10px;

            overflow:hidden;

            text-overflow:ellipsis;

            white-space:nowrap;

        }

    </style>

</head>

<body>

    <div class="navbar">

        <div class="logo">

            📁 LPK File Manager

        </div>

        <div>

            Enterprise Internal System

        </div>

    </div>

    <div class="top-grid">

        <div class="card">

            <div>

                Storage Usage

            </div>

            <div
            class="stats"
            id="storageStats">

                Loading...

            </div>

        </div>

        <div class="card">

            <div>

                Total Files

            </div>

            <div
            class="stats"
            id="totalFiles">

                0

            </div>

        </div>

    </div>

    <div class="grid">

        <div>

            <div class="card">

                <h3>Create Folder</h3>

                <input
                type="text"
                id="folderName"
                placeholder="Example: siswa/dirly/passport">

                <button
                onclick="createFolder()">

                    Create Folder

                </button>

            </div>

            <div class="card">

                <h3>Upload File</h3>

                <select id="uploadFolder"></select>

                <div id="dropZone">

                    Drag & Drop Files Here

                </div>

                <input
                multiple
                type="file"
                id="fileInput">

                <button
                onclick="uploadFile()">

                    Upload Files

                </button>

            </div>

            <div class="card">

                <h3>Recent Files</h3>

                <div id="recentFiles"></div>

            </div>

        </div>

        <div>

            <div class="card">

                <h3>Search File</h3>

                <input
                type="text"
                id="searchInput"
                placeholder="Search file..."
                onkeyup="searchFile()">

            </div>

            <div class="card">

                <h3>Folders</h3>

                <div id="folderList"></div>

            </div>

            <div class="card">

                <h3>Files</h3>

                <div id="fileList"></div>

            </div>

        </div>

    </div>

    <div id="previewModal">

        <div class="preview-content">

            <div class="preview-header">

                <div>

                    File Preview

                </div>

                <button
                onclick="closePreview()">

                    Close

                </button>

            </div>

            <iframe
            id="previewFrame">

            </iframe>

        </div>

    </div>

    <script>

        async function createFolder(){

            let folderName =
            document.getElementById(
                'folderName'
            ).value;

            if(folderName == ''){

                alert('Folder name empty');

                return;

            }

            let formData =
            new FormData();

            formData.append(
                'name',
                folderName
            );

            let response =
            await fetch(
                'create-folder.php',
                {
                    method:'POST',
                    body:formData
                }
            );

            let result =
            await response.text();

            alert(result);

            document.getElementById(
                'folderName'
            ).value = '';

            loadFolders();

            loadFolderOptions();

        }

        async function uploadFile(){

            let files =
            document.getElementById(
                'fileInput'
            ).files;

            let folder =
            document.getElementById(
                'uploadFolder'
            ).value;

            if(files.length == 0){

                alert('Choose file');

                return;

            }

            let uploaded = 0;

            let skipped = 0;

            for(let file of files){

                let formData =
                new FormData();

                formData.append(
                    'file',
                    file
                );

                formData.append(
                    'folder',
                    folder
                );

                formData.append(
                    'force',
                    '0'
                );

                let response =
                await fetch(

                    'upload.php',

                    {
                        method:'POST',
                        body:formData
                    }

                );

                let result =
                await response.text();

                if(result == 'DUPLICATE'){

                    let confirmUpload =
                    confirm(

                        'Duplicate file detected in this folder.\n\nUpload as new version?'

                    );

                    if(confirmUpload){

                        let retryForm =
                        new FormData();

                        retryForm.append(
                            'file',
                            file
                        );

                        retryForm.append(
                            'folder',
                            folder
                        );

                        retryForm.append(
                            'force',
                            '1'
                        );

                        let retry =
                        await fetch(

                            'upload.php',

                            {
                                method:'POST',
                                body:retryForm
                            }

                        );

                        let retryResult =
                        await retry.text();

                        console.log(
                            retryResult
                        );

                        uploaded++;

                    }

                    else{

                        skipped++;

                    }

                }

                else{

                    uploaded++;

                }

            }

            alert(

                'Upload Finished\n\n'

                +

                'Uploaded: '

                +

                uploaded

                +

                '\nSkipped: '

                +

                skipped

            );

            loadFiles();

            loadRecentFiles();

            loadStorage();

        }

        async function loadFolders(){

    let response =
    await fetch(
        'get-folders.php'
    );

    let folders =
    await response.json();

    let html = '';

    if(folders.length == 0){

        html =
        `<div class="empty">

            No folders

        </div>`;

    }

    folders.forEach(folder => {

        /*
        |--------------------------------------------------------------------------
        | DEPTH
        |--------------------------------------------------------------------------
        */

        let depth =
        folder.depth || 0;

        /*
        |--------------------------------------------------------------------------
        | INDENT
        |--------------------------------------------------------------------------
        */

        let padding =
        depth * 25;

        html += `

        <div
        class="folder"
        style="
        margin-left:${padding}px;
        ">

            <div
            style="
            width:100%;
            overflow:hidden;
            ">

                <div
                style="
                font-weight:bold;
                white-space:nowrap;
                overflow:hidden;
                text-overflow:ellipsis;
                ">

                    📁 ${folder.folder_name}

                </div>

                <div
                style="
                opacity:0.5;
                font-size:12px;
                margin-top:5px;
                white-space:nowrap;
                overflow:hidden;
                text-overflow:ellipsis;
                ">

                    ${folder.folder_path}

                </div>

            </div>

            <div class="actions">

                <button
                onclick="setParentFolder(
                    '${folder.folder_path}'
                )">

                    Open

                </button>

                <button
                class="danger"
                onclick="deleteFolder(
                    '${folder.folder_path}'
                )">

                    Delete

                </button>

            </div>

        </div>

        `;

    });

    document.getElementById(
        'folderList'
    ).innerHTML = html;

}

        async function loadFolderOptions(){

    let response =
    await fetch(
        'get-folders.php'
    );

    let folders =
    await response.json();

    let html = '';

    folders.forEach(folder => {

        /*
        |--------------------------------------------------------------------------
        | DEPTH
        |--------------------------------------------------------------------------
        */

        let depth =
        folder.depth || 0;

        /*
        |--------------------------------------------------------------------------
        | INDENT
        |--------------------------------------------------------------------------
        */

        let indent = '';

        for(let i = 0; i < depth; i++){

            indent += '— ';

        }

        html += `

        <option
        value="${folder.folder_path}">

            ${indent}
            ${folder.folder_name}

        </option>

        `;

    });

    document.getElementById(
        'uploadFolder'
    ).innerHTML = html;

}

        async function loadFiles(){

            let response =
            await fetch(
                'search.php'
            );

            let files =
            await response.json();

            renderFiles(files);

            document.getElementById(
                'totalFiles'
            ).innerHTML =
            files.length;

        }

        function renderFiles(files){

            let html = '';

            if(files.length == 0){

                html =
                `<div class="empty">

                    No files

                </div>`;

            }

            files.forEach(file => {

                let normalizedPath =
                file.filepath.replaceAll(
                    '\\\\',
                    '/'
                );

                let parts =
                normalizedPath.split('/');

                let folderName =
                parts[parts.length - 2] || 'ROOT';

                let ext =
                file.extension.toLowerCase();

                let icon = '📄';

                if(
                    [
                        'jpg',
                        'jpeg',
                        'png',
                        'gif',
                        'webp'
                    ].includes(ext)
                ){
                    icon = '🖼️';
                }

                else if(ext == 'pdf'){
                    icon = '📕';
                }

                else if(
                    [
                        'xls',
                        'xlsx'
                    ].includes(ext)
                ){
                    icon = '📗';
                }

                else if(
                    [
                        'doc',
                        'docx'
                    ].includes(ext)
                ){
                    icon = '📘';
                }

                else if(
                    [
                        'ppt',
                        'pptx'
                    ].includes(ext)
                ){
                    icon = '📙';
                }

                html += `

                <div class="file">

                    <div
                    style="
                    display:flex;
                    gap:15px;
                    width:100%;
                    overflow:hidden;
                    ">

                        <div
                        style="
                        font-size:30px;
                        min-width:40px;
                        ">

                            ${icon}

                        </div>

                        <div class="file-info">

                            <div class="filename">

                                ${file.filename}

                            </div>

                            <div class="meta">

                                📁 Folder:
                                ${folderName}

                            </div>

                            <div class="meta">

                                📦 ${formatBytes(
                                    file.filesize
                                )}

                            </div>

                        </div>

                    </div>

                    <div class="actions">

                        <button
                        onclick="previewFile(
                            '${file.id}'
                        )">

                            Preview

                        </button>

                        <button
                        class="danger"
                        onclick="deleteFile(
                            '${file.filename}'
                        )">

                            Delete

                        </button>

                    </div>

                </div>

                `;

            });

            document.getElementById(
                'fileList'
            ).innerHTML = html;

        }

        async function searchFile(){

            let keyword =
            document.getElementById(
                'searchInput'
            ).value;

            let response =
            await fetch(
                'search.php?keyword='
                + keyword
            );

            let files =
            await response.json();

            renderFiles(files);

        }

        async function deleteFolder(name){

            if(!confirm(
                'Delete folder?'
            )) return;

            let formData =
            new FormData();

            formData.append(
                'name',
                name
            );

            let response =
            await fetch(
                'delete-folder.php',
                {
                    method:'POST',
                    body:formData
                }
            );

            let result =
            await response.text();

            alert(result);

            loadFolders();

            loadFolderOptions();

        }

        async function deleteFile(name){

            if(!confirm(
                'Delete file?'
            )) return;

            let formData =
            new FormData();

            formData.append(
                'name',
                name
            );

            let response =
            await fetch(
                'delete-file.php',
                {
                    method:'POST',
                    body:formData
                }
            );

            let result =
            await response.text();

            alert(result);

            loadFiles();

            loadRecentFiles();

            loadStorage();

        }

        function previewFile(id){

            document.getElementById(
                'previewModal'
            ).style.display = 'block';

            document.getElementById(
                'previewFrame'
            ).src =
            'preview.php?id=' + id;

        }

        function closePreview(){

            document.getElementById(
                'previewModal'
            ).style.display = 'none';

        }

        async function loadRecentFiles(){

            let response =
            await fetch(
                'search.php'
            );

            let files =
            await response.json();

            let html = '';

            files.slice(0,5).forEach(file => {

                html += `

                <div class="recent-item">

                    📄 ${file.filename}

                </div>

                `;

            });

            document.getElementById(
                'recentFiles'
            ).innerHTML = html;

        }

        async function loadStorage(){

            let response =
            await fetch(
                'storage.php'
            );

            let result =
            await response.text();

            document.getElementById(
                'storageStats'
            ).innerHTML = result;

        }

        function formatBytes(bytes){

            if(bytes >= 1073741824){

                return (
                    bytes / 1073741824
                ).toFixed(2) + ' GB';

            }

            if(bytes >= 1048576){

                return (
                    bytes / 1048576
                ).toFixed(2) + ' MB';

            }

            if(bytes >= 1024){

                return (
                    bytes / 1024
                ).toFixed(2) + ' KB';

            }

            return bytes + ' B';

        }

        const dropZone =
        document.getElementById(
            'dropZone'
        );

        dropZone.addEventListener(
            'dragover',
            e => {

                e.preventDefault();

                dropZone.classList.add(
                    'drag'
                );

            }
        );

        dropZone.addEventListener(
            'dragleave',
            () => {

                dropZone.classList.remove(
                    'drag'
                );

            }
        );
function setParentFolder(path){

    /*
    |--------------------------------------------------------------------------
    | AUTO SELECT UPLOAD
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'uploadFolder'
    ).value = path;

    /*
    |--------------------------------------------------------------------------
    | AUTO FILL CREATE
    |--------------------------------------------------------------------------
    */

    document.getElementById(
        'folderName'
    ).value =
    path + '/';

}
        dropZone.addEventListener(

            'drop',

            async e => {

                e.preventDefault();

                dropZone.classList.remove(
                    'drag'
                );

                let files =
                e.dataTransfer.files;

                let folder =
                document.getElementById(
                    'uploadFolder'
                ).value;

                let uploaded = 0;

                let skipped = 0;

                for(let file of files){

                    let formData =
                    new FormData();

                    formData.append(
                        'file',
                        file
                    );

                    formData.append(
                        'folder',
                        folder
                    );

                    formData.append(
                        'force',
                        '0'
                    );

                    let response =
                    await fetch(

                        'upload.php',

                        {
                            method:'POST',
                            body:formData
                        }

                    );

                    let result =
                    await response.text();

                    if(result == 'DUPLICATE'){

                        let confirmUpload =
                        confirm(

                            'Duplicate detected.\n\nUpload as new version?'

                        );

                        if(confirmUpload){

                            let retryForm =
                            new FormData();

                            retryForm.append(
                                'file',
                                file
                            );

                            retryForm.append(
                                'folder',
                                folder
                            );

                            retryForm.append(
                                'force',
                                '1'
                            );

                            await fetch(

                                'upload.php',

                                {
                                    method:'POST',
                                    body:retryForm
                                }

                            );

                            uploaded++;

                        }

                        else{

                            skipped++;

                        }

                    }

                    else{

                        uploaded++;

                    }

                }

                alert(

                    'Upload Finished\n\n'

                    +

                    'Uploaded: '

                    +

                    uploaded

                    +

                    '\nSkipped: '

                    +

                    skipped

                );

                loadFiles();

                loadRecentFiles();

                loadStorage();

            }

        );

        loadFolders();

        loadFiles();

        loadFolderOptions();

        loadRecentFiles();

        loadStorage();

    </script>

</body>
</html>