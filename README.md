# TDD-SDK-PHP
## Overview
This SDK is a tool for supporting Test Driven Development (TDD) with PHP.
This SDK doesn't require installation of PHP's third party libraries, it works only with standard PHP.
You can be used just by installing PHP and this tool.

## Feature
* Generate test code from Class-based source code
* Generate source code from test code
* Generate and add PHPDoc to the source code

## How to use
### Initalize
```
$ php composer.phar install
```

### Execution with CLI (Command Line Interface)

```
$ php tdd create test --bootstrap=../autoload.php --classname=Tdd¥Command¥TestCase --output=../tests/Command
```
