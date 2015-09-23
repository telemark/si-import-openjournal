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

## Installation

### MongDB
```
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
echo "deb http://repo.mongodb.org/apt/ubuntu precise/mongodb-org/3.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.0.list
echo "deb http://repo.mongodb.org/apt/ubuntu trusty/mongodb-org/3.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.0.list
sudo apt-get update
sudo apt-get install -y mongodb-org
sudo service mongod start
service mongod status
```

### php5
```
sudo apt-get -y install php5 php5-mongo php5-cli php5-json
```
### nginx
```
sudo apt-get install nginx
```

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
