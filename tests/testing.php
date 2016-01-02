<?php
/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 2. 1. 2016
 * Time: 18:49
 */
//if (preg_match("~php~i", "PHP is the web scripting language of choice.", $matches)) {
//	echo var_dump($matches);
//} else {
//	echo "A match was not found.";
//}


$test = []; //question => correct answer
$test = json_decode(file_get_contents(__DIR__ . '/../tests.json'), true);
var_dump($test);