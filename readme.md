# Laravel Bookmark

Visual bookmark organizer application in Laravel

![screenshot](https://rivario.com/bookmark/images/bookmark.png ""bookmark"")

Working demo at [https://rivario.com/bookmark](https://rivario.com/bookmark)

## Features

- Webpage screenshot
- Tagging
- Public bookmark
- Pinning

## Used

- [backbone.js](http://backbonejs.org)
- [laravel](http://laravel.com)
- [PhantomJS](http://phantomjs.org)


## Requirements

	PHP >= 5.4
	MCrypt PHP Extension
	GD Library (>=2.0) or Imagick PHP extension (>=6.5.7)


## How to install
### Step 1: Get the code

```
$ git clone git://github.com/yhbyun/laravel-bookmark.git
$ cd laravel-bookmark
```


### Step 2: Create Virtual Machine
#### Install the applications

If you already installed, skip this process.

- [Install VirtualBox](https://www.virtualbox.org/wiki/Downloads)
- [Install Vagrant](http://www.vagrantup.com/downloads.html)

#### Create Virtual Machine

```
$ vagrant up
```

### Step 3: Install Dependencies and Populate Database

```
$ vagrant ssh
$ cd /vagrant
$ composer update
$ php artisan migrate --force
```

> PhantomJS ssl bug<br>
If there is no screenshot of `https` site and OS is Ubuntu 14.04, replace the installed PhantomJS(ver 1.7) with 2.0.0 version.

```
$ wget https://github.com/Pyppe/phantomjs2.0-ubuntu14.04x64/raw/master/bin/phantomjs
$ cp phantomjs bin/
```

### You're done!

```
http://192.168.22.10.xip.io/
```

-----

## Release History

v0.1.0 - First Release

## License

This is free software distributed under the terms of the MIT license

## Additional information

Inspired by and based on [bookmarkly.com](http://bookmarkly.com) & [readtrend.com](http://readtrend.com)

Any questions, feel free to [contact me](http://about.me/yhbyun).
