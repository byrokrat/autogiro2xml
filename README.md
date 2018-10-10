# autogiro2xml

[![Packagist Version](https://img.shields.io/packagist/v/byrokrat/autogiro2xml.svg?style=flat-square)](https://packagist.org/packages/byrokrat/autogiro2xml)
[![Build Status](https://img.shields.io/travis/byrokrat/autogiro2xml/master.svg?style=flat-square)](https://travis-ci.org/byrokrat/autogiro2xml)

Command line utility for converting autogiro files to XML.

## Installation

### As a phar archive

Download the latest version of `autogiro2xml.phar` from the github
[releases](https://github.com/byrokrat/autogiro2xml/releases) page. Optionally
rename to `autogiro2xml` for a smoother experience.

### Through composer

Alternatively you may install `autogiro2xml` as a composer dependency using
something like

```shell
composer require byrokrat/autogiro2xml
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

Validate that autogiro files are valid using the `--format=validate` option.

```shell
autogiro2xml --format=validate <filename>
```

You may pass multiple file or directory names.

```shell
autogiro2xml --format=validate /dir/with/ag/files
```

Use the `--stop-on-failure` option to stop processing once a broken file is found.

```shell
autogiro2xml --format=validate --stop-on-filure /dir/with/ag/files
```

For the complete help see

```shell
autogiro2xml --help
```
