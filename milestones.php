<?php

/* 
======================================================================

Open source Last.FM Milestone image generation script.
Original Source: https://github.com/MartyniP/lastfm-milestones

Created by MartyniP.co.uk, Copyright 2012.

======================================================================
*/


include "config.php";

$q0 = mysql_query("SELECT * FROM users") or die('Error: '.mysql_error()."\n");
if (mysql_num_rows($q0) == 0) die();
while ($usersDB = mysql_fetch_assoc($q0)) {

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

$expired = array();
$cache = array();
$data = array();
$info = array();
$track = array();

//$uName = "MartyniP";
$uName = strtolower($usersDB['user']);
$uName = strip_tags($uName);
$uName = mysql_real_escape_string($uName);
$uName = str_replace("`", "", $uName);
$uName = str_replace("'", "", $uName);
$uName = trim($uName);

$glob_background = json_decode($usersDB['background'], true);
$glob_border = json_decode($usersDB['border'], true);
$glob_foreground = json_decode($usersDB['foreground'], true);
$glob_text_background = json_decode($usersDB['text_background'], true);

$glob_expire = $usersDB['expire'];
$glob_font = $usersDB['font'];

$extra = 0;

$q1 = mysql_query("SELECT * FROM cache WHERE user='".$uName."'") or die('Error: '.mysql_error()."\n");
if (mysql_num_rows($q1) != 0) {
while ($row = mysql_fetch_assoc($q1)) {
	$id = $row['id'];
	$user = $row['user'];
	$date = $row['date'];
	$expire = $row['expire'];
	$number = $row['number'];
	if ($expire > $date) {
		if ($config['debug'] == 1) echo $uName.":".$number."- using cache\n";
		$expired[$number] = 0;
		$cache[$number]['cache_artist'] = $row['cache_artist'];
		$cache[$number]['cache_track'] = $row['cache_track'];
		$cache[$number]['cache_date'] = $row['cache_date'];
	} else {
		if ($config['debug'] == 1) echo $uName.":".$number."- not using cache\n";
		$expired[$number] = 1;
	}
}
}

$xml = simplexml_load_file("http://ws.audioscrobbler.com/2.0/?method=user.getRecentTracks&user=".$uName."&api_key=".$config['apiKey']."&limit=1");
$pages = (int)$xml->recenttracks['totalPages'];

$numbers = range(0, $pages, $config['milestoneStep']);
foreach ($numbers as $number) {
//echo $pages."-".$number."\n";
if ($pages < $number) {
} else {
	if (!isset($expired[$number]) || $expired[$number] == 1) {
		$xml = simplexml_load_file("http://ws.audioscrobbler.com/2.0/?method=user.getRecentTracks&user=".$uName."&api_key=".$config['apiKey']."&limit=1&page=".($pages-$number+1));
		$xml = $xml->recenttracks->track;
		$data['artist'] = mysql_real_escape_string($xml->artist);
		$data['track'] = mysql_real_escape_string($xml->name);		
		$data['date'] = mysql_real_escape_string($xml->date['uts']);
		
		$now = time();
		$expire = strtotime("+".$glob_expire, $now);
		if (!isset($expired[$number])) {
			$q2 = mysql_query("INSERT INTO cache (user, number, cache_artist, cache_track, cache_date, date, expire, firstDate) VALUES ('".$uName."', '".$number."', '".$data['artist']."', '".$data['track']."', '".$data['date']."', '".$now."', '".$expire."', '".$now."')") or die('Error: '.mysql_error()."\n");
		} else {
			$q2 = mysql_query("UPDATE cache SET cache_artist='".$data['artist']."', cache_track='".$data['track']."', cache_date='".$data['date']."', date='".$now."', expire='".$expire."' WHERE user='".$uName."' AND number='".$number."'") or die('Error: '.mysql_error()."\n");
		}
	} else {
		$data['artist'] = $cache[$number]['cache_artist'];
		$data['track'] = $cache[$number]['cache_track'];
		$data['date'] = $cache[$number]['cache_date'];
	}
	foreach ($data as $k => $v) {
		$data[$k] = str_replace("\\'", "'", $v);
	}
	if ($number == 0) {
		$info[$number] = "1st track: ".strftime("%d %b %Y", (int)$data['date']);
	} else {
		$info[$number] = $number."th track: ".strftime("%d %b %Y", (int)$data['date']);
	}
	$track[$number] = $data['artist']." - ".$data['track'];
	if (strlen($track[$number]) > 40) {
		$extra++;
	}
}
}

// customizable variables
$font_file      = $config['font_dir'].''.$glob_font;
//$font_file      = 'arial.ttf';
//$font_color     = '255255255' ;

//header('Content-Type: image/png');

$height = 45*(count($track))+(15*$extra+45+33);

$img = imagecreatetruecolor(300, $height);

$font_color = imagecolorallocate($img, $glob_foreground[0], $glob_foreground[1], $glob_foreground[2]);
$textbg = imagecolorallocate($img, $glob_text_background[0], $glob_text_background[1], $glob_text_background[2]);


$bg = imagecolorallocate($img, $glob_background[0], $glob_background[1], $glob_background[2]);
$border = imagecolorallocate($img, $glob_border[0], $glob_border[1], $glob_border[2]);

imagefilledrectangle($img, 0, 0, 300, $height, $bg);

$i = 0;
$i++;
imagettftext($img, 14, 0, 102, 20, $font_color, $font_file, "Milestones");
$i++;
foreach ($track as $number => $text) {
	$i++;
	$text = $text;
	$text2 = "";
	$dualLine = 0;
	if (strlen($text) > 40) {
		imagefilledrectangle($img, 5, (15*$i)-14, 295, 30+((15*($i+1))-10), $border);
		imagefilledrectangle($img, 7, (15*$i)-12, 293, 28+((15*($i+1))-10), $textbg);
		$e = explode(" ", $text);
		$textOld = $text;
		$text = "";
		$text2 = "";
		$nextLine = false;
		foreach ($e as $k => $v) {
			if ((strlen($text)+strlen($v)) < 40 && $nextLine != true) {
				$text .= $v." ";
			} else {
				$nextLine = true;
				$text2 .= $v." ";
			}
		}
		//$text2 = substr($text, 40);
		//$text = substr($text, 0, 40);
		$dualLine = 1;
	} else {
		imagefilledrectangle($img, 5, (15*$i)-14, 295, 30+((15*$i)-10), $border);
		imagefilledrectangle($img, 7, (15*$i)-12, 293, 28+((15*$i)-10), $textbg);
	}
	imagettftext($img, 10, 0, 10, 15*$i, $font_color, $font_file, $info[$number]);
	$i++;
	imagettftext($img, 10, 0, 10, 15*$i, $font_color, $font_file, $text);
	if ($dualLine == 1) {
		$i++;
		imagettftext($img, 10, 0, 15, 15*$i, $font_color, $font_file, $text2);
	}
	$i++;
}
$i++;
$i++;
imagettftext($img, 13, 0, 40, 15*$i, $font_color, $font_file, "Created by MartyniP.co.uk");
imagettftext($img, 8, 0, 135, 15*$i+14, $font_color, $font_file, "Generated at ".date("H:i:s d/M/Y").".");

if (file_exists($config['wd'].$config['imgDir'].$uName.".png") && $config['keepOldImage'] == true) {
	$old = file_get_contents($config['wd'].$config['imgDir'].$uName.".png");
	file_put_contents($config['wd'].$config['imgDir'].$uName.".bak.png", $old);
}

imagepng($img, $config['wd'].$config['imgDir'].$uName.".png", 9);
imagedestroy($img);

if ($config['debug'] == 1) echo "Image complete for ".$uName."\n";
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
if ($config['debug'] == 1) echo "Took ".$total_time." seconds.\n\n";
$q3 = mysql_query("UPDATE users SET genTime='".$total_time."' WHERE user='".$uName."'") or die('Error: '.mysql_error()."\n");


//		print_r($xml);


} // close users mysql

/* 
======================================================================

Open source Last.FM Milestone image generation script.
Original Source: https://github.com/MartyniP/lastfm-milestones

Created by MartyniP.co.uk, Copyright 2012.

======================================================================
*/

?>
v
