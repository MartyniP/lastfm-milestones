<?php

/* 
======================================================================

Open source Last.FM Milestone image generation script.
Original Source: https://github.com/MartyniP/lastfm-milestones

Created by MartyniP.co.uk, Copyright 2012.

======================================================================
*/

// =============================================
// 	Edit setting below to your needs.
// =============================================

// MySQL Config
$config['dbhost'] = "localhost"; // MySQL database host
$config['dbuser'] = "root"; // MySQL database  user
$config['dbpass'] = "password"; // MySQL database password
$config['dbname'] = "milestones"; // MySQL database name

// Last.FM API Key
$config['apiKey'] = ""; 

// Directories
$config['wd'] = "/var/www/lastfm-milestones/"; // Working directory, root of git checkout
$config['font_dir'] = "/var/www/lastfm-milestones/fonts/"; // Font directory
$config['imgDir'] = "web/"; // Image directory

$config['debug'] = 1; // Display meaningless data, good for trouble shooting

$config['milestoneStep'] = 1000; // Milestone step, Examples: 1000, 10000, 2000
// Avoid mass flood of Last.FM by not using step lower than 1000, as this could result in a large number of requests 
// for accounts with more than 10000 played songs.

$config['keepOldImage'] = true; // Move the previous image to USERNAME.bak.png (true|false)





// =================================
// 	Do not edit below here.
// =================================




mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']) or die("Error: MySQL Failed to connect.\n");
mysql_select_db($config['dbname']) or die("Error: MySQL Failed to select DB.\n");

ini_set('display_errors', '1');
error_reporting(E_ALL);

if (!isset($config['apiKey']) || $config['apiKey'] == "") die("Error: Invalid Last.FM API Key. Get one at http://www.last.fm/api/account\n");
if (!isset($config['wd']) || $config['wd'] == "") die("Invalid Working diectory, please set in config.php\n");
if (!isset($config['imgDir']) || $config['imgDir'] == "") die("Error: Invalid image directory.\n");
if (!isset($config['font_dir']) || $config['font_dir'] == "") die("Error: Invalid font directory.\n");
if (!isset($config['milestoneStep']) || $config['milestoneStep'] == "") die("Error: Invalid image directory.\n");
if (!is_dir($config['wd'].$config['imgDir']) || !is_writable($config['wd'].$config['imgDir'])) die("Error: Image directory is not writable.\n");
if (!is_dir($config['wd'].$config['font_dir']) || !is_readable($config['wd'].$config['font_dir'])) die("Error: Font directory is not readble.\n");

/* 
======================================================================

Open source Last.FM Milestone image generation script.
Original Source: https://github.com/MartyniP/lastfm-milestones

Created by MartyniP.co.uk, Copyright 2012.

======================================================================
*/


?>
