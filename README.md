Diff Sniffer For Pull Requests
============================

[![PHP Version](https://img.shields.io/badge/php-%5E7.1-blue.svg)](https://packagist.org/packages/morozov/diff-sniffer-pull-request)
[![Latest Stable Version](https://poser.pugx.org/morozov/diff-sniffer-pull-request/v/stable)](https://packagist.org/packages/morozov/diff-sniffer-pull-request)
[[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/morozov/diff-sniffer-pull-request/badges/quality-score.png)](https://scrutinizer-ci.com/g/morozov/diff-sniffer-pull-request/)
[![Code Coverage](https://scrutinizer-ci.com/g/morozov/diff-sniffer-pull-request/badges/coverage.png)](https://scrutinizer-ci.com/g/morozov/diff-sniffer-pull-request/)
[![Build Status](https://travis-ci.org/morozov/diff-sniffer-pull-request.png)](https://travis-ci.org/morozov/diff-sniffer-pull-request)

This tool allows you using [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to validate coding standards of pull requests.

Installation
------------

The easiest way to use it is to download PHAR-package:
```
$ wget https://github.com/morozov/diff-sniffer-pull-request/releases/download/3.0.0/pull-request.phar
$ chmod +x pull-request.phar
```

Configuration
-------------

The configuration is optional and is mostly needed to access private repositories and in case of reaching anonymous GitHub API usage limit.

To edit configuration, copy the default configuration file and adjust it to your taste:
```
$ cp etc/config.dist.php etc/config.php
$ vi etc/config.php
```

Usage
-----
```
$ ./pull-request.phar username repository pull-request-number [--standard=standard-name]
```
For example:
```
$ ./pull-request.phar composer composer 2674

FILE: src/Composer/Downloader/DownloadManager.php
--------------------------------------------------------------------------------
FOUND 3 ERROR(S) AFFECTING 3 LINE(S)
--------------------------------------------------------------------------------
 182 | ERROR | Expected "if (...) {\n"; found "if(...) {\n"
 199 | ERROR | Expected "if (...) {\n"; found "if(...) {\n"
 240 | ERROR | Expected "if (...) {\n"; found "if(...) {\n"
--------------------------------------------------------------------------------
```
As `standard-name` you can use either one of [standards](https://github.com/squizlabs/PHP_CodeSniffer/tree/master/CodeSniffer/Standards) bundled with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) or cusom one by providing full path to the standard directory. PSR2 is used by default.
