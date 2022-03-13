<?php

//matamatika műveletek

function filter($array){

	array_filter(function($a){

		return $a*$a;
	});
}

filter([2,3,4]);