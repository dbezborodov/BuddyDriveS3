=== BuddyDrive ===
Contributors: dbezborodov, imath
Donate link: 
Tags: Amazon, S3, BuddyDrive, BuddyPress, files, folders
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 1.1
License: GPLv2

Share files the BuddyPress way!

== Description ==

BuddyDriveS3 is a BuddyDrive fork that uses Amazon S3 to store files securely. It has same features as original BuddyDrive plugin. 
This plugin is available in english.

There is no support available for the plugin.

== Installation ==

You can download and install BuddyDriveS3 using the built in WordPress plugin installer. If you download BuddyDriveS3 manually, make sure it is uploaded to "/wp-content/plugins/buddydrive/".

Activate BuddyDrive in the "Plugins" admin panel using the "Network Activate" (or "Activate" if you are not running a network) link. 

Please note: it can't work along with original plugin at the same time! You have to "Deactivate" BuddyDrive if you have it installed and active, then "Activate" BuddyDriveS3. 

You will have an option to Move existing BuddyDrive files to Amazon S3 storage from "Settings" page.

== Important notes ==

If Amazon S3 keys are not specified on "Settings" page, the Plugin will work exactly like original BuddyDrive. 
If you used BuddyDriveS3 and then decided to switch to BuddyDrive, "Deactivate" BuddyDriveS3 plugin and then move all files from all sub-folders within your S3 bucket to "wp-content/upload/buddydrive/". This way you won't lose your files.

== Frequently Asked Questions ==

= If you have any question =

== Screenshots ==

1. User's BuddyDrive.
2. BuddyDrive Uploader
3. BuddyDrive embed file.
4. BuddyDrive Supervising area.
5. BuddyDrive settings page

== Changelog ==

= 1.1 =
* Amazon S3 instead of local storage

= 1.0 =
* files, folders management for users
* Requires BuddyPress 1.7
* language supported : french, english

== Upgrade Notice ==

= 1.0 =
first version of the plugin, so nothing particular.
