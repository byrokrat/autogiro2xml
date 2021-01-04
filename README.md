![byrokrat](res/logo.svg)

# autogiro2xml

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/autogiro2xml.svg?style=flat-square)](https://packagist.org/packages/byrokrat/autogiro2xml)
[![Build Status](https://img.shields.io/travis/byrokrat/autogiro2xml/master.svg?style=flat-square)](https://travis-ci.com/github/byrokrat/autogiro2xml)

Command line utility for converting autogiro files to XML.

## Installation

### Using phive (recommended)

Install using [phive][1]:

```shell
phive install byrokrat/autogiro2xml
```

### As a phar archive

Download the latest version from the github [releases][2] page.

Optionally rename `autogiro2xml.phar` to `autogiro2xml` for a smoother experience.

### Using composer

Install as a [composer][3] dependency:

```shell
composer require byrokrat/autogiro2xml
```

This will make `autogiro2xml` avaliable as `vendor/bin/autogiro2xml`.

### From source

To build you need `make`

```shell
make
sudo make install
```

The build script uses [composer][3] to handle dependencies and [phive][1] to
handle build tools. If they are not installed as `composer` or `phive`
respectivly you can use something like

```shell
make COMPOSER_CMD=./composer.phar PHIVE_CMD=./phive.phar
```

## Usage

Transforming an autogiro file to XML.

```shell
autogiro2xml <filename>
```

Works well with \*nix streams and pipes.

```shell
cat filename | autogiro2xml > output.xml
```

Validate autogiro files using the `--format=validate` option.

```shell
autogiro2xml --format=validate <filename>
```

You may pass multiple file or directory names.

```shell
autogiro2xml --format=validate /dir/with/ag/files
```

Use the `--stop-on-failure` option to stop processing once a broken file is found.

```shell
autogiro2xml --format=validate --stop-on-failure /dir/with/ag/files
```

Parse a directory of autogiro files and save error descriptions

```shell
autogiro2xml --format=validate <dirname> 2> errors.txt
```

For the complete help see

```shell
autogiro2xml --help
```

[1]: <https://phar.io/>
[2]: <https://github.com/byrokrat/autogiro2xml/releases>
[3]: <https://getcomposer.org/>
