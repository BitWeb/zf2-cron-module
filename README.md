ZF2 implementation for [Cron/Cron](https://github.com/Cron/Cron)
===============
BitWeb ZF2 module for cron.

### Usage

#### Configuration
```php
array(
    'phpPath' => 'php',
    'scriptPath' => '/path/to/application/public/folder/',
    'jobs' => array(
        array(
            'command' => 'index.php application cron do-job',
            'schedule' => '* * * * *'
        )
    )
)
```


#### Run cron job
```sh
index.php cron module start
```
