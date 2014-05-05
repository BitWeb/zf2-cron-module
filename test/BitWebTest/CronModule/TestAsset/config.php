<?php

return [
    'phpPath' => 'php',
    'scriptPath' => '/var/www/application/public/',
    'jobs' => [
        [
            'command' => 'index.php application cron mail',
            'schedule' => '*/5 * * * *'
        ]
    ],

    // timeout in seconds for the process, defaults to 600 seconds
    'timeout' => 2
];