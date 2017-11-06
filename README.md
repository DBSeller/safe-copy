# SafeCopy

A package to safely copy files from one place to another

## Install
```
composer require dbseller/safe-copy
```

## Usage

```php
$safeCopy = new \DBSeller\SafeCopy\SafeCopy("/path/from", "/path/to");

// @throws Exception on error
$safeCopy->execute();
```

## bin

```sh
vendor/bin/safe-copy /path/from /path/to
```
