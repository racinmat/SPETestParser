<?php
/**
 * Created by PhpStorm.
 * User: Azathoth
 * Date: 2. 1. 2016
 * Time: 18:49
 */

require_once 'classes.php';

//if (preg_match("~php~i", "PHP is the web scripting language of choice.", $matches)) {
//	echo var_dump($matches);
//} else {
//	echo "A match was not found.";
//}


//$test = []; //question => correct answer
//$test = json_decode(file_get_contents(__DIR__ . '/../tests.json'), true);
//var_dump($test);

//$array1 = array("id1" => "value1");
//
//$array2 = array("id2" => "value2", "id3" => "value3", "id4" => "value4");
//
//$array3 = array_merge($array1, $array2/*, $arrayN, $arrayN*/);
//$array4 = $array1 + $array2;
//
//echo '<pre>';
//var_dump($array3);
//var_dump($array4);
//echo '</pre>';


/** @var Question[] $allTests */
$allTests = json_decode(file_get_contents(__DIR__ . '/../tests.json'), true);
$currentTest = []; //question => correct answer
var_dump($allTests);
foreach ($allTests as $questionText => $question) {
	$answers = [];
	foreach ($question['answers'] as $answer) {
		$answers[] = new Answer($answer['text'], $answer['correct'], $answer['id'], $answer['tried']);
	}
	$questionObject = new Question($question['text'], $answers);
	$questionObject->selected = $question['selected'];
	$questionObject->correctAnswer = $question['correctAnswer'];
	$allTests[$questionText] = $questionObject;
}

var_dump($allTests);

var_dump(json_encode($allTests, JSON_PRETTY_PRINT));