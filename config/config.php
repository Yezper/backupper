<?php

return [
    'app' => [
        'logging' => [
            'file_name' => __DIR__ . '/../backup.log',
            'lines_to_keep' => 10,
        ],
    ],

    'sources' => [
         [
             'job_name' => 'Docker volumes',
             'source_folder' => '/var/lib/docker/volumes',
             'destination_folder' => '/mnt/backup',
             'destination_file_name' => 'docker_volumes',
             'days_to_keep' => 7,
         ],
//        [
//            'job_name' => 'JNI - Documents',
//            'source_folder' => '/Users/jni/Documents',
//            'destination_folder' => '/Users/jni/Development/backupper/backups',
//            'destination_file_name' => 'documents',
//            'days_to_keep' => 7,
//        ],
    ],
];