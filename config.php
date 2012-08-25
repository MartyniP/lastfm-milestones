<?php

/* 
--------------------------------------------------------------------

Open source Last.FM Milestone image generation script.
Original Source: https://github.com/MartyniP/lastfm-milestones

Created by MartyniP.co.uk, Copyright 2012.

---------------------------------------------------------------------
*/

// ------------------------------------------
// 	Edit setting below to your needs.
// ------------------------------------------

$config['dbhost'] = "localhost";
$config['dbuser'] = "root";
$config['dbpass'] = "password";
$config['dbname'] = "milestones";

$config['apiKey'] = ""; 

$config['wd'] = "/var/www/lastfm-milestones/";
$config['imgDir'] = "web/";

$config['debug'] = 1;

$config['milestoneStep'] = 1000;

$config['keepOldImage'] = true;





// -----------------------------------
// 	Do not edit below here.
// -----------------------------------




mysql_connect($config['dbhost'], $config['dbuser'], $config['dbpass']) or die("Error: MySQL Failed to connect.\n");
mysql_select_db($config['dbname']) or die("Error: MySQL Failed to select DB.\n");

ini_set('display_errors', '1');
error_reporting(E_ALL);

if (!isset($config['apiKey']) || $config['apiKey'] == "") die("Error: Invalid Last.FM API Key. Get one at http://www.last.fm/api/account\n");
if (!isset($config['wd']) || $config['wd'] == "") die("Invalid Working diectory, please set in config.php\n");
if (!isset($config['imgDir']) || $config['imgDir'] == "") die("Error: Invalid image directory.\n");
if (!isset($config['milestoneStep']) || $config['milestoneStep'] == "") die("Error: Invalid image directory.\n");
if (!is_dir($config['wd'].$config['imgDir']) || !is_writable($config['wd'].$config['imgDir'])) die("Error: Image directory is not writable.\n");

/* 
--------------------------------------------------------------------

Open source Last.FM Milestone image generation script.
Original Source: https://github.com/MartyniP/lastfm-milestones

Created by MartyniP.co.uk, Copyright 2012.

---------------------------------------------------------------------
*/


?>
