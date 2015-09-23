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
* DOWNLOAD_URL (URL Where documents are downloaded from)
* DOWNLOAD_PATH (Directory where documents are moved internal on server)
* JOURNALS_FILES (Directory where ZIP-files from openjournal are placed)

## Fileshare
Create /mnt/journals
```
mkdir /mnt/journals
```

Add this to /etc/fstab
```
//hostname.domain.no/Postlister_export /mnt/journals cifs uid=<user>,gid=<user>,credentials=<path>.smbcred,iocharset=utf8,sec=ntlm 0 0
```

Add this to /path/to/.smbcred
```
username:<username>
password:<password>
```
Mount it
```
mount /mnt/journals
```

## Run
Start importing journals
```
$ php import_journals.php
```
## Crontab
```
*/10 	* * * *	<user>	php <path>/import_journals.php
```
