# TDD-SDK-PHP

[![StyleCI](https://styleci.io/repos/73277248/shield?branch=master)](https://styleci.io/repos/73277248)

## Overview
This SDK is a tool for supporting Test Driven Development (TDD) by PHP.

This SDK doesn't require installation of PHP's third party libraries, it works only with standard PHP.

You can be used just by installing PHP and this library.

## Feature
* Generate test code from Class-based source code
* Generate source code from PHPUnit's test code

## How to use
### Initialize

1. Added `composer.json`

To use this tool, `composer` is required.

If `composer` is not installed, please install it.

After `composer` is installed, add the following to `composer.json`.


```composer.json
    "require-dev": {
        "SRAUFactory/TDD-SDK-PHP": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/SRAUFactory/TDD-SDK-PHP.git"
        }
    ]
```


2. Run composer install or update

```CLI
$ php composer.phar [install/update]
```

or

```CLI
$ composer [install/update]
```

### Run CLI (Command Line Interface)


```CLI
$ php tdd -h

Usage: php tdd [options]

Options:

  -g|--generate source|test  Generate source/test file.
  -h|--help                  Prints this usage information.
  -i|--input <path>          Import source/test `class` file path.
  -o|--output <path>         Export generated file to directory.
```

```Example
$ php tdd --generate test --input Tdd/Command/TestCode --output './tests/Command'
```