# TDD-SDK-PHP

[![StyleCI](https://styleci.io/repos/73277248/shield?branch=master)](https://styleci.io/repos/73277248)

## Overview
This SDK is a tool for supporting Test Driven Development (TDD) by PHP.
This SDK doesn't require installation of PHP's third party libraries, it works only with standard PHP.
You can be used just by installing PHP and this library.

## Feature
* Generate test code from Class-based source code
* Generate source code from test code
* Generate and add PHPDoc to the source code

## How to use
### Initialize
```
$ php composer.phar install
```

### Execution with CLI (Command Line Interface)

```
$ php tdd --generate test --input Tdd/Command/TestCode --output './tests/Command'
```
