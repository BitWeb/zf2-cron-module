[![Build Status](https://travis-ci.org/BitWeb/zf2-cron-module.svg?branch=master)](https://travis-ci.org/BitWeb/zf2-cron-module)
[![Coverage Status](https://coveralls.io/repos/BitWeb/zf2-cron-module/badge.png?branch=development)](https://coveralls.io/r/BitWeb/zf2-cron-module?branch=master)
ZF2 implementation for [Cron/Cron](https://github.com/Cron/Cron)
===============
BitWeb ZF2 module for cron.

### Usage

#### Configuration
```php
[
    'phpPath' => 'php',
    'scriptPath' => '/path/to/application/public/folder/',
    'jobs' => [
        [
            'command' => 'index.php application cron do-job',
            'schedule' => '* * * * *'
        ]
    ]
]
```


#### Run cron job from command line
```sh
index.php cron module start
```
