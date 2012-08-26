
======================================================================

Open source Last.FM Milestone image generation script.
Original Source: https://github.com/MartyniP/lastfm-milestones

Created by MartyniP.co.uk, Copyright 2012.

======================================================================

lastfm-milestones
=====================

Version: 1.0.0
PHP Last.FM Milestones image generator


 Requirements
=====================
- php
	>- php-gd
	>- php MySQL
- MySQL
- Web Server

 Recommened
=====================
- phpMyAdmin
- Extra ttf font files
- We recommened only having web/ accessable by the web, to prevent non-command line execution which could cause heavy CPU load, and flood of requests to Last.FM API


  Installation
=====================

1) Copy code from sql.txt to your MySQL database.
2) Complete config.php with your details before runing milestones.php
3) chmod the directory 'web' to 777
4) Rename 'web/rename_to.htaccess' to 'web/.htaccess'
5) Point virtual host at folder 'web/'
6) Add cron task as show below:
0,30 * * * * /usr/bin/php /path/to/folder/milestones.php
	Note: This task will run twice every hour
7) (Optional) Change 404_error.psd to create your own custom error message.


 Tested
=====================
Working - Debian 6.0 (2.6.32-5-686)
Working - Ubuntu 10.04.4 LTS (2.6.9-39)
