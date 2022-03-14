<?php


$a = 10;
$b = 31;
$d = 124;
$elements = [$a, $b, $d];

$a = 20;
$b = 200;
$z = 37;

echo $z-$b-$a;
$elements = [$a, $b];

//for elements and write it.
for ($i=0; $i <count($elements) ; $i++) { 
	
	echo $elements[$i]."\n";
}

