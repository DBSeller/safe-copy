# SafeCopy

A package to safely copy files from one place to another

## Usage

```php
$safeCopy = new \DBSeller\SafeCopy\SafeCopy("/path/from", "/path/to");

// @throws Exception on error
$safeCopy->execute();

```