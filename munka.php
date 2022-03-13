<?php

//for elements
$elements = [2,3,4];
array_filter($elements, function($a){

	return $a*$a;
});
