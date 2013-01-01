
WeedPhp
======================

PHP client for Weed-FS, a simple and highly scalable distributed file system.

Weed-FS:
http://code.google.com/p/weed-fs/

Status:
======================
Ready for testing / In Development

Testing:
======================
dev-master: [![Build Status](https://travis-ci.org/micjohnson/weed-php.png?branch=master)](https://travis-ci.org/micjohnson/weed-php)

Installation:
======================
### Installation through composer
If you already use composer this is the most simple way to use WeedPhp in your project.

Add to your composer.json
```
"require": {
    "micjohnson/weed-php": "v0.1"
}
```

Install through composer.phar
```
php composer.phar update micjohnson/weed-php
```

### Installation without composer
Clone the repo
```
git clone https://github.com/micjohnson/weed-php.git
```

Create an autoloader. There is an example in test/autoload.php

Then include the autoloader in your project.

```
include_once('weed-php/test/autoload.php');
```

Documentation:
======================
Create a WeedPhp object in your project, by passing the location of your master weed-fs server.
```
<?php

// ...

$weedPhp = new WeedPhp\Client('localhost:9333');

```

WeedPhp provides fucntions for most of the weed-fs REST calls.

### assign($count = 1, $replication = null)
Assign returns a json response including the file id your file(s) will use, and where to store the file, using store.  

When storing files with weed-fs the logic flow is assign then store. Assign reserves $count locations for files to be stored (using a single file id).    

A string can be passed for replication, defining the type of replication this file will be stored with. see http://code.google.com/p/weed-fs/#Rack-Aware_and_Data_Center-Aware_Replication  

Note that if you have a count greater than one, append _1, _2, _3, etc. on the file id for the subsequent files. The first file still uses just the file id.  

### store($volumeServerAddress, $fileId, $file)
This stores a single file.

$volumeServerAddress should be the location returned from assign().

$fileId should be the file id returned from assign().

$file raw file data to be stored.

### storeMultiple($volumeServerAddress, $fileId, array $files)
This is meant to be used when assigning multiple file versions. This will automatically loop through your files, and append _1, _2, etc. on the file id, as it stores the files.

$volumeServerAddress should be the location returned from assign().

$fileId should be the file id returned from assign().

$files array of raw file datas to be stored.

### lookup($volumeId)
Returns a json response with the locations where the volume is stored

$volumeId is the number before the comma in the fileId.

eg.  
fid = 3,01637037d6  
volumeId = 3

### retrieve($volumeServerAddress, $fileId)
Retrieves the raw file data from a volume server.  

$volumeServerAddress should be the location returned from assign(). 

$fileId should be the file id returned from assign().

### delete($volumeServerAddress, $fileId)
Deletes file from volume server. Note that there is not a deleteMultiple, and if you assigned/stored multiple files you should delete them each individually.  

$volumeServerAddress should be the location returned from assign().  

$fileId should be the file id returned from assign().  

### status()
Gets status from your weed-fs master server