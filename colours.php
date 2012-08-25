<?php
$background = array(
'0'=>'255',
'1'=>'255',
'2'=>'255',
);
$border = array(
'0'=>'128',
'1'=>'128',
'2'=>'128',
);
$foreground = array(
'0'=>'0',
'1'=>'0',
'2'=>'0',
);

//print_r($background);
print(json_encode($background, JSON_FORCE_OBJECT));
echo "\n\n\n";

//print_r($border);
print(json_encode($border, JSON_FORCE_OBJECT));
echo "\n\n\n";

//print_r($foreground);
print(json_encode($foreground, JSON_FORCE_OBJECT));
echo "\n\n\n";
?>