Diff Sniffer For Pull Requests
============================

This tool allows you using [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) to validate coding standards of pull requests.

Installation
------------

The easiest way to use it is to download PHAR-package:
```
$ wget https://github.com/morozov/diff-sniffer-pull-request/releases/download/1.5.0/pull-request.phar
$ chmod +x pull-request.phar
```

Configuration
-------------

Currently validator requires GitHub authentication even for accessing public repositories. In order to obtain authentication token just run it for the first time and provide your username and password:
```
$ ./pull-request.phar
Username: morozov
Password: iloveyou
Configuration successfully saved.
```
Be aware that password is displayed in terminal during when you type it.

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
