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
- [phantom.js](http://phantomjs.org)



## Requirements

	PHP >= 5.3.7
	MCrypt PHP Extension


## How to install
### Step 1: Get the code
#### Option 1: Git Clone

	git clone git://github.com/yhbyun/laravel-bookmark.git
	
#### Option 2: Download the repository

    https://github.com/yhbyun/laravel-bookmark/archive/master.zip

### Step 2: Use Composer to install dependencies
#### Option 1: Composer is not installed globally

	curl -sS https://getcomposer.org/installer | php
	php composer.phar install

#### Option 2: Composer is installed globally

	composer install

If you haven't already, you might want to make [composer be installed globally](http://getcomposer.org/doc/00-intro.md#globally) for future ease of use.


### Step 3: Configure Database Settings

Now that you have the bookmark cloned and all the dependencies installed, you need to create a database and update the file `app/config/database.php`.

### Step 4: Configure Mail Settings

Now, you need to setup your mail settings by just opening and updating the following file `app/config/mail.php`.

This will be used to send password reset emails to the users.

### Step 5: Populate Database
Run these commands to create tables:

	php artisan migrate

### Step 6: Set Encryption Key
***In app/config/app.php***

```
/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| This key is used by the Illuminate encrypter service and should be set
| to a random, long string, otherwise these encrypted values will not
| be safe. Make sure to change it before deploying any application!
|
*/
```

	'key' => 'YourSecretKey!!!',

You can use artisan to do this

    php artisan key:generate


### Step 7: Make sure app/storage is writable by your web server.

If permissions are set correctly:

    chmod -R 775 app/storage

Should work, if not try

    chmod -R 777 app/storage

-----

## Screenshot

The screenshot daemon is in progress and unstable. I'll upload it ASAP.

## Release History

v0.1.0 - First Release

## License

This is free software distributed under the terms of the MIT license

## Additional information

Inspired by and based on [bookmarkly.com](http://bookmarkly.com) & [readtrend.com](http://readtrend.com)

Any questions, feel free to [contact me](http://about.me/yhbyun).
