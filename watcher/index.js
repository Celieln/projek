const chokidar =
require('chokidar');

const watcher =
chokidar.watch(
    'D:/LPK_DATA',
    {
        persistent:true
    }
);

watcher.on(
    'add',
    path => {

        console.log(
            'FILE ADDED:',
            path
        );

    }
);

watcher.on(
    'unlink',
    path => {

        console.log(
            'FILE REMOVED:',
            path
        );

    }
);

watcher.on(
    'addDir',
    path => {

        console.log(
            'FOLDER CREATED:',
            path
        );

    }
);

watcher.on(
    'unlinkDir',
    path => {

        console.log(
            'FOLDER REMOVED:',
            path
        );

    }
);