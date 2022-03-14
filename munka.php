<?php


$a = 10;
$b = 11;
$elements = [$a, $b];

//for elements and write it.
for ($i=0; $i <count($elements) ; $i++) { 
	
	echo $elements[$i]."\n";
}


//szorzas
$kicsi = 3;
$nagy = 23;

print $kicsi*$nagy;

// Osztás

$egyik = 25;
$masik = 5;
 print $egyik / $masik;


function method($a, $b){
	return $a + $b;
}

echo method($a, $b)."\n";

