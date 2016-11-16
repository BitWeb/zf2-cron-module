[![Build Status](https://travis-ci.org/BitWeb/zf2-cron-module.svg?branch=master)](https://travis-ci.org/BitWeb/zf2-cron-module)
[![Coverage Status](https://coveralls.io/repos/BitWeb/zf2-cron-module/badge.png?branch=development)](https://coveralls.io/r/BitWeb/zf2-cron-module?branch=master)
[ZF2](https://github.com/zendframework/zf2) implementation for [Cron/Cron](https://github.com/Cron/Cron)
===============

### Working with Zend Framework 3? [We've got you covered](https://github.com/BitWeb/zf3-cron-module).

### Installation

Installation of CronModule uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require bitweb/zf2-cron-module:2.0.*
```

or add to your composer.json
```json
"require": {
  "bitweb/zf2-cron-module": "2.0.*"
}
```

Then add `BitWeb\CronModule` to your `config/application.config.php`

Installation without composer is not officially supported, and requires you to install and autoload
the dependencies specified in the `composer.json`.

### Configuration

Add to your configuration:

```php
'cronModule' => [
    'phpPath'    => 'php',
    'scriptPath' => '/path/to/application/public/folder/',
    'jobs'       => [
        [
            'command'  => 'index.php application cron do-job',
            'schedule' => '* * * * *'
        ],
        [
            'command'  => 'index.php application cron do-another-job',
            'schedule' => '0 * * * *'
        ]
    ]
]
```

General options: 

| Option | Description |
|--------|-------------|
| phpPath | path to the php executable, usually "php" |
| scriptPath | path to your applications public folder, defaults to your root aplication public folder |
| jobs | an array of jobs and their schedules |
| timeout | timeout for the cron job |

Options for cron jobs:

| Option | Description |
|--------|-------------|
| command | the php script command to be run |
| schedule | A valid [Cron schedule](https://en.wikipedia.org/wiki/Cron) |


You can also get the configutation file sample from `config` folder `config/cronModule.config.php.dist`.


#### Run cron job from command line
```sh
php index.php cron module start
```
