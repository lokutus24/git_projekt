<?php

//matamatika műveletek


$elements = [2,3,4];
array_filter($elements, function($a){

	return $a*$a;
});



for ($i=0; $i <count($elements) ; $i++) { 
	
	echo $elements[$i]."\n";
}