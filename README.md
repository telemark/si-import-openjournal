si-import-openjournal
=====================

Import openjournal from Software Innovation to MongoDb

## Dependencies
Needs the following libraries
* php5
* php5-mongo
* php5-cli
* php5-json
* and a running mongodb server

## Configure
Edit config.php

## Run
Start importing journals
```
$ php import_journals.php
```
## Crontab
```
*/10 	* * * *	<user>	php <path>/import_journals.php
```
