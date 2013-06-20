# WordPress Plugin BuddyDriveS3

The WordPress Plugin BuddyDriveS3 is a fork of BuddyDrive plugin, it uses Amazon S3 to store files instead of the server disk.

## Features

* The Plugin BuddyDriveS3 has same functionality as original [BuddyDrive plugin](http://wordpress.org/plugins/buddydrive/).
* Uses Amazon [Simple Storage Service](http://aws.amazon.com/s3/) to store files and encrypted SSL connections to upload/download files.
* It is fully-based on the WordPress [Plugin API](http://codex.wordpress.org/Plugin_API).
* Uses [PHPDoc](http://en.wikipedia.org/wiki/PHPDoc) conventions to document the code.
* Uses S3 PHP library from https://github.com/tpyo/amazon-s3-php-class.

## Installation

1. Copy the `buddydrives3` directory into your `wp-content/plugins` directory
2. Navigate to the *Plugins* dashboard page
3. Locate the menu item that reads *BuddyDrive*
4. Click on *Deactivate* if you found it
5. Locate the menu item that reads *BuddyDriveS3*
6. Click on *Activate*

This will activate the WordPress Plugin BuddyDriveS3. Then from *Settings* page you can Move existing BuddyDrive files (if any) to Amazon S3.

## Important notes

If Amazon S3 keys are not specified on *Settings* page, the Plugin will work exactly like original BuddyDrive. 
If you used BuddyDriveS3 and then decided to switch to BuddyDrive, *Deactivate* BuddyDriveS3 plugin and then move all files from all sub-folders within your S3 bucket to "wp-content/upload/buddydrive/". This way you won't lose your files.

## License

The WordPress Plugin BuddyDriveS3 is licensed under the GPL v2 or later.

> This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

> This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

> You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

